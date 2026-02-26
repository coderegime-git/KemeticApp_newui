@extends('web.default.layouts.newapp')

<style>
  /* KEMETIC STATS */
.kemetic-stat-section {
    margin-top: 25px;
}

.kemetic-title {
    font-size: 22px;
    font-weight: 700;
    color: #F2C94C;
    margin-bottom: 18px;
}

/* CARD */
.kemetic-stat-card {
    background: linear-gradient(180deg, #121212, #0b0b0b);
    border: 1px solid #262626;
    border-radius: 18px;
    padding: 26px 18px;
}

/* ITEM */
.kemetic-stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
}

/* ICON */
.kemetic-stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: rgba(242, 201, 76, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
}
.kemetic-stat-icon img {
    width: 28px;
    filter: invert(0.9);
}

/* VALUE */
.kemetic-stat-value {
    font-size: 30px;
    font-weight: 700;
    color: #F2C94C;
}

/* LABEL */
.kemetic-stat-label {
    font-size: 14px;
    color: #9a9a9a;
}

/* MOBILE */
@media (max-width: 768px) {
    .kemetic-stat-card {
        padding: 20px 12px;
    }
    .kemetic-stat-value {
        font-size: 24px;
    }
}

/* KEMETIC FILTER */
.kemetic-filter-section {
    color: #eaeaea;
}

.kemetic-title {
    font-size: 22px;
    font-weight: 700;
    color: #F2C94C;
}

