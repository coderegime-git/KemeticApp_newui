@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    {{ trans('update.verification_request_details') }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-comment"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ trans('update.open_installments') }}</h4>
                        </div>
                        <div class="card-body">
                            {{ $openInstallments['count'] }}
                            <span class="d-block font-12 text-muted mt-1">{{ handlePrice($openInstallments['amount']) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-eye"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ trans('update.pending_verification') }}</h4>
                        </div>
                        <div class="card-body">
                            {{ $pendingVerifications }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-hourglass-start"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ trans('update.finished_installments') }}</h4>
                        </div>
                        <div class="card-body">
                            {{ $finishedInstallments['count'] }}
                            <span class="d-block font-12 text-muted mt-1">{{ handlePrice($finishedInstallments['amount']) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-flag"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ trans('update.overdue_installments') }}</h4>
                        </div>
                        <div class="card-body">
                            {{ $overdueInstallmentsCount }}
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-7">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ trans('update.installment_overview') }}</h4>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th class="text-left">{{ trans('admin/main.title') }}</th>
                                        <th class="text-center">{{ trans('admin/main.amount') }}</th>
                                        <th class="text-center">{{ trans('update.due_date') }}</th>
                                        <th class="text-center">{{ trans('update.payment_date') }}</th>
                                        <th class="text-center">{{ trans('public.status') }}</th>
                                    </tr>


                                    @if(!empty($installment->upfront))
                                        @php
                                            $upfrontPayment = $payments->where('type', 'upfront')->first();
                                        @endphp
                                        <tr>

                                            <td class="text-left">
                                                {{ trans('update.upfront') }}
                                                @if($installment->upfront_type == 'percent')
                                                    <span class="ml-1">({{ $installment->upfront }}%)</span>
                                                @endif
                                            </td>

                                            <td class="text-center">{{ handlePrice($installment->getUpfront($itemPrice)) }}</td>

                                            <td class="text-center">-</td>

                                            <td class="text-center">{{ !empty($upfrontPayment) ? dateTimeFormat($upfrontPayment->created_at, 'j M Y H:i') : '-' }}</td>

                                            <td class="text-center">
                                                @if(!empty($upfrontPayment))
                                                    <span class="text-primary">{{ trans('public.paid') }}</span>
                                                @else
                                                    <span class="text-dark-blue">{{ trans('update.unpaid') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif

                                    @foreach($installment->steps as $step)
                                        @php
                                            $stepPayment = $payments->where('selected_installment_step_id', $step->id)->where('status', 'paid')->first();
                                            $dueAt = ($step->deadline * 86400) + $order->created_at;
                                            $isOverdue = ($dueAt < time() and empty($stepPayment));
                                        @endphp

                                        <tr>
                                            <td class="text-left">
                                                <div class="d-block font-16 font-weight-500 text-dark-blue">
                                                    {{ $step->title }}

                                                    @if($step->amount_type == 'percent')
                                                        <span class="ml-1 font-12 text-gray">({{ $step->amount }}%)</span>
                                                    @endif
                                                </div>

                                                <span class="d-block font-12 text-gray">{{ trans('update.n_days_after_purchase', ['days' => $step->deadline]) }}</span>
                                            </td>

                                            <td class="text-center">{{ handlePrice($step->getPrice($itemPrice)) }}</td>

                                            <td class="text-center">
                                                <span class="{{ $isOverdue ? 'text-danger' : '' }}">{{ dateTimeFormat($dueAt, 'j M Y') }}</span>
                                            </td>

                                            <td class="text-center">{{ !empty($stepPayment) ? dateTimeFormat($stepPayment->created_at, 'j M Y H:i') : '-' }}</td>

                                            <td class="text-center">
                                                @if(!empty($stepPayment))
                                                    <span class="text-primary">{{ trans('public.paid') }}</span>
                                                @else
                                                    <span class="{{ $isOverdue ? 'text-danger' : 'text-dark-blue' }}">{{ trans('update.unpaid') }} {{ $isOverdue ? "(". trans('update.overdue') .")" : '' }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-12 col-md-5">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ trans('update.user_uploaded_files') }}</h4>
                        </div>

                        <div class="card-body">
                            @if(!empty($attachments) and count($attachments))
                                <div class="table-responsive">
                                    <table class="table table-striped font-14">
                                        <tr>
                                            <th class="text-left">{{ trans('admin/main.title') }}</th>
                                            <th class="text-right">{{ trans('admin/main.actions') }}</th>
                                        </tr>

                                        @foreach($attachments as $attachment)
                                            <tr>

                                                <td class="text-left">
                                                    {{ $attachment->title }}
                                                </td>

                                                <td class="text-right">
                                                    <a href="{{ getAdminPanelUrl("/financial/installments/orders/{$order->id}/attachments/{$attachment->id}/download") }}" class="" data-toggle="tooltip" data-placement="top" title="{{ trans('home.download') }}">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </table>
                                </div>
                            @else
                                @include('admin.includes.no-result',[
                                    'file_name' => 'faq.png',
                                    'title' => trans('update.no_uploaded_files'),
                                    'hint' => trans('update.no_uploaded_files_hint'),
                                    'noResultSmLogo' => true
                                ])
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-end my-3">

            @if($order->status == "pending_verification")
                @include('admin.includes.delete_button',[
                        'url' => getAdminPanelUrl("/financial/installments/orders/{$order->id}/approve"),
                        'btnClass' => 'btn btn-success text-white',
                        'btnText' => '<i class="fa fa-check"></i><span class="ml-2">'. trans("admin/main.approve") .'</span>',
                        'noBtnTransparent' => true,
                        ])
            @endif

            @if($order->status == "open")
                @include('admin.includes.delete_button',[
                        'url' => getAdminPanelUrl("/financial/installments/orders/{$order->id}/reject"),
                        'btnClass' => 'btn btn-danger text-white ml-1',
                        'btnText' => '<i class="fa fa-check"></i><span class="ml-2">'. trans("admin/main.reject") .'</span>',
                        'noBtnTransparent' => true,
                        ])
            @endif
        </div>

    </section>
@endsection
