@push('styles_top')
<style>
    /* ================= KEMETIC THEME: FAQ Accordion ================= */
    .k-faq-item {
        background: #151a23;
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
        color: #e5e7eb;
    }

    .k-faq-item .accordion-row {
        background: #1c2230;
        border-radius: 12px;
        padding: 15px 20px;
        transition: all 0.3s;
    }

    .k-faq-item .accordion-row:hover {
        background: #262c3a;
    }

    .k-faq-item .font-weight-bold {
        color: #F2C94C;
        font-size: 16px;
    }

    .k-faq-item .move-icon,
    .k-faq-item .collapse-chevron-icon,
    .k-faq-item .btn-group i {
        color: #e5e7eb;
    }

    .k-faq-item .panel-collapse {
        margin-top: 15px;
        color: #cbd5e1;
    }

    .k-faq-item .form-control {
        background: #0e1117;
        border: 1px solid #262c3a;
        border-radius: 10px;
        color: #e5e7eb;
        padding: 10px 12px;
    }

    .k-faq-item .form-control:focus {
        outline: none;
        border-color: #F2C94C;
        box-shadow: 0 0 5px rgba(242, 201, 76, 0.6);
        background: #0e1117;
        color: #e5e7eb;
    }

    .k-faq-item .input-label {
        font-weight: 600;
        color: #F2C94C;
    }

    .k-faq-item .btn-primary {
        background-color: #F2C94C;
        border-color: #F2C94C;
        color: #151a23;
    }

    .k-faq-item .btn-primary:hover {
        background-color: #e6b93f;
        border-color: #e6b93f;
        color: #151a23;
    }

    .k-faq-item .btn-danger {
        background-color: #ff6b6b;
        border-color: #ff6b6b;
        color: #ffffff;
    }

    .k-faq-item .btn-danger:hover {
        background-color: #ff4c4c;
        border-color: #ff4c4c;
        color: #ffffff;
    }
</style>
@endpush

<li data-id="{{ !empty($faq) ? $faq->id :'' }}" class="k-faq-item accordion-row panel-shadow">
    <div class="d-flex align-items-center justify-content-between" role="tab" id="faq_{{ !empty($faq) ? $faq->id :'record' }}">
        <div class="font-weight-bold" href="#collapseFaq{{ !empty($faq) ? $faq->id :'record' }}" aria-controls="collapseFaq{{ !empty($faq) ? $faq->id :'record' }}" data-parent="#faqsAccordion" role="button" data-toggle="collapse" aria-expanded="true">
            <span>{{ !empty($faq) ? $faq->title : trans('webinars.add_new_faqs') }}</span>
        </div>

        <div class="d-flex align-items-center">
            <i data-feather="move" class="move-icon mr-10 cursor-pointer" height="20"></i>

            @if(!empty($faq))
                <div class="btn-group dropdown table-actions mr-15">
                    <button type="button" class="btn-transparent dropdown-toggle d-flex align-items-center justify-content-center" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i data-feather="more-vertical" height="20"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a href="/panel/faqs/{{ $faq->id }}/delete" class="delete-action btn btn-sm btn-danger">{{ trans('public.delete') }}</a>
                    </div>
                </div>
            @endif

            <i class="collapse-chevron-icon" data-feather="chevron-down" height="20" href="#collapseFaq{{ !empty($faq) ? $faq->id :'record' }}" aria-controls="collapseFaq{{ !empty($faq) ? $faq->id :'record' }}" data-parent="#faqsAccordion" role="button" data-toggle="collapse" aria-expanded="true"></i>
        </div>
    </div>

    <div id="collapseFaq{{ !empty($faq) ? $faq->id :'record' }}" aria-labelledby="faq_{{ !empty($faq) ? $faq->id :'record' }}" class="collapse @if(empty($faq)) show @endif" role="tabpanel">
        <div class="panel-collapse">
            <div class="js-content-form faq-form" data-action="/panel/faqs/{{ !empty($faq) ? $faq->id . '/update' : 'store' }}">
                <input type="hidden" name="ajax[{{ !empty($faq) ? $faq->id : 'new' }}][upcoming_course_id]" value="{{ !empty($upcomingCourse) ? $upcomingCourse->id :'' }}">

                <div class="row">
                    <div class="col-12 col-lg-6">
                        @if(!empty(getGeneralSettings('content_translate')))
                            <div class="form-group">
                                <label class="input-label">{{ trans('auth.language') }}</label>
                                <select name="ajax[{{ !empty($faq) ? $faq->id : 'new' }}][locale]" class="form-control {{ !empty($faq) ? 'js-upcoming-course-content-locale' : '' }}">
                                    @foreach($userLanguages as $lang => $language)
                                        <option value="{{ $lang }}" {{ (!empty($faq) && !empty($faq->locale)) ? (mb_strtolower($faq->locale) == mb_strtolower($lang) ? 'selected' : '') : ($locale == $lang ? 'selected' : '') }}>{{ $language }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="ajax[{{ !empty($faq) ? $faq->id : 'new' }}][locale]" value="{{ $defaultLocale }}">
                        @endif

                        <div class="form-group">
                            <label class="input-label">{{ trans('public.title') }}</label>
                            <input type="text" name="ajax[{{ !empty($faq) ? $faq->id : 'new' }}][title]" class="js-ajax-title form-control" value="{{ !empty($faq) ? $faq->title : '' }}" placeholder="{{ trans('forms.maximum_64_characters') }}"/>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group">
                            <label class="input-label">{{ trans('public.answer') }}</label>
                            <textarea name="ajax[{{ !empty($faq) ? $faq->id : 'new' }}][answer]" class="js-ajax-answer form-control" rows="6">{{ !empty($faq) ? $faq->answer : '' }}</textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-30 d-flex align-items-center">
                    <button type="button" class="js-save-faq btn btn-sm btn-primary">{{ trans('public.save') }}</button>

                    @if(empty($faq))
                        <button type="button" class="btn btn-sm btn-danger ml-10 cancel-accordion">{{ trans('public.close') }}</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</li>
