@push('styles_top')
<style>
/* Kemetic theme variables */
:root {
    --k-bg: #0b0b0b;
    --k-card: #141414;
    --k-gold: #f2c94c;
    --k-gold-soft: #e6b93d;
    --k-border: rgba(242, 201, 76, 0.25);
    --k-text: #eaeaea;
    --k-muted: #9a9a9a;
    --k-radius: 16px;
}

/* Accordion card */
.k-accordion-item {
    background: var(--k-card);
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius);
    margin-top: 20px;
    padding: 15px 20px;
    transition: all 0.3s;
}
.k-accordion-item:hover {
    box-shadow: 0 0 15px rgba(242, 201, 76, 0.2);
}

/* Header */
.k-accordion-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
}
.k-accordion-header .title {
    font-weight: 600;
    color: var(--k-gold);
}
.k-accordion-header .actions i {
    margin-left: 10px;
    color: var(--k-muted);
    cursor: pointer;
}
.k-accordion-header .actions i:hover {
    color: var(--k-gold-soft);
}

/* Collapsible content */
.k-accordion-body {
    margin-top: 15px;
    color: var(--k-text);
}
.k-accordion-body input,
.k-accordion-body textarea,
.k-accordion-body select {
    background: #0f0f0f;
    border: 1px solid var(--k-border);
    border-radius: 12px;
    color: var(--k-text);
    padding: 10px;
    width: 100%;
}

/* Buttons */
.k-accordion-body .btn-primary {
    background-color: var(--k-gold);
    border: none;
    color: #000;
}
.k-accordion-body .btn-primary:hover {
    background-color: var(--k-gold-soft);
}
.k-accordion-body .btn-danger {
    background-color: #ff4c4c;
    color: #fff;
}
.k-accordion-body .btn-danger:hover {
    background-color: #ff2a2a;
}
</style>
@endpush

<li data-id="{{ !empty($faq) ? $faq->id : '' }}" class="accordion-row k-accordion-item">
    <div class="k-accordion-header" data-toggle="collapse" href="#collapseFaq{{ !empty($faq) ? $faq->id : 'record' }}" aria-controls="collapseFaq{{ !empty($faq) ? $faq->id : 'record' }}" data-parent="#faqsAccordion">
        <div class="d-flex align-items-center">
            <i data-feather="help-circle" class="mr-10"></i>
            <span class="title">{{ !empty($faq) ? $faq->title : trans('webinars.add_new_faqs') }}</span>
        </div>
        <div class="actions d-flex align-items-center">
            <i data-feather="move" class="move-icon"></i>
            @if(!empty($faq))
                <a href="/panel/store/products/faqs/{{ $faq->id }}/delete" class="delete-action">
                    <i data-feather="trash-2"></i>
                </a>
            @endif
            <i data-feather="chevron-down" class="collapse-icon"></i>
        </div>
    </div>

    <div id="collapseFaq{{ !empty($faq) ? $faq->id : 'record' }}" class="collapse @if(empty($faq)) show @endif k-accordion-body" aria-labelledby="faq_{{ !empty($faq) ? $faq->id : 'record' }}">
        <div class="js-content-form faq-form" data-action="/panel/store/products/faqs/{{ !empty($faq) ? $faq->id . '/update' : 'store' }}">
            <input type="hidden" name="ajax[{{ !empty($faq) ? $faq->id : 'new' }}][product_id]" value="{{ !empty($product) ? $product->id : '' }}">

            <div class="row">
                <div class="col-12 col-lg-6">
                    @if(!empty(getGeneralSettings('content_translate')))
                        <div class="form-group">
                            <label class="input-label">{{ trans('auth.language') }}</label>
                            <select name="ajax[{{ !empty($faq) ? $faq->id : 'new' }}][locale]"
                                    class="form-control {{ !empty($faq) ? 'js-product-content-locale' : '' }}"
                                    data-product-id="{{ !empty($product) ? $product->id : '' }}"
                                    data-id="{{ !empty($faq) ? $faq->id : '' }}"
                                    data-relation="faqs"
                                    data-fields="title,answer"
                            >
                                @foreach(getUserLanguagesLists() as $lang => $language)
                                    <option value="{{ $lang }}" {{ (!empty($faq) and !empty($faq->locale)) ? (mb_strtolower($faq->locale) == mb_strtolower($lang) ? 'selected' : '') : ($locale == $lang ? 'selected' : '') }}>{{ $language }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="ajax[{{ !empty($faq) ? $faq->id : 'new' }}][locale]" value="{{ $defaultLocale }}">
                    @endif

                    <div class="form-group">
                        <label class="input-label">{{ trans('public.title') }}</label>
                        <input type="text" name="ajax[{{ !empty($faq) ? $faq->id : 'new' }}][title]" class="js-ajax-title form-control" value="{{ !empty($faq) ? $faq->title : '' }}" placeholder="{{ trans('forms.maximum_255_characters') }}"/>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label class="input-label">{{ trans('public.answer') }}</label>
                        <textarea name="ajax[{{ !empty($faq) ? $faq->id : 'new' }}][answer]" class="js-ajax-answer form-control" rows="6">{{ !empty($faq) ? $faq->answer : '' }}</textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>

            <div class="mt-30 d-flex align-items-center" style="padding:10px;">
                <button type="button" class="js-save-faq btn btn-primary">{{ trans('public.save') }}</button>

                @if(empty($faq))
                    <button type="button" class="btn btn-danger ml-10 cancel-accordion">{{ trans('public.close') }}</button>
                @endif
            </div>
        </div>
    </div>
</li>
