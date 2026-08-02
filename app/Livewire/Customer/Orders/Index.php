<?php

namespace App\Livewire\Customer\Orders;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $orders = Auth::user()->orders()->latest()->paginate(10);

        return view('livewire.customer.orders.index', ['orders' => $orders])
            ->layout('layouts.app', ['overlay' => false]);
    }
}
