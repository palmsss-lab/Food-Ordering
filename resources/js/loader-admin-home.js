// ========== PROGRESS BAR ==========
const _bar = document.getElementById('progress-bar');
let _barTimer  = null;
let _barValue  = 0;
let _barActive = false;

function showProgress() {
    if (_barActive) return;
    _barActive = true;
    _barValue  = 0;
    if (_bar) {
        _bar.style.opacity    = '1';
        _bar.style.width      = '0%';
        _bar.style.transition = 'width 0.25s ease, opacity 0.3s ease';
    }
    _tickProgress();
}

function _tickProgress() {
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

// Kept for backward-compat
function showLoader() { showProgress(); }
function hideLoader() { finishProgress(); }

// ========== PAGE LOAD HANDLING ==========
document.addEventListener('DOMContentLoaded', finishProgress);

// ========== HANDLE ADMIN LINK CLICKS ==========
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

// ========== HANDLE ADMIN FORM SUBMISSIONS ==========
const _spinner = `<svg class="inline-block w-4 h-4 animate-spin mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
</svg>`;

document.addEventListener('submit', function(e) {
    const form = e.target;
    if (form.hasAttribute('data-no-loader')) return;

    // Skip GET forms (search/filter) — just show progress bar, no button change
    const method = (form.getAttribute('method') || 'get').toLowerCase();
    const isGetForm = method === 'get';

    showProgress();

    if (isGetForm) return;

    const submitBtn = form.querySelector('[type="submit"]');
    if (!submitBtn || submitBtn.disabled) return;

    // Lock button width before changing content so it doesn't shrink
    submitBtn.style.minWidth = submitBtn.offsetWidth + 'px';

    submitBtn.disabled = true;
    submitBtn.setAttribute('data-original-html', submitBtn.innerHTML);
    submitBtn.classList.add('opacity-80', 'cursor-not-allowed');

    const loadingText = submitBtn.getAttribute('data-loading-text') || 'Processing...';
    submitBtn.innerHTML = `${_spinner}<span>${loadingText}</span>`;
});

// ========== HANDLE FILE DOWNLOADS / EXPORTS ==========
document.querySelectorAll('a[download], a[href*="export"], form[action*="export"]').forEach(element => {
    element.addEventListener('click', function() {
        showProgress();
        let exportTimeout = null;
        let focusHandler  = null;

        exportTimeout = setTimeout(function() {
            finishProgress();
            if (focusHandler) window.removeEventListener('focus', focusHandler);
        }, 3000);

        focusHandler = function() {
            finishProgress();
            if (exportTimeout) clearTimeout(exportTimeout);
            window.removeEventListener('focus', focusHandler);
        };
        window.addEventListener('focus', focusHandler);
        return true;
    });
});

// ========== HANDLE BACK/FORWARD CACHE ==========
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        finishProgress();

        document.querySelectorAll('button[disabled], input[type="submit"][disabled]').forEach(btn => {
            btn.disabled = false;
            btn.classList.remove('opacity-80', 'cursor-not-allowed');
            btn.style.minWidth = '';
            const originalHtml = btn.getAttribute('data-original-html');
            if (originalHtml) {
                btn.innerHTML = originalHtml;
                btn.removeAttribute('data-original-html');
            }
        });
    }
});

// ========== GLOBAL TOAST FUNCTION ==========
window.showToast = function(message, isError = false) {
    const toast      = document.getElementById('toast');
    const toastMsg   = document.getElementById('toast-message');
    const toastIcon  = document.getElementById('toast-icon');

    if (!toast) return;

    toastMsg.textContent = message;

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

// ========== IMAGE PREVIEW FUNCTIONS ==========
const imageInput = document.getElementById('imageInput');
if (imageInput && !imageInput.hasListener) {
    imageInput.hasListener = true;
    imageInput.addEventListener('change', function(e) {
        const previewContainer = document.getElementById('imagePreviewContainer');
        const previewImg       = document.getElementById('imagePreview');
        const file             = e.target.files[0];

        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                window.showToast('File is too large. Maximum size is 2MB.', true);
                this.value = '';
                if (previewContainer) previewContainer.style.display = 'none';
                return;
            }
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
            };
            reader.readAsDataURL(file);
        } else {
            if (previewContainer) previewContainer.style.display = 'none';
        }
    });
}

const removeCheckbox = document.getElementById('remove_image');
if (removeCheckbox && !removeCheckbox.hasListener) {
    removeCheckbox.hasListener = true;
    removeCheckbox.addEventListener('change', function() {
        const imgInput         = document.getElementById('imageInput');
        const previewContainer = document.getElementById('imagePreviewContainer');
        if (this.checked) {
            if (imgInput) imgInput.value = '';
            if (previewContainer) previewContainer.style.display = 'none';
        }
    });
}

window.clearImage = function() {
    const imgInput         = document.getElementById('imageInput');
    const previewContainer = document.getElementById('imagePreviewContainer');
    if (imgInput) imgInput.value = '';
    if (previewContainer) previewContainer.style.display = 'none';
};

