<?php

namespace App\Livewire\Admin\Coupons;

use App\Models\Coupon;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Form extends Component
{
    public ?Coupon $coupon = null;

    public string $code = '';

    public ?string $description = '';

    public string $discount_type = 'fixed';

    public string $discount_value = '';

    public string $minimum_order_amount = '';

    public string $start_date = '';

    public string $end_date = '';

    public string $usage_limit = '';

    public bool $is_active = true;

    public function mount(?Coupon $coupon = null): void
    {
        if ($coupon?->exists) {
            $this->coupon = $coupon;
            $this->code = $coupon->code;
            $this->description = $coupon->description;
            $this->discount_type = $coupon->discount_type;
            $this->discount_value = (string) $coupon->discount_value;
            $this->minimum_order_amount = (string) $coupon->minimum_order_amount;
            $this->start_date = $coupon->start_date->format('Y-m-d');
            $this->end_date = $coupon->end_date->format('Y-m-d');
            $this->usage_limit = (string) $coupon->usage_limit;
            $this->is_active = $coupon->is_active;
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('coupons', 'code')->ignore($this->coupon?->id)],
            'description' => ['nullable', 'string'],
            'discount_type' => ['required', Rule::in(['fixed', 'percentage'])],
            'discount_value' => ['required', 'numeric', 'min:0'],
            'minimum_order_amount' => ['nullable', 'numeric', 'min:0'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['boolean'],
        ]);

        if ($validated['discount_type'] === 'percentage' && $validated['discount_value'] > 100) {
            $this->addError('discount_value', 'Percentage discount cannot exceed 100.');

            return;
        }

        $validated['code'] = strtoupper($validated['code']);
        $validated['minimum_order_amount'] = $validated['minimum_order_amount'] ?: null;
        $validated['usage_limit'] = $validated['usage_limit'] ?: null;

        if ($this->coupon) {
            $this->coupon->update($validated);
            session()->flash('success', "\"{$validated['code']}\" was updated.");
        } else {
            Coupon::create($validated);
            session()->flash('success', "\"{$validated['code']}\" was created.");
        }

        $this->redirectRoute('admin.coupons.index');
    }

    public function render()
    {
        return view('livewire.admin.coupons.form')->layout('layouts.admin');
    }
}
