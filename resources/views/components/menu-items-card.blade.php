@props(['item'])

@vite('resources/css/app.css')

@php
    $activePromo      = \App\Models\Promotion::todayPromo();
    $discountedPrice  = $activePromo ? round($item->price * (1 - $activePromo->discount_percentage / 100), 2) : null;
    $displayPrice     = $discountedPrice ?? $item->price;
@endphp

<!-- Quantity Selector Modal -->
<div id="quantity-modal-{{ $item->id }}" 
     class="fixed inset-0 z-50 flex items-center justify-center hidden">
    
    <!-- Backdrop with blur -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeQuantityModal({{ $item->id }})"></div>
    
    <!-- Modal Card -->
    <div class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" 
         id="modal-card-{{ $item->id }}">
        
        <!-- Decorative top bar -->
        <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-[#ea5a47] to-[#c53030] rounded-t-3xl"></div>
        
        <!-- Close button -->
        <button onclick="closeQuantityModal({{ $item->id }})" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        
        <div class="p-8">
            <!-- Food-themed icon -->
            <div class="relative mb-6 text-center">
                <div class="absolute inset-0 bg-[#ea5a47] opacity-10 rounded-full blur-2xl"></div>
                <div class="relative bg-gradient-to-br from-[#ea5a47] to-[#c53030] w-24 h-24 rounded-full flex items-center justify-center mx-auto shadow-lg">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            
            <!-- Item Preview -->
            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-200">
                @if($item->image_path)
                    @php
                        $isExternalUrl = filter_var($item->image_path, FILTER_VALIDATE_URL);
                    @endphp
                    
                    @if($isExternalUrl)
                        <img src="{{ $item->image_path }}"
                             class="w-20 h-20 object-cover rounded-xl border-2 border-white shadow-md"
                             alt="{{ $item->name }}"
                             loading="lazy"
                             onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 80 80%22%3E%3Crect width=%2280%22 height=%2280%22 fill=%22%23f3f4f6%22/%3E%3Cpath d=%22M20 56l13-13a4 4 0 016 0L52 56m-4-4l5-5a4 4 0 016 0L64 52M28 32h.02M20 68h40a4 4 0 004-4V20a4 4 0 00-4-4H20a4 4 0 00-4 4v44a4 4 0 004 4z%22 fill=%22none%22 stroke=%22%239ca3af%22 stroke-width=%223%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22/%3E%3C/svg%3E';">
                    @else
                        <img src="{{ Storage::url($item->image_path) }}"
                             class="w-20 h-20 object-cover rounded-xl border-2 border-white shadow-md"
                             alt="{{ $item->name }}"
                             loading="lazy"
                             onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 80 80%22%3E%3Crect width=%2280%22 height=%2280%22 fill=%22%23f3f4f6%22/%3E%3Cpath d=%22M20 56l13-13a4 4 0 016 0L52 56m-4-4l5-5a4 4 0 016 0L64 52M28 32h.02M20 68h40a4 4 0 004-4V20a4 4 0 00-4-4H20a4 4 0 00-4 4v44a4 4 0 004 4z%22 fill=%22none%22 stroke=%22%239ca3af%22 stroke-width=%223%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22/%3E%3C/svg%3E';">
                    @endif
                @else
                    <div class="w-20 h-20 bg-gray-100 rounded-xl flex items-center justify-center border-2 border-gray-200">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                @endif
                
                <div class="flex-1">
                    <h3 class="font-bold text-xl text-gray-800">{{ $item->name }}</h3>
                    <div class="flex items-baseline gap-2 flex-wrap">
                        <p class="text-[#ea5a47] font-bold text-lg">₱{{ number_format($displayPrice, 2) }}</p>
                        @if($discountedPrice)
                            <p class="text-gray-400 text-sm line-through">₱{{ number_format($item->price, 2) }}</p>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Available stock: <span id="stock-display-{{ $item->id }}">{{ $item->stock }}</span></p>
                </div>
            </div>
            
            <!-- Quantity Selector -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-3">Select Quantity:</label>
                <div class="flex items-center justify-center gap-4">
                    <button type="button" 
                            onclick="decrementQuantity({{ $item->id }})"
                            class="w-12 h-12 flex items-center justify-center bg-white rounded-xl border-2 border-gray-200 text-gray-600 hover:bg-[#ea5a47] hover:border-[#ea5a47] hover:text-white transition-all duration-200 font-bold text-xl shadow-sm"
                            id="modal-minus-{{ $item->id }}">
                        −
                    </button>
                    
                    <input type="number" 
                           id="modal-quantity-{{ $item->id }}"
                           value="1" 
                           min="1" 
                           max="{{ $item->stock }}"
                           class="w-20 h-12 text-center font-bold text-xl text-gray-700 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:ring-1 focus:ring-[#ea5a47] outline-none"
                           onchange="validateQuantityInput({{ $item->id }})"
                           oninput="validateQuantityInput({{ $item->id }})">
                    
                    <button type="button" 
                            onclick="incrementQuantity({{ $item->id }})"
                            class="w-12 h-12 flex items-center justify-center bg-white rounded-xl border-2 border-gray-200 text-gray-600 hover:bg-[#ea5a47] hover:border-[#ea5a47] hover:text-white transition-all duration-200 font-bold text-xl shadow-sm"
                            id="modal-plus-{{ $item->id }}">
                        +
                    </button>
                </div>
                
                <!-- Price Preview -->
                <div class="mt-4 text-center">
                    <span class="text-sm text-gray-500">Total:</span>
                    <span class="ml-2 text-xl font-bold text-[#ea5a47]" id="modal-total-{{ $item->id }}">
                        ₱{{ number_format($displayPrice, 2) }}
                    </span>
                    {{-- hidden unit price used by JS to calculate total --}}
                    <span class="hidden" id="modal-unit-price-{{ $item->id }}">{{ $displayPrice }}</span>
                </div>
                
                <!-- Error message for invalid quantity -->
                <div id="quantity-error-{{ $item->id }}" class="mt-2 text-center text-sm text-red-600 hidden">
                    Please enter a quantity between 1 and {{ $item->stock }}
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-3">
                <button onclick="closeQuantityModal({{ $item->id }})" 
                        class="flex-1 px-6 py-3 border-2 border-gray-200 text-gray-700 font-semibold rounded-xl hover:border-[#ea5a47] hover:text-[#ea5a47] transition-all duration-200">
                    Cancel
                </button>
                <button onclick="addToCartWithQuantity({{ $item->id }})" 
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-semibold rounded-xl hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center gap-2"
                        id="modal-add-btn-{{ $item->id }}">
                    <span>Add to Tray</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Main Card with Floating Hover Card (Clean & Minimal) -->
<div class="relative group">
    <div class="bg-[#fdf7f2] rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border-2 
        {{ $item->stock > 0 ? 'hover:border-[#ea5a47]' : 'border-gray-200 opacity-75' }}">
        
        <!-- Image Container -->
        <div class="relative overflow-hidden bg-gradient-to-br from-[#ea5a47]/10 to-[#c53030]/5 p-6">
            <!-- Decorative Dish Icons -->
            <div class="absolute top-2 right-2 text-2xl opacity-20 group-hover:opacity-30 transition-opacity">
                🍽️
            </div>
            <div class="absolute bottom-2 left-2 text-2xl opacity-20 group-hover:opacity-30 transition-opacity">
                🥘
            </div>
            
            <!-- Product Image -->
            <div class="relative transform group-hover:scale-105 transition-transform duration-300">
                <div class="absolute inset-0 bg-[#ea5a47] opacity-10 rounded-2xl blur-sm"></div>
                
                @if($item->image_path)
                    @php
                        $isExternalUrl = filter_var($item->image_path, FILTER_VALIDATE_URL);
                    @endphp
                    
                    @if($isExternalUrl)
                        <img class="w-48 h-48 object-cover rounded-2xl mx-auto shadow-xl border-4 border-white
                            {{ $item->stock < 1 ? 'opacity-50 grayscale' : '' }}"
                             src="{{ $item->image_path }}"
                             alt="{{ $item->name }}"
                             onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 192 192%22%3E%3Crect width=%22192%22 height=%22192%22 fill=%22%23f3f4f6%22/%3E%3Cpath d=%22M48 134l32-32a10 10 0 0114 0L128 134m-10-10l12-12a10 10 0 0114 0L160 128M68 76h.02M48 164h96a10 10 0 0010-10V48a10 10 0 00-10-10H48a10 10 0 00-10 10v106a10 10 0 0010 10z%22 fill=%22none%22 stroke=%22%239ca3af%22 stroke-width=%228%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22/%3E%3C/svg%3E';">
                    @else
                        <img class="w-48 h-48 object-cover rounded-2xl mx-auto shadow-xl border-4 border-white
                            {{ $item->stock < 1 ? 'opacity-50 grayscale' : '' }}"
                             src="{{ Storage::url($item->image_path) }}"
                             alt="{{ $item->name }}"
                             onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 192 192%22%3E%3Crect width=%22192%22 height=%22192%22 fill=%22%23f3f4f6%22/%3E%3Cpath d=%22M48 134l32-32a10 10 0 0114 0L128 134m-10-10l12-12a10 10 0 0114 0L160 128M68 76h.02M48 164h96a10 10 0 0010-10V48a10 10 0 00-10-10H48a10 10 0 00-10 10v106a10 10 0 0010 10z%22 fill=%22none%22 stroke=%22%239ca3af%22 stroke-width=%228%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22/%3E%3C/svg%3E';">
                    @endif
                @else
                    <div class="w-48 h-48 bg-gray-100 rounded-2xl mx-auto shadow-xl border-4 border-white flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                @endif
                
                <!-- Out of Stock Overlay -->
                @if($item->stock < 1)
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="bg-red-600/90 text-white font-bold text-xl py-3 px-6 rounded-2xl transform -rotate-12 shadow-2xl border-2 border-white">
                            OUT OF STOCK
                        </div>
                    </div>
                    <svg class="absolute inset-0 w-full h-full text-red-600/30" viewBox="0 0 100 100">
                        <line x1="10" y1="10" x2="90" y2="90" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                        <line x1="90" y1="10" x2="10" y2="90" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                @endif
            </div>
            
            <!-- Stock Badge -->
            @if($item->stock > 0)
                <div class="absolute top-4 left-4 bg-white px-3 py-1 rounded-full shadow-md">
                    <span class="text-sm font-medium text-gray-600 flex items-center gap-1">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                        Stock: {{ $item->stock }}
                    </span>
                </div>
            @else
                <div class="absolute top-4 left-4 bg-red-100 px-3 py-1 rounded-full shadow-md">
                    <span class="text-sm font-medium text-red-600 flex items-center gap-1">
                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                        Out of Stock
                    </span>
                </div>
            @endif
        </div>

        <!-- Content Section - Clean & Minimal -->
        <div class="p-6 {{ $item->stock < 1 ? 'opacity-60' : '' }}">
            <!-- Product Name and Price -->
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-xl font-bold text-gray-800 group-hover:text-[#ea5a47] transition-colors line-clamp-2 
                    {{ $item->stock < 1 ? 'line-through decoration-red-500 decoration-2' : '' }}">
                    {{ $item->name }}
                </h3>
                <div class="ml-4 text-right flex-shrink-0">
                    <span class="text-2xl font-black {{ $item->stock > 0 ? 'text-[#ea5a47]' : 'text-gray-400' }}
                        {{ $item->stock < 1 ? 'line-through decoration-red-500 decoration-2' : '' }}">
                        ₱{{ number_format($displayPrice, 2) }}
                    </span>
                    @if($discountedPrice && $item->stock > 0)
                        <div class="text-xs text-gray-400 line-through leading-none mt-0.5">₱{{ number_format($item->price, 2) }}</div>
                    @endif
                </div>
            </div>
            
            <!-- Hover Hint - Simple indicator that more info is available on hover -->
            <div class="mb-4 text-center">
                <span class="text-xs text-gray-400 group-hover:text-[#ea5a47] transition-colors flex items-center justify-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Hover for details
                </span>
            </div>
            
            <!-- Add to Tray Button -->
            <button 
                type="button"
                data-id="{{ $item->id }}"
                data-stock="{{ $item->stock }}"
                onclick="openQuantityModal({{ $item->id }})"
                @if($item->stock < 1) disabled @endif
                class="add-to-cart-btn w-full inline-flex items-center justify-center gap-2 
                    {{ $item->stock > 0 
                        ? 'bg-gradient-to-r from-[#ea5a47] to-[#c53030] hover:from-[#c53030] hover:to-[#ea5a47] cursor-pointer' 
                        : 'bg-gray-300 cursor-not-allowed opacity-60' }}
                    text-white font-bold py-4 px-6 rounded-xl 
                    transition-all duration-300 transform hover:scale-[1.02] 
                    shadow-lg hover:shadow-xl group/btn
                    {{ $item->stock < 1 ? 'pointer-events-none' : '' }}">

                <!-- Cart Icon -->
                <svg class="w-5 h-5 transition-transform group-hover/btn:scale-110" 
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>

                <span class="btn-text text-lg font-semibold">
                    {{ $item->stock > 0 ? 'Add to Tray' : 'Out of Stock' }}
                </span>

                <!-- Price in Button -->
                @if($item->stock > 0)
                    <span class="ml-auto bg-white/20 px-2 py-1 rounded-lg text-sm flex items-center gap-1.5">
                        ₱{{ number_format($displayPrice, 2) }}
                        @if($discountedPrice)
                            <span class="opacity-60 line-through text-xs">₱{{ number_format($item->price, 2) }}</span>
                        @endif
                    </span>
                @endif
            </button>
        </div>
    </div>
    
    <!-- FLOATING HOVER CARD - All details appear here -->
    <div class="absolute top-1/2 left-full ml-4 transform -translate-y-1/2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-20 w-80 pointer-events-none">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-[#ea5a47] to-[#c53030] px-4 py-3">
                <h4 class="text-white font-bold text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $item->name }} - Details
                </h4>
            </div>
            
            <!-- Card Content -->
            <div class="p-4 max-h-96 overflow-y-auto custom-scrollbar">
                <!-- Serving Size -->
                @if($item->serving_display)
                    <div class="mb-4">
                        <div class="flex items-center gap-2 text-gray-600 text-sm font-semibold mb-2">
                            <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Serving Size
                        </div>
                        <p class="text-gray-700 text-sm">{{ $item->serving_display }}</p>
                    </div>
                @endif
                
                <!-- Dietary Options -->
                @if($item->dietary_badges && count($item->dietary_badges) > 0)
                    <div class="mb-4">
                        <div class="flex items-center gap-2 text-gray-600 text-sm font-semibold mb-2">
                            <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                            Dietary Options
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($item->dietary_badges as $badge)
                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full {{ $badge['color'] }}">
                                    <span>{{ $badge['icon'] }}</span>
                                    <span>{{ $badge['name'] }}</span>
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <!-- Allergens -->
                @if($item->allergen_badges && count($item->allergen_badges) > 0)
                    <div class="mb-4">
                        <div class="flex items-center gap-2 text-gray-600 text-sm font-semibold mb-2">
                            <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Allergens
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($item->allergen_badges as $badge)
                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full {{ $badge['color'] }}">
                                    <span>{{ $badge['icon'] }}</span>
                                    <span>{{ $badge['name'] }}</span>
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <!-- Allergen Notes -->
                @if($item->allergen_notes)
                    <div class="mb-4">
                        <div class="flex items-center gap-2 text-gray-600 text-sm font-semibold mb-2">
                            <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Important Notes
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-200">
                            <p class="text-xs text-yellow-800 leading-relaxed">
                                {{ $item->allergen_notes }}
                            </p>
                        </div>
                    </div>
                @endif
                
                <!-- Description -->
                @if($item->description)
                    <div class="mb-4">
                        <div class="flex items-center gap-2 text-gray-600 text-sm font-semibold mb-2">
                            <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                            Description
                        </div>
                        <p class="text-gray-700 text-sm leading-relaxed">{{ $item->description }}</p>
                    </div>
                @endif
                
                <!-- If no details available -->
                @if(!$item->serving_display && (!$item->dietary_badges || count($item->dietary_badges) == 0) && (!$item->allergen_badges || count($item->allergen_badges) == 0) && !$item->allergen_notes && !$item->description)
                    <div class="text-center py-6">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm text-gray-400">No additional details available</p>
                    </div>
                @endif
            </div>
            
            <!-- Card Footer -->
            <div class="border-t border-gray-100 px-4 py-2 bg-gray-50">
                <p class="text-xs text-gray-400 text-center flex items-center justify-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Click "Add to Tray" to customize quantity
                </p>
            </div>
        </div>
        
        <!-- Small arrow pointing to card -->
        <div class="absolute top-1/2 -left-2 transform -translate-y-1/2 w-4 h-4 bg-white rotate-45 border-l border-t border-gray-100"></div>
    </div>
</div>

<script>
    // ============ QUANTITY MODAL FUNCTIONS ============

    function openQuantityModal(itemId) {
        const modal = document.getElementById(`quantity-modal-${itemId}`);
        const modalCard = document.getElementById(`modal-card-${itemId}`);
        
        if (!modal) {
            console.error('Modal not found for item:', itemId);
            return;
        }
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalCard.classList.remove('scale-95', 'opacity-0');
            modalCard.classList.add('scale-100', 'opacity-100');
        }, 10);
        
        document.body.style.overflow = 'hidden';
        
        const quantityInput = document.getElementById(`modal-quantity-${itemId}`);
        if (quantityInput) {
            quantityInput.value = 1;
            updateModalTotal(itemId);
        }
        
        const errorMsg = document.getElementById(`quantity-error-${itemId}`);
        if (errorMsg) errorMsg.classList.add('hidden');
        
        updateModalButtonStates(itemId);
    }
    
    function closeQuantityModal(itemId) {
        const modal = document.getElementById(`quantity-modal-${itemId}`);
        const modalCard = document.getElementById(`modal-card-${itemId}`);
        
        if (!modal) return;
        
        modalCard.classList.remove('scale-100', 'opacity-100');
        modalCard.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
        
        document.body.style.overflow = '';
    }
    
    function validateQuantityInput(itemId) {
        const quantityInput = document.getElementById(`modal-quantity-${itemId}`);
        const errorMsg = document.getElementById(`quantity-error-${itemId}`);
        
        if (!quantityInput) return;
        
        let value = parseInt(quantityInput.value);
        const min = parseInt(quantityInput.min);
        const max = parseInt(quantityInput.max);
        
        if (isNaN(value) || value < min) {
            quantityInput.value = min;
            value = min;
        } else if (value > max) {
            quantityInput.value = max;
            value = max;
            if (errorMsg) errorMsg.classList.remove('hidden');
        } else {
            if (errorMsg) errorMsg.classList.add('hidden');
        }
        
        updateModalTotal(itemId);
        updateModalButtonStates(itemId);
    }
    
    function decrementQuantity(itemId) {
        const quantityInput = document.getElementById(`modal-quantity-${itemId}`);
        const currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
            validateQuantityInput(itemId);
        }
    }
    
    function incrementQuantity(itemId) {
        const quantityInput = document.getElementById(`modal-quantity-${itemId}`);
        const currentValue = parseInt(quantityInput.value);
        const maxStock = parseInt(quantityInput.max);
        if (currentValue < maxStock) {
            quantityInput.value = currentValue + 1;
            validateQuantityInput(itemId);
        }
    }
    
    function updateModalButtonStates(itemId) {
        const quantityInput = document.getElementById(`modal-quantity-${itemId}`);
        const minusBtn = document.getElementById(`modal-minus-${itemId}`);
        const plusBtn = document.getElementById(`modal-plus-${itemId}`);
        
        if (!quantityInput || !minusBtn || !plusBtn) return;
        
        const currentValue = parseInt(quantityInput.value);
        const maxStock = parseInt(quantityInput.max);
        
        if (currentValue <= 1) {
            minusBtn.disabled = true;
            minusBtn.classList.add('opacity-50', 'cursor-not-allowed');
            minusBtn.classList.remove('hover:bg-[#ea5a47]', 'hover:border-[#ea5a47]', 'hover:text-white');
        } else {
            minusBtn.disabled = false;
            minusBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            minusBtn.classList.add('hover:bg-[#ea5a47]', 'hover:border-[#ea5a47]', 'hover:text-white');
        }
        
        if (currentValue >= maxStock) {
            plusBtn.disabled = true;
            plusBtn.classList.add('opacity-50', 'cursor-not-allowed');
            plusBtn.classList.remove('hover:bg-[#ea5a47]', 'hover:border-[#ea5a47]', 'hover:text-white');
        } else {
            plusBtn.disabled = false;
            plusBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            plusBtn.classList.add('hover:bg-[#ea5a47]', 'hover:border-[#ea5a47]', 'hover:text-white');
        }
    }
    
    function updateModalTotal(itemId) {
        const quantityInput = document.getElementById(`modal-quantity-${itemId}`);
        const totalSpan = document.getElementById(`modal-total-${itemId}`);
        const unitPriceEl = document.getElementById(`modal-unit-price-${itemId}`);

        if (!quantityInput || !totalSpan) return;

        const price = unitPriceEl ? parseFloat(unitPriceEl.textContent) : 0;
        const quantity = parseInt(quantityInput.value);
        const total = price * quantity;
        totalSpan.textContent = '₱' + total.toFixed(2);
    }
    
    function addToCartWithQuantity(itemId) {
        const quantityInput = document.getElementById(`modal-quantity-${itemId}`);
        const quantity = parseInt(quantityInput.value);
        const maxStock = parseInt(quantityInput.max);
        
        if (quantity < 1 || quantity > maxStock) {
            if (window.showToast) {
                window.showToast(`Please enter a valid quantity between 1 and ${maxStock}`, true);
            } else {
                alert(`Please enter a valid quantity between 1 and ${maxStock}`);
            }
            return;
        }
        
        const modalAddBtn = document.getElementById(`modal-add-btn-${itemId}`);
        const originalBtnText = modalAddBtn.innerHTML;
        
        modalAddBtn.innerHTML = '<span>Adding...</span><svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>';
        modalAddBtn.disabled = true;
        
        fetch(`/client/cart/add/${itemId}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest"
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || `Server error: ${response.status}`);
            }
            return data;
        })
        .then(data => {
            if (data.success) {
                closeQuantityModal(itemId);
                
                if (window.updateCartBadge) {
                    window.updateCartBadge(data.count);
                }
                
                if (window.showToast) {
                    window.showToast(`${quantity} x ${data.itemName || 'item'} added to cart! 🍽️`, false);
                }
                
                const mainButton = document.querySelector(`.add-to-cart-btn[data-id="${itemId}"]`);
                if (mainButton) {
                    const btnText = mainButton.querySelector('.btn-text');
                    const originalMainText = btnText.innerText;
                    
                    mainButton.classList.remove("bg-gradient-to-r", "from-[#ea5a47]", "to-[#c53030]");
                    mainButton.classList.add("bg-green-600");
                    btnText.innerText = "Added ✓";
                    
                    setTimeout(() => {
                        btnText.innerText = originalMainText;
                        mainButton.classList.remove("bg-green-600");
                        mainButton.classList.add("bg-gradient-to-r", "from-[#ea5a47]", "to-[#c53030]");
                    }, 1200);
                }
            }
        })
        .catch(error => {
            console.error('Error details:', error);
            closeQuantityModal(itemId);
            
            if (window.showToast) {
                window.showToast(error.message, true);
            } else {
                alert(error.message);
            }
        })
        .finally(() => {
            modalAddBtn.innerHTML = originalBtnText;
            modalAddBtn.disabled = false;
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('[id^="quantity-modal-"]:not(.hidden)').forEach(modal => {
                const id = modal.id.replace('quantity-modal-', '');
                closeQuantityModal(id);
            });
        }
    });
</script>

<style>
    [id^="quantity-modal-"] {
        transition: opacity 0.3s ease;
    }
    
    [id^="modal-card-"] {
        transition: all 0.3s ease-out;
    }
    
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
    
    button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #ea5a47;
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #c53030;
    }
    
    .group:hover .group-hover\:opacity-100 {
        transition-delay: 0.1s;
    }
</style>