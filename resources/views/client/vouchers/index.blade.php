@extends('client.layouts.home')

@section('title', 'Vouchers')

@section('content')
<div class="mt-24 md:mt-28 min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] pb-16 px-4 sm:px-6">
    <div class="max-w-4xl mx-auto">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-black text-gray-800 flex items-center gap-3">
                <div class="bg-gradient-to-br from-[#ea5a47] to-[#c53030] p-2.5 rounded-xl">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
                Vouchers
            </h1>
            <p class="text-gray-500 mt-1 text-sm">Collect vouchers now, use them at checkout.</p>
        </div>

        <!-- One-time hint banner -->
        <div id="voucherHint"
             class="mb-6 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 flex items-start gap-3 text-sm text-blue-700"
             style="display: none;">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="flex-1">
                <strong>How it works:</strong> Click <em>Collect</em> on any voucher to save it to your account.
                At checkout, select a collected voucher to apply the discount automatically.
            </div>
            <button onclick="dismissVoucherHint()" class="text-blue-400 hover:text-blue-600 flex-shrink-0" aria-label="Dismiss">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Tabs -->
        <div class="flex gap-2 mb-6">
            <button id="tab-all" onclick="showTab('all')"
                    class="px-5 py-2 rounded-full text-sm font-semibold transition-all bg-[#ea5a47] text-white shadow">
                All Vouchers
            </button>
            <button id="tab-mine" onclick="showTab('mine')"
                    class="px-5 py-2 rounded-full text-sm font-semibold transition-all bg-white text-gray-600 border border-gray-200 hover:border-[#ea5a47]">
                My Collected
                @if($collectedCount > 0)
                    <span id="collected-badge" class="ml-1 bg-[#ea5a47] text-white text-xs px-1.5 py-0.5 rounded-full">{{ $collectedCount }}</span>
                @endif
            </button>
        </div>

        <!-- All Vouchers Tab -->
        <div id="pane-all">
            @forelse($vouchers as $voucher)
                <div class="voucher-card bg-white rounded-2xl shadow-md mb-4 overflow-hidden border border-gray-100 flex
                            {{ $voucher->used ? 'opacity-50' : '' }}"
                     data-id="{{ $voucher->id }}">

                    <!-- Left color strip -->
                    <div class="w-2 flex-shrink-0 bg-gradient-to-b from-[#ea5a47] to-[#c53030]"></div>

                    <!-- Scissor divider -->
                    <div class="flex items-center px-1">
                        <div class="h-full flex flex-col justify-between py-4 gap-1">
                            @for($i = 0; $i < 8; $i++)
                                <div class="w-1.5 h-1.5 rounded-full bg-gray-200"></div>
                            @endfor
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex-1">
                            <!-- Discount headline -->
                            <div class="flex items-center gap-2 mb-1">
                                @if($voucher->type === 'percentage')
                                    <span class="text-2xl font-black text-[#ea5a47]">{{ $voucher->value }}% OFF</span>
                                @else
                                    <span class="text-2xl font-black text-[#ea5a47]">₱{{ number_format($voucher->value, 0) }} OFF</span>
                                @endif
                                <span class="text-xs font-mono bg-[#ea5a47]/10 text-[#ea5a47] px-2 py-0.5 rounded font-bold">
                                    {{ $voucher->code }}
                                </span>
                            </div>

                            <!-- Description -->
                            @if($voucher->description)
                                <p class="text-sm text-gray-600 mb-1">{{ $voucher->description }}</p>
                            @endif

                            <!-- Conditions row -->
                            <div class="flex flex-wrap gap-3 text-xs text-gray-400">
                                @if($voucher->min_order_amount)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                                        Min. ₱{{ number_format($voucher->min_order_amount, 0) }}
                                    </span>
                                @endif
                                @if($voucher->expires_at)
                                    <span class="flex items-center gap-1 {{ $voucher->expires_at->diffInDays(now()) <= 3 ? 'text-orange-500 font-medium' : '' }}">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        Expires {{ $voucher->expires_at->format('M d, Y') }}
                                    </span>
                                @else
                                    <span class="flex items-center gap-1 text-green-500">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        No expiry
                                    </span>
                                @endif
                                @if($voucher->max_uses)
                                    @php $remaining = $voucher->max_uses - $voucher->actualUsedCount(); @endphp
                                    <span class="flex items-center gap-1 {{ $remaining <= 5 ? 'text-red-500 font-medium' : '' }}">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857"/></svg>
                                        {{ $remaining }} left
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Claim button area (right side) -->
                        <div class="flex-shrink-0 text-center sm:min-w-[90px]">
                            @if($voucher->used)
                                <span class="block text-xs text-gray-400 font-medium">Used</span>
                            @elseif($voucher->claimed)
                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-xs text-green-600 font-semibold flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Collected
                                    </span>
                                    <a href="{{ route('client.checkout') }}"
                                       class="text-xs text-[#ea5a47] hover:underline font-medium">Use now</a>
                                </div>
                            @else
                                <button type="button"
                                        onclick="claimVoucher({{ $voucher->id }}, this)"
                                        class="claim-btn px-4 py-2 bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white text-xs font-bold rounded-xl hover:shadow-lg transition-all active:scale-95">
                                    Collect
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <p class="font-medium text-lg">No vouchers available right now.</p>
                    <p class="text-sm mt-1">Check back later for deals!</p>
                </div>
            @endforelse
        </div>

        <!-- My Collected Tab -->
        <div id="pane-mine" class="hidden">
            <div id="collected-list">
                <div class="text-center py-10 text-gray-400 text-sm">Loading your vouchers...</div>
            </div>
        </div>
    </div>
