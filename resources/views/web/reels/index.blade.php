@extends('web.default.layouts.app')
<style>
/* Modal Theme Variables */
:root {
  /* Light theme (default) */
  --modal-bg: #ffffff;
  --modal-border: #e5e7eb;
  --modal-text: #1f2937;
  --modal-header-bg: #f9fafb;
  --modal-input-bg: #ffffff;
  --modal-input-border: #d1d5db;
  --modal-input-text: #374151;
  --modal-placeholder: #9ca3af;
  --modal-close-btn: #6b7280;
  --modal-btn-secondary-bg: #f3f4f6;
  --modal-btn-secondary-text: #374151;
  --modal-btn-primary-bg: var(--gold);
  --modal-btn-primary-text: #000000;
  --modal-progress-bg: #e5e7eb;
  --modal-progress-fill: #10b981;
  --modal-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

[data-theme="dark"] {
  /* Dark theme */
  --modal-bg: #1a1a1a;
  --modal-border: #333333;
  --modal-text: #e5e5e5;
  --modal-header-bg: #222222;
  --modal-input-bg: #2d2d2d;
  --modal-input-border: #404040;
  --modal-input-text: #e5e5e5;
  --modal-placeholder: #8c8c8c;
  --modal-close-btn: #a6a6a6;
  --modal-btn-secondary-bg: #333333;
  --modal-btn-secondary-text: #e5e5e5;
  --modal-btn-primary-bg: var(--gold);
  --modal-btn-primary-text: #000000;
  --modal-progress-bg: #404040;
  --modal-progress-fill: var(--gold);
  --modal-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 10px 10px -5px rgba(0, 0, 0, 0.3);
}

/* Modal Overlay */
.modal {
  background: rgba(0, 0, 0, 0.7);
  backdrop-filter: saturate(180%) blur(8px);
}

.modal-dialog {
  max-width: 600px;
}

/* Modal Content - Integrated with reels theme */
.modal-content {
  background: var(--modal-bg);
  border: 1px solid var(--modal-border);
  border-radius: 18px;
  color: var(--modal-text);
  box-shadow: var(--modal-shadow);
  overflow: hidden;
}

/* Modal Header */
.modal-header {
  background: var(--modal-header-bg);
  border-bottom: 1px solid var(--modal-border);
  padding: 20px 24px;
  border-radius: 18px 18px 0 0;
}

.modal-title {
  font-weight: 900;
  font-size: 22px;
  color: var(--modal-text);
  margin: 0;
}

/* Close Button */
.btn-close {
  background: transparent;
  border: none;
  opacity: 0.7;
  transition: opacity 0.2s ease;
  padding: 8px;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%236b7280'%3e%3cpath d='M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z'/%3e%3c/svg%3e");
  filter: none;
  width: 24px;
  height: 24px;
}

[data-theme="dark"] .btn-close {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23a6a6a6'%3e%3cpath d='M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z'/%3e%3c/svg%3e");
}

.btn-close:hover {
  opacity: 1;
}

/* Modal Body */
.modal-body {
  padding: 24px;
  background: var(--modal-bg);
}

/* Form Elements */
.form-label {
  font-weight: 700;
  color: var(--modal-text);
  margin-bottom: 8px;
  display: block;
}

.form-control {
  background: var(--modal-input-bg);
  border: 1px solid var(--modal-input-border);
  border-radius: 10px;
  color: var(--modal-input-text);
  padding: 12px 16px;
  font-size: 15px;
  transition: all 0.2s ease;
  width: 100%;
}

.form-control:focus {
  outline: none;
  border-color: var(--gold);
  box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
}

.form-control::placeholder {
  color: var(--modal-placeholder);
  opacity: 0.7;
}

/* File Upload Styling */
input[type="file"] {
  cursor: pointer;
}

input[type="file"]::file-selector-button {
  background: var(--modal-btn-secondary-bg);
  color: var(--modal-btn-secondary-text);
  border: 1px solid var(--modal-input-border);
  border-radius: 8px;
  padding: 8px 16px;
  margin-right: 12px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.2s ease;
}

input[type="file"]::file-selector-button:hover {
  background: var(--modal-input-border);
}

/* Video Preview */
#videoPreview video {
  background: #000;
  border-radius: 10px;
  border: 1px solid var(--modal-border);
}

