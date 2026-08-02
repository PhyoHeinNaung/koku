<?php

namespace App\Services;

use Stripe\Event;
use Stripe\PaymentIntent;
use Stripe\StripeClient;
use Stripe\Webhook;

class StripeService
{
    protected StripeClient $client;

    public function __construct()
    {
        $this->client = new StripeClient(config('services.stripe.secret'));
    }

    /**
     * Create a PaymentIntent for the given total (in dollars, e.g. 1200.00).
     * Stripe expects amounts in the smallest currency unit (cents for USD),
     * so we convert here — callers always work in plain dollar amounts.
     */
    public function createPaymentIntent(float $amount, array $metadata = []): PaymentIntent
    {
        return $this->client->paymentIntents->create([
            'amount' => (int) round($amount * 100),
            'currency' => 'usd',
            'automatic_payment_methods' => ['enabled' => true],
            'metadata' => $metadata,
        ]);
    }

    public function retrievePaymentIntent(string $paymentIntentId): PaymentIntent
    {
        return $this->client->paymentIntents->retrieve($paymentIntentId);
    }

    public function updatePaymentIntentAmount(string $paymentIntentId, float $amount): PaymentIntent
    {
        return $this->client->paymentIntents->update($paymentIntentId, [
            'amount' => (int) round($amount * 100),
        ]);
    }

    public function constructWebhookEvent(string $payload, string $signature, string $webhookSecret): Event
    {
        return Webhook::constructEvent($payload, $signature, $webhookSecret);
    }
}
