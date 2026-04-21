<div wire:poll.20s>

    {{-- Live indicator --}}
    <div class="flex items-center justify-end gap-2 mb-4 text-xs text-gray-400">
        <span class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
        </span>
        Live updates on
        <span wire:loading class="text-blue-400">refreshing...</span>
    </div>

    {{-- Tab Navigation --}}
    <div class="mb-6 border-b border-gray-200 overflow-x-auto">
        <ul class="flex -mb-px text-sm font-medium text-center min-w-max" role="tablist">

            <li class="mr-1" role="presentation">
                <button wire:click="setTab('pending')"
                        class="inline-flex items-center gap-1.5 px-3 py-3 sm:px-4 sm:py-4 rounded-t-lg border-b-2 whitespace-nowrap {{ $activeTab === 'pending' ? 'border-[#ea5a47] text-[#ea5a47]' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}"
                        type="button" role="tab" aria-selected="{{ $activeTab === 'pending' ? 'true' : 'false' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="hidden sm:inline">Awaiting</span>
                    <span class="sm:hidden">Pending</span>
                    @if($counts['pending'] > 0)
                        <span class="bg-yellow-100 text-yellow-700 px-1.5 py-0.5 rounded-full text-xs font-bold">{{ $counts['pending'] }}</span>
                    @endif
                </button>
            </li>

            <li class="mr-1" role="presentation">
                <button wire:click="setTab('preparing')"
                        class="inline-flex items-center gap-1.5 px-3 py-3 sm:px-4 sm:py-4 rounded-t-lg border-b-2 whitespace-nowrap {{ $activeTab === 'preparing' ? 'border-[#ea5a47] text-[#ea5a47]' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}"
                        type="button" role="tab" aria-selected="{{ $activeTab === 'preparing' ? 'true' : 'false' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Preparing</span>
                    @if($counts['preparing'] > 0)
                        <span class="bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded-full text-xs font-bold">{{ $counts['preparing'] }}</span>
                    @endif
                </button>
            </li>

            <li class="mr-1" role="presentation">
                <button wire:click="setTab('ready')"
                        class="inline-flex items-center gap-1.5 px-3 py-3 sm:px-4 sm:py-4 rounded-t-lg border-b-2 whitespace-nowrap {{ $activeTab === 'ready' ? 'border-[#ea5a47] text-[#ea5a47]' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}"
                        type="button" role="tab" aria-selected="{{ $activeTab === 'ready' ? 'true' : 'false' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="hidden sm:inline">Ready for Pickup</span>
                    <span class="sm:hidden">Ready</span>
                    @if($counts['ready'] > 0)
                        <span class="bg-green-100 text-green-700 px-1.5 py-0.5 rounded-full text-xs font-bold">{{ $counts['ready'] }}</span>
                    @endif
                </button>
            </li>

            <li class="mr-1" role="presentation">
                <button wire:click="setTab('completed')"
                        class="inline-flex items-center gap-1.5 px-3 py-3 sm:px-4 sm:py-4 rounded-t-lg border-b-2 whitespace-nowrap {{ $activeTab === 'completed' ? 'border-[#ea5a47] text-[#ea5a47]' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}"
                        type="button" role="tab" aria-selected="{{ $activeTab === 'completed' ? 'true' : 'false' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Completed</span>
                    @if($counts['completed'] > 0)
                        <span class="bg-gray-100 text-gray-700 px-1.5 py-0.5 rounded-full text-xs font-bold">{{ $counts['completed'] }}</span>
                    @endif
                </button>
            </li>

            <li class="mr-1" role="presentation">
                <button wire:click="setTab('cancelled')"
                        class="inline-flex items-center gap-1.5 px-3 py-3 sm:px-4 sm:py-4 rounded-t-lg border-b-2 whitespace-nowrap {{ $activeTab === 'cancelled' ? 'border-[#ea5a47] text-[#ea5a47]' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}"
                        type="button" role="tab" aria-selected="{{ $activeTab === 'cancelled' ? 'true' : 'false' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>Cancelled</span>
                    @if($counts['cancelled'] > 0)
                        <span class="bg-red-100 text-red-700 px-1.5 py-0.5 rounded-full text-xs font-bold">{{ $counts['cancelled'] }}</span>
                    @endif
                </button>
            </li>

            <li role="presentation">
                <button wire:click="setTab('refunded')"
                        class="inline-flex items-center gap-1.5 px-3 py-3 sm:px-4 sm:py-4 rounded-t-lg border-b-2 whitespace-nowrap {{ $activeTab === 'refunded' ? 'border-[#ea5a47] text-[#ea5a47]' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}"
                        type="button" role="tab" aria-selected="{{ $activeTab === 'refunded' ? 'true' : 'false' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                    </svg>
                    <span>Refunded</span>
                    @if($counts['refunded'] > 0)
                        <span class="bg-purple-100 text-purple-700 px-1.5 py-0.5 rounded-full text-xs font-bold">{{ $counts['refunded'] }}</span>
                    @endif
                </button>
            </li>

        </ul>
    </div>

    {{-- Skeleton loader — visible only while setTab is processing --}}
    <div wire:loading.block wire:target="setTab" class="space-y-4 w-full">
        @for($s = 0; $s < 3; $s++)
        <div class="bg-white border-2 border-gray-100 rounded-2xl p-6 animate-pulse">
            {{-- Header row --}}
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3 flex-wrap">
                    <div class="h-4 bg-gray-200 rounded-full w-16"></div>
                    <div class="h-4 bg-gray-200 rounded-full w-28"></div>
                    <div class="h-6 bg-gray-200 rounded-full w-24"></div>
                    <div class="h-6 bg-gray-200 rounded-full w-32"></div>
                </div>
                <div class="h-9 bg-gray-200 rounded-xl w-32 hidden lg:block"></div>
            </div>
            {{-- Body lines --}}
            <div class="space-y-2 mb-4">
                <div class="h-3 bg-gray-200 rounded-full w-3/5"></div>
                <div class="h-3 bg-gray-200 rounded-full w-2/5"></div>
            </div>
            {{-- Items section --}}
            <div class="pt-4 border-t border-gray-100">
                <div class="h-3 bg-gray-200 rounded-full w-10 mb-2"></div>
                <div class="flex flex-wrap gap-2">
                    <div class="h-6 bg-gray-200 rounded-full w-20"></div>
                    <div class="h-6 bg-gray-200 rounded-full w-24"></div>
                    <div class="h-6 bg-gray-200 rounded-full w-16"></div>
                </div>
            </div>
            {{-- Mobile button --}}
            <div class="mt-3 h-9 bg-gray-200 rounded-xl w-full lg:hidden"></div>
        </div>
        @endfor
    </div>

    {{-- Tab Content --}}
    @php
        $filteredOrders = $orders->filter(fn($o) => $o->display_status === $activeTab);
    @endphp

    <div wire:loading.remove wire:target="setTab">
    @if($filteredOrders->isEmpty())
        <div class="bg-gray-50 rounded-2xl p-12 text-center">
            <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-xl font-bold text-gray-600 mb-2">No {{ ucfirst($activeTab) }} Orders</h3>
            <p class="text-gray-500">You don't have any {{ $activeTab }} orders at the moment.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($filteredOrders as $order)
                @php
                    $latestPayment = $order->latestPayment;
                    $paymentMethod = $latestPayment ? $latestPayment->payment_method : null;
                    $isAwaitingConfirmation = !$order->admin_confirmed_at;
                @endphp

                <div wire:key="client-order-{{ $order->id }}"
                     class="bg-white border-2 rounded-2xl p-6 hover:shadow-lg transition-all
                         @if($activeTab === 'pending')    border-yellow-200
                         @elseif($activeTab === 'preparing') border-blue-200
                         @elseif($activeTab === 'ready')  border-green-200
                         @elseif($activeTab === 'completed') border-gray-200
                         @elseif($activeTab === 'cancelled') border-red-200
                         @elseif($activeTab === 'refunded')  border-purple-200
                         @endif">

                    <div class="flex flex-col lg:flex-row justify-between items-start gap-6">

                        {{-- Left: Order Info --}}
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2 flex-wrap">
                                <span class="text-sm font-semibold text-gray-500">Order #:</span>
                                <span class="font-bold text-[#ea5a47]">{{ $order->order_number }}</span>

                                @if($paymentMethod)
                                    <span class="{{ $order->payment_badge_class }} px-3 py-1 rounded-full text-xs font-bold">
                                        @if($paymentMethod === 'cash') 💵
                                        @else 💳
                                        @endif
                                        {{ $order->payment_display_name }}
                                    </span>
                                @endif

                                @if($activeTab === 'pending')
                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold">
                                        ⏳ {{ $isAwaitingConfirmation ? 'Awaiting Confirmation' : 'Pending' }}
                                    </span>
                                @elseif($activeTab === 'preparing')
                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">🔄 Preparing</span>
                                @elseif($activeTab === 'ready')
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">✅ Ready for Pickup</span>
                                @elseif($activeTab === 'completed')
                                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-bold">✓ Completed</span>
                                    @if($paymentMethod === 'cash')
                                        @if($order->payment_status === 'paid')
                                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-bold ml-1">💵 Paid</span>
                                        @else
                                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs font-bold ml-1">⏳ Payment Pending</span>
                                        @endif
                                    @else
                                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-bold ml-1">✓ Paid</span>
                                    @endif
                                @elseif($activeTab === 'cancelled')
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">✕ Cancelled</span>
                                @elseif($activeTab === 'refunded')
                                    <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-bold">↩ {{ $order->payment_status === 'partial_refund' ? 'Partially Refunded' : 'Refunded' }}</span>
                                @endif
                            </div>

                            <p class="text-gray-600 mb-2">
                                {{ $order->items->count() }} item(s) &bull; Total: ₱{{ number_format($order->total, 2) }}
                            </p>

                            <p class="text-sm text-gray-400">
                                Ordered {{ \Carbon\Carbon::parse($order->ordered_at ?? $order->created_at)->timezone('Asia/Manila')->format('M d, Y \a\t h:i A') }}
                            </p>

                            @if($order->admin_confirmed_at)
                                <p class="text-xs text-green-600 mt-1">
                                    ✓ Confirmed on {{ \Carbon\Carbon::parse($order->admin_confirmed_at)->timezone('Asia/Manila')->format('M d, Y \a\t h:i A') }}
                                </p>
                            @endif

                            @if($paymentMethod === 'cash' && $order->payment_status === 'paid' && $activeTab === 'completed')
                                <div class="mt-3 p-3 bg-green-50 border-l-4 border-green-500 rounded-lg">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-semibold text-green-800">Payment Confirmed! 💵</p>
                                            <p class="text-sm text-green-700 mt-1">Admin has confirmed your payment for this order.</p>
                                            <p class="text-xs text-green-600 mt-2">Thank you for dining with us!</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($activeTab === 'cancelled' && !empty($order->rejection_reason))
                                <div class="mt-3 p-3 bg-red-50 border-l-4 border-red-500 rounded-lg">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-semibold text-red-800">Order Rejection Reason:</p>
                                            <p class="text-sm text-red-700 mt-1">{{ $order->rejection_reason }}</p>
                                            <p class="text-xs text-red-600 mt-2">If you have questions, please contact our support team.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($activeTab === 'refunded')
                                <div class="mt-3 p-3 bg-purple-50 border-l-4 border-purple-400 rounded-lg">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-purple-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-semibold text-purple-800">
                                                {{ $order->payment_status === 'partial_refund' ? 'Partial Refund Issued' : 'Refund Issued' }}
                                                @if($order->refund_amount)
                                                    — <span class="text-purple-700">₱{{ number_format($order->refund_amount, 2) }}</span>
                                                @endif
                                            </p>
                                            @if($order->refund_reason)
                                                <p class="text-sm text-purple-700 mt-1">{{ $order->refund_reason }}</p>
                                            @endif
                                            @if($order->refunded_at)
                                                <p class="text-xs text-purple-500 mt-1">Processed on {{ \Carbon\Carbon::parse($order->refunded_at)->timezone('Asia/Manila')->format('M d, Y \a\t h:i A') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Right: Action Buttons --}}
                        <div class="flex flex-col gap-2 lg:min-w-[140px]">
                            <a href="{{ route('client.orders.show', $order->order_number) }}"
                               class="px-4 py-2 bg-gray-600 text-white text-sm font-semibold rounded-xl hover:bg-gray-700 transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View Details
                            </a>

                            @if($activeTab === 'ready')
                                <button onclick="showLocation()"
                                        class="px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-xl hover:bg-green-600 transition-all flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Store Location
                                </button>

                                <button type="button"
                                        onclick="openPickupModal('{{ $order->order_number }}', '{{ route('client.orders.picked-up', $order) }}')"
                                        class="w-full px-4 py-2 bg-purple-600 text-white text-sm font-semibold rounded-xl hover:bg-purple-700 transition-all flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Picked Up
                                </button>
                            @endif

                            @if($activeTab === 'completed' && $paymentMethod === 'cash' && $order->payment_status !== 'paid')
                                <div class="mt-2 p-2 bg-yellow-50 rounded-lg text-xs text-yellow-700 text-center">
                                    ⏳ Payment pending<br>admin confirmation
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Item preview --}}
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-sm font-medium text-gray-500 mb-2">Items:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($order->items->take(3) as $item)
                                <span class="text-sm bg-gray-100 px-3 py-1 rounded-full">
                                    {{ $item->quantity }}x {{ $item->item_name }}
                                </span>
                            @endforeach
                            @if($order->items->count() > 3)
                                <span class="text-sm text-gray-500">+{{ $order->items->count() - 3 }} more</span>
                            @endif
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    @endif
    </div>{{-- end wire:loading.remove --}}

</div>
