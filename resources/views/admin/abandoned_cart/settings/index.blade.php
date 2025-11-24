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
                                <input type="hidden" name="name" value="{{ \App\Models\Setting::$abandonedCartSettingsName }}">
                                <input type="hidden" name="locale" value="{{ \App\Models\Setting::$defaultSettingsLocale }}">

                                @php
                                    $switches = ['status', 'reset_cart_items']
                                @endphp

                                @foreach($switches as $switch)
                                    <div class="form-group custom-switches-stacked">
                                        <label class="custom-switch pl-0 d-flex align-items-center">
                                            <input type="hidden" name="value[{{ $switch }}]" value="0">
                                            <input type="checkbox" name="value[{{ $switch }}]" id="{{ $switch }}Switch" value="1" {{ (!empty($values) and !empty($values[$switch]) and $values[$switch]) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                            <span class="custom-switch-indicator"></span>
                                            <label class="custom-switch-description mb-0 cursor-pointer" for="{{ $switch }}Switch">{{ ($switch == 'status') ? trans('admin/main.active') : trans("update.{$switch}") }}</label>
                                        </label>
                                        <div class="text-muted text-small">{{ trans("update.abandoned_cart_setting_{$switch}_hint") }}</div>
                                    </div>
                                @endforeach

                                <div class="js-reset-hours-field form-group {{ (!empty($values) and !empty($values['reset_cart_items']) and $values['reset_cart_items']) ? '' : 'd-none' }}">
                                    <label class="control-label">{{ trans('update.reset_hours') }}</label>
                                    <input type="number" name="value[reset_hours]" class="form-control" value="{{ (!empty($values) and !empty($values['reset_hours'])) ? $values['reset_hours'] : '' }}" min="0">
                                    <div class="text-muted text-small">{{ trans("update.abandoned_cart_setting_reset_hours_hint") }}</div>
                                </div>


                                <div class="form-group ">
                                    <label class="control-label">{!! trans('update.default_cart_reminder') !!}</label>
                                    <select name="value[default_cart_reminder]" class=" form-control select2">
                                        <option value="">{{ trans('update.select_default_cart_reminder') }}</option>

                                        @foreach($notificationTemplates as $notificationTemplate)
                                            <option value="{{ $notificationTemplate->id }}" {{ (!empty($values) and !empty(!empty($values['default_cart_reminder'])) and $values['default_cart_reminder'] == $notificationTemplate->id) ? 'selected' : '' }}>{{ $notificationTemplate->title }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-muted text-small mt-1">{{ trans('update.abandoned_cart_reminder_template_hint') }}</div>
                                </div>

                                <div class="form-group ">
                                    <label class="control-label">{!! trans('update.default_cart_coupon_template') !!}</label>
                                    <select name="value[default_cart_coupon_template]" class=" form-control select2">
                                        <option value="">{{ trans('update.select_default_cart_coupon_template') }}</option>

                                        @foreach($notificationTemplates as $notificationTemplate)
                                            <option value="{{ $notificationTemplate->id }}" {{ (!empty($values) and !empty(!empty($values['default_cart_coupon_template'])) and $values['default_cart_coupon_template'] == $notificationTemplate->id) ? 'selected' : '' }}>{{ $notificationTemplate->title }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-muted text-small mt-1">{{ trans('update.abandoned_cart_coupon_template_hint') }}</div>
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
    <script src="/assets/default/js/admin/abandoned_cart_settings.min.js"></script>
@endpush
