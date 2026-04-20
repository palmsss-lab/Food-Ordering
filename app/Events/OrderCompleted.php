<?php

namespace App\Events;

use App\Models\Order;

class OrderCompleted
{
    public function __construct(public readonly Order $order) {}
}
