@extends('web.default.layouts.newapp')
<style>
 /* Card */
.kemetic-form-card {
    background: linear-gradient(145deg, #0b0b0b, #161616);
    border-radius: 18px;
    border: 1px solid rgba(245, 199, 122, 0.18);
    box-shadow: 0 16px 45px rgba(0, 0, 0, 0.75);
}

.kemetic-title {
    font-size: 26px;
    font-weight: 800;
    color: #f5c77a;
    margin-bottom: 10px !important;
}

/* Labels */
.kemetic-input-label {
    font-size: 13px;
    font-weight: 600;
    color: #f5c77a;
    margin-bottom: 6px;
}

/* Inputs */
.kemetic-input {
    background: #0f0f0f;
    border: 1px solid rgba(245, 199, 122, 0.25);
    color: #ffffff;
    border-radius: 12px;
    min-height: 44px;
}

.kemetic-input::placeholder {
    color: #8c8c8c;
}

.kemetic-input:focus {
    background: #0f0f0f;
    border-color: #f5c77a;
    box-shadow: 0 0 0 0.15rem rgba(245, 199, 122, 0.3);
}

/* Hint */
.kemetic-hint-text {
    font-size: 12px;
    color: #9a9a9a;
}

/* Summernote */
.kemetic-form-card .note-editor {
    background: #0f0f0f;
    border: 1px solid rgba(245, 199, 122, 0.25);
    border-radius: 12px;
}

.kemetic-form-card .note-toolbar {
    background: #121212;
    border-bottom: 1px solid rgba(245, 199, 122, 0.2);
}

.kemetic-form-card .note-editable {
    background: #0f0f0f;
    color: #ffffff;
}

/* Button */
.kemetic-btn-gold {
    background: linear-gradient(135deg, #f5c77a, #c89b3c);
    border: none;
    color: #000;
    font-weight: 700;
    border-radius: 14px;
    transition: all 0.3s ease;
}

.kemetic-btn-gold:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(245, 199, 122, 0.45);
}

</style>
@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
@endpush

@section('content')
<section class="mt-35">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-30">
        <h2 class="section-title kemetic-title mb-0">
            {{ trans('panel.new_noticeboard') }}
        </h2>
    </div>

    {{-- Card --}}
    <div class="kemetic-form-card p-25 p-lg-35" style="padding:10px;">

        <form action="/panel/{{ (!empty($isCourseNotice) and $isCourseNotice) ? 'course-noticeboard' : 'noticeboard' }}/{{ !empty($noticeboard) ? $noticeboard->id.'/update' : 'store' }}"
              method="post">
            {{ csrf_field() }}

            <div class="row">

                {{-- Left Column --}}
                <div class="col-lg-6">

                    {{-- Title --}}
                    <div class="form-group mb-25">
                        <label class="kemetic-input-label">
                            {{ trans('public.title') }}
                        </label>
                        <input type="text"
                               name="title"
                               class="form-control kemetic-input @error('title') is-invalid @enderror"
                               placeholder="{{ trans('public.title') }}"
                               value="{{ !empty($noticeboard) ? $noticeboard->title : old('title') }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Course Notice --}}
                    @if(!empty($isCourseNotice) and $isCourseNotice)

                        {{-- Course --}}
                        <div class="form-group mb-25">
                            <label class="kemetic-input-label">{{ trans('product.course') }}</label>
                            <select name="webinar_id"
                                    class="form-control kemetic-input @error('webinar_id') is-invalid @enderror">
                                <option disabled selected>{{ trans('panel.select_course') }}</option>
                                @foreach($webinars as $webinar)
                                    <option value="{{ $webinar->id }}"
                                        @if(!empty($noticeboard) && $noticeboard->webinar_id == $webinar->id) selected @endif>
                                        {{ $webinar->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('webinar_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Color --}}
                        <div class="form-group mb-25">
                            <label class="kemetic-input-label">{{ trans('update.color') }}</label>
                            <select name="color"
                                    class="form-control kemetic-input @error('color') is-invalid @enderror">
                                <option disabled selected>{{ trans('update.select_a_color') }}</option>
                                @foreach(\App\Models\CourseNoticeboard::$colors as $color)
                                    <option value="{{ $color }}"
                                        @if(!empty($noticeboard) && $noticeboard->color == $color) selected @endif>
                                        {{ trans('update.course_noticeboard_color_'.$color) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    @else

                        {{-- Notice Type --}}
                        <div class="form-group mb-20">
                            <label class="kemetic-input-label">{{ trans('admin/main.type') }}</label>
                            <select name="type"
                                    id="typeSelect"
                                    class="form-control kemetic-input @error('type') is-invalid @enderror">
                                <option disabled selected>{{ trans('admin/main.select_type') }}</option>

                                @if($authUser->isOrganization())
                                    @foreach(\App\Models\Noticeboard::$types as $type)
                                        <option value="{{ $type }}"
                                            @if(!empty($noticeboard) && $noticeboard->type == $type) selected @endif>
                                            {{ trans('public.'.$type) }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="students"
                                        @if(!empty($noticeboard) && empty($noticeboard->webinar_id)) selected @endif>
                                        {{ trans('update.all_students') }}
                                    </option>
                                    <option value="course"
                                        @if(!empty($noticeboard) && !empty($noticeboard->webinar_id)) selected @endif>
                                        {{ trans('update.course_students') }}
                                    </option>
                                @endif
                            </select>

                            <p class="kemetic-hint-text mt-6">
                                {{ trans('update.new_notice_hint') }}
                            </p>

                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Instructor Course --}}
                        @if($authUser->isTeacher())
                            <div class="form-group mb-25 {{ (!empty($noticeboard) && !empty($noticeboard->webinar_id)) ? '' : 'd-none' }}"
                                 id="instructorCourses">
                                <label class="kemetic-input-label">{{ trans('product.course') }}</label>
                                <select name="webinar_id"
                                        class="form-control kemetic-input @error('webinar_id') is-invalid @enderror">
                                    <option disabled selected>{{ trans('panel.select_course') }}</option>
                                    @foreach($webinars as $webinar)
                                        <option value="{{ $webinar->id }}"
                                            @if(!empty($noticeboard) && $noticeboard->webinar_id == $webinar->id) selected @endif>
                                            {{ $webinar->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('webinar_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                    @endif
                </div>
            </div>

            {{-- Message --}}
            <div class="form-group mt-25">
                <label class="kemetic-input-label">{{ trans('site.message') }}</label>
                <textarea name="message"
                          class="summernote form-control kemetic-input @error('message') is-invalid @enderror"
                          placeholder="{{ trans('site.write_message') }}">
                    {{ !empty($noticeboard) ? $noticeboard->message : '' }}
                </textarea>
                @error('message')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="d-flex justify-content-end mt-30">
                <button id="submitForm"
                        type="button"
                        class="btn kemetic-btn-gold btn-lg px-30">
                    {{ trans('notification.post_notice') }}
                </button>
            </div>

        </form>
    </div>
</section>

@endsection

@push('scripts_bottom')
<script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
<script>
    var noticeboard_success_send = '{{ trans('panel.noticeboard_success_send') }}';
</script>
<script src="/assets/default/js/panel/noticeboard.min.js"></script>
@endpush
