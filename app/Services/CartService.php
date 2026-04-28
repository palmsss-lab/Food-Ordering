<?php
// app/Services/CartService.php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartService
{
    protected $cart;

    public function __construct()
    {
        if (Auth::check()) {
            $this->cart = Cart::firstOrCreate([
                'user_id' => Auth::id()
            ]);
        }
    }

    public function getCart()
    {
        if ($this->cart) {
            return $this->cart->load('items.menuItem');
        }
        return null;
    }

    public function getItems()
    {
        if (!$this->cart) {
            return collect();
        }

        return $this->cart->items()->with('menuItem')->whereHas('menuItem')->get()
            ->filter(fn($item) => $item->menuItem !== null)
            ->values();
    }

    // In your CartService.php addItem method
    public function addItem(MenuItem $menuItem, $quantity = 1, $instructions = null)
    {
        if (!$this->cart) {
            throw new \Exception('User not authenticated');
        }

        // Check if item already exists in cart
        $cartItem = $this->cart->items()
            ->where('menu_item_id', $menuItem->id)
            ->first();

        if ($cartItem) {
            // Calculate total quantity if adding more
            $newQuantity = $cartItem->quantity + $quantity;
            
            // Check stock for total quantity
            if ($menuItem->stock < $newQuantity) {
                $availableToAdd = $menuItem->stock - $cartItem->quantity;
                throw new \Exception("Cannot add {$quantity} more. You already have {$cartItem->quantity} in cart. Only {$availableToAdd} more available.");
            }
            
            // Update quantity
            $cartItem->update([
                'quantity' => $newQuantity,
            ]);
            
            return $cartItem;
        } else {
            // Check stock for new item
            if ($menuItem->stock < $quantity) {
                throw new \Exception("Cannot add {$quantity} items. Only {$menuItem->stock} available in stock.");
            }
            
            // Add new item
            return $this->cart->items()->create([
                'menu_item_id' => $menuItem->id,
                'quantity' => $quantity,
                'price' => $menuItem->price,
            ]);
        }
    }

    public function updateQuantity($itemId, $quantity)
    {
        if (!$this->cart) {
            throw new \Exception('User not authenticated');
        }

        $cartItem = $this->cart->items()->findOrFail($itemId);
        
        if ($quantity < 1) {
            return $cartItem->delete();
        }

        if ($cartItem->menuItem->stock < $quantity) {
            throw new \Exception("Not enough stock. Only {$cartItem->menuItem->stock} available.");
        }

        return $cartItem->update(['quantity' => $quantity]);
    }

    public function removeItem($itemId)
    {
        if (!$this->cart) {
            return false;
        }

        return $this->cart->items()->find($itemId)?->delete();
    }

    public function clearCart()
    {
        if (!$this->cart) {
            return false;
        }

        return $this->cart->items()->delete();
    }

    public function getTotal()
    {
        if (!$this->cart) {
            return 0;
        }

        return $this->cart->items->sum(function($item) {
            return $item->price * $item->quantity;
        });
    }

    public function getItemCount()
    {
        if (!$this->cart) {
            return 0;
        }

        return $this->cart->items->sum('quantity');
    }

    public function isEmpty()
    {
        return $this->getItemCount() === 0;
    }


    /**
     * Get cart items formatted for checkout
     */
    public function getItemsForCheckout()
    {
        $cart = $this->getCart();
        
        if (!$cart) {
            return [];
        }
        
        $cartItems = CartItem::with('menuItem')
            ->where('cart_id', $cart->id)
            ->get();
        
        return $cartItems->map(function($item) {
            return [
                'id' => $item->menu_item_id,
                'name' => $item->menuItem->name,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $item->price * $item->quantity,
            ];
        })->toArray();
    }
}