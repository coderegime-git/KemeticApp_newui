@extends('web.default.layouts.newapp')

@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
<link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
<style>
    /* ===============================
       KEMETIC AD REELS PANEL
    ================================ */
    .kemetic-section {
        color: #fff;
    }

    .kemetic-title {
        font-size: 24px;
        font-weight: 600;
        color: #d4af37;
        margin-bottom: 25px;
    }

    /* Card */
    .kemetic-card {
        background: linear-gradient(145deg, #0b0b0b, #151515);
        border-radius: 16px;
        border: 1px solid rgba(212, 175, 55, 0.25);
        box-shadow: 0 15px 45px rgba(0, 0, 0, 0.65);
        overflow: hidden;
    }

    /* Table */
    .kemetic-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .kemetic-table thead {
        background: rgba(212, 175, 55, 0.12);
    }

    .kemetic-table th {
        padding: 14px;
        font-size: 13px;
        color: #d4af37;
        text-transform: uppercase;
        border-bottom: 1px solid rgba(212, 175, 55, 0.25);
    }

    .kemetic-table td {
        padding: 16px 14px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        vertical-align: middle;
        font-size: 14px;
    }

    /* Video Preview */
    .kemetic-video-preview {
        width: 120px;
        height: 80px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid rgba(212, 175, 55, 0.35);
        background: #000;
    }

    .kemetic-video-preview video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Badges */
    .kemetic-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .badge-gold {
        background: rgba(212, 175, 55, 0.18);
        color: #d4af37;
    }

    .badge-green {
        background: rgba(76, 175, 80, 0.18);
        color: #4caf50;
    }

    .badge-red {
        background: rgba(244, 67, 54, 0.18);
        color: #f44336;
    }

    /* Actions */
    .kemetic-action {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: 0.25s;
    }

    .action-edit {
        background: rgba(212, 175, 55, 0.2);
        color: #d4af37;
    }

    .action-delete {
        background: rgba(255, 80, 80, 0.2);
        color: #ff6b6b;
    }

    .action-preview {
        background: rgba(33, 150, 243, 0.2);
        color: #2196f3;
    }

    .kemetic-action:hover {
        transform: scale(1.08);
    }

    /* Create Button */
    .kemetic-btn-create {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #d4af37, #b8962e);
        color: #000;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        transition: 0.3s;
        text-decoration: none;
    }

    .kemetic-btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(212, 175, 55, 0.45);
        color: #000;
    }

    /* Filters */
    .kemetic-filters {
        background: rgba(212, 175, 55, 0.05);
        border: 1px solid rgba(212, 175, 55, 0.2);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
    }

    .kemetic-input {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(212, 175, 55, 0.35);
        color: #fff;
        border-radius: 8px;
        padding: 8px 12px;
    }

    .kemetic-input:focus {
        border-color: #d4af37;
        box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.25);
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

    .kemetic-action.edit {
        background: rgba(212, 175, 55, 0.2);
        color: #d4af37;
    }

    .kemetic-action.delete {
        background: rgba(255, 80, 80, 0.2);
        color: #ff6b6b;
    }
</style>
@endpush

