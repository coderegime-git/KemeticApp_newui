@push('styles_top')
<style>
/* ===============================
   KEMETIC VARIABLES
================================ */
:root {
    --k-black: #0b0b0b;
    --k-dark: #141414;
    --k-gold: #f2c94c;
    --k-gold-soft: rgba(242, 201, 76, 0.15);
    --k-border: rgba(242, 201, 76, 0.25);
    --k-radius: 14px;
}

/* ===============================
   SECTION WRAPPER
================================ */
.kemetic-review-section {
    background: var(--k-black);
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius);
    padding: 24px;
    margin-top: 20px;
}

/* ===============================
   SECTION TITLE
================================ */
.kemetic-title {
    color: var(--k-gold);
    font-size: 18px;
    font-weight: 700;
    position: relative;
    padding-left: 14px;
    margin-bottom: 18px;
}

.kemetic-title::before {
    content: "";
    position: absolute;
    left: 0;
    top: 2px;
    width: 4px;
    height: 18px;
    background: var(--k-gold);
    border-radius: 4px;
}

/* ===============================
   TEXTAREA
================================ */
.kemetic-textarea {
    background: var(--k-dark);
    border: 1px solid var(--k-border);
    border-radius: 12px;
    color: #fff;
    padding: 14px;
    resize: vertical;
    transition: .25s ease;
}

.kemetic-textarea:focus {
    background: #1a1a1a;
    border-color: var(--k-gold);
    box-shadow: 0 0 0 2px var(--k-gold-soft);
    color: #fff;
}

/* ===============================
   SWITCH ROW
================================ */
.kemetic-switch-row {
    background: var(--k-dark);
    border: 1px solid var(--k-border);
    border-radius: 12px;
    padding: 14px 16px;
    margin-top: 12px;
}

/* Switch label */
.kemetic-switch-row label {
    color: #ddd;
    font-weight: 600;
    margin-bottom: 0;
}

/* Bootstrap switch tweak */

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

/* Error text */
.kemetic-error {
    color: #ff6b6b;
    font-size: 13px;
    margin-top: 8px;
}
</style>
@endpush


{{-- ===============================
   MESSAGE TO REVIEWER SECTION
================================ --}}
<section class="kemetic-review-section">
    <h2 class="kemetic-title">
        {{ trans('public.message_to_reviewer') }}
    </h2>

    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <textarea
                    name="message_for_reviewer"
                    rows="8"
                    class="form-control kemetic-textarea"
                    placeholder="{{ trans('public.message_to_reviewer') }}"
                >{{ (!empty($bundle) && $bundle->message_for_reviewer) ? $bundle->message_for_reviewer : old('message_for_reviewer') }}</textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-5">
            <div class="kemetic-switch-row d-flex align-items-center justify-content-between">
                <label class="cursor-pointer" for="rulesSwitch">
                    {{ trans('public.agree_rules') }}
                </label>

                <div class="custom-control custom-switch">
                    <input
                        type="checkbox"
                        name="rules"
                        class="custom-control-input"
                        id="rulesSwitch"
                    >
                    <label class="custom-control-label" for="rulesSwitch"></label>
                </div>
            </div>

            @error('rules')
                <div class="kemetic-error">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
</section>


@push('scripts_bottom')
@endpush
