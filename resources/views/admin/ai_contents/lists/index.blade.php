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

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-object-group"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('update.total_generated')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalGenerated }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-file-word"></i></div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('update.text_generated')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ $textGenerated }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="fas fa-file-image"></i></div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('update.image_generated')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ $imageGenerated }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-users"></i></div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('admin/main.users')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ $usersCount }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        {{--<div class="card-header">

                        </div>--}}

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th class="text-left">{{ trans('admin/main.user') }}</th>
                                        <th>{{ trans('update.service_type') }}</th>
                                        <th>{{ trans('update.service') }}</th>
                                        <th>{{ trans('update.keyword') }}</th>
                                        <th>{{ trans('auth.language') }}</th>
                                        <th>{{ trans('update.generated_date') }}</th>
                                        <th width="120">{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($contents as $content)
                                        <tr>

                                            <td class="text-left">
                                                {{ !empty($content->user) ? $content->user->full_name : '' }}
                                                <div class="text-primary text-small font-600-bold">ID : {{  !empty($content->user) ? $content->user->id : '' }}</div>
                                            </td>

                                            <td>
                                                {{ trans($content->service_type) }}
                                            </td>

                                            <td>
                                                @if(!empty($content->template))
                                                    {{ $content->template->title }}
                                                @else
                                                    {{ trans('update.custom') }}
                                                @endif
                                            </td>

                                            <td>
                                                <span class="">{{ truncate($content->keyword, 100) }}</span>
                                            </td>

                                            <td>
                                                <span class="">{{ truncate($content->language, 100) }}</span>
                                            </td>

                                            <td>{{ dateTimeFormat($content->created_at, 'j F Y H:i') }}</td>

                                            <td>
                                                <input type="hidden" class="js-prompt" value="{{ $content->prompt }}">
                                                <input type="hidden" class="js-result" value="{{ $content->result }}">


                                                <a href="#" class="js-view-content btn-transparent  text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('public.view') }}">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                @can('admin_users_edit')
                                                    <a href="{{ getAdminPanelUrl() }}/users/{{ $content->user_id }}/edit" class="btn-transparent mx-1 text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('update.edit_user') }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endcan

                                                @can('admin_sales_refund')
                                                    @include('admin.includes.delete_button',[
                                                                'url' => getAdminPanelUrl("/ai-contents/{$content->id}/delete"),
                                                                'tooltip' => trans('admin/main.delete'),
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
                            {{ $contents->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Modal -->
    <div class="modal fade" id="contentModal" tabindex="-1" aria-labelledby="contactMessageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactMessageLabel">{{ trans('update.generated_content') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="js-prompt-card">
                        <h6 class="fs-12">{{ trans('update.prompt') }}:</h6>
                        <p class=""></p>
                    </div>

                    {{-- Text Generated --}}
                    <div id="generatedTextContents" class="d-none"></div>

                    <div class="js-text-generated-modal mt-20 p-15 bg-info-light border-gray300 rounded-sm d-none">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="font-14 text-gray">{{ trans('update.generated_content') }}</h4>

                            <div class="form-group mb-0">
                                <button type="button" class="btn-transparent d-flex align-items-center js-copy-content-modal" data-toggle="tooltip" data-placement="top" title="{{ trans('public.copy') }}" data-copy-text="{{ trans('public.copy') }}" data-done-text="{{ trans('public.done') }}">
                                    <i data-feather="copy" width="18" height="18" class="text-gray"></i>
                                    <span class="text-gray font-12 ml-5">{{ trans('public.copy') }}</span>
                                </button>
                            </div>
                        </div>

                        <div class="mt-15 font-14 text-gray js-content-modal"></div>
                    </div>


                    {{-- Text Generated --}}
                    <div class="js-image-generated-modal mt-20 p-15 bg-info-light border-gray300 rounded-sm d-none">
                        <div class="">
                            <h4 class="font-14 text-gray">{{ trans('update.generated_content') }}</h4>
                            <p class="mt-1 text-gray font-12">{{ trans('update.click_on_images_to_download_them') }}</p>
                        </div>

                        <div class="js-content-modal mt-15 d-flex-center flex-wrap">

                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('admin/main.close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script>
        var generatedContentLang = '{{ trans('update.generated_content') }}';
        var copyLang = '{{ trans('public.copy') }}';
        var doneLang = '{{ trans('public.done') }}';
    </script>

    <script src="/assets/default/js/admin/ai_contents_lists.min.js"></script>
@endpush
