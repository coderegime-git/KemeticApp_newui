@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
    @push('styles_top')
<link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">

<style>
:root{
    --k-bg:#0b0b0b;
    --k-card:#141414;
    --k-border:#262626;
    --k-gold:#f2c94c;
    --k-text:#eaeaea;
    --k-muted:#9a9a9a;
    --k-radius:14px;
}

/* Card Wrapper */
.kemetic-card{
    background:var(--k-card);
    border:1px solid var(--k-border);
    border-radius:var(--k-radius);
    padding:22px;
}

/* Labels */
.kemetic-label{
    color:var(--k-gold);
    font-weight:600;
    font-size:13px;
    letter-spacing:.4px;
}

/* Inputs */
.kemetic-card .form-control,
.kemetic-card .custom-select{
    background:#0f0f0f;
    border:1px solid var(--k-border);
    color:var(--k-text);
    border-radius:10px;
}

.kemetic-card .form-control::placeholder{
    color:var(--k-muted);
}

.kemetic-card .form-control:focus,
.kemetic-card .custom-select:focus{
    border-color:var(--k-gold);
    box-shadow:0 0 0 1px rgba(242,201,76,.35);
}

/* Upload buttons */
.kemetic-upload{
    background:linear-gradient(135deg,#f2c94c,#d4af37);
    border:none;
    border-radius:10px 0 0 10px;
}

/* Switch */
.custom-switch .custom-control-input:checked ~ .custom-control-label::before{
    background:var(--k-gold);
    border-color:var(--k-gold);
}

/* Summernote */
.note-editor.note-frame{
    background:#0f0f0f;
    border:1px solid var(--k-border);
    border-radius:var(--k-radius);
}
.note-toolbar{
    background:#141414;
    border-bottom:1px solid var(--k-border);
}
.note-editable{
    background:#0f0f0f;
    color:var(--k-text);
}

/* Helper text */
.kemetic-hint{
    color:var(--k-muted);
    font-size:12px;
}

/* ===== Kemetic Form Design ===== */

.kemetic-form {
    --gold: #d4af37;
    --dark: #0b0b0b;
}

.kemetic-group {
    background: linear-gradient(180deg, #141414, #0b0b0b);
    padding: 16px;
    border-radius: 14px;
    border: 1px solid rgba(212,175,55,.2);
}

.kemetic-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--gold);
    margin-bottom: 6px;
}

.kemetic-muted {
    color: #aaa;
    font-size: 11px;
}

.kemetic-input,
.kemetic-select {
    background: #0f0f0f;
    border: 1px solid rgba(212,175,55,.25);
    color: #fff;
    border-radius: 10px;
}

.kemetic-input:focus,
.kemetic-select:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 2px rgba(212,175,55,.25);
}

.kemetic-upload {
    display: flex;
    gap: 10px;
}

