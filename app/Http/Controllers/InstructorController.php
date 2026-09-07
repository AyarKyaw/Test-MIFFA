<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstructorController extends Controller
{
    /**
     * Display a listing of all instructors for users/students.
     */
    public function index(Request $request)
    {
        $query = Instructor::withCount([
            'courses',
            // Counts unique students enrolled across all courses assigned via course_instructor
            'courses as students_count' => function ($query) {
                $query->join('course_user', 'courses.id', '=', 'course_user.course_id')
                      ->select(DB::raw('count(distinct(course_user.user_id))'));
            }
        ]);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('bio', 'like', "%{$search}%");
            });
        }

        $instructors = $query->paginate(9)->withQueryString();

        return view('instructors.index', compact('instructors'));
    }

    /**
     * Display the specified instructor's profile.
     */
    public function show($id)
    {
        $instructor = Instructor::with(['courses'])->findOrFail($id);

        // Fetch distinct enrolled users filtering through the course_instructor pivot
        $instructor->students_count = User::whereHas('courses.instructors', function ($query) use ($id) {
            $query->where('instructors.id', $id);
        })->distinct()->count('users.id');

        return view('instructors.show', compact('instructor'));
    }
}