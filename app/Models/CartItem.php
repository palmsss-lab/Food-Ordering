<?php
// app/Models/CartItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_items';

    protected $fillable = [
        'cart_id',
        'menu_item_id',
        'quantity',
        'price',
        'special_instructions'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'float',
        'special_instructions' => 'string'
    ];

    protected $appends = ['subtotal'];

    /**
     * Get the cart that owns the item.
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Get the menu item.
     */
    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
    }

    /**
     * Get the subtotal for this item.
     */
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    /**
     * Update quantity with validation.
     */
    public function updateQuantity($quantity)
    {
        if ($quantity < 1) {
            return $this->delete();
        }
        
        // Check stock availability
        $menuItem = $this->menuItem;
        if ($menuItem && $menuItem->stock < $quantity) {
            throw new \Exception('Not enough stock available.');
        }
        
        $this->update(['quantity' => $quantity]);
        return $this;
    }
}