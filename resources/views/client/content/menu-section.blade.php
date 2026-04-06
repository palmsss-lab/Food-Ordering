@extends('client.layouts.home')

@section('title', 'Menu')

@section('content')
<div class="max-w-screen-xl mx-auto px-4 py-12 mt-20">
    <!-- Section Title -->
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Our <span class="text-[#ea5a47]">Menu</span></h2>

    @if($categories->isEmpty())
        <!-- No Categories Found -->
        <div class="text-center py-16 bg-[#fdf7f2] rounded-3xl shadow-xl">
            <div class="w-24 h-24 bg-[#ea5a47]/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-12 h-12 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-600 mb-2">No Categories Found</h3>
            <p class="text-gray-500">Please check back later for our delicious menu items.</p>
        </div>
    @else
        <!-- Categories Tabs -->
        <div x-data="{ activeCategory: {{ $categories->first()->id }} }" class="mb-8">
            <!-- Category Buttons -->
            <div class="flex flex-wrap gap-3 mb-6">
                @foreach($categories as $category)
                    <button
                        @click="activeCategory = {{ $category->id }}"
                        :class="activeCategory === {{ $category->id }} 
                            ? 'bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white shadow-md' 
                            : 'bg-gray-200 text-gray-800 hover:bg-[#ea5a47] hover:text-white'"
                        class="px-5 py-2.5 rounded-full font-semibold transition-all duration-200 text-sm"
                    >
                        {{ $category->name }}
                        <span class="ml-2 px-2 py-0.5 text-xs rounded-full" 
                              :class="activeCategory === {{ $category->id }} 
                                  ? 'bg-white/30 text-white' 
                                  : 'bg-gray-300 text-gray-700'">
                            {{ $category->menu_items_count }}
                        </span>
                    </button>
                @endforeach
            </div>

            <!-- Menu Items -->
            <div class="mt-8">
                @foreach($categories as $category)
                    <div 
                        x-show="activeCategory === {{ $category->id }}"
                        x-cloak
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"
                    >
                        @forelse($category->menuItems as $item)
                            <x-menu-items-card :item="$item" />
                        @empty
                            <div class="col-span-full text-center py-16 bg-[#fdf7f2] rounded-2xl">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <p class="text-lg text-gray-500 font-medium">No items available in this category.</p>
                                <p class="text-sm text-gray-400 mt-1">Please check back soon for new dishes!</p>
                            </div>
                        @endforelse
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<!-- Add Alpine.js if not already in layout -->
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush

<style>
    [x-cloak] { 
        display: none !important; 
    }
    
    /* Smooth transitions */
    .grid {
        transition: all 0.3s ease;
    }
</style>
@endsection