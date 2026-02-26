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
    border-collapse: separate;
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

.kemetic-title-cell .text-muted,
.kemetic-title-cell small {
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
.kemetic-select,
.panel-section-card .form-control,
.form-control {
    background: #1a1a1a !important;
    color: var(--k-text) !important;
    border: 1px solid var(--k-border) !important;
    border-radius: 12px !important;
    height: 44px;
    padding: 0 15px;
    transition: all 0.25s ease;
}

.kemetic-input:focus,
.kemetic-select:focus,
.form-control:focus {
    border-color: var(--k-gold) !important;
    box-shadow: 0 0 8px var(--k-gold-soft) !important;
    outline: none;
    background: #1a1a1a !important;
}

.form-control::placeholder {
    color: var(--k-muted);
    opacity: 0.7;
}

select.form-control option {
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

.btn-transparent {
    color: var(--k-gold) !important;
    background: none;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
}

.btn-transparent:hover {
    color: #ffd700 !important;
}

/* =========================
   DROPDOWN / ACTIONS
========================= */
.table-actions {
    position: relative;
    display: inline-block;
}

.table-actions .dropdown-menu {
    background: #0f0f0f;
    border: 1px solid var(--k-border);
    border-radius: 12px;
    padding: 6px;
    min-width: 120px;
}

.table-actions .dropdown-item {
    color: var(--k-text);
    font-size: 13px;
    padding: 8px 12px;
    border-radius: 8px;
    transition: all 0.2s ease;
    text-align: left;
}

.table-actions .dropdown-item:hover {
    background: rgba(212, 175, 55, 0.12);
    color: var(--k-gold);
}

/* =========================
   CERTIFICATE ID
========================= */
.kemetic-cert-id {
    color: var(--k-gold);
    font-weight: 600;
    font-size: 14px;
    background: rgba(212,175,55,0.1);
    padding: 4px 8px;
    border-radius: 8px;
    display: inline-block;
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
    max-width: 120px;
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
<link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
@endpush

@section('content')
<div class="kemetic-page">

    {{-- ===== CERTIFICATE STATS ===== --}}
    <section>
        <h2 class="section-title">{{ trans('quiz.my_certificates_statistics') }}</h2>
        <div class="kemetic-stats mt-25">
            <div class="row text-center">
                <div class="col-4 stat-item">
                    <img src="/assets/default/img/activity/56.svg" width="60" alt="certificates">
                    <div class="stat-value mt-10">{{ $certificatesCount }}</div>
                    <div class="stat-label">{{ trans('panel.certificates') }}</div>
                </div>
                <div class="col-4 stat-item">
                    <img src="/assets/default/img/activity/hours.svg" width="60" alt="average grade">
                    <div class="stat-value mt-10">{{ $avgGrades }}</div>
                    <div class="stat-label">{{ trans('quiz.average_grade') }}</div>
                </div>
                <div class="col-4 stat-item">
                    <img src="/assets/default/img/activity/60.svg" width="60" alt="failed quizzes">
                    <div class="stat-value mt-10">{{ $failedQuizzes }}</div>
                    <div class="stat-label">{{ trans('quiz.failed_quizzes') }}</div>
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
                                <select name="webinar_id" class="form-control kemetic-select select2">
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
                                        <select id="quizFilter" name="quiz_id" class="form-control kemetic-select select2" {{ empty(request()->get('quiz_id')) ? 'disabled' : '' }}>
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
                                        <input type="text" name="grade" value="{{ request()->get('grade','') }}" class="form-control">
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

    {{-- ===== CERTIFICATES TABLE ===== --}}
    <section class="mt-35">
        <h2 class="section-title">{{ trans('quiz.my_certificates') }}</h2>

        @if(!empty($quizzes) && count($quizzes))
            <div class="kemetic-table-card">
                <div class="table-responsive">
                    <table class="kemetic-table">
                        <thead>
                            <tr>
                                <th class="text-left">{{ trans('public.certificate') }}</th>
                                <th>{{ trans('public.certificate_id') }}</th>
                                <th>{{ trans('quiz.minimum_grade') }}</th>
                                <th>{{ trans('quiz.average_grade') }}</th>
                                <th>{{ trans('quiz.my_grade') }}</th>
                                <th>{{ trans('public.date') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quizzes as $quiz)
                                <tr>
                                    <td class="text-left kemetic-title-cell" data-label="{{ trans('public.certificate') }}">
                                        <span class="title">{{ $quiz->title }}</span>
                                        <span class="text-muted">{{ $quiz->webinar->title }}</span>
                                    </td>
                                    
                                    <td data-label="{{ trans('public.certificate_id') }}">
                                        @if($quiz->can_download_certificate)
                                            @php $getUserCertificate = $quiz->getUserCertificate($authUser,$quiz->result); @endphp
                                            <span class="kemetic-cert-id">{{ $getUserCertificate->id ?? '-' }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    
                                    <td data-label="{{ trans('quiz.minimum_grade') }}">{{ $quiz->pass_mark }}</td>
                                    <td data-label="{{ trans('quiz.average_grade') }}">{{ $quiz->total_mark }}</td>
                                    <td data-label="{{ trans('quiz.my_grade') }}">{{ $quiz->result->user_grade }}</td>
                                    
                                    <td data-label="{{ trans('public.date') }}" class="kemetic-date">
                                        {{ dateTimeFormat($quiz->result->created_at,'j M Y') }}
                                    </td>
                                    
                                    <td>
                                        @if($quiz->can_download_certificate)
                                            <div class="btn-group dropdown table-actions">
                                                <button class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                    <i data-feather="more-vertical" height="20"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a href="/panel/quizzes/results/{{ $quiz->result->id }}/showCertificate" target="_blank" class="dropdown-item">
                                                        {{ trans('public.open') }}
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
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
                <img src="/assets/default/img/no-results/cert.png" alt="{{ trans('quiz.my_certificates_no_result') }}">
                <h3>{{ trans('quiz.my_certificates_no_result') }}</h3>
                <p>{{ trans('quiz.my_certificates_no_result_hint') }}</p>
            </div>
        @endif
    </section>

</div>
@endsection

@push('scripts_bottom')
<script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
<script src="/assets/default/vendors/select2/select2.min.js"></script>
<script src="/assets/default/js/panel/certificates.min.js"></script>
@endpush