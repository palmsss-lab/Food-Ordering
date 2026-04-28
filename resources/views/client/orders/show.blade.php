@extends('client.layouts.home')

@section('title', 'Order Details')

@section('content')
<div class="max-w-4xl mx-auto mt-24 md:mt-32 px-4 mb-20">
    
    <!-- Header with back button -->
    <div class="flex flex-wrap items-center justify-between gap-3 mb-8">
        <div class="flex items-center gap-3">
            <div class="bg-[#ea5a47] p-3 rounded-2xl shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h1 class="text-2xl sm:text-4xl font-black text-gray-800">Order <span class="text-[#ea5a47]">Details</span></h1>
        </div>

        <a href="{{ route('client.orders.index', ['tab' => $order->display_status]) }}"
           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all flex items-center gap-2 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

            @php
                $isPrivilege = in_array($order->discount_type, ['pwd', 'senior']);
                $totalSaved  = ($order->promo_discount ?? 0) + ($order->discount ?? 0) + ($isPrivilege ? $order->tax : 0);
            @endphp

            {{-- Promotion discount row --}}
            @if(($order->promo_discount ?? 0) > 0)
            <div class="flex justify-between text-sm items-center">
                <span class="text-green-700 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    {{ $order->promo_label ?: 'Promotion' }}
                </span>
                <span class="font-semibold text-green-600">− ₱{{ number_format($order->promo_discount, 2) }}</span>
            </div>
            @endif

            {{-- Voucher / PWD / Senior discount row --}}
            @if(($order->discount ?? 0) > 0)
            @php
                $extraLabel = match($order->discount_type) {
                    'pwd'     => 'PWD Discount (20%)',
                    'senior'  => 'Senior Citizen Discount (20%)',
                    'voucher' => 'Voucher' . ($order->discount_label ? ': ' . $order->discount_label : ''),
                    default   => $order->discount_label ?: 'Discount',
                };
            @endphp
            <div class="flex justify-between text-sm items-center">
                <span class="text-green-700 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    {{ $extraLabel }}
                </span>
                <span class="font-semibold text-green-600">− ₱{{ number_format($order->discount, 2) }}</span>
            </div>
            @if($isPrivilege)
            <div class="flex justify-between text-sm">
                <span class="text-green-700">VAT Exempt</span>
                <span class="font-semibold text-green-600">− ₱{{ number_format($order->tax, 2) }}</span>
            </div>
            @endif
            @endif

            @if(!$isPrivilege)
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">VAT (12%):</span>
                <span>₱{{ number_format($order->tax, 2) }}</span>
            </div>
            @endif

            <div class="flex justify-between font-bold text-lg pt-2 border-t border-gray-100">
                <span>Total:</span>
                <span class="text-[#ea5a47]">₱{{ number_format($order->total, 2) }}</span>
            </div>

            @if($totalSaved > 0)
            <div class="flex justify-end">
                <span class="text-xs text-green-600 bg-green-50 border border-green-200 px-2 py-1 rounded-full font-medium">
                    You saved ₱{{ number_format($totalSaved, 2) }}
                </span>
            </div>
            @endif
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
                <button onclick="document.getElementById('cancelOrderModal').classList.remove('hidden')"
                        class="px-6 py-3 bg-red-500 text-white font-semibold rounded-xl hover:bg-red-600 transition-all w-full">
                    Cancel Order
                </button>
            @endif
        @endif
        
        @if($order->order_status === 'ready')
            <button onclick="document.getElementById('pickedUpModal').classList.remove('hidden')"
                    class="px-6 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                I've Picked Up My Order
            </button>
        @endif
        
        <a href="{{ route('client.orders.download', $order->order_number) }}"
           data-no-loader
           class="px-6 py-3 bg-[#ea5a47] text-white font-semibold rounded-xl hover:bg-[#c53030] transition-all flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Download Receipt
        </a>

        <button onclick="window.print()"
                class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-300 transition-all flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Print
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

{{-- Picked Up Confirmation Modal --}}
@if($order->order_status === 'ready')
<div id="pickedUpModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="document.getElementById('pickedUpModal').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden">

            {{-- Green header band --}}
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-black text-white">Confirm Pickup</h2>
                    <p class="text-white/80 text-sm">Order {{ $order->order_number }}</p>
                </div>
            </div>

            <div class="p-6">
                {{-- Checkmark illustration --}}
                <div class="flex justify-center mb-5">
                    <div class="w-20 h-20 rounded-full bg-green-50 border-4 border-green-100 flex items-center justify-center">
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>

                <h3 class="text-center text-lg font-bold text-gray-800 mb-1">Your order is ready!</h3>
                <p class="text-center text-sm text-gray-500 mb-5">
                    Please confirm that you have already received and picked up your order from the counter.
                </p>

                {{-- Order summary strip --}}
                <div class="bg-gray-50 rounded-2xl p-4 mb-5 space-y-1.5">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Customer</span>
                        <span class="font-medium text-gray-800">{{ $order->customer_name }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Items</span>
                        <span class="font-medium text-gray-800">{{ $order->items->count() }} item(s)</span>
                    </div>
                    <div class="flex justify-between text-sm pt-1 border-t border-gray-200">
                        <span class="text-gray-500 font-semibold">Total</span>
                        <span class="font-bold text-[#ea5a47]">₱{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>

                @if($order->payment_method === 'cash' && $order->payment_status !== 'paid')
                <div class="mb-5 p-3 bg-orange-50 border border-orange-200 rounded-xl flex items-start gap-2">
                    <svg class="w-4 h-4 text-orange-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-sm text-orange-700">
                        <span class="font-semibold">Reminder:</span> Please pay <strong>₱{{ number_format($order->total, 2) }}</strong> in cash at the counter.
                    </p>
                </div>
                @endif

                <form action="{{ route('client.orders.picked-up', $order) }}" method="POST">
                    @csrf
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="submit"
                                class="flex-1 px-5 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:opacity-90 transition-all font-semibold shadow-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Yes, I've Picked It Up
                        </button>
                        <button type="button"
                                onclick="document.getElementById('pickedUpModal').classList.add('hidden')"
                                class="px-5 py-3 border border-gray-300 rounded-xl hover:bg-gray-50 transition-all font-medium text-gray-600">
                            Not Yet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Cancel Order Confirmation Modal --}}
@if($order->order_status === 'pending' && !$order->admin_confirmed_at && $order->payment_method === 'cash')
<div id="cancelOrderModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="document.getElementById('cancelOrderModal').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden">

            {{-- Red header band --}}
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-black text-white">Cancel Order</h2>
                    <p class="text-white/80 text-sm">Order {{ $order->order_number }}</p>
                </div>
            </div>

            <div class="p-6">
                <div class="flex justify-center mb-5">
                    <div class="w-20 h-20 rounded-full bg-red-50 border-4 border-red-100 flex items-center justify-center">
                        <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>

                <h3 class="text-center text-lg font-bold text-gray-800 mb-1">Cancel this order?</h3>
                <p class="text-center text-sm text-gray-500 mb-5">
                    This action cannot be undone. Your order will be permanently cancelled.
                </p>

                <div class="bg-gray-50 rounded-2xl p-4 mb-5 space-y-1.5">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Order</span>
                        <span class="font-medium text-gray-800">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between text-sm pt-1 border-t border-gray-200">
                        <span class="text-gray-500 font-semibold">Total</span>
                        <span class="font-bold text-gray-800">₱{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>

                <form action="{{ route('client.orders.cancel', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="submit"
                                class="flex-1 px-5 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl hover:opacity-90 transition-all font-semibold shadow-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Yes, Cancel Order
                        </button>
                        <button type="button"
                                onclick="document.getElementById('cancelOrderModal').classList.add('hidden')"
                                class="px-5 py-3 border border-gray-300 rounded-xl hover:bg-gray-50 transition-all font-medium text-gray-600">
                            Keep Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

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
        <div class="r-center r-bold" style="letter-spacing:2px; margin:8px 0;">ORDER RECEIPT</div>
        <div class="r-dash1"></div>

        {{-- Order info --}}
        <table class="r-table">
            <tr><td class="r-label">Date</td><td class="r-val">{{ \Carbon\Carbon::parse($order->ordered_at ?? $order->created_at)->timezone('Asia/Manila')->format('M d, Y h:i A') }}</td></tr>
            <tr><td class="r-label">Order #</td><td class="r-val r-bold">{{ $order->order_number }}</td></tr>
            <tr><td class="r-label">Customer</td><td class="r-val">{{ $order->customer_name }}</td></tr>
            <tr><td class="r-label">Status</td><td class="r-val r-bold">{{ strtoupper($order->order_status) }}</td></tr>
            <tr><td class="r-label">Payment</td><td class="r-val">{{ $paymentMethod ? ucwords(str_replace('_', ' ', $paymentMethod)) : 'N/A' }}</td></tr>
            @if($order->latestPayment?->reference_number)
            <tr><td class="r-label">Ref #</td><td class="r-val r-mono">{{ $order->latestPayment->reference_number }}</td></tr>
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
        @php
            $pIsPrivilege = in_array($order->discount_type, ['pwd', 'senior']);
            $pTotalSaved  = ($order->promo_discount ?? 0) + ($order->discount ?? 0) + ($pIsPrivilege ? $order->tax : 0);
            $pExtraLabel  = match($order->discount_type) {
                'pwd'     => 'PWD Discount (20%)',
                'senior'  => 'Senior Citizen Discount (20%)',
                'voucher' => 'Voucher' . ($order->discount_label ? ': ' . $order->discount_label : ''),
                default   => $order->discount_label ?: 'Discount',
            };
        @endphp
        <table class="r-table">
            <tr><td class="r-label">Subtotal</td><td class="r-val">&#8369;{{ number_format($order->subtotal, 2) }}</td></tr>
            @if(($order->promo_discount ?? 0) > 0)
            <tr><td class="r-label">{{ $order->promo_label ?: 'Promotion' }}</td><td class="r-val">&minus;&#8369;{{ number_format($order->promo_discount, 2) }}</td></tr>
            @endif
            @if(($order->discount ?? 0) > 0)
            <tr><td class="r-label">{{ $pExtraLabel }}</td><td class="r-val">&minus;&#8369;{{ number_format($order->discount, 2) }}</td></tr>
            @if($pIsPrivilege)
            <tr><td class="r-label">VAT Exempt</td><td class="r-val">&minus;&#8369;{{ number_format($order->tax, 2) }}</td></tr>
            @endif
            @endif
            @if(!$pIsPrivilege)
            <tr><td class="r-label">VAT (12%)</td><td class="r-val">&#8369;{{ number_format($order->tax, 2) }}</td></tr>
            @endif
        </table>

        <div class="r-dash2"></div>
        <div class="r-total">
            <span>TOTAL</span>
            <span>&#8369;{{ number_format($order->total, 2) }}</span>
        </div>

        @if($pTotalSaved > 0)
        <div class="r-savings">You saved &#8369;{{ number_format($pTotalSaved, 2) }}</div>
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