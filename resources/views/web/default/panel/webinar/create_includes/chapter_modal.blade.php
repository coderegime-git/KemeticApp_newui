{{-- ===============================
    KEMETIC MODAL + FORM STYLES
================================ --}}
<style>
:root{
    --k-bg:#0f0f0f;
    --k-card:#161616;
    --k-border:#2a2a2a;
    --k-gold:#F2C94C;
    --k-gold-soft:rgba(242,201,76,.18);
    --k-text:#e6e6e6;
    --k-muted:#9a9a9a;
    --k-radius:16px;
}

/* Modal body */
.k-modal-body{
    background:var(--k-card);
    border:1px solid var(--k-border);
    border-radius:var(--k-radius);
    padding:25px;
}

/* Title */
.k-modal-title{
    color:var(--k-text);
    font-size:18px;
    font-weight:600;
    margin-bottom:20px;
    position:relative;
}

.k-modal-title::after{
    content:'';
    position:absolute;
    left:0;
    bottom:-8px;
    width:60px;
    height:2px;
    background:linear-gradient(90deg,var(--k-gold),transparent);
}

/* Form */
.k-form .input-label{
    color:var(--k-muted);
    font-size:13px;
    font-weight:500;
}

.k-form .form-control,
.k-form .custom-select{
    background:#101010;
    border:1px solid var(--k-border);
    color:var(--k-text);
    border-radius:10px;
}

.k-form .form-control:focus,
.k-form .custom-select:focus{
    border-color:var(--k-gold);
    box-shadow:0 0 0 2px var(--k-gold-soft);
}

/* Switch rows */
.k-switch-row{
    padding:10px 0;
    border-bottom:1px dashed var(--k-border);
}

.k-switch-row:last-child{
    border-bottom:none;
}

/* Switch labels */
.k-switch-row .js-switch{
    color:var(--k-text);
    font-size:14px;
}

/* Buttons */
.k-btn-save{
    background:linear-gradient(135deg,#F2C94C,#E0B63A);
    border:none;
    color:#000;
    font-weight:600;
    border-radius:10px;
    padding:6px 20px;
}

.k-btn-close{
    background:#1e1e1e;
    border:1px solid var(--k-border);
    color:var(--k-text);
    border-radius:10px;
}
</style>

{{-- ===============================
    NEW CHAPTER MODAL
================================ --}}
<div id="chapterModalHtml" class="d-none">

    <div class="k-modal-body">

        {{-- TITLE --}}
        <h2 class="k-modal-title">
            {{ trans('public.new_chapter') }}
        </h2>

        {{-- FORM --}}
        <div class=" chapter-form k-form mt-20"
             data-action="/panel/chapters/store">

            <input type="hidden"
                   name="ajax[chapter][webinar_id]"
                   class="js-chapter-webinar-id"
                   value="">

            {{-- LANGUAGE --}}
            @if(!empty(getGeneralSettings('content_translate')))
                <div class="form-group">
                    <label class="input-label">{{ trans('auth.language') }}</label>
                    <select name="ajax[chapter][locale]"
                            class="form-control js-chapter-locale"
                            data-webinar-id="{{ !empty($webinar) ? $webinar->id : '' }}"
                            data-id=""
                            data-relation="chapters"
                            data-fields="title">
                        @foreach($userLanguages as $lang => $language)
                            <option value="{{ $lang }}">{{ $language }}</option>
                        @endforeach
                    </select>
                </div>
            @else
                <input type="hidden"
                       name="ajax[chapter][locale]"
                       value="{{ $defaultLocale }}">
            @endif

            {{-- TITLE --}}
            <div class="form-group">
                <label class="input-label">{{ trans('public.chapter_title') }}</label>
                <input type="text"
                       name="ajax[chapter][title]"
                       class="form-control js-ajax-title"
                       placeholder="{{ trans('forms.maximum_64_characters') }}">
                <span class="invalid-feedback"></span>
            </div>

            {{-- ACTIVE SWITCH --}}
            <div class="form-group d-flex align-items-center justify-content-between k-switch-row js-switch-parent">
                <label class="js-switch cursor-pointer"
                       for="chapterStatus_record">
                    {{ trans('public.active') }}
                </label>

                <div class="custom-control custom-switch">
                    <input id="chapterStatus_record"
                           type="checkbox"
                           checked
                           name="ajax[chapter][status]"
                           class="custom-control-input js-chapter-status-switch">
                    <label class="custom-control-label"
                           for="chapterStatus_record"></label>
                </div>
            </div>

            {{-- SEQUENCE SWITCH --}}
            @if(getFeaturesSettings('sequence_content_status'))
                <div class="form-group d-flex align-items-center justify-content-between k-switch-row js-switch-parent">
                    <label class="js-switch cursor-pointer"
                           for="checkAllContentsPassSwitch_record">
                        {{ trans('update.check_all_contents_pass') }}
                    </label>

                    <div class="custom-control custom-switch">
                        <input id="checkAllContentsPassSwitch_record"
                               type="checkbox"
                               name="ajax[chapter][check_all_contents_pass]"
                               class="custom-control-input js-chapter-check-all-contents-pass">
                        <label class="custom-control-label"
                               for="checkAllContentsPassSwitch_record"></label>
                    </div>
                </div>
            @endif

            {{-- ACTIONS --}}
            <div class="d-flex align-items-center justify-content-end mt-25">
                <button type="button"
                        class="save-chapter k-btn-save">
                    {{ trans('public.save') }}
                </button>

                <button type="button"
                        class="close-swl k-btn-close ml-10">
                    {{ trans('public.close') }}
                </button>
            </div>

        </div>
    </div>
</div>
