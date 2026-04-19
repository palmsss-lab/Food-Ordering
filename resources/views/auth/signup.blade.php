@extends('layouts.home')

@section('title', 'Sign Up')

@section('content')
<div class="relative min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] overflow-hidden py-12 px-4 sm:px-6 lg:px-8">
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

    <div class="relative z-10 max-w-2xl mx-auto">
        <!-- Main Card -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl p-8 md:p-10 border border-white/20 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-32 h-32 bg-gradient-to-br from-[#ea5a47] to-[#c53030] opacity-5 rounded-br-3xl"></div>
            <div class="absolute bottom-0 right-0 w-32 h-32 bg-gradient-to-tl from-[#ea5a47] to-[#c53030] opacity-5 rounded-tl-3xl"></div>
            
            <!-- Header -->
            <div class="text-center relative">
                <div class="flex justify-center">
                    <div class="relative">
                        <img src="{{ asset('images/logo.png') }}" 
                        alt="2Dine-In Logo" 
                        class="w-40 h-40 object-contain">
                    </div>
                </div>
                <h1 class="text-3xl font-black text-gray-800">
                    Create <span class="text-[#ea5a47]">Account</span>
                </h1>
                <p class="text-gray-500 mt-2">Join us and start ordering your favorite dishes</p>
            </div>

            <!-- Sign Up Form -->
            <form id="signup-form" method="POST" class="space-y-4 relative">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <div class="group">
                            <label class="block text-gray-700 font-medium mb-2 flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name"
                                   value="{{ old('name') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white focus:ring-0 outline-none transition-all duration-300"
                                   placeholder="Enter your full name"
                                   required>
                        </div>

                        <div class="group">
                            <label class="block text-gray-700 font-medium mb-2 flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email"
                                   value="{{ old('email') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white focus:ring-0 outline-none transition-all duration-300"
                                   placeholder="Enter your email"
                                   required>
                        </div>

                        <div class="group">
                            <label class="block text-gray-700 font-medium mb-2 flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                                Username <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="username" 
                                   id="username"
                                   value="{{ old('username') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white focus:ring-0 outline-none transition-all duration-300"
                                   placeholder="Choose a username"
                                   required>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <div class="group">
                            <label class="block text-gray-700 font-medium mb-2 flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                Phone Number <span class="text-gray-400 font-normal">(Optional)</span>
                            </label>
                            <input type="text" 
                                   name="phone" 
                                   id="phone"
                                   value="{{ old('phone') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white focus:ring-0 outline-none transition-all duration-300"
                                   placeholder="Enter your phone number">
                        </div>

                        <div class="group">
                            <label class="block text-gray-700 font-medium mb-2 flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Address <span class="text-gray-400 font-normal">(Optional)</span>
                            </label>
                            <textarea name="address" 
                                      id="address"
                                      rows="3"
                                      class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white focus:ring-0 outline-none transition-all duration-300"
                                      placeholder="Enter your address">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Password Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                    <div class="group">
                        <label class="block text-gray-700 font-medium mb-2 flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="password"
                                   name="password" 
                                   class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white focus:ring-0 outline-none transition-all duration-300 pr-12"
                                   placeholder="Create a password"
                                   required>
                            <button type="button" 
                                    onclick="togglePassword('password', this)" 
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-[#ea5a47] transition-colors">
                                <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Minimum 6 characters</p>
                    </div>

                    <div class="group">
                        <label class="block text-gray-700 font-medium mb-2 flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="password_confirmation"
                                   name="password_confirmation" 
                                   class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white focus:ring-0 outline-none transition-all duration-300 pr-12"
                                   placeholder="Confirm your password"
                                   required>
                            <button type="button" 
                                    onclick="togglePassword('password_confirmation', this)" 
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-[#ea5a47] transition-colors">
                                <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit"
                        id="signup-btn"
                        class="w-full bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-bold py-4 px-4 rounded-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-[1.02] flex items-center justify-center gap-2 group mt-4">
                    <!-- Default icon -->
                    <svg id="signup-btn-icon" class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    <!-- Spinner (hidden by default) -->
                    <svg id="signup-btn-spinner" class="w-5 h-5 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span id="signup-btn-text">Create Account</span>
                </button>
            </form>

            <!-- Divider -->

            <div class="text-center mt-6 pt-4 border-t border-gray-100">
                <p class="text-gray-600">
                    Already have an account?
                    <a href="{{ route('login.form') }}" class="text-[#ea5a47] font-semibold hover:text-[#c53030] transition-colors hover:underline">
                        Sign in here
                    </a>
                </p>
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

// ========== REAL-TIME BLUR VALIDATION ==========
document.addEventListener('DOMContentLoaded', function () {
    function showFieldError(input, message) {
        clearFieldError(input);
        input.classList.add('border-red-500', 'bg-red-50');
        const err = document.createElement('p');
        err.className = 'field-inline-error text-red-500 text-xs mt-1';
        err.textContent = message;
        // Insert after the input's parent wrapper (handles password relative div)
        const wrapper = input.closest('.relative') || input.parentElement;
        wrapper.parentElement.appendChild(err);
    }

    function clearFieldError(input) {
        input.classList.remove('border-red-500', 'bg-red-50');
        const wrapper = input.closest('.relative') || input.parentElement;
        const prev = wrapper.parentElement.querySelector('.field-inline-error');
        if (prev) prev.remove();
    }

    // Email format
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', function () {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !re.test(this.value)) {
                showFieldError(this, 'Please enter a valid email address (e.g. user@example.com).');
            } else {
                clearFieldError(this);
            }
        });
        emailInput.addEventListener('input', function () { if (this.value) clearFieldError(this); });
    }

    // Password minimum length
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('blur', function () {
            if (this.value && this.value.length < 6) {
                showFieldError(this, 'Password must be at least 6 characters long.');
            } else {
                clearFieldError(this);
            }
        });
        passwordInput.addEventListener('input', function () {
            if (this.value.length >= 6) clearFieldError(this);
        });
    }

    // Confirm password match
    const confirmInput = document.getElementById('password_confirmation');
    if (confirmInput) {
        confirmInput.addEventListener('blur', function () {
            const pw = document.getElementById('password');
            if (this.value && pw && this.value !== pw.value) {
                showFieldError(this, 'Passwords do not match.');
            } else {
                clearFieldError(this);
            }
        });
        confirmInput.addEventListener('input', function () {
            const pw = document.getElementById('password');
            if (pw && this.value === pw.value) clearFieldError(this);
        });
    }
});

