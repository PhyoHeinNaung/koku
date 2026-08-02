<?php

namespace App\Http\Controllers;

use App\Jobs\SendPostPaymentEmails;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Stripe\Exception\SignatureVerificationException;
use Stripe\PaymentIntent;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $stripe = app(StripeService::class);

        try {
            $event = $stripe->constructWebhookEvent(
                $request->getContent(),
                $request->header('Stripe-Signature'),
                config('services.stripe.webhook_secret')
            );
        } catch (SignatureVerificationException $e) {
            Log::warning('Stripe webhook signature verification failed.');

            return response('Invalid signature', 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $this->handlePaymentSucceeded($event->data->object);
        }

        return response('OK', 200);
    }

    protected function handlePaymentSucceeded(PaymentIntent $intent): void
    {
        $order = Order::where('stripe_payment_intent_id', $intent->id)->first();

        if (! $order) {
            Log::warning("Stripe webhook: no order found for payment intent {$intent->id}.");

            return;
        }

        if ($order->status !== 'pending') {
            // Already processed — Stripe may retry webhook delivery.
            return;
        }

        $isNewAccount = false;

        DB::transaction(function () use ($order, $intent) {
            foreach ($order->items as $item) {
                $variant = ProductVariant::lockForUpdate()->find($item->variant_id);

                if ($variant) {
                    $variant->decrement('stock_quantity', min($item->quantity, $variant->stock_quantity));
                }
            }

            $order->update(['status' => 'processing']);

            $billingDetails = $intent->charges->data[0]->billing_details ?? null;

            if ($billingDetails && $billingDetails->address) {
                $order->update([
                    'billing_full_name' => $billingDetails->name ?: $order->billing_full_name,
                    'billing_phone' => $billingDetails->phone ?: $order->billing_phone,
                    'billing_country' => $billingDetails->address->country ?: $order->billing_country,
                    'billing_state_region' => $billingDetails->address->state ?: $order->billing_state_region,
                    'billing_city' => $billingDetails->address->city ?: $order->billing_city,
                    'billing_postal_code' => $billingDetails->address->postal_code ?: $order->billing_postal_code,
                    'billing_address_line1' => $billingDetails->address->line1 ?: $order->billing_address_line1,
                    'billing_address_line2' => $billingDetails->address->line2 ?: $order->billing_address_line2,
                ]);
            }

            Payment::create([
                'order_id' => $order->id,
                'method' => 'card',
                'status' => 'paid',
                'transaction_id' => $intent->id,
                'amount' => $order->total,
                'paid_at' => now(),
            ]);

            if ($order->coupon_id) {
                Coupon::where('id', $order->coupon_id)->increment('used_count');
            }
        });

        $isNewAccount = $this->resolveOrCreateUser($order);

        SendPostPaymentEmails::dispatch($order->fresh(), $isNewAccount);
    }

    protected function resolveOrCreateUser(Order $order): bool
    {
        if ($order->user_id) {
            return false;
        }

        $existing = User::where('email', $order->email)->first();

        if ($existing) {
            $order->update(['user_id' => $existing->id]);

            return false;
        }

        $user = User::forceCreate([
            'email' => $order->email,
            'password' => Str::random(32),
            'role' => 'user',
            'status' => 'pending',
            'name' => $order->shipping_full_name,
            'phone' => $order->shipping_phone,
        ]);

        $order->update(['user_id' => $user->id]);

        return true;
    }
}
