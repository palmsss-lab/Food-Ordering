@extends('admin.layouts.home', ['active' => 'password'])

@section('title', 'Admin - Change Password')

@section('content')
<div class="relative min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] overflow-hidden">

        <!-- Main Content Area -->
        <div class="p-4 sm:p-8">
            <div class="max-w-2xl mx-auto">
                    <!-- Change Password Card -->
                    <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl p-5 sm:p-8 border border-white/20 relative overflow-hidden">
                        <!-- Card decorative elements -->
                        <div class="absolute top-0 left-0 w-32 h-32 bg-gradient-to-br from-[#ea5a47] to-[#c53030] opacity-5 rounded-br-3xl"></div>
                        <div class="absolute bottom-0 right-0 w-32 h-32 bg-gradient-to-tl from-[#ea5a47] to-[#c53030] opacity-5 rounded-tl-3xl"></div>
                        
                        <!-- Header with enhanced design -->
                        <div class="flex items-center gap-3 mb-8 relative">
                            <div class="relative">
                                <div class="absolute inset-0 bg-[#ea5a47] rounded-2xl blur-md opacity-30"></div>
                                <div class="relative bg-gradient-to-br from-[#ea5a47] to-[#c53030] p-3 rounded-2xl shadow-lg transform hover:scale-105 transition-transform duration-300">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h1 class="text-2xl sm:text-4xl font-black text-gray-800">
                                    Change <span class="text-[#ea5a47]">Password</span>
                                </h1>
                                <p class="text-gray-500 mt-1">Secure your admin account</p>
                            </div>
                        </div>

                        <!-- Messages with enhanced styling -->
                        @if(session('success'))
                            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2 animate-slideDown">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2 animate-slideDown">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span>{{ session('error') }}</span>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="bg-red-50 border-l-4 border-red-500 rounded-lg mb-4 overflow-hidden animate-slideDown">
                                <div class="px-4 py-3">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="font-medium text-red-700">Please fix the following errors:</span>
                                    </div>
                                    <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <!-- Change Password Form -->
                        <form action="{{ route('admin.profile.update-password') }}" method="POST" class="space-y-6 relative">
                            @csrf
                            
                            <!-- Current Password Field -->
                            <div class="group">
                                <label class="block text-gray-700 font-medium mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                    </svg>
                                    Current Password
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                           id="current_password"
                                           name="current_password" 
                                           class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white focus:ring-0 outline-none transition-all duration-300 group-hover:border-[#ea5a47]/50 pr-12"
                                           required>
                                    <button type="button" 
                                           onclick="togglePassword('current_password', this)" 
                                           class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-[#ea5a47] transition-colors"
                                           title="Show password">
                                        <!-- Eye Closed Icon (initial state - password hidden) -->
                                        <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- New Password Field -->
                            <div class="group">
                                <label class="block text-gray-700 font-medium mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    New Password
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                           id="new_password"
                                           name="new_password" 
                                           class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white focus:ring-0 outline-none transition-all duration-300 group-hover:border-[#ea5a47]/50 pr-12"
                                           required>
                                    <button type="button" 
                                            onclick="togglePassword('new_password', this)" 
                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-[#ea5a47] transition-colors"
                                            title="Show password">
                                        <!-- Eye Closed Icon (initial state - password hidden) -->
                                        <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Minimum 6 characters
                                </p>
                            </div>

                            <!-- Confirm New Password Field -->
                            <div class="group">
                                <label class="block text-gray-700 font-medium mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    Confirm New Password
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                           id="new_password_confirmation"
                                           name="new_password_confirmation" 
                                           class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white focus:ring-0 outline-none transition-all duration-300 group-hover:border-[#ea5a47]/50 pr-12"
                                           required>
                                    <button type="button" 
                                            onclick="togglePassword('new_password_confirmation', this)" 
                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-[#ea5a47] transition-colors"
                                            title="Show password">
                                        <!-- Eye Closed Icon (initial state - password hidden) -->
                                        <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="flex gap-4 pt-4">
                                <button type="submit"
                                        data-loading-text="Updating Password..."
                                        class="flex-1 bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-bold py-3 px-4 rounded-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-[1.02] hover:from-[#c53030] hover:to-[#ea5a47] flex items-center justify-center gap-2 group">
                                    <svg class="w-5 h-5 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    <span>Update Password</span>
                                </button>
                                
                                <a href="{{ route('admin.dashboard') }}" 
                                   class="flex-1 bg-gray-100 text-gray-700 font-bold py-3 px-4 rounded-xl hover:bg-gray-200 hover:shadow-lg transition-all duration-300 text-center flex items-center justify-center gap-2 group">
                                    <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    <span>Cancel</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

</div>

<script>
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    
    if (input.type === 'password') {
        // Password is hidden - show it and show OPEN eye icon
        input.type = 'text';
        
        // Replace with OPEN eye icon (password is now visible)
        button.innerHTML = `
            <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
        `;
        button.setAttribute('title', 'Hide password');
    } else {
        // Password is visible - hide it and show CLOSED eye icon
        input.type = 'password';
        
        // Replace with CLOSED eye icon (password is now hidden)
        button.innerHTML = `
            <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
            </svg>
        `;
        button.setAttribute('title', 'Show password');
    }
}
</script>

<style>
    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-20px);
        }
    }
    
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-slideDown {
        animation: slideDown 0.3s ease-out;
    }
</style>
@endsection