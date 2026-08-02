<div class="mx-auto w-full max-w-[1500px] space-y-5" x-data>
    <header class="flex flex-col gap-4 rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] p-4 shadow-admin-panel sm:flex-row sm:items-center sm:justify-between sm:px-5">
        <div class="flex min-w-0 items-center gap-3">
            <a href="{{ route('admin.categories.index') }}"
                class="btn btn-square btn-sm shrink-0 rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] shadow-admin-control hover:bg-[var(--admin-surface-soft)]"
                aria-label="Back to categories">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6" />
                </svg>
            </a>
            <div class="min-w-0">
                <p class="text-[10px] font-medium uppercase tracking-[0.12em] text-base-content/40">Catalog · Categories</p>
                <h1 class="mt-0.5 truncate text-lg font-semibold tracking-tight sm:text-xl">
                    {{ $category ? 'Edit category' : 'New category' }}
                </h1>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost btn-sm h-10 min-h-10 rounded-xl px-4">Cancel</a>
            <button type="submit" form="category-form" wire:loading.attr="disabled" wire:target="save"
                class="btn btn-primary btn-sm h-10 min-h-10 min-w-32 rounded-xl px-5 shadow-[0_10px_24px_rgba(255,122,0,.2)]">
                <span wire:loading wire:target="save" class="loading loading-spinner loading-xs"></span>
                <span wire:loading.remove wire:target="save">{{ $category ? 'Save changes' : 'Create category' }}</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
        </div>
    </header>

    <form id="category-form" wire:submit="save"
        class="grid items-start gap-5 lg:grid-cols-[minmax(0,1fr)_22rem]">
        <x-admin.form-section title="Category information" description="Core catalog identity">
            <x-slot:icon>
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M5 4h5v5H5V4Zm9 0h5v5h-5V4ZM5 14h5v5H5v-5Zm9 0h5v5h-5v-5Z" />
                </svg>
            </x-slot:icon>

            <div class="space-y-5">
                <x-admin.form-field label="Category name" name="category-name" required>
                    <input id="category-name" type="text" wire:model.live.debounce.300ms="name"
                        class="input input-bordered h-11 w-full rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] shadow-admin-control focus:border-accent focus:outline-none"
                        autocomplete="off">
                    @error('name') <p class="text-xs text-error">{{ $message }}</p> @enderror
                </x-admin.form-field>

                <x-admin.form-field label="Description" name="category-description">
                    <textarea id="category-description" wire:model="description" rows="10"
                        class="textarea textarea-bordered w-full resize-y rounded-xl border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] leading-6 shadow-admin-control focus:border-accent focus:outline-none"></textarea>
                    @error('description') <p class="text-xs text-error">{{ $message }}</p> @enderror
                </x-admin.form-field>
            </div>
        </x-admin.form-section>

        <aside class="space-y-5 lg:sticky lg:top-20">
            <x-admin.form-section title="Visibility" description="Storefront availability">
                <x-admin.switch-row label="Active category" description="Available in customer navigation">
                    <input type="checkbox" wire:model="is_active" class="toggle toggle-primary toggle-sm">
                </x-admin.switch-row>
            </x-admin.form-section>

            <section class="relative overflow-hidden rounded-2xl border border-[var(--admin-border)] bg-[var(--admin-surface)] p-5 shadow-admin-panel">
                <span class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/15 to-transparent"></span>
                <p class="text-[10px] font-semibold uppercase tracking-[0.12em] text-base-content/40">Preview</p>
                <div class="mt-4 flex items-center gap-3">
                    <span class="grid size-11 shrink-0 place-items-center rounded-xl border border-accent/20 bg-accent/10 text-accent shadow-admin-control">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path d="M5 4h5v5H5V4Zm9 0h5v5h-5V4ZM5 14h5v5H5v-5Zm9 0h5v5h-5v-5Z" />
                        </svg>
                    </span>
                    <span class="min-w-0">
                        <strong class="block truncate text-sm" x-text="$wire.name || 'Category name'"></strong>
                        <small class="mt-1 block truncate font-mono text-[10px] text-base-content/40"
                            x-text="($wire.name || 'category-name').toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '')"></small>
                    </span>
                </div>
            </section>
        </aside>
    </form>
</div>
