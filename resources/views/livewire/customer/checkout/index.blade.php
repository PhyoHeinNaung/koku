<div class="bg-[var(--koku-paper)]">
    <header class="border-b border-[var(--koku-line)] bg-[var(--koku-white)]">
        <div class="koku-shell flex items-end justify-between py-12 sm:py-16">
            <div><p class="koku-eyebrow text-[var(--koku-indigo)]">Secure checkout</p><h1 class="mt-4 font-serif text-4xl font-medium tracking-[-0.05em] sm:text-5xl">Complete your order</h1></div>
            <div class="hidden items-center gap-3 text-[10px] uppercase tracking-[0.12em] sm:flex"><span class="{{ $step === 'details' ? 'text-[var(--koku-indigo)]' : 'text-[var(--koku-muted)]' }}">01 Details</span><span class="h-px w-8 bg-[var(--koku-line)]"></span><span class="{{ $step === 'payment' ? 'text-[var(--koku-indigo)]' : 'text-[var(--koku-muted)]' }}">02 Payment</span></div>
        </div>
    </header>

    <main class="koku-shell py-10 sm:py-16 lg:py-20">
        <div class="grid gap-14 lg:grid-cols-[minmax(0,1.2fr)_minmax(21rem,.65fr)] lg:gap-20">
            <section>
                @if ($step === 'details')
                    <div class="space-y-14">
                        @auth
                            @if ($savedAddresses->isNotEmpty())
                                <div><div class="mb-6 flex items-center justify-between border-b border-[var(--koku-ink)] pb-4"><h2 class="font-serif text-2xl">Delivery address</h2><span class="koku-eyebrow text-[var(--koku-muted)]">Saved</span></div>
                                    <div class="grid gap-3 sm:grid-cols-2">
                                        @foreach ($savedAddresses as $addr)
                                            <label wire:key="addr-{{ $addr->id }}" class="cursor-pointer border p-5 {{ $selectedAddressId === $addr->id && !$addNewAddress ? 'border-[var(--koku-indigo)] bg-[color-mix(in_srgb,var(--koku-indigo)_4%,white)]' : 'border-[var(--koku-line)] bg-[var(--koku-white)]' }}">
                                                <div class="flex items-start gap-3"><input type="radio" wire:click="selectSavedAddress({{ $addr->id }})" {{ $selectedAddressId === $addr->id && !$addNewAddress ? 'checked' : '' }} class="mt-1 border-[var(--koku-line)] text-[var(--koku-indigo)] focus:ring-[var(--koku-indigo)]"><div class="text-xs leading-6"><strong class="font-medium">{{ $addr->label ?: $addr->full_name }}</strong><p class="mt-1 text-[var(--koku-muted)]">{{ $addr->full_name }} · {{ $addr->phone }}<br>{{ $addr->address_line1 }}, {{ $addr->city }}<br>{{ $addr->state_region }}, {{ $addr->country }}</p></div></div>
                                            </label>
                                        @endforeach
                                        <button type="button" wire:click="showNewAddressForm" class="min-h-32 border border-dashed p-5 text-left text-sm {{ $addNewAddress ? 'border-[var(--koku-indigo)] text-[var(--koku-indigo)]' : 'border-[var(--koku-line)] text-[var(--koku-muted)]' }}">＋ Use a different address</button>
                                    </div>
                                </div>
                            @endif
                        @endauth

                        @if ($addNewAddress || !Auth::check())
                            <div>
                                <div class="mb-7 border-b border-[var(--koku-ink)] pb-4"><p class="koku-eyebrow text-[var(--koku-indigo)]">01</p><h2 class="mt-2 font-serif text-2xl">Contact and delivery</h2></div>
                                <div class="grid gap-5 sm:grid-cols-2">
                                    @guest<div class="sm:col-span-2"><label class="koku-field-label">Email address</label><input type="email" wire:model="email" autocomplete="email" class="koku-field">@error('email')<p class="koku-field-error">{{ $message }}</p>@enderror</div>@endguest
                                    <div><label class="koku-field-label">Full name</label><input type="text" wire:model="full_name" autocomplete="name" class="koku-field">@error('full_name')<p class="koku-field-error">{{ $message }}</p>@enderror</div>
                                    <div><label class="koku-field-label">Phone</label><input type="tel" wire:model="phone" autocomplete="tel" class="koku-field">@error('phone')<p class="koku-field-error">{{ $message }}</p>@enderror</div>
                                    <div><label class="koku-field-label">State / Region</label><select wire:model="state_region" class="koku-field"><option value="">Select a region</option>@foreach ($stateOptions as $state)<option value="{{ $state }}">{{ $state }}</option>@endforeach</select>@error('state_region')<p class="koku-field-error">{{ $message }}</p>@enderror</div>
                                    <div><label class="koku-field-label">City / Township</label><input type="text" wire:model="city" autocomplete="address-level2" class="koku-field">@error('city')<p class="koku-field-error">{{ $message }}</p>@enderror</div>
                                    <div><label class="koku-field-label">District / Area</label><input type="text" wire:model="district_area" class="koku-field"></div>
                                    <div><label class="koku-field-label">Postal code</label><input type="text" wire:model="postal_code" autocomplete="postal-code" class="koku-field"></div>
                                    <div class="sm:col-span-2"><label class="koku-field-label">Address line 1</label><input type="text" wire:model="address_line1" autocomplete="address-line1" class="koku-field">@error('address_line1')<p class="koku-field-error">{{ $message }}</p>@enderror</div>
                                    <div class="sm:col-span-2"><label class="koku-field-label">Address line 2 <span class="normal-case tracking-normal">(optional)</span></label><input type="text" wire:model="address_line2" autocomplete="address-line2" class="koku-field"></div>
                                </div>
                                @auth<label class="mt-5 flex cursor-pointer items-center gap-3 text-xs text-[var(--koku-muted)]"><input type="checkbox" wire:model="saveAddressForFuture" class="rounded-none border-[var(--koku-line)] text-[var(--koku-indigo)] focus:ring-[var(--koku-indigo)]">Save this address for future orders</label>@endauth
                            </div>
                        @endif

                        @if ($storeSettings->insurance_enabled)
                            <label class="flex cursor-pointer items-start justify-between gap-5 border-y border-[var(--koku-line)] py-5"><span><strong class="font-serif text-lg font-medium">Delivery insurance</strong><small class="mt-1 block max-w-md text-xs leading-5 text-[var(--koku-muted)]">Protects the order against loss or damage in transit at {{ rtrim(rtrim(number_format((float) $storeSettings->insurance_rate * 100, 2), '0'), '.') }}% of the subtotal.</small></span><input type="checkbox" wire:model.live="wantsInsurance" class="mt-1 rounded-none border-[var(--koku-line)] text-[var(--koku-indigo)] focus:ring-[var(--koku-indigo)]"></label>
                        @endif

                        <button type="button" wire:click="proceedToPayment" wire:loading.attr="disabled" class="w-full bg-[var(--koku-indigo)] px-6 py-4 text-xs font-medium uppercase tracking-[0.14em] text-white hover:bg-[var(--koku-indigo-deep)] disabled:opacity-50">Continue to payment</button>
                    </div>
                @else
                    <div wire:ignore x-data x-init="initCheckoutStripe(@js($clientSecret))">
                        <div class="border-b border-[var(--koku-ink)] pb-5"><p class="koku-eyebrow text-[var(--koku-indigo)]">02</p><h2 class="mt-2 font-serif text-3xl">Payment</h2><p class="mt-3 text-sm text-[var(--koku-muted)]">Enter your billing and card details securely below.</p></div>
                        <div id="payment-element" class="mt-8 border border-[var(--koku-line)] bg-[var(--koku-white)] p-5 sm:p-7"></div>
                        <div id="payment-error" class="mt-3 hidden text-sm text-[#a33b32]"></div>
                        <button type="button" id="submit-payment" class="mt-6 w-full bg-[var(--koku-indigo)] px-6 py-4 text-xs font-medium uppercase tracking-[0.14em] text-white hover:bg-[var(--koku-indigo-deep)]">Pay ${{ number_format($this->total, 2) }}</button>
                        <p class="mt-4 text-[10px] leading-5 text-[var(--koku-muted)]">Test mode · Use 4242 4242 4242 4242, any future expiry and any CVC.</p>
                    </div>
                @endif
            </section>

            <aside class="h-fit border-t border-[var(--koku-ink)] pt-6 lg:sticky lg:top-28">
                <div class="flex items-center justify-between"><p class="koku-eyebrow text-[var(--koku-indigo)]">Order summary</p><a href="{{ route('cart.index') }}" class="text-xs underline underline-offset-4">Edit cart</a></div>
                <div class="mt-6 max-h-80 space-y-5 overflow-y-auto border-b border-[var(--koku-line)] pb-6">
                    @foreach ($this->cartItems as $item)
                        <div class="grid grid-cols-[4rem_1fr_auto] items-start gap-3"><div class="relative aspect-[4/5] overflow-hidden bg-[#eae8e2]">@if ($item->variant->images->first())<img src="{{ Storage::url($item->variant->images->first()->image_url) }}" alt="" class="h-full w-full object-cover">@endif<span class="absolute right-0 top-0 flex size-5 items-center justify-center bg-[var(--koku-indigo)] text-[9px] text-white">{{ $item->quantity }}</span></div><div class="min-w-0"><p class="truncate font-serif text-sm">{{ $item->variant->product->name }}</p><p class="mt-1 truncate text-[10px] text-[var(--koku-muted)]">{{ $item->variant->name }}</p></div><span class="text-xs">${{ number_format($item->quantity * $item->unit_price, 2) }}</span></div>
                    @endforeach
                </div>

                @if ($step === 'details')
                    <div class="border-b border-[var(--koku-line)] py-6">
                        @if ($this->appliedCoupon)
                            <div class="flex items-center justify-between border border-[var(--koku-indigo)] px-3 py-3 text-xs text-[var(--koku-indigo)]"><span>{{ $this->appliedCoupon->code }} applied</span><button wire:click="removeCoupon" class="underline">Remove</button></div>
                        @else
                            <div class="flex"><input type="text" wire:model="couponCode" placeholder="Coupon code" class="koku-field min-w-0 flex-1"><button type="button" wire:click="applyCoupon" class="border-y border-r border-[var(--koku-line)] px-4 text-xs uppercase tracking-[0.08em]">Apply</button></div>@error('couponCode')<p class="koku-field-error">{{ $message }}</p>@enderror
                        @endif
                    </div>
                @endif

                <div class="space-y-3 py-6 text-xs">
                    <div class="flex justify-between"><span class="text-[var(--koku-muted)]">Subtotal</span><span>${{ number_format($this->subtotal, 2) }}</span></div>
                    @if ($this->discount > 0)<div class="flex justify-between text-[var(--koku-indigo)]"><span>Discount</span><span>−${{ number_format($this->discount, 2) }}</span></div>@endif
                    <div class="flex justify-between"><span class="text-[var(--koku-muted)]">Delivery</span><span>{{ $this->shippingFee > 0 ? '$'.number_format($this->shippingFee, 2) : 'Pending address' }}</span></div>
                    @if ($this->insuranceFee > 0)<div class="flex justify-between"><span class="text-[var(--koku-muted)]">Insurance</span><span>${{ number_format($this->insuranceFee, 2) }}</span></div>@endif
                </div>
                <div class="flex items-end justify-between border-t border-[var(--koku-line)] pt-5"><span class="font-serif text-lg">Total</span><strong class="font-serif text-2xl font-medium">${{ number_format($this->total, 2) }}</strong></div>
                <p class="mt-6 text-[10px] leading-5 text-[var(--koku-muted)]">Secure payment processing. Your payment information is never stored by Koku.</p>
            </aside>
        </div>
    </main>
</div>
