<x-app-layout>
    <div class="relative overflow-hidden bg-[#f4f2ee] py-8 sm:py-12 lg:py-16">
        <div class="pointer-events-none absolute -right-40 -top-48 size-[34rem] rounded-full bg-[var(--koku-indigo)]/[.07] blur-3xl"></div>
        <div class="pointer-events-none absolute -left-56 bottom-20 size-[32rem] rounded-full bg-[#b99872]/10 blur-3xl"></div>

        <main class="koku-shell relative">
            <section class="overflow-hidden rounded-[2rem] bg-[var(--koku-indigo-deep)] text-white shadow-[0_24px_70px_rgba(31,38,53,.14)]">
                <div class="relative px-6 py-7 sm:px-9 sm:py-9">
                    <div class="pointer-events-none absolute inset-0 opacity-70" style="background:radial-gradient(circle at 78% 15%,rgba(255,255,255,.13),transparent 34%)"></div>
                    <div class="relative flex flex-col gap-7 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex min-w-0 items-center gap-5">
                            @if($user->avatar)<img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="size-20 shrink-0 rounded-2xl border border-white/20 object-cover shadow-xl sm:size-24">@else<span class="flex size-20 shrink-0 items-center justify-center rounded-2xl border border-white/20 bg-white/10 text-3xl font-semibold text-white shadow-xl sm:size-24">{{ Str::upper(Str::substr($user->name,0,1)) }}</span>@endif
                            <div class="min-w-0"><p class="text-[9px] font-medium uppercase tracking-[.18em] text-white/45">Koku account</p><h1 class="mt-2 truncate font-serif text-3xl tracking-[-.04em] sm:text-4xl">{{ $user->name }}</h1><p class="mt-1 truncate text-xs text-white/55">{{ $user->email }} · Member since {{ $user->created_at->format('M Y') }}</p><span class="mt-3 inline-flex items-center gap-2 text-[9px] font-medium uppercase tracking-[.11em] text-emerald-300"><i class="size-1.5 rounded-full bg-emerald-400"></i>{{ $user->hasVerifiedEmail() ? 'Verified member' : 'Member' }}</span></div>
                        </div>
                        <div class="grid grid-cols-3 divide-x divide-white/10 border-y border-white/10 py-4 lg:min-w-[28rem] lg:border-y-0 lg:py-0">
                            <div class="px-4 first:pl-0 lg:px-6"><strong class="block font-serif text-2xl tabular-nums">{{ $user->orders()->count() }}</strong><span class="mt-1 block text-[9px] uppercase tracking-[.13em] text-white/45">Orders</span></div>
                            <div class="px-4 lg:px-6"><strong class="block font-serif text-2xl tabular-nums">{{ $user->addresses()->count() }}</strong><span class="mt-1 block text-[9px] uppercase tracking-[.13em] text-white/45">Addresses</span></div>
                            <div class="px-4 lg:px-6"><strong class="block font-serif text-2xl tabular-nums">{{ \App\Models\WishlistItem::where('user_id',$user->id)->count() }}</strong><span class="mt-1 block text-[9px] uppercase tracking-[.13em] text-white/45">Saved</span></div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="mt-6 grid gap-6 lg:grid-cols-[17rem_minmax(0,1fr)]">
                <aside class="h-fit rounded-3xl border border-white/80 bg-white/75 p-3 shadow-[0_18px_50px_rgba(31,38,53,.06)] backdrop-blur-xl lg:sticky lg:top-28">
                    <nav class="space-y-1 text-sm" aria-label="Account navigation">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 rounded-2xl bg-[var(--koku-indigo)] px-4 py-3.5 text-white shadow-lg shadow-[var(--koku-indigo)]/15"><svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="8" r="3.5"/><path d="M5.5 20a6.5 6.5 0 0113 0"/></svg><span>Profile & security</span></a>
                        <a href="{{ route('orders.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3.5 text-[var(--koku-muted)] transition hover:bg-[#f4f2ee] hover:text-[var(--koku-ink)]"><svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 8h14l-1 12H6L5 8Z"/><path d="M9 8V6a3 3 0 016 0v2"/></svg><span>Orders</span></a>
                        <a href="{{ route('addresses.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3.5 text-[var(--koku-muted)] transition hover:bg-[#f4f2ee] hover:text-[var(--koku-ink)]"><svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1116 0Z"/><circle cx="12" cy="10" r="2.5"/></svg><span>Addresses</span></a>
                        <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3.5 text-[var(--koku-muted)] transition hover:bg-[#f4f2ee] hover:text-[var(--koku-ink)]"><svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 8c0 6-8 11-8 11S4 14 4 8a4 4 0 017-2.6A4 4 0 0120 8Z"/></svg><span>Wishlist</span></a>
                    </nav>
                    <div class="m-1 mt-4 rounded-2xl bg-[#f4f2ee] p-4"><div class="flex items-center justify-between"><p class="text-xs font-medium">Account status</p><span class="size-2 rounded-full {{ $user->hasVerifiedEmail() ? 'bg-emerald-500' : 'bg-amber-500' }} shadow-[0_0_0_4px_rgba(16,185,129,.1)]"></span></div><p class="mt-2 text-[11px] leading-5 text-[var(--koku-muted)]">{{ $user->hasVerifiedEmail() ? 'Your email is verified and secure.' : 'Please verify your email address.' }}</p></div>
                </aside>

                <section id="profile-details" class="koku-modern-account space-y-6">
                    <div class="rounded-3xl border border-white/80 bg-white/80 p-6 shadow-[0_18px_50px_rgba(31,38,53,.06)] backdrop-blur-xl sm:p-8 lg:p-10">@include('customer.profile.partials.update-profile-information-form')</div>
                    <div class="rounded-3xl border border-white/80 bg-white/80 p-6 shadow-[0_18px_50px_rgba(31,38,53,.06)] backdrop-blur-xl sm:p-8 lg:p-10">@include('customer.profile.partials.update-password-form')</div>
                    <details class="group rounded-3xl border border-[#a33b32]/10 bg-white/65 shadow-[0_18px_50px_rgba(31,38,53,.04)]"><summary class="flex cursor-pointer list-none items-center justify-between p-6 sm:px-8"><div><p class="text-sm font-medium">Advanced account settings</p><p class="mt-1 text-xs text-[var(--koku-muted)]">Permanent account and data controls</p></div><span class="flex size-9 items-center justify-center rounded-full bg-[#f4f2ee] text-lg transition group-open:rotate-45">+</span></summary><div class="border-t border-[#a33b32]/10 p-6 sm:p-8">@include('customer.profile.partials.delete-user-form')</div></details>
                </section>
            </div>
        </main>
    </div>
</x-app-layout>
