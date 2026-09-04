<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Course;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. Fetch active course
        $recentLessonPivot = $user->lessons()
            ->whereNotNull('lesson_user.course_id')
            ->orderByPivot('updated_at', 'desc')
            ->first();

        $activeCourse = $recentLessonPivot 
            ? Course::find($recentLessonPivot->pivot->course_id) 
            : $user->courses()->first();

        $overallProgress = 0;
        $unfinishedLessons = collect();

        if ($activeCourse) {
            // Eager-load hierarchy: Course -> Units -> Sections -> Lessons
            $activeCourse->load(['units.sections.lessons']);

            // Gather all lessons belonging to this course
            $allCourseLessons = $activeCourse->units
                ->flatMap(fn($unit) => $unit->sections)
                ->flatMap(fn($section) => $section->lessons);

            $courseLessonIds = $allCourseLessons->pluck('id');
            $totalLessons = $courseLessonIds->count();

            // Fetch user's pivot data keyed by lesson_id for quick lookups
            $userLessonsMap = $user->lessons()
                ->whereIn('lesson_id', $courseLessonIds)
                ->get()
                ->keyBy('id');

            // Count completed
            $completedLessons = $userLessonsMap->filter(function ($lesson) {
                return $lesson->pivot->is_completed || ($lesson->pivot->quiz_score ?? 0) >= 80;
            })->count();

            $overallProgress = $totalLessons > 0 
                ? round(($completedLessons / $totalLessons) * 100) 
                : 0;

            // 2. Fetch up to 5 Unfinished / In-Progress or Not Started lessons
            $unfinishedLessons = $allCourseLessons->filter(function ($lesson) use ($userLessonsMap) {
                $userRecord = $userLessonsMap->get($lesson->id);
                // Exclude completed or passed lessons
                if ($userRecord && ($userRecord->pivot->is_completed || ($userRecord->pivot->quiz_score ?? 0) >= 80)) {
                    return false;
                }
                return true;
            })->map(function ($lesson) use ($userLessonsMap) {
                $userRecord = $userLessonsMap->get($lesson->id);
                $lesson->status_label = $userRecord ? 'In Progress' : 'Not Started';
                $lesson->last_activity = $userRecord?->pivot->updated_at ?? null;
                return $lesson;
            })
            // Sort in-progress lessons first by recent activity, followed by not started
            ->sortByDesc(fn($lesson) => $lesson->last_activity ?? '1970-01-01')
            ->take(5)
            ->values();
        }

        // 3. Fetch recent homework ONLY
        $recentHomework = $user->lessons()
            ->wherePivotNotNull('homework_file_path')
            ->orderByPivot('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.students.index', compact(
            'activeCourse',
            'overallProgress',
            'unfinishedLessons',
            'recentHomework'
        ));
    }
}