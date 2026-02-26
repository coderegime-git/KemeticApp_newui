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

/* SELECT2 - KEMETIC DARK THEME */
.select2-container--default .select2-selection--single {
    background: #0d0d0d !important;
    border: 1px solid #2a2a2a !important;
    border-radius: 12px !important;
    height: 44px !important;
    display: flex;
    align-items: center;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #fff !important;
    line-height: 44px !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 42px !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: #F2C94C transparent transparent transparent !important;
}
.select2-dropdown {
    background: #0f0f0f !important;
    border: 1px solid #2a2a2a !important;
    border-radius: 12px !important;
}
.select2-results__option {
    color: #e0e0e0 !important;
    padding: 10px 14px !important;
}
.select2-results__option--highlighted {
    background: rgba(242,201,76,.15) !important;
    color: #fff !important;
}
.select2-results__option[aria-selected=true] {
    background: rgba(242,201,76,.25) !important;
}
.select2-search--dropdown .select2-search__field {
    background: #0d0d0d !important;
    border: 1px solid #2a2a2a !important;
    color: #fff !important;
    border-radius: 8px !important;
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
.kemetic-actions .dropdown-item.delete-action {
    color: #e74c3c !important;
}
.kemetic-actions .dropdown-item.delete-action:hover {
    background: rgba(231,76,60,0.1);
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
.custom-control {
  position: relative;
  z-index: 1;
  display: block;
  min-height: 1.3rem;
  padding-left: 2rem;
  -webkit-print-color-adjust: exact;
          print-color-adjust: exact;
}

.custom-control-inline {
  display: inline-flex;
  margin-right: 1rem;
}

.custom-control-input {
  position: absolute;
  left: 0;
  z-index: -1;
  width: 1.5rem;
  height: 1.4rem;
  opacity: 0;
}
.custom-control-input:checked ~ .custom-control-label::before {
  color: #ffffff;
  border-color: #f2c94c;
  background-color: #f2c94c;
    box-shadow: 0 0 10px rgba(242, 201, 76, 0.45);
}
.custom-control-input:focus ~ .custom-control-label::before {
  box-shadow: none, 1.5rem;
}
.custom-control-input:focus:not(:checked) ~ .custom-control-label::before {
  border-color: #43d477;
}
.custom-control-input:not(:disabled):active ~ .custom-control-label::before {
  color: #ffffff;
  background-color: #43d477;
  border-color: #43d477;
}
.custom-control-input[disabled] ~ .custom-control-label, .custom-control-input:disabled ~ .custom-control-label {
  color: #6c757d;
}
.custom-control-input[disabled] ~ .custom-control-label::before, .custom-control-input:disabled ~ .custom-control-label::before {
  background-color: #f1f1f1;
}

.custom-control-label {
  position: relative;
  margin-bottom: 0;
  vertical-align: top;
}
.custom-control-label::before {
  position: absolute;
  top: -0.1rem;
  left: -2rem;
  display: block;
  width: 1.5rem;
  height: 1.5rem;
  pointer-events: none;
  content: "";
  background-color: #ffffff;
  border: 2px solid #adb5bd;
  box-shadow: none;
}
.custom-control-label::after {
  position: absolute;
  top: -0.1rem;
  left: -2rem;
  display: block;
  width: 1.5rem;
  height: 1.5rem;
  content: "";
  background: 50%/50% 50% no-repeat;
}

.custom-checkbox .custom-control-label::before {
  border-radius: 0.25rem;
}
.custom-checkbox .custom-control-input:checked ~ .custom-control-label::after {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%23ffffff' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26l2.974 2.99L8 2.193z'/%3e%3c/svg%3e");
}
.custom-checkbox .custom-control-input:indeterminate ~ .custom-control-label::before {
  border-color: #F2C94C;
  background-color: #F2C94C;
}#
.custom-checkbox .custom-control-input:indeterminate ~ .custom-control-label::after {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='4' height='4' viewBox='0 0 4 4'%3e%3cpath stroke='%23ffffff' d='M0 2h4'/%3e%3c/svg%3e");
}
.custom-checkbox .custom-control-input:disabled:checked ~ .custom-control-label::before {
  background-color: #F2C94C;
}
.custom-checkbox .custom-control-input:disabled:indeterminate ~ .custom-control-label::before {
  background-color: #F2C94C;
}

.custom-radio .custom-control-label::before {
  border-radius: 50%;
}
.custom-radio .custom-control-input:checked ~ .custom-control-label::after {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23ffffff'/%3e%3c/svg%3e");
}
.custom-radio .custom-control-input:disabled:checked ~ .custom-control-label::before {
  background-color: rgba(67, 212, 119, 0.5);
}

.custom-switch {
  padding-left: 4.125rem;
}
.custom-switch .custom-control-label::before {
  left: -3.125rem;
  width: 2.625rem;
  pointer-events: all;
  border-radius: 0.75rem;
}
.custom-switch .custom-control-label::after {
  top: calc(-0.1rem + 4px);
  left: calc(-3.125rem + 4px);
  width: calc(1.5rem - 8px);
  height: calc(1.5rem - 8px);
  background-color: #adb5bd;
  border-radius: 0.75rem;
  transition: transform 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
@media (prefers-reduced-motion: reduce) {
  .custom-switch .custom-control-label::after {
    transition: none;
  }
}
.custom-switch .custom-control-input:checked ~ .custom-control-label::after {
  background-color: #ffffff;
  transform: translateX(1.125rem);
}
.custom-switch .custom-control-input:disabled:checked ~ .custom-control-label::before {
  background-color: rgba(67, 212, 119, 0.5);
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
                            <img src="/assets/default/img/activity/43.svg" alt="">
                        </div>
                        <div class="kemetic-stat-value">{{ $waitingCount }}</div>
                        <div class="kemetic-stat-label">{{ trans('quiz.need_to_review') }}</div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="kemetic-stat-item">
                        <div class="kemetic-stat-icon">
                            <img src="/assets/default/img/activity/42.svg" alt="">
                        </div>
                        <div class="kemetic-stat-value">{{ $quizResultsCount }}</div>
                        <div class="kemetic-stat-label">{{ trans('public.results') }}</div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="kemetic-stat-item">
                        <div class="kemetic-stat-icon">
                            <img src="/assets/default/img/activity/58.svg" alt="">
                        </div>
                        <div class="kemetic-stat-value">{{ $quizAvgGrad }}</div>
                        <div class="kemetic-stat-label">{{ trans('quiz.average_grade') }}</div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="kemetic-stat-item">
                        <div class="kemetic-stat-icon">
                            <img src="/assets/default/img/activity/45.svg" alt="">
                        </div>
                        <div class="kemetic-stat-value">{{ $successRate }}%</div>
                        <div class="kemetic-stat-label">{{ trans('quiz.success_rate') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Filters --}}
    <section class="kemetic-filter-section mt-30">
        <h2 class="kemetic-title">{{ trans('quiz.filter_results') }}</h2>

        <div class="kemetic-filter-card mt-20">
            <form action="/panel/quizzes/results" method="get">
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

                    {{-- Quiz & Student filters --}}
                    <div class="col-12 col-lg-6">
                        <div class="row g-2">
                            <div class="col-12 col-lg-5">
                                <label class="kemetic-label">{{ trans('quiz.quiz_or_webinar') }}</label>
                                <select name="quiz_id" class="kemetic-select select2" style="width: 100%;">
                                    <option value="all">{{ trans('public.all') }}</option>
                                    @foreach($quizzes as $quiz)
                                        <option value="{{ $quiz->id }}" {{ request()->get('quiz_id') == $quiz->id ? 'selected' : '' }}>
                                            {{ $quiz->title }} - {{ $quiz->webinar?->title ?? '-' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-lg-7">
                                <div class="row g-2">
                                    <div class="col-12 col-lg-7">
                                        <label class="kemetic-label">{{ trans('quiz.student') }}</label>
                                        <select name="user_id" class="kemetic-select select2" style="width: 100%;">
                                            <option value="all">{{ trans('public.all') }}</option>
                                            @foreach($allStudents as $student)
                                                <option value="{{ $student->id }}" {{ request()->get('user_id') == $student->id ? 'selected' : '' }}>
                                                    {{ $student->full_name }}
                                                </option>
                                            @endforeach
                                        </select>
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

    {{-- Student Results List --}}
    <section class="kemetic-section mt-40">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-20">
            <h2 class="kemetic-title">{{ trans('quiz.student_results') }}</h2>

            <form action="/panel/quizzes/results" method="get" class="mt-15 mt-md-0">
                <div class="kemetic-switch-wrapper">
                    <span class="kemetic-switch-label">{{ trans('quiz.show_only_open_results') }}</span>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="open_results" 
                               class="custom-control-input" 
                               id="onlyOpenQuizzesSwitch"
                               onchange="this.form.submit()"
                               {{ request()->get('open_results') == 'on' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="onlyOpenQuizzesSwitch"></label>
                    </div>
                </div>
            </form>
        </div>

        @if($quizzesResults->count() > 0)

            <div class="kemetic-table-card mt-20">
                <div class="table-responsive">
                    <table class="kemetic-table">
                        <thead>
                            <tr>
                                <th class="text-left">{{ trans('quiz.student') }}</th>
                                <th class="text-left">{{ trans('quiz.quiz') }}</th>
                                <th>{{ trans('quiz.quiz_grade') }}</th>
                                <th>{{ trans('quiz.student_grade') }}</th>
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
                                                <img src="{{ $result->user->getAvatar() }}" alt="">
                                            </div>
                                            <div class="user-info">
                                                <span class="user-name">{{ $result->user->full_name }}</span>
                                                <span class="user-email">{{ $result->user->email }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-left">
                                        <div class="quiz-title">{{ $result->quiz->title }}</div>
                                        <div class="quiz-course">{{ $result->quiz->webinar->title }}</div>
                                    </td>

                                    <td>
                                        <span class="quiz-grade">{{ $result->getQuestions()->sum('grade') }}</span>
                                    </td>

                                    <td>
                                        <span class="user-grade">{{ $result->user_grade }}</span>
                                    </td>

                                    <td>
                                        @switch($result->status)
                                            @case(\App\Models\QuizzesResult::$passed)
                                                <span class="status-badge passed">{{ trans('quiz.passed') }}</span>
                                                @break
                                            @case(\App\Models\QuizzesResult::$failed)
                                                <span class="status-badge failed">{{ trans('quiz.failed') }}</span>
                                                @break
                                            @case(\App\Models\QuizzesResult::$waiting)
                                                <span class="status-badge waiting">{{ trans('quiz.waiting') }}</span>
                                                @break
                                        @endswitch
                                    </td>

                                    <td>
                                        <span class="quiz-date">{{ dateTimeFormat($result->created_at, 'j M Y H:i') }}</span>
                                    </td>

                                    <td class="text-right">
                                        <div class="dropdown kemetic-actions">
                                            <button type="button" data-toggle="dropdown">
                                                <i data-feather="more-vertical" height="18"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @if($result->status != 'waiting')
                                                    <a href="/panel/quizzes/{{ $result->id }}/result" class="dropdown-item">
                                                        {{ trans('public.view') }}
                                                    </a>
                                                @endif
                                                @if($result->status == 'waiting')
                                                    <a href="/panel/quizzes/{{ $result->id }}/edit-result" class="dropdown-item">
                                                        {{ trans('public.review') }}
                                                    </a>
                                                @endif
                                                <a href="/panel/quizzes/results/{{ $result->id }}/delete" class="dropdown-item delete-action">
                                                    {{ trans('public.delete') }}
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
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                width: '100%',
                theme: 'default'
            });

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
        });
    </script>

    <script src="/assets/default/js/panel/quiz_list.min.js"></script>
@endpush