.kemetic-upload-btn {
    background: linear-gradient(135deg, #d4af37, #b8962e);
    border: none;
    color: #000;
    border-radius: 10px;
    padding: 0 14px;
    cursor: pointer;
}

.kemetic-upload-btn svg {
    stroke-width: 2.2;
}

.kemetic-group:hover {
    box-shadow: 0 15px 30px rgba(212,175,55,.18);
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
/* ===== Kemetic Video Input ===== */

.kemetic-group {
    background: linear-gradient(180deg, #141414, #0b0b0b);
    padding: 16px;
    border-radius: 14px;
    border: 1px solid rgba(212,175,55,.25);
}

.kemetic-label {
    font-size: 13px;
    font-weight: 600;
    color: #d4af37;
    margin-bottom: 8px;
    display: block;
}

.kemetic-input-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
}

.kemetic-icon-btn {
    background: linear-gradient(135deg, #d4af37, #b8962e);
    border: none;
    color: #000;
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all .25s ease;
}

.kemetic-icon-btn svg {
    stroke-width: 2.2;
}

.kemetic-icon-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(212,175,55,.35);
}

.kemetic-input {
    background: #0f0f0f;
    border: 1px solid rgba(212,175,55,.3);
    color: #fff;
    border-radius: 12px;
    height: 44px;
}

.kemetic-input::placeholder {
    color: #999;
}

.kemetic-input:focus {
    border-color: #d4af37;
    box-shadow: 0 0 0 2px rgba(212,175,55,.25);
}

</style>
@endpush

@endpush
<div class="row kemetic-form">
    <div class="col-12 col-md-4 mt-15">

        {{-- Language --}}
        @if(!empty(getGeneralSettings('content_translate')))
            <div class="form-group kemetic-group mt-15">
                <label class="kemetic-label">{{ trans('auth.language') }}</label>
                <select name="locale"
                        class="kemetic-select {{ !empty($bundle) ? 'js-edit-content-locale' : '' }}">
                    @foreach($userLanguages as $lang => $language)
                        <option value="{{ $lang }}"
                            @if(mb_strtolower(request()->get('locale', app()->getLocale())) == mb_strtolower($lang)) selected @endif>
                            {{ $language }}
                        </option>
                    @endforeach
                </select>
            </div>
        @else
            <input type="hidden" name="locale" value="{{ getDefaultLocale() }}">
        @endif

        {{-- Teacher --}}
        @if($isOrganization)
            <div class="form-group kemetic-group mt-15">
                <label class="kemetic-label">{{ trans('public.select_a_teacher') }}</label>
                <select name="teacher_id" class="kemetic-select @error('teacher_id') is-invalid @enderror">
                    <option disabled {{ empty($bundle) ? 'selected' : '' }}>
                        {{ trans('public.choose_instructor') }}
                    </option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}"
                            {{ !empty($bundle) && $bundle->teacher_id === $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->full_name }}
                        </option>
                    @endforeach
                </select>
                @error('teacher_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        @endif

        {{-- Title --}}
        <div class="form-group kemetic-group mt-15">
            <label class="kemetic-label">{{ trans('public.title') }}</label>
            <input type="text" name="title"
                   value="{{ (!empty($bundle) and !empty($bundle->translate($locale))) ? $bundle->translate($locale)->title : old('title') }}" 
                   
                   class="form-control kemetic-input @error('title') is-invalid @enderror">
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- SEO --}}
        <div class="form-group kemetic-group mt-15">
            <label class="kemetic-label">{{ trans('public.seo_description') }}</label>
            <input type="text" name="seo_description"
                   value="{{ (!empty($bundle) and !empty($bundle->translate($locale))) ? $bundle->translate($locale)->seo_description : old('seo_description') }}"
                   class="form-control kemetic-input @error('seo_description') is-invalid @enderror">
        </div>

        {{-- Thumbnail --}}
        <div class="form-group kemetic-group mt-15">
            <label class="kemetic-label">{{ trans('public.thumbnail_image') }}</label>
            <div class="kemetic-upload">
                <button type="button"
                        class="panel-file-manager kemetic-upload-btn"
                        data-input="thumbnail">
                    <i data-feather="upload"></i>
                </button>
                <input type="text" name="thumbnail" id="thumbnail"
                       value="{{ $bundle->thumbnail ?? old('thumbnail') }}"
                       class="form-control kemetic-input">
            </div>
        </div>

        {{-- Cover --}}
        <div class="form-group kemetic-group mt-15">
            <label class="kemetic-label">{{ trans('public.cover_image') }}</label>
            <div class="kemetic-upload">
                <button type="button"
                        class="panel-file-manager kemetic-upload-btn"
                        data-input="cover_image">
                    <i data-feather="upload"></i>
                </button>
                <input type="text" name="image_cover" id="cover_image"
                       value="{{ $bundle->image_cover ?? old('image_cover') }}"
                       class="form-control kemetic-input">
            </div>
        </div>

        {{-- Demo Video --}}
        <div class="form-group kemetic-group mt-25">
            <label class="kemetic-label">
                {{ trans('public.demo_video') }}
                <span class="kemetic-muted">({{ trans('public.optional') }})</span>
            </label>

            <select name="video_demo_source"
                    class="js-video-demo-source ">
                @foreach(\App\Models\Webinar::$videoDemoSource as $source)
                    <option value="{{ $source }}"
                        {{ !empty($bundle) && $bundle->video_demo_source == $source ? 'selected' : '' }}>
                        {{ trans('update.file_source_'.$source) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="js-video-demo-other-inputs form-group mt-0 kemetic-group
            {{ (empty($bundle) or $bundle->video_demo_source != 'secure_host') ? '' : 'd-none' }}">

            <label class="kemetic-label">
                {{ trans('update.path') }}
            </label>

            <div class="kemetic-input-wrap js-video-demo-path-input">

                {{-- Upload --}}
                <button type="button"
                        class="js-video-demo-path-upload kemetic-icon-btn panel-file-manager
                        {{ (empty($bundle) or empty($bundle->video_demo_source) or $bundle->video_demo_source == 'upload') ? '' : 'd-none' }}"
                        data-input="demo_video"
                        data-preview="holder">
                    <i data-feather="upload"></i>
                </button>

                {{-- Link --}}
                <button type="button"
                        class="js-video-demo-path-links kemetic-icon-btn
                        {{ (empty($bundle) or empty($bundle->video_demo_source) or $bundle->video_demo_source == 'upload') ? 'd-none' : '' }}">
                    <i data-feather="link"></i>
                </button>

                <input type="text"
                    name="video_demo"
                    id="demo_video"
                    value="{{ !empty($bundle) ? $bundle->video_demo : old('video_demo') }}"
                    class="form-control kemetic-input @error('video_demo') is-invalid @enderror"
                    placeholder="{{ trans('update.path') }}"/>

            </div>

            @error('video_demo')
                <div class="invalid-feedback d-block mt-5">
                    {{ $message }}
                </div>
            @enderror
        </div>

    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="form-group">
            <label class="input-label">{{ trans('public.description') }}</label>
            <textarea id="summernote" name="description" class="form-control @error('description')  is-invalid @enderror" placeholder="{{ trans('forms.webinar_description_placeholder') }}">{!! (!empty($bundle) and !empty($bundle->translate($locale))) ? $bundle->translate($locale)->description : old('description')  !!}</textarea>
            @error('description')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
</div>

@if($isOrganization)
    <div class="row">
        <div class="col-6">

            <div class="form-group mt-30 d-flex align-items-center">
                <label class="cursor-pointer mb-0 input-label" for="privateSwitch">{{ trans('webinars.private') }}</label>
                <div class="ml-30 custom-control custom-switch">
                    <input type="checkbox" name="private" class="custom-control-input" id="privateSwitch" {{ (!empty($bundle) and $bundle->private) ? 'checked' :  '' }}>
                    <label class="custom-control-label" for="privateSwitch"></label>
                </div>
            </div>

            <p class="text-gray font-12">{{ trans('webinars.create_private_course_hint') }}</p>
        </div>
    </div>

@endif
@push('scripts_bottom')
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>

    @push('scripts_bottom')
        <script>
            var videoDemoPathPlaceHolderBySource = {
                upload: '{{ trans('update.file_source_upload_placeholder') }}',
                youtube: '{{ trans('update.file_source_youtube_placeholder') }}',
                vimeo: '{{ trans('update.file_source_vimeo_placeholder') }}',
                external_link: '{{ trans('update.file_source_external_link_placeholder') }}',
            }
        </script>
    @endpush
@endpush
