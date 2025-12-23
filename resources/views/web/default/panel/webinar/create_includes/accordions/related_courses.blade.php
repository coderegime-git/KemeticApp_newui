<style>
    /* WRAPPER */
.kemetic-accordion-item {
    background: #0F0F0F;
    border: 1px solid rgba(242, 201, 76, 0.15);
    border-radius: 14px;
    padding: 15px 18px;
    margin-top: 18px;
    transition: 0.3s ease;
}

.kemetic-accordion-item:hover {
    border-color: rgba(242, 201, 76, 0.35);
    box-shadow: 0 0 12px rgba(242, 201, 76, 0.25);
}

/* HEADER */
.kemetic-accordion-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
}

.kemetic-accordion-title {
    color: #F2C94C;
    font-weight: 600;
    font-size: 16px;
}

/* TOOLS */
.kemetic-accordion-tools {
    display: flex;
    align-items: center;
    gap: 10px;
}

.kemetic-chevron {
    color: #F2C94C;
    transition: 0.3s transform;
}

.collapse.show + .kemetic-chevron {
    transform: rotate(180deg);
}

/* DROPDOWN */
.kemetic-dropdown-btn {
    background: transparent;
    border: none;
    color: #F2C94C;
    padding: 4px;
}

.kemetic-dropdown-menu {
    background: #1a1a1a;
    border: 1px solid rgba(242,201,76,0.2);
    border-radius: 10px;
    padding: 5px 0;
}

.kemetic-dropdown-menu .dropdown-item {
    color: #aaa;
}

.kemetic-dropdown-menu .dropdown-item:hover {
    background: rgba(242,201,76,0.1);
    color: #F2C94C;
}

/* BODY */
.kemetic-accordion-body {
    padding-top: 18px;
}

.kemetic-panel-inner {
    background: #161616;
    border: 1px solid rgba(242,201,76,0.12);
    padding: 20px;
    border-radius: 12px;
}

/* INPUTS */
.kemetic-input-label {
    color: #ddd;
    font-size: 14px;
    margin-bottom: 6px;
}

.kemetic-select {
    background: #1c1c1c !important;
    color: #fff !important;
    border: 1px solid rgba(242,201,76,0.25) !important;
    border-radius: 10px;
    padding: 8px;
}

/* BUTTONS */
.kemetic-btn-row {
    margin-top: 25px;
    display: flex;
    align-items: center;
}

.kemetic-btn-gold {
    background: #F2C94C;
    border: none;
    color: #000;
    font-weight: 600;
    padding: 8px 18px;
    border-radius: 10px;
}

.kemetic-btn-red {
    background: #C62828;
    border: none;
    color: white;
    padding: 8px 18px;
    border-radius: 10px;
}
/* ===============================
   KEMETIC RELATED COURSES
================================ */

.kemetic-related-course-box {
    background: linear-gradient(145deg,#0b0b0b,#141414);
    border: 1px solid rgba(242,201,76,.22);
    border-radius: 14px;
    padding: 14px 16px;
}

/* Label */
.kemetic-label {
    color: #f2c94c;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
}

/* Select input */
.kemetic-input {
    background: #000 !important;
    border: 1px solid rgba(242,201,76,.35) !important;
    border-radius: 10px;
    color: #fff !important;
    min-height: 46px;
}

.kemetic-input:focus {
    border-color: #f2c94c !important;
    box-shadow: 0 0 0 2px rgba(242,201,76,.25);
}

/* Validation */
.kemetic-input.is-invalid {
    border-color: #c62828 !important;
}

/* ===============================
   SELECT2 DROPDOWN
================================ */

.select2-dropdown {
    background: #0b0b0b;
    border: 1px solid rgba(242,201,76,.25);
    border-radius: 12px;
}

.select2-results__option {
    color: #e0e0e0;
    padding: 10px 14px;
}

.select2-results__option--highlighted {
    background: rgba(242,201,76,.15);
    color: #f2c94c;
}

</style>
<li data-id="{{ !empty($relatedCourse) ? $relatedCourse->id :'' }}" 
    class="accordion-row kemetic-accordion-item">

    <div class="kemetic-accordion-header" 
        role="tab" 
        id="relatedCourse_{{ !empty($relatedCourse) ? $relatedCourse->id :'record' }}"
        data-toggle="collapse"
        data-parent="#relatedCoursesAccordion"
        href="#collapseRelatedCourse{{ !empty($relatedCourse) ? $relatedCourse->id :'record' }}"
        aria-expanded="true">

        <div class="kemetic-accordion-title">
            {{ (!empty($relatedCourse) and !empty($relatedCourse->course)) 
            ? $relatedCourse->course->title .' - '. $relatedCourse->course->teacher->full_name 
            : trans('update.add_new_related_courses') }}
        </div>

        <div class="kemetic-accordion-tools">

            @if(!empty($relatedCourse))
            <div class="kemetic-dropdown">
                <button class="kemetic-dropdown-btn" data-toggle="dropdown">
                    <i data-feather="more-vertical"></i>
                </button>

                <div class="kemetic-dropdown-menu">
                    <a href="/panel/relatedCourses/{{ $relatedCourse->id }}/delete" 
                       class="dropdown-item text-danger">
                       {{ trans('public.delete') }}
                    </a>
                </div>
            </div>
            @endif

            <i class="kemetic-chevron" 
               data-feather="chevron-down"></i>
        </div>
    </div>

    <div id="collapseRelatedCourse{{ !empty($relatedCourse) ? $relatedCourse->id :'record' }}"
         class="collapse kemetic-accordion-body @if(empty($relatedCourse)) show @endif"
         role="tabpanel">

        <div class="kemetic-panel-inner">

            <div class="related-course-form"
                 data-action="/panel/relatedCourses/{{ !empty($relatedCourse) ? $relatedCourse->id . '/update' : 'store' }}">

                <input type="hidden" 
                       name="ajax[{{ !empty($relatedCourse) ? $relatedCourse->id : 'new' }}][item_id]" 
                       value="{{ $webinar->id }}">

                <input type="hidden" 
                       name="ajax[{{ !empty($relatedCourse) ? $relatedCourse->id : 'new' }}][item_type]" 
                       value="webinar">

                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="form-group kemetic-related-course-box">

                            <label class="kemetic-label">
                                {{ trans('update.select_related_courses') }}
                            </label>

                            <select name="ajax[{{ !empty($relatedCourse) ? $relatedCourse->id : 'new' }}][course_id]"
                                    class="kemetic-input kemetic-select2 js-ajax-course_id
                                    @if(!empty($relatedCourse)) panel-search-webinar-select2
                                    @else relatedCourses-select2 @endif"
                                    data-webinar-id="{{ !empty($webinar) ? $webinar->id : '' }}"
                                    data-placeholder="{{ trans('update.search_courses') }}">

                                @if(!empty($relatedCourse) && !empty($relatedCourse->course))
                                    <option selected value="{{ $relatedCourse->course->id }}">
                                        {{ $relatedCourse->course->title }}
                                        — {{ $relatedCourse->course->teacher->full_name }}
                                    </option>
                                @endif
                            </select>

                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                </div>

                <div class="kemetic-btn-row">
                    <button type="button" 
                            class="js-save-related-course kemetic-btn-gold">
                            {{ trans('public.save') }}
                    </button>

                    @if(empty($relatedCourse))
                    <button type="button" 
                            class="kemetic-btn-red ml-10 cancel-accordion">
                            {{ trans('public.close') }}
                    </button>
                    @endif
                </div>

            </div>
        </div>
    </div>

</li>
