@extends('admin.layouts.home', ['active' => 'orders'])

@section('title', 'Orders Management')

@section('content')

<div class="relative min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] overflow-hidden py-12 px-4 sm:px-6 lg:px-8">
    <!-- Decorative Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-20 -right-20 w-96 h-96 bg-[#ea5a47] opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-[#c53030] opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-to-r from-[#ea5a47]/5 to-[#c53030]/5 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="absolute inset-0 bg-[#ea5a47] rounded-lg blur-md opacity-30"></div>
                    <div class="relative bg-gradient-to-br from-[#ea5a47] to-[#c53030] p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
                <h1 class="text-3xl font-black text-gray-800">
                    Orders <span class="text-[#ea5a47]">Management</span>
                </h1>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg mb-6 animate-slideDown">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6 animate-slideDown">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Status Tabs -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl p-4 border border-white/20 relative overflow-hidden mb-6">
            <div class="absolute top-0 left-0 w-32 h-32 bg-gradient-to-br from-[#ea5a47] to-[#c53030] opacity-5 rounded-br-3xl"></div>
            
            <div class="relative z-10 flex flex-wrap gap-2">
                @php
                    $tabs = [
                        'pending' => [
                            'label' => 'Awaiting Confirmation', 
                            'color' => 'yellow', 
                            'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
                        ],
                        'preparing' => [
                            'label' => 'Preparing', 
                            'color' => 'purple', 
                            'icon' => 'M12 6v6m0 0v6m0-6h6m-6 0H6'
                        ],
                        'ready' => [
                            'label' => 'Ready for Pickup', 
                            'color' => 'green', 
                            'icon' => 'M5 13l4 4L19 7'
                        ],
                        'completed' => [
                            'label' => 'Completed', 
                            'color' => 'gray', 
                            'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                        ],
                        'cancelled' => [
                            'label' => 'Cancelled', 
                            'color' => 'red', 
                            'icon' => 'M6 18L18 6M6 6l12 12'
                        ],
                    ];
                    $currentTab = request('tab', 'pending');
                    
                    $activeClasses = [
                        'pending' => 'bg-yellow-100 text-yellow-700 border-2 border-yellow-300',
                        'preparing' => 'bg-purple-100 text-purple-700 border-2 border-purple-300',
                        'ready' => 'bg-green-100 text-green-700 border-2 border-green-300',
                        'completed' => 'bg-gray-100 text-gray-700 border-2 border-gray-300',
                        'cancelled' => 'bg-red-100 text-red-700 border-2 border-red-300',
                    ];
                @endphp
                
                @foreach($tabs as $key => $tab)
                    <a href="{{ route('admin.orders.index', ['tab' => $key]) }}" 
                       class="tab-link flex items-center gap-2 px-4 py-2 rounded-xl transition-all duration-200 whitespace-nowrap
                              {{ $currentTab == $key ? $activeClasses[$key] : 'hover:bg-gray-100 hover:scale-105' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}" />
                        </svg>
                        <span class="font-medium">{{ $tab['label'] }}</span>
                        <span class="ml-2 px-2 py-0.5 text-xs bg-white rounded-full shadow-sm">
                            {{ $counts[$key] ?? 0 }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-[#ea5a47] to-[#c53030] opacity-5 rounded-bl-3xl"></div>
            
            <div class="relative z-10 overflow-x-auto" style="max-height: calc(100vh - 320px); overflow-y: auto;">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs uppercase bg-gray-50/80 border-b-2 border-gray-200 sticky top-0 z-10">
                        <tr>
                            <th class="px-6 py-4 bg-gray-50/95">Order #</th>
                            <th class="px-6 py-4 bg-gray-50/95">Customer</th>
                            <th class="px-6 py-4 bg-gray-50/95">Items</th>
                            <th class="px-6 py-4 bg-gray-50/95">Total</th>
                            <th class="px-6 py-4 bg-gray-50/95">Payment Method</th>
                            <th class="px-6 py-4 bg-gray-50/95">Payment Status</th>
                            <th class="px-6 py-4 bg-gray-50/95">Order Status</th>
                            <th class="px-6 py-4 bg-gray-50/95">Admin Confirmation</th>
                            <th class="px-6 py-4 bg-gray-50/95">Ordered</th>
                            <th class="px-6 py-4 bg-gray-50/95">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($orders as $order)
                        @php
                            $paymentMethod = $order->payment_method ?? 'N/A';
                            $isCashPayment = ($paymentMethod === 'cash');
                            $needsConfirmation = ($order->order_status === 'pending' && !$order->admin_confirmed_at);
                            $canMarkAsPaid = ($isCashPayment && $order->payment_status === 'cash on pickup' && $order->order_status === 'completed');
                        @endphp

                        <tr class="hover:bg-gray-50/50 transition-colors duration-200" data-order-id="{{ $order->id }}">
                            <td class="px-6 py-4 font-mono text-sm">
                                <a href="{{ route('admin.orders.show', $order) }}" class="view-order-link text-[#ea5a47] hover:underline font-medium">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ $order->customer_name }}</div>
                                @if($order->customer_phone)
                                    <div class="text-xs text-gray-500">{{ $order->customer_phone }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">{{ $order->items->count() }} item(s)</div>
                                <div class="text-xs text-gray-500 truncate max-w-[200px]">
                                    {{ $order->items->pluck('item_name')->implode(', ') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900">
                                ₱{{ number_format($order->total, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @if($paymentMethod == 'cash')
                                    <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-medium whitespace-nowrap">💵 Cash on Pickup</span>
                                @elseif($paymentMethod == 'gcash')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium whitespace-nowrap">📱 GCash</span>
                                @elseif($paymentMethod == 'card')
                                    <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium whitespace-nowrap">💳 Card</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium whitespace-nowrap">{{ $paymentMethod }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($order->payment_status == 'paid')
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium whitespace-nowrap">✅ Paid</span>
                                @elseif($order->payment_status == 'cash on pickup')
                                    <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-medium whitespace-nowrap">💵 To Pay on Pickup</span>
                                @elseif($order->payment_status == 'pending')
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium whitespace-nowrap">⏳ Pending Payment</span>
                                @elseif($order->payment_status == 'failed')
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium whitespace-nowrap">❌ Failed</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium whitespace-nowrap">{{ ucfirst($order->payment_status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium rounded-full whitespace-nowrap
                                    @if($order->order_status === 'pending') bg-yellow-100 text-yellow-700
                                    @elseif($order->order_status === 'preparing') bg-purple-100 text-purple-700
                                    @elseif($order->order_status === 'ready') bg-green-100 text-green-700
                                    @elseif($order->order_status === 'completed') bg-gray-100 text-gray-700
                                    @elseif($order->order_status === 'cancelled') bg-red-100 text-red-700
                                    @endif">
                                    @if($order->order_status === 'pending' && !$order->admin_confirmed_at)
                                        Awaiting Confirmation
                                    @else
                                        {{ ucfirst($order->order_status) }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($order->admin_confirmed_at)
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">✓ Confirmed</span>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ \Carbon\Carbon::parse($order->admin_confirmed_at)->format('M d, h:i A') }}
                                    </div>
                                @else
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">⏳ Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500 whitespace-nowrap">
                                {{ $order->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="view-order-link p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 hover:scale-110 transition-all duration-300" title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    @if($needsConfirmation)
                                        <button onclick="openConfirmOrderModal('{{ $order->id }}', '{{ $order->order_number }}', '{{ $order->customer_name }}', '{{ $order->total }}', '{{ $order->payment_method }}', '{{ $order->payment_status }}', '{{ route('admin.orders.confirm', $order) }}')"
                                                class="p-2 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 hover:scale-110 transition-all duration-300 relative group"
                                                title="Confirm Order">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                            </span>
                                        </button>
                                    @endif

                                    @if($order->admin_confirmed_at && $order->order_status === 'pending')
                                        <button onclick="openStatusModal('{{ $order->id }}', '{{ $order->order_number }}', 'preparing', '{{ route('admin.orders.update-status', $order) }}', 'Start Preparing', 'This order will be moved to preparation queue.')"
                                                class="p-2 bg-purple-100 text-purple-600 rounded-lg hover:bg-purple-200 hover:scale-110 transition-all duration-300"
                                                title="Start Preparing">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </button>
                                    @endif

                                    @if($order->order_status === 'preparing')
                                        <button onclick="openStatusModal('{{ $order->id }}', '{{ $order->order_number }}', 'ready', '{{ route('admin.orders.update-status', $order) }}', 'Mark as Ready', 'This order will be marked as ready for customer pickup.')"
                                                class="p-2 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 hover:scale-110 transition-all duration-300"
                                                title="Mark as Ready">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    @endif

                                    @if($canMarkAsPaid)
                                        <button onclick="openPaidModal('{{ $order->id }}', '{{ $order->order_number }}', '{{ $order->total }}', '{{ route('admin.orders.mark-as-paid', $order) }}')"
                                                class="p-2 bg-green-600 text-white rounded-lg hover:bg-green-700 hover:scale-110 transition-all duration-300"
                                                title="Mark as Paid">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    @endif

                                    @if($needsConfirmation)
                                        <button onclick="openRejectOrderModal('{{ $order->id }}', '{{ $order->order_number }}', '{{ route('admin.orders.reject', $order) }}')"
                                                class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 hover:scale-110 transition-all duration-300"
                                                title="Reject Order">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <h5 class="text-xl font-bold text-gray-600 mb-2">No Orders Found</h5>
                                    <p class="text-gray-500">There are no orders in this category.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-white/95">
                {{ $orders->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- ==================== UNIFIED MODAL COMPONENT ==================== -->
<div id="universalModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-all duration-300" id="modalBackdrop"></div>
        
        <!-- Modal Container -->
        <div class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full transform transition-all duration-300 scale-95 opacity-0" id="modalContainer">
            <!-- Decorative Gradient Bar -->
            <div class="absolute top-0 left-0 right-0 h-2 rounded-t-3xl bg-gradient-to-r from-[#ea5a47] to-[#c53030]"></div>
            
            <!-- Modal Content -->
            <div class="p-6">
                <!-- Icon & Title -->
                <div class="flex items-center gap-4 mb-4" id="modalIconTitle"></div>
                
                <!-- Order Info -->
                <div id="modalOrderInfo" class="mb-6 p-4 bg-gray-50 rounded-xl border border-gray-100 hidden"></div>
                
                <!-- Message -->
                <p class="text-gray-600 leading-relaxed" id="modalMessage"></p>
                
                <!-- Rejection Reason (for reject modal) -->
                <div id="rejectionReasonContainer" class="mt-4 hidden">
                    <label class="block text-gray-700 font-medium mb-2 text-sm">Rejection Reason <span class="text-red-500">*</span></label>
                    <textarea id="rejectionReason" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:border-[#ea5a47] focus:ring-2 focus:ring-[#ea5a47]/20 outline-none transition-all" placeholder="Please provide a reason for rejection..."></textarea>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 mt-6" id="modalButtons"></div>
            </div>
            
            <!-- Close Button (Top Right) -->
            <button onclick="closeUniversalModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 hover:scale-110 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
    // ==================== VARIABLES ====================
    let isSubmitting = false;
    let currentModalData = null;
    let modalAnimationTimeout = null;

    // ==================== UNIVERSAL MODAL FUNCTIONS ====================
    
    function openUniversalModal(config) {
        if (modalAnimationTimeout) clearTimeout(modalAnimationTimeout);
        
        const modal = document.getElementById('universalModal');
        const container = document.getElementById('modalContainer');
        
        // Set content
        document.getElementById('modalIconTitle').innerHTML = config.iconTitle;
        document.getElementById('modalMessage').innerHTML = config.message;
        
        // Handle order info display
        const orderInfoDiv = document.getElementById('modalOrderInfo');
        if (config.orderInfo) {
            orderInfoDiv.innerHTML = config.orderInfo;
            orderInfoDiv.classList.remove('hidden');
        } else {
            orderInfoDiv.classList.add('hidden');
        }
        
        // Handle rejection reason container
        const rejectionContainer = document.getElementById('rejectionReasonContainer');
        if (config.showRejectionReason) {
            rejectionContainer.classList.remove('hidden');
            document.getElementById('rejectionReason').value = '';
        } else {
            rejectionContainer.classList.add('hidden');
        }
        
        // Set buttons
        document.getElementById('modalButtons').innerHTML = config.buttons;
        
        // Store config for form submission
        currentModalData = config;
        
        // Show modal with animation
        modal.classList.remove('hidden');
        setTimeout(() => {
            container.classList.remove('scale-95', 'opacity-0');
            container.classList.add('scale-100', 'opacity-100');
        }, 10);
        
        document.body.style.overflow = 'hidden';
    }
    
    function closeUniversalModal() {
        const modal = document.getElementById('universalModal');
        const container = document.getElementById('modalContainer');
        
        container.classList.remove('scale-100', 'opacity-100');
        container.classList.add('scale-95', 'opacity-0');
        
        modalAnimationTimeout = setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            currentModalData = null;
            isSubmitting = false;
        }, 300);
    }
    
    document.getElementById('modalBackdrop')?.addEventListener('click', closeUniversalModal);
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('universalModal');
            if (modal && !modal.classList.contains('hidden')) {
                closeUniversalModal();
            }
        }
    });

    // ==================== BUTTON LOADING STATES ====================
    function setButtonLoading(button, isLoading, loadingText = 'Processing...', originalText = null) {
        if (!button) return;
        
        if (isLoading) {
            button.disabled = true;
            button.setAttribute('data-original-text', originalText || button.innerHTML);
            button.classList.add('opacity-70', 'cursor-not-allowed');
            button.innerHTML = `<div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mx-auto"></div>`;
        } else {
            button.disabled = false;
            button.classList.remove('opacity-70', 'cursor-not-allowed');
            const original = button.getAttribute('data-original-text');
            if (original) {
                button.innerHTML = original;
                button.removeAttribute('data-original-text');
            }
        }
    }

    // ==================== MODAL CONFIGURATIONS ====================
    
    // Confirm Order Modal
    function openConfirmOrderModal(orderId, orderNumber, customerName, total, paymentMethod, paymentStatus, confirmUrl) {
        const paymentMethodDisplay = paymentMethod === 'cash' ? 'Cash on Pickup' : (paymentMethod === 'gcash' ? 'GCash' : (paymentMethod === 'card' ? 'Card' : paymentMethod));
        const paymentStatusDisplay = paymentStatus === 'paid' ? '✅ Paid' : (paymentStatus === 'cash on pickup' ? '💵 To Pay on Pickup' : paymentStatus);
        
        openUniversalModal({
            iconTitle: `
                <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-gray-800">Confirm Order</h2>
                    <p class="text-sm text-gray-500">Review order details before confirming</p>
                </div>
            `,
            orderInfo: `
                <div class="space-y-2">
                    <div class="flex justify-between items-center pb-2 border-b border-gray-200">
                        <span class="text-gray-500">Order Number:</span>
                        <span class="font-bold text-[#ea5a47]">${orderNumber}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Customer:</span>
                        <span class="font-medium">${escapeHtml(customerName)}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Total Amount:</span>
                        <span class="font-bold text-green-600">₱${parseFloat(total).toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Payment Method:</span>
                        <span class="px-2 py-1 bg-gray-100 rounded-full text-xs">${paymentMethodDisplay}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Payment Status:</span>
                        <span class="px-2 py-1 bg-gray-100 rounded-full text-xs">${paymentStatusDisplay}</span>
                    </div>
                </div>
            `,
            message: `Are you sure you want to confirm this order? This will move it to the preparation queue and notify the customer.`,
            showRejectionReason: false,
            buttons: `
                <button onclick="submitConfirmOrder('${confirmUrl}', this)" 
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 hover:shadow-lg hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2 font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Yes, Confirm Order
                </button>
                <button onclick="closeUniversalModal()" 
                        class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 hover:scale-105 transition-all duration-300 font-semibold">
                    Cancel
                </button>
            `
        });
    }
    
    function submitConfirmOrder(confirmUrl, button) {
        if (isSubmitting) return;
        isSubmitting = true;
        
        // Show loading on button
        setButtonLoading(button, true, 'Confirming...', button.innerHTML);
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = confirmUrl;
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        document.body.appendChild(form);
        
        setTimeout(() => form.submit(), 100);
    }
    
    // Status Update Modal (Preparing/Ready)
    function openStatusModal(orderId, orderNumber, status, actionUrl, buttonText, message) {
        const statusText = status === 'preparing' ? 'Start Preparing' : 'Mark as Ready';
        const statusColor = status === 'preparing' ? 'purple' : 'green';
        const iconPath = status === 'preparing' 
            ? 'M12 6v6m0 0v6m0-6h6m-6 0H6' 
            : 'M5 13l4 4L19 7';
        
        openUniversalModal({
            iconTitle: `
                <div class="w-14 h-14 rounded-full bg-${statusColor}-100 flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-${statusColor}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${iconPath}" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-gray-800">${statusText}</h2>
                    <p class="text-sm text-gray-500">Update order status</p>
                </div>
            `,
            orderInfo: `
                <div class="flex justify-between items-center pb-2 border-b border-gray-200">
                    <span class="text-gray-500">Order Number:</span>
                    <span class="font-bold text-[#ea5a47]">${orderNumber}</span>
                </div>
            `,
            message: message,
            showRejectionReason: false,
            buttons: `
                <button onclick="submitStatusUpdate('${actionUrl}', '${status}', this)" 
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-${statusColor}-600 to-${statusColor}-700 text-white rounded-xl hover:from-${statusColor}-700 hover:to-${statusColor}-800 hover:shadow-lg hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2 font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Yes, ${buttonText}
                </button>
                <button onclick="closeUniversalModal()" 
                        class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 hover:scale-105 transition-all duration-300 font-semibold">
                    Cancel
                </button>
            `
        });
    }
    
    function submitStatusUpdate(actionUrl, status, button) {
        if (isSubmitting) return;
        isSubmitting = true;
        
        // Show loading on button
        setButtonLoading(button, true, 'Updating...', button.innerHTML);
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = actionUrl;
        form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="status" value="${status}">
        `;
        document.body.appendChild(form);
        
        setTimeout(() => form.submit(), 100);
    }
    
    // Mark as Paid Modal
    function openPaidModal(orderId, orderNumber, total, paidUrl) {
        openUniversalModal({
            iconTitle: `
                <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-gray-800">Mark as Paid</h2>
                    <p class="text-sm text-gray-500">Confirm cash payment</p>
                </div>
            `,
            orderInfo: `
                <div class="space-y-2">
                    <div class="flex justify-between items-center pb-2 border-b border-gray-200">
                        <span class="text-gray-500">Order Number:</span>
                        <span class="font-bold text-[#ea5a47]">${orderNumber}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Total Amount:</span>
                        <span class="font-bold text-green-600">₱${parseFloat(total).toFixed(2)}</span>
                    </div>
                </div>
            `,
            message: `Confirm that the customer has paid the full amount in cash. This will mark the order as paid.`,
            showRejectionReason: false,
            buttons: `
                <button onclick="submitMarkAsPaid('${paidUrl}', this)" 
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 hover:shadow-lg hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2 font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Yes, Mark as Paid
                </button>
                <button onclick="closeUniversalModal()" 
                        class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 hover:scale-105 transition-all duration-300 font-semibold">
                    Cancel
                </button>
            `
        });
    }
    
    function submitMarkAsPaid(paidUrl, button) {
        if (isSubmitting) return;
        isSubmitting = true;
        
        // Show loading on button
        setButtonLoading(button, true, 'Processing...', button.innerHTML);
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = paidUrl;
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        document.body.appendChild(form);
        
        setTimeout(() => form.submit(), 100);
    }
    
    // Reject Order Modal
    function openRejectOrderModal(orderId, orderNumber, rejectUrl) {
        openUniversalModal({
            iconTitle: `
                <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-gray-800">Reject Order</h2>
                    <p class="text-sm text-gray-500">Provide rejection reason</p>
                </div>
            `,
            orderInfo: `
                <div class="flex justify-between items-center pb-2 border-b border-gray-200">
                    <span class="text-gray-500">Order Number:</span>
                    <span class="font-bold text-[#ea5a47]">${orderNumber}</span>
                </div>
            `,
            message: `Please provide a reason for rejecting this order. This will be visible to the customer.`,
            showRejectionReason: true,
            buttons: `
                <button onclick="submitRejectOrder('${rejectUrl}', this)" 
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 hover:shadow-lg hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2 font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Yes, Reject Order
                </button>
                <button onclick="closeUniversalModal()" 
                        class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 hover:scale-105 transition-all duration-300 font-semibold">
                    Cancel
                </button>
            `
        });
    }
    
    function submitRejectOrder(rejectUrl, button) {
        if (isSubmitting) return;
        
        const reason = document.getElementById('rejectionReason')?.value.trim();
        if (!reason) {
            if (window.showToast) {
                window.showToast('Please provide a rejection reason', true);
            } else {
                alert('Please provide a rejection reason');
            }
            return;
        }
        
        isSubmitting = true;
        
        // Show loading on button
        setButtonLoading(button, true, 'Rejecting...', button.innerHTML);
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = rejectUrl;
        form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="rejection_reason" value="${escapeHtml(reason)}">
        `;
        document.body.appendChild(form);
        
        setTimeout(() => form.submit(), 100);
    }
    
    // Helper function to escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Handle tab clicks - show page loader only for navigation
    document.querySelectorAll('.tab-link').forEach(link => {
        link.addEventListener('click', function(e) {
            if (window.showLoader && !isSubmitting) {
                window.showLoader();
            }
        });
    });

    // Handle view order links - show page loader only for navigation
    document.querySelectorAll('.view-order-link').forEach(link => {
        link.addEventListener('click', function(e) {
            if (window.showLoader && !isSubmitting) {
                window.showLoader();
            }
        });
    });

    // Reset flags on page show
    window.addEventListener('pageshow', function() {
        isSubmitting = false;
    });

    // Auto-hide success alerts
    setTimeout(function() {
        document.querySelectorAll('.bg-green-50, .bg-red-50').forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 3000);
</script>

<style>
    .animate-slideDown {
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
    
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .overflow-x-auto::-webkit-scrollbar {
        height: 4px;
        width: 6px;
    }
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #ea5a47;
        border-radius: 4px;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #c53030;
    }
    
    .sticky th {
        position: sticky;
        top: 0;
        background: rgba(249, 250, 251, 0.95);
        backdrop-filter: blur(4px);
    }
    
    #modalContainer {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Additional hover effects for all interactive elements */
    button, a, .tab-link {
        transition: all 0.3s ease;
    }
    
    button:active, a:active {
        transform: scale(0.98);
    }
</style>
@endsection