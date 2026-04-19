@extends('client.layouts.home')

@section('title', 'Transaction Details')

@section('content')
<div class="max-w-4xl mx-auto mt-24 md:mt-32 px-4 mb-20">
    
    <!-- Header with back button -->
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('client.transactions.index') }}" class="p-2 bg-white rounded-lg shadow hover:shadow-md transition-all">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div class="bg-[#ea5a47] p-3 rounded-2xl shadow-lg">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </div>
        <h1 class="text-2xl sm:text-4xl font-black text-gray-800">Transaction <span class="text-[#ea5a47]">Details</span></h1>
    </div>

    <!-- Transaction Details Card -->
    <div class="bg-white rounded-3xl shadow-2xl p-8">
        <!-- Header with Transaction Number and Original Order -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 pb-4 border-b border-gray-200">
            <div>
                <p class="text-sm text-gray-500 mb-1">Transaction Number</p>
                <p class="text-2xl font-bold text-[#ea5a47]">{{ $transaction->transaction_number }}</p>
                <p class="text-sm text-gray-400 mt-1">
                    Original Order: 
                    <a href="{{ route('client.orders.show', $transaction->order_number) }}" class="text-[#ea5a47] hover:underline font-medium">
                        #{{ $transaction->order_number }}
                    </a>
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <span class="px-4 py-2 rounded-full text-sm font-bold
                    @if($transaction->payment_method == 'cash') bg-orange-100 text-orange-700
                    @elseif($transaction->payment_method == 'gcash') bg-blue-100 text-blue-700
                    @else bg-purple-100 text-purple-700
                    @endif">
                    {{ strtoupper($transaction->payment_method) }}
                </span>
                @if($transaction->payment_status === 'refunded')
                    <span class="px-4 py-2 rounded-full text-sm font-bold bg-red-100 text-red-700">
                        ↩ REFUNDED
                    </span>
                @elseif($transaction->payment_status === 'partial_refund')
                    <span class="px-4 py-2 rounded-full text-sm font-bold bg-orange-100 text-orange-700">
                        ↩ PARTIALLY REFUNDED
                    </span>
                @else
                    <span class="px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-700">
                        PAID
                    </span>
                @endif
            </div>
        </div>

        <!-- Customer Info -->
        <div class="mb-6 p-4 bg-gray-50 rounded-xl">
            <h3 class="font-bold text-gray-700 mb-3">Customer Information</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Name</p>
                    <p class="font-medium">{{ $transaction->customer_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-medium">{{ $transaction->customer_email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Phone</p>
                    <p class="font-medium">{{ $transaction->customer_phone ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Transaction Date</p>
                    <p class="font-medium">{{ $transaction->transaction_date->format('M d, Y h:i A') }}</p>
                </div>
                @if($transaction->reference_number)
                <div>
                    <p class="text-sm text-gray-500">Reference Number</p>
                    <p class="font-mono text-sm">{{ $transaction->reference_number }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Order Items -->
        <h3 class="font-bold text-gray-700 mb-4">Order Items</h3>
        <div class="space-y-3 mb-6">
            @foreach($transaction->items as $item)
            <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                <div>
                    <p class="font-medium">{{ $item->item_name }}</p>
                    <p class="text-sm text-gray-500">{{ $item->quantity }} x ₱{{ number_format($item->price, 2) }}</p>
                </div>
                <p class="font-bold text-[#ea5a47]">₱{{ number_format($item->subtotal, 2) }}</p>
            </div>
            @endforeach
        </div>

        <!-- Totals -->
        @php
            $txOrder         = $transaction->order;
            $txPromoDiscount = $txOrder?->promo_discount ?? 0;
            $txPromoLabel    = $txOrder?->promo_label;
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
            $txTotalSaved = $txPromoDiscount + $txDiscount + ($txIsPrivilege ? $transaction->tax : 0);
        @endphp
        <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Subtotal:</span>
                    <span>₱{{ number_format($transaction->subtotal, 2) }}</span>
                </div>

                @if($txPromoDiscount > 0)
                <div class="flex justify-between text-sm items-center">
                    <span class="text-green-700 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Promo{{ $txPromoLabel ? ': ' . $txPromoLabel : '' }}
                    </span>
                    <span class="font-semibold text-green-600">− ₱{{ number_format($txPromoDiscount, 2) }}</span>
                </div>
                @endif

                @if($txDiscount > 0)
                <div class="flex justify-between text-sm items-center">
                    <span class="text-green-700 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        {{ $txDiscountTypeLabel }}
                    </span>
                    <span class="font-semibold text-green-600">− ₱{{ number_format($txDiscount, 2) }}</span>
                </div>
                @if($txIsPrivilege)
                <div class="flex justify-between text-sm">
                    <span class="text-green-700">VAT Exempt</span>
                    <span class="font-semibold text-green-600">− ₱{{ number_format($transaction->tax, 2) }}</span>
                </div>
                @endif
                @endif

                @if(!$txIsPrivilege)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">VAT (12%):</span>
                    <span>₱{{ number_format($transaction->tax, 2) }}</span>
                </div>
                @endif

                <div class="flex justify-between font-bold text-lg pt-2 border-t border-gray-100">
                    <span class="text-gray-800">Total Paid:</span>
                    <span class="text-[#ea5a47]">{{ $transaction->formatted_total }}</span>
                </div>

                @if($txTotalSaved > 0)
                <div class="flex justify-end">
                    <span class="text-xs text-green-600 bg-green-50 border border-green-200 px-2 py-1 rounded-full font-medium">
                        You saved ₱{{ number_format($txTotalSaved, 2) }}
                    </span>
                </div>
                @endif
                @if($transaction->isRefunded() && $transaction->refund_amount)
                <div class="flex justify-between text-sm text-red-600 font-semibold">
                    <span>Amount Refunded:</span>
                    <span>−₱{{ number_format($transaction->refund_amount, 2) }}</span>
                </div>
                <div class="flex justify-between font-bold text-lg border-t border-gray-200 pt-2">
                    <span class="text-gray-800">Net Charged:</span>
                    <span class="text-gray-800">₱{{ number_format($transaction->total - $transaction->refund_amount, 2) }}</span>
                </div>
                @endif
            </div>
        </div>

        @if($transaction->isRefunded())
        <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-xl">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                </svg>
                <div>
                    <p class="font-semibold text-red-800 text-sm">
                        {{ $transaction->payment_status === 'partial_refund' ? 'Partial Refund Issued' : 'Refund Issued' }}
                    </p>
                    @if($transaction->refund_reason)
                        <p class="text-sm text-red-700 mt-1">Reason: {{ $transaction->refund_reason }}</p>
                    @endif
                    @if($transaction->refunded_at)
                        <p class="text-xs text-red-500 mt-1">
                            Processed on {{ $transaction->refunded_at->format('M d, Y \a\t h:i A') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if($transaction->notes)
        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-600"><span class="font-medium">Notes:</span> {{ $transaction->notes }}</p>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-end">
            <a href="{{ route('client.transactions.download', $transaction->transaction_number) }}"
               data-no-loader
               class="px-6 py-3 bg-[#ea5a47] text-white font-semibold rounded-xl hover:bg-[#c53030] transition-all flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download Receipt
            </a>
            <button onclick="window.print()"
                    class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-300 transition-all flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print
            </button>
            <a href="{{ route('client.transactions.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-all text-center">
                Back to Transactions
            </a>
        </div>
    </div>
</div>

{{-- Hidden print receipt --}}
<div id="print-receipt">
    <div class="receipt-wrap">

        {{-- Header --}}
        <div class="r-center" style="margin-bottom:14px;">
            <div class="r-logo">2DINE-IN</div>
            <div class="r-sm">San Juan Bautista, Goa, Camarines Sur</div>
            <div class="r-sm">(054) 123 4567 &bull; Open Daily 10AM–10PM</div>
        </div>

        <div class="r-dash2"></div>
        <div class="r-center r-bold" style="letter-spacing:2px; margin:8px 0;">OFFICIAL RECEIPT</div>
        <div class="r-dash1"></div>

        {{-- Transaction info --}}
        <table class="r-table">
            <tr><td class="r-label">Date</td><td class="r-val">{{ $transaction->transaction_date->timezone('Asia/Manila')->format('M d, Y h:i A') }}</td></tr>
            <tr><td class="r-label">Trans. #</td><td class="r-val r-bold">{{ $transaction->transaction_number }}</td></tr>
            <tr><td class="r-label">Order #</td><td class="r-val">{{ $transaction->order_number }}</td></tr>
            <tr><td class="r-label">Customer</td><td class="r-val">{{ $transaction->customer_name }}</td></tr>
            <tr><td class="r-label">Payment</td><td class="r-val">{{ ucwords(str_replace('_', ' ', $transaction->payment_method)) }}</td></tr>
            @if($transaction->reference_number)
            <tr><td class="r-label">Ref #</td><td class="r-val r-mono">{{ $transaction->reference_number }}</td></tr>
            @endif
            <tr><td class="r-label">Status</td><td class="r-val r-bold">{{ strtoupper($transaction->payment_status) }}</td></tr>
        </table>

        <div class="r-dash1"></div>
        <div class="r-bold" style="margin-bottom:8px;">ORDERED ITEMS</div>

        @foreach($transaction->items as $item)
        <div class="r-item">
            <span>{{ $item->quantity }}x {{ $item->item_name }}</span>
            <span>&#8369;{{ number_format($item->subtotal, 2) }}</span>
        </div>
        @endforeach

        <div class="r-dash1"></div>

        {{-- Totals --}}
        <table class="r-table">
            <tr><td class="r-label">Subtotal</td><td class="r-val">&#8369;{{ number_format($transaction->subtotal, 2) }}</td></tr>
            @if($txDiscount > 0)
            <tr><td class="r-label">{{ $txDiscountTypeLabel }}</td><td class="r-val">&minus;&#8369;{{ number_format($txDiscount, 2) }}</td></tr>
            @if($txIsPrivilege)
            <tr><td class="r-label">VAT Exempt</td><td class="r-val">&minus;&#8369;{{ number_format($transaction->tax, 2) }}</td></tr>
            @endif
            @endif
            @if(!$txIsPrivilege)
            <tr><td class="r-label">VAT (12%)</td><td class="r-val">&#8369;{{ number_format($transaction->tax, 2) }}</td></tr>
            @endif
        </table>

        <div class="r-dash2"></div>
        <div class="r-total">
            <span>TOTAL PAID</span>
            <span>{{ $transaction->formatted_total }}</span>
        </div>

        @if($txDiscount > 0)
        <div class="r-savings">You saved &#8369;{{ number_format($txDiscount + ($txIsPrivilege ? $transaction->tax : 0), 2) }}</div>
        @endif

        @if($transaction->isRefunded() && $transaction->refund_amount)
        <div class="r-dash1"></div>
        <table class="r-table">
            <tr><td class="r-label">Refunded</td><td class="r-val">&minus;&#8369;{{ number_format($transaction->refund_amount, 2) }}</td></tr>
            <tr class="r-bold"><td class="r-label">Net Charged</td><td class="r-val">&#8369;{{ number_format($transaction->total - $transaction->refund_amount, 2) }}</td></tr>
        </table>
        @if($transaction->refund_reason)
        <div class="r-note">Refund reason: {{ $transaction->refund_reason }}</div>
        @endif
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