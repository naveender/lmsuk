@extends('layouts.app')

@section('title', $subject->title . ' Video Lessons')

@push('styles')
<style>
    /* Masterclass-style Streaming Layout */
    .video-view-container {
        background: #f8f9fc;
        min-height: calc(100vh - 150px);
    }
    
    .player-card {
        border-radius: 16px;
        overflow: hidden;
        background: #000000;
        box-shadow: 0 12px 36px rgba(0, 0, 0, 0.15);
    }

    .video-container {
        position: relative;
        width: 100%;
        padding-top: 56.25%; /* 16:9 Aspect Ratio */
        background: #000;
    }

    .video-container video, 
    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }

    .active-info-card {
        border: none;
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }

    .playlist-card {
        border: none;
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        max-height: 700px;
        display: flex;
        flex-direction: column;
    }

    .playlist-scroll {
        overflow-y: auto;
        flex-grow: 1;
        padding-right: 4px;
    }

    .playlist-scroll::-webkit-scrollbar {
        width: 6px;
    }
    .playlist-scroll::-webkit-scrollbar-thumb {
        background-color: #e2e8f0;
        border-radius: 4px;
    }

    .playlist-item {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        cursor: pointer;
        background: #ffffff;
    }

    .playlist-item:hover {
        border-color: #7367f0;
        background: #f8fafc;
        transform: translateY(-2px);
    }

    .active-playlist-item {
        border-color: #7367f0;
        background: #f1f0ff;
        box-shadow: 0 4px 15px rgba(115, 103, 240, 0.08);
    }

    .active-playlist-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #7367f0;
    }

    .playlist-thumbnail {
        width: 100px;
        height: 56px;
        border-radius: 8px;
        background-size: cover;
        background-position: center;
        position: relative;
        flex-shrink: 0;
        background-color: #1e293b;
    }

    .playlist-thumbnail::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.15);
        border-radius: 8px;
    }

    .playlist-duration {
        position: absolute;
        bottom: 4px;
        right: 4px;
        background: rgba(0,0,0,0.75);
        color: #fff;
        font-size: 0.65rem;
        padding: 1px 4px;
        border-radius: 4px;
        font-weight: 600;
        z-index: 2;
    }

    .playlist-progress-bar {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        transition: width 0.3s ease;
    }

    .playlist-completed {
        background-color: #f0fdf4 !important;
        border-color: #86efac !important;
    }

    /* Gradient Background Placeholders for missing thumbnails */
    .thumb-grad-1 { background: linear-gradient(135deg, #7367f0 0%, #a83279 100%); }
    .thumb-grad-2 { background: linear-gradient(135deg, #28c76f 0%, #81f1a1 100%); }
    .thumb-grad-3 { background: linear-gradient(135deg, #ff9f43 0%, #ffc085 100%); }
    .thumb-grad-4 { background: linear-gradient(135deg, #00cfe8 0%, #00b5cc 100%); }
</style>
@endpush

@section('content')
<div class="app-content content video-view-container">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-body py-2">
            
            <!-- Breadcrumbs / Back button -->
            <div class="d-flex align-items-center mb-3">
                <a href="{{ route('student.videolessonscategories') }}" class="btn btn-flat-primary mr-1 p-50">
                    <i class="feather icon-arrow-left font-medium-3"></i>
                </a>
                <div>
                    <h5 class="mb-0 text-dark font-weight-bold">{{ $subject->title }} Lessons</h5>
                    <span class="text-muted font-small-3">General Repository Videos</span>
                </div>
            </div>

            @if($mediaFiles->isNotEmpty())
                @php 
                    $firstVideo = $mediaFiles->first(); 
                    $firstProgress = $videoProgressMap[$firstVideo->id] ?? null;
                @endphp
                
                <div class="row">
                    <!-- Left: Video Player and Details -->
                    <div class="col-lg-8 mb-2">
                        <div class="card player-card mb-2">
                            <div class="video-container" id="videoContainer" data-active-id="{{ $firstVideo->id }}">
                                @if($firstVideo->type === 'iframe')
                                    {!! $firstVideo->embed_url !!}
                                @elseif(in_array($firstVideo->type, ['video_file', 'video_url', 's3', 'wasabi']))
                                    <video id="trackedHtml5Player" controls class="embed-responsive-item" src="{{ $firstVideo->embed_url }}" style="width: 100%; height: 100%; border: none;"></video>
                                @else
                                    <iframe id="videoIframe" class="embed-responsive-item" width="100%" height="100%" src="{{ $firstVideo->embed_url }}" frameborder="0" allowfullscreen allow="autoplay" style="border: none;"></iframe>
                                @endif
                            </div>
                        </div>

                        <div class="card active-info-card">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                    <div class="mr-2">
                                        <h3 class="font-weight-bold text-dark mb-50" id="activeVideoTitle">{{ $firstVideo->title }}</h3>
                                        <span class="text-success font-weight-bold font-small-3" id="activeVideoDuration">
                                            @if($firstVideo->duration)
                                                <i class="feather icon-play-circle mr-25"></i>Runtime: {{ $firstVideo->duration }}
                                            @else
                                                <i class="feather icon-video mr-25"></i>General Lesson Video
                                            @endif
                                        </span>
                                    </div>
                                    <div id="activeVideoProgressBadge" class="mt-50 mt-sm-0">
                                        @if($firstProgress && $firstProgress->is_completed)
                                            <span class="badge badge-success px-2 py-1"><i class="feather icon-check-circle mr-25"></i>Completed</span>
                                        @elseif($firstProgress && $firstProgress->watch_time > 0)
                                            <span class="badge badge-light-warning px-2 py-1"><i class="feather icon-clock mr-25"></i>{{ gmdate('H:i:s', $firstProgress->watch_time) }} watched</span>
                                        @else
                                            <span class="badge badge-light-secondary px-2 py-1">Not started</span>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                                <h6 class="font-weight-bold text-dark mb-1">Description</h6>
                                <p class="text-muted" id="activeVideoDescription" style="line-height: 1.6;">
                                    {{ $firstVideo->description ?: 'No description provided for this video lecture.' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Playlist Sidebar -->
                    <div class="col-lg-4 mb-2">
                        <div class="card playlist-card h-100">
                            <div class="card-body p-3 flex-column d-flex h-100">
                                <h5 class="font-weight-bold text-dark mb-2">
                                    <i class="feather icon-list mr-50 text-primary"></i> Playlist ({{ $mediaFiles->total() }} Videos)
                                </h5>
                                
                                <div class="playlist-scroll pr-1">
                                    @foreach($mediaFiles as $index => $video)
                                        @php
                                            $vProgress = $videoProgressMap[$video->id] ?? null;
                                            $isVCompleted = $vProgress && $vProgress->is_completed;
                                            $watchPct = 0;
                                            if ($vProgress && $video->duration) {
                                                $parts = explode(':', $video->duration);
                                                $durSec = count($parts) === 3
                                                    ? ((int)$parts[0]*3600 + (int)$parts[1]*60 + (int)$parts[2])
                                                    : (count($parts) === 2 ? ((int)$parts[0]*60 + (int)$parts[1]) : (int)$video->duration);
                                                $watchPct = $durSec > 0 ? min(100, round(($vProgress->watch_time / $durSec) * 100)) : 0;
                                            }
                                            
                                            // Assign thumbnail path or dynamic color gradient class
                                            $thumbBgStyle = '';
                                            $gradClass = 'thumb-grad-' . (($index % 4) + 1);
                                            if ($video->thumbnail_url) {
                                                $thumbBgStyle = 'background-image: url(' . $video->thumbnail_url . ')';
                                            }
                                        @endphp
                                        
                                        <div class="playlist-item d-flex align-items-start p-1 mb-2 {{ $loop->first ? 'active-playlist-item' : '' }} {{ $isVCompleted ? 'playlist-completed' : '' }}"
                                             data-id="{{ $video->id }}"
                                             data-embed="{{ $video->embed_url }}"
                                             data-title="{{ $video->title }}"
                                             data-desc="{{ $video->description ?: 'No description provided.' }}"
                                             data-duration="{{ $video->duration }}"
                                             data-type="{{ $video->type }}"
                                             data-completed="{{ $isVCompleted ? '1' : '0' }}">
                                             
                                            <div class="playlist-thumbnail mr-2 {{ $video->thumbnail_url ? '' : $gradClass }}" style="{{ $thumbBgStyle }}">
                                                @if($video->duration)
                                                    <span class="playlist-duration">{{ $video->duration }}</span>
                                                @endif
                                            </div>
                                            
                                            <div class="flex-grow-1 min-width-0">
                                                <h6 class="font-small-3 font-weight-bold mb-50 text-dark text-truncate">{{ $video->title }}</h6>
                                                
                                                <div class="d-flex align-items-center flex-wrap font-small-1">
                                                    @if($isVCompleted)
                                                        <span class="text-success font-weight-bold"><i class="feather icon-check mr-25"></i>Completed</span>
                                                    @elseif($vProgress && $vProgress->watch_time > 0)
                                                        <span class="text-warning font-weight-bold"><i class="feather icon-clock mr-25"></i>{{ gmdate('i:s', $vProgress->watch_time) }} watched</span>
                                                    @else
                                                        <span class="text-muted">Not played</span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Progress bar bottom bar overlay -->
                                            @if($isVCompleted)
                                                <div class="playlist-progress-bar bg-success" style="width: 100%;"></div>
                                            @elseif($watchPct > 0)
                                                <div class="playlist-progress-bar bg-warning" style="width: {{ $watchPct }}%;"></div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                
                                <!-- Custom Pagination -->
                                @if($mediaFiles->hasPages())
                                    <div class="mt-2 pt-2 border-top d-flex justify-content-center">
                                        {!! $mediaFiles->appends(request()->input())->links('pagination::bootstrap-4') !!}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card text-center border-0 shadow-sm p-4 bg-white">
                    <div class="card-body">
                        <i class="feather icon-video text-muted font-large-3 mb-2"></i>
                        <h4 class="text-dark font-weight-bold">No Video Lessons Available</h4>
                        <p class="text-muted">We couldn't find any unassigned general video files for this subject matching your visibility configurations.</p>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let activeVideoId = null;
        let lastPosition = 0;
        let accumulatedWatchTime = 0;
        let heartbeatInterval = null;
        let isTrackingActive = false;

        const container = document.getElementById('videoContainer');
        if (container) {
            activeVideoId = container.getAttribute('data-active-id');
            setupVideoTracking();
        }

        function setupVideoTracking() {
            if (heartbeatInterval) {
                clearInterval(heartbeatInterval);
            }
            accumulatedWatchTime = 0;
            lastPosition = 0;
            isTrackingActive = false;

            const videoEl = document.getElementById('trackedHtml5Player');
            if (videoEl) {
                let lastTime = videoEl.currentTime;

                videoEl.addEventListener('play', () => {
                    if (!isTrackingActive) {
                        isTrackingActive = true;
                        lastTime = videoEl.currentTime;
                        startHeartbeatLoop(videoEl);
                    }
                });

                videoEl.addEventListener('pause', () => {
                    isTrackingActive = false;
                    sendWatchProgress(videoEl.currentTime);
                });

                videoEl.addEventListener('ended', () => {
                    isTrackingActive = false;
                    sendWatchProgress(videoEl.currentTime);
                });

                videoEl.addEventListener('timeupdate', () => {
                    if (videoEl.paused || videoEl.seeking) {
                        lastTime = videoEl.currentTime;
                        return;
                    }
                    const diff = videoEl.currentTime - lastTime;
                    if (diff > 0 && diff < 2.5) {
                        accumulatedWatchTime += diff;
                    }
                    lastTime = videoEl.currentTime;
                });
            }
        }

        function startHeartbeatLoop(videoEl) {
            heartbeatInterval = setInterval(() => {
                if (!isTrackingActive || videoEl.paused) {
                    clearInterval(heartbeatInterval);
                    return;
                }
                sendWatchProgress(videoEl.currentTime);
            }, 5000);
        }

        function sendWatchProgress(currentPos) {
            if (!activeVideoId) return;
            const roundedIncrement = Math.round(accumulatedWatchTime);
            if (roundedIncrement === 0 && Math.abs(currentPos - lastPosition) < 1) {
                return;
            }

            accumulatedWatchTime = 0;
            lastPosition = currentPos;

            axios.post('{{ route("student.media.progress") }}', {
                media_file_id: activeVideoId,
                last_position: currentPos,
                increment_watch_time: roundedIncrement
            }, {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (response.data.success) {
                    updateProgressBadge(response.data);
                }
            })
            .catch(err => {
                console.error('Failed to send watch progress', err);
            });
        }

        function updateProgressBadge(data) {
            const badgeEl = document.getElementById('activeVideoProgressBadge');
            if (!badgeEl) return;

            if (data.is_completed) {
                badgeEl.innerHTML = '<span class="badge badge-success px-2 py-1"><i class="feather icon-check-circle mr-25"></i>Completed</span>';
            } else if (data.watch_time > 0) {
                const h = Math.floor(data.watch_time / 3600);
                const m = Math.floor((data.watch_time % 3600) / 60);
                const s = data.watch_time % 60;
                const timeStr = String(h).padStart(2,'0') + ':' + String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
                badgeEl.innerHTML = `<span class="badge badge-light-warning px-2 py-1"><i class="feather icon-clock mr-25"></i>${timeStr} watched</span>`;
            }

            const playlistItem = document.querySelector(`.playlist-item[data-id="${activeVideoId}"]`);
            if (playlistItem) {
                if (data.is_completed) {
                    playlistItem.classList.add('playlist-completed');
                    playlistItem.setAttribute('data-completed', '1');
                    
                    const infoText = playlistItem.querySelector('.font-small-1');
                    if (infoText) {
                        infoText.innerHTML = '<span class="text-success font-weight-bold"><i class="feather icon-check mr-25"></i>Completed</span>';
                    }
                    
                    let progBar = playlistItem.querySelector('.playlist-progress-bar');
                    if (progBar) {
                        progBar.className = 'playlist-progress-bar bg-success';
                        progBar.style.width = '100%';
                    }
                }
            }
        }

        // Swapping active playlist items
        document.querySelectorAll('.playlist-item').forEach(item => {
            item.addEventListener('click', function() {
                const currentVideoEl = document.getElementById('trackedHtml5Player');
                if (currentVideoEl) {
                    sendWatchProgress(currentVideoEl.currentTime);
                }

                document.querySelectorAll('.playlist-item').forEach(i => {
                    i.classList.remove('active-playlist-item');
                });
                this.classList.add('active-playlist-item');

                const videoId = this.getAttribute('data-id');
                const embedUrl = this.getAttribute('data-embed');
                const title = this.getAttribute('data-title');
                const desc = this.getAttribute('data-desc');
                const duration = this.getAttribute('data-duration');
                const type = this.getAttribute('data-type');

                activeVideoId = videoId;
                if (container) {
                    container.setAttribute('data-active-id', videoId);
                }

                document.getElementById('activeVideoTitle').textContent = title;
                document.getElementById('activeVideoDescription').textContent = desc;
                
                const durEl = document.getElementById('activeVideoDuration');
                durEl.innerHTML = duration ? `<i class="feather icon-play-circle mr-25"></i>Runtime: ${duration}` : '<i class="feather icon-video mr-25"></i>General Lesson Video';

                // Query and fetch state of the swapped video
                const isCompleted = this.getAttribute('data-completed') === '1';
                const badgeEl = document.getElementById('activeVideoProgressBadge');
                if (badgeEl) {
                    if (isCompleted) {
                        badgeEl.innerHTML = '<span class="badge badge-success px-2 py-1"><i class="feather icon-check-circle mr-25"></i>Completed</span>';
                    } else {
                        badgeEl.innerHTML = '<span class="badge badge-light-secondary px-2 py-1">Not started</span>';
                    }
                }

                if (container) {
                    let playerHtml = '';
                    if (type === 'iframe') {
                        playerHtml = embedUrl;
                    } else if (type === 'video_file' || type === 'video_url' || type === 's3' || type === 'wasabi') {
                        playerHtml = `<video id="trackedHtml5Player" controls class="embed-responsive-item" src="${embedUrl}" autoplay style="width:100%; height:100%; border:none;"></video>`;
                    } else {
                        playerHtml = `<iframe class="embed-responsive-item" width="100%" height="100%" src="${embedUrl}" frameborder="0" allowfullscreen allow="autoplay" style="border:none;"></iframe>`;
                    }
                    container.innerHTML = playerHtml;
                }

                setupVideoTracking();
            });
        });
    });
</script>
@endpush
