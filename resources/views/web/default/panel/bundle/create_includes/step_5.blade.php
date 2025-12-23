@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/sortable/jquery-ui.min.css"/>

<style>
/* ===============================
   KEMETIC THEME VARIABLES
================================ */
:root {
    --k-black: #0d0d0d;
    --k-dark: #141414;
    --k-gold: #f2c94c;
    --k-gold-soft: rgba(242, 201, 76, 0.15);
    --k-border: rgba(242, 201, 76, 0.25);
    --k-radius: 14px;
}

/* ===============================
   SECTION CONTAINER
================================ */
.kemetic-section {
    background: var(--k-black);
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius);
    padding: 25px;
    margin-top: 50px;
}

/* ===============================
   SECTION TITLE
================================ */
.kemetic-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--k-gold);
    position: relative;
    padding-left: 14px;
}

.kemetic-title::before {
    content: "";
    position: absolute;
    left: 0;
    top: 3px;
    width: 4px;
    height: 18px;
    background: var(--k-gold);
    border-radius: 4px;
}

.kemetic-title small {
    color: #999;
    font-weight: 400;
}

/* ===============================
   ADD FAQ BUTTON
================================ */
.kemetic-btn {
    background: linear-gradient(135deg, #f2c94c, #c9a63c);
    color: #000;
    border: none;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    padding: 8px 18px;
    transition: all .25s ease;
}

.kemetic-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(242,201,76,.35);
}

/* ===============================
   ACCORDION CONTAINER
================================ */
.kemetic-accordion {
    margin-top: 20px;
}

/* ===============================
   ACCORDION ITEM WRAPPER
================================ */
.kemetic-accordion-item {
    background: var(--k-dark);
    border: 1px solid var(--k-border);
    border-radius: 14px;
    padding: 16px 18px;
    margin-bottom: 12px;
    transition: .25s ease;
}

.kemetic-accordion-item:hover {
    background: #1b1b1b;
    border-color: var(--k-gold);
}

/* ===============================
   EMPTY STATE
================================ */
.kemetic-empty {
    background: #111;
    border: 1px dashed var(--k-border);
    border-radius: 14px;
    padding: 40px 20px;
    text-align: center;
    color: #aaa;
}

.kemetic-empty h3 {
    font-size: 15px;
    color: var(--k-gold);
    margin-bottom: 8px;
}
</style>
@endpush


{{-- ===============================
   FAQ SECTION
================================ --}}
<section class="kemetic-section">
    <div class="d-flex align-items-center justify-content-between">
        <h2 class="kemetic-title">
            {{ trans('public.faq') }}
            <small>({{ trans('public.optional') }})</small>
        </h2>

        <button id="webinarAddFAQ"
                data-bundle-id="{{ $bundle->id }}"
                type="button"
                class="kemetic-btn">
            + {{ trans('public.add_faq') }}
        </button>
    </div>

    <div class="kemetic-accordion" id="faqsAccordion" role="tablist" aria-multiselectable="true">
        @if(!empty($bundle->faqs) && count($bundle->faqs))
            <ul class="draggable-lists" data-order-table="faqs">
                @foreach($bundle->faqs as $faqInfo)
                    <li class="kemetic-accordion-item">
                        @include(
                            'web.default.panel.bundle.create_includes.accordions.faq',
                            ['bundle' => $bundle, 'faq' => $faqInfo]
                        )
                    </li>
                @endforeach
            </ul>
        @else
            <div class="kemetic-empty">
                <h3>{{ trans('public.faq_no_result') }}</h3>
                <p>{{ trans('public.faq_no_result_hint') }}</p>
            </div>
        @endif
    </div>
</section>


{{-- ===============================
   HIDDEN NEW FAQ FORM
================================ --}}
<div id="newFaqForm" class="d-none">
    @include('web.default.panel.bundle.create_includes.accordions.faq',['bundle' => $bundle])
</div>


@push('scripts_bottom')
<script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>
@endpush
