<?php

use App\Livewire\Admin\Dashboard;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShippingLocation;
use App\Models\ShippingZone;
use App\Models\User;
use Livewire\Livewire;

function dashboardAdmin(): User
{
    return User::factory()->create([
        'role' => 'admin',
        'status' => 'active',
    ]);
}

function dashboardLocation(): ShippingLocation
{
    $zone = ShippingZone::create([
        'name' => 'Dashboard delivery',
        'fee' => 5,
        'estimated_days' => '2-3 days',
        'is_active' => true,
    ]);

    return $zone->locations()->create([
        'country' => 'Myanmar',
        'state_region' => 'Yangon',
        'city' => 'Yangon',
        'is_active' => true,
    ]);
}

function dashboardVariant(int $stock = 3): ProductVariant
{
    $brand = Brand::create([
        'name' => 'Dashboard Brand',
        'slug' => 'dashboard-brand',
        'tier' => 'luxury',
        'is_active' => true,
    ]);
    $category = Category::create([
        'name' => 'Dashboard Category',
        'slug' => 'dashboard-category',
        'is_active' => true,
    ]);
    $product = Product::create([
        'brand_id' => $brand->id,
        'category_id' => $category->id,
        'name' => 'TICKS Dashboard Watch',
        'slug' => 'ticks-dashboard-watch',
        'description' => 'Dashboard reporting watch.',
        'gender' => 'unisex',
        'watch_type' => 'traditional',
        'movement' => 'automatic',
        'is_active' => true,
        'is_featured' => false,
    ]);

    return $product->variants()->create([
        'name' => 'Champagne Dial',
        'sku' => 'TICKS-DASH-CH',
        'price' => 500,
        'stock_quantity' => $stock,
        'is_active' => true,
        'is_default' => true,
    ]);
}

function dashboardOrder(
    ShippingLocation $location,
    string $number,
    string $status,
    float $total,
    DateTimeInterface $createdAt,
): Order {
    $order = Order::create([
        'shipping_location_id' => $location->id,
        'order_number' => $number,
        'email' => strtolower($number).'@example.test',
        'shipping_full_name' => 'Dashboard Buyer',
        'shipping_phone' => '+95 9 123 456 789',
        'shipping_country' => 'Myanmar',
        'shipping_state_region' => 'Yangon',
        'shipping_city' => 'Yangon',
        'shipping_address_line1' => 'Dashboard Street',
        'billing_full_name' => 'Dashboard Buyer',
        'billing_phone' => '+95 9 123 456 789',
        'billing_country' => 'Myanmar',
        'billing_state_region' => 'Yangon',
        'billing_city' => 'Yangon',
        'billing_address_line1' => 'Dashboard Street',
        'subtotal' => $total,
        'discount' => 0,
        'tax' => 0,
        'shipping_fee' => 0,
        'insurance_fee' => 0,
        'total' => $total,
        'status' => $status,
    ]);
    $order->forceFill(['created_at' => $createdAt, 'updated_at' => $createdAt])->save();

    return $order;
}

test('only administrators can open the operational dashboard', function () {
    $this->actingAs(dashboardAdmin())
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Operations overview');

    $this->actingAs(User::factory()->create([
        'role' => 'user',
        'status' => 'active',
    ]))
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

test('dashboard metrics use real orders payments and catalog stock', function () {
    $location = dashboardLocation();
    $variant = dashboardVariant();
    $delivered = dashboardOrder($location, 'TICKS-DASH-1001', 'delivered', 500, now()->subDay());
    $pending = dashboardOrder($location, 'TICKS-DASH-1002', 'pending', 300, now()->subDays(2));
    dashboardOrder($location, 'TICKS-DASH-CANCELLED', 'cancelled', 900, now()->subDay());

    $delivered->items()->create([
        'variant_id' => $variant->id,
        'variant_sku' => $variant->sku,
        'product_name' => $variant->product->name,
        'variant_name' => $variant->name,
        'unit_price' => 500,
        'quantity' => 1,
        'subtotal' => 500,
    ]);
    $delivered->payments()->create([
        'method' => 'card',
        'status' => 'paid',
        'transaction_id' => 'pi_dashboard_paid',
        'amount' => 500,
        'paid_at' => now()->subDay(),
    ]);

    Livewire::actingAs(dashboardAdmin())
        ->test(Dashboard::class)
        ->assertSee('$800.00')
        ->assertSee('$500.00 collected')
        ->assertSee('TICKS-DASH-1001')
        ->assertSee('TICKS-DASH-1002')
        ->assertSee('TICKS Dashboard Watch')
        ->assertSee('3 left')
        ->assertSee('1 awaiting action');

    expect($pending->status)->toBe('pending');
});

test('dashboard period controls filter operational activity', function () {
    $location = dashboardLocation();
    dashboardOrder($location, 'TICKS-DASH-RECENT', 'processing', 200, now()->subDays(2));
    dashboardOrder($location, 'TICKS-DASH-OLDER', 'delivered', 400, now()->subDays(15));

    Livewire::actingAs(dashboardAdmin())
        ->test(Dashboard::class)
        ->assertSee('TICKS-DASH-OLDER')
        ->call('setRange', '7')
        ->assertSet('range', '7')
        ->assertSee('TICKS-DASH-RECENT')
        ->assertDontSee('TICKS-DASH-OLDER');
});
