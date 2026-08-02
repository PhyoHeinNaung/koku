@props([
    'title',
    'description' => null,
])

<section {{ $attributes->class('relative overflow-hidden rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] shadow-admin-panel') }}>
    <span class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/15 to-transparent"></span>
    <header class="flex items-center gap-3 border-b border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-5 py-4 sm:px-6">
        @isset($icon)
            <span class="grid size-9 shrink-0 place-items-center rounded-xl border border-accent/20 bg-accent/10 text-accent shadow-admin-control">
                {{ $icon }}
            </span>
        @endisset

        <span class="min-w-0">
            <h2 class="text-sm font-semibold tracking-tight">{{ $title }}</h2>
            @if ($description)
                <small class="mt-0.5 block text-[10px] text-base-content/40">{{ $description }}</small>
            @endif
        </span>

        @isset($actions)
            <div class="ml-auto">
                {{ $actions }}
            </div>
        @endisset
    </header>

    <div class="p-5 sm:p-6">
        {{ $slot }}
    </div>
</section>
