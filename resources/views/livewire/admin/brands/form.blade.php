<div class="mx-auto w-full max-w-[1500px] space-y-5" x-data>
    <header class="flex flex-col gap-4 rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] p-4 shadow-admin-panel sm:flex-row sm:items-center sm:justify-between sm:px-5">
        <div class="flex min-w-0 items-center gap-3">
            <a href="{{ route('admin.brands.index') }}"
                class="btn btn-square btn-sm shrink-0 rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] shadow-admin-control hover:bg-[var(--admin-surface-soft)]"
                aria-label="Back to brands">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6" />
                </svg>
            </a>
            <div class="min-w-0">
                <p class="text-[10px] font-medium uppercase tracking-[0.12em] text-base-content/40">Catalog · Brands</p>
                <h1 class="mt-0.5 truncate text-lg font-semibold tracking-tight sm:text-xl">
                    {{ $brand ? 'Edit brand' : 'New brand' }}
                </h1>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('admin.brands.index') }}" class="btn btn-ghost btn-sm h-10 min-h-10 rounded-xl px-4">Cancel</a>
            <button type="submit" form="brand-form" wire:loading.attr="disabled" wire:target="save"
                class="btn btn-primary btn-sm h-10 min-h-10 min-w-32 rounded-xl px-5 shadow-[0_10px_24px_rgba(255,122,0,.2)]">
                <span wire:loading wire:target="save" class="loading loading-spinner loading-xs"></span>
                <span wire:loading.remove wire:target="save">{{ $brand ? 'Save changes' : 'Create brand' }}</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
        </div>
    </header>

    <form id="brand-form" wire:submit="save"
        class="grid items-start gap-5 lg:grid-cols-[minmax(0,1fr)_22rem]">
        <x-admin.form-section title="Brand information" description="Identity and market positioning">
            <x-slot:icon>
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M12 3.5 20 8v8l-8 4.5L4 16V8l8-4.5Z" />
                </svg>
            </x-slot:icon>

            <div class="space-y-5">
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-admin.form-field label="Brand name" name="brand-name" required>
                        <input id="brand-name" type="text" wire:model.live.debounce.300ms="name"
                            class="input input-bordered h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none"
                            autocomplete="off">
                        @error('name') <p class="text-xs text-error">{{ $message }}</p> @enderror
                    </x-admin.form-field>

                    <x-admin.form-field label="Market tier" name="brand-tier" required>
                        <select id="brand-tier" wire:model.live="tier"
                            class="select select-bordered h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none">
                            <option value="">Select tier</option>
                            <option value="luxury">Luxury</option>
                            <option value="premium">Premium</option>
                            <option value="everyday">Everyday</option>
                            <option value="smart_sport">Smart / Sport</option>
                        </select>
                        @error('tier') <p class="text-xs text-error">{{ $message }}</p> @enderror
                    </x-admin.form-field>
                </div>

                <x-admin.form-field label="Description" name="brand-description">
                    <textarea id="brand-description" wire:model="description" rows="10"
                        class="textarea textarea-bordered w-full resize-y rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] leading-6 shadow-admin-control focus:border-accent focus:outline-none"></textarea>
                    @error('description') <p class="text-xs text-error">{{ $message }}</p> @enderror
                </x-admin.form-field>
            </div>
        </x-admin.form-section>

        <aside class="space-y-5 lg:sticky lg:top-20">
            <x-admin.form-section title="Brand mark" description="Square or horizontal logo">
                <div class="grid aspect-[16/10] place-items-center overflow-hidden rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] p-4 shadow-inner">
                    @if ($logo)
                        <img src="{{ $logo->temporaryUrl() }}" alt="New brand logo preview"
                            class="max-h-28 w-full object-contain">
                    @elseif ($existingLogo)
                        <img src="{{ Storage::url($existingLogo) }}" alt="Current brand logo"
                            class="max-h-28 w-full object-contain">
                    @else
                        <div class="grid justify-items-center text-base-content/30">
                            <svg class="size-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M4 5h16v14H4V5Zm3 10 3-3 2.5 2.5L15 12l3 3M8.5 9h.01" />
                            </svg>
                            <span class="mt-2 text-[11px]">No logo</span>
                        </div>
                    @endif
                </div>

                <label class="btn btn-sm mt-3 h-10 min-h-10 w-full cursor-pointer rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] shadow-admin-control hover:border-accent/35 hover:bg-accent/10">
                    <span wire:loading.remove wire:target="logo">{{ $existingLogo || $logo ? 'Replace logo' : 'Upload logo' }}</span>
                    <span wire:loading wire:target="logo" class="loading loading-spinner loading-xs"></span>
                    <span wire:loading wire:target="logo">Uploading...</span>
                    <input type="file" wire:model="logo" accept="image/*" class="hidden">
                </label>
                @error('logo') <p class="mt-2 text-xs text-error">{{ $message }}</p> @enderror
            </x-admin.form-section>

            <x-admin.form-section title="Visibility" description="Catalog availability">
                <x-admin.switch-row label="Active brand" description="Products can appear in the catalog">
                    <input type="checkbox" wire:model="is_active" class="toggle toggle-primary toggle-sm">
                </x-admin.switch-row>
            </x-admin.form-section>

            <section class="relative overflow-hidden rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] p-5 shadow-admin-panel">
                <span class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/15 to-transparent"></span>
                <p class="text-[10px] font-semibold uppercase tracking-[0.12em] text-base-content/40">Preview</p>
                <div class="mt-4 flex items-center gap-3">
                    <span class="grid size-11 shrink-0 place-items-center rounded-xl border border-accent/20 bg-accent/10 text-accent shadow-admin-control">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path d="M12 3.5 20 8v8l-8 4.5L4 16V8l8-4.5Z" />
                        </svg>
                    </span>
                    <span class="min-w-0">
                        <strong class="block truncate text-sm" x-text="$wire.name || 'Brand name'"></strong>
                        <small class="mt-1 block capitalize text-[10px] text-base-content/40"
                            x-text="($wire.tier || 'market tier').replace('_', ' / ')"></small>
                    </span>
                </div>
            </section>
        </aside>
    </form>
</div>
