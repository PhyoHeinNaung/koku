@php
    $drawerFilterCount = ($payment !== 'all' ? 1 : 0) + ($sort !== 'newest' ? 1 : 0);
    $pageIds = $orders->getCollection()->pluck('id')->map(fn ($id) => (int) $id)->values()->all();
    $allPageSelected = $pageIds !== [] && count(array_intersect($pageIds, array_map('intval', $selected))) === count($pageIds);
    $orderSortLabels = [
        'newest' => 'Newest first',
        'oldest' => 'Oldest first',
        'total_desc' => 'Highest total',
        'total_asc' => 'Lowest total',
    ];
    $orderTones = [
        'pending' => 'amber',
        'processing' => 'blue',
        'shipped' => 'purple',
        'delivered' => 'green',
        'cancelled' => 'red',
    ];
    $paymentTones = [
        'pending' => 'amber',
        'paid' => 'green',
        'failed' => 'red',
        'refunded' => 'purple',
    ];
@endphp

<div class="mx-auto w-full max-w-[1600px] space-y-5"
    x-data="{ filterDrawerOpen: false, orderDrawerOpen: @entangle('drawerOpen') }"
    @keydown.escape.window="
        filterDrawerOpen = false;
        if (orderDrawerOpen) {
            orderDrawerOpen = false;
            setTimeout(() => $wire.closeOrder(), 250);
        }
    ">
    <x-admin.page-header title="Orders" :count="$summary['all']" />

    <x-admin.resource-panel loading-target="search,status,payment,sort,clearAll,bulkUpdateStatus">
        <x-slot:navigation>
            @foreach ([
                'all' => ['All orders', $summary['all']],
                'pending' => ['Pending', $summary['pending']],
                'processing' => ['Processing', $summary['processing']],
                'shipped' => ['Shipped', $summary['shipped']],
                'delivered' => ['Delivered', $summary['delivered']],
                'cancelled' => ['Cancelled', $summary['cancelled']],
            ] as $value => [$label, $count])
                <x-admin.resource-tab wire:click="$set('status', '{{ $value }}')"
                    :active="$status === $value" :count="$count">
                    {{ $label }}
                </x-admin.resource-tab>
            @endforeach
        </x-slot:navigation>

        <x-slot:toolbar>
            <label
                class="input flex h-10 w-full max-w-md items-center gap-2 rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] px-3 shadow-admin-control focus-within:border-accent/60 focus-within:outline-none">
                <svg class="size-3.5 shrink-0 text-base-content/35" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8">
                    <circle cx="11" cy="11" r="7" />
                    <path d="m20 20-4-4" />
                </svg>
                <input type="search" wire:model.live.debounce.350ms="search"
                    class="grow border-0 bg-transparent p-0 text-xs shadow-none outline-none focus:border-0 focus:ring-0"
                    placeholder="Search order, customer or phone">
                <span wire:loading wire:target="search"
                    class="loading loading-spinner loading-xs shrink-0 text-accent"></span>
            </label>

            <div class="flex flex-1 items-center gap-2 lg:justify-end">
                <button type="button" @click="filterDrawerOpen = true"
                    :aria-expanded="filterDrawerOpen.toString()"
                    class="btn btn-sm h-10 min-h-10 gap-2 rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-3 text-[11px] font-medium shadow-admin-control hover:border-[var(--admin-border-strong)] hover:bg-[var(--admin-surface-soft)]">
                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <path d="M4 7h16M7 12h10m-7 5h4" />
                    </svg>
                    Filter
                    @if ($drawerFilterCount > 0)
                        <span class="grid size-4 place-items-center rounded bg-accent text-[8px] font-bold text-accent-content">
                            {{ $drawerFilterCount }}
                        </span>
                    @endif
                </button>
                <span class="ml-auto whitespace-nowrap text-[10px] tabular-nums text-base-content/40 lg:ml-2">
                    {{ $orders->total() }} {{ Str::plural('order', $orders->total()) }}
                </span>
            </div>
        </x-slot:toolbar>

        @if ($hasFilters)
            <x-slot:chips>
                <div class="flex flex-wrap items-center gap-1.5 border-b border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-4 py-2">
                    <span class="mr-1 text-[8px] font-semibold uppercase tracking-[0.14em] text-base-content/30">Applied</span>
                    @if (filled($search))
                        <x-admin.filter-chip wire:click="$set('search', '')" :label="'Search: '.$search" />
                    @endif
                    @if ($payment !== 'all')
                        <x-admin.filter-chip wire:click="$set('payment', 'all')" :label="'Payment: '.str($payment)->title()" />
                    @endif
                    @if ($sort !== 'newest')
                        <x-admin.filter-chip wire:click="$set('sort', 'newest')" :label="$orderSortLabels[$sort]" />
                    @endif
                    <button type="button" wire:click="clearAll"
                        class="ml-auto text-[9px] font-medium text-base-content/40 hover:text-base-content">Clear all</button>
                </div>
            </x-slot:chips>
        @endif

        @if (count($selected) > 0)
            <x-slot:bulk>
                <x-admin.bulk-actions :count="count($selected)">
                    @foreach ([
                        'processing' => 'Process',
                        'shipped' => 'Mark shipped',
                        'delivered' => 'Mark delivered',
                        'cancelled' => 'Cancel',
                    ] as $value => $label)
                        <button type="button" wire:click="bulkUpdateStatus('{{ $value }}')"
                            @if ($value === 'cancelled') wire:confirm="Cancel the selected orders?" @endif
                            wire:loading.attr="disabled"
                            class="btn btn-ghost btn-xs h-7 min-h-7 rounded-md px-2.5 text-[10px]
                                {{ $value === 'cancelled' ? 'text-error hover:bg-error/10' : 'text-base-content hover:bg-base-content/10' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                    <button type="button" wire:click="clearSelection"
                        class="btn btn-ghost btn-xs h-7 min-h-7 rounded-md px-2 text-[10px] text-base-content/55 hover:bg-base-content/10">
                        Clear
                    </button>
                </x-admin.bulk-actions>
            </x-slot:bulk>
        @endif

        <x-slot:table>
            <table class="w-full min-w-[940px] table-fixed text-left">
                <thead class="border-b border-[var(--admin-border)] bg-[var(--admin-surface-sunken)]">
                    <tr class="text-[9px] font-semibold uppercase tracking-[0.11em] text-base-content/40">
                        <th class="w-10 px-4 py-2.5">
                            <input type="checkbox" wire:click="togglePageSelection(@js($pageIds))"
                                @checked($allPageSelected)
                                class="checkbox checkbox-xs rounded border-base-content/25 checked:border-accent checked:bg-accent checked:text-accent-content"
                                aria-label="Select all orders on this page">
                        </th>
                        <th class="w-[19%] px-3 py-2.5">Order</th>
                        <th class="w-[22%] px-3 py-2.5">Customer</th>
                        <th class="w-[13%] px-3 py-2.5">Date</th>
                        <th class="w-[13%] px-3 py-2.5">Total</th>
                        <th class="w-[13%] px-3 py-2.5">Payment</th>
                        <th class="w-[14%] px-3 py-2.5">Status</th>
                        <th class="w-16 px-3 py-2.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--admin-border)]">
                    @forelse ($orders as $order)
                        @php($latestPayment = $order->latestPayment)
                        <tr wire:key="order-row-{{ $order->id }}"
                            class="transition-colors hover:bg-[var(--admin-surface-soft)]">
                            <td class="px-4 py-3">
                                <input type="checkbox" value="{{ $order->id }}" wire:model.live="selected"
                                    class="checkbox checkbox-xs rounded border-base-content/25 checked:border-accent checked:bg-accent checked:text-accent-content"
                                    aria-label="Select order {{ $order->order_number }}">
                            </td>
                            <td class="px-3 py-3">
                                <button type="button" wire:click="openOrder({{ $order->id }})"
                                    class="text-left">
                                    <strong class="block text-xs font-semibold">{{ $order->order_number }}</strong>
                                    <span class="mt-1 block text-[10px] text-base-content/40">
                                        {{ $order->items_count }} {{ Str::plural('item', $order->items_count) }}
                                    </span>
                                </button>
                            </td>
                            <td class="px-3 py-3">
                                <strong class="block truncate text-xs font-medium">{{ $order->shipping_full_name }}</strong>
                                <span class="mt-1 block truncate text-[10px] text-base-content/40">
                                    {{ $order->email ?: $order->user?->email }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-xs text-base-content/65">
                                {{ $order->created_at->format('M j, Y') }}
                                <span class="mt-1 block text-[10px] text-base-content/35">{{ $order->created_at->format('g:i A') }}</span>
                            </td>
                            <td class="px-3 py-3 text-xs font-semibold">${{ number_format($order->total, 2) }}</td>
                            <td class="px-3 py-3">
                                <x-admin.badge :tone="$paymentTones[$latestPayment?->status ?? 'pending'] ?? 'gray'">
                                    {{ str($latestPayment?->status ?? 'pending')->title() }}
                                </x-admin.badge>
                            </td>
                            <td class="px-3 py-3">
                                <x-admin.badge :tone="$orderTones[$order->status] ?? 'gray'">
                                    {{ str($order->status)->title() }}
                                </x-admin.badge>
                            </td>
                            <td class="px-3 py-3 text-right">
                                <button type="button" wire:click="openOrder({{ $order->id }})"
                                    class="btn btn-ghost btn-square btn-sm rounded-lg border border-transparent text-base-content/55 hover:border-[var(--admin-border)] hover:bg-[var(--admin-surface-raised)]"
                                    aria-label="View order {{ $order->order_number }}">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8">
                                        <path d="M3 12s3.5-6 9-6 9 6 9 6-3.5 6-9 6-9-6-9-6Z" />
                                        <circle cx="12" cy="12" r="2.5" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <x-admin.empty-state title="No orders found"
                                    description="Try another status or adjust the current filters." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-slot:table>

        <x-slot:mobile>
            <div class="divide-y divide-[var(--admin-border)]">
                @forelse ($orders as $order)
                    @php($latestPayment = $order->latestPayment)
                    <article wire:key="order-card-{{ $order->id }}" class="bg-[var(--admin-surface)] p-4">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" value="{{ $order->id }}" wire:model.live="selected"
                                class="checkbox checkbox-sm mt-0.5 rounded border-base-content/25 checked:border-accent checked:bg-accent checked:text-accent-content"
                                aria-label="Select order {{ $order->order_number }}">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-3">
                                    <button type="button" wire:click="openOrder({{ $order->id }})"
                                        class="min-w-0 text-left">
                                        <strong class="block truncate text-sm">{{ $order->order_number }}</strong>
                                        <span class="mt-1 block truncate text-[11px] text-base-content/45">{{ $order->shipping_full_name }}</span>
                                    </button>
                                    <x-admin.badge :tone="$orderTones[$order->status] ?? 'gray'">
                                        {{ str($order->status)->title() }}
                                    </x-admin.badge>
                                </div>

                                <div class="mt-3 grid grid-cols-3 gap-3 rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] p-3 shadow-inner">
                                    <div>
                                        <span class="block text-[9px] uppercase tracking-wide text-base-content/35">Total</span>
                                        <strong class="mt-1 block text-xs">${{ number_format($order->total, 2) }}</strong>
                                    </div>
                                    <div>
                                        <span class="block text-[9px] uppercase tracking-wide text-base-content/35">Payment</span>
                                        <strong class="mt-1 block text-xs capitalize">{{ $latestPayment?->status ?? 'pending' }}</strong>
                                    </div>
                                    <div>
                                        <span class="block text-[9px] uppercase tracking-wide text-base-content/35">Date</span>
                                        <strong class="mt-1 block text-xs">{{ $order->created_at->format('M j') }}</strong>
                                    </div>
                                </div>

                                <div class="mt-3 flex justify-end">
                                    <button type="button" wire:click="openOrder({{ $order->id }})"
                                        class="btn btn-ghost btn-xs rounded-lg">View order</button>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <x-admin.empty-state title="No orders found"
                        description="Try another status or adjust the current filters." />
                @endforelse
            </div>
        </x-slot:mobile>

        @if ($orders->hasPages())
            <x-slot:footer>
                <x-admin.pagination :paginator="$orders" />
            </x-slot:footer>
        @endif
    </x-admin.resource-panel>

    <x-admin.filter-drawer title="Order filters" description="Refine by payment state or ordering."
        :count="$drawerFilterCount">
        <x-admin.filter-section title="Payment status">
            <div class="grid grid-cols-2 gap-2">
                @foreach (['all' => 'All payments', 'pending' => 'Pending', 'paid' => 'Paid', 'failed' => 'Failed', 'refunded' => 'Refunded'] as $value => $label)
                    <button type="button" wire:click="$set('payment', '{{ $value }}')"
                        class="btn btn-sm h-10 min-h-10 justify-start rounded-xl border text-[11px]
                            {{ $payment === $value ? 'border-accent/30 bg-accent/15 text-base-content shadow-admin-control' : 'border-[var(--admin-border)] bg-[var(--admin-surface-raised)] text-base-content/55 shadow-admin-control hover:border-[var(--admin-border-strong)] hover:bg-[var(--admin-surface-soft)]' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </x-admin.filter-section>

        <x-admin.filter-section title="Sort order">
            <div class="space-y-1">
                @foreach ($orderSortLabels as $value => $label)
                    <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-transparent px-3 py-2.5 text-xs hover:border-[var(--admin-border)] hover:bg-[var(--admin-surface-raised)]">
                        <input type="radio" wire:model.live="sort" value="{{ $value }}"
                            class="radio radio-xs radio-accent">
                        {{ $label }}
                    </label>
                @endforeach
            </div>
        </x-admin.filter-section>

        <x-slot:actions>
            <button type="button" wire:click="clearAll" @click="filterDrawerOpen = false"
                class="btn btn-ghost btn-sm rounded-lg">Reset</button>
            <button type="button" @click="filterDrawerOpen = false"
                class="btn btn-primary btn-sm h-9 min-h-9 rounded-lg px-5 shadow-[0_8px_20px_rgba(255,122,0,.18)]">Show orders</button>
        </x-slot:actions>
    </x-admin.filter-drawer>

    <div class="fixed inset-0 z-[80]" :class="orderDrawerOpen ? 'pointer-events-auto' : 'pointer-events-none'"
        :aria-hidden="(!orderDrawerOpen).toString()">
        <button type="button" x-show="orderDrawerOpen" x-cloak
            x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            @click="orderDrawerOpen = false; setTimeout(() => $wire.closeOrder(), 250)"
            class="absolute inset-0 bg-black/65 backdrop-blur-[2px]" aria-label="Close order details"></button>

        <aside x-show="orderDrawerOpen" x-cloak
            x-transition:enter="transform transition duration-300 ease-out"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition duration-250 ease-in"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
            role="dialog" aria-modal="true" aria-label="Order details"
            class="absolute inset-y-0 right-0 flex w-full flex-col overflow-hidden bg-[var(--admin-surface)] shadow-2xl sm:inset-y-3 sm:right-3 sm:max-w-xl sm:rounded-2xl sm:border sm:border-[var(--admin-border)]">
            @if ($selectedOrder)
                @php($selectedPayment = $selectedOrder->payments->first())
                <header class="relative flex items-start justify-between gap-4 border-b border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-5 py-4">
                    <span class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/15 to-transparent"></span>
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-[0.12em] text-base-content/40">Order details</p>
                        <h2 class="mt-1 text-lg font-semibold">{{ $selectedOrder->order_number }}</h2>
                        <p class="mt-1 text-[11px] text-base-content/40">{{ $selectedOrder->created_at->format('M j, Y · g:i A') }}</p>
                    </div>
                    <button type="button"
                        @click="orderDrawerOpen = false; setTimeout(() => $wire.closeOrder(), 250)"
                        class="btn btn-square btn-sm rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] shadow-admin-control hover:bg-[var(--admin-surface-soft)]"
                        aria-label="Close order details">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M6 6l12 12M18 6 6 18" />
                        </svg>
                    </button>
                </header>

                <div class="min-h-0 flex-1 space-y-4 overflow-y-auto bg-[var(--admin-canvas)] p-5">
                    <section class="rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface)] p-4 shadow-admin-control">
                        <div class="flex items-center justify-between gap-3">
                            <h3 class="text-xs font-semibold">Customer</h3>
                            <x-admin.badge :tone="$paymentTones[$selectedPayment?->status ?? 'pending'] ?? 'gray'">
                                {{ str($selectedPayment?->status ?? 'pending')->title() }}
                            </x-admin.badge>
                        </div>
                        <dl class="mt-4 grid gap-3 text-xs sm:grid-cols-2">
                            <div><dt class="text-base-content/40">Name</dt><dd class="mt-1 font-medium">{{ $selectedOrder->shipping_full_name }}</dd></div>
                            <div><dt class="text-base-content/40">Email</dt><dd class="mt-1 break-all font-medium">{{ $selectedOrder->email ?: $selectedOrder->user?->email }}</dd></div>
                            <div><dt class="text-base-content/40">Phone</dt><dd class="mt-1 font-medium">{{ $selectedOrder->shipping_phone }}</dd></div>
                            <div><dt class="text-base-content/40">Payment</dt><dd class="mt-1 font-medium capitalize">{{ str($selectedPayment?->method ?? 'Not recorded')->replace('_', ' ') }}</dd></div>
                        </dl>
                    </section>

                    <section class="rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface)] p-4 shadow-admin-control">
                        <h3 class="text-xs font-semibold">Shipping address</h3>
                        <address class="mt-3 text-xs not-italic leading-5 text-base-content/65">
                            {{ $selectedOrder->shipping_address_line1 }}
                            @if ($selectedOrder->shipping_address_line2), {{ $selectedOrder->shipping_address_line2 }}@endif<br>
                            {{ collect([$selectedOrder->shipping_district_area, $selectedOrder->shipping_city, $selectedOrder->shipping_state_region])->filter()->join(', ') }}
                            @if ($selectedOrder->shipping_postal_code) {{ $selectedOrder->shipping_postal_code }}@endif<br>
                            {{ $selectedOrder->shipping_country }}
                        </address>
                    </section>

                    <section class="overflow-hidden rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface)] shadow-admin-control">
                        <header class="border-b border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-4 py-3">
                            <h3 class="text-xs font-semibold">Items</h3>
                        </header>
                        <div class="divide-y divide-[var(--admin-border)]">
                            @foreach ($selectedOrder->items as $item)
                                <div class="flex items-center gap-3 p-4">
                                    @if ($item->variant?->primary_image)
                                        <img src="{{ Storage::url($item->variant->primary_image->image_url) }}"
                                            alt="" class="size-11 shrink-0 rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] object-cover">
                                    @else
                                        <span class="grid size-11 shrink-0 place-items-center rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] text-base-content/25">
                                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path d="M4 5h16v14H4V5Z" />
                                            </svg>
                                        </span>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <strong class="block truncate text-xs">{{ $item->product_name }}</strong>
                                        <span class="mt-1 block truncate text-[10px] text-base-content/40">{{ $item->variant_name }} · {{ $item->variant_sku }}</span>
                                    </div>
                                    <div class="shrink-0 text-right">
                                        <strong class="block text-xs">${{ number_format($item->subtotal, 2) }}</strong>
                                        <span class="mt-1 block text-[10px] text-base-content/40">{{ $item->quantity }} × ${{ number_format($item->unit_price, 2) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section class="rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface)] p-4 shadow-admin-control">
                        <h3 class="text-xs font-semibold">Payment summary</h3>
                        <dl class="mt-4 space-y-2 text-xs">
                            <div class="flex justify-between"><dt class="text-base-content/45">Subtotal</dt><dd>${{ number_format($selectedOrder->subtotal, 2) }}</dd></div>
                            @if ($selectedOrder->discount > 0)
                                <div class="flex justify-between text-success"><dt>Discount</dt><dd>−${{ number_format($selectedOrder->discount, 2) }}</dd></div>
                            @endif
                            <div class="flex justify-between"><dt class="text-base-content/45">Shipping</dt><dd>${{ number_format($selectedOrder->shipping_fee, 2) }}</dd></div>
                            <div class="flex justify-between"><dt class="text-base-content/45">Tax</dt><dd>${{ number_format($selectedOrder->tax, 2) }}</dd></div>
                            @if ($selectedOrder->insurance_fee > 0)
                                <div class="flex justify-between"><dt class="text-base-content/45">Insurance</dt><dd>${{ number_format($selectedOrder->insurance_fee, 2) }}</dd></div>
                            @endif
                            <div class="flex justify-between border-t border-[var(--admin-border)] pt-3 text-sm font-semibold"><dt>Total</dt><dd>${{ number_format($selectedOrder->total, 2) }}</dd></div>
                        </dl>
                    </section>

                    @if ($selectedOrder->notes)
                        <section class="rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface)] p-4 shadow-admin-control">
                            <h3 class="text-xs font-semibold">Order note</h3>
                            <p class="mt-2 text-xs leading-5 text-base-content/60">{{ $selectedOrder->notes }}</p>
                        </section>
                    @endif
                </div>

                <footer class="border-t border-[var(--admin-border)] bg-[var(--admin-surface-raised)] p-4">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                        <x-admin.form-field label="Order status" name="selectedOrderStatus" class="flex-1">
                            <select id="selectedOrderStatus" wire:model="selectedOrderStatus"
                                class="select select-bordered h-10 min-h-10 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent">
                                @foreach (['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $value)
                                    <option value="{{ $value }}">{{ str($value)->title() }}</option>
                                @endforeach
                            </select>
                        </x-admin.form-field>
                        <button type="button" wire:click="updateOrderStatus" wire:loading.attr="disabled"
                            wire:target="updateOrderStatus"
                            class="btn btn-primary btn-sm h-10 min-h-10 rounded-xl px-5 shadow-[0_8px_20px_rgba(255,122,0,.18)]">
                            <span wire:loading wire:target="updateOrderStatus" class="loading loading-spinner loading-xs"></span>
                            <span wire:loading.remove wire:target="updateOrderStatus">Update status</span>
                        </button>
                    </div>
                </footer>
            @endif
        </aside>
    </div>
</div>
