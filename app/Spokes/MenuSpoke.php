<?php

namespace App\Spokes;

use Illuminate\Support\Facades\Log;

class MenuSpoke
{
    public function deductStock(array $lockedMenuItems, $cartItems): void
    {
        foreach ($cartItems as $cartItem) {
            $menuItem = $lockedMenuItems[$cartItem->menu_item_id] ?? null;
            if ($menuItem) {
                $menuItem->decrement('stock', $cartItem->quantity);
                Log::info('MenuSpoke: stock deducted', [
                    'menu_item_id' => $cartItem->menu_item_id,
                    'quantity'     => $cartItem->quantity,
                ]);
            }
        }
    }
}
