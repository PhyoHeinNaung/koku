<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingZone extends Model
{
    protected $fillable = ['name', 'fee', 'estimated_days', 'description', 'is_active'];

    protected $casts = ['fee' => 'decimal:2', 'is_active' => 'boolean'];

    public function locations(): HasMany
    {
        return $this->hasMany(ShippingLocation::class);
    }
}
