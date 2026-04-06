<?php
// app/Http/Controllers/Client/OrderController.php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display user's orders with tabs
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'pending');
        
        $orders = Order::with(['items', 'payments' => function($q) {
                $q->latest();
            }])
            ->where('user_id', Auth::id())
            ->orderBy('ordered_at', 'desc')
            ->get();
        
        // Force correct display status for each order
        foreach ($orders as $order) {
            // For GCash/Card orders that are in preparing/confirmed but not confirmed by admin
            if (in_array($order->payment_method, ['gcash', 'card']) && 
                !$order->admin_confirmed_at && 
                in_array($order->order_status, ['preparing', 'confirmed'])) {
                
                // Override the display_status by adding a temporary attribute
                $order->display_status = 'pending';
            }
            
            // For Cash orders that are in preparing but not confirmed by admin
            if ($order->payment_method === 'cash' && 
                !$order->admin_confirmed_at && 
                $order->order_status === 'preparing') {
                
                // Override the display_status
                $order->display_status = 'pending';
            }
        }
        
        return view('client.orders.index', compact('orders', 'tab'));
    }

    /**
     * Show single order details
     */
    public function show($orderNumber)
    {
        $order = Order::with(['items', 'payments'])
            ->where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('client.orders.show', compact('order'));
    }

    /**
     * Mark order as picked up (for ready orders)
     */
    public function markAsPickedUp(Request $request, Order $order)
    {
        // Verify order belongs to user
        if ($order->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized access.');
        }

        // Only allow if order is ready
        if ($order->order_status !== 'ready') {
            return back()->with('error', 'Order is not ready for pickup.');
        }

        DB::beginTransaction();
        
        try {
            // Mark order as completed
            $order->update([
                'order_status' => 'completed',
                'completed_at' => now()
            ]);

            // For GCash/Card orders, create transaction immediately
            // For Cash orders, transaction will be created when admin marks as paid
            if ($order->payment_method !== 'cash') {
                $existingTransaction = Transaction::where('order_number', $order->order_number)->first();
                
                if (!$existingTransaction) {
                    $transaction = Transaction::create([
                        'transaction_number' => Transaction::generateTransactionNumber(),
                        'order_number' => $order->order_number,
                        'user_id' => $order->user_id,
                        'customer_name' => $order->customer_name,
                        'customer_email' => $order->customer_email,
                        'customer_phone' => $order->customer_phone,
                        'payment_method' => $order->payment_method,
                        'payment_status' => 'paid',
                        'subtotal' => $order->subtotal,
                        'tax' => $order->tax,
                        'total' => $order->total,
                        'notes' => $order->notes,
                        'transaction_date' => now(),
                        'reference_number' => $order->latestPayment?->reference_number,
                    ]);

                    // Copy order items to transaction items
                    foreach ($order->items as $item) {
                        TransactionItem::create([
                            'transaction_id' => $transaction->id,
                            'order_item_id' => $item->id,
                            'item_name' => $item->item_name,
                            'quantity' => $item->quantity,
                            'price' => $item->price,
                            'subtotal' => $item->subtotal,
                            'special_instructions' => $item->special_instructions,
                        ]);
                    }
                }
            }

            DB::commit();

            $message = $order->payment_method === 'cash' 
                ? 'Thank you for picking up your order! Payment confirmation pending.'
                : 'Thank you for picking up your order! Transaction recorded.';

            return redirect()->route('client.orders.index', ['tab' => 'completed'])
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update order status.');
        }
    }

    /**
     * Show receipt for an order (API endpoint)
     */
    public function showReceipt(Order $order)
    {
        // Verify order belongs to user
        if ($order->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $order->load(['items', 'payments']);
        $latestPayment = $order->latestPayment;
        
        return response()->json([
            'id' => $order->id,
            'order_number' => $order->order_number,
            'date' => $order->updated_at->timezone('Asia/Manila')->format('F d, Y h:i A'),
            'items' => $order->items->map(function($item) {
                return [
                    'name' => $item->item_name,
                    'quantity' => (int)$item->quantity,
                    'price' => (float)$item->price,
                    'subtotal' => (float)$item->subtotal
                ];
            }),
            'subtotal' => (float)$order->subtotal,
            'tax' => (float)$order->tax,
            'total' => (float)$order->total,
            'payment_method' => $latestPayment ? $latestPayment->payment_method : null,
            'payment_status' => $order->payment_status,
            'customer_name' => $order->customer_name,
            'order_status' => $order->order_status,
            'reference_number' => $latestPayment ? $latestPayment->reference_number : null
        ]);
    }

    /**
     * Cancel order (if still pending)
     */
    public function cancel(Order $order)
    {
        // Verify order belongs to user
        if ($order->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized access.');
        }

        // Only allow cancellation if order is pending AND not yet confirmed by admin
        if ($order->order_status !== 'pending') {
            return back()->with('error', 'Only pending orders can be cancelled.');
        }

        // Check if already confirmed by admin
        if ($order->admin_confirmed_at) {
            return back()->with('error', 'This order has already been confirmed by admin and cannot be cancelled.');
        }

        DB::beginTransaction();

        try {
            // Restore stock for each item
            foreach ($order->items as $item) {
                if ($item->menu_item_id) {
                    $menuItem = \App\Models\MenuItem::find($item->menu_item_id);
                    if ($menuItem) {
                        $menuItem->increment('stock', $item->quantity);
                    }
                }
            }

            // Update any pending payments to failed
            foreach ($order->payments as $payment) {
                if ($payment->payment_status === 'pending') {
                    $payment->update([
                        'payment_status' => 'failed',
                        'failure_reason' => 'Order cancelled by customer'
                    ]);
                }
            }

            $order->update([
                'order_status' => 'cancelled',
                'cancelled_at' => now(),
                'payment_status' => 'failed'
            ]);

            DB::commit();

            return redirect()->route('client.orders.index', ['tab' => 'pending'])
                ->with('success', 'Order cancelled successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel order.');
        }
    }

    /**
     * Check for order updates (polling)
     */
    public function checkUpdates(Request $request)
    {
        $lastUpdate = $request->last_update ?? 0;
        $lastUpdateDate = date('Y-m-d H:i:s', $lastUpdate / 1000);
        
        $updatedOrders = Order::where('user_id', Auth::id())
            ->where(function($query) use ($lastUpdateDate) {
                $query->where('updated_at', '>', $lastUpdateDate)
                    ->orWhere('admin_confirmed_at', '>', $lastUpdateDate);
            })
            ->get()
            ->map(function($order) {
                $paymentMethod = $order->latestPayment ? $order->latestPayment->payment_method : null;
                
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => $order->order_status,
                    'display_status' => $order->display_status,
                    'payment_status' => $order->payment_status,
                    'payment_method' => $paymentMethod,
                    'admin_confirmed_at' => $order->admin_confirmed_at,
                    'updated_at' => $order->updated_at
                ];
            });
        
        return response()->json([
            'updated_orders' => $updatedOrders,
            'timestamp' => now()
        ]);
    }

        

}