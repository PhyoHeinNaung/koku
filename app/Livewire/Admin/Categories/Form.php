<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Form extends Component
{
    public ?Category $category = null;

    public string $name = '';

    public ?string $description = '';

    public bool $is_active = true;

    public function mount(?Category $category = null): void
    {
        if ($category?->exists) {
            $this->category = $category;
            $this->name = $category->name;
            $this->description = $category->description;
            $this->is_active = $category->is_active;
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($this->category?->id)],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $validated['slug'] = str($validated['name'])->slug();

        if ($this->category) {
            $this->category->update($validated);
            session()->flash('success', "\"{$validated['name']}\" was updated.");
        } else {
            Category::create($validated);
            session()->flash('success', "\"{$validated['name']}\" was created.");
        }

        $this->redirectRoute('admin.categories.index');
    }

    public function render()
    {
        return view('livewire.admin.categories.form')->layout('layouts.admin');
    }
}
