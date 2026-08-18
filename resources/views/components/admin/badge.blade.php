@props(['tone' => 'gray'])

@php
    $tones = [
        'green' => 'border-success/20 bg-success/10 text-success',
        'gray' => 'border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] text-base-content/55',
        'amber' => 'border-warning/25 bg-warning/10 text-warning',
        'red' => 'border-error/15 bg-error/10 text-error',
        'blue' => 'border-info/15 bg-info/10 text-info',
        'orange' => 'border-primary/15 bg-primary/10 text-primary',
    ];
@endphp

<span {{ $attributes->class([
    'inline-flex h-6 items-center gap-1.5 rounded-[3px] border px-2 text-[11px] font-medium leading-none',
    $tones[$tone] ?? $tones['gray'],
]) }}>
    <span class="size-1 shrink-0 rounded-full bg-current opacity-75"></span>
    {{ $slot }}
</span>
