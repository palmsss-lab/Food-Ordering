<?php

namespace App\Livewire\Client;

use App\Models\Order;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class OrdersList extends Component
{
    public string $activeTab = 'pending';

    /**
     * Tracks "display_status|payment_status" for each order between polls.
     * Persists in Livewire's state snapshot so we can diff on the next cycle.
     */
    public array $previousStatuses = [];

    public function mount(): void
    {
        $this->activeTab = request()->get('tab', 'pending');
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        $orders = Order::with(['items', 'latestPayment', 'transaction'])
            ->where('user_id', Auth::id())
            ->orderBy('ordered_at', 'desc')
            ->get();

        $orders->each(fn($o) => $o->setAppends([
            'display_status',
            'payment_method',
            'payment_badge_class',
            'payment_display_name',
        ]));

        // Detect status changes since the last poll and notify the client
        foreach ($orders as $order) {
            $currentKey  = $order->display_status . '|' . $order->payment_status;
            $previousKey = $this->previousStatuses[$order->id] ?? null;

            if ($previousKey !== null && $previousKey !== $currentKey) {
                [$prevDisplay]                  = explode('|', $previousKey);
                [$currDisplay, $currPayment]    = explode('|', $currentKey);
                $this->notifyStatusChange($order->order_number, $order->id, $prevDisplay, $currDisplay, $currPayment);
            }

            $this->previousStatuses[$order->id] = $currentKey;
        }

        $counts = [
            'pending'   => $orders->filter(fn($o) => $o->display_status === 'pending')->count(),
            'preparing' => $orders->filter(fn($o) => $o->display_status === 'preparing')->count(),
            'ready'     => $orders->filter(fn($o) => $o->display_status === 'ready')->count(),
            'completed' => $orders->filter(fn($o) => $o->display_status === 'completed')->count(),
            'cancelled' => $orders->filter(fn($o) => $o->display_status === 'cancelled')->count(),
            'refunded'  => $orders->filter(fn($o) => $o->display_status === 'refunded')->count(),
        ];

        return view('livewire.client.orders-list', compact('orders', 'counts'));
    }

    private function notifyStatusChange(
        string $orderNumber,
        int    $orderId,
        string $prevDisplay,
        string $currDisplay,
        string $currPayment
    ): void {
        $statusKey = $currDisplay . '|' . $currPayment;

        // Order confirmed by admin → now being prepared
        if ($prevDisplay === 'pending' && $currDisplay === 'preparing') {
            $this->dispatch('order-status-toast',
                message: "Your order #{$orderNumber} has been confirmed and is now being prepared! 🍳",
                type: 'preparing',
                orderId: $orderId,
                statusKey: $statusKey
            );
            return;
        }

        // Order is ready for pickup
        if ($currDisplay === 'ready') {
            $this->dispatch('order-status-toast',
                message: "Order #{$orderNumber} is ready for pickup! 🎉 Please come to our store.",
                type: 'ready',
                orderId: $orderId,
                statusKey: $statusKey
            );
            return;
        }

        // Order was cancelled
        if ($currDisplay === 'cancelled') {
            $this->dispatch('order-status-toast',
                message: "Your order #{$orderNumber} has been cancelled. Please contact us if you have questions.",
                type: 'cancelled',
                orderId: $orderId,
                statusKey: $statusKey
            );
            return;
        }

        // Cash payment confirmed by admin after pickup.
        // prevDisplay must already be 'completed' — meaning the client already picked up
        // and admin is now marking the cash as paid. For GCash/Card the transition is
        // ready → completed (prevDisplay = 'ready'), so this correctly won't fire there.
        if ($currDisplay === 'completed' && $currPayment === 'paid' && $prevDisplay === 'completed') {
            $this->dispatch('order-status-toast',
                message: "Cash payment for order #{$orderNumber} has been confirmed! 💵 Thank you!",
                type: 'payment',
                orderId: $orderId,
                statusKey: $statusKey
            );
        }

        // Refund issued
        if (in_array($currPayment, ['refunded', 'partial_refund'])) {
            $label = $currPayment === 'partial_refund' ? 'A partial refund' : 'A refund';
            $this->dispatch('order-status-toast',
                message: "{$label} has been issued for order #{$orderNumber}. Check your transactions for details.",
                type: 'cancelled',
                orderId: $orderId,
                statusKey: $statusKey
            );
        }
    }
}
