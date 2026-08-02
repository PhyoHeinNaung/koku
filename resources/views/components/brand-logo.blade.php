@props([
    'variant' => 'wordmark',
    'label' => 'TICKS',
])

@if ($variant === 'mark')
    <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"
        role="img" aria-label="{{ $label }}" {{ $attributes }}>
        <rect x="3" y="3" width="42" height="42" rx="12"
            fill="var(--color-accent, #ff5d0a)" />
        <path
            d="M13.5 12.75h18.25L24.8 21.9a3.35 3.35 0 0 0 0 4.2l6.95 9.15H13.5l6.95-9.15a3.35 3.35 0 0 0 0-4.2l-6.95-9.15Z"
            stroke="var(--color-neutral, #171513)" stroke-width="3.25"
            stroke-linecap="round" stroke-linejoin="round" />
        <path d="m35 18.5-4.25 5.5L35 29.5"
            stroke="var(--color-neutral, #171513)" stroke-width="3.25"
            stroke-linecap="round" stroke-linejoin="round" />
    </svg>
@else
    <svg viewBox="0 0 150 48" fill="none" xmlns="http://www.w3.org/2000/svg"
        role="img" aria-label="{{ $label }}" {{ $attributes }}>
        <g>
            <rect x="3" y="3" width="42" height="42" rx="12"
                fill="var(--color-accent, #ff5d0a)" />
            <path
                d="M13.5 12.75h18.25L24.8 21.9a3.35 3.35 0 0 0 0 4.2l6.95 9.15H13.5l6.95-9.15a3.35 3.35 0 0 0 0-4.2l-6.95-9.15Z"
                stroke="var(--color-neutral, #171513)" stroke-width="3.25"
                stroke-linecap="round" stroke-linejoin="round" />
            <path d="m35 18.5-4.25 5.5L35 29.5"
                stroke="var(--color-neutral, #171513)" stroke-width="3.25"
                stroke-linecap="round" stroke-linejoin="round" />
        </g>
        <text x="57" y="33" fill="currentColor"
            font-family="Manrope, Inter, Arial, sans-serif" font-size="27" font-weight="700"
            letter-spacing="-0.6">ticks</text>
    </svg>
@endif
