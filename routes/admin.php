<?php

use App\Livewire\Admin\Brands\Form as BrandForm;
use App\Livewire\Admin\Brands\Index as BrandIndex;
use App\Livewire\Admin\Categories\Form as CategoryForm;
use App\Livewire\Admin\Categories\Index as CategoryIndex;
use App\Livewire\Admin\Coupons\Form as CouponForm;
use App\Livewire\Admin\Coupons\Index as CouponIndex;
use App\Livewire\Admin\Customers\Index as CustomerIndex;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Orders\Index as OrderIndex;
use App\Livewire\Admin\Products\Form as ProductForm;
use App\Livewire\Admin\Products\Index as ProductIndex;
use App\Livewire\Admin\Profile as AdminProfile;
use App\Livewire\Admin\Reports\Index as ReportIndex;
use App\Livewire\Admin\Settings\Index as SettingIndex;
use App\Livewire\Admin\Shipping\Index as ShippingIndex;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('profile', AdminProfile::class)->name('profile');

    Route::get('brands', BrandIndex::class)->name('brands.index');
    Route::get('brands/create', BrandForm::class)->name('brands.create');
    Route::get('brands/{brand}/edit', BrandForm::class)->name('brands.edit');

    Route::get('categories', CategoryIndex::class)->name('categories.index');
    Route::get('categories/create', CategoryForm::class)->name('categories.create');
    Route::get('categories/{category}/edit', CategoryForm::class)->name('categories.edit');

    Route::get('products', ProductIndex::class)->name('products.index');
    Route::get('products/create', ProductForm::class)->name('products.create');
    Route::get('products/{product}/edit', ProductForm::class)->name('products.edit');

    Route::get('orders', OrderIndex::class)->name('orders.index');

    Route::get('coupons', CouponIndex::class)->name('coupons.index');
    Route::get('coupons/create', CouponForm::class)->name('coupons.create');
    Route::get('coupons/{coupon}/edit', CouponForm::class)->name('coupons.edit');

    Route::get('shipping', ShippingIndex::class)->name('shipping.index');
    Route::get('customers', CustomerIndex::class)->name('customers.index');
    Route::get('reports', ReportIndex::class)->name('reports.index');
    Route::get('settings', SettingIndex::class)->name('settings.index');
});
