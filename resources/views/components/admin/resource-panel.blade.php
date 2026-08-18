@props(['loadingTarget' => null])

<section
    {{ $attributes->class('admin-resource-panel overflow-hidden bg-white') }}
    @if ($loadingTarget) wire:loading.class.delay="opacity-60" wire:target="{{ $loadingTarget }}" @endif>
    @isset($navigation)
        <div class="overflow-x-auto border-b border-[var(--admin-border)] bg-white px-0 py-2">
            <nav class="flex min-w-max items-center gap-1" role="tablist" aria-label="Resource views">
                {{ $navigation }}
            </nav>
        </div>
    @endisset

    <div class="flex flex-col gap-3 border-b border-[var(--admin-border)] bg-white px-0 py-3 lg:flex-row lg:items-center">
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
