@extends('web.default.layouts.newapp')

@push('styles_top')
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
}

/* =========================
   STATS
========================= */
.kemetic-stats {
    background: linear-gradient(145deg, #161616, #0c0c0c);
    border-radius: var(--k-radius);
    border: 1px solid var(--k-border);
    box-shadow: var(--k-shadow);
    padding:10px;
}

.stat-item img {
    filter: brightness(1.2);
}

.stat-value {
    font-size: 34px;
    color: var(--k-gold);
}

.stat-label {
    color: var(--k-muted);
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* =========================
   FILTER CARD
========================= */
.kemetic-filter {
    background: var(--k-card);
    border-radius: var(--k-radius);
    border: 1px solid var(--k-border);
    padding: 20px;
    box-shadow: var(--k-shadow);
    margin-top: 20px;
}

/* =========================
   TABLE CARD
========================= */
.kemetic-table-card {
    background: var(--k-card);
    border-radius: var(--k-radius);
    border: 1px solid var(--k-border);
    box-shadow: var(--k-shadow);
}

.custom-table thead {
    background: rgba(212,175,55,.1);
}

.custom-table th {
    color: var(--k-gold);
    font-weight: 600;
    border-bottom: 1px solid var(--k-border);
}

.custom-table td {
    color: var(--k-text);
    border-top: 1px solid rgba(255,255,255,.05);
}

.custom-table tr:hover {
    background: rgba(212,175,55,.05);
}

.custom-table a {
    color: var(--k-gold);
    font-weight: 600;
}

.custom-table a:hover {
    text-decoration: underline;
}

/* =========================
   STATUS
========================= */
.k-status-passed {
    color: #2ecc71;
    font-weight: 600;
}

.k-status-failed {
    color: #e74c3c;
    font-weight: 600;
}

.k-status-pending {
    color: #f1c40f;
    font-weight: 600;
}

/* =========================
   ACTIONS
========================= */
.btn-transparent {
    color: var(--k-gold);
}

.dropdown-menu {
    background: #0f0f0f;
    border: 1px solid var(--k-border);
}

.dropdown-menu a {
    color: var(--k-text);
}

.dropdown-menu a:hover {
    background: rgba(212,175,55,.12);
    color: var(--k-gold);
}

/* =========================
   PAGINATION
========================= */
.pagination .page-link {
    background: #111;
    color: var(--k-gold);
    border: 1px solid var(--k-border);
}

.pagination .active .page-link {
    background: var(--k-gold);
    color: #000;
}


.kemetic-input,
.kemetic-select {
    width: 100%;
    height: 46px;
    padding: 10px 14px 10px 42px;
    border-radius: 12px;
    background: #0f0f0f;
    border: 1px solid rgba(212,175,55,.25);
    color: #fff;
    transition: .3s;
}

.kemetic-select {
    padding-left: 14px;
}

.kemetic-input:focus,
.kemetic-select:focus {
    outline: none;
    border-color: #d4af37;
    box-shadow: 0 0 0 2px rgba(212,175,55,.25);
}

.kemetic-input-group {
    position: relative;
}

/* ===============================
   KEMETIC FILTER FORM
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

/* ===============================
   SELECT2 – KEMETIC DARK THEME
================================ */

/* main box */
.select2-container--default .select2-selection--single {
    background: #0d0d0d !important;
    border: 1px solid rgba(242,201,76,.35) !important;
    border-radius: 14px !important;
    height: 44px !important;
    display: flex;
    align-items: center;
    color: #e0e0e0 !important;
}

/* text */
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #e0e0e0 !important;
    line-height: 44px !important;
}

/* arrow */
.select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: #f2c94c transparent transparent transparent !important;
}

/* dropdown */
.select2-dropdown {
    background: #0f0f0f !important;
    border: 1px solid rgba(242,201,76,.35) !important;
    border-radius: 12px !important;
}

/* options */
.select2-results__option {
    color: #e0e0e0 !important;
    padding: 10px 14px !important;
}

/* hover */
.select2-results__option--highlighted {
    background: rgba(242,201,76,.15) !important;
    color: #fff !important;
}