// ========== MAKE FUNCTIONS GLOBALLY AVAILABLE ==========
window.showLoader = showLoader;
window.hideLoader = hideLoader;

// ========== ADMIN ORDER TOAST NOTIFICATIONS (Livewire → JS + global poll) ==========
(function () {
    const PENDING_KEY   = 'admin_pending_count';
    const COMPLETED_KEY = 'admin_completed_count';

    function getStored(key) {
        const v = localStorage.getItem(key);
        return v === null ? -1 : parseInt(v, 10);
    }

    // Livewire fires this on the orders page — sync counts so the global
    // poller won't re-fire the same notification after navigation.
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('admin-order-toast', (params) => {
            showAdminOrderToast(params.message, params.type);
            if (params.type === 'new-order' && params.pendingCount !== undefined) {
                localStorage.setItem(PENDING_KEY, params.pendingCount);
            }
            if (params.type === 'picked-up' && params.completedCount !== undefined) {
                localStorage.setItem(COMPLETED_KEY, params.completedCount);
            }
        });
    });

    // Global poller — runs on every admin page so toasts appear anywhere
    function poll() {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
        fetch('/admin/orders/check-updates', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ last_update: Date.now() })
        })
        .then(r => {
            if (!r.ok) { console.warn('[Admin Poller] HTTP', r.status); return null; }
            return r.json();
        })
        .then(data => {
            if (!data?.counts) return;

            const storedPending   = getStored(PENDING_KEY);
            const storedCompleted = getStored(COMPLETED_KEY);
            const currentPending   = data.counts.pending;
            const currentCompleted = data.counts.completed;

            // New orders waiting for confirmation
            if (storedPending >= 0 && currentPending > storedPending) {
                const diff  = currentPending - storedPending;
                const label = diff === 1 ? 'new order' : `${diff} new orders`;
                showAdminOrderToast(`You have ${label} waiting for confirmation!`, 'new-order');
            }

            // Orders picked up by customers
            if (storedCompleted >= 0 && currentCompleted > storedCompleted) {
                const diff  = currentCompleted - storedCompleted;
                const label = diff === 1 ? '1 order has' : `${diff} orders have`;
                showAdminOrderToast(`✅ ${label} been picked up by the customer.`, 'picked-up');
            }

            localStorage.setItem(PENDING_KEY,   currentPending);
            localStorage.setItem(COMPLETED_KEY, currentCompleted);
        })
        .catch(err => console.warn('[Admin Poller] fetch error:', err));
    }

    setTimeout(poll, 2000);
    setInterval(poll, 15000);
})();

function showAdminOrderToast(message, type) {
    const configs = {
        'new-order': {
            border: '#16a34a', iconBg: '#dcfce7', iconColor: '#16a34a',
            iconPath: 'M12 4v16m8-8H4', title: 'New Order Received!', titleColor: '#15803d',
        },
        'picked-up': {
            border: '#2563eb', iconBg: '#dbeafe', iconColor: '#2563eb',
            iconPath: 'M5 13l4 4L19 7', title: 'Order Picked Up', titleColor: '#1d4ed8',
        },
    };

    const cfg  = configs[type] || configs['new-order'];
    const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

    const toast = document.createElement('div');
    toast.setAttribute('data-admin-toast', '');
    toast.style.cssText = `
        position:fixed;right:20px;z-index:9999;
        max-width:360px;width:100%;
        background:white;border-left:4px solid ${cfg.border};
        border-radius:12px;box-shadow:0 20px 60px rgba(0,0,0,0.15);
        padding:14px 16px;display:flex;align-items:flex-start;gap:12px;
        animation:adminToastIn 0.35s cubic-bezier(0.22,1,0.36,1);
    `;

    toast.innerHTML = `
        <div style="width:40px;height:40px;border-radius:50%;background:${cfg.iconBg};
                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="18" height="18" fill="none" stroke="${cfg.iconColor}" stroke-width="2.5"
                 stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="${cfg.iconPath}"/>
            </svg>
        </div>
        <div style="flex:1;min-width:0;">
            <p style="font-weight:700;font-size:13px;color:${cfg.titleColor};margin:0 0 2px;">${cfg.title}</p>
            <p style="font-size:13px;color:#4b5563;margin:0;line-height:1.4;">${message}</p>
            <p style="font-size:11px;color:#9ca3af;margin:4px 0 0;">${time}</p>
        </div>
        <button onclick="this.closest('[data-admin-toast]').remove()"
                style="color:#d1d5db;background:none;border:none;cursor:pointer;padding:0;flex-shrink:0;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    `;

    const existing = document.querySelectorAll('[data-admin-toast]');
    toast.style.top = `${20 + existing.length * 85}px`;
    document.body.appendChild(toast);
    setTimeout(() => { toast?.remove(); }, 8000);
}

if (!document.getElementById('admin-toast-styles')) {
    const s = document.createElement('style');
    s.id = 'admin-toast-styles';
    s.textContent = `
        @keyframes adminToastIn {
            from { opacity:0; transform:translateX(110%); }
            to   { opacity:1; transform:translateX(0); }
        }
    `;
    document.head.appendChild(s);
}
