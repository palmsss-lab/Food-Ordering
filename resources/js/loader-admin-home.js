// ========== GLOBAL LOADER FUNCTIONS ==========
let isTransitioning = false;
let loaderTimeout;
let activeFormSubmissions = 0;

const loader = document.getElementById('loader');

function showLoader() {
    if (loader && !isTransitioning) {
        isTransitioning = true;
        loader.style.display = 'flex';
        loader.classList.remove('opacity-0');
        loader.classList.add('opacity-100');
        document.body.style.overflow = 'hidden';
        
        // Clear any existing timeout
        if (loaderTimeout) clearTimeout(loaderTimeout);
        
        // Safety timeout - hide loader after 15 seconds max
        loaderTimeout = setTimeout(() => {
            if (loader && loader.style.display !== 'none') {
                hideLoader();
            }
        }, 15000);
    }
}

function hideLoader() {
    if (loader) {
        // Don't hide if there are active form submissions
        if (activeFormSubmissions > 0) {
            return;
        }
        
        if (loaderTimeout) clearTimeout(loaderTimeout);
        loader.classList.remove('opacity-100');
        loader.classList.add('opacity-0');
        setTimeout(() => {
            loader.style.display = 'none';
            document.body.style.overflow = '';
            isTransitioning = false;
        }, 300);
    }
}

// ========== PAGE LOAD HANDLING ==========
// Show loader initially
if (loader) {
    showLoader();
}

// Hide loader when page is fully loaded with a delay to ensure content is rendered
window.addEventListener('load', function() {
    // Small delay to ensure all content is rendered
    setTimeout(() => {
        activeFormSubmissions = 0;
        hideLoader();
    }, 500);
});

// Also hide when DOM is ready but with proper handling
document.addEventListener('DOMContentLoaded', function() {
    // Don't hide immediately if there were form submissions
    setTimeout(() => {
        if (activeFormSubmissions === 0) {
            hideLoader();
        }
    }, 300);
});

// ========== HANDLE ADMIN LINK CLICKS - FIXED ==========
document.querySelectorAll('a').forEach(link => {
    // Skip external links and special cases
    if (link.hostname !== window.location.hostname) return;
    if (link.hasAttribute('data-no-loader')) return;
    if (link.getAttribute('href') === '#') return;
    if (link.getAttribute('href')?.startsWith('#')) return;
    if (link.getAttribute('href')?.startsWith('javascript:')) return;
    if (link.target === '_blank') return;
    if (link.hasAttribute('download')) return;
    
    // Remove existing listener to prevent duplicates
    const newLink = link.cloneNode(true);
    link.parentNode.replaceChild(newLink, link);
    
    newLink.addEventListener('click', function(e) {
        // Don't show loader for modifier keys (Ctrl+Click, etc)
        if (e.ctrlKey || e.metaKey || e.shiftKey) return;
        
        // Check if already transitioning
        if (isTransitioning) {
            e.preventDefault();
            return;
        }
        
        // Show loader
        showLoader();
    });
});

// ========== HANDLE ADMIN FORM SUBMISSIONS - FIXED FOR GET FORMS ==========
document.querySelectorAll('form').forEach(form => {
    // Skip forms with data-no-loader
    if (form.hasAttribute('data-no-loader')) return;
    
    // Remove existing listener to prevent duplicates
    const newForm = form.cloneNode(true);
    form.parentNode.replaceChild(newForm, form);
    
    newForm.addEventListener('submit', function(e) {
        // Prevent double submission
        if (isTransitioning) {
            e.preventDefault();
            return;
        }
        
        // Increment active form submissions counter
        activeFormSubmissions++;
        
        // Show loader
        showLoader();
        
        // Disable submit button to prevent multiple clicks
        const submitBtn = this.querySelector('[type="submit"]');
        if (submitBtn && !submitBtn.disabled) {
            submitBtn.disabled = true;
            
            // Store original text
            if (!submitBtn.hasAttribute('data-original-text')) {
                submitBtn.setAttribute('data-original-text', submitBtn.innerHTML);
            }
            
            // Show loading text on button
            if (!submitBtn.hasAttribute('data-no-loader-text')) {
                submitBtn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Processing...';
            }
        }
        
        // For GET forms (search/filter), we need to ensure loader stays visible
        if (this.method === 'get' || this.method === 'GET') {
            // The form will submit and page will reload
            // We need to keep loader visible until page fully reloads
            // The page load event will hide it
            return true;
        }
    });
});

