@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    {{ trans('update.transactions') }}
                </div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('update.cashback_users')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalUsers }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-money-bill"></i>
                        </div>

                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('update.total_purchase')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ handlePrice($totalPurchase) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-wallet"></i></div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('update.total_cashback')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ handlePrice($totalCashback) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <section class="card">
                <div class="card-body">
                    <form method="get" class="mb-0">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.search')}}</label>
                                    <input name="title" type="text" class="form-control" value="{{ request()->get('title') }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.start_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="from" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.end_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="to" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                    </div>
                                </div>
                            </div>


                            @php
                                $filters = ['purchase_amount_asc', 'purchase_amount_desc', 'cashback_amount_asc', 'cashback_amount_desc', 'last_cashback_asc', 'last_cashback_desc'];
                            @endphp
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.filters')}}</label>
                                    <select name="sort" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('admin/main.all')}}</option>

                                        @foreach($filters as $filter)
                                            <option value="{{ $filter }}" @if(request()->get('sort') == $filter) selected @endif>{{trans('update.'.$filter)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.user')}}</label>
                                    <select name="user_ids[]" multiple="multiple" class="form-control search-user-select2"
                                            data-placeholder="Search users">

                                        @if(!empty($selectedUsers) and $selectedUsers->count() > 0)
                                            @foreach($selectedUsers as $user)
                                                <option value="{{ $user->id }}" selected>{{ $user->full_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('update.min_purchase_amount')}}</label>
                                    <input type="text" name="min_purchase_amount" class="form-control" value="{{ request()->get('min_purchase_amount') }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('update.min_cashback_amount')}}</label>
                                    <input type="text" name="min_cashback_amount" class="form-control" value="{{ request()->get('min_cashback_amount') }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group mt-1">
                                    <label class="input-label mb-4"> </label>
                                    <input type="submit" class="text-center btn btn-primary w-100" value="{{trans('admin/main.show_results')}}">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>

            {{-- Lists --}}
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            @can('admin_cashback_transactions')
                                <div class="text-right">
                                    <a href="{{ getAdminPanelUrl('/cashback/history/excel?'. http_build_query(request()->all())) }}" class="btn btn-primary">{{ trans('admin/main.export_xls') }}</a>
                                </div>
                            @endcan
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14 ">
                                    <tr>
                                        <th class="text-left">{{ trans('admin/main.user') }}</th>
                                        <th>{{trans('update.total_purchase')}}</th>
                                        <th>{{trans('update.total_cashback')}}</th>
                                        <th>{{trans('update.last_cashback')}}</th>
                                        <th width="120">{{trans('admin/main.actions')}}</th>
                                    </tr>

                                    @foreach($transactions as $transaction)
                                        <tr class="text-center">

                                            <td class="text-left">
                                                <div class="d-flex align-items-center">
                                                    @if(!empty($transaction->user))
                                                        <figure class="avatar mr-2">
                                                            <img src="{{ $transaction->user->getAvatar() }}" alt="{{ $transaction->user->full_name }}">
                                                        </figure>
                                                        <div class="media-body ml-1">
                                                            <div class="mt-0 mb-1 font-weight-bold">{{ $transaction->user->full_name }}</div>

                                                            @if($transaction->user->mobile)
                                                                <div class="text-primary text-small font-600-bold">{{ $transaction->user->mobile }}</div>
                                                            @endif

                                                            @if($transaction->user->email)
                                                                <div class="text-primary text-small font-600-bold">{{ $transaction->user->email }}</div>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="fs-11">{{ trans('update.deleted_user') }}</span>
                                                    @endif
                                                </div>
                                            </td>

                                            <td>
                                                {{ $transaction->purchase_amount ? handlePrice($transaction->purchase_amount) : '-' }}
                                            </td>

                                            <td>
                                                {{ handlePrice($transaction->total_cashback) }}
                                            </td>

                                            <td class="font-12">{{ dateTimeFormat($transaction->last_cashback, 'j M Y') }}</td>

                                            <td class="text-center mb-2" width="120">
                                                @if(!empty($transaction->user))
                                                    @can('admin_users_impersonate')
                                                        <a href="{{ getAdminPanelUrl() }}/users/{{ $transaction->user_id }}/impersonate" target="_blank" class="btn-transparent  text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('admin/main.login') }}">
                                                            <i class="fa fa-user-shield"></i>
                                                        </a>
                                                    @endcan

                                                    @can('admin_users_edit')
                                                        <a href="{{ getAdminPanelUrl() }}/users/{{ $transaction->user_id }}/edit" class="btn-transparent  text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('admin/main.edit') }}">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    @endcan

                                                    @can('admin_cashback_transactions')
                                                        @include('admin.includes.delete_button',[
                                                                'url' => getAdminPanelUrl("/users/{$transaction->user_id}/disable_cashback_toggle"),
                                                                'tooltip' => $transaction->user->disable_cashback ? trans('update.enable_cashback') : trans('update.disable_cashback'),
                                                                'btnIcon' => 'fa-times-circle'
                                                            ])
                                                    @endcan
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $transactions->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
