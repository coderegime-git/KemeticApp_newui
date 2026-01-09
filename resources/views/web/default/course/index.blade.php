@extends('web.default.layouts.app')

@section('content')
 
<div class="coursedetail-wrap">
  <!-- HERO -->
  <div class="coursedetail-hero">
    @php
      $videoDemo = $course->video_demo;
      $videoSource = $course->video_demo_source;
      $isVideoTag = false;
      $isYoutube = false;
      $isVimeo = false;
      $isIframe = false;
      $iframeSrc = '';
      
      if ($videoDemo && $videoSource)
      {
        switch($videoSource)
        {
            case 'upload':
                // Check if it's a video file
                $videoExtensions = ['.mp4', '.webm', '.ogg', '.mov', '.avi', '.m4v', '.wmv'];
                $isVideoTag = Str::endsWith(strtolower($videoDemo), $videoExtensions);
                break;
                
            case 'youtube':
                $isYoutube = true;
                $isIframe = true;
                // Extract YouTube video ID
                $youtubeId = '';
                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $videoDemo, $matches)) {
                    $youtubeId = $matches[1];
                }
                $iframeSrc = "https://www.youtube.com/embed/{$youtubeId}?autoplay=0&controls=1&showinfo=0&rel=0&modestbranding=1";
                break;
                
            case 'vimeo':
                $isVimeo = true;
                $isIframe = true;
                // Extract Vimeo video ID
                $vimeoId = '';
                if (preg_match('/(?:vimeo\.com\/(?:video\/)?)(\d+)/i', $videoDemo, $matches)) {
                    $vimeoId = $matches[1];
                }
                $iframeSrc = "https://player.vimeo.com/video/{$vimeoId}?autoplay=0&title=0&byline=0&portrait=0";
                break;
                
            case 'external_link':
            case 'secure_host':
                // Check if it's a direct video file
                $videoExtensions = ['.mp4', '.webm', '.ogg', '.mov', '.avi', '.m4v', '.wmv'];
                $isVideoTag = Str::endsWith(strtolower($videoDemo), $videoExtensions);
                
                // If not a direct video file, try to embed as iframe
                if (!$isVideoTag) {
                    $isIframe = true;
                    $iframeSrc = $videoDemo;
                }
                break;
        }
      }
    @endphp
     @if($videoDemo && $videoSource)
        @if($isVideoTag)
          <video class="img-cover course-cover-img video-cover" 
                  id="courseVideoCover"
                  poster="{{ $videoDemo }}"
                  preload="metadata">
              <source src="{{ $videoDemo }}" type="video/mp4">
              Your browser does not support the video tag.
          </video>
            
        @elseif($isIframe)
          <iframe 
              id="videoIframe"
              class="img-cover course-cover-img"
              src="{{ $iframeSrc }}"
              frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
              allowfullscreen
              loading="lazy"
              data-original-src="{{ $iframeSrc }}"
              data-current-time="0"
              data-is-playing="false">
          </iframe>
        @endif
        
    @else
    <img src="{{ $course->getImageCover() }}" class="img-cover course-cover-img" alt="{{ $course->title }}"/>
    @endif
    <div class="coursedetail-hero-top">
      <div class="coursedetail-glass"><span>⏱</span><span>{{ convertMinutesToHourAndMinute(!empty($course->duration) ? $course->duration : 0) }} {{ trans('home.hours') }} · {{ $course->textLessons->count() }} lessons</span></div>
    </div>
    <div class="coursedetail-hero-top-right">
      <!-- <button class="coursedetail-glass" id="openCurr"><span>📖</span><span>Open curriculum</span></button> -->
    </div>
    @if($videoDemo && $videoSource)
      @if($isVideoTag)
        <div class="coursedetail-play"  id="videoPlayPauseBtn"><div class="coursedetail-btn">▶</div></div>
      
      @endif
    @endif
  </div>
        <!-- <div class="coursedetail-play"  id="embeddedVideoPlayBtn"><div class="coursedetail-btn">▶</div></div> -->

  <h1 class="coursedetail-title"> {{ $course->title }}</h1>
  <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
    <div class="coursedetail-teacher" id="coursedetail-teacher">
      <img src="{{ $course->teacher->avatar }}" class="img-cover course-cover-img" alt="{{ $course->teacher->full_name }}"/>
      <!-- <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=200"> -->
      <span>{{ $course->teacher->full_name }}</span>
    </div>
    <!-- chakra stars + 3,255+ -->
    <div class="coursedetail-chakra">
      <span class="coursedetail-dot" style="background:#E94560"></span>
      <span class="coursedetail-dot" style="background:#F4A261"></span>
      <span class="coursedetail-dot" style="background:#F1C40F"></span>
      <span class="coursedetail-dot" style="background:#2ECC71"></span>
      <span class="coursedetail-dot" style="background:#3498DB"></span>
      <b style="color:var(--gold)">{{ $course->getSalesCount() }}+</b>
    </div>
     @php
      $canSale = ($course->canSale() and !$hasBought);
      $authUserJoinedWaitlist = false;

      if (!empty($authUser)) {
          $authUserWaitlist = $course->waitlists()->where('user_id', $authUser->id)->first();
          $authUserJoinedWaitlist = !empty($authUserWaitlist);
      }
    @endphp
    <form action="/cart/store" method="post">
      {{ csrf_field() }}
      <input type="hidden" name="item_id" value="{{ $course->id }}">
      <input type="hidden" name="item_name" value="webinar_id">
    <div class="coursedetail-cta-sticky">
      @if(!$canSale and $course->canJoinToWaitlist())
        <button type="button" data-slug="{{ $course->slug }}" class="coursedetail-btn {{ (!$authUserJoinedWaitlist) ? ((!empty($authUser)) ? 'js-join-waitlist-user' : 'js-join-waitlist-guest') : 'disabled' }}" {{ $authUserJoinedWaitlist ? 'disabled' : '' }}>
          @if($authUserJoinedWaitlist)
            {{ trans('update.already_joined') }}
            @else
            {{ trans('update.join_waitlist') }}
          @endif
        </button>
      @elseif($hasBought or !empty($course->getInstallmentOrder()))
        <a href="{{ $course->getLearningPageUrl() }}"> <button type="button" class="coursedetail-btn">{{ trans('update.go_to_learning_page') }}</button></a>
      @elseif(!empty($course->price) and $course->price > 0)
        <button type="button" class="coursedetail-btn btn btn-primary {{ $canSale ? 'js-course-add-to-cart-btn' : ($course->cantSaleStatus($hasBought) .' disabled ') }}">
          @if(!$canSale)
            @if($course->checkCapacityReached())
              {{ trans('update.capacity_reached') }}
            @else
              {{ trans('update.disabled_add_to_cart') }}
            @endif
          @else
            {{ trans('public.add_to_cart') }}
          @endif
        </button>

        @if($canSale and !empty($course->points))
          <a href="{{ !(auth()->check()) ? '/login' : '#' }}" class="{{ (auth()->check()) ? 'js-buy-with-point' : '' }} coursedetail-btn btn btn-outline-warning mt-10 {{ (!$canSale) ? 'disabled' : '' }}" rel="nofollow">
            {!! trans('update.buy_with_n_points',['points' => $course->points]) !!}
          </a>
        @endif
        
        @if($canSale and !empty(getFeaturesSettings('direct_classes_payment_button_status')))
          <button type="button" class="coursedetail-btn js-course-direct-payment">
            {{ trans('update.buy_now') }}
          </button>
        @endif

        @if(!empty($installments) and count($installments) and getInstallmentsSettings('display_installment_button'))
          <a href="/course/{{ $course->slug }}/installments">
            <button class="coursedetail-btn">{{ trans('update.pay_with_installments') }}></button> 
          </a>
        @endif
    @else
      <a href="{{ $canSale ? '/course/'. $course->slug .'/free' : '#' }}" class="{{ (! $canSale) ? (' disabled ' . $course->cantSaleStatus($hasBought)) : '' }}">
        <button class="coursedetail-btn">@if(!$canSale)
          @if($course->checkCapacityReached())
            {{ trans('update.capacity_reached') }}
            @else
              {{ trans('public.disabled') }}
            @endif
        @else
          {{ trans('public.enroll_on_webinar') }}
        @endif
      </button></a>
    @endif

    @if($canSale and $course->subscribe)
      <a href="/subscribes/apply/{{ $course->slug }}" class="@if(!$canSale) disabled @endif">
        <button class="coursedetail-btn">Connect</button></a>
    @endif</div>
  </div>
  </form>

  <p style="color:var(--muted);margin-top:6px">Relax the body and mind with ancient breathing techniques.</p>
