@extends('web.default.layouts.newapp')

<style>
  /* KEMETIC STATS */
.kemetic-stat-section {
    margin-top: 25px;
}

.kemetic-title {
    font-size: 22px;
    font-weight: 700;
    color: #F2C94C;
    margin-bottom: 18px;
}

/* CARD */
.kemetic-stat-card {
    background: linear-gradient(180deg, #121212, #0b0b0b);
    border: 1px solid #262626;
    border-radius: 18px;
    padding: 26px 18px;
}

/* ITEM */
.kemetic-stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
}

/* ICON */
.kemetic-stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: rgba(242, 201, 76, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
}
.kemetic-stat-icon img {
    width: 28px;
    filter: invert(0.9);
}

/* VALUE */
.kemetic-stat-value {
    font-size: 30px;
    font-weight: 700;
    color: #F2C94C;
}

/* LABEL */
.kemetic-stat-label {
    font-size: 14px;
    color: #9a9a9a;
}

/* MOBILE */
@media (max-width: 768px) {
    .kemetic-stat-card {
        padding: 20px 12px;
    }
    .kemetic-stat-value {
        font-size: 24px;
    }
}

/* KEMETIC FILTER */
.kemetic-filter-section {
    color: #eaeaea;
}

.kemetic-title {
    font-size: 22px;
    font-weight: 700;
    color: #F2C94C;
}

