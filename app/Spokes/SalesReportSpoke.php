<?php

namespace App\Spokes;

use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesReportSpoke
{
    public function getBestSellers(Carbon $dateFrom, Carbon $dateTo)
    {
        Log::info('SalesReportSpoke: fetching best sellers', [
            'from' => $dateFrom->toDateString(),
            'to'   => $dateTo->toDateString(),
        ]);

        return TransactionItem::select(
                'item_name',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(subtotal) as total_revenue'),
                DB::raw('COUNT(DISTINCT transaction_id) as order_count')
            )
            ->whereHas('transaction', function ($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('transaction_date', [$dateFrom, $dateTo])
                      ->where('payment_status', 'paid');
            })
            ->groupBy('item_name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();
    }

    public function getTopRevenueItems(Carbon $dateFrom, Carbon $dateTo)
    {
        return TransactionItem::select(
                'item_name',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(subtotal) as total_revenue'),
                DB::raw('COUNT(DISTINCT transaction_id) as order_count')
            )
            ->whereHas('transaction', function ($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('transaction_date', [$dateFrom, $dateTo])
                      ->where('payment_status', 'paid');
            })
            ->groupBy('item_name')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get();
    }

    public function getComparisonData(string $period, Carbon $currentStart, Carbon $currentEnd): array
    {
        $daysDiff = $currentStart->diffInDays($currentEnd);

        $previousStart = $currentStart->copy()->subDays($daysDiff + 1);
        $previousEnd   = $currentStart->copy()->subDay();

        $currentTotal  = Transaction::whereBetween('transaction_date', [$currentStart, $currentEnd])
                            ->where('payment_status', 'paid')->sum('total')
                         - Transaction::whereBetween('transaction_date', [$currentStart, $currentEnd])
                            ->whereIn('payment_status', ['refunded', 'partial_refund'])->sum('refund_amount');

        $previousTotal = Transaction::whereBetween('transaction_date', [$previousStart, $previousEnd])
                            ->where('payment_status', 'paid')->sum('total')
                         - Transaction::whereBetween('transaction_date', [$previousStart, $previousEnd])
                            ->whereIn('payment_status', ['refunded', 'partial_refund'])->sum('refund_amount');

        $change        = $previousTotal > 0 ? (($currentTotal - $previousTotal) / $previousTotal) * 100 : 0;

        return [
            'current'        => $currentTotal,
            'previous'       => $previousTotal,
            'change'         => $change,
            'change_percent' => round(abs($change), 1),
            'trend'          => $change >= 0 ? 'up' : 'down',
            'difference'     => $currentTotal - $previousTotal,
        ];
    }
}
