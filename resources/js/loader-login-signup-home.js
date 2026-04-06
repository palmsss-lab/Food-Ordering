// ========== GLOBAL LOADER FUNCTIONS ==========
let loaderTimeout;
let activeRequests = 0;
let isPageTransitioning = false;
let pendingHashChange = null;

const loader = document.getElementById('loader');

function showLoader() {
    if (loader && !isPageTransitioning) {
        isPageTransitioning = true;
        loader.style.display = 'flex';
        loader.classList.remove('opacity-0');
        loader.classList.add('opacity-100');
        document.body.style.overflow = 'hidden';
        
        // Safety timeout to hide loader if something goes wrong
        clearTimeout(loaderTimeout);
        loaderTimeout = setTimeout(() => {
            if (loader && loader.style.display !== 'none') {
                hideLoader();
            }
        }, 15000); // 15 seconds max
    }
}

function hideLoader() {
    if (loader) {
        clearTimeout(loaderTimeout);
        loader.classList.remove('opacity-100');
        loader.classList.add('opacity-0');
        setTimeout(() => {
            loader.style.display = 'none';
            document.body.style.overflow = '';
            isPageTransitioning = false;
            
            // Process any pending hash changes
            if (pendingHashChange) {
                window.location.hash = pendingHashChange;
                pendingHashChange = null;
            }
        }, 300);
    }
}

// ========== PAGE LOAD HANDLING ==========
// Handle page load and DOM ready
let pageLoaded = false;

function handlePageLoad() {
    if (!pageLoaded) {
        pageLoaded = true;
        setTimeout(hideLoader, 300);
    }
}

// Show loader initially
if (loader) {
    showLoader();
}

// Hide loader when page is fully loaded
window.addEventListener('load', handlePageLoad);

// Also hide when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(hideLoader, 500);
});

// ========== HANDLE ADMIN LINK CLICKS ==========
document.addEventListener('click', function(e) {
    // Find the clicked link
    let link = e.target.closest('a');
    if (!link) return;
    
    // Skip if we're already transitioning
    if (isPageTransitioning) {
        e.preventDefault();
        return;
    }
    
    // Skip external links and special cases
    if (link.hostname !== window.location.hostname) return;
    if (link.hasAttribute('data-no-loader')) return;
    if (link.getAttribute('href') === '#') return;
    if (link.getAttribute('href')?.startsWith('#')) {
        // Handle anchor links without page reload
        e.preventDefault();
        const targetId = link.getAttribute('href').substring(1);
        const targetElement = document.getElementById(targetId);
        if (targetElement) {
            targetElement.scrollIntoView({ behavior: 'smooth' });
        }
        return;
    }
    if (link.getAttribute('href')?.startsWith('javascript:')) return;
    if (link.target === '_blank') return;
    if (link.hasAttribute('download')) return;
    
    // Don't show loader for modifier keys (Ctrl+Click, etc)
    if (e.ctrlKey || e.metaKey || e.shiftKey) return;
    
    // Store hash if present
    const href = link.getAttribute('href');
    const hashIndex = href?.indexOf('#');
    if (hashIndex > 0) {
        pendingHashChange = href.substring(hashIndex);
    }
    
    // Show loader
    showLoader();
});

// ========== HANDLE ADMIN FORM SUBMISSIONS ==========
document.addEventListener('submit', function(e) {
    const form = e.target;
    
    // Skip forms with data-no-loader
    if (form.hasAttribute('data-no-loader')) return;
    
    // Don't show loader for file uploads (they'll show their own progress)
    if (form.getAttribute('enctype') === 'multipart/form-data') {
        // Still show loader but with a shorter timeout
        showLoader();
        
        // For forms with file uploads, we need to handle them differently
        // The form submission might take longer, so we'll hide loader on page load
        return;
    }
    
    // Show loader for all other form submissions
    showLoader();
});

// ========== HANDLE BACK/FORWARD CACHE ==========
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        // Page came from cache, hide loader immediately
        hideLoader();
        pageLoaded = true;
        isPageTransitioning = false;
    }
});

// ========== HANDLE POPSTATE (BROWSER BACK/FORWARD) ==========
window.addEventListener('popstate', function() {
    showLoader();
    // The page will reload content, hideLoader will be called on load
});

