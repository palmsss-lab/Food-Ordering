@extends('admin.layouts.home', ['active' => 'promotions'])

@section('title', 'Promotions')

@section('content')
<div class="relative min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9] py-8 px-4 sm:px-6">
    <div class="relative z-10 max-w-6xl mx-auto">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="absolute inset-0 bg-[#ea5a47] rounded-lg blur-md opacity-30"></div>
                    <div class="relative bg-gradient-to-br from-[#ea5a47] to-[#c53030] p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                </div>
                <h1 class="text-xl sm:text-3xl font-black text-gray-800">
                    Promotions & <span class="text-[#ea5a47]">Deals</span>
                </h1>
            </div>
            <a href="{{ route('admin.promotions.create') }}"
               class="flex items-center gap-2 bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-bold px-6 py-3 rounded-xl hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Promotion
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-50 border-l-4 border-green-500 px-4 py-3 rounded-lg text-green-700 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        {{-- Table --}}
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[600px] text-sm text-left text-gray-700">
                    <thead class="text-xs uppercase bg-gray-50/80 border-b-2 border-gray-200">
                        <tr>
                            <th class="px-6 py-4">Title</th>
                            <th class="px-6 py-4">Discount</th>
                            <th class="px-6 py-4">Date Range</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($promotions as $promo)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-4 h-4 rounded-full flex-shrink-0" style="background-color: {{ $promo->banner_color }}"></div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $promo->title }}</p>
                                        @if($promo->description)
                                            <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ $promo->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-[#ea5a47] text-lg">{{ $promo->discount_percentage }}%</span>
                                <span class="text-gray-500 text-xs ml-1">off</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                <div>{{ $promo->start_date->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-400">to {{ $promo->end_date->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php $label = $promo->statusLabel(); @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold
                                    {{ $label === 'Active'    ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $label === 'Upcoming'  ? 'bg-blue-100 text-blue-700'   : '' }}
                                    {{ $label === 'Expired'   ? 'bg-gray-100 text-gray-500'   : '' }}
                                    {{ $label === 'Inactive'  ? 'bg-red-100 text-red-600'     : '' }}">
                                    {{ $label }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.promotions.edit', $promo) }}"
                                       class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-all"
                                       title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <button type="button"
                                            onclick="openDeleteModal({{ $promo->id }}, '{{ addslashes($promo->title) }}')"
                                            class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-all"
                                            title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                No promotions yet. <a href="{{ route('admin.promotions.create') }}" class="text-[#ea5a47] hover:underline font-medium">Create one</a>.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($promotions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $promotions->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" id="modal-overlay"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="relative inline-block align-bottom bg-white/95 backdrop-blur-sm rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/20">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-[#ea5a47] to-[#c53030] opacity-5 rounded-bl-3xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-[#ea5a47] to-[#c53030] opacity-5 rounded-tr-3xl"></div>

            <div class="p-6 relative">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-14 w-14 rounded-full bg-red-50 sm:mx-0 sm:h-12 sm:w-12">
                        <svg class="h-7 w-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>

                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-xl font-black text-gray-900">Delete <span class="text-[#ea5a47]">Promotion</span></h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-600 leading-relaxed">
                                Are you sure you want to delete <span class="font-semibold text-[#ea5a47]" id="modalPromoTitle"></span>? This action cannot be undone.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <form id="deleteForm" method="POST">
                @csrf @method('DELETE')
                <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-200 flex flex-col sm:flex-row-reverse gap-3">
                    <button type="submit"
                            class="w-full inline-flex justify-center items-center gap-2 rounded-xl border border-transparent px-5 py-3 bg-gradient-to-r from-red-600 to-red-700 text-base font-bold text-white shadow-lg hover:shadow-xl transform hover:scale-[1.02] focus:outline-none transition-all duration-300 sm:w-auto sm:text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Yes, Delete Promotion
                    </button>
                    <button type="button" id="cancelDelete"
                            class="w-full inline-flex justify-center items-center gap-2 rounded-xl border-2 border-gray-200 px-5 py-3 bg-white text-base font-bold text-gray-700 shadow-sm hover:border-[#ea5a47] hover:bg-gray-50 hover:text-[#ea5a47] focus:outline-none transition-all duration-300 sm:w-auto sm:text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal   = document.getElementById('deleteModal');
    const overlay = document.getElementById('modal-overlay');
    const form    = document.getElementById('deleteForm');
    const cancel  = document.getElementById('cancelDelete');
    const title   = document.getElementById('modalPromoTitle');

    window.openDeleteModal = function (id, name) {
        title.textContent = '"' + name + '"';
        form.action = '/admin/promotions/' + id;
        modal.classList.remove('hidden');
    };

    function closeModal() {
        modal.classList.add('hidden');
    }

    cancel.addEventListener('click', closeModal);
    overlay.addEventListener('click', closeModal);
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeModal();
    });
});

setTimeout(() => {
    const el = document.querySelector('.bg-green-50');
    if (el) el.style.display = 'none';
}, 4000);
</script>
@endsection
