<style>
    /* -------------------------------------------------
    KEMETIC BLACK GOLD THEME
--------------------------------------------------*/
:root {
    --k-bg: #121212;
    --k-surface: #1A1A1A;
    --k-border: #2A2A2A;
    --k-gold: #D4AF37;
    --k-gold-soft: rgba(212, 175, 55, 0.4);
    --k-text: #F2F2F2;
    --k-danger: #d9534f;
    --k-radius: 14px;
    --k-transition: 0.25s ease;
}

/* WRAPPER */
.kemetic-item-wrapper {
    background: var(--k-surface);
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius);
    padding: 18px 20px;
    transition: var(--k-transition);
}
.kemetic-item-wrapper:hover {
    border-color: var(--k-gold);
    box-shadow: 0 0 12px rgba(212, 175, 55, 0.2);
}

/* HEADER */
.kemetic-item-header {
    padding: 0;
    cursor: pointer;
}
.kemetic-item-header .kemetic-title {
    color: var(--k-text);
    font-weight: 600;
    font-size: 16px;
}

/* ICON */
.kemetic-icon i {
    color: var(--k-gold);
    height: 20px;
    width: 20px;
}

/* DRAG ICON */
.kemetic-drag-icon {
    color: var(--k-gold);
    opacity: 0.6;
}
.kemetic-drag-icon:hover {
    opacity: 1;
}

/* CHEVRON */
.kemetic-chevron {
    color: var(--k-gold);
    transition: 0.25s;
}
.kemetic-chevron[aria-expanded="true"] {
    transform: rotate(180deg);
}

/* COLLAPSE */
.kemetic-collapse {
    border-top: 1px solid var(--k-border);
    margin-top: 15px;
    padding-top: 15px;
}
.kemetic-collapse-body {
    padding: 15px 5px;
}

/* LABEL */
.kemetic-label {
    color: var(--k-gold);
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
}

/* INPUTS */
.kemetic-input {
    width: 100%;
    background: var(--k-bg);
    border: 1px solid var(--k-border);
    padding: 10px 12px;
    border-radius: var(--k-radius);
    color: var(--k-text);
    transition: var(--k-transition);
}
.kemetic-input:focus {
    border-color: var(--k-gold);
    box-shadow: 0 0 0 3px var(--k-gold-soft);
}

/* GROUP */
.kemetic-group {
    margin-bottom: 18px;
}

/* BUTTONS */
.kemetic-btn-primary {
    background: var(--k-gold);
    color: #000;
    padding: 8px 18px;
    border-radius: var(--k-radius);
    font-weight: 600;
    transition: var(--k-transition);
}
.kemetic-btn-primary:hover {
    background: #b8962c;
}

.kemetic-btn-danger {
    background: var(--k-danger);
    color: #fff !important;
    padding: 8px 18px;
    border-radius: var(--k-radius);
    font-weight: 600;
    border: none;
}
.kemetic-btn-danger:hover {
    opacity: .9;
}

.kemetic-btn-icon {
    background: transparent;
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius);
    padding: 6px 10px;
    color: var(--k-text);
    transition: var(--k-transition);
}
.kemetic-btn-icon:hover {
    border-color: var(--k-gold);
    color: var(--k-gold);
}

/* BADGE */
.kemetic-badge-disabled {
    padding: 4px 8px;
    font-size: 11px;
    background: #444;
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius);
    color: #ccc;
}

/* FILE INPUT GROUP */
.kemetic-input-group {
    display: flex;
    align-items: center;
}
.kemetic-file-btn {
    background: var(--k-gold);
    border-radius: var(--k-radius) 0 0 var(--k-radius);
    padding: 8px 12px;
    cursor: pointer;
    color: black !important;
    border: none;
}
.kemetic-file-btn:hover {
    background: #bb9733;
}
.kemetic-input-group .kemetic-input {
    border-radius: 0 var(--k-radius) var(--k-radius) 0;
    margin-left: -1px;
}

/* RADIO */
.kemetic-radio-group {
    display: flex;
    gap: 15px;
}
.kemetic-radio input {
    accent-color: var(--k-gold);
}
.kemetic-radio span {
    color: var(--k-text);
}

