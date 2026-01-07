@extends('web.default.layouts.app')

@section('content')
<style>

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
    
<header class="home-topnav">
  <div class="home-nav-row">
    <!-- <div class="home-brand">Kemetic.app</div>
    <nav class="home-nav-links">
      <a href="#">Livestreams</a>
      <a href="#">Courses</a>
      <a href="#">Reels</a>
      <a href="#">Shop</a>
      <a href="#">Television</a>
      <a href="#">Articles</a>
    </nav> -->
    <div class="home-nav-cta">
       @if(auth()->check())
        <a href="/membership"><span class="home-chip">{{ trans('Free') }} • {{ trans('Join') }}</span></a>
        <a href="/membership"><button class="home-btn" style="margin-left:10px">€1/mo or €10/yr</button></a>
      @else
        <a href="/login"><span class="home-chip">{{ trans('Free') }} • {{ trans('Join') }}</span></a>
        <a href="/login"><button class="home-btn" style="margin-left:10px">€1/mo or €10/yr</button></a>
      @endif
    </div>
  </div>
</header>

    <main class="home-container">
      <!-- HERO: Course Spotlight -->
      @if(!empty($bestRateWebinars))
      @foreach($bestRateWebinars as $bestRateWebinar)
      <section class="home-hero">
        <div class="home-hero-wrap">
          <img src="{{ $bestRateWebinar->getImage() }}" class="img-cover" alt="{{ $bestRateWebinar->title }}">
            
            @php
            
    
            $userId = auth()->id();
            $systemIp = getSystemIP();
    
            // Get total stats (for all users)
            $totalStats = DB::table('stats')
            ->where('webinar_id', $bestRateWebinar->id)
            ->selectRaw('SUM(likes) as total_likes, SUM(views) as total_views, SUM(shares) as total_shares')
            ->first();
    
            // Check if the system IP or logged-in user has interacted
            $userStats = DB::table('stats')
            ->where('webinar_id', $bestRateWebinar->id)
            ->when($userId, function ($query) use ($userId) {
            return $query->where('user_id', $userId);
            }, function ($query) use ($systemIp) {
            return $query->where('ip_address', $systemIp);
            })
            ->selectRaw('SUM(likes) as user_likes, SUM(views) as user_views, SUM(shares) as user_shares')
            ->first();
    
            // Define icon classes based on user/system IP interaction
            $likeIconClass = ($userStats->user_likes ?? 0) > 0 ? 'text-primary' : 'stats_icon';
            $viewIconClass = ($userStats->user_views ?? 0) > 0 ? 'text-primary' : 'stats_icon';
            $shareIconClass = ($userStats->user_shares ?? 0) > 0 ? 'text-primary' : 'stats_icon';
            @endphp

          <div class="home-rail">
            <div class="home-pill"><span class="home-icon home-like">❤️</span><span class="home-count">{{ $totalStats->total_likes ?? 0 }}</span></div>
            <div class="home-pill"><span class="home-icon home-play">▶︎</span><span class="home-count">{{ $totalStats->total_views ?? 0 }}</span></div>
            <div class="home-pill"><span class="home-icon home-share">↗︎</span><span class="home-count">{{ $totalStats->total_shares ?? 0 }}</span></div>
          </div>
          <div class="home-hero-content">
            <h1 class="home-hero-title">{{ clean($bestRateWebinar->title,'title') }}</h1>
            <p class="home-hero-sub">{{ convertMinutesToHourAndMinute($bestRateWebinar->duration) }} {{ trans('home.hours') }} · <a href="{{ $bestRateWebinar->teacher->getProfileUrl() }}" target="_blank" class="user-name ml-5 font-14">{{ $bestRateWebinar->teacher->full_name }}</p>
            <div class="home-stars">
              <span class="home-star home-s1"></span><span class="home-star home-s2"></span>
              <span class="home-star home-s3"></span><span class="home-star home-s4"></span>
              <span class="home-star home-s5"></span>
              <span style="margin-left:10px;opacity:.85;font-weight:800">{{ $totalStats->total_likes ?? 0 }}+</span>
            </div>
            <a href="{{ $bestRateWebinar->getUrl() }}" ><button class="home-btn">{{ trans('Join Course') }}</button></a>
          </div>
        </div>
      </section>
      
      @endforeach
      @endif

      <!-- Marquee Section -->
      <!-- <section class="slider-all">
        <div class="container overflow-hidden" data-aos="fade-up">
          <div class="row">
            <div class="col-12">
              <div class="marquee-block marquee1">
                <ul class="marquee-item-list ps-0">
                  <li>
                    <a href="https://apps.apple.com/nl/app/kemetic-app/id6479200304?l=en-GB" target="_blank"><img src="/assets/default/img/home/apple-pay.svg" class="" alt="apple-pay"></a>
                  </li>
                  <li>
                    <a href="https://play.google.com/store/apps/details?id=com.app.kemeticapp&pcampaignid=web_share&pli=1/" target="_blank"><img src="/assets/default/img/home/google-pay.svg" class="" alt="google-pay"></a>
                  </li>
                  <li>
                    <img src="/assets/default/img/home/connection.svg" class="" alt="connection-img">
                  </li>
                  <li> 
                    <img src="/assets/default/img/home/payment.svg" class="" alt="connection-img">
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section> -->

      <!-- GLOBAL TOP RANKED REELS (Featured Classes) -->
      @if(!empty($reels) and !$reels->isEmpty())
      <section>
        <div class="home-row-head">
          <h2>{{ trans('Global Top Ranked Portals') }}</h2>
          <span class="home-chip">#1–#10</span>
        </div>
        <div class="home-scroller" id="reels">
          @foreach($reels as $index => $reel)
          <article class="home-card" aria-label="Reel">
            <div class="home-thumb-round">
               <video class="reel-video" controls preload="metadata" poster="{{ $reel->thumbnail_url }}">
                                    <source src="{{ $reel->video_url }}" type="video/mp4">
                                    {{ trans('public.browser_not_support_video') }}
                                </video>
              <!-- <span class="home-badge-live">#{{ $index + 1 }}</span> -->
            </div>
            <div class="home-meta">
              <span>⭐</span><span class="home-gold">{{ $reel->likes_count ?: 0 }}</span>
            </div>
            <div class="home-actions">
                <button class="home-btn play-video-btn" 
                    data-video-url="{{ $reel->video_url }}" 
                    data-title="{{ $reel->title }}" 
                    data-thumbnail="{{ $reel->thumbnail_url }}">
              Watch
            </button>
              <!-- <a href="{{ $reel->id }}"><button class="home-btn">{{ trans('Watch') }}</button></a> -->
              <div class="home-chakra">
                <span class="home-dot"></span><span class="home-dot"></span><span class="home-dot"></span><span class="home-dot"></span><span class="home-dot"></span>
              </div>
            </div>
          </article>
          @endforeach
        </div>
      </section>
      @endif

      <!-- CLASSES (Latest Classes) -->
      @if(!empty($latestWebinars) and !$latestWebinars->isEmpty())
      <section>
        <div class="home-row-head"><h2>{{ trans('Courses') }}</h2><a href="/classes" class="home-chip">{{ trans('View all') }}</a></div>
        <div class="home-grid">
          @foreach($latestWebinars as $latestWebinar)
          <div class="home-tile">
            <div class="home-thumb"><img src="{{ $latestWebinar->getImage() }}" alt="{{ $latestWebinar->title }}"></div>
            <div class="home-title">{{ Str::limit($latestWebinar->title, 13, '..') }}</div>
            <div class="home-meta"><span>⏱</span><span>{{ convertMinutesToHourAndMinute($latestWebinar->duration) }} {{ trans('hours') }}</span></div>
            <div class="home-actions">
              <a href="{{ $latestWebinar->getUrl() }}"><button class="home-btn">{{ trans('Enroll Now') }}</button></a>
            </div>
          </div>
          @endforeach
        </div>
      </section>
      @endif

      <!-- SHOP (Products) -->
      @if(!empty($newProducts) and !$newProducts->isEmpty())
      <section>
        <div class="home-row-head"><h2>{{ trans('Shop') }}</h2><a href="/products" class="home-chip">{{ trans('See all') }}</a></div>
        <div class="home-grid">
          @foreach($newProducts as $product)
          <div class="home-tile">
            <div class="home-thumb"><img src="{{ $product->thumbnail }}" alt="{{ $product->title }}"></div>
            <div class="home-title">{{ Str::limit($product->title, 13, '..') }} </div>
            <div class="home-price">{{ handlePrice($product->price, true, true, false, null, true) }}</div>
            <div class="home-actions">
                <a href="{{ $product->getUrl() }}"><button class="home-btn">{{ trans('Add to Cart') }}</button></a>
            </div>
          </div>
          @endforeach
        </div>
      </section>
      @endif

      <!-- BOOKS (Bundles) -->
      @if(!empty($books) and !$books->isEmpty())
      <section>
        <div class="home-row-head"><h2>Scrolls</h2><a href="/classes?type[]=bundle" class="home-chip">{{ trans('Browse') }}</a></div>
        <div class="home-grid">
          @foreach($books as $book)
          <div class="home-tile">
            <div class="home-thumb"><img src="{{ $book->getImage() }}" alt="{{ $book->title }}"></div>
            <div class="home-title">{{ Str::limit($book->title, 13, '..') }} </div>
            <div class="home-meta">
              @if($book->price == 0)
                <span class="home-chip">{{ trans('Free with Membership') }}</span>
              @endif
            </div>
            <div class="home-actions">
              @if($book->price == 0)
                <a href="{{ $book->getUrl() }}"><button class="home-btn">{{ trans('Read eBook') }}</button></a>
              @else
                <a href="{{ $book->getUrl() }}"><button class="home-btn">{{ trans('Buy Audiobook') }}</button></a>
              @endif
            </div>
          </div>
          @endforeach
        </div>
      </section>
      @endif

      <!-- LIVE NOW (Livestreams) -->
      <section>
        <div class="home-row-head"><h2>{{ trans('Live Now') }}</h2><a class="home-chip" href="#">{{ trans('Join Live') }}</a></div>
        <div class="home-tv">
          <div class="home-tile" style="position:relative;text-align:center">
            <div class="home-thumb-round"><img src="https://images.unsplash.com/photo-1544717305-2782549b5136?q=80&w=900" alt="image"></div>
            <div class="home-badge-live">LIVE</div>
            <div class="home-meta" style="justify-content:center"><span>👁️</span><span class="home-gold">9.2k</span></div>
            <button class="home-btn">{{ trans('Join Live') }}</button>
          </div>
          <div class="home-tile" style="text-align:center">
            <div class="home-thumb-round"><img src="https://images.unsplash.com/photo-1516826957135-700dedea698c?q=80&w=900" alt="image"></div>
            <div class="home-meta" style="justify-content:center"><span>⭐</span><span class="home-gold">{{ trans('preview') }}</span></div>
            <button class="home-btn">{{ trans('Set Reminder') }}</button>
          </div>
        </div>
      </section>

      <!-- ARTICLES (Blog) -->
      @if(!empty($blog) and !$blog->isEmpty())
      <section>
        <div class="home-row-head"><h2>{{ trans('Articles') }}</h2><a href="/blog" class="home-chip">{{ trans('Explore') }}</a></div>
        <div class="home-grid">
          @foreach($blog as $post)
          <div class="home-tile">
            <div class="home-thumb"><img src="{{ $post->image }}" alt="{{ $post->title }}"></div>
            <div class="home-title">{{ Str::limit($post->title, 13, '..') }} </div>
            <div class="home-meta"><span>❤️ {{ $post->likes_count }}</span><span style="margin-left:10px">💬 {{ $post->comments_count }}</span></div>
            <div class="home-actions">
              <a href="{{ $post->getUrl() }}"><button class="home-btn">{{ trans('Read') }}</button></a>
            </div>
          </div>
          @endforeach
        </div>
      </section>
      @endif

      <!-- Footer -->
      <section style="padding-bottom:60px">
        <div class="home-row-head">
          <span class="home-chip">© Kemetic.app</span>
          <span class="home-chip">{{ trans('membership') }} €1/mo or €10/yr</span>
        </div>
      </section>
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
    </main>
