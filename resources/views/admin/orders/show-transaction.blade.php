@extends('admin.layouts.home', ['active' => 'transactions'])

@section('title', 'Transaction Details')

@section('content')
<div class="relative min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] py-12 px-4 sm:px-6 lg:px-8">
    <div class="relative z-10 max-w-4xl mx-auto">
        
        <!-- Simple Header -->
        <div class="flex flex-wrap items-center gap-3 mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.orders.transactions') }}" 
                   class="p-2 bg-white rounded-lg shadow hover:shadow-md transition-all">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div class="bg-[#ea5a47] p-2 rounded-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h1 class="text-lg sm:text-2xl font-bold text-gray-800">Transaction Details</h1>
            </div>
        </div>

        <!-- Transaction Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            
            <!-- Transaction Header -->
            <div class="bg-gradient-to-r from-[#ea5a47] to-[#c53030] px-6 py-4">
                <div class="flex flex-wrap justify-between items-center gap-3">
                    <div>
                        <p class="text-white/80 text-xs">Transaction Number</p>
                        <p class="text-xl font-bold text-white">{{ $transaction->transaction_number }}</p>
                        <p class="text-white/70 text-xs mt-1">
                            Order: 
                            <span class="text-white">
                                {{ $transaction->order_number }}
                            </span>
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-white/20 text-white">
                            {{ strtoupper($transaction->payment_method) }}
                        </span>
                        @php
                            $statusConfig = match($transaction->payment_status) {
                                'paid'           => ['bg-green-500 text-white', 'PAID'],
                                'refunded'       => ['bg-purple-500 text-white', 'REFUNDED'],
                                'partial_refund' => ['bg-orange-400 text-white', 'PARTIAL REFUND'],
                                'pending'        => ['bg-yellow-400 text-white', 'PENDING'],
                                'failed'         => ['bg-red-500 text-white', 'FAILED'],
                                default          => ['bg-gray-400 text-white', strtoupper($transaction->payment_status)],
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusConfig[0] }}">
                            {{ $statusConfig[1] }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6 space-y-6">
                
                <!-- Customer Info - Simple Grid -->
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm mb-3">Customer Information</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-gray-500 text-xs">Name</p>
                            <p class="font-medium text-gray-800">{{ $transaction->customer_name }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-gray-500 text-xs">Email</p>
                            <p class="font-medium text-gray-800">{{ $transaction->customer_email }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-gray-500 text-xs">Phone</p>
                            <p class="font-medium text-gray-800">{{ $transaction->customer_phone ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-gray-500 text-xs">Date</p>
                            <p class="font-medium text-gray-800">{{ $transaction->transaction_date->format('M d, Y h:i A') }}</p>
                        </div>
                        @if($transaction->reference_number)
                        <div class="bg-gray-50 p-3 rounded-lg sm:col-span-2">
                            <p class="text-gray-500 text-xs">Reference Number</p>
                            <p class="font-mono text-sm text-gray-800">{{ $transaction->reference_number }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Order Items - Simple Table -->
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm mb-3">Order Items ({{ $transaction->items->count() }})</h3>
                    <div class="border rounded-lg overflow-hidden">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr class="border-b">
                                    <th class="px-3 py-2 text-left text-gray-600 text-xs">Item</th>
                                    <th class="px-3 py-2 text-center text-gray-600 text-xs w-16">Qty</th>
                                    <th class="px-3 py-2 text-right text-gray-600 text-xs w-24">Price</th>
                                    <th class="px-3 py-2 text-right text-gray-600 text-xs w-24">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($transaction->items as $item)
                                <tr>
                                    <td class="px-3 py-2">
                                        <span class="font-medium">{{ $item->item_name }}</span>
                                    </td>
                                    <td class="px-3 py-2 text-center">{{ $item->quantity }}</td>
                                    <td class="px-3 py-2 text-right">₱{{ number_format($item->price, 2) }}</td>
                                    <td class="px-3 py-2 text-right font-medium text-[#ea5a47]">₱{{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
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
                            <tfoot class="bg-gray-50 border-t">
                                <tr>
                                    <td colspan="3" class="px-3 py-2 text-right font-medium">Subtotal:</td>
                                    <td class="px-3 py-2 text-right">₱{{ number_format($transaction->subtotal, 2) }}</td>
                                </tr>
                                @if($txPromoDiscount > 0)
                                <tr>
                                    <td colspan="3" class="px-3 py-2 text-right font-medium text-green-700">
                                        <span class="flex items-center justify-end gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                            Promo{{ $txPromoLabel ? ': ' . $txPromoLabel : '' }}:
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 text-right font-semibold text-green-600">− ₱{{ number_format($txPromoDiscount, 2) }}</td>
                                </tr>
                                @endif
                                @if($txDiscount > 0)
                                <tr>
                                    <td colspan="3" class="px-3 py-2 text-right font-medium text-green-700">
                                        <span class="flex items-center justify-end gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                            {{ $txDiscountTypeLabel }}:
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 text-right font-semibold text-green-600">− ₱{{ number_format($txDiscount, 2) }}</td>
                                </tr>
                                @if($txIsPrivilege)
                                <tr>
                                    <td colspan="3" class="px-3 py-2 text-right font-medium text-green-700">VAT Exempt:</td>
                                    <td class="px-3 py-2 text-right font-semibold text-green-600">− ₱{{ number_format($transaction->tax, 2) }}</td>
                                </tr>
                                @endif
                                @endif
                                @if(!$txIsPrivilege)
                                <tr>
                                    <td colspan="3" class="px-3 py-2 text-right font-medium">Tax (12%):</td>
                                    <td class="px-3 py-2 text-right">₱{{ number_format($transaction->tax, 2) }}</td>
                                </tr>
                                @endif
                                <tr class="border-t border-gray-200">
                                    <td colspan="3" class="px-3 py-2 text-right font-bold">Total:</td>
                                    <td class="px-3 py-2 text-right font-bold text-[#ea5a47]">{{ $transaction->formatted_total }}</td>
                                </tr>
                                @if($txTotalSaved > 0)
                                <tr>
                                    <td colspan="4" class="px-3 py-1.5 text-right">
                                        <span class="text-xs text-green-600 bg-green-50 border border-green-200 px-2 py-0.5 rounded-full font-medium">
                                            Saved ₱{{ number_format($txTotalSaved, 2) }}
                                        </span>
                                    </td>
                                </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Notes -->
                @if($transaction->notes)
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-3 rounded">
                    <p class="text-sm text-yellow-800">
                        <span class="font-medium">Notes:</span> {{ $transaction->notes }}
                    </p>
                </div>
                @endif

                <!-- Refund Info -->
                @if($transaction->isRefunded())
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <h3 class="font-semibold text-purple-800 text-sm mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>
                        Refund Details
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div class="bg-white p-3 rounded-lg border border-purple-100">
                            <p class="text-purple-500 text-xs">Refund Amount</p>
                            <p class="font-bold text-purple-700 text-lg">₱{{ number_format($transaction->refund_amount, 2) }}</p>
                        </div>
                        <div class="bg-white p-3 rounded-lg border border-purple-100">
                            <p class="text-purple-500 text-xs">Refunded At</p>
                            <p class="font-medium text-gray-800">{{ $transaction->refunded_at?->format('M d, Y h:i A') ?? '—' }}</p>
                        </div>
                        @if($transaction->refundedByAdmin)
                        <div class="bg-white p-3 rounded-lg border border-purple-100">
                            <p class="text-purple-500 text-xs">Processed By</p>
                            <p class="font-medium text-gray-800">{{ $transaction->refundedByAdmin->name }}</p>
                        </div>
                        @endif
                        <div class="bg-white p-3 rounded-lg border border-purple-100 {{ $transaction->refundedByAdmin ? '' : 'sm:col-span-1' }}">
                            <p class="text-purple-500 text-xs">Type</p>
                            <p class="font-medium text-gray-800">
                                {{ $transaction->payment_status === 'partial_refund' ? 'Partial Refund' : 'Full Refund' }}
                            </p>
                        </div>
                        <div class="bg-white p-3 rounded-lg border border-purple-100 sm:col-span-2">
                            <p class="text-purple-500 text-xs">Reason</p>
                            <p class="text-gray-800">{{ $transaction->refund_reason }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex gap-3 justify-end pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.orders.transactions') }}"
                       class="px-4 py-2 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700 transition-all">
                        Back to List
                    </a>
                    @if($transaction->isRefundable())
                    <button onclick="openRefundModal(
                        '{{ $transaction->transaction_number }}',
                        '{{ $transaction->order_number }}',
                        '{{ $transaction->customer_name }}',
                        {{ $transaction->total }},
                        '{{ $transaction->payment_method }}',
                        '{{ $transaction->transaction_date->format('M d, Y') }}',
                        '{{ route('admin.transactions.refund', $transaction) }}'
                    )"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>
                        Process Refund
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== REFUND MODAL ===== -->
<div id="refundModal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeRefundModal()"></div>

    <!-- Dialog -->
    <div class="relative z-10 flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">

            <!-- Step 1: Enter Refund Details -->
            <div id="step1">
                <div class="bg-gradient-to-r from-indigo-600 to-indigo-500 px-6 py-4">
                    <h2 class="text-lg font-bold text-white">Process Refund</h2>
                    <p class="text-indigo-200 text-xs mt-0.5">Step 1 of 2 — Enter refund details</p>
                </div>

                <div class="p-6 space-y-4">
                    <!-- Transaction Summary -->
                    <div class="bg-gray-50 rounded-xl p-4 text-sm space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Transaction</span>
                            <span id="s1TxnNum" class="font-mono font-medium text-gray-800"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Order</span>
                            <span id="s1OrderNum" class="font-medium text-gray-800"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Customer</span>
                            <span id="s1Customer" class="font-medium text-gray-800"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Payment</span>
                            <span id="s1Method" class="font-medium text-gray-800 uppercase"></span>
                        </div>
                        <div class="flex justify-between border-t pt-2 mt-2">
                            <span class="font-semibold text-gray-700">Total Paid</span>
                            <span id="s1Total" class="font-bold text-[#ea5a47] text-base"></span>
                        </div>
                    </div>

                    <!-- Refund Amount -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="text-sm font-medium text-gray-700">Refund Amount (₱)</label>
                            <button type="button" onclick="setFullRefund()"
                                class="text-xs text-indigo-600 hover:text-indigo-800 font-medium underline underline-offset-2">
                                Full refund
                            </button>
                        </div>
                        <input type="number" id="refundAmountInput" step="0.01" min="1"
                               placeholder="0.00"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent"
                               oninput="clearError('amountError')">
                        <p id="amountError" class="text-xs text-red-600 mt-1 hidden"></p>
                    </div>

                    <!-- Reason -->
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-1 block">Reason for Refund</label>
                        <textarea id="refundReasonInput" rows="3" maxlength="1000"
                                  placeholder="Explain why this refund is being processed (min. 10 characters)..."
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent resize-none"
                                  oninput="updateCharCount(); clearError('reasonError')"></textarea>
                        <div class="flex justify-between mt-0.5">
                            <p id="reasonError" class="text-xs text-red-600 hidden"></p>
                            <p class="text-xs text-gray-400 ml-auto"><span id="charCount">0</span>/1000</p>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3 pt-2">
                        <button onclick="closeRefundModal()"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50 transition-all">
                            Cancel
                        </button>
                        <button onclick="goToConfirmStep()"
                            class="flex-1 px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition-all font-medium">
                            Review Refund →
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 2: Confirm -->
            <div id="step2" class="hidden">
                <div class="bg-gradient-to-r from-red-600 to-red-500 px-6 py-4">
                    <h2 class="text-lg font-bold text-white">Confirm Refund</h2>
                    <p class="text-red-200 text-xs mt-0.5">Step 2 of 2 — Review and confirm</p>
                </div>

                <div class="p-6 space-y-4">
                    <!-- Warning -->
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex gap-3">
                        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.538-1.333-3.308 0L3.732 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-red-800">This action cannot be undone</p>
                            <p class="text-xs text-red-600 mt-0.5">Once processed, this refund will be permanently recorded.</p>
                        </div>
                    </div>

                    <!-- Refund Summary -->
                    <div class="bg-gray-50 rounded-xl p-4 text-sm space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Transaction</span>
                            <span id="s2TxnNum" class="font-mono font-medium text-gray-800"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Customer</span>
                            <span id="s2Customer" class="font-medium text-gray-800"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Total Paid</span>
                            <span id="s2Total" class="font-medium text-gray-800"></span>
                        </div>
                        <div class="flex justify-between border-t pt-2 mt-2">
                            <span class="font-semibold text-red-700">Refund Amount</span>
                            <span id="s2RefundAmt" class="font-bold text-red-700 text-base"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Type</span>
                            <span id="s2RefundType" class="font-medium text-gray-800"></span>
                        </div>
                        <div class="border-t pt-2 mt-2">
                            <p class="text-gray-500 text-xs mb-1">Reason</p>
                            <p id="s2Reason" class="text-gray-800 text-xs leading-relaxed"></p>
                        </div>
                    </div>

                    <!-- Checkbox -->
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" id="confirmCheckbox" onchange="toggleSubmit()"
                               class="mt-0.5 w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <span class="text-sm text-gray-700">I confirm that I want to process this refund and understand it cannot be reversed.</span>
                    </label>

                    <!-- Hidden Form -->
                    <form id="refundForm" method="POST" action="">
                        @csrf
                        <input type="hidden" name="refund_amount" id="hiddenRefundAmount">
                        <input type="hidden" name="refund_reason" id="hiddenRefundReason">
                    </form>

                    <!-- Buttons -->
                    <div class="flex gap-3 pt-2">
                        <button onclick="goBackToStep1()"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50 transition-all">
                            ← Back
                        </button>
                        <button id="submitRefundBtn" onclick="submitRefund()" disabled
                            class="flex-1 px-4 py-2 bg-red-600 text-white text-sm rounded-lg transition-all font-medium opacity-50 cursor-not-allowed">
                            Confirm Refund
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    let _maxRefund = 0;
    let _refundRoute = '';

    function openRefundModal(txnNum, orderNum, customer, total, method, date, route) {
        _maxRefund   = parseFloat(total);
        _refundRoute = route;

        document.getElementById('s1TxnNum').textContent   = txnNum;
        document.getElementById('s1OrderNum').textContent  = orderNum;
        document.getElementById('s1Customer').textContent  = customer;
        document.getElementById('s1Method').textContent    = method;
        document.getElementById('s1Total').textContent     = '₱' + parseFloat(total).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        document.getElementById('refundAmountInput').value = '';
        document.getElementById('refundAmountInput').max   = total;
        document.getElementById('refundReasonInput').value = '';
        document.getElementById('charCount').textContent   = '0';
        clearError('amountError');
        clearError('reasonError');

        document.getElementById('step1').classList.remove('hidden');
        document.getElementById('step2').classList.add('hidden');
        document.getElementById('refundModal').classList.remove('hidden');
    }

    function closeRefundModal() {
        document.getElementById('refundModal').classList.add('hidden');
    }

    function setFullRefund() {
        document.getElementById('refundAmountInput').value = _maxRefund.toFixed(2);
        clearError('amountError');
    }

    function updateCharCount() {
        document.getElementById('charCount').textContent =
            document.getElementById('refundReasonInput').value.length;
    }

    function clearError(id) {
        const el = document.getElementById(id);
        el.textContent = '';
        el.classList.add('hidden');
    }

    function showError(id, msg) {
        const el = document.getElementById(id);
        el.textContent = msg;
        el.classList.remove('hidden');
    }

    function goToConfirmStep() {
        const amt    = parseFloat(document.getElementById('refundAmountInput').value);
        const reason = document.getElementById('refundReasonInput').value.trim();
        let valid = true;

        if (!amt || amt < 1) {
            showError('amountError', 'Please enter a refund amount of at least ₱1.00.');
            valid = false;
        } else if (amt > _maxRefund) {
            showError('amountError', `Refund cannot exceed ₱${_maxRefund.toFixed(2)}.`);
            valid = false;
        }

        if (reason.length < 10) {
            showError('reasonError', 'Please provide a reason with at least 10 characters.');
            valid = false;
        }

        if (!valid) return;

        const isPartial = amt < _maxRefund;
        document.getElementById('s2TxnNum').textContent    = document.getElementById('s1TxnNum').textContent;
        document.getElementById('s2Customer').textContent  = document.getElementById('s1Customer').textContent;
        document.getElementById('s2Total').textContent     = document.getElementById('s1Total').textContent;
        document.getElementById('s2RefundAmt').textContent = '₱' + amt.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        document.getElementById('s2RefundType').textContent = isPartial ? 'Partial Refund' : 'Full Refund';
        document.getElementById('s2Reason').textContent   = reason;

        document.getElementById('hiddenRefundAmount').value = amt.toFixed(2);
        document.getElementById('hiddenRefundReason').value = reason;
        document.getElementById('refundForm').action        = _refundRoute;

        document.getElementById('confirmCheckbox').checked  = false;
        document.getElementById('submitRefundBtn').disabled = true;
        document.getElementById('submitRefundBtn').classList.add('opacity-50', 'cursor-not-allowed');

        document.getElementById('step1').classList.add('hidden');
        document.getElementById('step2').classList.remove('hidden');
    }

    function goBackToStep1() {
        document.getElementById('step1').classList.remove('hidden');
        document.getElementById('step2').classList.add('hidden');
    }

    function toggleSubmit() {
        const checked = document.getElementById('confirmCheckbox').checked;
        const btn     = document.getElementById('submitRefundBtn');
        btn.disabled  = !checked;
        btn.classList.toggle('opacity-50', !checked);
        btn.classList.toggle('cursor-not-allowed', !checked);
    }

    function submitRefund() {
        const btn = document.getElementById('submitRefundBtn');
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin w-4 h-4 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>';
        document.getElementById('refundForm').submit();
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeRefundModal();
    });
</script>

<style>
    /* Custom scrollbar */
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }
    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #ea5a47;
        border-radius: 3px;
    }
    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #c53030;
    }
    
    @media print {
        body * {
            visibility: hidden;
        }
        .bg-white, .bg-white * {
            visibility: visible;
        }
        .bg-white {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 20px;
        }
        .bg-gradient-to-r {
            background: #ea5a47;
        }
        button, a, .flex.justify-end {
            display: none !important;
        }
        .shadow-lg, .shadow-xl {
            box-shadow: none;
        }
    }
</style>
@endsection