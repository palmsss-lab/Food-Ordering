@extends('client.layouts.home')

@section('title', 'My Transactions')

@section('content')
<div class="max-w-6xl mx-auto mt-32 px-4 mb-20">
    
    <!-- Header -->
    <div class="flex items-center gap-3 mb-8">
        <div class="bg-[#ea5a47] p-3 rounded-2xl shadow-lg">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </div>
        <h1 class="text-4xl font-black text-gray-800">My <span class="text-[#ea5a47]">Transactions</span></h1>
    </div>

    @if($transactions->isEmpty())
        <div class="bg-gray-50 rounded-2xl p-12 text-center">
            <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <h3 class="text-xl font-bold text-gray-600 mb-2">No Transactions Yet</h3>
            <p class="text-gray-500">Your completed orders will appear here.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($transactions as $transaction)
                @php
                    $paymentMethod = $transaction->payment_method;
                @endphp
                <div class="bg-white border-2 rounded-2xl p-6 hover:shadow-lg transition-all
                    @if($paymentMethod == 'cash') border-orange-200
                    @elseif($paymentMethod == 'gcash') border-blue-200
                    @else border-purple-200
                    @endif">
                    
                    <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2 flex-wrap">
                                <span class="text-sm font-semibold text-gray-500">Transaction #:</span>
                                <span class="font-bold text-[#ea5a47]">{{ $transaction->transaction_number }}</span>
                                
                                <!-- Payment Method Badge -->
                                @if($paymentMethod == 'cash')
                                    <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-bold">
                                        💵 Cash
                                    </span>
                                @elseif($paymentMethod == 'gcash')
                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">
                                        📱 GCash
                                    </span>
                                @elseif($paymentMethod == 'card')
                                    <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-bold">
                                        💳 Card
                                    </span>
                                @endif
                            </div>
                            
                            <p class="text-gray-600 mb-2">
                                {{ $transaction->items->count() }} item(s) • 
                                Total: <span class="font-bold text-[#ea5a47]">{{ $transaction->formatted_total }}</span>
                            </p>
                            
                            <p class="text-sm text-gray-400">
                                {{ $transaction->transaction_date->format('M d, Y h:i A') }}
                            </p>
                            
                            @if($transaction->reference_number)
                                <p class="text-xs text-gray-500 mt-1">
                                    Ref: {{ $transaction->reference_number }}
                                </p>
                            @endif
                        </div>
                        
                        <a href="{{ route('client.transactions.show', $transaction->transaction_number) }}" 
                           class="px-4 py-2 bg-gray-600 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            View Receipt
                        </a>
                    </div>
                    
                    <!-- Quick preview of items -->
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-sm font-medium text-gray-500 mb-2">Items:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($transaction->items->take(3) as $item)
                                <span class="text-sm bg-gray-100 px-3 py-1 rounded-full">
                                    {{ $item->quantity }}x {{ $item->item_name }}
                                </span>
                            @endforeach
                            @if($transaction->items->count() > 3)
                                <span class="text-sm text-gray-500">
                                    +{{ $transaction->items->count() - 3 }} more
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if($transactions->hasPages())
        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
        @endif
    @endif
</div>
@endsection