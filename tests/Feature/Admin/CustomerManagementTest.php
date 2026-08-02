<?php

use App\Livewire\Admin\Customers\Index as CustomerIndex;
use App\Models\Order;
use App\Models\ShippingLocation;
use App\Models\ShippingZone;
use App\Models\User;
use Livewire\Livewire;

function customerManagementAdmin(): User
{
    return User::factory()->create([
        'role' => 'admin',
        'status' => 'active',
    ]);
}

function managedCustomer(string $name, array $overrides = []): User
{
    return User::factory()->create(array_merge([
        'name' => $name,
        'role' => 'user',
        'status' => 'active',
    ], $overrides));
}

function customerManagementLocation(): ShippingLocation
{
    $zone = ShippingZone::create([
        'name' => 'Customer Test Zone',
        'fee' => 5,
        'estimated_days' => '2-3 business days',
        'is_active' => true,
    ]);

    return $zone->locations()->create([
        'country' => 'Myanmar',
        'state_region' => 'Yangon',
        'city' => 'Yangon',
        'district_area' => 'Bahan',
        'is_active' => true,
    ]);
}

function customerManagementOrder(
    User $customer,
    ShippingLocation $location,
    string $number,
    float $total = 100,
): Order {
    return Order::create([
        'user_id' => $customer->id,
        'shipping_location_id' => $location->id,
        'order_number' => $number,
        'email' => $customer->email,
        'shipping_full_name' => $customer->name,
        'shipping_phone' => '+95 9 123 456 789',
        'shipping_country' => 'Myanmar',
        'shipping_state_region' => 'Yangon',
        'shipping_city' => 'Yangon',
        'shipping_district_area' => 'Bahan',
        'shipping_address_line1' => 'No. 10, Merchant Road',
        'billing_full_name' => $customer->name,
        'billing_phone' => '+95 9 123 456 789',
        'billing_country' => 'Myanmar',
        'billing_state_region' => 'Yangon',
        'billing_city' => 'Yangon',
        'billing_district_area' => 'Bahan',
        'billing_address_line1' => 'No. 10, Merchant Road',
        'subtotal' => $total,
        'discount' => 0,
        'tax' => 0,
        'shipping_fee' => 0,
        'insurance_fee' => 0,
        'total' => $total,
        'status' => 'pending',
    ]);
}

test('only administrators can open customer management', function () {
    $admin = customerManagementAdmin();

    $this->actingAs($admin)
        ->get(route('admin.customers.index'))
        ->assertOk()
        ->assertSee('Customers');

    $customer = managedCustomer('Regular Customer');

    $this->actingAs($customer)
        ->get(route('admin.customers.index'))
        ->assertForbidden();
});

test('customers can be searched and filtered by lifecycle verification and order activity', function () {
    $admin = customerManagementAdmin();
    $location = customerManagementLocation();
    $active = managedCustomer('Aye Aye', ['email' => 'aye@example.test']);
    $pending = managedCustomer('Bo Bo', [
        'email' => 'bo@example.test',
        'status' => 'pending',
        'email_verified_at' => null,
    ]);
    managedCustomer('Cherry Win', ['status' => 'banned']);
    customerManagementOrder($active, $location, 'TICKS-CUSTOMER-1001', 450);

    Livewire::actingAs($admin)
        ->test(CustomerIndex::class)
        ->set('status', 'pending')
        ->assertSee('Bo Bo')
        ->assertDontSee('Aye Aye')
        ->set('status', 'all')
        ->set('verification', 'unverified')
        ->assertSee('Bo Bo')
        ->assertDontSee('Aye Aye')
        ->set('verification', 'all')
        ->set('activity', 'with_orders')
        ->assertSee('Aye Aye')
        ->assertDontSee('Bo Bo')
        ->set('activity', 'all')
        ->set('search', 'TICKS-CUSTOMER-1001')
        ->assertSee('Aye Aye')
        ->assertDontSee('Bo Bo');

    expect($pending->fresh()->status)->toBe('pending');
});

test('an administrator can inspect customer context and update account status', function () {
    $admin = customerManagementAdmin();
    $customer = managedCustomer('Mya Mya', [
        'email' => 'mya@example.test',
        'phone' => '+95 9 555 123 456',
    ]);
    $location = customerManagementLocation();
    customerManagementOrder($customer, $location, 'TICKS-CUSTOMER-DETAIL', 725);

    $customer->addresses()->create([
        'label' => 'Home',
        'full_name' => $customer->name,
        'phone' => '+95 9 555 123 456',
        'country' => 'Myanmar',
        'state_region' => 'Yangon',
        'city' => 'Yangon',
        'district_area' => 'Kamayut',
        'address_line1' => 'No. 20, Inya Road',
        'is_default' => true,
    ]);

    Livewire::actingAs($admin)
        ->test(CustomerIndex::class)
        ->call('openCustomer', $customer->id)
        ->assertSet('drawerOpen', true)
        ->assertSet('selectedCustomerStatus', 'active')
        ->assertSee('TICKS-CUSTOMER-DETAIL')
        ->assertSee('No. 20, Inya Road')
        ->set('selectedCustomerStatus', 'banned')
        ->call('updateCustomerStatus')
        ->assertHasNoErrors()
        ->assertDispatched('admin-notify');

    expect($customer->fresh()->status)->toBe('banned');
});

test('bulk customer status changes never modify administrator accounts', function () {
    $admin = customerManagementAdmin();
    $first = managedCustomer('First Customer', ['status' => 'pending']);
    $second = managedCustomer('Second Customer', ['status' => 'pending']);

    Livewire::actingAs($admin)
        ->test(CustomerIndex::class)
        ->set('selected', [$first->id, $second->id, $admin->id])
        ->call('bulkUpdateStatus', 'banned')
        ->assertSet('selected', [])
        ->assertDispatched('admin-notify');

    expect($first->fresh()->status)->toBe('banned')
        ->and($second->fresh()->status)->toBe('banned')
        ->and($admin->fresh()->status)->toBe('active');
});
