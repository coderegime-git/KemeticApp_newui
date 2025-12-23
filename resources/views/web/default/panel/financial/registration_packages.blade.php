@extends('web.default.layouts.newapp')

@push('styles_top')
<style>
/* =============================
   KEMETIC PLAN DASHBOARD
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
    flex: 1 1 calc(33.333% - 20px);
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

/* ACCOUNT STATISTICS */
.registration-package-statistics {
    background: #1e1e1e;
    border-radius: 14px;
    padding: 15px;
    flex: 1;
    margin: 5px;
    text-align: center;
    box-shadow: 0 8px 20px rgba(0,0,0,.5);
}

.registration-package-statistics-icon img {
    width: 36px;
    height: 36px;
}

.registration-package-statistics span.font-weight-bold {
    color: #d4af37;
}

/* SUBSCRIBE PLAN */
.subscribe-plan {
    background: #1e1e1e;
    border-radius: 18px;
    padding: 30px 20px;
    box-shadow: 0 12px 30px rgba(0,0,0,.7);
    text-align: center;
    position: relative;
    transition: all 0.3s;
}

.subscribe-plan:hover {
    transform: translateY(-5px);
    box-shadow: 0 18px 40px rgba(0,0,0,.8);
}

.subscribe-plan .badge-popular {
    position: absolute;
    top: 15px;
    left: 50%;
    transform: translateX(-50%);
    background: #d4af37;
    color: #141414;
    font-weight: 700;
    border-radius: 12px;
}

.subscribe-plan h3 {
    color: #d4af37;
    margin-top: 20px;
}

.subscribe-plan p {
    color: #9a9a9a;
    font-size: 14px;
}

.subscribe-plan ul.plan-feature li {
    color: #f5f5f5;
    font-size: 14px;
    margin-top: 10px;
}

.subscribe-plan .btn-primary {
    background: #d4af37;
    color: #141414;
    font-weight: 700;
    border-radius: 18px;
    padding: 10px 20px;
    transition: all 0.3s;
}

.subscribe-plan .btn-primary:hover {
    background: #b8952c;
}

.subscribe-plan .btn-outline-primary {
    border-color: #d4af37;
    color: #d4af37;
}

.subscribe-plan .btn-outline-primary:hover {
    background: #d4af37;
    color: #141414;
}
</style>
@endpush

@section('content')

@if(!empty($activePackage))
<section class="kemetic-section">
    <h2 class="section-title">{{ trans('financial.my_active_plan') }}</h2>

    <div class="activities-container mt-25">
        <div class="activity-box">
            <img src="/assets/default/img/activity/webinars.svg" alt="">
            <strong>{{ $activePackage->title }}</strong>
            <span>{{ trans('financial.active_plan') }}</span>
        </div>

        <div class="activity-box">
            <img src="/assets/default/img/activity/53.svg" alt="">
            <strong>{{ dateTimeFormat($activePackage->activation_date, 'j M Y') }}</strong>
            <span>{{ trans('update.activation_date') }}</span>
        </div>

        <div class="activity-box">
            <img src="/assets/default/img/activity/54.svg" alt="">
            <strong>{{ $activePackage->days_remained ?? trans('update.unlimited') }}</strong>
            <span>{{ trans('financial.days_remained') }}</span>
        </div>
    </div>
</section>
@endif

<section class="kemetic-section mt-30">
    <h2 class="section-title">{{ trans('update.account_statistics') }}</h2>

    <div class="d-flex flex-wrap justify-content-around mt-15">
        <div class="registration-package-statistics">
            <img src="/assets/default/img/icons/play.svg" alt="">
            <span class="font-weight-bold mt-5">
                @if(!empty($activePackage) and isset($activePackage->courses_count))
                    {{ $accountStatistics['myCoursesCount'] }}/{{ $activePackage->courses_count }}
                @else
                    {{ trans('update.unlimited') }}
                @endif
            </span>
            <span>{{ trans('product.courses') }}</span>
        </div>

        <div class="registration-package-statistics">
            <img src="/assets/default/img/icons/video-2.svg" alt="">
            <span class="font-weight-bold mt-5">
                @if(!empty($activePackage) and isset($activePackage->courses_capacity))
                    {{ $activePackage->courses_capacity }}
                @else
                    {{ trans('update.unlimited') }}
                @endif
            </span>
            <span>{{ trans('update.live_students') }}</span>
        </div>

        <div class="registration-package-statistics">
            <img src="/assets/default/img/icons/clock.svg" alt="">
            <span class="font-weight-bold mt-5">
                @if(!empty($activePackage) and isset($activePackage->meeting_count))
                    {{ $accountStatistics['myMeetingCount'] }}/{{ $activePackage->meeting_count }}
                @else
                    {{ trans('update.unlimited') }}
                @endif
            </span>
            <span>{{ trans('update.meeting_hours') }}</span>
        </div>

        <div class="registration-package-statistics">
            <img src="/assets/default/img/activity/products.svg" alt="">
            <span class="font-weight-bold mt-5">
                @if(!empty($activePackage) and isset($activePackage->product_count))
                    {{ $accountStatistics['myProductCount'] }}/{{ $activePackage->product_count }}
                @else
                    {{ trans('update.unlimited') }}
                @endif
            </span>
            <span>{{ trans('update.products') }}</span>
        </div>

        @if($authUser->isOrganization())
        <div class="registration-package-statistics">
            <img src="/assets/default/img/icons/users.svg" alt="">
            <span class="font-weight-bold mt-5">
                @if(!empty($activePackage) and isset($activePackage->instructors_count))
                    {{ $accountStatistics['myInstructorsCount'] }}/{{ $activePackage->instructors_count }}
                @else
                    {{ trans('update.unlimited') }}
                @endif
            </span>
            <span>{{ trans('home.instructors') }}</span>
        </div>

        <div class="registration-package-statistics">
            <img src="/assets/default/img/icons/user.svg" alt="">
            <span class="font-weight-bold mt-5">
                @if(!empty($activePackage) and isset($activePackage->students_count))
                    {{ $accountStatistics['myStudentsCount'] }}/{{ $activePackage->students_count }}
                @else
                    {{ trans('update.unlimited') }}
                @endif
            </span>
            <span>{{ trans('public.students') }}</span>
        </div>
        @endif
    </div>
