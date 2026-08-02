<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use App\Models\ProductSpecification;
use App\Models\ProductVariant;
use App\Models\StoreSetting;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Variants extends Component
{
    public Product $product;

    public bool $drawerOpen = false;

    public bool $editorOpen = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $sku = '';

    public string $price = '';

    public string $compare_price = '';

    public string $stock_quantity = '0';

    public bool $is_active = true;

    public bool $is_default = false;

    /** @var array<string, bool> */
    public array $overriddenSpecs = [];

    /** @var array<string, string|null> */
    public array $specOverrides = [];

    /** @var array<int, array{key: string, value: string}> */
    public array $customSpecOverrides = [];

    public function openManager(): void
    {
        $firstVariant = $this->product->variants()
            ->orderByDesc('is_default')
            ->orderBy('id')
            ->first();

        if ($firstVariant) {
            $this->edit($firstVariant);

            return;
        }

        $this->addNew();
    }

    public function addNew(): void
    {
        $this->resetEditor();
        $this->drawerOpen = true;
        $this->editorOpen = true;
    }

    public function edit(ProductVariant $variant): void
    {
        $this->ensureBelongsToProduct($variant);

        $this->editingId = $variant->id;
        $this->name = $variant->name;
        $this->sku = $variant->sku;
        $this->price = (string) $variant->price;
        $this->compare_price = (string) $variant->compare_price;
        $this->stock_quantity = (string) $variant->stock_quantity;
        $this->is_active = $variant->is_active;
        $this->is_default = $variant->is_default;
        $overrides = $variant->specification?->overrides ?? [];
        $customOverrides = $overrides['custom_specifications'] ?? [];
        unset($overrides['custom_specifications']);
        $this->specOverrides = $overrides;
        $this->overriddenSpecs = collect($overrides)->map(fn () => true)->all();
        $this->customSpecOverrides = collect($customOverrides)
            ->map(fn ($value, $key) => ['key' => $key, 'value' => $value])
            ->values()
            ->all();
        $this->drawerOpen = true;
        $this->editorOpen = true;
    }

    public function save(): void
    {
        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'sku' => ['required', 'string', 'max:100', 'unique:product_variants,sku,'.$this->editingId],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'is_default' => ['boolean'],
            'customSpecOverrides.*.key' => ['nullable', 'string', 'max:100'],
            'customSpecOverrides.*.value' => ['nullable', 'string', 'max:255'],
        ];

        foreach ($this->activeOverrideKeys() as $key) {
            $rules["specOverrides.{$key}"] = ['required', 'string', 'max:255'];
        }

        $validated = $this->validate($rules);

        $validated['compare_price'] = $validated['compare_price'] ?: null;

        $isFirstVariant = ! $this->editingId && $this->product->variants()->doesntExist();

        $savedVariant = DB::transaction(function () use (&$validated, $isFirstVariant) {
            $variant = $this->editingId
                ? $this->product->variants()->findOrFail($this->editingId)
                : null;

            if ($variant?->is_default && ! $validated['is_default']) {
                $validated['is_default'] = true;
            }

            if ($validated['is_default'] || $isFirstVariant) {
                $this->product->variants()->update(['is_default' => false]);

                $validated['is_default'] = true;
            }

            if ($variant) {
                $variant->update($validated);

                return $variant;
            }

            return $this->product->variants()->create($validated);
        });

        $this->synchronizeDefaultAndPublishingState();

        $savedVariant->refresh();
        $this->saveSpecificationOverrides($savedVariant);
        $this->editingId = $savedVariant->id;
        $this->is_active = $savedVariant->is_active;
        $this->is_default = $savedVariant->is_default;
        $this->drawerOpen = true;
        $this->editorOpen = true;

        $this->dispatch('admin-notify', type: 'success', message: 'Variant saved.');
        $this->dispatch('variants-updated');
    }

    public function setDefault(ProductVariant $variant): void
    {
        $this->ensureBelongsToProduct($variant);

        if (! $variant->is_active) {
            $this->dispatch('admin-notify', type: 'warning', message: 'Activate this variant before making it the default.');

            return;
        }

        DB::transaction(function () use ($variant) {
            $this->product->variants()->where('id', '!=', $variant->id)->update(['is_default' => false]);
            $variant->update(['is_default' => true]);
        });

        $this->dispatch('admin-notify', type: 'success', message: "\"{$variant->name}\" is now the default variant.");
        $this->dispatch('variants-updated');
    }

    public function deleteVariant(ProductVariant $variant): void
    {
        $this->ensureBelongsToProduct($variant);

        $variant->delete();

        if ($this->editingId === $variant->id) {
            $this->resetEditor();
        }

        $this->synchronizeDefaultAndPublishingState();

        $this->dispatch('admin-notify', type: 'success', message: 'Variant deleted.');
        $this->dispatch('variants-updated');
    }

    public function cancel(): void
    {
        $this->resetEditor();
        $this->drawerOpen = false;
    }

    public function closeEditor(): void
    {
        $this->resetEditor();
    }

    public function addCustomSpecOverride(): void
    {
        $this->customSpecOverrides[] = ['key' => '', 'value' => ''];
    }

    public function removeCustomSpecOverride(int $index): void
    {
        unset($this->customSpecOverrides[$index]);
        $this->customSpecOverrides = array_values($this->customSpecOverrides);
    }

    private function resetEditor(): void
    {
        $this->reset([
            'name',
            'sku',
            'price',
            'compare_price',
            'stock_quantity',
            'editingId',
            'editorOpen',
            'is_default',
            'overriddenSpecs',
            'specOverrides',
            'customSpecOverrides',
        ]);
        $this->is_active = true;
        $this->stock_quantity = '0';
    }

    private function synchronizeDefaultAndPublishingState(): void
    {
        $activeDefaultExists = $this->product->variants()
            ->where('is_active', true)
            ->where('is_default', true)
            ->exists();

        if (! $activeDefaultExists) {
            $replacement = $this->product->variants()
                ->where('is_active', true)
                ->oldest()
                ->first();

            if ($replacement) {
                DB::transaction(function () use ($replacement) {
                    $this->product->variants()->update(['is_default' => false]);
                    $replacement->update(['is_default' => true]);
                });

                $activeDefaultExists = true;
            }
        }

        if (! $activeDefaultExists && $this->product->is_active) {
            $this->product->update(['is_active' => false]);
        }
    }

    private function ensureBelongsToProduct(ProductVariant $variant): void
    {
        abort_unless($variant->product_id === $this->product->id, 404);
    }

    private function activeOverrideKeys(): array
    {
        $allowedKeys = collect(ProductSpecification::fieldGroupsFor($this->product->watch_type))
            ->flatMap(fn ($fields) => $fields)
            ->keys();

        return collect($this->overriddenSpecs)
            ->filter()
            ->keys()
            ->intersect($allowedKeys)
            ->values()
            ->all();
    }

    private function saveSpecificationOverrides(ProductVariant $variant): void
    {
        $overrides = collect($this->activeOverrideKeys())
            ->mapWithKeys(fn ($key) => [$key => trim((string) ($this->specOverrides[$key] ?? ''))])
            ->all();

        $customOverrides = collect($this->customSpecOverrides)
            ->filter(fn ($row) => filled(trim($row['key'] ?? '')) && filled(trim($row['value'] ?? '')))
            ->mapWithKeys(fn ($row) => [trim($row['key']) => trim($row['value'])])
            ->all();

        if ($customOverrides !== []) {
            $overrides['custom_specifications'] = $customOverrides;
        }

        if ($overrides === []) {
            $variant->specification()->delete();

            return;
        }

        $variant->specification()->updateOrCreate(
            ['product_variant_id' => $variant->id],
            ['overrides' => $overrides]
        );
    }

    public function render()
    {
        return view('livewire.admin.products.variants', [
            'variants' => $this->product->variants()->with(['images', 'specification'])->get(),
            'editingVariant' => $this->editingId
                ? $this->product->variants()->with(['images', 'specification'])->find($this->editingId)
                : null,
            'specificationGroups' => ProductSpecification::fieldGroupsFor($this->product->watch_type),
            'sharedSpecifications' => $this->product->specification?->specificationValues() ?? [],
            'lowStockThreshold' => StoreSetting::current()->low_stock_threshold,
        ]);
    }
}
