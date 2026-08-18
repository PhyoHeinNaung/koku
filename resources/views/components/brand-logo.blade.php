@props([
    'variant' => 'wordmark',
    'label' => 'Koku',
])

@if ($variant === 'mark')
    <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"
        role="img" aria-label="{{ $label }}" {{ $attributes }}>
        <rect x="1" y="1" width="46" height="46" rx="2" fill="#293D68" />
        <path d="M16 12v24M16 25l15-13M17 24l16 12" stroke="white" stroke-width="2.25"
            stroke-linecap="square" stroke-linejoin="miter" />
    </svg>
@else
    <svg viewBox="0 0 150 48" fill="none" xmlns="http://www.w3.org/2000/svg"
        role="img" aria-label="{{ $label }}" {{ $attributes }}>
        <rect x="1" y="1" width="46" height="46" rx="2" fill="#293D68" />
        <path d="M16 12v24M16 25l15-13M17 24l16 12" stroke="white" stroke-width="2.25"
            stroke-linecap="square" stroke-linejoin="miter" />
        <text x="60" y="33" fill="currentColor"
            font-family="'Noto Serif JP', Georgia, serif" font-size="27" font-weight="600"
            letter-spacing="-0.8">Koku</text>
    </svg>
@endif
