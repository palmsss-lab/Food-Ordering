<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use App\Services\CartService;
use App\Models\Order;

// Hub
use App\Hub\SystemHub;

// Spokes
use App\Spokes\AccountSpoke;
use App\Spokes\MenuSpoke;
use App\Spokes\OrderingSpoke;
use App\Spokes\PaymentSpoke;
use App\Spokes\TransactionSpoke;
use App\Spokes\SalesReportSpoke;

// Events
use App\Events\UserRegistered;
use App\Events\OrderPlaced;
use App\Events\OrderCompleted;
use App\Events\CashOrderPaid;
use App\Events\PaymentProcessed;

// Listeners
use App\Listeners\LogUserRegistration;
use App\Listeners\DeductMenuStock;
use App\Listeners\RecordTransaction;
use App\Listeners\HandlePaymentProcessed;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register each spoke as a singleton
        $this->app->singleton(AccountSpoke::class);
        $this->app->singleton(MenuSpoke::class);
        $this->app->singleton(OrderingSpoke::class);
        $this->app->singleton(PaymentSpoke::class);
        $this->app->singleton(TransactionSpoke::class);
        $this->app->singleton(SalesReportSpoke::class);

        // Register the hub as a singleton and attach all spokes
        $this->app->singleton(SystemHub::class, function ($app) {
            $hub = new SystemHub();
            $hub->registerSpoke('account',      $app->make(AccountSpoke::class));
            $hub->registerSpoke('menu',         $app->make(MenuSpoke::class));
            $hub->registerSpoke('ordering',     $app->make(OrderingSpoke::class));
            $hub->registerSpoke('payment',      $app->make(PaymentSpoke::class));
            $hub->registerSpoke('transaction',  $app->make(TransactionSpoke::class));
            $hub->registerSpoke('sales_report', $app->make(SalesReportSpoke::class));
            return $hub;
        });
    }

    public function boot()
    {
        // Wire events to listeners through the hub
        Event::listen(UserRegistered::class,  LogUserRegistration::class);
        Event::listen(OrderPlaced::class,     DeductMenuStock::class);
        Event::listen(OrderCompleted::class,  RecordTransaction::class);
        Event::listen(CashOrderPaid::class,   RecordTransaction::class);
        Event::listen(PaymentProcessed::class, HandlePaymentProcessed::class);

        View::composer('*', function ($view) {
            $defaults = [
                'cartCount'           => 0,
                'cartTotal'           => 0,
                'userTotalOrders'     => 0,
                'userPendingOrders'   => 0,
                'userActiveOrders'    => 0,
                'userCompletedOrders' => 0,
            ];

            if (!Auth::check()) {
                $view->with($defaults);
                return;
            }

            $userId   = Auth::id();
            $userRole = session('user_role') ?? Auth::user()->role ?? 'client';

            if (!session('user_role')) {
                session(['user_role' => $userRole]);
            }

            // Cache all view-composer queries per user for 5 seconds so concurrent
            // Livewire polls and multi-browser sessions don't hammer the database.
            $data = \Illuminate\Support\Facades\Cache::remember(
                "view_composer_user_{$userId}",
                5,
                function () use ($userId, $userRole) {
                    $cartCount = 0;
                    $cartTotal = 0;

                    try {
                        $cartService = app(CartService::class);
                        $cartCount   = $cartService->getItemCount();
                        $cartTotal   = $cartService->getTotal();
                    } catch (\Exception $e) {}

                    $userTotalOrders     = 0;
                    $userPendingOrders   = 0;
                    $userActiveOrders    = 0;
                    $userCompletedOrders = 0;

                    if ($userRole === 'client') {
                        try {
                            $row = Order::selectRaw("
                                COUNT(*) AS total,
                                SUM(CASE WHEN order_status = 'pending' AND admin_confirmed_at IS NULL THEN 1 ELSE 0 END) AS pending,
                                SUM(CASE WHEN order_status IN ('preparing','ready')                   THEN 1 ELSE 0 END) AS active,
                                SUM(CASE WHEN order_status = 'completed'                              THEN 1 ELSE 0 END) AS completed
                            ")->where('user_id', $userId)->first();

                            $userTotalOrders     = (int) ($row->total     ?? 0);
                            $userPendingOrders   = (int) ($row->pending   ?? 0);
                            $userActiveOrders    = (int) ($row->active    ?? 0);
                            $userCompletedOrders = (int) ($row->completed ?? 0);
                        } catch (\Exception $e) {}
                    }

                    return compact(
                        'cartCount', 'cartTotal',
                        'userTotalOrders', 'userPendingOrders',
                        'userActiveOrders', 'userCompletedOrders'
                    );
                }
            );

            $view->with($data);
        });
    }
}