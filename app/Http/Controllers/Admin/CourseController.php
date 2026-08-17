<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Display a paginated list of courses.
     */
    public function index(Request $request): View
    {
        $query = Course::with('category');

        if ($request->get('filter') === 'latest') {
            $query->latest();
        } else {
            $query->latest();
        }

        $courses = $query->paginate(10)->withQueryString();

        return view('dashboard.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create(): View
    {
        $categories = CourseCategory::orderBy('name')->get();

        return view('dashboard.courses.create', compact('categories'));
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'              => 'required|string|max:255',
            'code'               => 'required|string|max:50|unique:courses,code',
            'course_category_id' => 'required|exists:course_categories,id',
            'price'              => 'nullable|numeric|min:0',
            'hour'               => 'required|integer|min:1',
            'desc'               => 'nullable|string',
            'image'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Map form input 'course_category_id' to database column 'category_id'
        $validated['category_id'] = $validated['course_category_id'];
        unset($validated['course_category_id']);

        $validated['price'] = $validated['price'] ?? 0;

        // Handle Image Upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('courses', 'public');
        }

        Course::create($validated);

        return redirect()->route('admin.course.index')
                         ->with('success', 'Course created successfully!');
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course): View
    {
        $categories = CourseCategory::orderBy('name')->get();

        return view('dashboard.courses.edit', compact('course', 'categories'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'title'              => 'required|string|max:255',
            'code'               => 'required|string|max:50|unique:courses,code,' . $course->id,
            'course_category_id' => 'required|exists:course_categories,id',
            'price'              => 'nullable|numeric|min:0',
            'hour'               => 'required|integer|min:1',
            'desc'               => 'nullable|string',
            'image'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Map form input 'course_category_id' to database column 'category_id'
        $validated['category_id'] = $validated['course_category_id'];
        unset($validated['course_category_id']);

        $validated['price'] = $validated['price'] ?? 0;

        // Handle Image Upload
        if ($request->hasFile('image')) {
            // Delete old image if present
            if ($course->image && Storage::disk('public')->exists($course->image)) {
                Storage::disk('public')->delete($course->image);
            }

            // Store new image
            $validated['image'] = $request->file('image')->store('courses', 'public');
        }

        $course->update($validated);

        return redirect()->route('admin.course.index')
                         ->with('success', 'Course updated successfully!');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course): RedirectResponse
    {
        // Delete course image from storage when deleting record
        if ($course->image && Storage::disk('public')->exists($course->image)) {
            Storage::disk('public')->delete($course->image);
        }

        $course->delete();

        return redirect()->route('admin.course.index')
                         ->with('success', 'Course deleted successfully!');
    }
}