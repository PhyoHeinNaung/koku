<div>

    {{-- Breadcrumb --}}
    <div class="px-6 sm:px-10 lg:px-16 pt-6">
        <nav class="text-xs text-gray-500 tracking-wide uppercase">
            <a href="{{ route('home') }}" class="hover:text-gray-900">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('shop.index') }}" class="hover:text-gray-900">Shop</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900">{{ $product->name }}</span>
        </nav>
    </div>

    <div class="px-6 sm:px-10 lg:px-16 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-16 gap-y-10">

            {{-- Image gallery --}}
            <div wire:key="gallery-{{ $selectedVariantId }}" x-data="{
                    images: @js($this->galleryImages),
                    active: {{ $this->galleryActiveIndex }},
                    lightbox: false,
                    next() { this.active = (this.active + 1) % this.images.length },
                    prev() { this.active = (this.active - 1 + this.images.length) % this.images.length },
                }">

                <div
                    class="relative aspect-square w-full bg-white border border-gray-100 rounded-lg overflow-hidden mb-4 flex items-center justify-center group">
                    <template x-if="images.length">
                        <img :src="images[active]" @click="lightbox = true"
                            class="w-full h-full object-contain p-8 cursor-zoom-in">
                    </template>
                    <template x-if="!images.length">
                        <span class="text-gray-300 text-sm">No image</span>
                    </template>

                    <template x-if="images.length">
                        <button type="button" @click="lightbox = true"
                            class="absolute top-3 right-3 p-2 bg-white/90 rounded-full shadow opacity-0 group-hover:opacity-100 transition-opacity"
                            aria-label="Zoom">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-700" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0zM10.5 8v3m0 0v3m0-3h3m-3 0h-3" />
                            </svg>
                        </button>
                    </template>

                    <template x-if="images.length > 1">
                        <button type="button" @click="prev()"
                            class="absolute left-2 top-1/2 -translate-y-1/2 p-2 bg-white/90 rounded-full shadow opacity-0 group-hover:opacity-100 transition-opacity"
                            aria-label="Previous image">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-700" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                    </template>

                    <template x-if="images.length > 1">
                        <button type="button" @click="next()"
                            class="absolute right-2 top-1/2 -translate-y-1/2 p-2 bg-white/90 rounded-full shadow opacity-0 group-hover:opacity-100 transition-opacity"
                            aria-label="Next image">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-700" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </template>

                </div>

                <template x-if="images.length > 1">
                    <div class="flex gap-3 max-w-[520px] mx-auto">
                        <template x-for="(img, idx) in images" :key="idx">
                            <button type="button" @click="active = idx"
                                class="w-16 h-16 rounded-lg overflow-hidden border-2 bg-gray-50 flex items-center justify-center shrink-0"
                                :class="active === idx ? 'border-gray-900' : 'border-transparent hover:border-gray-300'">
                                <img :src="img" class="w-full h-full object-contain p-1.5">
                            </button>
                        </template>
                    </div>
                </template>

                {{-- Lightbox --}}
                <div x-show="lightbox" x-cloak x-transition.opacity @keydown.escape.window="lightbox = false"
                    @click="lightbox = false" class="fixed inset-0 bg-black/90 z-[60] flex items-center justify-center">

                    <button type="button" @click.stop="lightbox = false"
                        class="absolute top-6 right-6 p-2 text-white/80 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <template x-if="images.length > 1">
                        <button type="button" @click.stop="prev()"
                            class="absolute left-6 top-1/2 -translate-y-1/2 p-2 text-white/80 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                    </template>

                    <img :src="images[active]" @click="lightbox = true"
                        class="w-full h-full object-contain p-4 cursor-zoom-in">

                    <template x-if="images.length > 1">
                        <button type="button" @click.stop="next()"
                            class="absolute right-6 top-1/2 -translate-y-1/2 p-2 text-white/80 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </template>

                </div>

            </div>

            {{-- Details column --}}
            <div class="max-w-lg">
                <p class="text-sm text-gray-500 uppercase tracking-widest">{{ $product->brand->name }}</p>
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mt-1">{{ $product->name }}</h1>

                @if ($this->selectedVariant)
                    <p class="text-xl text-gray-900 mt-4">
                        ${{ number_format($this->selectedVariant->price, 2) }}
                        @if ($this->selectedVariant->compare_price)
                            <span class="text-base text-gray-400 line-through ml-2">
                                ${{ number_format($this->selectedVariant->compare_price, 2) }}
                            </span>
                        @endif
                    </p>
                @endif

                <button type="button" wire:click="toggleWishlist({{ $product->id }})"
                    class="mt-3 inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                        fill="{{ $this->isWishlisted($product->id) ? 'currentColor' : 'none' }}" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                    </svg>
                    {{ $this->isWishlisted($product->id) ? 'Saved to Wishlist' : 'Add to Wishlist' }}
                </button>

                {{-- Variant swatches --}}
                @if ($this->activeVariants->count() > 1)
                    <div class="mt-6" x-data="{ showAll: @entangle('showAllVariants') }">
                        <p class="text-sm font-medium text-gray-700 mb-3">
                            Available in {{ $this->activeVariants->count() }}
                            {{ Str::plural('variation', $this->activeVariants->count()) }}
                        </p>

                        <div class="flex flex-wrap items-center gap-3">
                            @foreach ($this->activeVariants->take(4) as $variant)
                                <button type="button" wire:click="selectVariant({{ $variant->id }})"
                                    class="w-14 h-14 rounded-lg border-2 overflow-hidden bg-gray-50 flex items-center justify-center transition-colors
                                                                                                    {{ $selectedVariantId === $variant->id ? 'border-gray-900' : 'border-transparent hover:border-gray-300' }}">
                                    @if ($variant->primary_image)
                                        <img src="{{ Storage::url($variant->primary_image->image_url) }}"
                                            class="w-full h-full object-contain p-1">
                                    @else
                                        <span class="text-[10px] text-gray-400 px-1 text-center">{{ $variant->name }}</span>
                                    @endif
                                </button>
                            @endforeach

                            @if ($this->activeVariants->count() > 4)
                                <button type="button" @click="showAll = true"
                                    class="w-14 h-14 rounded-lg border border-gray-200 flex items-center justify-center text-sm font-medium text-gray-600 hover:border-gray-400">
                                    +{{ $this->activeVariants->count() - 4 }}
                                </button>
                            @endif
                        </div>

                        <p class="text-sm text-gray-500 mt-3">{{ $this->selectedVariant?->name }}</p>

                        <div x-show="showAll" x-transition.opacity x-cloak @click="showAll = false"
                            class="fixed inset-0 bg-black/40 z-40"></div>

                        <div x-show="showAll" x-cloak @keydown.escape.window="showAll = false"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
                            x-transition:leave-end="translate-x-full"
                            class="fixed top-24 bottom-4 right-4 w-full max-w-sm bg-white z-50 flex flex-col rounded-2xl shadow-2xl overflow-hidden">

                            <div class="flex items-center justify-between px-6 h-16 border-b border-gray-200 shrink-0">
                                <h2 class="text-base font-semibold text-gray-900">
                                    {{ $this->activeVariants->count() }} Variations
                                </h2>
                                <button type="button" @click="showAll = false"
                                    class="p-1.5 border border-gray-200 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div class="flex-1 overflow-y-auto p-6">
                                <div class="grid grid-cols-3 gap-4">
                                    @foreach ($this->activeVariants as $variant)
                                        <button type="button" wire:click="selectVariant({{ $variant->id }})" class="text-left">
                                            <div
                                                class="w-full aspect-square rounded-lg border-2 overflow-hidden bg-gray-50 flex items-center justify-center
                                                                                                                                                            {{ $selectedVariantId === $variant->id ? 'border-gray-900' : 'border-transparent' }}">
                                                @if ($variant->primary_image)
                                                    <img src="{{ Storage::url($variant->primary_image->image_url) }}"
                                                        class="w-full h-full object-contain p-2">
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-600 mt-2">{{ $variant->name }}</p>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($this->selectedVariant)
                    <p class="text-sm text-gray-500 mt-4">
                        {{ $this->selectedVariant->stock_quantity > 0 ? $this->selectedVariant->stock_quantity . ' in stock' : 'Out of stock' }}
                    </p>
                @endif

                {{-- Actions --}}
                <div class="mt-4 space-y-3">
                    <button type="button" wire:click="addToCart({{ $selectedVariantId }})"
                        class="w-full py-3 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 disabled:opacity-40"
                        {{ !$this->selectedVariant || $this->selectedVariant->stock_quantity < 1 ? 'disabled' : '' }}>
                        Add to Cart
                    </button>
                    <button type="button" wire:click="buyNow"
                        class="w-full py-3 border border-gray-900 text-gray-900 text-sm font-medium rounded-lg hover:bg-gray-50 disabled:opacity-40"
                        {{ !$this->selectedVariant || $this->selectedVariant->stock_quantity < 1 ? 'disabled' : '' }}>
                        Buy It Now
                    </button>
                </div>

                {{-- Accordion --}}
                <div class="mt-10 pt-8 border-t border-gray-200" x-data="{ open: 'description' }">

                    <div class="border-b border-gray-200">
                        <button type="button" @click="open = open === 'description' ? null : 'description'"
                            class="w-full flex items-center justify-between py-4 text-left">
                            <span class="text-sm font-semibold text-gray-900">Description</span>
                            <svg class="h-4 w-4 text-gray-400 transition-transform"
                                :class="open === 'description' ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open === 'description'" x-collapse
                            class="pb-4 text-sm text-gray-600 leading-relaxed">
                            {{ $product->description }}
                        </div>
                    </div>

                    <div class="border-b border-gray-200">
                        <button type="button" @click="open = open === 'category' ? null : 'category'"
                            class="w-full flex items-center justify-between py-4 text-left">
                            <span class="text-sm font-semibold text-gray-900">Product Details</span>
                            <svg class="h-4 w-4 text-gray-400 transition-transform"
                                :class="open === 'category' ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open === 'category'" x-collapse class="pb-5">
                            <div class="grid grid-cols-2 gap-x-6 gap-y-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 mb-1">Category</p>
                                    <p class="text-sm text-gray-500">{{ $product->category->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 mb-1">Movement</p>
                                    <p class="text-sm text-gray-500 capitalize">{{ $product->movement }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 mb-1">Gender</p>
                                    <p class="text-sm text-gray-500 capitalize">{{ $product->gender }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @foreach ($this->specGroups as $groupName => $fields)
                        <div class="border-b border-gray-200">
                            <button type="button"
                                @click="open = open === '{{ Str::slug($groupName) }}' ? null : '{{ Str::slug($groupName) }}'"
                                class="w-full flex items-center justify-between py-4 text-left">
                                <span class="text-sm font-semibold text-gray-900">{{ $groupName }}</span>
                                <svg class="h-4 w-4 text-gray-400 transition-transform"
                                    :class="open === '{{ Str::slug($groupName) }}' ? 'rotate-180' : ''"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open === '{{ Str::slug($groupName) }}'" x-collapse class="pb-5">
                                <div class="grid grid-cols-2 gap-x-6 gap-y-4">
                                    @foreach ($fields as $label => $value)
                                        <div>
                                            @if (is_string($label))
                                                <p class="text-sm font-medium text-gray-900 mb-1">{{ $label }}</p>
                                                <p class="text-sm text-gray-500">{{ $value }}</p>
                                            @else
                                                <p class="text-sm text-gray-500">{{ $value }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

            </div>

        </div>
    </div>

</div>