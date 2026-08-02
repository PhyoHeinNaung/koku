<?php

namespace App\Livewire\Admin\Brands;

use App\Models\Brand;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public ?Brand $brand = null;

    public string $name = '';

    public string $tier = '';

    public ?string $description = '';

    public bool $is_active = true;

    #[Validate('nullable|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:2048')]
    public $logo = null;

    public ?string $existingLogo = null;

    public function mount(?Brand $brand = null): void
    {
        if ($brand?->exists) {
            $this->brand = $brand;
            $this->name = $brand->name;
            $this->tier = $brand->tier;
            $this->description = $brand->description;
            $this->is_active = $brand->is_active;
            $this->existingLogo = $brand->logo;
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('brands', 'name')->ignore($this->brand?->id)],
            'tier' => ['required', Rule::in(['luxury', 'premium', 'everyday', 'smart_sport'])],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $validated['slug'] = str($validated['name'])->slug();

        if ($this->logo) {
            $validated['logo'] = $this->logo->store('brands', 'public');
        }

        if ($this->brand) {
            $this->brand->update($validated);
            session()->flash('success', "\"{$validated['name']}\" was updated.");
        } else {
            Brand::create($validated);
            session()->flash('success', "\"{$validated['name']}\" was created.");
        }

        $this->redirectRoute('admin.brands.index');
    }

    public function render()
    {
        return view('livewire.admin.brands.form')->layout('layouts.admin');
    }
}
