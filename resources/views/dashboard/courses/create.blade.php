@extends('dashboard.layouts.master')

@section('title', 'Add New Course - MIFFA')

@section('content')
<main class="main-content" id="main-content">
    <div class="section__content section__content--p30">
        <div class="row row-tight" style="margin-top: 16px;">
            <div class="col-md-12">
                <section class="m-card" aria-labelledby="add-course-title">
                    <header class="m-card__header d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="m-card__title" id="add-course-title">Add New Course</h2>
                            <p class="m-card__subtitle">Fill in the details below to create a new course listing</p>
                        </div>
                        <div>
                            <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                                <i class="fa-solid fa-arrow-left"></i> Back to Courses
                            </a>
                        </div>
                    </header>

                    <div class="m-card__body p-4">
                        <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row g-3">
                                <!-- Course Title -->
                                <div class="col-md-8">
                                    <label for="title" class="form-label fw-semibold">Course Title <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title') }}" 
                                           placeholder="e.g. Advanced Freight Forwarding Management" 
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Course Code -->
                                <div class="col-md-4">
                                    <label for="code" class="form-label fw-semibold">Course Code <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('code') is-invalid @enderror" 
                                           id="code" 
                                           name="code" 
                                           value="{{ old('code') }}" 
                                           placeholder="e.g. FFM-101"
                                           required>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Category Selection -->
                                <div class="col-md-6">
                                    <label for="category_id" class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" 
                                            id="category_id" 
                                            name="category_id" 
                                            required>
                                        <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Total Hours -->
                                <div class="col-md-6">
                                    <label for="hour" class="form-label fw-semibold">Total Hours <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           min="1" 
                                           class="form-control @error('hour') is-invalid @enderror" 
                                           id="hour" 
                                           name="hour" 
                                           value="{{ old('hour') }}" 
                                           placeholder="e.g. 40"
                                           required>
                                    @error('hour')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Assign Course Admins (Multi-Select) -->
                                <div class="col-md-6">
                                    <label for="admin_ids" class="form-label fw-semibold">Assign Course Admins</label>
                                    <select class="form-select @error('admin_ids') is-invalid @enderror @error('admin_ids.*') is-invalid @enderror" 
                                            id="admin_ids" 
                                            name="admin_ids[]" 
                                            multiple 
                                            style="min-height: 120px;">
                                        @foreach($admins as $admin)
                                            <option value="{{ $admin->id }}" {{ is_array(old('admin_ids')) && in_array($admin->id, old('admin_ids')) ? 'selected' : '' }}>
                                                {{ $admin->name }} ({{ $admin->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted d-block mt-1">Hold Ctrl (Cmd on Mac) to select multiple admins.</small>
                                    @error('admin_ids')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @error('admin_ids.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Assign Instructors (Multi-Select) -->
                                <div class="col-md-6">
                                    <label for="instructor_ids" class="form-label fw-semibold">Assign Instructors</label>
                                    <select class="form-select @error('instructor_ids') is-invalid @enderror @error('instructor_ids.*') is-invalid @enderror" 
                                            id="instructor_ids" 
                                            name="instructor_ids[]" 
                                            multiple 
                                            style="min-height: 120px;">
                                        @foreach($instructors as $instructor)
                                            <option value="{{ $instructor->id }}" {{ is_array(old('instructor_ids')) && in_array($instructor->id, old('instructor_ids')) ? 'selected' : '' }}>
                                                {{ $instructor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted d-block mt-1">Hold Ctrl (Cmd on Mac) to select multiple instructors.</small>
                                    @error('instructor_ids')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @error('instructor_ids.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Standard Price -->
                                <div class="col-md-6">
                                    <label for="price" class="form-label fw-semibold">Standard Price (Non-Member)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">MMK</span>
                                        <input type="number" 
                                               step="0.01" 
                                               class="form-control @error('price') is-invalid @enderror" 
                                               id="price" 
                                               name="price" 
                                               value="{{ old('price') }}" 
                                               placeholder="150000.00">
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Member Price -->
                                <div class="col-md-6">
                                    <label for="member_price" class="form-label fw-semibold">Member Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">MMK</span>
                                        <input type="number" 
                                               step="0.01" 
                                               class="form-control @error('member_price') is-invalid @enderror" 
                                               id="member_price" 
                                               name="member_price" 
                                               value="{{ old('member_price') }}" 
                                               placeholder="120000.00">
                                    </div>
                                    @error('member_price')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Image Upload -->
                                <div class="col-md-12">
                                    <label for="image" class="form-label fw-semibold">Course Thumbnail Image</label>
                                    <input type="file" 
                                           class="form-control @error('image') is-invalid @enderror" 
                                           id="image" 
                                           name="image" 
                                           accept="image/*"
                                           onchange="previewCourseImage(event)">
                                    <div class="form-text">Supported formats: JPG, PNG, WEBP (Max: 2MB)</div>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <div class="mt-2" id="image-preview-wrapper" style="display: none;">
                                        <img id="image-preview" src="#" alt="Course Image Preview" class="img-thumbnail rounded" style="max-height: 150px; object-fit: cover;">
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-md-12">
                                    <label for="desc" class="form-label fw-semibold">Description</label>
                                    <textarea class="form-control @error('desc') is-invalid @enderror" 
                                              id="desc" 
                                              name="desc" 
                                              rows="5" 
                                              placeholder="Provide a detailed overview of the course curriculum...">{{ old('desc') }}</textarea>
                                    @error('desc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="mt-4 d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.courses.index') }}" class="btn btn-light border">Cancel</a>
                                <button type="submit" class="au-btn au-btn--green au-btn--small d-inline-flex align-items-center gap-1 border-0">
                                    <i class="fa-solid fa-save"></i> Save Course
                                </button>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
function previewCourseImage(event) {
    const input = event.target;
    const previewWrapper = document.getElementById('image-preview-wrapper');
    const previewImage = document.getElementById('image-preview');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewWrapper.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        previewWrapper.style.display = 'none';
    }
}
</script>
@endpush