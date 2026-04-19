@extends('layouts.home')

@section('title', 'Login')

@section('content')
<div class="relative min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] overflow-hidden flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <!-- Decorative Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-20 -right-20 w-96 h-96 bg-[#ea5a47] opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-[#c53030] opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-to-r from-[#ea5a47]/5 to-[#c53030]/5 rounded-full blur-3xl"></div>
        <div class="absolute top-20 left-20 text-7xl opacity-10 transform rotate-12">🍽️</div>
        <div class="absolute bottom-20 right-20 text-7xl opacity-10 transform -rotate-12">🥘</div>
        <div class="absolute top-40 right-40 text-6xl opacity-10">🍲</div>
        <div class="absolute bottom-40 left-40 text-6xl opacity-10">🥗</div>
        <div class="absolute top-60 left-60 text-5xl opacity-10">🍜</div>
        <div class="absolute bottom-60 right-60 text-5xl opacity-10">🍛</div>
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, #ea5a47 1px, transparent 0); background-size: 40px 40px; opacity: 0.02;"></div>
        @for($i = 0; $i < 10; $i++)
            <div class="absolute w-2 h-2 bg-[#ea5a47] rounded-full opacity-10 animate-float" 
                 style="top: {{ rand(0, 100) }}%; left: {{ rand(0, 100) }}%; animation-delay: {{ rand(0, 5) }}s;"></div>
        @endfor
    </div>

    <div class="relative z-10 w-full max-w-md">
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl p-8 border border-white/20 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-32 h-32 bg-gradient-to-br from-[#ea5a47] to-[#c53030] opacity-5 rounded-br-3xl"></div>
            <div class="absolute bottom-0 right-0 w-32 h-32 bg-gradient-to-tl from-[#ea5a47] to-[#c53030] opacity-5 rounded-tl-3xl"></div>
            
            <div class="text-center mb-8 relative">
                <div class="flex justify-center">
                    <div class="relative">
                        <img src="{{ asset('images/logo.png') }}" 
                        alt="2Dine-In Logo" 
                        class="w-40 h-40 object-contain">
                    </div>
                </div>
                <h1 class="text-3xl font-black text-gray-800">
                    Welcome to <span class="text-[#ea5a47]">2Dine-In</span>
                </h1>
                <p class="text-gray-500 mt-2">Login to your account to continue</p>
            </div>

            <!-- SIMPLE FORM - NO AJAX, just regular form submission -->
            <form action="{{ route('login') }}" method="POST" class="space-y-6 relative">
                @csrf
                
                <div class="group">
                    <label class="block text-gray-700 font-medium mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Username or Email
                    </label>
                    <input type="text" 
                           name="username" 
                           value="{{ old('username') }}"
                           class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white focus:ring-0 outline-none transition-all duration-300"
                           placeholder="Enter your username or email"
                           required>
                </div>

                <div class="group">
                    <label class="block text-gray-700 font-medium mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Password
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="password"
                               name="password" 
                               class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white focus:ring-0 outline-none transition-all duration-300 pr-12"
                               placeholder="Enter your password"
                               required>
                        <button type="button" 
                                onclick="togglePassword('password', this)" 
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-[#ea5a47] transition-colors">
                            <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="text-right">
                </div>

                <button type="submit"
                        id="login-btn"
                        class="w-full bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-bold py-3 px-4 rounded-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-[1.02] hover:from-[#c53030] hover:to-[#ea5a47] flex items-center justify-center gap-2 group">
                    <!-- Default state -->
                    <svg id="login-btn-icon" class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    <!-- Spinner (hidden by default) -->
                    <svg id="login-btn-spinner" class="w-5 h-5 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span id="login-btn-text">Login</span>
                </button>
            </form>

            <!-- Divider -->

            <div class="text-center mt-6 pt-4 border-t border-gray-100">
                <p class="text-gray-600">
                    Don't have an account?
                    <a href="{{ route('signup.form') }}" class="text-[#ea5a47] font-semibold hover:text-[#c53030] transition-colors hover:underline">
                        Sign up here
                    </a>
                </p>
            </div>

            <div class="mt-4 p-3 bg-gray-50 rounded-lg text-xs text-gray-500">
                <p class="font-medium mb-1">Demo Credentials:</p>
                <p>Username: admin / Password: admin123</p>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    
    if (input.type === 'password') {
        input.type = 'text';
        button.innerHTML = `<svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>`;
        button.setAttribute('title', 'Hide password');
    } else {
        input.type = 'password';
        button.innerHTML = `<svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
            </svg>`;
        button.setAttribute('title', 'Show password');
    }
}

// Real-time blur validation
(function () {
    const usernameInput = document.querySelector('input[name="username"]');

    function showFieldError(input, message) {
        clearFieldError(input);
        input.classList.add('border-red-500', 'bg-red-50');
        const err = document.createElement('p');
        err.className = 'field-error text-red-500 text-xs mt-1';
        err.textContent = message;
        input.parentElement.appendChild(err);
    }

    function clearFieldError(input) {
        input.classList.remove('border-red-500', 'bg-red-50');
        const prev = input.parentElement.querySelector('.field-error');
        if (prev) prev.remove();
    }

    if (usernameInput) {
        usernameInput.addEventListener('blur', function () {
            if (!this.value.trim()) {
                showFieldError(this, 'Please enter your username or email.');
            } else {
                clearFieldError(this);
            }
        });
        usernameInput.addEventListener('input', function () {
            if (this.value.trim()) clearFieldError(this);
        });
    }
})();

document.querySelector('form').addEventListener('submit', function () {
    const btn     = document.getElementById('login-btn');
    const icon    = document.getElementById('login-btn-icon');
    const spinner = document.getElementById('login-btn-spinner');
    const text    = document.getElementById('login-btn-text');

    btn.disabled = true;
    btn.classList.add('opacity-80', 'cursor-not-allowed');
    btn.classList.remove('hover:scale-[1.02]');
    icon.classList.add('hidden');
    spinner.classList.remove('hidden');
    text.textContent = 'Logging in...';
});
</script>

<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    .animate-float { animation: float 6s ease-in-out infinite; }
    
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slideDown { animation: slideDown 0.3s ease-out; }
</style>
@endsection