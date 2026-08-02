<?php

namespace App\Livewire\Admin\Products;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSpecification;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class Form extends Component
{
    public ?Product $product = null;

    public string $brand_id = '';

    public string $category_id = '';

    public string $name = '';

    public string $description = '';

    public string $gender = '';

    public string $movement = '';

    public string $watch_type = 'traditional';

    public bool $is_active = false;

    public bool $is_featured = false;

    public ?string $case_size = '';

    public ?string $case_material = '';

    public ?string $case_thickness = '';

    public ?string $water_resistance = '';

    public ?string $glass_type = '';

    public ?string $weight = '';

    public ?string $dial_color = '';

    public ?string $movement_caliber = '';

    public ?string $power_reserve = '';

    public ?string $frequency = '';

    public ?string $jewels = '';

    public ?string $functions = '';

    public ?string $strap_material = '';

    public ?string $clasp_type = '';

    public ?string $battery_life = '';

    public ?string $display_type = '';

    public ?string $connectivity = '';

    public ?string $compatibility = '';

    public ?string $country_of_origin = '';

    /** @var array<int, array{key: string, value: string}> */
    public array $customSpecs = [];

    public function mount(?Product $product = null): void
    {
        if ($product?->exists) {
            $this->product = $product;
            $this->brand_id = (string) $product->brand_id;
            $this->category_id = (string) $product->category_id;
            $this->name = $product->name;
            $this->description = $product->description;
            $this->gender = $product->gender;
            $this->watch_type = $product->watch_type ?: 'traditional';
            $this->movement = $product->movement;
            $this->is_active = $product->is_active;
            $this->is_featured = $product->is_featured;

            $spec = $product->specification;
            $this->case_size = $spec?->case_size;
            $this->case_material = $spec?->case_material;
            $this->case_thickness = $spec?->case_thickness;
            $this->water_resistance = $spec?->water_resistance;
            $this->glass_type = $spec?->glass_type;
            $this->weight = $spec?->weight;
            $this->dial_color = $spec?->dial_color;
            $this->movement_caliber = $spec?->movement_caliber;
            $this->power_reserve = $spec?->power_reserve;
            $this->frequency = $spec?->frequency;
            $this->jewels = $spec?->jewels;
            $this->functions = $spec?->functions;
            $this->strap_material = $spec?->strap_material;
            $this->clasp_type = $spec?->clasp_type;
            $this->battery_life = $spec?->battery_life;
            $this->display_type = $spec?->display_type;
            $this->connectivity = $spec?->connectivity;
            $this->compatibility = $spec?->compatibility;
            $this->country_of_origin = $spec?->country_of_origin;

            $this->customSpecs = collect($spec?->custom_specifications ?? [])
                ->map(fn ($value, $key) => ['key' => $key, 'value' => $value])
                ->values()
                ->all();
        }

        if (empty($this->customSpecs)) {
            $this->customSpecs = [['key' => '', 'value' => '']];
        }
    }

    public function addCustomSpec(): void
    {
        $this->customSpecs[] = ['key' => '', 'value' => ''];
    }

    public function removeCustomSpec(int $index): void
    {
        unset($this->customSpecs[$index]);
        $this->customSpecs = array_values($this->customSpecs);

        if (empty($this->customSpecs)) {
            $this->customSpecs = [['key' => '', 'value' => '']];
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'brand_id' => ['required', 'exists:brands,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255', Rule::unique('products', 'name')->ignore($this->product?->id)],
            'description' => ['required', 'string'],
            'gender' => ['required', Rule::in(['men', 'women', 'unisex'])],
            'watch_type' => ['required', Rule::in(['traditional', 'smart', 'hybrid'])],
            'movement' => ['required', Rule::in(['automatic', 'quartz', 'mechanical', 'chronograph', 'smart'])],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
        ]);

        if ($validated['is_active'] && ! $this->canPublish()) {
            $this->addError('is_active', 'Add an active default variant before publishing this product.');

            return;
        }

        $validated['slug'] = str($validated['name'])->slug();

        if ($this->product) {
            $this->product->update($validated);
        } else {
            $this->product = Product::create($validated);
        }

        $customSpecifications = collect($this->customSpecs)
            ->filter(fn ($row) => trim($row['key'] ?? '') !== '' && trim($row['value'] ?? '') !== '')
            ->pluck('value', 'key')
            ->all();

        $isConnectedWatch = in_array($this->watch_type, ['smart', 'hybrid'], true);

        $this->product->specification()->updateOrCreate(
            ['product_id' => $this->product->id],
            [
                'case_size' => $this->case_size,
                'case_material' => $this->case_material,
                'case_thickness' => $this->case_thickness,
                'water_resistance' => $this->water_resistance,
                'glass_type' => $this->glass_type,
                'weight' => $this->weight,
                'dial_color' => $this->dial_color,
                'movement_caliber' => $this->movement_caliber,
                'power_reserve' => $this->power_reserve,
                'frequency' => $this->frequency,
                'jewels' => $this->jewels,
                'functions' => $this->functions,
                'strap_material' => $this->strap_material,
                'clasp_type' => $this->clasp_type,
                'battery_life' => $isConnectedWatch ? $this->battery_life : null,
                'display_type' => $isConnectedWatch ? $this->display_type : null,
                'connectivity' => $isConnectedWatch ? $this->connectivity : null,
                'compatibility' => $isConnectedWatch ? $this->compatibility : null,
                'country_of_origin' => $this->country_of_origin,
                'custom_specifications' => $customSpecifications ?: null,
            ]
        );

        if (! $isConnectedWatch) {
            $this->clearSmartVariantOverrides();
        }

        session()->flash('success', "\"{$validated['name']}\" was saved.");

        $this->redirectRoute('admin.products.edit', $this->product);
    }

    #[On('variants-updated')]
    public function refreshProductState(): void
    {
        if ($this->product) {
            $this->product->refresh();
            $this->is_active = $this->product->is_active;
        }
    }

    private function canPublish(): bool
    {
        return $this->product?->variants()
            ->where('is_active', true)
            ->where('is_default', true)
            ->exists() ?? false;
    }

    private function clearSmartVariantOverrides(): void
    {
        $this->product->variants()
            ->with('specification')
            ->get()
            ->each(function ($variant) {
                if (! $variant->specification) {
                    return;
                }

                $overrides = collect($variant->specification->overrides ?? [])
                    ->except(ProductSpecification::SMART_FIELDS)
                    ->all();

                if ($overrides === []) {
                    $variant->specification->delete();

                    return;
                }

                $variant->specification->update(['overrides' => $overrides]);
            });
    }

    public function render()
    {
        return view('livewire.admin.products.form', [
            'brands' => Brand::where('is_active', true)->orderBy('name')->get(),
            'categories' => Category::where('is_active', true)->orderBy('name')->get(),
            'canPublish' => $this->canPublish(),
        ])->layout('layouts.admin');
    }
}
