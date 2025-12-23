@push('styles_top')
<style>
/* ===============================
   KEMETIC VARIABLES
================================ */
:root {
    --k-black: #0b0b0b;
    --k-dark: #141414;
    --k-dark-2: #1c1c1c;
    --k-gold: #f2c94c;
    --k-gold-soft: rgba(242, 201, 76, 0.15);
    --k-border: rgba(242, 201, 76, 0.25);
    --k-radius: 16px;
}

/* ===============================
   FAQ ACCORDION ITEM
================================ */
.kemetic-faq-item {
    background: var(--k-black);
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius);
    margin-top: 18px;
    transition: .25s ease;
}

.kemetic-faq-item:hover {
    box-shadow: 0 12px 30px rgba(0,0,0,.6);
}

/* ===============================
   HEADER
================================ */
.kemetic-faq-header {
    padding: 16px 18px;
    cursor: pointer;
}

.kemetic-faq-title {
    color: var(--k-gold);
    font-weight: 700;
    font-size: 15px;
}

/* ===============================
   ICONS
================================ */
.kemetic-icon {
    color: #bbb;
    transition: .2s ease;
}

.kemetic-icon:hover {
    color: var(--k-gold);
}

/* ===============================
   BODY
================================ */
.kemetic-faq-body {
    background: var(--k-dark);
    border-top: 1px solid var(--k-border);
    padding: 20px;
    border-radius: 0 0 var(--k-radius) var(--k-radius);
}

/* ===============================
   FORM
================================ */
.kemetic-label {
    color: #ddd;
    font-weight: 600;
    margin-bottom: 6px;
}

.kemetic-input,
.kemetic-textarea,
.kemetic-select {
    background: var(--k-dark-2);
    border: 1px solid var(--k-border);
    color: #fff;
    border-radius: 12px;
}

.kemetic-input:focus,
.kemetic-textarea:focus,
.kemetic-select:focus {
    border-color: var(--k-gold);
    box-shadow: 0 0 0 2px var(--k-gold-soft);
}

/* ===============================
   BUTTONS
================================ */
.kemetic-btn-primary {
    background: linear-gradient(135deg, #f2c94c, #d4af37);
    color: #000;
    font-weight: 700;
    border-radius: 10px;
    padding: 6px 18px;
}

.kemetic-btn-danger {
    background: transparent;
    color: #ff6b6b;
    border: 1px solid #ff6b6b;
    border-radius: 10px;
    padding: 6px 18px;
}

/* ===============================
   DROPDOWN
================================ */
.kemetic-dropdown {
    background: var(--k-dark-2);
    border: 1px solid var(--k-border);
}

.kemetic-dropdown a {
    color: #ddd;
}

.kemetic-dropdown a:hover {
    background: var(--k-gold-soft);
    color: var(--k-gold);
}
</style>
@endpush


<li data-id="{{ !empty($faq) ? $faq->id :'' }}"
    class="accordion-row kemetic-faq-item">

    {{-- HEADER --}}
    <div class="d-flex align-items-center justify-content-between kemetic-faq-header"
         role="tab"
         id="faq_{{ !empty($faq) ? $faq->id :'record' }}">

        <div class="kemetic-faq-title"
             href="#collapseFaq{{ !empty($faq) ? $faq->id :'record' }}"
             data-toggle="collapse"
             data-parent="#faqsAccordion"
             aria-expanded="true">
            {{ !empty($faq) ? $faq->title : trans('webinars.add_new_faqs') }}
        </div>

        <div class="d-flex align-items-center">

            <i data-feather="move" class="kemetic-icon mr-10 cursor-pointer" height="20"></i>

            @if(!empty($faq))
                <div class="btn-group dropdown mr-10">
                    <button class="btn btn-sm btn-transparent dropdown-toggle"
                            data-toggle="dropdown">
                        <i data-feather="more-vertical" class="kemetic-icon" height="18"></i>
                    </button>

                    <div class="dropdown-menu kemetic-dropdown">
                        <a href="/panel/faqs/{{ $faq->id }}/delete"
                           class="delete-action dropdown-item">
                            {{ trans('public.delete') }}
                        </a>
                    </div>
                </div>
            @endif

            <i data-feather="chevron-down"
               class="kemetic-icon"
               height="20"
               data-toggle="collapse"
               href="#collapseFaq{{ !empty($faq) ? $faq->id :'record' }}">
            </i>
        </div>
    </div>

    {{-- BODY --}}
    <div id="collapseFaq{{ !empty($faq) ? $faq->id :'record' }}"
         class="collapse @if(empty($faq)) show @endif"
         role="tabpanel">

        <div class="kemetic-faq-body">

            <div class="js-content-form faq-form"
                 data-action="/panel/faqs/{{ !empty($faq) ? $faq->id . '/update' : 'store' }}">

                <input type="hidden"
                       name="ajax[{{ !empty($faq) ? $faq->id : 'new' }}][bundle_id]"
                       value="{{ !empty($bundle) ? $bundle->id :'' }}">

                <div class="row">
                    <div class="col-12 col-lg-6">

                        {{-- LANGUAGE --}}
                        @if(!empty(getGeneralSettings('content_translate')))
                            <div class="form-group">
                                <label class="kemetic-label">{{ trans('auth.language') }}</label>
                                <select
                                    name="ajax[{{ !empty($faq) ? $faq->id : 'new' }}][locale]"
                                    class="form-control kemetic-select {{ !empty($faq) ? 'js-bundle-content-locale' : '' }}"
                                    data-bundle-id="{{ !empty($bundle) ? $bundle->id : '' }}"
                                    data-id="{{ !empty($faq) ? $faq->id : '' }}"
                                    data-relation="faqs"
                                    data-fields="title,answer">

                                    @foreach($userLanguages as $lang => $language)
                                        <option value="{{ $lang }}"
                                            {{ (!empty($faq) && $faq->locale == $lang) ? 'selected' : ($locale == $lang ? 'selected' : '') }}>
                                            {{ $language }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden"
                                   name="ajax[{{ !empty($faq) ? $faq->id : 'new' }}][locale]"
                                   value="{{ $defaultLocale }}">
                        @endif

                        {{-- TITLE --}}
                        <div class="form-group">
                            <label class="kemetic-label">{{ trans('public.title') }}</label>
                            <input type="text"
                                   name="ajax[{{ !empty($faq) ? $faq->id : 'new' }}][title]"
                                   class="js-ajax-title form-control kemetic-input"
                                   value="{{ !empty($faq) ? $faq->title : '' }}"
                                   placeholder="{{ trans('forms.maximum_64_characters') }}">
                            <div class="invalid-feedback"></div>
                        </div>

                        {{-- ANSWER --}}
                        <div class="form-group">
                            <label class="kemetic-label">{{ trans('public.answer') }}</label>
                            <textarea
                                name="ajax[{{ !empty($faq) ? $faq->id : 'new' }}][answer]"
                                class="js-ajax-answer form-control kemetic-textarea"
                                rows="6">{{ !empty($faq) ? $faq->answer : '' }}</textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="mt-25 d-flex align-items-center" style="padding:10px;">
                    <button type="button"
                            class="js-save-faq btn kemetic-btn-primary btn-sm">
                        {{ trans('public.save') }}
                    </button>

                    @if(empty($faq))
                        <button type="button"
                                class="btn kemetic-btn-danger btn-sm ml-10 cancel-accordion">
                            {{ trans('public.close') }}
                        </button>
                    @endif
                </div>

            </div>
        </div>
    </div>
</li>
