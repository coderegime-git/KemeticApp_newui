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

    <div class="card">
        <div class="card-header">
            @can("admin_user_ip_restriction_create")
                <button data-path="{{ getAdminPanelUrl("/users/ip-restriction/get-form") }}" class="js-add-restriction btn btn-primary">{{ trans('admin/main.add_new') }}</button>
            @endcan
        </div>

        <div class="card-body">
            <div class="table-responsive text-center">
                <table class="table table-striped font-14">
                    <tr>
                        <th width="120">{{ trans('admin/main.type') }}</th>
                        <th class="text-left" width="200">{{ trans('update.value') }}</th>
                        <th class="text-left">{{ trans('product.reason') }}</th>
                        <th class="text-left">{{ trans('update.blocked_date') }}</th>
                        <th width="120">{{ trans('admin/main.actions') }}</th>
                    </tr>

                    @foreach($restrictions as $restriction)

                        <tr>
                            <td width="120">{{ trans("update.{$restriction->type}") }}</td>


                            <td class="text-left" width="200">
                                @if($restriction->type == "country")
                                    {{ getCountriesLists($restriction->value) }}
                                @else
                                    {{ $restriction->value }}
                                @endif
                            </td>

                            <td class="text-left">{{ $restriction->reason }}</td>

                            <td class="text-left">{{ dateTimeFormat($restriction->created_at, 'j M Y H:i') }}</td>


                            <td class="text-center mb-2" width="120">

                                @can("admin_user_ip_restriction_create")
                                    <a href="{{ getAdminPanelUrl("/users/ip-restriction/{$restriction->id}/edit") }}" class="js-edit-restriction btn-transparent  text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('admin/main.edit') }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                @endcan

                                @can('admin_user_ip_restriction_delete')
                                    @include('admin.includes.delete_button',[
                                            'url' => getAdminPanelUrl("/users/ip-restriction/{$restriction->id}/delete"),
                                           ])
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </table>
            </div>
        </div>

        <div class="card-footer text-center">
            {{ $restrictions->appends(request()->input())->links() }}
        </div>
    </div>


    <section class="card">
        <div class="card-body">
            <div class="section-title ml-0 mt-0 mb-3"><h5>{{trans('admin/main.hints')}}</h5></div>
            <div class="row">
                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('update.restrictions_hint_title_1')}}</div>
                        <div class=" text-small font-600-bold">{{trans('update.restrictions_hint_description_1')}}</div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('update.restrictions_hint_title_2')}}</div>
                        <div class=" text-small font-600-bold">{{trans('update.restrictions_hint_description_2')}}</div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('update.restrictions_hint_title_3')}}</div>
                        <div class="text-small font-600-bold">{{trans('update.restrictions_hint_description_3')}}</div>
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
