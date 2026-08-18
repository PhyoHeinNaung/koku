@php
    $pageIds = $orders->getCollection()->pluck('id')->map(fn ($id) => (int) $id)->values()->all();
    $allPageSelected = $pageIds !== [] && count(array_intersect($pageIds, array_map('intval', $selected))) === count($pageIds);
    $paidRate = $summary['all'] ? round(($summary['paid'] / $summary['all']) * 100) : 0;
    $statusMeta = [
        'pending' => ['Pending', 'admin-status-warning'],
        'processing' => ['Processing', 'admin-status-muted'],
        'shipped' => ['Shipped', 'admin-status-muted'],
        'delivered' => ['Delivered', 'admin-status-success'],
        'cancelled' => ['Cancelled', 'admin-status-error'],
    ];
@endphp

<div class="admin-page flex" x-data="{ orderDrawerOpen: @entangle('drawerOpen') }" @keydown.escape.window="if(orderDrawerOpen){orderDrawerOpen=false;setTimeout(()=>$wire.closeOrder(),220)}">
    <section class="admin-page-main min-w-0 flex-1">
        <header class="admin-page-head">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
                <div><h1 class="admin-page-title">Orders</h1></div>
                <div class="admin-page-actions">
                    <button type="button" class="admin-action admin-action-dark"><span>↓</span> Import</button>
                    <button type="button" class="admin-action"><span>↑</span> Export</button>
                </div>
            </div>
            <div class="admin-filter-row">
                <select wire:model.live="status" class="admin-filter {{ $status !== 'all' ? 'is-active' : '' }}">
                    <option value="all">All types</option>
                    <option value="pending">Pending</option><option value="processing">Processing</option><option value="shipped">Shipped</option><option value="delivered">Delivered</option><option value="cancelled">Cancelled</option>
                </select>
                <select wire:model.live="payment" class="admin-filter {{ $payment !== 'all' ? 'is-active' : '' }}"><option value="all">Status</option><option value="paid">Paid</option><option value="pending">Payment pending</option><option value="failed">Failed</option><option value="refunded">Refunded</option></select>
                <select wire:model.live="sort" class="admin-filter {{ $sort !== 'newest' ? 'is-active' : '' }}"><option value="newest">Order date</option><option value="oldest">Oldest first</option><option value="total_desc">Highest total</option><option value="total_asc">Lowest total</option></select>
                <label class="admin-filter min-w-52 flex-1 sm:flex-none"><svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><input type="search" wire:model.live.debounce.350ms="search" class="min-w-0 grow border-0 bg-transparent p-0 text-[11px] outline-none ring-0 focus:border-0 focus:ring-0" placeholder="Search all orders"></label>
                @if($hasFilters)<button type="button" wire:click="clearAll" class="text-[11px] text-black/45 hover:text-black">Clear all</button>@endif
            </div>
        </header>

        <nav class="admin-tab-row" aria-label="Order lifecycle">
            @foreach(['all' => 'All orders', 'pending' => 'Pending', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'] as $value => $label)
                <button type="button" wire:click="$set('status', '{{ $value }}')" class="admin-tab {{ $status === $value ? 'is-active' : '' }}">
                    {{ $label }} <span>{{ $value === 'all' ? $summary['all'] : $summary[$value] }}</span>
                </button>
            @endforeach
        </nav>

        <div class="admin-table-wrap">
            <table class="admin-table min-w-[880px]">
                <thead><tr>
                    <th class="w-10 pl-6"><input type="checkbox" wire:click="togglePageSelection(@js($pageIds))" @checked($allPageSelected) aria-label="Select visible orders"></th>
                    <th class="w-[16%]">Order</th><th class="w-[22%]">Customer</th><th class="w-[13%]">Type</th><th class="w-[14%]">Status</th><th class="w-[13%]">Total</th><th class="w-[15%]">Date</th><th class="w-12"></th>
                </tr></thead>
                <tbody>
                    @forelse($orders as $order)
                        @php($meta = $statusMeta[$order->status] ?? [str($order->status)->title(), 'admin-status-muted'])
                        <tr wire:key="order-{{ $order->id }}">
                            <td class="pl-6"><input type="checkbox" value="{{ $order->id }}" wire:model.live="selected" aria-label="Select {{ $order->order_number }}"></td>
                            <td><button type="button" wire:click="openOrder({{ $order->id }})" class="font-medium hover:underline">{{ $order->order_number }}</button><span class="ml-1 text-[9px] text-black/25">▣</span></td>
                            <td><div class="flex items-center gap-2.5"><span class="grid size-7 shrink-0 place-items-center rounded-full bg-[#f0f0ee] text-[9px] font-medium">{{ str($order->shipping_full_name)->explode(' ')->map(fn($p)=>str($p)->substr(0,1))->take(2)->join('') }}</span><span class="truncate font-medium">{{ $order->shipping_full_name }}</span></div></td>
                            <td class="text-black/65">{{ $order->shippingLocation ? 'Shipping' : 'Pickup' }}</td>
                            <td><span class="admin-status {{ $meta[1] }}">{{ $meta[0] }}</span></td>
                            <td class="font-medium">${{ number_format($order->total, 2) }}</td>
                            <td class="text-black/65">{{ $order->created_at->format('M j, Y') }}</td>
                            <td><button type="button" wire:click="openOrder({{ $order->id }})" class="px-2 text-lg leading-none" aria-label="Open order">•••</button></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="!h-56 text-center text-black/40">No orders match this view.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">{{ $orders->links() }}</div>
    </section>

    <aside class="admin-context-rail">
        <section class="admin-context-section">
            <p class="admin-context-kicker">Receipt of goods</p>
            <div class="relative mx-auto mt-7 grid size-36 place-items-center rounded-full" style="background: conic-gradient(#356f65 0 {{ $paidRate }}%,#dfe3e4 {{ $paidRate }}% 100%);">
                <div class="grid size-[7.4rem] place-items-center rounded-full bg-white text-center"><div><strong class="block text-xl font-semibold tracking-[-.05em]">${{ number_format($summary['gross']/1000,1) }}k</strong><span class="text-[11px] text-black/45">{{ number_format($summary['all']) }} orders</span></div></div>
            </div>
            <div class="mt-7 grid grid-cols-2 gap-5"><div><span class="admin-status admin-status-success">${{ number_format($summary['gross'],0) }}</span><span class="mt-1 block pl-3 text-[10px] text-black/40">gross sales</span></div><div><span class="admin-status admin-status-muted">{{ $summary['paid'] }}</span><span class="mt-1 block pl-3 text-[10px] text-black/40">paid orders</span></div></div>
        </section>
        <section class="admin-context-section">
            <div class="flex items-center justify-between"><p class="admin-context-kicker">Order status</p><span class="text-[10px] text-black/45">Active⌄</span></div>
            <div class="mt-6 flex h-1.5 overflow-hidden bg-[#ececea]"><span class="bg-[#397a68]" style="width:{{ $summary['all'] ? ($summary['delivered']/$summary['all'])*100 : 0 }}%"></span><span class="bg-[#c74b4b]" style="width:{{ $summary['all'] ? ($summary['cancelled']/$summary['all'])*100 : 0 }}%"></span></div>
            <dl class="mt-4 space-y-3 text-[11px]">@foreach(['delivered'=>'Delivered','processing'=>'Processing','cancelled'=>'Cancelled'] as $key=>$label)<div class="flex justify-between"><dt class="admin-status {{ $key==='delivered'?'admin-status-success':($key==='cancelled'?'admin-status-error':'admin-status-muted') }}">{{ $label }}</dt><dd class="font-medium">{{ $summary['all'] ? round(($summary[$key]/$summary['all'])*100) : 0 }}%</dd></div>@endforeach</dl>
        </section>
        <section class="admin-context-section">
            <div class="flex items-center justify-between"><p class="admin-context-kicker">Overview</p><span class="text-[10px] text-black/45">All time⌄</span></div>
            <div class="mt-6 grid grid-cols-2 gap-x-5 gap-y-6"><div><strong class="admin-context-value">${{ number_format($summary['average'],2) }}</strong><span class="admin-context-label mt-1 block">Average order</span></div><div><strong class="admin-context-value">${{ number_format($summary['gross']/1000,1) }}k</strong><span class="admin-context-label mt-1 block">Total revenue</span></div><div><strong class="admin-context-value">{{ $summary['pending'] }}</strong><span class="admin-context-label mt-1 block">Pending orders</span></div><div><strong class="admin-context-value">{{ $paidRate }}%</strong><span class="admin-context-label mt-1 block">Paid rate</span></div></div>
        </section>
    </aside>

    @if(count($selected)>0)
        <div class="admin-bulk-bar"><button wire:click="clearSelection" class="border-r border-white/10">✕</button><span class="px-3 text-[11px] text-white/45">Selected: {{ count($selected) }}</span><button wire:click="bulkUpdateStatus('processing')">↑ Process</button><button wire:click="bulkUpdateStatus('shipped')">▣ Ship</button><button wire:click="bulkUpdateStatus('delivered')">✓ Delivered</button><button wire:click="bulkUpdateStatus('cancelled')" wire:confirm="Cancel selected orders?" class="text-[#e9aaa5]">Cancel</button></div>
    @endif

    <div class="fixed inset-0 z-[60]" :class="orderDrawerOpen?'pointer-events-auto':'pointer-events-none'">
        <button x-cloak x-show="orderDrawerOpen" x-transition.opacity type="button" class="admin-drawer-backdrop" @click="orderDrawerOpen=false;setTimeout(()=>$wire.closeOrder(),220)" aria-label="Close order details"></button>
        <aside x-cloak x-show="orderDrawerOpen" x-transition:enter="transition-transform duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition-transform duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="admin-detail-drawer" role="dialog" aria-modal="true">
            @if($selectedOrder)
                @php($selectedPayment=$selectedOrder->payments->first())
                <div class="absolute right-7 top-7 flex gap-3"><button type="button" class="text-lg">↗</button><button type="button" @click="orderDrawerOpen=false;setTimeout(()=>$wire.closeOrder(),220)" class="text-3xl font-light" aria-label="Close">×</button></div>
                <header class="admin-detail-head"><h2 class="admin-detail-title">{{ $selectedOrder->order_number }}</h2><div class="mt-8 flex flex-wrap gap-2"><button class="admin-action admin-action-dark">↑ Export</button><button class="admin-action">▣ Print</button><button class="admin-action">▢ Duplicate</button><button class="admin-action">•••</button></div></header>
                <div class="admin-detail-body">
                    <section class="admin-detail-section"><h3>Order items <span class="ml-1 font-normal text-black/35">{{ $selectedOrder->items->count() }}</span></h3>@foreach($selectedOrder->items as $item)<div class="admin-detail-row"><div class="flex min-w-0 items-center gap-4">@if($item->variant?->primary_image)<img src="{{ Storage::url($item->variant->primary_image->image_url) }}" alt="{{ $item->product_name }}" class="admin-detail-image">@else<span class="admin-detail-image grid place-items-center text-black/20">□</span>@endif<div class="min-w-0"><strong class="block text-[13px] font-medium">{{ $item->product_name }}</strong><span class="mt-1 block text-[10px] text-black/40">{{ $item->variant_name }}</span></div></div><span class="text-[12px]">{{ $item->quantity }} <span class="text-black/30">×</span></span><strong class="text-[13px]">${{ number_format($item->subtotal,2) }}</strong></div>@endforeach<div class="mt-2 flex justify-between border-t border-[var(--admin-border)] pt-5 text-[13px]"><span>Total</span><strong>${{ number_format($selectedOrder->total,2) }}</strong></div></section>
                    <section class="admin-detail-section"><h3>Contacts</h3><dl class="grid grid-cols-[9rem_1fr] gap-y-4 text-[13px]"><dt class="text-black/40">Customer</dt><dd>{{ $selectedOrder->shipping_full_name }}</dd><dt class="text-black/40">Email</dt><dd class="break-all">{{ $selectedOrder->email ?: $selectedOrder->user?->email }}</dd><dt class="text-black/40">Phone</dt><dd>{{ $selectedOrder->shipping_phone }}</dd></dl></section>
                    <section class="admin-detail-section"><h3>Delivery</h3><address class="text-[13px] not-italic leading-6 text-black/65">{{ $selectedOrder->shipping_address_line1 }}@if($selectedOrder->shipping_address_line2), {{ $selectedOrder->shipping_address_line2 }}@endif<br>{{ collect([$selectedOrder->shipping_district_area,$selectedOrder->shipping_city,$selectedOrder->shipping_state_region])->filter()->join(', ') }} {{ $selectedOrder->shipping_postal_code }}<br>{{ $selectedOrder->shipping_country }}</address></section>
                </div>
                <footer class="flex items-center gap-3 border-t border-[var(--admin-border)] px-8 py-4"><select wire:model="selectedOrderStatus" class="select select-bordered h-9 min-h-9 flex-1">@foreach(['pending','processing','shipped','delivered','cancelled'] as $value)<option value="{{ $value }}">{{ str($value)->title() }}</option>@endforeach</select><button type="button" wire:click="updateOrderStatus" class="admin-action admin-action-dark">Update status</button></footer>
            @endif
        </aside>
    </div>
</div>
