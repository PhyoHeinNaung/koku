@props(['label', 'value', 'tint' => 'gray'])

@php
    $tints = [
        'gray' => 'bg-gray-50',
        'amber' => 'bg-amber-50',
        'green' => 'bg-green-50',
        'blue' => 'bg-blue-50',
    ];
@endphp

<div class="{{ $tints[$tint] ?? $tints['gray'] }} rounded-xl p-5">
    <p class="text-sm text-gray-600">{{ $label }}</p>
    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $value }}</p>
    {{ $slot }}
</div>
