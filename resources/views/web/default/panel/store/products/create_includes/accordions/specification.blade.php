<style>
    /* ACCORDION */
.kemetic-accordion-item {
    background: #0c0c0c;
    border: 1px solid rgba(242,201,76,.2);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 15px 40px rgba(0,0,0,.6);
}

.kemetic-accordion-header {
    padding: 16px 20px;
    background: linear-gradient(135deg,#111,#0a0a0a);
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
}

.kemetic-accordion-title {
    font-weight: 700;
    color: #F2C94C;
}

.kemetic-icon-box {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: rgba(242,201,76,.15);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #F2C94C;
}

.kemetic-chevron {
    color: #F2C94C;
    transition: transform .3s ease;
}

.collapse.show ~ .kemetic-chevron {
    transform: rotate(180deg);
}

/* BODY */
.kemetic-accordion-body {
    padding: 20px;
    background: #0f0f0f;
}

/* FORM */
.kemetic-label {
    color: #F2C94C;
    font-weight: 600;
    font-size: 13px;
}

.kemetic-select,
.kemetic-textarea {
    width: 100%;
    background: #111;
    border: 1px solid rgba(242,201,76,.25);
    color: #fff;
    padding: 10px 14px;
    border-radius: 10px;
}

/* BUTTONS */
.kemetic-btn-primary {
    background: linear-gradient(135deg,#F2C94C,#d4a017);
    border: none;
    color: #000;
    padding: 10px 22px;
    border-radius: 10px;
    font-weight: 600;
}

.kemetic-btn-danger {
    background: #ff4d4f;
    border: none;
    color: #fff;
    padding: 10px 22px;
    border-radius: 10px;
}

/* ICON ACTIONS */
.kemetic-icon-btn-danger {
    width: 34px;
    height: 34px;
    background: rgba(255,77,79,.15);
    color: #ff4d4f;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* BADGE */
.kemetic-badge-warning {
    background: rgba(255,193,7,.15);
    color: #ffc107;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 12px;
}

/* Allow-selection toggle label */
.kemetic-toggle-label {
    color: #ccc;
    font-size: 13px;
    font-weight: 500;
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
  box-shadow: 0 0 10px rgba(242, 201, 76, 0.45);
}
.custom-control-input:focus ~ .custom-control-label::before {
  box-shadow: none;
}
.custom-control-input:focus:not(:checked) ~ .custom-control-label::before {
  border-color: #43d477;
}
.custom-control-input:not(:disabled):active ~ .custom-control-label::before {
  color: #ffffff;
  background-color: #43d477;
  border-color: #43d477;
}
.custom-control-input[disabled] ~ .custom-control-label,
.custom-control-input:disabled ~ .custom-control-label {
  color: #6c757d;
}
.custom-control-input[disabled] ~ .custom-control-label::before,
.custom-control-input:disabled ~ .custom-control-label::before {
  background-color: #1a1a1a;
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
  background-color: #1e1e1e;
  border: 2px solid #444;
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
}
.custom-checkbox .custom-control-input:indeterminate ~ .custom-control-label::after {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='4' height='4' viewBox='0 0 4 4'%3e%3cpath stroke='%23ffffff' d='M0 2h4'/%3e%3c/svg%3e");
}
.custom-checkbox .custom-control-input:disabled:checked ~ .custom-control-label::before {
  background-color: rgba(242,201,76,0.4);
}
.custom-checkbox .custom-control-input:disabled:indeterminate ~ .custom-control-label::before {
  background-color: rgba(242,201,76,0.4);
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
  background-color: #555;
  border-radius: 0.75rem;
  transition: transform 0.15s ease-in-out, background-color 0.15s ease-in-out,
              border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
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
  background-color: rgba(242,201,76,0.4);
}


</style>

<li data-id="{{ !empty($selectedSpecification) ? $selectedSpecification->id :'' }}"
    class="accordion-row kemetic-accordion-item mt-25">

    {{-- ================= HEADER ================= --}}
    <div class="kemetic-accordion-header"
         role="tab"
         id="specification_{{ !empty($selectedSpecification) ? $selectedSpecification->id :'record' }}"
         data-toggle="collapse"
         href="#collapseSpecification{{ !empty($selectedSpecification) ? $selectedSpecification->id :'record' }}"
         aria-controls="collapseSpecification{{ !empty($selectedSpecification) ? $selectedSpecification->id :'record' }}"
         data-parent="#specificationsAccordion"
         aria-expanded="true">

        <div class="d-flex align-items-center">
            <span class="kemetic-icon-box mr-12">
                <i data-feather="file"></i>
            </span>

            <span class="kemetic-accordion-title">
                {{ !empty($selectedSpecification)
                    ? $selectedSpecification->specification->title
                    : trans('update.add_new_specification') }}
            </span>
        </div>

        <div class="d-flex align-items-center">

            @if(!empty($selectedSpecification) && $selectedSpecification->status != 'active')
                <span class="kemetic-badge-warning mr-10">
                    {{ trans('public.disabled') }}
                </span>
            @endif

            <i data-feather="move"
               class="kemetic-drag-icon mr-12 cursor-pointer"
               height="20"></i>

            @if(!empty($selectedSpecification))
                <a href="/panel/store/products/specifications/{{ $selectedSpecification->id }}/delete"
                   class="kemetic-icon-btn-danger delete-action mr-10">
                    <i data-feather="trash-2" height="20"></i>
                </a>
            @endif

            <i data-feather="chevron-down"
               class="kemetic-chevron"
               height="20"
               href="#collapseSpecification{{ !empty($selectedSpecification) ? $selectedSpecification->id :'record' }}"
               aria-controls="collapseSpecification{{ !empty($selectedSpecification) ? $selectedSpecification->id :'record' }}"
               data-parent="#specificationsAccordion"
               role="button"
               data-toggle="collapse"
               aria-expanded="true"></i>
        </div>
    </div>

    {{-- ================= BODY ================= --}}
    <div id="collapseSpecification{{ !empty($selectedSpecification) ? $selectedSpecification->id :'record' }}"
         aria-labelledby="specification_{{ !empty($selectedSpecification) ? $selectedSpecification->id :'record' }}"
         class="collapse @if(empty($selectedSpecification)) show @endif"
         role="tabpanel">

        <div class="kemetic-accordion-body">

            <div class="js-content-form specification-form"
                 data-action="/panel/store/products/specifications/{{ !empty($selectedSpecification) ? $selectedSpecification->id . '/update' : 'store' }}">

                <input type="hidden"
                       name="ajax[{{ !empty($selectedSpecification) ? $selectedSpecification->id : 'new' }}][product_id]"
                       value="{{ !empty($product) ? $product->id :'' }}">

                <input type="hidden"
                       class="js-input-type"
                       name="ajax[{{ !empty($selectedSpecification) ? $selectedSpecification->id : 'new' }}][input_type]"
                       value="{{ !empty($selectedSpecification) ? $selectedSpecification->type :'' }}">

                <div class="row">
                    <div class="col-12 col-lg-6">

                        {{-- ── LANGUAGE ── --}}
                        @if(!empty(getGeneralSettings('content_translate')))
                            <div class="form-group kemetic-form-group">
                                <label class="kemetic-label">{{ trans('auth.language') }}</label>

                                <select name="ajax[{{ !empty($selectedSpecification) ? $selectedSpecification->id : 'new' }}][locale]"
                                        class="kemetic-select {{ !empty($selectedSpecification) ? 'js-product-content-locale' : '' }}"
                                        data-product-id="{{ !empty($product) ? $product->id : '' }}"
                                        data-id="{{ !empty($selectedSpecification) ? $selectedSpecification->id : '' }}"
                                        data-relation="selectedSpecifications"
                                        data-fields="value">
                                    @foreach(getUserLanguagesLists() as $lang => $language)
                                        <option value="{{ $lang }}"
                                            {{ (!empty($selectedSpecification) and !empty($selectedSpecification->value) and !empty($selectedSpecification->locale)) ? (mb_strtolower($selectedSpecification->locale) == mb_strtolower($lang) ? 'selected' : '') : ($locale == $lang ? 'selected' : '') }}>
                                            {{ $language }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            {{-- ── Hidden locale fallback (restored from original) ── --}}
                            <input type="hidden"
                                   name="ajax[{{ !empty($selectedSpecification) ? $selectedSpecification->id : 'new' }}][locale]"
                                   value="{{ $defaultLocale }}">
                        @endif

                        {{-- ── SPECIFICATION SELECT ── --}}
                        <div class="form-group kemetic-form-group mt-15">
                            <label class="kemetic-label d-block">{{ trans('update.specification') }}</label>

                            <select name="ajax[{{ !empty($selectedSpecification) ? $selectedSpecification->id : 'new' }}][specification_id]"
                                    class="kemetic-select js-ajax-specification_id {{ !empty($selectedSpecification) ? '' : 'specification-select2' }}"
                                    data-placeholder="{{ trans('update.search_and_select_specifications') }}"
                                    data-allow-clear="false"
                                    data-category="{{ !empty($product) ? $product->category_id : '' }}"
                                    {{ !empty($selectedSpecification) ? 'disabled' : '' }}>

                                @if(!empty($productSpecifications))
                                    <option value="">{{ trans('update.search_and_select_specifications') }}</option>
                                    @foreach($productSpecifications as $productSpecification)
                                        <option value="{{ $productSpecification->id }}"
                                            {{ (!empty($selectedSpecification) and $selectedSpecification->product_specification_id == $productSpecification->id) ? 'selected' : '' }}>
                                            {{ $productSpecification->title }}
                                        </option>
                                    @endforeach

                                @elseif(!empty($selectedSpecification))
                                    {{-- ── Restored: fallback option when no productSpecifications list ── --}}
                                    <option value="{{ $selectedSpecification->specification->id }}" selected>
                                        {{ $selectedSpecification->specification->title }}
                                    </option>
                                @endif
                            </select>

                            <div class="invalid-feedback"></div>

                            {{-- ── Restored: hidden specification_id input when editing ── --}}
                            @if(!empty($selectedSpecification))
                                <input type="hidden"
                                       name="ajax[{{ $selectedSpecification->id }}][specification_id]"
                                       value="{{ $selectedSpecification->specification->id }}">
                            @endif
                        </div>

                        {{-- ── MULTI VALUES ── --}}
                        <div class="form-group kemetic-form-group js-multi-values-input {{ (!empty($selectedSpecification) and $selectedSpecification->type == 'multi_value') ? '' : 'd-none' }}">
                            <label class="kemetic-label d-block">{{ trans('update.parameters') }}</label>

                            @php
                                $selectedMultiValues = [];
                                if (!empty($selectedSpecification)) {
                                    $selectedMultiValues = $selectedSpecification->selectedMultiValues->pluck('specification_multi_value_id')->toArray();
                                }
                            @endphp

                            {{-- ── Restored: full select2 classes & data attributes ── --}}
                            <select name="ajax[{{ !empty($selectedSpecification) ? $selectedSpecification->id : 'new' }}][multi_values][]"
                                    class="kemetic-select js-ajax-multi_values {{ !empty($selectedSpecification) ? 'select-multi-values-select2' : 'multi_values-select' }}"
                                    multiple
                                    data-placeholder="{{ trans('update.select_specification_params') }}"
                                    data-allow-clear="false"
                                    data-search="false">

                                @if(!empty($selectedSpecification->specification) and !empty($selectedSpecification->specification->multiValues))
                                    @foreach($selectedSpecification->specification->multiValues as $multiValue)
                                        <option value="{{ $multiValue->id }}"
                                            {{ in_array($multiValue->id, $selectedMultiValues) ? 'selected' : '' }}>
                                            {{ $multiValue->title }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>

                            <div class="invalid-feedback"></div>
                        </div>

                        {{-- ── SUMMARY (textarea) ── --}}
                        <div class="form-group kemetic-form-group js-summery-input {{ (!empty($selectedSpecification) and $selectedSpecification->type == 'textarea') ? '' : 'd-none' }}">
                            <label class="kemetic-label d-block">{{ trans('update.product_summary') }}</label>
                            <textarea name="ajax[{{ !empty($selectedSpecification) ? $selectedSpecification->id : 'new' }}][summary]"
                                      rows="4"
                                      class="kemetic-textarea js-ajax-summary">{{ (!empty($selectedSpecification) and $selectedSpecification->type == 'textarea') ? $selectedSpecification->value : '' }}</textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        {{-- ── ALLOW USER SELECTION (restored) ── --}}
                        <div class="form-group kemetic-form-group mt-20 js-allow-selection-input {{ (!empty($selectedSpecification) and $selectedSpecification->type == 'multi_value') ? '' : 'd-none' }}">
                            <div class="d-flex align-items-center justify-content-between">
                                <label class="kemetic-label cursor-pointer"
                                       for="specificationAllowSelectionSwitch{{ !empty($selectedSpecification) ? $selectedSpecification->id : '_record' }}">
                                    {{ trans('update.allow_user_selection') }}
                                </label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox"
                                           name="ajax[{{ !empty($selectedSpecification) ? $selectedSpecification->id : 'new' }}][allow_selection]"
                                           class="custom-control-input"
                                           id="specificationAllowSelectionSwitch{{ !empty($selectedSpecification) ? $selectedSpecification->id : '_record' }}"
                                           {{ (!empty($selectedSpecification) and $selectedSpecification->allow_selection) ? 'checked' : '' }}>
                                    <label class="custom-control-label"
                                           for="specificationAllowSelectionSwitch{{ !empty($selectedSpecification) ? $selectedSpecification->id : '_record' }}"></label>
                                </div>
                            </div>
                        </div>

                        {{-- ── ACTIVE STATUS ── --}}
                        <div class="form-group kemetic-form-group mt-20">
                            <div class="d-flex align-items-center justify-content-between">
                                <label class="kemetic-label cursor-pointer"
                                       for="specificationStatusSwitch{{ !empty($selectedSpecification) ? $selectedSpecification->id : '_record' }}">
                                    {{ trans('public.active') }}
                                </label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox"
                                           name="ajax[{{ !empty($selectedSpecification) ? $selectedSpecification->id : 'new' }}][status]"
                                           class="custom-control-input"
                                           id="specificationStatusSwitch{{ !empty($selectedSpecification) ? $selectedSpecification->id : '_record' }}"
                                           {{ (empty($selectedSpecification) or $selectedSpecification->status == \App\Models\ProductSelectedSpecification::$Active) ? 'checked' : '' }}>
                                    <label class="custom-control-label"
                                           for="specificationStatusSwitch{{ !empty($selectedSpecification) ? $selectedSpecification->id : '_record' }}"></label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ── ACTIONS ── --}}
                <div class="mt-30 d-flex align-items-center">
                    <button type="button"
                            class="js-save-specification kemetic-btn-primary">
                        {{ trans('public.save') }}
                    </button>

                    @if(empty($selectedSpecification))
                        <button type="button"
                                class="kemetic-btn-danger ml-10 cancel-accordion">
                            {{ trans('public.close') }}
                        </button>
                    @endif
                </div>

            </div>
        </div>
    </div>
</li>