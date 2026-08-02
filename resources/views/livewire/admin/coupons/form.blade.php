<div class="mx-auto w-full max-w-[1500px] space-y-5" x-data>
    <header class="flex flex-col gap-4 rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] p-4 shadow-admin-panel sm:flex-row sm:items-center sm:justify-between sm:px-5">
        <div class="flex min-w-0 items-center gap-3">
            <a href="{{ route('admin.coupons.index') }}"
                class="btn btn-square btn-sm shrink-0 rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] shadow-admin-control hover:bg-[var(--admin-surface-soft)]"
                aria-label="Back to coupons">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6" />
                </svg>
            </a>

            <div class="min-w-0">
                <p class="text-[10px] font-medium uppercase tracking-[0.12em] text-base-content/40">Sales · Coupons</p>
                <h1 class="mt-0.5 truncate text-lg font-semibold tracking-tight sm:text-xl">
                    {{ $coupon ? 'Edit coupon' : 'New coupon' }}
                </h1>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-ghost btn-sm h-10 min-h-10 rounded-xl px-4">Cancel</a>
            <button type="submit" form="coupon-form" wire:loading.attr="disabled" wire:target="save"
                class="btn btn-primary btn-sm h-10 min-h-10 min-w-32 rounded-xl px-5 shadow-[0_10px_24px_rgba(255,122,0,.2)]">
                <span wire:loading wire:target="save" class="loading loading-spinner loading-xs"></span>
                <span wire:loading.remove wire:target="save">{{ $coupon ? 'Save changes' : 'Create coupon' }}</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
        </div>
    </header>

    <form id="coupon-form" wire:submit="save"
        class="grid items-start gap-5 lg:grid-cols-[minmax(0,1fr)_22rem]">
        <div class="space-y-5">
            <x-admin.form-section title="Coupon details" description="Customer-facing promotion identity">
                <x-slot:icon>
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 7.5V5a1 1 0 0 1 1-1h2.5a3 3 0 0 0 6 0H19a1 1 0 0 1 1 1v2.5a3 3 0 0 0 0 6V19a1 1 0 0 1-1 1h-5.5a3 3 0 0 0-6 0H5a1 1 0 0 1-1-1v-5.5a3 3 0 0 0 0-6Z" />
                    </svg>
                </x-slot:icon>

                <div class="space-y-5">
                    <x-admin.form-field label="Coupon code" name="code" required>
                        <input id="code" type="text" wire:model.live.debounce.250ms="code"
                            class="input input-bordered h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] uppercase shadow-admin-control focus:border-accent focus:outline-none"
                            autocomplete="off" spellcheck="false">
                    </x-admin.form-field>

                    <x-admin.form-field label="Description" name="description">
                        <textarea id="description" wire:model="description" rows="4"
                            class="textarea textarea-bordered w-full resize-y rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] leading-6 shadow-admin-control focus:border-accent focus:outline-none"></textarea>
                    </x-admin.form-field>
                </div>
            </x-admin.form-section>

            <x-admin.form-section title="Discount rules" description="Value and qualifying cart conditions">
                <x-slot:icon>
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m19 5-14 14M7.5 8A1.5 1.5 0 1 0 7.5 5a1.5 1.5 0 0 0 0 3Zm9 11a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                    </svg>
                </x-slot:icon>

                <div class="grid gap-4 sm:grid-cols-2">
                    <x-admin.form-field label="Discount type" name="discount_type" required>
                        <select id="discount_type" wire:model.live="discount_type"
                            class="select select-bordered h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                            <option value="fixed">Fixed amount</option>
                            <option value="percentage">Percentage</option>
                        </select>
                    </x-admin.form-field>

                    <x-admin.form-field label="Discount value" name="discount_value" required>
                        <label class="input input-bordered flex h-11 w-full items-center gap-2 rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus-within:border-accent">
                            <span class="text-sm text-base-content/35"
                                x-text="$wire.discount_type === 'percentage' ? '%' : '$'"></span>
                            <input id="discount_value" type="number" min="0" step="0.01"
                                wire:model.live.debounce.250ms="discount_value"
                                class="min-w-0 grow bg-transparent outline-none">
                        </label>
                    </x-admin.form-field>

                    <x-admin.form-field label="Minimum order amount" name="minimum_order_amount"
                        class="sm:col-span-2">
                        <label class="input input-bordered flex h-11 w-full items-center gap-2 rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus-within:border-accent">
                            <span class="text-sm text-base-content/35">$</span>
                            <input id="minimum_order_amount" type="number" min="0" step="0.01"
                                wire:model="minimum_order_amount" class="min-w-0 grow bg-transparent outline-none">
                        </label>
                    </x-admin.form-field>
                </div>
            </x-admin.form-section>
        </div>

        <aside class="space-y-5 lg:sticky lg:top-20">
            <x-admin.form-section title="Availability" description="Promotion window">
                <div class="space-y-4">
                    <x-admin.form-field label="Start date" name="start_date" required>
                        <input id="start_date" type="date" wire:model="start_date"
                            class="input input-bordered h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                    </x-admin.form-field>

                    <x-admin.form-field label="End date" name="end_date" required>
                        <input id="end_date" type="date" wire:model="end_date"
                            class="input input-bordered h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                    </x-admin.form-field>
                </div>
            </x-admin.form-section>

            <x-admin.form-section title="Usage" description="Redemption controls">
                <div class="space-y-4">
                    <x-admin.form-field label="Redemption limit" name="usage_limit">
                        <input id="usage_limit" type="number" min="1" wire:model="usage_limit"
                            class="input input-bordered h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                    </x-admin.form-field>

                    <x-admin.switch-row label="Active coupon" description="Available when its date and usage rules pass">
                        <input type="checkbox" wire:model="is_active" class="toggle toggle-primary toggle-sm">
                    </x-admin.switch-row>
                </div>
            </x-admin.form-section>

            <section class="relative overflow-hidden rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] shadow-admin-panel">
                <span class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/15 to-transparent"></span>
                <div class="border-b border-dashed border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-5 py-5">
                    <div class="flex items-start justify-between gap-4">
                        <span>
                            <span class="block text-[10px] font-semibold uppercase tracking-[0.14em] text-base-content/35">
                                Coupon preview
                            </span>
                            <strong class="mt-2 block truncate font-mono text-lg tracking-[0.08em]"
                                x-text="($wire.code || 'WATCH10').toUpperCase()"></strong>
                        </span>
                        <span class="grid size-9 shrink-0 place-items-center rounded-xl border border-accent/20 bg-accent/10 text-accent shadow-admin-control">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 7.5V5a1 1 0 0 1 1-1h2.5a3 3 0 0 0 6 0H19a1 1 0 0 1 1 1v2.5a3 3 0 0 0 0 6V19a1 1 0 0 1-1 1h-5.5a3 3 0 0 0-6 0H5a1 1 0 0 1-1-1v-5.5a3 3 0 0 0 0-6Z" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="px-5 py-4 text-xs text-base-content/55">
                    <span x-show="$wire.discount_type === 'fixed'">
                        <strong class="text-base-content" x-text="'$' + ($wire.discount_value || '0')"></strong>
                        off the qualifying order
                    </span>
                    <span x-cloak x-show="$wire.discount_type === 'percentage'">
                        <strong class="text-base-content" x-text="($wire.discount_value || '0') + '%'"></strong>
                        off the qualifying order
                    </span>
                </div>
            </section>
        </aside>
    </form>
</div>
