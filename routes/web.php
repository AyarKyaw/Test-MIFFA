<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
// Import both controllers with unique aliases
use App\Http\Controllers\Admin\CourseCategoryController as AdminCourseCategoryController;
use App\Http\Controllers\CourseCategoryController as FrontendCourseCategoryController;

Route::get('/', function () {
    return view('index');
});

Route::get('/about', function () {
    return view('about-us');
});

Route::get('/course', function () {
    return view('course');
});

Route::get('/course-single', function () {
    return view('course-single');
});

// FIXED: Points to Frontend Controller's index method instead of Admin show
Route::get('/course/categories', [FrontendCourseCategoryController::class, 'index'])->name('course-categories.index');

Route::get('/dashboard', function () {
    return view('dashboard.index');
})->name('dashboard');

Route::resource('/dashboard/users', UserController::class);

// Admin Resource Routes
Route::resource('/dashboard/course-categories', AdminCourseCategoryController::class)
    ->names('admin.course-categories');

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