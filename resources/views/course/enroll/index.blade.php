@extends('layouts.master')

@section('title', 'Enroll - ' . $course->title . ' - MIFFA')

@section('content')
<!-- Start Breadcrumb -->
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
                                <p class="text-muted small mb-0">Fill in your details to complete your course enrollment</p>
                            </div>
                        </div>

                        {{-- Alert Messages --}}
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('enroll.store', $course->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Full Name (Readonly) -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Full Name <span class="text-muted">(Locked)</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                                    <input type="text" 
                                           class="form-control border-start-0 ps-0 bg-light text-muted" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', auth()->user()->name ?? '') }}" 
                                           readonly>
                                </div>
                            </div>

                            <!-- Email Address (Readonly) -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email Address <span class="text-muted">(Locked)</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                                    <input type="email" 
                                           class="form-control border-start-0 ps-0 bg-light text-muted" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', auth()->user()->email ?? '') }}" 
                                           readonly>
                                </div>
                            </div>

                            <!-- Phone Number -->
                            <div class="mb-3">
                                <label for="phone" class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-phone"></i></span>
                                    <input type="tel" 
                                           class="form-control border-start-0 ps-0 @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone') }}" 
                                           placeholder="09123456789" 
                                           required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Gender & Membership Status -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="gender" class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-venus-mars"></i></span>
                                        <select class="form-select border-start-0 ps-0 @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                            <option value="" disabled selected>Select Gender</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="membership_status" class="form-label fw-semibold">MIFFA Membership <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-id-card"></i></span>
                                        <select class="form-select border-start-0 ps-0 @error('membership_status') is-invalid @enderror" id="membership_status" name="membership_status" required>
                                            <option value="" disabled selected>Select Status</option>
                                            <option value="member" {{ old('membership_status') == 'member' ? 'selected' : '' }}>Member</option>
                                            <option value="non-member" {{ old('membership_status') == 'non-member' ? 'selected' : '' }}>Non-Member</option>
                                        </select>
                                        @error('membership_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- NRC Number -->
                            <div class="mb-3">
                                <label for="nrc_number" class="form-label fw-semibold">NRC / Identity Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-address-card"></i></span>
                                    <input type="text" 
                                           class="form-control border-start-0 ps-0 @error('nrc_number') is-invalid @enderror" 
                                           id="nrc_number" 
                                           name="nrc_number" 
                                           value="{{ old('nrc_number') }}" 
                                           placeholder="e.g. 12/YAGANA(N)123456" 
                                           required>
                                    @error('nrc_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Company & Job Title -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="company" class="form-label fw-semibold">Company Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-building"></i></span>
                                        <input type="text" 
                                               class="form-control border-start-0 ps-0 @error('company') is-invalid @enderror" 
                                               id="company" 
                                               name="company" 
                                               value="{{ old('company') }}" 
                                               placeholder="Company Name" 
                                               required>
                                        @error('company')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="job_title" class="form-label fw-semibold">Job Title / Position <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-briefcase"></i></span>
                                        <input type="text" 
                                               class="form-control border-start-0 ps-0 @error('job_title') is-invalid @enderror" 
                                               id="job_title" 
                                               name="job_title" 
                                               value="{{ old('job_title') }}" 
                                               placeholder="e.g. Logistics Manager" 
                                               required>
                                        @error('job_title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Passport Photo Upload -->
                            <div class="mb-4">
                                <label for="passport_photo" class="form-label fw-semibold">Passport Photo <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-file-image"></i></span>
                                    <input type="file" 
                                           class="form-control border-start-0 ps-0 @error('passport_photo') is-invalid @enderror" 
                                           id="passport_photo" 
                                           name="passport_photo" 
                                           accept="image/*" 
                                           required>
                                    @error('passport_photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">Upload a clear passport-style photo (JPEG, PNG / max 2MB).</div>
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