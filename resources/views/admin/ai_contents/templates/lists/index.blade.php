@extends('admin.layouts.app')

@push('libraries_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('update.service_templates') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('update.service_templates') }}</div>
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
                                        <th>{{ trans('update.service_type') }}</th>
                                        <th>{{ trans('update.generated_contents') }}</th>
                                        <th>{{ trans('admin/main.status') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
                                    </tr>
                                    @foreach($templates as $template)
                                        <tr>
                                            <td>{{ $template->title }}</td>

                                            <td>{{ trans($template->type) }}</td>

                                            <td>{{ $template->contents_count ?? 0 }}</td>

                                            <td>
                                                @if($template->enable)
                                                    <span class="text-success">{{ trans('admin/main.active') }}</span>
                                                @else
                                                    <span class="text-danger">{{ trans('admin/main.inactive') }}</span>
                                                @endif
                                            </td>

                                            <td>

                                                @can('admin_ai_contents_templates_edit')
                                                    <a href="{{ getAdminPanelUrl() }}/ai-contents/templates/{{ $template->id }}/edit"
                                                       class="btn-transparent btn-sm text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('admin/main.edit') }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>

                                                    @include('admin.includes.delete_button',[
                                                          'url' => getAdminPanelUrl('/ai-contents/templates/'. $template->id.'/statusToggle'),
                                                          'tooltip' => ($template->enable ? trans('admin/main.inactive') : trans('admin/main.active')),
                                                          'btnClass' => 'mx-2',
                                                          'btnIcon' => "fa-times-circle"
                                                      ])
                                                @endcan

                                                @can('admin_ai_contents_templates_delete')
                                                    @include('admin.includes.delete_button',['url' => getAdminPanelUrl().'/ai-contents/templates/'.$template->id.'/delete'])
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $templates->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
