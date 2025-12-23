<style>
    /* WRAPPER */
.kemetic-accordion-item{
    background:#111;
    border:1px solid rgba(242,201,76,0.25);
    border-radius:14px;
    padding:15px 18px;
    margin-top:18px;
    transition:.3s ease;
}
.kemetic-accordion-item:hover{
    border-color:#F2C94C;
    box-shadow:0 0 12px rgba(242,201,76,0.25);
}

/* HEADER */
.kemetic-accordion-header{
    display:flex;
    align-items:center;
    justify-content:space-between;
    cursor:pointer;
    color:#F2C94C;
}

.kemetic-accordion-title{
    display:flex;
    align-items:center;
}

.kemetic-icon i{
    color:#F2C94C;
    width:22px;
    height:22px;
    margin-right:10px;
}

.kemetic-title-text{
    color:#fff;
    font-weight:600;
}

/* ACTIONS */
.kemetic-header-actions{
    display:flex;
    align-items:center;
    gap:10px;
}

.kemetic-move-icon,
.kemetic-chevron,
.kemetic-icon-btn i,
.kemetic-delete-btn i{
    color:#F2C94C;
    cursor:pointer;
    width:20px;
    height:20px;
}

.kemetic-badge-disabled{
    background:#8a0000;
    color:#fff;
    padding:2px 8px;
    border-radius:6px;
    font-size:12px;
}

/* COLLAPSE BODY */
.kemetic-collapse-body{
    margin-top:12px;
}

.kemetic-body-inner{
    background:#0b0b0b;
    padding:20px;
    border-radius:12px;
    border:1px solid rgba(242,201,76,0.18);
}

/* FORM ELEMENTS */
.kemetic-form-group label{
    color:#F2C94C;
    margin-bottom:6px;
    font-size:14px;
}

.kemetic-input{
    width:100%;
    background:#111;
    border:1px solid rgba(242,201,76,0.25);
    color:#fff;
    padding:8px 12px;
    border-radius:10px;
}

.kemetic-input:focus{
    border-color:#F2C94C;
    box-shadow:0 0 6px rgba(242,201,76,0.35);
}

/* FILE INPUT */
.kemetic-input-file-group{
    display:flex;
    align-items:center;
}

.kemetic-file-btn{
    background:#F2C94C;
    border:none;
    padding:6px 10px;
    color:#000;
    border-radius:8px 0 0 8px;
}

.kemetic-file-btn i{
    color:#000;
}

/* SWITCH */
.kemetic-switch input{
    display:none;
}
.kemetic-switch span{
    width:40px;
    height:20px;
    background:#444;
    border-radius:20px;
    position:relative;
    display:inline-block;
}
.kemetic-switch span::after{
    content:'';
    width:18px;
    height:18px;
    background:#F2C94C;
    position:absolute;
    top:1px;
    left:1px;
    border-radius:50%;
    transition:.3s;
}
.kemetic-switch input:checked + span{
    background:#F2C94C;
}
.kemetic-switch input:checked + span::after{
    left:20px;
    background:#000;
}

/* BUTTONS */
.kemetic-save-row{
    margin-top:25px;
    display:flex;
    align-items:center;
    gap:15px;
}

.kemetic-btn-save{
    background:#F2C94C;
    color:#000;
    padding:6px 18px;
    border-radius:8px;
    font-weight:600;
}

.kemetic-btn-cancel{
    background:#822;
    color:#fff;
    padding:6px 18px;
    border-radius:8px;
}

</style>

