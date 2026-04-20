<?php

namespace App\Spokes;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderingSpoke
{
    public function onOrderPlaced(Order $order): void
    {
        Log::info('OrderingSpoke: order placed', [
            'order_id'     => $order->id,
            'order_number' => $order->order_number,
            'total'        => $order->total,
        ]);
    }

    public function onOrderCompleted(Order $order): void
    {
        Log::info('OrderingSpoke: order completed', [
            'order_id'     => $order->id,
            'order_number' => $order->order_number,
        ]);
    }

    public function onOrderCancelled(Order $order): void
    {
        Log::info('OrderingSpoke: order cancelled', [
            'order_id'     => $order->id,
            'order_number' => $order->order_number,
        ]);
    }
}
