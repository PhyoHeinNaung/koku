<?php

namespace App\Livewire\Customer\Checkout;

use App\Models\Order;
use Livewire\Attributes\Url;
use Livewire\Component;

class Confirmation extends Component
{
    #[Url]
    public string $payment_intent = '';

    public ?Order $order = null;

    public function mount(): void
    {
        if ($this->payment_intent) {
            $this->order = Order::with('items')
                ->where('stripe_payment_intent_id', $this->payment_intent)
                ->first();
        }
    }

    public function render()
    {
        return view('livewire.customer.checkout.confirmation')
            ->layout('layouts.app', ['overlay' => false]);
    }
}
