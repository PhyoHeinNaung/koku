@php
    $drawerFilterCount = ($discountType !== 'all' ? 1 : 0) + ($sort !== 'newest' ? 1 : 0);
    $pageIds = $coupons->getCollection()->pluck('id')->map(fn ($id) => (int) $id)->values()->all();
    $allPageSelected = $pageIds !== [] && count(array_intersect($pageIds, array_map('intval', $selected))) === count($pageIds);
    $couponSortLabels = [
        'newest' => 'Newest first',
        'oldest' => 'Oldest first',
        'code_asc' => 'Code A-Z',
        'ending' => 'Ending soon',
        'usage_desc' => 'Most used',
    ];
@endphp

<div class="mx-auto w-full max-w-[1600px] space-y-5"
    x-data="{ filterDrawerOpen: false }"
    @keydown.escape.window="filterDrawerOpen = false">
    <x-admin.page-header title="Coupons" :count="$summary['all']"
        :action-href="route('admin.coupons.create')" action-label="Add coupon" />

    <x-admin.resource-panel loading-target="search,status,discountType,sort,clearAll,bulkSetActive,bulkDelete">
        <x-slot:navigation>
            @foreach ([
                'all' => ['All coupons', $summary['all']],
                'active' => ['Active', $summary['active']],
                'scheduled' => ['Scheduled', $summary['scheduled']],
                'expired' => ['Expired', $summary['expired']],
                'inactive' => ['Inactive', $summary['inactive']],
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
                    placeholder="Search code or description">
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
                    {{ $coupons->total() }} {{ Str::plural('coupon', $coupons->total()) }}
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
                    @if ($discountType !== 'all')
                        <x-admin.filter-chip wire:click="$set('discountType', 'all')"
                            :label="$discountType === 'fixed' ? 'Fixed amount' : 'Percentage'" />
                    @endif
                    @if ($sort !== 'newest')
                        <x-admin.filter-chip wire:click="$set('sort', 'newest')" :label="$couponSortLabels[$sort]" />
                    @endif
                    <button type="button" wire:click="clearAll"
                        class="ml-auto text-[9px] font-medium text-base-content/40 hover:text-base-content">Clear all</button>
                </div>
            </x-slot:chips>
        @endif

        @if (count($selected) > 0)
            <x-slot:bulk>
                <x-admin.bulk-actions :count="count($selected)">
                    <button type="button" wire:click="bulkSetActive(true)" wire:loading.attr="disabled"
                        class="btn btn-ghost btn-xs h-7 min-h-7 rounded-md px-2.5 text-[10px] text-base-content hover:bg-base-content/10">
                        Activate
                    </button>
                    <button type="button" wire:click="bulkSetActive(false)" wire:loading.attr="disabled"
                        class="btn btn-ghost btn-xs h-7 min-h-7 rounded-md px-2.5 text-[10px] text-base-content hover:bg-base-content/10">
                        Deactivate
                    </button>
                    <button type="button" wire:click="bulkDelete"
                        wire:confirm="Delete unused selected coupons? Used coupons will be kept."
                        wire:loading.attr="disabled"
                        class="btn btn-ghost btn-xs h-7 min-h-7 rounded-md px-2.5 text-[10px] text-error hover:bg-error/10">
                        Delete
                    </button>
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
                                aria-label="Select all coupons on this page">
                        </th>
                        <th class="w-[22%] px-3 py-2.5">Coupon</th>
                        <th class="w-[15%] px-3 py-2.5">Discount</th>
                        <th class="w-[15%] px-3 py-2.5">Minimum</th>
                        <th class="w-[13%] px-3 py-2.5">Usage</th>
                        <th class="w-[18%] px-3 py-2.5">Validity</th>
                        <th class="w-[12%] px-3 py-2.5">Status</th>
                        <th class="w-20 px-3 py-2.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--admin-border)]">
                    @forelse ($coupons as $coupon)
                        @php
                            $lifecycle = ! $coupon->is_active
                                ? 'inactive'
                                : ($coupon->start_date->isFuture()
                                    ? 'scheduled'
                                    : ($coupon->isValidNow() ? 'active' : 'expired'));
                            $tone = ['active' => 'green', 'scheduled' => 'blue', 'expired' => 'amber', 'inactive' => 'gray'][$lifecycle];
                        @endphp
                        <tr wire:key="coupon-row-{{ $coupon->id }}"
                            class="transition-colors hover:bg-[var(--admin-surface-soft)]">
                            <td class="px-4 py-3">
                                <input type="checkbox" value="{{ $coupon->id }}" wire:model.live="selected"
                                    class="checkbox checkbox-xs rounded border-base-content/25 checked:border-accent checked:bg-accent checked:text-accent-content"
                                    aria-label="Select coupon {{ $coupon->code }}">
                            </td>
                            <td class="px-3 py-3">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="group">
                                    <strong class="block font-mono text-xs font-semibold tracking-wide group-hover:text-accent">{{ $coupon->code }}</strong>
                                    <span class="mt-1 block truncate text-[10px] text-base-content/40">{{ $coupon->description ?: 'Promotion code' }}</span>
                                </a>
                            </td>
                            <td class="px-3 py-3 text-xs font-semibold">
                                @if ($coupon->discount_type === 'percentage')
                                    {{ rtrim(rtrim($coupon->discount_value, '0'), '.') }}%
                                @else
                                    ${{ number_format($coupon->discount_value, 2) }}
                                @endif
                            </td>
                            <td class="px-3 py-3 text-xs text-base-content/60">
                                {{ $coupon->minimum_order_amount ? '$'.number_format($coupon->minimum_order_amount, 2) : 'None' }}
                            </td>
                            <td class="px-3 py-3">
                                <span class="text-xs font-medium tabular-nums">{{ $coupon->used_count }}</span>
                                <span class="text-[10px] text-base-content/35"> / {{ $coupon->usage_limit ?: '∞' }}</span>
                            </td>
                            <td class="px-3 py-3 text-xs text-base-content/60">
                                {{ $coupon->start_date->format('M j') }} – {{ $coupon->end_date->format('M j, Y') }}
                            </td>
                            <td class="px-3 py-3">
                                <x-admin.badge :tone="$tone">{{ str($lifecycle)->title() }}</x-admin.badge>
                            </td>
                            <td class="px-3 py-3">
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}"
                                        class="btn btn-ghost btn-square btn-sm rounded-lg border border-transparent text-base-content/55 hover:border-[var(--admin-border)] hover:bg-[var(--admin-surface-raised)]"
                                        aria-label="Edit {{ $coupon->code }}">
                                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8">
                                            <path d="m14 5 5 5M4 20l3.5-.7L19 7.8a2.1 2.1 0 0 0-3-3L4.7 16.2 4 20Z" />
                                        </svg>
                                    </a>
                                    <button type="button" wire:click="deleteCoupon({{ $coupon->id }})"
                                        wire:confirm="Delete &quot;{{ $coupon->code }}&quot;?"
                                        class="btn btn-ghost btn-square btn-sm rounded-lg border border-transparent text-error hover:border-error/20 hover:bg-error/10"
                                        aria-label="Delete {{ $coupon->code }}">
                                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8">
                                            <path d="M4 7h16m-10 4v5m4-5v5M9 7V4h6v3m-9 0 1 13h10l1-13" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <x-admin.empty-state title="No coupons found"
                                    description="Try another lifecycle view or adjust the current filters."
                                    :action-href="route('admin.coupons.create')" action-label="Create coupon" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-slot:table>

        <x-slot:mobile>
            <div class="divide-y divide-[var(--admin-border)]">
                @forelse ($coupons as $coupon)
                    @php
                        $lifecycle = ! $coupon->is_active
                            ? 'inactive'
                            : ($coupon->start_date->isFuture()
                                ? 'scheduled'
                                : ($coupon->isValidNow() ? 'active' : 'expired'));
                        $tone = ['active' => 'green', 'scheduled' => 'blue', 'expired' => 'amber', 'inactive' => 'gray'][$lifecycle];
                    @endphp
                    <article wire:key="coupon-card-{{ $coupon->id }}" class="bg-[var(--admin-surface)] p-4">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" value="{{ $coupon->id }}" wire:model.live="selected"
                                class="checkbox checkbox-sm mt-0.5 rounded border-base-content/25 checked:border-accent checked:bg-accent checked:text-accent-content"
                                aria-label="Select coupon {{ $coupon->code }}">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-3">
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="min-w-0">
                                        <strong class="block truncate font-mono text-sm tracking-wide">{{ $coupon->code }}</strong>
                                        <span class="mt-1 block truncate text-[11px] text-base-content/45">{{ $coupon->description ?: 'Promotion code' }}</span>
                                    </a>
                                    <x-admin.badge :tone="$tone">{{ str($lifecycle)->title() }}</x-admin.badge>
                                </div>

                                <div class="mt-3 grid grid-cols-3 gap-3 rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] p-3 shadow-inner">
                                    <div>
                                        <span class="block text-[9px] uppercase tracking-wide text-base-content/35">Discount</span>
                                        <strong class="mt-1 block text-xs">
                                            @if ($coupon->discount_type === 'percentage')
                                                {{ rtrim(rtrim($coupon->discount_value, '0'), '.') }}%
                                            @else
                                                ${{ number_format($coupon->discount_value, 2) }}
                                            @endif
                                        </strong>
                                    </div>
                                    <div>
                                        <span class="block text-[9px] uppercase tracking-wide text-base-content/35">Usage</span>
                                        <strong class="mt-1 block text-xs">{{ $coupon->used_count }} / {{ $coupon->usage_limit ?: '∞' }}</strong>
                                    </div>
                                    <div>
                                        <span class="block text-[9px] uppercase tracking-wide text-base-content/35">Ends</span>
                                        <strong class="mt-1 block text-xs">{{ $coupon->end_date->format('M j') }}</strong>
                                    </div>
                                </div>

                                <div class="mt-3 flex justify-end gap-1">
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}"
                                        class="btn btn-ghost btn-xs rounded-lg">Edit</a>
                                    <button type="button" wire:click="deleteCoupon({{ $coupon->id }})"
                                        wire:confirm="Delete &quot;{{ $coupon->code }}&quot;?"
                                        class="btn btn-ghost btn-xs rounded-lg text-error">Delete</button>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <x-admin.empty-state title="No coupons found"
                        description="Try another lifecycle view or adjust the current filters."
                        :action-href="route('admin.coupons.create')" action-label="Create coupon" />
                @endforelse
            </div>
        </x-slot:mobile>

        @if ($coupons->hasPages())
            <x-slot:footer>
                <x-admin.pagination :paginator="$coupons" />
            </x-slot:footer>
        @endif
    </x-admin.resource-panel>

    <x-admin.filter-drawer title="Coupon filters" description="Refine coupons by discount structure or ordering."
        :count="$drawerFilterCount">
        <x-admin.filter-section title="Discount type">
            <div class="grid grid-cols-2 gap-2">
                @foreach (['all' => 'All types', 'fixed' => 'Fixed amount', 'percentage' => 'Percentage'] as $value => $label)
                    <button type="button" wire:click="$set('discountType', '{{ $value }}')"
                        class="btn btn-sm h-10 min-h-10 justify-start rounded-xl border text-[11px]
                            {{ $discountType === $value ? 'border-accent/30 bg-accent/15 text-base-content shadow-admin-control' : 'border-[var(--admin-border)] bg-[var(--admin-surface-raised)] text-base-content/55 shadow-admin-control hover:border-[var(--admin-border-strong)] hover:bg-[var(--admin-surface-soft)]' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </x-admin.filter-section>

        <x-admin.filter-section title="Sort order">
            <div class="space-y-1">
                @foreach ($couponSortLabels as $value => $label)
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
                class="btn btn-primary btn-sm h-9 min-h-9 rounded-lg px-5 shadow-[0_8px_20px_rgba(255,122,0,.18)]">Show coupons</button>
        </x-slot:actions>
    </x-admin.filter-drawer>
</div>
