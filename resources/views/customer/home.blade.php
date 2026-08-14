<x-app-layout>
    @php
        $heroImage = 'https://images.unsplash.com/photo-1523170335258-f5ed11844a49?auto=format&fit=crop&w=1800&q=88';
        $editorialImage = 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?auto=format&fit=crop&w=1500&q=85';
        $productImages = [
            'https://images.unsplash.com/photo-1523170335258-f5ed11844a49?auto=format&fit=crop&w=900&q=82',
            'https://images.unsplash.com/photo-1548171915-e79a380a2a4b?auto=format&fit=crop&w=900&q=82',
            'https://images.unsplash.com/photo-1526045431048-f857369baa09?auto=format&fit=crop&w=900&q=82',
            'https://images.unsplash.com/photo-1612817159949-195b6eb9e31a?auto=format&fit=crop&w=900&q=82',
        ];
        $collections = [
            ['Automatic', 'Watches powered by movement, made for a lifetime.', route('shop.index', ['movements' => ['automatic']]), 'https://images.unsplash.com/photo-1547996160-81dfa63595aa?auto=format&fit=crop&w=1000&q=82'],
            ['Everyday', 'Understated pieces that belong in your daily rhythm.', route('shop.index'), 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=1000&q=82'],
            ['For everyone', 'Clear design, considered without convention.', route('shop.index', ['genders' => ['unisex']]), 'https://images.unsplash.com/photo-1539874754764-5a96559165b0?auto=format&fit=crop&w=1000&q=82'],
        ];
    @endphp

    <div class="home-page bg-[#f7f6f2]">
        <section class="relative min-h-[calc(100svh-4.5rem)] overflow-hidden bg-[#17202b] text-white">
            <img src="{{ $heroImage }}" alt="A classic wristwatch in warm natural light" class="absolute inset-0 h-full w-full object-cover object-center" fetchpriority="high">
            <div class="absolute inset-0 bg-gradient-to-r from-black/75 via-black/30 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/45 via-transparent to-black/10"></div>
            <div class="koku-shell relative flex min-h-[calc(100svh-4.5rem)] items-end pb-14 pt-28 sm:pb-20 lg:items-center lg:pb-24">
                <div class="max-w-xl">
                    <p class="home-kicker text-white/70">The art of keeping time</p>
                    <h1 class="mt-5 font-serif text-[clamp(3.25rem,7.2vw,7rem)] font-medium leading-[.91] tracking-[-.065em]">Time, well<br>chosen.</h1>
                    <p class="mt-7 max-w-md text-sm leading-7 text-white/72 sm:text-base">A considered collection of watches selected for enduring design, honest craft and everyday pleasure.</p>
                    <div class="mt-9 flex flex-wrap items-center gap-5">
                        <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-8 bg-white px-6 py-4 text-[11px] font-semibold uppercase tracking-[.14em] text-[#18212c] transition hover:bg-[#e8e4dc]">Shop the collection <span aria-hidden="true">&rarr;</span></a>
                        <a href="#new-arrivals" class="text-[11px] font-medium uppercase tracking-[.14em] text-white underline decoration-white/45 underline-offset-8 transition hover:decoration-white">See what is new</a>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-7 right-8 hidden items-center gap-3 text-[10px] uppercase tracking-[.18em] text-white/55 lg:flex"><span class="block h-px w-10 bg-white/40"></span> Scroll to explore</div>
        </section>

        <section class="koku-shell py-20 sm:py-28 lg:py-36">
            <div class="grid gap-10 border-b border-[#d8d4cb] pb-14 lg:grid-cols-[.8fr_1.2fr] lg:items-end">
                <div><p class="home-kicker text-[#6d685f]">Curated by Koku</p><h2 class="mt-4 max-w-md font-serif text-4xl leading-[1.08] tracking-[-.05em] sm:text-5xl">Find your rhythm.</h2></div>
                <p class="max-w-xl text-sm leading-7 text-[#716d65] lg:justify-self-end">Not every watch needs to shout. We look for balance, proportion and a sense of purpose — pieces that feel as relevant years from now as they do today.</p>
            </div>
            <div class="mt-8 grid gap-4 md:grid-cols-3">
                @foreach ($collections as [$title, $copy, $href, $image])
                    <a href="{{ $href }}" class="group block">
                        <div class="aspect-[4/5] overflow-hidden bg-[#e3e0d8]"><img src="{{ $image }}" alt="{{ $title }} watch collection" loading="lazy" class="h-full w-full object-cover transition duration-700 ease-out group-hover:scale-[1.025]"></div>
                        <div class="flex items-start justify-between gap-5 pt-5"><div><h3 class="font-serif text-xl tracking-[-.03em]">{{ $title }}</h3><p class="mt-2 max-w-[17rem] text-xs leading-5 text-[#777269]">{{ $copy }}</p></div><span class="mt-1 text-lg transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true">&rarr;</span></div>
                    </a>
                @endforeach
            </div>
        </section>

        <section id="new-arrivals" class="bg-white py-20 sm:py-28">
            <div class="koku-shell">
                <div class="flex items-end justify-between gap-8"><div><p class="home-kicker text-[#6d685f]">New and noteworthy</p><h2 class="mt-4 font-serif text-4xl tracking-[-.05em] sm:text-5xl">The latest edit.</h2></div><a href="{{ route('shop.index') }}" class="koku-link hidden sm:inline-flex">View all watches <span>&rarr;</span></a></div>
                @if ($featuredProducts->isNotEmpty())
                    <div class="mt-11 grid grid-cols-2 gap-x-4 gap-y-12 lg:grid-cols-4 lg:gap-x-6">
                        @foreach ($featuredProducts as $product)
                            @php($variant = $product->defaultVariant())
                            <a href="{{ route('shop.product', $product) }}" class="group block">
                                <div class="aspect-[4/5] overflow-hidden bg-[#f0eee9]"><img src="{{ $productImages[$loop->index % count($productImages)] }}" alt="{{ $product->name }}" loading="lazy" class="koku-product-image h-full w-full object-cover"></div>
                                <div class="pt-4"><p class="text-[10px] font-medium uppercase tracking-[.15em] text-[#8a857c]">{{ $product->brand?->name ?? 'Koku selection' }}</p><div class="mt-2 flex items-start justify-between gap-3"><h3 class="font-serif text-base tracking-[-.02em] sm:text-lg">{{ $product->name }}</h3>@if($variant)<span class="shrink-0 text-xs text-[#716d65]">${{ number_format((float) $variant->price, 0) }}</span>@endif</div></div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="mt-10 border border-[#ddd9d1] px-6 py-16 text-center"><p class="font-serif text-2xl">A new edit is on its way.</p><a href="{{ route('shop.index') }}" class="koku-link mt-6">Explore all watches <span>&rarr;</span></a></div>
                @endif
                <a href="{{ route('shop.index') }}" class="koku-link mt-10 sm:hidden">View all watches <span>&rarr;</span></a>
            </div>
        </section>

        <section class="grid bg-[#1a2735] text-white lg:grid-cols-2">
            <div class="min-h-[30rem] lg:min-h-[42rem]"><img src="{{ $editorialImage }}" alt="A refined watch detail" loading="lazy" class="h-full w-full object-cover"></div>
            <div class="flex items-center px-7 py-20 sm:px-14 lg:px-[clamp(4rem,8vw,8rem)]">
                <div class="max-w-lg"><p class="home-kicker text-white/55">Our philosophy</p><h2 class="mt-5 font-serif text-4xl leading-[1.08] tracking-[-.05em] sm:text-5xl">Fewer things.<br>Better chosen.</h2><p class="mt-7 max-w-md text-sm leading-7 text-white/62">We believe a watch should earn its place on your wrist. Our edit favours legibility, material honesty and design with the confidence to remain quiet.</p><a href="{{ route('about') }}" class="mt-9 inline-flex items-center gap-5 border-b border-white/50 pb-2 text-[11px] font-medium uppercase tracking-[.14em] transition hover:border-white">Read our story <span>&rarr;</span></a></div>
            </div>
        </section>

        <section class="koku-shell grid gap-10 py-20 sm:py-28 lg:grid-cols-[1fr_1.05fr] lg:items-end">
            <div><p class="home-kicker text-[#6d685f]">The Koku letter</p><h2 class="mt-4 font-serif text-4xl tracking-[-.05em]">Time, delivered slowly.</h2><p class="mt-4 max-w-md text-sm leading-7 text-[#716d65]">New arrivals, thoughtful stories and practical watch guidance. Occasionally, and always worth opening.</p></div>
            <form class="flex border-b border-[#272924]" onsubmit="event.preventDefault()"><label for="home-email" class="sr-only">Email address</label><input id="home-email" type="email" required placeholder="Your email address" class="min-w-0 flex-1 border-0 bg-transparent px-0 py-4 text-sm shadow-none placeholder:text-[#918d84] focus:ring-0"><button class="px-3 text-[10px] font-semibold uppercase tracking-[.14em]">Subscribe &rarr;</button></form>
        </section>
    </div>
</x-app-layout>
