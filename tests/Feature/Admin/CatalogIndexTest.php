<?php

use App\Livewire\Admin\Brands\Index as BrandIndex;
use App\Livewire\Admin\Categories\Index as CategoryIndex;
use App\Livewire\Admin\Products\Index as ProductIndex;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Livewire;

function catalogBrand(string $name, array $overrides = []): Brand
{
    return Brand::create(array_merge([
        'name' => $name,
        'slug' => str($name)->slug(),
        'tier' => 'premium',
        'is_active' => true,
    ], $overrides));
}

function catalogCategory(string $name, array $overrides = []): Category
{
    return Category::create(array_merge([
        'name' => $name,
        'slug' => str($name)->slug(),
        'is_active' => true,
    ], $overrides));
}

function catalogProduct(
    string $name,
    Brand $brand,
    Category $category,
    array $overrides = []
): Product {
    return Product::create(array_merge([
        'brand_id' => $brand->id,
        'category_id' => $category->id,
        'name' => $name,
        'slug' => str($name)->slug(),
        'description' => "Catalog product {$name}.",
        'gender' => 'unisex',
        'watch_type' => 'traditional',
        'movement' => 'automatic',
        'is_active' => false,
        'is_featured' => false,
    ], $overrides));
}

test('categories can be searched filtered and sorted by product usage', function () {
    $brand = catalogBrand('Category Test Brand');
    $used = catalogCategory('Dress Watches');
    $unused = catalogCategory('Dive Watches');
    $inactive = catalogCategory('Archived Watches', ['is_active' => false]);

    catalogProduct('Dress Reference One', $brand, $used);
    catalogProduct('Dress Reference Two', $brand, $used);

    Livewire::test(CategoryIndex::class)
        ->set('status', 'inactive')
        ->assertSee('Archived Watches')
        ->assertDontSee('Dress Watches')
        ->call('resetFilters')
        ->set('search', 'Dive')
        ->assertSee('Dive Watches')
        ->assertDontSee('Dress Watches')
        ->call('clearAll')
        ->set('sort', 'products_desc')
        ->assertSeeInOrder(['Dress Watches', 'Dive Watches']);

    expect($inactive->is_active)->toBeFalse()
        ->and($unused->products()->count())->toBe(0);
});

test('brands can be filtered by tier and status and sorted by product usage', function () {
    $category = catalogCategory('Brand Test Category');
    $luxury = catalogBrand('Atelier Time', ['tier' => 'luxury']);
    $everyday = catalogBrand('Daily Time', ['tier' => 'everyday']);
    $inactive = catalogBrand('Retired Time', ['tier' => 'luxury', 'is_active' => false]);

    catalogProduct('Atelier One', $luxury, $category);
    catalogProduct('Atelier Two', $luxury, $category);

    Livewire::test(BrandIndex::class)
        ->set('tier', 'everyday')
        ->assertSee('Daily Time')
        ->assertDontSee('Atelier Time')
        ->call('resetFilters')
        ->set('status', 'inactive')
        ->assertSee('Retired Time')
        ->assertDontSee('Daily Time')
        ->call('resetFilters')
        ->set('sort', 'products_desc')
        ->assertSeeInOrder(['Atelier Time', 'Daily Time']);

    expect($inactive->is_active)->toBeFalse()
        ->and($everyday->products()->count())->toBe(0);
});

test('products support lifecycle relationship and sku filters with inventory sorting', function () {
    $brand = catalogBrand('Product Test Brand');
    $otherBrand = catalogBrand('Other Product Brand');
    $category = catalogCategory('Product Test Category');

    $active = catalogProduct('Active Chronometer', $brand, $category, [
        'is_active' => true,
        'is_featured' => true,
    ]);
    $active->variants()->create([
        'name' => 'Steel',
        'sku' => 'ACTIVE-SKU-001',
        'price' => 1800,
        'stock_quantity' => 12,
        'is_active' => true,
        'is_default' => true,
    ]);

    $draft = catalogProduct('Draft Chronometer', $otherBrand, $category);
    $draft->variants()->create([
        'name' => 'Leather',
        'sku' => 'DRAFT-SKU-001',
        'price' => 1200,
        'stock_quantity' => 4,
        'is_active' => true,
        'is_default' => true,
    ]);

    catalogProduct('Incomplete Chronometer', $brand, $category);

    Livewire::test(ProductIndex::class)
        ->set('status', 'active')
        ->assertSee('Active Chronometer')
        ->assertDontSee('Draft Chronometer')
        ->call('resetFilters')
        ->set('status', 'incomplete')
        ->assertSee('Incomplete Chronometer')
        ->assertDontSee('Active Chronometer')
        ->call('resetFilters')
        ->set('search', 'DRAFT-SKU-001')
        ->assertSee('Draft Chronometer')
        ->assertDontSee('Active Chronometer')
        ->call('clearAll')
        ->set('brand', (string) $brand->id)
        ->assertSee('Active Chronometer')
        ->assertDontSee('Draft Chronometer')
        ->call('resetFilters')
        ->set('sort', 'stock_desc')
        ->assertSeeInOrder(['Active Chronometer', 'Draft Chronometer', 'Incomplete Chronometer']);
});

test('category and brand bulk actions protect resources that are in use', function () {
    $usedBrand = catalogBrand('Used Bulk Brand');
    $emptyBrand = catalogBrand('Empty Bulk Brand');
    $usedCategory = catalogCategory('Used Bulk Category');
    $emptyCategory = catalogCategory('Empty Bulk Category');

    catalogProduct('Bulk Protected Product', $usedBrand, $usedCategory);

    Livewire::test(CategoryIndex::class)
        ->set('selected', [$usedCategory->id, $emptyCategory->id])
        ->call('bulkDelete')
        ->assertSet('selected', [])
        ->assertDispatched('admin-notify');

    expect(Category::find($usedCategory->id))->not->toBeNull()
        ->and(Category::find($emptyCategory->id))->toBeNull();

    Livewire::test(BrandIndex::class)
        ->set('selected', [$usedBrand->id, $emptyBrand->id])
        ->call('bulkDelete')
        ->assertSet('selected', [])
        ->assertDispatched('admin-notify');

    expect(Brand::find($usedBrand->id))->not->toBeNull()
        ->and(Brand::find($emptyBrand->id))->toBeNull();
});

test('product bulk activation only publishes products with an active default variant', function () {
    $brand = catalogBrand('Bulk Product Brand');
    $category = catalogCategory('Bulk Product Category');
    $eligible = catalogProduct('Eligible Bulk Product', $brand, $category);
    $incomplete = catalogProduct('Incomplete Bulk Product', $brand, $category);

    $eligible->variants()->create([
        'name' => 'Default',
        'sku' => 'BULK-ELIGIBLE-001',
        'price' => 650,
        'stock_quantity' => 8,
        'is_active' => true,
        'is_default' => true,
    ]);

    Livewire::test(ProductIndex::class)
        ->call('togglePageSelection', [$eligible->id, $incomplete->id])
        ->assertSet('selected', [$eligible->id, $incomplete->id])
        ->call('bulkSetActive', true)
        ->assertSet('selected', [])
        ->assertDispatched('admin-notify');

    expect($eligible->fresh()->is_active)->toBeTrue()
        ->and($incomplete->fresh()->is_active)->toBeFalse();
});
