<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Koku — Account</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600&family=Noto+Serif+JP:wght@500;600&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/customer.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="koku-site font-sans text-[var(--koku-ink)] antialiased">
    <div class="flex min-h-screen flex-col items-center bg-[var(--koku-paper)] px-5 pt-16 sm:justify-center sm:pt-0">
        <div>
            <a href="/"
                class="block font-serif text-4xl font-semibold tracking-[-0.06em] text-[var(--koku-ink)]"
                aria-label="Koku home">
                Koku
            </a>
        </div>

        <div
            class="mt-10 w-full border-t border-[var(--koku-line)] bg-[var(--koku-white)] px-6 py-8 sm:max-w-md sm:border">
            {{ $slot }}
        </div>
    </div>
    @livewireScripts
</body>

</html>
