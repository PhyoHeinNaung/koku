<?php

namespace App\Livewire\Customer\Checkout;

use App\Livewire\Customer\Concerns\ManagesCart;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\ShippingLocation;
use App\Models\StoreSetting;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class Index extends Component
{
    use ManagesCart;

    public string $step = 'details'; // 'details' | 'payment'

    public ?int $selectedAddressId = null;

    public bool $addNewAddress = false;

    public bool $saveAddressForFuture = false;

    public string $full_name = '';

    public string $phone = '';

    public string $email = '';

    public string $country = 'Myanmar';

    public string $state_region = '';

    public string $city = '';

    public string $district_area = '';

    public string $postal_code = '';

    public string $address_line1 = '';

    public string $address_line2 = '';

    public string $couponCode = '';

    public ?int $appliedCouponId = null;

    public bool $wantsInsurance = false;

    public ?int $orderId = null;

    public ?string $clientSecret = null;

    public function mount(): void
    {
        $settings = StoreSetting::current();

        if (! Auth::check() && ! $settings->guest_checkout_enabled) {
            session()->put('url.intended', route('checkout.index'));
            $this->redirectRoute('login');

            return;
        }

        $this->country = $settings->default_country;

        if (Auth::check()) {
            $this->email = Auth::user()->email;

            $default = Address::where('user_id', Auth::id())->where('is_default', true)->first();

            if ($default) {
                $this->selectedAddressId = $default->id;
                $this->fillFromAddress($default);
            } else {
                $this->addNewAddress = true;
            }
        } else {
            $this->addNewAddress = true;
        }

        if ($this->cartItems->isEmpty()) {
            $this->redirectRoute('cart.index');
        }
    }

    public function getCartItemsProperty()
    {
        $cart = $this->currentCart();

        return $cart ? $cart->items()->with('variant.product', 'variant.images')->get() : collect();
    }

    public function getSubtotalProperty(): float
    {
        return (float) $this->cartItems->sum(fn ($item) => $item->quantity * $item->unit_price);
    }

    public function getShippingLocationProperty(): ?ShippingLocation
    {
        if (! $this->state_region) {
            return null;
        }

        return ShippingLocation::with('zone')
            ->where('country', $this->country)
            ->where('state_region', $this->state_region)
            ->where('is_active', true)
            ->whereHas('zone', fn ($query) => $query->where('is_active', true))
            ->first();
    }

    public function getShippingFeeProperty(): float
    {
        return (float) ($this->shippingLocation?->zone?->fee ?? 0);
    }

    public function getAppliedCouponProperty(): ?Coupon
    {
        return $this->appliedCouponId ? Coupon::find($this->appliedCouponId) : null;
    }

    public function getDiscountProperty(): float
    {
        $coupon = $this->appliedCoupon;

        if (! $coupon) {
            return 0;
        }

        return $coupon->discount_type === 'percentage'
            ? round($this->subtotal * ($coupon->discount_value / 100), 2)
            : min((float) $coupon->discount_value, $this->subtotal);
    }

    public function getInsuranceFeeProperty(): float
    {
        $settings = StoreSetting::current();

        return $settings->insurance_enabled && $this->wantsInsurance
            ? round($this->subtotal * (float) $settings->insurance_rate, 2)
            : 0;
    }

    public function getTotalProperty(): float
    {
        return max(0, $this->subtotal - $this->discount + $this->shippingFee + $this->insuranceFee);
    }

    public function selectSavedAddress(int $addressId): void
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($addressId);

        $this->selectedAddressId = $addressId;
        $this->addNewAddress = false;
        $this->fillFromAddress($address);
    }

    public function showNewAddressForm(): void
    {
        $this->selectedAddressId = null;
        $this->addNewAddress = true;
        $this->reset(['full_name', 'phone', 'country', 'state_region', 'city', 'district_area', 'postal_code', 'address_line1', 'address_line2']);
        $this->country = StoreSetting::current()->default_country;
    }

    protected function fillFromAddress(Address $address): void
    {
        $this->full_name = $address->full_name;
        $this->phone = $address->phone;
        $this->country = $address->country;
        $this->state_region = (string) $address->state_region;
        $this->city = $address->city;
        $this->district_area = (string) $address->district_area;
        $this->postal_code = (string) $address->postal_code;
        $this->address_line1 = $address->address_line1;
        $this->address_line2 = (string) $address->address_line2;
    }

    public function applyCoupon(): void
    {
        $this->resetErrorBag('couponCode');

        $coupon = Coupon::where('code', strtoupper($this->couponCode))->first();

        if (! $coupon) {
            $this->addError('couponCode', 'Coupon not found.');

            return;
        }

        if (! $coupon->isValidNow()) {
            $this->addError('couponCode', 'This coupon is no longer valid.');

            return;
        }

        if ($coupon->minimum_order_amount && $this->subtotal < $coupon->minimum_order_amount) {
            $this->addError('couponCode', "This coupon requires a minimum order of \${$coupon->minimum_order_amount}.");

            return;
        }

        $this->appliedCouponId = $coupon->id;
    }

    public function removeCoupon(): void
    {
        $this->appliedCouponId = null;
        $this->couponCode = '';
    }

    public function proceedToPayment(): void
    {
        $validated = $this->validate([
            'email' => ['required', 'email'],
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
            'state_region' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'district_area' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'address_line1' => ['required', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
        ]);

        if (! Auth::check()) {
            $existingUser = User::where('email', $validated['email'])->first();

            if ($existingUser) {
                $this->addError('email', 'An account with this email already exists. Please log in to continue.');

                return;
            }
        }

        $location = $this->shippingLocation;

        if (! $location) {
            $this->addError('state_region', 'Please select a valid state/region.');

            return;
        }

        if ($this->cartItems->isEmpty()) {
            $this->redirectRoute('cart.index');

            return;
        }

        DB::transaction(function () use ($validated, $location) {
            if (Auth::check() && $this->addNewAddress && $this->saveAddressForFuture) {
                Address::create([
                    'user_id' => Auth::id(),
                    'full_name' => $validated['full_name'],
                    'phone' => $validated['phone'],
                    'country' => $validated['country'],
                    'state_region' => $validated['state_region'],
                    'city' => $validated['city'],
                    'district_area' => $validated['district_area'] ?: null,
                    'postal_code' => $validated['postal_code'] ?: null,
                    'address_line1' => $validated['address_line1'],
                    'address_line2' => $validated['address_line2'] ?: null,
                    'is_default' => ! Address::where('user_id', Auth::id())->exists(),
                ]);
            }

            $order = Order::create([
                'user_id' => Auth::id(),
                'coupon_id' => $this->appliedCouponId,
                'shipping_location_id' => $location->id,
                'order_number' => $this->generateOrderNumber(),
                'email' => $validated['email'],

                'shipping_full_name' => $validated['full_name'],
                'shipping_phone' => $validated['phone'],
                'shipping_country' => $validated['country'],
                'shipping_state_region' => $validated['state_region'],
                'shipping_city' => $validated['city'],
                'shipping_district_area' => $validated['district_area'] ?: null,
                'shipping_postal_code' => $validated['postal_code'] ?: null,
                'shipping_address_line1' => $validated['address_line1'],
                'shipping_address_line2' => $validated['address_line2'] ?: null,

                // Billing defaults to shipping at creation time — Stripe's Payment
                // Element collects the customer's real billing details during
                // payment, and the webhook (once payment succeeds) overwrites
                // these with what Stripe actually returns.
                'billing_full_name' => $validated['full_name'],
                'billing_phone' => $validated['phone'],
                'billing_country' => $validated['country'],
                'billing_state_region' => $validated['state_region'],
                'billing_city' => $validated['city'],
                'billing_district_area' => $validated['district_area'] ?: null,
                'billing_postal_code' => $validated['postal_code'] ?: null,
                'billing_address_line1' => $validated['address_line1'],
                'billing_address_line2' => $validated['address_line2'] ?: null,

                'shipping_address_id' => $this->selectedAddressId,

                'subtotal' => $this->subtotal,
                'discount' => $this->discount,
                'tax' => 0,
                'shipping_fee' => $this->shippingFee,
                'insurance_fee' => $this->insuranceFee,
                'total' => $this->total,

                'status' => 'pending',
            ]);

            foreach ($this->cartItems as $item) {
                $order->items()->create([
                    'variant_id' => $item->variant_id,
                    'variant_sku' => $item->variant->sku,
                    'product_name' => $item->variant->product->name,
                    'variant_name' => $item->variant->name,
                    'unit_price' => $item->unit_price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->quantity * $item->unit_price,
                ]);
            }

            $stripe = app(StripeService::class);
            $intent = $stripe->createPaymentIntent($this->total, ['order_id' => $order->id, 'order_number' => $order->order_number]);

            $order->update(['stripe_payment_intent_id' => $intent->id]);

            $this->currentCart()?->update(['expired_at' => now()]);

            $this->orderId = $order->id;
            $this->clientSecret = $intent->client_secret;
        });

        $this->step = 'payment';
    }

    protected function generateOrderNumber(): string
    {
        $prefix = StoreSetting::current()->order_prefix;

        do {
            $number = $prefix.'-'.strtoupper(Str::random(8));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }

    public function render()
    {
        $storeSettings = StoreSetting::current();
        $savedAddresses = Auth::check() ? Address::where('user_id', Auth::id())->orderByDesc('is_default')->get() : collect();
        $stateOptions = ShippingLocation::query()
            ->where('country', $storeSettings->default_country)
            ->where('is_active', true)
            ->whereHas('zone', fn ($query) => $query->where('is_active', true))
            ->whereNotNull('state_region')
            ->distinct()
            ->orderBy('state_region')
            ->pluck('state_region');

        return view('livewire.customer.checkout.index', [
            'savedAddresses' => $savedAddresses,
            'stateOptions' => $stateOptions,
            'storeSettings' => $storeSettings,
        ])->layout('layouts.app', ['overlay' => false]);
    }
}
