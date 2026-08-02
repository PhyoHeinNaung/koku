<div class="px-6 sm:px-10 lg:px-16 py-10 max-w-2xl">

    <a href="{{ route('orders.index') }}" class="text-sm text-gray-500 hover:text-gray-900">← Back to orders</a>

    <div class="flex items-center justify-between mt-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $order->order_number }}</h1>
            <p class="text-sm text-gray-500 mt-1">Placed {{ $order->created_at->format('M j, Y') }}</p>
        </div>
        <span class="px-3 py-1 text-sm rounded-full
            {{ match ($order->status) {
    'pending' => 'bg-amber-50 text-amber-700',
    'processing' => 'bg-blue-50 text-blue-700',
    'shipped' => 'bg-indigo-50 text-indigo-700',
    'delivered' => 'bg-green-50 text-green-700',
    'cancelled' => 'bg-gray-100 text-gray-500',
} }}">
            {{ ucfirst($order->status) }}
        </span>
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

    <div class="mt-8 border-t border-gray-200 pt-6 grid grid-cols-2 gap-8 text-sm text-gray-500">
        <div>
            <p class="text-gray-900 font-medium mb-1">Shipping Address</p>
            <p>{{ $order->shipping_full_name }}</p>
            <p>{{ $order->shipping_address_line1 }}</p>
            <p>{{ $order->shipping_city }}, {{ $order->shipping_state_region }}, {{ $order->shipping_country }}</p>
        </div>
        <div>
            <p class="text-gray-900 font-medium mb-1">Payment</p>
            @forelse ($order->payments as $payment)
                <p class="capitalize">{{ $payment->method }} — {{ ucfirst($payment->status) }}</p>
                <p class="text-xs mt-0.5">{{ $payment->paid_at?->format('M j, Y g:i A') }}</p>
            @empty
                <p>Awaiting confirmation</p>
            @endforelse
        </div>
    </div>

</div>