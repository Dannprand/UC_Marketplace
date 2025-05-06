<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProductController;
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

// User Routes
Route::prefix('user')->group(function () {
    Route::get('/home', [ProductController::class, 'index'])->name('home');
    
    Route::get('/cart', function () {
        return view('user_view.cart');
    })->name('cart');
    
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
    
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