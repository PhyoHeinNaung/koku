<div x-data>
    <div class="flex items-center justify-between gap-3">
        <div>
            <h4 class="text-sm font-semibold">Variant gallery</h4>
            <p class="mt-1 text-[10px] text-base-content/40">The cover image represents this variant across the catalog.</p>
        </div>
        @if ($images->isNotEmpty())
            <span class="rounded-lg border border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] px-2 py-1 text-[9px] font-semibold tabular-nums text-base-content/45">
                {{ $images->count() }} / 6
            </span>
        @endif
    </div>

    <label class="relative mt-4 flex min-h-32 cursor-pointer flex-col items-center justify-center rounded-2xl border border-dashed border-[var(--admin-border-strong)] bg-[var(--admin-surface-sunken)] px-5 py-5 text-center shadow-inner transition hover:border-accent/50 hover:bg-accent/[0.045] focus-within:border-accent focus-within:ring-2 focus-within:ring-accent/15">
        <input type="file" wire:model="newImages" multiple accept="image/jpeg,image/png,image/webp,image/avif,image/gif,image/svg+xml" class="absolute inset-0 z-10 cursor-pointer opacity-0">

        <span wire:loading.remove wire:target="newImages" class="grid size-10 place-items-center rounded-xl border border-accent/20 bg-accent/10 text-accent shadow-admin-control">
            <svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" d="M12 16V5m0 0L8 9m4-4 4 4M5 15v4h14v-4" />
            </svg>
        </span>
        <span wire:loading wire:target="newImages" class="loading loading-spinner loading-sm text-accent"></span>

        <span wire:loading.remove wire:target="newImages" class="mt-2 text-xs font-semibold">Upload images</span>
        <span wire:loading wire:target="newImages" class="mt-2 text-xs font-semibold">Uploading images...</span>
        <span class="mt-1 text-[11px] text-base-content/40">JPEG, PNG, WebP or AVIF · up to 6 files · 2 MB each</span>
    </label>

    @error('newImages') <p class="mt-2 text-xs text-error">{{ $message }}</p> @enderror
    @error('newImages.*') <p class="mt-2 text-xs text-error">{{ $message }}</p> @enderror

    @if ($images->isEmpty())
        <div class="mt-4 rounded-xl border border-dashed border-[var(--admin-border)] bg-[var(--admin-surface-sunken)] px-4 py-6 text-center">
            <p class="text-xs font-medium text-base-content/55">No images for this variant yet</p>
        </div>
    @else
        <div
            class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5"
            x-sort="$wire.reorderImage($item, $position)"
            wire:loading.class="opacity-60"
            wire:target="reorderImage"
        >
            @foreach ($images as $index => $image)
                <article
                    wire:key="image-{{ $image->id }}"
                    x-sort:item="{{ $image->id }}"
                    class="group overflow-hidden rounded-xl border border-[var(--admin-border)] bg-[var(--admin-surface-raised)] shadow-admin-control transition hover:-translate-y-0.5 hover:border-[var(--admin-border-strong)] hover:shadow-admin-panel"
                >
                    <div class="relative aspect-square overflow-hidden bg-[var(--admin-surface-sunken)]">
                        <img
                            src="{{ Storage::url($image->image_url) }}"
                            alt="{{ $image->alt_text ?: $variant->name . ' image ' . ($index + 1) }}"
                            class="h-full w-full object-cover"
                            loading="lazy"
                            decoding="async"
                        >

                        <div class="absolute inset-x-2 top-2 flex items-start justify-between gap-2">
                            @if ($image->is_primary)
                                <span class="badge badge-sm gap-1 border-accent/30 bg-accent text-accent-content shadow-[0_7px_16px_rgba(255,122,0,.22)]">
                                    <svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" />
                                    </svg>
                                    Cover
                                </span>
                            @else
                                <button type="button" wire:click="setPrimary({{ $image->id }})" wire:loading.attr="disabled" class="btn btn-circle btn-sm size-8 min-h-8 border border-white/15 bg-black/70 p-0 text-white/75 shadow-lg backdrop-blur hover:bg-accent hover:text-accent-content" aria-label="Use image {{ $index + 1 }} as cover" title="Use as cover image">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path stroke-linejoin="round" d="m12 3 2.7 5.5 6.1.9-4.4 4.3 1 6.1-5.4-2.9-5.4 2.9 1-6.1-4.4-4.3 6.1-.9L12 3Z" />
                                    </svg>
                                </button>
                            @endif

                            <div class="flex gap-1">
                                <button type="button" x-sort:handle class="btn btn-circle btn-sm size-8 min-h-8 cursor-grab border border-white/15 bg-black/70 p-0 text-white/75 shadow-lg backdrop-blur active:cursor-grabbing" aria-label="Drag image {{ $index + 1 }} to reorder" title="Drag to reorder">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <circle cx="9" cy="7" r="1.2" /><circle cx="15" cy="7" r="1.2" />
                                        <circle cx="9" cy="12" r="1.2" /><circle cx="15" cy="12" r="1.2" />
                                        <circle cx="9" cy="17" r="1.2" /><circle cx="15" cy="17" r="1.2" />
                                    </svg>
                                </button>
                                <button type="button" wire:click="deleteImage({{ $image->id }})" wire:confirm="Delete this image?" class="btn btn-circle btn-sm size-8 min-h-8 border border-white/15 bg-black/70 p-0 text-error opacity-0 shadow-lg backdrop-blur transition-opacity group-hover:opacity-100 focus:opacity-100" aria-label="Delete image {{ $index + 1 }}" title="Delete image">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16m-10 4v5m4-5v5M9 7V4h6v3m-9 0 1 13h10l1-13" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-2 px-3 py-2">
                        <span class="text-[11px] font-medium text-base-content/50">Image {{ $index + 1 }}</span>
                        @if ($image->is_primary)
                            <span class="text-[10px] font-semibold text-primary">Storefront cover</span>
                        @else
                            <span class="text-[10px] text-base-content/35">Drag to arrange</span>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
