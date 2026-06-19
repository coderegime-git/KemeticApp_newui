<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ $pageTitle ?? '' }} </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- General CSS File -->
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap/bootstrap.min.css"/>
    <link rel="stylesheet" href="/assets/vendors/fontawesome/css/all.min.css"/>


    <link rel="stylesheet" href="/assets/admin/css/style.css">
    <link rel="stylesheet" href="/assets/admin/css/custom.css">
    <link rel="stylesheet" href="/assets/admin/css/components.css">

    <style>
        {!! !empty(getCustomCssAndJs('css')) ? getCustomCssAndJs('css') : '' !!}
    </style>
</head>
<style>
    /* ======================================================
   KEMETIC INVOICE THEME
   Black • Gold • Print Safe
====================================================== */

:root {
    --k-bg: #0b0b0b;
    --k-card: #141414;
    --k-border: rgba(242,201,76,.25);
    --k-gold: #F2C94C;
    --k-gold-soft: rgba(242,201,76,.15);
    --k-text: #eaeaea;
    --k-muted: #9a9a9a;
    --k-radius: 16px;
}

/* BODY */
body {
    background: radial-gradient(circle at top, #121212, #070707);
    color: var(--k-text);
}

/* CARD */
.card-primary {
    background: linear-gradient(180deg, #161616, #0e0e0e);
    border-radius: var(--k-radius);
    border: 1px solid var(--k-border);
    box-shadow: 0 20px 60px rgba(0,0,0,.7);
}

/* INVOICE TITLE */
.invoice-title h2 {
    color: var(--k-gold);
    font-weight: 800;
    letter-spacing: .5px;
}

.invoice-number {
    color: var(--k-muted);
    font-size: 14px;
}

/* SEPARATORS */
hr {
    border-color: var(--k-border);
}

/* ADDRESS */
address strong {
    color: var(--k-gold);
    font-weight: 600;
}

address {
    color: var(--k-text);
    line-height: 1.7;
}

/* SECTION TITLE */
.section-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--k-gold);
    border-left: 4px solid var(--k-gold);
    padding-left: 10px;
    margin-bottom: 15px;
}

/* TABLE */
.table {
    background: transparent;
}

.table thead th,
.table th {
    color: var(--k-gold);
    border-bottom: 1px solid var(--k-border);
    font-weight: 700;
}

.table td {
    color: var(--k-text);
    border-top: 1px solid rgba(255,255,255,.05);
}

.table-striped tbody tr:nth-of-type(odd) {
    background: rgba(255,255,255,.03);
}

.table-hover tbody tr:hover {
    background: rgba(242,201,76,.08);
}

/* TOTAL SUMMARY */
.invoice-detail-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
}

.invoice-detail-name {
    color: var(--k-muted);
}

.invoice-detail-value {
    color: var(--k-text);
    font-weight: 600;
}

.invoice-detail-value-lg {
    font-size: 20px;
    font-weight: 800;
    color: var(--k-gold);
}

