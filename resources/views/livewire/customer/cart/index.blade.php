<div class="bg-[var(--koku-paper)]">
    <header class="border-b border-[var(--koku-line)] bg-[var(--koku-white)]">
        <div class="koku-shell flex items-end justify-between py-14 sm:py-20">
            <div><p class="koku-eyebrow text-[var(--koku-indigo)]">Your selection</p><h1 class="mt-5 font-serif text-5xl font-medium tracking-[-0.055em] sm:text-6xl">Cart</h1></div>
            <p class="koku-eyebrow text-[var(--koku-muted)]">{{ $items->sum('quantity') }} {{ Str::plural('piece', $items->sum('quantity')) }}</p>
        </div>
    </header>

    <main class="koku-shell py-12 sm:py-16 lg:py-24">
        @if ($items->isEmpty())
            <div class="flex min-h-[30rem] flex-col items-center justify-center border-y border-[var(--koku-line)] text-center">
                <span class="font-serif text-5xl text-[var(--koku-indigo)]">空</span>
                <h2 class="mt-6 font-serif text-3xl font-medium tracking-[-0.04em]">Nothing selected yet.</h2>
                <p class="mt-3 text-sm text-[var(--koku-muted)]">Take your time. The collection is ready when you are.</p>
                <a href="{{ route('shop.index') }}" class="koku-link mt-9">Explore watches <span>→</span></a>
            </div>
        @else
            <div class="grid gap-14 lg:grid-cols-[minmax(0,1.45fr)_minmax(20rem,.55fr)] lg:gap-20">
                <section class="border-t border-[var(--koku-ink)]" aria-label="Cart items">
                    @foreach ($items as $item)
                        <article wire:key="cart-item-{{ $item->id }}" class="grid grid-cols-[6.5rem_1fr] gap-5 border-b border-[var(--koku-line)] py-6 sm:grid-cols-[9rem_1fr] sm:gap-8 sm:py-8">
                            <a href="{{ route('shop.product', $item->variant->product) }}" class="aspect-[4/5] overflow-hidden bg-[#eae8e2]">
                                @if ($item->variant->images->first())<img src="{{ Storage::url($item->variant->images->first()->image_url) }}" alt="{{ $item->variant->product->name }}" class="h-full w-full object-cover">@endif
                            </a>
                            <div class="flex min-w-0 flex-col justify-between">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="min-w-0"><p class="koku-eyebrow truncate text-[var(--koku-muted)]">{{ $item->variant->product->brand?->name }}</p><a href="{{ route('shop.product', $item->variant->product) }}" class="mt-2 block truncate font-serif text-xl font-medium sm:text-2xl">{{ $item->variant->product->name }}</a><p class="mt-2 text-xs text-[var(--koku-muted)]">{{ $item->variant->name }}</p></div>
                                    <button type="button" wire:click="removeCartItem({{ $item->id }})" class="shrink-0 text-xs text-[var(--koku-muted)] underline underline-offset-4 hover:text-[var(--koku-ink)]">Remove</button>
                                </div>
                                <div class="mt-6 flex flex-wrap items-end justify-between gap-4">
                                    <div class="grid h-10 w-28 grid-cols-3 border border-[var(--koku-line)] text-sm"><button wire:click="updateCartItemQuantity({{ $item->id }}, {{ $item->quantity - 1 }})" aria-label="Decrease quantity">−</button><span class="flex items-center justify-center border-x border-[var(--koku-line)]">{{ $item->quantity }}</span><button wire:click="updateCartItemQuantity({{ $item->id }}, {{ $item->quantity + 1 }})" aria-label="Increase quantity">+</button></div>
                                    <div class="text-right"><p class="text-xs text-[var(--koku-muted)]">${{ number_format($item->unit_price, 2) }} each</p><p class="mt-1 font-serif text-lg">${{ number_format($item->quantity * $item->unit_price, 2) }}</p></div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </section>

                <aside class="h-fit border-t border-[var(--koku-ink)] pt-6 lg:sticky lg:top-28">
                    <p class="koku-eyebrow text-[var(--koku-indigo)]">Order summary</p>
                    <div class="mt-7 space-y-4 text-sm"><div class="flex justify-between"><span class="text-[var(--koku-muted)]">Subtotal</span><span>${{ number_format($subtotal, 2) }}</span></div><div class="flex justify-between"><span class="text-[var(--koku-muted)]">Delivery</span><span>Calculated next</span></div></div>
                    <div class="mt-7 flex items-end justify-between border-y border-[var(--koku-line)] py-5"><span class="font-serif text-lg">Estimated total</span><strong class="font-serif text-2xl font-medium">${{ number_format($subtotal, 2) }}</strong></div>
                    <a href="{{ route('checkout.index') }}" class="mt-6 block bg-[var(--koku-indigo)] px-5 py-4 text-center text-xs font-medium uppercase tracking-[0.14em] text-white hover:bg-[var(--koku-indigo-deep)]">Continue to checkout</a>
                    <div class="mt-8 space-y-3 text-xs leading-5 text-[var(--koku-muted)]"><p>Complimentary delivery may apply based on destination.</p><p>Taxes, insurance and delivery are confirmed during checkout.</p></div>
                    <a href="{{ route('shop.index') }}" class="koku-link mt-8">Continue browsing <span>→</span></a>
                </aside>
            </div>
        @endif
    </main>
</div>
