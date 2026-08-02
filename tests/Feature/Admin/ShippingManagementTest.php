<?php

use App\Livewire\Admin\Shipping\Index as ShippingIndex;
use App\Livewire\Customer\Checkout\Index as CheckoutIndex;
use App\Models\Order;
use App\Models\ShippingLocation;
use App\Models\ShippingZone;
use App\Models\User;
use Livewire\Livewire;

function shippingAdmin(): User
{
    return User::factory()->create([
        'role' => 'admin',
        'status' => 'active',
    ]);
}

function shippingZone(string $name, array $overrides = []): ShippingZone
{
    return ShippingZone::create(array_merge([
        'name' => $name,
        'fee' => 5,
        'estimated_days' => '2–3 business days',
        'description' => "{$name} delivery coverage.",
        'is_active' => true,
    ], $overrides));
}

function shippingLocation(ShippingZone $zone, string $city, array $overrides = []): ShippingLocation
{
    return $zone->locations()->create(array_merge([
        'country' => 'Myanmar',
        'state_region' => $city,
        'city' => $city,
        'district_area' => null,
        'is_active' => true,
    ], $overrides));
}

function shippingOrder(ShippingLocation $location): Order
{
    return Order::create([
        'shipping_location_id' => $location->id,
        'order_number' => 'TICKS-SHIPPING-001',
        'email' => 'shipping@example.test',
        'shipping_full_name' => 'Moe Thura',
        'shipping_phone' => '+95 9 123 456 789',
        'shipping_country' => 'Myanmar',
        'shipping_state_region' => 'Yangon',
        'shipping_city' => 'Yangon',
        'shipping_address_line1' => 'No. 10, Merchant Road',
        'billing_full_name' => 'Moe Thura',
        'billing_phone' => '+95 9 123 456 789',
        'billing_country' => 'Myanmar',
        'billing_state_region' => 'Yangon',
        'billing_city' => 'Yangon',
        'billing_address_line1' => 'No. 10, Merchant Road',
        'subtotal' => 500,
        'discount' => 0,
        'tax' => 0,
        'shipping_fee' => $location->zone->fee,
        'insurance_fee' => 0,
        'total' => 505,
        'status' => 'pending',
    ]);
}

test('an administrator can create shipping zones and locations from one workspace', function () {
    $admin = shippingAdmin();

    Livewire::actingAs($admin)
        ->test(ShippingIndex::class)
        ->call('openCreate', 'zone')
        ->assertSet('editorOpen', true)
        ->set('name', 'Upper Myanmar')
        ->set('fee', '8.50')
        ->set('estimatedDays', '3–5 business days')
        ->set('description', 'Mandalay and surrounding service areas.')
        ->call('saveZone')
        ->assertHasNoErrors()
        ->assertSet('editorOpen', false)
        ->assertDispatched('admin-notify');

    $zone = ShippingZone::where('name', 'Upper Myanmar')->firstOrFail();

    Livewire::actingAs($admin)
        ->test(ShippingIndex::class)
        ->set('tab', 'locations')
        ->call('openCreate', 'location')
        ->set('zoneId', (string) $zone->id)
        ->set('country', 'Myanmar')
        ->set('stateRegion', 'Mandalay')
        ->set('city', 'Mandalay')
        ->set('districtArea', 'Chanayethazan')
        ->call('saveLocation')
        ->assertHasNoErrors()
        ->assertSet('editorOpen', false)
        ->assertDispatched('admin-notify');

    $location = ShippingLocation::where('city', 'Mandalay')->firstOrFail();

    expect($zone->fee)->toBe('8.50')
        ->and($location->shipping_zone_id)->toBe($zone->id)
        ->and($location->district_area)->toBe('Chanayethazan');
});

test('shipping zones and locations can be searched filtered and sorted', function () {
    $admin = shippingAdmin();
    $yangon = shippingZone('Yangon Metro', ['fee' => 3]);
    $mandalay = shippingZone('Mandalay Region', ['fee' => 8, 'is_active' => false]);
    shippingLocation($yangon, 'Yangon', ['district_area' => 'Bahan']);
    shippingLocation($mandalay, 'Mandalay', ['is_active' => false]);

    Livewire::actingAs($admin)
        ->test(ShippingIndex::class)
        ->set('status', 'inactive')
        ->assertSee('Mandalay Region')
        ->assertDontSee('Yangon Metro')
        ->call('clearAll')
        ->set('sort', 'fee_desc')
        ->assertSeeInOrder(['Mandalay Region', 'Yangon Metro'])
        ->set('tab', 'locations')
        ->set('search', 'Bahan')
        ->assertSee('Yangon')
        ->assertDontSee('Mandalay');
});

test('linked shipping records are protected from deletion', function () {
    $admin = shippingAdmin();
    $zone = shippingZone('Protected Zone');
    $linked = shippingLocation($zone, 'Yangon');
    $unusedZone = shippingZone('Unused Zone');
    $unusedLocation = shippingLocation($unusedZone, 'Naypyidaw');
    shippingOrder($linked);

    Livewire::actingAs($admin)
        ->test(ShippingIndex::class)
        ->call('deleteZone', $zone->id)
        ->assertDispatched('admin-notify')
        ->set('selected', [$zone->id, $unusedZone->id])
        ->call('bulkDelete')
        ->assertSet('selected', [])
        ->assertDispatched('admin-notify');

    expect(ShippingZone::find($zone->id))->not->toBeNull()
        ->and(ShippingZone::find($unusedZone->id))->not->toBeNull();

    Livewire::actingAs($admin)
        ->test(ShippingIndex::class)
        ->set('tab', 'locations')
        ->call('deleteLocation', $linked->id)
        ->assertDispatched('admin-notify')
        ->set('selected', [$linked->id, $unusedLocation->id])
        ->call('bulkDelete')
        ->assertSet('selected', [])
        ->assertDispatched('admin-notify');

    expect(ShippingLocation::find($linked->id))->not->toBeNull()
        ->and(ShippingLocation::find($unusedLocation->id))->toBeNull();
});

test('zone availability controls whether its active locations can be used at checkout', function () {
    $zone = shippingZone('Checkout Zone');
    shippingLocation($zone, 'Yangon');

    $checkout = new CheckoutIndex;
    $checkout->country = 'Myanmar';
    $checkout->state_region = 'Yangon';

    expect($checkout->getShippingLocationProperty())->not->toBeNull()
        ->and($checkout->getShippingFeeProperty())->toBe(5.0);

    $zone->update(['is_active' => false]);

    $inactiveCheckout = new CheckoutIndex;
    $inactiveCheckout->country = 'Myanmar';
    $inactiveCheckout->state_region = 'Yangon';

    expect($inactiveCheckout->getShippingLocationProperty())->toBeNull()
        ->and($inactiveCheckout->getShippingFeeProperty())->toBe(0.0);
});

test('shipping records support bulk activation changes', function () {
    $admin = shippingAdmin();
    $first = shippingZone('First Bulk Zone');
    $second = shippingZone('Second Bulk Zone');

    Livewire::actingAs($admin)
        ->test(ShippingIndex::class)
        ->set('selected', [$first->id, $second->id])
        ->call('bulkSetActive', false)
        ->assertSet('selected', [])
        ->assertDispatched('admin-notify');

    expect($first->fresh()->is_active)->toBeFalse()
        ->and($second->fresh()->is_active)->toBeFalse();
});
