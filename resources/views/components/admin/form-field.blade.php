@props([
    'label',
    'name',
    'required' => false,
])

<fieldset {{ $attributes->class('admin-form-field fieldset min-w-0 gap-1.5') }}>
    <label for="{{ $name }}" class="text-[11px] font-medium text-base-content/65">
        {{ $label }}
        @if ($required)
            <span class="ml-0.5 text-error" aria-hidden="true">*</span>
        @endif
    </label>

    {{ $slot }}

    @error($name)
        <p class="flex items-center gap-1.5 text-[11px] font-medium text-error" role="alert">
            <span class="size-1 rounded-full bg-error"></span>
            {{ $message }}
        </p>
    @enderror
</fieldset>
