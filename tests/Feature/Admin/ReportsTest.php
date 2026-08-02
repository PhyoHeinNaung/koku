<?php

use App\Livewire\Admin\Reports\Index as ReportIndex;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShippingLocation;
use App\Models\ShippingZone;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Livewire;

function reportsAdmin(): User
{
    return User::factory()->create([
        'role' => 'admin',
        'status' => 'active',
    ]);
}

function reportsLocation(): ShippingLocation
{
    $zone = ShippingZone::create([
        'name' => 'Reports Zone',
        'fee' => 5,
        'estimated_days' => '2-3 business days',
        'is_active' => true,
    ]);

    return $zone->locations()->create([
        'country' => 'Myanmar',
        'state_region' => 'Yangon',
        'city' => 'Yangon',
        'is_active' => true,
    ]);
}

function reportsVariant(): ProductVariant
{
    $brand = Brand::create([
        'name' => 'Report Horology',
        'slug' => 'report-horology',
        'tier' => 'luxury',
        'is_active' => true,
    ]);
    $category = Category::create([
        'name' => 'Report Watches',
        'slug' => 'report-watches',
        'is_active' => true,
    ]);
    $product = Product::create([
        'brand_id' => $brand->id,
        'category_id' => $category->id,
        'name' => 'TICKS Heritage',
        'slug' => 'ticks-heritage',
        'description' => 'A report fixture watch.',
        'gender' => 'unisex',
        'watch_type' => 'traditional',
        'movement' => 'automatic',
        'is_active' => true,
        'is_featured' => false,
    ]);

    return $product->variants()->create([
        'name' => 'Champagne Dial',
        'sku' => 'REPORT-HERITAGE-01',
        'price' => 500,
        'stock_quantity' => 4,
        'is_active' => true,
        'is_default' => true,
    ]);
}

function reportsOrder(
    ShippingLocation $location,
    string $number,
    string $status,
    float $total,
    Carbon $createdAt,
    ?User $user = null,
    ?string $guestEmail = null,
): Order {
    $order = Order::create([
        'user_id' => $user?->id,
        'shipping_location_id' => $location->id,
        'order_number' => $number,
        'email' => $user?->email ?? $guestEmail ?? strtolower($number).'@example.test',
        'shipping_full_name' => $user?->name ?? 'Guest Buyer',
        'shipping_phone' => '+95 9 123 456 789',
        'shipping_country' => 'Myanmar',
        'shipping_state_region' => 'Yangon',
        'shipping_city' => 'Yangon',
        'shipping_address_line1' => 'No. 10, Merchant Road',
        'billing_full_name' => $user?->name ?? 'Guest Buyer',
        'billing_phone' => '+95 9 123 456 789',
        'billing_country' => 'Myanmar',
        'billing_state_region' => 'Yangon',
        'billing_city' => 'Yangon',
        'billing_address_line1' => 'No. 10, Merchant Road',
        'subtotal' => $total,
        'discount' => 0,
        'tax' => 0,
        'shipping_fee' => 0,
        'insurance_fee' => 0,
        'total' => $total,
        'status' => $status,
    ]);

    $order->forceFill([
        'created_at' => $createdAt,
        'updated_at' => $createdAt,
    ])->saveQuietly();

    return $order;
}

test('only administrators can open reports and insights', function () {
    $admin = reportsAdmin();

    $this->actingAs($admin)
        ->get(route('admin.reports.index'))
        ->assertOk()
        ->assertSee('Reports & insights');

    $customer = User::factory()->create(['role' => 'user']);

    $this->actingAs($customer)
        ->get(route('admin.reports.index'))
        ->assertForbidden();
});

test('sales reporting uses real date scoped gross and collected values', function () {
    $this->travelTo(Carbon::parse('2026-07-30 12:00:00'));

    $admin = reportsAdmin();
    $location = reportsLocation();
    $delivered = reportsOrder($location, 'TICKS-REPORT-01', 'delivered', 500, now()->subDay());
    reportsOrder($location, 'TICKS-REPORT-02', 'pending', 300, now()->subDays(10));
    reportsOrder($location, 'TICKS-REPORT-03', 'cancelled', 900, now()->subDay());

    Payment::create([
        'order_id' => $delivered->id,
        'method' => 'card',
        'status' => 'paid',
        'amount' => 500,
        'paid_at' => now()->subDay(),
    ]);

    Livewire::actingAs($admin)
        ->test(ReportIndex::class)
        ->assertViewHas('summary', fn (array $summary) => $summary['orders'] === 3
            && $summary['gross_sales'] === 800.0
            && $summary['collected'] === 500.0)
        ->call('setRange', '7')
        ->assertViewHas('summary', fn (array $summary) => $summary['orders'] === 2
            && $summary['gross_sales'] === 500.0
            && $summary['cancellation_rate'] === 50.0);
});

test('product and customer reports use order snapshots and include guest buyers', function () {
    $this->travelTo(Carbon::parse('2026-07-30 12:00:00'));

    $admin = reportsAdmin();
    $location = reportsLocation();
    $variant = reportsVariant();
    $customer = User::factory()->create([
        'name' => 'Registered Buyer',
        'role' => 'user',
        'status' => 'active',
        'created_at' => now()->subDays(5),
    ]);
    $registered = reportsOrder($location, 'TICKS-REPORT-PRODUCT', 'delivered', 1000, now()->subDay(), $customer);
    $guest = reportsOrder($location, 'TICKS-REPORT-GUEST', 'processing', 500, now(), null, 'guest@ticks.test');
    $cancelled = reportsOrder($location, 'TICKS-REPORT-CANCELLED', 'cancelled', 500, now(), null, 'cancelled@ticks.test');

    foreach ([[$registered, 2, 1000], [$guest, 1, 500], [$cancelled, 1, 500]] as [$order, $quantity, $subtotal]) {
        $order->items()->create([
            'variant_id' => $variant->id,
            'variant_sku' => $variant->sku,
            'product_name' => 'TICKS Heritage',
            'variant_name' => 'Champagne Dial',
            'unit_price' => 500,
            'quantity' => $quantity,
            'subtotal' => $subtotal,
        ]);
    }

    Livewire::actingAs($admin)
        ->test(ReportIndex::class)
        ->set('tab', 'products')
        ->assertSee('TICKS Heritage')
        ->assertViewHas('summary', fn (array $summary) => $summary['units_sold'] === 3
            && $summary['merchandise_sales'] === 1500.0
            && $summary['low_stock'] === 1)
        ->set('tab', 'customers')
        ->assertSee('Registered Buyer')
        ->assertSee('guest@ticks.test')
        ->assertDontSee('cancelled@ticks.test')
        ->assertViewHas('summary', fn (array $summary) => $summary['buyers'] === 2
            && $summary['registered_buyers'] === 1
            && $summary['guest_buyers'] === 1);
});

test('custom report ranges validate and csv export follows the active report', function () {
    $admin = reportsAdmin();

    Livewire::actingAs($admin)
        ->test(ReportIndex::class)
        ->set('from', '2026-07-20')
        ->set('to', '2026-07-10')
        ->call('applyCustomRange')
        ->assertHasErrors(['to'])
        ->set('to', '2026-07-30')
        ->call('applyCustomRange')
        ->assertHasNoErrors()
        ->assertSet('range', 'custom')
        ->set('tab', 'products')
        ->call('exportCsv')
        ->assertFileDownloaded('ticks-products-report-'.now()->format('Y-m-d').'.csv');
});
