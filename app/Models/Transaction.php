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
        'order_id',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'payment_method',
        'payment_status',
        'subtotal',
        'tax',
        'total',
        'refund_amount',
        'refund_reason',
        'refunded_by',
        'refunded_at',
        'notes',
        'transaction_date',
        'reference_number',
    ];

    protected $casts = [
        'subtotal'         => 'decimal:2',
        'tax'              => 'decimal:2',
        'total'            => 'decimal:2',
        'refund_amount'    => 'decimal:2',
        'transaction_date' => 'datetime',
        'refunded_at'      => 'datetime',
    ];

    protected $appends = [
        'formatted_total',
        'formatted_subtotal',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function refundedByAdmin()
    {
        return $this->belongsTo(User::class, 'refunded_by');
    }

    public function getFormattedTotalAttribute(): string
    {
        return '₱' . number_format($this->total, 2);
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return '₱' . number_format($this->subtotal, 2);
    }

    public function isRefunded(): bool
    {
        return $this->payment_status === 'refunded' || $this->payment_status === 'partial_refund';
    }

    public function isRefundable(): bool
    {
        return $this->payment_status === 'paid';
    }

    public static function generateTransactionNumber(): string
    {
        $date            = now()->format('Ymd');
        $lastTransaction = self::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        if ($lastTransaction) {
            $lastNumber = intval(substr($lastTransaction->transaction_number, -4));
            $newNumber  = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return 'TXN-' . $date . '-' . $newNumber;
    }
}