</section>

<section class="kemetic-section mt-30">
    <h2 class="section-title">{{ trans('update.upgrade_your_account') }}</h2>

    <div class="row mt-15">
        @foreach($packages as $package)
            @php $specialOffer = $package->activeSpecialOffer(); @endphp

            <div class="col-12 col-sm-6 col-lg-3 mt-15">
                <div class="subscribe-plan">
                    @if(!empty($activePackage) and $activePackage->package_id == $package->id)
                        <span class="badge badge-popular">{{ trans('update.activated') }}</span>
                    @elseif(!empty($specialOffer))
                        <span class="badge badge-popular">{{ trans('update.percent_off', ['percent' => $specialOffer->percent]) }}</span>
                    @endif

                    <div class="plan-icon">
                        <img src="{{ $package->icon }}" alt="">
                    </div>

                    <h3>{{ $package->title }}</h3>
                    <p>{{ $package->description }}</p>

                    <div class="d-flex justify-content-center mt-30">
                        @if(!empty($package->price) and $package->price > 0)
                            @if(!empty($specialOffer))
                                <div class="d-flex align-items-end">
                                    <span class="font-36 text-primary">{{ handlePrice($package->getPrice(), true, true, false, null, true) }}</span>
                                    <span class="font-14 text-gray ml-5 text-decoration-line-through">{{ handlePrice($package->price, true, true, false, null, true) }}</span>
                                </div>
                            @else
                                <span class="font-36 text-primary">{{ handlePrice($package->price, true, true, false, null, true) }}</span>
                            @endif
                        @else
                            <span class="font-36 text-primary">{{ trans('public.free') }}</span>
                        @endif
                    </div>

                    <ul class="plan-feature mt-20">
                        <li>{{ !isset($package->days) ? trans('update.unlimited'): $package->days }} {{ trans('public.days') }}</li>
                        <li>{{ !isset($package->courses_count) ? trans('update.unlimited') : $package->courses_count }} {{ trans('product.courses') }}</li>
                        <li>{{ !isset($package->courses_capacity) ? trans('update.unlimited') : $package->courses_capacity }} {{ trans('update.live_students') }}</li>
                        <li>{{ !isset($package->meeting_count) ? trans('update.unlimited') : $package->meeting_count }} {{ trans('update.meeting_hours') }}</li>
                        <li>{{ !isset($package->product_count) ? trans('update.unlimited') : $package->product_count }} {{ trans('update.products') }}</li>

                        @if($authUser->isOrganization())
                            <li>{{ $package->instructors_count ?? trans('update.unlimited') }} {{ trans('home.instructors') }}</li>
                            <li>{{ $package->students_count ?? trans('update.unlimited') }} {{ trans('public.students') }}</li>
                        @endif
                    </ul>

                    <form action="{{ route('payRegistrationPackage') }}" method="post" class="btn-block mt-20">
                        {{ csrf_field() }}
                        <input name="id" value="{{ $package->id }}" type="hidden">

                        <div class="d-flex align-items-center w-100">
                            <button type="submit" class="btn btn-primary flex-grow-1">{{ trans('update.upgrade') }}</button>

                            @if(!empty($package->has_installment))
                                <a href="/panel/financial/registration-packages/{{ $package->id }}/installments" class="btn btn-outline-primary flex-grow-1 ml-10">{{ trans('update.installments') }}</a>
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
