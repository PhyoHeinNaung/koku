<?php

namespace App\Livewire\Customer\Cart;

use App\Livewire\Customer\Concerns\ManagesCart;
use Livewire\Attributes\On;
use Livewire\Component;

class Drawer extends Component
{
    use ManagesCart;

    public bool $open = false;

    #[On('cart-updated')]
    public function handleCartUpdated($open = false): void
    {
        if ($open) {
            $this->open = true;
        }
    }

    public function render()
    {
        $cart = $this->currentCart();
        $items = $cart ? $cart->items()->with('variant.product', 'variant.images')->get() : collect();

        return view('livewire.customer.cart.drawer', [
            'items' => $items,
            'count' => $items->sum('quantity'),
            'subtotal' => $items->sum(fn ($i) => $i->quantity * $i->unit_price),
        ]);
    }
}
