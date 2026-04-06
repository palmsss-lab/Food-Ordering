@extends('client.layouts.home')

@section('title', 'Transaction Details')

@section('content')
<div class="max-w-4xl mx-auto mt-32 px-4 mb-20">
    
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
        <h1 class="text-4xl font-black text-gray-800">Transaction <span class="text-[#ea5a47]">Details</span></h1>
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
            <div class="flex gap-2">
                <span class="px-4 py-2 rounded-full text-sm font-bold
                    @if($transaction->payment_method == 'cash') bg-orange-100 text-orange-700
                    @elseif($transaction->payment_method == 'gcash') bg-blue-100 text-blue-700
                    @else bg-purple-100 text-purple-700
                    @endif">
                    {{ strtoupper($transaction->payment_method) }}
                </span>
                <span class="px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-700">
                    PAID
                </span>
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
                    @if($item->special_instructions)
                        <p class="text-xs text-gray-400 italic">Note: {{ $item->special_instructions }}</p>
                    @endif
                </div>
                <p class="font-bold text-[#ea5a47]">₱{{ number_format($item->subtotal, 2) }}</p>
            </div>
            @endforeach
        </div>

        <!-- Totals -->
        <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Subtotal:</span>
                    <span>₱{{ number_format($transaction->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Tax (12%):</span>
                    <span>₱{{ number_format($transaction->tax, 2) }}</span>
                </div>
                <div class="flex justify-between font-bold text-lg pt-2">
                    <span class="text-gray-800">Total:</span>
                    <span class="text-[#ea5a47]">{{ $transaction->formatted_total }}</span>
                </div>
            </div>
        </div>

        @if($transaction->notes)
        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-600"><span class="font-medium">Notes:</span> {{ $transaction->notes }}</p>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-end">
            <button onclick="window.print()" 
                    class="px-6 py-3 bg-gray-600 text-white font-semibold rounded-xl hover:bg-gray-700 transition-all flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Receipt
            </button>
            <a href="{{ route('client.transactions.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-all text-center">
                Back to Transactions
            </a>
        </div>
    </div>
</div>

<style>
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
        }
        button, a, .flex.justify-end {
            display: none !important;
        }
    }
</style>
@endsection