<div class="px-6 sm:px-10 lg:px-16 py-10 max-w-3xl">

    <h1 class="text-3xl font-bold text-gray-900 mb-8">My Orders</h1>

    @if ($orders->isEmpty())
        <p class="text-gray-400 text-center py-16">No orders yet.</p>
    @else
        <div class="border border-gray-200 rounded-xl divide-y divide-gray-100">
            @foreach ($orders as $order)
                <a href="{{ route('orders.show', $order) }}" wire:key="order-{{ $order->id }}"
                    class="flex items-center justify-between px-5 py-4 hover:bg-gray-50">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $order->order_number }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $order->created_at->format('M j, Y') }}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="px-2 py-1 text-xs rounded-full
                                    {{ match ($order->status) {
                    'pending' => 'bg-amber-50 text-amber-700',
                    'processing' => 'bg-blue-50 text-blue-700',
                    'shipped' => 'bg-indigo-50 text-indigo-700',
                    'delivered' => 'bg-green-50 text-green-700',
                    'cancelled' => 'bg-gray-100 text-gray-500',
                } }}">
                            {{ ucfirst($order->status) }}
                        </span>
                        <p class="text-sm text-gray-900">${{ number_format($order->total, 2) }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">{{ $orders->links() }}</div>
    @endif

</div>