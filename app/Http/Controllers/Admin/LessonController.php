<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        $query = Lesson::with(['course', 'questions'])->orderBy('order', 'asc');

        // Filter by course if course_id is present in query parameters
        if ($request->has('course_id') && $request->course_id) {
            $query->where('course_id', $request->course_id);
        }

        $lessons = $query->get();
        $selectedCourse = $request->course_id ? Course::find($request->course_id) : null;

        return view('dashboard.lessons.index', compact('lessons', 'selectedCourse'));
    }

    public function create()
    {
        $courses = Course::orderBy('title')->get();
        return view('dashboard.lessons.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id'        => 'required|exists:courses,id',
            'title'            => 'required|string|max:255',
            'type'             => 'required|string|in:video,text,document,quiz',
            'video_url'        => 'nullable|url|max:255',
            'content'          => 'nullable|string',
            'document_file'    => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:20480',
            'order'            => 'nullable|integer',
            'questions'        => 'nullable|array',
            'questions.*.text' => 'required_if:type,quiz|string',
            'questions.*.type' => 'required_if:type,quiz|in:multiple_choice,boolean',
        ]);

        $validated['order'] = $validated['order'] ?? 1;

        // Handle document upload if present
        if ($request->hasFile('document_file')) {
            $validated['document_path'] = $request->file('document_file')->store('lessons/documents', 'public');
        }

        // 1. Create the base lesson
        $lesson = Lesson::create($validated);

        // 2. Handle quiz questions and options creation
        if ($lesson->type === 'quiz' && $request->has('questions')) {
            foreach ($request->input('questions') as $qData) {
                $questionText = $qData['text'] ?? ($qData['question_text'] ?? '');
                $questionType = $qData['type'] ?? 'multiple_choice';

                $question = $lesson->questions()->create([
                    'question_text' => $questionText,
                    'type'          => $questionType,
                    'is_correct'    => $questionType === 'boolean' ? (isset($qData['is_correct']) && $qData['is_correct'] == 1) : false,
                ]);

                // Save multiple choice options
                if ($questionType === 'multiple_choice' && isset($qData['options']) && is_array($qData['options'])) {
                    $selectedCorrectIndex = $qData['correct_option'] ?? 0;

                    foreach ($qData['options'] as $optIndex => $optData) {
                        $optText = is_array($optData) ? ($optData['text'] ?? '') : $optData;
                        if (!empty(trim($optText))) {
                            $question->options()->create([
                                'option_text' => $optText,
                                'is_correct'  => ($optIndex == $selectedCorrectIndex),
                            ]);
                        }
                    }
                }
            }
        }

        return redirect()->route('admin.lessons.index', ['course_id' => $lesson->course_id])
                        ->with('success', 'Lesson created successfully.');
    }

    public function getQuestions(Lesson $lesson)
    {
        $lesson->load(['questions.options']);

        return response()->json([
            'lesson_title' => $lesson->title,
            'questions' => $lesson->questions->map(function ($q) {
                return [
                    'id' => $q->id,
                    'text' => $q->question ?? $q->title ?? $q->question_text,
                    'type' => $q->type ?? ($q->options->isEmpty() ? 'boolean' : 'multiple_choice'),
                    'is_correct' => $q->is_correct, // Boolean answer field on questions table
                    'options' => $q->options->map(function ($opt) {
                        return [
                            'id' => $opt->id,
                            'text' => $opt->option_text ?? $opt->text,
                            'is_correct' => (bool) $opt->is_correct,
                        ];
                    }),
                ];
            }),
        ]);
    }

    public function edit(Lesson $lesson)
    {
        $lesson->load(['questions.options']);
        $courses = Course::orderBy('title')->get();

        return view('dashboard.lessons.edit', compact('lesson', 'courses'));
    }

    public function update(Request $request, Lesson $lesson)
{
    $validated = $request->validate([
        'course_id'        => 'required|exists:courses,id',
        'title'            => 'required|string|max:255',
        'type'             => 'required|in:video,document,text,quiz',
        'order'            => 'required|integer|min:0',
        'video_url'        => 'nullable|required_if:type,video|url|max:255',
        'content'          => 'nullable|string',
        'document_file'    => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:20480',
        'questions'        => 'nullable|array',
        'questions.*.text' => 'required_if:type,quiz|string',
        'questions.*.type' => 'required_if:type,quiz|in:multiple_choice,boolean',
    ]);

    DB::transaction(function () use ($request, $lesson, $validated) {
        // Handle lesson type content clearing/updating
        if ($validated['type'] === 'video') {
            $validated['video_url'] = $request->input('video_url');
        } else {
            // Nullify video_url if type switched away from video
            $validated['video_url'] = null; 
        }

        // Handle document file upload & replace old file
        if ($request->hasFile('document_file')) {
            if ($lesson->document_path && Storage::disk('public')->exists($lesson->document_path)) {
                Storage::disk('public')->delete($lesson->document_path);
            }
            $validated['document_path'] = $request->file('document_file')->store('lessons/documents', 'public');
        }

        // 1. Update basic lesson attributes
        $lesson->update([
            'course_id'     => $validated['course_id'],
            'title'         => $validated['title'],
            'type'          => $validated['type'],
            'order'         => $validated['order'],
            'video_url'     => $validated['video_url'],
            'content'       => $validated['content'] ?? null,
            'document_path' => $validated['document_path'] ?? $lesson->document_path,
        ]);

        // 2. Sync Quiz Questions and Options if lesson type is quiz
        if ($lesson->type === 'quiz') {
            $submittedQuestions = $request->input('questions', []);
            $keepQuestionIds = [];

            foreach ($submittedQuestions as $qData) {
                $questionText = $qData['text'] ?? ($qData['question_text'] ?? '');
                $questionType = $qData['type'] ?? 'multiple_choice';

                if (!empty($qData['id'])) {
                    $question = $lesson->questions()->find($qData['id']);
                    if ($question) {
                        $question->update([
                            'question_text' => $questionText,
                            'type'          => $questionType,
                        ]);
                    }
                } else {
                    $question = $lesson->questions()->create([
                        'question_text' => $questionText,
                        'type'          => $questionType,
                    ]);
                }

                if ($question) {
                    $keepQuestionIds[] = $question->id;

                    if ($questionType === 'boolean') {
                        $isCorrect = isset($qData['is_correct']) && $qData['is_correct'] == 1;
                        $question->update(['is_correct' => $isCorrect]);
                        $question->options()->delete();
                    } else {
                        $question->options()->delete();
                        
                        if (isset($qData['options']) && is_array($qData['options'])) {
                            $selectedCorrectIndex = $qData['correct_option'] ?? 0;

                            foreach ($qData['options'] as $optIndex => $optData) {
                                $optText = is_array($optData) ? ($optData['text'] ?? '') : $optData;
                                if (!empty(trim($optText))) {
                                    $question->options()->create([
                                        'option_text' => $optText,
                                        'is_correct'  => ($optIndex == $selectedCorrectIndex),
                                    ]);
                                }
                            }
                        }
                    }
                }
            }

            $lesson->questions()->whereNotIn('id', $keepQuestionIds)->delete();
        } else {
            // Delete questions if lesson type changed from quiz to something else
            $lesson->questions()->delete();
        }
    });

    return redirect()->route('admin.lessons.index', ['course_id' => $lesson->course_id])
                    ->with('success', 'Lesson updated successfully.');
}

    public function destroy(Lesson $lesson)
    {
        $courseId = $lesson->course_id;

        // Optional: Clean up associated document file if present
        if ($lesson->document_path && Storage::disk('public')->exists($lesson->document_path)) {
            Storage::disk('public')->delete($lesson->document_path);
        }

        // Delete lesson (associated questions/options will cascade if foreign keys are configured)
        $lesson->delete();

        return redirect()->route('admin.lessons.index', ['course_id' => $courseId])
                        ->with('success', 'Lesson deleted successfully.');
    }
}