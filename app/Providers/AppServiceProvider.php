<?php

namespace App\Providers;

use App\Models\Brand;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event; // new line for status "active" after email verification
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider; // new line

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('customer.partials.navigation', function ($view) {
            $sellableProducts = fn ($query) => $query
                ->where('is_active', true)
                ->whereHas('variants', fn ($variants) => $variants->where('is_active', true));

            $featuredBrands = Brand::query()
                ->where('is_active', true)
                ->whereHas('products', $sellableProducts)
                ->withCount([
                    'products as featured_products_count' => fn ($query) => $sellableProducts($query)->where('is_featured', true),
                    'products as sellable_products_count' => $sellableProducts,
                ])
                ->orderByDesc('featured_products_count')
                ->orderByDesc('sellable_products_count')
                ->orderBy('name')
                ->limit(8)
                ->get(['id', 'name', 'slug', 'logo']);

            $view->with(compact('featuredBrands'));
        });

        Event::listen(Verified::class, function ($event) {

            $user = $event->user;

            if ($user->status === 'pending') {

                $user->status = 'active';
                $user->save();
            }
        });
    }
}
