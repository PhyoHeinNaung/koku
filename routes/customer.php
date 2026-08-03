<?php

use App\Http\Controllers\Customer\ProfileController;
use App\Livewire\Customer\Addresses\Form as AddressForm;
use App\Livewire\Customer\Addresses\Index as AddressIndex;
use App\Livewire\Customer\Cart\Index as CartIndex;
use App\Livewire\Customer\Checkout\Confirmation as CheckoutConfirmation;
use App\Livewire\Customer\Checkout\Index as CheckoutIndex;
use App\Livewire\Customer\Orders\Index as OrdersIndex;
use App\Livewire\Customer\Orders\Show as OrdersShow;
use App\Livewire\Customer\Shop\Index as ShopIndex;
use App\Livewire\Customer\Shop\Show as ShopShow;
use App\Livewire\Customer\Wishlist\Index as WishlistIndex;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $featuredProducts = Product::query()
        ->with(['brand', 'category', 'variants.images'])
        ->where('is_active', true)
        ->whereHas('variants', fn ($query) => $query->where('is_active', true))
        ->orderByDesc('is_featured')
        ->latest()
        ->take(4)
        ->get();

    return view('customer.home', compact('featuredProducts'));
})->name('home');

Route::get('/shop', ShopIndex::class)->name('shop.index');
Route::get('/products/{product:slug}', ShopShow::class)->name('shop.product');
Route::get('/wishlist', WishlistIndex::class)->name('wishlist.index');
Route::get('/cart', CartIndex::class)->name('cart.index');
Route::get('/checkout', CheckoutIndex::class)->name('checkout.index');
Route::get('/checkout/success', CheckoutConfirmation::class)->name('checkout.success');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/addresses', AddressIndex::class)->name('addresses.index');
    Route::get('/addresses/create', AddressForm::class)->name('addresses.create');
    Route::get('/addresses/{address}/edit', AddressForm::class)->name('addresses.edit');

    Route::get('/orders', OrdersIndex::class)->name('orders.index');
    Route::get('/orders/{order}', OrdersShow::class)->name('orders.show');
});
