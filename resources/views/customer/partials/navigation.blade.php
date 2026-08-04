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
                <a href="{{ route('community.index') }}" class="koku-eyebrow">Community</a>
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
                    <div x-show="profileOpen" x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="translate-y-2 scale-[.97] opacity-0" x-transition:enter-end="translate-y-0 scale-100 opacity-100" x-transition:leave="transition duration-150 ease-in" x-transition:leave-start="translate-y-0 scale-100 opacity-100" x-transition:leave-end="translate-y-2 scale-[.97] opacity-0" x-cloak class="absolute right-0 top-full mt-3 w-[22rem] origin-top-right overflow-hidden rounded-3xl border border-white/80 bg-white/95 text-sm text-[var(--koku-ink)] shadow-[0_28px_80px_rgba(15,24,44,.24)] backdrop-blur-2xl">
                        @auth
                            <div class="relative overflow-hidden bg-[var(--koku-indigo-deep)] p-5 text-white"><div class="absolute -right-8 -top-10 size-28 rounded-full bg-white/10 blur-2xl"></div><div class="relative flex items-center gap-3.5">@if(auth()->user()->avatar)<img src="{{ Storage::url(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="size-12 rounded-2xl object-cover shadow-lg ring-1 ring-white/20">@else<span class="flex size-12 items-center justify-center rounded-2xl bg-white text-base font-semibold text-[var(--koku-indigo)] shadow-lg">{{ Str::upper(Str::substr(auth()->user()->name, 0, 1)) }}</span>@endif<div class="min-w-0 flex-1"><div class="flex items-center gap-2"><p class="truncate font-medium">{{ auth()->user()->name }}</p><i class="size-1.5 rounded-full bg-emerald-400"></i></div><p class="mt-1 truncate text-[11px] text-white/50">{{ auth()->user()->email }}</p></div></div></div>
                            <div class="grid grid-cols-2 gap-2 p-3"><a href="{{ route('orders.index') }}" class="group rounded-2xl bg-[#f4f2ee] p-4 transition hover:-translate-y-0.5 hover:bg-[#ebe7e0]"><svg class="size-5 text-[var(--koku-indigo)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 8h14l-1 12H6L5 8Z"/><path d="M9 8V6a3 3 0 016 0v2"/></svg><span class="mt-3 block text-xs font-medium">My orders</span><span class="mt-1 block text-[10px] text-[var(--koku-muted)]">Track purchases</span></a><a href="{{ route('addresses.index') }}" class="group rounded-2xl bg-[#f4f2ee] p-4 transition hover:-translate-y-0.5 hover:bg-[#ebe7e0]"><svg class="size-5 text-[var(--koku-indigo)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1116 0Z"/><circle cx="12" cy="10" r="2.5"/></svg><span class="mt-3 block text-xs font-medium">Addresses</span><span class="mt-1 block text-[10px] text-[var(--koku-muted)]">Manage delivery</span></a></div>
                            <div class="border-t border-[var(--koku-line)]/60 p-3"><a href="{{ route('profile.edit') }}" class="flex items-center justify-between rounded-xl px-3 py-3 transition hover:bg-[#f4f2ee]"><span class="text-xs font-medium">Profile & security</span><span class="flex size-7 items-center justify-center rounded-full bg-[#f4f2ee]" aria-hidden="true">&rarr;</span></a><button type="button" onclick="document.getElementById('logout-form').submit()" class="mt-1 block w-full rounded-xl px-3 py-2.5 text-left text-xs text-[var(--koku-muted)] transition hover:bg-red-50 hover:text-red-700">Sign out</button></div>
                        @else
                            <div class="relative overflow-hidden bg-[var(--koku-indigo-deep)] px-6 pb-8 pt-7 text-white"><div class="absolute -right-12 -top-16 size-40 rounded-full bg-white/10 blur-2xl"></div><span class="relative flex size-10 items-center justify-center rounded-xl bg-white/10"><svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="8" r="3.5"/><path d="M5.5 20a6.5 6.5 0 0113 0"/></svg></span><h2 class="relative mt-5 font-serif text-2xl tracking-[-.04em]">Your collection,<br>made personal.</h2><p class="relative mt-3 text-xs leading-5 text-white/55">Save favourites, follow orders and enjoy a faster checkout.</p></div>
                            <div class="p-4"><a href="{{ route('login') }}" class="block rounded-2xl bg-[var(--koku-indigo)] px-4 py-3.5 text-center text-xs font-medium text-white shadow-lg shadow-[var(--koku-indigo)]/20 transition hover:-translate-y-0.5 hover:bg-[var(--koku-indigo-deep)]">Sign in to Koku</a><a href="{{ route('register') }}" class="mt-2 block rounded-2xl px-4 py-3 text-center text-xs font-medium text-[var(--koku-indigo)] transition hover:bg-[#f4f2ee]">Create a new account</a><div class="mt-3 flex items-center justify-center gap-2 text-[10px] text-[var(--koku-muted)]"><svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V7a4 4 0 018 0v3"/></svg> Secure, private access</div></div>
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

    <div x-show="searchOpen" x-cloak class="fixed inset-0 z-[70] flex items-start justify-center bg-[var(--koku-indigo-deep)]/55 px-4 pt-24 text-[var(--koku-ink)] backdrop-blur-sm sm:pt-32" @click.self="searchOpen=false" @keydown.escape.window="searchOpen = false">
        <div x-transition.origin.top class="w-full max-w-2xl rounded-3xl bg-white p-5 shadow-[0_30px_90px_rgba(10,18,35,.3)] sm:p-7"><div class="flex items-center justify-between"><div><p class="koku-eyebrow text-[var(--koku-indigo)]">Search Koku</p><p class="mt-1 text-xs text-[var(--koku-muted)]">Find by watch, maker or movement</p></div><button type="button" @click="searchOpen=false" class="flex size-9 items-center justify-center rounded-full bg-[#f4f2ee]" aria-label="Close search">×</button></div><form action="{{ route('shop.index') }}" method="GET" class="mt-5 flex items-center rounded-2xl border border-[var(--koku-line)] bg-[#f8f7f4] px-4 focus-within:border-[var(--koku-indigo)] focus-within:bg-white"><svg class="size-5 shrink-0 text-[var(--koku-muted)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="6.5"/><path d="m16 16 4 4"/></svg><input autofocus type="search" name="search" placeholder="Search watches…" class="min-w-0 flex-1 border-0 bg-transparent px-3 py-4 text-sm shadow-none placeholder:text-[var(--koku-muted)]/60 focus:ring-0"><button class="rounded-xl bg-[var(--koku-indigo)] px-4 py-2.5 text-xs font-medium text-white" aria-label="Submit search">Search</button></form><div class="mt-4 flex flex-wrap gap-2"><span class="text-[10px] uppercase tracking-[.12em] text-[var(--koku-muted)]">Popular</span><a href="{{ route('shop.index', ['movements'=>['automatic']]) }}" class="rounded-full bg-[#f4f2ee] px-3 py-1.5 text-[10px]">Automatic</a><a href="{{ route('shop.index', ['movements'=>['quartz']]) }}" class="rounded-full bg-[#f4f2ee] px-3 py-1.5 text-[10px]">Quartz</a></div></div>
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
            <a href="{{ route('community.index') }}" class="mt-3 block rounded-xl bg-[var(--koku-indigo)] px-4 py-3 text-center text-sm text-white">Wrist Stories community</a>
        </div>
    </div>
</nav>
