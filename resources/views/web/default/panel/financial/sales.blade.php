@extends('web.default.layouts.newapp')

@push('styles_top')
<style>
/* =============================
   KEMETIC SALES DASHBOARD
============================= */

.kemetic-section {
    background: #141414;
    color: #f5f5f5;
    border-radius: 18px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 12px 30px rgba(0,0,0,.7);
}

.kemetic-section h2.section-title {
    color: #d4af37;
    font-weight: 700;
    margin-bottom: 25px;
    letter-spacing: 0.6px;
}

.activities-container {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.activities-container .activity-box {
    background: #1e1e1e;
    border-radius: 14px;
    padding: 20px;
    flex: 1 1 calc(25% - 20px);
    text-align: center;
    box-shadow: 0 8px 20px rgba(0,0,0,.5);
}

.activities-container .activity-box img {
    width: 64px;
    height: 64px;
}

.activities-container .activity-box strong {
    color: #d4af37;
    font-size: 28px;
    margin-top: 10px;
    display: block;
}

.activities-container .activity-box span {
    color: #9a9a9a;
    font-size: 14px;
}
:root {
    --k-bg: #0f0f0f;
    --k-card: #141414;
    --k-gold: #f2c94c;
    --k-border: rgba(242,201,76,.35);
    --k-text: #e0e0e0;
    --k-shadow: 0 0 20px rgba(242,201,76,.15);
    --k-radius: 14px;
}

/* SECTION */
.kemetic-section {
    padding: 10px;
}

/* TITLE */
.kemetic-title {
    font-size: 20px;
    color: var(--k-gold);
    border-left: 4px solid var(--k-gold);
    padding-left: 12px;
    font-weight: 600;
}

/* CARD */
.kemetic-card {
    background: var(--k-card);
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius);
    padding: 20px 25px;
    box-shadow: var(--k-shadow);
}

/* FORM */
.kemetic-form-group {
    margin-bottom: 15px;
}

.kemetic-label {
    font-size: 13px;
    color: var(--k-gold);
    margin-bottom: 6px;
    display: block;
}

/* INPUT GROUP */
.kemetic-input-group {
    display: flex;
    align-items: center;
    background: #0d0d0d;
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius);
    transition: .3s;
}

.kemetic-input-group:hover {
    box-shadow: 0 0 15px rgba(242,201,76,.2);
}

.kemetic-icon {
    padding: 0 12px;
    color: var(--k-gold);
}

.kemetic-input {
    background: transparent;
    border: none;
    color: var(--k-text);
    padding: 10px;
    width: 100%;
}

.kemetic-input:focus {
    outline: none;
}

/* SELECT */
.kemetic-select {
    width: 100%;
    background: #0d0d0d;
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius);
    color: var(--k-text);
    padding: 10px;
}

