<style>

/* ROOT COLORS */
:root {
    --kemetic-bg: #0E0E0E;
    --kemetic-card: #161616;
    --kemetic-border: rgba(242, 201, 76, 0.28);
    --kemetic-gold: #F2C94C;
    --kemetic-gold-dark: #D5A632;
    --kemetic-text: #E3E3E3;
    --kemetic-gray: #9A9A9A;
    --kemetic-radius: 14px;
    --kemetic-shadow: 0 0 14px rgba(242, 201, 76, 0.15);
}

/* GENERAL PAGE BG */
body {
    background: var(--kemetic-bg) !important;
}

/* SECTION WRAPPER */
.kemetic-card {
    background: var(--kemetic-card);
    border: 1px solid var(--kemetic-border);
    padding: 22px;
    border-radius: var(--kemetic-radius);
    box-shadow: var(--kemetic-shadow);
    margin-bottom: 25px;
}

/* LABEL */
.input-label {
    color: var(--kemetic-gold) !important;
    font-weight: 600;
}

/* TEXT GRAY */
.text-gray {
    color: var(--kemetic-gray) !important;
}

/* FORM INPUTS */
.form-control {
    background: #1A1A1A !important;
    border: 1px solid var(--kemetic-border) !important;
    border-radius: var(--kemetic-radius) !important;
    padding: 10px 14px !important;
    color: var(--kemetic-text) !important;
    transition: 0.25s;
}

.form-control:focus {
    border-color: var(--kemetic-gold) !important;
    box-shadow: 0 0 10px rgba(242, 201, 76, 0.35) !important;
}

/* SECTION TITLE */
.section-title {
    color: var(--kemetic-gold) !important;
    font-size: 20px !important;
    font-weight: 700 !important;
    padding-left: 10px;
}

.section-title.after-line::after {
    background: var(--kemetic-gold);
}

/* BUTTON PRIMARY */
.btn-primary {
    background: var(--kemetic-gold) !important;
    border-color: var(--kemetic-gold-dark) !important;
    color: #000 !important;
    font-weight: 600 !important;
    border-radius: var(--kemetic-radius) !important;
    padding: 6px 18px !important;
}

.btn-primary:hover {
    background: var(--kemetic-gold-dark) !important;
}

/* SWITCHES */
.custom-switch .custom-control-label::before {
    background: #202020 !important;
    border: 1px solid var(--kemetic-border) !important;
    border-radius: 20px !important;
}

.custom-switch .custom-control-input:checked ~ .custom-control-label::before {
    background: var(--kemetic-gold) !important;
    border-color: var(--kemetic-gold) !important;
    box-shadow: 0 0 12px rgba(242, 201, 76, 0.45);
}

.custom-control-label::after {
    border-radius: 50% !important;
}

/* ACCORDION */
.accordion-row, .accordion-content-wrapper {
    background: #1a1a1a !important;
    border: 1px solid var(--kemetic-border);
    border-radius: var(--kemetic-radius);
    padding: 15px;
}

.accordion-row .card-header {
    background: transparent !important;
    border: none !important;
}

/* DRAGGABLE LISTS */
.draggable-lists li {
    background: #1a1a1a !important;
    border-radius: var(--kemetic-radius);
    border: 1px solid var(--kemetic-border);
    margin-bottom: 12px;
    padding: 12px;
    cursor: move;
    transition: 0.25s;
}

.draggable-lists li:hover {
    box-shadow: var(--kemetic-shadow);
    transform: scale(1.01);
}

/* NO RESULT BOX */
.no-result {
    background: #1A1A1A !important;
    border-radius: var(--kemetic-radius);
    border: 1px solid var(--kemetic-border);
}

