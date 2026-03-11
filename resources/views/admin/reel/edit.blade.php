@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
    <style>
        .current-video {
            width: 100%;
            max-height: 300px;
            border-radius: 12px;
            background: #f8f9fa;
        }
        .current-thumbnail {
            width: 150px;
            height: 150px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #dee2e6;
        }
        .thumbnail-preview {
            width: 150px;
            height: 150px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #dee2e6;
        }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item"><a href="{{ getAdminPanelUrl() }}/reel">Portals</a></div>
                <div class="breadcrumb-item">Edit</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ getAdminPanelUrl() }}/reel/{{ $reel->id }}/update" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-8">
                                        <!-- Title -->
                                        <div class="form-group">
                                            <label>Title *</label>
                                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $reel->title) }}" required>
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Category -->
                                        <div class="form-group">
                                            <label>Category *</label>
                                            <select name="category_id" class="form-control select2 @error('category_id') is-invalid @enderror" required>
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category['id'] }}" {{ (old('category_id', $reel->category_id) == $category['id']) ? 'selected' : '' }}>
                                                        {{ $category['title'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Caption -->
                                        <div class="form-group">
                                            <label>Caption *</label>
                                            <textarea name="caption" class="form-control @error('caption') is-invalid @enderror" rows="4" required>{{ old('caption', $reel->caption) }}</textarea>
                                            @error('caption')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Status -->
                                        <!-- <div class="form-group">
                                            <label>Status *</label>
                                            <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                                <option value="pending" {{ (old('status', $reel->status) == 'pending') ? 'selected' : '' }}>Pending</option>
                                                <option value="published" {{ (old('status', $reel->status) == 'published') ? 'selected' : '' }}>Published</option>
                                                <option value="rejected" {{ (old('status', $reel->status) == 'rejected') ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div> -->
                                    </div>

                                    <div class="col-md-4">
                                        <!-- Current Video -->
                                        <div class="form-group">
                                            <label>Current Video</label>
                                            <div class="current-video mb-3">
                                                @if($reel->video_url)
                                                    <video class="plyr" controls>
                                                        <source src="{{ $reel->video_url }}" type="video/mp4">
                                                    </video>
                                                @else
                                                    <div class="p-5 text-center">
                                                        <i class="fa fa-video" style="font-size: 48px; color: #adb5bd;"></i>
                                                        <p class="mt-2">No video uploaded</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Video Upload -->
                                        <div class="form-group">
                                            <label>Update Video (Optional)</label>
                                            <div class="custom-file">
                                                <input type="file" name="video" class="custom-file-input @error('video') is-invalid @enderror" id="videoInput" accept="video/*">
                                                <label class="custom-file-label" for="videoInput">Choose new video</label>
                                            </div>
                                            <small class="form-text text-muted">Leave empty to keep current video. Supported formats: mp4, mov, ogg, webm (Max: 250MB)</small>
                                            @error('video')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Current Thumbnail -->
                                        <!-- <div class="form-group mt-4">
                                            <label>Current Thumbnail</label>
                                            <div class="mb-3">
                                                @if($reel->thumbnail_url)
                                                    <img src="{{ $reel->thumbnail_url }}" alt="Current Thumbnail" class="current-thumbnail">
                                                @else
                                                    <div class="current-thumbnail bg-secondary d-flex align-items-center justify-content-center">
                                                        <i class="fa fa-image text-white" style="font-size: 32px;"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        </div> -->

                                        <!-- Thumbnail Upload -->
                                        <!-- <div class="form-group">
                                            <label>Update Thumbnail (Optional)</label>
                                            <div class="mb-3">
                                                <img src="" alt="New Thumbnail Preview" id="thumbnailPreview" class="thumbnail-preview" style="display: none;">
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" name="thumbnail" class="custom-file-input @error('thumbnail') is-invalid @enderror" id="thumbnailInput" accept="image/*">
                                                <label class="custom-file-label" for="thumbnailInput">Choose new thumbnail</label>
                                            </div>
                                            <small class="form-text text-muted">Leave empty to keep current thumbnail. Recommended size: 320x320 pixels</small>
                                            @error('thumbnail')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div> -->
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-save"></i> Update Portals
                                    </button>
                                    <a href="{{ getAdminPanelUrl() }}/reel" class="btn btn-secondary">
                                        <i class="fa fa-times"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                width: '100%',
                theme: 'default'
            });

            // Initialize Plyr
            const player = Plyr.setup('.plyr', {
                controls: ['play', 'progress', 'current-time', 'mute', 'volume', 'fullscreen'],
            });

            // Video input change
            $('#videoInput').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').html(fileName || 'Choose new video');
            });

            // Thumbnail preview
            $('#thumbnailInput').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').html(fileName || 'Choose new thumbnail');

                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#thumbnailPreview').attr('src', e.target.result).show();
                    }
                    reader.readAsDataURL(this.files[0]);
                } else {
                    $('#thumbnailPreview').hide();
                }
            });
        });
    </script>
@endpush