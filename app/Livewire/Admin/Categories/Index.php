<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
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

    #[Url(except: 'newest')]
    public string $sort = 'newest';

    public array $selected = [];

    public function updated(string $property): void
    {
        if (in_array($property, ['search', 'status', 'sort'], true)) {
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

        $updated = Category::query()->whereIn('id', $ids)->update(['is_active' => $active]);
        $this->clearSelection();

        $this->dispatch(
            'admin-notify',
            type: 'success',
            message: "{$updated} ".str('category')->plural($updated)." marked ".($active ? 'active.' : 'inactive.')
        );
    }

    public function bulkDelete(): void
    {
        $ids = $this->selectedIds();

        if ($ids === []) {
            return;
        }

        $deletableIds = Category::query()
            ->whereIn('id', $ids)
            ->whereDoesntHave('products')
            ->pluck('id');
        $deleted = Category::query()->whereIn('id', $deletableIds)->delete();
        $skipped = count($ids) - $deleted;
        $this->clearSelection();

        $this->dispatch(
            'admin-notify',
            type: $deleted === 0 ? 'error' : ($skipped > 0 ? 'warning' : 'success'),
            message: $skipped > 0
                ? "{$deleted} deleted; {$skipped} skipped because products are assigned."
                : "{$deleted} ".str('category')->plural($deleted)." deleted."
        );
    }

    public function resetFilters(): void
    {
        $this->reset('status', 'sort');
        $this->resetPage();
        $this->clearSelection();
    }

    public function clearAll(): void
    {
        $this->reset('search', 'status', 'sort');
        $this->resetPage();
        $this->clearSelection();
    }

    public function deleteCategory(Category $category): void
    {
        if ($category->products()->exists()) {
            $this->dispatch(
                'admin-notify',
                type: 'error',
                message: "\"{$category->name}\" has products assigned to it and cannot be deleted. Deactivate it instead, or reassign its products first."
            );

            return;
        }

        $category->delete();

        $this->dispatch('admin-notify', type: 'success', message: "\"{$category->name}\" was deleted.");
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
        $categories = Category::query()
            ->withCount('products')
            ->when($this->search, fn ($query) => $query->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('slug', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            }))
            ->when($this->status === 'active', fn ($query) => $query->where('is_active', true))
            ->when($this->status === 'inactive', fn ($query) => $query->where('is_active', false));

        match ($this->sort) {
            'oldest' => $categories->oldest(),
            'name_asc' => $categories->orderBy('name'),
            'name_desc' => $categories->orderByDesc('name'),
            'products_desc' => $categories->orderByDesc('products_count')->orderBy('name'),
            default => $categories->latest(),
        };

        $summary = [
            'all' => Category::count(),
            'active' => Category::where('is_active', true)->count(),
            'inactive' => Category::where('is_active', false)->count(),
        ];

        return view('livewire.admin.categories.index', [
            'categories' => $categories->paginate(10),
            'summary' => $summary,
            'hasFilters' => filled($this->search) || $this->status !== 'all' || $this->sort !== 'newest',
        ])
            ->layout('layouts.admin');
    }
}
