@php
    $pageTitle = match (true) {
        request()->routeIs('admin.dashboard') => 'Dashboard',
        request()->routeIs('admin.products.create') => 'Add product',
        request()->routeIs('admin.products.edit') => 'Edit product',
        request()->routeIs('admin.products.*') => 'Products',
        request()->routeIs('admin.categories.*') => 'Categories',
        request()->routeIs('admin.brands.*') => 'Brands',
        request()->routeIs('admin.orders.*') => 'Orders',
        request()->routeIs('admin.coupons.*') => 'Coupons',
        request()->routeIs('admin.customers.*') => 'Customers',
        request()->routeIs('admin.shipping.*') => 'Shipping',
        request()->routeIs('admin.reviews.*') => 'Review moderation',
        request()->routeIs('admin.community.*') => 'Community moderation',
        request()->routeIs('admin.reports.*') => 'Reports & insights',
        request()->routeIs('admin.settings.*') => 'Store settings',
        request()->routeIs('admin.profile') => 'Admin profile',
        default => 'Administration',
    };
    $adminAvatar = auth()->user()?->avatar;
    $adminAvatarUrl = $adminAvatar ? (str_starts_with($adminAvatar, 'http') ? $adminAvatar : Storage::url($adminAvatar)) : null;
    $adminInitial = str(auth()->user()->name)->substr(0, 1)->upper();

@endphp
<!DOCTYPE html>
<html lang="en" data-theme="kokuadmin">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Koku commerce administration workspace">
    <title>{{ $pageTitle }} | {{ config('app.name', 'Koku') }} Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&family=Geist+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-admin antialiased" x-data="{
    mobileOpen: false,
    desktop: window.innerWidth >= 1024,
    sidebarCollapsed: localStorage.getItem('koku-admin-rail') === 'collapsed',
    toggleRail() {
        if (!this.desktop) { this.mobileOpen = !this.mobileOpen; return; }
        this.setSidebarCollapsed(!this.sidebarCollapsed);
    },
    setSidebarCollapsed(collapsed) {
        this.sidebarCollapsed = Boolean(collapsed);
        localStorage.setItem('koku-admin-rail', this.sidebarCollapsed ? 'collapsed' : 'open');
    }
}" @resize.window="desktop = window.innerWidth >= 1024; if (desktop) mobileOpen = false"
@keydown.escape.window="mobileOpen = false"
@keydown.window="if ($event.key === '/' && !['INPUT','TEXTAREA','SELECT'].includes($event.target.tagName)) { $event.preventDefault(); $refs.adminSearch?.focus(); }">
    <a href="#main-content" class="admin-skip-link">Skip to content</a>

    <button x-cloak x-show="mobileOpen" type="button" class="fixed inset-0 z-40 bg-[#101b2e]/50 lg:hidden" @click="mobileOpen=false" aria-label="Close navigation"></button>

    <aside class="admin-shell-rail fixed inset-y-0 left-0 z-50 flex w-[12.5rem] -translate-x-full flex-col bg-[#0b1625] text-white transition-[width,transform] duration-200 lg:translate-x-0"
        :class="{'!translate-x-0': mobileOpen, 'lg:!w-[4rem]': sidebarCollapsed, 'lg:!w-[12.5rem]': !sidebarCollapsed}">
        <div class="flex h-[4.5rem] shrink-0 items-center px-5" :class="sidebarCollapsed ? 'lg:justify-center lg:!px-0' : 'justify-between'">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 text-white"
                x-show="!desktop || !sidebarCollapsed">
                <span class="grid size-7 place-items-center border border-white/65 text-[13px] font-semibold">K</span>
                <strong x-cloak x-show="!desktop || !sidebarCollapsed" class="text-[17px] font-semibold tracking-[-.04em]">Koku</strong>
            </a>
            <button x-cloak x-show="!desktop || !sidebarCollapsed" type="button" @click="toggleRail" class="grid size-8 place-items-center text-white/45 hover:text-white" aria-label="Collapse navigation" :aria-expanded="(!sidebarCollapsed).toString()">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 5h16v14H4V5Zm5 0v14m6-9-3 2 3 2"/></svg>
            </button>
            <button x-cloak x-show="desktop && sidebarCollapsed" type="button" @click="toggleRail"
                class="grid size-9 place-items-center text-white/55 hover:text-white" aria-label="Expand navigation" :aria-expanded="(!sidebarCollapsed).toString()">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 5h16v14H4V5Zm5 0v14m3-9 3 2-3 2"/></svg>
            </button>
        </div>

        <div x-cloak x-show="!desktop || !sidebarCollapsed" class="px-3.5 pb-3">
            <label class="flex h-9 items-center gap-2 rounded-[18px] border border-white/14 px-3 text-white/45 focus-within:border-white/35 focus-within:text-white">
                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                <input x-ref="adminSearch" type="search" class="min-w-0 grow border-0 bg-transparent p-0 text-[11px] text-white outline-none ring-0 placeholder:text-white/35 focus:border-0 focus:ring-0" placeholder="Search">
                <kbd class="font-mono text-[9px] text-white/25">⌘ F</kbd>
            </label>
        </div>

        @include('layouts.partials.admin-sidebar-navigation')

        <div class="relative mt-auto p-3" x-data="{accountOpen:false}">
            <div x-cloak x-show="accountOpen" @click.outside="accountOpen=false" class="absolute bottom-full left-3 z-20 mb-2 w-[calc(100%-1.5rem)] border border-white/10 bg-[#111f33] p-1.5 shadow-2xl" :class="sidebarCollapsed ? 'lg:left-[calc(100%+.5rem)] lg:bottom-0 lg:w-56' : ''">
                <a href="{{ route('admin.profile') }}" class="block px-3 py-2 text-[11px] text-white/65 hover:bg-white/5 hover:text-white">Profile settings</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="w-full px-3 py-2 text-left text-[11px] text-[#e6aaa5] hover:bg-white/5">Sign out</button></form>
            </div>
            <button type="button" @click="accountOpen=!accountOpen" class="flex h-11 w-full items-center gap-2.5 text-left" :class="sidebarCollapsed ? 'lg:justify-center' : ''">
                <span class="grid size-7 shrink-0 place-items-center overflow-hidden rounded-full bg-[#d9bfa5] text-[10px] font-semibold text-[#192640]">
                    @if($adminAvatarUrl)<img src="{{ $adminAvatarUrl }}" alt="{{ auth()->user()->name }}" class="size-full object-cover">@else{{ $adminInitial }}@endif
                </span>
                <span x-cloak x-show="!desktop || !sidebarCollapsed" class="min-w-0 flex-1"><strong class="block truncate text-[11px] font-medium text-white">{{ auth()->user()->name }}</strong><small class="block truncate text-[9px] text-white/35">Administrator</small></span>
                <span x-cloak x-show="!desktop || !sidebarCollapsed" class="text-white/30">•••</span>
            </button>
        </div>
    </aside>

    <div class="min-h-screen bg-white transition-[margin] duration-200 lg:ml-[12.5rem]" :class="sidebarCollapsed ? 'lg:!ml-[4rem]' : 'lg:!ml-[12.5rem]'">
        <button type="button" @click="mobileOpen=true" class="fixed left-3 top-3 z-30 grid size-9 place-items-center border border-[#ddd] bg-white lg:hidden" aria-label="Open navigation"><svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 7h16M4 12h16M4 17h16"/></svg></button>
        @include('layouts.partials.admin-notifications')
        <main id="main-content" class="admin-workspace min-h-screen">
            {{ $slot }}
        </main>
    </div>
    @livewireScripts
</body>
</html>
