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

/* Theme Toggle Button (optional, if you want to add theme switching) */
.theme-toggle {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background: var(--modal-bg);
  border: 1px solid var(--modal-border);
  border-radius: 50%;
  width: 48px;
  height: 48px;
  display: grid;
  place-items: center;
  cursor: pointer;
  z-index: 1000;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.theme-toggle svg {
  width: 20px;
  height: 20px;
  fill: var(--modal-text);
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
        <button class="reels-btn">Watch Portals</button>
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
      {{-- Only show Create Reel button if user is authenticated --}}
      <button type="button" class="reels-btn" data-bs-toggle="modal" data-bs-target="#uploadModal">
          <i data-feather="plus-circle"></i>
          Create Reel
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
        <div class="reels-cta"><button class="reels-btn-sm">Watch</button></div>
      </article>
      @endforeach

      
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
        <div class="reels-cta"><button class="reels-btn-sm">Watch</button></div>
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

  <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload New Reel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="uploadForm" action="/reels" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Video (Max 100MB)</label>
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
                        <input type="text" class="form-control" id="title" name="title" required maxlength="255" placeholder="Enter a title for your reel">
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