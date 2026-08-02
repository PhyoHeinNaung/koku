<?php

namespace App\Livewire\Customer\Addresses;

use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Form extends Component
{
    public ?Address $address = null;

    public string $label = '';

    public string $full_name = '';

    public string $phone = '';

    public string $country = 'Myanmar';

    public string $state_region = '';

    public string $city = '';

    public string $district_area = '';

    public string $postal_code = '';

    public string $address_line1 = '';

    public string $address_line2 = '';

    public bool $is_default = false;

    public function mount(?Address $address = null): void
    {
        if ($address?->exists) {
            abort_unless($address->user_id === Auth::id(), 403);

            $this->address = $address;
            $this->label = (string) $address->label;
            $this->full_name = $address->full_name;
            $this->phone = $address->phone;
            $this->country = $address->country;
            $this->state_region = (string) $address->state_region;
            $this->city = $address->city;
            $this->district_area = (string) $address->district_area;
            $this->postal_code = (string) $address->postal_code;
            $this->address_line1 = $address->address_line1;
            $this->address_line2 = (string) $address->address_line2;
            $this->is_default = $address->is_default;
        } else {
            $this->is_default = ! Address::where('user_id', Auth::id())->exists();
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'label' => ['nullable', 'string', 'max:50'],
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
            'state_region' => ['nullable', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'district_area' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'address_line1' => ['required', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'is_default' => ['boolean'],
        ]);

        $validated['user_id'] = Auth::id();
        $validated['label'] = $validated['label'] ?: null;
        $validated['state_region'] = $validated['state_region'] ?: null;
        $validated['district_area'] = $validated['district_area'] ?: null;
        $validated['postal_code'] = $validated['postal_code'] ?: null;
        $validated['address_line2'] = $validated['address_line2'] ?: null;

        if ($validated['is_default']) {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        if ($this->address) {
            $this->address->update($validated);
            session()->flash('success', 'Address updated.');
        } else {
            Address::create($validated);
            session()->flash('success', 'Address saved.');
        }

        $this->redirectRoute('addresses.index');
    }

    public function render()
    {
        return view('livewire.customer.addresses.form')->layout('layouts.app', ['overlay' => false]);
    }
}
