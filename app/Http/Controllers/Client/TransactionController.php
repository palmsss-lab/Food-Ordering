<?php

namespace App\Http\Controllers\Client;

use App\Models\Transaction;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
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
            ->with(['items', 'order'])
            ->firstOrFail();

        return view('client.client-profile.transactions.show', compact('transaction'));
    }

    /**
     * Download PDF receipt for a transaction
     */
    public function downloadReceipt($transactionNumber)
    {
        set_time_limit(120);

        $transaction = Transaction::where('transaction_number', $transactionNumber)
            ->where('user_id', Auth::id())
            ->with(['items', 'order'])
            ->firstOrFail();

        $pdf = Pdf::loadView('pdf.transaction-receipt', compact('transaction'))
            ->setPaper([0, 0, 340, 700], 'portrait');

        return $pdf->download("receipt-{$transaction->transaction_number}.pdf");
    }
}
