@props([
    'title' => 'Filters',
    'description' => 'Refine the results shown in the table.',
    'count' => 0,
])

<div class="fixed inset-0 z-[70]"
    :class="filterDrawerOpen ? 'pointer-events-auto' : 'pointer-events-none'">
    <button type="button"
        x-cloak
        x-show="filterDrawerOpen"
        x-transition:enter="transition-opacity duration-200 ease-out"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-150 ease-in"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="filterDrawerOpen = false"
        class="absolute inset-0 bg-black/65 backdrop-blur-[3px]"
        aria-label="Close filters"></button>

    <aside x-cloak
        x-show="filterDrawerOpen"
        x-transition:enter="transition-transform duration-250 ease-out"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition-transform duration-200 ease-in"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        role="dialog"
        aria-modal="true"
        aria-label="{{ $title }}"
        class="admin-drawer absolute inset-y-0 right-0 flex w-full max-w-[430px] flex-col overflow-hidden border-l border-[var(--admin-border)] bg-[var(--admin-surface)] text-base-content shadow-2xl shadow-black/45">
        <header class="relative border-b border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-5 py-4">
            <span class="absolute inset-x-5 top-0 h-px bg-gradient-to-r from-transparent via-accent to-transparent" aria-hidden="true"></span>
            <div class="flex items-start justify-between gap-4">
                <div class="flex min-w-0 items-start gap-3">
                    <span
                        class="grid size-9 shrink-0 place-items-center rounded-xl border border-accent/20 bg-accent/10 text-accent shadow-admin-control">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" d="M4 7h10m4 0h2M4 17h2m4 0h10" />
                            <circle cx="16" cy="7" r="2" />
                            <circle cx="8" cy="17" r="2" />
                        </svg>
                    </span>
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="text-sm font-semibold tracking-tight">{{ $title }}</h2>
                            @if ($count > 0)
                                <span
                                    class="inline-flex h-5 items-center rounded-md bg-accent px-1.5 text-[9px] font-semibold text-accent-content">
                                    {{ $count }} active
                                </span>
                            @endif
                        </div>
                        <p class="mt-0.5 max-w-xs text-[11px] leading-4 text-base-content/45">
                            {{ $description }}
                        </p>
                    </div>
                </div>
                <button type="button"
                    @click="filterDrawerOpen = false"
                    class="btn btn-square btn-sm size-8 min-h-8 shrink-0 rounded-lg border border-[var(--admin-border)] bg-[var(--admin-surface)] text-base-content/45 shadow-admin-control hover:text-base-content"
                    aria-label="Close filters">
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" d="m6 6 12 12M18 6 6 18" />
                    </svg>
                </button>
            </div>
        </header>

        <div class="min-h-0 flex-1 overflow-y-auto bg-[var(--admin-surface)]">
            {{ $slot }}
        </div>

        <footer class="flex items-center justify-between gap-3 border-t border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-5 py-3.5">
            {{ $actions ?? '' }}
        </footer>
    </aside>
</div>
