<?php

namespace App\Livewire\Admin\Coupons;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $status = 'all';

    #[Url]
    public string $discountType = 'all';

    #[Url]
    public string $sort = 'newest';

    /** @var array<int, int> */
    public array $selected = [];

    public function updatedSearch(): void
    {
        $this->resetPage();
        $this->clearSelection();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
        $this->clearSelection();
    }

    public function updatedDiscountType(): void
    {
        $this->resetPage();
        $this->clearSelection();
    }

    public function updatedSort(): void
    {
        $this->resetPage();
        $this->clearSelection();
    }

    /** @param array<int, int|string> $ids */
    public function togglePageSelection(array $ids): void
    {
        $ids = array_map('intval', $ids);
        $selected = array_map('intval', $this->selected);
        $allSelected = count(array_intersect($ids, $selected)) === count($ids);

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
        if ($this->selected === []) {
            return;
        }

        $count = Coupon::whereKey($this->selected)->update(['is_active' => $active]);
        $this->clearSelection();

        $this->dispatch('admin-notify', type: 'success', message: "{$count} ".str('coupon')->plural($count).' updated.');
    }

    public function bulkDelete(): void
    {
        $coupons = Coupon::whereKey($this->selected)->withCount('orders')->get();
        $deletable = $coupons->filter(fn (Coupon $coupon) => $coupon->orders_count === 0 && $coupon->used_count === 0);
        $skipped = $coupons->count() - $deletable->count();

        Coupon::whereKey($deletable->pluck('id'))->delete();
        $this->clearSelection();

        $message = $deletable->count().' '.str('coupon')->plural($deletable->count()).' deleted.';
        if ($skipped > 0) {
            $message .= " {$skipped} used ".str('coupon')->plural($skipped).' kept.';
        }

        $this->dispatch('admin-notify', type: $skipped > 0 ? 'warning' : 'success', message: $message);
    }

    public function deleteCoupon(Coupon $coupon): void
    {
        if ($coupon->used_count > 0 || $coupon->orders()->exists()) {
            $this->dispatch('admin-notify', type: 'warning', message: 'Used coupons cannot be deleted. Deactivate it instead.');

            return;
        }

        $code = $coupon->code;
        $coupon->delete();

        $this->dispatch('admin-notify', type: 'success', message: "\"{$code}\" was deleted.");
    }

    public function clearAll(): void
    {
        $this->search = '';
        $this->status = 'all';
        $this->discountType = 'all';
        $this->sort = 'newest';
        $this->resetPage();
        $this->clearSelection();
    }

    private function applyLifecycle(Builder $query, string $status): Builder
    {
        $today = now()->toDateString();

        return match ($status) {
            'active' => $query
                ->where('is_active', true)
                ->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->where(fn (Builder $query) => $query
                    ->whereNull('usage_limit')
                    ->orWhereColumn('used_count', '<', 'usage_limit')),
            'scheduled' => $query
                ->where('is_active', true)
                ->whereDate('start_date', '>', $today),
            'expired' => $query
                ->where('is_active', true)
                ->where(function (Builder $query) use ($today): void {
                    $query->whereDate('end_date', '<', $today)
                        ->orWhere(function (Builder $query): void {
                            $query->whereNotNull('usage_limit')
                                ->whereColumn('used_count', '>=', 'usage_limit');
                        });
                }),
            'inactive' => $query->where('is_active', false),
            default => $query,
        };
    }

    private function filteredQuery(): Builder
    {
        $query = Coupon::query()
            ->withCount('orders')
            ->when(filled($this->search), function (Builder $query): void {
                $search = trim($this->search);
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('code', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($this->discountType !== 'all', fn (Builder $query) => $query->where('discount_type', $this->discountType));

        $this->applyLifecycle($query, $this->status);

        return $query
            ->when($this->sort === 'oldest', fn (Builder $query) => $query->oldest())
            ->when($this->sort === 'code_asc', fn (Builder $query) => $query->orderBy('code'))
            ->when($this->sort === 'ending', fn (Builder $query) => $query->orderBy('end_date'))
            ->when($this->sort === 'usage_desc', fn (Builder $query) => $query->orderByDesc('used_count'))
            ->when($this->sort === 'newest', fn (Builder $query) => $query->latest());
    }

    public function render()
    {
        $summary = ['all' => Coupon::count()];
        foreach (['active', 'scheduled', 'expired', 'inactive'] as $lifecycle) {
            $summary[$lifecycle] = $this->applyLifecycle(Coupon::query(), $lifecycle)->count();
        }

        return view('livewire.admin.coupons.index', [
            'coupons' => $this->filteredQuery()->paginate(10),
            'summary' => $summary,
            'hasFilters' => filled($this->search) || $this->status !== 'all'
                || $this->discountType !== 'all' || $this->sort !== 'newest',
        ])->layout('layouts.admin');
    }
}
