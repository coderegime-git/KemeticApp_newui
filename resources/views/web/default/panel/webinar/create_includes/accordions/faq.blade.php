<style>
    /* FAQ Card */
.kemetic-faq-item {
    background: linear-gradient(180deg, #0b0b0b, #121212);
    border: 1px solid rgba(212,175,55,.35);
    border-radius: 14px;
    overflow: hidden;
}

/* Header */
.kemetic-faq-header {
    padding: 16px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    background: rgba(212,175,55,.05);
}

.kemetic-faq-title {
    font-size: 15px;
    font-weight: 600;
    color: #d4af37;
}

/* Actions */
.kemetic-faq-actions {
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
.kemetic-faq-body {
    background: #0e0e0e;
}

.kemetic-faq-content {
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

/* Buttons */
.kemetic-faq-buttons {
    margin-top: 20px;
    display: flex;
    align-items: center;
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
<li data-id="{{ !empty($faq) ? $faq->id :'' }}"
    class="accordion-row kemetic-faq-item mt-20">

    <!-- HEADER -->
    <div class="kemetic-faq-header"
         role="tab"
         id="faq_{{ !empty($faq) ? $faq->id :'record' }}"
         data-toggle="collapse"
         href="#collapseFaq{{ !empty($faq) ? $faq->id :'record' }}"
         aria-expanded="true">

        <div class="kemetic-faq-title">
            {{ !empty($faq) ? $faq->title : trans('webinars.add_new_faqs') }}
        </div>

        <div class="kemetic-faq-actions">

            <i data-feather="move" class="kemetic-icon drag-icon"></i>

            @if(!empty($faq))
                <div class="dropdown kemetic-dropdown">
                    <button class="kemetic-dot-btn" data-toggle="dropdown">
                        <i data-feather="more-vertical"></i>
                    </button>

                    <div class="dropdown-menu kemetic-dropdown-menu">
                        <a href="/panel/faqs/{{ $faq->id }}/delete"
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
    <div id="collapseFaq{{ !empty($faq) ? $faq->id :'record' }}"
         class="collapse kemetic-faq-body @if(empty($faq)) show @endif"
         aria-labelledby="faq_{{ !empty($faq) ? $faq->id :'record' }}">

        <div class="kemetic-faq-content">

            <div class="js-content-form faq-form"
                 data-action="/panel/faqs/{{ !empty($faq) ? $faq->id . '/update' : 'store' }}">

                <input type="hidden"
                       name="ajax[{{ !empty($faq) ? $faq->id : 'new' }}][webinar_id]"
                       value="{{ !empty($webinar) ? $webinar->id :'' }}">

                <div class="row">
                    <div class="col-12 col-lg-6">

                        @if(!empty(getGeneralSettings('content_translate')))
                            <div class="kemetic-form-group">
                                <label class="kemetic-label">{{ trans('auth.language') }}</label>
                                <select class="kemetic-input
                                        {{ !empty($faq) ? 'js-webinar-content-locale' : '' }}"
                                        name="ajax[{{ !empty($faq) ? $faq->id : 'new' }}][locale]">
                                    @foreach($userLanguages as $lang => $language)
                                        <option value="{{ $lang }}"
                                            {{ (!empty($faq) && $faq->locale == $lang) ? 'selected' : '' }}>
                                            {{ $language }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="kemetic-form-group">
                            <label class="kemetic-label">{{ trans('public.title') }}</label>
                            <input type="text"
                                   name="ajax[{{ !empty($faq) ? $faq->id : 'new' }}][title]"
                                   class="kemetic-input js-ajax-title"
                                   value="{{ !empty($faq) ? $faq->title : '' }}">
                        </div>

                        <div class="kemetic-form-group">
                            <label class="kemetic-label">{{ trans('public.answer') }}</label>
                            <textarea name="ajax[{{ !empty($faq) ? $faq->id : 'new' }}][answer]"
                                      class="kemetic-input js-ajax-answer"
                                      rows="5">{{ !empty($faq) ? $faq->answer : '' }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="kemetic-faq-buttons">
                    <button type="button"
                            class="btn kemetic-btn-gold js-save-faq">
                        {{ trans('public.save') }}
                    </button>

                    @if(empty($faq))
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
