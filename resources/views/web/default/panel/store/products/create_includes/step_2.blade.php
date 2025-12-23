@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
<link href="/assets/default/vendors/sortable/jquery-ui.min.css"/>

<style>
/* Kemetic Card */
.k-card{
    background:#141414;
    border:1px solid #262626;
    border-radius:18px;
    padding:22px;
}

/* Labels */
.k-card .input-label{
    color:#d4af37;
    font-size:13px;
    font-weight:600;
}

/* Inputs */
.k-card .form-control,
.k-card .custom-select,
.k-card .select2-container--default .select2-selection--single{
    background:#0b0b0b;
    color:#eaeaea;
    border:1px solid #2a2a2a;
    border-radius:12px;
}

.k-card .form-control:focus,
.k-card .custom-select:focus{
    border-color:#d4af37;
    box-shadow:0 0 0 2px rgba(212,175,55,.15);
}

/* Switch */
.k-switch .custom-control-input:checked~.custom-control-label::before{
    background:#d4af37;
    border-color:#d4af37;
}

/* Section titles */
.k-section-title{
    color:#eaeaea;
    font-weight:700;
    position:relative;
    padding-left:12px;
}
.k-section-title::before{
    content:'';
    position:absolute;
    left:0;
    top:50%;
    transform:translateY(-50%);
    width:4px;
    height:70%;
    background:#d4af37;
    border-radius:4px;
}

/* Filter box */
.k-filter-box{
    background:#0b0b0b;
    border:1px solid #2a2a2a;
    border-radius:14px;
    padding:15px;
}

/* Related courses */
.k-related-btn{
    background:#d4af37;
    color:#000;
    border:none;
    border-radius:12px;
    font-weight:600;
}
.k-related-btn:hover{
    background:#e5c252;
}

.custom-control {
  position: relative;
  z-index: 1;
  display: block;
  min-height: 1.3rem;
  padding-left: 2rem;
  -webkit-print-color-adjust: exact;
          print-color-adjust: exact;
}

.custom-control-inline {
  display: inline-flex;
  margin-right: 1rem;
}

.custom-control-input {
  position: absolute;
  left: 0;
  z-index: -1;
  width: 1.5rem;
  height: 1.4rem;
  opacity: 0;
}
.custom-control-input:checked ~ .custom-control-label::before {
  color: #ffffff;
  border-color: #F2C94C;
  background-color: #F2C94C;
  box-shadow: 0 0 10px rgba(242, 201, 76, 0.45); */
}
.custom-control-input:focus ~ .custom-control-label::before {
  box-shadow: none, 1.5rem;
}
.custom-control-input:focus:not(:checked) ~ .custom-control-label::before {
  border-color: #43d477;
}
.custom-control-input:not(:disabled):active ~ .custom-control-label::before {
  color: #ffffff;
  background-color: #43d477;
  border-color: #43d477;
}
.custom-control-input[disabled] ~ .custom-control-label, .custom-control-input:disabled ~ .custom-control-label {
  color: #6c757d;
}
.custom-control-input[disabled] ~ .custom-control-label::before, .custom-control-input:disabled ~ .custom-control-label::before {
  background-color: #f1f1f1;
}

.custom-control-label {
  position: relative;
  margin-bottom: 0;
  vertical-align: top;
}
.custom-control-label::before {
  position: absolute;
  top: -0.1rem;
  left: -2rem;
  display: block;
  width: 1.5rem;
  height: 1.5rem;
  pointer-events: none;
  content: "";
  background-color: #ffffff;
  border: 2px solid #adb5bd;
  box-shadow: none;
}
.custom-control-label::after {
  position: absolute;
  top: -0.1rem;
  left: -2rem;
  display: block;
  width: 1.5rem;
  height: 1.5rem;
  content: "";
  background: 50%/50% 50% no-repeat;
}

.custom-checkbox .custom-control-label::before {
  border-radius: 0.25rem;
}
.custom-checkbox .custom-control-input:checked ~ .custom-control-label::after {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%23ffffff' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26l2.974 2.99L8 2.193z'/%3e%3c/svg%3e");
}
.custom-checkbox .custom-control-input:indeterminate ~ .custom-control-label::before {
  border-color: #F2C94C;
  background-color: #F2C94C;
}#
.custom-checkbox .custom-control-input:indeterminate ~ .custom-control-label::after {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='4' height='4' viewBox='0 0 4 4'%3e%3cpath stroke='%23ffffff' d='M0 2h4'/%3e%3c/svg%3e");
}
.custom-checkbox .custom-control-input:disabled:checked ~ .custom-control-label::before {
  background-color: #F2C94C;
}
.custom-checkbox .custom-control-input:disabled:indeterminate ~ .custom-control-label::before {
  background-color: #F2C94C;
}

