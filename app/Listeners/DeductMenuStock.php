<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Spokes\MenuSpoke;

class DeductMenuStock
{
    public function __construct(private MenuSpoke $menuSpoke) {}

    public function handle(OrderPlaced $event): void
    {
        $this->menuSpoke->deductStock($event->lockedMenuItems, $event->cartItems);
    }
}
