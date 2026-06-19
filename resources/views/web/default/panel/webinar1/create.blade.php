@extends('web.default.layouts.newapp')

@section('content')

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
    padding:8px 18px;
    transition:.3s;
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
    padding:8px 18px;
}
.btn-kemetic-outline:hover{
    background:rgba(212,175,55,.1);
}

/* Danger */
.btn-kemetic-danger{
    background:#dc2626;
    color:#fff;
    border-radius:12px;
}

/* Step content */
.k-step-content{
    animation:fadeSlide .35s ease;
}
@keyframes fadeSlide{
    from{opacity:0;transform:translateY(10px)}
    to{opacity:1;transform:translateY(0)}
}

</style>


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

        <div class="d-flex align-items-center">

            {{-- PREVIOUS --}}
            @if(!empty($webinar))
                <a href="/panel/webinars/{{ $webinar->id }}/step/{{ ($currentStep - 1) }}"
                   class="btn btn-kemetic-outline {{ $currentStep < 2 ? 'disabled' : '' }}">
                    {{ trans('webinars.previous') }}
                </a>
            @else
                <button class="btn btn-kemetic-outline disabled">Previous</button>
            @endif

            {{-- NEXT --}}
            <button type="button"
                    id="getNextStep"
                    class="btn btn-kemetic ml-15" style="margin-left: 10px;"
                    @if($currentStep >= $stepCount) disabled @endif>
                {{ trans('webinars.next') }}
            </button>

        </div>



       <div class="mt-20 mt-md-0 d-flex flex-wrap align-items-center">

            {{-- SEND FOR REVIEW / PUBLISH --}}
            <button type="button" id="sendForReview" class="btn btn-kemetic mr-10" style="margin-right: 10px;">
                {{ !empty(getGeneralOptionsSettings('direct_publication_of_courses')) ? trans('update.publish') : trans('public.send_for_review') }}
            </button>

            {{-- SAVE DRAFT --}}
            <button type="button" id="saveAsDraft" class="btn btn-kemetic-outline mr-10">
                {{ trans('public.save_as_draft') }}
            </button>

            {{-- DELETE --}}
            @if(!empty($webinar) and $webinar->creator_id == $authUser->id)
                @include('web.default.panel.includes.content_delete_btn', [
                    'deleteContentUrl' => "/panel/webinars/{$webinar->id}/delete?redirect_to=/panel/webinars",
                    'deleteContentClassName' => 'btn btn-kemetic-danger mt-10 mt-md-0',
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
