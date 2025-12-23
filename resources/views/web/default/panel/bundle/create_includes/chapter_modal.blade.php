<style>
    :root {
    --k-bg: #0b0b0b;
    --k-card: #141414;
    --k-gold: #f2c94c;
    --k-gold-soft: #d4af37;
    --k-border: rgba(242,201,76,0.25);
    --k-text: #f5f5f5;
    --k-muted: #9a9a9a;
    --k-radius: 18px;
}

/* Modal Body */
.kemetic-modal-body {
    background: linear-gradient(180deg, #121212, #0b0b0b);
    padding: 30px;
    border-radius: var(--k-radius);
    box-shadow: 0 15px 50px rgba(242,201,76,0.15);
    color: var(--k-text);
}

/* Title */
.kemetic-modal-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--k-gold);
    margin-bottom: 25px;
    position: relative;
}

.kemetic-modal-title::after {
    content: "";
    display: block;
    width: 50px;
    height: 3px;
    background: var(--k-gold);
    margin-top: 8px;
    border-radius: 10px;
}

/* Form */
.kemetic-label {
    font-size: 13px;
    color: var(--k-muted);
    margin-bottom: 6px;
}

.kemetic-input {
    background: #101010;
    border: 1px solid var(--k-border);
    border-radius: 12px;
    color: var(--k-text);
    padding: 10px 14px;
}

.kemetic-input:focus {
    outline: none;
    border-color: var(--k-gold);
    box-shadow: 0 0 0 2px rgba(242,201,76,0.15);
}

/* Switch Row */
.kemetic-switch-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 0;
    border-bottom: 1px dashed rgba(255,255,255,0.08);
}

/* Switch */
.kemetic-switch {
    position: relative;
    width: 48px;
    height: 26px;
}

.kemetic-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.kemetic-switch .slider {
    position: absolute;
    cursor: pointer;
    inset: 0;
    background: #333;
    border-radius: 34px;
    transition: .3s;
}

.kemetic-switch .slider:before {
    content: "";
    position: absolute;
    height: 20px;
    width: 20px;
    left: 3px;
    bottom: 3px;
    background: #fff;
    border-radius: 50%;
    transition: .3s;
}

.kemetic-switch input:checked + .slider {
    background: linear-gradient(135deg, var(--k-gold), var(--k-gold-soft));
}

.kemetic-switch input:checked + .slider:before {
    transform: translateX(22px);
}

/* Actions */
.kemetic-modal-actions {
    display: flex;
    justify-content: flex-end;
}

.kemetic-btn-primary {
    background: linear-gradient(135deg, var(--k-gold), var(--k-gold-soft));
    color: #000;
    font-weight: 600;
    border-radius: 12px;
    padding: 8px 22px;
}

.kemetic-btn-outline {
    background: transparent;
    color: var(--k-gold);
    border: 1px solid var(--k-gold);
    border-radius: 12px;
    padding: 8px 20px;
}

.kemetic-btn-outline:hover {
    background: rgba(242,201,76,0.1);
}

</style>
<div id="chapterModalHtml" class="d-none">
    <div class="kemetic-modal-body">

        <h2 class="kemetic-modal-title">
            {{ trans('public.new_chapter') }}
        </h2>

        <div class="js-content-form chapter-form mt-25 kemetic-form"
             data-action="/panel/chapters/store">

            <input type="hidden" name="ajax[chapter][webinar_id]"
                   class="js-chapter-webinar-id" value="">

            {{-- Language --}}
            @if(!empty(getGeneralSettings('content_translate')))
                <div class="form-group">
                    <label class="kemetic-label">{{ trans('auth.language') }}</label>
                    <select name="ajax[chapter][locale]"
                            class="form-control kemetic-input js-chapter-locale"
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
                <input type="hidden" name="ajax[chapter][locale]" value="{{ $defaultLocale }}">
            @endif

            {{-- Chapter Title --}}
            <div class="form-group">
                <label class="kemetic-label">{{ trans('public.chapter_title') }}</label>
                <input type="text"
                       name="ajax[chapter][title]"
                       class="form-control kemetic-input js-ajax-title"
                       placeholder="{{ trans('public.chapter_title') }}">
                <span class="invalid-feedback"></span>
            </div>

            {{-- Active Switch --}}
            <div class="kemetic-switch-row">
                <label class="cursor-pointer" for="chapterStatus_record">
                    {{ trans('public.active') }}
                </label>

                <label class="kemetic-switch">
                    <input id="chapterStatus_record"
                           type="checkbox"
                           checked
                           name="ajax[chapter][status]"
                           class="js-chapter-status-switch">
                    <span class="slider"></span>
                </label>
            </div>

            {{-- Sequence Content --}}
            @if(getFeaturesSettings('sequence_content_status'))
                <div class="kemetic-switch-row mt-15">
                    <label class="cursor-pointer" for="checkAllContentsPassSwitch_record">
                        {{ trans('update.check_all_contents_pass') }}
                    </label>

                    <label class="kemetic-switch">
                        <input id="checkAllContentsPassSwitch_record"
                               type="checkbox"
                               name="ajax[chapter][check_all_contents_pass]"
                               class="js-chapter-check-all-contents-pass">
                        <span class="slider"></span>
                    </label>
                </div>
            @endif

            {{-- Actions --}}
            <div class="kemetic-modal-actions mt-30">
                <button type="button" class="save-chapter btn kemetic-btn-primary">
                    {{ trans('public.save') }}
                </button>
                <button type="button" class="close-swl btn kemetic-btn-outline ml-10">
                    {{ trans('public.close') }}
                </button>
            </div>

        </div>
    </div>
</div>
