<?php
// app/Http/Controllers/Client/PaymentController.php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Show payment page for order
     */
    public function pay(Order $order)
    {
        // Verify order belongs to user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow payment for pending orders
        if ($order->order_status !== 'pending' || $order->payment_status !== 'pending') {
            return redirect()->route('client.orders.index')
                ->with('error', 'This order cannot be paid at this time.');
        }

        return view('client.payment.pay', compact('order'));
    }


    /**
     * Process payment (for GCash/Card)
     * FIXED: Now redirects to waiting page instead of immediate success
     */
    public function process(Request $request, Order $order)
    {
        // Verify order belongs to user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Validate request
        $request->validate([
            'payment_method' => 'required|in:gcash,card',
            'gcash_number' => 'required_if:payment_method,gcash|nullable|string|max:11',
            'card_number' => 'required_if:payment_method,card|nullable|string|max:16',
            'card_name' => 'required_if:payment_method,card|nullable|string|max:255',
            'card_expiry' => 'required_if:payment_method,card|nullable|string|max:7',
            'card_cvv' => 'required_if:payment_method,card|nullable|string|max:4',
        ]);
        
        DB::transaction(function () use ($request, $order) {
            // Create payment record
            $paymentData = [
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'payment_method' => $request->payment_method,
                'payment_status' => 'completed',
                'amount' => $order->total,
                'payment_number' => Payment::generatePaymentNumber(),
                'reference_number' => strtoupper($request->payment_method) . '-' . uniqid(),
                'paid_at' => now(),
                'gcash_number' => $request->gcash_number ?? null,
                'card_last_four' => null,
                'card_type' => null,
            ];

            if ($request->payment_method === 'card') {
                $paymentData['card_last_four'] = substr($request->card_number, -4);
                $paymentData['card_type'] = $this->detectCardType($request->card_number);
                $paymentData['payment_details'] = json_encode([
                    'card_type' => $paymentData['card_type'],
                    'card_last_four' => $paymentData['card_last_four']
                ]);
            } elseif ($request->payment_method === 'gcash') {
                $paymentData['gcash_reference'] = 'GCASH-' . uniqid();
                $paymentData['payment_details'] = json_encode([
                    'gcash_number' => $request->gcash_number
                ]);
            }

            Payment::create($paymentData);

            // FIXED: Only update payment_status to 'paid'
            // DO NOT change order_status here! It must stay 'pending'
            $order->update([
                'payment_status' => 'paid'
                // order_status remains 'pending' - requires admin confirmation
            ]);
        });

        // Redirect to waiting page instead of success
        if ($request->payment_method === 'gcash') {
            return redirect()->route('client.payments.gcash-waiting', $order)
                ->with('success', 'Payment successful! Your order is pending admin confirmation.');
        } else {
            return redirect()->route('client.payments.success', $order->order_number)
                ->with('success', 'Payment successful! Your order is pending admin confirmation.');
        }
    }

    /**
     * Handle cash payment
     */
    public function cash(Order $order)
    {
        // Verify order belongs to user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        DB::transaction(function () use ($order) {
            // Create cash payment record
            Payment::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'payment_method' => 'cash',
                'payment_status' => 'pending',
                'amount' => $order->total,
                'payment_number' => Payment::generatePaymentNumber(),
                'reference_number' => 'CASH-' . strtoupper(uniqid()),
            ]);

            // Update order status - cash stays as pending
            $order->update([
                'payment_status' => 'cash on pickup',
                // order_status stays 'pending'
            ]);
        });

        return redirect()->route('client.payments.pending', $order->order_number)
            ->with('success', 'Please proceed to the counter for payment.');
    }

    /**
     * Show payment pending page (for cash payments)
     */
    public function pending($orderNumber)
    {
        $order = Order::with(['items', 'payments'])
            ->where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('client.payment.payment-pending', compact('order'));
    }

    /**
     * Show GCash waiting page
     */
    public function gcashWaiting(Order $order)
    {
        // Verify order belongs to user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        return view('client.payment.gcash-waiting', compact('order'));
    }

    /**
     * Show payment success page
     */
    public function success($orderNumber)
    {
        $order = Order::with(['items', 'payments'])
            ->where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('client.payment.payment-success', compact('order'));
    }

    /**
     * Show payment failed page
     */
    public function failed($orderNumber)
    {
        $order = Order::with(['items', 'payments'])
            ->where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('client.payment.payment-failed', compact('order'));
    }

    /**
     * Download PDF receipt after successful payment
     */
    public function downloadReceipt($orderNumber)
    {
        set_time_limit(120);

        $order = Order::with(['items', 'payments'])
            ->where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $pdf = Pdf::loadView('pdf.payment-receipt', compact('order'))
            ->setPaper([0, 0, 340, 700], 'portrait');

        return $pdf->download("payment-receipt-{$order->order_number}.pdf");
    }

    /**
     * Check payment status (for AJAX polling)
     */
    public function checkStatus(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'payment_status' => $order->payment_status,
            'order_status' => $order->order_status
        ]);
    }

    /**
     * Detect card type from number
     */
    private function detectCardType($number)
    {
        $number = preg_replace('/\D/', '', $number);
        
        if (preg_match('/^4/', $number)) {
            return 'Visa';
        } elseif (preg_match('/^5[1-5]/', $number)) {
            return 'Mastercard';
        } elseif (preg_match('/^3[47]/', $number)) {
            return 'American Express';
        } elseif (preg_match('/^6(?:011|5)/', $number)) {
            return 'Discover';
        }
        
        return 'Unknown';
    }
}