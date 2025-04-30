<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landingPage');
});

Route::get('/home', function () {
    return view('home');
})->name('home');
