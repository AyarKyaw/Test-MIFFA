<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;

// Course Category Controllers
use App\Http\Controllers\Admin\CourseCategoryController as AdminCourseCategoryController;
use App\Http\Controllers\CourseCategoryController as FrontendCourseCategoryController;

// Course Controllers
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\CourseController as FrontendCourseController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;

use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public / Frontend Routes
Route::get('/', function () {
    return view('index');
});

Route::get('/about', function () {
    return view('about-us');
});

// Frontend Courses & Categories
Route::get('/courses', [FrontendCourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{id}', [FrontendCourseController::class, 'show'])->name('courses.show');
Route::get('/course/categories', [FrontendCourseCategoryController::class, 'index'])->name('course-categories.index');

// Auth Routes
Route::get('/register', function () {
    return view('register');
})->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.perform');

Route::view('/login', 'login')->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/auth/google/one-tap', [AuthController::class, 'handleGoogleOneTap'])->name('auth.google.onetap');

// Admin / Dashboard Routes
Route::get('/dashboard', function () {
    return view('dashboard.index');
})->name('dashboard');

Route::resource('/dashboard/users', UserController::class);

// Admin Resource Routes for Categories and Courses
Route::resource('/dashboard/course-categories', AdminCourseCategoryController::class)
    ->names('admin.course-categories');
Route::resource('/dashboard/categories', AdminCategoryController::class)
    ->names('admin.categories');
Route::resource('/dashboard/courses', AdminCourseController::class)
    ->names('admin.course');

// Enrollment Routes
Route::get('/enroll/{course}', [EnrollmentController::class, 'index'])->name('enroll.index');
Route::post('/enroll/{id}', [EnrollmentController::class, 'store'])->name('enroll.store');

// Payment Routes
Route::get('/payment/qr/{course}', [PaymentController::class, 'showQr'])->name('payment.qr');
Route::post('/payment/confirm/{course}', [PaymentController::class, 'confirmPayment'])->name('payment.confirm');

Route::get('/my-courses', [FrontendCourseController::class, 'myCourses'])->name('courses.my');
Route::get('/courses/{course}/learn/{lesson?}', [FrontendCourseController::class, 'classroom'])->name('courses.learn');
Route::post('/courses/{course}/lessons/{lesson}/submit', [FrontendCourseController::class, 'submitQuiz'])->name('courses.lessons.submit');

// Lesson Progress / Completion Route
Route::post('/lessons/{lesson}/complete', [FrontendCourseController::class, 'markComplete'])
    ->middleware('auth')
    ->name('lessons.complete');