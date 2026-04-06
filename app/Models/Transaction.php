<?php
// app/Models/Transaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'payment_method',
        'payment_status',
        'subtotal',
        'tax',
        'total',
        'notes',
        'transaction_date',
        'reference_number',
    ];

    protected $casts = [
        'subtotal' => 'float',
        'tax' => 'float',
        'total' => 'float',
        'transaction_date' => 'datetime',
    ];

    protected $appends = [
        'formatted_total',
        'formatted_subtotal',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function getFormattedTotalAttribute()
    {
        return '₱' . number_format($this->total, 2);
    }

    public function getFormattedSubtotalAttribute()
    {
        return '₱' . number_format($this->subtotal, 2);
    }

    public static function generateTransactionNumber()
    {
        $date = now()->format('Ymd');
        $lastTransaction = self::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        if ($lastTransaction) {
            $lastNumber = intval(substr($lastTransaction->transaction_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return 'TXN-' . $date . '-' . $newNumber;
    }
}