</div>


<script>
function showTab(tab) {
    document.getElementById('pane-all').classList.toggle('hidden', tab !== 'all');
    document.getElementById('pane-mine').classList.toggle('hidden', tab !== 'mine');

    document.getElementById('tab-all').className  = tab === 'all'
        ? 'px-5 py-2 rounded-full text-sm font-semibold transition-all bg-[#ea5a47] text-white shadow'
        : 'px-5 py-2 rounded-full text-sm font-semibold transition-all bg-white text-gray-600 border border-gray-200 hover:border-[#ea5a47]';
    document.getElementById('tab-mine').className = tab === 'mine'
        ? 'px-5 py-2 rounded-full text-sm font-semibold transition-all bg-[#ea5a47] text-white shadow'
        : 'px-5 py-2 rounded-full text-sm font-semibold transition-all bg-white text-gray-600 border border-gray-200 hover:border-[#ea5a47]';

    const badge = document.getElementById('collected-badge');
    if (badge) {
        badge.className = tab === 'mine'
            ? 'ml-1 bg-white text-[#ea5a47] text-xs px-1.5 py-0.5 rounded-full font-bold'
            : 'ml-1 bg-[#ea5a47] text-white text-xs px-1.5 py-0.5 rounded-full';
    }

    if (tab === 'mine') loadMyVouchers();
}

function claimVoucher(id, btn) {
    btn.disabled = true;
    btn.textContent = '...';

    fetch(`/client/vouchers/${id}/claim`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, false);
            const wrap = btn.parentElement;
            wrap.innerHTML = `
                <span class="text-xs text-green-600 font-semibold flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Collected
                </span>
                <a href="{{ route('client.cart.index') }}" class="text-xs text-[#ea5a47] hover:underline font-medium">Use now</a>
            `;
        } else {
            showToast(data.message, true);
            btn.disabled = false;
            btn.textContent = 'Collect';
        }
    })
    .catch(() => {
        showToast('Something went wrong. Try again.', true);
        btn.disabled = false;
        btn.textContent = 'Collect';
    });
}

