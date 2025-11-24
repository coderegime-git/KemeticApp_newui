@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">
    <link href="/assets/default/vendors/sortable/jquery-ui.min.css"/>
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    <a href="{{ getAdminPanelUrl('/forms') }}">{{ trans('update.forms') }}</a>
                </div>
                <div class="breadcrumb-item">{{ !empty($form) ?trans('/admin/main.edit'): trans('admin/main.new') }}</div>
            </div>
        </div>


        <div class="section-body">
            <div class="card">
                <div class="card-body">

                    <form method="post" action="{{ getAdminPanelUrl('/forms/'. (!empty($form) ? $form->id.'/update' : 'store')) }}">
                        {{ csrf_field() }}

                        {{-- Basic Information --}}
                        <section>
                            <h2 class="section-title after-line">{{ trans('public.basic_information') }}</h2>

                            @include('admin.forms.create.includes.basic_information')
                        </section>

                        {{-- Welcome Message --}}
                        <section class="mt-3">
                            <h2 class="section-title after-line">{{ trans('update.welcome_message') }}</h2>

                            @include('admin.forms.create.includes.welcome_message')
                        </section>

                        {{-- Tank You Message --}}
                        <section class="mt-3">
                            <h2 class="section-title after-line">{{ trans('update.tank_you_message') }}</h2>

                            @include('admin.forms.create.includes.tank_you_message')
                        </section>

                        {{-- Form Fields --}}
                        <section class="mt-3">
                            <h2 class="section-title after-line">{{ trans('update.form_fields') }}</h2>

                            @if(!empty($form))
                                @include('admin.forms.create.includes.form_fields')
                            @else
                                <div class="alert alert-warning">{{ trans('update.after_saving_the_form_you_can_create_the_fields') }}</div>
                            @endif
                        </section>

                        @if(!empty($form))
                            <div class="mb-20">
                                <div class="form-group mt-30 mb-0 d-flex align-items-center">
                                    <label class="" for="statusSwitch">{{ trans('admin/main.active') }}</label>
                                    <div class="custom-control custom-switch ml-3">
                                        <input type="checkbox" name="enable" class="custom-control-input" id="statusSwitch" {{ ($form->enable) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="statusSwitch"></label>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <button type="submit" class="btn btn-success">{{ trans('admin/main.save_change') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script>
        var fieldSaveSuccessLang = '{{ trans('update.form_field_data_stored_successfully') }}'
        var chooseTitleLang = '{{ trans('admin/main.choose_title') }}'
    </script>
    <script src="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
    <script src="/assets/default/vendors/feather-icons/dist/feather.min.js"></script>
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>

    <script src="/assets/default/js/admin/create_forms.min.js"></script>
@endpush
