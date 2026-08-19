@php
    // Extract YouTube Video ID safely
    $youtubeId = null;
    if (!empty($lesson->video_url)) {
        if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/', $lesson->video_url, $matches)) {
            $youtubeId = $matches[1];
        }
    }
@endphp

<div class="p-3 p-md-4">
    <!-- Video Embed Container -->
    <div class="ratio ratio-16x9 rounded-3 overflow-hidden bg-dark mb-4 position-relative">
        @if($youtubeId)
            <!-- YouTube Player Placeholder -->
            <div id="youtube-player" class="w-100 h-100 position-absolute top-0 start-0"></div>
        @elseif($lesson->video_path)
            <!-- Local HTML5 Video Player -->
            <video id="local-video" controls class="w-100 h-100 position-absolute top-0 start-0">
                <source src="{{ asset('storage/' . $lesson->video_path) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @else
            <div class="d-flex align-items-center justify-content-center text-white-50 w-100 h-100">
                <i class="fas fa-exclamation-triangle me-2"></i> Video content unavailable.
            </div>
        @endif
    </div>

    <!-- Lesson Header -->
    <div class="border-bottom pb-3 mb-3">
        <h4 class="fw-bold text-dark mb-1">{{ $lesson->title }}</h4>
        <p class="text-muted small mb-0">ဗီဒီယိုကိုကြည့်ရှုပြီး အောက်ပါမေးခွန်းများကို တစ်ခါတည်း ဖြေဆိုနိုင်ပါသည်။</p>
    </div>

    <!-- Lesson Description/Notes -->
    <div class="text-secondary lh-base">
        {!! $lesson->content ?? $lesson->description ?? 'No additional instructions for this lesson.' !!}
    </div>
</div>
@push('scripts')
<script>
    let isCompleted = false;
    // Explicitly parse lesson ID as an integer
    const lessonId = parseInt("{{ $lesson->id }}", 10);
    const storageKey = `lesson_progress_${lessonId}`;

    // Load saved data or set defaults
    let savedData = {};
    try {
        savedData = JSON.parse(localStorage.getItem(storageKey)) || {};
    } catch(e) {
        savedData = {};
    }

    let maxWatchedTime = savedData.maxWatchedTime || 0;
    let lastPosition = savedData.lastPosition || 0;
    let checkInterval = null;

    console.log(`[Video Tracking Initialized] Lesson ID: ${lessonId}`, { maxWatchedTime, lastPosition });

    // Function to write to localStorage and log
    function saveProgress(currentTime) {
        // Only count valid forward progress (prevents skips)
        if (currentTime - maxWatchedTime < 2) {
            maxWatchedTime = Math.max(maxWatchedTime, currentTime);
        }
        lastPosition = currentTime;

        const payload = {
            maxWatchedTime: parseFloat(maxWatchedTime.toFixed(2)),
            lastPosition: parseFloat(lastPosition.toFixed(2))
        };

        localStorage.setItem(storageKey, JSON.stringify(payload));
        console.log(`[Progress Saved for ${storageKey}]:`, payload);
    }

    function handleCompletion() {
        if (isCompleted) return;
        isCompleted = true;

        clearInterval(checkInterval);

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
                localStorage.removeItem(storageKey);
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    window.location.reload();
                }
            }
        })
        .catch(error => {
            console.error('Error saving progress:', error);
            isCompleted = false;
        });
    }

    function verifyAndComplete(duration) {
        console.log(`[Checking Completion] Max Watched: ${maxWatchedTime}s / Target: ${(duration * 0.85).toFixed(2)}s`);
        if (maxWatchedTime >= duration * 0.85) {
            handleCompletion();
        } else {
            alert(`You need to watch at least 85% of the video to complete this lesson.`);
        }
    }

    // 1. YouTube Player Integration
    @if($youtubeId)
        var player;

        function createYTPlayer() {
            var container = document.getElementById('youtube-player');
            if (container) {
                container.innerHTML = '';
                player = new YT.Player('youtube-player', {
                    height: '100%',
                    width: '100%',
                    videoId: '{{ $youtubeId }}',
                    playerVars: {
                        'enablejsapi': 1,
                        'rel': 0,
                        'modestbranding': 1,
                        'playsinline': 1
                    },
                    events: {
                        'onReady': function(event) {
                            if (lastPosition > 5 && lastPosition < player.getDuration() - 5) {
                                player.seekTo(lastPosition, true);
                            }
                        },
                        'onStateChange': function(event) {
                            // 1 = PLAYING
                            if (event.data === 1) {
                                clearInterval(checkInterval);
                                checkInterval = setInterval(function() {
                                    if (player && player.getCurrentTime) {
                                        saveProgress(player.getCurrentTime());
                                    }
                                }, 1000);
                            } else {
                                clearInterval(checkInterval);
                            }

                            // 0 = ENDED
                            if (event.data === 0) {
                                verifyAndComplete(player.getDuration());
                            }
                        }
                    }
                });
            }
        }

        window.onYouTubeIframeAPIReady = function() { createYTPlayer(); };
        if (window.YT && window.YT.Player) { createYTPlayer(); } else {
            var tag = document.createElement('script');
            tag.src = "https://www.youtube.com/iframe_api";
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        }
    @endif

    // 2. HTML5 Local Video Integration
    @if($lesson->video_path)
        document.addEventListener('DOMContentLoaded', function() {
            const localVideo = document.getElementById('local-video');
            if (localVideo) {
                if (lastPosition > 5 && lastPosition < localVideo.duration - 5) {
                    localVideo.currentTime = lastPosition;
                }

                localVideo.addEventListener('timeupdate', function() {
                    saveProgress(localVideo.currentTime);
                });

                localVideo.addEventListener('ended', function() {
                    verifyAndComplete(localVideo.duration);
                });
            }
        });
    @endif
</script>
@endpush