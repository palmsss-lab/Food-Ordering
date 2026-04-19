@extends('client.layouts.home')

@section('title', 'Checkout')

@section('content')
@php
    $promoDiscount = (isset($activePromo) && $activePromo)
        ? round($subtotal * ($activePromo->discount_percentage / 100), 2)
        : 0;
@endphp
<div class="mt-24 relative min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] overflow-hidden py-8 px-4 sm:px-6 lg:px-8">
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
            <h1 class="text-2xl sm:text-4xl font-black text-gray-800">Checkout</h1>
        </div>

        <!-- Order Summary Card -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl p-6 mb-6 border border-white/20">

            {{-- Promo badge inside the card --}}
            @if(isset($activePromo) && $activePromo)
            <div class="flex items-center gap-2 mb-4 px-3 py-2 rounded-xl text-sm font-semibold"
                 style="background-color: {{ $activePromo->banner_color }}15; color: {{ $activePromo->banner_color }}">
                <span>🎉</span>
                <span>{{ $activePromo->title }} — {{ number_format($activePromo->discount_percentage, 0) }}% OFF applied</span>
            </div>
            @endif

            <h2 class="text-xl font-bold text-gray-800 mb-4">Order Summary</h2>

            <!-- Cart Items — always show original price so the subtotal row makes sense -->
            <div class="space-y-3 mb-4">
                @foreach($cartItemsForDisplay as $item)
                    <div class="flex justify-between items-start pb-2 border-b border-gray-100">
                        <div>
                            <p class="font-medium text-gray-800">{{ $item['name'] }}</p>
                            <p class="text-sm text-gray-500">{{ $item['quantity'] }} × ₱{{ number_format($item['price'], 2) }}</p>
                        </div>
                        <span class="font-semibold text-gray-700 ml-4">₱{{ number_format($item['subtotal'], 2) }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Totals breakdown -->
            <div class="pt-4 border-t border-gray-200 space-y-2 text-sm">

                {{-- Original items total --}}
                <div class="flex justify-between text-gray-500">
                    <span>Items Total <span class="text-xs">({{ count($cartItemsForDisplay) }} item{{ count($cartItemsForDisplay) !== 1 ? 's' : '' }})</span></span>
                    <span>₱{{ number_format($subtotal, 2) }}</span>
                </div>

                {{-- Promo discount row (always visible when a promo is active, never changed by JS) --}}
                <div id="discountRow" class="{{ isset($activePromo) && $activePromo ? 'flex' : 'hidden' }} justify-between text-green-600 font-semibold">
                    <span>🏷 {{ isset($activePromo) && $activePromo ? $activePromo->title . ' (' . number_format($activePromo->discount_percentage, 0) . '% off)' : '' }}</span>
                    <span>− ₱{{ number_format($promoDiscount, 2) }}</span>
                </div>

                {{-- Extra discount row (voucher / pwd / senior — managed by JS) --}}
                <div id="extraDiscountRow" class="hidden justify-between text-green-600 font-semibold">
                    <span id="extraDiscountRowLabel">Discount</span>
                    <span>− ₱<span id="extraDiscountRowAmount">0.00</span></span>
                </div>

                {{-- VAT --}}
                <div class="flex justify-between text-gray-500">
                    <span>VAT <span class="text-xs">(12%)</span></span>
                    <span id="taxDisplay">₱{{ number_format($tax, 2) }}</span>
                </div>

                {{-- Divider + final total --}}
                <div class="flex justify-between text-xl font-black text-[#ea5a47] pt-3 border-t border-gray-200">
                    <span>You Pay</span>
                    <span id="totalDisplay">₱{{ number_format($total, 2) }}</span>
                </div>

                @if(isset($activePromo) && $activePromo && $promoDiscount > 0)
                <p class="text-xs text-green-500 text-right -mt-1">
                    You save ₱{{ number_format($promoDiscount, 2) }} with this promo
                </p>
                @endif
            </div>
        </div>

        <!-- Discounts & Privileges -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl p-6 mb-6 border border-white/20" id="discountSection">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                Discounts & Privileges
            </h2>

            <!-- Discount type selection -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
                <label class="discount-option relative flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer transition-all hover:border-[#ea5a47]"
                       data-type="none">
                    <input type="radio" name="discount_choice" value="none" class="hidden" checked>
                    <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center flex-shrink-0 radio-circle">
                        <div class="w-2.5 h-2.5 rounded-full bg-[#ea5a47] hidden radio-dot"></div>
                    </div>
                    <div>
                        <p class="font-semibold text-sm text-gray-700">No Discount</p>
                        <p class="text-xs text-gray-400">Pay full price</p>
                    </div>
                </label>

                <label class="discount-option relative flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer transition-all hover:border-[#ea5a47]"
                       data-type="voucher">
                    <input type="radio" name="discount_choice" value="voucher" class="hidden">
                    <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center flex-shrink-0 radio-circle">
                        <div class="w-2.5 h-2.5 rounded-full bg-[#ea5a47] hidden radio-dot"></div>
                    </div>
                    <div>
                        <p class="font-semibold text-sm text-gray-700">Voucher Code</p>
                        <p class="text-xs text-gray-400">Enter a promo code</p>
                    </div>
                </label>

                <label class="discount-option relative flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer transition-all hover:border-[#ea5a47]"
                       data-type="privileges">
                    <input type="radio" name="discount_choice" value="privileges" class="hidden">
                    <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center flex-shrink-0 radio-circle">
                        <div class="w-2.5 h-2.5 rounded-full bg-[#ea5a47] hidden radio-dot"></div>
                    </div>
                    <div>
                        <p class="font-semibold text-sm text-gray-700">PWD / Senior</p>
                        <p class="text-xs text-gray-400">20% off + VAT exempt</p>
                    </div>
                </label>
            </div>

            <!-- Voucher picker (shown when voucher is selected) -->
            <div id="voucherInput" class="hidden">
                <div id="voucherLoading" class="text-sm text-gray-400 py-2">Loading your vouchers...</div>
                <div id="voucherList" class="hidden space-y-2"></div>
                <div id="voucherEmpty" class="hidden">
                    <p class="text-sm text-gray-500">You have no collected vouchers for this order.</p>
                    <a href="{{ route('client.vouchers.index') }}" target="_blank"
                       class="text-sm text-[#ea5a47] font-semibold hover:underline inline-flex items-center gap-1 mt-1">
                        Browse & collect vouchers
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>
                <div id="voucherMsg" class="mt-2 text-sm hidden"></div>
            </div>

            <!-- PWD / Senior options (shown when privileges is selected) -->
            <div id="privilegeInput" class="hidden space-y-2">
                <label class="privilege-option flex items-center gap-3 p-3 bg-purple-50 border-2 border-purple-200 rounded-xl cursor-pointer hover:border-purple-400 transition-all"
                       data-priv="pwd">
                    <input type="radio" name="privilege_type" value="pwd" class="hidden">
                    <div class="w-5 h-5 rounded-full border-2 border-purple-300 flex items-center justify-center flex-shrink-0 priv-circle">
                        <div class="w-2.5 h-2.5 rounded-full bg-purple-500 hidden priv-dot"></div>
                    </div>
                    <div>
                        <p class="font-semibold text-sm text-purple-800">Person with Disability (PWD)</p>
                        <p class="text-xs text-purple-600">20% discount + VAT exempt — present valid PWD ID upon pickup</p>
                    </div>
                </label>
                <label class="privilege-option flex items-center gap-3 p-3 bg-amber-50 border-2 border-amber-200 rounded-xl cursor-pointer hover:border-amber-400 transition-all"
                       data-priv="senior">
                    <input type="radio" name="privilege_type" value="senior" class="hidden">
                    <div class="w-5 h-5 rounded-full border-2 border-amber-300 flex items-center justify-center flex-shrink-0 priv-circle">
                        <div class="w-2.5 h-2.5 rounded-full bg-amber-500 hidden priv-dot"></div>
                    </div>
                    <div>
                        <p class="font-semibold text-sm text-amber-800">Senior Citizen</p>
                        <p class="text-xs text-amber-600">20% discount + VAT exempt — present valid Senior ID upon pickup</p>
                    </div>
                </label>
                <button type="button" id="applyPrivilegeBtn"
                        onclick="applyPrivilege()"
                        class="w-full py-2.5 bg-gray-800 text-white font-semibold rounded-xl hover:bg-gray-700 transition-colors text-sm mt-2">
                    Apply Privilege Discount
                </button>
                <div id="privilegeMsg" class="mt-1 text-sm hidden"></div>
            </div>

            <!-- Applied discount badge -->
            <div id="appliedBadge" class="hidden mt-3 flex items-center justify-between bg-green-50 border border-green-200 rounded-xl px-4 py-3">
                <div class="flex items-center gap-2 text-green-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-sm font-semibold" id="appliedBadgeText"></span>
                </div>
                <button type="button" onclick="removeDiscount()" class="text-xs text-red-500 hover:text-red-700 underline">Remove</button>
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

            <form action="{{ route('client.place-order') }}" method="POST" id="checkoutForm" data-no-loader>
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
                                    <span class="text-2xl font-black text-orange-700" id="cashAmountDisplay">₱{{ number_format($total, 2) }}</span>
                                </div>
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
                                        <span class="text-xl font-bold text-blue-700" id="gcashAmountDisplay">₱{{ number_format($total, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    GCash Mobile Number <span class="text-red-500">*</span>
                                </label>
                                <div class="flex rounded-xl border-2 border-gray-200 overflow-hidden focus-within:border-blue-500 transition-all bg-white">
                                    <div class="flex items-center gap-1.5 px-4 bg-gray-100 border-r-2 border-gray-200 text-gray-600 font-semibold text-sm select-none whitespace-nowrap">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21l1.9-5.7a8.5 8.5 0 113.8 3.8z"/>
                                        </svg>
                                        +63
                                    </div>
                                    <input type="text"
                                           name="gcash_number"
                                           class="flex-1 px-4 py-3 outline-none bg-transparent text-gray-800 placeholder-gray-400 text-sm"
                                           placeholder="9XX XXX XXXX"
                                           pattern="[0-9]{10}"
                                           maxlength="10"
                                           inputmode="numeric">
                                </div>
                                <p class="text-xs text-gray-400 mt-1.5">Enter your 10-digit GCash number starting with 9</p>
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
                                        <span class="text-xl font-bold text-purple-700" id="cardAmountDisplay">₱{{ number_format($total, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Card Number <span class="text-red-500">*</span>
                                </label>
                                <div class="flex rounded-xl border-2 border-gray-200 overflow-hidden focus-within:border-purple-500 transition-all bg-white">
                                    <div class="flex items-center px-4 bg-gray-100 border-r-2 border-gray-200 text-gray-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <rect x="2" y="5" width="20" height="14" rx="2" stroke-width="2"/>
                                            <line x1="2" y1="10" x2="22" y2="10" stroke-width="2"/>
                                        </svg>
                                    </div>
                                    <input type="text"
                                           name="card_number"
                                           id="card_number"
                                           class="flex-1 px-4 py-3 outline-none bg-transparent text-gray-800 placeholder-gray-400 text-sm tracking-widest"
                                           placeholder="XXXX  XXXX  XXXX  XXXX"
                                           maxlength="19"
                                           inputmode="numeric">
                                </div>
                                <p class="text-xs text-gray-400 mt-1.5">16-digit number on the front of your card</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Cardholder Name <span class="text-red-500">*</span>
                                </label>
                                <div class="flex rounded-xl border-2 border-gray-200 overflow-hidden focus-within:border-purple-500 transition-all bg-white">
                                    <div class="flex items-center px-4 bg-gray-100 border-r-2 border-gray-200 text-gray-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <input type="text"
                                           name="card_name"
                                           class="flex-1 px-4 py-3 outline-none bg-transparent text-gray-800 placeholder-gray-400 text-sm uppercase"
                                           placeholder="Name as shown on card">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Expiry Date <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex rounded-xl border-2 border-gray-200 overflow-hidden focus-within:border-purple-500 transition-all bg-white">
                                        <div class="flex items-center px-3 bg-gray-100 border-r-2 border-gray-200 text-gray-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <input type="text"
                                               name="card_expiry"
                                               id="card_expiry"
                                               class="flex-1 px-3 py-3 outline-none bg-transparent text-gray-800 placeholder-gray-400 text-sm"
                                               placeholder="MM / YY"
                                               maxlength="5"
                                               inputmode="numeric">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        CVV <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex rounded-xl border-2 border-gray-200 overflow-hidden focus-within:border-purple-500 transition-all bg-white">
                                        <div class="flex items-center px-3 bg-gray-100 border-r-2 border-gray-200 text-gray-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                        </div>
                                        <input type="password"
                                               name="card_cvv"
                                               class="flex-1 px-3 py-3 outline-none bg-transparent text-gray-800 placeholder-gray-400 text-sm"
                                               placeholder="•••"
                                               maxlength="4"
                                               inputmode="numeric">
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1.5">3–4 digits on back</p>
                                </div>
                            </div>

                            <div class="bg-purple-50 border border-purple-100 rounded-xl p-3 flex items-start gap-2">
                                <svg class="w-4 h-4 text-purple-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                <p class="text-xs text-purple-700">Your card details are encrypted and secure. Your card will be charged immediately.</p>
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

<!-- Place Order Confirmation Modal -->
<div id="placeOrderModal" class="fixed inset-0 z-50 hidden items-center justify-center">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closePlaceOrderModal()"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl max-w-sm w-full mx-4 p-8 text-center">
        <div class="w-16 h-16 bg-[#ea5a47]/10 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-800 mb-2">Confirm Order</h3>
        <p class="text-gray-500 text-sm mb-1">Ready to place your order?</p>
        <p class="text-2xl font-black text-[#ea5a47] mb-6" id="confirmOrderTotal"></p>
        <div class="flex gap-3">
            <button onclick="closePlaceOrderModal()"
                    class="flex-1 py-3 border-2 border-gray-200 text-gray-700 font-semibold rounded-xl hover:border-[#ea5a47] transition-all">
                Review
            </button>
            <button onclick="submitOrderForm()"
                    id="confirmOrderBtn"
                    class="flex-1 py-3 bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-bold rounded-xl hover:shadow-lg transition-all">
                Place Order
            </button>
        </div>
    </div>
</div>

<script>
// ===================== DISCOUNT LOGIC =====================

const SUBTOTAL = {{ $subtotal }};
let currentTotal = {{ $total }};

// Discount type selector
document.querySelectorAll('.discount-option').forEach(opt => {
    opt.addEventListener('click', function() {
        const type = this.dataset.type;
        document.querySelectorAll('.discount-option').forEach(o => {
            o.classList.remove('border-[#ea5a47]', 'bg-[#ea5a47]/5');
            o.querySelector('.radio-dot').classList.add('hidden');
            o.querySelector('.radio-circle').classList.remove('border-[#ea5a47]');
        });
        this.classList.add('border-[#ea5a47]', 'bg-[#ea5a47]/5');
        this.querySelector('.radio-dot').classList.remove('hidden');
        this.querySelector('.radio-circle').classList.add('border-[#ea5a47]');
        this.querySelector('input').checked = true;

        document.getElementById('voucherInput').classList.add('hidden');
        document.getElementById('privilegeInput').classList.add('hidden');

        if (type === 'voucher') {
            document.getElementById('voucherInput').classList.remove('hidden');
            loadCollectedVouchers();
        } else if (type === 'privileges') {
            document.getElementById('privilegeInput').classList.remove('hidden');
        } else {
            removeDiscount();
        }
    });
});

// Privilege type selector
document.querySelectorAll('.privilege-option').forEach(opt => {
    opt.addEventListener('click', function() {
        document.querySelectorAll('.privilege-option').forEach(o => {
            o.classList.remove('ring-2', 'ring-offset-1');
            o.querySelector('.priv-dot').classList.add('hidden');
        });
        this.classList.add('ring-2', 'ring-offset-1');
        this.querySelector('.priv-dot').classList.remove('hidden');
        this.querySelector('input').checked = true;
    });
});

let _vouchersLoaded = false;
function loadCollectedVouchers() {
    if (_vouchersLoaded) return;
    _vouchersLoaded = true;
    fetch(`{{ route("client.vouchers.mine") }}?subtotal=${SUBTOTAL}`)
    .then(r => r.json())
    .then(vouchers => {
        document.getElementById('voucherLoading').classList.add('hidden');
        if (!vouchers.length) {
            document.getElementById('voucherEmpty').classList.remove('hidden');
            return;
        }
        const list = document.getElementById('voucherList');
        list.classList.remove('hidden');
        list.innerHTML = vouchers.map(v => {
            const discLabel = v.type === 'percentage' ? `${v.value}% off` : `₱${parseFloat(v.value).toFixed(0)} off`;
            const eligible  = v.eligible;
            return `
            <label class="voucher-pick flex items-center gap-3 p-3 border-2 rounded-xl cursor-pointer transition-all
                          ${eligible ? 'border-gray-200 hover:border-[#ea5a47]' : 'border-gray-100 opacity-50 cursor-not-allowed'}"
                   data-id="${v.id}" data-code="${v.code}" data-label="${v.label}"
                   data-discount="${v.discount_amount}" data-tax="${(SUBTOTAL - v.discount_amount) * 0.12}"
                   data-total="${v.total}">
                <input type="radio" name="voucher_pick" value="${v.id}" class="hidden" ${!eligible ? 'disabled' : ''}>
                <div class="w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center flex-shrink-0 vpick-circle">
                    <div class="w-2 h-2 rounded-full bg-[#ea5a47] hidden vpick-dot"></div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-[#ea5a47] text-sm">${discLabel}</span>
                        <span class="font-mono text-xs bg-[#ea5a47]/10 text-[#ea5a47] px-1.5 py-0.5 rounded">${v.code}</span>
                    </div>
                    ${v.description ? `<p class="text-xs text-gray-500">${v.description}</p>` : ''}
                    ${!eligible ? `<p class="text-xs text-orange-500">Min. order ₱${parseFloat(v.min_order_amount).toFixed(0)} required</p>` : ''}
                    ${eligible && v.discount_amount ? `<p class="text-xs text-green-600 font-medium">Saves ₱${parseFloat(v.discount_amount).toFixed(2)}</p>` : ''}
                </div>
                ${v.expires_at ? `<span class="text-xs text-gray-400 flex-shrink-0">${v.expires_at}</span>` : ''}
            </label>`;
        }).join('');

        // Click handler for voucher selection
        list.querySelectorAll('.voucher-pick').forEach(card => {
            if (card.querySelector('input[disabled]')) return;
            card.addEventListener('click', function() {
                list.querySelectorAll('.voucher-pick').forEach(c => {
                    c.classList.remove('border-[#ea5a47]', 'bg-[#ea5a47]/5');
                    c.querySelector('.vpick-dot').classList.add('hidden');
                    c.querySelector('.vpick-circle').classList.remove('border-[#ea5a47]');
                });
                this.classList.add('border-[#ea5a47]', 'bg-[#ea5a47]/5');
                this.querySelector('.vpick-dot').classList.remove('hidden');
                this.querySelector('.vpick-circle').classList.add('border-[#ea5a47]');
                this.querySelector('input').checked = true;

                const discount = parseFloat(this.dataset.discount);
                const tax      = parseFloat(this.dataset.tax);
                const total    = parseFloat(this.dataset.total);
                const code     = this.dataset.code;
                const label    = this.dataset.label;

                // Apply via server
                fetch('{{ route("client.apply-discount") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ discount_type: 'voucher', voucher_code: code, subtotal: SUBTOTAL })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) updateTotalsUI(data.discount_amount, data.tax, data.total, data.label);
                });
            });
        });
    })
    .catch(() => {
        document.getElementById('voucherLoading').textContent = 'Failed to load vouchers.';
    });
}

function applyPrivilege() {
    const selected = document.querySelector('.privilege-option input:checked');
    if (!selected) {
        const msg = document.getElementById('privilegeMsg');
        msg.textContent = 'Please select PWD or Senior Citizen.';
        msg.className = 'mt-1 text-sm text-red-600';
        msg.classList.remove('hidden');
        return;
    }

    const type = selected.value;
    fetch('{{ route("client.apply-discount") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ discount_type: type, subtotal: SUBTOTAL })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('privilegeMsg').classList.add('hidden');
            updateTotalsUI(data.discount_amount, data.tax, data.total, data.label);
        }
    });
}

function updateTotalsUI(discountAmt, tax, total, label) {
    currentTotal = total;

    const fmt = n => '₱' + parseFloat(n).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');

    // Update the extra discount row (the promo row is server-rendered and never touched here)
    document.getElementById('extraDiscountRow').classList.remove('hidden');
    document.getElementById('extraDiscountRowLabel').textContent = label;
    document.getElementById('extraDiscountRowAmount').textContent = parseFloat(discountAmt).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    document.getElementById('taxDisplay').textContent = fmt(tax);
    document.getElementById('totalDisplay').textContent = fmt(total);

    ['cashAmountDisplay', 'gcashAmountDisplay', 'cardAmountDisplay'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.textContent = fmt(total);
    });

    // Show applied badge
    document.getElementById('appliedBadge').classList.remove('hidden');
    document.getElementById('appliedBadgeText').textContent = label + ' — saves ₱' + parseFloat(discountAmt).toFixed(2);
}

function removeDiscount() {
    // Reset to post-promo totals (promo is always kept; only the extra discount is cleared)
    currentTotal = {{ $total }};
    const fmt = n => '₱' + parseFloat(n).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    const origTax = {{ $tax }};

    document.getElementById('extraDiscountRow').classList.add('hidden');
    document.getElementById('taxDisplay').textContent = fmt(origTax);
    document.getElementById('totalDisplay').textContent = fmt({{ $total }});

    ['cashAmountDisplay', 'gcashAmountDisplay', 'cardAmountDisplay'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.textContent = fmt({{ $total }});
    });

    document.getElementById('appliedBadge').classList.add('hidden');
    document.getElementById('voucherMsg').classList.add('hidden');
    document.getElementById('privilegeMsg').classList.add('hidden');

    // Reset radio to "none"
    document.querySelectorAll('.discount-option').forEach(o => {
        o.classList.remove('border-[#ea5a47]', 'bg-[#ea5a47]/5');
        o.querySelector('.radio-dot').classList.add('hidden');
        o.querySelector('.radio-circle').classList.remove('border-[#ea5a47]');
    });
    const noneOpt = document.querySelector('.discount-option[data-type="none"]');
    if (noneOpt) {
        noneOpt.classList.add('border-[#ea5a47]', 'bg-[#ea5a47]/5');
        noneOpt.querySelector('.radio-dot').classList.remove('hidden');
        noneOpt.querySelector('input').checked = true;
    }
    document.getElementById('voucherInput').classList.add('hidden');
    document.getElementById('privilegeInput').classList.add('hidden');

    // Clear server-side discount session via fetch
    fetch('{{ route("client.apply-discount") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ discount_type: 'none', subtotal: SUBTOTAL })
    }).catch(() => {});
}

// ===================== PAYMENT TAB LOGIC =====================

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

// ===================== FIELD ERROR HELPERS =====================

function showFieldError(input, message) {
    input.classList.add('border-red-400');
    let err = input.parentElement.querySelector('.field-error');
    if (!err) {
        err = document.createElement('p');
        err.className = 'field-error text-xs text-red-500 mt-1';
        input.parentElement.appendChild(err);
    }
    err.textContent = message;
    input.scrollIntoView({ behavior: 'smooth', block: 'center' });
    input.focus();
}

function clearFieldErrors() {
    document.querySelectorAll('.field-error').forEach(e => e.remove());
    document.querySelectorAll('.border-red-400').forEach(e => e.classList.remove('border-red-400'));
}

function validatePaymentFields() {
    const method = document.getElementById('payment_method').value;
    if (method === 'gcash') {
        const gcashInput = document.querySelector('[name="gcash_number"]');
        if (!gcashInput.value || gcashInput.value.replace(/\D/g, '').length < 10) {
            switchPaymentTab('gcash');
            showFieldError(gcashInput, 'Please enter a valid 10-digit GCash number.');
            return false;
        }
    } else if (method === 'card') {
        const cardNumber = document.querySelector('[name="card_number"]');
        const cardName   = document.querySelector('[name="card_name"]');
        const cardExpiry = document.querySelector('[name="card_expiry"]');
        const cardCvv    = document.querySelector('[name="card_cvv"]');
        switchPaymentTab('card');
        if (!cardNumber.value.replace(/\s/g, '') || cardNumber.value.replace(/\s/g, '').length < 15) {
            showFieldError(cardNumber, 'Please enter a valid card number (15–16 digits).');
            return false;
        }
        if (!cardName.value.trim()) {
            showFieldError(cardName, 'Please enter the cardholder name.');
            return false;
        }
        if (!cardExpiry.value.match(/^\d{2}\/\d{2}$/)) {
            showFieldError(cardExpiry, 'Please enter expiry as MM/YY.');
            return false;
        }
        if (cardCvv.value.length < 3) {
            showFieldError(cardCvv, 'CVV must be 3–4 digits.');
            return false;
        }
    }
    return true;
}

// ===================== PLACE ORDER MODAL =====================

function showPlaceOrderModal() {
    const fmt = n => '₱' + parseFloat(n).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    document.getElementById('confirmOrderTotal').textContent = fmt(currentTotal);
    const modal = document.getElementById('placeOrderModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closePlaceOrderModal() {
    const modal = document.getElementById('placeOrderModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

function submitOrderForm() {
    const btn = document.getElementById('confirmOrderBtn');
    if (btn) { btn.disabled = true; btn.textContent = 'Placing...'; }
    document.getElementById('checkoutForm').submit();
}

// ===================== INITIALIZE =====================

document.addEventListener('DOMContentLoaded', function() {
    // Restore payment tab (supports old() on validation failure)
    const savedTab = '{{ old("payment_method", "cash") }}';
    switchPaymentTab(savedTab || 'cash');

    // Highlight "No Discount" by default
    const noneOpt = document.querySelector('.discount-option[data-type="none"]');
    if (noneOpt) {
        noneOpt.classList.add('border-[#ea5a47]', 'bg-[#ea5a47]/5');
        noneOpt.querySelector('.radio-dot').classList.remove('hidden');
        noneOpt.querySelector('.radio-circle').classList.add('border-[#ea5a47]');
    }

    // Form → validate → show confirmation modal
    const form = document.getElementById('checkoutForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            clearFieldErrors();
            if (!validatePaymentFields()) return;
            showPlaceOrderModal();
        });
    }

    // Close modal with Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closePlaceOrderModal();
    });
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