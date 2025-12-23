@extends('web.default.layouts.newapp')

@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
<link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">

<style>
    :root {
        --k-bg: #0b0b0b;
        --k-card: #141414;
        --k-gold: #f2c94c;
        --k-gold-soft: #e6b93d;
        --k-border: rgba(242,201,76,0.25);
        --k-text: #eaeaea;
        --k-muted: #9a9a9a;
        --k-radius: 16px;
    }

    body {
        background: var(--k-bg);
        color: var(--k-text);
        font-family: 'Nunito', sans-serif;
    }

    .section-title {
        font-size: 1.5rem;
        color: var(--k-gold);
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .activities-container, .panel-section-card {
        background: var(--k-card);
        border-radius: var(--k-radius);
        border: 1px solid var(--k-border);
        box-shadow: 0 4px 20px rgba(242,201,76,0.1);
        padding:10px;
    }

    .activities-container .d-flex {
        padding: 1rem;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: var(--k-radius);
    }

    .activities-container img {
        margin-bottom: 0.5rem;
    }

    .text-dark-blue {
        color: var(--k-gold);
        font-weight: bold;
    }

    .text-gray {
        color: var(--k-muted);
    }

    .custom-table {
        background: var(--k-card);
        color: var(--k-text);
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    .custom-table th, .custom-table td {
        border-top: 1px solid var(--k-border);
        vertical-align: middle;
    }

    .custom-table th {
        color: var(--k-gold);
        font-weight: 600;
    }

    .custom-table td {
        color: var(--k-text);
    }

    .form-control {
        background: #1f1f1f;
        color: var(--k-text);
        border: 1px solid var(--k-border);
        border-radius: var(--k-radius);
    }

    .form-control::placeholder {
        color: var(--k-muted);
    }

    .input-group-text {
        background: #1f1f1f;
        border: 1px solid var(--k-border);
        color: var(--k-text);
    }

    .btn-primary {
        background-color: var(--k-gold);
        border: none;
        color: #000;
        border-radius: var(--k-radius);
    }

    .btn-primary:hover {
        background-color: var(--k-gold-soft);
    }

    .select2-container--default .select2-selection--single {
        background-color: #1f1f1f;
        border: 1px solid var(--k-border);
        border-radius: var(--k-radius);
        color: var(--k-text);
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: var(--k-text) transparent transparent transparent;
    }

    .user-inline-avatar .avatar {
        border-radius: var(--k-radius);
        overflow: hidden;
    }

    .btn-transparent {
        color: var(--k-text);
    }

    .btn-transparent:hover {
        color: var(--k-gold);
    }

    .dropdown-menu {
        background: var(--k-card);
        border: 1px solid var(--k-border);
        border-radius: var(--k-radius);
    }
</style>
@endpush

@section('content')
<section>
    <h2 class="section-title">{{ trans('update.orders_statistics') }}</h2>

    <div class="activities-container mt-25 p-20 p-lg-35 d-flex flex-wrap justify-content-between">
        <div class="col-6 col-md-3 mb-4">
            <div class="d-flex flex-column align-items-center">
                <img src="/assets/default/img/activity/physical_product3.png" width="64" height="64" alt="">
                <strong class="font-30 font-weight-bold mt-2 text-dark-blue">{{ $totalOrders }}</strong>
                <span class="font-16 font-weight-500 text-gray">{{ trans('update.total_orders') }}</span>
            </div>
        </div>

        <div class="col-6 col-md-3 mb-4">
            <div class="d-flex flex-column align-items-center">
                <img src="/assets/default/img/activity/physical_product2.png" width="64" height="64" alt="">
                <strong class="font-30 font-weight-bold mt-2 text-dark-blue">{{ $pendingOrders }}</strong>
                <span class="font-16 font-weight-500 text-gray">{{ trans('update.pending_orders') }}</span>
            </div>
        </div>

        <div class="col-6 col-md-3 mb-4">
            <div class="d-flex flex-column align-items-center">
                <img src="/assets/default/img/activity/physical_product1.png" width="64" height="64" alt="">
                <strong class="font-30 font-weight-bold mt-2 text-dark-blue">{{ $canceledOrders }}</strong>
                <span class="font-16 font-weight-500 text-gray">{{ trans('update.canceled_orders') }}</span>
            </div>
        </div>

        <div class="col-6 col-md-3 mb-4">
            <div class="d-flex flex-column align-items-center">
                <img src="/assets/default/img/activity/33.png" width="64" height="64" alt="">
                <strong class="font-30 font-weight-bold mt-2 text-dark-blue">{{ (!empty($totalSales) && $totalSales > 0) ? handlePrice($totalSales) : 0 }}</strong>
                <span class="font-16 font-weight-500 text-gray">{{ trans('financial.total_sales') }}</span>
            </div>
        </div>
    </div>
</section>

<section class="mt-25">
    <h2 class="section-title">{{ trans('update.orders_report') }}</h2>

    <div class="panel-section-card py-20 px-25 mt-20">
        <form action="" method="get" class="row">
            <div class="col-12 col-lg-4">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="input-label">{{ trans('public.from') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i data-feather="calendar" width="18" height="18"></i>
                                    </span>
                                </div>
                                <input type="text" name="from" autocomplete="off" class="form-control @if(request()->get('from')) datepicker @else datefilter @endif" value="{{ request()->get('from', null) }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="input-label">{{ trans('public.to') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i data-feather="calendar" width="18" height="18"></i>
                                    </span>
                                </div>
                                <input type="text" name="to" autocomplete="off" class="form-control @if(request()->get('to')) datepicker @else datefilter @endif" value="{{ request()->get('to', null) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="row">
                    <div class="col-12 col-lg-5">
                        <div class="form-group">
                            <label class="input-label">{{ trans('update.customer') }}</label>
                            <select name="customer_id" class="form-control select2">
                                <option value="all">{{ trans('public.all') }}</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" @if(request()->get('customer_id') == $customer->id) selected @endif>{{ $customer->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-lg-3">
                        <div class="form-group">
                            <label class="input-label">{{ trans('public.type') }}</label>
                            <select class="form-control" name="type">
                                <option value="all" @if(request()->get('type')=='all') selected @endif>{{ trans('public.all') }}</option>
                                @foreach(\App\Models\Product::$productTypes as $productType)
                                    <option value="{{ $productType }}" @if(request()->get('type')==$productType) selected @endif>{{ trans('update.product_type_'.$productType) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-lg-3">
                        <div class="form-group">
                            <label class="input-label">{{ trans('public.status') }}</label>
                            <select class="form-control" name="status">
                                <option value="all" @if(request()->get('status')=='all') selected @endif>{{ trans('public.all') }}</option>
                                @foreach(\App\Models\ProductOrder::$status as $orderStatus)
                                    @if($orderStatus != 'pending')
                                        <option value="{{ $orderStatus }}" @if(request()->get('status')==$orderStatus) selected @endif>{{ trans('update.product_order_status_'.$orderStatus) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-2 d-flex align-items-center justify-content-end">
                <button type="submit" class="btn btn-primary w-100 mt-2">{{ trans('public.show_results') }}</button>
            </div>
        </form>
    </div>
</section>

@if(!empty($orders) && !$orders->isEmpty())
<section class="mt-35">
    <h2 class="section-title">{{ trans('update.orders_history') }}</h2>

    <div class="panel-section-card py-20 px-25 mt-20">
        <div class="table-responsive">
            <table class="table text-center custom-table">
                <thead>
                    <tr>
                        <th>{{ trans('update.customer') }}</th>
                        <th class="text-left">{{ trans('update.order_id') }}</th>
                        <th>{{ trans('public.price') }}</th>
                        <th>{{ trans('public.discount') }}</th>
                        <th>{{ trans('financial.total_amount') }}</th>
                        <th>{{ trans('financial.income') }}</th>
                        <th>{{ trans('public.type') }}</th>
                        <th>{{ trans('public.status') }}</th>
                        <th>{{ trans('public.date') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td class="text-left">
                                <div class="user-inline-avatar d-flex align-items-center">
                                    <div class="avatar bg-gray200">
                                        <img src="{{ $order->buyer->getAvatar() ?? '' }}" class="img-cover" alt="">
                                    </div>
                                    <div class="ml-3">
                                        <span>{{ $order->buyer->full_name ?? '' }}</span>
                                        <span class="d-block font-12 text-gray">{{ $order->buyer->email ?? '' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-left">
                                <span class="d-block font-weight-500 text-dark-blue font-16">{{ $order->id }}</span>
                                <span class="d-block font-12 text-gray">{{ $order->quantity }} {{ trans('update.product') }}</span>
                            </td>
                            <td>{{ handlePrice($order->sale->amount) }}</td>
                            <td>{{ handlePrice($order->sale->discount ?? 0) }}</td>
                            <td>{{ handlePrice($order->sale->total_amount) }}</td>
                            <td>{{ handlePrice($order->sale->getIncomeItem()) }}</td>
                            <td>@if(!empty($order->product)) {{ trans('update.product_type_'.$order->product->type) }} @endif</td>
                            <td>
                                @if($order->status == \App\Models\ProductOrder::$waitingDelivery)
                                    <span class="text-warning">{{ trans('update.product_order_status_waiting_delivery') }}</span>
                                @elseif($order->status == \App\Models\ProductOrder::$success)
                                    <span class="text-dark-blue">{{ trans('update.product_order_status_success') }}</span>
                                @elseif($order->status == \App\Models\ProductOrder::$shipped)
                                    <span class="text-warning">{{ trans('update.product_order_status_shipped') }}</span>
                                @elseif($order->status == \App\Models\ProductOrder::$canceled)
                                    <span class="text-danger">{{ trans('update.product_order_status_canceled') }}</span>
                                @endif
                            </td>
                            <td>{{ dateTimeFormat($order->created_at,'j M Y H:i') }}</td>
                            <td class="text-center">
                                @if($order->status != \App\Models\ProductOrder::$canceled)
                                    <div class="btn-group dropdown table-actions">
                                        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                            <i data-feather="more-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a href="/panel/store/sales/{{ $order->sale_id }}/productOrder/{{ $order->id }}/invoice" target="_blank" class="d-block mt-10">{{ trans('public.invoice') }}</a>
                                            @if($order->status == \App\Models\ProductOrder::$waitingDelivery)
                                                <button type="button" data-sale-id="{{ $order->sale_id }}" data-product-order-id="{{ $order->id }}" class="js-enter-tracking-code d-block mt-10">{{ trans('update.enter_tracking_code') }}</button>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="my-30">
            {{ $orders->appends(request()->input())->links('vendor.pagination.panel') }}
        </div>
    </div>
</section>
@else
@include(getTemplate() . '.includes.no-result',[
    'file_name'=>'sales.png',
    'title'=>trans('update.product_sales_no_result'),
    'hint'=>nl2br(trans('update.product_sales_no_result_hint'))
])
@endif

@endsection

@push('scripts_bottom')
<script>
    var enterTrackingCodeModalTitleLang = '{{ trans('update.enter_tracking_code') }}';
    var trackingCodeLang = '{{ trans('update.tracking_code') }}';
    var addressLang = '{{ trans('update.address') }}';
    var saveLang = '{{ trans('public.save') }}';
    var closeLang = '{{ trans('public.close') }}';
    var trackingCodeSaveSuccessLang = '{{ trans('update.tracking_code_success_save') }}';
</script>
<script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
<script src="/assets/default/vendors/select2/select2.min.js"></script>
<script src="/assets/default/js/panel/store/sale.min.js"></script>
@endpush
