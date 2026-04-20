<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

class OrderPlaced
{
    public function __construct(
        public readonly Order      $order,
        public readonly array      $lockedMenuItems,
        public readonly Collection $cartItems,
    ) {}
}
