<div class="bg-[var(--koku-paper)]">
    <header class="border-b border-[var(--koku-line)] bg-[var(--koku-white)]"><div class="koku-shell flex items-end justify-between py-14 sm:py-20"><div><p class="koku-eyebrow text-[var(--koku-indigo)]">Saved for later</p><h1 class="mt-5 font-serif text-5xl font-medium tracking-[-0.055em] sm:text-6xl">Wishlist</h1></div><span class="koku-eyebrow text-[var(--koku-muted)]">{{ $products->count() }} {{ Str::plural('piece', $products->count()) }}</span></div></header>
    <main class="koku-shell py-12 sm:py-20">
        @if ($products->isEmpty())
            <div class="flex min-h-[28rem] flex-col items-center justify-center border-y border-[var(--koku-line)] text-center"><span class="font-serif text-5xl text-[var(--koku-indigo)]">心</span><h2 class="mt-6 font-serif text-3xl">Keep what stays with you.</h2><p class="mt-3 text-sm text-[var(--koku-muted)]">Save watches here while you consider them.</p><a href="{{ route('shop.index') }}" class="koku-link mt-8">Explore watches <span>→</span></a></div>
        @else
            <div class="grid grid-cols-2 gap-x-4 gap-y-14 sm:gap-x-7 lg:grid-cols-3 lg:gap-y-24">
                @foreach ($products as $product)
                    <article wire:key="wishlist-product-{{ $product->id }}" class="group relative"><button wire:click="removeFromWishlist({{ $product->id }})" class="absolute right-3 top-3 z-10 flex size-9 items-center justify-center bg-[var(--koku-white)]/90 sm:right-5 sm:top-5" aria-label="Remove {{ $product->name }}">×</button><a href="{{ route('shop.product', $product) }}"><div class="aspect-[4/5] overflow-hidden bg-[#eae8e2]">@if ($product->primary_image_url)<img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="koku-product-image h-full w-full object-cover">@endif</div><div class="mt-4 border-t border-[var(--koku-line)] pt-4"><p class="koku-eyebrow text-[var(--koku-muted)]">{{ $product->brand?->name }}</p><div class="mt-2 flex justify-between gap-4"><h2 class="truncate font-serif text-lg">{{ $product->name }}</h2>@if ($product->variants_min_price)<span class="shrink-0 text-xs">From ${{ number_format($product->variants_min_price, 2) }}</span>@endif</div></div></a></article>
                @endforeach
            </div>
        @endif
    </main>
</div>
