<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EnrollmentController extends Controller
{
    /**
     * Show the enrollment page for a specific course.
     */
    public function index($id)
    {
        $course = Course::with('category')->findOrFail($id);
        $user = auth()->user();

        // If authenticated user already has a student profile, jump directly to payment
        if ($user && $user->studentProfile) {
            return redirect()->route('payment.qr', $course->id);
        }

        return view('course.enroll.index', compact('course'));
    }

    /**
     * Create user account, store student profile, and redirect to QR payment.
     */
    public function store(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $user = auth()->user();

        // If authenticated user already has a profile, redirect directly to payment
        if ($user && $user->studentProfile) {
            return redirect()->route('payment.qr', $course->id);
        }

        // 1. Validate Form Inputs (including 4-part NRC selection)
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|string|email|max:255|unique:users,email,' . ($user->id ?? 'NULL'),
            'password'          => $user ? 'nullable' : ['required', Password::defaults()],
            'phone'             => 'required|string|max:20',
            'gender'            => 'required|in:male,female,other',
            'membership_status' => 'required|in:member,non-member',
            'nrc_state'         => 'required|integer|between:1,14',
            'nrc_district'      => 'required|string|max:50',
            'nrc_type'          => 'required|in:(N),(P),(E),(NRA)',
            'nrc_number'        => 'required|digits:6',
            'company'           => 'required|string|max:255',
            'job_title'         => 'required|string|max:255',
            'passport_photo'    => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Concatenate NRC parts into full string (e.g. 12/YAGANA(N)123456)
        $fullNrc = $validated['nrc_state'] . '/' . $validated['nrc_district'] . $validated['nrc_type'] . $validated['nrc_number'];

        return DB::transaction(function () use ($request, $validated, $fullNrc, $course, $user) {
            // 3. Register and log in user if guest
            if (!$user) {
                $user = User::create([
                    'name'     => $validated['name'],
                    'email'    => $validated['email'],
                    'phone'    => $validated['phone'],
                    'password' => Hash::make($validated['password']),
                ]);

                Auth::login($user);
            }

            // 4. Upload Passport Photo
            $photoPath = $request->file('passport_photo')->store('passports', 'public');

            // 5. Save Student Profile
            StudentProfile::create([
                'user_id'           => $user->id,
                'phone'             => $validated['phone'],
                'gender'            => $validated['gender'],
                'membership_status' => $validated['membership_status'],
                'nrc_number'        => $fullNrc,
                'company'           => $validated['company'],
                'job_title'         => $validated['job_title'],
                'passport_photo'    => $photoPath,
            ]);

            // 6. Redirect to Payment
            return redirect()->route('payment.qr', $course->id)
                ->with('success', 'Account created and profile saved! Please complete your payment for ' . $course->title . '.');
        });
    }
}