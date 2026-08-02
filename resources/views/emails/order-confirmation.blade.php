<x-mail::message>
    # Thank you for your order!

    Your order **{{ $order->order_number }}** has been confirmed.

    @component('mail::table')
    | Item | Qty | Price |
    |:-----|:---:|------:|
    @foreach ($order->items as $item)
        | {{ $item->product_name }} ({{ $item->variant_name }}) | {{ $item->quantity }} |
        ${{ number_format($item->subtotal, 2) }} |
    @endforeach
    @endcomponent

    **Total: ${{ number_format($order->total, 2) }}**

    Shipping to: {{ $order->shipping_full_name }}, {{ $order->shipping_address_line1 }}, {{ $order->shipping_city }},
    {{ $order->shipping_state_region }}, {{ $order->shipping_country }}

    Thanks for shopping with us,<br>
    {{ $storeSettings->store_name }}

    @if ($storeSettings->support_email)
        Questions? Contact {{ $storeSettings->support_email }}.
    @endif
</x-mail::message>
