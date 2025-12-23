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

/* BODY */
.kemetic-accordion-body {
    background: #111;
    border-top: 1px solid #F2C94C;
    padding: 20px;
}

/* FIELDS */
.kemetic-field label {
    color: #F2C94C;
    font-weight: 600;
}

.kemetic-input,
.kemetic-textarea {
    background: #1c1c1c;
    border: 1px solid #444;
    color: #fff;
    border-radius: 10px;
    padding: 10px 14px;
    width: 100%;
}

.kemetic-input:focus {
    border-color: #F2C94C;
}

.kemetic-textarea {
    min-height: 120px;
}

/* SWITCH */
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
}

.kemetic-switch input:checked + .slider {
    background: #F2C94C;
}

.kemetic-footer {
    margin-top: 25px;
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

            @if(!empty($assignment) and $assignment->status != \App\Models\WebinarChapter::$chapterActive)
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
                                            {{ (!empty($assignment) and !empty($assignment->locale)) ?
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
                                <select name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][chapter_id]"
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
                                      name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][description]">
                                {{ !empty($assignment) ? $assignment->description : '' }}
                            </textarea>
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

                        {{-- STATUS SWITCH --}}
                        <div class="kemetic-switch-row">
                            <label>{{ trans('public.active') }}</label>
                            <label class="kemetic-switch">
                                <input type="checkbox"
                                       name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][status]"
                                       {{ (empty($assignment) or $assignment->status == \App\Models\File::$Active) ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>

                    </div>
                </div>

                <div class="kemetic-footer">
                    <button type="button" class="btn btn-primary js-save-assignment">
                        {{ trans('public.save') }}
                    </button>

                    @if(empty($assignment))
                        <button type="button" class="btn btn-danger ml-10 cancel-accordion">
                            {{ trans('public.close') }}
                        </button>
                    @endif
                </div>

            </div>
        </div>
    </div>
</li>
