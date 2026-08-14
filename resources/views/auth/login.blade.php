<x-guest-layout>
    <div>
        <p class="koku-eyebrow text-[var(--koku-indigo)]">Welcome back</p>
        <h1 class="mt-4 font-serif text-4xl tracking-[-.05em] sm:text-5xl">Sign in to Koku.</h1>
        <p class="mt-4 text-sm leading-7 text-[var(--koku-muted)]">Access your saved watches, order history and delivery details.</p>
    </div>

    <x-auth-session-status class="mt-7 border-l-2 border-emerald-600 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-9 space-y-6">
        @csrf
        <div><label class="koku-field-label" for="email">Email address</label><input id="email" class="koku-auth-field" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@example.com">@error('email')<p class="koku-field-error">{{ $message }}</p>@enderror</div>
        <div x-data="{ show: false }"><div class="flex items-center justify-between"><label class="koku-field-label" for="password">Password</label><a href="{{ route('password.request') }}" class="mb-2 text-[11px] text-[var(--koku-indigo)] hover:underline">Forgot password?</a></div><div class="relative"><input id="password" class="koku-auth-field pr-16" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password" placeholder="Enter your password"><button type="button" @click="show = !show" class="absolute inset-y-0 right-0 px-4 text-[10px] font-medium uppercase tracking-[.1em] text-[var(--koku-muted)]" x-text="show ? 'Hide' : 'Show'"></button></div>@error('password')<p class="koku-field-error">{{ $message }}</p>@enderror</div>
        <label class="flex cursor-pointer items-center gap-3 text-xs text-[var(--koku-muted)]"><input type="checkbox" name="remember" class="size-4 rounded-none border-[var(--koku-line)] text-[var(--koku-indigo)] focus:ring-[var(--koku-indigo)]">Keep me signed in on this device</label>
        <button class="koku-auth-button">Sign in <span aria-hidden="true">→</span></button>
    </form>

    <div class="mt-8 flex items-center gap-4"><span class="h-px flex-1 bg-[var(--koku-line)]"></span><span class="text-[10px] uppercase tracking-[.13em] text-[var(--koku-muted)]">New to Koku?</span><span class="h-px flex-1 bg-[var(--koku-line)]"></span></div>
    <a href="{{ route('register') }}" class="mt-6 flex w-full items-center justify-center border border-[var(--koku-ink)] px-5 py-4 text-[11px] font-medium uppercase tracking-[.13em] transition hover:bg-[var(--koku-ink)] hover:text-white">Create an account</a>
</x-guest-layout>
