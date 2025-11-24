@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.css">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('update.purchase_notifications') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ trans('update.purchase_notifications') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <form action="{{ getAdminPanelUrl('/purchase_notifications/').(!empty($notification) ? $notification->id.'/update' : 'store') }}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <div class="row">

                                    <div class="col-12 col-md-6">

                                        @if(!empty(getGeneralSettings('content_translate')))
                                            <div class="form-group">
                                                <label class="input-label">{{ trans('auth.language') }}</label>
                                                <select name="locale" class="form-control {{ !empty($notification) ? 'js-edit-content-locale' : '' }}">
                                                    @foreach($userLanguages as $lang => $language)
                                                        <option value="{{ $lang }}" @if(mb_strtolower(request()->get('locale', app()->getLocale())) == mb_strtolower($lang)) selected @endif>{{ $language }} {{ (!empty($definedLanguage) and is_array($definedLanguage) and in_array(mb_strtolower($lang), $definedLanguage)) ? '('. trans('panel.content_defined') .')' : '' }}</option>
                                                    @endforeach
                                                </select>
                                                @error('locale')
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        @else
                                            <input type="hidden" name="locale" value="{{ getDefaultLocale() }}">
                                        @endif

                                        <div class="form-group">
                                            <label>{{ trans('admin/main.title') }}</label>
                                            <input type="text" name="title" value="{{ !empty($notification) ? $notification->title : old('title') }}" class="form-control "/>
                                            @error('title')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label class="input-label">{{ trans('admin/main.start_date') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="dateRangeLabel">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                </div>

                                                <input type="text" name="start_at" class="form-control text-center datetimepicker"
                                                       aria-describedby="dateRangeLabel" autocomplete="off"
                                                       value="{{ (!empty($notification) and !empty($notification->start_at)) ? dateTimeFormat($notification->start_at, 'Y-m-d H:i', false) : old('start_at') }}"/>
                                                @error('start_at')
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                                @enderror

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="input-label">{{ trans('admin/main.end_date') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="dateRangeLabel">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                </div>

                                                <input type="text" name="end_at" class="form-control text-center datetimepicker"
                                                       aria-describedby="dateRangeLabel" autocomplete="off"
                                                       value="{{ (!empty($notification) and !empty($notification->end_at)) ? dateTimeFormat($notification->end_at, 'Y-m-d H:i', false) : old('end_at') }}"/>
                                                @error('end_at')
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label>{{ trans('update.popup_duration') }} ({{ trans('seconds') }})</label>
                                            <input type="number" name="popup_duration" value="{{ !empty($notification) ? $notification->popup_duration : old('popup_duration') }}" class="form-control " min="0"/>
                                            <div class="text-muted text-small mt-1">{{ trans('update.purchase_notifications_duration_hint') }}</div>
                                            @error('popup_duration')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('update.popup_delay') }} ({{ trans('seconds') }})</label>
                                            <input type="number" name="popup_delay" value="{{ !empty($notification) ? $notification->popup_delay : old('popup_delay') }}" class="form-control " min="0"/>
                                            <div class="text-muted text-small mt-1">{{ trans('update.purchase_notifications_delay_hint') }}</div>
                                            @error('popup_delay')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>


                                        @php
                                            $selectedRoleIds = !empty($notification) ? $notification->roles->pluck('id')->toArray() : [];
                                            $selectedGroupIds = !empty($notification) ? $notification->userGroups->pluck('id')->toArray() : [];
                                        @endphp

                                        <div class="form-group">
                                            <label class="input-label d-block">{{ trans('admin/main.role') }}</label>
                                            <select name="role_id[]" class="form-control select2 @error('role_id') is-invalid @enderror" multiple>

                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}" {{ (in_array($role->id, $selectedRoleIds)) ? 'selected' : '' }}>{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="text-muted text-small mt-1">{{ trans('update.purchase_notifications_role_hint') }}</div>
                                            <div class="invalid-feedback d-block">@error('role_id') {{ $message }} @enderror</div>
                                        </div>

                                        <div class="form-group">
                                            <label class="input-label d-block">{{ trans('admin/main.group') }}</label>
                                            <select name="group_id[]" class="form-control select2 @error('group_id') is-invalid @enderror" multiple>

                                                @foreach($userGroups as $userGroup)
                                                    <option value="{{ $userGroup->id }}" {{ (in_array($userGroup->id, $selectedGroupIds)) ? 'selected' : '' }}>{{ $userGroup->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="text-muted text-small mt-1">{{ trans('update.purchase_notifications_group_hint') }}</div>
                                            <div class="invalid-feedback d-block">@error('group_id') {{ $message }} @enderror</div>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('update.maximum_purchase_amount') }}</label>
                                            <input type="number" name="maximum_purchase_amount" value="{{ (!empty($notification) and !empty($notification->maximum_purchase_amount)) ? convertPriceToUserCurrency($notification->maximum_purchase_amount) : old('maximum_purchase_amount') }}" class="form-control " min="0"/>
                                            <div class="text-muted text-small mt-1">{{ trans('update.purchase_notifications_max_amount_hint') }}</div>
                                            @error('maximum_purchase_amount')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('update.maximum_community_age') }}</label>
                                            <input type="number" name="maximum_community_age" value="{{ (!empty($notification)) ? $notification->maximum_community_age : old('maximum_community_age') }}" class="form-control " min="0"/>
                                            <div class="text-muted text-small mt-1">{{ trans('update.purchase_notifications_community_age_hint') }}</div>
                                            @error('maximum_community_age')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('update.display_type') }}</label>
                                            <select name="display_type" class="form-control ">
                                                <option value="overall" {{ (!empty($notification) and $notification->display_type == "overall") ? 'selected' : '' }}>{{ trans('update.overall') }}</option>
                                                <option value="per_session" {{ (!empty($notification) and $notification->display_type == "per_session") ? 'selected' : '' }}>{{ trans('update.per_session') }}</option>
                                            </select>
                                            <div class="text-muted text-small mt-1">{{ trans('update.purchase_notifications_display_type_hint') }}</div>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('update.display_time') }}</label>
                                            <input type="number" name="display_time" value="{{ (!empty($notification)) ? $notification->display_time : old('display_time') }}" class="form-control " min="0"/>
                                            <div class="text-muted text-small mt-1">{{ trans('update.purchase_notifications_display_times_hint') }}</div>
                                            @error('display_time')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group custom-switches-stacked">
                                            <label class="custom-switch pl-0 d-flex align-items-center">
                                                <input type="hidden" name="display_for_logged_out_users" value="0">
                                                <input type="checkbox" name="display_for_logged_out_users" id="display_for_logged_out_usersSwitch" value="on" {{ (!empty($notification) and $notification->display_for_logged_out_users) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                                <span class="custom-switch-indicator"></span>
                                                <label class="custom-switch-description mb-0 cursor-pointer" for="display_for_logged_out_usersSwitch">{{ trans('update.display_for_logged_out_users') }}</label>
                                            </label>
                                        </div>

                                        <h5 class="font-16 font-weight-bold mt-20">{{ trans('update.popup_content') }}</h5>
                                        <div class="text-muted text-small mt-1 mb-20">{{ trans('update.purchase_notifications_content_hint') }}</div>

                                        <div class="form-group">
                                            <label class="input-label d-block">{{ trans('admin/main.users') }}</label>
                                            <input type="text" name="users" data-max-tag="" value="{{ !empty($notification) ? $notification->users : '' }}" class="form-control inputtags" placeholder="{{ trans('update.type_user_name_and_press_enter') }}"/>
                                            <div class="text-muted text-small mt-1">{{ trans('update.purchase_notifications_users_hint') }}</div>
                                            @error('users')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>


                                        <div class="form-group">
                                            <label class="input-label">{{trans('update.content')}}</label>
                                            <select name="contents[]" class="form-control search-content-select2" multiple data-path="{{ getAdminPanelUrl("/purchase_notifications/search-contents") }}"
                                                    data-placeholder="{{trans('update.choose_contents')}}">

                                                @if(!empty($notification))
                                                    @foreach($notification->webinars as $webinar)
                                                        <option value="{{ $webinar->id }}_webinar" selected>{{ $webinar->title }} - {{ trans("update.webinar") }}</option>
                                                    @endforeach

                                                    @foreach($notification->bundles as $bundle)
                                                        <option value="{{ $bundle->id }}_bundle" selected>{{ $bundle->title }} - {{ trans("update.bundle") }}</option>
                                                    @endforeach

                                                    @foreach($notification->products as $product)
                                                        <option value="{{ $product->id }}_product" selected>{{ $product->title }} - {{ trans("update.product") }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <div class="text-muted text-small mt-1">{{ trans('update.purchase_notifications_contents_hint') }}</div>

                                            @error('contents')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label class="input-label d-block">{{ trans('update.times') }}</label>
                                            <input type="text" name="times" data-max-tag="" value="{{ !empty($notification) ? $notification->times : '' }}" class="form-control inputtags" placeholder="{{ trans('update.type_time_and_press_enter') }}"/>
                                            <div class="text-muted text-small mt-1">{{ trans('update.purchase_notifications_time_hint') }}</div>
                                            @error('times')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <h5 class="font-16 font-weight-bold mt-20">{{ trans('admin/main.tags') }}</h5>
                                        <div class="text-muted text-small mt-1 mb-20">{{ trans('update.purchase_notifications_tags_hint') }}</div>

                                        <div class="d-flex align-items-center flex-wrap">
                                            <p class="mb-1 mr-2">[user]: {{ trans('admin/main.user_name') }}</p>
                                            <p class="mb-1 mr-2">[content]: {{ trans('update.content') }}</p>
                                            <p class="mb-1 mr-2">[time]: {{ trans('admin/main.time') }}</p>
                                            <p class="mb-1 mr-2">[price]: {{ trans('admin/main.price') }}</p>
                                        </div>

                                        <div class="form-group">
                                            <label class="input-label d-block">{{ trans('update.popup_title') }}</label>
                                            <input type="text" name="popup_title" value="{{ !empty($notification) ? $notification->popup_title : old('popup_title') }}" class="form-control "/>
                                            @error('popup_title')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label class="input-label d-block">{{ trans('update.popup_subtitle') }}</label>
                                            <input type="text" name="popup_subtitle" value="{{ !empty($notification) ? $notification->popup_subtitle : old('popup_subtitle') }}" class="form-control "/>
                                            @error('popup_subtitle')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group custom-switches-stacked">
                                            <label class="custom-switch pl-0 d-flex align-items-center">
                                                <input type="hidden" name="enable" value="0">
                                                <input type="checkbox" name="enable" id="enableSwitch" value="on" {{ (!empty($notification) and $notification->enable) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                                <span class="custom-switch-indicator"></span>
                                                <label class="custom-switch-description mb-0 cursor-pointer" for="enableSwitch">{{ trans('update.enable') }}</label>
                                            </label>
                                        </div>

                                    </div>
                                </div>

                                <div class="">
                                    <button type="button" class="js-submit-form btn btn-primary">{{ trans('admin/main.save_change') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
    <script src="/assets/default/js/admin/purchase_notifications.min.js"></script>
@endpush
