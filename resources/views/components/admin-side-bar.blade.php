@props(['active' => null])

<aside class="w-90 bg-white/95 backdrop-blur-sm shadow-2xl flex flex-col relative overflow-hidden border-r border-[#ea5a47]/10">
    <!-- Sidebar Decorative Elements -->
    <div class="absolute top-0 left-0 w-32 h-32 bg-gradient-to-br from-[#ea5a47] to-[#c53030] opacity-5 rounded-br-3xl"></div>
    <div class="absolute bottom-0 right-0 w-32 h-32 bg-gradient-to-tl from-[#ea5a47] to-[#c53030] opacity-5 rounded-tl-3xl"></div>
    
    <!-- Restaurant Branding -->
   <div class="p-6 border-b border-gray-200 relative">
    <div class="flex items-center gap-4">
        <div class="flex-shrink-0">
            <img src="{{ asset('images/logo.png') }}" 
                 alt="2Dine-In Logo" 
                 class="w-20 h-20 object-contain">
        </div>
        <div>
            <h1 class="text-2xl font-black text-gray-800">2Dine-In</h1>
            <p class="text-xs text-gray-500">Admin Panel</p>
        </div>
    </div>
</div>
    
    <!-- User Info -->
    <div class="px-6 py-4 bg-[#ea5a47]/5 border-b border-gray-200">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#ea5a47] to-[#c53030] flex items-center justify-center text-white font-bold text-xl shadow-lg">
                {{ substr(session('user_name'), 0, 1) }}
            </div>
            <div>
                <p class="font-semibold text-gray-800">{{ session('user_name') }}</p>
                <p class="text-xs text-gray-500 flex items-center gap-1">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                    Administrator
                </p>
            </div>
        </div>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto py-6 px-4">
        <ul class="space-y-2">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ $active === 'dashboard' ? 'bg-[#ea5a47]/10 text-[#ea5a47]' : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>
            
            <!-- Categories -->
            <li>
                <a href="{{ route('admin.categories.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ $active === 'categories' ? 'bg-[#ea5a47]/10 text-[#ea5a47]' : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                    </svg>
                    <span class="font-medium">Categories</span>
                </a>
            </li>
            <!-- Menus -->
            <li>
                <a href="{{ route('admin.menu-items.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ $active === 'menu-items' ? 'bg-[#ea5a47]/10 text-[#ea5a47]' : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    <span class="font-medium">Menus</span>
                </a>
            </li>
            
            <!-- Orders -->
            <li>
                <a href="{{ route('admin.orders.index', ['tab' => 'pending']) }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ $active === 'orders' ? 'bg-[#ea5a47]/10 text-[#ea5a47]' : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="font-medium">Orders</span>
                    
                    <!-- Show pending count badge -->
                    @php
                        $pendingCount = \App\Models\Order::where('order_status', 'pending')
                            ->whereNull('admin_confirmed_at')
                            ->count();
                    @endphp
                    @if($pendingCount > 0 && $active !== 'orders')
                        <span class="ml-auto bg-[#ea5a47] text-white text-xs px-2 py-1 rounded-full font-semibold animate-pulse">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>
            </li>
            
            <!-- Transactions - Keep "Soon" badge if not yet implemented -->
            <li>
                <a href="{{ route('admin.orders.transactions') ?? '#' }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ $active === 'transactions' ? 'bg-[#ea5a47]/10 text-[#ea5a47]' : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="font-medium">Transactions</span>
                </a>
            </li>
            
            <!-- Sales with gray Soon badge -->
            <li>
                <a href="{{ route('admin.sales.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47] {{ $active === 'sales' ? 'bg-[#ea5a47]/10 text-[#ea5a47]' : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="font-medium">Sales</span>
                </a>
            </li>
            
            <!-- Divider -->
            <li class="border-t border-gray-200 my-4"></li>

            {{-- Admin Profile --}}
            <li>
                <a href="{{ route('admin.profile.show') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ $active === 'profile' ? 'bg-[#ea5a47]/10 text-[#ea5a47]' : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="font-medium">My Profile</span>
                </a>
            </li>
            
            <!-- Edit Profile -->
            <li>
                <a href="{{ route('admin.profile.edit') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ $active === 'edit-profile' ? 'bg-[#ea5a47]/10 text-[#ea5a47]' : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span class="font-medium">Edit Profile</span>
                </a>
            </li>
            
            <!-- Change Password -->
            <li>
                <a href="{{ route('admin.profile.password') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ $active === 'password' ? 'bg-[#ea5a47]/10 text-[#ea5a47]' : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <span class="font-medium">Change Password</span>
                </a>
            </li>
            
            <!-- Logout -->
            <li class="mt-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-gray-700 rounded-xl hover:bg-red-50 hover:text-red-600 transition-all duration-200 group">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="font-medium">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
    
    <!-- Footer Credit -->
    <div class="px-6 py-4 border-t border-gray-200">
        <p class="text-xs text-gray-400 text-center">2Dine-In Admin Panel v1.0</p>
    </div>
</aside>