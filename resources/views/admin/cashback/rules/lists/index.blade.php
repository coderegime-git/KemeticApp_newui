@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    {{ trans('update.cashback_rules') }}
                </div>
            </div>
        </div>

        {{-- Stats --}}
        @include('admin.cashback.rules.lists.stats')

        {{-- Filters --}}
        @include('admin.cashback.rules.lists.filters')

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th>{{ trans('admin/main.title') }}</th>
                                        <th class="text-center">{{ trans('update.target_type') }}</th>
                                        <th class="text-center">{{ trans('admin/main.amount') }}</th>
                                        <th class="text-center">{{ trans('public.paid_amount') }}</th>
                                        <th class="text-center">{{ trans('admin/main.users') }}</th>
                                        <th class="text-center">{{ trans('admin/main.start_date') }}</th>
                                        <th class="text-center">{{ trans('admin/main.end_date') }}</th>
                                        <th class="text-center">{{ trans('admin/main.status') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($rules as $rule)
                                        <tr>
                                            <td>
                                                <span class="d-block font-16 font-weight-500">{{ $rule->title }}</span>
                                            </td>

                                            <td>
                                                <span class="">{{ trans('update.target_types_'.$rule->target_type) }}</span>
                                            </td>

                                            <td class="text-center">
                                                {{ ($rule->amount_type == 'percent') ? $rule->amount.'%' : handlePrice($rule->amount) }}
                                            </td>

                                            <td class="text-center">0</td>

                                            <td class="text-center">0</td>

                                            <td class="text-center">{{ $rule->start_date ? dateTimeFormat($rule->start_date, 'Y M j | H:i') : '-' }}</td>

                                            <td class="text-center">{{ $rule->end_date ? dateTimeFormat($rule->end_date, 'Y M j | H:i') : trans('update.unlimited') }}</td>

                                            <td class="text-center">
                                                @if($rule->enable)
                                                    <span class="text-success">{{ trans('admin/main.active') }}</span>
                                                @else
                                                    <span class="text-danger">{{ trans('admin/main.inactive') }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                @can('admin_cashback_rules')
                                                    <a href="{{ getAdminPanelUrl("/cashback/rules/{$rule->id}/edit") }}" class="btn-sm btn-transparent text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('admin/main.edit') }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endcan

                                                @can('admin_cashback_rules')
                                                    @include('admin.includes.delete_button',['url' => getAdminPanelUrl('/cashback/rules/'. $rule->id.'/statusToggle'), 'tooltip' => ($rule->enable ? trans('admin/main.inactive') : trans('admin/main.active')), 'btnClass' => 'ml-2', 'btnIcon' => "fa-times-circle"])
                                                @endcan

                                                @can('admin_cashback_rules')
                                                    @include('admin.includes.delete_button',['url' => getAdminPanelUrl('/cashback/rules/'. $rule->id.'/delete'), 'tooltip' => trans('admin/main.delete'), 'btnClass' => 'ml-2'])
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
            </div>
        </div>
    </section>
@endsection
