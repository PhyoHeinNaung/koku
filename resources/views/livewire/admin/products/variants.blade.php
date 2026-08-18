<section class="relative overflow-hidden rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] shadow-admin-panel"
    x-data="{ drawerOpen: @entangle('drawerOpen') }"
    x-effect="$dispatch('admin-drawer-state', { open: drawerOpen })"
    @keydown.escape.window="if (drawerOpen) { drawerOpen = false; setTimeout(() => $wire.cancel(), 260) }">
    <span class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/15 to-transparent"></span>
    <div class="flex flex-col gap-4 border-b border-[var(--admin-border)] bg-[var(--admin-surface-raised)] p-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <div class="flex items-center gap-4">
            <span class="grid size-10 shrink-0 place-items-center rounded-xl border border-accent/20 bg-accent/10 text-accent shadow-admin-control">
                <svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8" aria-hidden="true">
                    <path d="M5 7h14v10H5V7Zm3-3v3m8-3v3M8 17v3m8-3v3" />
                </svg>
            </span>
            <div class="flex items-center gap-2">
                <h2 class="text-sm font-semibold">Product variants</h2>
                <span class="badge badge-ghost badge-sm">{{ $variants->count() }}</span>
            </div>
        </div>

        <button type="button" wire:click="openManager" class="btn btn-primary btn-sm h-10 min-h-10 gap-1.5 rounded-xl px-4 shadow-[0_10px_24px_rgba(255,122,0,.2)]">
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.8" aria-hidden="true">
                <path d="M5 7h14v10H5V7Zm3-3v3m8-3v3M8 17v3m8-3v3" />
            </svg>
            Manage variants
        </button>
    </div>

    <div class="p-3 sm:p-4">
        @if ($variants->isEmpty())
            <div class="grid justify-items-center rounded-xl border border-dashed border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] px-6 py-12 text-center">
                <span class="grid size-11 place-items-center rounded-xl border border-accent/20 bg-accent/10 text-accent shadow-admin-control">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.7" aria-hidden="true">
                        <path d="M5 8h14v9H5V8Zm3-3h8v3H8V5Z" />
                    </svg>
                </span>
                <h3 class="mt-3 text-sm font-semibold">No variants yet</h3>
                <button type="button" wire:click="openManager"
                    class="btn btn-primary btn-sm mt-4 rounded-xl px-4">Create first variant</button>
            </div>
        @else
            <div class="hidden overflow-hidden rounded-xl border border-[var(--admin-border)] md:block">
                <table class="table table-sm w-full">
                    <thead class="bg-[var(--admin-surface-sunken)] text-[9px] uppercase tracking-[0.1em] text-base-content/40">
                        <tr class="border-0">
                            <th class="px-4 py-3 font-medium">Variant</th>
                            <th class="px-4 py-3 font-medium">SKU</th>
                            <th class="px-4 py-3 font-medium">Price</th>
                            <th class="px-4 py-3 font-medium">Inventory</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="w-14 px-3 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--admin-border)]">
                        @foreach ($variants as $variant)
                            <tr wire:key="variant-summary-{{ $variant->id }}"
                                class="border-0 transition-colors [&>td]:border-0 {{ $variant->is_default ? 'bg-accent/[0.045] hover:bg-accent/[0.075]' : 'hover:bg-[var(--admin-surface-soft)]' }}">
                                <td class="px-4 py-3">
                                    <button type="button" wire:click="edit({{ $variant->id }})"
                                        class="flex min-w-0 items-center gap-3 text-left">
                                        @if ($variant->primary_image)
                                            <img src="{{ Storage::url($variant->primary_image->image_url) }}"
                                                alt="{{ $variant->name }}"
                                                class="size-10 shrink-0 rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] object-cover shadow-inner" loading="lazy">
                                        @else
                                            <span
                                                class="grid size-10 shrink-0 place-items-center rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] text-base-content/30 shadow-inner">
                                                <svg class="size-4" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="1.7">
                                                    <path d="M4 5h16v14H4V5Z" />
                                                </svg>
                                            </span>
                                        @endif
                                        <span class="flex min-w-0 items-center gap-2">
                                            <strong class="truncate text-xs font-semibold">{{ $variant->name }}</strong>
                                            @if ($variant->is_default)
                                                <span
                                                    class="badge badge-sm shrink-0 gap-1 border-accent/25 bg-accent/15 px-2 text-[9px] font-semibold text-accent">
                                                    <svg class="size-3" viewBox="0 0 24 24" fill="currentColor"
                                                        aria-hidden="true">
                                                        <path d="m12 3 2.7 5.5 6.1.9-4.4 4.3 1 6.1-5.4-2.9-5.4 2.9 1-6.1-4.4-4.3 6.1-.9L12 3Z" />
                                                    </svg>
                                                    Default
                                                </span>
                                            @endif
                                        </span>
                                    </button>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 font-mono text-[11px] text-base-content/50">
                                    {{ $variant->sku }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-xs font-semibold">
                                    ${{ number_format($variant->price, 2) }}
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="badge badge-sm {{ $variant->stock_quantity === 0 ? 'badge-error badge-soft' : ($variant->stock_quantity <= $lowStockThreshold ? 'badge-warning badge-soft' : 'badge-ghost') }}">
                                        {{ $variant->stock_quantity }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <x-admin.badge :tone="$variant->is_active ? 'green' : 'gray'">
                                        {{ $variant->is_active ? 'Active' : 'Inactive' }}
                                    </x-admin.badge>
                                </td>
                                <td class="px-3 py-3 text-right">
                                    <button type="button" wire:click="edit({{ $variant->id }})"
                                        class="btn btn-ghost btn-square btn-sm rounded-lg border border-transparent text-base-content/55 hover:border-[var(--admin-border)] hover:bg-[var(--admin-surface-raised)]"
                                        aria-label="Edit {{ $variant->name }}">
                                        <svg class="size-4" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.8">
                                            <path d="m14 5 5 5M4 20l3.5-.7L19 7.8a2.1 2.1 0 0 0-3-3L4.7 16.2 4 20Z" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="space-y-2 md:hidden">
                @foreach ($variants as $variant)
                    <button type="button" wire:click="edit({{ $variant->id }})"
                        wire:key="variant-mobile-{{ $variant->id }}"
                        class="flex w-full items-center gap-3 rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] p-3 text-left shadow-admin-control">
                        @if ($variant->primary_image)
                            <img src="{{ Storage::url($variant->primary_image->image_url) }}" alt="{{ $variant->name }}"
                                class="size-11 shrink-0 rounded-lg object-cover">
                        @else
                            <span
                                class="grid size-11 shrink-0 place-items-center rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] text-base-content/30 shadow-admin-control">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M4 5h16v14H4V5Z" />
                                </svg>
                            </span>
                        @endif
                        <span class="min-w-0 flex-1">
                            <span class="flex items-center gap-2">
                                <strong class="truncate text-xs font-semibold">{{ $variant->name }}</strong>
                                @if ($variant->is_default)
                                    <span
                                        class="badge badge-sm shrink-0 gap-1 border-primary/15 bg-primary/10 px-1.5 text-[9px] font-semibold text-primary">
                                        <svg class="size-2.5" viewBox="0 0 24 24" fill="currentColor"
                                            aria-hidden="true">
                                            <path d="m12 3 2.7 5.5 6.1.9-4.4 4.3 1 6.1-5.4-2.9-5.4 2.9 1-6.1-4.4-4.3 6.1-.9L12 3Z" />
                                        </svg>
                                        Default
                                    </span>
                                @endif
                            </span>
                            <span class="mt-1 block truncate font-mono text-[10px] text-base-content/40">
                                {{ $variant->sku }}
                            </span>
                        </span>
                        <span class="shrink-0 text-right">
                            <strong class="block text-xs">${{ number_format($variant->price, 2) }}</strong>
                            <small class="mt-1 block text-[10px] text-base-content/40">{{ $variant->stock_quantity }}
                                stock</small>
                        </span>
                    </button>
                @endforeach
            </div>
        @endif
    </div>

        <div class="fixed inset-0 z-[80]" :class="drawerOpen ? 'pointer-events-auto' : 'pointer-events-none'"
            :aria-hidden="(!drawerOpen).toString()" role="dialog" aria-modal="true"
            aria-labelledby="variant-manager-title">
            <button type="button" x-show="drawerOpen" x-cloak
                x-transition:enter="transition-opacity duration-200 ease-out"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity duration-250 ease-in"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                @click="drawerOpen = false; setTimeout(() => $wire.cancel(), 260)"
                class="absolute inset-0 cursor-default bg-black/65 backdrop-blur-[2px]"
                aria-label="Close variant manager"></button>

            <aside x-show="drawerOpen" x-cloak
                x-transition:enter="transform transition duration-300 ease-out"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition duration-250 ease-in"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                class="absolute inset-y-0 right-0 flex w-full flex-col overflow-hidden border-l border-[var(--admin-border)] bg-[var(--admin-surface)] shadow-2xl sm:inset-y-3 sm:right-3 sm:w-[92vw] sm:rounded-2xl sm:border xl:w-[82vw] 2xl:max-w-6xl">
                <form wire:submit="save" class="flex min-h-0 flex-1 flex-col">
                    <header class="relative flex shrink-0 items-center justify-between gap-4 border-b border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-5 py-4 sm:px-6">
                        <span class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/15 to-transparent"></span>
                        <div class="flex min-w-0 items-center gap-3">
                            <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-accent text-accent-content shadow-[0_8px_20px_rgba(255,122,0,.22)]">
                                <svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" aria-hidden="true">
                                    <path d="M5 7h14v10H5V7Zm3-3v3m8-3v3M8 17v3m8-3v3" />
                                </svg>
                            </span>
                            <h3 id="variant-manager-title" class="truncate text-base font-semibold sm:text-lg">
                                {{ $product->name }}
                            </h3>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="hidden badge badge-ghost badge-sm sm:inline-flex">{{ $variants->count() }}
                                {{ Str::plural('variant', $variants->count()) }}</span>
                            <button type="button"
                                @click="drawerOpen = false; setTimeout(() => $wire.cancel(), 260)"
                                class="btn btn-square btn-sm rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] shadow-admin-control hover:bg-[var(--admin-surface-soft)]"
                                aria-label="Close variant manager">
                                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" aria-hidden="true">
                                    <path d="M6 6l12 12M18 6 6 18" />
                                </svg>
                            </button>
                        </div>
                    </header>

                    <div class="grid min-h-0 flex-1 lg:grid-cols-[17rem_minmax(0,1fr)]">
                        <aside
                            class="border-b border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] p-4 lg:min-h-0 lg:overflow-y-auto lg:border-b-0 lg:border-r">
                            <div class="flex items-center justify-between gap-3">
                                <h4 class="text-xs font-semibold">All variants</h4>
                                <button type="button" wire:click="addNew"
                                    class="btn btn-primary btn-square btn-sm rounded-xl" aria-label="Add variant"
                                    title="Add variant">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" aria-hidden="true">
                                        <path d="M12 5v14M5 12h14" />
                                    </svg>
                                </button>
                            </div>

                            <div class="mt-3 flex gap-2 overflow-x-auto pb-1 lg:flex-col lg:overflow-visible">
                                @foreach ($variants as $variant)
                                    <button type="button" wire:click="edit({{ $variant->id }})"
                                        wire:key="variant-nav-{{ $variant->id }}"
                                        class="flex w-64 shrink-0 items-center gap-3 rounded-xl border p-2.5 text-left transition lg:w-full {{ $editingId === $variant->id ? 'border-accent/25 bg-accent/10 text-base-content shadow-admin-control' : 'border-transparent hover:border-[var(--admin-border)] hover:bg-[var(--admin-surface-raised)]' }}">
                                        @if ($variant->primary_image)
                                            <img src="{{ Storage::url($variant->primary_image->image_url) }}" alt=""
                                                class="size-10 shrink-0 rounded-lg object-cover">
                                        @else
                                            <span
                                                class="grid size-10 shrink-0 place-items-center rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] text-base-content/30 shadow-admin-control">
                                                <svg class="size-4" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="1.7">
                                                    <path d="M4 5h16v14H4V5Z" />
                                                </svg>
                                            </span>
                                        @endif
                                        <span class="min-w-0 flex-1">
                                            <span class="flex items-center gap-1.5">
                                                <strong class="truncate text-xs font-semibold">{{ $variant->name }}</strong>
                                                @if ($variant->is_default)
                                                    <span
                                                        class="badge badge-sm shrink-0 gap-1 border-primary/15 bg-primary/10 px-1.5 text-[9px] font-semibold text-primary">
                                                        <svg class="size-2.5" viewBox="0 0 24 24" fill="currentColor"
                                                            aria-hidden="true">
                                                            <path d="m12 3 2.7 5.5 6.1.9-4.4 4.3 1 6.1-5.4-2.9-5.4 2.9 1-6.1-4.4-4.3 6.1-.9L12 3Z" />
                                                        </svg>
                                                        Default
                                                    </span>
                                                @endif
                                            </span>
                                            <span class="mt-1 block truncate font-mono text-[9px] text-base-content/40">
                                                {{ $variant->sku }} · {{ $variant->stock_quantity }} stock
                                            </span>
                                        </span>
                                    </button>
                                @endforeach

                                <button type="button" wire:click="addNew"
                                    class="flex w-52 shrink-0 items-center justify-center gap-2 rounded-xl border border-dashed border-[var(--admin-border-strong)] px-4 py-3 text-xs font-medium text-base-content/50 transition-colors hover:border-accent/50 hover:bg-accent/10 hover:text-accent lg:w-full">
                                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M12 5v14M5 12h14" />
                                    </svg>
                                    Add variant
                                </button>
                            </div>
                        </aside>

                        <main class="min-h-0 overflow-y-auto bg-[var(--admin-canvas)] p-4 sm:p-6">
                            @if ($editorOpen)
                                <article class="mx-auto max-w-5xl">
                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                        <h4 class="text-lg font-semibold">
                                            {{ $editingId ? ($name ?: 'Untitled variant') : 'Create a variant' }}
                                        </h4>
                                        @if ($editingId)
                                            <button type="button" wire:click="deleteVariant({{ $editingId }})"
                                                wire:confirm="Delete this variant and all its images?"
                                                class="btn btn-ghost btn-sm rounded-xl text-error"
                                                aria-label="Delete variant">
                                                <svg class="size-4" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="1.8">
                                                    <path d="M4 7h16m-10 4v5m4-5v5M9 7V4h6v3m-9 0 1 13h10l1-13" />
                                                </svg>
                                                Delete
                                            </button>
                                        @endif
                                    </div>

                                    <p class="mt-1 text-xs leading-5 text-base-content/45">Set pricing and inventory first, then review inherited specifications and media below.</p>

                                    <div class="mt-5 overflow-hidden rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] shadow-admin-panel">
                                        <div class="border-b border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-5 py-4">
                                            <h5 class="text-sm font-semibold">Identity and inventory</h5>
                                        </div>
                                        <div class="space-y-5 p-5">
                                            <div class="grid gap-4 md:grid-cols-2">
                                                <fieldset class="fieldset">
                                                    <legend class="fieldset-legend text-xs">Variant name <span
                                                            class="text-error">*</span></legend>
                                                    <input type="text" wire:model="name"
                                                        class="input input-bordered h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                                                    @error('name') <p class="text-xs text-error">{{ $message }}</p> @enderror
                                                </fieldset>
                                                <fieldset class="fieldset">
                                                    <legend class="fieldset-legend text-xs">SKU <span
                                                            class="text-error">*</span></legend>
                                                    <input type="text" wire:model="sku"
                                                        class="input input-bordered h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] uppercase shadow-admin-control focus:border-accent focus:outline-none">
                                                    @error('sku') <p class="text-xs text-error">{{ $message }}</p> @enderror
                                                </fieldset>
                                            </div>

                                            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                                <fieldset class="fieldset">
                                                    <legend class="fieldset-legend text-xs">Price <span
                                                            class="text-error">*</span></legend>
                                                    <label
                                                        class="input input-bordered flex h-11 w-full items-center gap-2 rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus-within:border-accent">
                                                        <span class="text-base-content/40">$</span>
                                                        <input type="number" step="0.01" wire:model="price"
                                                            class="grow border-0 bg-transparent p-0 focus:border-0 focus:ring-0">
                                                    </label>
                                                    @error('price') <p class="text-xs text-error">{{ $message }}</p> @enderror
                                                </fieldset>
                                                <fieldset class="fieldset">
                                                    <legend class="fieldset-legend text-xs">Compare price</legend>
                                                    <label
                                                        class="input input-bordered flex h-11 w-full items-center gap-2 rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus-within:border-accent">
                                                        <span class="text-base-content/40">$</span>
                                                        <input type="number" step="0.01" wire:model="compare_price"
                                                            class="grow border-0 bg-transparent p-0 focus:border-0 focus:ring-0">
                                                    </label>
                                                    @error('compare_price') <p class="text-xs text-error">{{ $message }}</p> @enderror
                                                </fieldset>
                                                <fieldset class="fieldset">
                                                    <legend class="fieldset-legend text-xs">Stock quantity <span
                                                            class="text-error">*</span></legend>
                                                    <input type="number" wire:model="stock_quantity" min="0"
                                                        class="input input-bordered h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                                                    @error('stock_quantity') <p class="text-xs text-error">{{ $message }}</p> @enderror
                                                </fieldset>
                                            </div>

                                            <div class="grid gap-3 sm:grid-cols-2">
                                                <label
                                                    class="flex cursor-pointer items-center justify-between gap-4 rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] p-3.5 shadow-inner">
                                                    <strong class="text-xs font-semibold">Active variant</strong>
                                                    <input type="checkbox" wire:model="is_active"
                                                        class="toggle toggle-primary toggle-sm">
                                                </label>
                                                <label
                                                    class="flex cursor-pointer items-center justify-between gap-4 rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] p-3.5 shadow-inner">
                                                    <strong class="text-xs font-semibold">Default variant</strong>
                                                    <input type="checkbox" wire:model="is_default"
                                                        class="toggle toggle-warning toggle-sm"
                                                        @disabled($editingId && $is_default)>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-5 space-y-4">
                                        @foreach ($specificationGroups as $group => $fields)
                                            <section
                                                class="overflow-hidden rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] shadow-admin-panel">
                                                <div class="border-b border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-5 py-3.5">
                                                    <h5 class="text-xs font-semibold">{{ $group }}</h5>
                                                </div>
                                                <div class="grid lg:grid-cols-2">
                                                    @foreach ($fields as $key => $label)
                                                        @php($isOverridden = (bool) ($overriddenSpecs[$key] ?? false))
                                                        @php($sharedValue = $sharedSpecifications[$key] ?? null)
                                                        <div wire:key="variant-spec-{{ $key }}"
                                                            class="border-b border-[var(--admin-border)] p-4 last:border-b-0 lg:odd:border-r lg:odd:border-[var(--admin-border)]">
                                                            <div class="flex items-center justify-between gap-3">
                                                                <div class="min-w-0">
                                                                    <label for="override-{{ $key }}"
                                                                        class="block text-xs font-medium">{{ $label }}</label>
                                                                    @unless ($isOverridden)
                                                                        <p class="mt-1 truncate text-[10px] text-base-content/40">
                                                                            {{ filled($sharedValue) ? 'Shared: ' . $sharedValue : 'No shared value' }}
                                                                        </p>
                                                                    @endunless
                                                                </div>
                                                                <label
                                                                    class="flex shrink-0 cursor-pointer items-center gap-2 text-[10px] text-base-content/45">
                                                                    <span>{{ $isOverridden ? 'Override' : 'Inherit' }}</span>
                                                                    <input id="override-{{ $key }}" type="checkbox"
                                                                        wire:model.live="overriddenSpecs.{{ $key }}"
                                                                        class="toggle toggle-primary toggle-xs">
                                                                </label>
                                                            </div>

                                                            @if ($isOverridden)
                                                                <input type="text"
                                                                    wire:model="specOverrides.{{ $key }}"
                                                                    class="input input-bordered mt-3 h-10 min-h-10 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                                                                @error("specOverrides.$key") <p
                                                                        class="mt-1 text-[11px] text-error">{{ $message }}</p> @enderror
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </section>
                                        @endforeach

                                        <section
                                            class="overflow-hidden rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] shadow-admin-panel">
                                            <div
                                                class="flex flex-col gap-3 border-b border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                                                <h5 class="text-xs font-semibold">Additional variant attributes</h5>
                                                <button type="button" wire:click="addCustomSpecOverride"
                                                    class="btn btn-xs h-9 min-h-9 gap-1 rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-3 shadow-admin-control hover:border-accent/35 hover:bg-accent/10">
                                                    <svg class="size-3" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path d="M12 5v14M5 12h14" />
                                                    </svg>
                                                    Add attribute
                                                </button>
                                            </div>
                                            <div class="p-4">
                                                @if ($customSpecOverrides)
                                                    <div class="space-y-2">
                                                        @foreach ($customSpecOverrides as $index => $row)
                                                            <div wire:key="variant-custom-spec-{{ $index }}"
                                                                class="grid gap-2 sm:grid-cols-[1fr_1fr_auto]">
                                                                <input type="text"
                                                                    wire:model="customSpecOverrides.{{ $index }}.key"
                                                                    placeholder="Attribute name"
                                                                    class="input input-bordered h-10 min-h-10 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                                                                <input type="text"
                                                                    wire:model="customSpecOverrides.{{ $index }}.value"
                                                                    placeholder="Value"
                                                                    class="input input-bordered h-10 min-h-10 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                                                                <button type="button"
                                                                    wire:click="removeCustomSpecOverride({{ $index }})"
                                                                    class="btn btn-ghost btn-square btn-sm rounded-xl border border-transparent text-error hover:border-error/20 hover:bg-error/10"
                                                                    aria-label="Remove variant attribute">
                                                                    <svg class="size-4" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="1.8">
                                                                        <path d="M6 6l12 12M18 6 6 18" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <p class="py-3 text-center text-[11px] text-base-content/40">No
                                                        additional attributes for this variant.</p>
                                                @endif
                                            </div>
                                        </section>
                                    </div>

                                    <div class="mt-5 overflow-hidden rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] p-5 shadow-admin-panel">
                                        @if ($editingVariant)
                                            <livewire:admin.products.variant-images :variant="$editingVariant"
                                                :key="'workspace-variant-images-' . $editingVariant->id" />
                                        @else
                                            <div class="grid min-h-56 place-items-center text-center">
                                                <div>
                                                    <span
                                                        class="mx-auto grid size-11 place-items-center rounded-xl border border-accent/20 bg-accent/10 text-accent shadow-admin-control">
                                                        <svg class="size-5" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="1.7">
                                                            <path d="M4 5h16v14H4V5Zm3 10 3-3 2.5 2.5L15 12l3 3" />
                                                        </svg>
                                                    </span>
                                                    <h5 class="mt-3 text-sm font-semibold">Save the variant first</h5>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </article>
                            @else
                                <div class="grid min-h-[28rem] place-items-center text-center">
                                    <div>
                                        <span
                                            class="mx-auto grid size-12 place-items-center rounded-2xl border border-accent/20 bg-accent/10 text-accent shadow-admin-control">
                                            <svg class="size-5" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.8">
                                                <path d="M5 7h14v10H5V7Zm3-3v3m8-3v3" />
                                            </svg>
                                        </span>
                                        <h4 class="mt-4 text-sm font-semibold">Select or create a variant</h4>
                                        <button type="button" wire:click="addNew"
                                            class="btn btn-primary btn-sm mt-4 rounded-xl px-4">Add variant</button>
                                    </div>
                                </div>
                            @endif
                        </main>
                    </div>

                    <footer
                        class="flex shrink-0 items-center justify-between gap-3 border-t border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-5 py-3.5 sm:px-6">
                        <div class="ml-auto flex items-center gap-2">
                            <button type="button"
                                @click="drawerOpen = false; setTimeout(() => $wire.cancel(), 260)"
                                class="btn btn-ghost btn-sm rounded-xl">Close</button>
                            @if ($editorOpen)
                                <button type="submit" wire:loading.attr="disabled" wire:target="save"
                                    class="btn btn-primary btn-sm h-10 min-h-10 min-w-32 rounded-xl px-5 shadow-[0_10px_24px_rgba(255,122,0,.2)]">
                                    <span wire:loading wire:target="save"
                                        class="loading loading-spinner loading-xs"></span>
                                    <span wire:loading.remove
                                        wire:target="save">{{ $editingId ? 'Save changes' : 'Create variant' }}</span>
                                    <span wire:loading wire:target="save">Saving...</span>
                                </button>
                            @endif
                        </div>
                    </footer>
                </form>
            </aside>
        </div>
</section>
