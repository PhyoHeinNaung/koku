<div class="min-h-screen bg-[#f6f4f0] py-10 sm:py-16"><main class="koku-shell max-w-5xl">
    <a href="{{ route('community.index') }}" class="text-xs text-[var(--koku-indigo)]">← Community</a>
    <section class="mt-6 overflow-hidden rounded-[2rem] bg-white shadow-[0_24px_70px_rgba(31,38,53,.08)]">
        <header class="bg-[var(--koku-indigo-deep)] px-6 py-9 text-white sm:px-10"><span class="text-[10px] uppercase tracking-[.17em] text-white/50">Verified owners</span><h1 class="mt-3 font-serif text-4xl tracking-[-.04em]">Share a wrist story.</h1><p class="mt-3 text-xs text-white/55">A personal moment, reviewed with care before it joins the community.</p></header>
        @if($eligibleItems->isEmpty())
            <div class="p-10 text-center"><h2 class="font-serif text-2xl">No eligible watches yet.</h2><p class="mt-2 text-sm text-[var(--koku-muted)]">You can share after an order has been delivered.</p><a href="{{ route('orders.index') }}" class="mt-6 inline-block rounded-full bg-[var(--koku-indigo)] px-5 py-3 text-xs text-white">View orders</a></div>
        @else
            <form wire:submit="save" class="grid gap-8 p-6 sm:p-10">
                <fieldset><legend class="koku-field-label">Choose the watch you wore</legend><div class="mt-3 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($eligibleItems as $item) @php($product=$item->variant->product) @php($image=$item->variant->primary_image)
                    <label class="group relative cursor-pointer"><input type="radio" wire:model="productId" value="{{ $product->id }}" class="peer sr-only"><span class="flex min-h-28 items-center gap-4 rounded-2xl border border-[var(--koku-line)] bg-[#faf9f7] p-3 transition peer-checked:border-[var(--koku-indigo)] peer-checked:bg-[#f1f3f9] peer-checked:shadow-[0_0_0_3px_rgba(36,57,105,.08)]">
                        <span class="flex size-20 shrink-0 items-center justify-center rounded-xl bg-white">@if($image)<img src="{{ Storage::url($image->image_url) }}" alt="{{ $product->name }}" class="size-16 object-contain">@else<span class="font-serif text-xl text-[var(--koku-muted)]">K</span>@endif</span>
                        <span class="min-w-0"><span class="block text-[9px] uppercase tracking-[.13em] text-[var(--koku-muted)]">{{ $product->brand?->name }}</span><span class="mt-1 block text-sm font-medium leading-5">{{ $product->name }}</span><span class="mt-2 inline-flex items-center gap-1 text-[9px] text-emerald-700"><span class="size-1.5 rounded-full bg-emerald-500"></span>Verified purchase</span></span>
                        <span class="absolute right-3 top-3 flex size-5 items-center justify-center rounded-full border border-[var(--koku-line)] bg-white text-transparent peer-checked:border-[var(--koku-indigo)] peer-checked:bg-[var(--koku-indigo)] peer-checked:text-white">✓</span>
                    </span></label>@endforeach
                </div>@error('productId')<p class="koku-field-error">{{ $message }}</p>@enderror</fieldset>
                <div><label class="koku-field-label">Your story</label><textarea wire:model="caption" rows="5" maxlength="2000" class="koku-field resize-none rounded-xl" placeholder="Where were you? What does this watch mean to you?"></textarea></div>
                <div><label class="koku-field-label">Location <span class="normal-case tracking-normal">(optional)</span></label><input wire:model="location" class="koku-field rounded-xl" placeholder="Yangon, Myanmar"></div>
                <div><label class="koku-field-label">Photos</label><label class="flex cursor-pointer flex-col items-center justify-center rounded-2xl border border-dashed border-[var(--koku-line)] bg-[#f8f7f4] px-6 py-9 text-center"><svg class="size-7 text-[var(--koku-indigo)]" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 8h3l2-2h6l2 2h3v11H4V8Z"/><circle cx="12" cy="13" r="3"/></svg><span class="mt-3 text-sm font-medium">Choose 1–5 images</span><span class="mt-1 text-xs text-[var(--koku-muted)]">JPG, PNG or WebP · 5 MB each</span><input type="file" wire:model="photos" multiple accept="image/jpeg,image/png,image/webp" class="sr-only"></label>@error('photos')<p class="koku-field-error">{{ $message }}</p>@enderror @error('photos.*')<p class="koku-field-error">{{ $message }}</p>@enderror @if(count($photos))<div class="mt-3 grid grid-cols-3 gap-2 sm:grid-cols-5">@foreach($photos as $photo)<img src="{{ $photo->temporaryUrl() }}" alt="Post preview" class="aspect-square w-full rounded-xl object-cover">@endforeach</div>@endif</div>
                <button class="w-fit rounded-full bg-[var(--koku-indigo)] px-7 py-3.5 text-xs font-medium text-white">Send for approval</button>
            </form>
        @endif
    </section>
</main></div>
