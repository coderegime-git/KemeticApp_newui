<style>
/* =========================================================
   KEMETIC FILE ACCORDION — COMPLETE DESIGN SYSTEM
   Dark gold + obsidian — Egyptian-inspired UI
   ========================================================= */

@import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Jost:wght@300;400;500;600&display=swap');

:root {
    --k-gold:        #F2C94C;
    --k-gold-dim:    rgba(242, 201, 76, 0.25);
    --k-gold-border: rgba(242, 201, 76, 0.35);
    --k-dark:        #111111;
    --k-surface:     #181818;
    --k-surface-2:   #202020;
    --k-text:        #E8E0D0;
    --k-text-muted:  #9A8F7A;
    --k-danger:      #C0392B;
    --k-danger-dim:  rgba(192, 57, 43, 0.15);
    --k-radius:      16px;
    --k-radius-sm:   10px;
    --k-radius-xs:   8px;
    --k-transition:  .25s cubic-bezier(.4,0,.2,1);
}

/* ── Accordion wrapper ── */
.kemetic-accordion-item {
    background: var(--k-surface);
    border: 1px solid var(--k-gold-border);
    border-radius: var(--k-radius);
    padding: 18px 24px;
    margin-top: 20px;
    box-shadow: 0 6px 24px rgba(0,0,0,.45),
                inset 0 1px 0 rgba(242,201,76,.08);
    transition: box-shadow var(--k-transition), border-color var(--k-transition);
    position: relative;
    overflow: hidden;
}

.kemetic-accordion-item::before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 3px; height: 100%;
    background: linear-gradient(180deg, var(--k-gold) 0%, transparent 100%);
    border-radius: 4px 0 0 4px;
}

.kemetic-accordion-item:hover {
    border-color: rgba(242,201,76,.55);
    box-shadow: 0 8px 32px rgba(0,0,0,.55),
                0 0 0 1px rgba(242,201,76,.12),
                inset 0 1px 0 rgba(242,201,76,.12);
}

/* ── Header ── */
.kemetic-accordion-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    user-select: none;
}

.kemetic-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.kemetic-icon-wrap {
    width: 38px; height: 38px;
    background: var(--k-gold-dim);
    border: 1px solid var(--k-gold-border);
    border-radius: var(--k-radius-sm);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}

.kemetic-icon-wrap svg,
.kemetic-icon-wrap i { color: var(--k-gold) !important; }

.kemetic-title {
    font-family: 'Cinzel', serif;
    font-weight: 600;
    font-size: 14px;
    color: var(--k-gold);
    letter-spacing: .04em;
}

/* ── Header action buttons ── */
.kemetic-header-actions {
    display: flex;
    align-items: center;
    gap: 4px;
}

.kemetic-action-btn {
    background: none;
    border: none;
    padding: 6px;
    border-radius: var(--k-radius-xs);
    color: var(--k-text-muted);
    cursor: pointer;
    transition: color var(--k-transition), background var(--k-transition);
    display: flex; align-items: center;
}

.kemetic-action-btn:hover {
    color: var(--k-gold);
    background: var(--k-gold-dim);
}

.kemetic-action-btn.danger:hover {
    color: #E74C3C;
    background: var(--k-danger-dim);
}

.kemetic-badge-disabled {
    font-family: 'Jost', sans-serif;
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: var(--k-text-muted);
    background: var(--k-surface-2);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 20px;
    padding: 2px 10px;
}

/* ── Body ── */
.kemetic-accordion-body {
    padding-top: 24px;
}

/* ── Form ── */
.kemetic-form-group {
    margin-bottom: 20px;
}

.kemetic-label {
    font-family: 'Jost', sans-serif;
    font-size: 12px;
    font-weight: 500;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--k-gold);
    display: block;
    margin-bottom: 8px;
    opacity: .9;
}

.kemetic-control {
    font-family: 'Jost', sans-serif;
    font-size: 14px;
    width: 100%;
    background: var(--k-dark);
    border: 1px solid var(--k-gold-border);
    border-radius: var(--k-radius-sm);
    color: var(--k-text);
    padding: 10px 14px;
    transition: border-color var(--k-transition), box-shadow var(--k-transition);
    outline: none;
    appearance: none;
}

