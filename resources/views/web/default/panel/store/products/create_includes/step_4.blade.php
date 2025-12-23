@push('styles_top')
<link href="/assets/default/vendors/sortable/jquery-ui.min.css"/>
<link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
<link rel="stylesheet" href="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.css">

<style>
:root{
    --k-bg:#0b0b0b;
    --k-card:#141414;
    --k-gold:#f2c94c;
    --k-gold-soft:#e6b93d;
    --k-border:rgba(242,201,76,.25);
    --k-text:#eaeaea;
    --k-muted:#9a9a9a;
    --k-radius:16px;
}

/* Card */
.k-card{
    background:var(--k-card);
    border:1px solid var(--k-border);
    border-radius:var(--k-radius);
    padding:24px;
}

/* Title */
.k-title{
    color:var(--k-gold);
    font-weight:600;
    letter-spacing:.4px;
}

/* Buttons */
.k-btn{
    background:linear-gradient(135deg,var(--k-gold),var(--k-gold-soft));
    border:none;
    color:#000;
    font-weight:600;
}
.k-btn-outline{
    background:#101010;
    border:1px dashed var(--k-border);
    color:var(--k-gold);
}

/* Accordion container */
.k-accordion{
    background:#0f0f0f;
    border:1px dashed var(--k-border);
    border-radius:14px;
    padding:16px;
}
</style>
@endpush


<div class="row">

    {{-- SPECIFICATIONS --}}
    <div class="col-12 mt-25">
        <div class="k-card">

            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <h3 class="k-title mb-0">
                    {{ trans('update.specifications') }}
                </h3>

                <button type="button"
                        id="productAddSpecification"
                        class="btn btn-sm k-btn mt-10 mt-md-0">
                    {{ trans('update.new_specification') }}
                </button>
            </div>

            <div class="k-accordion mt-20"
                 id="specificationsAccordion"
                 role="tablist"
                 aria-multiselectable="true">

                @if(!empty($product->selectedSpecifications) and count($product->selectedSpecifications))
                    <ul class="draggable-lists"
                        data-order-path="/panel/store/products/specifications/order-items">
                        @foreach($product->selectedSpecifications as $selectedSpecificationRow)
                            @include(
                                'web.default.panel.store.products.create_includes.accordions.specification',
                                ['selectedSpecification'=>$selectedSpecificationRow]
                            )
                        @endforeach
                    </ul>
                @else
                    @include(getTemplate().'.includes.no-result',[
                        'file_name'=>'files.png',
                        'title'=>trans('update.specifications_no_result'),
                        'hint'=>trans('update.specifications_no_result_hint'),
                    ])
                @endif
            </div>

            <div id="newSpecificationForm" class="d-none">
                @include('web.default.panel.store.products.create_includes.accordions.specification')
            </div>

        </div>
    </div>

    {{-- FAQ --}}
    <div class="col-12 mt-40">
        <div class="k-card">

            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <h3 class="k-title mb-0">
                    {{ trans('public.faq') }}
                </h3>

                <button type="button"
                        id="productAddFAQ"
                        class="btn btn-sm k-btn mt-10 mt-md-0">
                    {{ trans('webinars.add_new_faqs') }}
                </button>
            </div>

            <div class="k-accordion mt-20"
                 id="faqsAccordion"
                 role="tablist"
                 aria-multiselectable="true">

                @if(!empty($product->faqs) and count($product->faqs))
                    <ul class="draggable-lists2"
                        data-order-path="/panel/store/products/faqs/order-items">
                        @foreach($product->faqs as $faqRow)
                            @include(
                                'web.default.panel.store.products.create_includes.accordions.faq',
                                ['faq'=>$faqRow]
                            )
                        @endforeach
                    </ul>
                @else
                    @include(getTemplate().'.includes.no-result',[
                        'file_name'=>'faq.png',
                        'title'=>trans('update.product_faq_no_result'),
                        'hint'=>trans('update.product_faq_no_result_hint'),
                    ])
                @endif
            </div>

            <div id="newFaqForm" class="d-none">
                @include('web.default.panel.store.products.create_includes.accordions.faq')
            </div>

        </div>
    </div>

</div>


@push('scripts_bottom')
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>
@endpush
