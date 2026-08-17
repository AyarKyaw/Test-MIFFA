<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $selectedCategorySlug = $request->query('category');

        // Query top-tier CourseCategories with their middle-tier categories and courses
        $categoriesQuery = CourseCategory::with('categories.courses');

        // If a specific ?category=... slug is passed in the URL, filter to ONLY that CourseCategory
        if ($selectedCategorySlug) {
            $categoriesQuery->where('slug', $selectedCategorySlug);
        }

        $categories = $categoriesQuery->get();

        return view('course.index', [
            'courseCategory' => $categories->first(), // The current main CourseCategory
            'categories'     => $categories->pluck('categories')->flatten(), // The sub-categories inside it
        ]);
    }

    public function show($id)
    {
        // Eager load category to prevent N+1 queries
        $course = Course::with('category')->findOrFail($id);

        return view('course.single', compact('course'));
    }
}