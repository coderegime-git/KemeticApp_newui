@extends('admin.layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Product Sale Detail</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">
                <a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
            </div>
            <div class="breadcrumb-item">
                <a href="{{ getAdminPanelUrl() }}/financial/sales">{{ trans('admin/main.sales') }}</a>
            </div>
            <div class="breadcrumb-item">Product Sale #{{ $sale->id }}</div>
        </div>
    </div>

    <div class="section-body">
        <a href="{{ getAdminPanelUrl() }}/financial/sales" class="btn btn-outline-secondary mb-4">
            <i class="fas fa-arrow-left mr-1"></i> Back to Sales
        </a>

        {{-- ─── Top Type Badges ─────────────────────────────────────────────── --}}
        <div class="mb-3">
            @if($isCj)
                <span class="badge badge-pill badge-warning px-3 py-2 mr-2" style="font-size:13px;">
                    <i class="fas fa-globe mr-1"></i> CJ Dropshipping Product
                </span>
            @else
                <span class="badge badge-pill badge-secondary px-3 py-2 mr-2" style="font-size:13px;">
                    <i class="fas fa-store mr-1"></i> Own Platform Product
                </span>
            @endif

            @if($isPhysical)
                <span class="badge badge-pill badge-dark px-3 py-2" style="font-size:13px;">
                    <i class="fas fa-box mr-1"></i> Physical Product
                </span>
            @else
                <span class="badge badge-pill badge-info px-3 py-2" style="font-size:13px;">
                    <i class="fas fa-cloud-download-alt mr-1"></i> Virtual / Digital Product
                </span>
            @endif
        </div>

        {{-- ─── Info Cards Row ──────────────────────────────────────────────── --}}
        <div class="row">

            {{-- Sale Info --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-receipt mr-2"></i>Sale Information</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted font-weight-bold" width="45%">Sale ID</td>
                                <td><span class="badge badge-primary">#{{ $sale->id }}</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted font-weight-bold">Date</td>
                                <td>{{ dateTimeFormat($sale->created_at, 'j F Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted font-weight-bold">Status</td>
                                <td>
                                    @if(!empty($sale->refund_at))
                                        <span class="badge badge-warning">Refunded</span>
                                    @elseif(!$sale->access_to_purchased_item)
                                        <span class="badge badge-danger">Blocked</span>
                                    @else
                                        <span class="badge badge-success">Successful</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted font-weight-bold">Payment</td>
                                <td>{{ ucfirst($sale->payment_method ?? 'N/A') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted font-weight-bold">Sale Type</td>
                                <td><span class="badge badge-secondary">Product</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Buyer --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="fas fa-user mr-2"></i>Buyer</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted font-weight-bold" width="40%">Name</td>
                                <td>{{ optional($sale->buyer)->full_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted font-weight-bold">ID</td>
                                <td>
                                    <span class="text-primary font-weight-bold">
                                        {{ optional($sale->buyer)->id ?? 'N/A' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Seller --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-warning text-white">
                        <h6 class="mb-0"><i class="fas fa-chalkboard-teacher mr-2"></i>Seller / Instructor</h6>
                    </div>
                    <div class="card-body">
                        @php $seller = optional($productOrder)->seller ?? optional($product)->creator; @endphp
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted font-weight-bold" width="40%">Name</td>
                                <td>{{ optional($seller)->full_name ?? 'Admin' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted font-weight-bold">ID</td>
                                <td>
                                    <span class="text-primary font-weight-bold">
                                        {{ optional($seller)->id ?? '---' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        {{-- ─── Product Info + Price Breakdown ────────────────────────────── --}}
        <div class="row">

            {{-- Product Details --}}
            <div class="col-lg-5 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-box-open mr-2"></i>Product Information</h6>
                    </div>
                    <div class="card-body">
                        @php $thumb = $product->thumbnail ?? null; @endphp
                        @if($thumb)
                            <div class="text-center mb-3">
                                <img src="{{ url($thumb) }}" alt="{{ $product->title }}"
                                     class="img-thumbnail" style="max-height:140px;object-fit:cover;">
                            </div>
                        @endif
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted font-weight-bold" width="40%">Title</td>
                                <td><strong>{{ $product->title }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted font-weight-bold">Product ID</td>
                                <td><span class="text-primary font-weight-bold">#{{ $product->id }}</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted font-weight-bold">Type</td>
                                <td>{{ ucfirst($product->type) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted font-weight-bold">Category</td>
                                <td>{{ optional($product->category)->title ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted font-weight-bold">Quantity</td>
                                <td>{{ optional($productOrder)->quantity ?? 1 }}</td>
                            </tr>

                            {{-- CJ Variant Details --}}
                            @if($isCj && $cjVariant)
                                <tr><td colspan="2"><hr class="my-2"></td></tr>
                                <tr>
                                    <td class="text-muted font-weight-bold">CJ Variant</td>
                                    <td>{{ $cjVariant->variant_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted font-weight-bold">Variant SKU</td>
                                    <td><code>{{ $cjVariant->variant_sku ?? 'N/A' }}</code></td>
                                </tr>
                                <tr>
                                    <td class="text-muted font-weight-bold">CJ PID</td>
                                    <td><code>{{ $cjVariant->cj_pid ?? 'N/A' }}</code></td>
                                </tr>
                                @if($cjVariant->variant_image)
                                <tr>
                                    <td class="text-muted font-weight-bold">Variant Image</td>
                                    <td><img src="{{ $cjVariant->variant_image }}" style="height:48px;border-radius:4px;"></td>
                                </tr>
                                @endif
                            @elseif($isCj)
                                <tr>
                                    <td colspan="2">
                                        <span class="badge badge-light">CJ product — no variant matched</span>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            {{-- ─── Price Breakdown ─────────────────────────────────────────── --}}
            <div class="col-lg-7 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center">
                        <h6 class="mb-0"><i class="fas fa-tags mr-2"></i>Price Breakdown</h6>
                        @php $qty = optional($productOrder)->quantity ?? 1; @endphp
                        @if($qty > 1)
                            <span class="ml-auto text-muted small">× {{ $qty }} qty</span>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Component</th>
                                    <th class="text-right">Unit Price</th>
                                    <th class="text-right">Amount</th>
                                    <th class="text-center">Note</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if($isCj)
                                {{-- ── CJ Dropshipping: 4-row granular breakdown ── --}}
                                @php
                                    $trueCjBasePrice = max(0,
                                        ($product->price          ?? 0)
                                        - ($product->cj_shipping_price ?? 0)
                                        - ($product->cj_your_price     ?? 0)
                                        - ($product->platform_price    ?? 0)
                                    );
                                @endphp

                                {{-- 1. CJ Base Price --}}
                                <tr>
                                    <td>
                                        <i class="fas fa-tag text-primary mr-2"></i>
                                        <strong>Base Price</strong>
                                        <div class="small text-muted font-italic">Calculated CJ Base Price</div>
                                    </td>
                                    <td class="text-right font-weight-bold">{{ handlePrice($trueCjBasePrice) }}</td>
                                    <td class="text-right font-weight-bold">{{ handlePrice($trueCjBasePrice * $qty) }}</td>
                                    <td class="text-center text-muted small">CJ base cost</td>
                                </tr>

                                {{-- 2. CJ Shipping Price --}}
                                <tr>
                                    <td>
                                        <i class="fas fa-shipping-fast text-warning mr-2"></i>
                                        <strong>CJ Shipping Price</strong>
                                        <div class="small text-muted font-italic">products.cj_shipping_price</div>
                                    </td>
                                    <td class="text-right font-weight-bold text-warning">{{ handlePrice($product->cj_shipping_price ?? 0) }}</td>
                                    <td class="text-right font-weight-bold text-warning">{{ handlePrice(($product->cj_shipping_price ?? 0) * $qty) }}</td>
                                    <td class="text-center text-muted small">CJ delivery cost</td>
                                </tr>

                                {{-- 3. CJ Your Price --}}
                                <tr class="table-success">
                                    <td>
                                        <i class="fas fa-hand-holding-usd text-success mr-2"></i>
                                        <strong>CJ Your Price</strong>
                                        <div class="small text-muted font-italic">products.cj_your_price</div>
                                    </td>
                                    <td class="text-right font-weight-bold text-success">{{ handlePrice($product->cj_your_price ?? 0) }}</td>
                                    <td class="text-right font-weight-bold text-success">{{ handlePrice(($product->cj_your_price ?? 0) * $qty) }}</td>
                                    <td class="text-center text-muted small">Seller markup</td>
                                </tr>

                                {{-- 4. CJ Platform Price --}}
                                <tr class="table-danger">
                                    <td>
                                        <i class="fas fa-server text-danger mr-2"></i>
                                        <strong>Platform Price</strong>
                                        <div class="small text-muted font-italic">products.platform_price</div>
                                    </td>
                                    <td class="text-right font-weight-bold text-danger">{{ handlePrice($product->platform_price ?? 0) }}</td>
                                    <td class="text-right font-weight-bold text-danger">{{ handlePrice(($product->platform_price ?? 0) * $qty) }}</td>
                                    <td class="text-center text-muted small">Platform fee</td>
                                </tr>

                                @else
                                {{-- ── Own Platform: 3-row breakdown ── --}}

                                {{-- 1. Base Price --}}
                                <tr>
                                    <td>
                                        <i class="fas fa-tag text-primary mr-2"></i>
                                        <strong>Base Price</strong>
                                        <div class="small text-muted font-italic">products.price</div>
                                    </td>
                                    <td class="text-right font-weight-bold">{{ handlePrice($product->price ?? 0) }}</td>
                                    <td class="text-right font-weight-bold">{{ handlePrice(($product->price ?? 0) * $qty) }}</td>
                                    <td class="text-center text-muted small">Listed price</td>
                                </tr>

                                {{-- Price after discount (only if different) --}}
                                @if($discountedBase != $basePrice)
                                <tr class="table-warning">
                                    <td>
                                        <i class="fas fa-percentage text-warning mr-2"></i>
                                        Price after Discount
                                    </td>
                                    <td class="text-right text-warning font-weight-bold">{{ handlePrice($discountedBase) }}</td>
                                    <td class="text-right text-warning font-weight-bold">{{ handlePrice($discountedBase * $qty) }}</td>
                                    <td class="text-center text-muted small">After product discount</td>
                                </tr>
                                @endif

                                {{-- 2. Earning Price --}}
                                <tr class="table-success">
                                    <td>
                                        <i class="fas fa-hand-holding-usd text-success mr-2"></i>
                                        <strong>Earning Price</strong>
                                        <div class="small text-muted font-italic">products.earning_price</div>
                                    </td>
                                    <td class="text-right font-weight-bold text-success">{{ handlePrice($product->earning_price ?? 0) }}</td>
                                    <td class="text-right font-weight-bold text-success">{{ handlePrice(($product->earning_price ?? 0) * $qty) }}</td>
                                    <td class="text-center text-muted small">Seller earning</td>
                                </tr>

                                {{-- 3. Own Platform Price --}}
                                <tr class="table-danger">
                                    <td>
                                        <i class="fas fa-server text-danger mr-2"></i>
                                        <strong>Own Platform Price</strong>
                                        <div class="small text-muted font-italic">products.own_platform_price</div>
                                    </td>
                                    <td class="text-right font-weight-bold text-danger">− {{ handlePrice($product->own_platform_price ?? 0) }}</td>
                                    <td class="text-right font-weight-bold text-danger">− {{ handlePrice(($product->own_platform_price ?? 0) * $qty) }}</td>
                                    <td class="text-center text-muted small">Platform fee</td>
                                </tr>

                                @endif

                                {{-- ── Coupon / Discount ── --}}
                                @if($discount > 0)
                                <tr>
                                    <td>
                                        <i class="fas fa-percent text-warning mr-2"></i>
                                        Coupon / Discount
                                    </td>
                                    <td class="text-right text-warning font-weight-bold" colspan="2">− {{ handlePrice($discount) }}</td>
                                    <td class="text-center text-muted small">Applied coupon</td>
                                </tr>
                                @endif

                                {{-- ── Tax ── --}}
                                @if($tax > 0)
                                <tr>
                                    <td>
                                        <i class="fas fa-landmark text-secondary mr-2"></i>
                                        Tax
                                    </td>
                                    <td class="text-right font-weight-bold" colspan="2">+ {{ handlePrice($tax) }}</td>
                                    <td class="text-center text-muted small">{{ $product->getTax() }}%</td>
                                </tr>
                                @endif

                                {{-- ── Revenue Split ── --}}
                                <tr class="thead-light">
                                    <td colspan="4" class="py-1 px-3">
                                        <small class="text-muted text-uppercase font-weight-bold">Revenue Split</small>
                                    </td>
                                </tr>

                                @if($isCj)
                                {{-- CJ: Shipping = cj_shipping_price | Seller = cj_your_price | Platform = platform_price --}}
                                <tr>
                                    <td>
                                        <i class="fas fa-shipping-fast text-warning mr-2"></i>
                                        <strong>Shipping (CJ)</strong>
                                        <div class="small text-muted font-italic">products.cj_shipping_price</div>
                                    </td>
                                    <td class="text-right font-weight-bold text-warning" colspan="2">{{ handlePrice($product->cj_shipping_price ?? 0) }}</td>
                                    <td class="text-center text-muted small">CJ delivery cost</td>
                                </tr>
                                <tr class="table-success">
                                    <td>
                                        <i class="fas fa-hand-holding-usd text-success mr-2"></i>
                                        <strong>Wisdom Keeper Earnings</strong>
                                        <div class="small text-muted font-italic">products.cj_your_price</div>
                                    </td>
                                    <td class="text-right font-weight-bold text-success" colspan="2">{{ handlePrice($product->cj_your_price ?? 0) }}</td>
                                    <td class="text-center text-muted small">Seller earning</td>
                                </tr>
                                <tr class="table-danger">
                                    <td>
                                        <i class="fas fa-server text-danger mr-2"></i>
                                        <strong>Platform Earnings</strong>
                                        <div class="small text-muted font-italic">products.platform_price</div>
                                    </td>
                                    <td class="text-right font-weight-bold text-danger" colspan="2">{{ handlePrice($product->platform_price ?? 0) }}</td>
                                    <td class="text-center text-muted small">Platform fee</td>
                                </tr>

                                @else
                                {{-- Own Platform: Shipping = product_delivery_fee | Seller = earning_price | Platform = own_platform_price --}}
                                @if($isPhysical)
                                <tr>
                                    <td>
                                        <i class="fas fa-shipping-fast text-warning mr-2"></i>
                                        <strong>Shipping Fee</strong>
                                        <div class="small text-muted font-italic">sales.product_delivery_fee</div>
                                    </td>
                                    <td class="text-right font-weight-bold text-warning" colspan="2">{{ handlePrice($shippingFee) }}</td>
                                    <td class="text-center text-muted small">Delivery charge</td>
                                </tr>
                                @endif
                                <tr class="table-success">
                                    <td>
                                        <i class="fas fa-hand-holding-usd text-success mr-2"></i>
                                        <strong>Wisdom Keeper Earnings</strong>
                                        <div class="small text-muted font-italic">products.earning_price</div>
                                    </td>
                                    <td class="text-right font-weight-bold text-success" colspan="2">{{ handlePrice($product->earning_price ?? 0) }}</td>
                                    <td class="text-center text-muted small">Seller earning</td>
                                </tr>
                                <tr class="table-danger">
                                    <td>
                                        <i class="fas fa-server text-danger mr-2"></i>
                                        <strong>Platform Earnings</strong>
                                        <div class="small text-muted font-italic">products.own_platform_price</div>
                                    </td>
                                    <td class="text-right font-weight-bold text-danger" colspan="2">{{ handlePrice($product->own_platform_price ?? 0) }}</td>
                                    <td class="text-center text-muted small">Platform fee</td>
                                </tr>
                                @endif

                            </tbody>
                            <tfoot class="thead-dark">
                                <tr>
                                    <th><i class="fas fa-equals mr-2"></i>Total Paid</th>
                                    <th class="text-right" colspan="2">{{ handlePrice($totalAmount) }}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        {{-- ─── Delivery Status (physical products only) ────────────────────── --}}
        @if($isPhysical && !empty($productOrder))
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-truck mr-2"></i>Delivery Status</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                            @foreach($deliveryStatuses as $statusKey => $statusInfo)
                                @php
                                    $isActive   = $currentStatus === $statusKey;
                                    $statusOrder = array_keys($deliveryStatuses);
                                    $currentIdx  = array_search($currentStatus, $statusOrder);
                                    $thisIdx     = array_search($statusKey, $statusOrder);
                                    $isPast      = $thisIdx <= $currentIdx;
                                @endphp
                                <div class="text-center" style="flex:1; min-width:80px;">
                                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center
                                        {{ $isPast ? 'bg-'.$statusInfo['color'].' text-white' : 'bg-light text-muted' }}"
                                        style="width:40px;height:40px;font-size:14px;border:2px solid #dee2e6;">
                                        @if($statusKey === 'pending')          <i class="fas fa-clock"></i>
                                        @elseif($statusKey === 'waiting_delivery') <i class="fas fa-box"></i>
                                        @elseif($statusKey === 'shipped')      <i class="fas fa-shipping-fast"></i>
                                        @elseif($statusKey === 'success')      <i class="fas fa-check-double"></i>
                                        @elseif($statusKey === 'canceled')     <i class="fas fa-times"></i>
                                        @endif
                                    </div>
                                    <div class="mt-1 small {{ $isActive ? 'font-weight-bold text-'.$statusInfo['color'] : 'text-muted' }}">
                                        {{ $statusInfo['label'] }}
                                    </div>
                                </div>
                                @if(!$loop->last)
                                    <div style="flex:1;height:2px;background:{{ $isPast ? '#28a745' : '#dee2e6' }};margin-bottom:20px;"></div>
                                @endif
                            @endforeach
                        </div>

                        <table class="table table-sm table-borderless mt-3 mb-0" style="max-width:450px;">
                            <tr>
                                <td class="text-muted font-weight-bold" width="40%">Current Status</td>
                                <td>
                                    <span class="badge badge-{{ $deliveryStatuses[$currentStatus]['color'] ?? 'secondary' }}">
                                        {{ $deliveryStatuses[$currentStatus]['label'] ?? ucfirst($currentStatus) }}
                                    </span>
                                </td>
                            </tr>
                            @if(!empty($productOrder->tracking_code))
                            <tr>
                                <td class="text-muted font-weight-bold">Tracking Code</td>
                                <td><code>{{ $productOrder->tracking_code }}</code></td>
                            </tr>
                            @endif
                            @if($isCj && !empty($productOrder->cj_order_id))
                            <tr>
                                <td class="text-muted font-weight-bold">CJ Order ID</td>
                                <td><code>{{ $productOrder->cj_order_id }}</code></td>
                            </tr>
                            @endif
                            @if($isCj && !empty($productOrder->cj_tracking_number))
                            <tr>
                                <td class="text-muted font-weight-bold">CJ Tracking #</td>
                                <td><code>{{ $productOrder->cj_tracking_number }}</code></td>
                            </tr>
                            @endif
                            @if(!empty($productOrder->message_to_seller))
                            <tr>
                                <td class="text-muted font-weight-bold">Note to Seller</td>
                                <td>{{ $productOrder->message_to_seller }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- ─── CJ Specifications (if CJ) ──────────────────────────────────── --}}
        @if($isCj && !empty($cjSpecs))
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-list-alt mr-2"></i>CJ Dropshipping Order Specifications</h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead class="thead-light">
                                <tr><th>Key</th><th>Value</th></tr>
                            </thead>
                            <tbody>
                                @foreach($cjSpecs as $key => $val)
                                <tr>
                                    <td class="text-muted font-weight-bold">{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                                    <td>{{ is_array($val) ? json_encode($val) : $val }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- ─── Summary Banner ─────────────────────────────────────────────── --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0" style="background:linear-gradient(135deg,#1a1a2e 0%,#16213e 50%,#0f3460 100%);color:#fff;">
                    <div class="card-body py-4">
                        <div class="row text-center">

                            {{-- Total Paid — always --}}
                            <div class="col-md-3 col-6 mb-3 mb-md-0">
                                <div class="text-white-50 small mb-1">Total Paid by Buyer</div>
                                <div class="h4 font-weight-bold text-white">{{ handlePrice($totalAmount) }}</div>
                            </div>

                            @if($isCj)
                            {{-- CJ: cj_shipping_price | cj_your_price | platform_price --}}
                            <div class="col-md-3 col-6 mb-3 mb-md-0">
                                <div class="text-white-50 small mb-1">CJ Shipping</div>
                                <div class="h4 font-weight-bold" style="color:#ffd93d;">{{ handlePrice($product->cj_shipping_price ?? 0) }}</div>
                            </div>
                            <div class="col-md-3 col-6 mb-3 mb-md-0">
                                <div class="text-white-50 small mb-1">Wisdom Keeper Earnings</div>
                                <div class="h4 font-weight-bold" style="color:#6bffb8;">{{ handlePrice($product->cj_your_price ?? 0) }}</div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="text-white-50 small mb-1">Platform Earnings</div>
                                <div class="h4 font-weight-bold" style="color:#ff6b6b;">{{ handlePrice($product->platform_price ?? 0) }}</div>
                            </div>

                            @else
                            {{-- Own Platform: product_delivery_fee | earning_price | own_platform_price --}}
                            <div class="col-md-3 col-6 mb-3 mb-md-0">
                                <div class="text-white-50 small mb-1">
                                    @if($isPhysical) Shipping Fee @else Tax @endif
                                </div>
                                <div class="h4 font-weight-bold" style="color:#ffd93d;">
                                    @if($isPhysical) {{ handlePrice($shippingFee) }} @else {{ handlePrice($tax) }} @endif
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3 mb-md-0">
                                <div class="text-white-50 small mb-1">Wisdom Keeper Earnings</div>
                                <div class="h4 font-weight-bold" style="color:#6bffb8;">{{ handlePrice($product->earning_price ?? 0) }}</div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="text-white-50 small mb-1">Platform Earnings</div>
                                <div class="h4 font-weight-bold" style="color:#ff6b6b;">{{ handlePrice($product->own_platform_price ?? 0) }}</div>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection