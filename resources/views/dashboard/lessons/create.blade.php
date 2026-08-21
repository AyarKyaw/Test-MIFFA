@extends('dashboard.layouts.master')

@section('title', 'Create Lesson - MIFFA')

@section('content')
<main class="main-content" id="main-content">
    <div class="section__content section__content--p30">
        <div class="row row-tight" style="margin-top: 16px;">
            <div class="col-md-10 offset-md-1">
                <section class="m-card">
                    <header class="m-card__header d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="m-card__title">Add New Lesson</h2>
                            <p class="m-card__subtitle">Create and assign a lesson module to an existing course</p>
                        </div>
                    </header>

                    <div class="p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.lessons.store') }}" method="POST" id="lesson-form">
                            @csrf

                            <!-- Course Selection -->
                            <div class="mb-3">
                                <label for="course_id" class="form-label fw-semibold text-dark">Course <span class="text-danger">*</span></label>
                                <select name="course_id" id="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                                    <option value="" disabled {{ old('course_id', request('course_id')) ? '' : 'selected' }}>-- Select Course --</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id', request('course_id')) == $course->id ? 'selected' : '' }}>
                                            {{ $course->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Lesson Title -->
                            <div class="mb-3">
                                <label for="title" class="form-label fw-semibold text-dark">Lesson Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="e.g., Introduction to Logistics" required>
                            </div>

                            <!-- Type & Order Grid -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="type" class="form-label fw-semibold text-dark">Lesson Type <span class="text-danger">*</span></label>
                                    <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                        <option value="video" {{ old('type', 'video') == 'video' ? 'selected' : '' }}>Video</option>
                                        <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>Text / Article</option>
                                        <option value="document" {{ old('type') == 'document' ? 'selected' : '' }}>Document</option>
                                        <option value="quiz" {{ old('type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="order" class="form-label fw-semibold text-dark">Order Index</label>
                                    <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', 1) }}" min="0">
                                </div>
                            </div>

                            {{-- Render Partials --}}
                            @include('dashboard.lessons.partials._video_fields')
                            @include('dashboard.lessons.partials._document_fields')
                            @include('dashboard.lessons.partials._content_fields')
                            @include('dashboard.lessons.partials._quiz_fields')

                            <!-- Actions -->
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('admin.courses.index') }}" class="btn btn-light border">Cancel</a>
                                <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-save me-1"></i> Save Lesson</button>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('type');
    const fieldVideo = document.getElementById('field-video');
    const fieldDocument = document.getElementById('field-document');
    const fieldContent = document.getElementById('field-content');
    const fieldQuiz = document.getElementById('field-quiz');
    const contentLabel = document.getElementById('content-label');

    const videoInput = document.getElementById('video_url');
    const documentInput = document.getElementById('document_url');

    function toggleFields() {
        const type = typeSelect.value;

        // Reset visibility
        if (fieldVideo) fieldVideo.style.display = 'none';
        if (fieldDocument) fieldDocument.style.display = 'none';
        if (fieldQuiz) fieldQuiz.style.display = 'none';
        if (fieldContent) fieldContent.style.display = 'block';

        // Toggle HTML required attribute to prevent form submission blocks on hidden inputs
        if (videoInput) videoInput.removeAttribute('required');
        if (documentInput) documentInput.removeAttribute('required');

        if (type === 'video') {
            if (fieldVideo) fieldVideo.style.display = 'block';
            if (videoInput) videoInput.setAttribute('required', 'required');
            if (contentLabel) contentLabel.innerHTML = 'Video Summary / Notes <span class="text-muted">(Optional)</span>';
        } else if (type === 'text') {
            if (contentLabel) contentLabel.innerHTML = 'Article Body Content <span class="text-danger">*</span>';
        } else if (type === 'document') {
            if (fieldDocument) fieldDocument.style.display = 'block';
            if (documentInput) documentInput.setAttribute('required', 'required');
            if (contentLabel) contentLabel.innerHTML = 'Document Overview / Instructions <span class="text-muted">(Optional)</span>';
        } else if (type === 'quiz') {
            if (fieldQuiz) fieldQuiz.style.display = 'block';
            if (contentLabel) contentLabel.innerHTML = 'Quiz Instructions / Overview <span class="text-muted">(Optional)</span>';
            
            window.dispatchEvent(new CustomEvent('quiz-type-selected'));
        }
    }

    typeSelect.addEventListener('change', toggleFields);
    toggleFields();
});
</script>
@endsection