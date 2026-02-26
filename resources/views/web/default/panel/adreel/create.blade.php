@extends('web.default.layouts.newapp')

@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
<link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
<style>
    /* ===============================
       KEMETIC REEL FORM
    ================================ */
    .kemetic-form-section {
        max-width: 800px;
        margin: 0 auto;
    }

    .kemetic-label {
        font-weight: 600;
        color: #d4af37;
        margin-bottom: 8px;
        display: block;
        font-size: 14px;
    }

    .kemetic-input,
    .kemetic-textarea,
    .kemetic-file-input {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(212, 175, 55, 0.35);
        color: #fff;
        border-radius: 10px;
        padding: 12px 16px;
        width: 100%;
        transition: all 0.3s;
    }

    .kemetic-input:focus,
    .kemetic-textarea:focus,
    .kemetic-file-input:focus {
        border-color: #d4af37;
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.25);
        outline: none;
    }

    .kemetic-textarea {
        min-height: 120px;
        resize: vertical;
    }

    .kemetic-file-input {
        padding: 10px;
    }

    .file-upload-area {
        border: 2px dashed rgba(212, 175, 55, 0.35);
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
        background: rgba(212, 175, 55, 0.05);
        cursor: pointer;
        transition: all 0.3s;
    }

    .file-upload-area:hover {
        border-color: #d4af37;
        background: rgba(212, 175, 55, 0.1);
    }

    .file-upload-area.dragover {
        border-color: #d4af37;
        background: rgba(212, 175, 55, 0.15);
    }

    .upload-icon {
        font-size: 48px;
        color: #d4af37;
        margin-bottom: 15px;
    }

    .upload-hint {
        color: #999;
        font-size: 13px;
        margin-top: 10px;
    }

    .video-preview {
        max-width: 100%;
        border-radius: 12px;
        overflow: hidden;
        margin-top: 15px;
        border: 1px solid rgba(212, 175, 55, 0.35);
    }

    .current-video {
        border: 1px solid rgba(212, 175, 55, 0.35);
        border-radius: 12px;
        overflow: hidden;
        background: #000;
        margin-top: 10px;
    }

    /* Buttons */
    .kemetic-btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(135deg, #d4af37, #b8962e);
        color: #000;
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        transition: all 0.3s;
        text-decoration: none;
    }

    .kemetic-btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 40px rgba(212, 175, 55, 0.45);
        color: #000;
    }

    .kemetic-btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 12px;
        border: 1px solid rgba(212, 175, 55, 0.45);
        color: #d4af37;
        background: transparent;
        transition: all 0.3s;
        text-decoration: none;
    }

    .kemetic-btn-cancel:hover {
        background: rgba(212, 175, 55, 0.1);
        color: #d4af37;
    }

    /* Date inputs */
    .date-input-group {
        display: flex;
        gap: 15px;
    }

    .date-input-group .form-group {
        flex: 1;
    }

    /* Error states */
    .is-invalid {
        border-color: #ff6b6b !important;
    }

    .invalid-feedback {
        color: #ff6b6b;
        font-size: 13px;
        margin-top: 5px;
    }

    /* Plan cards */
    .plan-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(212, 175, 55, 0.2);
        border-radius: 12px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .plan-card:hover {
        border-color: #d4af37;
        background: rgba(212, 175, 55, 0.05);
    }

    .plan-card.selected {
        border-color: #d4af37;
        background: rgba(212, 175, 55, 0.1);
    }

    .plan-name {
        color: #d4af37;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .plan-price {
        font-size: 24px;
        font-weight: bold;
        color: #fff;
        margin-bottom: 10px;
    }

    .plan-features {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .plan-features li {
        padding: 5px 0;
        color: #ccc;
        font-size: 13px;
    }

    .plan-features li:before {
        content: "✓";
        color: #d4af37;
        margin-right: 8px;
    }

    /* Stats for edit mode */
    .stats-card {
        background: rgba(212, 175, 55, 0.05);
        border: 1px solid rgba(212, 175, 55, 0.2);
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 25px;
    }

    .stat-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .stat-item:last-child {
        border-bottom: none;
    }

    .kemetic-select {
        background: #1a1a1a !important;
        border: 1px solid var(--kemetic-border) !important;
        color: var(--kemetic-text) !important;
        border-radius: var(--kemetic-radius) !important;
        padding: 10px 14px !important;
        transition: 0.25s ease;
    }

    .kemetic-select:focus {
        border-color: var(--kemetic-gold) !important;
        box-shadow: 0 0 12px rgba(242, 201, 76, 0.35);
    }

    /* ===============================
    SELECT2 – KEMETIC DARK THEME
    ================================ */

    /* main box */
    .select2-container--default .select2-selection--single {
        background: #0d0d0d !important;
        border: 1px solid rgba(242,201,76,.35) !important;
        border-radius: 14px !important;
        height: 45px !important;
        display: flex;
        align-items: center;
        color: #e0e0e0 !important;
    }

    /* text */
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #e0e0e0 !important;
        line-height: 45px !important;
    }

    /* arrow */
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #f2c94c transparent transparent transparent !important;
    }

    /* dropdown */
    .select2-dropdown {
        background: #0f0f0f !important;
        border: 1px solid rgba(242,201,76,.35) !important;
        border-radius: 12px !important;
    }

    /* options */
    .select2-results__option {
        color: #e0e0e0 !important;
        padding: 10px 14px !important;
    }

    /* hover */
    .select2-results__option--highlighted {
        background: rgba(242,201,76,.15) !important;
        color: #fff !important;
    }

    /* selected */
    .select2-results__option[aria-selected=true] {
        background: rgba(242,201,76,.25) !important;
    }

    /* search box */
    .select2-search--dropdown .select2-search__field {
        background: #0d0d0d !important;
        border: 1px solid rgba(242,201,76,.35) !important;
        color: #fff !important;
        border-radius: 8px !important;
    }
