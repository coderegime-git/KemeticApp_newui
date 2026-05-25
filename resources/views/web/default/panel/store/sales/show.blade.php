@extends('web.default.layouts.newapp')

@push('styles_top')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Crimson+Pro:ital,wght@0,300;0,400;0,600;1,300&display=swap');
    :root{--gold:#c9a84c;--gold-light:#e8c96a;--gold-dim:#8a6e2f;--surface:#16161d;--surface2:#1e1e28;--surface3:#262634;--border:rgba(201,168,76,.18);--text:#e8e4d8;--text-muted:#8a8676;--success:#2ecc71;--warning:#f39c12;--info:#3498db;--danger:#e74c3c;}
    .wk-wrap{font-family:'Crimson Pro',Georgia,serif;color:var(--text);}

    .wk-back-btn{display:inline-flex;align-items:center;gap:7px;background:rgba(201,168,76,.1);border:1px solid var(--border);color:var(--gold);border-radius:8px;padding:8px 18px;font-family:'Cinzel',serif;font-size:.8rem;letter-spacing:.06em;text-decoration:none;margin-bottom:24px;transition:background .2s;}
    .wk-back-btn:hover{background:rgba(201,168,76,.18);color:var(--gold-light);text-decoration:none;}

    .wk-hero{background:linear-gradient(135deg,var(--surface) 0%,var(--surface2) 60%,#1a1226 100%);border:1px solid var(--border);border-radius:16px;padding:28px 32px;margin-bottom:24px;position:relative;overflow:hidden;}
    .wk-hero::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,var(--gold),transparent);}
    .wk-hero-title{font-family:'Cinzel',serif;font-size:1.4rem;font-weight:700;color:var(--gold-light);margin:0 0 6px;letter-spacing:.04em;}
    .wk-hero-sub{color:var(--text-muted);font-style:italic;margin:0;}

    .wk-card{background:var(--surface2);border:1px solid var(--border);border-radius:14px;margin-bottom:22px;overflow:hidden;}
    .wk-card-head{background:var(--surface3);border-bottom:1px solid var(--border);padding:14px 20px;display:flex;align-items:center;gap:10px;}
    .wk-card-head h6{font-family:'Cinzel',serif;font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.1em;color:var(--gold);margin:0;}
    .wk-card-body{padding:20px;}

    .wk-info-table{width:100%;border-collapse:collapse;}
    .wk-info-table tr{border-bottom:1px solid rgba(255,255,255,.04);}
    .wk-info-table tr:last-child{border-bottom:none;}
    .wk-info-table td{padding:9px 4px;vertical-align:middle;font-size:.92rem;}
    .wk-info-table td:first-child{color:var(--text-muted);font-size:.8rem;text-transform:uppercase;letter-spacing:.06em;width:42%;}

    .wk-price-table{width:100%;border-collapse:collapse;}
    .wk-price-table thead th{background:var(--surface3);font-family:'Cinzel',serif;font-size:.72rem;text-transform:uppercase;letter-spacing:.1em;color:var(--gold);padding:12px 14px;border-bottom:1px solid var(--border);}
    .wk-price-table tbody tr{border-bottom:1px solid rgba(255,255,255,.04);transition:background .15s;}
    .wk-price-table tbody tr:hover{background:rgba(201,168,76,.03);}
    .wk-price-table tbody td{padding:13px 14px;font-size:.92rem;vertical-align:middle;}
    .wk-price-table tfoot td{padding:14px;border-top:1px solid var(--border);font-family:'Cinzel',serif;font-size:.85rem;background:var(--surface3);}
    .wk-row-null{opacity:.45;}
    .wk-row-earn{background:rgba(46,204,113,.06);}
    .wk-row-plat{background:rgba(231,76,60,.06);}
    .wk-row-ship{background:rgba(243,156,18,.06);}
    .wk-row-divider td{background:var(--surface3);padding:6px 14px;font-size:.7rem;text-transform:uppercase;letter-spacing:.1em;color:var(--text-muted);font-family:'Cinzel',serif;}

    .amt-total{color:var(--gold-light);font-weight:700;font-family:'Cinzel',serif;}
    .amt-earn{color:var(--success);font-weight:700;font-family:'Cinzel',serif;font-size:1rem;}
    .amt-plat{color:var(--danger);font-weight:600;}
    .amt-cost{color:var(--warning);font-weight:600;}
    .amt-muted{color:var(--text-muted);font-size:.8rem;}

    .wk-badge{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:20px;font-size:.78rem;font-weight:600;}
    .wk-badge-success{background:rgba(46,204,113,.15);color:#2ecc71;}
    .wk-badge-warning{background:rgba(243,156,18,.15);color:#f39c12;}
    .wk-badge-info{background:rgba(52,152,219,.15);color:#3498db;}
    .wk-badge-danger{background:rgba(231,76,60,.15);color:#e74c3c;}
    .wk-badge-purple{background:rgba(155,89,182,.15);color:#9b59b6;}

    .wk-summary{background:linear-gradient(135deg,#0e0e12 0%,#16161d 50%,#0a0a10 100%);border:1px solid var(--border);border-radius:14px;padding:28px;margin-bottom:22px;position:relative;overflow:hidden;}
    .wk-summary::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,var(--gold),transparent);}
    .wk-sum-item{text-align:center;padding:8px;}
    .wk-sum-label{color:rgba(255,255,255,.4);font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;}
    .wk-sum-val{font-family:'Cinzel',serif;font-size:1.35rem;font-weight:700;line-height:1;}

    .wk-stepper{display:flex;align-items:center;padding:10px 0;overflow-x:auto;}
    .wk-step{text-align:center;min-width:80px;flex:1;}
    .wk-step-dot{width:42px;height:42px;border-radius:50%;margin:0 auto 8px;display:flex;align-items:center;justify-content:center;font-size:16px;border:2px solid var(--border);background:var(--surface3);color:var(--text-muted);transition:all .3s;}
    .wk-step-dot.active-step{background:rgba(201,168,76,.15);border-color:var(--gold);color:var(--gold);}
    .wk-step-dot.past-step{border-color:var(--success);color:var(--success);}
    .wk-step-label{font-size:.72rem;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);}
    .wk-step-label.active-label{color:var(--gold);font-weight:600;}
    .wk-step-line{flex:1;height:2px;background:var(--border);min-width:20px;}
    .wk-step-line.past-line{background:var(--success);}

    .wk-db-note{font-size:.7rem;color:var(--text-muted);font-family:'Courier New',monospace;}

    .wk-cj-specs table{width:100%;border-collapse:collapse;}
    .wk-cj-specs td{padding:8px 14px;border-bottom:1px solid rgba(255,255,255,.04);font-size:.88rem;}
    .wk-cj-specs td:first-child{color:var(--text-muted);font-size:.78rem;text-transform:uppercase;letter-spacing:.05em;width:38%;}
</style>
@endpush

@section('content')
<div class="wk-wrap">

    <a href="{{ url('panel/store/sales') }}" class="wk-back-btn">
        <i class="fas fa-arrow-left"></i> Back to Product Sales
    </a>

    {{-- ─── Hero ─────────────────────────────────────────────────────────── --}}
    <div class="wk-hero">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <div class="wk-hero-title"><i class="fas fa-box-open mr-2"></i>Product Sale Detail</div>
                <p class="wk-hero-sub">Order #{{ $productOrder->id }} &middot; {{ dateTimeFormat($productOrder->created_at, 'j F Y H:i') }}</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                @if($isCj)
                    <span class="wk-badge" style="background:rgba(255,165,0,.15);color:#ffa500;border:1px solid rgba(255,165,0,.3);">
                        <i class="fas fa-globe"></i> CJ Dropshipping
                    </span>
                @else
                    <span class="wk-badge" style="background:rgba(100,100,120,.25);color:#aaa;border:1px solid rgba(150,150,170,.2);">
                        <i class="fas fa-store"></i> Own Platform
                    </span>
                @endif
                @if($isPhysical)
                    <span class="wk-badge" style="background:rgba(52,73,94,.5);color:#bdc3c7;border:1px solid #4a5568;">
                        <i class="fas fa-box"></i> Physical
                    </span>
                @else
                    <span class="wk-badge wk-badge-info"><i class="fas fa-cloud-download-alt"></i> Virtual</span>
                @endif
                @if(!empty($sale->refund_at))
                    <span class="wk-badge wk-badge-warning"><i class="fas fa-undo"></i> Refunded</span>
                @elseif(!$sale->access_to_purchased_item)
                    <span class="wk-badge wk-badge-danger"><i class="fas fa-ban"></i> Blocked</span>
                @else
                    <span class="wk-badge wk-badge-success"><i class="fas fa-check"></i> Successful</span>
                @endif
            </div>
        </div>
    </div>

    {{-- ─── Summary Bar ──────────────────────────────────────────────────── --}}
    <div class="wk-summary mb-4">
        <div class="row">
            <div class="col-6 col-md-3 wk-sum-item">
                <div class="wk-sum-label">Total Paid</div>
                <div class="wk-sum-val" style="color:var(--gold-light);">{{ handlePrice($totalAmount) }}</div>
            </div>
            <div class="col-6 col-md-3 wk-sum-item">
                <div class="wk-sum-label">Your Earnings</div>
                <div class="wk-sum-val" style="color:#2ecc71;">{{ handlePrice(max(0, $earningAmount)) }}</div>
            </div>
            <div class="col-6 col-md-3 wk-sum-item">
                <div class="wk-sum-label">Platform Fee</div>
                <div class="wk-sum-val" style="color:#e74c3c;">{{ handlePrice($platformAmount) }}</div>
                <div style="color:rgba(255,255,255,.3);font-size:.72rem;">{{ $commissionPct }}%</div>
            </div>
            <div class="col-6 col-md-3 wk-sum-item">
                @if($isPhysical)
                    <div class="wk-sum-label">Shipping Fee</div>
                    <div class="wk-sum-val" style="color:#f39c12;">{{ handlePrice($shippingFee) }}</div>
                @else
                    <div class="wk-sum-label">Tax</div>
                    <div class="wk-sum-val" style="color:#f39c12;">{{ handlePrice($tax) }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        {{-- ─── LEFT: Product + Buyer ───────────────────────────────────────── --}}
        <div class="col-lg-4">

            {{-- Product Info --}}
            <div class="wk-card">
                <div class="wk-card-head"><i class="fas fa-box" style="color:var(--gold)"></i><h6>Product</h6></div>
                <div class="wk-card-body">
                    @php $thumb = $product->thumbnail ?? null; @endphp
                    @if($thumb)
                        <div class="text-center mb-3">
                            <img src="{{ url($thumb) }}" alt="{{ $product->title }}"
                                 style="max-height:120px;border-radius:8px;border:1px solid var(--border);">
                        </div>
                    @endif
                    <table class="wk-info-table">
                        <tr><td>Title</td>      <td><strong style="color:var(--gold-light);">{{ $product->title }}</strong></td></tr>
                        <tr><td>Product ID</td> <td><code style="color:var(--gold);background:rgba(201,168,76,.1);padding:2px 6px;border-radius:4px;">#{{ $product->id }}</code></td></tr>
                        <tr><td>Type</td>       <td>{{ ucfirst($product->type) }}</td></tr>
                        <tr><td>Category</td>   <td>{{ optional($product->category)->title ?? 'N/A' }}</td></tr>
                        <tr><td>Qty Ordered</td><td><strong style="color:var(--gold);">{{ $quantity }}</strong></td></tr>
                    </table>

                    {{-- CJ Variant block --}}
                    @if($isCj && $cjVariant)
                    <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--border);">
                        <div style="font-family:'Cinzel',serif;font-size:.72rem;text-transform:uppercase;letter-spacing:.08em;color:var(--gold);margin-bottom:10px;">CJ Variant</div>
                        @if($cjVariant->variant_image)
                            <div class="text-center mb-2">
                                <img src="{{ $cjVariant->variant_image }}" style="height:52px;border-radius:6px;border:1px solid var(--border);">
                            </div>
                        @endif
                        <table class="wk-info-table">
                            <tr><td>Variant</td><td>{{ $cjVariant->variant_name }}</td></tr>
                            <tr><td>SKU</td>    <td><code style="font-size:.78rem;">{{ $cjVariant->variant_sku }}</code></td></tr>
                            <tr><td>CJ PID</td> <td><code style="font-size:.78rem;">{{ $cjVariant->cj_pid }}</code></td></tr>
                            @php $trueBase = max(0, ($product->price ?? 0) - ($product->cj_shipping_price ?? 0) - ($product->cj_your_price ?? 0) - ($product->platform_price ?? 0)); @endphp
                            <tr><td>Sell Price</td><td><strong style="color:var(--gold);">{{ handlePrice($trueBase) }}</strong></td></tr>
                        </table>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Buyer --}}
            <div class="wk-card">
                <div class="wk-card-head"><i class="fas fa-user" style="color:var(--gold)"></i><h6>Buyer</h6></div>
                <div class="wk-card-body">
                    <table class="wk-info-table">
                        <tr><td>Name</td> <td>{{ optional($productOrder->buyer)->full_name ?? 'N/A' }}</td></tr>
                        <tr><td>ID</td>   <td><code style="color:var(--info);background:rgba(52,152,219,.1);padding:2px 6px;border-radius:4px;">{{ optional($productOrder->buyer)->id ?? '—' }}</code></td></tr>
                        <tr><td>Email</td><td style="font-size:.82rem;">{{ optional($productOrder->buyer)->email ?? '—' }}</td></tr>
                        @if($isPhysical && !empty($productOrder->buyer))
                            <tr><td>Phone</td><td>{{ optional($productOrder->buyer)->mobile ?? '—' }}</td></tr>
                        @endif
                    </table>

                    @if($isPhysical && !empty($productOrder->buyer))
                    <div style="margin-top:12px;padding:10px 12px;background:var(--surface3);border-radius:8px;">
                        <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin-bottom:4px;"><i class="fas fa-map-marker-alt" style="color:#e74c3c;"></i> Shipping Address</div>
                        <div style="font-size:.88rem;color:var(--text);">{{ optional($productOrder->buyer)->getAddress(true) ?? 'No address provided' }}</div>
                    </div>
                    @endif

                    @if(!empty($productOrder->message_to_seller))
                    <div style="margin-top:12px;padding:10px 12px;background:rgba(201,168,76,.07);border-radius:8px;border-left:3px solid var(--gold-dim);">
                        <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin-bottom:4px;">Note from Buyer</div>
                        <div style="font-style:italic;font-size:.88rem;">{{ $productOrder->message_to_seller }}</div>
                    </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- ─── RIGHT: Price Breakdown ─────────────────────────────────────── --}}
        <div class="col-lg-8">
            <div class="wk-card">
                <div class="wk-card-head">
                    <i class="fas fa-tags" style="color:var(--gold)"></i>
                    <h6>Price Breakdown</h6>
                    @if($quantity > 1)
                        <span style="margin-left:auto;font-size:.75rem;color:var(--text-muted);">× {{ $quantity }} qty</span>
                    @endif
                </div>
                <table class="wk-price-table">
                    <thead>
                        <tr>
                            <th style="width:38%">Component</th>
                            <th class="text-right" style="width:20%">Unit Price</th>
                            <th class="text-right" style="width:22%">Amount</th>
                            <th style="width:20%">Note</th>
                        </tr>
                    </thead>
                    <tbody>

                        {{-- ── CJ: 4-row granular breakdown ── --}}
                        @if($isCj)
                        @php
                            $trueCjBasePrice = max(0,
                                ($product->price          ?? 0)
                                - ($product->cj_shipping_price ?? 0)
                                - ($product->cj_your_price     ?? 0)
                                - ($product->platform_price    ?? 0)
                            );
                        @endphp
                        <tr>
                            <td>
                                <i class="fas fa-tag mr-2" style="color:var(--gold-dim)"></i>
                                <strong>Base Price</strong>
                                <div class="wk-db-note">Calculated CJ Base Price</div>
                            </td>
                            <td class="text-right amt-total">{{ handlePrice($trueCjBasePrice) }}</td>
                            <td class="text-right amt-total">{{ handlePrice($trueCjBasePrice * $quantity) }}</td>
                            <td style="color:var(--text-muted);font-size:.8rem;">CJ base cost</td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fas fa-shipping-fast mr-2" style="color:#f39c12"></i>
                                <strong>CJ Shipping Price</strong>
                                <div class="wk-db-note">products.cj_shipping_price</div>
                            </td>
                            <td class="text-right amt-cost">{{ handlePrice($product->cj_shipping_price ?? 0) }}</td>
                            <td class="text-right amt-cost">{{ handlePrice(($product->cj_shipping_price ?? 0) * $quantity) }}</td>
                            <td style="color:var(--text-muted);font-size:.8rem;">CJ delivery cost</td>
                        </tr>
                        <tr class="wk-row-earn">
                            <td>
                                <i class="fas fa-hand-holding-usd mr-2" style="color:#2ecc71"></i>
                                <strong>CJ Your Price</strong>
                                <div class="wk-db-note">products.cj_your_price</div>
                            </td>
                            <td class="text-right amt-earn">{{ handlePrice($product->cj_your_price ?? 0) }}</td>
                            <td class="text-right amt-earn">{{ handlePrice(($product->cj_your_price ?? 0) * $quantity) }}</td>
                            <td style="color:var(--text-muted);font-size:.8rem;">Seller pricing</td>
                        </tr>
                        <tr class="wk-row-plat">
                            <td>
                                <i class="fas fa-server mr-2" style="color:#e74c3c"></i>
                                <strong>Platform Price</strong>
                                <div class="wk-db-note">products.platform_price</div>
                            </td>
                            <td class="text-right amt-plat">{{ handlePrice($product->platform_price ?? 0) }}</td>
                            <td class="text-right amt-plat">{{ handlePrice(($product->platform_price ?? 0) * $quantity) }}</td>
                            <td style="color:var(--text-muted);font-size:.8rem;">Platform fee</td>
                        </tr>

                        {{-- ── Own Platform: 3-row breakdown ── --}}
                        @else
                        <tr>
                            <td>
                                <i class="fas fa-tag mr-2" style="color:var(--gold-dim)"></i>
                                <strong>Base Price</strong>
                                <div class="wk-db-note">products.price</div>
                            </td>
                            <td class="text-right amt-total">{{ handlePrice($product->price ?? 0) }}</td>
                            <td class="text-right amt-total">{{ handlePrice(($product->price ?? 0) * $quantity) }}</td>
                            <td style="color:var(--text-muted);font-size:.8rem;">Listed price</td>
                        </tr>
                        <tr class="wk-row-earn">
                            <td>
                                <i class="fas fa-hand-holding-usd mr-2" style="color:#2ecc71"></i>
                                <strong>Earning Price</strong>
                                <div class="wk-db-note">products.earning_price</div>
                            </td>
                            <td class="text-right amt-earn">{{ handlePrice($product->earning_price ?? 0) }}</td>
                            <td class="text-right amt-earn">{{ handlePrice(($product->earning_price ?? 0) * $quantity) }}</td>
                            <td style="color:var(--text-muted);font-size:.8rem;">Seller earning</td>
                        </tr>
                        <tr class="wk-row-plat">
                            <td>
                                <i class="fas fa-server mr-2" style="color:#e74c3c"></i>
                                <strong>Own Platform Price</strong>
                                <div class="wk-db-note">products.own_platform_price</div>
                            </td>
                            <td class="text-right amt-plat">− {{ handlePrice($product->own_platform_price ?? 0) }}</td>
                            <td class="text-right amt-plat">− {{ handlePrice(($product->own_platform_price ?? 0) * $quantity) }}</td>
                            <td style="color:var(--text-muted);font-size:.8rem;">Platform fee</td>
                        </tr>
                        @endif

                        {{-- ── Discount ── --}}
                        @if($discount > 0)
                        <tr>
                            <td><i class="fas fa-percent mr-2" style="color:#f39c12"></i>Discount / Coupon</td>
                            <td class="text-right amt-cost" colspan="2">− {{ handlePrice($discount) }}</td>
                            <td style="color:var(--text-muted);font-size:.8rem;">Applied coupon</td>
                        </tr>
                        @endif

                        {{-- ── Tax ── --}}
                        @if($tax > 0)
                        <tr>
                            <td><i class="fas fa-landmark mr-2" style="color:var(--text-muted)"></i>Tax</td>
                            <td class="text-right" colspan="2">+ {{ handlePrice($tax) }}</td>
                            <td style="color:var(--text-muted);font-size:.8rem;">{{ $product->getTax() }}%</td>
                        </tr>
                        @endif

                        {{-- ── Revenue Split ── --}}
                        <tr class="wk-row-divider"><td colspan="4">── Revenue Split ──</td></tr>

                        @if($isCj)
                        {{-- CJ: cj_shipping_price | cj_your_price | platform_price --}}
                        <tr class="wk-row-ship">
                            <td>
                                <i class="fas fa-shipping-fast mr-2" style="color:#f39c12"></i>
                                <strong>Shipping (CJ)</strong>
                                <div class="wk-db-note">products.cj_shipping_price</div>
                            </td>
                            <td class="text-right amt-muted">—</td>
                            <td class="text-right amt-cost">{{ handlePrice($product->cj_shipping_price ?? 0) }}</td>
                            <td style="color:var(--text-muted);font-size:.8rem;">CJ delivery cost</td>
                        </tr>
                        <tr class="wk-row-earn">
                            <td>
                                <i class="fas fa-hand-holding-usd mr-2" style="color:#2ecc71"></i>
                                <strong>Your Earnings</strong>
                                <div class="wk-db-note">products.cj_your_price</div>
                            </td>
                            <td class="text-right amt-muted">—</td>
                            <td class="text-right amt-earn">{{ handlePrice($product->cj_your_price ?? 0) }}</td>
                            <td style="color:var(--text-muted);font-size:.8rem;">Seller earning</td>
                        </tr>
                        <tr class="wk-row-plat">
                            <td>
                                <i class="fas fa-server mr-2" style="color:#e74c3c"></i>
                                <strong>Platform Earnings</strong>
                                <div class="wk-db-note">products.platform_price</div>
                            </td>
                            <td class="text-right amt-muted">—</td>
                            <td class="text-right amt-plat">{{ handlePrice($product->platform_price ?? 0) }}</td>
                            <td style="color:var(--text-muted);font-size:.8rem;">Platform fee</td>
                        </tr>

                        @else
                        {{-- Own Platform: product_delivery_fee (physical) | earning_price | own_platform_price --}}
                        @if($isPhysical)
                        <tr class="wk-row-ship">
                            <td>
                                <i class="fas fa-shipping-fast mr-2" style="color:#f39c12"></i>
                                <strong>Shipping Fee</strong>
                                <div class="wk-db-note">sales.product_delivery_fee</div>
                            </td>
                            <td class="text-right amt-muted">—</td>
                            <td class="text-right amt-cost">{{ handlePrice($shippingFee) }}</td>
                            <td style="color:var(--text-muted);font-size:.8rem;">Delivery charge</td>
                        </tr>
                        @endif
                        <tr class="wk-row-earn">
                            <td>
                                <i class="fas fa-hand-holding-usd mr-2" style="color:#2ecc71"></i>
                                <strong>Your Earnings</strong>
                                <div class="wk-db-note">products.earning_price</div>
                            </td>
                            <td class="text-right amt-muted">—</td>
                            <td class="text-right amt-earn">{{ handlePrice($product->earning_price ?? 0) }}</td>
                            <td style="color:var(--text-muted);font-size:.8rem;">Seller earning</td>
                        </tr>
                        <tr class="wk-row-plat">
                            <td>
                                <i class="fas fa-server mr-2" style="color:#e74c3c"></i>
                                <strong>Platform Earnings</strong>
                                <div class="wk-db-note">products.own_platform_price</div>
                            </td>
                            <td class="text-right amt-muted">—</td>
                            <td class="text-right amt-plat">{{ handlePrice($product->own_platform_price ?? 0) }}</td>
                            <td style="color:var(--text-muted);font-size:.8rem;">Platform fee</td>
                        </tr>
                        @endif

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">
                                <strong style="font-family:'Cinzel',serif;color:var(--gold);font-size:.8rem;text-transform:uppercase;letter-spacing:.08em;">
                                    Total Paid by Buyer
                                </strong>
                            </td>
                            <td class="text-right">
                                <strong class="amt-total" style="font-size:1.05rem;">{{ handlePrice($totalAmount) }}</strong>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- CJ Specs (if CJ) --}}
            @if($isCj && !empty($cjSpecs))
            <div class="wk-card">
                <div class="wk-card-head"><i class="fas fa-list-alt" style="color:var(--gold)"></i><h6>CJ Order Specifications</h6></div>
                <div class="wk-cj-specs">
                    <table>
                        @foreach($cjSpecs as $key => $val)
                        <tr>
                            <td>{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                            <td>{{ is_array($val) ? json_encode($val) : $val }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- ─── Delivery Stepper (Physical only) ──────────────────────────────── --}}
    @if($isPhysical)
    <div class="wk-card">
        <div class="wk-card-head"><i class="fas fa-truck" style="color:var(--gold)"></i><h6>Delivery Status</h6></div>
        <div class="wk-card-body">
            <div class="wk-stepper">
                @php $statusKeys = array_keys($deliveryStatuses); $currentIdx = array_search($currentStatus, $statusKeys); @endphp
                @foreach($deliveryStatuses as $sk => $sv)
                    @php
                        $thisIdx  = array_search($sk, $statusKeys);
                        $isPast   = $thisIdx < $currentIdx;   // strictly before current
                        $isActive = $sk === $currentStatus;
                        $isDone   = $isPast || $isActive;     // highlighted = past + active
                    @endphp

                    <div class="wk-step">
                        <div class="wk-step-dot
                            {{ $isActive ? 'active-step' : ($isPast ? 'past-step' : '') }}"
                            style="
                                {{ $isActive ? 'border-color:'.$sv['color'].';color:'.$sv['color'].';background:rgba(201,168,76,.15);' : '' }}
                                {{ $isPast   ? 'border-color:#2ecc71;color:#2ecc71;' : '' }}
                            ">
                            <i class="fas fa-{{ $sv['icon'] }}"></i>
                        </div>
                        <div class="wk-step-label {{ $isActive ? 'active-label' : '' }}"
                            style="{{ $isActive ? 'color:'.$sv['color'] : '' }}">
                            {{ $sv['label'] }}
                        </div>
                    </div>

                    @if(!$loop->last)
                        {{-- Line turns green once the NEXT step's index <= currentIdx --}}
                        @php $nextIdx = $thisIdx + 1; @endphp
                        <div class="wk-step-line {{ $nextIdx <= $currentIdx ? 'past-line' : '' }}"></div>
                    @endif
                @endforeach
            </div>

            {{-- tracking codes / CJ info below stepper — unchanged --}}
            <div class="mt-3" style="display:flex;gap:28px;flex-wrap:wrap;">
                @if(!empty($productOrder->tracking_code))
                <div>
                    <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin-bottom:4px;">Tracking Code</div>
                    <code style="background:rgba(201,168,76,.1);color:var(--gold);padding:4px 10px;border-radius:6px;font-size:.85rem;">{{ $productOrder->tracking_code }}</code>
                </div>
                @endif
                @if($isCj && !empty($productOrder->cj_order_id))
                <div>
                    <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin-bottom:4px;">CJ Order ID</div>
                    <code style="background:rgba(255,165,0,.1);color:#ffa500;padding:4px 10px;border-radius:6px;font-size:.85rem;">{{ $productOrder->cj_order_id }}</code>
                </div>
                @endif
                @if($isCj && !empty($productOrder->cj_tracking_number))
                <div>
                    <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin-bottom:4px;">CJ Tracking #</div>
                    <code style="background:rgba(255,165,0,.1);color:#ffa500;padding:4px 10px;border-radius:6px;font-size:.85rem;">{{ $productOrder->cj_tracking_number }}</code>
                </div>
                @endif
            </div>

            @if($currentStatus === \App\Models\ProductOrder::$waitingDelivery && !$isCj)
            <div style="margin-top:20px;padding-top:20px;border-top:1px dashed var(--border);">
                <form action="/panel/store/sales/{{ $sale->id }}/productOrder/{{ $productOrder->id }}/setTrackingCode" method="post" id="setTrackingCodeForm">
                    <div class="form-group">
                        <label style="font-size:.85rem;color:var(--gold-light);margin-bottom:8px;display:block;">Update Delivery Status (Enter Tracking Code)</label>
                        <div class="d-flex" style="gap:10px;max-width:400px;">
                            <input type="text" name="tracking_code" class="form-control" placeholder="Enter tracking number..." style="background:var(--surface3);border:1px solid var(--border);color:var(--text);border-radius:8px;" required>
                            <button type="submit" class="btn btn-sm" style="background:linear-gradient(135deg,var(--gold-dim),var(--gold));color:#0e0e12;border:none;border-radius:8px;font-family:'Cinzel',serif;font-weight:600;padding:0 20px;">Save & Ship</button>
                        </div>
                        <div class="invalid-feedback text-danger" style="font-size:0.8rem;margin-top:5px;display:none;"></div>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts_bottom')
<script>
    (function($) {
        "use strict";
        $('#setTrackingCodeForm').on('submit', function(e) {
            e.preventDefault();
            var $form = $(this);
            var $btn = $form.find('button[type="submit"]');
            var action = $form.attr('action');
            var data = $form.serialize();

            $btn.prop('disabled', true).text('Saving...');
            $form.find('.form-control').removeClass('is-invalid');
            $form.find('.invalid-feedback').hide();

            $.post(action, data, function(result) {
                if (result && result.code === 200) {
                    Swal.fire({
                        icon: 'success',
                        html: '<h3 class="font-20 text-center text-dark-blue py-25">Tracking code saved successfully</h3>',
                        showConfirmButton: false,
                        width: '25rem'
                    });
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                }
            }).fail(function(err) {
                $btn.prop('disabled', false).text('Save & Ship');
                var errors = err.responseJSON;
                if (errors && errors.errors) {
                    Object.keys(errors.errors).forEach(function(key) {
                        var error = errors.errors[key];
                        var element = $form.find('[name="' + key + '"]');
                        element.addClass('is-invalid');
                        element.parent().next('.invalid-feedback').text(error[0]).show();
                    });
                }
            });
        });
    })(jQuery);
</script>
@endpush