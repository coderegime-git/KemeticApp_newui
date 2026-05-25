@extends('admin.layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Book Sale Detail</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">
                <a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
            </div>
            <div class="breadcrumb-item">
                <a href="{{ getAdminPanelUrl() }}/financial/sales">{{ trans('admin/main.sales') }}</a>
            </div>
            <div class="breadcrumb-item">Book Sale #{{ $sale->id }}</div>
        </div>
    </div>

    <div class="section-body">
        <a href="{{ getAdminPanelUrl() }}/financial/sales" class="btn btn-outline-secondary mb-4">
            <i class="fas fa-arrow-left mr-1"></i> Back to Sales
        </a>

        {{-- ─── Top Info Cards ─────────────────────────────────────────────── --}}
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
                                <td><span class="badge badge-info">Book</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Buyer Info --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="fas fa-user-graduate mr-2"></i>Buyer (Student)</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted font-weight-bold" width="45%">Name</td>
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

            {{-- Seller / Wisdom Keeper Info --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-warning text-white">
                        <h6 class="mb-0"><i class="fas fa-chalkboard-teacher mr-2"></i>Instructor (Wisdom Keeper)</h6>
                    </div>
                    <div class="card-body">
                        @php $seller = optional($bookOrder)->seller ?? optional($book)->creator; @endphp
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted font-weight-bold" width="45%">Name</td>
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

        {{-- ─── Book Info + Price Breakdown ───────────────────────────────── --}}
        <div class="row">
            {{-- Book Details --}}
            <div class="col-lg-5 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-book mr-2"></i>Book Information</h6>
                    </div>
                    <div class="card-body">
                        @if($book->image_cover)
                            <div class="text-center mb-3">
                                <img src="{{ url($book->image_cover) }}" alt="{{ $book->title }}"
                                     class="img-thumbnail" style="max-height:150px; object-fit:cover;">
                            </div>
                        @endif
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted font-weight-bold" width="40%">Title</td>
                                <td><strong>{{ $book->title }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted font-weight-bold">Book ID</td>
                                <td><span class="text-primary font-weight-bold">#{{ $book->id }}</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted font-weight-bold">Book Type</td>
                                <td>
                                    @if($bookType === 'print')
                                        <span class="badge badge-dark"><i class="fas fa-print mr-1"></i>Print</span>
                                    @elseif($bookType === 'ebook')
                                        <span class="badge badge-info"><i class="fas fa-tablet-alt mr-1"></i>E-Book</span>
                                    @elseif($bookType === 'audio')
                                        <span class="badge badge-secondary"><i class="fas fa-headphones mr-1"></i>Audio Book</span>
                                    @else
                                        <span class="badge badge-light">{{ ucfirst($bookType) }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted font-weight-bold">Page Count</td>
                                <td>{{ $book->page_count ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted font-weight-bold">Quantity</td>
                                <td>{{ optional($bookOrder)->quantity ?? 1 }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Price Breakdown --}}
            <div class="col-lg-7 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-tags mr-2"></i>Price Breakdown</h6>
                    </div>
                    <div class="card-body p-0">

                        {{--
                            FIX: $bookPrice passed from controller equals $sale->amount (the total paid),
                            NOT the book's unit price. Use $book->price directly as the true base price.
                            $quantity comes from bookOrder or defaults to 1.
                        --}}
                        @php
                            $qty               = optional($bookOrder)->quantity ?? 1;
                            $unitPrice         = $bookPrice;      // from controller — already getRawOriginal('price')
                            $unitPrintPrice    = $printPrice;     // from controller — getRawOriginal('print_price')
                            $unitShippingPrice = $shippingPrice;  // from controller — getRawOriginal('shipping_price')
                        @endphp

                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Component</th>
                                    <th class="text-right">Amount</th>
                                    <th class="text-center">Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Book Base Price --}}
                                <tr>
                                    <td>
                                        <i class="fas fa-book text-primary mr-2"></i>
                                        <strong>Book Price</strong>
                                    </td>
                                    <td class="text-right font-weight-bold">
                                        {{-- FIX: was {{ handlePrice($bookPrice) }} which showed totalAmount.
                                             Now uses $book->price (real unit price × qty). --}}
                                        {{ handlePrice($unitPrice * $qty) }}
                                    </td>
                                    <td class="text-center text-muted small">Base selling price</td>
                                </tr>

                                {{-- Print Price — only for print type --}}
                                @if($bookType === 'print' && $printPrice > 0)
                                <tr class="table-secondary">
                                    <td>
                                        <i class="fas fa-print text-dark mr-2"></i>
                                        Print Price
                                    </td>
                                    <td class="text-right text-danger font-weight-bold">
                                        - {{ handlePrice($printPrice) }}
                                    </td>
                                    <td class="text-center text-muted small">Physical print cost</td>
                                </tr>
                                @endif

                                {{-- Shipping (if applicable) --}}
                                @if($bookType === 'print' && $shippingPrice > 0)
                                <tr>
                                    <td>
                                        <i class="fas fa-shipping-fast text-secondary mr-2"></i>
                                        Shipping Price
                                    </td>
                                    <td class="text-right text-danger font-weight-bold">
                                        - {{ handlePrice($shippingPrice) }}
                                    </td>
                                    <td class="text-center text-muted small">Delivery charge</td>
                                </tr>
                                @endif

                                {{-- Discount --}}
                                @if($discount > 0)
                                <tr>
                                    <td>
                                        <i class="fas fa-percent text-warning mr-2"></i>
                                        Discount Applied
                                    </td>
                                    <td class="text-right text-warning font-weight-bold">
                                        - {{ handlePrice($discount) }}
                                    </td>
                                    <td class="text-center text-muted small">Coupon / promo</td>
                                </tr>
                                @endif

                                {{-- Tax --}}
                                @if($tax > 0)
                                <tr>
                                    <td>
                                        <i class="fas fa-landmark text-secondary mr-2"></i>
                                        Tax
                                    </td>
                                    <td class="text-right font-weight-bold">
                                        {{ handlePrice($tax) }}
                                    </td>
                                    <td class="text-center text-muted small">{{ $book->getTax() }}%</td>
                                </tr>
                                @endif

                                {{-- Divider --}}
                                <tr class="thead-light">
                                    <td colspan="3" class="py-1 px-3">
                                        <small class="text-muted text-uppercase font-weight-bold">Revenue Split</small>
                                    </td>
                                </tr>

                                {{-- Platform Share --}}
                                <tr class="table-danger">
                                    <td>
                                        <i class="fas fa-server text-danger mr-2"></i>
                                        <strong>Platform Earnings</strong>
                                    </td>
                                    <td class="text-right font-weight-bold text-danger">
                                        {{ handlePrice($platformAmount) }}
                                    </td>
                                    <td class="text-center text-muted small">
                                        {{ $commissionPct }}% commission
                                    </td>
                                </tr>

                                {{-- Wisdom Keeper Earning --}}
                                <tr class="table-success">
                                    <td>
                                        <i class="fas fa-hand-holding-usd text-success mr-2"></i>
                                        <strong>Wisdom Keeper Earnings</strong>
                                    </td>
                                    <td class="text-right font-weight-bold text-success">
                                        {{ handlePrice($earningAmount) }}
                                    </td>
                                    <td class="text-center text-muted small">After platform cut & tax</td>
                                </tr>
                            </tbody>
                            <tfoot class="thead-dark">
                                <tr>
                                    <th><i class="fas fa-equals mr-2"></i>Total Paid</th>
                                    <th class="text-right">{{ handlePrice($totalAmount) }}</th>
                                    <th class="text-center">—</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── Delivery Status (Print books only) ────────────────────────── --}}
        @if($bookType === 'print' && !empty($bookOrder))
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-truck mr-2"></i>Delivery Status</h6>
                    </div>
                    <div class="card-body">
                        {{-- Step Progress --}}
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                            @foreach($deliveryStatuses as $statusKey => $statusInfo)
                                @php
                                    $isActive = $currentStatus === $statusKey;
                                    $statusOrder = array_keys($deliveryStatuses);
                                    $currentIdx = array_search($currentStatus, $statusOrder);
                                    $thisIdx = array_search($statusKey, $statusOrder);
                                    $isPast = $thisIdx <= $currentIdx;
                                @endphp
                                <div class="text-center" style="flex:1; min-width:80px;">
                                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center
                                        {{ $isPast ? 'bg-'.$statusInfo['color'].' text-white' : 'bg-light text-muted' }}"
                                        style="width:40px;height:40px;font-size:14px;border:2px solid #dee2e6;">
                                        @if($statusKey === 'pending') <i class="fas fa-clock"></i>
                                        @elseif($statusKey === 'waiting_delivery') <i class="fas fa-box"></i>
                                        @elseif($statusKey === 'shipped') <i class="fas fa-shipping-fast"></i>
                                        @elseif($statusKey === 'success') <i class="fas fa-check-double"></i>
                                        @elseif($statusKey === 'canceled') <i class="fas fa-times"></i>
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

                        <table class="table table-sm table-borderless mt-3 mb-0" style="max-width:400px;">
                            <tr>
                                <td class="text-muted font-weight-bold" width="40%">Current Status</td>
                                <td>
                                    <span class="badge badge-{{ $deliveryStatuses[$currentStatus]['color'] ?? 'secondary' }}">
                                        {{ $deliveryStatuses[$currentStatus]['label'] ?? ucfirst($currentStatus) }}
                                    </span>
                                </td>
                            </tr>
                            @if(!empty($bookOrder->tracking_code))
                            <tr>
                                <td class="text-muted font-weight-bold">Tracking Code</td>
                                <td><code>{{ $bookOrder->tracking_code }}</code></td>
                            </tr>
                            @endif
                            @if(!empty($bookOrder->printjob_id))
                            <tr>
                                <td class="text-muted font-weight-bold">Print Job ID</td>
                                <td><code>{{ $bookOrder->printjob_id }}</code></td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- ─── Summary Banner ─────────────────────────────────────────────── --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0" style="background: linear-gradient(135deg,#1a1a2e 0%,#16213e 50%,#0f3460 100%); color:#fff;">
                    <div class="card-body py-4">
                        <div class="row text-center">
                            <div class="col-md-3 col-6 mb-3 mb-md-0">
                                <div class="text-white-50 small mb-1">Total Paid by Buyer</div>
                                <div class="h4 font-weight-bold text-white">{{ handlePrice($totalAmount) }}</div>
                            </div>
                            <div class="col-md-3 col-6 mb-3 mb-md-0">
                                <div class="text-white-50 small mb-1">Platform Earnings</div>
                                <div class="h4 font-weight-bold" style="color:#ff6b6b;">{{ handlePrice($platformAmount) }}</div>
                                <div class="text-white-50 small">{{ $commissionPct }}% commission</div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="text-white-50 small mb-1">Wisdom Keeper Earnings</div>
                                <div class="h4 font-weight-bold" style="color:#6bffb8;">{{ handlePrice($earningAmount) }}</div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="text-white-50 small mb-1">Tax Collected</div>
                                <div class="h4 font-weight-bold" style="color:#ffd93d;">{{ handlePrice($tax) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection