@props(['label'])

<button type="button"
    {{ $attributes->class('inline-flex h-7 items-center gap-1.5 rounded-lg border border-accent/20 bg-accent/10 px-2.5 text-[9px] font-medium text-base-content/65 shadow-[inset_0_1px_0_var(--admin-highlight)] transition hover:border-accent/40 hover:text-base-content') }}>
    <span>{{ $label }}</span>
    <svg class="size-3 text-base-content/35" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2" aria-hidden="true">
        <path stroke-linecap="round" d="m7 7 10 10M17 7 7 17" />
    </svg>
</button>
