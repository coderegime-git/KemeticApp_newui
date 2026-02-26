<style>
    /* KEMETIC Theme */

.kemetic-accordion-item {
    background: #141414;
    border: 1px solid #F2C94C;
    border-radius: 14px;
    margin-top: 18px;
    padding: 0;
    overflow: hidden;
    transition: 0.3s;
}

.kemetic-accordion-item:hover {
    border-color: #ffe28a;
}

/* HEADER */
.kemetic-accordion-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 20px;
    cursor: pointer;
}

.kemetic-accordion-left {
    display: flex;
    align-items: center;
}

.kemetic-icon {
    width: 34px;
    height: 34px;
    background: rgba(242, 201, 76, 0.12);
    border: 1px solid #F2C94C;
    border-radius: 10px;
    color: #F2C94C;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
}

.kemetic-title {
    font-weight: 600;
    font-size: 16px;
    color: #fff;
}

/* Right actions */
.kemetic-actions {
    display: flex;
    align-items: center;
}

.kemetic-icon-btn {
    background: transparent;
    border: none;
    margin-right: 10px;
    color: #aaa;
    cursor: pointer;
}

.kemetic-icon-btn:hover {
    color: #F2C94C;
}

.kemetic-chevron {
    cursor: pointer;
    color: #F2C94C;
    margin-left: 6px;
}

.kemetic-move {
    cursor: grab;
    color: #777;
    margin-right: 12px;
}

.kemetic-badge-disabled {
    background: rgba(255,255,255,0.08);
    color: #aaa;
    font-size: 12px;
    border-radius: 6px;
    padding: 3px 10px;
    margin-right: 10px;
}

/* BODY */
.kemetic-accordion-body {
    background: #111;
    border-top: 1px solid #F2C94C;
    padding: 20px;
}

/* FIELDS */
.kemetic-field {
    margin-bottom: 16px;
}

.kemetic-field label {
    color: #F2C94C;
    font-weight: 600;
    display: block;
    margin-bottom: 6px;
    font-size: 14px;
}

.kemetic-input,
.kemetic-textarea {
    background: #1c1c1c;
    border: 1px solid #444;
    color: #fff;
    border-radius: 10px;
    padding: 10px 14px;
    width: 100%;
    transition: border-color 0.2s;
}

.kemetic-input:focus,
.kemetic-textarea:focus {
    border-color: #F2C94C;
    outline: none;
    box-shadow: 0 0 0 2px rgba(242, 201, 76, 0.15);
}

.kemetic-textarea {
    min-height: 120px;
    resize: vertical;
}

/* SWITCH ROW */
.kemetic-switch-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 18px;
    color: #fff;
}

.kemetic-switch {
    position: relative;
    width: 45px;
    height: 22px;
}

.kemetic-switch input {
    display: none;
}

.kemetic-switch .slider {
    position: absolute;
    top: 0;
    left: 0;
    background: #444;
    width: 100%;
    height: 100%;
    border-radius: 30px;
    transition: 0.3s;
    cursor: pointer;
}

.kemetic-switch input:checked + .slider {
    background: #F2C94C;
}

/* ATTACHMENTS */
.kemetic-attachments-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
}

.kemetic-attachments-header label {
    color: #F2C94C;
    font-weight: 600;
    font-size: 14px;
    margin: 0;
}

.kemetic-attachment-block {
    background: #1a1a1a;
    border: 1px solid #333;
    border-radius: 10px;
    padding: 14px;
    margin-top: 10px;
    position: relative;
}

.kemetic-attachment-block label {
    color: #F2C94C;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
}

.kemetic-input-group {
    display: flex;
}

.kemetic-input-group .kemetic-input {
    border-radius: 0 10px 10px 0;
}

.kemetic-input-group-prepend button {
    background: #F2C94C;
    border: none;
    border-radius: 10px 0 0 10px;
    padding: 0 14px;
    color: #141414;
    cursor: pointer;
    font-weight: 700;
    transition: background 0.2s;
}

