<?php

use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    return view('landingPage');
});

// Authentication Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// User Routes
Route::prefix('user')->group(function () {
    Route::get('/home', function () {
        return view('user_view.home');
    })->name('home');
    
    Route::get('/cart', function () {
        return view('user_view.cart');
    })->name('cart');
    
    Route::get('/product', function () {
        return view('user_view.product');
    })->name('product');
    
    Route::get('/payment', function () {
        return view('user_view.payment');
    })->name('payment');
});

// Merchant Routes
Route::prefix('merchant')->group(function () {
    // You can add merchant-specific routes here later
    Route::get('/dashboard', function () {
        return view('merchant_view.dashboard');
    })->name('merchant.dashboard');
});

Route::get('/profile', function () {
    return view('profile');
})->name('profile');

Route::get('/merchant', function () {
    return view('merchant');
})->name('merchant');

Route::get('/openMerchant', function () {
    return view('openMerchant');
})->name('openMerchant');