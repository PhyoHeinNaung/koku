@php
    $hour = now()->hour;
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
    $statusTones = [
        'pending' => 'amber',
        'processing' => 'blue',
        'shipped' => 'purple',
        'delivered' => 'green',
        'cancelled' => 'red',
    ];
    $pipelineMeta = [
        'pending' => ['Pending', 'bg-warning'],
        'processing' => ['Processing', 'bg-info'],
        'shipped' => ['Shipped', 'bg-secondary'],
        'delivered' => ['Delivered', 'bg-success'],
        'cancelled' => ['Cancelled', 'bg-error'],
    ];
    $pipelineTotal = max(1, array_sum($pipeline));
    $metrics = [
        [
            'label' => 'Gross sales',
            'value' => '$'.number_format($summary['gross_sales'], 2),
            'helper' => '$'.number_format($summary['collected'], 2).' collected',
            'change' => $summary['gross_sales_change'],
            'icon' => 'M4 7h16v10H4V7Zm3 3h.01M17 14h.01M12 10.5a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z',
        ],
        [
            'label' => 'Orders',
            'value' => number_format($summary['orders']),
            'helper' => number_format($summary['pending']).' awaiting action',
            'change' => $summary['orders_change'],
            'icon' => 'M5 5h14v15H5V5Zm4-2v4m6-4v4M8 11h8m-8 4h5',
        ],
        [
            'label' => 'Buyers',
            'value' => number_format($summary['buyers']),
            'helper' => '$'.number_format($summary['average_order'], 2).' average order',
            'change' => $summary['buyers_change'],
            'icon' => 'M16 20v-1.5a3.5 3.5 0 0 0-3.5-3.5h-5A3.5 3.5 0 0 0 4 18.5V20m5.75-9a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm7.25-1a3 3 0 0 1 0 6',
        ],
        [
            'label' => 'Stock attention',
            'value' => number_format($summary['stock_attention']),
            'helper' => 'At or below '.$settings->low_stock_threshold.' units',
            'change' => null,
            'icon' => 'M4 7.5 12 3l8 4.5v9L12 21l-8-4.5v-9Zm8 4.5 8-4.5M12 12 4 7.5M12 12v9',
        ],
    ];
@endphp

