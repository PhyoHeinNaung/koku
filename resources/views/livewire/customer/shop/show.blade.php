<div class="bg-white">
    <div class="koku-shell py-5 sm:py-7">
        <nav class="koku-eyebrow flex min-w-0 items-center gap-2 text-[var(--koku-muted)]" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-[var(--koku-indigo)]">Koku</a><span>/</span>
            <a href="{{ route('shop.index') }}" class="hover:text-[var(--koku-indigo)]">Watches</a><span>/</span>
            <span class="truncate text-[var(--koku-ink)]">{{ $product->name }}</span>
        </nav>
    </div>

    <section class="koku-shell max-w-[82rem] bg-white pb-14 lg:grid lg:grid-cols-[minmax(0,1.06fr)_minmax(23rem,.94fr)] lg:gap-20 lg:pb-20">
        <div wire:key="gallery-{{ $selectedVariantId }}"
            x-data="{ images: @js($this->galleryImages), active: {{ $this->galleryActiveIndex }}, lightbox: false, next(){ if(this.images.length) this.active=(this.active+1)%this.images.length }, prev(){ if(this.images.length) this.active=(this.active-1+this.images.length)%this.images.length } }">
            <div class="grid gap-3 sm:grid-cols-[4.5rem_minmax(0,1fr)]">
                <div class="order-2 flex gap-2 overflow-x-auto py-3 sm:order-1 sm:flex sm:max-h-[42rem] sm:flex-col sm:gap-3 sm:space-y-0 sm:overflow-y-auto sm:py-0">
                    <template x-for="(image, index) in images" :key="index">
                        <button type="button" @click="active=index" class="relative size-16 shrink-0 overflow-hidden bg-[#f6f6f6] sm:h-[4.5rem] sm:w-full"
                            :class="active===index ? 'after:absolute after:inset-0 after:border after:border-[var(--koku-indigo)]' : ''">
                            <img :src="image" alt="" class="h-full w-full object-contain p-1.5">
                        </button>
                    </template>
                </div>

                <div class="order-1 relative h-[30rem] overflow-hidden bg-[#f6f6f6] sm:order-2 sm:h-[38rem] lg:h-[42rem]">
                    <template x-if="images.length"><img :src="images[active]" @click="lightbox=true" alt="{{ $product->name }}" class="h-full w-full cursor-zoom-in object-contain p-6 sm:p-10 lg:p-12"></template>
                    <template x-if="!images.length"><div class="flex h-full items-center justify-center font-serif text-5xl text-[var(--koku-line)]">Koku</div></template>
                    <div class="absolute bottom-4 left-4 flex items-center bg-white text-xs">
                        <button type="button" @click="prev()" class="flex size-11 items-center justify-center border-r border-[var(--koku-line)]" aria-label="Previous image">←</button>
                        <span class="min-w-16 px-3 text-center font-serif"><span x-text="String(active+1).padStart(2,'0')"></span> / <span x-text="String(images.length).padStart(2,'0')"></span></span>
                        <button type="button" @click="next()" class="flex size-11 items-center justify-center border-l border-[var(--koku-line)]" aria-label="Next image">→</button>
                    </div>
                    <button type="button" @click="lightbox=true" class="koku-icon-button absolute right-4 top-4 bg-white" aria-label="Enlarge image">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="10.5" cy="10.5" r="6.5"/><path d="m15.5 15.5 5 5M7.5 10.5h6m-3-3v6"/></svg>
                    </button>
                </div>
            </div>

            <div x-show="lightbox" x-cloak x-transition.opacity @keydown.escape.window="lightbox=false" class="fixed inset-0 z-[80] bg-[#10110f]/96 text-white">
                <button type="button" @click="lightbox=false" class="absolute right-5 top-5 z-10 flex size-12 items-center justify-center border border-white/25" aria-label="Close image"><svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="m5 5 14 14M19 5 5 19"/></svg></button>
                <button type="button" @click="prev()" class="absolute left-4 top-1/2 z-10 flex size-12 -translate-y-1/2 items-center justify-center border border-white/25 sm:left-8" aria-label="Previous image">←</button>
                <img :src="images[active]" alt="{{ $product->name }} enlarged" class="h-full w-full object-contain p-8 sm:p-16">
                <button type="button" @click="next()" class="absolute right-4 top-1/2 z-10 flex size-12 -translate-y-1/2 items-center justify-center border border-white/25 sm:right-8" aria-label="Next image">→</button>
            </div>
        </div>

        <div class="relative">
            <div class="py-8 sm:py-10 lg:sticky lg:top-[7rem] lg:py-8">
                <div class="flex items-center justify-between pb-2">
                    <p class="koku-eyebrow text-[var(--koku-indigo)]">{{ $product->brand?->name }}</p>
                    <p class="font-serif text-sm text-[var(--koku-muted)]">{{ $product->category?->name }}</p>
                </div>

                <div class="mt-8 flex items-start justify-between gap-6">
                    <h1 class="max-w-[14ch] font-serif text-4xl font-medium leading-[1.06] tracking-[-0.05em] sm:text-5xl">{{ $product->name }}</h1>
                    <button type="button" wire:click="toggleWishlist({{ $product->id }})" class="mt-1 flex size-10 shrink-0 items-center justify-center text-[var(--koku-indigo)] transition-colors hover:text-[var(--koku-indigo-deep)]" aria-label="Toggle wishlist">
                        <svg class="size-5" fill="{{ $this->isWishlisted($product->id) ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733C11.285 4.876 9.623 3.75 7.687 3.75 5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
                    </button>
                </div>
                @if ($this->selectedVariant)
                    <div class="mt-6 flex items-baseline gap-3">
                        <p class="font-serif text-2xl">${{ number_format($this->selectedVariant->price, 2) }}</p>
                        @if ($this->selectedVariant->compare_price)<p class="text-sm text-[var(--koku-muted)] line-through">${{ number_format($this->selectedVariant->compare_price, 2) }}</p>@endif
                    </div>
                @endif

                @if ($this->activeVariants->count() > 1)
                    <div class="mt-9" x-data="{ showAll: @entangle('showAllVariants') }">
                        <div class="mb-4 flex items-center justify-between"><p class="koku-eyebrow text-[var(--koku-muted)]">Variation · {{ $this->selectedVariant?->name }}</p></div>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($this->activeVariants->take(4) as $variant)
                                <button type="button" wire:click="selectVariant({{ $variant->id }})" title="{{ $variant->name }}" class="size-16 overflow-hidden bg-[#f6f6f6] {{ $selectedVariantId === $variant->id ? 'border border-[var(--koku-indigo)]' : 'border border-transparent hover:border-[var(--koku-line)]' }}">
                                    @if ($variant->primary_image)<img src="{{ Storage::url($variant->primary_image->image_url) }}" alt="{{ $variant->name }}" class="h-full w-full object-contain p-1.5">@else<span class="text-[9px]">{{ $variant->name }}</span>@endif
                                </button>
                            @endforeach
                            @if ($this->activeVariants->count() > 4)
                                <button type="button" @click="showAll=true" class="flex size-16 items-center justify-center bg-[#f5f5f5] text-2xl font-light text-[var(--koku-indigo)]" aria-label="View all {{ $this->activeVariants->count() }} variations">+</button>
                            @endif
                        </div>

                        <div x-show="showAll" x-cloak @click="showAll=false" class="fixed inset-0 z-[60] bg-black/50"></div>
                        <aside x-show="showAll" x-cloak x-transition:enter="transition duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="fixed inset-y-0 right-0 z-[70] w-full max-w-md overflow-y-auto bg-white p-6 pt-28 sm:p-8 sm:pt-28">
                            <div class="flex items-center justify-between border-b border-[var(--koku-line)] pb-6"><div><p class="koku-eyebrow text-[var(--koku-indigo)]">Choose</p><h2 class="mt-2 font-serif text-2xl">{{ $this->activeVariants->count() }} variations</h2></div><button @click="showAll=false" class="koku-icon-button" aria-label="Close"><span class="text-xl">×</span></button></div>
                            <div class="mt-8 grid grid-cols-2 gap-5">
                                @foreach ($this->activeVariants as $variant)
                                    <button type="button" wire:click="selectVariant({{ $variant->id }})" class="text-left">
                                        <div class="aspect-square overflow-hidden bg-[var(--koku-paper)] {{ $selectedVariantId === $variant->id ? 'border border-[var(--koku-indigo)]' : '' }}">@if ($variant->primary_image)<img src="{{ Storage::url($variant->primary_image->image_url) }}" alt="{{ $variant->name }}" class="h-full w-full object-cover">@endif</div>
                                        <p class="mt-3 text-xs">{{ $variant->name }}</p><p class="mt-1 text-xs text-[var(--koku-muted)]">${{ number_format($variant->price, 2) }}</p>
                                    </button>
                                @endforeach
                            </div>
                        </aside>
                    </div>
                @endif

                @if ($this->selectedVariant)
                    <div class="mt-8 flex items-center justify-between py-3 text-xs">
                        <span class="text-[var(--koku-muted)]">Availability</span>
                        <span class="flex items-center gap-2"><i class="size-1.5 {{ $this->selectedVariant->stock_quantity > 0 ? 'bg-[var(--koku-indigo)]' : 'bg-red-700' }}"></i>{{ $this->selectedVariant->stock_quantity > 0 ? $this->selectedVariant->stock_quantity.' in stock' : 'Out of stock' }}</span>
                    </div>
                @endif

                <div class="mt-7">
                    <p class="koku-eyebrow mb-3 text-[var(--koku-muted)]">Quantity</p>
                    <div class="grid h-12 w-28 grid-cols-3 bg-[#f5f5f5] text-sm">
                        <button type="button" wire:click="decrementQuantity" class="hover:bg-[var(--koku-paper)]" aria-label="Decrease quantity">−</button><span class="flex items-center justify-center border-x border-[var(--koku-line)]">{{ $quantity }}</span><button type="button" wire:click="incrementQuantity" class="hover:bg-[var(--koku-paper)]" aria-label="Increase quantity">+</button>
                    </div>
                </div>
                <button type="button" wire:click="addSelectedToCart" wire:loading.attr="disabled" class="mt-5 h-12 w-full bg-[var(--koku-indigo)] px-5 text-xs font-medium uppercase tracking-[0.13em] text-white transition-colors hover:bg-[var(--koku-indigo-deep)] disabled:opacity-40" {{ !$this->selectedVariant || $this->selectedVariant->stock_quantity < 1 ? 'disabled' : '' }}>Add to cart</button>
                <button type="button" wire:click="buyNow" class="mt-3 h-12 w-full border border-[var(--koku-indigo)] bg-white px-5 text-xs font-medium uppercase tracking-[0.13em] text-[var(--koku-indigo)] transition-colors hover:bg-[var(--koku-indigo)] hover:text-white disabled:opacity-40" {{ !$this->selectedVariant || $this->selectedVariant->stock_quantity < 1 ? 'disabled' : '' }}>Buy now</button>

                <div class="mt-9 flex gap-8 text-[9px] uppercase tracking-[0.12em] text-[var(--koku-muted)]"><span>Complimentary delivery</span><span>Considered returns</span></div>
            </div>
        </div>
    </section>

    <section class="koku-shell bg-white py-20 sm:py-24 lg:py-28" x-data="{ open: 'description' }">
        <div class="grid gap-16 lg:grid-cols-[.8fr_1.2fr] lg:gap-24">
            <div>
                <p class="koku-eyebrow text-[var(--koku-indigo)]">The object</p>
                <h2 class="mt-5 font-serif text-4xl font-medium leading-tight tracking-[-0.045em] sm:text-5xl">Made to accompany time.</h2>
                <p class="mt-8 max-w-md text-sm leading-7 text-[var(--koku-muted)]">{{ $product->description ?: 'Designed with restraint and selected for daily use, this watch balances visual clarity with dependable construction.' }}</p>
            </div>
            <div class="border-t border-[var(--koku-ink)]">
                @foreach (array_merge(['Description' => ['Story' => $product->description ?: 'A considered expression of watchmaking, selected by Koku.'], 'Product details' => ['Category' => $product->category?->name, 'Movement' => ucfirst($product->movement), 'Wearer' => ucfirst($product->gender)]], $this->specGroups) as $groupName => $fields)
                    <div class="border-b border-[var(--koku-line)]">
                        <button type="button" @click="open = open === '{{ Str::slug($groupName) }}' ? null : '{{ Str::slug($groupName) }}'" class="flex w-full items-center justify-between py-6 text-left">
                            <span class="font-serif text-lg">{{ $groupName }}</span><span class="text-[var(--koku-indigo)]" x-text="open === '{{ Str::slug($groupName) }}' ? '−' : '+'"></span>
                        </button>
                        <div x-show="open === '{{ Str::slug($groupName) }}'" x-collapse class="grid gap-x-8 gap-y-5 pb-7 sm:grid-cols-2">
                            @foreach ($fields as $label => $value)
                                @if (filled($value))<div class="text-sm"><span class="block text-xs text-[var(--koku-muted)]">{{ is_string($label) ? $label : 'Detail' }}</span><span class="mt-1.5 block leading-6">{{ $value }}</span></div>@endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @if ($relatedProducts->isNotEmpty())
        <section class="border-t border-[var(--koku-line)] bg-white py-20 sm:py-24">
            <div class="koku-shell">
                <div class="flex items-end justify-between border-b border-[var(--koku-line)] pb-6"><div><p class="koku-eyebrow text-[var(--koku-muted)]">Continue looking</p><h2 class="mt-3 font-serif text-3xl font-medium tracking-[-0.04em]">Related watches</h2></div><a href="{{ route('shop.index') }}" class="koku-link hidden sm:inline-flex">View all <span>→</span></a></div>
                <div class="mt-10 grid grid-cols-2 gap-4 sm:gap-7 lg:grid-cols-3">
                    @foreach ($relatedProducts as $related)
                        <a href="{{ route('shop.product', $related) }}" class="group">
                            <div class="h-64 overflow-hidden bg-[#f6f6f6]">@if ($related->primary_image_url)<img src="{{ $related->primary_image_url }}" alt="{{ $related->name }}" loading="lazy" class="koku-product-image h-full w-full object-contain p-6">@endif</div>
                            <div class="mt-4 border-t border-[var(--koku-line)] pt-4"><p class="koku-eyebrow text-[var(--koku-muted)]">{{ $related->brand?->name }}</p><div class="mt-2 flex justify-between gap-4"><h3 class="font-serif">{{ $related->name }}</h3>@if ($related->variants_min_price)<span class="shrink-0 text-xs">${{ number_format($related->variants_min_price, 2) }}</span>@endif</div></div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
