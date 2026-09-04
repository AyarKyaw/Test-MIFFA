<?php

use App\Models\User;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\DashboardController;
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
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\InstructorController;
use App\Http\Controllers\Admin\AdminManagementController;

use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public / Frontend Routes
Route::get('/', HomeController::class)->name('home');

Route::get('/about', function () {
    return view('about-us');
});

// Frontend Courses & Categories (Public browsing)
Route::get('/courses', [FrontendCourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{id}', [FrontendCourseController::class, 'show'])->name('courses.show');
Route::get('/course/categories', [FrontendCourseCategoryController::class, 'index'])->name('course-categories.index');

Route::post('/google-one-tap', [AuthController::class, 'handleGoogleOneTap'])->name('google.onetap');

// Guest / Authentication Routes (Students / Frontend Users)
Route::middleware('guest')->group(function () {
    Route::get('/register', function () {
        return view('register');
    })->name('register');
    
    Route::post('/register', [AuthController::class, 'register'])->name('register.perform');

    Route::view('/login', 'login')->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
});

// Admin Authentication Guest Routes
Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.perform');
});

// Auth Routes (Public/Shared endpoints)
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::post('/auth/google/one-tap', [AuthController::class, 'handleGoogleOneTap'])->name('auth.google.onetap');
Route::get('/account/confirm/{payload}', [AuthController::class, 'confirmAccount'])
    ->name('account.confirm')
    ->middleware('signed');

Route::post('/account/resend', [AuthController::class, 'resendConfirmation'])
    ->name('account.resend')
    ->middleware('throttle:3,1');

Route::get('/account/check-status', [AuthController::class, 'checkVerificationStatus'])
->name('account.check-status');
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

// Public Enrollment Routes
Route::get('/enroll/{course}', [EnrollmentController::class, 'index'])->name('enroll.index');
Route::post('/enroll/{id}', [EnrollmentController::class, 'store'])->name('enroll.store');

/*
|--------------------------------------------------------------------------
| Student / Classroom Routes (Requires Auth & Email Verification)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // Payment Routes
    Route::get('/payment/qr/{course}', [PaymentController::class, 'showQr'])->name('payment.qr');
    Route::post('/payment/confirm/{course}', [PaymentController::class, 'confirmPayment'])->name('payment.confirm');
    Route::get('/student-dashboard', [DashboardController::class, 'index'])->name('student.dashboard');
    // Learning & Classroom Routes
    Route::get('/my-courses', [FrontendCourseController::class, 'myCourses'])->name('courses.my');
    Route::get('/courses/{course}/learn/{lesson?}', [FrontendCourseController::class, 'classroom'])->name('courses.learn');
    Route::post('/courses/{course}/lessons/{lesson}/submit', [FrontendCourseController::class, 'submitQuiz'])->name('courses.lessons.submit');
    Route::get('/courses/{course}/units', [FrontendCourseController::class, 'units'])->name('courses.units');

    Route::post('/courses/{course}/lessons/{lesson}/homework', [FrontendCourseController::class, 'submitHomework'])->name('courses.homework.submit');

    // Lesson Progress / Completion Route
    Route::post('/lessons/{lesson}/complete', [FrontendCourseController::class, 'markComplete'])->name('lessons.complete');
});

/*
|--------------------------------------------------------------------------
| Protected Admin Routes (Requires Admin Verification, Prefixed with /dashboard)
|--------------------------------------------------------------------------
*/
Route::middleware('admin')->prefix('dashboard')->group(function () {

    // Admin Dashboard Home (/dashboard)
    Route::get('/', function () {
        return view('dashboard.index');
    })->name('dashboard');

    // Admin Logout (/dashboard/logout)
    Route::post('/logout', [AuthController::class, 'adminLogout'])->name('admin.logout');

    // Admin Resource Management (/dashboard/students, /dashboard/instructors, etc.)
    Route::resource('students', StudentController::class)->names('admin.students');
    Route::resource('instructors', InstructorController::class)->names('admin.instructors');
    Route::resource('admins', AdminManagementController::class)->names('admin.admins');
    
    // Admin Courses & Categories
    Route::resource('course-categories', AdminCourseCategoryController::class)->names('admin.course-categories');
    Route::resource('categories', AdminCategoryController::class)->names('admin.categories');
    Route::resource('courses', AdminCourseController::class)->names('admin.courses');
    
    Route::get('courses/{course}/students', [AdminCourseController::class, 'students'])->name('admin.courses.students');
    Route::delete('courses/{course}/students/{student}', [AdminCourseController::class, 'removeStudent'])->name('admin.courses.students.remove');

    // Curriculum Management (Lessons, Units, Sections)
    Route::resource('lessons', LessonController::class)->names('admin.lessons');
    Route::get('lessons/{lesson}/questions', [LessonController::class, 'getQuestions'])->name('lessons.questions');
    Route::resource('units', UnitController::class)->names('admin.units');
    Route::resource('sections', SectionController::class)->names('admin.sections');
    Route::get('/lessons/{lesson}/submissions', [LessonController::class, 'submissions'])->name('admin.lessons.submissions');
    Route::put('/lesson-user/{pivotId}/update', [LessonController::class, 'updateSubmission'])->name('admin.lesson-user.update');
});