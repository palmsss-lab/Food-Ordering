@extends('admin.layouts.home', ['active' => 'promotions'])

@section('title', 'Edit Promotion')

@section('content')
<div class="relative min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] overflow-hidden py-8 px-4 sm:px-6 lg:px-8">
    <div class="relative z-10 max-w-2xl mx-auto">

        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.promotions.index') }}"
               class="p-2 bg-white rounded-lg shadow hover:shadow-md transition-all text-gray-600 hover:text-gray-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-xl sm:text-3xl font-black text-gray-800">Edit <span class="text-[#ea5a47]">Promotion</span></h1>
        </div>

        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 p-8">
            <form action="{{ route('admin.promotions.update', $promotion) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                @include('admin.promotions._form', ['promotion' => $promotion])

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 py-3 bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-bold rounded-xl hover:shadow-lg transition-all">
                        Save Changes
                    </button>
                    <a href="{{ route('admin.promotions.index') }}"
                       class="flex-1 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
