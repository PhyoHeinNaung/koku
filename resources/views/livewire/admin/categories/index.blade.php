@php
    $drawerFilterCount = collect([$sort !== 'newest'])->filter()->count();
    $pageIds = $categories->getCollection()->pluck('id')->map(fn ($id) => (int) $id)->values()->all();
    $allPageSelected = $pageIds !== [] && count(array_intersect($pageIds, array_map('intval', $selected))) === count($pageIds);
    $categorySortLabels = [
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
    <x-admin.page-header title="Categories" :count="$summary['all']"
        :action-href="route('admin.categories.create')" action-label="Add category" />

    <x-admin.resource-panel
        loading-target="search,status,sort,resetFilters,clearAll,deleteCategory">
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
                    placeholder="Search categories">
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
                    {{ $categories->total() }} {{ Str::plural('category', $categories->total()) }}
                </span>
            </div>
        </x-slot:toolbar>

        @if ($hasFilters)
            <x-slot:chips>
                <div class="flex flex-wrap items-center gap-1.5 border-b border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-4 py-2">
                    <span class="mr-1 text-[8px] font-semibold uppercase tracking-[0.14em] text-base-content/30">
                        Applied
                    </span>
                    @if (filled($search))
                        <x-admin.filter-chip wire:click="$set('search', '')" :label="'Search: '.$search" />
                    @endif
                    @if ($sort !== 'newest')
                        <x-admin.filter-chip wire:click="$set('sort', 'newest')"
                            :label="$categorySortLabels[$sort] ?? 'Custom order'" />
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
                    <button type="button" wire:click="bulkSetActive(true)" wire:loading.attr="disabled"
                        class="btn btn-ghost btn-xs h-7 min-h-7 rounded-md px-2.5 text-[10px] text-base-content hover:bg-base-content/10">
                        Mark active
                    </button>
                    <button type="button" wire:click="bulkSetActive(false)" wire:loading.attr="disabled"
                        class="btn btn-ghost btn-xs h-7 min-h-7 rounded-md px-2.5 text-[10px] text-base-content hover:bg-base-content/10">
                        Mark inactive
                    </button>
                    <button type="button" wire:click="bulkDelete"
                        wire:confirm="Delete the selected categories that have no assigned products?"
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
            <table class="w-full min-w-[840px] table-fixed text-left">
                <thead class="border-b border-[var(--admin-border)] bg-[var(--admin-surface-sunken)]">
                    <tr class="text-[9px] font-semibold uppercase tracking-[0.11em] text-base-content/40">
                        <th class="w-10 px-4 py-2.5">
                            <input type="checkbox"
                                wire:click="togglePageSelection(@js($pageIds))"
                                @checked($allPageSelected)
                                class="checkbox checkbox-xs rounded border-base-content/25 checked:border-accent checked:bg-accent checked:text-accent-content"
                                aria-label="Select all categories on this page">
                        </th>
                        <th class="w-[27%] px-3 py-2.5">Category</th>
                        <th class="w-[20%] px-3 py-2.5">Slug</th>
                        <th class="w-[13%] px-3 py-2.5">Products</th>
                        <th class="w-[13%] px-3 py-2.5">Status</th>
                        <th class="w-[15%] px-3 py-2.5">Updated</th>
                        <th class="w-[7%] px-4 py-2.5"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr wire:key="category-row-{{ $category->id }}"
                            class="group border-b border-[var(--admin-border)] transition-colors last:border-b-0 hover:bg-[var(--admin-surface-soft)]">
                            <td class="px-4 py-3">
                                <input type="checkbox" wire:model.live="selected" value="{{ $category->id }}"
                                    class="checkbox checkbox-xs rounded border-base-content/25 checked:border-accent checked:bg-accent checked:text-accent-content"
                                    aria-label="Select {{ $category->name }}">
                            </td>
                            <td class="px-3 py-3">
                                <div class="flex min-w-0 items-center gap-2.5">
                                    <span
                                        class="grid size-9 shrink-0 place-items-center rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] text-base-content/50 shadow-inner">
                                        <svg class="size-3.5" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                            <path d="M5 4h5v5H5V4Zm9 0h5v5h-5V4ZM5 14h5v5H5v-5Zm9 0h5v5h-5v-5Z" />
                                        </svg>
                                    </span>
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                        class="truncate text-xs font-semibold text-base-content hover:text-base-content/70">
                                        {{ $category->name }}
                                    </a>
                                </div>
                            </td>
                            <td class="truncate px-3 py-3 font-mono text-[10px] text-base-content/40">
                                {{ $category->slug }}
                            </td>
                            <td class="px-3 py-3 text-xs font-medium tabular-nums text-base-content/65">
                                {{ $category->products_count }}
                            </td>
                            <td class="px-3 py-3">
                                <x-admin.badge :tone="$category->is_active ? 'green' : 'gray'">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </x-admin.badge>
                            </td>
                            <td class="px-3 py-3 text-[10px] text-base-content/40">
                                {{ $category->updated_at->format('M j, Y') }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-0.5 opacity-60 group-hover:opacity-100 group-focus-within:opacity-100">
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                        class="btn btn-ghost btn-square btn-xs size-8 min-h-8 rounded-lg border border-transparent text-base-content/55 hover:border-[var(--admin-border)] hover:bg-[var(--admin-surface-raised)]"
                                        aria-label="Edit {{ $category->name }}" title="Edit">
                                        <svg class="size-3.5" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14 5 5 5M4 20l3.5-.7L19 7.8a2.1 2.1 0 0 0-3-3L4.7 16.2 4 20Z" />
                                        </svg>
                                    </a>
                                    <button type="button" @disabled($category->products_count > 0)
                                        wire:click="deleteCategory({{ $category->id }})"
                                        wire:confirm="Delete &quot;{{ $category->name }}&quot;? This cannot be undone."
                                        wire:loading.attr="disabled"
                                        wire:target="deleteCategory({{ $category->id }})"
                                        class="btn btn-ghost btn-square btn-xs size-8 min-h-8 rounded-lg border border-transparent text-error hover:border-error/20 hover:bg-error/10 disabled:text-base-content/20"
                                        aria-label="Delete {{ $category->name }}"
                                        title="{{ $category->products_count > 0 ? 'Reassign products before deleting' : 'Delete' }}">
                                        <svg class="size-3.5" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4 7h16m-10 4v5m4-5v5M9 7V4h6v3m-9 0 1 13h10l1-13" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-admin.empty-state
                                    :title="$hasFilters ? 'No matching categories' : 'No categories yet'"
                                    :description="$hasFilters ? 'Try adjusting or clearing the current view.' : 'Create the first category for the watch catalog.'"
                                    :action-href="$hasFilters ? null : route('admin.categories.create')"
                                    :action-label="$hasFilters ? null : 'Add category'">
                                    <x-slot:icon>
                                        <svg class="size-4" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                            <path d="M5 4h5v5H5V4Zm9 0h5v5h-5V4ZM5 14h5v5H5v-5Zm9 0h5v5h-5v-5Z" />
                                        </svg>
                                    </x-slot:icon>
                                    @if ($hasFilters)
                                        <x-slot:action>
                                            <button type="button" wire:click="clearAll"
                                                class="btn btn-ghost btn-sm mt-4 h-8 min-h-8 rounded-lg text-xs">
                                                Clear filters
                                            </button>
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
                @forelse ($categories as $category)
                    <article wire:key="category-card-{{ $category->id }}" class="bg-[var(--admin-surface)] p-4">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" wire:model.live="selected" value="{{ $category->id }}"
                                class="checkbox checkbox-xs mt-2 rounded border-base-content/25 checked:border-accent checked:bg-accent checked:text-accent-content"
                                aria-label="Select {{ $category->name }}">
                            <span class="grid size-10 shrink-0 place-items-center rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] text-base-content/45">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" aria-hidden="true">
                                    <path d="M5 4h5v5H5V4Zm9 0h5v5h-5V4ZM5 14h5v5H5v-5Zm9 0h5v5h-5v-5Z" />
                                </svg>
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                            class="block truncate text-xs font-semibold">{{ $category->name }}</a>
                                        <p class="mt-1 truncate font-mono text-[9px] text-base-content/35">{{ $category->slug }}</p>
                                    </div>
                                    <x-admin.badge :tone="$category->is_active ? 'green' : 'gray'">
                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                    </x-admin.badge>
                                </div>
                                <div class="mt-3 flex items-center justify-between">
                                    <span class="text-[10px] text-base-content/40">
                                        {{ $category->products_count }} {{ Str::plural('product', $category->products_count) }}
                                    </span>
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                            class="btn btn-ghost btn-xs h-7 min-h-7 rounded-md px-2 text-[10px]">Edit</a>
                                        <button type="button" @disabled($category->products_count > 0)
                                            wire:click="deleteCategory({{ $category->id }})"
                                            wire:confirm="Delete &quot;{{ $category->name }}&quot;? This cannot be undone."
                                            class="btn btn-ghost btn-xs h-7 min-h-7 rounded-md px-2 text-[10px] text-error disabled:text-base-content/20">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <x-admin.empty-state
                        :title="$hasFilters ? 'No matching categories' : 'No categories yet'"
                        :description="$hasFilters ? 'Try adjusting or clearing the current view.' : 'Create the first category for the watch catalog.'">
                        <x-slot:icon>
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8"><path d="M5 4h5v5H5V4Zm9 0h5v5h-5V4ZM5 14h5v5H5v-5Zm9 0h5v5h-5v-5Z" /></svg>
                        </x-slot:icon>
                    </x-admin.empty-state>
                @endforelse
            </div>
        </x-slot:mobile>

        <x-slot:footer>
            <x-admin.pagination :paginator="$categories" noun="category" />
        </x-slot:footer>
    </x-admin.resource-panel>

    <x-admin.filter-drawer title="Category filters"
        description="Refine category availability and ordering."
        :count="$drawerFilterCount">
        <x-admin.filter-section title="Sort by" meta="Order">
            <div class="space-y-1">
                @foreach ($categorySortLabels as $value => $label)
                    <button type="button" wire:click="$set('sort', '{{ $value }}')"
                        class="flex min-h-10 w-full items-center justify-between rounded-xl border px-3 text-left text-[11px] transition {{ $sort === $value ? 'border-accent/20 bg-accent/10 font-semibold text-base-content shadow-admin-control' : 'border-transparent text-base-content/55 hover:border-[var(--admin-border)] hover:bg-[var(--admin-surface-raised)] hover:text-base-content' }}">
                        <span>{{ $label }}</span>
                        @if ($sort === $value)
                            <span class="size-1.5 rounded-full bg-accent"></span>
                        @endif
                    </button>
                @endforeach
            </div>
        </x-admin.filter-section>

        <x-slot:actions>
            <button type="button" wire:click="resetFilters"
                class="btn btn-ghost btn-sm h-9 min-h-9 rounded-lg px-3 text-xs"
                @disabled($drawerFilterCount === 0 && $sort === 'newest')>
                Reset
            </button>
            <button type="button" @click="filterDrawerOpen = false"
                class="btn btn-primary btn-sm h-9 min-h-9 rounded-lg px-4 text-xs shadow-[0_8px_20px_rgba(255,122,0,.18)]">
                Show {{ $categories->total() }}
            </button>
        </x-slot:actions>
    </x-admin.filter-drawer>
</div>
