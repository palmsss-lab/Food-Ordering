<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MenuItem;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $users = User::all(); // Keep existing user data
        
        // Add dashboard statistics
        $totalMenuItems = MenuItem::count();
        $totalCategories = Category::count();
        $todayOrders = Order::whereDate('created_at', today())->count();
        $pendingOrders = Order::where('order_status', 'pending')->count();
        
        return view('admin.dashboard', compact(
            'users',
            'totalMenuItems',
            'totalCategories',
            'todayOrders',
            'pendingOrders'
        ));
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }
}