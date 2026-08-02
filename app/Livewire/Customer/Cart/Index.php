<?php

namespace App\Livewire\Customer\Cart;

use App\Livewire\Customer\Concerns\ManagesCart;
use Livewire\Component;

class Index extends Component
{
    use ManagesCart;

    public function render()
    {
        $cart = $this->currentCart();
        $items = $cart ? $cart->items()->with('variant.product', 'variant.images')->get() : collect();

        return view('livewire.customer.cart.index', [
            'items' => $items,
            'subtotal' => $items->sum(fn ($i) => $i->quantity * $i->unit_price),
        ])->layout('layouts.app', ['overlay' => false]);
    }
}