function loadMyVouchers() {
    const list = document.getElementById('collected-list');
    list.innerHTML = `<div class="text-center py-10 text-gray-400 text-sm">Loading your vouchers...</div>`;

    fetch('{{ route("client.vouchers.collected") }}')
    .then(r => r.json())
    .then(vouchers => {
        if (!vouchers.length) {
            list.innerHTML = `<div class="text-center py-16 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                <p class="font-medium">No collected vouchers yet.</p>
                <button onclick="showTab('all')" class="mt-3 text-sm text-[#ea5a47] hover:underline">Browse vouchers</button>
            </div>`;
            return;
        }

        list.innerHTML = vouchers.map(v => {
            const isUsed    = v.is_used;
            const isExpired = v.is_expired;
            const isActive  = !isUsed && !isExpired;

            const stripColor = isActive
                ? 'bg-gradient-to-b from-[#ea5a47] to-[#c53030]'
                : 'bg-gradient-to-b from-gray-300 to-gray-400';

            const discountColor = isActive ? 'text-[#ea5a47]' : 'text-gray-400';
            const codeColor     = isActive
                ? 'bg-[#ea5a47]/10 text-[#ea5a47]'
                : 'bg-gray-100 text-gray-400';

            const statusBadge = isUsed
                ? `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">
                       <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                       Used ${v.used_at ? 'on ' + v.used_at : ''}
                   </span>`
                : isExpired
                ? `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-400">
                       <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                       Expired ${v.expires_at ? 'on ' + v.expires_at : ''}
                   </span>`
                : '';

            const expiryLine = v.expires_at
                ? (isExpired
                    ? `<span class="text-red-400">Expired ${v.expires_at}</span>`
                    : `<span>Expires ${v.expires_at}</span>`)
                : `<span class="text-green-500">No expiry</span>`;

            const actionBtn = isActive
                ? `<a href="{{ route('client.cart.index') }}"
                      class="flex-shrink-0 px-4 py-2 bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white text-xs font-bold rounded-xl hover:shadow-lg transition-all">
                       Use Now
                   </a>`
                : `<span class="flex-shrink-0 px-4 py-2 bg-gray-100 text-gray-400 text-xs font-bold rounded-xl cursor-not-allowed">
                       ${isUsed ? 'Used' : 'Expired'}
                   </span>`;

            return `
            <div class="bg-white rounded-2xl shadow-md mb-4 overflow-hidden border border-gray-100 flex ${!isActive ? 'opacity-60' : ''}">
                <div class="w-2 flex-shrink-0 ${stripColor}"></div>
                <div class="flex-1 p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="text-2xl font-black ${discountColor}">
                                ${v.type === 'percentage' ? v.value + '% OFF' : '₱' + parseFloat(v.value).toFixed(0) + ' OFF'}
                            </span>
                            <span class="text-xs font-mono ${codeColor} px-2 py-0.5 rounded font-bold">${v.code}</span>
                            ${statusBadge}
                        </div>
                        ${v.description ? `<p class="text-sm text-gray-500 mb-1">${v.description}</p>` : ''}
                        <div class="flex flex-wrap gap-3 text-xs text-gray-400">
                            ${v.min_order_amount ? `<span>Min. ₱${parseFloat(v.min_order_amount).toFixed(0)}</span>` : ''}
                            ${expiryLine}
                            <span>Collected ${v.claimed_at}</span>
                        </div>
                    </div>
                    ${actionBtn}
                </div>
            </div>`;
        }).join('');
    });
}

// One-time hint banner
function dismissVoucherHint() {
    document.getElementById('voucherHint').style.display = 'none';
    try { localStorage.setItem('voucher_hint_dismissed', '1'); } catch(e) {}
}
document.addEventListener('DOMContentLoaded', function() {
    const hint = document.getElementById('voucherHint');
    if (hint) {
        try {
            if (!localStorage.getItem('voucher_hint_dismissed')) {
                hint.style.display = 'flex';
            }
        } catch(e) { hint.style.display = 'flex'; }
    }
});

</script>
@endsection
