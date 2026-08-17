@extends('layouts.master')

@section('title', 'Enroll - ' . $course->title . ' - MIFFA')

@section('content')
<!-- Start Breadcrumb 
============================================= -->
<div class="breadcrumb-area text-center bg-gray-gradient-secondary">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h1>Course Registration</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li><a href="/"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="{{ route('admin.course.index') }}">Courses</a></li>
                        <li><a href="{{ route('courses.show', $course->id) }}">{{ $course->title }}</a></li>
                        <li class="active">Enrollment</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumb -->

<!-- Start Enrollment Form Area -->
<div class="enrollment-area py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center g-4">
            <!-- Left Side: Enrollment Form -->
            <div class="col-lg-7 col-md-10">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; min-width: 48px;">
                                <i class="fas fa-user-graduate fa-lg"></i>
                            </div>
                            <div>
                                <h3 class="mb-0 fw-bold">Student Registration</h3>
                                <p class="text-muted small mb-0">Fill in your information to complete your enrollment</p>
                            </div>
                        </div>

                        {{-- Alert Messages --}}
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('enroll.store', $course->id) }}" method="POST">
                            @csrf

                            <!-- Full Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-user"></i></span>
                                    <input type="text" 
                                           class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', auth()->user()->name ?? '') }}" 
                                           placeholder="e.g. John Doe" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email Address -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                                    <input type="email" 
                                           class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', auth()->user()->email ?? '') }}" 
                                           placeholder="name@example.com" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Phone Number -->
                            <div class="mb-4">
                                <label for="phone" class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-phone"></i></span>
                                    <input type="tel" 
                                           class="form-control border-start-0 ps-0 @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', auth()->user()->phone ?? '') }}" 
                                           placeholder="09123456789" 
                                           required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-gradient w-100 py-3 fw-bold d-flex align-items-center justify-content-center gap-2">
                                <i class="fas fa-check-circle"></i> Complete Enrollment
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Side: Course Summary Card -->
            <div class="col-lg-4 col-md-10">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    @if(!empty($course->image_path))
                        <img src="{{ asset($course->image_path) }}" class="card-img-top" alt="{{ $course->title }}" style="height: 180px; object-fit: cover;">
                    @endif
                    <div class="card-body p-4">
                        <span class="badge bg-primary-soft text-primary px-3 py-2 rounded-pill small mb-2">
                            {{ $course->category?->name ?? 'General' }}
                        </span>
                        <h4 class="fw-bold text-dark mb-3">{{ $course->title }}</h4>

                        <div class="bg-light p-3 rounded-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted"><i class="fas fa-barcode me-2"></i>Course Code</span>
                                <span class="fw-bold">{{ $course->code ?? 'N/A' }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted"><i class="fas fa-clock me-2"></i>Duration</span>
                                <span class="fw-bold">{{ $course->hour ?? 0 }} Hours</span>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between align-items-center pt-1">
                                <span class="fw-bold text-dark">Total Fee</span>
                                <span class="fs-4 fw-bold text-success">
                                    {{ isset($course->price) && $course->price > 0 ? '$' . number_format($course->price, 2) : 'Free' }}
                                </span>
                            </div>
                        </div>

                        <ul class="list-unstyled text-muted small mb-0">
                            <li class="mb-2"><i class="fas fa-shield-alt text-success me-2"></i> Secure registration process</li>
                            <li class="mb-2"><i class="fas fa-certificate text-success me-2"></i> MIFFA certification upon completion</li>
                            <li><i class="fas fa-headset text-success me-2"></i> Instant enrollment confirmation</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Enrollment Form Area -->
@endsection