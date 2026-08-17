<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    /**
     * Show the enrollment page for a specific course.
     */
    public function index($id)
    {
        $course = Course::with('category')->findOrFail($id);

        return view('course.enroll.index', compact('course'));
    }
    
    public function store(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        // Option A: If storing in an Enrollment model
        // \App\Models\Enrollment::create([
        //     'course_id' => $course->id,
        //     'user_id'   => auth()->id(), // optional if logged in
        //     'name'      => $validated['name'],
        //     'email'     => $validated['email'],
        //     'phone'     => $validated['phone'],
        // ]);

        return redirect()->route('admin.course.index')
            ->with('success', 'Successfully enrolled in ' . $course->title . '!');
    }
}
