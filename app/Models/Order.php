<?php
// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Order extends Model
{
    use HasFactory;


    // In app/Models/Order.php, add this method:

protected static function booted()
{
    static::updating(function ($order) {
        if ($order->isDirty('order_status')) {
            $oldStatus = $order->getOriginal('order_status');
            $newStatus = $order->order_status;
            
            Log::info('Order status changing', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'admin_confirmed_at' => $order->admin_confirmed_at,
                'payment_method' => $order->payment_method,
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'user_id' => Auth::id()
            ]);
        }
    });
}

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'order_type',
        // 'payment_method', // REMOVED - now stored in payments table
        'payment_status',
        'rejection_reason',
        'refund_reason',
        'refund_amount',
        'refunded_by',
        'order_status',
        'subtotal',
        'tax',
        'discount',
        'total',
        'notes',
        'ordered_at',
        'confirmed_at',
        'prepared_at',
        'ready_at',
        'completed_at',
        'cancelled_at',
        'refunded_at',
        'admin_confirmed_at' // ADD THIS - track when admin confirms the order
    ];

    protected $casts = [
        'subtotal' => 'float',
        'tax' => 'float',
        'discount' => 'float',
        'total' => 'float',
        'refund_amount' => 'float',
        'ordered_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'prepared_at' => 'datetime',
        'ready_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'refunded_at' => 'datetime',
        'admin_confirmed_at' => 'datetime' // ADD THIS
    ];

    protected $appends = [
        'formatted_total',
        'formatted_subtotal',
        'status_color',
        'payment_status_color',
        'formatted_refund_amount',
        'is_refundable',
        'payment_method',
        'payment_method_badge',
        'display_status', // ADD THIS
        'payment_display_name', // ADD THIS
        'payment_badge_class', // ADD THIS
        'needs_payment', // ADD THIS
        'awaiting_confirmation' // ADD THIS
    ];

    /**
     * Get the user that owns the order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for this order
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the payments for this order
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the latest payment
     */
    public function latestPayment()
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    /**
     * Get payment method from latest payment (for backward compatibility)
     */
    public function getPaymentMethodAttribute()
    {
        $latestPayment = $this->latestPayment;
        return $latestPayment ? $latestPayment->payment_method : null;
    }

    /**
     * Get display status based on payment method and admin confirmation
     * All orders need admin confirmation before moving to preparing
     */
    /**
 * Get display status based on payment method and admin confirmation
 * All orders need admin confirmation before moving to preparing
 */
    public function getDisplayStatusAttribute()
    {
        // If order is cancelled/completed/etc, return actual status
        if (!in_array($this->order_status, ['pending', 'confirmed', 'preparing'])) {
            return $this->order_status;
        }
        
        // IMPORTANT: If admin_confirmed_at is NULL, order should be in pending tab
        // regardless of what order_status says
        if (!$this->admin_confirmed_at) {
            return 'pending';
        }
        
        // If admin_confirmed_at is set, order can show as preparing
        if ($this->admin_confirmed_at) {
            return 'preparing';
        }
        
        // Fallback
        return $this->order_status;
    }

    /**
     * Get payment display name
     */
    public function getPaymentDisplayNameAttribute()
    {
        $paymentMethod = $this->payment_method;
        
        return match($paymentMethod) {
            'cash' => 'Cash on Pickup',
            'gcash' => 'GCash',
            'card' => 'Card Payment',
            default => ucfirst($paymentMethod ?? 'Unknown')
        };
    }

    /**
     * Get payment badge class for UI
     */
    public function getPaymentBadgeClassAttribute()
    {
        $paymentMethod = $this->payment_method;
        
        return match($paymentMethod) {
            'cash' => 'bg-orange-100 text-orange-700',
            'gcash' => 'bg-blue-100 text-blue-700',
            'card' => 'bg-purple-100 text-purple-700',
            default => 'bg-gray-100 text-gray-700'
        };
    }

    /**
     * Check if order needs payment (online payment for non-cash orders)
     */
    public function getNeedsPaymentAttribute()
    {
        $paymentMethod = $this->payment_method;
        
        // Cash on Pickup doesn't need online payment
        if ($paymentMethod === 'cash') {
            return false;
        }
        
        // GCash and Card need payment if not paid yet
        return $this->payment_status !== 'paid';
    }

    /**
     * Check if order is awaiting admin confirmation
     */
    public function getAwaitingConfirmationAttribute()
    {
        return $this->order_status === 'pending' && !$this->admin_confirmed_at;
    }

    /**
     * Get payment method badge for UI
     */
    public function getPaymentMethodBadgeAttribute()
    {
        $method = $this->payment_method;
        
        return match($method) {
            'cash' => [
                'bg' => 'bg-orange-100', // Changed from yellow to orange for cash
                'text' => 'text-orange-800',
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
     * Format total with peso sign
     */
    public function getFormattedTotalAttribute()
    {
        return '₱' . number_format($this->total, 2);
    }

    /**
     * Format subtotal with peso sign
     */
    public function getFormattedSubtotalAttribute()
    {
        return '₱' . number_format($this->subtotal, 2);
    }

    /**
     * Format refund amount with peso sign
     */
    public function getFormattedRefundAmountAttribute()
    {
        return $this->refund_amount ? '₱' . number_format($this->refund_amount, 2) : null;
    }

    /**
     * Check if order can be refunded
     */
    public function getIsRefundableAttribute()
    {
        return $this->payment_status === 'paid' &&
               in_array($this->order_status, ['completed', 'ready']) &&
               !$this->isRefunded();
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute()
    {
        return match($this->order_status) {
            'pending' => 'yellow',
            'confirmed' => 'blue',
            'preparing' => 'purple',
            'ready' => 'green',
            'completed' => 'gray',
            'cancelled', 'rejected' => 'red',
            'refunded' => 'indigo',
            default => 'gray'
        };
    }

    /**
     * Get payment status color for UI
     */
    public function getPaymentStatusColorAttribute()
    {
        return match($this->payment_status) {
            'pending' => 'yellow',
            'cash on pickup' => 'orange',
            'paid' => 'green',
            'failed' => 'red',
            'refunded', 'partial_refund' => 'indigo',
            default => 'gray'
        };
    }

    /**
     * Check if order is pending
     */
    public function isPending()
    {
        return $this->order_status === 'pending';
    }

    /**
     * Check if order is confirmed
     */
    public function isConfirmed()
    {
        return $this->order_status === 'confirmed';
    }

    /**
     * Check if order is preparing
     */
    public function isPreparing()
    {
        return $this->order_status === 'preparing';
    }

    /**
     * Check if order is ready
     */
    public function isReady()
    {
        return $this->order_status === 'ready';
    }

    /**
     * Check if order is completed
     */
    public function isCompleted()
    {
        return $this->order_status === 'completed';
    }

    /**
     * Check if order is cancelled
     */
    public function isCancelled()
    {
        return $this->order_status === 'cancelled';
    }

    /**
     * Check if order is rejected
     */
    public function isRejected()
    {
        return $this->order_status === 'rejected';
    }

    /**
     * Check if order is refunded
     */
    public function isRefunded()
    {
        return $this->order_status === 'refunded' || $this->payment_status === 'refunded';
    }

    /**
     * Check if order is partially refunded
     */
    public function isPartiallyRefunded()
    {
        return $this->payment_status === 'partial_refund';
    }

    /**
     * Check if payment is pending
     */
    public function isPaymentPending()
    {
        return $this->payment_status === 'pending';
    }

    /**
     * Check if payment is cash on pickup
     */
    public function isCashOnPickup()
    {
        return $this->payment_status === 'cash on pickup';
    }

    /**
     * Check if payment is paid
     */
    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check if payment is failed
     */
    public function isPaymentFailed()
    {
        return $this->payment_status === 'failed';
    }

    /**
     * Check if payment method is cash (from latest payment)
     */
    public function isCashPayment()
    {
        return $this->payment_method === 'cash';
    }

    /**
     * Check if payment method is GCash (from latest payment)
     */
    public function isGcashPayment()
    {
        return $this->payment_method === 'gcash';
    }

    /**
     * Check if payment method is card (from latest payment)
     */
    public function isCardPayment()
    {
        return $this->payment_method === 'card';
    }

    /**
     * Check if order is awaiting admin confirmation (for all payment types)
     */
    public function isAwaitingConfirmation()
    {
        return $this->order_status === 'pending' && !$this->admin_confirmed_at;
    }

    /**
     * Check if order is confirmed by admin
     */
    public function isConfirmedByAdmin()
    {
        return !is_null($this->admin_confirmed_at);
    }

    /**
     * Process a refund for this order
     */
    public function processRefund($reason, $amount = null, $refundedBy = null)
    {
        $refundAmount = $amount ?? $this->total;

        $this->update([
            'payment_status' => $refundAmount < $this->total ? 'partial_refund' : 'refunded',
            'order_status' => 'refunded',
            'refund_reason' => $reason,
            'refund_amount' => $refundAmount,
            'refunded_at' => now(),
            'refunded_by' => $refundedBy,
            'notes' => $this->notes
                ? $this->notes . "\nRefunded: " . $reason
                : "Refunded: " . $reason
        ]);

        // Also update related payments
        foreach ($this->payments as $payment) {
            $payment->markAsRefunded($reason);
        }

        return $this;
    }

    /**
     * Scope for user's orders
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for pending orders
     */
    public function scopePending($query)
    {
        return $query->where('order_status', 'pending');
    }

    /**
     * Scope for confirmed orders
     */
    public function scopeConfirmed($query)
    {
        return $query->where('order_status', 'confirmed');
    }

    /**
     * Scope for preparing orders
     */
    public function scopePreparing($query)
    {
        return $query->where('order_status', 'preparing');
    }

    /**
     * Scope for ready orders
     */
    public function scopeReady($query)
    {
        return $query->where('order_status', 'ready');
    }

    /**
     * Scope for completed orders
     */
    public function scopeCompleted($query)
    {
        return $query->where('order_status', 'completed');
    }

    /**
     * Scope for cancelled orders
     */
    public function scopeCancelled($query)
    {
        return $query->where('order_status', 'cancelled');
    }

    /**
     * Scope for rejected orders
     */
    public function scopeRejected($query)
    {
        return $query->where('order_status', 'rejected');
    }

    /**
     * Scope for refunded orders
     */
    public function scopeRefunded($query)
    {
        return $query->where('order_status', 'refunded');
    }

    /**
     * Scope for partially refunded orders
     */
    public function scopePartiallyRefunded($query)
    {
        return $query->where('payment_status', 'partial_refund');
    }

    /**
     * Scope for cash on pickup orders
     */
    public function scopeCashOnPickup($query)
    {
        return $query->where('payment_status', 'cash on pickup');
    }

    /**
     * Scope for paid orders
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Scope for orders awaiting admin confirmation
     */
    public function scopeAwaitingConfirmation($query)
    {
        return $query->where('order_status', 'pending')
                     ->whereNull('admin_confirmed_at');
    }

    /**
     * Scope for orders confirmed by admin
     */
    public function scopeConfirmedByAdmin($query)
    {
        return $query->whereNotNull('admin_confirmed_at');
    }

    /**
     * Scope for today's orders
     */
    public function scopeToday($query)
    {
        return $query->whereDate('ordered_at', today());
    }

    /**
     * Scope for refundable orders
     */
    public function scopeRefundable($query)
    {
        return $query->where('payment_status', 'paid')
                     ->whereIn('order_status', ['completed', 'ready'])
                     ->where(function($q) {
                         $q->whereNull('refunded_at')
                           ->orWhere('payment_status', '!=', 'refunded');
                     });
    }

    /**
     * Scope for searching orders
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('order_number', 'LIKE', "%{$term}%")
              ->orWhere('customer_name', 'LIKE', "%{$term}%")
              ->orWhere('customer_email', 'LIKE', "%{$term}%")
              ->orWhere('customer_phone', 'LIKE', "%{$term}%");
        });
    }
}