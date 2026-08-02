<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'sku',
        'price',
        'compare_price',
        'stock_quantity',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'variant_id')->orderBy('sort_order');
    }

    public function specification(): HasOne
    {
        return $this->hasOne(ProductVariantSpecification::class);
    }

    public function effectiveSpecifications(?ProductSpecification $sharedSpecification = null): array
    {
        $sharedSpecification ??= $this->product->specification;

        $shared = $sharedSpecification?->specificationValues()
            ?? array_fill_keys([...ProductSpecification::fieldKeys(), 'custom_specifications'], null);
        $overrides = $this->specification?->overrides ?? [];

        $sharedCustom = $shared['custom_specifications'] ?? [];
        $overrideCustom = $overrides['custom_specifications'] ?? [];

        unset($shared['custom_specifications'], $overrides['custom_specifications']);

        return [
            ...array_replace($shared, $overrides),
            'custom_specifications' => array_replace($sharedCustom, $overrideCustom),
        ];
    }

    public function getPrimaryImageAttribute(): ?ProductImage
    {
        return $this->images->firstWhere('is_primary', true) ?? $this->images->first();
    }
}
