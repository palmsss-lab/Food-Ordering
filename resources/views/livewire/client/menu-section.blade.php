<div wire:poll.20s>

    @if($categories->isEmpty())
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
        <!-- Live indicator -->
        <div class="flex items-center justify-end gap-2 mb-3 text-xs text-gray-400">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
            </span>
            Live updates on
            <span wire:loading class="text-blue-400">refreshing...</span>
        </div>

        <!-- Category Tabs -->
        <div class="relative mb-6">
            {{-- Fade hint on right edge — visible only on mobile to signal more tabs --}}
            <div class="absolute right-0 top-0 bottom-2 w-10 bg-gradient-to-l from-white to-transparent pointer-events-none z-10 sm:hidden"></div>

            <div id="category-tabs-scroll"
                 class="flex gap-2 sm:gap-3 overflow-x-auto pb-2 scrollbar-hide -mx-4 px-4 sm:mx-0 sm:px-0 sm:flex-wrap">
                @foreach($categories as $category)
                    <button
                        wire:click="setCategory({{ $category->id }})"
                        wire:loading.attr="disabled"
                        wire:target="setCategory"
                        {{ $activeCategory === $category->id ? 'data-active-tab' : '' }}
                        class="flex-shrink-0 px-3 py-2 sm:px-5 sm:py-2.5 rounded-full font-semibold transition-all duration-200 text-xs sm:text-sm disabled:opacity-60 disabled:cursor-not-allowed
                            {{ $activeCategory === $category->id
                                ? 'bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white shadow-md'
                                : 'bg-gray-200 text-gray-800 hover:bg-[#ea5a47] hover:text-white' }}">
                        {{ $category->name }}
                        <span class="ml-1 sm:ml-2 px-1.5 py-0.5 text-xs rounded-full
                            {{ $activeCategory === $category->id ? 'bg-white/30 text-white' : 'bg-gray-300 text-gray-700' }}">
                            {{ $category->menu_items_count }}
                        </span>
                    </button>
                @endforeach
            </div>
        </div>

        <script>
            function scrollActiveTabIntoView() {
                const tab = document.querySelector('[data-active-tab]');
                const container = document.getElementById('category-tabs-scroll');
                if (!tab || !container) return;
                const offset = tab.offsetLeft - (container.offsetWidth / 2) + (tab.offsetWidth / 2);
                container.scrollLeft = Math.max(0, offset);
            }
            document.addEventListener('DOMContentLoaded', scrollActiveTabIntoView);
            document.addEventListener('livewire:update', scrollActiveTabIntoView);
        </script>

        {{-- Skeleton loading --}}
        <div wire:loading.block wire:target="setCategory"
             class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-8 mb-6">

            @php
                $skeletonVisibility = [
                    '',                              // card 1 — always visible
                    'hidden sm:block',               // card 2 — sm+
                    'hidden md:block',               // card 3 — md+
                    'hidden lg:block',               // card 4 — lg+
                ];
            @endphp

            @foreach($skeletonVisibility as $visibility)
                <div class="bg-white rounded-3xl shadow-lg overflow-hidden border-2 border-gray-100 animate-pulse {{ $visibility }}">
                    <div class="relative p-6 bg-gray-50">
                        <div class="absolute top-4 left-4 h-6 w-20 rounded-full bg-gray-200"></div>
                        <div class="w-full h-40 rounded-2xl bg-gray-200"></div>
                    </div>
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-3 gap-3">
                            <div class="h-4 bg-gray-200 rounded-full w-2/3"></div>
                            <div class="h-4 bg-gray-200 rounded-full w-1/4"></div>
                        </div>
                        <div class="h-3 bg-gray-200 rounded-full w-1/2 mb-5"></div>
                        <div class="h-12 bg-gray-200 rounded-xl w-full"></div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Menu Items Grid --}}
        <div wire:loading.remove wire:target="setCategory"
             class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-8"
             wire:key="category-{{ $activeCategory }}">
            @forelse($activeItems as $item)
                <div wire:key="item-{{ $item->id }}-{{ $item->stock }}">
                    <x-menu-items-card :item="$item" />
                </div>
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
    @endif

</div>{{-- end wire:poll --}}
