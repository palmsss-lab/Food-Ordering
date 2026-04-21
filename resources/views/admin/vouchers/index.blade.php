@extends('admin.layouts.home', ['active' => 'vouchers'])

@section('title', 'Vouchers')

@section('content')
<div class="relative min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] overflow-hidden py-8 px-4 sm:px-6 lg:px-8">
    <div class="relative z-10 max-w-6xl mx-auto">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="absolute inset-0 bg-[#ea5a47] rounded-lg blur-md opacity-30"></div>
                    <div class="relative bg-gradient-to-br from-[#ea5a47] to-[#c53030] p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                </div>
                <h1 class="text-xl sm:text-3xl font-black text-gray-800">
                    Vouchers & <span class="text-[#ea5a47]">Discounts</span>
                </h1>
            </div>
            <a href="{{ route('admin.vouchers.create') }}"
               class="flex items-center gap-2 bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-bold px-6 py-3 rounded-xl hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Voucher</span>
            </a>
        </div>

        @if(session('success'))
            <div id="flash-success" class="mb-4 bg-green-50 border-l-4 border-green-500 px-4 py-3 rounded-lg text-green-700 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <!-- Search -->
        <div class="bg-white/95 rounded-3xl shadow-xl p-4 mb-4 border border-white/20">
            <form method="GET" action="{{ route('admin.vouchers.index') }}" class="flex gap-3">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by code or description..."
                       class="flex-1 px-4 py-2 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] outline-none transition-all text-sm">
                <button type="submit"
                        class="px-5 py-2 bg-[#ea5a47] text-white rounded-xl text-sm font-semibold hover:bg-[#c53030] transition-colors">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.vouchers.index') }}"
                       class="px-5 py-2 bg-gray-100 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-colors">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[380px] text-sm">
                    <thead>
                        <tr class="bg-gradient-to-r from-[#ea5a47]/10 to-[#c53030]/10 border-b border-gray-100">
                            <th class="px-6 py-4 text-left font-bold text-gray-700">Code</th>
                            <th class="px-6 py-4 text-left font-bold text-gray-700 hidden md:table-cell">Description</th>
                            <th class="px-6 py-4 text-left font-bold text-gray-700">Discount</th>
                            <th class="px-6 py-4 text-left font-bold text-gray-700 hidden sm:table-cell">Min Order</th>
                            <th class="px-6 py-4 text-left font-bold text-gray-700 hidden sm:table-cell">Uses</th>
                            <th class="px-6 py-4 text-left font-bold text-gray-700 hidden sm:table-cell">Expires</th>
                            <th class="px-6 py-4 text-left font-bold text-gray-700">Status</th>
                            <th class="px-6 py-4 text-right font-bold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($vouchers as $voucher)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-mono font-bold text-[#ea5a47] bg-[#ea5a47]/10 px-3 py-1 rounded-lg text-sm">
                                        {{ $voucher->code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600 max-w-[180px] truncate hidden md:table-cell">
                                    {{ $voucher->description ?: '—' }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-800">
                                    @if($voucher->type === 'percentage')
                                        <span class="text-green-600">{{ $voucher->value }}% off</span>
                                    @else
                                        <span class="text-blue-600">₱{{ number_format($voucher->value, 2) }} off</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600 hidden sm:table-cell">
                                    {{ $voucher->min_order_amount ? '₱' . number_format($voucher->min_order_amount, 2) : '—' }}
                                </td>
                                <td class="px-6 py-4 text-gray-600 hidden sm:table-cell">
                                    {{ $voucher->actualUsedCount() }}{{ $voucher->max_uses ? ' / ' . $voucher->max_uses : '' }}
                                </td>
                                <td class="px-6 py-4 text-gray-600 hidden sm:table-cell">
                                    @if($voucher->expires_at)
                                        <span class="{{ $voucher->expires_at->isPast() ? 'text-red-500' : 'text-gray-600' }}">
                                            {{ $voucher->expires_at->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">No expiry</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($voucher->isExpired())
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-600">
                                            Expired
                                        </span>
                                    @else
                                        <form method="POST" action="{{ route('admin.vouchers.toggle', $voucher) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="px-3 py-1 rounded-full text-xs font-semibold transition-colors
                                                           {{ $voucher->is_active
                                                               ? 'bg-green-100 text-green-700 hover:bg-green-200'
                                                               : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                                {{ $voucher->is_active ? 'Active' : 'Inactive' }}
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.vouchers.edit', $voucher) }}"
                                           class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors"
                                           title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('admin.vouchers.destroy', $voucher) }}"
                                              onsubmit="return confirm('Delete voucher {{ $voucher->code }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors"
                                                    title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    <p class="font-medium">No vouchers found.</p>
                                    <a href="{{ route('admin.vouchers.create') }}" class="text-[#ea5a47] hover:underline text-sm mt-1 inline-block">Create your first voucher</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($vouchers->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $vouchers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
setTimeout(() => {
    const el = document.getElementById('flash-success');
    if (el) el.style.display = 'none';
}, 4000);
</script>
@endsection
