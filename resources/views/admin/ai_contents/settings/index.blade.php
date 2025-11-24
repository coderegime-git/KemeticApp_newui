@extends('admin.layouts.app')

@push('libraries_top')

@endpush

@php
    $values = !empty($setting) ? $setting->value : null;

    if (!empty($values)) {
        $values = json_decode($values, true);
    }
@endphp


@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('update.settings') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a></div>
                <div class="breadcrumb-item">{{ trans('update.settings') }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <form action="{{ getAdminPanelUrl('/settings/main') }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="page" value="general">
                                <input type="hidden" name="name" value="{{ \App\Models\Setting::$aiContentsSettingsName }}">
                                <input type="hidden" name="locale" value="{{ \App\Models\Setting::$defaultSettingsLocale }}">

                                @php
                                    $switches = ['status', 'active_for_admin_panel', 'active_for_organization_panel', 'active_for_instructor_panel']
                                @endphp

                                @foreach($switches as $switch)
                                    <div class="form-group custom-switches-stacked">
                                        <label class="custom-switch pl-0 d-flex align-items-center">
                                            <input type="hidden" name="value[{{ $switch }}]" value="0">
                                            <input type="checkbox" name="value[{{ $switch }}]" id="{{ $switch }}Switch" value="1" {{ (!empty($values) and !empty($values[$switch]) and $values[$switch]) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                            <span class="custom-switch-indicator"></span>
                                            <label class="custom-switch-description mb-0 cursor-pointer" for="{{ $switch }}Switch">{{ ($switch == 'status') ? trans('admin/main.active') : trans("update.{$switch}") }}</label>
                                        </label>
                                        <div class="text-muted text-small">{{ trans("update.ai_content_setting_{$switch}_hint") }}</div>
                                    </div>
                                @endforeach

                                <div class="form-group">
                                    <label class="control-label">{{ trans('update.secret_key') }}</label>
                                    <input type="text" name="value[secret_key]" class="form-control" value="{{ (!empty($values) and !empty($values['secret_key'])) ? $values['secret_key'] : '' }}">
                                </div>


                                <div class="form-group custom-switches-stacked">
                                    <label class="custom-switch pl-0 d-flex align-items-center">
                                        <input type="hidden" name="value[activate_text_service_type]" value="0">
                                        <input type="checkbox" name="value[activate_text_service_type]" id="activate_text_service_typeSwitch" value="1" {{ (!empty($values) and !empty($values['activate_text_service_type']) and $values['activate_text_service_type']) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                        <span class="custom-switch-indicator"></span>
                                        <label class="custom-switch-description mb-0 cursor-pointer" for="activate_text_service_typeSwitch">{{  trans('update.activate_text_service_type') }}</label>
                                    </label>
                                </div>

                                <div class="js-text-fields {{ (!empty($values) and !empty($values['activate_text_service_type']) and $values['activate_text_service_type']) ? '' : 'd-none' }}">

                                    <div class="form-group ">
                                        <label class="control-label">{!! trans('update.text_service_type') !!}</label>
                                        <select name="value[text_service_type]" class=" form-control">
                                            <option value="">{{ trans('update.select_text_service_type') }}</option>
                                            @foreach(\App\Enums\AiTextServices::types as $typ)
                                                <option value="{{ $typ }}" {{ (!empty($values) and !empty(!empty($values['text_service_type'])) and $values['text_service_type'] == $typ) ? 'selected' : '' }}>{{ trans("update.{$typ}") }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group ">
                                        <label class="control-label">{!! trans('update.number_of_text_generated_per_request') !!}</label>
                                        <select name="value[number_of_text_generated_per_request]" class=" form-control">

                                            @foreach([1,2,3,4,5,6,7,8,9,10] as $num)
                                                <option value="{{ $num }}" {{ (!empty($values) and !empty(!empty($values['number_of_text_generated_per_request'])) and $values['number_of_text_generated_per_request'] == $num) ? 'selected' : '' }}>{{ $num }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">{{ trans('update.max_tokens') }}</label>
                                        <input type="number" name="value[max_tokens]" class="form-control" value="{{ (!empty($values) and !empty($values['max_tokens'])) ? $values['max_tokens'] : '' }}" >
                                    </div>

                                </div>

                                <div class="form-group custom-switches-stacked">
                                    <label class="custom-switch pl-0 d-flex align-items-center">
                                        <input type="hidden" name="value[activate_image_service_type]" value="0">
                                        <input type="checkbox" name="value[activate_image_service_type]" id="activate_image_service_typeSwitch" value="1" {{ (!empty($values) and !empty($values['activate_image_service_type']) and $values['activate_image_service_type']) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                        <span class="custom-switch-indicator"></span>
                                        <label class="custom-switch-description mb-0 cursor-pointer" for="activate_image_service_typeSwitch">{{  trans('update.activate_image_service_type') }}</label>
                                    </label>
                                </div>

                                <div class="js-image-fields {{ (!empty($values) and !empty($values['activate_image_service_type']) and $values['activate_image_service_type']) ? '' : 'd-none' }}">

                                    <div class="form-group ">
                                        <label class="control-label">{!! trans('update.number_of_images_generated_per_request') !!}</label>
                                        <select name="value[number_of_images_generated_per_request]" class=" form-control">

                                            @foreach([1,2,3,4,5,6,7,8,9,10] as $num)
                                                <option value="{{ $num }}" {{ (!empty($values) and !empty(!empty($values['number_of_images_generated_per_request'])) and $values['number_of_images_generated_per_request'] == $num) ? 'selected' : '' }}>{{ $num }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>

                                <button type="submit" class="btn btn-primary mt-1">{{ trans('admin/main.submit') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('scripts_bottom')
    <script src="/assets/default/js/admin/ai_content_settings.min.js"></script>
@endpush
