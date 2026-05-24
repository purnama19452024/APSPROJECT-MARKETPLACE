<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ReturnRequestController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\SaldoController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Admin\ReturnRequestController as AdminReturnRequestController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\SaldoController as AdminSaldoController;
use App\Http\Controllers\Supervisor\SupervisorController;
use App\Http\Controllers\Supervisor\TransactionController as SupervisorTransactionController;
use App\Http\Controllers\Supervisor\ComplaintController as SupervisorComplaintController;
use App\Http\Controllers\Supervisor\ReturnRequestController as SupervisorReturnRequestController;
use App\Http\Controllers\Supervisor\ReviewController as SupervisorReviewController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categories/{slug}', [ProductController::class, 'category'])->name('products.category');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');

// Region data (AJAX)
Route::get('/api/cities/{provinceCode}', function ($code) {
    return \Laravolt\Indonesia\Models\City::where('province_code', $code)->orderBy('name')->get(['code', 'name']);
});
Route::get('/api/districts/{cityCode}', function ($code) {
    return \Laravolt\Indonesia\Models\District::where('city_code', $code)->orderBy('name')->get(['code', 'name']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cart}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/buy-now/{product}', [CartController::class, 'buyNow'])->name('cart.buy-now');

    // Checkout
    Route::get('/checkout', [TransactionController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/process', [TransactionController::class, 'process'])->name('checkout.process');

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{transaction}/payment', [TransactionController::class, 'uploadPayment'])->name('transactions.payment');
    Route::post('/transactions/{transaction}/confirm', [TransactionController::class, 'confirmReceived'])->name('transactions.confirm');

    // QRIS
    Route::get('/transactions/{transaction}/qris', [TransactionController::class, 'qrisPage'])->name('transactions.qris');
    Route::post('/transactions/{transaction}/qris/confirm', [TransactionController::class, 'qrisConfirm'])->name('transactions.qris.confirm');

    // Reviews
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{transaction}', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::match(['get', 'post'], '/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Complaints
    Route::get('/complaints/create/{transaction}', [\App\Http\Controllers\ComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaints', [\App\Http\Controllers\ComplaintController::class, 'store'])->name('complaints.store');

    // Returns
    Route::get('/returns', [ReturnRequestController::class, 'index'])->name('returns.index');
    Route::get('/returns/create/{transaction}', [ReturnRequestController::class, 'create'])->name('returns.create');
    Route::post('/returns', [ReturnRequestController::class, 'store'])->name('returns.store');
    Route::get('/returns/{return}', [ReturnRequestController::class, 'show'])->name('returns.show');

    // Addresses
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::get('/addresses/create', [AddressController::class, 'create'])->name('addresses.create');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::get('/addresses/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit');
    Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');

    // Tracking
    Route::get('/tracking', [TrackingController::class, 'search'])->name('tracking.search');
    Route::get('/tracking/{transaction}', [TrackingController::class, 'show'])->name('tracking.show');

    // Saldo
    Route::get('/saldo', [SaldoController::class, 'index'])->name('saldo.index');
    Route::get('/saldo/topup', [SaldoController::class, 'topup'])->name('saldo.topup');
    Route::post('/saldo/topup', [SaldoController::class, 'topupStore'])->name('saldo.topup.store');
    Route::post('/saldo/topup/confirm', [SaldoController::class, 'topupConfirm'])->name('saldo.topup.confirm');
    Route::get('/saldo/withdraw', [SaldoController::class, 'withdraw'])->name('saldo.withdraw');
    Route::post('/saldo/withdraw', [SaldoController::class, 'withdrawStore'])->name('saldo.withdraw.store');

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');

    // Users
    Route::resource('/users', AdminUserController::class);
    Route::post('/users/{user}/suspend', [AdminUserController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{user}/activate', [AdminUserController::class, 'activate'])->name('users.activate');
    Route::post('/users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');

    // Categories
    Route::resource('/categories', AdminCategoryController::class);

    // Products
    Route::resource('/products', AdminProductController::class);
    Route::post('/products/{product}/approve', [AdminProductController::class, 'approve'])->name('products.approve');

    // Transactions (Pesanan Pelanggan)
    Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [AdminTransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{transaction}/status', [AdminTransactionController::class, 'updateStatus'])->name('transactions.update-status');
    Route::post('/transactions/{transaction}/verify-payment', [AdminTransactionController::class, 'verifyPayment'])->name('transactions.verify-payment');
    Route::post('/transactions/{transaction}/confirm-cod', [AdminTransactionController::class, 'confirmCod'])->name('transactions.confirm-cod');
    Route::delete('/transactions/{transaction}', [AdminTransactionController::class, 'destroy'])->name('transactions.destroy');

    // Banners
    Route::resource('/banners', BannerController::class);

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Complaints
    Route::get('/complaints', [AdminComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/{complaint}', [AdminComplaintController::class, 'show'])->name('complaints.show');
    Route::post('/complaints/{complaint}/respond', [AdminComplaintController::class, 'respond'])->name('complaints.respond');

    // Returns
    Route::get('/returns', [AdminReturnRequestController::class, 'index'])->name('returns.index');
    Route::get('/returns/{return}', [AdminReturnRequestController::class, 'show'])->name('returns.show');
    Route::post('/returns/{return}/respond', [AdminReturnRequestController::class, 'respond'])->name('returns.respond');

    // Reviews
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Saldo
    Route::get('/saldo', [AdminSaldoController::class, 'index'])->name('saldo.index');
    Route::get('/saldo/{user}/edit', [AdminSaldoController::class, 'edit'])->name('saldo.edit');
    Route::post('/saldo/{user}', [AdminSaldoController::class, 'update'])->name('saldo.update');
    Route::get('/saldo/withdrawals', [AdminSaldoController::class, 'withdrawals'])->name('saldo.withdrawals');
    Route::post('/saldo/withdrawals/{withdrawal}/approve', [AdminSaldoController::class, 'withdrawalsApprove'])->name('saldo.withdrawals.approve');
    Route::post('/saldo/withdrawals/{withdrawal}/reject', [AdminSaldoController::class, 'withdrawalsReject'])->name('saldo.withdrawals.reject');

    // Backup
    Route::get('/backup', [AdminController::class, 'backup'])->name('backup');
    Route::post('/backup/run', [AdminController::class, 'runBackup'])->name('backup.run');
    Route::get('/backup/download/{filename}', [AdminController::class, 'downloadBackup'])->name('backup.download');
    Route::delete('/backup/{filename}', [AdminController::class, 'deleteBackup'])->name('backup.delete');
});

// Supervisor Routes
Route::middleware(['auth', 'supervisor'])->prefix('supervisor')->name('supervisor.')->group(function () {
    Route::get('/dashboard', [SupervisorController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [SupervisorController::class, 'analytics'])->name('analytics');

    // Transactions
    Route::get('/transactions', [SupervisorTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [SupervisorTransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{transaction}/verify-payment', [SupervisorTransactionController::class, 'verifyPayment'])->name('transactions.verify-payment');
    Route::post('/transactions/{transaction}/update-status', [SupervisorTransactionController::class, 'updateStatus'])->name('transactions.update-status');

    // Complaints
    Route::get('/complaints', [SupervisorComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/{complaint}', [SupervisorComplaintController::class, 'show'])->name('complaints.show');
    Route::post('/complaints/{complaint}/respond', [SupervisorComplaintController::class, 'respond'])->name('complaints.respond');

    // Returns
    Route::get('/returns', [SupervisorReturnRequestController::class, 'index'])->name('returns.index');
    Route::get('/returns/{return}', [SupervisorReturnRequestController::class, 'show'])->name('returns.show');
    Route::post('/returns/{return}/respond', [SupervisorReturnRequestController::class, 'respond'])->name('returns.respond');

    // Reviews
    Route::get('/reviews', [SupervisorReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{review}', [SupervisorReviewController::class, 'destroy'])->name('reviews.destroy');

    // Reports
    Route::get('/reports', [SupervisorController::class, 'reports'])->name('reports');
    Route::get('/reports/export', [SupervisorController::class, 'exportReport'])->name('reports.export');
});

require __DIR__.'/auth.php';
