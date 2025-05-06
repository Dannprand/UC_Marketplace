<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\StoreController;

// use Illuminate\Foundation\Auth\EmailVerificationRequest;
// use Illuminate\Http\Request;

// Public Routes
Route::get('/', function () {
    return view('landingPage');
});

/*
// Email Verification Routes (commented out for future use)
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
*/

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

//Route payment and cart
Route::middleware(['auth'])->group(function () {
    // Route::get('/cart', [CartController::class, 'index'])->name('cart');
    // Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment');
});

Route::middleware('auth')->prefix('user')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
});

// User Routes
Route::prefix('user')->group(function () {
    Route::get('/home', [ProductController::class, 'index'])->name('home');

    Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

    Route::get('/live-search', [ProductController::class, 'liveSearch']);
    
    Route::get('/payment', function () {
        return view('user_view.payment');
    })->name('payment');

    Route::get('/profile', function () {
        return view('user_view.profile');
    })->name('profile');
    
    Route::get('/balance', function () {
        return view('user_view.balance');
    })->name('balance');
});


// Optional: view-only routes (if you're still using them)
Route::get('/merchant', function () {
    return view('merchant');
})->name('merchant');

Route::get('/openMerchant', function () {
    return view('openMerchant');
})->name('openMerchant');

Route::get('/detailMerchant', function () {
    return view('detailMerchant');
})->name('detailMerchant');

// Merchant Routes with controller logic
Route::prefix('merchant')->middleware(['auth'])->group(function () {
    // Open Merchant (initial form)
    Route::get('/open', [MerchantController::class, 'showOpenForm'])->name('merchant.open');
    Route::post('/open', [MerchantController::class, 'openMerchant']);
    // Manage Merchant (merchant password confirmation etc.)
    Route::get('/manage', [MerchantController::class, 'showManageForm'])->name('merchant.manage');
    Route::post('/manage', [MerchantController::class, 'manageMerchant']);
    // Create Store (after merchant registration)
    Route::get('/store/create', [StoreController::class, 'create'])->name('store.create');
    // Route::post('/store', [StoreController::class, 'store']);
    Route::post('/store', [StoreController::class, 'store'])->name('store.store');
    // Dashboard (after store is created)
    Route::get('/dashboard', [MerchantController::class, 'dashboard'])->name('merchant.dashboard');
    // Add these inside your merchant prefix group
    Route::get('/products/create', [ProductController::class, 'create'])->name('product.create');

    
});

// Product Routes
// Product Management Routes
Route::get('/products/create', [ProductController::class, 'create'])->name('product.create');
Route::post('/products', [ProductController::class, 'store'])->name('product.store');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('product.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('product.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('product.destroy');