@extends('layouts.master')

@section('title', ($courseCategory->name ?? 'Courses') . ' - MIFFA')

@section('content')

```
<!-- Start Breadcrumb -->
<div class="breadcrumb-area text-center bg-gray-gradient-secondary">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h1>{{ $courseCategory->name ?? 'Courses' }}</h1>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li>
                            <a href="{{ url('/') }}">
                                <i class="fas fa-home"></i> Home
                            </a>
                        </li>

                        <li class="active">
                            {{ $courseCategory->name ?? 'Course' }}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumb -->


<!-- Start Course -->
<div class="course-tabs-area default-padding">
    <div class="container">

        <div class="course-tab-style-one">

            <div class="row">

                <!-- ==========================================
                     CATEGORY SIDEBAR
                =========================================== -->
                <div class="col-xl-4 col-lg-5">

                    <ul class="nav nav-tabs category-tabs wow fadeInLeft"
                        id="myTab"
                        role="tablist">

                        @forelse($categories as $category)

                            <li class="nav-item category-sidebar-item"
                                role="presentation">

                                @php
                                    $bgImage = $category->icon_path
                                        ?? $category->image
                                        ?? $category->icon
                                        ?? null;

                                    $bgUrl = $bgImage
                                        ? asset($bgImage)
                                        : asset('assets/img/icon/29.png');
                                @endphp

                                <button
                                    class="nav-link category-sidebar-card {{ $loop->first ? 'active' : '' }}"
                                    id="tabs_{{ $category->id }}"
                                    data-bs-toggle="tab"
                                    data-bs-target="#tab_{{ $category->id }}"
                                    type="button"
                                    role="tab"
                                    aria-controls="tab_{{ $category->id }}"
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                    style="background-image: url('{{ $bgUrl }}');"
                                >

                                    <!-- Bright overlay -->
                                    <span class="category-image-overlay"></span>

                                    <!-- Category content -->
                                    <span class="category-card-content">
                                        <strong>
                                            {{ $category->name }}
                                        </strong>

                                        <span class="category-arrow">
                                            <i class="fas fa-arrow-right"></i>
                                        </span>
                                    </span>

                                </button>

                            </li>

                        @empty

                            <li class="nav-item">
                                <span class="nav-link">
                                    No categories available
                                </span>
                            </li>

                        @endforelse

                    </ul>

                </div>
                <!-- End Category Sidebar -->


                <!-- ==========================================
                     CATEGORY CONTENT
                =========================================== -->
                <div class="col-xl-7 offset-xl-1 col-lg-7">

                    <div class="tab-content category-tab-content wow fadeInUp"
                         data-wow-delay="400ms"
                         id="myTabContent">

                        @forelse($categories as $category)

                            <div
                                class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                id="tab_{{ $category->id }}"
                                role="tabpanel"
                                aria-labelledby="tabs_{{ $category->id }}"
                            >

                                @forelse($category->courses as $course)

                                    @php
                                        $isEnrolled = in_array(
                                            $course->id,
                                            $enrolledCourseIds ?? []
                                        );
                                    @endphp


                                    <!-- ==========================================
                                         SINGLE COURSE
                                    =========================================== -->
                                    <div class="course-style-one-item hover-less list-layout mb-4">

                                        <div class="thumb">
                                            <img
                                                src="{{ asset('storage/' . $course->image) }}"
                                                alt="{{ $course->title }}"
                                            >
                                        </div>


                                        <div class="info">

                                            <!-- Instructor
                                            <div class="author">
                                                <img
                                                    src="{{ asset($course->instructor_image ?? 'assets/img/team/m2.jpg') }}"
                                                    alt="{{ $course->instructor_name ?? 'Instructor' }}"
                                                >

                                                <a href="#">
                                                    {{ $course->instructor_name ?? 'Instructor' }}
                                                </a>
                                            </div>
                                            -->


                                            <h4>
                                                <a href="{{ route('courses.show', $course->id) }}">
                                                    {{ $course->title }}
                                                </a>
                                            </h4>


                                            <div class="course-meta">

                                                <ul>

                                                    <li>
                                                        <div class="course-rating">

                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>

                                                            <span>
                                                                ({{ $course->rating_count ?? '0' }})
                                                            </span>

                                                        </div>
                                                    </li>


                                                    <li>
                                                        <i class="fas fa-user"></i>

                                                        {{ $course->users_count
                                                            ?? $course->students_count
                                                            ?? 0 }}

                                                        Students
                                                    </li>

                                                </ul>

                                            </div>


                                            <div class="bottom-meta">

                                                @if($isEnrolled)

                                                    <a href="{{ route('courses.show', $course->id) }}">
                                                        Enrolled
                                                        <i class="fas fa-long-arrow-right"></i>
                                                    </a>

                                                @else

                                                    <a href="{{ route('courses.show', $course->id) }}">
                                                        Enroll Now
                                                        <i class="fas fa-long-arrow-right"></i>
                                                    </a>

                                                @endif


                                                <h2 class="price">

                                                    @if($course->old_price)

                                                        <del>
                                                            {{ number_format($course->old_price, 0) }}
                                                            MMK
                                                        </del>

                                                    @endif

                                                    {{ number_format($course->price, 0) }}
                                                    MMK

                                                </h2>

                                            </div>

                                        </div>

                                    </div>
                                    <!-- End Single Course -->


                                @empty

                                    <div class="alert alert-info text-center">

                                        No courses found in
                                        <strong>{{ $category->name }}</strong>.

                                    </div>

                                @endforelse

                            </div>

                        @empty

                            <div class="alert alert-warning text-center">
                                No categories found for this program.
                            </div>

                        @endforelse

                    </div>

                </div>
                <!-- End Category Content -->

            </div>

        </div>

    </div>
</div>
<!-- End Course -->
```

