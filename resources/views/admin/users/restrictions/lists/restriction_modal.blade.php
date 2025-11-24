<div>
    <div class="custom-modal-body">
        <h2 class="section-title after-line">{{ trans('update.add_restriction') }}</h2>

        <div class="restriction-form" data-action="{{ getAdminPanelUrl("/users/ip-restriction/").(!empty($restriction) ? $restriction->id.'/update' : 'store') }}">

            @if(!empty($defaultFullIP))
                <input type="hidden" name="type" value="full_ip">
                <input type="hidden" name="full_ip" value="{{ $defaultFullIP }}">

                <div class="mb-2">
                    <div class="js-ajax-full_ip"></div>
                    <div class="invalid-feedback d-block"></div>
                </div>
            @else
                <div class="form-group">
                    <label class="input-label d-block">{{ trans('admin/main.type') }}</label>
                    <select id="restrictionType" name="type" class="js-ajax-type form-control">
                        <option value="">{{ trans('update.select_a_type') }}</option>
                        <option value="full_ip" {{ (!empty($restriction) and $restriction->type == "full_ip") ? 'selected' : '' }}>{{ trans('update.full_ip') }}</option>
                        <option value="ip_range" {{ (!empty($restriction) and $restriction->type == "ip_range") ? 'selected' : '' }}>{{ trans('update.ip_range') }}</option>
                        <option value="country" {{ (!empty($restriction) and $restriction->type == "country") ? 'selected' : '' }}>{{ trans('update.country') }}</option>
                    </select>

                    <div class="invalid-feedback"></div>
                </div>

                <div class="js-type-fields js-type-full_ip form-group {{ (!empty($restriction) and $restriction->type == "full_ip") ? '' : 'd-none' }}">
                    <label class="input-label d-block">{{ trans('update.full_ip') }}</label>

                    <input type="text" name="full_ip" class="js-ajax-full_ip form-control" placeholder="{{ trans('update.full_ip_placeholder') }}" value="{{ !empty($restriction) ? $restriction->value : '' }}">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="js-type-fields js-type-ip_range form-group {{ (!empty($restriction) and $restriction->type == "ip_range") ? '' : 'd-none' }}">
                    <label class="input-label d-block">{{ trans('update.ip_range') }}</label>

                    <input type="text" name="ip_range" class="js-ajax-ip_range form-control" placeholder="{{ trans('update.ip_range_hint') }}" value="{{ !empty($restriction) ? $restriction->value : '' }}">
                    <div class="invalid-feedback"></div>

                    <p class="font-12 text-muted mt-1">{{ trans('update.ip_range_hint') }}</p>
                </div>


                <div class="js-type-fields js-type-country form-group {{ (!empty($restriction) and $restriction->type == "country") ? '' : 'd-none' }}">
                    <label class="input-label d-block">{{ trans('update.country') }}</label>
                    <select name="country" class="js-ajax-country form-control js-select2">
                        <option value="">{{ trans('update.select_a_country') }}</option>

                        @foreach(getCountriesLists() as $countryCode => $country)
                            <option value="{{ $countryCode }}" {{ (!empty($restriction) and $restriction->value == $countryCode) ? 'selected' : '' }}>{{ $country }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            @endif

            <div class="form-group">
                <label class="input-label d-block">{{ trans('product.reason') }}</label>
                <textarea name="reason" rows="4" class="js-ajax-reason form-control">{{ !empty($restriction) ? $restriction->reason : '' }}</textarea>
                <div class="invalid-feedback"></div>
            </div>

            <div class="d-flex align-items-center justify-content-end mt-3">
                <button type="button" class="js-save-restriction btn btn-sm btn-primary">{{ trans('public.save') }}</button>
                <button type="button" class="close-swl btn btn-sm btn-danger ml-2">{{ trans('public.close') }}</button>
            </div>

        </div>
    </div>
</div>
