@props([
    'label',
    'description' => null,
])

<label {{ $attributes->class('flex cursor-pointer items-center justify-between gap-5 border-b border-[var(--admin-border)] bg-white px-1 py-4 transition hover:bg-[#faf9f7]') }}>
    <span class="min-w-0">
        <strong class="block text-[13px] font-semibold">{{ $label }}</strong>
        @if ($description)
            <small class="mt-1 block text-xs text-base-content/50">{{ $description }}</small>
        @endif
    </span>

    {{ $slot }}
</label>
