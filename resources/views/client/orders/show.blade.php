@extends('client.layouts.home')

@section('title', 'Order Details')

@section('content')
<div class="max-w-4xl mx-auto mt-32 px-4 mb-20">
    
    <!-- Header with back button -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-3">
            <div class="bg-[#ea5a47] p-3 rounded-2xl shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h1 class="text-4xl font-black text-gray-800">Order <span class="text-[#ea5a47]">Details</span></h1>
        </div>
        
        <a href="{{ route('client.orders.index', ['tab' => $order->display_status]) }}" 
           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Orders
        </a>
    </div>

    <!-- Order Status Card -->
    <div class="bg-white rounded-3xl shadow-2xl p-8 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <p class="text-sm text-gray-500 mb-1">Order Number</p>
                <p class="text-2xl font-bold text-[#ea5a47]">{{ $order->order_number }}</p>
                @php
                    $transaction = \App\Models\Transaction::where('order_number', $order->order_number)->first();
                @endphp
                @if($order->order_status === 'completed' && $transaction)
                    <p class="text-sm text-gray-400 mt-1">
                        Transaction: 
                        <a href="{{ route('client.transactions.show', $transaction->transaction_number) }}" class="text-[#ea5a47] hover:underline">
                            {{ $transaction->transaction_number }}
                        </a>
                    </p>
                @endif
            </div>
            
            <div class="flex items-center gap-2 flex-wrap">
                <!-- Payment Method Badge -->
                @php
                    $paymentMethod = $order->latestPayment?->payment_method;
                @endphp
                @if($paymentMethod)
                    <span class="px-4 py-2 rounded-full text-sm font-bold {{ $order->payment_badge_class }}">
                        @if($paymentMethod === 'cash')
                            💵 {{ $order->payment_display_name }}
                        @elseif($paymentMethod === 'gcash')
                            💳 {{ $order->payment_display_name }}
                        @elseif($paymentMethod === 'card')
                            💳 {{ $order->payment_display_name }}
                        @endif
                    </span>
                @endif
                
                <!-- Status Badge -->
                <span class="px-4 py-2 rounded-full text-sm font-bold
                    @if($order->order_status === 'pending') 
                        @if($paymentMethod === 'cash' && !$order->admin_confirmed_at)
                            bg-yellow-100 text-yellow-700
                        @else
                            bg-yellow-100 text-yellow-700
                        @endif
                    @elseif($order->order_status === 'preparing') bg-blue-100 text-blue-700
                    @elseif($order->order_status === 'ready') bg-green-100 text-green-700
                    @elseif($order->order_status === 'completed') bg-gray-100 text-gray-700
                    @elseif($order->order_status === 'cancelled') bg-red-100 text-red-700
                    @endif">
                    @if($order->order_status === 'pending' && $paymentMethod === 'cash' && !$order->admin_confirmed_at)
                        AWAITING CONFIRMATION
                    @else
                        {{ strtoupper($order->order_status) }}
                    @endif
                </span>
                
                <!-- Payment Status Badge -->
                <span class="px-4 py-2 rounded-full text-sm font-bold
                    @if($order->payment_status === 'paid') bg-green-100 text-green-700
                    @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-700
                    @else bg-gray-100 text-gray-700
                    @endif">
                    @if($paymentMethod === 'cash' && $order->payment_status === 'pending')
                        TO PAY ON PICKUP
                    @else
                        {{ strtoupper($order->payment_status) }}
                    @endif
                </span>
            </div>
        </div>

        <!-- Order Timeline -->
        <div class="mb-8">
            <h3 class="font-bold text-gray-700 mb-4">Order Timeline</h3>
            <div class="space-y-3">
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center mt-0.5">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium">Order Placed</p>
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($order->ordered_at ?? $order->created_at)->timezone('Asia/Manila')->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
                
                @if($order->admin_confirmed_at)
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center mt-0.5">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium">Order Confirmed</p>
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($order->admin_confirmed_at)->timezone('Asia/Manila')->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
                @endif
                
                @if($order->prepared_at)
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center mt-0.5">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium">Preparation Started</p>
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($order->prepared_at)->timezone('Asia/Manila')->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
                @endif
                
                @if($order->ready_at)
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center mt-0.5">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium">Ready for Pickup</p>
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($order->ready_at)->timezone('Asia/Manila')->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
                @endif
                
                @if($order->completed_at)
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center mt-0.5">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium">Completed</p>
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($order->completed_at)->timezone('Asia/Manila')->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
                @endif
                
                @if($order->cancelled_at)
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-red-500 flex items-center justify-center mt-0.5">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium">Cancelled</p>
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($order->cancelled_at)->timezone('Asia/Manila')->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Order Items Card -->
    <div class="bg-white rounded-3xl shadow-2xl p-8 mb-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Order Items</h3>
        
        <div class="space-y-4">
            @foreach($order->items as $item)
            <div class="flex justify-between items-center border-b border-gray-100 pb-3 last:border-0">
                <div>
                    <p class="font-medium">{{ $item->item_name }}</p>
                    <p class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                    @if($item->special_instructions)
                        <p class="text-xs text-gray-400 italic">Note: {{ $item->special_instructions }}</p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="font-bold">₱{{ number_format($item->price, 2) }}</p>
                    <p class="text-sm text-gray-500">₱{{ number_format($item->subtotal, 2) }}</p>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Totals -->
        <div class="mt-6 pt-4 border-t border-gray-200 space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Subtotal:</span>
                <span>₱{{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Tax:</span>
                <span>₱{{ number_format($order->tax, 2) }}</span>
            </div>
            <div class="flex justify-between font-bold text-lg">
                <span>Total:</span>
                <span class="text-[#ea5a47]">₱{{ number_format($order->total, 2) }}</span>
            </div>
        </div>
        
        <!-- Payment Instructions for Cash on Pickup -->
        @if($paymentMethod === 'cash' && $order->order_status === 'pending' && !$order->admin_confirmed_at)
        <div class="mt-6 p-4 bg-orange-50 border border-orange-200 rounded-xl">
            <div class="flex items-start gap-3">
                <div class="bg-orange-100 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-orange-800">Cash on Pickup</p>
                    <p class="text-sm text-orange-700">Your order is awaiting confirmation. Please wait for admin to confirm your order. Payment will be made when you pick up your order.</p>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Transaction Link for Completed Orders -->
        @if($order->order_status === 'completed' && $transaction)
        <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-xl">
            <div class="flex items-start gap-3">
                <div class="bg-green-100 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-green-800">Transaction Recorded</p>
                    <p class="text-sm text-green-700">
                        This order has been recorded in your transaction history.
                        <a href="{{ route('client.transactions.show', $transaction->transaction_number) }}" class="font-medium underline hover:text-green-800">
                            View Transaction Receipt →
                        </a>
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-3 justify-end">
        @if($order->order_status === 'pending')
            <!-- Only show payment button for non-cash orders that need payment -->
            @if($order->needs_payment)
                <a href="{{ route('client.payments.pay', $order) }}" 
                class="px-6 py-3 bg-[#ea5a47] text-white font-semibold rounded-xl hover:bg-[#c53030] transition-all text-center">
                    Proceed to Payment
                </a>
            @endif
            
            <!-- Show waiting message for confirmed orders in pending tab -->
            @if($order->admin_confirmed_at)
                <div class="px-6 py-3 bg-green-100 text-green-700 font-semibold rounded-xl text-center">
                    ✓ Order Confirmed - Moving to Preparation
                </div>
            @endif
            
            <!-- Cancel button - only show if not confirmed -->
           @if(!$order->admin_confirmed_at && $order->payment_method === 'cash')
                <form action="{{ route('client.orders.cancel', $order) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" 
                            class="px-6 py-3 bg-red-500 text-white font-semibold rounded-xl hover:bg-red-600 transition-all w-full"
                            onclick="return confirm('Are you sure you want to cancel this order?')">
                        Cancel Order
                    </button>
                </form>
            @endif
        @endif
        
        @if($order->order_status === 'ready')
            <form action="{{ route('client.orders.picked-up', $order) }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="px-6 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition-all"
                        onclick="return confirm('Have you picked up your order?');">
                    I've Picked Up My Order
                </button>
            </form>
        @endif
        
        <button onclick="window.print()" 
                class="px-6 py-3 bg-gray-600 text-white font-semibold rounded-xl hover:bg-gray-700 transition-all">
            Print Receipt
        </button>
    </div>

    <!-- Add a info message for paid but unconfirmed orders -->
    @if($order->payment_status === 'paid' && !$order->admin_confirmed_at)
    <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
        <div class="flex items-start gap-3">
            <div class="bg-blue-100 p-2 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="font-bold text-blue-800">Payment Received</p>
                <p class="text-sm text-blue-700">Your payment has been received. Please wait for admin to confirm your order.</p>
            </div>
        </div>
    </div>
    @endif
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