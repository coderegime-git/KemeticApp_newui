@extends('web.default.layouts.newapp')
<style>
  /* KEMETIC STATS */

.kemetic-section {
    margin-top: 30px;
}

.kemetic-section-title {
    font-size: 20px;
    font-weight: 700;
    color: #F2C94C;
    margin-bottom: 20px;
}

/* Container */
.kemetic-stats-container {
    background: #141414;
    border: 1px solid rgba(242,201,76,0.25);
    border-radius: 16px;
    padding: 30px;
}

/* Card */
.kemetic-stat-card {
    background: #0F0F0F;
    border: 1px solid rgba(242,201,76,0.2);
    border-radius: 14px;
    padding: 25px 15px;
    text-align: center;
    transition: all .3s ease;
}

.kemetic-stat-card:hover {
    border-color: #F2C94C;
    transform: translateY(-4px);
}

/* Icon */
.kemetic-stat-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 10px;
    background: rgba(242,201,76,0.12);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.kemetic-stat-icon img {
    width: 36px;
    height: 36px;
}

/* Value */
.kemetic-stat-value {
    font-size: 32px;
    font-weight: 800;
    color: #ffffff;
    margin-top: 8px;
}

/* Label */
.kemetic-stat-label {
    font-size: 14px;
    color: #b5b5b5;
    margin-top: 4px;
}
.kemetic-filter-card {
    border-radius: 14px;
    background: #ffffff;
    box-shadow: 0 6px 20px rgba(0,0,0,0.04);
}

.kemetic-input .input-with-icon {
    position: relative;
}

.kemetic-input .input-with-icon i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    width: 18px;
    height: 18px;
    color: #6c757d;
}

.kemetic-input .input-with-icon input {
    padding-left: 42px;
}

.filter-group-title {
    font-size: 13px;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
}

.kemetic-btn {
    border-radius: 10px;
    font-weight: 600;
}

/* Kemetic Filter Card */
.kemetic-filter-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 24px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
}

/* Labels */
.kemetic-label {
    font-size: 13px;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 6px;
}

/* Input Group */
.kemetic-input-group {
    position: relative;
}

.kemetic-input-group i {
    position: absolute;
    top: 50%;
    left: 12px;
    transform: translateY(-50%);
    color: #6b7280;
    width: 16px;
    height: 16px;
}

.kemetic-input-group input {
    padding-left: 38px;
    height: 44px;
    border-radius: 10px;
}

/* Inputs */
.form-control {
    height: 44px;
    border-radius: 10px;
    font-size: 14px;
}

/* Button */
.kemetic-btn-primary {
    height: 44px;
    border-radius: 10px;
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    border: none;
    color: #fff;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
}

.kemetic-btn-primary i {
    width: 18px;
    height: 18px;
}

:root {
    --k-bg: #0f0f0f;
    --k-card: #141414;
    --k-gold: #f2c94c;
    --k-border: rgba(242,201,76,.35);
    --k-text: #e0e0e0;
    --k-shadow: 0 0 20px rgba(242,201,76,.15);
    --k-radius: 14px;
}

/* SECTION */
.kemetic-section {
    padding: 10px;
}

/* TITLE */
.kemetic-title {
    font-size: 20px;
    color: var(--k-gold);
    border-left: 4px solid var(--k-gold);
    padding-left: 12px;
    font-weight: 600;
}

/* CARD */
.kemetic-card {
    background: var(--k-card);
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius);
    padding: 20px 25px;
    box-shadow: var(--k-shadow);
}

/* FORM */
.kemetic-form-group {
    margin-bottom: 15px;
}

.kemetic-label {
    font-size: 13px;
    color: var(--k-gold);
    margin-bottom: 6px;
    display: block;
}

/* INPUT GROUP */
.kemetic-input-group {
    display: flex;
    align-items: center;
    background: #0d0d0d;
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius);
    transition: .3s;
}

.kemetic-input-group:hover {
    box-shadow: 0 0 15px rgba(242,201,76,.2);
}

