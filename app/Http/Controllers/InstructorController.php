<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    /**
     * Display a listing of all instructors for users/students.
     */
    public function index()
    {
        // Fetch active/published instructors with pagination
        $instructors = Instructor::latest()->paginate(9);

        return view('instructors.index', compact('instructors'));
    }

    /**
     * Display the specified instructor's profile.
     */
    public function show($id)
    {
        // Eager-load courses (if relationship exists) to prevent N+1 queries
        $instructor = Instructor::with(['courses' => function ($query) {
            // Optional: filter only published courses if you have a status column
            // $query->where('status', 'published');
        }])->findOrFail($id);

        return view('instructors.show', compact('instructor'));
    }
}