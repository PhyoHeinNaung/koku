@props([
    'title',
    'count' => null,
    'actionHref' => null,
    'actionLabel' => null,
])

<header class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex min-w-0 items-center gap-3">
        <span
            class="hidden size-9 shrink-0 place-items-center rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] text-accent shadow-admin-control sm:grid">
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m12 3 8 4.5-8 4.5-8-4.5L12 3Zm-8 9 8 4.5 8-4.5M4 16.5 12 21l8-4.5" />
            </svg>
        </span>
        <h1 class="truncate text-xl font-semibold tracking-[-0.035em] text-base-content sm:text-[1.35rem]">
            {{ $title }}
        </h1>

        @if (! is_null($count))
            <span
                class="inline-flex h-6 min-w-6 items-center justify-center rounded-lg border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-1.5 text-[10px] font-semibold tabular-nums text-base-content/55 shadow-admin-control">
                {{ $count }}
            </span>
        @endif
    </div>

    @if ($actionHref && $actionLabel)
        <a href="{{ $actionHref }}"
            class="btn btn-primary btn-sm h-10 min-h-10 w-full gap-2 rounded-xl border border-primary/50 px-4 text-xs font-semibold shadow-[inset_0_1px_0_rgb(255_255_255/.22),0_10px_22px_-12px_var(--admin-accent-glow)] sm:w-auto">
            <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" d="M12 5v14M5 12h14" />
            </svg>
            {{ $actionLabel }}
        </a>
    @endif
</header>
