<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('index');
});

Route::get('/about', function () {
    return view('about-us');
});

Route::get('/course', function () {
    return view('course');
});

// Register Routes
Route::get('/register', function () {
    return view('register');
})->name('register');

Route::post('/register', [AuthController::class, 'register'])->name('register.perform');

// Login Routes
Route::view('/login', 'login')->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');

// Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/auth/google/one-tap', [AuthController::class, 'handleGoogleOneTap'])->name('auth.google.onetap');