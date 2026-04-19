@extends('admin.layouts.home', ['active' => 'vouchers'])

@section('title', 'Add Voucher')

@section('content')
<div class="relative min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] py-8 px-4 sm:px-6">
    <div class="relative z-10 max-w-2xl mx-auto">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="absolute inset-0 bg-[#ea5a47] rounded-lg blur-md opacity-30"></div>
                    <div class="relative bg-gradient-to-br from-[#ea5a47] to-[#c53030] p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                </div>
                <h1 class="text-xl sm:text-3xl font-black text-gray-800">Add <span class="text-[#ea5a47]">Voucher</span></h1>
            </div>
            <a href="{{ route('admin.vouchers.index') }}"
               class="flex items-center gap-2 bg-white border-2 border-gray-200 text-gray-700 font-medium px-4 py-2 rounded-xl hover:border-[#ea5a47] transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back
            </a>
        </div>

        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 p-8">
            @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 px-4 py-3 rounded-lg">
                    <ul class="text-red-600 text-sm space-y-1 list-disc list-inside">
                        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.vouchers.store') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Voucher Code <span class="text-[#ea5a47]">*</span></label>
                    <input type="text" name="code" value="{{ old('code') }}"
                           placeholder="e.g. SAVE10, WELCOME20"
                           class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] outline-none transition-all uppercase @error('code') border-red-400 @enderror"
                           required>
                    <p class="text-xs text-gray-400 mt-1">Will be auto-uppercased.</p>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <input type="text" name="description" value="{{ old('description') }}"
                           placeholder="e.g. 10% off on all orders"
                           class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] outline-none transition-all">
                </div>

                <!-- Type + Value -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Discount Type <span class="text-[#ea5a47]">*</span></label>
                        <select name="type" id="discountType"
                                class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] outline-none transition-all"
                                onchange="updateValueLabel()" required>
                            <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                            <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Fixed Amount (₱)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Value <span class="text-[#ea5a47]">*</span>
                            <span id="valueUnit" class="text-gray-400 font-normal">(e.g. 10 for 10%)</span>
                        </label>
                        <input type="number" name="value" value="{{ old('value') }}"
                               min="0.01" step="0.01" placeholder="0"
                               class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] outline-none transition-all @error('value') border-red-400 @enderror"
                               required>
                    </div>
                </div>

                <!-- Min Order + Max Uses -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Min Order Amount (₱)</label>
                        <input type="number" name="min_order_amount" value="{{ old('min_order_amount') }}"
                               min="0" step="0.01" placeholder="None"
                               class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] outline-none transition-all">
                        <p class="text-xs text-gray-400 mt-1">Leave blank for no minimum.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Total Redemptions</label>
                        <input type="number" name="max_uses" value="{{ old('max_uses') }}"
                               min="1" step="1" placeholder="Unlimited"
                               class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] outline-none transition-all">
                        <p class="text-xs text-gray-400 mt-1">Total times this voucher can be redeemed across <strong>all users</strong>. Leave blank for unlimited. Each user is already limited to 1 use.</p>
                    </div>
                </div>

                <!-- Expiry -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                    <input type="date" name="expires_at" value="{{ old('expires_at') }}"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#ea5a47] outline-none transition-all">
                    <p class="text-xs text-gray-400 mt-1">Leave blank for no expiry.</p>
                </div>

                <!-- Toggles -->
                <div class="flex flex-col gap-3">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', '1') ? 'checked' : '' }}
                               class="w-5 h-5 text-[#ea5a47] rounded focus:ring-[#ea5a47]">
                        <label for="is_active" class="text-sm font-medium text-gray-700">Active (usable at checkout)</label>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_public" id="is_public" value="1"
                               {{ old('is_public', '1') ? 'checked' : '' }}
                               class="w-5 h-5 text-[#ea5a47] rounded focus:ring-[#ea5a47]">
                        <label for="is_public" class="text-sm font-medium text-gray-700">
                            Public — show on the Vouchers browse page
                            <span class="text-xs text-gray-400 font-normal block">Uncheck for exclusive/private codes only</span>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 pt-2 border-t border-gray-100 mt-6">
                    <button type="submit"
                            data-loading-text="Creating..."
                            class="flex items-center gap-2 bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-bold py-3 px-8 rounded-xl hover:shadow-xl transition-all transform hover:scale-[1.02]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Create Voucher
                    </button>
                    <a href="{{ route('admin.vouchers.index') }}"
                       class="flex items-center gap-2 bg-white border-2 border-gray-200 text-gray-700 font-bold py-3 px-8 rounded-xl hover:border-[#ea5a47] transition-all">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Live discount preview -->
<div id="discountPreview" class="hidden mt-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm text-green-700">
    <span class="font-semibold">Preview:</span> <span id="previewText"></span>
</div>

<script>
function updateValueLabel() {
    const type = document.getElementById('discountType').value;
    document.getElementById('valueUnit').textContent = type === 'percentage' ? '(e.g. 10 for 10%)' : '(e.g. 50 for ₱50)';
    updatePreview();
}
updateValueLabel();

function updatePreview() {
    const type  = document.getElementById('discountType').value;
    const value = parseFloat(document.querySelector('[name="value"]').value);
    const min   = parseFloat(document.querySelector('[name="min_order_amount"]').value) || 0;
    const preview = document.getElementById('discountPreview');
    const previewText = document.getElementById('previewText');
    if (!value || value <= 0) { preview.classList.add('hidden'); return; }

    let text = '';
    if (type === 'percentage') {
        const capped = Math.min(value, 100);
        text = `${capped}% off`;
        if (min > 0) text += ` on orders ≥ ₱${min.toFixed(2)}`;
        text += `. Example: on a ₱500 order → save ₱${(500 * capped / 100).toFixed(2)}, pay ₱${(500 - 500 * capped / 100).toFixed(2)}.`;
    } else {
        text = `₱${value.toFixed(2)} off`;
        if (min > 0) text += ` on orders ≥ ₱${min.toFixed(2)}`;
        text += `.`;
    }
    previewText.textContent = text;
    preview.classList.remove('hidden');
}

document.querySelector('[name="value"]').addEventListener('input', updatePreview);
document.querySelector('[name="min_order_amount"]').addEventListener('input', updatePreview);

// Auto-uppercase code input
document.querySelector('[name="code"]').addEventListener('input', function() {
    const pos = this.selectionStart;
    this.value = this.value.toUpperCase();
    this.setSelectionRange(pos, pos);
});
</script>
@endsection
