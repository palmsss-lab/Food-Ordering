@extends('client.layouts.home')

@section('title', 'My Profile')

@section('content')
<div class="max-w-4xl mx-auto mt-32 px-4 mb-20">
    
    <!-- Header -->
    <div class="flex items-center gap-3 mb-8">
        <div class="bg-[#ea5a47] p-3 rounded-2xl shadow-lg">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
        <h1 class="text-4xl font-black text-gray-800">My <span class="text-[#ea5a47]">Profile</span></h1>
    </div>

    <div class="grid lg:grid-cols-1 gap-8 max-w-2xl mx-auto">
        
        <!-- Profile Info Card -->
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
            <!-- Profile Header with Avatar -->
            <div class="bg-gradient-to-r from-[#ea5a47] to-[#c53030] px-8 pt-8 pb-16 text-center relative">
                <div class="absolute top-0 left-0 w-full h-full opacity-10">
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <path d="M0,0 L100,0 L100,100 L0,100 Z" fill="url(#gradient)"/>
                    </svg>
                </div>
                <div class="relative">
                    <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center mx-auto shadow-xl border-4 border-white">
                        <span class="text-5xl font-bold text-[#ea5a47]">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <h2 class="text-2xl font-bold text-white mt-4">{{ Auth::user()->name }}</h2>
                    <p class="text-white/90">{{ Auth::user()->email }}</p>
                    <p class="text-white/80 text-sm mt-2">Member since {{ Auth::user()->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <!-- User Details Section -->
            <div class="p-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Personal Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-sm text-gray-500 mb-1">Full Name</p>
                        <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-sm text-gray-500 mb-1">Email Address</p>
                        <p class="font-semibold text-gray-800">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-sm text-gray-500 mb-1">Phone Number</p>
                        <p class="font-semibold text-gray-800">
                            @if(Auth::user()->phone)
                                {{ Auth::user()->phone }}
                            @else
                                <span class="text-gray-400">Not provided</span>
                            @endif
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-sm text-gray-500 mb-1">Member Since</p>
                        <p class="font-semibold text-gray-800">{{ Auth::user()->created_at->format('F d, Y') }}</p>
                    </div>
                </div>

                <!-- Address Section -->
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Address Information
                </h3>
                
                <div class="bg-gray-50 rounded-xl p-4 mb-8">
                    <p class="text-sm text-gray-500 mb-1">Address</p>
                    @if(Auth::user()->address)
                        <p class="font-semibold text-gray-800">{{ Auth::user()->address }}</p>
                    @else
                        <p class="text-gray-400">No address added yet</p>
                    @endif
                </div>

                <!-- Order Statistics -->
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#ea5a47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Order Statistics
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-4 text-center">
                        <p class="text-2xl font-bold text-[#ea5a47]">{{ \App\Models\Order::where('user_id', Auth::id())->count() }}</p>
                        <p class="text-sm text-gray-600">Total Orders</p>
                    </div>
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 text-center">
                        <p class="text-2xl font-bold text-green-600">{{ \App\Models\Order::where('user_id', Auth::id())->where('order_status', 'completed')->count() }}</p>
                        <p class="text-sm text-gray-600">Completed Orders</p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 text-center">
                        <p class="text-2xl font-bold text-purple-600">₱{{ number_format(\App\Models\Order::where('user_id', Auth::id())->where('order_status', 'completed')->sum('total'), 2) }}</p>
                        <p class="text-sm text-gray-600">Total Spent</p>
                    </div>
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-200 my-6"></div>

                <!-- Profile Actions -->
                <div class="space-y-3">
                    
                    <button onclick="openDeleteAccountModal()" 
                            class="w-full flex items-center cursor-pointer justify-between p-4 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all group">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span class="font-medium">Delete Account</span>
                        </div>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div id="deleteAccountModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="closeDeleteAccountModal()"></div>
        
        <div class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Delete Account</h2>
            </div>
            
            <p class="text-gray-600 mb-4">
                Are you sure you want to delete your account? This action cannot be undone. All your data including orders and transactions will be permanently removed.
            </p>
            
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-3 rounded-lg mb-4">
                <p class="text-sm text-yellow-700">
                    <span class="font-bold">Warning:</span> This will permanently delete:
                </p>
                <ul class="text-xs text-yellow-600 mt-2 space-y-1">
                    <li>• Your profile information</li>
                    <li>• All your orders</li>
                    <li>• Transaction history</li>
                    <li>• Cart items</li>
                </ul>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Confirm your password to continue</label>
                <input type="password" id="delete_password" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-red-500 outline-none"
                       placeholder="Enter your password">
                <p id="password_error" class="text-red-500 text-xs mt-1 hidden">Incorrect password</p>
            </div>
            
            <div class="flex gap-3">
                <form id="deleteAccountForm" action="{{ route('client.account.delete') }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="password" id="delete_password_input">
                    <button type="submit" 
                            class="w-full px-4 py-2 cursor-pointer bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all">
                        Yes, Delete My Account
                    </button>
                </form>
                <button type="button" onclick="closeDeleteAccountModal()"
                        class="px-4 py-2 border cursor-pointer border-gray-300 rounded-lg hover:bg-gray-50 transition-all">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Delete Account Modal Functions
    function openDeleteAccountModal() {
        document.getElementById('deleteAccountModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteAccountModal() {
        document.getElementById('deleteAccountModal').classList.add('hidden');
        document.body.style.overflow = '';
        document.getElementById('delete_password').value = '';
        document.getElementById('password_error').classList.add('hidden');
    }

    // Password confirmation before submitting
    document.getElementById('deleteAccountForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const password = document.getElementById('delete_password').value;
        
        if (!password) {
            document.getElementById('password_error').textContent = 'Please enter your password';
            document.getElementById('password_error').classList.remove('hidden');
            return;
        }
        
        // Verify password via AJAX
        fetch('{{ route("client.verify-password") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ password: password })
        })
        .then(response => response.json())
        .then(data => {
            if (data.valid) {
                document.getElementById('delete_password_input').value = password;
                document.getElementById('deleteAccountForm').submit();
            } else {
                document.getElementById('password_error').textContent = 'Incorrect password';
                document.getElementById('password_error').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('password_error').textContent = 'Error verifying password';
            document.getElementById('password_error').classList.remove('hidden');
        });
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteAccountModal();
        }
    });
</script>
@endsection