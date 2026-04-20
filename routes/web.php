<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Client\ClientProfileController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\MenuItemsController;
use App\Http\Controllers\Client\OrderController as ClientOrderController; // Alias for client
use App\Http\Controllers\Admin\OrderController as AdminOrderController; // Add this for admin
use App\Http\Controllers\Admin\SalesReportController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Client\VoucherController as ClientVoucherController;
use App\Http\Controllers\Client\PaymentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Client\TransactionController;
use Illuminate\Support\Facades\Route;



// =============================================
// PUBLIC ROUTES (no restrictions)
// =============================================
Route::middleware('guest')->group(function () {
    // Regular auth
    Route::get('/login', [AuthenticationController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [AuthenticationController::class, 'login'])->name('login')->middleware('throttle:5,1');
    Route::get('/signup', [AuthenticationController::class, 'showSignupForm'])->name('signup.form');
    Route::post('/signup', [AuthenticationController::class, 'signup'])->name('signup')->middleware('throttle:10,1');

});

// Logout
Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout')->middleware('auth.check');

// =============================================
// PROTECTED CLIENT ROUTES
// =============================================
Route::middleware(['auth.check', 'client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/home', [ClientController::class, 'hero'])->name('home');
    Route::get('/profile', [ClientProfileController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [ClientProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ClientProfileController::class, 'update'])->name('profile.update');
    Route::get('/password/change', [ClientProfileController::class, 'changePassword'])->name('password.change');
    Route::post('/password/update', [ClientProfileController::class, 'updatePassword'])->name('password.update');
    Route::get('menu', [MenuItemsController::class, 'index'])->name('menu');
    Route::view('/about', 'client.content.about')->name('about');
    Route::get('/order-counts', [ClientController::class, 'getOrderCounts'])->name('order.counts');
    // Delete account route
    Route::delete('/account/delete', [ClientProfileController::class, 'deleteAccount'])->name('account.delete');
    
    // Verify password route (for AJAX)
    Route::post('/verify-password', [ClientProfileController::class, 'verifyPassword'])->name('verify-password');

    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'transactions'])->name('index');
        Route::get('/{transactionNumber}', [TransactionController::class, 'showTransaction'])->name('show');
        Route::get('/{transactionNumber}/download', [TransactionController::class, 'downloadReceipt'])->name('download');
    });

    // Cart Routes
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{menuItem}', [CartController::class, 'add'])->name('add');
        Route::put('/update/{itemId}', [CartController::class, 'update'])->name('update');
        Route::delete('/remove/{itemId}', [CartController::class, 'remove'])->name('remove');
        Route::post('/clear', [CartController::class, 'clear'])->name('clear');
    });


    // Vouchers
    Route::get('/vouchers', [ClientVoucherController::class, 'index'])->name('vouchers.index');
    Route::post('/vouchers/{voucher}/claim', [ClientVoucherController::class, 'claim'])->name('vouchers.claim');
    Route::get('/vouchers/mine', [ClientVoucherController::class, 'myVouchers'])->name('vouchers.mine');
    Route::get('/vouchers/collected', [ClientVoucherController::class, 'myCollectedHistory'])->name('vouchers.collected');

    // Checkout routes
    Route::post('/checkout', [CartController::class, 'showCheckout'])->name('checkout');
    // GET fallback: browser refresh or back-button on checkout page redirects cleanly to cart
    Route::get('/checkout', fn() => redirect()->route('client.cart.index')
        ->with('error', 'Your checkout session expired. Please select your items again.')
    )->name('checkout.get');
    Route::post('/place-order', [CartController::class, 'placeOrder'])->name('place-order');
    Route::post('/apply-discount', [CartController::class, 'applyDiscount'])->name('apply-discount');

    // Client Orders Routes - using ClientOrderController
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [ClientOrderController::class, 'index'])->name('index');
        Route::get('/{orderNumber}', [ClientOrderController::class, 'show'])->name('show');
        Route::get('/{orderNumber}/download', [ClientOrderController::class, 'downloadReceipt'])->name('download');
        Route::post('/{order}/picked-up', [ClientOrderController::class, 'markAsPickedUp'])->name('picked-up');
        Route::put('/{order}/cancel', [ClientOrderController::class, 'cancel'])->name('cancel');
        Route::get('/{order}/receipt', [ClientOrderController::class, 'showReceipt'])->name('receipt');
        Route::post('/check-updates', [ClientOrderController::class, 'checkUpdates'])->name('check-updates');
    });

    // Payment Routes
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::post('/process/{order}', [PaymentController::class, 'process'])->name('process');
        Route::post('/cash/{order}', [PaymentController::class, 'cash'])->name('cash');
        Route::get('/pending/{orderNumber}', [PaymentController::class, 'pending'])->name('pending');
        Route::get('/gcash-waiting/{order}', [PaymentController::class, 'gcashWaiting'])->name('gcash-waiting');
        Route::get('/success/{orderNumber}', [PaymentController::class, 'success'])->name('success');
        Route::get('/receipt/{orderNumber}/download', [PaymentController::class, 'downloadReceipt'])->name('receipt.download');
        Route::get('/failed/{orderNumber}', [PaymentController::class, 'failed'])->name('failed');
        Route::get('/check-status/{order}', [PaymentController::class, 'checkStatus'])->name('check-status');
    });
});

