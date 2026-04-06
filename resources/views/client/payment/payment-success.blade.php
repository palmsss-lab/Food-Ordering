@extends('client.layouts.home')

@section('title', 'Payment Successful')

@section('content')
<div class="mt-25 max-w-4xl mx-auto mt-32 px-4 mb-20">
    
    <!-- Header -->
    <div class="flex items-center gap-3 mb-8">
        <div class="bg-[#ea5a47] p-3 rounded-2xl shadow-lg">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h1 class="text-4xl font-black text-gray-800">Payment <span class="text-[#ea5a47]">Successful</span></h1>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-3xl shadow-2xl p-12 text-center">
        <!-- Success Animation -->
        <div class="relative mb-8">
            <div class="w-32 h-32 mx-auto bg-green-100 rounded-full flex items-center justify-center animate-pulse">
                <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-gray-800 mb-4">Thank You for Your Payment!</h2>
        
        <p class="text-gray-600 mb-6 max-w-lg mx-auto">
            Your payment has been successfully processed. Your order is now being prepared.
            You will be notified when it's ready for pickup.
        </p>

        <!-- Order Details -->
        <div class="bg-gray-50 rounded-2xl p-6 max-w-md mx-auto mb-8">
            <p class="text-sm text-gray-500 mb-2">Order Number</p>
            <p class="text-xl font-bold text-[#ea5a47]">{{ $order->order_number }}</p>
            <p class="text-sm text-gray-500 mt-4 mb-2">Total Amount Paid</p>
            <p class="text-3xl font-black text-gray-800">{{ $order->formatted_total }}</p>
            <p class="text-sm text-gray-500 mt-4 mb-2">Payment Method</p>
            <p class="text-lg font-semibold text-gray-800 capitalize">{{ $order->payment_method }}</p>
            
            <!-- Payment Reference -->
            @if($order->payments->first())
            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-500 mb-1">Reference Number</p>
                <p class="text-sm font-mono bg-gray-100 px-3 py-2 rounded-lg">
                    {{ $order->payments->first()->reference_number }}
                </p>
            </div>
            @endif
        </div>

        <!-- Estimated Time -->
        <div class="bg-blue-50 rounded-2xl p-6 max-w-lg mx-auto mb-8">
            <div class="flex items-center justify-center gap-3">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-lg font-semibold text-gray-800">
                    Estimated pickup time: <span class="text-blue-600">15-20 minutes</span>
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('client.orders.index', ['tab' => 'preparing']) }}" 
               class="px-8 py-3 bg-[#ea5a47] text-white font-semibold rounded-xl hover:bg-[#c53030] transition-all">
                Track My Order
            </a>
            
            <a href="{{ route('client.menu') }}" 
               class="px-8 py-3 bg-gray-600 text-white font-semibold rounded-xl hover:bg-gray-700 transition-all">
                Order More
            </a>
            
            <button onclick="printReceipt()" 
                    class="px-8 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:border-[#ea5a47] hover:text-[#ea5a47] transition-all">
                Print Receipt
            </button>
        </div>
    </div>
</div>

<script>
function printReceipt() {
    // Create a printable receipt
    const receiptContent = `
        <html>
        <head>
            <title>Payment Receipt - {{ $order->order_number }}</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 40px; }
                .receipt { max-width: 400px; margin: 0 auto; }
                .header { text-align: center; margin-bottom: 30px; }
                .header h1 { color: #ea5a47; margin-bottom: 5px; }
                .details { margin-bottom: 20px; }
                .items { margin-bottom: 20px; }
                .item { display: flex; justify-content: space-between; margin-bottom: 5px; }
                .total { font-weight: bold; font-size: 1.2em; text-align: right; }
            </style>
        </head>
        <body>
            <div class="receipt">
                <div class="header">
                    <h1>2Dine-In</h1>
                    <p>Payment Receipt</p>
                </div>
                
                <div class="details">
                    <p><strong>Order #:</strong> {{ $order->order_number }}</p>
                    <p><strong>Date:</strong> {{ now()->timezone('Asia/Manila')->format('M d, Y h:i A') }}</p>
                    <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
                    <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                    @if($order->payments->first())
                    <p><strong>Reference #:</strong> {{ $order->payments->first()->reference_number }}</p>
                    @endif
                </div>
                
                <div class="items">
                    <h3>Items:</h3>
                    @foreach($order->items as $item)
                        <div class="item">
                            <span>{{ $item->quantity }}x {{ $item->item_name }}</span>
                            <span>₱{{ number_format($item->subtotal, 2) }}</span>
                        </div>
                    @endforeach
                </div>
                
                <div class="total">
                    <p>Total: {{ $order->formatted_total }}</p>
                </div>
                
                <p style="text-align: center; margin-top: 40px;">Thank you for dining with us!</p>
            </div>
        </body>
        </html>
    `;
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(receiptContent);
    printWindow.document.close();
    printWindow.print();
}
</script>
@endsection