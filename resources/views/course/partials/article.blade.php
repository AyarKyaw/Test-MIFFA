<div class="p-4 p-md-5">
    <h3 class="fw-bold text-dark mb-4">{{ $lesson->title }}</h3>
    <div class="lh-lg text-secondary">
        {!! $lesson->content ?? 'No content available.' !!}
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let isCompleted = false;

    function markLessonComplete() {
        if (isCompleted) return;
        isCompleted = true;

        fetch("{{ route('lessons.complete', $lesson->id) }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('[Lesson Completed Automatically]');
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                }
            }
        })
        .catch(error => {
            console.error('Error auto-completing lesson:', error);
            isCompleted = false;
        });
    }

    // Trigger completion immediately upon opening
    markLessonComplete();
});
</script>
@endpush