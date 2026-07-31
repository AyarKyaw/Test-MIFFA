<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = CourseCategory::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->withCount('courses')
                            ->latest()
                            ->paginate(10)
                            ->withQueryString();

        return view('dashboard.course-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('dashboard.course-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:course_categories,name',
            'slug'        => 'nullable|string|max:255|unique:course_categories,slug',
            'description' => 'nullable|string',
        ]);

        CourseCategory::create([
            'name'        => $request->name,
            'slug'        => $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->name),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.course-categories.index')
                         ->with('success', 'Category created successfully!');
    }

    public function show(CourseCategory $courseCategory)
    {
        $courseCategory->loadCount('courses');
        return view('dashboard.course-categories.show', compact('courseCategory'));
    }

    public function edit(CourseCategory $courseCategory)
    {
        return view('dashboard.course-categories.edit', compact('courseCategory'));
    }

    public function update(Request $request, CourseCategory $courseCategory)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:course_categories,name,' . $courseCategory->id,
            'slug'        => 'nullable|string|max:255|unique:course_categories,slug,' . $courseCategory->id,
            'description' => 'nullable|string',
        ]);

        $courseCategory->update([
            'name'        => $request->name,
            'slug'        => $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->name),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.course-categories.index')
                         ->with('success', 'Category updated successfully!');
    }

    public function destroy(CourseCategory $courseCategory)
    {
        if ($courseCategory->courses()->exists()) {
            return redirect()->back()
                             ->with('error', 'Cannot delete category that has assigned courses.');
        }

        $courseCategory->delete();

        return redirect()->route('admin.course-categories.index')
                         ->with('success', 'Category deleted successfully!');
    }
}