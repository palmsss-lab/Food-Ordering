@extends('client.layouts.home')

@section('title', 'Payment Successful')

@section('content')
<div class="max-w-4xl mx-auto mt-24 md:mt-32 px-4 mb-20">
    
    <!-- Header -->
    <div class="flex items-center gap-3 mb-8">
        <div class="bg-[#ea5a47] p-3 rounded-2xl shadow-lg">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h1 class="text-2xl sm:text-4xl font-black text-gray-800">Payment <span class="text-[#ea5a47]">Successful</span></h1>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-3xl shadow-2xl p-12 text-center">
        <!-- Success Animation -->
        <div class="relative mb-8">
            <div class="w-32 h-32 mx-auto bg-green-100 rounded-full flex items-center justify-center animate-pulse">
                <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-gray-800 mb-4">Thank You for Your Payment!</h2>
        
        <p class="text-gray-600 mb-6 max-w-lg mx-auto">
            Your payment has been successfully processed. Your order is now being prepared.
            You will be notified when it's ready for pickup.
        </p>

        <!-- Order Details -->
        <div class="bg-gray-50 rounded-2xl p-6 max-w-md mx-auto mb-8">
            <p class="text-sm text-gray-500 mb-2">Order Number</p>
            <p class="text-xl font-bold text-[#ea5a47]">{{ $order->order_number }}</p>
            <p class="text-sm text-gray-500 mt-4 mb-2">Total Amount Paid</p>
            <p class="text-3xl font-black text-gray-800">{{ $order->formatted_total }}</p>
            <p class="text-sm text-gray-500 mt-4 mb-2">Payment Method</p>
            <p class="text-lg font-semibold text-gray-800 capitalize">{{ $order->payment_method }}</p>
            
            <!-- Payment Reference -->
            @if($order->payments->first())
            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-500 mb-1">Reference Number</p>
                <p class="text-sm font-mono bg-gray-100 px-3 py-2 rounded-lg">
                    {{ $order->payments->first()->reference_number }}
                </p>
            </div>
            @endif
        </div>

        <!-- Estimated Time -->
        <div class="bg-blue-50 rounded-2xl p-6 max-w-lg mx-auto mb-8">
            <div class="flex items-center justify-center gap-3">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-lg font-semibold text-gray-800">
                    Estimated pickup time: <span class="text-blue-600">15-20 minutes</span>
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row sm:flex-wrap gap-4 justify-center">
            <a href="{{ route('client.orders.index', ['tab' => 'preparing']) }}"
               class="w-full sm:w-auto px-8 py-3 bg-[#ea5a47] text-white font-semibold rounded-xl hover:bg-[#c53030] transition-all text-center">
                Track My Order
            </a>

            <a href="{{ route('client.menu') }}"
               class="w-full sm:w-auto px-8 py-3 bg-gray-600 text-white font-semibold rounded-xl hover:bg-gray-700 transition-all text-center">
                Order More
            </a>

            <a href="{{ route('client.payments.receipt.download', $order->order_number) }}"
               class="w-full sm:w-auto px-8 py-3 bg-[#ea5a47] text-white font-semibold rounded-xl hover:bg-[#c53030] transition-all flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download Receipt
            </a>
            <button onclick="window.print()"
                    class="w-full sm:w-auto px-8 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:border-gray-400 transition-all flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print
            </button>
        </div>
    </div>
</div>

{{-- Hidden print receipt --}}
@php
    $psPaymentMethod  = $order->payment_method;
    $psDiscount       = $order->discount ?? 0;
    $psDiscountType   = $order->discount_type ?? null;
    $psIsPrivilege    = in_array($psDiscountType, ['pwd', 'senior']);
    $psDiscountLabel  = match($psDiscountType) {
        'pwd'     => 'PWD Discount (20%)',
        'senior'  => 'Senior Citizen Discount (20%)',
        'voucher' => 'Voucher' . ($order->discount_label ? ': ' . $order->discount_label : ''),
        default   => $order->discount_label ?: 'Discount',
    };
@endphp

