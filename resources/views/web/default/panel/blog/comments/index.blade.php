@extends('web.default.layouts.newapp')

@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
<link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">

<style>
/* ===============================
   KEMETIC BLOG COMMENTS
================================ */
.kemetic-card {
    background: linear-gradient(180deg, #0b0b0b, #121212);
    border: 1px solid rgba(242, 201, 76, 0.25);
    border-radius: 18px;
}

.kemetic-section-title {
    color: #f2c94c;
    font-weight: 600;
}

/* Inputs */
.kemetic-card .input-label {
    color: #c9b26d;
    font-size: 13px;
}

.kemetic-card .form-control,
.kemetic-card .select2-selection {
    background: #0e0e0e !important;
    border: 1px solid rgba(242, 201, 76, 0.3);
    color: #fff;
    border-radius: 12px;
}

.kemetic-card .form-control:focus {
    border-color: #f2c94c;
    box-shadow: 0 0 0 2px rgba(242, 201, 76, 0.15);
}

/* Input group */
.kemetic-card .input-group-text {
    background: #151515;
    border: 1px solid rgba(242, 201, 76, 0.3);
}

/* Button */
.kemetic-btn {
    background: linear-gradient(135deg, #f2c94c, #caa63c);
    color: #000;
    font-weight: 600;
    border-radius: 12px;
}

/* Table */
.kemetic-table {
    color: #ddd;
}

.kemetic-table thead th {
    color: #f2c94c;
    border-bottom: 1px solid rgba(242, 201, 76, 0.25);
    font-size: 13px;
}

.kemetic-table tbody tr {
    border-bottom: 1px solid rgba(255,255,255,0.05);
}

.kemetic-table tbody tr:hover {
    background: rgba(242, 201, 76, 0.05);
}

/* Avatar */
.kemetic-avatar {
    border-radius: 50%;
    border: 1px solid rgba(242, 201, 76, 0.3);
}

/* Status */
.kemetic-active {
    color: #6bffb3;
    font-weight: 600;
}

.kemetic-pending {
    color: #f2c94c;
    font-weight: 600;
}

/* View button */
.kemetic-view-btn {
    background: transparent;
    border: 1px solid rgba(242, 201, 76, 0.4);
    color: #f2c94c;
    border-radius: 10px;
}
</style>
@endpush

@section('content')

{{-- FILTER --}}
<section class="mt-25">
    <h2 class="section-title kemetic-section-title">
        {{ trans('panel.filter_comments') }}
    </h2>

    <div class="kemetic-card py-25 px-25 mt-20" style="padding:10px;">
        <form action="/panel/blog/comments" method="get" class="row">

            {{-- DATE RANGE --}}
            <div class="col-12 col-lg-6">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="input-label">{{ trans('public.from') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i data-feather="calendar" width="16"></i>
                                    </span>
                                </div>
                                <input type="text" name="from"
                                       value="{{ request()->get('from') }}"
                                       class="form-control {{ request()->get('from') ? 'datepicker' : 'datefilter' }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="input-label">{{ trans('public.to') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i data-feather="calendar" width="16"></i>
                                    </span>
                                </div>
                                <input type="text" name="to"
                                       value="{{ request()->get('to') }}"
                                       class="form-control {{ request()->get('to') ? 'datepicker' : 'datefilter' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- POST --}}
            <div class="col-12 col-lg-4">
                <div class="form-group">
                    <label class="input-label">{{ trans('admin/main.post') }}</label>
                    <select name="blog_id" class="form-control select2"
                            data-placeholder="{{ trans('update.select_post') }}">
                        <option value="">{{ trans('public.all') }}</option>
                        @foreach($posts as $post)
                            <option value="{{ $post->id }}"
                                {{ !empty($selectedPost) && $selectedPost->id == $post->id ? 'selected' : '' }}>
                                {{ $post->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- SUBMIT --}}
            <div class="col-12 col-lg-2 d-flex align-items-end">
                <button class="btn kemetic-btn w-100">
                    {{ trans('public.show_results') }}
                </button>
            </div>

        </form>
    </div>
</section>

{{-- LIST --}}
<section class="mt-35">
    <h2 class="section-title kemetic-section-title">
        {{ trans('update.blog_comments_list') }}
    </h2>

    @if(!empty($comments) && !$comments->isEmpty())

        <div class="kemetic-card py-20 px-25 mt-20">
            <div class="table-responsive">
                <table class="table kemetic-table text-center">
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
                                <div class="d-flex align-items-center">
                                    <img src="{{ $comment->user->getAvatar() }}"
                                         class="kemetic-avatar mr-10"
                                         width="40">
                                    <span>{{ $comment->user->full_name }}</span>
                                </div>
                            </td>

                            <td class="text-left">
                                <a href="{{ $comment->blog->getUrl() }}"
                                   target="_blank"
                                   class="text-white">
                                    {{ $comment->blog->title }}
                                </a>
                            </td>

                            <td>
                                <input type="hidden"
                                       id="commentDescription{{ $comment->id }}"
                                       value="{{ nl2br($comment->comment) }}">
                                <button class="btn kemetic-view-btn js-view-comment"
                                        data-comment-id="{{ $comment->id }}">
                                    {{ trans('public.view') }}
                                </button>
                            </td>

                            <td>
                                @if($comment->status == 'active')
                                    <span class="kemetic-active">{{ trans('public.active') }}</span>
                                @else
                                    <span class="kemetic-pending">{{ trans('public.pending') }}</span>
                                @endif
                            </td>

                            <td>
                                {{ dateTimeFormat($comment->created_at,'j M Y | H:i') }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @else
        @include(getTemplate().'.includes.no-result',[
            'file_name' => 'comment.png',
            'title' => trans('panel.comments_no_result'),
            'hint' => nl2br(trans('panel.comments_no_result_hint'))
        ])
    @endif
</section>

<div class="my-30">
    {{ $comments->appends(request()->input())->links('vendor.pagination.panel') }}
</div>

@endsection

@push('scripts_bottom')
<script>
    var commentLang = '{{ trans('panel.comment') }}';
</script>

<script src="/assets/default/vendors/select2/select2.min.js"></script>
<script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
<script src="/assets/default/js/panel/blog_comments.min.js"></script>
@endpush
