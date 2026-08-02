@php
    $pageIds = $resources->getCollection()->pluck('id')->map(fn ($id) => (int) $id)->values()->all();
    $allPageSelected = $pageIds !== [] && count(array_intersect($pageIds, array_map('intval', $selected))) === count($pageIds);
    $drawerFilterCount = ($status !== 'all' ? 1 : 0) + ($sort !== 'newest' ? 1 : 0);
    $sortLabels = $tab === 'zones'
        ? [
            'newest' => 'Newest first',
            'oldest' => 'Oldest first',
            'name' => 'Name A–Z',
            'fee_desc' => 'Highest fee',
            'fee_asc' => 'Lowest fee',
        ]
        : [
            'newest' => 'Newest first',
            'oldest' => 'Oldest first',
            'name' => 'Location A–Z',
            'zone' => 'Zone A–Z',
        ];
@endphp

<div class="mx-auto w-full max-w-[1600px] space-y-5"
    x-data="{ filterDrawerOpen: false, shippingEditorOpen: @entangle('editorOpen') }"
    @keydown.escape.window="
        filterDrawerOpen = false;
        if (shippingEditorOpen) {
            shippingEditorOpen = false;
            setTimeout(() => $wire.closeEditor(), 220);
        }
    ">
    <header class="flex flex-col gap-3 rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] p-4 shadow-admin-panel sm:flex-row sm:items-center sm:justify-between sm:px-5">
        <div class="flex min-w-0 items-center gap-2.5">
            <h1 class="truncate text-xl font-semibold tracking-[-0.025em] text-base-content sm:text-[1.35rem]">
                Shipping
            </h1>
            <span
                class="inline-flex h-6 min-w-6 items-center justify-center rounded-lg border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] px-1.5 text-[10px] font-semibold tabular-nums text-base-content/55 shadow-inner">
                {{ $tab === 'zones' ? $zoneCount : $locationCount }}
            </span>
        </div>

        <button type="button" wire:click="openCreate('{{ $tab === 'zones' ? 'zone' : 'location' }}')"
            class="btn btn-primary btn-sm h-10 min-h-10 w-full gap-2 rounded-xl px-4 text-xs font-semibold shadow-[0_10px_24px_rgba(255,122,0,.2)] sm:w-auto">
            <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" d="M12 5v14M5 12h14" />
            </svg>
            Add {{ $tab === 'zones' ? 'zone' : 'location' }}
        </button>
    </header>

    <x-admin.resource-panel loading-target="tab,search,status,sort,clearAll,bulkSetActive,bulkDelete">
        <x-slot:navigation>
            <x-admin.resource-tab wire:click="$set('tab', 'zones')" :active="$tab === 'zones'" :count="$zoneCount">
                Zones
            </x-admin.resource-tab>
            <x-admin.resource-tab wire:click="$set('tab', 'locations')" :active="$tab === 'locations'" :count="$locationCount">
                Locations
            </x-admin.resource-tab>
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
                    placeholder="{{ $tab === 'zones' ? 'Search zones' : 'Search location or zone' }}">
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
                    {{ $resources->total() }} {{ Str::plural($tab === 'zones' ? 'zone' : 'location', $resources->total()) }}
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
                    @if ($status !== 'all')
                        <x-admin.filter-chip wire:click="$set('status', 'all')" :label="str($status)->title()" />
                    @endif
                    @if ($sort !== 'newest')
                        <x-admin.filter-chip wire:click="$set('sort', 'newest')" :label="$sortLabels[$sort] ?? 'Custom sort'" />
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
                    <button type="button" wire:click="bulkSetActive(true)"
                        class="btn btn-ghost btn-xs min-h-7 rounded-md text-[9px] text-base-content hover:bg-base-content/10">
                        Activate
                    </button>
                    <button type="button" wire:click="bulkSetActive(false)"
                        class="btn btn-ghost btn-xs min-h-7 rounded-md text-[9px] text-base-content hover:bg-base-content/10">
                        Deactivate
                    </button>
                    <button type="button" wire:click="bulkDelete"
                        wire:confirm="Delete eligible selected records? Linked records will be kept."
                        class="btn btn-ghost btn-xs min-h-7 rounded-md text-[9px] text-error hover:bg-error/10">
                        Delete
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
                @if ($tab === 'zones')
                    <thead class="border-b border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] text-[9px] uppercase tracking-[0.1em] text-base-content/40">
                        <tr>
                            <th class="w-11 px-4 py-2.5">
                                <input type="checkbox" class="checkbox checkbox-xs rounded"
                                    @checked($allPageSelected)
                                    wire:click="togglePageSelection(@js($pageIds))"
                                    aria-label="Select all zones on this page">
                            </th>
                            <th class="w-[27%] px-3 py-2.5">Zone</th>
                            <th class="w-[14%] px-3 py-2.5">Fee</th>
                            <th class="w-[18%] px-3 py-2.5">Locations</th>
                            <th class="w-[18%] px-3 py-2.5">Delivery</th>
                            <th class="w-[13%] px-3 py-2.5">Status</th>
                            <th class="px-4 py-2.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--admin-border)]">
                        @forelse ($resources as $zone)
                            <tr wire:key="shipping-zone-{{ $zone->id }}"
                                class="group transition-colors hover:bg-[var(--admin-surface-soft)]">
                                <td class="px-4 py-3.5 align-middle">
                                    <input type="checkbox" wire:model.live="selected" value="{{ $zone->id }}"
                                        class="checkbox checkbox-xs rounded" aria-label="Select {{ $zone->name }}">
                                </td>
                                <td class="px-3 py-3.5 align-middle">
                                    <div class="flex min-w-0 items-center gap-3">
                                        <span class="grid size-9 shrink-0 place-items-center rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] text-base-content/55 shadow-inner">
                                            <svg class="size-4" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                                <path d="M4 6.5h16v11H4v-11Zm0 4h16M8 4v5m8-5v5" />
                                            </svg>
                                        </span>
                                        <span class="min-w-0">
                                            <strong class="block truncate text-xs font-semibold">{{ $zone->name }}</strong>
                                            <small class="mt-0.5 block truncate text-[9px] text-base-content/35">
                                                {{ Str::limit($zone->description ?: 'Standard delivery zone', 48) }}
                                            </small>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-3 py-3.5 text-xs font-semibold tabular-nums">${{ number_format($zone->fee, 2) }}</td>
                                <td class="px-3 py-3.5">
                                    <strong class="block text-xs tabular-nums">{{ $zone->locations_count }}</strong>
                                    <small class="mt-0.5 block text-[9px] text-base-content/35">{{ $zone->active_locations_count }} active</small>
                                </td>
                                <td class="px-3 py-3.5 text-[11px] text-base-content/60">
                                    {{ $zone->estimated_days ?: 'Not specified' }}
                                </td>
                                <td class="px-3 py-3.5">
                                    <x-admin.badge :tone="$zone->is_active ? 'green' : 'gray'">
                                        {{ $zone->is_active ? 'Active' : 'Inactive' }}
                                    </x-admin.badge>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="flex justify-end gap-1">
                                        <button type="button" wire:click="editZone({{ $zone->id }})"
                                            class="btn btn-ghost btn-square btn-xs size-8 min-h-8 rounded-lg border border-transparent text-base-content/55 hover:border-[var(--admin-border)] hover:bg-[var(--admin-surface-raised)]"
                                            aria-label="Edit {{ $zone->name }}">
                                            <svg class="size-3.5" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.8">
                                                <path d="m4 16-.7 4.7L8 20l11-11-4-4L4 16Zm9-9 4 4" />
                                            </svg>
                                        </button>
                                        <button type="button" wire:click="deleteZone({{ $zone->id }})"
                                            wire:confirm="Delete this shipping zone?"
                                            class="btn btn-ghost btn-square btn-xs size-8 min-h-8 rounded-lg border border-transparent text-error hover:border-error/20 hover:bg-error/10"
                                            aria-label="Delete {{ $zone->name }}">
                                            <svg class="size-3.5" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.8">
                                                <path d="M5 7h14m-9-3h4l1 3H9l1-3Zm-3 3 1 13h8l1-13M10 11v5m4-5v5" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <x-admin.empty-state title="No shipping zones found"
                                        description="Adjust the current filters or add the first delivery zone." />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                @else
                    <thead class="border-b border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] text-[9px] uppercase tracking-[0.1em] text-base-content/40">
                        <tr>
                            <th class="w-11 px-4 py-2.5">
                                <input type="checkbox" class="checkbox checkbox-xs rounded"
                                    @checked($allPageSelected)
                                    wire:click="togglePageSelection(@js($pageIds))"
                                    aria-label="Select all locations on this page">
                            </th>
                            <th class="w-[23%] px-3 py-2.5">Location</th>
                            <th class="w-[18%] px-3 py-2.5">Region</th>
                            <th class="w-[18%] px-3 py-2.5">Zone</th>
                            <th class="w-[12%] px-3 py-2.5">Fee</th>
                            <th class="w-[11%] px-3 py-2.5">Orders</th>
                            <th class="w-[11%] px-3 py-2.5">Status</th>
                            <th class="px-4 py-2.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--admin-border)]">
                        @forelse ($resources as $location)
                            <tr wire:key="shipping-location-{{ $location->id }}"
                                class="group transition-colors hover:bg-[var(--admin-surface-soft)]">
                                <td class="px-4 py-3.5 align-middle">
                                    <input type="checkbox" wire:model.live="selected" value="{{ $location->id }}"
                                        class="checkbox checkbox-xs rounded" aria-label="Select {{ $location->city }}">
                                </td>
                                <td class="px-3 py-3.5">
                                    <div class="flex min-w-0 items-center gap-3">
                                        <span class="grid size-9 shrink-0 place-items-center rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] text-base-content/55 shadow-inner">
                                            <svg class="size-4" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                                <path d="M12 21s6-5.3 6-11a6 6 0 1 0-12 0c0 5.7 6 11 6 11Z" />
                                                <circle cx="12" cy="10" r="2" />
                                            </svg>
                                        </span>
                                        <span class="min-w-0">
                                            <strong class="block truncate text-xs font-semibold">{{ $location->city }}</strong>
                                            <small class="mt-0.5 block truncate text-[9px] text-base-content/35">
                                                {{ $location->district_area ?: $location->country }}
                                            </small>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-3 py-3.5 text-[11px] text-base-content/60">
                                    {{ $location->state_region ?: '—' }}
                                </td>
                                <td class="px-3 py-3.5">
                                    <strong class="block truncate text-[11px]">{{ $location->zone->name }}</strong>
                                    @unless ($location->zone->is_active)
                                        <small class="mt-0.5 block text-[9px] text-warning">Zone inactive</small>
                                    @endunless
                                </td>
                                <td class="px-3 py-3.5 text-xs font-semibold tabular-nums">${{ number_format($location->zone->fee, 2) }}</td>
                                <td class="px-3 py-3.5 text-xs tabular-nums">{{ $location->orders_count }}</td>
                                <td class="px-3 py-3.5">
                                    <x-admin.badge :tone="$location->is_active ? 'green' : 'gray'">
                                        {{ $location->is_active ? 'Active' : 'Inactive' }}
                                    </x-admin.badge>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="flex justify-end gap-1">
                                        <button type="button" wire:click="editLocation({{ $location->id }})"
                                            class="btn btn-ghost btn-square btn-xs size-8 min-h-8 rounded-lg border border-transparent text-base-content/55 hover:border-[var(--admin-border)] hover:bg-[var(--admin-surface-raised)]"
                                            aria-label="Edit {{ $location->city }}">
                                            <svg class="size-3.5" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.8">
                                                <path d="m4 16-.7 4.7L8 20l11-11-4-4L4 16Zm9-9 4 4" />
                                            </svg>
                                        </button>
                                        <button type="button" wire:click="deleteLocation({{ $location->id }})"
                                            wire:confirm="Delete this shipping location?"
                                            class="btn btn-ghost btn-square btn-xs size-8 min-h-8 rounded-lg border border-transparent text-error hover:border-error/20 hover:bg-error/10"
                                            aria-label="Delete {{ $location->city }}">
                                            <svg class="size-3.5" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.8">
                                                <path d="M5 7h14m-9-3h4l1 3H9l1-3Zm-3 3 1 13h8l1-13M10 11v5m4-5v5" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <x-admin.empty-state title="No shipping locations found"
                                        description="Adjust the current filters or add the first service location." />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                @endif
            </table>
        </x-slot:table>

        <x-slot:mobile>
            <div class="divide-y divide-[var(--admin-border)]">
                @forelse ($resources as $resource)
                    <article wire:key="shipping-mobile-{{ $tab }}-{{ $resource->id }}" class="bg-[var(--admin-surface)] p-4">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" wire:model.live="selected" value="{{ $resource->id }}"
                                class="checkbox checkbox-sm mt-0.5 rounded"
                                aria-label="Select {{ $tab === 'zones' ? $resource->name : $resource->city }}">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-3">
                                    <span class="min-w-0">
                                        <strong class="block truncate text-xs">
                                            {{ $tab === 'zones' ? $resource->name : $resource->city }}
                                        </strong>
                                        <small class="mt-1 block truncate text-[10px] text-base-content/40">
                                            {{ $tab === 'zones'
                                                ? ($resource->estimated_days ?: 'Delivery estimate not set')
                                                : collect([$resource->district_area, $resource->state_region, $resource->country])->filter()->join(', ') }}
                                        </small>
                                    </span>
                                    <x-admin.badge :tone="$resource->is_active ? 'green' : 'gray'">
                                        {{ $resource->is_active ? 'Active' : 'Inactive' }}
                                    </x-admin.badge>
                                </div>

                                <dl class="mt-4 grid grid-cols-3 gap-3 rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] p-3 shadow-inner">
                                    @if ($tab === 'zones')
                                        <div><dt class="text-[8px] uppercase text-base-content/35">Fee</dt><dd class="mt-1 text-[11px] font-semibold">${{ number_format($resource->fee, 2) }}</dd></div>
                                        <div><dt class="text-[8px] uppercase text-base-content/35">Locations</dt><dd class="mt-1 text-[11px] font-semibold">{{ $resource->locations_count }}</dd></div>
                                        <div><dt class="text-[8px] uppercase text-base-content/35">Active</dt><dd class="mt-1 text-[11px] font-semibold">{{ $resource->active_locations_count }}</dd></div>
                                    @else
                                        <div><dt class="text-[8px] uppercase text-base-content/35">Zone</dt><dd class="mt-1 truncate text-[11px] font-semibold">{{ $resource->zone->name }}</dd></div>
                                        <div><dt class="text-[8px] uppercase text-base-content/35">Fee</dt><dd class="mt-1 text-[11px] font-semibold">${{ number_format($resource->zone->fee, 2) }}</dd></div>
                                        <div><dt class="text-[8px] uppercase text-base-content/35">Orders</dt><dd class="mt-1 text-[11px] font-semibold">{{ $resource->orders_count }}</dd></div>
                                    @endif
                                </dl>

                                <div class="mt-3 flex justify-end gap-1">
                                    <button type="button"
                                        wire:click="{{ $tab === 'zones' ? 'editZone' : 'editLocation' }}({{ $resource->id }})"
                                        class="btn btn-ghost btn-xs rounded-md">Edit</button>
                                    <button type="button"
                                        wire:click="{{ $tab === 'zones' ? 'deleteZone' : 'deleteLocation' }}({{ $resource->id }})"
                                        wire:confirm="Delete this shipping record?"
                                        class="btn btn-ghost btn-xs rounded-md text-error">Delete</button>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <x-admin.empty-state
                        :title="$tab === 'zones' ? 'No shipping zones found' : 'No shipping locations found'"
                        description="Adjust the current filters or create the first record." />
                @endforelse
            </div>
        </x-slot:mobile>

        @if ($resources->hasPages())
            <x-slot:footer>
                <x-admin.pagination :paginator="$resources" />
            </x-slot:footer>
        @endif
    </x-admin.resource-panel>

    <x-admin.filter-drawer title="Shipping filters"
        :description="$tab === 'zones' ? 'Refine delivery zones by state or fee.' : 'Refine service locations by state or zone.'"
        :count="$drawerFilterCount">
        <x-admin.filter-section title="Availability">
            <div class="grid grid-cols-3 gap-2">
                @foreach (['all' => 'All', 'active' => 'Active', 'inactive' => 'Inactive'] as $value => $label)
                    <button type="button" wire:click="$set('status', '{{ $value }}')"
                        class="btn btn-sm h-10 min-h-10 rounded-xl border text-[11px]
                            {{ $status === $value ? 'border-accent/30 bg-accent/15 text-base-content shadow-admin-control' : 'border-[var(--admin-border)] bg-[var(--admin-surface-raised)] text-base-content/55 shadow-admin-control hover:border-[var(--admin-border-strong)] hover:bg-[var(--admin-surface-soft)]' }}">
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
                class="btn btn-primary btn-sm h-9 min-h-9 rounded-lg px-5 shadow-[0_8px_20px_rgba(255,122,0,.18)]">Show results</button>
        </x-slot:actions>
    </x-admin.filter-drawer>

    <div class="fixed inset-0 z-[80]"
        :class="shippingEditorOpen ? 'pointer-events-auto' : 'pointer-events-none'"
        :aria-hidden="(!shippingEditorOpen).toString()">
        <button type="button" x-show="shippingEditorOpen" x-cloak
            x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            @click="shippingEditorOpen = false; setTimeout(() => $wire.closeEditor(), 220)"
            class="absolute inset-0 bg-black/65 backdrop-blur-[2px]" aria-label="Close shipping editor"></button>

        <aside x-show="shippingEditorOpen" x-cloak
            x-transition:enter="transform transition duration-300 ease-out"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition duration-220 ease-in"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
            role="dialog" aria-modal="true"
            :aria-label="$wire.editorType === 'zone' ? 'Shipping zone editor' : 'Shipping location editor'"
            class="absolute inset-y-0 right-0 flex w-full max-w-lg flex-col overflow-hidden bg-[var(--admin-surface)] shadow-2xl sm:inset-y-3 sm:right-3 sm:rounded-2xl sm:border sm:border-[var(--admin-border)]">
            <header class="relative border-b border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-5 py-4">
                <span class="absolute inset-x-0 top-0 h-0.5 bg-accent"></span>
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-[0.12em] text-base-content/40">
                            Shipping configuration
                        </p>
                        <h2 class="mt-1 text-base font-semibold">
                            {{ $editingId ? 'Edit' : 'Add' }} {{ $editorType }}
                        </h2>
                    </div>
                    <button type="button"
                        @click="shippingEditorOpen = false; setTimeout(() => $wire.closeEditor(), 220)"
                        class="btn btn-square btn-sm rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] shadow-admin-control hover:bg-[var(--admin-surface-soft)]" aria-label="Close editor">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8"><path d="M6 6l12 12M18 6 6 18" /></svg>
                    </button>
                </div>
            </header>

            <div class="min-h-0 flex-1 overflow-y-auto bg-[var(--admin-canvas)] p-5">
                @if ($editorType === 'zone')
                    <div class="space-y-5">
                        <section class="rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface)] p-5 shadow-admin-control">
                            <div class="space-y-4">
                                <x-admin.form-field label="Zone name" name="name" required>
                                    <input id="name" type="text" wire:model="name"
                                        class="input input-bordered h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                                </x-admin.form-field>

                                <div class="grid gap-4 sm:grid-cols-2">
                                    <x-admin.form-field label="Shipping fee" name="fee" required>
                                        <label class="input input-bordered flex h-11 w-full items-center gap-2 rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus-within:border-accent">
                                            <span class="text-sm text-base-content/35">$</span>
                                            <input id="fee" type="number" min="0" step="0.01" wire:model="fee"
                                                class="min-w-0 grow bg-transparent outline-none">
                                        </label>
                                    </x-admin.form-field>
                                    <x-admin.form-field label="Delivery estimate" name="estimatedDays">
                                        <input id="estimatedDays" type="text" wire:model="estimatedDays"
                                            class="input input-bordered h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none"
                                            placeholder="2–3 business days">
                                    </x-admin.form-field>
                                </div>

                                <x-admin.form-field label="Description" name="description">
                                    <textarea id="description" wire:model="description" rows="5"
                                        class="textarea textarea-bordered w-full resize-y rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] leading-6 shadow-admin-control focus:border-accent focus:outline-none"></textarea>
                                </x-admin.form-field>
                            </div>
                        </section>

                        <x-admin.switch-row label="Active zone"
                            description="Only active zones are available during checkout">
                            <input type="checkbox" wire:model="zoneActive" class="toggle toggle-primary toggle-sm">
                        </x-admin.switch-row>
                    </div>
                @else
                    <div class="space-y-5">
                        <section class="rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface)] p-5 shadow-admin-control">
                            <div class="space-y-4">
                                <x-admin.form-field label="Shipping zone" name="zoneId" required>
                                    <select id="zoneId" wire:model="zoneId"
                                        class="select select-bordered h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                                        <option value="">Select a zone</option>
                                        @foreach ($zones as $zone)
                                            <option value="{{ $zone->id }}">
                                                {{ $zone->name }} · ${{ number_format($zone->fee, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </x-admin.form-field>

                                <div class="grid gap-4 sm:grid-cols-2">
                                    <x-admin.form-field label="Country" name="country" required>
                                        <input id="country" type="text" wire:model="country"
                                            class="input input-bordered h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                                    </x-admin.form-field>
                                    <x-admin.form-field label="State / region" name="stateRegion">
                                        <input id="stateRegion" type="text" wire:model="stateRegion"
                                            class="input input-bordered h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                                    </x-admin.form-field>
                                    <x-admin.form-field label="City" name="city" required>
                                        <input id="city" type="text" wire:model="city"
                                            class="input input-bordered h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                                    </x-admin.form-field>
                                    <x-admin.form-field label="District / area" name="districtArea">
                                        <input id="districtArea" type="text" wire:model="districtArea"
                                            class="input input-bordered h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                                    </x-admin.form-field>
                                </div>
                            </div>
                        </section>

                        <x-admin.switch-row label="Active location"
                            description="Customers can select this location during checkout">
                            <input type="checkbox" wire:model="locationActive" class="toggle toggle-primary toggle-sm">
                        </x-admin.switch-row>
                    </div>
                @endif
            </div>

            <footer class="flex items-center justify-end gap-2 border-t border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-5 py-4">
                <button type="button"
                    @click="shippingEditorOpen = false; setTimeout(() => $wire.closeEditor(), 220)"
                    class="btn btn-ghost btn-sm rounded-lg">Cancel</button>
                <button type="button"
                    wire:click="{{ $editorType === 'zone' ? 'saveZone' : 'saveLocation' }}"
                    wire:loading.attr="disabled"
                    wire:target="{{ $editorType === 'zone' ? 'saveZone' : 'saveLocation' }}"
                    class="btn btn-primary btn-sm h-10 min-h-10 min-w-28 rounded-xl shadow-[0_8px_20px_rgba(255,122,0,.18)]">
                    <span wire:loading
                        wire:target="{{ $editorType === 'zone' ? 'saveZone' : 'saveLocation' }}"
                        class="loading loading-spinner loading-xs"></span>
                    <span wire:loading.remove
                        wire:target="{{ $editorType === 'zone' ? 'saveZone' : 'saveLocation' }}">
                        {{ $editingId ? 'Save changes' : 'Create '.$editorType }}
                    </span>
                </button>
            </footer>
        </aside>
    </div>
</div>
