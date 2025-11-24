@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a></div>
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl("/abandoned-cart/users-carts") }}">{{trans('update.users_carts')}}</a></div>
                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="card">
                {{--<div class="card-header">

                </div>--}}

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped font-14" id="datatable-basic">

                            <tr>
                                <th class="text-left">{{ trans('admin/main.item') }}</th>
                                <th class="text-center">{{ trans('admin/main.amount') }}</th>
                                <th class="text-center">{{ trans('admin/main.date') }}</th>
                                <th>{{ trans('public.controls') }}</th>
                            </tr>

                            @foreach($carts as $cart)
                                @php
                                    $cartItemInfo = $cart->getItemInfo();
                                    $cartTaxType = !empty($cartItemInfo['isProduct']) ? 'store' : 'general';
                                @endphp

                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <figure class="avatar mr-2 rounded-sm">
                                                <img src="{{ $cartItemInfo['imgPath'] }}" class="img-fluid rounded-sm" alt="user avatar" width="80px" height="80px">
                                            </figure>

                                            <div class="">
                                                <a href="{{ $cartItemInfo['itemUrl'] ?? '#!' }}" target="_blank">
                                                    <h3 class="font-16 font-weight-bold text-black">{{ $cartItemInfo['title'] }}</h3>
                                                </a>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        @if(!empty($cartItemInfo['discountPrice']))
                                            <span class="text-muted text-decoration-line-through mx-2 mx-md-0">{{ handlePrice($cartItemInfo['price'], true, true, false, null, true, $cartTaxType) }}</span>
                                            <span class="font-16 mt-0 mt-md-1 font-weight-bold">{{ handlePrice($cartItemInfo['discountPrice'], true, true, false, null, true, $cartTaxType) }}</span>
                                        @else
                                            <span class="font-16 mt-0 mt-md-1 font-weight-bold">{{ handlePrice($cartItemInfo['price'], true, true, false, null, true, $cartTaxType) }}</span>
                                        @endif
                                    </td>

                                    <td class="text-center">{{ dateTimeFormat($cart->created_at, 'j M Y H:i') }}</td>

                                    <td width="100">

                                        @include('admin.includes.delete_button', [
                                                'url' => getAdminPanelUrl("/abandoned-cart/users-carts/delete-by-id/{$cart->id}"),
                                                'btnClass' => '',
                                                'tooltip' => trans('admin/main.delete'),
                                                'btnIcon' => 'fa-times'
                                            ])
                                    </td>
                                </tr>
                            @endforeach

                        </table>
                    </div>
                </div>


            </div>
        </div>
    </section>

@endsection
