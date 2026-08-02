@props(['overlay' => false])

@php
    $megaMenus = [
        'shop' => [
            'label' => 'Shop',
            'columns' => [
                ['title' => 'Collections', 'links' => ["Men's Watches", "Women's Watches", 'Unisex', 'New Arrivals', 'Best Sellers']],
                ['title' => 'By Style', 'links' => ['Casual & Everyday', 'Dress & Formal', 'Sport & Dive', 'Minimalist']],
                ['title' => 'By Movement', 'links' => ['Automatic', 'Quartz (Battery)', 'Mechanical (Hand-wind)', 'Chronographs']],
            ],
            'images' => [
                ['src' => 'https://i.pinimg.com/1200x/75/88/42/7588424550866cfc0cc5067d1d2655cf.jpg', 'caption' => 'New Arrivals'],
                ['src' => 'https://i.pinimg.com/736x/67/32/94/673294bf94aeef2b2a9aa901e3fc85cd.jpg', 'caption' => 'Best Sellers'],
            ],
        ],
        'brands' => [
            'label' => 'Brands',
            'columns' => [
                ['title' => 'Luxury Tier', 'links' => ['Rolex', 'Omega', 'Cartier', 'Tudor']],
                ['title' => 'Premium Tier', 'links' => ['Longines', 'Tissot', 'Hamilton', 'Seiko Prospex']],
                ['title' => 'Everyday Tier', 'links' => ['Citizen', 'Casio / G-Shock', 'Orient', 'Timex']],
                ['title' => 'Smart / Sport', 'links' => ['Garmin', 'Apple', 'Suunto', 'Fitbit']],
            ],
            'images' => [
                ['src' => 'https://images.pexels.com/photos/31642726/pexels-photo-31642726.jpeg', 'caption' => 'View All Brands'],
            ],
        ],
        'about' => [
            'label' => 'About',
            'columns' => [
                ['title' => 'Company & Help', 'links' => ['About Us', 'Contact Us', 'FAQs']],
                ['title' => 'Trust & Education', 'links' => ['Shipping & Returns', 'Watch Care Guide']],
            ],
            'images' => [
                ['src' => 'https://i.pinimg.com/736x/93/4e/4f/934e4fda42aeabcd2d797048a49af4ae.jpg', 'caption' => 'Our Story'],
                ['src' => 'https://i.pinimg.com/1200x/f8/69/f4/f869f467a0b5a789e7160c6027e9d4d6.jpg', 'caption' => 'Watch Care Guide'],
            ],
        ],
    ];
@endphp

