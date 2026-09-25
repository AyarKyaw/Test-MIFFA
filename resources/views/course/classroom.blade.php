@extends('layouts.master')

@section('title', ($currentLesson ? $currentLesson->section->title : $course->title) . ' - MIFFA Learning Room')

@section('content')

@php
    $user = auth()->user();

    // =========================================================
    // USER LESSON PROGRESS
    // =========================================================

    $userLessons = $user
        ? $user->lessons()
            ->where('lesson_user.course_id', $course->id)
            ->get()
            ->keyBy('id')
        : collect();


    // =========================================================
    // ACTIVE SECTION / UNIT
    // =========================================================

    $activeSection = $currentLesson
        ? $currentLesson->section
        : null;

    $activeUnit = $activeSection
        ? $activeSection->unit
        : null;

    $sectionLessons = $activeSection
        ? $activeSection->lessons
        : collect();


    // =========================================================
    // SECTION PROGRESS
    // =========================================================

    $totalSectionLessons = $sectionLessons->count();

    $completedSectionCount = $sectionLessons->filter(function ($lesson) use ($userLessons) {

        $record = $userLessons->get($lesson->id);

        return $record &&
            (
                $record->pivot->is_completed ||
                $record->pivot->quiz_score >= 80
            );

    })->count();


    $sectionProgressPercent = $totalSectionLessons > 0
        ? round(($completedSectionCount / $totalSectionLessons) * 100)
        : 0;


    // =========================================================
    // LESSON NAVIGATION
    // =========================================================

    $allLessons = $course->units->flatMap(
        fn($u) => $u->sections->flatMap(
            fn($s) => $s->lessons
        )
    );


    $currentIndex = $currentLesson
        ? $allLessons->search(
            fn($item) => $item->id === $currentLesson->id
        )
        : false;


    $prevLesson = $currentIndex !== false
        ? ($allLessons[$currentIndex - 1] ?? null)
        : null;


    $nextLesson = $currentIndex !== false
        ? ($allLessons[$currentIndex + 1] ?? null)
        : null;
@endphp


<div
    class="container-fluid learning-room bg-light mt-5 pt-2 pt-lg-4"
    style="min-height: 85vh;"
