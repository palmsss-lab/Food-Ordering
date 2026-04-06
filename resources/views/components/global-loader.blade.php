   <!-- Global Loader - Always visible initially, hides on page load -->
    <div id="loader" class="fixed inset-0 bg-white/80 backdrop-blur-sm flex flex-col items-center justify-center z-50 transition-all duration-300">
        <div class="relative flex items-center justify-center">
            <!-- Orange spinning ring -->
            <div class="absolute w-28 h-28 border-4 border-orange-200 border-t-orange-500 rounded-full animate-spin"></div>
            <!-- Logo -->
            <img src="/images/logo.png" alt="Logo" class="w-24 h-24 rounded-full object-cover shadow-md">
        </div>
        <p class="mt-4 text-orange-500 font-semibold tracking-wide animate-pulse">
            Loading...
        </p>
    </div>

    <!-- Global Toast - Single Instance -->
    <div id="toast" 
         class="fixed top-5 left-1/2 -translate-x-1/2 px-6 py-3 rounded-lg shadow-lg pointer-events-none
                opacity-0 -translate-y-5 transition-all duration-300 z-50 min-w-[300px] text-center text-white bg-green-600">
        <div class="flex items-center justify-center gap-2">
            <span id="toast-icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </span>
            <span id="toast-message">Item added to cart ✓</span>
        </div>
    </div>