/* CARD */
.kemetic-filter-card {
    background: linear-gradient(180deg, #121212, #0a0a0a);
    border: 1px solid #262626;
    border-radius: 18px;
    padding: 26px 22px;
}

/* LABEL */
.kemetic-label {
    font-size: 13px;
    color: #b5b5b5;
    margin-bottom: 6px;
    display: block;
}

/* INPUT GROUP */
.kemetic-input-group {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #0f0f0f;
    border: 1px solid #2a2a2a;
    border-radius: 12px;
    padding: 10px 12px;
}

.kemetic-input-group i {
    color: #F2C94C;
}

/* INPUT */
.kemetic-input {
    width: 100%;
    background: transparent;
    border: none;
    color: #fff;
    outline: none;
    font-size: 14px;
}

.kemetic-input::placeholder {
    color: #666;
}

/* SELECT */
.kemetic-select {
    background: #0f0f0f;
    border: 1px solid #2a2a2a;
    border-radius: 12px;
    color: #fff;
    padding: 10px 12px;
    width: 100%;
    outline: none;
}
.kemetic-select option {
    background: #0f0f0f;
    color: #fff;
}
.kemetic-select:focus {
    border-color: #F2C94C;
}

/* BUTTON */
.kemetic-btn {
    background: linear-gradient(135deg, #F2C94C, #d4af37);
    border: none;
    border-radius: 14px;
    padding: 12px;
    font-weight: 700;
    color: #000;
    transition: 0.3s ease;
}
.kemetic-btn:hover {
    background: linear-gradient(135deg, #d4af37, #F2C94C);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(242,201,76,0.4);
}

/* TABLE CARD */
.kemetic-table-card {
    background: linear-gradient(180deg,#121212,#0a0a0a);
    border:1px solid #262626;
    border-radius:18px;
    padding:20px;
}

/* TABLE */
.kemetic-table {
    width:100%;
    border-collapse:separate;
    border-spacing:0 10px;
}
.kemetic-table thead th {
    color:#aaa; 
    font-size:13px;
    font-weight:600; 
    text-align:center;
    padding-bottom: 10px;
}
.kemetic-table thead th.text-left {
    text-align: left;
}
.kemetic-table tbody tr {
    background:#0f0f0f;
    border:1px solid #262626;
    transition: 0.3s ease;
}
.kemetic-table tbody tr:hover {
    background: #1a1a1a;
    border-color: rgba(242,201,76,0.3);
}
.kemetic-table td {
    padding:14px;
    text-align:center;
    vertical-align:middle;
}
.kemetic-table td.text-left { 
    text-align:left; 
}
.kemetic-table td.text-right {
    text-align: right;
}

/* USER AVATAR */
.user-avatar-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}
.user-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid rgba(242,201,76,0.3);
    background: #1a1a1a;
}
.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.user-info {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}
.user-name {
    color: #fff;
    font-weight: 600;
    font-size: 14px;
}
.user-email {
    color: #888;
    font-size: 11px;
}

/* QUIZ INFO */
.quiz-title {
    color: #F2C94C;
    font-weight: 600;
    font-size: 15px;
    margin-bottom: 4px;
}
.quiz-course {
    color: #888;
    font-size: 12px;
}

/* GRADE */
.quiz-grade {
    color: #F2C94C;
    font-weight: 600;
    font-size: 16px;
}
.user-grade {
    color: #fff;
    font-weight: 600;
    font-size: 16px;
}

/* STATUS BADGES */
.status-badge {
    padding:4px 10px;
    border-radius:12px;
    font-size:12px;
    font-weight: 500;
    display: inline-block;
}
.status-badge.passed {
    background: #1f3d2b;
    color: #2ecc71;
}
.status-badge.failed {
    background: #3d1f1f;
    color: #e74c3c;
}
.status-badge.waiting {
    background: #3d2e1f;
    color: #f39c12;
}
.chance-remained {
    color: #888;
    font-size: 11px;
    margin-top: 2px;
}

/* DATE */
.quiz-date {
    color: #b5b5b5;
    font-size: 14px;
    font-weight: 500;
}

/* DROPDOWN */
.kemetic-actions {
    position: relative;
}
.kemetic-actions button {
    background: transparent;
    border: none;
    color: #F2C94C;
    padding: 5px 10px;
}
.kemetic-actions .dropdown-menu {
    background: #121212;
    border: 1px solid #262626;
    border-radius: 12px;
    padding: 8px 0;
    min-width: 180px;
}
.kemetic-actions .dropdown-item {
    color: #F2C94C;
    padding: 8px 16px;
    font-size: 13px;
    transition: 0.2s ease;
    background: transparent;
    border: none;
    width: 100%;
    text-align: left;
}
.kemetic-actions .dropdown-item:hover {
    background: rgba(242,201,76,0.1);
    color: #fff;
}

/* SWITCH */
.kemetic-switch-wrapper {
    display: flex;
    align-items: center;
    gap: 12px;
}
.kemetic-switch-label {
    color: #b5b5b5;
    font-size: 14px;
}
.kemetic-switch {
    position: relative;
    width: 46px;
    height: 24px;
}
.kemetic-switch input {
    display: none;
}
.kemetic-slider {
    position: absolute;
    inset: 0;
    background: #2a2a2a;
    border-radius: 30px;
    cursor: pointer;
}
.kemetic-slider:before {
    content: "";
    position: absolute;
    width: 18px;
    height: 18px;
    background: #F2C94C;
    border-radius: 50%;
    top: 3px;
    left: 4px;
    transition: .3s;
}
.kemetic-switch input:checked + .kemetic-slider:before {
    transform: translateX(20px);
}

/* NO RESULT */
.no-result-card {
    background: linear-gradient(180deg,#121212,#0a0a0a);
    border:1px solid #262626;
    border-radius:18px;
    padding:60px 40px;
    text-align: center;
}
.no-result-card img {
    opacity: 0.7;
    margin-bottom: 20px;
    width: 120px;
}
.no-result-card h3 {
    color: #F2C94C;
    font-size: 20px;
    margin-bottom: 10px;
}
.no-result-card p {
    color: #888;
    font-size: 14px;
    max-width: 400px;
    margin: 0 auto;
}

/* TEXT COLORS */
.text-gray {
    color: #888 !important;
}
</style>

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
@endpush

@section('content')

    {{-- Statistics --}}
    <section class="kemetic-stat-section">
        <h2 class="kemetic-title">{{ trans('quiz.results_statistics') }}</h2>

        <div class="kemetic-stat-card">
            <div class="row text-center">
                <div class="col-6 col-md-3">
                    <div class="kemetic-stat-item">
                        <div class="kemetic-stat-icon">
                            <img src="/assets/default/img/activity/42.svg" alt="">
                        </div>
                        <div class="kemetic-stat-value">{{ $quizzesResultsCount }}</div>
                        <div class="kemetic-stat-label">{{ trans('quiz.quizzes') }}</div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="kemetic-stat-item">
                        <div class="kemetic-stat-icon">
                            <img src="/assets/default/img/activity/45.svg" alt="">
                        </div>
                        <div class="kemetic-stat-value">{{ $passedCount }}</div>
                        <div class="kemetic-stat-label">{{ trans('public.passed') }}</div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="kemetic-stat-item">
                        <div class="kemetic-stat-icon">
                            <img src="/assets/default/img/activity/44.svg" alt="">
                        </div>
                        <div class="kemetic-stat-value">{{ $failedCount }}</div>
                        <div class="kemetic-stat-label">{{ trans('quiz.failed') }}</div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="kemetic-stat-item">
                        <div class="kemetic-stat-icon">
                            <img src="/assets/default/img/activity/43.svg" alt="">
                        </div>
                        <div class="kemetic-stat-value">{{ $waitingCount }}</div>
                        <div class="kemetic-stat-label">{{ trans('quiz.open_results') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Filters --}}
    <section class="kemetic-filter-section mt-30">
        <h2 class="kemetic-title">{{ trans('quiz.filter_results') }}</h2>

        <div class="kemetic-filter-card mt-20">
            <form action="/panel/quizzes/my-results" method="get">
                <div class="row g-3">

                    {{-- Date range --}}
                    <div class="col-12 col-lg-4">
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="kemetic-label">{{ trans('public.from') }}</label>
                                <div class="kemetic-input-group">
                                    <!-- <i data-feather="calendar" width="18" height="18"></i> -->
                                    <input type="date" name="from"
                                        class="kemetic-input"
                                        value="{{ request()->get('from') }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="kemetic-label">{{ trans('public.to') }}</label>
                                <div class="kemetic-input-group">
                                    <!-- <i data-feather="calendar" width="18" height="18"></i> -->
                                    <input type="date" name="to"
                                        class="kemetic-input"
                                        value="{{ request()->get('to') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Quiz & Instructor filters --}}
                    <div class="col-12 col-lg-6">
                        <div class="row g-2">
                            <div class="col-12 col-lg-5">
                                <label class="kemetic-label">{{ trans('quiz.quiz_or_webinar') }}</label>
                                <div class="kemetic-input-group">
                                    <i data-feather="book-open" width="18" height="18"></i>
                                    <input type="text" name="quiz_or_webinar" 
                                        class="kemetic-input"
                                        value="{{ request()->get('quiz_or_webinar','') }}"
                                        placeholder="{{ trans('public.search') }}">
                                </div>
                            </div>

                            <div class="col-12 col-lg-7">
                                <div class="row g-2">
                                    <div class="col-12 col-lg-7">
                                        <label class="kemetic-label">{{ trans('public.instructor') }}</label>
                                        <div class="kemetic-input-group">
                                            <i data-feather="user" width="18" height="18"></i>
                                            <input type="text" name="instructor" 
                                                class="kemetic-input"
                                                value="{{ request()->get('instructor','') }}"
                                                placeholder="{{ trans('public.search') }}">
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-5">
                                        <label class="kemetic-label">{{ trans('public.status') }}</label>
                                        <select name="status" class="kemetic-select">
                                            <option value="all">{{ trans('public.all') }}</option>
                                            <option value="passed" {{ request()->get('status') === 'passed' ? 'selected' : '' }}>{{ trans('quiz.passed') }}</option>
                                            <option value="failed" {{ request()->get('status') === 'failed' ? 'selected' : '' }}>{{ trans('quiz.failed') }}</option>
                                            <option value="waiting" {{ request()->get('status') === 'waiting' ? 'selected' : '' }}>{{ trans('quiz.waiting') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="col-12 col-lg-2 d-flex align-items-end">
                        <button type="submit" class="kemetic-btn w-100">
                            {{ trans('public.show_results') }}
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </section>

    {{-- Quiz Results List --}}
    <section class="kemetic-section mt-40">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-20">
            <h2 class="kemetic-title">{{ trans('quiz.my_quizzes') }}</h2>

            <form action="" method="get" class="mt-15 mt-md-0">
                <div class="kemetic-switch-wrapper">
                    <span class="kemetic-switch-label">{{ trans('quiz.show_only_open_results') }}</span>
                    <label class="kemetic-switch">
                        <input type="checkbox" name="open_results" onchange="this.form.submit()"
                            {{ request()->get('open_results') == 'on' ? 'checked' : '' }}>
                        <span class="kemetic-slider"></span>
                    </label>
                </div>
            </form>
        </div>

        @if($quizzesResults->count() > 0)

            <div class="kemetic-table-card mt-20">
                <div class="table-responsive">
                    <table class="kemetic-table">
                        <thead>
                            <tr>
                                <th class="text-left">{{ trans('public.instructor') }}</th>
                                <th class="text-left">{{ trans('quiz.quiz') }}</th>
                                <th>{{ trans('quiz.quiz_grade') }}</th>
                                <th>{{ trans('quiz.my_grade') }}</th>
                                <th>{{ trans('public.status') }}</th>
                                <th>{{ trans('public.date') }}</th>
                                <th class="text-right"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quizzesResults as $result)
                                <tr>
                                    <td class="text-left">
                                        <div class="user-avatar-cell">
                                            <div class="user-avatar">
                                                <img src="{{ $result->quiz->creator->getAvatar() }}" alt="">
                                            </div>
                                            <div class="user-info">
                                                <span class="user-name">{{ $result->quiz->creator->full_name }}</span>
                                                <span class="user-email">{{ $result->quiz->creator->email }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-left">
                                        <div class="quiz-title">{{ $result->quiz->title }}</div>
                                        <div class="quiz-course">{{ $result->quiz->webinar->title }}</div>
                                    </td>

                                    <td>
                                        <span class="quiz-grade">{{ $result->quiz->quizQuestions->sum('grade') }}</span>
                                    </td>

                                    <td>
                                        <span class="user-grade">{{ $result->user_grade }}</span>
                                    </td>

                                    <td>
                                        @if($result->status == 'passed')
                                            <span class="status-badge passed">{{ trans('quiz.passed') }}</span>
                                        @elseif($result->status == 'failed')
                                            <span class="status-badge failed">{{ trans('quiz.failed') }}</span>
                                        @else
                                            <span class="status-badge waiting">{{ trans('quiz.waiting') }}</span>
                                        @endif
                                        @if($result->status == 'failed' && $result->can_try)
                                            <div class="chance-remained">{{ trans('quiz.quiz_chance_remained',['count'=>$result->count_can_try]) }}</div>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="quiz-date">{{ dateTimeFormat($result->created_at,'j M Y H:i') }}</span>
                                    </td>

                                    <td class="text-right">
                                        <div class="dropdown kemetic-actions">
                                            <button type="button" data-toggle="dropdown">
                                                <i data-feather="more-vertical" height="18"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @if((!$result->can_try && $result->status!='waiting') || $result->status=='passed')
                                                    <a href="/panel/quizzes/{{ $result->id }}/result" class="dropdown-item">
                                                        {{ trans('public.view_answers') }}
                                                    </a>
                                                @endif
                                                @if($result->status!='passed' && $result->can_try)
                                                    <a href="/panel/quizzes/{{ $result->quiz->id }}/start" class="dropdown-item">
                                                        {{ trans('public.try_again') }}
                                                    </a>
                                                @endif
                                                <a href="{{ $result->quiz->webinar->getUrl() }}" class="dropdown-item" target="_blank">
                                                    {{ trans('webinars.webinar_page') }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="my-30">
                {{ $quizzesResults->appends(request()->input())->links('vendor.pagination.panel') }}
            </div>

        @else
            <div class="no-result-card">
                @include(getTemplate() . '.includes.no-result',[
                    'file_name' => 'result.png',
                    'title' => trans('quiz.quiz_result_no_result'),
                    'hint' => trans('quiz.quiz_result_no_result_hint'),
                ])
            </div>
        @endif
    </section>

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize datepickers
            if ($('.datefilter').length) {
                $('.datefilter').daterangepicker();
            }
            if ($('.datepicker').length) {
                $('.datepicker').daterangepicker({
                    singleDatePicker: true,
                    locale: { format: 'YYYY-MM-DD' }
                });
            }

            // Auto-submit switch
            $('input[name="open_results"]').on('change', function() {
                $(this).closest('form').submit();
            });
        });
    </script>

    <script src="/assets/default/js/panel/quiz_list.min.js"></script>
@endpush