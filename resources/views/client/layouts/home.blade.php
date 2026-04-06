<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>@yield('title') | 2Dine-In</title>
    
    <style>
        @keyframes slideDown {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .animate-slide-down {
            animation: slideDown 0.3s ease-out;
        }
    </style>
</head>

<body class="h-screen">

    <x-global-loader />
    
    <x-nav-bar />
    
    <main class="container mx-auto">
        @yield('content')
    </main>

    <x-footer-links />
    
    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        // ========== GLOBAL LOADER FUNCTIONS ==========
        const loader = document.getElementById('loader');

        function showLoader() {
            if (loader) {
                loader.style.display = 'flex';
                loader.classList.remove('opacity-0');
                loader.classList.add('opacity-100');
                document.body.style.overflow = 'hidden';
            }
        }

        function hideLoader() {
            if (loader) {
                loader.classList.remove('opacity-100');
                loader.classList.add('opacity-0');
                setTimeout(() => {
                    loader.style.display = 'none';
                    document.body.style.overflow = '';
                }, 300);
            }
        }

        // ========== PAGE LOAD HANDLING ==========
        // Hide loader when page is fully loaded
        window.addEventListener('load', function() {
            hideLoader();
        });

        // Also hide when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Small delay to ensure all content is loaded
            setTimeout(hideLoader, 500);
        });

        // Handle all link clicks
        document.querySelectorAll('a').forEach(link => {
            // Skip external links and special cases
            if (link.hostname !== window.location.hostname) return;
            if (link.hasAttribute('data-no-loader')) return;
            if (link.getAttribute('href') === '#') return;
            if (link.getAttribute('href')?.startsWith('#')) return;
            if (link.getAttribute('href')?.startsWith('javascript:')) return;
            if (link.target === '_blank') return;
            if (link.hasAttribute('download')) return;
            
            link.addEventListener('click', function(e) {
                // Don't show loader for modifier keys (Ctrl+Click, etc)
                if (e.ctrlKey || e.metaKey || e.shiftKey) return;
                showLoader();
            });
        });

        // Handle ALL form submissions - IMPORTANT for checkout
        document.querySelectorAll('form').forEach(form => {
            // Skip forms with data-no-loader
            if (form.hasAttribute('data-no-loader')) return;
            
            form.addEventListener('submit', function(e) {
                // Show loader immediately
                showLoader();
                
                // For checkout form, disable submit button and show loading text
                const submitBtn = this.querySelector('button[type="submit"], button:not([type="button"])');
                if (submitBtn) {
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span>Processing...</span><svg class="w-5 h-5 animate-spin ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>';
                    
                    // Restore button if form doesn't submit (for validation errors)
                    setTimeout(() => {
                        if (submitBtn.disabled) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        }
                    }, 5000);
                }
            });
        });

        // Handle checkout button specifically if it's not inside a form
        const checkoutButtons = document.querySelectorAll('[onclick*="prepareCheckout"], [onclick*="checkout"]');
        checkoutButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                showLoader();
            });
        });

        // Handle back/forward cache
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                hideLoader();
            }
        });

        // Also hide loader if navigation takes too long (safety)
        window.addEventListener('beforeunload', function() {
            // Don't hide here, just clean up
        });

        // ========== ORDER POLLING ==========
        let pollingInterval;
        let lastUpdateTime = localStorage.getItem('last_order_update') || Date.now();
        let lastShownNotification = {};

        function startOrderPolling() {
            if (pollingInterval) clearInterval(pollingInterval);
            pollingInterval = setInterval(() => {
                checkForOrderUpdates();
            }, 10000);
        }

        function checkForOrderUpdates() {
            fetch('{{ route("client.orders.check-updates") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ last_update: lastUpdateTime })
            })
            .then(response => response.json())
            .then(data => {
                if (data.updated_orders && data.updated_orders.length > 0) {
                    data.updated_orders.forEach(order => {
                        showOrderNotification(order);
                    });
                    lastUpdateTime = Date.now();
                    localStorage.setItem('last_order_update', lastUpdateTime);
                    
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }
            })
            .catch(error => console.error('Polling error:', error));
        }

        // ========== ORDER NOTIFICATION FUNCTION ==========
        function showOrderNotification(order) {
            let message = '';
            let icon = '';
            let bgColor = '';
            
            const notificationKey = `${order.order_number}_${order.status}_${order.payment_status}`;
            
            if (lastShownNotification[notificationKey] && 
                (Date.now() - lastShownNotification[notificationKey]) < 30000) {
                return;
            }
            
            lastShownNotification[notificationKey] = Date.now();
            
            // Determine notification type based on order status
            if (order.payment_status === 'paid' && order.payment_method === 'cash') {
                message = `✅ Payment Confirmed! Your payment for Order #${order.order_number} has been confirmed.`;
                icon = '💵';
                bgColor = 'bg-green-50 border-l-4 border-green-500';
                showDynamicToast(message, false, icon, bgColor);
            } else if (order.status === 'preparing') {
                message = `Order #${order.order_number} is now being prepared! 🍳`;
                icon = '🔪';
                bgColor = 'bg-purple-50 border-l-4 border-purple-500';
                showDynamicToast(message, false, icon, bgColor);
            } else if (order.status === 'ready') {
                message = `Order #${order.order_number} is ready for pickup! 🎉`;
                icon = '✅';
                bgColor = 'bg-green-50 border-l-4 border-green-500';
                showDynamicToast(message, false, icon, bgColor);
            } else if (order.status === 'completed' && order.payment_method !== 'cash') {
                message = `Order #${order.order_number} has been completed! 🎊`;
                icon = '🎉';
                bgColor = 'bg-gray-50 border-l-4 border-gray-500';
                showDynamicToast(message, false, icon, bgColor);
            } else if (order.status === 'cancelled') {
                message = `Order #${order.order_number} was cancelled. ❌`;
                icon = '❌';
                bgColor = 'bg-red-50 border-l-4 border-red-500';
                showDynamicToast(message, true, icon, bgColor);
            }
            
            // Browser notification
            if (Notification.permission === 'granted') {
                new Notification('2Dine-In Order Update', {
                    body: message,
                    icon: '/favicon.ico'
                });
            }
        }

        // ========== DYNAMIC TOAST FUNCTION (Creates toast on the fly) ==========
        function showDynamicToast(message, isError = false, icon = null, bgColor = null) {
            // Create toast element dynamically
            const toast = document.createElement('div');
            
            // Use provided styling or default
            if (bgColor) {
                toast.className = `fixed top-4 right-4 z-[9999] ${bgColor} shadow-xl rounded-lg p-4 animate-slide-down max-w-sm`;
            } else {
                const defaultBg = isError ? 'bg-red-600 text-white' : 'bg-green-600 text-white';
                toast.className = `fixed top-4 right-4 z-[9999] ${defaultBg} shadow-xl rounded-lg p-4 animate-slide-down max-w-sm`;
            }
            
            // Use provided icon or default
            const defaultIcon = isError ? '❌' : '✅';
            const displayIcon = icon || defaultIcon;
            
            toast.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="text-2xl">${displayIcon}</div>
                    <div class="flex-1">
                        <p class="font-bold ${bgColor ? 'text-gray-800' : 'text-white'}">Order Update</p>
                        <p class="text-sm ${bgColor ? 'text-gray-600' : 'text-white opacity-90'}">${message}</p>
                        <p class="text-xs ${bgColor ? 'text-gray-400' : 'text-white opacity-75'} mt-1">${new Date().toLocaleTimeString()}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="${bgColor ? 'text-gray-400 hover:text-gray-600' : 'text-white hover:text-gray-200'}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (toast && toast.remove) toast.remove();
            }, 5000);
        }

        // ========== GLOBAL TOAST FUNCTION (Compatible with your existing code) ==========
        window.showToast = function(message, isError = false) {
            showDynamicToast(message, isError);
        };

        // Clean up old notification records
        setInterval(() => {
            const now = Date.now();
            Object.keys(lastShownNotification).forEach(key => {
                if (now - lastShownNotification[key] > 60000) {
                    delete lastShownNotification[key];
                }
            });
        }, 60000);

        // Request notification permission
        if (Notification.permission === 'default') {
            Notification.requestPermission();
        }

        // Start polling when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            startOrderPolling();
        });

        // Stop polling when leaving the page
        window.addEventListener('beforeunload', function() {
            if (pollingInterval) clearInterval(pollingInterval);
        });

        // ========== CART FUNCTIONS ==========
        function updateCartBadge(count) {
            const badge = document.getElementById('cart-count-badge');
            if (badge) {
                if (count > 0) {
                    badge.textContent = count;
                    badge.classList.remove('hidden');
                    badge.classList.add('scale-125');
                    setTimeout(() => {
                        badge.classList.remove('scale-125');
                    }, 200);
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
            .then(response => response.json())
            .then(data => {
                if (data.count !== undefined) updateCartBadge(data.count);
            })
            .catch(error => console.error('Error fetching cart count:', error));
        }

        // Make functions globally available
        window.updateCartBadge = updateCartBadge;
        window.showLoader = showLoader;
        window.hideLoader = hideLoader;
        window.refreshCartCount = refreshCartCount;
        window.showToast = showToast;

    </script>
</body>
</html>