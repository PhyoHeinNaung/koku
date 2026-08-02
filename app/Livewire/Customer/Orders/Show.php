<?php

namespace App\Livewire\Customer\Orders;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        abort_unless($order->user_id === Auth::id(), 403);

        $this->order = $order->load('items', 'payments');
    }

    public function render()
    {
        return view('livewire.customer.orders.show')
            ->layout('layouts.app', ['overlay' => false]);
    }
}
