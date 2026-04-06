<?php
// app/Http/Controllers/Admin/SalesReportController.php

namespace App\Http\Controllers\Admin;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{
    /**
     * Show sales report dashboard
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'today');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        // Set date range based on period
        switch($period) {
            case 'today':
                $dateFrom = Carbon::today()->startOfDay();
                $dateTo = Carbon::today()->endOfDay();
                break;
            case 'yesterday':
                $dateFrom = Carbon::yesterday()->startOfDay();
                $dateTo = Carbon::yesterday()->endOfDay();
                break;
            case 'this_week':
                $dateFrom = Carbon::now()->startOfWeek();
                $dateTo = Carbon::now()->endOfWeek();
                break;
            case 'last_week':
                $dateFrom = Carbon::now()->subWeek()->startOfWeek();
                $dateTo = Carbon::now()->subWeek()->endOfWeek();
                break;
            case 'this_month':
                $dateFrom = Carbon::now()->startOfMonth();
                $dateTo = Carbon::now()->endOfMonth();
                break;
            case 'last_month':
                $dateFrom = Carbon::now()->subMonth()->startOfMonth();
                $dateTo = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'this_year':
                $dateFrom = Carbon::now()->startOfYear();
                $dateTo = Carbon::now()->endOfYear();
                break;
            case 'custom':
                $dateFrom = $request->filled('date_from') ? Carbon::parse($request->date_from)->startOfDay() : Carbon::now()->startOfMonth();
                $dateTo = $request->filled('date_to') ? Carbon::parse($request->date_to)->endOfDay() : Carbon::now()->endOfDay();
                break;
            default:
                $dateFrom = Carbon::today()->startOfDay();
                $dateTo = Carbon::today()->endOfDay();
        }

        // Get transactions for the selected period
        $transactions = Transaction::whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->with('items')
            ->orderBy('transaction_date', 'desc')
            ->get();

        // Calculate items_count for each transaction
        foreach ($transactions as $transaction) {
            $transaction->items_count = $transaction->items->count();
        }

        // Get Best Sellers (Most Quantity Sold)
        $bestSellers = $this->getBestSellers($dateFrom, $dateTo);
        
        // Get Top Revenue Items (Most Money Generated)
        $topRevenue = $this->getTopRevenueItems($dateFrom, $dateTo);

        // Summary statistics
        $summary = [
            'total_sales' => $transactions->sum('total'),
            'total_transactions' => $transactions->count(),
            'average_order' => $transactions->count() > 0 ? $transactions->avg('total') : 0,
            'total_items' => $transactions->sum('items_count'),
        ];

        // Payment method breakdown
        $totalCount = $transactions->count() ?: 1;
        $paymentBreakdown = $transactions->groupBy('payment_method')
            ->map(function($group) use ($totalCount) {
                return [
                    'count' => $group->count(),
                    'total' => $group->sum('total'),
                    'percentage' => round(($group->count() / $totalCount) * 100, 1),
                ];
            });

        // Daily/Monthly breakdown for charts
        $dailyBreakdown = collect();
        if ($transactions->isNotEmpty()) {
            $daysCount = $dateFrom->diffInDays($dateTo);
            
            if ($period === 'this_year' || $period === 'last_year' || $daysCount > 60) {
                $dailyBreakdown = $transactions->groupBy(function($txn) {
                    return $txn->transaction_date->format('Y-m');
                })->map(function($group, $month) {
                    $date = Carbon::parse($month . '-01');
                    return [
                        'date' => $month,
                        'count' => $group->count(),
                        'total' => $group->sum('total'),
                        'formatted_date' => $date->format('M Y'),
                        'display_date' => $date->format('F Y'),
                        'is_monthly' => true,
                    ];
                })->values();
            } else {
                $dailyBreakdown = $transactions->groupBy(function($txn) {
                    return $txn->transaction_date->format('Y-m-d');
                })->map(function($group, $date) {
                    return [
                        'date' => $date,
                        'count' => $group->count(),
                        'total' => $group->sum('total'),
                        'formatted_date' => Carbon::parse($date)->format('M d'),
                        'display_date' => Carbon::parse($date)->format('F j, Y'),
                        'is_monthly' => false,
                    ];
                })->values();
            }
        }

        // Hourly breakdown
        $hourlyBreakdown = collect();
        if (in_array($period, ['today', 'yesterday']) && $transactions->isNotEmpty()) {
            $hourlyBreakdown = $transactions->groupBy(function($txn) {
                return $txn->transaction_date->format('H');
            })->map(function($group, $hour) {
                return [
                    'hour' => (int)$hour,
                    'count' => $group->count(),
                    'total' => $group->sum('total'),
                    'label' => $hour . ':00',
                ];
            })->values();
        }

        // Get comparison with previous period
        $comparison = $this->getComparisonData($period, $dateFrom, $dateTo);

        // Get recent transactions for table
        $recentTransactions = Transaction::with('items')
            ->orderBy('transaction_date', 'desc')
            ->limit(10)
            ->get();

        foreach ($recentTransactions as $transaction) {
            $transaction->items_count = $transaction->items->count();
        }

        return view('admin.sales-report.index', compact(
            'summary',
            'paymentBreakdown',
            'dailyBreakdown',
            'hourlyBreakdown',
            'recentTransactions',
            'comparison',
            'period',
            'dateFrom',
            'dateTo',
            'bestSellers',
            'topRevenue'
        ));
    }

    /**
     * Get best selling items (most quantity sold)
     */
    private function getBestSellers($dateFrom, $dateTo)
    {
        return TransactionItem::select(
                'item_name',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(subtotal) as total_revenue'),
                DB::raw('COUNT(DISTINCT transaction_id) as order_count')
            )
            ->whereHas('transaction', function($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('transaction_date', [$dateFrom, $dateTo]);
            })
            ->groupBy('item_name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get top revenue items (most money generated)
     */
    private function getTopRevenueItems($dateFrom, $dateTo)
    {
        return TransactionItem::select(
                'item_name',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(subtotal) as total_revenue'),
                DB::raw('COUNT(DISTINCT transaction_id) as order_count')
            )
            ->whereHas('transaction', function($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('transaction_date', [$dateFrom, $dateTo]);
            })
            ->groupBy('item_name')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get comparison with previous period
     */
    private function getComparisonData($period, $currentStart, $currentEnd)
    {
        // Parse dates
        $currentStart = $currentStart instanceof Carbon ? $currentStart : Carbon::parse($currentStart);
        $currentEnd = $currentEnd instanceof Carbon ? $currentEnd : Carbon::parse($currentEnd);
        
        $daysDiff = $currentStart->diffInDays($currentEnd);

        $previousStart = $currentStart->copy()->subDays($daysDiff + 1);
        $previousEnd = $currentStart->copy()->subDay();

        $currentTotal = Transaction::whereBetween('transaction_date', [$currentStart, $currentEnd])->sum('total');
        $previousTotal = Transaction::whereBetween('transaction_date', [$previousStart, $previousEnd])->sum('total');

        $change = $previousTotal > 0 ? (($currentTotal - $previousTotal) / $previousTotal) * 100 : 0;
        $changePercent = round(abs($change), 1);

        return [
            'current' => $currentTotal,
            'previous' => $previousTotal,
            'change' => $change,
            'change_percent' => $changePercent,
            'trend' => $change >= 0 ? 'up' : 'down',
            'difference' => $currentTotal - $previousTotal,
        ];
    }

    /**
     * Export report as CSV
     */
    public function export(Request $request)
    {
        $period = $request->get('period', 'today');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        // Set date range based on period
        switch($period) {
            case 'today':
                $dateFrom = Carbon::today()->startOfDay();
                $dateTo = Carbon::today()->endOfDay();
                break;
            case 'yesterday':
                $dateFrom = Carbon::yesterday()->startOfDay();
                $dateTo = Carbon::yesterday()->endOfDay();
                break;
            case 'this_week':
                $dateFrom = Carbon::now()->startOfWeek();
                $dateTo = Carbon::now()->endOfWeek();
                break;
            case 'last_week':
                $dateFrom = Carbon::now()->subWeek()->startOfWeek();
                $dateTo = Carbon::now()->subWeek()->endOfWeek();
                break;
            case 'this_month':
                $dateFrom = Carbon::now()->startOfMonth();
                $dateTo = Carbon::now()->endOfMonth();
                break;
            case 'last_month':
                $dateFrom = Carbon::now()->subMonth()->startOfMonth();
                $dateTo = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'this_year':
                $dateFrom = Carbon::now()->startOfYear();
                $dateTo = Carbon::now()->endOfYear();
                break;
            case 'custom':
                $dateFrom = $request->filled('date_from') ? Carbon::parse($request->date_from)->startOfDay() : Carbon::now()->startOfMonth();
                $dateTo = $request->filled('date_to') ? Carbon::parse($request->date_to)->endOfDay() : Carbon::now()->endOfDay();
                break;
            default:
                $dateFrom = Carbon::today()->startOfDay();
                $dateTo = Carbon::today()->endOfDay();
        }

        // Get transactions for the selected period
        $transactions = Transaction::with('items')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->orderBy('transaction_date', 'desc')
            ->get();

        // Calculate items_count
        foreach ($transactions as $txn) {
            $txn->items_count = $txn->items->count();
        }

        // Get best sellers for export
        $bestSellers = $this->getBestSellers($dateFrom, $dateTo);
        $topRevenue = $this->getTopRevenueItems($dateFrom, $dateTo);

        // Format period name for filename
        $periodName = str_replace('_', '-', $period);
        $filename = 'sales-report-' . $periodName . '-' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($transactions, $dateFrom, $dateTo, $period, $bestSellers, $topRevenue) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // ==================== HEADER SECTION ====================
            fputcsv($file, ['SALES REPORT']);
            fputcsv($file, []);
            
            // Report Information
            fputcsv($file, ['Report Information']);
            fputcsv($file, ['Period', ucwords(str_replace('_', ' ', $period))]);
            fputcsv($file, ['Date Range', $dateFrom->format('M d, Y') . ' - ' . $dateTo->format('M d, Y')]);
            fputcsv($file, ['Generated On', Carbon::now('Asia/Manila')->format('M d, Y h:i A')]);
            fputcsv($file, ['Total Transactions', $transactions->count()]);
            fputcsv($file, []);
            
            // ==================== SUMMARY SECTION ====================
            fputcsv($file, ['SUMMARY']);
            fputcsv($file, ['Metric', 'Value']);
            fputcsv($file, ['Total Sales', '₱' . number_format($transactions->sum('total'), 2)]);
            fputcsv($file, ['Total Transactions', $transactions->count()]);
            fputcsv($file, ['Average Order Value', '₱' . number_format($transactions->avg('total') ?: 0, 2)]);
            fputcsv($file, ['Total Items Sold', $transactions->sum('items_count')]);
            fputcsv($file, []);
            
            // ==================== BEST SELLERS SECTION ====================
            fputcsv($file, ['BEST SELLERS (Most Quantity Sold)']);
            fputcsv($file, ['Rank', 'Item Name', 'Quantity Sold', 'Revenue', 'Orders']);
            
            foreach ($bestSellers as $index => $item) {
                fputcsv($file, [
                    $index + 1,
                    $item->item_name,
                    $item->total_quantity,
                    '₱' . number_format($item->total_revenue, 2),
                    $item->order_count
                ]);
            }
            fputcsv($file, []);
            
            // ==================== TOP REVENUE SECTION ====================
            fputcsv($file, ['TOP REVENUE ITEMS']);
            fputcsv($file, ['Rank', 'Item Name', 'Revenue', 'Quantity Sold', 'Orders']);
            
            foreach ($topRevenue as $index => $item) {
                fputcsv($file, [
                    $index + 1,
                    $item->item_name,
                    '₱' . number_format($item->total_revenue, 2),
                    $item->total_quantity,
                    $item->order_count
                ]);
            }
            fputcsv($file, []);
            
            // ==================== PAYMENT BREAKDOWN ====================
            fputcsv($file, ['PAYMENT METHOD BREAKDOWN']);
            fputcsv($file, ['Payment Method', 'Transactions', 'Total Amount', 'Percentage']);
            
            $paymentBreakdown = $transactions->groupBy('payment_method');
            foreach ($paymentBreakdown as $method => $group) {
                $percentage = $transactions->count() > 0 
                    ? round(($group->count() / $transactions->count()) * 100, 1) 
                    : 0;
                
                fputcsv($file, [
                    ucfirst($method),
                    $group->count(),
                    '₱' . number_format($group->sum('total'), 2),
                    $percentage . '%'
                ]);
            }
            fputcsv($file, []);
            
            // ==================== TRANSACTIONS LIST ====================
            fputcsv($file, ['TRANSACTION DETAILS']);
            fputcsv($file, [
                'Transaction #',
                'Order #',
                'Date',
                'Time',
                'Customer',
                'Items',
                'Amount',
                'Payment Method',
                'Status'
            ]);
            
            foreach ($transactions as $txn) {
                fputcsv($file, [
                    $txn->transaction_number,
                    $txn->order_number,
                    $txn->transaction_date->format('Y-m-d'),
                    $txn->transaction_date->format('h:i A'),
                    $txn->customer_name,
                    $txn->items_count . ' item(s)',
                    number_format($txn->total, 2),
                    ucfirst($txn->payment_method),
                    'Paid'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get sales data for charts (AJAX)
     */
    public function chartData(Request $request)
    {
        $period = $request->get('period', 'this_month');
        
        switch($period) {
            case 'today':
                $start = Carbon::today()->startOfDay();
                $end = Carbon::today()->endOfDay();
                $groupBy = 'hour';
                break;
            case 'this_week':
                $start = Carbon::now()->startOfWeek();
                $end = Carbon::now()->endOfWeek();
                $groupBy = 'day';
                break;
            case 'this_month':
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                $groupBy = 'day';
                break;
            case 'this_year':
                $start = Carbon::now()->startOfYear();
                $end = Carbon::now()->endOfYear();
                $groupBy = 'month';
                break;
            default:
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                $groupBy = 'day';
        }
        
        $transactions = Transaction::whereBetween('transaction_date', [$start, $end])
            ->get();
        
        $data = [];
        
        if ($groupBy === 'hour') {
            for ($i = 0; $i < 24; $i++) {
                $hour = str_pad($i, 2, '0', STR_PAD_LEFT);
                $data[] = [
                    'label' => $hour . ':00',
                    'sales' => $transactions->where('transaction_date->hour', $i)->sum('total'),
                    'count' => $transactions->where('transaction_date->hour', $i)->count(),
                ];
            }
        } elseif ($groupBy === 'day') {
            $current = $start->copy();
            while ($current <= $end) {
                $dateStr = $current->format('Y-m-d');
                $data[] = [
                    'label' => $current->format('M d'),
                    'sales' => $transactions->where('transaction_date->format', 'Y-m-d', $dateStr)->sum('total'),
                    'count' => $transactions->where('transaction_date->format', 'Y-m-d', $dateStr)->count(),
                ];
                $current->addDay();
            }
        } elseif ($groupBy === 'month') {
            $current = $start->copy();
            while ($current <= $end) {
                $monthStr = $current->format('Y-m');
                $data[] = [
                    'label' => $current->format('M Y'),
                    'sales' => $transactions->where('transaction_date->format', 'Y-m', $monthStr)->sum('total'),
                    'count' => $transactions->where('transaction_date->format', 'Y-m', $monthStr)->count(),
                ];
                $current->addMonth();
            }
        }
        
        return response()->json($data);
    }

    /**
     * Show all transactions page
     */
    public function transactions(Request $request)
    {
        $query = Transaction::with('items');

        // Apply filters
        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_number', 'like', "%{$search}%")
                  ->orWhere('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        // Clone query for summary before pagination
        $summaryQuery = clone $query;
        
        $transactions = $query->orderBy('transaction_date', 'desc')->paginate(20);
        
        // Calculate items_count for each transaction
        foreach ($transactions as $transaction) {
            $transaction->items_count = $transaction->items->count();
        }
        
        // Get summary for filtered results
        $summary = [
            'total_sales' => $summaryQuery->sum('total'),
            'total_transactions' => $summaryQuery->count(),
            'average_order' => $summaryQuery->avg('total') ?? 0,
            'total_items' => $summaryQuery->with('items')->get()->sum(function($t) {
                return $t->items->count();
            }),
        ];

        return view('admin.sales-report.transactions', compact('transactions', 'summary'));
    }
}