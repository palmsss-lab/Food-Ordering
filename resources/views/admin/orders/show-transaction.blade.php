@extends('admin.layouts.home', ['active' => 'transactions'])

@section('title', 'Transaction Details')

@section('content')
<div class="relative min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] py-12 px-4 sm:px-6 lg:px-8">
    <div class="relative z-10 max-w-4xl mx-auto">
        
        <!-- Simple Header -->
        <div class="flex items-center justify-between mb-6">
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
                <h1 class="text-2xl font-bold text-gray-800">Transaction Details</h1>
            </div>
        </div>

        <!-- Transaction Card - Scrollable -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden" style="max-height: calc(100vh - 120px); overflow-y: auto;">
            
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
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-500/100 text-white">
                            PAID
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
                                        @if($item->special_instructions)
                                            <div class="text-xs text-gray-400 mt-0.5">Note: {{ $item->special_instructions }}</div>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 text-center">{{ $item->quantity }}</td>
                                    <td class="px-3 py-2 text-right">₱{{ number_format($item->price, 2) }}</td>
                                    <td class="px-3 py-2 text-right font-medium text-[#ea5a47]">₱{{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 border-t">
                                <tr>
                                    <td colspan="3" class="px-3 py-2 text-right font-medium">Subtotal:</td>
                                    <td class="px-3 py-2 text-right">₱{{ number_format($transaction->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="px-3 py-2 text-right font-medium">Tax (12%):</td>
                                    <td class="px-3 py-2 text-right">₱{{ number_format($transaction->tax, 2) }}</td>
                                </tr>
                                <tr class="border-t border-gray-200">
                                    <td colspan="3" class="px-3 py-2 text-right font-bold">Total:</td>
                                    <td class="px-3 py-2 text-right font-bold text-[#ea5a47]">{{ $transaction->formatted_total }}</td>
                                </tr>
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

                <!-- Action Buttons -->
                <div class="flex gap-3 justify-end pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.orders.transactions') }}" 
                       class="px-4 py-2 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700 transition-all">
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

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