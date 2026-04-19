@extends('client.layouts.home')

@section('title', 'Payment Failed')

@section('content')
<div class="max-w-4xl mx-auto mt-24 md:mt-32 px-4 mb-20">

    <!-- Header -->
    <div class="flex items-center gap-3 mb-8">
        <div class="bg-[#ea5a47] p-3 rounded-2xl shadow-lg">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
        <h1 class="text-2xl sm:text-4xl font-black text-gray-800">Payment <span class="text-[#ea5a47]">Failed</span></h1>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-3xl shadow-2xl p-12 text-center">
        <!-- Failed Icon -->
        <div class="relative mb-8">
            <div class="w-32 h-32 mx-auto bg-red-100 rounded-full flex items-center justify-center">
                <svg class="w-16 h-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-gray-800 mb-4">Payment Was Not Completed</h2>

        <p class="text-gray-600 mb-6 max-w-lg mx-auto">
            Something went wrong while processing your payment. Your order has not been confirmed.
            Please try again or choose a different payment method.
        </p>

        <!-- Order Details -->
        <div class="bg-gray-50 rounded-2xl p-6 max-w-md mx-auto mb-8">
            <p class="text-sm text-gray-500 mb-2">Order Number</p>
            <p class="text-xl font-bold text-[#ea5a47]">{{ $order->order_number }}</p>
            <p class="text-sm text-gray-500 mt-4 mb-2">Order Total</p>
            <p class="text-3xl font-black text-gray-800">{{ $order->formatted_total }}</p>
            <p class="text-sm text-gray-500 mt-4 mb-2">Payment Method</p>
            <p class="text-lg font-semibold text-gray-800 capitalize">{{ $order->payment_method }}</p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('client.payments.process', $order) }}"
               class="w-full sm:w-auto px-8 py-3 bg-[#ea5a47] text-white font-semibold rounded-xl hover:bg-[#c53030] transition-all text-center">
                Try Again
            </a>
            <a href="{{ route('client.menu') }}"
               class="w-full sm:w-auto px-8 py-3 bg-gray-600 text-white font-semibold rounded-xl hover:bg-gray-700 transition-all text-center">
                Back to Menu
            </a>
        </div>
    </div>
</div>
@endsection
