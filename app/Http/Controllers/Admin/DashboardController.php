<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MenuItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {


        $totalMenuItems  = MenuItem::count();
        $totalCategories = Category::count();
        $todayOrders     = Order::whereDate('created_at', today())->count();
        $pendingOrders   = Order::where('order_status', 'pending')->whereNull('admin_confirmed_at')->count();
        $preparingOrders = Order::where('order_status', 'preparing')->count();

        // Today's net revenue from completed transactions
        $todayRevenue = Transaction::whereDate('transaction_date', today())
            ->where('payment_status', 'paid')
            ->sum('total')
            - Transaction::whereDate('transaction_date', today())
                ->whereIn('payment_status', ['refunded', 'partial_refund'])
                ->sum('refund_amount');

        // Low stock items (stock between 1 and 10)
        $lowStockItems = MenuItem::where('stock', '>', 0)->where('stock', '<=', 10)->count();
        $outOfStockItems = MenuItem::where('stock', 0)->count();

        return view('admin.dashboard', compact(
            'totalMenuItems',
            'totalCategories',
            'todayOrders',
            'pendingOrders',
            'preparingOrders',
            'todayRevenue',
            'lowStockItems',
            'outOfStockItems'
        ));
    }

    public function stats()
    {
        $pendingOrders   = Order::where('order_status', 'pending')->whereNull('admin_confirmed_at')->count();
        $preparingOrders = Order::where('order_status', 'preparing')->count();
        $todayOrders     = Order::whereDate('created_at', today())->count();

        $todayRevenue = Transaction::whereDate('transaction_date', today())
            ->where('payment_status', 'paid')
            ->sum('total')
            - Transaction::whereDate('transaction_date', today())
                ->whereIn('payment_status', ['refunded', 'partial_refund'])
                ->sum('refund_amount');

        return response()->json([
            'pending_orders'   => $pendingOrders,
            'preparing_orders' => $preparingOrders,
            'today_orders'     => $todayOrders,
            'today_revenue'    => $todayRevenue,
        ]);
    }

    public function users()
    {
        $users = User::withCount('orders')
            ->with('archivedEmail')
            ->withTrashed()
            ->orderByDesc('created_at')
            ->get();

        return view('admin.users', compact('users'));
    }
}