@extends('web.default.layouts.newapp')

<style>
/* ===== Kemetic Stats Section ===== */
.kemetic-stats-section {
    margin-top: 35px;
}

.kemetic-title {
    font-weight: 700;
    letter-spacing: 0.5px;
    color: #d4af37;
}

/* Wrapper */
.kemetic-stats-wrapper {
    background: #0f0f0f;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.35);
}

/* Card */
.kemetic-stat-card {
    background: linear-gradient(180deg, #141414, #0c0c0c);
    border-radius: 14px;
    padding: 25px 15px;
    text-align: center;
    height: 100%;
    transition: all 0.35s ease;
    border: 1px solid rgba(212, 175, 55, 0.15);
}

.kemetic-stat-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 30px rgba(212, 175, 55, 0.25);
    border-color: rgba(212, 175, 55, 0.4);
}

/* Icon */
.icon-box {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    background: rgba(212, 175, 55, 0.15);
}

.icon-box img {
    width: 32px;
    height: 32px;
    filter: brightness(0) invert(1);
}

/* Value */
.stat-value {
    display: block;
    font-size: 30px;
    font-weight: 700;
    color: #d4af37;
    margin-bottom: 4px;
}

/* Label */
.stat-label {
    font-size: 14px;
    font-weight: 500;
    color: #bdbdbd;
    letter-spacing: 0.4px;
}

/* Responsive */
@media (max-width: 768px) {
    .kemetic-stats-wrapper {
        padding: 20px;
    }

    .stat-value {
        font-size: 24px;
    }
}
/* ===== My Products (Kemetic) ===== */

.kemetic-products {
    --gold: #d4af37;
}

.kemetic-product-card {
    background: linear-gradient(180deg, #121212, #0b0b0b);
    border-radius: 18px;
    padding: 18px;
    gap: 18px;
    border: 1px solid rgba(212,175,55,.2);
    transition: .35s;
}

.kemetic-product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(212,175,55,.25);
}

.kemetic-image-box {
    width: 220px;
    border-radius: 14px;
    overflow: hidden;
    position: relative;
}

