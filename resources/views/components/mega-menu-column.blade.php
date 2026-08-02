@props(['title'])

<div {{ $attributes->merge(['class' => 'w-44 shrink-0']) }}>
    <p class="text-xs font-medium tracking-widest text-base-content/50 uppercase mb-4">
        {{ $title }}
    </p>
    <ul class="space-y-3">
        {{ $slot }}
    </ul>
</div>