// ========== GLOBAL TOAST FUNCTION ==========
window.showToast = function(message, isError = false, duration = 3000) {
    // Check if toast container exists, if not create it
    let toast = document.getElementById('global-toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'global-toast';
        toast.className = 'fixed top-4 right-4 z-50 transform transition-all duration-300 opacity-0 -translate-y-5';
        document.body.appendChild(toast);
    }
    
    const toastMessage = document.createElement('div');
    const bgClass = isError ? 'bg-red-600' : 'bg-green-600';
    const iconSvg = isError 
        ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
    
    toastMessage.className = `${bgClass} shadow-xl rounded-lg p-4 mb-3 animate-slide-down max-w-md`;
    toastMessage.innerHTML = `
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0">${iconSvg}</div>
            <div class="flex-1">
                <p class="text-sm font-medium text-white">${message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    `;
    
    toast.appendChild(toastMessage);
    toast.classList.remove('opacity-0', '-translate-y-5');
    toast.classList.add('opacity-100', 'translate-y-0');
    
    // Auto remove after duration
    setTimeout(() => {
        if (toastMessage && toastMessage.remove) {
            toastMessage.remove();
        }
        // If no more toasts, hide the container
        if (toast.children.length === 0) {
            toast.classList.remove('opacity-100', 'translate-y-0');
            toast.classList.add('opacity-0', '-translate-y-5');
        }
    }, duration);
};

// ========== ADMIN POLLING FOR ORDER UPDATES ==========
let adminPollingInterval;
let adminLastUpdateTime = localStorage.getItem('admin_last_order_update') || Date.now();

function startAdminPolling() {
    if (adminPollingInterval) clearInterval(adminPollingInterval);
    
    // Only start polling if we're on admin pages
    if (!window.location.pathname.includes('/admin')) return;
    
    adminPollingInterval = setInterval(() => {
        // Don't poll if page is transitioning
        if (isPageTransitioning) return;
        checkAdminOrderUpdates();
    }, 10000); // Check every 10 seconds
}

function checkAdminOrderUpdates() {
    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) return;
    
    fetch(window.location.origin + '/admin/orders/check-updates', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ last_update: adminLastUpdateTime })
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        if (data.updated_orders && data.updated_orders.length > 0) {
            data.updated_orders.forEach(order => {
                showAdminNotification(order);
            });
            adminLastUpdateTime = Date.now();
            localStorage.setItem('admin_last_order_update', adminLastUpdateTime);
            
            // Reload the page to show updated data (only if not transitioning)
            if (!isPageTransitioning) {
                setTimeout(() => {
                    if (!isPageTransitioning) {
                        window.location.reload();
                    }
                }, 2000);
            }
        }
    })
    .catch(error => {
        // Silent fail for polling errors
        console.debug('Admin polling error:', error);
    });
}

