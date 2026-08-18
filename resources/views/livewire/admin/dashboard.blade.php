@php
    $pipeTotal = max(1, array_sum($pipeline));
    $chartWidth = 720;
    $chartHeight = 210;
    $chartPad = 14;
    $chartMax = max(1, $trendMaximum);
    $pointCount = max(1, $trend->count() - 1);
    $chartPoints = $trend->values()->map(function ($point, $index) use ($chartWidth, $chartHeight, $chartPad, $chartMax, $pointCount) {
        $x = $chartPad + ($index / $pointCount) * ($chartWidth - ($chartPad * 2));
        $y = $chartHeight - $chartPad - (($point['sales'] / $chartMax) * ($chartHeight - ($chartPad * 2)));
        return ['x' => round($x, 2), 'y' => round($y, 2), 'point' => $point];
    });
    $polyline = $chartPoints->map(fn ($item) => $item['x'].','.$item['y'])->implode(' ');
    $areaPath = 'M '.$chartPad.' '.($chartHeight - $chartPad).' L '.$polyline.' L '.($chartWidth - $chartPad).' '.($chartHeight - $chartPad).' Z';
    $pipelineStops = collect($pipeline)->reduce(function ($carry, $value, $status) use ($pipeTotal) {
        $start = $carry['cursor'];
        $end = $start + ($value / $pipeTotal * 100);
        $opacity = ['pending' => 1, 'processing' => .78, 'shipped' => .56, 'delivered' => .34, 'cancelled' => .14][$status];
        $carry['stops'][] = "rgb(52 116 104 / {$opacity}) {$start}% {$end}%";
        $carry['cursor'] = $end;
        return $carry;
    }, ['cursor' => 0, 'stops' => []]);
@endphp

