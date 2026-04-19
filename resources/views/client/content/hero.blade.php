@extends('client.layouts.home')

@section('title','Home')

@section('content')

<!-- Hero Section -->
<div class="mt-10 relative overflow-hidden bg-gradient-to-br from-[#fdf7f2] to-white">
    <!-- Decorative Food Icons Background -->
    <div class="absolute inset-0 opacity-5 pointer-events-none">
        <div class="absolute top-20 left-10 text-6xl sm:text-8xl">🍽️</div>
        <div class="absolute bottom-20 right-10 text-6xl sm:text-8xl">🥘</div>
        <div class="absolute top-40 right-20 text-4xl sm:text-6xl">🍲</div>
        <div class="absolute bottom-40 left-20 text-4xl sm:text-6xl">🥗</div>
    </div>

    <div class="max-w-screen-xl mx-auto mt-24 md:mt-32 mb-16 md:mb-24 px-4 md:px-8 lg:px-4 relative z-10">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-8 md:gap-12">

            <!-- Left side: text  -->
            <div class="w-full md:w-1/2 flex flex-col justify-center text-center md:text-left">
                <!-- Welcome Badge -->
                <div class="inline-flex items-center gap-2 bg-[#ea5a47]/10 text-[#ea5a47] px-4 py-2 rounded-full text-sm font-semibold mb-4 md:mb-6 w-max mx-auto md:mx-0">
                    <span class="w-2 h-2 bg-[#ea5a47] rounded-full animate-pulse"></span>
                    Welcome to 2Dine-In
                </div>

                <h1 class="text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-black text-gray-900 tracking-tight leading-tight">
                    Taste <span class="text-[#ea5a47]">Home</span>
                    <br>Like Never Before
                </h1>

                <p class="mt-4 md:mt-6 text-base sm:text-lg md:text-xl text-gray-600 leading-relaxed max-w-lg mx-auto md:mx-0">
                    Experience the taste of home-cooked meals with our carefully crafted dishes.
                    From classic Filipino favorites to modern twists, we bring delicious flavors directly to your table.
                </p>

                <!-- Features List -->
                <div class="mt-6 md:mt-8 space-y-3 text-left">
                    <div class="flex items-center gap-3 text-gray-700">
                        <div class="bg-[#ea5a47]/10 p-2 rounded-lg flex-shrink-0">
                            <svg class="w-5 h-5 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="font-medium">Fresh ingredients daily</span>
                    </div>
                    <div class="flex items-center gap-3 text-gray-700">
                        <div class="bg-[#ea5a47]/10 p-2 rounded-lg flex-shrink-0">
                            <svg class="w-5 h-5 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="font-medium">Ready in 20-30 minutes</span>
                    </div>
                    <div class="flex items-center gap-3 text-gray-700">
                        <div class="bg-[#ea5a47]/10 p-2 rounded-lg flex-shrink-0">
                            <svg class="w-5 h-5 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="font-medium">Perfect for sharing</span>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="mt-8 md:mt-10 flex flex-col sm:flex-row flex-wrap gap-3 sm:gap-4 items-center md:items-start">
                    <a href="{{ route('client.menu') }}"
                       class="group bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-bold text-base sm:text-lg
                              hover:shadow-2xl transition-all duration-300 transform hover:scale-105
                              inline-flex items-center gap-3 w-full sm:w-auto justify-center">
                        <span>Explore Menu</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>

                    <a href="{{ route('client.about') }}"
                       class="bg-white text-gray-700 px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-bold text-base sm:text-lg
                              border-2 border-gray-200 hover:border-[#ea5a47] hover:text-[#ea5a47]
                              transition-all duration-300 inline-flex items-center gap-3 w-full sm:w-auto justify-center">
                        <span>Learn More</span>
                    </a>
                </div>

                <!-- Stats -->
                <div class="mt-8 md:mt-12 flex flex-wrap items-center gap-6 sm:gap-8 justify-center md:justify-start">
                    <div class="text-center md:text-left">
                        <div class="text-2xl sm:text-3xl font-black text-[#ea5a47]">50+</div>
                        <div class="text-xs sm:text-sm text-gray-500">Menu Items</div>
                    </div>
                    <div class="text-center md:text-left">
                        <div class="text-2xl sm:text-3xl font-black text-[#ea5a47]">1000+</div>
                        <div class="text-xs sm:text-sm text-gray-500">Happy Customers</div>
                    </div>
                    <div class="text-center md:text-left">
                        <div class="text-2xl sm:text-3xl font-black text-[#ea5a47]">4.8</div>
                        <div class="text-xs sm:text-sm text-gray-500">★ Rating</div>
                    </div>
                </div>
            </div>

            <!-- Right side: Enhanced Carousel -->
            <div class="w-full md:w-1/2 relative mt-8 md:mt-0">
                <!-- Decorative Frame -->
                <div class="absolute -inset-4 bg-gradient-to-r from-[#ea5a47] to-[#c53030] opacity-20 rounded-3xl blur-2xl"></div>

                <div id="default-carousel" class="relative rounded-3xl overflow-hidden shadow-2xl" data-carousel="slide">
                    <!-- Carousel wrapper -->
                    <div class="relative h-[250px] sm:h-[350px] md:h-[500px] overflow-hidden bg-[#fdf7f2]">
                        <!-- Item 1 - Sinigang -->
                        <div class="hidden duration-700 ease-in-out" data-carousel-item>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                            <img src="/images/sinigang-carousel.png" 
                                 class="absolute block w-full h-full object-cover" 
                                 alt="Sinigang">
                            <div class="absolute bottom-6 left-6 text-white">
                                <h3 class="text-2xl font-bold">Sinigang</h3>
                                <p class="text-sm opacity-90">Classic Filipino Sour Soup</p>
                            </div>
                        </div>
                        <!-- Item 2 - Bulalo -->
                        <div class="hidden duration-700 ease-in-out" data-carousel-item>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                            <img src="/images/bulalo-carousel.png" 
                                 class="absolute block w-full h-full object-cover" 
                                 alt="Bulalo">
                            <div class="absolute bottom-6 left-6 text-white">
                                <h3 class="text-2xl font-bold">Bulalo</h3>
                                <p class="text-sm opacity-90">Beef Marrow Soup</p>
                            </div>
                        </div>
                        <!-- Item 3 - Lechon Kawali -->
                        <div class="hidden duration-700 ease-in-out" data-carousel-item>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                            <img src="/images/lechon-kawali-carousel.png" 
                                 class="absolute block w-full h-full object-cover" 
                                 alt="Lechon Kawali">
                            <div class="absolute bottom-6 left-6 text-white">
                                <h3 class="text-2xl font-bold">Lechon Kawali</h3>
                                <p class="text-sm opacity-90">Crispy Fried Pork Belly</p>
                            </div>
                        </div>
                        <!-- Item 4 - Bicol Express -->
                        <div class="hidden duration-700 ease-in-out" data-carousel-item>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                            <img src="/images/bicol-express-carousel.png" 
                                 class="absolute block w-full h-full object-cover" 
                                 alt="Bicol Express">
                            <div class="absolute bottom-6 left-6 text-white">
                                <h3 class="text-2xl font-bold">Bicol Express</h3>
                                <p class="text-sm opacity-90">Spicy Pork in Coconut Milk</p>
                            </div>
                        </div>
                        <!-- Item 5 - Sisig -->
                        <div class="hidden duration-700 ease-in-out" data-carousel-item>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                            <img src="/images/sisig-carousel.png" 
                                 class="absolute block w-full h-full object-cover" 
                                 alt="Sisig">
                            <div class="absolute bottom-6 left-6 text-white">
                                <h3 class="text-2xl font-bold">Sisig</h3>
                                <p class="text-sm opacity-90">Sizzling Chopped Pork</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Slider indicators -->
                    <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-2">
                        <button type="button" class="w-2 h-2 rounded-full bg-white/50 hover:bg-white transition-all data-[active]:w-6 data-[active]:bg-white" aria-current="true" aria-label="Slide 1" data-carousel-slide-to="0"></button>
                        <button type="button" class="w-2 h-2 rounded-full bg-white/50 hover:bg-white transition-all data-[active]:w-6 data-[active]:bg-white" aria-current="false" aria-label="Slide 2" data-carousel-slide-to="1"></button>
                        <button type="button" class="w-2 h-2 rounded-full bg-white/50 hover:bg-white transition-all data-[active]:w-6 data-[active]:bg-white" aria-current="false" aria-label="Slide 3" data-carousel-slide-to="2"></button>
                        <button type="button" class="w-2 h-2 rounded-full bg-white/50 hover:bg-white transition-all data-[active]:w-6 data-[active]:bg-white" aria-current="false" aria-label="Slide 4" data-carousel-slide-to="3"></button>
                        <button type="button" class="w-2 h-2 rounded-full bg-white/50 hover:bg-white transition-all data-[active]:w-6 data-[active]:bg-white" aria-current="false" aria-label="Slide 5" data-carousel-slide-to="4"></button>
                    </div>
                    
                    <!-- Slider controls -->
                    <button type="button" class="absolute top-1/2 -translate-y-1/2 start-4 z-30 group" data-carousel-prev>
                        <span class="bg-white/90 hover:bg-white text-[#ea5a47] inline-flex items-center justify-center w-12 h-12 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </span>
                    </button>
                    <button type="button" class="absolute top-1/2 -translate-y-1/2 end-4 z-30 group" data-carousel-next>
                        <span class="bg-white/90 hover:bg-white text-[#ea5a47] inline-flex items-center justify-center w-12 h-12 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    </button>
                </div>
                
            
            </div>

        </div>
    </div>
</div>


<style>
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    /* For carousel indicators active state */
    [data-carousel-slide-to][aria-current="true"] {
        width: 1.5rem;
        background-color: white;
    }
</style>
@endsection

