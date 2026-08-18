@extends('layouts.master')

@section('title', $course->title . ' - MIFFA Learning Room')

@section('content')
<div class="container-fluid py-4 bg-light mt-5 pt-5" style="min-height: 85vh; margin-top: 80px !important;">
    <div class="row g-0 rounded-4 overflow-hidden shadow-sm border bg-white mx-md-4">
        
        <!-- Left Lesson Sidebar -->
        <div class="col-lg-3 border-end bg-white">
            <!-- Course Header & Progress -->
            <div class="p-4 bg-primary text-white">
                <h5 class="fw-bold mb-1 text-white">{{ $course->title }}</h5>
                <div class="d-flex align-items-center justify-content-between mt-2">
                    <small class="opacity-75">Course Progress</small>
                    <small class="fw-bold">33%</small>
                </div>
                <div class="progress mt-1" style="height: 6px;">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 33%"></div>
                </div>
            </div>

            <!-- Lessons List -->
            <div class="list-group list-group-flush overflow-auto" style="max-height: 600px;">
                @forelse($course->lessons as $index => $item)
                    @php $isActive = $currentLesson && $currentLesson->id === $item->id; @endphp
                    <a href="{{ route('courses.learn', [$course->id, $item->id]) }}" 
                       class="list-group-item list-group-item-action p-3 d-flex align-items-center gap-3 border-bottom {{ $isActive ? 'bg-primary-subtle border-start border-primary border-4 text-primary fw-bold' : '' }}">
                        <div>
                            @if($isActive)
                                <i class="fas fa-play-circle text-primary fs-5"></i>
                            @else
                                <i class="far fa-circle text-muted fs-5"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1 text-truncate">
                            <span class="d-block small text-muted">Lesson {{ $index + 1 }}</span>
                            <span>{{ $item->title }}</span>
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
                        @include('course.partials.quiz', ['lesson' => $currentLesson, 'course' => $course])
                    @elseif($currentLesson->type === 'article')
                        @include('course.partials.article', ['lesson' => $currentLesson])
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