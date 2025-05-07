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

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

//Route payment and cart
Route::middleware(['auth'])->group(function () {
    // Route::get('/cart', [CartController::class, 'index'])->name('cart');
    // Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment');
    Route::post('/payment-methods', [PaymentController::class, 'store'])->name('payment-methods.store');
    // Route::get('/address/create', [AddressController::class, 'create'])->name('address.create');

});

Route::middleware('auth')->prefix('user')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
});

// User Routes
Route::middleware('auth')->prefix('user')->group(function () {
    Route::get('/payment', function () {
        return view('user_view.payment');
    })->name('payment');
    
    Route::get('/profile', function () {
        return view('user_view.profile');
    })->name('profile');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/balance', function () {
        return view('user_view.balance');
    })->name('balance');

    Route::get('/order', function () {
        return view('user_view.order');
    })->name('order');

    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/payment', [CartController::class, 'payment'])->name('payment');
    Route::post('/checkout', [CartController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
});
    //Page Awal User Masuk!!
    Route::get('/home', [ProductController::class, 'index'])->name('home');
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
    Route::get('/live-search', [ProductController::class, 'liveSearch']);


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

    // Add this inside the merchant group
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

// View-only routes (if still needed for legacy links)
Route::get('/merchant', function () {
    return redirect()->route('merchant.dashboard');
});

Route::get('/detailMerchant', function () {
    return redirect()->route('merchant.detail');
});

Route::get('/openMerchant', function () {
    return view('openMerchant'); // This remains in root views
})->name('openMerchant.legacy');

Route::post('/payment/confirm', [PaymentController::class, 'confirmPayment'])->name('payment.confirm');

Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');


// Route::get('/merchant', function () {
//     return view('merchant_view.merchant'); // Updated path
// })->name('merchant.legacy');

// Route::get('/detailMerchant', function () {
//     return view('merchant_view.detailMerchant'); // Updated path
// })->name('detailMerchant.legacy');

