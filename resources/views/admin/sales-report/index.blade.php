@extends('admin.layouts.home', ['active' => 'sales'])

@section('title', 'Sales Reports')

@section('content')
<div class="relative min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] overflow-hidden py-12 px-4 sm:px-6 lg:px-8">
    <div class="relative z-10 max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-3">
                <div class="bg-[#ea5a47] p-3 rounded-2xl shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h1 class="text-4xl font-black text-gray-800">Sales <span class="text-[#ea5a47]">Reports</span></h1>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.orders.transactions') }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    All Transactions
                </a>
                <a href="{{ route('admin.sales.export', [
                    'period' => $period,
                    'date_from' => $dateFrom instanceof Carbon ? $dateFrom->format('Y-m-d') : $dateFrom,
                    'date_to' => $dateTo instanceof Carbon ? $dateTo->format('Y-m-d') : $dateTo
                ]) }}" 
                class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export CSV
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg mb-6">
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Period Filter -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl p-6 mb-8">
            <form method="GET" action="{{ route('admin.sales.index') }}" id="filter-form" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-medium mb-2">Period</label>
                    <select name="period" id="period-select" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-[#ea5a47] outline-none">
                        <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="yesterday" {{ $period == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                        <option value="this_week" {{ $period == 'this_week' ? 'selected' : '' }}>This Week</option>
                        <option value="last_week" {{ $period == 'last_week' ? 'selected' : '' }}>Last Week</option>
                        <option value="this_month" {{ $period == 'this_month' ? 'selected' : '' }}>This Month</option>
                        <option value="last_month" {{ $period == 'last_month' ? 'selected' : '' }}>Last Month</option>
                        <option value="this_year" {{ $period == 'this_year' ? 'selected' : '' }}>This Year</option>
                        <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Custom Range</option>
                    </select>
                </div>
                
                <div class="custom-date-container md:col-span-8 {{ $period != 'custom' ? 'hidden' : '' }}" id="custom-date-container">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">From Date</label>
                            <input type="date" name="date_from" value="{{ $dateFrom instanceof Carbon ? $dateFrom->format('Y-m-d') : $dateFrom }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-[#ea5a47] focus:ring-2 focus:ring-[#ea5a47]/20 outline-none transition-all"
                                   style="min-width: 180px;">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">To Date</label>
                            <input type="date" name="date_to" value="{{ $dateTo instanceof Carbon ? $dateTo->format('Y-m-d') : $dateTo }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-[#ea5a47] focus:ring-2 focus:ring-[#ea5a47]/20 outline-none transition-all"
                                   style="min-width: 180px;">
                        </div>
                    </div>
                </div>
                
                <div class="md:col-span-2 flex items-end">
                    <button type="submit" 
                            class="w-full px-6 py-2 bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-semibold rounded-lg hover:from-[#c53030] hover:to-[#ea5a47] transition-all duration-300">
                        Apply Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Scrollable Content Container -->
        <div class="space-y-8" style="max-height: calc(100vh - 280px); overflow-y: auto; padding-right: 8px;" id="scrollable-content">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
                    <p class="text-sm text-gray-500">Total Sales</p>
                    <p class="text-3xl font-bold text-[#ea5a47]">₱{{ number_format($summary['total_sales'], 2) }}</p>
                    @if(isset($comparison))
                        <p class="text-xs mt-2 {{ $comparison['trend'] == 'up' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $comparison['trend'] == 'up' ? '▲' : '▼' }} {{ $comparison['change_percent'] }}% vs previous period
                        </p>
                    @endif
                </div>
                <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
                    <p class="text-sm text-gray-500">Total Transactions</p>
                    <p class="text-3xl font-bold text-[#ea5a47]">{{ $summary['total_transactions'] }}</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
                    <p class="text-sm text-gray-500">Average Order Value</p>
                    <p class="text-3xl font-bold text-[#ea5a47]">₱{{ number_format($summary['average_order'], 2) }}</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
                    <p class="text-sm text-gray-500">Items Sold</p>
                    <p class="text-3xl font-bold text-[#ea5a47]">{{ $summary['total_items'] }}</p>
                </div>
            </div>

            <!-- Sales Trend Chart -->
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Sales Trend</h2>
                @if($dailyBreakdown->isEmpty())
                    <p class="text-gray-500 text-center py-8">No sales data for this period</p>
                @else
                    @php
                        $dataPoints = $dailyBreakdown->count();
                        $isLongPeriod = $dataPoints > 15;
                        $maxTotal = $dailyBreakdown->max('total') ?: 1;
                        $averageTotal = $dailyBreakdown->avg('total') ?: 0;
                    @endphp
                    
                    @if($isLongPeriod)
                        <div class="relative">
                            <div class="overflow-x-auto pb-8" style="max-width: 100%;" id="chart-scroll-container">
                                <div class="relative" style="min-width: {{ $dataPoints * 45 }}px;">
                                    <div class="flex items-end gap-2" style="height: 250px;">
                                        @foreach($dailyBreakdown as $index => $item)
                                            @php
                                                $height = ($item['total'] / $maxTotal) * 200;
                                                $displayDate = $item['display_date'] ?? $item['formatted_date'];
                                                $percentageOfMax = ($item['total'] / $maxTotal) * 100;
                                                if ($percentageOfMax >= 70) {
                                                    $barColor = 'bg-green-500';
                                                } elseif ($percentageOfMax >= 40) {
                                                    $barColor = 'bg-yellow-500';
                                                } else {
                                                    $barColor = 'bg-red-500';
                                                }
                                            @endphp
                                            <div class="relative flex flex-col items-center" style="width: 35px;">
                                                <div class="relative w-full group" style="height: 200px;">
                                                    <div class="absolute bottom-0 w-full bg-gray-200 rounded-t" style="height: 200px;"></div>
                                                    <div class="absolute bottom-0 w-full {{ $barColor }} rounded-t transition-all duration-300 hover:opacity-80 cursor-pointer" 
                                                         style="height: {{ $height }}px;">
                                                    </div>
                                                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-800 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-200 whitespace-nowrap z-20 pointer-events-none shadow-lg" style="min-width: 140px;">
                                                        <div class="font-bold text-center border-b border-gray-600 pb-1 mb-1">{{ $displayDate }}</div>
                                                        <div class="flex justify-between gap-4">
                                                            <span>Sales:</span>
                                                            <span class="text-green-300 font-medium">₱{{ number_format($item['total'], 2) }}</span>
                                                        </div>
                                                        <div class="flex justify-between gap-4">
                                                            <span>Orders:</span>
                                                            <span class="text-blue-300 font-medium">{{ $item['count'] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="text-xs mt-2 text-gray-600 transform rotate-45 origin-top-left whitespace-nowrap">
                                                    {{ $item['formatted_date'] }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 text-center mt-2">← Scroll to see more →</p>
                        </div>
                    @else
                        <div class="h-80 flex items-end justify-center gap-3">
                            @foreach($dailyBreakdown as $item)
                                @php
                                    $height = ($item['total'] / $maxTotal) * 220;
                                    $displayDate = $item['display_date'] ?? $item['formatted_date'];
                                    if ($item['total'] >= $averageTotal * 1.2) {
                                        $barColor = 'bg-green-500';
                                        $shadowColor = 'rgba(34, 197, 94, 0.3)';
                                    } elseif ($item['total'] >= $averageTotal) {
                                        $barColor = 'bg-green-400';
                                        $shadowColor = 'rgba(34, 197, 94, 0.2)';
                                    } elseif ($item['total'] >= $averageTotal * 0.6) {
                                        $barColor = 'bg-yellow-500';
                                        $shadowColor = 'rgba(234, 179, 8, 0.2)';
                                    } else {
                                        $barColor = 'bg-red-500';
                                        $shadowColor = 'rgba(239, 68, 68, 0.2)';
                                    }
                                @endphp
                                <div class="flex-1 flex flex-col items-center max-w-[100px] group">
                                    <div class="w-full relative" style="height: 220px;">
                                        <div class="absolute bottom-0 w-full bg-gray-200 rounded-t" style="height: 220px;"></div>
                                        <div class="absolute bottom-0 w-full {{ $barColor }} rounded-t transition-all duration-300 group-hover:opacity-80 cursor-pointer" 
                                             style="height: {{ $height }}px; box-shadow: 0 -4px 12px {{ $shadowColor }};">
                                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-800 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-200 whitespace-nowrap z-20 pointer-events-none shadow-lg">
                                                <div class="font-bold text-center border-b border-gray-600 pb-1 mb-1">{{ $displayDate }}</div>
                                                <div class="flex justify-between gap-4">
                                                    <span>Sales:</span>
                                                    <span class="text-green-300 font-medium">₱{{ number_format($item['total'], 2) }}</span>
                                                </div>
                                                <div class="flex justify-between gap-4">
                                                    <span>Orders:</span>
                                                    <span class="text-blue-300 font-medium">{{ $item['count'] }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-xs mt-2 text-gray-600 text-center font-medium">{{ $item['formatted_date'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    <div class="flex justify-center gap-6 mt-6 pt-4 border-t border-gray-200">
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-green-500 rounded"></div>
                            <span class="text-xs text-gray-600">High Revenue (70%+)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-yellow-500 rounded"></div>
                            <span class="text-xs text-gray-600">Medium Revenue (40-69%)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-red-500 rounded"></div>
                            <span class="text-xs text-gray-600">Low Revenue (Below 40%)</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Best Sellers & Top Revenue Section -->
            @if(isset($bestSellers) && $bestSellers->isNotEmpty())
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white rounded-xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-6 h-6 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            Most Sold Items
                        </h2>
                        <span class="text-xs text-gray-400">by quantity</span>
                    </div>
                    
                    <div class="space-y-4">
                        @foreach($bestSellers as $index => $item)
                            @php
                                $rank = $index + 1;
                                $medalColor = match($rank) {
                                    1 => 'bg-yellow-500',
                                    2 => 'bg-gray-400',
                                    3 => 'bg-orange-600',
                                    default => 'bg-gray-200'
                                };
                                $maxQuantity = $bestSellers->first()->total_quantity;
                                $percentage = ($item->total_quantity / $maxQuantity) * 100;
                            @endphp
                            <div class="flex items-center gap-4 p-3 rounded-lg hover:bg-gray-50 transition-all">
                                <div class="w-10 h-10 rounded-full {{ $medalColor }} flex items-center justify-center text-white font-bold text-lg shadow-md">
                                    @if($rank <= 3)
                                        @if($rank == 1) 🥇
                                        @elseif($rank == 2) 🥈
                                        @elseif($rank == 3) 🥉
                                        @endif
                                    @else
                                        {{ $rank }}
                                    @endif
                                </div>
                                
                                <div class="flex-1">
                                    <div class="flex justify-between items-center">
                                        <h3 class="font-semibold text-gray-800">{{ $item->item_name }}</h3>
                                        <span class="text-lg font-bold text-[#ea5a47]">{{ number_format($item->total_quantity) }} sold</span>
                                    </div>
                                    <div class="flex justify-between text-sm text-gray-500 mt-1">
                                        <span>₱{{ number_format($item->total_revenue, 2) }} revenue</span>
                                        <span>{{ $item->order_count }} orders</span>
                                    </div>
                                    <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5">
                                        <div class="bg-[#ea5a47] h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-6 h-6 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Top Revenue Items
                        </h2>
                        <span class="text-xs text-gray-400">by earnings</span>
                    </div>
                    
                    <div class="space-y-4">
                        @foreach($topRevenue as $index => $item)
                            @php
                                $rank = $index + 1;
                                $medalColor = match($rank) {
                                    1 => 'bg-green-500',
                                    2 => 'bg-blue-500',
                                    3 => 'bg-purple-500',
                                    default => 'bg-gray-200'
                                };
                                $maxRevenue = $topRevenue->first()->total_revenue;
                                $percentage = ($item->total_revenue / $maxRevenue) * 100;
                            @endphp
                            <div class="flex items-center gap-4 p-3 rounded-lg hover:bg-gray-50 transition-all">
                                <div class="w-10 h-10 rounded-full {{ $medalColor }} flex items-center justify-center text-white font-bold text-lg shadow-md">
                                    {{ $rank }}
                                </div>
                                
                                <div class="flex-1">
                                    <div class="flex justify-between items-center">
                                        <h3 class="font-semibold text-gray-800">{{ $item->item_name }}</h3>
                                        <span class="text-lg font-bold text-green-600">₱{{ number_format($item->total_revenue, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm text-gray-500 mt-1">
                                        <span>{{ number_format($item->total_quantity) }} sold</span>
                                        <span>{{ $item->order_count }} orders</span>
                                    </div>
                                    <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5">
                                        <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Payment Method & Hourly Sales -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl p-6 shadow-lg">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Payment Methods</h2>
                    <div class="space-y-4">
                        @forelse($paymentBreakdown as $method => $data)
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="capitalize font-medium">{{ $method }}</span>
                                    <span class="font-medium">₱{{ number_format($data['total'], 2) }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-[#ea5a47] h-2 rounded-full" style="width: {{ $data['percentage'] }}%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span>{{ $data['count'] }} transactions</span>
                                    <span>{{ $data['percentage'] }}%</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No payment data available</p>
                        @endforelse
                    </div>
                </div>

                <!-- Hourly Breakdown - FIXED to show ALL 24 hours -->
                @if(isset($hourlyBreakdown) && $hourlyBreakdown->isNotEmpty())
                <div class="bg-white rounded-xl p-6 shadow-lg lg:col-span-2">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-6 h-6 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Hourly Sales
                        </h2>
                        <span class="text-xs text-gray-400">24-hour breakdown (12 AM - 11 PM)</span>
                    </div>
                    <div class="relative group">
                        <div class="overflow-x-auto pb-4" style="max-width: 100%;" id="hourly-chart-container">
                            <div class="flex items-end gap-1" style="min-width: 1100px; height: 300px;">
                                @for($hour = 0; $hour < 24; $hour++)
                                    @php
                                        $hourData = $hourlyBreakdown->firstWhere('hour', $hour);
                                        $maxTotal = $hourlyBreakdown->max('total') ?: 1;
                                        $height = $hourData ? ($hourData['total'] / $maxTotal) * 220 : 0;
                                        $percentageOfMax = $hourData ? ($hourData['total'] / $maxTotal) * 100 : 0;
                                        
                                        if ($percentageOfMax >= 70) {
                                            $barColor = 'bg-green-500';
                                        } elseif ($percentageOfMax >= 40) {
                                            $barColor = 'bg-yellow-500';
                                        } else {
                                            $barColor = 'bg-red-500';
                                        }
                                        
                                        if ($hour == 0) {
                                            $displayHour = '12 AM';
                                        } elseif ($hour < 12) {
                                            $displayHour = $hour . ' AM';
                                        } elseif ($hour == 12) {
                                            $displayHour = '12 PM';
                                        } else {
                                            $displayHour = ($hour - 12) . ' PM';
                                        }
                                        
                                        $isMidnight = ($hour == 0);
                                        $isNoon = ($hour == 12);
                                    @endphp
                                    <div class="flex-1 min-w-[40px] flex flex-col items-center group relative">
                                        <div class="w-full relative" style="height: 220px;">
                                            <div class="absolute bottom-0 w-full bg-gray-200 rounded-t" style="height: 220px;"></div>
                                            @if($hourData && $height > 0)
                                                <div class="absolute bottom-0 w-full {{ $barColor }} rounded-t transition-all duration-300 group-hover:opacity-80 cursor-pointer hourly-bar" 
                                                     style="height: {{ $height }}px; transition: height 0.3s ease;">
                                                </div>
                                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-800 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-200 whitespace-nowrap z-50 pointer-events-none shadow-lg" style="min-width: 130px;">
                                                    <div class="font-bold text-center border-b border-gray-600 pb-1 mb-1">{{ $displayHour }}</div>
                                                    <div class="flex justify-between gap-3">
                                                        <span>Sales:</span>
                                                        <span class="text-green-300 font-medium">₱{{ number_format($hourData['total'], 2) }}</span>
                                                    </div>
                                                    <div class="flex justify-between gap-3">
                                                        <span>Orders:</span>
                                                        <span class="text-blue-300 font-medium">{{ $hourData['count'] }}</span>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="absolute bottom-0 w-full bg-gray-100 rounded-t" style="height: 4px;"></div>
                                            @endif
                                            
                                            @if($isMidnight || $isNoon)
                                                <div class="absolute -top-1 left-1/2 transform -translate-x-1/2 w-1.5 h-1.5 bg-[#ea5a47] rounded-full"></div>
                                            @endif
                                        </div>
                                        <span class="text-xs mt-2 text-gray-600 font-medium {{ ($isMidnight || $isNoon) ? 'text-[#ea5a47] font-bold' : '' }}">
                                            {{ $displayHour }}
                                        </span>
                                    </div>
                                @endfor
                            </div>
                        </div>
                        
                        <!-- Scroll indicators -->
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 bg-gradient-to-r from-white to-transparent px-2 py-4 rounded-r-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none" id="scroll-left-indicator">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </div>
                        <div class="absolute right-0 top-1/2 -translate-y-1/2 bg-gradient-to-l from-white to-transparent px-2 py-4 rounded-l-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none" id="scroll-right-indicator">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        
                        <p class="text-xs text-gray-400 text-center mt-3">← Scroll horizontally to see all 24 hours (12 AM to 11 PM) →</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white rounded-xl p-6 shadow-lg mb-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Recent Transactions</h2>
                    <a href="{{ route('admin.orders.transactions') }}" class="text-sm text-[#ea5a47] hover:underline">View All →</a>
                </div>
                
                @if($recentTransactions->isEmpty())
                    <p class="text-gray-500 text-center py-8">No recent transactions</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[600px]">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-3">Transaction #</th>
                                    <th class="text-left py-3">Date</th>
                                    <th class="text-left py-3">Customer</th>
                                    <th class="text-right py-3">Amount</th>
                                    <th class="text-center py-3">Payment</th>
                                    <th class="text-center py-3">Items</th>
                                 </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $txn)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3 font-mono text-sm">{{ $txn->transaction_number }}</td>
                                    <td class="py-3 text-sm">{{ $txn->transaction_date->format('M d, h:i A') }}</td>
                                    <td class="py-3">{{ $txn->customer_name }}</td>
                                    <td class="py-3 text-right font-medium">₱{{ number_format($txn->total, 2) }}</td>
                                    <td class="py-3 text-center">
                                        @if($txn->payment_method == 'cash')
                                            <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded-full text-xs">Cash</span>
                                        @elseif($txn->payment_method == 'gcash')
                                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">GCash</span>
                                        @elseif($txn->payment_method == 'card')
                                            <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs">Card</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-center">{{ $txn->items_count }}</td>
                                 </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const periodSelect = document.getElementById('period-select');
    const customDateContainer = document.getElementById('custom-date-container');
    
    function toggleCustomDates() {
        if (periodSelect && customDateContainer) {
            if (periodSelect.value === 'custom') {
                customDateContainer.classList.remove('hidden');
                customDateContainer.style.opacity = '0';
                customDateContainer.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    customDateContainer.style.transition = 'all 0.3s ease';
                    customDateContainer.style.opacity = '1';
                    customDateContainer.style.transform = 'translateY(0)';
                }, 10);
            } else {
                customDateContainer.classList.add('hidden');
            }
        }
    }
    
    toggleCustomDates();
    
    if (periodSelect) {
        periodSelect.addEventListener('change', toggleCustomDates);
    }
    
    // Chart containers
    const chartContainer = document.getElementById('chart-scroll-container');
    if (chartContainer) {
        chartContainer.scrollLeft = chartContainer.scrollWidth;
    }
    
    // Hourly chart scroll - center on 12 PM
    const hourlyContainer = document.getElementById('hourly-chart-container');
    if (hourlyContainer) {
        const scrollAmount = hourlyContainer.scrollWidth / 2;
        hourlyContainer.scrollLeft = scrollAmount;
        
        const leftIndicator = document.getElementById('scroll-left-indicator');
        const rightIndicator = document.getElementById('scroll-right-indicator');
        
        function updateScrollIndicators() {
            if (leftIndicator && rightIndicator) {
                const isAtStart = hourlyContainer.scrollLeft <= 10;
                const isAtEnd = hourlyContainer.scrollLeft + hourlyContainer.clientWidth >= hourlyContainer.scrollWidth - 10;
                
                leftIndicator.style.opacity = isAtStart ? '0' : '0.6';
                rightIndicator.style.opacity = isAtEnd ? '0' : '0.6';
            }
        }
        
        hourlyContainer.addEventListener('scroll', updateScrollIndicators);
        window.addEventListener('resize', updateScrollIndicators);
        setTimeout(updateScrollIndicators, 100);
    }
    
    // Filter form submission
    const filterForm = document.getElementById('filter-form');
    let isSubmitting = false;
    
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return;
            }
            isSubmitting = true;
            if (window.showLoader) {
                window.showLoader();
            }
        });
    }
    
    window.addEventListener('pageshow', function() {
        isSubmitting = false;
    });
});
</script>

<style>
.space-y-8::-webkit-scrollbar {
    width: 8px;
}
.space-y-8::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}
.space-y-8::-webkit-scrollbar-thumb {
    background: #ea5a47;
    border-radius: 10px;
}
.space-y-8::-webkit-scrollbar-thumb:hover {
    background: #c53030;
}

.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
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

.hidden {
    display: none;
}

.custom-date-container {
    transition: all 0.3s ease;
}

input[type="date"] {
    font-family: inherit;
    cursor: pointer;
}

input[type="date"]::-webkit-calendar-picker-indicator {
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
}

input[type="date"]::-webkit-calendar-picker-indicator:hover {
    background-color: #ea5a47;
    color: white;
}

.bg-green-500, .bg-yellow-500, .bg-red-500 {
    transition: all 0.3s ease;
}

#chart-scroll-container, #hourly-chart-container {
    scroll-behavior: smooth;
}

.group .z-50 {
    z-index: 9999;
}

.shadow-lg {
    transition: all 0.3s ease;
}

.shadow-lg:hover {
    transform: translateY(-2px);
}

.hourly-bar {
    transition: height 0.3s ease;
}
</style>
@endsection