// =============================================
// PROTECTED ADMIN ROUTES
// =============================================
Route::middleware(['auth.check', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
    Route::get('/users', [DashboardController::class, 'users'])->name('users');
    Route::get('/profile', [AdminProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [AdminProfileController::class, 'changePassword'])->name('profile.password');
    Route::post('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::resource('menu-items', MenuItemController::class);
    Route::post('menu-items/{menuItem}/toggle-stock', [MenuItemController::class, 'toggleStock'])->name('menu-items.toggle-stock');
    Route::post('menu-items/{id}/restore', [MenuItemController::class, 'restore'])->name('menu-items.restore');
    Route::delete('menu-items/{id}/force-delete', [MenuItemController::class, 'forceDelete'])->name('menu-items.force-delete');
    Route::resource('categories', CategoryController::class);
    Route::post('categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.force-delete');

    // ==================== ADMIN ORDER ROUTES ====================
    // Orders list with tabs
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    
    // View single order
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    
    // Confirm order (for all payment methods)
    Route::post('/orders/{order}/confirm', [AdminOrderController::class, 'confirmOrder'])->name('orders.confirm');
    
    // Reject order
    Route::post('/orders/{order}/reject', [AdminOrderController::class, 'rejectOrder'])->name('orders.reject');
    
    // Update order status (preparing → ready → completed)
    Route::put('/orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    
    // Mark cash order as paid
    Route::post('/orders/{order}/mark-as-paid', [AdminOrderController::class, 'markAsPaid'])->name('orders.mark-as-paid');
    
    // Mark order as ready (if you have separate method)
    Route::post('/orders/{order}/ready', [AdminOrderController::class, 'markAsReady'])->name('orders.ready');
    
    // Transactions (completed orders)
    Route::get('/transactions', [AdminTransactionController::class, 'transactions'])->name('orders.transactions');
   
    Route::get('/transactions/{transactionNumber}', [AdminTransactionController::class, 'showTransaction'])->name('transactions.show');
    Route::post('/transactions/{transaction}/refund', [AdminTransactionController::class, 'refund'])->name('transactions.refund');
    
    // Backward compatibility (keep old routes if needed)
    Route::post('/orders/{order}/confirm-cash', [AdminOrderController::class, 'confirmCashPayment'])->name('orders.confirm-cash');
    Route::post('/orders/{order}/reject-cash', [AdminOrderController::class, 'rejectCashPayment'])->name('orders.reject-cash');
    
    Route::post('/orders/check-updates', [AdminOrderController::class, 'checkAdminUpdates'])->name('orders.check-updates');

    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/', [SalesReportController::class, 'index'])->name('index');
        Route::get('/export', [SalesReportController::class, 'export'])->name('export');
    });

    // Vouchers
    Route::resource('vouchers', VoucherController::class);
    Route::post('vouchers/{voucher}/toggle', [VoucherController::class, 'toggle'])->name('vouchers.toggle');

    Route::resource('promotions', PromotionController::class)->except(['show']);

});

// =============================================
// HOME REDIRECT
// =============================================
Route::get('/', [HomeController::class, 'index']);