.no-result .title {
    color: var(--kemetic-gold) !important;
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


@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link href="/assets/default/vendors/sortable/jquery-ui.min.css"/>
@endpush
<div class="kemetic-card">
<div class="row kemetic-card">
    <div class="col-12 col-md-6">

        <div class="form-group mt-30 d-flex align-items-center justify-content-between mb-5">
            <label class="cursor-pointer input-label" for="subscribeSwitch">{{ trans('update.include_subscribe') }}</label>
            <div class="custom-control custom-switch">
                <input type="checkbox" name="subscribe" class="custom-control-input" id="subscribeSwitch" {{ !empty($webinar) && $webinar->subscribe ? 'checked' : (old('subscribe') ? 'checked' : '')  }}>
                <label class="custom-control-label" for="subscribeSwitch"></label>
            </div>
        </div>

        <div>
            <p class="font-12 text-gray">- {{ trans('forms.subscribe_hint') }}</p>
        </div>

        <div class="form-group mt-15">
            <label class="input-label">{{ trans('update.access_days') }} ({{ trans('public.optional') }})</label>
            <input type="number" name="access_days" value="{{ !empty($webinar) ? $webinar->access_days : old('access_days') }}" class="form-control @error('access_days')  is-invalid @enderror"/>
            @error('access_days')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
            <p class="font-12 text-gray mt-10">- {{ trans('update.access_days_input_hint') }}</p>
        </div>

        <div class="form-group mt-15">
            <label class="input-label">{{ trans('public.price') }} ({{ $currency }})</label>
            <input type="number" name="price" value="{{ (!empty($webinar) and !empty($webinar->price)) ? convertPriceToUserCurrency($webinar->price) : old('price') }}" class="form-control @error('price')  is-invalid @enderror" placeholder="{{ trans('public.0_for_free') }}"/>
            @error('price')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        @if($authUser->isOrganization() and $authUser->id == $webinar->creator_id)
            <div class="form-group mt-15">
                <label class="input-label">{{ trans('update.organization_price') }} ({{ $currency }})</label>
                <input type="number" name="organization_price" value="{{ (!empty($webinar) and $webinar->organization_price) ? convertPriceToUserCurrency($webinar->organization_price) : old('organization_price') }}" class="form-control @error('organization_price')  is-invalid @enderror" placeholder=""/>
                @error('organization_price')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
                <p class="font-12 text-gray mt-5">- {{ trans('update.organization_price_hint') }}</p>
            </div>
        @endif
    </div>
</div>

<section class="kemetic-card mt-30">
    <div class="">
        <h2 class="section-title after-line">{{ trans('webinars.sale_plans') }} ({{ trans('public.optional') }})</h2>


        <div class="mt-15">
            <p class="font-12 text-gray">- {{ trans('webinars.sale_plans_hint_1') }}</p>
            <p class="font-12 text-gray">- {{ trans('webinars.sale_plans_hint_2') }}</p>
            <p class="font-12 text-gray">- {{ trans('webinars.sale_plans_hint_3') }}</p>
        </div>
    </div>

    <button id="webinarAddTicket" data-webinar-id="{{ $webinar->id }}" type="button" class="btn btn-primary btn-sm mt-15">{{ trans('public.add_plan') }}</button>

    <div class="row mt-10">
        <div class="col-12">

            <div class="accordion-content-wrapper mt-15" id="ticketsAccordion" role="tablist" aria-multiselectable="true">
                @if(!empty($webinar->tickets) and count($webinar->tickets))
                    <ul class="draggable-lists" data-order-table="tickets">
                        @foreach($webinar->tickets as $ticketInfo)
                            @include('web.default.panel.webinar.create_includes.accordions.ticket',['webinar' => $webinar,'ticket' => $ticketInfo])
                        @endforeach
                    </ul>
                @else
                    @include(getTemplate() . '.includes.no-result',[
                        'file_name' => 'ticket.png',
                        'title' => trans('public.ticket_no_result'),
                        'hint' => trans('public.ticket_no_result_hint'),
                    ])
                @endif
            </div>
        </div>
    </div>
</section>

<div id="newTicketForm" class="d-none">
    @include('web.default.panel.webinar.create_includes.accordions.ticket',['webinar' => $webinar])
</div>
</div>

@push('scripts_bottom')
<script>
 // Initialize placeholders for date inputs
function initDatePlaceholders() {
    document.querySelectorAll('input[type="date"]').forEach(input => {
        // Set placeholder attribute
        input.setAttribute('placeholder', 'yyyy-mm-dd');
        
        // For browsers that don't show placeholder on date inputs
        // Create a pseudo-placeholder effect
        if (!input.value) {
            input.style.color = '#999';
        }
        
        input.addEventListener('focus', function() {
            this.style.color = '';
            if (this.type === 'date' && !this.value) {
                this.type = 'text';
            }
        });
        
        input.addEventListener('blur', function() {
            if (this.type === 'text' && !this.value) {
                this.type = 'date';
                this.style.color = '#999';
            }
        });
    });
}

// Call on page load
document.addEventListener('DOMContentLoaded', initDatePlaceholders);

function formatDateToYMD(input) {
    if (input.value) {
        try {
            // Parse the date value
            const date = new Date(input.value);
            
            // Check if it's a valid date
            if (isNaN(date.getTime())) {
                console.error('Invalid date:', input.value);
                return;
            }
            
            // Format to YYYY-MM-DD
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const formattedDate = `${year}-${month}-${day}`;
            
            // Only update if different
            if (input.value !== formattedDate) {
                input.value = formattedDate;
            }
        } catch (error) {
            console.error('Error formatting date:', error);
        }
    }
}
</script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>
@endpush
