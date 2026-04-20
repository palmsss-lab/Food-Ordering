@extends('client.layouts.home')

@section('title', 'Cart')

@section('content')

<!-- Custom Confirmation Modal -->
<div id="confirm-modal" 
     class="fixed inset-0 z-50 flex items-center justify-center hidden">
    
    <!-- Backdrop with blur -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity" id="modal-backdrop"></div>
    
    <!-- Modal Card -->
    <div class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="modal-card">
        
        <!-- Decorative top bar -->
        <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-[#ea5a47] to-[#c53030] rounded-t-3xl"></div>
        
        <!-- Close button -->
        <button onclick="closeConfirmModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        
        <div class="p-8 text-center">
            <!-- Food-themed icon -->
            <div class="relative mb-6">
                <div class="absolute inset-0 bg-[#ea5a47] opacity-10 rounded-full blur-2xl"></div>
                <div class="relative bg-gradient-to-br from-[#ea5a47] to-[#c53030] w-24 h-24 rounded-full flex items-center justify-center mx-auto shadow-lg">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <!-- Floating food icons -->
                <div class="absolute -top-2 -right-2 bg-white p-2 rounded-full shadow-lg animate-bounce" style="animation-delay: 0.2s">
                    🍽️
                </div>
                <div class="absolute -bottom-2 -left-2 bg-white p-2 rounded-full shadow-lg animate-bounce" style="animation-delay: 0.4s">
                    🥘
                </div>
            </div>
            
            <!-- Title -->
            <h3 class="text-2xl font-bold text-gray-800 mb-3" id="modal-title">Remove Item?</h3>
            
            <!-- Message -->
            <p class="text-gray-600 mb-2" id="modal-message">Are you sure you want to remove this item from your cart?</p>
            <p class="text-sm text-gray-500 mb-8">You can re-add it from the menu anytime.</p>
            
            <!-- Item preview (for single item removal) -->
            <div id="modal-item-preview" class="bg-[#fdf7f2] rounded-xl p-4 mb-8 flex items-center gap-4 hidden">
                <img src="" alt="" class="w-16 h-16 object-cover rounded-lg border-2 border-white shadow-md" id="modal-item-image">
                <div class="text-left">
                    <p class="font-semibold text-gray-800" id="modal-item-title"></p>
                    <p class="text-sm text-gray-500" id="modal-item-price"></p>
                </div>
            </div>
            
            <!-- Bulk removal message -->
            <div id="modal-bulk-message" class="bg-yellow-50 rounded-xl p-4 mb-8 hidden">
                <p class="text-yellow-800 text-sm" id="bulk-count"></p>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-3">
                <button onclick="closeConfirmModal()" 
                        class="flex-1 px-6 py-3 border-2 border-gray-200 text-gray-700 font-semibold rounded-xl hover:border-[#ea5a47] hover:text-[#ea5a47] transition-all duration-200">
                    Cancel
                </button>
                <button onclick="confirmRemove()" 
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-semibold rounded-xl hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center gap-2"
                        id="confirm-remove-btn">
                    <span>Yes, Remove</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- CHECKOUT FORM -->
<form id="checkout-form" method="POST" action="{{ route('client.checkout') }}">
    @csrf
    <input type="hidden" name="selected_items" id="selected-items-input" value="">
    <div class="max-w-6xl mx-auto mt-24 md:mt-32 px-4 pb-24 md:pb-8">
        
        <!-- Header with food icon -->
        <div class="flex items-center gap-3 mb-8">  
            <div class="bg-[#ea5a47] p-3 rounded-2xl shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <h1 class="text-2xl sm:text-4xl font-black text-gray-800">Your <span class="text-[#ea5a47]">Cart</span></h1>
        </div>

        <div id="cart-container" class="space-y-6">
            @php
                $cartItems = $cartItems ?? collect();
            @endphp

            @if($cartItems->isEmpty())
                <!-- Enhanced Empty Cart - Food Themed -->
                <div class="bg-[#fdf7f2] rounded-3xl shadow-xl p-16">
                    <div class="flex flex-col items-center justify-center text-center max-w-md mx-auto">
                        <!-- Animated Food Illustration -->
                        <div class="relative mb-8">
                            <div class="absolute inset-0 bg-[#ea5a47] opacity-10 rounded-full blur-3xl"></div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-40 h-40 text-[#ea5a47] opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M8 12h8" />
                            </svg>
                            <!-- Floating Food Icons -->
                            <div class="absolute -top-4 -right-4 bg-white p-3 rounded-full shadow-lg animate-bounce" style="animation-delay: 0.2s">
                                🍕
                            </div>
                            <div class="absolute -bottom-2 -left-4 bg-white p-3 rounded-full shadow-lg animate-bounce" style="animation-delay: 0.4s">
                                🍔
                            </div>
                            <div class="absolute top-1/2 -right-8 bg-white p-3 rounded-full shadow-lg animate-bounce" style="animation-delay: 0.6s">
                                🥗
                            </div>
                        </div>
                        
                        <h2 class="text-3xl font-bold text-gray-800 mb-3">Your cart feels hungry!</h2>
                        <p class="text-gray-600 mb-2 text-lg">Looks like you haven't added any delicious food yet.</p>
                        <p class="text-gray-500 mb-8 text-lg">Time to explore our menu and satisfy your cravings! 🌮</p>
                        
                        <a href="{{ route('client.menu') }}" class="group bg-[#ea5a47] text-white px-10 py-4 rounded-2xl font-bold text-lg hover:bg-[#c53030] transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl inline-flex items-center gap-3">
                            <span>Browse Our Menu</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            @else
                @php $cartPromoCheck = \App\Models\Promotion::todayPromo(); @endphp
                @if($cartPromoCheck)
                <div class="rounded-2xl p-4 mb-4 border-2 flex items-center gap-3"
                     style="background-color: {{ $cartPromoCheck->banner_color }}12; border-color: {{ $cartPromoCheck->banner_color }}35">
                    <div class="text-xl flex-shrink-0">🎉</div>
                    <div>
                        <p class="font-bold text-sm" style="color: {{ $cartPromoCheck->banner_color }}">
                            {{ $cartPromoCheck->title }} — {{ number_format($cartPromoCheck->discount_percentage, 0) }}% OFF applied to all items
                        </p>
                        @if($cartPromoCheck->description)
                            <p class="text-xs text-gray-500 mt-0.5">{{ $cartPromoCheck->description }}</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Cart Items Container -->
                <div class="overflow-x-auto rounded-3xl shadow-xl">
                <div class="bg-[#fdf7f2] min-w-[640px] overflow-hidden rounded-3xl">
                    <!-- Header Bar with Select All -->
                    <div class="bg-[#ea5a47] text-white px-6 py-4">
                        <div class="hidden sm:grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-5">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox"
                                           id="select-all-checkbox"
                                           class="w-5 h-5 rounded-lg cursor-pointer accent-white"
                                           onchange="toggleSelectAll(this)">
                                    <span class="font-semibold text-sm uppercase tracking-wider">Select All</span>
                                </label>
                            </div>
                            <div class="col-span-2 text-center font-semibold text-sm uppercase tracking-wider">Price</div>
                            <div class="col-span-2 text-center font-semibold text-sm uppercase tracking-wider">Quantity</div>
                            <div class="col-span-2 text-center font-semibold text-sm uppercase tracking-wider">Subtotal</div>
                            <div class="col-span-1 text-right">
                                <button type="button"
                                        onclick="showBulkDeleteModal()"
                                        id="delete-selected-btn"
                                        class="text-white hover:text-red-200 transition-all duration-200 px-3 py-1 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed"
                                        disabled>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        {{-- Mobile header --}}
                        <div class="sm:hidden flex items-center justify-between">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox"
                                       id="select-all-checkbox-mobile"
                                       class="w-5 h-5 rounded-lg cursor-pointer accent-white"
                                       onchange="toggleSelectAll(this)">
                                <span class="font-semibold text-sm uppercase tracking-wider">Select All</span>
                            </label>
                            <button type="button"
                                    onclick="showBulkDeleteModal()"
                                    id="delete-selected-btn-mobile"
                                    class="text-white hover:text-red-200 transition-all duration-200 px-3 py-1 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed"
                                    disabled>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Items -->
                    @php
                        $cartPromo          = \App\Models\Promotion::todayPromo();
                        $cartPromoPercent   = $cartPromo ? $cartPromo->discount_percentage : 0;
                    @endphp
                    @foreach($cartItems as $item)
                        @php
                            $menuItem          = $item->menuItem;
                            $stock             = $menuItem->stock ?? 0;
                            $stockColor        = $stock > 5 ? 'green' : ($stock > 0 ? 'yellow' : 'red');
                            $discountedPrice   = $cartPromo ? round($item->price * (1 - $cartPromoPercent / 100), 2) : null;
                            $effectivePrice    = $discountedPrice ?? $item->price;
                        @endphp
                        <div class="cart-item border-b border-gray-200 last:border-b-0 hover:bg-white/50 transition-colors"
                             data-id="{{ $item->id }}"
                             data-menu-item-id="{{ $menuItem->id }}"
                             data-price="{{ $effectivePrice }}"
                             data-stock="{{ $stock }}">
                            
                            <div class="grid grid-cols-12 gap-4 items-center px-6 py-4">
                                <!-- LEFT SECTION - Checkbox + Image + Info -->
                                <div class="col-span-5">
                                    <div class="flex items-center gap-4">
                                        <!-- Custom Checkbox -->
                                        <label class="relative flex items-center cursor-pointer">
                                            <input type="checkbox"
                                                   class="cart-item-checkbox w-5 h-5 appearance-none border-2 border-gray-300 rounded-lg checked:bg-[#ea5a47] checked:border-[#ea5a47] transition-all duration-200"
                                                   data-id="{{ $item->id }}"
                                                   onchange="updateSelection()">
                                            <svg class="absolute w-4 h-4 text-white left-0.5 top-0.5 pointer-events-none hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </label>

                                        <!-- Product Image -->
                                        <div class="relative">
                                            <div class="absolute inset-0 bg-[#ea5a47] opacity-10 rounded-xl"></div>
                                            @if($menuItem->image_path)
                                                <img src="{{ Storage::url($menuItem->image_path) }}"
                                                     class="w-16 h-16 object-cover rounded-xl border-2 border-white shadow-md"
                                                     alt="{{ $menuItem->name }}">
                                            @else
                                                <div class="w-16 h-16 bg-gray-200 rounded-xl flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <!-- Stock Badge -->
                                            <span class="absolute -top-2 -right-2 px-2 py-1 text-white text-xs rounded-full" 
                                                  style="background-color: 
                                                    @if($stockColor == 'green') #10b981
                                                    @elseif($stockColor == 'yellow') #f59e0b
                                                    @elseif($stockColor == 'red') #ef4444
                                                    @else #6b7280
                                                    @endif">
                                                {{ $stock > 10 ? 'In stock' : $stock . ' left' }}
                                            </span>
                                        </div>

                                        <!-- Product Info -->
                                        <div>
                                            <h2 class="font-bold text-lg text-gray-800">
                                                {{ $menuItem->name }}
                                            </h2>
                                            <p class="text-xs text-gray-500 mt-1">
                                                Stock available: <span class="font-semibold">{{ $stock }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Price -->
                                <div class="col-span-2 text-center">
                                    <span class="font-medium {{ $discountedPrice ? 'text-[#ea5a47]' : 'text-gray-700' }}">
                                        ₱{{ number_format($effectivePrice, 2) }}
                                    </span>
                                    @if($discountedPrice)
                                        <div class="text-xs text-gray-400 line-through leading-none mt-0.5">
                                            ₱{{ number_format($item->price, 2) }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Quantity Controls -->
                                <div class="col-span-2 flex items-center justify-center gap-3">
                                    <button type="button"
                                        onclick="changeQuantity({{ $item->id }}, -1)"
                                        class="quantity-btn minus-btn w-10 h-10 flex items-center justify-center bg-white rounded-xl border-2 border-gray-200 text-gray-600 hover:bg-[#ea5a47] hover:border-[#ea5a47] hover:text-white transition-all duration-200 font-bold text-lg shadow-sm
                                               {{ $item->quantity <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                        −
                                    </button>

                                    <span class="font-bold text-lg text-gray-700 w-8 text-center quantity">
                                        {{ $item->quantity }}
                                    </span>

                                    <button type="button"
                                        onclick="changeQuantity({{ $item->id }}, 1)"
                                        class="quantity-btn plus-btn w-10 h-10 flex items-center justify-center bg-white rounded-xl border-2 border-gray-200 text-gray-600 hover:bg-[#ea5a47] hover:border-[#ea5a47] hover:text-white transition-all duration-200 font-bold text-lg shadow-sm
                                               {{ $item->quantity >= $stock ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ $item->quantity >= $stock ? 'disabled' : '' }}>
                                        +
                                    </button>
                                </div>

                                <!-- Subtotal -->
                                <div class="col-span-2 text-center">
                                    <span class="font-bold text-lg text-[#ea5a47] subtotal">
                                        ₱{{ number_format($effectivePrice * $item->quantity, 2) }}
                                    </span>
                                    @if($discountedPrice)
                                        <div class="text-xs text-gray-400 line-through leading-none mt-0.5">
                                            ₱{{ number_format($item->price * $item->quantity, 2) }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Action column -->
                                <div class="col-span-1 text-right">
                                    <button type="button"
                                            onclick="showSingleDeleteModal({{ $item->id }}, this.closest('.cart-item'))"
                                            class="delete-btn text-gray-400 hover:text-[#ea5a47] transition-colors p-2 hover:bg-red-50 rounded-lg"
                                            title="Remove item">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-6 h-6"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Low Stock Warning -->
                            @if($stock <= 5 && $stock > 0)
                                <div class="px-6 pb-3 text-xs text-{{ $stock <= 2 ? 'red' : 'yellow' }}-600">
                                    ⚠️ Only {{ $stock }} left in stock!
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                </div>{{-- /overflow-x-auto --}}

                <!-- Bottom Section with Selection Info -->
                <div class="mt-8 bg-white rounded-2xl shadow-lg p-6 border-2 border-[#fdf7f2]">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 sm:gap-6">
                        <div class="flex items-center gap-4">
                            <div class="bg-[#ea5a47] bg-opacity-10 p-3 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#ea5a47]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">
                                    <span id="selected-count">0</span> item(s) selected
                                </p>
                                <h2 class="text-3xl font-black text-gray-800">
                                    <span id="grand-total">₱0.00</span>
                                </h2>
                            </div>
                        </div>

                        <!-- Proceed to Checkout Button (hidden on mobile — sticky bar handles it) -->
                        <button type="button"
                                onclick="prepareCheckout()"
                                id="checkout-btn"
                                class="group hidden md:flex w-full md:w-auto bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white px-10 py-4 rounded-2xl font-bold text-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 items-center justify-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span>Proceed to Checkout</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </button>   
                    </div>

                    <!-- Pickup Info -->
                    <div class="mt-4 flex items-center justify-center gap-2 text-sm text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Ready for pickup in 20-30 minutes after payment confirmation</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</form>

<!-- Sticky Mobile Checkout Bar (visible only on small screens when items are selected) -->
@if(!$cartItems->isEmpty())
<div id="sticky-checkout-bar"
     class="fixed bottom-0 left-0 right-0 z-40 md:hidden bg-white border-t-2 border-gray-100 px-4 py-3 shadow-2xl hidden">
    <div class="flex items-center justify-between gap-3">
        <div>
            <p class="text-xs text-gray-500"><span id="sticky-count">0</span> item(s) selected</p>
            <p class="text-lg font-black text-[#ea5a47]" id="sticky-total">₱0.00</p>
        </div>
        <button type="button"
                onclick="prepareCheckout()"
                id="sticky-checkout-btn"
                class="bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white px-6 py-3 rounded-xl font-bold text-sm flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                disabled>
            Checkout
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </button>
    </div>
</div>
@endif

<!-- DELETE FORMS -->
@foreach($cartItems as $item)
    <form id="delete-form-{{ $item->id }}" 
          action="{{ route('client.cart.remove', $item->id) }}" 
          method="POST"
          class="hidden">
        @csrf
        @method('DELETE')
    </form>
@endforeach

<script>
let currentItemId = null;
let currentItemElement = null;
let currentMenuItemId = null;
let bulkDeleteMode = false;
let selectedItemsToDelete = [];

function showToast(message, isError = false) {
    let toast = document.getElementById('toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'toast';
        toast.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-4 py-3 rounded-lg shadow-xl z-50 transition-all duration-300 opacity-0 transform translate-y-0';
        document.body.appendChild(toast);
    }
    
    toast.textContent = message;
    toast.classList.remove('bg-green-600', 'bg-red-600');
    
    if (isError) {
        toast.classList.add('bg-red-600');
    } else {
        toast.classList.add('bg-green-600');
    }
    
    toast.style.opacity = '1';
    toast.style.transform = 'translateY(0)';
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px)';
    }, 3000);
}

function showUndoToast(menuItemId) {
    // Remove any existing undo toast
    const existing = document.getElementById('undo-toast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.id = 'undo-toast';
    toast.className = 'fixed bottom-4 right-4 z-50 bg-gray-800 text-white px-4 py-3 rounded-xl shadow-xl flex items-center gap-3 animate-slide-in-right';
    toast.setAttribute('role', 'status');
    toast.setAttribute('aria-live', 'polite');

    let countdown = 5;
    toast.innerHTML = `
        <span class="text-sm">Item removed.</span>
        <button id="undo-btn"
                class="text-[#ea5a47] font-bold text-sm hover:text-orange-300 underline transition-colors"
                aria-label="Undo item removal">
            Undo (${countdown}s)
        </button>
        <button onclick="document.getElementById('undo-toast')?.remove()" aria-label="Dismiss" class="text-gray-400 hover:text-white ml-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    `;

    document.body.appendChild(toast);

    // Countdown timer
    const interval = setInterval(() => {
        countdown--;
        const btn = document.getElementById('undo-btn');
        if (btn) btn.textContent = `Undo (${countdown}s)`;
        if (countdown <= 0) {
            clearInterval(interval);
            document.getElementById('undo-toast')?.remove();
        }
    }, 1000);

    // Undo: re-add item to cart
    document.getElementById('undo-btn')?.addEventListener('click', function () {
        clearInterval(interval);
        document.getElementById('undo-toast')?.remove();

        fetch(`/client/cart/add/${menuItemId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ quantity: 1 })
        })
        .then(r => r.json())
        .then(d => {
            if (d.success !== false) {
                showToast('Item added back to cart.');
                if (d.count !== undefined) updateCartBadge(d.count);
                // Reload to show the item in the cart list
                setTimeout(() => location.reload(), 800);
            } else {
                showToast(d.message || 'Could not re-add item.', true);
            }
        })
        .catch(() => showToast('Could not re-add item. Please browse the menu.', true));
    });
}

function showSingleDeleteModal(itemId, element) {
    bulkDeleteMode = false;
    currentItemId = itemId;
    currentItemElement = element;
    currentMenuItemId = element.getAttribute('data-menu-item-id') || null;

    // Get item details
    const itemTitle = element.querySelector('.font-bold.text-lg.text-gray-800')?.textContent?.trim() || 'Item';
    const itemPrice = element.querySelector('.font-medium.text-gray-700')?.textContent || '₱0.00';
    const itemImage = element.querySelector('img')?.src || '';
    
    document.getElementById('modal-title').textContent = 'Remove Item?';
    document.getElementById('modal-message').textContent = `Are you sure you want to remove this item from your cart?`;
    document.getElementById('modal-item-preview').classList.remove('hidden');
    document.getElementById('modal-bulk-message').classList.add('hidden');
    document.getElementById('modal-item-title').textContent = itemTitle;
    document.getElementById('modal-item-price').textContent = itemPrice;
    document.getElementById('modal-item-image').src = itemImage;
    
    const modal = document.getElementById('confirm-modal');
    const modalCard = document.getElementById('modal-card');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        modalCard.classList.remove('scale-95', 'opacity-0');
        modalCard.classList.add('scale-100', 'opacity-100');
    }, 10);
    
    document.body.style.overflow = 'hidden';
}

function showBulkDeleteModal() {
    const selectedCheckboxes = document.querySelectorAll('.cart-item-checkbox:checked');
    if (selectedCheckboxes.length === 0) return;
    
    bulkDeleteMode = true;
    selectedItemsToDelete = [];
    selectedCheckboxes.forEach(checkbox => {
        selectedItemsToDelete.push({
            id: checkbox.getAttribute('data-id'),
            element: checkbox.closest('.cart-item')
        });
    });
    
    document.getElementById('modal-title').textContent = 'Remove Selected Items?';
    document.getElementById('modal-message').textContent = `Are you sure you want to remove ${selectedItemsToDelete.length} item(s) from your cart?`;
    document.getElementById('modal-item-preview').classList.add('hidden');
    document.getElementById('modal-bulk-message').classList.remove('hidden');
    document.getElementById('bulk-count').textContent = `You are about to remove ${selectedItemsToDelete.length} item(s) from your cart. You can re-add them from the menu.`;
    
    const modal = document.getElementById('confirm-modal');
    const modalCard = document.getElementById('modal-card');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        modalCard.classList.remove('scale-95', 'opacity-0');
        modalCard.classList.add('scale-100', 'opacity-100');
    }, 10);
    
    document.body.style.overflow = 'hidden';
}

function closeConfirmModal() {
    const modal = document.getElementById('confirm-modal');
    const modalCard = document.getElementById('modal-card');
    
    modalCard.classList.remove('scale-100', 'opacity-100');
    modalCard.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 300);
}

function confirmRemove() {
    if (bulkDeleteMode) {
        confirmBulkRemove();
    } else {
        confirmSingleRemove();
    }
}

function confirmSingleRemove() {
    if (!currentItemId || !currentItemElement) return;
    
    const btn = document.getElementById('confirm-remove-btn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span>Removing...</span><svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>';
    btn.disabled = true;
    
    currentItemElement.style.transition = 'all 0.3s ease';
    currentItemElement.style.opacity = '0';
    currentItemElement.style.transform = 'translateX(20px)';
    
    fetch(`/client/cart/remove/${currentItemId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const removedMenuItemId = currentMenuItemId;
            setTimeout(() => {
                currentItemElement.remove();
                updateCartBadge(data.count);
                updateSelection();

                // Show undo toast with re-add link
                if (removedMenuItemId) {
                    showUndoToast(removedMenuItemId);
                } else {
                    showToast('Item removed from cart');
                }

                if (document.querySelectorAll('.cart-item').length === 0) {
                    location.reload();
                }
            }, 300);
        } else {
            showToast(data.message || 'Error removing item', true);
            currentItemElement.style.opacity = '1';
            currentItemElement.style.transform = 'translateX(0)';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast(navigator.onLine ? 'Could not remove item. Please try again.' : 'No connection — check your network.', true);
        currentItemElement.style.opacity = '1';
        currentItemElement.style.transform = 'translateX(0)';
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        closeConfirmModal();
    });
}

function confirmBulkRemove() {
    if (selectedItemsToDelete.length === 0) return;
    
    const btn = document.getElementById('confirm-remove-btn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span>Removing...</span><svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>';
    btn.disabled = true;
    
    // Animate all selected items
    selectedItemsToDelete.forEach(item => {
        item.element.style.transition = 'all 0.3s ease';
        item.element.style.opacity = '0';
        item.element.style.transform = 'translateX(20px)';
    });
    
    // Send delete requests for all selected items
    const deletePromises = selectedItemsToDelete.map(item => {
        return fetch(`/client/cart/remove/${item.id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        }).then(response => response.json());
    });
    
    Promise.all(deletePromises)
        .then(results => {
            const allSuccess = results.every(r => r.success);
            if (allSuccess) {
                setTimeout(() => {
                    selectedItemsToDelete.forEach(item => {
                        item.element.remove();
                    });
                    updateCartBadge(results[0]?.count || 0);
                    updateSelection();
                    showToast(`${selectedItemsToDelete.length} item(s) removed from cart`);
                    
                    if (document.querySelectorAll('.cart-item').length === 0) {
                        location.reload();
                    }
                }, 300);
            } else {
                showToast('Some items could not be removed', true);
                selectedItemsToDelete.forEach(item => {
                    item.element.style.opacity = '1';
                    item.element.style.transform = 'translateX(0)';
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error removing items', true);
            selectedItemsToDelete.forEach(item => {
                item.element.style.opacity = '1';
                item.element.style.transform = 'translateX(0)';
            });
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            closeConfirmModal();
        });
}

function toggleSelectAll(source) {
    const isChecked = source.checked;

    // Sync both select-all checkboxes
    ['select-all-checkbox', 'select-all-checkbox-mobile'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.checked = isChecked;
    });

    document.querySelectorAll('.cart-item-checkbox').forEach(checkbox => {
        checkbox.checked = isChecked;
        if (isChecked) {
            checkbox.nextElementSibling?.classList.add('block');
            checkbox.nextElementSibling?.classList.remove('hidden');
        } else {
            checkbox.nextElementSibling?.classList.remove('block');
            checkbox.nextElementSibling?.classList.add('hidden');
        }
    });

    updateSelection();
}

function updateSelection() {
    const selectedCheckboxes = document.querySelectorAll('.cart-item-checkbox:checked');
    const selectedCount = selectedCheckboxes.length;
    const deleteSelectedBtn = document.getElementById('delete-selected-btn');
    const checkoutBtn = document.getElementById('checkout-btn');
    const selectAllCheckbox = document.getElementById('select-all-checkbox');
    const totalCheckboxes = document.querySelectorAll('.cart-item-checkbox').length;
    
    // Update selected count display
    document.getElementById('selected-count').textContent = selectedCount;
    
    // Enable/disable delete selected button
    if (deleteSelectedBtn) {
        if (selectedCount > 0) {
            deleteSelectedBtn.disabled = false;
        } else {
            deleteSelectedBtn.disabled = true;
        }
    }
    
    // Enable/disable checkout button
    if (checkoutBtn) {
        if (selectedCount > 0) {
            checkoutBtn.disabled = false;
        } else {
            checkoutBtn.disabled = true;
        }
    }
    
    // Sync both select-all checkboxes
    ['select-all-checkbox', 'select-all-checkbox-mobile'].forEach(id => {
        const cb = document.getElementById(id);
        if (!cb) return;
        if (selectedCount === totalCheckboxes && totalCheckboxes > 0) {
            cb.checked = true;
            cb.indeterminate = false;
        } else if (selectedCount > 0) {
            cb.indeterminate = true;
            cb.checked = false;
        } else {
            cb.checked = false;
            cb.indeterminate = false;
        }
    });

    // Also sync delete-selected mobile button
    const deleteMobileBtn = document.getElementById('delete-selected-btn-mobile');
    if (deleteMobileBtn) deleteMobileBtn.disabled = selectedCount === 0;
    
    calculateGrandTotal();

    // Sync sticky mobile checkout bar
    const stickyBar = document.getElementById('sticky-checkout-bar');
    const stickyBtn = document.getElementById('sticky-checkout-btn');
    const stickyCount = document.getElementById('sticky-count');
    const stickyTotal = document.getElementById('sticky-total');
    if (stickyBar) {
        if (selectedCount > 0) {
            stickyBar.classList.remove('hidden');
            stickyBtn.disabled = false;
            stickyCount.textContent = selectedCount;
        } else {
            stickyBar.classList.add('hidden');
            stickyBtn.disabled = true;
        }
    }
    if (stickyTotal) stickyTotal.textContent = document.getElementById('grand-total')?.textContent || '₱0.00';
}

function changeQuantity(itemId, change) {
    const itemElement = document.querySelector(`.cart-item[data-id="${itemId}"]`);
    if (!itemElement) return;
    
    const quantitySpan = itemElement.querySelector('.quantity');
    const currentQty = parseInt(quantitySpan.textContent);
    const newQty = currentQty + change;
    const stock = parseInt(itemElement.dataset.stock);
    const price = parseFloat(itemElement.dataset.price);
    
    if (newQty < 1) {
        showToast('Quantity cannot be less than 1', true);
        return;
    }
    if (newQty > stock) {
        showToast(`Only ${stock} items available in stock`, true);
        return;
    }
    
    const originalQty = currentQty;
    const originalSubtotal = itemElement.querySelector('.subtotal').textContent;
    
    const minusBtn = itemElement.querySelector('.minus-btn');
    const plusBtn = itemElement.querySelector('.plus-btn');
    minusBtn.disabled = true;
    plusBtn.disabled = true;
    
    quantitySpan.textContent = newQty;
    const subtotalSpan = itemElement.querySelector('.subtotal');
    subtotalSpan.textContent = '₱' + (price * newQty).toFixed(2);
    
    fetch(`/client/cart/update/${itemId}`, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            "Accept": "application/json"
        },
        body: JSON.stringify({ quantity: newQty })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartBadge(data.count);
            updateSelection();
            showToast('Quantity updated');
            updateButtonStates(itemElement, newQty, stock);
        } else {
            quantitySpan.textContent = originalQty;
            subtotalSpan.textContent = originalSubtotal;
            showToast(data.message || 'Failed to update', true);
            updateButtonStates(itemElement, originalQty, stock);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        quantitySpan.textContent = originalQty;
        subtotalSpan.textContent = originalSubtotal;
        showToast('Error updating quantity', true);
        updateButtonStates(itemElement, originalQty, stock);
    })
    .finally(() => {
        minusBtn.disabled = false;
        plusBtn.disabled = false;
    });
}

function updateButtonStates(itemElement, currentQty, stock) {
    const minusBtn = itemElement.querySelector('.minus-btn');
    const plusBtn = itemElement.querySelector('.plus-btn');
    
    if (currentQty <= 1) {
        minusBtn.disabled = true;
        minusBtn.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        minusBtn.disabled = false;
        minusBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
    
    if (currentQty >= stock) {
        plusBtn.disabled = true;
        plusBtn.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        plusBtn.disabled = false;
        plusBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}

function calculateGrandTotal() {
    let total = 0;
    document.querySelectorAll('.cart-item-checkbox:checked').forEach(checkbox => {
        const itemElement = checkbox.closest('.cart-item');
        if (itemElement) {
            const subtotalText = itemElement.querySelector('.subtotal').textContent;
            total += parseFloat(subtotalText.replace(/[^0-9.-]+/g, ''));
        }
    });
    const grandTotalSpan = document.getElementById('grand-total');
    if (grandTotalSpan) {
        grandTotalSpan.textContent = '₱' + total.toFixed(2);
    }
    // Sync sticky bar total
    const stickyTotal = document.getElementById('sticky-total');
    if (stickyTotal) stickyTotal.textContent = '₱' + total.toFixed(2);
}

function prepareCheckout() {
    const selectedCheckboxes = document.querySelectorAll('.cart-item-checkbox:checked');
    
    if (selectedCheckboxes.length === 0) {
        showToast('Please select at least one item to checkout', true);
        return false;
    }
    
    const selectedItems = [];
    selectedCheckboxes.forEach(checkbox => {
        selectedItems.push(checkbox.getAttribute('data-id'));
    });
    
    const selectedItemsInput = document.getElementById('selected-items-input');
    if (selectedItemsInput) {
        selectedItemsInput.value = JSON.stringify(selectedItems);
    }

    const checkoutForm = document.getElementById('checkout-form');
    if (checkoutForm) {
        if (typeof showProgress === 'function') showProgress();
        checkoutForm.submit();
    }
}

function updateCartBadge(count) {
    const badge = document.getElementById('cart-count-badge');
    if (badge) {
        if (count > 0) {
            badge.textContent = count;
            badge.classList.remove('hidden');
            badge.classList.add('scale-125');
            setTimeout(() => {
                badge.classList.remove('scale-125');
            }, 200);
        } else {
            badge.classList.add('hidden');
        }
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateSelection();
    
    document.querySelectorAll('.cart-item').forEach(itemElement => {
        const quantitySpan = itemElement.querySelector('.quantity');
        const currentQty = parseInt(quantitySpan.textContent);
        const stock = parseInt(itemElement.dataset.stock);
        updateButtonStates(itemElement, currentQty, stock);
    });
    
    document.querySelectorAll('.cart-item-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                this.nextElementSibling?.classList.add('block');
                this.nextElementSibling?.classList.remove('hidden');
            } else {
                this.nextElementSibling?.classList.remove('block');
                this.nextElementSibling?.classList.add('hidden');
            }
        });
    });
    
    const backdrop = document.getElementById('modal-backdrop');
    if (backdrop) {
        backdrop.addEventListener('click', closeConfirmModal);
    }
});

window.updateCartBadge = updateCartBadge;
window.changeQuantity = changeQuantity;
window.prepareCheckout = prepareCheckout;
window.calculateGrandTotal = calculateGrandTotal;
window.showToast = showToast;
window.toggleSelectAll = toggleSelectAll;
window.updateSelection = updateSelection;
window.showSingleDeleteModal = showSingleDeleteModal;
window.showBulkDeleteModal = showBulkDeleteModal;
</script>

<style>
    .cart-item-checkbox:checked + svg {
        display: block !important;
    }
    
    .cart-item-checkbox:checked {
        background-color: #ea5a47;
        border-color: #ea5a47;
    }
    
    /* Indeterminate state for select all */
    #select-all-checkbox:indeterminate {
        background-color: #ea5a47;
        border-color: #ea5a47;
        position: relative;
    }
    
    #select-all-checkbox:indeterminate::after {
        content: '';
        position: absolute;
        width: 10px;
        height: 2px;
        background-color: white;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    
    /* Modal animations */
    #confirm-modal.show {
        display: flex;
    }
    
    #confirm-modal.show #modal-card {
        transform: scale(1);
        opacity: 1;
    }
    
    #confirm-modal #modal-card {
        transition: all 0.3s ease-out;
    }
    
    #confirm-modal .animate-bounce {
        animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .animate-spin {
        animation: spin 1s linear infinite;
        display: inline-block;
    }
    
    button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }
    
    .scale-125 {
        transform: scale(1.25);
    }
</style>
@endsection