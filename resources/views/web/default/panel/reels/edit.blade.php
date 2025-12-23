@extends('web.default.layouts.newapp')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <!-- Plyr CSS -->
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
    <!-- Plyr JS -->
    <script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>
@endpush
<style>
        /* ===============================
   KEMETIC EDIT REEL – BLACK GOLD
================================ */

.kemetic-form {
    max-width: 700px;
}

.kemetic-label {
    font-weight: 600;
    color: #d4af37;
    margin-bottom: 6px;
    display: block;
}

.kemetic-input,
.kemetic-file-input {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(212, 175, 55, 0.35);
    color: #fff;
    border-radius: 10px;
    padding: 10px 14px;
}

.kemetic-input:focus {
    border-color: #d4af37;
    box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.25);
}

.kemetic-file-input {
    padding: 8px;
}

.kemetic-video-preview {
    width: 320px;
    max-width: 100%;
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid rgba(212, 175, 55, 0.45);
    background: #000;
}

/* Buttons */
.kemetic-btn-gold {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, #d4af37, #b8962e);
    color: #000;
    padding: 10px 18px;
    border-radius: 10px;
    font-weight: 600;
    transition: 0.3s;
}

.kemetic-btn-gold:hover {
    transform: translateY(-1px);
    box-shadow: 0 10px 35px rgba(212, 175, 55, 0.45);
}

.kemetic-btn-outline {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 16px;
    border-radius: 10px;
    border: 1px solid rgba(212, 175, 55, 0.45);
    color: #d4af37;
    transition: 0.3s;
}

.kemetic-btn-outline:hover {
    background: rgba(212, 175, 55, 0.15);
}

/* Errors */
.is-invalid {
    border-color: #ff6b6b !important;
}

/* ===============================
   KEMETIC FORM ACTIONS
================================ */

.kemetic-form-actions {
    padding-top: 20px;
    border-top: 1px solid rgba(212, 175, 55, 0.25);
}

/* Cancel Button */
.kemetic-btn-cancel {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    border-radius: 12px;
    border: 1px solid rgba(212, 175, 55, 0.45);
    color: #d4af37;
    font-weight: 500;
    transition: 0.3s ease;
}

.kemetic-btn-cancel:hover {
    background: rgba(212, 175, 55, 0.12);
    transform: translateY(-1px);
}

/* Save Button */
.kemetic-btn-save {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 22px;
    border-radius: 14px;
    font-weight: 600;
    color: #000;
    background: linear-gradient(135deg, #d4af37, #b8962e);
    box-shadow: 0 12px 40px rgba(212, 175, 55, 0.45);
    transition: 0.3s ease;
}

.kemetic-btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 18px 55px rgba(212, 175, 55, 0.6);
}

/* Mobile */
@media (max-width: 576px) {
    .kemetic-form-actions {
        flex-direction: column-reverse;
        gap: 14px;
    }

    .kemetic-btn-cancel,
    .kemetic-btn-save {
        width: 100%;
        justify-content: center;
    }
}


    </style>
@section('content')
    <section class="kemetic-section mt-25">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-20">
        <h2 class="section-title kemetic-title">Edit Reel</h2>

        <a href="{{ route('reels.index') }}" class="kemetic-btn-outline">
            <i data-feather="arrow-left" width="16"></i>
            <span class="ml-5">Back</span>
        </a>
    </div>

    {{-- Card --}}
    <div class="kemetic-card p-25">

        <form action="{{ route('reels.update', $reel->id) }}"
              method="POST"
              enctype="multipart/form-data"
              class="kemetic-form">

            @csrf
            @method('PUT')

            <input type="hidden" name="id" value="{{ $reel->id }}">

            {{-- Title --}}
            <div class="form-group">
                <label class="kemetic-label">Title</label>
                <input type="text"
                       name="title"
                       class="form-control kemetic-input @error('title') is-invalid @enderror"
                       value="{{ old('title', $reel->title) }}"
                       placeholder="Enter reel title"
                       required>

                @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Caption --}}
            <div class="form-group">
                <label class="kemetic-label">Caption</label>
                <textarea name="caption"
                          rows="5"
                          class="form-control kemetic-input @error('caption') is-invalid @enderror"
                          placeholder="Write reel caption..."
                          required>{{ old('caption', $reel->caption) }}</textarea>

                @error('caption')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Video Upload --}}
            <div class="form-group">
                <label class="kemetic-label">Replace Video <span class="text-muted">(Max 100MB)</span></label>

                <input type="file"
                       name="video"
                       accept="video/*"
                       class="form-control kemetic-file-input">

                <small class="text-muted d-block mt-5">
                    Upload only if you want to replace the existing video
                </small>
            </div>

            {{-- Video Preview --}}
            <div class="form-group mt-15">
                <label class="kemetic-label">Current Video</label>

                <div class="kemetic-video-preview">
                    <video class="plyr reel-video"
                           controls
                           preload="metadata"
                           poster="{{ $reel->thumbnail_url }}">
                        <source src="{{ $reel->video_url }}" type="video/mp4"/>
                        {{ trans('public.browser_not_support_video') }}
                    </video>
                </div>
            </div>

            {{-- Actions --}}
            <div class="kemetic-form-actions mt-35 d-flex justify-content-between align-items-center">

                <a href="{{ route('reels.index') }}" class="kemetic-btn-cancel">
                    <i data-feather="x" width="16"></i>
                    <span>Cancel</span>
                </a>

                <button type="submit" class="kemetic-btn-save">
                    <i data-feather="check-circle" width="18"></i>
                    <span>Update Reel</span>
                </button>

            </div>


        </form>

    </div>
</section>

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const players = Plyr.setup('.plyr');
        });
    </script>
@endpush
