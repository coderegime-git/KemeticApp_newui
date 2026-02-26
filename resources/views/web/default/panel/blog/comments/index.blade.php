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
    border: 1px solid rgba(242,201,76,.35) !important;
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
    border: 1px solid rgba(242,201,76,.35) !important;
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
    padding: 8px 16px;
    font-size: 13px;
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
    padding-bottom: 10px;
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
    padding:14px;
    text-align:center;
    vertical-align:middle;
}
.kemetic-table td.text-left { 
    text-align:left; 
}

/* USER AVATAR */
.user-avatar-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}
.user-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid rgba(242,201,76,0.3);
    background: #1a1a1a;
}
.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.user-info {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}
.user-name {
    color: #fff;
    font-weight: 600;
    font-size: 14px;
}
.user-email {
    color: #888;
    font-size: 11px;
}

/* POST LINK */
.post-link {
    color: #fff;
    text-decoration: none;
    font-weight: 500;
    transition: 0.3s ease;
}
.post-link:hover {
    color: #F2C94C;
}

/* STATUS BADGES */
.status-badge {
    padding:4px 10px;
    border-radius:12px;
    font-size:12px;
    font-weight: 500;
    display: inline-block;
}
.status-badge.active {
    background: #1f3d2b; 
    color: #2ecc71;
}
.status-badge.pending {
    background: #3d2e1f; 
    color: #f39c12;
}

/* VIEW BUTTON */
.view-btn {
    background: transparent;
    border: 1px solid rgba(242,201,76,0.4);
    color: #F2C94C;
    border-radius: 10px;
    padding: 6px 16px;
    font-size: 13px;
    transition: 0.3s ease;
}
.view-btn:hover {
    background: rgba(242,201,76,0.1);
    border-color: #F2C94C;
}

/* COMMENT MODAL */
.modal-content {
    background: #121212;
    border: 1px solid #262626;
    border-radius: 18px;
}
.modal-header {
    border-bottom: 1px solid #262626;
}
.modal-title {
    color: #F2C94C;
    font-weight: 700;
}
.modal-body {
    color: #eaeaea;
}
.modal-footer {
    border-top: 1px solid #262626;
}
.modal-footer .btn {
    background: #1a1a1a;
    border: 1px solid #262626;
    color: #fff;
    border-radius: 12px;
}
.modal-footer .btn:hover {
    background: #2a2a2a;
}

