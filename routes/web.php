<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;

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
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// Payment Route
// Route::middleware(['auth'])->group(function () {
//     Route::get('/payment', [PaymentController::class, 'index'])->name('payment');
// });

// User Routes
Route::middleware('auth')->prefix('user')->group(function () {
    Route::get('/payment', function () {
        return view('user_view.payment');
    })->name('payment');
    
    Route::get('/profile', function () {
        return view('user_view.profile');
    })->name('profile');
    
    Route::get('/balance', function () {
        return view('user_view.balance');
    })->name('balance');

    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/payment', [CartController::class, 'payment'])->name('payment');
    Route::post('/checkout', [CartController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/orders/{order}', [CartController::class, 'orderConfirmation'])->name('order.confirmation');
});
    //Page Awal User Masuk!!
    Route::get('/home', [ProductController::class, 'index'])->name('home');
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
    Route::get('/live-search', [ProductController::class, 'liveSearch']);

// Merchant Routes
Route::prefix('merchant')->group(function () {
    Route::get('/dashboard', function () {
        return view('merchant_view.dashboard');
    })->name('merchant.dashboard');
});

Route::get('/merchant', function () {
    return view('merchant');
})->name('merchant');

Route::get('/openMerchant', function () {
    return view('openMerchant');
})->name('openMerchant');

Route::get('/detailMerchant', function () {
    return view('detailMerchant');
})->name('detailMerchant');
