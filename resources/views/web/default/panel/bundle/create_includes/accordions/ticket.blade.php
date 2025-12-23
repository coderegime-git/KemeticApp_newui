<style>
    /* ===== Kemetic Ticket Card ===== */

.kemetic-card {
    background: linear-gradient(180deg,#141414,#0b0b0b);
    border-radius: 18px;
    padding: 18px;
    border: 1px solid rgba(212,175,55,.25);
    box-shadow: 0 20px 45px rgba(0,0,0,.6);
}

.kemetic-title {
    font-size: 16px;
    font-weight: 700;
    color: #d4af37;
    cursor: pointer;
}

.kemetic-body {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px dashed rgba(212,175,55,.25);
}

.kemetic-label {
    font-size: 13px;
    color: #d4af37;
    font-weight: 600;
    margin-bottom: 6px;
}

.kemetic-input {
    background: #0f0f0f;
    border: 1px solid rgba(212,175,55,.3);
    border-radius: 12px;
    color: #fff;
}

.kemetic-input:focus {
    border-color: #d4af37;
    box-shadow: 0 0 0 2px rgba(212,175,55,.25);
}

.kemetic-btn {
    background: linear-gradient(135deg,#d4af37,#b8962e);
    border: none;
    color: #000;
    padding: 10px 28px;
    border-radius: 14px;
    font-weight: 700;
}

/* ===== Three Dot Gold Menu ===== */

.kemetic-dots {
    cursor: pointer;
    color: #d4af37;
    transition: .2s;
}

.kemetic-dots:hover {
    transform: scale(1.15);
}

.kemetic-dropdown {
    background: #0b0b0b;
    border: 1px solid rgba(212,175,55,.3);
    border-radius: 12px;
}

/* ===== Icons ===== */

.kemetic-chevron {
    color: #d4af37;
    cursor: pointer;
}

.kemetic-move {
    color: #777;
}

</style>
<li data-id="{{ !empty($ticket) ? $ticket->id :'' }}"
    class="accordion-row kemetic-card mt-20">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between"
         role="tab"
         id="ticket_{{ !empty($ticket) ? $ticket->id :'record' }}">

        <div class="kemetic-title"
             href="#collapseTicket{{ !empty($ticket) ? $ticket->id :'record' }}"
             data-toggle="collapse"
             aria-expanded="true">

            {{ !empty($ticket) ? $ticket->title : trans('public.add_new_ticket') }}
        </div>

        <div class="d-flex align-items-center">

            <i data-feather="move"
               class="move-icon kemetic-move mr-15"
               height="20"></i>

            @if(!empty($ticket))
                <div class="dropdown mr-15">
                    <span class="kemetic-dots"
                          data-toggle="dropdown">
                        <i data-feather="more-vertical"></i>
                    </span>

                    <div class="dropdown-menu kemetic-dropdown">
                        <a href="/panel/tickets/{{ $ticket->id }}/delete"
                           class="dropdown-item text-danger">
                            {{ trans('public.delete') }}
                        </a>
                    </div>
                </div>
            @endif

            <i data-feather="chevron-down"
               class="kemetic-chevron"
               href="#collapseTicket{{ !empty($ticket) ? $ticket->id :'record' }}"
               data-toggle="collapse"></i>
        </div>
    </div>

    {{-- Body --}}
    <div id="collapseTicket{{ !empty($ticket) ? $ticket->id :'record' }}"
         class="collapse @if(empty($ticket)) show @endif">

        <div class="kemetic-body">
            <div class="js-content-form ticket-form"
                 data-action="/panel/tickets/{{ !empty($ticket) ? $ticket->id . '/update' : 'store' }}">

                <input type="hidden"
                       name="ajax[{{ !empty($ticket) ? $ticket->id : 'new' }}][bundle_id]"
                       value="{{ !empty($bundle) ? $bundle->id :'' }}">

                {{-- Title --}}
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="kemetic-label">{{ trans('public.title') }}</label>
                            <input type="text"
                                   name="ajax[{{ !empty($ticket) ? $ticket->id : 'new' }}][title]"
                                   class="form-control kemetic-input js-ajax-title"
                                   value="{{ !empty($ticket) ? $ticket->title :'' }}">
                        </div>
                    </div>
                </div>

                {{-- Discount / Capacity --}}
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="kemetic-label">{{ trans('public.discount') }} (%)</label>
                            <input type="text"
                                   class="form-control kemetic-input js-ajax-discount"
                                   name="ajax[{{ !empty($ticket) ? $ticket->id : 'new' }}][discount]"
                                   value="{{ !empty($ticket) ? $ticket->discount :'' }}">
                        </div>

                        <div class="form-group mt-15">
                            <label class="kemetic-label">{{ trans('public.capacity') }}</label>
                            <input type="text"
                                   class="form-control kemetic-input js-ajax-capacity"
                                   name="ajax[{{ !empty($ticket) ? $ticket->id : 'new' }}][capacity]"
                                   value="{{ !empty($ticket) ? $ticket->capacity :'' }}">
                        </div>
                    </div>
                </div>

                {{-- Dates --}}
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="kemetic-label">{{ trans('public.start_date') }}</label>
                                <input type="text"
                                       class="form-control kemetic-input datepicker js-ajax-start_date"
                                       name="ajax[{{ !empty($ticket) ? $ticket->id : 'new' }}][start_date]"
                                       value="{{ !empty($ticket) ? dateTimeFormat($ticket->start_date,'Y-m-d',false) :'' }}">
                            </div>

                            <div class="col-lg-6 mt-15 mt-lg-0">
                                <label class="kemetic-label">{{ trans('webinars.end_date') }}</label>
                                <input type="text"
                                       class="form-control kemetic-input datepicker js-ajax-end_date"
                                       name="ajax[{{ !empty($ticket) ? $ticket->id : 'new' }}][end_date]"
                                       value="{{ !empty($ticket) ? dateTimeFormat($ticket->end_date,'Y-m-d',false) :'' }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-30 d-flex align-items-center" style="padding:10px;">
                    <button type="button"
                            class="btn kemetic-btn js-save-ticket">
                        {{ trans('public.save') }}
                    </button>

                    @if(empty($ticket))
                        <button type="button"
                                class="btn btn-danger ml-15 cancel-accordion" style="margin-left: 10px;">
                            {{ trans('public.close') }}
                        </button>
                    @endif
                </div>

            </div>
        </div>
    </div>
</li>
