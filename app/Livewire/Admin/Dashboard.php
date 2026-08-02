<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StoreSetting;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;

class Dashboard extends Component
{
    #[Url]
    public string $range = '30';

    public function setRange(string $range): void
    {
        if (in_array($range, ['7', '30', '90'], true)) {
            $this->range = $range;
        }
    }

    /** @return array{0: Carbon, 1: Carbon} */
    private function dateBounds(bool $previous = false): array
    {
        $days = (int) $this->range;
        $to = $previous
            ? now()->subDays($days)->endOfDay()
            : now()->endOfDay();
        $from = $to->copy()->subDays($days - 1)->startOfDay();

        return [$from, $to];
    }

    private function periodOrders(bool $previous = false): Builder
    {
        [$from, $to] = $this->dateBounds($previous);

        return Order::query()->whereBetween('created_at', [$from, $to]);
    }

    private function comparison(float|int $current, float|int $previous): ?float
    {
        if ((float) $previous === 0.0) {
            return null;
        }

        return round((($current - $previous) / abs($previous)) * 100, 1);
    }

    private function summary(int $lowStockThreshold): array
    {
        $orders = $this->periodOrders();
        $previousOrders = $this->periodOrders(true);
        $grossOrders = (clone $orders)->where('status', '!=', 'cancelled');
        $previousGrossOrders = (clone $previousOrders)->where('status', '!=', 'cancelled');

        $grossSales = (float) (clone $grossOrders)->sum('total');
        $previousGrossSales = (float) (clone $previousGrossOrders)->sum('total');
        $orderCount = (clone $orders)->count();
        $previousOrderCount = (clone $previousOrders)->count();
        $buyers = (clone $grossOrders)->whereNotNull('email')->distinct('email')->count('email');
        $previousBuyers = (clone $previousGrossOrders)->whereNotNull('email')->distinct('email')->count('email');

        [$from, $to] = $this->dateBounds();
        $collected = (float) Payment::query()
            ->where('status', 'paid')
            ->whereHas('order', fn (Builder $query) => $query->whereBetween('created_at', [$from, $to]))
            ->sum('amount');

        return [
            'gross_sales' => $grossSales,
            'gross_sales_change' => $this->comparison($grossSales, $previousGrossSales),
            'collected' => $collected,
            'orders' => $orderCount,
            'orders_change' => $this->comparison($orderCount, $previousOrderCount),
            'buyers' => $buyers,
            'buyers_change' => $this->comparison($buyers, $previousBuyers),
            'average_order' => (clone $grossOrders)->count() > 0
                ? $grossSales / (clone $grossOrders)->count()
                : 0,
            'pending' => (clone $orders)->whereIn('status', ['pending', 'processing'])->count(),
            'stock_attention' => ProductVariant::query()
                ->where('is_active', true)
                ->where('stock_quantity', '<=', $lowStockThreshold)
                ->count(),
        ];
    }

    private function salesTrend(): Collection
    {
        $to = now()->endOfDay();
        $from = $to->copy()->subDays(13)->startOfDay();

        $values = Order::query()
            ->selectRaw('DATE(created_at) as period')
            ->selectRaw('COUNT(*) as orders_count')
            ->selectRaw("SUM(CASE WHEN status <> 'cancelled' THEN total ELSE 0 END) as gross_sales")
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->keyBy('period');

        return collect(CarbonPeriod::create($from->toDateString(), $to->toDateString()))
            ->map(function (Carbon $day) use ($values): array {
                $value = $values->get($day->toDateString());

                return [
                    'date' => $day,
                    'orders' => (int) ($value?->orders_count ?? 0),
                    'sales' => (float) ($value?->gross_sales ?? 0),
                ];
            });
    }

    private function pipeline(): array
    {
        $counts = $this->periodOrders()
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        return collect(['pending', 'processing', 'shipped', 'delivered', 'cancelled'])
            ->mapWithKeys(fn (string $status): array => [$status => (int) ($counts[$status] ?? 0)])
            ->all();
    }

    private function topSellers(): Collection
    {
        [$from, $to] = $this->dateBounds();

        return OrderItem::query()
            ->select(['variant_id', 'product_name', 'variant_name', 'variant_sku'])
            ->selectRaw('SUM(quantity) as units_sold')
            ->selectRaw('SUM(subtotal) as revenue')
            ->with('variant.images')
            ->whereHas('order', fn (Builder $query) => $query
                ->whereBetween('created_at', [$from, $to])
                ->where('status', '!=', 'cancelled'))
            ->groupBy('variant_id', 'product_name', 'variant_name', 'variant_sku')
            ->orderByDesc('units_sold')
            ->limit(5)
            ->get();
    }

    private function catalogHealth(): array
    {
        $activeDefault = fn (Builder $query) => $query
            ->where('is_active', true)
            ->where('is_default', true);

        return [
            'active_products' => Product::query()
                ->where('is_active', true)
                ->whereHas('variants', $activeDefault)
                ->count(),
            'draft_products' => Product::query()->where('is_active', false)->count(),
            'incomplete_products' => Product::query()->whereDoesntHave('variants', $activeDefault)->count(),
            'active_variants' => ProductVariant::query()->where('is_active', true)->count(),
        ];
    }

    public function render()
    {
        if (! in_array($this->range, ['7', '30', '90'], true)) {
            $this->range = '30';
        }

        $settings = StoreSetting::current();
        $trend = $this->salesTrend();
        $trendMaximum = max(1, (float) $trend->max('sales'));

        return view('livewire.admin.dashboard', [
            'settings' => $settings,
            'summary' => $this->summary($settings->low_stock_threshold),
            'trend' => $trend,
            'trendMaximum' => $trendMaximum,
            'pipeline' => $this->pipeline(),
            'recentOrders' => $this->periodOrders()
                ->with(['latestPayment'])
                ->withCount('items')
                ->latest()
                ->limit(5)
                ->get(),
            'topSellers' => $this->topSellers(),
            'lowStockVariants' => ProductVariant::query()
                ->with(['product', 'images'])
                ->where('is_active', true)
                ->where('stock_quantity', '<=', $settings->low_stock_threshold)
                ->orderBy('stock_quantity')
                ->limit(5)
                ->get(),
            'catalogHealth' => $this->catalogHealth(),
        ])->layout('layouts.admin');
    }
}
