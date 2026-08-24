@extends('layouts.master')

@section('title', ($courseCategory->name ?? 'Courses') . ' - MIFFA')

@section('content')
    <!-- Start Breadcrumb -->
    <div class="breadcrumb-area text-center bg-gray-gradient-secondary">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <h1>{{ $courseCategory->name ?? 'Courses' }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="{{ url('/') }}"><i class="fas fa-home"></i> Home</a></li>
                            <li class="active">{{ $courseCategory->name ?? 'Course' }}</li>
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
                    <!-- Category Tabs Sidebar (Sub-categories under this CourseCategory) -->
                    <div class="col-xl-4 col-lg-5">
                        <ul class="nav nav-tabs category-tabs wow fadeInUp" id="myTab" role="tablist">
                            @forelse($categories as $category)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                            id="tabs_{{ $category->id }}" 
                                            data-bs-toggle="tab" 
                                            data-bs-target="#tab_{{ $category->id }}" 
                                            type="button" 
                                            role="tab" 
                                            aria-controls="tab_{{ $category->id }}" 
                                            aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                        <img src="{{ asset($category->icon_path ?? $category->icon ?? 'assets/img/icon/29.png') }}" alt="{{ $category->name }}">
                                        <strong>{{ $category->name }}</strong>
                                    </button>
                                </li>
                            @empty
                                <li class="nav-item">
                                    <span class="nav-link">No categories available</span>
                                </li>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Category Tab Content Area -->
                    <div class="col-xl-7 offset-xl-1 col-lg-7">
                        <div class="tab-content category-tab-content wow fadeInUp" data-wow-delay="400ms" id="myTabContent">
                            @forelse($categories as $category)
                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                     id="tab_{{ $category->id }}" 
                                     role="tabpanel" 
                                     aria-labelledby="tabs_{{ $category->id }}">
                                    
                                    @forelse($category->courses as $course)
                                        <!-- Single Course Item -->
                                        <div class="course-style-one-item hover-less list-layout mb-4">
                                            <div class="thumb">
                                                <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}">
                                            </div>
                                            <div class="info">
                                                <div class="author">
                                                    <img src="{{ asset($course->instructor_image ?? 'assets/img/team/m2.jpg') }}" alt="{{ $course->instructor_name ?? 'Instructor' }}">
                                                    <a href="#">{{ $course->instructor_name ?? 'Instructor' }}</a>
                                                </div>
                                                <h4>
                                                    <a href="{{ route('courses.show', $course->id) }}">{{ $course->title }}</a>
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
                                                                <span>({{ $course->rating_count ?? '0' }})</span>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <i class="fas fa-user"></i> {{ $course->students_count ?? 0 }} Students
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="bottom-meta">
                                                    <a href="{{ route('courses.show', $course->id) }}">Enroll Now <i class="fas fa-long-arrow-right"></i></a>
                                                    <h2 class="price">
                                                        @if($course->old_price)
                                                            <del>${{ number_format($course->old_price, 2) }}</del>
                                                        @endif
                                                        ${{ number_format($course->price, 2) }}
                                                    </h2>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Single Course Item -->
                                    @empty
                                        <div class="alert alert-info text-center">
                                            No courses found in <strong>{{ $category->name }}</strong>.
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
                </div>
            </div>
        </div>
    </div>
    <!-- End Course -->
@endsection