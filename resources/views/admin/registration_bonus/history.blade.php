@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    {{ trans('update.registration_bonus') }}
                </div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('update.achieved_users')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ $achievedUsers }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-unlock"></i>
                        </div>

                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('update.unlocked_bonus_users')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ $unlockedBonusUsers }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-money-bill"></i></div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('update.total_bonus')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ handlePrice($totalBonus) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-money-bill-wave"></i></div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('update.unlocked_bonus')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ handlePrice($unlockedBonus) }}
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
                                $filters = ['registration_date_asc', 'registration_date_desc', 'referred_users_asc', 'referred_users_desc', 'bonus_asc', 'bonus_desc'];
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
                                    <label class="input-label">{{trans('admin/main.user')}}</label>
                                    <select name="role_id" class="form-control select2" data-allow-clear="true"
                                            data-placeholder="{{ trans('update.select_user_role') }}">
                                        <option value="">{{ trans('admin/main.all') }}</option>

                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ (request()->get('role_id') == $role->id) ? 'selected' : '' }}>{{ $role->caption }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            {{--<div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('update.bonus_wallet')}}</label>
                                    <select name="bonus_wallet" class="form-control populate">
                                        <option value="">{{trans('admin/main.all')}}</option>

                                        <option value="income_wallet" {{ (request()->get('bonus_wallet') == 'income_wallet') ? 'selected' : '' }}>{{ trans('update.income_wallet') }}</option>
                                        <option value="balance_wallet" {{ (request()->get('bonus_wallet') == 'balance_wallet') ? 'selected' : '' }}>{{ trans('update.balance_wallet') }}</option>
                                    </select>
                                </div>
                            </div>--}}

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('update.bonus_status')}}</label>
                                    <select name="bonus_status" class="form-control populate">
                                        <option value="">{{trans('admin/main.all')}}</option>

                                        <option value="locked" {{ (request()->get('bonus_status') == 'locked') ? 'selected' : '' }}>{{ trans('update.locked') }}</option>
                                        <option value="unlocked" {{ (request()->get('bonus_status') == 'unlocked') ? 'selected' : '' }}>{{ trans('update.unlocked') }}</option>
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
                            @can('admin_registration_bonus_export_excel')
                                <div class="text-right">
                                    <a href="{{ getAdminPanelUrl('/registration_bonus/export?'. http_build_query(request()->all())) }}" class="btn btn-primary">{{ trans('admin/main.export_xls') }}</a>
                                </div>
                            @endcan
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14 ">
                                    <tr>
                                        <th class="text-left">{{ trans('update.user_id') }}</th>
                                        <th class="text-left">{{ trans('admin/main.name') }}</th>
                                        <th class="text-left">{{trans('admin/main.role')}}</th>

                                        <th>{{trans('update.bonus')}}</th>

                                        @if (!empty($registrationBonusSettings['unlock_registration_bonus_with_referral']) and !empty($registrationBonusSettings['number_of_referred_users']))
                                            <th>{{trans('update.referred_users')}}</th>

                                            @if (!empty($registrationBonusSettings['enable_referred_users_purchase']))
                                                <th>{{trans('update.referred_purchases')}}</th>
                                            @endif
                                        @endif

                                        {{--<th>{{trans('update.bonus_wallet')}}</th>--}}
                                        <th>{{trans('update.registration_date')}}</th>
                                        <th>{{trans('update.bonus_status')}}</th>
                                        <th width="120">{{trans('admin/main.actions')}}</th>
                                    </tr>

                                    @foreach($users as $user)
                                        <tr class="text-center">
                                            <td>{{ $user->id }}</td>

                                            <td class="text-left">
                                                <div class="d-flex align-items-center">
                                                    <figure class="avatar mr-2">
                                                        <img src="{{ $user->getAvatar() }}" alt="{{ $user->full_name }}">
                                                    </figure>
                                                    <div class="media-body ml-1">
                                                        <div class="mt-0 mb-1 font-weight-bold">{{ $user->full_name }}</div>

                                                        @if($user->mobile)
                                                            <div class="text-primary text-small font-600-bold">{{ $user->mobile }}</div>
                                                        @endif

                                                        @if($user->email)
                                                            <div class="text-primary text-small font-600-bold">{{ $user->email }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="text-left">{{ $user->role->caption }}</td>

                                            <td>
                                                {{ handlePrice($user->registration_bonus_amount ?? 0) }}
                                            </td>

                                            @if (!empty($registrationBonusSettings['unlock_registration_bonus_with_referral']) and !empty($registrationBonusSettings['number_of_referred_users']))
                                                <td>{{ $user->referred_users }}</td>

                                                @if (!empty($registrationBonusSettings['enable_referred_users_purchase']))
                                                    <td>{{ $user->referred_purchases }}</td>
                                                @endif
                                            @endif


                                            {{--<td class="font-12">{{ !empty($user->bonus_wallet) ? trans('update.'.$user->bonus_wallet) : '-' }}</td>--}}

                                            <td class="font-12">{{ dateTimeFormat($user->created_at, 'j M Y') }}</td>

                                            <td class="font-12">{{ $user->bonus_status }}</td>


                                            <td class="text-center mb-2" width="120">
                                                @can('admin_users_impersonate')
                                                    <a href="{{ getAdminPanelUrl() }}/users/{{ $user->id }}/impersonate" target="_blank" class="btn-transparent  text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('admin/main.login') }}">
                                                        <i class="fa fa-user-shield"></i>
                                                    </a>
                                                @endcan

                                                @can('admin_users_edit')
                                                    <a href="{{ getAdminPanelUrl() }}/users/{{ $user->id }}/edit" class="btn-transparent  text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('admin/main.edit') }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endcan

                                                @can('admin_users_edit')
                                                    @include('admin.includes.delete_button',[
                                                            'url' => getAdminPanelUrl("/users/{$user->id}/disable_registration_bonus"),
                                                            'tooltip' => trans('update.disable_registration_bonus'),
                                                            'btnIcon' => 'fa-times-circle'
                                                        ])
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $users->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

