@push('styles_top')
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

/* Card wrapper */
.k-card{
    background: var(--k-card);
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius);
    padding: 24px;
}

/* Section title */
.k-title{
    color: var(--k-gold);
    font-weight: 600;
    margin-bottom: 16px;
}

/* Textarea */
.k-textarea{
    background: #0f0f0f;
    border: 1px solid var(--k-border);
    color: var(--k-text);
    border-radius: 12px;
    padding: 12px;
    width: 100%;
    resize: vertical;
}

/* Switch */
.k-switch .custom-control-label::before {
    background-color: var(--k-border);
}
.k-switch .custom-control-input:checked ~ .custom-control-label::before {
    background-color: var(--k-gold);
}
.k-switch .custom-control-label {
    color: var(--k-text);
}

/* Error */
.k-error{
    color: #ff4c4c;
    margin-top: 8px;
    font-size: 13px;
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
</style>
@endpush

<section class="mt-20 k-card">

    <h2 class="k-title">{{ trans('public.message_to_reviewer') }}</h2>

    <div class="row">
        <div class="col-12">
            <div class="form-group mt-15">
                <textarea name="message_for_reviewer"
                          rows="10"
                          class="k-textarea">{{ (!empty($product) and $product->message_for_reviewer) ? $product->message_for_reviewer : old('message_for_reviewer') }}</textarea>
            </div>
        </div>
    </div>

    <div class="row mt-20">
        <div class="col-12 col-md-4">
            <div class="form-group k-switch mt-10">
                <div class="d-flex align-items-center justify-content-between">
                    <label class="cursor-pointer input-label" for="rulesSwitch">{{ trans('public.agree_rules') }}</label>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="rules" class="custom-control-input" id="rulesSwitch">
                        <label class="custom-control-label" for="rulesSwitch"></label>
                    </div>
                </div>

                @error('rules')
                <div class="k-error">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
    </div>

</section>

@push('scripts_bottom')
@endpush
