@props(['title', 'meta' => null])

<section class="border-b border-[var(--admin-border)] px-5 py-5 last:border-b-0">
    <div class="mb-3 flex items-center justify-between gap-3">
        <h3 class="text-xs font-semibold tracking-tight text-base-content">{{ $title }}</h3>
        @if ($meta)
            <span class="text-[9px] font-medium uppercase tracking-[0.14em] text-base-content/35">
                {{ $meta }}
            </span>
        @endif
    </div>
    {{ $slot }}
</section>
