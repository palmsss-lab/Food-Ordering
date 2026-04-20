<?php

namespace App\Events;

use App\Models\Order;

class CashOrderPaid
{
    public function __construct(public readonly Order $order) {}
}