>

    <div class="row g-0 learning-room-card bg-white mx-0 mx-md-4 align-items-stretch">


        <!-- =====================================================
             LEFT SIDEBAR
        ====================================================== -->

        <div class="col-lg-3 d-none d-lg-block border-end bg-white">


            <!-- =================================================
                 SECTION HEADER & PROGRESS
            ================================================== -->

            <div class="p-4 bg-primary text-white">

                <div class="d-flex align-items-center gap-2 mb-1 opacity-75 small">

                    <i class="fas fa-layer-group"></i>

                    <span class="text-truncate">
                        {{ $activeUnit->title ?? $course->title }}
                    </span>

                </div>


                <h5
                    class="fw-bold mb-2 text-white text-truncate"
                    title="{{ $activeSection->title ?? 'Course Lessons' }}"
                >
                    {{ $activeSection->title ?? 'Course Lessons' }}
                </h5>


                <div class="d-flex align-items-center justify-content-between mt-3">

                    <small class="opacity-75">
                        Section Progress
                    </small>

                    <small class="fw-bold">

                        {{ $completedSectionCount }}/{{ $totalSectionLessons }}

                        ({{ $sectionProgressPercent }}%)

                    </small>

                </div>


                <div
                    class="progress mt-1"
                    style="height: 6px; background-color: rgba(255,255,255,0.25);"
                >

                    <div
                        class="progress-bar bg-warning"
                        role="progressbar"
                        style="width: {{ $sectionProgressPercent }}%"
                    ></div>

                </div>

            </div>


            <!-- =================================================
                 ACTIVE SECTION LESSONS
            ================================================== -->

            <div
                class="list-group list-group-flush overflow-auto"
                style="max-height: 600px;"
            >

                @forelse($sectionLessons as $index => $item)

                    @php

                        $isActive = $currentLesson &&
                            $currentLesson->id === $item->id;


                        $userLessonRecord = $userLessons->get($item->id);


                        $quizScore = $userLessonRecord
                            ? $userLessonRecord->pivot->quiz_score
                            : null;


                        $isCompleted = $userLessonRecord
                            ? $userLessonRecord->pivot->is_completed
                            : false;


                        $hasHomework = $userLessonRecord
                            ? !empty($userLessonRecord->pivot->homework_file_path)
                            : false;

                    @endphp


                    <a
                        href="{{ route('courses.learn', [$course->id, $item->id]) }}"
                        class="
                            list-group-item
                            list-group-item-action
                            p-3
                            d-flex
                            align-items-center
                            gap-3
                            border-bottom

                            {{ $isActive
                                ? 'bg-primary-subtle border-start border-primary border-4 text-primary fw-bold'
                                : ''
                            }}
                        "
                    >


                        <!-- =================================================
                             STATUS ICON
                        ================================================== -->

                        <div>

                            @if(is_null($quizScore) && !$isCompleted && !$hasHomework)

                                @if($isActive)

                                    <i
                                        class="fas fa-play-circle text-primary fs-5"
                                        title="Current Lesson"
                                    ></i>

                                @else

                                    <i
                                        class="far fa-circle text-muted fs-5"
                                        title="Not Started"
                                    ></i>

                                @endif


                            @elseif($item->type === 'homework' && $hasHomework)

                                <i
                                    class="fas fa-check-circle text-success fs-5"
                                    title="Homework Submitted"
                                ></i>


                            @elseif($quizScore < 50 && !$isCompleted)

                                <i
                                    class="fas fa-exclamation-circle text-danger fs-5"
                                    title="Needs Practice ({{ $quizScore }}%)"
                                ></i>


                            @elseif($quizScore >= 50 && $quizScore < 80 && !$isCompleted)

                                <i
                                    class="fas fa-adjust text-warning fs-5"
                                    title="Familiar ({{ $quizScore }}%)"
                                ></i>


                            @else

                                <i
                                    class="fas fa-check-circle text-success fs-5"
                                    title="Completed"
                                ></i>

                            @endif

                        </div>


                        <!-- =================================================
                             LESSON DETAILS
                        ================================================== -->

                        <div class="flex-grow-1 text-truncate">

                            <div
                                class="
                                    d-flex
                                    align-items-center
                                    justify-content-between
                                "
                            >

                                <span class="d-block small text-muted">
                                    Lesson {{ $index + 1 }}
                                </span>


                                @if(!is_null($quizScore))

                                    <span
                                        class="
                                            badge
                                            rounded-pill

                                            {{ $quizScore >= 80
                                                ? 'bg-success-subtle text-success'
                                                : ($quizScore >= 50
                                                    ? 'bg-warning-subtle text-warning-emphasis'
                                                    : 'bg-danger-subtle text-danger')
                                            }}
                                        "
                                        style="font-size: 0.7rem;"
                                    >
                                        {{ $quizScore }}%
                                    </span>


                                @elseif($item->type === 'homework' && $hasHomework)

                                    <span
                                        class="
                                            badge
                                            bg-success-subtle
                                            text-success
                                            rounded-pill
                                        "
                                        style="font-size: 0.7rem;"
                                    >
                                        Submitted
                                    </span>

                                @endif

                            </div>


                            <span
                                class="
                                    text-truncate
                                    d-block

                                    {{ ($isCompleted || $hasHomework) && !$isActive
                                        ? 'text-secondary'
                                        : ''
                                    }}
                                "
                            >
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


        <!-- =====================================================
             MAIN CONTENT AREA
        ====================================================== -->

        <div
            class="
                col-12
                col-lg-9
                bg-light
                p-2
                p-sm-3
                p-lg-5
                d-flex
                flex-column
                justify-content-between
            "
            style="padding-bottom: 0 !important;"
        >

            @if($currentLesson)


                <!-- =================================================
                     LESSON AREA
                ================================================== -->

                <div>


                    <!-- =================================================
                         BREADCRUMB
                    ================================================== -->

                    <nav
                        aria-label="breadcrumb"
                        class="mb-2 mb-lg-4"
                    >

                        <ol
                            class="
                                breadcrumb
                                bg-white
                                px-2
                                px-sm-3
                                py-2
                                rounded-3
                                border
                                shadow-sm
                                align-items-center
                                mb-0
                                text-truncate
                            "
                            style="font-size: 0.8rem;"
                        >


                            <!-- Course -->

                            <li class="breadcrumb-item">

                                <a
                                    href="{{ route('courses.my', $course->id) }}"
                                    class="
                                        text-decoration-none
                                        fw-semibold
                                        text-secondary
                                        hover-primary
                                    "
                                >

                                    <i class="fas fa-graduation-cap me-1"></i>

                                    {{ $course->title }}

                                </a>

                            </li>


                            <!-- Unit -->

                            @if($activeUnit)

                                <li class="breadcrumb-item">

                                    <a
                                        href="{{ route('courses.units', [$course->id, 'unit' => $activeUnit->id]) }}"
                                        class="
                                            text-decoration-none
                                            fw-semibold
                                            text-secondary
                                            hover-primary
                                        "
                                    >

                                        {{ $activeUnit->title }}

                                    </a>

                                </li>

                            @endif


                            <!-- Section -->

                            @if($activeSection)

                                <li class="breadcrumb-item">

                                    <a
                                        href="{{ route('courses.units', [$course->id, 'unit' => $activeUnit->id]) }}"
                                        class="
                                            text-decoration-none
                                            fw-semibold
                                            text-secondary
                                            hover-primary
                                        "
                                    >

                                        {{ $activeSection->title }}

                                    </a>

                                </li>

                            @endif


                            <!-- Current Lesson -->

                            <li
                                class="
                                    breadcrumb-item
                                    active
                                    text-primary
                                    fw-bold
                                    text-truncate
                                "
                                aria-current="page"
                                style="max-width: 180px;"
                            >

                                {{ $currentLesson->title }}

                            </li>

                        </ol>

                    </nav>


                    <!-- =================================================
                         PLAYER / LESSON CONTENT
                    ================================================== -->

                    <div
                        class="
                            bg-white
                            rounded-3
                            rounded-lg-4
                            shadow-sm
                            border
                            overflow-hidden
                            lesson-content

                            {{ $currentLesson->type === 'video'
                                ? 'video-lesson-content'
                                : ''
                            }}
                        "
                    >

                        @if($currentLesson->type === 'video')

                            @include(
                                'course.partials.video',
                                ['lesson' => $currentLesson]
                            )


                        @elseif($currentLesson->type === 'quiz')

                            @include(
                                'course.partials.quiz',
                                [
                                    'lesson' => $currentLesson,
                                    'questions' => $questions ?? []
                                ]
                            )


                        @elseif($currentLesson->type === 'article')

                            @include(
                                'course.partials.article',
                                ['lesson' => $currentLesson]
                            )


                        @elseif($currentLesson->type === 'homework')

                            @include(
                                'course.partials.homework',
                                [
                                    'lesson' => $currentLesson,
                                    'userLessons' => $userLessons
                                ]
                            )

                        @endif

                    </div>

                </div>

                @if($nextLesson)

                    <div class="next-lesson-fixed">

                        <a
                            href="{{ route('courses.learn', [$course->id, $nextLesson->id]) }}"
                            class="btn btn-primary next-lesson-button"
                        >

                            <span>
                                Up next: {{ ucfirst($nextLesson->type) }}
                            </span>

                            <i class="fas fa-arrow-right ms-2"></i>

                        </a>

                    </div>

                @endif


            @else


                <!-- =================================================
                     NO LESSON
                ================================================== -->

                <div class="text-center py-5 my-auto">

                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>

                    <h5>
                        Select a lesson to begin learning.
                    </h5>

                </div>

            @endif

        </div>

    </div>

