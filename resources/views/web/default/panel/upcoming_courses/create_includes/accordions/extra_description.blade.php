@push('styles_top')
<style>
    /* ================= KEMETIC THEME: Extra Description Accordion ================= */
    .k-accordion-item {
        background: #151a23;
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
        color: #e5e7eb;
    }

    .k-accordion-item .accordion-row {
        background: #1c2230;
        border-radius: 12px;
        padding: 15px 20px;
        transition: all 0.3s;
    }

    .k-accordion-item .accordion-row:hover {
        background: #262c3a;
    }

    .k-accordion-item .font-weight-bold {
        color: #F2C94C;
        font-size: 16px;
    }

    .k-accordion-item .move-icon,
    .k-accordion-item .collapse-chevron-icon,
    .k-accordion-item .btn-group i {
        color: #e5e7eb;
    }

    .k-accordion-item .panel-collapse {
        margin-top: 15px;
        color: #cbd5e1;
    }

    .k-accordion-item .form-control {
        background: #0e1117;
        border: 1px solid #262c3a;
        border-radius: 10px;
        color: #e5e7eb;
        padding: 10px 12px;
    }

    .k-accordion-item .form-control:focus {
        outline: none;
        border-color: #F2C94C;
        box-shadow: 0 0 5px rgba(242, 201, 76, 0.6);
        background: #0e1117;
        color: #e5e7eb;
    }

    .k-accordion-item .input-label {
        font-weight: 600;
        color: #F2C94C;
    }

    .k-accordion-item .btn-primary {
        background-color: #F2C94C;
        border-color: #F2C94C;
        color: #151a23;
    }

    .k-accordion-item .btn-primary:hover {
        background-color: #e6b93f;
        border-color: #e6b93f;
        color: #151a23;
    }

    .k-accordion-item .btn-danger {
        background-color: #ff6b6b;
        border-color: #ff6b6b;
        color: #ffffff;
    }

    .k-accordion-item .btn-danger:hover {
        background-color: #ff4c4c;
        border-color: #ff4c4c;
        color: #ffffff;
    }

    .webinar-extra-description-company-logos {
        max-height: 40px;
        border-radius: 6px;
    }
</style>
@endpush

