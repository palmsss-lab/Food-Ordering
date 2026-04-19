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
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>


<body class="h-screen">

    <x-global-loader />

    <x-admin-side-bar active="{{ $active ?? '' }}" />

    <main class="lg:ml-72 xl:ml-80 overflow-y-auto h-screen min-w-0">
        <!-- Mobile top bar with hamburger -->
        <div class="lg:hidden flex items-center gap-3 px-4 py-3 bg-white border-b border-gray-200 sticky top-0 z-20">
            <button onclick="openSidebar()" aria-label="Open navigation menu"
                    class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <span class="font-bold text-gray-800">2Dine-In</span>
        </div>
        @yield('content')
    </main>

     <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js" defer></script>

     @vite(['resources/js/loader-admin-home.js'])
     @livewireScripts

     <script>
     // When Livewire's poll gets a 419 (session/CSRF expired), Livewire v4 calls
     // window.confirm("This page has expired..."). Override it to auto-accept
     // so the page silently reloads without blocking the admin with a dialog.
     (function () {
         var _confirm = window.confirm;
         window.confirm = function (msg) {
             if (typeof msg === 'string' && msg.indexOf('expired') !== -1) {
                 return true; // auto-accept → Livewire will call window.location.reload()
             }
             return _confirm.apply(this, arguments);
         };
     })();
     </script>
</body>
</html>

