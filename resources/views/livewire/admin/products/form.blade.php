<div class="mx-auto w-full max-w-[1500px] space-y-5"
    x-data="{ pageTab: 'details', specTab: 'case', watchType: @entangle('watch_type') }"
    x-effect="if (watchType === 'traditional' && specTab === 'smart') specTab = 'case'">
    <header class="flex flex-col gap-4 rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] p-4 shadow-admin-panel sm:flex-row sm:items-center sm:justify-between sm:px-5">
        <div class="flex min-w-0 items-center gap-3">
            <a href="{{ route('admin.products.index') }}"
                class="btn btn-square btn-sm shrink-0 rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] shadow-admin-control hover:bg-[var(--admin-surface-soft)]"
                aria-label="Back to products">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6" />
                </svg>
            </a>
            <div class="min-w-0">
                <p class="text-[10px] font-medium uppercase tracking-[0.12em] text-base-content/40">Catalog · Products</p>
                <div class="mt-0.5 flex min-w-0 items-center gap-2">
                    <h1 class="truncate text-lg font-semibold tracking-tight sm:text-xl">
                        {{ $product ? $product->name : 'New product' }}
                    </h1>
                    @if ($product)
                        <x-admin.badge :tone="$product->is_active ? 'green' : 'gray'">
                            {{ $product->is_active ? 'Active' : 'Draft' }}
                        </x-admin.badge>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('admin.products.index') }}" class="btn btn-ghost btn-sm h-10 min-h-10 rounded-xl px-4">Cancel</a>
            <button type="submit" form="product-form" wire:loading.attr="disabled" wire:target="save"
                class="btn btn-primary btn-sm h-10 min-h-10 min-w-32 rounded-xl px-5 shadow-[0_10px_24px_rgba(255,122,0,.2)]">
                <span wire:loading wire:target="save" class="loading loading-spinner loading-xs"></span>
                <span wire:loading.remove wire:target="save">{{ $product ? 'Save changes' : 'Create product' }}</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
        </div>
    </header>

    <nav class="overflow-x-auto rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] p-1.5 shadow-inner [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
        aria-label="Product editor sections">
        <div class="flex min-w-max gap-1" role="tablist">
            <button type="button" role="tab" @click="pageTab = 'details'"
                :aria-selected="(pageTab === 'details').toString()"
                class="flex h-9 items-center gap-2 rounded-xl border px-4 text-[11px] font-semibold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent/25"
                :class="pageTab === 'details' ? 'border-[var(--admin-border-strong)] bg-[var(--admin-surface-raised)] text-base-content shadow-admin-control' : 'border-transparent text-base-content/45 hover:bg-[var(--admin-surface-soft)] hover:text-base-content/75'">
                Details
            </button>
            <button type="button" role="tab" @click="pageTab = 'specifications'"
                :aria-selected="(pageTab === 'specifications').toString()"
                class="flex h-9 items-center gap-2 rounded-xl border px-4 text-[11px] font-semibold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent/25"
                :class="pageTab === 'specifications' ? 'border-[var(--admin-border-strong)] bg-[var(--admin-surface-raised)] text-base-content shadow-admin-control' : 'border-transparent text-base-content/45 hover:bg-[var(--admin-surface-soft)] hover:text-base-content/75'">
                Specifications
            </button>
            @if ($product)
                <button type="button" role="tab" @click="pageTab = 'variants'"
                    :aria-selected="(pageTab === 'variants').toString()"
                    class="flex h-9 items-center gap-2 rounded-xl border px-4 text-[11px] font-semibold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent/25"
                    :class="pageTab === 'variants' ? 'border-[var(--admin-border-strong)] bg-[var(--admin-surface-raised)] text-base-content shadow-admin-control' : 'border-transparent text-base-content/45 hover:bg-[var(--admin-surface-soft)] hover:text-base-content/75'">
                    Variants
                    <span class="badge badge-ghost badge-xs">{{ $product->variants()->count() }}</span>
                </button>
            @else
                <span class="flex h-9 cursor-not-allowed items-center gap-2 rounded-xl px-4 text-[11px] font-semibold text-base-content/25"
                    title="Create the product before adding variants">
                    Variants
                    <svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="6" y="10" width="12" height="9" rx="2" />
                        <path d="M9 10V7a3 3 0 0 1 6 0v3" />
                    </svg>
                </span>
            @endif
        </div>
    </nav>

    <form id="product-form" wire:submit="save">
        <div x-show="pageTab === 'details'" x-cloak
            class="grid items-start gap-5 xl:grid-cols-[minmax(0,1fr)_21rem]">
            <x-admin.form-section title="Product information" description="Customer-facing catalog content">
                <x-slot:icon>
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="m12 3 8 4.5-8 4.5-8-4.5L12 3Zm-8 9 8 4.5 8-4.5M4 16.5 12 21l8-4.5" />
                    </svg>
                </x-slot:icon>

                <div class="space-y-5">
                    <x-admin.form-field label="Product name" name="name" required>
                        <input id="name" type="text" wire:model="name"
                            class="input input-bordered h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none"
                            autocomplete="off">
                    </x-admin.form-field>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-admin.form-field label="Brand" name="brand_id" required>
                            <select id="brand_id" wire:model="brand_id"
                                class="select select-bordered h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                                <option value="">Select brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </x-admin.form-field>

                        <x-admin.form-field label="Category" name="category_id" required>
                            <select id="category_id" wire:model="category_id"
                                class="select select-bordered h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                                <option value="">Select category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </x-admin.form-field>
                    </div>

                    <x-admin.form-field label="Description" name="description" required>
                        <textarea id="description" wire:model="description" rows="9"
                            class="textarea textarea-bordered w-full resize-y rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] leading-6 shadow-admin-control focus:border-accent focus:outline-none"></textarea>
                    </x-admin.form-field>
                </div>
            </x-admin.form-section>

            <aside class="space-y-5 xl:sticky xl:top-20">
                <x-admin.form-section title="Classification" description="Catalog placement">
                    <div class="space-y-4">
                        <x-admin.form-field label="Audience" name="gender" required>
                            <select id="gender" wire:model="gender"
                                class="select select-bordered h-10 min-h-10 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                                <option value="">Select audience</option>
                                <option value="men">Men</option>
                                <option value="women">Women</option>
                                <option value="unisex">Unisex</option>
                            </select>
                        </x-admin.form-field>

                        <x-admin.form-field label="Watch type" name="watch_type" required>
                            <select id="watch_type" wire:model.live="watch_type"
                                class="select select-bordered h-10 min-h-10 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                                <option value="traditional">Traditional</option>
                                <option value="smart">Smartwatch</option>
                                <option value="hybrid">Hybrid</option>
                            </select>
                        </x-admin.form-field>

                        <x-admin.form-field label="Movement" name="movement" required>
                            <select id="movement" wire:model="movement"
                                class="select select-bordered h-10 min-h-10 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                                <option value="">Select movement</option>
                                <option value="automatic">Automatic</option>
                                <option value="quartz">Quartz</option>
                                <option value="mechanical">Mechanical</option>
                                <option value="chronograph">Chronograph</option>
                                <option value="smart">Smart</option>
                            </select>
                        </x-admin.form-field>
                    </div>
                </x-admin.form-section>

                <x-admin.form-section title="Publishing" description="Storefront state">
                    <div class="space-y-3">
                        <x-admin.switch-row label="Active product"
                            :description="$canPublish ? 'Visible on the storefront' : 'Requires an active default variant'">
                            <input type="checkbox" wire:model="is_active"
                                class="toggle toggle-primary toggle-sm shrink-0" @disabled(!$canPublish)>
                        </x-admin.switch-row>
                        @error('is_active') <p class="text-xs text-error">{{ $message }}</p> @enderror

                        <x-admin.switch-row label="Featured product" description="Prioritized in featured collections">
                            <input type="checkbox" wire:model="is_featured"
                                class="toggle toggle-warning toggle-sm shrink-0">
                        </x-admin.switch-row>
                    </div>
                </x-admin.form-section>
            </aside>
        </div>

        <div x-show="pageTab === 'specifications'" x-cloak>
            <x-admin.form-section title="Shared specifications" description="Defaults inherited by every variant">
                <x-slot:icon>
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="8" />
                        <path d="M4 12h16M12 4v16" />
                    </svg>
                </x-slot:icon>

                <div
                    class="-mx-1 mb-6 overflow-x-auto rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] p-1.5 shadow-inner [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                    <div class="flex min-w-max gap-1" role="tablist" aria-label="Specification groups">
                        @foreach ([
                            'case' => 'Case & dial',
                            'movement' => 'Movement',
                            'strap' => 'Strap & origin',
                        ] as $tab => $label)
                            <button type="button" role="tab" @click="specTab = '{{ $tab }}'"
                                :aria-selected="(specTab === '{{ $tab }}').toString()"
                                class="h-9 rounded-lg border px-3 text-[11px] font-semibold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent/25"
                                :class="specTab === '{{ $tab }}' ? 'border-[var(--admin-border-strong)] bg-[var(--admin-surface-raised)] text-base-content shadow-admin-control' : 'border-transparent text-base-content/45 hover:text-base-content'">
                                {{ $label }}
                            </button>
                        @endforeach
                        @if (in_array($watch_type, ['smart', 'hybrid'], true))
                            <button type="button" role="tab" @click="specTab = 'smart'"
                                :aria-selected="(specTab === 'smart').toString()"
                                class="h-9 rounded-lg border px-3 text-[11px] font-semibold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent/25"
                                :class="specTab === 'smart' ? 'border-[var(--admin-border-strong)] bg-[var(--admin-surface-raised)] text-base-content shadow-admin-control' : 'border-transparent text-base-content/45 hover:text-base-content'">
                                Smart features
                            </button>
                        @endif
                        <button type="button" role="tab" @click="specTab = 'additional'"
                            :aria-selected="(specTab === 'additional').toString()"
                            class="h-9 rounded-lg border px-3 text-[11px] font-semibold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent/25"
                            :class="specTab === 'additional' ? 'border-[var(--admin-border-strong)] bg-[var(--admin-surface-raised)] text-base-content shadow-admin-control' : 'border-transparent text-base-content/45 hover:text-base-content'">
                            Additional
                        </button>
                    </div>
                </div>

                <div x-show="specTab === 'case'" x-cloak class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ([
                        'case_size' => 'Case size',
                        'case_material' => 'Case material',
                        'case_thickness' => 'Case thickness',
                        'water_resistance' => 'Water resistance',
                        'glass_type' => 'Crystal / glass',
                        'weight' => 'Weight',
                        'dial_color' => 'Dial color',
                    ] as $field => $label)
                        <x-admin.form-field :label="$label" :name="$field">
                            <input id="{{ $field }}" type="text" wire:model="{{ $field }}"
                                class="input input-bordered h-10 min-h-10 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                        </x-admin.form-field>
                    @endforeach
                </div>

                <div x-show="specTab === 'movement'" x-cloak class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ([
                        'movement_caliber' => 'Movement caliber',
                        'power_reserve' => 'Power reserve',
                        'frequency' => 'Frequency',
                        'jewels' => 'Jewels',
                        'functions' => 'Functions',
                    ] as $field => $label)
                        <x-admin.form-field :label="$label" :name="$field" @class(['xl:col-span-2' => $field === 'functions'])>
                            <input id="{{ $field }}" type="text" wire:model="{{ $field }}"
                                class="input input-bordered h-10 min-h-10 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                        </x-admin.form-field>
                    @endforeach
                </div>

                <div x-show="specTab === 'strap'" x-cloak class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ([
                        'strap_material' => 'Strap material',
                        'clasp_type' => 'Clasp type',
                        'country_of_origin' => 'Country of origin',
                    ] as $field => $label)
                        <x-admin.form-field :label="$label" :name="$field">
                            <input id="{{ $field }}" type="text" wire:model="{{ $field }}"
                                class="input input-bordered h-10 min-h-10 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                        </x-admin.form-field>
                    @endforeach
                </div>

                @if (in_array($watch_type, ['smart', 'hybrid'], true))
                    <div x-show="specTab === 'smart'" x-cloak class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach ([
                            'battery_life' => 'Battery life',
                            'display_type' => 'Display type',
                            'connectivity' => 'Connectivity',
                            'compatibility' => 'Compatibility',
                        ] as $field => $label)
                            <x-admin.form-field :label="$label" :name="$field">
                                <input id="{{ $field }}" type="text" wire:model="{{ $field }}"
                                    class="input input-bordered h-10 min-h-10 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                            </x-admin.form-field>
                        @endforeach
                    </div>
                @endif

                <div x-show="specTab === 'additional'" x-cloak>
                    <div class="mb-4 flex justify-end">
                        <button type="button" wire:click="addCustomSpec"
                            class="btn btn-xs h-9 min-h-9 gap-1.5 rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-3 shadow-admin-control hover:border-accent/35 hover:bg-accent/10">
                            <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14M5 12h14" />
                            </svg>
                            Add attribute
                        </button>
                    </div>

                    <div class="space-y-2">
                        @foreach ($customSpecs as $index => $row)
                            <div class="grid gap-2 sm:grid-cols-[1fr_1fr_auto]" wire:key="custom-spec-{{ $index }}">
                                <input type="text" wire:model="customSpecs.{{ $index }}.key"
                                    placeholder="Attribute name" aria-label="Attribute name"
                                    class="input input-bordered h-10 min-h-10 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                                <input type="text" wire:model="customSpecs.{{ $index }}.value"
                                    placeholder="Value" aria-label="Attribute value"
                                    class="input input-bordered h-10 min-h-10 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                                <button type="button" wire:click="removeCustomSpec({{ $index }})"
                                    class="btn btn-ghost btn-square btn-sm rounded-xl border border-transparent text-error hover:border-error/20 hover:bg-error/10"
                                    aria-label="Remove attribute">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M6 6l12 12M18 6 6 18" />
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </x-admin.form-section>
        </div>
    </form>

    @if ($product)
        <div x-show="pageTab === 'variants'" x-cloak>
            <livewire:admin.products.variants :product="$product" :key="'variants-' . $product->id" />
        </div>
    @endif
</div>
