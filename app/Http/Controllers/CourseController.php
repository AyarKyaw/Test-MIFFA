<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use App\Models\Course;
use App\Models\Lesson;
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

    public function myCourses()
    {
        $user = auth()->user();

        // Fetch user's enrolled courses with their categories
        $courses = $user ? $user->courses()->with('category')->latest()->get() : collect();

        return view('course.my-courses', compact('courses'));
    }

    public function classroom(Course $course, Lesson $lesson = null)
    {
        // Authorization check: ensure user has enrolled
        $user = auth()->user();
        if (!$user || !$user->courses->contains($course->id)) {
            return redirect()->route('courses.show', $course->id)->with('error', 'Please enroll to access class materials.');
        }

        // Load course lessons
        $course->load('lessons');

        // Default to first lesson if none selected
        $currentLesson = $lesson ?? $course->lessons->first();

        // Load quiz questions and multiple-choice options if active lesson exists
        if ($currentLesson) {
            $currentLesson->load('questions.options');
        }

        return view('course.classroom', compact('course', 'currentLesson'));
    }

    public function submitQuiz(Request $request, Course $course, Lesson $lesson)
{
    $lesson->load('questions.options');

    $questionId = $request->input('question_id');
    $submittedAnswer = $request->input('answer');
    
    $question = $lesson->questions->firstWhere('id', $questionId);

    if (!$question) {
        return response()->json(['error' => 'Question not found.'], 404);
    }

    // 1. Evaluate single answer
    $isCorrect = false;
    $correctOption = $question->options->firstWhere('is_correct', true);

    if ($question->type === 'multiple_choice') {
        if ($correctOption && (int)$submittedAnswer === $correctOption->id) {
            $isCorrect = true;
        }
    } elseif ($question->type === 'boolean') {
        if ($correctOption && (string)$submittedAnswer === (string)$correctOption->is_correct) {
            $isCorrect = true;
        }
    }

    // 2. Track results in session
    $sessionKey = "quiz_progress_{$lesson->id}";
    $progress = session()->get($sessionKey, [
        'correct_count' => 0,
        'results' => [],
    ]);

    $progress['results'][$question->id] = [
        'submitted' => $submittedAnswer,
        'is_correct' => $isCorrect,
    ];

    $progress['correct_count'] = count(array_filter($progress['results'], fn($item) => $item['is_correct']));
    session()->put($sessionKey, $progress);

    $totalQuestions = $lesson->questions->count();
    $currentStepIndex = (int) $request->input('step_index', 0);
    $isCompleted = ($currentStepIndex + 1) >= $totalQuestions;

    $summary = null;
    if ($isCompleted) {
        $percentage = $totalQuestions > 0 ? round(($progress['correct_count'] / $totalQuestions) * 100) : 0;
        $summary = [
            'correct_count'   => $progress['correct_count'],
            'total_questions' => $totalQuestions,
            'score_percentage'=> $percentage,
            'passed'          => $percentage >= 70,
        ];
        session()->forget($sessionKey);
    }

    // Return AJAX response
    return response()->json([
        'is_correct'   => $isCorrect,
        'is_completed' => $isCompleted,
        'summary'      => $summary,
    ]);
}
}