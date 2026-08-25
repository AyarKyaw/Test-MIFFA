@extends('dashboard.layouts.master')

@section('title', 'Add Instructor - MIFFA')

@section('content')
<main class="main-content" id="main-content">
    <div class="section__content section__content--p30">
        <div class="row row-tight" style="margin-top: 16px;">
            <div class="col-md-12">
                <section class="m-card">
                    <header class="m-card__header">
                        <h2 class="m-card__title">Add New Instructor</h2>
                        <p class="m-card__subtitle">Create a new instructor profile</p>
                    </header>

                    <div class="card-body">
                        <form action="{{ route('admin.instructors.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Instructor Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Enter instructor name" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="image" class="form-label fw-bold">Profile Image</label>
                                <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                <div class="form-text">Allowed formats: JPG, PNG, WEBP (Max: 2MB)</div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.instructors.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="au-btn au-btn--green text-decoration-none">Create Instructor</button>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
</main>
@endsection