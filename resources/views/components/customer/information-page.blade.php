@props(['eyebrow', 'title', 'intro', 'sections' => []])

<x-app-layout>
    <main class="bg-[var(--koku-paper)]">
        <header class="border-b border-white/10 bg-[var(--koku-indigo-deep)] text-white">
            <div class="koku-shell py-16 sm:py-24">
                <nav class="text-[10px] font-medium uppercase tracking-[.16em] text-white/50"><a href="{{ route('home') }}" class="transition hover:text-white">Home</a><span class="mx-3">/</span><span class="text-white">{{ $eyebrow }}</span></nav>
                <div class="mt-14 grid gap-8 lg:grid-cols-[1.25fr_.75fr] lg:items-end"><h1 class="max-w-4xl font-serif text-[clamp(3.2rem,6vw,6rem)] leading-[.95] tracking-[-.065em]">{{ $title }}</h1><p class="max-w-md text-sm leading-7 text-white/65">{{ $intro }}</p></div>
            </div>
        </header>

        <div class="koku-shell py-14 sm:py-20">
            {{ $slot }}
            @foreach($sections as $index => $section)
                <section class="grid gap-8 border-t border-[var(--koku-line)] py-10 first:border-t-0 first:pt-0 lg:grid-cols-[.58fr_1.42fr] lg:gap-20">
                    <div><p class="koku-eyebrow text-[var(--koku-clay)]">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</p><h2 class="mt-4 max-w-sm font-serif text-3xl tracking-[-.04em]">{{ $section['title'] }}</h2></div>
                    <div class="max-w-3xl space-y-5 text-sm leading-7 text-[var(--koku-muted)]">@foreach($section['paragraphs'] as $paragraph)<p>{{ $paragraph }}</p>@endforeach</div>
                </section>
            @endforeach
        </div>

        <section class="border-t border-[var(--koku-line)] bg-white"><div class="koku-shell flex flex-col gap-6 py-12 sm:flex-row sm:items-center sm:justify-between"><div><p class="koku-eyebrow text-[var(--koku-indigo)]">Need a clear answer?</p><h2 class="mt-3 font-serif text-3xl tracking-[-.04em]">Talk with the Koku team.</h2></div><a href="{{ route('contact') }}" class="w-fit bg-[var(--koku-indigo)] px-6 py-4 text-[10px] font-medium uppercase tracking-[.14em] text-white">Contact us</a></div></section>
    </main>
</x-app-layout>
