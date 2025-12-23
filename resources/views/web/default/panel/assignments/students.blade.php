@extends('web.default.layouts.newapp')

@push('styles_top')
<style>
/* =========================
   KEMETIC APP THEME
========================= */
:root {
    --k-bg: #0b0b0b;
    --k-card: #141414;
    --k-gold: #d4af37;
    --k-gold-soft: rgba(212,175,55,.2);
    --k-border: rgba(212,175,55,.15);
    --k-text: #f5f5f5;
    --k-muted: #9a9a9a;
    --k-radius: 18px;
    --k-shadow: 0 12px 40px rgba(0,0,0,.65);
}

.kemetic-page {
    background: radial-gradient(circle at top, #1a1a1a, #000);
    min-height: 100vh;
    padding: 25px;
    color: var(--k-text);
}

.section-title {
    color: var(--k-gold);
    font-weight: 700;
    letter-spacing: 0.6px;
}

/* ===== STAT BOXES ===== */
.kemetic-stats {
    background: linear-gradient(145deg, #161616, #0c0c0c);
    border-radius: var(--k-radius);
    border: 1px solid var(--k-border);
    box-shadow: var(--k-shadow);
    padding: 30px 15px;
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

/* ===== TABLE CARD ===== */
.kemetic-table-card {
    background: var(--k-card);
    border-radius: var(--k-radius);
    border: 1px solid var(--k-border);
    box-shadow: var(--k-shadow);
    padding: 20px;
    margin-top: 20px;
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

/* ===== STATUS BADGES ===== */
.k-status-passed {
    color: var(--k-gold);
    font-weight: 600;
}

.k-status-pending {
    color: #f1c40f;
    font-weight: 600;
}

.k-status-failed {
    color: #e74c3c;
    font-weight: 600;
}

/* ===== DROPDOWN ===== */
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

/* ===== AVATAR ===== */
.user-inline-avatar .avatar {
    border-radius: 50%;
    overflow: hidden;
    width: 40px;
    height: 40px;
}

.user-inline-avatar .ml-5 {
    margin-left: 12px;
}
</style>
@endpush

@section('content')
<div class="kemetic-page">

    {{-- ===== ASSIGNMENT STATS ===== --}}
    <section>
        <h2 class="section-title">{{ $webinar->title }} | {{ $assignment->title }}</h2>
        <div class="kemetic-stats mt-25 row text-center">
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
    </section>

    {{-- ===== FILTER FORM ===== --}}
    <section class="mt-25">
        <h2 class="section-title">{{ trans('update.filter_assignments') }}</h2>
        <div class="kemetic-table-card">
            <form action="/panel/assignments/{{ $assignment->id }}/students" method="get" class="row">
                <div class="col-12 col-lg-4">
                    <div class="row">
                        <div class="col-12 col-md-6 form-group">
                            <label>{{ trans('public.from') }}</label>
                            <input type="text" name="from" class="form-control" value="{{ request()->get('from','') }}">
                        </div>
                        <div class="col-12 col-md-6 form-group">
                            <label>{{ trans('public.to') }}</label>
                            <input type="text" name="to" class="form-control" value="{{ request()->get('to','') }}">
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="row">
                        <div class="col-12 col-lg-8 form-group">
                            <label>{{ trans('admin/main.student') }}</label>
                            <select name="student_id" class="form-control">
                                <option value="">{{ trans('public.all') }}</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ request()->get('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-lg-4 form-group">
                            <label>{{ trans('public.status') }}</label>
                            <select name="status" class="form-control">
                                <option value="">{{ trans('public.all') }}</option>
                                @foreach(\App\Models\WebinarAssignmentHistory::$assignmentHistoryStatus as $status)
                                    <option value="{{ $status }}" {{ request()->get('status') == $status ? 'selected' : '' }}>
                                        {{ trans('update.assignment_history_status_'.$status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-2 d-flex align-items-center">
                    <button type="submit" class="btn btn-primary w-100">{{ trans('public.show_results') }}</button>
                </div>
            </form>
        </div>
    </section>

    {{-- ===== STUDENT HISTORIES TABLE ===== --}}
    <section class="mt-35">
        <h2 class="section-title">{{ trans('update.your_course_assignments') }}</h2>

        @if($histories->count() > 0)
            <div class="kemetic-table-card">
                <div class="table-responsive">
                    <table class="table text-center custom-table">
                        <thead>
                            <tr>
                                <th>{{ trans('quiz.student') }}</th>
                                <th>{{ trans('panel.purchase_date') }}</th>
                                <th>{{ trans('update.first_submission') }}</th>
                                <th>{{ trans('update.last_submission') }}</th>
                                <th>{{ trans('update.attempts') }}</th>
                                <th>{{ trans('quiz.grade') }}</th>
                                <th>{{ trans('public.status') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($histories as $history)
                                <tr>
                                    <td class="text-left">
                                        <div class="user-inline-avatar d-flex align-items-center">
                                            <div class="avatar">
                                                <img src="{{ $history->student->getAvatar() }}" class="img-cover">
                                            </div>
                                            <div class="ml-5">
                                                <span class="d-block">{{ $history->student->full_name }}</span>
                                                <span class="font-12 text-muted d-block">{{ $history->student->email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $history->purchase_date ? dateTimeFormat($history->purchase_date,'j M Y') : '-' }}</td>
                                    <td>{{ $history->first_submission ? dateTimeFormat($history->first_submission,'j M Y | H:i') : '-' }}</td>
                                    <td>{{ $history->last_submission ? dateTimeFormat($history->last_submission,'j M Y | H:i') : '-' }}</td>
                                    <td>{{ $assignment->attempts ? "{$history->usedAttemptsCount}/{$assignment->attempts}" : '-' }}</td>
                                    <td>{{ $history->grade ?? '-' }}</td>
                                    <td>
                                        @switch($history->status)
                                            @case(\App\Models\WebinarAssignmentHistory::$passed)
                                                <span class="k-status-passed">{{ trans('quiz.passed') }}</span>
                                            @break
                                            @case(\App\Models\WebinarAssignmentHistory::$pending)
                                                <span class="k-status-pending">{{ trans('public.pending') }}</span>
                                            @break
                                            @case(\App\Models\WebinarAssignmentHistory::$notPassed)
                                                <span class="k-status-failed">{{ trans('quiz.failed') }}</span>
                                            @break
                                            @default
                                                <span class="k-status-failed">{{ trans('update.assignment_history_status_not_submitted') }}</span>
                                        @endswitch
                                    </td>
                                    <td class="text-right">
                                        <div class="btn-group dropdown table-actions">
                                            <button class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                <i data-feather="more-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu menu-lg">
                                                <a href="{{ "{$assignment->webinar->getLearningPageUrl()}?type=assignment&item={$assignment->id}&student={$history->student_id}" }}" target="_blank" class="dropdown-item">
                                                    {{ trans('update.view_assignment') }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="my-30">
                    {{ $histories->appends(request()->input())->links('vendor.pagination.panel') }}
                </div>
            </div>
        @else
            @include(getTemplate() . '.includes.no-result',[
                'file_name' => 'meeting.png',
                'title' => trans('update.my_assignments_no_result'),
                'hint' => nl2br(trans('update.my_assignments_no_result_hint')),
            ])
        @endif
    </section>

</div>
@endsection

@push('scripts_bottom')
<script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
<script src="/assets/default/vendors/select2/select2.min.js"></script>
@endpush
