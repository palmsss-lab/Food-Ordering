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

function showLoader() { showProgress(); }
function hideLoader() { finishProgress(); }

// ========== PAGE LOAD HANDLING ==========
document.addEventListener('DOMContentLoaded', function() {
    finishProgress();
});

window.addEventListener('pageshow', function(event) {
    if (event.persisted) finishProgress();
});

// ========== HANDLE LINK CLICKS ==========
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

// ========== HANDLE FORM SUBMISSIONS ==========
document.addEventListener('submit', function(e) {
    const form = e.target;
    if (form.hasAttribute('data-no-loader')) return;
    showProgress();
});

// ========== GLOBAL TOAST FUNCTION ==========
window.showToast = function(message, isError = false, duration = 3000) {
    let toast = document.getElementById('global-toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'global-toast';
        toast.className = 'fixed top-4 right-4 z-50 transform transition-all duration-300 opacity-0 -translate-y-5';
        document.body.appendChild(toast);
    }

    const bgClass = isError ? 'bg-red-600' : 'bg-green-600';
    const iconSvg = isError
        ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';

    const toastMessage = document.createElement('div');
    toastMessage.className = `${bgClass} shadow-xl rounded-lg p-4 mb-3 animate-slide-down max-w-md`;
    toastMessage.innerHTML = `
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0 text-white">${iconSvg}</div>
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

    setTimeout(() => {
        if (toastMessage?.remove) toastMessage.remove();
        if (toast.children.length === 0) {
            toast.classList.remove('opacity-100', 'translate-y-0');
            toast.classList.add('opacity-0', '-translate-y-5');
        }
    }, duration);
};

// ========== MAKE FUNCTIONS GLOBALLY AVAILABLE ==========
window.showLoader = showLoader;
window.hideLoader = hideLoader;

// ========== SAFETY TIMEOUT ==========
setTimeout(function() {
    if (loader && loader.style.display !== 'none') hideLoader();
}, 15000);

// ========== ADD LOADER STYLES IF NOT PRESENT ==========
if (!document.getElementById('loader-styles')) {
    const style = document.createElement('style');
    style.id = 'loader-styles';
    style.textContent = `
        @keyframes slide-down {
            from { opacity: 0; transform: translateY(-20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-down { animation: slide-down 0.3s ease-out; }
        #loader { transition: opacity 0.3s ease-out; }
    `;
    document.head.appendChild(style);
}
