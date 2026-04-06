@extends('client.layouts.home')

@section('title', 'About')

@section('content')
<!-- About Section -->
<div class="max-w-screen-xl mx-auto mt-10 px-4 md:px-0 py-20">
    <!-- Section Header -->
    <div class="text-center mb-16">
        <div class="inline-flex items-center gap-2 bg-[#ea5a47]/10 text-[#ea5a47] px-4 py-2 rounded-full text-sm font-semibold mb-4">
            <span class="w-2 h-2 bg-[#ea5a47] rounded-full animate-pulse"></span>
            Our Story
        </div>
        <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
            More Than Just <span class="text-[#ea5a47]">Food</span>
        </h2>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
            Every dish tells a story of passion, tradition, and love for Filipino cuisine
        </p>
    </div>

    <div class="grid md:grid-cols-2 gap-12 items-start">
        <!-- Left side - Dish Showcase -->
        <div class="space-y-6">
            <!-- Featured Dish Card -->
            <div class="bg-gradient-to-br from-[#ea5a47] to-[#c53030] rounded-3xl p-8 shadow-2xl text-white">
                <div class="flex items-center gap-4 mb-4">
                    <span class="w-1 h-8 bg-white rounded-full"></span>
                    <h3 class="text-2xl font-bold">Our Signature Dishes</h3>
                </div>
                <p class="text-white/90 mb-6">
                    From classic Sinigang to sizzling Sisig, each dish is prepared with authentic recipes passed down through generations.
                </p>
                
                <!-- Dish Tags -->
                <div class="flex flex-wrap gap-2">
                    <span class="bg-white/20 px-4 py-2 rounded-full text-sm">Sinigang</span>
                    <span class="bg-white/20 px-4 py-2 rounded-full text-sm">Bulalo</span>
                    <span class="bg-white/20 px-4 py-2 rounded-full text-sm">Lechon Kawali</span>
                    <span class="bg-white/20 px-4 py-2 rounded-full text-sm">Sisig</span>
                    <span class="bg-white/20 px-4 py-2 rounded-full text-sm">Bicol Express</span>
                    <span class="bg-white/20 px-4 py-2 rounded-full text-sm">Adobo</span>
                </div>
            </div>

            <!-- Values Grid (2x2) -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:border-[#ea5a47] transition-all group">
                    <div class="bg-[#ea5a47]/10 w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:bg-[#ea5a47] transition-colors">
                        <svg class="w-6 h-6 text-[#ea5a47] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z" />
                        </svg>
                    </div>
                    <h4 class="font-bold text-gray-800 mb-2">Fresh Ingredients</h4>
                    <p class="text-sm text-gray-500">Daily sourced from local markets</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:border-[#ea5a47] transition-all group">
                    <div class="bg-[#ea5a47]/10 w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:bg-[#ea5a47] transition-colors">
                        <svg class="w-6 h-6 text-[#ea5a47] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h4 class="font-bold text-gray-800 mb-2">Family Recipes</h4>
                    <p class="text-sm text-gray-500">Passed down through generations</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:border-[#ea5a47] transition-all group">
                    <div class="bg-[#ea5a47]/10 w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:bg-[#ea5a47] transition-colors">
                        <svg class="w-6 h-6 text-[#ea5a47] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h4 class="font-bold text-gray-800 mb-2">Community</h4>
                    <p class="text-sm text-gray-500">Bringing people together</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:border-[#ea5a47] transition-all group">
                    <div class="bg-[#ea5a47]/10 w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:bg-[#ea5a47] transition-colors">
                        <svg class="w-6 h-6 text-[#ea5a47] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h4 class="font-bold text-gray-800 mb-2">Quality</h4>
                    <p class="text-sm text-gray-500">Highest standards guaranteed</p>
                </div>
            </div>
        </div>

        <!-- Right side - Story Content -->
        <div class="space-y-6">
            <!-- Main Story -->
            <div class="bg-[#fdf7f2] rounded-3xl p-8 shadow-lg">
                <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-1 h-8 bg-[#ea5a47] rounded-full"></span>
                    Our Passion
                </h3>
                <p class="text-gray-600 leading-relaxed mb-6">
                    At 2Dine-In, we believe that the best meals are the ones shared with loved ones. 
                    What started as a small family kitchen, sharing traditional recipes with neighbors, 
                    has grown into a place where everyone can experience the warmth of home-cooked Filipino meals.
                </p>
                
                <!-- Milestones -->
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Established</p>
                            <p class="text-2xl font-bold text-gray-800">2020</p>
                        </div>
                        <div class="h-10 w-px bg-gray-200"></div>
                        <div>
                            <p class="text-sm text-gray-500">Authentic Dishes</p>
                            <p class="text-2xl font-bold text-[#ea5a47]">50+</p>
                        </div>
                        <div class="h-10 w-px bg-gray-200"></div>
                        <div>
                            <p class="text-sm text-gray-500">Happy Customers</p>
                            <p class="text-2xl font-bold text-gray-800">1000+</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chef's Note -->
            <div class="bg-gradient-to-r from-[#ea5a47] to-[#c53030] rounded-3xl p-8 shadow-xl text-white">
                <div class="flex items-start gap-4">
                    <div class="bg-white/20 p-3 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-medium mb-2">Chef's Note</p>
                        <p class="text-white/90 text-sm leading-relaxed">
                            "Every dish we serve is made with love and respect for our Filipino heritage. 
                            We invite you to taste the difference that passion makes."
                        </p>
                        <p class="mt-3 font-semibold">— Chef Joram Saba</p>
                    </div>
                </div>
            </div>

            <!-- Opening Hours -->
            <div class="bg-white rounded-3xl p-6 shadow-lg border border-gray-100">
                <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Opening Hours
                </h4>
                <div class="space-y-2 text-gray-600">
                    <div class="flex justify-between">
                        <span>Monday - Friday</span>
                        <span class="font-medium">10:00 AM - 9:00 PM</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Saturday - Sunday</span>
                        <span class="font-medium">9:00 AM - 10:00 PM</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Banner -->
    <div class="mt-20 bg-[#fdf7f2] rounded-3xl p-10 shadow-lg">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-4xl font-black text-[#ea5a47]">4.8</div>
                <div class="text-sm text-gray-500 mt-1">Customer Rating</div>
                <div class="flex justify-center mt-2 text-yellow-400">
                    ★ ★ ★ ★ ★
                </div>
            </div>
            <div>
                <div class="text-4xl font-black text-[#ea5a47]">50+</div>
                <div class="text-sm text-gray-500 mt-1">Authentic Dishes</div>
            </div>
            <div>
                <div class="text-4xl font-black text-[#ea5a47]">1000+</div>
                <div class="text-sm text-gray-500 mt-1">Happy Customers</div>
            </div>
            <div>
                <div class="text-4xl font-black text-[#ea5a47]">5</div>
                <div class="text-sm text-gray-500 mt-1">Years of Service</div>
            </div>
        </div>
    </div>
</div>
@endsection