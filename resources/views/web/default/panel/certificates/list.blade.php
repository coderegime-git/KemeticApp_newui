@extends('web.default.layouts.newapp')

@push('styles_top')
<style>
/* =========================
   KEMETIC APP DESIGN
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

/* ===== FORM STYLING ===== */
.panel-section-card .form-control {
    background: #1a1a1a;
    color: var(--k-text);
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius);
}

.panel-section-card .form-control:focus {
    border-color: var(--k-gold);
    box-shadow: 0 0 6px var(--k-gold-soft);
    background: #1a1a1a;
    color: var(--k-text);
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

/* ===== NO RESULT ===== */
.no-result .no-result-content {
    color: var(--k-muted);
}
.kemetic-btn {
    background: linear-gradient(135deg, #d4af37, #b8962e);
    color: #000;
    font-weight: 600;
    border-radius: 12px;
    height: 44px;
    border: none;
    transition: all .25s ease;
}

.kemetic-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(212,175,55,.35);
}
</style>
@endpush

@section('content')
<div class="kemetic-page">

    {{-- ===== CERTIFICATES STATISTICS ===== --}}
    <section>
        <h2 class="section-title">{{ trans('quiz.certificates_statistics') }}</h2>
        <div class="kemetic-stats mt-25 row text-center">
            <div class="col-6 col-lg-3 stat-item">
                <img src="/assets/default/img/activity/56.svg" width="60">
                <div class="stat-value mt-10">{{ $activeQuizzes }}</div>
                <div class="stat-label">{{ trans('quiz.active_certificates') }}</div>
            </div>
            <div class="col-6 col-lg-3 stat-item">
                <img src="/assets/default/img/activity/57.svg" width="60">
                <div class="stat-value mt-10">{{ $achievementsCount }}</div>
                <div class="stat-label">{{ trans('quiz.student_achievements') }}</div>
            </div>
            <div class="col-6 col-lg-3 stat-item mt-5 mt-lg-0">
                <img src="/assets/default/img/activity/60.svg" width="60">
                <div class="stat-value mt-10">{{ $failedResults }}</div>
                <div class="stat-label">{{ trans('quiz.failed_students') }}</div>
            </div>
            <div class="col-6 col-lg-3 stat-item mt-5 mt-lg-0">
                <img src="/assets/default/img/activity/hours.svg" width="60">
                <div class="stat-value mt-10">{{ $avgGrade }}</div>
                <div class="stat-label">{{ trans('quiz.average_grade') }}</div>
            </div>
        </div>
    </section>

    {{-- ===== FILTER FORM ===== --}}
    <section class="mt-25">
        <h2 class="section-title">{{ trans('quiz.filter_certificates') }}</h2>
        <div class="kemetic-table-card">
            <form action="" method="get" class="row">
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
                        <div class="col-12 col-lg-4 form-group">
                            <label>{{ trans('product.course') }}</label>
                            <select name="webinar_id" class="form-control">
                                <option value="all">{{ trans('webinars.all_courses') }}</option>
                                @foreach($userWebinars as $userWebinar)
                                    <option value="{{ $userWebinar->id }}" {{ request()->get('webinar_id') == $userWebinar->id ? 'selected' : '' }}>
                                        {{ $userWebinar->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-lg-8">
                            <div class="row">
                                <div class="col-12 col-lg-6 form-group">
                                    <label>{{ trans('quiz.quiz') }}</label>
                                    <select id="quizFilter" name="quiz_id" class="form-control" {{ empty(request()->get('quiz_id')) ? 'disabled' : '' }}>
                                        <option value="all">{{ trans('quiz.all_quizzes') }}</option>
                                        @foreach($userAllQuizzes as $userQuiz)
                                            <option value="{{ $userQuiz->id }}" data-webinar-id="{{ $userQuiz->webinar_id }}" 
                                                {{ request()->get('quiz_id') == $userQuiz->id ? 'selected' : 'class=d-none' }}>
                                                {{ $userQuiz->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6 form-group">
                                    <label>{{ trans('quiz.grade') }}</label>
                                    <input type="text" name="grade" value="{{ request()->get('grade','') }}" class="form-control"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-2">
                     <label></label>
                    <button type="submit" class="btn kemetic-btn w-100">
                        {{ trans('public.show_results') }}
                    </button>
                </div>
                
            </form>
        </div>
    </section>

    {{-- ===== ACTIVE CERTIFICATES TABLE ===== --}}
    <section class="mt-35">
        <h2 class="section-title">{{ trans('quiz.active_certificates') }}</h2>

        @if(!empty($quizzes) && count($quizzes))
            <div class="kemetic-table-card">
                <div class="table-responsive">
                    <table class="table text-center custom-table">
                        <thead>
                            <tr>
                                <th>{{ trans('quiz.quiz') }}</th>
                                <th>{{ trans('quiz.grade') }}</th>
                                <th>{{ trans('quiz.average') }}</th>
                                <th>{{ trans('quiz.generated_certificates') }}</th>
                                <th>{{ trans('public.date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quizzes as $quiz)
                                <tr>
                                    <td class="text-left">
                                        <span class="d-block">{{ $quiz->title }}</span>
                                        <span class="d-block font-12 text-muted mt-5">{{ !empty($quiz->webinar) ? $quiz->webinar->title : trans('update.delete_item') }}</span>
                                    </td>
                                    <td>{{ $quiz->pass_mark }}</td>
                                    <td>{{ round($quiz->avg_grade, 2) }}</td>
                                    <td>{{ count($quiz->certificates) }}</td>
                                    <td>{{ dateTimeFormat($quiz->created_at, 'j M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="my-30">
                    {{ $quizzes->appends(request()->input())->links('vendor.pagination.panel') }}
                </div>
            </div>
        @else
            @include(getTemplate() . '.includes.no-result',[
                'file_name' => 'certificate.png',
                'title' => trans('quiz.certificates_no_result'),
                'hint' => nl2br(trans('quiz.certificates_no_result_hint')),
            ])
        @endif
    </section>

</div>
@endsection

@push('scripts_bottom')
<script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
<script src="/assets/default/js/panel/certificates.min.js"></script>
@endpush
