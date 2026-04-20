<?php

namespace App\Spokes;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentSpoke
{
    public function onPaymentProcessed(Order $order, Payment $payment): void
    {
        Log::info('PaymentSpoke: payment processed', [
            'order_id'       => $order->id,
            'order_number'   => $order->order_number,
            'payment_id'     => $payment->id,
            'payment_method' => $payment->payment_method,
            'amount'         => $payment->amount,
        ]);
    }
}
