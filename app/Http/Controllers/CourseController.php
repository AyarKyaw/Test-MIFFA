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
        // Fetch questions and options in random order directly on every load
        $questions = \App\Models\Question::where('lesson_id', $currentLesson->id)
            ->inRandomOrder()
            ->with(['options' => function ($query) {
                $query->inRandomOrder();
            }])
            ->get();
    }

    return view('course.classroom', compact('course', 'currentLesson', 'questions'));
}

    public function submitQuiz(Request $request, Course $course, Lesson $lesson)
{
    $user = auth()->user();

    // 1. Debug: Log incoming request & auth status
    Log::info('Quiz Submission Triggered', [
        'user_id'     => $user ? $user->id : 'NOT_LOGGED_IN',
        'lesson_id'   => $lesson->id,
        'course_id'   => $lesson->course_id,
        'question_id' => $request->input('question_id'),
        'step_index'  => $request->input('step_index'),
    ]);

    $lesson->load('questions.options');
    $questionId = $request->input('question_id');
    $submittedAnswer = $request->input('answer');
    
    $question = $lesson->questions->firstWhere('id', $questionId);

    if (!$question) {
        Log::error('Quiz Error: Question not found ID ' . $questionId);
        return response()->json(['error' => 'Question not found.'], 404);
    }

    // 2. Evaluate answer
    $isCorrect = false;

    if ($question->type === 'multiple_choice') {
        $correctOption = $question->options->firstWhere('is_correct', true);
        if ($correctOption && (int)$submittedAnswer === $correctOption->id) {
            $isCorrect = true;
        }
    } elseif ($question->type === 'boolean') {
        // Parse submitted answer ("true", "1", 1, true) into a clean boolean
        $userBool = filter_var($submittedAnswer, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        // Priority 1: Check $question->is_correct from the questions table
        if (!is_null($question->is_correct)) {
            $correctBool = (bool) $question->is_correct;
            $isCorrect = ($userBool !== null && $userBool === $correctBool);
        } 
        // Priority 2: Fallback to options table if options exist
        else {
            $correctOption = $question->options->firstWhere('is_correct', true);
            if ($correctOption) {
                // If option_text is "true"/"false" or is_correct boolean flag is set
                $correctBool = filter_var($correctOption->option_text, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) 
                    ?? (bool)$correctOption->is_correct;
                $isCorrect = ($userBool !== null && $userBool === $correctBool);
            }
        }
    }

    // 3. Track progress in session
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
        
        // Khan Academy Status Tiers:
        // - low: < 50%
        // - mid: 50% - 79%
        // - mastered: >= 80%
        $tier = 'low';
        if ($percentage >= 80) {
            $tier = 'mastered';
        } elseif ($percentage >= 50) {
            $tier = 'mid';
        }

        $isCompletedStatus = ($tier === 'mastered');

        $summary = [
            'correct_count'   => $progress['correct_count'],
            'total_questions' => $totalQuestions,
            'score_percentage'=> $percentage,
            'tier'            => $tier, // 'low', 'mid', or 'mastered'
            'passed'          => $isCompletedStatus,
        ];

        Log::info('Quiz Finalizing', [
            'total_questions' => $totalQuestions,
            'correct_count'   => $progress['correct_count'],
            'percentage'      => $percentage,
            'tier'            => $tier,
            'has_user'        => !is_null($user),
        ]);

        // Always save score on attempt completion if user is logged in
        if ($user) {
            try {
                $user->lessons()->syncWithoutDetaching([
                    $lesson->id => [
                        'course_id'        => $lesson->course_id,
                        'is_completed'     => $isCompletedStatus,
                        'progress_percent' => 100,
                        'quiz_score'       => $percentage,
                        'completed_at'     => now(),
                    ]
                ]);
                Log::info("Successfully synced lesson {$lesson->id} score ({$percentage}%) to user {$user->id}");
            } catch (\Exception $e) {
                Log::error("Failed syncing lesson to pivot table: " . $e->getMessage());
            }
        } else {
            Log::warning('Quiz score not saved: User is null / not logged in');
        }

        session()->forget($sessionKey);
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