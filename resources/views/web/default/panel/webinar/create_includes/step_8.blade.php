
<style>
    :root {
        --k-bg: #0f0f0f;
        --k-card: #181818;
        --k-border: #2a2a2a;
        --k-gold: #f2c94c;
        --k-gold-soft: rgba(242,201,76,0.15);
        --k-text: #e6e6e6;
        --k-radius: 14px;
    }

    /* Title */
    .kemetic-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--k-gold);
        margin-bottom: 18px;
        border-left: 4px solid var(--k-gold);
        padding-left: 12px;
    }

    /* Textarea */
    .kemetic-textarea {
        background: var(--k-card);
        border: 1px solid var(--k-border);
        color: var(--k-text);
        width: 100%;
        border-radius: var(--k-radius);
        padding: 14px;
        font-size: 15px;
        min-height: 160px;
        resize: vertical;
        transition: 0.2s;
    }

    .kemetic-textarea:focus {
        border-color: var(--k-gold);
        outline: none;
        box-shadow: 0 0 0 3px var(--k-gold-soft);
    }

    /* Switch */
    .kemetic-switch {
        position: relative;
        display: inline-block;
        width: 52px;
        height: 26px;
    }

    .kemetic-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .kemetic-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: #333;
        transition: .3s;
        border-radius: 34px;
    }

    .kemetic-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: var(--k-text);
        transition: .3s;
        border-radius: 50%;
    }

    .kemetic-switch input:checked + .kemetic-slider {
        background-color: var(--k-gold);
    }

    .kemetic-switch input:checked + .kemetic-slider:before {
        transform: translateX(26px);
        background: #000;
    }

    /* Error message */
    .kemetic-error {
        color: #ff6b6b;
        font-size: 14px;
        margin-top: 6px;
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
  border-color: #43d477;
  background-color: #43d477;
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

<section class="mt-40">

   <h2 class="section-title after-line">{{ trans('public.message_to_reviewer') }}</h2>
    <div class="row">
        <div class="col-12">
            <div class="form-group mt-15">
                <textarea name="message_for_reviewer" rows="10" class="form-control">{{ (!empty($webinar) and $webinar->message_for_reviewer) ? $webinar->message_for_reviewer : old('message_for_reviewer') }}</textarea>
            </div>
        </div>
    </div>

    <!-- Switch Row -->
    <div class="row mt-25" style="margin-top: 10px;">
        <div class="col-12 col-md-4">

            <label class="d-flex align-items-center justify-content-between">

                <span style="color: var(--k-text); font-size: 15px; font-weight: 600;">
                    {{ trans('public.agree_rules') }}
                </span>

                <!-- Kemetic Switch -->
                <label class="kemetic-switch">
                    <input type="checkbox" name="rules" id="rulesSwitch">
                    <span class="kemetic-slider"></span>
                </label>

            </label>

            @error('rules')
                <div class="kemetic-error">{{ $message }}</div>
            @enderror

        </div>
    </div>

</section>


@push('scripts_bottom')
@endpush
