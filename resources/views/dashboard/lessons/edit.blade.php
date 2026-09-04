@extends('dashboard.layouts.master')

@section('title', 'Edit Lesson - MIFFA')

@section('content')
<main class="main-content" id="main-content">
    <div class="section__content section__content--p30">
        <div class="row row-tight" style="margin-top: 16px;">
            <div class="col-md-10 offset-md-1">
                <section class="m-card">
                    <header class="m-card__header d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="m-card__title">Edit Lesson</h2>
                            <p class="m-card__subtitle">Update video, text, document, homework, or quiz content</p>
                        </div>
                        <a href="{{ route('admin.lessons.index', array_filter(['section_id' => $lesson->section_id ?? request('section_id')])) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back to Lessons
                        </a>
                    </header>

                    <form action="{{ route('admin.lessons.update', $lesson->id) }}" method="POST" enctype="multipart/form-data" class="p-3">
                        @csrf
                        @method('PUT')

                        <!-- Global Error Display -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                <strong class="d-block mb-2"><i class="fa-solid fa-triangle-exclamation me-1"></i> Form Submission Failed:</strong>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row g-3">
                            <!-- Section Selection -->
                            <div class="col-md-8">
                                <label for="section_id" class="form-label fw-bold">Section <span class="text-danger">*</span></label>
                                <select name="section_id" id="section_id" class="form-select @error('section_id') is-invalid @enderror" required>
                                    <option value="" disabled>Select a Section</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section->id }}" {{ old('section_id', $lesson->section_id) == $section->id ? 'selected' : '' }}>
                                            {{ $section->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('section_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Display Order -->
                            <div class="col-md-4">
                                <label for="order" class="form-label fw-bold">Display Order</label>
                                <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', $lesson->order ?? 1) }}" min="1">
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Lesson Title -->
                            <div class="col-md-8">
                                <label for="title" class="form-label fw-bold">Lesson Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $lesson->title) }}" placeholder="e.g. Introduction to Freight Forwarding" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Content Type -->
                            <div class="col-md-4">
                                <label for="type" class="form-label fw-bold">Content Type <span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required onchange="toggleLessonTypeFields()">
                                    <option value="article" {{ old('type', $lesson->type) == 'article' ? 'selected' : '' }}>Article</option>
                                    <option value="video" {{ old('type', $lesson->type) == 'video' ? 'selected' : '' }}>Video</option>
                                    <option value="document" {{ old('type', $lesson->type) == 'document' ? 'selected' : '' }}>Document</option>
                                    <option value="homework" {{ old('type', $lesson->type) == 'homework' ? 'selected' : '' }}>Homework / Assignment</option>
                                    <option value="quiz" {{ old('type', $lesson->type) == 'quiz' ? 'selected' : '' }}>Quiz</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Video Section -->
                            <div class="col-md-12 type-field field-video" style="display: none;">
                                <label for="video_url" class="form-label fw-bold">Video URL</label>
                                <input type="url" name="video_url" id="video_url" class="form-control @error('video_url') is-invalid @enderror" value="{{ old('video_url', $lesson->video_url) }}" placeholder="https://www.youtube.com/watch?v=...">
                                @error('video_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Document Section -->
                            <div class="col-md-12 type-field field-document" style="display: none;">
                                <label for="document_file" class="form-label fw-bold">Document Attachment (PDF, DOC, PPT)</label>
                                @if(!empty($lesson->document_file_path))
                                    <div class="mb-2">
                                        <span class="badge bg-secondary me-2"><i class="fa-solid fa-paperclip me-1"></i> Current File</span>
                                        <a href="{{ Storage::url($lesson->document_file_path) }}" target="_blank" class="small text-decoration-underline">View Attached Document</a>
                                    </div>
                                @endif
                                <input type="file" name="document_file" id="document_file" class="form-control @error('document_file') is-invalid @enderror">
                                <small class="text-muted d-block">Leave blank to keep current file</small>
                                @error('document_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Homework Attachment Section -->
                            <div class="col-md-12 type-field field-homework" style="display: none;">
                                <div class="alert alert-info py-2 mb-2">
                                    <i class="fa-solid fa-circle-info me-1"></i>
                                    Students will be prompted to submit their file (Excel, Word, PDF, PowerPoint) when viewing this lesson.
                                </div>
                                <label for="homework_file" class="form-label fw-bold">Homework Reference/Template File (Optional)</label>
                                @if(!empty($lesson->homework_file_path))
                                    <div class="mb-2">
                                        <span class="badge bg-secondary me-2"><i class="fa-solid fa-file-arrow-down me-1"></i> Current File</span>
                                        <a href="{{ Storage::url($lesson->homework_file_path) }}" target="_blank" class="small text-decoration-underline">View Reference File</a>
                                    </div>
                                @endif
                                <input type="file" name="homework_file" id="homework_file" class="form-control @error('homework_file') is-invalid @enderror" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                                <small class="text-muted">Allowed formats for initial download: PDF, Word, Excel, PowerPoint. Leave blank to keep current file.</small>
                                @error('homework_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Content Text Area / Instructions -->
                            <div class="col-md-12 type-field field-content" style="display: none;">
                                <label for="content" id="content-label" class="form-label fw-bold">Lesson Content / Instructions</label>
                                <textarea name="content" id="content" rows="5" class="form-control @error('content') is-invalid @enderror" placeholder="Enter full body text or instructions...">{{ old('content', $lesson->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Quiz Questions Section -->
                            <div class="col-md-12 type-field field-quiz" style="display: none;">
                                <hr class="my-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="fw-bold mb-0">Quiz Questions</h4>
                                    <button type="button" class="btn btn-outline-success btn-sm" onclick="addQuestionBlock()">
                                        <i class="fa-solid fa-plus me-1"></i> Add Question
                                    </button>
                                </div>
                                <div id="questions-container" class="d-flex flex-column gap-3"></div>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fa-solid fa-save me-1"></i> Update Lesson
                            </button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</main>

<script>
let questionCount = 0;

function toggleLessonTypeFields() {
    const type = document.getElementById('type').value;
    const contentLabel = document.getElementById('content-label');

    // Hide all dynamic field containers
    document.querySelectorAll('.type-field').forEach(el => el.style.display = 'none');

    // Display fields based on selection
    if (type === 'video') {
        document.querySelector('.field-video').style.display = 'block';
        document.querySelector('.field-content').style.display = 'block';
        contentLabel.textContent = 'Lesson Instructions / Notes';
    } else if (type === 'document') {
        document.querySelector('.field-document').style.display = 'block';
        document.querySelector('.field-content').style.display = 'block';
        contentLabel.textContent = 'Document Description / Notes';
    } else if (type === 'article') {
        document.querySelector('.field-content').style.display = 'block';
        contentLabel.textContent = 'Lesson Content';
    } else if (type === 'homework') {
        document.querySelector('.field-homework').style.display = 'block';
        document.querySelector('.field-content').style.display = 'block';
        contentLabel.textContent = 'Homework Task Instructions';
    } else if (type === 'quiz') {
        document.querySelector('.field-quiz').style.display = 'block';
    }
}

function addQuestionBlock(data = null) {
    const container = document.getElementById('questions-container');
    const qIndex = questionCount++;

    const text = data ? (data.text || '') : '';
    const qType = data ? (data.type || 'multiple_choice') : 'multiple_choice';
    const hint = data ? (data.hint || '') : '';
    const correctOpt = data ? parseInt(data.correct_option || 0) : 0;
    const isCorrect = data ? (data.is_correct !== undefined ? data.is_correct : '1') : '1';

    const options = data && data.options ? data.options : ['', '', '', ''];
    const optionFeedbacks = data && data.option_feedbacks ? data.option_feedbacks : ['', '', '', ''];

    const trueFeedback = data ? (data.true_feedback || '') : '';
    const falseFeedback = data ? (data.false_feedback || '') : '';

    const qHtml = `
        <div class="card border p-3 question-card bg-light" id="question-${qIndex}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold text-primary">Question #${qIndex + 1}</span>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeQuestionBlock(${qIndex})">
                    <i class="fa-solid fa-trash me-1"></i> Delete
                </button>
            </div>
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label small fw-bold">Question Text <span class="text-danger">*</span></label>
                    <input type="text" name="questions[${qIndex}][text]" class="form-control" value="${escapeHtml(text)}" placeholder="e.g. What is a banana?" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Question Type</label>
                    <select name="questions[${qIndex}][type]" class="form-select" onchange="toggleQuestionType(${qIndex}, this.value)">
                        <option value="multiple_choice" ${qType === 'multiple_choice' ? 'selected' : ''}>Multiple Choice</option>
                        <option value="boolean" ${qType === 'boolean' ? 'selected' : ''}>True / False</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label small fw-bold text-muted">
                        <i class="fa-solid fa-lightbulb text-warning me-1"></i> Question Hint (Optional)
                    </label>
                    <input type="text" name="questions[${qIndex}][hint]" class="form-control form-control-sm" value="${escapeHtml(hint)}" placeholder="Clue offered before answering">
                </div>

                <div class="col-12 q-options-block-${qIndex}" style="${qType === 'boolean' ? 'display:none;' : ''}">
                    <label class="form-label small fw-bold">Options & Specific Feedback (Select radio for correct answer):</label>
                    
                    ${[0, 1, 2, 3].map(i => `
                        <div class="border rounded p-2 mb-2 bg-white">
                            <div class="input-group mb-1">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" name="questions[${qIndex}][correct_option]" value="${i}" ${correctOpt === i ? 'checked' : ''}>
                                </div>
                                <input type="text" name="questions[${qIndex}][options][${i}]" class="form-control fw-semibold" value="${escapeHtml(options[i] || '')}" placeholder="Option ${i + 1}">
                            </div>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light text-secondary">
                                    <i class="fa-solid fa-comment-dots me-1"></i> Option Feedback
                                </span>
                                <input type="text" name="questions[${qIndex}][option_feedbacks][${i}]" class="form-control" value="${escapeHtml(optionFeedbacks[i] || '')}" placeholder="Why this option is right or wrong">
                            </div>
                        </div>
                    `).join('')}
                </div>

                <div class="col-12 q-boolean-block-${qIndex}" style="${qType === 'boolean' ? '' : 'display:none;'}">
                    <label class="form-label small fw-bold">Correct Answer:</label>
                    <select name="questions[${qIndex}][is_correct]" class="form-select mb-2">
                        <option value="1" ${isCorrect == '1' ? 'selected' : ''}>True</option>
                        <option value="0" ${isCorrect == '0' ? 'selected' : ''}>False</option>
                    </select>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Feedback if True is selected:</label>
                            <input type="text" name="questions[${qIndex}][true_feedback]" class="form-control form-control-sm" value="${escapeHtml(trueFeedback)}" placeholder="Explanation when student selects True">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Feedback if False is selected:</label>
                            <input type="text" name="questions[${qIndex}][false_feedback]" class="form-control form-control-sm" value="${escapeHtml(falseFeedback)}" placeholder="Explanation when student selects False">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', qHtml);
}

function removeQuestionBlock(qIndex) {
    document.getElementById(`question-${qIndex}`).remove();
}

function toggleQuestionType(qIndex, type) {
    const optBlock = document.querySelector(`.q-options-block-${qIndex}`);
    const boolBlock = document.querySelector(`.q-boolean-block-${qIndex}`);
    if (type === 'boolean') {
        optBlock.style.display = 'none';
        boolBlock.style.display = 'block';
    } else {
        optBlock.style.display = 'block';
        boolBlock.style.display = 'none';
    }
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

document.addEventListener('DOMContentLoaded', () => {
    toggleLessonTypeFields();

    // Priority 1: Check validation old input. Priority 2: Fallback to database existing questions.
    const oldQuestions = @json(old('questions', null));
    const dbQuestions = @json($lesson->questions ?? []);

    const questionsToLoad = oldQuestions !== null ? Object.values(oldQuestions) : dbQuestions;

    if (questionsToLoad && questionsToLoad.length > 0) {
        questionsToLoad.forEach(qData => {
            addQuestionBlock(qData);
        });
    }
});
</script>
@endsection