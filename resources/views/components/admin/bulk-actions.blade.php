@props(['count'])

<div class="flex flex-col gap-3 border-b border-accent/20 bg-[linear-gradient(100deg,var(--admin-accent-soft),var(--admin-surface-raised)_55%)] px-4 py-2.5 text-base-content shadow-[inset_0_1px_0_var(--admin-highlight)] sm:flex-row sm:items-center">
    <div class="flex min-w-0 items-center gap-2.5">
        <span class="grid size-6 shrink-0 place-items-center rounded-lg bg-accent text-[9px] font-bold text-accent-content shadow-[0_0_16px_var(--admin-accent-glow)]">
            {{ $count }}
        </span>
        <span class="text-[11px] font-semibold text-base-content">
            {{ Str::plural('item', $count) }} selected
        </span>
    </div>
    <div class="flex flex-1 flex-wrap items-center gap-1.5 sm:justify-end">
        {{ $slot }}
    </div>
</div>
