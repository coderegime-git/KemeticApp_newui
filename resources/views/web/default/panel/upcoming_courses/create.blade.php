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
        margin-bottom: 20px;
    }

    .create-webinar-footer {
        background: var(--k-card);
        border-top: 1px solid var(--k-border);
        border-radius: var(--k-radius);
        padding: 15px 25px;
        box-shadow: 0 4px 20px rgba(242,201,76,0.1);
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

    .disabled {
        opacity: 0.6;
        pointer-events: none;
    }

    .webinar-actions {
        color: var(--k-gold);
        font-weight: 500;
    }

    .webinar-actions:hover {
        color: var(--k-gold-soft);
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
        background: rgba(255,255,255,0.1);
        border-radius: var(--k-radius);
        height: 8px;
        margin-top: 10px;
    }

    .progress-bar {
        background: var(--k-gold);
    }

</style>
@endpush

@section('content')
<div class="webinar-form">

    <form method="post" action="/panel/upcoming_courses/{{ !empty($upcomingCourse) ? $upcomingCourse->id .'/update' : 'store' }}" id="upcomingCourseForm" enctype="multipart/form-data">
        @include('web.default.panel.upcoming_courses.create_includes.progress')

        {{ csrf_field() }}
        <input type="hidden" name="current_step" value="{{ !empty($currentStep) ? $currentStep : 1 }}">
        <input type="hidden" name="draft" value="no" id="forDraft"/>
        <input type="hidden" name="get_next" value="no" id="getNext"/>
        <input type="hidden" name="get_step" value="0" id="getStep"/>

        @if($currentStep == 1)
            @include('web.default.panel.upcoming_courses.create_includes.step_1')
        @elseif(!empty($upcomingCourse))
            @include('web.default.panel.upcoming_courses.create_includes.step_'.$currentStep)
        @endif
    </form>

    <div class="create-webinar-footer d-flex flex-column flex-md-row align-items-center justify-content-between mt-20">
        <div class="d-flex align-items-center">
            @if(!empty($upcomingCourse))
                <a href="/panel/upcoming_courses/{{ $upcomingCourse->id }}/step/{{ ($currentStep - 1) }}" class="btn btn-sm btn-primary {{ $currentStep < 2 ? 'disabled' : '' }}">{{ trans('webinars.previous') }}</a>
            @else
                <a href="" class="btn btn-sm btn-primary disabled">{{ trans('webinars.previous') }}</a>
            @endif

            <button type="button" id="getNextStep" class="btn btn-sm btn-primary ml-15" @if($currentStep >= 8) disabled @endif>{{ trans('webinars.next') }}</button>
        </div>

        <div class="mt-20 mt-md-0">
            <button type="button" id="sendForReview" class="btn btn-sm btn-primary">{{ trans('public.send_for_review') }}</button>

            <button type="button" id="saveAsDraft" class=" btn btn-sm btn-primary">{{ trans('public.save_as_draft') }}</button>

            @if(!empty($upcomingCourse) && $upcomingCourse->creator_id == $authUser->id)
                <a href="/panel/upcoming_courses/{{ $upcomingCourse->id }}/delete?redirect_to=/panel/upcoming_courses" class="delete-action webinar-actions btn btn-sm btn-danger mt-20 mt-md-0">{{ trans('public.delete') }}</a>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts_bottom')
<script>
    var saveSuccessLang = '{{ trans('webinars.success_store') }}';
</script>
<script src="/assets/default/js/panel/create_upcoming_course.min.js"></script>
@endpush
