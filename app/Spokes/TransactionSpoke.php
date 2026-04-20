<?php

namespace App\Spokes;

use App\Models\Order;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\Log;

class TransactionSpoke
{
    public function createFromOrder(Order $order): ?Transaction
    {
        $existing = Transaction::where('order_id', $order->id)->first();
        if ($existing) {
            return $existing;
        }

        $order->loadMissing(['items', 'latestPayment']);

        $transaction = Transaction::create([
            'transaction_number' => Transaction::generateTransactionNumber(),
            'order_number'       => $order->order_number,
            'order_id'           => $order->id,
            'user_id'            => $order->user_id,
            'customer_name'      => $order->customer_name,
            'customer_email'     => $order->customer_email,
            'customer_phone'     => $order->customer_phone,
            'payment_method'     => $order->latestPayment?->payment_method ?? 'cash',
            'payment_status'     => 'paid',
            'subtotal'           => $order->subtotal,
            'tax'                => $order->tax,
            'total'              => $order->total,
            'notes'              => $order->notes,
            'transaction_date'   => now(),
            'reference_number'   => $order->latestPayment?->reference_number,
        ]);

        foreach ($order->items as $item) {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'order_item_id'  => $item->id,
                'item_name'      => $item->item_name,
                'quantity'       => $item->quantity,
                'price'          => $item->price,
                'subtotal'       => $item->subtotal,
            ]);
        }

        Log::info('TransactionSpoke: transaction created', [
            'transaction_id'     => $transaction->id,
            'transaction_number' => $transaction->transaction_number,
            'order_id'           => $order->id,
        ]);

        return $transaction;
    }
}
