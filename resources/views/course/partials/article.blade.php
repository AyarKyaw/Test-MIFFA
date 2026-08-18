<div class="p-4 p-md-5">
    <h3 class="fw-bold text-dark mb-4">{{ $lesson->title }}</h3>
    <div class="lh-lg text-secondary">
        {!! $lesson->content ?? 'No content available.' !!}
    </div>
</div>