/* NO RESULT */
.no-result-card {
    background: linear-gradient(180deg,#121212,#0a0a0a);
    border:1px solid #262626;
    border-radius:18px;
    padding:40px;
    text-align: center;
}
.no-result-card img {
    opacity: 0.7;
    margin-bottom: 20px;
}
.no-result-card h3 {
    color: #F2C94C;
    font-size: 20px;
    margin-bottom: 10px;
}
.no-result-card p {
    color: #888;
    font-size: 14px;
}

/* DATE STYLE */
.comment-date {
    color: #888;
    font-size: 13px;
}
</style>

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')

    {{-- Statistics --}}
    <section class="kemetic-stat-section">
        <h2 class="kemetic-title">{{ trans('panel.comments_statistics') }}</h2>

        <div class="kemetic-stat-card">
            <div class="row text-center">
                <div class="col-4">
                    <div class="kemetic-stat-item">
                        <div class="kemetic-stat-icon">
                            <img src="/assets/default/img/activity/44.svg" alt="">
                        </div>
                        <div class="kemetic-stat-value">{{ $totalComments ?? 0 }}</div>
                        <div class="kemetic-stat-label">{{ trans('panel.total_comments') }}</div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="kemetic-stat-item">
                        <div class="kemetic-stat-icon">
                            <img src="/assets/default/img/activity/45.svg" alt="">
                        </div>
                        <div class="kemetic-stat-value">{{ $activeComments ?? 0 }}</div>
                        <div class="kemetic-stat-label">{{ trans('public.active') }}</div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="kemetic-stat-item">
                        <div class="kemetic-stat-icon">
                            <img src="/assets/default/img/activity/46.svg" alt="">
                        </div>
                        <div class="kemetic-stat-value">{{ $pendingComments ?? 0 }}</div>
                        <div class="kemetic-stat-label">{{ trans('public.pending') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Filters --}}
    <section class="kemetic-filter-section mt-30">
        <h2 class="kemetic-title">{{ trans('panel.filter_comments') }}</h2>

        <div class="kemetic-filter-card mt-20">
            <form action="/panel/blog/comments" method="get">
                <div class="row g-3">

                    {{-- Date range --}}
                    <div class="col-12 col-lg-6">
                        <div class="row g-2">
                            <div class="col-12 col-md-6">
                                <label class="kemetic-label">{{ trans('public.from') }}</label>
                                <div class="kemetic-input-group">
                                    <input type="date" name="from" autocomplete="off"
                                        class="kemetic-input"
                                        value="{{ request()->get('from','') }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="kemetic-label">{{ trans('public.to') }}</label>
                                <div class="kemetic-input-group">
                                    <input type="date" name="to" autocomplete="off"
                                        class="kemetic-input"
                                        value="{{ request()->get('to','') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Post filter --}}
                    <div class="col-12 col-lg-4">
                        <label class="kemetic-label">{{ trans('admin/main.post') }}</label>
                        <select name="blog_id" class="kemetic-select select2" data-placeholder="{{ trans('update.select_post') }}" style="width: 100%;">
                            <option value="">{{ trans('public.all') }}</option>
                            @foreach($posts as $post)
                                <option value="{{ $post->id }}"
                                    {{ !empty($selectedPost) && $selectedPost->id == $post->id ? 'selected' : '' }}>
                                    {{ $post->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Submit --}}
                    <div class="col-12 col-lg-2 d-flex align-items-end">
                        <button type="submit" class="kemetic-btn w-100">
                            {{ trans('public.show_results') }}
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </section>

    {{-- Comments List --}}
    <section class="kemetic-section mt-40">
        <h2 class="kemetic-title">{{ trans('update.blog_comments_list') }}</h2>

        @if(!empty($comments) && !$comments->isEmpty())

            <div class="kemetic-table-card mt-25">
                <div class="table-responsive">
                    <table class="kemetic-table">
                        <thead>
                            <tr>
                                <th class="text-left">{{ trans('panel.user') }}</th>
                                <th class="text-left">{{ trans('admin/main.post') }}</th>
                                <th>{{ trans('panel.comment') }}</th>
                                <th>{{ trans('public.status') }}</th>
                                <th>{{ trans('public.date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($comments as $comment)
                                <tr>
                                    <td class="text-left">
                                        <div class="user-avatar-cell">
                                            <div class="user-avatar">
                                                <img src="{{ $comment->user->getAvatar() }}" alt="">
                                            </div>
                                            <div class="user-info">
                                                <span class="user-name">{{ $comment->user->full_name }}</span>
                                                <span class="user-email">{{ $comment->user->email }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-left">
                                        <a href="{{ $comment->blog->getUrl() }}" 
                                           target="_blank" 
                                           class="post-link">
                                            {{ $comment->blog->title }}
                                        </a>
                                    </td>

                                    <td>
                                        <input type="hidden" 
                                               id="commentDescription{{ $comment->id }}" 
                                               value="{{ nl2br($comment->comment) }}">
                                        <button class="view-btn js-view-comment" 
                                                data-comment-id="{{ $comment->id }}">
                                            {{ trans('public.view') }}
                                        </button>
                                    </td>

                                    <td>
                                        @if($comment->status == 'active')
                                            <span class="status-badge active">{{ trans('public.active') }}</span>
                                        @else
                                            <span class="status-badge pending">{{ trans('public.pending') }}</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="comment-date">{{ dateTimeFormat($comment->created_at,'j M Y | H:i') }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        @else
            <div class="no-result-card mt-25">
                <img src="/assets/default/img/comment.png" alt="" width="120">
                <h3>{{ trans('panel.comments_no_result') }}</h3>
                <p>{{ trans('panel.comments_no_result_hint') }}</p>
            </div>
        @endif
    </section>

    {{-- Pagination --}}
    @if(!empty($comments) && !$comments->isEmpty())
        <div class="my-30">
            {{ $comments->appends(request()->input())->links('vendor.pagination.panel') }}
        </div>
    @endif

    {{-- Comment View Modal --}}
    <div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('panel.comment') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: #0e0d0d;">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="commentModalBody" style="color: #0e0d0d;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">{{ trans('public.close') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                width: '100%',
                theme: 'default'
            });
            

            // View comment
            var commentLang = '{{ trans('panel.comment') }}';
            
            $('.js-view-comment').on('click', function() {
                var commentId = $(this).data('comment-id');
                var commentText = $('#commentDescription' + commentId).val();
                $('#commentModalBody').html(commentText);
                $('#commentModal').modal('show');
            });
        });
    </script>

    <script src="/assets/default/js/panel/blog_comments.min.js"></script>
@endpush