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
            @include("admin.abandoned_cart.rules.lists.top_stats")

            <div class="card">
                <div class="card-header">
                    @can('admin_abandoned_cart_rules')
                        <div class="text-right">
                            <a href="{{ getAdminPanelUrl("/abandoned-cart/rules/create") }}" class="btn btn-primary">{{ trans('update.new_rule') }}</a>
                        </div>
                    @endcan
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped font-14" id="datatable-basic">

                            <tr>
                                <th class="text-left">{{ trans('admin/main.title') }}</th>
                                <th class="text-center">{{ trans('update.activities') }}</th>
                                <th class="text-center">{{ trans('admin/main.sales') }}</th>
                                <th class="text-center">{{ trans('update.minimum_amount') }}</th>
                                <th class="text-center">{{ trans('update.maximum_amount') }}</th>
                                <th class="text-center">{{ trans('admin/main.action') }}</th>
                                <th class="text-center">{{ trans('admin/main.start_date') }}</th>
                                <th class="text-center">{{ trans('update.expire_date') }}</th>
                                <th class="text-center">{{ trans('admin/main.status') }}</th>
                                <th>{{ trans('public.controls') }}</th>
                            </tr>

                            @foreach($rules as $rule)
                                <tr>
                                    <td>{{ $rule->title }}</td>

                                    <td class="text-center">0</td>

                                    <td class="text-center">0</td>

                                    <td class="text-center">
                                        @if(!empty($rule->minimum_cart_amount))
                                            {{ handlePrice($rule->minimum_cart_amount) }}
                                        @else
                                            {{ trans('update.unlimited') }}
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        @if(!empty($rule->maximum_cart_amount))
                                            {{ handlePrice($rule->maximum_cart_amount) }}
                                        @else
                                            {{ trans('update.unlimited') }}
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        {{ trans('update.'.$rule->action) }}
                                    </td>

                                    <td class="text-center">
                                        @if(!empty($rule->start_at))
                                            {{ dateTimeFormat($rule->start_at, 'j M Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        @if(!empty($rule->end_at))
                                            {{ dateTimeFormat($rule->end_at, 'j M Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>


                                    <td class="text-center">
                                        @if(!$rule->enable)
                                            <span class="text-danger">{{ trans('admin/main.disabled') }}</span>
                                        @elseif(!empty($rule->start_at) and $rule->start_at > time())
                                            <span class="text-warning">{{ trans('admin/main.pending') }}</span>
                                        @elseif(!empty($rule->end_at) and $rule->end_at < time())
                                            <span class="text-danger">{{ trans('panel.expired') }}</span>
                                        @else
                                            <span class="text-success">{{ trans('admin/main.active') }}</span>
                                        @endif
                                    </td>

                                    <td width="100">

                                        @can('admin_abandoned_cart_rules')
                                            <a href="{{ getAdminPanelUrl("/abandoned-cart/rules/{$rule->id}/edit") }}" class="btn-transparent  text-primary mr-1" data-toggle="tooltip" data-placement="top" title="{{ trans('admin/main.edit') }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endcan

                                        @can('admin_abandoned_cart_rules')
                                            @include('admin.includes.delete_button',['url' => getAdminPanelUrl("/abandoned-cart/rules/{$rule->id}/delete"),'btnClass' => ''])
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach

                        </table>
                    </div>
                </div>

                <div class="card-footer text-center">
                    {{ $rules->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </section>


@endsection