<div class="mx-auto w-full max-w-[1500px] space-y-6">
    <header class="relative overflow-hidden rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] p-5 shadow-admin-panel sm:p-6">
        <div class="pointer-events-none absolute -right-16 -top-20 size-56 rounded-full bg-accent/8 blur-3xl"></div>
        <div class="relative flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-[9px] font-semibold uppercase tracking-[0.18em] text-accent">Operations overview</p>
                <h1 class="mt-1.5 text-xl font-semibold tracking-[-0.025em] text-base-content sm:text-[1.55rem]">
                    {{ $greeting }}, {{ str(auth()->user()->name)->before(' ') }}
                </h1>
                <p class="mt-1.5 text-[11px] text-base-content/45">Here is what needs attention across {{ $settings->store_name }}.</p>

                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
                        class="inline-flex h-7 items-center gap-1.5 rounded-lg border border-warning/20 bg-warning/10 px-2.5 text-[9px] font-semibold text-warning transition hover:border-warning/35">
                        <span class="size-1.5 rounded-full bg-warning"></span>
                        {{ number_format($summary['pending']) }} orders awaiting action
                    </a>
                    <a href="{{ route('admin.products.index', ['sort' => 'stock_asc']) }}"
                        class="inline-flex h-7 items-center gap-1.5 rounded-lg border border-error/20 bg-error/10 px-2.5 text-[9px] font-semibold text-error transition hover:border-error/35">
                        <span class="size-1.5 rounded-full bg-error"></span>
                        {{ number_format($summary['stock_attention']) }} stock alerts
                    </a>
                </div>
            </div>

            <div class="inline-flex w-full rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] p-1 shadow-inner sm:w-auto" aria-label="Dashboard period">
                @foreach (['7' => '7 days', '30' => '30 days', '90' => '90 days'] as $value => $label)
                    <button type="button" wire:click="setRange('{{ $value }}')" wire:loading.attr="disabled"
                        wire:target="setRange"
                        class="h-9 flex-1 rounded-lg border px-3.5 text-[10px] font-semibold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent/30 sm:flex-none
                            {{ $range === $value
                                ? 'border-[var(--admin-border-strong)] bg-[var(--admin-surface-raised)] text-base-content shadow-admin-control'
                                : 'border-transparent text-base-content/45 hover:bg-[var(--admin-surface-soft)] hover:text-base-content' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>
    </header>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Key performance indicators">
        @foreach ($metrics as $metric)
            @php
                $metricAccent = match ($loop->index) {
                    0 => 'bg-accent',
                    1 => 'bg-info',
                    2 => 'bg-success',
                    default => 'bg-warning',
                };
            @endphp
            <article class="group relative overflow-hidden rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] p-5 shadow-admin-panel transition-all hover:-translate-y-0.5 hover:border-[var(--admin-border-strong)] motion-reduce:transform-none motion-reduce:transition-none">
                <span class="absolute inset-x-0 top-0 h-0.5 {{ $metricAccent }}"></span>
                <div class="flex items-start justify-between gap-4">
                    <span class="grid size-10 place-items-center rounded-xl border border-accent/15 bg-accent/10 text-accent shadow-admin-control">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.7" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $metric['icon'] }}" />
                        </svg>
                    </span>

                    @if (! is_null($metric['change']))
                        <span class="inline-flex items-center gap-1 rounded-md px-1.5 py-1 text-[8px] font-semibold
                            {{ $metric['change'] >= 0 ? 'bg-success/10 text-success' : 'bg-error/10 text-error' }}">
                            <svg class="size-2.5 {{ $metric['change'] < 0 ? 'rotate-180' : '' }}"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m6 15 6-6 6 6" />
                            </svg>
                            {{ number_format(abs($metric['change']), 1) }}%
                        </span>
                    @endif
                </div>

                <p class="mt-5 text-[9px] font-semibold uppercase tracking-[0.12em] text-base-content/40">{{ $metric['label'] }}</p>
                <strong class="mt-1.5 block text-[1.35rem] font-semibold tracking-tight tabular-nums">{{ $metric['value'] }}</strong>
                <p class="mt-2 text-[9px] text-base-content/40">{{ $metric['helper'] }}</p>
            </article>
        @endforeach
    </section>

    <div class="grid gap-4 xl:grid-cols-[minmax(0,1.65fr)_minmax(17rem,.7fr)]">
        <section class="overflow-hidden rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] shadow-admin-panel">
            <header class="flex items-start justify-between gap-4 border-b border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-5 py-4">
                <div>
                    <h2 class="text-sm font-semibold">Sales movement</h2>
                    <p class="mt-0.5 text-[9px] text-base-content/38">Gross sales across the latest 14 days.</p>
                </div>
                <a href="{{ route('admin.reports.index') }}"
                    class="btn btn-ghost btn-xs h-7 min-h-7 rounded-lg px-2 text-[9px] font-medium">
                    Full report
                    <svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6" />
                    </svg>
                </a>
            </header>

            <div class="p-5">
                <div class="overflow-x-auto pb-1">
                    <div class="flex h-56 min-w-[34rem] items-end gap-2 sm:min-w-0">
                        @foreach ($trend as $point)
                            @php
                                $height = $point['sales'] > 0
                                    ? max(8, ($point['sales'] / $trendMaximum) * 100)
                                    : 2;
                            @endphp
                            <div class="group relative flex h-full min-w-0 flex-1 flex-col items-center justify-end gap-2"
                                title="{{ $point['date']->format('M j') }}: ${{ number_format($point['sales'], 2) }}">
                                <div class="pointer-events-none absolute bottom-9 left-1/2 z-10 hidden -translate-x-1/2 whitespace-nowrap rounded-lg bg-neutral px-2 py-1.5 text-[8px] text-neutral-content shadow-lg group-hover:block">
                                    ${{ number_format($point['sales'], 0) }} · {{ $point['orders'] }} orders
                                </div>
                                <div class="w-full max-w-7 rounded-t-md border border-[var(--admin-border-strong)] bg-[var(--admin-surface-soft)] shadow-admin-control transition-all group-hover:border-accent/60 group-hover:bg-accent"
                                    style="height: {{ $height }}%"></div>
                                <span class="text-[8px] text-base-content/30">{{ $point['date']->format('j') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="mt-3 flex items-center justify-between border-t border-[var(--admin-border)] pt-3 text-[9px] text-base-content/35">
                    <span>{{ $trend->first()['date']->format('M j') }}</span>
                    <span class="inline-flex items-center gap-1.5">
                        <span class="size-1.5 rounded-full bg-accent"></span>
                        Daily gross sales
                    </span>
                    <span>{{ $trend->last()['date']->format('M j') }}</span>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] p-5 shadow-admin-panel">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-sm font-semibold">Order pipeline</h2>
                    <p class="mt-0.5 text-[9px] text-base-content/38">Selected period fulfilment.</p>
                </div>
                <span class="rounded-lg border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] px-2 py-1 text-[9px] font-semibold tabular-nums shadow-inner">
                    {{ number_format(array_sum($pipeline)) }}
                </span>
            </div>

            <div class="mt-5 space-y-4">
                @foreach ($pipelineMeta as $status => [$label, $color])
                    @php $percentage = ($pipeline[$status] / $pipelineTotal) * 100; @endphp
                    <div>
                        <div class="mb-1.5 flex items-center justify-between text-[10px]">
                            <span class="inline-flex items-center gap-2 text-base-content/58">
                                <span class="size-1.5 rounded-full {{ $color }}"></span>
                                {{ $label }}
                            </span>
                            <strong class="tabular-nums">{{ $pipeline[$status] }}</strong>
                        </div>
                        <div class="h-1.5 overflow-hidden rounded-full bg-[var(--admin-surface-sunken)] shadow-inner">
                            <div class="h-full rounded-full {{ $color }}" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <a href="{{ route('admin.orders.index') }}"
                class="btn btn-sm mt-5 h-9 min-h-9 w-full rounded-lg border-[var(--admin-border-strong)] bg-[var(--admin-surface-raised)] text-[10px] font-semibold shadow-admin-control hover:border-accent/50 hover:bg-accent/10 hover:text-accent">
                Manage orders
            </a>
        </section>
    </div>

    <div class="grid gap-4 xl:grid-cols-[minmax(0,1.35fr)_minmax(19rem,.75fr)]">
        <section class="overflow-hidden rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] shadow-admin-panel">
            <header class="flex items-center justify-between gap-4 border-b border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-5 py-4">
                <div>
                    <h2 class="text-sm font-semibold">Recent orders</h2>
                    <p class="mt-0.5 text-[9px] text-base-content/38">Latest activity in this period.</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-[9px] font-semibold text-accent hover:underline">
                    View all
                </a>
            </header>

            @if ($recentOrders->isEmpty())
                <x-admin.empty-state title="No orders in this period"
                    description="Choose a wider date range when more history is available." />
            @else
                <div class="hidden overflow-x-auto md:block">
                    <table class="table table-sm">
                        <thead>
                            <tr class="border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] text-[8px] uppercase tracking-[0.12em] text-base-content/38">
                                <th class="px-5">Order</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th class="pr-5 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentOrders as $order)
                                <tr class="border-[var(--admin-border)] transition-colors hover:bg-[var(--admin-surface-soft)]" wire:key="dashboard-order-{{ $order->id }}">
                                    <td class="px-5 py-3">
                                        <a href="{{ route('admin.orders.index', ['search' => $order->order_number]) }}"
                                            class="text-[10px] font-semibold hover:text-accent">{{ $order->order_number }}</a>
                                        <span class="mt-0.5 block text-[8px] text-base-content/35">{{ $order->created_at->format('M j, g:i A') }}</span>
                                    </td>
                                    <td>
                                        <span class="block max-w-40 truncate text-[10px] font-medium">{{ $order->shipping_full_name }}</span>
                                        <span class="mt-0.5 block max-w-40 truncate text-[8px] text-base-content/35">{{ $order->email }}</span>
                                    </td>
                                    <td><x-admin.badge :tone="$statusTones[$order->status] ?? 'gray'">{{ str($order->status)->title() }}</x-admin.badge></td>
                                    <td><x-admin.badge :tone="$order->latestPayment?->status === 'paid' ? 'green' : 'amber'">{{ str($order->latestPayment?->status ?? 'unrecorded')->title() }}</x-admin.badge></td>
                                    <td class="pr-5 text-right text-[10px] font-semibold tabular-nums">${{ number_format($order->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="divide-y divide-[var(--admin-border)] md:hidden">
                    @foreach ($recentOrders as $order)
                        <article class="p-4" wire:key="dashboard-mobile-order-{{ $order->id }}">
                            <div class="flex items-start justify-between gap-3">
                                <span class="min-w-0">
                                    <a href="{{ route('admin.orders.index', ['search' => $order->order_number]) }}"
                                        class="block truncate text-[11px] font-semibold">{{ $order->order_number }}</a>
                                    <small class="mt-1 block truncate text-[9px] text-base-content/38">{{ $order->shipping_full_name }}</small>
                                </span>
                                <strong class="shrink-0 text-[11px] tabular-nums">${{ number_format($order->total, 2) }}</strong>
                            </div>
                            <div class="mt-3 flex items-center justify-between">
                                <x-admin.badge :tone="$statusTones[$order->status] ?? 'gray'">{{ str($order->status)->title() }}</x-admin.badge>
                                <span class="text-[8px] text-base-content/35">{{ $order->created_at->format('M j, g:i A') }}</span>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] p-5 shadow-admin-panel">
            <div>
                <h2 class="text-sm font-semibold">Catalog health</h2>
                <p class="mt-0.5 text-[9px] text-base-content/38">Current storefront readiness.</p>
            </div>

            <dl class="mt-5 grid grid-cols-2 gap-2">
                @foreach ([
                    ['Active products', $catalogHealth['active_products']],
                    ['Draft products', $catalogHealth['draft_products']],
                    ['Incomplete', $catalogHealth['incomplete_products']],
                    ['Active variants', $catalogHealth['active_variants']],
                ] as [$label, $value])
                    <div class="rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] p-3 shadow-inner">
                        <dt class="text-[8px] text-base-content/38">{{ $label }}</dt>
                        <dd class="mt-1 text-base font-semibold tabular-nums">{{ number_format($value) }}</dd>
                    </div>
                @endforeach
            </dl>

            <div class="mt-5 border-t border-[var(--admin-border)] pt-4">
                <p class="text-[8px] font-semibold uppercase tracking-[0.14em] text-base-content/35">Quick actions</p>
                <div class="mt-3 grid grid-cols-2 gap-2">
                    @foreach ([
                        [route('admin.products.create'), 'Add product', 'M12 5v14m-7-7h14'],
                        [route('admin.coupons.create'), 'New coupon', 'M4 9V5h4l12 12-4 4L4 9Zm3-1h.01'],
                        [route('admin.shipping.index'), 'Shipping', 'M3 7h11v10H3V7Zm11 4h3l3 3v3h-6v-6Z'],
                        [route('admin.reports.index'), 'Reports', 'M5 20V10m5 10V4m5 16v-7m5 7V7'],
                    ] as [$href, $label, $icon])
                        <a href="{{ $href }}"
                            class="flex min-h-16 flex-col justify-between rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] p-3 text-[9px] font-medium shadow-inner transition-all hover:-translate-y-0.5 hover:border-accent/45 hover:bg-accent/10 motion-reduce:transform-none motion-reduce:transition-none">
                            <svg class="size-3.5 text-accent" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}" />
                            </svg>
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    </div>

    <div class="grid gap-4 lg:grid-cols-2">
        <section class="overflow-hidden rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] shadow-admin-panel">
            <header class="flex items-center justify-between border-b border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-5 py-4">
                <div>
                    <h2 class="text-sm font-semibold">Top sellers</h2>
                    <p class="mt-0.5 text-[9px] text-base-content/38">Best-performing variants in this period.</p>
                </div>
                <span class="text-[9px] text-base-content/35">{{ $range }} days</span>
            </header>

            @if ($topSellers->isEmpty())
                <x-admin.empty-state title="No product sales yet"
                    description="Sold variants will appear here automatically." />
            @else
                <div class="divide-y divide-[var(--admin-border)]">
                    @foreach ($topSellers as $item)
                        @php $image = $item->variant?->primary_image; @endphp
                        <article class="flex items-center gap-3 px-5 py-3.5 transition-colors hover:bg-[var(--admin-surface-soft)]" wire:key="dashboard-seller-{{ $item->variant_id }}">
                            <span class="grid size-10 shrink-0 place-items-center overflow-hidden rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-inner">
                                @if ($image)
                                    <img src="{{ asset('storage/'.$image->path) }}" alt="" class="size-full object-contain p-1">
                                @else
                                    <svg class="size-4 text-base-content/25" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 2h6v4H9V2Zm0 16h6v4H9v-4Zm-2-12h10l1 3v6l-1 3H7l-1-3V9l1-3Z" />
                                    </svg>
                                @endif
                            </span>
                            <span class="min-w-0 flex-1">
                                <strong class="block truncate text-[10px]">{{ $item->product_name }}</strong>
                                <small class="mt-0.5 block truncate text-[8px] text-base-content/35">{{ $item->variant_name }} · {{ $item->variant_sku }}</small>
                            </span>
                            <span class="shrink-0 text-right">
                                <strong class="block text-[10px] tabular-nums">{{ number_format($item->units_sold) }} sold</strong>
                                <small class="mt-0.5 block text-[8px] text-base-content/35">${{ number_format($item->revenue, 2) }}</small>
                            </span>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="overflow-hidden rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] shadow-admin-panel">
            <header class="flex items-center justify-between border-b border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-5 py-4">
                <div>
                    <h2 class="text-sm font-semibold">Stock attention</h2>
                    <p class="mt-0.5 text-[9px] text-base-content/38">Variants at or below {{ $settings->low_stock_threshold }} units.</p>
                </div>
                <a href="{{ route('admin.products.index', ['sort' => 'stock_asc']) }}"
                    class="text-[9px] font-semibold text-accent hover:underline">View products</a>
            </header>

            @if ($lowStockVariants->isEmpty())
                <x-admin.empty-state title="Stock levels look healthy"
                    description="No active variants currently need attention." />
            @else
                <div class="divide-y divide-[var(--admin-border)]">
                    @foreach ($lowStockVariants as $variant)
                        @php $image = $variant->primary_image; @endphp
                        <article class="flex items-center gap-3 px-5 py-3.5 transition-colors hover:bg-[var(--admin-surface-soft)]" wire:key="dashboard-stock-{{ $variant->id }}">
                            <span class="grid size-10 shrink-0 place-items-center overflow-hidden rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-inner">
                                @if ($image)
                                    <img src="{{ asset('storage/'.$image->path) }}" alt="" class="size-full object-contain p-1">
                                @else
                                    <svg class="size-4 text-base-content/25" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 2h6v4H9V2Zm0 16h6v4H9v-4Zm-2-12h10l1 3v6l-1 3H7l-1-3V9l1-3Z" />
                                    </svg>
                                @endif
                            </span>
                            <span class="min-w-0 flex-1">
                                <strong class="block truncate text-[10px]">{{ $variant->product->name }}</strong>
                                <small class="mt-0.5 block truncate text-[8px] text-base-content/35">{{ $variant->name }} · {{ $variant->sku }}</small>
                            </span>
                            <x-admin.badge :tone="$variant->stock_quantity === 0 ? 'red' : 'amber'">
                                {{ $variant->stock_quantity === 0 ? 'Out of stock' : $variant->stock_quantity.' left' }}
                            </x-admin.badge>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </div>

    <div wire:loading.flex wire:target="setRange"
        class="pointer-events-none fixed inset-x-0 top-0 z-[80] h-0.5 bg-accent/20">
        <span class="h-full w-1/3 animate-pulse bg-accent"></span>
    </div>
</div>
