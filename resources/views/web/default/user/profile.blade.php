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
        <div class="profile-subtitle">{{ $user["role"]["caption"] }}</div>

        <div class="profile-counters">
          <div class="profile-chip"><span class="profile-dot like"></span> <span>Likes</span> <span style="opacity:.8;">{{ $totalLikes ?? 0 }}</span></div>
          <div class="profile-chip"><span class="profile-dot rev"></span> <span>Reviews</span> <span style="opacity:.8;">{{ $totalReviews ?? 0 }}</span></div>
          <div class="profile-chip"><span class="profile-dot com"></span> <span>Comments</span> <span style="opacity:.8;">{{ $totalComments ?? 0 }}</span></div>
        </div>
      </div>

       @if(auth()->check() && auth()->id() != $user->id)
      <button type="button" id="followToggle" data-user-id="{{ $user['id'] }}" class="profile-follow">
        @if(!empty($authUserIsFollower) and $authUserIsFollower)
            Unconnect
        @else
            Connect
        @endif
        <!-- @if(!empty($authUserIsFollower) and $authUserIsFollower)
            {{ trans('panel.unfollow') }}
        @else
            {{ trans('panel.follow') }}
        @endif -->
      </button>
       @endif
    </section>

    <!-- Stories row -->
    <section class="profile-stories" id="stories">
       @if(auth()->check() && auth()->id() == $user->id)
      <div class="profile-story" id="addStoryBtn">
        <div class="profile-ring profile-add" id="addStory">
          <div class="profile-inner">＋</div>
        </div>
        Add Story
      </div>
       @endif

      <!-- Sample stories -->
      @foreach($userStories as $story)
      <div class="profile-story story-item" 
           data-story-id="{{ $story->id }}"
           data-media-type="{{ $story->media_type }}"
           data-media-url="{{ $story->media_url }}"
           data-title="{{ $story->title }}"
           data-created-at="{{ is_int($story->created_at) ? $story->created_at : strtotime($story->created_at) }}">
        <div class="profile-ring {{ $story->viewed_by_current_user ? 'viewed' : 'not-viewed' }}">
          <div class="profile-inner">
            @if($story->media_type == 'image')
              <img src="{{ $story->thumbnail_url ?: $story->media_url }}" alt="{{ $story->title }}">
            @else
              <img src="{{ $story->thumbnail_url }}" alt="{{ $story->title }}">
              <div style="position: absolute; bottom: 5px; right: 5px; background: rgba(0,0,0,0.7); color: white; padding: 2px 5px; border-radius: 3px; font-size: 10px;">
                ▶
              </div>
            @endif
          </div>
        </div>
        <div class="profile-story-text">{{ $story->title }}</div>
      </div>
      @endforeach
    </section>

    <!-- Tabs -->
    <nav class="profile-tabs" id="tabs">
      <a class="profile-tab active" data-tab="reels">Reels</a>
      <a class="profile-tab" data-tab="courses">Courses</a>
      <!-- <a class="profile-tab" data-tab="live">Livestreams</a> -->
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
          <a class="profile-card" href="#">
             <video class="reel-video" controls preload="metadata" poster="{{ url($reel->thumbnail_url) }}">
                                    <source src="{{ url($reel->video_url) }}" type="video/mp4">
                                    {{ $reel->title }}
                                </video>
              <!-- <img src="{{ url($reel->video_path) }}" alt="{{ $reel->title }}"> -->
            <div class="profile-badge"><span class="profile-star">★</span> {{$reel->like_count ?? 0}}</div>
          </a>
          @endforeach
          @else
            @if($user->role->caption === 'Wisdom Keeper' || $user->role->caption === 'wisdom_keeper')
              @include(getTemplate() . '.includes.no-result',[
                  'file_name' => 'webinar.png',
                  'title' => trans('site.instructor_not_have_reel'),
                  'hint' => '',
              ])
            @else
              @include(getTemplate() . '.includes.no-result',[
                  'file_name' => 'webinar.png',
                  'title' => 'This Seeker hasn\'t shared any reels yet',
                  'hint' => '',
              ])
            @endif
      @endif
        </section>
      </div>

      <!-- Courses Grid -->
      <div class="tab-content" id="courses-content">
        <section class="profile-grid">
          @if($isWisdomKeeper)
            @if(!empty($webinars) and !$webinars->isEmpty())
              @foreach($webinars as $webinar)
              <a class="profile-card" href="{{ $webinar->getUrl() }}">
                  <img src="{{ $webinar->getImage() }}" alt="{{ $webinar->title }}">
                <div class="profile-badge"><span class="profile-star">★</span> {{ $webinar->like_count ?? 0 }}</div>
              </a>
              @endforeach
            @else
              @include(getTemplate() . '.includes.no-result',[
                  'file_name' => 'webinar.png',
                  'title' => trans('site.instructor_not_have_webinar'),
                  'hint' => '',
              ])
            @endif
          @else
             @if($seekerLikedWebinars->count())
              @foreach($seekerLikedWebinars as $webinar)
                <a class="profile-card" href="{{ $webinar->getUrl() }}">
                  <img src="{{ $webinar->getImage() }}" alt="{{ $webinar->title }}">
                  <div class="profile-badge"><span class="profile-star">★</span> {{ $webinar->like_count ?? 0 }}</div>
                </a>
              @endforeach
            @else
              @include(getTemplate() . '.includes.no-result', [
                'file_name' => 'webinar.png',
                'title' => "This Seeker hasn't liked any courses yet",
                'hint' => '',
              ])
            @endif
          @endif
        </section>
      </div>

      <!-- Livestreams Grid -->
      <!-- <div class="tab-content" id="live-content">
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
      </div> -->

      <!-- Shop Grid -->
      <div class="tab-content" id="shop-content">
        <section class="profile-grid">
          @if($isWisdomKeeper)
            @if(!empty($user->products) and !$user->products->isEmpty())
              @foreach($user->products as $product)
                <a class="profile-card" href="{{ $product->getUrl() }}">
                  <img src="{{ $product->thumbnail }}" class="img-cover" alt="{{ $product->title }}">
                  <div class="profile-badge"><span class="profile-star">★</span> {{ $product->like_count ?? 0 }}</div>
                </a>
              @endforeach
            @else
              @include(getTemplate() . '.includes.no-result',[
                  'file_name' => 'webinar.png',
                  'title' => trans('update.instructor_not_have_products'),
                  'hint' => '',
              ])
            @endif
          @else
           @if($seekerLikedProducts->count())
              @foreach($seekerLikedProducts as $product)
                <a class="profile-card" href="{{ $product->getUrl() }}">
                  <img src="{{ $product->thumbnail }}" class="img-cover" alt="{{ $product->title }}">
                  <div class="profile-badge"><span class="profile-star">★</span> {{ $product->like_count ?? 0 }}</div>
                </a>
              @endforeach
            @else
              @include(getTemplate() . '.includes.no-result', [
                'file_name' => 'webinar.png',
                'title' => "This Seeker hasn't liked any products yet",
                'hint' => '',
              ])
            @endif
          @endif
        </section>
      </div>

      <!-- Articles Grid -->
      <div class="tab-content" id="articles-content">
        <section class="profile-grid">
          @if($isWisdomKeeper)
            @if(!empty($user->blog) and !$user->blog->isEmpty())
              @foreach($user->blog as $post)
              <a class="profile-card" href="{{ $post->getUrl() }}">
                  <img src="{{ $post->image }}" class="img-cover" alt="{{ $post->title }}">
                <div class="profile-badge"><span class="profile-star">★</span> {{ $post->like_count ?? 0 }}</div>
              </a>
              @endforeach
            @else
                @include(getTemplate() . '.includes.no-result',[
                    'file_name' => 'webinar.png',
                    'title' => trans('update.instructor_not_have_posts'),
                    'hint' => '',
                ])
            @endif
          @else
            @if($seekerLikedArticles->count())
              @foreach($seekerLikedArticles as $post)
                <a class="profile-card" href="{{ $post->getUrl() }}">
                  <img src="{{ $post->image }}" class="img-cover" alt="{{ $post->title }}">
                  <div class="profile-badge"><span class="profile-star">★</span> {{ $post->like_count ?? 0 }}</div>
                </a>
              @endforeach
            @else
              @include(getTemplate() . '.includes.no-result', [
                'file_name' => 'webinar.png',
                'title' => "This Seeker hasn't liked any articles yet",
                'hint' => '',
              ])
            @endif
          @endif
        </section>
      </div>

      <!-- Reviews Grid -->
      <div class="tab-content" id="reviews-content">
        <section class="profile-grid">
          @if($isWisdomKeeper)
            @php
              $allReceivedReviews = collect();
              foreach(['webinars','products','articles','reels'] as $type) {
                if(!empty($wisdomKeeperReceivedReviews[$type])) {
                  $allReceivedReviews = $allReceivedReviews->merge($wisdomKeeperReceivedReviews[$type]);
                }
              }
            @endphp

            @if($allReceivedReviews->count())
              @foreach($allReceivedReviews as $review)
                <div class="profile-card review-card" style="padding: 16px; display:flex; flex-direction:column; gap:8px;">

                  {{-- Reviewer info --}}
                  <div style="display:flex; align-items:center; gap:8px;">
                    <img src="{{ $review->reviewer_avatar ?? '/assets/default/img/default/avatar-1.png' }}" 
                        alt="{{ $review->reviewer_name }}"
                        style="width:36px; height:36px; border-radius:50%; object-fit:cover;">
                    <span style="font-weight:600; font-size:14px;">{{ $review->reviewer_name }}</span>
                  </div>

                  {{-- Star Rating --}}
                  <div style="color:#f5a623; font-size:14px;">
                    @for($i = 1; $i <= 5; $i++)
                      {{ $i <= ($review->rates ?? $review->rating ?? 0) ? '★' : '☆' }}
                    @endfor
                  </div>

                  {{-- Review text --}}
                  <p style="font-size:13px; color:#555; margin:0;">
                    {{ $review->review ?? $review->description ?? '' }}
                  </p>

                  {{-- Content it's about --}}
                  <small style="color:#999;">
                    On: <em>{{ $review->content_title }}</em>
                  </small>

                </div>
              @endforeach
            @else
              @include(getTemplate() . '.includes.no-result', [
                'file_name' => 'webinar.png',
                'title' => 'No reviews received yet',
                'hint' => '',
              ])
            @endif
          @else
            @php $hasReviews = false; @endphp

            @foreach(['webinars','products','articles','reels'] as $type)
              @if(!empty($seekerReviews[$type]) && $seekerReviews[$type]->count())
                @php $hasReviews = true; @endphp
                @foreach($seekerReviews[$type] as $item)

                  @if($type === 'webinars')
                  <a class="profile-card" href="/course/{{ $item->slug }}">
                    <img src="{{ $item->thumbnail ?? '' }}" class="img-cover" alt="{{ $item->title ?? '' }}">
                     <div class="profile-badge">
                      <span class="profile-star">★</span> {{ $item->rating ?? $item->rates ?? 0 }}
                    </div>
                  </a>

                  @elseif($type === 'products')
                  <a class="profile-card" href="/products/{{ $item->slug }}">
                    <img src="{{ $item->thumbnail ?? '' }}" class="img-cover" alt="{{ $item->title ?? '' }}">
                    <div class="profile-badge">
                      <span class="profile-star">★</span> {{ $item->rating ?? $item->rates ?? 0 }}
                    </div>
                  </a>

                  @elseif($type === 'articles')
                  <a class="profile-card" href="/blog/{{ $item->slug }}">
                    <img src="{{ $item->image ?? '' }}" class="img-cover" alt="{{ $item->title ?? '' }}">
                    <div class="profile-badge">
                      <span class="profile-star">★</span> {{ $item->rating ?? $item->rates ?? 0 }}
                    </div>
                  </a>

                  @elseif($type === 'reels')
                  <a class="profile-card" href="#">
                    <video class="reel-video" controls preload="metadata" poster="{{ url($item->thumbnail_url ?? '') }}">
                      <source src="{{ url('/store/reels/videos/'.$item->video_path ?? '') }}" type="video/mp4">
                      {{ $item->title }}
                    </video>
                     <div class="profile-badge">
                      <span class="profile-star">★</span> {{ $item->rating ?? $item->rates ?? 0 }}
                    </div>
                  </a>
                  @endif
                @endforeach
              @endif
            @endforeach

            @if(!$hasReviews)
              @include(getTemplate() . '.includes.no-result', [
                'file_name' => 'webinar.png',
                'title' => "This Seeker hasn't submitted any reviews yet",
                'hint' => '',
              ])
            @endif
          @endif
        </section>
      </div>
    </div>
  </div>

   <div class="story-upload-modal" id="storyUploadModal">
    <div class="story-upload-content">
      <button class="story-upload-close" id="closeUploadModal">×</button>
      <h2 style="margin-bottom: 20px; color: #333;">Add New Story</h2>
      
      <div class="story-choose-btn" id="storyChooseBtn">
        <div class="story-choose-text">Choose Photo or Video</div>
        <div class="story-choose-subtext">JPG, PNG, MP4 up to 50MB</div>
      </div>
      
      <input type="file" id="storyFileInput" accept="image/*,video/*" hidden>
      
      <img class="story-preview" id="imagePreview" alt="Image preview">
      <video class="story-preview" id="videoPreview" controls style="display: none;"></video>
      
      <form class="story-upload-form" id="storyUploadForm">
        @csrf
        <input type="text" 
               class="story-input" 
               name="title" 
               placeholder="Story Title (Optional)" 
               maxlength="100">
               <input type="hidden" name="link">
<!--         
        <input type="text" 
               class="story-input" 
               name="link" 
               placeholder="Add a link (Optional)"> -->
        
        <div class="upload-progress" id="uploadProgress">
          <div class="upload-progress-bar" id="uploadProgressBar"></div>
        </div>
        
        <div class="error-message" id="errorMessage"></div>
        
        <button type="submit" class="story-upload-btn" id="uploadBtn" disabled>
          Upload Story
        </button>
      </form>
    </div>
  </div>

  <!-- Hidden file input for Add Story -->
  <!-- <input type="file" id="storyInput" accept="image/*,video/*" hidden> -->

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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - initializing tabs');

    let currentStories = [];
    let currentStoryIndex = 0;
    let currentFile = null;
    let storyTimeout;
    let currentFileData = null;
    let isUploading = false;
    let currentFileName = '';
    let currentFileType = '';
    const storyDuration = 30000;
    
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
    
     const addStoryBtn = document.getElementById('addStoryBtn');
    const storyUploadModal = document.getElementById('storyUploadModal');
    const closeUploadModal = document.getElementById('closeUploadModal');
    const storyChooseBtn = document.getElementById('storyChooseBtn');
    const storyFileInput = document.getElementById('storyFileInput');
    const imagePreview = document.getElementById('imagePreview');
    const videoPreview = document.getElementById('videoPreview');
    const storyUploadForm = document.getElementById('storyUploadForm');
    const uploadBtn = document.getElementById('uploadBtn');
    const uploadProgress = document.getElementById('uploadProgress');
    const uploadProgressBar = document.getElementById('uploadProgressBar');
    const errorMessage = document.getElementById('errorMessage');
    
    // Open upload modal
    if (addStoryBtn) {
      addStoryBtn.addEventListener('click', () => {
        storyUploadModal.classList.add('active');
      });
    }
    
    // Close upload modal
    closeUploadModal.addEventListener('click', () => {
      storyUploadModal.classList.remove('active');
      resetUploadForm();
    });

    storyUploadModal.addEventListener('click', (e) => {
      if (e.target === storyUploadModal) {
        storyUploadModal.classList.remove('active');
        resetUploadForm();
      }
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && storyUploadModal.classList.contains('active')) {
        storyUploadModal.classList.remove('active');
        resetUploadForm();
      }
    });
    
    // Click on choose button
    storyChooseBtn.addEventListener('click', () => {
      document.getElementById('storyFileInput').click();
    });
    
    // Handle file selection
    storyFileInput.addEventListener('change', handleFileChange);

    // storyFileInput.addEventListener('change', function(e) {
      
    // });

    function handleFileChange(e) {
      const file = e.target.files[0];
      if (!file) {
        // If no file selected (user canceled), reset everything
        resetUploadForm();
        return;
      }

      currentFileData = file;
      currentFileName = file.name;
      currentFileType = file.type;
      
      // Validate file type
      const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'video/mp4', 'video/quicktime'];
      if (!validTypes.includes(file.type)) {
          showError('Please select a valid image or video file (JPG, PNG, GIF, MP4)');
          storyFileInput.value = ''; // Clear the invalid selection
          resetUploadForm();
          return;
      }
      
      // Validate file size (50MB max)
      const maxSize = 50 * 1024 * 1024; // 50MB in bytes
      if (file.size > maxSize) {
        showError('File size should be less than 50MB');
        storyFileInput.value = ''; // Clear the invalid selection
        resetUploadForm();
        return;
      }
      
      // Reset error
      hideError();
      
      // Preview file
      if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
          imagePreview.src = e.target.result;
          imagePreview.classList.add('active');
          videoPreview.classList.remove('active');
          videoPreview.pause();
        };
        reader.readAsDataURL(file);
      } else if (file.type.startsWith('video/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
          videoPreview.src = e.target.result;
          videoPreview.classList.add('active');
          imagePreview.classList.remove('active');
          imagePreview.src = '';
        };
        reader.readAsDataURL(file);
      }
      
      // Enable upload button
      uploadBtn.disabled = false;
    }
    
    // Handle form submission
    storyUploadForm.addEventListener('submit', async function(e) {
      e.preventDefault();
      
      if (isUploading) return; 
      if (!currentFileData) {
        showError('Please select a file first');
        return;
      }

      isUploading = true;
      
      const formData = new FormData();
      formData.append('story', currentFileData, currentFileName);
      formData.append('title', this.title.value);
      formData.append('link', this.link.value);
      formData.append('_token', '{{ csrf_token() }}');
      
      // Show progress bar
      uploadProgress.classList.add('active');
      uploadBtn.disabled = true;
      uploadBtn.textContent = 'Uploading...';
      
      try {
        const userId = {{ $user->id }}; // Get the current user ID
        const response = await fetch(`/users/${userId}/story/upload`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
          // Upload successful
          showSuccess('Story uploaded successfully!');
          resetUploadForm();
          storyUploadModal.classList.remove('active');
          
          // Reload stories section
          loadUserStories();
        } else {
          showError(result.message || 'Upload failed. Please try again.');
        }
      } catch (error) {
        //console.error('Upload error:', error);
        showError('Upload error:', error.message || 'An error occurred during upload.');
      } finally {
        isUploading = false;
        uploadProgress.classList.remove('active');
        uploadProgressBar.style.width = '0%';
        uploadBtn.disabled = false;
        uploadBtn.textContent = 'Upload Story';
      }
    });
    
    // Handle progress for file upload (if needed for larger files)
    function updateProgressBar(percent) {
      uploadProgressBar.style.width = percent + '%';
    }
    
    function showError(message) {
      errorMessage.textContent = message;
      errorMessage.classList.add('active');
    }
    
    function hideError() {
      errorMessage.classList.remove('active');
    }
    
    function showSuccess(message) {
      (function() {
        
          $.toast({
              heading: 'Success',
              text: message,
              bgColor: '#43d477',
              textColor: 'white',
              hideAfter: 10000,
              position: 'bottom-right',
              icon: 'success'
          });
      })();
      // You can implement a success toast notification here
      //alert(message); // Temporary success message
    }
    
    function resetUploadForm() {
      const oldInput = document.getElementById('storyFileInput');
      const newInput = oldInput.cloneNode(true);
      oldInput.parentNode.replaceChild(newInput, oldInput);
      
      // Re-attach the change event to the new input
      newInput.addEventListener('change', handleFileChange);
      
      // Re-attach click to choose button
      storyChooseBtn.onclick = () => newInput.click();

      storyFileInput.value = '';
      imagePreview.src = '';
      imagePreview.classList.remove('active');
      videoPreview.src = '';
      videoPreview.classList.remove('active');
      videoPreview.pause();

      storyUploadForm.reset();
      uploadBtn.disabled = true;
      hideError();

      uploadProgress.classList.remove('active');
      uploadProgressBar.style.width = '0%';
      
      currentFileData = null;
      currentFileName = '';
      currentFileType = '';
      isUploading = false;
    }
    
    // Story Viewer Functionality
    const storyViewer = document.getElementById('storyViewer');
    const closeStoryViewer = document.getElementById('closeStoryViewer');
    const storyMediaImage = document.getElementById('storyMediaImage');
    const storyMediaVideo = document.getElementById('storyMediaVideo');
    const storyViewerTitle = document.getElementById('storyViewerTitle');
    const progressContainer = document.getElementById('progressContainer');
    const prevStoryBtn = document.getElementById('prevStory');
    const nextStoryBtn = document.getElementById('nextStory');
    
    // Load user stories
    async function loadUserStories() {
      try {
        const response = await fetch(`{{ route("profile.stories", $user->id) }}`);
        const result = await response.json();
        
        if (result.success) {
          // Update stories section
          // You can implement AJAX updating of stories here
          location.reload(); // Simple reload for now
        }
      } catch (error) {
        console.error('Error loading stories:', error);
      }
    }
    
    // Open story viewer when clicking on a story
    document.querySelectorAll('.story-item').forEach(item => {
      item.addEventListener('click', function() {
        const storyId = this.getAttribute('data-story-id');
        const mediaType = this.getAttribute('data-media-type');
        const mediaUrl = this.getAttribute('data-media-url');
        const title = this.getAttribute('data-title');
        
        // Mark as viewed
        markStoryAsViewed(storyId);

        openProfileModal(mediaType, mediaUrl);

        // const modal = document.createElement('div');
        // modal.style.cssText = `
        //   position: fixed;
        //   top: 0;
        //   left: 0;
        //   width: 100%;
        //   height: 100%;
        //   background: rgba(0,0,0,0.9);
        //   z-index: 99999;
        //   display: flex;
        //   justify-content: center;
        //   align-items: center;
        // `;
        
        // modal.innerHTML = `
        //   <div style="position: relative; max-width: 90%; max-height: 90vh;">
        //     ${mediaType === 'image' 
        //       ? `<img src="${mediaUrl}" style="max-width: 100%; max-height: 90vh;">`
        //       : `<video src="${mediaUrl}" controls autoplay style="max-width: 100%; max-height: 90vh;"></video>`
        //     }
        //     <button style="position: absolute; top: -40px; right: 0; background: none; border: none; color: white; font-size: 30px; cursor: pointer;">×</button>
        //   </div>
        // `;
        
        // document.body.appendChild(modal);
        
        // // Close modal on click
        // modal.querySelector('button').addEventListener('click', () => {
        //   document.body.removeChild(modal);
        // });
        
        // // Close on background click
        // modal.addEventListener('click', (e) => {
        //   if (e.target === modal) {
        //     document.body.removeChild(modal);
        //   }
        // });
        
        // // Close on Escape key
        // document.addEventListener('keydown', function closeOnEscape(e) {
        //   if (e.key === 'Escape') {
        //     document.body.removeChild(modal);
        //     document.removeEventListener('keydown', closeOnEscape);
        //   }
        // });
        
        // Load all stories for this user
        // loadAllStories().then(stories => {
        //   if (stories.length > 0) {
        //     currentStories = stories;
        //     currentStoryIndex = stories.findIndex(s => s.id == storyId);
        //     openStoryViewer();
        //   }
        // });
      });
    });
    
    async function loadAllStories() {
      try {
        const userId = {{ $user->id }};
        const response = await fetch(`/users/${userId}/stories/all`);
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        const result = await response.json();
        
        if (result.success) {
            return result.stories;
        } else {
            console.error('API Error:', result.message);
            return [];
        }
      } catch (error) {
        console.error('Error loading all stories:', error);
        return [];
      }
    }
    
    async function markStoryAsViewed(storyId) {
      try {
          const userId = {{ $user->id }};
          
          // FIXED: Use correct URL and headers
          const response = await fetch(`/users/${userId}/story/${storyId}/view`, {
              method: 'POST',
              headers: {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}',
                  'Content-Type': 'application/json',
                  'Accept': 'application/json'
              }
          });
          
          if (!response.ok) {
              throw new Error(`HTTP error! status: ${response.status}`);
          }
          
          const result = await response.json();
          
          if (!result.success) {
              console.error('Failed to mark story as viewed:', result.message);
          }
          
          return result.success;
      } catch (error) {
          console.error('Error marking story as viewed:', error);
          return false;
      }
  }

  function openProfileModal(mediaType, mediaUrl) {
    const modal = document.getElementById("modal");
    const media = document.getElementById("media");
    const closeBtn = document.getElementById("close");

    // Insert image or video
    if (mediaType === "image") {
        media.innerHTML = `<img src="${mediaUrl}" style="width:100%; max-height:90vh; object-fit: contain;" />`;
    } else {
        media.innerHTML = `<video src="${mediaUrl}" controls autoplay style="width:100%; max-height:90vh;object-fit: contain;"></video>`;
    }

    // Show modal
    modal.classList.add("open");

    // Close button
    closeBtn.onclick = () => {
        modal.classList.remove("open");
        media.innerHTML = ""; // clear media
    };

    // Close when clicking outside viewer
    modal.onclick = e => {
        if (e.target === modal) {
            modal.classList.remove("open");
            media.innerHTML = "";
        }
    };

    // ESC key close
    document.addEventListener("keydown", function escClose(e) {
        if (e.key === "Escape") {
            modal.classList.remove("open");
            media.innerHTML = "";
            document.removeEventListener("keydown", escClose);
        }
    });
}
    
  });
</script>

<script>
  var unFollowLang = 'Unconnect';
  var followLang = 'Connect';
  // var unFollowLang = '{{ trans('panel.unfollow') }}';
  // var followLang = '{{ trans('panel.follow') }}';
  var reservedLang = '{{ trans('meeting.reserved') }}';
  var availableDays = {{ json_encode($times) }};
  var messageSuccessSentLang = '{{ trans('site.message_success_sent') }}';
</script>

<script src="/assets/default/vendors/persian-datepicker/persian-date.js"></script>
<script src="/assets/default/vendors/persian-datepicker/persian-datepicker.js"></script>


@if(!empty($user->live_chat_js_code) and !empty(getFeaturesSettings('show_live_chat_widget')))
  <script>
    (function () {
      "use strict"
      {!! $user->live_chat_js_code !!}
    })(jQuery)
  </script>
@endif
@endpush