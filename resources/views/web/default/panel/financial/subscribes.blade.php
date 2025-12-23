@extends('web.default.layouts.newapp')

@push('styles_top')
<style>
/* =============================
   KEMETIC SUBSCRIBE DASHBOARD
============================= */

.kemetic-section {
    background: #141414;
    color: #f5f5f5;
    border-radius: 18px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 12px 30px rgba(0,0,0,.7);
}

.kemetic-section h2.section-title {
    color: #d4af37;
    font-weight: 700;
    margin-bottom: 25px;
    letter-spacing: 0.6px;
}

.activities-container {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.activities-container .activity-box {
    background: #1e1e1e;
    border-radius: 14px;
    padding: 20px;
    flex: 1 1 calc(33% - 20px);
    text-align: center;
    box-shadow: 0 8px 20px rgba(0,0,0,.5);
}

.activities-container .activity-box img {
    width: 64px;
    height: 64px;
}

.activities-container .activity-box strong {
    color: #d4af37;
    font-size: 28px;
    margin-top: 10px;
    display: block;
}

.activities-container .activity-box span {
    color: #9a9a9a;
    font-size: 14px;
}

/* SUBSCRIBE CARDS */
.card.subscribe-plan {
    background: #1e1e1e;
    color: #f5f5f5;
    border-radius: 18px;
    padding: 30px 20px 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,.7);
    position: relative;
    transition: transform 0.3s;
}

.card.subscribe-plan:hover {
    transform: translateY(-8px);
}

.card.subscribe-plan .badge-popular {
    position: absolute;
    top: 15px;
    left: 15px;
    background: #d4af37;
    color: #141414;
    font-weight: 700;
    border-radius: 12px;
    padding: 5px 12px;
}

.card.subscribe-plan .plan-icon img {
    width: 70px;
    height: 70px;
}

.card.subscribe-plan h3 {
    color: #d4af37;
    font-size: 22px;
    margin-top: 15px;
}

.card.subscribe-plan p {
    color: #b5b5b5;
    font-size: 14px;
    margin-top: 5px;
}

.card.subscribe-plan .plan-feature li {
    font-size: 14px;
    margin-top: 8px;
    color: #9a9a9a;
}

.card.subscribe-plan .text-primary {
    color: #d4af37 !important;
}

.btn-primary {
    background: #d4af37;
    color: #141414;
    font-weight: 700;
    border-radius: 18px;
    transition: all 0.3s;
}

.btn-primary:hover {
    background: #b8952c;
    color: #fff;
}

.btn-outline-primary {
    border-color: #d4af37;
    color: #d4af37;
}

.btn-outline-primary:hover {
    background: #d4af37;
    color: #141414;
}
</style>
@endpush

@section('content')
@if($activeSubscribe)
<section class="kemetic-section">
    <h2 class="section-title">{{ trans('financial.my_active_plan') }}</h2>

    <div class="activities-container mt-25">
        <div class="activity-box">
            <img src="/assets/default/img/activity/webinars.svg" alt="">
            <strong>{{ $activeSubscribe->title }}</strong>
            <span>{{ trans('financial.active_plan') }}</span>
        </div>

        <div class="activity-box">
            <img src="/assets/default/img/activity/53.svg" alt="">
            <strong>
                @if($activeSubscribe->infinite_use)
                    {{ trans('update.unlimited') }}
                @else
                    {{ $activeSubscribe->usable_count - $activeSubscribe->used_count }}
                @endif
            </strong>
            <span>{{ trans('financial.remained_downloads') }}</span>
        </div>

        <div class="activity-box">
            <img src="/assets/default/img/activity/54.svg" alt="">
            <strong>{{ $activeSubscribe->days - $dayOfUse }}</strong>
            <span>{{ trans('financial.days_remained') }}</span>
        </div>
    </div>
</section>
@else
@include(getTemplate() . '.includes.no-result',[
    'file_name' => 'subcribe.png',
    'title' => trans('financial.subcribe_no_result'),
    'hint' => nl2br(trans('financial.subcribe_no_result_hint')),
])
@endif

<section class="kemetic-section mt-30">
    <h2 class="section-title">{{ trans('financial.select_a_subscribe_plan') }}</h2>

    <div class="row mt-15">
        @foreach($subscribes as $subscribe)
            @php $subscribeSpecialOffer = $subscribe->activeSpecialOffer(); @endphp

            <div class="col-12 col-sm-6 col-lg-4 mt-15">
                <div class="card subscribe-plan text-center h-100">
                    @if($subscribe->is_popular)
                        <span class="badge badge-popular">{{ trans('panel.popular') }}</span>
                    @elseif(!empty($subscribeSpecialOffer))
                        <span class="badge badge-popular">{{ trans('update.percent_off', ['percent' => $subscribeSpecialOffer->percent]) }}</span>
                    @endif

                    <div class="plan-icon mt-3">
                        <img src="{{ $subscribe->icon }}" class="img-cover" alt="">
                    </div>

                    <h3>{{ $subscribe->title }}</h3>
                    <p>{{ $subscribe->description }}</p>

                    <div class="d-flex align-items-start justify-content-center mt-3">
                        @if(!empty($subscribe->price) && $subscribe->price > 0)
                            @if(!empty($subscribeSpecialOffer))
                                <div class="d-flex align-items-end">
                                    <span class="font-36 text-primary">{{ handlePrice($subscribe->getPrice(), true, true, false, null, true) }}</span>
                                    <span class="font-14 text-gray ml-2 text-decoration-line-through">{{ handlePrice($subscribe->price, true, true, false, null, true) }}</span>
                                </div>
                            @else
                                <span class="font-36 text-primary hover-text-price">{{ handlePrice($subscribe->price, true, true, false, null, true) }}</span>
                            @endif
                        @else
                            <span class="font-36 text-primary hover-text-price">{{ trans('public.free') }}</span>
                        @endif
                    </div>

                    <ul class="plan-feature mt-20">
                        <li>{{ $subscribe->days }} {{ trans('financial.days_of_subscription') }}</li>
                        <li>
                            @if($subscribe->infinite_use)
                                {{ trans('update.unlimited') }}
                            @else
                                {{ $subscribe->usable_count }}
                            @endif
                            <span class="ml-2">{{ trans('update.subscribes') }}</span>
                        </li>
                    </ul>

                    <form action="/panel/financial/recurringPay-subscribes?auto_redirect=1" method="post" class="mt-3">
                        {{ csrf_field() }}
                        <input name="amount" value="{{ $subscribe->price }}" type="hidden">
                        <input name="id" value="{{ $subscribe->id }}" type="hidden">

                        <div class="d-flex align-items-center mt-30 w-100">
                            <button type="submit" class="btn btn-primary flex-grow-1">{{ trans('update.purchase') }}</button>
                            @if(!empty($subscribe->has_installment))
                                <a href="/panel/financial/subscribes/{{ $subscribe->id }}/installments" class="btn btn-outline-primary flex-grow-1 ml-2">{{ trans('update.installments') }}</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection

@push('scripts_bottom')
<script src="/assets/default/js/panel/financial/subscribes.min.js"></script>
@endpush
