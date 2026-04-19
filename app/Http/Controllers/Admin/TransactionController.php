<?php
// app/Http/Controllers/Admin/TransactionController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function transactions(Request $request)
    {
        $query = Transaction::with(['user' => fn($q) => $q->withTrashed()]);

        if ($request->filled('from_date')) {
            $query->whereDate('transaction_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('transaction_date', '<=', $request->to_date);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_number', 'like', "%{$search}%")
                  ->orWhere('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->paginate(15)->withQueryString();

        $transactions->getCollection()->transform(function ($transaction) {
            if ($transaction->user && $transaction->user->trashed()) {
                $transaction->customer_name = $transaction->user->name . ' (Account Deleted)';
            }
            return $transaction;
        });

        // Aggregate stats on the full filtered query (re-run without pagination)
        $statsQuery   = Transaction::when($request->filled('from_date'), fn($q) => $q->whereDate('transaction_date', '>=', $request->from_date))
                                   ->when($request->filled('to_date'),   fn($q) => $q->whereDate('transaction_date', '<=', $request->to_date))
                                   ->when($request->filled('search'),    fn($q) => $q->where(function ($sq) use ($request) {
                                       $sq->where('transaction_number', 'like', "%{$request->search}%")
                                          ->orWhere('order_number', 'like', "%{$request->search}%")
                                          ->orWhere('customer_name', 'like', "%{$request->search}%");
                                   }));

        $totalSales        = (clone $statsQuery)->where('payment_status', 'paid')->sum('total');
        $totalRefunded     = (clone $statsQuery)->where('payment_status', 'refunded')->sum('refund_amount');
        $totalOrders       = (clone $statsQuery)->count();
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        return view('admin.orders.transactions', compact(
            'transactions', 'totalSales', 'totalRefunded', 'totalOrders', 'averageOrderValue'
        ));
    }

    public function showTransaction(string $transactionNumber)
    {
        $transaction = Transaction::with(['items', 'order', 'refundedByAdmin', 'user' => fn($q) => $q->withTrashed()])
            ->where('transaction_number', $transactionNumber)
            ->firstOrFail();

        if ($transaction->user && $transaction->user->trashed()) {
            $transaction->customer_name = $transaction->user->name . ' (Account Deleted)';
        }

        return view('admin.orders.show-transaction', compact('transaction'));
    }

    public function refund(Request $request, Transaction $transaction)
    {
        // Guard: already refunded
        if ($transaction->isRefunded()) {
            return back()->with('error', 'This transaction has already been refunded.');
        }

        $validated = $request->validate([
            'refund_amount' => [
                'required',
                'numeric',
                'min:1',
                "max:{$transaction->total}",
            ],
            'refund_reason' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'refund_amount.max'       => "Refund amount cannot exceed the transaction total of ₱" . number_format($transaction->total, 2) . ".",
            'refund_reason.min'       => 'Please provide a more detailed reason (at least 10 characters).',
        ]);

        $refundAmount  = (float) $validated['refund_amount'];
        $isPartial     = $refundAmount < (float) $transaction->total;
        $newStatus     = $isPartial ? 'partial_refund' : 'refunded';

        DB::beginTransaction();

        try {
            // Update the transaction record
            $transaction->update([
                'payment_status' => $newStatus,
                'refund_amount'  => $refundAmount,
                'refund_reason'  => $validated['refund_reason'],
                'refunded_by'    => Auth::id(),
                'refunded_at'    => now(),
            ]);

            // Mirror the refund on the linked Order if it exists
            $order = $transaction->order ?? Order::where('order_number', $transaction->order_number)->first();
            if ($order && !$order->isRefunded()) {
                $order->processRefund(
                    $validated['refund_reason'],
                    $refundAmount,
                    Auth::id()
                );
            }

            DB::commit();

            $label = $isPartial
                ? 'Partial refund of ₱' . number_format($refundAmount, 2)
                : 'Full refund';

            return redirect()
                ->route('admin.orders.transactions')
                ->with('success', "{$label} for transaction {$transaction->transaction_number} has been processed successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process refund. Please try again.');
        }
    }
}
