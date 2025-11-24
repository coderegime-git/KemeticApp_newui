@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('update.new_rule') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ trans('update.new_rule') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <form action="{{ getAdminPanelUrl('/abandoned-cart/rules/').(!empty($rule) ? $rule->id.'/update' : 'store') }}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <div class="row">

                                    <div class="col-12 col-md-6">

                                        @if(!empty(getGeneralSettings('content_translate')))
                                            <div class="form-group">
                                                <label class="input-label">{{ trans('auth.language') }}</label>
                                                <select name="locale" class="form-control {{ !empty($rule) ? 'js-edit-content-locale' : '' }}">
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
                                            <input type="text" name="title" value="{{ !empty($rule) ? $rule->title : old('title') }}" class="form-control "/>
                                            @error('title')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('update.minimum_cart_amount') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        {{ $currency }}
                                                    </div>
                                                </div>

                                                <input type="number" name="minimum_cart_amount"
                                                       class="form-control text-center @error('minimum_cart_amount') is-invalid @enderror"
                                                       value="{{ (!empty($rule) and !empty($rule->minimum_cart_amount)) ? convertPriceToUserCurrency($rule->minimum_cart_amount) : old('minimum_cart_amount') }}"
                                                       placeholder="{{ trans('update.minimum_cart_amount_placeholder') }}"/>
                                                @error('minimum_cart_amount')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label>{{ trans('update.maximum_cart_amount') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        {{ $currency }}
                                                    </div>
                                                </div>

                                                <input type="number" name="maximum_cart_amount"
                                                       class="form-control text-center @error('maximum_cart_amount') is-invalid @enderror"
                                                       value="{{ (!empty($rule) and !empty($rule->maximum_cart_amount)) ? convertPriceToUserCurrency($rule->maximum_cart_amount) : old('maximum_cart_amount') }}"
                                                       placeholder="{{ trans('update.maximum_cart_amount_placeholder') }}"/>
                                                @error('maximum_cart_amount')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('admin/main.action') }}</label>
                                            <select name="action" class="form-control">
                                                <option value="send_reminder" {{ (!empty($rule) and $rule->action == "send_reminder") ? 'selected' : '' }}>{{ trans('admin/main.send_reminder') }}</option>
                                                <option value="send_coupon" {{ (!empty($rule) and $rule->action == "send_coupon") ? 'selected' : '' }}>{{ trans('update.send_coupon') }}</option>
                                            </select>
                                        </div>

                                        <div class="js-discounts-field form-group {{ (!empty($rule) and $rule->action == "send_coupon") ? '' : 'd-none' }}">
                                            <label class="input-label d-block">{{ trans('update.coupon') }}</label>
                                            <select name="discount_id" class="form-control select2 @error('source') is-invalid @enderror">
                                                @foreach($discounts as $discount)
                                                    <option value="{{ $discount->id }}" {{ (!empty($rule) and $rule->discount_id == $discount->id) ? 'selected' : '' }}>{{ $discount->title }} - {{ ($discount->discount_type == \App\Models\Discount::$discountTypePercentage) ? ($discount->percent."%") : handlePrice($discount->amount) }}</option>
                                                @endforeach
                                            </select>
                                            <div class="text-muted text-small mt-1">{{ trans('update.abandoned_cart_coupon_hint') }}</div>
                                            <div class="invalid-feedback">@error('discount_id') {{ $message }} @enderror</div>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('update.action_cycle') }} ({{ trans('home.hours') }})</label>
                                            <input type="number" name="action_cycle" class="form-control @error('action_cycle') is-invalid @enderror" value="{{ !empty($rule) ? $rule->action_cycle : old('action_cycle') }}">

                                            @error('action_cycle')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group custom-switches-stacked">
                                            <label class="custom-switch pl-0">
                                                <input type="hidden" name="repeat_action" value="0">
                                                <input type="checkbox" name="repeat_action" id="repeat_actionSwitch" value="1" {{ (!empty($rule) and $rule->repeat_action) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                                <span class="custom-switch-indicator"></span>
                                                <label class="custom-switch-description mb-0 cursor-pointer" for="repeat_actionSwitch">{{ trans('update.repeat_action') }}</label>
                                            </label>
                                            <div class="text-muted text-small mt-1">{{ trans('update.abandoned_cart_repeat_action_hint') }}</div>
                                        </div>

                                        <div class="js-repeat-action-count-field form-group {{ (!empty($rule) and $rule->repeat_action) ? '' : 'd-none' }}">
                                            <label>{{ trans('update.repeat_action_count') }}</label>
                                            <input type="number" name="repeat_action_count" class="form-control @error('repeat_action_count') is-invalid @enderror" value="{{ !empty($rule) ? $rule->repeat_action_count : old('repeat_action_count') }}">

                                            @error('repeat_action_count')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror

                                            <div class="text-muted text-small mt-1">{{ trans('update.abandoned_cart_repeat_action_count_hint') }}</div>
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
                                                       value="{{ (!empty($rule) and !empty($rule->start_at)) ? dateTimeFormat($rule->start_at, 'Y-m-d H:i', false) : old('start_at') }}"/>
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
                                                       value="{{ (!empty($rule) and !empty($rule->end_at)) ? dateTimeFormat($rule->end_at, 'Y-m-d H:i', false) : old('end_at') }}"/>
                                                @error('end_at')
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label class="input-label">{{ trans('admin/main.users') }}</label>

                                            <select name="users_ids[]" class="custom-select search-user-select2" multiple data-placeholder="{{ trans('public.search_user') }}">

                                                @if(!empty($rule) and !empty($rule->users))
                                                    @foreach($rule->users as $ruleUser)
                                                        <option value="{{ $ruleUser->id }}" selected>{{ $ruleUser->full_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <div class="text-muted text-small mt-1">{{ trans('update.abandoned_cart_rule_users_hint') }}</div>
                                            @error('users_ids')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        @php
                                            $selectedGroupIds = !empty($rule) ? $rule->userGroups->pluck('id')->toArray() : [];
                                        @endphp

                                        <div class="form-group ">
                                            <label class="input-label">{{ trans('admin/main.user_group') }}</label>

                                            <select name="group_ids[]" class="custom-select select2" multiple data-placeholder="{{ trans('admin/main.select_users_group') }}">

                                                @foreach($userGroups as $userGroup)
                                                    <option value="{{ $userGroup->id }}" {{ in_array($userGroup->id, $selectedGroupIds) ? 'selected' : '' }}>{{ $userGroup->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="text-muted text-small mt-1">{{ trans('update.abandoned_cart_rule_user_groups_hint') }}</div>
                                            @error('group_ids')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        {{-- target products --}}
                                        <section class="mt-3">
                                            <h2 class="section-title after-line">{{ trans('update.target_products') }}</h2>

                                            @include('admin.abandoned_cart.rules.create.target_products')
                                        </section>

                                        <div class="form-group custom-switches-stacked">
                                            <label class="custom-switch pl-0">
                                                <input type="hidden" name="enable" value="0">
                                                <input type="checkbox" name="enable" id="enableSwitch" value="1" {{ (!empty($rule) and $rule->enable) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                                <span class="custom-switch-indicator"></span>
                                                <label class="custom-switch-description mb-0 cursor-pointer" for="enableSwitch">{{ trans('update.enable') }}</label>
                                            </label>
                                            <div class="text-muted text-small mt-1">{{ trans('update.abandoned_cart_rule_enable_hint') }}</div>
                                        </div>

                                        <div class=" mt-4">
                                            <button class="btn btn-primary">{{ trans('admin/main.submit') }}</button>
                                        </div>

                                    </div>
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
    <script src="/assets/default/js/admin/abandoned_cart_rules.min.js"></script>
@endpush