/* PRINT BUTTON */
.btn-warning {
    background: linear-gradient(135deg, #F2C94C, #E5A100);
    border: none;
    color: #000;
    font-weight: 700;
    border-radius: 30px;
    padding: 10px 20px;
    box-shadow: 0 10px 25px rgba(242,201,76,.4);
}

.btn-warning:hover {
    background: linear-gradient(135deg, #E5A100, #F2C94C);
}

/* ======================================================
   PRINT MODE
====================================================== */

@media print {
    body {
        background: #fff !important;
        color: #000 !important;
    }

    .card-primary {
        box-shadow: none;
        border: 1px solid #ccc;
    }

    .invoice-detail-value-lg,
    .invoice-title h2 {
        color: #000 !important;
    }

    .btn {
        display: none !important;
    }
}

</style>
<body>

<div id="app">
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-md-10 offset-md-1 col-lg-10 offset-lg-1">

                    <div class="card card-primary">
                        <div class="row m-0">
                            <div class="col-12 col-md-12">
                                <div class="card-body">

                                    <div class="section-body">
                                        <div class="invoice">
                                            <div class="invoice-print">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="invoice-title">
                                                            <h2>{{ $generalSettings['site_name'] }}</h2>
                                                            <div class="invoice-number">{{ trans('public.item_id') }}: #{{ $webinar->id }}</div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <address>
                                                                    <strong>{{ trans('quiz.student') }}:</strong>
                                                                    <br>
                                                                    {{ !empty($sale->gift_recipient) ? $sale->gift_recipient : $sale->buyer->full_name }}
                                                                    <br>
                                                                </address>

                                                                <address>
                                                                    <strong>{{ trans('home.organization') }}:</strong><br>
                                                                    @if($webinar->tracher_id != $webinar->creator_id)
                                                                        {{ $webinar->creator->full_name }}
                                                                    @else
                                                                        -
                                                                    @endif
                                                                    <br>
                                                                </address>
                                                            </div>
                                                            <div class="col-md-6 text-md-right">
                                                                <address>
                                                                    <strong>{{ trans('home.platform_address') }}:</strong><br>
                                                                    {!! nl2br(getContactPageSettings('address')) !!}
                                                                </address>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <address>
                                                                    <strong>{{ trans('home.teachers') }}:</strong><br>
                                                                    {{ $webinar->teacher->full_name }} <br>

                                                                    @if(!empty($webinar->webinarPartnerTeacher) and count($webinar->webinarPartnerTeacher))
                                                                        @foreach($webinar->webinarPartnerTeacher as $partner)
                                                                            {{ $partner->teacher->full_name }}
                                                                        @endforeach
                                                                    @endif
                                                                </address>
                                                            </div>

                                                            <div class="col-md-6 text-md-right">
                                                                <address>
                                                                    <strong>{{ trans('panel.purchase_date') }}:</strong><br>
                                                                    {{ dateTimeFormat($sale->created_at,'j M Y | H:i') }}<br><br>
                                                                </address>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-4">
                                                    <div class="col-md-12">
                                                        <div class="section-title">{{ trans('home.order_summary') }}</div>
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-hover table-md">
                                                                <tr>
                                                                    <th data-width="40">#</th>
                                                                    <th>{{ trans('cart.item') }}</th>
                                                                    <th class="text-center">{{ trans('admin/main.type') }}</th>
                                                                    <th class="text-center">{{ trans('public.price') }}</th>
                                                                    <th class="text-center">{{ trans('panel.discount') }}</th>
                                                                    <th class="text-right">{{ trans('cart.total') }}</th>
                                                                </tr>

                                                                <tr>
                                                                    <td>{{ $webinar->id }}</td>
                                                                    <td>{{ $webinar->title }}</td>
                                                                    <td class="text-center">{{ trans('webinars.'.$webinar->type) }}</td>
                                                                    <td class="text-center">
                                                                        @if(!empty($sale->amount))
                                                                            {{ handlePrice($sale->amount) }}
                                                                        @else
                                                                            {{ trans('public.free') }}
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if(!empty($sale->discount))
                                                                            {{ handlePrice($sale->discount) }}
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-right">
                                                                        @if(!empty($sale->total_amount))
                                                                            {{ handlePrice($sale->total_amount) }}
                                                                        @else
                                                                            0
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="row mt-4">

                                                            <div class="col-lg-12 text-right">
                                                                <div class="invoice-detail-item">
                                                                    <div class="invoice-detail-name">{{ trans('cart.sub_total') }}</div>
                                                                    <div class="invoice-detail-value">{{ handlePrice($sale->amount) }}</div>
                                                                </div>
                                                                <div class="invoice-detail-item">
                                                                    <div class="invoice-detail-name">{{ trans('cart.tax') }} ({{ getFinancialSettings('tax') }}%)</div>
                                                                    <div class="invoice-detail-value">
                                                                        @if(!empty($sale->tax))
                                                                            {{ handlePrice($sale->tax) }}
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="invoice-detail-item">
                                                                    <div class="invoice-detail-name">{{ trans('public.discount') }}</div>
                                                                    <div class="invoice-detail-value">
                                                                        @if(!empty($sale->discount))
                                                                            {{ handlePrice($sale->discount) }}
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <hr class="mt-2 mb-2">
                                                                <div class="invoice-detail-item">
                                                                    <div class="invoice-detail-name">{{ trans('cart.total') }}</div>
                                                                    <div class="invoice-detail-value invoice-detail-value-lg">
                                                                        @if(!empty($sale->total_amount))
                                                                            {{ handlePrice($sale->total_amount) }}
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
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

                </div>
            </div>
        </div>
    </section>
</div>
</body>
