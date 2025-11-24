@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <form action="{{ getAdminPanelUrl('/certificates/settings') }}" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="page" value="general">
                                        <input type="hidden" name="name" value="{{ \App\Models\Setting::$certificateSettingsName }}">
                                        <input type="hidden" name="locale" value="{{ \App\Models\Setting::$defaultSettingsLocale }}">

                                        <div class="form-group custom-switches-stacked">
                                            <label class="custom-switch pl-0 d-flex align-items-center">
                                                <input type="hidden" name="status" value="0">
                                                <input type="checkbox" name="status" id="statusSwitch" value="1" {{ (!empty($values) and !empty($values['status']) and $values['status']) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                                <span class="custom-switch-indicator"></span>
                                                <label class="custom-switch-description mb-0 cursor-pointer" for="statusSwitch">{{ trans('admin/main.active') }}</label>
                                            </label>
                                            <div class="text-muted text-small">{{ trans('update.certificate_setting_active_hint') }}</div>
                                        </div>

                                        {{--<div class="form-group">
                                            <label class="input-label">{{ trans('update.certificate_id') }}</label>
                                            <input type="text" name="certificate_id" id="certificate_id" value="{{ (!empty($values) and !empty($values['certificate_id'])) ? $values['certificate_id'] : old('certificate_id') }}" class="form-control"/>
                                        </div>--}}

                                        <div class="form-group">
                                            <label class="input-label">{{ trans('update.ltr_font') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="input-group-text admin-file-manager " data-input="ltr_font" data-preview="holder">
                                                        <i class="fa fa-upload"></i>
                                                    </button>
                                                </div>
                                                <input type="text" name="ltr_font" id="ltr_font" value="{{ (!empty($values) and !empty($values['ltr_font'])) ? $values['ltr_font'] : old('ltr_font') }}" class="form-control"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="input-label">{{ trans('update.rtl_font') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="input-group-text admin-file-manager " data-input="rtl_font" data-preview="holder">
                                                        <i class="fa fa-upload"></i>
                                                    </button>
                                                </div>
                                                <input type="text" name="rtl_font" id="rtl_font" value="{{ (!empty($values) and !empty($values['rtl_font'])) ? $values['rtl_font'] : old('rtl_font') }}" class="form-control"/>
                                            </div>
                                        </div>

                                        <div class="mb-5">
                                            <h5>{{ trans('update.certificate_api_settings') }}</h5>

                                            <div class="form-group">
                                                <label>{{ trans('update.user_id') }}</label>
                                                <input type="text" name="certificate_api_user_id" value="{{ (!empty($values) and !empty($values['certificate_api_user_id'])) ? $values['certificate_api_user_id'] : old('certificate_api_user_id') }}" class="form-control "/>
                                                <p class="text-muted font-12 mt-1">{{ trans('update.certificate_api_user_id_hint') }}</p>
                                            </div>

                                            <div class="form-group">
                                                <label>{{ trans('update.api_key') }}</label>
                                                <input type="text" name="certificate_api_key" value="{{ (!empty($values) and !empty($values['certificate_api_key'])) ? $values['certificate_api_key'] : old('certificate_api_key') }}" class="form-control "/>
                                                <p class="text-muted font-12 mt-1">{{ trans('update.certificate_api_key_hint') }}</p>
                                            </div>

                                        </div>


                                        <button type="submit" class="btn btn-primary mt-1">{{ trans('admin/main.submit') }}</button>
                                    </form>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
@endpush
