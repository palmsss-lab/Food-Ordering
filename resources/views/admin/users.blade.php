@extends('admin.layouts.home', ['active' => 'users'])

@section('title', 'Registered Users')

@section('content')
<div class="relative min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] overflow-hidden py-8 px-4 sm:px-6 lg:px-8">

    <div class="relative z-10 max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="flex flex-wrap items-center gap-3 mb-6">
            <div class="relative">
                <div class="absolute inset-0 bg-[#ea5a47] rounded-lg blur-md opacity-30"></div>
                <div class="relative bg-gradient-to-br from-[#ea5a47] to-[#c53030] p-3 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
            <div>
                <h1 class="text-xl sm:text-3xl font-black text-gray-800">Registered <span class="text-[#ea5a47]">Users</span></h1>
                <p class="text-sm text-gray-500">{{ $users->count() }} total (including deleted accounts)</p>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[400px] text-sm text-left text-gray-700">
                    <thead class="text-xs uppercase bg-gray-50/90 border-b-2 border-gray-200 sticky top-0 z-10">
                        <tr>
                            <th class="px-6 py-4 bg-gray-50/95">User</th>
                            <th class="px-6 py-4 bg-gray-50/95 hidden sm:table-cell">Email</th>
                            <th class="px-6 py-4 bg-gray-50/95 hidden md:table-cell">Phone</th>
                            <th class="px-6 py-4 bg-gray-50/95 text-center hidden sm:table-cell">Total Orders</th>
                            <th class="px-6 py-4 bg-gray-50/95 hidden md:table-cell">Registered</th>
                            <th class="px-6 py-4 bg-gray-50/95 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors {{ $user->trashed() ? 'opacity-60' : '' }}">
                            {{-- Avatar + Name --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#ea5a47] to-[#c53030] flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                        @if($user->trashed())
                                            <p class="text-xs text-red-400">Account deleted</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 hidden sm:table-cell">
                                @if($user->trashed() && $user->archivedEmail)
                                    {{ $user->archivedEmail->original_email }}
                                    <div class="text-xs text-gray-400 italic">original email</div>
                                @else
                                    {{ $user->email }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-500 hidden md:table-cell">{{ $user->phone ?? '—' }}</td>
                            <td class="px-6 py-4 text-center hidden sm:table-cell">
                                <span class="font-bold text-[#ea5a47]">{{ $user->orders_count }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 whitespace-nowrap hidden md:table-cell">
                                {{ $user->created_at->format('M d, Y') }}
                                <div class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($user->trashed())
                                    <span class="px-2 py-1 bg-red-100 text-red-600 text-xs font-semibold rounded-full">Deleted</span>
                                @else
                                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Active</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                No users registered yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