.kemetic-control:focus {
    border-color: var(--k-gold);
    box-shadow: 0 0 0 3px rgba(242,201,76,.15);
}

.kemetic-control::placeholder { color: var(--k-text-muted); }

select.kemetic-control {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23F2C94C' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 32px;
}

select.kemetic-control option {
    background: var(--k-surface);
    color: var(--k-text);
}

/* ── Input group (icon + input) ── */
.kemetic-input-group {
    display: flex;
    border: 1px solid var(--k-gold-border);
    border-radius: var(--k-radius-sm);
    overflow: hidden;
    transition: border-color var(--k-transition), box-shadow var(--k-transition);
}

.kemetic-input-group:focus-within {
    border-color: var(--k-gold);
    box-shadow: 0 0 0 3px rgba(242,201,76,.15);
}

.kemetic-input-group .kemetic-control {
    border: none;
    border-radius: 0;
    flex: 1;
    box-shadow: none !important;
}

.kemetic-input-addon {
    background: var(--k-gold-dim);
    border-right: 1px solid var(--k-gold-border);
    padding: 0 12px;
    display: flex; align-items: center;
    color: var(--k-gold);
    cursor: pointer;
    flex-shrink: 0;
    transition: background var(--k-transition);
}

.kemetic-input-addon:hover {
    background: rgba(242,201,76,.35);
}

/* ── Radio group ── */
.kemetic-radio-group {
    display: flex;
    gap: 12px;
}

.kemetic-radio-label {
    display: flex;
    align-items: center;
    gap: 7px;
    font-family: 'Jost', sans-serif;
    font-size: 14px;
    color: var(--k-text);
    cursor: pointer;
    padding: 8px 14px;
    border: 1px solid var(--k-gold-border);
    border-radius: var(--k-radius-sm);
    background: var(--k-dark);
    transition: border-color var(--k-transition), background var(--k-transition), color var(--k-transition);
}

.kemetic-radio-label:hover {
    border-color: var(--k-gold);
    color: var(--k-gold);
}

.kemetic-radio-label input[type="radio"] {
    accent-color: var(--k-gold);
    width: 15px; height: 15px;
    flex-shrink: 0;
}

.kemetic-radio-label:has(input:checked) {
    border-color: var(--k-gold);
    background: var(--k-gold-dim);
    color: var(--k-gold);
}

/* ── Switch row ── */
.kemetic-switch-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    background: var(--k-dark);
    border: 1px solid var(--k-gold-border);
    border-radius: var(--k-radius-sm);
    margin-bottom: 10px;
}

.kemetic-switch-row .switch-label {
    font-family: 'Jost', sans-serif;
    font-size: 14px;
    color: var(--k-text);
}

.kemetic-switch {
    position: relative;
    display: inline-block;
    width: 44px; height: 24px;
    flex-shrink: 0;
}

.kemetic-switch input { display: none; }

.kemetic-switch-track {
    position: absolute;
    inset: 0;
    background: #333;
    border-radius: 24px;
    cursor: pointer;
    transition: background var(--k-transition);
}

.kemetic-switch-track::before {
    content: '';
    position: absolute;
    width: 18px; height: 18px;
    top: 3px; left: 3px;
    background: #777;
    border-radius: 50%;
    transition: transform var(--k-transition), background var(--k-transition);
}

.kemetic-switch input:checked ~ .kemetic-switch-track {
    background: var(--k-gold-dim);
    border: 1px solid var(--k-gold);
}

.kemetic-switch input:checked ~ .kemetic-switch-track::before {
    background: var(--k-gold);
    transform: translateX(20px);
}

/* ── Sub-section (sequence content) ── */
.kemetic-subsection {
    margin-top: 12px;
    padding: 16px;
    background: rgba(0,0,0,.25);
    border: 1px solid rgba(242,201,76,.15);
    border-radius: var(--k-radius-sm);
}

/* ── Progress ── */
.kemetic-progress {
    height: 6px;
    background: #333;
    border-radius: 6px;
    overflow: hidden;
    margin-top: 16px;
}

.kemetic-progress-bar {
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg, var(--k-gold) 0%, #FFE066 100%);
    border-radius: 6px;
    transition: width .3s ease;
    animation: shimmer 2s infinite linear;
    background-size: 200% 100%;
}

