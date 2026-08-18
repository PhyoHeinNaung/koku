@php
    $tabs = ['sales' => 'Sales', 'products' => 'Products', 'customers' => 'Customers'];
    $summaryCards = match($tab) {
        'products' => [
            ['Units sold', number_format($summary['units_sold']), 'Across completed and active orders'],
            ['Merchandise sales', '$'.number_format($summary['merchandise_sales'], 2), 'Product revenue in this range'],
            ['Variants sold', number_format($summary['variants_sold']), 'Distinct variants purchased'],
            ['Stock alerts', number_format($summary['low_stock'] + $summary['out_of_stock']), $summary['out_of_stock'].' currently out of stock'],
        ],
        'customers' => [
            ['Buyers', number_format($summary['buyers']), 'Distinct purchasing customers'],
            ['Repeat buyers', number_format($summary['repeat_buyers']), 'Customers with multiple orders'],
            ['New accounts', number_format($summary['new_accounts']), 'Accounts created in this range'],
            ['Average customer value', '$'.number_format($summary['average_customer_value'], 2), $summary['guest_buyers'].' guest buyers'],
        ],
        default => [
            ['Gross sales', '$'.number_format($summary['gross_sales'], 2), 'Excludes cancelled orders'],
            ['Collected', '$'.number_format($summary['collected'], 2), 'Recorded paid transactions'],
            ['Orders', number_format($summary['orders']), $summary['pending'].' need action'],
            ['Average order', '$'.number_format($summary['average_order_value'], 2), $summary['delivered'].' delivered'],
        ],
    };

    if ($tab === 'sales') {
        $chartWidth = 720; $chartHeight = 180; $chartPad = 12;
        $chartMax = max(1, (float) $trend->max('sales'));
        $pointCount = max(1, $trend->count() - 1);
        $chartPoints = $trend->values()->map(function ($point, $index) use ($chartWidth, $chartHeight, $chartPad, $chartMax, $pointCount) {
            return [
                'x' => round($chartPad + ($index / $pointCount) * ($chartWidth - $chartPad * 2), 2),
                'y' => round($chartHeight - $chartPad - (($point['sales'] / $chartMax) * ($chartHeight - $chartPad * 2)), 2),
                'point' => $point,
            ];
        });
        $polyline = $chartPoints->map(fn ($item) => $item['x'].','.$item['y'])->implode(' ');
        $areaPath = 'M '.$chartPad.' '.($chartHeight-$chartPad).' L '.$polyline.' L '.($chartWidth-$chartPad).' '.($chartHeight-$chartPad).' Z';
    }
@endphp

