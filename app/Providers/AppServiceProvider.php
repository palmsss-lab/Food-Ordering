<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Services\CartService;
use App\Models\Order;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot()
    {
        View::composer('*', function ($view) {
            // Always set default values
            $cartCount = 0;
            $cartTotal = 0;
            $userPendingOrders = 0;
            $userTotalOrders = 0;
            $userActiveOrders = 0;
            $userCompletedOrders = 0;
            
            if (Auth::check()) {
                $userId = Auth::id();
                $userRole = session('user_role');
                
                if (!$userRole) {
                    $user = Auth::user();
                    $userRole = $user->role ?? 'client';
                    session(['user_role' => $userRole]);
                }
                
                // Cart data for all authenticated users
                try {
                    $cartService = app(CartService::class);
                    $cartCount = $cartService->getItemCount();
                    $cartTotal = $cartService->getTotal();
                } catch (\Exception $e) {
                    $cartCount = 0;
                    $cartTotal = 0;
                }
                
                // Order data specifically for client users
                if ($userRole === 'client') {
                    try {
                        // Get total orders count
                        $userTotalOrders = Order::where('user_id', $userId)->count();
                        
                        // Get pending orders count - ALL orders awaiting admin confirmation
                        // This includes:
                        // 1. Cash orders with status 'pending' and no admin confirmation
                        // 2. GCash/Card orders with status 'pending' and no admin confirmation
                        $userPendingOrders = Order::where('user_id', $userId)
                            ->where('order_status', 'pending')
                            ->whereNull('admin_confirmed_at')
                            ->count();
                        
                        // Get active orders count (preparing + ready)
                        $userActiveOrders = Order::where('user_id', $userId)
                            ->whereIn('order_status', ['preparing', 'ready'])
                            ->count();
                        
                        // Get completed orders count
                        $userCompletedOrders = Order::where('user_id', $userId)
                            ->where('order_status', 'completed')
                            ->count();
                        
                    } catch (\Exception $e) {
                        // Handle errors silently
                    }
                }
            }
            
            // Pass all variables to the view
            $view->with([
                'cartCount' => $cartCount,
                'cartTotal' => $cartTotal,
                'userTotalOrders' => $userTotalOrders,
                'userPendingOrders' => $userPendingOrders,
                'userActiveOrders' => $userActiveOrders,
                'userCompletedOrders' => $userCompletedOrders
            ]);
        });
    }
}