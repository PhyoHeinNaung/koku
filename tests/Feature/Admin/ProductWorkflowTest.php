<?php

use App\Livewire\Admin\Products\Form as ProductForm;
use App\Livewire\Admin\Products\VariantImages;
use App\Livewire\Admin\Products\Variants;
use App\Livewire\Customer\Shop\Show as ShopShow;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Livewire\Livewire;

function workflowProduct(array $overrides = []): Product
{
    $brand = Brand::create([
        'name' => 'Test Watch Co.',
        'slug' => 'test-watch-co',
        'tier' => 'premium',
        'is_active' => true,
    ]);

    $category = Category::create([
        'name' => 'Test Watches',
        'slug' => 'test-watches',
        'is_active' => true,
    ]);

    return Product::create(array_merge([
        'brand_id' => $brand->id,
        'category_id' => $category->id,
        'name' => 'Test Chronometer',
        'slug' => 'test-chronometer',
        'description' => 'A product used to verify the admin workflow.',
        'gender' => 'unisex',
        'movement' => 'automatic',
        'is_active' => false,
        'is_featured' => false,
    ], $overrides));
}

test('a product cannot be published without an active default variant', function () {
    $product = workflowProduct();

    Livewire::test(ProductForm::class, ['product' => $product])
        ->set('is_active', true)
        ->call('save')
        ->assertHasErrors(['is_active']);

    expect($product->fresh()->is_active)->toBeFalse();
});

test('the first variant becomes default and deleting it returns the product to draft', function () {
    $product = workflowProduct();

    Livewire::test(Variants::class, ['product' => $product])
        ->call('addNew')
        ->set('name', 'Silver dial')
        ->set('sku', 'TEST-SILVER')
        ->set('price', '1250.00')
        ->set('stock_quantity', '5')
        ->set('is_active', true)
        ->call('save')
        ->assertDispatched('admin-notify')
        ->assertHasNoErrors();

    $variant = $product->variants()->firstOrFail();

    expect($variant->is_default)->toBeTrue()
        ->and($variant->is_active)->toBeTrue();

    $product->update(['is_active' => true]);

    Livewire::test(Variants::class, ['product' => $product->fresh()])
        ->call('deleteVariant', $variant->id)
        ->assertDispatched('admin-notify');

    expect($product->fresh()->is_active)->toBeFalse()
        ->and($product->variants()->count())->toBe(0);
});

test('one variant manager session can create multiple variants', function () {
    $product = workflowProduct();

    $manager = Livewire::test(Variants::class, ['product' => $product])
        ->call('openManager')
        ->assertSet('drawerOpen', true)
        ->call('addNew')
        ->set('name', 'Silver dial')
        ->set('sku', 'TEST-MULTI-SILVER')
        ->set('price', '1250.00')
        ->set('stock_quantity', '5')
        ->call('save')
        ->assertSet('drawerOpen', true)
        ->assertSet('editorOpen', true)
        ->call('addNew')
        ->assertSet('editingId', null)
        ->set('name', 'Black dial')
        ->set('sku', 'TEST-MULTI-BLACK')
        ->set('price', '1350.00')
        ->set('stock_quantity', '3')
        ->call('save')
        ->assertHasNoErrors();

    expect($product->variants()->count())->toBe(2)
        ->and($product->variants()->where('is_default', true)->count())->toBe(1);
});

test('variant images can be reordered and a cover image can be selected', function () {
    $product = workflowProduct();
    $variant = $product->variants()->create([
        'name' => 'Black dial',
        'sku' => 'TEST-BLACK',
        'price' => 1400,
        'stock_quantity' => 3,
        'is_active' => true,
        'is_default' => true,
    ]);

    $first = ProductImage::create([
        'variant_id' => $variant->id,
        'image_url' => 'products/first.webp',
        'is_primary' => true,
        'sort_order' => 0,
    ]);

    $second = ProductImage::create([
        'variant_id' => $variant->id,
        'image_url' => 'products/second.webp',
        'is_primary' => false,
        'sort_order' => 1,
    ]);

    Livewire::test(VariantImages::class, ['variant' => $variant])
        ->call('reorderImage', $second->id, 0)
        ->assertDispatched('admin-notify')
        ->call('setPrimary', $second->id)
        ->assertDispatched('admin-notify');

    expect($second->fresh()->sort_order)->toBe(0)
        ->and($second->fresh()->is_primary)->toBeTrue()
        ->and($first->fresh()->sort_order)->toBe(1)
        ->and($first->fresh()->is_primary)->toBeFalse();
});

