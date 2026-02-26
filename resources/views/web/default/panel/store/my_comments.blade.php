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
            font-size: 1.3rem;
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
           FORM CARD
        ========================= */
        .panel-section-card {
            background: linear-gradient(180deg, #121212, #0a0a0a);
            border: 1px solid var(--k-border);
            border-radius: var(--k-radius);
            padding: 25px;
            box-shadow: var(--k-shadow);
            margin-bottom: 2rem;
        }

        /* =========================
           FORM STYLING
        ========================= */
        .form-group label,
        .input-label {
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

        .input-group-text {
            background: #1a1a1a;
            border: 1px solid var(--k-border);
            border-right: none;
            border-top-left-radius: 12px !important;
            border-bottom-left-radius: 12px !important;
            color: var(--k-gold);
        }

        .input-group .form-control {
            border-left: none;
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
        }

        .input-group-text i {
            color: var(--k-gold);
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

        .custom-table thead th.text-gray {
            color: var(--k-gold);
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

        .custom-table tbody td.align-middle {
            vertical-align: middle;
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
            color: #2ecc71 !important;
            font-weight: 600;
            background: rgba(46,204,113,0.15);
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
        }

        .text-warning {
            color: #f1c40f !important;
            font-weight: 600;
            background: rgba(241,196,15,0.15);
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
        }

        /* =========================
           DATE
        ========================= */
        .text-dark-blue.font-weight-500 {
            color: var(--k-gold) !important;
            font-weight: 500;
            font-size: 13px;
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

        .table-actions .btn-transparent:hover {
            background: rgba(212, 175, 55, 0.12);
            color: var(--k-gold);
            text-decoration: none;
        }

        .delete-action {
            color: #e74c3c !important;
        }

        .delete-action:hover {
            background: rgba(231, 76, 60, 0.15) !important;
            color: #ff6b6b !important;
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
        }
    </style>
@endpush

@section('content')
    <div class="kemetic-page">
        <section>
            <h2 class="section-title">{{ trans('panel.filter_comments') }}</h2>

            <div class="panel-section-card">
                <form action="" method="get">
                    <div class="row">
                        <div class="col-12 col-lg-5">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('public.from') }}</label>
                                        <div class="input-group">
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
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('public.to') }}</label>
                                        <div class="input-group">
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
                        </div>
                        
                        <div class="col-12 col-lg-5">
                            <div class="form-group">
                                <label>{{ trans('update.product') }}</label>
                                <input type="text" name="product" value="{{ request()->get('product') }}" class="form-control" placeholder="{{ trans('update.product') }}"/>
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
            <h2 class="section-title">{{ trans('panel.my_comments') }}</h2>

            @if(!empty($comments) and !$comments->isEmpty())
                <div class="panel-section-card">
                    <div class="table-responsive">
                        <table class="custom-table text-center">
                            <thead>
                                <tr>
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
                                        <td class="text-left" data-label="{{ trans('update.product') }}">
                                            <a class="text-dark-blue" href="{{ $comment->product->getUrl() }}" target="_blank">{{ $comment->product->title }}</a>
                                        </td>
                                        <td data-label="{{ trans('panel.comment') }}">
                                            <button type="button" data-comment-id="{{ $comment->id }}" class="js-view-comment btn-gray200 btn-sm">{{ trans('public.view') }}</button>
                                        </td>
                                        <td data-label="{{ trans('public.status') }}">
                                            @if($comment->status == 'active')
                                                <span class="text-primary">{{ trans('public.published') }}</span>
                                            @else
                                                <span class="text-warning">{{ trans('public.pending') }}</span>
                                            @endif
                                        </td>
                                        <td data-label="{{ trans('public.date') }}" class="text-dark-blue font-weight-500">{{ dateTimeFormat($comment->created_at,'j M Y | H:i') }}</td>
                                        <td>
                                            <input type="hidden" id="commentDescription{{ $comment->id }}" value="{{ nl2br($comment->comment) }}">
                                            <div class="btn-group dropdown table-actions">
                                                <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                    <i data-feather="more-vertical" height="20"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <button type="button" data-comment-id="{{ $comment->id }}" class="js-edit-comment btn-transparent">{{ trans('public.edit') }}</button>
                                                    <a href="/panel/webinars/comments/{{ $comment->id }}/delete" class="delete-action btn-transparent">{{ trans('public.delete') }}</a>
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
                        <img src="/assets/default/img/no-results/comment.png" alt="{{ trans('panel.my_comments_no_result') }}">
                        <h2>{{ trans('panel.my_comments_no_result') }}</h2>
                        <p>{{ trans('panel.my_comments_no_result_hint') }}</p>
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
        var editCommentLang = '{{ trans('panel.edit_comment') }}';
        var saveLang = '{{ trans('public.save') }}';
        var closeLang = '{{ trans('public.close') }}';
        var failedLang = '{{ trans('quiz.failed') }}';
    </script>
    <script src="/assets/default/js/panel/comments.min.js"></script>
@endpush