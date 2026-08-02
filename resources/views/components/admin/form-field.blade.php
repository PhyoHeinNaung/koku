@props([
    'label',
    'name',
    'required' => false,
])

<fieldset {{ $attributes->class('fieldset min-w-0 gap-1.5') }}>
    <label for="{{ $name }}" class="text-[11px] font-semibold text-base-content/70">
        {{ $label }}
        @if ($required)
            <span class="text-error" aria-hidden="true">*</span>
        @endif
    </label>

    {{ $slot }}

    @error($name)
        <p class="flex items-center gap-1.5 text-[10px] font-medium text-error">
            <span class="size-1 rounded-full bg-error"></span>
            {{ $message }}
        </p>
    @enderror
</fieldset>
