<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaction Receipt - {{ $transaction->transaction_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans Mono', monospace;
            font-size: 13px;
            color: #111;
            background: #fff;
            padding: 30px 20px;
        }
        .receipt { max-width: 480px; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 16px; }
        .store-name { font-size: 26px; font-weight: 900; letter-spacing: 5px; color: #ea5a47; margin-bottom: 4px; }
        .store-info { font-size: 11px; color: #555; line-height: 1.6; }
        .dash1 { border-top: 1px dashed #777; margin: 10px 0; }
        .dash2 { border-top: 2px dashed #222; margin: 10px 0; }
        .solid  { border-top: 2px solid #222; margin: 10px 0; }
        .doc-title { text-align: center; font-weight: bold; font-size: 13px; letter-spacing: 2px; margin: 8px 0; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        .info-table td { padding: 2px 0; font-size: 12px; line-height: 1.7; }
        .info-table .label { color: #555; width: 38%; }
        .info-table .value { text-align: right; }
        .info-table .value.bold { font-weight: bold; }
        .info-table .value.mono { font-family: 'DejaVu Sans Mono', monospace; font-size: 11px; }
        .section-title { font-weight: bold; font-size: 12px; margin-bottom: 8px; letter-spacing: 1px; }
        .item-row { display: table; width: 100%; margin-bottom: 5px; }
        .item-name { display: table-cell; font-size: 12px; }
        .item-price { display: table-cell; text-align: right; font-size: 12px; white-space: nowrap; }
        .item-note { font-size: 11px; color: #666; padding-left: 14px; margin-bottom: 4px; }
        .totals-table { width: 100%; border-collapse: collapse; }
        .totals-table td { padding: 2px 0; font-size: 12px; line-height: 1.7; }
        .totals-table .label { color: #555; }
        .totals-table .value { text-align: right; }
        .discount { color: #16a34a; }
        .grand-total { display: table; width: 100%; margin: 6px 0; }
        .grand-total .label { display: table-cell; font-weight: bold; font-size: 16px; }
        .grand-total .amount { display: table-cell; text-align: right; font-weight: bold; font-size: 16px; color: #ea5a47; }
        .savings { text-align: right; font-size: 11px; color: #16a34a; margin-top: 3px; }
        .refund-box { border: 1px dashed #ef4444; padding: 8px 10px; margin-top: 10px; font-size: 11px; color: #b91c1c; }
        .footer { text-align: center; margin-top: 16px; font-size: 12px; line-height: 1.8; }
        .footer .tagline { font-weight: bold; font-size: 13px; }
        .footer .meta { font-size: 10px; color: #888; margin-top: 8px; }
    </style>
</head>
<body>
<div class="receipt">

    <div class="header">
        <div class="store-name">2DINE-IN</div>
        <div class="store-info">
            San Juan Bautista, Goa, Camarines Sur<br>
            (054) 123 4567 &bull; Open Daily: 10:00 AM &ndash; 10:00 PM
        </div>
    </div>

    <div class="dash2"></div>
    <div class="doc-title">OFFICIAL RECEIPT</div>
    <div class="dash1"></div>

    @php
        $txOrder         = $transaction->order;
        $txDiscount      = $txOrder?->discount ?? 0;
        $txDiscountType  = $txOrder?->discount_type;
        $txDiscountLabel = $txOrder?->discount_label;
        $txIsPrivilege   = in_array($txDiscountType, ['pwd', 'senior']);
        $txDiscountTypeLabel = match($txDiscountType) {
            'pwd'     => 'PWD Discount (20%)',
            'senior'  => 'Senior Citizen Discount (20%)',
            'voucher' => 'Voucher' . ($txDiscountLabel ? ': ' . $txDiscountLabel : ''),
            default   => $txDiscountLabel ?: 'Discount',
        };
    @endphp

    <table class="info-table">
        <tr>
            <td class="label">Date</td>
            <td class="value">{{ $transaction->transaction_date->timezone('Asia/Manila')->format('M d, Y h:i A') }}</td>
        </tr>
        <tr>
            <td class="label">Trans. #</td>
            <td class="value bold">{{ $transaction->transaction_number }}</td>
        </tr>
        <tr>
            <td class="label">Order #</td>
            <td class="value">{{ $transaction->order_number }}</td>
        </tr>
        <tr>
            <td class="label">Customer</td>
            <td class="value">{{ $transaction->customer_name }}</td>
        </tr>
        <tr>
            <td class="label">Payment</td>
            <td class="value">{{ ucwords(str_replace('_', ' ', $transaction->payment_method)) }}</td>
        </tr>
        @if($transaction->reference_number)
        <tr>
            <td class="label">Ref #</td>
            <td class="value mono">{{ $transaction->reference_number }}</td>
        </tr>
        @endif
        <tr>
            <td class="label">Status</td>
            <td class="value bold">{{ strtoupper($transaction->payment_status) }}</td>
        </tr>
    </table>

    <div class="dash1"></div>
    <div class="section-title">ORDERED ITEMS</div>

    @foreach($transaction->items as $item)
    <div class="item-row">
        <div class="item-name">{{ $item->quantity }}x {{ $item->item_name }}</div>
        <div class="item-price">&#8369;{{ number_format($item->subtotal, 2) }}</div>
    </div>
    @endforeach

    <div class="dash1"></div>

    <table class="totals-table">
        <tr>
            <td class="label">Subtotal</td>
            <td class="value">&#8369;{{ number_format($transaction->subtotal, 2) }}</td>
        </tr>
        @if($txDiscount > 0)
        <tr>
            <td class="label discount">{{ $txDiscountTypeLabel }}</td>
            <td class="value discount">&minus;&#8369;{{ number_format($txDiscount, 2) }}</td>
        </tr>
        @if($txIsPrivilege)
        <tr>
            <td class="label discount">VAT Exempt</td>
            <td class="value discount">&minus;&#8369;{{ number_format($transaction->tax, 2) }}</td>
        </tr>
        @endif
        @endif
        @if(!$txIsPrivilege)
        <tr>
            <td class="label">VAT (12%)</td>
            <td class="value">&#8369;{{ number_format($transaction->tax, 2) }}</td>
        </tr>
        @endif
    </table>

    <div class="solid"></div>

    <div class="grand-total">
        <div class="label">TOTAL PAID</div>
        <div class="amount">&#8369;{{ number_format($transaction->total, 2) }}</div>
    </div>

    @if($txDiscount > 0)
    <div class="savings">
        You saved &#8369;{{ number_format($txDiscount + ($txIsPrivilege ? $transaction->tax : 0), 2) }}
    </div>
    @endif

    @if($transaction->isRefunded() && $transaction->refund_amount)
    <div class="dash1"></div>
    <table class="totals-table">
        <tr>
            <td class="label" style="color:#ef4444;">Refunded</td>
            <td class="value" style="color:#ef4444;">&minus;&#8369;{{ number_format($transaction->refund_amount, 2) }}</td>
        </tr>
        <tr>
            <td class="label" style="font-weight:bold;">Net Charged</td>
            <td class="value" style="font-weight:bold; text-align:right;">&#8369;{{ number_format($transaction->total - $transaction->refund_amount, 2) }}</td>
        </tr>
    </table>
    @if($transaction->refund_reason)
    <div class="refund-box">Refund reason: {{ $transaction->refund_reason }}</div>
    @endif
    @endif

    <div class="dash2"></div>

    <div class="footer">
        <div class="tagline">Thank you for dining with us!</div>
        <div>Please come again!</div>
        <div class="meta">
            This serves as your official receipt.<br>
            Generated: {{ now()->timezone('Asia/Manila')->format('M d, Y h:i A') }}
        </div>
    </div>

</div>
</body>
</html>
