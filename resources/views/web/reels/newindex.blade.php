@extends('web.default.layouts.app')

@push('styles_top')
<style>
    .reels-container {
        padding: 20px 0;
    }
    .section-title {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 25px;
    }
    .reel-card {
        background-color: var(--secondary-bg);
        margin-bottom: 20px;
        border-radius: 10px;
        box-shadow: 0 3px 15px 0 rgba(0,0,0,.1);
        overflow: hidden;
    }
    .reel-video-container {
        position: relative;
        width: 100%;
        padding-top: 177.78%;
        background-color: var(--secondary-bg);
    }
    .reel-video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .reel-body {
        padding: 15px;
    }
    .reel-title {
        font-weight: 500;
        font-size: 16px;
        margin-bottom: 10px;
    }
    .reel-caption {
        font-size: 14px;
        color: var(--gray);
        margin-bottom: 15px;
    }
    .reel-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .action-btns {
        display: flex;
        gap: 15px;
    }
    .action-btn {
        background: transparent;
        border: none;
        padding: 5px;
        cursor: pointer;
        color: var(--gray);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .action-btn:hover {
        color: var(--primary);
    }
    .action-btn.liked {
        color: #f74141;
    }
    .action-btn.liked svg {
        fill: #f74141;
        stroke: #f74141;
    }
    .action-btn:hover.liked {
        color: #f74141;
    }
    .action-btn i {
        font-size: 18px;
    }
    .stat-value {
        margin-left: 4px;
    }
    .stat-value {
        font-size: 12px;
        margin-left: 5px;
        color: var(--gray);
    }
    .time-info {
        font-size: 12px;
        color: var(--gray);
    }
    @media (max-width: 991px) {
        .reels-container {
            padding: 15px 0;
        }
        .section-title {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .section-subtitle {
            font-size: 16px;
            color: var(--gray);
            margin: 0;
        }
    }
</style>
@endpush

@section('content')
    <!-- Comments Modal -->
    <!-- Report Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Report Reel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="reportForm" onsubmit="submitReport(event)">
                        <div class="mb-3">
                            <label class="form-label">Select a reason:</label>
                            <div id="reportTemplates">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="reason" id="reportSpam" value="Spam" required>
                                    <label class="form-check-label" for="reportSpam">Spam</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="reason" id="reportInappropriate" value="Inappropriate Content">
                                    <label class="form-check-label" for="reportInappropriate">Inappropriate Content</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="reason" id="reportCopyright" value="Copyright Violation">
                                    <label class="form-check-label" for="reportCopyright">Copyright Violation</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="reason" id="reportOther" value="Other">
                                    <label class="form-check-label" for="reportOther">Other</label>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="reportReelId" name="reel_id" value="">
                        <div id="reportSubmitStatus" class="mt-2"></div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Submit Report</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="commentsModal" tabindex="-1" aria-labelledby="commentsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentsModalLabel">Comments</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="commentsModalBody">
                    <!-- Comments will be injected here -->
                    <div id="commentsList"></div>
                    <div class="mt-3">
                        <form id="addCommentForm" onsubmit="submitComment(event)">
                            <div class="input-group">
                                <input type="text" class="form-control" id="newCommentInput" name="comment" placeholder="Add a comment..." required maxlength="500" style="
                                padding-block: 1.45rem;
                                font-size: .9rem;
                                ">
                                <button class="btn btn-primary" type="submit">Submit</button>
                                <button type="button" class="btn btn-secondary" id="closeCommentsModalBtn">Close</button>
                            </div>
                            <input type="hidden" id="currentReelId" name="reel_id" value="">
                        </form>
                        <div id="commentSubmitStatus" class="mt-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="reels-container mt-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="section-title mb-2">{{ trans('public.reels') }}</h2>
                    <p class="section-subtitle">Explore our reels</p>
                </div>
                @if (auth()->check()) 
                {{-- Only show Create Reel button if user is authenticated --}}
                <button type="button" class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i data-feather="plus-circle"></i>
                    Create Reel
                </button>
                @endif
                
            </div>

            <!-- Upload Modal -->
            <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="uploadModalLabel">Upload New Reel</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="uploadForm" action="{{ route('reels.store') }}" method="POST" enctype="multipart/form-data">
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

            <div class="row">
                @foreach($reels as $reel)
                    <div class="col-12 col-md-4 col-lg-4">
                        <div class="reel-card">
                            <div class="reel-video-container">
                                <video class="reel-video" controls preload="metadata" poster="{{ $reel->thumbnail_url }}">
                                    <source src="{{ $reel->video_url }}" type="video/mp4">
                                    {{ trans('public.browser_not_support_video') }}
                                </video>
                            </div>
                            
                            <div class="reel-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h3 class="reel-title">{{ $reel->title }}</h3>
                                    <h3 class="badge badge-primary d-flex align-items-center">
                                        <i data-feather="user" width="15" height="15"></i> <span>{{ $reel->user?->full_name ?? '' }}</span>
                                    </h3>
                                </div>
                                <p class="reel-caption">{{ $reel->caption }}</p>
                                
                                <div class="reel-actions">
                                    <div class="action-btns">
                                        <form action="{{ route('reels.like', $reel->id) }}" method="POST" class="d-inline like-form">
                                            @csrf
                                            <button type="submit" class="action-btn d-flex align-items-center {{ $reel->likes->count() > 0 ? 'liked' : '' }}">
                                                <i data-feather="heart" width="20" height="20"></i>
                                                <span class="stat-value">{{ $reel->likes_count }}</span>
                                            </button>
                                        </form>

                    <button type="button" class="action-btn d-flex align-items-center" 
                        onclick="showComments('{{ $reel->id }}', {{ json_encode($reel->comments->map(function($c){return ['user'=>$c->user->full_name??'Unknown','content'=>$c->content];})) }}, {{ $reel->comments_count }})"
                        data-reel-id="{{ $reel->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                                            <span class="stat-value">{{ $reel->comments_count }}</span>
                                        </button>

                                        <button type="button" class="action-btn d-flex align-items-center" 
                                                    onclick="showReportModal('{{ $reel->id }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-octagon"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                            <!-- Report count removed as requested -->
                                        </button>
                                    </div>

                                    <span class="time-info">{{ dateTimeFormat($reel->created_at, 'j M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $reels->links() }}
        </div>
    </div>
@endsection

@push('scripts_bottom')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js') }}"></script>
<script src="{{ asset('/assets/default/vendors/feather-icons/dist/feather.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var closeBtn = document.getElementById('closeCommentsModalBtn');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            let commentsModal = bootstrap.Modal.getInstance(document.getElementById('commentsModal'));
            commentsModal.hide();
            setTimeout(function() {
                location.reload();
            }, 400);
        });
    }
});
function showReportModal(reelId) {
    document.getElementById('reportReelId').value = reelId;
    document.getElementById('reportSubmitStatus').innerHTML = '';
    document.getElementById('reportForm').reset();
    let reportModal = new bootstrap.Modal(document.getElementById('reportModal'));
    reportModal.show();
}

