@php
    $pageTitle = match (true) {
        request()->routeIs('admin.dashboard') => 'Dashboard',
        request()->routeIs('admin.products.create') => 'Add product',
        request()->routeIs('admin.products.edit') => 'Edit product',
        request()->routeIs('admin.products.*') => 'Products',
        request()->routeIs('admin.categories.create') => 'Add category',
        request()->routeIs('admin.categories.edit') => 'Edit category',
        request()->routeIs('admin.categories.*') => 'Categories',
        request()->routeIs('admin.brands.create') => 'Add brand',
        request()->routeIs('admin.brands.edit') => 'Edit brand',
        request()->routeIs('admin.brands.*') => 'Brands',
        request()->routeIs('admin.orders.*') => 'Orders',
        request()->routeIs('admin.coupons.create') => 'Add coupon',
        request()->routeIs('admin.coupons.edit') => 'Edit coupon',
        request()->routeIs('admin.coupons.*') => 'Coupons',
        request()->routeIs('admin.customers.*') => 'Customers',
        request()->routeIs('admin.shipping.*') => 'Shipping',
        request()->routeIs('admin.reports.*') => 'Reports & insights',
        request()->routeIs('admin.settings.*') => 'Store settings',
        request()->routeIs('admin.profile') => 'Admin profile',
        default => 'Administration',
    };

    $pageSection = match (true) {
        request()->routeIs('admin.products.*', 'admin.categories.*', 'admin.brands.*') => 'Catalog',
        request()->routeIs('admin.orders.*', 'admin.coupons.*') => 'Sales',
        request()->routeIs('admin.customers.*', 'admin.shipping.*') => 'Operations',
        request()->routeIs('admin.reports.*') => 'Insights',
        request()->routeIs('admin.settings.*') => 'System',
        request()->routeIs('admin.profile') => 'Account',
        default => 'Overview',
    };

    $adminAvatar = auth()->user()?->avatar;
    $adminAvatarUrl = $adminAvatar
        ? (str_starts_with($adminAvatar, 'http') ? $adminAvatar : Storage::url($adminAvatar))
        : null;
    $adminInitial = str(auth()->user()->name)->substr(0, 1)->upper();
@endphp

