@props(['overlay' => false])

@php
    $megaMenus = [
        'shop' => [
            'label' => 'Watches',
            'intro' => 'A considered edit for every rhythm of life.',
            'columns' => [
                ['title' => 'Collection', 'links' => [["Men's watches", route('shop.index', ['genders' => ['men']])], ["Women's watches", route('shop.index', ['genders' => ['women']])], ['Unisex', route('shop.index', ['genders' => ['unisex']])], ['View all', route('shop.index')]]],
                ['title' => 'Style', 'links' => [['Casual & everyday', route('shop.index')], ['Dress & formal', route('shop.index')], ['Sport & dive', route('shop.index')], ['Minimal', route('shop.index')]]],
                ['title' => 'Movement', 'links' => [['Automatic', route('shop.index', ['movements' => ['automatic']])], ['Quartz', route('shop.index', ['movements' => ['quartz']])], ['Mechanical', route('shop.index', ['movements' => ['mechanical']])], ['Chronograph', route('shop.index', ['movements' => ['chronograph']])]]],
            ],
        ],
        'brands' => [
            'label' => 'Brands',
            'intro' => 'Established houses and modern makers, selected by Koku.',
            'columns' => [
                ['title' => 'Japanese', 'links' => [['Seiko', route('shop.index')], ['Citizen', route('shop.index')], ['Casio / G-Shock', route('shop.index')], ['Orient', route('shop.index')]]],
                ['title' => 'Swiss', 'links' => [['Longines', route('shop.index')], ['Tissot', route('shop.index')], ['Hamilton', route('shop.index')], ['View all brands', route('shop.index')]]],
            ],
        ],
        'journal' => [
            'label' => 'Journal',
            'intro' => 'Notes on time, materials, movements and care.',
            'columns' => [
                ['title' => 'Discover', 'links' => [['Our story', '#'], ['Watch care', '#'], ['Movement guide', '#'], ['Contact', '#']]],
            ],
        ],
    ];
@endphp

