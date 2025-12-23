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

/* Section Title */
.section-title {
    color: var(--k-gold);
    font-weight: 700;
    margin-bottom: 20px;
    letter-spacing: 0.6px;
}

/* ALERTS */
.success-transparent-alert {
    background: var(--k-gold-soft);
    color: var(--k-text);
    border-radius: var(--k-radius);
    padding: 15px;
    display: flex;
    align-items: center;
    margin-bottom: 25px;
}
.success-transparent-alert__icon {
    background: var(--k-gold);
    color: #0b0b0b;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Summary Cards */
.activities-container .col-4 {
    margin-bottom: 25px;
}
.activities-container .col-4 .d-flex {
    background: var(--k-card);
    padding: 20px;
    border-radius: var(--k-radius);
    box-shadow: var(--k-shadow);
    transition: transform 0.3s;
}
.activities-container .col-4 .d-flex:hover {
    transform: translateY(-5px);
}

/* Payment Gateway Radio */
.charge-account-radio input[type="radio"] {
    display: none;
}
.charge-account-radio label {
    background: var(--k-card);
    border-radius: var(--k-radius);
    box-shadow: var(--k-shadow);
    padding: 20px 25px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: transform 0.3s, border 0.3s;
    border: 2px solid transparent;
}
.charge-account-radio input[type="radio"]:checked + label {
    border: 2px solid var(--k-gold);
    transform: translateY(-5px);
}
.charge-account-radio img {
    max-width: 120px;
    max-height: 60px;
}
.charge-account-radio p {
    color: var(--k-text);
    font-weight: 500;
    margin-top: 15px;
    text-align: center;
}
.disabled-payment-channel {
    opacity: 0.6;
    pointer-events: none;
    border: 1px solid var(--k-border);
    background: var(--k-card);
    transition: none;
}

/* Form Inputs */
.input-label {
    color: var(--k-text);
}
.form-control {
    background: #1a1a1a;
    color: var(--k-text);
    border-radius: var(--k-radius);
    border: 1px solid var(--k-border);
}
.form-control:focus {
    box-shadow: 0 0 8px var(--k-gold);
    border-color: var(--k-gold);
    color: var(--k-text);
}

/* Buttons */
.btn-primary {
    background-color: var(--k-gold);
    color: #0b0b0b;
    border-radius: var(--k-radius);
    padding: 10px 25px;
    font-weight: bold;
    transition: background 0.3s, transform 0.3s;
}
.btn-primary:hover {
    background-color: #b8952c;
    transform: translateY(-2px);
}

/* Bank Info Cards */
.panel-shadow {
    background: var(--k-card);
    border-radius: var(--k-radius);
    box-shadow: var(--k-shadow);
    padding: 25px;
    transition: transform 0.3s;
}
.panel-shadow:hover {
    transform: translateY(-5px);
}
.panel-shadow img {
    max-width: 120px;
    max-height: 60px;
}

/* Offline Transactions Table */
.custom-table {
    background: var(--k-card);
    border-radius: var(--k-radius);
    color: var(--k-text);
}
.custom-table th, .custom-table td {
    vertical-align: middle !important;
    border-color: var(--k-border);
}
.custom-table th {
    color: var(--k-gold);
}
.custom-table td {
    color: var(--k-text);
}

/* Responsive */
@media (max-width: 768px) {
    .charge-account-radio label {
        padding: 15px;
    }
}
.bg-dark { background-color: #0f0f0f; }
.text-gold { color: #f2c94c !important; }
.panel-shadow { box-shadow: 0 0 20px rgba(242, 201, 76, 0.3); }
.rounded { border-radius: 14px; }
.activities-container img { filter: brightness(1.2); } /* Slight glow for icons */
.text-muted { color: #b0b0b0 !important; } /* Softer gray for subtitles */

</style>
@endpush

@section('content')
<div class="kemetic-page">

    {{-- Cashback Alerts --}}
    @if(!empty($cashbackRules) and count($cashbackRules))
        @foreach($cashbackRules as $cashbackRule)
            <div class="d-flex align-items-center mb-20 p-15 success-transparent-alert {{ $classNames ?? '' }}">
                <div class="success-transparent-alert__icon d-flex align-items-center justify-content-center">
                    <i data-feather="credit-card" width="18" height="18"></i>
                </div>
                <div class="ml-10">
                    <div class="font-14 font-weight-bold">{{ trans('update.get_cashback') }}</div>
                    <div class="font-12">{{ trans('update.by_charging_your_wallet_will_get_amount_as_cashback',['amount' => ($cashbackRule->amount_type == 'percent' ? "%{$cashbackRule->amount}" : handlePrice($cashbackRule->amount))]) }}</div>
                </div>
            </div>
        @endforeach
    @endif

    {{-- Registration Bonus --}}
    @if(!empty($registrationBonusAmount))
        <div class="mb-25 d-flex align-items-center justify-content-between p-15 panel-shadow">
            <div class="d-flex align-items-center">
                <img src="/assets/default/img/icons/money.png" alt="money" width="51" height="51">
                <div class="ml-15" style="padding:10px;">
                    <span class="d-block font-16 font-weight-bold">{{ trans('update.unlock_registration_bonus') }}</span>
                    <span class="d-block font-14 text-muted mt-15">{{ trans('update.your_wallet_includes_amount_registration_bonus_This_amount_is_locked',['amount' => handlePrice($registrationBonusAmount)]) }}</span>
                </div>
            </div>
            <a href="/panel/marketing/registration_bonus" class="btn btn-primary btn-sm">{{ trans('update.view_more') }}</a>
        </div>
    @endif

    {{-- Account Summary --}}
    <section>
    <h2 class="section-title text-gold">{{ trans('financial.account_summary') }}</h2>

    <div class="activities-container mt-25 p-20 p-lg-35 bg-dark rounded panel-shadow">
        <div class="row text-center">
            
            <div class="col-4 d-flex flex-column align-items-center justify-content-center">
                <img src="/assets/default/img/activity/36.svg" width="64" height="64" alt="">
                <strong class="font-30 text-gold font-weight-bold mt-3">{{ $accountCharge ? handlePrice($accountCharge) : 0 }}</strong>
                <span class="font-16 text-muted font-weight-500">{{ trans('financial.account_charge') }}</span>
            </div>

            <div class="col-4 d-flex flex-column align-items-center justify-content-center">
                <img src="/assets/default/img/activity/37.svg" width="64" height="64" alt="">
                <strong class="font-30 text-gold font-weight-bold mt-3">{{ $readyPayout ? handlePrice($readyPayout) : 0 }}</strong>
                <span class="font-16 text-muted font-weight-500">{{ trans('financial.ready_to_payout') }}</span>
            </div>

            <div class="col-4 d-flex flex-column align-items-center justify-content-center">
                <img src="/assets/default/img/activity/38.svg" width="64" height="64" alt="">
                <strong class="font-30 text-gold font-weight-bold mt-3">{{ $totalIncome ? handlePrice($totalIncome) : 0 }}</strong>
                <span class="font-16 text-muted font-weight-500">{{ trans('financial.total_income') }}</span>
            </div>

        </div>
    </div>
</section>

    @if (\Session::has('msg'))
        <div class="alert alert-warning">
            <ul>
                <li>{!! \Session::get('msg') !!}</li>
            </ul>
        </div>
    @endif

    @php
        $showOfflineFields = false;
        if ($errors->has('date') or $errors->has('referral_code') or $errors->has('account') or !empty($editOfflinePayment)) {
            $showOfflineFields = true;
        }

        $isMultiCurrency = !empty(getFinancialCurrencySettings('multi_currency'));
        $userCurrency = currency();
        $invalidChannels = [];
    @endphp

    {{-- Payment Gateways Form --}}
    <section class="mt-30">
        <h2 class="section-title">{{ trans('financial.select_the_payment_gateway') }}</h2>

        <form action="/panel/financial/{{ !empty($editOfflinePayment) ? 'offline-payments/'. $editOfflinePayment->id .'/update' : 'charge' }}" method="post" enctype="multipart/form-data" class="mt-25">
            {{csrf_field()}}
            
            @if($errors->has('gateway'))
                <div class="text-danger mb-3">{{ $errors->first('gateway') }}</div>
            @endif

            <div class="row">
                @foreach($paymentChannels as $paymentChannel)
                    @if(!$isMultiCurrency or (!empty($paymentChannel->currencies) and in_array($userCurrency, $paymentChannel->currencies)))
                        <div class="col-6 col-lg-3 mb-40 charge-account-radio">
                            <input type="radio" class="online-gateway" name="gateway" id="{{ $paymentChannel->class_name }}" @if(old('gateway') == $paymentChannel->class_name) checked @endif value="{{ $paymentChannel->class_name }}">
                            <label for="{{ $paymentChannel->class_name }}">
                                <img src="{{ $paymentChannel->image }}" alt="">
                                <p>{{ trans('financial.pay_via') }} <span class="font-weight-bold">{{ $paymentChannel->title }}</span></p>
                            </label>
                        </div>
                    @else
                        @php $invalidChannels[] = $paymentChannel; @endphp
                    @endif
                @endforeach

                {{-- Offline Option --}}
                @if(!empty(getOfflineBankSettings('offline_banks_status')))
                    <div class="col-6 col-lg-3 mb-40 charge-account-radio">
                        <input type="radio" name="gateway" id="offline" value="offline" @if(old('gateway') == 'offline' or !empty($editOfflinePayment)) checked @endif>
                        <label for="offline">
                            <img src="/assets/default/img/activity/pay.svg" alt="">
                            <p>{{ trans('financial.pay_via') }} <span class="font-weight-bold">{{ trans('financial.offline') }}</span></p>
                        </label>
                    </div>
                @endif
            </div>

            @if(!empty($invalidChannels) and empty(getFinancialSettings("hide_disabled_payment_gateways")))
                <div class="d-flex align-items-center rounded-lg border p-15">
                    <div class="size-40 d-flex-center rounded-circle bg-gray200">
                        <i data-feather="gift" class="text-gray" width="20" height="20"></i>
                    </div>
                    <div class="ml-5">
                        <h4 class="font-14 font-weight-bold text-gray">{{ trans('update.disabled_payment_gateways') }}</h4>
                        <p class="font-12 text-gray">{{ trans('update.disabled_payment_gateways_hint') }}</p>
                    </div>
                </div>

                <div class="row mt-20">
                    @foreach($invalidChannels as $invalidChannel)
                        <div class="col-6 col-lg-3 mb-40 charge-account-radio">
                            <div class="disabled-payment-channel bg-white border rounded-sm p-20 p-lg-45 d-flex flex-column align-items-center justify-content-center">
                                <img src="{{ $invalidChannel->image }}" width="120" height="60" alt="">

                                <p class="mt-30 mt-lg-50 font-weight-500 text-dark-blue">
                                    {{ trans('financial.pay_via') }}
                                    <span class="font-weight-bold font-14">{{ $invalidChannel->title }}</span>
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Offline Payment Fields & Amount --}}
            <div class="mt-25">
                <h3 class="section-title mb-20">{{ trans('financial.finalize_payment') }}</h3>

                <div class="row">
                    <div class="col-12 col-lg-3 mb-25 mb-lg-0">
                        <label class="input-label">{{ trans('panel.amount') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-white">{{ $currency }}</span>
                            </div>
                            <input type="number" name="amount" min="0" class="form-control @error('amount') is-invalid @enderror" value="{{ !empty($editOfflinePayment) ? $editOfflinePayment->amount : old('amount') }}" placeholder="{{ trans('panel.number_only') }}"/>
                            <div class="invalid-feedback">@error('amount') {{ $message }} @enderror</div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-3 mb-25 mb-lg-0 js-offline-payment-input " style="{{ (!$showOfflineFields) ? 'display:none' : '' }}">
                        <div class="form-group">
                            <label class="input-label">{{ trans('financial.account') }}</label>
                            <select name="account" class="form-control @error('account') is-invalid @enderror">
                                <option selected disabled>{{ trans('financial.select_the_account') }}</option>

                                @foreach($offlineBanks as $offlineBank)
                                    <option value="{{ $offlineBank->id }}" @if(!empty($editOfflinePayment) and $editOfflinePayment->offline_bank_id == $offlineBank->id) selected @endif>{{ $offlineBank->title }}</option>
                                @endforeach
                            </select>

                            @error('account')
                            <div class="invalid-feedback"> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-lg-3 mb-25 mb-lg-0 js-offline-payment-input " style="{{ (!$showOfflineFields) ? 'display:none' : '' }}">
                        <div class="form-group">
                            <label for="referralCode" class="input-label">{{ trans('admin/main.referral_code') }}</label>
                            <input type="text" name="referral_code" id="referralCode" value="{{ !empty($editOfflinePayment) ? $editOfflinePayment->reference_number : old('referral_code') }}" class="form-control @error('referral_code') is-invalid @enderror"/>
                            @error('referral_code')
                            <div class="invalid-feedback"> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-lg-3 mb-25 mb-lg-0 js-offline-payment-input " style="{{ (!$showOfflineFields) ? 'display:none' : '' }}">
                        <div class="form-group">
                            <label class="input-label">{{ trans('public.date_time') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="dateRangeLabel">
                                        <i data-feather="calendar" width="18" height="18" class="text-white"></i>
                                    </span>
                                </div>
                                <input type="text" name="date" value="{{ !empty($editOfflinePayment) ? dateTimeFormat($editOfflinePayment->pay_date, 'Y-m-d H:i', false) : old('date') }}" class="form-control datetimepicker @error('date') is-invalid @enderror"
                                       aria-describedby="dateRangeLabel"/>
                                @error('date')
                                <div class="invalid-feedback"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-3 mb-25 mb-lg-0 js-offline-payment-input " style="{{ (!$showOfflineFields) ? 'display:none' : '' }}">
                        <div class="form-group">
                            <label class="input-label">{{ trans('update.attach_the_payment_photo') }}</label>

                            <label for="attachmentFile" id="attachmentFileLabel" class="custom-upload-input-group">
                                <span class="custom-upload-icon text-white">
                                    <i data-feather="upload" width="18" height="18" class="text-white"></i>
                                </span>
                                <div class="custom-upload-input"></div>
                            </label>

                            <input type="file" name="attachment" id="attachmentFile"
                                   class="form-control h-auto invisible-file-input @error('attachment') is-invalid @enderror"
                                   value=""/>
                            @error('attachment')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-3" style="padding:10px;">
                    <div class="mt-30">
                        <button type="button" id="submitChargeAccountForm" class="btn btn-primary btn-sm">{{ trans('public.pay') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </section>

    {{-- Bank Accounts --}}
    <section class="mt-40">
        <h2 class="section-title">{{ trans('financial.bank_accounts_information') }}</h2>
        <div class="row mt-25">
            @foreach($offlineBanks as $offlineBank)
                <div class="col-12 col-lg-3 mb-30">
                    <div class="panel-shadow d-flex flex-column align-items-center justify-content-center">
                        <img src="{{ $offlineBank->logo }}" alt="">
                        <div class="mt-15 mt-30 w-100">
                            <div class="d-flex justify-content-between">
                                <span class="text-secondary">{{ trans('public.name') }}:</span>
                                <span class="text-gray">{{ $offlineBank->title }}</span>
                            </div>
                            @foreach($offlineBank->specifications as $specification)
                                <div class="d-flex justify-content-between mt-10">
                                    <span class="text-secondary">{{ $specification->name }}:</span>
                                    <span class="text-gray">{{ $specification->value }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Offline Payment History --}}
    @if($offlinePayments->count() > 0)
        <section class="mt-40">
            <h2 class="section-title">{{ trans('financial.offline_transactions_history') }}</h2>
            <div class="panel-shadow py-20 px-25 mt-20 table-responsive">
                <table class="table text-center custom-table">
                    <thead>
                        <tr>
                            <th>{{ trans('financial.bank') }}</th>
                            <th>{{ trans('admin/main.referral_code') }}</th>
                            <th>{{ trans('panel.amount') }} ({{ $currency }})</th>
                            <th>{{ trans('update.attachment') }}</th>
                            <th>{{ trans('public.status') }}</th>
                            <th>{{ trans('public.controls') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($offlinePayments as $offlinePayment)
                            <tr>
                                <td class="text-left">{{ $offlinePayment->offlineBank->title ?? '-' }} 
                                    <span class="font-12 text-gray">{{ dateTimeFormat($offlinePayment->pay_date, 'j M Y H:i') }}</span>
                                </td>
                                <td>{{ $offlinePayment->reference_number }}</td>
                                <td class="text-primary">{{ handlePrice($offlinePayment->amount, false) }}</td>
                                <td>
                                    @if($offlinePayment->attachment)
                                        <a href="{{ $offlinePayment->getAttachmentPath() }}" class="text-primary" target="_blank">{{ trans('public.view') }}</a>
                                    @else
                                        ---
                                    @endif
                                </td>
                                <td>
                                    @switch($offlinePayment->status)
                                        @case(\App\Models\OfflinePayment::$waiting)
                                            <span class="text-warning">{{ trans('public.waiting') }}</span>
                                            @break
                                        @case(\App\Models\OfflinePayment::$approved)
                                            <span class="text-primary">{{ trans('financial.approved') }}</span>
                                            @break
                                        @case(\App\Models\OfflinePayment::$reject)
                                            <span class="text-danger">{{ trans('public.rejected') }}</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    @if($offlinePayment->status != 'approved')
                                        <div class="btn-group dropdown table-actions">
                                            <button class="btn-transparent dropdown-toggle" data-toggle="dropdown"><i data-feather="more-vertical" height="20"></i></button>
                                            <div class="dropdown-menu">
                                                <a href="/panel/financial/offline-payments/{{ $offlinePayment->id }}/edit" class="webinar-actions d-block mt-10">{{ trans('public.edit') }}</a>
                                                <a href="/panel/financial/offline-payments/{{ $offlinePayment->id }}/delete" class="webinar-actions d-block mt-10 delete-action">{{ trans('public.delete') }}</a>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @else
        @include(getTemplate() . '.includes.no-result',[
            'file_name' => 'offline.png',
            'title' => trans('financial.offline_no_result'),
            'hint' => nl2br(trans('financial.offline_no_result_hint')),
        ])
    @endif

</div>
@endsection

@push('scripts_bottom')
<script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
<script src="/assets/default/js/panel/financial/account.min.js"></script>
<script>
    (function ($) {
        "use strict";
        @if(session()->has('sweetalert'))
        Swal.fire({
            icon: "{{ session()->get('sweetalert')['status'] ?? 'success' }}",
            html: '<h3 class="font-20 text-center text-dark-blue py-25">{{ session()->get('sweetalert')['msg'] ?? '' }}</h3>',
            showConfirmButton: false,
            width: '25rem',
        });
        @endif
    })(jQuery)
</script>
@endpush
