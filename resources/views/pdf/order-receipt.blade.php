<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt - {{ $order->order_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans Mono', monospace;
            font-size: 13px;
            color: #111;
            background: #fff;
            padding: 30px 20px;
        }

        .receipt {
            max-width: 480px;
            margin: 0 auto;
        }

        /* Header */
        .header { text-align: center; margin-bottom: 16px; }
        .store-name {
            font-size: 26px;
            font-weight: 900;
            letter-spacing: 5px;
            color: #ea5a47;
            margin-bottom: 4px;
        }
        .store-info { font-size: 11px; color: #555; line-height: 1.6; }

        /* Dividers */
        .dash1 { border-top: 1px dashed #777; margin: 10px 0; }
        .dash2 { border-top: 2px dashed #222; margin: 10px 0; }
        .solid  { border-top: 2px solid #222; margin: 10px 0; }

        /* Title */
        .doc-title {
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            letter-spacing: 2px;
            margin: 8px 0;
        }

        /* Info table */
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        .info-table td { padding: 2px 0; font-size: 12px; line-height: 1.7; }
        .info-table .label { color: #555; width: 38%; }
        .info-table .value { text-align: right; }
        .info-table .value.bold { font-weight: bold; }
        .info-table .value.mono { font-family: 'DejaVu Sans Mono', monospace; font-size: 11px; }

        /* Items */
        .section-title { font-weight: bold; font-size: 12px; margin-bottom: 8px; letter-spacing: 1px; }
        .item-row { display: table; width: 100%; margin-bottom: 5px; }
        .item-name { display: table-cell; font-size: 12px; }
        .item-price { display: table-cell; text-align: right; font-size: 12px; white-space: nowrap; }
        .item-note { font-size: 11px; color: #666; padding-left: 14px; margin-bottom: 4px; }

        /* Totals */
        .totals-table { width: 100%; border-collapse: collapse; }
        .totals-table td { padding: 2px 0; font-size: 12px; line-height: 1.7; }
        .totals-table .label { color: #555; }
        .totals-table .value { text-align: right; }
        .totals-table .discount { color: #16a34a; }
        .totals-table .discount .value { color: #16a34a; }

        /* Grand total */
        .grand-total {
            display: table;
            width: 100%;
            margin: 6px 0;
        }
        .grand-total .label {
            display: table-cell;
            font-weight: bold;
            font-size: 16px;
        }
        .grand-total .amount {
            display: table-cell;
            text-align: right;
            font-weight: bold;
            font-size: 16px;
            color: #ea5a47;
        }

        .savings {
            text-align: right;
            font-size: 11px;
            color: #16a34a;
            margin-top: 3px;
        }

        /* Status badges */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: bold;
        }
        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-yellow { background: #fef9c3; color: #a16207; }
        .badge-red    { background: #fee2e2; color: #b91c1c; }
        .badge-gray   { background: #f3f4f6; color: #374151; }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 16px;
            font-size: 12px;
            line-height: 1.8;
        }
        .footer .tagline { font-weight: bold; font-size: 13px; }
        .footer .meta { font-size: 10px; color: #888; margin-top: 8px; }
    </style>
</head>
<body>
<div class="receipt">

    {{-- Header --}}
    <div class="header">
        <div class="store-name">2DINE-IN</div>
        <div class="store-info">
            San Juan Bautista, Goa, Camarines Sur<br>
            (054) 123 4567 &bull; Open Daily: 10:00 AM &ndash; 10:00 PM
        </div>
    </div>

    <div class="dash2"></div>
    <div class="doc-title">ORDER RECEIPT</div>
    <div class="dash1"></div>

    {{-- Order Info --}}
    @php
        $printPaymentMethod  = $order->latestPayment?->payment_method;
        $printPromoDiscount  = $order->promo_discount ?? 0;
        $printPromoLabel     = $order->promo_label ?: 'Promotion';
        $printDiscount       = $order->discount ?? 0;
        $printDiscountType   = $order->discount_type ?? null;
        $printIsPrivilege    = in_array($printDiscountType, ['pwd', 'senior']);
        $printDiscountLabel  = match($printDiscountType) {
            'pwd'     => 'PWD Discount (20%)',
            'senior'  => 'Senior Citizen Discount (20%)',
            'voucher' => 'Voucher' . ($order->discount_label ? ': ' . $order->discount_label : ''),
            default   => $order->discount_label ?: 'Discount',
        };
    @endphp

    <table class="info-table">
        <tr>
            <td class="label">Date</td>
            <td class="value">{{ \Carbon\Carbon::parse($order->ordered_at ?? $order->created_at)->timezone('Asia/Manila')->format('M d, Y h:i A') }}</td>
        </tr>
        <tr>
            <td class="label">Order #</td>
            <td class="value bold">{{ $order->order_number }}</td>
        </tr>
        <tr>
            <td class="label">Customer</td>
            <td class="value">{{ $order->customer_name }}</td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td class="value bold">{{ strtoupper($order->order_status) }}</td>
        </tr>
        <tr>
            <td class="label">Payment</td>
            <td class="value">{{ $printPaymentMethod ? ucwords(str_replace('_', ' ', $printPaymentMethod)) : 'N/A' }}</td>
        </tr>
        @if($order->latestPayment?->reference_number)
        <tr>
            <td class="label">Ref #</td>
            <td class="value mono">{{ $order->latestPayment->reference_number }}</td>
        </tr>
        @endif
    </table>

    <div class="dash1"></div>
    <div class="section-title">ORDERED ITEMS</div>

    @foreach($order->items as $item)
    <div class="item-row">
        <div class="item-name">{{ $item->quantity }}x {{ $item->item_name }}</div>
        <div class="item-price">&#8369;{{ number_format($item->subtotal, 2) }}</div>
    </div>
    @endforeach

    <div class="dash1"></div>

    {{-- Totals --}}
    <table class="totals-table">
        <tr>
            <td class="label">Subtotal</td>
            <td class="value">&#8369;{{ number_format($order->subtotal, 2) }}</td>
        </tr>
        @if($printPromoDiscount > 0)
        <tr class="discount">
            <td class="label discount">{{ $printPromoLabel }}</td>
            <td class="value discount">&minus;&#8369;{{ number_format($printPromoDiscount, 2) }}</td>
        </tr>
        @endif
        @if($printDiscount > 0)
        <tr class="discount">
            <td class="label discount">{{ $printDiscountLabel }}</td>
            <td class="value discount">&minus;&#8369;{{ number_format($printDiscount, 2) }}</td>
        </tr>
        @if($printIsPrivilege)
        <tr class="discount">
            <td class="label discount">VAT Exempt</td>
            <td class="value discount">&minus;&#8369;{{ number_format($order->tax, 2) }}</td>
        </tr>
        @endif
        @endif
        @if(!$printIsPrivilege)
        <tr>
            <td class="label">VAT (12%)</td>
            <td class="value">&#8369;{{ number_format($order->tax, 2) }}</td>
        </tr>
        @endif
    </table>

    <div class="solid"></div>

    <div class="grand-total">
        <div class="label">TOTAL</div>
        <div class="amount">&#8369;{{ number_format($order->total, 2) }}</div>
    </div>

    @php $printTotalSaved = $printPromoDiscount + $printDiscount + ($printIsPrivilege ? $order->tax : 0); @endphp
    @if($printTotalSaved > 0)
    <div class="savings">
        You saved &#8369;{{ number_format($printTotalSaved, 2) }}
    </div>
    @endif

    <div class="dash2"></div>

    {{-- Footer --}}
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