/* Progress Bar */
.progress {
  background: var(--modal-progress-bg);
  border-radius: 999px;
  height: 12px;
  overflow: hidden;
  margin: 24px 0;
}

.progress-bar {
  background: var(--modal-progress-fill);
  border-radius: 999px;
  transition: width 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 11px;
  font-weight: 700;
  color: #000;
  text-shadow: 0 0 1px rgba(255, 255, 255, 0.5);
}

/* Buttons */
.d-flex.justify-content-end {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  margin-top: 24px;
}

.btn {
  border: none;
  border-radius: 999px;
  padding: 12px 24px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s ease;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-size: 15px;
  text-decoration: none;
}

.btn-secondary {
  background: var(--modal-btn-secondary-bg);
  color: var(--modal-btn-secondary-text);
  border: 1px solid var(--modal-input-border);
}

.btn-secondary:hover {
  background: var(--modal-input-border);
  transform: translateY(-1px);
}

.btn-primary {
  background: var(--modal-btn-primary-bg);
  color: var(--modal-btn-primary-text);
  font-weight: 900;
}

.btn-primary:hover {
  background: #e0b52d;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
}

/* Text Muted */
.text-muted {
  color: var(--modal-placeholder) !important;
  font-size: 13px;
  margin-top: 6px;
  display: block;
}

/* Utility Classes */
.d-none {
  display: none !important;
}

.mt-2 {
  margin-top: 8px !important;
}

.mb-3 {
  margin-bottom: 16px !important;
}

/* Responsive */
@media (max-width: 640px) {
  .modal-dialog {
    margin: 16px;
    max-width: calc(100% - 32px);
  }
  
  .modal-header,
  .modal-body {
    padding: 20px;
  }
  
  .modal-title {
    font-size: 20px;
  }
  
  .form-control {
    padding: 10px 14px;
    font-size: 14px;
  }
  
  .btn {
    padding: 10px 20px;
    font-size: 14px;
  }
  
  .progress {
    height: 10px;
  }
}

/* Animation for modal */
.modal.fade .modal-dialog {
  transform: translateY(-20px);
  transition: transform 0.3s ease-out;
}

.modal.show .modal-dialog {
  transform: translateY(0);
}

/* Video Player Modal */
#videoPlayerModal .modal-dialog {
  max-width: 90%;
  max-height: 90vh;
}

#videoPlayerModal .modal-content {
  background: #000;
  border-radius: 0;
}

#videoPlayerModal .modal-body {
  padding: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  background: #000;
}

#videoPlayerModal #videoPlayer {
  width: 100%;
  height: auto;
  max-height: 80vh;
}

#videoPlayerModal .modal-header {
  background: rgba(0, 0, 0, 0.8);
  border-bottom: 1px solid #333;
  position: absolute;
  top: 0;
  right: 0;
  left: 0;
  z-index: 10;
}

#videoPlayerModal .modal-title {
  color: white;
}

#videoPlayerModal .btn-close {
  filter: brightness(0) invert(1);
}

</style>
@section('content')

<!-- membership banner -->
<div class="reels-banner">
  <div class="reels-wrap">
    <span>Unlock Unlimited Portals, Courses & Livestreams — €1/month or €10/year</span>
     @if(auth()->check())
          <button class="reels-btn"><a href="/membership">Join Now</a></button>
        @else
          <button class="reels-btn"><a href="/login">Join Now</a></button>
        @endif  
  </div>
</div>

