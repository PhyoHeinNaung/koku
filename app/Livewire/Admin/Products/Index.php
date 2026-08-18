<?php

namespace App\Livewire\Admin\Products;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\StoreSetting;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url(except: 'all')]
    public string $status = 'all';

    #[Url(except: 'all')]
    public string $brand = 'all';

    #[Url(except: 'all')]
    public string $category = 'all';

    #[Url(except: 'all')]
    public string $watchType = 'all';

    #[Url(except: 'all')]
    public string $featured = 'all';

    #[Url(except: 'newest')]
    public string $sort = 'newest';

    public array $selected = [];

    public ?int $selectedProductId = null;

    public bool $drawerOpen = false;

    public function openProduct(int $productId): void
    {
        Product::findOrFail($productId);
        $this->selectedProductId = $productId;
        $this->drawerOpen = true;
    }

    public function closeProduct(): void
    {
        $this->drawerOpen = false;
        $this->selectedProductId = null;
    }

    public function updated(string $property): void
    {
        if (in_array($property, [
            'search',
            'status',
            'brand',
            'category',
            'watchType',
            'featured',
            'sort',
        ], true)) {
            $this->resetPage();
            $this->clearSelection();
        }
    }

    public function togglePageSelection(array $ids): void
    {
        $ids = array_values(array_unique(array_map('intval', $ids)));
        $selected = array_values(array_unique(array_map('intval', $this->selected)));
        $allSelected = count($ids) > 0 && count(array_intersect($ids, $selected)) === count($ids);

        $this->selected = $allSelected
            ? array_values(array_diff($selected, $ids))
            : array_values(array_unique([...$selected, ...$ids]));
    }

    public function clearSelection(): void
    {
        $this->selected = [];
    }

    public function bulkSetActive(bool $active): void
    {
        $ids = $this->selectedIds();

        if ($ids === []) {
            return;
        }

        if (! $active) {
            $updated = Product::query()->whereIn('id', $ids)->update(['is_active' => false]);
            $skipped = 0;
        } else {
            $eligibleIds = Product::query()
                ->whereIn('id', $ids)
                ->whereHas('variants', fn ($query) => $query->where('is_active', true)->where('is_default', true))
                ->pluck('id');
            $updated = Product::query()->whereIn('id', $eligibleIds)->update(['is_active' => true]);
            $skipped = count($ids) - $updated;
        }

        $this->clearSelection();
        $this->dispatch(
            'admin-notify',
            type: $updated === 0 ? 'error' : ($skipped > 0 ? 'warning' : 'success'),
            message: $skipped > 0
                ? "{$updated} activated; {$skipped} skipped because an active default variant is required."
                : "{$updated} ".str('product')->plural($updated).' marked '.($active ? 'active.' : 'draft.')
        );
    }

    public function bulkSetFeatured(bool $featured): void
    {
        $ids = $this->selectedIds();

        if ($ids === []) {
            return;
        }

        $updated = Product::query()->whereIn('id', $ids)->update(['is_featured' => $featured]);
        $this->clearSelection();

        $this->dispatch(
            'admin-notify',
            type: 'success',
            message: "{$updated} ".str('product')->plural($updated).' '.($featured ? 'featured.' : 'removed from featured.')
        );
    }

    public function bulkDelete(): void
    {
        $ids = $this->selectedIds();

        if ($ids === []) {
            return;
        }

        $deleted = Product::query()->whereIn('id', $ids)->delete();
        $this->clearSelection();

        $this->dispatch(
            'admin-notify',
            type: 'success',
            message: "{$deleted} ".str('product')->plural($deleted).' deleted.'
        );
    }

    public function resetFilters(): void
    {
        $this->reset('status', 'brand', 'category', 'watchType', 'featured', 'sort');
        $this->resetPage();
        $this->clearSelection();
    }

    public function clearAll(): void
    {
        $this->reset('search', 'status', 'brand', 'category', 'watchType', 'featured', 'sort');
        $this->resetPage();
        $this->clearSelection();
    }

    public function deleteProduct(Product $product): void
    {
        $product->delete();

        $this->dispatch('admin-notify', type: 'success', message: "\"{$product->name}\" was deleted.");
    }

    private function selectedIds(): array
    {
        return array_values(array_unique(array_filter(
            array_map('intval', $this->selected),
            fn (int $id) => $id > 0
        )));
    }

    public function render()
    {
        $activeDefault = fn ($query) => $query->where('is_active', true)->where('is_default', true);

        $products = Product::query()
            ->with(['brand', 'category', 'variants.images'])
            ->withCount('variants')
            ->withSum('variants', 'stock_quantity')
            ->when($this->search, fn ($query) => $query->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('slug', 'like', "%{$this->search}%")
                    ->orWhereHas('brand', fn ($brand) => $brand->where('name', 'like', "%{$this->search}%"))
                    ->orWhereHas('category', fn ($category) => $category->where('name', 'like', "%{$this->search}%"))
                    ->orWhereHas('variants', fn ($variant) => $variant->where('sku', 'like', "%{$this->search}%"));
            }))
            ->when($this->status === 'active', fn ($query) => $query
                ->where('is_active', true)
                ->whereHas('variants', $activeDefault))
            ->when($this->status === 'draft', fn ($query) => $query
                ->where('is_active', false)
                ->whereHas('variants', $activeDefault))
            ->when($this->status === 'incomplete', fn ($query) => $query
                ->whereDoesntHave('variants', $activeDefault))
            ->when(ctype_digit($this->brand), fn ($query) => $query->where('brand_id', (int) $this->brand))
            ->when(ctype_digit($this->category), fn ($query) => $query->where('category_id', (int) $this->category))
            ->when(
                in_array($this->watchType, ['traditional', 'smart', 'hybrid'], true),
                fn ($query) => $query->where('watch_type', $this->watchType)
            )
            ->when($this->featured === 'yes', fn ($query) => $query->where('is_featured', true))
            ->when($this->featured === 'no', fn ($query) => $query->where('is_featured', false));

        match ($this->sort) {
            'oldest' => $products->oldest(),
            'name_asc' => $products->orderBy('name'),
            'name_desc' => $products->orderByDesc('name'),
            'stock_asc' => $products->orderBy('variants_sum_stock_quantity')->orderBy('name'),
            'stock_desc' => $products->orderByDesc('variants_sum_stock_quantity')->orderBy('name'),
            default => $products->latest(),
        };

        $summary = [
            'all' => Product::count(),
            'active' => Product::where('is_active', true)->whereHas('variants', $activeDefault)->count(),
            'draft' => Product::where('is_active', false)->whereHas('variants', $activeDefault)->count(),
            'incomplete' => Product::whereDoesntHave('variants', $activeDefault)->count(),
        ];

        $selectedProduct = $this->selectedProductId
            ? Product::with(['brand', 'category', 'variants.images'])->find($this->selectedProductId)
            : null;

        return view('livewire.admin.products.index', [
            'products' => $products->paginate(10),
            'brands' => Brand::orderBy('name')->get(['id', 'name']),
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'summary' => $summary,
            'lowStockThreshold' => StoreSetting::current()->low_stock_threshold,
            'hasFilters' => filled($this->search)
                || $this->status !== 'all'
                || $this->brand !== 'all'
                || $this->category !== 'all'
                || $this->watchType !== 'all'
                || $this->featured !== 'all'
                || $this->sort !== 'newest',
            'selectedProduct' => $selectedProduct,
        ])
            ->layout('layouts.admin');
    }
}
