<?php

use App\Livewire\Admin\Coupons\Form as CouponForm;
use App\Livewire\Admin\Coupons\Index as CouponIndex;
use App\Livewire\Admin\Orders\Index as OrderIndex;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ShippingLocation;
use App\Models\ShippingZone;
use App\Models\User;
use Livewire\Livewire;

function salesAdmin(): User
{
    return User::factory()->create([
        'role' => 'admin',
        'status' => 'active',
    ]);
}

function salesLocation(): ShippingLocation
{
    $zone = ShippingZone::create([
        'name' => 'Yangon',
        'fee' => 5,
        'estimated_days' => '2–3 days',
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

function salesOrder(
    ShippingLocation $location,
    string $number,
    string $status = 'pending',
    float $total = 100,
    ?Coupon $coupon = null,
): Order {
    return Order::create([
        'coupon_id' => $coupon?->id,
        'shipping_location_id' => $location->id,
        'order_number' => $number,
        'email' => strtolower($number).'@example.test',
        'shipping_full_name' => 'Moe Thura',
        'shipping_phone' => '+95 9 123 456 789',
        'shipping_country' => 'Myanmar',
        'shipping_state_region' => 'Yangon',
        'shipping_city' => 'Yangon',
        'shipping_district_area' => 'Bahan',
        'shipping_address_line1' => 'No. 10, Merchant Road',
        'billing_full_name' => 'Moe Thura',
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
        'status' => $status,
    ]);
}

function salesCoupon(string $code, array $overrides = []): Coupon
{
    return Coupon::create(array_merge([
        'code' => $code,
        'description' => "{$code} promotion",
        'discount_type' => 'percentage',
        'discount_value' => 10,
        'start_date' => now()->subDay()->toDateString(),
        'end_date' => now()->addWeek()->toDateString(),
        'usage_limit' => 100,
        'used_count' => 0,
        'is_active' => true,
    ], $overrides));
}

test('orders can be searched and filtered by lifecycle and latest payment status', function () {
    $admin = salesAdmin();
    $location = salesLocation();
    $pending = salesOrder($location, 'TICKS-PENDING', 'pending', 150);
    $delivered = salesOrder($location, 'TICKS-DELIVERED', 'delivered', 650);

    Payment::create([
        'order_id' => $pending->id,
        'method' => 'bank_transfer',
        'status' => 'pending',
        'amount' => 150,
    ]);
    Payment::create([
        'order_id' => $delivered->id,
        'method' => 'card',
        'status' => 'paid',
        'amount' => 650,
        'paid_at' => now(),
    ]);

    Livewire::actingAs($admin)
        ->test(OrderIndex::class)
        ->set('status', 'delivered')
        ->assertSee('TICKS-DELIVERED')
        ->assertDontSee('TICKS-PENDING')
        ->set('status', 'all')
        ->set('payment', 'pending')
        ->assertSee('TICKS-PENDING')
        ->assertDontSee('TICKS-DELIVERED')
        ->set('payment', 'all')
        ->set('search', 'delivered')
        ->assertSee('TICKS-DELIVERED')
        ->assertDontSee('TICKS-PENDING');
});

test('an administrator can inspect an order and update individual and bulk statuses', function () {
    $admin = salesAdmin();
    $location = salesLocation();
    $first = salesOrder($location, 'TICKS-1001');
    $second = salesOrder($location, 'TICKS-1002');

    Livewire::actingAs($admin)
        ->test(OrderIndex::class)
        ->call('openOrder', $first->id)
        ->assertSet('drawerOpen', true)
        ->assertSet('selectedOrderStatus', 'pending')
        ->set('selectedOrderStatus', 'processing')
        ->call('updateOrderStatus')
        ->assertHasNoErrors()
        ->assertDispatched('admin-notify')
        ->set('selected', [$first->id, $second->id])
        ->call('bulkUpdateStatus', 'shipped')
        ->assertSet('selected', [])
        ->assertDispatched('admin-notify');

    expect($first->fresh()->status)->toBe('shipped')
        ->and($second->fresh()->status)->toBe('shipped');
});

test('coupon lifecycle views distinguish active scheduled expired and inactive promotions', function () {
    $admin = salesAdmin();

    salesCoupon('ACTIVE10');
    salesCoupon('NEXT10', [
        'start_date' => now()->addWeek()->toDateString(),
        'end_date' => now()->addWeeks(2)->toDateString(),
    ]);
    salesCoupon('ENDED10', [
        'start_date' => now()->subWeeks(2)->toDateString(),
        'end_date' => now()->subDay()->toDateString(),
    ]);
    salesCoupon('OFF10', ['is_active' => false]);

    Livewire::actingAs($admin)
        ->test(CouponIndex::class)
        ->set('status', 'active')
        ->assertSee('ACTIVE10')
        ->assertDontSee('NEXT10')
        ->set('status', 'scheduled')
        ->assertSee('NEXT10')
        ->assertDontSee('ACTIVE10')
        ->set('status', 'expired')
        ->assertSee('ENDED10')
        ->assertDontSee('OFF10')
        ->set('status', 'inactive')
        ->assertSee('OFF10')
        ->assertDontSee('ACTIVE10');
});

test('used coupons are preserved while unused coupons can be removed in bulk', function () {
    $admin = salesAdmin();
    $location = salesLocation();
    $used = salesCoupon('USED10', ['used_count' => 1]);
    $unused = salesCoupon('UNUSED10');

    salesOrder($location, 'TICKS-COUPON', coupon: $used);

    Livewire::actingAs($admin)
        ->test(CouponIndex::class)
        ->set('selected', [$used->id, $unused->id])
        ->call('bulkDelete')
        ->assertSet('selected', [])
        ->assertDispatched('admin-notify');

    expect(Coupon::find($used->id))->not->toBeNull()
        ->and(Coupon::find($unused->id))->toBeNull();
});

test('the coupon editor validates percentages and creates a normalized promotion', function () {
    $admin = salesAdmin();

    Livewire::actingAs($admin)
        ->test(CouponForm::class)
        ->set('code', 'summer25')
        ->set('discount_type', 'percentage')
        ->set('discount_value', '125')
        ->set('start_date', now()->toDateString())
        ->set('end_date', now()->addWeek()->toDateString())
        ->call('save')
        ->assertHasErrors(['discount_value'])
        ->set('discount_value', '25')
        ->set('minimum_order_amount', '200')
        ->set('usage_limit', '50')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.coupons.index'));

    $coupon = Coupon::where('code', 'SUMMER25')->firstOrFail();

    expect($coupon->discount_value)->toBe('25.00')
        ->and($coupon->usage_limit)->toBe(50)
        ->and($coupon->is_active)->toBeTrue();
});
