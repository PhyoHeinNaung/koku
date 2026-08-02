<?php

use App\Http\Controllers\StripeWebhookController;

require __DIR__.'/auth.php';
require __DIR__.'/customer.php';
require __DIR__.'/admin.php';

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');
