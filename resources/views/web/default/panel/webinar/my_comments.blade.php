@extends('web.default.layouts.newapp')
@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush
<style>
    /* =========================================================
   KEMETIC COMMENTS PAGE
   Black • Gold • Premium Panel UI
========================================================= */

:root {
    --k-bg: #0d0d0d;
    --k-card: #161616;
    --k-border: rgba(242, 201, 76, 0.25);
    --k-gold: #F2C94C;
    --k-gold-soft: rgba(242, 201, 76, 0.18);
    --k-text: #e6e6e6;
    --k-muted: #9a9a9a;
    --k-radius: 16px;
}

/* PAGE BACKGROUND */
.panel-content,
section {
    background: transparent;
}

/* SECTION TITLE */
.section-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--k-gold);
    letter-spacing: 0.4px;
    position: relative;
}

.section-title::after {
    content: "";
    display: block;
    width: 60px;
    height: 1px;
    margin-top: 6px;
    background: linear-gradient(to right, var(--k-gold), transparent);
}

/* PANEL CARD */
.panel-section-card {
    background: linear-gradient(180deg, #161616, #101010);
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius);
    box-shadow: 0 20px 55px rgba(0, 0, 0, 0.75);
}

/* FORM LABEL */
.input-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--k-gold);
    margin-bottom: 6px;
}

/* INPUTS */
.form-control {
    background: #0f0f0f;
    border: 1px solid #2a2a2a;
    color: var(--k-text);
    border-radius: 12px;
    padding: 11px 14px;
    font-size: 14px;
    transition: 0.25s ease;
}

.form-control:focus {
    background: #141414;
    border-color: var(--k-gold);
    box-shadow: 0 0 0 2px var(--k-gold-soft);
    outline: none;
}

.form-control::placeholder {
    color: var(--k-muted);
}

/* INPUT GROUP */
.input-group-text {
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    color: var(--k-gold);
    border-radius: 12px 0 0 12px;
}

/* BUTTONS */
.btn {
    border-radius: 12px;
    font-weight: 600;
    transition: 0.25s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #F2C94C, #E5A100);
    border: none;
    color: #000;
}

.btn-primary:hover {
    box-shadow: 0 12px 30px rgba(242, 201, 76, 0.45);
    transform: translateY(-2px);
}

/* VIEW BUTTON */
.btn-gray200 {
    background: #1c1c1c;
    border: 1px solid #2a2a2a;
    color: var(--k-gold);
}

.btn-gray200:hover {
    background: var(--k-gold);
    color: #000;
}

/* TABLE */
.custom-table {
    background: transparent;
    border-collapse: separate;
    border-spacing: 0 10px;
}

.custom-table thead th {
    border: none;
    font-size: 13px;
    color: var(--k-muted);
    font-weight: 600;
}

.custom-table tbody tr {
    background: #141414;
    border: 1px solid var(--k-border);
    border-radius: 12px;
    transition: 0.25s ease;
}

.custom-table tbody tr:hover {
    background: #1b1b1b;
    box-shadow: 0 10px 25px rgba(242, 201, 76, 0.12);
}

.custom-table td {
    border: none;
    padding: 16px;
    color: var(--k-text);
}

.custom-table a {
    color: var(--k-gold);
    text-decoration: none;
}

.custom-table a:hover {
    text-decoration: underline;
}

/* STATUS */
.text-primary,
.text-warning {
    color: var(--k-gold) !important;
}

/* DROPDOWN */
.table-actions .dropdown-menu {
    background: #161616;
    border: 1px solid var(--k-border);
    border-radius: 12px;
    padding: 10px;
}

.table-actions .dropdown-menu button,
.table-actions .dropdown-menu a {
    color: var(--k-text);
    font-size: 14px;
}

.table-actions .dropdown-menu button:hover,
.table-actions .dropdown-menu a:hover {
    color: var(--k-gold);
    background: transparent;
}

/* PAGINATION */
.pagination .page-link {
    background: #161616;
    border: 1px solid #2a2a2a;
    color: var(--k-text);
}

.pagination .page-item.active .page-link {
    background: var(--k-gold);
    border-color: var(--k-gold);
    color: #000;
}

/* NO RESULT */
.no-result-card {
    background: #141414;
    border: 1px dashed var(--k-border);
    border-radius: var(--k-radius);
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .custom-table tbody tr {
        display: block;
    }
}