</style>

<link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
@endpush

@section('content')
<section class="kemetic-section mt-25">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-25">
        <h2 class="section-title kemetic-title">
            @if(isset($reel))
                Edit Reel: {{ $reel->title }}
            @else
                Create New Ad Reel
            @endif
        </h2>

        <a href="/panel/adreel" class="kemetic-btn-cancel">
            <i data-feather="arrow-left" width="16"></i>
            <span>Back to List</span>
        </a>
    </div>


    {{-- Form Card --}}
    <div class="kemetic-card p-30">
        
          <form action="/panel/adreel/{{ (!empty($reel) ? $reel->id.'/update' : 'store') }}" method="post" 
              enctype="multipart/form-data"
              class="kemetic-form-section"
              id="reelForm">
            @csrf

            {{-- Title --}}
            <div class="form-group mb-25">
                <label class="kemetic-label" for="title">Title *</label>
                <input type="text" 
                       id="title"
                       name="title" 
                       class="kemetic-input @error('title') is-invalid @enderror"
                       value="{{ old('title', $reel->title ?? '') }}"
                       placeholder="Enter a catchy title for your reel"
                       required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Caption --}}
            <!-- <div class="form-group mb-25">
                <label class="kemetic-label" for="caption">Caption / Description</label>
                <textarea id="caption"
                          name="caption" 
                          class="kemetic-textarea @error('caption') is-invalid @enderror"
                          placeholder="Describe your reel content (optional)"
                          rows="4">{{ old('caption', $reel->caption ?? '') }}</textarea>
                @error('caption')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div> -->

            {{-- Product Selection --}}
            <div class="form-group mb-25">
                <label class="kemetic-label" for="product_id">Associated Product</label>
                <select id="product_id"
                        name="product_id" 
                        class="kemetic-select select2 @error('product_id') is-invalid @enderror">
                    <option value="">-- Select a Product --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" 
                            {{ old('product_id', $reel->product_id ?? '') == $product->id ? 'selected' : '' }}>
                            {{ $product->title }} ({{ $product->price ? '$' . $product->price : 'Free' }})
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted mt-2 d-block">
                    Link this reel to one of your products for better promotion
                </small>
            </div>

            {{-- Video Section --}}
            @if(isset($reel))
                {{-- Edit Mode: Current Video --}}
                <div class="form-group mb-25">
                    <label class="kemetic-label">Current Video</label>
                    <div class="current-video">
                        <video class="plyr" controls style="width:100%;max-height:300px;">
                            <source src="{{ $reel->video_url }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>

                {{-- Edit Mode: Replace Video --}}
                <div class="form-group mb-25">
                    <label class="kemetic-label">Replace Video (Optional)</label>
                    <input type="file" 
                           name="video" 
                           class="kemetic-file-input @error('video') is-invalid @enderror"
                           accept="video/*">
                    @error('video')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted mt-2 d-block">
                        Leave empty to keep the current video. Max 250MB
                    </small>
                </div>
            @else
                {{-- Create Mode: Upload Video --}}
                <div class="form-group mb-30">
                    <label class="kemetic-label">Upload Video *</label>
                    
                    <div class="file-upload-area" id="fileUploadArea">
                        <div class="upload-icon">
                            <i data-feather="upload-cloud" width="48" height="48"></i>
                        </div>
                        <h5 class="text-white mb-2">Drop your video here or click to browse</h5>
                        <p class="text-muted">MP4, MOV, AVI, WMV formats up to 250MB</p>
                        
                        <input type="file" 
                               id="videoInput"
                               name="video" 
                               class="d-none"
                               accept="video/*"
                               {{ !isset($reel) ? 'required' : '' }}>
                        
                        <div class="upload-hint" id="fileInfo">
                            No file selected
                        </div>
                    </div>
                    
                    <div class="video-preview" id="videoPreview" style="display: none;">
                        <video controls style="width:100%;max-height:300px;">
                            <source src="" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    
                    @error('video')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            {{-- Plan Selection --}}
            <!-- <div class="form-group mb-30">
                <label class="kemetic-label">Select Plan </label>
                <div class="row">
                    @foreach($plans as $plan)
                        <div class="col-md-4 mb-3">
                            <div class="plan-card {{ old('plan_code', $reel->plan_code ?? '') == $plan->code ? 'selected' : '' }}" 
                                 onclick="selectPlan('{{ $plan->code }}')">
                                <div class="plan-name">{{ $plan->name }}</div>
                                <div class="plan-price">${{ number_format($plan->price, 2) }}</div>
                                <ul class="plan-features">
                                    <li>{{ $plan->duration_days }} days visibility</li>
                                    <li>Featured placement</li>
                                    <li>Analytics dashboard</li>
                                </ul>
                                <input type="radio" 
                                       name="plan_code" 
                                       value="{{ $plan->code }}" 
                                       class="d-none"
                                       {{ old('plan_code', $reel->plan_code ?? '') == $plan->code ? 'checked' : '' }}>
                            </div>
                        </div>
                    @endforeach
                </div>
                <small class="text-muted mt-2 d-block">
                    Choose a plan to boost your reel's visibility
                </small>
            </div> -->

            {{-- Actions --}}
            <div class="form-group mt-40 pt-20 border-top border-gold-20" style="padding:10px;">
                <div class="d-flex justify-content-between">
                    <a href="/panel/adreel" class="kemetic-btn-cancel">
                        <i data-feather="x" width="16"></i>
                        Cancel
                    </a>
                    
                    <div>
                        <button type="submit" class="kemetic-btn-submit">
                            @if(isset($reel))
                                <i data-feather="save" width="18"></i>
                                Update Reel
                            @else
                                <i data-feather="upload-cloud" width="18"></i>
                                Upload Reel
                            @endif
                        </button>
                        
                        @if(isset($reel))
                        <!-- <button type="button" 
                                class="kemetic-btn-cancel ml-3"
                                onclick="updateTrendingScore()">
                            <i data-feather="trending-up" width="16"></i>
                            Update Trending Score
                        </button> -->
                        @endif
                    </div>
                </div>
            </div>

        </form>

    </div>