<main class="reels-container">

  <!-- HERO: last month global #1 -->
  <section class="reels-hero">
    <div class="reels-hero-card">
      <video class="img" controls preload="metadata" poster="{{ $heroreels->thumbnail_url }}">
                                    <source src="{{ $heroreels->video_url }}" type="video/mp4">
                                    {{ trans('public.browser_not_support_video') }}
                                </video>
      <div class="reels-rail">
        <div class="reels-pill"><span class="reels-circle reels-bg-red">❤</span><b>{{ $heroreels->likes_count }}</b></div>
        <div class="reels-pill"><span class="reels-circle reels-bg-blue">▶</span><b>{{ $heroreels->views_count }}</b></div>
        <div class="reels-pill"><span class="reels-circle reels-bg-yellow">↗</span><b>{{ $heroreels->comments_count }}</b></div>
      </div>
      <div class="reels-hero-body">
        <div class="reels-sub">Last month's Global #1</div>
        <h1 class="reels-title">{{ $heroreels->title }}</h1>
        <div class="reels-sub">{{ $heroreels->caption }}</div>
        <div class="reels-chakra-stars">
          <span class="reels-dot reels-bg-red"></span><span class="reels-dot reels-bg-orange"></span>
          <span class="reels-dot reels-bg-yellow"></span><span class="reels-dot reels-bg-green"></span>
          <span class="reels-dot reels-bg-blue"></span><b style="margin-left:10px">3,255+</b>
        </div>
        <button class="reels-btn play-video-btn" data-video-url="{{ $heroreels->video_url }}" data-title="{{ $heroreels->title }}" data-thumbnail="{{ $heroreels->thumbnail_url }}">Watch Portals</button>
        <a class="reels-go-profile" href="/user/1066/profile">→ View profile</a>
      </div>
    </div>
    <div>
      <h2>Portals</h2>
      <div class="reels-sub">{{ $heroreels->title }}</div>
      <p class="reels-sub">{{ $heroreels->caption }}</p>
    </div>
  </section>

  <!-- GLOBAL -->
  <section>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
          <h2 class="section-title mb-2">🌍 Global Portals</h2>
          <p class="section-subtitle"></p>
      </div>
      @if (auth()->check()) 
      <button type="button" class="reels-btn" data-bs-toggle="modal" data-bs-target="#uploadModal">
          <i data-feather="plus-circle"></i>
          Create Portals
      </button>
      @endif
    </div>
    <div class="reels-scroller">
      <!-- repeat cards -->
       @foreach($reels as $reel)
      <article class="reels-card">
        <span class="reels-rank">{{ $reel->title }}</span>
        <div class="reels-thumb"> <video class="img" controls preload="metadata" poster="{{ $reel->thumbnail_url }}">
                                    <source src="{{ $reel->video_url }}" type="video/mp4">
                                    {{ trans('public.browser_not_support_video') }}
                                </video></div>
        <div class="reels-rail-vert">
            <form action="{{ route('reels.like', $reel->id) }}" method="POST" class="d-inline like-form">
            @csrf
                <button type="submit" class="action-btn d-flex align-items-center {{ $reel->likes->count() > 0 ? 'liked' : '' }}">
                <i data-feather="heart" width="20" height="20"></i>
                <span class="reels-circle reels-bg-red">{{ $reel->likes_count }}</span> </button>
            </form>

          <span class="reels-circle reels-bg-yellow">{{ $reel->comments_count }}</span>
          <span class="reels-circle reels-bg-green">↗</span>
          <span class="reels-circle reels-bg-blue">🎁</span>
        </div>
        <div class="reels-meta">
          <div class="reels-dot reels-bg-red"></div><div class="reels-dot reels-bg-orange"></div><div class="reels-dot reels-bg-yellow"></div>
          <div class="reels-dot reels-bg-green"></div><div class="reels-dot reels-bg-blue"></div>
          <span class="reels-count">5,340+</span>
        </div>
        <div class="reels-cta">
          <button class="reels-btn-sm play-video-btn" 
                  data-video-url="{{ $reel->video_url }}" 
                  data-title="{{ $reel->title }}" 
                  data-thumbnail="{{ $reel->thumbnail_url }}">
            Watch
          </button>
        </div>
      </article>
      @endforeach

      
    </div>
    <div class="mt-50 pt-30">
      {{ $reels->appends(request()->input())->links('vendor.pagination.panel') }}
    </div>
  </section>

  <!-- TRENDING -->
  <section>
    <h2>🔥 Trending Portals</h2>
    <div class="reels-scroller">
     @foreach($reels as $reel)
      <article class="reels-card">
        <span class="reels-rank">{{ $reel->title }}</span>
        <div class="reels-thumb"> <video class="reel-video" controls preload="metadata" poster="{{ $reel->thumbnail_url }}">
                                    <source src="{{ $reel->video_url }}" type="video/mp4">
                                    {{ trans('public.browser_not_support_video') }}
                                </video></div>
        <div class="reels-rail-vert">
            <form action="{{ route('reels.like', $reel->id) }}" method="POST" class="d-inline like-form">
            @csrf
                <button type="submit" class="action-btn d-flex align-items-center {{ $reel->likes->count() > 0 ? 'liked' : '' }}">
                <i data-feather="heart" width="20" height="20"></i>
                <span class="reels-circle reels-bg-red">{{ $reel->likes_count }}</span> </button>
            </form>

          <span class="reels-circle reels-bg-yellow">{{ $reel->comments_count }}</span>
          <span class="reels-circle reels-bg-green">↗</span>
          <span class="reels-circle reels-bg-blue">🎁</span>
        </div>
        <div class="reels-meta">
          <div class="reels-dot reels-bg-red"></div><div class="reels-dot reels-bg-orange"></div><div class="reels-dot reels-bg-yellow"></div>
          <div class="reels-dot reels-bg-green"></div><div class="reels-dot reels-bg-blue"></div>
          <span class="reels-count">5,340+</span>
        </div>
        <div class="reels-cta">
          <button class="reels-btn-sm play-video-btn" 
                  data-video-url="{{ $reel->video_url }}" 
                  data-title="{{ $reel->title }}" 
                  data-thumbnail="{{ $reel->thumbnail_url }}">
            Watch
          </button>
        </div>
      </article>
      @endforeach
    </div>
  </section>

  <!-- LIVE NOW -->
  <!-- <section>
    <h2>🔴 Live Now</h2>
    <div class="reels-scroller">
     @foreach($reels as $reel)
      <article class="reels-card">
        <span class="reels-rank">{{ $reel->title }}</span>
        <div class="reels-thumb"> <video class="reel-video" controls preload="metadata" poster="{{ $reel->thumbnail_url }}">
                                    <source src="{{ $reel->video_url }}" type="video/mp4">
                                    {{ trans('public.browser_not_support_video') }}
                                </video></div>
        <div class="reels-rail-vert">
            <form action="{{ route('reels.like', $reel->id) }}" method="POST" class="d-inline like-form">
            @csrf
                <button type="submit" class="action-btn d-flex align-items-center {{ $reel->likes->count() > 0 ? 'liked' : '' }}">
                <i data-feather="heart" width="20" height="20"></i>
                <span class="reels-circle reels-bg-red">{{ $reel->likes_count }}</span> </button>
            </form>

          <span class="reels-circle reels-bg-yellow">{{ $reel->comments_count }}</span>
          <span class="reels-circle reels-bg-green">↗</span>
          <span class="reels-circle reels-bg-blue">🎁</span>
        </div>
        <div class="reels-meta">
          <div class="reels-dot reels-bg-red"></div><div class="reels-dot reels-bg-orange"></div><div class="reels-dot reels-bg-yellow"></div>
          <div class="reels-dot reels-bg-green"></div><div class="reels-dot reels-bg-blue"></div>
          <span class="reels-count">5,340+</span>
        </div>
        <div class="reels-cta"><button class="reels-btn-sm">Watch</button></div>
      </article>
      @endforeach
    </div>
  </section> -->

  <!-- FOR ME -->
  <!-- <section>
    <h2>💫 For Me</h2>
    <div class="reels-scroller">
      @foreach($reels as $reel)
      <article class="reels-card">
        <span class="reels-rank">{{ $reel->title }}</span>
        <div class="reels-thumb"> <video class="reel-video" controls preload="metadata" poster="{{ $reel->thumbnail_url }}">
                                    <source src="{{ $reel->video_url }}" type="video/mp4">
                                    {{ trans('public.browser_not_support_video') }}
                                </video></div>
        <div class="reels-rail-vert">
            <form action="{{ route('reels.like', $reel->id) }}" method="POST" class="d-inline like-form">
            @csrf
                <button type="submit" class="action-btn d-flex align-items-center {{ $reel->likes->count() > 0 ? 'liked' : '' }}">
                <i data-feather="heart" width="20" height="20"></i>
                <span class="reels-circle reels-bg-red">{{ $reel->likes_count }}</span> </button>
            </form>

          <span class="reels-circle reels-bg-yellow">{{ $reel->comments_count }}</span>
          <span class="reels-circle reels-bg-green">↗</span>
          <span class="reels-circle reels-bg-blue">🎁</span>
        </div>
        <div class="reels-meta">
          <div class="reels-dot reels-bg-red"></div><div class="reels-dot reels-bg-orange"></div><div class="reels-dot reels-bg-yellow"></div>
          <div class="reels-dot reels-bg-green"></div><div class="reels-dot reels-bg-blue"></div>
          <span class="reels-count">5,340+</span>
        </div>
        <div class="reels-cta"><button class="reels-btn-sm">Watch</button></div>
      </article>
      @endforeach
    </div>
  </section> -->

  <!-- CLASSES -->
  <!-- <section>
    <h2>🎓 Classes / Courses</h2>
    <div class="reels-scroller">
     @foreach($reels as $reel)
      <article class="reels-card">
        <span class="reels-rank">{{ $reel->title }}</span>
        <div class="reels-thumb"> <video class="reel-video" controls preload="metadata" poster="{{ $reel->thumbnail_url }}">
                                    <source src="{{ $reel->video_url }}" type="video/mp4">
                                    {{ trans('public.browser_not_support_video') }}
                                </video></div>
        <div class="reels-rail-vert">
            <form action="{{ route('reels.like', $reel->id) }}" method="POST" class="d-inline like-form">
            @csrf
                <button type="submit" class="action-btn d-flex align-items-center {{ $reel->likes->count() > 0 ? 'liked' : '' }}">
                <i data-feather="heart" width="20" height="20"></i>
                <span class="reels-circle reels-bg-red">{{ $reel->likes_count }}</span> </button>
            </form>

          <span class="reels-circle reels-bg-yellow">{{ $reel->comments_count }}</span>
          <span class="reels-circle reels-bg-green">↗</span>
          <span class="reels-circle reels-bg-blue">🎁</span>
        </div>
        <div class="reels-meta">
          <div class="reels-dot reels-bg-red"></div><div class="reels-dot reels-bg-orange"></div><div class="reels-dot reels-bg-yellow"></div>
          <div class="reels-dot reels-bg-green"></div><div class="reels-dot reels-bg-blue"></div>
          <span class="reels-count">5,340+</span>
        </div>
        <div class="reels-cta"><button class="reels-btn-sm">Watch</button></div>
      </article>
      @endforeach
    </div>
  </section> -->

  <div class="modal fade" id="videoPlayerModal" tabindex="-1" aria-labelledby="videoPlayerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="videoPlayerModalLabel">Video Player</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <video id="videoPlayer" controls style="width: 100%; border-radius: 8px;">
            Your browser does not support the video tag.
          </video>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload New Portals</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="uploadForm" action="/reels" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Video (Max 250MB)</label>
                        <input type="file" class="form-control" id="videoFile" name="video" accept="video/*" required>
                        <div id="videoPreview" class="mt-2 d-none">
                            <video controls style="max-width: 100%; max-height: 400px">
                                <source src="" type="video/mp4">
                            </video>
                        </div>
                        <small class="text-muted">Supported formats: MP4, MOV, OGG, WebM</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required maxlength="255" placeholder="Enter a title for your portals">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Caption</label>
                        <textarea class="form-control" id="caption" name="caption" required maxlength="1000" rows="3" placeholder="Write a caption..."></textarea>
                    </div>
                    <div class="progress mb-3">
                        <div id="uploadProgress" class="progress-bar bg-success" role="progressbar" style="width: 0%">0%</div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i data-feather="upload-cloud"></i>
                            Upload Reel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</main>
