@php
    $drawerFilterCount = ($verification !== 'all' ? 1 : 0)
        + ($activity !== 'all' ? 1 : 0)
        + ($sort !== 'newest' ? 1 : 0);
    $pageIds = $customers->getCollection()->pluck('id')->map(fn ($id) => (int) $id)->values()->all();
    $allPageSelected = $pageIds !== [] && count(array_intersect($pageIds, array_map('intval', $selected))) === count($pageIds);
    $sortLabels = [
        'newest' => 'Newest accounts',
        'oldest' => 'Oldest accounts',
        'name' => 'Name A-Z',
        'spend_desc' => 'Highest spend',
        'orders_desc' => 'Most orders',
        'recent_order' => 'Most recent order',
    ];
    $statusTones = [
        'active' => 'green',
        'pending' => 'amber',
        'banned' => 'red',
    ];
@endphp

<div class="mx-auto w-full max-w-[1600px] space-y-5"
    x-data="{ filterDrawerOpen: false, customerDrawerOpen: @entangle('drawerOpen') }"
    @keydown.escape.window="
        filterDrawerOpen = false;
        if (customerDrawerOpen) {
            customerDrawerOpen = false;
            setTimeout(() => $wire.closeCustomer(), 220);
        }
    ">
    <x-admin.page-header title="Customers" :count="$summary['all']" />

    <x-admin.resource-panel
        loading-target="search,status,verification,activity,sort,clearAll,bulkUpdateStatus">
        <x-slot:navigation>
            @foreach ([
                'all' => ['All customers', $summary['all']],
                'active' => ['Active', $summary['active']],
                'pending' => ['Pending', $summary['pending']],
                'banned' => ['Banned', $summary['banned']],
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
                    stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <circle cx="11" cy="11" r="7" />
                    <path d="m20 20-4-4" />
                </svg>
                <input type="search" wire:model.live.debounce.350ms="search"
                    class="grow border-0 bg-transparent p-0 text-xs shadow-none outline-none focus:border-0 focus:ring-0"
                    placeholder="Search name, email, phone or order">
                <span wire:loading wire:target="search"
                    class="loading loading-spinner loading-xs shrink-0 text-accent"></span>
            </label>

            <div class="flex flex-1 items-center gap-2 lg:justify-end">
                <button type="button" @click="filterDrawerOpen = true"
                    :aria-expanded="filterDrawerOpen.toString()"
                    class="btn btn-sm h-10 min-h-10 gap-2 rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-3 text-[11px] font-medium shadow-admin-control hover:border-[var(--admin-border-strong)] hover:bg-[var(--admin-surface-soft)]">
                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" aria-hidden="true">
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
                    {{ $customers->total() }} {{ Str::plural('customer', $customers->total()) }}
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
                    @if ($verification !== 'all')
                        <x-admin.filter-chip wire:click="$set('verification', 'all')" :label="str($verification)->title()" />
                    @endif
                    @if ($activity !== 'all')
                        <x-admin.filter-chip wire:click="$set('activity', 'all')"
                            :label="$activity === 'with_orders' ? 'Has orders' : 'No orders'" />
                    @endif
                    @if ($sort !== 'newest')
                        <x-admin.filter-chip wire:click="$set('sort', 'newest')" :label="$sortLabels[$sort]" />
                    @endif
                    <button type="button" wire:click="clearAll"
                        class="ml-auto text-[9px] font-medium text-base-content/40 hover:text-base-content">
                        Clear all
                    </button>
                </div>
            </x-slot:chips>
        @endif

        @if (count($selected) > 0)
            <x-slot:bulk>
                <x-admin.bulk-actions :count="count($selected)">
                    <button type="button" wire:click="bulkUpdateStatus('active')"
                        class="btn btn-ghost btn-xs min-h-7 rounded-md text-[9px] text-base-content hover:bg-base-content/10">
                        Activate
                    </button>
                    <button type="button" wire:click="bulkUpdateStatus('pending')"
                        class="btn btn-ghost btn-xs min-h-7 rounded-md text-[9px] text-base-content hover:bg-base-content/10">
                        Mark pending
                    </button>
                    <button type="button" wire:click="bulkUpdateStatus('banned')"
                        wire:confirm="Ban the selected customer accounts?"
                        class="btn btn-ghost btn-xs min-h-7 rounded-md text-[9px] text-error hover:bg-error/10">
                        Ban
                    </button>
                    <button type="button" wire:click="clearSelection"
                        class="btn btn-ghost btn-xs ml-auto min-h-7 rounded-md text-[9px] text-base-content/55 hover:bg-base-content/10">
                        Clear
                    </button>
                </x-admin.bulk-actions>
            </x-slot:bulk>
        @endif

        <x-slot:table>
            <table class="table table-sm w-full table-fixed">
                <thead class="border-b border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] text-[9px] uppercase tracking-[0.1em] text-base-content/40">
                    <tr>
                        <th class="w-11 px-4 py-2.5">
                            <input type="checkbox" class="checkbox checkbox-xs rounded"
                                @checked($allPageSelected)
                                wire:click="togglePageSelection(@js($pageIds))"
                                aria-label="Select all customers on this page">
                        </th>
                        <th class="w-[25%] px-3 py-2.5">Customer</th>
                        <th class="w-[19%] px-3 py-2.5">Contact</th>
                        <th class="w-[10%] px-3 py-2.5">Orders</th>
                        <th class="w-[13%] px-3 py-2.5">Lifetime spend</th>
                        <th class="w-[14%] px-3 py-2.5">Last order</th>
                        <th class="w-[11%] px-3 py-2.5">Status</th>
                        <th class="px-4 py-2.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--admin-border)]">
                    @forelse ($customers as $customer)
                        <tr wire:key="customer-{{ $customer->id }}"
                            class="group transition-colors hover:bg-[var(--admin-surface-soft)]">
                            <td class="px-4 py-3.5 align-middle">
                                <input type="checkbox" wire:model.live="selected" value="{{ $customer->id }}"
                                    class="checkbox checkbox-xs rounded" aria-label="Select {{ $customer->name }}">
                            </td>
                            <td class="px-3 py-3.5">
                                <div class="flex min-w-0 items-center gap-3">
                                    @if ($customer->avatar)
                                        <img src="{{ str_starts_with($customer->avatar, 'http') ? $customer->avatar : Storage::url($customer->avatar) }}"
                                            alt="" class="size-9 shrink-0 rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] object-cover shadow-inner">
                                    @else
                                        <span
                                            class="grid size-9 shrink-0 place-items-center rounded-xl bg-accent text-[11px] font-semibold text-accent-content shadow-[0_7px_16px_rgba(255,122,0,.18)]">
                                            {{ str($customer->name)->substr(0, 1)->upper() }}
                                        </span>
                                    @endif
                                    <span class="min-w-0">
                                        <strong class="block truncate text-xs font-semibold">{{ $customer->name }}</strong>
                                        <small class="mt-0.5 flex items-center gap-1 truncate text-[9px] text-base-content/35">
                                            Joined {{ $customer->created_at->format('M Y') }}
                                            @if ($customer->email_verified_at)
                                                <span class="size-1.5 rounded-full bg-success" title="Email verified"
                                                    aria-label="Email verified"></span>
                                            @endif
                                        </small>
                                    </span>
                                </div>
                            </td>
                            <td class="px-3 py-3.5">
                                <span class="block truncate text-[11px] text-base-content/70">{{ $customer->email }}</span>
                                <small class="mt-0.5 block truncate text-[9px] text-base-content/35">
                                    {{ $customer->phone ?: 'No phone number' }}
                                </small>
                            </td>
                            <td class="px-3 py-3.5">
                                <strong class="block text-xs tabular-nums">{{ $customer->orders_count }}</strong>
                                <small class="mt-0.5 block text-[9px] text-base-content/35">{{ $customer->addresses_count }} {{ Str::plural('address', $customer->addresses_count) }}</small>
                            </td>
                            <td class="px-3 py-3.5 text-xs font-semibold tabular-nums">
                                ${{ number_format($customer->lifetime_value ?? 0, 2) }}
                            </td>
                            <td class="px-3 py-3.5 text-[11px] text-base-content/55">
                                {{ $customer->last_order_at ? \Carbon\Carbon::parse($customer->last_order_at)->format('M j, Y') : 'Never' }}
                            </td>
                            <td class="px-3 py-3.5">
                                <x-admin.badge :tone="$statusTones[$customer->status] ?? 'gray'">
                                    {{ str($customer->status)->title() }}
                                </x-admin.badge>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex justify-end">
                                    <button type="button" wire:click="openCustomer({{ $customer->id }})"
                                        class="btn btn-ghost btn-xs h-8 min-h-8 rounded-lg border border-transparent px-2.5 text-[10px] text-base-content/60 hover:border-[var(--admin-border)] hover:bg-[var(--admin-surface-raised)]">
                                        View
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <x-admin.empty-state title="No customers found"
                                    description="Try another lifecycle view or adjust the current filters." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-slot:table>

        <x-slot:mobile>
            <div class="divide-y divide-[var(--admin-border)]">
                @forelse ($customers as $customer)
                    <article wire:key="customer-mobile-{{ $customer->id }}" class="bg-[var(--admin-surface)] p-4">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" wire:model.live="selected" value="{{ $customer->id }}"
                                class="checkbox checkbox-sm mt-1 rounded" aria-label="Select {{ $customer->name }}">

                            @if ($customer->avatar)
                                <img src="{{ str_starts_with($customer->avatar, 'http') ? $customer->avatar : Storage::url($customer->avatar) }}"
                                    alt="" class="size-10 shrink-0 rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] object-cover">
                            @else
                                <span
                                    class="grid size-10 shrink-0 place-items-center rounded-xl bg-accent text-xs font-semibold text-accent-content shadow-[0_7px_16px_rgba(255,122,0,.18)]">
                                    {{ str($customer->name)->substr(0, 1)->upper() }}
                                </span>
                            @endif

                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-3">
                                    <span class="min-w-0">
                                        <strong class="block truncate text-xs">{{ $customer->name }}</strong>
                                        <small class="mt-1 block truncate text-[10px] text-base-content/40">{{ $customer->email }}</small>
                                    </span>
                                    <x-admin.badge :tone="$statusTones[$customer->status] ?? 'gray'">
                                        {{ str($customer->status)->title() }}
                                    </x-admin.badge>
                                </div>

                                <dl class="mt-4 grid grid-cols-3 gap-3 rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] p-3 shadow-inner">
                                    <div><dt class="text-[8px] uppercase text-base-content/35">Orders</dt><dd class="mt-1 text-[11px] font-semibold">{{ $customer->orders_count }}</dd></div>
                                    <div><dt class="text-[8px] uppercase text-base-content/35">Spend</dt><dd class="mt-1 text-[11px] font-semibold">${{ number_format($customer->lifetime_value ?? 0, 2) }}</dd></div>
                                    <div><dt class="text-[8px] uppercase text-base-content/35">Last order</dt><dd class="mt-1 truncate text-[11px] font-semibold">{{ $customer->last_order_at ? \Carbon\Carbon::parse($customer->last_order_at)->format('M j') : 'Never' }}</dd></div>
                                </dl>

                                <div class="mt-3 flex justify-end">
                                    <button type="button" wire:click="openCustomer({{ $customer->id }})"
                                        class="btn btn-ghost btn-xs rounded-md">View customer</button>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <x-admin.empty-state title="No customers found"
                        description="Try another lifecycle view or adjust the current filters." />
                @endforelse
            </div>
        </x-slot:mobile>

        @if ($customers->hasPages())
            <x-slot:footer>
                <x-admin.pagination :paginator="$customers" />
            </x-slot:footer>
        @endif
    </x-admin.resource-panel>

    <x-admin.filter-drawer title="Customer filters"
        description="Refine accounts by verification, order activity, or value."
        :count="$drawerFilterCount">
        <x-admin.filter-section title="Email verification">
            <div class="grid grid-cols-3 gap-2">
                @foreach (['all' => 'All', 'verified' => 'Verified', 'unverified' => 'Unverified'] as $value => $label)
                    <button type="button" wire:click="$set('verification', '{{ $value }}')"
                        class="btn btn-sm h-10 min-h-10 rounded-xl border text-[11px]
                            {{ $verification === $value ? 'border-accent/30 bg-accent/15 text-base-content shadow-admin-control' : 'border-[var(--admin-border)] bg-[var(--admin-surface-raised)] text-base-content/55 shadow-admin-control hover:border-[var(--admin-border-strong)] hover:bg-[var(--admin-surface-soft)]' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </x-admin.filter-section>

        <x-admin.filter-section title="Order activity">
            <div class="grid grid-cols-3 gap-2">
                @foreach (['all' => 'All', 'with_orders' => 'Has orders', 'without_orders' => 'No orders'] as $value => $label)
                    <button type="button" wire:click="$set('activity', '{{ $value }}')"
                        class="btn btn-sm h-10 min-h-10 rounded-xl border text-[11px]
                            {{ $activity === $value ? 'border-accent/30 bg-accent/15 text-base-content shadow-admin-control' : 'border-[var(--admin-border)] bg-[var(--admin-surface-raised)] text-base-content/55 shadow-admin-control hover:border-[var(--admin-border-strong)] hover:bg-[var(--admin-surface-soft)]' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </x-admin.filter-section>

        <x-admin.filter-section title="Sort order">
            <div class="space-y-1">
                @foreach ($sortLabels as $value => $label)
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
                class="btn btn-primary btn-sm h-9 min-h-9 rounded-lg px-5 shadow-[0_8px_20px_rgba(255,122,0,.18)]">Show customers</button>
        </x-slot:actions>
    </x-admin.filter-drawer>

    <div class="fixed inset-0 z-[80]"
        :class="customerDrawerOpen ? 'pointer-events-auto' : 'pointer-events-none'"
        :aria-hidden="(!customerDrawerOpen).toString()">
        <button type="button" x-show="customerDrawerOpen" x-cloak
            x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            @click="customerDrawerOpen = false; setTimeout(() => $wire.closeCustomer(), 220)"
            class="absolute inset-0 bg-black/65 backdrop-blur-[2px]" aria-label="Close customer details"></button>

        <aside x-show="customerDrawerOpen" x-cloak
            x-transition:enter="transform transition duration-300 ease-out"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition duration-220 ease-in"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
            role="dialog" aria-modal="true" aria-label="Customer details"
            class="absolute inset-y-0 right-0 flex w-full max-w-xl flex-col overflow-hidden bg-[var(--admin-surface)] shadow-2xl sm:inset-y-3 sm:right-3 sm:rounded-2xl sm:border sm:border-[var(--admin-border)]">
            @if ($selectedCustomer)
                <header class="relative border-b border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-5 py-4">
                    <span class="absolute inset-x-0 top-0 h-0.5 bg-accent"></span>
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex min-w-0 items-center gap-3">
                            @if ($selectedCustomer->avatar)
                                <img src="{{ str_starts_with($selectedCustomer->avatar, 'http') ? $selectedCustomer->avatar : Storage::url($selectedCustomer->avatar) }}"
                                    alt="" class="size-11 shrink-0 rounded-xl bg-base-200 object-cover">
                            @else
                                <span
                                    class="grid size-11 shrink-0 place-items-center rounded-xl bg-accent text-sm font-semibold text-accent-content">
                                    {{ str($selectedCustomer->name)->substr(0, 1)->upper() }}
                                </span>
                            @endif
                            <span class="min-w-0">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.12em] text-base-content/40">Customer account</p>
                                <h2 class="mt-1 truncate text-base font-semibold">{{ $selectedCustomer->name }}</h2>
                            </span>
                        </div>
                        <button type="button"
                            @click="customerDrawerOpen = false; setTimeout(() => $wire.closeCustomer(), 220)"
                            class="btn btn-square btn-sm rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] shadow-admin-control hover:bg-[var(--admin-surface-soft)]" aria-label="Close customer details">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8"><path d="M6 6l12 12M18 6 6 18" /></svg>
                        </button>
                    </div>
                </header>

                <div class="min-h-0 flex-1 space-y-4 overflow-y-auto bg-[var(--admin-canvas)] p-5">
                    <section class="grid grid-cols-3 divide-x divide-[var(--admin-border)] overflow-hidden rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface)] shadow-admin-control">
                        <div class="p-4">
                            <span class="text-[8px] font-semibold uppercase tracking-[0.1em] text-base-content/35">Orders</span>
                            <strong class="mt-2 block text-lg tabular-nums">{{ $selectedCustomer->orders_count }}</strong>
                        </div>
                        <div class="p-4">
                            <span class="text-[8px] font-semibold uppercase tracking-[0.1em] text-base-content/35">Spend</span>
                            <strong class="mt-2 block text-lg tabular-nums">${{ number_format($selectedCustomer->lifetime_value ?? 0, 2) }}</strong>
                        </div>
                        <div class="p-4">
                            <span class="text-[8px] font-semibold uppercase tracking-[0.1em] text-base-content/35">Since</span>
                            <strong class="mt-2 block text-sm">{{ $selectedCustomer->created_at->format('M Y') }}</strong>
                        </div>
                    </section>

                    <section class="rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface)] p-4 shadow-admin-control">
                        <div class="flex items-center justify-between gap-3">
                            <h3 class="text-xs font-semibold">Account details</h3>
                            <x-admin.badge :tone="$selectedCustomer->email_verified_at ? 'green' : 'amber'">
                                {{ $selectedCustomer->email_verified_at ? 'Email verified' : 'Unverified email' }}
                            </x-admin.badge>
                        </div>
                        <dl class="mt-4 grid gap-3 text-xs sm:grid-cols-2">
                            <div><dt class="text-base-content/40">Email</dt><dd class="mt-1 break-all font-medium">{{ $selectedCustomer->email }}</dd></div>
                            <div><dt class="text-base-content/40">Phone</dt><dd class="mt-1 font-medium">{{ $selectedCustomer->phone ?: 'Not provided' }}</dd></div>
                            <div><dt class="text-base-content/40">Joined</dt><dd class="mt-1 font-medium">{{ $selectedCustomer->created_at->format('M j, Y') }}</dd></div>
                            <div><dt class="text-base-content/40">Current status</dt><dd class="mt-1 font-medium capitalize">{{ $selectedCustomer->status }}</dd></div>
                        </dl>
                    </section>

                    <section class="overflow-hidden rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface)] shadow-admin-control">
                        <header class="flex items-center justify-between border-b border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-4 py-3">
                            <h3 class="text-xs font-semibold">Saved addresses</h3>
                            <span class="text-[9px] tabular-nums text-base-content/35">{{ $selectedCustomer->addresses->count() }}</span>
                        </header>
                        @forelse ($selectedCustomer->addresses as $address)
                            <div class="border-b border-[var(--admin-border)] p-4 last:border-b-0">
                                <div class="flex items-center gap-2">
                                    <strong class="text-xs">{{ $address->label ?: 'Address' }}</strong>
                                    @if ($address->is_default)
                                        <span class="rounded bg-accent/15 px-1.5 py-0.5 text-[8px] font-semibold">Default</span>
                                    @endif
                                </div>
                                <address class="mt-2 text-[11px] not-italic leading-5 text-base-content/55">
                                    {{ $address->address_line1 }}
                                    @if ($address->address_line2), {{ $address->address_line2 }}@endif<br>
                                    {{ collect([$address->district_area, $address->city, $address->state_region, $address->country])->filter()->join(', ') }}
                                </address>
                            </div>
                        @empty
                            <p class="px-4 py-6 text-center text-[11px] text-base-content/40">No saved addresses.</p>
                        @endforelse
                    </section>

                    <section class="overflow-hidden rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface)] shadow-admin-control">
                        <header class="flex items-center justify-between border-b border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-4 py-3">
                            <h3 class="text-xs font-semibold">Recent orders</h3>
                            @if ($selectedCustomer->orders_count > 0)
                                <a href="{{ route('admin.orders.index', ['search' => $selectedCustomer->email]) }}"
                                    class="text-[9px] font-semibold text-primary hover:underline">
                                    View all
                                </a>
                            @endif
                        </header>
                        @forelse ($selectedCustomer->orders as $order)
                            <a href="{{ route('admin.orders.index', ['search' => $order->order_number]) }}"
                                class="flex items-center justify-between gap-3 border-b border-[var(--admin-border)] px-4 py-3 transition-colors last:border-b-0 hover:bg-[var(--admin-surface-soft)]">
                                <span class="min-w-0">
                                    <strong class="block truncate text-[11px]">{{ $order->order_number }}</strong>
                                    <small class="mt-1 block text-[9px] text-base-content/35">{{ $order->created_at->format('M j, Y') }}</small>
                                </span>
                                <span class="flex shrink-0 items-center gap-3">
                                    <x-admin.badge :tone="match ($order->status) {
                                        'delivered' => 'green',
                                        'cancelled' => 'red',
                                        'pending' => 'amber',
                                        default => 'blue',
                                    }">
                                        {{ str($order->status)->title() }}
                                    </x-admin.badge>
                                    <strong class="text-[11px] tabular-nums">${{ number_format($order->total, 2) }}</strong>
                                </span>
                            </a>
                        @empty
                            <p class="px-4 py-6 text-center text-[11px] text-base-content/40">No orders yet.</p>
                        @endforelse
                    </section>
                </div>

                <footer class="border-t border-[var(--admin-border)] bg-[var(--admin-surface-raised)] p-4">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                        <x-admin.form-field label="Account status" name="selectedCustomerStatus" class="flex-1">
                            <select id="selectedCustomerStatus" wire:model="selectedCustomerStatus"
                                class="select select-bordered h-10 min-h-10 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent">
                                <option value="pending">Pending</option>
                                <option value="active">Active</option>
                                <option value="banned">Banned</option>
                            </select>
                        </x-admin.form-field>
                        <button type="button" wire:click="updateCustomerStatus"
                            wire:loading.attr="disabled" wire:target="updateCustomerStatus"
                            class="btn btn-primary btn-sm h-10 min-h-10 rounded-xl px-5 shadow-[0_8px_20px_rgba(255,122,0,.18)]">
                            <span wire:loading wire:target="updateCustomerStatus"
                                class="loading loading-spinner loading-xs"></span>
                            <span wire:loading.remove wire:target="updateCustomerStatus">Update status</span>
                        </button>
                    </div>
                </footer>
            @endif
        </aside>
    </div>
</div>