</div>


@push('styles')

<style>

/* =========================================================
   LEARNING ROOM
========================================================= */

.learning-room {
    width: 100%;
}

.learning-room-card {
    min-height: 80vh;
}


/* =========================================================
   BREADCRUMB
========================================================= */

.learning-room .breadcrumb {
    overflow: hidden;
    white-space: nowrap;
}

.learning-room .breadcrumb-item {
    min-width: 0;
}

.learning-room .breadcrumb-item a,
.learning-room .breadcrumb-item.active {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}


/* =========================================================
   LESSON CONTENT
========================================================= */

.lesson-content {
    width: 100%;
    max-width: 100%;
}


/* =========================================================
   LESSON NAVIGATION
========================================================= */

.lesson-navigation-wrapper {
    position: relative;

    margin-top: 24px;

    padding-top: 16px;

    min-height: 60px;
}


.lesson-previous-navigation {
    display: flex;

    justify-content: flex-start;

    align-items: center;
}


/* =========================================================
   NEXT LESSON
   FIXED BOTTOM - DESKTOP
========================================================= */

.next-lesson-fixed {
    position: fixed;

    left: 0;
    right: 0;
    bottom: 0;

    z-index: 1050;

    display: flex;

    justify-content: flex-end;

    align-items: center;

    padding: 12px 24px;

    background: rgba(255, 255, 255, 0.96);

    border-top: 1px solid #dee2e6;

    box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.08);

    backdrop-filter: blur(8px);

    -webkit-backdrop-filter: blur(8px);
}


/* =========================================================
   NEXT BUTTON
========================================================= */

.next-lesson-button {
    min-height: 48px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    padding: 0 24px;

    border-radius: 999px;

    font-weight: 600;

    white-space: nowrap;
    background: #0d6efd !important;

    text-decoration: none;
}


/* =========================================================
   DESKTOP ROOM BOTTOM SPACE
========================================================= */

.learning-room {
    padding-bottom: 90px !important;
}

@media (min-width: 992px) {

    .learning-room-card {
        align-items: stretch;
    }

    .learning-room-card > .col-lg-3,
    .learning-room-card > .col-lg-9 {
        display: flex;
        flex-direction: column;
    }

    .learning-room-card > .col-lg-9 {
        min-height: 80vh;
    }

    .learning-room-card .lesson-content {
        flex: 1 1 auto;
        min-height: 0;
    }

}


/* =========================================================
   MOBILE
   iPhone / Android
========================================================= */

