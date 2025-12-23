@extends('web.default.layouts.newapp')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <style>
        :root {
            --k-bg: #0b0b0b;
            --k-card: #141414;
            --k-gold: #f2c94c;
            --k-gold-soft: #e6b93d;
            --k-border: rgba(242, 201, 76, 0.25);
            --k-text: #eaeaea;
            --k-muted: #9a9a9a;
            --k-radius: 16px;
        }

        body, .section-title {
            color: var(--k-text);
        }

        .activities-container {
            background: var(--k-card);
            border-radius: var(--k-radius);
            box-shadow: 0 0 20px rgba(242,201,76,0.1);
            padding: 20px 35px;
        }

        .activities-container .font-30 {
            color: var(--k-gold);
        }

        .activities-container .text-gray {
            color: var(--k-muted);
        }

        .panel-section-card {
            background: var(--k-card);
            border: 1px solid var(--k-border);
            border-radius: var(--k-radius);
            padding: 20px 25px;
        }

        .form-control {
            background: #0f0f0f;
            border: 1px solid var(--k-border);
            border-radius: 12px;
            color: var(--k-text);
        }

        .btn-primary {
            background-color: var(--k-gold);
            border: none;
            color: #000;
        }
        .btn-primary:hover {
            background-color: var(--k-gold-soft);
        }

        .btn-gray200 {
            background-color: #1a1a1a;
            border: 1px solid var(--k-border);
            color: var(--k-text);
        }

        .table.custom-table {
            background: var(--k-card);
            border-radius: var(--k-radius);
            color: var(--k-text);
        }

        .table.custom-table th, .table.custom-table td {
            vertical-align: middle;
            color: var(--k-text);
        }

        .table .user-inline-avatar .avatar {
            background: #1a1a1a;
        }

        .table .dropdown-menu {
            background: #141414;
            border-radius: var(--k-radius);
            border: 1px solid var(--k-border);
        }

        .table .dropdown-menu button {
            color: var(--k-text);
        }
        .table .dropdown-menu button:hover {
            color: var(--k-gold);
        }

        .no-result {
            background: var(--k-card);
            border-radius: var(--k-radius);
            padding: 50px;
            text-align: center;
            color: var(--k-muted);
        }

        .pagination .page-item .page-link {
            background: var(--k-card);
            color: var(--k-text);
            border: 1px solid var(--k-border);
        }

        .pagination .page-item.active .page-link {
            background: var(--k-gold);
            color: #000;
            border-color: var(--k-gold);
        }

        .datepicker, .datefilter {
            background: #0f0f0f;
            border: 1px solid var(--k-border);
            color: var(--k-text);
        }

        input::placeholder, textarea::placeholder {
            color: var(--k-muted);
        }

        h2.section-title {
            color: var(--k-gold);
            font-weight: bold;
        }
    </style>
@endpush

@section('content')
    <section>
        <h2 class="section-title">{{ trans('panel.comments_statistics') }}</h2>

        <div class="activities-container mt-25">
            <div class="row text-center">
                <div class="col-4">
                    <img src="/assets/default/img/activity/39.svg" width="64" height="64" alt="">
                    <strong class="font-30 font-weight-bold mt-5">{{ $comments->count() }}</strong>
                    <div class="text-gray font-weight-500">{{ trans('panel.comments') }}</div>
                </div>

                <div class="col-4">
                    <img src="/assets/default/img/activity/41.svg" width="64" height="64" alt="">
                    <strong class="font-30 font-weight-bold mt-5">{{ $repliedCommentsCount }}</strong>
                    <div class="text-gray font-weight-500">{{ trans('panel.replied') }}</div>
                </div>

                <div class="col-4">
                    <img src="/assets/default/img/activity/40.svg" width="64" height="64" alt="">
                    <strong class="font-30 font-weight-bold mt-5">{{ ($comments->count() - $repliedCommentsCount) }}</strong>
                    <div class="text-gray font-weight-500">{{ trans('panel.not_replied') }}</div>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-25">
        <h2 class="section-title">{{ trans('panel.filter_comments') }}</h2>

        <div class="panel-section-card mt-20">
            <form action="/panel/store/products/comments" method="get" class="row">
                <div class="col-12 col-lg-4">
                    <div class="row">
                        <div class="col-6">
                            <label>{{ trans('public.from') }}</label>
                            <input type="text" name="from" value="{{ request()->get('from') }}" class="form-control datefilter">
                        </div>
                        <div class="col-6">
                            <label>{{ trans('public.to') }}</label>
                            <input type="text" name="to" value="{{ request()->get('to') }}" class="form-control datefilter">
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="row">
                        <div class="col-5">
                            <label>{{ trans('panel.user') }}</label>
                            <input type="text" name="user" value="{{ request()->get('user') }}" class="form-control">
                        </div>
                        <div class="col-7">
                            <label>{{ trans('update.product') }}</label>
                            <input type="text" name="product" value="{{ request()->get('product') }}" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-2 d-flex align-items-center justify-content-end">
                    <button type="submit" class="btn btn-primary w-100 mt-2">{{ trans('public.show_results') }}</button>
                </div>
            </form>
        </div>
    </section>

    <section class="mt-35">
        <h2 class="section-title">{{ trans('update.product_comments_list') }}</h2>

        @if(!empty($comments) and !$comments->isEmpty())
            <div class="panel-section-card mt-20">
                <div class="table-responsive">
                    <table class="table custom-table text-center">
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
                                <td class="text-left">
                                    <div class="user-inline-avatar d-flex align-items-center">
                                        <div class="avatar bg-gray200">
                                            <img src="{{ $comment->user->getAvatar() }}" class="img-cover" alt="">
                                        </div>
                                        <span class="ml-5 text-dark-blue font-weight-500">{{ $comment->user->full_name }}</span>
                                    </div>
                                </td>
                                <td class="text-left">
                                    <a href="{{ $comment->product->getUrl() }}" target="_blank" class="text-dark-blue font-weight-500">{{ $comment->product->title }}</a>
                                </td>
                                <td>
                                    <button type="button" data-comment-id="{{ $comment->id }}" class="js-view-comment btn btn-gray200 btn-sm">{{ trans('public.view') }}</button>
                                </td>
                                <td>
                                    @if(empty($comment->reply_id))
                                        <span class="text-primary font-weight-500">{{ trans('public.open') }}</span>
                                    @else
                                        <span class="text-dark-blue font-weight-500">{{ trans('panel.replied') }}</span>
                                    @endif
                                </td>
                                <td>{{ dateTimeFormat($comment->created_at,'j M Y | H:i') }}</td>
                                <td class="text-right">
                                    <input type="hidden" id="commentDescription{{ $comment->id }}" value="{{ nl2br($comment->comment) }}">
                                    <div class="btn-group dropdown table-actions">
                                        <button class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                            <i data-feather="more-vertical" height="20"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <button type="button" data-comment-id="{{ $comment->id }}" class="js-reply-comment btn-transparent">{{ trans('panel.reply') }}</button>
                                            <button type="button" data-item-id="{{ $comment->product_id }}" data-comment-id="{{ $comment->id }}" class="btn-transparent webinar-actions d-block mt-10 report-comment">{{ trans('panel.report') }}</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            @include(getTemplate() . '.includes.no-result',[
                'file_name' => 'comment.png',
                'title' => trans('panel.comments_no_result'),
                'hint' =>  nl2br(trans('panel.comments_no_result_hint')),
            ])
        @endif
    </section>

    <div class="my-30">
        {{ $comments->appends(request()->input())->links('vendor.pagination.panel') }}
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
