<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSpecification extends Model
{
    use HasFactory;

    public const SMART_FIELDS = [
        'battery_life',
        'display_type',
        'connectivity',
        'compatibility',
    ];

    public const FIELD_GROUPS = [
        'Case & dial' => [
            'case_size' => 'Case size',
            'case_material' => 'Case material',
            'case_thickness' => 'Case thickness',
            'water_resistance' => 'Water resistance',
            'glass_type' => 'Crystal / glass',
            'weight' => 'Weight',
            'dial_color' => 'Dial color',
        ],
        'Movement' => [
            'movement_caliber' => 'Movement caliber',
            'power_reserve' => 'Power reserve',
            'frequency' => 'Frequency',
            'jewels' => 'Jewels',
            'functions' => 'Functions',
        ],
        'Strap & origin' => [
            'strap_material' => 'Strap material',
            'clasp_type' => 'Clasp type',
            'country_of_origin' => 'Country of origin',
        ],
        'Smart & sport' => [
            'battery_life' => 'Battery life',
            'display_type' => 'Display type',
            'connectivity' => 'Connectivity',
            'compatibility' => 'Compatibility',
        ],
    ];

    protected $fillable = [
        'product_id',
        'case_size',
        'case_material',
        'case_thickness',
        'water_resistance',
        'glass_type',
        'weight',
        'dial_color',
        'movement_caliber',
        'power_reserve',
        'frequency',
        'jewels',
        'functions',
        'strap_material',
        'clasp_type',
        'battery_life',
        'display_type',
        'connectivity',
        'compatibility',
        'country_of_origin',
        'custom_specifications',
    ];

    protected $casts = [
        'custom_specifications' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public static function fieldGroupsFor(?string $watchType): array
    {
        $watchType ??= 'traditional';

        return collect(self::FIELD_GROUPS)
            ->reject(fn ($fields, $group) => $group === 'Smart & sport' && $watchType === 'traditional')
            ->all();
    }

    public static function fieldKeys(): array
    {
        return collect(self::FIELD_GROUPS)
            ->flatMap(fn ($fields) => $fields)
            ->keys()
            ->all();
    }

    public function specificationValues(): array
    {
        return collect(self::fieldKeys())
            ->mapWithKeys(fn ($key) => [$key => $this->{$key}])
            ->put('custom_specifications', $this->custom_specifications ?? [])
            ->all();
    }
}
