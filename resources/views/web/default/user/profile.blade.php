@extends('web.default.layouts.newapp')

@section('content')
  <style>
    /* Add this CSS at the top to ensure it loads properly */
    .tab-content {
      display: none;
    }
    
    .tab-content.active {
      display: block;
    }
  </style>

  <div class="profile-wrap">

    <!-- Header -->
    <section class="profile-header">
      <div class="profile-pfp">
        <img src="{{ $user->getAvatar(190) }}" alt="{{ $user["full_name"] }}"/>
      </div>

      <div class="profile-head-info">
        <div class="profile-title">{{ $user["full_name"] }}</div>
        <div class="profile-subtitle">{{ $user["caption"] }}</div>

        <div class="profile-counters">
          <div class="profile-chip"><span class="profile-dot like"></span> <span>Likes</span> <span style="opacity:.8;">45.2K</span></div>
          <div class="profile-chip"><span class="profile-dot rev"></span> <span>Reviews</span> <span style="opacity:.8;">1.2K</span></div>
          <div class="profile-chip"><span class="profile-dot com"></span> <span>Comments</span> <span style="opacity:.8;">8.9K</span></div>
        </div>
      </div>

      <button type="button" id="followToggle" data-user-id="{{ $user['id'] }}" class="profile-follow">
        @if(!empty($authUserIsFollower) and $authUserIsFollower)
            {{ trans('panel.unfollow') }}
        @else
            {{ trans('panel.follow') }}
        @endif
      </button>
    </section>

    <!-- Stories row -->
    <section class="profile-stories" id="stories">
      <!-- Add Story -->
      <div class="profile-story">
        <div class="profile-ring profile-add" id="addStory">
          <div class="profile-inner">＋</div>
        </div>
        Add Story
      </div>

      <!-- Sample stories -->
      <div class="profile-story">
        <div class="profile-ring profile-open" data-src="https://images.unsplash.com/photo-1543294001-f7cd5d7fb516?w=800" data-type="image">
          <div class="profile-inner"><img src="https://images.unsplash.com/photo-1543294001-f7cd5d7fb516?w=400" alt=""></div>
        </div>
        New Drop
      </div>
      <div class="profile-story">
        <div class="profile-ring profile-open" data-src="https://images.unsplash.com/photo-1599643478518-a784e5dc4c49?w=800" data-type="image">
          <div class="profile-inner"><img src="https://images.unsplash.com/photo-1599643478518-a784e5dc4c49?w=400" alt=""></div>
        </div>
        Healing
      </div>
      <div class="profile-story">
        <div class="profile-ring profile-open" data-src="https://images.unsplash.com/photo-1519681393784-d120267933ba?w=1200" data-type="image">
          <div class="profile-inner"><img src="https://images.unsplash.com/photo-1519681393784-d120267933ba?w=400" alt=""></div>
        </div>
        Ceremony
      </div>
    </section>

    <!-- Tabs -->
    <nav class="profile-tabs" id="tabs">
      <a class="profile-tab active" data-tab="reels">Reels</a>
      <a class="profile-tab" data-tab="courses">Courses</a>
      <a class="profile-tab" data-tab="live">Livestreams</a>
      <a class="profile-tab" data-tab="shop">Shop</a>
      <a class="profile-tab" data-tab="articles">Articles</a>
      <a class="profile-tab" data-tab="reviews">Reviews</a>
    </nav>

    <!-- Content grids -->
    <div id="tab-contents">
      <!-- Reels Grid -->
      <div class="tab-content active" id="reels-content">
        <section class="profile-grid">
           @if(!empty($user->reels) and !$user->reels->isEmpty())
             @foreach($user->reels as $reel)
          <a class="profile-card" href="{{ $webinar->getUrl() }}">
              <img src="{{ url($reel->video_path) }}" alt="{{ $reel->title }}">
            <div class="profile-badge"><span class="profile-star">★</span> 4,320</div>
          </a>
          @endforeach
          @else
          @include(getTemplate() . '.includes.no-result',[
              'file_name' => 'webinar.png',
              'title' => trans('site.instructor_not_have_reel'),
              'hint' => '',
          ])
      @endif
        </section>
      </div>

      <!-- Courses Grid -->
      <div class="tab-content" id="courses-content">
        <section class="profile-grid">
          @if(!empty($webinars) and !$webinars->isEmpty())
          @foreach($webinars as $webinar)
          <a class="profile-card" href="{{ $webinar->getUrl() }}">
              <img src="{{ $webinar->getImage() }}" alt="{{ $webinar->title }}">
            <div class="profile-badge"><span class="profile-star">★</span> 4,320</div>
          </a>
          @endforeach
          @else
          @include(getTemplate() . '.includes.no-result',[
              'file_name' => 'webinar.png',
              'title' => trans('site.instructor_not_have_webinar'),
              'hint' => '',
          ])
      @endif
        </section>
      </div>

      <!-- Livestreams Grid -->
      <div class="tab-content" id="live-content">
        <section class="profile-grid">
          <a class="profile-card" href="#">
            <img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=1200" alt="">
            <div class="profile-badge"><span class="profile-star">★</span> 7,890</div>
          </a>
          <a class="profile-card" href="#">
            <img src="https://images.unsplash.com/photo-1543294001-f7cd5d7fb516?w=1200" alt="">
            <div class="profile-badge"><span class="profile-star">★</span> 6,540</div>
          </a>
          <a class="profile-card" href="#">
            <img src="https://images.unsplash.com/photo-1599643478518-a784e5dc4c49?w=1200" alt="">
            <div class="profile-badge"><span class="profile-star">★</span> 5,320</div>
          </a>
          <a class="profile-card" href="#">
            <img src="https://images.unsplash.com/photo-1535905496755-26ae35d0ae54?w=1200" alt="">
            <div class="profile-badge"><span class="profile-star">★</span> 8,120</div>
          </a>
          <a class="profile-card" href="#">
            <img src="https://images.unsplash.com/photo-1603575449299-3d82f74a0f9d?w=1200" alt="">
            <div class="profile-badge"><span class="profile-star">★</span> 4,980</div>
          </a>
          <a class="profile-card" href="#">
            <img src="https://images.unsplash.com/photo-1520975954732-35dd222996f8?w=1200" alt="">
            <div class="profile-badge"><span class="profile-star">★</span> 6,750</div>
          </a>
        </section>
      </div>

      <!-- Shop Grid -->
      <div class="tab-content" id="shop-content">
        <section class="profile-grid">
          @if(!empty($user->products) and !$user->products->isEmpty())
             @foreach($user->products as $product)
          <a class="profile-card" href="{{ $product->getUrl() }}">
             <img src="{{ $product->thumbnail }}" class="img-cover" alt="{{ $product->title }}">
            <div class="profile-badge"><span class="profile-star">★</span> 4,320</div>
          </a>
          @endforeach
          @else
              @include(getTemplate() . '.includes.no-result',[
                  'file_name' => 'webinar.png',
                  'title' => trans('update.instructor_not_have_products'),
                  'hint' => '',
              ])
          @endif
        </section>
      </div>

      <!-- Articles Grid -->
      <div class="tab-content" id="articles-content">
        <section class="profile-grid">
          @if(!empty($user->blog) and !$user->blog->isEmpty())
           @foreach($user->blog as $post)
          <a class="profile-card" href="{{ $post->getUrl() }}">
              <img src="{{ $post->image }}" class="img-cover" alt="{{ $post->title }}">
            <div class="profile-badge"><span class="profile-star">★</span> 4,320</div>
          </a>
          @endforeach
          @else
              @include(getTemplate() . '.includes.no-result',[
                  'file_name' => 'webinar.png',
                  'title' => trans('update.instructor_not_have_posts'),
                  'hint' => '',
              ])
          @endif
        </section>
      </div>

      <!-- Reviews Grid -->
      <div class="tab-content" id="reviews-content">
        <section class="profile-grid">
          <a class="profile-card" href="#">
            <img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=1200" alt="">
            <div class="profile-badge"><span class="profile-star">★</span> 4,560</div>
          </a>
          <a class="profile-card" href="#">
            <img src="https://images.unsplash.com/photo-1519681393784-d120267933ba?w=1200" alt="">
            <div class="profile-badge"><span class="profile-star">★</span> 3,890</div>
          </a>
          <a class="profile-card" href="#">
            <img src="https://images.unsplash.com/photo-1517433456452-f9633a875f6f?w=1200" alt="">
            <div class="profile-badge"><span class="profile-star">★</span> 5,210</div>
          </a>
          <a class="profile-card" href="#">
            <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=1200" alt="">
            <div class="profile-badge"><span class="profile-star">★</span> 4,780</div>
          </a>
          <a class="profile-card" href="#">
            <img src="https://images.unsplash.com/photo-1596495578065-8fbecb91a9d1?w=1200" alt="">
            <div class="profile-badge"><span class="profile-star">★</span> 3,450</div>
          </a>
          <a class="profile-card" href="#">
            <img src="https://images.unsplash.com/photo-1603575449299-3d82f74a0f9d?w=1200" alt="">
            <div class="profile-badge"><span class="profile-star">★</span> 6,120</div>
          </a>
        </section>
      </div>
    </div>
  </div>

  <!-- Hidden file input for Add Story -->
  <input type="file" id="storyInput" accept="image/*,video/*" hidden>

  <!-- Story Viewer Modal -->
  <div class="profile-modal" id="modal">
    <div class="profile-viewer">
      <div class="profile-progress"><div class="profile-bar" id="bar"></div></div>
      <div class="profile-media" id="media"></div>
      <button class="profile-close" id="close" title="Close">✕</button>
    </div>
  </div>
