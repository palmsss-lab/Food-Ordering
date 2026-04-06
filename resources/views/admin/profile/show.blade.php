@extends('admin.layouts.home', ['active' => 'profile'])

@section('title', 'My Profile')

@section('content')
<div class="relative min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] overflow-hidden py-12 px-4 sm:px-6 lg:px-8">
    <div class="relative z-10 max-w-5xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="absolute inset-0 bg-[#ea5a47] rounded-lg blur-md opacity-30"></div>
                    <div class="relative bg-gradient-to-br from-[#ea5a47] to-[#c53030] p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
                <h1 class="text-3xl font-black text-gray-800">
                    My <span class="text-[#ea5a47]">Profile</span>
                </h1>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg mb-6 animate-slideDown">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6 animate-slideDown">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Profile Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Profile Avatar & Basic Info -->
            <div class="lg:col-span-1">
                <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 relative overflow-hidden sticky top-6">
                    <div class="absolute top-0 left-0 w-32 h-32 bg-gradient-to-br from-[#ea5a47] to-[#c53030] opacity-5 rounded-br-3xl"></div>
                    
                    <div class="relative z-10 p-6 text-center">
                        <!-- Avatar -->
                        <div class="relative inline-block mb-4">
                            <div class="w-36 h-36 rounded-full bg-gradient-to-br from-[#ea5a47] to-[#c53030] flex items-center justify-center text-white text-6xl font-bold shadow-xl mx-auto">
                                {{ substr($admin->name, 0, 1) }}
                            </div>
                            <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 rounded-full border-4 border-white"></div>
                        </div>
                        
                        <!-- Name & Role -->
                        <h2 class="text-2xl font-bold text-gray-800">{{ $admin->name }}</h2>
                        <div class="flex items-center justify-center gap-2 mt-2">
                            <span class="px-3 py-1 bg-[#ea5a47]/10 text-[#ea5a47] rounded-full text-xs font-medium">Administrator</span>
                        </div>
                        
                        <!-- Member Since -->
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <p class="text-xs text-gray-400">Member Since</p>
                            <p class="text-sm font-medium text-gray-700">{{ $admin->created_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Detailed Information -->
            <div class="lg:col-span-2">
                <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-[#ea5a47] to-[#c53030] opacity-5 rounded-bl-3xl"></div>
                    
                    <div class="relative z-10 p-8">
                        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                            <svg class="w-6 h-6 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Personal Information
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Full Name -->
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 hover:shadow-md transition-all">
                                <label class="block text-xs text-gray-500 mb-1 uppercase tracking-wide">Full Name</label>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="font-medium text-gray-800">{{ $admin->name }}</span>
                                </div>
                            </div>

                            <!-- Email Address -->
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 hover:shadow-md transition-all">
                                <label class="block text-xs text-gray-500 mb-1 uppercase tracking-wide">Email Address</label>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span class="font-medium text-gray-800">{{ $admin->email }}</span>
                                </div>
                            </div>

                            <!-- Phone Number -->
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 hover:shadow-md transition-all">
                                <label class="block text-xs text-gray-500 mb-1 uppercase tracking-wide">Phone Number</label>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span class="font-medium text-gray-800">{{ $admin->phone ?? 'Not provided' }}</span>
                                </div>
                            </div>

                            <!-- Role -->
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 hover:shadow-md transition-all">
                                <label class="block text-xs text-gray-500 mb-1 uppercase tracking-wide">Role</label>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <span class="px-2 py-1 bg-[#ea5a47]/10 text-[#ea5a47] rounded-full text-xs font-medium">Administrator</span>
                                </div>
                            </div>
                        </div>

                        <!-- Address Section - Full Width -->
                        <div class="mt-6">
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 hover:shadow-md transition-all">
                                <label class="block text-xs text-gray-500 mb-1 uppercase tracking-wide">Address</label>
                                <div class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-[#ea5a47] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="font-medium text-gray-800">{{ $admin->address ?? 'Not provided' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Quick Actions</h4>
                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('admin.profile.edit') }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-[#ea5a47]/10 text-[#ea5a47] rounded-lg hover:bg-[#ea5a47] hover:text-white transition-all duration-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit Profile
                                </a>
                                <a href="{{ route('admin.profile.password') }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-all duration-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Change Password
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .animate-slideDown {
        animation: slideDown 0.3s ease-out;
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
    
    /* Sticky sidebar */
    .sticky {
        position: sticky;
        top: 1.5rem;
    }
    
    /* Hover effects */
    .bg-gray-50 {
        transition: all 0.3s ease;
    }
    
    .bg-gray-50:hover {
        transform: translateY(-2px);
    }
</style>
@endsection