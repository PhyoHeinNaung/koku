@props([
    'active' => false,
    'count' => null,
])

<button type="button"
    role="tab"
    aria-selected="{{ $active ? 'true' : 'false' }}"
    {{ $attributes->class([
        'relative inline-flex h-8 shrink-0 items-center gap-2 rounded-lg border px-3 text-[10px] font-medium transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-accent',
        'border-accent/25 bg-accent/10 text-base-content shadow-[inset_0_1px_0_var(--admin-highlight),0_8px_18px_-15px_var(--admin-accent-glow)]' => $active,
        'border-transparent text-base-content/45 hover:border-[var(--admin-border)] hover:bg-[var(--admin-surface-soft)] hover:text-base-content' => ! $active,
    ]) }}>
    {{ $slot }}
    @if (! is_null($count))
        <span @class([
            'inline-flex h-4 min-w-4 items-center justify-center rounded-md px-1 text-[8px] font-semibold tabular-nums',
            'bg-accent text-accent-content' => $active,
            'bg-[var(--admin-surface-sunken)] text-base-content/40' => ! $active,
        ])>{{ $count }}</span>
    @endif
</button>
