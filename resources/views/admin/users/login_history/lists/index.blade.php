@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">
@endpush


@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a>{{ trans('admin/main.users') }}</a></div>
                <div class="breadcrumb-item"><a href="#">{{ $pageTitle }}</a></div>
            </div>
        </div>
    </section>

    <div class="section-body">
        <section class="card">
            <div class="card-body">
                <form method="get" class="mb-0">

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('admin/main.search') }}</label>
                                <input name="search" type="text" class="form-control" value="{{ request()->get('search') }}" placeholder="{{ trans('public.search_user') }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('update.session_status') }}</label>
                                <select name="session_status" class="form-control">
                                    <option value="">{{ trans('update.choose_session_status') }}</option>
                                    <option value="open" {{ (request()->get('session_status') == "open") ? 'selected' : '' }}>{{ trans('admin/main.open') }}</option>
                                    <option value="ended" {{ (request()->get('session_status') == "ended") ? 'selected' : '' }}>{{ trans('update.ended') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="input-label">{{ trans('update.start_login_date') }}</label>
                                <div class="input-group">
                                    <input type="date" id="from" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="input-label">{{ trans('update.end_login_date') }}</label>
                                <div class="input-group">
                                    <input type="date" id="to" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group mt-1">
                                <label class="input-label mb-4"> </label>
                                <input type="submit" class="text-center btn btn-primary w-100" value="{{ trans('admin/main.show_results') }}">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <div class="card">
        <div class="card-header">

            @can('admin_user_login_history_export')
                <a href="{{ getAdminPanelUrl("/users/login-history/export") }}?{{ http_build_query(request()->all()) }}" class="btn btn-primary">{{ trans('admin/main.export_xls') }}</a>
            @endcan

            <div class="h-10"></div>
        </div>

        <div class="card-body">
            <div class="table-responsive text-center">
                <table class="table table-striped font-14">
                    <tr>
                        <th>ID</th>
                        <th class="text-left">{{ trans('admin/main.user') }}</th>
                        <th>{{ trans('update.os') }}</th>
                        <th>{{ trans('update.browser') }}</th>
                        <th>{{ trans('update.device') }}</th>
                        <th>{{ trans('update.ip_address') }}</th>
                        <th>{{ trans('update.country') }}</th>
                        <th>{{ trans('update.city') }}</th>
                        <th>{{ trans('update.lat,long') }}</th>
                        <th>{{ trans('update.session_start') }}</th>
                        <th>{{ trans('update.session_end') }}</th>
                        <th>{{ trans('public.duration') }}</th>
                        <th width="120">{{ trans('admin/main.actions') }}</th>
                    </tr>

                    @foreach($sessions as $session)

                        <tr>
                            <td>{{ $session->user->id }}</td>
                            <td class="text-left">
                                <div class="d-flex align-items-center">
                                    <figure class="avatar mr-2">
                                        <img src="{{ $session->user->getAvatar() }}" alt="{{ $session->user->full_name }}">
                                    </figure>
                                    <div class="media-body ml-1">
                                        <div class="mt-0 mb-1 font-weight-bold">{{ $session->user->full_name }}</div>

                                        @if($session->user->mobile)
                                            <div class="text-primary text-small font-600-bold">{{ $session->user->mobile }}</div>
                                        @endif

                                        @if($session->user->email)
                                            <div class="text-primary text-small font-600-bold">{{ $session->user->email }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td>{{ $session->os ?? '-' }}</td>

                            <td>{{ $session->browser ?? '-' }}</td>

                            <td>{{ $session->device ?? '-' }}</td>

                            <td>{{ $session->ip ?? '-' }}</td>

                            <td>{{ $session->country ?? '-' }}</td>

                            <td>{{ $session->city ?? '-' }}</td>

                            <td>{{ $session->location ?? '-' }}</td>

                            <td>{{ dateTimeFormat($session->session_start_at, 'j M Y H:i') }}</td>

                            <td>{{ !empty($session->session_end_at) ? dateTimeFormat($session->session_end_at, 'j M Y H:i') : '-' }}</td>

                            <td>{{ $session->getDuration() }}</td>

                            <td class="text-center mb-2" width="120">
                                @can('admin_users_impersonate')
                                    @if(!$session->user->isAdmin())
                                        <a href="{{ getAdminPanelUrl() }}/users/{{ $session->user->id }}/impersonate" target="_blank" class="btn-transparent  text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('admin/main.login') }}">
                                            <i class="fa fa-user-shield"></i>
                                        </a>
                                    @endif
                                @endcan

                                @can('admin_users_edit')
                                    <a href="{{ getAdminPanelUrl() }}/users/{{ $session->user->id }}/edit" target="_blank" class="btn-transparent  text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('update.user_edit') }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                @endcan

                                @can('admin_user_ip_restriction_create')
                                    @if(!empty($session->ip))
                                        <a href="#!" data-path="{{ getAdminPanelUrl("/users/ip-restriction/get-form?full_ip={$session->ip}") }}" class="js-add-restriction btn-transparent  text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('update.block_ip') }}">
                                            <i class="fa fa-ban"></i>
                                        </a>
                                    @endif
                                @endcan

                                @can('admin_user_login_history_end_session')
                                    @if(empty($session->session_end_at))
                                        @include('admin.includes.delete_button',[
                                            'url' => getAdminPanelUrl().'/users/login-history/'.$session->id.'/end-session' ,
                                            'btnIcon' => 'fa-arrow-down',
                                            'tooltip' => trans('update.end_session')
                                           ])
                                    @endif
                                @endcan

                                @can('admin_user_login_history_delete')
                                    @include('admin.includes.delete_button',[
                                            'url' => getAdminPanelUrl().'/users/login-history/'.$session->id.'/delete' ,
                                           ])
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </table>
            </div>
        </div>

        <div class="card-footer text-center">
            {{ $sessions->appends(request()->input())->links() }}
        </div>
    </div>


    <section class="card">
        <div class="card-body">
            <div class="section-title ml-0 mt-0 mb-3"><h5>{{trans('admin/main.hints')}}</h5></div>
            <div class="row">
                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('update.login_history_hint_title_1')}}</div>
                        <div class=" text-small font-600-bold">{{trans('update.login_history_hint_description_1')}}</div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('update.login_history_hint_title_2')}}</div>
                        <div class=" text-small font-600-bold">{{trans('update.login_history_hint_description_2')}}</div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('update.login_history_hint_title_3')}}</div>
                        <div class="text-small font-600-bold">{{trans('update.login_history_hint_description_3')}}</div>
                    </div>
                </div>


            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>

    <script src="/assets/default/js/admin/ip-restriction.min.js"></script>
@endpush
