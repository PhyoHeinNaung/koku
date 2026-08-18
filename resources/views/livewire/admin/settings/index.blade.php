<div class="admin-page admin-workflow block" x-data="{ tab: 'identity' }">
    <form wire:submit="save" class="space-y-6">
        <x-admin.page-header title="Store settings" />

        <div class="overflow-hidden rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] shadow-admin-panel">
            <div class="border-b border-[var(--admin-border)] bg-[var(--admin-surface-raised)] px-4 py-3 sm:px-6"
                role="tablist" aria-label="Store setting sections">
                <div class="flex max-w-full items-center gap-1 overflow-x-auto rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] p-1 shadow-inner sm:w-fit">
                    @foreach ([
                        ['id' => 'identity', 'label' => 'Store profile', 'icon' => 'M5 20V7l7-4 7 4v13M9 20v-5h6v5M8 9h.01M12 9h.01M16 9h.01'],
                        ['id' => 'checkout', 'label' => 'Checkout policies', 'icon' => 'M4 6h16v12H4V6Zm0 4h16M8 15h3'],
                    ] as $item)
                        <button type="button" role="tab" @click="tab = '{{ $item['id'] }}'"
                            :aria-selected="(tab === '{{ $item['id'] }}').toString()"
                            class="flex h-9 shrink-0 items-center gap-2 rounded-lg border px-3.5 text-[11px] font-semibold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent/30"
                            :class="tab === '{{ $item['id'] }}'
                                ? 'border-[var(--admin-border-strong)] bg-[var(--admin-surface-raised)] text-base-content shadow-admin-control'
                                : 'border-transparent text-base-content/45 hover:bg-[var(--admin-surface-soft)] hover:text-base-content'">
                            <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.7" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                            </svg>
                            {{ $item['label'] }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div x-show="tab === 'identity'" role="tabpanel" class="p-5 sm:p-7 lg:p-8">
                <div class="grid gap-8 xl:grid-cols-[minmax(0,1fr)_19rem]">
                    <div class="space-y-8">
                        <section>
                            <div class="mb-4 flex items-center gap-3">
                                <span class="grid size-10 place-items-center rounded-xl border border-accent/20 bg-accent/10 text-accent shadow-admin-control">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.7" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M5 20V7l7-4 7 4v13M9 20v-5h6v5M8 9h.01M12 9h.01M16 9h.01" />
                                    </svg>
                                </span>
                                <div>
                                    <h2 class="text-sm font-semibold">Store identity</h2>
                                    <p class="mt-1 text-[11px] text-base-content/45">Public and legal business names.</p>
                                </div>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <x-admin.form-field label="Store name" name="storeName" required>
                                    <input id="storeName" type="text" wire:model="storeName"
                                        class="input h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] text-xs shadow-admin-control focus:border-accent focus:outline-none"
                                        autocomplete="organization">
                                </x-admin.form-field>

                                <x-admin.form-field label="Legal name" name="legalName">
                                    <input id="legalName" type="text" wire:model="legalName"
                                        class="input h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] text-xs shadow-admin-control focus:border-accent focus:outline-none"
                                        autocomplete="organization">
                                </x-admin.form-field>
                            </div>
                        </section>

                        <div class="h-px bg-[var(--admin-border)]"></div>

                        <section>
                            <div class="mb-4">
                                <h2 class="text-sm font-semibold">Customer support</h2>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <x-admin.form-field label="Support email" name="supportEmail">
                                    <input id="supportEmail" type="email" wire:model="supportEmail"
                                        class="input h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] text-xs shadow-admin-control focus:border-accent focus:outline-none"
                                        autocomplete="email">
                                </x-admin.form-field>

                                <x-admin.form-field label="Support phone" name="supportPhone">
                                    <input id="supportPhone" type="tel" wire:model="supportPhone"
                                        class="input h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] text-xs shadow-admin-control focus:border-accent focus:outline-none"
                                        autocomplete="tel">
                                </x-admin.form-field>

                                <x-admin.form-field label="Business address" name="businessAddress"
                                    class="sm:col-span-2">
                                    <textarea id="businessAddress" wire:model="businessAddress" rows="3"
                                        class="textarea min-h-24 w-full resize-y rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] text-xs shadow-admin-control focus:border-accent focus:outline-none"
                                        autocomplete="street-address"></textarea>
                                </x-admin.form-field>

                                <x-admin.form-field label="Default country" name="defaultCountry" required>
                                    <input id="defaultCountry" type="text" wire:model="defaultCountry"
                                        class="input h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] text-xs shadow-admin-control focus:border-accent focus:outline-none"
                                        autocomplete="country-name">
                                </x-admin.form-field>
                            </div>
                        </section>
                    </div>

                    <aside class="h-fit rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] p-5 shadow-admin-control xl:sticky xl:top-24">
                        <p class="text-[9px] font-semibold uppercase tracking-[0.16em] text-base-content/40">Store preview</p>
                        <div class="mt-4 flex items-center gap-3">
                            <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-accent text-sm font-black text-accent-content shadow-lg shadow-accent/15">
                                T
                            </span>
                            <span class="min-w-0">
                                <strong class="block truncate text-sm" x-text="$wire.storeName || 'TICKS'"></strong>
                                <small class="mt-0.5 block truncate text-[10px] text-base-content/40"
                                    x-text="$wire.supportEmail || 'No support email'"></small>
                            </span>
                        </div>
                        <dl class="mt-5 space-y-3 border-t border-[var(--admin-border)] pt-4 text-[10px]">
                            <div class="flex justify-between gap-4">
                                <dt class="text-base-content/40">Country</dt>
                                <dd class="truncate font-medium" x-text="$wire.defaultCountry || '—'"></dd>
                            </div>
                            <div class="flex justify-between gap-4">
                                <dt class="text-base-content/40">Currency</dt>
                                <dd class="font-medium">USD</dd>
                            </div>
                        </dl>
                    </aside>
                </div>
            </div>

            <div x-cloak x-show="tab === 'checkout'" role="tabpanel" class="p-5 sm:p-7 lg:p-8">
                <div class="grid gap-8 xl:grid-cols-[minmax(0,1fr)_19rem]">
                    <div class="space-y-8">
                        <section>
                            <div class="mb-4 flex items-center gap-3">
                                <span class="grid size-10 place-items-center rounded-xl border border-accent/20 bg-accent/10 text-accent shadow-admin-control">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.7" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4 6h16v12H4V6Zm0 4h16M8 15h3" />
                                    </svg>
                                </span>
                                <div>
                                    <h2 class="text-sm font-semibold">Checkout access</h2>
                                    <p class="mt-1 text-[11px] text-base-content/45">Control who can place an order.</p>
                                </div>
                            </div>

                            <x-admin.switch-row label="Guest checkout"
                                description="Allow customers to purchase without signing in.">
                                <input type="checkbox" wire:model="guestCheckoutEnabled"
                                    class="toggle toggle-sm toggle-accent"
                                    aria-label="Enable guest checkout">
                            </x-admin.switch-row>
                        </section>

                        <div class="h-px bg-[var(--admin-border)]"></div>

                        <section>
                            <div class="mb-4">
                                <h2 class="text-sm font-semibold">Order controls</h2>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <x-admin.form-field label="Order prefix" name="orderPrefix" required>
                                    <label class="input flex h-11 items-center gap-2 rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus-within:border-accent">
                                        <span class="text-[10px] text-base-content/35">#</span>
                                        <input id="orderPrefix" type="text" wire:model="orderPrefix"
                                            class="min-w-0 grow border-0 bg-transparent p-0 text-xs uppercase outline-none"
                                            maxlength="8">
                                    </label>
                                </x-admin.form-field>

                                <x-admin.form-field label="Low-stock threshold" name="lowStockThreshold" required>
                                    <input id="lowStockThreshold" type="number" wire:model="lowStockThreshold"
                                        min="1" max="9999"
                                        class="input h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] text-xs shadow-admin-control focus:border-accent focus:outline-none">
                                </x-admin.form-field>
                            </div>
                        </section>

                        <div class="h-px bg-[var(--admin-border)]"></div>

                        <section>
                            <div class="mb-4">
                                <h2 class="text-sm font-semibold">Delivery insurance</h2>
                            </div>

                            <div class="space-y-4">
                                <x-admin.switch-row label="Offer delivery insurance"
                                    description="Let customers add shipment protection at checkout.">
                                    <input type="checkbox" wire:model.live="insuranceEnabled"
                                        class="toggle toggle-sm toggle-accent"
                                        aria-label="Enable delivery insurance">
                                </x-admin.switch-row>

                                <x-admin.form-field label="Insurance rate" name="insuranceRate"
                                    x-show="$wire.insuranceEnabled" x-transition>
                                    <label class="input flex h-11 max-w-52 items-center gap-2 rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus-within:border-accent">
                                        <input id="insuranceRate" type="number" wire:model="insuranceRate"
                                            min="0.1" max="25" step="0.1"
                                            class="min-w-0 grow border-0 bg-transparent p-0 text-xs outline-none">
                                        <span class="text-xs text-base-content/40">%</span>
                                    </label>
                                </x-admin.form-field>
                            </div>
                        </section>
                    </div>

                    <aside class="h-fit rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] p-5 shadow-admin-control xl:sticky xl:top-24">
                        <p class="text-[9px] font-semibold uppercase tracking-[0.16em] text-base-content/40">Commerce setup</p>
                        <dl class="mt-4 space-y-3 text-[10px]">
                            <div class="flex items-center justify-between gap-3">
                                <dt class="text-base-content/45">Payment</dt>
                                <dd><x-admin.badge tone="green">Stripe</x-admin.badge></dd>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <dt class="text-base-content/45">Currency</dt>
                                <dd class="font-semibold">USD</dd>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <dt class="text-base-content/45">Shipping rates</dt>
                                <dd>
                                    <a href="{{ route('admin.shipping.index') }}"
                                        class="font-medium text-accent underline-offset-2 hover:underline">Manage</a>
                                </dd>
                            </div>
                        </dl>
                        <p class="mt-4 border-t border-[var(--admin-border)] pt-4 text-[9px] leading-4 text-base-content/40">
                            Currency follows the current Stripe integration and cannot be changed here.
                        </p>
                    </aside>
                </div>
            </div>
        </div>

        <div class="sticky bottom-3 z-20 flex items-center justify-between gap-4 rounded-2xl border border-[var(--admin-border-strong)] bg-[var(--admin-surface-raised)] px-4 py-3 shadow-admin-panel sm:px-5">
            <span class="text-[10px] text-base-content/40">
                <span wire:dirty class="inline-flex items-center gap-2 text-warning">
                    <span class="size-1.5 rounded-full bg-warning"></span>
                    Unsaved changes
                </span>
                <span wire:dirty.remove>Settings are up to date</span>
            </span>

            <div class="flex items-center gap-2">
                <button type="button" wire:click="discardChanges" wire:dirty
                    class="btn btn-ghost btn-sm h-9 min-h-9 rounded-lg px-3 text-xs">
                    Discard
                </button>
                <button type="submit"
                    class="btn btn-primary btn-sm h-9 min-h-9 min-w-28 rounded-lg border-accent bg-accent px-4 text-xs font-semibold text-accent-content shadow-lg shadow-accent/15 hover:border-accent/90 hover:bg-accent/90"
                    wire:loading.attr="disabled" wire:target="save">
                    <span wire:loading.remove wire:target="save">Save settings</span>
                    <span wire:loading wire:target="save" class="loading loading-spinner loading-xs"></span>
                </button>
            </div>
        </div>
    </form>
</div>
