<?php

namespace App\Livewire\Customer\Shop;

use App\Livewire\Customer\Concerns\ManagesWishlist;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\WishlistItem;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use ManagesWishlist;
    use WithPagination;

    #[Url]
    public array $brands = [];

    #[Url]
    public array $categories = [];

    #[Url]
    public array $genders = [];

    #[Url]
    public array $movements = [];

    #[Url]
    public ?float $minPrice = null;

    #[Url]
    public ?float $maxPrice = null;

    #[Url]
    public string $sort = 'date_desc';

    #[Url]
    public string $search = '';

    public bool $showFilters = false;

    public float $priceFloor = 0;

    public float $priceCeil = 0;

    public function getWishlistedProductIdsProperty(): array
    {
        return WishlistItem::where($this->wishlistOwnerConditions())->pluck('product_id')->all();
    }

    public function mount(): void
    {
        $this->priceFloor = floor((float) (ProductVariant::where('is_active', true)->min('price') ?? 0));
        $this->priceCeil = ceil((float) (ProductVariant::where('is_active', true)->max('price') ?? 0));

        if ($this->minPrice === null) {
            $this->minPrice = $this->priceFloor;
        }

        if ($this->maxPrice === null) {
            $this->maxPrice = $this->priceCeil;
        }
    }

    public function updated($property): void
    {
        if (in_array($property, ['sort', 'search', 'minPrice', 'maxPrice', 'brands', 'categories', 'genders', 'movements'])) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['brands', 'categories', 'genders', 'movements']);
        $this->minPrice = $this->priceFloor;
        $this->maxPrice = $this->priceCeil;
        $this->resetPage();
    }

    public function getActiveFilterCountProperty(): int
    {
        $count = count($this->brands) + count($this->categories) + count($this->genders) + count($this->movements);

        if ($this->minPrice > $this->priceFloor || $this->maxPrice < $this->priceCeil) {
            $count++;
        }

        return $count;
    }

    protected function baseQuery()
    {
        return Product::query()
            ->with(['brand', 'category', 'specification', 'variants.images'])
            ->withMin('variants', 'price')
            ->where('is_active', true)
            ->whereHas('variants', fn ($q) => $q->where('is_active', true))
            ->when($this->search !== '', function ($query) {
                $term = '%'.trim($this->search).'%';

                $query->where(function ($query) use ($term) {
                    $query->where('name', 'like', $term)
                        ->orWhere('description', 'like', $term)
                        ->orWhereHas('brand', fn ($brand) => $brand->where('name', 'like', $term));
                });
            });
    }

    protected function applyFilters($query, array $except = [])
    {
        if ($this->brands && ! in_array('brands', $except)) {
            $query->whereHas('brand', fn ($q) => $q->whereIn('slug', $this->brands));
        }

        if ($this->categories && ! in_array('categories', $except)) {
            $query->whereHas('category', fn ($q) => $q->whereIn('slug', $this->categories));
        }

        if ($this->genders && ! in_array('genders', $except)) {
            $query->whereIn('gender', $this->genders);
        }

        if ($this->movements && ! in_array('movements', $except)) {
            $query->whereIn('movement', $this->movements);
        }

        if (! in_array('price', $except)) {
            $query->whereHas('variants', function ($q) {
                $q->where('is_active', true)
                    ->where('price', '>=', $this->minPrice)
                    ->where('price', '<=', $this->maxPrice);
            });
        }

        return $query;
    }

    public function render()
    {
        $query = $this->applyFilters($this->baseQuery());

        match ($this->sort) {
            'alpha_asc' => $query->orderBy('name'),
            'alpha_desc' => $query->orderByDesc('name'),
            'price_asc' => $query->orderBy('variants_min_price'),
            'price_desc' => $query->orderByDesc('variants_min_price'),
            'date_asc' => $query->oldest(),
            default => $query->latest(), // date_desc
        };

        $brandsList = Brand::where('is_active', true)->orderBy('name')->get();
        $categoriesList = Category::where('is_active', true)->orderBy('name')->get();

        $brandCounts = $brandsList->mapWithKeys(fn ($b) => [
            $b->slug => (clone $this->applyFilters($this->baseQuery(), ['brands']))
                ->whereHas('brand', fn ($q) => $q->where('slug', $b->slug))
                ->count(),
        ]);

        $categoryCounts = $categoriesList->mapWithKeys(fn ($c) => [
            $c->slug => (clone $this->applyFilters($this->baseQuery(), ['categories']))
                ->whereHas('category', fn ($q) => $q->where('slug', $c->slug))
                ->count(),
        ]);

        $genderCounts = collect(['men', 'women', 'unisex'])->mapWithKeys(fn ($g) => [
            $g => (clone $this->applyFilters($this->baseQuery(), ['genders']))->where('gender', $g)->count(),
        ]);

        $movementCounts = collect(['automatic', 'quartz', 'mechanical', 'chronograph', 'smart'])->mapWithKeys(fn ($m) => [
            $m => (clone $this->applyFilters($this->baseQuery(), ['movements']))->where('movement', $m)->count(),
        ]);

        return view('livewire.customer.shop.index', [
            'products' => $query->paginate(12),
            'brandOptions' => $brandsList,
            'categoryOptions' => $categoriesList,
            'brandCounts' => $brandCounts,
            'categoryCounts' => $categoryCounts,
            'genderCounts' => $genderCounts,
            'movementCounts' => $movementCounts,
        ])->layout('layouts.app', ['overlay' => false]);
    }
}
