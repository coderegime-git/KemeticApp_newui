@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.css">


<style>
:root{
    --k-bg:#0b0b0b;
    --k-card:#141414;
    --k-border:#262626;
    --k-gold:#f2c94c;
    --k-text:#ededed;
    --k-muted:#9a9a9a;
    --k-radius:14px;
}

/* Card */
.kemetic-card{
    background:var(--k-card);
    border:1px solid var(--k-border);
    border-radius:var(--k-radius);
    padding:22px;
    height:100%;
}

/* Labels */
.kemetic-label{
    color:var(--k-gold);
    font-weight:600;
    font-size:13px;
    letter-spacing:.4px;
}

/* Inputs */
.kemetic-card .form-control,
.kemetic-card .custom-select{
    background:#0f0f0f;
    border:1px solid var(--k-border);
    color:var(--k-text);
    border-radius:10px;
    min-height: 48px;
}

.kemetic-card .form-control::placeholder{
    color:var(--k-muted);
}

.kemetic-card .form-control:focus,
.kemetic-card .custom-select:focus{
    border-color:var(--k-gold);
    box-shadow:0 0 0 1px rgba(242,201,76,.35);
}

/* Switch */
/* .custom-switch .custom-control-input:checked ~ .custom-control-label::before{
    background:var(--k-gold);
    border-color:var(--k-gold);
} */

/* Tags */
.bootstrap-tagsinput{
    background:#f2f2f2;
    border:1px solid var(--k-border);
    border-radius:10px;
}
.bootstrap-tagsinput .tag{
    background:var(--k-gold);
    color:#000;
    border-radius:6px;
}

/* Category Filters */
.kemetic-filter{
    background:#0f0f0f;
    border:1px solid var(--k-border);
    border-radius:12px;
    padding:16px;
    height:100%;
}
.kemetic-filter-title{
    color:var(--k-gold);
    font-size:14px;
    font-weight:600;
}
.kemetic-hint{
    color:var(--k-muted);
    font-size:12px;
}

/* .custom-control-input:checked ~ .custom-control-label::before {
    background-color: var(--kemetic-gold) !important;
    border-color: var(--kemetic-gold) !important;
    box-shadow: 0 0 10px rgba(242, 201, 76, 0.45);
} */

.custom-control-label::before {
    border-radius: 20px !important;
    background: #1c1c1c;
    border: 1px solid var(--kemetic-border);
}

/* SWITCH CIRCLE */
.custom-control-label::after {
    border-radius: 50% !important;
}

/* CATEGORY FILTER BLOCK */
.webinar-category-filters {
    background: #1a1a1a;
    padding: 14px;
    border-radius: var(--kemetic-radius);
    border: 1px solid var(--kemetic-border);
}

.category-filter-title {
    color: var(--kemetic-gold);
    font-size: 15px;
    font-weight: bold;
}

/* CHECKBOX */
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
  border-color: #f2c94c;
  background-color: #f2c94c;
    box-shadow: 0 0 10px rgba(242, 201, 76, 0.45);
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
  padding-left: 3.125rem;
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
</style>
@endpush

<div class="row">
    <div class="col-12 col-md-6 mt-15">
        <div class="kemetic-card">

            {{-- Certificate --}}
            @if(!empty(getCertificateMainSettings("status")))
                <div class="form-group">
                    <div class="d-flex align-items-center justify-content-between">
                        <label class="kemetic-label mb-0" for="certificateSwitch">
                            {{ trans('update.bundle_completion_certificate') }}
                        </label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox"
                                   name="certificate"
                                   class="custom-control-input"
                                   id="certificateSwitch"
                                   {{ ((!empty($bundle) && $bundle->certificate) || old('certificate') == 'on') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="certificateSwitch"></label>
                        </div>
                    </div>
                    <p class="kemetic-hint mt-10">
                        {{ trans('update.bundle_completion_certificate_hint') }}
                    </p>
                </div>
            @endif

            {{-- Tags --}}
            <div class="form-group mt-20">
                <label class="kemetic-label">{{ trans('public.tags') }}</label>
                <input type="text"
                       name="tags"
                       data-max-tag="5"
                       value="{{ !empty($bundle) ? implode(',',$bundleTags) : '' }}"
                       class="form-control inputtags"
                       placeholder="{{ trans('public.type_tag_name_and_press_enter') }} (Max : 5)">
            </div>

            {{-- Category --}}
            <div class="form-group mt-20">
                <label class="kemetic-label">{{ trans('public.category') }}</label>
                <select id="categories"
                        name="category_id"
                        class="custom-select @error('category_id') is-invalid @enderror"
                        required>
                    <option disabled {{ empty($bundle) ? 'selected' : '' }}>
                        {{ trans('public.choose_category') }}
                    </option>

                    @foreach($categories as $category)
                        @if(!empty($category->subCategories) && $category->subCategories->count())
                            <optgroup label="{{ $category->title }}">
                                @foreach($category->subCategories as $subCategory)
                                    <option value="{{ $subCategory->id }}"
                                        {{ ((!empty($bundle) && $bundle->category_id == $subCategory->id) || old('category_id') == $subCategory->id) ? 'selected' : '' }}>
                                        {{ $subCategory->title }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @else
                            <option value="{{ $category->id }}"
                                {{ ((!empty($bundle) && $bundle->category_id == $category->id) || old('category_id') == $category->id) ? 'selected' : '' }}>
                                {{ $category->title }}
                            </option>
                        @endif
                    @endforeach
                </select>

                @error('category_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>
    </div>
</div>


<div class="form-group mt-25 {{ (!empty($bundleCategoryFilters) && count($bundleCategoryFilters)) ? '' : 'd-none' }}"
     id="categoriesFiltersContainer">

    <span class="kemetic-label d-block">{{ trans('public.category_filters') }}</span>

    <div id="categoriesFiltersCard" class="row mt-20">
        @foreach($bundleCategoryFilters ?? [] as $filter)
            <div class="col-12 col-md-3">
                <div class="kemetic-filter">

                    <span class="kemetic-filter-title d-block">
                        {{ $filter->title }}
                    </span>

                    @php
                        $bundleFilterOptions = $bundle->filterOptions->pluck('filter_option_id')->toArray();
                        if (!empty(old('filters'))) {
                            $bundleFilterOptions = array_merge($bundleFilterOptions, old('filters'));
                        }
                    @endphp

                    @foreach($filter->options as $option)
                        <div class="form-group mt-15 d-flex align-items-center justify-content-between">
                            <label class="cursor-pointer font-13 text-gray mb-0"
                                   for="filterOptions{{ $option->id }}">
                                {{ $option->title }}
                            </label>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                       name="filters[]"
                                       value="{{ $option->id }}"
                                       class="custom-control-input"
                                       id="filterOptions{{ $option->id }}"
                                       {{ in_array($option->id, $bundleFilterOptions ?? []) ? 'checked' : '' }}>
                                <label class="custom-control-label"
                                       for="filterOptions{{ $option->id }}"></label>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        @endforeach
    </div>
</div>


@push('scripts_bottom')
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
@endpush
