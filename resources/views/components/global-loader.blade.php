{{-- Top progress bar — shown on navigation/form submit instead of a full-page overlay --}}
<div id="progress-bar"
     style="position:fixed;top:0;left:0;z-index:9999;height:3px;width:0%;
            background:linear-gradient(to right,#ea5a47,#f97316);
            transition:width 0.25s ease,opacity 0.3s ease;
            opacity:0;pointer-events:none;box-shadow:0 0 8px rgba(234,90,71,0.6);">
</div>

{{-- Global Toast — unchanged, used for success/error flash messages --}}
<div id="toast"
     class="fixed top-5 left-1/2 -translate-x-1/2 px-6 py-3 rounded-lg shadow-lg pointer-events-none
            opacity-0 -translate-y-5 transition-all duration-300 z-50 min-w-[300px] text-center text-white bg-green-600">
    <div class="flex items-center justify-center gap-2">
        <span id="toast-icon">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </span>
        <span id="toast-message">Success</span>
    </div>
</div>
