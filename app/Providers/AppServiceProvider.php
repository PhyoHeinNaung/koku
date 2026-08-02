<?php

namespace App\Providers;

use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event; // new line for status "active" after email verification
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
        Event::listen(Verified::class, function ($event) {

            $user = $event->user;

            if ($user->status === 'pending') {

                $user->status = 'active';
                $user->save();
            }
        });
    }
}