<!DOCTYPE html>
<html lang="en" data-theme="ticksadmin">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }} | {{ config('app.name', 'Ticks') }} Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        (() => {
            const preference = localStorage.getItem('ticks-admin-theme') || 'dark';
            const dark = preference === 'dark' ||
                (preference === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.setAttribute('data-theme', dark ? 'ticksadmindark' : 'ticksadmin');
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body
    class="min-h-screen overflow-x-hidden bg-[var(--admin-canvas)] font-admin text-base-content antialiased"
    x-data="{
        mobileOpen: false,
        desktop: window.innerWidth >= 1024,
        viewportWidth: window.innerWidth,
        adminDrawerOpen: false,
        sidebarCollapsed: localStorage.getItem('ticks-admin-sidebar-collapsed') === 'true',
        themePreference: localStorage.getItem('ticks-admin-theme') || 'dark',
        darkMode: false,
        applyTheme() {
            this.darkMode = this.themePreference === 'dark' ||
                (this.themePreference === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.setAttribute('data-theme', this.darkMode ? 'ticksadmindark' : 'ticksadmin');
            document.documentElement.style.colorScheme = this.darkMode ? 'dark' : 'light';
        },
        setTheme(theme) {
            this.themePreference = theme;
            localStorage.setItem('ticks-admin-theme', theme);
            this.applyTheme();
        },
        toggleTheme() {
            this.setTheme(this.darkMode ? 'light' : 'dark');
        },
        setSidebarCollapsed(collapsed) {
            this.sidebarCollapsed = collapsed;
            localStorage.setItem('ticks-admin-sidebar-collapsed', collapsed);
        },
        toggleSidebar() {
            if (!this.desktop) {
                this.mobileOpen = !this.mobileOpen;
                return;
            }
            this.setSidebarCollapsed(!this.sidebarCollapsed);
        }
    }"
    x-init="
        applyTheme();
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (themePreference === 'system') applyTheme();
        });
    "
    @resize.window="viewportWidth = window.innerWidth; desktop = viewportWidth >= 1024; if (desktop) mobileOpen = false"
    @admin-drawer-state.window="adminDrawerOpen = $event.detail.open"
    @keydown.escape.window="mobileOpen = false"
    @keydown.window="if ($event.key === '/' && !['INPUT', 'TEXTAREA', 'SELECT'].includes($event.target.tagName)) { $event.preventDefault(); $refs.globalSearch.focus(); }">

    <div x-cloak x-show="mobileOpen"
        x-transition:enter="transition-opacity duration-200 motion-reduce:transition-none"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-150 motion-reduce:transition-none"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-black/70 backdrop-blur-[2px] lg:hidden"
        @click="mobileOpen = false" aria-hidden="true"></div>

    <aside
        class="fixed inset-y-0 left-0 z-50 flex w-[15.5rem] -translate-x-full flex-col border-r border-[var(--admin-border)] bg-[var(--admin-sidebar)] text-base-content shadow-2xl shadow-black/30 transition-[width,transform] duration-200 ease-out motion-reduce:transition-none lg:translate-x-0 lg:shadow-none"
        :class="{
            '!translate-x-0': mobileOpen,
            'lg:!w-[4.75rem]': sidebarCollapsed,
            'lg:!w-[15.5rem]': !sidebarCollapsed
        }"
        aria-label="Admin navigation">

        <div class="flex h-16 shrink-0 items-center border-b border-[var(--admin-border)] px-3"
            :class="desktop && sidebarCollapsed ? 'justify-center !px-2' : 'justify-between'">
            <a href="{{ route('admin.dashboard') }}"
                class="flex shrink-0 items-center text-base-content outline-none transition hover:text-accent focus-visible:rounded-lg focus-visible:ring-2 focus-visible:ring-accent"
                @click="if (desktop && sidebarCollapsed) { $event.preventDefault(); setSidebarCollapsed(false) }"
                :aria-label="desktop && sidebarCollapsed ? 'Expand TICKS navigation' : 'TICKS dashboard'"
                :title="desktop && sidebarCollapsed ? 'Expand sidebar' : 'TICKS dashboard'">
                <x-brand-logo x-cloak x-show="!desktop || !sidebarCollapsed" class="h-7 w-[8.4rem]" />
                <x-brand-logo x-cloak x-show="desktop && sidebarCollapsed" variant="mark" class="size-8" />
            </a>

            <button x-cloak x-show="!desktop || !sidebarCollapsed" type="button"
                class="btn btn-square size-9 min-h-9 rounded-lg border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] text-base-content/55 shadow-admin-control hover:border-[var(--admin-border-strong)] hover:text-base-content"
                @click="toggleSidebar"
                :aria-label="desktop ? 'Collapse sidebar' : 'Close sidebar'"
                :aria-expanded="desktop ? (!sidebarCollapsed).toString() : mobileOpen.toString()">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path x-show="desktop" stroke-linecap="round" stroke-linejoin="round"
                        d="M4 5h16v14H4V5Zm5 0v14m6-9-3 2 3 2" />
                    <path x-show="!desktop" stroke-linecap="round" d="m6 6 12 12M18 6 6 18" />
                </svg>
            </button>
        </div>

        @include('layouts.partials.admin-sidebar-navigation')

        <div class="relative shrink-0 border-t border-[var(--admin-border)] p-2.5"
            x-data="{ accountOpen: false }" @keydown.escape.window="accountOpen = false">
            <div x-cloak x-show="accountOpen" @click.outside="accountOpen = false"
                x-transition:enter="transition duration-150 ease-out motion-reduce:transition-none"
                x-transition:enter-start="translate-y-2 scale-95 opacity-0"
                x-transition:enter-end="translate-y-0 scale-100 opacity-100"
                x-transition:leave="transition duration-100 ease-in motion-reduce:transition-none"
                x-transition:leave-start="translate-y-0 scale-100 opacity-100"
                x-transition:leave-end="translate-y-2 scale-95 opacity-0"
                class="absolute bottom-full z-50 mb-2 w-[calc(100%-1.25rem)] overflow-hidden rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] p-1.5 text-base-content shadow-admin-raised"
                :class="desktop && sidebarCollapsed ? 'left-[calc(100%+0.6rem)] !bottom-0 !w-64' : 'left-2.5'">
                <div class="rounded-lg border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] px-3 py-2.5">
                    <strong class="block truncate text-xs font-semibold">{{ auth()->user()->name }}</strong>
                    <span class="mt-0.5 block truncate text-[10px] text-base-content/45">{{ auth()->user()->email }}</span>
                </div>
                <a href="{{ route('admin.profile') }}" @click="accountOpen = false; mobileOpen = false"
                    class="mt-1 flex min-h-10 items-center gap-2.5 rounded-lg px-3 text-xs font-medium text-base-content/65 transition hover:bg-[var(--admin-surface-soft)] hover:text-base-content focus:outline-none focus-visible:ring-2 focus-visible:ring-accent">
                    <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <circle cx="12" cy="8" r="3.5" />
                        <path stroke-linecap="round" d="M5 20a7 7 0 0 1 14 0" />
                    </svg>
                    Profile settings
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex min-h-10 w-full items-center gap-2.5 rounded-lg px-3 text-left text-xs font-medium text-error transition hover:bg-error/10 focus:outline-none focus-visible:ring-2 focus-visible:ring-error">
                        <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10 5H5v14h5m4-4 4-3-4-3m4 3H9" />
                        </svg>
                        Sign out
                    </button>
                </form>
            </div>

            <button type="button" @click="accountOpen = !accountOpen"
                :aria-expanded="accountOpen.toString()" aria-label="Open administrator account menu"
                class="group flex min-h-12 w-full items-center rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] p-1.5 text-left shadow-admin-control transition hover:border-[var(--admin-border-strong)] hover:bg-[var(--admin-surface-soft)] focus:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                :class="desktop && sidebarCollapsed ? 'justify-center !p-1' : 'gap-2.5'">
                <div class="avatar {{ $adminAvatarUrl ? '' : 'avatar-placeholder' }} shrink-0">
                    <div class="w-8 rounded-lg bg-accent text-accent-content ring-1 ring-accent/30 ring-offset-2 ring-offset-[var(--admin-surface-raised)]">
                        @if ($adminAvatarUrl)
                            <img src="{{ $adminAvatarUrl }}" alt="{{ auth()->user()->name }}"
                                class="size-full object-cover">
                        @else
                            <span class="text-[11px] font-bold">{{ $adminInitial }}</span>
                        @endif
                    </div>
                </div>
                <span x-cloak x-show="!desktop || !sidebarCollapsed" class="min-w-0 flex-1">
                    <strong class="block truncate text-[11px] font-semibold">{{ auth()->user()->name }}</strong>
                    <small class="mt-0.5 block text-[9px] font-normal text-base-content/40">Administrator</small>
                </span>
                <svg x-cloak x-show="!desktop || !sidebarCollapsed"
                    class="size-3.5 shrink-0 text-base-content/35 transition-transform"
                    :class="{ 'rotate-180': accountOpen }" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m7 10 5 5 5-5" />
                </svg>
            </button>
        </div>
    </aside>

    <div
        class="min-h-screen pt-16 transition-[margin] duration-200 ease-out motion-reduce:transition-none lg:ml-[15.5rem]"
        :class="sidebarCollapsed ? 'lg:!ml-[4.75rem]' : 'lg:!ml-[15.5rem]'">
        <header
            class="fixed left-0 right-0 top-0 z-30 flex h-16 items-center gap-3 border-b border-[var(--admin-border)] bg-[var(--admin-sidebar)] px-3 transition-[left] duration-200 ease-out motion-reduce:transition-none sm:px-4 lg:left-[15.5rem] lg:px-5"
            :class="sidebarCollapsed ? 'lg:!left-[4.75rem]' : 'lg:!left-[15.5rem]'">
            <button type="button"
                class="btn btn-square size-9 min-h-9 shrink-0 rounded-lg border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] text-base-content/65 shadow-admin-control hover:border-[var(--admin-border-strong)] hover:text-base-content lg:hidden"
                @click="mobileOpen = true" aria-label="Open sidebar">
                <svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
                </svg>
            </button>

            <div class="hidden min-w-[9rem] items-center gap-2 xl:flex" aria-label="Current location">
                <svg class="size-3.5 shrink-0 text-accent" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 4h6v6H4V4Zm10 0h6v6h-6V4ZM4 14h6v6H4v-6Zm10 0h6v6h-6v-6Z" />
                </svg>
                <span class="text-[10px] font-medium text-base-content/40">{{ $pageSection }}</span>
                <span class="text-base-content/20">/</span>
                <strong class="truncate text-[11px] font-semibold text-base-content">{{ $pageTitle }}</strong>
            </div>

            <label
                class="input ml-0 flex h-10 w-full max-w-[25rem] items-center gap-2 rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] px-3 shadow-admin-control transition focus-within:border-accent/60 focus-within:outline-none sm:ml-2 xl:ml-5">
                <svg class="size-4 shrink-0 text-base-content/35" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8">
                    <circle cx="11" cy="11" r="7" />
                    <path stroke-linecap="round" d="m20 20-4-4" />
                </svg>
                <input x-ref="globalSearch" type="search"
                    class="grow border-0 bg-transparent p-0 text-[11px] shadow-none outline-none ring-0 placeholder:text-base-content/35 focus:border-0 focus:outline-none focus:ring-0"
                    placeholder="Search products, orders, customers">
                <kbd class="kbd kbd-xs hidden min-h-5 border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-1.5 text-[8px] text-base-content/35 md:inline-flex">/</kbd>
            </label>

            <div class="ml-auto flex shrink-0 items-center gap-2">
                <button type="button"
                    class="btn btn-square size-9 min-h-9 rounded-lg border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] text-base-content/55 shadow-admin-control hover:border-[var(--admin-border-strong)] hover:text-base-content"
                    @click="toggleTheme"
                    :aria-label="darkMode ? 'Switch to light mode' : 'Switch to dark mode'"
                    :title="darkMode ? 'Light mode' : 'Dark mode'">
                    <svg x-cloak x-show="!darkMode" class="size-4" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20 15.2A8 8 0 0 1 8.8 4 8 8 0 1 0 20 15.2Z" />
                    </svg>
                    <svg x-cloak x-show="darkMode" class="size-4" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="3.5" />
                        <path stroke-linecap="round"
                            d="M12 2.5V5m0 14v2.5M2.5 12H5m14 0h2.5M5.3 5.3 7 7m10 10 1.7 1.7m0-13.4L17 7M7 17l-1.7 1.7" />
                    </svg>
                </button>

                <div class="indicator">
                    <span class="indicator-item right-1.5 top-1.5 size-1.5 rounded-full bg-accent ring-2 ring-[var(--admin-sidebar)]"></span>
                    <button type="button"
                        class="btn btn-square size-9 min-h-9 rounded-lg border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] text-base-content/55 shadow-admin-control hover:border-[var(--admin-border-strong)] hover:text-base-content"
                        aria-label="Notifications">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.5 18.5a2.5 2.5 0 0 1-5 0M18 9a6 6 0 0 0-12 0c0 6-2.5 7-2.5 8.5h17C20.5 16 18 15 18 9Z" />
                        </svg>
                    </button>
                </div>
            </div>
        </header>

        @include('layouts.partials.admin-notifications')

        <main class="min-h-[calc(100vh-4rem)] bg-[var(--admin-canvas)] px-3 pb-8 pt-3 sm:px-4 sm:pt-4 lg:px-5">
            <div class="mx-auto w-full max-w-[112rem]">{{ $slot }}</div>
        </main>
    </div>

    @livewireScripts
</body>

</html>
