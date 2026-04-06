<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>@yield('title') | 2Dine-In</title>
</head>


<body class="h-screen">

    <x-global-loader />

    <div class="flex h-screen overflow-hidden">
        <!-- Use the sidebar component with active state -->
         <x-admin-side-bar active="{{ $active ?? '' }}" />
    
        <main class="w-screen"> 
            @yield('content')
        </main>
    </div>

     <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>

     @vite(['resources/js/loader-admin-home.js'])
</body>
</html>

