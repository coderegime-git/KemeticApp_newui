@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    {{ trans('update.verification_requests') }}
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="{{ (count($orders) > 4) ? 'table-responsive' : 'table-responsive-2' }}">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th>{{ trans('admin/main.user') }}</th>
                                        <th class="text-left">{{ trans('update.installment_plan') }}</th>
                                        <th class="text-center">{{ trans('update.product') }}</th>
                                        <th class="text-center">{{ trans('financial.total_amount') }}</th>
                                        <th class="text-center">{{ trans('update.upfront') }}</th>
                                        <th class="text-center">{{ trans('update.installments_count') }}</th>
                                        <th class="text-center">{{ trans('update.installments_amount') }}</th>
                                        <th class="text-center">{{ trans('update.request_date') }}</th>
                                        <th class="text-center">{{ trans('admin/main.status') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($orders as $order)
                                        <tr>
                                            <td class="text-left">
                                                <div class="d-flex align-items-center">
                                                    <figure class="avatar mr-2">
                                                        <img src="{{ $order->user->getAvatar() }}" alt="{{ $order->user->full_name }}">
                                                    </figure>
                                                    <div class="media-body ml-1">
                                                        <div class="mt-0 mb-1 font-weight-bold">{{ $order->user->full_name }}</div>

                                                        @if($order->user->mobile)
                                                            <div class="text-primary text-small font-600-bold">{{ $order->user->mobile }}</div>
                                                        @endif

                                                        @if($order->user->email)
                                                            <div class="text-primary text-small font-600-bold">{{ $order->user->email }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="text-left">
                                                <div class="">
                                                    <span class="d-block font-16 font-weight-500">{{ $order->selectedInstallment->installment->title }}</span>
                                                    <span class="d-block font-12 mt-1">{{ trans('update.target_types_'.$order->selectedInstallment->installment->target_type) }}</span>
                                                </div>
                                            </td>

                                            @php
                                                $itemPrice = $order->getItemPrice();
                                            @endphp

                                            <td class="text-center">
                                                @if(!empty($order->webinar_id))
                                                    <a href="{{ !empty($order->webinar) ? $order->webinar->getUrl() : '' }}"
                                                       target="_blank" class="font-14">#{{ $order->webinar_id }}-{{ !empty($order->webinar) ? $order->webinar->title : '' }}</a>
                                                    <span class="d-block font-12">{{ trans('update.target_types_courses') }}</span>
                                                @elseif(!empty($order->bundle_id))
                                                    <a href="{{ !empty($order->bundle) ? $order->bundle->getUrl() : '' }}"
                                                       target="_blank" class="font-14">#{{ $order->bundle_id }}-{{ !empty($order->bundle) ? $order->bundle->title : '' }}</a>
                                                    <span class="d-block font-12">{{ trans('update.target_types_bundles') }}</span>
                                                @elseif(!empty($order->product_id))
                                                    <a href="{{ !empty($order->product) ? $order->product->getUrl() : '' }}"
                                                       target="_blank" class="font-14">#{{ $order->product_id }}-{{ !empty($order->product) ? $order->product->title : '' }}</a>
                                                    <span class="d-block font-12">{{ trans('update.target_types_store_products') }}</span>
                                                @elseif(!empty($order->subscribe_id))
                                                    <span class="font-14">{{ trans('admin/main.purchased_subscribe') }}</span>
                                                    <span class="d-block font-12">{{ trans('update.target_types_subscription_packages') }}</span>
                                                @elseif(!empty($order->registration_package_id))
                                                    <span class="font-14">{{ trans('update.purchased_registration_package') }}</span>
                                                    <span class="d-block font-12">{{ trans('update.target_types_registration_packages') }}</span>
                                                @else
                                                    ---
                                                @endif
                                            </td>

                                            <td class="text-center">{{ handlePrice($order->selectedInstallment->totalPayments($itemPrice)) }}</td>

                                            <td class="text-center">
                                                @if(!empty($order->selectedInstallment->upfront))
                                                    {{ ($order->selectedInstallment->upfront_type == 'percent') ? $order->selectedInstallment->upfront.'%' : handlePrice($order->selectedInstallment->upfront) }}
                                                @else
                                                    --
                                                @endif
                                            </td>

                                            <td class="text-center">{{ $order->selectedInstallment->steps_count }}</td>

                                            <td class="text-center">
                                                @php
                                                    $stepsFixedAmount = $order->selectedInstallment->steps->where('amount_type', 'fixed_amount')->sum('amount');
                                                    $stepsPercents = $order->selectedInstallment->steps->where('amount_type', 'percent')->sum('amount');
                                                @endphp

                                                <span class="">{{ $stepsFixedAmount ? handlePrice($stepsFixedAmount) : '' }}</span>

                                                @if($stepsPercents)
                                                    <span>{{ $stepsFixedAmount ? ' + ' : '' }}{{ $stepsPercents }}%</span>
                                                @endif
                                            </td>

                                            <td class="text-center">{{ dateTimeFormat($order->created_at, 'j M Y') }}</td>

                                            <td class="text-center">
                                                @if($order->status == "pending_verification")
                                                    <span class="text-warning">{{ trans('update.pending_verification') }}</span>
                                                @elseif($order->status == "open")
                                                    <span class="text-success">{{ trans('financial.approved') }}</span>
                                                @elseif($order->status == "rejected")
                                                    <span class="text-danger">{{ trans('public.rejected') }}</span>
                                                @elseif($order->status == "canceled")
                                                    <span class="text-danger">{{ trans('public.canceled') }}</span>
                                                @elseif($order->status == "refunded")
                                                    <span class="text-dark">{{ trans('update.refunded') }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                <div class="btn-group dropdown table-actions">
                                                    <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </button>
                                                    <div class="dropdown-menu text-left webinars-lists-dropdown">

                                                        @can('admin_installments_orders')
                                                            @if($order->status == "pending_verification")
                                                                @include('admin.includes.delete_button',[
                                                                        'url' => getAdminPanelUrl("/financial/installments/orders/{$order->id}/approve"),
                                                                        'btnClass' => 'd-flex align-items-center text-success text-decoration-none btn-transparent btn-sm mb-1',
                                                                        'btnText' => '<i class="fa fa-check"></i><span class="ml-2">'. trans("admin/main.approve") .'</span>'
                                                                        ])

                                                                @include('admin.includes.delete_button',[
                                                                        'url' => getAdminPanelUrl("/financial/installments/orders/{$order->id}/reject"),
                                                                        'btnClass' => 'd-flex align-items-center text-danger text-decoration-none btn-transparent btn-sm mb-1',
                                                                        'btnText' => '<i class="fa fa-check"></i><span class="ml-2">'. trans("admin/main.reject") .'</span>'
                                                                        ])
                                                            @endif

                                                            <a href="{{ getAdminPanelUrl("/financial/installments/orders/{$order->id}/details") }}" target="_blank" class="d-flex align-items-center text-dark text-decoration-none btn-transparent btn-sm">
                                                                <i class="fa fa-eye"></i>
                                                                <span class="ml-2">{{ trans('update.show_details') }}</span>
                                                            </a>
                                                        @endcan

                                                        @can('admin_users_impersonate')
                                                            <a href="{{ getAdminPanelUrl() }}/users/{{ $order->user_id }}/impersonate" target="_blank" class="d-flex align-items-center text-dark text-decoration-none btn-transparent btn-sm mt-1">
                                                                <i class="fa fa-user-shield"></i>
                                                                <span class="ml-2">{{ trans('admin/main.login') }}</span>
                                                            </a>
                                                        @endcan

                                                        @can('admin_users_edit')
                                                            <a href="{{ getAdminPanelUrl() }}/users/{{ $order->user_id }}/edit" class="d-flex align-items-center text-dark text-decoration-none btn-transparent btn-sm mt-1">
                                                                <i class="fa fa-edit"></i>
                                                                <span class="ml-2">{{ trans('admin/main.edit') }}</span>
                                                            </a>
                                                        @endcan

                                                        @can('admin_support_send')
                                                            <a href="{{ getAdminPanelUrl() }}/supports/create?user_id={{ $order->user_id }}" target="_blank" class="d-flex align-items-center text-dark text-decoration-none btn-transparent btn-sm text-primary mt-1">
                                                                <i class="fa fa-comment"></i>
                                                                <span class="ml-2">{{ trans('site.send_message') }}</span>
                                                            </a>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $orders->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
