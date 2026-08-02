<?php

namespace App\Livewire\Admin\Settings;

use App\Models\StoreSetting;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Index extends Component
{
    public string $storeName = '';

    public string $legalName = '';

    public string $supportEmail = '';

    public string $supportPhone = '';

    public string $businessAddress = '';

    public string $defaultCountry = 'Myanmar';

    public string $orderPrefix = 'TCK';

    public int $lowStockThreshold = 5;

    public bool $insuranceEnabled = true;

    public string $insuranceRate = '2';

    public bool $guestCheckoutEnabled = true;

    public function mount(): void
    {
        $this->loadSettings();
    }

    public function save(): void
    {
        $this->orderPrefix = strtoupper(trim($this->orderPrefix));

        $validated = $this->validate([
            'storeName' => ['required', 'string', 'max:100'],
            'legalName' => ['nullable', 'string', 'max:150'],
            'supportEmail' => ['nullable', 'email', 'max:255'],
            'supportPhone' => ['nullable', 'string', 'max:40'],
            'businessAddress' => ['nullable', 'string', 'max:1000'],
            'defaultCountry' => ['required', 'string', 'max:100'],
            'orderPrefix' => ['required', 'alpha_num', 'min:2', 'max:8'],
            'lowStockThreshold' => ['required', 'integer', 'min:1', 'max:9999'],
            'insuranceEnabled' => ['boolean'],
            'insuranceRate' => [
                Rule::requiredIf($this->insuranceEnabled),
                'nullable',
                'numeric',
                'min:0.1',
                'max:25',
            ],
            'guestCheckoutEnabled' => ['boolean'],
        ]);

        StoreSetting::query()->updateOrCreate(
            ['id' => StoreSetting::query()->value('id') ?? 1],
            [
                'store_name' => trim($validated['storeName']),
                'legal_name' => $this->blankToNull($validated['legalName']),
                'support_email' => $this->blankToNull($validated['supportEmail']),
                'support_phone' => $this->blankToNull($validated['supportPhone']),
                'business_address' => $this->blankToNull($validated['businessAddress']),
                'default_country' => trim($validated['defaultCountry']),
                'order_prefix' => $validated['orderPrefix'],
                'low_stock_threshold' => $validated['lowStockThreshold'],
                'insurance_enabled' => $validated['insuranceEnabled'],
                'insurance_rate' => $validated['insuranceEnabled']
                    ? round(((float) $validated['insuranceRate']) / 100, 4)
                    : 0,
                'guest_checkout_enabled' => $validated['guestCheckoutEnabled'],
            ]
        );

        $this->loadSettings();
        $this->dispatch('admin-notify', type: 'success', message: 'Store settings saved.');
    }

    public function discardChanges(): void
    {
        $this->resetValidation();
        $this->loadSettings();
    }

    private function loadSettings(): void
    {
        $settings = StoreSetting::current();

        $this->storeName = $settings->store_name;
        $this->legalName = (string) $settings->legal_name;
        $this->supportEmail = (string) $settings->support_email;
        $this->supportPhone = (string) $settings->support_phone;
        $this->businessAddress = (string) $settings->business_address;
        $this->defaultCountry = $settings->default_country;
        $this->orderPrefix = $settings->order_prefix;
        $this->lowStockThreshold = $settings->low_stock_threshold;
        $this->insuranceEnabled = $settings->insurance_enabled;
        $this->insuranceRate = rtrim(rtrim(number_format((float) $settings->insurance_rate * 100, 2, '.', ''), '0'), '.');
        $this->guestCheckoutEnabled = $settings->guest_checkout_enabled;
    }

    private function blankToNull(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    public function render()
    {
        return view('livewire.admin.settings.index')
            ->layout('layouts.admin');
    }
}