.kemetic-image-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.kemetic-badges {
    position: absolute;
    top: 10px;
    left: 10px;
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.k-badge {
    font-size: 11px;
    padding: 4px 10px;
    border-radius: 20px;
    font-weight: 600;
    text-transform: uppercase;
}

.k-badge.success { background:#1f6f43; color:#fff; }
.k-badge.warning { background:#8a6d1d; color:#fff; }
.k-badge.danger  { background:#7a1f1f; color:#fff; }
.k-badge.info    { background:#1e3a5f; color:#fff; }

.kemetic-product-title {
    font-size: 16px;
    font-weight: 700;
    color: #fff;
}

.kemetic-more-btn {
    background: transparent;
    border: none;
    font-size: 22px;
    color: var(--gold);
    cursor: pointer;
}

.kemetic-price .price {
    color: var(--gold);
    font-size: 18px;
    font-weight: 700;
}

.kemetic-price .old-price {
    margin-left: 10px;
    color: #999;
    text-decoration: line-through;
}

.kemetic-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-top: 20px;
}

.kemetic-stats div span {
    font-size: 12px;
    color: #aaa;
}

.kemetic-stats div strong {
    display: block;
    color: #fff;
    font-weight: 600;
}

/* Mobile */
@media (max-width: 768px) {
    .kemetic-product-card {
        flex-direction: column;
    }

    .kemetic-image-box {
        width: 100%;
        height: 200px;
    }
}


</style>


@section('content')
    <section class="kemetic-stats-section">
    <h2 class="section-title kemetic-title">
        {{ trans('update.products_statistics') }}
    </h2>

    <div class="kemetic-stats-wrapper mt-25">
        <div class="row">

            <!-- Physical Products -->
            <div class="col-6 col-md-3 mt-20">
                <div class="kemetic-stat-card">
                    <!-- <div class="icon-box gold"> -->
                       <img src="/assets/default/img/activity/webinars.svg" width="64" height="64" alt="">
                    <!-- </div> -->
                    <strong class="stat-value">{{ $physicalProducts }}</strong>
                    <span class="stat-label">{{ trans('update.physical_products') }}</span>
                </div>
            </div>

            <!-- Virtual Products -->
            <div class="col-6 col-md-3 mt-20">
                <div class="kemetic-stat-card">
                    <!-- <div class="icon-box gold"> -->
                        <img src="/assets/default/img/activity/hours.svg" alt="">
                    <!-- </div> -->
                    <strong class="stat-value">{{ $virtualProducts }}</strong>
                    <span class="stat-label">{{ trans('update.virtual_products') }}</span>
                </div>
            </div>

            <!-- Physical Sales -->
            <div class="col-6 col-md-3 mt-20">
                <div class="kemetic-stat-card">
                    <!-- <div class="icon-box gold"> -->
                        <img src="/assets/default/img/activity/sales.svg" alt="">
                    <!-- </div> -->
                    <strong class="stat-value">
                        {{ !empty($physicalSales) ? handlePrice($physicalSales) : 0 }}
                    </strong>
                    <span class="stat-label">{{ trans('update.physical_sales') }}</span>
                </div>
            </div>

            <!-- Virtual Sales -->
            <div class="col-6 col-md-3 mt-20">
                <div class="kemetic-stat-card">
                    <!-- <div class="icon-box gold"> -->
                        <img src="/assets/default/img/activity/download-sales.svg" alt="">
                    <!-- </div> -->
                    <strong class="stat-value">
                        {{ !empty($virtualSales) ? handlePrice($virtualSales) : 0 }}
                    </strong>
                    <span class="stat-label">{{ trans('update.virtual_sales') }}</span>
                </div>
            </div>

        </div>
    </div>
</section>


   <section class="mt-25 kemetic-products">
    <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
        <h2 class="section-title kemetic-title">
            {{ trans('update.my_products') }}
        </h2>
    </div>

    @if(!empty($products) and !$products->isEmpty())
        @foreach($products as $product)

            @php $hasDiscount = $product->getActiveDiscount(); @endphp

            <div class="row mt-25">
                <div class="col-12">
                    <div class="kemetic-product-card d-flex">

                        <!-- Image -->
                        <div class="kemetic-image-box">
                            <img src="{{ $product->thumbnail }}" alt="">

                            <div class="kemetic-badges">
                                @if($product->ordering and !empty($product->inventory) and $product->getAvailability() < 1)
                                    <span class="k-badge danger">{{ trans('update.out_of_stock') }}</span>
                                @elseif(!$product->ordering and $hasDiscount)
                                    <span class="k-badge info">{{ trans('update.ordering_off') }}</span>
                                @elseif($hasDiscount)
                                    <span class="k-badge danger">
                                        {{ trans('public.offer',['off' => $hasDiscount->percent]) }}
                                    </span>
                                @else
                                    @switch($product->status)
                                        @case(\App\Models\Product::$active)
                                            <span class="k-badge success">{{ trans('public.active') }}</span>
                                            @break
                                        @case(\App\Models\Product::$draft)
                                            <span class="k-badge danger">{{ trans('public.draft') }}</span>
                                            @break
                                        @case(\App\Models\Product::$pending)
                                            <span class="k-badge warning">{{ trans('public.waiting') }}</span>
                                            @break
                                        @case(\App\Models\Product::$inactive)
                                            <span class="k-badge danger">{{ trans('public.rejected') }}</span>
                                            @break
                                    @endswitch
                                @endif
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="kemetic-product-body d-flex flex-column w-100">

                            <!-- Header -->
                            <div class="d-flex align-items-center justify-content-between">
                                <a href="{{ $product->getUrl() }}" target="_blank">
                                    <h3 class="kemetic-product-title">
                                        {{ $product->title }}
                                    </h3>
                                </a>

                                @if($authUser->id == $product->creator_id)
                                    <div class="dropdown kemetic-dropdown">
                                        <button class="kemetic-more-btn" data-toggle="dropdown">
                                            ⋮
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="/panel/store/products/{{ $product->id }}/edit">
                                                {{ trans('public.edit') }}
                                            </a>

                                            @include('web.default.panel.includes.content_delete_btn',[
                                                'deleteContentUrl'=>"/panel/store/products/{$product->id}/delete",
                                                'deleteContentClassName'=>'text-danger',
                                                'deleteContentItem'=>$product
                                            ])
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @include('web.default.includes.webinar.rate',['rate'=>$product->getRate()])

                            <!-- Price -->
                            <div class="kemetic-price mt-10">
                                @if($product->price > 0)
                                    @if($product->getPriceWithActiveDiscountPrice() < $product->price)
                                        <span class="price">{{ handlePrice($product->getPriceWithActiveDiscountPrice(),true,true,false,null,true,'store') }}</span>
                                        <span class="old-price">{{ handlePrice($product->price,true,true,false,null,true,'store') }}</span>
                                    @else
                                        <span class="price">{{ handlePrice($product->price,true,true,false,null,true,'store') }}</span>
                                    @endif
                                @else
                                    <span class="price">{{ trans('public.free') }}</span>
                                @endif
                            </div>

                            <!-- Stats -->
                            <div class="kemetic-stats mt-auto">
                                <div><span>ID</span><strong>{{ $product->id }}</strong></div>
                                <div><span>{{ trans('public.category') }}</span><strong>{{ $product->category->title ?? '' }}</strong></div>
                                <div><span>{{ trans('public.type') }}</span><strong>{{ trans('update.product_type_'.$product->type) }}</strong></div>
                                <div><span>{{ trans('update.availability') }}</span>
                                    <strong>{{ $product->unlimited_inventory ? trans('update.unlimited') : $product->getAvailability() }}</strong>
                                </div>
                                <div><span>{{ trans('panel.sales') }}</span>
                                    <strong>{{ $product->salesCount() }}</strong>
                                </div>

                                @if($product->isPhysical())
                                <div><span>{{ trans('update.shipping_cost') }}</span>
                                    <strong>{{ !empty($product->delivery_fee) ? handlePrice($product->delivery_fee) : 0 }}</strong>
                                </div>
                                <div><span>{{ trans('update.waiting_orders') }}</span>
                                    <strong>{{ $product->productOrders->whereIn('status',[\App\Models\ProductOrder::$waitingDelivery,\App\Models\ProductOrder::$shipped])->count() }}</strong>
                                </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        @endforeach

        <div class="my-30">
            {{ $products->links('vendor.pagination.panel') }}
        </div>
    @endif
</section>

@endsection
