@extends('client.layouts.home')

@section('title', 'My Orders')

@section('content')


<div class="max-w-6xl mx-auto mt-32 px-4 mb-20">
    
    <!-- Header -->
    <div class="flex items-center gap-3 mb-8">
        <div class="bg-[#ea5a47] p-3 rounded-2xl shadow-lg">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </div>
        <h1 class="text-4xl font-black text-gray-800">My <span class="text-[#ea5a47]">Orders</span></h1>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg mb-6">
            <p class="text-green-700">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
            <p class="text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Tab Navigation -->
    @php
        $pendingCount = $orders->filter(function($order) {
            return $order->display_status === 'pending';
        })->count();
        
        $preparingCount = $orders->filter(function($order) {
            return $order->display_status === 'preparing';
        })->count();
        
        $readyCount = $orders->filter(function($order) {
            return $order->display_status === 'ready';
        })->count();
        
        $completedCount = $orders->filter(function($order) {
            return $order->display_status === 'completed';
        })->count();
        
        $cancelledCount = $orders->filter(function($order) {
            return $order->display_status === 'cancelled';
        })->count();
        
        $activeTab = request()->get('tab', 'pending');
    @endphp

    <div class="mb-6 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="ordersTab" role="tablist">
            <li class="mr-2" role="presentation">
                <button class="inline-block p-4 rounded-t-lg border-b-2 {{ $activeTab === 'pending' ? 'border-[#ea5a47] text-[#ea5a47]' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}" 
                        id="pending-tab" 
                        onclick="switchTab('pending')" 
                        type="button" 
                        role="tab" 
                        aria-controls="pending" 
                        aria-selected="{{ $activeTab === 'pending' ? 'true' : 'false' }}">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Awaiting Confirmation
                        @if($pendingCount > 0)
                            <span class="ml-2 bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full text-xs">{{ $pendingCount }}</span>
                        @endif
                    </span>
                </button>
            </li>
            <li class="mr-2" role="presentation">
                <button class="inline-block p-4 rounded-t-lg border-b-2 {{ $activeTab === 'preparing' ? 'border-[#ea5a47] text-[#ea5a47]' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}" 
                        id="preparing-tab" 
                        onclick="switchTab('preparing')" 
                        type="button" 
                        role="tab" 
                        aria-controls="preparing" 
                        aria-selected="{{ $activeTab === 'preparing' ? 'true' : 'false' }}">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Preparing
                        @if($preparingCount > 0)
                            <span class="ml-2 bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs">{{ $preparingCount }}</span>
                        @endif
                    </span>
                </button>
            </li>
            <li class="mr-2" role="presentation">
                <button class="inline-block p-4 rounded-t-lg border-b-2 {{ $activeTab === 'ready' ? 'border-[#ea5a47] text-[#ea5a47]' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}" 
                        id="ready-tab" 
                        onclick="switchTab('ready')" 
                        type="button" 
                        role="tab" 
                        aria-controls="ready" 
                        aria-selected="{{ $activeTab === 'ready' ? 'true' : 'false' }}">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Ready for Pickup
                        @if($readyCount > 0)
                            <span class="ml-2 bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs">{{ $readyCount }}</span>
                        @endif
                    </span>
                </button>
            </li>
            <li class="mr-2" role="presentation">
                <button class="inline-block p-4 rounded-t-lg border-b-2 {{ $activeTab === 'completed' ? 'border-[#ea5a47] text-[#ea5a47]' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}" 
                        id="completed-tab" 
                        onclick="switchTab('completed')" 
                        type="button" 
                        role="tab" 
                        aria-controls="completed" 
                        aria-selected="{{ $activeTab === 'completed' ? 'true' : 'false' }}">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Completed
                        @if($completedCount > 0)
                            <span class="ml-2 bg-gray-100 text-gray-700 px-2 py-0.5 rounded-full text-xs">{{ $completedCount }}</span>
                        @endif
                    </span>
                </button>
            </li>
            <li class="mr-2" role="presentation">
                <button class="inline-block p-4 rounded-t-lg border-b-2 {{ $activeTab === 'cancelled' ? 'border-[#ea5a47] text-[#ea5a47]' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}" 
                        id="cancelled-tab" 
                        onclick="switchTab('cancelled')" 
                        type="button" 
                        role="tab" 
                        aria-controls="cancelled" 
                        aria-selected="{{ $activeTab === 'cancelled' ? 'true' : 'false' }}">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancelled
                        @if($cancelledCount > 0)
                            <span class="ml-2 bg-red-100 text-red-700 px-2 py-0.5 rounded-full text-xs">{{ $cancelledCount }}</span>
                        @endif
                    </span>
                </button>
            </li>
        </ul>
    </div>

    <!-- Tab Content -->
    <div id="tabContent">
        @foreach(['pending', 'preparing', 'ready', 'completed', 'cancelled'] as $status)
            <div id="{{ $status }}" class="tab-pane {{ $activeTab === $status ? '' : 'hidden' }}" role="tabpanel">
                @php
                    $filteredOrders = $orders->filter(function($order) use ($status) {
                        return $order->display_status === $status;
                    });
                @endphp

                @if($filteredOrders->isEmpty())
                    <div class="bg-gray-50 rounded-2xl p-12 text-center">
                        <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="text-xl font-bold text-gray-600 mb-2">No {{ ucfirst($status) }} Orders</h3>
                        <p class="text-gray-500">You don't have any {{ $status }} orders at the moment.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($filteredOrders as $order)
                            @php
                                $latestPayment = $order->latestPayment;
                                $paymentMethod = $latestPayment ? $latestPayment->payment_method : null;
                                // Check if order is awaiting confirmation (admin_confirmed_at is null)
                                $isAwaitingConfirmation = !$order->admin_confirmed_at;
                            @endphp
                            
                            <div class="bg-white border-2 rounded-2xl p-6 hover:shadow-lg transition-all
                                @if($status === 'pending') border-yellow-200
                                @elseif($status === 'preparing') border-blue-200
                                @elseif($status === 'ready') border-green-200
                                @elseif($status === 'completed') border-gray-200
                                @elseif($status === 'cancelled') border-red-200
                                @endif">
                                
                                <div class="flex flex-col lg:flex-row justify-between items-start gap-6">
                                    <!-- Left side - Order Info -->
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2 flex-wrap">
                                            <span class="text-sm font-semibold text-gray-500">Order #:</span>
                                            <span class="font-bold text-[#ea5a47]">{{ $order->order_number }}</span>
                                            
                                            <!-- Payment Method Badge -->
                                            @if($paymentMethod)
                                                <span class="{{ $order->payment_badge_class }} px-3 py-1 rounded-full text-xs font-bold">
                                                    @if($paymentMethod === 'cash')
                                                        💵 {{ $order->payment_display_name }}
                                                    @elseif($paymentMethod === 'gcash')
                                                        💳 {{ $order->payment_display_name }}
                                                    @elseif($paymentMethod === 'card')
                                                        💳 {{ $order->payment_display_name }}
                                                    @endif
                                                </span>
                                            @endif
                                            
                                            <!-- Status badges with context -->
                                            @if($status === 'pending')
                                                @if($isAwaitingConfirmation)
                                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold">
                                                        ⏳ Awaiting Confirmation
                                                    </span>
                                                @else
                                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold">
                                                        ⏳ Pending
                                                    </span>
                                                @endif
                                            @elseif($status === 'preparing')
                                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">
                                                    🔄 Preparing
                                                </span>
                                            @elseif($status === 'ready')
                                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">
                                                    ✅ Ready for Pickup
                                                </span>
                                            @elseif($status === 'completed')
                                                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-bold">
                                                    ✓ Completed
                                                </span>
                                                <!-- Payment Status for Completed Orders -->
                                                @if($paymentMethod === 'cash')
                                                    @if($order->payment_status === 'paid')
                                                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-bold ml-1">
                                                            💵 Paid
                                                        </span>
                                                    @else
                                                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs font-bold ml-1">
                                                            ⏳ Payment Pending
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-bold ml-1">
                                                        ✓ Paid
                                                    </span>
                                                @endif
                                            @elseif($status === 'cancelled')
                                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">
                                                    ✕ Cancelled
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <p class="text-gray-600 mb-2">
                                            {{ $order->items->count() }} item(s) • 
                                            Total: ₱{{ number_format($order->total, 2) }}
                                        </p>
                                        
                                        <p class="text-sm text-gray-400">
                                            Ordered {{ \Carbon\Carbon::parse($order->ordered_at ?? $order->created_at)->timezone('Asia/Manila')->format('M d, Y \a\t h:i A') }}
                                        </p>
                                        
                                        <!-- Show admin confirmation time if available -->
                                        @if($order->admin_confirmed_at)
                                            <p class="text-xs text-green-600 mt-1">
                                                ✓ Confirmed on {{ \Carbon\Carbon::parse($order->admin_confirmed_at)->timezone('Asia/Manila')->format('M d, Y \a\t h:i A') }}
                                            </p>
                                        @endif
                                        
                                        <!-- SHOW PAYMENT CONFIRMATION MESSAGE FOR CASH ORDERS -->
                                        @if($paymentMethod === 'cash' && $order->payment_status === 'paid' && $status === 'completed')
                                            <div class="mt-3 p-3 bg-green-50 border-l-4 border-green-500 rounded-lg">
                                                <div class="flex items-start gap-2">
                                                    <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <div>
                                                        <p class="text-sm font-semibold text-green-800">Payment Confirmed! 💵</p>
                                                        <p class="text-sm text-green-700 mt-1">Admin has confirmed your payment for this order.</p>
                                                        <p class="text-xs text-green-600 mt-2">Thank you for dining with us!</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <!-- SHOW REJECTION REASON FOR CANCELLED ORDERS -->
                                        @if($status === 'cancelled' && !empty($order->rejection_reason))
                                            <div class="mt-3 p-3 bg-red-50 border-l-4 border-red-500 rounded-lg">
                                                <div class="flex items-start gap-2">
                                                    <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <div>
                                                        <p class="text-sm font-semibold text-red-800">Order Rejection Reason:</p>
                                                        <p class="text-sm text-red-700 mt-1">{{ $order->rejection_reason }}</p>
                                                        <p class="text-xs text-red-600 mt-2">If you have questions, please contact our support team.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Right side - Action Buttons -->
                                    <div class="flex flex-row lg:flex-col gap-2 min-w-[140px]">
                                        <!-- View Details Button -->
                                        <a href="{{ route('client.orders.show', $order->order_number) }}" 
                                           class="px-4 py-2 bg-gray-600 text-white text-sm font-semibold rounded-xl hover:bg-gray-700 transition-all flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View Details
                                        </a>
                                        
                                        @if($status === 'ready')
                                            <!-- Store Location Button - ONLY FOR READY ORDERS -->
                                            <button onclick="showLocation()" 
                                                    class="px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-xl hover:bg-green-600 transition-all flex items-center justify-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                Store Location
                                            </button>
                                            
                                            <form action="{{ route('client.orders.picked-up', $order) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="w-full px-4 py-2 bg-purple-600 text-white text-sm font-semibold rounded-xl hover:bg-purple-700 transition-all flex items-center justify-center gap-2"
                                                        onclick="return confirm('Have you picked up your order?');">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Picked Up
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($status === 'completed' && $paymentMethod === 'cash' && $order->payment_status !== 'paid')
                                            <div class="mt-2 p-2 bg-yellow-50 rounded-lg text-xs text-yellow-700 text-center">
                                                ⏳ Payment pending<br>admin confirmation
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Quick preview of items -->
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <p class="text-sm font-medium text-gray-500 mb-2">Items:</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($order->items->take(3) as $item)
                                            <span class="text-sm bg-gray-100 px-3 py-1 rounded-full">
                                                {{ $item->quantity }}x {{ $item->item_name }}
                                            </span>
                                        @endforeach
                                        @if($order->items->count() > 3)
                                            <span class="text-sm text-gray-500">
                                                +{{ $order->items->count() - 3 }} more
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>

