@extends('admin.layouts.home', ['active' => 'transactions'])

@section('title', 'Transaction History')

@section('content')
<div class="relative min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] overflow-hidden py-12 px-4 sm:px-6 lg:px-8">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-20 -right-20 w-96 h-96 bg-[#ea5a47] opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-[#c53030] opacity-5 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="absolute inset-0 bg-[#ea5a47] rounded-lg blur-md opacity-30"></div>
                    <div class="relative bg-gradient-to-br from-[#ea5a47] to-[#c53030] p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <h1 class="text-xl sm:text-3xl font-black text-gray-800">Transaction <span class="text-[#ea5a47]">History</span></h1>
            </div>
            <span class="text-sm text-gray-500">
                Total: <span class="font-bold text-[#ea5a47]">{{ $transactions->total() }}</span> transactions
            </span>
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Stats row --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-white/95 rounded-2xl shadow p-5 border border-white/20 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Total Sales</p>
                    <p class="text-xl font-black text-gray-800">₱{{ number_format($totalSales, 2) }}</p>
                </div>
            </div>
            <div class="bg-white/95 rounded-2xl shadow p-5 border border-white/20 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Total Refunded</p>
                    <p class="text-xl font-black text-gray-800">₱{{ number_format($totalRefunded, 2) }}</p>
                </div>
            </div>
            <div class="bg-white/95 rounded-2xl shadow p-5 border border-white/20 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Avg. Order Value</p>
                    <p class="text-xl font-black text-gray-800">₱{{ number_format($averageOrderValue, 2) }}</p>
                </div>
            </div>
        </div>

        {{-- Filter Card --}}
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl p-6 border border-white/20 mb-6">
            <form method="GET" action="{{ route('admin.orders.transactions') }}" id="filter-form">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-gray-700 text-xs font-semibold mb-1 uppercase tracking-wide">From Date</label>
                        <input type="date" name="from_date" value="{{ request('from_date') }}"
                               class="w-full px-3 py-2 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white outline-none text-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-xs font-semibold mb-1 uppercase tracking-wide">To Date</label>
                        <input type="date" name="to_date" value="{{ request('to_date') }}"
                               class="w-full px-3 py-2 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white outline-none text-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-xs font-semibold mb-1 uppercase tracking-wide">Status</label>
                        <select name="status"
                                class="w-full px-3 py-2 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white outline-none text-sm transition-all">
                            <option value="">All Statuses</option>
                            <option value="paid"          {{ request('status') === 'paid'           ? 'selected' : '' }}>Paid</option>
                            <option value="refunded"      {{ request('status') === 'refunded'       ? 'selected' : '' }}>Refunded</option>
                            <option value="partial_refund"{{ request('status') === 'partial_refund' ? 'selected' : '' }}>Partial Refund</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 text-xs font-semibold mb-1 uppercase tracking-wide">Search</label>
                        <div class="flex flex-wrap gap-2">
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Transaction #, Order #, customer…"
                                   class="flex-1 min-w-0 px-3 py-2 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white outline-none text-sm transition-all">
                            <button type="submit"
                                    class="px-4 py-2 bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white text-sm font-semibold rounded-xl hover:shadow-lg transition-all whitespace-nowrap">
                                Search
                            </button>
                            <a href="{{ route('admin.orders.transactions') }}"
                               class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-300 transition-all whitespace-nowrap">
                                Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Transactions Table --}}
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 relative overflow-hidden">
            <div class="relative z-10 overflow-x-auto">
                <table class="w-full min-w-[480px] text-sm text-left text-gray-700">
                    <thead class="text-xs uppercase bg-gray-50/80 border-b-2 border-gray-200 sticky top-0 z-10">
                        <tr>
                            <th class="px-5 py-4 bg-gray-50/95">Transaction #</th>
                            <th class="px-5 py-4 bg-gray-50/95 hidden sm:table-cell">Order #</th>
                            <th class="px-5 py-4 bg-gray-50/95">Customer</th>
                            <th class="px-5 py-4 bg-gray-50/95 hidden sm:table-cell">Items</th>
                            <th class="px-5 py-4 bg-gray-50/95">Total</th>
                            <th class="px-5 py-4 bg-gray-50/95 hidden sm:table-cell">Method</th>
                            <th class="px-5 py-4 bg-gray-50/95">Status</th>
                            <th class="px-5 py-4 bg-gray-50/95 hidden sm:table-cell">Date</th>
                            <th class="px-5 py-4 bg-gray-50/95">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                            <td class="px-5 py-4 font-mono text-sm font-bold text-[#ea5a47] whitespace-nowrap">
                                {{ $transaction->transaction_number }}
                            </td>
                            <td class="px-5 py-4 font-mono text-sm text-gray-600 whitespace-nowrap hidden sm:table-cell">
                                {{ $transaction->order_number }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-medium">{{ $transaction->customer_name }}</div>
                                @if($transaction->customer_phone)
                                    <div class="text-xs text-gray-500">{{ $transaction->customer_phone }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-4 hidden sm:table-cell">
                                <div class="text-sm">{{ $transaction->items->count() }} item(s)</div>
                                <div class="text-xs text-gray-400 truncate max-w-[160px]">
                                    {{ $transaction->items->pluck('item_name')->implode(', ') }}
                                </div>
                            </td>
                            <td class="px-5 py-4 font-bold text-gray-900 whitespace-nowrap">
                                {{ $transaction->formatted_total }}
                                @if($transaction->refund_amount)
                                    <div class="text-xs text-indigo-500 font-normal">
                                        Refunded: ₱{{ number_format($transaction->refund_amount, 2) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-4 hidden sm:table-cell">
                                @if($transaction->payment_method === 'cash')
                                    <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-medium whitespace-nowrap">💵 Cash</span>
                                @elseif($transaction->payment_method === 'gcash')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium whitespace-nowrap">📱 GCash</span>
                                @elseif($transaction->payment_method === 'card')
                                    <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium whitespace-nowrap">💳 Card</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium whitespace-nowrap">{{ ucfirst($transaction->payment_method) }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @if($transaction->payment_status === 'paid')
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold whitespace-nowrap">✅ Paid</span>
                                @elseif($transaction->payment_status === 'refunded')
                                    <span class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-semibold whitespace-nowrap">↩ Refunded</span>
                                @elseif($transaction->payment_status === 'partial_refund')
                                    <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold whitespace-nowrap">↩ Partial Refund</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold whitespace-nowrap">{{ ucfirst($transaction->payment_status) }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-xs text-gray-500 whitespace-nowrap hidden sm:table-cell">
                                @if($transaction->transaction_date)
                                    {{ $transaction->transaction_date->format('M d, Y') }}
                                    <div class="text-gray-400">{{ $transaction->transaction_date->format('h:i A') }}</div>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.transactions.show', $transaction->transaction_number) }}"
                                       class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 hover:scale-110 transition-all"
                                       title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    @if($transaction->isRefundable())
                                        <button type="button"
                                                onclick="openRefundModal(
                                                    {{ $transaction->id }},
                                                    '{{ $transaction->transaction_number }}',
                                                    '{{ $transaction->order_number }}',
                                                    '{{ addslashes($transaction->customer_name) }}',
                                                    {{ $transaction->total }},
                                                    '{{ ucfirst($transaction->payment_method) }}',
                                                    '{{ route('admin.transactions.refund', $transaction) }}'
                                                )"
                                                class="p-2 bg-indigo-100 text-indigo-600 rounded-lg hover:bg-indigo-200 hover:scale-110 transition-all"
                                                title="Process Refund">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <h5 class="text-xl font-bold text-gray-600 mb-2">No Transactions Found</h5>
                                    <p class="text-gray-500">No completed orders match your filters.</p>
                                    @if(request()->hasAny(['from_date','to_date','search','status']))
                                        <a href="{{ route('admin.orders.transactions') }}" class="mt-4 text-[#ea5a47] hover:underline">Clear filters</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-white/95">
                {{ $transactions->withQueryString()->links() }}
            </div>
            @endif
        </div>

    </div>
</div>

{{-- ==================== REFUND CONFIRMATION MODAL ==================== --}}
<div id="refundModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" id="refundBackdrop"></div>

        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg transform transition-all duration-300 scale-95 opacity-0"
             id="refundContainer">

            {{-- Red gradient top bar --}}
            <div class="absolute top-0 left-0 right-0 h-1.5 rounded-t-3xl bg-gradient-to-r from-indigo-500 to-purple-600"></div>

            {{-- Step 1: Fill in refund details --}}
            <div id="refundStep1" class="p-6">
                {{-- Header --}}
                <div class="flex items-start gap-4 mb-5">
                    <div class="w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 shadow">
                        <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-gray-800">Process Refund</h2>
                        <p class="text-sm text-gray-500 mt-0.5">Step 1 of 2 — Enter refund details</p>
                    </div>
                    <button type="button" onclick="closeRefundModal()"
                            class="ml-auto text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Transaction summary --}}
                <div class="bg-gray-50 rounded-2xl p-4 mb-5 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Transaction #</span>
                        <span id="r-txn-number" class="font-mono font-bold text-[#ea5a47]"></span>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-2">
                        <span class="text-gray-500">Order #</span>
                        <span id="r-order-number" class="font-mono text-gray-700"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Customer</span>
                        <span id="r-customer" class="font-medium text-gray-800"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Payment Method</span>
                        <span id="r-method" class="text-gray-700"></span>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-2">
                        <span class="text-gray-500">Amount Paid</span>
                        <span id="r-total" class="font-bold text-gray-900 text-base"></span>
                    </div>
                </div>

                {{-- Refund amount --}}
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Refund Amount <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                        <input type="number" id="r-amount" step="0.01" min="1"
                               class="w-full pl-7 pr-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-indigo-500 outline-none transition-all text-sm"
                               placeholder="0.00">
                    </div>
                    <div class="flex justify-between mt-1">
                        <span class="text-xs text-gray-400" id="r-amount-hint"></span>
                        <button type="button" onclick="setFullRefund()"
                                class="text-xs text-indigo-600 hover:underline font-medium">
                            Full refund
                        </button>
                    </div>
                    <p class="text-xs text-red-500 mt-1 hidden" id="r-amount-error"></p>
                </div>

                {{-- Reason --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Reason for Refund <span class="text-red-500">*</span>
                    </label>
                    <textarea id="r-reason" rows="3"
                              class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-indigo-500 outline-none transition-all text-sm resize-none"
                              placeholder="Describe why this refund is being processed (min 10 characters)…"></textarea>
                    <div class="flex justify-between mt-1">
                        <p class="text-xs text-red-500 hidden" id="r-reason-error"></p>
                        <span class="text-xs text-gray-400 ml-auto" id="r-char-count">0 / 1000</span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3">
                    <button type="button" onclick="closeRefundModal()"
                            class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold transition-all">
                        Cancel
                    </button>
                    <button type="button" onclick="goToConfirmStep()"
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl hover:shadow-lg font-semibold transition-all flex items-center justify-center gap-2">
                        Review Refund
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Step 2: Confirmation --}}
            <div id="refundStep2" class="p-6 hidden">
                <div class="flex items-start gap-4 mb-5">
                    <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 shadow">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-gray-800">Confirm Refund</h2>
                        <p class="text-sm text-gray-500 mt-0.5">Step 2 of 2 — Review and confirm</p>
                    </div>
                </div>

                {{-- Summary box — populated dynamically by goToConfirmStep() --}}
                <div id="refund-summary" class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-5 space-y-2 text-sm">
                    {{-- content injected via innerHTML --}}
                </div>

                {{-- Confirmation checkbox --}}
                <label class="flex items-start gap-3 mb-5 cursor-pointer group">
                    <input type="checkbox" id="r-confirm-check"
                           class="mt-0.5 w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                    <span class="text-sm text-gray-700 group-hover:text-gray-900 transition-colors">
                        I confirm that this refund has been authorized and the customer has been notified.
                    </span>
                </label>

                {{-- Hidden form --}}
                <form id="refund-form" method="POST" action="" class="hidden">
                    @csrf
                    <input type="hidden" name="refund_amount" id="form-refund-amount">
                    <input type="hidden" name="refund_reason" id="form-refund-reason">
                </form>

                <div class="flex gap-3">
                    <button type="button" onclick="backToStep1()"
                            class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back
                    </button>
                    <button type="button" id="r-submit-btn" onclick="submitRefund()"
                            disabled
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-red-500 to-red-700 text-white rounded-xl font-semibold transition-all
                                   disabled:opacity-40 disabled:cursor-not-allowed hover:shadow-lg flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>
                        Process Refund
                    </button>
                </div>
            </div>

        </div>{{-- /refundContainer --}}
    </div>
</div>

<script>
    // ==================== REFUND MODAL STATE ====================
    // Store everything as JS variables so we never chase elements that
    // Livewire's DOM morph might have touched inside the hidden step-2 div.
    let _refundTotal   = 0;
    let _refundAction  = '';
    let _txnNumber     = '';
    let _orderNumber   = '';
    let _customer      = '';
    let _method        = '';

    function openRefundModal(id, txnNumber, orderNumber, customer, total, method, action) {
        _refundTotal  = parseFloat(total);
        _refundAction = action;
        _txnNumber    = txnNumber;
        _orderNumber  = orderNumber;
        _customer     = customer;
        _method       = method;

        // Populate Step 1 display
        document.getElementById('r-txn-number').textContent   = txnNumber;
        document.getElementById('r-order-number').textContent = orderNumber;
        document.getElementById('r-customer').textContent     = customer;
        document.getElementById('r-method').textContent       = method;
        document.getElementById('r-total').textContent        = '₱' + _refundTotal.toFixed(2);
        document.getElementById('r-amount-hint').textContent  = 'Max: ₱' + _refundTotal.toFixed(2);

        // Reset inputs
        document.getElementById('r-amount').value             = _refundTotal.toFixed(2);
        document.getElementById('r-reason').value             = '';
        document.getElementById('r-char-count').textContent   = '0 / 1000';
        document.getElementById('r-amount-error').classList.add('hidden');
        document.getElementById('r-reason-error').classList.add('hidden');

        // Show step 1, hide step 2
        document.getElementById('refundStep1').classList.remove('hidden');
        document.getElementById('refundStep2').classList.add('hidden');

        // Show modal with animation
        const modal     = document.getElementById('refundModal');
        const container = document.getElementById('refundContainer');
        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            container.classList.remove('scale-95', 'opacity-0');
            container.classList.add('scale-100', 'opacity-100');
        });
        document.body.style.overflow = 'hidden';
    }

    function closeRefundModal() {
        const container = document.getElementById('refundContainer');
        container.classList.remove('scale-100', 'opacity-100');
        container.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            document.getElementById('refundModal').classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
    }

    function setFullRefund() {
        document.getElementById('r-amount').value = _refundTotal.toFixed(2);
    }

    // Character counter
    document.getElementById('r-reason').addEventListener('input', function() {
        document.getElementById('r-char-count').textContent = this.value.length + ' / 1000';
    });

    function goToConfirmStep() {
        const amount = parseFloat(document.getElementById('r-amount').value);
        const reason = document.getElementById('r-reason').value.trim();
        let valid    = true;

        // Validate amount
        const amtError = document.getElementById('r-amount-error');
        if (!amount || amount <= 0) {
            amtError.textContent = 'Please enter a valid refund amount.';
            amtError.classList.remove('hidden');
            valid = false;
        } else if (amount > _refundTotal) {
            amtError.textContent = 'Refund amount cannot exceed ₱' + _refundTotal.toFixed(2) + '.';
            amtError.classList.remove('hidden');
            valid = false;
        } else {
            amtError.classList.add('hidden');
        }

        // Validate reason
        const reaError = document.getElementById('r-reason-error');
        if (reason.length < 10) {
            reaError.textContent = 'Reason must be at least 10 characters.';
            reaError.classList.remove('hidden');
            valid = false;
        } else {
            reaError.classList.add('hidden');
        }

        if (!valid) return;

        const isPartial  = amount < _refundTotal;
        const refundType = isPartial ? 'Partial Refund' : 'Full Refund';

        // Build step-2 summary via innerHTML — avoids any getElementById null issues
        // on elements that may have been touched by Livewire DOM morphing.
        document.getElementById('refund-summary').innerHTML = `
            <p class="font-semibold text-red-700 text-sm mb-2">⚠️ This action cannot be undone. Please review carefully.</p>
            <div class="flex justify-between">
                <span class="text-gray-600">Transaction</span>
                <span class="font-mono font-bold text-[#ea5a47]">${_txnNumber}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Customer</span>
                <span class="font-medium text-gray-800">${_customer}</span>
            </div>
            <div class="flex justify-between border-t border-red-200 pt-2">
                <span class="text-gray-600">Refund Amount</span>
                <span class="font-black text-red-600 text-base">₱${amount.toFixed(2)}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Type</span>
                <span class="font-semibold text-gray-700">${refundType}</span>
            </div>
            <div class="pt-2 border-t border-red-200">
                <p class="text-gray-600 mb-1">Reason:</p>
                <p class="text-gray-800 font-medium italic text-xs leading-relaxed">${reason.replace(/</g,'&lt;').replace(/>/g,'&gt;')}</p>
            </div>`;

        // Reset checkbox and submit button (they are static so getElementById is fine)
        document.getElementById('r-confirm-check').checked = false;
        document.getElementById('r-submit-btn').disabled   = true;

        // Set hidden form fields
        document.getElementById('form-refund-amount').value = amount.toFixed(2);
        document.getElementById('form-refund-reason').value = reason;
        document.getElementById('refund-form').action       = _refundAction;

        // Switch steps
        document.getElementById('refundStep1').classList.add('hidden');
        document.getElementById('refundStep2').classList.remove('hidden');
    }

    // Enable/disable submit based on checkbox
    document.getElementById('r-confirm-check').addEventListener('change', function() {
        document.getElementById('r-submit-btn').disabled = !this.checked;
    });

    function backToStep1() {
        document.getElementById('refundStep2').classList.add('hidden');
        document.getElementById('refundStep1').classList.remove('hidden');
    }

    function submitRefund() {
        const btn = document.getElementById('r-submit-btn');
        if (btn.disabled) return;
        btn.disabled  = true;
        btn.innerHTML = '<div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div> Processing…';
        document.getElementById('refund-form').submit();
    }

    // Close on backdrop click
    document.getElementById('refundBackdrop').addEventListener('click', closeRefundModal);

    // Close on Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('refundModal').classList.contains('hidden')) {
            closeRefundModal();
        }
    });

    // Auto-hide flash messages after 5s
    // Use border-l-4 to target only the flash banners, not other bg-red-50/bg-green-50 elements.
    setTimeout(() => {
        document.querySelectorAll('.bg-green-50.border-l-4, .bg-red-50.border-l-4').forEach(el => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity    = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 5000);
</script>

<style>
    #refundContainer { transition: all 0.3s cubic-bezier(0.4,0,0.2,1); }
    .overflow-x-auto::-webkit-scrollbar { height:4px; width:6px; }
    .overflow-x-auto::-webkit-scrollbar-track { background:#f1f1f1; border-radius:4px; }
    .overflow-x-auto::-webkit-scrollbar-thumb { background:#ea5a47; border-radius:4px; }
    .overflow-x-auto::-webkit-scrollbar-thumb:hover { background:#c53030; }
</style>
@endsection