<li data-id="{{ !empty($chapterItem) ? $chapterItem->id :'' }}" 
    class="kemetic-accordion-item">

    <!-- HEADER -->
    <div class="kemetic-accordion-header" 
         role="tab" 
         id="file_{{ !empty($file) ? $file->id :'record' }}"
         data-toggle="collapse" 
         href="#collapseFile{{ !empty($file) ? $file->id :'record' }}"
         aria-expanded="true">

        <div class="kemetic-accordion-title">
            <span class="kemetic-icon">
                <i data-feather="{{ !empty($file) ? $file->getIconByType() : 'file' }}"></i>
            </span>

            <div class="kemetic-title-text">
                {{ !empty($file) ? $file->title . ($file->accessibility == 'free' ? " (". trans('public.free') .")" : '') : trans('public.add_new_files') }}
            </div>
        </div>

        <div class="kemetic-header-actions">

            @if(!empty($file) and $file->status != \App\Models\WebinarChapter::$chapterActive)
                <span class="kemetic-badge-disabled">{{ trans('public.disabled') }}</span>
            @endif

            @if(!empty($file))
                <button type="button"
                        data-item-id="{{ $file->id }}"
                        data-item-type="{{ \App\Models\WebinarChapterItem::$chapterFile }}"
                        class="kemetic-icon-btn js-change-content-chapter">
                    <i data-feather="grid"></i>
                </button>
            @endif

            <i data-feather="move" class="kemetic-move-icon"></i>

            @if(!empty($file))
                <a href="/panel/files/{{ $file->id }}/delete" 
                   class="kemetic-delete-btn">
                    <i data-feather="trash-2"></i>
                </a>
            @endif

            <i class="kemetic-chevron" 
               data-feather="chevron-down"></i>
        </div>
    </div>


    <!-- CONTENT -->
    <div id="collapseFile{{ !empty($file) ? $file->id :'record' }}"
         class="collapse kemetic-collapse-body @if(empty($file)) show @endif">

        <div class="kemetic-body-inner">

            <div class="js-content-form file-form"
                 data-action="/panel/files/{{ !empty($file) ? $file->id . '/update' : 'store' }}">

                <input type="hidden" name="ajax[{{ !empty($file) ? $file->id :'new' }}][webinar_id]" 
                       value="{{ !empty($webinar) ? $webinar->id :'' }}">
                <input type="hidden" name="ajax[{{ !empty($file) ? $file->id :'new' }}][storage]" value="upload_archive">
                <input type="hidden" name="ajax[{{ !empty($file) ? $file->id :'new' }}][file_type]" value="archive">

                <div class="row">
                    <div class="col-12 col-lg-6">

                        {{-- LANGUAGE SELECT --}}
                        @if(!empty(getGeneralSettings('content_translate')))
                            <div class="kemetic-form-group">
                                <label>{{ trans('auth.language') }}</label>
                                
                                <select name="ajax[{{ !empty($file) ? $file->id : 'new' }}][locale]"
                                        class="kemetic-input">
                                    @foreach($userLanguages as $lang => $language)
                                        <option value="{{ $lang }}"
                                            {{ (!empty($file) and $file->locale==$lang) ? 'selected':'' }}>
                                            {{ $language }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif


                        {{-- TITLE --}}
                        <div class="kemetic-form-group">
                            <label>{{ trans('public.title') }}</label>
                            <input type="text" name="ajax[{{ !empty($file) ? $file->id :'new' }}][title]"
                                   class="kemetic-input js-ajax-title"
                                   value="{{ !empty($file) ? $file->title : '' }}">
                        </div>

                        {{-- CHAPTER --}}
                        @if(!empty($file))
                        <div class="kemetic-form-group">
                            <label>{{ trans('public.chapter') }}</label>
                            <select class="kemetic-input js-ajax-chapter_id"
                                    name="ajax[{{ $file->id }}][chapter_id]">
                                @foreach($webinar->chapters as $ch)
                                    <option value="{{ $ch->id }}" {{ $file->chapter_id == $ch->id ? 'selected':'' }}>
                                        {{ $ch->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif


                        {{-- INTERACTIVE TYPE --}}
                        <div class="kemetic-form-group">
                            <label>{{ trans('update.interactive_type') }}</label>
                            <select class="kemetic-input js-interactive-type"
                                    name="ajax[{{ !empty($file) ? $file->id :'new' }}][interactive_type]">
                                <option value="adobe_captivate"
                                        {{ (!empty($file) && $file->interactive_type=='adobe_captivate') ? 'selected':'' }}>
                                    {{ trans('update.adobe_captivate') }}
                                </option>

                                <option value="i_spring"
                                        {{ (!empty($file) && $file->interactive_type=='i_spring') ? 'selected':'' }}>
                                    {{ trans('update.i_spring') }}
                                </option>

                                <option value="custom"
                                        {{ (!empty($file) && $file->interactive_type=='custom') ? 'selected':'' }}>
                                    {{ trans('update.custom') }}
                                </option>
                            </select>
                        </div>


                        {{-- ZIP FILE --}}
                        <div class="kemetic-form-group">
                            <label>{{ trans('update.choose_zip_file') }}</label>

                            <div class="kemetic-input-file-group">
                                <button type="button" 
                                        class="kemetic-file-btn panel-file-manager"
                                        data-input="file_path{{ !empty($file) ? $file->id :'record' }}">
                                    <i data-feather="upload"></i>
                                </button>

                                <input type="text"
                                       id="file_path{{ !empty($file) ? $file->id :'record' }}"
                                       name="ajax[{{ !empty($file) ? $file->id :'new' }}][file_path]"
                                       class="kemetic-input js-ajax-file_path"
                                       value="{{ !empty($file) ? $file->file : '' }}">
                            </div>
                        </div>


                        {{-- DESCRIPTION --}}
                        <div class="kemetic-form-group">
                            <label>{{ trans('public.description') }}</label>
                            <textarea class="kemetic-input js-ajax-description" rows="5"
                                      name="ajax[{{ !empty($file) ? $file->id :'new' }}][description]">
                                {{ !empty($file) ? $file->description : '' }}
                            </textarea>
                        </div>


                        {{-- STATUS --}}
                        <div class="kemetic-form-switch">
                            <label>{{ trans('public.active') }}</label>
                            <label class="kemetic-switch">
                                <input type="checkbox" 
                                       name="ajax[{{ !empty($file) ? $file->id :'new' }}][status]"
                                       {{ (empty($file) || $file->status==1) ? 'checked':'' }}>
                                <span></span>
                            </label>
                        </div>

                    </div>
                </div>


                <!-- SAVE BUTTON -->
                <div class="kemetic-save-row">
                    <button type="button" class="kemetic-btn-save js-save-file">
                        {{ trans('public.save') }}
                    </button>

                    @if(empty($file))
                        <button type="button" class="kemetic-btn-cancel cancel-accordion">
                            {{ trans('public.close') }}
                        </button>
                    @endif
                </div>

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