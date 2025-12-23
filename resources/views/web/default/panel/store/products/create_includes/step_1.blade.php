@push('styles_top')
<link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">

<style>
/* Kemetic form */
.k-form-card{
    background:#141414;
    border:1px solid #262626;
    border-radius:18px;
    padding:22px;
}

/* Labels */
.k-form-card .input-label{
    color:#d4af37;
    font-weight:600;
    font-size:13px;
    letter-spacing:.3px;
}

/* Inputs */
.k-form-card .form-control,
.k-form-card .custom-select{
    background:#0b0b0b;
    border:1px solid #2a2a2a;
    color:#eaeaea;
    border-radius:12px;
}
.k-form-card .form-control:focus,
.k-form-card .custom-select:focus{
    border-color:#d4af37;
    box-shadow:0 0 0 2px rgba(212,175,55,.15);
}

/* Textarea */
.k-form-card textarea{
    resize:vertical;
}

/* Help text */
.k-help{
    color:#9ca3af;
    font-size:12px;
}


/* Summernote */
.note-editor{
    background:#0b0b0b;
    border:1px solid #2a2a2a;
    border-radius:12px;
}
.note-toolbar{
    background:#141414;
    border-bottom:1px solid #262626;
}
.note-editable{
    background:#0b0b0b;
    color:#eaeaea;
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
/* ===================== KEMETIC FORM DESIGN ===================== */

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
    <div class="col-12 col-md-4 mt-20">

        <div class="kemetic-card">

            {{-- LANGUAGE --}}
            @if(!empty(getGeneralSettings('content_translate')))
                <div class="form-group kemetic-form-group">
                    <label class="kemetic-label">{{ trans('auth.language') }}</label>

                    <select name="locale"
                            class="kemetic-select {{ !empty($product) ? 'js-edit-content-locale' : '' }}">
                        @foreach(getUserLanguagesLists() as $lang => $language)
                            <option value="{{ $lang }}"
                                @if(mb_strtolower(request()->get('locale', app()->getLocale())) == mb_strtolower($lang)) selected @endif>
                                {{ $language }}
                                {{ (!empty($definedLanguage) && is_array($definedLanguage) && in_array(mb_strtolower($lang), $definedLanguage)) ? '(' . trans('public.content_defined') . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @else
                <input type="hidden" name="locale" value="{{ getDefaultLocale() }}">
            @endif

            {{-- TYPE --}}
            <div class="form-group kemetic-form-group">
                <label class="kemetic-label">{{ trans('public.type') }}</label>

                <select name="type"
                        class="kemetic-select @error('type') is-invalid @enderror">
                    @if(!empty(getStoreSettings('possibility_create_physical_product')))
                        <option value="physical" @if(!empty($product) && $product->isPhysical()) selected @endif>
                            {{ trans('update.physical') }}
                        </option>
                    @endif

                    @if(!empty(getStoreSettings('possibility_create_virtual_product')))
                        <option value="virtual" @if(!empty($product) && $product->isVirtual()) selected @endif>
                            {{ trans('update.virtual') }}
                        </option>
                    @endif
                </select>

                @error('type')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            {{-- TITLE --}}
            <div class="form-group kemetic-form-group">
                <label class="kemetic-label">{{ trans('public.title') }}</label>

                <input type="text"
                       name="title"
                       value="{{ (!empty($product) && !empty($product->translate($locale))) ? $product->translate($locale)->title : old('title') }}"
                       class="kemetic-input @error('title') is-invalid @enderror">

                @error('title')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            {{-- SEO DESCRIPTION --}}
            <div class="form-group kemetic-form-group">
                <label class="kemetic-label">{{ trans('public.seo_description') }}</label>

                <input type="text"
                       name="seo_description"
                       value="{{ (!empty($product) && !empty($product->translate($locale))) ? $product->translate($locale)->seo_description : old('seo_description') }}"
                       class="kemetic-input @error('seo_description') is-invalid @enderror"
                       placeholder="{{ trans('forms.50_160_characters_preferred') }}">

                @error('seo_description')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            {{-- SUMMARY --}}
            <div class="form-group kemetic-form-group">
                <label class="kemetic-label">{{ trans('public.summary') }}</label>

                <textarea name="summary"
                          rows="5"
                          class="kemetic-textarea @error('summary') is-invalid @enderror"
                          placeholder="{{ trans('update.product_summary_placeholder') }}">{{ (!empty($product) && !empty($product->translate($locale))) ? $product->translate($locale)->summary : old('summary') }}</textarea>

                @error('summary')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

        </div>
    </div>
</div>


{{-- Description --}}
<div class="row mt-20">
    <div class="col-12">
        <div class="k-form-card">
            <label class="input-label">{{ trans('public.description') }}</label>
            <textarea id="summernote" name="description"
                      class="form-control @error('description') is-invalid @enderror">
                {!! (!empty($product) && !empty($product->translate($locale))) ? $product->translate($locale)->description : old('description') !!}
            </textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

{{-- Ordering --}}
<div class="row mt-20">
    <div class="col-6">
        <div class="k-form-card">
            <div class="form-group d-flex align-items-center k-switch">
                <label class="input-label mb-0 cursor-pointer" for="orderingSwitch">
                    {{ trans('update.enable_ordering') }}
                </label>
                <div class="ml-30 custom-control custom-switch">
                    <input type="checkbox" name="ordering" class="custom-control-input"
                           id="orderingSwitch" {{ (!empty($product) && $product->ordering) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="orderingSwitch"></label>
                </div>
            </div>
            <p class="k-help">{{ trans('update.create_product_enable_ordering_hint') }}</p>
        </div>
    </div>
</div>



@push('scripts_bottom')
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>

    @push('scripts_bottom')
        <script>
            var videoDemoPathPlaceHolderBySource = {
                upload: '{{ trans('update.file_source_upload_placeholder') }}',
                youtube: '{{ trans('update.file_source_youtube_placeholder') }}',
                vimeo: '{{ trans('update.file_source_vimeo_placeholder') }}',
                external_link: '{{ trans('update.file_source_external_link_placeholder') }}',
            }
        </script>
    @endpush
@endpush
