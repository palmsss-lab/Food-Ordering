<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'min_order_amount',
        'max_uses',
        'used_count',
        'expires_at',
        'is_active',
        'is_public',
    ];

    protected $casts = [
        'value'            => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'expires_at'       => 'datetime',
        'is_active'        => 'boolean',
        'is_public'        => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /** Users who have claimed this voucher */
    public function claimedUsers()
    {
        return $this->belongsToMany(\App\Models\User::class, 'user_vouchers')
                    ->withPivot('claimed_at', 'used_at', 'order_id')
                    ->withTimestamps();
    }

    /** Check if a specific user has already claimed this voucher */
    public function isClaimedBy(int $userId): bool
    {
        return \DB::table('user_vouchers')
            ->where('user_id', $userId)
            ->where('voucher_id', $this->id)
            ->exists();
    }

    /** Check if a specific user has an unused claim */
    public function isAvailableFor(int $userId): bool
    {
        return \DB::table('user_vouchers')
            ->where('user_id', $userId)
            ->where('voucher_id', $this->id)
            ->whereNull('used_at')
            ->exists();
    }

    /**
     * Actual redemption count from user_vouchers — the authoritative source.
     * Never use the cached used_count column for validity checks.
     */
    public function actualUsedCount(): int
    {
        return \DB::table('user_vouchers')
            ->where('voucher_id', $this->id)
            ->whereNotNull('used_at')
            ->count();
    }

    /**
     * True when the voucher has passed its expiry date.
     */
    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    /**
     * Effective status label for display purposes.
     */
    public function statusLabel(): string
    {
        if ($this->isExpired()) return 'Expired';
        if (!$this->is_active)  return 'Inactive';
        if ($this->max_uses !== null && $this->actualUsedCount() >= $this->max_uses) return 'Used Up';
        return 'Active';
    }

    /**
     * Check if the voucher is valid for a given subtotal.
     */
    public function isValid(float $subtotal): bool
    {
        if (!$this->is_active) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->max_uses !== null && $this->actualUsedCount() >= $this->max_uses) return false;
        if ($this->min_order_amount !== null && $subtotal < (float) $this->min_order_amount) return false;
        return true;
    }

    /**
     * Calculate discount amount against the pre-tax subtotal.
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percentage') {
            return round($subtotal * ((float) $this->value / 100), 2);
        }
        // Fixed — cannot exceed subtotal
        return min((float) $this->value, $subtotal);
    }

    /**
     * Human-readable label shown on receipts / order detail.
     */
    public function label(): string
    {
        if ($this->type === 'percentage') {
            return "{$this->code} ({$this->value}% off)";
        }
        return "{$this->code} (₱" . number_format($this->value, 2) . ' off)';
    }
}
