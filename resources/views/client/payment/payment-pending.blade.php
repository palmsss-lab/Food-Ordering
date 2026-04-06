@extends('client.layouts.home')

@section('title', 'Cash on Pickup - Order Placed')

@section('content')
<div class="mt-25 relative min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] overflow-hidden py-12 px-4 sm:px-6 lg:px-8">
    <div class="relative z-10 max-w-4xl mx-auto">
        
        <!-- Header with back button -->
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('client.orders.index', ['tab' => 'pending']) }}" 
               class="p-2 bg-white rounded-lg shadow hover:shadow-md transition-all">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div class="bg-[#ea5a47] p-3 rounded-2xl shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                </svg>
            </div>
            <h1 class="text-4xl font-black text-gray-800">Cash <span class="text-[#ea5a47]">on Pickup</span></h1>
        </div>

        <!-- Main Card -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl p-12 text-center border border-white/20">
            <!-- Success Icon -->
            <div class="relative mb-8">
                <div class="w-32 h-32 mx-auto bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-gray-800 mb-4">Order Placed Successfully!</h2>
            
            <p class="text-gray-600 mb-6 max-w-lg mx-auto">
                Your order has been placed. Payment will be made when you pick up your order at the counter.
            </p>

            <!-- Order Details -->
            <div class="bg-gray-50 rounded-2xl p-6 max-w-md mx-auto mb-8">
                <p class="text-sm text-gray-500 mb-2">Order Number</p>
                <p class="text-xl font-bold text-[#ea5a47]">{{ $order->order_number }}</p>
                <p class="text-sm text-gray-500 mt-4 mb-2">Total to Pay on Pickup</p>
                <p class="text-3xl font-black text-gray-800">{{ $order->formatted_total }}</p>
                
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-500 mb-2">Payment Method</p>
                    <div class="flex items-center justify-center gap-2">
                        <span class="text-2xl">💵</span>
                        <p class="text-lg font-semibold text-gray-800">Cash on Pickup</p>
                    </div>
                </div>
            </div>

            <!-- Waiting for Confirmation Message -->
            <div class="bg-yellow-50 rounded-2xl p-6 max-w-lg mx-auto text-left mb-8 border-l-4 border-yellow-500">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-yellow-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="font-semibold text-yellow-800">Awaiting Admin Confirmation</p>
                        <p class="text-sm text-yellow-700 mt-1">
                            Your order is pending admin confirmation. Once confirmed, it will move to preparation.
                            You will be notified when your order is ready for pickup.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Store Information -->
            <div class="bg-orange-50 rounded-2xl p-6 max-w-lg mx-auto text-left">
                <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Pickup Location:
                </h3>
                <p class="text-gray-600 mb-2">
                    <span class="font-bold">2Dine-In Restaurant</span><br>
                    San Juan Bautista, Goa, Camarines Sur
                </p>
                <p class="text-gray-600">
                    <span class="font-bold">Store Hours:</span> 10:00 AM - 10:00 PM
                </p>
            </div>

            <!-- What to Expect -->
            <div class="mt-8 text-left max-w-lg mx-auto">
                <h3 class="font-bold text-gray-800 mb-3">What to expect:</h3>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold flex-shrink-0">1</div>
                        <p class="text-gray-600">Admin confirms your order (usually within minutes)</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold flex-shrink-0">2</div>
                        <p class="text-gray-600">Order is prepared by our kitchen</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold flex-shrink-0">3</div>
                        <p class="text-gray-600">You receive notification when ready for pickup</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold flex-shrink-0">4</div>
                        <p class="text-gray-600">Pay cash at the counter when you pick up your order</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
                <a href="{{ route('client.orders.index', ['tab' => 'pending']) }}" 
                   class="px-8 py-3 bg-[#ea5a47] text-white font-semibold rounded-xl hover:bg-[#c53030] transition-all">
                    View My Orders
                </a>
                
                <a href="{{ route('client.menu') }}" 
                   class="px-8 py-3 bg-gray-600 text-white font-semibold rounded-xl hover:bg-gray-700 transition-all">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
@endsection