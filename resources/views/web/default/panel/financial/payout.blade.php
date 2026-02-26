@extends('web.default.layouts.newapp')

@push('styles_top')
<style>
/* =============================
   KEMETIC FINANCIAL DASHBOARD
============================= */

.kemetic-card {
    background: #141414;
    color: #f5f5f5;
    border-radius: 18px;
    box-shadow: 0 12px 30px rgba(0,0,0,.7);
    padding: 25px;
    margin-bottom: 25px;
}

.kemetic-card h2.section-title {
    color: #d4af37;
    font-weight: 700;
    margin-bottom: 25px;
    letter-spacing: 0.6px;
}

.kemetic-card .activities-container {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    padding:10px;
}

.kemetic-card .activities-container .activity-box {
    background: #1e1e1e;
    border-radius: 14px;
    padding: 20px;
    flex: 1 1 calc(33.333% - 20px);
    text-align: center;
    box-shadow: 0 8px 20px rgba(0,0,0,.5);
}

.kemetic-card .activities-container img {
    width: 64px;
    height: 64px;
}

.kemetic-card .activities-container strong {
    color: #d4af37;
    font-size: 28px;
    margin-top: 10px;
    display: block;
}

.kemetic-card .activities-container span {
    color: #9a9a9a;
    font-size: 14px;
}

.request-payout {
    background: #d4af37;
    color: #141414;
    font-weight: 700;
    border-radius: 18px;
    padding: 10px 25px;
    transition: all 0.3s;
}

.request-payout:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.request-payout:hover:not(:disabled) {
    background: #b8952c;
}

.table.custom-table {
    background: #1e1e1e;
    border-radius: 14px;
    overflow: hidden;
    color: #f5f5f5;
}

.table.custom-table thead {
    background: #141414;
}

.table.custom-table th, .table.custom-table td {
    border: none !important;
    color: #f5f5f5;
}

.table.custom-table tbody tr:hover {
    background: rgba(212, 175, 55, 0.1);
}

.js-show-details {
    color: #d4af37;
    border: 1px solid #d4af37;
    border-radius: 14px;
    padding: 5px 10px;
    transition: all 0.3s;
}

.js-show-details:hover {
    background: #d4af37;
    color: #141414;
}

.not-verified-alert {
    background: #1e1e1e;
    border-left: 4px solid #d4af37;
    padding: 15px;
    font-weight: 500;
    border-radius: 14px;
}

#requestPayoutModal {
    background: #141414;
    color: #f5f5f5;
    padding: 30px;
    border-radius: 18px;
    max-width: 500px;
    margin: auto;
    box-shadow: 0 12px 40px rgba(0,0,0,.7);
}

#requestPayoutModal h3 {
    color: #d4af37;
    margin-bottom: 20px;
}

#requestPayoutModal .btn-primary {
    background: #d4af37;
    color: #141414;
    border-radius: 18px;
    font-weight: 700;
}

#requestPayoutModal .btn-danger {
    border-radius: 18px;
}

</style>
@endpush

@section('content')
<section class="kemetic-card">
    <h2 class="section-title">{{ trans('financial.account_summary') }}</h2>

    @if(!$authUser->financial_approval)
        <div class="not-verified-alert">
            {{ trans('panel.not_verified_alert') }}
           {{ trans('panel.this_link') }}.
        </div>
    @endif

    <div class="activities-container mt-25">
        <div class="activity-box">
            <img src="/assets/default/img/activity/36.svg" alt="">
            <strong>{{ $accountCharge ? handlePrice($accountCharge) : 0 }}</strong>
            <span>{{ trans('financial.account_charge') }}</span>
        </div>

        <div class="activity-box">
            <img src="/assets/default/img/activity/37.svg" alt="">
            <strong>{{ handlePrice($readyPayout ?? 0) }}</strong>
            <span>{{ trans('financial.ready_to_payout') }}</span>
        </div>

        <div class="activity-box">
            <img src="/assets/default/img/activity/38.svg" alt="">
            <strong>{{ handlePrice($totalIncome ?? 0) }}</strong>
            <span>{{ trans('financial.total_income') }}</span>
        </div>
    </div>
</section>

<div class="mt-45 text-center">
    <button type="button" @if(!$authUser->financial_approval) disabled @endif class="request-payout btn btn-sm">{{ trans('financial.request_payout') }}</button>
</div>

