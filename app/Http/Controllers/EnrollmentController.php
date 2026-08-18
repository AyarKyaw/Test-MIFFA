<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\StudentProfile;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    /**
     * Show the enrollment page for a specific course.
     */
    public function index($id)
    {
        $course = Course::with('category')->findOrFail($id);
        $user = auth()->user();

        // If user already has a profile, skip profile form and go straight to QR payment
        if ($user && $user->studentProfile) {
            return redirect()->route('payment.qr', $course->id);
        }

        return view('course.enroll.index', compact('course'));
    }

    /**
     * Store student profile and redirect to QR Code payment page.
     */
    public function store(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $user = auth()->user();

        // If user submits form but already has a profile, go straight to QR payment
        if ($user && $user->studentProfile) {
            return redirect()->route('payment.qr', $course->id);
        }

        // 1. Validate Form Inputs
        $validated = $request->validate([
            'phone'             => 'required|string|max:20',
            'gender'            => 'required|in:male,female,other',
            'membership_status' => 'required|in:member,non-member',
            'nrc_number'        => 'required|string|max:100',
            'company'           => 'required|string|max:255',
            'job_title'         => 'required|string|max:255',
            'passport_photo'    => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Upload Passport Photo
        $photoPath = $request->file('passport_photo')->store('passports', 'public');

        // 3. Save One-Time Student Profile
        StudentProfile::create([
            'user_id'           => $user->id,
            'phone'             => $validated['phone'],
            'gender'            => $validated['gender'],
            'membership_status' => $validated['membership_status'],
            'nrc_number'        => $validated['nrc_number'],
            'company'           => $validated['company'],
            'job_title'         => $validated['job_title'],
            'passport_photo'    => $photoPath,
        ]);

        // 4. Redirect to QR Code Payment Page
        return redirect()->route('payment.qr', $course->id)
            ->with('success', 'Profile completed! Please scan the QR code to finish purchasing ' . $course->title . '.');
    }
}