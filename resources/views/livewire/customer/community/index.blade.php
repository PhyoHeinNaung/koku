<div class="min-h-screen bg-[#fbfaf8]">
    <header class="border-b border-[var(--koku-line)]/60 bg-white">
        <div class="koku-shell py-12 sm:py-16">
            <div class="flex flex-col justify-between gap-8 sm:flex-row sm:items-end">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[.24em] text-[var(--koku-indigo)]">Koku community</p>
                    <h1 class="mt-3 font-serif text-4xl tracking-[-.045em] sm:text-6xl">Worn by you.</h1>
                    <p class="mt-3 max-w-lg text-sm leading-6 text-[var(--koku-muted)]">Personal stories and everyday moments from verified watch owners.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex rounded-full bg-[#f3f1ed] p-1 text-[11px]">
                        <button wire:click="$set('sort','latest')" class="rounded-full px-4 py-2 {{ $sort === 'latest' ? 'bg-white text-[var(--koku-ink)] shadow-sm' : 'text-[var(--koku-muted)]' }}">Latest</button>
                        <button wire:click="$set('sort','popular')" class="rounded-full px-4 py-2 {{ $sort === 'popular' ? 'bg-white text-[var(--koku-ink)] shadow-sm' : 'text-[var(--koku-muted)]' }}">Popular</button>
                    </div>
                    <a href="{{ auth()->check() ? route('community.create') : route('login') }}" class="rounded-full bg-[var(--koku-indigo)] px-5 py-3 text-[11px] font-medium text-white">{{ auth()->check() ? 'Share a story' : 'Sign in to participate' }}</a>
                </div>
            </div>
        </div>
    </header>

    <main class="koku-shell py-10 sm:py-14">
        @if(session('community-success'))<div class="mb-8 rounded-2xl bg-emerald-50 px-5 py-4 text-sm text-emerald-800">{{ session('community-success') }}</div>@endif
        @if($posts->isEmpty())
            <div class="flex min-h-96 flex-col items-center justify-center border-y border-[var(--koku-line)] text-center">
                <span class="font-serif text-5xl text-[var(--koku-indigo)]">K</span><h2 class="mt-5 font-serif text-2xl">The first story is waiting.</h2><p class="mt-2 text-sm text-[var(--koku-muted)]">Verified owners can begin the Koku community.</p>
            </div>
        @else
            <div class="columns-1 gap-6 sm:columns-2 lg:columns-3 xl:columns-4">
                @foreach($posts as $post)
                    @php($cover=$post->media->first())
                    <article wire:key="community-{{ $post->id }}" class="group mb-10 break-inside-avoid">
                        <a href="{{ route('community.show',$post) }}" class="relative block overflow-hidden bg-[#efede8]">
                            @if($cover)<img src="{{ Storage::url($cover->file_path) }}" alt="{{ $cover->alt_text ?: 'Watch shared by '.$post->user->name }}" loading="lazy" class="w-full object-cover transition duration-700 group-hover:scale-[1.015]">@endif
                            @if($post->media->count()>1)<span class="absolute right-3 top-3 flex size-8 items-center justify-center rounded-full bg-white/90 text-[10px] shadow-sm">1/{{ $post->media->count() }}</span>@endif
                        </a>
                        <div class="pt-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0"><p class="truncate text-[12px] font-medium">{{ $post->user->name }}</p><p class="mt-1 truncate text-[9px] uppercase tracking-[.13em] text-[var(--koku-muted)]">{{ $post->product->brand?->name }} · {{ $post->product->name }}</p></div>
                                <div class="flex shrink-0 items-center gap-3 text-[var(--koku-muted)]">
                                    <button wire:click="toggleLike({{ $post->id }})" aria-label="Like story" class="flex items-center gap-1 text-[10px] {{ in_array($post->id,$liked) ? 'text-[#a34b52]' : '' }}"><svg class="size-4" viewBox="0 0 24 24" fill="{{ in_array($post->id,$liked) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5"><path d="M20.8 4.7a5.5 5.5 0 0 0-7.8 0L12 5.8l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21l7.8-7.4 1.1-1.1a5.5 5.5 0 0 0-.1-7.8Z"/></svg>{{ $post->likes_count }}</button>
                                    <a href="{{ route('community.show',$post) }}?conversation=1" aria-label="View comments" class="flex items-center gap-1 text-[10px]"><svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4Z"/></svg>{{ $post->comments_count }}</a>
                                </div>
                            </div>
                            @if($post->caption)<p class="mt-3 line-clamp-2 text-[11px] leading-5 text-[var(--koku-muted)]">{{ $post->caption }}</p>@endif
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="mt-6">{{ $posts->links() }}</div>
        @endif
    </main>
</div>
