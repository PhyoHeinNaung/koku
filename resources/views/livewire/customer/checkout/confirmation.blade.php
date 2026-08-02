<div class="px-6 sm:px-10 lg:px-16 py-16 max-w-2xl mx-auto">

    @if (!$order)
        <div class="text-center py-16">
            <p class="text-gray-400">We couldn't find that order.</p>
            <a href="{{ route('shop.index') }}"
                class="inline-block mt-4 text-sm underline underline-offset-4 text-gray-900">
                Continue shopping
            </a>
        </div>
    @else
        <div class="text-center mb-10">
            <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-green-50 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-green-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Order Confirmed</h1>
            <p class="text-gray-500 mt-1">
                Order <span class="font-medium text-gray-900">{{ $order->order_number }}</span> — a confirmation has been
                sent to {{ $order->email }}
            </p>
            @if ($order->status === 'pending')
                <p class="text-xs text-amber-600 mt-2">Finalizing your order — this page will update shortly.</p>
            @endif
        </div>

        <div class="border border-gray-200 rounded-xl divide-y divide-gray-100">
            @foreach ($order->items as $item)
                <div class="flex justify-between px-5 py-4 text-sm">
                    <div>
                        <p class="text-gray-900">{{ $item->product_name }}</p>
                        <p class="text-gray-500 text-xs mt-0.5">{{ $item->variant_name }} &middot; Qty {{ $item->quantity }}</p>
                    </div>
                    <p class="text-gray-900">${{ number_format($item->subtotal, 2) }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-6 space-y-2 text-sm">
            <div class="flex justify-between"><span class="text-gray-600">Subtotal</span><span
                    class="text-gray-900">${{ number_format($order->subtotal, 2) }}</span></div>
            @if ($order->discount > 0)
                <div class="flex justify-between"><span class="text-gray-600">Discount</span><span
                        class="text-green-700">−${{ number_format($order->discount, 2) }}</span></div>
            @endif
            <div class="flex justify-between"><span class="text-gray-600">Shipping</span><span
                    class="text-gray-900">${{ number_format($order->shipping_fee, 2) }}</span></div>
            @if ($order->insurance_fee > 0)
                <div class="flex justify-between"><span class="text-gray-600">Insurance</span><span
                        class="text-gray-900">${{ number_format($order->insurance_fee, 2) }}</span></div>
            @endif
            <div class="flex justify-between font-semibold text-base pt-2 border-t border-gray-200">
                <span>Total</span><span>${{ number_format($order->total, 2) }}</span></div>
        </div>

        <div class="mt-8 border-t border-gray-200 pt-6 text-sm text-gray-500">
            <p class="text-gray-900 font-medium mb-1">Shipping to</p>
            <p>{{ $order->shipping_full_name }}</p>
            <p>{{ $order->shipping_address_line1 }}{{ $order->shipping_address_line2 ? ', ' . $order->shipping_address_line2 : '' }}
            </p>
            <p>{{ $order->shipping_city }}{{ $order->shipping_district_area ? ', ' . $order->shipping_district_area : '' }},
                {{ $order->shipping_state_region }}, {{ $order->shipping_country }}</p>
        </div>

        <div class="mt-10 text-center">
            <a href="{{ route('shop.index') }}" class="text-sm underline underline-offset-4 text-gray-900">
                Continue shopping
            </a>
        </div>
    @endif

</div>