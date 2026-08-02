<div>

    {{-- Hero banner --}}
    <section class="relative h-40 sm:h-48 lg:h-56 flex items-center overflow-hidden">
        <img src="https://www.casio.com/content/casio/locales/intl/en/products/watches/casio/standard/vintage/aq-230/_jcr_content/root/responsivegrid/container_1479889198/container_2106131580/teaser_copy_copy_cop.casiocoreimg.jpeg/1769996532300/kv.jpeg"
            alt="Shop all watches" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/20"></div>

        {{-- Breadcrumb --}}
        <div class="relative z-10 px-6 sm:px-10 lg:px-20 xl:px-28 w-full">
            <nav class="text-xs text-white/70 mb-2 tracking-widest uppercase">
                <a href="{{ url('/') }}" class="hover:text-white">Home</a>
                <span class="mx-2">/</span>
                <span class="text-white">Shop</span>
            </nav>
            <h1 class="text-2xl lg:text-3xl font-bold text-white">All Watches</h1>
        </div>
    </section>

    <div class="px-6 sm:px-10 lg:px-20 xl:px-28 py-8 max-w-[1800px] mx-auto"
        x-data="{ showFilters: @entangle('showFilters') }"
        x-effect="document.body.classList.toggle('overflow-hidden', showFilters)">

        @php
            $sortOptions = [
                'alpha_asc' => 'Alphabetically, A-Z',
                'alpha_desc' => 'Alphabetically, Z-A',
                'price_asc' => 'Price, low to high',
                'price_desc' => 'Price, high to low',
                'date_asc' => 'Date, old to new',
                'date_desc' => 'Date, new to old',
            ];
        @endphp

        {{-- Controls bar --}}
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <p class="text-sm text-gray-900">
                    <span class="font-semibold">{{ $products->total() }}</span>
                    {{ \Illuminate\Support\Str::plural('product', $products->total()) }}
                </p>

                <span class="w-px h-4 bg-gray-300"></span>

                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button type="button" @click="open = !open"
                        class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:border-gray-500 whitespace-nowrap">
                        <span>{{ $sort === 'date_desc' ? 'Sort by' : ($sortOptions[$sort] ?? 'Sort by') }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="absolute z-30 mt-2 w-56 bg-white border border-gray-200 rounded-lg shadow-lg py-1">
                        @foreach ($sortOptions as $value => $label)
                            <button type="button" wire:click="$set('sort', '{{ $value }}')" @click="open = false"
                                class="w-full text-left px-4 py-2.5 text-sm transition-colors
                                                                            {{ $sort === $value ? 'text-gray-900 font-medium bg-gray-50' : 'text-gray-600 hover:bg-gray-50' }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <button type="button" @click="showFilters = true"
                class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:border-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                </svg>
                Filter
                @if ($this->activeFilterCount > 0)
                    <span
                        class="ml-1 inline-flex items-center justify-center h-4 w-4 rounded-full bg-gray-900 text-white text-[10px]">
                        {{ $this->activeFilterCount }}
                    </span>
                @endif
            </button>
        </div>

        {{-- Grid --}}
        @if ($products->isEmpty())
            <p class="text-gray-400 text-center py-20">No products match your filters.</p>
        @else
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-16">
                @foreach ($products as $product)
                    <a href="{{ route('shop.product', $product->slug) }}" class="group block"
                        wire:key="product-{{ $product->id }}">
                        <div class="relative aspect-square bg-gray-100 overflow-hidden mb-4">
                            <button type="button" wire:click.stop.prevent="toggleWishlist({{ $product->id }})"
                                class="absolute top-3 right-3 z-10 p-2 bg-white/90 rounded-full shadow hover:bg-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                    fill="{{ in_array($product->id, $this->wishlistedProductIds) ? 'currentColor' : 'none' }}"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">

                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />

                                </svg>
                            </button>
                            {{-- @if ($product->is_featured)
                            <span
                                class="absolute top-3 left-3 z-10 bg-white px-3 py-1 text-[10px] font-semibold tracking-widest uppercase text-gray-900">
                                Featured
                            </span>
                            @endif --}}
                            @if ($product->primary_image_url)
                                <img src="{{ $product->primary_image_url }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @endif
                        </div>

                        <p class="text-[11px] tracking-widest uppercase text-gray-400 mb-1">
                            {{ $product->brand->name }}
                        </p>
                        <h3
                            class="text-sm font-semibold uppercase tracking-wide text-gray-900 leading-snug mb-1.5 line-clamp-2">
                            {{ $product->name }}
                        </h3>
                        <p class="text-sm text-gray-900 font-medium">
                            @if ($product->variants_min_price)
                                From ${{ number_format($product->variants_min_price, 2) }}
                            @endif
                        </p>
                    </a>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $products->links() }}
            </div>
        @endif

        {{-- Filter drawer backdrop --}}
        <div x-show="showFilters" x-transition.opacity x-cloak @click="showFilters = false"
            class="fixed inset-0 bg-black/40 z-40"></div>

        {{-- Filter drawer panel --}}
        <div x-show="showFilters" x-cloak @keydown.escape.window="showFilters = false"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
            class="fixed top-24 bottom-4 right-4 w-full max-w-md bg-white z-40 flex flex-col rounded-2xl shadow-2xl overflow-hidden">

            <div class="flex items-center justify-between px-6 h-20 border-b border-gray-200 shrink-0">
                <h2 class="text-lg font-semibold text-gray-900">Filter your search</h2>
                <div class="flex items-center gap-4">
                    @if ($this->activeFilterCount > 0)
                        <button type="button" wire:click="clearFilters"
                            class="text-sm text-gray-500 hover:text-gray-900 underline">
                            Reset
                        </button>
                    @endif
                    <button type="button" @click="showFilters = false" class="p-2 border border-gray-200 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-6 space-y-8"
                x-data="{ open: { price: true, gender: true, brand: true, category: false, movement: false } }">

                {{-- Price --}}
                <div class="border-b border-gray-100 pb-6" x-data="{
                    min: @entangle('minPrice'),
                    max: @entangle('maxPrice'),
                    floor: {{ $priceFloor }},
                    ceil: {{ $priceCeil }},
                    timer: null,
                    pct(v) { return this.ceil === this.floor ? 0 : ((v - this.floor) / (this.ceil - this.floor)) * 100 },
                    sync() {
                        clearTimeout(this.timer);
                        this.timer = setTimeout(() => {
                            $wire.set('minPrice', this.min);
                            $wire.set('maxPrice', this.max);
                        }, 400);
                    },
                }">
                    <button type="button" @click="open.price = !open.price"
                        class="w-full flex items-center justify-between">
                        <span class="text-base font-semibold text-gray-900">Price</span>
                        <span class="text-xl text-gray-400" x-text="open.price ? '−' : '+'"></span>
                    </button>

                    <div x-show="open.price" x-collapse class="pt-6">
                        <div class="relative h-1 bg-gray-200 rounded-full mb-6">
                            <div class="absolute h-1 bg-gray-900 rounded-full"
                                :style="`left: ${pct(min)}%; right: ${100 - pct(max)}%`"></div>
                            <span
                                class="absolute top-1/2 -translate-y-1/2 -translate-x-1/2 h-3 w-3 rounded-full bg-white border-2 border-gray-900"
                                :style="`left: ${pct(min)}%`"></span>
                            <span
                                class="absolute top-1/2 -translate-y-1/2 -translate-x-1/2 h-3 w-3 rounded-full bg-white border-2 border-gray-900"
                                :style="`left: ${pct(max)}%`"></span>

                            <input type="range" :min="floor" :max="ceil" step="1" x-model.number="min"
                                @input="if (min > max) min = max; sync()"
                                class="range-thumb absolute inset-0 w-full h-1 appearance-none bg-transparent pointer-events-none">
                            <input type="range" :min="floor" :max="ceil" step="1" x-model.number="max"
                                @input="if (max < min) max = min; sync()"
                                class="range-thumb absolute inset-0 w-full h-1 appearance-none bg-transparent pointer-events-none">
                        </div>

                        <p class="text-sm text-gray-500">
                            From <span class="text-gray-900 font-medium underline underline-offset-4"
                                x-text="'$' + min"></span>
                            to <span class="text-gray-900 font-medium underline underline-offset-4"
                                x-text="'$' + max"></span>
                        </p>
                    </div>
                </div>

                {{-- Gender --}}
                <div class="border-b border-gray-100 pb-6">
                    <button type="button" @click="open.gender = !open.gender"
                        class="w-full flex items-center justify-between">
                        <span class="text-base font-semibold text-gray-900">Gender @if(count($genders))
                        ({{ count($genders) }}) @endif</span>
                        <span class="text-xl text-gray-400" x-text="open.gender ? '−' : '+'"></span>
                    </button>
                    <div x-show="open.gender" x-collapse class="pt-4 space-y-3">
                        @foreach (['men' => "Man", 'women' => "Woman", 'unisex' => 'Unisex'] as $value => $label)
                            <label wire:key="gender-{{ $value }}"
                                class="flex items-center justify-between gap-3 text-sm text-gray-700 cursor-pointer">
                                <span class="flex items-center gap-3">
                                    <input type="checkbox" wire:model.live="genders" value="{{ $value }}"
                                        class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                                    {{ $label }}
                                </span>
                                <span class="text-gray-400">({{ $genderCounts[$value] ?? 0 }})</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Brand --}}
                <div class="border-b border-gray-100 pb-6">
                    <button type="button" @click="open.brand = !open.brand"
                        class="w-full flex items-center justify-between">
                        <span class="text-base font-semibold text-gray-900">Brand @if(count($brands))
                        ({{ count($brands) }}) @endif</span>
                        <span class="text-xl text-gray-400" x-text="open.brand ? '−' : '+'"></span>
                    </button>
                    <div x-show="open.brand" x-collapse class="pt-4 space-y-3">
                        @foreach ($brandOptions as $b)
                            <label wire:key="brand-{{ $b->id }}"
                                class="flex items-center justify-between gap-3 text-sm text-gray-700 cursor-pointer">
                                <span class="flex items-center gap-3">
                                    <input type="checkbox" wire:model.live="brands" value="{{ $b->slug }}"
                                        class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                                    {{ $b->name }}
                                </span>
                                <span class="text-gray-400">({{ $brandCounts[$b->slug] ?? 0 }})</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Category --}}
                <div class="border-b border-gray-100 pb-6">
                    <button type="button" @click="open.category = !open.category"
                        class="w-full flex items-center justify-between">
                        <span class="text-base font-semibold text-gray-900">Style @if(count($categories))
                        ({{ count($categories) }}) @endif</span>
                        <span class="text-xl text-gray-400" x-text="open.category ? '−' : '+'"></span>
                    </button>
                    <div x-show="open.category" x-collapse class="pt-4 space-y-3">
                        @foreach ($categoryOptions as $c)
                            <label wire:key="category-{{ $c->id }}"
                                class="flex items-center justify-between gap-3 text-sm text-gray-700 cursor-pointer">
                                <span class="flex items-center gap-3">
                                    <input type="checkbox" wire:model.live="categories" value="{{ $c->slug }}"
                                        class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                                    {{ $c->name }}
                                </span>
                                <span class="text-gray-400">({{ $categoryCounts[$c->slug] ?? 0 }})</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Movement --}}
                <div class="pb-6">
                    <button type="button" @click="open.movement = !open.movement"
                        class="w-full flex items-center justify-between">
                        <span class="text-base font-semibold text-gray-900">Movement @if(count($movements))
                        ({{ count($movements) }}) @endif</span>
                        <span class="text-xl text-gray-400" x-text="open.movement ? '−' : '+'"></span>
                    </button>
                    <div x-show="open.movement" x-collapse class="pt-4 space-y-3">
                        @foreach (['automatic' => 'Automatic', 'quartz' => 'Quartz', 'mechanical' => 'Mechanical', 'chronograph' => 'Chronograph', 'smart' => 'Smart'] as $value => $label)
                            <label wire:key="movement-{{ $value }}"
                                class="flex items-center justify-between gap-3 text-sm text-gray-700 cursor-pointer">
                                <span class="flex items-center gap-3">
                                    <input type="checkbox" wire:model.live="movements" value="{{ $value }}"
                                        class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                                    {{ $label }}
                                </span>
                                <span class="text-gray-400">({{ $movementCounts[$value] ?? 0 }})</span>
                            </label>
                        @endforeach
                    </div>
                </div>

            </div>

            <div class="p-6 border-t border-gray-200 shrink-0">
                <button type="button" @click="showFilters = false"
                    class="w-full py-3 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800">
                    Show {{ $products->total() }} products
                </button>
            </div>

        </div>

    </div>

</div>