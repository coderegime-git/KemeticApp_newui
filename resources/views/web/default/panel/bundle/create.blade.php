@extends('web.default.layouts.newapp')

@push('styles_top')
<style>
    :root {
        --k-bg: #0b0b0b;
        --k-card: #141414;
        --k-gold: #f2c94c;
        --k-gold-soft: #e6b93d;
        --k-border: rgba(242,201,76,0.25);
        --k-text: #eaeaea;
        --k-muted: #9a9a9a;
        --k-radius: 16px;
    }

    body {
        background: var(--k-bg);
        color: var(--k-text);
        font-family: 'Nunito', sans-serif;
    }

    .webinar-form {
        background: var(--k-card);
        padding: 25px;
        border-radius: var(--k-radius);
        box-shadow: 0 4px 20px rgba(242,201,76,0.15);
    }

    .create-bundle-footer {
        background: var(--k-card);
        padding: 20px;
        border-radius: var(--k-radius);
        box-shadow: 0 4px 20px rgba(242,201,76,0.1);
        margin-top: 20px;
    }

    .btn-primary {
        background-color: var(--k-gold);
        border-color: var(--k-gold);
        color: #000;
        border-radius: var(--k-radius);
        font-weight: 600;
        transition: 0.2s;
    }

    .btn-primary:hover {
        background-color: var(--k-gold-soft);
        border-color: var(--k-gold-soft);
        color: #000;
    }

    .btn-danger {
        background-color: #c53030;
        border-color: #c53030;
        border-radius: var(--k-radius);
        font-weight: 600;
        transition: 0.2s;
    }

    .btn-danger:hover {
        background-color: #a82828;
        border-color: #a82828;
    }

    .form-control {
        background: #1c1c1c;
        border: 1px solid var(--k-border);
        color: var(--k-text);
        border-radius: var(--k-radius);
    }

    .form-control:focus {
        background: #1c1c1c;
        border-color: var(--k-gold);
        color: var(--k-text);
        box-shadow: 0 0 0 0.2rem rgba(242,201,76,0.25);
    }

    .progress {
        height: 8px;
        background: #1c1c1c;
        border-radius: var(--k-radius);
        overflow: hidden;
        margin-top: 10px;
    }

    .progress-bar {
        background: var(--k-gold);
        height: 100%;
    }

    .badge-primary {
        background: var(--k-gold);
        color: #000;
        border-radius: var(--k-radius);
    }

    .badge-danger {
        background: #eb5757;
        color: #000;
        border-radius: var(--k-radius);
    }

    .badge-warning {
        background: #f2994a;
        color: #000;
        border-radius: var(--k-radius);
    }

    .badge-secondary {
        background: #6b6b6b;
        color: #fff;
        border-radius: var(--k-radius);
    }

    .webinar-actions {
        color: var(--k-gold);
        font-weight: 500;
    }

    .webinar-actions:hover {
        color: var(--k-gold-soft);
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
@endpush

@section('content')
<div class="">

    <form method="post" action="/panel/bundles/{{ !empty($bundle) ? $bundle->id .'/update' : 'store' }}" id="webinarForm" class="webinar-form" enctype="multipart/form-data">
        @include('web.default.panel.bundle.create_includes.progress')

        {{ csrf_field() }}
        <input type="hidden" name="current_step" value="{{ !empty($currentStep) ? $currentStep : 1 }}">
        <input type="hidden" name="draft" value="no" id="forDraft"/>
        <input type="hidden" name="get_next" value="no" id="getNext"/>
        <input type="hidden" name="get_step" value="0" id="getStep"/>

        @if($currentStep == 1)
            @include('web.default.panel.bundle.create_includes.step_1')
        @elseif(!empty($bundle))
            @include('web.default.panel.bundle.create_includes.step_'.$currentStep)
        @endif

    </form>

    <div class="k-create-footer d-flex flex-column flex-md-row align-items-center justify-content-between mt-25">

        <div class="d-flex align-items-center">
            @if(!empty($bundle))
                <a href="/panel/bundles/{{ $bundle->id }}/step/{{ ($currentStep - 1) }}" class="btn btn-kemetic-outline {{ $currentStep < 2 ? 'disabled' : '' }}">{{ trans('webinars.previous') }}</a>
            @else
                <a href="" class="btn btn-kemetic-outline disabled">{{ trans('webinars.previous') }}</a>
            @endif

            <button type="button" id="getNextStep"  style="margin-left: 10px;" class="btn btn-kemetic ml-15" @if($currentStep >= $stepCount) disabled @endif>{{ trans('webinars.next') }}</button>
        </div>

        <div class="mt-20 mt-md-0 d-flex flex-wrap align-items-center">

            <button type="button" id="sendForReview"  style="margin-right: 10px;" class="btn btn-kemetic mr-10">{{ !empty(getGeneralOptionsSettings('direct_publication_of_bundles')) ? trans('update.publish') : trans('public.send_for_review') }}</button>
            <button type="button" id="saveAsDraft" class="btn btn-kemetic-outline mr-10">{{ trans('public.save_as_draft') }}</button>

            @if(!empty($bundle) && $bundle->creator_id == $authUser->id)
                @include('web.default.panel.includes.content_delete_btn', [
                    'deleteContentUrl' => "/panel/bundles/{$bundle->id}/delete?redirect_to=/panel/bundles",
                    'deleteContentClassName' => 'btn btn-kemetic-danger mt-10 mt-md-0',
                    'deleteContentItem' => $bundle,
                ])
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts_bottom')
<script>
    var saveSuccessLang = '{{ trans('webinars.success_store') }}';
</script>
@endpush
