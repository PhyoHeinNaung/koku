@php
    $navigationGroups = [
        [
            'id' => 'catalog-navigation',
            'label' => 'Catalog',
            'icon' => 'm12 3 8 4.5-8 4.5-8-4.5L12 3Zm-8 9 8 4.5 8-4.5M4 16.5 12 21l8-4.5',
            'active' => ['admin.categories.*', 'admin.brands.*', 'admin.products.*'],
            'items' => [
                [
                    'label' => 'Categories',
                    'route' => 'admin.categories.index',
                    'active' => 'admin.categories.*',
                    'icon' => 'M5 4h5v5H5V4Zm9 0h5v5h-5V4ZM5 14h5v5H5v-5Zm9 0h5v5h-5v-5Z',
                ],
                [
                    'label' => 'Brands',
                    'route' => 'admin.brands.index',
                    'active' => 'admin.brands.*',
                    'icon' => 'M12 3.5 20 8v8l-8 4.5L4 16V8l8-4.5Zm0 0V12m8-4-8 4-8-4',
                ],
                [
                    'label' => 'Products',
                    'route' => 'admin.products.index',
                    'active' => 'admin.products.*',
                    'icon' => 'M5 5.5h14v13H5v-13Zm3 3h8m-8 3h8m-8 3h5',
                ],
            ],
        ],
        [
            'id' => 'sales-navigation',
            'label' => 'Sales',
            'icon' => 'M6 7h14l-1.5 8.5H8L6 4H3m6 15.5h.01m7.99 0h.01',
            'active' => ['admin.orders.*', 'admin.coupons.*'],
            'items' => [
                [
                    'label' => 'Orders',
                    'route' => 'admin.orders.index',
                    'active' => 'admin.orders.*',
                    'icon' => 'M5 5h14v15H5V5Zm4-2v4m6-4v4M8 11h8m-8 4h5',
                ],
                [
                    'label' => 'Coupons',
                    'route' => 'admin.coupons.index',
                    'active' => 'admin.coupons.*',
                    'icon' => 'M4 9V5h4l12 12-4 4L4 9Zm3-1h.01M8 16l8-8',
                ],
            ],
        ],
        [
            'id' => 'operations-navigation',
            'label' => 'Operations',
            'icon' => 'M4 6h16v12H4V6Zm4 0V4h8v2M8 11h8',
            'active' => ['admin.customers.*', 'admin.shipping.*', 'admin.reviews.*', 'admin.community.*'],
            'items' => [
                [
                    'label' => 'Customers',
                    'route' => 'admin.customers.index',
                    'active' => 'admin.customers.*',
                    'icon' => 'M16 20v-1.5a3.5 3.5 0 0 0-3.5-3.5h-5A3.5 3.5 0 0 0 4 18.5V20m5.75-9a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z',
                ],
                [
                    'label' => 'Shipping',
                    'route' => 'admin.shipping.index',
                    'active' => 'admin.shipping.*',
                    'icon' => 'M3 7h11v10H3V7Zm11 4h3l3 3v3h-6v-6ZM7 19.5h.01m10 0h.01',
                ],
                [
                    'label' => 'Reviews',
                    'route' => 'admin.reviews.index',
                    'active' => 'admin.reviews.*',
                    'icon' => 'M4 5h16v12H8l-4 4V5Zm4 4h8m-8 4h5',
                ],
                [
                    'label' => 'Community',
                    'route' => 'admin.community.index',
                    'active' => 'admin.community.*',
                    'icon' => 'M4 5h16v14H4V5Zm3 10 3-3 2 2 3-4 2 5',
                ],
            ],
        ],
        [
            'id' => 'insights-navigation',
            'label' => 'Insights',
            'icon' => 'M5 20V10m5 10V4m5 16v-7m5 7V7',
            'active' => ['admin.reports.*'],
            'items' => [
                [
                    'label' => 'Reports',
                    'route' => 'admin.reports.index',
                    'active' => 'admin.reports.*',
                    'icon' => 'M5 20V10m5 10V4m5 16v-7m5 7V7',
                ],
            ],
        ],
        [
            'id' => 'system-navigation',
            'label' => 'System',
            'icon' => 'M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm7-3.5 2-1-2-3.5-2.2.4a7.7 7.7 0 0 0-1.5-.9L14.5 5h-4l-.8 2a7.7 7.7 0 0 0-1.5.9L6 7.5 4 11l2 1a7.6 7.6 0 0 0 0 2l-2 1 2 3.5 2.2-.4c.5.4 1 .7 1.5.9l.8 2h4l.8-2c.5-.2 1-.5 1.5-.9l2.2.4 2-3.5-2-1a7.6 7.6 0 0 0 0-2Z',
            'active' => ['admin.settings.*'],
            'items' => [
                [
                    'label' => 'Store settings',
                    'route' => 'admin.settings.index',
                    'active' => 'admin.settings.*',
                    'icon' => 'M5 20V7l7-4 7 4v13M9 20v-5h6v5',
                ],
            ],
        ],
    ];
@endphp

