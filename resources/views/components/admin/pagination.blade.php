@props(['paginator', 'noun' => 'result'])

@if ($paginator->hasPages())
    <footer
        class="flex flex-col gap-3 border-t border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-[11px] tabular-nums text-base-content/45">
            {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}
            <span class="text-base-content/25">of</span>
            {{ $paginator->total() }} {{ Str::plural($noun, $paginator->total()) }}
        </p>
        <div class="text-xs">
            {{ $paginator->links() }}
        </div>
    </footer>
@endif
