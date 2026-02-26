<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ $pageTitle ?? 'Kemetic Membership Invoice' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--k-bg);
            color: var(--k-text);
            font-family: 'Nunito', sans-serif;
            font-size: 13px;
        }

        .card-primary {
            background: var(--k-card);
            border-radius: var(--k-radius);
            border: 1px solid var(--k-border);
            box-shadow: 0 4px 30px rgba(242,201,76,0.1);
            padding: 32px 36px;
            max-width: 820px;
            margin: 36px auto;
        }

        /* Header */
        .inv-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            padding-bottom: 18px;
            border-bottom: 1px solid var(--k-border);
        }

        .inv-header h2 {
            font-size: 22px;
            font-weight: 700;
            color: var(--k-gold);
        }

        .invoice-number {
            color: var(--k-muted);
            font-size: 12px;
            margin-top: 4px;
        }

        .membership-badge {
            background: rgba(242, 201, 76, 0.12);
            border: 1px solid var(--k-gold);
            border-radius: 50px;
            padding: 5px 16px;
            color: var(--k-gold);
            font-size: 11.5px;
            font-weight: 600;
        }

        /* Info rows */
        .info-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-block { flex: 1; }

        .info-block strong {
            display: block;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--k-gold);
            margin-bottom: 5px;
            font-weight: 600;
        }

        .info-block address,
        .info-block p {
            font-size: 12.5px;
            color: var(--k-text);
            line-height: 1.65;
            font-style: normal;
        }

        /* Status row */
        .status-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--k-border);
            border-radius: 10px;
            padding: 10px 16px;
            margin-bottom: 20px;
        }

        .plan-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--k-text);
        }

        .status-pill {
            border-radius: 50px;
            padding: 3px 12px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 10px;
        }

        .status-active { background: rgba(40,167,69,0.2); color: #5cb85c; border: 1px solid rgba(40,167,69,0.4); }
        .status-expired { background: rgba(220,53,69,0.2); color: #e05c6a; border: 1px solid rgba(220,53,69,0.4); }

        /* Section title */
        .section-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--k-gold);
            font-weight: 600;
            margin-bottom: 10px;
        }

        /* Table */
        .inv-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
        }

        .inv-table thead tr {
            background: rgba(242, 201, 76, 0.05);
        }

        .inv-table th {
            padding: 9px 12px;
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--k-gold);
            font-weight: 600;
            border-top: 1px solid var(--k-border);
            border-bottom: 1px solid var(--k-border);
            text-align: left;
        }

        .inv-table th:not(:first-child),
        .inv-table td:not(:first-child) { text-align: center; }

        .inv-table td {
            padding: 10px 12px;
            font-size: 12.5px;
            border-bottom: 1px solid var(--k-border);
            color: var(--k-text);
            vertical-align: middle;
        }

        .inv-table td .item-desc {
            font-size: 11px;
            color: var(--k-muted);
            margin-top: 2px;
        }

        /* Bottom */
        .bottom-row {
            display: flex;
            gap: 24px;
        }

        .features-col { flex: 1.2; }
        .totals-col { flex: 1; }

        .feature-list {
            list-style: none;
            padding: 0;
        }

        .feature-list li {
            font-size: 12px;
            color: var(--k-text);
            padding: 6px 0;
            border-bottom: 1px solid var(--k-border);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .feature-list li:last-child { border-bottom: none; }

        .feature-list i {
            color: var(--k-gold);
            font-size: 11px;
            flex-shrink: 0;
        }

        /* Totals */
        .invoice-detail-item {
            margin-bottom: 10px;
        }

        .invoice-detail-name {
            color: var(--k-muted);
            font-weight: 600;
            font-size: 11px;
            margin-bottom: 2px;
        }

        .invoice-detail-value {
            color: var(--k-text);
            font-weight: 500;
            font-size: 13px;
        }

        .invoice-detail-value-lg {
            font-size: 18px;
            font-weight: 700;
            color: var(--k-gold);
        }

        .payment-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-online { background: rgba(40,167,69,0.15); color: #5cb85c; border: 1px solid rgba(40,167,69,0.3); }
        .badge-credit { background: rgba(0,123,255,0.15); color: #5ba4f5; border: 1px solid rgba(0,123,255,0.3); }
        .badge-offline { background: rgba(255,193,7,0.15); color: #f2c94c; border: 1px solid rgba(255,193,7,0.3); }

        hr {
            border: none;
            border-top: 1px solid var(--k-border);
            margin: 10px 0;
        }

        /* Footer */
        .inv-footer {
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid var(--k-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .alert-strip {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
        }

        .alert-success-strip { background: rgba(40,167,69,0.15); color: #5cb85c; border: 1px solid rgba(40,167,69,0.3); }
        .alert-danger-strip { background: rgba(220,53,69,0.15); color: #e05c6a; border: 1px solid rgba(220,53,69,0.3); }

        .btn-print {
            background: var(--k-gold);
            color: #000;
            border: none;
            border-radius: 10px;
            padding: 9px 22px;
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .btn-print:hover { background: var(--k-gold-soft); }

        .btn-renew {
            background: transparent;
            color: var(--k-gold);
            border: 1px solid var(--k-border);
            border-radius: 10px;
            padding: 9px 18px;
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            margin-left: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-renew:hover { background: rgba(242,201,76,0.08); color: var(--k-gold); }

        /* Print Styles */
        @media print {
            body {
                background: #0b0b0b !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .card-primary {
                margin: 0 !important;
                max-width: 100% !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                padding: 20px 24px !important;
            }

            .no-print { display: none !important; }

            @page {
                size: A4;
                margin: 8mm 10mm;
            }
        }
    </style>
</head>
<body>
<div class="card-primary">

    {{-- HEADER --}}
    <div class="inv-header">
        <div>
            <h2>{{ $generalSettings['site_name'] ?? 'Kemetic App' }}</h2>
            <div class="invoice-number">Invoice ID: #{{ $order->id ?? 'N/A' }}</div>
        </div>
        <span class="membership-badge">
            <i class="fas fa-crown" style="margin-right:6px;"></i>Membership Invoice
        </span>
    </div>

    {{-- INFO ROW --}}
    <div class="info-row">
        <div class="info-block">
            <strong>{{ trans('admin/main.buyer') }}</strong>
            <address>
                {{ $buyer->full_name }}<br>
                {{ $buyer->email ?? '' }}<br>
                {{ $buyer->mobile ?? '' }}
            </address>
        </div>

        <div class="info-block">
            <strong>{{ trans('update.buyer_address') }}</strong>
            <address>{{ $buyer->getAddressInvoice(true) ?? trans('update.not_provided') }}</address>
        </div>

        <div class="info-block">
            <strong>{{ trans('panel.purchase_date') }}</strong>
            <p>{{ dateTimeFormat($sale->created_at ?? time(), 'Y M j | H:i') }}</p>
            @if($isActive && $membershipFeatures['usable_time'])
                <strong style="margin-top:8px;">Expires On</strong>
                <p>{{ dateTimeFormat(($orderItem->created_at + $membershipFeatures['usable_time']), 'Y M j | H:i') }}</p>
            @endif
        </div>

        <div class="info-block" style="text-align:right;">
            <strong>{{ trans('home.platform_address') }}</strong>
            <address>{!! nl2br(getContactPageSettings('address') ?? 'Kemetic App Headquarters') !!}</address>
        </div>
    </div>

    {{-- PLAN STATUS BAR --}}
    <div class="status-row">
        <div style="display:flex; align-items:center;">
            <span class="plan-name">{{ $subscribe->title ?? 'Membership' }}</span>
            <span class="status-pill {{ $isActive ? 'status-active' : 'status-expired' }}">
                <i class="fas {{ $isActive ? 'fa-check-circle' : 'fa-exclamation-circle' }}" style="margin-right:4px;"></i>
                {{ $isActive ? 'Active' : 'Expired' }}
            </span>
        </div>
        <div style="font-size:12px; color:var(--k-muted);">
            Payment:&nbsp;
            @if($sale->payment_method == 'credit')
                <span class="payment-badge badge-credit">Credit</span>
            @elseif($sale->payment_method == 'offline')
                <span class="payment-badge badge-offline">Offline</span>
            @else
                <span class="payment-badge badge-online">Online</span>
            @endif
        </div>
    </div>

    {{-- ORDER SUMMARY TABLE --}}
    <div class="section-title">{{ trans('home.order_summary') }}</div>
    <table class="inv-table">
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
                        <div class="item-desc">{{ $subscribe->description }}</div>
                    @endif
                </td>
                <td>
                    @php
                        if ($subscribe->days == 31) { $membershipType = 'Monthly'; }
                        elseif ($subscribe->days == 365) { $membershipType = 'Yearly'; }
                        elseif ($subscribe->days == 100000) { $membershipType = 'Lifetime'; }
                        else { $membershipType = $subscribe->days . ' Days'; }
                    @endphp
                    {{ $membershipType }}
                </td>
                <td>{{ $subscribe->days ?? 0 }} Days</td>
                <td>{{ handlePrice($sale->amount ?? $subscribe->price) }}</td>
                <td>{{ handlePrice($sale->tax ?? 0) }}</td>
                <td><strong style="color:var(--k-gold);">{{ handlePrice($sale->total_amount ?? $subscribe->price) }}</strong></td>
            </tr>
        </tbody>
    </table>

    {{-- FEATURES + TOTALS --}}
    <div class="bottom-row">
        <div class="features-col">
            <div class="section-title">Membership Features</div>
            @if(!empty($subscribe->features) && is_array($subscribe->features))
                <ul class="feature-list">
                    @foreach($subscribe->features as $feature)
                        <li><i class="fas fa-check-circle"></i> {{ $feature }}</li>
                    @endforeach
                </ul>
            @else
                <ul class="feature-list">
                    <li><i class="fas fa-check-circle"></i> Access to all Courses</li>
                    <li><i class="fas fa-check-circle"></i> Unlimited Downloads</li>
                    <li><i class="fas fa-check-circle"></i> Priority Support</li>
                    <li><i class="fas fa-check-circle"></i> Exclusive Content</li>
                </ul>
            @endif
        </div>

        <div class="totals-col">
            <div class="invoice-detail-item">
                <div class="invoice-detail-name">{{ trans('cart.sub_total') }}</div>
                <div class="invoice-detail-value">{{ handlePrice($sale->amount ?? $subscribe->price) }}</div>
            </div>
            <div class="invoice-detail-item">
                <div class="invoice-detail-name">{{ trans('cart.tax') }}</div>
                <div class="invoice-detail-value">{{ handlePrice($sale->tax ?? 0) }}</div>
            </div>
            <hr>
            <div class="invoice-detail-item">
                <div class="invoice-detail-name">{{ trans('cart.total') }}</div>
                <div class="invoice-detail-value invoice-detail-value-lg">
                    {{ handlePrice($sale->total_amount ?? $subscribe->price) }}
                </div>
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <hr style="margin-top:20px;">
    <div class="inv-footer">
        <div>
            @if($isActive)
                <div class="alert-strip alert-success-strip">
                    <i class="fas fa-check-circle"></i>
                    Your Membership is active until {{ dateTimeFormat(($orderItem->created_at + $membershipFeatures['usable_time']), 'Y M d') }}
                </div>
            @else
                <div class="alert-strip alert-danger-strip">
                    <i class="fas fa-exclamation-circle"></i>
                    Your Membership has Expired
                </div>
            @endif
        </div>
        <div class="no-print" style="display:flex; align-items:center;">
            <button type="button" onclick="window.print()" class="btn-print">
                <i class="fas fa-print"></i> Print
            </button>
            @if(!$isActive)
                <a href="/panel/membership" class="btn-renew">
                    <i class="fas fa-sync-alt"></i> Renew
                </a>
            @endif
        </div>
    </div>

</div>
</body>
</html>`