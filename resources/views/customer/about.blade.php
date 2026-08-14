<x-app-layout>
    <div class="bg-[var(--koku-paper)]">
        <section class="relative isolate min-h-[34rem] overflow-hidden bg-[var(--koku-indigo-deep)] text-white sm:min-h-[42rem]">
            <img src="{{ asset('images/hero.jpg') }}" alt="A refined Koku timepiece" class="absolute inset-0 -z-20 h-full w-full object-cover object-center">
            <div class="absolute inset-0 -z-10 bg-gradient-to-r from-[#101a2d]/95 via-[#14213a]/72 to-[#14213a]/20"></div>
            <div class="absolute inset-x-0 bottom-0 h-40 bg-gradient-to-t from-black/35 to-transparent"></div>
            <div class="koku-shell flex min-h-[34rem] items-end pb-14 pt-28 sm:min-h-[42rem] sm:pb-20">
                <div class="max-w-3xl">
                    <div class="flex items-center gap-3 text-[10px] font-medium uppercase tracking-[.18em] text-white/60">
                        <a href="{{ route('home') }}" class="transition hover:text-white">Home</a><span>/</span><span class="text-white">Our story</span>
                    </div>
                    <p class="mt-8 koku-eyebrow text-white/70">Independent watch specialists · Yangon</p>
                    <h1 class="mt-5 font-serif text-[clamp(3.5rem,7vw,7rem)] font-medium leading-[.92] tracking-[-.07em]">Time deserves<br>our attention.</h1>
                    <p class="mt-7 max-w-xl text-sm leading-7 text-white/72 sm:text-base sm:leading-8">Koku brings together enduring watches, useful knowledge and personal service for people who choose with intention.</p>
                </div>
            </div>
        </section>

        <section class="koku-shell grid gap-12 py-20 sm:py-28 lg:grid-cols-[.8fr_1.2fr] lg:gap-24">
            <div>
                <p class="koku-eyebrow text-[var(--koku-indigo)]">The idea behind Koku</p>
                <h2 class="mt-5 max-w-md font-serif text-4xl leading-[1.08] tracking-[-.05em] sm:text-5xl">A quieter way to discover watches.</h2>
            </div>
            <div class="grid gap-8 text-sm leading-8 text-[var(--koku-muted)] sm:grid-cols-2 sm:text-[15px]">
                <p>The name Koku speaks to a small measure of time. It reminds us that a watch is more than an instrument—it accompanies routines, milestones and the ordinary moments that shape a life.</p>
                <p>We select every piece for proportion, legibility, material honesty and long-term usefulness. Trends may introduce a watch, but character is what allows it to stay.</p>
            </div>
        </section>

        <section class="border-y border-[var(--koku-line)] bg-[#f4f0ea]">
            <div class="koku-shell grid lg:grid-cols-2">
                <div class="relative min-h-[30rem] overflow-hidden border-b border-[var(--koku-line)] lg:min-h-[42rem] lg:border-b-0 lg:border-r">
                    <img src="{{ asset('images/hero.jpg') }}" alt="Watch craftsmanship and details" class="absolute inset-0 h-full w-full object-cover object-[70%_center] scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/45 via-transparent to-transparent"></div>
                    <p class="absolute bottom-8 left-8 koku-eyebrow text-white sm:bottom-12 sm:left-12">Made to accompany a life</p>
                </div>
                <div class="flex items-center px-7 py-16 sm:px-14 lg:px-20 lg:py-24">
                    <div class="max-w-lg">
                        <p class="koku-eyebrow text-[var(--koku-clay)]">Our point of view</p>
                        <h2 class="mt-5 font-serif text-4xl leading-tight tracking-[-.05em] sm:text-5xl">Less noise.<br>Better choices.</h2>
                        <p class="mt-7 text-sm leading-8 text-[var(--koku-muted)]">A smaller, well-explained collection makes room for confidence. We look beyond the first impression to movement, finishing, comfort, serviceability and how a watch will feel years from now.</p>
                        <a href="{{ route('shop.index') }}" class="koku-link mt-9">Explore the collection <span>→</span></a>
                    </div>
                </div>
            </div>
        </section>

        <section class="koku-shell py-20 sm:py-28">
            <div class="mb-12 max-w-xl">
                <p class="koku-eyebrow text-[var(--koku-indigo)]">What you can expect</p>
                <h2 class="mt-4 font-serif text-4xl tracking-[-.05em]">Considered at every step.</h2>
            </div>
            <div class="grid border-l border-t border-[var(--koku-line)] md:grid-cols-3">
                @foreach ([
                    ['01', 'A focused selection', 'Watches with clear purpose, dependable construction and lasting visual balance.'],
                    ['02', 'Knowledge without noise', 'Plain guidance on movements, materials, fit and care—never unnecessary complexity.'],
                    ['03', 'Service that continues', 'Thoughtful help before and after your purchase, grounded in trust rather than pressure.'],
                ] as [$number, $title, $copy])
                    <article class="border-b border-r border-[var(--koku-line)] bg-white p-8 sm:p-10 lg:p-12">
                        <p class="koku-eyebrow text-[var(--koku-clay)]">{{ $number }}</p>
                        <h3 class="mt-14 font-serif text-2xl tracking-[-.035em]">{{ $title }}</h3>
                        <p class="mt-5 text-sm leading-7 text-[var(--koku-muted)]">{{ $copy }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="bg-[var(--koku-indigo-deep)] text-white">
            <div class="koku-shell flex flex-col gap-10 py-16 sm:flex-row sm:items-center sm:justify-between sm:py-20">
                <div><p class="koku-eyebrow text-white/55">Begin your collection</p><h2 class="mt-4 max-w-2xl font-serif text-4xl tracking-[-.05em] sm:text-5xl">Find the watch that feels like yours.</h2></div>
                <div class="flex shrink-0 flex-wrap gap-3"><a href="{{ route('shop.index') }}" class="bg-white px-6 py-4 text-[10px] font-medium uppercase tracking-[.14em] text-[var(--koku-indigo-deep)]">Shop watches</a><a href="{{ route('contact') }}" class="border border-white/35 px-6 py-4 text-[10px] font-medium uppercase tracking-[.14em]">Visit Koku</a></div>
            </div>
        </section>
    </div>
</x-app-layout>
