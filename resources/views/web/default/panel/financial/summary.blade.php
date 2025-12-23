@extends('web.default.layouts.newapp')

@push('styles_top')
<style>
/* =============================
   KEMETIC FINANCIAL DASHBOARD
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

/* TABLE REPLACEMENT */
.kemetic-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 15px;
}

.kemetic-table thead th {
    color: #d4af37;
    text-align: center;
    font-weight: 700;
    padding-bottom: 10px;
    border-bottom: 2px solid #d4af37;
}

.kemetic-table tbody tr {
    background: #1e1e1e;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,.5);
    transition: transform 0.2s;
}

.kemetic-table tbody tr:hover {
    transform: translateY(-4px);
}

.kemetic-table tbody td {
    padding: 15px;
    vertical-align: middle;
    color: #f5f5f5;
}

.kemetic-table tbody td span {
    display: block;
}

.kemetic-table tbody td .text-primary {
    color: #2ecc71; /* green for positive */
    font-weight: 600;
}

.kemetic-table tbody td .text-danger {
    color: #e74c3c; /* red for negative */
    font-weight: 600;
}

.kemetic-table tbody td .font-12 {
    color: #9a9a9a;
}

.kemetic-no-result {
    text-align: center;
    padding: 50px 0;
}

.kemetic-no-result img {
    width: 150px;
    margin-bottom: 20px;
}

.pagination-wrapper {
    margin-top: 30px;
    display: flex;
    justify-content: center;
}
</style>
@endpush

@section('content')
@if($accountings->count() > 0)
<section class="kemetic-section">
    <h2 class="section-title">{{ trans('financial.financial_documents') }}</h2>

    <table class="kemetic-table">
        <thead>
            <tr>
                <th>{{ trans('public.title') }}</th>
                <th>{{ trans('public.description') }}</th>
                <th>{{ trans('panel.amount') }} ({{ $currency }})</th>
                <th>{{ trans('public.creator') }}</th>
                <th>{{ trans('public.date') }}</th>
            </tr>
        </thead>
        <tbody>
        @foreach($accountings as $accounting)
            <tr>
                <td class="text-left">
                    <div class="d-flex flex-column">
                        <span class="font-14 font-weight-500">
                            @if($accounting->is_cashback)
                                {{ trans('update.cashback') }}
                            @elseif(!empty($accounting->webinar_id) and !empty($accounting->webinar))
                                {{ $accounting->webinar->title }}
                            @elseif(!empty($accounting->bundle_id) and !empty($accounting->bundle))
                                {{ $accounting->bundle->title }}
                            @elseif(!empty($accounting->product_id) and !empty($accounting->product))
                                {{ $accounting->product->title }}
                            @elseif(!empty($accounting->meeting_time_id))
                                {{ trans('meeting.reservation_appointment') }}
                            @elseif(!empty($accounting->subscribe_id) and !empty($accounting->subscribe))
                                {{ $accounting->subscribe->title }}
                            @elseif(!empty($accounting->promotion_id) and !empty($accounting->promotion))
                                {{ $accounting->promotion->title }}
                            @elseif(!empty($accounting->registration_package_id) and !empty($accounting->registrationPackage))
                                {{ $accounting->registrationPackage->title }}
                            @elseif(!empty($accounting->installment_payment_id))
                                {{ trans('update.installment') }}
                            @elseif($accounting->store_type == \App\Models\Accounting::$storeManual)
                                {{ trans('financial.manual_document') }}
                            @elseif($accounting->type == \App\Models\Accounting::$addiction and $accounting->type_account == \App\Models\Accounting::$asset)
                                {{ trans('financial.charge_account') }}
                            @elseif($accounting->type == \App\Models\Accounting::$deduction and $accounting->type_account == \App\Models\Accounting::$income)
                                {{ trans('financial.payout') }}
                            @elseif($accounting->is_registration_bonus)
                                {{ trans('update.registration_bonus') }}
                            @else
                                ---
                            @endif
                        </span>
                        <span class="font-12 text-gray">
                            @if(!empty($accounting->webinar_id) and !empty($accounting->webinar))
                                #{{ $accounting->webinar->id }}{{ ($accounting->is_cashback) ? '-'.$accounting->webinar->title : '' }}
                            @elseif(!empty($accounting->bundle_id) and !empty($accounting->bundle))
                                #{{ $accounting->bundle->id }}{{ ($accounting->is_cashback) ? '-'.$accounting->bundle->title : '' }}
                            @elseif(!empty($accounting->product_id) and !empty($accounting->product))
                                #{{ $accounting->product->id }}{{ ($accounting->is_cashback) ? '-'.$accounting->product->title : '' }}
                            @elseif(!empty($accounting->meeting_time_id) and !empty($accounting->meetingTime))
                                {{ $accounting->meetingTime->meeting->creator->full_name }}
                            @elseif(!empty($accounting->subscribe_id) and !empty($accounting->subscribe))
                                {{ $accounting->subscribe->id }}{{ ($accounting->is_cashback) ? '-'.$accounting->subscribe->title : '' }}
                            @elseif(!empty($accounting->promotion_id) and !empty($accounting->promotion))
                                {{ $accounting->promotion->id }}{{ ($accounting->is_cashback) ? '-'.$accounting->promotion->title : '' }}
                            @elseif(!empty($accounting->registration_package_id) and !empty($accounting->registrationPackage))
                                {{ $accounting->registrationPackage->id }}{{ ($accounting->is_cashback) ? '-'.$accounting->registrationPackage->title : '' }}
                            @elseif(!empty($accounting->installment_payment_id))
                                @php
                                    $installmentItemTitle = "--";
                                    $installmentOrderPayment = $accounting->installmentOrderPayment;

                                    if (!empty($installmentOrderPayment)) {
                                        $installmentOrder = $installmentOrderPayment->installmentOrder;
                                        if (!empty($installmentOrder)) {
                                            $installmentItem = $installmentOrder->getItem();
                                            if (!empty($installmentItem)) {
                                                $installmentItemTitle = $installmentItem->title;
                                            }
                                        }
                                    }
                                @endphp
                                {{ $installmentItemTitle }}
                            @else
                                ---
                            @endif
                        </span>
                    </div>
                </td>
                <td class="text-left align-middle">
                    <span class="font-weight-500 text-gray">{{ $accounting->description }}</span>
                </td>
                <td class="text-center align-middle">
                    @switch($accounting->type)
                        @case(\App\Models\Accounting::$addiction)
                            <span class="text-primary">+{{ handlePrice($accounting->amount, false) }}</span>
                            @break
                        @case(\App\Models\Accounting::$deduction)
                            <span class="text-danger">-{{ handlePrice($accounting->amount, false) }}</span>
                            @break
                    @endswitch
                </td>
                <td class="text-center align-middle">{{ trans('public.'.$accounting->store_type) }}</td>
                <td class="text-center align-middle">
                    <span>{{ dateTimeFormat($accounting->created_at, 'j M Y') }}</span>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</section>

<div class="pagination-wrapper">
    {{ $accountings->appends(request()->input())->links('vendor.pagination.panel') }}
</div>

@else
<div class="kemetic-no-result">
    @include(getTemplate() . '.includes.no-result',[
        'file_name' => 'financial.png',
        'title' => trans('financial.financial_summary_no_result'),
        'hint' => nl2br(trans('financial.financial_summary_no_result_hint')),
    ])
</div>
@endif
@endsection



