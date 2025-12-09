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
          <a class="profile-card" href="#">
             <video class="reel-video" controls preload="metadata" poster="{{ url($reel->thumbnail_url) }}">
                                    <source src="{{ url($reel->video_path) }}" type="video/mp4">
                                    {{ $reel->title }}
                                </video>
              <!-- <img src="{{ url($reel->video_path) }}" alt="{{ $reel->title }}"> -->
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
        
        <input type="text" 
               class="story-input" 
               name="link" 
               placeholder="Add a link (Optional)">
        
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
<script>
  document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - initializing tabs');

    let currentStories = [];
    let currentStoryIndex = 0;
    let currentFile = null;
    let storyTimeout;
    let currentFileData = null;
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
    
    // Click on choose button
    storyChooseBtn.addEventListener('click', () => {
      storyFileInput.click();
    });
    
    // Handle file selection
    storyFileInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (!file) return;

      currentFileData = file;
      currentFileName = file.name;
      currentFileType = file.type;
      
      // Validate file type
      const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'video/mp4', 'video/quicktime'];
      if (!validTypes.includes(file.type)) {
          showError('Please select a valid image or video file (JPG, PNG, GIF, MP4)');
          return;
      }
      
      // Validate file size (50MB max)
      const maxSize = 50 * 1024 * 1024; // 50MB in bytes
      if (file.size > maxSize) {
        showError('File size should be less than 50MB');
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
        };
        reader.readAsDataURL(file);
      } else if (file.type.startsWith('video/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
          videoPreview.src = e.target.result;
          videoPreview.classList.add('active');
          imagePreview.classList.remove('active');
        };
        reader.readAsDataURL(file);
      }
      
      // Enable upload button
      uploadBtn.disabled = false;
    });
    
    // Handle form submission
    storyUploadForm.addEventListener('submit', async function(e) {
      e.preventDefault();
      
      if (!currentFileData) {
        showError('Please select a file first');
        return;
      }
      
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
      // You can implement a success toast notification here
      alert(message); // Temporary success message
    }
    
    function resetUploadForm() {
      storyFileInput.value = '';
      imagePreview.src = '';
      imagePreview.classList.remove('active');
      videoPreview.src = '';
      videoPreview.classList.remove('active');
      storyUploadForm.reset();
      uploadBtn.disabled = true;
      hideError();
      
      currentFileData = null;
      currentFileName = '';
      currentFileType = '';
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