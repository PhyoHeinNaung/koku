@php
    $navItems = [
        ['Dashboard', 'admin.dashboard', 'admin.dashboard', 'M4 4h6v6H4V4Zm10 0h6v6h-6V4ZM4 14h6v6H4v-6Zm10 0h6v6h-6v-6Z'],
        ['Orders', 'admin.orders.index', 'admin.orders.*', 'M5 4h14v16H5V4Zm3 4h8M8 12h8m-8 4h5'],
        ['Products', 'admin.products.index', 'admin.products.*', 'm12 3 8 4.5-8 4.5-8-4.5L12 3Zm-8 9 8 4.5 8-4.5M4 16.5 12 21l8-4.5'],
        ['Categories', 'admin.categories.index', 'admin.categories.*', 'M4 4h6v6H4V4Zm10 0h6v6h-6V4ZM4 14h6v6H4v-6Zm10 0h6v6h-6v-6Z'],
        ['Brands', 'admin.brands.index', 'admin.brands.*', 'M12 3.5 20 8v8l-8 4.5L4 16V8l8-4.5Z'],
        ['Coupons', 'admin.coupons.index', 'admin.coupons.*', 'M4 9V5h4l12 12-4 4L4 9Zm3-1h.01M8 16l8-8'],
        ['Customers', 'admin.customers.index', 'admin.customers.*', 'M16 20v-1a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4m6.5-8a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm7-1a3 3 0 0 1 0 6'],
        ['Shipping', 'admin.shipping.index', 'admin.shipping.*', 'M3 6h11v10H3V6Zm11 4h4l3 3v3h-7v-6ZM7 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm10 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z'],
        ['Reviews', 'admin.reviews.index', 'admin.reviews.*', 'm12 3 2.7 5.5 6.1.9-4.4 4.3 1 6.1-5.4-2.9-5.4 2.9 1-6.1-4.4-4.3 6.1-.9L12 3Z'],
        ['Community', 'admin.community.index', 'admin.community.*', 'M4 18v-2.5A3.5 3.5 0 0 1 7.5 12h3a3.5 3.5 0 0 1 3.5 3.5V18M9 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm7.5 1a2.5 2.5 0 1 0 0-5'],
        ['Reports', 'admin.reports.index', 'admin.reports.*', 'M4 19V9m5 10V5m5 14v-7m5 7V3'],
    ];
@endphp

<nav class="min-h-0 flex-1 overflow-y-auto px-2.5 py-2" aria-label="Primary navigation">
    <ul class="space-y-0.5">
        @foreach ($navItems as [$label, $route, $active, $icon])
            <li>
                <a href="{{ route($route) }}" @click="mobileOpen = false"
                    @if(request()->routeIs($active)) aria-current="page" @endif
                    class="admin-nav-link flex h-9 items-center gap-3 px-3 text-[12px] font-medium {{ request()->routeIs($active) ? 'is-active' : '' }}"
                    :class="desktop && sidebarCollapsed ? 'justify-center !px-0' : ''" title="{{ $label }}">
                    <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}" /></svg>
                    <span x-cloak x-show="!desktop || !sidebarCollapsed">{{ $label }}</span>
                </a>
            </li>
        @endforeach
    </ul>

    <div x-cloak x-show="!desktop || !sidebarCollapsed" class="mx-3 my-3 border-t border-white/8"></div>
    <ul class="space-y-0.5">
        @foreach ([
            ['Store settings', 'admin.settings.index', 'admin.settings.*', 'M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm0-12v2m0 13v2m8.5-8.5h-2m-13 0h-2m14.5-6-1.5 1.5m-9 9L6 18m12 0-1.5-1.5m-9-9L6 6'],
            ['View storefront', 'home', '__none__', 'M14 5h5v5m0-5-8 8m8 1v5H5V5h5'],
        ] as [$label, $route, $active, $icon])
            <li>
                <a href="{{ route($route) }}" @click="mobileOpen = false" class="admin-nav-link flex h-9 items-center gap-3 px-3 text-[12px] font-medium {{ request()->routeIs($active) ? 'is-active' : '' }}" :class="desktop && sidebarCollapsed ? 'justify-center !px-0' : ''">
                    <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}" /></svg>
                    <span x-cloak x-show="!desktop || !sidebarCollapsed">{{ $label }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</nav>
