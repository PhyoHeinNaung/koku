@props(['menuKey', 'label'])

<button type="button" @mouseenter="activeMenu = '{{ $menuKey }}'"
    class="text-sm font-medium tracking-wide pb-1 border-b transition-colors"
    :class="activeMenu === '{{ $menuKey }}' ? 'border-current' : 'border-transparent'">
    {{ $label }}
</button>