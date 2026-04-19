@extends('admin.layouts.home', ['active' => 'edit-profile'])
    
@section('title', 'Admin - Edit Profile')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] flex items-center justify-center p-4 sm:p-6 lg:p-8">

        <!-- Main Content Area -->
        <div class="w-full">
            <div class="max-w-2xl mx-auto">
                <!-- Edit Profile Card -->
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h1 class="text-2xl sm:text-4xl font-black text-gray-800">
                                    Edit <span class="text-[#ea5a47]">Profile</span>
                                </h1>
                                <p class="text-gray-500 mt-1">Update your admin information</p>
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

                        <!-- Edit Profile Form -->
                        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6 relative">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="group">
                                    <label class="block text-gray-700 font-medium mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Full Name
                                    </label>
                                    <input type="text" 
                                           name="name" 
                                           value="{{ old('name', session('user')->name ?? '') }}"
                                           class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white focus:ring-0 outline-none transition-all duration-300 group-hover:border-[#ea5a47]/50"
                                           required>
                                </div>

                                <div class="group">
                                    <label class="block text-gray-700 font-medium mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        Email Address
                                    </label>
                                    <input type="email" 
                                           name="email" 
                                           value="{{ old('email', session('user')->email ?? '') }}"
                                           class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white focus:ring-0 outline-none transition-all duration-300 group-hover:border-[#ea5a47]/50"
                                           required>
                                </div>

                                <div class="group">
                                    <label class="block text-gray-700 font-medium mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        Phone Number
                                    </label>
                                    <input type="text" 
                                           name="phone" 
                                           value="{{ old('phone', session('user')->phone ?? '') }}"
                                           class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white focus:ring-0 outline-none transition-all duration-300 group-hover:border-[#ea5a47]/50">
                                </div>

                                <div class="group">
                                    <label class="block text-gray-700 font-medium mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                        </svg>
                                        Username
                                    </label>
                                    <input type="text" 
                                           value="{{ session('user')->username ?? '' }}"
                                           class="w-full px-4 py-3 bg-gray-100 border-2 border-gray-200 rounded-xl cursor-not-allowed"
                                           disabled>
                                    <p class="text-xs text-gray-500 mt-1">Username cannot be changed</p>
                                </div>
                            </div>

                            <div class="group">
                                <label class="block text-gray-700 font-medium mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Address
                                </label>
                                <textarea name="address" 
                                          rows="3"
                                          class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white focus:ring-0 outline-none transition-all duration-300 group-hover:border-[#ea5a47]/50">{{ old('address', session('user')->address ?? '') }}</textarea>
                            </div>

                            <div class="flex gap-4 pt-4">
                                <button type="submit"
                                        data-loading-text="Saving Changes..."
                                        class="flex-1 bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-bold py-3 px-4 rounded-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-[1.02] hover:from-[#c53030] hover:to-[#ea5a47] flex items-center justify-center gap-2 group">
                                    <svg class="w-5 h-5 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Update Profile</span>
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