@section('content')
<section class="kemetic-section">

    <div class="d-flex align-items-center justify-content-between mb-20">
        <h2 class="section-title kemetic-title">Ad Reels Management</h2>

        <a href="/panel/adreel/create" class="kemetic-btn-create">
            <i data-feather="plus" width="18"></i>
            <span>Create New Reel</span>
        </a>
    </div>

    {{-- Filters --}}
    <div class="kemetic-filters">
        <form method="GET" action="/adreel">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <input type="text" 
                           name="search" 
                           class="form-control kemetic-input"
                           placeholder="Search title or caption..."
                           value="{{ request('search') }}">
                </div>
                
                <div class="col-md-2 mb-3">
                    <select name="status" class="kemetic-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>
                
                <div class="col-md-2 mb-3">
                    <select name="plan" class="kemetic-select">
                        <option value="">All Plans</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->code }}" {{ request('plan') == $plan->code ? 'selected' : '' }}>
                                {{ $plan->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn kemetic-btn-create mr-2">
                        <i data-feather="search" width="16"></i>
                        Filter
                    </button>
                    <a href="/adreel" class="btn kemetic-btn-outline">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Table Card --}}
    <div class="kemetic-card p-0">

        <div class="table-responsive">
            <table class="kemetic-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Video</th>
                        <th>Product</th>
                        <th>Plan</th>
                        <!-- <th>Status</th>
                        <th>Stats</th>
                        <th>Dates</th> -->
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($reels as $reel)
                        <tr>
                            <td>{{ $reel->id }}</td>

                            <td>
                                <div class="fw-600 text-gold">{{ $reel->title }}</div>
                                <small class="text-muted">{{ Str::limit($reel->caption, 50) }}</small>
                            </td>

                            <td>
                                <div class="kemetic-video-preview">
                                    <video controls preload="metadata" style="width:100%;height:100%;object-fit:cover;">
                                        <source src="{{ $reel->video_url }}" type="video/mp4">
                                    </video>
                                </div>
                            </td>

                            <td>
                                @if($reel->product)
                                    <span class="badge badge-gold">{{ $reel->product->title }}</span>
                                @else
                                    <span class="text-muted">No Product</span>
                                @endif
                            </td>

                            <td>
                                @if($reel->plan_code)
                                    <span class="kemetic-badge badge-gold">
                                        {{ $reel->plan_code }}
                                    </span>
                                @else
                                    <span class="text-muted">Free</span>
                                @endif
                            </td>

                            <!-- <td>
                                @if($reel->expires_at)
                                    <span class="kemetic-badge badge-green">Active</span>
                                @else
                                    <span class="kemetic-badge badge-red">Expired</span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex flex-column gap-2">
                                    <span class="kemetic-badge badge-gold">
                                        <i data-feather="star" width="12"></i>
                                        {{ $reel->stars }} Stars
                                    </span>
                                    <span class="kemetic-badge" style="background:rgba(255,255,255,0.1);">
                                        <i data-feather="message-circle" width="12"></i>
                                        {{ $reel->reviews }} Reviews
                                    </span>
                                </div>
                            </td>

                            <td>
                                <div class="small">
                                    <div>Starts:</div>
                                    <div>Expires:</div>
                                </div>
                            </td> -->

                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-10">
                                    <a href="/panel/adreel/{{ $reel->id }}/edit"
                                       class="kemetic-action action-edit"
                                       title="Edit">
                                        <i data-feather="edit" width="16"></i>
                                    </a>

                                    <form action="/panel/adreel/{{ $reel->id }}/delete"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this reel?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="kemetic-action delete">
                                            <i data-feather="trash-2" width="16"></i>
                                        </button>
                                    </form>

                                    <button type="button" 
                                            class="kemetic-action action-preview"
                                            title="Preview"
                                            onclick="previewVideo('{{ $reel->video_url }}')">
                                        <i data-feather="eye" width="16"></i>
                                    </button>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="text-muted">
                                    <i data-feather="film" width="48" height="48"></i>
                                    <h5 class="mt-2">No reels found</h5>
                                    <p>Create your first ad reel to get started</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    {{-- Pagination --}}
    <div class="mt-25">
        {{ $reels->links() }}
    </div>

</section>

{{-- Video Preview Modal --}}
<div class="modal fade" id="videoPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background:#151515;border:1px solid rgba(212,175,55,0.25);">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title text-gold">Video Preview</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <video id="previewVideoPlayer" controls style="width:100%;border-radius:8px;">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts_bottom')
<script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
<script>
    function previewVideo(videoUrl) {
        const videoPlayer = document.getElementById('previewVideoPlayer');
        const source = videoPlayer.querySelector('source') || document.createElement('source');
        
        source.src = videoUrl;
        source.type = 'video/mp4';
        
        if (!videoPlayer.contains(source)) {
            videoPlayer.appendChild(source);
        }
        
        videoPlayer.load();
        $('#videoPreviewModal').modal('show');
    }
    
</script>
@endpush