</section>

@endsection

@push('scripts_bottom')
<script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
<script src="/assets/default/vendors/select2/select2.min.js"></script>
<script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>
<script>
    // Initialize video player for edit mode
    @if(isset($reel))
    document.addEventListener('DOMContentLoaded', () => {
        const players = Plyr.setup('.plyr');
    });
    @endif

    // File upload handling (Create mode only)
    @if(!isset($reel))
    const fileUploadArea = document.getElementById('fileUploadArea');
    const videoInput = document.getElementById('videoInput');
    const fileInfo = document.getElementById('fileInfo');
    const videoPreview = document.getElementById('videoPreview');
    const previewVideo = videoPreview.querySelector('video source');

    // Click to upload
    fileUploadArea.addEventListener('click', () => videoInput.click());

    // File input change
    videoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (250MB)
            if (file.size > 250 * 1024 * 1024) {
                alert('File size exceeds 250MB limit');
                videoInput.value = '';
                return;
            }
            
            // Validate file type
            const validTypes = ['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/x-ms-wmv'];
            if (!validTypes.includes(file.type)) {
                alert('Please upload a valid video file (MP4, MOV, AVI, WMV)');
                videoInput.value = '';
                return;
            }
            
            // Update file info
            fileInfo.textContent = `${file.name} (${(file.size / (1024 * 1024)).toFixed(2)} MB)`;
            
            // Show preview
            const videoURL = URL.createObjectURL(file);
            previewVideo.src = videoURL;
            videoPreview.style.display = 'block';
            videoPreview.querySelector('video').load();
            
            // Add loading state to form
            // fileUploadArea.innerHTML = `
            //     <div class="text-center">
            //         <div class="spinner-border text-gold mb-3" role="status">
            //             <span class="sr-only">Loading...</span>
            //         </div>
            //         <p class="text-white">Processing video...</p>
            //     </div>
            // `;
        }
    });

    // Drag and drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        fileUploadArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        fileUploadArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        fileUploadArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        fileUploadArea.classList.add('dragover');
    }

    function unhighlight() {
        fileUploadArea.classList.remove('dragover');
    }

    fileUploadArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        videoInput.files = files;
        
        // Trigger change event
        const event = new Event('change');
        videoInput.dispatchEvent(event);
    }
    @endif

    // Plan selection (Both modes)
    function selectPlan(planCode) {
        // Remove selected class from all cards
        document.querySelectorAll('.plan-card').forEach(card => {
            card.classList.remove('selected');
            card.querySelector('input[type="radio"]').checked = false;
        });
        
        // Add selected class to clicked card
        const selectedCard = document.querySelector(`.plan-card input[value="${planCode}"]`).closest('.plan-card');
        selectedCard.classList.add('selected');
        selectedCard.querySelector('input[type="radio"]').checked = true;
    }

    // Initialize select2
    $(document).ready(function() {
        
        // Form submission
        $('#reelForm').on('submit', function() {
            const submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true);
            submitBtn.html(`
                <div class="spinner-border spinner-border-sm mr-2" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                @if(isset($reel))
                    Updating...
                @else
                    Uploading...
                @endif
            `);
        });
    });

    // Update trending score (Edit mode only)
    @if(isset($reel))
    function updateTrendingScore() {
        if (confirm('Update trending score for this reel?')) {
            $.ajax({
                url: '/panel/adreel.update_trending/{{ $reel->id }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        alert('Trending score updated to: ' + response.trending_score);
                        location.reload();
                    }
                },
                error: function() {
                    alert('Error updating trending score');
                }
            });
        }
    }
    @endif
</script>
@endpush