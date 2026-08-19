@extends('layouts.master')

@section('title', $course->title . ' - MIFFA Learning Room')

@section('content')
@php
    $user = auth()->user();

    // Fix: Explicitly specify 'lesson_user.course_id' to resolve the ambiguity
    $userLessons = $user 
        ? $user->lessons()
               ->where('lesson_user.course_id', $course->id)
               ->get()
               ->keyBy('id') 
        : collect();

    $totalLessons = $course->lessons->count();
    
    // Count completed or mastered lessons
    $completedCount = $userLessons->filter(function($lesson) {
        return $lesson->pivot->is_completed || ($lesson->pivot->quiz_score >= 80);
    })->count();

    $courseProgressPercent = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0;
@endphp

<div class="container-fluid py-4 bg-light mt-5 pt-5" style="min-height: 85vh; margin-top: 80px !important;">
    <div class="row g-0 rounded-4 overflow-hidden shadow-sm border bg-white mx-md-4">
        
        <!-- Left Lesson Sidebar -->
        <div class="col-lg-3 border-end bg-white">
            <!-- Course Header & Overall Progress -->
            <div class="p-4 bg-primary text-white">
                <h5 class="fw-bold mb-1 text-white">{{ $course->title }}</h5>
                <div class="d-flex align-items-center justify-content-between mt-2">
                    <small class="opacity-75">Course Progress</small>
                    <small class="fw-bold">{{ $courseProgressPercent }}%</small>
                </div>
                <div class="progress mt-1" style="height: 6px; background-color: rgba(255,255,255,0.25);">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $courseProgressPercent }}%"></div>
                </div>
            </div>

            <!-- Lessons List -->
            <div class="list-group list-group-flush overflow-auto" style="max-height: 600px;">
                @forelse($course->lessons as $index => $item)
                    @php 
                        $isActive = $currentLesson && $currentLesson->id === $item->id;
                        $userLessonRecord = $userLessons->get($item->id);
                        $quizScore = $userLessonRecord ? $userLessonRecord->pivot->quiz_score : null;
                        $isCompleted = $userLessonRecord ? $userLessonRecord->pivot->is_completed : false;
                    @endphp

                    <a href="{{ route('courses.learn', [$course->id, $item->id]) }}" 
                       class="list-group-item list-group-item-action p-3 d-flex align-items-center gap-3 border-bottom {{ $isActive ? 'bg-primary-subtle border-start border-primary border-4 text-primary fw-bold' : '' }}">
                        
                        <!-- Khan Academy Style Mastery & Lesson Status Icon -->
                        <div>
                            @if(is_null($quizScore) && !$isCompleted)
                                @if($isActive)
                                    <!-- Currently Selected/Playing State -->
                                    <i class="fas fa-play-circle text-primary fs-5" title="Current Lesson"></i>
                                @else
                                    <!-- Unattended State -->
                                    <i class="far fa-circle text-muted fs-5" title="Not Started"></i>
                                @endif

                            @elseif($quizScore < 50 && !$isCompleted)
                                <!-- Low Tier (< 50%) -->
                                <i class="fas fa-exclamation-circle text-danger fs-5" title="Needs Practice ({{ $quizScore }}%)"></i>

                            @elseif($quizScore >= 50 && $quizScore < 80 && !$isCompleted)
                                <!-- Mid Tier (50% - 79%) -->
                                <i class="fas fa-adjust text-warning fs-5" title="Familiar ({{ $quizScore }}%)"></i>

                            @else
                                <!-- Completed / Mastered Tier (>= 80% or marked completed) -->
                                <i class="fas fa-check-circle text-success fs-5" title="Mastered ({{ $quizScore ?? 100 }}%)"></i>
                            @endif
                        </div>

                        <!-- Lesson Title & Score Subtitle -->
                        <div class="flex-grow-1 text-truncate">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="d-block small text-muted">Lesson {{ $index + 1 }}</span>
                                
                                @if(!is_null($quizScore))
                                    <span class="badge rounded-pill {{ $quizScore >= 80 ? 'bg-success-subtle text-success' : ($quizScore >= 50 ? 'bg-warning-subtle text-warning-emphasis' : 'bg-danger-subtle text-danger') }}" style="font-size: 0.7rem;">
                                        {{ $quizScore }}%
                                    </span>
                                @endif
                            </div>

                            <span class="{{ $isCompleted && !$isActive ? 'text-secondary' : '' }}">
                                {{ $item->title }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="p-4 text-center text-muted">
                        No lessons uploaded yet.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="col-lg-9 bg-light p-4 p-md-5">
            @if($currentLesson)
                <div class="bg-white rounded-4 shadow-sm border overflow-hidden">
                    @if($currentLesson->type === 'video')
                        @include('course.partials.video', ['lesson' => $currentLesson])
                    @elseif($currentLesson->type === 'quiz')
                        @include('course.partials.quiz', [
                                'lesson' => $currentLesson,
                                'questions' => $questions
                            ])
                    @elseif($currentLesson->type === 'article')
                        @include('course.partials.article', ['lesson' => $currentLesson])
                    @endif
                </div>

                <!-- Previous & Next Lesson Navigation Controls -->
                @php
                    $currentIndex = $course->lessons->search(fn($item) => $item->id === $currentLesson->id);
                    $prevLesson = $currentIndex !== false ? ($course->lessons[$currentIndex - 1] ?? null) : null;
                    $nextLesson = $currentIndex !== false ? ($course->lessons[$currentIndex + 1] ?? null) : null;
                @endphp

                <div class="d-flex justify-content-between align-items-center mt-4">
                    @if($prevLesson)
                        <a href="{{ route('courses.learn', [$course->id, $prevLesson->id]) }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="fas fa-arrow-left me-2"></i> Previous Lesson
                        </a>
                    @else
                        <div></div>
                    @endif

                    @if($nextLesson)
                        <a href="{{ route('courses.learn', [$course->id, $nextLesson->id]) }}" class="btn btn-primary rounded-pill px-4">
                            Next Lesson <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    @endif
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-video fa-3x text-muted mb-3"></i>
                    <h5>Select a lesson from the left menu to start watching.</h5>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection