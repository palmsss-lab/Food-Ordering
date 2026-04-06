<?php

namespace App\Http\Controllers\Client;

use App\Models\Transaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display user's transaction history
     */
    public function transactions()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->orderBy('transaction_date', 'desc')
            ->with('items')
            ->paginate(10);
        
        return view('client.client-profile.transactions.index', compact('transactions'));
    }

    /**
     * Show single transaction details
     */
    public function showTransaction($transactionNumber)
    {
        $transaction = Transaction::where('transaction_number', $transactionNumber)
            ->where('user_id', Auth::id())
            ->with('items')
            ->firstOrFail();

        return view('client.client-profile.transactions.show', compact('transaction'));
    }
}
