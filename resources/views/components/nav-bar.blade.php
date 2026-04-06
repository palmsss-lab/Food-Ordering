<nav class="bg-white/95 backdrop-blur-md fixed w-full z-20 top-0 start-0 border-b border-[#ea5a47]/10 shadow-lg">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-2">
        <!-- Logo -->
        <div class="flex items-center space-x-3 rtl:space-x-reverse group cursor-default">
            <div class="relative">
                <img src="{{ asset('images/logo.png') }}" 
                    alt="2Dine-In Logo" 
                    class="w-20 h-20 object-contain">
            </div>
            <span class="self-center text-2xl font-black text-gray-800 group-hover:text-[#ea5a47] transition-colors">
                2Dine-In
            </span>
        </div>

        <!-- Mobile Menu Button -->
        <button data-collapse-toggle="navbar-dropdown" type="button" 
            class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-600 rounded-xl 
                   md:hidden hover:bg-[#ea5a47]/10 hover:text-[#ea5a47] 
                   focus:outline-none focus:ring-2 focus:ring-[#ea5a47]/30 
                   transition-all duration-200" 
            aria-controls="navbar-dropdown" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14"/>
            </svg>
        </button>

        <!-- Navigation Menu -->
        <div class="hidden w-full md:block md:w-auto" id="navbar-dropdown">
            <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-200 rounded-2xl 
                       bg-white md:flex-row md:mt-0 md:border-0 md:bg-transparent 
                        md:space-x-3 lg:space-x-4 xl:space-x-6 rtl:space-x-reverse shadow-lg md:shadow-none">
                
            <!-- HOME -->
            <li>
                <a href="{{ route('client.home') }}" 
                class="flex w-full items-center gap-2 text-[17px] py-2.5 px-5 rounded-xl transition-all duration-200
                        {{ request()->routeIs('client.home') 
                            ? 'bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-semibold shadow-md' 
                            : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Home</span>
                </a>
            </li>


                <!-- MENU with tooltip -->
                <li class="relative group">
                    <a href="{{ route('client.menu') }}" 
                    class="flex items-center gap-2 text-[17px] py-2.5 px-5 rounded-xl transition-all duration-200
                            {{ request()->routeIs('client.menu') 
                                ? 'bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-semibold shadow-md' 
                                : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        <span>Menu</span>
                    </a>
                    <!-- Tooltip -->
                    <div class="absolute -bottom-10 left-1/2 transform -translate-x-1/2 
                                bg-gray-800 text-white text-xs py-1 px-3 rounded-lg 
                                opacity-0 group-hover:opacity-100 transition-opacity duration-200
                                whitespace-nowrap pointer-events-none z-50 shadow-lg">
                        Browse our delicious menu
                        <div class="absolute -top-1 left-1/2 transform -translate-x-1/2 
                                    w-2 h-2 bg-gray-800 rotate-45"></div>
                    </div>
                </li>

                <!-- ABOUT with tooltip -->
                <li class="relative group">
                    <a href="{{ route('client.about') }}" 
                    class="flex items-center gap-2 text-[17px] py-2.5 px-5 rounded-xl transition-all duration-200
                            {{ request()->routeIs('client.about') 
                                ? 'bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-semibold shadow-md' 
                                : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        <span>About</span>
                    </a>
                    <!-- Tooltip -->
                    <div class="absolute -bottom-10 left-1/2 transform -translate-x-1/2 
                                bg-gray-800 text-white text-xs py-1 px-3 rounded-lg 
                                opacity-0 group-hover:opacity-100 transition-opacity duration-200
                                whitespace-nowrap pointer-events-none z-50 shadow-lg">
                        Learn more about us
                        <div class="absolute -top-1 left-1/2 transform -translate-x-1/2 
                                    w-2 h-2 bg-gray-800 rotate-45"></div>
                    </div>
                </li>

                <!-- MY ORDERS NAV ITEM -->
                <li class="flex items-center">
                    <a href="{{ route('client.orders.index') }}" 
                    class="relative group outline-none block p-2.5 rounded-xl transition-all duration-300
                            {{ request()->routeIs('client.orders.index') || request()->routeIs('client.orders.show')
                                ? 'bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-semibold shadow-md' 
                                : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                        
                        <!-- Orders Icon and Text Container -->
                        <div class="flex items-center gap-2">
                            <!-- Orders Icon -->
                            <svg class="w-6 h-6 transition-all duration-300" 
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            
                            <!-- Text -->
                            <span class="text-[17px]">My Orders</span>
                            
                            <!-- Orders Badge - shows total pending orders -->
                            @if(isset($userPendingOrders) && $userPendingOrders > 0)
                                <span id="orders-count-badge" 
                                    class="min-w-[22px] h-[22px] px-1.5 
                                            bg-gradient-to-r from-[#ea5a47] to-[#c53030] 
                                            text-white text-xs font-bold rounded-full 
                                            flex items-center justify-center 
                                            shadow-lg transform transition-all duration-300
                                            group-hover:scale-110 group-hover:shadow-xl
                                            border-2 border-white">
                                    {{ $userPendingOrders }}
                                </span>
                            @else
                                <span id="orders-count-badge" class="hidden"></span>
                            @endif
                        </div>
                        
                        <!-- Optional tooltip on hover -->
                        <span class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 
                                    bg-gray-800 text-white text-xs py-1 px-2 rounded 
                                    opacity-0 group-hover:opacity-100 transition-opacity duration-300
                                    whitespace-nowrap pointer-events-none">
                            View Orders
                        </span>
                    </a>
                </li>


                <!-- CART - WITH DYNAMIC BADGE -->
                <li class="flex items-center">
                    <a href="{{ route('client.cart.index') }}" 
                    class="relative group outline-none block p-2.5 rounded-xl transition-all duration-300
                            {{ request()->routeIs('client.cart.index') 
                                ? 'bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-semibold shadow-md' 
                                : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                        
                        <!-- Cart Icon Container -->
                        <div class="relative">
                            <!-- Cart Icon -->
                            <svg class="w-6 h-6 transition-all duration-300" 
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            
                            <!-- Cart Badge with enhanced design -->
                            <span id="cart-count-badge" 
                                class="absolute -top-4 -right-4 min-w-[22px] h-[22px] px-1.5 
                                        bg-gradient-to-r from-[#ea5a47] to-[#c53030] 
                                        text-white text-xs font-bold rounded-full 
                                        flex items-center justify-center 
                                        shadow-lg transform transition-all duration-300
                                        group-hover:scale-110 group-hover:shadow-xl
                                        border-2 border-white
                                        {{ isset($cartCount) && $cartCount > 0 ? '' : 'hidden' }}">
                                {{ $cartCount ?? 0 }}
                            </span>
                        
                        </div>
                        
                        <!-- Optional tooltip on hover -->
                        <span class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 
                                    bg-gray-800 text-white text-xs py-1 px-2 rounded 
                                    opacity-0 group-hover:opacity-100 transition-opacity duration-300
                                    whitespace-nowrap pointer-events-none">
                            View Cart
                        </span>
                    </a>
                </li>
                
                <!-- PROFILE DROPDOWN MENU -->
                <!-- PROFILE DROPDOWN MENU -->
                <li class="relative group">
                    <!-- Profile Button -->
                    <button class="flex items-center gap-2 text-[17px] py-2.5 px-5 rounded-xl transition-all duration-200
                                text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47] group">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Profile</span>
                        <svg class="w-4 h-4 ml-1 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2 z-50">
                        
                        <!-- USER INFO HEADER -->
                        <div class="px-5 py-4 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#ea5a47] to-[#c53030] flex items-center justify-center text-white font-bold text-xl shadow-md">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                    <p class="text-xs text-gray-400 mt-1">Member since {{ Auth::user()->created_at->format('M Y') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- MY PROFILE -->
                        <a href="{{ route('client.profile') }}" 
                        class="flex items-center gap-3 px-5 py-3 text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47] transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-sm font-medium">My Profile</span>
                        </a>
                        
                        <!-- MY TRANSACTIONS -->
                        <a href="{{ route('client.transactions.index') }}" 
                        class="flex items-center gap-3 px-5 py-3 text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47] transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span class="text-sm font-medium">My Transactions</span>
                            @php
                                $transactionCount = \App\Models\Transaction::where('user_id', Auth::id())->count();
                            @endphp
                            @if($transactionCount > 0)
                                <span class="ml-auto bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full">
                                    {{ $transactionCount }}
                                </span>
                            @endif
                        </a>
                        
                        <!-- EDIT PROFILE -->
                        <a href="{{ route('client.profile.edit') }}" 
                        class="flex items-center gap-3 px-5 py-3 text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47] transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span class="text-sm font-medium">Edit Profile</span>
                        </a>
                        
                        <!-- CHANGE PASSWORD -->
                        <a href="{{ route('client.password.change') }}" 
                        class="flex items-center gap-3 px-5 py-3 text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47] transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span class="text-sm font-medium">Change Password</span>
                        </a>                        
                       
                        <!-- Divider -->
                        <div class="border-t border-gray-100 my-2"></div>
                        
                        <!-- LOGOUT -->
                        @if(session()->has('user'))
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center cursor-pointer gap-3 px-5 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span class="text-sm font-medium">Logout</span>
                                </button>
                            </form>
                        @else
                            <!-- Login Link for Guest Users -->
                            <a href="{{ route('login.form') }}" 
                            class="flex items-center gap-3 px-5 py-3 text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47] transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                <span class="text-sm font-medium">Login</span>
                            </a>
                        @endif
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>


<script>
    function updateOrderBadge() {
        fetch('{{ route("client.order.counts") }}')
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('orders-count-badge');
                if (data.success && data.pending > 0) {
                    badge.textContent = data.pending;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            })
            .catch(error => console.error('Error updating order badge:', error));
    }

    // Update badge every 30 seconds
    setInterval(updateOrderBadge, 30000);

    // Also update when page becomes visible again
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            updateOrderBadge();
        }
    });
</script>


<style>
    /* Smooth scroll padding for fixed navbar */
    html {
        scroll-padding-top: 80px;
    }
    
    /* Mobile menu styles */
    @media (max-width: 768px) {
        #navbar-dropdown:not(.hidden) {
            display: block;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            margin: 0 1rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        /* Mobile dropdown positioning */
        .group .group-hover\:visible {
            position: static;
            width: 100%;
            margin-top: 0.5rem;
            box-shadow: none;
            border: 1px solid #e5e7eb;
        }
    }
    
    /* Dropdown arrow */
    .group:hover .group-hover\:rotate-180 {
        transform: rotate(180deg);
    }
    
    /* Cursor default for non-functional items */
    .cursor-default {
        cursor: default;
    }
    
    /* Badge animation */
    .scale-125 {
        transform: scale(1.25);
    }
</style>