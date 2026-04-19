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
                <h1 class="text-xl sm:text-3xl font-black text-gray-800">
                    Orders <span class="text-[#ea5a47]">Management</span>
                </h1>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg mb-6 animate-slideDown flex items-center justify-between" role="alert">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.closest('[role=alert]').remove()" aria-label="Dismiss" class="text-green-400 hover:text-green-600 ml-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6 animate-slideDown flex items-center justify-between" role="alert">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.closest('[role=alert]').remove()" aria-label="Dismiss" class="text-red-400 hover:text-red-600 ml-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        <livewire:admin.orders-list />

    </div>
</div>

<!-- ==================== BULK ACTION BAR ==================== -->
<div id="bulk-action-bar"
     class="hidden fixed bottom-4 sm:bottom-6 left-1/2 -translate-x-1/2 z-40 bg-gray-900 text-white rounded-2xl shadow-2xl px-4 sm:px-6 py-3 flex flex-wrap items-center gap-2 sm:gap-4 transition-all duration-300 max-w-[calc(100vw-2rem)]"
     role="toolbar" aria-label="Bulk order actions">
    <span class="text-sm font-medium" id="bulk-selected-label">0 selected</span>
    <div class="h-4 w-px bg-gray-600"></div>
    <button type="button" id="bulk-btn-confirm" onclick="bulkConfirmOrders()"
            class="hidden px-4 py-1.5 bg-green-500 hover:bg-green-400 text-white text-sm font-semibold rounded-lg transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        Confirm Selected
    </button>
    <button type="button" id="bulk-btn-ready" onclick="bulkMarkReady()"
            class="hidden px-4 py-1.5 bg-green-500 hover:bg-green-400 text-white text-sm font-semibold rounded-lg transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        Mark as Ready
    </button>
    <button type="button" id="bulk-btn-paid" onclick="bulkMarkAsPaid()"
            class="hidden px-4 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-lg transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Mark as Paid
    </button>
    <button type="button" onclick="clearBulkSelection()"
            class="px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-sm font-medium rounded-lg transition-colors">
        Deselect All
    </button>
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
            <button type="button" onclick="closeUniversalModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 hover:scale-110 transition-all duration-300">
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
            button.classList.add('opacity-50', 'cursor-not-allowed');
            button.innerHTML = `<div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mx-auto"></div>`;
        } else {
            button.disabled = false;
            button.classList.remove('opacity-50', 'cursor-not-allowed');
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
                <button type="button" onclick="submitConfirmOrder('${confirmUrl}', this)"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 hover:shadow-lg hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2 font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Yes, Confirm Order
                </button>
                <button type="button" onclick="closeUniversalModal()"
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
    function openStatusModal(orderId, orderNumber, status, actionUrl, buttonText, message, customerName, total, paymentMethod) {
        const isPreparing  = status === 'preparing';
        const statusColor  = isPreparing ? 'purple' : 'green';
        const iconPath     = isPreparing ? 'M12 6v6m0 0v6m0-6h6m-6 0H6' : 'M5 13l4 4L19 7';
        const titleText    = isPreparing ? 'Start Preparing' : 'Mark as Ready';
        const subtitleText = isPreparing ? 'Move order to kitchen queue' : 'Notify customer for pickup';

        const pmDisplay = paymentMethod === 'cash' ? 'Cash on Pickup'
                        : paymentMethod === 'gcash' ? 'GCash'
                        : paymentMethod === 'card'  ? 'Card'
                        : (paymentMethod || '—');

        const totalDisplay = total ? '₱' + parseFloat(total).toFixed(2) : '—';

        openUniversalModal({
            iconTitle: `
                <div class="w-14 h-14 rounded-full bg-${statusColor}-100 flex items-center justify-center shadow-lg flex-shrink-0">
                    <svg class="w-7 h-7 text-${statusColor}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${iconPath}" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-gray-800">${titleText}</h2>
                    <p class="text-sm text-gray-500">${subtitleText}</p>
                </div>`,
            orderInfo: `
                <div class="space-y-2">
                    <div class="flex justify-between items-center pb-2 border-b border-gray-200">
                        <span class="text-gray-500">Order Number:</span>
                        <span class="font-bold text-[#ea5a47]">${orderNumber}</span>
                    </div>
                    ${customerName ? `
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Customer:</span>
                        <span class="font-medium">${escapeHtml(customerName)}</span>
                    </div>` : ''}
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Total Amount:</span>
                        <span class="font-bold text-green-600">${totalDisplay}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Payment Method:</span>
                        <span class="px-2 py-1 bg-gray-100 rounded-full text-xs">${pmDisplay}</span>
                    </div>
                </div>`,
            message: message,
            showRejectionReason: false,
            buttons: `
                <button type="button" onclick="submitStatusUpdate('${actionUrl}', '${status}', this)"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-${statusColor}-600 to-${statusColor}-700 text-white rounded-xl hover:from-${statusColor}-700 hover:to-${statusColor}-800 hover:shadow-lg hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2 font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Yes, ${buttonText}
                </button>
                <button type="button" onclick="closeUniversalModal()"
                        class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 hover:scale-105 transition-all duration-300 font-semibold">
                    Cancel
                </button>`
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
                <button type="button" onclick="submitMarkAsPaid('${paidUrl}', this)"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 hover:shadow-lg hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2 font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Yes, Mark as Paid
                </button>
                <button type="button" onclick="closeUniversalModal()"
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
                <button type="button" onclick="submitRejectOrder('${rejectUrl}', this)"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 hover:shadow-lg hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2 font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Yes, Reject Order
                </button>
                <button type="button" onclick="closeUniversalModal()"
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

<script>
// ==================== BULK ORDER ACTIONS ====================

// Restore checkboxes after Livewire re-renders (wire:poll re-renders every 5s)
let _bulkSelectedIds = new Set();
let _lastKnownTab = null;

document.addEventListener('livewire:morph', function () {
    const currentTab = getActiveTab();

    // Tab changed — clear stale selection from the previous tab
    if (_lastKnownTab !== null && _lastKnownTab !== currentTab) {
        _bulkSelectedIds.clear();
        const selectAll = document.getElementById('bulk-select-all');
        if (selectAll) selectAll.checked = false;
    }
    _lastKnownTab = currentTab;

    restoreCheckboxState();
});

function getActiveTab() {
    const root = document.querySelector('[data-active-tab]');
    return root ? root.getAttribute('data-active-tab') : null;
}

function restoreCheckboxState() {
    if (_bulkSelectedIds.size === 0) {
        updateBulkBar();
        return;
    }
    document.querySelectorAll('.bulk-order-checkbox').forEach(cb => {
        const id = cb.getAttribute('data-order-id');
        if (_bulkSelectedIds.has(id)) cb.checked = true;
    });
    updateBulkBar();
}

function toggleBulkSelectAll(selectAll) {
    document.querySelectorAll('.bulk-order-checkbox').forEach(cb => {
        cb.checked = selectAll.checked;
        const id = cb.getAttribute('data-order-id');
        if (selectAll.checked) {
            _bulkSelectedIds.add(id);
        } else {
            _bulkSelectedIds.delete(id);
        }
    });
    updateBulkBar();
}

function updateBulkBar() {
    // Sync internal state with only the currently visible checkboxes
    const visibleIds = new Set();
    document.querySelectorAll('.bulk-order-checkbox').forEach(cb => {
        const id = cb.getAttribute('data-order-id');
        visibleIds.add(id);
        if (cb.checked) {
            _bulkSelectedIds.add(id);
        } else {
            _bulkSelectedIds.delete(id);
        }
    });

    // Remove IDs that are no longer in the DOM (stale from previous tab)
    for (const id of [..._bulkSelectedIds]) {
        if (!visibleIds.has(id)) _bulkSelectedIds.delete(id);
    }

    const count = _bulkSelectedIds.size;
    const bar   = document.getElementById('bulk-action-bar');
    const label = document.getElementById('bulk-selected-label');

    if (count > 0) {
        bar.classList.remove('hidden');
        label.textContent = `${count} order${count !== 1 ? 's' : ''} selected`;
    } else {
        bar.classList.add('hidden');
    }

    // Show bulk buttons based on active tab
    const tab = getActiveTab();

    const confirmBtn = document.getElementById('bulk-btn-confirm');
    const readyBtn   = document.getElementById('bulk-btn-ready');
    const paidBtn    = document.getElementById('bulk-btn-paid');

    if (confirmBtn) confirmBtn.classList.toggle('hidden', tab !== 'pending');
    if (readyBtn)   readyBtn.classList.toggle('hidden',   tab !== 'preparing');
    if (paidBtn)    paidBtn.classList.toggle('hidden',    tab !== 'completed');
}

function clearBulkSelection() {
    _bulkSelectedIds.clear();
    document.querySelectorAll('.bulk-order-checkbox').forEach(cb => cb.checked = false);
    const selectAll = document.getElementById('bulk-select-all');
    if (selectAll) selectAll.checked = false;
    updateBulkBar();
}

function setBulkLoading(isLoading) {
    const buttons = document.querySelectorAll('#bulk-btn-confirm, #bulk-btn-ready, #bulk-btn-paid');
    buttons.forEach(btn => {
        btn.disabled = isLoading;
        btn.classList.toggle('opacity-50', isLoading);
        btn.classList.toggle('cursor-not-allowed', isLoading);
        if (isLoading) {
            btn.dataset.originalHtml = btn.innerHTML;
            btn.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>';
        } else if (btn.dataset.originalHtml) {
            btn.innerHTML = btn.dataset.originalHtml;
            delete btn.dataset.originalHtml;
        }
    });
}

// Switch the Livewire tab by clicking its button
function switchToTab(tabKey) {
    const btn = [...document.querySelectorAll('[wire\\:click]')]
        .find(el => el.getAttribute('wire:click') === `setTab('${tabKey}')`);
    if (btn) btn.click();
}

// Build a [{id, number}] list from the currently selected IDs using DOM data attributes
function getSelectedOrderMeta() {
    const meta = [];
    document.querySelectorAll('.bulk-order-row').forEach(row => {
        const id = row.getAttribute('data-order-id');
        if (_bulkSelectedIds.has(id)) {
            meta.push({ id, number: row.getAttribute('data-order-number') || id });
        }
    });
    return meta;
}

function buildOrderListHtml(orders) {
    const rows = orders.map(o =>
        `<div class="flex items-center gap-2 py-1.5 border-b border-gray-100 last:border-0">
            <svg class="w-3.5 h-3.5 text-[#ea5a47] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="font-semibold text-[#ea5a47] text-sm">${escapeHtml(o.number)}</span>
        </div>`
    ).join('');

    const scrollClass = orders.length > 5 ? 'max-h-40 overflow-y-auto' : '';
    return `<div class="${scrollClass} pr-1">${rows}</div>`;
}

function bulkConfirmOrders() {
    if (_bulkSelectedIds.size === 0) return;

    const orders = getSelectedOrderMeta();
    const count  = orders.length;

    openUniversalModal({
        iconTitle: `
            <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center shadow-lg flex-shrink-0">
                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-black text-gray-800">Confirm ${count} Order${count !== 1 ? 's' : ''}</h2>
                <p class="text-sm text-gray-500">Review before sending to kitchen</p>
            </div>`,
        orderInfo: `
            <div class="mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">Selected Orders</div>
            ${buildOrderListHtml(orders)}`,
        message: `These <strong>${count} order${count !== 1 ? 's' : ''}</strong> will be moved to the <strong>Preparing</strong> queue and the kitchen will be notified.`,
        showRejectionReason: false,
        buttons: `
            <button type="button" id="bulk-confirm-submit-btn"
                    onclick="executeBulkConfirm(this)"
                    class="flex-1 px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 hover:shadow-lg hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2 font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Yes, Confirm All
            </button>
            <button type="button" onclick="closeUniversalModal()"
                    class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 hover:scale-105 transition-all duration-300 font-semibold">
                Cancel
            </button>`
    });
}

function executeBulkConfirm(button) {
    if (isSubmitting) return;
    isSubmitting = true;

    const ids = [..._bulkSelectedIds];
    setButtonLoading(button, true, 'Confirming...', button.innerHTML);

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const promises  = ids.map(id =>
        fetch(`/admin/orders/${id}/confirm`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        })
        .then(r => r.json().then(data => ({ success: r.ok && data.success, id })))
        .catch(() => ({ success: false, id }))
    );

    Promise.all(promises).then(results => {
        isSubmitting = false;
        closeUniversalModal();
        clearBulkSelection();

        const succeeded = results.filter(r => r.success).length;
        const failed    = results.filter(r => !r.success).length;

        if (succeeded > 0) {
            const msg = failed > 0
                ? `${succeeded} order${succeeded !== 1 ? 's' : ''} confirmed. ${failed} could not be confirmed.`
                : `${succeeded} order${succeeded !== 1 ? 's' : ''} confirmed and moved to Preparing.`;
            window.showToast(msg, false);
            switchToTab('preparing');
        } else {
            window.showToast('No orders could be confirmed. They may already be confirmed or cancelled.', true);
        }
    });
}

function bulkMarkReady() {
    if (_bulkSelectedIds.size === 0) return;

    const orders = getSelectedOrderMeta();
    const count  = orders.length;

    openUniversalModal({
        iconTitle: `
            <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center shadow-lg flex-shrink-0">
                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-black text-gray-800">Mark ${count} Order${count !== 1 ? 's' : ''} Ready</h2>
                <p class="text-sm text-gray-500">Ready for customer pickup</p>
            </div>`,
        orderInfo: `
            <div class="mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">Selected Orders</div>
            ${buildOrderListHtml(orders)}`,
        message: `These <strong>${count} order${count !== 1 ? 's' : ''}</strong> will be marked as <strong>Ready for Pickup</strong> and customers will be notified.`,
        showRejectionReason: false,
        buttons: `
            <button type="button" id="bulk-ready-submit-btn"
                    onclick="executeBulkMarkReady(this)"
                    class="flex-1 px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 hover:shadow-lg hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2 font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Yes, Mark All Ready
            </button>
            <button type="button" onclick="closeUniversalModal()"
                    class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 hover:scale-105 transition-all duration-300 font-semibold">
                Cancel
            </button>`
    });
}

function executeBulkMarkReady(button) {
    if (isSubmitting) return;
    isSubmitting = true;

    const ids = [..._bulkSelectedIds];
    setButtonLoading(button, true, 'Updating...', button.innerHTML);

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const promises  = ids.map(id =>
        fetch(`/admin/orders/${id}/update-status`, {
            method: 'PUT',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ status: 'ready' }),
        })
        .then(r => r.json().then(data => ({ success: r.ok && data.success, id })))
        .catch(() => ({ success: false, id }))
    );

    Promise.all(promises).then(results => {
        isSubmitting = false;
        closeUniversalModal();
        clearBulkSelection();

        const succeeded = results.filter(r => r.success).length;
        const failed    = results.filter(r => !r.success).length;

        if (succeeded > 0) {
            const msg = failed > 0
                ? `${succeeded} order${succeeded !== 1 ? 's' : ''} marked ready. ${failed} could not be updated.`
                : `${succeeded} order${succeeded !== 1 ? 's' : ''} marked as Ready for Pickup.`;
            window.showToast(msg, false);
            switchToTab('ready');
        } else {
            window.showToast('No orders could be marked ready. They must be in Preparing status first.', true);
        }
    });
}

function bulkMarkAsPaid() {
    if (_bulkSelectedIds.size === 0) return;

    const orders = getSelectedOrderMeta();
    const count  = orders.length;

    openUniversalModal({
        iconTitle: `
            <div class="w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center shadow-lg flex-shrink-0">
                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-black text-gray-800">Mark ${count} Order${count !== 1 ? 's' : ''} as Paid</h2>
                <p class="text-sm text-gray-500">Cash on pickup — payment received</p>
            </div>`,
        orderInfo: `
            <div class="mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">Selected Orders</div>
            ${buildOrderListHtml(orders)}`,
        message: `Confirm that cash payment has been collected for <strong>${count} order${count !== 1 ? 's' : ''}</strong>. Only cash orders that are not yet paid will be updated.`,
        showRejectionReason: false,
        buttons: `
            <button type="button" id="bulk-paid-submit-btn"
                    onclick="executeBulkMarkAsPaid(this)"
                    class="flex-1 px-4 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-xl hover:from-emerald-700 hover:to-emerald-800 hover:shadow-lg hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2 font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Yes, Mark All as Paid
            </button>
            <button type="button" onclick="closeUniversalModal()"
                    class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 hover:scale-105 transition-all duration-300 font-semibold">
                Cancel
            </button>`
    });
}

function executeBulkMarkAsPaid(button) {
    if (isSubmitting) return;
    isSubmitting = true;

    const ids = [..._bulkSelectedIds];
    setButtonLoading(button, true, 'Processing...', button.innerHTML);

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const promises  = ids.map(id =>
        fetch(`/admin/orders/${id}/mark-as-paid`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        })
        .then(r => r.json().then(data => ({ success: r.ok && data.success, id, message: data.message })))
        .catch(() => ({ success: false, id }))
    );

    Promise.all(promises).then(results => {
        isSubmitting = false;
        closeUniversalModal();
        clearBulkSelection();

        const succeeded = results.filter(r => r.success).length;
        const skipped   = results.filter(r => !r.success).length;

        if (succeeded > 0) {
            const msg = skipped > 0
                ? `${succeeded} order${succeeded !== 1 ? 's' : ''} marked as paid. ${skipped} skipped (non-cash or already paid).`
                : `${succeeded} order${succeeded !== 1 ? 's' : ''} marked as paid and added to transactions.`;
            window.showToast(msg, false);
        } else {
            window.showToast('No orders were updated. Orders must be cash, completed, and unpaid.', true);
        }
    });
}
</script>
@endsection