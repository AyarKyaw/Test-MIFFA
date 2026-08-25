@extends('dashboard.layouts.master')

@section('title', 'Instructors - MIFFA')

@section('content')
<main class="main-content" id="main-content">
    <div class="section__content section__content--p30">
        <div class="row row-tight" style="margin-top: 16px;">
            <div class="col-md-12">
                <section class="m-card" aria-labelledby="instructors-title">
                    <header class="m-card__header d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="m-card__title" id="instructors-title">Instructors</h2>
                            <p class="m-card__subtitle">Manage instructor profiles and avatars</p>
                        </div>
                    </header>

                    <!-- Alerts for session messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-data__tool">
                        <div class="table-data__tool-left">
                            <form action="{{ route('admin.instructors.index') }}" method="GET" class="d-flex gap-2">
                                <div class="select-wrapper">
                                    <select class="form-select" name="filter" onchange="this.form.submit()">
                                        <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>All Instructors</option>
                                        <option value="latest" {{ request('filter') == 'latest' ? 'selected' : '' }}>Latest First</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="table-data__tool-right d-flex gap-2">
                            <a href="{{ route('admin.instructors.create') }}" class="au-btn au-btn--green au-btn--small text-decoration-none d-inline-flex align-items-center gap-1">
                                <i class="fa-solid fa-plus" aria-hidden="true"></i> Add Instructor
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-data2">
                            <thead>
                                <tr>
                                    <th style="width:24px;">
                                        <label class="au-checkbox">
                                            <input type="checkbox" aria-label="Select all"><span class="au-checkmark"></span>
                                        </label>
                                    </th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Created Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($instructors as $instructor)
                                    <tr class="tr-shadow">
                                        <td>
                                            <label class="au-checkbox">
                                                <input type="checkbox" name="selected_instructors[]" value="{{ $instructor->id }}">
                                                <span class="au-checkmark"></span>
                                            </label>
                                        </td>
                                        <td>
                                            @if($instructor->image)
                                                <img src="{{ asset('storage/' . $instructor->image) }}" 
                                                     alt="{{ $instructor->name }}" 
                                                     class="rounded-circle" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fa-solid fa-user"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark">{{ $instructor->name }}</span>
                                        </td>
                                        <td>{{ $instructor->created_at ? $instructor->created_at->format('M d, Y H:i') : 'N/A' }}</td>
                                        <td>
                                            <div class="table-data-feature justify-content-end gap-1">
                                                <a href="{{ route('admin.instructors.edit', $instructor->id) }}" class="item" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <form action="{{ route('admin.instructors.destroy', $instructor->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this instructor?');" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="item" type="submit" data-bs-toggle="tooltip" title="Delete">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            No instructors found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    @if(method_exists($instructors, 'links'))
                        <div class="mt-3 d-flex justify-content-end">
                            {{ $instructors->appends(request()->query())->links() }}
                        </div>
                    @endif

                </section>
            </div>
        </div>
    </div>
</main>
@endsection