function submitReport(event) {
    event.preventDefault();
    const reelId = document.getElementById('reportReelId').value;
    const reason = document.querySelector('input[name="reason"]:checked')?.value;
    const statusDiv = document.getElementById('reportSubmitStatus');
    statusDiv.innerHTML = '';
    if (!reason) {
        statusDiv.innerHTML = '<span class="text-danger">Please select a reason.</span>';
        return;
    }
    $.ajax({
        url: '/reels/' + reelId + '/report',
        type: 'POST',
        data: {
            reason: reason,
            reel_id: reelId,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                statusDiv.innerHTML = '<span style="color:#218838;font-weight:bold;">Report submitted!</span>';
                setTimeout(function() {
                    let reportModal = bootstrap.Modal.getInstance(document.getElementById('reportModal'));
                    reportModal.hide();
                    setTimeout(function() {
                        location.reload();
                    }, 400);
                }, 1200);
            } else {
                statusDiv.innerHTML = '<span class="text-danger">Failed to submit report.</span>';
            }
        },
        error: function() {
            statusDiv.innerHTML = '<span class="text-danger">Error submitting report.</span>';
        }
    });
}
// Initialize Feather icons as soon as the script loads
if (typeof feather !== 'undefined') {
    feather.replace();
}

function showComments(reelId, comments, commentCount) {
    // Build comments list
    let commentsHtml = `<h5>Reel #${reelId}</h5><p>Comments (${commentCount}):</p><ul class='list-group'>`;
    if (comments && comments.length > 0) {
        comments.forEach(comment => {
            commentsHtml += `<li class='list-group-item'><strong>${comment.user}:</strong> ${comment.content}</li>`;
        });
    } else {
        commentsHtml += '<li class="list-group-item">No comments yet</li>';
    }
    commentsHtml += '</ul>';

    // Set comments list and reel id
    document.getElementById('commentsList').innerHTML = commentsHtml;
    document.getElementById('currentReelId').value = reelId;
    document.getElementById('newCommentInput').value = '';
    document.getElementById('commentSubmitStatus').innerHTML = '';
    let commentsModal = new bootstrap.Modal(document.getElementById('commentsModal'));
    commentsModal.show();
}

