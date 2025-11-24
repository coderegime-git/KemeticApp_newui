@extends('admin.layouts.app')

@push('libraries_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('update.forms') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('update.forms') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th>{{ trans('admin/main.title') }}</th>
                                        <th>{{ trans('update.inputs') }}</th>
                                        <th>{{ trans('update.submissions') }}</th>
                                        <th>{{ trans('admin/main.users') }}</th>
                                        <th>{{ trans('update.last_submission') }}</th>
                                        <th>{{ trans('admin/main.created_date') }}</th>
                                        <th>{{ trans('admin/main.status') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
                                    </tr>
                                    @foreach($forms as $form)
                                        <tr>
                                            <td>{{ $form->title }}</td>

                                            <td>{{ $form->fields_count }}</td>

                                            <td>{{ $form->submissions_count }}</td>

                                            <td>{{ $form->users_count }}</td>

                                            <td>{{ dateTimeFormat(time(), 'j M Y') }}</td>

                                            <td>{{ dateTimeFormat($form->created_at, 'j M Y') }}</td>

                                            <td>
                                                @if($form->enable)
                                                    <span class="">{{ trans('admin/main.active') }}</span>
                                                @else
                                                    <span class="">{{ trans('admin/main.inactive') }}</span>
                                                @endif
                                            </td>

                                            <td>

                                                <a href="/forms/{{ $form->url }}" target="_blank"
                                                   class="btn-transparent btn-sm text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('update.show_form') }}">
                                                    <i class="fa fa-link"></i>
                                                </a>


                                                @can('admin_forms_submissions')
                                                    <a href="{{ getAdminPanelUrl() }}/forms/submissions?form={{ $form->id }}"
                                                       class="btn-transparent btn-sm text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('update.show_submissions') }}">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                @endcan

                                                @can('admin_forms_edit')
                                                    <a href="{{ getAdminPanelUrl() }}/forms/{{ $form->id }}/edit"
                                                       class="btn-transparent btn-sm text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('admin/main.edit') }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endcan
                                                @can('admin_forms_delete')
                                                    @include('admin.includes.delete_button',['url' => getAdminPanelUrl().'/forms/'.$form->id.'/delete'])
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $forms->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
