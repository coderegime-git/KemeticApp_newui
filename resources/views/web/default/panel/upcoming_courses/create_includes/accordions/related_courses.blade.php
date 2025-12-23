@push('styles_top')
<style>
    /* ================= KEMETIC THEME: Related Courses Accordion ================= */
    .k-related-course-item {
        background: #151a23;
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
        color: #e5e7eb;
    }

    .k-related-course-item .accordion-row {
        background: #1c2230;
        border-radius: 12px;
        padding: 15px 20px;
        transition: all 0.3s;
    }

    .k-related-course-item .accordion-row:hover {
        background: #262c3a;
    }

    .k-related-course-item .font-weight-bold {
        color: #F2C94C;
        font-size: 16px;
    }

    .k-related-course-item .collapse-chevron-icon,
    .k-related-course-item .btn-group i {
        color: #e5e7eb;
    }

    .k-related-course-item .panel-collapse {
        margin-top: 15px;
        color: #cbd5e1;
    }

    .k-related-course-item .form-control {
        background: #0e1117;
        border: 1px solid #262c3a;
        border-radius: 10px;
        color: #e5e7eb;
        padding: 10px 12px;
    }

    .k-related-course-item .form-control:focus {
        outline: none;
        border-color: #F2C94C;
        box-shadow: 0 0 5px rgba(242, 201, 76, 0.6);
        background: #0e1117;
        color: #e5e7eb;
    }

    .k-related-course-item .input-label {
        font-weight: 600;
        color: #F2C94C;
    }

    .k-related-course-item .btn-primary {
        background-color: #F2C94C;
        border-color: #F2C94C;
        color: #151a23;
    }

    .k-related-course-item .btn-primary:hover {
        background-color: #e6b93f;
        border-color: #e6b93f;
        color: #151a23;
    }

    .k-related-course-item .btn-danger {
        background-color: #ff6b6b;
        border-color: #ff6b6b;
        color: #ffffff;
    }

    .k-related-course-item .btn-danger:hover {
        background-color: #ff4c4c;
        border-color: #ff4c4c;
        color: #ffffff;
    }
</style>
@endpush

<li data-id="{{ !empty($relatedCourse) ? $relatedCourse->id :'' }}" class="k-related-course-item accordion-row panel-shadow">
    <div class="d-flex align-items-center justify-content-between" role="tab" id="relatedCourse_{{ !empty($relatedCourse) ? $relatedCourse->id :'record' }}">
        <div class="font-weight-bold" href="#collapseRelatedCourse{{ !empty($relatedCourse) ? $relatedCourse->id :'record' }}" aria-controls="collapseRelatedCourse{{ !empty($relatedCourse) ? $relatedCourse->id :'record' }}" data-parent="#relatedCoursesAccordion" role="button" data-toggle="collapse" aria-expanded="true">
            <span>{{ (!empty($relatedCourse) && !empty($relatedCourse->course)) ? $relatedCourse->course->title .' - '. $relatedCourse->course->teacher->full_name : trans('update.add_new_related_courses') }}</span>
        </div>

        <div class="d-flex align-items-center">
            @if(!empty($relatedCourse))
                <div class="btn-group dropdown table-actions mr-15">
                    <button type="button" class="btn-transparent dropdown-toggle d-flex align-items-center justify-content-center" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i data-feather="more-vertical" height="20"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a href="/panel/relatedCourses/{{ $relatedCourse->id }}/delete" class="delete-action btn btn-sm btn-danger">{{ trans('public.delete') }}</a>
                    </div>
                </div>
            @endif

            <i class="collapse-chevron-icon" data-feather="chevron-down" height="20" href="#collapseRelatedCourse{{ !empty($relatedCourse) ? $relatedCourse->id :'record' }}" aria-controls="collapseRelatedCourse{{ !empty($relatedCourse) ? $relatedCourse->id :'record' }}" data-parent="#relatedCoursesAccordion" role="button" data-toggle="collapse" aria-expanded="true"></i>
        </div>
    </div>

    <div id="collapseRelatedCourse{{ !empty($relatedCourse) ? $relatedCourse->id :'record' }}" aria-labelledby="relatedCourse_{{ !empty($relatedCourse) ? $relatedCourse->id :'record' }}" class="collapse @if(empty($relatedCourse)) show @endif" role="tabpanel">
        <div class="panel-collapse">
            <div class="related-course-form" data-action="/panel/relatedCourses/{{ !empty($relatedCourse) ? $relatedCourse->id . '/update' : 'store' }}">
                <input type="hidden" name="ajax[{{ !empty($relatedCourse) ? $relatedCourse->id : 'new' }}][item_id]" value="{{ $upcomingCourse->id }}">
                <input type="hidden" name="ajax[{{ !empty($relatedCourse) ? $relatedCourse->id : 'new' }}][item_type]" value="upcomingCourse">

                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="form-group mt-15">
                            <label class="input-label d-block">{{ trans('update.select_related_courses') }}</label>
                            <select name="ajax[{{ !empty($relatedCourse) ? $relatedCourse->id : 'new' }}][course_id]" class="js-ajax-course_id form-control @if(!empty($relatedCourse)) panel-search-webinar-select2 @else relatedCourses-select2 @endif" data-placeholder="{{ trans('update.search_courses') }}">
                                @if(!empty($relatedCourse) && !empty($relatedCourse->course))
                                    <option selected value="{{ $relatedCourse->course->id }}">{{ $relatedCourse->course->title .' - '. $relatedCourse->course->teacher->full_name }}</option>
                                @endif
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-30 d-flex align-items-center">
                    <button type="button" class="js-save-related-course btn btn-sm btn-primary">{{ trans('public.save') }}</button>

                    @if(empty($relatedCourse))
                        <button type="button" class="btn btn-sm btn-danger ml-10 cancel-accordion">{{ trans('public.close') }}</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</li>
