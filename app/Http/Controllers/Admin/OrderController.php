<?php
// app/Http/Controllers/Admin/OrderController.php

namespace App\Http\Controllers\Admin;

use App\Events\CashOrderPaid;
use App\Events\OrderCompleted;
use App\Hub\SystemHub;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{

    public function dashboard() {
        return view('admin.dashboard');
    }

    /**
     * Display orders based on status tab.
     */
    public function index(Request $request)
    {
        // Data is fetched by the Livewire\Admin\OrdersList component
        return view('admin.orders.index');
    }

    /**
     * Show order details.
     */
    public function show(Order $order)
    {
        $order->load(['items', 'payments', 'user' => function($q) {
            $q->withTrashed(); // Include soft-deleted users
        }]);
        
        return view('admin.orders.show', compact('order'));
    }

    // ==================== ADMIN CONFIRMATION FOR ALL PAYMENT METHODS ====================

    /**
     * Confirm order (admin approves the order for kitchen)
     * This works for ALL payment methods (cash, gcash, card)
     */
    public function confirmOrder(Request $request, Order $order)
    {
        // Check if order can be confirmed
        if ($order->order_status !== 'pending') {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Only pending orders can be confirmed.'], 422);
            }
            return back()->with('error', 'Only pending orders can be confirmed.');
        }

        if ($order->admin_confirmed_at) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Order has already been confirmed.'], 422);
            }
            return back()->with('error', 'Order has already been confirmed.');
        }

        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($order, $request) {
            // Set admin_confirmed_at and update order status to preparing
            $order->update([
                'admin_confirmed_at' => now(),
                'order_status' => 'preparing', // Move to kitchen for preparation
                'prepared_at' => now(),
                'notes' => $request->notes ? ($order->notes . "\n" . $request->notes) : $order->notes,
            ]);

            // For cash orders, payment_status remains 'cash on pickup'
            // For gcash/card, payment_status should already be 'paid'
        });

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "Order #{$order->order_number} confirmed and moved to preparing."]);
        }
        return redirect()->route('admin.orders.index', ['tab' => 'preparing'])
            ->with('success', "Order #{$order->order_number} confirmed and moved to preparing.");
    }

    /**
     * Reject order
     */
    public function rejectOrder(Request $request, Order $order)
    {
        if ($order->order_status !== 'pending') {
            return back()->with('error', 'Only pending orders can be rejected.');
        }

        if ($order->admin_confirmed_at) {
            return back()->with('error', 'Order has already been confirmed and cannot be rejected.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        DB::transaction(function () use ($order, $request) {
            $order->update([
                'payment_status' => 'failed',
                'order_status' => 'cancelled',
                'rejection_reason' => $request->rejection_reason,
                'cancelled_at' => now(),
            ]);

            // Update any pending payments to failed
            foreach ($order->payments as $payment) {
                if ($payment->payment_status === 'pending') {
                    $payment->update([
                        'payment_status' => 'failed',
                        'failure_reason' => 'Order rejected by admin'
                    ]);
                }
            }
        });

        return redirect()->route('admin.orders.index', ['tab' => 'pending'])
            ->with('success', "Order #{$order->order_number} has been rejected.");
    }

    /**
     * Mark cash order as paid upon pickup
     */
    public function markAsPaid(Order $order, Request $request)
    {
        $isJson = $request->wantsJson();

        if ($order->payment_method !== 'cash') {
            return $isJson
                ? response()->json(['success' => false, 'message' => 'Only cash orders can be marked as paid.'], 422)
                : back()->with('error', 'Only cash orders can be marked as paid via this method.');
        }

        if ($order->payment_status === 'paid') {
            return $isJson
                ? response()->json(['success' => false, 'message' => 'Order is already marked as paid.'], 422)
                : back()->with('error', 'Order is already marked as paid.');
        }

        DB::beginTransaction();

        try {
            $order->update(['payment_status' => 'paid']);

            $latestPayment = $order->latestPayment;
            if ($latestPayment && $latestPayment->payment_method === 'cash') {
                $latestPayment->update(['payment_status' => 'completed', 'paid_at' => now()]);
            }

            // Route transaction creation through the hub to TransactionSpoke
            app(SystemHub::class)->dispatch(new CashOrderPaid($order));

            DB::commit();

            return $isJson
                ? response()->json(['success' => true, 'message' => "Order #{$order->order_number} marked as paid."])
                : redirect()->back()->with('success', "Order #{$order->order_number} marked as paid and added to transactions.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to mark order as paid: ' . $e->getMessage());
            return $isJson
                ? response()->json(['success' => false, 'message' => 'Failed to mark order as paid.'], 500)
                : back()->with('error', 'Failed to mark order as paid: ' . $e->getMessage());
        }
    }

    // ==================== ORDER STATUS METHODS ====================

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:preparing,ready,completed'
        ]);

        // Admin can only move forward: preparing → ready → completed
        $allowedTransitions = [
            'preparing' => ['ready'],
            'ready' => ['completed'],
        ];

        $currentStatus = $order->order_status;
        $newStatus = $request->status;

        // Check if the transition is allowed
        if (!isset($allowedTransitions[$currentStatus]) || !in_array($newStatus, $allowedTransitions[$currentStatus])) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => "Cannot change order from {$currentStatus} to {$newStatus}."], 422);
            }
            return redirect()->back()
                ->with('error', "Cannot change order from {$currentStatus} to {$newStatus}.");
        }

        $timestampField = $newStatus . '_at';

        DB::beginTransaction();
        
        try {
            $order->update([
                'order_status' => $newStatus,
                $timestampField => now(),
            ]);

            // If order is completed and NOT cash (GCash/Card), route transaction creation through the hub
            if ($newStatus === 'completed' && $order->payment_method !== 'cash') {
                app(SystemHub::class)->dispatch(new OrderCompleted($order));
            }

            DB::commit();

            $statusNames = [
                'preparing' => 'Preparing',
                'ready' => 'Ready for Pickup',
                'completed' => 'Completed',
            ];

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => "Order #{$order->order_number} marked as {$statusNames[$newStatus]}."]);
            }
            return redirect()->back()
                ->with('success', "Order #{$order->order_number} marked as {$statusNames[$newStatus]}.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update order status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update order status.');
        }
    }

    /**
     * Mark order as ready for pickup
     */
    public function markAsReady(Request $request, Order $order)
    {
        if ($order->order_status !== 'preparing') {
            return back()->with('error', 'Only orders in preparation can be marked as ready.');
        }

        DB::beginTransaction();
        try {
            $order->update([
                'order_status' => 'ready',
                'ready_at' => now(),
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to mark order as ready: ' . $e->getMessage());
            return back()->with('error', 'Failed to update order status.');
        }

        return redirect()->route('admin.orders.index', ['tab' => 'ready'])
            ->with('success', "Order #{$order->order_number} is ready for pickup.");
    }

    /**
     * Legacy method for backward compatibility
     */
    public function confirmCashPayment(Request $request, Order $order)
    {
        return $this->confirmOrder($request, $order);
    }

    /**
     * Legacy method for backward compatibility
     */
    public function rejectCashPayment(Request $request, Order $order)
    {
        return $this->rejectOrder($request, $order);
    }

    /**
     * Check for order updates (for admin polling)
     */
    public function checkAdminUpdates(Request $request)
    {
        try {
            $lastUpdate = $request->last_update ?? 0;
            $lastUpdateDate = date('Y-m-d H:i:s', $lastUpdate / 1000);
            
            Log::info('Admin check updates called', [
                'last_update' => $lastUpdate,
                'last_update_date' => $lastUpdateDate
            ]);
            
            // Include soft-deleted users in the query
            $updatedOrders = Order::with(['user' => function($q) {
                $q->withTrashed(); // Include soft-deleted users
            }, 'items', 'latestPayment'])
                ->where(function($query) use ($lastUpdateDate) {
                    $query->where('updated_at', '>', $lastUpdateDate)
                        ->orWhere('admin_confirmed_at', '>', $lastUpdateDate)
                        ->orWhere('completed_at', '>', $lastUpdateDate)
                        ->orWhere('created_at', '>', $lastUpdateDate);
                })
                ->orderBy('updated_at', 'desc')
                ->get()
                ->map(function($order) {
                    $paymentMethod = $order->latestPayment ? $order->latestPayment->payment_method : null;
                    
                    // Handle deleted users in notifications
                    $customerName = $order->customer_name;
                    if ($order->user && $order->user->trashed()) {
                        $customerName = $order->user->name . ' (Account Deleted)';
                    }
                    
                    return [
                        'id' => $order->id,
                        'order_number' => $order->order_number,
                        'status' => $order->order_status,
                        'payment_status' => $order->payment_status,
                        'payment_method' => $paymentMethod,
                        'customer_name' => $customerName,
                        'items_count' => $order->items->count(),
                        'total' => $order->total,
                        'admin_confirmed_at' => $order->admin_confirmed_at,
                        'updated_at' => $order->updated_at,
                        'needs_payment_confirmation' => ($paymentMethod === 'cash' && $order->order_status === 'completed' && $order->payment_status !== 'paid')
                    ];
                });
            
            // Get counts for each status tab
            $counts = [
                'pending' => Order::where('order_status', 'pending')->whereNull('admin_confirmed_at')->count(),
                'preparing' => Order::where('order_status', 'preparing')->count(),
                'ready' => Order::where('order_status', 'ready')->count(),
                'completed' => Order::where('order_status', 'completed')->count(),
                'cancelled' => Order::where('order_status', 'cancelled')->count(),
            ];
            
            return response()->json([
                'success' => true,
                'updated_orders' => $updatedOrders,
                'counts' => $counts,
                'timestamp' => now()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Admin check updates error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'updated_orders' => [],
                'counts' => []
            ]);
        }
    }
}