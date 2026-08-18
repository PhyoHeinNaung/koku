@props(['count'])

<div class="admin-bulk-actions admin-bulk-bar flex items-center text-white">
    <div class="flex min-w-0 items-center gap-2.5">
        <span class="grid size-6 shrink-0 place-items-center bg-white/10 text-[9px] font-semibold text-white">
            {{ $count }}
        </span>
        <span class="text-[11px] font-medium text-white/55">
            {{ Str::plural('item', $count) }} selected
        </span>
    </div>
    <div class="flex flex-1 flex-wrap items-center gap-1.5 sm:justify-end">
        {{ $slot }}
    </div>
</div>
