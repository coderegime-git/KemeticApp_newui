@extends('web.default.layouts.newapp')

@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
<link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">

<style>
/* ===== Kemetic Theme ===== */
:root{
    --k-bg:#0f1115;
    --k-card:#161a22;
    --k-border:#262c3a;
    --k-gold:#F2C94C;
    --k-text:#e5e7eb;
    --k-muted:#9ca3af;
    --k-radius:16px;
}

.k-section-title{
    font-size:20px;
    font-weight:700;
    color:var(--k-gold);
}

.k-card{
    background:var(--k-card);
    border:1px solid var(--k-border);
    border-radius:var(--k-radius);
}

.k-label{
    color:var(--k-muted);
    font-size:13px;
    margin-bottom:6px;
}

.k-input,
.k-select{
    background:#0b0e14;
    border:1px solid var(--k-border);
    color:var(--k-text);
    border-radius:12px;
}

.k-input:focus,
.k-select:focus{
    border-color:var(--k-gold);
    box-shadow:none;
}

.k-btn{
    background:linear-gradient(135deg,#F2C94C,#E0B93D);
    color:#000;
    border:none;
    border-radius:12px;
    font-weight:600;
}

.k-table th{
    color:var(--k-muted);
    font-size:13px;
    border-bottom:1px solid var(--k-border);
}

.k-table td{
    color:var(--k-text);
    border-bottom:1px solid var(--k-border);
}

.badge-active{
    background:rgba(34,197,94,.15);
    color:#22c55e;
}
.badge-inactive{
    background:rgba(245,158,11,.15);
    color:#f59e0b;
}

/* ===============================
   SELECT2 – KEMETIC DARK THEME
================================ */

/* main box */
.select2-container--default .select2-selection--single {
    background: #0d0d0d !important;
    border: 1px solid rgba(242,201,76,.35) !important;
    border-radius: 14px !important;
    height: 30px !important;
    display: flex;
    align-items: center;
    color: #e0e0e0 !important;
}

/* text */
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #e0e0e0 !important;
    line-height: 30px !important;
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
  border-color: #43d477;
  background-color: #43d477;
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
 <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
@endpush

@section('content')

    <section>
        <h2 class="k-section-title">{{ trans('panel.create_a_new_discount') }}</h2>
        @if (\Session::has('msg'))
            <div class="alert alert-warning">
                <ul>
                    <li>{!! \Session::get('msg') !!}</li>
                </ul>
            </div>
        @endif

        <div class="k-card p-25 mt-20" style="padding: 10px;">
        <form action="/panel/marketing/special_offers/store" method="post" class="row">
            {{ csrf_field() }}

            <div class="col-12 col-lg-6">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label class="k-label">{{ trans('public.title') }}</label>
                            <input type="text" name="name" class="form-control k-input">
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="k-label">{{ trans('panel.webinar') }}</label>
                            <select name="webinar_id" class="form-control k-select select2">
                                <option disabled selected>{{ trans('panel.select_course') }}</option>
                                @foreach($webinars as $webinar)
                                    <option value="{{ $webinar->id }}">{{ $webinar->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="k-label">{{ trans('panel.amount') }} (%)</label>
                            <input type="text" name="percent" class="form-control k-input">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="k-label">{{ trans('public.from') }}</label>
                            <input type="date"
                                    class="form-control k-input text-center"
                                    name="from_date"
                                    value="{{ request()->get('from') }}">
                                    <!-- <span class="input-group-text kemetic-icon bg-gold text-black">
                                        <i data-feather="calendar" width="18" height="18"></i>
                                    </span> -->

                                    <!-- <input type="text" name="from" autocomplete="off"
                                        class="kemetic-input @if(request()->get('from')) datepicker @else datefilter @endif"
                                        value="{{ request()->get('from','') }}"> -->
                            <!-- <input type="text" name="from_date" class="k-input datetimepicker"> -->
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="k-label">{{ trans('public.to') }}</label>
                            <input type="date"
                                    class="form-control k-input text-center"
                                    name="to_date"
                                    value="{{ request()->get('to') }}">

                                <!-- <span class="input-group-text kemetic-icon bg-gold text-black">
                                    <i data-feather="calendar" width="18" height="18"></i>
                                </span> -->
                                
                                <!-- <input type="text" name="to" autocomplete="off"
                                    class="kemetic-input @if(request()->get('to')) datepicker @else datefilter @endif"
                                    value="{{ request()->get('to','') }}"> -->
                            <!-- <input type="text" name="to_date" class="k-input datetimepicker"> -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-1 d-flex align-items-end">
                <button type="submit" class="btn k-btn w-100">{{ trans('public.create') }}</button>
            </div>
        </form>
    </div>
</section>

    @if(!empty($specialOffers) and $specialOffers->count() > 0)

        <section class="mt-35">
            <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
                 <h2 class="k-section-title">{{ trans('panel.discounts') }}</h2>

                <form action="" method="get" class="d-flex align-items-center flex-row-reverse flex-md-row justify-content-start justify-content-md-center mt-20 mt-md-0">
                    <label class="cursor-pointer mb-0 mr-10 text-gray font-14 font-weight-500" for="activeDiscountsSwitch">{{ trans('panel.show_only_active_discounts') }}</label>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="active_discounts" class="js-panel-list-switch-filter custom-control-input" {{ request()->get('active_discounts', '') == 'on' ? 'checked' : '' }}
                        id="activeDiscountsSwitch">
                        <label class="custom-control-label" for="activeDiscountsSwitch"></label>
                    </div>
                </form>
            </div>

            <div class="panel-section-card py-20 px-25 mt-20">
                <div class="row">
                    <div class="col-12 ">
                        <div class="table-responsive">
                            <table class="custom-table text-center">
                                <thead>
                                <tr>
                                    <th class="text-center text-gray">{{ trans('panel.name') }}</th>
                                    <th class="text-center text-gray">{{ trans('panel.webinar') }}</th>
                                    <th class="text-center text-gray">{{ trans('panel.amount') }}</th>
                                    <th class="text-center text-gray">{{ trans('public.status') }}</th>
                                    <th class="text-center text-gray">{{ trans('public.from') }}</th>
                                    <th class="text-center text-gray">{{ trans('public.to') }}</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($specialOffers as $specialOffer)
                                    <tr>
                                        <td class="text-center align-middle text-dark-blue font-weight-500">{{ $specialOffer->name }}</td>
                                        <td class="text-center align-middle">
                                            <a href="{{ $specialOffer->webinar->getUrl() }}" class="text-dark-blue font-weight-500" target="_blank">{{ $specialOffer->webinar->title }}</a>
                                        </td>
                                        <td class="align-middle">
                                            <span class="text-dark-blue font-weight-500">{{ $specialOffer->percent }}%</span>
                                        </td>
                                        <td class="align-middle">
                                            @switch($specialOffer->status)
                                                @case(\App\Models\SpecialOffer::$active)
                                                <span class="text-primary font-weight-500">{{ trans('public.active') }}</span>

                                                @break
                                                @case(\App\Models\SpecialOffer::$inactive)
                                                <span class="text-warning font-weight-500">{{ trans('public.inactive') }}</span>
                                                @break
                                            @endswitch
                                        </td>
                                        <td class="align-middle text-dark-blue font-weight-500">{{ dateTimeFormat($specialOffer->from_date, 'j M Y | H:i') }}</td>
                                        <td class="align-middle text-dark-blue font-weight-500">{{ dateTimeFormat($specialOffer->to_date, 'j M Y | H:i') }}</td>
                                        <td class="text-right align-middle">
                                            @if($specialOffer->status != \App\Models\SpecialOffer::$inactive)
                                                <div class="btn-group dropdown table-actions">
                                                    <button type="button" class="btn-transparent dropdown-toggle"
                                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i data-feather="more-vertical" height="20"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a href="/panel/marketing/special_offers/{{ $specialOffer->id }}/disable" type="button"
                                                           class="delete-action btn-transparent">{{ trans('public.disable') }}</a>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <div class="my-30">
            {{ $specialOffers->appends(request()->input())->links('vendor.pagination.panel') }}
        </div>

    @else

        @include(getTemplate() . '.includes.no-result',[
            'file_name' => 'offer.png',
            'title' => trans('panel.discount_no_result'),
            'hint' =>  nl2br(trans('panel.discount_no_result_hint')) ,
        ])

    @endif
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/select2/select2.min.js"></script>

    <script>
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
    </script>

    <script src="/assets/default/js/panel/marketing/special_offers.min.js"></script>
@endpush
