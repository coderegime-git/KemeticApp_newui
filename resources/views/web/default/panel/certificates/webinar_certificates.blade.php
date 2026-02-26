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
    margin-bottom: 15px;
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
   FORM CARD
========================= */
.kemetic-form-card {
    background: linear-gradient(180deg, #121212, #0a0a0a);
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius);
    padding: 25px;
    box-shadow: var(--k-shadow);
}

/* =========================
   FORM STYLING
========================= */
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
   ITEM TITLE CELL
========================= */
.kemetic-title-cell .title {
    color: #fff;
    font-weight: 600;
    display: block;
}

.kemetic-title-cell .font-12 {
    color: var(--k-muted) !important;
    font-size: 12px;
    display: block;
    margin-top: 5px;
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

/* =========================
   DROPDOWN / ACTIONS
========================= */
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
    padding: 4px 12px;
    border-radius: 20px;
    display: inline-block;
    border: 1px solid var(--k-border);
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
    font-weight: 500;
}

/* =========================
   TYPE BADGE
========================= */
.kemetic-type-badge {
    background: rgba(212,175,55,0.1);
    color: var(--k-gold);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid var(--k-border);
    display: inline-block;
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

    {{-- ===== FILTER CERTIFICATES ===== --}}
    <section class="mt-25">
        <h2 class="section-title">{{ trans('quiz.filter_certificates') }}</h2>
        <div class="kemetic-form-card">
            <form action="" method="get">
                <div class="row">
                    <div class="col-12 col-lg-6">
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

    {{-- ===== MY CERTIFICATES TABLE ===== --}}
    <section class="mt-35">
        <h2 class="section-title">{{ trans('quiz.my_certificates') }}</h2>

        @if(!empty($certificates) && count($certificates))
            <div class="kemetic-table-card">
                <div class="table-responsive">
                    <table class="kemetic-table">
                        <thead>
                            <tr>
                                <th class="text-left">{{ trans('cart.item') }}</th>
                                <th>{{ trans('public.type') }}</th>
                                <th>{{ trans('public.certificate_id') }}</th>
                                <th>{{ trans('public.date') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($certificates as $certificate)
                                <tr>
                                    <td class="text-left kemetic-title-cell" data-label="{{ trans('cart.item') }}">
                                        <span class="title">
                                            @if(!empty($certificate->webinar_id))
                                                {{ $certificate->webinar->title }}
                                            @else
                                                {{ $certificate->bundle->title }}
                                            @endif
                                        </span>
                                    </td>
                                    
                                    <td data-label="{{ trans('public.type') }}">
                                        <span class="kemetic-type-badge">
                                            {{ !empty($certificate->webinar_id) ? trans('product.course') : trans('update.bundle') }}
                                        </span>
                                    </td>
                                    
                                    <td data-label="{{ trans('public.certificate_id') }}">
                                        <span class="kemetic-cert-id">{{ $certificate->id }}</span>
                                    </td>
                                    
                                    <td data-label="{{ trans('public.date') }}" class="kemetic-date">
                                        {{ dateTimeFormat($certificate->created_at, 'j M Y') }}
                                    </td>
                                    
                                    <td>
                                        <div class="btn-group dropdown table-actions">
                                            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                <i data-feather="more-vertical" height="20"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a href="/panel/certificates/{{ !empty($certificate->webinar_id) ? 'webinars' : 'bundles' }}/{{ $certificate->id }}/show" target="_blank" class="dropdown-item">
                                                    {{ trans('public.open') }}
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
                @if($certificates->hasPages())
                    <div class="my-30">
                        {{ $certificates->appends(request()->input())->links('vendor.pagination.panel') }}
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