@endsection

@push('scripts_bottom')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - initializing tabs');
    
    // Tab functionality
    const tabs = document.querySelectorAll('.profile-tab');
    
    // Function to show only active tab content
    function showActiveTabContent() {
      console.log('Showing active tab content');
      
      // Hide all tab contents
      document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
        console.log('Hiding:', content.id);
      });
      
      // Show only the active tab content
      const activeTab = document.querySelector('.profile-tab.active');
      if (activeTab) {
        const tabId = activeTab.getAttribute('data-tab');
        const activeContent = document.getElementById(`${tabId}-content`);
        if (activeContent) {
          activeContent.classList.add('active');
          console.log('Showing:', activeContent.id);
        }
      }
    }
    
    // Initialize - show only active tab content on page load
    showActiveTabContent();
    
    tabs.forEach(tab => {
      tab.addEventListener('click', () => {
        console.log('Tab clicked:', tab.getAttribute('data-tab'));
        
        // Remove active class from all tabs
        tabs.forEach(t => t.classList.remove('active'));
        
        // Add active class to clicked tab
        tab.classList.add('active');
        
        // Show only the active tab content
        showActiveTabContent();
      });
    });
    
    // Follow button toggle
    
  });
</script>

<script>
  var unFollowLang = '{{ trans('panel.unfollow') }}';
  var followLang = '{{ trans('panel.follow') }}';
  var reservedLang = '{{ trans('meeting.reserved') }}';
  var availableDays = {{ json_encode($times) }};
  var messageSuccessSentLang = '{{ trans('site.message_success_sent') }}';
</script>

<script src="/assets/default/vendors/persian-datepicker/persian-date.js"></script>
<script src="/assets/default/vendors/persian-datepicker/persian-datepicker.js"></script>
<script src="/assets/default/js/parts/profile.min.js"></script>

@if(!empty($user->live_chat_js_code) and !empty(getFeaturesSettings('show_live_chat_widget')))
  <script>
    (function () {
      "use strict"
      {!! $user->live_chat_js_code !!}
    })(jQuery)
  </script>
@endif
@endpush