.kemetic-input-group-prepend button:hover {
    background: #ffe28a;
}

/* FOOTER */
.kemetic-footer {
    margin-top: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.kemetic-footer .btn-primary {
    background: #F2C94C;
    border: none;
    color: #141414;
    font-weight: 700;
    border-radius: 10px;
    padding: 10px 24px;
    cursor: pointer;
    transition: background 0.2s;
}

.kemetic-footer .btn-primary:hover {
    background: #ffe28a;
}

.kemetic-footer .btn-danger {
    background: transparent;
    border: 1px solid #e74c3c;
    color: #e74c3c;
    font-weight: 600;
    border-radius: 10px;
    padding: 10px 24px;
    cursor: pointer;
    transition: all 0.2s;
}

.kemetic-footer .btn-danger:hover {
    background: #e74c3c;
    color: #fff;
}

/* Sequence content inputs */
.kemetic-sequence-block {
    background: #1a1a1a;
    border-left: 3px solid #F2C94C;
    border-radius: 0 10px 10px 0;
    padding: 14px;
    margin-top: 10px;
}

/* Form group label override for non-kemetic-field areas */
.kemetic-accordion-body .input-label {
    color: #F2C94C;
    font-weight: 600;
    font-size: 14px;
}

.kemetic-accordion-body .form-control {
    background: #1c1c1c;
    border: 1px solid #444;
    color: #fff;
    border-radius: 10px;
}

.kemetic-accordion-body .form-control:focus {
    border-color: #F2C94C;
    box-shadow: 0 0 0 2px rgba(242,201,76,0.15);
    background: #1c1c1c;
    color: #fff;
}

.kemetic-accordion-body .custom-control-input:checked ~ .custom-control-label::before {
    background-color: #F2C94C;
    border-color: #F2C94C;
}

.kemetic-accordion-body .btn-sm.btn-primary {
    background: #F2C94C;
    border: none;
    color: #141414;
    font-weight: 700;
    border-radius: 8px;
}

.kemetic-accordion-body .btn-sm.btn-danger {
    background: transparent;
    border: 1px solid #e74c3c;
    color: #e74c3c;
    border-radius: 8px;
}

.kemetic-accordion-body .border {
    border-color: #333 !important;
    border-radius: 10px;
}
</style>

<li data-id="{{ !empty($chapterItem) ? $chapterItem->id :'' }}"
    class="accordion-row kemetic-accordion-item">

    <div class="kemetic-accordion-header"
         role="tab"
         id="file_{{ !empty($assignment) ? $assignment->id :'record' }}">

        <!-- LEFT : Icon + Title -->
        <div class="kemetic-accordion-left"
             href="#collapseFile{{ !empty($assignment) ? $assignment->id :'record' }}"
             aria-controls="collapseFile{{ !empty($assignment) ? $assignment->id :'record' }}"
             data-parent="#chapterContentAccordion{{ !empty($chapter) ? $chapter->id :'' }}"
             role="button"
             data-toggle="collapse"
             aria-expanded="true">

            <span class="kemetic-icon">
                <i data-feather="feather"></i>
            </span>

            <div class="kemetic-title">
                {{ !empty($assignment) ? $assignment->title . ($assignment->accessibility == 'free' ? " (". trans('public.free') .")" : '') : trans('update.add_new_assignments') }}
            </div>
        </div>

        <!-- RIGHT : Action buttons -->
        <div class="kemetic-actions">

            @if(!empty($assignment) && $assignment->status != \App\Models\WebinarChapter::$chapterActive)
                <span class="kemetic-badge-disabled">{{ trans('public.disabled') }}</span>
            @endif

            @if(!empty($assignment))
                <button type="button"
                        data-item-id="{{ $assignment->id }}"
                        data-item-type="{{ \App\Models\WebinarChapterItem::$chapterAssignment }}"
                        data-chapter-id="{{ !empty($chapter) ? $chapter->id : '' }}"
                        class="kemetic-icon-btn js-change-content-chapter">
                    <i data-feather="grid" height="20"></i>
                </button>
            @endif

            <i data-feather="move" class="kemetic-move"></i>

            @if(!empty($assignment))
                <a href="/panel/assignments/{{ $assignment->id }}/delete"
                   class="kemetic-icon-btn text-danger">
                    <i data-feather="trash-2" height="20"></i>
                </a>
            @endif

            <i class="kemetic-chevron"
               data-feather="chevron-down"
               href="#collapseFile{{ !empty($assignment) ? $assignment->id :'record' }}"
               aria-controls="collapseFile{{ !empty($assignment) ? $assignment->id :'record' }}"
               data-parent="#chapterContentAccordion{{ !empty($chapter) ? $chapter->id :'' }}"
               role="button"
               data-toggle="collapse"
               aria-expanded="true"></i>
        </div>
    </div>

    <!-- BODY -->
    <div id="collapseFile{{ !empty($assignment) ? $assignment->id :'record' }}"
         aria-labelledby="file_{{ !empty($assignment) ? $assignment->id :'record' }}"
         class="collapse @if(empty($assignment)) show @endif kemetic-accordion-body">

        <div class="panel-collapse text-gray">
            <div class="assignment-form"
                 data-action="/panel/assignments/{{ !empty($assignment) ? $assignment->id . '/update' : 'store' }}">

                <input type="hidden"
                       name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][webinar_id]"
                       value="{{ !empty($webinar) ? $webinar->id :'' }}">

                <div class="row">
                    <div class="col-12 col-lg-6">

                        {{-- LANGUAGE --}}
                        @if(!empty(getGeneralSettings('content_translate')))
                            <div class="kemetic-field">
                                <label>{{ trans('auth.language') }}</label>
                                <select name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][locale]"
                                        class="kemetic-input {{ !empty($assignment) ? 'js-webinar-content-locale' : '' }}"
                                        data-webinar-id="{{ !empty($webinar) ? $webinar->id : '' }}"
                                        data-id="{{ !empty($assignment) ? $assignment->id : '' }}"
                                        data-relation="assignments"
                                        data-fields="title,description">
                                    @foreach($userLanguages as $lang => $language)
                                        <option value="{{ $lang }}"
                                            {{ (!empty($assignment) && !empty($assignment->locale)) ?
                                                (mb_strtolower($assignment->locale) == mb_strtolower($lang) ? 'selected' : '') :
                                                ($locale == $lang ? 'selected' : '') }}>
                                            {{ $language }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden"
                                   name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][locale]"
                                   value="{{ $defaultLocale }}">
                        @endif

                        {{-- CHAPTER SELECT --}}
                        @if(!empty($assignment))
                            <div class="kemetic-field">
                                <label>{{ trans('public.chapter') }}</label>
                                <select name="ajax[{{ $assignment->id }}][chapter_id]"
                                        class="kemetic-input js-ajax-chapter_id">
                                    @foreach($webinar->chapters as $ch)
                                        <option value="{{ $ch->id }}"
                                                {{ ($assignment->chapter_id == $ch->id) ? 'selected' : '' }}>
                                            {{ $ch->title }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        @else
                            <input type="hidden" name="ajax[new][chapter_id]" value="" class="chapter-input">
                        @endif

                        {{-- TITLE --}}
                        <div class="kemetic-field">
                            <label>{{ trans('public.title') }}</label>
                            <input type="text"
                                   name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][title]"
                                   class="kemetic-input js-ajax-title"
                                   value="{{ !empty($assignment) ? $assignment->title : '' }}">
                            <div class="invalid-feedback"></div>
                        </div>

                        {{-- DESCRIPTION --}}
                        <div class="kemetic-field">
                            <label>{{ trans('public.description') }}</label>
                            <textarea class="kemetic-textarea js-ajax-description"
                                      name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][description]">{{ !empty($assignment) ? $assignment->description : '' }}</textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        {{-- GRADE / PASS GRADE --}}
                        <div class="kemetic-field">
                            <label>{{ trans('quiz.grade') }}</label>
                            <input type="text"
                                   class="kemetic-input js-ajax-grade"
                                   name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][grade]"
                                   value="{{ !empty($assignment) ? $assignment->grade : '' }}">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="kemetic-field">
                            <label>{{ trans('update.pass_grade') }}</label>
                            <input type="text"
                                   class="kemetic-input js-ajax-pass_grade"
                                   name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][pass_grade]"
                                   value="{{ !empty($assignment) ? $assignment->pass_grade : '' }}">
                            <div class="invalid-feedback"></div>
                        </div>

                        {{-- DEADLINE --}}
                        <div class="kemetic-field">
                            <label>{{ trans('update.deadline') }}</label>
                            <input type="text"
                                   class="kemetic-input js-ajax-deadline"
                                   name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][deadline]"
                                   value="{{ !empty($assignment) ? $assignment->deadline : '' }}">
                            <div class="invalid-feedback"></div>
                        </div>

                        {{-- ATTEMPTS --}}
                        <div class="kemetic-field">
                            <label>{{ trans('update.attempts') }}</label>
                            <input type="text"
                                   name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][attempts]"
                                   class="kemetic-input js-ajax-attempts"
                                   value="{{ !empty($assignment) ? $assignment->attempts : '' }}"/>
                            <div class="invalid-feedback"></div>
                        </div>

                        {{-- ATTACHMENTS --}}
                        <div class="js-assignment-attachments-items form-group mt-15">
                            <div class="kemetic-attachments-header">
                                <label class="input-label mb-0">{{ trans('public.attachments') }}</label>
                                <button type="button" class="btn btn-primary btn-sm assignment-attachments-add-btn">
                                    <i data-feather="plus" width="18" height="18" class="text-white"></i>
                                </button>
                            </div>

                            <div class="assignment-attachments-main-row js-ajax-attachments position-relative">
                                <div class="kemetic-attachment-block">
                                    <div class="mb-10">
                                        <label>{{ trans('public.title') }}</label>
                                        <input type="text"
                                               name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][attachments][assignmentTemp][title]"
                                               class="kemetic-input"
                                               placeholder="{{ trans('forms.maximum_255_characters') }}"/>
                                    </div>
                                    <div class="kemetic-input-group">
                                        <div class="kemetic-input-group-prepend">
                                            <button type="button"
                                                    class="panel-file-manager"
                                                    data-input="attachments_assignmentTemp"
                                                    data-preview="holder">
                                                <i data-feather="upload" width="18" height="18"></i>
                                            </button>
                                        </div>
                                        <input type="text"
                                               name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][attachments][assignmentTemp][attach]"
                                               id="attachments_assignmentTemp"
                                               value=""
                                               class="kemetic-input"
                                               placeholder="{{ trans('update.assignment_attachments_placeholder') }}"/>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm assignment-attachments-remove-btn d-none">
                                    <i data-feather="x" width="18" height="18" class="text-white"></i>
                                </button>
                            </div>

                            <div class="invalid-feedback"></div>

                            @if(!empty($assignment) && !empty($assignment->attachments) && count($assignment->attachments))
                                @foreach($assignment->attachments as $attachment)
                                    <div class="js-ajax-attachments position-relative">
                                        <div class="kemetic-attachment-block">
                                            <div class="mb-10">
                                                <label>{{ trans('public.title') }}</label>
                                                <input type="text"
                                                       name="ajax[{{ $assignment->id }}][attachments][{{ $attachment->id }}][title]"
                                                       value="{{ $attachment->title }}"
                                                       class="kemetic-input"
                                                       placeholder="{{ trans('forms.maximum_255_characters') }}"/>
                                            </div>
                                            <div class="kemetic-input-group">
                                                <div class="kemetic-input-group-prepend">
                                                    <button type="button"
                                                            class="panel-file-manager"
                                                            data-input="attachments_{{ $attachment->id }}"
                                                            data-preview="holder">
                                                        <i data-feather="upload" width="18" height="18"></i>
                                                    </button>
                                                </div>
                                                <input type="text"
                                                       name="ajax[{{ $assignment->id }}][attachments][{{ $attachment->id }}][attach]"
                                                       id="attachments_{{ $attachment->id }}"
                                                       value="{{ $attachment->attach }}"
                                                       class="kemetic-input"
                                                       placeholder="{{ trans('update.assignment_attachments_placeholder') }}"/>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-danger btn-sm assignment-attachments-remove-btn">
                                            <i data-feather="x" width="18" height="18" class="text-white"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        {{-- ACTIVE STATUS SWITCH --}}
                        <div class="kemetic-switch-row mt-20">
                            <label class="cursor-pointer input-label"
                                   for="assignmentStatusSwitch{{ !empty($assignment) ? $assignment->id : '_record' }}">
                                {{ trans('public.active') }}
                            </label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox"
                                       name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][status]"
                                       class="custom-control-input"
                                       id="assignmentStatusSwitch{{ !empty($assignment) ? $assignment->id : '_record' }}"
                                       {{ (empty($assignment) || $assignment->status == \App\Models\File::$Active) ? 'checked' : '' }}>
                                <label class="custom-control-label"
                                       for="assignmentStatusSwitch{{ !empty($assignment) ? $assignment->id : '_record' }}"></label>
                            </div>
                        </div>

                        {{-- SEQUENCE CONTENT --}}
                        @if(getFeaturesSettings('sequence_content_status'))
                            <div class="kemetic-switch-row mt-20">
                                <label class="cursor-pointer input-label"
                                       for="SequenceContentSwitch{{ !empty($assignment) ? $assignment->id : '_record' }}">
                                    {{ trans('update.sequence_content') }}
                                </label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox"
                                           name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][sequence_content]"
                                           class="js-sequence-content-switch custom-control-input"
                                           id="SequenceContentSwitch{{ !empty($assignment) ? $assignment->id : '_record' }}"
                                           {{ (!empty($assignment) && ($assignment->check_previous_parts || !empty($assignment->access_after_day))) ? 'checked' : '' }}>
                                    <label class="custom-control-label"
                                           for="SequenceContentSwitch{{ !empty($assignment) ? $assignment->id : '_record' }}"></label>
                                </div>
                            </div>

                            <div class="kemetic-sequence-block js-sequence-content-inputs {{ (!empty($assignment) && ($assignment->check_previous_parts || !empty($assignment->access_after_day))) ? '' : 'd-none' }}">
                                <div class="kemetic-switch-row">
                                    <label class="cursor-pointer input-label"
                                           for="checkPreviousPartsSwitch{{ !empty($assignment) ? $assignment->id : '_record' }}">
                                        {{ trans('update.check_previous_parts') }}
                                    </label>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox"
                                               name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][check_previous_parts]"
                                               class="custom-control-input"
                                               id="checkPreviousPartsSwitch{{ !empty($assignment) ? $assignment->id : '_record' }}"
                                               {{ (empty($assignment) || $assignment->check_previous_parts) ? 'checked' : '' }}>
                                        <label class="custom-control-label"
                                               for="checkPreviousPartsSwitch{{ !empty($assignment) ? $assignment->id : '_record' }}"></label>
                                    </div>
                                </div>

                                <div class="kemetic-field mt-15">
                                    <label>{{ trans('update.access_after_day') }}</label>
                                    <input type="number"
                                           name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][access_after_day]"
                                           value="{{ !empty($assignment) ? $assignment->access_after_day : '' }}"
                                           class="kemetic-input js-ajax-access_after_day"
                                           placeholder="{{ trans('update.access_after_day_placeholder') }}"/>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                <div class="kemetic-footer">
                    <button type="button" class="btn btn-primary js-save-assignment">
                        {{ trans('public.save') }}
                    </button>

                    @if(empty($assignment))
                        <button type="button" class="btn btn-danger cancel-accordion">
                            {{ trans('public.close') }}
                        </button>
                    @endif
                </div>

            </div>
        </div>
    </div>
</li>