.custom-radio .custom-control-label::before {
  border-radius: 50%;
}
.custom-radio .custom-control-input:checked ~ .custom-control-label::after {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23ffffff'/%3e%3c/svg%3e");
}
.custom-radio .custom-control-input:disabled:checked ~ .custom-control-label::before {
  background-color: rgba(67, 212, 119, 0.5);
}

.custom-switch {
  padding-left: 4.125rem;
}
.custom-switch .custom-control-label::before {
  left: -3.125rem;
  width: 2.625rem;
  pointer-events: all;
  border-radius: 0.75rem;
}
.custom-switch .custom-control-label::after {
  top: calc(-0.1rem + 4px);
  left: calc(-3.125rem + 4px);
  width: calc(1.5rem - 8px);
  height: calc(1.5rem - 8px);
  background-color: #adb5bd;
  border-radius: 0.75rem;
  transition: transform 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
@media (prefers-reduced-motion: reduce) {
  .custom-switch .custom-control-label::after {
    transition: none;
  }
}
.custom-switch .custom-control-input:checked ~ .custom-control-label::after {
  background-color: #ffffff;
  transform: translateX(1.125rem);
}
.custom-switch .custom-control-input:disabled:checked ~ .custom-control-label::before {
  background-color: rgba(67, 212, 119, 0.5);
}

.kemetic-card {
    background: #0d0d0d;
    border: 1px solid rgba(242,201,76,.18);
    border-radius: 14px;
    padding: 22px;
    box-shadow: 0 10px 30px rgba(0,0,0,.55);
}

/* Labels */
.kemetic-label {
    font-size: 13px;
    font-weight: 600;
    color: #F2C94C;
    margin-bottom: 6px;
}

/* Inputs & Selects */
.kemetic-input,
.kemetic-select,
.kemetic-textarea {
    width: 100%;
    background: #111;
    border: 1px solid rgba(242,201,76,.25);
    color: #fff;
    border-radius: 10px;
    padding: 11px 14px;
    font-size: 14px;

    transition: all .25s ease;
}

.kemetic-textarea {
    resize: none;
}

/* Focus */
.kemetic-input:focus,
.kemetic-select:focus,
.kemetic-textarea:focus {
    outline: none;
    border-color: #F2C94C;
    box-shadow: 0 0 0 2px rgba(242,201,76,.15);
    background: #0f0f0f;
}

/* Spacing */
.kemetic-form-group {
    margin-top: 16px;
}

/* Invalid */
.is-invalid {
    border-color: #ff6b6b !important;
}

.invalid-feedback {
    color: #ff6b6b;
    font-size: 12px;
    margin-top: 5px;
}

/* Select arrow fix */
.kemetic-select {
    appearance: none;
    background-image:
        linear-gradient(45deg, transparent 50%, #F2C94C 50%),
        linear-gradient(135deg, #F2C94C 50%, transparent 50%);
    background-position:
        calc(100% - 18px) 18px,
        calc(100% - 12px) 18px;
    background-size: 6px 6px;
    background-repeat: no-repeat;
}
</style>
@endpush


<div class="row">
    <div class="col-12 col-md-6 mt-15">
        <div class="k-card">

            <div class="form-group">
                <label class="input-label">{{ trans('public.price') }} ({{ $currency }})</label>
                <input type="number" name="price"
                       value="{{ (!empty($product) && !empty($product->price)) ? convertPriceToUserCurrency($product->price) : old('price') }}"
                       class="form-control @error('price') is-invalid @enderror">
                @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            @if($product->isPhysical())
                <div class="form-group">
                    <label class="input-label">{{ trans('update.delivery_fee') }}</label>
                    <input type="number" name="delivery_fee"
                           value="{{ (!empty($product) && !empty($product->delivery_fee)) ? convertPriceToUserCurrency($product->delivery_fee) : old('delivery_fee') }}"
                           class="form-control @error('delivery_fee') is-invalid @enderror">
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('update.delivery_estimated_time') }}</label>
                    <input type="number" name="delivery_estimated_time"
                           value="{{ $product->delivery_estimated_time ?? old('delivery_estimated_time') }}"
                           class="form-control">
                </div>
            @endif

            <div class="form-group js-inventory-inputs {{ ($product->unlimited_inventory ?? false) ? 'd-none' : '' }}">
                <label class="input-label">{{ trans('update.inventory') }}</label>
                <input type="number" name="inventory"
                       value="{{ $product->getAvailability() ?? old('inventory') }}"
                       class="form-control">
            </div>

            <div class="form-group js-inventory-inputs {{ ($product->unlimited_inventory ?? false) ? 'd-none' : '' }}">
                <label class="input-label">{{ trans('update.inventory_warning') }}</label>
                <input type="number" name="inventory_warning"
                       value="{{ $product->inventory_warning ?? old('inventory_warning') }}"
                       class="form-control">
            </div>

            <div class="form-group mt-25 d-flex align-items-center k-switch" style="margin-top:10px;">
                <label class="input-label mb-0" for="unlimitedInventorySwitch">
                    {{ trans('update.unlimited_inventory') }}
                </label>
                <div class="ml-30 custom-control custom-switch">
                    <input type="checkbox" name="unlimited_inventory"
                           class="custom-control-input"
                           id="unlimitedInventorySwitch"
                           {{ (!empty($product) && $product->unlimited_inventory) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="unlimitedInventorySwitch"></label>
                </div>
            </div>

            <p class="text-gray font-12">{{ trans('update.create_product_unlimited_inventory_hint') }}</p>

        </div>
    </div>
</div>


<div class="row mt-30">
    <div class="col-12 col-md-6">

        <div class="form-group kemetic-form-group">
            <label class="kemetic-label">{{ trans('public.category') }}</label>

            <select name="type" class="kemetic-select @error('type') is-invalid @enderror" id="categories" name="category_id">
                <option disabled selected>{{ trans('public.choose_category') }}</option>
                @foreach($productCategories as $cat)
                    @foreach($cat->subCategories ?? [] as $sub)
                        <option value="{{ $sub->id }}" {{ $product->category_id == $sub->id ? 'selected' : '' }}>
                            {{ $sub->title }}
                        </option>
                    @endforeach
                @endforeach
            </select>

            @error('type')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        
    </div>

    <div class="col-12 mt-20">
        <div id="categoriesFiltersContainer"
             class="{{ empty($productCategoryFilters) ? 'd-none' : '' }}">

            <div class="k-card">
                <span class="input-label">{{ trans('public.category_filters') }}</span>

                <div class="row">
                    @foreach($productCategoryFilters ?? [] as $filter)
                        <div class="col-12 col-md-3 mt-15">
                            <div class="k-filter-box">
                                <strong class="text-white">{{ $filter->title }}</strong>

                                @foreach($filter->options as $option)
                                    <div class="d-flex align-items-center justify-content-between mt-10">
                                        <label class="text-gray">{{ $option->title }}</label>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox"
                                                   class="custom-control-input"
                                                   id="filterOptions{{ $option->id }}"
                                                   name="filters[]"
                                                   value="{{ $option->id }}">
                                            <label class="custom-control-label" for="filterOptions{{ $option->id }}"></label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>


<section class="mt-50">
    <h2 class="k-section-title">
        {{ trans('update.related_courses') }} ({{ trans('public.optional') }})
    </h2>

    <button id="webinarAddRelatedCourses"
            type="button"
            data-bundle-id="{{ $product->id }}"
            class="btn k-related-btn btn-sm mt-15">
        {{ trans('update.add_related_courses') }}
    </button>

    <div class="k-card mt-20">
        <div id="relatedCoursesAccordion">
            @if(!empty($product->relatedCourses))
                <ul class="draggable-lists" data-order-table="relatedCourses">
                    @foreach($product->relatedCourses as $relatedCourseInfo)
                        @include('web.default.panel.store.products.create_includes.accordions.related_courses')
                    @endforeach
                </ul>
            @else
                @include(getTemplate().'.includes.no-result',[
                    'file_name'=>'comment.png',
                    'title'=>trans('update.related_courses_no_result')
                ])
            @endif
        </div>
    </div>
</section>


<div id="newRelatedCourseForm" class="d-none">
    @include('web.default.panel.store.products.create_includes.accordions.related_courses',['product' => $product])
</div>


@push('scripts_bottom')
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>

    <script src="/assets/default/js/panel/webinar.min.js"></script>
@endpush
