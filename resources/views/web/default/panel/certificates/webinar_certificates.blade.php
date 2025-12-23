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
    margin-bottom: 15px;
}

/* ===== FORM CARD ===== */
.panel-section-card {
    background: var(--k-card);
    border-radius: var(--k-radius);
    border: 1px solid var(--k-border);
    box-shadow: var(--k-shadow);
    padding: 25px;
}

/* ===== INPUT FIELDS ===== */
.form-control {
    background: #1a1a1a;
    color: var(--k-text);
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius);
}
.form-control:focus {
    border-color: var(--k-gold);
    box-shadow: 0 0 6px var(--k-gold-soft);
    background: #1a1a1a;
    color: var(--k-text);
}

/* ===== TABLE ===== */
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

/* ===== DROPDOWN BUTTON ===== */
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

    {{-- ===== FILTER CERTIFICATES ===== --}}
    <section class="mt-25">
        <h2 class="section-title">{{ trans('quiz.filter_certificates') }}</h2>
        <div class="panel-section-card">
            <form action="" method="get" class="row">
                <div class="col-12 col-lg-6">
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
                <div class="col-12 col-lg-2">
                     <label></label>
                    <button type="submit" class="btn kemetic-btn w-100">
                        {{ trans('public.show_results') }}
                    </button>
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
                    <table class="table text-center custom-table">
                        <thead>
                            <tr>
                                <th>{{ trans('cart.item') }}</th>
                                <th>{{ trans('public.type') }}</th>
                                <th>{{ trans('public.certificate_id') }}</th>
                                <th>{{ trans('public.date') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($certificates as $certificate)
                                <tr>
                                    <td class="text-left">
                                        <span class="d-block font-weight-500 text-dark-blue">
                                            @if(!empty($certificate->webinar_id))
                                                {{ $certificate->webinar->title }}
                                            @else
                                                {{ $certificate->bundle->title }}
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        {{ !empty($certificate->webinar_id) ? trans('product.course') : trans('update.bundle') }}
                                    </td>
                                    <td>{{ $certificate->id }}</td>
                                    <td>
                                        <span class="text-dark-blue font-weight-500">{{ dateTimeFormat($certificate->created_at, 'j M Y') }}</span>
                                    </td>
                                    <td class="font-weight-normal">
                                        <div class="btn-group dropdown table-actions">
                                            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                <i data-feather="more-vertical" height="20"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a href="/panel/certificates/{{ !empty($certificate->webinar_id) ? 'webinars' : 'bundles' }}/{{ $certificate->id }}/show" target="_blank" class="webinar-actions d-block">{{ trans('public.open') }}</a>
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
                    {{ $certificates->appends(request()->input())->links('vendor.pagination.panel') }}
                </div>
            </div>
        @else
            @include(getTemplate() . '.includes.no-result',[
                'file_name' => 'cert.png',
                'title' => trans('quiz.my_certificates_no_result'),
                'hint' => nl2br(trans('quiz.my_certificates_no_result_hint')),
            ])
        @endif
    </section>

</div>
@endsection

@push('scripts_bottom')
<script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
<script src="/assets/default/js/panel/certificates.min.js"></script>
@endpush
