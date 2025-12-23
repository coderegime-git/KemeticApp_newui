<style>
    /* Accordion Card */
.kemetic-accordion-item {
    background: linear-gradient(180deg, #0b0b0b, #121212);
    border: 1px solid rgba(212,175,55,.35);
    border-radius: 14px;
    overflow: hidden;
}

/* Header */
.kemetic-accordion-header {
    padding: 16px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    background: rgba(212,175,55,.05);
}

/* Title */
.kemetic-acc-title {
    color: #d4af37;
    font-weight: 600;
    font-size: 14px;
}

/* Actions */
.kemetic-acc-actions {
    display: flex;
    align-items: center;
    gap: 14px;
}

.kemetic-icon {
    stroke: #d4af37;
    width: 18px;
    height: 18px;
}

.drag-icon {
    cursor: grab;
}

/* Company Logo */
.kemetic-company-logo {
    max-height: 32px;
    background: #fff;
    padding: 4px 8px;
    border-radius: 6px;
}

/* Three dot button */
.kemetic-dot-btn {
    background: transparent;
    border: 1px solid rgba(212,175,55,.4);
    border-radius: 8px;
    padding: 6px;
}

.kemetic-dot-btn:hover {
    background: rgba(212,175,55,.15);
}

/* Dropdown */
.kemetic-dropdown-menu {
    background: #0f0f0f;
    border: 1px solid rgba(212,175,55,.3);
    border-radius: 10px;
}

/* Body */
.kemetic-accordion-body {
    background: #0e0e0e;
}

.kemetic-accordion-content {
    padding: 20px;
}

/* Form */
.kemetic-form-group {
    margin-bottom: 16px;
}

.kemetic-label {
    color: #d4af37;
    font-size: 13px;
    margin-bottom: 6px;
}

.kemetic-input {
    width: 100%;
    background: #000;
    border: 1px solid rgba(212,175,55,.4);
    color: #fff;
    padding: 10px 14px;
    border-radius: 10px;
}

/* Upload */
.kemetic-input-group {
    display: flex;
    gap: 10px;
}

.kemetic-upload-btn {
    background: rgba(212,175,55,.15);
    border: 1px solid rgba(212,175,55,.5);
    border-radius: 10px;
    padding: 10px;
    color: #d4af37;
}

/* Buttons */
.kemetic-accordion-buttons {
    margin-top: 20px;
    display: flex;
}

.kemetic-btn-gold {
    background: linear-gradient(135deg,#d4af37,#b8962e);
    color: #000;
    font-weight: 600;
    border-radius: 10px;
}

.kemetic-btn-red {
    background: #8b1e1e;
    color: #fff;
    border-radius: 10px;
}

</style>
<li data-id="{{ !empty($extraDescription) ? $extraDescription->id :'' }}"
    class="accordion-row kemetic-accordion-item mt-20">

    <!-- HEADER -->
    <div class="kemetic-accordion-header"
         role="tab"
         id="{{ $extraDescriptionType }}_{{ !empty($extraDescription) ? $extraDescription->id :'record' }}"
         data-toggle="collapse"
         href="#collapseExtraDescription{{ !empty($extraDescription) ? $extraDescription->id :'record' }}"
         aria-expanded="true">

        <!-- Title -->
        <div class="kemetic-acc-title">
            @if(!empty($extraDescription) and !empty($extraDescription->value))
                @if($extraDescriptionType == \App\Models\WebinarExtraDescription::$COMPANY_LOGOS)
                    <img src="{{ $extraDescription->value }}"
                         class="kemetic-company-logo"
                         alt="">
                @else
                    <span>{{ truncate($extraDescription->value, 45) }}</span>
                @endif
            @else
                <span>{{ trans('update.new_item') }}</span>
            @endif
        </div>

        <!-- Actions -->
        <div class="kemetic-acc-actions">

            <i data-feather="move" class="kemetic-icon drag-icon"></i>

            @if(!empty($extraDescription))
                <div class="dropdown kemetic-dropdown">
                    <button class="kemetic-dot-btn" data-toggle="dropdown">
                        <i data-feather="more-vertical"></i>
                    </button>

                    <div class="dropdown-menu kemetic-dropdown-menu">
                        <a href="/panel/webinar-extra-description/{{ $extraDescription->id }}/delete"
                           class="dropdown-item text-danger">
                            {{ trans('public.delete') }}
                        </a>
                    </div>
                </div>
            @endif

            <i data-feather="chevron-down"
               class="kemetic-icon chevron-icon"></i>
        </div>
    </div>

    <!-- BODY -->
    <div id="collapseExtraDescription{{ !empty($extraDescription) ? $extraDescription->id :'record' }}"
         class="collapse kemetic-accordion-body @if(empty($extraDescription)) show @endif"
         role="tabpanel">

        <div class="kemetic-accordion-content">

            <div class="js-content-form extra_description-form"
                 data-action="/panel/webinar-extra-description/{{ !empty($extraDescription) ? $extraDescription->id . '/update' : 'store' }}">

                <input type="hidden"
                       name="ajax[{{ !empty($extraDescription) ? $extraDescription->id : 'new' }}][webinar_id]"
                       value="{{ !empty($webinar) ? $webinar->id :'' }}">

                <input type="hidden"
                       name="ajax[{{ !empty($extraDescription) ? $extraDescription->id : 'new' }}][type]"
                       value="{{ $extraDescriptionType }}">

                <div class="row">
                    <div class="col-12 col-lg-6">

                        @if($extraDescriptionType == \App\Models\WebinarExtraDescription::$COMPANY_LOGOS)

                            <input type="hidden"
                                   name="ajax[{{ !empty($extraDescription) ? $extraDescription->id : 'new' }}][locale]"
                                   value="{{ $defaultLocale }}">

                            <div class="kemetic-form-group">
                                <label class="kemetic-label">{{ trans('public.image') }}</label>

                                <div class="kemetic-input-group">
                                    <button type="button"
                                            class="kemetic-upload-btn panel-file-manager"
                                            data-input="image{{ !empty($extraDescription) ? $extraDescription->id : 'record' }}">
                                        <i data-feather="upload"></i>
                                    </button>

                                    <input type="text"
                                           id="image{{ !empty($extraDescription) ? $extraDescription->id : 'record' }}"
                                           name="ajax[{{ !empty($extraDescription) ? $extraDescription->id : 'new' }}][value]"
                                           value="{{ !empty($extraDescription) ? $extraDescription->value : '' }}"
                                           class="kemetic-input js-ajax-value">
                                </div>
                            </div>

                        @else

                            @if(!empty(getGeneralSettings('content_translate')))
                                <div class="kemetic-form-group">
                                    <label class="kemetic-label">{{ trans('auth.language') }}</label>
                                    <select name="ajax[{{ !empty($extraDescription) ? $extraDescription->id : 'new' }}][locale]"
                                            class="kemetic-input {{ !empty($extraDescription) ? 'js-webinar-content-locale' : '' }}">
                                        @foreach($userLanguages as $lang => $language)
                                            <option value="{{ $lang }}"
                                                {{ (!empty($extraDescription) && $extraDescription->locale == $lang) ? 'selected' : '' }}>
                                                {{ $language }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="kemetic-form-group">
                                <label class="kemetic-label">{{ trans('public.title') }}</label>
                                <input type="text"
                                       name="ajax[{{ !empty($extraDescription) ? $extraDescription->id : 'new' }}][value]"
                                       class="kemetic-input js-ajax-value"
                                       value="{{ !empty($extraDescription) ? $extraDescription->value : '' }}">
                            </div>

                        @endif
                    </div>
                </div>

                <div class="kemetic-accordion-buttons">
                    <button type="button"
                            class="btn kemetic-btn-gold js-save-extra_description">
                        {{ trans('public.save') }}
                    </button>

                    @if(empty($extraDescription))
                        <button type="button"
                                class="btn kemetic-btn-red ml-10 cancel-accordion">
                            {{ trans('public.close') }}
                        </button>
                    @endif
                </div>

            </div>
        </div>
    </div>
</li>
