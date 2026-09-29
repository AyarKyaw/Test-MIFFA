@extends('layouts.student')

@section('title', 'My Homework | MIFFA ACADEMY')

@section('content')
<div class="p-2">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Homework Submissions 📝</h2>
            <p class="text-muted mb-0">View all your uploaded assignment files, track grades, and check review status.</p>
        </div>
        <div>
            <a href="{{ route('student.dashboard.courses') }}" class="btn btn-outline-primary rounded-pill px-4" style="border-color: #0b3281; color: #0b3281;">
                <i class="fas fa-book me-1"></i> Go to Courses
            </a>
        </div>
    </div>

    <!-- Homework Submissions Table -->
    @if(isset($homeworkSubmissions) && $homeworkSubmissions->count() > 0)
        <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-secondary small text-uppercase fw-semibold" style="letter-spacing: 0.5px;">
                            <th class="ps-4 py-3">Lesson & Details</th>
                            <th class="py-3">Homework Type</th>
                            <th class="py-3">Submitted File</th>
                            <th class="py-3">Status & Grade</th>
                            <th class="py-3">Submission Date</th>
                            <th class="text-end pe-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach($homeworkSubmissions as $lesson)
                            @php
                                $filePath = $lesson->pivot->homework_file_path ?? '';
                                $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                                // Determine Homework Type Badge & Icon
                                switch($extension) {
                                    case 'pdf':
                                        $typeLabel = 'PDF Document';
                                        $typeBadge = 'bg-danger-subtle text-danger border-danger';
                                        $typeIcon = 'fa-file-pdf';
                                        break;
                                    case 'doc':
                                    case 'docx':
                                        $typeLabel = 'Word Document';
                                        $typeBadge = 'bg-primary-subtle text-primary border-primary';
                                        $typeIcon = 'fa-file-word';
                                        break;
                                    case 'zip':
                                    case 'rar':
                                    case '7z':
                                        $typeLabel = 'Archive (ZIP)';
                                        $typeBadge = 'bg-warning-subtle text-warning-emphasis border-warning';
                                        $typeIcon = 'fa-file-archive';
                                        break;
                                    case 'jpg':
                                    case 'jpeg':
                                    case 'png':
                                        $typeLabel = 'Image File';
                                        $typeBadge = 'bg-info-subtle text-info-emphasis border-info';
                                        $typeIcon = 'fa-file-image';
                                        break;
                                    default:
                                        $typeLabel = strtoupper($extension) ?: 'File';
                                        $typeBadge = 'bg-secondary-subtle text-secondary border-secondary';
                                        $typeIcon = 'fa-file';
                                        break;
                                }

                                $quizScore = $lesson->pivot->quiz_score ?? null;
                                $isCompleted = $lesson->pivot->is_completed ?? false;
                            @endphp
                            <tr>
                                <!-- Lesson & Title -->
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center text-white" 
                                             style="width: 42px; height: 42px; background-color: #0b3281; flex-shrink: 0;">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0">{{ $lesson->title }}</h6>
                                            <small class="text-muted">
                                                <i class="fas fa-layer-group me-1"></i>
                                                {{ $lesson->section->unit->title ?? $lesson->unit->title ?? 'General Unit' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <!-- Homework Type Column -->
                                <td class="py-3">
                                    <span class="badge {{ $typeBadge }} border px-3 py-2 rounded-pill fw-semibold">
                                        <i class="fas {{ $typeIcon }} me-1"></i> {{ $typeLabel }}
                                    </span>
                                </td>

                                <!-- Submitted File Link -->
                                <td class="py-3">
                                    @if($filePath)
                                        <a href="{{ Storage::url($filePath) }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-light border rounded-pill text-truncate px-3" 
                                           style="max-width: 200px;">
                                            <i class="fas fa-paperclip me-1" style="color: #0b3281;"></i>
                                            {{ basename($filePath) }}
                                        </a>
                                    @else
                                        <span class="text-muted small">No file attached</span>
                                    @endif
                                </td>

                                <!-- Status & Grade Column (Uses existing pivot attributes) -->
                                <td class="py-3">
                                    @if(!is_null($quizScore))
                                        <span class="badge bg-success-subtle text-success border border-success rounded-pill px-3 py-2 fw-bold">
                                            <i class="fas fa-star me-1 text-warning"></i> Score: {{ $quizScore }}%
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary rounded-pill px-3 py-2 fw-normal">
                                            <i class="fas fa-hourglass-half me-1"></i> Pending Review
                                        </span>
                                    @endif
                                </td>

                                <!-- Submission Date -->
                                <td class="py-3 text-muted small">
                                    <i class="far fa-clock me-1 text-secondary"></i>
                                    {{ \Carbon\Carbon::parse($lesson->pivot->updated_at)->format('M d, Y • h:i A') }}
                                </td>

                                <!-- Action Button -->
                                <!-- Action Button -->
<td class="text-end pe-4 py-3">
    @if(isset($lesson->pivot->course_id))
        <a href="{{ route('courses.learn', ['course' => $lesson->pivot->course_id, 'lesson' => $lesson->id]) }}" 
           class="btn btn-sm text-white rounded-pill px-3 fw-semibold" 
           style="background-color: #0b3281;">
            <i class="fas fa-arrow-right me-1"></i> Go to Lesson
        </a>
    @else
        <span class="badge bg-light text-secondary border">Completed</span>
    @endif
</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if(method_exists($homeworkSubmissions, 'links'))
            <div class="d-flex justify-content-center mt-4">
                {{ $homeworkSubmissions->links() }}
            </div>
        @endif

    @else
        <!-- Empty State -->
        <div class="card border-0 rounded-4 shadow-sm p-5 bg-white text-center">
            <div class="my-4">
                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="fas fa-tasks text-muted fa-2x"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">No Homework Submissions Found</h5>
                <p class="text-muted mb-4 mx-auto" style="max-width: 420px;">
                    You haven't uploaded any homework files yet. Complete your lessons and upload assignments from your course classroom.
                </p>
                <a href="{{ route('student.dashboard.courses') }}" class="btn text-white rounded-pill px-4 py-2 fw-semibold" style="background-color: #0b3281;">
                    <i class="fas fa-book me-2"></i> View My Courses
                </a>
            </div>
        </div>
    @endif
</div>
@endsection