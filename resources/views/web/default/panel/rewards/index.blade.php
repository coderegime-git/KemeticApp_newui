@extends('web.default.layouts.newapp')
<style>
    /* Section Title */
.kemetic-title {
    color: #f5c26b;
    font-weight: 700;
    letter-spacing: 0.4px;
}

/* Main Card */
.kemetic-stats-card {
    background: linear-gradient(145deg, #0b0b0b, #121212);
    border: 1px solid rgba(212, 175, 55, 0.25);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.7);
}

/* Individual Stat Box */
.kemetic-stat-box {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(212, 175, 55, 0.18);
    border-radius: 14px;
    padding: 25px 20px;
    transition: all 0.3s ease;
}

.kemetic-stat-box:hover {
    transform: translateY(-6px);
    border-color: rgba(212, 175, 55, 0.6);
    box-shadow: 0 12px 30px rgba(212, 175, 55, 0.15);
}

/* Numbers */
.kemetic-stat-value {
    margin-top: 12px;
    font-size: 32px;
    font-weight: 800;
    color: #d4af37; /* Gold */
}

/* Labels */
.kemetic-stat-label {
    font-size: 15px;
    font-weight: 500;
    color: #9f9f9f;
    margin-top: 4px;
}

/* Optional Highlight */
.text-danger-gold {
    color: #ffb703;
}

