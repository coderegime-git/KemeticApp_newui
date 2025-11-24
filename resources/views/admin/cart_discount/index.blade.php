@extends('admin.layouts.app')

@push('styles_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('update.cart_discount') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('update.cart_discount') }}</div>
            </div>
        </div>


        <div class="section-body">

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-8 col-lg-6">
                            <form action="{{ getAdminPanelUrl("/cart_discount/store") }}" method="Post">
                                {{ csrf_field() }}

                                @if(!empty(getGeneralSettings('content_translate')))
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('auth.language') }}</label>
                                        <select name="locale" class="form-control {{ !empty($cartDiscount) ? 'js-edit-content-locale' : '' }}">
                                            @foreach($userLanguages as $lang => $language)
                                                <option value="{{ $lang }}" @if(mb_strtolower(request()->get('locale', app()->getLocale())) == mb_strtolower($lang)) selected @endif>{{ $language }}</option>
                                            @endforeach
                                        </select>
                                        @error('locale')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                @else
                                    <input type="hidden" name="locale" value="{{ getDefaultLocale() }}">
                                @endif

                                <div class="form-group">
                                    <label>{{ trans('admin/main.title') }}</label>
                                    <input type="text" name="title"
                                           class="form-control  @error('title') is-invalid @enderror"
                                           value="{{ !empty($cartDiscount) ? $cartDiscount->title : old('title') }}"/>
                                    @error('title')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>{{ trans('update.subtitle') }}</label>
                                    <input type="text" name="subtitle"
                                           class="form-control  @error('subtitle') is-invalid @enderror"
                                           value="{{ !empty($cartDiscount) ? $cartDiscount->subtitle : old('subtitle') }}"/>
                                    @error('subtitle')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="input-label d-block">{{ trans('update.coupon') }}</label>
                                    <select name="discount_id" class="form-control select2 @error('source') is-invalid @enderror">
                                        @foreach($discounts as $discount)
                                            <option value="{{ $discount->id }}" {{ (!empty($cartDiscount) and $cartDiscount->discount_id == $discount->id) ? 'selected' : '' }}>{{ $discount->title }} - {{ ($discount->discount_type == \App\Models\Discount::$discountTypePercentage) ? ($discount->percent."%") : handlePrice($discount->amount) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">@error('discount_id') {{ $message }} @enderror</div>
                                </div>


                                <div class="form-group custom-switches-stacked">
                                    <label class="custom-switch pl-0">
                                        <input type="hidden" name="show_only_on_empty_cart" value="0">
                                        <input type="checkbox" name="show_only_on_empty_cart" id="show_only_on_empty_cartSwitch" value="1" {{ (!empty($cartDiscount) and $cartDiscount->show_only_on_empty_cart) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                        <span class="custom-switch-indicator"></span>
                                        <label class="custom-switch-description mb-0 cursor-pointer" for="show_only_on_empty_cartSwitch">{{ trans('update.show_only_on_empty_cart') }}</label>
                                    </label>
                                    <div class="text-muted text-small mt-1">{{ trans('update.cart_discount_show_only_on_empty_cart_hint') }}</div>
                                </div>

                                <div class="form-group custom-switches-stacked">
                                    <label class="custom-switch pl-0">
                                        <input type="hidden" name="enable" value="0">
                                        <input type="checkbox" name="enable" id="enableSwitch" value="1" {{ (!empty($cartDiscount) and $cartDiscount->enable) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                        <span class="custom-switch-indicator"></span>
                                        <label class="custom-switch-description mb-0 cursor-pointer" for="enableSwitch">{{ trans('update.enable') }}</label>
                                    </label>
                                    <div class="text-muted text-small mt-1">{{ trans('update.cart_discount_enable_hint') }}</div>
                                </div>

                                <div class=" mt-4">
                                    <button class="btn btn-primary">{{ trans('admin/main.submit') }}</button>
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
    <script src="/assets/default/js/admin/discount.min.js"></script>
@endpush
