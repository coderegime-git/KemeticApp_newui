@extends('web.default.layouts.newapp')

@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
<link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
<!-- Plyr CSS -->
<link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />

<style>
/* =========================
   KEMETIC APP DESIGN
========================= */
/* ===============================
   KEMETIC REELS – BLACK GOLD
================================ */

.kemetic-section {
    color: #fff;
}

/* Title */
.kemetic-title {
    font-size: 22px;
    font-weight: 600;
    color: #d4af37;
}

/* Card */
.kemetic-card {
    background: linear-gradient(145deg, #0b0b0b, #151515);
    border-radius: 16px;
    border: 1px solid rgba(212, 175, 55, 0.25);
    box-shadow: 0 15px 45px rgba(0, 0, 0, 0.65);
    overflow: hidden;
}

/* Button */
.kemetic-btn-sm {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, #d4af37, #b8962e);
    color: #000;
    padding: 8px 14px;
    border-radius: 10px;
    font-weight: 600;
    transition: 0.3s;
}

.kemetic-btn-sm:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 30px rgba(212, 175, 55, 0.45);
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

/* Video */
.kemetic-video-box {
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid rgba(212, 175, 55, 0.35);
    background: #000;
}

/* Badges */
.kemetic-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.kemetic-badge.gold {
    background: rgba(212, 175, 55, 0.18);
    color: #d4af37;
}

.kemetic-badge.silver {
    background: rgba(255, 255, 255, 0.12);
    color: #e0e0e0;
}

/* Actions */
.kemetic-action {
    width: 34px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: 0.25s;
}

.kemetic-action.edit {
    background: rgba(212, 175, 55, 0.2);
    color: #d4af37;
}

.kemetic-action.delete {
    background: rgba(255, 80, 80, 0.2);
    color: #ff6b6b;
}

.kemetic-action:hover {
    transform: scale(1.08);
}

/* Pagination */
.kemetic-pagination .pagination {
    justify-content: center;
}

</style>
@endpush

@section('content')
<section class="kemetic-section mt-25">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-20">
        <h2 class="section-title kemetic-title">Reels</h2>

        <a href="/reels" class="kemetic-btn-sm">
            <i data-feather="plus" width="16"></i>
            <span class="ml-5">Create Reel</span>
        </a>
    </div>

    {{-- Table Card --}}
    <div class="kemetic-card p-0">

        <div class="table-responsive">
            <table class="kemetic-table">
                <thead>
                    <tr>
                        <th>Sl No</th>
                        <th>Title</th>
                        <th>Video</th>
                        <th>Likes</th>
                        <th>Comments</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($reels as $reel)
                        <tr>
                            <td>{{ $reels->firstItem() + $loop->index }}</td>

                            <td class="fw-600 text-gold">
                                {{ $reel->title }}
                            </td>

                            <td width="260">
                                <div class="kemetic-video-box">
                                    <video class="plyr reel-video" controls preload="metadata"
                                           poster="{{ $reel->thumbnail_url }}">
                                        <source src="{{ $reel->video_url }}" type="video/mp4"/>
                                        {{ trans('public.browser_not_support_video') }}
                                    </video>
                                </div>
                            </td>

                            <td>
                                <span class="kemetic-badge gold">
                                    <i data-feather="heart" width="14"></i>
                                    {{ $reel->likes_count }}
                                </span>
                            </td>

                            <td>
                                <span class="kemetic-badge silver">
                                    <i data-feather="message-circle" width="14"></i>
                                    {{ $reel->comments_count }}
                                </span>
                            </td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-10">

                                    <a href="{{ route('reels.edit', $reel->id) }}"
                                       class="kemetic-action edit">
                                        <i data-feather="edit" width="16"></i>
                                    </a>

                                    <form action="{{ route('reels.destroy', $reel->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this reel?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="kemetic-action delete">
                                            <i data-feather="trash-2" width="16"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    {{-- Pagination --}}
    <div class="kemetic-pagination mt-25">
        {{ $reels->links() }}
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