<!-- Store Location Modal -->
<div id="locationModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="closeLocation()"></div>
        
        <div class="relative bg-white rounded-3xl shadow-2xl max-w-2xl w-full">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">Store Location</h2>
                <button onclick="closeLocation()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="p-6">
                <div class="bg-gray-100 rounded-xl p-6 mb-4">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">2Dine-In Restaurant</h3>
                    <div class="space-y-2 text-gray-600">
                        <p class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-[#ea5a47] mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>San Juan Bautista, Goa, Camarines Sur</span>
                        </p>
                        <p class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-[#ea5a47] mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>(054) 123 4567</span>
                        </p>
                        <p class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-[#ea5a47] mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Open Daily: 10:00 AM - 10:00 PM</span>
                        </p>
                    </div>
                </div>
                
                <!-- Embedded Google Maps -->
                <div class="rounded-xl overflow-hidden h-96">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3881.2832050812537!2d123.489915!3d13.698056!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a2f1f1f1f1f1f1%3A0x0!2sSan%20Juan%20Bautista%2C%20Goa%2C%20Camarines%20Sur!5e0!3m2!1sen!2sph!4v1234567890"
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
                
                <div class="mt-4 flex gap-3">
                    <a href="https://maps.google.com/?q=San+Juan+Bautista+Goa+Camarines+Sur" 
                       target="_blank"
                       class="flex-1 px-4 py-2 bg-[#ea5a47] text-white rounded-lg hover:bg-[#c53030] transition-all text-center">
                        Open in Google Maps
                    </a>
                    <a href="https://waze.com/ul?q=San+Juan+Bautista+Goa+Camarines+Sur" 
                       target="_blank"
                       class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all text-center">
                        Open in Waze
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    
    
    // Tab switching function
    function switchTab(tabName) {
        const url = new URL(window.location);
        url.searchParams.set('tab', tabName);
        window.history.pushState({}, '', url);
        
        document.querySelectorAll('[role="tab"]').forEach(tab => {
            tab.classList.remove('border-[#ea5a47]', 'text-[#ea5a47]');
            tab.classList.add('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
            tab.setAttribute('aria-selected', 'false');
        });
        
        const activeTab = document.getElementById(tabName + '-tab');
        if (activeTab) {
            activeTab.classList.remove('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
            activeTab.classList.add('border-[#ea5a47]', 'text-[#ea5a47]');
            activeTab.setAttribute('aria-selected', 'true');
        }
        
        document.querySelectorAll('.tab-pane').forEach(pane => {
            pane.classList.add('hidden');
        });
        
        document.getElementById(tabName).classList.remove('hidden');
    }
    
    // Handle browser back/forward buttons
    window.addEventListener('popstate', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab') || 'pending';
        switchTab(tab);
    });

    // Initialize active tab on page load
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab') || 'pending';
        switchTab(tab);
    });
    
    // Store Location functions
    function showLocation() {
        const modal = document.getElementById('locationModal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeLocation() {
        const modal = document.getElementById('locationModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLocation();
        }
    });
</script>

<style>
    .animate-slide-down {
        animation: slideDown 0.3s ease-out;
    }
    
    @keyframes slideDown {
        from {
            transform: translateY(-20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    .animate-slide-up {
        animation: slideUp 0.3s ease-out;
    }
    
    @keyframes slideUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    /* Smooth transitions */
    .transition-all {
        transition: all 0.3s ease;
    }
    
    /* Tab content fade */
    .tab-pane {
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Modal animation */
    #locationModal {
        animation: fadeInModal 0.3s ease;
    }
    
    @keyframes fadeInModal {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
@endsection