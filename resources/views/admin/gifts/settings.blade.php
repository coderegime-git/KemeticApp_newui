@extends('admin.layouts.app')

@push('styles_top')

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

                            @php
                                $basicValue = !empty($setting) ? $setting->value : null;

                                if (!empty($basicValue)) {
                                    $basicValue = json_decode($basicValue, true);
                                }
                            @endphp

                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <form action="{{ getAdminPanelUrl('/gifts/settings') }}" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="page" value="general">
                                        <input type="hidden" name="name" value="{{ \App\Models\Setting::$giftsGeneralSettingsName }}">
                                        <input type="hidden" name="locale" value="{{ \App\Models\Setting::$defaultSettingsLocale }}">

                                        <div class="form-group custom-switches-stacked">
                                            <label class="custom-switch pl-0 d-flex align-items-center">
                                                <input type="hidden" name="value[status]" value="0">
                                                <input type="checkbox" name="value[status]" id="giftsStatusSwitch" value="1" {{ (!empty($basicValue) and !empty($basicValue['status']) and $basicValue['status']) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                                <span class="custom-switch-indicator"></span>
                                                <label class="custom-switch-description mb-0 cursor-pointer" for="giftsStatusSwitch">{{ trans('admin/main.active') }}</label>
                                            </label>
                                            <div class="text-muted text-small">{{ trans('update.gifts_setting_active_hint') }}</div>
                                        </div>


                                        <div class="js-show-after-enable {{ (!empty($basicValue) and !empty($basicValue['status']) and $basicValue['status']) ? '' : 'd-none' }}">
                                            @php
                                                $otherSwitches = ['allow_sending_gift_for_courses', 'allow_sending_gift_for_bundles', 'allow_sending_gift_for_products'];
                                            @endphp

                                            @foreach($otherSwitches as $otherSwitch)
                                                <div class="form-group custom-switches-stacked">
                                                    <label class="custom-switch pl-0 d-flex align-items-center">
                                                        <input type="hidden" name="value[{{ $otherSwitch }}]" value="0">
                                                        <input type="checkbox" name="value[{{ $otherSwitch }}]" id="{{ $otherSwitch }}Switch" value="1" {{ (!empty($basicValue) and !empty($basicValue[$otherSwitch]) and $basicValue[$otherSwitch]) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                                        <span class="custom-switch-indicator"></span>
                                                        <label class="custom-switch-description mb-0 cursor-pointer" for="{{ $otherSwitch }}Switch">{{ trans("update.{$otherSwitch}") }}</label>
                                                    </label>
                                                    <div class="text-muted text-small">{{ trans("update.{$otherSwitch}_hint") }}</div>
                                                </div>
                                            @endforeach

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
    <script src="/assets/default/js/admin/settings/gifts_settings.min.js"></script>
@endpush