@endsection

@if(session()->has('toast'))
    <script>
        (function () {
            "use strict";

            $.toast({
                heading: '{{ session()->get('toast')['title'] ?? '' }}',
                text: '{{ session()->get('toast')['msg'] ?? '' }}',
                bgColor: '@if(session()->get('toast')['status'] == 'success') #43d477 @else #f63c3c @endif',
                textColor: 'white',
                hideAfter: 10000,
                position: 'bottom-right',
                icon: '{{ session()->get('toast')['status'] }}'
            });
        })(jQuery)
    </script>
@endif

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
      
      const formData = new FormData(this);
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

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        // Marquee animation
        var $list = $('.marquee-item-list');
        var $clone = $list.children('li').clone();
        $list.append($clone);

        // Smooth wheel for horizontal scrollers
        for (const s of document.querySelectorAll('.home-scroller')) {
            s.addEventListener('wheel', (e) => {
                if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
                    s.scrollLeft += e.deltaY;
                    e.preventDefault();
                }
            }, {passive:false});
        }

        // Membership section scroll
        if (window.location.hash === '#memberShip') {
            setTimeout(function() {
                const targetElement = document.querySelector('#memberShip');
                if (targetElement) {
                    const offset = 75;
                    const elementPosition = targetElement.getBoundingClientRect().top + window.pageYOffset;
                    const offsetPosition = elementPosition - offset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: "smooth"
                    });
                }
            }, 300);
        }
    });
</script>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
