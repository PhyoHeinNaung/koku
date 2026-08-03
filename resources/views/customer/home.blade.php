<x-app-layout>
    @php
        $leadProduct = $featuredProducts->first();
        $leadVariant = $leadProduct?->defaultVariant();
        $leadImages = $leadVariant?->images ?? collect();
        $imageUrls = $leadImages->map(fn ($image) => Storage::url($image->image_url))->values();
        $leadImage = $leadProduct?->primary_image_url ?: asset('images/hero.jpg');
    @endphp

    <div class="bg-[var(--koku-paper)] pb-20 sm:pb-28">
        <main class="koku-shell pt-5 sm:pt-8">
            <section class="grid min-h-[31rem] overflow-hidden bg-[#e5ded4] lg:grid-cols-[.9fr_1.1fr]">
                <div class="order-2 flex items-center px-7 py-12 sm:px-12 lg:order-1 lg:px-16 lg:py-16"><div class="max-w-md"><p class="koku-eyebrow text-[var(--koku-indigo)]">New arrival · 01</p><h1 class="mt-5 font-serif text-[clamp(2.8rem,5vw,5.2rem)] font-medium leading-[1.02] tracking-[-0.06em]">Quiet precision,<br>made visible.</h1><p class="mt-6 max-w-sm text-sm leading-7 text-[var(--koku-muted)]">A focused selection of watches shaped by clear purpose, lasting materials and the pleasure of everyday use.</p><a href="{{ $leadProduct ? route('shop.product', $leadProduct) : route('shop.index') }}" class="mt-8 inline-flex bg-[var(--koku-ink)] px-6 py-3.5 text-[10px] font-medium uppercase tracking-[0.14em] text-white">Discover the watch</a></div></div>
                <a href="{{ $leadProduct ? route('shop.product', $leadProduct) : route('shop.index') }}" class="group order-1 relative min-h-[24rem] overflow-hidden bg-[#ddd7cf] lg:order-2 lg:min-h-[34rem]"><img src="{{ $leadImage }}" alt="{{ $leadProduct?->name ?? 'Koku watch collection' }}" class="koku-product-image absolute inset-0 h-full w-full object-cover">@if ($leadProduct)<div class="absolute bottom-5 left-5 bg-[var(--koku-white)] px-5 py-4 sm:bottom-7 sm:left-7"><p class="koku-eyebrow text-[var(--koku-muted)]">{{ $leadProduct->brand?->name }}</p><p class="mt-1 font-serif">{{ $leadProduct->name }}</p></div>@endif</a>
            </section>

            <section class="mt-2 grid gap-2 sm:grid-cols-3">
                @foreach ([['Automatic','Mechanical rhythm',route('shop.index',['movements'=>['automatic']]),$imageUrls->get(1) ?: $leadImage,'#dfe6e8'],['For everyone','Unisex selection',route('shop.index',['genders'=>['unisex']]),$imageUrls->get(2) ?: $leadImage,'#eadbd2'],['The collection','View every watch',route('shop.index'),$imageUrls->get(3) ?: $leadImage,'#e8e3db']] as [$title,$subtitle,$href,$image,$color])
                    <a href="{{ $href }}" class="group relative min-h-[20rem] overflow-hidden" style="background-color:{{ $color }}"><img src="{{ $image }}" alt="" class="koku-product-image absolute inset-0 h-full w-full object-cover"><div class="absolute inset-x-5 bottom-5 bg-[var(--koku-white)] px-5 py-4 text-center"><h2 class="font-serif text-lg">{{ $title }}</h2><p class="mt-1 text-[10px] uppercase tracking-[0.1em] text-[var(--koku-muted)]">{{ $subtitle }}</p></div></a>
                @endforeach
            </section>

            <section class="py-20 sm:py-28">
                <div class="text-center"><p class="koku-eyebrow text-[var(--koku-indigo)]">Koku selection</p><h2 class="mt-4 font-serif text-3xl font-medium tracking-[-0.04em] sm:text-4xl">Objects for keeping time</h2><p class="mx-auto mt-4 max-w-md text-sm leading-7 text-[var(--koku-muted)]">A small collection, intentionally chosen. Each piece has a reason to remain.</p></div>
                @if ($leadProduct)<div class="mt-12 grid auto-rows-[14rem] gap-2 sm:auto-rows-[18rem] sm:grid-cols-2 lg:auto-rows-[21rem] lg:grid-cols-4">@foreach ($imageUrls->take(8) as $index => $image)<a href="{{ route('shop.product',$leadProduct) }}" class="group relative overflow-hidden bg-[var(--koku-white)] {{ in_array($index,[0,5]) ? 'sm:row-span-2' : '' }} {{ in_array($index,[0,6]) ? 'lg:col-span-2' : '' }}"><img src="{{ $image }}" alt="{{ $leadProduct->name }} detail {{ $index+1 }}" loading="lazy" class="koku-product-image h-full w-full object-cover">@if($index===0)<div class="absolute inset-x-5 bottom-5 bg-[var(--koku-white)]/94 px-5 py-4"><p class="koku-eyebrow text-[var(--koku-muted)]">{{ $leadProduct->brand?->name }}</p><div class="mt-2 flex justify-between gap-4"><span class="font-serif">{{ $leadProduct->name }}</span><span class="text-xs">${{ number_format((float)$leadVariant?->price,2) }}</span></div></div>@endif</a>@endforeach</div>@endif
            </section>

            <section class="grid overflow-hidden bg-[var(--koku-white)] lg:grid-cols-2"><div class="min-h-[27rem] bg-[#ddd6cc]"><img src="{{ $imageUrls->get(8) ?: $leadImage }}" alt="Watch detail" class="h-full w-full object-cover"></div><div class="flex items-center px-8 py-14 sm:px-14 lg:px-16"><div class="max-w-md"><p class="koku-eyebrow text-[var(--koku-indigo)]">Our point of view</p><h2 class="mt-5 font-serif text-4xl font-medium leading-tight tracking-[-0.045em]">Less, but considered.</h2><p class="mt-6 text-sm leading-7 text-[var(--koku-muted)]">Koku values clarity over spectacle. We select watches for proportion, legibility, honest construction and the quiet satisfaction they bring over time.</p><a href="{{ route('shop.index') }}" class="koku-link mt-8">Explore the collection <span>→</span></a></div></div></section>

            <section class="grid gap-10 border-b border-[var(--koku-line)] py-20 sm:py-24 lg:grid-cols-[1fr_1.2fr] lg:items-end"><div><p class="koku-eyebrow text-[var(--koku-indigo)]">Letters from Koku</p><h2 class="mt-4 font-serif text-3xl tracking-[-0.04em]">A slower kind of update.</h2><p class="mt-4 max-w-sm text-sm leading-7 text-[var(--koku-muted)]">New watches, thoughtful objects and notes on keeping time.</p></div><form class="flex border-b border-[var(--koku-ink)]"><input type="email" placeholder="Email address" class="min-w-0 flex-1 border-0 bg-transparent px-0 py-4 text-sm shadow-none focus:ring-0"><button class="px-3 text-[10px] uppercase tracking-[0.12em] text-[var(--koku-indigo)]">Subscribe →</button></form></section>
        </main>
    </div>
</x-app-layout>
