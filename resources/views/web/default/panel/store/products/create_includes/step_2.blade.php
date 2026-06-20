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
.k-card .custom-select {
    background:#0b0b0b;
    color:#eaeaea;
    border:1px solid #2a2a2a;
    border-radius:12px;
    width: fit-content !important;
}

.k-card .select2-container--default .select2-selection--single {
    background:#0b0b0b !important;
    color:#eaeaea !important;
    border:1px solid #2a2a2a !important;
    border-radius:12px !important;
    width: 100% !important;
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
    background: #1a1a1a;
    color: #fff;
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

.select2-container--default .select2-selection--single {
    background: #0d0d0d !important;
    border: 1px solid rgba(242,201,76,.35) !important;
    border-radius: 14px !important;
    height: 45px !important;
    display: flex;
    align-items: center;
    color: #e0e0e0 !important;
    width: 100% !important;
}

/* text */
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #e0e0e0 !important;
    line-height: 45px !important;
}

/* arrow */
.select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: #f2c94c transparent transparent transparent !important;
}

/* dropdown */
.select2-dropdown {
    background: #0f0f0f !important;
    border: 1px solid rgba(242,201,76,.35) !important;
    border-radius: 12px !important;
    z-index: 9999 !important;
}

.select2-container {
    width: 100% !important;
}

/* options */
.select2-results__option {
    color: #e0e0e0 !important;
    padding: 10px 14px !important;
}

/* hover */
.select2-results__option--highlighted {
    background: rgba(242,201,76,.15) !important;
    color: #fff !important;
}

/* selected */
.select2-results__option[aria-selected=true] {
    background: rgba(242,201,76,.25) !important;
}

/* search box */
.select2-search--dropdown .select2-search__field {
    background: #0d0d0d !important;
    border: 1px solid rgba(242,201,76,.35) !important;
    color: #fff !important;
    border-radius: 8px !important;
}

.bg-gold-transparent {
    background: rgba(212,175,55,0.15) !important;
}
</style>
@endpush


