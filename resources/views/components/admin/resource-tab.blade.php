@props([
    'active' => false,
    'count' => null,
])

<button type="button"
    role="tab"
    aria-selected="{{ $active ? 'true' : 'false' }}"
    {{ $attributes->class([
        'relative inline-flex h-8 shrink-0 items-center gap-2 border-0 border-b px-3 text-[11px] font-medium transition focus:outline-none focus-visible:ring-2 focus-visible:ring-accent',
        'border-[#111a24] text-base-content' => $active,
        'border-transparent text-base-content/45 hover:text-base-content' => ! $active,
    ]) }}>
    {{ $slot }}
    @if (! is_null($count))
        <span @class([
            'inline-flex h-4 min-w-4 items-center justify-center px-1 text-[8px] font-medium tabular-nums',
            'bg-[#111a24] text-white' => $active,
            'bg-[#f0f0ed] text-base-content/40' => ! $active,
        ])>{{ $count }}</span>
    @endif
</button>
