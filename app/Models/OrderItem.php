<?php
// app/Models/OrderItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'menu_item_id',
        'item_name',
        'quantity',
        'price',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'float',
        'subtotal' => 'float'
    ];

    protected $appends = ['formatted_price', 'formatted_subtotal'];

    /**
     * Get the order that owns this item
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the original menu item (may be null if deleted)
     */
    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
    }

    /**
     * Format price with peso sign
     */
    public function getFormattedPriceAttribute()
    {
        return '₱' . number_format($this->price, 2);
    }

    /**
     * Format subtotal with peso sign
     */
    public function getFormattedSubtotalAttribute()
    {
        return '₱' . number_format($this->subtotal, 2);
    }

    /**
     * Calculate subtotal
     */
    public function calculateSubtotal()
    {
        return $this->price * $this->quantity;
    }

    /**
     * Scope for items by order
     */
    public function scopeForOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }
}