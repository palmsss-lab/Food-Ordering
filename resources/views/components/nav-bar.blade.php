<nav class="bg-white/95 backdrop-blur-md fixed w-full z-20 top-0 start-0 border-b border-[#ea5a47]/10 shadow-lg">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-16 sm:h-18 md:h-20">

            <!-- ── Logo ── -->
            <a href="{{ route('client.home') }}"
               class="flex items-center gap-2 shrink-0 group cursor-pointer">
                <img src="{{ asset('images/logo.png') }}"
                     alt="2Dine-In Logo"
                     class="w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14 object-contain">
                <span class="text-lg sm:text-xl md:text-2xl font-black text-gray-800
                             group-hover:text-[#ea5a47] transition-colors">
                    2Dine-In
                </span>
            </a>

            <!-- ── Desktop nav (md+) ── -->
            <ul class="hidden md:flex items-center gap-0.5 lg:gap-1 xl:gap-2 font-medium">

                {{-- Home --}}
                <li>
                    <a href="{{ route('client.home') }}"
                       class="flex items-center gap-1.5 text-sm lg:text-[15px] py-2 px-2.5 lg:px-3.5 xl:px-4 rounded-xl transition-all duration-200
                              {{ request()->routeIs('client.home')
                                   ? 'bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-semibold shadow-md'
                                   : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Home</span>
                    </a>
                </li>

                {{-- Menu --}}
                <li>
                    <a href="{{ route('client.menu') }}"
                       class="flex items-center gap-1.5 text-sm lg:text-[15px] py-2 px-2.5 lg:px-3.5 xl:px-4 rounded-xl transition-all duration-200
                              {{ request()->routeIs('client.menu')
                                   ? 'bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-semibold shadow-md'
                                   : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        <span>Menu</span>
                    </a>
                </li>

                {{-- About --}}
                <li>
                    <a href="{{ route('client.about') }}"
                       class="flex items-center gap-1.5 text-sm lg:text-[15px] py-2 px-2.5 lg:px-3.5 xl:px-4 rounded-xl transition-all duration-200
                              {{ request()->routeIs('client.about')
                                   ? 'bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-semibold shadow-md'
                                   : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                        </svg>
                        <span>About</span>
                    </a>
                </li>

                {{-- Vouchers --}}
                <li>
                    @php
                        $myVoucherCount = auth()->check()
                            ? \DB::table('user_vouchers')->where('user_id', auth()->id())->whereNull('used_at')->count()
                            : 0;
                    @endphp
                    <a href="{{ route('client.vouchers.index') }}"
                       class="flex items-center gap-1.5 text-sm lg:text-[15px] py-2 px-2.5 lg:px-3.5 xl:px-4 rounded-xl transition-all duration-200
                              {{ request()->routeIs('client.vouchers.*')
                                   ? 'bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-semibold shadow-md'
                                   : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <span>Vouchers</span>
                        @if($myVoucherCount > 0)
                            <span class="min-w-[18px] h-[18px] px-1 bg-[#ea5a47] text-white text-xs font-bold rounded-full flex items-center justify-center leading-none">{{ $myVoucherCount }}</span>
                        @endif
                    </a>
                </li>

                {{-- My Orders --}}
                <li>
                    <a href="{{ route('client.orders.index') }}"
                       class="flex items-center gap-1.5 text-sm lg:text-[15px] py-2 px-2.5 lg:px-3.5 xl:px-4 rounded-xl transition-all duration-300
                              {{ request()->routeIs('client.orders.index') || request()->routeIs('client.orders.show')
                                   ? 'bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-semibold shadow-md'
                                   : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        {{-- Shorten label on md to save space, full label on lg+ --}}
                        <span class="md:hidden lg:inline">My Orders</span>
                        <span class="hidden md:inline lg:hidden">Orders</span>
                        @if(isset($userPendingOrders) && $userPendingOrders > 0)
                            <span id="orders-count-badge"
                                  class="min-w-[18px] h-[18px] px-1 bg-gradient-to-r from-[#ea5a47] to-[#c53030]
                                         text-white text-xs font-bold rounded-full flex items-center justify-center
                                         shadow-sm border border-white">
                                {{ $userPendingOrders }}
                            </span>
                        @else
                            <span id="orders-count-badge" class="hidden"></span>
                        @endif
                    </a>
                </li>

                {{-- Cart --}}
                <li class="relative group/cart">
                    <a href="{{ route('client.cart.index') }}"
                       class="relative flex items-center gap-1.5 text-sm lg:text-[15px] py-2 px-2.5 lg:px-3.5 xl:px-4 rounded-xl transition-all duration-300
                              {{ request()->routeIs('client.cart.index')
                                   ? 'bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-semibold shadow-md'
                                   : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                        <div class="relative shrink-0">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <!-- Handle knob -->
                                <circle cx="12" cy="5" r="1.5" fill="currentColor" stroke="none"/>
                                <!-- Handle stem -->
                                <line x1="12" y1="6.5" x2="12" y2="9.5"/>
                                <!-- Dome -->
                                <path d="M4 17C4 12.582 7.582 9.5 12 9.5s8 3.082 8 7.5"/>
                                <!-- Plate rim -->
                                <line x1="2" y1="17" x2="22" y2="17"/>
                                <!-- Plate base -->
                                <path d="M5 17v1a1 1 0 001 1h12a1 1 0 001-1v-1"/>
                            </svg>
                            <span id="cart-count-badge"
                                  class="absolute -top-2.5 -right-2.5 min-w-[18px] h-[18px] px-1
                                         bg-gradient-to-r from-[#ea5a47] to-[#c53030]
                                         text-white text-[10px] font-bold rounded-full
                                         flex items-center justify-center border border-white
                                         transition-all duration-300
                                         {{ isset($cartCount) && $cartCount > 0 ? '' : 'hidden' }}">
                                {{ $cartCount ?? 0 }}
                            </span>
                        </div>
                    </a>
                    {{-- Tooltip --}}
                    <div class="absolute top-full left-1/2 -translate-x-1/2 mt-2 pointer-events-none
                                opacity-0 group-hover/cart:opacity-100 translate-y-1 group-hover/cart:translate-y-0
                                transition-all duration-200 z-50 whitespace-nowrap">
                        <div class="bg-gray-900 text-white text-xs font-medium px-2.5 py-1.5 rounded-lg shadow-lg">
                            My Tray
                        </div>
                        <div class="absolute -top-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-gray-900 rotate-45"></div>
                    </div>
                </li>

                {{-- Profile dropdown --}}
                <li class="relative" id="profile-dropdown-wrapper">
                    <button onclick="toggleProfileDropdown()" id="profile-dropdown-btn"
                            class="flex items-center gap-1.5 text-sm lg:text-[15px] py-2 px-2.5 lg:px-3.5 xl:px-4 rounded-xl transition-all duration-200
                                   text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Profile</span>
                        <svg class="w-3.5 h-3.5 shrink-0 transition-transform duration-200"
                             id="profile-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown panel -->
                    <div id="profile-dropdown-menu"
                         class="absolute right-0 mt-2 w-72 bg-white rounded-2xl shadow-xl border border-gray-100
                                py-2 opacity-0 invisible transition-all duration-300 transform translate-y-2 z-50">

                        <div class="px-4 py-3 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#ea5a47] to-[#c53030]
                                            flex items-center justify-center text-white font-bold text-lg shadow-md shrink-0">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Member since {{ Auth::user()->created_at->format('M Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('client.profile') }}"
                           class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47] transition-colors">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="text-sm font-medium">My Profile</span>
                        </a>

                        <a href="{{ route('client.transactions.index') }}"
                           class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47] transition-colors">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span class="text-sm font-medium">My Transactions</span>
                            @php $transactionCount = \App\Models\Transaction::where('user_id', Auth::id())->count(); @endphp
                            @if($transactionCount > 0)
                                <span class="ml-auto bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full">{{ $transactionCount }}</span>
                            @endif
                        </a>

                        <a href="{{ route('client.profile.edit') }}"
                           class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47] transition-colors">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span class="text-sm font-medium">Edit Profile</span>
                        </a>

                        <a href="{{ route('client.password.change') }}"
                           class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47] transition-colors">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span class="text-sm font-medium">Change Password</span>
                        </a>

                        <div class="border-t border-gray-100 my-1"></div>

                        @if(session()->has('user'))
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-gray-700
                                               hover:bg-red-50 hover:text-red-600 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    <span class="text-sm font-medium">Logout</span>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login.form') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47] transition-colors">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                <span class="text-sm font-medium">Login</span>
                            </a>
                        @endif
                    </div>
                </li>
            </ul>

            <!-- ── Mobile right cluster (cart icon + hamburger) ── -->
            <div class="flex items-center gap-1 md:hidden">

                {{-- Cart shortcut always visible on mobile --}}
                <a href="{{ route('client.cart.index') }}"
                   class="relative p-2 rounded-xl transition-all
                          {{ request()->routeIs('client.cart.index')
                               ? 'bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white'
                               : 'text-gray-600 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="5" r="1.5" fill="currentColor" stroke="none"/>
                        <line x1="12" y1="6.5" x2="12" y2="9.5"/>
                        <path d="M4 17C4 12.582 7.582 9.5 12 9.5s8 3.082 8 7.5"/>
                        <line x1="2" y1="17" x2="22" y2="17"/>
                        <path d="M5 17v1a1 1 0 001 1h12a1 1 0 001-1v-1"/>
                    </svg>
                    {{-- Uses same id so updateCartBadge() works on mobile too --}}
                    <span id="cart-count-badge"
                          class="absolute -top-1 -right-1 min-w-[16px] h-[16px] px-0.5
                                 bg-[#ea5a47] text-white text-[9px] font-bold rounded-full
                                 flex items-center justify-center border border-white
                                 {{ isset($cartCount) && $cartCount > 0 ? '' : 'hidden' }}">
                        {{ $cartCount ?? 0 }}
                    </span>
                </a>

                {{-- Hamburger --}}
                <button data-collapse-toggle="mobile-menu" type="button"
                        class="inline-flex items-center justify-center p-2 w-10 h-10 text-gray-600 rounded-xl
                               hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]
                               focus:outline-none focus:ring-2 focus:ring-[#ea5a47]/30
                               transition-all duration-200"
                        aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14"/>
                    </svg>
                </button>
            </div>

        </div><!-- /flex row -->
    </div><!-- /max-w container -->

    <!-- ── Mobile slide-down menu ── -->
    <div id="mobile-menu"
         class="hidden md:hidden border-t border-gray-100 bg-white shadow-lg">
        <ul class="flex flex-col px-4 py-3 gap-1 font-medium">

            {{-- Home --}}
            <li>
                <a href="{{ route('client.home') }}"
                   class="flex items-center gap-3 py-3 px-4 rounded-xl text-[15px] transition-all
                          {{ request()->routeIs('client.home')
                               ? 'bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-semibold'
                               : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Home</span>
                </a>
            </li>

            {{-- Menu --}}
            <li>
                <a href="{{ route('client.menu') }}"
                   class="flex items-center gap-3 py-3 px-4 rounded-xl text-[15px] transition-all
                          {{ request()->routeIs('client.menu')
                               ? 'bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-semibold'
                               : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                    </svg>
                    <span>Menu</span>
                </a>
            </li>

            {{-- About --}}
            <li>
                <a href="{{ route('client.about') }}"
                   class="flex items-center gap-3 py-3 px-4 rounded-xl text-[15px] transition-all
                          {{ request()->routeIs('client.about')
                               ? 'bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-semibold'
                               : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                    </svg>
                    <span>About</span>
                </a>
            </li>

            {{-- Vouchers --}}
            <li>
                <a href="{{ route('client.vouchers.index') }}"
                   class="flex items-center gap-3 py-3 px-4 rounded-xl text-[15px] transition-all
                          {{ request()->routeIs('client.vouchers.*')
                               ? 'bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-semibold'
                               : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <span>Vouchers</span>
                    @if($myVoucherCount > 0)
                        <span class="ml-auto bg-[#ea5a47] text-white text-xs px-2 py-0.5 rounded-full font-bold">{{ $myVoucherCount }}</span>
                    @endif
                </a>
            </li>

            {{-- My Orders --}}
            <li>
                <a href="{{ route('client.orders.index') }}"
                   class="flex items-center gap-3 py-3 px-4 rounded-xl text-[15px] transition-all
                          {{ request()->routeIs('client.orders.index') || request()->routeIs('client.orders.show')
                               ? 'bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-semibold'
                               : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span>My Orders</span>
                    @if(isset($userPendingOrders) && $userPendingOrders > 0)
                        <span class="ml-auto bg-yellow-100 text-yellow-700 text-xs px-2 py-0.5 rounded-full font-bold">{{ $userPendingOrders }}</span>
                    @endif
                </a>
            </li>

            {{-- Cart --}}
            <li>
                <a href="{{ route('client.cart.index') }}"
                   class="flex items-center gap-3 py-3 px-4 rounded-xl text-[15px] transition-all
                          {{ request()->routeIs('client.cart.index')
                               ? 'bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-semibold'
                               : 'text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47]' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span>Cart</span>
                    @if(isset($cartCount) && $cartCount > 0)
                        <span class="ml-auto bg-[#ea5a47] text-white text-xs px-2 py-0.5 rounded-full font-bold">{{ $cartCount }}</span>
                    @endif
                </a>
            </li>

            {{-- Mobile Profile Accordion --}}
            <li class="border-t border-gray-100 mt-1 pt-1">
                <button onclick="toggleMobileProfile()" id="mobile-profile-btn"
                        class="w-full flex items-center gap-3 py-3 px-4 rounded-xl text-[15px]
                               text-gray-700 hover:bg-[#ea5a47]/10 hover:text-[#ea5a47] transition-all">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#ea5a47] to-[#c53030]
                                flex items-center justify-center text-white font-bold text-sm shrink-0">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="min-w-0 flex-1 text-left">
                        <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <svg id="mobile-profile-chevron" class="w-4 h-4 shrink-0 transition-transform duration-200"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Collapsible profile links --}}
                <div id="mobile-profile-menu" class="hidden flex-col gap-0.5 pl-2 pb-1">
                    <a href="{{ route('client.profile') }}"
                       class="flex items-center gap-3 py-2.5 px-4 rounded-xl text-[15px] text-gray-700
                              hover:bg-[#ea5a47]/10 hover:text-[#ea5a47] transition-all">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>My Profile</span>
                    </a>

                    <a href="{{ route('client.profile.edit') }}"
                       class="flex items-center gap-3 py-2.5 px-4 rounded-xl text-[15px] text-gray-700
                              hover:bg-[#ea5a47]/10 hover:text-[#ea5a47] transition-all">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span>Edit Profile</span>
                    </a>

                    <a href="{{ route('client.password.change') }}"
                       class="flex items-center gap-3 py-2.5 px-4 rounded-xl text-[15px] text-gray-700
                              hover:bg-[#ea5a47]/10 hover:text-[#ea5a47] transition-all">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span>Change Password</span>
                    </a>

                    <a href="{{ route('client.transactions.index') }}"
                       class="flex items-center gap-3 py-2.5 px-4 rounded-xl text-[15px] text-gray-700
                              hover:bg-[#ea5a47]/10 hover:text-[#ea5a47] transition-all">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span>My Transactions</span>
                    </a>

                    <div class="border-t border-gray-100 mt-1 pt-1">
                        @if(session()->has('user'))
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-3 py-2.5 px-4 rounded-xl text-[15px]
                                               text-red-600 hover:bg-red-50 transition-all cursor-pointer font-medium">
                                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    <span>Logout</span>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login.form') }}"
                               class="flex items-center gap-3 py-2.5 px-4 rounded-xl text-[15px] text-gray-700
                                      hover:bg-[#ea5a47]/10 hover:text-[#ea5a47] transition-all font-medium">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                <span>Login</span>
                            </a>
                        @endif
                    </div>
                </div>
            </li>

        </ul>
    </div><!-- /mobile-menu -->
</nav>


<script>
    // ── Profile dropdown (desktop) ──
    function toggleProfileDropdown() {
        const menu    = document.getElementById('profile-dropdown-menu');
        const chevron = document.getElementById('profile-chevron');
        const isOpen  = !menu.classList.contains('invisible');
        if (isOpen) {
            menu.classList.add('opacity-0', 'invisible', 'translate-y-2');
            menu.classList.remove('opacity-100', 'translate-y-0');
            chevron.style.transform = 'rotate(0deg)';
        } else {
            menu.classList.remove('opacity-0', 'invisible', 'translate-y-2');
            menu.classList.add('opacity-100', 'translate-y-0');
            chevron.style.transform = 'rotate(180deg)';
        }
    }

    // Close desktop dropdown on outside click
    document.addEventListener('click', function (e) {
        const wrapper = document.getElementById('profile-dropdown-wrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            const menu    = document.getElementById('profile-dropdown-menu');
            const chevron = document.getElementById('profile-chevron');
            if (menu) {
                menu.classList.add('opacity-0', 'invisible', 'translate-y-2');
                menu.classList.remove('opacity-100', 'translate-y-0');
            }
            if (chevron) chevron.style.transform = 'rotate(0deg)';
        }
    });

    // ── Mobile profile accordion ──
    function toggleMobileProfile() {
        const menu    = document.getElementById('mobile-profile-menu');
        const chevron = document.getElementById('mobile-profile-chevron');
        const isHidden = menu.classList.contains('hidden');
        menu.classList.toggle('hidden', !isHidden);
        menu.classList.toggle('flex', isHidden);
        chevron.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
    }

    // ── Mobile hamburger toggle ──
    (function () {
        const btn  = document.querySelector('[data-collapse-toggle="mobile-menu"]');
        const menu = document.getElementById('mobile-menu');
        if (!btn || !menu) return;

        btn.addEventListener('click', function () {
            const isHidden = menu.classList.contains('hidden');
            menu.classList.toggle('hidden', !isHidden);
            btn.setAttribute('aria-expanded', String(isHidden));
            // Reset profile accordion when hamburger closes
            if (!isHidden) {
                const pm = document.getElementById('mobile-profile-menu');
                const pc = document.getElementById('mobile-profile-chevron');
                if (pm) { pm.classList.add('hidden'); pm.classList.remove('flex'); }
                if (pc) pc.style.transform = 'rotate(0deg)';
            }
        });

        // Close hamburger when a link inside the menu is clicked (but not the profile toggle button)
        menu.querySelectorAll('a, button[type="submit"]').forEach(function (el) {
            el.addEventListener('click', function () {
                menu.classList.add('hidden');
                btn.setAttribute('aria-expanded', 'false');
            });
        });
    })();

    // ── Orders badge refresh ──
    function updateOrderBadge() {
        fetch('{{ route("client.order.counts") }}')
            .then(r => r.json())
            .then(data => {
                const badge = document.getElementById('orders-count-badge');
                if (!badge) return;
                if (data.success && data.pending > 0) {
                    badge.textContent = data.pending;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            })
            .catch(() => {});
    }

    setInterval(updateOrderBadge, 30000);
    document.addEventListener('visibilitychange', function () {
        if (!document.hidden) updateOrderBadge();
    });
</script>

<style>
    html { scroll-padding-top: 72px; }

    @media (min-width: 768px) {
        html { scroll-padding-top: 80px; }
    }
</style>
