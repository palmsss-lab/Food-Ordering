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
    
    <main class=" mx-auto">
        @yield('content')
    </main>

     <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>

      @vite(['resources/js/loader-login-signup-home.js'])

      <script>
             @if($errors->any())
            document.addEventListener('DOMContentLoaded', function()    {
                @foreach($errors->all() as $error)
                    window.showToast('{{ $error }}', true);
                @endforeach
            });
            @endif
            
            @if(session('success'))
                document.addEventListener('DOMContentLoaded', function() {
                    window.showToast('{{ session('success') }}', false);
                });
            @endif
            
            @if(session('error'))
                document.addEventListener('DOMContentLoaded', function() {
                    window.showToast('{{ session('error') }}', true);
                });
            @endif
      </script>

</body>
</html>

