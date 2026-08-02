<div class="px-6 sm:px-10 lg:px-16 py-10">

    <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

        <div class="lg:col-span-2">

            @if ($step === 'details')
                <div class="space-y-8">

                    @auth
                        @if ($savedAddresses->isNotEmpty())
                            <div>
                                <h2 class="text-sm font-semibold text-gray-900 mb-3">Shipping Address</h2>
                                <div class="space-y-3">
                                    @foreach ($savedAddresses as $addr)
                                        <label wire:key="addr-{{ $addr->id }}"
                                            class="flex items-start gap-3 border rounded-lg p-4 cursor-pointer {{ $selectedAddressId === $addr->id && !$addNewAddress ? 'border-gray-900' : 'border-gray-200' }}">
                                            <input type="radio" wire:click="selectSavedAddress({{ $addr->id }})"
                                                {{ $selectedAddressId === $addr->id && !$addNewAddress ? 'checked' : '' }}
                                                class="mt-1 border-gray-300">
                                            <div class="text-sm">
                                                <p class="font-medium text-gray-900">{{ $addr->label ?: 'Address' }}</p>
                                                <p class="text-gray-500 mt-0.5">{{ $addr->full_name }} &middot; {{ $addr->phone }}</p>
                                                <p class="text-gray-500">{{ $addr->address_line1 }}, {{ $addr->city }}, {{ $addr->state_region }}, {{ $addr->country }}</p>
                                            </div>
                                        </label>
                                    @endforeach

                                    <button type="button" wire:click="showNewAddressForm"
                                        class="w-full border border-dashed border-gray-300 rounded-lg p-4 text-sm text-gray-600 hover:border-gray-500 {{ $addNewAddress ? 'border-gray-900 text-gray-900' : '' }}">
                                        + Use a new address
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endauth

                    @if ($addNewAddress || !Auth::check())
                        <div>
                            @auth
                                @if ($savedAddresses->isNotEmpty())
                                    <h2 class="text-sm font-semibold text-gray-900 mb-3">New Address</h2>
                                @endif
                            @endauth

                            <div class="space-y-4">
                                @guest
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                        <input type="email" wire:model="email"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
                                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                @endguest

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                        <input type="text" wire:model="full_name"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
                                        @error('full_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                        <input type="text" wire:model="phone"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
                                        @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">State / Region</label>
                                        <select wire:model="state_region"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
                                            <option value="">Select</option>
                                            @foreach ($stateOptions as $state)
                                                <option value="{{ $state }}">{{ $state }}</option>
                                            @endforeach
                                        </select>
                                        @error('state_region') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">City / Township</label>
                                        <input type="text" wire:model="city"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
                                        @error('city') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">District / Area</label>
                                        <input type="text" wire:model="district_area"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                                        <input type="text" wire:model="postal_code"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 1</label>
                                    <input type="text" wire:model="address_line1"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
                                    @error('address_line1') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 2 (optional)</label>
                                    <input type="text" wire:model="address_line2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
                                </div>

                                @auth
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" wire:model="saveAddressForFuture" class="rounded border-gray-300">
                                        <span class="text-sm text-gray-700">Save this address for future orders</span>
                                    </label>
                                @endauth
                            </div>
                        </div>
                    @endif

                    @if ($storeSettings->insurance_enabled)
                        <div>
                            <label class="flex items-start gap-3 border border-gray-200 rounded-lg p-4 cursor-pointer">
                                <input type="checkbox" wire:model="wantsInsurance" class="mt-0.5 rounded border-gray-300">
                                <span class="text-sm">
                                    <span class="text-gray-900 font-medium">Add delivery insurance</span>
                                    <span class="text-gray-500 block">Covers loss or damage in transit — {{ rtrim(rtrim(number_format((float) $storeSettings->insurance_rate * 100, 2), '0'), '.') }}% of order subtotal.</span>
                                </span>
                            </label>
                        </div>
                    @endif

                    <button type="button" wire:click="proceedToPayment"
                        class="w-full py-3 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800">
                        Continue to Payment
                    </button>

                </div>
            @else
                <div class="space-y-4" wire:ignore x-data x-init="initCheckoutStripe(@js($clientSecret))">
                    <h2 class="text-sm font-semibold text-gray-900">Payment</h2>
                    <div id="payment-element" class="border border-gray-200 rounded-lg p-4"></div>
                    <div id="payment-error" class="hidden text-sm text-red-600"></div>
                    <button type="button" id="submit-payment"
                        class="w-full py-3 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800">
                        Pay ${{ number_format($this->total, 2) }}
                    </button>
                    <p class="text-xs text-gray-400">Test mode — use card <span class="font-mono">4242 4242 4242 4242</span>, any future expiry, any CVC.</p>
                </div>
            @endif

        </div>

        {{-- Order summary --}}
        <div class="bg-gray-50 rounded-xl p-6 h-fit">
            <h2 class="text-sm font-semibold text-gray-900 mb-4">Order Summary</h2>

            <div class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                @foreach ($this->cartItems as $item)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ $item->variant->product->name }} × {{ $item->quantity }}</span>
                        <span class="text-gray-900">${{ number_format($item->quantity * $item->unit_price, 2) }}</span>
                    </div>
                @endforeach
            </div>

            @if ($step === 'details')
                <div class="mb-4">
                    @if ($this->appliedCoupon)
                        <div class="flex items-center justify-between text-sm bg-green-50 text-green-800 rounded-lg px-3 py-2">
                            <span>{{ $this->appliedCoupon->code }} applied</span>
                            <button type="button" wire:click="removeCoupon" class="underline">Remove</button>
                        </div>
                    @else
                        <div class="flex gap-2">
                            <input type="text" wire:model="couponCode" placeholder="Coupon code"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
                            <button type="button" wire:click="applyCoupon" class="px-4 py-2 border border-gray-300 rounded-lg text-sm hover:border-gray-500">
                                Apply
                            </button>
                        </div>
                        @error('couponCode') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    @endif
                </div>
            @endif

            <div class="border-t border-gray-200 pt-4 space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-600">Subtotal</span><span class="text-gray-900">${{ number_format($this->subtotal, 2) }}</span></div>
                @if ($this->discount > 0)
                    <div class="flex justify-between"><span class="text-gray-600">Discount</span><span class="text-green-700">−${{ number_format($this->discount, 2) }}</span></div>
                @endif
                <div class="flex justify-between"><span class="text-gray-600">Shipping</span><span class="text-gray-900">{{ $this->shippingFee > 0 ? '$' . number_format($this->shippingFee, 2) : 'Select address' }}</span></div>
                @if ($this->insuranceFee > 0)
                    <div class="flex justify-between"><span class="text-gray-600">Insurance</span><span class="text-gray-900">${{ number_format($this->insuranceFee, 2) }}</span></div>
                @endif
                <div class="flex justify-between font-semibold text-base pt-2 border-t border-gray-200"><span>Total</span><span>${{ number_format($this->total, 2) }}</span></div>
            </div>
        </div>

    </div>

</div>
