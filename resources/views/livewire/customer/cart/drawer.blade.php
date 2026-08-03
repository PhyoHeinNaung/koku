<div x-data="{ open: @entangle('open') }" x-effect="document.body.classList.toggle('overflow-hidden', open)">
    <button type="button" @click="open=true"
        class="koku-icon-button relative text-[#faf8f3] hover:!bg-white/10 hover:!text-white" aria-label="Cart">
        <svg class="size-[1.15rem]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M6.5 8.5h11l1 12h-13l1-12Z" />
            <path d="M9 9V6a3 3 0 0 1 6 0v3" />
        </svg>
        @if ($count > 0)<span
        class="absolute right-0 top-0 flex h-4 w-4 items-center justify-center rounded-full bg-[var(--koku-indigo)] text-[9px] text-white">{{ $count }}</span>@endif
    </button>
    <div x-show="open" x-cloak x-transition.opacity @click="open=false" class="fixed inset-0 z-[60] bg-[#11130f]/55">
    </div>
    <aside x-show="open" x-cloak @keydown.escape.window="open=false" x-transition:enter="transition duration-300"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition duration-200" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed inset-y-0 right-0 z-[70] flex w-full max-w-md flex-col bg-white text-[var(--koku-ink)]">
        <div class="flex h-20 shrink-0 items-center justify-between border-b border-[var(--koku-line)] px-6 sm:px-8">
            <div>
                <p class="koku-eyebrow text-[var(--koku-indigo)]">Selection</p>
                <h2 class="mt-1 font-serif text-xl">Cart · {{ $count }}</h2>
            </div><button @click="open=false" class="koku-icon-button" aria-label="Close cart"><span
                    class="text-xl">×</span></button>
        </div>
        <div class="flex-1 overflow-y-auto px-6 sm:px-8">
            @forelse ($items as $item)
                <article wire:key="drawer-item-{{ $item->id }}"
                    class="grid grid-cols-[5rem_1fr] gap-4 border-b border-[var(--koku-line)] py-6">
                    <div class="aspect-square overflow-hidden bg-[#f5f5f5]">
                        @if ($item->variant->images->first())<img
                            src="{{ Storage::url($item->variant->images->first()->image_url) }}"
                        alt="{{ $item->variant->product->name }}" class="h-full w-full object-contain p-2">@endif</div>
                    <div class="min-w-0">
                        <div class="flex justify-between gap-3">
                            <div class="min-w-0">
                                <p class="truncate font-serif">{{ $item->variant->product->name }}</p>
                                <p class="mt-1 truncate text-[10px] text-[var(--koku-muted)]">{{ $item->variant->name }}</p>
                            </div><button wire:click="removeCartItem({{ $item->id }})"
                                class="self-start text-[var(--koku-muted)]">×</button>
                        </div>
                        <div class="mt-5 flex items-center justify-between">
                            <div class="grid h-8 w-24 grid-cols-3 border border-[var(--koku-line)] text-xs"><button
                                    wire:click="updateCartItemQuantity({{ $item->id }}, {{ $item->quantity - 1 }})">−</button><span
                                    class="flex items-center justify-center border-x border-[var(--koku-line)]">{{ $item->quantity }}</span><button
                                    wire:click="updateCartItemQuantity({{ $item->id }}, {{ $item->quantity + 1 }})">+</button>
                            </div><span class="text-xs">${{ number_format($item->quantity * $item->unit_price, 2) }}</span>
                        </div>
                    </div>
                </article>
            @empty
                <div class="flex h-full min-h-80 flex-col items-center justify-center text-center"><span
                        class="font-serif text-4xl text-[var(--koku-indigo)]">空</span>
                    <p class="mt-5 font-serif text-xl">Your cart is empty.</p><a href="{{ route('shop.index') }}"
                        @click="open=false" class="koku-link mt-7">Explore watches <span>→</span></a>
                </div>
            @endforelse
        </div>
        @if ($items->isNotEmpty())
            <div class="shrink-0 border-t border-[var(--koku-line)] p-6 sm:p-8">
                <div class="flex justify-between"><span class="font-serif text-lg">Subtotal</span><span
                        class="font-serif text-2xl">${{ number_format($subtotal, 2) }}</span></div>
                <p class="mt-2 text-[10px] text-[var(--koku-muted)]">Taxes included. Delivery calculated at checkout.</p>
                <div class="mt-6 grid grid-cols-2 gap-3">
                    <a href="{{ route('cart.index') }}" @click="open=false" class="flex min-h-12 items-center justify-center border border-[var(--koku-indigo)] px-4 text-center text-xs uppercase tracking-[0.12em] text-[var(--koku-indigo)]">View cart</a>
                    <a href="{{ route('checkout.index') }}" class="flex min-h-12 items-center justify-center bg-[var(--koku-indigo)] px-4 text-center text-xs uppercase tracking-[0.12em] text-white">Checkout</a>
                </div>
        </div>@endif
    </aside>
</div>
