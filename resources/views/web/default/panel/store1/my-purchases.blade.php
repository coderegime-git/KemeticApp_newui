@extends('web.default.layouts.newapp')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">

    <style>
        /* =========================
           KEMETIC THEME VARIABLES
        ========================= */
        :root {
            --k-bg: #0b0b0b;
            --k-card: #141414;
            --k-gold: #d4af37;
            --k-gold-soft: rgba(212,175,55,.25);
            --k-border: rgba(212,175,55,.15);
            --k-text: #f5f5f5;
            --k-muted: #9a9a9a;
            --k-radius: 18px;
            --k-shadow: 0 12px 40px rgba(0,0,0,.65);
        }

        /* =========================
           PAGE
        ========================= */
        .kemetic-page {
            background: radial-gradient(circle at top, #1a1a1a, #000);
            min-height: 100vh;
            padding: 25px;
            color: var(--k-text);
        }

        .section-title {
            color: var(--k-gold);
            font-weight: 700;
            letter-spacing: .6px;
            position: relative;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .section-title::after {
            content: "";
            display: block;
            width: 70px;
            height: 1px;
            margin-top: 6px;
            background: linear-gradient(to right, var(--k-gold), transparent);
        }

        /* =========================
           STATS CARDS
        ========================= */
        .activities-container {
            background: linear-gradient(145deg, #161616, #0c0c0c);
            border-radius: var(--k-radius);
            border: 1px solid var(--k-border);
            box-shadow: var(--k-shadow);
            padding: 20px;
        }

        .activities-container .d-flex {
            padding: 1rem;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: var(--k-radius);
            transition: all 0.3s ease;
        }

        .activities-container .d-flex:hover {
            background: rgba(212,175,55,0.05);
            transform: translateY(-2px);
        }

        .activities-container img {
            filter: brightness(1.2);
            margin-bottom: 0.5rem;
            width: 64px;
            height: 64px;
        }

        .text-dark-blue {
            color: var(--k-gold) !important;
            font-weight: 700;
            font-size: 30px;
        }

        .text-gray {
            color: var(--k-muted) !important;
            font-size: 16px;
        }

        /* =========================
           FORM CARD
        ========================= */
        .panel-section-card {
            background: linear-gradient(180deg, #121212, #0a0a0a);
            border: 1px solid var(--k-border);
            border-radius: var(--k-radius);
            padding: 25px;
            box-shadow: var(--k-shadow);
        }

        /* =========================
           FORM STYLING
        ========================= */
        .form-group label {
            color: var(--k-gold);
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
            letter-spacing: 0.3px;
        }

        .form-control,
        .input-group-text,
        .select2-container--default .select2-selection--single {
            background: #1a1a1a !important;
            color: var(--k-text) !important;
            border: 1px solid var(--k-border) !important;
            border-radius: 12px !important;
            height: 44px;
            transition: all 0.25s ease;
        }

        .form-control:focus,
        .input-group-text:focus {
            border-color: var(--k-gold) !important;
            box-shadow: 0 0 8px var(--k-gold-soft) !important;
            outline: none;
        }

        .form-control::placeholder {
            color: var(--k-muted);
            opacity: 0.7;
        }

        .input-group-text {
            background: #1a1a1a;
            border-right: none;
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }

        .input-group .form-control {
            border-left: none;
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
        }

        .input-group-text i {
            color: var(--k-gold);
        }

        /* Select2 Styling */
        .select2-container--default .select2-selection--single {
            height: 44px !important;
            padding: 8px 15px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--k-text) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: var(--k-gold) transparent transparent transparent !important;
        }

        .select2-dropdown {
            background: #1a1a1a;
            border: 1px solid var(--k-border);
            border-radius: 12px;
            overflow: hidden;
        }

        .select2-results__option {
            color: var(--k-text);
            padding: 10px 15px;
        }

        .select2-results__option:hover,
        .select2-results__option[aria-selected="true"] {
            background: rgba(212,175,55,0.15);
            color: var(--k-gold);
        }

        /* =========================
           BUTTONS
        ========================= */
        .btn-primary {
            background: linear-gradient(135deg, #d4af37, #b8962e) !important;
            color: #000 !important;
            font-weight: 700;
            border-radius: 12px;
            height: 44px;
            border: none;
            transition: all .25s ease;
            padding: 0 20px;
            font-size: 14px;
            letter-spacing: 0.3px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212,175,55,.35);
            color: #000 !important;
        }

        /* =========================
           TABLE
        ========================= */
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0 12px;
        }

        .custom-table thead th {
            background: transparent;
            color: var(--k-gold);
            font-size: 13px;
            font-weight: 600;
            text-align: center;
            border: none;
            padding: 15px;
        }

        .custom-table thead th.text-left {
            text-align: left;
        }

        .custom-table tbody tr {
            background: #0f0f0f;
            border: 1px solid var(--k-border);
            border-radius: 14px;
            transition: all 0.3s ease;
        }

        .custom-table tbody tr:hover {
            background: #151515;
            box-shadow: 0 10px 28px rgba(212, 175, 55, 0.12);
        }

        .custom-table tbody td {
            border: none;
            /* padding: 16px 18px; */
            vertical-align: middle;
            color: var(--k-text);
            text-align: center;
        }

        .custom-table tbody td.text-left {
            text-align: left;
        }

        /* =========================
           USER AVATAR
        ========================= */
        .user-inline-avatar {
            display: flex;
            align-items: center;
        }

        .user-inline-avatar .avatar {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid var(--k-border);
            background: #1a1a1a;
        }

        .user-inline-avatar .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-inline-avatar .ml-3 {
            margin-left: 12px;
        }

        .user-inline-avatar span:first-child {
            color: #fff;
            font-weight: 600;
            display: block;
        }

        .user-inline-avatar .text-gray {
            color: var(--k-muted) !important;
            font-size: 12px;
            display: block;
            margin-top: 2px;
        }

        /* =========================
           ORDER ID CELL
        ========================= */
        .font-weight-500 {
            font-weight: 600;
        }

        .font-16 {
            font-size: 16px;
        }

        .font-12 {
            font-size: 12px;
        }

        .text-dark-blue {
            color: var(--k-gold) !important;
        }

        /* =========================
           STATUS BADGES
        ========================= */
        .text-warning {
            color: #f1c40f !important;
            font-weight: 600;
            background: rgba(241,196,15,0.15);
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
        }

        .text-danger {
            color: #e74c3c !important;
            font-weight: 600;
            background: rgba(231,76,60,0.15);
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
        }

        .text-dark-blue.status-success {
            color: #2ecc71 !important;
            border-radius: 20px;
            display: inline-block;
        }

        /* =========================
           DROPDOWN / ACTIONS
        ========================= */
        .btn-transparent {
            color: var(--k-gold) !important;
            background: none;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .btn-transparent:hover {
            color: #ffd700 !important;
        }

        .table-actions .dropdown-menu {
            background: #0f0f0f;
            border: 1px solid var(--k-border);
            border-radius: 12px;
            padding: 6px;
            min-width: 180px;
        }

        .table-actions .dropdown-item,
        .table-actions .d-block {
            color: var(--k-text);
            font-size: 13px;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.2s ease;
            text-align: left;
            display: block;
            width: 100%;
            border: none;
            background: none;
            cursor: pointer;
        }

        .table-actions .dropdown-item:hover,
        .table-actions .d-block:hover {
            background: rgba(212, 175, 55, 0.12);
            color: var(--k-gold);
            text-decoration: none;
        }

        /* =========================
           NO RESULT
        ========================= */
        .no-result {
            background: #0f0f0f;
            border: 1px dashed var(--k-border);
            border-radius: 18px;
            padding: 60px 20px;
            text-align: center;
            margin-top: 20px;
        }

        .no-result img {
            filter: brightness(0.9) sepia(0.3);
            opacity: 0.9;
            max-width: 120px;
        }

        .no-result .no-result-content h2 {
            color: var(--k-gold);
            font-size: 20px;
            margin: 20px 0 10px;
        }

        .no-result .no-result-content p {
            color: var(--k-muted);
            font-size: 14px;
            max-width: 400px;
            margin: 0 auto;
        }

        /* =========================
           PAGINATION
        ========================= */
        .pagination .page-link {
            background: #111;
            color: var(--k-gold);
            border: 1px solid var(--k-border);
            border-radius: 10px;
            margin: 0 3px;
        }

        .pagination .page-item.active .page-link {
            background: var(--k-gold);
            border-color: var(--k-gold);
            color: #000;
        }

        .pagination .page-item.disabled .page-link {
            background: #1a1a1a;
            color: var(--k-muted);
            border-color: #2a2a2a;
        }

        /* =========================
           RESPONSIVE
        ========================= */
        @media (max-width: 768px) {
            .custom-table thead {
                display: none;
            }

            .custom-table tbody tr {
                display: block;
                margin-bottom: 15px;
            }

            .custom-table tbody td {
                display: block;
                text-align: left;
                padding: 12px;
                position: relative;
            }

            .custom-table tbody td:before {
                content: attr(data-label);
                float: left;
                font-weight: 600;
                color: var(--k-gold);
                margin-right: 10px;
                min-width: 120px;
            }

            .user-inline-avatar {
                justify-content: flex-start;
            }
        }
    </style>
@endpush

@section('content')
    <div class="kemetic-page">
        <section>
            <h2 class="section-title">{{ trans('update.purchases_statistics') }}</h2>

            <div class="activities-container mt-25 p-20 p-lg-35">
                <div class="row text-center">
                    <div class="col-6 col-md-3 mb-4">
                        <div class="d-flex flex-column align-items-center">
                            <img src="/assets/default/img/activity/physical_product3.png" width="64" height="64" alt="">
                            <strong class="text-dark-blue mt-2">{{ $totalOrders }}</strong>
                            <span class="text-gray">{{ trans('update.total_orders') }}</span>
                        </div>
                    </div>

                    <div class="col-6 col-md-3 mb-4">
                        <div class="d-flex flex-column align-items-center">
                            <img src="/assets/default/img/activity/physical_product2.png" width="64" height="64" alt="">
                            <strong class="text-dark-blue mt-2">{{ $pendingOrders }}</strong>
                            <span class="text-gray">{{ trans('update.pending_orders') }}</span>
                        </div>
                    </div>

                    <div class="col-6 col-md-3 mb-4">
                        <div class="d-flex flex-column align-items-center">
                            <img src="/assets/default/img/activity/physical_product1.png" width="64" height="64" alt="">
                            <strong class="text-dark-blue mt-2">{{ $canceledOrders }}</strong>
                            <span class="text-gray">{{ trans('update.canceled_orders') }}</span>
                        </div>
                    </div>

                    <div class="col-6 col-md-3 mb-4">
                        <div class="d-flex flex-column align-items-center">
                            <img src="/assets/default/img/activity/33.png" width="64" height="64" alt="">
                            <strong class="text-dark-blue mt-2">{{ !empty($totalPurchase) ? handlePrice($totalPurchase) : 0 }}</strong>
                            <span class="text-gray">{{ trans('update.total_purchase') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-35">
            <h2 class="section-title">{{ trans('update.purchases_report') }}</h2>

            <div class="panel-section-card mt-20">
                <form action="" method="get">
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('public.from') }}</label>
                                        <div class="input-group">
                                            <!-- <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i data-feather="calendar" width="18" height="18"></i>
                                                </span>
                                            </div> -->
                                            <input type="date" name="from" autocomplete="off"
                                                   class="form-control"
                                                   value="{{ request()->get('from', null) }}" placeholder="YYYY-MM-DD">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('public.to') }}</label>
                                        <div class="input-group">
                                            <!-- <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i data-feather="calendar" width="18" height="18"></i>
                                                </span>
                                            </div> -->
                                            <input type="date" name="to" autocomplete="off"
                                                   class="form-control"
                                                   value="{{ request()->get('to', null) }}" placeholder="YYYY-MM-DD">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="row">
                                <div class="col-12 col-lg-5">
                                    <div class="form-group">
                                        <label>{{ trans('update.seller') }}</label>
                                        <select name="seller_id" class="form-control select2">
                                            <option value="all">{{ trans('public.all') }}</option>
                                            @foreach($sellers as $seller)
                                                <option value="{{ $seller->id }}" @if(request()->get('seller_id') == $seller->id) selected @endif>{{ $seller->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-3">
                                    <div class="form-group">
                                        <label>{{ trans('public.type') }}</label>
                                        <select name="type" class="form-control">
                                            <option value="all" @if(request()->get('type') == 'all') selected @endif>{{ trans('public.all') }}</option>
                                            @foreach(\App\Models\Product::$productTypes as $productType)
                                                <option value="{{ $productType }}" @if(request()->get('type') == $productType) selected @endif>{{ trans('update.product_type_'.$productType) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-3">
                                    <div class="form-group">
                                        <label>{{ trans('public.status') }}</label>
                                        <select name="status" class="form-control">
                                            <option value="all" @if(request()->get('status') == 'all') selected @endif>{{ trans('public.all') }}</option>
                                            @foreach(\App\Models\ProductOrder::$status as $orderStatus)
                                                @if($orderStatus != 'pending')
                                                    <option value="{{ $orderStatus }}" @if(request()->get('status') == $orderStatus) selected @endif>{{ trans('update.product_order_status_'.$orderStatus) }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-2 d-flex align-items-end">
                            <div class="form-group w-100">
                                <label class="d-none d-lg-block">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">{{ trans('public.show_results') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        @if(!empty($orders) && !$orders->isEmpty())
            <section class="mt-35">
                <h2 class="section-title">{{ trans('update.purchases_history') }}</h2>

                <div class="panel-section-card mt-20">
                    <div class="table-responsive">
                        <table class="custom-table text-center">
                            <thead>
                                <tr>
                                    <th class="text-left">{{ trans('update.seller') }}</th>
                                    <th class="text-left">{{ trans('update.order_id') }}</th>
                                    <th>{{ trans('public.price') }}</th>
                                    <th>{{ trans('public.discount') }}</th>
                                    <th>{{ trans('cart.tax') }}</th>
                                    <th>{{ trans('update.delivery_fee') }}</th>
                                    <th>{{ trans('financial.total_amount') }}</th>
                                    <th>{{ trans('public.type') }}</th>
                                    <th>{{ trans('public.status') }}</th>
                                    <th>{{ trans('public.date') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td class="text-left" data-label="{{ trans('update.seller') }}">
                                            <div class="user-inline-avatar">
                                                <div class="avatar">
                                                    <img src="{{ $order->seller->getAvatar() ?? '' }}" class="img-cover" alt="">
                                                </div>
                                                <div class="ml-3">
                                                    <span>{{ $order->seller->full_name ?? '' }}</span>
                                                    <span class="text-gray">{{ $order->seller->email ?? '' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-left" data-label="{{ trans('update.order_id') }}">
                                            <span class="font-16 font-weight-500 text-dark-blue">{{ $order->id }}</span>
                                            <span class="d-block font-12 text-gray">{{ $order->quantity }} {{ trans('update.product') }}</span>
                                        </td>
                                        <td data-label="{{ trans('public.price') }}">{{ handlePrice($order->sale->amount) }}</td>
                                        <td data-label="{{ trans('public.discount') }}">@if(!empty($order->sale->discount)) {{ handlePrice($order->sale->discount) }} @else - @endif</td>
                                        <td data-label="{{ trans('cart.tax') }}">@if(!empty($order->sale->tax)) {{ handlePrice($order->sale->tax) }} @else - @endif</td>
                                        <td data-label="{{ trans('update.delivery_fee') }}">@if(!empty($order->sale->product_delivery_fee)) {{ handlePrice($order->sale->product_delivery_fee) }} @else - @endif</td>
                                        <td data-label="{{ trans('financial.total_amount') }}">{{ handlePrice($order->sale->total_amount) }}</td>
                                        <td data-label="{{ trans('public.type') }}">
                                            @if(!empty($order->product)) 
                                                <span class="text-gray">{{ trans('update.product_type_'.$order->product->type) }}</span>
                                            @endif
                                        </td>
                                        <td data-label="{{ trans('public.status') }}">
                                            @if($order->status == \App\Models\ProductOrder::$waitingDelivery)
                                                <span class="text-warning">{{ trans('update.product_order_status_waiting_delivery') }}</span>
                                            @elseif($order->status == \App\Models\ProductOrder::$success)
                                                <span class="text-dark-blue status-success">{{ trans('update.product_order_status_success') }}</span>
                                            @elseif($order->status == \App\Models\ProductOrder::$shipped)
                                                <span class="text-warning">{{ trans('update.product_order_status_shipped') }}</span>
                                            @elseif($order->status == \App\Models\ProductOrder::$canceled)
                                                <span class="text-danger">{{ trans('update.product_order_status_canceled') }}</span>
                                            @endif
                                        </td>
                                        <td data-label="{{ trans('public.date') }}">{{ dateTimeFormat($order->created_at, 'j M Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group dropdown table-actions">
                                                <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                    <i data-feather="more-vertical" height="20"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    @if($order->product && $order->product->isVirtual())
                                                        <a href="/panel/store/products/{{ $order->product->id }}/getFilesModal" class="dropdown-item">{{ trans('home.download') }}</a>
                                                    @endif
                                                    <a href="/panel/store/purchases/{{ $order->sale_id }}/productOrder/{{ $order->id }}/invoice" target="_blank" class="dropdown-item">{{ trans('public.invoice') }}</a>
                                                    @if($order->product && $order->status == \App\Models\ProductOrder::$success)
                                                        <a href="{{ $order->product->getUrl() }}" target="_blank" class="dropdown-item">{{ trans('public.feedback') }}</a>
                                                    @endif
                                                    @if($order->status == \App\Models\ProductOrder::$shipped)
                                                        <button type="button" data-sale-id="{{ $order->sale_id }}" data-product-order-id="{{ $order->id }}" class="js-view-tracking-code dropdown-item">{{ trans('update.view_tracking_code') }}</button>
                                                        <button type="button" data-sale-id="{{ $order->sale_id }}" data-product-order-id="{{ $order->id }}" class="js-got-the-parcel dropdown-item">{{ trans('update.i_got_the_parcel') }}</button>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($orders->hasPages())
                        <div class="my-30" style="padding:10px;">
                            {{ $orders->appends(request()->input())->links('vendor.pagination.panel') }}
                        </div>
                    @endif
                </div>
            </section>
        @else
            <div class="no-result">
                <div class="no-result-content">
                    <img src="/assets/default/img/no-results/sales.png" alt="{{ trans('update.product_purchases_no_result') }}">
                    <h2>{{ trans('update.product_purchases_no_result') }}</h2>
                    <p>{{ trans('update.product_purchases_no_result_hint') }}</p>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts_bottom')
    <script>

        // When clicking the download button/link
    $('a[href*="getFilesModal"]').on('click', function(e) {
        e.preventDefault();
        
        var downloadUrl = $(this).attr('href');
        // alert(downloadUrl);
        $.ajax({
            url: downloadUrl,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // alert(JSON.stringify(response)); // This will show the response properly
                if (response.code === 200) {
                    // Encode the URL to handle spaces and special characters
                    var encodedUrl = encodeURI(response.url);
                    window.open(encodedUrl, '_blank');
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                // Handle AJAX error
                var response = xhr.responseJSON;
                alert('Error: ' + (response ? response.message : 'Something went wrong'));
            }
        });
    });
        var viewTrackingCodeModalTitleLang = '{{ trans('update.view_tracking_code') }}';
        var trackingCodeLang = '{{ trans('update.tracking_code') }}';
        var closeLang = '{{ trans('public.close') }}';
        var confirmLang = '{{ trans('update.confirm') }}';
        var gotTheParcelLang = '{{ trans('update.i_got_the_parcel') }}';
        var gotTheParcelConfirmTextLang = '{{ trans('update.i_got_the_parcel_confirm') }}';
        var gotTheParcelSaveSuccessLang = '{{ trans('update.i_got_the_parcel_success_save') }}';
        var gotTheParcelSaveErrorLang = '{{ trans('update.i_got_the_parcel_error_save') }}';
        var shippingTrackingUrlLang = '{{ trans('update.track_shipping') }}';
        var addressLang = '{{ trans('update.address') }}';
        var filesLang = '{{ trans('public.files') }}';
        var onlineViewerModalTitleLang = '{{ trans('update.online_viewer') }}';
    </script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/default/js/panel/store/my-purchase.min.js"></script>
    <script src="/assets/default/js/parts/product_show.min.js"></script>
@endpush