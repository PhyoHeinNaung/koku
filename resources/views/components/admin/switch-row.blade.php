@props([
    'label',
    'description' => null,
])

<label {{ $attributes->class('flex cursor-pointer items-center justify-between gap-5 rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] px-4 py-3.5 shadow-inner transition hover:border-[var(--admin-border-strong)]') }}>
    <span class="min-w-0">
        <strong class="block text-[11px] font-semibold">{{ $label }}</strong>
        @if ($description)
            <small class="mt-0.5 block text-[11px] text-base-content/45">{{ $description }}</small>
        @endif
    </span>

    {{ $slot }}
</label>
