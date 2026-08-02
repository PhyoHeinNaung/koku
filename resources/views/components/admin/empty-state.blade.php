@props([
    'title',
    'description' => null,
    'actionHref' => null,
    'actionLabel' => null,
])

<div class="grid min-h-64 place-items-center px-6 py-12 text-center">
    <div class="max-w-sm">
        <span
            class="mx-auto grid size-11 place-items-center rounded-xl border border-accent/20 bg-accent/10 text-accent shadow-admin-control">
            @isset($icon)
                {{ $icon }}
            @else
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.7" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 7.5h16M6.5 4h11A1.5 1.5 0 0 1 19 5.5v13a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 5 18.5v-13A1.5 1.5 0 0 1 6.5 4ZM9 11h6" />
                </svg>
            @endisset
        </span>
        <h3 class="mt-3 text-sm font-semibold tracking-tight text-base-content">{{ $title }}</h3>
        @if ($description)
            <p class="mt-1 text-xs leading-5 text-base-content/45">{{ $description }}</p>
        @endif
        @if ($actionHref && $actionLabel)
            <a href="{{ $actionHref }}" class="btn btn-primary btn-sm mt-4 h-9 min-h-9 rounded-lg px-3 text-xs shadow-[0_8px_18px_-12px_var(--admin-accent-glow)]">
                {{ $actionLabel }}
            </a>
        @else
            {{ $action ?? '' }}
        @endif
    </div>
</div>