/* CARD */
.kemetic-filter-card {
    background: linear-gradient(180deg, #121212, #0a0a0a);
    border: 1px solid #262626;
    border-radius: 18px;
    padding: 26px 22px;
}

/* LABEL */
.kemetic-label {
    font-size: 13px;
    color: #b5b5b5;
    margin-bottom: 6px;
    display: block;
}

/* INPUT GROUP */
.kemetic-input-group {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #0f0f0f;
    border: 1px solid #2a2a2a;
    border-radius: 12px;
    padding: 10px 12px;
}

.kemetic-input-group i {
    color: #F2C94C;
}

/* INPUT */
.kemetic-input {
    width: 100%;
    background: transparent;
    border: none;
    color: #fff;
    outline: none;
    font-size: 14px;
}

.kemetic-input::placeholder {
    color: #666;
}

/* SELECT2 */
.select2-container--default .select2-selection--single {
    background: #0f0f0f !important;
    border: 1px solid #2a2a2a !important;
    border-radius: 12px !important;
    height: 44px !important;
    display: flex;
    align-items: center;
}
.select2-selection__rendered {
    color: #fff !important;
    line-height: 44px !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 42px !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: #F2C94C transparent transparent transparent !important;
}
.select2-dropdown {
    background: #0f0f0f !important;
    border: 1px solid #2a2a2a !important;
    border-radius: 12px !important;
}
.select2-results__option {
    color: #e0e0e0 !important;
    padding: 10px 14px !important;
}
.select2-results__option--highlighted {
    background: rgba(242,201,76,.15) !important;
    color: #fff !important;
}
.select2-results__option[aria-selected=true] {
    background: rgba(242,201,76,.25) !important;
}
.select2-search--dropdown .select2-search__field {
    background: #0d0d0d !important;
    border: 1px solid #2a2a2a !important;
    color: #fff !important;
    border-radius: 8px !important;
}

/* BUTTON */
.kemetic-btn {
    background: linear-gradient(135deg, #F2C94C, #d4af37);
    border: none;
    border-radius: 14px;
    padding: 12px;
    font-weight: 700;
    color: #000;
    transition: 0.3s ease;
}
.kemetic-btn:hover {
    background: linear-gradient(135deg, #d4af37, #F2C94C);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(242,201,76,0.4);
}
.kemetic-btn-sm {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #F2C94C, #d4af37);
    color: #000;
    padding: 8px 16px;
    border-radius: 12px;
    font-weight: 600;
    transition: 0.3s ease;
}
.kemetic-btn-sm:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(242,201,76,0.4);
}

/* TABLE CARD */
.kemetic-table-card {
    background: linear-gradient(180deg,#121212,#0a0a0a);
    border:1px solid #262626;
    border-radius:18px;
    padding:20px;
}

/* TABLE */
.kemetic-table {
    width:100%;
    border-collapse:separate;
    border-spacing:0 10px;
}
.kemetic-table thead th {
    color:#aaa; 
    font-size:13px;
    font-weight:600; 
    text-align:center;
    padding: 14px;
    background: rgba(242,201,76,0.05);
    border-bottom: 1px solid rgba(242,201,76,0.25);
}
.kemetic-table thead th.text-left {
    text-align: left;
}
.kemetic-table tbody tr {
    background:#0f0f0f;
    border:1px solid #262626;
    transition: 0.3s ease;
}
.kemetic-table tbody tr:hover {
    background: #1a1a1a;
    border-color: rgba(242,201,76,0.3);
}
.kemetic-table td {
    padding:16px 14px;
    text-align:center;
    vertical-align:middle;
    border-bottom: 1px solid rgba(255,255,255,0.03);
}
.kemetic-table td.text-left { 
    text-align:left; 
}
.kemetic-table td.text-right {
    text-align: right;
}

/* SERIAL NUMBER */
.serial-number {
    color: #888;
    font-weight: 500;
    font-size: 14px;
}

/* TITLE */
.reel-title {
    color: #F2C94C;
    font-weight: 600;
    font-size: 16px;
}

/* VIDEO BOX */
.kemetic-video-box {
    border-radius: 12px;
    overflow: hidden;
    border: 2px solid rgba(242,201,76,0.3);
    background: #000;
    width: 240px;
    margin: 0 auto;
}
.plyr {
    border-radius: 10px;
}
.plyr--video {
    background: #000;
}
.plyr__control--overlaid {
    background: rgba(242,201,76,0.9);
}
.plyr__control--overlaid:hover {
    background: #F2C94C;
}
.plyr--video .plyr__control:hover {
    background: #F2C94C;
}

/* BADGES */
.kemetic-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}
.kemetic-badge.gold {
    background: rgba(242,201,76,0.15);
    color: #F2C94C;
}
.kemetic-badge.silver {
    background: rgba(255,255,255,0.1);
    color: #e0e0e0;
}
.kemetic-badge i {
    width: 14px;
    height: 14px;
}

/* ACTIONS */
.kemetic-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}
.kemetic-action {
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    transition: 0.25s ease;
}
.kemetic-action.edit {
    background: rgba(242,201,76,0.15);
    color: #F2C94C;
}
.kemetic-action.edit:hover {
    background: rgba(242,201,76,0.25);
    transform: scale(1.1);
}
.kemetic-action.delete {
    background: rgba(231,76,60,0.15);
    color: #e74c3c;
}
.kemetic-action.delete:hover {
    background: rgba(231,76,60,0.25);
    transform: scale(1.1);
}
.kemetic-action i {
    width: 18px;
    height: 18px;
}

/* PAGINATION */
.kemetic-pagination {
    margin-top: 30px;
}
.kemetic-pagination .pagination {
    justify-content: center;
    gap: 5px;
}
.kemetic-pagination .page-item .page-link {
    background: #0f0f0f;
    border: 1px solid #262626;
    color: #888;
    border-radius: 10px;
    padding: 8px 14px;
    transition: 0.3s ease;
}
.kemetic-pagination .page-item.active .page-link {
    background: #F2C94C;
    border-color: #F2C94C;
    color: #000;
}
.kemetic-pagination .page-item .page-link:hover {
    background: rgba(242,201,76,0.15);
    border-color: #F2C94C;
    color: #F2C94C;
}

/* NO RESULT */
.no-result-card {
    background: linear-gradient(180deg,#121212,#0a0a0a);
    border:1px solid #262626;
    border-radius:18px;
    padding:60px 40px;
    text-align: center;
}
.no-result-card img {
    opacity: 0.7;
    margin-bottom: 20px;
    width: 120px;
}
.no-result-card h3 {
    color: #F2C94C;
    font-size: 20px;
    margin-bottom: 10px;
}
.no-result-card p {
    color: #888;
    font-size: 14px;
    max-width: 400px;
    margin: 0 auto;
}

/* TEXT COLORS */
.text-gold {
    color: #F2C94C !important;
}
.text-gray {
    color: #888 !important;
}
</style>

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <!-- Plyr CSS -->
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
@endpush

@section('content')

    {{-- Header --}}
    <section class="kemetic-section mt-30">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-20">
            <h2 class="kemetic-title">Reels</h2>

            <a href="/reels" class="kemetic-btn-sm mt-15 mt-md-0">
                <i data-feather="plus" width="16"></i>
                <span>Create Reel</span>
            </a>
        </div>

        {{-- Reels Table --}}
        @if($reels->count() > 0)

            <div class="kemetic-table-card mt-20">
                <div class="table-responsive">
                    <table class="kemetic-table">
                        <thead>
                            <tr>
                                <th>Sl No</th>
                                <th class="text-left">Title</th>
                                <th>Video</th>
                                <th>Likes</th>
                                <th>Comments</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reels as $reel)
                                <tr>
                                    <td>
                                        <span class="serial-number">{{ $reels->firstItem() + $loop->index }}</span>
                                    </td>

                                    <td class="text-left">
                                        <span class="reel-title">{{ $reel->title }}</span>
                                    </td>

                                    <td>
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
                                            <i data-feather="heart"></i>
                                            {{ $reel->likes_count }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="kemetic-badge silver">
                                            <i data-feather="message-circle"></i>
                                            {{ $reel->comments_count }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="kemetic-actions">
                                            <a href="{{ route('reels.edit', $reel->id) }}"
                                               class="kemetic-action edit">
                                                <i data-feather="edit"></i>
                                            </a>

                                            <form action="{{ route('reels.destroy', $reel->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Are you sure you want to delete this reel?');"
                                                  style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="kemetic-action delete" style="margin-top: 15px;">
                                                    <i data-feather="trash-2"></i>
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
            <div class="my-30" style="padding: 10px;">
                {{ $reels->appends(request()->input())->links('vendor.pagination.panel') }}
            </div>

        @else
            <div class="no-result-card">
                <!-- <img src="/assets/default/img/reel.png" alt=""> -->
                <h3>No Reels Found</h3>
                <p>Start creating your first reel to engage with your audience.</p>
            </div>
        @endif
    </section>

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize Select2 if needed
            $('.select2').select2({
                width: '100%',
                theme: 'default'
            });

            // Initialize Plyr video players
            const players = Plyr.setup('.plyr', {
                controls: ['play', 'progress', 'current-time', 'mute', 'volume', 'fullscreen'],
                settings: ['quality', 'speed'],
                quality: { default: 576, options: [4320, 2880, 2160, 1440, 1080, 720, 576, 480, 360, 240] }
            });
        });
    </script>
@endpush