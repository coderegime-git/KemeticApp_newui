@extends('web.default.layouts.newapp')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <style>
        :root {
            --k-bg: #0b0b0b;
            --k-card: #141414;
            --k-gold: #f2c94c;
            --k-gold-soft: #e6b93d;
            --k-border: rgba(242,201,76,0.25);
            --k-text: #eaeaea;
            --k-muted: #9a9a9a;
            --k-radius: 16px;
        }

        body {
            background: var(--k-bg);
            color: var(--k-text);
            font-family: 'Nunito', sans-serif;
        }

        .panel-section-card {
            background: var(--k-card);
            border-radius: var(--k-radius);
            border: 1px solid var(--k-border);
            box-shadow: 0 4px 20px rgba(242,201,76,0.1);
            padding: 1.25rem;
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.3rem;
            color: var(--k-gold);
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .table.custom-table {
            background: var(--k-card);
            color: var(--k-text);
            border-collapse: separate;
            border-spacing: 0;
        }

        .table.custom-table th,
        .table.custom-table td {
            border-top: 1px solid var(--k-border);
            vertical-align: middle;
        }

        .table th {
            color: var(--k-gold);
            font-weight: 600;
        }

        .table td {
            color: var(--k-text);
        }

        .btn-gray200 {
            background: #1f1f1f;
            color: var(--k-text);
            border-radius: 12px;
            border: none;
        }

        .btn-gray200:hover {
            background: var(--k-gold-soft);
            color: #000;
        }

        .dropdown-menu {
            background: var(--k-card);
            border: 1px solid var(--k-border);
            border-radius: var(--k-radius);
        }

        .dropdown-menu .btn-transparent {
            color: var(--k-text);
        }

        .dropdown-menu .btn-transparent:hover {
            color: var(--k-gold);
        }

        a.text-dark-blue {
            color: var(--k-gold);
            font-weight: 500;
        }

        a.text-dark-blue:hover {
            color: var(--k-gold-soft);
        }

        .text-gray {
            color: var(--k-muted);
        }

        .text-primary {
            color: var(--k-gold);
        }

        .text-warning {
            color: #f2994a;
        }

        input.form-control, select.form-control {
            background: #1f1f1f;
            color: var(--k-text);
            border: 1px solid var(--k-border);
            border-radius: var(--k-radius);
        }

        input.form-control::placeholder {
            color: var(--k-muted);
        }

        .input-group-text {
            background: #1f1f1f;
            border: 1px solid var(--k-border);
            color: var(--k-text);
        }

        .btn-primary {
            background-color: var(--k-gold);
            border: none;
            color: #000;
            border-radius: var(--k-radius);
        }

        .btn-primary:hover {
            background-color: var(--k-gold-soft);
        }

    </style>
@endpush

@section('content')

    <section>
        <h2 class="section-title">{{ trans('panel.filter_comments') }}</h2>

        <div class="panel-section-card">
            <form action="" method="get" class="row">
                <div class="col-12 col-lg-5">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ trans('public.from') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i data-feather="calendar" width="18" height="18" class="text-white"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="from" autocomplete="off" value="{{ request()->get('from') }}" class="form-control {{ !empty(request()->get('from')) ? 'datepicker' : 'datefilter' }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ trans('public.to') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i data-feather="calendar" width="18" height="18" class="text-white"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="to" autocomplete="off" value="{{ request()->get('to') }}" class="form-control {{ !empty(request()->get('to')) ? 'datepicker' : 'datefilter' }}"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-5">
                    <div class="form-group">
                        <label class="input-label">{{ trans('update.product') }}</label>
                        <input type="text" name="product" value="{{ request()->get('product') }}" class="form-control"/>
                    </div>
                </div>
                <div class="col-12 col-lg-2 d-flex align-items-center justify-content-end">
                    <button type="submit" class="btn btn-primary w-100 mt-2">{{ trans('public.show_results') }}</button>
                </div>
            </form>
        </div>
    </section>

    <section class="mt-35">
        <h2 class="section-title">{{ trans('panel.my_comments') }}</h2>

        @if(!empty($comments) and !$comments->isEmpty())

            <div class="panel-section-card">
                <div class="table-responsive">
                    <table class="table custom-table text-center">
                        <thead>
                            <tr>
                                <th class="text-left text-gray">{{ trans('update.product') }}</th>
                                <th class="text-gray text-center">{{ trans('panel.comment') }}</th>
                                <th class="text-gray text-center">{{ trans('public.status') }}</th>
                                <th class="text-gray text-center">{{ trans('public.date') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($comments as $comment)
                                <tr>
                                    <td class="text-left align-middle">
                                        <a class="text-dark-blue" href="{{ $comment->product->getUrl() }}" target="_blank">{{ $comment->product->title }}</a>
                                    </td>
                                    <td class="align-middle">
                                        <button type="button" data-comment-id="{{ $comment->id }}" class="js-view-comment btn btn-gray200 btn-sm">{{ trans('public.view') }}</button>
                                    </td>
                                    <td class="align-middle">
                                        @if($comment->status == 'active')
                                            <span class="text-primary font-weight-500">{{ trans('public.published') }}</span>
                                        @else
                                            <span class="text-warning font-weight-500">{{ trans('public.pending') }}</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-dark-blue font-weight-500">{{ dateTimeFormat($comment->created_at,'j M Y | H:i') }}</td>
                                    <td class="align-middle text-right">
                                        <input type="hidden" id="commentDescription{{ $comment->id }}" value="{{ nl2br($comment->comment) }}">
                                        <div class="btn-group dropdown table-actions">
                                            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                <i data-feather="more-vertical" height="20"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <button type="button" data-comment-id="{{ $comment->id }}" class="js-edit-comment btn-transparent">{{ trans('public.edit') }}</button>
                                                <a href="/panel/webinars/comments/{{ $comment->id }}/delete" class="delete-action btn-transparent d-block mt-10">{{ trans('public.delete') }}</a>
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
                'title' => trans('panel.my_comments_no_result'),
                'hint' =>  nl2br(trans('panel.my_comments_no_result_hint')) ,
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
        var editCommentLang = '{{ trans('panel.edit_comment') }}';
        var saveLang = '{{ trans('public.save') }}';
        var closeLang = '{{ trans('public.close') }}';
        var failedLang = '{{ trans('quiz.failed') }}';
    </script>
    <script src="/assets/default/js/panel/comments.min.js"></script>
@endpush
