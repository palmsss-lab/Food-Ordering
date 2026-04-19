<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersList extends Component
{
    use WithPagination;

    public string $tab = 'pending';

    /**
     * -1 means "first render — don't dispatch anything yet".
     * After the first render these hold the last known counts so we can
     * fire a notification whenever a count increases.
     */
    public int $previousPendingCount   = -1;
    public int $previousCompletedCount = -1;

    public function mount(): void
    {
        $this->tab = request()->get('tab', 'pending');
    }

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function render()
    {
        $query = Order::with(['items', 'latestPayment', 'user' => fn($q) => $q->withTrashed()]);

        match ($this->tab) {
            'pending'   => $query->where('order_status', 'pending')->whereNull('admin_confirmed_at'),
            'confirmed' => $query->whereNotNull('admin_confirmed_at')->whereIn('order_status', ['pending', 'confirmed', 'preparing']),
            'preparing' => $query->where('order_status', 'preparing'),
            'ready'     => $query->where('order_status', 'ready'),
            'completed' => $query->where('order_status', 'completed'),
            'cancelled' => $query->where('order_status', 'cancelled'),
            default     => null,
        };

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);
        $orders->each(fn($o) => $o->setAppends(['payment_method']));

        $counts = $this->getCounts();

        // Dispatch notifications for meaningful count changes (skip on first render)
        if ($this->previousPendingCount >= 0) {
            $pendingDiff   = $counts['pending']   - $this->previousPendingCount;
            $completedDiff = $counts['completed'] - $this->previousCompletedCount;

            if ($pendingDiff > 0) {
                $label = $pendingDiff === 1 ? 'new order' : "{$pendingDiff} new orders";
                $this->dispatch('admin-order-toast',
                    message: "🆕 You have {$label} waiting for confirmation!",
                    type: 'new-order',
                    pendingCount: $counts['pending']
                );
            }

            if ($completedDiff > 0) {
                $label = $completedDiff === 1 ? '1 order has' : "{$completedDiff} orders have";
                $this->dispatch('admin-order-toast',
                    message: "✅ {$label} been picked up by the customer.",
                    type: 'picked-up',
                    completedCount: $counts['completed']
                );
            }
        }

        $this->previousPendingCount   = $counts['pending'];
        $this->previousCompletedCount = $counts['completed'];

        return view('livewire.admin.orders-list', compact('orders', 'counts'));
    }

    /**
     * One query instead of 6 separate COUNT queries.
     * Cached for 4 seconds — counts update every 5s poll anyway.
     */
    private function getCounts(): array
    {
        return Cache::remember('admin_order_counts', 4, function () {
            $row = Order::selectRaw("
                SUM(CASE WHEN order_status = 'pending'   AND admin_confirmed_at IS NULL                                     THEN 1 ELSE 0 END) AS pending,
                SUM(CASE WHEN admin_confirmed_at IS NOT NULL AND order_status IN ('pending','confirmed','preparing')         THEN 1 ELSE 0 END) AS confirmed,
                SUM(CASE WHEN order_status = 'preparing'  THEN 1 ELSE 0 END) AS preparing,
                SUM(CASE WHEN order_status = 'ready'      THEN 1 ELSE 0 END) AS ready,
                SUM(CASE WHEN order_status = 'completed'  THEN 1 ELSE 0 END) AS completed,
                SUM(CASE WHEN order_status = 'cancelled'  THEN 1 ELSE 0 END) AS cancelled
            ")->first();

            return [
                'pending'   => (int) $row->pending,
                'confirmed' => (int) $row->confirmed,
                'preparing' => (int) $row->preparing,
                'ready'     => (int) $row->ready,
                'completed' => (int) $row->completed,
                'cancelled' => (int) $row->cancelled,
            ];
        });
    }
}
