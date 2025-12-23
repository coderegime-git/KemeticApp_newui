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

/* ALERT */
.danger-transparent-alert {
    background: var(--k-gold-soft);
    color: var(--k-text);
    border-radius: var(--k-radius);
    padding: 15px;
    display: flex;
    align-items: center;
    margin-bottom: 25px;
}
.danger-transparent-alert__icon {
    background: var(--k-gold);
    color: #0b0b0b;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Activities Cards */
.activities-container .col-6 {
    margin-bottom: 25px;
}
.activities-container .col-6 .d-flex {
    background: var(--k-card);
    padding: 20px;
    border-radius: var(--k-radius);
    box-shadow: var(--k-shadow);
    transition: transform 0.3s;
}
.activities-container .col-6 .d-flex:hover {
    transform: translateY(-5px);
}

/* Installment Card */
.panel-installment-card {
    background: var(--k-card);
    border-radius: var(--k-radius);
    box-shadow: var(--k-shadow);
    margin-bottom: 25px;
    overflow: hidden;
    display: flex;
    transition: transform 0.3s;
}
.panel-installment-card:hover {
    transform: translateY(-5px);
}

.panel-installment-card .image-box {
    width: 200px;
    height: 200px;
    position: relative;
    overflow: hidden;
    flex-shrink: 0;
    border-right: 1px solid var(--k-border);
}
.panel-installment-card .image-box img,
.panel-installment-card .image-box .d-flex img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.badges-lists {
    position: absolute;
    top: 10px;
    left: 10px;
    display: flex;
    flex-direction: column;
}
.badge {
    background: var(--k-gold);
    color: #0b0b0b;
    border-radius: var(--k-radius);
    padding: 4px 10px;
    font-size: 12px;
    margin-bottom: 5px;
    font-weight: bold;
}
.badge-outlined-danger {
    border: 1px solid #ff4d4d;
    background: transparent;
    color: #ff4d4d;
}

/* Card Body */
.webinar-card-body {
    padding: 20px;
    display: flex;
    flex-direction: column;
    flex: 1;
    justify-content: space-between;
}

.webinar-card-body h3 {
    color: var(--k-gold);
}

.stat-title {
    font-size: 12px;
    color: var(--k-muted);
}
.stat-value {
    font-size: 14px;
    color: var(--k-text);
    font-weight: 600;
}

/* Dropdown Menu */
.dropdown-menu {
    background: var(--k-card);
    border-radius: var(--k-radius);
    box-shadow: var(--k-shadow);
    border: none;
}

/* Buttons / Links */
.webinar-actions {
    color: var(--k-gold);
    font-weight: 500;
}
.webinar-actions:hover {
    color: #b8952c;
}
.delete-action {
    color: #ff4d4d !important;
}

/* Responsive */
@media (max-width: 768px) {
    .panel-installment-card {
        flex-direction: column;
    }
    .panel-installment-card .image-box {
        width: 100%;
        height: 180px;
        border-right: none;
        border-bottom: 1px solid var(--k-border);
    }
}
</style>
@endpush

@section('content')
<div class="kemetic-page">

    {{-- Overdue Alert --}}
    @if(!empty($overdueInstallmentsCount) and $overdueInstallmentsCount > 0)
        <div class="d-flex align-items-center mb-20 p-15 danger-transparent-alert">
            <div class="danger-transparent-alert__icon d-flex align-items-center justify-content-center">
                <i data-feather="credit-card" width="18" height="18"></i>
            </div>
            <div class="ml-10">
                <div class="font-14 font-weight-bold ">{{ trans('update.overdue_installments') }}</div>
                <div class="font-12 ">{{ trans('update.you_have_count_overdue_installments_please_pay_them_to_avoid_restrictions_and_negative_effects_on_your_account',['count' => $overdueInstallmentsCount]) }}</div>
            </div>
        </div>
    @endif

    {{-- Installments Overview --}}
    <section>
        <h2 class="section-title">{{ trans('update.installments_overview') }}</h2>

        <div class="activities-container mt-25 p-20 p-lg-35">
            <div class="row">
                <div class="col-6 col-md-3 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/default/img/activity/129.png" width="64" height="64" alt="">
                        <strong class="font-30 font-weight-bold mt-5">{{ $openInstallmentsCount }}</strong>
                        <span class="font-16 font-weight-500">{{ trans('update.open_installments') }}</span>
                    </div>
                </div>

                <div class="col-6 col-md-3 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/default/img/activity/130.png" width="64" height="64" alt="">
                        <strong class="font-30 font-weight-bold mt-5">{{ $pendingVerificationCount }}</strong>
                        <span class="font-16 font-weight-500">{{ trans('update.pending_verification') }}</span>
                    </div>
                </div>

                <div class="col-6 col-md-3 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/default/img/activity/127.png" width="64" height="64" alt="">
                        <strong class="font-30 font-weight-bold mt-5">{{ $finishedInstallmentsCount }}</strong>
                        <span class="font-16 font-weight-500">{{ trans('update.finished_installments') }}</span>
                    </div>
                </div>

                <div class="col-6 col-md-3 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/default/img/activity/128.png" width="64" height="64" alt="">
                        <strong class="font-30 font-weight-bold mt-5">{{ $overdueInstallmentsCount }}</strong>
                        <span class="font-16 font-weight-500">{{ trans('update.overdue_installments') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Installments List --}}
    <section class="mt-25">
        <h2 class="section-title">{{ trans('update.my_installments') }}</h2>

        @if(!empty($orders) and count($orders))
            @foreach($orders as $order)
                @php
                    $orderItem = $order->getItem();
                    $itemType = $order->getItemType();
                    $itemPrice = $order->getItemPrice();
                @endphp

                @if(!empty($orderItem))
                    <div class="panel-installment-card">
                        <div class="image-box">
                            @if(in_array($itemType, ['course', 'bundle']))
                                <img src="{{ $orderItem->getImage() }}" class="img-cover" alt="">
                            @elseif($itemType == 'product')
                                <img src="{{ $orderItem->thumbnail }}" class="img-cover" alt="">
                            @elseif($itemType == "subscribe")
                                <div class="d-flex align-items-center justify-content-center w-100 h-100">
                                    <img src="/assets/default/img/icons/installment/subscribe_default.svg" alt="">
                                </div>
                            @elseif($itemType == "registrationPackage")
                                <div class="d-flex align-items-center justify-content-center w-100 h-100">
                                    <img src="/assets/default/img/icons/installment/reg_package_default.svg" alt="">
                                </div>
                            @endif

                            <div class="badges-lists">
                                @if($order->isCompleted())
                                    <span class="badge">{{ trans('update.completed') }}</span>
                                @elseif($order->status == "open")
                                    <span class="badge">{{  trans('public.open') }}</span>
                                @elseif($order->status == "rejected")
                                    <span class="badge badge-outlined-danger">{{  trans('public.rejected') }}</span>
                                @elseif($order->status == "canceled")
                                    <span class="badge badge-outlined-danger">{{  trans('public.canceled') }}</span>
                                @elseif($order->status == "pending_verification")
                                    <span class="badge" style="background:#ffb74d;color:#0b0b0b;">{{  trans('update.pending_verification') }}</span>
                                @elseif($order->status == "refunded")
                                    <span class="badge">{{  trans('update.refunded') }}</span>
                                @endif
                            </div>

                            @if(!in_array($order->status, ['refunded', 'canceled']) or $order->isCompleted())
                                <div class="btn-group dropdown table-actions">
                                    <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i data-feather="more-vertical" height="20"></i>
                                    </button>
                                    <div class="dropdown-menu ">

                                        @if($order->status == "open")
                                            <a href="/panel/financial/installments/{{ $order->id }}/pay_upcoming_part" target="_blank" class="webinar-actions d-block mt-10">{{ trans('update.pay_upcoming_part') }}</a>
                                        @endif

                                        @if(!in_array($order->status, ['refunded', 'canceled']))
                                            <a href="/panel/financial/installments/{{ $order->id }}/details" target="_blank" class="webinar-actions d-block mt-10">{{ trans('update.view_details') }}</a>
                                        @endif

                                        @if($itemType == "course" and ($order->isCompleted() or $order->status == "open"))
                                            <a href="{{ $orderItem->getLearningPageUrl() }}" target="_blank" class="webinar-actions d-block mt-10">{{ trans('update.learning_page') }}</a>
                                        @endif

                                        {{--@if($order->isCompleted() or $order->status == "open")
                                            <a href="/panel/financial/installments/{{ $order->id }}/refund" class="webinar-actions d-block mt-10 delete-action">{{ trans('update.refund') }}</a>
                                        @endif--}}

                                        @if($order->status == "pending_verification" and getInstallmentsSettings("allow_cancel_verification"))
                                            <a href="/panel/financial/installments/{{ $order->id }}/cancel" class="webinar-actions d-block mt-10 text-danger delete-action" data-title="{{ trans('public.deleteAlertHint') }}" data-confirm="{{ trans('update.yes_cancel') }}">{{ trans('public.cancel') }}</a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="webinar-card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <h3>{{ $orderItem->title }}</h3>
                                @if($order->has_overdue)
                                    <span class="badge badge-outlined-danger">{{ trans('update.overdue') }}</span>
                                @endif
                            </div>

                            <div class="d-flex align-items-center justify-content-between flex-wrap mt-20">
                                <div class="d-flex flex-column">
                                    <span class="stat-title">{{ trans('update.item_type') }}:</span>
                                    <span class="stat-value">{{ trans('update.item_type_'.$itemType) }}</span>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="stat-title">{{ trans('panel.purchase_date') }}:</span>
                                    <span class="stat-value">{{ dateTimeFormat($order->created_at, 'j M Y H:i') }}</span>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="stat-title">{{ trans('update.upfront') }}:</span>
                                    <span class="stat-value">{{ !empty($order->selectedInstallment->upfront) ? handlePrice($order->selectedInstallment->getUpfront($itemPrice)) : '-' }}</span>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="stat-title">{{ trans('update.total_installments') }}:</span>
                                    <span class="stat-value">{{ trans('update.total_parts_count', ['count' => $order->selectedInstallment->steps_count]) }} ({{ handlePrice($order->selectedInstallment->totalPayments($itemPrice, false)) }})</span>
                                </div>
                                @if($order->status == "open" or $order->status == "pending_verification")
                                    <div class="d-flex flex-column">
                                        <span class="stat-title">{{ trans('update.remained_installments') }}:</span>
                                        <span class="stat-value">{{ trans('update.total_parts_count', ['count' => $order->remained_installments_count]) }} ({{ handlePrice($order->remained_installments_amount) }})</span>
                                    </div>

                                    @if(!empty($order->upcoming_installment))
                                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                            <span class="stat-title">{{ trans('update.upcoming_installment') }}:</span>
                                            <span class="stat-value">{{ dateTimeFormat((($order->upcoming_installment->deadline * 86400) + $order->created_at), 'j M Y') }} ({{ handlePrice($order->upcoming_installment->getPrice($itemPrice)) }})</span>
                                        </div>
                                    @endif

                                    @if($order->has_overdue)
                                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                            <span class="stat-title">{{ trans('update.overdue_installments') }}:</span>
                                            <span class="stat-value">{{ $order->overdue_count }} ({{ handlePrice($order->overdue_amount) }})</span>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

            <div class="my-30">
                {{ $orders->appends(request()->input())->links('vendor.pagination.panel') }}
            </div>
        @else
            @include('web.default.includes.no-result',[
                'file_name' => 'webinar.png',
                'title' => trans('update.you_not_have_any_installment'),
                'hint' => trans('update.you_not_have_any_installment_hint'),
            ])
        @endif
    </section>

</div>
@endsection

@push('scripts_bottom')
@endpush