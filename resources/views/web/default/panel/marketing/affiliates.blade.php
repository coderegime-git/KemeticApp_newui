@extends('web.default.layouts.newapp')

@push('styles_top')
<style>
    :root {
        --k-bg: #0f0f0f;
        --k-card: #161616;
        --k-border: #2a2a2a;
        --k-gold: #F2C94C;
        --k-text: #eaeaea;
        --k-muted: #9aa0a6;
        --k-radius: 18px;
    }

    .k-section-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--k-gold);
        margin-bottom: 20px;
    }

    .k-card {
        background: var(--k-card);
        border: 1px solid var(--k-border);
        border-radius: var(--k-radius);
        padding: 25px;
    }

    .k-stat {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .k-stat strong {
        font-size: 32px;
        color: var(--k-gold);
    }

    .k-stat span {
        color: var(--k-muted);
        margin-top: 6px;
    }

    .k-summary p {
        color: var(--k-text);
        font-size: 14px;
        margin-bottom: 6px;
    }

    .k-input {
        background: #0c0c0c;
        border: 1px solid var(--k-border);
        color: var(--k-text);
    }

    .k-input:focus {
        border-color: var(--k-gold);
        box-shadow: none;
    }

    .k-table {
        background: var(--k-card);
        border-radius: var(--k-radius);
        overflow: hidden;
    }

    .k-table th {
        background: #121212;
        color: var(--k-gold);
        border-bottom: 1px solid var(--k-border);
        font-size: 14px;
    }

    .k-table td {
        color: var(--k-text);
        border-top: 1px solid var(--k-border);
        vertical-align: middle;
    }

    .k-table tr:hover {
        background: rgba(242,201,76,0.06);
    }

    .k-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        overflow: hidden;
        border: 1px solid var(--k-border);
    }

    .k-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

</style>
@endpush

@section('content')

{{-- ================= STATISTICS ================= --}}
<section>
    <h2 class="k-section-title">{{ trans('panel.affiliate_statistics') }}</h2>

    <div class="k-card">
        <div class="row text-center">
            <div class="col-12 col-md-4">
                <div class="k-stat">
                    <img src="/assets/default/img/activity/48.svg" width="54">
                    <strong>{{ $referredUsersCount }}</strong>
                    <span>{{ trans('panel.referred_users') }}</span>
                </div>
            </div>

            <div class="col-12 col-md-4 mt-20 mt-md-0">
                <div class="k-stat">
                    <img src="/assets/default/img/activity/38.svg" width="54">
                    <strong>{{ handlePrice($registrationBonus) }}</strong>
                    <span>{{ trans('panel.registration_bonus') }}</span>
                </div>
            </div>

            <div class="col-12 col-md-4 mt-20 mt-md-0">
                <div class="k-stat">
                    <img src="/assets/default/img/activity/36.svg" width="54">
                    <strong>{{ handlePrice($affiliateBonus) }}</strong>
                    <span>{{ trans('panel.affiliate_bonus') }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ================= SUMMARY ================= --}}
<section class="mt-40">
    <h2 class="k-section-title">{{ trans('panel.affiliate_summary') }}</h2>

    <div class="k-card k-summary">
        @if(!empty($referralSettings))
            @if(!empty($referralSettings['affiliate_user_amount']))
                <p>• {{ trans('panel.user_registration_reward') }}:
                    <strong class="text-warning">{{ handlePrice($referralSettings['affiliate_user_amount']) }}</strong>
                </p>
            @endif

            @if(!empty($referralSettings['referred_user_amount']))
                <p>• {{ trans('panel.referred_user_registration_reward') }}:
                    <strong class="text-warning">{{ handlePrice($referralSettings['referred_user_amount']) }}</strong>
                </p>
            @endif

            @if(!empty($referralSettings['affiliate_user_commission']))
                <p>• {{ trans('panel.referred_user_purchase_commission') }}:
                    <strong class="text-warning">{{ $referralSettings['affiliate_user_commission'] }}%</strong>
                </p>
            @endif

            <p>• {{ trans('panel.your_affiliate_code') }}:
                <strong class="text-warning">{{ $affiliateCode->code }}</strong>
            </p>

            @if(!empty($referralSettings['referral_description']))
                <p class="mt-10">{{ $referralSettings['referral_description'] }}</p>
            @endif
        @endif
    </div>
</section>

{{-- ================= AFFILIATE URL ================= --}}
<section class="mt-40">
    <h2 class="k-section-title">{{ trans('panel.affiliate_url') }}</h2>

    <div class="k-card col-lg-6 px-0">
        <div class="input-group">
            <div class="input-group-prepend">
                <button type="button"
                        class="input-group-text js-copy"
                        data-input="affiliate_url"
                        title="{{ trans('public.copy') }}">
                    <i data-feather="copy" ></i>
                </button>
            </div>
            <input type="text"
                   name="affiliate_url"
                   readonly
                   value="{{ $affiliateCode->getAffiliateUrl() }}"
                   class="form-control k-input"/>
        </div>
    </div>
</section>

{{-- ================= EARNINGS TABLE ================= --}}
<section class="mt-40">
    <h2 class="k-section-title">{{ trans('panel.earnings') }}</h2>

    <div class="k-card">
        <div class="table-responsive">
            <table class="k-table text-center">
                <thead>
                <tr>
                    <th class="text-left">{{ trans('panel.user') }}</th>
                    <th>{{ trans('panel.registration_bonus') }}</th>
                    <th>{{ trans('panel.affiliate_bonus') }}</th>
                    <th>{{ trans('panel.registration_date') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($affiliates as $affiliate)
                    <tr>
                        <td class="text-left">
                            <div class="d-flex align-items-center">
                                <div class="k-avatar">
                                    <img src="{{ $affiliate->referredUser->getAvatar() }}">
                                </div>
                                <span class="ml-10 font-weight-500">
                                    {{ $affiliate->referredUser->full_name }}
                                </span>
                            </div>
                        </td>

                        <td>{{ handlePrice($affiliate->getAffiliateRegistrationAmountsOfEachReferral()) }}</td>
                        <td>{{ handlePrice($affiliate->getTotalAffiliateCommissionOfEachReferral()) }}</td>
                        <td>{{ dateTimeFormat($affiliate->created_at, 'Y M j | H:i') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-30" style="padding: 10px;">
            {{ $affiliates->appends(request()->input())->links('vendor.pagination.panel') }}
        </div>
    </div>
</section>

@endsection
