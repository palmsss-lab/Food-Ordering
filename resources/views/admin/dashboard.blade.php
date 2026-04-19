@extends('admin.layouts.home', ['active' => 'dashboard'])

@section('title', 'Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] p-4 sm:p-6 lg:p-8 overflow-y-auto" style="max-height:100vh;">

    {{-- Top bar --}}
    <div class="flex flex-wrap justify-between items-center gap-3 mb-8">
        <div>
            <h2 class="text-xl sm:text-3xl font-black text-gray-800">
                Welcome back, <span class="text-[#ea5a47]">{{ session('user_name') }}</span>
            </h2>
            <p class="text-sm text-gray-500 mt-1">{{ now()->format('l, F j, Y') }}</p>
        </div>
        <a href="{{ route('admin.orders.index', ['tab' => 'pending']) }}"
           title="View live pending orders"
           class="flex items-center gap-2 text-sm text-gray-500 hover:text-[#ea5a47] transition-colors">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
            </span>
            Live Orders
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Pending Orders Alert --}}
    <a id="pending-alert"
       href="{{ route('admin.orders.index', ['tab' => 'pending']) }}"
       class="flex items-center gap-4 bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white rounded-2xl p-5 mb-6 shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-[1.01] {{ $pendingOrders > 0 ? '' : 'hidden' }}">
        <div class="flex-shrink-0 w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
        </div>
        <div class="flex-1">
            <p class="font-black text-2xl"><span id="pending-count">{{ $pendingOrders }}</span> Order<span id="pending-plural">{{ $pendingOrders > 1 ? 's' : '' }}</span> Waiting</p>
            <p class="text-white/80 text-sm mt-0.5">Tap to review and confirm pending orders →</p>
        </div>
        <span class="relative flex h-4 w-4">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
            <span class="relative inline-flex rounded-full h-4 w-4 bg-white"></span>
        </span>
    </a>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

        {{-- Today's Revenue --}}
        <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Today's Revenue</p>
                    <p class="text-3xl font-black text-gray-800 mt-1">₱<span id="stat-revenue">{{ number_format($todayRevenue, 2) }}</span></p>
                    <p class="text-xs text-gray-400 mt-1">Net after refunds</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Today's Orders --}}
        <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Today's Orders</p>
                    <p class="text-3xl font-black text-gray-800 mt-1"><span id="stat-today-orders">{{ $todayOrders }}</span></p>
                    <p id="stat-preparing-label" class="text-xs mt-1 {{ $preparingOrders > 0 ? 'text-purple-500' : 'text-gray-400' }}">
                        {{ $preparingOrders > 0 ? $preparingOrders . ' in kitchen now' : 'Total placed today' }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Menu Items --}}
        <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Menu Items</p>
                    <p class="text-3xl font-black text-gray-800 mt-1">{{ $totalMenuItems }}</p>
                    @if($outOfStockItems > 0 || $lowStockItems > 0)
                        <p class="text-xs mt-1">
                            @if($outOfStockItems > 0)
                                <span class="text-red-500">{{ $outOfStockItems }} out of stock</span>
                            @endif
                            @if($outOfStockItems > 0 && $lowStockItems > 0)
                                <span class="text-gray-300 mx-1">·</span>
                            @endif
                            @if($lowStockItems > 0)
                                <span class="text-yellow-500">{{ $lowStockItems }} low</span>
                            @endif
                        </p>
                    @else
                        <p class="text-xs text-gray-400 mt-1">across {{ $totalCategories }} categories</p>
                    @endif
                </div>
                <a href="{{ route('admin.menu-items.index') }}"
                   class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center flex-shrink-0 hover:bg-orange-200 transition-colors">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Categories --}}
        <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Categories</p>
                    <p class="text-3xl font-black text-gray-800 mt-1">{{ $totalCategories }}</p>
                    <p class="text-xs text-gray-400 mt-1">Menu categories</p>
                </div>
                <a href="{{ route('admin.categories.index') }}"
                   class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0 hover:bg-purple-200 transition-colors">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </a>
            </div>
        </div>

    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <div class="w-1 h-5 bg-gradient-to-b from-[#ea5a47] to-[#c53030] rounded-full"></div>
            Quick Actions
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.menu-items.create') }}"
               class="group flex flex-col items-center gap-2 p-4 bg-orange-50 rounded-xl border border-orange-100 hover:shadow-md hover:scale-105 transition-all duration-200 text-center">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700">Add Menu Item</span>
            </a>
            <a href="{{ route('admin.categories.create') }}"
               class="group flex flex-col items-center gap-2 p-4 bg-purple-50 rounded-xl border border-purple-100 hover:shadow-md hover:scale-105 transition-all duration-200 text-center">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700">Add Category</span>
            </a>
            <a href="{{ route('admin.orders.index', ['tab' => 'preparing']) }}"
               class="group flex flex-col items-center gap-2 p-4 bg-indigo-50 rounded-xl border border-indigo-100 hover:shadow-md hover:scale-105 transition-all duration-200 text-center">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700">Kitchen View</span>
            </a>
            <a href="{{ route('admin.sales.index') }}"
               class="group flex flex-col items-center gap-2 p-4 bg-green-50 rounded-xl border border-green-100 hover:shadow-md hover:scale-105 transition-all duration-200 text-center">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700">Sales Report</span>
            </a>
        </div>
    </div>

</div>

<script>
(function () {
    function poll() {
        fetch('{{ route('admin.dashboard.stats') }}', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'Accept': 'application/json'
            }
        })
        .then(r => r.ok ? r.json() : null)
        .then(data => {
            if (!data) return;

            // Pending orders alert
            const alert    = document.getElementById('pending-alert');
            const countEl  = document.getElementById('pending-count');
            const pluralEl = document.getElementById('pending-plural');
            if (alert && countEl) {
                const n = data.pending_orders;
                countEl.textContent  = n;
                pluralEl.textContent = n !== 1 ? 's' : '';
                alert.classList.toggle('hidden', n === 0);
            }

            // Today's orders + kitchen label
            const todayEl   = document.getElementById('stat-today-orders');
            const prepLabel = document.getElementById('stat-preparing-label');
            if (todayEl) todayEl.textContent = data.today_orders;
            if (prepLabel) {
                const p = data.preparing_orders;
                prepLabel.textContent = p > 0 ? p + ' in kitchen now' : 'Total placed today';
                prepLabel.className   = 'text-xs mt-1 ' + (p > 0 ? 'text-purple-500' : 'text-gray-400');
            }

            // Today's revenue
            const revEl = document.getElementById('stat-revenue');
            if (revEl) revEl.textContent = parseFloat(data.today_revenue).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        })
        .catch(() => {});
    }

    setInterval(poll, 15000);
})();
</script>
@endsection
