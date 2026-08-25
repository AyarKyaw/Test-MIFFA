<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Section;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        $sectionId = $request->input('section_id');

        $lessons = Lesson::with(['section', 'questions'])
            ->when($sectionId, function ($query) use ($sectionId) {
                return $query->where('section_id', $sectionId);
            })
            ->orderBy('order', 'asc')
            ->get();

        $selectedSection = $sectionId ? Section::with(['unit.course'])->find($sectionId) : null;

        return view('dashboard.lessons.index', compact('lessons', 'selectedSection'));
    }

    public function create(Request $request)
    {
        $sections = Section::orderBy('title')->get();
        $sectionId = $request->query('section_id');

        return view('dashboard.lessons.create', compact('sections', 'sectionId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'section_id'                        => 'nullable|exists:sections,id',
            'title'                             => 'required|string|max:255',
            'type'                              => 'required|string|in:video,article,document,quiz',
            'video_url'                         => 'nullable|url|max:255',
            'content'                           => 'nullable|string',
            'document_file'                     => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:20480',
            'order'                             => 'nullable|integer',
            'questions'                         => 'nullable|array',
            'questions.*.text'                  => 'required_if:type,quiz|string',
            'questions.*.type'                  => 'required_if:type,quiz|in:multiple_choice,boolean',
            'questions.*.hint'                  => 'nullable|string',
            'questions.*.explanation'           => 'nullable|string',
            'questions.*.options'               => 'nullable|array',
            'questions.*.options.*'             => 'nullable|array',
            'questions.*.options.*.text'        => 'nullable|string',
            'questions.*.options.*.feedback'    => 'nullable|string',
            'questions.*.boolean_feedback'      => 'nullable|array',
            'questions.*.boolean_feedback.true' => 'nullable|string',
            'questions.*.boolean_feedback.false'=> 'nullable|string',
        ]);

        $courseId = $validated['course_id'];
        $sectionId = $request->input('section_id');

        if (!$sectionId) {
            $section = Section::whereHas('unit', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })->first();

            if (!$section) {
                $unit = Unit::where('course_id', $courseId)->first();

                if (!$unit) {
                    $unit = Unit::create([
                        'course_id' => $courseId,
                        'title'     => 'General Unit',
                        'order'     => 1,
                    ]);
                }

                $section = Section::create([
                    'unit_id' => $unit->id,
                    'title'   => 'General Section',
                    'order'   => 1,
                ]);
            }

            $sectionId = $section->id;
        }

        $validated['section_id'] = $sectionId;
        $validated['order'] = $validated['order'] ?? 1;

        if ($request->hasFile('document_file')) {
            $validated['document_path'] = $request->file('document_file')->store('lessons/documents', 'public');
        }

        try {
            DB::beginTransaction();

            $lesson = Lesson::create([
                'section_id'    => $validated['section_id'],
                'title'         => $validated['title'],
                'type'          => $validated['type'],
                'video_url'     => $validated['video_url'] ?? null,
                'content'       => $validated['content'] ?? null,
                'document_path' => $validated['document_path'] ?? null,
                'order'         => $validated['order'],
            ]);

            if ($lesson->type === 'quiz' && !empty($request->input('questions'))) {
                foreach ($request->input('questions') as $qData) {
                    $questionText = $qData['text'] ?? ($qData['question_text'] ?? '');
                    $questionType = $qData['type'] ?? 'multiple_choice';

                    $question = $lesson->questions()->create([
                        'question_text' => $questionText,
                        'type'          => $questionType,
                        'is_correct'    => $questionType === 'boolean' ? (isset($qData['is_correct']) && $qData['is_correct'] == 1) : false,
                        'hint'          => $qData['hint'] ?? null,
                        'explanation'   => $qData['explanation'] ?? null,
                    ]);

                    if ($questionType === 'multiple_choice' && isset($qData['options']) && is_array($qData['options'])) {
                        $selectedCorrectIndex = $qData['correct_option'] ?? 0;

                        foreach ($qData['options'] as $optIndex => $optData) {
                            $optText = is_array($optData) ? ($optData['text'] ?? '') : $optData;
                            $optFeedback = is_array($optData) ? ($optData['feedback'] ?? null) : ($qData['option_feedbacks'][$optIndex] ?? null);

                            if (!empty(trim($optText))) {
                                $question->options()->create([
                                    'option_text' => $optText,
                                    'is_correct'  => ((string)$optIndex === (string)$selectedCorrectIndex),
                                    'feedback'    => $optFeedback,
                                ]);
                            }
                        }
                    } elseif ($questionType === 'boolean') {
                        $correctIsTrue = (isset($qData['is_correct']) && $qData['is_correct'] == '1');
                        
                        $question->options()->create([
                            'option_text' => 'True',
                            'is_correct'  => $correctIsTrue,
                            'feedback'    => $qData['boolean_feedback']['true'] ?? ($qData['true_feedback'] ?? null),
                        ]);

                        $question->options()->create([
                            'option_text' => 'False',
                            'is_correct'  => !$correctIsTrue,
                            'feedback'    => $qData['boolean_feedback']['false'] ?? ($qData['false_feedback'] ?? null),
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.lessons.index', ['course_id' => $courseId])
                             ->with('success', 'Lesson created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to store lesson: ' . $e->getMessage()]);
        }
    }

    public function getQuestions(Lesson $lesson)
    {
        $lesson->load(['questions.options']);

        return response()->json([
            'lesson_title' => $lesson->title,
            'questions' => $lesson->questions->map(function ($q) {
                return [
                    'id'          => $q->id,
                    'text'        => $q->question ?? $q->title ?? $q->question_text,
                    'type'        => $q->type ?? ($q->options->isEmpty() ? 'boolean' : 'multiple_choice'),
                    'is_correct'  => $q->is_correct,
                    'hint'        => $q->hint,
                    'explanation' => $q->explanation,
                    'options'     => $q->options->map(function ($opt) {
                        return [
                            'id'         => $opt->id,
                            'text'       => $opt->option_text ?? $opt->text,
                            'is_correct' => (bool) $opt->is_correct,
                            'feedback'   => $opt->feedback,
                        ];
                    }),
                ];
            }),
        ]);
    }

    public function edit(Lesson $lesson)
    {
        $lesson->load(['questions.options']);
        $sections = Section::orderBy('title')->get();

        return view('dashboard.lessons.edit', compact('lesson', 'sections'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'section_id'                        => 'required|exists:sections,id',
            'title'                             => 'required|string|max:255',
            'type'                              => 'required|in:video,document,article,quiz',
            'order'                             => 'required|integer|min:0',
            'video_url'                         => 'nullable|required_if:type,video|url|max:255',
            'content'                           => 'nullable|string',
            'document_file'                     => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:20480',
            'questions'                         => 'nullable|array',
            'questions.*.text'                  => 'required_if:type,quiz|string',
            'questions.*.type'                  => 'required_if:type,quiz|in:multiple_choice,boolean',
            'questions.*.hint'                  => 'nullable|string',
            'questions.*.explanation'           => 'nullable|string',
            'questions.*.options'               => 'nullable|array',
            'questions.*.options.*'             => 'nullable|array',
            'questions.*.options.*.text'        => 'nullable|string',
            'questions.*.options.*.feedback'    => 'nullable|string',
            'questions.*.boolean_feedback'      => 'nullable|array',
            'questions.*.boolean_feedback.true' => 'nullable|string',
            'questions.*.boolean_feedback.false'=> 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $lesson, $validated) {
            $validated['video_url'] = ($validated['type'] === 'video') ? $request->input('video_url') : null;

            if ($request->hasFile('document_file')) {
                if ($lesson->document_path && Storage::disk('public')->exists($lesson->document_path)) {
                    Storage::disk('public')->delete($lesson->document_path);
                }
                $validated['document_path'] = $request->file('document_file')->store('lessons/documents', 'public');
            }

            $lesson->update([
                'section_id'    => $validated['section_id'],
                'title'         => $validated['title'],
                'type'          => $validated['type'],
                'order'         => $validated['order'],
                'video_url'     => $validated['video_url'],
                'content'       => $validated['content'] ?? null,
                'document_path' => $validated['document_path'] ?? $lesson->document_path,
            ]);

            if ($lesson->type === 'quiz') {
                $submittedQuestions = $request->input('questions', []);
                $keepQuestionIds = [];

                foreach ($submittedQuestions as $qData) {
                    $questionText = $qData['text'] ?? ($qData['question_text'] ?? '');
                    $questionType = $qData['type'] ?? 'multiple_choice';
                    $hint = $qData['hint'] ?? null;
                    $explanation = $qData['explanation'] ?? null;

                    if (!empty($qData['id'])) {
                        $question = $lesson->questions()->find($qData['id']);
                        if ($question) {
                            $question->update([
                                'question_text' => $questionText,
                                'type'          => $questionType,
                                'hint'          => $hint,
                                'explanation'   => $explanation,
                            ]);
                        }
                    } else {
                        $question = $lesson->questions()->create([
                            'question_text' => $questionText,
                            'type'          => $questionType,
                            'hint'          => $hint,
                            'explanation'   => $explanation,
                        ]);
                    }

                    if ($question) {
                        $keepQuestionIds[] = $question->id;
                        $question->options()->delete();

                        if ($questionType === 'boolean') {
                            $isCorrect = isset($qData['is_correct']) && $qData['is_correct'] == 1;
                            $question->update(['is_correct' => $isCorrect]);

                            $question->options()->create([
                                'option_text' => 'True',
                                'is_correct'  => $isCorrect,
                                'feedback'    => $qData['boolean_feedback']['true'] ?? null,
                            ]);

                            $question->options()->create([
                                'option_text' => 'False',
                                'is_correct'  => !$isCorrect,
                                'feedback'    => $qData['boolean_feedback']['false'] ?? null,
                            ]);
                        } else {
                            if (isset($qData['options']) && is_array($qData['options'])) {
                                $selectedCorrectIndex = $qData['correct_option'] ?? 0;

                                foreach ($qData['options'] as $optIndex => $optData) {
                                    $optText = is_array($optData) ? ($optData['text'] ?? '') : $optData;
                                    $optFeedback = is_array($optData) ? ($optData['feedback'] ?? null) : null;

                                    if (!empty(trim($optText))) {
                                        $question->options()->create([
                                            'option_text' => $optText,
                                            'is_correct'  => ((string)$optIndex === (string)$selectedCorrectIndex),
                                            'feedback'    => $optFeedback,
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }

                $lesson->questions()->whereNotIn('id', $keepQuestionIds)->delete();
            } else {
                $lesson->questions()->delete();
            }
        });

        session()->forget("quiz_questions_" . auth()->id() . "_{$lesson->id}");

        return redirect()->route('admin.lessons.index', ['section_id' => $lesson->section_id])
                         ->with('success', 'Lesson updated successfully.');
    }
}