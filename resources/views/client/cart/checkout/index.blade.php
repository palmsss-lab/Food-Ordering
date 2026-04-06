@extends('client.layouts.home')

@section('title', 'Checkout')

@section('content')
<div class="mt-25 relative min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] overflow-hidden py-12 px-4 sm:px-6 lg:px-8">
    <div class="relative z-10 max-w-4xl mx-auto">
        
        <!-- Header with back button -->
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('client.cart.index') }}" 
               class="p-2 bg-white rounded-lg shadow hover:shadow-md transition-all">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div class="bg-[#ea5a47] p-3 rounded-2xl shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h1 class="text-4xl font-black text-gray-800">Checkout</h1>
        </div>

        <!-- Order Summary Card -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl p-6 mb-6 border border-white/20">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Order Summary</h2>
            
            <!-- Cart Items -->
            <div class="space-y-3 mb-4">
                @foreach($cartItemsForDisplay as $item)
                    <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                        <div>
                            <p class="font-medium">{{ $item['name'] }}</p>
                            <p class="text-sm text-gray-500">{{ $item['quantity'] }} x ₱{{ number_format($item['price'], 2) }}</p>
                            @if(isset($item['special_instructions']))
                                <p class="text-xs text-gray-400 italic">Note: {{ $item['special_instructions'] }}</p>
                            @endif
                        </div>
                        <span class="font-bold text-[#ea5a47]">₱{{ number_format($item['subtotal'], 2) }}</span>
                    </div>
                @endforeach
            </div>
            
            <div class="grid md:grid-cols-3 gap-4 pt-4 border-t border-gray-200">
                <div>
                    <p class="text-sm text-gray-500">Items</p>
                    <p class="text-xl font-bold">{{ count($cartItemsForDisplay) }} item(s)</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Subtotal</p>
                    <p class="text-xl font-bold">₱{{ number_format($subtotal, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Amount</p>
                    <p class="text-2xl font-black text-[#ea5a47]">₱{{ number_format($total, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Payment Method Tabs -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl p-6 border border-white/20">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Select Payment Method</h2>
            
            <!-- Tab Navigation -->
            <div class="mb-6 border-b border-gray-200">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="paymentTabs" role="tablist">
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 rounded-t-lg border-b-2 border-[#ea5a47] text-[#ea5a47]" 
                                id="cash-tab" 
                                onclick="switchPaymentTab('cash')" 
                                type="button">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                </svg>
                                Cash on Pickup
                            </span>
                        </button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300" 
                                id="gcash-tab" 
                                onclick="switchPaymentTab('gcash')" 
                                type="button">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                GCash
                            </span>
                        </button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300" 
                                id="card-tab" 
                                onclick="switchPaymentTab('card')" 
                                type="button">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                Credit/Debit Card
                            </span>
                        </button>
                    </li>
                </ul>
            </div>

            <form action="{{ route('client.place-order') }}" method="POST" id="checkoutForm">
                @csrf
                <input type="hidden" name="payment_method" id="payment_method" value="cash">
                <input type="hidden" name="notes" id="notes" value="">
                
                <!-- Tab Content -->
                <div id="tabContent">
                    <!-- Cash Tab -->
                    <div id="cash" class="tab-pane" role="tabpanel">
                        <div class="bg-orange-50 p-4 rounded-lg mb-4 border-l-4 border-orange-500">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-2xl">💵</span>
                                <span class="text-orange-700 font-bold text-lg">Cash on Pickup</span>
                            </div>
                            <p class="text-sm text-orange-700">Pay at the counter when you pick up your order.</p>
                            
                            <div class="mt-4 p-3 bg-orange-100 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-orange-800">Amount to Pay:</span>
                                    <span class="text-2xl font-black text-orange-700">₱{{ number_format($total, 2) }}</span>
                                </div>
                                <input type="hidden" name="cash_amount" value="{{ $total }}">
                            </div>
                            
                            <p class="text-xs text-orange-600 mt-3">Your order will be pending until admin confirmation.</p>
                        </div>
                    </div>

                    <!-- GCash Tab -->
                    <div id="gcash" class="tab-pane hidden" role="tabpanel">
                        <div class="space-y-4">
                            <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                                <div class="flex items-center gap-3 mb-2">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-blue-700 font-bold">Pay via GCash</span>
                                </div>
                                <p class="text-sm text-blue-700">Payment will be processed immediately.</p>
                                <div class="mt-2 p-2 bg-blue-100 rounded-lg">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-blue-800">Amount to Pay:</span>
                                        <span class="text-xl font-bold text-blue-700">₱{{ number_format($total, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">GCash Mobile Number *</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">+63</span>
                                    <input type="text" 
                                           name="gcash_number" 
                                           class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 outline-none transition-all bg-white/80"
                                           placeholder="9123456789"
                                           pattern="[0-9]{10}"
                                           maxlength="10">
                                </div>
                                <p class="text-xs text-gray-400 mt-1">Enter your 10-digit GCash number (e.g., 9123456789)</p>
                            </div>
                            
                            <div class="bg-green-50 p-4 rounded-lg">
                                <div class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-xs text-green-700">You will receive a confirmation once payment is processed.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Tab -->
                    <div id="card" class="tab-pane hidden" role="tabpanel">
                        <div class="space-y-4">
                            <div class="bg-purple-50 p-4 rounded-lg border-l-4 border-purple-500">
                                <div class="flex items-center gap-3 mb-2">
                                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <rect x="2" y="5" width="20" height="14" rx="2" stroke-width="2"/>
                                        <line x1="2" y1="10" x2="22" y2="10" stroke-width="2"/>
                                    </svg>
                                    <span class="text-purple-700 font-bold">Credit / Debit Card</span>
                                </div>
                                <p class="text-sm text-purple-700">Payment will be processed immediately.</p>
                                <div class="mt-2 p-2 bg-purple-100 rounded-lg">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-purple-800">Amount to Pay:</span>
                                        <span class="text-xl font-bold text-purple-700">₱{{ number_format($total, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Card Number *</label>
                                <input type="text" 
                                       name="card_number" 
                                       id="card_number"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 outline-none transition-all bg-white/80"
                                       placeholder="1234 5678 9012 3456"
                                       maxlength="19">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Cardholder Name *</label>
                                <input type="text" 
                                       name="card_name" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 outline-none transition-all bg-white/80"
                                       placeholder="JOHN DOE">
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Expiry Date *</label>
                                    <input type="text" 
                                           name="card_expiry" 
                                           id="card_expiry"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 outline-none transition-all bg-white/80"
                                           placeholder="MM/YY"
                                           maxlength="5">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">CVV *</label>
                                    <input type="password" 
                                           name="card_cvv" 
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 outline-none transition-all bg-white/80"
                                           placeholder="123"
                                           maxlength="4">
                                </div>
                            </div>
                            
                            <div class="bg-green-50 p-4 rounded-lg">
                                <div class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    <p class="text-xs text-green-700">Your card will be charged immediately.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Place Order Button -->
                <button type="submit" 
                        class="w-full mt-6 py-4 bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-bold rounded-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-[1.02] flex items-center justify-center gap-3">
                    <span>Place Order</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                
                <!-- Back to Cart Link -->
                <div class="mt-4 text-center">
                    <a href="{{ route('client.cart.index') }}" 
                       class="text-gray-500 hover:text-[#ea5a47] transition-colors inline-flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Cart
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Payment tab switching function
function switchPaymentTab(tabName) {
    // Update hidden input
    document.getElementById('payment_method').value = tabName;
    
    // Update tab styles
    document.querySelectorAll('#paymentTabs button').forEach(tab => {
        tab.classList.remove('border-[#ea5a47]', 'text-[#ea5a47]');
        tab.classList.add('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
    });
    
    // Highlight selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.remove('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
    activeTab.classList.add('border-[#ea5a47]', 'text-[#ea5a47]');
    
    // Show/hide tab panes
    document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.classList.add('hidden');
    });
    document.getElementById(tabName).classList.remove('hidden');
    
    // Update required fields
    updateRequiredFields(tabName);
}

function updateRequiredFields(method) {
    // Reset required attributes
    document.querySelectorAll('[name="gcash_number"], [name="card_number"], [name="card_name"], [name="card_expiry"], [name="card_cvv"]').forEach(field => {
        field.required = false;
    });
    
    // Set required based on method
    if (method === 'gcash') {
        document.querySelector('[name="gcash_number"]').required = true;
    } else if (method === 'card') {
        document.querySelector('[name="card_number"]').required = true;
        document.querySelector('[name="card_name"]').required = true;
        document.querySelector('[name="card_expiry"]').required = true;
        document.querySelector('[name="card_cvv"]').required = true;
    }
}

// Card number formatting
const cardNumberInput = document.getElementById('card_number');
if (cardNumberInput) {
    cardNumberInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '').replace(/\D/g, '');
        let formattedValue = '';
        for (let i = 0; i < value.length; i++) {
            if (i > 0 && i % 4 === 0) formattedValue += ' ';
            formattedValue += value[i];
        }
        e.target.value = formattedValue;
    });
}

// Expiry date formatting
const expiryInput = document.getElementById('card_expiry');
if (expiryInput) {
    expiryInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            e.target.value = value.slice(0, 2) + '/' + value.slice(2, 4);
        } else {
            e.target.value = value;
        }
    });
}

// Set special instructions before submit
document.getElementById('checkoutForm').addEventListener('submit', function() {
    const instructions = document.getElementById('special_instructions').value;
    document.getElementById('notes').value = instructions;
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    switchPaymentTab('cash');
    
    // Form validation before submit
    const form = document.getElementById('checkoutForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const method = document.getElementById('payment_method').value;
            
            if (method === 'gcash') {
                const gcashNumber = document.querySelector('[name="gcash_number"]').value;
                if (!gcashNumber || gcashNumber.length < 10) {
                    e.preventDefault();
                    alert('Please enter a valid 10-digit GCash number');
                    return false;
                }
            } else if (method === 'card') {
                const cardNumber = document.querySelector('[name="card_number"]').value.replace(/\s/g, '');
                const cardName = document.querySelector('[name="card_name"]').value;
                const cardExpiry = document.querySelector('[name="card_expiry"]').value;
                const cardCvv = document.querySelector('[name="card_cvv"]').value;
                
                if (!cardNumber || cardNumber.length < 15) {
                    e.preventDefault();
                    alert('Please enter a valid card number');
                    return false;
                }
                if (!cardName) {
                    e.preventDefault();
                    alert('Please enter the cardholder name');
                    return false;
                }
                if (!cardExpiry || !cardExpiry.match(/^\d{2}\/\d{2}$/)) {
                    e.preventDefault();
                    alert('Please enter a valid expiry date (MM/YY)');
                    return false;
                }
                if (!cardCvv || cardCvv.length < 3) {
                    e.preventDefault();
                    alert('Please enter a valid CVV');
                    return false;
                }
            }
            return true;
        });
    }
});
</script>

<style>
    .payment-fields {
        transition: all 0.3s ease;
    }
    
    .tab-pane {
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection