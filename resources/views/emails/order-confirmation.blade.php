<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <title>Order {{ $order->order_number }} confirmed</title>
    <style>
        @media only screen and (max-width: 620px) {
            .email-shell { padding: 0 !important; }
            .email-card { border-radius: 0 !important; }
            .section { padding-left: 24px !important; padding-right: 24px !important; }
            .hero-title { font-size: 34px !important; }
            .mobile-block { display: block !important; width: 100% !important; }
            .mobile-left { padding-top: 20px !important; padding-left: 0 !important; text-align: left !important; }
        }
    </style>
</head>
<body style="margin:0; padding:0; background:#eeece6; color:#1d1d1a; font-family:Arial, Helvetica, sans-serif; -webkit-font-smoothing:antialiased;">
<div style="display:none; max-height:0; overflow:hidden; opacity:0; color:transparent;">
    Your order {{ $order->order_number }} is confirmed. Total ${{ number_format((float) $order->total, 2) }}.
</div>
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="width:100%; background:#eeece6;">
<tr><td class="email-shell" align="center" style="padding:40px 16px;">
<table class="email-card" role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="width:100%; max-width:600px; overflow:hidden; background:#fbfaf7; border-radius:4px;">
    <tr><td class="section" style="padding:28px 42px; background:#192640; color:#ffffff;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr>
            <td style="font-family:Georgia, 'Times New Roman', serif; font-size:25px; font-weight:bold; letter-spacing:-0.6px;">{{ $storeSettings->store_name }}</td>
            <td align="right" style="font-size:10px; font-weight:bold; letter-spacing:1.7px; text-transform:uppercase; color:#d9c6a6;">Order confirmation</td>
        </tr></table>
    </td></tr>
    <tr><td class="section" style="padding:52px 42px 46px; background:#f6f2e9; border-bottom:1px solid #ded7ca;">
        <div style="width:34px; height:3px; margin-bottom:24px; background:#b66a4d; font-size:0; line-height:0;">&nbsp;</div>
        <p style="margin:0 0 12px; color:#746f67; font-size:11px; font-weight:bold; letter-spacing:1.6px; text-transform:uppercase;">Payment received</p>
        <h1 class="hero-title" style="margin:0; color:#1d1d1a; font-family:Georgia, 'Times New Roman', serif; font-size:42px; font-weight:normal; line-height:1.12; letter-spacing:-1.4px;">Your order is confirmed.</h1>
        <p style="max-width:430px; margin:20px 0 0; color:#625f59; font-size:15px; line-height:1.7;">Thank you, {{ $order->shipping_full_name }}. We’re preparing your order and will let you know when it is on the way.</p>
    </td></tr>
    <tr><td class="section" style="padding:28px 42px; border-bottom:1px solid #ded7ca;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr>
            <td class="mobile-block" width="50%" valign="top">
                <p style="margin:0 0 7px; color:#8a857c; font-size:10px; font-weight:bold; letter-spacing:1.35px; text-transform:uppercase;">Order number</p>
                <p style="margin:0; color:#293d68; font-family:'Courier New', monospace; font-size:14px; font-weight:bold;">{{ $order->order_number }}</p>
            </td>
            <td class="mobile-block mobile-left" width="50%" align="right" valign="top">
                <p style="margin:0 0 7px; color:#8a857c; font-size:10px; font-weight:bold; letter-spacing:1.35px; text-transform:uppercase;">Order date</p>
                <p style="margin:0; color:#34332f; font-size:14px;">{{ $order->created_at?->format('F j, Y') ?? now()->format('F j, Y') }}</p>
            </td>
        </tr></table>
    </td></tr>
    <tr><td class="section" style="padding:38px 42px 12px;">
        <h2 style="margin:0 0 20px; color:#1d1d1a; font-family:Georgia, 'Times New Roman', serif; font-size:23px; font-weight:normal; letter-spacing:-0.4px;">Order details</h2>
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
            @foreach ($order->items as $item)
                <tr>
                    <td valign="top" style="padding:18px 0; border-top:1px solid #ded7ca;">
                        <p style="margin:0 0 6px; color:#252521; font-size:15px; font-weight:bold; line-height:1.4;">{{ $item->product_name }}</p>
                        <p style="margin:0; color:#827d74; font-size:12px; line-height:1.5;">{{ $item->variant_name }} &nbsp;·&nbsp; Qty {{ $item->quantity }}</p>
                    </td>
                    <td width="110" align="right" valign="top" style="padding:18px 0; border-top:1px solid #ded7ca; color:#293d68; font-family:'Courier New', monospace; font-size:14px; font-weight:bold; white-space:nowrap;">${{ number_format((float) $item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </table>
    </td></tr>
    <tr><td class="section" style="padding:8px 42px 38px;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f0ede6;">
            <tr><td style="padding:22px 24px 10px; color:#6e6a63; font-size:12px;">Subtotal</td><td align="right" style="padding:22px 24px 10px; color:#34332f; font-family:'Courier New', monospace; font-size:12px;">${{ number_format((float) $order->subtotal, 2) }}</td></tr>
            @if ((float) $order->discount > 0)
                <tr><td style="padding:5px 24px; color:#6e6a63; font-size:12px;">Discount</td><td align="right" style="padding:5px 24px; color:#347468; font-family:'Courier New', monospace; font-size:12px;">−${{ number_format((float) $order->discount, 2) }}</td></tr>
            @endif
            @if ((float) $order->shipping_fee > 0)
                <tr><td style="padding:5px 24px; color:#6e6a63; font-size:12px;">Shipping</td><td align="right" style="padding:5px 24px; color:#34332f; font-family:'Courier New', monospace; font-size:12px;">${{ number_format((float) $order->shipping_fee, 2) }}</td></tr>
            @endif
            @if ((float) $order->insurance_fee > 0)
                <tr><td style="padding:5px 24px; color:#6e6a63; font-size:12px;">Shipping protection</td><td align="right" style="padding:5px 24px; color:#34332f; font-family:'Courier New', monospace; font-size:12px;">${{ number_format((float) $order->insurance_fee, 2) }}</td></tr>
            @endif
            @if ((float) $order->tax > 0)
                <tr><td style="padding:5px 24px; color:#6e6a63; font-size:12px;">Tax</td><td align="right" style="padding:5px 24px; color:#34332f; font-family:'Courier New', monospace; font-size:12px;">${{ number_format((float) $order->tax, 2) }}</td></tr>
            @endif
            <tr><td style="padding:18px 24px 22px; color:#1d1d1a; font-size:14px; font-weight:bold; border-top:1px solid #d8d1c4;">Total</td><td align="right" style="padding:18px 24px 22px; color:#192640; font-family:'Courier New', monospace; font-size:19px; font-weight:bold; border-top:1px solid #d8d1c4;">${{ number_format((float) $order->total, 2) }}</td></tr>
        </table>
    </td></tr>
    <tr><td class="section" style="padding:34px 42px 38px; background:#ffffff; border-top:1px solid #ded7ca;">
        <h2 style="margin:0 0 16px; color:#1d1d1a; font-family:Georgia, 'Times New Roman', serif; font-size:21px; font-weight:normal;">Shipping to</h2>
        <p style="margin:0; color:#5f5c56; font-size:13px; line-height:1.8;">
            <strong style="color:#292925;">{{ $order->shipping_full_name }}</strong><br>
            {{ $order->shipping_address_line1 }}@if($order->shipping_address_line2), {{ $order->shipping_address_line2 }}@endif<br>
            @if($order->shipping_district_area){{ $order->shipping_district_area }}, @endif{{ $order->shipping_city }}@if($order->shipping_state_region), {{ $order->shipping_state_region }}@endif @if($order->shipping_postal_code){{ $order->shipping_postal_code }}@endif<br>
            {{ $order->shipping_country }}
        </p>
    </td></tr>
    <tr><td class="section" style="padding:30px 42px; background:#192640; color:#bfc5d1;">
        <p style="margin:0 0 9px; color:#ffffff; font-size:13px; font-weight:bold;">Thank you for choosing {{ $storeSettings->store_name }}.</p>
        @if ($storeSettings->support_email)
            <p style="margin:0; font-size:11px; line-height:1.7;">Questions about your order? Write to <a href="mailto:{{ $storeSettings->support_email }}" style="color:#d9c6a6; text-decoration:none;">{{ $storeSettings->support_email }}</a>.</p>
        @else
            <p style="margin:0; font-size:11px; line-height:1.7;">Keep this email for your order records.</p>
        @endif
    </td></tr>
</table>
</td></tr>
</table>
</body>
</html>
