@extends('layouts.master')

@section('title', $instructor->name . ' - Instructor Profile')

@section('content')
<div class="bg-light py-5">
    <div class="container">
        <!-- Instructor Header / Hero Section -->
        <div class="card border-0 shadow-sm overflow-hidden mb-4">
            <div class="card-body p-4 p-md-5">
                <div class="row align-items-center g-4">
                    <div class="col-auto">
                        @if($instructor->image)
                            <img src="{{ asset('storage/' . $instructor->image) }}" 
                                 alt="{{ $instructor->name }}" 
                                 class="rounded-circle img-thumbnail shadow-sm" 
                                 style="width: 140px; height: 140px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center shadow-sm" 
                                 style="width: 140px; height: 140px; font-size: 3rem;">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <div>
                                <h1 class="h2 fw-bold mb-1">{{ $instructor->name }}</h1>
                                <p class="text-primary fw-medium mb-2">
                                    {{ $instructor->title ?? 'Senior Instructor' }}
                                </p>
                            </div>
                            <div class="d-flex gap-2">
                                @if(!empty($instructor->social_links['linkedin']))
                                    <a href="{{ $instructor->social_links['linkedin'] }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" aria-label="LinkedIn">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                @endif
                                @if(!empty($instructor->social_links['twitter']))
                                    <a href="{{ $instructor->social_links['twitter'] }}" target="_blank" class="btn btn-outline-info btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" aria-label="Twitter">
                                        <i class="fab fa-x-twitter"></i>
                                    </a>
                                @endif
                                @if(!empty($instructor->email))
                                    <a href="mailto:{{ $instructor->email }}" class="btn btn-outline-secondary btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" aria-label="Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Stats Strip -->
                        <div class="d-flex flex-wrap gap-4 mt-3 pt-3 border-top">
                            <div>
                                <span class="d-block text-muted small">Courses</span>
                                <span class="h6 mb-0 fw-bold">{{ $instructor->courses_count ?? $instructor->courses->count() }}</span>
                            </div>
                            <div>
                                <span class="d-block text-muted small">Total Students</span>
                                <span class="h6 mb-0 fw-bold">{{ number_format($instructor->students_count ?? 0) }}</span>
                            </div>
                            <div>
                                <span class="d-block text-muted small">Rating</span>
                                <span class="h6 mb-0 fw-bold text-warning">
                                    <i class="fas fa-star"></i> {{ number_format($instructor->rating ?? 4.9, 1) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column: Bio & Qualifications -->
            <div class="col-lg-8">
                <!-- Biography Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h3 class="h5 fw-bold border-bottom pb-3 mb-3">About the Instructor</h3>
                        <div class="text-secondary lh-base" style="white-space: pre-line;">
                            {{ $instructor->bio ?? 'No biography details available for this instructor.' }}
                        </div>
                    </div>
                </div>

                <!-- Instructor's Courses Section -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="h5 fw-bold border-bottom pb-3 mb-4">
                            Courses Taught by {{ $instructor->name }}
                        </h3>

                        @if(isset($instructor->courses) && $instructor->courses->count() > 0)
                            <div class="row row-cols-1 row-cols-md-2 g-4">
                                @foreach($instructor->courses as $course)
                                    <div class="col">
                                        <div class="card h-100 border border-light shadow-sm hover-shadow transition">
                                            @if($course->image)
                                                <img src="{{ asset('storage/' . $course->image) }}" class="card-img-top" alt="{{ $course->title }}" style="height: 160px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary text-white text-center py-5 card-img-top">
                                                    <i class="fas fa-book-open fa-2x"></i>
                                                </div>
                                            @endif
                                            <div class="card-body d-flex flex-column">
                                                <span class="badge bg-primary-subtle text-primary align-self-start mb-2">
                                                    {{ $course->category->name ?? 'Logistics' }}
                                                </span>
                                                <h4 class="h6 card-title fw-bold text-dark mb-2">
                                                    <a href="{{ route('courses.show', $course->slug ?? $course->id) }}" class="text-decoration-none text-dark">
                                                        {{ $course->title }}
                                                    </a>
                                                </h4>
                                                <p class="card-text text-muted small flex-grow-1">
                                                    {{ Str::limit($course->description, 80) }}
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                                    <span class="text-muted small">
                                                        <i class="far fa-clock me-1"></i>{{ $course->duration ?? 'Self-paced' }}
                                                    </span>
                                                    <a href="{{ route('courses.show', $course->slug ?? $course->id) }}" class="btn btn-sm btn-outline-primary">
                                                        View Course
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                                No active courses available for this instructor at the moment.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar Info -->
            <div class="col-lg-4">
                <!-- Quick Contact / Info Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h4 class="h6 fw-bold text-uppercase text-muted mb-3">Instructor Overview</h4>
                        <ul class="list-unstyled mb-0">
                            <li class="d-flex align-items-center mb-3">
                                <i class="fas fa-briefcase text-primary me-3 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Experience</small>
                                    <span class="fw-medium">{{ $instructor->experience_years ?? '10+' }} Years</span>
                                </div>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="fas fa-graduation-cap text-primary me-3 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Specialization</small>
                                    <span class="fw-medium">{{ $instructor->specialization ?? 'Supply Chain & Logistics' }}</span>
                                </div>
                            </li>
                            <li class="d-flex align-items-center">
                                <i class="fas fa-language text-primary me-3 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Languages</small>
                                    <span class="fw-medium">{{ $instructor->languages ?? 'English, Myanmar' }}</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Action CTA -->
                <div class="card bg-primary text-white border-0 shadow-sm p-3">
                    <div class="card-body text-center">
                        <h4 class="h5 fw-bold mb-2">Have Questions?</h4>
                        <p class="small text-white-50 mb-3">Reach out to our academic team to learn more about training sessions with {{ $instructor->name }}.</p>
                        <a href="{{ Route::has('contact') ? route('contact') : 'mailto:' . ($instructor->email ?? 'support@example.com') }}" class="btn btn-light btn-sm text-primary fw-bold w-100">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection