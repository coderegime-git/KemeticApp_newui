@extends('web.default.layouts.newapp')

@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">

<style>
:root{
    --k-bg:#0f0f0f;
    --k-card:#161616;
    --k-border:#2a2a2a;
    --k-gold:#F2C94C;
    --k-gold-soft:rgba(242,201,76,.18);
    --k-text:#e6e6e6;
    --k-muted:#9a9a9a;
    --k-radius:16px;
}

/* Section title */
.k-title{
    color:var(--k-text);
}

/* Cards */
.k-card{
    background:var(--k-card);
    border:1px solid var(--k-border);
    border-radius:var(--k-radius);
}

/* Stat box */
.k-stat strong{
    color:var(--k-gold);
}

/* Form */
.k-form .input-label{
    color:var(--k-muted);
    font-size:13px;
}

.k-form .form-control{
    background:#101010;
    border:1px solid var(--k-border);
    color:var(--k-text);
}

.k-form .form-control:focus{
    border-color:var(--k-gold);
    box-shadow:0 0 0 2px var(--k-gold-soft);
}

.k-form .input-group-text{
    background:#111;
    border:1px solid var(--k-border);
}

/* Table */
.k-table{
    background:transparent;
}

.k-table thead th{
    color:var(--k-muted);
    border-bottom:1px solid var(--k-border);
}

.k-table tbody tr{
    border-bottom:1px solid var(--k-border);
}

.k-table tbody tr:hover{
    background:rgba(255,255,255,0.02);
}

.k-user-name{
    color:var(--k-text);
}

/* Buttons */
.k-btn{
    background:linear-gradient(135deg,#F2C94C,#E0B63A);
    border:none;
    color:#000;
    font-weight:600;
}

.k-btn-gray{
    background:#1e1e1e;
    border:1px solid var(--k-border);
    color:var(--k-text);
}

/* Dropdown */
.k-dropdown .dropdown-menu{
    background:#151515;
    border:1px solid var(--k-border);
}

.k-dropdown .dropdown-item,
.k-dropdown button{
    color:var(--k-text);
}

.k-dropdown .dropdown-item:hover,
.k-dropdown button:hover{
    background:var(--k-gold-soft);
    color:var(--k-gold);
}

/* ===============================
   KEMETIC COMMENTS STATISTICS
=============================== */

.kemetic-box{
    background:#0b0b0b;
    border:1px solid rgba(212,175,55,.25);
    border-radius:18px;
    padding: 10px;
}

.kemetic-stat-card{
    background:#090909;
    border:1px solid rgba(212,175,55,.25);
    border-radius:16px;
    transition:.3s ease;
}

.kemetic-stat-card:hover{
    transform:translateY(-4px);
    box-shadow:0 12px 30px rgba(212,175,55,.15);
}

.kemetic-stat-card img{
    filter:drop-shadow(0 0 8px rgba(212,175,55,.6));
}

.kemetic-stat-card strong{
    color:#d4af37;
    font-weight:700;
}

.section-title{
    color:#d4af37;
    font-weight:700;
}

/* ================================
   KEMETIC FILTER PANEL – BLACK GOLD
================================ */

/* Section */
.kemetic-section {
    color: #fff;
}

/* Title */
.kemetic-title {
    font-size: 20px;
    font-weight: 600;
    color: #d4af37;
}

/* Card */
.kemetic-card {
    background: linear-gradient(145deg, #0e0e0e, #161616);
    border-radius: 16px;
    padding: 24px;
    border: 1px solid rgba(212, 175, 55, 0.2);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.6);
}

/* Labels */
.kemetic-label {
    font-size: 13px;
    color: #c9b46a;
    margin-bottom: 6px;
    display: block;
}

/* Form Group */
.kemetic-form-group {
    width: 100%;
}

/* Input Group */
.kemetic-input-group {
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 10px;
    border: 1px solid rgba(212, 175, 55, 0.25);
    transition: all 0.3s ease;
}

.kemetic-input-group:focus-within {
    border-color: #d4af37;
    box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.15);
}

/* Icon */
.kemetic-icon {
    padding: 0 12px;
    color: #d4af37;
}

.kemetic-icon svg {
    width: 18px;
    height: 18px;
}

/* Inputs */
.kemetic-input {
    width: 100%;
    background: transparent;
    border: none;
    outline: none;
    padding: 12px;
    color: #fff;
    font-size: 14px;
}

.kemetic-input::placeholder {
    color: rgba(255, 255, 255, 0.4);
}