<div class="admin-dashboard">
  <div class="admin-dashboard-canvas">
    <header class="admin-dashboard-head">
        <div>
            <p class="admin-moderation-eyebrow">{{ now()->format('l, F j') }}</p>
            <h1>Dashboard</h1>
            <p>A focused view of revenue, fulfilment, and inventory at {{ $settings->store_name }}.</p>
        </div>
        <nav class="admin-segmented" aria-label="Dashboard date range">
            @foreach(['7' => '7 days', '30' => '30 days', '90' => '90 days'] as $value => $label)<button wire:click="setRange('{{ $value }}')" class="{{ $range === $value ? 'is-active' : '' }}">{{ $label }}</button>@endforeach
        </nav>
    </header>

    <section class="admin-kpi-grid" aria-label="Store summary">
        @foreach([
            ['Gross sales', '$'.number_format($summary['gross_sales'], 2), $summary['gross_sales_change'], 'Revenue from non-cancelled orders'],
            ['Orders', number_format($summary['orders']), $summary['orders_change'], $summary['pending'].' need action'],
            ['Buyers', number_format($summary['buyers']), $summary['buyers_change'], 'Distinct customers in this period'],
            ['Average order', '$'.number_format($summary['average_order'], 2), null, '$'.number_format($summary['collected'], 2).' collected'],
        ] as [$label, $value, $change, $note])
            <article class="admin-kpi-card">
                <span>{{ $label }}</span><strong>{{ $value }}</strong>
                <footer>@if($change !== null)<em class="{{ $change >= 0 ? 'is-up' : 'is-down' }}">{{ $change >= 0 ? '↗' : '↘' }} {{ abs($change) }}%</em>@endif<p>{{ $note }}</p></footer>
            </article>
        @endforeach
    </section>

    <section class="admin-dashboard-grid">
        <article class="admin-dashboard-panel admin-sales-panel">
            <header><div><p class="admin-panel-kicker">Performance</p><h2>Sales movement</h2></div><a href="{{ route('admin.reports.index') }}">Open reports →</a></header>
            <div class="admin-chart-summary"><strong>${{ number_format($trend->sum('sales'), 2) }}</strong><span>gross sales across the last 14 days</span></div>
            <div class="admin-line-chart">
                <svg viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}" role="img" aria-label="Fourteen-day sales trend">
                    <defs><linearGradient id="salesArea" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#347468" stop-opacity=".24"/><stop offset="1" stop-color="#347468" stop-opacity="0"/></linearGradient></defs>
                    @foreach([.25,.5,.75,1] as $line)<line x1="{{ $chartPad }}" y1="{{ $chartHeight*$line-$chartPad/2 }}" x2="{{ $chartWidth-$chartPad }}" y2="{{ $chartHeight*$line-$chartPad/2 }}" stroke="#e5e8e5" stroke-width="1"/>@endforeach
                    <path d="{{ $areaPath }}" fill="url(#salesArea)"/>
                    <polyline points="{{ $polyline }}" fill="none" stroke="#347468" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    @foreach($chartPoints as $item)<circle cx="{{ $item['x'] }}" cy="{{ $item['y'] }}" r="4" fill="#fff" stroke="#347468" stroke-width="2"><title>{{ $item['point']['date']->format('M j') }}: ${{ number_format($item['point']['sales'], 2) }}</title></circle>@endforeach
                </svg>
                <div class="admin-chart-axis"><span>{{ $trend->first()['date']->format('M j') }}</span><span>{{ $trend->get(6)['date']->format('M j') }}</span><span>{{ $trend->last()['date']->format('M j') }}</span></div>
            </div>
        </article>

        <article class="admin-dashboard-panel admin-pipeline-panel">
            <header><div><p class="admin-panel-kicker">Fulfilment</p><h2>Order pipeline</h2></div><strong>{{ array_sum($pipeline) }}</strong></header>
            <div class="admin-donut" style="--pipeline: conic-gradient({{ implode(', ', $pipelineStops['stops']) }})"><div><strong>{{ $pipeline['pending'] + $pipeline['processing'] }}</strong><span>need action</span></div></div>
            <dl class="admin-pipeline-list">@foreach($pipeline as $status => $count)<div><dt><i style="opacity:{{ ['pending'=>1,'processing'=>.78,'shipped'=>.56,'delivered'=>.34,'cancelled'=>.14][$status] }}"></i>{{ str($status)->title() }}</dt><dd>{{ $count }} <span>{{ round($count/$pipeTotal*100) }}%</span></dd></div>@endforeach</dl>
        </article>

        <article class="admin-dashboard-panel admin-orders-panel">
            <header><div><p class="admin-panel-kicker">Latest activity</p><h2>Recent orders</h2></div><a href="{{ route('admin.orders.index') }}">View all →</a></header>
            <div class="admin-order-list">@forelse($recentOrders as $order)<a href="{{ route('admin.orders.index', ['search' => $order->order_number]) }}"><div><strong>{{ $order->order_number }}</strong><span>{{ $order->shipping_full_name }} · {{ $order->items_count }} {{ Str::plural('item', $order->items_count) }}</span></div><div><strong>${{ number_format($order->total, 2) }}</strong><span class="admin-status {{ $order->status === 'delivered' ? 'admin-status-success' : 'admin-status-warning' }}">{{ str($order->status)->title() }}</span></div></a>@empty<div class="admin-inline-empty">No orders in this period.</div>@endforelse</div>
        </article>

        <article class="admin-dashboard-panel admin-sellers-panel">
            <header><div><p class="admin-panel-kicker">Product demand</p><h2>Top sellers</h2></div><span>By units</span></header>
            @php($sellerMax = max(1, (int) $topSellers->max('units_sold')))
            <div class="admin-seller-list">@forelse($topSellers as $index => $seller)<div><span class="admin-seller-rank">0{{ $index + 1 }}</span><div class="admin-seller-name"><strong>{{ $seller->product_name }}</strong><span>{{ $seller->variant_name }}</span><i><b style="width:{{ $seller->units_sold/$sellerMax*100 }}%"></b></i></div><div class="admin-seller-value"><strong>{{ $seller->units_sold }}</strong><span>${{ number_format($seller->revenue, 0) }}</span></div></div>@empty<div class="admin-inline-empty">Sales will rank products here.</div>@endforelse</div>
        </article>

        <article class="admin-dashboard-panel admin-attention-panel">
            <header><div><p class="admin-panel-kicker">Inventory</p><h2>Needs attention</h2></div><a href="{{ route('admin.products.index', ['sort' => 'stock_asc']) }}">Inventory →</a></header>
            <div class="admin-attention-list">@forelse($lowStockVariants as $variant)<div><div><strong>{{ $variant->product->name }}</strong><span>{{ $variant->name }} · {{ $variant->sku }}</span></div><strong class="{{ $variant->stock_quantity === 0 ? 'is-empty' : '' }}">{{ $variant->stock_quantity }} left</strong></div>@empty<div class="admin-inline-empty">Stock levels are healthy.</div>@endforelse</div>
            <footer class="admin-catalog-health"><div><strong>{{ $catalogHealth['active_products'] }}</strong><span>active products</span></div><div><strong>{{ $catalogHealth['draft_products'] }}</strong><span>drafts</span></div><div><strong>{{ $catalogHealth['incomplete_products'] }}</strong><span>incomplete</span></div></footer>
        </article>
    </section>
  </div>
</div>