/* BUTTON */
.kemetic-btn {
    background: linear-gradient(135deg, #f2c94c, #d4a62f);
    color: #000;
    border: none;
    padding: 11px;
    border-radius: var(--k-radius);
    font-weight: 600;
    transition: .3s;
}

.kemetic-btn:hover {
    box-shadow: 0 0 25px rgba(242,201,76,.5);
    transform: translateY(-1px);
}

.kemetic-section {
    padding: 10px 0;
}

.kemetic-title {
    font-size: 20px;
    color: #f2c94c;
    border-left: 4px solid #f2c94c;
    padding-left: 12px;
    font-weight: 600;
}

/* CARD */
.kemetic-card {
    background: #0f0f0f;
    border: 1px solid rgba(242,201,76,.35);
    border-radius: 14px;
    padding: 20px 25px;
    box-shadow: 0 0 20px rgba(242,201,76,.15);
}

/* SALES TABLE */
.table.custom-table {
    background: #1e1e1e;
    border-radius: 14px;
    overflow: hidden;
}

.table.custom-table thead {
    background: #141414;
    color: #d4af37;
}

.table.custom-table th, .table.custom-table td {
    color: #f5f5f5;
    vertical-align: middle !important;
}

.user-inline-avatar .avatar {
    background: #333;
    border-radius: 50%;
    overflow: hidden;
}

.user-inline-avatar .avatar img {
    width: 36px;
    height: 36px;
}

.text-primary {
    color: #d4af37 !important;
}

.text-secondary {
    color: #9a9a9a !important;
}

.text-warning {
    color: #f39c12 !important;
}

.text-dark-blue {
    color: #5dade2 !important;
}
</style>
@endpush
@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
<link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
<link rel="stylesheet" href="/assets/default/css/kemetic-quiz-results.css">
@endpush
@section('content')
<section class="kemetic-section">
    <h2 class="section-title">{{ trans('financial.sales_statistics') }}</h2>

    <div class="activities-container mt-25">
        <div class="activity-box">
            <img src="/assets/default/img/activity/48.svg" alt="">
            <strong>{{ $studentCount }}</strong>
            <span>{{ trans('quiz.students') }}</span>
        </div>

        <div class="activity-box">
            <img src="/assets/default/img/activity/webinars.svg" alt="">
            <strong>{{ $webinarCount }}</strong>
            <span>{{ trans('panel.content_sales') }}</span>
        </div>

        <div class="activity-box">
            <img src="/assets/default/img/activity/sales.svg" alt="">
            <strong>{{ $meetingCount }}</strong>
            <span>{{ trans('panel.appointment_sales') }}</span>
        </div>

        <div class="activity-box">
            <img src="/assets/default/img/activity/download-sales.svg" alt="">
            <strong>{{ handlePrice($totalSales) }}</strong>
            <span>{{ trans('financial.total_sales') }}</span>
        </div>
    </div>
</section>

<section class="kemetic-section mt-25">
    <h2 class="kemetic-title">{{ trans('financial.sales_report') }}</h2>

    <div class="kemetic-card mt-20">
        <form action="" method="get" class="row">
            <div class="col-12 col-lg-4">
                <div class="row">

                    <div class="col-12 col-md-6">
                        <div class="kemetic-form-group">
                            <label class="kemetic-label">{{ trans('public.from') }}</label>
                            <div class="kemetic-input-group">
                                <span class="kemetic-icon">
                                    <i data-feather="calendar"></i>
                                </span>
                                <input type="text"
                                       name="from"
                                       autocomplete="off"
                                       class="kemetic-input @if(!empty(request()->get('from'))) datepicker @else datefilter @endif"
                                       value="{{ request()->get('from','') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="kemetic-form-group">
                            <label class="kemetic-label">{{ trans('public.to') }}</label>
                            <div class="kemetic-input-group">
                                <span class="kemetic-icon">
                                    <i data-feather="calendar"></i>
                                </span>
                                <input type="text"
                                       name="to"
                                       autocomplete="off"
                                       class="kemetic-input @if(!empty(request()->get('to'))) datepicker @else datefilter @endif"
                                       value="{{ request()->get('to','') }}">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="row">
                    <div class="col-12 col-lg-5">
                        <div class="kemetic-form-group">
                            <label class="kemetic-label">{{ trans('webinars.webinar') }}</label>
                            <select name="webinar_id" class="kemetic-select select2">
                                <option value="all">{{ trans('public.all') }}</option>
                                @foreach($userWebinars as $webinar)
                                    <option value="{{ $webinar->id }}" @if(request()->get('webinar_id') == $webinar->id) selected @endif>{{ $webinar->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-lg-7">
                        <div class="row">
                        <div class="col-12 col-lg-7">
                            <div class="kemetic-form-group">
                                <label class="kemetic-label">{{ trans('quiz.student') }}</label>
                                <select name="student_id" class="kemetic-select select2">
                                    <option value="all">{{ trans('public.all') }}</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" @if(request()->get('student_id') == $student->id) selected @endif>{{ $student->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="kemetic-group">
                                <label class="kemetic-label">{{ trans('public.type') }}</label>
                                <select class="kemetic-select" id="type" name="type">
                                    <option value="all" @if(request()->get('type')=='all') selected @endif>{{ trans('public.all') }}</option>
                                    <option value="webinar" @if(request()->get('type')=='webinar') selected @endif>{{ trans('webinars.webinar') }}</option>
                                    <option value="meeting" @if(request()->get('type')=='meeting') selected @endif>{{ trans('public.meeting') }}</option>
                                </select>
                            </div>
                        </div>
</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-2 d-flex align-items-end">
                <button type="submit" class="kemetic-btn w-100">
                    {{ trans('public.show_results') }}
                </button>
            </div>
        </form>
    </div>
</section>

@if(!empty($sales) and !$sales->isEmpty())
<section class="kemetic-section mt-35">
    <h2 class="section-title">{{ trans('financial.sales_history') }}</h2>

    <div class="panel-section-card py-20 px-25 mt-20">
        <div class="table-responsive">
            <table class="table custom-table text-center">
                <thead>
                <tr>
                    <th>{{ trans('quiz.student') }}</th>
                    <th class="text-left">{{ trans('product.content') }}</th>
                    <th>{{ trans('public.price') }}</th>
                    <th>{{ trans('public.discount') }}</th>
                    <th>{{ trans('financial.total_amount') }}</th>
                    <th>{{ trans('financial.income') }}</th>
                    <th>{{ trans('public.type') }}</th>
                    <th>{{ trans('public.date') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($sales as $sale)
                    <tr>
                        <td class="text-left">
                            @if(!empty($sale->buyer))
                                <div class="user-inline-avatar d-flex align-items-center">
                                    <div class="avatar">
                                        <img src="{{ $sale->buyer->getAvatar() }}" alt="">
                                    </div>
                                    <div class="ml-5">
                                        <span>{{ $sale->buyer->full_name }}</span>
                                        <span class="font-12 text-gray">{{ $sale->buyer->email }}</span>
                                    </div>
                                </div>
                            @else
                                <span class="text-danger">{{ trans('update.deleted_user') }}</span>
                            @endif
                        </td>
                        
                        <td class="align-middle">
                            <div class="text-left">
                                @php
                                    $content = trans('update.deleted_item');
                                    $contentId = null;

                                    if(!empty($sale->webinar)) {
                                        $content = $sale->webinar->title;
                                        $contentId =$sale->webinar->id;
                                    } elseif(!empty($sale->bundle)) {
                                        $content = $sale->bundle->title;
                                        $contentId =$sale->bundle->id;
                                    } elseif(!empty($sale->productOrder) and !empty($sale->productOrder->product)) {
                                        $content = $sale->productOrder->product->title;
                                        $contentId =$sale->productOrder->product->id;
                                    } elseif(!empty($sale->registrationPackage)) {
                                        $content = $sale->registrationPackage->title;
                                        $contentId =$sale->registrationPackage->id;
                                    } elseif(!empty($sale->subscribe)) {
                                        $content = $sale->subscribe->title;
                                        $contentId =$sale->subscribe->id;
                                    } elseif(!empty($sale->promotion)) {
                                        $content = $sale->promotion->title;
                                        $contentId =$sale->promotion->id;
                                    } elseif (!empty($sale->meeting_id)) {
                                        $content = trans('meeting.reservation_appointment');
                                    }
                                @endphp

                                <span class="d-block">{{ $content }}</span>

                                @if(!empty($contentId))
                                    <span class="d-block font-12 text-gray">Id: {{ $contentId }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="align-middle">
                            @if($sale->payment_method == \App\Models\Sale::$subscribe)
                                <span class="">{{ trans('financial.subscribe') }}</span>
                            @else
                                <span>{{ !empty($sale->amount) ? handlePrice($sale->amount) : '-' }}</span>
                            @endif
                        </td>
                        <td class="align-middle">{{ !empty($sale->discount) ? handlePrice($sale->discount) : '-' }}</td>
                        <td class="align-middle">
                            @if($sale->payment_method == \App\Models\Sale::$subscribe)
                                <span class="">{{ trans('financial.subscribe') }}</span>
                            @else
                                <span>{{ !empty($sale->total_amount) ? handlePrice($sale->total_amount) : '-' }}</span>
                            @endif
                        </td>
                        <td class="align-middle">
                            <span>{{ !empty($sale->getIncomeItem()) ? handlePrice($sale->getIncomeItem()) : '-' }}</span>
                        </td>
                        <td class="align-middle">
                            @switch($sale->type)
                                @case(\App\Models\Sale::$webinar)
                                @if(!empty($sale->webinar))
                                    <span class="text-primary">{{ trans('webinars.'.$sale->webinar->type) }}</span>
                                @else
                                    <span class="text-danger">{{ trans('update.class') }}</span>
                                @endif
                                @break;
                                @case(\App\Models\Sale::$meeting)
                                <span class="text-dark-blue">{{ trans('meeting.appointment') }}</span>
                                @break;
                                @case(\App\Models\Sale::$subscribe)
                                <span class="text-danger">{{ trans('financial.subscribe') }}</span>
                                @break;
                                @case(\App\Models\Sale::$promotion)
                                <span class="text-warning">{{ trans('panel.promotion') }}</span>
                                @break;
                                @case(\App\Models\Sale::$registrationPackage)
                                <span class="text-secondary">{{ trans('update.registration_package') }}</span>
                                @break;
                                @case(\App\Models\Sale::$bundle)
                                <span class="text-primary">{{ trans('update.bundle') }}</span>
                                @break;
                                @case(\App\Models\Sale::$product)
                                <span class="text-dark-blue">{{ trans('update.product') }}</span>
                                @break;
                            @endswitch
                        </td>
                        <td>{{ dateTimeFormat($sale->created_at, 'j M Y H:i') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="my-30">
            {{ $sales->appends(request()->input())->links('vendor.pagination.panel') }}
        </div>
    </div>
</section>
@else
    @include(getTemplate() . '.includes.no-result',[
        'file_name' => 'sales.png',
        'title' => trans('financial.sales_no_result'),
        'hint' => nl2br(trans('financial.sales_no_result_hint')),
    ])
@endif

@endsection

@push('scripts_bottom')
<script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
<script src="/assets/default/vendors/select2/select2.min.js"></script>
@endpush