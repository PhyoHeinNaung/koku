<?php

namespace App\Livewire\Customer\Addresses;

use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public function setDefault(Address $address): void
    {
        $this->authorizeOwnership($address);

        Address::where('user_id', Auth::id())->update(['is_default' => false]);
        $address->update(['is_default' => true]);
    }

    public function deleteAddress(Address $address): void
    {
        $this->authorizeOwnership($address);

        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            Address::where('user_id', Auth::id())->first()?->update(['is_default' => true]);
        }

        session()->flash('success', 'Address removed.');
    }

    protected function authorizeOwnership(Address $address): void
    {
        abort_unless($address->user_id === Auth::id(), 403);
    }

    public function render()
    {
        $addresses = Address::where('user_id', Auth::id())
            ->orderByDesc('is_default')
            ->latest()
            ->get();

        return view('livewire.customer.addresses.index', ['addresses' => $addresses])
            ->layout('layouts.app', ['overlay' => false]);
    }
}