// ========== HANDLE FILE DOWNLOADS / EXPORTS ==========
// This handles export buttons and file download links
document.querySelectorAll('a[download], a[href*="export"], form[action*="export"]').forEach(element => {
    element.addEventListener('click', function(e) {
        // Show loader
        showLoader();
        
        // Store reference to timeout and focus handler for cleanup
        let exportTimeout = null;
        let focusHandler = null;
        
        // Set timeout to hide loader after 3 seconds (file should start downloading)
        exportTimeout = setTimeout(function() {
            // Decrement active form submissions if this was from a form
            if (activeFormSubmissions > 0) {
                activeFormSubmissions--;
            }
            hideLoader();
            if (focusHandler) {
                window.removeEventListener('focus', focusHandler);
            }
        }, 3000);
        
        // Also hide loader when window regains focus (download complete)
        focusHandler = function() {
            if (activeFormSubmissions > 0) {
                activeFormSubmissions--;
            }
            hideLoader();
            if (exportTimeout) {
                clearTimeout(exportTimeout);
            }
            window.removeEventListener('focus', focusHandler);
        };
        window.addEventListener('focus', focusHandler);
        
        // Allow the download to proceed
        return true;
    });
});

// Also handle any dynamically added export buttons
const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        if (mutation.addedNodes.length) {
            document.querySelectorAll('a[download], a[href*="export"], form[action*="export"]').forEach(element => {
                if (!element.hasAttribute('data-export-handler')) {
                    element.setAttribute('data-export-handler', 'true');
                    element.addEventListener('click', function(e) {
                        showLoader();
                        
                        let exportTimeout = null;
                        let focusHandler = null;
                        
                        exportTimeout = setTimeout(function() {
                            if (activeFormSubmissions > 0) {
                                activeFormSubmissions--;
                            }
                            hideLoader();
                            if (focusHandler) {
                                window.removeEventListener('focus', focusHandler);
                            }
                        }, 3000);
                        
                        focusHandler = function() {
                            if (activeFormSubmissions > 0) {
                                activeFormSubmissions--;
                            }
                            hideLoader();
                            if (exportTimeout) {
                                clearTimeout(exportTimeout);
                            }
                            window.removeEventListener('focus', focusHandler);
                        };
                        window.addEventListener('focus', focusHandler);
                        
                        return true;
                    });
                }
            });
        }
    });
});

observer.observe(document.body, { childList: true, subtree: true });

// ========== HANDLE BACK/FORWARD CACHE ==========
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        // Reset counters
        activeFormSubmissions = 0;
        isTransitioning = false;
        
        // Hide loader
        if (loader && loader.style.display !== 'none') {
            loader.classList.remove('opacity-100');
            loader.classList.add('opacity-0');
            setTimeout(() => {
                loader.style.display = 'none';
                document.body.style.overflow = '';
            }, 300);
        }
        
        // Re-enable any disabled buttons
        document.querySelectorAll('button[disabled], input[type="submit"][disabled]').forEach(btn => {
            btn.disabled = false;
            const originalText = btn.getAttribute('data-original-text');
            if (originalText) {
                btn.innerHTML = originalText;
                btn.removeAttribute('data-original-text');
            }
        });
    }
});

// ========== HANDLE BEFORE UNLOAD ==========
window.addEventListener('beforeunload', function() {
    // Ensure loader is visible during navigation
    if (loader && isTransitioning) {
        loader.style.display = 'flex';
        loader.classList.remove('opacity-0');
        loader.classList.add('opacity-100');
    }
});

