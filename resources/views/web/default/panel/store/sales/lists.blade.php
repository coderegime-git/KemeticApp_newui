@extends('web.default.layouts.newapp')

@push('styles_top')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Crimson+Pro:ital,wght@0,300;0,400;0,600;1,300&display=swap');
    :root {
        --gold:#c9a84c;--gold-light:#e8c96a;--gold-dim:#8a6e2f;
        --surface:#16161d;--surface2:#1e1e28;--surface3:#262634;
        --border:rgba(201,168,76,.18);--text:#e8e4d8;--text-muted:#8a8676;
        --success:#2ecc71;--warning:#f39c12;--info:#3498db;--danger:#e74c3c;
    }
    .wk-wrap{font-family:'Crimson Pro',Georgia,serif;color:var(--text);}

    .wk-page-header{background:linear-gradient(135deg,var(--surface) 0%,var(--surface2) 100%);border:1px solid var(--border);border-radius:16px;padding:32px 36px;margin-bottom:28px;position:relative;overflow:hidden;}
    .wk-page-header::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,var(--gold),transparent);}
    .wk-page-header h1{font-family:'Cinzel',serif;font-size:1.7rem;font-weight:700;color:var(--gold-light);margin:0 0 4px;letter-spacing:.04em;}
    .wk-page-header p{color:var(--text-muted);margin:0;font-style:italic;}

    .wk-stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(155px,1fr));gap:16px;margin-bottom:28px;}
    .wk-stat{background:var(--surface2);border:1px solid var(--border);border-radius:12px;padding:20px 18px;transition:transform .2s,border-color .2s;}
    .wk-stat:hover{transform:translateY(-2px);border-color:var(--gold-dim);}
    .wk-stat-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:12px;font-size:18px;}
    .wk-stat-val{font-family:'Cinzel',serif;font-size:1.5rem;font-weight:700;line-height:1;margin-bottom:4px;}
    .wk-stat-lbl{font-size:.78rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;}

    .wk-filter-bar{background:var(--surface2);border:1px solid var(--border);border-radius:12px;padding:18px 22px;margin-bottom:24px;}
    .wk-filter-bar .form-control,.wk-filter-bar select{background:var(--surface3);border:1px solid var(--border);color:var(--text);border-radius:8px;font-family:inherit;}
    .wk-filter-bar label{color:var(--text-muted);font-size:.82rem;text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px;display:block;}
    .wk-btn-filter{background:linear-gradient(135deg,var(--gold-dim),var(--gold));color:#0e0e12;border:none;border-radius:8px;padding:10px 22px;font-family:'Cinzel',serif;font-size:.82rem;font-weight:600;letter-spacing:.06em;cursor:pointer;transition:opacity .2s;width:100%;}
    .wk-btn-filter:hover{opacity:.88;}

    .wk-table-card{background:var(--surface2);border:1px solid var(--border);border-radius:16px;overflow:hidden;}
    .wk-table{width:100%;border-collapse:collapse;}
    .wk-table thead th{background:var(--surface3);font-family:'Cinzel',serif;font-size:.72rem;text-transform:uppercase;letter-spacing:.1em;color:var(--gold);padding:14px 16px;white-space:nowrap;border-bottom:1px solid var(--border);}
    .wk-table tbody tr{border-bottom:1px solid rgba(255,255,255,.04);transition:background .15s;}
    .wk-table tbody tr:hover{background:rgba(201,168,76,.04);}
    .wk-table tbody td{padding:14px 16px;vertical-align:middle;font-size:.93rem;}
    .wk-table tbody tr:last-child{border-bottom:none;}

    .wk-badge{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:600;letter-spacing:.03em;white-space:nowrap;}
    .wk-badge-success{background:rgba(46,204,113,.15);color:#2ecc71;}
    .wk-badge-warning{background:rgba(243,156,18,.15);color:#f39c12;}
    .wk-badge-info{background:rgba(52,152,219,.15);color:#3498db;}
    .wk-badge-danger{background:rgba(231,76,60,.15);color:#e74c3c;}
    .wk-badge-purple{background:rgba(155,89,182,.15);color:#9b59b6;}
    .wk-badge-cj{background:rgba(255,165,0,.15);color:#ffa500;}
    .wk-badge-own{background:rgba(100,100,120,.25);color:#aaa;}

    .wk-amount-earn{color:var(--success);font-weight:700;font-family:'Cinzel',serif;}
    .wk-amount-total{color:var(--gold-light);font-weight:600;}
    .wk-amount-plat{color:var(--danger);font-size:.85rem;}

    .wk-avatar{width:34px;height:34px;border-radius:50%;background:var(--surface3);border:1px solid var(--border);display:inline-flex;align-items:center;justify-content:center;font-size:.8rem;color:var(--gold);font-weight:700;overflow:hidden;vertical-align:middle;margin-right:8px;}
    .wk-avatar img{width:100%;height:100%;object-fit:cover;}

    .wk-btn-view{display:inline-flex;align-items:center;gap:5px;background:rgba(201,168,76,.12);border:1px solid var(--border);color:var(--gold);border-radius:7px;padding:5px 12px;font-size:.8rem;font-family:'Cinzel',serif;letter-spacing:.04em;text-decoration:none;transition:background .2s;}
    .wk-btn-view:hover{background:rgba(201,168,76,.22);color:var(--gold-light);text-decoration:none;}

    .wk-empty{text-align:center;padding:60px 20px;color:var(--text-muted);}
    .wk-empty i{font-size:3rem;color:var(--border);display:block;margin-bottom:14px;}

    .wk-pagination .page-link{background:var(--surface3);border-color:var(--border);color:var(--text);}
    .wk-pagination .page-item.active .page-link{background:var(--gold-dim);border-color:var(--gold-dim);color:#0e0e12;}

    @media(max-width:768px){.wk-stats{grid-template-columns:repeat(2,1fr);}.wk-table-responsive{overflow-x:auto;}}
</style>
@endpush

@section('content')
<div class="wk-wrap">

    <div class="wk-page-header">
        <h1><i class="fas fa-box-open mr-2" style="color:var(--gold)"></i>My Product Sales</h1>
        <p>Track your product orders, earnings, and delivery</p>
    </div>

    {{-- Stats --}}
    <div class="wk-stats">
        <div class="wk-stat">
            <div class="wk-stat-icon" style="background:rgba(201,168,76,.12);"><i class="fas fa-shopping-cart" style="color:var(--gold)"></i></div>
            <div class="wk-stat-val" style="color:var(--gold-light)">{{ $totalOrders }}</div>
            <div class="wk-stat-lbl">Total Orders</div>
        </div>
        <div class="wk-stat">
            <div class="wk-stat-icon" style="background:rgba(46,204,113,.12);"><i class="fas fa-hand-holding-usd" style="color:#2ecc71"></i></div>
            <div class="wk-stat-val" style="color:#2ecc71">{{ handlePrice(max(0, $totalEarnings)) }}</div>
            <div class="wk-stat-lbl">Your Earnings</div>
        </div>
        <div class="wk-stat">
            <div class="wk-stat-icon" style="background:rgba(52,152,219,.12);"><i class="fas fa-coins" style="color:#3498db"></i></div>
            <div class="wk-stat-val" style="color:#3498db">{{ handlePrice($totalRevenue) }}</div>
            <div class="wk-stat-lbl">Total Revenue</div>
        </div>
        <div class="wk-stat">
            <div class="wk-stat-icon" style="background:rgba(243,156,18,.12);"><i class="fas fa-clock" style="color:#f39c12"></i></div>
            <div class="wk-stat-val" style="color:#f39c12">{{ $pendingOrders }}</div>
            <div class="wk-stat-lbl">In Transit</div>
        </div>
        <div class="wk-stat">
            <div class="wk-stat-icon" style="background:rgba(46,204,113,.12);"><i class="fas fa-check-circle" style="color:#2ecc71"></i></div>
            <div class="wk-stat-val" style="color:#2ecc71">{{ $deliveredOrders }}</div>
            <div class="wk-stat-lbl">Delivered</div>
        </div>
        <div class="wk-stat">
            <div class="wk-stat-icon" style="background:rgba(231,76,60,.12);"><i class="fas fa-times-circle" style="color:#e74c3c"></i></div>
            <div class="wk-stat-val" style="color:#e74c3c">{{ $canceledOrders }}</div>
            <div class="wk-stat-lbl">Canceled</div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="wk-filter-bar">
        <form method="GET">
            <div class="row align-items-end g-2">
                <div class="col-md-2 col-6">
                    <label>From</label>
                    <input type="date" class="form-control" name="from" value="{{ request('from') }}">
                </div>
                <div class="col-md-2 col-6">
                    <label>To</label>
                    <input type="date" class="form-control" name="to" value="{{ request('to') }}">
                </div>
                <div class="col-md-3 col-6">
                    <label>Status</label>
                    <select class="form-control" name="status">
                        <option value="all">All Status</option>
                        <option value="waiting_delivery" {{ request('status')=='waiting_delivery'?'selected':'' }}>Waiting Delivery</option>
                        <option value="shipped"          {{ request('status')=='shipped'?'selected':'' }}>Shipped</option>
                        <option value="success"          {{ request('status')=='success'?'selected':'' }}>Delivered</option>
                        <option value="canceled"         {{ request('status')=='canceled'?'selected':'' }}>Canceled</option>
                    </select>
                </div>
                <div class="col-md-3 col-6">
                    <label>Product Type</label>
                    <select class="form-control" name="type">
                        <option value="">All Types</option>
                        <option value="physical" {{ request('type')=='physical'?'selected':'' }}>Physical</option>
                        <option value="virtual"  {{ request('type')=='virtual'?'selected':'' }}>Virtual</option>
                    </select>
                </div>
                <div class="col-md-2 col-12">
                    <button type="submit" class="wk-btn-filter"><i class="fas fa-search mr-1"></i> Filter</button>
                </div>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="wk-table-card">
        <div class="wk-table-responsive">
            <table class="wk-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Buyer</th>
                        <th>Product</th>
                        <th>Type</th>
                        <th>Source</th>
                        <th>Qty</th>
                        <th>Total Paid</th>
                        <th>Shipping</th>
                        <th>Platform</th>
                        <th>Your Earning</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        @php
                            $sale    = $order->sale;
                            $product = $order->product;
                            $qty     = (int)($order->quantity ?? 1);

                            $total      = (float)($sale->total_amount         ?? 0);
                            $tax        = (float)($sale->tax                  ?? 0);
                            $shipping   = (float)($sale->product_delivery_fee ?? 0);
                            $discount   = (float)($sale->discount             ?? 0);

                            $commPct    = $product ? (float)$product->getCommission() : 0;
                            $platAmount = round($total * $commPct / 100, 2);
                            $earning    = round($total - $platAmount - $tax - $shipping, 2);

                            $isCj       = (bool)($product->is_cj_product ?? false);
                            $isPhysical = $product ? $product->isPhysical() : false;

                            $statusColors = ['pending'=>'warning','waiting_delivery'=>'info','shipped'=>'purple','success'=>'success','canceled'=>'danger'];
                            $statusLabels = ['pending'=>'Pending','waiting_delivery'=>'Waiting','shipped'=>'Shipped','success'=>'Delivered','canceled'=>'Canceled'];
                            $sc = $statusColors[$order->status] ?? 'info';
                            $sl = $statusLabels[$order->status] ?? ucfirst($order->status);

                            $buyerName    = optional($order->buyer)->full_name ?? 'N/A';
                            $buyerAvatar  = optional($order->buyer)->avatar    ?? null;
                            $buyerInitial = strtoupper(substr($buyerName, 0, 1));

                            $thumb = $product ? ($product->thumbnail ?? null) : null;
                        @endphp
                        <tr>
                            <td style="color:var(--text-muted);font-size:.82rem;">#{{ $order->id }}</td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="wk-avatar">
                                        @if($buyerAvatar)<img src="{{ $buyerAvatar }}" alt="">@else{{ $buyerInitial }}@endif
                                    </div>
                                    <div>
                                        <div style="font-size:.88rem;">{{ $buyerName }}</div>
                                        <div style="font-size:.75rem;color:var(--text-muted)">ID: {{ optional($order->buyer)->id }}</div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    @if($thumb)
                                        <img src="{{ url($thumb) }}" style="width:36px;height:36px;object-fit:cover;border-radius:6px;border:1px solid var(--border);">
                                    @endif
                                    <div>
                                        <div style="font-size:.88rem;font-weight:600;color:var(--gold-light);">{{ optional($product)->title ?? 'Deleted' }}</div>
                                        <div style="font-size:.75rem;color:var(--text-muted)">ID: {{ optional($product)->id }}</div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                @if($isPhysical)
                                    <span class="wk-badge" style="background:rgba(52,73,94,.5);color:#bdc3c7;"><i class="fas fa-box"></i> Physical</span>
                                @else
                                    <span class="wk-badge wk-badge-info"><i class="fas fa-cloud-download-alt"></i> Virtual</span>
                                @endif
                            </td>

                            <td>
                                @if($isCj)
                                    <span class="wk-badge wk-badge-cj"><i class="fas fa-globe"></i> CJ Drop</span>
                                @else
                                    <span class="wk-badge wk-badge-own"><i class="fas fa-store"></i> Own</span>
                                @endif
                            </td>

                            <td style="text-align:center;">{{ $qty }}</td>

                            <td><span class="wk-amount-total">{{ handlePrice($total) }}</span></td>

                            <td>
                                @if($shipping > 0)
                                    <span style="color:#f39c12;">{{ handlePrice($shipping) }}</span>
                                @else
                                    <span style="color:var(--text-muted);font-size:.8rem;">—</span>
                                @endif
                            </td>

                            <td><span class="wk-amount-plat">{{ handlePrice($platAmount) }}</span></td>

                            <td><span class="wk-amount-earn">{{ handlePrice(max(0, $earning)) }}</span></td>

                            <td style="font-size:.8rem;color:var(--text-muted);white-space:nowrap;">
                                {{ dateTimeFormat($order->created_at, 'j M Y') }}
                            </td>

                            <td>
                                <span class="wk-badge wk-badge-{{ $sc }}">{{ $sl }}</span>
                            </td>

                            <td>
                                <a href="{{ url('panel/store/sales/' . $order->id) }}" class="wk-btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="13"><div class="wk-empty"><i class="fas fa-box-open"></i><p>No product sales yet.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
        <div class="p-3 d-flex justify-content-center">
            <nav class="wk-pagination">{{ $orders->appends(request()->input())->links() }}</nav>
        </div>
        @endif
    </div>

</div>
@endsection