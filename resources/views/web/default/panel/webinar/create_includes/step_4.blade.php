@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
    <link href="/assets/default/vendors/sortable/jquery-ui.min.css"/>

    <style>
    /* KEMETIC THEME - Chapters area */
    :root{
        --k-bg: #0F0F0F;
        --k-card: #141414;
        --k-border: rgba(242,201,76,0.22);
        --k-gold: #F2C94C;
        --k-gold-dark: #C79D2C;
        --k-text: #E6E6E6;
        --k-muted: #9b9b9b;
        --k-radius: 16px;
        --k-shadow: 0 8px 28px rgba(0,0,0,0.6);
        --k-glow: 0 6px 18px rgba(242,201,76,0.12);
    }

    /* SECTION WRAPPER */
    .kemetic-section {
        background: transparent;
        margin-top: 30px;
    }

    .kemetic-card {
        background: var(--k-card);
        border: 1px solid var(--k-border);
        padding: 20px;
        border-radius: var(--k-radius);
        box-shadow: var(--k-shadow), var(--k-glow);
        color: var(--k-text);
    }

    /* SECTION TITLE */
    .kemetic-section .section-title {
        color: var(--k-gold);
        font-weight: 700;
        font-size: 20px;
        margin-bottom: 12px;
    }
    .kemetic-section .section-title::after{ background: var(--k-gold); }

    /* NEW CHAPTER BUTTON */
    .kemetic-fab {
        background: linear-gradient(180deg,var(--k-gold),var(--k-gold-dark));
        color:#000;
        border-radius: 14px;
        padding: 8px 14px;
        font-weight: 700;
        border: none;
        box-shadow: 0 8px 20px rgba(199,157,44,0.18);
    }
    .kemetic-fab:hover { filter: brightness(.98); }

    /* CARD (chapter accordion) */
    .kemetic-accordion {
        margin-top: 16px;
    }
    .kemetic-accordion .card {
        background: #111111;
        border: 1px solid var(--k-border);
        border-radius: 12px;
        margin-bottom: 12px;
        overflow: hidden;
    }
    .kemetic-accordion .card .card-header {
        background: transparent;
        padding: 12px 16px;
        display:flex;
        align-items:center;
        justify-content:space-between;
        cursor: grab;
    }
    .kemetic-accordion .card .card-header .title {
        color: var(--k-gold);
        font-weight:700;
    }
    .kemetic-accordion .card .card-body {
        background: #0f0f0f;
        padding: 16px;
        border-top: 1px solid rgba(255,255,255,0.02);
    }

    /* PLACEHOLDER FOR EMPTY LIST */
    .kemetic-no-result {
        background: #121212;
        border-radius: 12px;
        border: 1px dashed var(--k-border);
        padding: 26px;
        text-align: center;
        color: var(--k-muted);
    }
    .kemetic-no-result .title{
        color: var(--k-gold);
        font-weight:700;
        margin-bottom:8px;
    }

    /* HIDDEN TEMPLATES (keeps same structure but styled) */
    #newTicketForm, #newSessionForm, #newFileForm, #newTextLessonForm, #newQuizForm, #newAssignmentForm, #newInteractiveFileForm {
        display: none;
    }

    /* buttons inside cards */
    .kemetic-card .btn {
        border-radius: 10px;
    }

    /* make icons gold */
    .kemetic-card [data-feather] { color: var(--k-gold); }

    /* responsive */
    @media (max-width: 768px){
        .kemetic-card { padding: 14px; }
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


<section class="kemetic-section">
    <div class="kemetic-card">

        <div class="d-flex align-items-center justify-content-between mb-10">
            <div>
                <h2 class="section-title after-line">{{ trans('public.chapters') }} <span class="text-muted">({{ trans('public.optional') }})</span></h2>
            </div>

            <div>
                <button type="button"
                        class="js-add-chapter kemetic-fab"
                        data-webinar-id="{{ $webinar->id }}">
                    <i data-feather="plus" style="width:16px;height:16px;margin-right:8px;"></i>
                    {{ trans('public.new_chapter') }}
                </button>
            </div>
        </div>

        {{-- CHAPTERS / ACCORDIONS --}}
        <div class="kemetic-accordion" id="chaptersAccordion">

            {{-- include original accordion partial (keeps your server-side markup) --}}
            @include('web.default.panel.webinar.create_includes.accordions.chapter')

        </div>

    </div>
</section>


{{-- hidden templates (styled containers remain hidden but will be used by JS) --}}
@if($webinar->isWebinar())
    <div id="newSessionForm" class="d-none">
        @include('web.default.panel.webinar.create_includes.accordions.session',['webinar' => $webinar])
    </div>
@endif

<div id="newFileForm" class="d-none">
    @include('web.default.panel.webinar.create_includes.accordions.file',['webinar' => $webinar])
</div>

@if(getFeaturesSettings('new_interactive_file'))
    <div id="newInteractiveFileForm" class="d-none">
        @include('web.default.panel.webinar.create_includes.accordions.new_interactive_file',['webinar' => $webinar])
    </div>
@endif

<div id="newTextLessonForm" class="d-none">
    @include('web.default.panel.webinar.create_includes.accordions.text-lesson',['webinar' => $webinar])
</div>

<div id="newQuizForm" class="d-none">
    @include('web.default.panel.webinar.create_includes.accordions.quiz',['webinar' => $webinar, 'quizInfo' => null, 'webinarChapterPages' => true])
</div>

@if(getFeaturesSettings('webinar_assignment_status'))
    <div id="newAssignmentForm" class="d-none">
        @include('web.default.panel.webinar.create_includes.accordions.assignment',['webinar' => $webinar])
    </div>
@endif

@include('web.default.panel.webinar.create_includes.chapter_modal')
@include('web.default.panel.webinar.create_includes.change_chapter_modal')

@push('scripts_bottom')
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>

    <script>
        var requestFailedLang = '{{ trans('public.request_failed') }}';
        var thisLiveHasEndedLang = '{{ trans('update.this_live_has_been_ended') }}';
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
        var quizzesSectionLang = '{{ trans('quiz.quizzes_section') }}';
    </script>

    <script src="/assets/default/js/panel/quiz.min.js"></script>
@endpush