// ========== GLOBAL TOAST FUNCTION ==========
window.showToast = function(message, isError = false) {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toast-message');
    const toastIcon = document.getElementById('toast-icon');
    
    if (!toast) return;
    
    toastMessage.textContent = message;
    
    if (isError) {
        toast.classList.remove('bg-green-600');
        toast.classList.add('bg-red-600');
        toastIcon.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
    } else {
        toast.classList.remove('bg-red-600');
        toast.classList.add('bg-green-600');
        toastIcon.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
    }
    
    toast.classList.remove('opacity-0', '-translate-y-5');
    toast.classList.add('opacity-100', 'translate-y-0');
    
    setTimeout(() => {
        toast.classList.remove('opacity-100', 'translate-y-0');
        toast.classList.add('opacity-0', '-translate-y-5');
    }, 3000);
};

// ========== ADMIN POLLING FOR ORDER UPDATES ==========
let adminPollingInterval;
let adminLastUpdateTime = localStorage.getItem('admin_last_order_update') || Date.now();

function startAdminPolling() {
    if (adminPollingInterval) clearInterval(adminPollingInterval);
    
    // Only start polling on admin pages
    if (!window.location.pathname.includes('/admin')) return;
    
    adminPollingInterval = setInterval(() => {
        // Don't poll during transitions
        if (!isTransitioning && activeFormSubmissions === 0) {
            checkAdminOrderUpdates();
        }
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
            if (!isTransitioning && activeFormSubmissions === 0) {
                setTimeout(() => {
                    if (!isTransitioning && activeFormSubmissions === 0) {
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
// For create.blade.php
const imageInput = document.getElementById('imageInput');
if (imageInput && !imageInput.hasListener) {
    imageInput.hasListener = true;
    imageInput.addEventListener('change', function(e) {
        const previewContainer = document.getElementById('imagePreviewContainer');
        const previewImg = document.getElementById('imagePreview');
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
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                window.showToast('Invalid file type. Please upload JPG, PNG, or GIF.', true);
                this.value = '';
                if (previewContainer) previewContainer.style.display = 'none';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                if (previewImg) previewImg.src = e.target.result;
                if (previewContainer) previewContainer.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            if (previewContainer) previewContainer.style.display = 'none';
        }
    });
}

// For edit.blade.php with remove checkbox
const editImageInput = document.getElementById('imageInput');
const removeCheckbox = document.getElementById('remove_image');

if (editImageInput && !editImageInput.hasEditListener) {
    editImageInput.hasEditListener = true;
    editImageInput.addEventListener('change', function(e) {
        const previewContainer = document.getElementById('imagePreviewContainer');
        const previewImg = document.getElementById('imagePreview');
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
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                window.showToast('Invalid file type. Please upload JPG, PNG, or GIF.', true);
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
}

// Clear image function
window.clearImage = function() {
    const imageInput = document.getElementById('imageInput');
    const previewContainer = document.getElementById('imagePreviewContainer');
    
    if (imageInput) imageInput.value = '';
    if (previewContainer) previewContainer.style.display = 'none';
};

// Handle remove image checkbox
if (removeCheckbox && !removeCheckbox.hasListener) {
    removeCheckbox.hasListener = true;
    removeCheckbox.addEventListener('change', function() {
        const imageInput = document.getElementById('imageInput');
        const previewContainer = document.getElementById('imagePreviewContainer');
        
        if (this.checked) {
            // Clear any selected file
            if (imageInput) imageInput.value = '';
            if (previewContainer) previewContainer.style.display = 'none';
        }
    });
}

// ========== MAKE FUNCTIONS GLOBALLY AVAILABLE ==========
window.showLoader = showLoader;
window.hideLoader = hideLoader;
window.showToast = showToast;

// ========== SAFETY TIMEOUT - Hide loader if page takes too long ==========
setTimeout(function() {
    if (loader && loader.style.display !== 'none') {
        hideLoader();
    }
}, 15000);