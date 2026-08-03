<x-nav-icon-button href="{{ route('wishlist.index') }}" label="Wishlist">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
        stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
    </svg>
    @if ($count > 0)
        <span
            class="absolute right-0 top-0 flex h-4 w-4 items-center justify-center rounded-full bg-[var(--koku-indigo)] text-[9px] leading-none text-white">
            {{ $count }}
        </span>
    @endif
</x-nav-icon-button>
