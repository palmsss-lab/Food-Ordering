@extends('client.layouts.home')

@section('title', 'Menu')

@section('content')
<div class="max-w-screen-xl mx-auto px-4 pt-8 pb-12 mt-24">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Our <span class="text-[#ea5a47]">Menu</span></h2>

    <livewire:client.menu-section />
</div>

<style>
    [x-cloak] { display: none !important; }

    @keyframes skel-shimmer {
        0%   { background-position: -500px 0; }
        100% { background-position: 500px 0; }
    }
    .skel {
        background: linear-gradient(90deg, #ede9e3 25%, #f5f0eb 50%, #ede9e3 75%);
        background-size: 500px 100%;
        animation: skel-shimmer 1.3s infinite linear;
    }
</style>
@endsection
