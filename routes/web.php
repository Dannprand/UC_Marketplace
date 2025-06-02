<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\OrderController;

// Public Routes
Route::get('/', function () {
    return view('landingPage');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Address input
    Route::get('/register/address', [AuthController::class, 'showAddressForm'])->name('register.address');
    Route::post('/register/address', [AuthController::class, 'processAddress']);
});

// Admin routes
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/users', [AuthController::class, 'adminUsers'])->name('admin.users');
    Route::delete('/users/{user}', [AuthController::class, 'adminDeleteUser'])->name('admin.users.delete');
    Route::get('/users/{user}/orders', [AuthController::class, 'userOrders'])->name('admin.users.orders');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');


// User Routes
Route::middleware('auth')->prefix('user')->group(function () {  
    Route::get('/profile', function () {
        return view('user_view.profile');
    })->name('profile');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/balance', function () {
        return view('user_view.balance');
    })->name('balance');

    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // Payment routes
    Route::get('/payment', [CartController::class, 'payment'])->name('payment');
    Route::post('/address/store', [OrderController::class, 'storeAddress'])->name('address.store');
    // Route::delete('/address/{id}', [OrderController::class, 'deleteAddress'])->name('address.delete');
    Route::post('/checkout', [CartController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/checkout', [CartController::class, 'payment'])->name('checkout.payment');

    // Order Routes 
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

});
    // Page awal user masuk!
    Route::get('/home', [ProductController::class, 'index'])->name('home');

    //Product page
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
    Route::get('/live-search', [ProductController::class, 'liveSearch'])->name('live.search');
    

// Merchant Routes
Route::prefix('merchant')->middleware(['auth'])->group(function () {
    // Merchant Registration Flow
    Route::get('/open', [MerchantController::class, 'showOpenForm'])->name('merchant.open');
    Route::post('/open', [MerchantController::class, 'openMerchant']);
    
    // Merchant Management
    Route::get('/manage', [MerchantController::class, 'showManageForm'])->name('merchant.manage');
    Route::post('/manage', [MerchantController::class, 'manageMerchant']);
    
    // Store Management
    Route::get('/store/create', [StoreController::class, 'create'])->name('store.create');
    Route::post('/store', [StoreController::class, 'store'])->name('store.store');
    
    // Dashboard
    Route::get('/dashboard', [MerchantController::class, 'dashboard'])->name('merchant.dashboard');
    
    // Transactions Page
    Route::get('/transactions', [MerchantController::class, 'transactions'])->name('merchant.transactions');
    Route::put('/merchant/orders/{order}/status', [MerchantController::class, 'updateStatus'])->name('merchant.orders.updateStatus');

    // Shipping Page
    Route::get('/merchant/orders/{order}/shipping', [MerchantController::class, 'showShippingForm'])->name('merchant.orders.shipping');
    Route::post('/merchant/orders/{order}/shipping', [MerchantController::class, 'storeShipping'])->name('merchant.orders.shipping.store');

    // Add this inside the merchant group
    Route::get('/merchant/dashboard', [MerchantController::class, 'index'])->name('merchant_view.merchant');
     Route::get('/detail/{id}', [MerchantController::class, 'showDetail'])->name('merchant.detail');
     Route::get('/income-data', [MerchantController::class, 'getIncomeData'])->name('income.data');
    
    // Product Management
    Route::prefix('/products')->group(function () {
        Route::get('/create', [ProductController::class, 'create'])->name('product.create');
        Route::post('/', [ProductController::class, 'store'])->name('product.store');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('product.edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('product.update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('product.destroy');
    });
});

Route::get('/openMerchant', function () {
    return view('openMerchant'); // This remains in root views
})->name('openMerchant.legacy');

Route::get('/qr', [PaymentController::class, 'showQr']);