/* SWITCH */
.kemetic-switch {
    position: relative;
    width: 42px;
    height: 20px;
    display: inline-block;
}
.kemetic-switch input {
    display: none;
}
.kemetic-switch span {
    position: absolute;
    cursor: pointer;
    background: #555;
    border-radius: 20px;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    transition: var(--k-transition);
}
.kemetic-switch span::before {
    content: "";
    position: absolute;
    height: 16px;
    width: 16px;
    left: 2px;
    bottom: 2px;
    background: white;
    border-radius: 50%;
    transition: var(--k-transition);
}
.kemetic-switch input:checked + span {
    background: var(--k-gold);
}
.kemetic-switch input:checked + span::before {
    transform: translateX(22px);
}

/* SUMMERNOTE CONTENT */
.note-editor {
    background: var(--k-surface) !important;
    border-color: var(--k-border) !important;
}
.note-editor .note-editable {
    background: var(--k-bg) !important;
    color: var(--k-text) !important;
}
.note-toolbar {
    background: var(--k-surface) !important;
    border-color: var(--k-border) !important;
}

/* SELECT2 */
.select2-container .select2-selection--single,
.select2-container .select2-selection--multiple {
    background: var(--k-bg) !important;
    border: 1px solid var(--k-border) !important;
    color: var(--k-text) !important;
    border-radius: var(--k-radius) !important;
    min-height: 45px;
}
.select2-container .select2-selection__rendered {
    color: var(--k-text) !important;
}
.select2-container .select2-selection__arrow b {
    border-color: var(--k-gold) transparent transparent transparent !important;
}
.select2-container--default .select2-results__option--highlighted {
    background: var(--k-gold) !important;
    color: #000 !important;
}
/* MULTIPLE SELECT TAGS (PILLS) */
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: var(--k-gold) !important;
    border: 1px solid var(--k-border) !important;
    color: #111 !important;
    border-radius: 4px;
    margin-top: 6px;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #111 !important;
    margin-right: 5px;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #000 !important;
    background: transparent !important;
}
.select2-dropdown {
    background: #1a1a1a !important;
    border: 1px solid var(--k-border) !important;
    color: var(--k-text) !important;
}

/* FOOTER BUTTON WRAP */
.kemetic-btn-footer {
    display: flex;
    align-items: center;
    gap: 10px;
    padding-top: 10px;
}

/* =====================================================
   MACOS / SAFARI FIX
   Sortable parent <li> with cursor:move blocks pointer-events
   and -webkit-user-select on child inputs/textareas in Safari.
   Force all form elements inside text_lesson-form to be
   always interactive.
====================================================== */
.text_lesson-form {
    position: relative;
    z-index: 2;
}

.text_lesson-form input,
.text_lesson-form textarea,
.text_lesson-form select,
.text_lesson-form label,
.text_lesson-form button,
.text_lesson-form .kemetic-input,
.text_lesson-form .kemetic-radio,
.text_lesson-form .kemetic-switch {
    position: relative;
    z-index: 3;
    pointer-events: auto !important;
    -webkit-user-select: text !important;
    user-select: text !important;
    touch-action: auto !important;
    cursor: auto;
}

.text_lesson-form button,
.text_lesson-form .kemetic-btn-primary,
.text_lesson-form .kemetic-btn-danger,
.text_lesson-form .kemetic-file-btn,
.text_lesson-form label.kemetic-switch {
    cursor: pointer !important;
}

.text_lesson-form .select2-container {
    position: relative;
    z-index: 4;
    pointer-events: auto !important;
    touch-action: auto !important;
}

/* Ensure select2 dropdown renders above everything */
.select2-dropdown {
    z-index: 99999 !important;
}

/* Fix kemetic-collapse-body so it doesn't inherit grab cursor */
.kemetic-collapse-body {
    cursor: default;
}