/* selected */
.select2-results__option[aria-selected=true] {
    background: rgba(242,201,76,.25) !important;
}

/* search box */
.select2-search--dropdown .select2-search__field {
    background: #0d0d0d !important;
    border: 1px solid rgba(242,201,76,.35) !important;
    color: #fff !important;
    border-radius: 8px !important;
}

</style>
<link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
<link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
@endpush

@section('content')
<div class="kemetic-page">

    {{-- ===== ASSIGNMENT STATISTICS ===== --}}
    <section>
        <h2 class="section-title">{{ trans('update.assignment_statistics') }}</h2>

        <div class="kemetic-stats mt-25 p-30">
            <div class="row text-center">

                <div class="col-3 stat-item">
                    <img src="/assets/default/img/activity/homework.svg" width="60">
                    <div class="stat-value mt-10">{{ $courseAssignmentsCount }}</div>
                    <div class="stat-label">{{ trans('update.course_assignments') }}</div>
                </div>

                <div class="col-3 stat-item">
                    <img src="/assets/default/img/activity/58.svg" width="60">
                    <div class="stat-value mt-10">{{ $pendingReviewCount }}</div>
                    <div class="stat-label">{{ trans('update.pending_review') }}</div>
                </div>

                <div class="col-3 stat-item">
                    <img src="/assets/default/img/activity/45.svg" width="60">
                    <div class="stat-value mt-10">{{ $passedCount }}</div>
                    <div class="stat-label">{{ trans('quiz.passed') }}</div>
                </div>

                <div class="col-3 stat-item">
                    <img src="/assets/default/img/activity/pin.svg" width="60">
                    <div class="stat-value mt-10">{{ $failedCount }}</div>
                    <div class="stat-label">{{ trans('quiz.failed') }}</div>
                </div>

            </div>
        </div>
    </section>

    <section class="mt-25 kemetic-section">
        <h2 class="section-title kemetic-title">
            {{ trans('panel.filter_comments') }}
        </h2>

        <div class="kemetic-card mt-20">
            <form action="/panel/assignments/my-assignments" method="get" class="row g-3 align-items-end">

                {{-- Date Range --}}
                <div class="col-12 col-lg-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="kemetic-form-group">
                                <label class="kemetic-label">{{ trans('public.from') }}</label>
                                <div class="kemetic-input-group">
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
                            <div class="kemetic-form-group">
                                <label class="kemetic-label">{{ trans('public.to') }}</label>
                                <div class="kemetic-input-group">
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

                {{-- User & Webinar --}}
                <div class="col-12 col-lg-6">
                    <div class="row g-3">
                        <div class="col-12 col-lg-5">
                            <div class="kemetic-form-group">
                                <label class="kemetic-label">{{ trans('product.course') }}</label>
                                <div class="kemetic-input-group">
                                    <select name="webinar_id" class="kemetic-select select2">
                                        <option value="">{{ trans('webinars.all_courses') }}</option>

                                        @foreach($webinars as $webinar)
                                            <option value="{{ $webinar->id }}"
                                                    @if(request()->get('webinar_id') == $webinar->id) selected @endif>
                                                {{ $webinar->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-7">
                            <div class="kemetic-form-group">
                                <label class="kemetic-label"> {{ trans('public.status') }}</label>
                                <div class="kemetic-input-group">
                                   <select class="kemetic-select" name="status">
                                        <option value="">{{ trans('public.all') }}</option>

                                        @foreach(\App\Models\WebinarAssignmentHistory::$assignmentHistoryStatus as $status)
                                            <option value="{{ $status }}"
                                                    {{ (request()->get('status') == $status) ? 'selected' : '' }}>
                                                {{ trans('update.assignment_history_status_'.$status) }}
                                            </option>
                                        @endforeach
                                    </select>
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


    {{-- ===== ASSIGNMENTS TABLE ===== --}}
    <section class="mt-35">
        <h2 class="section-title">{{ trans('update.my_assignments') }}</h2>

        @if($assignments->count() > 0)
            <div class="kemetic-table-card mt-20 p-20">
                <div class="table-responsive">
                    <table class="custom-table text-center">
                        <thead>
                        <tr>
                            <th>{{ trans('update.title_and_course') }}</th>
                            <th>{{ trans('update.deadline') }}</th>
                            <th>{{ trans('update.first_submission') }}</th>
                            <th>{{ trans('update.last_submission') }}</th>
                            <th>{{ trans('update.attempts') }}</th>
                            <th>{{ trans('quiz.grade') }}</th>
                            <th>{{ trans('update.pass_grade') }}</th>
                            <th>{{ trans('public.status') }}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($assignments as $assignment)
                            <tr>
                                <td class="text-left">
                                    <span class="d-block font-16 font-weight-500">{{ $assignment->title }}</span>
                                    <span class="d-block font-12 text-muted">{{ $assignment->webinar->title }}</span>
                                </td>
                                <td>{{ !empty($assignment->deadline) ? dateTimeFormat($assignment->deadlineTime, 'j M Y') : '-' }}</td>
                                <td>{{ !empty($assignment->first_submission) ? dateTimeFormat($assignment->first_submission, 'j M Y | H:i') : '-' }}</td>
                                <td>{{ !empty($assignment->last_submission) ? dateTimeFormat($assignment->last_submission, 'j M Y | H:i') : '-' }}</td>
                                <td>{{ !empty($assignment->attempts) ? "{$assignment->usedAttemptsCount}/{$assignment->attempts}" : '-' }}</td>
                                <td>{{ (!empty($assignment->assignmentHistory) and !empty($assignment->assignmentHistory->grade)) ? $assignment->assignmentHistory->grade : '-' }}</td>
                                <td>{{ $assignment->pass_grade }}</td>
                                <td>
                                    @if(empty($assignment->assignmentHistory) or ($assignment->assignmentHistory->status == \App\Models\WebinarAssignmentHistory::$notSubmitted))
                                        <span class="k-status-failed">{{ trans('update.assignment_history_status_not_submitted') }}</span>
                                    @else
                                        @switch($assignment->assignmentHistory->status)
                                            @case(\App\Models\WebinarAssignmentHistory::$passed)
                                                <span class="k-status-passed">{{ trans('quiz.passed') }}</span>
                                            @break
                                            @case(\App\Models\WebinarAssignmentHistory::$pending)
                                                <span class="k-status-pending">{{ trans('public.pending') }}</span>
                                            @break
                                            @case(\App\Models\WebinarAssignmentHistory::$notPassed)
                                                <span class="k-status-failed">{{ trans('quiz.failed') }}</span>
                                            @break
                                        @endswitch
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="btn-group dropdown table-actions">
                                        <button class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                            <i data-feather="more-vertical" height="20"></i>
                                        </button>
                                        <div class="dropdown-menu menu-lg">
                                            @if($assignment->webinar->checkUserHasBought())
                                                <a href="{{ "{$assignment->webinar->getLearningPageUrl()}?type=assignment&item={$assignment->id}" }}" target="_blank" class="dropdown-item">{{ trans('update.view_assignment') }}</a>
                                            @else
                                                <a href="#!" class="not-access-toast dropdown-item">{{ trans('update.view_assignment') }}</a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="my-30" style="padding: 10px;">
                    {{ $assignments->appends(request()->input())->links('vendor.pagination.panel') }}
                </div>
            </div>
        @else
            @include(getTemplate() . '.includes.no-result',[
                'file_name' => 'meeting.png',
                'title' => trans('update.my_assignments_no_result'),
                'hint' => nl2br(trans('update.my_assignments_no_result_hint_student')),
            ])
        @endif
    </section>

</div>
@endsection

@push('scripts_bottom')
<script>
    var notAccessToastTitleLang = '{{ trans('public.not_access_toast_lang') }}';
    var notAccessToastMsgLang = '{{ trans('public.not_access_toast_msg_lang') }}';
</script>
<script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
<script src="/assets/default/vendors/select2/select2.min.js"></script>
<script src="/assets/default/js/panel/my_assignments.min.js"></script>
@endpush
