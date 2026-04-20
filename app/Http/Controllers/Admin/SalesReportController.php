<?php
// app/Http/Controllers/Admin/SalesReportController.php

namespace App\Http\Controllers\Admin;

use App\Models\Transaction;
use App\Http\Controllers\Controller;
use App\Spokes\SalesReportSpoke;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalesReportController extends Controller
{
    public function __construct(private SalesReportSpoke $salesReportSpoke) {}

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

        // Get ALL transactions for the period (for display and full breakdown)
        $transactions = Transaction::whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->with('items')
            ->orderBy('transaction_date', 'desc')
            ->get();

        // Calculate items_count for each transaction
        foreach ($transactions as $transaction) {
            $transaction->items_count = $transaction->items->count();
        }

        // Only paid (non-refunded) transactions count as revenue
        $paidTransactions = $transactions->where('payment_status', 'paid');

        // Refunded transactions
        $refundedTransactions = $transactions->whereIn('payment_status', ['refunded', 'partial_refund']);

        $grossSales     = $transactions->whereIn('payment_status', ['paid', 'refunded', 'partial_refund'])->sum('total');
        $totalRefunded  = $refundedTransactions->sum('refund_amount');
        $netRevenue     = $grossSales - $totalRefunded;

        // Delegate to SalesReportSpoke via hub
        $bestSellers = $this->salesReportSpoke->getBestSellers($dateFrom, $dateTo);
        $topRevenue  = $this->salesReportSpoke->getTopRevenueItems($dateFrom, $dateTo);

        // Summary statistics
        $summary = [
            'gross_sales'        => $grossSales,
            'total_refunded'     => $totalRefunded,
            'net_revenue'        => $netRevenue,
            'total_transactions' => $paidTransactions->count(),
            'refund_count'       => $refundedTransactions->count(),
            'average_order'      => $paidTransactions->count() > 0 ? $paidTransactions->sum('total') / $paidTransactions->count() : 0,
            'total_items'        => $paidTransactions->sum('items_count'),
            // keep legacy key so existing view references don't break
            'total_sales'        => $netRevenue,
        ];

        // Payment method breakdown — only paid transactions
        $totalCount = $paidTransactions->count() ?: 1;
        $paymentBreakdown = $paidTransactions->groupBy('payment_method')
            ->map(function($group) use ($totalCount) {
                return [
                    'count' => $group->count(),
                    'total' => $group->sum('total'),
                    'percentage' => round(($group->count() / $totalCount) * 100, 1),
                ];
            });

        // Daily/Monthly breakdown for charts — use net revenue per period
        $dailyBreakdown = collect();
        if ($paidTransactions->isNotEmpty() || $refundedTransactions->isNotEmpty()) {
            $daysCount = $dateFrom->diffInDays($dateTo);

            if ($period === 'this_year' || $period === 'last_year' || $daysCount > 60) {
                $dailyBreakdown = $transactions->groupBy(function($txn) {
                    return $txn->transaction_date->format('Y-m');
                })->map(function($group, $month) {
                    $date          = Carbon::parse($month . '-01');
                    $paid          = $group->where('payment_status', 'paid');
                    $refunded      = $group->whereIn('payment_status', ['refunded', 'partial_refund']);
                    $refundAmount  = (float) $refunded->sum('refund_amount');
                    $net           = (float) $paid->sum('total') - $refundAmount;
                    return [
                        'date'           => $month,
                        'count'          => $paid->count(),
                        'total'          => $net,
                        'refund_amount'  => $refundAmount,
                        'refund_count'   => $refunded->count(),
                        'formatted_date' => $date->format('M Y'),
                        'display_date'   => $date->format('F Y'),
                        'is_monthly'     => true,
                    ];
                })->values();
            } else {
                $dailyBreakdown = $transactions->groupBy(function($txn) {
                    return $txn->transaction_date->format('Y-m-d');
                })->map(function($group, $date) {
                    $paid          = $group->where('payment_status', 'paid');
                    $refunded      = $group->whereIn('payment_status', ['refunded', 'partial_refund']);
                    $refundAmount  = (float) $refunded->sum('refund_amount');
                    $net           = (float) $paid->sum('total') - $refundAmount;
                    return [
                        'date'           => $date,
                        'count'          => $paid->count(),
                        'total'          => $net,
                        'refund_amount'  => $refundAmount,
                        'refund_count'   => $refunded->count(),
                        'formatted_date' => Carbon::parse($date)->format('M d'),
                        'display_date'   => Carbon::parse($date)->format('F j, Y'),
                        'is_monthly'     => false,
                    ];
                })->values();
            }
        }

        // Hourly breakdown — paid only
        $hourlyBreakdown = collect();
        if (in_array($period, ['today', 'yesterday']) && $paidTransactions->isNotEmpty()) {
            $hourlyBreakdown = $paidTransactions->groupBy(function($txn) {
                return $txn->transaction_date->format('H');
            })->map(function($group, $hour) {
                return [
                    'hour'  => (int)$hour,
                    'count' => $group->count(),
                    'total' => $group->sum('total'),
                    'label' => $hour . ':00',
                ];
            })->values();
        }

        $comparison = $this->salesReportSpoke->getComparisonData($period, $dateFrom, $dateTo);

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
     * Export report as CSV
     */
    public function export(Request $request)
    {
        set_time_limit(120);

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

        $bestSellers = $this->salesReportSpoke->getBestSellers($dateFrom, $dateTo);
        $topRevenue  = $this->salesReportSpoke->getTopRevenueItems($dateFrom, $dateTo);

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
            $paidTxns      = $transactions->where('payment_status', 'paid');
            $refundedTxns  = $transactions->whereIn('payment_status', ['refunded', 'partial_refund']);
            $grossSales    = $transactions->whereIn('payment_status', ['paid', 'refunded', 'partial_refund'])->sum('total');
            $totalRefunded = $refundedTxns->sum('refund_amount');
            $netRevenue    = $grossSales - $totalRefunded;

            fputcsv($file, ['SUMMARY']);
            fputcsv($file, ['Metric', 'Value']);
            fputcsv($file, ['Gross Sales',          '₱' . number_format($grossSales, 2)]);
            fputcsv($file, ['Total Refunded',        '₱' . number_format($totalRefunded, 2)]);
            fputcsv($file, ['Net Revenue',           '₱' . number_format($netRevenue, 2)]);
            fputcsv($file, ['Paid Transactions',     $paidTxns->count()]);
            fputcsv($file, ['Refunded Transactions', $refundedTxns->count()]);
            fputcsv($file, ['Average Order Value',   '₱' . number_format($paidTxns->count() > 0 ? $paidTxns->sum('total') / $paidTxns->count() : 0, 2)]);
            fputcsv($file, ['Total Items Sold',      $paidTxns->sum('items_count')]);
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
                'Refunded',
                'Payment Method',
                'Status'
            ]);

            foreach ($transactions as $txn) {
                $statusLabel = match($txn->payment_status) {
                    'paid'           => 'Paid',
                    'refunded'       => 'Refunded',
                    'partial_refund' => 'Partial Refund',
                    default          => ucfirst($txn->payment_status),
                };
                fputcsv($file, [
                    $txn->transaction_number,
                    $txn->order_number,
                    $txn->transaction_date->format('Y-m-d'),
                    $txn->transaction_date->format('h:i A'),
                    $txn->customer_name,
                    $txn->items_count . ' item(s)',
                    number_format($txn->total, 2),
                    $txn->refund_amount ? number_format($txn->refund_amount, 2) : '-',
                    ucfirst($txn->payment_method),
                    $statusLabel,
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