<style>
    /* KEMETIC APP DESIGN SYSTEM */
.kemetic-accordion-item {
    background: #141414;
    border: 1px solid rgba(242, 201, 76, 0.3);
    padding: 15px 20px;
    border-radius: 18px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    transition: .3s ease;
}

.kemetic-accordion-header {
    cursor: pointer;
}

.kemetic-icon-wrapper i {
    color: #F2C94C;
}

.kemetic-title {
    color: #F2C94C;
    font-weight: 600;
}

.kemetic-action-icon i,
.kemetic-move-icon,
.kemetic-chevron {
    color: #F2C94C;
    cursor: pointer;
}

/* FORM */
.kemetic-form-group {
    margin-bottom: 18px;
}

.kemetic-label {
    color: #F2C94C;
    font-size: 14px;
    margin-bottom: 6px;
    display: block;
}

.kemetic-input,
.kemetic-input-icon input {
    background: #1A1A1A;
    border: 1px solid rgba(242,201,76,.3);
    color: #fff;
    border-radius: 14px;
    padding: 10px 14px;
    width: 100%;
    transition: .2s;
}

.kemetic-input:focus {
    border-color: #F2C94C;
}

/* ICON INPUT BUTTON */
.input-icon-btn {
    background: #F2C94C;
    border-radius: 12px 0 0 12px;
    border: none;
    padding: 10px;
    cursor: pointer;
}

.input-icon-btn i {
    color: #000;
}

/* RADIO */
.kemetic-radio input {
    accent-color: #F2C94C;
}

/* SWITCH */
.kemetic-switch {
    position: relative;
    display: inline-block;
    width: 48px;
    height: 22px;
}

.kemetic-switch input { display:none; }

.kemetic-switch span {
    position: absolute;
    cursor: pointer;
    background: #555;
    border-radius: 22px;
    top: 0; left: 0;
    right: 0; bottom: 0;
    transition: .3s;
}

.kemetic-switch span:before {
    content: "";
    height: 18px; width: 18px;
    position: absolute;
    background: white;
    border-radius: 50%;
    top: 2px; left: 2px;
    transition: .3s;
}

.kemetic-switch input:checked + span {
    background: #F2C94C;
}

.kemetic-switch input:checked + span:before {
    transform: translateX(26px);
}

/* BUTTONS */
.kemetic-btn-gold {
    background: #F2C94C;
    padding: 8px 16px;
    border-radius: 12px;
    color: #000;
    font-weight: 600;
    border: none;
}

.kemetic-btn-red {
    background: #D9534F;
    padding: 8px 16px;
    border-radius: 12px;
    color: #fff;
    border: none;
}

</style>

@if(!empty($file) and $file->storage == 'upload_archive')
    @include('web.default.panel.webinar.create_includes.accordions.new_interactive_file',['file' => $file])
