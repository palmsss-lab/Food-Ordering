{{-- Shared form fields for create/edit --}}
@php $p = $promotion ?? null; @endphp

{{-- Title --}}
<div>
    <label class="block text-sm font-semibold text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
    <input type="text" name="title" value="{{ old('title', $p?->title) }}"
           placeholder="e.g. Christmas Special"
           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#ea5a47] focus:border-transparent @error('title') border-red-400 @enderror">
    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

{{-- Description --}}
<div>
    <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
    <textarea name="description" rows="2"
              placeholder="e.g. Enjoy 30% off all items this Christmas!"
              class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#ea5a47] focus:border-transparent resize-none @error('description') border-red-400 @enderror">{{ old('description', $p?->description) }}</textarea>
    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

{{-- Discount percentage --}}
<div>
    <label class="block text-sm font-semibold text-gray-700 mb-1">Discount Percentage <span class="text-red-500">*</span></label>
    <div class="relative">
        <input type="number" name="discount_percentage" min="1" max="100" step="0.01"
               value="{{ old('discount_percentage', $p?->discount_percentage) }}"
               placeholder="30"
               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#ea5a47] focus:border-transparent pr-10 @error('discount_percentage') border-red-400 @enderror">
        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">%</span>
    </div>
    @error('discount_percentage') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

{{-- Date range --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Start Date <span class="text-red-500">*</span></label>
        <input type="date" name="start_date" value="{{ old('start_date', $p?->start_date?->format('Y-m-d')) }}"
               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#ea5a47] focus:border-transparent @error('start_date') border-red-400 @enderror">
        @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">End Date <span class="text-red-500">*</span></label>
        <input type="date" name="end_date" value="{{ old('end_date', $p?->end_date?->format('Y-m-d')) }}"
               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#ea5a47] focus:border-transparent @error('end_date') border-red-400 @enderror">
        @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
</div>

{{-- Banner color --}}
<div>
    <label class="block text-sm font-semibold text-gray-700 mb-1">Modal Accent Color</label>
    <div class="flex items-center gap-3">
        <input type="color" name="banner_color" value="{{ old('banner_color', $p?->banner_color ?? '#ea5a47') }}"
               class="w-12 h-10 rounded-lg border border-gray-300 cursor-pointer p-1">
        <span class="text-sm text-gray-500">Choose the accent color for the promo popup modal</span>
    </div>
</div>

{{-- Active toggle --}}
<div class="flex items-center gap-3">
    <input type="checkbox" name="is_active" id="is_active" value="1"
           {{ old('is_active', $p?->is_active ?? true) ? 'checked' : '' }}
           class="w-5 h-5 accent-[#ea5a47] rounded cursor-pointer">
    <label for="is_active" class="text-sm font-semibold text-gray-700 cursor-pointer">Active (show popup to clients when date is within range)</label>
</div>
