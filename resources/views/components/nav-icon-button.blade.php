@props(['href' => null, 'label'])

@php $tag = $href ? 'a' : 'button'; @endphp

<{{ $tag }}
    {{ $href ? "href={$href}" : '' }}
    class="relative p-2 transition-colors duration-200 hover:opacity-60"
    :class="(scrolled || hovered) ? 'text-gray-900' : 'text-white'"
    aria-label="{{ $label }}"
    {{ $attributes }}
    >
    {{ $slot }}
</{{ $tag }}>