@extends('web.default.layouts.newapp')

@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">

<style>
    :root {
        --k-bg: #0f0f0f;
        --k-card: #171717;
        --k-border: #2a2a2a;
        --k-gold: #F2C94C;
        --k-text: #eaeaea;
        --k-muted: #9aa0a6;
        --k-radius: 18px;
    }

    .k-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--k-gold);
    }

    .k-plan {
        background: var(--k-card);
        border: 1px solid var(--k-border);
        border-radius: var(--k-radius);
        padding: 45px 20px 25px;
        text-align: center;
        height: 100%;
        transition: all .3s ease;
        position: relative;
    }

    .k-plan:hover {
        transform: translateY(-6px);
        border-color: var(--k-gold);
        box-shadow: 0 12px 35px rgba(0,0,0,.6);
    }

    .k-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, #F2C94C, #d4a72c);
        color: #000;
        font-size: 12px;
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 600;
    }

    .k-plan h3 {
        color: var(--k-gold);
        font-size: 26px;
        margin-top: 15px;
    }

    .k-price {
        font-size: 36px;
        color: var(--k-text);
        margin-top: 25px;
        font-weight: 700;
    }

    .k-desc {
        color: var(--k-muted);
        font-size: 14px;
        margin-top: 15px;
        line-height: 1.6;
    }

    .k-btn {
        margin-top: 35px;
        background: linear-gradient(135deg, #F2C94C, #d4a72c);
        border: none;
        color: #000;
        font-weight: 600;
        border-radius: 30px;
    }

    .k-btn:hover {
        opacity: .9;
    }

    .k-card {
        background: var(--k-card);
        border: 1px solid var(--k-border);
        border-radius: var(--k-radius);
        padding: 25px;
    }

    .k-table th {
        background: #121212;
        color: var(--k-gold);
        border-bottom: 1px solid var(--k-border);
    }

    .k-table td {
        color: var(--k-text);
        border-top: 1px solid var(--k-border);
    }

    .k-table tr:hover {
        background: rgba(242,201,76,.06);
    }

    .k-modal select,
    .k-modal .custom-select {
        background: #0c0c0c;
        border: 1px solid var(--k-border);
        color: var(--k-text);
    }
</style>
@endpush

@section('content')

{{-- ================= PROMOTION PLANS ================= --}}
<section>
    <h2 class="k-title">{{ trans('panel.select_promotion_plan') }}</h2>

    <div class="row mt-25">
        @foreach($promotions as $promotion)
            <div class="col-12 col-sm-6 col-lg-3 mt-20">
                <div class="k-plan">

                    @if($promotion->is_popular)
                        <span class="k-badge">{{ trans('panel.popular') }}</span>
                    @endif

                    <img src="{{ $promotion->icon }}" width="64">

                    <h3>{{ $promotion->title }}</h3>
                    <p class="text-muted mt-5">
                        {{ trans('panel.promotion_days',['day' => $promotion->days]) }}
                    </p>

                    <div class="k-price">
                        {{ (!empty($promotion->price) && $promotion->price > 0)
                            ? handlePrice($promotion->price, true, true, false, null, true)
                            : trans('public.free') }}
                    </div>

                    <div class="k-desc">{!! nl2br($promotion->description) !!}</div>

                    <button
                        type="button"
                        data-promotion-id="{{ $promotion->id }}"
                        class="js-pay-promotion btn k-btn btn-block">
                        {{ trans('update.purchase') }}
                    </button>

                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- ================= PROMOTION HISTORY ================= --}}
@if($promotionSales->count() > 0)

<section class="mt-40">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <h2 class="k-title">{{ trans('panel.promotions_history') }}</h2>

        <div class="d-flex align-items-center mt-15 mt-md-0">
            <label class="mr-10 text-muted">
                {{ trans('panel.show_only_active_promotions') }}
            </label>
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="activePromotionSwitch">
                <label class="custom-control-label" for="activePromotionSwitch"></label>
            </div>
        </div>
    </div>

    <div class="k-card mt-25">
        <div class="table-responsive">
            <table class="table k-table text-center">
                <thead>
                <tr>
                    <th class="text-left">{{ trans('panel.webinar') }}</th>
                    <th>{{ trans('panel.plan') }}</th>
                    <th>{{ trans('public.price') }}</th>
                    <th>{{ trans('public.date') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($promotionSales as $promotionSale)
                    <tr>
                        <td class="text-left font-weight-500">
                            {{ $promotionSale->webinar->title }}
                        </td>
                        <td>{{ $promotionSale->promotion->title }}</td>
                        <td>
                            {{ (!empty($promotionSale->promotion->price) && $promotionSale->promotion->price > 0)
                                ? handlePrice($promotionSale->promotion->price)
                                : trans('public.free') }}
                        </td>
                        <td>{{ dateTimeFormat($promotionSale->created_at, 'j M Y | H:i') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

@else
    @include(getTemplate().'.includes.no-result',[
        'file_name' => 'promotion.png',
        'title' => trans('panel.promotion_no_result'),
        'hint' => nl2br(trans('panel.promotion_no_result_hint')),
    ])
@endif

<div class="my-30">
    {{ $promotionSales->appends(request()->input())->links('vendor.pagination.panel') }}
</div>

{{-- ================= MODAL ================= --}}
<div id="promotionModal" class="d-none k-modal">
    <form action="/panel/marketing/pay-promotion" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="promotion_id">

        <h3 class="k-title">{{ trans('panel.promote_the_webinar') }}</h3>

        <div class="text-center mt-25">
            <img src="/assets/default/img/check.png" width="110">
            <p class="mt-10 text-muted">{{ trans('panel.select_webinar_for_promotion') }}</p>
        </div>

        <div class="mt-20">
            <div class="d-flex justify-content-between">
                <span class="text-muted">{{ trans('panel.plan') }}</span>
                <span class="modal-title text-warning"></span>
            </div>

            <div class="d-flex justify-content-between mt-10">
                <span class="text-muted">{{ trans('public.price') }}</span>
                <span class="modal-price text-warning"></span>
            </div>

            <div class="form-group mt-15">
                <select name="webinar_id" class="form-control custom-select">
                    <option disabled selected>{{ trans('panel.select_course') }}</option>
                    @foreach($webinars as $webinar)
                        <option value="{{ $webinar->id }}">{{ $webinar->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-30 d-flex justify-content-end">
            <button type="button" class="btn k-btn js-submit-promotion">
                {{ trans('panel.pay') }}
            </button>
            <button type="button" class="btn btn-danger ml-10 close-swl">
                {{ trans('public.close') }}
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts_bottom')
<script src="/assets/default/vendors/select2/select2.min.js"></script>
<script src="/assets/default/js/panel/marketing/promotions.min.js"></script>
@endpush
