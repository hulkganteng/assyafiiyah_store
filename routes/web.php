<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\BankAccountController as AdminBankAccountController;
use App\Http\Controllers\Admin\DiscountController as AdminDiscountController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// Guest Checkout & Orders (No Login Required)
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/locations/provinces', [LocationController::class, 'provinces'])->name('locations.provinces');
Route::get('/locations/cities/{provinceId}', [LocationController::class, 'cities'])->name('locations.cities');
Route::get('/locations/districts/{cityId}', [LocationController::class, 'districts'])->name('locations.districts');
Route::get('/locations/villages/{districtId}', [LocationController::class, 'villages'])->name('locations.villages');

Route::get('/orders/track', [OrderController::class, 'trackForm'])->name('orders.track');
Route::post('/orders/track', [OrderController::class, 'track'])->name('orders.track.submit');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::post('/orders/{order}/pay', [OrderController::class, 'storePayment'])->name('orders.pay');
Route::get('/orders/{order}/payment-receipt', [OrderController::class, 'paymentReceipt'])->name('orders.payment-receipt');
// Note: orders.index removed for guests as there is no account history.

// Authenticated Routes (Admin Only mostly, or explicit Dashboard if needed)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    
    // Profile Management (Maybe keep for Admin or if logic re-added)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('products', AdminProductController::class);
    Route::post('products/bulk-discount', [AdminProductController::class, 'bulkDiscount'])->name('products.bulk-discount');
    Route::get('discounts', [AdminDiscountController::class, 'index'])->name('discounts.index');
    Route::post('discounts/apply', [AdminDiscountController::class, 'apply'])->name('discounts.apply');
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update']);
    Route::post('orders/{order}/discount', [AdminOrderController::class, 'applyDiscount'])->name('orders.discount');
    Route::get('orders/{order}/shipping-receipt', [AdminOrderController::class, 'shippingReceipt'])->name('orders.shipping-receipt');
    Route::get('orders/{order}/payment-receipt', [AdminOrderController::class, 'paymentReceipt'])->name('orders.payment-receipt');
    Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::patch('payments/{payment}/verify', [AdminPaymentController::class, 'verify'])->name('payments.verify');
    Route::get('reports/export', [AdminOrderController::class, 'export'])->name('reports.export');
    Route::resource('bank-accounts', AdminBankAccountController::class)->except(['show']);
});

require __DIR__.'/auth.php';
