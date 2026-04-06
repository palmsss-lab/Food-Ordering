@extends('client.layouts.home')

@section('title', 'Payment Received - Awaiting Confirmation')

@section('content')
<div class="max-w-4xl mx-auto mt-32 px-4 mb-20">
    
    <!-- Header -->
    <div class="flex items-center gap-3 mb-8">
        <div class="bg-[#ea5a47] p-3 rounded-2xl shadow-lg">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h1 class="text-4xl font-black text-gray-800">Payment <span class="text-[#ea5a47]">Received</span></h1>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-3xl shadow-2xl p-12 text-center">
        <!-- Success Checkmark -->
        <div class="mb-8">
            <div class="w-32 h-32 mx-auto">
                <div class="rounded-full bg-green-100 w-full h-full flex items-center justify-center animate-bounce">
                    <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-gray-800 mb-4">Payment Successful!</h2>
        
        <p class="text-gray-600 mb-6 max-w-lg mx-auto">
            Your payment has been received. Your order is now pending admin confirmation.
        </p>

        <!-- Awaiting Confirmation Alert -->
        <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-2xl p-6 max-w-lg mx-auto mb-8 text-left">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="font-semibold text-yellow-800">⏳ Awaiting Admin Confirmation</p>
                    <p class="text-sm text-yellow-700 mt-1">
                        Your order has been paid and is now waiting for admin confirmation. 
                        Once confirmed, it will move to preparation.
                    </p>
                </div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="bg-gray-50 rounded-2xl p-6 max-w-md mx-auto mb-8">
            <p class="text-sm text-gray-500 mb-2">Order Number</p>
            <p class="text-xl font-bold text-[#ea5a47]">{{ $order->order_number }}</p>
            <p class="text-sm text-gray-500 mt-4 mb-2">Amount Paid</p>
            <p class="text-3xl font-black text-gray-800">{{ $order->formatted_total }}</p>
            <p class="text-sm text-gray-500 mt-4 mb-2">Payment Method</p>
            <p class="font-medium text-gray-700">GCash</p>
        </div>

        <!-- Progress Steps - Updated to show Awaiting Confirmation -->
        <div class="max-w-lg mx-auto mb-8">
            <div class="flex items-center justify-between">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white font-bold">✓</div>
                    <span class="text-sm text-gray-600 mt-2">Order Placed</span>
                </div>
                <div class="flex-1 h-1 bg-green-500"></div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-yellow-500 flex items-center justify-center text-white font-bold animate-pulse">⏳</div>
                    <span class="text-sm text-gray-600 mt-2">Awaiting Confirmation</span>
                </div>
                <div class="flex-1 h-1 bg-gray-300"></div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-bold">3</div>
                    <span class="text-sm text-gray-600 mt-2">Preparing</span>
                </div>
                <div class="flex-1 h-1 bg-gray-300"></div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-bold">4</div>
                    <span class="text-sm text-gray-600 mt-2">Ready for Pickup</span>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-blue-50 rounded-2xl p-6 max-w-lg mx-auto text-left mb-8">
            <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                What happens next?
            </h3>
            <ol class="space-y-3 text-gray-600">
                <li class="flex items-start gap-2">
                    <span class="text-blue-600 font-bold">1.</span>
                    <span>Our admin will review and confirm your order</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-blue-600 font-bold">2.</span>
                    <span>Once confirmed, your order will move to preparation</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-blue-600 font-bold">3.</span>
                    <span>You'll receive updates as your order is prepared</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-blue-600 font-bold">4.</span>
                    <span>We'll notify you when your order is ready for pickup</span>
                </li>
            </ol>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
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

<script>
// Optional: Auto-refresh to check if order status changes
let checkInterval;

function checkOrderStatus() {
    fetch('{{ route("client.payments.check-status", $order) }}')
        .then(response => response.json())
        .then(data => {
            // If order moves to preparing (admin confirmed), redirect to orders page
            if (data.order_status === 'preparing') {
                clearInterval(checkInterval);
                window.location.href = '{{ route("client.orders.index", ["tab" => "preparing"]) }}';
            }
        })
        .catch(error => console.error('Error checking status:', error));
}

// Auto-check every 30 seconds (optional - for real-time updates)
document.addEventListener('DOMContentLoaded', function() {
    checkInterval = setInterval(checkOrderStatus, 30000);
});
</script>
@endsection