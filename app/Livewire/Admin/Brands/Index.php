<?php

namespace App\Livewire\Admin\Brands;

use App\Models\Brand;
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
    public string $tier = 'all';

    #[Url(except: 'newest')]
    public string $sort = 'newest';

    public array $selected = [];

    public function updated(string $property): void
    {
        if (in_array($property, ['search', 'status', 'tier', 'sort'], true)) {
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

        $updated = Brand::query()->whereIn('id', $ids)->update(['is_active' => $active]);
        $this->clearSelection();

        $this->dispatch(
            'admin-notify',
            type: 'success',
            message: "{$updated} ".str('brand')->plural($updated)." marked ".($active ? 'active.' : 'inactive.')
        );
    }

    public function bulkDelete(): void
    {
        $ids = $this->selectedIds();

        if ($ids === []) {
            return;
        }

        $deletableIds = Brand::query()
            ->whereIn('id', $ids)
            ->whereDoesntHave('products')
            ->pluck('id');
        $deleted = Brand::query()->whereIn('id', $deletableIds)->delete();
        $skipped = count($ids) - $deleted;
        $this->clearSelection();

        $this->dispatch(
            'admin-notify',
            type: $deleted === 0 ? 'error' : ($skipped > 0 ? 'warning' : 'success'),
            message: $skipped > 0
                ? "{$deleted} deleted; {$skipped} skipped because products are assigned."
                : "{$deleted} ".str('brand')->plural($deleted)." deleted."
        );
    }

    public function resetFilters(): void
    {
        $this->reset('status', 'tier', 'sort');
        $this->resetPage();
        $this->clearSelection();
    }

    public function clearAll(): void
    {
        $this->reset('search', 'status', 'tier', 'sort');
        $this->resetPage();
        $this->clearSelection();
    }

    public function deleteBrand(Brand $brand): void
    {

        if ($brand->products()->exists()) {
            $this->dispatch(
                'admin-notify',
                type: 'error',
                message: "\"{$brand->name}\" has products assigned to it and cannot be deleted. Deactivate it instead, or reassign its products first."
            );

            return;
        }

        $brand->delete();

        $this->dispatch('admin-notify', type: 'success', message: "\"{$brand->name}\" was deleted.");
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
        $brands = Brand::query()
            ->withCount('products')
            ->when($this->search, fn ($query) => $query->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('slug', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            }))
            ->when($this->status === 'active', fn ($query) => $query->where('is_active', true))
            ->when($this->status === 'inactive', fn ($query) => $query->where('is_active', false))
            ->when(
                in_array($this->tier, ['luxury', 'premium', 'everyday', 'smart_sport'], true),
                fn ($query) => $query->where('tier', $this->tier)
            );

        match ($this->sort) {
            'oldest' => $brands->oldest(),
            'name_asc' => $brands->orderBy('name'),
            'name_desc' => $brands->orderByDesc('name'),
            'products_desc' => $brands->orderByDesc('products_count')->orderBy('name'),
            default => $brands->latest(),
        };

        $summary = [
            'all' => Brand::count(),
            'active' => Brand::where('is_active', true)->count(),
            'inactive' => Brand::where('is_active', false)->count(),
        ];

        return view('livewire.admin.brands.index', [
            'brands' => $brands->paginate(10),
            'summary' => $summary,
            'hasFilters' => filled($this->search)
                || $this->status !== 'all'
                || $this->tier !== 'all'
                || $this->sort !== 'newest',
        ])
            ->layout('layouts.admin');
    }
}
