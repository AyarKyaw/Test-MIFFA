@extends('dashboard.layouts.master')

@section('title', 'Edit Instructor - MIFFA')

@section('content')
<main class="main-content" id="main-content">
    <div class="section__content section__content--p30">
        <div class="row row-tight" style="margin-top: 16px;">
            <div class="col-md-12">
                <section class="m-card">
                    <header class="m-card__header">
                        <h2 class="m-card__title">Edit Instructor</h2>
                        <p class="m-card__subtitle">Update instructor details and image</p>
                    </header>

                    <div class="card-body">
                        <form action="{{ route('admin.instructors.update', $instructor->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Instructor Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $instructor->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold d-block">Current Image</label>
                                @if($instructor->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $instructor->image) }}" alt="{{ $instructor->name }}" class="rounded img-thumbnail" style="height: 90px; width: 90px; object-fit: cover;">
                                    </div>
                                @else
                                    <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center mb-2" style="width: 90px; height: 90px;">
                                        <i class="fa-solid fa-user fa-2x"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label for="image" class="form-label fw-bold">Change Profile Image</label>
                                <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                <div class="form-text">Leave blank to keep the current image. Allowed: JPG, PNG, WEBP (Max: 2MB)</div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.instructors.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="au-btn au-btn--green text-decoration-none">Update Instructor</button>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
</main>
@endsection