<style>
    /* ================= KEMETIC ACCORDION ================= */
.kemetic-card {
    background: #0b1120;
    border: 1px solid #1f2937;
    border-radius: 14px;
    padding: 18px;
    box-shadow: 0 10px 30px rgba(0,0,0,.35);
}

.kemetic-accordion-header {
    cursor: pointer;
}

.kemetic-accordion-title {
    font-weight: 600;
    color: #FACC15;
    font-size: 15px;
}

.kemetic-chevron {
    color: #9ca3af;
}

.kemetic-accordion-body {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px dashed #1f2937;
}

.kemetic-label {
    color: #FACC15;
    font-weight: 600;
    margin-bottom: 6px;
}

.kemetic-input {
    background: #020617;
    border: 1px solid #1f2937;
    border-radius: 10px;
    color: #e5e7eb;
}

.kemetic-btn-primary {
    background: linear-gradient(135deg, #FACC15, #EAB308);
    color: #111827;
    border-radius: 10px;
    font-weight: 600;
    padding: 6px 16px;
    border: none;
}

.kemetic-btn-outline {
    background: transparent;
    border: 1px solid #374151;
    color: #e5e7eb;
    border-radius: 10px;
    padding: 6px 16px;
}

.kemetic-icon-btn {
    background: transparent;
    border: none;
    color: #9ca3af;
}

</style>
<li data-id="{{ !empty($relatedCourse) ? $relatedCourse->id :'' }}"
    class="accordion-row kemetic-card mt-20">

    <!-- HEADER -->
    <div class="d-flex align-items-center justify-content-between kemetic-accordion-header"
         role="tab"
         id="relatedCourse_{{ !empty($relatedCourse) ? $relatedCourse->id :'record' }}">

        <div class="kemetic-accordion-title"
             href="#collapseRelatedCourse{{ !empty($relatedCourse) ? $relatedCourse->id :'record' }}"
             aria-controls="collapseRelatedCourse{{ !empty($relatedCourse) ? $relatedCourse->id :'record' }}"
             data-parent="#relatedCoursesAccordion"
             role="button"
             data-toggle="collapse"
             aria-expanded="true">

            <span>
                {{ (!empty($relatedCourse) and !empty($relatedCourse->course))
                    ? $relatedCourse->course->title .' - '. $relatedCourse->course->teacher->full_name
                    : trans('update.add_new_related_courses') }}
            </span>
        </div>

        <div class="d-flex align-items-center">

            @if(!empty($relatedCourse))
                <div class="dropdown mr-15">
                    <button type="button"
                            class="btn kemetic-icon-btn"
                            data-toggle="dropdown">
                        <i data-feather="more-vertical" width="18" height="18"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right kemetic-dropdown">
                        <a href="/panel/relatedCourses/{{ $relatedCourse->id }}/delete"
                           class="dropdown-item text-danger delete-action">
                            {{ trans('public.delete') }}
                        </a>
                    </div>
                </div>
            @endif

            <i class="collapse-chevron-icon kemetic-chevron"
               data-feather="chevron-down"
               width="18"
               height="18"
               href="#collapseRelatedCourse{{ !empty($relatedCourse) ? $relatedCourse->id :'record' }}"
               aria-controls="collapseRelatedCourse{{ !empty($relatedCourse) ? $relatedCourse->id :'record' }}"
               data-parent="#relatedCoursesAccordion"
               role="button"
               data-toggle="collapse"
               aria-expanded="true"></i>
        </div>
    </div>

    <!-- BODY -->
    <div id="collapseRelatedCourse{{ !empty($relatedCourse) ? $relatedCourse->id :'record' }}"
         aria-labelledby="relatedCourse_{{ !empty($relatedCourse) ? $relatedCourse->id :'record' }}"
         class="collapse @if(empty($relatedCourse)) show @endif"
         role="tabpanel">

        <div class="kemetic-accordion-body">
            <div class="related-course-form"
                 data-action="/panel/relatedCourses/{{ !empty($relatedCourse) ? $relatedCourse->id . '/update' : 'store' }}">

                <input type="hidden"
                       name="ajax[{{ !empty($relatedCourse) ? $relatedCourse->id : 'new' }}][item_id]"
                       value="{{ $bundle->id }}">

                <input type="hidden"
                       name="ajax[{{ !empty($relatedCourse) ? $relatedCourse->id : 'new' }}][item_type]"
                       value="bundle">

                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="form-group mt-15">
                            <label class="kemetic-label">
                                {{ trans('update.select_related_courses') }}
                            </label>

                            <select name="ajax[{{ !empty($relatedCourse) ? $relatedCourse->id : 'new' }}][course_id]"
                                    class="js-ajax-course_id kemetic-input form-control
                                    @if(!empty($relatedCourse)) panel-search-webinar-select2
                                    @else relatedCourses-select2 @endif"
                                    data-placeholder="{{ trans('update.search_courses') }}">

                                @if(!empty($relatedCourse) and !empty($relatedCourse->course))
                                    <option selected value="{{ $relatedCourse->course->id }}">
                                        {{ $relatedCourse->course->title .' - '. $relatedCourse->course->teacher->full_name }}
                                    </option>
                                @endif
                            </select>

                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-30 d-flex align-items-center" style="padding:10px;">
                    <button type="button"
                            class="js-save-related-course kemetic-btn-primary btn-sm">
                        {{ trans('public.save') }}
                    </button>

                    @if(empty($relatedCourse))
                        <button type="button"
                                class="kemetic-btn-outline btn-sm ml-10 cancel-accordion">
                            {{ trans('public.close') }}
                        </button>
                    @endif
                </div>

            </div>
        </div>
    </div>
</li>
