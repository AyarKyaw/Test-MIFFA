<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{

    const QUIZ_QUESTION_LIMIT = 6;

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
        $user = auth()->user();
        if (!$user || !$user->courses->contains($course->id)) {
            return redirect()->route('courses.show', $course->id)->with('error', 'Please enroll to access class materials.');
        }

        $currentLesson = $lesson ?? $course->lessons()->first();
        $questions = collect();

        if ($currentLesson && $currentLesson->type === 'quiz') {
            $sessionKey = "quiz_questions_{$user->id}_{$currentLesson->id}";

            // Store selected question IDs in session to maintain consistency across step refills/refreshes
            if (!session()->has($sessionKey)) {
                $selectedIds = \App\Models\Question::where('lesson_id', $currentLesson->id)
                    ->inRandomOrder()
                    ->limit(self::QUIZ_QUESTION_LIMIT)
                    ->pluck('id')
                    ->toArray();

                session()->put($sessionKey, $selectedIds);
            }

            $questionIds = session()->get($sessionKey, []);

            // Load questions in the cached random order with randomized options
            $questions = \App\Models\Question::whereIn('id', $questionIds)
                ->with(['options' => fn($q) => $q->inRandomOrder()])
                ->get()
                ->sortBy(fn($q) => array_search($q->id, $questionIds))
                ->values();
        }

        return view('course.classroom', compact('course', 'currentLesson', 'questions'));
    }

    public function submitQuiz(Request $request, Course $course, Lesson $lesson)
{
    $user = auth()->user();
    if (!$user) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }

    $lesson->load('questions.options');
    $questionId = $request->input('question_id');
    $submittedAnswer = $request->input('answer');
    
    $question = $lesson->questions->firstWhere('id', $questionId);
    if (!$question) {
        return response()->json(['error' => 'Question not found.'], 404);
    }

    // 1. Evaluate answer
    $isCorrect = false;
    if ($question->type === 'multiple_choice') {
        $correctOption = $question->options->firstWhere('is_correct', true);
        if ($correctOption && (int)$submittedAnswer === $correctOption->id) {
            $isCorrect = true;
        }
    } elseif ($question->type === 'boolean') {
        $userBool = filter_var($submittedAnswer, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if (!is_null($question->is_correct)) {
            $isCorrect = ($userBool !== null && $userBool === (bool)$question->is_correct);
        } else {
            $correctOption = $question->options->firstWhere('is_correct', true);
            if ($correctOption) {
                $correctBool = filter_var($correctOption->option_text, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) 
                    ?? (bool)$correctOption->is_correct;
                $isCorrect = ($userBool !== null && $userBool === $correctBool);
            }
        }
    }

    // 2. Track progress against session subset
    $progressKey = "quiz_progress_{$user->id}_{$lesson->id}";
    $questionsKey = "quiz_questions_{$user->id}_{$lesson->id}";
    
    $progress = session()->get($progressKey, ['correct_count' => 0, 'results' => []]);
    $sessionQuestionIds = session()->get($questionsKey, []);

    // Active target length: total session question count (defaults to QUIZ_QUESTION_LIMIT if set)
    $targetQuestionCount = count($sessionQuestionIds) > 0 ? count($sessionQuestionIds) : self::QUIZ_QUESTION_LIMIT;

    $progress['results'][$question->id] = [
        'submitted' => $submittedAnswer,
        'is_correct' => $isCorrect,
    ];

    $progress['correct_count'] = count(array_filter($progress['results'], fn($item) => $item['is_correct']));
    session()->put($progressKey, $progress);

    $currentStepIndex = (int) $request->input('step_index', 0);
    $isCompleted = ($currentStepIndex + 1) >= $targetQuestionCount;

    $summary = null;
    if ($isCompleted) {
        $percentage = $targetQuestionCount > 0 
            ? round(($progress['correct_count'] / $targetQuestionCount) * 100) 
            : 0;

        $tier = 'low';
        if ($percentage >= 80) {
            $tier = 'mastered';
        } elseif ($percentage >= 50) {
            $tier = 'mid';
        }

        $isCompletedStatus = ($tier === 'mastered');

        $summary = [
            'correct_count'   => $progress['correct_count'],
            'total_questions' => $targetQuestionCount,
            'score_percentage' => $percentage,
            'tier'            => $tier,
            'passed'          => $isCompletedStatus,
        ];

        // 3. Preserve high score on completion
        $existingPivot = $user->lessons()->where('lesson_id', $lesson->id)->first()?->pivot;
        $finalScore = max($percentage, $existingPivot?->quiz_score ?? 0);
        $finalCompletedStatus = ($existingPivot?->is_completed ?? false) || $isCompletedStatus;

        $user->lessons()->syncWithoutDetaching([
            $lesson->id => [
                'course_id'        => $lesson->course_id,
                'is_completed'     => $finalCompletedStatus,
                'progress_percent' => 100,
                'quiz_score'       => $finalScore,
                'completed_at'     => $existingPivot?->completed_at ?? now(),
            ]
        ]);

        // Clean up session state for next attempt
        session()->forget([$progressKey, $questionsKey]);
    }

    return response()->json([
        'is_correct'   => $isCorrect,
        'is_completed' => $isCompleted,
        'summary'      => $summary,
    ]);
}

// In markComplete():
public function markComplete(Request $request, Lesson $lesson)
{
    $user = auth()->user();

    if (!$user) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }

    $user->lessons()->syncWithoutDetaching([
        $lesson->id => [
            'course_id'        => $lesson->course_id,
            'is_completed'     => true,
            'progress_percent' => 100,
            'completed_at'     => now(),
        ]
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Lesson marked as completed!'
    ]);
}
}