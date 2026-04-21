@extends('admin.layouts.home', ['active' => 'create-category'])

@section('title', 'Add New Category')

@section('content')
<div class="relative min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] overflow-hidden">
    <!-- Decorative Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-20 -right-20 w-96 h-96 bg-[#ea5a47] opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-[#c53030] opacity-5 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 max-w-3xl mx-auto py-8 px-4 sm:px-6">
        <!-- Header with back button -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="absolute inset-0 bg-[#ea5a47] rounded-lg blur-md opacity-30"></div>
                    <div class="relative bg-gradient-to-br from-[#ea5a47] to-[#c53030] p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                    </div>
                </div>
                <h1 class="text-xl sm:text-3xl font-black text-gray-800">
                    Add New <span class="text-[#ea5a47]">Category</span>
                </h1>
            </div>
            <a href="{{ route('admin.categories.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 shadow-sm border border-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Back to List</span>
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-32 h-32 bg-gradient-to-br from-[#ea5a47] to-[#c53030] opacity-5 rounded-br-3xl"></div>
            
            <div class="relative z-10 p-8">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    
                    <!-- Category Name Field -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Category Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               placeholder="e.g., Beverages, Appetizers, Main Course"
                               required 
                               autofocus
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#ea5a47] focus:outline-none transition-colors duration-200 @error('name') border-red-500 @enderror">
                        
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                        <button type="submit"
                                data-loading-text="Creating Category..."
                                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-semibold rounded-xl hover:from-[#c53030] hover:to-[#ea5a47] transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Create Category
                        </button>
                        
                        <a href="{{ route('admin.categories.index') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Help Card -->
        <div class="mt-6 bg-gradient-to-r from-[#ea5a47]/10 to-[#c53030]/10 rounded-2xl p-6 border border-[#ea5a47]/20">
            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Category Tips</span>
            </h3>
            <ul class="text-sm text-gray-600 space-y-2">
                <li class="flex items-start gap-2">
                    <span class="text-[#ea5a47] font-bold">•</span>
                    <span>Use clear, descriptive names that customers will easily understand.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-[#ea5a47] font-bold">•</span>
                    <span>Examples: "Beverages", "Appetizers", "Main Course", "Desserts"</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-[#ea5a47] font-bold">•</span>
                    <span>Categories help organize your menu and make it easier for customers to browse.</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection