<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>@yield('title') | 2Dine-In</title>

    <style>
        @keyframes slideDown {
            from { transform: translateX(100%); opacity: 0; }
            to   { transform: translateX(0);    opacity: 1; }
        }
        .animate-slide-down { animation: slideDown 0.3s ease-out; }

        /* Respect user motion preference */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>

<body>

    <x-global-loader />

    <x-nav-bar />

    {{-- Promo Modal --}}
    @php
        $activePromo = \App\Models\Promotion::todayPromo();
        $promoSessionKey = 'promo_shown_' . now()->toDateString();
        $showPromoModal  = $activePromo && auth()->check() && !session($promoSessionKey);
        if ($showPromoModal) session([$promoSessionKey => true]);
    @endphp

    @if($showPromoModal)
    <div id="promoModal" class="fixed inset-0 z-[9998] flex items-center justify-center px-4" aria-modal="true" role="dialog">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closePromoModal()"></div>

        {{-- Card --}}
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden animate-promo-in">

            {{-- Hero section with full gradient background --}}
            <div class="relative px-8 pt-10 pb-8 text-center text-white"
                 style="background: linear-gradient(145deg, {{ $activePromo->banner_color }}, {{ $activePromo->banner_color }}bb)">

                {{-- Close button --}}
                <button onclick="closePromoModal()"
                        class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-white/20 hover:bg-white/35 transition-colors">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                {{-- Tag label --}}
                <div class="inline-flex items-center gap-1.5 bg-white/20 rounded-full px-3 py-1 text-xs font-bold tracking-widest uppercase mb-5">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    Limited Time Offer
                </div>

                {{-- Discount display --}}
                <div class="mb-4">
                    <div class="flex items-end justify-center gap-1 leading-none">
                        <span class="text-7xl font-black tracking-tighter">{{ number_format($activePromo->discount_percentage, 0) }}</span>
                        <span class="text-4xl font-black mb-2">%</span>
                    </div>
                    <p class="text-sm font-semibold opacity-80 tracking-wide uppercase mt-1">Off All Menu Items</p>
                </div>

                {{-- Title & description --}}
                <h2 class="text-xl font-bold mb-1">{{ $activePromo->title }}</h2>
                @if($activePromo->description)
                    <p class="text-sm opacity-80 leading-relaxed">{{ $activePromo->description }}</p>
                @endif
            </div>

            {{-- Bottom section --}}
            <div class="px-8 py-6">

                {{-- Date pill --}}
                <div class="flex items-center justify-center gap-2 mb-5">
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-xs text-gray-500 font-medium">
                        {{ $activePromo->start_date->format('M d, Y') }} — {{ $activePromo->end_date->format('M d, Y') }}
                    </span>
                </div>

                {{-- CTA --}}
                <a href="{{ route('client.menu') }}"
                   onclick="closePromoModal()"
                   class="flex items-center justify-center gap-2 w-full py-3.5 text-white font-bold rounded-2xl transition-all hover:opacity-90 hover:shadow-lg active:scale-95 text-sm"
                   style="background: linear-gradient(135deg, {{ $activePromo->banner_color }}, {{ $activePromo->banner_color }}cc)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Order Now & Save
                </a>
                <button onclick="closePromoModal()"
                        class="w-full mt-2.5 py-2.5 text-gray-400 text-sm font-medium hover:text-gray-600 transition-colors">
                    Maybe Later
                </button>
            </div>
        </div>
    </div>

    <style>
        @keyframes promoIn {
            from { opacity: 0; transform: scale(0.9) translateY(16px); }
            to   { opacity: 1; transform: scale(1)   translateY(0);    }
        }
        .animate-promo-in { animation: promoIn 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) both; }
    </style>

    <script>
        function closePromoModal() {
            const m = document.getElementById('promoModal');
            if (m) {
                m.style.transition = 'opacity 0.2s ease';
                m.style.opacity = '0';
                setTimeout(() => m.remove(), 200);
                document.body.style.overflow = '';
            }
        }
        document.body.style.overflow = 'hidden';
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closePromoModal();
        });
    </script>
    @endif

    <main>
        @yield('content')
    </main>

    <x-footer-links />

    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js" defer></script>

    <script>
        // ========== PROGRESS BAR ==========
        const _bar = document.getElementById('progress-bar');
        let _barTimer = null;
        let _barValue  = 0;
        let _barActive = false;

        function showProgress() {
            if (_barActive) return;
            _barActive = true;
            _barValue  = 0;
            if (_bar) {
                _bar.style.opacity = '1';
                _bar.style.width   = '0%';
                _bar.style.transition = 'width 0.25s ease, opacity 0.3s ease';
            }
            _tickProgress();
        }

        function _tickProgress() {
            // Increment quickly at first, slow as we approach 85%
            const increment = _barValue < 30 ? 8 : _barValue < 60 ? 4 : _barValue < 80 ? 1.5 : 0;
            _barValue = Math.min(_barValue + increment, 85);
            if (_bar) _bar.style.width = _barValue + '%';
            if (_barValue < 85) _barTimer = setTimeout(_tickProgress, 180);
        }

        function finishProgress() {
            if (_barTimer) clearTimeout(_barTimer);
            if (_bar) {
                _bar.style.width = '100%';
                setTimeout(() => {
                    _bar.style.opacity = '0';
                    setTimeout(() => {
                        if (_bar) _bar.style.width = '0%';
                        _barActive = false;
                    }, 320);
                }, 220);
            } else {
                _barActive = false;
            }
        }

        // Kept for backward-compat (old code may call these)
        function showLoader() { showProgress(); }
        function hideLoader() { finishProgress(); }

        // ========== PAGE LOAD HANDLING ==========
        document.addEventListener('DOMContentLoaded', function() {
            finishProgress();
        });

        // Handle all internal link clicks
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (!link) return;
            if (e.ctrlKey || e.metaKey || e.shiftKey) return;
            if (link.hostname !== window.location.hostname) return;
            if (link.hasAttribute('data-no-loader')) return;
            const href = link.getAttribute('href');
            if (!href || href === '#' || href.startsWith('#') || href.startsWith('javascript:')) return;
            if (link.target === '_blank') return;
            if (link.hasAttribute('download')) return;
            showProgress();
        });

        // Handle form submissions
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.hasAttribute('data-no-loader')) return;

            showProgress();

            const submitBtn = form.querySelector('button[type="submit"], button:not([type="button"])');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = `<span>Processing...</span>
                    <svg class="w-4 h-4 animate-spin ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>`;
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                }, 8000);
            }
        });

        // Handle back/forward cache
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) finishProgress();
        });

        // ========== TOAST NOTIFICATION ==========
        function showDynamicToast(message, isError = false, icon = null, bgColor = null) {
            const toast = document.createElement('div');

            if (bgColor) {
                toast.className = `fixed top-4 right-4 z-[9999] ${bgColor} shadow-xl rounded-lg p-4 animate-slide-down max-w-sm`;
            } else {
                const defaultBg = isError ? 'bg-red-600 text-white' : 'bg-green-600 text-white';
                toast.className = `fixed top-4 right-4 z-[9999] ${defaultBg} shadow-xl rounded-lg p-4 animate-slide-down max-w-sm`;
            }

            const displayIcon = icon || (isError ? '❌' : '✅');

            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', isError ? 'assertive' : 'polite');
            toast.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="text-2xl" aria-hidden="true">${displayIcon}</div>
                    <div class="flex-1">
                        <p class="text-sm ${bgColor ? 'text-gray-600' : 'text-white opacity-90'}">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" aria-label="Dismiss notification" class="${bgColor ? 'text-gray-400 hover:text-gray-600' : 'text-white hover:text-gray-200'}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            `;

            document.body.appendChild(toast);
            // Errors stay 8s; success toasts stay 5s — critical messages need more reading time
            const dismissDelay = isError ? 8000 : 5000;
            setTimeout(() => { if (toast?.remove) toast.remove(); }, dismissDelay);
        }

        // ========== CART FUNCTIONS ==========
        function updateCartBadge(count) {
            const badge = document.getElementById('cart-count-badge');
            if (badge) {
                if (count > 0) {
                    badge.textContent = count;
                    badge.classList.remove('hidden');
                    badge.classList.add('scale-125');
                    setTimeout(() => badge.classList.remove('scale-125'), 200);
                } else {
                    badge.classList.add('hidden');
                }
            }
        }

        function refreshCartCount() {
            fetch('/client/cart/count', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => { if (data.count !== undefined) updateCartBadge(data.count); })
            .catch(err => console.error('Error fetching cart count:', err));
        }

        // Make functions globally available
        window.showLoader       = showLoader;
        window.hideLoader       = hideLoader;
        window.updateCartBadge  = updateCartBadge;
        window.refreshCartCount = refreshCartCount;
        window.showToast = function(message, isError = false) {
            showDynamicToast(message, isError);
        };
    </script>

    @livewireScripts

    <script>
        // ========== FIRST-TIME USER ONBOARDING ==========
        (function () {
            if (localStorage.getItem('2dinein_onboarded')) return;

            // Only show on the menu/home page, not on auth pages
            const isClientPage = document.querySelector('#cart-count-badge') !== null;
            if (!isClientPage) return;

            // Brief cart tooltip after 1.5s
            setTimeout(function () {
                const cartIcon = document.getElementById('cart-count-badge')?.parentElement;
                if (!cartIcon) return;

                const tip = document.createElement('div');
                tip.id = 'onboarding-tip';
                tip.setAttribute('role', 'tooltip');
                tip.className = [
                    'absolute z-[9999] right-0 mt-2 w-64 bg-gray-900 text-white text-sm rounded-xl',
                    'shadow-2xl p-4 pointer-events-auto'
                ].join(' ');
                tip.innerHTML = `
                    <p class="font-semibold mb-1">Welcome to 2Dine-In! 👋</p>
                    <p class="text-gray-300 text-xs leading-relaxed">Browse the menu, add items to your cart, and place your order. We'll notify you when it's ready for pickup.</p>
                    <button onclick="document.getElementById('onboarding-tip')?.remove(); localStorage.setItem('2dinein_onboarded','1');"
                            class="mt-3 w-full py-1.5 bg-[#ea5a47] text-white text-xs font-bold rounded-lg hover:bg-[#c53030] transition-colors">
                        Got it!
                    </button>
                `;

                // Position relative to cart icon
                const parent = cartIcon.closest('.relative') || cartIcon.parentElement;
                if (parent) {
                    parent.style.position = 'relative';
                    parent.appendChild(tip);
                } else {
                    tip.className = 'fixed top-20 right-4 z-[9999] w-64 bg-gray-900 text-white text-sm rounded-xl shadow-2xl p-4';
                    document.body.appendChild(tip);
                }

                // Auto-dismiss after 10s
                setTimeout(function () {
                    document.getElementById('onboarding-tip')?.remove();
                    localStorage.setItem('2dinein_onboarded', '1');
                }, 10000);
            }, 1500);
        })();

        // ========== ORDER NOTIFICATION DEDUP HELPERS ==========
        // Both Livewire (orders page) and the global poller (all other pages) write here
        // so the same status change is never toasted twice regardless of which fires first.
        function _getNotifShown()    { try { return JSON.parse(localStorage.getItem('order_notif_shown') || '{}'); } catch { return {}; } }
        function _getPrevStatuses()  { try { return JSON.parse(localStorage.getItem('order_prev_statuses') || '{}'); } catch { return {}; } }

        function markOrderNotified(orderId, statusKey) {
            const d = _getNotifShown();
            d[orderId + ':' + statusKey] = 1;
            // Cap at 100 entries to avoid unbounded growth
            const keys = Object.keys(d);
            if (keys.length > 100) keys.slice(0, keys.length - 100).forEach(k => delete d[k]);
            localStorage.setItem('order_notif_shown', JSON.stringify(d));
        }

        function wasOrderNotified(orderId, statusKey) {
            return !!_getNotifShown()[orderId + ':' + statusKey];
        }

        // ========== ORDER STATUS TOAST NOTIFICATIONS (Livewire → JS) ==========
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('order-status-toast', (params) => {
                const { message, type, orderId, statusKey } = params;
                // Mark as shown so the global poller won't duplicate it on the next page
                if (orderId && statusKey) markOrderNotified(orderId, statusKey);
                showOrderToast(message, type);
            });
        });

        @auth
        // ========== GLOBAL ORDER POLLING (fires on every page, not just /orders) ==========
        (function () {
            function fireToast(order, prevKey) {
                const currKey    = order.display_status + '|' + order.payment_status;
                const [prevDisp] = prevKey.split('|');
                const [currDisp, currPay] = currKey.split('|');

                if (wasOrderNotified(order.id, currKey)) return;
                markOrderNotified(order.id, currKey);

                if (prevDisp === 'pending' && currDisp === 'preparing') {
                    showOrderToast(`Your order #${order.order_number} has been confirmed and is now being prepared! 🍳`, 'preparing');
                } else if (currDisp === 'ready') {
                    showOrderToast(`Order #${order.order_number} is ready for pickup! 🎉 Please come to our store.`, 'ready');
                } else if (currDisp === 'cancelled') {
                    showOrderToast(`Your order #${order.order_number} has been cancelled. Please contact us if you have questions.`, 'cancelled');
                } else if (currDisp === 'completed' && currPay === 'paid' && prevDisp === 'completed') {
                    showOrderToast(`Cash payment for order #${order.order_number} has been confirmed! 💵 Thank you!`, 'payment');
                } else if (currPay === 'refunded' || currPay === 'partial_refund') {
                    const label = currPay === 'partial_refund' ? 'A partial refund' : 'A refund';
                    showOrderToast(`${label} has been issued for order #${order.order_number}. Check your transactions for details.`, 'cancelled');
                }
            }

            function poll() {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
                fetch('/client/orders/check-updates', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ last_update: 0 })
                })
                .then(r => r.ok ? r.json() : null)
                .then(data => {
                    if (!data) return;
                    const prev = _getPrevStatuses();
                    const next = Object.assign({}, prev);

                    data.updated_orders.forEach(order => {
                        const currKey = order.display_status + '|' + order.payment_status;
                        const prevKey = prev[order.id];
                        // prevKey exists (not first-ever poll) and status actually changed
                        if (prevKey && prevKey !== currKey) {
                            fireToast(order, prevKey);
                        }
                        next[order.id] = currKey;
                    });

                    localStorage.setItem('order_prev_statuses', JSON.stringify(next));
                })
                .catch(() => {});
            }

            // Small delay so the page finishes loading before the first fetch
            setTimeout(poll, 3000);
            setInterval(poll, 20000);
        })();
        @endauth

        function showOrderToast(message, type) {
            const configs = {
                preparing: {
                    wrapper: 'bg-white border-l-4 border-[#ea5a47] shadow-2xl',
                    icon: `<div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0">
                               <svg class="w-5 h-5 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                               </svg>
                           </div>`,
                    title: 'Order Confirmed!',
                    titleClass: 'text-[#ea5a47]',
                },
                ready: {
                    wrapper: 'bg-white border-l-4 border-green-500 shadow-2xl',
                    icon: `<div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                               <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                               </svg>
                           </div>`,
                    title: 'Ready for Pickup! 🎉',
                    titleClass: 'text-green-700',
                },
                cancelled: {
                    wrapper: 'bg-white border-l-4 border-red-500 shadow-2xl',
                    icon: `<div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                               <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                               </svg>
                           </div>`,
                    title: 'Order Cancelled',
                    titleClass: 'text-red-700',
                },
                payment: {
                    wrapper: 'bg-white border-l-4 border-blue-500 shadow-2xl',
                    icon: `<div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                               <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                               </svg>
                           </div>`,
                    title: 'Payment Confirmed!',
                    titleClass: 'text-blue-700',
                },
            };

            const cfg = configs[type] || configs.preparing;
            const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            const toast = document.createElement('div');
            toast.className = `fixed top-5 right-5 z-[9999] max-w-sm w-full rounded-2xl p-4 ${cfg.wrapper}
                               flex items-start gap-3 animate-slide-in-right`;

            toast.innerHTML = `
                ${cfg.icon}
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sm ${cfg.titleClass}">${cfg.title}</p>
                    <p class="text-sm text-gray-600 mt-0.5 leading-snug">${message}</p>
                    <p class="text-xs text-gray-400 mt-1">${time}</p>
                </div>
                <button onclick="this.parentElement.remove()"
                        class="text-gray-300 hover:text-gray-500 flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            `;
            toast.setAttribute('data-order-toast', '');

            // Stack toasts — cap at 3 to prevent off-screen overflow on small screens
            const existing = document.querySelectorAll('[data-order-toast]');
            if (existing.length >= 3) existing[0].remove();
            const remaining = document.querySelectorAll('[data-order-toast]');
            const offset = remaining.length * 80;
            const maxTop = Math.min(20 + offset, window.innerHeight - 120);
            toast.style.top = `${maxTop}px`;

            document.body.appendChild(toast);

            // Auto-remove after 7 seconds
            setTimeout(() => { toast?.remove(); }, 7000);
        }
    </script>

    <style>
        @keyframes slide-in-right {
            from { opacity: 0; transform: translateX(120%); }
            to   { opacity: 1; transform: translateX(0); }
        }
        .animate-slide-in-right { animation: slide-in-right 0.35s cubic-bezier(0.22,1,0.36,1); }
    </style>
</body>
</html>
