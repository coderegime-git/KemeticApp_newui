@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
<link href="/assets/default/vendors/sortable/jquery-ui.min.css"/>

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
}

/* Titles */
.kemetic-title{
    color:var(--k-gold);
    font-weight:700;
    letter-spacing:.5px;
}

/* Labels */
.kemetic-label{
    color:var(--k-gold);
    font-size:13px;
    font-weight:600;
}

/* Inputs */
.kemetic-card .form-control{
    background:#0f0f0f;
    border:1px solid var(--k-border);
    color:var(--k-text);
    border-radius:10px;
}

.kemetic-card .form-control::placeholder{
    color:var(--k-muted);
}

.kemetic-card .form-control:focus{
    border-color:var(--k-gold);
    box-shadow:0 0 0 1px rgba(242,201,76,.35);
}

/* Switch */
.custom-switch .custom-control-input:checked ~ .custom-control-label::before{
    background:var(--k-gold);
    border-color:var(--k-gold);
}

/* Buttons */
.kemetic-btn{
    background:linear-gradient(135deg,#f2c94c,#c9a227);
    border:none;
    color:#000;
    font-weight:600;
    border-radius:10px;
}

/* Accordion wrapper */
.kemetic-accordion{
    background:#0f0f0f;
    border:1px dashed var(--k-border);
    border-radius:14px;
    padding:18px;
}

/* Hint text */
.kemetic-hint{
    font-size:12px;
    color:var(--k-muted);
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

            {{-- Subscribe --}}
            <div class="form-group d-flex align-items-center justify-content-between">
                <label class="kemetic-label mb-0" for="subscribeSwitch">
                    {{ trans('update.include_subscribe') }}
                </label>
                <div class="custom-control custom-switch">
                    <input type="checkbox"
                           name="subscribe"
                           class="custom-control-input"
                           id="subscribeSwitch"
                           {{ (!empty($bundle) && $bundle->subscribe) || old('subscribe') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="subscribeSwitch"></label>
                </div>
            </div>

            <p class="kemetic-hint mb-15">
                {{ trans('forms.subscribe_hint') }}
            </p>

            {{-- Access Days --}}
            <div class="form-group mt-20">
                <label class="kemetic-label">
                    {{ trans('update.access_days') }} ({{ trans('public.optional') }})
                </label>
                <input type="number"
                       name="access_days"
                       value="{{ !empty($bundle) ? $bundle->access_days : old('access_days') }}"
                       class="form-control @error('access_days') is-invalid @enderror">
                @error('access_days')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <p class="kemetic-hint mt-10">
                    {{ trans('update.access_days_input_hint') }}
                </p>
            </div>

            {{-- Price --}}
            <div class="form-group mt-20">
                <label class="kemetic-label">
                    {{ trans('public.price') }} ({{ $currency }})
                </label>
                <input type="number"
                       name="price"
                       value="{{ (!empty($bundle) && !empty($bundle->price)) ? convertPriceToUserCurrency($bundle->price) : old('price') }}"
                       class="form-control @error('price') is-invalid @enderror"
                       placeholder="{{ trans('public.0_for_free') }}">
                @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>
    </div>
</div>

<section class="mt-40">
    <div class="kemetic-card">

        <h2 class="kemetic-title mb-10">
            {{ trans('webinars.sale_plans') }}
            <span class="kemetic-hint">({{ trans('public.optional') }})</span>
        </h2>

        <div class="mt-10">
            <p class="kemetic-hint">{{ trans('webinars.sale_plans_hint_1') }}</p>
            <p class="kemetic-hint">{{ trans('webinars.sale_plans_hint_2') }}</p>
            <p class="kemetic-hint">{{ trans('webinars.sale_plans_hint_3') }}</p>
        </div>

        <button id="webinarAddTicket"
                data-webinar-id="{{ $bundle->id }}"
                type="button"
                class="btn kemetic-btn btn-sm mt-15">
            {{ trans('public.add_plan') }}
        </button>

        <div class="row mt-20">
            <div class="col-12">
                <div class="kemetic-accordion" id="ticketsAccordion">

                    @if(!empty($bundle->tickets) && count($bundle->tickets))
                        <ul class="draggable-lists" data-order-table="tickets">
                            @foreach($bundle->tickets as $ticketInfo)
                                @include('web.default.panel.bundle.create_includes.accordions.ticket',[
                                    'bundle' => $bundle,
                                    'ticket' => $ticketInfo
                                ])
                            @endforeach
                        </ul>
                    @else
                        @include(getTemplate().'.includes.no-result',[
                            'file_name' => 'ticket.png',
                            'title' => trans('public.ticket_no_result'),
                            'hint' => trans('public.ticket_no_result_hint'),
                        ])
                    @endif

                </div>
            </div>
        </div>

    </div>
</section>


<div id="newTicketForm" class="d-none">
    @include('web.default.panel.bundle.create_includes.accordions.ticket',['bundle' => $bundle])
</div>

@push('scripts_bottom')
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>
@endpush
