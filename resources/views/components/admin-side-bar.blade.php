@props(['active' => null])

<!-- Mobile overlay -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden" onclick="closeSidebar()"></div>

<aside id="adminSidebar" class="w-[280px] lg:w-72 xl:w-80 bg-white/95 backdrop-blur-sm shadow-2xl flex flex-col overflow-hidden border-r border-[#ea5a47]/10 fixed inset-y-0 left-0 z-40 -translate-x-full lg:translate-x-0 transition-transform duration-300">
    <!-- Sidebar Decorative Elements -->
    <div class="absolute top-0 left-0 w-32 h-32 bg-gradient-to-br from-[#ea5a47] to-[#c53030] opacity-5 rounded-br-3xl"></div>
    <div class="absolute bottom-0 right-0 w-32 h-32 bg-gradient-to-tl from-[#ea5a47] to-[#c53030] opacity-5 rounded-tl-3xl"></div>

    <!-- Restaurant Branding -->
    <div class="px-4 py-4 border-b border-gray-200 relative">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0">
                <img src="{{ asset('images/logo.png') }}"
                     alt="2Dine-In Logo"
                     class="w-14 h-14 lg:w-16 lg:h-16 object-contain">
            </div>
            <div class="min-w-0">
                <h1 class="text-xl lg:text-2xl font-black text-gray-800 truncate">2Dine-In</h1>
                <p class="text-xs text-gray-500">Admin Panel</p>
            </div>
            <!-- Mobile close button -->
            <button onclick="closeSidebar()"
                    aria-label="Close navigation"
                    class="lg:hidden ml-auto flex-shrink-0 p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- User Info -->
    <div class="px-4 py-3 bg-[#ea5a47]/5 border-b border-gray-200">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 flex-shrink-0 rounded-full bg-gradient-to-br from-[#ea5a47] to-[#c53030] flex items-center justify-center text-white font-bold text-lg shadow-lg">
                {{ substr(session('user_name'), 0, 1) }}
            </div>
            <div class="min-w-0">
                <p class="font-semibold text-gray-800 truncate">{{ session('user_name') }}</p>
                <p class="text-xs text-gray-500 flex items-center gap-1">
                    <span class="w-2 h-2 flex-shrink-0 bg-green-500 rounded-full"></span>
                    Administrator
                </p>
            </div>
        </div>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto py-4 px-3">
        <ul class="space-y-1">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('admin.dashboard') }}"
                   onclick="if(window.innerWidth < 1024) closeSidebar()"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ $active === 'dashboard' ? 'bg-[#ea5a47]/10 text-[#ea5a47]' : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="font-medium truncate">Dashboard</span>
                </a>
            </li>

            <!-- Categories -->
            <li>
                <a href="{{ route('admin.categories.index') }}"
                   onclick="if(window.innerWidth < 1024) closeSidebar()"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ $active === 'categories' ? 'bg-[#ea5a47]/10 text-[#ea5a47]' : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                    </svg>
                    <span class="font-medium truncate">Categories</span>
                </a>
            </li>

            <!-- Menus -->
            <li>
                <a href="{{ route('admin.menu-items.index') }}"
                   onclick="if(window.innerWidth < 1024) closeSidebar()"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ $active === 'menu-items' ? 'bg-[#ea5a47]/10 text-[#ea5a47]' : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    <span class="font-medium truncate">Menus</span>
                </a>
            </li>

            <!-- Orders -->
            <li>
                <a href="{{ route('admin.orders.index', ['tab' => 'pending']) }}"
                   onclick="if(window.innerWidth < 1024) closeSidebar()"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ $active === 'orders' ? 'bg-[#ea5a47]/10 text-[#ea5a47]' : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="font-medium truncate">Orders</span>

                    <!-- Show pending count badge -->
                    @php
                        $pendingCount = \App\Models\Order::where('order_status', 'pending')
                            ->whereNull('admin_confirmed_at')
                            ->count();
                    @endphp
                    @if($pendingCount > 0 && $active !== 'orders')
                        <span class="ml-auto flex-shrink-0 bg-[#ea5a47] text-white text-xs px-2 py-0.5 rounded-full font-semibold animate-pulse">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>
            </li>

            <!-- Transactions -->
            <li>
                <a href="{{ route('admin.orders.transactions') }}"
                   onclick="if(window.innerWidth < 1024) closeSidebar()"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 {{ $active === 'transactions' ? 'bg-[#ea5a47]/10 text-[#ea5a47]' : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="font-medium truncate">Transactions</span>
                </a>
            </li>

            <!-- Users -->
            <li>
                <a href="{{ route('admin.users') }}"
                   onclick="if(window.innerWidth < 1024) closeSidebar()"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 {{ $active === 'users' ? 'bg-[#ea5a47]/10 text-[#ea5a47]' : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="font-medium truncate">Users</span>
                </a>
            </li>

            <!-- Vouchers -->
            <li>
                <a href="{{ route('admin.vouchers.index') }}"
                   onclick="if(window.innerWidth < 1024) closeSidebar()"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ $active === 'vouchers' ? 'bg-[#ea5a47]/10 text-[#ea5a47]' : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span class="font-medium truncate">Vouchers</span>
                </a>
            </li>

            <!-- Promotions -->
            <li>
                <a href="{{ route('admin.promotions.index') }}"
                   onclick="if(window.innerWidth < 1024) closeSidebar()"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ $active === 'promotions' ? 'bg-[#ea5a47]/10 text-[#ea5a47]' : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    <span class="font-medium truncate">Promotions</span>
                </a>
            </li>

            <!-- Sales -->
            <li>
                <a href="{{ route('admin.sales.index') }}"
                   onclick="if(window.innerWidth < 1024) closeSidebar()"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ $active === 'sales' ? 'bg-[#ea5a47]/10 text-[#ea5a47]' : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="font-medium truncate">Sales</span>
                </a>
            </li>

            <!-- Divider -->
            <li class="border-t border-gray-200 my-2 pt-1"></li>

            {{-- Profile Collapsible Group --}}
            @php
                $profileActive = in_array($active, ['profile', 'edit-profile', 'password']);
            @endphp
            <li>
                <button type="button"
                        onclick="toggleProfileMenu()"
                        id="profileMenuToggle"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 {{ $profileActive ? 'bg-[#ea5a47]/10 text-[#ea5a47]' : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="font-medium flex-1 text-left truncate">Account</span>
                    <svg id="profileMenuChevron" class="w-4 h-4 flex-shrink-0 transition-transform duration-200 {{ $profileActive ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <ul id="profileSubMenu" class="{{ $profileActive ? '' : 'hidden' }} mt-1 ml-3 space-y-1 border-l-2 border-[#ea5a47]/20 pl-3">
                    <li>
                        <a href="{{ route('admin.profile.show') }}"
                           onclick="if(window.innerWidth < 1024) closeSidebar()"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg transition-all duration-200 text-sm {{ $active === 'profile' ? 'bg-[#ea5a47]/10 text-[#ea5a47] font-semibold' : 'text-gray-600 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="truncate">My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.profile.edit') }}"
                           onclick="if(window.innerWidth < 1024) closeSidebar()"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg transition-all duration-200 text-sm {{ $active === 'edit-profile' ? 'bg-[#ea5a47]/10 text-[#ea5a47] font-semibold' : 'text-gray-600 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span class="truncate">Edit Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.profile.password') }}"
                           onclick="if(window.innerWidth < 1024) closeSidebar()"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg transition-all duration-200 text-sm {{ $active === 'password' ? 'bg-[#ea5a47]/10 text-[#ea5a47] font-semibold' : 'text-gray-600 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span class="truncate">Change Password</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Logout -->
            <li class="mt-2">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-gray-700 rounded-xl hover:bg-red-50 hover:text-red-600 transition-all duration-200 group">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="font-medium truncate">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <!-- Footer Credit -->
    <div class="px-4 py-3 border-t border-gray-200">
        <p class="text-xs text-gray-400 text-center">2Dine-In Admin Panel v1.0</p>
    </div>
</aside>

<script>
function toggleProfileMenu() {
    const menu = document.getElementById('profileSubMenu');
    const chevron = document.getElementById('profileMenuChevron');
    menu.classList.toggle('hidden');
    chevron.classList.toggle('rotate-180');
}

function openSidebar() {
    document.getElementById('adminSidebar').classList.remove('-translate-x-full');
    document.getElementById('sidebarOverlay').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeSidebar() {
    document.getElementById('adminSidebar').classList.add('-translate-x-full');
    document.getElementById('sidebarOverlay').classList.add('hidden');
    document.body.style.overflow = '';
}
</script>