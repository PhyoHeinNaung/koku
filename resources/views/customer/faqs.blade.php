<x-app-layout>
    @php
        $groups = [
            [
                'id' => 'orders',
                'number' => '01',
                'title' => 'Orders & payment',
                'intro' => 'From placing an order to receiving confirmation.',
                'items' => [
                    ['Which payment methods do you accept?', 'We accept KBZPay, WavePay, bank transfer and major debit or credit cards. Available methods are shown securely at checkout.'],
                    ['Can I change or cancel my order?', 'Contact us as soon as possible with your order number. We can usually make a change before an order enters processing, but we cannot guarantee changes once packing has begun.'],
                    ['How do I know my order was received?', 'A confirmation is shown after checkout and sent to the email address on your order. Registered customers can also follow order progress from My Account.'],
                ],
            ],
            [
                'id' => 'shipping',
                'number' => '02',
                'title' => 'Delivery & returns',
                'intro' => 'Clear expectations from our door to yours.',
                'items' => [
                    ['Where do you deliver?', 'We deliver throughout supported locations in Myanmar. Your delivery fee and estimated timeframe are calculated from the destination selected at checkout.'],
                    ['How long will delivery take?', 'Yangon orders generally arrive sooner than regional orders. The current estimate appears during checkout; remote locations and public holidays may require additional time.'],
                    ['Can I return a watch?', 'Unworn watches may be eligible for return when sent back in original condition with all packaging, tags and documents. Contact our team before returning anything so we can confirm eligibility and guide you.'],
                    ['What if my parcel arrives damaged?', 'Photograph the outer parcel and item, keep all packaging, and contact us promptly with your order number. We will review the issue and arrange the appropriate next step.'],
                ],
            ],
            [
                'id' => 'watches',
                'number' => '03',
                'title' => 'Watches & care',
                'intro' => 'Choosing well and caring for your watch over time.',
                'items' => [
                    ['Are your watches authentic?', 'Yes. Every watch offered by Koku is selected through trusted supply channels and is sold with the documents and accessories supplied for that piece.'],
                    ['How do I choose the right case size?', 'Case diameter is only one part of fit; lug-to-lug length, thickness and wrist shape also matter. Visit us at Myanmar Plaza or contact our team for personal guidance.'],
                    ['What does water resistance mean?', 'Water resistance is a tested rating, not a permanent condition. Seals age and should be checked periodically. Avoid operating crowns or pushers underwater and always follow the maker’s guidance.'],
                    ['How should I care for my watch?', 'Keep it clean and dry, avoid strong magnets and sudden impacts, and store it away from direct heat. Mechanical watches benefit from periodic professional servicing.'],
                ],
            ],
            [
                'id' => 'service',
                'number' => '04',
                'title' => 'Account & service',
                'intro' => 'Support before and after your purchase.',
                'items' => [
                    ['Do I need an account to order?', 'Guest checkout may be available. Creating an account makes it easier to save favourites, manage addresses and follow current and previous orders.'],
                    ['Can I visit your store?', 'Yes. Visit our showroom at Myanmar Plaza, 192 Kaba Aye Pagoda Road, Bahan Township, Yangon. We are open daily from 10:00 to 20:00; holiday hours may vary.'],
                    ['How can I speak with the Koku team?', 'Email hello@koku.com.mm, call +95 9 770 123 456, or use our contact page. We normally respond within one business day.'],
                ],
            ],
        ];
    @endphp

    <div class="bg-[var(--koku-paper)]">
        <section class="relative isolate min-h-[31rem] overflow-hidden bg-[var(--koku-indigo-deep)] text-white sm:min-h-[37rem]">
            <img src="{{ asset('images/hero.jpg') }}" alt="Koku customer care" class="absolute inset-0 -z-20 h-full w-full object-cover object-[35%_center] scale-105">
            <div class="absolute inset-0 -z-10 bg-gradient-to-r from-[#101a2d]/96 via-[#14213a]/82 to-[#14213a]/30"></div>
            <div class="koku-shell flex min-h-[31rem] items-end pb-14 pt-28 sm:min-h-[37rem] sm:pb-20">
                <div class="max-w-3xl">
                    <div class="flex items-center gap-3 text-[10px] font-medium uppercase tracking-[.18em] text-white/60"><a href="{{ route('home') }}" class="hover:text-white">Home</a><span>/</span><span class="text-white">FAQs</span></div>
                    <p class="mt-8 koku-eyebrow text-white/70">Customer care · Koku</p>
                    <h1 class="mt-5 font-serif text-[clamp(3.5rem,7vw,6.5rem)] font-medium leading-[.94] tracking-[-.07em]">Questions,<br>answered.</h1>
                    <p class="mt-7 max-w-xl text-sm leading-7 text-white/72 sm:text-base">Straightforward guidance about ordering, delivery and living with your watch.</p>
                </div>
            </div>
        </section>

        <nav aria-label="FAQ categories" class="sticky top-[4.5rem] z-30 overflow-x-auto border-b border-[var(--koku-line)] bg-white/95 backdrop-blur lg:top-[4.75rem]">
            <div class="koku-shell flex min-w-max items-center gap-8 py-5 text-[10px] font-medium uppercase tracking-[.14em] text-[var(--koku-muted)]">
                <span class="text-[var(--koku-ink)]">Browse:</span>
                @foreach ($groups as $group)<a href="#{{ $group['id'] }}" class="transition hover:text-[var(--koku-indigo)]">{{ $group['title'] }}</a>@endforeach
            </div>
        </nav>

        <section class="koku-shell py-16 sm:py-24" x-data="{ open: 'orders-0' }">
            @foreach ($groups as $group)
                <div id="{{ $group['id'] }}" class="grid scroll-mt-28 gap-8 border-t border-[var(--koku-line)] py-12 first:border-t-0 first:pt-0 lg:grid-cols-[.7fr_1.3fr] lg:gap-20">
                    <div>
                        <p class="koku-eyebrow text-[var(--koku-clay)]">{{ $group['number'] }}</p>
                        <h2 class="mt-4 font-serif text-3xl tracking-[-.04em]">{{ $group['title'] }}</h2>
                        <p class="mt-4 max-w-xs text-sm leading-7 text-[var(--koku-muted)]">{{ $group['intro'] }}</p>
                    </div>
                    <div class="border-t border-[var(--koku-line)]">
                        @foreach ($group['items'] as $index => [$question, $answer])
                            @php($key = $group['id'].'-'.$index)
                            <article class="border-b border-[var(--koku-line)]">
                                <button type="button" @click="open = open === '{{ $key }}' ? null : '{{ $key }}'" class="flex w-full items-center justify-between gap-6 py-6 text-left" :aria-expanded="open === '{{ $key }}'">
                                    <span class="font-serif text-lg tracking-[-.025em] sm:text-xl">{{ $question }}</span>
                                    <span class="flex size-8 shrink-0 items-center justify-center border border-[var(--koku-line)] text-[var(--koku-indigo)]" x-text="open === '{{ $key }}' ? '−' : '+'"></span>
                                </button>
                                <div x-show="open === '{{ $key }}'" x-collapse x-cloak>
                                    <p class="max-w-2xl pb-7 pr-12 text-sm leading-7 text-[var(--koku-muted)]">{{ $answer }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </section>

        <section class="border-t border-[var(--koku-line)] bg-[#e8e1d7]">
            <div class="koku-shell flex flex-col gap-7 py-14 sm:flex-row sm:items-center sm:justify-between sm:py-16">
                <div>
                    <p class="koku-eyebrow text-[var(--koku-indigo)]">Still deciding?</p>
                    <h2 class="mt-3 font-serif text-3xl tracking-[-.04em]">We are happy to help.</h2>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('contact') }}" class="bg-[var(--koku-indigo)] px-6 py-3.5 text-[10px] font-medium uppercase tracking-[.14em] text-white">Contact Koku</a>
                    <a href="{{ route('shop.index') }}" class="border border-[var(--koku-ink)] px-6 py-3.5 text-[10px] font-medium uppercase tracking-[.14em]">Explore watches</a>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