<li data-id="{{ !empty($extraDescription) ? $extraDescription->id :'' }}" class="k-accordion-item accordion-row panel-shadow">
    <div class="d-flex align-items-center justify-content-between" role="tab" id="{{ $extraDescriptionType }}_{{ !empty($extraDescription) ? $extraDescription->id :'record' }}">
        <div class="font-weight-bold" href="#collapseExtraDescription{{ !empty($extraDescription) ? $extraDescription->id :'record' }}" aria-controls="collapseExtraDescription{{ !empty($extraDescription) ? $extraDescription->id :'record' }}" data-parent="#{{ $extraDescriptionParentAccordion }}" role="button" data-toggle="collapse" aria-expanded="true">
            @if(!empty($extraDescription) and !empty($extraDescription->value))
                @if($extraDescriptionType == \App\Models\WebinarExtraDescription::$COMPANY_LOGOS)
                    <img src="{{ $extraDescription->value }}" class="webinar-extra-description-company-logos" alt="">
                @else
                    <span>{{ truncate($extraDescription->value, 45) }}</span>
                @endif
            @else
                <span>{{ trans('update.new_item') }}</span>
            @endif
        </div>

        <div class="d-flex align-items-center">
            <i data-feather="move" class="move-icon mr-10 cursor-pointer" height="20"></i>

            @if(!empty($extraDescription))
                <div class="btn-group dropdown table-actions mr-15">
                    <button type="button" class="btn-transparent dropdown-toggle d-flex align-items-center justify-content-center" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i data-feather="more-vertical" height="20"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a href="/panel/webinar-extra-description/{{ $extraDescription->id }}/delete" class="delete-action btn btn-sm btn-danger">{{ trans('public.delete') }}</a>
                    </div>
                </div>
            @endif

            <i class="collapse-chevron-icon" data-feather="chevron-down" height="20" href="#collapseExtraDescription{{ !empty($extraDescription) ? $extraDescription->id :'record' }}" aria-controls="collapseExtraDescription{{ !empty($extraDescription) ? $extraDescription->id :'record' }}" data-parent="#{{ $extraDescriptionParentAccordion }}" role="button" data-toggle="collapse" aria-expanded="true"></i>
        </div>
    </div>

    <div id="collapseExtraDescription{{ !empty($extraDescription) ? $extraDescription->id :'record' }}" aria-labelledby="{{ $extraDescriptionType }}_{{ !empty($extraDescription) ? $extraDescription->id :'record' }}" class="collapse @if(empty($extraDescription)) show @endif" role="tabpanel">
        <div class="panel-collapse">
            <div class="js-content-form extra_description-form" data-action="/panel/webinar-extra-description/{{ !empty($extraDescription) ? $extraDescription->id . '/update' : 'store' }}">
                <input type="hidden" name="ajax[{{ !empty($extraDescription) ? $extraDescription->id : 'new' }}][upcoming_course_id]" value="{{ !empty($upcomingCourse) ? $upcomingCourse->id :'' }}">
                <input type="hidden" name="ajax[{{ !empty($extraDescription) ? $extraDescription->id : 'new' }}][type]" value="{{ $extraDescriptionType }}">

                <div class="row">
                    <div class="col-12 col-lg-6">

                        @if($extraDescriptionType == \App\Models\WebinarExtraDescription::$COMPANY_LOGOS)
                            <input type="hidden" name="ajax[{{ !empty($extraDescription) ? $extraDescription->id : 'new' }}][locale]" value="{{ $defaultLocale }}">
                            <div class="form-group">
                                <label class="input-label">{{ trans('public.image') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button type="button" class="input-group-text panel-file-manager" data-input="image{{ !empty($extraDescription) ? $extraDescription->id : 'record' }}" data-preview="holder">
                                            <i data-feather="upload" class="text-white" width="18" height="18"></i>
                                        </button>
                                    </div>
                                    <input type="text" name="ajax[{{ !empty($extraDescription) ? $extraDescription->id : 'new' }}][value]" id="image{{ !empty($extraDescription) ? $extraDescription->id : 'record' }}" value="{{ !empty($extraDescription) ? $extraDescription->value : '' }}" class="js-ajax-value form-control" placeholder=""/>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        @else
                            @if(!empty(getGeneralSettings('content_translate')))
                                <div class="form-group">
                                    <label class="input-label">{{ trans('auth.language') }}</label>
                                    <select name="ajax[{{ !empty($extraDescription) ? $extraDescription->id : 'new' }}][locale]"
                                            class="form-control {{ !empty($extraDescription) ? 'js-upcoming-course-content-locale' : '' }}">
                                        @foreach($userLanguages as $lang => $language)
                                            <option value="{{ $lang }}" {{ (!empty($extraDescription) and !empty($extraDescription->locale)) ? (mb_strtolower($extraDescription->locale) == mb_strtolower($lang) ? 'selected' : '') : ($locale == $lang ? 'selected' : '') }}>{{ $language }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <input type="hidden" name="ajax[{{ !empty($extraDescription) ? $extraDescription->id : 'new' }}][locale]" value="{{ $defaultLocale }}">
                            @endif

                            <div class="form-group">
                                <label class="input-label">{{ trans('public.title') }}</label>
                                <input type="text" name="ajax[{{ !empty($extraDescription) ? $extraDescription->id : 'new' }}][value]" class="js-ajax-value form-control" value="{{ !empty($extraDescription) ? $extraDescription->value : '' }}"/>
                                <div class="invalid-feedback"></div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-30 d-flex align-items-center">
                    <button type="button" class="js-save-extra_description btn btn-sm btn-primary">{{ trans('public.save') }}</button>

                    @if(empty($extraDescription))
                        <button type="button" class="btn btn-sm btn-danger ml-10 cancel-accordion">{{ trans('public.close') }}</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</li>
