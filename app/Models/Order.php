<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'coupon_id', 'shipping_location_id', 'order_number', 'email',
        'shipping_full_name', 'shipping_phone', 'shipping_country', 'shipping_state_region',
        'shipping_city', 'shipping_district_area', 'shipping_postal_code',
        'shipping_address_line1', 'shipping_address_line2',
        'billing_full_name', 'billing_phone', 'billing_country', 'billing_state_region',
        'billing_city', 'billing_district_area', 'billing_postal_code',
        'billing_address_line1', 'billing_address_line2',
        'shipping_address_id', 'billing_address_id',
        'subtotal', 'discount', 'tax', 'shipping_fee', 'insurance_fee', 'total',
        'status', 'stripe_payment_intent_id', 'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'insurance_fee' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function shippingLocation(): BelongsTo
    {
        return $this->belongsTo(ShippingLocation::class);
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }
}
