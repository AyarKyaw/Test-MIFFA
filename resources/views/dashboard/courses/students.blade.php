@extends('dashboard.layouts.master')

@section('title', $course->title . ' - Enrolled Students | MIFFA')

@section('content')
<main class="main-content" id="main-content">
    <div class="section__content section__content--p30">
        <div class="row row-tight" style="margin-top: 16px;">
            <div class="col-md-12">
                <section class="m-card" aria-labelledby="course-students-title">
                    
                    <!-- Header Banner with Course Details -->
                    <header class="m-card__header d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-outline-secondary me-1">
                                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back
                                </a>
                                <span class="badge bg-secondary font-monospace">{{ $course->code }}</span>
                                <span class="badge bg-info text-dark">{{ $course->category->name ?? 'Uncategorized' }}</span>
                            </div>
                            <h2 class="m-card__title" id="course-students-title">
                                Enrolled Students: <span class="text-primary">{{ $course->title }}</span>
                            </h2>
                            <p class="m-card__subtitle text-muted mb-0">
                                Total Enrolled: <strong>{{ $students->total() ?? count($students) }}</strong> students
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1">
                                <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i> Edit Course
                            </a>
                        </div>
                    </header>

                    <!-- Alert Messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Toolbar & Filters -->
                    <div class="table-data__tool">
                        <div class="table-data__tool-left">
                            <form action="{{ route('admin.courses.students', $course->id) }}" method="GET" class="d-flex gap-2">
                                <div class="input-group input-group-sm" style="width: 260px;">
                                    <input type="text" name="search" class="form-control" placeholder="Search student name or email..." value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                </div>
                                <div class="select-wrapper">
                                    <select class="form-select form-select-sm" name="status" onchange="this.form.submit()">
                                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Statuses</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="table-data__tool-right">
                            <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                                <i class="fa-solid fa-users" aria-hidden="true"></i> View All Students
                            </a>
                        </div>
                    </div>

                    <!-- Students Table -->
                    <div class="table-responsive">
                        <table class="table table-data2">
                            <thead>
                                <tr>
                                    <th style="width:24px;">
                                        <label class="au-checkbox">
                                            <input type="checkbox" aria-label="Select all"><span class="au-checkmark"></span>
                                        </label>
                                    </th>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Enrolled Date</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($students as $student)
                                    <tr class="tr-shadow">
                                        <td>
                                            <label class="au-checkbox">
                                                <input type="checkbox" name="selected_students[]" value="{{ $student->id }}">
                                                <span class="au-checkmark"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary font-monospace">{{ $student->student_code ?? '#' . $student->id }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark">{{ $student->name }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $student->email }}</span>
                                        </td>
                                        <td>
                                            <span>{{ $student->phone ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            {{ $student->pivot->created_at ? \Carbon\Carbon::parse($student->pivot->created_at)->format('M d, Y') : ($student->created_at ? $student->created_at->format('M d, Y') : 'N/A') }}
                                        </td>
                                        <td>
                                            @php
                                                $status = $student->pivot->status ?? $student->status ?? 'active';
                                            @endphp
                                            @if($status === 'completed')
                                                <span class="badge bg-success">Completed</span>
                                            @elseif($status === 'active')
                                                <span class="badge bg-primary">Active</span>
                                            @else
                                                <span class="badge bg-warning text-dark">{{ ucfirst($status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="table-data-feature justify-content-end gap-1">
                                                <a href="{{ route('admin.students.show', $student->id) }}" class="item" data-bs-toggle="tooltip" title="View Profile">
                                                    <i class="fa-solid fa-eye text-primary"></i>
                                                </a>
                                                <form action="{{ route('admin.courses.students.remove', ['course' => $course->id, 'student' => $student->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to unenroll this student?');" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="item" type="submit" data-bs-toggle="tooltip" title="Unenroll">
                                                        <i class="fa-solid fa-user-minus text-danger"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            No students enrolled in this course yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    @if(method_exists($students, 'links'))
                        <div class="mt-3 d-flex justify-content-end">
                            {{ $students->appends(request()->query())->links() }}
                        </div>
                    @endif

                </section>
            </div>
        </div>
    </div>
</main>
@endsection