@props(['image', 'caption', 'href' => '#'])

<a href="{{ $href }}" class="block w-[300px] shrink-0">
    <div class="h-[380px] overflow-hidden bg-base-200 rounded">
        <img src="{{ $image }}" alt="{{ $caption }}" class="w-full h-full object-cover">
    </div>
    <p class="mt-3 text-sm text-center text-base-content/70">{{ $caption }}</p>
</a>