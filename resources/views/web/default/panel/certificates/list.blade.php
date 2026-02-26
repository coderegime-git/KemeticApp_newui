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
    position: relative;
}

.section-title::after {
    content: "";
    display: block;
    width: 70px;
    height: 1px;
    margin-top: 6px;
    background: linear-gradient(to right, var(--k-gold), transparent);
}

/* =========================
   STATS CARDS
========================= */
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
    font-weight: 700;
}

.stat-label {
    color: var(--k-muted);
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* =========================
   TABLE CARD
========================= */
.kemetic-table-card {
    background: linear-gradient(180deg, #121212, #0a0a0a);
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius);
    padding: 20px;
    box-shadow: var(--k-shadow);
    margin-top: 20px;
}

/* =========================
   TABLE
========================= */
.kemetic-table {
    width: 100%;
    border-collapse: collapse;
    border-spacing: 0 12px;
}

.kemetic-table thead th {
    background: transparent;
    color: var(--k-gold);
    font-size: 13px;
    font-weight: 600;
    text-align: center;
    border: none;
    padding: 15px;
}

.kemetic-table thead th.text-left {
    text-align: left;
}

.kemetic-table tbody tr {
    background: #0f0f0f;
    border: 1px solid var(--k-border);
    border-radius: 14px;
    transition: all 0.3s ease;
}

.kemetic-table tbody tr:hover {
    background: #151515;
    box-shadow: 0 10px 28px rgba(212, 175, 55, 0.12);
}

.kemetic-table tbody td,
.kemetic-table tbody th {
    border: none;
    padding: 16px 18px;
    vertical-align: middle;
    color: var(--k-text);
    text-align: center;
}

.kemetic-table tbody td.text-left,
.kemetic-table tbody th.text-left {
    text-align: left;
}

/* =========================
   COURSE/QUIZ TITLE CELL
========================= */
.kemetic-title-cell .title {
    color: #fff;
    font-weight: 600;
    display: block;
}

.kemetic-title-cell small,
.kemetic-title-cell .text-muted {
    color: var(--k-muted) !important;
    font-size: 12px;
    display: block;
    margin-top: 5px;
}

/* =========================
   FORM STYLING
========================= */
.kemetic-form-card {
    background: linear-gradient(180deg, #121212, #0a0a0a);
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius);
    padding: 25px;
    box-shadow: var(--k-shadow);
}

.kemetic-input,
.kemetic-select {
    background: #1a1a1a !important;
    color: var(--k-text) !important;
    border: 1px solid var(--k-border) !important;
    border-radius: 12px !important;
    height: 44px;
    padding: 0 15px;
    transition: all 0.25s ease;
}

.kemetic-input:focus,
.kemetic-select:focus {
    border-color: var(--k-gold) !important;
    box-shadow: 0 0 8px var(--k-gold-soft) !important;
    outline: none;
    background: #1a1a1a !important;
}

.kemetic-input::placeholder {
    color: var(--k-muted);
    opacity: 0.7;
}

.kemetic-select option {
    background: #1a1a1a;
    color: var(--k-text);
}

.form-group label {
    color: var(--k-gold);
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 8px;
    display: block;
    letter-spacing: 0.3px;
}

/* =========================
   BUTTONS
========================= */
.kemetic-btn {
    background: linear-gradient(135deg, #d4af37, #b8962e);
    color: #000;
    font-weight: 700;
    border-radius: 12px;
    height: 44px;
    border: none;
    transition: all .25s ease;
    padding: 0 20px;
    font-size: 14px;
    letter-spacing: 0.3px;
}

.kemetic-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(212,175,55,.35);
    color: #000;
}

.kemetic-btn-outline {
    background: transparent;
    border: 1px solid var(--k-border);
    color: var(--k-gold);
    height: 44px;
    border-radius: 12px;
    transition: all .25s ease;
}

.kemetic-btn-outline:hover {
    background: var(--k-gold-soft);
    border-color: var(--k-gold);
    color: var(--k-gold);
}

/* =========================
   NO RESULT
========================= */
.kemetic-no-result {
    background: #0f0f0f;
    border: 1px dashed var(--k-border);
    border-radius: 18px;
    padding: 60px 20px;
    text-align: center;
    margin-top: 20px;
}

.kemetic-no-result img {
    filter: brightness(0.9) sepia(0.3);
    opacity: 0.9;
}

.kemetic-no-result h3 {
    color: var(--k-gold);
    font-size: 20px;
    margin: 20px 0 10px;
}

.kemetic-no-result p {
    color: var(--k-muted);
    font-size: 14px;
    max-width: 400px;
    margin: 0 auto;
}

/* =========================
   PAGINATION
========================= */
.pagination .page-link {
    background: #111;
    color: var(--k-gold);
    border: 1px solid var(--k-border);
    border-radius: 10px;
    margin: 0 3px;
}

.pagination .page-item.active .page-link {
    background: var(--k-gold);
    border-color: var(--k-gold);
    color: #000;
}

.pagination .page-item.disabled .page-link {
    background: #1a1a1a;
    color: var(--k-muted);
    border-color: #2a2a2a;
}

/* =========================
   DATE
========================= */
.kemetic-date {
    font-size: 13px;
    color: var(--k-text);
}

/* =========================
   RESPONSIVE
========================= */
@media (max-width: 768px) {
    .kemetic-table thead {
        display: none;
    }

    .kemetic-table tbody tr {
        display: block;
        margin-bottom: 15px;
    }

    .kemetic-table tbody td,
    .kemetic-table tbody th {
        display: block;
        text-align: left;
        padding: 12px;
        position: relative;
    }

    .kemetic-table tbody td:before {
        content: attr(data-label);
        float: left;
        font-weight: 600;
        color: var(--k-gold);
        margin-right: 10px;
        min-width: 120px;
    }

    .stat-item {
        margin-bottom: 20px;
    }
}
</style>
@endpush

@section('content')
<div class="kemetic-page">

    {{-- ===== CERTIFICATES STATISTICS ===== --}}
    <section>
        <h2 class="section-title">{{ trans('quiz.certificates_statistics') }}</h2>
        <div class="kemetic-stats mt-25">
            <div class="row text-center">
                <div class="col-6 col-lg-3 stat-item">
                    <img src="/assets/default/img/activity/56.svg" width="60" alt="active certificates">
                    <div class="stat-value mt-10">{{ $activeQuizzes }}</div>
                    <div class="stat-label">{{ trans('quiz.active_certificates') }}</div>
                </div>
                <div class="col-6 col-lg-3 stat-item">
                    <img src="/assets/default/img/activity/57.svg" width="60" alt="student achievements">
                    <div class="stat-value mt-10">{{ $achievementsCount }}</div>
                    <div class="stat-label">{{ trans('quiz.student_achievements') }}</div>
                </div>
                <div class="col-6 col-lg-3 stat-item mt-5 mt-lg-0">
                    <img src="/assets/default/img/activity/60.svg" width="60" alt="failed students">
                    <div class="stat-value mt-10">{{ $failedResults }}</div>
                    <div class="stat-label">{{ trans('quiz.failed_students') }}</div>
                </div>
                <div class="col-6 col-lg-3 stat-item mt-5 mt-lg-0">
                    <img src="/assets/default/img/activity/hours.svg" width="60" alt="average grade">
                    <div class="stat-value mt-10">{{ $avgGrade }}</div>
                    <div class="stat-label">{{ trans('quiz.average_grade') }}</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== FILTER FORM ===== --}}
    <section class="mt-35">
        <h2 class="section-title">{{ trans('quiz.filter_certificates') }}</h2>
        <div class="kemetic-form-card mt-20">
            <form action="" method="get">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <div class="row">
                            <div class="col-12 col-md-6 form-group">
                                <label>{{ trans('public.from') }}</label>
                                <input type="date"
                                    class="form-control kemetic-input"
                                    name="from"
                                    value="{{ request()->get('from') }}">
                            </div>
                            <div class="col-12 col-md-6 form-group">
                                <label>{{ trans('public.to') }}</label>
                                <input type="date"
                                    class="form-control kemetic-input"
                                    name="to"
                                    value="{{ request()->get('to') }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-lg-6">
                        <div class="row">
                            <div class="col-12 col-lg-4 form-group">
                                <label>{{ trans('product.course') }}</label>
                                <select name="webinar_id" class="form-control kemetic-select">
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
                                        <select id="quizFilter" name="quiz_id" class="form-control kemetic-select" {{ empty(request()->get('quiz_id')) ? 'disabled' : '' }}>
                                            <option value="all">{{ trans('quiz.all_quizzes') }}</option>
                                            @foreach($userAllQuizzes as $userQuiz)
                                                <option value="{{ $userQuiz->id }}" data-webinar-id="{{ $userQuiz->webinar_id }}" 
                                                    {{ request()->get('quiz_id') == $userQuiz->id ? 'selected' : '' }}>
                                                    {{ $userQuiz->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-lg-6 form-group">
                                        <label>{{ trans('quiz.grade') }}</label>
                                        <input type="text" name="grade" value="{{ request()->get('grade','') }}" class="form-control kemetic-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-lg-2 d-flex align-items-end">
                        <div class="form-group w-100">
                            <label class="d-none d-lg-block">&nbsp;</label>
                            <button type="submit" class="btn kemetic-btn w-100">
                                {{ trans('public.show_results') }}
                            </button>
                        </div>
                    </div>
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
                    <table class="kemetic-table">
                        <thead>
                            <tr>
                                <th class="text-left">{{ trans('quiz.quiz') }}</th>
                                <th>{{ trans('quiz.grade') }}</th>
                                <th>{{ trans('quiz.average') }}</th>
                                <th>{{ trans('quiz.generated_certificates') }}</th>
                                <th>{{ trans('public.date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quizzes as $quiz)
                                <tr>
                                    <td class="text-left kemetic-title-cell" data-label="{{ trans('quiz.quiz') }}">
                                        <span class="title">{{ $quiz->title }}</span>
                                        @if(!empty($quiz->webinar))
                                            <small>{{ $quiz->webinar->title }}</small>
                                        @else
                                            <small class="text-muted">{{ trans('update.delete_item') }}</small>
                                        @endif
                                    </td>
                                    <td data-label="{{ trans('quiz.grade') }}">{{ $quiz->pass_mark }}</td>
                                    <td data-label="{{ trans('quiz.average') }}">{{ round($quiz->avg_grade, 2) }}</td>
                                    <td data-label="{{ trans('quiz.generated_certificates') }}">{{ count($quiz->certificates) }}</td>
                                    <td data-label="{{ trans('public.date') }}" class="kemetic-date">{{ dateTimeFormat($quiz->created_at, 'j M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                @if($quizzes->hasPages())
                    <div class="my-30">
                        {{ $quizzes->appends(request()->input())->links('vendor.pagination.panel') }}
                    </div>
                @endif
            </div>
        @else
            <div class="kemetic-no-result">
                <img src="/assets/default/img/no-results/certificate.png" alt="{{ trans('quiz.certificates_no_result') }}">
                <h3>{{ trans('quiz.certificates_no_result') }}</h3>
                <p>{{ trans('quiz.certificates_no_result_hint') }}</p>
            </div>
        @endif
    </section>

</div>
@endsection

@push('scripts_bottom')
<script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
<script src="/assets/default/js/panel/certificates.min.js"></script>
@endpush