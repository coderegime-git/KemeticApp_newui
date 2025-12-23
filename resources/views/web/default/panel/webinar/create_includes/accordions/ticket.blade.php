{{-- ===============================
    KEMETIC TICKET ACCORDION STYLES
================================ --}}
<style>
:root{
    --k-bg:#0f0f0f;
    --k-card:#161616;
    --k-border:#2a2a2a;
    --k-gold:#F2C94C;
    --k-gold-soft:rgba(242,201,76,.18);
    --k-text:#e6e6e6;
    --k-muted:#9a9a9a;
    --k-radius:14px;
}

/* Ticket Card */
.k-ticket-row{
    background:var(--k-card)!important;
    border:1px solid var(--k-border)!important;
    border-radius:var(--k-radius);
    transition:.25s ease;
}

.k-ticket-row:hover{
    border-color:var(--k-gold);
    box-shadow:0 0 0 1px var(--k-gold-soft);
}

/* Header */
.k-ticket-header{
    cursor:pointer;
}

.k-ticket-title{
    color:var(--k-text);
    font-weight:600;
    font-size:15px;
}

/* Icons */
.k-icon{
    color:var(--k-muted);
    transition:.25s ease;
}

.k-ticket-row:hover .k-icon{
    color:var(--k-gold);
}

.collapse-chevron-icon{
    transition:.25s ease;
}

/* Body */
.k-ticket-body{
    border-top:1px solid var(--k-border);
    margin-top:15px;
    padding-top:20px;
}

/* Form */
.k-form .input-label{
    color:var(--k-muted);
    font-size:13px;
    font-weight:500;
}

.k-form .form-control{
    background:#101010;
    border:1px solid var(--k-border);
    color:var(--k-text);
    border-radius:10px;
}

.k-form .form-control:focus{
    border-color:var(--k-gold);
    box-shadow:0 0 0 2px var(--k-gold-soft);
}

/* Input group */
.k-form .input-group-text{
    background:#111;
    border:1px solid var(--k-border);
}

