<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleTimeout
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    private static function timeoutPage(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taking Too Long — 2Dine-In</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes spin { to { transform: rotate(360deg); } }
        .spin { animation: spin 1.4s linear infinite; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.5s ease both; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] flex items-center justify-center px-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-10 text-center fade-up">

        <!-- Icon -->
        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-orange-100 flex items-center justify-center">
            <svg class="w-10 h-10 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <!-- Heading -->
        <h1 class="text-2xl font-black text-gray-800 mb-2">That Took Too Long</h1>
        <p class="text-gray-500 text-sm leading-relaxed mb-8">
            The server is taking longer than expected to respond.<br>
            This could be a heavy operation or a temporary slowdown.
        </p>

        <!-- Actions -->
        <div class="flex flex-col gap-3">
            <button onclick="window.location.reload()"
                    class="w-full py-3 bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-bold rounded-2xl
                           hover:opacity-90 active:scale-95 transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Try Again
            </button>
            <button onclick="window.history.back()"
                    class="w-full py-3 bg-gray-100 text-gray-600 font-semibold rounded-2xl
                           hover:bg-gray-200 active:scale-95 transition-all text-sm">
                Go Back
            </button>
        </div>

        <!-- Auto-retry countdown -->
        <p class="mt-6 text-xs text-gray-400">
            Auto-retrying in <span id="countdown" class="font-semibold text-[#ea5a47]">30</span>s
        </p>
    </div>

    <script>
        let seconds = 30;
        const el = document.getElementById('countdown');
        const timer = setInterval(() => {
            seconds--;
            el.textContent = seconds;
            if (seconds <= 0) {
                clearInterval(timer);
                window.location.reload();
            }
        }, 1000);
    </script>
</body>
</html>
HTML;
    }
}
