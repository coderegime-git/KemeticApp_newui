<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ $pageTitle ?? 'Kemetic Invoice' }} </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- General CSS File -->
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap/bootstrap.min.css"/>
    <link rel="stylesheet" href="/assets/vendors/fontawesome/css/all.min.css"/>

    <style>
        :root {
            --k-bg: #0b0b0b;
            --k-card: #141414;
            --k-gold: #f2c94c;
            --k-gold-soft: #e6b93d;
            --k-border: rgba(242, 201, 76, 0.25);
            --k-text: #eaeaea;
            --k-muted: #9a9a9a;
            --k-radius: 16px;
        }

        body {
            background: var(--k-bg);
            color: var(--k-text);
            font-family: 'Nunito', sans-serif;
        }

        .card-primary {
            background: var(--k-card);
            border-radius: var(--k-radius);
            border: 1px solid var(--k-border);
            box-shadow: 0 4px 30px rgba(242,201,76,0.1);
        }

        .invoice-title h2 {
            color: var(--k-gold);
            font-weight: 700;
        }

        .invoice-number {
            color: var(--k-muted);
            margin-top: 5px;
        }

        address {
            color: var(--k-text);
            font-size: 0.95rem;
        }

        .section-title {
            font-size: 1.2rem;
            color: var(--k-gold);
            font-weight: bold;
            margin-bottom: 15px;
        }

        .table {
            background: var(--k-card);
            color: var(--k-text);
        }

        .table th, .table td {
            vertical-align: middle;
            border-top: 1px solid var(--k-border);
        }

        .invoice-detail-item {
            margin-bottom: 12px;
        }

        .invoice-detail-name {
            color: var(--k-muted);
            font-weight: 600;
        }

        .invoice-detail-value {
            color: var(--k-text);
            font-weight: 500;
        }

        .invoice-detail-value-lg {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--k-gold);
        }

        hr {
            border-top: 1px solid var(--k-border);
        }

        .btn-warning {
            background-color: var(--k-gold);
            border: none;
            color: #000;
            border-radius: 12px;
        }

        .btn-warning:hover {
            background-color: var(--k-gold-soft);
            color: #000;
        }

        .btn-icon i {
            margin-right: 5px;
        }

        .ml-3 {
            margin-left: 1rem;
        }
    </style>
</head>
<body>
<div id="app">
    <section class="section">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-10">

                    <div class="card card-primary p-4">
                        <div class="invoice">
                            <div class="invoice-print">

                                <div class="invoice-title mb-4">
                                    <h2>{{ $generalSettings['site_name'] ?? 'Kemetic App' }}</h2>
                                    <div class="invoice-number">{{ trans('public.item_id') }}: #{{ $order->product_id }}</div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <address>
                                            <strong>{{ trans('admin/main.buyer') }}:</strong><br>
                                            {{ $buyer->full_name }}
                                        </address>
                                        <address class="mt-2">
                                            <strong>{{ trans('update.buyer_address') }}:</strong><br>
                                            {{ $buyer->getAddressInvoice(true) }}
                                        </address>
                                    </div>

                                    <div class="col-md-6 text-md-right">
                                        <address>
                                            <strong>{{ trans('home.platform_address') }}:</strong><br>
                                            {!! nl2br(getContactPageSettings('address')) !!}
                                        </address>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <address>
                                            <strong>{{ trans('admin/main.seller') }}:</strong><br>
                                            {{ $seller->full_name }}
                                        </address>
                                    </div>

                                    <div class="col-md-6 text-md-right">
                                        <address>
                                            <strong>{{ trans('panel.purchase_date') }}:</strong><br>
                                            {{ dateTimeFormat($sale->created_at,'Y M j | H:i') }}
                                        </address>
                                    </div>
                                </div>

                                <div class="section-title">{{ trans('home.order_summary') }}</div>

                                <div class="table-responsive mb-4">
                                    <table class="table table-striped table-hover table-md text-center">
                                        <thead>
                                            <tr>
                                                <th>{{ trans('admin/main.item') }}</th>
                                                <th>{{ trans('update.quantity') }}</th>
                                                <th>{{ trans('public.price') }}</th>
                                                <th>{{ trans('panel.discount') }}</th>
                                                <th>{{ trans('update.delivery_fee') }}</th>
                                                <th>{{ trans('cart.total') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    {{ $product->title ?? trans('update.delete_item') }}
                                                    @if(!empty($order->specifications))
                                                        <div>
                                                            @foreach(json_decode($order->specifications,true) as $specKey => $specValue)
                                                                <span>{{ str_replace('_',' ',$specValue) }}{{ !$loop->last ? ', ' : '' }}</span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>{{ $order->quantity }} {{ trans('cart.item') }}</td>
                                                <td>{{ handlePrice($sale->amount) ?? trans('public.free') }}</td>
                                                <td>{{ handlePrice($sale->discount) ?? '-' }}</td>
                                                <td>{{ handlePrice($sale->product_delivery_fee) ?? '-' }}</td>
                                                <td>{{ handlePrice($sale->total_amount) ?? '-' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-lg-6 text-left">
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">{{ trans('admin/main.item') }}</div>
                                            <div class="invoice-detail-value">{{ $product->title ?? trans('update.delete_item') }}</div>
                                        </div>

                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">{{ trans('update.quantity') }}</div>
                                            <div class="invoice-detail-value">{{ $order->quantity }} {{ trans('cart.item') }}</div>
                                        </div>

                                        @if(!empty($order->specifications))
                                            <div class="invoice-detail-item">
                                                <div class="invoice-detail-name">{{ trans('update.specifications') }}</div>
                                                @foreach(json_decode($order->specifications,true) as $specKey => $specValue)
                                                    <div class="invoice-detail-value">
                                                        <span>{{ $specKey }}:</span>
                                                        <span class="ml-3">{{ str_replace('_',' ',$specValue) }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if(!empty($order->message_to_seller))
                                            <div class="invoice-detail-item">
                                                <div class="invoice-detail-name">{{ trans('update.message_to_seller') }}</div>
                                                <div class="invoice-detail-value">{!! $order->message_to_seller !!}</div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-lg-6 text-right">
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">{{ trans('cart.sub_total') }}</div>
                                            <div class="invoice-detail-value">{{ handlePrice($sale->amount) }}</div>
                                        </div>
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">{{ trans('cart.tax') }} @if(!empty($product)) ({{ $product->getTax() }}%) @endif</div>
                                            <div class="invoice-detail-value">{{ handlePrice($sale->tax) ?? '-' }}</div>
                                        </div>
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">{{ trans('public.discount') }}</div>
                                            <div class="invoice-detail-value">{{ handlePrice($sale->discount) ?? '-' }}</div>
                                        </div>
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">{{ trans('update.delivery_fee') }}</div>
                                            <div class="invoice-detail-value">{{ handlePrice($sale->product_delivery_fee) ?? '-' }}</div>
                                        </div>
                                        <hr class="mt-2 mb-2">
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">{{ trans('cart.total') }}</div>
                                            <div class="invoice-detail-value invoice-detail-value-lg">{{ handlePrice($sale->total_amount) ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="text-md-right">
                                    <button type="button" onclick="window.print()" class="btn btn-warning btn-icon icon-left"><i class="fas fa-print"></i> Print</button>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>
</body>
</html>