function showAdminNotification(order) {
    let message = '';
    let icon = '';
    let bgClass = '';
    
    // Only show notifications for NEW ORDERS and PICKED UP ORDERS
    if (order.status === 'pending' && !order.admin_confirmed_at) {
        // New order placed
        message = `🆕 NEW ORDER #${order.order_number} from ${order.customer_name} - ₱${order.total}`;
        icon = '🆕';
        bgClass = 'border-l-4 border-green-500 bg-green-50';
    } else if (order.status === 'completed' && order.needs_payment_confirmation) {
        // Customer picked up order (cash payment)
        message = `✅ ORDER #${order.order_number} has been picked up by ${order.customer_name}`;
        icon = '✅';
        bgClass = 'border-l-4 border-blue-500 bg-blue-50';
    } else {
        // Don't show notifications for other status changes
        return;
    }
    
    // Create toast notification
    const toast = document.createElement('div');
    toast.className = `fixed top-20 right-4 ${bgClass} shadow-xl rounded-lg p-4 z-50 animate-slide-down max-w-md`;
    toast.innerHTML = `
        <div class="flex items-start gap-3">
            <div class="text-2xl">${icon}</div>
            <div class="flex-1">
                <p class="font-bold text-gray-800">Order Update</p>
                <p class="text-sm text-gray-600">${message}</p>
                <p class="text-xs text-gray-400 mt-1">${new Date().toLocaleTimeString()}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
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
    
    // Also show browser notification
    if (Notification.permission === 'granted') {
        new Notification('Admin Order Update', {
            body: message,
            icon: '/favicon.ico'
        });
    }
}

// Request notification permission
if (Notification.permission === 'default' && window.location.pathname.includes('/admin')) {
    Notification.requestPermission();
}

// Start polling when page loads
document.addEventListener('DOMContentLoaded', function() {
    startAdminPolling();
});

// Stop polling when leaving the page
window.addEventListener('beforeunload', function() {
    if (adminPollingInterval) clearInterval(adminPollingInterval);
});

// ========== IMAGE PREVIEW FUNCTIONS ==========
// Generic image preview handler
window.initImagePreview = function(imageInputId, previewContainerId, previewImgId, removeCheckboxId = null) {
    const imageInput = document.getElementById(imageInputId);
    const previewContainer = document.getElementById(previewContainerId);
    const previewImg = document.getElementById(previewImgId);
    const removeCheckbox = removeCheckboxId ? document.getElementById(removeCheckboxId) : null;
    
    if (!imageInput) return;
    
    // Remove existing listener to prevent duplicates
    if (imageInput.hasPreviewListener) return;
    imageInput.hasPreviewListener = true;
    
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            // Check file size (2MB limit)
            if (file.size > 2 * 1024 * 1024) {
                window.showToast('File is too large. Maximum size is 2MB.', true);
                this.value = '';
                if (previewContainer) previewContainer.style.display = 'none';
                return;
            }
            
            // Check file type
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                window.showToast('Invalid file type. Please upload JPG, PNG, GIF, or WEBP.', true);
                this.value = '';
                if (previewContainer) previewContainer.style.display = 'none';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                if (previewImg) previewImg.src = e.target.result;
                if (previewContainer) previewContainer.style.display = 'block';
                
                // Uncheck remove image if it was checked
                if (removeCheckbox) {
                    removeCheckbox.checked = false;
                }
            }
            reader.readAsDataURL(file);
        } else {
            if (previewContainer) previewContainer.style.display = 'none';
        }
    });
    
    // Handle remove image checkbox
    if (removeCheckbox && !removeCheckbox.hasRemoveListener) {
        removeCheckbox.hasRemoveListener = true;
        removeCheckbox.addEventListener('change', function() {
            if (this.checked) {
                // Clear any selected file
                if (imageInput) imageInput.value = '';
                if (previewContainer) previewContainer.style.display = 'none';
            }
        });
    }
};

// Clear image function
window.clearImage = function(imageInputId = 'imageInput', previewContainerId = 'imagePreviewContainer') {
    const imageInput = document.getElementById(imageInputId);
    const previewContainer = document.getElementById(previewContainerId);
    
    if (imageInput) imageInput.value = '';
    if (previewContainer) previewContainer.style.display = 'none';
};

// Initialize image previews when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // For create pages
    window.initImagePreview('imageInput', 'imagePreviewContainer', 'imagePreview');
    
    // For edit pages
    window.initImagePreview('imageInput', 'imagePreviewContainer', 'imagePreview', 'remove_image');
});

// ========== MAKE FUNCTIONS GLOBALLY AVAILABLE ==========
window.showLoader = showLoader;
window.hideLoader = hideLoader;
window.showToast = showToast;
window.initImagePreview = initImagePreview;
window.clearImage = clearImage;

// ========== SAFETY TIMEOUT - Hide loader if page takes too long ==========
setTimeout(function() {
    if (loader && loader.style.display !== 'none') {
        hideLoader();
    }
}, 15000);

// ========== ADD LOADER STYLES IF NOT PRESENT ==========
if (!document.getElementById('loader-styles')) {
    const style = document.createElement('style');
    style.id = 'loader-styles';
    style.textContent = `
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes slide-down {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-slide-down {
            animation: slide-down 0.3s ease-out;
        }
        #loader {
            transition: opacity 0.3s ease-out;
        }
    `;
    document.head.appendChild(style);
}

// ========== HANDLE TURBO/LIVEWIRE LINKS (if using) ==========
document.addEventListener('turbo:before-visit', function() {
    showLoader();
});

document.addEventListener('turbo:load', function() {
    hideLoader();
});

// ========== HANDLE FETCH REQUESTS ==========
const originalFetch = window.fetch;
window.fetch = function(...args) {
    // Don't show loader for polling requests
    const url = args[0];
    if (typeof url === 'string' && url.includes('/check-updates')) {
        return originalFetch.apply(this, args);
    }
    
    // Show loader for fetch requests
    showLoader();
    
    return originalFetch.apply(this, args)
        .then(response => {
            hideLoader();
            return response;
        })
        .catch(error => {
            hideLoader();
            throw error;
        });
};