/* Button */
.kemetic-btn {
    background: linear-gradient(135deg, #d4af37, #b8962e);
    border: none;
    border-radius: 12px;
    padding: 12px;
    color: #000;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.kemetic-btn:hover {
    background: linear-gradient(135deg, #e6c55a, #d4af37);
    box-shadow: 0 8px 30px rgba(212, 175, 55, 0.4);
    transform: translateY(-1px);
}


</style>
@endpush

@section('content')

{{-- ================= STATISTICS ================= --}}
<section class="mt-30">
        <h2 class="section-title">
            {{ trans('panel.comments_statistics') }}
        </h2>

        <div class="kemetic-box mt-25 p-25">
            <div class="row text-center">

                {{-- TOTAL COMMENTS --}}
                <div class="col-12 col-md-4 mt-15">
                    
                        <img src="/assets/default/img/activity/39.svg" width="64" alt="">
                        <strong class="font-30 d-block mt-15">
                            {{ $comments->count() }}
                        </strong>
                        <span class="font-16 text-gray">
                            {{ trans('panel.comments') }}
                        </span>                </div>

                {{-- REPLIED COMMENTS --}}
                <div class="col-12 col-md-4 mt-15">
                        <img src="/assets/default/img/activity/41.svg" width="64" alt="">
                        <strong class="font-30 d-block mt-15">
                            {{ $repliedCommentsCount }}
                        </strong>
                        <span class="font-16 text-gray">
                            {{ trans('panel.replied') }}
                        </span>
                </div>

                {{-- NOT REPLIED COMMENTS --}}
                <div class="col-12 col-md-4 mt-15">
                        <img src="/assets/default/img/activity/40.svg" width="64" alt="">
                        <strong class="font-30 d-block mt-15">
                            {{ $comments->count() - $repliedCommentsCount }}
                        </strong>
                        <span class="font-16 text-gray">
                            {{ trans('panel.not_replied') }}
                        </span>
                </div>

            </div>
        </div>
    </section>


{{-- ================= FILTER ================= --}}
<section class="mt-25 kemetic-section">
        <h2 class="section-title kemetic-title">
            {{ trans('panel.filter_comments') }}
        </h2>

        <div class="kemetic-card mt-20">
            <form action="/panel/webinars/comments" method="get" class="row g-3 align-items-end">

                {{-- Date Range --}}
                <div class="col-12 col-lg-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="kemetic-form-group">
                                <label class="kemetic-label">{{ trans('public.from') }}</label>
                                <div class="kemetic-input-group">
                                    <span class="kemetic-icon">
                                        <i data-feather="calendar"></i>
                                    </span>
                                    <input
                                        type="text"
                                        name="from"
                                        autocomplete="off"
                                        value="{{ request()->get('from') }}"
                                        class="kemetic-input {{ !empty(request()->get('from')) ? 'datepicker' : 'datefilter' }}"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="kemetic-form-group">
                                <label class="kemetic-label">{{ trans('public.to') }}</label>
                                <div class="kemetic-input-group">
                                    <span class="kemetic-icon">
                                        <i data-feather="calendar"></i>
                                    </span>
                                    <input
                                        type="text"
                                        name="to"
                                        autocomplete="off"
                                        value="{{ request()->get('to') }}"
                                        class="kemetic-input {{ !empty(request()->get('to')) ? 'datepicker' : 'datefilter' }}"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- User & Webinar --}}
                <div class="col-12 col-lg-6">
                    <div class="row g-3">
                        <div class="col-12 col-lg-5">
                            <div class="kemetic-form-group">
                                <label class="kemetic-label">{{ trans('panel.user') }}</label>
                                <div class="kemetic-input-group">
                                    <input
                                        type="text"
                                        name="user"
                                        value="{{ request()->get('user') }}"
                                        class="kemetic-input"
                                        placeholder="User name"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-7">
                            <div class="kemetic-form-group">
                                <label class="kemetic-label">{{ trans('panel.webinar') }}</label>
                                <div class="kemetic-input-group">
                                    <input
                                        type="text"
                                        name="webinar"
                                        value="{{ request()->get('webinar') }}"
                                        class="kemetic-input"
                                        placeholder="Course / Webinar"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Button --}}
                <div class="col-12 col-lg-2">
                    <button type="submit" class="kemetic-btn w-100">
                        {{ trans('public.show_results') }}
                    </button>
                </div>

            </form>
        </div>
    </section>


{{-- ================= TABLE ================= --}}
<section class="mt-35">
    <h2 class="section-title k-title">{{ trans('panel.webinar_comments_list') }}</h2>

    @if(!$comments->isEmpty())
        <div class="k-card py-20 px-25 mt-20">
            <div class="table-responsive">
                <table class="table k-table text-center">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('panel.user') }}</th>
                        <th class="text-left">{{ trans('panel.webinar') }}</th>
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
                                <div class="d-flex align-items-center">
                                    <img src="{{ $comment->user->getAvatar() }}"
                                         class="rounded-circle" width="36">
                                    <span class="ml-10 k-user-name">
                                        {{ $comment->user->full_name }}
                                    </span>
                                </div>
                            </td>

                            <td class="text-left">
                                <a href="{{ $comment->webinar->getUrl() }}"
                                   target="_blank"
                                   class="k-user-name">
                                    {{ $comment->webinar->title }}
                                </a>
                            </td>

                            <td>
                                <button class="btn btn-sm k-btn-gray js-view-comment"
                                        data-comment-id="{{ $comment->id }}">
                                    {{ trans('public.view') }}
                                </button>
                            </td>

                            <td>
                                @if(empty($comment->reply_id))
                                    <span class="text-warning">{{ trans('public.open') }}</span>
                                @else
                                    <span class="text-success">{{ trans('panel.replied') }}</span>
                                @endif
                            </td>

                            <td>{{ dateTimeFormat($comment->created_at,'j M Y | H:i') }}</td>

                            <td class="text-right">
                                <input type="hidden"
                                       id="commentDescription{{ $comment->id }}"
                                       value="{{ nl2br($comment->comment) }}">

                                <div class="dropdown k-dropdown">
                                    <button class="btn-transparent dropdown-toggle"
                                            data-toggle="dropdown">
                                        <i data-feather="more-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <button class="dropdown-item js-reply-comment"
                                                data-comment-id="{{ $comment->id }}">
                                            {{ trans('panel.reply') }}
                                        </button>
                                        <button class="dropdown-item report-comment"
                                                data-comment-id="{{ $comment->id }}"
                                                data-item-id="{{ $comment->webinar_id }}">
                                            {{ trans('panel.report') }}
                                        </button>
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
        @include(getTemplate().'.includes.no-result',[
            'file_name'=>'comment.png',
            'title'=>trans('panel.comments_no_result'),
            'hint'=>nl2br(trans('panel.comments_no_result_hint'))
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
