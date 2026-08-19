@push('styles')
<style>
    .option-label {
        cursor: pointer !important;
        transition: all 0.2s ease-in-out;
        user-select: none;
    }
    .option-label:hover {
        border-color: #0d6efd !important;
        background-color: #f8f9fa !important;
    }
    .option-label.active-option {
        border-color: #0d6efd !important;
        background-color: #e7f1ff !important;
    }
    .option-label.correct-option {
        border-color: #198754 !important;
        background-color: #d1e7dd !important;
        color: #0f5132;
    }
    .option-label.incorrect-option {
        border-color: #dc3545 !important;
        background-color: #f8d7da !important;
        color: #842029;
    }
</style>
@endpush

<div class="p-4 p-md-5">
    <!-- Final Quiz Completion State -->
    <div id="quizCompletedCard" class="text-center py-4" style="display: none;">
        <div class="mb-3" id="resultIcon"></div>
        <h3 class="fw-bold mt-3" id="resultTitle"></h3>
        <p class="fs-5 text-muted mb-4" id="resultScore"></p>
        <div id="quizCompletedActions">
            <a href="{{ route('courses.learn', [$course->id, $lesson->id]) }}" class="btn btn-outline-primary rounded-3 px-4">
                <i class="fas fa-redo me-1"></i> Retake Quiz
            </a>
        </div>
    </div>
    @php
        $quizQuestions = $questions ?? $lesson->questions;
    @endphp
    <!-- Active Quiz Form -->
    <form id="quizForm" action="{{ route('courses.lessons.submit', [$course->id, $lesson->id]) }}" method="POST">
        @csrf
        @forelse($quizQuestions as $qIndex => $question)
            <div class="quiz-step" data-step="{{ $qIndex }}" data-question-id="{{ $question->id }}" style="{{ $qIndex !== 0 ? 'display: none;' : '' }}">
                
                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill mb-3">
                    Question {{ $qIndex + 1 }} of {{ $lesson->questions->count() }}
                </span>

                <h6 class="fw-bold text-dark mb-4 fs-5">{{ $question->question_text }}</h6>

                <!-- Feedback Alert Container -->
                <div class="feedback-container mb-3" style="display: none;"></div>

                <!-- Options -->
                @if($question->type === 'multiple_choice')
                    <div class="d-flex flex-column gap-2 mb-4 options-container">
                        @foreach($question->options as $option)
                            <label class="border rounded-3 p-3 d-flex align-items-center gap-3 bg-light option-label">
                                <input type="radio" 
                                       name="answer_{{ $question->id }}" 
                                       value="{{ $option->id }}" 
                                       class="form-check-input mt-0 quiz-radio">
                                <span>{{ $option->option_text }}</span>
                            </label>
                        @endforeach
                    </div>
                @elseif($question->type === 'boolean')
                    <div class="d-flex flex-column gap-2 mb-4 options-container">
                        <label class="border rounded-3 p-3 d-flex align-items-center gap-3 bg-light option-label">
                            <input type="radio" 
                                   name="answer_{{ $question->id }}" 
                                   value="1" 
                                   class="form-check-input mt-0 quiz-radio">
                            <span>True</span>
                        </label>
                        <label class="border rounded-3 p-3 d-flex align-items-center gap-3 bg-light option-label">
                            <input type="radio" 
                                   name="answer_{{ $question->id }}" 
                                   value="0" 
                                   class="form-check-input mt-0 quiz-radio">
                            <span>False</span>
                        </label>
                    </div>
                @endif

                <!-- Action Button -->
                <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                    <button type="button" class="btn btn-secondary px-4 rounded-3 action-btn" data-state="submit" disabled>
                        Submit Answer
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-4">No questions added yet.</div>
        @endforelse
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const quizForm = document.getElementById('quizForm');
    if (!quizForm) return;

    // 1. Radio Selection Styling & Button Activation
    quizForm.addEventListener('change', function (e) {
        const radio = e.target.closest('.quiz-radio');
        if (!radio) return;

        const step = radio.closest('.quiz-step');
        const container = radio.closest('.options-container');

        if (container) {
            container.querySelectorAll('.option-label').forEach(lbl => lbl.classList.remove('active-option'));
            const label = radio.closest('.option-label');
            if (label) label.classList.add('active-option');
        }

        const actionBtn = step.querySelector('.action-btn');
        if (actionBtn && actionBtn.getAttribute('data-state') === 'submit') {
            actionBtn.classList.remove('btn-secondary');
            actionBtn.classList.add('btn-primary');
            actionBtn.removeAttribute('disabled');
        }
    });

    // 2. Action Button Handling (Submit -> Next -> Finish)
    quizForm.addEventListener('click', async function (e) {
        const actionBtn = e.target.closest('.action-btn');
        if (!actionBtn) return;

        const currentStep = actionBtn.closest('.quiz-step');
        const currentState = actionBtn.getAttribute('data-state');
        const stepIndex = parseInt(currentStep.getAttribute('data-step'), 10);
        const questionId = currentStep.getAttribute('data-question-id');

        // STEP STATE A: Submit selected answer via AJAX
        if (currentState === 'submit') {
            const selectedRadio = currentStep.querySelector('.quiz-radio:checked');
            if (!selectedRadio) return;

            actionBtn.setAttribute('disabled', 'true');
            actionBtn.innerText = 'Checking...';

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('question_id', questionId);
            formData.append('step_index', stepIndex);
            formData.append('answer', selectedRadio.value);

            try {
                const response = await fetch(quizForm.action, {
                    method: 'POST',
                    headers: { 
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                // Lock inputs for current step
                currentStep.querySelectorAll('.quiz-radio').forEach(r => r.disabled = true);

                // Show feedback UI
                const feedbackBox = currentStep.querySelector('.feedback-container');
                const selectedLabel = selectedRadio.closest('.option-label');
                selectedLabel.classList.remove('active-option');

                if (data.is_correct) {
                    selectedLabel.classList.add('correct-option');
                    feedbackBox.innerHTML = `<div class="alert alert-success d-flex align-items-center gap-2 mb-0 py-2"><i class="fas fa-check-circle"></i> Correct!</div>`;
                } else {
                    selectedLabel.classList.add('incorrect-option');
                    feedbackBox.innerHTML = `<div class="alert alert-danger d-flex align-items-center gap-2 mb-0 py-2"><i class="fas fa-times-circle"></i> Incorrect.</div>`;
                }
                feedbackBox.style.display = 'block';

                // Transform button state based on quiz completion
                actionBtn.removeAttribute('disabled');
                if (data.is_completed) {
                    actionBtn.setAttribute('data-state', 'finish');
                    actionBtn.className = 'btn btn-success px-4 rounded-3 action-btn';
                    actionBtn.innerHTML = 'Finish & See Results';
                    actionBtn.dataset.summary = JSON.stringify(data.summary);
                } else {
                    actionBtn.setAttribute('data-state', 'next');
                    actionBtn.className = 'btn btn-primary px-4 rounded-3 action-btn';
                    actionBtn.innerHTML = 'Next Question <i class="fas fa-arrow-right ms-1"></i>';
                }

            } catch (err) {
                console.error('Quiz submission error:', err);
                actionBtn.innerText = 'Submit Answer';
                actionBtn.removeAttribute('disabled');
            }
        } 
        // STEP STATE B: Advance to Next Question Step
        else if (currentState === 'next') {
            const nextStep = quizForm.querySelector(`.quiz-step[data-step="${stepIndex + 1}"]`);
            if (nextStep) {
                currentStep.style.display = 'none';
                nextStep.style.display = 'block';
            }
        } 
        // STEP STATE C: Show final summary card
        else if (currentState === 'finish') {
            const summary = JSON.parse(actionBtn.dataset.summary || '{}');
            quizForm.style.display = 'none';

            const card = document.getElementById('quizCompletedCard');
            const icon = document.getElementById('resultIcon');
            const title = document.getElementById('resultTitle');
            const score = document.getElementById('resultScore');
            const actionsArea = document.getElementById('quizCompletedActions');

            if (summary.passed) {
                icon.innerHTML = '<i class="fas fa-check-circle text-success fa-4x"></i>';
                title.className = 'fw-bold mt-3 text-success';
                title.innerText = 'Congratulations! You Passed';
                score.innerHTML = `You scored <strong>${summary.score_percentage}%</strong> (${summary.correct_count} out of ${summary.total_questions} correct)`;
                
                actionsArea.innerHTML = `
                    <div class="d-flex justify-content-center gap-2">
                        <button onclick="window.location.reload()" class="btn btn-success rounded-3 px-4">
                            <i class="fas fa-arrow-right me-1"></i> Continue Course
                        </button>
                        <a href="${window.location.href}" class="btn btn-outline-secondary rounded-3 px-3">
                            <i class="fas fa-redo me-1"></i> Retake
                        </a>
                    </div>
                `;
            } else {
                icon.innerHTML = '<i class="fas fa-times-circle text-danger fa-4x"></i>';
                title.className = 'fw-bold mt-3 text-danger';
                title.innerText = 'Quiz Not Passed';
                score.innerHTML = `You scored <strong>${summary.score_percentage}%</strong> (${summary.correct_count} out of ${summary.total_questions} correct). You need 70% to pass.`;
                
                actionsArea.innerHTML = `
                    <a href="${window.location.href}" class="btn btn-outline-primary rounded-3 px-4">
                        <i class="fas fa-redo me-1"></i> Retake Quiz
                    </a>
                `;
            }

            card.style.display = 'block';
        }
    });
});
</script>
@endpush