<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Promotion extends Model
{
    protected $fillable = [
        'title',
        'description',
        'discount_percentage',
        'start_date',
        'end_date',
        'is_active',
        'banner_color',
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'start_date'          => 'date',
        'end_date'            => 'date',
        'is_active'           => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }

    /** Scope: active promotions that cover today's date */
    public function scopeActiveToday($query)
    {
        $today = Carbon::today()->toDateString();

        return $query->where('is_active', true)
                     ->where('start_date', '<=', $today)
                     ->where('end_date', '>=', $today);
    }

    /** Get the single active promotion for today, or null */
    public static function todayPromo(): ?self
    {
        return static::activeToday()->latest()->first();
    }

    public function isRunning(): bool
    {
        $today = Carbon::today();
        return $this->is_active
            && $this->start_date->lte($today)
            && $this->end_date->gte($today);
    }

    public function statusLabel(): string
    {
        if (!$this->is_active) return 'Inactive';
        $today = Carbon::today();
        if ($this->start_date->gt($today)) return 'Upcoming';
        if ($this->end_date->lt($today))   return 'Expired';
        return 'Active';
    }
}
