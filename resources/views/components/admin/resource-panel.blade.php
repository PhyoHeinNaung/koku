@props(['loadingTarget' => null])

<section
    {{ $attributes->class('overflow-hidden rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] shadow-admin-panel') }}
    @if ($loadingTarget) wire:loading.class.delay="opacity-60" wire:target="{{ $loadingTarget }}" @endif>
    @isset($navigation)
        <div class="overflow-x-auto border-b border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-3 py-2">
            <nav class="flex min-w-max items-center gap-1" role="tablist" aria-label="Resource views">
                {{ $navigation }}
            </nav>
        </div>
    @endisset

    <div class="flex flex-col gap-3 border-b border-[var(--admin-border)] bg-[var(--admin-surface)] px-3 py-3 sm:px-4 lg:flex-row lg:items-center">
        {{ $toolbar }}
    </div>

    @isset($chips)
        {{ $chips }}
    @endisset

    @isset($bulk)
        {{ $bulk }}
    @endisset

    <div class="hidden overflow-x-hidden xl:block">
        {{ $table }}
    </div>

    <div class="bg-[var(--admin-surface)] xl:hidden">
        {{ $mobile }}
    </div>

    @isset($footer)
        {{ $footer }}
    @endisset
</section>
