<div class="px-6 sm:px-10 lg:px-16 py-10">

    <h1 class="text-3xl font-bold text-gray-900 mb-8">Wishlist</h1>

    @if ($products->isEmpty())
        <div class="text-center py-20">
            <p class="text-gray-400 mb-4">Your wishlist is empty.</p>
            <a href="{{ route('shop.index') }}" class="text-sm underline underline-offset-4 text-gray-900">
                Browse the shop
            </a>
        </div>
    @else
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-10">
            @foreach ($products as $product)
                <div wire:key="wishlist-product-{{ $product->id }}" class="group relative">
                    <button type="button" wire:click="removeFromWishlist({{ $product->id }})"
                        class="absolute top-3 right-3 z-10 h-8 w-8 flex items-center justify-center bg-white/90 rounded-full shadow hover:bg-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <a href="{{ route('shop.product', $product->slug) }}">
                        <div class="aspect-square bg-gray-100 overflow-hidden mb-4">
                            @if ($product->primary_image_url)
                                <img src="{{ $product->primary_image_url }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @endif
                        </div>
                        <p class="text-sm text-gray-900">{{ $product->name }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            @if ($product->variants_min_price)
                                From ${{ number_format($product->variants_min_price, 2) }}
                            @endif
                        </p>
                    </a>
                </div>
            @endforeach
        </div>
    @endif

</div>