@extends('dashboard.layouts.master')

@section('title', 'Manage Lessons - MIFFA')

@section('content')
<main class="main-content" id="main-content">
    <div class="section__content section__content--p30">
        <div class="row row-tight" style="margin-top: 16px;">
            <div class="col-md-12">
                <section class="m-card">
                    <header class="m-card__header d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="m-card__title">
                                {{ isset($selectedCourse) ? $selectedCourse->title . ' - Lessons' : 'All Lessons' }}
                            </h2>
                            <p class="m-card__subtitle">
                                {{ isset($selectedCourse) ? 'Manage and review modules for ' . $selectedCourse->title : 'Manage and review all course lessons across the platform' }}
                            </p>
                        </div>
                        <a href="{{ isset($selectedCourse) ? route('admin.lessons.create', ['course_id' => $selectedCourse->id]) : route('admin.lessons.create') }}" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-plus me-1"></i> Add New Lesson
                        </a>
                    </header>

                    <!-- Alerts for session messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-4 mb-0" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="p-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 60px;">Order</th>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Details</th>
                                        <th class="text-end" style="width: 120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($lessons as $lesson)
                                        <tr>
                                            <td class="fw-bold text-secondary">#{{ $lesson->order }}</td>
                                            <td class="fw-semibold text-dark">{{ $lesson->title }}</td>
                                            <td>
                                                @switch($lesson->type)
                                                    @case('video')
                                                        <span class="badge bg-primary"><i class="fa-solid fa-video me-1"></i> Video</span>
                                                        @break
                                                    @case('document')
                                                        <span class="badge bg-info text-dark"><i class="fa-solid fa-file-pdf me-1"></i> Document</span>
                                                        @break
                                                    @case('text')
                                                        <span class="badge bg-secondary"><i class="fa-solid fa-align-left me-1"></i> Article</span>
                                                        @break
                                                    @case('quiz')
                                                        <span class="badge bg-warning text-dark"><i class="fa-solid fa-list-check me-1"></i> Quiz ({{ $lesson->questions->count() }} Qs)</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td class="small text-muted">
                                                @if($lesson->type === 'video')
                                                    <code>{{ Str::limit($lesson->video_url, 30) }}</code>
                                                @elseif($lesson->type === 'quiz')
                                                    {{ $lesson->questions->count() }} total questions configured
                                                @else
                                                    {{ Str::limit(strip_tags($lesson->content), 40) ?? 'N/A' }}
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.lessons.edit', $lesson->id) }}" class="btn btn-outline-secondary" title="Edit Lesson">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </a>
                                                    <form action="{{ route('admin.lessons.destroy', $lesson->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this lesson?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger" title="Delete Lesson">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">No lessons found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</main>
@endsection