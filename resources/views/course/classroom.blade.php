@extends('layouts.master')

@section('title', ($currentLesson ? $currentLesson->section->title : $course->title) . ' - MIFFA Learning Room')

@section('content')
@php
    $user = auth()->user();

    // Fetch user progress keyed by lesson ID
    $userLessons = $user 
        ? $user->lessons()
               ->where('lesson_user.course_id', $course->id)
               ->get()
               ->keyBy('id') 
        : collect();

    // Active Section & Unit Details
    $activeSection = $currentLesson ? $currentLesson->section : null;
    $activeUnit = $activeSection ? $activeSection->unit : null;
    $sectionLessons = $activeSection ? $activeSection->lessons : collect();

    // Section Progress Calculation
    $totalSectionLessons = $sectionLessons->count();
    $completedSectionCount = $sectionLessons->filter(function($lesson) use ($userLessons) {
        $record = $userLessons->get($lesson->id);
        return $record && ($record->pivot->is_completed || $record->pivot->quiz_score >= 80);
    })->count();

    $sectionProgressPercent = $totalSectionLessons > 0 ? round(($completedSectionCount / $totalSectionLessons) * 100) : 0;
@endphp

<div class="container-fluid py-4 bg-light mt-5 pt-5" style="min-height: 85vh; margin-top: 80px !important;">
    <div class="row g-0 rounded-4 overflow-hidden shadow-sm border bg-white mx-md-4">
        
        <!-- Left Sidebar: Active Section Header & Lessons -->
        <div class="col-lg-3 border-end bg-white">
            
            <!-- Section Header & Progress -->
            <div class="p-4 bg-primary text-white">
                <div class="d-flex align-items-center gap-2 mb-1 opacity-75 small">
                    <i class="fas fa-layer-group"></i>
                    <span class="text-truncate">{{ $activeUnit->title ?? $course->title }}</span>
                </div>
                
                <h5 class="fw-bold mb-2 text-white text-truncate" title="{{ $activeSection->title ?? 'Course Lessons' }}">
                    {{ $activeSection->title ?? 'Course Lessons' }}
                </h5>

                <div class="d-flex align-items-center justify-content-between mt-3">
                    <small class="opacity-75">Section Progress</small>
                    <small class="fw-bold">{{ $completedSectionCount }}/{{ $totalSectionLessons }} ({{ $sectionProgressPercent }}%)</small>
                </div>
                <div class="progress mt-1" style="height: 6px; background-color: rgba(255,255,255,0.25);">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $sectionProgressPercent }}%"></div>
                </div>
            </div>

            <!-- Active Section Lessons List -->
            <div class="list-group list-group-flush overflow-auto" style="max-height: 600px;">
                @forelse($sectionLessons as $index => $item)
                    @php 
                        $isActive = $currentLesson && $currentLesson->id === $item->id;
                        $userLessonRecord = $userLessons->get($item->id);
                        $quizScore = $userLessonRecord ? $userLessonRecord->pivot->quiz_score : null;
                        $isCompleted = $userLessonRecord ? $userLessonRecord->pivot->is_completed : false;
                    @endphp

                    <a href="{{ route('courses.learn', [$course->id, $item->id]) }}" 
                       class="list-group-item list-group-item-action p-3 d-flex align-items-center gap-3 border-bottom {{ $isActive ? 'bg-primary-subtle border-start border-primary border-4 text-primary fw-bold' : '' }}">
                        
                        <!-- Status Icon -->
                        <div>
                            @if(is_null($quizScore) && !$isCompleted)
                                @if($isActive)
                                    <i class="fas fa-play-circle text-primary fs-5" title="Current Lesson"></i>
                                @else
                                    <i class="far fa-circle text-muted fs-5" title="Not Started"></i>
                                @endif
                            @elseif($quizScore < 50 && !$isCompleted)
                                <i class="fas fa-exclamation-circle text-danger fs-5" title="Needs Practice ({{ $quizScore }}%)"></i>
                            @elseif($quizScore >= 50 && $quizScore < 80 && !$isCompleted)
                                <i class="fas fa-adjust text-warning fs-5" title="Familiar ({{ $quizScore }}%)"></i>
                            @else
                                <i class="fas fa-check-circle text-success fs-5" title="Mastered ({{ $quizScore ?? 100 }}%)"></i>
                            @endif
                        </div>

                        <!-- Lesson Details -->
                        <div class="flex-grow-1 text-truncate">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="d-block small text-muted">Lesson {{ $index + 1 }}</span>
                                
                                @if(!is_null($quizScore))
                                    <span class="badge rounded-pill {{ $quizScore >= 80 ? 'bg-success-subtle text-success' : ($quizScore >= 50 ? 'bg-warning-subtle text-warning-emphasis' : 'bg-danger-subtle text-danger') }}" style="font-size: 0.7rem;">
                                        {{ $quizScore }}%
                                    </span>
                                @endif
                            </div>

                            <span class="text-truncate d-block {{ $isCompleted && !$isActive ? 'text-secondary' : '' }}">
                                {{ $item->title }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="p-4 text-center text-muted">
                        No lessons in this section.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="col-lg-9 bg-light p-4 p-md-5 d-flex flex-column justify-content-between">
            @if($currentLesson)
                <div>
                    <!-- Dedicated Route Breadcrumbs -->
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb bg-white p-3 rounded-3 border shadow-sm align-items-center" style="font-size: 0.9rem;">
                            
                            <!-- Course Route -->
                            <li class="breadcrumb-item">
                                <a href="{{ route('courses.my', $course->id) }}" 
                                   class="text-decoration-none fw-semibold text-secondary hover-primary">
                                    <i class="fas fa-graduation-cap me-1"></i> {{ $course->title }}
                                </a>
                            </li>

                            <!-- Unit Route -->
                            @if($activeUnit)
                                <li class="breadcrumb-item">
                                    <a href="{{ route('courses.units', [$course->id, $activeUnit->id]) }}" 
                                       class="text-decoration-none fw-semibold text-secondary hover-primary">
                                        {{ $activeUnit->title }}
                                    </a>
                                </li>
                            @endif

                            <!-- Section Route -->
                            @if($activeSection)
                                <li class="breadcrumb-item">
                                    <a href="{{ route('courses.units', [$course->id, $activeSection->id]) }}" 
                                       class="text-decoration-none fw-semibold text-secondary hover-primary">
                                        {{ $activeSection->title }}
                                    </a>
                                </li>
                            @endif

                            <!-- Current Active Lesson -->
                            <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">
                                {{ $currentLesson->title }}
                            </li>
                        </ol>
                    </nav>

                    <!-- Player / Partial Box -->
                    <div class="bg-white rounded-4 shadow-sm border overflow-hidden">
                        @if($currentLesson->type === 'video')
                            @include('course.partials.video', ['lesson' => $currentLesson])
                        @elseif($currentLesson->type === 'quiz')
                            @include('course.partials.quiz', [
                                    'lesson' => $currentLesson,
                                    'questions' => $questions ?? []
                                ])
                        @elseif($currentLesson->type === 'article')
                            @include('course.partials.article', ['lesson' => $currentLesson])
                        @endif
                    </div>
                </div>

                <!-- Navigation Controls -->
                @php
                    $allLessons = $course->units->flatMap(fn($u) => $u->sections->flatMap(fn($s) => $s->lessons));
                    $currentIndex = $allLessons->search(fn($item) => $item->id === $currentLesson->id);
                    $prevLesson = $currentIndex !== false ? ($allLessons[$currentIndex - 1] ?? null) : null;
                    $nextLesson = $currentIndex !== false ? ($allLessons[$currentIndex + 1] ?? null) : null;
                @endphp

                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
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
                <div class="text-center py-5 my-auto">
                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                    <h5>Select a lesson to begin learning.</h5>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection