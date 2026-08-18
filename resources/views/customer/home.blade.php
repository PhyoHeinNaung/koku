<x-app-layout :overlay="true">
    @php
        $fallbackImages = [
            'https://images.unsplash.com/photo-1523170335258-f5ed11844a49?auto=format&fit=crop&w=1000&q=86',
            'https://images.unsplash.com/photo-1548171915-e79a380a2a4b?auto=format&fit=crop&w=1000&q=86',
            'https://images.unsplash.com/photo-1526045431048-f857369baa09?auto=format&fit=crop&w=1000&q=86',
            'https://images.unsplash.com/photo-1612817159949-195b6eb9e31a?auto=format&fit=crop&w=1000&q=86',
        ];
        $departments = [
            ['01', 'Automatic', 'Self-winding movements with a mechanical pulse.', route('shop.index', ['movements' => ['automatic']])],
            ['02', 'Quartz', 'Precise, dependable watches made for every day.', route('shop.index', ['movements' => ['quartz']])],
            ['03', 'Men', 'From restrained dress watches to robust daily pieces.', route('shop.index', ['genders' => ['men']])],
            ['04', 'Women', 'Considered proportions, enduring materials.', route('shop.index', ['genders' => ['women']])],
        ];
    @endphp

    <div class="koku-home">
        <section class="koku-home-hero" aria-labelledby="home-heading">
            <img src="{{ asset('images/hero.jpg') }}" alt="A considered selection of mechanical watches at the Koku counter" class="koku-home-hero__image" fetchpriority="high">
            <div class="koku-home-hero__shade"></div>
            <div class="koku-home-hero__content koku-shell">
                <div class="koku-home-hero__copy">
                    <p class="koku-home-label">Independent watch curators · Est. 2024</p>
                    <h1 id="home-heading">A watch should<br><em>feel like yours.</em></h1>
                    <p class="koku-home-hero__intro">Original watches from makers we trust, selected for their design, build and staying power.</p>
                    <a href="{{ route('shop.index') }}" class="koku-home-button">Shop all watches <span aria-hidden="true">↗</span></a>
                </div>
                <div class="koku-home-hero__note" aria-label="Koku service promise"><span>01 / 03</span><p>Every watch is inspected before it reaches your wrist.</p></div>
            </div>
        </section>

        <aside class="koku-home-assurance" aria-label="Shopping benefits"><div class="koku-shell">
            <p><span>Complimentary delivery</span> on qualifying orders</p><p><span>30-day returns</span> for a change of mind</p><p><span>Authenticity guaranteed</span> on every watch</p>
        </div></aside>

        <section class="koku-home-departments koku-shell" aria-labelledby="departments-heading">
            <header class="koku-home-section-head"><div><p class="koku-home-label">Shop by collection</p><h2 id="departments-heading">Begin with what matters.</h2></div><p>Movement, proportion, purpose. Four useful ways into a collection built to be worn—not merely admired.</p></header>
            <div class="koku-home-department-list">
                @foreach ($departments as [$number, $title, $copy, $href])
                    <a href="{{ $href }}" class="koku-home-department"><span class="koku-home-department__number">{{ $number }}</span><h3>{{ $title }}</h3><p>{{ $copy }}</p><span class="koku-home-department__arrow" aria-hidden="true">↗</span></a>
                @endforeach
            </div>
        </section>

        <section class="koku-home-new" id="new-arrivals" aria-labelledby="new-heading"><div class="koku-shell">
            <header class="koku-home-section-head koku-home-section-head--products"><div><p class="koku-home-label">Fresh from the cabinet</p><h2 id="new-heading">New arrivals</h2></div><a href="{{ route('shop.index') }}" class="koku-home-text-link">See the full collection <span aria-hidden="true">→</span></a></header>
            @if ($featuredProducts->isNotEmpty())
                <div class="koku-home-products">
                    @foreach ($featuredProducts as $product)
                        @php
                            $variant = $product->defaultVariant();
                            $image = $product->primary_image_url ?: $fallbackImages[$loop->index % count($fallbackImages)];
                        @endphp
                        <article class="koku-home-product">
                            <a href="{{ route('shop.product', $product) }}" class="koku-home-product__media">
                                @if ($product->is_featured)<span class="koku-home-product__flag">Koku pick</span>@endif
                                <img src="{{ $image }}" alt="{{ $product->name }} by {{ $product->brand?->name ?? 'Koku' }}" loading="lazy"><span class="koku-home-product__view">View watch <span aria-hidden="true">↗</span></span>
                            </a>
                            <div class="koku-home-product__details"><p>{{ $product->brand?->name ?? 'Koku selection' }}</p><div><h3><a href="{{ route('shop.product', $product) }}">{{ $product->name }}</a></h3>@if ($variant)<span>${{ number_format((float) $variant->price, 0) }}</span>@endif</div></div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="koku-home-empty"><p>The cabinet is being refreshed.</p><a href="{{ route('shop.index') }}" class="koku-home-text-link">Browse every watch <span aria-hidden="true">→</span></a></div>
            @endif
        </div></section>

        <section class="koku-home-story" aria-labelledby="story-heading">
            <div class="koku-home-story__image"><img src="https://images.unsplash.com/photo-1524592094714-0f0654e20314?auto=format&fit=crop&w=1600&q=88" alt="Close detail of a classic wristwatch" loading="lazy"><span>Details matter</span></div>
            <div class="koku-home-story__copy"><p class="koku-home-label">The Koku standard</p><h2 id="story-heading">Chosen slowly.<br>Worn often.</h2><p>We look past novelty. Each piece earns its place through legibility, honest materials and a design that will still make sense years from now.</p><dl><div><dt>01</dt><dd>Authorised sourcing</dd></div><div><dt>02</dt><dd>Pre-dispatch inspection</dd></div><div><dt>03</dt><dd>Care beyond purchase</dd></div></dl><a href="{{ route('about') }}" class="koku-home-text-link">How we choose <span aria-hidden="true">→</span></a></div>
        </section>

        <section class="koku-home-letter koku-shell" aria-labelledby="letter-heading">
            <div><p class="koku-home-label">Notes on good time</p><h2 id="letter-heading">The Koku letter</h2></div><p>New watches, useful care advice and stories from people who wear them.</p>
            <form onsubmit="event.preventDefault()"><label for="home-email">Email address</label><div><input id="home-email" type="email" required autocomplete="email" placeholder="you@example.com"><button type="submit">Subscribe <span aria-hidden="true">→</span></button></div></form>
        </section>
    </div>
</x-app-layout>
