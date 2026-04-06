<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use App\Models\Order; // Add this
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth; // Add this

class ClientController extends Controller
{
    public function hero()
    {
        // No need to check session here because middleware already did
        // You can access the logged-in user data from session
        $user = session()->get('user'); // Get the logged-in user
        return view('client.content.hero', compact('user'));
    }

    /**
     * Get order counts for the authenticated user
     * Used for real-time badge updates
     */
    public function getOrderCounts()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'total' => 0,
                'pending' => 0,
                'active' => 0,
                'completed' => 0
            ]);
        }

        $userId = Auth::id();
        
        try {
            // Get total orders count
            $totalOrders = Order::where('user_id', $userId)->count();
            
            // Get pending orders count (orders awaiting admin confirmation)
            $pendingOrders = Order::where('user_id', $userId)
                ->where('order_status', 'pending')
                ->whereNull('admin_confirmed_at')
                ->count();
            
            // Get active orders count (preparing + ready)
            $activeOrders = Order::where('user_id', $userId)
                ->whereIn('order_status', ['preparing', 'ready'])
                ->count();
            
            // Get completed orders count
            $completedOrders = Order::where('user_id', $userId)
                ->where('order_status', 'completed')
                ->count();
            
            return response()->json([
                'success' => true,
                'total' => $totalOrders,
                'pending' => $pendingOrders,
                'active' => $activeOrders,
                'completed' => $completedOrders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'total' => 0,
                'pending' => 0,
                'active' => 0,
                'completed' => 0
            ]);
        }
    }
}   