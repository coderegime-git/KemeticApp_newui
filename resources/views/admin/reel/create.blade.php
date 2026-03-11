@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
    <style>
        .video-preview {
            width: 100%;
            max-height: 300px;
            border-radius: 12px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px dashed #dee2e6;
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
                <div class="breadcrumb-item">Create</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ getAdminPanelUrl() }}/reel/store" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-8">
                                        <!-- Title -->
                                        <div class="form-group">
                                            <label>Title *</label>
                                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
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
                                                    <option value="{{ $category['id'] }}" {{ old('category_id') == $category['id'] ? 'selected' : '' }}>
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
                                            <textarea name="caption" class="form-control @error('caption') is-invalid @enderror" rows="4" required>{{ old('caption') }}</textarea>
                                            @error('caption')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Status -->
                                        <!-- <div class="form-group">
                                            <label>Status *</label>
                                            <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                                <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div> -->
                                    </div>

                                    <div class="col-md-4">
                                        <!-- Video Upload -->
                                        <div class="form-group">
                                            <label>Video *</label>
                                            <div class="video-preview mb-3" id="videoPreview">
                                                <i class="fa fa-video" style="font-size: 48px; color: #adb5bd;"></i>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" name="video" class="custom-file-input @error('video') is-invalid @enderror" id="videoInput" accept="video/*" required>
                                                <label class="custom-file-label" for="videoInput">Choose video</label>
                                            </div>
                                            <small class="form-text text-muted">Supported formats: mp4, mov, ogg, webm (Max: 250MB)</small>
                                            @error('video')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Thumbnail Upload -->
                                        <!-- <div class="form-group mt-4">
                                            <label>Thumbnail (Optional)</label>
                                            <div class="mb-3">
                                                <img src="" alt="Thumbnail Preview" id="thumbnailPreview" class="thumbnail-preview" style="display: none;">
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" name="thumbnail" class="custom-file-input @error('thumbnail') is-invalid @enderror" id="thumbnailInput" accept="image/*">
                                                <label class="custom-file-label" for="thumbnailInput">Choose thumbnail</label>
                                            </div>
                                            <small class="form-text text-muted">Recommended size: 320x320 pixels</small>
                                            @error('thumbnail')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div> -->
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-save"></i> Save Portals
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
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                width: '100%',
                theme: 'default'
            });

            // Video preview
            $('#videoInput').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').html(fileName);

                var preview = $('#videoPreview');
                preview.html('<i class="fa fa-spinner fa-spin" style="font-size: 48px; color: #adb5bd;"></i>');

                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        preview.html('<video width="100%" height="auto" controls><source src="' + e.target.result + '" type="video/mp4">Your browser does not support the video tag.</video>');
                    }
                    reader.readAsDataURL(this.files[0]);
                } else {
                    preview.html('<i class="fa fa-video" style="font-size: 48px; color: #adb5bd;"></i>');
                }
            });

            // Thumbnail preview
            // $('#thumbnailInput').on('change', function() {
            //     var fileName = $(this).val().split('\\').pop();
            //     $(this).next('.custom-file-label').html(fileName);

            //     if (this.files && this.files[0]) {
            //         var reader = new FileReader();
            //         reader.onload = function(e) {
            //             $('#thumbnailPreview').attr('src', e.target.result).show();
            //         }
            //         reader.readAsDataURL(this.files[0]);
            //     } else {
            //         $('#thumbnailPreview').hide();
            //     }
            // });
        });
    </script>
@endpush