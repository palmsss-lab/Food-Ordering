<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionItem extends Model
{
    use HasFactory;

    protected $table = 'transaction_items';

    protected $fillable = [
        'transaction_id',
        'order_item_id',
        'item_name',
        'quantity',
        'price',
        'subtotal',
    ];

    protected $casts = [
        'price' => 'float',
        'subtotal' => 'float',
        'quantity' => 'integer',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}