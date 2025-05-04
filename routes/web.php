<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landingPage');
});

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/product', function () {
    return view('product');
})->name('product');

Route::get('/cart', function () {
    return view('cart');
})->name('cart');