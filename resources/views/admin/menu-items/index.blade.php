@extends('admin.layouts.home', ['active' => 'menu-items'])

@section('title', 'Menu Items')

@section('content')
<div class="relative min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] overflow-hidden py-8 px-4 sm:px-6 lg:px-8">
    <!-- Decorative Background Elements (keep existing) -->
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

    <div class="relative z-10 max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="absolute inset-0 bg-[#ea5a47] rounded-lg blur-md opacity-30"></div>
                    <div class="relative bg-gradient-to-br from-[#ea5a47] to-[#c53030] p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                    </div>
                </div>
                <h1 class="text-xl sm:text-3xl font-black text-gray-800">
                    Menu <span class="text-[#ea5a47]">Items</span>
                </h1>
            </div>
            <a href="{{ route('admin.menu-items.create') }}" 
               class="flex items-center gap-2 bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-bold px-6 py-3 rounded-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-[1.02]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add New Menu Item</span>
            </a>
        </div>

        <!-- Search and Filter Card -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl p-6 border border-white/20 relative overflow-hidden mb-6">
            <div class="absolute top-0 left-0 w-32 h-32 bg-gradient-to-br from-[#ea5a47] to-[#c53030] opacity-5 rounded-br-3xl"></div>
            <form method="GET" action="{{ route('admin.menu-items.index') }}" id="filter-form" class="relative z-10">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <div class="relative group">
                            <span class="absolute left-3 top-3 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input type="text" 
                                   name="search" 
                                   id="search-input"
                                   class="w-full pl-10 pr-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white focus:ring-0 outline-none transition-all duration-300" 
                                   placeholder="Search by name..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div>
                        <select name="category" 
                                id="category-select"
                                class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white focus:ring-0 outline-none transition-all duration-300">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" 
                                id="filter-submit"
                                class="w-full bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-bold py-3 px-4 rounded-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-[1.02]">
                            Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Archive Toggle -->
        @if($archivedItems->count() > 0)
        <div class="mb-4 flex items-center gap-3">
            <button onclick="toggleArchive()" id="archive-toggle-btn"
                    class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition-all text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1.293 13h11.414L19 8M10 12h4" />
                </svg>
                Archive
                <span class="bg-gray-400 text-white text-xs px-2 py-0.5 rounded-full">{{ $archivedItems->count() }}</span>
            </button>
        </div>

        <!-- Archive Section -->
        <div id="archive-section" class="hidden mb-6">
            <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-3xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1.293 13h11.414L19 8M10 12h4" />
                    </svg>
                    <h3 class="font-bold text-gray-500 text-sm uppercase tracking-wide">Archived Menu Items</h3>
                </div>
                <div class="overflow-auto max-h-72">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs uppercase bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Category</th>
                            <th class="px-6 py-3">Price</th>
                            <th class="px-6 py-3">Archived On</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($archivedItems as $archived)
                        <tr class="hover:bg-gray-100/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-500">{{ $archived->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-400">
                                {{ $archived->category->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-gray-400">
                                ₱{{ number_format($archived->price, 2) }}
                            </td>
                            <td class="px-6 py-4 text-gray-400 text-xs">
                                {{ $archived->deleted_at->format('M d, Y h:i A') }}
                                <div>{{ $archived->deleted_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    {{-- Restore --}}
                                    <form action="{{ route('admin.menu-items.restore', $archived->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="p-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors"
                                                title="Restore">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                        </button>
                                    </form>
                                    {{-- Permanent Delete --}}
                                    <form action="{{ route('admin.menu-items.force-delete', $archived->id) }}" method="POST"
                                          onsubmit="return confirm('Permanently delete \"{{ addslashes($archived->name) }}\"? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-2 bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition-colors"
                                                title="Permanently Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>{{-- end overflow-x-auto --}}
            </div>
        </div>
        @endif

        <!-- Menu Items Table Card -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-[#ea5a47] to-[#c53030] opacity-5 rounded-bl-3xl"></div>
            
            <div class="relative z-10 overflow-x-auto">
                <table class="w-full min-w-[480px] text-sm text-left text-gray-700">
                    <thead class="text-xs uppercase bg-gray-50/80 border-b-2 border-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-4">Image</th>
                            <th scope="col" class="px-6 py-4">Name</th>
                            <th scope="col" class="px-6 py-4 hidden sm:table-cell">Category</th>
                            <th scope="col" class="px-6 py-4">Price</th>
                            <th scope="col" class="px-6 py-4 text-center">Stock</th>
                            <th scope="col" class="px-4 py-4 hidden md:table-cell">Dietary</th>
                            <th scope="col" class="px-4 py-4 hidden md:table-cell">Allergens</th>
                            <th scope="col" class="px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($menuItems as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                @if($item->image_path)
                                    @php
                                        $isExternalUrl = filter_var($item->image_path, FILTER_VALIDATE_URL);
                                    @endphp
                                    
                                    @if($isExternalUrl)
                                        <img src="{{ $item->image_path }}" 
                                             alt="{{ $item->name }}" 
                                             class="w-12 h-12 object-cover rounded-lg border-2 border-gray-200"
                                             loading="lazy">
                                    @else
                                        <img src="{{ Storage::url($item->image_path) }}" 
                                             alt="{{ $item->name }}" 
                                             class="w-12 h-12 object-cover rounded-lg border-2 border-gray-200"
                                             loading="lazy">
                                    @endif
                                @else
                                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center border-2 border-gray-200">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $item->name }}</div>
                                @if($item->description)
                                    <div class="text-xs text-gray-500 mt-1">{{ Str::limit($item->description, 60) }}</div>
                                @endif
                                @if($item->serving_display)
                                    <div class="text-xs text-gray-400 mt-1">Serving: {{ $item->serving_display }}</div>
                                @endif
                             </td>
                            <td class="px-6 py-4 hidden sm:table-cell">
                                <span class="px-3 py-1 bg-[#ea5a47]/10 text-[#ea5a47] text-xs font-medium rounded-full">
                                    {{ $item->category->name }}
                                </span>
                             </td>
                            <td class="px-6 py-4">
                                <span class="text-[#ea5a47] font-bold">{{ $item->formatted_price }}</span>
                             </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $status = $item->stock_status;
                                @endphp
                                <span class="px-3 py-1 text-xs font-medium rounded-full 
                                    @if($status['class'] === 'success') bg-green-100 text-green-700
                                    @elseif($status['class'] === 'warning') bg-yellow-100 text-yellow-700
                                    @elseif($status['class'] === 'danger') bg-red-100 text-red-700
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    {{ $status['label'] }} ({{ $item->stock }})
                                </span>
                             </td>
                            <td class="px-4 py-4 hidden md:table-cell">
                                @if($item->dietary_badges && count($item->dietary_badges) > 0)
                                    <div class="flex flex-wrap gap-1 max-w-[120px]">
                                        @foreach($item->dietary_badges as $badge)
                                            <span class="inline-flex items-center px-1.5 py-1 text-sm rounded-full {{ $badge['color'] }}" title="{{ $badge['name'] }}">
                                                {{ $badge['icon'] }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 hidden md:table-cell">
                                @if($item->allergen_badges && count($item->allergen_badges) > 0)
                                    <div class="flex flex-wrap gap-1 max-w-[120px]">
                                        @foreach($item->allergen_badges as $badge)
                                            <span class="inline-flex items-center px-1.5 py-1 text-sm rounded-full {{ $badge['color'] }}" title="{{ $badge['name'] }}">
                                                {{ $badge['icon'] }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    {{-- Quick stock toggle --}}
                                    <form action="{{ route('admin.menu-items.toggle-stock', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                title="{{ $item->stock > 0 ? 'Mark Out of Stock' : 'Mark In Stock (restores to 50)' }}"
                                                class="p-2 rounded-lg transition-colors duration-200 {{ $item->stock > 0 ? 'bg-green-100 text-green-600 hover:bg-red-100 hover:text-red-600' : 'bg-red-100 text-red-600 hover:bg-green-100 hover:text-green-600' }}">
                                            @if($item->stock > 0)
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>

                                    <a href="{{ route('admin.menu-items.edit', $item->id) }}"
                                       class="edit-item p-2 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 transition-colors duration-200"
                                       title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <button type="button" 
                                            class="delete-item p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors duration-200"
                                            data-id="{{ $item->id }}"
                                            data-name="{{ $item->name }}"
                                            title="Delete">
                                        <svg class="w-5 h-5 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                    <form id="delete-form-{{ $item->id }}" 
                                          action="{{ route('admin.menu-items.destroy', $item->id) }}" 
                                          method="POST" 
                                          class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                             </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </div>
                                    <h5 class="text-xl font-bold text-gray-600 mb-2">No Menu Items Found</h5>
                                    <p class="text-gray-500 mb-4">Get started by adding your first menu item.</p>
                                    <a href="{{ route('admin.menu-items.create') }}" 
                                       class="flex items-center gap-2 bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-bold px-6 py-3 rounded-xl hover:shadow-2xl transition-all duration-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        <span>Add Menu Item</span>
                                    </a>
                                </div>
                             </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($menuItems->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex justify-center">
                    {{ $menuItems->withQueryString()->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" id="modal-overlay"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        
        <div class="relative inline-block align-bottom bg-white/95 backdrop-blur-sm rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/20">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-[#ea5a47] to-[#c53030] opacity-5 rounded-bl-3xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-[#ea5a47] to-[#c53030] opacity-5 rounded-tr-3xl"></div>
            
            <div class="p-6 relative">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-14 w-14 rounded-full bg-red-50 sm:mx-0 sm:h-12 sm:w-12">
                        <svg class="h-7 w-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-xl font-black text-gray-900">Archive <span class="text-[#ea5a47]">Menu Item</span></h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-600 leading-relaxed">
                                Archive <span class="font-semibold text-[#ea5a47]" id="itemName"></span>? It will be hidden from the menu but can be restored anytime from the Archive.
                            </p>
                        </div>    
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-200 flex flex-col sm:flex-row-reverse gap-3">
                <button type="button" id="confirmDelete" 
                        class="w-full inline-flex justify-center items-center gap-2 rounded-xl border border-transparent px-5 py-3 bg-gradient-to-r from-red-600 to-red-700 text-base font-bold text-white shadow-lg hover:shadow-xl transform hover:scale-[1.02] focus:outline-none transition-all duration-300 sm:ml-3 sm:w-auto sm:text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Yes, Archive Item</span>
                </button>
                
                <button type="button" id="cancelDelete" 
                        class="w-full inline-flex justify-center items-center gap-2 rounded-xl border-2 border-gray-200 px-5 py-3 bg-white text-base font-bold text-gray-700 shadow-sm hover:border-[#ea5a47] hover:bg-gray-50 hover:text-[#ea5a47] focus:outline-none transition-all duration-300 sm:mt-0 sm:w-auto sm:text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>Cancel</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Prevent double loading on filter, search, and pagination
let isFiltering = false;
let isPaginating = false;

document.addEventListener('DOMContentLoaded', function() {
    // Get modal elements
    const modal = document.getElementById('deleteModal');
    const overlay = document.getElementById('modal-overlay');
    const itemNameSpan = document.getElementById('itemName');
    const confirmBtn = document.getElementById('confirmDelete');
    const cancelBtn = document.getElementById('cancelDelete');
    let currentDeleteForm = null;
    let currentItemElement = null;

    // Hide modal on page load
    if (modal) modal.classList.add('hidden');

    // Delete button click handlers
    document.querySelectorAll('.delete-item').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const itemId = this.dataset.id;
            const itemName = this.dataset.name;
            
            if (!itemId || !itemName) {
                alert('Error: Missing item information');
                return;
            }
            
            // Set the item name in modal
            itemNameSpan.textContent = `"${itemName}"`;
            
            // Get the delete form
            currentDeleteForm = document.getElementById(`delete-form-${itemId}`);
            currentItemElement = this.closest('tr');
            
            if (!currentDeleteForm) {
                alert('Error: Delete form not found');
                return;
            }
            
            // Show modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    });

    // Confirm archive
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentDeleteForm) {
                // Loading state on button
                confirmBtn.disabled = true;
                confirmBtn.innerHTML = `
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span>Processing...</span>
                `;
                confirmBtn.classList.add('opacity-80', 'cursor-not-allowed');
                isSubmitting = true;

                // Also disable cancel button so modal can't be dismissed mid-submit
                const cancelBtnEl = document.getElementById('cancelDelete');
                if (cancelBtnEl) cancelBtnEl.disabled = true;

                if (window.showLoader) window.showLoader();

                setTimeout(() => {
                    currentDeleteForm.submit();
                }, 300);
            } else {
                alert('Error: No form to submit');
                hideModal();
            }
        });
    }

    let isSubmitting = false;

    // Hide modal function
    function hideModal() {
        if (isSubmitting) return;
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        currentDeleteForm = null;
        currentItemElement = null;
    }

    // Cancel button
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function(e) {
            e.preventDefault();
            hideModal();
        });
    }

    // Click overlay to close
    if (overlay) {
        overlay.addEventListener('click', function(e) {
            e.preventDefault();
            hideModal();
        });
    }

    // Close with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            e.preventDefault();
            hideModal();
        }
    });

    // FIXED: Handle filter form submission - Prevent double loading
    const filterForm = document.getElementById('filter-form');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            // Prevent double submission
            if (isFiltering) {
                e.preventDefault();
                return;
            }
            
            isFiltering = true;
            
            // Show loader
            if (window.showLoader) {
                window.showLoader();
            }
            
            // Disable submit button to prevent multiple clicks
            const submitBtn = document.getElementById('filter-submit');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.7';
                submitBtn.style.cursor = 'not-allowed';
            }
            
            // Form will submit naturally
        });
    }

    // FIXED: Handle pagination links - Prevent double loading
    function setupPaginationLinks() {
        document.querySelectorAll('.pagination a').forEach(link => {
            // Remove existing listener to prevent duplicates
            link.removeEventListener('click', paginationClickHandler);
            link.addEventListener('click', paginationClickHandler);
        });
    }
    
    function paginationClickHandler(e) {
        // Prevent double pagination
        if (isPaginating) {
            e.preventDefault();
            return;
        }
        
        isPaginating = true;
        
        // Show loader
        if (window.showLoader) {
            window.showLoader();
        }
    }
    
    // Initialize pagination links
    setupPaginationLinks();
    
    // Re-initialize pagination links when table content changes (for livewire/turbo)
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                setupPaginationLinks();
            }
        });
    });
    
    // Observe the pagination container
    const paginationContainer = document.querySelector('.pagination')?.parentElement;
    if (paginationContainer) {
        observer.observe(paginationContainer, { childList: true, subtree: true });
    }

    // FIXED: Handle edit links - Show loader
    document.querySelectorAll('.edit-item').forEach(link => {
        link.addEventListener('click', function(e) {
            if (window.showLoader) {
                window.showLoader();
            }
        });
    });

    // FIXED: Handle add new button - Show loader
    const addNewBtn = document.querySelector('a[href*="create"]');
    if (addNewBtn) {
        addNewBtn.addEventListener('click', function(e) {
            if (window.showLoader) {
                window.showLoader();
            }
        });
    }

    // Reset flags when page loads or returns from cache
    window.addEventListener('pageshow', function() {
        isFiltering = false;
        isPaginating = false;
        
        // Re-enable filter button
        const submitBtn = document.getElementById('filter-submit');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
        }
    });
    
    // Optional: Add debounced search to prevent multiple rapid submissions
    let searchTimeout;
    const searchInput = document.getElementById('search-input');
    const categorySelect = document.getElementById('category-select');
    
    function debouncedSubmit() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            if (!isFiltering) {
                filterForm.submit();
            }
        }, 500);
    }
    
    // Optional: Auto-submit on search input change with debounce
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            debouncedSubmit();
        });
    }
    
    // Auto-submit on category change
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            if (!isFiltering) {
                filterForm.submit();
            }
        });
    }

});

function toggleArchive() {
    const section = document.getElementById('archive-section');
    const btn     = document.getElementById('archive-toggle-btn');
    const hidden  = section.classList.toggle('hidden');
    btn.classList.toggle('bg-gray-200', !hidden);
    btn.classList.toggle('ring-2',      !hidden);
    btn.classList.toggle('ring-gray-400', !hidden);
}

</script>

<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
    
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
</style>
@endsection