<nav x-data="{ hovered: false, activeMenu: null, profileOpen: false, mobileOpen: false, searchOpen: false }"
    @mouseleave="hovered = false; activeMenu = null"
    class="sticky inset-x-0 top-0 z-50 border-b border-white/10 bg-[var(--koku-indigo-deep)] text-[#faf8f3]">

    <div class="koku-shell">
        <div class="relative flex h-[4.5rem] items-center lg:h-[4.75rem]">
            <a href="{{ route('home') }}" class="font-serif text-[1.55rem] font-semibold tracking-[-0.06em] sm:text-[1.7rem]" aria-label="Koku home">Koku</a>

            <div class="absolute left-1/2 hidden h-full -translate-x-1/2 items-center gap-8 lg:flex">
                @foreach ($megaMenus as $key => $menu)
                    <button type="button" @mouseenter="hovered = true; activeMenu = '{{ $key }}'"
                        @focus="hovered = true; activeMenu = '{{ $key }}'"
                        class="koku-eyebrow relative flex h-full items-center after:absolute after:inset-x-0 after:bottom-5 after:h-px after:origin-left after:bg-current after:transition-transform"
                        :class="activeMenu === '{{ $key }}' ? 'after:scale-x-100' : 'after:scale-x-0'">
                        {{ $menu['label'] }}
                    </button>
                @endforeach
                <a href="{{ route('shop.index') }}" class="koku-eyebrow">New arrivals</a>
            </div>

            <button type="button" @click="mobileOpen = true" class="koku-icon-button order-3 hover:!bg-white/10 hover:!text-white lg:hidden" aria-label="Open menu">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" d="M3 7h18M3 17h18"/></svg>
            </button>

            <div class="ml-auto flex items-center gap-0.5">
                <button type="button" @click="searchOpen = true" class="koku-icon-button hover:!bg-white/10 hover:!text-white" aria-label="Search">
                    <svg class="size-[1.15rem]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="6.5"/><path stroke-linecap="round" d="m16 16 4.25 4.25"/></svg>
                </button>
                <div class="hidden sm:block"><livewire:customer.wishlist.icon /></div>
                <livewire:customer.cart.drawer />
                <div class="relative hidden sm:block" @click.outside="profileOpen = false">
                    <button type="button" @click="profileOpen = !profileOpen" class="koku-icon-button text-[#faf8f3] hover:!bg-white/10 hover:!text-white" aria-label="Account">
                        <svg class="size-[1.15rem]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="7.5" r="3.5"/><path stroke-linecap="round" d="M5.5 20a6.5 6.5 0 0 1 13 0"/></svg>
                    </button>
                    <div x-show="profileOpen" x-transition x-cloak class="absolute right-0 top-full mt-3 w-52 border border-[var(--koku-line)] bg-[var(--koku-white)] p-2 text-sm text-[var(--koku-ink)] shadow-xl">
                        @auth
                            <a href="{{ route('profile.edit') }}" class="block px-3 py-2.5 hover:bg-[var(--koku-paper)]">My account</a>
                            <button type="button" onclick="document.getElementById('logout-form').submit()" class="block w-full px-3 py-2.5 text-left hover:bg-[var(--koku-paper)]">Sign out</button>
                        @else
                            <a href="{{ route('login') }}" class="block px-3 py-2.5 hover:bg-[var(--koku-paper)]">Sign in</a>
                            <a href="{{ route('register') }}" class="block px-3 py-2.5 hover:bg-[var(--koku-paper)]">Create account</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">@csrf</form>

    <div x-show="activeMenu" x-transition.opacity x-cloak @mouseenter="hovered = true"
        class="absolute inset-x-0 top-full border-b border-[var(--koku-line)] bg-[var(--koku-white)] text-[var(--koku-ink)] shadow-[0_18px_35px_rgba(25,26,24,0.08)]">
        <div class="koku-shell py-10">
            @foreach ($megaMenus as $key => $menu)
                <div x-show="activeMenu === '{{ $key }}'" class="grid grid-cols-[minmax(13rem,1fr)_3fr] gap-16">
                    <div>
                        <p class="font-serif text-2xl leading-snug">{{ $menu['intro'] }}</p>
                        <span class="mt-7 block h-px w-12 bg-[var(--koku-indigo)]"></span>
                    </div>
                    <div class="grid grid-cols-3 gap-10">
                        @foreach ($menu['columns'] as $column)
                            <div>
                                <p class="koku-eyebrow mb-5 text-[var(--koku-muted)]">{{ $column['title'] }}</p>
                                <ul class="space-y-3 text-sm">
                                    @foreach ($column['links'] as [$label, $href])
                                        <li><a href="{{ $href }}" class="transition-colors hover:text-[var(--koku-indigo)]">{{ $label }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div x-show="searchOpen" x-cloak class="fixed inset-0 z-[70] bg-[var(--koku-white)] text-[var(--koku-ink)]" @keydown.escape.window="searchOpen = false">
        <div class="koku-shell flex h-[4.75rem] items-center justify-between border-b border-[var(--koku-line)]">
            <span class="font-serif text-2xl font-semibold tracking-[-0.06em]">Koku</span>
            <button type="button" @click="searchOpen = false" class="koku-icon-button" aria-label="Close search"><svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="m5 5 14 14M19 5 5 19"/></svg></button>
        </div>
        <div class="koku-shell pt-20 sm:pt-28">
            <p class="koku-eyebrow text-[var(--koku-muted)]">Search the collection</p>
            <form action="{{ route('shop.index') }}" method="GET" class="mt-5 flex border-b border-[var(--koku-ink)]">
                <input autofocus type="search" name="search" placeholder="What are you looking for?" class="min-w-0 flex-1 border-0 bg-transparent px-0 py-5 font-serif text-2xl shadow-none placeholder:text-[var(--koku-muted)]/60 focus:ring-0 sm:text-4xl">
                <button class="px-3 text-[var(--koku-indigo)]" aria-label="Submit search"><svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12h14m-5-5 5 5-5 5"/></svg></button>
            </form>
        </div>
    </div>

    <div x-show="mobileOpen" x-transition.opacity x-cloak @click="mobileOpen = false" class="fixed inset-0 z-40 bg-black/45 lg:hidden"></div>
    <div x-show="mobileOpen" x-cloak x-transition:enter="transition duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 z-50 w-full max-w-md overflow-y-auto bg-[var(--koku-white)] text-[var(--koku-ink)] lg:hidden">
        <div class="flex h-[4.75rem] items-center justify-between border-b border-[var(--koku-line)] px-5">
            <span class="font-serif text-2xl font-semibold tracking-[-0.06em]">Koku</span>
            <button type="button" @click="mobileOpen = false" class="koku-icon-button" aria-label="Close menu"><svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="m5 5 14 14M19 5 5 19"/></svg></button>
        </div>
        <div class="px-5 py-3">
            @foreach ($megaMenus as $menu)
                <div x-data="{ open: false }" class="border-b border-[var(--koku-line)]">
                    <button type="button" @click="open = !open" class="flex w-full items-center justify-between py-5 font-serif text-xl"><span>{{ $menu['label'] }}</span><span class="text-[var(--koku-indigo)]" x-text="open ? '−' : '+'"></span></button>
                    <div x-show="open" x-collapse class="pb-6">
                        @foreach ($menu['columns'] as $column)
                            <p class="koku-eyebrow mb-3 mt-5 text-[var(--koku-muted)] first:mt-0">{{ $column['title'] }}</p>
                            <ul class="space-y-3 text-sm">
                                @foreach ($column['links'] as [$label, $href])<li><a href="{{ $href }}">{{ $label }}</a></li>@endforeach
                            </ul>
                        @endforeach
                    </div>
                </div>
            @endforeach
            <div class="grid grid-cols-2 gap-px bg-[var(--koku-line)] mt-8 border border-[var(--koku-line)]">
                <a href="{{ route('wishlist.index') }}" class="bg-[var(--koku-white)] p-4 text-center text-sm">Wishlist</a>
                <a href="{{ auth()->check() ? route('profile.edit') : route('login') }}" class="bg-[var(--koku-white)] p-4 text-center text-sm">Account</a>
            </div>
        </div>
    </div>
</nav>
