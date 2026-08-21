<?php

use App\Models\User;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\StudentController;

// Course Category Controllers
use App\Http\Controllers\Admin\CourseCategoryController as AdminCourseCategoryController;
use App\Http\Controllers\CourseCategoryController as FrontendCourseCategoryController;

// Course Controllers
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\CourseController as FrontendCourseController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;

use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\LessonController;

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

// Frontend Courses & Categories (Public browsing)
Route::get('/courses', [FrontendCourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{id}', [FrontendCourseController::class, 'show'])->name('courses.show');
Route::get('/course/categories', [FrontendCourseCategoryController::class, 'index'])->name('course-categories.index');

// Guest / Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', function () {
        return view('register');
    })->name('register');
    
    Route::post('/register', [AuthController::class, 'register'])->name('register.perform');

    Route::view('/login', 'login')->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
});

// Auth Routes (Public/Shared endpoints)
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::post('/auth/google/one-tap', [AuthController::class, 'handleGoogleOneTap'])->name('auth.google.onetap');

/*
|--------------------------------------------------------------------------
| Email Verification Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Notice page shown to unverified users
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    // Email link callback
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard')->with('success', 'Your email address has been verified!');
    })->middleware('signed')->name('verification.verify');

    // Resend verification link
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Verification link sent!');
    })->middleware('throttle:6,1')->name('verification.send');
});


// Enrollment Routes
    Route::get('/enroll/{course}', [EnrollmentController::class, 'index'])->name('enroll.index');
    Route::post('/enroll/{id}', [EnrollmentController::class, 'store'])->name('enroll.store');

/*
|--------------------------------------------------------------------------
| Protected Routes (Requires Auth & Email Verification)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Admin / Dashboard Routes
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::resource('/dashboard/students', StudentController::class)->names('admin.students');

    // Admin Lessons Routes
    Route::resource('/dashboard/lessons', LessonController::class)->names('admin.lessons');
    Route::get('/dashboard/lessons/{lesson}/questions', [LessonController::class, 'getQuestions'])->name('lessons.questions');

    // Admin Resource Routes for Categories and Courses
    Route::resource('/dashboard/course-categories', AdminCourseCategoryController::class)
        ->names('admin.course-categories');
    Route::resource('/dashboard/categories', AdminCategoryController::class)
        ->names('admin.categories');
    Route::resource('/dashboard/courses', AdminCourseController::class)
        ->names('admin.courses');

    // Payment Routes
    Route::get('/payment/qr/{course}', [PaymentController::class, 'showQr'])->name('payment.qr');
    Route::post('/payment/confirm/{course}', [PaymentController::class, 'confirmPayment'])->name('payment.confirm');

    // Learning / Classroom Routes
    Route::get('/my-courses', [FrontendCourseController::class, 'myCourses'])->name('courses.my');
    Route::get('/courses/{course}/learn/{lesson?}', [FrontendCourseController::class, 'classroom'])->name('courses.learn');
    Route::post('/courses/{course}/lessons/{lesson}/submit', [FrontendCourseController::class, 'submitQuiz'])->name('courses.lessons.submit');

    // Lesson Progress / Completion Route
    Route::post('/lessons/{lesson}/complete', [FrontendCourseController::class, 'markComplete'])->name('lessons.complete');
});

// Route::get('/account/confirm/{payload}', function (Request $request, $payload) {
//     if (! $request->hasValidSignature()) {
//         Log::warning('ACCOUNT CONFIRM: Invalid or expired signature clicked.');
//         abort(403, 'This confirmation link is invalid or has expired.');
//     }

//     try {
//         $data = decrypt($payload);
//         Log::info('ACCOUNT CONFIRM: Decrypted payload successfully', ['email' => $data['email'] ?? null]);

//         // 1. Create User in DB if not created yet
//         $user = User::where('email', $data['email'])->first();
//         if (! $user) {
//             $user = User::create([
//                 'name'              => $data['name'],
//                 'email'             => $data['email'],
//                 'password'          => $data['password'],
//                 'google_id'         => $data['google_id'] ?? null,
//                 'email_verified_at' => now(),
//             ]);
//             Log::info('ACCOUNT CONFIRM: New user created in DB', ['user_id' => $user->id]);
//         } else {
//             Log::info('ACCOUNT CONFIRM: User already existed in DB', ['user_id' => $user->id]);
//         }

//         // 2. Set Cache Flag for the polling tab
//         $cacheKey = 'confirmed_email_' . md5($data['email']);
//         Cache::put($cacheKey, $user->id, now()->addMinutes(10));
//         Log::info('ACCOUNT CONFIRM: Cache flag written', ['cache_key' => $cacheKey, 'user_id' => $user->id]);

//         // 3. Authenticate current browser session
//         Auth::login($user, true);
//         $request->session()->regenerate();
//         session()->forget('pending_registration');

//         return redirect('/')->with('success', 'Account confirmed! Welcome to MIFFA.');

//     } catch (\Exception $e) {
//         Log::error('ACCOUNT CONFIRM ERROR: ' . $e->getMessage());
//         return redirect()->route('register')->withErrors(['email' => 'Confirmation failed.']);
//     }
// })->name('account.confirm');

// Route::post('/account/check-status', [AuthController::class, 'checkVerificationStatus'])->name('account.check-status');

// // Route to resend link from verify-email page
// Route::post('/account/resend-confirmation', [AuthController::class, 'resendConfirmation'])->name('account.resend');