@endsection
<script>
  // smooth mouse-wheel horizontal scrolling for carousels
  document.querySelectorAll('.reels-scroller').forEach(s=>{
    s.addEventListener('wheel', e=>{
      if(Math.abs(e.deltaY)>Math.abs(e.deltaX)){ s.scrollLeft+=e.deltaY; e.preventDefault(); }
    }, {passive:false});
  });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Video playback functionality
document.addEventListener('DOMContentLoaded', function() {
  // Get modal elements
  const videoPlayerModal = document.getElementById('videoPlayerModal');
  const videoPlayer = document.getElementById('videoPlayer');
  const videoPlayerModalLabel = document.getElementById('videoPlayerModalLabel');
  
  // Function to play video
  function playVideo(videoUrl, title) {
    // Set video source and title
    videoPlayer.src = videoUrl;
    videoPlayerModalLabel.textContent = title;
    
    // Show modal
    const modal = new bootstrap.Modal(videoPlayerModal);
    modal.show();
    
    // Play video when modal is shown
    videoPlayerModal.addEventListener('shown.bs.modal', function() {
      videoPlayer.play().catch(function(error) {
        console.log('Autoplay failed:', error);
        // If autoplay fails, show controls and let user play manually
        videoPlayer.controls = true;
      });
    });
  }
  
  // Add click event to all "Watch" buttons
  document.querySelectorAll('.play-video-btn').forEach(function(button) {
    button.addEventListener('click', function() {
      const videoUrl = this.getAttribute('data-video-url');
      const title = this.getAttribute('data-title');
      playVideo(videoUrl, title);
    });
  });
  
  // Add click event to video thumbnails
  document.querySelectorAll('.video-thumbnail').forEach(function(thumbnail) {
    thumbnail.addEventListener('click', function() {
      const card = this.closest('.reels-card');
      const watchButton = card.querySelector('.play-video-btn');
      if (watchButton) {
        const videoUrl = watchButton.getAttribute('data-video-url');
        const title = watchButton.getAttribute('data-title');
        playVideo(videoUrl, title);
      }
    });
  });
  
  // Pause video when modal is closed
  videoPlayerModal.addEventListener('hidden.bs.modal', function() {
    videoPlayer.pause();
    videoPlayer.currentTime = 0;
  });
  
  // Handle video upload preview
  const videoFileInput = document.getElementById('videoFile');
  const videoPreview = document.getElementById('videoPreview');
  
  if (videoFileInput) {
    videoFileInput.addEventListener('change', function() {
      const file = this.files[0];
      if (file && file.type.startsWith('video/')) {
        const videoElement = videoPreview.querySelector('video');
        const sourceElement = videoElement.querySelector('source');
        
        const url = URL.createObjectURL(file);
        sourceElement.src = url;
        videoElement.load();
        
        videoPreview.classList.remove('d-none');
        
        // Revoke object URL after video is loaded
        videoElement.onloadeddata = function() {
          URL.revokeObjectURL(url);
        };
      }
    });
  }
  
  // Handle form submission with progress
  const uploadForm = document.getElementById('uploadForm');
  const uploadProgress = document.getElementById('uploadProgress');
  
  if (uploadForm) {
    uploadForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      uploadProgress.style.width = '0%';
      uploadProgress.textContent = '0%';

      const formData = new FormData(this);

      const videoFile = document.getElementById('videoFile').files[0];

      if (videoFile && videoFile.size > 250 * 1024 * 1024) {
        alert('File size exceeds 250MB limit. Please choose a smaller video.');
        return;
      }
      
      const xhr = new XMLHttpRequest();
      
      xhr.upload.addEventListener('progress', function(e) {
        if (e.lengthComputable) {
          const percentComplete = (e.loaded / e.total) * 100;
          uploadProgress.style.width = percentComplete + '%';
          uploadProgress.textContent = Math.round(percentComplete) + '%';
        }
      });
      
      xhr.addEventListener('load', function() {
        if (xhr.status === 200) {
          // const response = JSON.parse(xhr.responseText);

          //if (response.success) {
            uploadProgress.style.width = '100%';
            uploadProgress.textContent = '100%';
            
            // Show success message
            alert('Video uploaded successfully!');
            
            // Close modal and reload page
            const modal = bootstrap.Modal.getInstance(document.getElementById('uploadModal'));
            modal.hide();
            setTimeout(() => {
              window.location.reload();
            }, 1000);
          //} else {
            //alert('Error: ' + (response.message || 'Unknown error occurred'));
          //}
          
        } else {
          alert('Error uploading video. Please try again.');
        }
      });
      
      xhr.addEventListener('error', function() {
        alert('Error uploading video. Please check your connection.');
      });
      
      xhr.open('POST', this.action);
      xhr.send(formData);
    });
  }
  
  // Smooth mouse-wheel horizontal scrolling for carousels
  document.querySelectorAll('.reels-scroller').forEach(function(scroller) {
    scroller.addEventListener('wheel', function(e) {
      if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
        this.scrollLeft += e.deltaY;
        e.preventDefault();
      }
    }, { passive: false });
  });
});

// Initialize Feather icons
if (typeof feather !== 'undefined') {
  feather.replace();
}
</script>