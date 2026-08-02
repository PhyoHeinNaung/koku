<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ProductVariant;
use App\Models\StoreSetting;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $tab = 'sales';

    #[Url]
    public string $range = '30';

    #[Url]
    public string $from = '';

    #[Url]
    public string $to = '';

    public function updatedTab(): void
    {
        $this->validateOnly('tab', [
            'tab' => ['required', Rule::in(['sales', 'products', 'customers'])],
        ]);
        $this->resetPage();
    }

    public function setRange(string $range): void
    {
        if (! in_array($range, ['7', '30', '90', 'all'], true)) {
            return;
        }

        $this->range = $range;
        $this->from = '';
        $this->to = '';
        $this->resetPage();
        $this->resetValidation();
    }

    public function applyCustomRange(): void
    {
        $validated = $this->validate([
            'from' => ['required', 'date'],
            'to' => ['required', 'date', 'after_or_equal:from'],
        ]);

        $this->from = $validated['from'];
        $this->to = $validated['to'];
        $this->range = 'custom';
        $this->resetPage();
    }

    public function exportCsv(): StreamedResponse
    {
        [$headers, $rows] = match ($this->tab) {
            'products' => $this->productExport(),
            'customers' => $this->customerExport(),
            default => $this->salesExport(),
        };

        $filename = 'ticks-'.$this->tab.'-report-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($headers, $rows): void {
            $stream = fopen('php://output', 'w');
            fputcsv($stream, $headers);

            foreach ($rows as $row) {
                fputcsv($stream, $row);
            }

            fclose($stream);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    /** @return array{0: ?Carbon, 1: Carbon} */
    private function dateBounds(): array
    {
        $to = $this->range === 'custom' && filled($this->to)
            ? Carbon::parse($this->to)->endOfDay()
            : now()->endOfDay();

        $from = match ($this->range) {
            '7' => $to->copy()->subDays(6)->startOfDay(),
            '30' => $to->copy()->subDays(29)->startOfDay(),
            '90' => $to->copy()->subDays(89)->startOfDay(),
            'custom' => filled($this->from) ? Carbon::parse($this->from)->startOfDay() : null,
            default => null,
        };

        return [$from, $to];
    }

    private function applyDateRange(Builder $query, string $column = 'created_at'): Builder
    {
        [$from, $to] = $this->dateBounds();

        return $query
            ->when($from, fn (Builder $query) => $query->where($column, '>=', $from))
            ->where($column, '<=', $to);
    }

    private function salesSummary(): array
    {
        $orders = $this->applyDateRange(Order::query());
        $grossOrders = (clone $orders)->where('status', '!=', 'cancelled');
        $totalOrders = (clone $orders)->count();
        $grossSales = (float) (clone $grossOrders)->sum('total');

        $collected = (float) Payment::query()
            ->where('status', 'paid')
            ->whereHas('order', fn (Builder $query) => $this->applyDateRange($query))
            ->sum('amount');

        return [
            'orders' => $totalOrders,
            'gross_sales' => $grossSales,
            'collected' => $collected,
            'average_order_value' => (clone $grossOrders)->count() > 0
                ? $grossSales / (clone $grossOrders)->count()
                : 0,
            'delivered' => (clone $orders)->where('status', 'delivered')->count(),
            'pending' => (clone $orders)->whereIn('status', ['pending', 'processing'])->count(),
            'cancelled' => (clone $orders)->where('status', 'cancelled')->count(),
            'cancellation_rate' => $totalOrders > 0
                ? ((clone $orders)->where('status', 'cancelled')->count() / $totalOrders) * 100
                : 0,
        ];
    }

    private function salesTrend(): Collection
    {
        [$from, $to] = $this->dateBounds();
        $trendFrom = ($from && $from->greaterThan($to->copy()->subDays(13)))
            ? $from->copy()
            : $to->copy()->subDays(13)->startOfDay();

        $values = Order::query()
            ->selectRaw('DATE(created_at) as period')
            ->selectRaw('COUNT(*) as orders_count')
            ->selectRaw("SUM(CASE WHEN status <> 'cancelled' THEN total ELSE 0 END) as gross_sales")
            ->whereBetween('created_at', [$trendFrom, $to])
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->keyBy('period');

        return collect(CarbonPeriod::create($trendFrom->toDateString(), $to->toDateString()))
            ->map(function (Carbon $day) use ($values): array {
                $value = $values->get($day->toDateString());

                return [
                    'date' => $day,
                    'orders' => (int) ($value?->orders_count ?? 0),
                    'sales' => (float) ($value?->gross_sales ?? 0),
                ];
            });
    }

    private function productPerformanceQuery(): Builder
    {
        return OrderItem::query()
            ->select(['variant_id', 'product_name', 'variant_name', 'variant_sku'])
            ->selectRaw('SUM(quantity) as units_sold')
            ->selectRaw('SUM(subtotal) as revenue')
            ->selectRaw('COUNT(DISTINCT order_id) as order_count')
            ->with(['variant.product', 'variant.images'])
            ->whereHas('order', function (Builder $query): void {
                $this->applyDateRange($query)->where('status', '!=', 'cancelled');
            })
            ->groupBy('variant_id', 'product_name', 'variant_name', 'variant_sku');
    }

    private function productSummary(): array
    {
        $lowStockThreshold = StoreSetting::current()->low_stock_threshold;
        $items = OrderItem::query()->whereHas('order', function (Builder $query): void {
            $this->applyDateRange($query)->where('status', '!=', 'cancelled');
        });

        $bestSeller = (clone $this->productPerformanceQuery())
            ->orderByDesc('units_sold')
            ->first();

        return [
            'units_sold' => (int) (clone $items)->sum('quantity'),
            'merchandise_sales' => (float) (clone $items)->sum('subtotal'),
            'variants_sold' => (clone $items)->distinct('variant_id')->count('variant_id'),
            'best_seller' => $bestSeller?->product_name,
            'low_stock' => ProductVariant::where('is_active', true)
                ->whereBetween('stock_quantity', [1, $lowStockThreshold])
                ->count(),
            'out_of_stock' => ProductVariant::where('is_active', true)
                ->where('stock_quantity', 0)
                ->count(),
        ];
    }

    private function customerPerformanceQuery(): Builder
    {
        return Order::query()
            ->selectRaw('MAX(id) as id')
            ->selectRaw('MAX(user_id) as user_id')
            ->selectRaw('email')
            ->selectRaw('MAX(shipping_full_name) as customer_name')
            ->selectRaw('COUNT(*) as orders_count')
            ->selectRaw('SUM(total) as lifetime_value')
            ->selectRaw('MAX(created_at) as last_order_at')
            ->where('status', '!=', 'cancelled')
            ->whereNotNull('email')
            ->tap(fn (Builder $query) => $this->applyDateRange($query))
            ->groupBy('email');
    }

    private function customerSummary(): array
    {
        $buyers = (clone $this->customerPerformanceQuery())->get();
        [$from, $to] = $this->dateBounds();

        $newAccounts = User::query()
            ->where('role', 'user')
            ->when($from, fn (Builder $query) => $query->where('created_at', '>=', $from))
            ->where('created_at', '<=', $to)
            ->count();

        return [
            'buyers' => $buyers->count(),
            'registered_buyers' => $buyers->whereNotNull('user_id')->count(),
            'guest_buyers' => $buyers->whereNull('user_id')->count(),
            'repeat_buyers' => $buyers->where('orders_count', '>', 1)->count(),
            'new_accounts' => $newAccounts,
            'average_customer_value' => $buyers->count() > 0
                ? (float) $buyers->sum('lifetime_value') / $buyers->count()
                : 0,
        ];
    }

    /** @return array{0: array<int, string>, 1: Collection<int, array<int, mixed>>} */
    private function salesExport(): array
    {
        $orders = $this->applyDateRange(Order::query())
            ->with('latestPayment')
            ->latest()
            ->get();

        return [
            ['Order', 'Customer', 'Email', 'Date', 'Status', 'Payment', 'Total'],
            $orders->map(fn (Order $order): array => [
                $order->order_number,
                $order->shipping_full_name,
                $order->email,
                $order->created_at->format('Y-m-d'),
                $order->status,
                $order->latestPayment?->status ?? 'unrecorded',
                $order->total,
            ]),
        ];
    }

    /** @return array{0: array<int, string>, 1: Collection<int, array<int, mixed>>} */
    private function productExport(): array
    {
        $products = $this->productPerformanceQuery()->orderByDesc('revenue')->get();

        return [
            ['Product', 'Variant', 'SKU', 'Units sold', 'Orders', 'Revenue'],
            $products->map(fn (OrderItem $item): array => [
                $item->product_name,
                $item->variant_name,
                $item->variant_sku,
                $item->units_sold,
                $item->order_count,
                $item->revenue,
            ]),
        ];
    }

    /** @return array{0: array<int, string>, 1: Collection<int, array<int, mixed>>} */
    private function customerExport(): array
    {
        $customers = $this->customerPerformanceQuery()->orderByDesc('lifetime_value')->get();

        return [
            ['Customer', 'Email', 'Type', 'Orders', 'Gross spend', 'Last order'],
            $customers->map(fn (Order $customer): array => [
                $customer->customer_name,
                $customer->email,
                $customer->user_id ? 'Registered' : 'Guest',
                $customer->orders_count,
                $customer->lifetime_value,
                Carbon::parse($customer->last_order_at)->format('Y-m-d'),
            ]),
        ];
    }

    public function render()
    {
        [$from, $to] = $this->dateBounds();
        $rangeLabel = $from
            ? $from->format('M j, Y').' - '.$to->format('M j, Y')
            : 'All recorded time';

        $data = match ($this->tab) {
            'products' => [
                'summary' => $this->productSummary(),
                'rows' => $this->productPerformanceQuery()->orderByDesc('revenue')->paginate(10),
            ],
            'customers' => [
                'summary' => $this->customerSummary(),
                'rows' => $this->customerPerformanceQuery()->orderByDesc('lifetime_value')->paginate(10),
            ],
            default => [
                'summary' => $this->salesSummary(),
                'rows' => $this->applyDateRange(Order::query())
                    ->with(['user', 'latestPayment'])
                    ->withCount('items')
                    ->latest()
                    ->paginate(10),
                'trend' => $this->salesTrend(),
            ],
        };

        return view('livewire.admin.reports.index', [
            ...$data,
            'rangeLabel' => $rangeLabel,
            'lowStockThreshold' => StoreSetting::current()->low_stock_threshold,
        ])->layout('layouts.admin');
    }
}
