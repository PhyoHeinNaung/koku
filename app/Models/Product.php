<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $attributes = [
        'watch_type' => 'traditional',
    ];

    protected $fillable = [
        'brand_id',
        'category_id',
        'name',
        'slug',
        'description',
        'gender',
        'watch_type',
        'movement',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function specification(): HasOne
    {
        return $this->hasOne(ProductSpecification::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function wishlistItems(): HasMany
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function defaultVariant(): ?ProductVariant
    {
        $flaggedDefaults = $this->variants->where('is_default', true);

        if ($flaggedDefaults->count() > 1) {
            return $flaggedDefaults->sortByDesc('updated_at')->first();
        }

        return $flaggedDefaults->first()
            ?? $this->variants->firstWhere('is_active', true)
            ?? $this->variants->first();
    }

    public function getPrimaryImageUrlAttribute(): ?string
    {
        $variant = $this->defaultVariant();

        return $variant?->primary_image
            ? \Storage::url($variant->primary_image->image_url)
            : null;
    }
}
