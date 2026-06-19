@push('styles_top')
<style>
/* ================= KEMETIC ACCORDION ================= */

.kemetic-accordion {
    background: #0d0d0d;
    border: 1px solid rgba(242,201,76,.18);
    border-radius: 14px;
    box-shadow: 0 10px 35px rgba(0,0,0,.6);
    overflow: hidden;
}

/* Header */
.kemetic-accordion-header {
    padding: 16px 18px;
    cursor: pointer;
    border-bottom: 1px solid rgba(242,201,76,.15);
}

/* Title */
.kemetic-accordion-title span {
    font-size: 14px;
    font-weight: 600;
    color: #F2C94C;
}

/* Chevron */
.kemetic-chevron {
    color: #F2C94C;
    cursor: pointer;
    transition: transform .3s ease;
}

.collapsed .kemetic-chevron {
    transform: rotate(-90deg);
}

/* Body */
.kemetic-accordion-body {
    padding: 18px;
    background: #0b0b0b;
}

/* 3 DOT BUTTON */
.kemetic-3dot {
    width: 36px;
    height: 36px;
    background: transparent;
    border: 1px solid rgba(242,201,76,.35);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #F2C94C;
    cursor: pointer;
    transition: all .25s ease;
}

.kemetic-3dot:hover {
    background: rgba(242,201,76,.12);
    transform: scale(1.05);
}

/* Dropdown */
.kemetic-dropdown-menu {
    background: #111;
    border: 1px solid rgba(242,201,76,.25);
    border-radius: 10px;
    padding: 6px;
}

.kemetic-dropdown-menu .dropdown-item {
    color: #111;
    font-size: 13px;
    border-radius: 6px;
    padding: 8px 12px;
}

.kemetic-dropdown-menu .dropdown-item:hover {
    background: rgba(242,201,76,.15);
}

/* Buttons */
.kemetic-btn-primary {
    background: linear-gradient(135deg,#F2C94C,#d4a017);
    border: none;
    color: #000;
    font-weight: 600;
    padding: 8px 18px;
    border-radius: 10px;
}

.kemetic-btn-outline {
    background: transparent;
    border: 1px solid rgba(242,201,76,.4);
    color: #F2C94C;
    padding: 8px 18px;
    border-radius: 10px;
}

/* Select reuse */
.kemetic-select {
    background: #111;
    border: 1px solid rgba(242,201,76,.25);
    color: #111;
    border-radius: 10px;
    padding: 10px 14px;
}

</style>
@endpush

<li data-id="{{ !empty($relatedCourse) ? $relatedCourse->id :'' }}"
    class="accordion-row kemetic-accordion mt-20">

    <div class="kemetic-accordion-header d-flex align-items-center justify-content-between"
         role="tab"
         id="relatedCourse_{{ !empty($relatedCourse) ? $relatedCourse->id :'record' }}">

        {{-- TITLE --}}
        <div class="kemetic-accordion-title"
             href="#collapseRelatedCourse{{ !empty($relatedCourse) ? $relatedCourse->id :'record' }}"
             data-parent="#relatedCoursesAccordion"
             role="button"
             data-toggle="collapse"
             aria-expanded="true">

            <span>
                {{ (!empty($relatedCourse) && !empty($relatedCourse->course))
                    ? $relatedCourse->course->title . ' - ' . $relatedCourse->course->teacher->full_name
                    : trans('update.add_new_related_courses') }}
            </span>
        </div>

        {{-- ACTIONS --}}
        <div class="d-flex align-items-center gap-12">

            @if(!empty($relatedCourse))
                <div class="dropdown kemetic-dropdown">
                    <button type="button"
                            class="kemetic-3dot"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false">
                        <i data-feather="more-vertical" height="18"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right kemetic-dropdown-menu">
                        <a href="/panel/relatedCourses/{{ $relatedCourse->id }}/delete"
                           class="dropdown-item text-danger">
                            {{ trans('public.delete') }}
                        </a>
                    </div>
                </div>
            @endif

            <i class="kemetic-chevron"
               data-feather="chevron-down"
               height="18"
               href="#collapseRelatedCourse{{ !empty($relatedCourse) ? $relatedCourse->id :'record' }}"
               data-parent="#relatedCoursesAccordion"
               role="button"
               data-toggle="collapse"
               aria-expanded="true"></i>
        </div>
    </div>

    {{-- COLLAPSE --}}
    <div id="collapseRelatedCourse{{ !empty($relatedCourse) ? $relatedCourse->id :'record' }}"
         aria-labelledby="relatedCourse_{{ !empty($relatedCourse) ? $relatedCourse->id :'record' }}"
         class="collapse @if(empty($relatedCourse)) show @endif"
         role="tabpanel">

        <div class="kemetic-accordion-body">

            <div class="related-course-form"
                 data-action="/panel/relatedCourses/{{ !empty($relatedCourse) ? $relatedCourse->id . '/update' : 'store' }}">

                <input type="hidden"
                       name="ajax[{{ !empty($relatedCourse) ? $relatedCourse->id : 'new' }}][item_id]"
                       value="{{ $product->id }}">

                <input type="hidden"
                       name="ajax[{{ !empty($relatedCourse) ? $relatedCourse->id : 'new' }}][item_type]"
                       value="product">

                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="form-group kemetic-form-group">
                            <label class="kemetic-label">
                                {{ trans('update.select_related_courses') }}
                            </label>

                            <select name="ajax[{{ !empty($relatedCourse) ? $relatedCourse->id : 'new' }}][course_id]"
                                    class="js-ajax-course_id kemetic-select
                                    @if(!empty($relatedCourse)) panel-search-webinar-select2
                                    @else relatedCourses-select2 @endif"
                                    data-placeholder="{{ trans('update.search_courses') }}">

                                @if(!empty($relatedCourse) && !empty($relatedCourse->course))
                                    <option selected value="{{ $relatedCourse->course->id }}">
                                        {{ $relatedCourse->course->title . ' - ' . $relatedCourse->course->teacher->full_name }}
                                    </option>
                                @endif
                            </select>

                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-25 d-flex align-items-center" style="padding:10px;">
                    <button type="button"
                            class="js-save-related-course kemetic-btn-primary">
                        {{ trans('public.save') }}
                    </button>

                    @if(empty($relatedCourse))
                        <button type="button"
                                class="kemetic-btn-outline ml-10 cancel-accordion">
                            {{ trans('public.close') }}
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</li>

