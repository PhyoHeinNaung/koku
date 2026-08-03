<div class="bg-[var(--koku-white)]" x-data="{ showFilters: @entangle('showFilters'), sortOpen: false }"
    x-effect="document.body.classList.toggle('overflow-hidden', showFilters)">
    @php
        $sortOptions = [
            'date_desc' => 'Newest first',
            'date_asc' => 'Oldest first',
            'alpha_asc' => 'Name, A–Z',
            'alpha_desc' => 'Name, Z–A',
            'price_asc' => 'Price, low to high',
            'price_desc' => 'Price, high to low',
        ];
    @endphp

    <header class="bg-[var(--koku-white)]">
        <div class="koku-shell pb-10 pt-8 sm:pb-12 sm:pt-10">
            <nav class="flex items-center justify-center gap-7 overflow-x-auto whitespace-nowrap py-4 text-xs text-[var(--koku-muted)] sm:gap-14 sm:text-sm"
                aria-label="Watch categories">
                <a href="{{ route('shop.index') }}"
                    class="relative font-medium text-[var(--koku-ink)] after:absolute after:-top-3 after:left-1/2 after:size-1 after:-translate-x-1/2 after:rounded-full after:bg-[var(--koku-indigo)]">All
                    watches</a>
                <a href="{{ route('shop.index', ['sort' => 'date_desc']) }}"
                    class="transition-colors hover:text-[var(--koku-indigo)]">New</a>
                <a href="{{ route('shop.index', ['movements' => ['automatic']]) }}"
                    class="transition-colors hover:text-[var(--koku-indigo)]">Automatic</a>
                <a href="{{ route('shop.index', ['movements' => ['quartz']]) }}"
                    class="transition-colors hover:text-[var(--koku-indigo)]">Quartz</a>
                <a href="{{ route('shop.index', ['movements' => ['mechanical']]) }}"
                    class="transition-colors hover:text-[var(--koku-indigo)]">Mechanical</a>
            </nav>
            <div class="hidden">
                <div>
                    <nav class="koku-eyebrow text-[var(--koku-muted)]" aria-label="Breadcrumb">
                        <a href="{{ route('home') }}" class="hover:text-[var(--koku-indigo)]">Koku</a>
                        <span class="mx-2 text-[var(--koku-line)]">/</span>
                        <span>Watches</span>
                    </nav>
                    <h1 class="mt-5 font-serif text-3xl font-medium tracking-[-0.04em] sm:text-4xl">
                        @if ($search !== '')
                            Results for “{{ $search }}”
                        @else
                            The collection
                        @endif
                    </h1>
                </div>
                <p class="mx-auto mt-4 max-w-lg text-xs leading-6 text-[var(--koku-muted)]">Watches selected for
                    clarity, proportion and enduring use.</p>
            </div>
            <div class="relative mt-6 h-40 overflow-hidden bg-[#f4f5f7] sm:h-48 lg:h-52">
                <div class="absolute -right-16 -top-36 h-[30rem] w-[54rem] rotate-[-7deg] rounded-[50%] bg-white shadow-[0_30px_70px_rgba(25,38,64,.12)]"></div>
                <div class="absolute right-[12%] top-8 h-36 w-[38%] rotate-[5deg] rounded-[50%] border border-[#e7e9ee] bg-gradient-to-b from-white to-[#eceff4] shadow-[0_25px_45px_rgba(25,38,64,.08)]"></div>
                <div class="absolute inset-y-0 left-0 flex max-w-lg flex-col justify-center px-6 sm:px-10 lg:px-14">
                    <p class="text-[9px] font-medium uppercase tracking-[0.2em] text-[var(--koku-indigo)]">The Koku edit</p>
                    <h1 class="mt-3 font-serif text-2xl font-medium tracking-[-0.03em] text-[var(--koku-ink)] sm:text-4xl">Objects for measured time.</h1>
                    <p class="mt-3 hidden max-w-sm text-xs leading-5 text-[var(--koku-muted)] sm:block">Quiet forms, precise movements and enduring materials.</p>
                </div>
            </div>
        </div>
    </header>

    <div class="bg-[var(--koku-white)]">
        <div
            class="koku-shell relative flex min-h-20 items-center justify-between gap-4 border-b border-[var(--koku-line)]">
            <button type="button" @click="showFilters = true"
                class="group flex h-14 items-center gap-3 text-[10px] font-medium uppercase tracking-[0.12em]">
                <svg class="size-4 text-[var(--koku-indigo)]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5">
                    <path d="M4 7h16M7 12h10M10 17h4" />
                </svg>
                Filter <span class="hidden text-[var(--koku-muted)] sm:inline">the collection</span>
                @if ($this->activeFilterCount > 0)
                    <span
                        class="flex size-5 items-center justify-center bg-[var(--koku-indigo)] text-[9px] text-white">{{ $this->activeFilterCount }}</span>
                @endif
            </button>

            <div class="pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-center">
                <h1 class="font-serif text-xl font-medium sm:text-2xl">@if ($search !== '') Results @else All watches
                @endif</h1>
                <p class="mt-1 text-[9px] uppercase tracking-[0.14em] text-[var(--koku-muted)]">{{ $products->total() }}
                    pieces</p>
            </div>

            <div class="relative" @click.outside="sortOpen = false">
                <button type="button" @click="sortOpen = !sortOpen"
                    class="flex h-14 items-center gap-3 text-[10px] font-medium uppercase tracking-[0.12em]">
                    <span class="hidden text-[var(--koku-muted)] sm:inline">Sort</span>
                    <span>{{ $sortOptions[$sort] ?? 'Newest first' }}</span>
                    <svg class="size-3 transition-transform" :class="sortOpen && 'rotate-180'" viewBox="0 0 16 16"
                        fill="none" stroke="currentColor">
                        <path d="m3 6 5 5 5-5" />
                    </svg>
                </button>
                <div x-show="sortOpen" x-transition x-cloak
                    class="absolute right-0 top-full w-64 border border-[var(--koku-line)] bg-[var(--koku-white)] p-2 shadow-xl">
                    @foreach ($sortOptions as $value => $label)
                        <button type="button" wire:click="$set('sort', '{{ $value }}')" @click="sortOpen = false"
                            class="flex w-full items-center justify-between px-3 py-3 text-left text-sm transition-colors hover:bg-[var(--koku-paper)] {{ $sort === $value ? 'text-[var(--koku-indigo)]' : 'text-[var(--koku-muted)]' }}">
                            {{ $label }}
                            @if ($sort === $value)<span aria-hidden="true">—</span>@endif
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @if ($this->activeFilterCount > 0 || $search !== '')
        <div class="border-b border-[var(--koku-line)] bg-[var(--koku-white)]">
            <div class="koku-shell flex flex-wrap items-center gap-2 py-4">
                @if ($search !== '')
                    <button wire:click="$set('search', '')"
                        class="flex items-center gap-2 border border-[var(--koku-line)] px-3 py-2 text-xs">Search: {{ $search }}
                        <span>×</span></button>
                @endif
                @foreach ($genders as $value)<button
                    wire:click="$set('genders', {{ Js::from(array_values(array_diff($genders, [$value]))) }})"
                    class="flex items-center gap-2 border border-[var(--koku-line)] px-3 py-2 text-xs capitalize">{{ $value }}
                <span>×</span></button>@endforeach
                @foreach ($movements as $value)<button
                    wire:click="$set('movements', {{ Js::from(array_values(array_diff($movements, [$value]))) }})"
                    class="flex items-center gap-2 border border-[var(--koku-line)] px-3 py-2 text-xs capitalize">{{ $value }}
                <span>×</span></button>@endforeach
                @foreach ($brands as $value)<button
                    wire:click="$set('brands', {{ Js::from(array_values(array_diff($brands, [$value]))) }})"
                    class="flex items-center gap-2 border border-[var(--koku-line)] px-3 py-2 text-xs capitalize">{{ str_replace('-', ' ', $value) }}
                <span>×</span></button>@endforeach
                @foreach ($categories as $value)<button
                    wire:click="$set('categories', {{ Js::from(array_values(array_diff($categories, [$value]))) }})"
                    class="flex items-center gap-2 border border-[var(--koku-line)] px-3 py-2 text-xs capitalize">{{ str_replace('-', ' ', $value) }}
                <span>×</span></button>@endforeach
                @if ($this->activeFilterCount > 0)<button wire:click="clearFilters"
                class="ml-2 text-xs text-[var(--koku-muted)] underline underline-offset-4">Clear filters</button>@endif
            </div>
        </div>
    @endif

    <main class="koku-shell py-12 sm:py-16 lg:py-20">
        @if ($products->isEmpty())
            <div
                class="flex min-h-[28rem] flex-col items-center justify-center border-y border-[var(--koku-line)] text-center">
                <span class="font-serif text-5xl text-[var(--koku-indigo)]">零</span>
                <h2 class="mt-6 font-serif text-3xl font-medium tracking-[-0.04em]">Nothing in this edit.</h2>
                <p class="mt-3 max-w-sm text-sm leading-6 text-[var(--koku-muted)]">Adjust the filters or clear the search
                    to return to the complete collection.</p>
                @if ($search !== '')
                    <button type="button" wire:click="$set('search', '')" class="koku-link mt-8">Clear search
                        <span>→</span></button>
                @else
                    <button type="button" wire:click="clearFilters" class="koku-link mt-8">Clear filters <span>→</span></button>
                @endif
            </div>
        @else
            <div class="grid grid-cols-2 gap-x-4 gap-y-14 sm:gap-x-8 sm:gap-y-20 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-10">
                @foreach ($products as $product)
                    <article wire:key="product-{{ $product->id }}" class="group min-w-0 text-center">
                        <a href="{{ route('shop.product', $product->slug) }}" class="relative block h-60 sm:h-72 lg:h-80">
                            @if ($product->primary_image_url)
                                <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" loading="lazy"
                                    class="koku-product-image h-full w-full object-contain px-3 py-2 transition-transform duration-500 group-hover:scale-[1.025] sm:px-5">
                            @else
                                <div class="flex h-full items-center justify-center font-serif text-3xl text-[var(--koku-line)]">
                                    Koku</div>
                            @endif
                            @if ($product->is_featured)
                                <span
                                    class="absolute left-0 top-0 text-[8px] font-medium uppercase tracking-[0.15em] text-[var(--koku-indigo)]">Selected</span>
                            @endif
                            <button type="button" wire:click.stop.prevent="toggleWishlist({{ $product->id }})"
                                aria-label="Toggle {{ $product->name }} in wishlist"
                                class="absolute right-0 top-0 flex size-8 items-center justify-center text-[var(--koku-ink)] transition-colors hover:text-[var(--koku-indigo)]">
                                <svg class="size-4" fill="{{ in_array($product->id, $this->wishlistedProductIds) ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733C11.285 4.876 9.623 3.75 7.687 3.75 5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
                            </button>
                        </a>
                        <div class="mt-5 px-1">
                            <div class="text-center">
                                <div class="min-w-0">
                                    <p
                                        class="truncate text-[8px] font-medium uppercase tracking-[0.14em] text-[var(--koku-muted)]">
                                        {{ $product->brand?->name }}</p>
                                    <h2 class="mt-2 truncate text-xs font-medium sm:text-sm">{{ $product->name }}</h2>
                                </div>
                                @if ($product->variants_min_price)<span
                                class="mt-2 block text-[11px] text-[var(--koku-muted)]">${{ number_format($product->variants_min_price, 2) }}</span>@endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            @if ($products->hasPages())
                <div class="mt-20 border-t border-[var(--koku-line)] pt-8 lg:mt-28">{{ $products->links() }}</div>
            @endif
        @endif
    </main>

    <div x-show="showFilters" x-transition.opacity x-cloak @click="showFilters = false"
        class="fixed inset-0 z-[60] bg-[#11130f]/55"></div>
    <aside x-show="showFilters" x-cloak @keydown.escape.window="showFilters = false"
        x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0" x-transition:leave="transition duration-200 ease-in"
        x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
        class="fixed inset-y-0 right-0 z-[70] flex w-full max-w-lg flex-col bg-[var(--koku-white)] text-[var(--koku-ink)]">
        <div class="flex h-20 shrink-0 items-center justify-between border-b border-[var(--koku-line)] px-6 sm:px-8">
            <div>
                <p class="koku-eyebrow text-[var(--koku-indigo)]">Refine</p>
                <h2 class="mt-1 font-serif text-xl font-medium">The collection</h2>
            </div>
            <div class="flex items-center gap-4">
                @if ($this->activeFilterCount > 0)<button type="button" wire:click="clearFilters"
                class="text-xs underline underline-offset-4 text-[var(--koku-muted)]">Reset</button>@endif
                <button type="button" @click="showFilters = false" class="koku-icon-button"
                    aria-label="Close filters"><svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <path d="m5 5 14 14M19 5 5 19" />
                    </svg></button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto px-6 sm:px-8"
            x-data="{ open: { price: true, gender: true, brand: true, category: false, movement: false } }">
            <div class="border-b border-[var(--koku-line)] py-7"
                x-data="{ min: @entangle('minPrice'), max: @entangle('maxPrice'), floor: {{ $priceFloor }}, ceil: {{ $priceCeil }}, timer: null, pct(v) { return this.ceil === this.floor ? 0 : ((v - this.floor) / (this.ceil - this.floor)) * 100 }, sync() { clearTimeout(this.timer); this.timer = setTimeout(() => { $wire.set('minPrice', this.min); $wire.set('maxPrice', this.max) }, 400) } }">
                <button type="button" @click="open.price = !open.price"
                    class="flex w-full items-center justify-between font-serif text-lg"><span>Price</span><span
                        class="text-[var(--koku-indigo)]" x-text="open.price ? '−' : '+'"></span></button>
                <div x-show="open.price" x-collapse class="pt-7">
                    <div class="relative mb-7 h-px bg-[var(--koku-line)]">
                        <div class="absolute h-px bg-[var(--koku-indigo)]"
                            :style="`left:${pct(min)}%;right:${100-pct(max)}%`"></div>
                        <input type="range" :min="floor" :max="ceil" step="1" x-model.number="min"
                            @input="if(min>max)min=max;sync()"
                            class="range-thumb pointer-events-none absolute inset-0 h-px w-full appearance-none bg-transparent">
                        <input type="range" :min="floor" :max="ceil" step="1" x-model.number="max"
                            @input="if(max<min)max=min;sync()"
                            class="range-thumb pointer-events-none absolute inset-0 h-px w-full appearance-none bg-transparent">
                    </div>
                    <div class="flex justify-between text-xs"><span>$<span x-text="min"></span></span><span>$<span
                                x-text="max"></span></span></div>
                </div>
            </div>

            @foreach ([
                    ['key' => 'gender', 'title' => 'Wearer', 'selected' => count($genders), 'items' => collect(['men' => 'Men', 'women' => 'Women', 'unisex' => 'Unisex']), 'model' => 'genders', 'counts' => $genderCounts],
                    ['key' => 'brand', 'title' => 'Maker', 'selected' => count($brands), 'items' => $brandOptions->pluck('name', 'slug'), 'model' => 'brands', 'counts' => $brandCounts],
                    ['key' => 'category', 'title' => 'Style', 'selected' => count($categories), 'items' => $categoryOptions->pluck('name', 'slug'), 'model' => 'categories', 'counts' => $categoryCounts],
                    ['key' => 'movement', 'title' => 'Movement', 'selected' => count($movements), 'items' => collect(['automatic' => 'Automatic', 'quartz' => 'Quartz', 'mechanical' => 'Mechanical', 'chronograph' => 'Chronograph', 'smart' => 'Smart']), 'model' => 'movements', 'counts' => $movementCounts],
                ] as $section)
                <div class="border-b border-[var(--koku-line)] py-7">
                    <button type="button" @click="open.{{ $section['key'] }} = !open.{{ $section['key'] }}"
                        class="flex w-full items-center justify-between font-serif text-lg">
                        <span>{{ $section['title'] }} @if ($section['selected'])<small
                        class="ml-1 font-sans text-[10px] text-[var(--koku-indigo)]">{{ $section['selected'] }}</small>@endif</span>
                        <span class="text-[var(--koku-indigo)]" x-text="open.{{ $section['key'] }} ? '−' : '+'"></span>
                    </button>
                    <div x-show="open.{{ $section['key'] }}" x-collapse class="space-y-4 pt-6">
                        @foreach ($section['items'] as $value => $label)
                            <label class="flex cursor-pointer items-center justify-between gap-4 text-sm">
                                <span class="flex items-center gap-3"><input type="checkbox"
                                        wire:model.live="{{ $section['model'] }}" value="{{ $value }}"
                                        class="size-4 rounded-none border-[var(--koku-line)] text-[var(--koku-indigo)] focus:ring-[var(--koku-indigo)]">{{ $label }}</span>
                                <span class="text-xs text-[var(--koku-muted)]">{{ $section['counts'][$value] ?? 0 }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="shrink-0 border-t border-[var(--koku-line)] p-6 sm:p-8">
            <button type="button" @click="showFilters = false"
                class="w-full bg-[var(--koku-indigo)] px-5 py-4 text-xs font-medium uppercase tracking-[0.14em] text-white transition-colors hover:bg-[var(--koku-indigo-deep)]">Show
                {{ $products->total() }} {{ Str::plural('watch', $products->total()) }}</button>
        </div>
    </aside>
</div>
