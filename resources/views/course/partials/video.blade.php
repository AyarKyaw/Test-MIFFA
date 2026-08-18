<div class="p-3 p-md-4">
    <!-- Video Embed Container -->
    <div class="ratio ratio-16x9 rounded-3 overflow-hidden bg-dark mb-4">
        @if($lesson->video_url)
            @php $embedUrl = Str::replace('watch?v=', 'embed/', $lesson->video_url); @endphp
            <iframe src="{{ $embedUrl }}" title="{{ $lesson->title }}" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        @elseif($lesson->video_path)
            <video controls class="w-100 h-100">
                <source src="{{ asset('storage/' . $lesson->video_path) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @endif
    </div>

    <!-- Lesson Metadata -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">{{ $lesson->title }}</h4>
            <p class="text-muted small mb-0">ဗီဒီယိုကိုကြည့်ရှုပြီး အောက်ပါမေးခွန်းများကို တစ်ခါတည်း ဖြေဆိုနိုင်ပါသည်။</p>
        </div>
        <button class="btn btn-success btn-sm rounded-pill px-3 fw-semibold">
            <i class="fas fa-check-circle me-1"></i> Mark Completed
        </button>
    </div>

    <!-- Lesson Description/Notes -->
    <div class="text-secondary lh-base">
        {!! $lesson->content ?? $lesson->description ?? 'No additional instructions for this lesson.' !!}
    </div>
</div>