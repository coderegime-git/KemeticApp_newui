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
                                $filters = ['purchase_amount_asc', 'purchase_amount_desc', 'cashback_amount_asc', 'cashback_amount_desc', 'date_asc', 'date_desc'];
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
                                    <label class="input-label">{{trans('admin/main.type')}}</label>
                                    <select name="target_type" class="form-control populate">
                                        <option value="">{{trans('admin/main.all')}}</option>

                                        @foreach(\App\Models\CashbackRule::$targetTypes as $type)
                                            <option value="{{ $type }}" @if(request()->get('target_type') == $type) selected @endif>{{ trans('update.target_types_'.$type) }}</option>
                                        @endforeach
                                    </select>
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
                                    <a href="{{ getAdminPanelUrl('/cashback/transactions/excel?'. http_build_query(request()->all())) }}" class="btn btn-primary">{{ trans('admin/main.export_xls') }}</a>
                                </div>
                            @endcan
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14 ">
                                    <tr>
                                        <th class="text-left">{{ trans('admin/main.user') }}</th>
                                        <th class="text-left">{{trans('update.product')}}</th>
                                        <th class="">{{trans('admin/main.description')}}</th>
                                        <th>{{trans('admin/main.amount')}}</th>
                                        <th>{{trans('update.cashback_amount')}}</th>
                                        <th>{{trans('admin/main.date')}}</th>
                                        <th>{{trans('admin/main.status')}}</th>
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

                                            <td class="text-left">
                                                <div class="">
                                                    @if(!empty($transaction->webinar_id))
                                                        <a href="{{ !empty($transaction->webinar) ? $transaction->webinar->getUrl() : '' }}"
                                                           target="_blank" class="font-14">#{{ $transaction->webinar_id }}-{{ !empty($transaction->webinar) ? $transaction->webinar->title : '' }}</a>
                                                        <span class="d-block font-12">{{ trans('update.target_types_courses') }}</span>
                                                    @elseif(!empty($transaction->bundle_id))
                                                        <a href="{{ !empty($transaction->bundle) ? $transaction->bundle->getUrl() : '' }}"
                                                           target="_blank" class="font-14">#{{ $transaction->bundle_id }}-{{ !empty($transaction->bundle) ? $transaction->bundle->title : '' }}</a>
                                                        <span class="d-block font-12">{{ trans('update.target_types_bundles') }}</span>
                                                    @elseif(!empty($transaction->product_id))
                                                        <a href="{{ !empty($transaction->product) ? $transaction->product->getUrl() : '' }}"
                                                           target="_blank" class="font-14">#{{ $transaction->product_id }}-{{ !empty($transaction->product) ? $transaction->product->title : '' }}</a>
                                                        <span class="d-block font-12">{{ trans('update.target_types_store_products') }}</span>
                                                    @elseif(!empty($transaction->meeting_time_id))
                                                        <div class="font-14">#{{ $transaction->meeting_time_id }} {{ trans('admin/main.meeting') }}</div>
                                                        <span class="d-block font-12">{{ trans('update.target_types_meetings') }}</span>
                                                    @elseif(!empty($transaction->subscribe_id))
                                                        <span class="font-14">{{ trans('admin/main.purchased_subscribe') }}</span>
                                                        <span class="d-block font-12">{{ trans('update.target_types_subscription_packages') }}</span>
                                                    @elseif(!empty($transaction->promotion_id))
                                                        <span class="font-14">{{ trans('admin/main.purchased_promotion') }}</span>
                                                    @elseif(!empty($transaction->registration_package_id))
                                                        <span class="font-14">{{ trans('update.purchased_registration_package') }}</span>
                                                        <span class="d-block font-12">{{ trans('update.target_types_registration_packages') }}</span>
                                                    @else
                                                        ---
                                                    @endif
                                                </div>
                                            </td>

                                            <td>{{ $transaction->description }}</td>

                                            <td>
                                                {{ $transaction->purchase_amount ? handlePrice($transaction->purchase_amount) : '-' }}
                                            </td>

                                            <td>
                                                {{ handlePrice($transaction->amount) }}
                                            </td>

                                            <td class="font-12">{{ dateTimeFormat($transaction->created_at, 'j M Y') }}</td>

                                            <td class="">
                                                @if($transaction->system)
                                                    <span class="font-12 text-danger">{{ trans('admin/main.refund') }}</span>
                                                @else
                                                    <span class="font-12 text-success">{{ trans('update.successful') }}</span>
                                                @endif
                                            </td>

                                            <td class="text-center mb-2" width="120">
                                                @can('admin_cashback_transactions')
                                                    @if(!$transaction->system)
                                                        @include('admin.includes.delete_button',[
                                                                'url' => getAdminPanelUrl('/cashback/transactions/'. $transaction->id .'/refund'),
                                                                'tooltip' => trans('admin/main.refund'),
                                                                'btnIcon' => 'fa-times-circle'
                                                            ])
                                                    @endif
                                                @endcan
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