// AJAX Form Submission - Shows ALL errors with proper alignment
document.addEventListener('DOMContentLoaded', function() {
    const signupForm = document.getElementById('signup-form');
    let isSubmitting = false;
    
    const signupBtn     = document.getElementById('signup-btn');
    const signupIcon    = document.getElementById('signup-btn-icon');
    const signupSpinner = document.getElementById('signup-btn-spinner');
    const signupBtnText = document.getElementById('signup-btn-text');

    function setSignupLoading(loading) {
        signupBtn.disabled = loading;
        signupBtn.classList.toggle('opacity-80', loading);
        signupBtn.classList.toggle('cursor-not-allowed', loading);
        signupBtn.classList.toggle('hover:scale-[1.02]', !loading);
        signupIcon.classList.toggle('hidden', loading);
        signupSpinner.classList.toggle('hidden', !loading);
        signupBtnText.textContent = loading ? 'Creating Account...' : 'Create Account';
    }

    if (signupForm) {
        signupForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            if (isSubmitting) return;
            isSubmitting = true;
            setSignupLoading(true);

            const formData = new FormData(signupForm);
            const formDataObj = {};
            formData.forEach((value, key) => {
                formDataObj[key] = value;
            });

            if (window.showLoader) window.showLoader();
            
            try {
                const response = await fetch('{{ route("signup") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(formDataObj)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    if (window.showToast) {
                        window.showToast(data.message, false);
                    }
                    
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);
                } else {
                    if (window.hideLoader) window.hideLoader();
                    setSignupLoading(false);

                    // Show ALL errors with proper formatting and alignment
                    if (window.showToast && data.errors && data.errors.length > 0) {
                        // Create an HTML formatted error list for better alignment
                        const errorHtml = `
                            <div class="text-left">
                                <div class="font-semibold mb-2 text-center">Kindly correct the following items:</div>
                                <div class="space-y-1">
                                    ${data.errors.map(err => `<div class="flex items-start gap-2">
                                        <span class="text-red-300 mt-0.5">•</span>
                                        <span class="text-sm">${err}</span>
                                    </div>`).join('')}
                                </div>
                            </div>
                        `;
                        
                        // Custom toast for multiple errors
                        showErrorToast(errorHtml);
                    } else if (window.showToast) {
                        window.showToast(data.message || 'Please check your information and try again.', true);
                    }
                    
                    // Highlight ALL fields with errors
                    if (data.errors) {
                        const errorMessages = data.errors.join(' ').toLowerCase();
                        
                        // Clear previous highlights
                        document.querySelectorAll('.border-red-500').forEach(el => {
                            el.classList.remove('border-red-500', 'bg-red-50');
                        });
                        
                        if (errorMessages.includes('name')) {
                            document.getElementById('name')?.classList.add('border-red-500', 'bg-red-50');
                        }
                        if (errorMessages.includes('email')) {
                            document.getElementById('email')?.classList.add('border-red-500', 'bg-red-50');
                        }
                        if (errorMessages.includes('username')) {
                            document.getElementById('username')?.classList.add('border-red-500', 'bg-red-50');
                        }
                        if (errorMessages.includes('password')) {
                            document.getElementById('password')?.classList.add('border-red-500', 'bg-red-50');
                            document.getElementById('password_confirmation')?.classList.add('border-red-500', 'bg-red-50');
                        }
                        
                        // Remove highlighting after 10 seconds to give users enough time to fix fields
                        setTimeout(() => {
                            document.querySelectorAll('.border-red-500').forEach(el => {
                                el.classList.remove('border-red-500', 'bg-red-50');
                            });
                        }, 10000);
                    }
                    
                    isSubmitting = false;
                }
            } catch (error) {
                console.error('Signup error:', error);
                if (window.hideLoader) window.hideLoader();
                setSignupLoading(false);
                if (window.showToast) {
                    const msg = navigator.onLine
                        ? 'Our server had a hiccup. Please try again in a moment.'
                        : 'No internet connection detected. Please check your network and retry.';
                    window.showToast(msg, true);
                }
                isSubmitting = false;
            }
        });
    }
});

