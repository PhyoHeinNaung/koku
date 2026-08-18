@props(['label', 'value', 'tint' => 'gray'])

<div class="border border-[var(--admin-border)] bg-white p-5">
    <p class="text-xs font-medium text-[var(--koku-admin-muted)]">{{ $label }}</p>
    <p class="mt-3 text-[1.75rem] font-semibold tracking-[-.035em] text-[var(--koku-admin-ink)] tabular-nums">{{ $value }}</p>
    {{ $slot }}
</div>
