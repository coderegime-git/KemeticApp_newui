@extends('web.default.layouts.newapp')

@push('styles_top')
<style>
/* =========================
   KEMETIC THEME
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

/* ===== STATUS ===== */
.k-status-active {
    color: var(--k-gold);
    font-weight: 600;
}

.k-status-inactive {
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

/* ===== PAGINATION ===== */
.pagination .page-link {
    background: #111;
    color: var(--k-gold);
    border: 1px solid var(--k-border);
}

.pagination .active .page-link {
    background: var(--k-gold);
    color: #000;
}
</style>
@endpush

@section('content')
<div class="kemetic-page">

    {{-- ===== ASSIGNMENT STATISTICS ===== --}}
    <section>
        <h2 class="section-title">{{ trans('update.assignment_statistics') }}</h2>
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

    {{-- ===== STUDENTS ASSIGNMENTS ===== --}}
    <section class="mt-35">
        <h2 class="section-title">{{ trans('update.your_students_assignments') }}</h2>

        @if($assignments->count() > 0)
            <div class="kemetic-table-card">
                <div class="table-responsive">
                    <table class="text-center custom-table">
                        <thead>
                            <tr>
                                <th>{{ trans('update.title_and_course') }}</th>
                                <th>{{ trans('update.min_grade') }}</th>
                                <th>{{ trans('quiz.average') }}</th>
                                <th>{{ trans('update.submissions') }}</th>
                                <th>{{ trans('public.pending') }}</th>
                                <th>{{ trans('quiz.passed') }}</th>
                                <th>{{ trans('quiz.failed') }}</th>
                                <th>{{ trans('public.status') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($assignments as $assignment)
                            <tr>
                                <td class="text-left">
                                    <span class="d-block font-16 font-weight-500 text-dark-blue">{{ $assignment->title }}</span>
                                    <span class="d-block font-12 text-muted">{{ $assignment->webinar->title }}</span>
                                </td>
                                <td>{{ $assignment->min_grade ?? '-' }}</td>
                                <td>{{ $assignment->average_grade ?? '-' }}</td>
                                <td>{{ $assignment->submissions }}</td>
                                <td>{{ $assignment->pendingCount }}</td>
                                <td>{{ $assignment->passedCount }}</td>
                                <td>{{ $assignment->failedCount }}</td>
                                <td>
                                    @switch($assignment->status)
                                        @case('active')
                                            <span class="k-status-active">{{ trans('public.active') }}</span>
                                        @break
                                        @case('inactive')
                                            <span class="k-status-inactive">{{ trans('public.inactive') }}</span>
                                        @break
                                    @endswitch
                                </td>
                                <td class="text-right">
                                    <div class="btn-group dropdown table-actions">
                                        <button class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                            <i data-feather="more-vertical" height="20"></i>
                                        </button>
                                        <div class="dropdown-menu menu-lg">
                                            @can('panel_assignments_students')
                                                <a href="/panel/assignments/{{ $assignment->id }}/students?status=pending" target="_blank" class="dropdown-item">{{ trans('update.pending_review') }}</a>
                                                <a href="/panel/assignments/{{ $assignment->id }}/students" target="_blank" class="dropdown-item">{{ trans('update.all_assignments') }}</a>
                                            @endcan
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
                'hint' => nl2br(trans('update.my_assignments_no_result_hint')),
            ])
        @endif
    </section>

</div>
@endsection

@push('scripts_bottom')
@endpush
