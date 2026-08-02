@php
    $drawerFilterCount = collect([$brand, $category, $watchType, $featured])
        ->filter(fn ($value) => $value !== 'all')
        ->count() + ($sort !== 'newest' ? 1 : 0);
    $pageIds = $products->getCollection()->pluck('id')->map(fn ($id) => (int) $id)->values()->all();
    $allPageSelected = $pageIds !== [] && count(array_intersect($pageIds, array_map('intval', $selected))) === count($pageIds);
    $productSortLabels = [
        'newest' => 'Newest first',
        'oldest' => 'Oldest first',
        'name_asc' => 'Name A-Z',
        'name_desc' => 'Name Z-A',
        'stock_desc' => 'Highest stock',
        'stock_asc' => 'Lowest stock',
    ];
@endphp

<div class="mx-auto w-full max-w-[1600px] space-y-5"
    x-data="{ filterDrawerOpen: false }"
    @keydown.escape.window="filterDrawerOpen = false">
    <x-admin.page-header title="Products" :count="$summary['all']"
        :action-href="route('admin.products.create')" action-label="Add product" />

    <x-admin.resource-panel
        loading-target="search,status,brand,category,watchType,featured,sort,resetFilters,clearAll,deleteProduct">
        <x-slot:navigation>
            @foreach ([
                'all' => ['All products', $summary['all']],
                'active' => ['Active', $summary['active']],
                'draft' => ['Draft', $summary['draft']],
                'incomplete' => ['Incomplete', $summary['incomplete']],
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
                    placeholder="Search products or SKU">
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
                    {{ $products->total() }} {{ Str::plural('product', $products->total()) }}
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
                    @if ($brand !== 'all')
                        <x-admin.filter-chip wire:click="$set('brand', 'all')"
                            :label="'Brand: '.($brands->firstWhere('id', (int) $brand)?->name ?? 'Selected')" />
                    @endif
                    @if ($category !== 'all')
                        <x-admin.filter-chip wire:click="$set('category', 'all')"
                            :label="'Category: '.($categories->firstWhere('id', (int) $category)?->name ?? 'Selected')" />
                    @endif
                    @if ($watchType !== 'all')
                        <x-admin.filter-chip wire:click="$set('watchType', 'all')" :label="str($watchType)->title()" />
                    @endif
                    @if ($featured !== 'all')
                        <x-admin.filter-chip wire:click="$set('featured', 'all')"
                            :label="$featured === 'yes' ? 'Featured' : 'Standard'" />
                    @endif
                    @if ($sort !== 'newest')
                        <x-admin.filter-chip wire:click="$set('sort', 'newest')"
                            :label="$productSortLabels[$sort] ?? 'Custom order'" />
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
                        Set draft
                    </button>
                    <button type="button" wire:click="bulkSetFeatured(true)" wire:loading.attr="disabled"
                        class="btn btn-ghost btn-xs h-7 min-h-7 rounded-md px-2.5 text-[10px] text-base-content hover:bg-base-content/10">
                        Feature
                    </button>
                    <button type="button" wire:click="bulkSetFeatured(false)" wire:loading.attr="disabled"
                        class="btn btn-ghost btn-xs h-7 min-h-7 rounded-md px-2.5 text-[10px] text-base-content hover:bg-base-content/10">
                        Unfeature
                    </button>
                    <button type="button" wire:click="bulkDelete"
                        wire:confirm="Delete the selected products and all of their variants? This cannot be undone."
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
            <table class="w-full min-w-[900px] table-fixed text-left">
                <thead class="border-b border-[var(--admin-border)] bg-[var(--admin-surface-sunken)]">
                    <tr class="text-[9px] font-semibold uppercase tracking-[0.11em] text-base-content/40">
                        <th class="w-10 px-4 py-2.5">
                            <input type="checkbox"
                                wire:click="togglePageSelection(@js($pageIds))"
                                @checked($allPageSelected)
                                class="checkbox checkbox-xs rounded border-base-content/25 checked:border-accent checked:bg-accent checked:text-accent-content"
                                aria-label="Select all products on this page">
                        </th>
                        <th class="w-[25%] px-3 py-2.5">Product</th>
                        <th class="w-[13%] px-3 py-2.5">Brand</th>
                        <th class="w-[17%] px-3 py-2.5">Category</th>
                        <th class="w-[12%] px-3 py-2.5">Price</th>
                        <th class="w-[12%] px-3 py-2.5">Inventory</th>
                        <th class="w-[10%] px-3 py-2.5">Status</th>
                        <th class="w-[6%] px-4 py-2.5"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        @php
                            $primaryImageUrl = $product->primary_image_url;
                            $defaultVariant = $product->defaultVariant();
                            $hasActiveDefault = $product->variants->contains(
                                fn ($variant) => $variant->is_active && $variant->is_default
                            );
                            $totalStock = (int) ($product->variants_sum_stock_quantity ?? 0);
                        @endphp
                        <tr wire:key="product-row-{{ $product->id }}"
                            class="group border-b border-[var(--admin-border)] transition-colors last:border-b-0 hover:bg-[var(--admin-surface-soft)]">
                            <td class="px-4 py-3">
                                <input type="checkbox" wire:model.live="selected" value="{{ $product->id }}"
                                    class="checkbox checkbox-xs rounded border-base-content/25 checked:border-accent checked:bg-accent checked:text-accent-content"
                                    aria-label="Select {{ $product->name }}">
                            </td>
                            <td class="px-3 py-3">
                                <div class="flex min-w-0 items-center gap-2.5">
                                    @if ($primaryImageUrl)
                                        <img src="{{ $primaryImageUrl }}" alt="{{ $product->name }}"
                                            class="size-10 shrink-0 rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] object-cover shadow-inner"
                                            loading="lazy" decoding="async">
                                    @else
                                        <span class="grid size-10 shrink-0 place-items-center rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] text-base-content/40 shadow-inner">
                                            <svg class="size-4" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 2h6l1 4a6 6 0 0 1 0 12l-1 4H9l-1-4A6 6 0 0 1 8 6l1-4Z" />
                                                <circle cx="12" cy="12" r="3.5" />
                                            </svg>
                                        </span>
                                    @endif
                                    <div class="min-w-0">
                                        <div class="flex min-w-0 items-center gap-1.5">
                                            <a href="{{ route('admin.products.edit', $product) }}"
                                                class="truncate text-xs font-semibold text-base-content hover:text-base-content/70">
                                                {{ $product->name }}
                                            </a>
                                            @if ($product->is_featured)
                                                <span class="inline-flex h-5 shrink-0 items-center rounded-md border border-accent/20 bg-accent/15 px-1.5 text-[8px] font-semibold text-base-content">
                                                    Featured
                                                </span>
                                            @endif
                                        </div>
                                        <p class="mt-0.5 truncate font-mono text-[9px] text-base-content/35">
                                            {{ $defaultVariant?->sku ?? 'No default SKU' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="truncate px-3 py-3 text-[11px] text-base-content/60">{{ $product->brand->name }}</td>
                            <td class="truncate px-3 py-3 text-[11px] text-base-content/60">{{ $product->category->name }}</td>
                            <td class="px-3 py-3 text-xs font-semibold tabular-nums">
                                {{ $defaultVariant ? '$'.number_format((float) $defaultVariant->price, 2) : '—' }}
                            </td>
                            <td class="px-3 py-3">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs font-semibold tabular-nums {{ $totalStock === 0 ? 'text-error' : ($totalStock <= $lowStockThreshold ? 'text-warning' : 'text-base-content') }}">
                                        {{ $totalStock }}
                                    </span>
                                    <span class="text-[9px] text-base-content/35">units</span>
                                </div>
                                <p class="mt-0.5 text-[9px] text-base-content/35">
                                    {{ $product->variants_count }} {{ Str::plural('variant', $product->variants_count) }}
                                </p>
                            </td>
                            <td class="px-3 py-3">
                                @if (! $hasActiveDefault)
                                    <x-admin.badge tone="amber">Incomplete</x-admin.badge>
                                @elseif ($product->is_active)
                                    <x-admin.badge tone="green">Active</x-admin.badge>
                                @else
                                    <x-admin.badge tone="gray">Draft</x-admin.badge>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-0.5 opacity-60 group-hover:opacity-100 group-focus-within:opacity-100">
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                        class="btn btn-ghost btn-square btn-xs size-8 min-h-8 rounded-lg border border-transparent text-base-content/55 hover:border-[var(--admin-border)] hover:bg-[var(--admin-surface-raised)]"
                                        aria-label="Edit {{ $product->name }}" title="Edit">
                                        <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14 5 5 5M4 20l3.5-.7L19 7.8a2.1 2.1 0 0 0-3-3L4.7 16.2 4 20Z" /></svg>
                                    </a>
                                    <button type="button" wire:click="deleteProduct({{ $product->id }})"
                                        wire:confirm="Delete &quot;{{ $product->name }}&quot; and all variants? This cannot be undone."
                                        wire:loading.attr="disabled"
                                        wire:target="deleteProduct({{ $product->id }})"
                                        class="btn btn-ghost btn-square btn-xs size-8 min-h-8 rounded-lg border border-transparent text-error hover:border-error/20 hover:bg-error/10"
                                        aria-label="Delete {{ $product->name }}" title="Delete">
                                        <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4 7h16m-10 4v5m4-5v5M9 7V4h6v3m-9 0 1 13h10l1-13" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <x-admin.empty-state
                                    :title="$hasFilters ? 'No matching products' : 'No products yet'"
                                    :description="$hasFilters ? 'Try adjusting or clearing the current view.' : 'Add the first watch to the catalog.'"
                                    :action-href="$hasFilters ? null : route('admin.products.create')"
                                    :action-label="$hasFilters ? null : 'Add product'">
                                    <x-slot:icon>
                                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8"><path d="m12 3 8 4.5-8 4.5-8-4.5L12 3Zm-8 9 8 4.5 8-4.5" /></svg>
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
                @forelse ($products as $product)
                    @php
                        $primaryImageUrl = $product->primary_image_url;
                        $defaultVariant = $product->defaultVariant();
                        $hasActiveDefault = $product->variants->contains(
                            fn ($variant) => $variant->is_active && $variant->is_default
                        );
                        $totalStock = (int) ($product->variants_sum_stock_quantity ?? 0);
                    @endphp
                    <article wire:key="product-card-{{ $product->id }}" class="bg-[var(--admin-surface)] p-4">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" wire:model.live="selected" value="{{ $product->id }}"
                                class="checkbox checkbox-xs mt-3 rounded border-base-content/25 checked:border-accent checked:bg-accent checked:text-accent-content"
                                aria-label="Select {{ $product->name }}">
                            @if ($primaryImageUrl)
                                <img src="{{ $primaryImageUrl }}" alt="{{ $product->name }}"
                                    class="size-12 shrink-0 rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] object-cover shadow-inner"
                                    loading="lazy" decoding="async">
                            @else
                                <span class="grid size-12 shrink-0 place-items-center rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] text-base-content/40 shadow-inner">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.7"><path d="M9 2h6l1 4a6 6 0 0 1 0 12l-1 4H9l-1-4A6 6 0 0 1 8 6l1-4Z" /><circle cx="12" cy="12" r="3.5" /></svg>
                                </span>
                            @endif
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                            class="block truncate text-xs font-semibold">{{ $product->name }}</a>
                                        <p class="mt-1 truncate text-[9px] text-base-content/35">
                                            {{ $product->brand->name }} · {{ $product->category->name }}
                                        </p>
                                    </div>
                                    @if (! $hasActiveDefault)
                                        <x-admin.badge tone="amber">Incomplete</x-admin.badge>
                                    @elseif ($product->is_active)
                                        <x-admin.badge tone="green">Active</x-admin.badge>
                                    @else
                                        <x-admin.badge tone="gray">Draft</x-admin.badge>
                                    @endif
                                </div>
                                <div class="mt-3 grid grid-cols-3 gap-3">
                                    <div>
                                        <span class="block text-[8px] uppercase tracking-wider text-base-content/30">Price</span>
                                        <strong class="mt-0.5 block text-[10px]">
                                            {{ $defaultVariant ? '$'.number_format((float) $defaultVariant->price, 2) : '—' }}
                                        </strong>
                                    </div>
                                    <div>
                                        <span class="block text-[8px] uppercase tracking-wider text-base-content/30">Stock</span>
                                        <strong class="mt-0.5 block text-[10px]">{{ $totalStock }}</strong>
                                    </div>
                                    <div>
                                        <span class="block text-[8px] uppercase tracking-wider text-base-content/30">Variants</span>
                                        <strong class="mt-0.5 block text-[10px]">{{ $product->variants_count }}</strong>
                                    </div>
                                </div>
                                <div class="mt-3 flex justify-end gap-1">
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                        class="btn btn-ghost btn-xs h-7 min-h-7 rounded-md px-2 text-[10px]">Edit</a>
                                    <button type="button" wire:click="deleteProduct({{ $product->id }})"
                                        wire:confirm="Delete &quot;{{ $product->name }}&quot; and all variants? This cannot be undone."
                                        class="btn btn-ghost btn-xs h-7 min-h-7 rounded-md px-2 text-[10px] text-error">Delete</button>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <x-admin.empty-state
                        :title="$hasFilters ? 'No matching products' : 'No products yet'"
                        :description="$hasFilters ? 'Try adjusting or clearing the current view.' : 'Add the first watch to the catalog.'">
                        <x-slot:icon>
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8"><path d="m12 3 8 4.5-8 4.5-8-4.5L12 3Zm-8 9 8 4.5 8-4.5" /></svg>
                        </x-slot:icon>
                    </x-admin.empty-state>
                @endforelse
            </div>
        </x-slot:mobile>

        <x-slot:footer>
            <x-admin.pagination :paginator="$products" noun="product" />
        </x-slot:footer>
    </x-admin.resource-panel>

    <x-admin.filter-drawer title="Product filters"
        description="Refine lifecycle, placement, attributes, and ordering."
        :count="$drawerFilterCount">
        <x-admin.filter-section title="Catalog placement" meta="Relations">
            <div class="grid gap-3 sm:grid-cols-2">
                <label>
                    <span class="mb-1.5 block text-[9px] font-medium uppercase tracking-wider text-base-content/35">Brand</span>
                    <select wire:model.live="brand"
                        class="select select-bordered h-10 min-h-10 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] text-[11px] shadow-admin-control focus:border-accent">
                        <option value="all">All brands</option>
                        @foreach ($brands as $brandOption)
                            <option value="{{ $brandOption->id }}">{{ $brandOption->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    <span class="mb-1.5 block text-[9px] font-medium uppercase tracking-wider text-base-content/35">Category</span>
                    <select wire:model.live="category"
                        class="select select-bordered h-10 min-h-10 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] text-[11px] shadow-admin-control focus:border-accent">
                        <option value="all">All categories</option>
                        @foreach ($categories as $categoryOption)
                            <option value="{{ $categoryOption->id }}">{{ $categoryOption->name }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
        </x-admin.filter-section>

        <x-admin.filter-section title="Watch type" meta="Attributes">
            <div class="flex flex-wrap gap-1.5">
                @foreach (['all' => 'All', 'traditional' => 'Traditional', 'smart' => 'Smart', 'hybrid' => 'Hybrid'] as $value => $label)
                    <button type="button" wire:click="$set('watchType', '{{ $value }}')"
                        class="h-9 rounded-xl border px-3 text-[10px] font-medium transition {{ $watchType === $value ? 'border-accent/30 bg-accent/15 text-base-content shadow-admin-control' : 'border-[var(--admin-border)] bg-[var(--admin-surface-raised)] text-base-content/55 hover:border-[var(--admin-border-strong)] hover:text-base-content' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
            <p class="mb-1.5 mt-4 text-[9px] font-medium uppercase tracking-wider text-base-content/35">Merchandising</p>
            <div class="flex gap-1.5">
                @foreach (['all' => 'All', 'yes' => 'Featured', 'no' => 'Standard'] as $value => $label)
                    <button type="button" wire:click="$set('featured', '{{ $value }}')"
                        class="h-9 rounded-xl border px-3 text-[10px] font-medium transition {{ $featured === $value ? 'border-accent/30 bg-accent/15 text-base-content shadow-admin-control' : 'border-[var(--admin-border)] bg-[var(--admin-surface-raised)] text-base-content/55 hover:border-[var(--admin-border-strong)] hover:text-base-content' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </x-admin.filter-section>

        <x-admin.filter-section title="Sort by" meta="Order">
            <div class="space-y-1">
                @foreach ($productSortLabels as $value => $label)
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
                class="btn btn-primary btn-sm h-9 min-h-9 rounded-lg px-4 text-xs shadow-[0_8px_20px_rgba(255,122,0,.18)]">Show {{ $products->total() }}</button>
        </x-slot:actions>
    </x-admin.filter-drawer>
</div>