.kemetic-icon {
    padding: 0 12px;
    color: var(--k-gold);
}

.kemetic-input {
    background: transparent;
    border: none;
    color: var(--k-text);
    padding: 10px;
    width: 100%;
}

.kemetic-input:focus {
    outline: none;
}

/* SELECT */
.kemetic-select {
    width: 100%;
    background: #0d0d0d;
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius);
    color: var(--k-text);
    padding: 10px;
}

/* BUTTON */
.kemetic-btn {
    background: linear-gradient(135deg, #f2c94c, #d4a62f);
    color: #000;
    border: none;
    padding: 11px;
    border-radius: var(--k-radius);
    font-weight: 600;
    transition: .3s;
}

.kemetic-btn:hover {
    box-shadow: 0 0 25px rgba(242,201,76,.5);
    transform: translateY(-1px);
}

.kemetic-section {
    padding: 10px 0;
}

.kemetic-title {
    font-size: 20px;
    color: #f2c94c;
    border-left: 4px solid #f2c94c;
    padding-left: 12px;
    font-weight: 600;
}

/* CARD */
.kemetic-card {
    background: #0f0f0f;
    border: 1px solid rgba(242,201,76,.35);
    border-radius: 14px;
    padding: 20px 25px;
    box-shadow: 0 0 20px rgba(242,201,76,.15);
}

/* TABLE */
.kemetic-table {
    width: 100%;
    color: #e0e0e0;
    border-collapse: separate;
    border-spacing: 0;
}

.kemetic-table thead th {
    border-bottom: 1px solid rgba(242,201,76,.25);
    color: #f2c94c;
    font-weight: 600;
}

.kemetic-table tbody tr:hover {
    background: rgba(242,201,76,.05);
}

.kemetic-text {
    color: #f2c94c;
}

.kemetic-status.passed {
    color: #4cd137;
    font-weight: 600;
}

.kemetic-status.failed {
    color: #e84118;
    font-weight: 600;
}

.kemetic-status.waiting {
    color: #f39c12;
    font-weight: 600;
}

.kemetic-switch-label {
    color: #f2c94c;
    font-weight: 500;
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
</style>
@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
<link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
<link rel="stylesheet" href="/assets/default/css/kemetic-quiz-results.css">
@endpush

@section('content')
<section class="kemetic-section">
    <h2 class="kemetic-section-title">
        {{ trans('quiz.results_statistics') }}
    </h2>

    <div class="kemetic-stats-container">
        <div class="row">

            <!-- Need Review -->
            <div class="col-6 col-md-3">
                <div class="kemetic-stat-card">
                    <div class="kemetic-stat-icon">
                        <img src="/assets/default/img/activity/43.svg" alt="">
                    </div>

                    <div class="kemetic-stat-value">
                        {{ $waitingCount }}
                    </div>

                    <div class="kemetic-stat-label">
                        {{ trans('quiz.need_to_review') }}
                    </div>
                </div>
            </div>

            <!-- Results -->
            <div class="col-6 col-md-3">
                <div class="kemetic-stat-card">
                    <div class="kemetic-stat-icon">
                        <img src="/assets/default/img/activity/42.svg" alt="">
                    </div>

                    <div class="kemetic-stat-value">
                        {{ $quizResultsCount }}
                    </div>

                    <div class="kemetic-stat-label">
                        {{ trans('public.results') }}
                    </div>
                </div>
            </div>

            <!-- Average Grade -->
            <div class="col-6 col-md-3 mt-4 mt-md-0">
                <div class="kemetic-stat-card">
                    <div class="kemetic-stat-icon">
                        <img src="/assets/default/img/activity/58.svg" alt="">
                    </div>

                    <div class="kemetic-stat-value">
                        {{ $quizAvgGrad }}
                    </div>

                    <div class="kemetic-stat-label">
                        {{ trans('quiz.average_grade') }}
                    </div>
                </div>
            </div>

            <!-- Success Rate -->
            <div class="col-6 col-md-3 mt-4 mt-md-0">
                <div class="kemetic-stat-card">
                    <div class="kemetic-stat-icon">
                        <img src="/assets/default/img/activity/45.svg" alt="">
                    </div>

                    <div class="kemetic-stat-value">
                        {{ $successRate }}%
                    </div>

                    <div class="kemetic-stat-label">
                        {{ trans('quiz.success_rate') }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="kemetic-section mt-25">
    <h2 class="kemetic-title">
        {{ trans('quiz.filter_results') }}
    </h2>

    <div class="kemetic-card mt-20">
        <form action="/panel/quizzes/results" method="get" class="row">

            {{-- DATE FILTER --}}
            <div class="col-12 col-lg-4">
                <div class="row">

                    <div class="col-12 col-md-6">
                        <div class="kemetic-form-group">
                            <label class="kemetic-label">{{ trans('public.from') }}</label>
                            <div class="kemetic-input-group">
                                <span class="kemetic-icon">
                                    <i data-feather="calendar"></i>
                                </span>
                                <input type="text"
                                       name="from"
                                       autocomplete="off"
                                       class="kemetic-input @if(!empty(request()->get('from'))) datepicker @else datefilter @endif"
                                       value="{{ request()->get('from','') }}">
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
                                <input type="text"
                                       name="to"
                                       autocomplete="off"
                                       class="kemetic-input @if(!empty(request()->get('to'))) datepicker @else datefilter @endif"
                                       value="{{ request()->get('to','') }}">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- QUIZ + STUDENT --}}
            <div class="col-12 col-lg-6">
                <div class="row">

                    <div class="col-12 col-lg-5">
                        <div class="kemetic-form-group">
                            <label class="kemetic-label">{{ trans('quiz.quiz_or_webinar') }}</label>
                                <select name="quiz_id" class="kemetic-select select2">
                                    <option value="all">{{ trans('public.all') }}</option>
                                    @foreach($quizzes as $quiz)
                                        <option value="{{ $quiz->id }}" @selected(request()->get('quiz_id') == $quiz->id)>
                                            {{ $quiz->title }} - {{ $quiz->webinar?->title ?? '-' }}
                                        </option>
                                    @endforeach
                                </select>
                        </div>
                    </div>

                    <div class="col-12 col-lg-7">
                        <div class="row">

                            <div class="col-12 col-lg-7">
                                <div class="kemetic-form-group">
                                    <label class="kemetic-label">{{ trans('quiz.student') }}</label>
                                    <select name="user_id" class="kemetic-select select2">
                                        <option value="all">{{ trans('public.all') }}</option>
                                        @foreach($allStudents as $student)
                                            <option value="{{ $student->id }}" @selected(request()->get('user_id') == $student->id)>
                                                {{ $student->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-lg-5">
                                <div class="kemetic-form-group">
                                    <label class="kemetic-label">{{ trans('public.status') }}</label>
                                    <select name="status" class="kemetic-select">
                                        <option value="all">{{ trans('public.all') }}</option>
                                        <option value="passed" @selected(request()->get('status') === 'passed')>{{ trans('quiz.passed') }}</option>
                                        <option value="failed" @selected(request()->get('status') === 'failed')>{{ trans('quiz.failed') }}</option>
                                        <option value="waiting" @selected(request()->get('status') === 'waiting')>{{ trans('quiz.waiting') }}</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            {{-- BUTTON --}}
            <div class="col-12 col-lg-2 d-flex align-items-end">
                <button type="submit" class="kemetic-btn w-100">
                    {{ trans('public.show_results') }}
                </button>
            </div>

        </form>
    </div>
</section>



<section class="kemetic-section mt-35">

    {{-- HEADER + SWITCH --}}
    <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
        <h2 class="kemetic-title">{{ trans('quiz.student_results') }}</h2>

        <form action="/panel/quizzes/results" method="get" class="">
            <div class="d-flex align-items-center flex-row-reverse flex-md-row justify-content-start justify-content-md-center mt-20 mt-md-0">
                <label class="mb-0 mr-10 kemetic-switch-label" for="onlyOpenQuizzesSwitch">
                    {{ trans('quiz.show_only_open_results') }}
                </label>
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="open_results" class="custom-control-input" id="onlyOpenQuizzesSwitch" @if(request()->get('open_results',null) == 'on') checked @endif>
                    <label class="custom-control-label" for="onlyOpenQuizzesSwitch"></label>
                </div>
            </div>
        </form>
    </div>

    @if($quizzesResults->count() > 0)

        <div class="kemetic-card mt-20">
            <div class="table-responsive">
                <table class="table kemetic-table">
                    <thead>
                        <tr>
                            <th>{{ trans('quiz.student') }}</th>
                            <th>{{ trans('quiz.quiz') }}</th>
                            <th class="text-center">{{ trans('quiz.quiz_grade') }}</th>
                            <th class="text-center">{{ trans('quiz.student_grade') }}</th>
                            <th class="text-center">{{ trans('public.status') }}</th>
                            <th class="text-center">{{ trans('public.date') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quizzesResults as $result)
                            <tr>
                                <td>
                                    <div class="user-inline-avatar">
                                        <div class="avatar bg-gray200">
                                            <img src="{{ $result->user->getAvatar() }}" class="img-cover" alt="">
                                        </div>
                                        <div class="ml-5">
                                            <span class="d-block kemetic-text">{{ $result->user->full_name }}</span>
                                            <span class="font-12 text-gray d-block">{{ $result->user->email }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="d-block kemetic-text">{{ $result->quiz->title }}</span>
                                    <span class="font-12 text-gray d-block">{{ $result->quiz->webinar->title }}</span>
                                </td>

                                <td class="text-center">{{ $result->getQuestions()->sum('grade') }}</td>
                                <td class="text-center">{{ $result->user_grade }}</td>
                                <td class="text-center">
                                    @switch($result->status)
                                        @case(\App\Models\QuizzesResult::$passed)
                                            <span class="kemetic-status passed">{{ trans('quiz.passed') }}</span>
                                        @break
                                        @case(\App\Models\QuizzesResult::$failed)
                                            <span class="kemetic-status failed">{{ trans('quiz.failed') }}</span>
                                        @break
                                        @case(\App\Models\QuizzesResult::$waiting)
                                            <span class="kemetic-status waiting">{{ trans('quiz.waiting') }}</span>
                                        @break
                                    @endswitch
                                </td>
                                <td class="text-center">{{ dateTimeFormat($result->created_at, 'j M Y H:i') }}</td>
                                <td class="text-right">
                                    <div class="btn-group dropdown table-actions">
                                        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i data-feather="more-vertical" height="20"></i>
                                        </button>
                                        <div class="dropdown-menu font-weight-normal">
                                            @if($result->status != 'waiting')
                                                <a href="/panel/quizzes/{{ $result->id }}/result" class="webinar-actions d-block mt-10">{{ trans('public.view') }}</a>
                                            @endif
                                            @if($result->status == 'waiting')
                                                <a href="/panel/quizzes/{{ $result->id }}/edit-result" class="webinar-actions d-block mt-10">{{ trans('public.review') }}</a>
                                            @endif
                                            <a href="/panel/quizzes/results/{{ $result->id }}/delete" class="webinar-actions d-block mt-10 delete-action">{{ trans('public.delete') }}</a>
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
            'file_name' => 'result.png',
            'title' => trans('quiz.quiz_result_no_result'),
            'hint' => trans('quiz.quiz_result_no_result_hint'),
        ])
    @endif

</section>



    <div class="my-30">
        {{ $quizzesResults->appends(request()->input())->links('vendor.pagination.panel') }}
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/select2/select2.min.js"></script>

    <script src="/assets/default/js/panel/quiz_list.min.js"></script>
@endpush
