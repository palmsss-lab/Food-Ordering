@extends('admin.layouts.home', ['active' => 'dashboard'])

@section('title', 'Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9]">

        <!-- ============================================= -->
        <!-- MAIN CONTENT - Right Side -->
        <!-- ============================================= -->
        <main class="flex-1 overflow-y-auto">
            <!-- Top Bar -->
            <div class="bg-white/95 backdrop-blur-sm shadow-sm border-b border-gray-200 sticky top-0 z-10">
                <div class="px-8 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800">Dashboard Overview</h2>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</span>
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#ea5a47] to-[#c53030] flex items-center justify-center text-white font-bold text-sm shadow-md">
                            {{ substr(session('user_name'), 0, 1) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="p-8">
                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2 animate-slideDown">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Main Dashboard Card -->
                <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 mb-8">
                    <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                        <div class="w-1 h-8 bg-gradient-to-b from-[#ea5a47] to-[#c53030] rounded-full"></div>
                        <span>Welcome, <span class="text-[#ea5a47]">{{ session('user_name') }}</span></span>
                    </h2>
                    
                    <p class="text-gray-600 mb-8">Manage your restaurant efficiently from this dashboard. Use the sidebar to navigate through different sections.</p>
                </div>

                <!-- Stats Cards Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Menu Items Card -->
                    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Total Menu Items</p>
                                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalMenuItems ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Total Categories Card -->
                    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Total Categories</p>
                                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalCategories ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Total Orders Card -->
                    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Today's Orders</p>
                                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $todayOrders ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Orders Card -->
                    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Pending Orders</p>
                                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $pendingOrders ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Quick Actions Section -->
                <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                    <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                        <div class="w-1 h-6 bg-gradient-to-b from-[#ea5a47] to-[#c53030] rounded-full"></div>
                        <span>Quick Actions</span>
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Add Menu Item -->
                        <a href="{{ route('admin.menu-items.create') }}" 
                           class="group bg-gradient-to-br from-orange-50 to-white rounded-xl p-6 border border-orange-100 hover:shadow-lg transition-all duration-300 hover:scale-105">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">Add Menu Item</h4>
                                    <p class="text-sm text-gray-500">Create a new dish or product</p>
                                </div>
                            </div>
                        </a>

                        <!-- Add Category -->
                        <a href="{{ route('admin.categories.create') }}" 
                           class="group bg-gradient-to-br from-purple-50 to-white rounded-xl p-6 border border-purple-100 hover:shadow-lg transition-all duration-300 hover:scale-105">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">Add Category</h4>
                                    <p class="text-sm text-gray-500">Create a new menu category</p>
                                </div>
                            </div>
                        </a>

                        <!-- View Menu Items -->
                        <a href="{{ route('admin.menu-items.index') }}" 
                           class="group bg-gradient-to-br from-blue-50 to-white rounded-xl p-6 border border-blue-100 hover:shadow-lg transition-all duration-300 hover:scale-105">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">View Menu Items</h4>
                                    <p class="text-sm text-gray-500">Manage your existing items</p>
                                </div>
                            </div>
                        </a>

                        <!-- View Categories -->
                        <a href="{{ route('admin.categories.index') }}" 
                           class="group bg-gradient-to-br from-green-50 to-white rounded-xl p-6 border border-green-100 hover:shadow-lg transition-all duration-300 hover:scale-105">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">View Categories</h4>
                                    <p class="text-sm text-gray-500">Organize your menu</p>
                                </div>
                            </div>
                        </a>

                        {{-- <!-- View Orders -->
                        <a href="{{ route('admin.orders.index') }}" 
                           class="group bg-gradient-to-br from-yellow-50 to-white rounded-xl p-6 border border-yellow-100 hover:shadow-lg transition-all duration-300 hover:scale-105">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center group-hover:bg-yellow-200 transition-colors">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">View Orders</h4>
                                    <p class="text-sm text-gray-500">Manage incoming orders</p>
                                </div>
                            </div>
                        </a> --}}

                        {{-- <!-- View Transactions -->
                        <a href="{{ route('admin.transactions.index') }}" 
                           class="group bg-gradient-to-br from-red-50 to-white rounded-xl p-6 border border-red-100 hover:shadow-lg transition-all duration-300 hover:scale-105">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center group-hover:bg-red-200 transition-colors">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">Transactions</h4>
                                    <p class="text-sm text-gray-500">View sales and reports</p>
                                </div>
                            </div>
                        </a> --}}
                    </div>
                </div>
            </div>
        </main>
</div>

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
    
    /* Custom scrollbar for sidebar */
    .overflow-y-auto::-webkit-scrollbar {
        width: 4px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #ea5a47;
        border-radius: 4px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #c53030;
    }
</style>
@endsection