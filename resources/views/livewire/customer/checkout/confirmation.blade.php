<div class="bg-[var(--koku-paper)] py-12 sm:py-20 lg:py-28">
    <main class="koku-shell max-w-5xl">
        @if (!$order)
            <div class="flex min-h-[30rem] flex-col items-center justify-center border-y border-[var(--koku-line)] text-center"><span class="font-serif text-5xl text-[var(--koku-indigo)]">?</span><h1 class="mt-6 font-serif text-3xl">Order not found.</h1><p class="mt-3 text-sm text-[var(--koku-muted)]">We could not find an order associated with this payment.</p><a href="{{ route('shop.index') }}" class="koku-link mt-8">Return to watches <span>→</span></a></div>
        @else
            <header class="grid gap-10 border-b border-[var(--koku-ink)] pb-12 lg:grid-cols-[1fr_1.1fr] lg:items-end">
                <div><div class="flex size-12 items-center justify-center border border-[var(--koku-indigo)] text-[var(--koku-indigo)]">✓</div><p class="koku-eyebrow mt-7 text-[var(--koku-indigo)]">Order received</p><h1 class="mt-4 font-serif text-5xl font-medium leading-none tracking-[-0.055em] sm:text-6xl">Thank you.</h1></div>
                <div><p class="font-serif text-xl leading-8">Your timepiece is being prepared with care.</p><p class="mt-4 text-sm leading-7 text-[var(--koku-muted)]">Order <strong class="font-medium text-[var(--koku-ink)]">{{ $order->order_number }}</strong>. A confirmation has been sent to {{ $order->email }}.</p>@if ($order->status === 'pending')<p class="mt-3 text-xs text-[#8a651b]">Payment is being finalized. This page will update when processing completes.</p>@endif</div>
            </header>

            <div class="grid gap-14 py-12 lg:grid-cols-[1.2fr_.8fr] lg:gap-20 lg:py-16">
                <section>
                    <div class="flex items-center justify-between border-b border-[var(--koku-line)] pb-4"><h2 class="font-serif text-2xl">Order details</h2><span class="koku-eyebrow text-[var(--koku-muted)]">{{ $order->items->sum('quantity') }} {{ Str::plural('piece', $order->items->sum('quantity')) }}</span></div>
                    @foreach ($order->items as $item)
                        <div class="flex items-start justify-between gap-6 border-b border-[var(--koku-line)] py-6 text-sm"><div><p class="font-serif text-lg">{{ $item->product_name }}</p><p class="mt-2 text-xs text-[var(--koku-muted)]">{{ $item->variant_name }} · Quantity {{ $item->quantity }}</p></div><p class="shrink-0">${{ number_format($item->subtotal, 2) }}</p></div>
                    @endforeach
                    <div class="ml-auto mt-7 max-w-sm space-y-3 text-xs"><div class="flex justify-between"><span class="text-[var(--koku-muted)]">Subtotal</span><span>${{ number_format($order->subtotal, 2) }}</span></div>@if ($order->discount > 0)<div class="flex justify-between text-[var(--koku-indigo)]"><span>Discount</span><span>−${{ number_format($order->discount, 2) }}</span></div>@endif<div class="flex justify-between"><span class="text-[var(--koku-muted)]">Delivery</span><span>${{ number_format($order->shipping_fee, 2) }}</span></div>@if ($order->insurance_fee > 0)<div class="flex justify-between"><span class="text-[var(--koku-muted)]">Insurance</span><span>${{ number_format($order->insurance_fee, 2) }}</span></div>@endif<div class="mt-4 flex items-end justify-between border-t border-[var(--koku-ink)] pt-4"><span class="font-serif text-lg">Total</span><strong class="font-serif text-2xl font-medium">${{ number_format($order->total, 2) }}</strong></div></div>
                </section>

                <aside class="space-y-10">
                    <div class="border-t border-[var(--koku-ink)] pt-5"><p class="koku-eyebrow text-[var(--koku-indigo)]">Delivering to</p><address class="mt-5 text-sm not-italic leading-7 text-[var(--koku-muted)]"><strong class="font-medium text-[var(--koku-ink)]">{{ $order->shipping_full_name }}</strong><br>{{ $order->shipping_address_line1 }}@if($order->shipping_address_line2)<br>{{ $order->shipping_address_line2 }}@endif<br>{{ $order->shipping_city }}@if($order->shipping_district_area), {{ $order->shipping_district_area }}@endif<br>{{ $order->shipping_state_region }}, {{ $order->shipping_country }}</address></div>
                    <div class="border-t border-[var(--koku-line)] pt-5"><p class="koku-eyebrow text-[var(--koku-muted)]">What happens next</p><ol class="mt-5 space-y-5 text-sm"><li class="grid grid-cols-[1.5rem_1fr] gap-3"><span class="font-serif text-[var(--koku-indigo)]">1</span><span>We verify and prepare your order.</span></li><li class="grid grid-cols-[1.5rem_1fr] gap-3"><span class="font-serif text-[var(--koku-indigo)]">2</span><span>You receive a dispatch confirmation.</span></li><li class="grid grid-cols-[1.5rem_1fr] gap-3"><span class="font-serif text-[var(--koku-indigo)]">3</span><span>Your watch arrives at the address above.</span></li></ol></div>
                </aside>
            </div>
            <div class="flex flex-col gap-5 border-t border-[var(--koku-line)] pt-8 sm:flex-row sm:items-center sm:justify-between"><p class="text-xs text-[var(--koku-muted)]">Questions about your order? Contact Koku support.</p><a href="{{ route('shop.index') }}" class="koku-link">Continue exploring <span>→</span></a></div>
        @endif
    </main>
</div>