@else
<li data-id="{{ !empty($chapterItem) ? $chapterItem->id :'' }}"
    class="accordion-row kemetic-accordion-item mt-20">

    <!-- HEADER -->
    <div class="kemetic-accordion-header d-flex align-items-center justify-content-between"
         role="tab"
         id="file_{{ !empty($file) ? $file->id :'record' }}">

        <div class="d-flex align-items-center kemetic-accordion-trigger"
             data-toggle="collapse"
             href="#collapseFile{{ !empty($file) ? $file->id :'record' }}"
             aria-expanded="true">

            <span class="kemetic-icon-wrapper mr-10">
                <i data-feather="{{ !empty($file) ? $file->getIconByType() : 'file' }}"></i>
            </span>

            <div class="kemetic-title">
                {{ !empty($file) ? $file->title . ($file->accessibility == 'free' ? " (". trans('public.free') .")" : '') : trans('public.add_new_files') }}
            </div>
        </div>

        <div class="d-flex align-items-center">

            @if(!empty($file) and $file->status != \App\Models\WebinarChapter::$chapterActive)
                <span class="kemetic-badge mr-10">{{ trans('public.disabled') }}</span>
            @endif

            @if(!empty($file))
                <button type="button"
                        data-item-id="{{ $file->id }}"
                        data-item-type="{{ \App\Models\WebinarChapterItem::$chapterFile }}"
                        data-chapter-id="{{ !empty($chapter) ? $chapter->id : '' }}"
                        class="kemetic-action-icon mr-10 js-change-content-chapter">
                    <i data-feather="grid"></i>
                </button>
            @endif

            <i data-feather="move"
               class="kemetic-move-icon mr-10"></i>

            @if(!empty($file))
                <a href="/panel/files/{{ $file->id }}/delete"
                   class="kemetic-action-icon delete-action">
                    <i data-feather="trash-2"></i>
                </a>
            @endif

            <i class="kemetic-chevron"
               data-feather="chevron-down"
               data-toggle="collapse"
               href="#collapseFile{{ !empty($file) ? $file->id :'record' }}">
            </i>
        </div>
    </div>

    <!-- BODY -->
    <div id="collapseFile{{ !empty($file) ? $file->id :'record' }}"
         class="collapse @if(empty($file)) show @endif kemetic-accordion-body">

        <div class="file-form"
             data-action="/panel/files/{{ !empty($file) ? $file->id . '/update' : 'store' }}">

            <input type="hidden"
                   name="ajax[{{ !empty($file) ? $file->id : 'new' }}][webinar_id]"
                   value="{{ !empty($webinar) ? $webinar->id :'' }}">

            <div class="row">
                <div class="col-12 col-lg-6">

                    {{-- Language --}}
                    @if(!empty(getGeneralSettings('content_translate')))
                        <div class="kemetic-form-group">
                            <label class="kemetic-label">{{ trans('auth.language') }}</label>
                            <select class="kemetic-input"
                                    name="ajax[{{ !empty($file) ? $file->id : 'new' }}][locale]"
                                    data-webinar-id="{{ !empty($webinar) ? $webinar->id : '' }}"
                                    data-id="{{ !empty($file) ? $file->id : '' }}"
                                    data-relation="files"
                                    data-fields="title,description">

                                @foreach($userLanguages as $lang => $language)
                                    <option value="{{ $lang }}"
                                        {{ (!empty($file) and $file->locale == $lang) ? 'selected' : '' }}>
                                        {{ $language }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- Chapter --}}
                    @if(!empty($file))
                        <div class="kemetic-form-group">
                            <label class="kemetic-label">{{ trans('public.chapter') }}</label>
                            <select class="kemetic-input"
                                    name="ajax[{{ $file->id }}][chapter_id]">
                                @foreach($webinar->chapters as $ch)
                                    <option value="{{ $ch->id }}"
                                        {{ $file->chapter_id == $ch->id ? 'selected' : '' }}>
                                        {{ $ch->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- Title --}}
                    <div class="kemetic-form-group">
                        <label class="kemetic-label">{{ trans('public.title') }}</label>
                        <input type="text"
                               class="kemetic-input js-ajax-title"
                               name="ajax[{{ !empty($file) ? $file->id : 'new' }}][title]"
                               value="{{ !empty($file) ? $file->title : '' }}"
                               placeholder="{{ trans('forms.maximum_255_characters') }}">
                    </div>

                    {{-- Source --}}
                    <div class="kemetic-form-group">
                        <label class="kemetic-label">{{ trans('public.source') }}</label>
                        <select class="kemetic-input js-file-storage"
                                name="ajax[{{ !empty($file) ? $file->id : 'new' }}][storage]">
                            @foreach(getFeaturesSettings('available_sources') as $source)
                                <option value="{{ $source }}"
                                        @if(!empty($file) and $file->storage == $source) selected @endif>
                                    {{ trans('update.file_source_'.$source) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Accessibility --}}
                    <div class="kemetic-form-group">
                        <label class="kemetic-label">{{ trans('public.accessibility') }}</label>

                        <div class="kemetic-radio-row js-ajax-accessibility">
                            <label class="kemetic-radio">
                                <input type="radio"
                                       value="free"
                                       name="ajax[{{ !empty($file) ? $file->id : 'new' }}][accessibility]"
                                       {{ empty($file) || $file->accessibility=='free' ? 'checked' : '' }}>
                                <span>Free</span>
                            </label>

                            <label class="kemetic-radio ml-15">
                                <input type="radio"
                                       value="paid"
                                       name="ajax[{{ !empty($file) ? $file->id : 'new' }}][accessibility]"
                                       {{ !empty($file) && $file->accessibility=='paid' ? 'checked' : '' }}>
                                <span>Paid</span>
                            </label>
                        </div>
                    </div>

                    {{-- File Path --}}
                    <div class="kemetic-form-group js-file-path-input {{ (!empty($file) and $file->storage == 's3') ? 'd-none' : '' }}">
                        <label class="kemetic-label">{{ trans('webinars.file_upload_placeholder') }}</label>
                        <div class="kemetic-input-icon">
                            <button class="input-icon-btn panel-file-manager"
                                    data-input="file_path{{ !empty($file) ? $file->id : 'record' }}">
                                <i data-feather="upload"></i>
                            </button>
                            <input type="text"
                                   id="file_path{{ !empty($file) ? $file->id : 'record' }}"
                                   class="kemetic-input js-ajax-file_path"
                                   name="ajax[{{ !empty($file) ? $file->id : 'new' }}][file_path]"
                                   value="{{ !empty($file) ? $file->file : '' }}">
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="kemetic-form-group">
                        <label class="kemetic-label">{{ trans('public.description') }}</label>
                        <textarea class="kemetic-input js-ajax-description"
                                  rows="6"
                                  name="ajax[{{ !empty($file) ? $file->id : 'new' }}][description]">
                            {{ !empty($file) ? $file->description : '' }}
                        </textarea>
                    </div>

                    {{-- Switches --}}
                    <div class="kemetic-switch-row mt-20">
                        <label>{{ trans('update.online_viewer') }}</label>
                        <label class="kemetic-switch">
                            <input type="checkbox"
                                   name="ajax[{{ !empty($file) ? $file->id : 'new' }}][online_viewer]"
                                   {{ !empty($file) && $file->online_viewer ? 'checked' : '' }}>
                            <span></span>
                        </label>
                    </div>

                    <div class="kemetic-switch-row mt-20">
                        <label>{{ trans('home.downloadable') }}</label>
                        <label class="kemetic-switch">
                            <input type="checkbox"
                                   name="ajax[{{ !empty($file) ? $file->id : 'new' }}][downloadable]"
                                   {{ empty($file) || $file->downloadable ? 'checked' : '' }}>
                            <span></span>
                        </label>
                    </div>

                    <div class="kemetic-switch-row mt-20">
                        <label>{{ trans('public.active') }}</label>
                        <label class="kemetic-switch">
                            <input type="checkbox"
                                   name="ajax[{{ !empty($file) ? $file->id : 'new' }}][status]"
                                   {{ empty($file) || $file->status == \App\Models\File::$Active ? 'checked' : '' }}>
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- SAVE BUTTON -->
            <div class="kemetic-actions mt-30">
                <button class="kemetic-btn-gold js-save-file">{{ trans('public.save') }}</button>

                @if(empty($file))
                    <button class="kemetic-btn-red ml-10 cancel-accordion">{{ trans('public.close') }}</button>
                @endif
            </div>
        </div>
    </div>
</li>
    @push('scripts_bottom')
        <script>
            var filePathPlaceHolderBySource = {
                upload: '{{ trans('update.file_source_upload_placeholder') }}',
                youtube: '{{ trans('update.file_source_youtube_placeholder') }}',
                vimeo: '{{ trans('update.file_source_vimeo_placeholder') }}',
                external_link: '{{ trans('update.file_source_external_link_placeholder') }}',
                google_drive: '{{ trans('update.file_source_google_drive_placeholder') }}',
                dropbox: '{{ trans('update.file_source_dropbox_placeholder') }}',
                iframe: '{{ trans('update.file_source_iframe_placeholder') }}',
                s3: '{{ trans('update.file_source_s3_placeholder') }}',
            }
        </script>
    @endpush
@endif
