<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ $pageTitle ?? 'Kemetic Membership Invoice' }} </title>
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

        .membership-badge {
            background: linear-gradient(135deg, rgba(242, 201, 76, 0.2), rgba(242, 201, 76, 0.05));
            border: 1px solid var(--k-gold);
            border-radius: 50px;
            padding: 8px 20px;
            display: inline-block;
            color: var(--k-gold);
            font-weight: 600;
        }

        .status-badge {
            border-radius: 50px;
            padding: 5px 15px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .status-active {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: #fff;
        }

        .status-expired {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: #fff;
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
            border-bottom: 2px solid var(--k-border);
            padding-bottom: 10px;
        }

        .table {
            background: var(--k-card);
            color: var(--k-text);
        }

        .table th {
            border-top: 1px solid var(--k-border);
            color: var(--k-gold);
            font-weight: 600;
        }

        .table td {
            vertical-align: middle;
            border-top: 1px solid var(--k-border);
        }

        .invoice-detail-item {
            margin-bottom: 15px;
            padding: 10px;
            background: rgba(255,255,255,0.02);
            border-radius: 12px;
        }

        .invoice-detail-name {
            color: var(--k-muted);
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .invoice-detail-value {
            color: var(--k-text);
            font-weight: 500;
            font-size: 1.1rem;
        }

        .invoice-detail-value-lg {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--k-gold);
        }

        .membership-icon {
            width: 60px;
            height: 60px;
            background: rgba(242, 201, 76, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .membership-icon i {
            font-size: 30px;
            color: var(--k-gold);
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .feature-list li {
            padding: 8px 0;
            border-bottom: 1px solid var(--k-border);
        }

        .feature-list li:last-child {
            border-bottom: none;
        }

        .feature-list i {
            color: var(--k-gold);
            margin-right: 10px;
            font-size: 14px;
        }

        hr {
            border-top: 1px solid var(--k-border);
        }

        .btn-warning {
            background-color: var(--k-gold);
            border: none;
            color: #000;
            border-radius: 12px;
            padding: 12px 30px;
            font-weight: 600;
        }

        .btn-warning:hover {
            background-color: var(--k-gold-soft);
            color: #000;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(242, 201, 76, 0.3);
        }

        .btn-icon i {
            margin-right: 5px;
        }

        .ml-3 {
            margin-left: 1rem;
        }
        
        .period-dates {
            background: rgba(242, 201, 76, 0.05);
            border-radius: 12px;
            padding: 15px;
            margin-top: 20px;
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

                                <div class="invoice-title mb-4 d-flex justify-content-between align-items-center">
                                    <div>
                                        <h2>{{ $generalSettings['site_name'] ?? 'Kemetic App' }}</h2>
                                        <div class="invoice-number">Invoice Id: #{{ $order->id ?? 'N/A' }}</div>
                                    </div>
                                    <div>
                                        <span class="membership-badge">
                                            <i class="fas fa-crown mr-2"></i>Membership Invoice
                                        </span>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <address>
                                            <strong>{{ trans('admin/main.buyer') }}:</strong><br>
                                            {{ $buyer->full_name }}<br>
                                            {{ $buyer->email ?? '' }}<br>
                                            {{ $buyer->mobile ?? '' }}
                                        </address>
                                        <address class="mt-2">
                                            <strong>{{ trans('update.buyer_address') }}:</strong><br>
                                            {{ $buyer->getAddressInvoice(true) ?? trans('update.not_provided') }}
                                        </address>
                                    </div>

                                    <div class="col-md-6 text-md-right">
                                        <address>
                                            <strong>{{ trans('home.platform_address') }}:</strong><br>
                                            {!! nl2br(getContactPageSettings('address') ?? 'Kemetic App Headquarters<br>123 Business Avenue<br>Digital City, DC 12345') !!}
                                        </address>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <!-- <div class="membership-icon mr-3">
                                                <i class="fas {{ $subscribe->icon ?? 'fa-crown' }}"></i>
                                            </div> -->
                                            <div>
                                                <h4 class="text-white mb-1">{{ $subscribe->title ?? Membership }}</h4>
                                                <div class="status-badge {{ $isActive ? 'status-active' : 'status-expired' }}">
                                                    <i class="fas {{ $isActive ? 'fa-check-circle' : 'fa-exclamation-circle' }} mr-1"></i>
                                                    {{ $isActive ? 'Active' : 'Expired' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 text-md-right">
                                        <address>
                                            <strong>{{ trans('panel.purchase_date') }}:</strong><br>
                                            {{ dateTimeFormat($sale->created_at ?? time(),'Y M j | H:i') }}
                                        </address>
                                        @if($isActive && $membershipFeatures['usable_time'])
                                            <address class="mt-2">
                                                <strong>Expired On:</strong><br>
                                                {{ dateTimeFormat(($orderItem->created_at + $membershipFeatures['usable_time']),'Y M j | H:i') }}
                                            </address>
                                        @endif
                                    </div>
                                </div>

                                <div class="section-title">Membership Details</div>

                                <div class="table-responsive mb-4">
                                    <table class="table table-striped table-hover table-md text-center">
                                        <thead>
                                            <tr>
                                                <th>{{ trans('admin/main.item') }}</th>
                                                <th>Plan Type</th>
                                                <th>Duration</th>
                                                <th>{{ trans('public.price') }}</th>
                                                <th>{{ trans('cart.tax') }}</th>
                                                <th>{{ trans('cart.total') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <strong>{{ $subscribe->title ?? trans('update.membership_plan') }}</strong>
                                                    @if(!empty($subscribe->description))
                                                        <div><small class="text-muted">{{ $subscribe->description }}</small></div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $membershipType = '';
                                                        $planType = '';
                                                        $badgeText = '';
                                                        if ($subscribe->days == 31) {
                                                            $membershipType = 'Monthly Membership';
                                                            $planType = 'monthly';
                                                            $planColor = '#34C759';
                                                        } elseif ($subscribe->days == 365) {
                                                            $membershipType = 'Yearly Membership';
                                                            $planType = 'yearly';
                                                            $planColor = '#007AFF';
                                                            $badgeText = 'Most Popular';
                                                        } elseif ($subscribe->days == 100000) {
                                                            $membershipType = 'Lifetime access to the full platform';
                                                            $planType = 'lifetime';
                                                            $planColor = '#AF52DE';
                                                        } else {
                                                            $membershipType = $subscribe->days . ' days';
                                                            $planType = 'custom';
                                                            $planColor = '#5856D6';
                                                        }
                                                        
                                                    @endphp

                                                    {{ $membershipType }}
                                                </td>
                                                <td>{{ $subscribe->days ?? 0 }} Days</td>
                                                <td>{{ handlePrice($sale->amount ?? $subscribe->price) }}</td>
                                                <td>{{ handlePrice($sale->tax ?? 0) }}</td>
                                                <td>{{ handlePrice($sale->total_amount ?? $subscribe->price) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-lg-7">
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">Membership Featurs</div>
                                            <div class="mt-3">
                                                @if(!empty($subscribe->features) && is_array($subscribe->features))
                                                    <ul class="feature-list">
                                                        @foreach($subscribe->features as $feature)
                                                            <li>
                                                                <i class="fas fa-check-circle"></i>
                                                                {{ $feature }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <ul class="feature-list">
                                                        <li><i class="fas fa-check-circle"></i> Access to all Courses</li>
                                                        <li><i class="fas fa-check-circle"></i> Unlimited Downloads</li>
                                                        <li><i class="fas fa-check-circle"></i> Priority Support</li>
                                                        <li><i class="fas fa-check-circle"></i> Exclusive content</li>
                                                    </ul>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-5">
                                        <!-- <div class="period-dates">
                                            <h5 class="text-gold mb-3"><i class="fas fa-calendar-alt mr-2"></i>Membership Period</h5>
                                            
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">Start Date:</span>
                                                <span class="font-weight-bold">{{ dateTimeFormat($orderItem->created_at,'Y M d') }}</span>
                                            </div>
                                            
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">End Date:</span>
                                                @if($isActive && $membershipFeatures['usable_time'])
                                                    <span class="font-weight-bold">{{ dateTimeFormat(($orderItem->created_at + $membershipFeatures['usable_time']),'Y M d') }}</span>
                                                @else
                                                    <span class="text-danger">Expired</span>
                                                @endif
                                            </div>
                                            
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">Remaining Days:</span>
                                                @if($isActive && $membershipFeatures['usable_time'])
                                                    @php
                                                        $remaining = ($orderItem->created_at + $membershipFeatures['usable_time'] - time()) / 86400;
                                                        $remainingDays = max(0, floor($remaining));
                                                    @endphp
                                                    <span class="font-weight-bold {{ $remainingDays < 7 ? 'text-warning' : 'text-success' }}">
                                                        {{ $remainingDays }} Days
                                                    </span>
                                                @else
                                                    <span class="text-danger">0 Days</span>
                                                @endif
                                            </div>
                                        </div> -->

                                        <div class="invoice-detail-item mt-4">
                                            <div class="invoice-detail-name">{{ trans('cart.sub_total') }}</div>
                                            <div class="invoice-detail-value">{{ handlePrice($sale->amount ?? $subscribe->price) }}</div>
                                        </div>
                                        
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">{{ trans('cart.tax') }}</div>
                                            <div class="invoice-detail-value">{{ handlePrice($sale->tax ?? 0) }}</div>
                                        </div>
                                        
                                        <!-- <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">{{ trans('public.discount') }}</div>
                                            <div class="invoice-detail-value">{{ handlePrice($sale->discount ?? 0) ?: '-' }}</div>
                                        </div> -->
                                        
                                        <hr class="mt-2 mb-2">
                                        
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">{{ trans('cart.total') }}</div>
                                            <div class="invoice-detail-value invoice-detail-value-lg">
                                                {{ handlePrice($sale->total_amount ?? $subscribe->price) }}
                                            </div>
                                        </div>

                                        <div class="invoice-detail-item mt-3">
                                            <div class="invoice-detail-name">Payment Method</div>
                                            <div class="invoice-detail-value">
                                                @if($sale->payment_method == 'credit')
                                                    <span class="badge badge-info">Credit</span>
                                                @elseif($sale->payment_method == 'offline')
                                                    <span class="badge badge-warning">Offline</span>
                                                @else
                                                    <span class="badge badge-success">Online</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        @if($isActive)
                                            <div class="alert alert-success bg-success text-white border-0">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                Your Membership is active until {{ dateTimeFormat(($orderItem->created_at + $membershipFeatures['usable_time']),'Y M d') }}
                                            </div>
                                        @else
                                            <div class="alert alert-danger bg-danger text-white border-0">
                                                <i class="fas fa-exclamation-circle mr-2"></i>
                                                Your Membership has Expired
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6 text-md-right">
                                        <button type="button" onclick="window.print()" class="btn btn-warning btn-icon icon-left">
                                            <i class="fas fa-print"></i> Print
                                        </button>
                                        @if(!$isActive)
                                            <a href="/panel/membership" class="btn btn-primary ml-2">
                                                <i class="fas fa-sync-alt mr-1"></i> Renew Membership
                                            </a>
                                        @endif
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
</html>