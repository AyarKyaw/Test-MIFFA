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
                            <p class="m-card__subtitle">Update content details and options</p>
                        </div>
                        <a href="{{ route('admin.lessons.index', ['section_id' => $lesson->section_id]) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back to Lessons
                        </a>
                    </header>

                    <form action="{{ route('admin.lessons.update', $lesson->id) }}" method="POST" enctype="multipart/form-data" class="p-3">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="section_id" class="form-label fw-bold">Section <span class="text-danger">*</span></label>
                                <select name="section_id" id="section_id" class="form-select @error('section_id') is-invalid @enderror" required>
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

                            <div class="col-md-4">
                                <label for="order" class="form-label fw-bold">Display Order</label>
                                <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', $lesson->order) }}" min="0" required>
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-8">
                                <label for="title" class="form-label fw-bold">Lesson Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $lesson->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="type" class="form-label fw-bold">Content Type <span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required onchange="toggleLessonTypeFields()">
                                    <option value="article" {{ old('type', $lesson->type) == 'article' ? 'selected' : '' }}>Article</option>
                                    <option value="video" {{ old('type', $lesson->type) == 'video' ? 'selected' : '' }}>Video</option>
                                    <option value="quiz" {{ old('type', $lesson->type) == 'quiz' ? 'selected' : '' }}>Quiz</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Video Section -->
                            <div class="col-md-12 type-field field-video" style="display: none;">
                                <label for="video_url" class="form-label fw-bold">Video URL</label>
                                <input type="url" name="video_url" id="video_url" class="form-control @error('video_url') is-invalid @enderror" value="{{ old('video_url', $lesson->video_url) }}">
                                @error('video_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Document Section -->
                            <div class="col-md-12 type-field field-document" style="display: none;">
                                <label for="document_file" class="form-label fw-bold">Document File</label>
                                @if($lesson->document_path)
                                    <div class="mb-2">
                                        <a href="{{ Storage::url($lesson->document_path) }}" target="_blank" class="text-success text-decoration-none">
                                            <i class="fa-solid fa-file-pdf me-1"></i> View Existing Document
                                        </a>
                                    </div>
                                @endif
                                <input type="file" name="document_file" id="document_file" class="form-control @error('document_file') is-invalid @enderror">
                                @error('document_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Content Text Area -->
                            <div class="col-md-12 type-field field-text">
                                <label for="content" class="form-label fw-bold">Lesson Content / Instructions</label>
                                <textarea name="content" id="content" rows="5" class="form-control @error('content') is-invalid @enderror">{{ old('content', $lesson->content) }}</textarea>
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
                                <div id="questions-container" class="d-flex flex-column gap-3">
                                    @php
                                        $existingQuestions = old('questions', $lesson->questions);
                                    @endphp
                                    @foreach($existingQuestions as $qIndex => $question)
                                        @php
                                            $isModel = is_object($question);
                                            $qId = $isModel ? $question->id : ($question['id'] ?? null);
                                            $qText = $isModel ? $question->question_text : ($question['text'] ?? '');
                                            $qType = $isModel ? $question->type : ($question['type'] ?? 'multiple_choice');
                                            $qHint = $isModel ? ($question->hint ?? '') : ($question['hint'] ?? '');
                                            $qIsCorrect = $isModel ? $question->is_correct : ($question['is_correct'] ?? '1');
                                            $options = $isModel ? $question->options->values() : collect($question['options'] ?? []);
                                            $selectedCorrect = $isModel ? null : ($question['correct_option'] ?? 0);
                                        @endphp
                                        <div class="card border p-3 question-card" id="question-{{ $qIndex }}" data-index="{{ $qIndex }}">
                                            @if($qId)
                                                <input type="hidden" name="questions[{{ $qIndex }}][id]" value="{{ $qId }}">
                                            @endif
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="fw-bold text-primary question-label">Question #{{ $qIndex + 1 }}</span>
                                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeQuestionBlock({{ $qIndex }})">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                            <div class="row g-2">
                                                <div class="col-md-8">
                                                    <label class="form-label small fw-bold">Question Text</label>
                                                    <input type="text" name="questions[{{ $qIndex }}][text]" class="form-control" value="{{ $qText }}" placeholder="Enter question text" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small fw-bold">Question Type</label>
                                                    <select name="questions[{{ $qIndex }}][type]" class="form-select" onchange="toggleQuestionType({{ $qIndex }}, this.value)">
                                                        <option value="multiple_choice" {{ $qType === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                                                        <option value="boolean" {{ $qType === 'boolean' ? 'selected' : '' }}>True / False</option>
                                                    </select>
                                                </div>

                                                <!-- Question Hint Field -->
                                                <div class="col-12 mt-1">
                                                    <label class="form-label small fw-bold"><i class="fa-regular fa-lightbulb text-warning me-1"></i> Hint (Optional)</label>
                                                    <input type="text" name="questions[{{ $qIndex }}][hint]" class="form-control form-control-sm" value="{{ $qHint }}" placeholder="Provide a helpful clue for students">
                                                </div>

                                                <!-- Multiple Choice Options & Per-Option Feedback -->
                                                <div class="col-12 q-options-block-{{ $qIndex }}" style="{{ $qType === 'boolean' ? 'display:none;' : '' }}">
                                                    <label class="form-label small fw-bold mt-2">Options & Feedback:</label>
                                                    @for($i = 0; $i < 4; $i++)
                                                        @php
                                                            $optObj = $isModel ? $options->get($i) : null;
                                                            $optText = $isModel ? ($optObj->option_text ?? '') : ($options[$i]['text'] ?? $options[$i] ?? '');
                                                            $optFeedback = $isModel ? ($optObj->feedback ?? '') : ($options[$i]['feedback'] ?? '');
                                                            $isCorrectOption = $isModel 
                                                                ? (($optObj && $optObj->is_correct) || ($i == 0 && !$question->options->where('is_correct', true)->count()))
                                                                : ($selectedCorrect == $i);
                                                        @endphp
                                                        <div class="border rounded p-2 mb-2 bg-light">
                                                            <div class="input-group mb-1">
                                                                <div class="input-group-text">
                                                                    <input class="form-check-input mt-0" type="radio" name="questions[{{ $qIndex }}][correct_option]" value="{{ $i }}" {{ $isCorrectOption ? 'checked' : '' }}>
                                                                </div>
                                                                <input type="text" name="questions[{{ $qIndex }}][options][{{ $i }}][text]" class="form-control" value="{{ $optText }}" placeholder="Option {{ $i + 1 }} Text">
                                                            </div>
                                                            <input type="text" name="questions[{{ $qIndex }}][options][{{ $i }}][feedback]" class="form-control form-control-sm text-muted" value="{{ $optFeedback }}" placeholder="Feedback for selecting Option {{ $i + 1 }} (Optional)">
                                                        </div>
                                                    @endfor
                                                </div>

                                                <!-- Boolean Options & Per-Option Feedback -->
                                                <div class="col-12 q-boolean-block-{{ $qIndex }}" style="{{ $qType !== 'boolean' ? 'display:none;' : '' }}">
                                                    <label class="form-label small fw-bold mt-2">Correct Answer & Option Feedback:</label>
                                                    <div class="mb-2">
                                                        <select name="questions[{{ $qIndex }}][is_correct]" class="form-select">
                                                            <option value="1" {{ $qIsCorrect == '1' ? 'selected' : '' }}>True</option>
                                                            <option value="0" {{ $qIsCorrect == '0' ? 'selected' : '' }}>False</option>
                                                        </select>
                                                    </div>
                                                    <div class="row g-2">
                                                        <div class="col-md-6">
                                                            <input type="text" name="questions[{{ $qIndex }}][boolean_feedback][true]" class="form-control form-control-sm" value="{{ $isModel ? ($question->options->where('option_text', 'True')->first()->feedback ?? '') : ($question['boolean_feedback']['true'] ?? '') }}" placeholder="Feedback if True is selected">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" name="questions[{{ $qIndex }}][boolean_feedback][false]" class="form-control form-control-sm" value="{{ $isModel ? ($question->options->where('option_text', 'False')->first()->feedback ?? '') : ($question['boolean_feedback']['false'] ?? '') }}" placeholder="Feedback if False is selected">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
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
let questionCount = {{ count(old('questions', $lesson->questions)) }};

function toggleLessonTypeFields() {
    const type = document.getElementById('type').value;
    
    document.querySelector('.field-video').style.display = (type === 'video') ? 'block' : 'none';
    document.querySelector('.field-document').style.display = (type === 'document') ? 'block' : 'none';
    document.querySelector('.field-quiz').style.display = (type === 'quiz') ? 'block' : 'none';
    document.querySelector('.field-text').style.display = (type !== 'quiz') ? 'block' : 'none';
}

function addQuestionBlock() {
    const container = document.getElementById('questions-container');
    const qIndex = questionCount++;

    const qHtml = `
        <div class="card border p-3 question-card" id="question-${qIndex}" data-index="${qIndex}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold text-primary question-label">Question #${container.children.length + 1}</span>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeQuestionBlock(${qIndex})">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
            <div class="row g-2">
                <div class="col-md-8">
                    <label class="form-label small fw-bold">Question Text</label>
                    <input type="text" name="questions[${qIndex}][text]" class="form-control" placeholder="Enter question text" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Question Type</label>
                    <select name="questions[${qIndex}][type]" class="form-select" onchange="toggleQuestionType(${qIndex}, this.value)">
                        <option value="multiple_choice">Multiple Choice</option>
                        <option value="boolean">True / False</option>
                    </select>
                </div>

                <div class="col-12 mt-1">
                    <label class="form-label small fw-bold"><i class="fa-regular fa-lightbulb text-warning me-1"></i> Hint (Optional)</label>
                    <input type="text" name="questions[${qIndex}][hint]" class="form-control form-control-sm" placeholder="Provide a helpful clue for students">
                </div>

                <div class="col-12 q-options-block-${qIndex}">
                    <label class="form-label small fw-bold mt-2">Options & Feedback:</label>
                    ${[0, 1, 2, 3].map(i => `
                        <div class="border rounded p-2 mb-2 bg-light">
                            <div class="input-group mb-1">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" name="questions[${qIndex}][correct_option]" value="${i}" ${i === 0 ? 'checked' : ''}>
                                </div>
                                <input type="text" name="questions[${qIndex}][options][${i}][text]" class="form-control" placeholder="Option ${i + 1} Text">
                            </div>
                            <input type="text" name="questions[${qIndex}][options][${i}][feedback]" class="form-control form-control-sm text-muted" placeholder="Feedback for selecting Option ${i + 1} (Optional)">
                        </div>
                    `).join('')}
                </div>

                <div class="col-12 q-boolean-block-${qIndex}" style="display:none;">
                    <label class="form-label small fw-bold mt-2">Correct Answer & Option Feedback:</label>
                    <div class="mb-2">
                        <select name="questions[${qIndex}][is_correct]" class="form-select">
                            <option value="1">True</option>
                            <option value="0">False</option>
                        </select>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <input type="text" name="questions[${qIndex}][boolean_feedback][true]" class="form-control form-control-sm" placeholder="Feedback if True is selected">
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="questions[${qIndex}][boolean_feedback][false]" class="form-control form-control-sm" placeholder="Feedback if False is selected">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', qHtml);
}

function removeQuestionBlock(qIndex) {
    const card = document.getElementById(`question-${qIndex}`);
    if (card) {
        card.remove();
        reindexQuestions();
    }
}

function reindexQuestions() {
    const cards = document.querySelectorAll('#questions-container .question-card');
    cards.forEach((card, index) => {
        const label = card.querySelector('.question-label');
        if (label) {
            label.textContent = `Question #${index + 1}`;
        }
    });
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

document.addEventListener('DOMContentLoaded', toggleLessonTypeFields);
</script>
@endsection