@extends('web.default.layouts.newapp')

@section('content')
@push('styles_top')
<style>
    :root {
        --k-bg: #0f0f0f;
        --k-card: #1a1a1a;
        --k-gold: #F2C94C;
        --k-gold-soft: rgba(242, 201, 76, 0.15);
        --k-border: #2a2a2a;
        --k-radius: 18px;
        --k-text: #e6e6e6;
        --k-text-muted: #9e9e9e;
    }

    /* PAGE */
    .kemetic-page {
        background: var(--k-bg);
        padding: 25px;
        border-radius: var(--k-radius);
    }

    /* FORM WRAPPER */
    .kemetic-form-wrapper {
        background: var(--k-card);
        border: 1px solid var(--k-border);
        padding: 25px;
        border-radius: var(--k-radius);
        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    }

    /* FOOTER */
    .kemetic-footer {
        background: var(--k-card);
        border: 1px solid var(--k-border);
        padding: 20px 25px;
        border-radius: var(--k-radius);
        margin-top: 25px;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    /* BUTTONS */
    .k-btn {
        padding: 10px 18px;
        border-radius: 14px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        transition: 0.25s;
    }

    .k-btn-gold {
        background: var(--k-gold);
        color: #000;
    }
    .k-btn-gold:hover {
        background: #ffdd6b;
    }

    .k-btn-outline {
        background: transparent;
        color: var(--k-gold);
        border: 1px solid var(--k-gold);
    }
    .k-btn-outline:hover {
        background: var(--k-gold-soft);
    }

    .k-btn-danger {
        background: #8a0000;
        color: #fff;
    }
    .k-btn-danger:hover {
        background: #b30000;
    }
    .k-create-footer{
    background:linear-gradient(135deg,#141414,#1b1b1b);
    border-top:1px solid var(--k-border);
    border-radius:18px;
    padding:18px 22px;
}

/* Buttons */
.btn-kemetic{
    background:var(--k-gold);
    color:#000;
    border:none;
    border-radius:12px;
    font-weight:600;
    /* padding:8px 18px; */
    transition:.3s;
    white-space:nowrap;
}
.btn-kemetic:hover{
    background:#e5c252;
    box-shadow:0 6px 20px rgba(212,175,55,.3);
}

.btn-kemetic-outline{
    background:transparent;
    color:var(--k-gold);
    border:1px solid var(--k-gold);
    border-radius:12px;
    /* padding:8px 18px; */
    white-space:nowrap;
}
.btn-kemetic-outline:hover{
    background:rgba(212,175,55,.1);
}

/* Danger */
.btn-kemetic-danger{
    background:#dc2626;
    color:#fff;
    border-radius:12px;
    white-space:nowrap;
}

/* Step content */
.k-step-content{
    animation:fadeSlide .35s ease;
}
@keyframes fadeSlide{
    from{opacity:0;transform:translateY(10px)}
    to{opacity:1;transform:translateY(0)}
}


/* ============================================
   GLOBAL MOBILE FIX — prevent overflow on ALL steps
   ============================================ */
@media (max-width: 767px) {
    /* Root level overflow control */
    body,
    html {
        overflow-x: hidden !important;
    }
    .dashboard-main {
        padding: 8px !important;
        overflow-x: hidden !important;
    }
    .kemetic-page {
        padding: 6px !important;
        overflow-x: hidden !important;
    }
    .kemetic-form-wrapper {
        padding: 8px !important;
        overflow-x: hidden !important;
    }
    /* All Bootstrap rows/cols inside steps */
    .kemetic-form-wrapper .row,
    .kemetic-form-section .row,
    .kemetic-card .row {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    .kemetic-form-wrapper [class*="col-"],
    .kemetic-form-section [class*="col-"],
    .kemetic-card [class*="col-"] {
        padding-left: 6px !important;
        padding-right: 6px !important;
    }
    /* Form controls should never overflow */
    .form-control,
    .custom-select,
    input[type="text"],
    input[type="number"],
    input[type="date"],
    input[type="email"],
    select,
    textarea {
        max-width: 100% !important;
        min-width: 0 !important;
        box-sizing: border-box !important;
    }
    /* Input groups */
    .input-group {
        flex-wrap: nowrap !important;
        max-width: 100% !important;
    }

    /* Tags input */
    .bootstrap-tagsinput {
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    /* Fix any section padding */
    section,
    .kemetic-section,
    .kemetic-card {
        padding-left: 8px !important;
        padding-right: 8px !important;
        overflow-x: hidden !important;
    }
    /* Accordion items mobile */
    .kemetic-accordion-item,
    .accordion-row {
        padding: 8px 10px !important;
        overflow-x: hidden !important;
    }
    /* Switch row labels */
    .kemetic-switch-row,
    .k-switch-row {
        flex-wrap: nowrap;
        gap: 8px;
    }
    
    .mobile-full-width {
        width: 100% !important;
        margin-bottom: 10px;
    }

}

    /* GLOBAL SELECT2 */
    .select2-container {
        width: 100% !important;
        max-width: 100% !important;
    }
    .select2-container--default .select2-selection--single {
        background: #0d0d0d !important;
        border: 1px solid rgba(242,201,76,.35) !important;
        border-radius: 14px !important;
        height: 45px !important;
        display: flex;
        align-items: center;
        color: #e0e0e0 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #e0e0e0 !important;
        line-height: 45px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #f2c94c transparent transparent transparent !important;
    }
    .select2-dropdown {
        background: #0f0f0f !important;
        border: 1px solid rgba(242,201,76,.35) !important;
        border-radius: 12px !important;
    }
    .select2-results__option {
        color: #e0e0e0 !important;
        padding: 10px 14px !important;
    }
    .select2-results__option--highlighted {
        background: rgba(242,201,76,.15) !important;
        color: #fff !important;
    }
    .select2-results__option[aria-selected=true] {
        background: rgba(242,201,76,.25) !important;
    }
    .select2-search--dropdown .select2-search__field {
        background: #0d0d0d !important;
        border: 1px solid rgba(242,201,76,.35) !important;
        color: #fff !important;
        border-radius: 8px !important;
    }

/* DATERANGEPICKER DARK THEME */
.daterangepicker {
    background-color: #1a1a1a !important;
    border: 1px solid rgba(242,201,76,.35) !important;
    color: #e0e0e0 !important;
}
.daterangepicker .calendar-table {
    background-color: #1a1a1a !important;
    border: none !important;
}
.daterangepicker td.off, .daterangepicker td.off.in-range, .daterangepicker td.off.start-date, .daterangepicker td.off.end-date {
    background-color: #111 !important;
    color: #555 !important;
}
.daterangepicker td.available:hover, .daterangepicker th.available:hover {
    background-color: rgba(242,201,76,.15) !important;
    color: #f2c94c !important;
}
.daterangepicker td.active, .daterangepicker td.active:hover {
    background-color: #f2c94c !important;
    color: #000 !important;
}
.daterangepicker .ranges li:hover {
    background-color: rgba(242,201,76,.15) !important;
}
.daterangepicker .ranges li.active {
    background-color: #f2c94c !important;
    color: #000 !important;
}
.daterangepicker select.monthselect, .daterangepicker select.yearselect {
    background: #0d0d0d;
    border: 1px solid rgba(242,201,76,.35);
    color: #fff;
    padding: 2px;
}
.daterangepicker select.monthselect option, .daterangepicker select.yearselect option {
    background: #0d0d0d;
    color: #fff;
}
</style>
@endpush

<div class="kemetic-page">

    <div class="kemetic-form-wrapper">

        <form method="post"
              action="/panel/webinars/{{ !empty($webinar) ? $webinar->id .'/update' : 'store' }}"
              id="webinarForm"
              class="webinar-form"
              enctype="multipart/form-data">

            @include('web.default.panel.webinar.create_includes.progress')

            {{ csrf_field() }}
            <input type="hidden" name="current_step" value="{{ $currentStep ?? 1 }}">
            <input type="hidden" name="draft" value="no" id="forDraft"/>
            <input type="hidden" name="get_next" value="no" id="getNext"/>
            <input type="hidden" name="get_step" value="0" id="getStep"/>

            @if($currentStep == 1)
                @include('web.default.panel.webinar.create_includes.step_1')
            @elseif(!empty($webinar))
                @include('web.default.panel.webinar.create_includes.step_'.$currentStep)
            @endif

        </form>

    </div>


    {{-- FOOTER BUTTONS --}}
    <div class="k-create-footer d-flex flex-column flex-md-row align-items-center justify-content-between mt-25">

        <div class="d-flex w-100 flex-column flex-md-row align-items-center justify-content-md-start mb-15 mb-md-0">

            {{-- PREVIOUS --}}
            @if(!empty($webinar))
                <a href="/panel/webinars/{{ $webinar->id }}/step/{{ ($currentStep - 1) }}"
                   class="btn btn-kemetic-outline mobile-full-width {{ $currentStep < 2 ? 'disabled' : '' }}">
                    {{ trans('webinars.previous') }}
                </a>
            @else
                <button class="btn btn-kemetic-outline mobile-full-width disabled">Previous</button>
            @endif

            {{-- NEXT --}}
            <button type="button"
                    id="getNextStep"
                    class="btn btn-kemetic ml-15 mobile-full-width" style="margin-left: 10px;"
                    @if($currentStep >= $stepCount) disabled @endif>
                {{ trans('webinars.next') }}
            </button>

        </div>



        <div class="d-flex w-100 flex-column flex-md-row align-items-center justify-content-md-end">

            {{-- SEND FOR REVIEW / PUBLISH --}}
            <button type="button" id="sendForReview" class="btn btn-kemetic mobile-full-width mb-10 mb-md-0 mr-md-10" style="margin-right: 10px;">
                {{ !empty(getGeneralOptionsSettings('direct_publication_of_courses')) ? trans('update.publish') : trans('public.send_for_review') }}
            </button>

            {{-- SAVE DRAFT --}}
            <button type="button" id="saveAsDraft" class="btn btn-kemetic-outline mobile-full-width mb-10 mb-md-0 mr-md-10">
                {{ trans('public.save_as_draft') }}
            </button>

            {{-- DELETE --}}
            @if(!empty($webinar) and $webinar->creator_id == $authUser->id)
                @include('web.default.panel.includes.content_delete_btn', [
                    'deleteContentUrl' => "/panel/webinars/{$webinar->id}/delete?redirect_to=/panel/webinars",
                    'deleteContentClassName' => 'btn btn-kemetic-danger mobile-full-width',
                    'deleteContentItem' => $webinar,
                ])
            @endif

        </div>

    </div>

</div>



@endsection



@push('scripts_bottom')
<script>
    var saveSuccessLang = '{{ trans('webinars.success_store') }}';
    var zoomJwtTokenInvalid = '{{ trans('webinars.zoom_jwt_token_invalid') }}';
    var hasZoomApiToken = '{{ (!empty($authUser->zoomApi) && !empty($authUser->zoomApi->api_key) && !empty($authUser->zoomApi->api_secret)) ? 'true' : 'false' }}';
    var editChapterLang = '{{ trans('public.edit_chapter') }}';
</script>
@endpush