<div class="row">
    <div class="col-12 col-xl-8 mt-15">
        <div class="k-card">

            @if(!empty($cjProduct))
                @php
                    // Use cjPrice from controller if available, else fallback to product sellPrice
                    $baseCjPrice     = (float)($cjPrice ?? $cjProduct['sellPrice'] ?? 0);

                    // Displayed base price = original + 20% VAT
                    $baseCjPriceVat  = round($baseCjPrice * 1.20, 2);

                    // Shipping: simple tier based on ORIGINAL price (no extra %)
                    $autoShipping    = $baseCjPrice > 0 ? (int)(ceil($baseCjPrice / 10) * 10) : 0;

                    // Initial total calculation: (VAT base + Shipping) / 0.9
                    $initialSubtotal = $baseCjPriceVat + $autoShipping;
                    $initialTotal    = $initialSubtotal / 0.9;
                @endphp
                <div class="kemetic-card mb-20 shadow-sm" style="background: rgba(212,175,55,0.05); border: 1px dashed #d4af37;">
                    <h5 class="k-section-title font-16 mb-15">CJ Dropshipping Pricing</h5>
                    
                    {{-- VAT Notice Banner --}}
                    <!-- <div id="cjVatNotice" class="d-flex align-items-start mb-15 p-10 rounded" style="background:rgba(242,201,76,0.10); border:1px solid rgba(242,201,76,0.40);">
                        <span style="font-size:18px; margin-right:8px;">⚠️</span>
                        <div>
                            <strong class="text-gold font-13">20% VAT Applied to CJ Base Price</strong>
                            <p class="mb-0 font-11 text-gray" id="cjVatNoticeText">
                                CJ cost <span class="text-white" id="cjOriginalPriceLabel">{{ $currency }}{{ number_format($baseCjPrice, 2) }}</span>
                                + 20% VAT = <span class="text-gold font-weight-bold" id="cjVatPriceLabel">{{ $currency }}{{ number_format($baseCjPriceVat, 2) }}</span> (used as base price)
                            </p>
                        </div>
                    </div> -->

                    <div class="row">
                        <div class="col-12 mb-15">
                            <span class="d-block text-gray font-12">CJ Base Price <small class="text-gold">(incl. 20% VAT)</small></span>
                            <span class="font-18 font-weight-bold text-gold" id="cjBasePriceDisplay">{{ $currency }}{{ number_format($baseCjPriceVat, 2) }}</span>
                            <input type="hidden" id="cjOriginalPriceRaw" value="{{ $baseCjPrice }}">
                        </div>
                    </div>

                    {{-- Variants Table --}}
                    @if(!empty($cjProduct['variants']))
                    <div class="mb-20">
                        <span class="d-block text-gray font-12 mb-10">Available Variants & Prices</span>
                        <div class="table-responsive" style="max-height: 150px; overflow-y: auto; background: #0b0b0b; border-radius: 8px; border: 1px solid #2a2a2a;">
                            <table class="table table-sm text-white font-12 mb-0 text-nowrap">
                                <thead>
                                    <tr style="background: #1a1a1a;">
                                        <th class="pl-10">Variant</th>
                                        <th>CJ Base</th>
                                        <th>Shipping</th>
                                        <th>Earning</th>
                                        <th>Fee (10%)</th>
                                        <th>Total</th>
                                        <th class="text-right pr-10">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cjProduct['variants'] as $v)
                                        @php 
                                            $vPrice      = (float)($v['variantSellPrice'] ?? $v['sellPrice'] ?? 0);
                                            // VAT-adjusted display price
                                            $vPriceVat   = round($vPrice * 1.20, 2);
                                            // Shipping: simple tier on ORIGINAL price
                                            $vShipping   = $vPrice > 0 ? (int)(ceil($vPrice / 10) * 10) : 0;
                                            $vEarning    = (float)($product->cj_your_price ?? 0);
                                            $vSubtotal   = $vPriceVat + $vShipping + $vEarning;
                                            $vFinal      = ceil($vSubtotal / 0.9);
                                            $vFee        = $vFinal * 0.1;
                                        @endphp
                                        <tr class="{{ ($cjVariantId == $v['vid']) ? 'bg-gold-transparent' : '' }} variant-row" data-base-price="{{ $vPrice }}" data-base-price-vat="{{ $vPriceVat }}">
                                            <td class="pl-10" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $v['variantNameEn'] ?? 'Variant' }}">
                                                {{ !empty($v['variantKey']) ? $v['variantKey'] : ($v['variantNameEn'] ?? 'Variant') }}
                                            </td>
                                            <td class="v-base-price" title="Original: {{ $currency }}{{ number_format($vPrice, 2) }}">{{ $currency }}{{ number_format($vPriceVat, 2) }} </td>
                                            <td class="v-shipping-price">{{ $currency }}{{ number_format($vShipping, 2) }}</td>
                                            <td class="v-earning-price">{{ $currency }}{{ number_format($vEarning, 2) }}</td>
                                            <td class="v-fee-price">{{ $currency }}{{ number_format($vFee, 2) }}</td>
                                            <td class="v-sell-price font-weight-bold text-gold">{{ $currency }}{{ number_format($vFinal, 2) }}</td>
                                            <td class="text-right pr-10">
                                                <button type="button" class="btn btn-xs btn-kemetic-outline py-0 px-2 use-variant-price" 
                                                        data-price="{{ $vPrice }}" data-vid="{{ $v['vid'] }}">
                                                    Use
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="kemetic-label">Shipping Price</label>
                                <input type="number" id="cjShippingPrice" name="cj_shipping_price" step="1"
                                       class="kemetic-input"
                                       placeholder="0"
                                       value="{{ $autoShipping }}"
                                       readonly
                                       style="opacity: 0.5; cursor: not-allowed; pointer-events: none; background: #0a0a0a;">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="kemetic-label">Earning price</label>
                                <input type="number" id="userMargin" name="cj_your_price" step="0.01" class="kemetic-input" placeholder="0" value="{{ (!empty($product) && isset($product->cj_your_price)) ? $product->cj_your_price : old('cj_your_price', '0') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-10">
                        <div class="col-6">
                            <span class="d-block text-gray font-11">Subtotal (CJ + S + E)</span>
                            <span class="text-white font-weight-bold" id="pricingSubtotal">{{ $currency }}{{ number_format($baseCjPrice, 2) }}</span>
                        </div>
                        <div class="col-6">
                            <span class="d-block text-gray font-11">Platform Fee (10%)</span>
                            <input type="number" id="platformprice" name="platform_price" step="0.01" class="kemetic-input" placeholder="0" value="{{ (!empty($product) && isset($product->platform_price)) ? $product->platform_price : old('platform_price') }}">
                        </div>
                    </div>

                    <div class="mt-20 p-10 rounded-lg text-center" style="background: #1a1a1a; border: 1px solid #262626;">
                        <span class="text-gray font-12 d-block">Final Selling Price (Rounded)</span>
                        <span class="font-24 font-weight-bold text-gold" id="calculatedTotalPriceText">{{ $currency }}{{ ceil($initialTotal) }}</span>
                    </div>

                    <input type="hidden" name="cj_vid" value="{{ $product->cj_vid ?? request()->get('cj_vid') }}">
                    <input type="hidden" name="cj_price" id="cjBasePriceHidden" value="{{ $baseCjPrice }}">
                </div>
            @endif

            @if($product->isPhysical())
                    @if(empty($cjProduct))
                        <div class="form-group">
                            <label class="input-label">{{ trans('update.delivery_fee') }}</label>
                            <input type="number" name="delivery_fee" min="0" data-price-input
                                value="{{ (!empty($product) && isset($product->delivery_fee)) ? $product->delivery_fee : old('delivery_fee') }}"
                                class="form-control @error('delivery_fee') is-invalid @enderror">
                        </div>
                    @endif

                <div class="form-group">
                    <label class="input-label">{{ trans('update.delivery_estimated_time') }}</label>
                    <input type="number" name="delivery_estimated_time" min="0"
                           value="{{ $product->delivery_estimated_time ?? old('delivery_estimated_time') }}"
                           class="form-control">
                </div>
            @endif

           
            @if(empty($cjProduct))
                <div class="form-group">
                    <label class="input-label">Earning {{ trans('public.price') }} ({{ $currency }})</label>
                    <input id="computedEarningPrice" name="earning_price" type="text" class="form-control" value="{{ (!empty($product) && isset($product->earning_price)) ? $product->earning_price : old('earning_price', '0') }}">
                    <p class="font-12 text-gray mt-10">- Earning amount for this product.</p>
                </div>

                <div class="form-group">
                    <label class="input-label">Platform {{ trans('public.price') }} (10%)</label>
                    <input id="computedPlatformFee" name="own_platform_price" type="text" class="form-control" value="{{ (!empty($product) && isset($product->own_platform_price)) ? $product->own_platform_price : old('own_platform_price', '0') }}" readonly>
                    <p class="font-12 text-gray mt-10">- Platform fee is 10% of the earning price.</p>
                </div>
            @endif
            

             <div class="form-group">
                <label class="input-label">{{ trans('public.price') }} ({{ $currency }})</label>
                <input type="number" name="price" id="finalPriceInput" data-price-input
                       value="{{ (!empty($product) && isset($product->price)) ? $product->price : (!empty($cjProduct) ? ceil($cjPrice * 1.10) : old('price')) }}"
                       class="form-control @error('price') is-invalid @enderror">
                @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

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
            <label class="kemetic-label">{{ trans('public.category') }} <span class="text-danger">*</span></label>

            <select id="categories" class="kemetic-select select2 @error('category_id')  is-invalid @enderror" name="category_id" required>
                <option {{ (!empty($product) and !empty($product->category_id)) ? '' : 'selected' }} disabled>{{ trans('public.choose_category') }}</option>
                @foreach($productCategories as $productCategory)
                    @if(!empty($productCategory->subCategories) and $productCategory->subCategories->count() > 0)
                        <optgroup label="{{  $productCategory->title }}">
                            @foreach($productCategory->subCategories as $subCategory)
                                <option value="{{ $subCategory->id }}" {{ ((!empty($product) and $product->category_id == $subCategory->id) or old('category_id') == $subCategory->id) ? 'selected' : '' }}>{{ $subCategory->title }}</option>
                            @endforeach
                        </optgroup>
                    @else
                        <option value="{{ $productCategory->id }}" {{ ((!empty($product) and $product->category_id == $productCategory->id) or old('category_id') == $productCategory->id) ? 'selected' : '' }}>{{ $productCategory->title }}</option>
                    @endif
                @endforeach
            </select>
            @error('category_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <!-- <div class="form-group kemetic-form-group">
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
        </div> -->

        
    </div>

    <div class="col-12 mt-20">
        <div id="categoriesFiltersContainer"
             class="{{ empty($productCategoryFilters) ? 'd-none' : '' }}">

            <div class="k-card">
                <span class="input-label">{{ trans('public.category_filters') }}</span>

                <div class="row">
                    @if(!empty($productCategoryFilters) and count($productCategoryFilters))
                        @foreach($productCategoryFilters as $filter)
                            <div class="col-12 col-md-3 mt-20">
                                <div class="webinar-category-filters">
                                    <strong class="category-filter-title d-block">{{ $filter->title }}</strong>
                                    <div class="py-10"></div>

                                    @php
                                        $productFilterOptions = $product->selectedFilterOptions->pluck('filter_option_id')->toArray();

                                        if (!empty(old('filters'))) {
                                            $productFilterOptions = array_merge($productFilterOptions, old('filters'));
                                        }
                                    @endphp

                                    @foreach($filter->options as $option)
                                        <div class="form-group mt-10 d-flex align-items-center justify-content-between">
                                            <label class="cursor-pointer font-14 text-gray" for="filterOptions{{ $option->id }}">{{ $option->title }}</label>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="filters[]" value="{{ $option->id }}" {{ ((!empty($productFilterOptions) && in_array($option->id, $productFilterOptions)) ? 'checked' : '') }} class="custom-control-input" id="filterOptions{{ $option->id }}">
                                                <label class="custom-control-label" for="filterOptions{{ $option->id }}"></label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif
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
            @if(!empty($product->relatedCourses) and count($product->relatedCourses))
                <ul class="draggable-lists" data-order-table="relatedCourses">
                    @foreach($product->relatedCourses as $relatedCourseInfo)
                        @include('web.default.panel.store.products.create_includes.accordions.related_courses',['product' => $product,'relatedCourse' => $relatedCourseInfo])
                    @endforeach
                </ul>
            @else
                @include(getTemplate() . '.includes.no-result',[
                    'file_name' => 'comment.png',
                    'title' => trans('update.related_courses_no_result'),
                    'hint' => trans('update.related_courses_no_result_hint'),
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
    <script>
        $(document).ready(function() {
            const currency = '{{ $currency }}';
            const PLATFORM_FEE_PERCENTAGE = 0.10;

            @if(!empty($cjProduct))
                let currentCjPrice = {{ $baseCjPrice }};
                const $shippingInput    = $('#cjShippingPrice');
                const $earningInput     = $('#userMargin');
                const $platformFeeInput = $('#platformprice');
                const $finalPriceInput  = $('#finalPriceInput');
                const $subtotalSpan     = $('#pricingSubtotal');
                const $totalPriceSpan   = $('#calculatedTotalPriceText');
                const $basePriceDisplay = $('#cjBasePriceDisplay');
                const $basePriceHidden  = $('#cjBasePriceHidden');

                /**
                 * CJ Shipping — simple tier on ORIGINAL CJ price (no extra %):
                 *   base ₹0.01–₹10   → shipping ₹10
                 *   base ₹10.01–₹20  → shipping ₹20
                 *   base ₹20.01–₹30  → shipping ₹30  … etc.
                 */
                function calcCjAutoShipping(basePrice) {
                    if (basePrice <= 0) return 0;
                    return Math.ceil(basePrice / 10) * 10;
                }

                /** Display price = original + 20% VAT */
                function calcVatPrice(basePrice) {
                    return parseFloat((basePrice * 1.20).toFixed(2));
                }

                /** Update the VAT notice banner text */
                function updateVatNotice(originalPrice) {
                    var vatPrice = calcVatPrice(originalPrice);
                    $('#cjOriginalPriceLabel').text(currency + originalPrice.toFixed(2));
                    $('#cjVatPriceLabel').text(currency + vatPrice.toFixed(2));
                }

                function updateCjPricing() {
                    // VAT-adjusted base price (what we use in totals)
                    let vatBase  = calcVatPrice(currentCjPrice);
                    // Shipping: simple tier on ORIGINAL price
                    let shipping = calcCjAutoShipping(currentCjPrice);
                    $shippingInput.val(shipping);

                    let earning  = parseFloat($earningInput.val()) || 0;

                    // Main calculation: vatBase + shipping + earning
                    let totalCost  = vatBase + shipping + earning;
                    let finalTotal = totalCost > 0 ? Math.ceil(totalCost / (1 - PLATFORM_FEE_PERCENTAGE)) : 0;
                    let platformFee = Math.round(finalTotal * PLATFORM_FEE_PERCENTAGE);

                    $subtotalSpan.text(currency + totalCost.toFixed(2));
                    $totalPriceSpan.text(currency + finalTotal);
                    $finalPriceInput.val(finalTotal);
                    $platformFeeInput.val(platformFee);

                    // Update VAT display
                    $basePriceDisplay.text(currency + vatBase.toFixed(2));
                    updateVatNotice(currentCjPrice);

                    // Update all variant rows in the table
                    $('.variant-row').each(function() {
                        let base      = parseFloat($(this).data('base-price')) || 0;
                        let vVatBase  = calcVatPrice(base);
                        let vShipping = calcCjAutoShipping(base);
                        let vSubtotal = vVatBase + vShipping + earning;
                        let vFinal    = vSubtotal > 0 ? Math.ceil(vSubtotal / (1 - PLATFORM_FEE_PERCENTAGE)) : 0;
                        let vFee      = Math.round(vFinal * PLATFORM_FEE_PERCENTAGE);

                        $(this).find('.v-base-price').html(currency + vVatBase.toFixed(2));
                        $(this).find('.v-shipping-price').text(currency + vShipping.toFixed(2));
                        $(this).find('.v-earning-price').text(currency + earning.toFixed(2));
                        $(this).find('.v-fee-price').text(currency + vFee.toFixed(2));
                        $(this).find('.v-sell-price').text(currency + vFinal);
                    });
                }

                function updateEarningFromFinalPrice() {
                    let finalPrice = parseFloat($finalPriceInput.val()) || 0;
                    let vatBase    = calcVatPrice(currentCjPrice);
                    let shipping   = calcCjAutoShipping(currentCjPrice);
                    let baseCost   = vatBase + shipping;

                    if (finalPrice > 0) {
                        let platformFee = Math.round(finalPrice * PLATFORM_FEE_PERCENTAGE);
                        let earning     = Math.round(finalPrice - platformFee - baseCost);
                        if (earning >= 0) {
                            $earningInput.val(earning);
                        }
                        $platformFeeInput.val(platformFee);
                    }
                    updateCjPricing();
                }

                // When user clicks "Use" on a variant, update base price + recalc
                $('.use-variant-price').on('click', function() {
                    const price = parseFloat($(this).data('price'));
                    currentCjPrice = price;
                    $basePriceHidden.val(price);

                    $('.use-variant-price').closest('tr').removeClass('bg-gold-transparent');
                    $(this).closest('tr').addClass('bg-gold-transparent');

                    updateCjPricing();
                });

                // Earning input triggers recalc; shipping is auto-only (readonly)
                $earningInput.on('input', updateCjPricing);
                $finalPriceInput.on('input', updateEarningFromFinalPrice);

                if (!$earningInput.val() || $earningInput.val() === '0' || $earningInput.val() === '0.00') {
                    $earningInput.val(Math.round(currentCjPrice * 0.10));
                }
                updateCjPricing(); // run on page load
            @endif

            @if(empty($cjProduct))

                const $ncjEarningInput  = $('#computedEarningPrice');   // editable earning
                const $ncjDeliveryInput = $('input[name="delivery_fee"]');
                const $ncjPlatformInput = $('#computedPlatformFee');
                const $ncjPriceInput    = $('#finalPriceInput');

                function updateNonCjPricing() {
                    let earning     = parseFloat($ncjEarningInput.val())  || 0;
                    let delivery    = parseFloat($ncjDeliveryInput.val()) || 0;
                    
                    let baseTotal   = earning + delivery;
                    let platformFee = Math.round(baseTotal * PLATFORM_FEE_PERCENTAGE);
                    let total       = Math.ceil(baseTotal + platformFee);
                    
                    // Adjust platform fee to match the ceiled total, maintaining exact earning
                    platformFee = total - earning - delivery;

                    $ncjPlatformInput.val(Math.round(platformFee));
                    $ncjPriceInput.val(total);
                }

                function updateEarningFromPrice() {
                    let total       = parseFloat($ncjPriceInput.val()) || 0;
                    let delivery    = parseFloat($ncjDeliveryInput.val()) || 0;
                    
                    let baseTotal   = total / (1 + PLATFORM_FEE_PERCENTAGE);
                    let earning     = Math.round(baseTotal - delivery);
                    let platformFee = total - baseTotal;
                    
                    if (earning >= 0) {
                        $ncjEarningInput.val(earning);
                    } else {
                        $ncjEarningInput.val('0');
                    }
                    $ncjPlatformInput.val(Math.round(platformFee));
                }

                $ncjEarningInput.on('input', updateNonCjPricing);
                $ncjDeliveryInput.on('input', updateNonCjPricing);
                $ncjPriceInput.on('input', updateEarningFromPrice);
                
                // Initial update based on what is already filled
                if ($ncjPriceInput.val() && $ncjPriceInput.val() !== '0') {
                    updateEarningFromPrice();
                } else {
                    updateNonCjPricing();
                }
            @endif

            // Related Courses functionality for Products
            function kemeticRandomString() {
                var text = "";
                var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
                for (var i = 0; i < 4; i++) text += possible.charAt(Math.floor(Math.random() * possible.length));
                return text;
            }

            $('body').off('click', '#webinarAddRelatedCourses'); // prevent duplicates
            $('body').on('click', '#webinarAddRelatedCourses', function (e) {
                e.preventDefault();
                var key = kemeticRandomString();
                var add = $('#newRelatedCourseForm').html();
                add = add.replaceAll('record', key);
                // Dynamically replace the select class so we can target it 
                add = add.replaceAll('relatedCourses-select2', 'panel-search-webinar-select2-' + key);
                $('#relatedCoursesAccordion').prepend(add);

                // Initialize Select2 specifically for the cloned block
                initWebinarSelect($('.panel-search-webinar-select2-' + key));

                if (typeof feather !== 'undefined') feather.replace();
            });

            function initWebinarSelect($elements) {
                if ($elements.length) {
                    $elements.select2({
                        minimumInputLength: 3,
                        allowClear: true,
                        ajax: {
                            url: '/panel/webinars/search',
                            dataType: 'json',
                            type: "POST",
                            quietMillis: 50,
                            data: function (params) {
                                return {
                                    term: params.term,
                                };
                            },
                            processResults: function (data) {
                                return {
                                    results: $.map(data, function (item) {
                                        return {
                                            text: item.title,
                                            id: item.id
                                        }
                                    })
                                };
                            }
                        }
                    });
                }
            }

            // Initialize existing elements on page load
            initWebinarSelect($('.panel-search-webinar-select2'));

            $('body').off('click', '.js-save-related-course'); // prevent duplicates
            $('body').on('click', '.js-save-related-course', function (e) {
                e.preventDefault();
                var $this = $(this);
                var form = $this.closest('.related-course-form');
                
                // .related-course-form is a div, not a form tag, so standard .serialize() fails.
                // We need to target the actual input/select elements inside it.
                var data = form.find(':input').serialize();
                var action = form.attr('data-action');
                
                $this.addClass('loadingbar primary').prop('disabled', true);
                
                $.post(action, data, function (result) {
                    if (result && result.code === 200) {
                        Swal.fire({
                            icon: 'success',
                            html: '<h3 class="font-20 text-center text-dark-blue py-25">Saved successfully!</h3>',
                            showConfirmButton: false,
                            width: '25rem'
                        });
                        setTimeout(function () {
                            window.location.reload();
                        }, 500);
                    }
                }).fail(function (err) {
                    $this.removeClass('loadingbar primary').prop('disabled', false);
                    var errors = err.responseJSON;
                    if (errors && errors.errors) {
                        Object.keys(errors.errors).forEach(function (key) {
                            $.toast({
                                heading: 'Validation Error',
                                text: errors.errors[key][0],
                                bgColor: '#f63c3c',
                                textColor: 'white',
                                hideAfter: 5000,
                                position: 'bottom-right',
                                icon: 'error'
                            });
                        });
                    }
                });
            });

        });
    </script>
@endpush