/* =====================================
   KEMETIC FILTER PANEL – BLACK & GOLD
===================================== */

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
    background: linear-gradient(145deg, #0b0b0b, #161616);
    border-radius: 16px;
    padding: 22px 24px;
    border: 1px solid rgba(212, 175, 55, 0.25);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
}

/* Labels */
.kemetic-label {
    font-size: 13px;
    color: #cbb86e;
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
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(212, 175, 55, 0.25);
    transition: 0.3s ease;
}

.kemetic-input-group:focus-within {
    border-color: #d4af37;
    box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.18);
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
    color: #ffffff;
    font-size: 14px;
}

.kemetic-input::placeholder {
    color: rgba(255, 255, 255, 0.45);
}

/* Button */
.kemetic-btn {
    background: linear-gradient(135deg, #d4af37, #b8962e);
    color: #000;
    border: none;
    border-radius: 12px;
    padding: 12px;
    font-weight: 600;
    letter-spacing: 0.4px;
    transition: all 0.3s ease;
}

.kemetic-btn:hover {
    background: linear-gradient(135deg, #e8c963, #d4af37);
    box-shadow: 0 8px 28px rgba(212, 175, 55, 0.45);
    transform: translateY(-1px);
}

</style>
@section('content')

    <section class="kemetic-section">
        <h2 class="section-title kemetic-title">
            {{ trans('panel.filter_comments') }}
        </h2>

        <div class="kemetic-card mt-20">
            <form action="" method="get" class="row g-3 align-items-end">

                {{-- Date Range --}}
                <div class="col-12 col-lg-5">
                    <div class="row g-3">

                        {{-- From --}}
                        <div class="col-12 col-md-6">
                            <div class="kemetic-form-group">
                                <label class="kemetic-label">
                                    {{ trans('public.from') }}
                                </label>

                                <div class="kemetic-input-group">
                                    <input type="date"
                                        class="kemetic-input text-center"
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

                        {{-- To --}}
                        <div class="col-12 col-md-6">
                            <div class="kemetic-form-group">
                                <label class="kemetic-label">
                                    {{ trans('public.to') }}
                                </label>

                                <div class="kemetic-input-group">
                                    <input type="date"
                                        class="kemetic-input text-center"
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

                {{-- Webinar --}}
                <div class="col-12 col-lg-5">
                    <div class="kemetic-form-group">
                        <label class="kemetic-label">
                            {{ trans('panel.webinar') }}
                        </label>
                        <div class="kemetic-input-group">
                            <input
                                type="text"
                                name="webinar"
                                value="{{ request()->get('webinar') }}"
                                class="kemetic-input"
                                placeholder="Search webinar / course"
                            />
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


    <section class="mt-35">
        <h2 class="section-title">{{ trans('panel.my_comments') }}</h2>

        @if(!empty($comments) and !$comments->isEmpty())

            <div class="panel-section-card py-20 px-25 mt-20">
                <div class="row">
                    <div class="col-12 ">
                        <div class="table-responsive">
                            <table class="custom-table text-center ">
                                <thead>
                                <tr>
                                    <th class="text-left text-gray">{{ trans('panel.webinar') }}</th>
                                    <th class="text-gray text-center">{{ trans('panel.comment') }}</th>
                                    <th class="text-gray text-center">{{ trans('public.status') }}</th>
                                    <th class="text-gray text-center">{{ trans('public.date') }}</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($comments as $comment)
                                    <tr>
                                        <td class="text-left align-middle" width="35%">
                                            <a class="text-dark-blue font-weight-500" href="{{ $comment->webinar->getUrl() }}" target="_blank">{{ $comment->webinar->title }}</a>
                                        </td>
                                        <td class="align-middle">
                                            <button type="button" data-comment-id="{{ $comment->id }}" class="js-view-comment btn btn-sm btn-gray200">{{ trans('public.view') }}</button>
                                        </td>

                                        <td class="align-middle">
                                            @if($comment->status == 'active')
                                                <span class="text-primary text-dark-blue font-weight-500">{{ trans('public.published') }}</span>
                                            @else
                                                <span class="text-warning text-dark-blue font-weight-500">{{ trans('public.pending') }}</span>
                                            @endif
                                        </td>

                                        <td class="text-dark-blue font-weight-500 align-middle">{{ dateTimeFormat($comment->created_at,'j M Y | H:i') }}</td>
                                        <td class="align-middle text-right">
                                            <input type="hidden" id="commentDescription{{ $comment->id }}" value="{{ nl2br($comment->comment) }}">
                                            <div class="btn-group dropdown table-actions">
                                                <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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

    <div class="my-30" style="padding: 10px;">
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
