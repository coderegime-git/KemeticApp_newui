@extends('web.default.layouts.newapp')

@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/chartjs/chart.min.css"/>

<style>
    /* ===== KEMETIC BLACK GOLD THEME ===== */

body {
    background: #070707;
}

/* Titles */
.kemetic-title {
    color: #D4AF37;
    font-weight: 700;
    letter-spacing: .6px;
}

/* Cards */
.kemetic-card {
    background: linear-gradient(145deg, #0b0b0b, #151515);
    border: 1px solid rgba(212,175,55,.25);
    border-radius: 16px;
    box-shadow: 0 15px 50px rgba(0,0,0,.6);
}

/* Alert */
.kemetic-alert {
    background: rgba(212,175,55,.12);
    border-left: 4px solid #D4AF37;
    border-radius: 12px;
}

.kemetic-alert-icon i {
    color: #D4AF37;
}

/* Stats */
.kemetic-stat strong {
    display: block;
    margin-top: 10px;
}

.kemetic-amount,
.kemetic-date {
    font-size: 28px;
    color: #FFD700;
}

.kemetic-status.gold {
    color: #D4AF37;
}

.kemetic-status.locked {
    color: #b84c4c;
}

.kemetic-label {
    color: #9a9a9a;
}

/* Tables */
.kemetic-table thead th {
    color: #D4AF37;
    border-bottom: 1px solid rgba(212,175,55,.4);
}

.kemetic-table tbody tr:hover {
    background: rgba(212,175,55,.05);
}

/* Pills */
.text-primary {
    color: #D4AF37 !important;
}

/* Icons */
.feather {
    stroke: #D4AF37;
}

/* ==============================
   KEMETIC BLACK GOLD THEME
============================== */

.kemetic-card {
    background: linear-gradient(145deg, #0b0b0b, #151515);
    border: 1px solid rgba(212,175,55,.28);
    border-radius: 18px;
    box-shadow: 0 18px 50px rgba(0,0,0,.65);
}

.kemetic-glow:hover {
    box-shadow: 0 0 30px rgba(212,175,55,.35);
}

/* Titles */
.kemetic-subtitle {
    color: #D4AF37;
    font-weight: 700;
    letter-spacing: .4px;
}

/* Muted text */
.kemetic-muted {
    color: #a8a8a8;
    font-size: 14px;
}

/* Progress block */
.kemetic-progress {
    background: rgba(212,175,55,.07);
    border: 1px solid rgba(212,175,55,.35);
    border-radius: 14px;
}

/* Text hierarchy */
.kemetic-text-title {
    font-size: 14px;
    font-weight: 600;
    color: #E6C87A;
}

.kemetic-text-sub {
    font-size: 12px;
    color: #9a9a9a;
}

/* Check badge */
.kemetic-check {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #D4AF37;
    border-radius: 50%;
    padding: 4px;
}

.kemetic-check i {
    stroke: #000;
}

/* List items */
.kemetic-list {
    padding: 12px 0;
    border-bottom: 1px dashed rgba(212,175,55,.25);
}

.kemetic-list:last-child {
    border-bottom: none;
}

/* Icons */
.kemetic-icon-box {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: rgba(212,175,55,.15);
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Images */
.kemetic-image {
    filter: drop-shadow(0 0 14px rgba(212,175,55,.35));
}

/* ==============================
   KEMETIC REFERRAL TABLE
============================== */

.kemetic-title {
    color: #D4AF37;
    font-weight: 700;
    letter-spacing: .5px;
}

/* Table */
.kemetic-table thead th {
    color: #D4AF37;
    border-bottom: 1px solid rgba(212,175,55,.4);
}

.kemetic-table tbody tr {
    transition: background .25s ease;
}

.kemetic-table tbody tr:hover {
    background: rgba(212,175,55,.05);
}

/* User */
.kemetic-user {
    align-items: center;
}

.kemetic-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid rgba(212,175,55,.4);
}

.kemetic-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.kemetic-user-name {
    font-weight: 600;
    color: #E6C87A;
}

/* Badges */
.kemetic-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

.kemetic-badge.reached {
    background: rgba(212,175,55,.15);
    color: #D4AF37;
    border: 1px solid rgba(212,175,55,.4);
}

.kemetic-badge.not-reached {
    background: rgba(184,76,76,.15);
    color: #d46a6a;
    border: 1px solid rgba(184,76,76,.4);
}

/* Date */
.kemetic-date {
    color: #9b9b9b;
    font-size: 13px;
}

/* Empty state */
.kemetic-empty-logo img {
    opacity: .85;
    max-width: 160px;
}

.kemetic-empty {
    color: #9b9b9b;
}
.bonus-status-pie-charts {
    width: 45px;
    min-width: 45px;
    height: 45px;
}

</style>
@endpush

@section('content')
@php
    $registrationBonusSettings = getRegistrationBonusSettings();
    $checkReferralUserCount = (!empty($registrationBonusSettings['unlock_registration_bonus_with_referral']) and !empty($registrationBonusSettings['number_of_referred_users']));
    $purchaseAmountCount = (!empty($registrationBonusSettings['enable_referred_users_purchase']));
@endphp

{{-- SUCCESS ALERT --}}
    @if(!empty($accounting))
    <div class="kemetic-alert d-flex align-items-center mb-20 p-15">
        <div class="kemetic-alert-icon">
            <i data-feather="credit-card"></i>
        </div>
        <div class="ml-10">
            <div class="font-14 font-weight-bold text-gold">
                {{ trans('update.you_got_the_bonus') }}
            </div>
            <div class="font-12 text-muted-gold">
                {{ trans('update.your_registration_bonus_was_unlocked_on_date',['date' => dateTimeFormat($accounting->created_at, 'j M Y')]) }}
            </div>
        </div>
    </div>
    @endif

   <section>
        <h2 class="section-title kemetic-title">
            {{ trans('update.registration_bonus') }}
        </h2>

        <div class="kemetic-card mt-25 p-20 p-lg-35">
            <div class="row text-center">

                <div class="col-4 kemetic-stat">
                    <img src="/assets/default/img/activity/36.svg" width="64">
                    <strong class="kemetic-amount">
                        {{ handlePrice($registrationBonusSettings['registration_bonus_amount'] ?? 0) }}
                    </strong>
                    <span class="kemetic-label">{{ trans('update.registration_bonus') }}</span>
                </div>

                <div class="col-4 kemetic-stat">
                    <img src="/assets/default/img/activity/rank.png" width="64">
                    <strong class="kemetic-status {{ !empty($accounting) ? 'gold' : 'locked' }}">
                        {{ !empty($accounting) ? trans('update.unlocked') : trans('update.locked') }}
                    </strong>
                    <span class="kemetic-label">{{ trans('update.bonus_status') }}</span>
                </div>

                <div class="col-4 kemetic-stat">
                    <img src="/assets/default/img/activity/computer.png" width="64">
                    <strong class="kemetic-date">
                        {{ !empty($accounting) ? dateTimeFormat($accounting->created_at, 'j M Y') : '-' }}
                    </strong>
                    <span class="kemetic-label">{{ trans('update.bonus_date') }}</span>
                </div>

            </div>
        </div>
    </section>

    <section class="row">
        @if($checkReferralUserCount or $purchaseAmountCount)
            <div class="col-12 col-md-6 mt-25">
                <div class="kemetic-card kemetic-glow p-20 h-100">
                    <div class="row" style="padding:10px;">

                        <div class="col-5 d-flex align-items-center justify-content-center">
                            <img src="/assets/default/img/rewards/registration_bonus.png"
                                class="img-fluid kemetic-image"
                                alt="{{ trans('update.registration_bonus') }}">
                        </div>

                        <div class="col-7">
                            <h4 class="kemetic-subtitle">
                                {{ trans('update.bonus_status') }}
                            </h4>

                            <p class="kemetic-muted mt-10">
                                {{ trans('update.your_bonus_is_locked_To_unlock_the_bonus_please_check_the_following_statuses') }}:
                            </p>

                            {{-- REFERRED USERS --}}
                            @if(!empty($registrationBonusSettings['number_of_referred_users']))
                                <div class="kemetic-progress d-flex align-items-center position-relative mt-15 p-15" style="padding:10px;">
                                    <div class="bonus-status-pie-charts">
                                        <canvas id="bonusStatusReferredUsersChart" height="40"></canvas>
                                    </div>

                                    <div class="ml-10">
                                        <span class="kemetic-text-title">
                                            {{ trans('update.referred_users') }}
                                        </span>
                                        <span class="kemetic-text-sub">
                                            {{ $bonusStatusReferredUsersChart['complete'] == 0
                                                ? trans('update.you_havent_referred_any_users')
                                                : trans('update.you_referred_count_users_to_the_platform',['count' => "{$bonusStatusReferredUsersChart['referred_users']}/{$registrationBonusSettings['number_of_referred_users']}"])
                                            }}
                                        </span>
                                    </div>

                                    @if($bonusStatusReferredUsersChart['complete'] == 100)
                                        <div class="kemetic-check">
                                            <i data-feather="check" width="12" height="12"></i>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            {{-- USERS PURCHASES --}}
                            @if($purchaseAmountCount)
                                <div class="kemetic-progress d-flex align-items-center position-relative mt-15 p-15" style="padding:10px; margin-top:10px;">
                                    <div class="bonus-status-pie-charts">
                                        <canvas id="bonusStatusUsersPurchasesChart" height="40"></canvas>
                                    </div>

                                    <div class="ml-10">
                                        <span class="kemetic-text-title">
                                            {{ trans('update.users_purchases') }}
                                        </span>
                                        <span class="kemetic-text-sub">
                                            {{ $bonusStatusUsersPurchasesChart['complete'] == 0
                                                ? trans('update.you_havent_referred_any_users_to_purchase')
                                                : trans('update.count_users_achieved_purchase_target',['count' => "{$bonusStatusUsersPurchasesChart['reached_user_purchased']}/{$bonusStatusUsersPurchasesChart['total_user_purchased']}"])
                                            }}
                                        </span>
                                    </div>

                                    @if($bonusStatusUsersPurchasesChart['complete'] == 100)
                                        <div class="kemetic-check">
                                            <i data-feather="check" width="12" height="12"></i>
                                        </div>
                                    @endif
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- HOW TO GET BONUS --}}
        @php
            $registrationBonusTermsSettings = getRegistrationBonusTermsSettings();
        @endphp

        @if(!empty($registrationBonusTermsSettings) and !empty($registrationBonusTermsSettings['items']))
            <div class="mt-25 {{ ($checkReferralUserCount or $purchaseAmountCount) ? 'col-12 col-md-6' : 'col-12' }}">
                <div class="kemetic-card p-20 h-100">
                    <div class="row" style="padding:10px;">

                        <div class="col-7">
                            <h4 class="kemetic-subtitle mb-20">
                                {{ trans('update.how_to_get_bonus') }}
                            </h4>

                            @foreach($registrationBonusTermsSettings['items'] as $termItem)
                                @if(!empty($termItem['icon']) && !empty($termItem['title']) && !empty($termItem['description']))
                                    <div class="kemetic-list d-flex align-items-start">
                                        <div class="icon-box kemetic-icon-box">
                                            <img src="{{ $termItem['icon'] }}" width="16" height="16">
                                        </div>
                                        <div class="ml-10">
                                            <span class="kemetic-text-title">
                                                {{ $termItem['title'] }}
                                            </span>
                                            <span class="kemetic-text-sub mt-5 d-block">
                                                {{ $termItem['description'] }}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        @if(!empty($registrationBonusTermsSettings['term_image']))
                            <div class="col-5 d-flex align-items-center justify-content-center">
                                <img src="{{ $registrationBonusTermsSettings['term_image'] }}"
                                    class="img-fluid kemetic-image"
                                    alt="{{ trans('update.how_to_get_bonus') }}">
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @endif

    </section>


    @if($checkReferralUserCount)
        <section class="mt-25">

    <h2 class="section-title kemetic-title">
        {{ trans('update.referral_history') }}
    </h2>

    @if(!empty($referredUsers) and count($referredUsers))

        <div class="kemetic-card py-20 px-25 mt-20">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">

                        <table class="table kemetic-table text-center">
                            <thead>
                            <tr>
                                <th>{{ trans('panel.user') }}</th>

                                @if($purchaseAmountCount)
                                    <th class="text-center">
                                        {{ trans('update.purchase_status') }}
                                    </th>
                                @endif

                                <th class="text-right">
                                    {{ trans('panel.registration_date') }}
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($referredUsers as $user)
                                <tr>

                                    {{-- USER --}}
                                    <td class="text-left">
                                        <div class="d-flex align-items-center kemetic-user">
                                            <div class="kemetic-avatar">
                                                <img src="{{ $user->getAvatar() }}"
                                                     class="img-cover"
                                                     alt="{{ $user->full_name }}">
                                            </div>
                                            <div class="ml-10">
                                                <span class="kemetic-user-name">
                                                    {{ $user->full_name }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- PURCHASE STATUS --}}
                                    @if($purchaseAmountCount)
                                        <td>
                                            @if(
                                                (!empty($registrationBonusSettings['purchase_amount_for_unlocking_bonus']) &&
                                                $user->totalPurchase >= $registrationBonusSettings['purchase_amount_for_unlocking_bonus'])
                                                ||
                                                (empty($registrationBonusSettings['purchase_amount_for_unlocking_bonus']) &&
                                                $user->totalPurchase > 0)
                                            )
                                                <span class="kemetic-badge reached">
                                                    {{ trans('update.reached') }}
                                                </span>
                                            @else
                                                <span class="kemetic-badge not-reached">
                                                    {{ trans('update.not_reached') }}
                                                </span>
                                            @endif
                                        </td>
                                    @endif

                                    {{-- DATE --}}
                                    <td class="text-right kemetic-date">
                                        {{ dateTimeFormat($user->created_at, 'Y M j | H:i') }}
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

    @else
        {{-- NO RESULT --}}
        <div class="kemetic-empty my-50 d-flex align-items-center justify-content-center flex-column">
            <div class="kemetic-empty-logo">
                <img src="/assets/default/img/no-results/no_followers.png"
                     alt="{{ trans('update.no_referred_users') }}">
            </div>
            <div class="text-center mt-25">
                <h3 class="kemetic-title">
                    {{ trans('update.no_referred_users') }}
                </h3>
                <p class="kemetic-muted mt-5">
                    {{ trans('update.you_havent_referred_any_users_yet') }}
                </p>
            </div>
        </div>
    @endif

</section>

    @endif
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/chartjs/chart.min.js"></script>
    <script src="/assets/default/js/panel/registration_bonus.min.js"></script>

    <script>
        (function ($) {
            "use strict";

            @if(!empty($bonusStatusReferredUsersChart))
            makePieChart('bonusStatusReferredUsersChart', @json($bonusStatusReferredUsersChart['labels']), Number({{ $bonusStatusReferredUsersChart['complete'] }}));
            @endif

            @if(!empty($bonusStatusUsersPurchasesChart))
            makePieChart('bonusStatusUsersPurchasesChart', @json($bonusStatusUsersPurchasesChart['labels']), Number({{ $bonusStatusUsersPurchasesChart['complete'] }}));
            @endif
        })()
    </script>
@endpush
