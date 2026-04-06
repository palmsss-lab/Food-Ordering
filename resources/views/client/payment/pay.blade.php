@extends('client.layouts.home')

@section('title', 'Pay for Order')

@section('content')
<div class="relative min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] overflow-hidden py-12 px-4 sm:px-6 lg:px-8">
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
            </div>
            <h1 class="text-4xl font-black text-gray-800">Complete <span class="text-[#ea5a47]">Payment</span></h1>
        </div>

        <!-- Order Summary Card -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl p-6 mb-6 border border-white/20">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Order Summary</h2>
            
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Order Number</p>
                    <p class="text-xl font-bold text-[#ea5a47]">{{ $order->order_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Items</p>
                    <p class="text-xl font-bold">{{ $order->items->count() }} item(s)</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Amount</p>
                    <p class="text-2xl font-black text-[#ea5a47]">{{ $order->formatted_total }}</p>
                </div>
            </div>
            
            <!-- Items List -->
            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="text-sm font-medium text-gray-500 mb-2">Items in your order:</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($order->items as $item)
                        <span class="text-sm bg-gray-100 px-3 py-1 rounded-full">
                            {{ $item->quantity }}x {{ $item->item_name }}
                        </span>
                    @endforeach
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
                        <button class="inline-block p-4 rounded-t-lg border-b-2" 
                                id="cash-tab" 
                                onclick="switchTab('cash')" 
                                type="button" 
                                role="tab" 
                                aria-controls="cash" 
                                aria-selected="true">
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
                                onclick="switchTab('gcash')" 
                                type="button" 
                                role="tab" 
                                aria-controls="gcash" 
                                aria-selected="false">
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
                                onclick="switchTab('card')" 
                                type="button" 
                                role="tab" 
                                aria-controls="card" 
                                aria-selected="false">
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

            <!-- Tab Content -->
            <div id="tabContent">
                <!-- Cash Tab -->
                <div id="cash" class="tab-pane" role="tabpanel">
                    <form action="{{ route('client.payments.cash', $order) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div class="bg-orange-50 p-4 rounded-lg mb-4 border-l-4 border-orange-500">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-2xl">💵</span>
                                <span class="text-orange-700 font-bold text-lg">Cash on Pickup</span>
                            </div>
                            <p class="text-sm text-orange-700">Pay at the counter when you pick up your order.</p>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Cash Amount *</label>
                            <input type="number" name="cash_amount" step="0.01" min="{{ $order->total }}" value="{{ $order->total }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] outline-none transition-all bg-white/80"
                                   placeholder="Enter cash amount"
                                   required>
                            <p class="text-sm text-gray-500 mt-1">Total: {{ $order->formatted_total }}</p>
                        </div>
                        
                        <div class="bg-orange-50 p-4 rounded-lg">
                            <p class="text-sm text-orange-700">
                                <span class="font-bold">Note:</span> Your order will be pending until admin confirmation.
                            </p>
                        </div>
                        
                        <button type="submit" 
                                class="w-full py-3.5 bg-gradient-to-r from-orange-600 to-orange-700 text-white font-semibold rounded-xl hover:shadow-xl transition-all flex items-center justify-center gap-2 group">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                            </svg>
                            Continue with Cash on Pickup
                        </button>
                    </form>
                </div>

                <!-- GCash Tab -->
                <div id="gcash" class="tab-pane hidden" role="tabpanel">
                    <form action="{{ route('client.payments.process', $order) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="payment_method" value="gcash">
                        
                        <div class="bg-blue-50 p-4 rounded-lg mb-4 border-l-4 border-blue-500">
                            <div class="flex items-center gap-3 mb-2">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <span class="text-blue-700 font-bold">Pay via GCash</span>
                            </div>
                            <p class="text-sm text-blue-700">Payment will be processed immediately.</p>
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
                                       maxlength="10"
                                       required>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Enter your 10-digit GCash number (e.g., 9123456789)</p>
                        </div>
                        
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-xs text-green-700">
                                    You will receive a confirmation once payment is processed.
                                </p>
                            </div>
                        </div>
                        
                        <button type="submit" 
                                class="w-full py-3.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:shadow-xl transition-all flex items-center justify-center gap-2 group">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Pay {{ $order->formatted_total }} via GCash
                        </button>
                    </form>
                </div>

                <!-- Card Tab -->
                <div id="card" class="tab-pane hidden" role="tabpanel">
                    <form action="{{ route('client.payments.process', $order) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="payment_method" value="card">
                        
                        <div class="bg-purple-50 p-4 rounded-lg mb-4 border-l-4 border-purple-500">
                            <div class="flex items-center gap-3 mb-2">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <rect x="2" y="5" width="20" height="14" rx="2" stroke-width="2"/>
                                    <line x1="2" y1="10" x2="22" y2="10" stroke-width="2"/>
                                </svg>
                                <span class="text-purple-700 font-bold">Credit / Debit Card</span>
                            </div>
                            <p class="text-sm text-purple-700">Payment will be processed immediately.</p>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Card Number *</label>
                            <input type="text" 
                                   name="card_number" 
                                   id="card_number"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 outline-none transition-all bg-white/80"
                                   placeholder="1234 5678 9012 3456"
                                   maxlength="19"
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Cardholder Name *</label>
                            <input type="text" 
                                   name="card_name" 
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 outline-none transition-all bg-white/80"
                                   placeholder="JOHN DOE"
                                   required>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Expiry Date *</label>
                                <input type="text" 
                                       name="card_expiry" 
                                       id="card_expiry"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 outline-none transition-all bg-white/80"
                                       placeholder="MM/YY"
                                       maxlength="5"
                                       required>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">CVV *</label>
                                <input type="password" 
                                       name="card_cvv" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 outline-none transition-all bg-white/80"
                                       placeholder="123"
                                       maxlength="4"
                                       required>
                            </div>
                        </div>
                        
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <p class="text-xs text-green-700">
                                    Your card will be charged immediately.
                                </p>
                            </div>
                        </div>
                        
                        <button type="submit" 
                                class="w-full py-3.5 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold rounded-xl hover:shadow-xl transition-all flex items-center justify-center gap-2 group">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            Pay {{ $order->formatted_total }} via Card
                        </button>
                    </form>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-6 text-center">
                <a href="{{ route('client.orders.index', ['tab' => 'pending']) }}" 
                   class="text-gray-500 hover:text-[#ea5a47] transition-colors inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to My Orders
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Tab switching function
function switchTab(tabName) {
    // Update tab styles
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
    
    // Show/hide tab panes
    document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.classList.add('hidden');
    });
    
    document.getElementById(tabName).classList.remove('hidden');
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

// Initialize active tab on page load
document.addEventListener('DOMContentLoaded', function() {
    switchTab('cash');
});
</script>
@endsection