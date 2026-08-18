<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $authPage = match (Route::currentRouteName()) {
            'login' => ['Sign in', 'Welcome back to your collection.', 'Your saved watches, orders and personal details—kept together with care.'],
            'register' => ['Create account', 'A more personal way to keep time.', 'Save considered pieces, move through checkout with ease and follow every order.'],
            'password.request' => ['Recover account', 'Return to what you saved.', 'A secure reset link will help you regain access to your Koku account.'],
            'password.reset' => ['New password', 'Begin again, securely.', 'Choose a strong new password for the collection and details you keep with Koku.'],
            'verification.notice' => ['Verify email', 'One thoughtful step remains.', 'Confirm your email to protect your account and receive important order updates.'],
            'password.confirm' => ['Confirm access', 'A quiet check for your security.', 'Please confirm your password before entering this protected area.'],
            default => ['Account', 'Time, considered.', 'A personal place for the watches and moments that matter.'],
        };
    @endphp
    <title>{{ $authPage[0] }} | Koku</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600&family=Noto+Serif+JP:wght@500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/customer.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="koku-site bg-[#f5f2ed] font-sans text-[var(--koku-ink)] antialiased">
    <main class="flex min-h-screen flex-col bg-[#f8f6f2]">
        <header class="border-b border-[var(--koku-line)] bg-white/70">
            <div class="mx-auto flex h-20 w-full max-w-6xl items-center justify-between px-5 sm:px-8">
                <a href="{{ route('home') }}" class="font-serif text-3xl font-semibold tracking-[-.065em]" aria-label="Koku home">Koku</a>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3 text-[10px] font-medium uppercase tracking-[.14em] text-[var(--koku-muted)] transition hover:text-[var(--koku-indigo)]"><span aria-hidden="true">←</span><span>Return to store</span></a>
            </div>
        </header>

        <section class="flex flex-1 items-center justify-center px-5 py-12 sm:px-8 sm:py-16">
            <div class="w-full max-w-[34rem] border border-[var(--koku-line)] bg-white px-6 py-9 shadow-[0_24px_70px_rgba(25,38,64,.07)] sm:px-12 sm:py-12">
                <div class="mb-9 flex items-center gap-3"><span class="h-px w-10 bg-[var(--koku-clay)]"></span><span class="koku-eyebrow text-[var(--koku-muted)]">Koku account</span></div>
                {{ $slot }}
            </div>
        </section>

        <footer class="border-t border-[var(--koku-line)] bg-white/55">
            <div class="mx-auto flex w-full max-w-6xl flex-col gap-3 px-5 py-5 text-[10px] text-[var(--koku-muted)] sm:flex-row sm:items-center sm:justify-between sm:px-8">
                <span>© {{ date('Y') }} Koku. Time, considered.</span>
                <div class="flex gap-5"><a href="{{ route('contact') }}">Support</a><a href="{{ route('faqs') }}">FAQs</a></div>
            </div>
        </footer>
    </main>
    @livewireScripts
</body>
</html>
