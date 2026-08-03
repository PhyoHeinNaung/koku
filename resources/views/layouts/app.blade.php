<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="stripe-key" content="{{ config('services.stripe.key') }}">
    <script src="https://js.stripe.com/v3/"></script>

    <title>{{ isset($title) ? $title . ' | Koku' : 'Koku — Watches, considered' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600&family=Noto+Serif+JP:wght@500;600&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/customer.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="koku-site min-h-screen bg-[var(--koku-paper)] font-sans text-[var(--koku-ink)] antialiased">
    {{-- <x-loading-bar /> --}}
    {{-- <x-loading-overlay /> --}}

    @include('customer.partials.navigation', ['overlay' => $overlay])

    @isset($header)
        <header class="border-b border-[var(--koku-line)] bg-[var(--koku-paper)]">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    <main>
        {{ $slot }}
    </main>

    @include('customer.partials.footer')

    @livewireScripts
</body>

</html>