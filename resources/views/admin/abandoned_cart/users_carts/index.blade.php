@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>

        <div class="section-body">

            {{-- Stats --}}
            @include("admin.abandoned_cart.users_carts.top_stats")

            {{-- Filters --}}
            @include("admin.abandoned_cart.users_carts.filters")

            <div class="card">
                <div class="card-header">

                    <div class="text-right">
                        <a href="{{ getAdminPanelUrl("/abandoned-cart/users-carts?excel=1") }}" class="btn btn-primary">{{ trans('admin/main.export_xls') }}</a>
                    </div>

                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped font-14" id="datatable-basic">

                            <tr>
                                <th class="text-left">{{ trans('admin/main.user') }}</th>
                                <th class="text-center">{{ trans('public.user_role') }}</th>
                                <th class="text-center">{{ trans('cart.cart_items') }}</th>
                                <th class="text-center">{{ trans('admin/main.amount') }}</th>
                                <th class="text-center">{{ trans('update.coupons') }}</th>
                                <th class="text-center">{{ trans('update.reminders') }}</th>
                                <th>{{ trans('public.controls') }}</th>
                            </tr>

                            @foreach($carts as $cart)
                                <tr>
                                    <td>{{ $cart->user->full_name }}</td>

                                    <td class="text-center">{{ trans('admin/main.'.$cart->user->role_name) }}</td>

                                    <td class="text-center">{{ $cart->total_items }}</td>

                                    <td class="text-center">{{ handlePrice($cart->total_amount) }}</td>

                                    <td class="text-center">
                                        {{ $cart->send_coupons }}
                                    </td>

                                    <td class="text-center">
                                        {{ $cart->send_reminders }}
                                    </td>

                                    <td width="100">


                                        <a href="{{ getAdminPanelUrl("/abandoned-cart/users-carts/{$cart->creator_id}/view-items") }}" class="btn-transparent  text-primary mr-1" data-toggle="tooltip" data-placement="top" title="{{ trans('update.view_items') }}">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        @include('admin.includes.delete_button', [
                                                'url' => getAdminPanelUrl("/abandoned-cart/users-carts/{$cart->creator_id}/send-reminder"),
                                                'btnClass' => '',
                                                'tooltip' => trans('admin/main.send_reminder'),
                                                'btnIcon' => 'fa-clock'
                                            ])

                                        @can('admin_users_impersonate')
                                            <a href="{{ getAdminPanelUrl() }}/users/{{ $cart->creator_id }}/impersonate" target="_blank" class="btn-transparent  text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('admin/main.login') }}">
                                                <i class="fa fa-user-shield"></i>
                                            </a>
                                        @endcan

                                        @can('admin_users_edit')
                                            <a href="{{ getAdminPanelUrl() }}/users/{{ $cart->creator_id }}/edit" target="_blank" class="btn-transparent  text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('update.edit_user') }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endcan

                                        @include('admin.includes.delete_button', [
                                                'url' => getAdminPanelUrl("/abandoned-cart/users-carts/{$cart->creator_id}/empty"),
                                                'btnClass' => '',
                                                'tooltip' => trans('update.empty_cart'),
                                                'btnIcon' => 'fa-times'
                                            ])
                                    </td>
                                </tr>
                            @endforeach

                        </table>
                    </div>
                </div>

                <div class="card-footer text-center">
                    {{ $carts->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </section>

@endsection
