@extends('admin.layouts.home', ['active' => 'categories'])

@section('title', 'Manage Categories')

@section('content')
<div class="relative min-h-screen bg-gradient-to-br from-[#fdf7f2] to-[#f5e8d9]">
    <!-- Decorative Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-20 -right-20 w-96 h-96 bg-[#ea5a47] opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-[#c53030] opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-to-r from-[#ea5a47]/5 to-[#c53030]/5 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-30 lg:py-30">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="absolute inset-0 bg-[#ea5a47] rounded-lg blur-md opacity-30"></div>
                    <div class="relative bg-gradient-to-br from-[#ea5a47] to-[#c53030] p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                    </div>
                </div>
                <h1 class="text-3xl font-black text-gray-800">
                    Category <span class="text-[#ea5a47]">Management</span>
                </h1>
            </div>
            
            <a href="{{ route('admin.categories.create') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-semibold rounded-xl hover:from-[#c53030] hover:to-[#ea5a47] transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add New Category</span>
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between animate-slideDown" role="alert">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="text-green-700" onclick="this.parentElement.remove()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg flex items-center justify-between animate-slideDown" role="alert">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" class="text-red-700" onclick="this.parentElement.remove()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        <!-- Categories Table Card -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-[#ea5a47] to-[#c53030] opacity-5 rounded-bl-3xl"></div>
            
            <div class="relative z-10 overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs uppercase bg-gray-50/80 border-b-2 border-gray-200">
                        <tr>
                            <th class="px-6 py-4">Category Name</th>
                            <th class="px-6 py-4">Menu Items</th>
                            <th class="px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($categories as $category)
                        <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ $category->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium border border-blue-200">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    {{ $category->menu_items_count }} items
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.menu-items.index', ['category' => $category->id]) }}" 
                                       class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-200"
                                       title="View Items">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                        </svg>
                                    </a>
                                    
                                    <a href="{{ route('admin.categories.edit', $category) }}" 
                                       class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 transition-colors duration-200"
                                       title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    
                                    <button type="button" 
                                            class="delete-category p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors duration-200"
                                            data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}"
                                            data-item-count="{{ $category->menu_items_count }}"
                                            title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $category->id }}" 
                                      action="{{ route('admin.categories.destroy', $category) }}" 
                                      method="POST" 
                                      class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                        </svg>
                                    </div>
                                    <h5 class="text-xl font-bold text-gray-600 mb-2">No Categories Found</h5>
                                    <p class="text-gray-500 mb-4">Get started by creating your first category.</p>
                                    <a href="{{ route('admin.categories.create') }}" 
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-[#ea5a47] to-[#c53030] text-white font-semibold rounded-lg hover:from-[#c53030] hover:to-[#ea5a47] transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add New Category
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($categories->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $categories->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Category Confirmation Modal -->
<div id="deleteCategoryModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" id="modal-overlay-category"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        
        <div class="relative inline-block align-bottom bg-white/95 backdrop-blur-sm rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/20">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-[#ea5a47] to-[#c53030] opacity-5 rounded-bl-3xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-[#ea5a47] to-[#c53030] opacity-5 rounded-tr-3xl"></div>
            
            <div class="p-6 relative">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-14 w-14 rounded-full sm:mx-0 sm:h-12 sm:w-12" id="modal-icon-container">
                        <svg class="h-7 w-7" id="modal-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-xl font-black text-gray-900" id="modal-title-category"></h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-600 leading-relaxed" id="modal-message-category"></p>
                        </div>
                        
                        <div class="mt-3 bg-yellow-50 rounded-lg p-3 border-l-4 border-yellow-400" id="warning-message" style="display: none;">
                            <div class="flex items-start">
                                <svg class="w-4 h-4 text-yellow-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <p class="text-xs text-yellow-700">This action is irreversible. The category will be permanently deleted.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-200 flex flex-col sm:flex-row-reverse gap-3" id="modal-actions"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('deleteCategoryModal');
    const overlay = document.getElementById('modal-overlay-category');
    const modalTitle = document.getElementById('modal-title-category');
    const modalMessage = document.getElementById('modal-message-category');
    const modalIconContainer = document.getElementById('modal-icon-container');
    const modalIcon = document.getElementById('modal-icon');
    const warningMessage = document.getElementById('warning-message');
    const modalActions = document.getElementById('modal-actions');
    let currentDeleteId = null;
    let currentDeleteForm = null;
    let isDeleting = false;

    if (modal) modal.classList.add('hidden');

    // Delete button click handlers
    document.querySelectorAll('.delete-category').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const categoryId = this.dataset.id;
            const categoryName = this.dataset.name;
            const itemCount = parseInt(this.dataset.itemCount);
            
            console.log('Category ID:', categoryId); // Debugging
            console.log('Form ID:', `delete-form-${categoryId}`); // Debugging
            
            if (!categoryId || !categoryName) return;
            
            // Store the ID
            currentDeleteId = categoryId;
            
            // Get the delete form
            currentDeleteForm = document.getElementById(`delete-form-${categoryId}`);
            console.log('Delete form found:', currentDeleteForm); // Debugging
            
            if (itemCount > 0) {
                // Cannot delete - has items
                modalTitle.innerHTML = `Cannot Delete <span class="text-[#ea5a47]">Category</span>`;
                modalMessage.innerHTML = `Category "<strong>${categoryName}</strong>" has <strong>${itemCount}</strong> menu item(s). Please delete the menu items first.`;
                modalIconContainer.className = 'mx-auto flex-shrink-0 flex items-center justify-center h-14 w-14 rounded-full bg-red-100 sm:mx-0 sm:h-12 sm:w-12';
                modalIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />';
                warningMessage.style.display = 'none';
                
                modalActions.innerHTML = `
                    <button type="button" id="modalOkButton" class="w-full inline-flex justify-center items-center gap-2 rounded-xl px-5 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold shadow-lg hover:shadow-xl transition-all duration-300 sm:w-auto sm:text-sm">
                        OK
                    </button>
                `;
                document.getElementById('modalOkButton').addEventListener('click', () => hideModal());
            } else {
                // Can delete
                modalTitle.innerHTML = `Delete <span class="text-[#ea5a47]">Category</span>`;
                modalMessage.innerHTML = `Are you sure you want to delete <span class="font-semibold text-[#ea5a47]">"${categoryName}"</span>?`;
                modalIconContainer.className = 'mx-auto flex-shrink-0 flex items-center justify-center h-14 w-14 rounded-full bg-red-50 sm:mx-0 sm:h-12 sm:w-12';
                modalIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />';
                warningMessage.style.display = 'block';
                
                modalActions.innerHTML = `
                    <button type="button" id="confirmCategoryDelete" 
                            class="w-full inline-flex justify-center items-center gap-2 rounded-xl px-5 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-bold shadow-lg hover:shadow-xl hover:from-red-700 hover:to-red-800 hover:scale-105 transition-all duration-300 sm:ml-3 sm:w-auto sm:text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Yes, Delete Category
                    </button>

                    <button type="button" id="cancelCategoryDelete" 
                            class="w-full inline-flex justify-center items-center gap-2 rounded-xl border-2 border-gray-200 px-5 py-3 bg-white font-bold text-gray-700 shadow-sm hover:border-[#ea5a47] hover:bg-gray-50 hover:scale-105 hover:text-[#ea5a47] transition-all duration-300 sm:mt-0 sm:w-auto sm:text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancel
                    </button>
                `;
                
                const confirmBtn = document.getElementById('confirmCategoryDelete');
                const cancelBtn = document.getElementById('cancelCategoryDelete');
                
                if (confirmBtn) {
                    // Remove any existing listeners to prevent duplicates
                    const newConfirmBtn = confirmBtn.cloneNode(true);
                    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
                    
                    newConfirmBtn.addEventListener('click', function() {
                        if (isDeleting) return;
                        if (!currentDeleteId) {
                            alert('Error: No category selected');
                            return;
                        }
                        
                        // Get the delete form again to ensure it exists
                        const deleteForm = document.getElementById(`delete-form-${currentDeleteId}`);
                        if (!deleteForm) {
                            alert('Error: Delete form not found for ID: ' + currentDeleteId);
                            console.error('Form not found:', `delete-form-${currentDeleteId}`);
                            return;
                        }
                        
                        isDeleting = true;
                        
                        // Show loader on button
                        const btn = this;
                        btn.disabled = true;
                        btn.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div> Deleting...';
                        btn.classList.add('opacity-70', 'cursor-not-allowed');
                        
                        // Show global page loader
                        if (window.showLoader) {
                            window.showLoader();
                        }
                        
                        // Hide modal
                        hideModal();
                        
                        // Submit the form
                        setTimeout(() => {
                            deleteForm.submit();
                        }, 100);
                    });
                }
                
                if (cancelBtn) {
                    const newCancelBtn = cancelBtn.cloneNode(true);
                    cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);
                    newCancelBtn.addEventListener('click', () => hideModal());
                }
            }
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    });

    function hideModal() {
        if (modal) modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        // Don't clear currentDeleteId immediately to allow form submission
        setTimeout(() => {
            currentDeleteId = null;
            currentDeleteForm = null;
        }, 500);
    }

    if (overlay) overlay.addEventListener('click', () => hideModal());
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            hideModal();
        }
    });
    
    window.addEventListener('pageshow', function() {
        isDeleting = false;
    });
});
</script>

<style>
@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-slideDown {
    animation: slideDown 0.3s ease-out;
}
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
@endsection