/* Buttons */
.k-btn-save{
    background:linear-gradient(135deg,#F2C94C,#E0B63A);
    border:none;
    color:#000;
    font-weight:600;
    border-radius:10px;
    padding:6px 18px;
}

.k-btn-close{
    background:#1e1e1e;
    border:1px solid var(--k-border);
    color:var(--k-text);
    border-radius:10px;
}

/* Dropdown */
.k-dropdown .dropdown-menu{
    background:#151515;
    border:1px solid var(--k-border);
}

.k-dropdown .dropdown-item{
    color:var(--k-text);
}

.k-dropdown .dropdown-item:hover{
    background:var(--k-gold-soft);
    color:var(--k-gold);
}

/* ===============================
   KEMETIC TICKET CARD
================================ */

.kemetic-ticket-card {
    background: linear-gradient(145deg, #0b0b0b, #141414);
    border: 1px solid rgba(212, 175, 55, 0.25);
    border-radius: 16px;
    padding: 22px;
    color: #d0d0d0;
}

/* Labels */
.kemetic-label {
    color: #d4af37;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 6px;
}

/* Inputs */
.kemetic-input {
    background: #000 !important;
    border: 1px solid rgba(212, 175, 55, 0.35) !important;
    border-radius: 10px;
    color: #fff !important;
    height: 46px;
}

.kemetic-input:focus {
    border-color: #d4af37 !important;
    box-shadow: 0 0 0 0.15rem rgba(212,175,55,.25);
}

/* Calendar icon */
.kemetic-calendar {
    background: #000;
    border: 1px solid rgba(212,175,55,.35);
    color: #d4af37;
}

/* Text helpers */
.kemetic-hint {
    font-size: 12px;
    color: #9a9a9a;
}

.kemetic-gold {
    color: #d4af37;
    font-weight: 600;
}

/* Buttons */
.kemetic-btn-gold {
    background: linear-gradient(135deg, #d4af37, #b8962e);
    color: #000;
    border-radius: 22px;
    padding: 8px 24px;
    font-weight: 600;
    border: none;
}

.kemetic-btn-gold:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(212,175,55,.35);
}

.kemetic-btn-outline {
    background: transparent;
    border: 1px solid rgba(212,175,55,.4);
    color: #d4af37;
    border-radius: 22px;
    padding: 8px 20px;
}

.kemetic-btn-outline:hover {
    background: rgba(212,175,55,.1);
}

.kemetic-more-btn {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: transparent;
    border: 1px solid rgba(242,201,76,.25);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;

    transition: all .25s ease;
}

/* Icon color */
.kemetic-more-btn svg {
    stroke: #F2C94C;
}

/* Hover effect */
.kemetic-more-btn:hover {
    background: rgba(242,201,76,.12);
    border-color: #F2C94C;
    box-shadow: 0 0 14px rgba(242,201,76,.35);
}

/* Active / open state */
.kemetic-more-btn[aria-expanded="true"] {
    background: rgba(242,201,76,.18);
    box-shadow: 0 0 18px rgba(242,201,76,.45);
}

/* Remove bootstrap caret */
.kemetic-more-btn::after {
    display: none !important;
}

/* Mobile tap feedback */
.kemetic-more-btn:active {
    transform: scale(.95);
}

</style>

{{-- ===============================
    TICKET ACCORDION ITEM
================================ --}}
<li data-id="{{ !empty($ticket) ? $ticket->id :'' }}"
    class="accordion-row k-ticket-row mt-20 py-15 px-20">

    {{-- HEADER --}}
    <div class="d-flex align-items-center justify-content-between k-ticket-header"
         role="tab"
         id="ticket_{{ !empty($ticket) ? $ticket->id :'record' }}">

        <div class="k-ticket-title"
             href="#collapseTicket{{ !empty($ticket) ? $ticket->id :'record' }}"
             data-toggle="collapse"
             role="button">
            {{ !empty($ticket) ? $ticket->title : trans('public.add_new_ticket') }}
        </div>

        <div class="d-flex align-items-center">
            <i data-feather="move" class="k-icon mr-10 cursor-pointer" height="20"></i>

            @if(!empty($ticket))
               

                <div class="btn-group dropdown k-dropdown mr-10">
                    <button type="button" class="btn-transparent dropdown-toggle kemetic-more-btn" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
                        <i data-feather="more-vertical" height="20"></i>
                    </button>
                    <!-- <button type="button"
                            class="btn-transparent dropdown-toggle"
                            data-toggle="dropdown">
                        <i data-feather="more-vertical" class="k-icon" height="20"></i>
                    </button> -->
                    <div class="dropdown-menu">
                        <a href="/panel/tickets/{{ $ticket->id }}/delete"
                           class="dropdown-item delete-action">
                            {{ trans('public.delete') }}
                        </a>
                    </div>
                </div>
            @endif

            <i data-feather="chevron-down"
               class="k-icon collapse-chevron-icon"
               height="20"
               data-toggle="collapse"
               href="#collapseTicket{{ !empty($ticket) ? $ticket->id :'record' }}">
            </i>
        </div>
    </div>

    {{-- BODY --}}
    <div id="collapseTicket{{ !empty($ticket) ? $ticket->id :'record' }}"
     aria-labelledby="ticket_{{ !empty($ticket) ? $ticket->id :'record' }}"
     class="collapse @if(empty($ticket)) show @endif"
     role="tabpanel">

    <div class="panel-collapse kemetic-ticket-card">
        <div class="js-content-form ticket-form"
             data-action="/panel/tickets/{{ !empty($ticket) ? $ticket->id . '/update' : 'store' }}">

            <input type="hidden"
                   name="ajax[{{ !empty($ticket) ? $ticket->id : 'new' }}][webinar_id]"
                   value="{{ !empty($webinar) ? $webinar->id :'' }}">

            {{-- LANGUAGE --}}
            @if(!empty(getGeneralSettings('content_translate')))
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="form-group kemetic-form-group">
                            <label class="kemetic-label">{{ trans('auth.language') }}</label>

                            <select name="ajax[{{ !empty($ticket) ? $ticket->id : 'new' }}][locale]"
                                    class="form-control kemetic-input {{ !empty($ticket) ? 'js-webinar-content-locale' : '' }}"
                                    data-webinar-id="{{ !empty($webinar) ? $webinar->id : '' }}"
                                    data-id="{{ !empty($ticket) ? $ticket->id : '' }}"
                                    data-relation="tickets"
                                    data-fields="title">

                                @foreach($userLanguages as $lang => $language)
                                    <option value="{{ $lang }}"
                                            {{ (!empty($ticket) && !empty($ticket->locale)) ? (mb_strtolower($ticket->locale) == mb_strtolower($lang) ? 'selected' : '') : ($locale == $lang ? 'selected' : '') }}>
                                        {{ $language }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            @else
                <input type="hidden"
                       name="ajax[{{ !empty($ticket) ? $ticket->id : 'new' }}][locale]"
                       value="{{ $defaultLocale }}">
            @endif

            {{-- TITLE --}}
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="form-group kemetic-form-group">
                        <label class="kemetic-label">{{ trans('public.title') }}</label>
                        <input type="text"
                               name="ajax[{{ !empty($ticket) ? $ticket->id : 'new' }}][title]"
                               class="js-ajax-title form-control kemetic-input"
                               value="{{ !empty($ticket) ? $ticket->title :'' }}"
                               placeholder="{{ trans('forms.maximum_64_characters') }}">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>

            {{-- DISCOUNT & CAPACITY --}}
            <div class="row">
                <div class="col-12 col-lg-4">
                    <div class="form-group kemetic-form-group">
                        <label class="kemetic-label">
                            {{ trans('public.discount') }} <span class="kemetic-brace">(%)</span>
                        </label>
                        <input type="text"
                               name="ajax[{{ !empty($ticket) ? $ticket->id : 'new' }}][discount]"
                               class="js-ajax-discount form-control kemetic-input"
                               value="{{ !empty($ticket) ? $ticket->discount :'' }}">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group kemetic-form-group">
                        <label class="kemetic-label">{{ trans('public.capacity') }}</label>

                        @if(empty($ticket) && !empty($webinar->capacity) && !empty($sumTicketsCapacities))
                            <span class="kemetic-hint">
                                {{ trans('panel.remaining') }}:
                                <span class="js-ticket-remaining-capacity kemetic-gold">
                                    {{ $webinar->capacity - $sumTicketsCapacities }}
                                </span>
                            </span>
                        @endif

                        <input type="text"
                               name="ajax[{{ !empty($ticket) ? $ticket->id : 'new' }}][capacity]"
                               class="js-ajax-capacity form-control kemetic-input mt-10"
                               value="{{ !empty($ticket) ? $ticket->capacity :'' }}"
                               placeholder="{{ $webinar->isWebinar() ? trans('webinars.empty_means_webinar_capacity') : trans('forms.empty_means_unlimited') }}">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>

            {{-- DATES --}}
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="row">
                        @foreach(['start_date' => trans('public.start_date'), 'end_date' => trans('webinars.end_date')] as $field => $label)
                            <div class="col-12 col-lg-6 {{ $field == 'end_date' ? 'mt-15 mt-lg-0' : '' }}">
                                <div class="form-group kemetic-form-group">
                                    <label class="kemetic-label">{{ $label }}</label>
                                    <div class="input-group kemetic-input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text kemetic-calendar">
                                                <i data-feather="calendar" width="16" height="16"></i>
                                            </span>
                                        </div>
                                        <input type="text"
                                               name="ajax[{{ !empty($ticket) ? $ticket->id : 'new' }}][{{ $field }}]"
                                               class="js-ajax-{{ $field }} form-control kemetic-input datepicker"
                                               value="{{ !empty($ticket) ? dateTimeFormat($ticket->{$field}, 'Y-m-d', false) :'' }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="mt-30 d-flex align-items-center" style="padding:10px;">
                <button type="button" class="js-save-ticket btn kemetic-btn-gold">
                    {{ trans('public.save') }}
                </button>

                @if(empty($ticket))
                    <button type="button" class="btn kemetic-btn-outline ml-10 cancel-accordion">
                        {{ trans('public.close') }}
                    </button>
                @endif
            </div>

        </div>
    </div>
</div>

</li>
