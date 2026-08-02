<?php

namespace App\Livewire\Customer\Shop;

use App\Livewire\Customer\Concerns\ManagesCart;
use App\Livewire\Customer\Concerns\ManagesWishlist;
use App\Models\Product;
use Livewire\Component;

class Show extends Component
{
    use ManagesCart;
    use ManagesWishlist;

    public Product $product;

    public ?int $selectedVariantId = null;

    public bool $showAllVariants = false;

    public function mount(Product $product): void
    {
        abort_unless($product->is_active, 404);

        $this->product = $product->load(['brand', 'category', 'specification', 'variants' => function ($query) {
            $query->orderByDesc('is_default')->orderBy('id');
        }, 'variants.images', 'variants.specification']);

        abort_unless(
            $this->product->variants->contains(fn ($variant) => $variant->is_active && $variant->is_default),
            404
        );

        $default = $this->product->defaultVariant();

        $this->selectedVariantId = $default?->id;
    }

    public function selectVariant(int $variantId): void
    {
        abort_unless(
            $this->product->variants->contains(fn ($variant) => $variant->id === $variantId && $variant->is_active),
            404
        );

        $this->selectedVariantId = $variantId;
        $this->showAllVariants = false;
    }

    public function getSelectedVariantProperty()
    {
        return $this->product->variants->firstWhere('id', $this->selectedVariantId);
    }

    public function getActiveVariantsProperty()
    {
        return $this->product->variants->where('is_active', true)->values();
    }

    public function getGalleryImagesProperty(): array
    {
        return $this->selectedVariant
            ? $this->selectedVariant->images->map(fn ($image) => \Storage::url($image->image_url))->values()->all()
            : [];
    }

    public function getGalleryActiveIndexProperty(): int
    {
        if (! $this->selectedVariant) {
            return 0;
        }

        $index = $this->selectedVariant->images->search(fn ($image) => $image->is_primary);

        return $index === false ? 0 : $index;
    }

    public function buyNow(): void
    {
        if ($this->selectedVariantId) {
            $this->addToCart($this->selectedVariantId);
            $this->redirectRoute('cart.index');
        }
    }

    /**
     * Spec groups for the accordion, each with a heading and the fields that
     * belong under it. Only groups (and fields within them) that actually
     * have a value get rendered — empty groups are skipped entirely.
     */
    public function getSpecGroupsProperty(): array
    {
        if (! $this->selectedVariant) {
            return [];
        }

        $spec = $this->selectedVariant->effectiveSpecifications($this->product->specification);

        $groups = [
            'Case' => [
                'Case Size' => $spec['case_size'],
                'Case Material' => $spec['case_material'],
                'Case Thickness' => $spec['case_thickness'],
                'Water Resistance' => $spec['water_resistance'],
                'Crystal' => $spec['glass_type'],
                'Weight' => $spec['weight'],
                'Dial Color' => $spec['dial_color'],
            ],
            'Movement' => [
                'Caliber' => $spec['movement_caliber'],
                'Power Reserve' => $spec['power_reserve'],
                'Frequency' => $spec['frequency'],
                'Jewels' => $spec['jewels'],
                'Functions' => $spec['functions'],
            ],
            'Strap' => [
                'Material' => $spec['strap_material'],
                'Clasp Type' => $spec['clasp_type'],
            ],
            'Smart Features' => [
                'Battery Life' => $spec['battery_life'],
                'Display Type' => $spec['display_type'],
                'Connectivity' => $spec['connectivity'],
                'Compatibility' => $spec['compatibility'],
            ],
            'Origin' => [
                'Country of Origin' => $spec['country_of_origin'],
            ],
        ];

        if (! empty($spec['custom_specifications'])) {
            $groups['Additional Details'] = $spec['custom_specifications'];
        }

        return collect($groups)
            ->map(fn ($fields) => array_filter($fields, fn ($value) => filled($value)))
            ->filter(fn ($fields) => count($fields) > 0)
            ->toArray();
    }

    public function render()
    {
        return view('livewire.customer.shop.show')
            ->layout('layouts.app', ['overlay' => false]);
    }
}