test('a variant inherits shared specifications and only replaces explicit overrides', function () {
    $product = workflowProduct();
    $product->specification()->create([
        'case_size' => '40mm',
        'dial_color' => 'Silver',
        'custom_specifications' => ['Warranty' => '2 years'],
    ]);

    $variant = $product->variants()->create([
        'name' => 'Blue dial',
        'sku' => 'TEST-SPEC-BLUE',
        'price' => 1500,
        'stock_quantity' => 2,
        'is_active' => true,
        'is_default' => true,
    ]);

    $variant->specification()->create([
        'overrides' => [
            'dial_color' => 'Blue',
            'custom_specifications' => ['Warranty' => '5 years', 'Reference' => 'BLU-40'],
        ],
    ]);

    $effective = $variant->effectiveSpecifications($product->specification);

    expect($effective['case_size'])->toBe('40mm')
        ->and($effective['dial_color'])->toBe('Blue')
        ->and($effective['custom_specifications'])->toBe([
            'Warranty' => '5 years',
            'Reference' => 'BLU-40',
        ]);
});

test('the variant manager saves only opted in specification overrides', function () {
    $product = workflowProduct();
    $product->specification()->create([
        'case_size' => '40mm',
        'dial_color' => 'Silver',
    ]);

    Livewire::test(Variants::class, ['product' => $product])
        ->call('addNew')
        ->set('name', 'Blue dial')
        ->set('sku', 'TEST-OVERRIDE-BLUE')
        ->set('price', '1750.00')
        ->set('stock_quantity', '4')
        ->set('overriddenSpecs.dial_color', true)
        ->set('specOverrides.dial_color', 'Blue')
        ->set('specOverrides.case_size', '42mm')
        ->call('save')
        ->assertHasNoErrors();

    $variant = $product->variants()->firstOrFail();

    expect($variant->specification->overrides)->toBe(['dial_color' => 'Blue'])
        ->and($variant->effectiveSpecifications($product->specification)['case_size'])->toBe('40mm');
});

test('traditional products discard smart shared values and smart variant overrides', function () {
    $product = workflowProduct([
        'watch_type' => 'smart',
        'movement' => 'smart',
    ]);
    $product->specification()->create([
        'battery_life' => '7 days',
        'connectivity' => 'Bluetooth',
    ]);
    $variant = $product->variants()->create([
        'name' => 'Connected edition',
        'sku' => 'TEST-SMART-CLEAR',
        'price' => 950,
        'stock_quantity' => 1,
        'is_active' => true,
        'is_default' => true,
    ]);
    $variant->specification()->create([
        'overrides' => [
            'battery_life' => '10 days',
            'case_size' => '42mm',
        ],
    ]);

    Livewire::test(ProductForm::class, ['product' => $product])
        ->set('watch_type', 'traditional')
        ->set('movement', 'quartz')
        ->call('save')
        ->assertHasNoErrors();

    expect($product->fresh()->watch_type)->toBe('traditional')
        ->and($product->specification->fresh()->battery_life)->toBeNull()
        ->and($variant->specification->fresh()->overrides)->toBe(['case_size' => '42mm']);
});

test('the storefront changes effective specifications with the selected variant', function () {
    $product = workflowProduct([
        'watch_type' => 'traditional',
        'is_active' => true,
    ]);
    $product->specification()->create([
        'dial_color' => 'Silver',
    ]);

    $silver = $product->variants()->create([
        'name' => 'Silver dial',
        'sku' => 'TEST-SHOP-SILVER',
        'price' => 1200,
        'stock_quantity' => 2,
        'is_active' => true,
        'is_default' => true,
    ]);
    $blue = $product->variants()->create([
        'name' => 'Blue dial',
        'sku' => 'TEST-SHOP-BLUE',
        'price' => 1250,
        'stock_quantity' => 2,
        'is_active' => true,
        'is_default' => false,
    ]);
    $blue->specification()->create([
        'overrides' => ['dial_color' => 'Midnight blue'],
    ]);

    Livewire::test(ShopShow::class, ['product' => $product])
        ->assertSet('selectedVariantId', $silver->id)
        ->assertSee('Silver')
        ->call('selectVariant', $blue->id)
        ->assertSet('selectedVariantId', $blue->id)
        ->assertSee('Midnight blue');
});
