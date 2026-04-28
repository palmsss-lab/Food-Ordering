
<div wire:poll.20s data-active-tab="{{ $this->tab }}">

    {{-- Live indicator --}}
    <div class="flex items-center justify-end gap-2 mb-3 text-xs text-gray-400">
        <span class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
        </span>
        Live &bull; updated {{ now()->timezone('Asia/Manila')->format('h:i:s A') }}
        <span wire:loading class="text-blue-400 ml-1">refreshing...</span>
    </div>

    {{-- Status Tabs --}}
    <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl p-4 border border-white/20 relative overflow-hidden mb-6">
        <div class="absolute top-0 left-0 w-32 h-32 bg-gradient-to-br from-[#ea5a47] to-[#c53030] opacity-5 rounded-br-3xl"></div>

        @php
            $tabs = [
                'pending'   => ['label' => 'Awaiting Confirmation', 'color' => 'yellow', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                'preparing' => ['label' => 'Preparing',            'color' => 'purple', 'icon' => 'M12 6v6m0 0v6m0-6h6m-6 0H6'],
                'ready'     => ['label' => 'Ready for Pickup',     'color' => 'green',  'icon' => 'M5 13l4 4L19 7'],
                'completed' => ['label' => 'Completed',            'color' => 'gray',   'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                'cancelled' => ['label' => 'Cancelled',            'color' => 'red',    'icon' => 'M6 18L18 6M6 6l12 12'],
            ];
            $activeClasses = [
                'pending'   => 'bg-yellow-100 text-yellow-700 border-2 border-yellow-300',
                'preparing' => 'bg-purple-100 text-purple-700 border-2 border-purple-300',
                'ready'     => 'bg-green-100 text-green-700 border-2 border-green-300',
                'completed' => 'bg-gray-100 text-gray-700 border-2 border-gray-300',
                'cancelled' => 'bg-red-100 text-red-700 border-2 border-red-300',
            ];
        @endphp

        <div class="relative z-10 flex flex-wrap gap-2">
            @foreach($tabs as $key => $tab)
                <button wire:click="setTab('{{ $key }}')"
                        class="flex items-center gap-2 px-4 py-2 rounded-xl transition-all duration-200 whitespace-nowrap
                               {{ $this->tab === $key ? $activeClasses[$key] : 'hover:bg-gray-100 hover:scale-105' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}" />
                    </svg>
                    <span class="font-medium">{{ $tab['label'] }}</span>
                    <span class="ml-2 px-2 py-0.5 text-xs bg-white rounded-full shadow-sm">
                        {{ $counts[$key] ?? 0 }}
                    </span>
                    @if($key === 'pending' && ($counts['pending'] ?? 0) > 0)
                        <span class="relative flex h-2 w-2 ml-1">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-yellow-500"></span>
                        </span>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    {{-- Skeleton loading — visible only while setTab is processing --}}
    <div wire:loading.block wire:target="setTab" class="w-full bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
        {{-- Skeleton header --}}
        <div class="bg-gray-50/80 border-b-2 border-gray-200 px-6 py-4">
            <div class="flex gap-6 items-center">
                <div class="h-4 w-4 bg-gray-200 rounded"></div>
                <div class="h-3 bg-gray-200 rounded-full w-20"></div>
                <div class="h-3 bg-gray-200 rounded-full w-24"></div>
                <div class="h-3 bg-gray-200 rounded-full w-16"></div>
                <div class="h-3 bg-gray-200 rounded-full w-14"></div>
                <div class="h-3 bg-gray-200 rounded-full w-28"></div>
                <div class="h-3 bg-gray-200 rounded-full w-24"></div>
                <div class="h-3 bg-gray-200 rounded-full w-24"></div>
                <div class="h-3 bg-gray-200 rounded-full flex-1"></div>
            </div>
        </div>
        {{-- Skeleton rows --}}
        <div class="divide-y divide-gray-100 animate-pulse">
            @for($s = 0; $s < 6; $s++)
            <div class="px-6 py-4">
                <div class="flex gap-6 items-center">
                    <div class="h-4 w-4 bg-gray-200 rounded"></div>
                    <div class="h-4 bg-gray-200 rounded-full w-24"></div>
                    <div class="flex flex-col gap-1.5 w-28">
                        <div class="h-3 bg-gray-200 rounded-full w-full"></div>
                        <div class="h-2 bg-gray-200 rounded-full w-2/3"></div>
                    </div>
                    <div class="flex flex-col gap-1.5 w-24">
                        <div class="h-3 bg-gray-200 rounded-full w-full"></div>
                        <div class="h-2 bg-gray-200 rounded-full w-3/4"></div>
                    </div>
                    <div class="h-4 bg-gray-200 rounded-full w-16"></div>
                    <div class="h-6 bg-gray-200 rounded-full w-28"></div>
                    <div class="h-6 bg-gray-200 rounded-full w-24"></div>
                    <div class="h-6 bg-gray-200 rounded-full w-24"></div>
                    <div class="flex gap-2 flex-1 justify-end">
                        <div class="h-8 w-8 bg-gray-200 rounded-lg"></div>
                        <div class="h-8 w-8 bg-gray-200 rounded-lg"></div>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>

    {{-- Orders Table --}}
    <div wire:loading.remove wire:target="setTab" class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-[#ea5a47] to-[#c53030] opacity-5 rounded-bl-3xl"></div>

        <div class="relative z-10 overflow-x-auto">
            <table class="w-full min-w-[900px] text-sm text-left text-gray-700">
                <thead class="text-xs uppercase bg-gray-50/80 border-b-2 border-gray-200 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-4 bg-gray-50/95 w-10">
                            <input type="checkbox" id="bulk-select-all" onchange="toggleBulkSelectAll(this)"
                                   class="w-4 h-4 accent-[#ea5a47] cursor-pointer rounded"
                                   aria-label="Select all orders">
                        </th>
                        <th class="px-6 py-4 bg-gray-50/95">Order #</th>
                        <th class="px-6 py-4 bg-gray-50/95">Customer</th>
                        <th class="px-6 py-4 bg-gray-50/95">Items</th>
                        <th class="px-6 py-4 bg-gray-50/95">Total</th>
                        <th class="px-6 py-4 bg-gray-50/95">Payment Method</th>
                        <th class="px-6 py-4 bg-gray-50/95">Payment Status</th>
                        <th class="px-6 py-4 bg-gray-50/95">Order Status</th>
                        <th class="px-6 py-4 bg-gray-50/95">Admin Confirmation</th>
                        <th class="px-6 py-4 bg-gray-50/95">Ordered</th>
                        @if($this->tab === 'preparing')
                        <th class="px-6 py-4 bg-gray-50/95">Time in Kitchen</th>
                        @endif
                        <th class="px-6 py-4 bg-gray-50/95">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($orders as $order)
                    @php
                        $paymentMethod     = $order->payment_method ?? 'N/A';
                        $isCashPayment     = ($paymentMethod === 'cash');
                        $needsConfirmation = ($order->order_status === 'pending' && !$order->admin_confirmed_at);
                        $canMarkAsPaid     = ($isCashPayment && $order->payment_status === 'cash on pickup' && $order->order_status === 'completed');
                    @endphp

                    <tr class="hover:bg-gray-50/50 transition-colors duration-200 bulk-order-row" wire:key="order-{{ $order->id }}" data-order-id="{{ $order->id }}" data-order-number="{{ $order->order_number }}" data-order-status="{{ $order->order_status }}"
                        @if($needsConfirmation) data-confirm-url="{{ route('admin.orders.confirm', $order) }}" @endif>
                        <td class="px-4 py-4">
                            <input type="checkbox" class="bulk-order-checkbox w-4 h-4 accent-[#ea5a47] cursor-pointer rounded"
                                   data-order-id="{{ $order->id }}"
                                   onchange="updateBulkBar()"
                                   aria-label="Select order {{ $order->order_number }}">
                        </td>
                        <td class="px-6 py-4 font-mono text-sm">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-[#ea5a47] hover:underline font-medium">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium">{{ $order->customer_name }}</div>
                            @if($order->customer_phone)
                                <div class="text-xs text-gray-500">{{ $order->customer_phone }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">{{ $order->items->count() }} item(s)</div>
                            <div class="text-xs text-gray-500 truncate max-w-[200px]">
                                {{ $order->items->pluck('item_name')->implode(', ') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-900">
                            ₱{{ number_format($order->total, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            @if($paymentMethod === 'cash')
                                <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-medium whitespace-nowrap">💵 Cash on Pickup</span>
                            @elseif($paymentMethod === 'gcash')
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium whitespace-nowrap">📱 GCash</span>
                            @elseif($paymentMethod === 'card')
                                <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium whitespace-nowrap">💳 Card</span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium whitespace-nowrap">{{ $paymentMethod }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($order->payment_status === 'paid')
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium whitespace-nowrap">✅ Paid</span>
                            @elseif($order->payment_status === 'cash on pickup')
                                <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-medium whitespace-nowrap">💵 To Pay on Pickup</span>
                            @elseif($order->payment_status === 'pending')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium whitespace-nowrap">⏳ Pending Payment</span>
                            @elseif($order->payment_status === 'failed')
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium whitespace-nowrap">❌ Failed</span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium whitespace-nowrap">{{ ucfirst($order->payment_status) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full whitespace-nowrap
                                @if($order->order_status === 'pending')    bg-yellow-100 text-yellow-700
                                @elseif($order->order_status === 'preparing') bg-purple-100 text-purple-700
                                @elseif($order->order_status === 'ready')  bg-green-100 text-green-700
                                @elseif($order->order_status === 'completed') bg-gray-100 text-gray-700
                                @elseif($order->order_status === 'cancelled') bg-red-100 text-red-700
                                @endif">
                                {{ ($order->order_status === 'pending' && !$order->admin_confirmed_at) ? 'Awaiting Confirmation' : ucfirst($order->order_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($order->admin_confirmed_at)
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">✓ Confirmed</span>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ \Carbon\Carbon::parse($order->admin_confirmed_at)->format('M d, h:i A') }}
                                </div>
                            @else
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">⏳ Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500 whitespace-nowrap">
                            {{ $order->created_at->diffForHumans() }}
                        </td>
                        @if($this->tab === 'preparing')
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->admin_confirmed_at)
                                <span class="kitchen-timer font-mono text-sm font-bold"
                                      data-confirmed-at="{{ $order->admin_confirmed_at->toIso8601String() }}">
                                    --:--
                                </span>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        @endif
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 hover:scale-110 transition-all duration-300"
                                   title="View Details">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>

                                @if($needsConfirmation)
                                    <button onclick="openConfirmOrderModal('{{ $order->id }}', '{{ $order->order_number }}', '{{ addslashes($order->customer_name) }}', '{{ $order->total }}', '{{ $order->payment_method }}', '{{ $order->payment_status }}', '{{ route('admin.orders.confirm', $order) }}')"
                                            class="p-2 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 hover:scale-110 transition-all duration-300 relative group"
                                            title="Confirm Order">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                        </span>
                                    </button>
                                @endif

                                @if($order->admin_confirmed_at && $order->order_status === 'pending')
                                    <button onclick="openStatusModal('{{ $order->id }}', '{{ $order->order_number }}', 'preparing', '{{ route('admin.orders.update-status', $order) }}', 'Start Preparing', 'This order will be moved to preparation queue.', '{{ addslashes($order->customer_name) }}', '{{ $order->total }}', '{{ $order->payment_method }}')"
                                            class="p-2 bg-purple-100 text-purple-600 rounded-lg hover:bg-purple-200 hover:scale-110 transition-all duration-300"
                                            title="Start Preparing">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </button>
                                @endif

                                @if($order->order_status === 'preparing')
                                    <button onclick="openStatusModal('{{ $order->id }}', '{{ $order->order_number }}', 'ready', '{{ route('admin.orders.update-status', $order) }}', 'Mark as Ready', 'This order will be marked as ready for customer pickup.', '{{ addslashes($order->customer_name) }}', '{{ $order->total }}', '{{ $order->payment_method }}')"
                                            class="p-2 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 hover:scale-110 transition-all duration-300"
                                            title="Mark as Ready"
                                            data-ready-url="{{ route('admin.orders.update-status', $order) }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                @endif

                                @if($canMarkAsPaid)
                                    <button onclick="openPaidModal('{{ $order->id }}', '{{ $order->order_number }}', '{{ $order->total }}', '{{ route('admin.orders.mark-as-paid', $order) }}')"
                                            class="p-2 bg-green-600 text-white rounded-lg hover:bg-green-700 hover:scale-110 transition-all duration-300"
                                            title="Mark as Paid">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                @endif

                                @if($needsConfirmation)
                                    <button onclick="openRejectOrderModal('{{ $order->id }}', '{{ $order->order_number }}', '{{ route('admin.orders.reject', $order) }}')"
                                            class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 hover:scale-110 transition-all duration-300"
                                            title="Reject Order">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $this->tab === 'preparing' ? 11 : 10 }}" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <h5 class="text-xl font-bold text-gray-600 mb-2">No Orders Found</h5>
                                <p class="text-gray-500">There are no orders in this category.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-white/95">
                {{ $orders->links() }}
            </div>
        @endif
    </div>{{-- end wire:loading.remove --}}

</div>

<script>
(function () {
    let _kitchenInterval = null;

    function updateKitchenTimers() {
        const timers = document.querySelectorAll('.kitchen-timer[data-confirmed-at]');
        if (!timers.length) return;

        const now = Date.now();
        timers.forEach(function (el) {
            const confirmedAt = new Date(el.dataset.confirmedAt).getTime();
            const elapsedMs   = now - confirmedAt;
            const totalSecs   = Math.floor(elapsedMs / 1000);
            const mins        = Math.floor(totalSecs / 60);
            const secs        = totalSecs % 60;

            el.textContent = String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');

            // Remove old pulse class before re-applying color logic
            el.classList.remove('animate-pulse');

            if (mins < 15) {
                el.style.color = '#16a34a'; // green
            } else if (mins < 30) {
                el.style.color = '#ca8a04'; // yellow
            } else {
                el.style.color = '#dc2626'; // red
                el.classList.add('animate-pulse');
            }
        });
    }

    function startKitchenTimers() {
        if (_kitchenInterval) clearInterval(_kitchenInterval);
        updateKitchenTimers();
        _kitchenInterval = setInterval(updateKitchenTimers, 1000);
    }

    // Start on initial load
    document.addEventListener('DOMContentLoaded', startKitchenTimers);

    // Restart after Livewire re-renders the DOM (Livewire v3)
    document.addEventListener('livewire:updated', startKitchenTimers);
})();
</script>
