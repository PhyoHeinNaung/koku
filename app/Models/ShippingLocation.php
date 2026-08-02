<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingLocation extends Model
{
    protected $fillable = ['shipping_zone_id', 'country', 'state_region', 'city', 'district_area', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(ShippingZone::class, 'shipping_zone_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