<nav
    class="min-h-0 flex-1 overflow-x-hidden overflow-y-auto px-2.5 py-4 [scrollbar-color:color-mix(in_oklab,currentColor_14%,transparent)_transparent] [scrollbar-width:thin]"
    aria-label="Primary">
    <p x-cloak x-show="!desktop || !sidebarCollapsed"
        class="mb-2 px-2.5 text-[8px] font-semibold uppercase tracking-[0.18em] text-base-content/35">
        Main
    </p>

    <ul class="w-full space-y-1 p-0">
        <li :class="{ 'tooltip tooltip-right': desktop && sidebarCollapsed }" data-tip="Dashboard">
            <a href="{{ route('admin.dashboard') }}"
                aria-label="Dashboard"
                class="relative flex min-h-10 items-center overflow-hidden rounded-xl border text-[11px] transition-all duration-150 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent
                    {{ request()->routeIs('admin.dashboard')
                        ? 'border-accent/25 bg-accent/10 font-semibold text-base-content shadow-[inset_0_1px_0_var(--admin-highlight),0_10px_24px_-18px_var(--admin-accent-glow)]'
                        : 'border-transparent text-base-content/58 hover:border-[var(--admin-border)] hover:bg-[var(--admin-surface-raised)] hover:text-base-content' }}"
                :class="desktop && sidebarCollapsed
                    ? '!mx-auto !size-10 !min-h-10 !min-w-10 !justify-center !p-0'
                    : 'justify-start gap-2.5 px-2.5'"
                @click="mobileOpen = false">
                <span class="{{ request()->routeIs('admin.dashboard') ? 'text-accent' : '' }}">
                    <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.7">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 4h6v6H4V4Zm10 0h6v6h-6V4ZM4 14h6v6H4v-6Zm10 0h6v6h-6v-6Z" />
                    </svg>
                </span>
                <span x-cloak x-show="!desktop || !sidebarCollapsed">Dashboard</span>
                @if (request()->routeIs('admin.dashboard'))
                    <span x-cloak x-show="!desktop || !sidebarCollapsed"
                        class="ml-auto size-1.5 rounded-full bg-accent shadow-[0_0_10px_var(--admin-accent-glow)]"></span>
                @endif
            </a>
        </li>

        @foreach ($navigationGroups as $group)
            @php($groupActive = request()->routeIs(...$group['active']))
            <li class="pt-0.5" x-data="{ open: {{ $groupActive ? 'true' : 'false' }} }"
                :class="{ 'tooltip tooltip-right': desktop && sidebarCollapsed }"
                data-tip="{{ $group['label'] }}">
                <button type="button"
                    aria-label="{{ $group['label'] }}"
                    @click="if (desktop && sidebarCollapsed) { setSidebarCollapsed(false); open = true } else { open = !open }"
                    class="flex min-h-10 w-full items-center rounded-xl border border-transparent text-[11px] transition-all duration-150 hover:border-[var(--admin-border)] hover:bg-[var(--admin-surface-raised)] hover:text-base-content focus:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                    :class="[
                        desktop && sidebarCollapsed
                            ? '!mx-auto !size-10 !min-h-10 !min-w-10 !justify-center !p-0'
                            : 'justify-start gap-2.5 px-2.5',
                        open || {{ $groupActive ? 'true' : 'false' }}
                            ? 'text-base-content'
                            : 'text-base-content/58'
                    ]"
                    :aria-expanded="open.toString()" aria-controls="{{ $group['id'] }}">
                    <svg class="size-4 shrink-0 {{ $groupActive ? 'text-accent' : '' }}" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $group['icon'] }}" />
                    </svg>
                    <span x-cloak x-show="!desktop || !sidebarCollapsed"
                        class="flex min-w-0 flex-1 items-center justify-between">
                        <span>{{ $group['label'] }}</span>
                        <svg class="size-3.5 shrink-0 text-base-content/30 transition-transform duration-200"
                            :class="{ 'rotate-180': open }" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m7 10 5 5 5-5" />
                        </svg>
                    </span>
                </button>

                <ul id="{{ $group['id'] }}" x-cloak x-show="open && (!desktop || !sidebarCollapsed)" x-collapse
                    class="relative ml-[1.15rem] mt-1 space-y-1 border-l border-[var(--admin-border)] pb-1 pl-3">
                    @foreach ($group['items'] as $item)
                        <li class="relative before:absolute before:-left-3 before:top-1/2 before:h-px before:w-2.5 before:bg-[var(--admin-border)]">
                            <a href="{{ route($item['route']) }}"
                                class="relative flex min-h-9 items-center gap-2 rounded-lg border px-2.5 text-[10px] transition-all duration-150 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent
                                    {{ request()->routeIs($item['active'])
                                        ? 'border-accent/25 bg-accent/10 font-semibold text-base-content shadow-[inset_0_1px_0_var(--admin-highlight)]'
                                        : 'border-transparent text-base-content/48 hover:border-[var(--admin-border)] hover:bg-[var(--admin-surface-raised)] hover:text-base-content' }}"
                                @click="mobileOpen = false">
                                <svg class="size-3.5 shrink-0 {{ request()->routeIs($item['active']) ? 'text-accent' : '' }}"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                                </svg>
                                <span>{{ $item['label'] }}</span>
                                @if (request()->routeIs($item['active']))
                                    <span
                                        class="ml-auto size-1.5 rounded-full bg-accent shadow-[0_0_10px_var(--admin-accent-glow)]"></span>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</nav>