/* ── Text Lesson Mobile Fix ── */
@media (max-width: 767px) {
    .kemetic-item-wrapper {
        padding: 10px 8px;
    }
    .kemetic-item-header {
        flex-wrap: wrap;
        gap: 8px;
    }
    .kemetic-item-header .kemetic-title {
        font-size: 13px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 130px;
    }
    .kemetic-collapse-body {
        padding: 8px 2px;
    }
    .kemetic-input-group {
        flex-wrap: nowrap;
    }
    .kemetic-radio-group {
        flex-direction: column;
        gap: 8px;
    }
    .kemetic-btn-footer {
        flex-direction: column;
    }
    .kemetic-btn-footer button {
        width: 100%;
        margin-left: 0 !important;
        margin-top: 6px;
    }
    .note-editor {
        min-height: 120px !important;
    }
}
</style>
<li data-id="{{ !empty($chapterItem) ? $chapterItem->id :'' }}"
    class="accordion-row kemetic-item-wrapper mt-20">

    <div class="kemetic-item-header d-flex align-items-center justify-content-between"
         role="tab"
         id="text_lesson_{{ !empty($textLesson) ? $textLesson->id :'record' }}">

        <div class="d-flex align-items-center"
             data-bs-target="#collapseTextLesson{{ !empty($textLesson) ? $textLesson->id :'record' }}"
             aria-controls="collapseTextLesson{{ !empty($textLesson) ? $textLesson->id :'record' }}"
             data-bs-parent="#chapterContentAccordion{{ !empty($chapter) ? $chapter->id :'' }}"
             role="button" data-bs-toggle="collapse" aria-expanded="true">

            <span class="kemetic-icon mr-10">
                <i data-feather="file-text"></i>
            </span>

            <div class="kemetic-title">
                {{ !empty($textLesson) ? $textLesson->title . ($textLesson->accessibility == 'free' ? " (". trans('public.free') .")" : '') : trans('public.add_new_test_lesson') }}
            </div>
        </div>

        <div class="d-flex align-items-center">

            @if(!empty($textLesson) and $textLesson->status != \App\Models\WebinarChapter::$chapterActive)
                <span class="kemetic-badge-disabled mr-10">{{ trans('public.disabled') }}</span>
            @endif

            @if(!empty($textLesson))
                <button type="button"
                        data-item-id="{{ $textLesson->id }}"
                        data-item-type="{{ \App\Models\WebinarChapterItem::$chapterTextLesson }}"
                        data-chapter-id="{{ !empty($chapter) ? $chapter->id : '' }}"
                        class="kemetic-btn-icon js-change-content-chapter mr-10">
                    <i data-feather="grid"></i>
                </button>
            @endif

            <i data-feather="move" class="kemetic-drag-icon mr-10 cursor-pointer"></i>

            @if(!empty($textLesson))
                <a href="/panel/text-lesson/{{ $textLesson->id }}/delete"
                   class="kemetic-btn-icon delete-action mr-10">
                    <i data-feather="trash-2"></i>
                </a>
            @endif

            <div class="kemetic-chevron"
                 data-bs-target="#collapseTextLesson{{ !empty($textLesson) ? $textLesson->id :'record' }}"
                 aria-controls="collapseTextLesson{{ !empty($textLesson) ? $textLesson->id :'record' }}"
                 data-bs-parent="#chapterContentAccordion{{ !empty($chapter) ? $chapter->id :'' }}"
                 role="button" data-bs-toggle="collapse" aria-expanded="true">
                <i data-feather="chevron-down" height="20"></i>
            </div>
        </div>
    </div>


    <!-- COLLAPSE BODY -->
    <div id="collapseTextLesson{{ !empty($textLesson) ? $textLesson->id :'record' }}"
         class="collapse kemetic-collapse @if(empty($textLesson)) show @endif"
         aria-labelledby="text_lesson_{{ !empty($textLesson) ? $textLesson->id :'record' }}"
         role="tabpanel">

        <div class="kemetic-collapse-body">

            <div class="text_lesson-form"
                 data-action="/panel/text-lesson/{{ !empty($textLesson) ? $textLesson->id . '/update' : 'store' }}">

                <input type="hidden"
                       name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][webinar_id]"
                       value="{{ !empty($webinar) ? $webinar->id :'' }}">

                <div class="row">

                    <!-- LEFT SECTION -->
                    <div class="col-12 col-lg-6">
                        
                        {{-- LANGUAGE --}}
                        @if(!empty(getGeneralSettings('content_translate')))
                        <div class="kemetic-group">
                            <label class="kemetic-label">{{ trans('auth.language') }}</label>
                            <select name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][locale]"
                                    class="kemetic-input {{ !empty($textLesson) ? 'js-webinar-content-locale' : '' }}"
                                    data-webinar-id="{{ !empty($webinar) ? $webinar->id : '' }}"
                                    data-id="{{ !empty($textLesson) ? $textLesson->id : '' }}"
                                    data-relation="textLessons"
                                    data-fields="title,summary,content">
                                @foreach($userLanguages as $lang => $language)
                                    <option value="{{ $lang }}"
                                        {{ (!empty($textLesson) and !empty($textLesson->locale)) ?
                                        (mb_strtolower($textLesson->locale) == mb_strtolower($lang) ? 'selected' : '') :
                                        ($locale == $lang ? 'selected' : '') }}>
                                        {{ $language }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        <input type="hidden"
                               name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][locale]"
                               value="{{ $defaultLocale }}">
                        @endif

                        {{-- CHAPTER SELECT --}}
                        @if(!empty($textLesson))
                        <div class="kemetic-group">
                            <label class="kemetic-label">{{ trans('public.chapter') }}</label>
                            <select name="ajax[{{ $textLesson->id }}][chapter_id]"
                                    class="kemetic-input js-ajax-chapter_id">
                                @foreach($webinar->chapters as $ch)
                                    <option value="{{ $ch->id }}"
                                        {{ ($textLesson->chapter_id == $ch->id) ? 'selected' : '' }}>
                                        {{ $ch->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        <input type="hidden" name="ajax[new][chapter_id]" class="chapter-input">
                        @endif


                        <!-- TITLE -->
                        <div class="kemetic-group">
                            <label class="kemetic-label">{{ trans('public.title') }} <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][title]"
                                   class="kemetic-input js-ajax-title"
                                   value="{{ !empty($textLesson) ? $textLesson->title : '' }}">
                        </div>


                        <!-- STUDY TIME -->
                        <div class="kemetic-group">
                            <label class="kemetic-label">{{ trans('public.study_time') }} (Min) <span class="text-danger">*</span></label>
                            <input type="number"
                                   name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][study_time]"
                                   class="kemetic-input js-ajax-study_time"
                                   value="{{ !empty($textLesson) ? $textLesson->study_time : '' }}">
                        </div>


                        <!-- IMAGE -->
                        <div class="kemetic-group">
                            <label class="kemetic-label">{{ trans('public.image') }}</label>
                            <div class="kemetic-input-group">
                                <button type="button"
                                        class="kemetic-file-btn panel-file-manager"
                                        data-input="image{{ !empty($textLesson) ? $textLesson->id :'record' }}">
                                    <i data-feather="arrow-up"></i>
                                </button>
                                <input type="text"
                                       id="image{{ !empty($textLesson) ? $textLesson->id :'record' }}"
                                       class="kemetic-input js-ajax-image"
                                       name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][image]"
                                       value="{{ !empty($textLesson) ? $textLesson->image : '' }}">
                            </div>
                        </div>


                        <!-- ACCESSIBILITY -->
                        <div class="kemetic-group">
                            <label class="kemetic-label">{{ trans('public.accessibility') }}</label>
                            <div class="kemetic-radio-group">

                                <label class="kemetic-radio">
                                    <input type="radio"
                                           name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][accessibility]"
                                           value="free"
                                           @if(empty($textLesson) or $textLesson->accessibility == 'free') checked @endif>
                                    <span>Free</span>
                                </label>

                                <label class="kemetic-radio">
                                    <input type="radio"
                                           name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][accessibility]"
                                           value="paid"
                                           @if(!empty($textLesson) and $textLesson->accessibility == 'paid') checked @endif>
                                    <span>Paid</span>
                                </label>

                            </div>
                        </div>


                        <!-- ATTACHMENTS -->
                        @php
                            if (!empty($textLesson)) {
                                $textLessonAttachmentsFileIds = $textLesson->attachments->pluck('file_id')->toArray();
                            } else {
                                $textLessonAttachmentsFileIds = [];
                            }
                        @endphp
                        @if(!empty($webinar->files) && count($webinar->files) > 0)
                        <div class="kemetic-group">
                            <label class="kemetic-label">{{ trans('public.attachments') }}</label>
                            <select class="js-ajax-attachments kemetic-input attachments-select2"
                                    multiple
                                    name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][attachments]">
                                @foreach($webinar->files as $filesInfo)
                                    <option value="{{ $filesInfo->id }}"
                                        @if(!empty($textLesson) and in_array($filesInfo->id,$textLessonAttachmentsFileIds)) selected @endif>
                                        {{ $filesInfo->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif


                        <!-- SUMMARY -->
                        <div class="kemetic-group">
                            <label class="kemetic-label">{{ trans('public.summary') }} <span class="text-danger">*</span></label>
                            <textarea class="kemetic-input js-ajax-summary" rows="6"
                                      name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][summary]">
                                {{ !empty($textLesson) ? $textLesson->summary : '' }}
                            </textarea>
                        </div>
                    </div>


                    <!-- CONTENT -->
                    <div class="col-12">
                        <div class="kemetic-group">
                            <label class="kemetic-label">{{ trans('public.content') }} <span class="text-danger">*</span></label>

                            <div class="content-summernote js-ajax-file_path">
                                <textarea class="js-content-summernote kemetic-input {{ !empty($textLesson) ? 'js-content-'.$textLesson->id : '' }}">
                                    {{ !empty($textLesson) ? $textLesson->content : '' }}
                                </textarea>

                                <textarea name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][content]"
                                          class="d-none js-hidden-content-summernote {{ !empty($textLesson) ? 'js-hidden-content-'.$textLesson->id : '' }}">
                                    {{ !empty($textLesson) ? $textLesson->content : '' }}
                                </textarea>
                            </div>
                        </div>
                    </div>


                    <!-- STATUS & SEQUENCE -->
                    <div class="col-12 col-lg-6">

                        <div class="kemetic-switch-group mt-20">
                            <label class="kemetic-label">{{ trans('public.active') }}</label>
                            <label class="kemetic-switch">
                                <input type="checkbox"
                                       name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][status]"
                                       @if(empty($textLesson) or $textLesson->status == \App\Models\TextLesson::$Active) checked @endif>
                                <span></span>
                            </label>
                        </div>

                        @if(getFeaturesSettings('sequence_content_status'))
                        <div class="kemetic-switch-group mt-20">
                            <label class="kemetic-label">{{ trans('update.sequence_content') }}</label>
                            <label class="kemetic-switch">
                                <input type="checkbox"
                                       class="js-sequence-content-switch"
                                       name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][sequence_content]"
                                       @if(!empty($textLesson) and ($textLesson->check_previous_parts or !empty($textLesson->access_after_day))) checked @endif>
                                <span></span>
                            </label>
                        </div>

                        <div class="js-sequence-content-inputs mt-10 pl-5
                            {{ (!empty($textLesson) and ($textLesson->check_previous_parts or !empty($textLesson->access_after_day))) ? '' : 'd-none' }}">

                            <div class="kemetic-switch-group">
                                <label class="kemetic-label">{{ trans('update.check_previous_parts') }}</label>
                                <label class="kemetic-switch">
                                    <input type="checkbox"
                                           name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][check_previous_parts]"
                                           @if(empty($textLesson) or $textLesson->check_previous_parts) checked @endif>
                                    <span></span>
                                </label>
                            </div>

                            <div class="kemetic-group">
                                <label class="kemetic-label">{{ trans('update.access_after_day') }}</label>
                                <input type="number"
                                       class="kemetic-input js-ajax-access_after_day" min="0"
                                       name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][access_after_day]"
                                       value="{{ !empty($textLesson) ? $textLesson->access_after_day : '' }}">
                            </div>

                        </div>
                        @endif

                    </div>
                </div>


                <!-- BUTTONS -->
                <div class="kemetic-btn-footer mt-30">
                    <button type="button"
                            class="kemetic-btn-primary js-save-text_lesson">
                        {{ trans('public.save') }}
                    </button>

                    @if(empty($textLesson))
                        <button type="button"
                                class="kemetic-btn-danger ml-10 cancel-accordion">
                            {{ trans('public.close') }}
                        </button>
                    @endif
                </div>

            </div>
        </div>
    </div>

</li>
