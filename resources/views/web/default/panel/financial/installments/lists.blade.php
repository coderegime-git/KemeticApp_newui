@extends('web.default.layouts.newapp')

<style>
  /* KEMETIC STATS */
.kemetic-stat-section {
    margin-top: 25px;
}

.kemetic-title {
    font-size: 22px;
    font-weight: 700;
    color: #F2C94C;
    margin-bottom: 18px;
}

/* CARD */
.kemetic-stat-card {
    background: linear-gradient(180deg, #121212, #0b0b0b);
    border: 1px solid #262626;
    border-radius: 18px;
    padding: 26px 18px;
}

/* ITEM */
.kemetic-stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
}

/* ICON */
.kemetic-stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: rgba(242, 201, 76, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
}
.kemetic-stat-icon img {
    width: 28px;
    filter: invert(0.9);
}

/* VALUE */
.kemetic-stat-value {
    font-size: 30px;
    font-weight: 700;
    color: #F2C94C;
}

/* LABEL */
.kemetic-stat-label {
    font-size: 14px;
    color: #9a9a9a;
}

/* MOBILE */
@media (max-width: 768px) {
    .kemetic-stat-card {
        padding: 20px 12px;
    }
    .kemetic-stat-value {
        font-size: 24px;
    }
}

/* ALERT */
.kemetic-alert {
    background: rgba(242, 201, 76, 0.1);
    border: 1px solid rgba(242, 201, 76, 0.3);
    border-radius: 16px;
    padding: 15px 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
}
.kemetic-alert-icon {
    background: #F2C94C;
    color: #000;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.kemetic-alert-content {
    flex: 1;
}
.kemetic-alert-title {
    color: #F2C94C;
    font-weight: 700;
    font-size: 14px;
    margin-bottom: 4px;
}
.kemetic-alert-text {
    color: #b5b5b5;
    font-size: 12px;
}

/* INSTALLMENT CARD */
.kemetic-installment-card {
    background: linear-gradient(180deg, #121212, #0a0a0a);
    border: 1px solid #262626;
    border-radius: 18px;
    margin-bottom: 25px;
    overflow: hidden;
    display: flex;
    transition: 0.3s ease;
}
.kemetic-installment-card:hover {
    transform: translateY(-5px);
    border-color: rgba(242,201,76,0.3);
    box-shadow: 0 12px 30px rgba(242,201,76,0.15);
}

/* IMAGE BOX */
.kemetic-image-box {
    width: 240px;
    position: relative;
    overflow: hidden;
    flex-shrink: 0;
    border-right: 1px solid #262626;
}
.kemetic-image-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.kemetic-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #1a1a1a;
}
.kemetic-image-placeholder img {
    width: 80px;
    height: 80px;
    object-fit: contain;
    opacity: 0.7;
}

/* BADGES */
.kemetic-badges {
    position: absolute;
    top: 10px;
    left: 10px;
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.kemetic-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
}
.kemetic-badge.completed {
    background: #1f3d2b;
    color: #2ecc71;
}
.kemetic-badge.open {
    background: rgba(242,201,76,0.15);
    color: #F2C94C;
}
.kemetic-badge.rejected, .kemetic-badge.canceled {
    background: #3d1f1f;
    color: #e74c3c;
}
.kemetic-badge.pending {
    background: #3d2e1f;
    color: #f39c12;
}
.kemetic-badge.refunded {
    background: #2c3e50;
    color: #3498db;
}
.kemetic-badge.overdue {
    background: #3d1f1f;
    color: #e74c3c;
    border: 1px solid #e74c3c;
}

/* CARD BODY */
.kemetic-card-body {
    padding: 20px;
    flex: 1;
}
.kemetic-card-title {
    color: #F2C94C;
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 5px;
}
.kemetic-card-subtitle {
    color: #888;
    font-size: 12px;
    margin-bottom: 15px;
}