<div id="print-receipt">
    <div class="receipt-wrap">

        {{-- Header --}}
        <div class="r-center" style="margin-bottom:14px;">
            <div class="r-logo">2DINE-IN</div>
            <div class="r-sm">San Juan Bautista, Goa, Camarines Sur</div>
            <div class="r-sm">(054) 123 4567 &bull; Open Daily 10AM–10PM</div>
        </div>

        <div class="r-dash2"></div>
        <div class="r-center r-bold" style="letter-spacing:2px; margin:8px 0;">PAYMENT RECEIPT</div>
        <div class="r-dash1"></div>

        {{-- Order info --}}
        <table class="r-table">
            <tr><td class="r-label">Date</td><td class="r-val">{{ now()->timezone('Asia/Manila')->format('M d, Y h:i A') }}</td></tr>
            <tr><td class="r-label">Order #</td><td class="r-val r-bold">{{ $order->order_number }}</td></tr>
            <tr><td class="r-label">Customer</td><td class="r-val">{{ $order->customer_name }}</td></tr>
            <tr><td class="r-label">Payment</td><td class="r-val">{{ ucwords(str_replace('_', ' ', $psPaymentMethod)) }}</td></tr>
            @if($order->payments->first()?->reference_number)
            <tr><td class="r-label">Ref #</td><td class="r-val r-mono">{{ $order->payments->first()->reference_number }}</td></tr>
            @endif
        </table>

        <div class="r-dash1"></div>
        <div class="r-bold" style="margin-bottom:8px;">ORDERED ITEMS</div>

        @foreach($order->items as $item)
        <div class="r-item">
            <span>{{ $item->quantity }}x {{ $item->item_name }}</span>
            <span>&#8369;{{ number_format($item->subtotal, 2) }}</span>
        </div>
        @endforeach

        <div class="r-dash1"></div>

        {{-- Totals --}}
        <table class="r-table">
            <tr><td class="r-label">Subtotal</td><td class="r-val">&#8369;{{ number_format($order->subtotal, 2) }}</td></tr>
            @if($psDiscount > 0)
            <tr><td class="r-label">{{ $psDiscountLabel }}</td><td class="r-val">&minus;&#8369;{{ number_format($psDiscount, 2) }}</td></tr>
            @if($psIsPrivilege)
            <tr><td class="r-label">VAT Exempt</td><td class="r-val">&minus;&#8369;{{ number_format($order->tax, 2) }}</td></tr>
            @endif
            @endif
            @if(!$psIsPrivilege)
            <tr><td class="r-label">VAT (12%)</td><td class="r-val">&#8369;{{ number_format($order->tax, 2) }}</td></tr>
            @endif
        </table>

        <div class="r-dash2"></div>
        <div class="r-total">
            <span>TOTAL PAID</span>
            <span>{{ $order->formatted_total }}</span>
        </div>

        @if($psDiscount > 0)
        <div class="r-savings">You saved &#8369;{{ number_format($psDiscount + ($psIsPrivilege ? $order->tax : 0), 2) }}</div>
        @endif

        <div class="r-dash2" style="margin-top:14px;"></div>

        {{-- Footer --}}
        <div class="r-center" style="margin-top:12px;">
            <div class="r-bold">Thank you for dining with us!</div>
            <div class="r-sm" style="margin-top:4px;">Please come again!</div>
            <div class="r-xs" style="margin-top:10px;">Printed: {{ now()->timezone('Asia/Manila')->format('M d, Y h:i A') }}</div>
            <div class="r-xs">This serves as your official receipt.</div>
        </div>

    </div>
</div>

<style>
    #print-receipt { display: none; }

    @media print {
        @page { size: A4 portrait; margin: 15mm; }
        .receipt-wrap { font-size: 14px !important; width: 480px !important; }
        .r-logo  { font-size: 26px !important; }
        .r-total { font-size: 18px !important; }
        .r-sm    { font-size: 12px !important; }
        .r-xs    { font-size: 11px !important; }
    }

    .receipt-wrap {
        font-family: 'Courier New', Courier, monospace;
        font-size: 13px;
        line-height: 1.75;
        width: 480px;
        max-width: 100%;
        margin: 0 auto;
        padding: 28px 24px;
        color: #111;
    }
    .r-logo   { font-size: 24px; font-weight: 900; letter-spacing: 4px; }
    .r-bold   { font-weight: bold; }
    .r-center { text-align: center; }
    .r-sm     { font-size: 12px; color: #444; }
    .r-xs     { font-size: 11px; color: #888; }
    .r-mono   { font-family: 'Courier New', monospace; font-size: 12px; }
    .r-dash1  { border-top: 1px dashed #555; margin: 10px 0; }
    .r-dash2  { border-top: 2px dashed #222; margin: 10px 0; }
    .r-table  { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
    .r-label  { color: #555; width: 42%; padding: 2px 0; }
    .r-val    { text-align: right; padding: 2px 0; }
    .r-item   { display: flex; justify-content: space-between; margin-bottom: 5px; }
    .r-note   { font-size: 12px; color: #666; padding-left: 14px; margin-bottom: 5px; }
    .r-total  { display: flex; justify-content: space-between; font-weight: 900; font-size: 16px; margin: 5px 0; }
    .r-savings{ text-align: right; font-size: 12px; color: #444; }
</style>

<script>
(function () {
    var _parent, _next;

    window.addEventListener('beforeprint', function () {
        var el = document.getElementById('print-receipt');
        if (!el) return;
        _parent = el.parentNode;
        _next   = el.nextSibling;
        document.body.appendChild(el);
        el.style.display = 'block';
        Array.from(document.body.children).forEach(function (c) {
            if (c !== el) {
                c.dataset.printSave = c.style.cssText;
                c.style.setProperty('display', 'none', 'important');
            }
        });
    });

    window.addEventListener('afterprint', function () {
        var el = document.getElementById('print-receipt');
        if (!el) return;
        if (_parent) { _next ? _parent.insertBefore(el, _next) : _parent.appendChild(el); }
        el.style.display = '';
        _parent = _next = null;
        Array.from(document.body.children).forEach(function (c) {
            if (c.dataset.printSave !== undefined) {
                c.style.cssText = c.dataset.printSave;
                delete c.dataset.printSave;
            }
        });
    });
}());
</script>
@endsection