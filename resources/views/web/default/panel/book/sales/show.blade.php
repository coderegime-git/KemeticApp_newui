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

    .wk-type-print    { background: rgba(52,73,94,.5);     color: #bdc3c7; }
    .wk-type-ebook    { background: rgba(41,128,185,.2);   color: #3498db; }
    .wk-type-audio    { background: rgba(142,68,173,.2);   color: #9b59b6; }

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
</style>
@endpush

@section('content')

@php
    $bookTypeLower = strtolower(trim($bookType ?? 'ebook'));

    // ── Quantity ──────────────────────────────────────────────────────
    $quantity = (isset($quantity) && $quantity > 0)
        ? (int) $quantity
        : (int) (optional($bookOrder)->quantity ?? 1);

    // ── Unit prices — controller already uses getRawOriginal(), trust them ──
    // $printPrice and $shippingPrice come from BookSalesController::show()
    // via $book->getRawOriginal('print_price') / getRawOriginal('shipping_price')
    $unitPrintPrice    = (float) ($printPrice    ?? 0);
    $unitShippingPrice = (float) ($shippingPrice ?? 0);

    // ── Per-quantity totals ──────────────────────────────────────────
    // Use controller-computed totals if available and non-zero,
    // otherwise multiply unit × qty.
    $resolvedPrintTotal    = (isset($printTotal)    && (float)$printTotal    > 0)
                                ? (float)$printTotal
                                : ($unitPrintPrice * $quantity);
    $resolvedShippingTotal = (isset($shippingTotal) && (float)$shippingTotal > 0)
                                ? (float)$shippingTotal
                                : ($unitShippingPrice * $quantity);

    // ── Combined shipping+print for the summary bar ──────────────────
    $printShippingTotal = $resolvedPrintTotal + $resolvedShippingTotal;
    $unitBookPrice = (float) ($bookPrice ?? 0);
@endphp

<div class="wk-wrap">

    <a href="{{ url('panel/book/sales') }}" class="wk-back-btn">
        <i class="fas fa-arrow-left"></i> Back to Sales
    </a>

    {{-- ─── Hero ─────────────────────────────────────────────────────────── --}}
    <div class="wk-hero">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <div class="wk-hero-title"><i class="fas fa-book mr-2"></i>Library Sale Detail</div>
                <p class="wk-hero-sub">Order #{{ $bookOrder->id }} &middot; {{ dateTimeFormat($bookOrder->created_at, 'j F Y H:i') }}</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                {{-- Book Type Badge --}}
                <span class="wk-badge wk-type-{{ $bookTypeLower }}">
                    @if($bookTypeLower === 'print')
                        <i class="fas fa-print"></i>
                    @elseif($bookTypeLower === 'ebook')
                        <i class="fas fa-tablet-alt"></i>
                    @else
                        <i class="fas fa-headphones"></i>
                    @endif
                    {{ ucfirst($bookType) }}
                </span>

                {{-- Sale status --}}
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
                @if($bookTypeLower === 'print')
                    <div class="wk-sum-label">Shipping / Print</div>
                    <div class="wk-sum-val" style="color:#f39c12;">{{ handlePrice($printShippingTotal) }}</div>
                @else
                    <div class="wk-sum-label">Tax</div>
                    <div class="wk-sum-val" style="color:#f39c12;">{{ handlePrice($tax) }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        {{-- ─── LEFT: Book + Buyer ───────────────────────────────────────── --}}
        <div class="col-lg-4">

            {{-- Book Info --}}
            <div class="wk-card">
                <div class="wk-card-head">
                    <i class="fas fa-book" style="color:var(--gold)"></i>
                    <h6>Library</h6>
                </div>
                <div class="wk-card-body">
                    @php $thumb = $book->image_cover ?? null; @endphp
                    @if($thumb)
                        <div class="text-center mb-3">
                            <img src="{{ url($thumb) }}" alt="{{ $book->title }}"
                                 style="max-height:160px;border-radius:4px;border:1px solid var(--border);">
                        </div>
                    @endif
                    <table class="wk-info-table">
                        <tr>
                            <td>Title</td>
                            <td><strong style="color:var(--gold-light);">{{ $book->title }}</strong></td>
                        </tr>
                        <tr>
                            <td>Book ID</td>
                            <td>
                                <code style="color:var(--gold);background:rgba(201,168,76,.1);padding:2px 6px;border-radius:4px;">
                                    #{{ $book->id }}
                                </code>
                            </td>
                        </tr>
                        <tr>
                            <td>Type</td>
                            <td>{{ ucfirst($bookType) }}</td>
                        </tr>
                        <tr>
                            <td>Pages</td>
                            <td>{{ $book->page_count ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Qty Ordered</td>
                            <td><strong style="color:var(--gold);">{{ $quantity }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Buyer --}}
            <div class="wk-card">
                <div class="wk-card-head">
                    <i class="fas fa-user" style="color:var(--gold)"></i>
                    <h6>Buyer</h6>
                </div>
                <div class="wk-card-body">
                    <table class="wk-info-table">
                        <tr>
                            <td>Name</td>
                            <td>{{ optional($bookOrder->buyer)->full_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>ID</td>
                            <td>
                                <code style="color:var(--info);background:rgba(52,152,219,.1);padding:2px 6px;border-radius:4px;">
                                    {{ optional($bookOrder->buyer)->id ?? '—' }}
                                </code>
                            </td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td style="font-size:.82rem;">{{ optional($bookOrder->buyer)->email ?? '—' }}</td>
                        </tr>
                    </table>
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

                        {{-- ── Base / Selling Price ─────────────────────────────── --}}
                        <tr>
                            <td>
                                <i class="fas fa-book mr-2" style="color:var(--gold-dim)"></i>
                                <strong>Library Price</strong>
                                <div class="wk-db-note">books.price (raw)</div>
                            </td>
                            {{-- $unitBookPrice = getRawOriginal('price') from controller --}}
                            <td class="text-right amt-total">{{ handlePrice($unitBookPrice) }}</td>
                            <td class="text-right amt-total">{{ handlePrice($unitBookPrice * $quantity) }}</td>
                            <td style="color:var(--text-muted);font-size:.8rem;">Base selling price</td>
                        </tr>

                        {{-- ── Print & Shipping (physical books only) ───────────── --}}
                        @if($bookTypeLower === 'print')

                            {{-- Print Cost --}}
                            @if($unitPrintPrice > 0)
                                <tr>
                                    <td>
                                        <i class="fas fa-print mr-2" style="color:var(--text-muted)"></i>
                                        Print Cost
                                        <div class="wk-db-note">books.print_price (raw)</div>
                                    </td>
                                    <td class="text-right amt-cost">− {{ handlePrice($unitPrintPrice) }}</td>
                                    <td class="text-right amt-cost">− {{ handlePrice($resolvedPrintTotal) }}</td>
                                    <td style="color:var(--text-muted);font-size:.8rem;">Physical print cost</td>
                                </tr>
                            @else
                                <tr class="wk-row-null">
                                    <td><i class="fas fa-print mr-2"></i>Print Cost</td>
                                    <td class="text-right amt-muted" colspan="2">NULL / 0</td>
                                    <td></td>
                                </tr>
                            @endif

                            {{-- Shipping Fee --}}
                            @if($unitShippingPrice > 0)
                                <tr>
                                    <td>
                                        <i class="fas fa-shipping-fast mr-2" style="color:#f39c12"></i>
                                        Shipping Fee
                                        <div class="wk-db-note">books.shipping_price (raw)</div>
                                    </td>
                                    <td class="text-right amt-cost">− {{ handlePrice($unitShippingPrice) }}</td>
                                    <td class="text-right amt-cost">− {{ handlePrice($resolvedShippingTotal) }}</td>
                                    <td style="color:var(--text-muted);font-size:.8rem;">Delivery charge</td>
                                </tr>
                            @else
                                <tr class="wk-row-null">
                                    <td><i class="fas fa-shipping-fast mr-2"></i>Shipping Fee</td>
                                    <td class="text-right amt-muted" colspan="2">NULL / 0</td>
                                    <td></td>
                                </tr>
                            @endif

                        @endif

                        {{-- ── Discount ──────────────────────────────────────────── --}}
                        @if($discount > 0)
                            <tr>
                                <td>
                                    <i class="fas fa-percent mr-2" style="color:#f39c12"></i>
                                    Discount / Coupon
                                </td>
                                <td class="text-right amt-cost" colspan="2">− {{ handlePrice($discount) }}</td>
                                <td style="color:var(--text-muted);font-size:.8rem;">Applied coupon</td>
                            </tr>
                        @endif

                        {{-- ── Tax ───────────────────────────────────────────────── --}}
                        @if($tax > 0)
                            <tr>
                                <td>
                                    <i class="fas fa-landmark mr-2" style="color:var(--text-muted)"></i>
                                    Tax
                                </td>
                                <td class="text-right" colspan="2">+ {{ handlePrice($tax) }}</td>
                                <td style="color:var(--text-muted);font-size:.8rem;">{{ $book->getTax() }}%</td>
                            </tr>
                        @endif

                        {{-- ── Revenue Split divider ────────────────────────────── --}}
                        <tr class="wk-row-divider">
                            <td colspan="4">── Revenue Split ──</td>
                        </tr>

                        {{-- ── Platform Fee ─────────────────────────────────────── --}}
                        <tr class="wk-row-plat">
                            <td>
                                <i class="fas fa-server mr-2" style="color:#e74c3c"></i>
                                <strong>Platform Fee</strong>
                                <div class="wk-db-note">
                                    {{ $platformPrice > 0 ? 'books.platform_price (fixed)' : $commissionPct.'% commission' }}
                                </div>
                            </td>
                            <td class="text-right amt-muted">
                                {{ $platformPrice > 0 ? handlePrice($platformPrice) : $commissionPct.'%' }}
                            </td>
                            <td class="text-right amt-plat">{{ handlePrice($platformAmount) }}</td>
                            <td style="color:var(--text-muted);font-size:.8rem;">Platform cut</td>
                        </tr>

                        {{-- ── Your Earnings ─────────────────────────────────────── --}}
                        <tr class="wk-row-earn">
                            <td>
                                <i class="fas fa-hand-holding-usd mr-2" style="color:#2ecc71"></i>
                                <strong>Your Earnings</strong>
                            </td>
                            <td class="text-right amt-muted">—</td>
                            <td class="text-right amt-earn">{{ handlePrice(max(0, $earningAmount)) }}</td>
                            <td style="color:var(--text-muted);font-size:.8rem;">
                                Total − Platform − Tax
                                @if($bookTypeLower === 'print') − Print − Shipping @endif
                            </td>
                        </tr>

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
        </div>
    </div>

    {{-- ─── Delivery Stepper (Print Book only) ──────────────────────────────── --}}
    @if($bookTypeLower === 'print')
        <div class="wk-card">
            <div class="wk-card-head">
                <i class="fas fa-truck" style="color:var(--gold)"></i>
                <h6>Delivery Status</h6>
            </div>
            <div class="wk-card-body">
                <div class="wk-stepper">
                    @php
                        $statusKeys = array_keys($deliveryStatuses);
                        $currentIdx = array_search($currentStatus, $statusKeys);
                    @endphp
                    @foreach($deliveryStatuses as $sk => $sv)
                        @php
                            $thisIdx  = array_search($sk, $statusKeys);
                            $isPast   = $thisIdx < $currentIdx;
                            $isActive = $sk === $currentStatus;
                        @endphp
                        <div class="wk-step">
                            <div class="wk-step-dot {{ $isActive ? 'active-step' : ($isPast ? 'past-step' : '') }}"
                                 style="{{ $isActive ? 'border-color:'.$sv['color'].';color:'.$sv['color'] : ($isPast ? 'border-color:'.$sv['color'].';color:'.$sv['color'] : '') }}">
                                @if($sk === 'pending')          <i class="fas fa-clock"></i>
                                @elseif($sk === 'waiting_delivery') <i class="fas fa-box"></i>
                                @elseif($sk === 'shipped')      <i class="fas fa-shipping-fast"></i>
                                @elseif($sk === 'success')      <i class="fas fa-check-double"></i>
                                @elseif($sk === 'canceled')     <i class="fas fa-times"></i>
                                @else                           <i class="fas fa-circle"></i>
                                @endif
                            </div>
                            <div class="wk-step-label {{ $isActive ? 'active-label' : '' }}"
                                 style="{{ $isActive ? 'color:'.$sv['color'] : '' }}">
                                {{ $sv['label'] }}
                            </div>
                        </div>
                        @if(!$loop->last)
                            <div class="wk-step-line {{ $isPast ? 'past-line' : '' }}"></div>
                        @endif
                    @endforeach
                </div>

                <div class="mt-3" style="display:flex;gap:28px;flex-wrap:wrap;">
                    @if(!empty($bookOrder->tracking_code))
                        <div>
                            <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin-bottom:4px;">Tracking Code</div>
                            <code style="background:rgba(201,168,76,.1);color:var(--gold);padding:4px 10px;border-radius:6px;font-size:.85rem;">
                                {{ $bookOrder->tracking_code }}
                            </code>
                        </div>
                    @endif
                    @if(!empty($bookOrder->printjob_id))
                        <div>
                            <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin-bottom:4px;">Print Job ID</div>
                            <code style="background:rgba(255,165,0,.1);color:#ffa500;padding:4px 10px;border-radius:6px;font-size:.85rem;">
                                {{ $bookOrder->printjob_id }}
                            </code>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

</div>
@endsection