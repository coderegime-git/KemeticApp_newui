@extends('web.default.layouts.newapp')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <style>
        /* =========================
           KEMETIC THEME VARIABLES
        ========================= */
        :root {
            --k-bg: #0b0b0b;
            --k-card: #141414;
            --k-gold: #d4af37;
            --k-gold-soft: rgba(212,175,55,.25);
            --k-border: rgba(212,175,55,.15);
            --k-text: #f5f5f5;
            --k-muted: #9a9a9a;
            --k-radius: 18px;
            --k-shadow: 0 12px 40px rgba(0,0,0,.65);
        }

        /* =========================
           PAGE
        ========================= */
        .kemetic-page {
            background: radial-gradient(circle at top, #1a1a1a, #000);
            min-height: 100vh;
            padding: 25px;
            color: var(--k-text);
        }

        .section-title {
            color: var(--k-gold);
            font-weight: 700;
            letter-spacing: .6px;
            position: relative;
            margin-bottom: 1rem;
        }

        .section-title::after {
            content: "";
            display: block;
            width: 70px;
            height: 1px;
            margin-top: 6px;
            background: linear-gradient(to right, var(--k-gold), transparent);
        }

        /* =========================
           STATS CARDS
        ========================= */
        .activities-container {
            background: linear-gradient(145deg, #161616, #0c0c0c);
            border-radius: var(--k-radius);
            border: 1px solid var(--k-border);
            box-shadow: var(--k-shadow);
            padding: 20px 35px;
        }

        .activities-container .d-flex {
            padding: 1rem;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: var(--k-radius);
            transition: all 0.3s ease;
        }

        .activities-container .d-flex:hover {
            background: rgba(212,175,55,0.05);
            transform: translateY(-2px);
        }

        .activities-container img {
            filter: brightness(1.2);
            margin-bottom: 0.5rem;
            width: 64px;
            height: 64px;
        }

        .activities-container .font-30 {
            color: var(--k-gold) !important;
            font-weight: 700;
            font-size: 30px;
            margin-top: 5px;
        }

        .activities-container .text-gray {
            color: var(--k-muted) !important;
            font-size: 14px;
            font-weight: 500;
        }

        /* =========================
           FORM CARD
        ========================= */
        .panel-section-card {
            background: linear-gradient(180deg, #121212, #0a0a0a);
            border: 1px solid var(--k-border);
            border-radius: var(--k-radius);
            padding: 25px;
            box-shadow: var(--k-shadow);
        }

        /* =========================
           FORM STYLING
        ========================= */
        .form-group label,
        .form-label,
        label {
            color: var(--k-gold);
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
            letter-spacing: 0.3px;
        }

        .form-control,
        .datefilter,
        .datepicker {
            background: #1a1a1a !important;
            color: var(--k-text) !important;
            border: 1px solid var(--k-border) !important;
            border-radius: 12px !important;
            height: 44px;
            padding: 0 15px;
            transition: all 0.25s ease;
            width: 100%;
        }

        .form-control:focus,
        .datefilter:focus,
        .datepicker:focus {
            border-color: var(--k-gold) !important;
            box-shadow: 0 0 8px var(--k-gold-soft) !important;
            outline: none;
        }

        .form-control::placeholder,
        .datefilter::placeholder,
        .datepicker::placeholder {
            color: var(--k-muted);
            opacity: 0.7;
        }

        /* =========================
           BUTTONS
        ========================= */
        .btn-primary {
            background: linear-gradient(135deg, #d4af37, #b8962e) !important;
            color: #000 !important;
            font-weight: 700;
            border-radius: 12px;
            height: 44px;
            border: none;
            transition: all .25s ease;
            padding: 0 20px;
            font-size: 14px;
            letter-spacing: 0.3px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212,175,55,.35);
            color: #000 !important;
        }

        .btn-gray200 {
            background: rgba(212,175,55,0.12);
            border: 1px solid var(--k-border);
            color: var(--k-gold);
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 14px;
            transition: 0.25s ease;
        }

        .btn-gray200:hover {
            background: var(--k-gold);
            color: #000;
        }

        /* =========================
           TABLE
        ========================= */
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0 12px;
        }

        .custom-table thead th {
            background: transparent;
            color: var(--k-gold);
            font-size: 13px;
            font-weight: 600;
            text-align: center;
            border: none;
            padding: 15px;
        }

        .custom-table thead th.text-left {
            text-align: left;
        }

        .custom-table tbody tr {
            background: #0f0f0f;
            border: 1px solid var(--k-border);
            border-radius: 14px;
            transition: all 0.3s ease;
        }

        .custom-table tbody tr:hover {
            background: #151515;
            box-shadow: 0 10px 28px rgba(212, 175, 55, 0.12);
        }

        .custom-table tbody td {
            border: none;
            padding: 16px 18px;
            vertical-align: middle;
            color: var(--k-text);
            text-align: center;
        }

        .custom-table tbody td.text-left {
            text-align: left;
        }

        /* =========================
           USER AVATAR
        ========================= */
        .user-inline-avatar {
            display: flex;
            align-items: center;
        }

        .user-inline-avatar .avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid var(--k-border);
            background: #1a1a1a;
            margin-right: 10px;
        }

        .user-inline-avatar .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-inline-avatar .text-dark-blue {
            color: var(--k-gold) !important;
            font-weight: 500;
        }

        /* =========================
           PRODUCT LINK
        ========================= */
        .custom-table a.text-dark-blue {
            color: var(--k-gold) !important;
            font-weight: 500;
            text-decoration: none;
        }

        .custom-table a.text-dark-blue:hover {
            text-decoration: underline;
            color: #ffd700 !important;
        }

        /* =========================
           STATUS BADGES
        ========================= */
        .text-primary {
            color: #f1c40f !important;
            font-weight: 600;
            background: rgba(241,196,15,0.15);
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
        }

        .text-dark-blue {
            color: #2ecc71 !important;
            font-weight: 600;
            background: rgba(46,204,113,0.15);
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
        }

        /* =========================
           DROPDOWN / ACTIONS
        ========================= */
        .btn-transparent {
            color: var(--k-gold) !important;
            background: none;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            width: 100%;
            text-align: left;
        }

        .btn-transparent:hover {
            color: #ffd700 !important;
        }

        .table-actions .dropdown-menu {
            background: #0f0f0f;
            border: 1px solid var(--k-border);
            border-radius: 12px;
            padding: 6px;
            min-width: 120px;
        }

        .table-actions .dropdown-item,
        .table-actions .btn-transparent {
            color: var(--k-text);
            font-size: 13px;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.2s ease;
            display: block;
            width: 100%;
            border: none;
            background: none;
            cursor: pointer;
        }

        .table-actions .dropdown-item:hover,
        .table-actions .btn-transparent:hover {
            background: rgba(212, 175, 55, 0.12);
            color: var(--k-gold);
            text-decoration: none;
        }

        /* =========================
           NO RESULT
        ========================= */
        .no-result {
            background: #0f0f0f;
            border: 1px dashed var(--k-border);
            border-radius: 18px;
            padding: 60px 20px;
            text-align: center;
            margin-top: 20px;
        }

        .no-result img {
            filter: brightness(0.9) sepia(0.3);
            opacity: 0.9;
            max-width: 120px;
        }

        .no-result .no-result-content h2 {
            color: var(--k-gold);
            font-size: 20px;
            margin: 20px 0 10px;
        }

        .no-result .no-result-content p {
            color: var(--k-muted);
            font-size: 14px;
            max-width: 400px;
            margin: 0 auto;
        }

        /* =========================
           PAGINATION
        ========================= */
        .pagination .page-link {
            background: #111;
            color: var(--k-gold);
            border: 1px solid var(--k-border);
            border-radius: 10px;
            margin: 0 3px;
        }

        .pagination .page-item.active .page-link {
            background: var(--k-gold);
            border-color: var(--k-gold);
            color: #000;
        }

        .pagination .page-item.disabled .page-link {
            background: #1a1a1a;
            color: var(--k-muted);
            border-color: #2a2a2a;
        }

        /* =========================
           DATE
        ========================= */
        .custom-table td:last-child {
            font-size: 13px;
            color: var(--k-text);
        }

        /* =========================
           RESPONSIVE
        ========================= */
        @media (max-width: 768px) {
            .custom-table thead {
                display: none;
            }

            .custom-table tbody tr {
                display: block;
                margin-bottom: 15px;
            }

            .custom-table tbody td {
                display: block;
                text-align: left;
                padding: 12px;
                position: relative;
            }

            .custom-table tbody td:before {
                content: attr(data-label);
                float: left;
                font-weight: 600;
                color: var(--k-gold);
                margin-right: 10px;
                min-width: 100px;
            }

            .user-inline-avatar {
                justify-content: flex-start;
            }
        }
    </style>
@endpush

@section('content')
    <div class="kemetic-page">
        <section>
            <h2 class="section-title">{{ trans('panel.comments_statistics') }}</h2>

            <div class="activities-container mt-25">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="d-flex flex-column align-items-center">
                            <img src="/assets/default/img/activity/39.svg" width="64" height="64" alt="">
                            <strong class="font-30 mt-2">{{ $comments->count() }}</strong>
                            <span class="text-gray">{{ trans('panel.comments') }}</span>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="d-flex flex-column align-items-center">
                            <img src="/assets/default/img/activity/41.svg" width="64" height="64" alt="">
                            <strong class="font-30 mt-2">{{ $repliedCommentsCount }}</strong>
                            <span class="text-gray">{{ trans('panel.replied') }}</span>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="d-flex flex-column align-items-center">
                            <img src="/assets/default/img/activity/40.svg" width="64" height="64" alt="">
                            <strong class="font-30 mt-2">{{ ($comments->count() - $repliedCommentsCount) }}</strong>
                            <span class="text-gray">{{ trans('panel.not_replied') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-35">
            <h2 class="section-title">{{ trans('panel.filter_comments') }}</h2>

            <div class="panel-section-card mt-20">
                <form action="/panel/store/products/comments" method="get">
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>{{ trans('public.from') }}</label>
                                        <input type="date"
                                            class="form-control kemetic-input text-center"
                                            name="from"
                                            value="{{ request()->get('from') }}">
                                            <!-- <span class="input-group-text kemetic-icon bg-gold text-black">
                                                <i data-feather="calendar" width="18" height="18"></i>
                                            </span> -->

                                            <!-- <input type="text" name="from" autocomplete="off"
                                                class="form-control kemetic-input @if(request()->get('from')) datepicker @else datefilter @endif"
                                                value="{{ request()->get('from','') }}"> -->
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>{{ trans('public.to') }}</label>
                                        <input type="date"
                                            class="form-control kemetic-input text-center"
                                            name="to"
                                            value="{{ request()->get('to') }}">

                                    <!-- <span class="input-group-text kemetic-icon bg-gold text-black">
                                        <i data-feather="calendar" width="18" height="18"></i>
                                    </span> -->
                                    
                                    <!-- <input type="text" name="to" autocomplete="off"
                                        class="form-control kemetic-input @if(request()->get('to')) datepicker @else datefilter @endif"
                                        value="{{ request()->get('to','') }}"> -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="row">
                                <div class="col-5">
                                    <div class="form-group">
                                        <label>{{ trans('panel.user') }}</label>
                                        <input type="text" name="user" value="{{ request()->get('user') }}" class="form-control" placeholder="{{ trans('panel.user') }}">
                                    </div>
                                </div>
                                <div class="col-7">
                                    <div class="form-group">
                                        <label>{{ trans('update.product') }}</label>
                                        <input type="text" name="product" value="{{ request()->get('product') }}" class="form-control" placeholder="{{ trans('update.product') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-2 d-flex align-items-end">
                            <div class="form-group w-100">
                                <label class="d-none d-lg-block">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">{{ trans('public.show_results') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <section class="mt-35">
            <h2 class="section-title">{{ trans('update.product_comments_list') }}</h2>

            @if(!empty($comments) and !$comments->isEmpty())
                <div class="panel-section-card mt-20">
                    <div class="table-responsive">
                        <table class="custom-table text-center">
                            <thead>
                                <tr>
                                    <th class="text-left">{{ trans('panel.user') }}</th>
                                    <th class="text-left">{{ trans('update.product') }}</th>
                                    <th>{{ trans('panel.comment') }}</th>
                                    <th>{{ trans('public.status') }}</th>
                                    <th>{{ trans('public.date') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($comments as $comment)
                                    <tr>
                                        <td class="text-left" data-label="{{ trans('panel.user') }}">
                                            <div class="user-inline-avatar">
                                                <div class="avatar">
                                                    <img src="{{ $comment->user->getAvatar() }}" class="img-cover" alt="">
                                                </div>
                                                <span class="text-dark-blue">{{ $comment->user->full_name }}</span>
                                            </div>
                                        </td>
                                        <td class="text-left" data-label="{{ trans('update.product') }}">
                                            <a href="{{ $comment->product->getUrl() }}" target="_blank" class="text-dark-blue">
                                                {{ $comment->product->title }}
                                            </a>
                                        </td>
                                        <td data-label="{{ trans('panel.comment') }}">
                                            <button type="button" data-comment-id="{{ $comment->id }}" class="js-view-comment btn-gray200 btn-sm">{{ trans('public.view') }}</button>
                                        </td>
                                        <td data-label="{{ trans('public.status') }}">
                                            @if(empty($comment->reply_id))
                                                <span class="text-primary">{{ trans('public.open') }}</span>
                                            @else
                                                <span class="text-dark-blue status-success">{{ trans('panel.replied') }}</span>
                                            @endif
                                        </td>
                                        <td data-label="{{ trans('public.date') }}">{{ dateTimeFormat($comment->created_at,'j M Y | H:i') }}</td>
                                        <td>
                                            <input type="hidden" id="commentDescription{{ $comment->id }}" value="{{ nl2br($comment->comment) }}">
                                            <div class="btn-group dropdown table-actions">
                                                <button class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                    <i data-feather="more-vertical" height="20"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <button type="button" data-comment-id="{{ $comment->id }}" class="js-reply-comment btn-transparent">{{ trans('panel.reply') }}</button>
                                                    <button type="button" data-item-id="{{ $comment->product_id }}" data-comment-id="{{ $comment->id }}" class="btn-transparent report-comment">{{ trans('panel.report') }}</button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($comments->hasPages())
                        <div class="my-30">
                            {{ $comments->appends(request()->input())->links('vendor.pagination.panel') }}
                        </div>
                    @endif
                </div>
            @else
                <div class="no-result">
                    <div class="no-result-content">
                        <img src="/assets/default/img/no-results/comment.png" alt="{{ trans('panel.comments_no_result') }}">
                        <h2>{{ trans('panel.comments_no_result') }}</h2>
                        <p>{{ trans('panel.comments_no_result_hint') }}</p>
                    </div>
                </div>
            @endif
        </section>
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script>
        var commentLang = '{{ trans('panel.comment') }}';
        var replyToCommentLang = '{{ trans('panel.reply_to_the_comment') }}';
        var saveLang = '{{ trans('public.save') }}';
        var closeLang = '{{ trans('public.close') }}';
        var reportLang = '{{ trans('panel.report') }}';
        var reportSuccessLang = '{{ trans('panel.report_success') }}';
        var messageToReviewerLang = '{{ trans('public.message_to_reviewer') }}';
    </script>
    <script src="/assets/default/js/panel/comments.min.js"></script>
@endpush