<div class="admin-dashboard admin-reports">
  <div class="admin-dashboard-canvas">
    <header class="admin-dashboard-head">
        <div>
            <p class="admin-moderation-eyebrow">Analysis workspace</p>
            <h1>Reports &amp; insights</h1>
            <p>{{ $rangeLabel }} · Review sales, product demand, and customer value.</p>
        </div>
        <button wire:click="exportCsv" class="admin-export-button">↓ Export CSV</button>
    </header>

    <section class="admin-report-controls">
        <nav class="admin-segmented" aria-label="Report dataset">
            @foreach($tabs as $value => $label)<button wire:click="$set('tab','{{ $value }}')" class="{{ $tab === $value ? 'is-active' : '' }}">{{ $label }}</button>@endforeach
        </nav>
        <nav class="admin-segmented" aria-label="Report date range">
            @foreach(['7' => '7 days', '30' => '30 days', '90' => '90 days', 'all' => 'All time'] as $value => $label)<button wire:click="setRange('{{ $value }}')" class="{{ $range === $value ? 'is-active' : '' }}">{{ $label }}</button>@endforeach
            <button wire:click="$set('range','custom')" class="{{ $range === 'custom' ? 'is-active' : '' }}">Custom</button>
        </nav>
    </section>

    @if($range === 'custom')
        <form wire:submit="applyCustomRange" class="admin-custom-range">
            <label><span>From</span><input wire:model="from" type="date"></label>
            <label><span>To</span><input wire:model="to" type="date"></label>
            <button>Apply range</button>
            @error('from')<p>{{ $message }}</p>@enderror @error('to')<p>{{ $message }}</p>@enderror
        </form>
    @endif

    <section class="admin-kpi-grid" aria-label="Report summary">
        @foreach($summaryCards as [$label, $value, $note])<article class="admin-kpi-card"><span>{{ $label }}</span><strong>{{ $value }}</strong><footer><p>{{ $note }}</p></footer></article>@endforeach
    </section>

    @if($tab === 'sales')
        <section class="admin-dashboard-panel admin-report-chart">
            <header><div><p class="admin-panel-kicker">Revenue signal</p><h2>Daily sales</h2></div><span>Last {{ $trend->count() }} days within range</span></header>
            <div class="admin-line-chart">
                <svg viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}" role="img" aria-label="Sales trend for selected report range">
                    <defs><linearGradient id="reportSalesArea" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#347468" stop-opacity=".2"/><stop offset="1" stop-color="#347468" stop-opacity="0"/></linearGradient></defs>
                    @foreach([.25,.5,.75,1] as $line)<line x1="{{ $chartPad }}" y1="{{ $chartHeight*$line-$chartPad/2 }}" x2="{{ $chartWidth-$chartPad }}" y2="{{ $chartHeight*$line-$chartPad/2 }}" stroke="#e5e8e5" stroke-width="1"/>@endforeach
                    <path d="{{ $areaPath }}" fill="url(#reportSalesArea)"/><polyline points="{{ $polyline }}" fill="none" stroke="#347468" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    @foreach($chartPoints as $item)<circle cx="{{ $item['x'] }}" cy="{{ $item['y'] }}" r="3.5" fill="#fff" stroke="#347468" stroke-width="2"><title>{{ $item['point']['date']->format('M j') }}: ${{ number_format($item['point']['sales'], 2) }}</title></circle>@endforeach
                </svg>
                <div class="admin-chart-axis"><span>{{ $trend->first()['date']->format('M j') }}</span><span>{{ $trend->last()['date']->format('M j') }}</span></div>
            </div>
        </section>
    @endif

    <section class="admin-dashboard-panel admin-report-results">
        <header><div><p class="admin-panel-kicker">Detailed data</p><h2>{{ $tabs[$tab] }} report</h2></div><span>{{ number_format($rows->total()) }} records</span></header>
        <div class="admin-report-list">
            @forelse($rows as $row)
                @if($tab === 'sales')
                    <article><div class="admin-report-primary"><strong>{{ $row->order_number }}</strong><span>{{ $row->shipping_full_name }} · {{ $row->items_count }} {{ Str::plural('item',$row->items_count) }}</span></div><dl><div><dt>Date</dt><dd>{{ $row->created_at->format('M j, Y') }}</dd></div><div><dt>Payment</dt><dd>{{ str($row->latestPayment?->status ?? 'unrecorded')->title() }}</dd></div><div><dt>Status</dt><dd><span class="admin-status {{ $row->status === 'delivered' ? 'admin-status-success' : 'admin-status-warning' }}">{{ str($row->status)->title() }}</span></dd></div></dl><strong class="admin-report-amount">${{ number_format($row->total,2) }}</strong></article>
                @elseif($tab === 'products')
                    <article><div class="admin-report-primary"><strong>{{ $row->product_name }}</strong><span>{{ $row->variant_name }} · {{ $row->variant_sku }}</span></div><dl><div><dt>Units sold</dt><dd>{{ $row->units_sold }}</dd></div><div><dt>Orders</dt><dd>{{ $row->order_count }}</dd></div></dl><strong class="admin-report-amount">${{ number_format($row->revenue,2) }}</strong></article>
                @else
                    <article><div class="admin-report-primary"><strong>{{ $row->customer_name }}</strong><span>{{ $row->email }}</span></div><dl><div><dt>Type</dt><dd>{{ $row->user_id ? 'Registered' : 'Guest' }}</dd></div><div><dt>Orders</dt><dd>{{ $row->orders_count }}</dd></div><div><dt>Last order</dt><dd>{{ \Carbon\Carbon::parse($row->last_order_at)->format('M j, Y') }}</dd></div></dl><strong class="admin-report-amount">${{ number_format($row->lifetime_value,2) }}</strong></article>
                @endif
            @empty<div class="admin-inline-empty">No data exists for this report and date range.</div>@endforelse
        </div>
        <footer class="admin-report-pagination">{{ $rows->links() }}</footer>
    </section>
  </div>
</div>
