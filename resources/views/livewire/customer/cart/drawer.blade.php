<div x-data="{ open: @entangle('open') }" x-effect="document.body.classList.toggle('overflow-hidden', open)">

    <button type="button" @click="open = true" class="relative p-2 transition-all duration-200 hover:opacity-60"
        :class="(scrolled || hovered) ? 'text-gray-900' : 'text-white'" aria-label="Cart">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
        </svg>
        @if ($count > 0)
            <span
                class="absolute -top-0.5 -right-0.5 flex items-center justify-center h-4 w-4 rounded-full bg-gray-900 text-white text-[10px] leading-none">
                {{ $count }}
            </span>
        @endif
    </button>

    <div x-show="open" x-transition.opacity x-cloak @click="open = false" class="fixed inset-0 bg-black/40 z-40"></div>

    <div x-show="open" x-cloak @keydown.escape.window="open = false"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
        class="fixed inset-y-0 right-0 w-full max-w-md bg-white z-50 flex flex-col">

        <div class="flex items-center justify-between px-6 h-20 border-b border-gray-200 shrink-0">
            <h2 class="text-lg font-semibold text-gray-900">Cart ({{ $count }})</h2>
            <button type="button" @click="open = false" class="p-2 border border-gray-200 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-6 py-6">
            @if ($items->isEmpty())
                <p class="text-gray-400 text-center py-20">Your cart is empty.</p>
            @else
                <div class="space-y-6">
                    @foreach ($items as $item)
                        <div wire:key="drawer-item-{{ $item->id }}" class="flex gap-4">
                            <div
                                class="w-20 h-20 bg-gray-50 rounded-lg overflow-hidden shrink-0 flex items-center justify-center">
                                @if ($item->variant->images->first())
                                    <img src="{{ Storage::url($item->variant->images->first()->image_url) }}"
                                        class="w-full h-full object-contain p-2">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $item->variant->product->name }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $item->variant->name }}</p>
                                    </div>
                                    <button type="button" wire:click="removeCartItem({{ $item->id }})"
                                        class="text-gray-400 hover:text-gray-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="flex items-center justify-between mt-3">
                                    <div class="flex items-center border border-gray-300 rounded-lg">
                                        <button type="button"
                                            wire:click="updateCartItemQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                            class="px-2.5 py-1 text-gray-600 hover:text-gray-900">−</button>
                                        <span class="px-2 text-sm">{{ $item->quantity }}</span>
                                        <button type="button"
                                            wire:click="updateCartItemQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                            class="px-2.5 py-1 text-gray-600 hover:text-gray-900">+</button>
                                    </div>
                                    <p class="text-sm text-gray-900">
                                        ${{ number_format($item->quantity * $item->unit_price, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        @if ($items->isNotEmpty())
            <div class="p-6 border-t border-gray-200 shrink-0 space-y-4">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="text-gray-900 font-medium">${{ number_format($subtotal, 2) }}</span>
                </div>
                <a href="{{ route('cart.index') }}" @click="open = false"
                    class="block text-center py-3 border border-gray-900 text-gray-900 text-sm font-medium rounded-lg hover:bg-gray-50">
                    View Cart
                </a>
                {{-- <button type="button"
                    class="w-full py-3 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800">
                    Checkout
                </button> --}}
                <a href="{{ route('checkout.index') }}"
                    class="w-full py-3 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 block text-center">Checkout</a>
            </div>
        @endif

    </div>

</div>