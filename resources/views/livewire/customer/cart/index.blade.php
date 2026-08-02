<div class="px-6 sm:px-10 lg:px-16 py-10">

    <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

    @if ($items->isEmpty())
        <div class="text-center py-20">
            <p class="text-gray-400 mb-4">Your cart is empty.</p>
            <a href="{{ route('shop.index') }}" class="text-sm underline underline-offset-4 text-gray-900">Browse the
                shop</a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <div class="lg:col-span-2 divide-y divide-gray-200">
                @foreach ($items as $item)
                    <div wire:key="cart-item-{{ $item->id }}" class="flex gap-6 py-6">
                        <div class="w-24 h-24 bg-gray-50 rounded-lg overflow-hidden shrink-0 flex items-center justify-center">
                            @if ($item->variant->images->first())
                                <img src="{{ Storage::url($item->variant->images->first()->image_url) }}"
                                    class="w-full h-full object-contain p-2">
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <a href="{{ route('shop.product', $item->variant->product->slug) }}"
                                        class="text-sm font-medium text-gray-900 hover:underline">
                                        {{ $item->variant->product->name }}
                                    </a>
                                    <p class="text-xs text-gray-500 mt-1">{{ $item->variant->name }}</p>
                                    <p class="text-sm text-gray-900 mt-2">${{ number_format($item->unit_price, 2) }}</p>
                                </div>
                                <button type="button" wire:click="removeCartItem({{ $item->id }})"
                                    class="text-sm text-gray-400 hover:text-gray-900">Remove</button>
                            </div>
                            <div class="flex items-center gap-4 mt-4">
                                <div class="flex items-center border border-gray-300 rounded-lg">
                                    <button type="button"
                                        wire:click="updateCartItemQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                        class="px-3 py-1.5 text-gray-600 hover:text-gray-900">−</button>
                                    <span class="px-3 text-sm">{{ $item->quantity }}</span>
                                    <button type="button"
                                        wire:click="updateCartItemQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                        class="px-3 py-1.5 text-gray-600 hover:text-gray-900">+</button>
                                </div>
                                <p class="text-sm text-gray-500">Subtotal: <span
                                        class="text-gray-900">${{ number_format($item->quantity * $item->unit_price, 2) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-gray-50 rounded-xl p-6 h-fit">
                <h2 class="text-sm font-semibold text-gray-900 mb-4">Order Summary</h2>
                <div class="flex items-center justify-between text-sm mb-2">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="text-gray-900">${{ number_format($subtotal, 2) }}</span>
                </div>
                <p class="text-xs text-gray-500 mb-6">Shipping calculated at checkout.</p>
                {{-- <button type="button"
                    class="w-full py-3 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800">Checkout</button>
                --}}
                <a href="{{ route('checkout.index') }}"
                    class="w-full py-3 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 block text-center">Checkout</a>
            </div>
        </div>
    @endif

</div>