<nav x-data="{
        overlay: @js($overlay),
        scrolled: {{ $overlay ? 'false' : 'true' }},
        hovered: false,
        activeMenu: null,
        profileOpen: false,
        mobileOpen: false,
    }" x-init="scrolled = overlay ? (window.scrollY > 40) : true"
    @scroll.window="if (overlay) scrolled = window.scrollY > 40" @mouseenter="hovered = true"
    @mouseleave="hovered = false; activeMenu = null"
    :class="(scrolled || hovered) ? 'bg-white text-gray-900 shadow-sm' : 'bg-transparent text-white'"
    class="{{ $overlay ? 'fixed' : 'sticky' }} top-0 inset-x-0 z-50 transition-colors duration-300">

    <div class="px-6 sm:px-10 lg:px-16">
        <div class="relative flex items-center justify-center h-20">

            {{-- Left: desktop nav triggers --}}
            <div class="absolute left-0 hidden lg:flex items-center gap-8 h-full">
                @foreach ($megaMenus as $key => $menu)
                    <x-mega-menu-trigger :menu-key="$key" :label="$menu['label']" />
                @endforeach
            </div>

            {{-- Left: mobile hamburger --}}
            <button type="button" @click="mobileOpen = true" class="absolute left-0 lg:hidden p-2"
                :class="(scrolled || hovered) ? 'text-gray-900' : 'text-white'" aria-label="Menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                </svg>
            </button>

            {{-- Center: logo --}}
            <a href="{{ url('/') }}"
                class="flex items-center transition-colors duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#FDBF2D]"
                :class="(scrolled || hovered) ? 'text-black' : 'text-white'" aria-label="TICKS home">
                <x-brand-logo class="h-6 w-[7.5rem] sm:h-7 sm:w-[8.75rem]" />
            </a>

            {{-- Right: desktop icons --}}
            <div class="absolute right-0 hidden lg:flex items-center gap-3">

                <x-nav-icon-button label="Search">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                    </svg>
                </x-nav-icon-button>

                <livewire:customer.wishlist.icon />

                <livewire:customer.cart.drawer />

                <div class="relative" @click.outside="profileOpen = false">
                    <button type="button" @click="profileOpen = !profileOpen"
                        class="p-2 transition-colors duration-200 hover:opacity-60"
                        :class="(scrolled || hovered) ? 'text-gray-900' : 'text-white'" aria-label="Account">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </button>
                    <ul x-show="profileOpen" x-transition x-cloak
                        class="absolute right-0 mt-3 menu p-2 shadow bg-white text-base-content rounded-box w-52 z-50">
                        @auth
                            <li><a href="{{ route('profile.edit') }}">Profile</a></li>
                            <li>
                                <button type=" button" onclick="document.getElementById('logout-form').submit()">
                                    Log Out
                                </button>
                            </li>
                        @else
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @endauth
                    </ul>
                </div>

            </div>

            {{-- Right: mobile icons (search + cart only) --}}
            <div class="absolute right-0 flex lg:hidden items-center gap-1">
                <button type="button" class="p-2" :class="(scrolled || hovered) ? 'text-gray-900' : 'text-white'"
                    aria-label="Search">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                    </svg>
                </button>
                <a href="#" class="p-2" :class="(scrolled || hovered) ? 'text-gray-900' : 'text-white'"
                    aria-label="Cart">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m-.75 9h9a2.25 2.25 0 002.25-2.25l-.75-9a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25l-.75 9a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </a>
            </div>

        </div>
    </div>

    {{-- logout form, shared by desktop dropdown + mobile drawer --}}
    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
        @csrf
    </form>

    {{-- Shared full-width desktop mega menu panel --}}
    <div x-show="activeMenu !== null" x-transition.opacity x-cloak
        class="absolute inset-x-0 top-full w-full bg-white text-gray-900 shadow-xl">
        <div class="px-6 sm:px-10 lg:px-16 pt-8 pb-12">
            @foreach ($megaMenus as $key => $menu)
                <x-mega-menu-panel :menu-key="$key" class="flex items-start gap-16 min-h-[300px]">
                    @foreach ($menu['columns'] as $column)
                        <x-mega-menu-column :title="$column['title']">
                            @foreach ($column['links'] as $link)
                                <li><a href="#" class="text-base hover:underline underline-offset-4">{{ $link }}</a></li>
                            @endforeach
                        </x-mega-menu-column>
                    @endforeach
                    @if (!empty($menu['images']))
                        <div class="flex gap-6 ml-auto">
                            @foreach ($menu['images'] as $img)
                                <x-mega-menu-promo :image="$img['src']" :caption="$img['caption']" />
                            @endforeach
                        </div>
                    @endif
                </x-mega-menu-panel>
            @endforeach
        </div>
    </div>

    {{-- Mobile drawer backdrop --}}
    <div x-show="mobileOpen" x-transition.opacity x-cloak @click="mobileOpen = false"
        class="fixed inset-0 bg-black/40 z-40 lg:hidden"></div>

    {{-- Mobile drawer panel --}}
    <div x-show="mobileOpen" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 w-full max-w-sm bg-white text-gray-900 z-50 lg:hidden overflow-y-auto">

        <div class="flex items-center justify-end px-6 h-20 border-b border-base-200">
            <button type="button" @click="mobileOpen = false" class="p-2" aria-label="Close menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="px-6 py-4">
            @foreach ($megaMenus as $key => $menu)
                <div class="border-b border-base-200" x-data="{ menuOpen: false }">
                    <button type="button" @click="menuOpen = !menuOpen"
                        class="w-full flex items-center justify-between py-4 text-base font-medium">
                        {{ $menu['label'] }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200"
                            :class="menuOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="menuOpen" x-collapse class="pb-3">
                        @foreach ($menu['columns'] as $column)
                            <div x-data="{ colOpen: false }" class="border-t border-base-200 first:border-t-0">
                                <button type="button" @click="colOpen = !colOpen"
                                    class="w-full flex items-center justify-between py-3 pl-4 pr-1  font-medium text-base">
                                    {{ $column['title'] }}
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-3.5 w-3.5 transition-transform duration-200"
                                        :class="colOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <ul x-show="colOpen" x-collapse class="pb-3 pl-12 space-y-2.5">
                                    @foreach ($column['links'] as $link)
                                        <li><a href="#" class="text-base">{{ $link }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="pt-4">
                @auth
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 py-3 text-sm font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        Account
                    </a>
                    <button type="button" onclick="document.getElementById('logout-form').submit()"
                        class="flex items-center gap-2 py-3 text-sm font-medium">
                        Log Out
                    </button>
                @else
                    <a href="{{ route('login') }}" class="flex items-center gap-2 py-3 text-sm font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        Account
                    </a>
                @endauth
            </div>
        </div>
    </div>

</nav>
