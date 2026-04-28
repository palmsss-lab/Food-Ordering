@extends('admin.layouts.home', ['active' => 'order-details'])

@section('title', 'Order Details')

@section('content')
<div class="relative min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] overflow-hidden py-12 px-4 sm:px-6 lg:px-8">
    <div class="relative z-10 max-w-7xl mx-auto">
        <!-- Header with back button -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.orders.index', ['tab' => request('tab', 'pending')]) }}" 
                   id="back-to-orders"
                   class="p-2 bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl sm:text-3xl font-black text-gray-800">
                        Order <span class="text-[#ea5a47]">{{ $order->order_number }}</span>
                    </h1>
                    <p class="text-gray-500">Placed {{ $order->ordered_at ? $order->ordered_at->diffForHumans() : $order->created_at->diffForHumans() }}</p>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3">
                <!-- Confirm Order Button (for pending unconfirmed orders) -->
                @if($order->order_status === 'pending' && !$order->admin_confirmed_at)
                    <button onclick="openConfirmModal()" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Confirm Order
                    </button>
                @endif

                <!-- Reject Order Button (for pending unconfirmed orders) -->
                @if($order->order_status === 'pending' && !$order->admin_confirmed_at)
                    <button onclick="openRejectModal()" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Reject Order
                    </button>
                @endif

                <!-- Mark as Ready (for confirmed orders) -->
                @if($order->order_status === 'confirmed' && $order->admin_confirmed_at && !$order->prepared_at)
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="inline" id="ready-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="ready">
                        <button type="submit" 
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2"
                                onclick="return confirmReadyOrder()">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Mark as Ready
                        </button>
                    </form>
                @endif

                <!-- Mark as Paid (for cash orders) - Admin decision button -->
                @if($order->payment_method === 'cash' && $order->payment_status === 'cash on pickup' && $order->order_status === 'completed')
                    <form action="{{ route('admin.orders.mark-as-paid', $order) }}" method="POST" class="inline" id="paid-form">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2"
                                onclick="return confirmPaidOrder()">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Mark as Paid
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Status Badges Row - Sticky on scroll -->
        <div class="sticky top-0 z-20 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Order Status Badge -->
                <div class="bg-white/95 backdrop-blur-sm rounded-xl shadow-lg p-4 border border-white/20">
                    <p class="text-sm text-gray-500 mb-1">Order Status</p>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1.5 rounded-full text-sm font-bold
                            @if($order->order_status === 'pending') bg-yellow-100 text-yellow-700
                            @elseif($order->order_status === 'confirmed') bg-blue-100 text-blue-700
                            @elseif($order->order_status === 'ready') bg-green-100 text-green-700
                            @elseif($order->order_status === 'completed') bg-gray-100 text-gray-700
                            @elseif($order->order_status === 'cancelled') bg-red-100 text-red-700
                            @endif">
                            @if($order->order_status === 'pending' && !$order->admin_confirmed_at)
                                Awaiting Confirmation
                            @elseif($order->order_status === 'pending' && $order->admin_confirmed_at)
                                Confirmed - Awaiting Preparation
                            @else
                                {{ ucfirst($order->order_status) }}
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Admin Confirmation Badge -->
                <div class="bg-white/95 backdrop-blur-sm rounded-xl shadow-lg p-4 border border-white/20">
                    <p class="text-sm text-gray-500 mb-1">Admin Confirmation</p>
                    <div class="flex items-center gap-2">
                        @if($order->admin_confirmed_at)
                            <span class="px-3 py-1.5 bg-green-100 text-green-700 rounded-full text-sm font-bold">
                                ✓ Confirmed
                            </span>
                            <span class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($order->admin_confirmed_at)->format('M d, h:i A') }}
                            </span>
                        @else
                            <span class="px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-full text-sm font-bold">
                                ⏳ Pending Confirmation
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Payment Method Badge -->
                <div class="bg-white/95 backdrop-blur-sm rounded-xl shadow-lg p-4 border border-white/20">
                    <p class="text-sm text-gray-500 mb-1">Payment Method</p>
                    <div class="flex items-center gap-2">
                        @php
                            $paymentMethod = $order->payment_method ?? 'N/A';
                        @endphp
                        @if($paymentMethod == 'cash')
                            <span class="px-3 py-1.5 bg-orange-100 text-orange-700 rounded-full text-sm font-bold">
                                💵 Cash on Pickup
                            </span>
                        @elseif($paymentMethod == 'gcash')
                            <span class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-full text-sm font-bold">
                                📱 GCash
                            </span>
                        @elseif($paymentMethod == 'card')
                            <span class="px-3 py-1.5 bg-purple-100 text-purple-700 rounded-full text-sm font-bold">
                                💳 Card
                            </span>
                        @else
                            <span class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-full text-sm font-bold">
                                {{ $paymentMethod }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Payment Status Badge -->
                <div class="bg-white/95 backdrop-blur-sm rounded-xl shadow-lg p-4 border border-white/20">
                    <p class="text-sm text-gray-500 mb-1">Payment Status</p>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1.5 rounded-full text-sm font-bold
                            @if($order->payment_status == 'paid') bg-green-100 text-green-700
                            @elseif($order->payment_status == 'cash on pickup') bg-orange-100 text-orange-700
                            @elseif($order->payment_status == 'pending') bg-yellow-100 text-yellow-700
                            @elseif($order->payment_status == 'failed') bg-red-100 text-red-700
                            @endif">
                            @if($order->payment_status == 'paid')
                                ✅ Paid
                            @elseif($order->payment_status == 'cash on pickup')
                                💵 To Pay on Pickup
                            @elseif($order->payment_status == 'pending')
                                ⏳ Pending Payment
                            @elseif($order->payment_status == 'failed')
                                ❌ Failed
                            @else
                                {{ ucfirst($order->payment_status) }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- SCROLLABLE MAIN CONTENT -->
        <div class="max-h-[calc(100vh-280px)] overflow-y-auto pr-2 space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Customer Information -->
                <div class="lg:col-span-1">
                    <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-xl p-6 border border-white/20">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Customer Details
                        </h2>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm text-gray-500">Name</label>
                                <p class="font-medium text-gray-800">{{ $order->customer_name }}</p>
                            </div>
                            @if($order->customer_phone)
                            <div>
                                <label class="text-sm text-gray-500">Phone</label>
                                <p class="font-medium text-gray-800">{{ $order->customer_phone }}</p>
                            </div>
                            @endif
                            @if($order->customer_email)
                            <div>
                                <label class="text-sm text-gray-500">Email</label>
                                <p class="font-medium text-gray-800">{{ $order->customer_email }}</p>
                            </div>
                            @endif
                            <div>
                                <label class="text-sm text-gray-500">Order Type</label>
                                <p class="font-medium text-gray-800 capitalize">{{ $order->order_type }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-xl p-6 border border-white/20">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Order Items
                        </h2>
                        <div class="space-y-4">
                            @foreach($order->items as $item)
                            <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800">{{ $item->item_name }}</p>
                                    <p class="text-sm text-gray-500">Qty: {{ $item->quantity }} x ₱{{ number_format($item->price, 2) }}</p>
                                </div>
                                <p class="font-bold text-[#ea5a47]">₱{{ number_format($item->subtotal, 2) }}</p>
                            </div>
                            @endforeach
                        </div>

                        <!-- Order Summary -->
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span class="font-medium">₱{{ number_format($order->subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Tax (12%):</span>
                                    <span class="font-medium">₱{{ number_format($order->tax, 2) }}</span>
                                </div>
                                @if(($order->promo_discount ?? 0) > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">{{ $order->promo_label ?: 'Promotion' }}:</span>
                                    <span class="font-medium text-green-600">-₱{{ number_format($order->promo_discount, 2) }}</span>
                                </div>
                                @endif
                                @if(($order->discount ?? 0) > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">{{ $order->discount_label ?: 'Discount' }}:</span>
                                    <span class="font-medium text-green-600">-₱{{ number_format($order->discount, 2) }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between font-bold text-lg pt-2">
                                    <span class="text-gray-800">Total:</span>
                                    <span class="text-[#ea5a47]">₱{{ number_format($order->total, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        @if($order->notes)
                        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600"><span class="font-medium">Notes:</span> {{ $order->notes }}</p>
                        </div>
                        @endif

                        <!-- Cancellation/Rejection Reason -->
                        @if($order->rejection_reason && $order->order_status === 'cancelled')
                        <div class="mt-4 p-3 bg-red-50 rounded-lg">
                            <p class="text-sm text-red-600"><span class="font-medium">Rejection Reason:</span> {{ $order->rejection_reason }}</p>
                        </div>
                        @endif

                        <!-- Payment Information for Cash Orders -->
                        @if($order->payment_method === 'cash')
                        <div class="mt-4 p-3 bg-orange-50 rounded-lg">
                            <p class="text-sm text-orange-700">
                                <span class="font-medium">💵 Cash on Pickup:</span> 
                                Payment will be collected when the customer picks up the order.
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-xl p-6 border border-white/20">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Order Timeline
                </h2>
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
                            <p class="font-medium">Confirmed by Admin</p>
                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($order->admin_confirmed_at)->timezone('Asia/Manila')->format('M d, Y h:i A') }}</p>
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
    </div>
</div>

<!-- Confirm Order Modal -->
<div id="confirmOrderModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="closeConfirmModal()"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Confirm Order</h2>
            </div>
            
            <p class="text-sm text-gray-600 mb-4">Are you sure you want to confirm this order? This will:</p>
            
            <ul class="list-disc list-inside text-sm text-gray-600 mb-6 space-y-1">
                <li>Mark the order as confirmed by admin</li>
                <li>Move the order to preparation</li>
                <li>Notify the customer that their order has been confirmed</li>
            </ul>
            
            <form action="{{ route('admin.orders.confirm', $order) }}" method="POST" id="confirm-form">
                @csrf
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit" 
                            class="flex-1 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all font-medium shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Yes, Confirm Order
                    </button>
                    
                    <button type="button" onclick="closeConfirmModal()"
                            class="px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-all font-medium">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Order Modal -->
<div id="rejectOrderModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="closeRejectModal()"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Reject Order</h2>
            </div>
            
            <p class="text-sm text-gray-600 mb-4">Please provide a reason for rejecting this order. The order will be cancelled.</p>
            
            <form action="{{ route('admin.orders.reject', $order) }}" method="POST" id="reject-form">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Rejection Reason <span class="text-red-500">*</span></label>
                    <textarea name="rejection_reason" rows="3" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-[#ea5a47] outline-none"
                              placeholder="Please provide a reason for rejection..."></textarea>
                </div>
                
                <div class="flex gap-3">
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all">
                        Reject Order
                    </button>
                    <button type="button" onclick="closeRejectModal()"
                            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-all">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let isSubmitting = false;

function openConfirmModal() {
    document.getElementById('confirmOrderModal').classList.remove('hidden');
}

function closeConfirmModal() {
    document.getElementById('confirmOrderModal').classList.add('hidden');
}

function openRejectModal() {
    document.getElementById('rejectOrderModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectOrderModal').classList.add('hidden');
}

function confirmReadyOrder() {
    if (isSubmitting) return false;
    isSubmitting = true;
    
    if (window.showLoader) {
        window.showLoader();
    }
    
    return true;
}

function confirmPaidOrder() {
    if (isSubmitting) return false;
    isSubmitting = true;
    
    if (window.showLoader) {
        window.showLoader();
    }
    
    return true;
}

// Handle form submissions with loader
document.addEventListener('DOMContentLoaded', function() {
    // Confirm form
    const confirmForm = document.getElementById('confirm-form');
    if (confirmForm) {
        confirmForm.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return;
            }
            isSubmitting = true;
            if (window.showLoader) window.showLoader();
        });
    }
    
    // Reject form
    const rejectForm = document.getElementById('reject-form');
    if (rejectForm) {
        rejectForm.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return;
            }
            isSubmitting = true;
            if (window.showLoader) window.showLoader();
        });
    }
    
    // Ready form
    const readyForm = document.getElementById('ready-form');
    if (readyForm) {
        readyForm.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return;
            }
            isSubmitting = true;
            if (window.showLoader) window.showLoader();
        });
    }
    
    // Paid form
    const paidForm = document.getElementById('paid-form');
    if (paidForm) {
        paidForm.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return;
            }
            isSubmitting = true;
            if (window.showLoader) window.showLoader();
        });
    }
    
    // Back button
    const backButton = document.getElementById('back-to-orders');
    if (backButton) {
        backButton.addEventListener('click', function(e) {
            if (window.showLoader) window.showLoader();
        });
    }
    
    // Reset flag on page show
    window.addEventListener('pageshow', function() {
        isSubmitting = false;
    });
});

// Close modals when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('fixed')) {
        closeConfirmModal();
        closeRejectModal();
    }
}
</script>

<style>
    /* Custom scrollbar styling */
    .overflow-y-auto::-webkit-scrollbar {
        width: 8px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #ea5a47;
        border-radius: 10px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #c53030;
    }
    
    /* Sticky header animation */
    .sticky {
        transition: all 0.3s ease;
    }
</style>
@endsection