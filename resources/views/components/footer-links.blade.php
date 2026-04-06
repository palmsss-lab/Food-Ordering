<footer class="mt-20 bg-[#fdf7f2] border-t border-[#ea5a47]/10">
    <div class="mx-auto w-full max-w-screen-xl px-4 py-12 lg:py-16">
        <!-- Decorative Food Icons Background -->
        <div class="absolute left-0 opacity-5 pointer-events-none">
            <span class="text-8xl">🍽️</span>
        </div>
        <div class="absolute right-0 bottom-0 opacity-5 pointer-events-none">
            <span class="text-8xl">🥘</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 lg:gap-12 relative z-10">
            <!-- Brand Section - Left -->
            <div class="md:col-span-4 space-y-4">
                <a href="{{ route('client.home') }}" class="flex items-center gap-3 group">
                    <div class="relative">
                        <img src="{{ asset('images/logo.png') }}" 
                            alt="2Dine-In Logo" 
                            class="w-20 h-20 object-contain">
                    </div>
                    <span class="text-2xl font-black text-gray-800 group-hover:text-[#ea5a47] transition-colors">
                        2Dine-In
                    </span>
                </a>
                
                <!-- Tagline -->
                <p class="text-gray-600 text-sm leading-relaxed max-w-sm">
                    Bringing the taste of home-cooked Filipino meals to your table. Fresh ingredients, family recipes, and lots of love.
                </p>
                
                <!-- Contact Info -->
                <div class="space-y-2 pt-2">
                    <div class="flex items-center gap-3 text-gray-600">
                        <div class="bg-[#ea5a47]/10 p-2 rounded-lg">
                            <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="text-sm">San Juan Bautista, Goa, Camarines Sur</span>
                    </div>
                    <div class="flex items-center gap-3 text-gray-600">
                        <div class="bg-[#ea5a47]/10 p-2 rounded-lg">
                            <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <span class="text-sm">+63 (2) 1234 5678</span>
                    </div>
                </div>
            </div>

            <!-- Links Sections - Right -->
            <div class="md:col-span-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-1 h-4 bg-[#ea5a47] rounded-full"></span>
                            Quick Links
                        </h3>
                        <ul class="space-y-3">
                            <li>
                                <a href="{{ route('client.home') }}" class="text-gray-600 hover:text-[#ea5a47] text-sm transition-colors flex items-center gap-2 group">
                                    <span class="w-1 h-1 bg-gray-300 rounded-full group-hover:bg-[#ea5a47] transition-colors"></span>
                                    Home
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('client.menu') }}" class="text-gray-600 hover:text-[#ea5a47] text-sm transition-colors flex items-center gap-2 group">
                                    <span class="w-1 h-1 bg-gray-300 rounded-full group-hover:bg-[#ea5a47] transition-colors"></span>
                                    Menu
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('client.about') }}" class="text-gray-600 hover:text-[#ea5a47] text-sm transition-colors flex items-center gap-2 group">
                                    <span class="w-1 h-1 bg-gray-300 rounded-full group-hover:bg-[#ea5a47] transition-colors"></span>
                                    About Us
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Resources -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-1 h-4 bg-[#ea5a47] rounded-full"></span>
                            Resources
                        </h3>
                        <ul class="space-y-3">
                            <li>
                                <a href="#" class="text-gray-600 hover:text-[#ea5a47] text-sm transition-colors flex items-center gap-2 group">
                                    <span class="w-1 h-1 bg-gray-300 rounded-full group-hover:bg-[#ea5a47] transition-colors"></span>
                                    FAQ
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-gray-600 hover:text-[#ea5a47] text-sm transition-colors flex items-center gap-2 group">
                                    <span class="w-1 h-1 bg-gray-300 rounded-full group-hover:bg-[#ea5a47] transition-colors"></span>
                                    Catering
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-gray-600 hover:text-[#ea5a47] text-sm transition-colors flex items-center gap-2 group">
                                    <span class="w-1 h-1 bg-gray-300 rounded-full group-hover:bg-[#ea5a47] transition-colors"></span>
                                    Careers
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-gray-600 hover:text-[#ea5a47] text-sm transition-colors flex items-center gap-2 group">
                                    <span class="w-1 h-1 bg-gray-300 rounded-full group-hover:bg-[#ea5a47] transition-colors"></span>
                                    Blog
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Legal -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-1 h-4 bg-[#ea5a47] rounded-full"></span>
                            Legal
                        </h3>
                        <ul class="space-y-3">
                            <li>
                                <a href="#" class="text-gray-600 hover:text-[#ea5a47] text-sm transition-colors flex items-center gap-2 group">
                                    <span class="w-1 h-1 bg-gray-300 rounded-full group-hover:bg-[#ea5a47] transition-colors"></span>
                                    Privacy Policy
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-gray-600 hover:text-[#ea5a47] text-sm transition-colors flex items-center gap-2 group">
                                    <span class="w-1 h-1 bg-gray-300 rounded-full group-hover:bg-[#ea5a47] transition-colors"></span>
                                    Terms of Service
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-gray-600 hover:text-[#ea5a47] text-sm transition-colors flex items-center gap-2 group">
                                    <span class="w-1 h-1 bg-gray-300 rounded-full group-hover:bg-[#ea5a47] transition-colors"></span>
                                    Refund Policy
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-gray-600 hover:text-[#ea5a47] text-sm transition-colors flex items-center gap-2 group">
                                    <span class="w-1 h-1 bg-gray-300 rounded-full group-hover:bg-[#ea5a47] transition-colors"></span>
                                    Accessibility
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Hours -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-1 h-4 bg-[#ea5a47] rounded-full"></span>
                            Hours
                        </h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex justify-between">
                                <span>Mon - Fri</span>
                                <span class="font-medium">10AM - 9PM</span>
                            </li>
                            <li class="flex justify-between">
                                <span>Saturday</span>
                                <span class="font-medium">9AM - 10PM</span>
                            </li>
                            <li class="flex justify-between">
                                <span>Sunday</span>
                                <span class="font-medium">9AM - 9PM</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="mt-12 pt-8 border-t border-[#ea5a47]/10">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <!-- Copyright -->
                <p class="text-sm text-gray-500">
                    © {{ date('Y') }} 2Dine-In. All rights reserved. 
                    <span class="mx-2">|</span>
                    <span class="text-[#ea5a47]">Made with 🧡 for Filipino food lovers</span>
                </p>

                <!-- Social Links -->
                <div class="flex items-center gap-3">
                    <a href="#" class="bg-white p-2 rounded-lg shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5 group">
                        <svg class="w-5 h-5 text-gray-600 group-hover:text-[#ea5a47]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                        </svg>
                    </a>
                    <a href="#" class="bg-white p-2 rounded-lg shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5 group">
                        <svg class="w-5 h-5 text-gray-600 group-hover:text-[#ea5a47]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                        </svg>
                    </a>
                    <a href="#" class="bg-white p-2 rounded-lg shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5 group">
                        <svg class="w-5 h-5 text-gray-600 group-hover:text-[#ea5a47]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM5.838 12a6.162 6.162 0 1112.324 0 6.162 6.162 0 01-12.324 0zM12 16a4 4 0 110-8 4 4 0 010 8zm4.965-10.405a1.44 1.44 0 112.881.001 1.44 1.44 0 01-2.881-.001z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>