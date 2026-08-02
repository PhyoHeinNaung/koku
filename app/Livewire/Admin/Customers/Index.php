<?php

namespace App\Livewire\Admin\Customers;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
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
    public string $verification = 'all';

    #[Url]
    public string $activity = 'all';

    #[Url]
    public string $sort = 'newest';

    /** @var array<int, int> */
    public array $selected = [];

    public ?int $selectedCustomerId = null;

    public string $selectedCustomerStatus = '';

    public bool $drawerOpen = false;

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

    public function updatedVerification(): void
    {
        $this->resetPage();
        $this->clearSelection();
    }

    public function updatedActivity(): void
    {
        $this->resetPage();
        $this->clearSelection();
    }

    public function updatedSort(): void
    {
        $this->resetPage();
        $this->clearSelection();
    }

    public function openCustomer(int $customerId): void
    {
        $customer = User::where('role', 'user')->findOrFail($customerId);

        $this->selectedCustomerId = $customer->id;
        $this->selectedCustomerStatus = $customer->status;
        $this->drawerOpen = true;
    }

    public function closeCustomer(): void
    {
        $this->drawerOpen = false;
        $this->selectedCustomerId = null;
        $this->selectedCustomerStatus = '';
    }

    public function updateCustomerStatus(): void
    {
        $validated = $this->validate([
            'selectedCustomerStatus' => ['required', Rule::in($this->allowedStatuses())],
        ]);

        $customer = User::where('role', 'user')->findOrFail($this->selectedCustomerId);
        $customer->forceFill(['status' => $validated['selectedCustomerStatus']])->save();

        $this->dispatch('admin-notify', type: 'success', message: "{$customer->name}'s account was updated.");
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

    public function bulkUpdateStatus(string $status): void
    {
        if (! in_array($status, $this->allowedStatuses(), true) || $this->selected === []) {
            return;
        }

        $count = User::query()
            ->where('role', 'user')
            ->whereKey($this->selected)
            ->update(['status' => $status]);

        $this->clearSelection();
        $this->dispatch('admin-notify', type: 'success', message: "{$count} customer accounts updated.");
    }

    public function clearAll(): void
    {
        $this->search = '';
        $this->status = 'all';
        $this->verification = 'all';
        $this->activity = 'all';
        $this->sort = 'newest';
        $this->resetPage();
        $this->clearSelection();
    }

    /** @return array<int, string> */
    private function allowedStatuses(): array
    {
        return ['pending', 'active', 'banned'];
    }

    private function filteredQuery(): Builder
    {
        return User::query()
            ->where('role', 'user')
            ->withCount(['orders', 'addresses'])
            ->withSum('orders as lifetime_value', 'total')
            ->withMax('orders as last_order_at', 'created_at')
            ->when(filled($this->search), function (Builder $query): void {
                $search = trim($this->search);
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhereHas('orders', fn (Builder $query) => $query
                            ->where('order_number', 'like', "%{$search}%"));
                });
            })
            ->when($this->status !== 'all', fn (Builder $query) => $query->where('status', $this->status))
            ->when($this->verification === 'verified', fn (Builder $query) => $query->whereNotNull('email_verified_at'))
            ->when($this->verification === 'unverified', fn (Builder $query) => $query->whereNull('email_verified_at'))
            ->when($this->activity === 'with_orders', fn (Builder $query) => $query->has('orders'))
            ->when($this->activity === 'without_orders', fn (Builder $query) => $query->doesntHave('orders'))
            ->when($this->sort === 'oldest', fn (Builder $query) => $query->oldest())
            ->when($this->sort === 'name', fn (Builder $query) => $query->orderBy('name'))
            ->when($this->sort === 'spend_desc', fn (Builder $query) => $query->orderByDesc('lifetime_value'))
            ->when($this->sort === 'orders_desc', fn (Builder $query) => $query->orderByDesc('orders_count'))
            ->when($this->sort === 'recent_order', fn (Builder $query) => $query->orderByDesc('last_order_at'))
            ->when($this->sort === 'newest', fn (Builder $query) => $query->latest());
    }

    public function render()
    {
        $customerQuery = User::where('role', 'user');
        $summary = [
            'all' => (clone $customerQuery)->count(),
            'active' => (clone $customerQuery)->where('status', 'active')->count(),
            'pending' => (clone $customerQuery)->where('status', 'pending')->count(),
            'banned' => (clone $customerQuery)->where('status', 'banned')->count(),
        ];

        $selectedCustomer = $this->selectedCustomerId
            ? User::query()
                ->where('role', 'user')
                ->with([
                    'addresses' => fn ($query) => $query->orderByDesc('is_default')->latest(),
                    'orders' => fn ($query) => $query->with('latestPayment')->latest()->limit(8),
                ])
                ->withCount('orders')
                ->withSum('orders as lifetime_value', 'total')
                ->find($this->selectedCustomerId)
            : null;

        return view('livewire.admin.customers.index', [
            'customers' => $this->filteredQuery()->paginate(10),
            'summary' => $summary,
            'selectedCustomer' => $selectedCustomer,
            'hasFilters' => filled($this->search) || $this->status !== 'all'
                || $this->verification !== 'all' || $this->activity !== 'all' || $this->sort !== 'newest',
        ])->layout('layouts.admin');
    }
}