// Custom error toast function for multiple errors with proper alignment
function showErrorToast(htmlContent) {
    const toast = document.getElementById('toast');
    if (!toast) return;
    
    // Store original content
    const originalContent = toast.innerHTML;
    
    // Set error styling
    toast.classList.remove('bg-green-600');
    toast.classList.add('bg-red-600');
    
    // Create new content with HTML
    toast.innerHTML = `
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 text-white mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="flex-1">
                ${htmlContent}
            </div>
            <button onclick="this.closest('#toast').classList.remove('opacity-100', 'translate-y-0'); this.closest('#toast').classList.add('opacity-0', '-translate-y-5')" 
                    class="flex-shrink-0 text-white hover:text-gray-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    `;
    
    // Show toast
    toast.classList.remove('opacity-0', '-translate-y-5');
    toast.classList.add('opacity-100', 'translate-y-0');
    
    // Auto hide after 5 seconds (longer for multiple errors)
    setTimeout(() => {
        toast.classList.remove('opacity-100', 'translate-y-0');
        toast.classList.add('opacity-0', '-translate-y-5');
        
        // Restore original content after animation
        setTimeout(() => {
            toast.innerHTML = originalContent;
            toast.classList.remove('bg-red-600');
            toast.classList.add('bg-green-600');
        }, 300);
    }, 5000);
}

// Make the custom toast function available globally
window.showErrorToast = showErrorToast;
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