/* STATS GRID */
.kemetic-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 15px;
}
.kemetic-stat-row {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.kemetic-stat-label {
    color: #888;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.kemetic-stat-value {
    color: #fff;
    font-size: 14px;
    font-weight: 600;
}
.kemetic-stat-value.price {
    color: #F2C94C;
}
.kemetic-stat-value.date {
    color: #b5b5b5;
}

/* OVERDUE TAG */
.kemetic-overdue-tag {
    background: #3d1f1f;
    color: #e74c3c;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    margin-left: 10px;
}

/* ACTIONS DROPDOWN */
.kemetic-actions {
    position: absolute;
    top: 10px;
    right: 10px;
}
.kemetic-actions button {
    background: rgba(0,0,0,0.5);
    border: 1px solid #262626;
    border-radius: 8px;
    padding: 5px 10px;
    color: #F2C94C;
}
.kemetic-actions .dropdown-menu {
    background: #121212;
    border: 1px solid #262626;
    border-radius: 12px;
    padding: 8px 0;
    min-width: 180px;
}
.kemetic-actions .dropdown-item {
    color: #F2C94C;
    padding: 8px 16px;
    font-size: 13px;
    transition: 0.2s ease;
}
.kemetic-actions .dropdown-item:hover {
    background: rgba(242,201,76,0.1);
    color: #fff;
}
.kemetic-actions .dropdown-item.text-danger {
    color: #e74c3c !important;
}
.kemetic-actions .dropdown-item.text-danger:hover {
    background: rgba(231,76,60,0.1);
}

/* NO RESULT */
.no-result-card {
    background: linear-gradient(180deg,#121212,#0a0a0a);
    border:1px solid #262626;
    border-radius:18px;
    padding:60px 40px;
    text-align: center;
}
.no-result-card img {
    opacity: 0.7;
    margin-bottom: 20px;
    width: 120px;
}
.no-result-card h3 {
    color: #F2C94C;
    font-size: 20px;
    margin-bottom: 10px;
}
.no-result-card p {
    color: #888;
    font-size: 14px;
    max-width: 400px;
    margin: 0 auto;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .kemetic-installment-card {
        flex-direction: column;
    }
    .kemetic-image-box {
        width: 100%;
        height: 200px;
        border-right: none;
        border-bottom: 1px solid #262626;
    }
    .kemetic-stats-grid {
        grid-template-columns: 1fr 1fr;
    }
}
</style>

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
@endpush

@section('content')

    {{-- Overdue Alert --}}
    @if(!empty($overdueInstallmentsCount) and $overdueInstallmentsCount > 0)
        <div class="kemetic-alert">
            <div class="kemetic-alert-icon">
                <i data-feather="credit-card" width="18" height="18"></i>
            </div>
            <div class="kemetic-alert-content">
                <div class="kemetic-alert-title">{{ trans('update.overdue_installments') }}</div>
                <div class="kemetic-alert-text">{{ trans('update.you_have_count_overdue_installments_please_pay_them_to_avoid_restrictions_and_negative_effects_on_your_account',['count' => $overdueInstallmentsCount]) }}</div>
            </div>
        </div>
    @endif

    {{-- Installments Overview --}}
    <section class="kemetic-stat-section">
        <h2 class="kemetic-title">{{ trans('update.installments_overview') }}</h2>

        <div class="kemetic-stat-card">
            <div class="row text-center">
                <div class="col-6 col-md-3">
                    <div class="kemetic-stat-item">
                        <div class="kemetic-stat-icon">
                            <img src="/assets/default/img/activity/129.png" alt="">
                        </div>
                        <div class="kemetic-stat-value">{{ $openInstallmentsCount }}</div>
                        <div class="kemetic-stat-label">{{ trans('update.open_installments') }}</div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="kemetic-stat-item">
                        <div class="kemetic-stat-icon">
                            <img src="/assets/default/img/activity/130.png" alt="">
                        </div>
                        <div class="kemetic-stat-value">{{ $pendingVerificationCount }}</div>
                        <div class="kemetic-stat-label">{{ trans('update.pending_verification') }}</div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="kemetic-stat-item">
                        <div class="kemetic-stat-icon">
                            <img src="/assets/default/img/activity/127.png" alt="">
                        </div>
                        <div class="kemetic-stat-value">{{ $finishedInstallmentsCount }}</div>
                        <div class="kemetic-stat-label">{{ trans('update.finished_installments') }}</div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="kemetic-stat-item">
                        <div class="kemetic-stat-icon">
                            <img src="/assets/default/img/activity/128.png" alt="">
                        </div>
                        <div class="kemetic-stat-value">{{ $overdueInstallmentsCount }}</div>
                        <div class="kemetic-stat-label">{{ trans('update.overdue_installments') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Installments List --}}
    <section class="mt-40">
        <h2 class="kemetic-title">{{ trans('update.my_installments') }}</h2>

        @if(!empty($orders) and count($orders))
            @foreach($orders as $order)
                @php
                    $orderItem = $order->getItem();
                    $itemType = $order->getItemType();
                    $itemPrice = $order->getItemPrice();
                @endphp

                @if(!empty($orderItem))
                    <div class="kemetic-installment-card">
                        <div class="kemetic-image-box">
                            @if(in_array($itemType, ['course', 'bundle']))
                                <img src="{{ $orderItem->getImage() }}" class="img-cover" alt="">
                            @elseif($itemType == 'product')
                                <img src="{{ $orderItem->thumbnail }}" class="img-cover" alt="">
                            @elseif($itemType == "subscribe")
                                <div class="kemetic-image-placeholder">
                                    <img src="/assets/default/img/icons/installment/subscribe_default.svg" alt="">
                                </div>
                            @elseif($itemType == "registrationPackage")
                                <div class="kemetic-image-placeholder">
                                    <img src="/assets/default/img/icons/installment/reg_package_default.svg" alt="">
                                </div>
                            @endif

                            <div class="kemetic-badges">
                                @if($order->isCompleted())
                                    <span class="kemetic-badge completed">{{ trans('update.completed') }}</span>
                                @elseif($order->status == "open")
                                    <span class="kemetic-badge open">{{ trans('public.open') }}</span>
                                @elseif($order->status == "rejected")
                                    <span class="kemetic-badge rejected">{{ trans('public.rejected') }}</span>
                                @elseif($order->status == "canceled")
                                    <span class="kemetic-badge canceled">{{ trans('public.canceled') }}</span>
                                @elseif($order->status == "pending_verification")
                                    <span class="kemetic-badge pending">{{ trans('update.pending_verification') }}</span>
                                @elseif($order->status == "refunded")
                                    <span class="kemetic-badge refunded">{{ trans('update.refunded') }}</span>
                                @endif
                            </div>

                            @if(!in_array($order->status, ['refunded', 'canceled']) or $order->isCompleted())
                                <div class="dropdown kemetic-actions">
                                    <button type="button" data-toggle="dropdown">
                                        <i data-feather="more-vertical" height="18"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        @if($order->status == "open")
                                            <a href="/panel/financial/installments/{{ $order->id }}/pay_upcoming_part" target="_blank" class="dropdown-item">
                                                {{ trans('update.pay_upcoming_part') }}
                                            </a>
                                        @endif

                                        @if(!in_array($order->status, ['refunded', 'canceled']))
                                            <a href="/panel/financial/installments/{{ $order->id }}/details" target="_blank" class="dropdown-item">
                                                {{ trans('update.view_details') }}
                                            </a>
                                        @endif

                                        @if($itemType == "course" and ($order->isCompleted() or $order->status == "open"))
                                            <a href="{{ $orderItem->getLearningPageUrl() }}" target="_blank" class="dropdown-item">
                                                {{ trans('update.learning_page') }}
                                            </a>
                                        @endif

                                        @if($order->status == "pending_verification" and getInstallmentsSettings("allow_cancel_verification"))
                                            <a href="/panel/financial/installments/{{ $order->id }}/cancel" class="dropdown-item text-danger delete-action">
                                                {{ trans('public.cancel') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="kemetic-card-body">
                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                <div>
                                    <h3 class="kemetic-card-title">{{ $orderItem->title }}</h3>
                                    <div class="kemetic-card-subtitle">{{ trans('update.item_type_'.$itemType) }}</div>
                                </div>
                                @if($order->has_overdue)
                                    <span class="kemetic-badge overdue">{{ trans('update.overdue') }}</span>
                                @endif
                            </div>

                            <div class="kemetic-stats-grid">
                                <div class="kemetic-stat-row">
                                    <span class="kemetic-stat-label">{{ trans('panel.purchase_date') }}</span>
                                    <span class="kemetic-stat-value date">{{ dateTimeFormat($order->created_at, 'j M Y H:i') }}</span>
                                </div>

                                <div class="kemetic-stat-row">
                                    <span class="kemetic-stat-label">{{ trans('update.upfront') }}</span>
                                    <span class="kemetic-stat-value price">{{ !empty($order->selectedInstallment->upfront) ? handlePrice($order->selectedInstallment->getUpfront($itemPrice)) : '-' }}</span>
                                </div>

                                <div class="kemetic-stat-row">
                                    <span class="kemetic-stat-label">{{ trans('update.total_installments') }}</span>
                                    <span class="kemetic-stat-value price">{{ trans('update.total_parts_count', ['count' => $order->selectedInstallment->steps_count]) }} ({{ handlePrice($order->selectedInstallment->totalPayments($itemPrice, false)) }})</span>
                                </div>

                                @if($order->status == "open" or $order->status == "pending_verification")
                                    <div class="kemetic-stat-row">
                                        <span class="kemetic-stat-label">{{ trans('update.remained_installments') }}</span>
                                        <span class="kemetic-stat-value price">{{ trans('update.total_parts_count', ['count' => $order->remained_installments_count]) }} ({{ handlePrice($order->remained_installments_amount) }})</span>
                                    </div>

                                    @if(!empty($order->upcoming_installment))
                                        <div class="kemetic-stat-row">
                                            <span class="kemetic-stat-label">{{ trans('update.upcoming_installment') }}</span>
                                            <span class="kemetic-stat-value date">{{ dateTimeFormat((($order->upcoming_installment->deadline * 86400) + $order->created_at), 'j M Y') }}</span>
                                            <span class="kemetic-stat-value price">{{ handlePrice($order->upcoming_installment->getPrice($itemPrice)) }}</span>
                                        </div>
                                    @endif

                                    @if($order->has_overdue)
                                        <div class="kemetic-stat-row">
                                            <span class="kemetic-stat-label">{{ trans('update.overdue_installments') }}</span>
                                            <span class="kemetic-stat-value">{{ $order->overdue_count }}</span>
                                            <span class="kemetic-stat-value price">{{ handlePrice($order->overdue_amount) }}</span>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

            {{-- Pagination --}}
            <div class="my-30" style="padding: 10px;">
                {{ $orders->appends(request()->input())->links('vendor.pagination.panel') }}
            </div>
        @else
            <div class="no-result-card">
                @include('web.default.includes.no-result',[
                    'file_name' => 'webinar.png',
                    'title' => trans('update.you_not_have_any_installment'),
                    'hint' => trans('update.you_not_have_any_installment_hint'),
                ])
            </div>
        @endif
    </section>

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize dropdowns
            $('.dropdown-toggle').dropdown();
            
            // Handle delete confirmation
            $('.delete-action').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                var title = $(this).data('title') || '{{ trans('public.deleteAlertHint') }}';
                var confirmText = $(this).data('confirm') || '{{ trans('public.delete') }}';
                
                if (confirm(title)) {
                    window.location.href = url;
                }
            });
        });
    </script>
@endpush