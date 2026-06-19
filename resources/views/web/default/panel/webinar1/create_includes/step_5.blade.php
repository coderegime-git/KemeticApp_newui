@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
    <link href="/assets/default/vendors/sortable/jquery-ui.min.css"/>

    <style>
        /* KEMETIC BASE THEME */
        :root{
            --k-bg: #0F0F0F;
            --k-card: #141414;
            --k-border: rgba(242,201,76,0.22);
            --k-gold: #F2C94C;
            --k-gold-dark: #C79D2C;
            --k-text: #E6E6E6;
            --k-muted: #9b9b9b;
            --k-radius: 18px;
            --k-shadow: 0 8px 28px rgba(0,0,0,0.55);
        }

        .kemetic-section{
            margin-top: 45px;
            padding: 20px;
            background: var(--k-card);
            border: 1px solid var(--k-border);
            border-radius: var(--k-radius);
            box-shadow: var(--k-shadow);
        }

        .kemetic-section .section-title{
            color: var(--k-gold);
            font-weight: 700;
            font-size: 20px;
        }
        .kemetic-section .section-title::after{
            background: var(--k-gold);
        }

        .kemetic-add-btn{
            background: linear-gradient(180deg,var(--k-gold),var(--k-gold-dark));
            border: none;
            padding: 8px 16px;
            border-radius: 14px;
            color: #000;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 6px 18px rgba(242,201,76,0.2);
        }
        .kemetic-add-btn:hover{
            filter: brightness(.95);
        }

        .kemetic-accordion{
            margin-top: 16px;
        }

        .kemetic-accordion ul.draggable-lists li{
            list-style: none;
        }

        .kemetic-card-item{
            background: #111;
            border: 1px solid var(--k-border);
            border-radius: 14px;
            margin-bottom: 12px;
            padding: 14px;
            color: var(--k-text);
        }

        .kemetic-card-item .item-header{
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: grab;
            margin-bottom: 10px;
            color: var(--k-gold);
            font-weight: 600;
            font-size: 15px;
        }

        .kemetic-no-result{
            background: #121212;
            border: 1px dashed var(--k-border);
            padding: 26px;
            border-radius: 16px;
            text-align: center;
            color: var(--k-muted);
            margin-top: 10px;
        }
        .kemetic-no-result .title{
            color: var(--k-gold);
            font-weight: 700;
            margin-bottom: 6px;
        }

        /* CHECKBOX */
.custom-control {
  position: relative;
  z-index: 1;
  display: block;
  min-height: 1.3rem;
  padding-left: 2rem;
  -webkit-print-color-adjust: exact;
          print-color-adjust: exact;
}

.custom-control-inline {
  display: inline-flex;
  margin-right: 1rem;
}

.custom-control-input {
  position: absolute;
  left: 0;
  z-index: -1;
  width: 1.5rem;
  height: 1.4rem;
  opacity: 0;
}
.custom-control-input:checked ~ .custom-control-label::before {
  color: #ffffff;
  border-color: #43d477;
  background-color: #43d477;
}
.custom-control-input:focus ~ .custom-control-label::before {
  box-shadow: none, 1.5rem;
}
.custom-control-input:focus:not(:checked) ~ .custom-control-label::before {
  border-color: #43d477;
}
.custom-control-input:not(:disabled):active ~ .custom-control-label::before {
  color: #ffffff;
  background-color: #43d477;
  border-color: #43d477;
}
.custom-control-input[disabled] ~ .custom-control-label, .custom-control-input:disabled ~ .custom-control-label {
  color: #6c757d;
}
.custom-control-input[disabled] ~ .custom-control-label::before, .custom-control-input:disabled ~ .custom-control-label::before {
  background-color: #f1f1f1;
}

.custom-control-label {
  position: relative;
  margin-bottom: 0;
  vertical-align: top;
}
.custom-control-label::before {
  position: absolute;
  top: -0.1rem;
  left: -2rem;
  display: block;
  width: 1.5rem;
  height: 1.5rem;
  pointer-events: none;
  content: "";
  background-color: #ffffff;
  border: 2px solid #adb5bd;
  box-shadow: none;
}
.custom-control-label::after {
  position: absolute;
  top: -0.1rem;
  left: -2rem;
  display: block;
  width: 1.5rem;
  height: 1.5rem;
  content: "";
  background: 50%/50% 50% no-repeat;
}

.custom-checkbox .custom-control-label::before {
  border-radius: 0.25rem;
}
.custom-checkbox .custom-control-input:checked ~ .custom-control-label::after {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%23ffffff' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26l2.974 2.99L8 2.193z'/%3e%3c/svg%3e");
}
.custom-checkbox .custom-control-input:indeterminate ~ .custom-control-label::before {
  border-color: #F2C94C;
  background-color: #F2C94C;
}#
.custom-checkbox .custom-control-input:indeterminate ~ .custom-control-label::after {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='4' height='4' viewBox='0 0 4 4'%3e%3cpath stroke='%23ffffff' d='M0 2h4'/%3e%3c/svg%3e");
}
.custom-checkbox .custom-control-input:disabled:checked ~ .custom-control-label::before {
  background-color: #F2C94C;
}
.custom-checkbox .custom-control-input:disabled:indeterminate ~ .custom-control-label::before {
  background-color: #F2C94C;
}

