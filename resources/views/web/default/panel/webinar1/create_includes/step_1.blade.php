<style>
    :root {
        --k-bg: #0f0f0f;
        --k-card: #1a1a1a;
        --k-gold: #F2C94C;
        --k-gold-soft: rgba(242, 201, 76, 0.18);
        --k-border: #2a2a2a;
        --k-radius: 16px;
        --k-text: #e6e6e6;
        --k-text-muted: #a3a3a3;
        --k-input-bg: #141414;
        --k-shadow: 0 4px 12px rgba(0,0,0,0.25);
    }

    /* CARD WRAPPER */
    .kemetic-card {
        background: var(--k-card);
        padding: 22px 22px;
        border-radius: var(--k-radius);
        border: 1px solid var(--k-border);
        box-shadow: var(--k-shadow);
        margin-bottom: 25px;
    }

    /* LABEL */
    .kemetic-label {
        color: var(--k-text);
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 6px;
        display: block;
    }

    /* INPUT & SELECT */
    .kemetic-input,
    .kemetic-textarea {
        background: var(--k-input-bg) !important;
        border: 1px solid var(--k-border) !important;
        color: var(--k-text) !important;
        border-radius: 12px !important;
        padding: 10px 14px;
    }

    .kemetic-select {
        background: #1a1a1a !important;
        border: 1px solid var(--kemetic-border) !important;
        color: var(--kemetic-text) !important;
        border-radius: var(--kemetic-radius) !important;
        padding: 10px 14px !important;
        transition: 0.25s ease;
    }

    .kemetic-select:focus {
        border-color: var(--kemetic-gold) !important;
        box-shadow: 0 0 12px rgba(242, 201, 76, 0.35);
    }

    /* INPUT GROUP */
    .kemetic-input-group {
        display: flex;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--k-border);
    }
    .kemetic-input-group .kemetic-btn-file {
        background: var(--k-gold-soft);
        color: var(--k-gold);
        border: none;
        width: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* SWITCH */
    .kemetic-switch .custom-control-label::before {
        background: var(--k-input-bg);
        border: 1px solid var(--k-gold);
    }
    .kemetic-switch .custom-control-input:checked ~ .custom-control-label::before {
        background: var(--k-gold);
    }

    /* SUMMERNOTE dark mode wrapper */
    /* .note-editor {
        background: #121212 !important;
        color: var(--k-text);
        border-radius: 10px;
    } */
</style>

@push('styles_top')
<link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
@endpush

<div class="row">
    <div class="col-12 col-md-4 mt-15">
        <div class="kemetic-card">

            {{-- LANGUAGE --}}
            @if(!empty(getGeneralSettings('content_translate')))
                <label class="kemetic-label">{{ trans('auth.language') }}</label>
                <select name="locale" class="kemetic-select w-100 {{ !empty($webinar) ? 'js-edit-content-locale' : '' }}">
                    @foreach($userLanguages as $lang => $language)
                        <option value="{{ $lang }}"
                            @if(mb_strtolower(request()->get('locale', app()->getLocale())) == mb_strtolower($lang)) selected @endif>
                            {{ $language }} {{ (!empty($definedLanguage) and in_array(mb_strtolower($lang), $definedLanguage)) ? '(' . trans('public.content_defined') . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            @else
                <input type="hidden" name="locale" value="{{ getDefaultLocale() }}">
            @endif



            {{-- COURSE TYPE --}}
            <div class="mt-20">
                <label class="kemetic-label">{{ trans('panel.course_type') }}</label>
                <select name="type" class="kemetic-select w-100 @error('type') is-invalid @enderror">
                    <option value="webinar" @if(!empty($webinar) and $webinar->isWebinar()) selected @endif>
                        {{ trans('webinars.webinar') }}
                    </option>
                    <option value="course" @if(!empty($webinar) and $webinar->type == 'course') selected @endif>
                        {{ trans('webinars.video_course') }}
                    </option>
                    <option value="text_lesson" @if(!empty($webinar) and $webinar->type == 'text_lesson') selected @endif>
                        {{ trans('webinars.text_lesson') }}
                    </option>
                </select>
            </div>



            {{-- TEACHER --}}
            @if($isOrganization)
                <div class="mt-20">
                    <label class="kemetic-label">{{ trans('public.select_a_teacher') }}</label>
                    <select name="teacher_id" class="kemetic-select w-100 @error('teacher_id') is-invalid @enderror">
                        <option value="" {{ empty($webinar->teacher_id) ? 'selected' : '' }}>
                            {{ trans('public.choose_instructor') }}
                        </option>

                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ (!empty($webinar) && $webinar->teacher_id == $teacher->id) ? 'selected' : '' }}>
                                {{ $teacher->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif



            {{-- TITLE --}}
            <div class="mt-20">
                <label class="kemetic-label">{{ trans('public.title') }}</label>
                <input type="text" name="title" class="kemetic-input w-100 @error('title') is-invalid @enderror"
                       value="{{ (!empty($webinar) && !empty($webinar->translate($locale))) ? $webinar->translate($locale)->title : old('title') }}">
            </div>



            {{-- SEO DESCRIPTION --}}
            <div class="mt-20">
                <label class="kemetic-label">{{ trans('public.seo_description') }}</label>
                <input type="text" name="seo_description"
                       class="kemetic-input w-100 @error('seo_description') is-invalid @enderror"
                       value="{{ (!empty($webinar) and !empty($webinar->translate($locale))) ? $webinar->translate($locale)->seo_description : old('seo_description') }}"
                       placeholder="{{ trans('forms.50_160_characters_preferred') }}">
            </div>



            {{-- THUMBNAIL --}}
            <div class="mt-20">
                <label class="kemetic-label">{{ trans('public.thumbnail_image') }}</label>

                <div class="kemetic-input-group">
                    <button type="button"
                        class="kemetic-btn-file panel-file-manager"
                        data-input="thumbnail"
                        data-preview="holder">
                        <i data-feather="arrow-up"></i>
                    </button>

                    <input type="text"
                           name="thumbnail"
                           id="thumbnail"
                           class="kemetic-input w-100 @error('thumbnail') is-invalid @enderror"
                           value="{{ !empty($webinar) ? $webinar->thumbnail : old('thumbnail') }}">
                </div>

                
            </div>



            {{-- COVER IMAGE --}}
            <div class="mt-20">
                <label class="kemetic-label">{{ trans('public.cover_image') }}</label>

                <div class="kemetic-input-group">
                    <button type="button"
                        class="kemetic-btn-file panel-file-manager"
                        data-input="cover_image"
                        data-preview="holder">
                        <i data-feather="arrow-up"></i>
                    </button>

                    <input type="text"
                           name="image_cover"
                           id="cover_image"
                           class="kemetic-input w-100 @error('image_cover') is-invalid @enderror"
                           value="{{ !empty($webinar) ? $webinar->image_cover : old('image_cover') }}">
                </div>
            </div>



            {{-- DEMO VIDEO --}}
            <div class="mt-25">
                <label class="kemetic-label">{{ trans('public.demo_video') }} ({{ trans('public.optional') }})</label>

                <label class="kemetic-label font-12">{{ trans('public.source') }}</label>
                <select name="video_demo_source"
                        class="kemetic-select w-100 js-video-demo-source">
                    @foreach(\App\Models\Webinar::$videoDemoSource as $source)
                        <option value="{{ $source }}" @if(!empty($webinar) and $webinar->video_demo_source == $source) selected @endif>
                            {{ trans('update.file_source_'.$source) }}
                        </option>
                    @endforeach
                </select>
            </div>



            {{-- VIDEO URL / UPLOAD --}}
            <div class="js-video-demo-other-inputs mt-10
                {{ (!empty($webinar) && $webinar->video_demo_source == 'secure_host') ? 'd-none' : '' }}">

                <label class="kemetic-label font-12">{{ trans('update.path') }}</label>

                <div class="kemetic-input-group js-video-demo-path-input">

                    <button type="button"
                            class="kemetic-btn-file js-video-demo-path-upload
                            {{ (!empty($webinar) && $webinar->video_demo_source != 'upload') ? 'd-none' : '' }}"
                            data-input="demo_video">
                        <i data-feather="upload"></i>
                    </button>

                    <button type="button"
                            class="kemetic-btn-file js-video-demo-path-links
                            {{ (empty($webinar) || $webinar->video_demo_source == 'upload') ? 'd-none' : '' }}">
                        <i data-feather="link"></i>
                    </button>

                    <input type="text"
                           name="video_demo"
                           id="demo_video"
                           class="kemetic-input w-100 @error('video_demo') is-invalid @enderror"
                           value="{{ !empty($webinar) ? $webinar->video_demo : old('video_demo') }}">
                </div>
            </div>



            {{-- SECURE HOST UPLOAD --}}
            <div class="js-video-demo-secure-host-input mt-15
                {{ (!empty($webinar) && $webinar->video_demo_source == 'secure_host') ? '' : 'd-none' }}">

                <div class="kemetic-input-group">
                    <button type="button" class="kemetic-btn-file">
                        <i data-feather="upload"></i>
                    </button>

                    <div class="custom-file js-ajax-s3_file">
                        <input type="file" name="video_demo_secure_host_file"
                               class="custom-file-input"
                               accept="video/*">

                        <label class="custom-file-label">{{ trans('update.choose_file') }}</label>
                    </div>
                </div>
            </div>

        </div> {{-- END CARD --}}
    </div>
</div>



{{-- DESCRIPTION BOX --}}
<div class="row">
    <div class="col-12">
        <div class="kemetic-card">
            <label class="kemetic-label">{{ trans('public.description') }}</label>

            <textarea id="summernote"
                      name="description"
                      class="kemetic-textarea w-100 @error('description') is-invalid @enderror">
                {!! (!empty($webinar) && !empty($webinar->translate($locale))) ? $webinar->translate($locale)->description : old('description') !!}
            </textarea>
        </div>
    </div>
</div>



{{-- PRIVATE COURSE SWITCH --}}
@if($isOrganization)
    <div class="row">
        <div class="col-6">
            <div class="kemetic-card">

                <div class="form-group d-flex align-items-center">
                    <label class="kemetic-label mb-0" for="privateSwitch">
                        {{ trans('webinars.private') }}
                    </label>

                    <div class="ml-30 custom-control custom-switch kemetic-switch">
                        <input type="checkbox"
                               name="private"
                               class="custom-control-input"
                               id="privateSwitch"
                               {{ (!empty($webinar) and $webinar->private) ? 'checked' :  '' }}>
                        <label class="custom-control-label" for="privateSwitch"></label>
                    </div>
                </div>

                <p class="text-muted font-12">{{ trans('webinars.create_private_course_hint') }}</p>

            </div>
        </div>
    </div>
@endif


@push('scripts_bottom')
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
     <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
        <script>
            var videoDemoPathPlaceHolderBySource = {
                upload: 'Path',
                youtube: '{{ trans('update.file_source_youtube_placeholder') }}',
                vimeo: '{{ trans('update.file_source_vimeo_placeholder') }}',
                external_link: '{{ trans('update.file_source_external_link_placeholder') }}',
                secure_host: '{{ trans('update.file_source_secure_host_placeholder') }}',
            }
        </script>
@endpush
