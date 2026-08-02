<?php

use App\Livewire\Admin\Settings\Index as StoreSettingsIndex;
use App\Livewire\Customer\Checkout\Index as CheckoutIndex;
use App\Models\StoreSetting;
use App\Models\User;
use Livewire\Livewire;

function storeSettingsAdmin(): User
{
    return User::factory()->create([
        'role' => 'admin',
        'status' => 'active',
    ]);
}

test('only administrators can open store settings', function () {
    $admin = storeSettingsAdmin();

    $this->actingAs($admin)
        ->get(route('admin.settings.index'))
        ->assertOk()
        ->assertSee('Store settings');

    $this->actingAs(User::factory()->create([
        'role' => 'user',
        'status' => 'active',
    ]))
        ->get(route('admin.settings.index'))
        ->assertForbidden();
});

test('an administrator can update store identity and checkout policies', function () {
    Livewire::actingAs(storeSettingsAdmin())
        ->test(StoreSettingsIndex::class)
        ->set('storeName', 'TICKS Atelier')
        ->set('legalName', 'Ticks Commerce Co., Ltd.')
        ->set('supportEmail', 'care@ticks.test')
        ->set('supportPhone', '+95 9 123 456 789')
        ->set('businessAddress', 'Yangon, Myanmar')
        ->set('defaultCountry', 'Myanmar')
        ->set('orderPrefix', 'tick')
        ->set('lowStockThreshold', 8)
        ->set('insuranceEnabled', true)
        ->set('insuranceRate', '2.5')
        ->set('guestCheckoutEnabled', false)
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('orderPrefix', 'TICK')
        ->assertDispatched('admin-notify');

    $settings = StoreSetting::current();

    expect($settings->store_name)->toBe('TICKS Atelier')
        ->and($settings->support_email)->toBe('care@ticks.test')
        ->and($settings->order_prefix)->toBe('TICK')
        ->and($settings->low_stock_threshold)->toBe(8)
        ->and((float) $settings->insurance_rate)->toBe(0.025)
        ->and($settings->guest_checkout_enabled)->toBeFalse();
});

test('store policy validation rejects unsafe values', function () {
    Livewire::actingAs(storeSettingsAdmin())
        ->test(StoreSettingsIndex::class)
        ->set('supportEmail', 'not-an-email')
        ->set('orderPrefix', '!')
        ->set('lowStockThreshold', 0)
        ->set('insuranceEnabled', true)
        ->set('insuranceRate', '30')
        ->call('save')
        ->assertHasErrors([
            'supportEmail' => 'email',
            'orderPrefix',
            'lowStockThreshold' => 'min',
            'insuranceRate' => 'max',
        ]);
});

test('disabling guest checkout sends guests to authentication', function () {
    StoreSetting::query()->firstOrFail()->update(['guest_checkout_enabled' => false]);

    Livewire::test(CheckoutIndex::class)
        ->assertRedirect(route('login'));
});
