<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\StoreController;

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

// Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// User Routes
Route::middleware('auth')->group(function () {
    // Home Page (Setelah Login)
    Route::get('/home', [ProductController::class, 'index'])->name('home');
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
    Route::get('/live-search', [ProductController::class, 'liveSearch']);

    // Profile & Balance
    Route::get('/user/profile', function () {
        return view('user_view.profile');
    })->name('profile');
    
    Route::get('/user/balance', function () {
        return view('user_view.balance');
    })->name('balance');

    // Cart Routes
    Route::prefix('user/cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart');
        Route::post('/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
        Route::patch('/update/{id}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    });

    // Payment Routes
    Route::get('/user/payment', [CartController::class, 'payment'])->name('payment');
    Route::post('/checkout', [CartController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/orders/{order}', [CartController::class, 'orderConfirmation'])->name('order.confirmation');
});

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

    // Detail Merchant View
    Route::get('/detail', function () {
        return view('merchant_view.detailMerchant');
    })->name('merchant.detail');
    
    // Product Management
    Route::prefix('/products')->group(function () {
        Route::get('/create', [ProductController::class, 'create'])->name('product.create');
        Route::post('/', [ProductController::class, 'store'])->name('product.store');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('product.edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('product.update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('product.destroy');
    });
});

// View-only routes (legacy redirects)
Route::get('/merchant', function () {
    return redirect()->route('merchant.dashboard');
});

Route::get('/detailMerchant', function () {
    return redirect()->route('merchant.detail');
});

Route::get('/openMerchant', function () {
    return view('openMerchant');
})->name('openMerchant.legacy');