@endsection

@push('styles')

<style>

/* ==========================================================
   CATEGORY SIDEBAR
========================================================== */

.category-tabs {
    border: none !important;
    display: flex;
    flex-direction: column;
    gap: 18px;
}


/* Individual sidebar item */

.category-sidebar-item {
    width: 100%;
    border: none !important;
    margin: 0 !important;

    opacity: 0;
    transform: translateX(-45px);

    animation: categorySlideIn 0.65s ease forwards;
}


/* Stagger the animation */

.category-sidebar-item:nth-child(1) {
    animation-delay: 0.1s;
}

.category-sidebar-item:nth-child(2) {
    animation-delay: 0.2s;
}

.category-sidebar-item:nth-child(3) {
    animation-delay: 0.3s;
}

.category-sidebar-item:nth-child(4) {
    animation-delay: 0.4s;
}

.category-sidebar-item:nth-child(5) {
    animation-delay: 0.5s;
}

.category-sidebar-item:nth-child(6) {
    animation-delay: 0.6s;
}

.category-sidebar-item:nth-child(7) {
    animation-delay: 0.7s;
}

.category-sidebar-item:nth-child(8) {
    animation-delay: 0.8s;
}


/* ==========================================================
   SIDEBAR CARD
========================================================== */

.category-sidebar-card {
    position: relative !important;

    width: 100% !important;
    min-height: 105px;

    display: flex !important;
    align-items: center !important;

    padding: 0 !important;

    overflow: hidden;

    border: 0 !important;
    border-radius: 14px !important;

    background-size: cover !important;
    background-position: center !important;
    background-repeat: no-repeat !important;

    color: #ffffff !important;

    box-shadow:
        0 8px 25px rgba(0, 0, 0, 0.12);

    transition:
        transform 0.35s ease,
        box-shadow 0.35s ease,
        filter 0.35s ease;
}


/* ==========================================================
   BRIGHT IMAGE OVERLAY
========================================================== */

.category-image-overlay {
    position: absolute;
    inset: 0;

    background:
        linear-gradient(
            90deg,
            rgba(0, 0, 0, 0.18),
            rgba(0, 0, 0, 0.08)
        );

    z-index: 1;

    transition:
        background 0.35s ease;
}


/* ==========================================================
   CATEGORY TEXT
========================================================== */

.category-card-content {
    position: relative;

    z-index: 2;

    width: 100%;

    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 25px 28px;

    color: #ffffff;
}


.category-card-content strong {
    color: #ffffff !important;

    font-size: 19px;
    font-weight: 700;

    line-height: 1.3;

    text-align: left;

    text-shadow:
        0 2px 5px rgba(0, 0, 0, 0.45);

    transition:
        transform 0.35s ease;
}


/* ==========================================================
   ARROW
========================================================== */

.category-arrow {
    width: 38px;
    height: 38px;

    min-width: 38px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;

    background: rgba(255, 255, 255, 0.92);

    color: #05d5b3;

    opacity: 0;
    transform: translateX(-12px);

    transition:
        opacity 0.3s ease,
        transform 0.3s ease;
}


.category-arrow i {
    font-size: 14px;
}


/* ==========================================================
   HOVER EFFECT
========================================================== */

.category-sidebar-card:hover {
    transform: translateX(8px) scale(1.015);

    box-shadow:
        0 14px 32px rgba(0, 0, 0, 0.20);

    filter: brightness(1.08);
}


.category-sidebar-card:hover .category-image-overlay {
    background:
        linear-gradient(
            90deg,
            rgba(0, 0, 0, 0.05),
            rgba(0, 0, 0, 0.02)
        );
}


.category-sidebar-card:hover .category-card-content strong {
    transform: translateX(4px);
}


.category-sidebar-card:hover .category-arrow {
    opacity: 1;
    transform: translateX(0);
}


/* ==========================================================
   ACTIVE CATEGORY
========================================================== */

.category-sidebar-card.active {
    transform: translateX(8px);

    box-shadow:
        0 12px 30px rgba(5, 213, 179, 0.30);

    border: 3px solid #05d5b3 !important;

    filter: brightness(1.08);
}


.category-sidebar-card.active .category-image-overlay {
    background:
        linear-gradient(
            90deg,
            rgba(0, 0, 0, 0.02),
            rgba(0, 0, 0, 0.02)
        );
}


.category-sidebar-card.active .category-arrow {
    opacity: 1;
    transform: translateX(0);

    background: #05d5b3;
    color: #ffffff;
}


/* ==========================================================
   SIDEBAR ENTRANCE ANIMATION
========================================================== */

@keyframes categorySlideIn {

    0% {
        opacity: 0;
        transform: translateX(-45px);
    }

    60% {
        opacity: 1;
        transform: translateX(8px);
    }

    100% {
        opacity: 1;
        transform: translateX(0);
    }

}


/* ==========================================================
   MOBILE
========================================================== */

@media (max-width: 991px) {

    .category-tabs {
        margin-bottom: 35px;
    }

    .category-sidebar-card {
        min-height: 90px;
    }

    .category-card-content {
        padding: 20px 22px;
    }

    .category-card-content strong {
        font-size: 17px;
    }

}


@media (max-width: 575px) {

    .category-sidebar-card {
        min-height: 80px;
    }

    .category-card-content {
        padding: 18px 20px;
    }

    .category-card-content strong {
        font-size: 16px;
    }

}


/* ==========================================================
   ACCESSIBILITY
========================================================== */

@media (prefers-reduced-motion: reduce) {

    .category-sidebar-item {
        animation: none;
        opacity: 1;
        transform: none;
    }

    .category-sidebar-card,
    .category-arrow,
    .category-card-content strong {
        transition: none;
    }

}

</style>

@endpush
