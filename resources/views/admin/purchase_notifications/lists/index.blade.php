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

            <div class="card">
                <div class="card-header">
                    @can('admin_purchase_notifications_create')
                        <div class="text-right">
                            <a href="{{ getAdminPanelUrl("/purchase_notifications/create") }}" class="btn btn-primary">{{ trans('update.new_notification') }}</a>
                        </div>
                    @endcan
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped font-14" id="datatable-basic">

                            <tr>
                                <th class="text-left">{{ trans('admin/main.title') }}</th>
                                <th class="text-center">{{ trans('update.displayed_time') }}</th>
                                <th class="text-center">{{ trans('update.contents') }}</th>
                                <th class="text-center">{{ trans('admin/main.users') }}</th>
                                <th class="text-center">{{ trans('admin/main.start_date') }}</th>
                                <th class="text-center">{{ trans('admin/main.end_date') }}</th>
                                <th class="text-center">{{ trans('admin/main.status') }}</th>
                                <th>{{ trans('public.controls') }}</th>
                            </tr>

                            @foreach($notifications as $notification)
                                <tr>
                                    <td>{{ $notification->title }}</td>

                                    <td class="text-center">{{ $notification->display_time }}</td>

                                    <td class="text-center">{{ $notification->webinars_count + $notification->bundles_count + $notification->products_count }}</td>

                                    <td class="text-center">
                                        {{ !empty($notification->users) ? count(explode(',', $notification->users)) : 0 }}
                                    </td>

                                    <td class="text-center">{{ !empty($notification->start_at) ? dateTimeFormat($notification->start_at, 'j M Y') : '-' }}</td>

                                    <td class="text-center">{{ !empty($notification->end_at) ? dateTimeFormat($notification->end_at, 'j M Y') : '-' }}</td>

                                    <td class="text-center">
                                        @if(!$notification->enable)
                                            <span class="text-danger">{{ trans('admin/main.disabled') }}</span>
                                        @elseif(!empty($notification->start_at) and $notification->start_at > time())
                                            <span class="text-warning">{{ trans('admin/main.pending') }}</span>
                                        @elseif(!empty($notification->end_at) and $notification->end_at < time())
                                            <span class="text-danger">{{ trans('panel.expired') }}</span>
                                        @else
                                            <span class="text-success">{{ trans('admin/main.active') }}</span>
                                        @endif
                                    </td>

                                    <td width="100">

                                        @can('admin_purchase_notifications_edit')
                                            <a href="{{ getAdminPanelUrl("/purchase_notifications/{$notification->id}/edit") }}" class="btn-transparent  text-primary mr-1" data-toggle="tooltip" data-placement="top" title="{{ trans('admin/main.edit') }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endcan

                                        @can('admin_purchase_notifications_delete')
                                            @include('admin.includes.delete_button',['url' => getAdminPanelUrl("/purchase_notifications/{$notification->id}/delete"),'btnClass' => ''])
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach

                        </table>
                    </div>
                </div>

                <div class="card-footer text-center">
                    {{ $notifications->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </section>


@endsection