.custom-radio .custom-control-label::before {
  border-radius: 50%;
}
.custom-radio .custom-control-input:checked ~ .custom-control-label::after {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23ffffff'/%3e%3c/svg%3e");
}
.custom-radio .custom-control-input:disabled:checked ~ .custom-control-label::before {
  background-color: rgba(67, 212, 119, 0.5);
}

.custom-switch {
  padding-left: 3.125rem;
}
.custom-switch .custom-control-label::before {
  left: -3.125rem;
  width: 2.625rem;
  pointer-events: all;
  border-radius: 0.75rem;
}
.custom-switch .custom-control-label::after {
  top: calc(-0.1rem + 4px);
  left: calc(-3.125rem + 4px);
  width: calc(1.5rem - 8px);
  height: calc(1.5rem - 8px);
  background-color: #adb5bd;
  border-radius: 0.75rem;
  transition: transform 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
@media (prefers-reduced-motion: reduce) {
  .custom-switch .custom-control-label::after {
    transition: none;
  }
}
.custom-switch .custom-control-input:checked ~ .custom-control-label::after {
  background-color: #ffffff;
  transform: translateX(1.125rem);
}
.custom-switch .custom-control-input:disabled:checked ~ .custom-control-label::before {
  background-color: rgba(67, 212, 119, 0.5);
}

    </style>
@endpush


{{-- ============================
      PREREQUISITES SECTION
============================= --}}
<section class="kemetic-section">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <h2 class="section-title after-line">
            {{ trans('public.prerequisites') }} <span class="text-muted">({{ trans('public.optional') }})</span>
        </h2>

        <button id="webinarAddPrerequisites"
                data-webinar-id="{{ $webinar->id }}"
                type="button"
                class="kemetic-add-btn">
            + {{ trans('public.add_prerequisites') }}
        </button>
    </div>

    <div class="kemetic-accordion" id="prerequisitesAccordion">
        @if(!empty($webinar->prerequisites) and count($webinar->prerequisites))
            <ul class="draggable-lists" data-order-table="prerequisites">
                @foreach($webinar->prerequisites as $prerequisiteInfo)
                    <li class="kemetic-card-item">
                        @include('web.default.panel.webinar.create_includes.accordions.prerequisites',
                                    ['webinar' => $webinar,'prerequisite' => $prerequisiteInfo])
                    </li>
                @endforeach
            </ul>
        @else
            <div class="kemetic-no-result">
                <div class="title">{{ trans('public.prerequisites_no_result') }}</div>
                <div class="hint">{{ trans('public.prerequisites_no_result_hint') }}</div>
            </div>
        @endif
    </div>
</section>


{{-- ============================
      RELATED COURSES SECTION
============================= --}}
<section class="kemetic-section">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <h2 class="section-title after-line">
            {{ trans('update.related_courses') }} <span class="text-muted">({{ trans('public.optional') }})</span>
        </h2>

        <button id="webinarAddRelatedCourses"
                data-webinar-id="{{ $webinar->id }}"
                type="button"
                class="kemetic-add-btn">
            + {{ trans('update.add_related_courses') }}
        </button>
    </div>

    <div class="kemetic-accordion" id="relatedCoursesAccordion">
        @if(!empty($webinar->relatedCourses) and count($webinar->relatedCourses))
            <ul class="draggable-lists" data-order-table="relatedCourses">
                @foreach($webinar->relatedCourses as $relatedCourseInfo)
                    <li class="kemetic-card-item">
                        @include('web.default.panel.webinar.create_includes.accordions.related_courses',
                                ['webinar' => $webinar,'relatedCourse' => $relatedCourseInfo])
                    </li>
                @endforeach
            </ul>
        @else
            <div class="kemetic-no-result">
                <div class="title">{{ trans('update.related_courses_no_result') }}</div>
                <div class="hint">{{ trans('update.related_courses_no_result_hint') }}</div>
            </div>
        @endif
    </div>
</section>


{{-- Hidden forms used by JS cloning --}}
<div id="newPrerequisiteForm" class="d-none">
    @include('web.default.panel.webinar.create_includes.accordions.prerequisites',['webinar' => $webinar])
</div>

<div id="newRelatedCourseForm" class="d-none">
    @include('web.default.panel.webinar.create_includes.accordions.related_courses',['webinar' => $webinar])
</div>


@push('scripts_bottom')
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>
@endpush
