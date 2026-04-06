<?php
// app/Http/Controllers/Admin/TransactionController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function transactions(Request $request)
    {
        $query = Transaction::with(['user' => function($q) {
            $q->withTrashed(); // Include soft-deleted users
        }]);
        
        // Apply date filters
        if ($request->filled('from_date')) {
            $query->whereDate('transaction_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('transaction_date', '<=', $request->to_date);
        }
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_number', 'like', "%{$search}%")
                  ->orWhere('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }
        
        $transactions = $query->orderBy('transaction_date', 'desc')->paginate(15)->withQueryString();
        
        // Transform transactions to show deleted user info
        $transactions->getCollection()->transform(function($transaction) {
            // If user was deleted, show anonymized info
            if ($transaction->user && $transaction->user->trashed()) {
                $transaction->customer_name = $transaction->user->name . ' (Account Deleted)';
            }
            return $transaction;
        });
        
        $totalSales = $query->sum('total');
        $totalOrders = $query->count();
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;
        
        return view('admin.orders.transactions', compact('transactions', 'totalSales', 'totalOrders', 'averageOrderValue'));
    }
    
    public function showTransaction($transactionNumber)
    {
        $transaction = Transaction::with(['items', 'user' => function($q) {
            $q->withTrashed();
        }])
            ->where('transaction_number', $transactionNumber)
            ->firstOrFail();
        
        // If user was deleted, show anonymized info
        if ($transaction->user && $transaction->user->trashed()) {
            $transaction->customer_name = $transaction->user->name . ' (Account Deleted)';
        }
        
        return view('admin.orders.show-transaction', compact('transaction'));
    }
}