@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    {{ trans('update.installment_plans') }}
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th>{{ trans('admin/main.title') }}</th>
                                        <th class="text-center">{{ trans('update.sales_count') }}</th>
                                        <th class="text-center">{{ trans('update.upfront') }}</th>
                                        <th class="text-center">{{ trans('update.number_of_installments') }}</th>
                                        <th class="text-center">{{ trans('update.amount_of_installments') }}</th>
                                        <th class="text-center">{{ trans('admin/main.capacity') }}</th>
                                        <th class="text-center">{{ trans('admin/main.created_at') }}</th>
                                        <th class="text-center">{{ trans('admin/main.end_date') }}</th>
                                        <th class="text-center">{{ trans('admin/main.status') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($installments as $installment)
                                        <tr>
                                            <td>
                                                <div class="">
                                                    <span class="d-block font-16 font-weight-500">{{ $installment->title }}</span>
                                                    <span class="d-block font-12 mt-1">{{ trans('update.target_types_'.$installment->target_type) }}</span>
                                                </div>
                                            </td>

                                            <td class="text-center">{{ $installment->sales_count }}</td>

                                            <td class="text-center">
                                                {{ ($installment->upfront_type == 'percent') ? $installment->upfront.'%' : handlePrice($installment->upfront) }}
                                            </td>

                                            <td class="text-center">{{ $installment->steps_count }}</td>

                                            <td class="text-center">
                                                @php
                                                    $stepsFixedAmount = $installment->steps->where('amount_type', 'fixed_amount')->sum('amount');
                                                    $stepsPercents = $installment->steps->where('amount_type', 'percent')->sum('amount');
                                                @endphp

                                                <span class="">{{ $stepsFixedAmount ? handlePrice($stepsFixedAmount) : '' }}</span>

                                                @if($stepsPercents)
                                                    <span>{{ $stepsFixedAmount ? ' + ' : '' }}{{ $stepsPercents }}%</span>
                                                @endif
                                            </td>

                                            <td class="text-center">{{ $installment->capacity ?? '' }}</td>

                                            <td class="text-center">{{ dateTimeFormat($installment->created_at, 'Y M j | H:i') }}</td>

                                            <td class="text-center">{{ $installment->end_date ? dateTimeFormat($installment->end_date, 'Y M j | H:i') : '-' }}</td>

                                            <td class="text-center">
                                                @if($installment->enable)
                                                    <span class="text-success">{{ trans('admin/main.active') }}</span>
                                                @else
                                                    <span class="text-danger">{{ trans('admin/main.inactive') }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                @can('admin_promotion_edit')
                                                    <a href="{{ getAdminPanelUrl("/financial/installments/{$installment->id}/edit") }}" class="btn-sm btn-transparent text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('admin/main.edit') }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endcan

                                                @can('admin_promotion_delete')
                                                    @include('admin.includes.delete_button',['url' => getAdminPanelUrl('/financial/installments/'. $installment->id.'/delete'),'btnClass' => ''])
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $installments->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
