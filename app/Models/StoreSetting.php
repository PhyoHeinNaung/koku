<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    protected $fillable = [
        'store_name',
        'legal_name',
        'support_email',
        'support_phone',
        'business_address',
        'default_country',
        'order_prefix',
        'low_stock_threshold',
        'insurance_enabled',
        'insurance_rate',
        'guest_checkout_enabled',
    ];

    protected $casts = [
        'low_stock_threshold' => 'integer',
        'insurance_enabled' => 'boolean',
        'insurance_rate' => 'decimal:4',
        'guest_checkout_enabled' => 'boolean',
    ];

    public static function current(): self
    {
        return static::query()->first() ?? new static([
            'store_name' => 'TICKS',
            'default_country' => 'Myanmar',
            'order_prefix' => 'TCK',
            'low_stock_threshold' => 5,
            'insurance_enabled' => true,
            'insurance_rate' => 0.0200,
            'guest_checkout_enabled' => true,
        ]);
    }
}
