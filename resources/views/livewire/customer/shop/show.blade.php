<div class="bg-white">
    <div class="koku-shell max-w-[82rem] py-5 sm:py-7">
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

                <div class="mt-8">
                    <h1 class="max-w-[14ch] font-serif text-4xl font-medium leading-[1.06] tracking-[-0.05em] sm:text-5xl">{{ $product->name }}</h1>
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
                                <button type="button" wire:click="selectVariant({{ $variant->id }})" title="{{ $variant->name }}" class="relative size-16 overflow-hidden rounded-xl transition {{ $selectedVariantId === $variant->id ? 'bg-[#e8ebf2] shadow-[0_0_0_2px_white,0_0_0_4px_var(--koku-indigo)]' : 'bg-[#f4f2ee] hover:-translate-y-0.5 hover:shadow-md' }}">
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
                                    <button type="button" wire:click="selectVariant({{ $variant->id }})" class="group/variant rounded-2xl p-2 text-left transition {{ $selectedVariantId === $variant->id ? 'bg-[#eef0f5] shadow-[0_12px_30px_rgba(41,61,104,.12)]' : 'hover:bg-[#f7f5f2]' }}">
                                        <div class="relative aspect-square overflow-hidden rounded-xl bg-[#f4f2ee]">@if ($variant->primary_image)<img src="{{ Storage::url($variant->primary_image->image_url) }}" alt="{{ $variant->name }}" class="h-full w-full object-contain p-3 transition group-hover/variant:scale-[1.03]">@endif @if($selectedVariantId === $variant->id)<span class="absolute right-2 top-2 flex size-6 items-center justify-center rounded-full bg-[var(--koku-indigo)] text-[10px] text-white shadow">✓</span>@endif</div>
                                        <p class="mt-3 text-xs font-medium">{{ $variant->name }}</p><p class="mt-1 text-xs text-[var(--koku-muted)]">${{ number_format($variant->price, 2) }}</p>
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

                <div class="mt-6 flex items-center justify-between border-y border-[var(--koku-line)] py-4">
                    <div><p class="koku-eyebrow text-[var(--koku-muted)]">Quantity</p><p class="mt-1 text-xs text-[var(--koku-muted)]">Choose up to available stock</p></div>
                    <div class="flex h-11 items-center border border-[var(--koku-line)] bg-white" aria-label="Quantity selector"><button type="button" wire:click="decrementQuantity" class="flex size-11 items-center justify-center text-lg hover:bg-[var(--koku-paper)]" aria-label="Decrease quantity">&minus;</button><output class="flex h-full min-w-11 items-center justify-center border-x border-[var(--koku-line)] font-serif text-sm">{{ $quantity }}</output><button type="button" wire:click="incrementQuantity" class="flex size-11 items-center justify-center text-lg hover:bg-[var(--koku-paper)]" aria-label="Increase quantity">+</button></div>
                </div>
                <div class="mt-5 grid grid-cols-[minmax(0,1fr)_3.5rem] gap-3">
                    <button type="button" wire:click="addSelectedToCart" wire:loading.attr="disabled" class="h-14 bg-[var(--koku-indigo)] px-5 text-xs font-medium uppercase tracking-[0.13em] text-white transition-colors hover:bg-[var(--koku-indigo-deep)] disabled:opacity-40" {{ !$this->selectedVariant || $this->selectedVariant->stock_quantity < 1 ? 'disabled' : '' }}>Add to cart</button>
                    <button type="button" wire:click="toggleWishlist({{ $product->id }})" class="flex h-14 items-center justify-center border border-[var(--koku-line)] bg-white text-[var(--koku-indigo)] hover:border-[var(--koku-indigo)] hover:bg-[var(--koku-paper)]" aria-label="{{ $this->isWishlisted($product->id) ? 'Remove from wishlist' : 'Add to wishlist' }}"><svg class="size-5" fill="{{ $this->isWishlisted($product->id) ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733C11.285 4.876 9.623 3.75 7.687 3.75 5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg></button>
                </div>
                <button type="button" wire:click="buyNow" class="mt-3 h-12 w-full border border-[var(--koku-indigo)] bg-white px-5 text-xs font-medium uppercase tracking-[0.13em] text-[var(--koku-indigo)] transition-colors hover:bg-[var(--koku-indigo)] hover:text-white disabled:opacity-40" {{ !$this->selectedVariant || $this->selectedVariant->stock_quantity < 1 ? 'disabled' : '' }}>Buy now</button>

                <div class="mt-9 flex gap-8 text-[9px] uppercase tracking-[0.12em] text-[var(--koku-muted)]"><span>Complimentary delivery</span><span>Considered returns</span></div>
            </div>
        </div>
    </section>

    <section class="border-y border-[var(--koku-line)]/55 bg-white py-16 sm:py-20 lg:py-24" x-data="{ open: null }">
        <div class="koku-shell max-w-[82rem]">
            <div class="grid gap-12 lg:grid-cols-[.72fr_1.28fr] lg:gap-20"><div><span class="text-[10px] font-medium uppercase tracking-[.17em] text-[var(--koku-indigo)]">Details, considered</span><h2 class="mt-5 font-serif text-4xl leading-[1.12] tracking-[-.045em]">The character behind the case.</h2><p class="mt-6 text-sm leading-7 text-[var(--koku-muted)]">{{ $product->description ?: 'A considered expression of watchmaking, balancing visual clarity with dependable construction and a quiet presence on the wrist.' }}</p><div class="mt-8 grid grid-cols-3 gap-4 border-t border-[var(--koku-line)] pt-5"><div><span class="block text-[9px] uppercase tracking-[.12em] text-[var(--koku-muted)]">Movement</span><strong class="mt-2 block text-xs">{{ ucfirst($product->movement) }}</strong></div><div><span class="block text-[9px] uppercase tracking-[.12em] text-[var(--koku-muted)]">Style</span><strong class="mt-2 block text-xs">{{ $product->category?->name }}</strong></div><div><span class="block text-[9px] uppercase tracking-[.12em] text-[var(--koku-muted)]">Wearer</span><strong class="mt-2 block text-xs">{{ ucfirst($product->gender) }}</strong></div></div></div>
                <div class="divide-y divide-[var(--koku-line)] overflow-hidden border-y border-[var(--koku-line)]">@foreach ($this->specGroups as $groupName => $fields)<div><button type="button" @click="open = open === '{{ Str::slug($groupName) }}' ? null : '{{ Str::slug($groupName) }}'" class="group flex w-full items-center justify-between py-6 text-left sm:px-2"><span class="flex items-baseline gap-4"><span class="font-serif text-2xl tracking-[-.025em]">{{ $groupName }}</span><span class="hidden text-[9px] uppercase tracking-[.13em] text-[var(--koku-muted)] sm:inline">{{ collect($fields)->filter(fn ($value) => filled($value))->count() }} details</span></span><span class="flex size-9 items-center justify-center rounded-full border border-[var(--koku-line)] bg-white text-sm text-[var(--koku-indigo)] transition group-hover:border-[var(--koku-indigo)]" x-text="open === '{{ Str::slug($groupName) }}' ? '−' : '+'"></span></button><div x-show="open === '{{ Str::slug($groupName) }}'" x-collapse><dl class="grid gap-x-10 gap-y-5 pb-7 sm:grid-cols-2 sm:px-2">@foreach ($fields as $label => $value)@if(filled($value))<div class="grid grid-cols-[minmax(0,1fr)_minmax(0,1.2fr)] items-baseline gap-4 border-t border-[var(--koku-line)]/60 pt-4"><dt class="text-[9px] uppercase tracking-[.12em] text-[var(--koku-muted)]">{{ is_string($label) ? $label : 'Detail' }}</dt><dd class="text-right text-xs leading-5">{{ $value }}</dd></div>@endif @endforeach</dl></div></div>@endforeach</div>
            </div>
        </div>
    </section>

    <section id="reviews" class="bg-[#f6f4f0] py-16 sm:py-20 lg:py-24">
        <div class="koku-shell max-w-[82rem]">
            <div class="grid gap-8 lg:grid-cols-[22rem_minmax(0,1fr)] lg:gap-12">
                <aside class="h-fit rounded-3xl bg-[#f4f2ee] p-6 sm:p-8 lg:sticky lg:top-28"><p class="text-[10px] font-medium uppercase tracking-[.16em] text-[var(--koku-indigo)]">Owner reviews</p><div class="mt-5 flex items-end gap-3"><strong class="font-serif text-6xl font-medium tracking-[-.06em]">{{ $reviewCount ? number_format($reviewAverage, 1) : '—' }}</strong><div class="pb-2"><div class="flex gap-0.5 text-sm text-[#b99872]">@for($star=1;$star<=5;$star++)<span>{{ $reviewCount && $reviewAverage >= $star - .5 ? '★' : '☆' }}</span>@endfor</div><p class="mt-1 text-[11px] text-[var(--koku-muted)]">{{ $reviewCount }} verified {{ Str::plural('review', $reviewCount) }}</p></div></div><div class="mt-7 space-y-2.5">@foreach($ratingDistribution as $stars => $percentage)<div class="grid grid-cols-[1.5rem_1fr_2rem] items-center gap-2 text-[10px] text-[var(--koku-muted)]"><span>{{ $stars }}★</span><div class="h-1 overflow-hidden rounded-full bg-white"><i class="block h-full rounded-full bg-[var(--koku-indigo)]" style="width:{{ $percentage }}%"></i></div><span class="text-right">{{ $percentage }}%</span></div>@endforeach</div><p class="mt-7 border-t border-[var(--koku-line)]/70 pt-5 text-xs leading-6 text-[var(--koku-muted)]">Every review comes from a customer whose order was delivered.</p></aside>
                <div>
                    <div class="flex items-end justify-between border-b border-[var(--koku-line)] pb-6"><div><h2 class="font-serif text-3xl tracking-[-.04em] sm:text-4xl">Worn. Lived with. Reviewed.</h2><p class="mt-2 text-sm text-[var(--koku-muted)]">Honest impressions from verified owners.</p></div></div>

                    @if(session('review-success'))<div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800">{{ session('review-success') }}</div>@endif

                    @auth
                        @if($this->canReview)
                            @if($this->ownReview && !$reviewEditing)
                                <article class="mt-6 overflow-hidden rounded-3xl bg-white shadow-[0_18px_50px_rgba(31,38,53,.09)] ring-1 ring-[var(--koku-line)]/55"><div class="flex flex-col justify-between gap-5 border-b border-[var(--koku-line)]/55 bg-[#fbfaf8] p-5 sm:flex-row sm:items-center sm:p-7"><div><div class="flex items-center gap-3"><p class="text-sm font-medium">Your review</p><span class="rounded-full px-3 py-1.5 text-[9px] font-medium uppercase tracking-[.11em] {{ $this->ownReview->status === 'approved' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">{{ $this->ownReview->status === 'approved' ? 'Published' : 'Awaiting approval' }}</span></div><div class="mt-3 flex gap-0.5 text-lg text-[#b99872]">@for($star=1;$star<=5;$star++)<span>{{ $this->ownReview->rating >= $star ? '★' : '☆' }}</span>@endfor</div></div><button type="button" wire:click="editReview" class="rounded-xl bg-[var(--koku-indigo)] px-5 py-3 text-xs font-medium text-white shadow-lg shadow-[var(--koku-indigo)]/15">Edit review</button></div>@if($this->ownReview->comment)<p class="px-5 py-5 text-sm leading-7 text-[var(--koku-muted)] sm:px-7">{{ $this->ownReview->comment }}</p>@endif @if($this->ownReview->images->isNotEmpty())<div class="flex flex-wrap gap-3 px-5 pb-6 sm:px-7">@foreach($this->ownReview->images as $image)<img src="{{ Storage::url($image->image_path) }}" alt="Your review photo" class="size-20 rounded-xl object-cover ring-1 ring-[var(--koku-line)] sm:size-24">@endforeach</div>@endif</article>
                            @else
                            <div class="mt-6 rounded-3xl border border-[var(--koku-line)]/70 bg-[#faf9f7] p-5 sm:p-7">
                                <div class="flex items-center justify-between"><div><p class="text-sm font-medium">{{ $this->ownReview ? 'Refine your review' : 'Share your experience' }}</p><p class="mt-1 text-xs text-[var(--koku-muted)]">Your review will be marked as a verified purchase.</p></div><span class="rounded-full bg-[var(--koku-indigo)]/8 px-3 py-1.5 text-[9px] font-medium uppercase tracking-[.12em] text-[var(--koku-indigo)]">Verified owner</span></div>
                                <form wire:submit="saveReview" class="mt-6"><div><p class="koku-field-label">Your rating</p><div class="flex gap-1">@for($star=1;$star<=5;$star++)<button type="button" wire:click="$set('reviewRating', {{ $star }})" class="text-2xl transition hover:-translate-y-0.5 {{ $reviewRating >= $star ? 'text-[#b99872]' : 'text-[var(--koku-line)]' }}" aria-label="{{ $star }} stars">★</button>@endfor</div>@error('reviewRating')<p class="koku-field-error">{{ $message }}</p>@enderror</div><div class="mt-5"><label class="koku-field-label" for="review-comment">Your thoughts</label><textarea id="review-comment" wire:model="reviewComment" rows="4" maxlength="2000" class="koku-field resize-none rounded-2xl" placeholder="How does it feel on the wrist? What has stood out over time?"></textarea>@error('reviewComment')<p class="koku-field-error">{{ $message }}</p>@enderror</div>
                                    @if($this->ownReview?->images->isNotEmpty())<div class="mt-4 flex flex-wrap gap-3">@foreach($this->ownReview->images as $image)<div class="relative"><img src="{{ Storage::url($image->image_path) }}" alt="Review photo" class="size-20 rounded-xl object-cover"><button type="button" wire:click="deleteReviewImage({{ $image->id }})" class="absolute -right-2 -top-2 flex size-6 items-center justify-center rounded-full bg-white text-xs shadow">×</button></div>@endforeach</div>@endif
                                    <div class="mt-5 flex flex-wrap items-center justify-between gap-4"><div><label class="inline-flex cursor-pointer items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-xs font-medium text-[var(--koku-indigo)] shadow-sm ring-1 ring-[var(--koku-line)]/70"><svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 8h3l1.5-2h7L17 8h3v10H4V8Z"/><circle cx="12" cy="13" r="3"/></svg>Add photos<input type="file" wire:model="reviewPhotos" multiple accept="image/jpeg,image/png,image/webp" class="sr-only"></label><span class="ml-2 text-[10px] text-[var(--koku-muted)]">Up to 3 images</span>@error('reviewPhotos.*')<p class="koku-field-error">{{ $message }}</p>@enderror</div><div class="flex items-center gap-2">@if($this->ownReview)<button type="button" wire:click="deleteReview" wire:confirm="Remove your review?" class="rounded-xl px-4 py-3 text-xs text-[var(--koku-muted)] hover:bg-red-50 hover:text-red-700">Remove</button>@endif<button type="submit" class="rounded-xl bg-[var(--koku-indigo)] px-6 py-3 text-xs font-medium text-white shadow-lg shadow-[var(--koku-indigo)]/20">{{ $this->ownReview ? 'Update review' : 'Publish review' }}</button></div></div>
                                    @if(count($reviewPhotos))<div class="mt-4 flex flex-wrap gap-2">@foreach($reviewPhotos as $photo)<div class="relative overflow-hidden rounded-xl bg-[#f4f2ee]"><img src="{{ $photo->temporaryUrl() }}" alt="New review photo preview" class="size-16 object-cover"><span class="absolute bottom-1 right-1 rounded bg-black/55 px-1.5 py-0.5 text-[8px] text-white">New</span></div>@endforeach</div>@endif
                                </form>
                            </div>
                            @endif
                        @else<div class="mt-6 rounded-2xl bg-[#f4f2ee] px-5 py-4 text-xs leading-6 text-[var(--koku-muted)]">Reviews open after your order has been delivered. <a href="{{ route('orders.index') }}" class="font-medium text-[var(--koku-indigo)]">View your orders →</a></div>@endif
                    @else<div class="mt-6 flex flex-col justify-between gap-4 rounded-2xl bg-[#f4f2ee] px-5 py-4 sm:flex-row sm:items-center"><p class="text-xs text-[var(--koku-muted)]">Purchased this watch? Sign in to share your experience.</p><a href="{{ route('login') }}" class="text-xs font-medium text-[var(--koku-indigo)]">Sign in →</a></div>@endauth

                    @if($approvedReviews->isEmpty())<div class="flex min-h-72 flex-col items-center justify-center text-center"><span class="flex size-14 items-center justify-center rounded-2xl bg-[#f4f2ee] text-xl text-[#b99872]">☆</span><h3 class="mt-5 font-serif text-2xl">Be the first to share.</h3><p class="mt-2 text-sm text-[var(--koku-muted)]">No owner reviews have been published yet.</p></div>@else<div class="mt-8 space-y-5">@foreach($approvedReviews as $review)<article class="rounded-3xl bg-white p-5 shadow-[0_14px_40px_rgba(31,38,53,.07)] ring-1 ring-[var(--koku-line)]/55 sm:p-7"><div class="flex items-start justify-between gap-4"><div class="flex items-center gap-3">@if($review->user->avatar)<img src="{{ Storage::url($review->user->avatar) }}" alt="{{ $review->user->name }}" class="size-11 rounded-xl object-cover">@else<span class="flex size-11 items-center justify-center rounded-xl bg-[var(--koku-indigo)] text-sm font-medium text-white">{{ Str::upper(Str::substr($review->user->name,0,1)) }}</span>@endif<div><p class="text-sm font-medium">{{ $review->user->name }}</p><p class="mt-1 text-[10px] text-[var(--koku-muted)]">{{ $review->created_at->format('M j, Y') }}</p></div></div><span class="rounded-full bg-emerald-50 px-3 py-1.5 text-[9px] font-medium uppercase tracking-[.1em] text-emerald-700">Verified purchase</span></div><div class="mt-5 flex gap-0.5 text-sm text-[#b99872]">@for($star=1;$star<=5;$star++)<span>{{ $review->rating >= $star ? '★' : '☆' }}</span>@endfor</div>@if($review->comment)<p class="mt-4 max-w-3xl text-sm leading-7 text-[var(--koku-muted)]">{{ $review->comment }}</p>@endif @if($review->images->isNotEmpty())<div class="mt-5 flex flex-wrap gap-3">@foreach($review->images as $image)<img src="{{ Storage::url($image->image_path) }}" alt="Photo shared with review" loading="lazy" class="size-20 rounded-xl object-cover ring-1 ring-[var(--koku-line)] sm:size-24">@endforeach</div>@endif</article>@endforeach</div>@endif
                </div>
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
