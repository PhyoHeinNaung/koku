@props([
    'title',
    'count' => null,
    'actionHref' => null,
    'actionLabel' => null,
])

<header class="admin-page-header border-b border-[var(--admin-border)] pb-5">
    <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
    <div class="min-w-0">
        <p class="mb-2 text-[10px] text-base-content/35">Koku / {{ str(request()->route()?->getName())->after('admin.')->before('.')->headline() }}</p>
        <div class="flex min-w-0 items-baseline gap-3">
        <h1 class="truncate text-2xl font-semibold tracking-[-0.055em] text-base-content sm:text-[2rem]">{{ $title }}</h1>

        @if (! is_null($count))
            <span
                class="inline-flex h-6 min-w-6 items-center justify-center border-l border-[var(--admin-border)] px-2 text-xs font-medium tabular-nums text-base-content/50">
                {{ $count }}
            </span>
        @endif
        </div>
        @isset($description)
            <p class="mt-2 max-w-2xl text-[13px] leading-5 text-base-content/55">{{ $description }}</p>
        @endisset
    </div>

    @if ($actionHref && $actionLabel)
        <a href="{{ $actionHref }}"
            class="btn btn-primary h-10 min-h-10 w-full gap-2 px-4 sm:w-auto">
            <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" d="M12 5v14M5 12h14" />
            </svg>
            {{ $actionLabel }}
        </a>
    @endif
    </div>
</header>
