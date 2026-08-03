@props(['href' => null, 'label'])

@php $tag = $href ? 'a' : 'button'; @endphp

<{{ $tag }}
    {{ $href ? "href={$href}" : '' }}
    class="koku-icon-button relative text-[#faf8f3] hover:!bg-white/10 hover:!text-white"
    aria-label="{{ $label }}"
    {{ $attributes }}
    >
    {{ $slot }}
</{{ $tag }}>
