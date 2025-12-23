@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
<link rel="stylesheet" href="/assets/default/vendors/sortable/jquery-ui.min.css"/>

<style>
/* ===============================
   KEMETIC THEME VARIABLES
================================ */
:root {
    --k-black: #0d0d0d;
    --k-dark: #141414;
    --k-gold: #f2c94c;
    --k-gold-soft: rgba(242, 201, 76, 0.15);
    --k-border: rgba(242, 201, 76, 0.25);
    --k-radius: 14px;
}

/* ===============================
   SECTION HEADER
================================ */
.kemetic-section {
    background: var(--k-black);
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius);
    padding: 25px;
    margin-top: 40px;
}

.kemetic-section-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--k-gold);
    position: relative;
    padding-left: 15px;
}

.kemetic-section-title::before {
    content: "";
    position: absolute;
    left: 0;
    top: 3px;
    width: 4px;
    height: 18px;
    background: var(--k-gold);
    border-radius: 4px;
}

/* ===============================
   BUTTONS
================================ */
.kemetic-btn {
    background: linear-gradient(135deg, #f2c94c, #c9a63c);
    color: #000;
    border: none;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    padding: 8px 18px;
    transition: all .25s ease;
}

.kemetic-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(242,201,76,.35);
}

/* ===============================
   ACCORDION CONTAINER
================================ */
.kemetic-accordion {
    margin-top: 20px;
}

.kemetic-accordion ul {
    padding: 0;
    margin: 0;
    list-style: none;
}

/* ===============================
   ACCORDION ITEM
================================ */
.kemetic-accordion-item {
    background: var(--k-dark);
    border: 1px solid var(--k-border);
    border-radius: 14px;
    padding: 18px 20px;
    margin-bottom: 12px;
    transition: .25s ease;
}

.kemetic-accordion-item:hover {
    background: #1b1b1b;
    border-color: var(--k-gold);
}

/* ===============================
   NO RESULT BOX
================================ */
.kemetic-empty {
    background: #111;
    border: 1px dashed var(--k-border);
    border-radius: 14px;
    padding: 40px 20px;
    text-align: center;
    color: #aaa;
}

.kemetic-empty h3 {
    font-size: 15px;
    color: var(--k-gold);
    margin-bottom: 8px;
}

</style>
@endpush


{{-- ===============================
   BUNDLE COURSES SECTION
================================ --}}
<section class="kemetic-section">
    <div class="d-flex align-items-center justify-content-between">
        <h2 class="kemetic-section-title">{{ trans('product.courses') }}</h2>
        <button id="addBundleWebinar"
                data-bundle-id="{{ $bundle->id }}"
                type="button"
                class="kemetic-btn">
            + {{ trans('update.add_course') }}
        </button>
    </div>

    <div class="kemetic-accordion" id="bundleWebinarsAccordion">
        @if(!empty($bundle->bundleWebinars) && count($bundle->bundleWebinars))
            <ul class="draggable-lists" data-order-table="bundle_webinars">
                @foreach($bundle->bundleWebinars as $bundleWebinarRow)
                    <li class="kemetic-accordion-item">
                        @include(
                            'web.default.panel.bundle.create_includes.accordions.bundle-webinars',
                            ['bundle' => $bundle, 'bundleWebinar' => $bundleWebinarRow]
                        )
                    </li>
                @endforeach
            </ul>
        @else
            <div class="kemetic-empty">
                <h3>{{ trans('update.bundle_webinar_no_result') }}</h3>
                <p>{{ trans('update.bundle_webinar_no_result_hint') }}</p>
            </div>
        @endif
    </div>
</section>


{{-- ===============================
   RELATED COURSES SECTION
================================ --}}
<section class="kemetic-section">
    <div class="d-flex align-items-center justify-content-between">
        <h2 class="kemetic-section-title">
            {{ trans('update.related_courses') }}
            <small class="text-muted">({{ trans('public.optional') }})</small>
        </h2>
        <button id="webinarAddRelatedCourses"
                data-bundle-id="{{ $bundle->id }}"
                type="button"
                class="kemetic-btn">
            + {{ trans('update.add_related_courses') }}
        </button>
    </div>

    <div class="kemetic-accordion" id="relatedCoursesAccordion">
        @if(!empty($bundle->relatedCourses) && count($bundle->relatedCourses))
            <ul class="draggable-lists" data-order-table="relatedCourses">
                @foreach($bundle->relatedCourses as $relatedCourseInfo)
                    <li class="kemetic-accordion-item">
                        @include(
                            'web.default.panel.bundle.create_includes.accordions.related_courses',
                            ['bundle' => $bundle, 'relatedCourse' => $relatedCourseInfo]
                        )
                    </li>
                @endforeach
            </ul>
        @else
            <div class="kemetic-empty">
                <h3>{{ trans('update.related_courses_no_result') }}</h3>
                <p>{{ trans('update.related_courses_no_result_hint') }}</p>
            </div>
        @endif
    </div>
</section>


{{-- ===============================
   HIDDEN FORMS
================================ --}}
<div id="newBundleWebinarForm" class="d-none">
    @include('web.default.panel.bundle.create_includes.accordions.bundle-webinars',['bundle' => $bundle])
</div>

<div id="newRelatedCourseForm" class="d-none">
    @include('web.default.panel.bundle.create_includes.accordions.related_courses',['bundle' => $bundle])
</div>


@push('scripts_bottom')
<script src="/assets/default/vendors/select2/select2.min.js"></script>
<script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>
@endpush