@keyframes shimmer {
    0%   { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* ── Buttons ── */
.kemetic-btn {
    font-family: 'Cinzel', serif;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: .06em;
    padding: 10px 22px;
    border-radius: var(--k-radius-sm);
    border: none;
    cursor: pointer;
    transition: transform var(--k-transition), box-shadow var(--k-transition), opacity var(--k-transition);
}

.kemetic-btn:hover { transform: translateY(-1px); opacity: .92; }
.kemetic-btn:active { transform: translateY(0); }

.kemetic-btn-gold {
    background: linear-gradient(135deg, #F2C94C 0%, #D4A017 100%);
    color: #111;
    box-shadow: 0 4px 12px rgba(242,201,76,.35);
}

.kemetic-btn-gold:hover {
    box-shadow: 0 6px 20px rgba(242,201,76,.5);
}

.kemetic-btn-ghost {
    background: transparent;
    color: var(--k-danger);
    border: 1px solid var(--k-danger);
}

.kemetic-btn-ghost:hover {
    background: var(--k-danger-dim);
    box-shadow: 0 4px 12px rgba(192,57,43,.25);
}

/* ── File type / volume row ── */
.kemetic-two-col {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

/* ── Section divider ── */
.kemetic-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--k-gold-border), transparent);
    margin: 20px 0;
}
</style>

@if(!empty($file) and $file->storage == 'upload_archive')
    @include('web.default.panel.webinar.create_includes.accordions.new_interactive_file',['file' => $file])
@else
<li data-id="{{ !empty($chapterItem) ? $chapterItem->id : '' }}"
    class="accordion-row kemetic-accordion-item">

    {{-- ═══════════════════════ HEADER ═══════════════════════ --}}
    <div class="kemetic-accordion-header"
         role="tab"
         id="file_{{ !empty($file) ? $file->id : 'record' }}">

        {{-- Left: icon + title --}}
        <div class="kemetic-header-left"
             href="#collapseFile{{ !empty($file) ? $file->id : 'record' }}"
             aria-controls="collapseFile{{ !empty($file) ? $file->id : 'record' }}"
             data-parent="#chapterContentAccordion{{ !empty($chapter) ? $chapter->id : '' }}"
             role="button"
             data-toggle="collapse"
             aria-expanded="true">

            <span class="kemetic-icon-wrap">
                <i data-feather="{{ !empty($file) ? $file->getIconByType() : 'file' }}"></i>
            </span>

            <span class="kemetic-title">
                {{ !empty($file)
                    ? $file->title . ($file->accessibility == 'free' ? ' (' . trans('public.free') . ')' : '')
                    : trans('public.add_new_files') }}
            </span>
        </div>

        {{-- Right: actions --}}
        <div class="kemetic-header-actions">

            @if(!empty($file) and $file->status != \App\Models\WebinarChapter::$chapterActive)
                <span class="kemetic-badge-disabled">{{ trans('public.disabled') }}</span>
            @endif

            @if(!empty($file))
                <button type="button"
                        data-item-id="{{ $file->id }}"
                        data-item-type="{{ \App\Models\WebinarChapterItem::$chapterFile }}"
                        data-chapter-id="{{ !empty($chapter) ? $chapter->id : '' }}"
                        class="kemetic-action-btn js-change-content-chapter"
                        title="{{ trans('public.chapter') }}">
                    <i data-feather="grid" height="18"></i>
                </button>
            @endif

            <button type="button" class="kemetic-action-btn" title="Move">
                <i data-feather="move" height="18" class="move-icon"></i>
            </button>

            @if(!empty($file))
                <a href="/panel/files/{{ $file->id }}/delete"
                   class="kemetic-action-btn danger delete-action"
                   title="{{ trans('public.delete') }}">
                    <i data-feather="trash-2" height="18"></i>
                </a>
            @endif

            <button type="button"
                    class="kemetic-action-btn collapse-chevron-icon"
                    href="#collapseFile{{ !empty($file) ? $file->id : 'record' }}"
                    aria-controls="collapseFile{{ !empty($file) ? $file->id : 'record' }}"
                    data-parent="#chapterContentAccordion{{ !empty($chapter) ? $chapter->id : '' }}"
                    role="button"
                    data-toggle="collapse"
                    aria-expanded="true">
                <i data-feather="chevron-down" height="18"></i>
            </button>
        </div>
    </div>

    {{-- ═══════════════════════ BODY ═══════════════════════ --}}
    <div id="collapseFile{{ !empty($file) ? $file->id : 'record' }}"
         aria-labelledby="file_{{ !empty($file) ? $file->id : 'record' }}"
         class="collapse kemetic-accordion-body @if(empty($file)) show @endif"
         role="tabpanel">

        <div class="js-content-form file-form"
             data-action="/panel/files/{{ !empty($file) ? $file->id . '/update' : 'store' }}">

            <input type="hidden"
                   name="ajax[{{ !empty($file) ? $file->id : 'new' }}][webinar_id]"
                   value="{{ !empty($webinar) ? $webinar->id : '' }}">

            <div class="row">
                <div class="col-12 col-lg-6">

                    {{-- ── Language ── --}}
                    @if(!empty(getGeneralSettings('content_translate')))
                        <div class="kemetic-form-group">
                            <label class="kemetic-label">{{ trans('auth.language') }}</label>
                            <select name="ajax[{{ !empty($file) ? $file->id : 'new' }}][locale]"
                                    class="kemetic-control {{ !empty($file) ? 'js-webinar-content-locale' : '' }}"
                                    data-webinar-id="{{ !empty($webinar) ? $webinar->id : '' }}"
                                    data-id="{{ !empty($file) ? $file->id : '' }}"
                                    data-relation="files"
                                    data-fields="title,description">
                                @foreach($userLanguages as $lang => $language)
                                    <option value="{{ $lang }}"
                                        {{ (!empty($file) and !empty($file->locale))
                                            ? (mb_strtolower($file->locale) == mb_strtolower($lang) ? 'selected' : '')
                                            : ($locale == $lang ? 'selected' : '') }}>
                                        {{ $language }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="hidden"
                               name="ajax[{{ !empty($file) ? $file->id : 'new' }}][locale]"
                               value="{{ $defaultLocale }}">
                    @endif

                    {{-- ── Chapter ── --}}
                    @if(!empty($file))
                        <div class="kemetic-form-group">
                            <label class="kemetic-label">{{ trans('public.chapter') }}</label>
                            <select name="ajax[{{ $file->id }}][chapter_id]"
                                    class="kemetic-control js-ajax-chapter_id">
                                @foreach($webinar->chapters as $ch)
                                    <option value="{{ $ch->id }}"
                                        {{ $file->chapter_id == $ch->id ? 'selected' : '' }}>
                                        {{ $ch->title }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    @else
                        <input type="hidden" name="ajax[new][chapter_id]" value="" class="chapter-input">
                    @endif

                    {{-- ── Title ── --}}
                    <div class="kemetic-form-group">
                        <label class="kemetic-label">{{ trans('public.title') }}</label>
                        <input type="text"
                               name="ajax[{{ !empty($file) ? $file->id : 'new' }}][title]"
                               class="kemetic-control js-ajax-title"
                               value="{{ !empty($file) ? $file->title : '' }}"
                               placeholder="{{ trans('forms.maximum_255_characters') }}">
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- ── Source ── --}}
                    <div class="kemetic-form-group">
                        <label class="kemetic-label">{{ trans('public.source') }}</label>
                        <select name="ajax[{{ !empty($file) ? $file->id : 'new' }}][storage]"
                                class="kemetic-control js-file-storage">
                            @foreach(getFeaturesSettings('available_sources') as $source)
                                <option value="{{ $source }}"
                                    @if(!empty($file) and $file->storage == $source) selected @endif>
                                    {{ trans('update.file_source_'.$source) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- ── Accessibility ── --}}
                    <div class="kemetic-form-group">
                        <label class="kemetic-label">{{ trans('public.accessibility') }}</label>
                        <div class="kemetic-radio-group js-ajax-accessibility">
                            <label class="kemetic-radio-label">
                                <input type="radio"
                                       name="ajax[{{ !empty($file) ? $file->id : 'new' }}][accessibility]"
                                       value="free"
                                       id="accessibilityRadio1_{{ !empty($file) ? $file->id : 'record' }}"
                                       @if(empty($file) or (!empty($file) and $file->accessibility == 'free')) checked="checked" @endif>
                                {{ trans('public.free') }}
                            </label>
                            <label class="kemetic-radio-label">
                                <input type="radio"
                                       name="ajax[{{ !empty($file) ? $file->id : 'new' }}][accessibility]"
                                       value="paid"
                                       id="accessibilityRadio2_{{ !empty($file) ? $file->id : 'record' }}"
                                       @if(!empty($file) and $file->accessibility == 'paid') checked="checked" @endif>
                                {{ trans('public.paid') }}
                            </label>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- ── Secure Host: Upload Type ── --}}
                    <div class="js-secure-host-upload-type-field kemetic-form-group {{ (!empty($file) and $file->storage == 'secure_host') ? '' : 'd-none' }}">
                        <label class="kemetic-label">{{ trans('update.upload_type') }}</label>
                        <div class="kemetic-radio-group js-ajax-secure_host_upload_type">
                            <label class="kemetic-radio-label">
                                <input type="radio"
                                       name="ajax[{{ !empty($file) ? $file->id : 'new' }}][secure_host_upload_type]"
                                       value="direct"
                                       id="uploadTypeRadio1_{{ !empty($file) ? $file->id : 'record' }}"
                                       {{ (empty($file) or $file->secure_host_upload_type == 'direct') ? 'checked' : '' }}>
                                {{ trans('update.direct') }}
                            </label>
                            <label class="kemetic-radio-label">
                                <input type="radio"
                                       name="ajax[{{ !empty($file) ? $file->id : 'new' }}][secure_host_upload_type]"
                                       value="manual"
                                       id="uploadTypeRadio2_{{ !empty($file) ? $file->id : 'record' }}"
                                       {{ (!empty($file) and $file->secure_host_upload_type == 'manual') ? 'checked' : '' }}>
                                {{ trans('public.manual') }}
                            </label>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- ── Secure Host: Manual Path ── --}}
                    <div class="js-secure-host-path-input kemetic-form-group {{ (!empty($file) and $file->storage == 'secure_host' and $file->secure_host_upload_type == 'manual') ? '' : 'd-none' }}">
                        <label class="kemetic-label">{{ trans('update.enter_file_url') }}</label>
                        <div class="kemetic-input-group">
                            <span class="kemetic-input-addon">
                                <i data-feather="link" width="16" height="16"></i>
                            </span>
                            <input type="text"
                                   name="ajax[{{ !empty($file) ? $file->id : 'new' }}][secure_host_file_path]"
                                   value="{{ !empty($file) ? $file->file : '' }}"
                                   class="kemetic-control js-ajax-secure_host_file_path"
                                   placeholder="{{ trans('update.enter_file_url') }}">
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- ── File Path (non-S3) ── --}}
                    <div class="js-file-path-input kemetic-form-group {{ (!empty($file) and $file->storage == 's3') ? 'd-none' : '' }}">
                        <label class="kemetic-label">{{ trans('webinars.file_upload_placeholder') }}</label>
                        <div class="kemetic-input-group">
                            <button type="button"
                                    class="kemetic-input-addon panel-file-manager"
                                    data-input="file_path{{ !empty($file) ? $file->id : 'record' }}"
                                    data-preview="holder">
                                <i data-feather="upload" width="16" height="16"></i>
                            </button>
                            <input type="text"
                                   name="ajax[{{ !empty($file) ? $file->id : 'new' }}][file_path]"
                                   id="file_path{{ !empty($file) ? $file->id : 'record' }}"
                                   value="{{ !empty($file) ? $file->file : '' }}"
                                   class="kemetic-control js-ajax-file_path"
                                   placeholder="{{ trans('webinars.file_upload_placeholder') }}">
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- ── S3 File Upload ── --}}
                    <div class="js-s3-file-path-input kemetic-form-group {{ (!empty($file) and $file->storage == 's3') ? '' : 'd-none' }}">
                        <label class="kemetic-label">{{ trans('update.choose_file') }}</label>
                        <div class="kemetic-input-group">
                            <span class="kemetic-input-addon">
                                <i data-feather="upload" width="16" height="16"></i>
                            </span>
                            <div class="custom-file js-ajax-s3_file" style="flex:1; overflow:hidden;">
                                <input type="file"
                                       name="ajax[{{ !empty($file) ? $file->id : 'new' }}][s3_file]"
                                       class="js-s3-file-input custom-file-input cursor-pointer"
                                       id="s3File{{ !empty($file) ? $file->id : 'record' }}">
                                <label class="custom-file-label kemetic-control"
                                       for="s3File{{ !empty($file) ? $file->id : 'record' }}"
                                       style="border-radius:0; border:none; margin:0;">
                                    {{ trans('update.choose_file') }}
                                </label>
                            </div>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- ── File Type & Volume ── --}}
                    <div class="js-file-type-volume kemetic-form-group ">
                        <div class="kemetic-two-col">
                            <div class="js-file-type-field">
                                <label class="kemetic-label">{{ trans('webinars.file_type') }}</label>
                                <select name="ajax[{{ !empty($file) ? $file->id : 'new' }}][file_type]"
                                        class="kemetic-control js-ajax-file_type">
                                    <option value="">{{ trans('webinars.select_file_type') }}</option>
                                    @foreach(\App\Models\File::$fileTypes as $fileType)
                                        <option value="{{ $fileType }}"
                                            @if(!empty($file) and $file->file_type == $fileType) selected @endif>
                                            {{ trans('update.file_type_'.$fileType) }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="js-file-volume-field">
                                <label class="kemetic-label">{{ trans('webinars.file_volume') }}</label>
                                <input type="text"
                                       name="ajax[{{ !empty($file) ? $file->id : 'new' }}][volume]"
                                       value="{{ !empty($file) ? $file->volume : '' }}"
                                       class="kemetic-control js-ajax-volume"
                                       placeholder="{{ trans('webinars.online_file_volume') }}">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Description ── --}}
                    <div class="kemetic-form-group">
                        <label class="kemetic-label">{{ trans('public.description') }}</label>
                        <textarea name="ajax[{{ !empty($file) ? $file->id : 'new' }}][description]"
                                  class="kemetic-control js-ajax-description"
                                  rows="5">{{ !empty($file) ? $file->description : '' }}</textarea>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="kemetic-divider"></div>

                    {{-- ── Online Viewer ── --}}
                    <div class="js-online_viewer-input">
                        <div class="kemetic-switch-row">
                            <label class="switch-label"
                                   for="online_viewerSwitch{{ !empty($file) ? $file->id : '_record' }}">
                                {{ trans('update.online_viewer') }}
                            </label>
                            <label class="kemetic-switch">
                                <input type="checkbox"
                                       name="ajax[{{ !empty($file) ? $file->id : 'new' }}][online_viewer]"
                                       id="online_viewerSwitch{{ !empty($file) ? $file->id : '_record' }}"
                                       {{ (!empty($file) and $file->online_viewer) ? 'checked' : '' }}>
                                <span class="kemetic-switch-track"></span>
                            </label>
                        </div>
                    </div>

                    {{-- ── Downloadable ── --}}
                    <div class="js-downloadable-input">
                        <div class="kemetic-switch-row">
                            <label class="switch-label"
                                   for="downloadableSwitch{{ !empty($file) ? $file->id : '_record' }}">
                                {{ trans('home.downloadable') }}
                            </label>
                            <label class="kemetic-switch">
                                <input type="checkbox"
                                       name="ajax[{{ !empty($file) ? $file->id : 'new' }}][downloadable]"
                                       id="downloadableSwitch{{ !empty($file) ? $file->id : '_record' }}"
                                       {{ (empty($file) or $file->downloadable) ? 'checked' : '' }}>
                                <span class="kemetic-switch-track"></span>
                            </label>
                        </div>
                    </div>

                    {{-- ── Active Status ── --}}
                    <div class="kemetic-switch-row">
                        <label class="switch-label"
                               for="fileStatusSwitch{{ !empty($file) ? $file->id : '_record' }}">
                            {{ trans('public.active') }}
                        </label>
                        <label class="kemetic-switch">
                            <input type="checkbox"
                                   name="ajax[{{ !empty($file) ? $file->id : 'new' }}][status]"
                                   id="fileStatusSwitch{{ !empty($file) ? $file->id : '_record' }}"
                                   {{ (empty($file) or $file->status == \App\Models\File::$Active) ? 'checked' : '' }}>
                            <span class="kemetic-switch-track"></span>
                        </label>
                    </div>

                    {{-- ── Sequence Content ── --}}
                    @if(getFeaturesSettings('sequence_content_status'))
                        <div class="kemetic-switch-row">
                            <label class="switch-label"
                                   for="SequenceContentSwitch{{ !empty($file) ? $file->id : '_record' }}">
                                {{ trans('update.sequence_content') }}
                            </label>
                            <label class="kemetic-switch">
                                <input type="checkbox"
                                       name="ajax[{{ !empty($file) ? $file->id : 'new' }}][sequence_content]"
                                       class="js-sequence-content-switch"
                                       id="SequenceContentSwitch{{ !empty($file) ? $file->id : '_record' }}"
                                       {{ (!empty($file) and ($file->check_previous_parts or !empty($file->access_after_day))) ? 'checked' : '' }}>
                                <span class="kemetic-switch-track"></span>
                            </label>
                        </div>

                        {{-- Sequence sub-options --}}
                        <div class="js-sequence-content-inputs kemetic-subsection {{ (!empty($file) and ($file->check_previous_parts or !empty($file->access_after_day))) ? '' : 'd-none' }}">

                            <div class="kemetic-switch-row" style="margin-bottom:14px;">
                                <label class="switch-label"
                                       for="checkPreviousPartsSwitch{{ !empty($file) ? $file->id : '_record' }}">
                                    {{ trans('update.check_previous_parts') }}
                                </label>
                                <label class="kemetic-switch">
                                    <input type="checkbox"
                                           name="ajax[{{ !empty($file) ? $file->id : 'new' }}][check_previous_parts]"
                                           id="checkPreviousPartsSwitch{{ !empty($file) ? $file->id : '_record' }}"
                                           {{ (empty($file) or $file->check_previous_parts) ? 'checked' : '' }}>
                                    <span class="kemetic-switch-track"></span>
                                </label>
                            </div>

                            <div class="kemetic-form-group" style="margin-bottom:0;">
                                <label class="kemetic-label">{{ trans('update.access_after_day') }}</label>
                                <input type="number"
                                       name="ajax[{{ !empty($file) ? $file->id : 'new' }}][access_after_day]"
                                       value="{{ !empty($file) ? $file->access_after_day : '' }}"
                                       class="kemetic-control js-ajax-access_after_day"
                                       placeholder="{{ trans('update.access_after_day_placeholder') }}">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    @endif

                    {{-- ── Progress bar ── --}}
                    <div class="progress kemetic-progress d-none" style="margin-top:16px;">
                        <div class="progress-bar kemetic-progress-bar"
                             role="progressbar"
                             aria-valuenow="0"
                             aria-valuemin="0"
                             aria-valuemax="100"
                             style="width:0%">
                        </div>
                    </div>

                </div>{{-- /col --}}
            </div>{{-- /row --}}

            {{-- ── Action Buttons ── --}}
            <div class="d-flex align-items-center mt-30" style="gap:10px;">
                <button type="button" class="js-save-file kemetic-btn kemetic-btn-gold">
                    {{ trans('public.save') }}
                </button>

                @if(empty($file))
                    <button type="button" class="kemetic-btn kemetic-btn-ghost cancel-accordion">
                        {{ trans('public.close') }}
                    </button>
                @endif
            </div>

        </div>{{-- /file-form --}}
    </div>{{-- /collapse --}}

</li>

@push('scripts_bottom')
    <script>
        var filePathPlaceHolderBySource = {
            upload:        '{{ trans('update.file_source_upload_placeholder') }}',
            youtube:       '{{ trans('update.file_source_youtube_placeholder') }}',
            vimeo:         '{{ trans('update.file_source_vimeo_placeholder') }}',
            external_link: '{{ trans('update.file_source_external_link_placeholder') }}',
            google_drive:  '{{ trans('update.file_source_google_drive_placeholder') }}',
            dropbox:       '{{ trans('update.file_source_dropbox_placeholder') }}',
            iframe:        '{{ trans('update.file_source_iframe_placeholder') }}',
            s3:            '{{ trans('update.file_source_s3_placeholder') }}',
        }
    </script>
@endpush
@endif