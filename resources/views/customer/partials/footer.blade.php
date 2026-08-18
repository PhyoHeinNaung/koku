<footer class="border-t border-white/10 bg-[var(--koku-indigo-deep)] text-white">
    <div class="koku-shell py-14 sm:py-20">
        <div class="grid gap-12 border-b border-white/15 pb-14 lg:grid-cols-[1.2fr_2fr] lg:gap-24">
            <div>
                <p class="font-serif text-4xl font-medium tracking-[-0.05em]">Koku</p>
                <p class="mt-5 max-w-sm text-sm leading-7 text-white/65">Watches selected with care for the
                    moments that matter—and the quiet ones between.</p>
            </div>
            <div class="grid grid-cols-2 gap-10 text-sm sm:grid-cols-3">
                <div>
                    <p class="koku-eyebrow mb-5 text-white/55">Explore</p>
                    <ul class="space-y-3">
                        <li><a href="{{ route('shop.index') }}">All watches</a></li>
                        <li><a href="{{ route('shop.index') }}">New arrivals</a></li>
                        <li><a href="{{ route('wishlist.index') }}">Wishlist</a></li>
                    </ul>
                </div>
                <div>
                    <p class="koku-eyebrow mb-5 text-white/55">Koku</p>
                    <ul class="space-y-3">
                        <li><a href="{{ route('about') }}">Our story</a></li>
                        <li><a href="{{ route('community.index') }}">Wrist Stories</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <p class="koku-eyebrow mb-5 text-white/55">Service</p>
                    <ul class="space-y-3">
                        <li><a href="{{ route('shipping-returns') }}">Shipping & returns</a></li>
                        <li><a href="{{ route('watch-care') }}">Watch care & warranty</a></li>
                        <li><a href="{{ route('faqs') }}">FAQs</a></li>
                        <li><a href="{{ auth()->check() ? route('profile.edit') : route('login') }}">My account</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="flex flex-col gap-5 pt-8 text-xs text-white/55 sm:flex-row sm:items-center sm:justify-between">
            <p>© {{ date('Y') }} Koku. Time, considered.</p>
            <div class="flex gap-6"><a href="{{ route('privacy') }}">Privacy</a><a href="{{ route('terms') }}">Terms</a><a href="https://www.instagram.com/" rel="noopener noreferrer" target="_blank">Instagram</a></div>
        </div>
    </div>
</footer>