@media (max-width: 767.98px) {


    /* =====================================================
       LEARNING ROOM
    ===================================================== */

    .learning-room {

        width: 100%;

        padding-left: 0 !important;

        padding-right: 0 !important;

        margin-top: 56px !important;

        padding-top: 0 !important;

        /*
         * Space for:
         *
         * 1. Next Lesson button
         * 2. Bottom navigation
         */
        padding-bottom: 145px !important;
    }


    /* =====================================================
       FULL WIDTH LEARNING CARD
    ===================================================== */

    .learning-room-card {

        width: 100% !important;

        margin-left: 0 !important;

        margin-right: 0 !important;

        border-radius: 0 !important;

        border-left: 0 !important;

        border-right: 0 !important;

        box-shadow: none !important;
    }


    /* =====================================================
       MAIN CONTENT
    ===================================================== */

    .learning-room .col-lg-9 {
        width: 100%;
    }


    /* =====================================================
       BREADCRUMB
    ===================================================== */

    .learning-room nav[aria-label="breadcrumb"] {

        margin-bottom: 8px !important;
    }


    .learning-room .breadcrumb {

        border-radius: 8px !important;

        padding: 8px 10px !important;

        font-size: 0.75rem !important;
    }


    /* =====================================================
       VIDEO
       TOUCH BOTH SIDES OF PHONE SCREEN
    ===================================================== */

    .video-lesson-content {

        width: calc(100% + 1rem) !important;

        max-width: none !important;

        margin-left: -0.5rem !important;

        margin-right: -0.5rem !important;

        border-left: 0 !important;

        border-right: 0 !important;

        border-radius: 0 !important;

        box-shadow: none !important;
    }


    /* =====================================================
       VIDEO PARTIAL WRAPPER
    ===================================================== */

    .video-lesson-content .video-partial-wrapper {

        padding-left: 0 !important;

        padding-right: 0 !important;
    }


    /* =====================================================
       VIDEO RATIO
    ===================================================== */

    .video-lesson-content .ratio {

        width: 100% !important;

        margin-left: 0 !important;

        margin-right: 0 !important;

        border-radius: 0 !important;
    }


    /* =====================================================
       YOUTUBE
    ===================================================== */

    .video-lesson-content iframe {

        display: block;

        width: 100% !important;

        max-width: 100% !important;

        height: 100% !important;
    }


    /* =====================================================
       LOCAL VIDEO
    ===================================================== */

    .video-lesson-content video {

        display: block;

        width: 100% !important;

        max-width: 100% !important;

        height: 100% !important;
    }


    /* =====================================================
       NORMAL LESSON CONTENT
    ===================================================== */

    .lesson-content {

        border-radius: 10px !important;
    }


    /* =====================================================
       VIDEO SQUARE EDGES
    ===================================================== */

    .video-lesson-content {

        border-radius: 0 !important;
    }


    /* =====================================================
       NORMAL BUTTONS
    ===================================================== */

    .learning-room .btn {

        min-height: 44px;
    }


    /* =====================================================
       PREVIOUS BUTTON
       NORMAL PAGE FLOW
    ===================================================== */

    .lesson-previous-navigation {

        margin-bottom: 10px;
    }


    /* =====================================================
       NEXT LESSON
       ABOVE MOBILE BOTTOM NAV
    ===================================================== */

    .next-lesson-fixed {

        left: 0;

        right: 0;

        /*
         * Your mobile bottom navigation is underneath.
         *
         * 65px = bottom navigation space.
         *
         * This keeps Next Lesson ABOVE it.
         */
        bottom: 80px;

        padding: 10px 12px;

        justify-content: center;
    }


    /* =====================================================
       NEXT LESSON BUTTON
    ===================================================== */

    .next-lesson-button {

        width: 100%;

        min-height: 50px;

        border-radius: 12px;

        font-size: 0.95rem;
    }

}


/* =========================================================
   VERY SMALL PHONES
   iPhone SE / SMALL ANDROID
========================================================= */

@media (max-width: 375px) {


    .learning-room .breadcrumb {

        font-size: 0.7rem !important;
    }


    .learning-room .breadcrumb-item.active {

        max-width: 130px !important;
    }


    .learning-room .btn {

        font-size: 0.85rem;
    }


    /* =====================================================
       NEXT LESSON
    ===================================================== */

    .next-lesson-fixed {

        bottom: 90px;

        padding: 8px 10px;
    }


    .next-lesson-button {

        min-height: 48px;

        font-size: 0.9rem;
    }

}


/* =========================================================
   DESKTOP
========================================================= */

@media (min-width: 768px) {

    .next-lesson-fixed {

        padding-left: 24px;

        padding-right: 24px;
    }


    .next-lesson-button {

        min-width: 160px;
    }

}

</style>

@endpush

@endsection