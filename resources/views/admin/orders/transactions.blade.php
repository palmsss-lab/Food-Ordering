@extends('admin.layouts.home', ['active' => 'transactions'])

@section('title', 'Transaction History')

@section('content')
<div class="relative min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] overflow-hidden py-12 px-4 sm:px-6 lg:px-8">
    <!-- Decorative Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-20 -right-20 w-96 h-96 bg-[#ea5a47] opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-[#c53030] opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-to-r from-[#ea5a47]/5 to-[#c53030]/5 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex items-center gap-3 mb-6">
            <div class="relative">
                <div class="absolute inset-0 bg-[#ea5a47] rounded-lg blur-md opacity-30"></div>
                <div class="relative bg-gradient-to-br from-[#ea5a47] to-[#c53030] p-3 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <h1 class="text-3xl font-black text-gray-800">
                Transaction <span class="text-[#ea5a47]">History</span>
            </h1>
            <span class="ml-auto text-sm text-gray-500">
                Total: <span class="font-bold text-[#ea5a47]">{{ $transactions->total() }}</span> transactions
            </span>
        </div>

        <!-- Filter Card -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl p-6 border border-white/20 relative overflow-hidden mb-6">
            <div class="absolute top-0 left-0 w-32 h-32 bg-gradient-to-br from-[#ea5a47] to-[#c53030] opacity-5 rounded-br-3xl"></div>
            
            <form method="GET" action="{{ route('admin.orders.transactions') }}" id="filter-form" class="relative z-10">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            From Date
                        </label>
                        <input type="date" 
                               name="from_date" 
                               value="{{ request('from_date') }}"
                               class="w-full px-4 py-2 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            To Date
                        </label>
                        <input type="date" 
                               name="to_date" 
                               value="{{ request('to_date') }}"
                               class="w-full px-4 py-2 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white outline-none transition-all">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 text-sm font-medium mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Search
                        </label>
                        <div class="flex gap-2">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search by order #, customer name, phone, or email..."
                                   class="flex-1 px-4 py-2 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] focus:bg-white outline-none transition-all">
                            <button type="submit" 
                                    id="filter-submit"
                                    class="px-6 py-2 bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-medium rounded-xl hover:from-[#c53030] hover:to-[#ea5a47] hover:shadow-lg hover:scale-105 transition-all duration-300 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Filter
                            </button>
                            <a href="{{ route('admin.orders.transactions') }}" 
                               class="reset-link px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-300 hover:scale-105 transition-all duration-300 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Results Summary -->
        @if(request('from_date') || request('to_date') || request('search'))
        <div class="mb-4 px-4 py-2 bg-white/50 backdrop-blur-sm rounded-xl inline-flex items-center gap-2 text-sm text-gray-600">
            <svg class="w-4 h-4 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            Showing results for:
            @if(request('from_date') && request('to_date'))
                <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse(request('from_date'))->format('M d, Y') }} - {{ \Carbon\Carbon::parse(request('to_date'))->format('M d, Y') }}</span>
            @elseif(request('from_date'))
                <span class="font-medium text-gray-800">from {{ \Carbon\Carbon::parse(request('from_date'))->format('M d, Y') }}</span>
            @elseif(request('to_date'))
                <span class="font-medium text-gray-800">up to {{ \Carbon\Carbon::parse(request('to_date'))->format('M d, Y') }}</span>
            @endif
            @if(request('search'))
                <span class="font-medium text-gray-800">search: "{{ request('search') }}"</span>
            @endif
            <a href="{{ route('admin.orders.transactions') }}" class="text-[#ea5a47] hover:underline ml-1">Clear filters</a>
        </div>
        @endif

        <!-- Transactions Table - SCROLLABLE CONTAINER -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-[#ea5a47] to-[#c53030] opacity-5 rounded-bl-3xl"></div>
            
            <!-- Table Container with max-height and overflow -->
            <div class="relative z-10 overflow-x-auto" style="max-height: calc(100vh - 380px); overflow-y: auto;">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs uppercase bg-gray-50/80 border-b-2 border-gray-200 sticky top-0 z-10">
                        <tr>
                            <th class="px-6 py-4 bg-gray-50/95">Transaction #</th>
                            <th class="px-6 py-4 bg-gray-50/95">Order #</th>
                            <th class="px-6 py-4 bg-gray-50/95">Customer</th>
                            <th class="px-6 py-4 bg-gray-50/95">Items</th>
                            <th class="px-6 py-4 bg-gray-50/95">Total</th>
                            <th class="px-6 py-4 bg-gray-50/95">Payment Method</th>
                            <th class="px-6 py-4 bg-gray-50/95">Payment Status</th>
                            <th class="px-6 py-4 bg-gray-50/95">Completed Date</th>
                            <th class="px-6 py-4 bg-gray-50/95">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($transactions as $transaction)
                        @php
                            $paymentMethod = $transaction->payment_method;
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                            <td class="px-6 py-4 font-mono text-sm font-bold text-[#ea5a47]">
                                {{ $transaction->transaction_number }}
                            </td>
                            <td class="px-6 py-4 font-mono text-sm">
                                <span class="text-gray-600">
                                    {{ $transaction->order_number }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ $transaction->customer_name }}</div>
                                @if($transaction->customer_phone)
                                    <div class="text-xs text-gray-500">{{ $transaction->customer_phone }}</div>
                                @endif
                                @if($transaction->customer_email)
                                    <div class="text-xs text-gray-400">{{ $transaction->customer_email }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">{{ $transaction->items->count() }} item(s)</div>
                                <div class="text-xs text-gray-500 truncate max-w-[200px]">
                                    {{ $transaction->items->pluck('item_name')->implode(', ') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900">
                                {{ $transaction->formatted_total }}
                            </td>
                            <td class="px-6 py-4">
                                @if($paymentMethod == 'cash')
                                    <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-medium whitespace-nowrap">
                                        💵 Cash
                                    </span>
                                @elseif($paymentMethod == 'gcash')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium whitespace-nowrap">
                                        📱 GCash
                                    </span>
                                @elseif($paymentMethod == 'card')
                                    <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium whitespace-nowrap">
                                        💳 Card
                                    </span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium whitespace-nowrap">
                                        {{ ucfirst($paymentMethod) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium whitespace-nowrap">
                                    ✅ Paid
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                @if($transaction->transaction_date)
                                    {{ $transaction->transaction_date->format('M d, Y') }}
                                    <div class="text-xs text-gray-400">{{ $transaction->transaction_date->format('h:i A') }}</div>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.transactions.show', $transaction->transaction_number) }}" 
                                   class="view-details-link p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 hover:scale-110 transition-all duration-300 inline-flex items-center gap-1"
                                   title="View Details">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                             </td>
                         </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <h5 class="text-xl font-bold text-gray-600 mb-2">No Transactions Found</h5>
                                    <p class="text-gray-500">No completed orders match your filters.</p>
                                    @if(request('from_date') || request('to_date') || request('search'))
                                        <a href="{{ route('admin.orders.transactions') }}" class="mt-4 text-[#ea5a47] hover:underline">Clear filters</a>
                                    @endif
                                </div>
                             </td>
                         </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-white/95">
                {{ $transactions->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
// Prevent double loading on filter submissions
let isFiltering = false;

document.addEventListener('DOMContentLoaded', function() {
    // Handle filter form submission
    const filterForm = document.getElementById('filter-form');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            if (isFiltering) {
                e.preventDefault();
                return;
            }
            
            isFiltering = true;
            
            // Show loader
            if (window.showLoader) {
                window.showLoader();
            }
            
            // Disable submit button
            const submitBtn = document.getElementById('filter-submit');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.7';
                submitBtn.style.cursor = 'not-allowed';
            }
        });
    }
    
    // Handle reset link
    const resetLink = document.querySelector('.reset-link');
    if (resetLink) {
        resetLink.addEventListener('click', function(e) {
            if (window.showLoader && !isFiltering) {
                window.showLoader();
            }
        });
    }
    
    // Handle view details links
    document.querySelectorAll('.view-details-link').forEach(link => {
        link.addEventListener('click', function(e) {
            if (window.showLoader && !isFiltering) {
                window.showLoader();
            }
        });
    });
    
    // Reset flags when page loads or returns from cache
    window.addEventListener('pageshow', function() {
        isFiltering = false;
        
        // Re-enable filter button
        const submitBtn = document.getElementById('filter-submit');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
        }
    });
});
</script>

<style>
    /* Custom scrollbar styling */
    .overflow-x-auto::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #ea5a47;
        border-radius: 10px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #c53030;
    }
    
    /* Sticky header styling */
    .sticky th {
        position: sticky;
        top: 0;
        background: rgba(249, 250, 251, 0.95);
        backdrop-filter: blur(4px);
        z-index: 10;
    }
    
    /* Hover effects for buttons */
    button, a {
        transition: all 0.3s ease;
    }
    
    button:active, a:active {
        transform: scale(0.98);
    }
    
    /* Table row hover effect */
    tbody tr {
        transition: all 0.2s ease;
    }
    
    tbody tr:hover {
        background-color: rgba(249, 250, 251, 0.8);
    }
    
    /* Smooth animations */
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
</style>
@endsection