@php
    $rangeLabels = ['7' => '7 days', '30' => '30 days', '90' => '90 days', 'all' => 'All time'];
    $orderStatusTones = [
        'pending' => 'amber',
        'processing' => 'blue',
        'shipped' => 'blue',
        'delivered' => 'green',
        'cancelled' => 'red',
    ];
    $paymentTones = ['paid' => 'green', 'pending' => 'amber', 'failed' => 'red', 'refunded' => 'blue'];
    $trendMax = $tab === 'sales' ? max(1, (float) $trend->max('sales')) : 1;
@endphp

<div class="mx-auto w-full max-w-[1500px] space-y-6"
    x-data="{ customRangeOpen: @js($range === 'custom') }">
    <header class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex min-w-0 items-center gap-2.5">
            <h1 class="truncate text-xl font-semibold tracking-[-0.025em] text-base-content sm:text-[1.35rem]">
                Reports & insights
            </h1>
            <span
                class="hidden rounded-lg border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-2 py-1 text-[9px] font-medium text-base-content/50 shadow-admin-control sm:inline-flex">
                {{ $rangeLabel }}
            </span>
        </div>

        <button type="button" wire:click="exportCsv" wire:loading.attr="disabled" wire:target="exportCsv"
            class="btn btn-primary btn-sm h-10 min-h-10 w-full gap-2 rounded-xl border-accent bg-accent px-4 text-xs font-semibold text-accent-content shadow-lg shadow-accent/15 sm:w-auto">
            <span wire:loading wire:target="exportCsv" class="loading loading-spinner loading-xs"></span>
            <svg wire:loading.remove wire:target="exportCsv" class="size-3.5" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" d="M12 3v12m0 0 4-4m-4 4-4-4M5 20h14" />
            </svg>
            Export CSV
        </button>
    </header>

    <section
        class="overflow-hidden rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] shadow-admin-panel"
        wire:loading.class.delay="opacity-60"
        wire:target="tab,range,from,to,setRange,applyCustomRange">
        <div class="flex flex-col border-b border-[var(--admin-border)] bg-[var(--admin-surface-raised)] lg:flex-row lg:items-center lg:justify-between">
            <nav class="flex min-w-0 gap-1 overflow-x-auto p-2" role="tablist" aria-label="Report type">
                @foreach ([
                    'sales' => ['Sales', 'Order value and collection'],
                    'products' => ['Products', 'Sell-through and stock'],
                    'customers' => ['Customers', 'Buyer value and retention'],
                ] as $value => [$label, $hint])
                    <button type="button" wire:click="$set('tab', '{{ $value }}')" role="tab"
                        aria-selected="{{ $tab === $value ? 'true' : 'false' }}"
                        @class([
                            'group relative flex h-12 shrink-0 items-center gap-2.5 rounded-xl border px-3 text-left transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-accent/30',
                            'border-[var(--admin-border-strong)] bg-[var(--admin-surface)] text-base-content shadow-admin-control' => $tab === $value,
                            'border-transparent text-base-content/40 hover:bg-[var(--admin-surface-soft)] hover:text-base-content' => $tab !== $value,
                        ])>
                        <span
                            class="grid size-8 place-items-center rounded-lg border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] text-base-content/55 shadow-inner">
                            @if ($value === 'sales')
                                <svg class="size-3.5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.8"><path d="M5 20V10m5 10V4m5 16v-7m5 7V7" /></svg>
                            @elseif ($value === 'products')
                                <svg class="size-3.5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.8"><path d="m4 8 8-4 8 4-8 4-8-4Zm0 0v8l8 4 8-4V8M12 12v8" /></svg>
                            @else
                                <svg class="size-3.5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.8"><path d="M16 20v-1.5a3.5 3.5 0 0 0-3.5-3.5h-5A3.5 3.5 0 0 0 4 18.5V20m5.75-9a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7ZM17 8h4m-2-2v4" /></svg>
                            @endif
                        </span>
                        <span>
                            <strong class="block text-[11px] font-semibold">{{ $label }}</strong>
                            <small class="hidden text-[8px] text-base-content/30 xl:block">{{ $hint }}</small>
                        </span>
                        @if ($tab === $value)
                            <span class="absolute inset-x-3 bottom-0 h-0.5 rounded-full bg-accent"></span>
                        @endif
                    </button>
                @endforeach
            </nav>

            <div class="flex flex-wrap items-center gap-1.5 border-t border-[var(--admin-border)] px-3 py-2.5 lg:border-l lg:border-t-0">
                @foreach ($rangeLabels as $value => $label)
                    <button type="button" wire:click="setRange('{{ $value }}')"
                        @click="customRangeOpen = false"
                        @class([
                            'btn btn-ghost btn-xs h-8 min-h-8 rounded-lg border px-2.5 text-[9px]',
                            'border-[var(--admin-border-strong)] bg-[var(--admin-surface)] text-base-content shadow-admin-control' => $range === $value,
                            'border-transparent text-base-content/45 shadow-none hover:bg-[var(--admin-surface-soft)] hover:text-base-content' => $range !== $value,
                        ])>
                        {{ $label }}
                    </button>
                @endforeach
                <button type="button" @click="customRangeOpen = !customRangeOpen"
                    @class([
                        'btn btn-ghost btn-xs h-8 min-h-8 gap-1.5 rounded-lg border px-2.5 text-[9px]',
                        'border-[var(--admin-border-strong)] bg-[var(--admin-surface)] text-base-content shadow-admin-control' => $range === 'custom',
                        'border-transparent text-base-content/45 shadow-none hover:bg-[var(--admin-surface-soft)] hover:text-base-content' => $range !== 'custom',
                    ])>
                    <svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8"><path d="M5 4v3m14-3v3M4 9h16M5 6h14a1 1 0 0 1 1 1v13H4V7a1 1 0 0 1 1-1Z" /></svg>
                    Custom
                </button>
            </div>
        </div>

        <form x-cloak x-show="customRangeOpen" x-collapse wire:submit="applyCustomRange"
            class="flex flex-col gap-3 border-b border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] px-4 py-4 shadow-inner sm:flex-row sm:items-end">
            <label class="form-control w-full max-w-48">
                <span class="mb-1 text-[9px] font-medium text-base-content/45">From</span>
                <input type="date" wire:model="from"
                    class="input input-sm h-10 min-h-10 rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-raised)] text-[10px] shadow-admin-control focus:border-accent focus:outline-none">
                @error('from') <span class="mt-1 text-[9px] text-error">{{ $message }}</span> @enderror
            </label>
            <label class="form-control w-full max-w-48">
                <span class="mb-1 text-[9px] font-medium text-base-content/45">To</span>
                <input type="date" wire:model="to"
                    class="input input-sm h-10 min-h-10 rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-raised)] text-[10px] shadow-admin-control focus:border-accent focus:outline-none">
                @error('to') <span class="mt-1 text-[9px] text-error">{{ $message }}</span> @enderror
            </label>
            <button class="btn btn-neutral btn-sm h-10 min-h-10 rounded-xl border-[var(--admin-border-strong)] px-4 text-[10px] shadow-admin-control">Apply range</button>
        </form>
    </section>

    @if ($tab === 'sales')
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                ['Gross sales', '$'.number_format($summary['gross_sales'], 2), 'Non-cancelled order value', 'accent'],
                ['Collected', '$'.number_format($summary['collected'], 2), 'Payments marked paid', 'success'],
                ['Orders', number_format($summary['orders']), number_format($summary['pending']).' awaiting fulfilment', 'info'],
                ['Average order', '$'.number_format($summary['average_order_value'], 2), number_format($summary['cancellation_rate'], 1).'% cancellation rate', 'warning'],
            ] as [$label, $value, $hint, $tone])
                <article class="relative overflow-hidden rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] p-5 shadow-admin-panel transition-all hover:-translate-y-0.5 hover:border-[var(--admin-border-strong)] motion-reduce:transform-none">
                    <span @class([
                        'absolute inset-y-0 left-0 w-0.5',
                        'bg-accent' => $tone === 'accent',
                        'bg-success' => $tone === 'success',
                        'bg-info' => $tone === 'info',
                        'bg-warning' => $tone === 'warning',
                    ])></span>
                    <p class="text-[9px] font-semibold uppercase tracking-[0.12em] text-base-content/35">{{ $label }}</p>
                    <strong class="mt-3 block text-xl font-semibold tabular-nums tracking-tight">{{ $value }}</strong>
                    <small class="mt-1 block text-[9px] text-base-content/35">{{ $hint }}</small>
                </article>
            @endforeach
        </section>

        <section class="rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] p-5 shadow-admin-panel">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-xs font-semibold">Recent sales movement</h2>
                    <p class="mt-0.5 text-[9px] text-base-content/35">The latest 14 calendar days inside this report range.</p>
                </div>
                <div class="flex items-center gap-3 text-[8px] text-base-content/35">
                    <span class="flex items-center gap-1"><i class="size-1.5 rounded-full bg-accent"></i> Gross sales</span>
                    <span>{{ $summary['delivered'] }} delivered</span>
                </div>
            </div>
            <div class="mt-5 flex h-36 items-end gap-1.5 sm:gap-2">
                @foreach ($trend as $point)
                    <div class="group flex min-w-0 flex-1 flex-col items-center justify-end gap-2" title="{{ $point['date']->format('M j') }}: ${{ number_format($point['sales'], 2) }}">
                        <span class="hidden text-[7px] font-medium tabular-nums text-base-content/30 group-hover:block">
                            ${{ number_format($point['sales'], 0) }}
                        </span>
                        <span class="w-full max-w-8 rounded-t-md border border-accent/30 bg-accent/70 shadow-admin-control transition-colors group-hover:bg-accent"
                            style="height: {{ max(3, ($point['sales'] / $trendMax) * 100) }}px"></span>
                        <span class="text-[7px] text-base-content/30">{{ $point['date']->format('j') }}</span>
                    </div>
                @endforeach
            </div>
        </section>
    @elseif ($tab === 'products')
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                ['Units sold', number_format($summary['units_sold']), 'Across non-cancelled orders'],
                ['Merchandise sales', '$'.number_format($summary['merchandise_sales'], 2), 'Order-item value'],
                ['Variants sold', number_format($summary['variants_sold']), $summary['best_seller'] ?: 'No sales in range'],
                ['Stock attention', number_format($summary['low_stock'] + $summary['out_of_stock']), $summary['out_of_stock'].' out · '.$summary['low_stock'].' low'],
            ] as [$label, $value, $hint])
                <article class="rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] p-5 shadow-admin-panel transition-all hover:-translate-y-0.5 hover:border-[var(--admin-border-strong)] motion-reduce:transform-none">
                    <p class="text-[9px] font-semibold uppercase tracking-[0.12em] text-base-content/35">{{ $label }}</p>
                    <strong class="mt-3 block text-xl font-semibold tabular-nums tracking-tight">{{ $value }}</strong>
                    <small class="mt-1 block truncate text-[9px] text-base-content/35">{{ $hint }}</small>
                </article>
            @endforeach
        </section>
    @else
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                ['Buyers', number_format($summary['buyers']), $summary['registered_buyers'].' registered · '.$summary['guest_buyers'].' guest'],
                ['Repeat buyers', number_format($summary['repeat_buyers']), 'Placed more than one order'],
                ['New accounts', number_format($summary['new_accounts']), 'Registered during this range'],
                ['Average buyer value', '$'.number_format($summary['average_customer_value'], 2), 'Non-cancelled gross spend'],
            ] as [$label, $value, $hint])
                <article class="rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] p-5 shadow-admin-panel transition-all hover:-translate-y-0.5 hover:border-[var(--admin-border-strong)] motion-reduce:transform-none">
                    <p class="text-[9px] font-semibold uppercase tracking-[0.12em] text-base-content/35">{{ $label }}</p>
                    <strong class="mt-3 block text-xl font-semibold tabular-nums tracking-tight">{{ $value }}</strong>
                    <small class="mt-1 block text-[9px] text-base-content/35">{{ $hint }}</small>
                </article>
            @endforeach
        </section>
    @endif

    <section class="overflow-hidden rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] shadow-admin-panel">
        <header class="flex items-center justify-between gap-3 border-b border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-4 py-3.5">
            <div>
                <h2 class="text-xs font-semibold">
                    {{ $tab === 'sales' ? 'Orders in range' : ($tab === 'products' ? 'Product performance' : 'Buyer performance') }}
                </h2>
                <p class="mt-0.5 text-[9px] text-base-content/35">
                    {{ $rows->total() }} {{ Str::plural($tab === 'sales' ? 'order' : ($tab === 'products' ? 'variant' : 'buyer'), $rows->total()) }}
                </p>
            </div>
            <span class="rounded-lg border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] px-2 py-1 text-[8px] font-medium text-base-content/40 shadow-inner">{{ $rangeLabel }}</span>
        </header>

        <div class="hidden overflow-x-auto xl:block">
            @if ($tab === 'sales')
                <table class="table table-sm w-full">
                    <thead class="bg-[var(--admin-surface-sunken)] text-[9px] uppercase tracking-[0.1em] text-base-content/40">
                        <tr><th class="px-4 py-2.5">Order</th><th>Customer</th><th>Date</th><th>Items</th><th>Payment</th><th>Status</th><th class="px-4 text-right">Total</th></tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--admin-border)]">
                        @forelse ($rows as $order)
                            <tr wire:key="report-order-{{ $order->id }}" class="transition-colors hover:bg-[var(--admin-surface-soft)]">
                                <td class="px-4 py-3.5"><a href="{{ route('admin.orders.index', ['search' => $order->order_number]) }}" class="text-[11px] font-semibold hover:text-primary">{{ $order->order_number }}</a></td>
                                <td><span class="block text-[11px] font-medium">{{ $order->shipping_full_name }}</span><small class="text-[9px] text-base-content/35">{{ $order->email }}</small></td>
                                <td class="text-[10px] text-base-content/55">{{ $order->created_at->format('M j, Y') }}</td>
                                <td class="text-[11px] tabular-nums">{{ $order->items_count }}</td>
                                <td><x-admin.badge :tone="$paymentTones[$order->latestPayment?->status] ?? 'gray'">{{ str($order->latestPayment?->status ?? 'Unrecorded')->title() }}</x-admin.badge></td>
                                <td><x-admin.badge :tone="$orderStatusTones[$order->status] ?? 'gray'">{{ str($order->status)->title() }}</x-admin.badge></td>
                                <td class="px-4 text-right text-[11px] font-semibold tabular-nums">${{ number_format($order->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7"><x-admin.empty-state title="No sales in this range" description="Choose a wider date range to inspect historical orders." /></td></tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif ($tab === 'products')
                <table class="table table-sm w-full">
                    <thead class="bg-[var(--admin-surface-sunken)] text-[9px] uppercase tracking-[0.1em] text-base-content/40">
                        <tr><th class="px-4 py-2.5">Product</th><th>SKU</th><th>Orders</th><th>Units sold</th><th>Current stock</th><th class="px-4 text-right">Revenue</th></tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--admin-border)]">
                        @forelse ($rows as $item)
                            <tr wire:key="report-product-{{ $item->variant_id }}" class="transition-colors hover:bg-[var(--admin-surface-soft)]">
                                <td class="px-4 py-3.5"><span class="block text-[11px] font-semibold">{{ $item->product_name }}</span><small class="text-[9px] text-base-content/35">{{ $item->variant_name }}</small></td>
                                <td class="font-mono text-[9px] text-base-content/45">{{ $item->variant_sku }}</td>
                                <td class="text-[11px] tabular-nums">{{ $item->order_count }}</td>
                                <td class="text-[11px] font-semibold tabular-nums">{{ $item->units_sold }}</td>
                                <td><x-admin.badge :tone="$item->variant?->stock_quantity === 0 ? 'red' : ($item->variant?->stock_quantity <= $lowStockThreshold ? 'amber' : 'gray')">{{ $item->variant?->stock_quantity ?? 'Unavailable' }}</x-admin.badge></td>
                                <td class="px-4 text-right text-[11px] font-semibold tabular-nums">${{ number_format($item->revenue, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6"><x-admin.empty-state title="No product sales in this range" description="Product performance appears after non-cancelled orders contain items." /></td></tr>
                        @endforelse
                    </tbody>
                </table>
            @else
                <table class="table table-sm w-full">
                    <thead class="bg-[var(--admin-surface-sunken)] text-[9px] uppercase tracking-[0.1em] text-base-content/40">
                        <tr><th class="px-4 py-2.5">Buyer</th><th>Type</th><th>Orders</th><th>Last order</th><th class="px-4 text-right">Gross spend</th></tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--admin-border)]">
                        @forelse ($rows as $customer)
                            <tr wire:key="report-customer-{{ md5($customer->email) }}" class="transition-colors hover:bg-[var(--admin-surface-soft)]">
                                <td class="px-4 py-3.5"><span class="block text-[11px] font-semibold">{{ $customer->customer_name }}</span><small class="text-[9px] text-base-content/35">{{ $customer->email }}</small></td>
                                <td><x-admin.badge :tone="$customer->user_id ? 'green' : 'gray'">{{ $customer->user_id ? 'Registered' : 'Guest' }}</x-admin.badge></td>
                                <td class="text-[11px] font-semibold tabular-nums">{{ $customer->orders_count }}</td>
                                <td class="text-[10px] text-base-content/55">{{ \Carbon\Carbon::parse($customer->last_order_at)->format('M j, Y') }}</td>
                                <td class="px-4 text-right text-[11px] font-semibold tabular-nums">${{ number_format($customer->lifetime_value, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5"><x-admin.empty-state title="No buyers in this range" description="Buyer insights appear after non-cancelled orders are placed." /></td></tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>

        <div class="divide-y divide-[var(--admin-border)] xl:hidden">
            @forelse ($rows as $row)
                <article class="p-4" wire:key="report-mobile-{{ $tab }}-{{ $tab === 'products' ? $row->variant_id : ($tab === 'customers' ? md5($row->email) : $row->id) }}">
                    @if ($tab === 'sales')
                        <div class="flex items-start justify-between gap-3"><span><a href="{{ route('admin.orders.index', ['search' => $row->order_number]) }}" class="text-xs font-semibold">{{ $row->order_number }}</a><small class="mt-1 block text-[9px] text-base-content/35">{{ $row->shipping_full_name }} · {{ $row->created_at->format('M j, Y') }}</small></span><strong class="text-xs tabular-nums">${{ number_format($row->total, 2) }}</strong></div>
                        <div class="mt-3 flex items-center gap-2"><x-admin.badge :tone="$orderStatusTones[$row->status] ?? 'gray'">{{ str($row->status)->title() }}</x-admin.badge><x-admin.badge :tone="$paymentTones[$row->latestPayment?->status] ?? 'gray'">{{ str($row->latestPayment?->status ?? 'Unrecorded')->title() }}</x-admin.badge><span class="ml-auto text-[9px] text-base-content/35">{{ $row->items_count }} {{ Str::plural('item', $row->items_count) }}</span></div>
                    @elseif ($tab === 'products')
                        <div class="flex items-start justify-between gap-3"><span><strong class="block text-xs">{{ $row->product_name }}</strong><small class="mt-1 block text-[9px] text-base-content/35">{{ $row->variant_name }} · {{ $row->variant_sku }}</small></span><strong class="text-xs tabular-nums">${{ number_format($row->revenue, 2) }}</strong></div>
                        <dl class="mt-3 grid grid-cols-3 rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] p-3 shadow-inner"><div><dt class="text-[8px] text-base-content/35">Orders</dt><dd class="mt-1 text-[11px] font-semibold">{{ $row->order_count }}</dd></div><div><dt class="text-[8px] text-base-content/35">Units</dt><dd class="mt-1 text-[11px] font-semibold">{{ $row->units_sold }}</dd></div><div><dt class="text-[8px] text-base-content/35">Stock</dt><dd class="mt-1 text-[11px] font-semibold">{{ $row->variant?->stock_quantity ?? '—' }}</dd></div></dl>
                    @else
                        <div class="flex items-start justify-between gap-3"><span><strong class="block text-xs">{{ $row->customer_name }}</strong><small class="mt-1 block text-[9px] text-base-content/35">{{ $row->email }}</small></span><x-admin.badge :tone="$row->user_id ? 'green' : 'gray'">{{ $row->user_id ? 'Registered' : 'Guest' }}</x-admin.badge></div>
                        <dl class="mt-3 grid grid-cols-3 rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] p-3 shadow-inner"><div><dt class="text-[8px] text-base-content/35">Orders</dt><dd class="mt-1 text-[11px] font-semibold">{{ $row->orders_count }}</dd></div><div><dt class="text-[8px] text-base-content/35">Spend</dt><dd class="mt-1 text-[11px] font-semibold">${{ number_format($row->lifetime_value, 2) }}</dd></div><div><dt class="text-[8px] text-base-content/35">Last order</dt><dd class="mt-1 text-[11px] font-semibold">{{ \Carbon\Carbon::parse($row->last_order_at)->format('M j') }}</dd></div></dl>
                    @endif
                </article>
            @empty
                <x-admin.empty-state title="No report data" description="Choose a wider date range to inspect historical performance." />
            @endforelse
        </div>

        <x-admin.pagination :paginator="$rows" :noun="$tab === 'sales' ? 'order' : ($tab === 'products' ? 'variant' : 'buyer')" />
    </section>
</div>