</div>

<!-- TABS -->
<div class="coursedetail-tabs">
  <div class="coursedetail-tabbar">
    <div class="coursedetail-tab active" data-tab="overview">Overview</div>
    <div class="coursedetail-tab" data-tab="curriculum">Curriculum</div>
    <div class="coursedetail-tab" data-tab="reviews">Reviews</div>
    <div class="coursedetail-tab" data-tab="community">Community</div>
    <div class="coursedetail-tab" data-tab="resources">Resources</div>
  </div>
</div>

<div class="coursedetail-wrap coursedetail-cols">
  <!-- LEFT CONTENT -->
  <div id="content">
    <!-- Overview -->
    <section id="overview" class="coursedetail-panel">
      <!-- <h3>{{ trans('product.Webinar_description') }}</h3>
      <ul>
        <li>Calm your nervous system in minutes</li>
        <li>Three-stage breath for focus & clarity</li>
        <li>Daily rituals you can keep</li>
      </ul> -->
      <h3>{{ trans('product.Webinar_description') }}</h3>
      <div class="course-description">
        {!! nl2br($course->description) !!}
    </div>
    </section>

    <!-- Curriculum preview -->
      <section id="curriculum" class="coursedetail-panel" style="margin-top:18px;display:none">
      <h3 style="display:flex;align-items:center;gap:10px">
        Curriculum
        <!-- <button id="openCurr2" class="coursedetail-btn-outline">Open drawer</button> -->
      </h3>
      <div style="display:flex;flex-direction:column;gap:10px;margin-top:10px">
        @foreach($course->chapters as $chapter)
          <div class="coursedetail-lesson" style="margin-top:8px"><span>{{ $chapter->title }}</span><button class="coursedetail-btn">Preview</button></div> 
        @endforeach
      </div>
    </section>


    <!-- Reviews -->
    @php
      $reviewCount = $course->reviews->pluck('creator_id')->count();
    @endphp
    <section id="reviews" class="coursedetail-panel" style="margin-top:18px;display:none">
      <h3>Reviews</h3>
      @if($course->reviews->count() > 0)
        @foreach($course->reviews as $review)
            <div class="coursedetail-lesson" style="margin-top:8px">
                {{-- Reviewer name --}}
                <span>
                    {{ $review->creator ? $review->creator->full_name : 'Anonymous' }} — 
                    "{{ $review->description ?? 'No review text provided.' }}"
                </span>

                {{-- Rating stars --}}
                <span style="color:var(--gold); margin-left:10px;">
                    @for ($i = 0; $i < $review->rates; $i++)
                        ★
                    @endfor
                    @for ($i = $review->rates; $i < 5; $i++)
                        ☆
                    @endfor
                </span>
            </div>
        @endforeach
    @else
        <p style="margin-top:10px; color:gray;">No reviews yet.</p>
    @endif
    </section>

    <!-- Community -->
    <section id="community" class="coursedetail-panel" style="margin-top:18px;display:none">
      <h3>Community</h3>
      @php
        $chatData = json_decode($course->cummnity_chat ?? '[]', true);
      @endphp
      @if(!empty($chatData) && count($chatData) > 0)
        @foreach($chatData as $chat)
          <div class="coursedetail-lesson"><span>{{ $chat['userName'] ?? '' }}:  {{ $chat['message'] ?? '' }}</span></div>
        @endforeach
      @else  
        <div class="coursedetail-lesson" style="margin-top:8px"><span>No community messages yet.</span></div>
      @endif 
    </section>

    <!-- Resources -->
    <section id="resources" class="coursedetail-panel" style="margin-top:18px;display:none">
      <h3>Resources</h3>
       @foreach($course->chapters as $chapter)
         @foreach($chapter->chapterItems as $chapterItem)
            @if($chapterItem->type == \App\Models\WebinarChapterItem::$chapterFile and !empty($chapterItem->file) and $chapterItem->file->status == 'active')
            @php
                $file = $chapterItem->file;
                $checkSequenceContent = $file->checkSequenceContent();
                $sequenceContentHasError = (!empty($checkSequenceContent) and (!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error'])));
              @endphp  
            <div class="coursedetail-lesson" style="margin-top:8px"><span>{{ $chapterItem->file->title }}</span>
              
              @if(!empty($checkSequenceContent) and $sequenceContentHasError)
                        <button
                            type="button"
                            class="coursedetail-btn btn-sm btn-gray flex-grow-1 disabled js-sequence-content-error-modal"
                            data-passed-error="{{ !empty($checkSequenceContent['all_passed_items_error']) ? $checkSequenceContent['all_passed_items_error'] : '' }}"
                            data-access-days-error="{{ !empty($checkSequenceContent['access_after_day_error']) ? $checkSequenceContent['access_after_day_error'] : '' }}"
                        >{{ trans('public.play') }}</button>
                    @elseif($file->accessibility == 'paid')
                        @if(!empty($user) and $hasBought)
                            @if($file->downloadable)
                                <a href="{{ $course->getUrl() }}/file/{{ $file->id }}/download">
                                   <button class="coursedetail-btn"> {{ trans('home.download') }}</button>
                                </a>
                            @else
                                <a href="{{ $course->getLearningPageUrl() }}?type=file&item={{ $file->id }}" target="_blank">
                                  <button class="coursedetail-btn">  {{ trans('public.play') }}</button>
                                </a>
                            @endif
                        @else
                            <button type="button" class="coursedetail-btn btn-sm btn-gray disabled {{ ((empty($user)) ? 'not-login-toast' : (!$hasBought ? 'not-access-toast' : '')) }}">
                                @if($file->downloadable)
                                    {{ trans('home.download') }}
                                @else
                                    {{ trans('public.play') }}
                                @endif
                            </button>
                        @endif
                    @else
                        @if($file->downloadable)
                            <a href="{{ $course->getUrl() }}/file/{{ $file->id }}/download">
                               <button class="coursedetail-btn"> {{ trans('home.download') }}</button>
                            </a>
                        @else
                            @if(!empty($user) and $hasBought)
                                <a href="{{ $course->getLearningPageUrl() }}?type=file&item={{ $file->id }}" target="_blank">
                                  <button class="coursedetail-btn">   {{ trans('public.play') }}</button> 
                                </a>
                            @elseif($file->storage == 'upload_archive')
                                <a href="/course/{{ $course->slug }}/file/{{ $file->id }}/showHtml" target="_blank">
                                   <button class="coursedetail-btn">  {{ trans('public.play') }}</button>
                                </a>
                            @elseif(in_array($file->storage, ['iframe', 'google_drive', 'dropbox']))
                                <a href="/course/{{ $course->slug }}/file/{{ $file->id }}/play" target="_blank">
                                  <button class="coursedetail-btn">   {{ trans('public.play') }}</button>
                                </a>
                            @elseif($file->isVideo())
                                <button type="button" data-id="{{ $file->id }}" data-title="{{ $file->title }}">
                                  <button class="coursedetail-btn">   {{ trans('public.play') }}</button>
                                </button>
                            @else
                                <a href="{{ $file->file }}" target="_blank">
                                  <button class="coursedetail-btn">   {{ trans('public.play') }}</button>
                                </a>
                            @endif
                        @endif
                    @endif</div>
            @endif
          @endforeach  
       @endforeach
      
    </section>
  </div>

  <!-- RIGHT ASIDE (could be used for upsells/teacher info) -->
  <aside class="coursedetail-panel">
    <h3>Includes</h3>
    <ul style="line-height:1.6;color:var(--muted)">
      <li>24 video lessons</li>
      <li>Guided practices</li>
      <li>Lifetime access</li>
      <li>Certificate</li>
    </ul>
  </aside>
</div>

<!-- CURRICULUM DRAWER -->
<div class="coursedetail-drawer" id="coursedetail-drawer">
  <header>
    <b>Curriculum</b>
    <button class="coursedetail-btn-outline" id="closeCurr">Close</button>
  </header>
  <div class="coursedetail-list">
    <!-- Generate sample lessons -->
    <div class="coursedetail-lesson"><span>01 · Foundations</span><button class="coursedetail-btn">Preview</button></div>
    <div class="coursedetail-lesson"><span>02 · Posture & Setup</span><button class="coursedetail-btn">Preview</button></div>
    <div class="coursedetail-lesson"><span>03 · 4-7-8 Breath</span><button class="coursedetail-btn">Preview</button></div>
    <div class="coursedetail-lesson"><span>04 · Box Breathing</span><button class="coursedetail-btn">Preview</button></div>
    <div class="coursedetail-lesson"><span>05 · Recovery Breath</span><button class="coursedetail-btn">Preview</button></div>
    <div class="coursedetail-lesson"><span>06 · Daily Ritual</span><button class="coursedetail-btn">Preview</button></div>
    <div class="coursedetail-lesson"><span>07 · Integrations</span><button class="coursedetail-btn">Preview</button></div>
  </div>
</div>

<!-- Teacher tooltip -->
<div class="coursedetail-tooltip" id="coursedetail-tip">
  <div style="display:flex;gap:10px;align-items:center">
    <img src="{{ $course->teacher->avatar }}" style="width:38px;height:38px;border-radius:50%">
    <div>
      <div style="font-weight:900">{{ $course->teacher->full_name }}</div>
      <div style="color:var(--gold)">★★★★★ 4.9 · {{ $course->getSalesCount() }} students</div>
    </div>
  </div>
  <div style="color:#ccc;margin-top:8px"> {{ $course->title }}</div>
  <a href="{{ $course->teacher->getProfileUrl() }}" target="_blank" class="user-name ml-5 font-14"><button class="coursedetail-btn" style="margin-top:10px" >View teacher</button>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Fallback to CDN if local files don't work -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
<script>
  // Cart and payment functionality
  $(document).ready(function() {
    $('body').on('click', '.js-course-add-to-cart-btn', function (e) {
      const $this = $(this);
      $this.addClass('loadingbar primary').prop('disabled', true);

      const $form = $this.closest('form');
      $form.attr('action', '/cart/store');
      $form.trigger('submit');
    });

    $('body').on('click', '.js-course-direct-payment', function (e) {
      const $this = $(this);
      $this.addClass('loadingbar danger').prop('disabled', true);

      const $form = $this.closest('form');
      $form.attr('action', '/course/direct-payment');
      $form.trigger('submit');
    });

    // Tabs - Moved inside document ready
    const tabs = document.querySelectorAll('.coursedetail-tab');
    const sections = ['overview','curriculum','reviews','community','resources'];
    
    if (tabs.length > 0) {
      tabs.forEach(t => {
        t.onclick = () => {
          tabs.forEach(x => x.classList.remove('active'));
          t.classList.add('active');
          sections.forEach(id => {
            const section = document.getElementById(id);
            if (section) {
              section.style.display = (t.dataset.tab === id) ? 'block' : 'none';
            }
          });
          // Scroll to top of content on switch
          const tabsElement = document.querySelector('.coursedetail-tabs');
          if (tabsElement) {
            window.scrollTo({top: tabsElement.offsetTop + 1, behavior: 'smooth'});
          }
        };
      });
    }

    // Drawer - Moved inside document ready with null checks
    const drawer = document.getElementById('coursedetail-drawer');
    const openCurr = document.getElementById('openCurr');
    const openCurr2 = document.getElementById('openCurr2');
    const closeCurr = document.getElementById('closeCurr');

    if (openCurr && drawer) {
      openCurr.onclick = () => drawer.classList.add('open');
    }
    
    if (openCurr2 && drawer) {
      openCurr2.onclick = () => drawer.classList.add('open');
    }
    
    if (closeCurr && drawer) {
      closeCurr.onclick = () => drawer.classList.remove('open');
    }

    // Teacher tooltip - Moved inside document ready with null checks
    const teacher = document.getElementById('coursedetail-teacher');
    const tip = document.getElementById('coursedetail-tip');
    
    // if (teacher && tip) {
    //   teacher.addEventListener('mouseenter', e => {
    //     const r = teacher.getBoundingClientRect();
    //     tip.style.left = r.left + 'px';
    //     tip.style.top = (r.bottom + 8) + 'px';
    //     tip.style.display = 'block';
    //   });
      
    //   teacher.addEventListener('mouseleave', () => tip.style.display = 'none');
    // }

    if (teacher && tip) {
      // Function to show tooltip
      function showTip() {
        const r = teacher.getBoundingClientRect();
        tip.style.left = r.left + 'px';
        tip.style.top = (r.bottom + 8) + 'px';
        tip.style.display = 'block';
      }
      
      // Function to hide tooltip
      function hideTip() {
        tip.style.display = 'none';
      }
      
      // Click on teacher to show tooltip
      teacher.addEventListener('click', (e) => {
        e.stopPropagation(); // Prevent click from bubbling to document
        if (tip.style.display === 'none' || tip.style.display === '') {
          showTip();
        } else {
          hideTip();
        }
      });
      
      // Click outside to hide tooltip
      document.addEventListener('click', (e) => {
        // Check if click is outside both teacher and tip elements
        if (!teacher.contains(e.target) && !tip.contains(e.target)) {
          hideTip();
        }
      });
      
      // Also hide when clicking on the tip itself (optional)
      tip.addEventListener('click', (e) => {
        e.stopPropagation(); // Prevent tip click from triggering document click
        // You can choose whether clicking tip should hide it or not
        // hideTip(); // Uncomment if you want tip click to hide it
      });
    }
  });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const videoElement = document.querySelector('.video-cover');
    const playPauseBtn = document.getElementById('videoPlayPauseBtn');
    
    if (videoElement && playPauseBtn) {
        const playBtn = playPauseBtn.querySelector('.coursedetail-btn');
        
        // Initial state - video paused
        videoElement.pause();
        playBtn.textContent = '▶';
        
        // Toggle play/pause on button click
        playPauseBtn.addEventListener('click', function() {
            if (videoElement.paused) {
                videoElement.play();
                playBtn.textContent = '⏸'; // Pause symbol
                playPauseBtn.classList.add('playing');
            } else {
                videoElement.pause();
                playBtn.textContent = '▶'; // Play symbol
                playPauseBtn.classList.remove('playing');
            }
        });
        
        // Update button when video ends
        videoElement.addEventListener('ended', function() {
            playBtn.textContent = '▶';
            playPauseBtn.classList.remove('playing');
        });
        
        // Update button when video is paused by other means
        videoElement.addEventListener('pause', function() {
            if (playBtn.textContent !== '▶') {
                playBtn.textContent = '▶';
                playPauseBtn.classList.remove('playing');
            }
        });
        
        // Update button when video starts playing
        videoElement.addEventListener('play', function() {
            playBtn.textContent = '⏸';
            playPauseBtn.classList.add('playing');
        });
    }

    const embeddedVideoBtn = document.getElementById('embeddedVideoPlayBtn');
    
    // if (embeddedVideoBtn)
    // {
    //    const iframeElement = document.querySelector('#videoIframe');
    //     if (!iframeElement) return;
        
    //     const playBtn = embeddedVideoBtn.querySelector('.coursedetail-btn');
    //     let isPlaying = false;
    //     let currentTime = 0;
    //     let playStartTime = 0;
    //     const originalSrc = iframeElement.src;
        
    //     embeddedVideoBtn.addEventListener('click', function() {
    //         if (isPlaying) {
    //             // Calculate elapsed time
    //             currentTime = (Date.now() - playStartTime) / 1000;
                
    //             // Pause by reloading with current time but no autoplay
    //             let pausedUrl = originalSrc;
    //             pausedUrl = pausedUrl.replace('autoplay=1', 'autoplay=0');
                
    //             // Add time parameter for resume
    //             if (currentTime > 0) {
    //                 if (pausedUrl.includes('youtube')) {
    //                     pausedUrl = pausedUrl.replace(/[?&]start=\d+/, '');
    //                     pausedUrl += (pausedUrl.includes('?') ? '&' : '?') + 'start=' + Math.floor(currentTime);
    //                 }
    //             }
                
    //             iframeElement.src = pausedUrl;
    //             isPlaying = false;
    //             playBtn.textContent = '▶';
                
    //         } else {
    //             // Play from current time
    //             let playUrl = originalSrc;
    //             playUrl = playUrl.replace('autoplay=0', 'autoplay=1');
                
    //             // Add time parameter if we have a saved position
    //             if (currentTime > 0) {
    //                 if (playUrl.includes('youtube')) {
    //                     playUrl = playUrl.replace(/[?&]start=\d+/, '');
    //                     playUrl += (playUrl.includes('?') ? '&' : '?') + 'start=' + Math.floor(currentTime);
    //                 }
    //             }
                
    //             iframeElement.src = playUrl;
    //             isPlaying = true;
    //             playStartTime = Date.now() - (currentTime * 1000);
    //             playBtn.textContent = '⏸';
    //         }
    //     });
        
    //     // Update button based on URL
    //     iframeElement.addEventListener('load', function() {
    //         isPlaying = this.src.includes('autoplay=1');
    //         playBtn.textContent = isPlaying ? '⏸' : '▶';
            
    //         // Extract time from URL to keep track
    //         const timeMatch = this.src.match(/[?&]start=(\d+)/);
    //         if (timeMatch) {
    //             currentTime = parseInt(timeMatch[1]);
    //             if (isPlaying) {
    //                 playStartTime = Date.now() - (currentTime * 1000);
    //             }
    //         }
    //     });
    // }
});
</script>
<script>
        var webinarDemoLang = '{{ trans('webinars.webinar_demo') }}';
        var replyLang = '{{ trans('panel.reply') }}';
        var closeLang = '{{ trans('public.close') }}';
        var saveLang = '{{ trans('public.save') }}';
        var reportLang = '{{ trans('panel.report') }}';
        var reportSuccessLang = '{{ trans('panel.report_success') }}';
        var reportFailLang = '{{ trans('panel.report_fail') }}';
        var messageToReviewerLang = '{{ trans('public.message_to_reviewer') }}';
        var copyLang = '{{ trans('public.copy') }}';
        var copiedLang = '{{ trans('public.copied') }}';
        var learningToggleLangSuccess = '{{ trans('public.course_learning_change_status_success') }}';
        var learningToggleLangError = '{{ trans('public.course_learning_change_status_error') }}';
        var notLoginToastTitleLang = '{{ trans('public.not_login_toast_lang') }}';
        var notLoginToastMsgLang = '{{ trans('public.not_login_toast_msg_lang') }}';
        var notAccessToastTitleLang = '{{ trans('public.not_access_toast_lang') }}';
        var notAccessToastMsgLang = '{{ trans('public.not_access_toast_msg_lang') }}';
        var canNotTryAgainQuizToastTitleLang = '{{ trans('public.can_not_try_again_quiz_toast_lang') }}';
        var canNotTryAgainQuizToastMsgLang = '{{ trans('public.can_not_try_again_quiz_toast_msg_lang') }}';
        var canNotDownloadCertificateToastTitleLang = '{{ trans('public.can_not_download_certificate_toast_lang') }}';
        var canNotDownloadCertificateToastMsgLang = '{{ trans('public.can_not_download_certificate_toast_msg_lang') }}';
        var sessionFinishedToastTitleLang = '{{ trans('public.session_finished_toast_title_lang') }}';
        var sessionFinishedToastMsgLang = '{{ trans('public.session_finished_toast_msg_lang') }}';
        var sequenceContentErrorModalTitle = '{{ trans('update.sequence_content_error_modal_title') }}';
        var courseHasBoughtStatusToastTitleLang = '{{ trans('cart.fail_purchase') }}';
        var courseHasBoughtStatusToastMsgLang = '{{ trans('site.you_bought_webinar') }}';
        var courseNotCapacityStatusToastTitleLang = '{{ trans('public.request_failed') }}';
        var courseNotCapacityStatusToastMsgLang = '{{ trans('cart.course_not_capacity') }}';
        var courseHasStartedStatusToastTitleLang = '{{ trans('cart.fail_purchase') }}';
        var courseHasStartedStatusToastMsgLang = '{{ trans('update.class_has_started') }}';
        var joinCourseWaitlistLang = '{{ trans('update.join_course_waitlist') }}';
        var joinCourseWaitlistModalHintLang = "{{ trans('update.join_course_waitlist_modal_hint') }}";
        var joinLang = '{{ trans('footer.join') }}';
        var nameLang = '{{ trans('auth.name') }}';
        var emailLang = '{{ trans('auth.email') }}';
        var phoneLang = '{{ trans('public.phone') }}';
        var captchaLang = '{{ trans('site.captcha') }}';
    </script>
    <link rel="stylesheet" href="{{ url('/assets/default/vendors/toast/jquery.toast.min.css') }}">
<script src="{{ url('/assets/default/vendors/toast/jquery.toast.min.js') }}"></script>
<script src="{{ url('/assets/default/js/parts/time-counter-down.min.js') }}"></script>
<script src="{{ url('/assets/default/vendors/barrating/jquery.barrating.min.js') }}"></script>
<script src="{{ url('/assets/default/vendors/video/video.min.js') }}"></script>
<script src="{{ url('/assets/default/vendors/video/youtube.min.js') }}"></script>
<script src="{{ url('/assets/default/vendors/video/vimeo.js') }}"></script>
<script src="{{ url('/assets/default/js/parts/comment.min.js') }}"></script>
<script src="{{ url('/assets/default/js/parts/video_player_helpers.min.js') }}"></script>
<script src="{{ url('/assets/default/js/parts/webinar_show.min.js') }}"></script>
<script src="{{ url('/assets/default/js/parts/blog.min.js') }}"></script>