@if($payouts->count() > 0)
<section class="kemetic-card mt-35">
    <h2 class="section-title">{{ trans('financial.payouts_history') }}</h2>

    <div class="table-responsive mt-20">
        <table class="text-center custom-table">
            <thead>
                <tr>
                    <th class="text-center">{{ trans('financial.account') }}</th>
                    <th class="text-center">{{ trans('public.type') }}</th>
                    <th class="text-center">{{ trans('panel.amount') }} ({{ $currency }})</th>
                    <th class="text-center">{{ trans('public.status') }}</th>
                    <th class="text-center">{{ trans('admin/main.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payouts as $payout)
                <tr>
                    <td class="text-left">
                        @if(!empty($payout->userSelectedBank->bank))
                            <span class="d-block font-weight-500 text-dark-blue">{{ $payout->userSelectedBank->bank->title }}</span>
                        @else
                            <span class="d-block font-weight-500 text-dark-blue">-</span>
                        @endif
                        <span class="d-block font-12 text-gray mt-1">{{ dateTimeFormat($payout->created_at, 'j M Y | H:i') }}</span>
                    </td>
                    <td>{{ trans('public.manual') }}</td>
                    <td class="text-primary font-weight-bold">{{ handlePrice($payout->amount, false) }}</td>
                    <td>
                        @switch($payout->status)
                            @case(\App\Models\Payout::$waiting)
                                <span class="text-warning font-weight-bold">{{ trans('public.waiting') }}</span>
                                @break
                            @case(\App\Models\Payout::$reject)
                                <span class="text-danger font-weight-bold">{{ trans('public.rejected') }}</span>
                                @break
                            @case(\App\Models\Payout::$done)
                                <span class="">{{ trans('public.done') }}</span>
                                @break
                        @endswitch
                    </td>
                    <td>
                        @if(!empty($payout->userSelectedBank->bank))
                            @php
                                $bank = $payout->userSelectedBank->bank;
                            @endphp
                                @endif
                                
                                @if(!empty($bank->title))
                            <input type="hidden" class="js-bank-details" data-name="{{ trans("admin/main.bank") }}" value="{{ $bank->title }}">
                            @foreach($bank->specifications as $specification)
                                @php
                                    $selectedBankSpecification = $payout->userSelectedBank->specifications->where('user_selected_bank_id', $payout->userSelectedBank->id)->where('user_bank_specification_id', $specification->id)->first();
                                @endphp

                                @if(!empty($selectedBankSpecification))
                                    <input type="hidden" class="js-bank-details" data-name="{{ $specification->name }}" value="{{ $selectedBankSpecification->value }}">
                                @endif
                            @endforeach
                        @endif
                        <button type="button" class="js-show-details btn-transparent btn-sm" data-toggle="tooltip" title="{{ trans('update.show_details') }}">
                            <i data-feather="eye" width="18"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="my-30">
        {{ $payouts->appends(request()->input())->links('vendor.pagination.panel') }}
    </div>
</section>
@else
    @include(getTemplate() . '.includes.no-result',[
        'file_name' => 'payout.png',
        'title' => trans('financial.payout_no_result'),
        'hint' => nl2br(trans('financial.payout_no_result_hint')),
    ])
@endif

<div id="requestPayoutModal" class="d-none">
    <h3 class="section-title">{{ trans('financial.payout_confirmation') }}</h3>
    <p class="text-gray mt-15">{{ trans('financial.payout_confirmation_hint') }}</p>

    <form method="post" action="/panel/financial/request-payout">
        {{ csrf_field() }}
        <div class="row justify-content-center">
            <div class="w-75 mt-50">
                <div class="d-flex align-items-center justify-content-between text-gray">
                    <span class="font-weight-bold">{{ trans('financial.ready_to_payout') }}</span>
                    <span>{{ handlePrice($readyPayout ?? 0) }}</span>
                </div>

                @if(!empty($authUser->selectedBank) and !empty($authUser->selectedBank->bank))
                    <div class="d-flex align-items-center justify-content-between text-gray mt-20">
                        <span class="font-weight-bold">{{ trans('financial.account_type') }}</span>
                        <span>{{ $authUser->selectedBank->bank->title }}</span>
                    </div>

                    @foreach($authUser->selectedBank->bank->specifications as $specification)
                        @php
                            $selectedBankSpecification = $authUser->selectedBank->specifications->where('user_selected_bank_id', $authUser->selectedBank->id)->where('user_bank_specification_id', $specification->id)->first();
                        @endphp
                        <div class="d-flex align-items-center justify-content-between text-gray mt-20">
                            <span class="font-weight-bold">{{ $specification->name }}</span>
                            <span>{{ (!empty($selectedBankSpecification)) ? $selectedBankSpecification->value : '' }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="mt-50 d-flex align-items-center justify-content-end">
            <button type="button" class="js-submit-payout btn btn-primary">{{ trans('financial.request_payout') }}</button>
            <button type="button" class="btn btn-danger ml-10 close-swl">{{ trans('public.close') }}</button>
        </div>
    </form>
</div>
@endsection

@push('scripts_bottom')
<script>
    var payoutDetailsLang = '{{ trans('update.payout_details') }}';
    var closeLang = '{{ trans('public.close') }}';
</script>

<script src="/assets/default/js/panel/financial/payout.min.js"></script>
@endpush

