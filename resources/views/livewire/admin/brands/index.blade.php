@php
    $drawerFilterCount = collect([$tier !== 'all', $sort !== 'newest'])->filter()->count();
    $pageIds = $brands->getCollection()->pluck('id')->map(fn ($id) => (int) $id)->values()->all();
    $allPageSelected = $pageIds !== [] && count(array_intersect($pageIds, array_map('intval', $selected))) === count($pageIds);
    $brandSortLabels = [
        'newest' => 'Newest first',
        'oldest' => 'Oldest first',
        'name_asc' => 'Name A-Z',
        'name_desc' => 'Name Z-A',
        'products_desc' => 'Most products',
    ];
@endphp

<div class="mx-auto w-full max-w-[1600px] space-y-5"
    x-data="{ filterDrawerOpen: false }"
    @keydown.escape.window="filterDrawerOpen = false">
    <x-admin.page-header title="Brands" :count="$summary['all']"
        :action-href="route('admin.brands.create')" action-label="Add brand" />

    <x-admin.resource-panel loading-target="search,status,tier,sort,resetFilters,clearAll,deleteBrand">
        <x-slot:navigation>
            @foreach ([
                'all' => ['All', $summary['all']],
                'active' => ['Active', $summary['active']],
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
                class="input flex h-10 w-full max-w-md items-center gap-2 rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] px-3 shadow-admin-control transition focus-within:border-accent/60 focus-within:outline-none">
                <svg class="size-3.5 shrink-0 text-base-content/35" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <circle cx="11" cy="11" r="7" />
                    <path stroke-linecap="round" d="m20 20-4-4" />
                </svg>
                <input type="search" wire:model.live.debounce.350ms="search"
                    class="grow border-0 bg-transparent p-0 text-xs shadow-none outline-none focus:border-0 focus:ring-0"
                    placeholder="Search brands">
                <span wire:loading wire:target="search"
                    class="loading loading-spinner loading-xs shrink-0 text-accent"></span>
            </label>

            <div class="flex flex-1 items-center gap-2 lg:justify-end">
                <button type="button" @click="filterDrawerOpen = true"
                    :aria-expanded="filterDrawerOpen.toString()"
                    class="btn btn-sm h-10 min-h-10 gap-2 rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-3 text-[11px] font-medium shadow-admin-control hover:border-[var(--admin-border-strong)] hover:bg-[var(--admin-surface-soft)]">
                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" d="M4 7h16M7 12h10m-7 5h4" />
                    </svg>
                    Filter
                    @if ($drawerFilterCount > 0)
                        <span class="grid size-4 place-items-center rounded bg-accent text-[8px] font-bold text-accent-content">
                            {{ $drawerFilterCount }}
                        </span>
                    @endif
                </button>
                <span class="ml-auto whitespace-nowrap text-[10px] tabular-nums text-base-content/40 lg:ml-2">
                    {{ $brands->total() }} {{ Str::plural('brand', $brands->total()) }}
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
                    @if ($tier !== 'all')
                        <x-admin.filter-chip wire:click="$set('tier', 'all')"
                            :label="str(str_replace('_', ' ', $tier))->title()" />
                    @endif
                    @if ($sort !== 'newest')
                        <x-admin.filter-chip wire:click="$set('sort', 'newest')"
                            :label="$brandSortLabels[$sort] ?? 'Custom order'" />
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
                        Mark active
                    </button>
                    <button type="button" wire:click="bulkSetActive(false)" wire:loading.attr="disabled"
                        class="btn btn-ghost btn-xs h-7 min-h-7 rounded-md px-2.5 text-[10px] text-base-content hover:bg-base-content/10">
                        Mark inactive
                    </button>
                    <button type="button" wire:click="bulkDelete"
                        wire:confirm="Delete the selected brands that have no assigned products?"
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
            <table class="w-full min-w-[880px] table-fixed text-left">
                <thead class="border-b border-[var(--admin-border)] bg-[var(--admin-surface-sunken)]">
                    <tr class="text-[9px] font-semibold uppercase tracking-[0.11em] text-base-content/40">
                        <th class="w-10 px-4 py-2.5">
                            <input type="checkbox"
                                wire:click="togglePageSelection(@js($pageIds))"
                                @checked($allPageSelected)
                                class="checkbox checkbox-xs rounded border-base-content/25 checked:border-accent checked:bg-accent checked:text-accent-content"
                                aria-label="Select all brands on this page">
                        </th>
                        <th class="w-[27%] px-3 py-2.5">Brand</th>
                        <th class="w-[19%] px-3 py-2.5">Slug</th>
                        <th class="w-[15%] px-3 py-2.5">Tier</th>
                        <th class="w-[13%] px-3 py-2.5">Products</th>
                        <th class="w-[14%] px-3 py-2.5">Status</th>
                        <th class="w-[7%] px-4 py-2.5"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($brands as $brand)
                        <tr wire:key="brand-row-{{ $brand->id }}"
                            class="group border-b border-[var(--admin-border)] transition-colors last:border-b-0 hover:bg-[var(--admin-surface-soft)]">
                            <td class="px-4 py-3">
                                <input type="checkbox" wire:model.live="selected" value="{{ $brand->id }}"
                                    class="checkbox checkbox-xs rounded border-base-content/25 checked:border-accent checked:bg-accent checked:text-accent-content"
                                    aria-label="Select {{ $brand->name }}">
                            </td>
                            <td class="px-3 py-3">
                                <div class="flex min-w-0 items-center gap-2.5">
                                    @if ($brand->logo)
                                        <img src="{{ Storage::url($brand->logo) }}" alt="{{ $brand->name }} logo"
                                            class="size-9 shrink-0 rounded-xl border border-[var(--admin-border)] bg-white object-contain p-1 shadow-inner">
                                    @else
                                        <span class="grid size-9 shrink-0 place-items-center rounded-xl bg-accent text-[10px] font-bold text-accent-content shadow-[0_7px_16px_rgba(255,122,0,.18)]">
                                            {{ str($brand->name)->substr(0, 1)->upper() }}
                                        </span>
                                    @endif
                                    <a href="{{ route('admin.brands.edit', $brand) }}"
                                        class="truncate text-xs font-semibold text-base-content hover:text-base-content/70">
                                        {{ $brand->name }}
                                    </a>
                                </div>
                            </td>
                            <td class="truncate px-3 py-3 font-mono text-[10px] text-base-content/40">{{ $brand->slug }}</td>
                            <td class="px-3 py-3">
                                <span class="inline-flex h-6 items-center rounded-lg border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] px-2 text-[9px] font-medium capitalize text-base-content/60">
                                    {{ str_replace('_', ' ', $brand->tier) }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-xs font-medium tabular-nums text-base-content/65">
                                {{ $brand->products_count }}
                            </td>
                            <td class="px-3 py-3">
                                <x-admin.badge :tone="$brand->is_active ? 'green' : 'gray'">
                                    {{ $brand->is_active ? 'Active' : 'Inactive' }}
                                </x-admin.badge>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-0.5 opacity-60 group-hover:opacity-100 group-focus-within:opacity-100">
                                    <a href="{{ route('admin.brands.edit', $brand) }}"
                                        class="btn btn-ghost btn-square btn-xs size-8 min-h-8 rounded-lg border border-transparent text-base-content/55 hover:border-[var(--admin-border)] hover:bg-[var(--admin-surface-raised)]"
                                        aria-label="Edit {{ $brand->name }}" title="Edit">
                                        <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14 5 5 5M4 20l3.5-.7L19 7.8a2.1 2.1 0 0 0-3-3L4.7 16.2 4 20Z" /></svg>
                                    </a>
                                    <button type="button" @disabled($brand->products_count > 0)
                                        wire:click="deleteBrand({{ $brand->id }})"
                                        wire:confirm="Delete &quot;{{ $brand->name }}&quot;? This cannot be undone."
                                        wire:loading.attr="disabled"
                                        wire:target="deleteBrand({{ $brand->id }})"
                                        class="btn btn-ghost btn-square btn-xs size-8 min-h-8 rounded-lg border border-transparent text-error hover:border-error/20 hover:bg-error/10 disabled:text-base-content/20"
                                        aria-label="Delete {{ $brand->name }}"
                                        title="{{ $brand->products_count > 0 ? 'Reassign products before deleting' : 'Delete' }}">
                                        <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4 7h16m-10 4v5m4-5v5M9 7V4h6v3m-9 0 1 13h10l1-13" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-admin.empty-state
                                    :title="$hasFilters ? 'No matching brands' : 'No brands yet'"
                                    :description="$hasFilters ? 'Try adjusting or clearing the current view.' : 'Add the first watch brand to the catalog.'"
                                    :action-href="$hasFilters ? null : route('admin.brands.create')"
                                    :action-label="$hasFilters ? null : 'Add brand'">
                                    <x-slot:icon>
                                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8"><path d="M12 3.5 20 8v8l-8 4.5L4 16V8l8-4.5Z" /></svg>
                                    </x-slot:icon>
                                    @if ($hasFilters)
                                        <x-slot:action>
                                            <button type="button" wire:click="clearAll"
                                                class="btn btn-ghost btn-sm mt-4 h-8 min-h-8 rounded-lg text-xs">Clear filters</button>
                                        </x-slot:action>
                                    @endif
                                </x-admin.empty-state>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-slot:table>

        <x-slot:mobile>
            <div class="divide-y divide-[var(--admin-border)]">
                @forelse ($brands as $brand)
                    <article wire:key="brand-card-{{ $brand->id }}" class="bg-[var(--admin-surface)] p-4">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" wire:model.live="selected" value="{{ $brand->id }}"
                                class="checkbox checkbox-xs mt-2 rounded border-base-content/25 checked:border-accent checked:bg-accent checked:text-accent-content"
                                aria-label="Select {{ $brand->name }}">
                            @if ($brand->logo)
                                <img src="{{ Storage::url($brand->logo) }}" alt="{{ $brand->name }} logo"
                                    class="size-10 shrink-0 rounded-xl border border-[var(--admin-border)] bg-white object-contain p-1 shadow-inner">
                            @else
                                <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-accent text-[10px] font-bold text-accent-content shadow-[0_7px_16px_rgba(255,122,0,.18)]">
                                    {{ str($brand->name)->substr(0, 1)->upper() }}
                                </span>
                            @endif
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.brands.edit', $brand) }}"
                                            class="block truncate text-xs font-semibold">{{ $brand->name }}</a>
                                        <p class="mt-1 text-[9px] capitalize text-base-content/35">
                                            {{ str_replace('_', ' ', $brand->tier) }} · {{ $brand->products_count }} {{ Str::plural('product', $brand->products_count) }}
                                        </p>
                                    </div>
                                    <x-admin.badge :tone="$brand->is_active ? 'green' : 'gray'">
                                        {{ $brand->is_active ? 'Active' : 'Inactive' }}
                                    </x-admin.badge>
                                </div>
                                <div class="mt-3 flex justify-end gap-1">
                                    <a href="{{ route('admin.brands.edit', $brand) }}"
                                        class="btn btn-ghost btn-xs h-7 min-h-7 rounded-md px-2 text-[10px]">Edit</a>
                                    <button type="button" @disabled($brand->products_count > 0)
                                        wire:click="deleteBrand({{ $brand->id }})"
                                        wire:confirm="Delete &quot;{{ $brand->name }}&quot;? This cannot be undone."
                                        class="btn btn-ghost btn-xs h-7 min-h-7 rounded-md px-2 text-[10px] text-error disabled:text-base-content/20">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <x-admin.empty-state
                        :title="$hasFilters ? 'No matching brands' : 'No brands yet'"
                        :description="$hasFilters ? 'Try adjusting or clearing the current view.' : 'Add the first watch brand to the catalog.'">
                        <x-slot:icon>
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8"><path d="M12 3.5 20 8v8l-8 4.5L4 16V8l8-4.5Z" /></svg>
                        </x-slot:icon>
                    </x-admin.empty-state>
                @endforelse
            </div>
        </x-slot:mobile>

        <x-slot:footer>
            <x-admin.pagination :paginator="$brands" noun="brand" />
        </x-slot:footer>
    </x-admin.resource-panel>

    <x-admin.filter-drawer title="Brand filters"
        description="Refine brand status, market tier, and ordering."
        :count="$drawerFilterCount">
        <x-admin.filter-section title="Tier" meta="Positioning">
            <div class="flex flex-wrap gap-1.5">
                @foreach ([
                    'all' => 'All tiers',
                    'luxury' => 'Luxury',
                    'premium' => 'Premium',
                    'everyday' => 'Everyday',
                    'smart_sport' => 'Smart & sport',
                ] as $value => $label)
                    <button type="button" wire:click="$set('tier', '{{ $value }}')"
                        class="h-9 rounded-xl border px-3 text-[10px] font-medium transition {{ $tier === $value ? 'border-accent/30 bg-accent/15 text-base-content shadow-admin-control' : 'border-[var(--admin-border)] bg-[var(--admin-surface-raised)] text-base-content/55 hover:border-[var(--admin-border-strong)] hover:text-base-content' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </x-admin.filter-section>

        <x-admin.filter-section title="Sort by" meta="Order">
            <div class="space-y-1">
                @foreach ($brandSortLabels as $value => $label)
                    <button type="button" wire:click="$set('sort', '{{ $value }}')"
                        class="flex min-h-10 w-full items-center justify-between rounded-xl border px-3 text-left text-[11px] transition {{ $sort === $value ? 'border-accent/20 bg-accent/10 font-semibold text-base-content shadow-admin-control' : 'border-transparent text-base-content/55 hover:border-[var(--admin-border)] hover:bg-[var(--admin-surface-raised)] hover:text-base-content' }}">
                        <span>{{ $label }}</span>
                        @if ($sort === $value)<span class="size-1.5 rounded-full bg-accent"></span>@endif
                    </button>
                @endforeach
            </div>
        </x-admin.filter-section>

        <x-slot:actions>
            <button type="button" wire:click="resetFilters"
                class="btn btn-ghost btn-sm h-9 min-h-9 rounded-lg px-3 text-xs"
                @disabled($drawerFilterCount === 0 && $sort === 'newest')>Reset</button>
            <button type="button" @click="filterDrawerOpen = false"
                class="btn btn-primary btn-sm h-9 min-h-9 rounded-lg px-4 text-xs shadow-[0_8px_20px_rgba(255,122,0,.18)]">Show {{ $brands->total() }}</button>
        </x-slot:actions>
    </x-admin.filter-drawer>
</div>
