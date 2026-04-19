@extends('admin.layouts.home', ['active' => 'add-menu'])

@section('title', 'Add Menu')

@section('content')
<div class="relative min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] py-8 px-4 sm:px-6 lg:py-12">
    <!-- Decorative Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-20 -right-20 w-96 h-96 bg-[#ea5a47] opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-[#c53030] opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-to-r from-[#ea5a47]/5 to-[#c53030]/5 rounded-full blur-3xl"></div>
        <div class="absolute top-20 left-20 text-7xl opacity-10 transform rotate-12">🍽️</div>
        <div class="absolute bottom-20 right-20 text-7xl opacity-10 transform -rotate-12">🥘</div>
        <div class="absolute top-40 right-40 text-6xl opacity-10">🍲</div>
        <div class="absolute bottom-40 left-40 text-6xl opacity-10">🥗</div>
        <div class="absolute top-60 left-60 text-5xl opacity-10">🍜</div>
        <div class="absolute bottom-60 right-60 text-5xl opacity-10">🍛</div>
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, #ea5a47 1px, transparent 0); background-size: 40px 40px; opacity: 0.02;"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="absolute inset-0 bg-[#ea5a47] rounded-lg blur-md opacity-30"></div>
                    <div class="relative bg-gradient-to-br from-[#ea5a47] to-[#c53030] p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                </div>
                <h1 class="text-xl sm:text-3xl font-black text-gray-800">
                    Add New <span class="text-[#ea5a47]">Menu Item</span>
                </h1>
            </div>
            <a href="{{ route('admin.menu-items.index') }}" 
               id="back-to-list"
               class="flex items-center gap-2 bg-white border-2 border-gray-200 text-gray-700 font-medium px-4 py-2 rounded-xl hover:border-[#ea5a47] hover:bg-gray-50 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Back to List</span>
            </a>
        </div>

        <!-- Main Card with Scrollable Content -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-32 h-32 bg-gradient-to-br from-[#ea5a47] to-[#c53030] opacity-5 rounded-br-3xl"></div>
            <div class="absolute bottom-0 right-0 w-32 h-32 bg-gradient-to-tl from-[#ea5a47] to-[#c53030] opacity-5 rounded-tl-3xl"></div>
            
            <!-- SCROLLABLE CONTAINER -->
            <div class="relative z-10 max-h-[calc(100vh-180px)] overflow-y-auto px-4 sm:px-6 md:px-8 py-4 sm:py-6">
                <form id="create-menu-form" action="{{ route('admin.menu-items.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Error Messages - Will be shown via toast, but keep for fallback -->
                    @if($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 rounded-lg mb-6 overflow-hidden animate-slideDown sticky top-0 z-20">
                            <div class="px-4 py-3">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="font-medium text-red-700">Please fix the following errors:</span>
                                </div>
                                <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <!-- Category and Name -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="group">
                            <label class="block text-gray-700 font-medium mb-2 flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                </svg>
                                Category <span class="text-[#ea5a47]">*</span>
                            </label>
                            <select class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white focus:ring-0 outline-none transition-all duration-300 group-hover:border-[#ea5a47]/50 @error('categories_id') border-red-500 @enderror" 
                                    id="categories_id" name="categories_id" required>
                                <option value="" class="text-gray-400">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('categories_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categories_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="group">
                            <label class="block text-gray-700 font-medium mb-2 flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Item Name <span class="text-[#ea5a47]">*</span>
                            </label>
                            <input type="text" 
                                class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white focus:ring-0 outline-none transition-all duration-300 group-hover:border-[#ea5a47]/50 @error('name') border-red-500 @enderror" 
                                id="name" name="name" value="{{ old('name') }}" 
                                placeholder="e.g., Beef Salpicao" required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="group">
                        <label class="block text-gray-700 font-medium mb-2 flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                            Description
                        </label>
                        <textarea class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white focus:ring-0 outline-none transition-all duration-300 group-hover:border-[#ea5a47]/50 @error('description') border-red-500 @enderror" 
                                id="description" name="description" rows="4" 
                                placeholder="Describe the dish...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price, Stock, and Serving Size -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="group">
                            <label class="block text-gray-700 font-medium mb-2 flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Price <span class="text-[#ea5a47]">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500 font-medium">₱</span>
                                <input type="number" step="0.01" min="0" 
                                    class="w-full pl-8 pr-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white focus:ring-0 outline-none transition-all duration-300 group-hover:border-[#ea5a47]/50 @error('price') border-red-500 @enderror" 
                                    id="price" name="price" value="{{ old('price') }}" 
                                    placeholder="0.00" required>
                            </div>
                            @error('price')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="group">
                            <label class="block text-gray-700 font-medium mb-2 flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                Stock <span class="text-[#ea5a47]">*</span>
                            </label>
                            <input type="number" min="0" 
                                class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white focus:ring-0 outline-none transition-all duration-300 group-hover:border-[#ea5a47]/50 @error('stock') border-red-500 @enderror" 
                                id="stock" name="stock" value="{{ old('stock', 0) }}" 
                                placeholder="0" required>
                            @error('stock')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="group">
                            <label class="block text-gray-700 font-medium mb-2 flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Serving Size
                            </label>
                            <input type="text" 
                                class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white focus:ring-0 outline-none transition-all duration-300 group-hover:border-[#ea5a47]/50 @error('serving_size') border-red-500 @enderror"
                                id="serving_size" 
                                name="serving_size" 
                                value="{{ old('serving_size') }}" 
                                placeholder="e.g., Good for 2-3 people, Serves 4, Family size, 200g per serving">
                            <div class="mt-1">
                                <p class="text-xs text-gray-500 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Examples: "Good for 2 people", "Serves 4-6", "Family size", "200g per serving"
                                </p>
                            </div>
                            @error('serving_size')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Dietary Options -->
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                        <label class="block text-gray-700 font-medium mb-3 flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                            Dietary Options
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_vegetarian" value="1" {{ old('is_vegetarian') ? 'checked' : '' }}
                                       class="w-4 h-4 text-[#ea5a47] rounded focus:ring-[#ea5a47]">
                                <span class="text-sm">🌱 Vegetarian</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_vegan" value="1" {{ old('is_vegan') ? 'checked' : '' }}
                                       class="w-4 h-4 text-[#ea5a47] rounded focus:ring-[#ea5a47]">
                                <span class="text-sm">🌿 Vegan</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_gluten_free" value="1" {{ old('is_gluten_free') ? 'checked' : '' }}
                                       class="w-4 h-4 text-[#ea5a47] rounded focus:ring-[#ea5a47]">
                                <span class="text-sm">🚫 Gluten-Free</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_nut_free" value="1" {{ old('is_nut_free') ? 'checked' : '' }}
                                       class="w-4 h-4 text-[#ea5a47] rounded focus:ring-[#ea5a47]">
                                <span class="text-sm">🥜 Nut-Free</span>
                            </label>
                        </div>
                    </div>

                    <!-- Allergens -->
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                        <label class="block text-gray-700 font-medium mb-3 flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Allergens
                        </label>
                        <p class="text-xs text-gray-500 mb-3">Select any allergens present in this dish</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @php
                                $allergens = [
                                    'gluten' => ['label' => '🌾 Gluten', 'desc' => 'Wheat, barley, rye'],
                                    'dairy' => ['label' => '🥛 Dairy', 'desc' => 'Milk, cheese, butter'],
                                    'eggs' => ['label' => '🥚 Eggs', 'desc' => 'Eggs'],
                                    'soy' => ['label' => '🫘 Soy', 'desc' => 'Soy products'],
                                    'nuts' => ['label' => '🥜 Nuts', 'desc' => 'Tree nuts, peanuts'],
                                    'shellfish' => ['label' => '🦐 Shellfish', 'desc' => 'Shrimp, crab, lobster'],
                                    'fish' => ['label' => '🐟 Fish', 'desc' => 'Fish products'],
                                    'sesame' => ['label' => '🌿 Sesame', 'desc' => 'Sesame seeds/oil'],
                                ];
                                $selectedAllergens = old('allergens', []);
                            @endphp
                            @foreach($allergens as $key => $allergen)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" name="allergens[]" value="{{ $key }}" 
                                           {{ in_array($key, $selectedAllergens) ? 'checked' : '' }}
                                           class="w-4 h-4 text-[#ea5a47] rounded focus:ring-[#ea5a47]">
                                    <span class="text-sm group-hover:text-gray-900 transition-colors">
                                        {{ $allergen['label'] }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        
                        <!-- Allergen Notes -->
                        <div class="mt-4">
                            <label class="block text-gray-700 font-medium mb-2 text-sm">Allergen Notes (Optional)</label>
                            <textarea name="allergen_notes" rows="2" 
                                      class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white outline-none transition-all duration-300"
                                      placeholder="e.g., May contain traces of nuts, Prepared in a kitchen that handles gluten...">{{ old('allergen_notes') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Additional information about allergens (cross-contamination, etc.)</p>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="group">
                        <label class="block text-gray-700 font-medium mb-2 flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Item Image
                        </label>
                        
                        <!-- Image Preview Container -->
                        <div class="mb-3" id="imagePreviewContainer" style="display: none;">
                            <div class="relative inline-block">
                                <img id="imagePreview" src="#" alt="Preview" 
                                    class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200">
                                <button type="button" 
                                        onclick="clearImage()"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- File Input -->
                        <div class="relative">
                            <input type="file" 
                                name="image" 
                                id="imageInput"
                                accept="image/jpeg,image/png,image/jpg,image/gif"
                                class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white focus:ring-0 outline-none transition-all duration-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#ea5a47] file:text-white hover:file:bg-[#c53030] @error('image') border-red-500 @enderror">
                        </div>
                        
                        @error('image')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Accepted formats: JPG, PNG, GIF (Max: 2MB)
                        </p>
                    </div>

                    <!-- Form Actions (Sticky at bottom) -->
                    <div class="sticky bottom-0 bg-white/95 backdrop-blur-sm pt-4 pb-2 -mx-8 px-8 border-t border-gray-200 mt-6">
                        <div class="flex gap-4">
                            <button type="submit"
                                    id="submit-btn"
                                    data-loading-text="Adding Menu Item..."
                                    class="flex items-center justify-center gap-2 bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-bold py-3 px-8 rounded-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-[1.02] hover:from-[#c53030] hover:to-[#ea5a47]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Create Menu Item</span>
                            </button>
                            <a href="{{ route('admin.menu-items.index') }}" 
                               id="cancel-btn"
                               class="flex items-center justify-center gap-2 bg-white border-2 border-gray-200 text-gray-700 font-bold py-3 px-8 rounded-xl hover:border-[#ea5a47] hover:bg-gray-50 transition-all duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span>Cancel</span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Prevent double loading and ensure proper navigation
let isSubmitting = false;

document.addEventListener('DOMContentLoaded', function() {
    // Handle back button navigation
    const backButton = document.getElementById('back-to-list');
    const cancelButton = document.getElementById('cancel-btn');
    
    // Function to handle navigation with loader
    function handleNavigation(e) {
        // Don't show loader if already navigating
        if (window.isPageTransitioning) {
            e.preventDefault();
            return;
        }
        
        // Show loader
        if (window.showLoader) {
            window.showLoader();
        }
    }
    
    // Add click handlers for navigation links
    if (backButton) {
        backButton.addEventListener('click', handleNavigation);
    }
    
    if (cancelButton) {
        cancelButton.addEventListener('click', handleNavigation);
    }
    
    // Handle form submission
    const createForm = document.getElementById('create-menu-form');
    const submitBtn  = document.getElementById('submit-btn');

    const _spinner = `<svg class="inline-block w-4 h-4 animate-spin mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
    </svg>`;

    if (createForm) {
        createForm.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return;
            }
            isSubmitting = true;

            if (submitBtn && !submitBtn.disabled) {
                submitBtn.style.minWidth = submitBtn.offsetWidth + 'px';
                submitBtn.disabled = true;
                submitBtn.setAttribute('data-original-html', submitBtn.innerHTML);
                submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
                const loadingText = submitBtn.getAttribute('data-loading-text') || 'Processing...';
                submitBtn.innerHTML = `${_spinner}<span>${loadingText}</span>`;
            }
        });
    }

    // Reset button if page is restored from cache or errors redirect back
    window.addEventListener('pageshow', function(event) {
        isSubmitting = false;
        if (submitBtn) {
            const originalHtml = submitBtn.getAttribute('data-original-html');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-80', 'cursor-not-allowed');
            submitBtn.style.minWidth = '';
            if (originalHtml) {
                submitBtn.innerHTML = originalHtml;
                submitBtn.removeAttribute('data-original-html');
            }
        }
    });
});

// Image preview functionality
const imageInput = document.getElementById('imageInput');
const imagePreview = document.getElementById('imagePreview');
const previewContainer = document.getElementById('imagePreviewContainer');

if (imageInput) {
    imageInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                if (window.showToast) {
                    window.showToast('File is too large. Maximum size is 2MB.', true);
                } else {
                    alert('File is too large. Maximum size is 2MB.');
                }
                this.value = '';
                clearImage();
                return;
            }
            
            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                if (window.showToast) {
                    window.showToast('Invalid file type. Please upload JPG, PNG, or GIF.', true);
                } else {
                    alert('Invalid file type. Please upload JPG, PNG, or GIF.');
                }
                this.value = '';
                clearImage();
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            clearImage();
        }
    });
}

function clearImage() {
    const imageInput = document.getElementById('imageInput');
    const previewContainer = document.getElementById('imagePreviewContainer');
    const imagePreview = document.getElementById('imagePreview');
    
    if (imageInput) imageInput.value = '';
    if (previewContainer) previewContainer.style.display = 'none';
    if (imagePreview) imagePreview.src = '#';
}
</script>

<style>
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-slideDown {
        animation: slideDown 0.3s ease-out;
    }
    
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
</style>
@endsection