<div class="mx-auto w-full max-w-[1500px] space-y-6">
    <x-admin.page-header title="Admin profile" />

    <div class="grid items-start gap-6 xl:grid-cols-[20rem_minmax(0,1fr)]">
        <aside class="space-y-5 xl:sticky xl:top-24">
            <section class="card overflow-hidden border border-[var(--admin-border)] bg-[var(--admin-surface)] shadow-admin-panel">
                <div class="h-16 border-b border-[var(--admin-border)] bg-gradient-to-r from-accent/20 via-accent/8 to-transparent"></div>
                <div class="card-body -mt-12 items-center gap-4 p-5 text-center sm:p-6">
                    <form wire:submit="saveAvatar" class="w-full space-y-4">
                        <div class="relative mx-auto size-28">
                            <div
                                class="grid size-28 place-items-center overflow-hidden rounded-2xl border-4 border-[var(--admin-surface)] bg-neutral text-neutral-content shadow-xl ring-1 ring-[var(--admin-border-strong)]">
                                @if ($avatar)
                                    <img src="{{ $avatar->temporaryUrl() }}" alt="New profile photo preview"
                                        class="size-full object-cover">
                                @elseif ($existingAvatar)
                                    <img src="{{ str_starts_with($existingAvatar, 'http') ? $existingAvatar : Storage::url($existingAvatar) }}"
                                        alt="{{ $user->name }}" class="size-full object-cover">
                                @else
                                    <span class="text-3xl font-semibold">{{ str($user->name)->substr(0, 1)->upper() }}</span>
                                @endif
                            </div>
                            <label
                                class="btn btn-circle btn-sm absolute -bottom-1 -right-1 cursor-pointer border-accent bg-accent text-accent-content shadow-lg shadow-accent/20 hover:border-accent/90 hover:bg-accent/90"
                                aria-label="Choose a profile photo" title="Choose photo">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 7h3l1.5-2h7L17 7h3v12H4V7Zm8 9a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z" />
                                </svg>
                                <input type="file" wire:model="avatar" accept="image/jpeg,image/png,image/webp"
                                    class="hidden">
                            </label>
                        </div>

                        <div>
                            <h2 class="truncate text-base font-semibold">{{ $user->name }}</h2>
                            <p class="mt-1 truncate text-xs text-base-content/45">{{ $user->email }}</p>
                            <div class="mt-3 flex flex-wrap justify-center gap-2">
                                <span class="badge border-[var(--admin-border-strong)] bg-[var(--admin-surface-sunken)] text-base-content badge-sm capitalize">{{ $user->role }}</span>
                                <span
                                    class="badge badge-sm {{ $user->status === 'active' ? 'border-success/20 bg-success/10 text-success' : 'badge-ghost' }} capitalize">
                                    {{ $user->status }}
                                </span>
                            </div>
                        </div>

                        <p class="text-[11px] leading-5 text-base-content/45">
                            JPG, PNG or WEBP. Maximum file size 2 MB.
                        </p>

                        @error('avatar')
                            <p class="text-xs text-error">{{ $message }}</p>
                        @enderror

                        @if ($avatar)
                            <div class="flex justify-center gap-2">
                                <button type="button" wire:click="$set('avatar', null)"
                                    class="btn btn-ghost btn-sm">Cancel</button>
                                <button type="submit" wire:loading.attr="disabled" wire:target="saveAvatar,avatar"
                                    class="btn btn-primary btn-sm min-w-28 border-accent bg-accent text-accent-content shadow-lg shadow-accent/15">
                                    <span wire:loading wire:target="saveAvatar"
                                        class="loading loading-spinner loading-xs"></span>
                                    <span wire:loading.remove wire:target="saveAvatar">Save photo</span>
                                </button>
                            </div>
                        @elseif ($existingAvatar)
                            <button type="button" wire:click="removeAvatar"
                                wire:confirm="Remove your current profile photo?"
                                wire:loading.attr="disabled" wire:target="removeAvatar"
                                class="btn btn-ghost btn-sm text-error hover:bg-error/10">
                                <span wire:loading wire:target="removeAvatar"
                                    class="loading loading-spinner loading-xs"></span>
                                <span wire:loading.remove wire:target="removeAvatar">Remove photo</span>
                            </button>
                        @endif
                    </form>
                </div>
            </section>

            <section class="card border border-[var(--admin-border)] bg-[var(--admin-surface)] shadow-admin-panel">
                <div class="card-body gap-0 p-5">
                    <h2 class="pb-4 text-sm font-semibold">Account details</h2>

                    <dl class="divide-y divide-[var(--admin-border)] text-xs">
                        <div class="flex items-center justify-between gap-4 py-3">
                            <dt class="text-base-content/45">Member since</dt>
                            <dd class="font-medium">{{ $user->created_at->format('M Y') }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 py-3">
                            <dt class="text-base-content/45">Email</dt>
                            <dd
                                class="{{ $user->hasVerifiedEmail() ? 'text-success' : 'text-warning' }} font-medium">
                                {{ $user->hasVerifiedEmail() ? 'Verified' : 'Unverified' }}
                            </dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 py-3">
                            <dt class="text-base-content/45">Access</dt>
                            <dd class="font-medium">Administration</dd>
                        </div>
                    </dl>
                </div>
            </section>
        </aside>

        <div class="min-w-0 space-y-5" x-data="{ profileSection: 'identity' }">
            <div role="tablist"
                class="flex max-w-full gap-1 overflow-x-auto rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] p-1 shadow-inner">
                <button type="button" role="tab"
                    class="inline-flex h-9 min-w-28 shrink-0 items-center justify-center rounded-lg px-3 text-xs font-semibold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent/30"
                    :class="profileSection === 'identity' ? 'border border-[var(--admin-border-strong)] bg-[var(--admin-surface-raised)] text-base-content shadow-admin-control' : 'border border-transparent text-base-content/50 hover:bg-[var(--admin-surface-soft)] hover:text-base-content'"
                    :aria-selected="(profileSection === 'identity').toString()"
                    @click="profileSection = 'identity'">
                    Profile & access
                </button>
                <button type="button" role="tab"
                    class="inline-flex h-9 min-w-24 shrink-0 items-center justify-center rounded-lg px-3 text-xs font-semibold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent/30"
                    :class="profileSection === 'security' ? 'border border-[var(--admin-border-strong)] bg-[var(--admin-surface-raised)] text-base-content shadow-admin-control' : 'border border-transparent text-base-content/50 hover:bg-[var(--admin-surface-soft)] hover:text-base-content'"
                    :aria-selected="(profileSection === 'security').toString()"
                    @click="profileSection = 'security'">
                    Security
                </button>
                <button type="button" role="tab"
                    class="inline-flex h-9 min-w-28 shrink-0 items-center justify-center rounded-lg px-3 text-xs font-semibold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent/30"
                    :class="profileSection === 'preferences' ? 'border border-[var(--admin-border-strong)] bg-[var(--admin-surface-raised)] text-base-content shadow-admin-control' : 'border border-transparent text-base-content/50 hover:bg-[var(--admin-surface-soft)] hover:text-base-content'"
                    :aria-selected="(profileSection === 'preferences').toString()"
                    @click="profileSection = 'preferences'">
                    Preferences
                </button>
            </div>

            <div x-cloak x-show="profileSection === 'identity'" x-transition.opacity class="space-y-5">
                <section class="card overflow-hidden border border-[var(--admin-border)] bg-[var(--admin-surface)] shadow-admin-panel">
                    <div class="card-body gap-5 p-5 sm:p-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-center gap-3">
                                <span class="grid size-10 place-items-center rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] text-base-content shadow-inner">
                                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 3 20 6v5c0 5-3.4 8.3-8 10-4.6-1.7-8-5-8-10V6l8-3Zm-3 9 2 2 4-5" />
                                    </svg>
                                </span>
                                <div>
                                    <h2 class="text-sm font-semibold">Administrator access</h2>
                                    <p class="mt-0.5 text-xs text-base-content/45">Your account can manage every currently available admin module.</p>
                                </div>
                            </div>
                            <span
                                class="badge border-success/20 bg-success/10 px-3 py-3 text-xs font-medium text-success">
                                Full access
                            </span>
                        </div>

                        <div class="grid overflow-hidden rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-inner sm:grid-cols-3">
                            <div class="border-b border-[var(--admin-border)] p-4 sm:border-b-0 sm:border-r">
                                <p class="text-[10px] font-medium uppercase tracking-wider text-base-content/40">Role</p>
                                <p class="mt-1.5 text-sm font-semibold capitalize">{{ $user->role }}</p>
                            </div>
                            <div class="border-b border-[var(--admin-border)] p-4 sm:border-b-0 sm:border-r">
                                <p class="text-[10px] font-medium uppercase tracking-wider text-base-content/40">Account status</p>
                                <p class="mt-1.5 text-sm font-semibold capitalize">{{ $user->status }}</p>
                            </div>
                            <div class="p-4">
                                <p class="text-[10px] font-medium uppercase tracking-wider text-base-content/40">Workspace</p>
                                <p class="mt-1.5 text-sm font-semibold">Ticks administration</p>
                            </div>
                        </div>

                        <div>
                            <p class="mb-2 text-xs font-medium text-base-content/55">Available management areas</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach (['Categories', 'Brands', 'Products', 'Coupons'] as $area)
                                    <span class="badge gap-1.5 border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] px-3 py-3 text-[11px]">
                                        <svg class="size-3 text-success" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m5 12.5 4 4L19 6.5" />
                                        </svg>
                                        {{ $area }}
                                    </span>
                                @endforeach
                            </div>
                            <p class="mt-3 text-[11px] leading-5 text-base-content/40">For account safety, your administrator role and status are read-only on this page.</p>
                        </div>
                    </div>
                </section>

            <section class="card border border-[var(--admin-border)] bg-[var(--admin-surface)] shadow-admin-panel">
                <form wire:submit="saveProfile" class="card-body gap-5 p-5 sm:p-6">
                    <div class="flex items-center gap-3 border-b border-[var(--admin-border)] pb-5">
                        <span class="grid size-10 place-items-center rounded-xl border border-accent/20 bg-accent/10 text-accent shadow-admin-control">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 20v-1.5A3.5 3.5 0 0 0 12.5 15h-5A3.5 3.5 0 0 0 4 18.5V20m5.75-9a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z" />
                            </svg>
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold">Personal information</h2>
                            <p class="mt-0.5 text-xs text-base-content/45">Used for your administrator identity and notifications.</p>
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend text-xs">Full name <span class="text-error">*</span>
                            </legend>
                            <input id="admin-profile-name" type="text" wire:model="name" autocomplete="name"
                                class="input h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/10">
                            @error('name')
                                <p class="text-xs text-error">{{ $message }}</p>
                            @enderror
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend text-xs">Phone number</legend>
                            <input id="admin-profile-phone" type="tel" wire:model="phone" autocomplete="tel"
                                placeholder="+95 9 123 456 789"
                                class="input h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/10">
                            @error('phone')
                                <p class="text-xs text-error">{{ $message }}</p>
                            @enderror
                        </fieldset>

                        <fieldset class="fieldset md:col-span-2">
                            <legend class="fieldset-legend text-xs">Email address <span class="text-error">*</span>
                            </legend>
                            <input id="admin-profile-email" type="email" wire:model="email" autocomplete="username"
                                class="input h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/10">
                            <p class="fieldset-label">Changing your email requires verification again.</p>
                            @error('email')
                                <p class="text-xs text-error">{{ $message }}</p>
                            @enderror
                        </fieldset>
                    </div>

                    @if (! $user->hasVerifiedEmail())
                        <div class="flex flex-col gap-3 rounded-xl border border-warning/25 bg-warning/10 p-4 shadow-inner sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex gap-3">
                                <svg class="mt-0.5 size-5 shrink-0 text-warning" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 8v5m0 3.5h.01M10.3 4.7 3.5 17a2 2 0 0 0 1.75 3h13.5a2 2 0 0 0 1.75-3L13.7 4.7a2 2 0 0 0-3.4 0Z" />
                                </svg>
                                <div>
                                    <h3 class="text-xs font-semibold">Email verification required</h3>
                                    <p class="mt-1 text-[11px] leading-5 text-base-content/55">Verify this address to secure account recovery and notifications.</p>
                                </div>
                            </div>
                            <button type="button" wire:click="sendVerification" wire:loading.attr="disabled"
                                wire:target="sendVerification" class="btn btn-outline btn-sm shrink-0">
                                <span wire:loading wire:target="sendVerification"
                                    class="loading loading-spinner loading-xs"></span>
                                <span wire:loading.remove wire:target="sendVerification">Resend email</span>
                            </button>
                        </div>
                    @endif

                    <div class="flex justify-end border-t border-[var(--admin-border)] pt-5">
                        <button type="submit" wire:loading.attr="disabled" wire:target="saveProfile"
                            class="btn btn-primary btn-sm min-w-32 border-accent bg-accent text-accent-content shadow-lg shadow-accent/15">
                            <span wire:loading wire:target="saveProfile"
                                class="loading loading-spinner loading-xs"></span>
                            <span wire:loading.remove wire:target="saveProfile">Save changes</span>
                        </button>
                    </div>
                </form>
            </section>
            </div>

            <div x-cloak x-show="profileSection === 'security'" x-transition.opacity class="space-y-5">
            <section class="card border border-[var(--admin-border)] bg-[var(--admin-surface)] shadow-admin-panel">
                <form wire:submit="updatePassword" class="card-body gap-5 p-5 sm:p-6">
                    <div class="flex items-center gap-3 border-b border-[var(--admin-border)] pb-5">
                        <span class="grid size-10 place-items-center rounded-xl border border-accent/20 bg-accent/10 text-accent shadow-admin-control">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <rect x="5" y="10" width="14" height="10" rx="2" />
                                <path stroke-linecap="round" d="M8 10V7a4 4 0 0 1 8 0v3m-4 4v2" />
                            </svg>
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold">Password and security</h2>
                            <p class="mt-0.5 text-xs text-base-content/45">Use a strong password you do not reuse elsewhere.</p>
                        </div>
                    </div>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend text-xs">Current password <span class="text-error">*</span>
                        </legend>
                        <input id="admin-current-password" type="password" wire:model="current_password"
                            autocomplete="current-password"
                            class="input h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/10">
                        @error('current_password')
                            <p class="text-xs text-error">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    <div class="grid gap-4 md:grid-cols-2">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend text-xs">New password <span class="text-error">*</span>
                            </legend>
                            <input id="admin-new-password" type="password" wire:model="password"
                                autocomplete="new-password"
                                class="input h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/10">
                            @error('password')
                                <p class="text-xs text-error">{{ $message }}</p>
                            @enderror
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend text-xs">Confirm new password <span
                                    class="text-error">*</span></legend>
                            <input id="admin-password-confirmation" type="password"
                                wire:model="password_confirmation" autocomplete="new-password"
                                class="input h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/10">
                        </fieldset>
                    </div>

                    <div class="flex justify-end border-t border-[var(--admin-border)] pt-5">
                        <button type="submit" wire:loading.attr="disabled" wire:target="updatePassword"
                            class="btn btn-primary btn-sm min-w-36 border-accent bg-accent text-accent-content shadow-lg shadow-accent/15">
                            <span wire:loading wire:target="updatePassword"
                                class="loading loading-spinner loading-xs"></span>
                            <span wire:loading.remove wire:target="updatePassword">Update password</span>
                        </button>
                    </div>
                </form>
            </section>

                <section class="card border border-[var(--admin-border)] bg-[var(--admin-surface)] shadow-admin-panel">
                    <div class="card-body gap-5 p-5 sm:p-6">
                        <div class="flex items-center gap-3 border-b border-[var(--admin-border)] pb-5">
                            <span class="grid size-10 place-items-center rounded-xl border border-accent/20 bg-accent/10 text-accent shadow-admin-control">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8">
                                    <rect x="3.5" y="5" width="17" height="12" rx="2" />
                                    <path stroke-linecap="round" d="M8 21h8m-4-4v4" />
                                </svg>
                            </span>
                            <div>
                                <h2 class="text-sm font-semibold">Active sessions</h2>
                                <p class="mt-0.5 text-xs text-base-content/45">Review browsers currently signed into your administrator account.</p>
                            </div>
                        </div>

                        <div class="divide-y divide-[var(--admin-border)] overflow-hidden rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-inner">
                            @foreach ($activeSessions as $activeSession)
                                <div class="flex items-center gap-3 p-4" wire:key="admin-session-{{ $activeSession['id'] }}">
                                    <span class="grid size-10 shrink-0 place-items-center rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] text-base-content/60 shadow-admin-control">
                                        @if ($activeSession['is_mobile'])
                                            <svg class="size-5" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.8">
                                                <rect x="7" y="2.5" width="10" height="19" rx="2" />
                                                <path stroke-linecap="round" d="M10 5h4m-2 13.5h.01" />
                                            </svg>
                                        @else
                                            <svg class="size-5" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.8">
                                                <rect x="3.5" y="4" width="17" height="13" rx="2" />
                                                <path stroke-linecap="round" d="M8 21h8m-4-4v4" />
                                            </svg>
                                        @endif
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h3 class="truncate text-xs font-semibold">{{ $activeSession['device'] }}</h3>
                                            @if ($activeSession['is_current'])
                                                <span class="badge border-success/20 bg-success/10 text-[10px] text-success">Current</span>
                                            @endif
                                        </div>
                                        <p class="mt-1 truncate text-[11px] text-base-content/45">
                                            {{ $activeSession['ip_address'] }} · {{ $activeSession['last_active'] }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if (count($activeSessions) > 1)
                            <form wire:submit="logoutOtherSessions"
                                class="flex flex-col gap-3 rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] p-4 shadow-inner sm:flex-row sm:items-end">
                                <fieldset class="fieldset min-w-0 flex-1">
                                    <legend class="fieldset-legend text-xs">Confirm your password</legend>
                                    <input type="password" wire:model="session_password" autocomplete="current-password"
                                        class="input h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-raised)] shadow-admin-control focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/10"
                                        placeholder="Current password">
                                    @error('session_password')
                                        <p class="text-xs text-error">{{ $message }}</p>
                                    @enderror
                                </fieldset>
                                <button type="submit" wire:loading.attr="disabled" wire:target="logoutOtherSessions"
                                    class="btn btn-outline btn-sm shrink-0">
                                    <span wire:loading wire:target="logoutOtherSessions"
                                        class="loading loading-spinner loading-xs"></span>
                                    <span wire:loading.remove wire:target="logoutOtherSessions">Log out other sessions</span>
                                </button>
                            </form>
                        @else
                            <p class="text-[11px] text-base-content/40">No other active administrator sessions were found.</p>
                        @endif
                    </div>
                </section>
            </div>

            <div x-cloak x-show="profileSection === 'preferences'" x-transition.opacity class="space-y-5">
                <section class="card border border-[var(--admin-border)] bg-[var(--admin-surface)] shadow-admin-panel">
                    <div class="card-body gap-6 p-5 sm:p-6">
                        <div class="flex items-center gap-3 border-b border-[var(--admin-border)] pb-5">
                            <span class="grid size-10 place-items-center rounded-xl border border-accent/20 bg-accent/10 text-accent shadow-admin-control">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm0-5v2m0 14v2M3 12h2m14 0h2M5.6 5.6 7 7m10 10 1.4 1.4m0-12.8L17 7M7 17l-1.4 1.4" />
                                </svg>
                            </span>
                            <div>
                                <h2 class="text-sm font-semibold">Admin interface preferences</h2>
                                <p class="mt-0.5 text-xs text-base-content/45">Personalize this browser without affecting the storefront or other administrators.</p>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-xs font-semibold">Appearance</h3>
                            <p class="mt-1 text-[11px] text-base-content/45">Choose how the administration interface is displayed.</p>
                            <div class="mt-3 grid gap-3 sm:grid-cols-3">
                                @foreach ([
                                    ['light', 'Light', 'M12 3v2m0 14v2M3 12h2m14 0h2M5.6 5.6 7 7m10 10 1.4 1.4m0-12.8L17 7M7 17l-1.4 1.4M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8Z'],
                                    ['dark', 'Dark', 'M20 15.2A8 8 0 0 1 8.8 4 8 8 0 1 0 20 15.2Z'],
                                    ['system', 'System', 'M4 5h16v12H4V5Zm5 16h6m-3-4v4'],
                                ] as [$themeValue, $themeLabel, $themeIcon])
                                    <button type="button" @click="setTheme('{{ $themeValue }}')"
                                        class="flex items-center gap-3 rounded-xl border p-3.5 text-left transition-all"
                                        :class="themePreference === '{{ $themeValue }}'
                                            ? 'border-accent/40 bg-accent/10 shadow-admin-control ring-1 ring-accent/20'
                                            : 'border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-inner hover:border-[var(--admin-border-strong)] hover:bg-[var(--admin-surface-soft)]'">
                                        <span class="grid size-9 shrink-0 place-items-center rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] shadow-admin-control">
                                            <svg class="size-4" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="{{ $themeIcon }}" />
                                            </svg>
                                        </span>
                                        <span class="text-xs font-medium">{{ $themeLabel }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="border-t border-[var(--admin-border)] pt-5">
                            <h3 class="text-xs font-semibold">Desktop sidebar</h3>
                            <p class="mt-1 text-[11px] text-base-content/45">Select the navigation width retained for your next visit.</p>
                            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                <button type="button" @click="setSidebarCollapsed(false)"
                                    class="flex items-center gap-3 rounded-xl border p-3.5 text-left transition-all"
                                    :class="!sidebarCollapsed
                                        ? 'border-accent/40 bg-accent/10 shadow-admin-control ring-1 ring-accent/20'
                                        : 'border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-inner hover:border-[var(--admin-border-strong)] hover:bg-[var(--admin-surface-soft)]'">
                                    <span class="grid size-9 shrink-0 place-items-center rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] shadow-admin-control">
                                        <svg class="size-4" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.8">
                                            <rect x="3" y="4" width="18" height="16" rx="2" />
                                            <path d="M9 4v16" />
                                        </svg>
                                    </span>
                                    <span><strong class="block text-xs font-medium">Expanded</strong><small
                                            class="mt-0.5 block text-[10px] text-base-content/40">Icons and labels</small></span>
                                </button>
                                <button type="button" @click="setSidebarCollapsed(true)"
                                    class="flex items-center gap-3 rounded-xl border p-3.5 text-left transition-all"
                                    :class="sidebarCollapsed
                                        ? 'border-accent/40 bg-accent/10 shadow-admin-control ring-1 ring-accent/20'
                                        : 'border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-inner hover:border-[var(--admin-border-strong)] hover:bg-[var(--admin-surface-soft)]'">
                                    <span class="grid size-9 shrink-0 place-items-center rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] shadow-admin-control">
                                        <svg class="size-4" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.8">
                                            <rect x="5" y="3" width="14" height="18" rx="2" />
                                            <path d="M10 3v18" />
                                        </svg>
                                    </span>
                                    <span><strong class="block text-xs font-medium">Compact</strong><small
                                            class="mt-0.5 block text-[10px] text-base-content/40">Icons only</small></span>
                                </button>
                            </div>
                        </div>

                        <div class="alert rounded-xl border border-accent/20 bg-accent/8 text-[11px] text-base-content shadow-inner">
                            <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.9">
                                <circle cx="12" cy="12" r="9" />
                                <path stroke-linecap="round" d="M12 11v5m0-8h.01" />
                            </svg>
                            <span>Interface preferences are saved securely in this browser and apply only to the admin panel.</span>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
