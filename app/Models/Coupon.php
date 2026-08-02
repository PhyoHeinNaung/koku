<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'description', 'discount_type', 'discount_value',
        'minimum_order_amount', 'start_date', 'end_date',
        'usage_limit', 'used_count', 'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function isValidNow(): bool
    {
        return $this->is_active
            && now()->toDateString() >= $this->start_date->toDateString()
            && now()->toDateString() <= $this->end_date->toDateString()
            && ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
