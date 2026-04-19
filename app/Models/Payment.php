<?php
// app/Models/Payment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        // Whenever a payment record is saved, keep the parent order's
        // payment_status column in sync so both tables never diverge.
        static::saved(function (Payment $payment) {
            $order = $payment->order;
            if (!$order) return;

            // Don't overwrite a refund/partial_refund state that was set deliberately
            if (in_array($order->payment_status, ['refunded', 'partial_refund'])) return;

            $derived = match ($payment->payment_status) {
                'completed' => 'paid',
                'pending'   => $payment->payment_method === 'cash' ? 'cash on pickup' : 'pending',
                'failed'    => 'failed',
                'refunded'  => 'refunded',
                default     => null,
            };

            if ($derived && $order->payment_status !== $derived) {
                $order->timestamps = false; // don't bump updated_at for this housekeeping write
                $order->update(['payment_status' => $derived]);
                $order->timestamps = true;
            }
        });
    }

   protected $fillable = [
    'payment_number',
    'order_id',
    'user_id',
    'amount',
    'payment_method',
    'payment_status',
    'reference_number',
    'transaction_id',
    'payment_details',
    'gcash_number',
    'gcash_reference',
    'card_last_four',
    'card_type',
    'paid_at',
    'refunded_at',
    'notes',
    'failure_reason'
];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_details' => 'array',
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime'
    ];

    protected $appends = ['formatted_amount', 'status_color', 'method_badge'];

    /**
     * Get the order for this payment
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the user who made this payment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Format amount with peso sign
     */
    public function getFormattedAmountAttribute()
    {
        return '₱' . number_format($this->amount, 2);
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute()
    {
        return match($this->payment_status) {
            'pending' => 'yellow',
            'processing' => 'blue',
            'completed' => 'green',
            'failed' => 'red',
            'refunded' => 'gray',
            default => 'gray'
        };
    }

    /**
     * Get method badge styling
     */
    public function getMethodBadgeAttribute()
    {
        return match($this->payment_method) {
            'cash' => [
                'bg' => 'bg-yellow-100',
                'text' => 'text-yellow-800',
                'icon' => '💰'
            ],
            'gcash' => [
                'bg' => 'bg-blue-100',
                'text' => 'text-blue-800',
                'icon' => '📱'
            ],
            'card' => [
                'bg' => 'bg-purple-100',
                'text' => 'text-purple-800',
                'icon' => '💳'
            ],
            default => [
                'bg' => 'bg-gray-100',
                'text' => 'text-gray-800',
                'icon' => '💵'
            ]
        };
    }

    /**
     * Check if payment is pending
     */
    public function isPending()
    {
        return $this->payment_status === 'pending';
    }

    /**
     * Check if payment is processing
     */
    public function isProcessing()
    {
        return $this->payment_status === 'processing';
    }

    /**
     * Check if payment is completed
     */
    public function isCompleted()
    {
        return $this->payment_status === 'completed';
    }

    /**
     * Check if payment is failed
     */
    public function isFailed()
    {
        return $this->payment_status === 'failed';
    }

    /**
     * Check if payment is refunded
     */
    public function isRefunded()
    {
        return $this->payment_status === 'refunded';
    }

    /**
     * Check if payment method is cash
     */
    public function isCash()
    {
        return $this->payment_method === 'cash';
    }

    /**
     * Check if payment method is GCash
     */
    public function isGcash()
    {
        return $this->payment_method === 'gcash';
    }

    /**
     * Check if payment method is card
     */
    public function isCard()
    {
        return $this->payment_method === 'card';
    }

    /**
     * Generate unique payment number
     */
    public static function generatePaymentNumber()
    {
        $date = now()->format('Ymd');
        $lastPayment = self::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        if ($lastPayment) {
            $lastNumber = intval(substr($lastPayment->payment_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return 'PAY-' . $date . '-' . $newNumber;
    }

    /**
     * Mark payment as completed
     */
    public function markAsCompleted($referenceNumber = null)
    {
        $this->update([
            'payment_status' => 'completed',
            'reference_number' => $referenceNumber ?? $this->reference_number,
            'paid_at' => now()
        ]);
    }

    /**
     * Mark payment as failed
     */
    public function markAsFailed($reason = null)
    {
        $this->update([
            'payment_status' => 'failed',
            'failure_reason' => $reason,
            'paid_at' => null
        ]);
    }

    /**
     * Mark payment as refunded
     */
    public function markAsRefunded($reason = null)
    {
        $this->update([
            'payment_status' => 'refunded',
            'refunded_at' => now(),
            'notes' => $reason ? ($this->notes . "\nRefund: " . $reason) : $this->notes
        ]);
    }

    /**
     * Mark payment as processing
     */
    public function markAsProcessing()
    {
        $this->update([
            'payment_status' => 'processing'
        ]);
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /**
     * Scope for processing payments
     */
    public function scopeProcessing($query)
    {
        return $query->where('payment_status', 'processing');
    }

    /**
     * Scope for completed payments
     */
    public function scopeCompleted($query)
    {
        return $query->where('payment_status', 'completed');
    }

    /**
     * Scope for failed payments
     */
    public function scopeFailed($query)
    {
        return $query->where('payment_status', 'failed');
    }

    /**
     * Scope for refunded payments
     */
    public function scopeRefunded($query)
    {
        return $query->where('payment_status', 'refunded');
    }

    /**
     * Scope for cash payments
     */
    public function scopeCash($query)
    {
        return $query->where('payment_method', 'cash');
    }

    /**
     * Scope for GCash payments
     */
    public function scopeGcash($query)
    {
        return $query->where('payment_method', 'gcash');
    }

    /**
     * Scope for card payments
     */
    public function scopeCard($query)
    {
        return $query->where('payment_method', 'card');
    }

    /**
     * Scope for payments by order
     */
    public function scopeForOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }

    /**
     * Scope for payments by user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for today's payments
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope for payments with reference number
     */
    public function scopeWithReference($query, $reference)
    {
        return $query->where('reference_number', 'LIKE', "%{$reference}%");
    }
}