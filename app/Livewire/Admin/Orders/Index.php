<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Order;
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
    public string $payment = 'all';

    #[Url]
    public string $sort = 'newest';

    /** @var array<int, int> */
    public array $selected = [];

    public ?int $selectedOrderId = null;

    public string $selectedOrderStatus = '';

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

    public function updatedPayment(): void
    {
        $this->resetPage();
        $this->clearSelection();
    }

    public function updatedSort(): void
    {
        $this->resetPage();
        $this->clearSelection();
    }

    public function openOrder(int $orderId): void
    {
        $order = Order::findOrFail($orderId);

        $this->selectedOrderId = $order->id;
        $this->selectedOrderStatus = $order->status;
        $this->drawerOpen = true;
    }

    public function closeOrder(): void
    {
        $this->drawerOpen = false;
        $this->selectedOrderId = null;
        $this->selectedOrderStatus = '';
    }

    public function updateOrderStatus(): void
    {
        $validated = $this->validate([
            'selectedOrderStatus' => ['required', Rule::in($this->allowedStatuses())],
        ]);

        $order = Order::findOrFail($this->selectedOrderId);
        $order->update(['status' => $validated['selectedOrderStatus']]);

        $this->dispatch('admin-notify', type: 'success', message: "Order {$order->order_number} was updated.");
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

        $count = Order::whereKey($this->selected)->update(['status' => $status]);
        $this->clearSelection();

        $this->dispatch('admin-notify', type: 'success', message: "{$count} ".str('order')->plural($count).' updated.');
    }

    public function clearAll(): void
    {
        $this->search = '';
        $this->status = 'all';
        $this->payment = 'all';
        $this->sort = 'newest';
        $this->resetPage();
        $this->clearSelection();
    }

    /** @return array<int, string> */
    private function allowedStatuses(): array
    {
        return ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
    }

    private function filteredQuery(): Builder
    {
        return Order::query()
            ->with(['user', 'latestPayment'])
            ->withCount('items')
            ->when(filled($this->search), function (Builder $query): void {
                $search = trim($this->search);
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('order_number', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('shipping_full_name', 'like', "%{$search}%")
                        ->orWhere('shipping_phone', 'like', "%{$search}%");
                });
            })
            ->when($this->status !== 'all', fn (Builder $query) => $query->where('status', $this->status))
            ->when($this->payment !== 'all', fn (Builder $query) => $query->whereHas(
                'latestPayment',
                fn (Builder $query) => $query->where('status', $this->payment)
            ))
            ->when($this->sort === 'oldest', fn (Builder $query) => $query->oldest())
            ->when($this->sort === 'total_desc', fn (Builder $query) => $query->orderByDesc('total'))
            ->when($this->sort === 'total_asc', fn (Builder $query) => $query->orderBy('total'))
            ->when($this->sort === 'newest', fn (Builder $query) => $query->latest());
    }

    public function render()
    {
        $summary = [
            'all' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        $selectedOrder = $this->selectedOrderId
            ? Order::with([
                'user',
                'coupon',
                'shippingLocation',
                'items.variant.images',
                'payments' => fn ($query) => $query->latest(),
            ])->find($this->selectedOrderId)
            : null;

        return view('livewire.admin.orders.index', [
            'orders' => $this->filteredQuery()->paginate(10),
            'summary' => $summary,
            'selectedOrder' => $selectedOrder,
            'hasFilters' => filled($this->search) || $this->status !== 'all'
                || $this->payment !== 'all' || $this->sort !== 'newest',
        ])->layout('layouts.admin');
    }
}
