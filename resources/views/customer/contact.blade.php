<x-app-layout>
    <div class="bg-[var(--koku-paper)]">
        <section class="relative isolate min-h-[31rem] overflow-hidden bg-[var(--koku-indigo-deep)] text-white sm:min-h-[37rem]">
            <img src="{{ asset('images/hero.jpg') }}" alt="Visit Koku in Yangon" class="absolute inset-0 -z-20 h-full w-full object-cover object-[65%_center] scale-105">
            <div class="absolute inset-0 -z-10 bg-gradient-to-r from-[#101a2d]/95 via-[#14213a]/78 to-[#14213a]/25"></div>
            <div class="koku-shell flex min-h-[31rem] items-end pb-14 pt-28 sm:min-h-[37rem] sm:pb-20">
                <div class="max-w-3xl">
                    <div class="flex items-center gap-3 text-[10px] font-medium uppercase tracking-[.18em] text-white/60"><a href="{{ route('home') }}" class="hover:text-white">Home</a><span>/</span><span class="text-white">Contact</span></div>
                    <p class="mt-8 koku-eyebrow text-white/70">Customer care · Yangon</p>
                    <h1 class="mt-5 font-serif text-[clamp(3.5rem,7vw,6.5rem)] font-medium leading-[.94] tracking-[-.07em]">We are here<br>to help.</h1>
                    <p class="mt-7 max-w-xl text-sm leading-7 text-white/72 sm:text-base">Product advice, order support or a showroom visit—talk to a real member of our watch team.</p>
                </div>
            </div>
        </section>

        <section class="border-b border-[var(--koku-line)] bg-white">
            <div class="koku-shell grid sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['Email', 'hello@koku.com.mm', 'mailto:hello@koku.com.mm'],
                    ['Telephone', '+95 9 770 123 456', 'tel:+959770123456'],
                    ['Showroom', 'Myanmar Plaza, Yangon', '#showroom'],
                    ['Opening hours', 'Daily · 10:00–20:00', null],
                ] as [$label, $value, $href])
                    <div class="border-b border-[var(--koku-line)] px-6 py-8 sm:px-8 lg:border-b-0 lg:border-r lg:last:border-r-0">
                        <p class="koku-eyebrow text-[var(--koku-muted)]">{{ $label }}</p>
                        @if ($href)<a href="{{ $href }}" class="mt-3 block font-serif text-lg transition hover:text-[var(--koku-indigo)]">{{ $value }}</a>@else<p class="mt-3 font-serif text-lg">{{ $value }}</p>@endif
                    </div>
                @endforeach
            </div>
        </section>

        <section class="koku-shell grid gap-12 py-20 sm:py-28 lg:grid-cols-[.72fr_1.28fr] lg:gap-24">
            <div class="lg:pt-3">
                <p class="koku-eyebrow text-[var(--koku-indigo)]">Send an enquiry</p>
                <h2 class="mt-5 font-serif text-4xl leading-tight tracking-[-.05em] sm:text-5xl">How can we<br>help today?</h2>
                <p class="mt-6 max-w-sm text-sm leading-7 text-[var(--koku-muted)]">Share a few details and our team will respond within one business day. For order questions, include your order number.</p>
                <div class="mt-10 border-l-2 border-[var(--koku-clay)] pl-5"><p class="text-sm leading-7">Prefer to speak in person?</p><a href="#showroom" class="mt-1 inline-block text-xs text-[var(--koku-indigo)]">Plan your showroom visit →</a></div>
            </div>
            <div class="border border-[var(--koku-line)] bg-[#f8f6f2] p-7 sm:p-10 lg:p-12">
                <form class="grid gap-6" action="mailto:hello@koku.com.mm" method="GET">
                    <div class="grid gap-6 sm:grid-cols-2"><div><label for="contact-name" class="koku-field-label">Full name</label><input id="contact-name" name="name" type="text" autocomplete="name" required class="koku-field" placeholder="Your name"></div><div><label for="contact-email" class="koku-field-label">Email address</label><input id="contact-email" name="email" type="email" autocomplete="email" required class="koku-field" placeholder="you@example.com"></div></div>
                    <div class="grid gap-6 sm:grid-cols-2"><div><label for="contact-subject" class="koku-field-label">Enquiry type</label><select id="contact-subject" name="subject" class="koku-field"><option>Product advice</option><option>Order support</option><option>Shipping and returns</option><option>Showroom visit</option><option>Partnership or press</option></select></div><div><label for="contact-order" class="koku-field-label">Order number <span class="normal-case tracking-normal">(optional)</span></label><input id="contact-order" name="order_number" type="text" class="koku-field" placeholder="For example, TCK-1024"></div></div>
                    <div><label for="contact-message" class="koku-field-label">Your message</label><textarea id="contact-message" name="body" rows="7" required class="koku-field resize-y" placeholder="Tell us how we can help"></textarea></div>
                    <div class="flex flex-col gap-5 border-t border-[var(--koku-line)] pt-7 sm:flex-row sm:items-center sm:justify-between"><p class="max-w-sm text-xs leading-6 text-[var(--koku-muted)]">Your details are used only to respond to this enquiry.</p><button type="submit" class="bg-[var(--koku-indigo)] px-8 py-4 text-[10px] font-medium uppercase tracking-[.14em] text-white transition hover:bg-[var(--koku-indigo-deep)]">Send enquiry</button></div>
                </form>
            </div>
        </section>

        <section id="showroom" class="scroll-mt-24 border-y border-[var(--koku-line)] bg-[#eee8df]">
            <div class="grid min-h-[38rem] lg:grid-cols-[1.2fr_.8fr]">
                <div class="relative min-h-[28rem] overflow-hidden border-b border-[var(--koku-line)] bg-[#ddd5c9] lg:min-h-full lg:border-b-0 lg:border-r">
                    <iframe title="Koku showroom at Myanmar Plaza, Yangon" src="https://www.google.com/maps?q=Myanmar%20Plaza%2C%20192%20Kaba%20Aye%20Pagoda%20Road%2C%20Yangon&z=16&output=embed" class="absolute inset-0 h-full w-full border-0 grayscale-[25%]" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                </div>
                <div class="flex items-center px-8 py-14 sm:px-14 lg:px-16">
                    <div class="max-w-md">
                        <p class="koku-eyebrow text-[var(--koku-indigo)]">Visit the Koku showroom</p>
                        <h2 class="mt-5 font-serif text-4xl leading-tight tracking-[-.05em] sm:text-5xl">See the details<br>in person.</h2>
                        <p class="mt-6 text-sm leading-7 text-[var(--koku-muted)]">Try on selected pieces and talk through size, movement and everyday wear with our team.</p>
                        <address class="mt-8 border-t border-[var(--koku-line)] pt-7 text-sm not-italic leading-7"><strong class="font-medium text-[var(--koku-ink)]">Myanmar Plaza</strong><br><span class="text-[var(--koku-muted)]">192 Kaba Aye Pagoda Road<br>Bahan Township, Yangon, Myanmar</span></address>
                        <div class="mt-6 flex items-center gap-4 text-xs"><span class="size-2 rounded-full bg-emerald-600"></span><span>Open daily, 10:00–20:00</span></div>
                        <a href="https://www.google.com/maps/search/?api=1&query=Myanmar+Plaza+Yangon" target="_blank" rel="noopener" class="koku-link mt-8">Open in Google Maps <span>→</span></a>
                    </div>
                </div>
            </div>
        </section>

        <section class="koku-shell grid gap-px bg-[var(--koku-line)] my-20 sm:grid-cols-3 sm:my-28">
            @foreach ([['Frequently asked questions','Quick answers about orders, delivery and watch care.',route('faqs')],['Our story','The ideas and standards behind the Koku selection.',route('about')],['The collection','Explore watches chosen for lasting character.',route('shop.index')]] as [$title,$copy,$href])
                <a href="{{ $href }}" class="group bg-white p-8 sm:p-10"><h3 class="font-serif text-2xl tracking-[-.035em]">{{ $title }}</h3><p class="mt-4 text-sm leading-7 text-[var(--koku-muted)]">{{ $copy }}</p><span class="mt-8 inline-block text-xs text-[var(--koku-indigo)] transition group-hover:translate-x-1">Continue →</span></a>
            @endforeach
        </section>
    </div>
</x-app-layout>