function submitComment(event) {
    event.preventDefault();
    const reelId = document.getElementById('currentReelId').value;
    const commentContent = document.getElementById('newCommentInput').value;
    const statusDiv = document.getElementById('commentSubmitStatus');
    statusDiv.innerHTML = '';
    if (!commentContent.trim()) {
        statusDiv.innerHTML = '<span class="text-danger">Comment cannot be empty.</span>';
        return;
    }
    // AJAX POST to add comment
    $.ajax({
        url: '/reels/' + reelId + '/comment',
        type: 'POST',
        data: {
            comment: commentContent,
            reel_id: reelId,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                statusDiv.innerHTML = '<span class="text-success">Comment added!</span>';
                // Optionally, append the new comment to the list
                let newCommentHtml = `<li class='list-group-item'><strong>${response.user}:</strong> ${response.comment}</li>`;
                document.querySelector('#commentsList ul').insertAdjacentHTML('beforeend', newCommentHtml);
                // Update the comment count in the heading
                const heading = document.querySelector('#commentsList h5 + p');
                if (heading) {
                    heading.textContent = `Comments (${response.comments_count}):`;
                }
                document.getElementById('newCommentInput').value = '';
            } else {
                statusDiv.innerHTML = '<span class="text-danger">Failed to add comment.</span>';
            }
        },
        error: function() {
            statusDiv.innerHTML = '<span class="text-danger">Please login to be able to comment.</span>';
        }
    });
}
</script>
<script>
   

    // Auto-pause other videos when one starts playing
    document.addEventListener('play', function(e) {
        if (e.target.tagName === 'VIDEO') {
            const videos = document.querySelectorAll('video');
            videos.forEach(video => {
                if (video !== e.target) {
                    video.pause();
                }
            });
        }
    }, true);

    // Handle video file input
    document.getElementById('videoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const video = document.querySelector('#videoPreview video');
            video.src = URL.createObjectURL(file);
            document.getElementById('videoPreview').classList.remove('d-none');
        }
    });

    // Handle reel upload
    $(document).ready(function () {
        // Initialize Modal
        const uploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));

        // Handle form submission
        $('#uploadForm').on('submit', function (e) {
            e.preventDefault();
            
            let formData = new FormData(this);
            
            $.ajax({
                xhr: function () {
                    let xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (e) {
                        if (e.lengthComputable) {
                            let percent = Math.round((e.loaded / e.total) * 100);
                            $('#uploadProgress').css('width', percent + '%').text(percent + '%');
                        }
                    });
                    return xhr;
                },
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        // Show success message
                       

                        // Close modal and reset form
                        uploadModal.hide();
                        $('#uploadForm')[0].reset();
                        $('#uploadProgress').css('width', '0%').text('0%');
                        $('#videoPreview').addClass('d-none');

                        // Reload page after a short delay and reinitialize icons
                        setTimeout(function() {
                            location.reload();
                            // Initialize Feather icons after reload
                            window.addEventListener('load', function() {
                                feather.replace();
                            });
                    }, 1000);
                }
            },
            error: function (xhr) {
                // Show error message

            }
                    // Reset progress
                    $('#uploadProgress').css('width', '0%').text('0%');
                }

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Feather icons
    feather.replace();

    // Initialize modal
    const uploadModal = document.getElementById('uploadModal');
    const modal = new bootstrap.Modal(uploadModal);

    // File input change handler
    document.getElementById('videoFile').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const videoPreview = document.getElementById('videoPreview');
            const video = videoPreview.querySelector('video');
            video.src = URL.createObjectURL(file);
            videoPreview.classList.remove('d-none');
        }
    });

    // Form submission handler
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const progressBar = document.getElementById('uploadProgress');
                
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhr: function() {
                        const xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener('progress', function(e) {
                            if (e.lengthComputable) {
                                const percent = Math.round((e.loaded / e.total) * 100);
                                progressBar.css('width', percent + '%').text(percent + '%');
                            }
                        });
                        return xhr;
                    },
                    success: function(response) {

                    // Hide modal using Bootstrap 5 method
                    const myModal = bootstrap.Modal.getInstance(document.getElementById('createReelModal'));
                    myModal.hide();

                    // Show success toast
                    // Reset form and UI
                    document.getElementById('reelUploadForm').reset();
                    document.getElementById('videoPreview').classList.add('d-none');
                    statusDiv.classList.add('d-none');
                    progressDiv.classList.add('d-none');
                    progressBar.style.width = '0%';
                    progressBar.textContent = '';
                    uploadBtn.disabled = false;
                    cancelBtn.disabled = false;

                    // Add the new reel to the page
                    if (response.data) {
                        const reelsContainer = document.querySelector('.row');
                            const newReelHtml = `
                                <div class="col-12 col-md-4 col-lg-4">
                                    <div class="reel-card">
                                        <div class="reel-video-container">
                                            <video class="reel-video" controls preload="metadata" poster="${response.data.thumbnail_url || ''}">
                                                <source src="${response.data.video_url}" type="video/mp4">
                                                {{ trans('public.browser_not_support_video') }}
                                            </video>
                                        </div>
                                        
                                        <div class="reel-body">
                                            <h3 class="reel-title">${response.data.title}</h3>
                                            <p class="reel-caption">${response.data.caption}</p>
                                            
                                            <div class="reel-actions">
                                                <div class="action-btns">
                                                    <form action="/reels/${response.data.id}/like" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="action-btn d-flex align-items-center">
                                                            <i data-feather="heart" width="20" height="20"></i>
                                                            <span class="stat-value">0</span>
                                                        </button>
                                                    </form>

                                                    <button type="button" class="action-btn d-flex align-items-center" 
                                                            data-reel-id="${response.data.id}">
                                                        <i data-feather="message-circle" width="20" height="20"></i>
                                                        <span class="stat-value">0</span>
                                                    </button>

                                                    <button type="button" class="action-btn d-flex align-items-center" 
                                                            onclick="showReportModal(${response.data.id})">
                                                        <i data-feather="alert-octagon" width="20" height="20"></i>
                                                        <span class="stat-value">0</span>
                                                    </button>
                                                </div>

                                                <span class="time-info">Just now</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;

                            // Add the new reel at the beginning of the container
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = newReelHtml;
                            const newReelElement = tempDiv.firstElementChild;
                            reelsContainer.insertBefore(newReelElement, reelsContainer.firstChild);

                            // Reinitialize Feather icons for the new reel
                            if (typeof feather !== 'undefined') {
                                setTimeout(function() {
                                    feather.replace();
                                }, 100);
                            }

                            // Scroll to the new reel with smooth animation
                            newReelElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                }
            } else {
                handleUploadError(xhr);
            }
        });

        xhr.addEventListener('error', function() {
            handleUploadError(xhr);
        });

        // Send the request
        xhr.open('POST', '{{ route("reels.store") }}', true);
        xhr.send(formData);
    }

    function handleUploadError(xhr) {
        let message = 'An error occurred while uploading';
        try {
            const response = JSON.parse(xhr.response);
            if (response.message) {
                message = response.message;
            }
        } catch (e) {
            console.error('Error parsing response:', e);
        }

       

        // Reset progress bar and enable buttons
        document.getElementById('uploadProgress').classList.add('d-none');
        document.getElementById('uploadStatus').classList.add('d-none');
        document.querySelector('#uploadProgress .progress-bar').style.width = '0%';
        document.getElementById('uploadReelBtn').disabled = false;
        document.getElementById('cancelUploadBtn').disabled = false;
    }
    

    // Function to initialize Feather icons
    function initFeatherIcons() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        } else {
            setTimeout(initFeatherIcons, 100); // retry if feather is not loaded yet
        }
    }

    // Initialize components and ensure icons are loaded
    $(document).ready(function() {
        // Initialize Feather icons
        initFeatherIcons();

        // Reinitialize icons after any AJAX operation
        $(document).ajaxComplete(function() {
            initFeatherIcons();
        });

        // Also initialize icons when DOM changes
        const observer = new MutationObserver(function(mutations) {
            initFeatherIcons();
        });

        // Observe the entire document for changes
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });

        // Handle modal cleanup and form reset on hide
        $('#createReelModal').on('hidden.bs.modal', function () {
            document.getElementById('reelUploadForm').reset();
            document.getElementById('videoPreview').classList.add('d-none');
            document.getElementById('uploadProgress').classList.add('d-none');
            document.getElementById('uploadStatus').classList.add('d-none');
            const progressBar = document.querySelector('#uploadProgress .progress-bar');
            progressBar.style.width = '0%';
            progressBar.textContent = '';
            document.getElementById('uploadReelBtn').disabled = false;
            document.getElementById('cancelUploadBtn').disabled = false;
            location.reload(); // Force page reload when modal is hidden
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.like-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(form);
            const url = form.getAttribute('action');
            const button = form.querySelector('button');
            const statSpan = form.querySelector('.stat-value');

            axios.post(url, formData)
                .then(response => {
                    if (response.data.success) {
                        button.classList.toggle('liked');
                        statSpan.textContent = response.data.likes_count;
                    }
                })
                .catch(error => {
                    console.error('Like failed:', error);
                });
        });
    });
});
</script>

@endpush