/* Base Card */
.kemetic-card {
    background: linear-gradient(145deg, #0b0b0b, #141414);
    border: 1px solid rgba(212, 175, 55, 0.25);
    border-radius: 16px;
    box-shadow: 0 15px 45px rgba(0, 0, 0, 0.75);
}

/* Titles */
.kemetic-card-title {
    font-size: 17px;
    font-weight: 700;
    color: #f5c26b;
}

.kemetic-card-title-sm {
    font-size: 15px;
    font-weight: 600;
    color: #f5c26b;
}

/* Text */
.kemetic-text-muted {
    color: #9f9f9f;
    font-weight: 500;
}

/* Gold Amount */
.kemetic-gold-amount {
    font-size: 32px;
    font-weight: 800;
    color: #d4af37;
}

/* Buttons */
.btn-kemetic-gold {
    background: linear-gradient(135deg, #d4af37, #f5c26b);
    color: #000;
    font-weight: 700;
    border-radius: 30px;
    padding: 6px 22px;
}

.btn-kemetic-outline {
    border: 1px solid #d4af37;
    color: #d4af37;
    background: transparent;
    border-radius: 30px;
    padding: 6px 22px;
}

.btn-kemetic-outline:hover {
    background: #d4af37;
    color: #000;
}

.btn-kemetic-disabled {
    background: #2a2a2a;
    color: #777;
    border-radius: 30px;
}

/* Leaderboard */
.leaderboard-avatar-gold {
    width: 88px;
    height: 88px;
    border: 3px solid #d4af37;
}

.leaderboard-name {
    display: block;
    margin-top: 10px;
    font-weight: 700;
    color: #f5c26b;
}

.leaderboard-points {
    font-size: 13px;
    color: #aaa;
}

.leaderboard-item {
    display: flex;
    align-items: center;
    padding: 10px 12px;
    border-radius: 12px;
    border: 1px solid rgba(212, 175, 55, 0.2);
    background: rgba(255, 255, 255, 0.03);
    margin-bottom: 10px;
}

/* Section */
.kemetic-section {
    color: #e5e5e5;
}

/* Title */
.kemetic-title {
    color: #d4af37;
    font-weight: 600;
    letter-spacing: 0.5px;
}

/* Card */
.kemetic-card {
    background: #0d0d0d;
    border: 1px solid rgba(212, 175, 55, 0.15);
    border-radius: 14px;
    padding: 20px 25px;
}

/* Table */

.kemetic-table-card {
    background: linear-gradient(145deg, #0b0b0b, #151515);
    border-radius: 18px;
    border: 1px solid rgba(245, 199, 122, 0.15);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.75);
}

.kemetic-table {
    color: #ffffff;
    margin-bottom: 0;
}

.kemetic-table thead th {
    border: none;
    padding: 14px 12px;
    font-size: 13px;
    font-weight: 600;
    color: #f5c77a;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.kemetic-table {
    color: #ffffff;
}

.kemetic-table tbody tr {
    transition: background 0.25s ease;
}

.kemetic-table tbody tr:hover {
    background: rgba(212, 175, 55, 0.05);
}

.kemetic-row {
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    transition: all 0.25s ease;
}

.kemetic-row:hover {
    background: rgba(245, 199, 122, 0.05);
}

/* Title */
.kemetic-table-title {
    font-size: 15px;
    font-weight: 600;
    color: #ffffff;
}

.kemetic-table-subtitle {
    font-size: 12px;
    color: #9a9a9a;
}

/* Meta */
.kemetic-table-meta {
    font-size: 14px;
    font-weight: 500;
    color: #d6d6d6;
}


/* Text styles */
.kemetic-text {
    font-weight: 500;
}

.kemetic-muted {
    color: #9a9a9a;
    font-size: 13px;
}

/* Points */
.kemetic-points {
    font-weight: 600;
    color: #f5c76a;
}

/* Badges */
.kemetic-badge {
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    text-transform: capitalize;
}



.kemetic-add {
    color: #d4af37;
    background: rgba(212, 175, 55, 0.15);
}

.kemetic-minus {
    color: #ff5c5c;
    background: rgba(255, 92, 92, 0.15);
}

/* Pagination */
.kemetic-pagination .pagination .page-link {
    background: #0d0d0d;
    color: #d4af37;
    border: 1px solid rgba(212, 175, 55, 0.25);
}

.kemetic-pagination .pagination .active .page-link {
    background: #d4af37;
    color: #000;
}

</style>
@section('content')
    <section class="mt-30">
    <h2 class="section-title kemetic-title">
        {{ trans('update.points_statistics') }}
    </h2>

    <div class="kemetic-stats-card mt-25 p-25 p-lg-35 rounded-lg">
        <div class="row text-center">

            {{-- Available Points --}}
            <div class="col-12 col-md-4 mb-20 mb-md-0">
                <div class="kemetic-stat-box">
                    <img src="/assets/default/img/activity/trophy_cup.png" width="56" alt="">
                    <h3 class="kemetic-stat-value">{{ $availablePoints }}</h3>
                    <p class="kemetic-stat-label">
                        {{ trans('update.available_points') }}
                    </p>
                </div>
            </div>

            {{-- Total Points --}}
            <div class="col-12 col-md-4 mb-20 mb-md-0">
                <div class="kemetic-stat-box">
                    <img src="/assets/default/img/activity/rank.png" width="56" alt="">
                    <h3 class="kemetic-stat-value">{{ $totalPoints }}</h3>
                    <p class="kemetic-stat-label">
                        {{ trans('update.total_points') }}
                    </p>
                </div>
            </div>

            {{-- Spent Points --}}
            <div class="col-12 col-md-4">
                <div class="kemetic-stat-box">
                    <img src="/assets/default/img/activity/spent.png" width="56" alt="">
                    <h3 class="kemetic-stat-value text-danger-gold">{{ $spentPoints }}</h3>
                    <p class="kemetic-stat-label">
                        {{ trans('update.spent_points') }}
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>


    <div class="row mt-25">

        {{-- Exchange / Reward Card --}}
        <div class="col-12 col-lg-6">
            <div class="kemetic-card kemetic-reward-card p-25 p-lg-35" style="padding: 10px;">

                <div class="row align-items-center">
                    <div class="col-12 col-lg-5 text-center">
                        <div class="reward-gift-img kemetic-glow">
                            <img src="/assets/default/img/rewards/gift_icon.svg" class="img-fluid" alt="gift">
                        </div>
                    </div>

                    <div class="col-12 col-lg-7 mt-20 mt-lg-0 text-center">
                        <h3 class="kemetic-card-title">
                            {{ trans('update.exchange_or_get_a_course') }}
                        </h3>

                        @if(!empty($rewardsSettings) and !empty($rewardsSettings['exchangeable']) and $rewardsSettings['exchangeable'] == '1')
                            <p class="kemetic-text-muted mt-15">
                                {{ trans('update.exchange_or_get_a_course_by_spending_points_hint') }}
                            </p>

                            <span class="kemetic-gold-amount d-block mt-15">
                                {{ handlePrice($earnByExchange) }}
                            </span>

                            <p class="kemetic-text-muted mt-10">
                                {{ trans('update.for_your_available_points') }}
                            </p>
                        @else
                            <p class="kemetic-text-muted mt-15">
                                {{ trans('update.just_get_a_course_by_spending_points_hint') }}
                            </p>
                        @endif

                        <div class="d-flex align-items-center justify-content-center mt-25 flex-wrap gap-10">
                            @if(!empty($rewardsSettings) and !empty($rewardsSettings['exchangeable']) and $rewardsSettings['exchangeable'] == '1')
                                <button
                                    type="button"
                                    class="btn btn-sm {{ $earnByExchange > 0 ? 'btn-kemetic-gold js-exchange-btn' : 'btn-kemetic-disabled' }}"
                                    {{ $earnByExchange > 0 ? '' : 'disabled' }}>
                                    {{ trans('update.exchange') }}
                                </button>
                            @endif

                            <a href="/reward-courses" class="btn btn-sm btn-kemetic-outline">
                                {{ trans('update.browse_courses') }}
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div class="col-12 col-lg-6 mt-20 mt-lg-0" style="padding: 10px;">

            {{-- Want More Points --}}
            <div class="kemetic-card p-20 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <img src="/assets/default/img/rewards/medal.png" width="50" alt="medal">

                    <div class="ml-15">
                        <h3 class="kemetic-card-title-sm">
                            {{ trans('update.want_more_points') }}
                        </h3>
                        <p class="kemetic-text-muted font-13">
                            {{ trans('update.want_more_points_hint') }}
                        </p>
                    </div>
                </div>

                <a href="{{ (!empty($rewardsSettings) and !empty($rewardsSettings['want_more_points_link'])) ? $rewardsSettings['want_more_points_link'] : '' }}"
                class="btn btn-sm btn-kemetic-outline">
                    {{ trans('update.view_more') }}
                </a>
            </div>

            {{-- Leaderboard --}}
            @if(!empty($mostPointsUsers) and count($mostPointsUsers))
                <div class="kemetic-card p-25 mt-20" style="padding: 10px;">

                    @php
                        $leaderboard = $mostPointsUsers->shift();
                    @endphp

                    <h3 class="kemetic-card-title mb-20">
                        {{ trans('update.leaderboard') }}
                    </h3>

                    <div class="row">

                        {{-- Top User --}}
                        <div class="col-12 col-lg-5 text-center">
                            <div class="leaderboard-top">
                                <img src="{{ $leaderboard->user->getAvatar() }}"
                                    class="img-cover rounded-circle leaderboard-avatar-gold"
                                    alt="{{ $leaderboard->user->full_name }}">

                                <span class="leaderboard-name">
                                    {{ $leaderboard->user->full_name }}
                                </span>
                                <span class="leaderboard-points">
                                    {{ $leaderboard->total_points }} {{ trans('update.points') }}
                                </span>
                            </div>
                        </div>

                        {{-- Other Users --}}
                        <div class="col-12 col-lg-7 mt-20 mt-lg-0">
                            @foreach($mostPointsUsers as $mostPoint)
                                <div class="leaderboard-item">
                                    <img src="{{ $mostPoint->user->getAvatar() }}"
                                        class="img-cover rounded-circle leaderboard-others-avatar"
                                        alt="{{ $mostPoint->user->full_name }}" width="50">

                                    <div class="ml-15 flex-grow-1">
                                        <span class="leaderboard-name-sm">
                                            {{ $mostPoint->user->full_name }}
                                        </span>
                                        <span class="leaderboard-points-sm">
                                            {{ $mostPoint->total_points }} {{ trans('update.points') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>

                </div>
            @endif

        </div>
    </div>

    <section class="mt-35 kemetic-section">
        <h2 class="section-title kemetic-title">
            {{ trans('update.points_statistics') }}
        </h2>

        @if(!empty($rewards))

            <div class="kemetic-table-card mt-25 p-25 p-lg-35">
                <div class="table-responsive">
                    <table class="table kemetic-table align-middle">
                        <thead>
                            <tr class="kemetic-row">
                                <th class="text-left">
                                    {{ trans('public.title') }}
                                </th>
                                <th class="text-center">
                                    {{ trans('update.points') }}
                                </th>
                                <th class="text-center">
                                    {{ trans('public.type') }}
                                </th>
                                <th class="text-center">
                                    {{ trans('public.date_time') }}
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($rewards as $reward)
                                <tr class="kemetic-row">
                                    <td class="text-left kemetic-table-title">
                                        {{ trans('update.reward_type_'.$reward->type) }}
                                    </td>

                                    <td class="text-center kemetic-points">
                                        {{ $reward->score }}
                                    </td>

                                    <td class="text-center">
                                        @if($reward->status == \App\Models\RewardAccounting::ADDICTION)
                                            <span class="kemetic-badge kemetic-add">
                                                {{ trans('update.add') }}
                                            </span>
                                        @else
                                            <span class="kemetic-badge kemetic-minus">
                                                {{ trans('update.minus') }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-center kemetic-muted">
                                        {{ dateTimeFormat($reward->created_at, 'j F Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="my-30 kemetic-pagination">
                {{ $rewards->links('vendor.pagination.panel') }}
            </div>

        @else

            @include(getTemplate() . '.includes.no-result',[
                'file_name' => 'quiz.png',
                'title' => trans('update.reward_no_result'),
                'hint' => nl2br(trans('update.reward_no_result_hint')),
            ])

        @endif
    </section>

    @if(!empty($rewardsSettings['exchangeable']) && $rewardsSettings['exchangeable'] == '1')
        @include('web.default.panel.rewards.exchange_modal')
    @endif
@endsection

@push('scripts_bottom')
    <script>
        var exchangeSuccessAlertTitleLang = '{{ trans('update.exchange_success_alert_title') }}';
        var exchangeSuccessAlertDescLang = '{{ trans('update.exchange_success_alert_desc') }}';
        var exchangeErrorAlertTitleLang = '{{ trans('update.exchange_error_alert_title') }}';
        var exchangeErrorAlertDescLang = '{{ trans('update.exchange_error_alert_desc') }}';
    </script>
    <script src="/assets/default/js/panel/reward.min.js"></script>
@endpush
