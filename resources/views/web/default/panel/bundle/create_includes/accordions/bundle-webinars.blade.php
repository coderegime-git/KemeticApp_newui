@push('styles_top')
<style>
/* ===============================
   KEMETIC VARIABLES
================================ */
:root {
    --k-black: #0b0b0b;
    --k-dark: #141414;
    --k-dark-2: #1c1c1c;
    --k-gold: #f2c94c;
    --k-gold-soft: rgba(242, 201, 76, 0.15);
    --k-border: rgba(242, 201, 76, 0.25);
    --k-radius: 16px;
}

/* ===============================
   ACCORDION ITEM
================================ */
.kemetic-accordion-item {
    background: var(--k-black);
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius);
    margin-top: 18px;
    transition: .25s ease;
}

.kemetic-accordion-item:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,.6);
}

/* ===============================
   HEADER
================================ */
.kemetic-accordion-header {
    padding: 16px 18px;
    cursor: pointer;
}

.kemetic-accordion-title {
    color: var(--k-gold);
    font-weight: 700;
    font-size: 15px;
}

/* ===============================
   ICONS
================================ */
.kemetic-icon {
    color: #bbb;
    transition: .2s ease;
}

.kemetic-icon:hover {
    color: var(--k-gold);
}

/* ===============================
   BODY
================================ */
.kemetic-accordion-body {
    background: var(--k-dark);
    border-top: 1px solid var(--k-border);
    padding: 20px;
    border-radius: 0 0 var(--k-radius) var(--k-radius);
}

/* ===============================
   FORM ELEMENTS
================================ */
.kemetic-label {
    color: #ddd;
    font-weight: 600;
    margin-bottom: 6px;
}

.kemetic-select {
    background: var(--k-dark-2);
    border: 1px solid var(--k-border);
    color: #fff;
    border-radius: 12px;
    padding: 10px;
}

.kemetic-select:focus {
    border-color: var(--k-gold);
    box-shadow: 0 0 0 2px var(--k-gold-soft);
}

/* ===============================
   BUTTONS
================================ */
.kemetic-btn-primary {
    background: linear-gradient(135deg, #f2c94c, #d4af37);
    color: #000;
    font-weight: 700;
    border-radius: 10px;
    padding: 6px 16px;
}

.kemetic-btn-danger {
    background: transparent;
    color: #ff6b6b;
    border: 1px solid #ff6b6b;
    border-radius: 10px;
    padding: 6px 16px;
}

/* ===============================
   DROPDOWN
================================ */
.kemetic-dropdown {
    background: var(--k-dark-2);
    border: 1px solid var(--k-border);
}

.kemetic-dropdown a {
    color: #ddd;
}

.kemetic-dropdown a:hover {
    background: var(--k-gold-soft);
    color: var(--k-gold);
}
</style>
@endpush


<li data-id="{{ !empty($bundleWebinar) ? $bundleWebinar->id :'' }}"
    class="accordion-row kemetic-accordion-item">

    {{-- HEADER --}}
    <div class="d-flex align-items-center justify-content-between kemetic-accordion-header"
         role="tab"
         id="bundleWebinar_{{ !empty($bundleWebinar) ? $bundleWebinar->id :'record' }}">

        <div class="kemetic-accordion-title"
             href="#collapseBundleWebinar{{ !empty($bundleWebinar) ? $bundleWebinar->id :'record' }}"
             data-toggle="collapse"
             aria-expanded="true"
             data-parent="#bundleWebinarsAccordion">
            {{ (!empty($bundleWebinar) && !empty($bundleWebinar->webinar))
                ? $bundleWebinar->webinar->title
                : trans('update.add_new_course') }}
        </div>

        <div class="d-flex align-items-center">

            <i data-feather="move" class="kemetic-icon mr-10 cursor-pointer" height="20"></i>

            @if(!empty($bundleWebinar))
                <div class="btn-group dropdown mr-10">
                    <button class="btn btn-sm btn-transparent dropdown-toggle"
                            data-toggle="dropdown">
                        <i data-feather="more-vertical" class="kemetic-icon" height="18"></i>
                    </button>

                    <div class="dropdown-menu kemetic-dropdown">
                        <a href="/panel/bundle-webinars/{{ $bundleWebinar->id }}/delete"
                           class="delete-action dropdown-item">
                            {{ trans('public.delete') }}
                        </a>
                    </div>
                </div>
            @endif

            <i data-feather="chevron-down"
               class="kemetic-icon"
               height="20"
               data-toggle="collapse"
               href="#collapseBundleWebinar{{ !empty($bundleWebinar) ? $bundleWebinar->id :'record' }}">
            </i>
        </div>
    </div>

    {{-- BODY --}}
    <div id="collapseBundleWebinar{{ !empty($bundleWebinar) ? $bundleWebinar->id :'record' }}"
         class="collapse @if(empty($bundleWebinar)) show @endif"
         role="tabpanel">

        <div class="kemetic-accordion-body">

            <div class="bundleWebinar-form"
                 data-action="/panel/bundle-webinars/{{ !empty($bundleWebinar) ? $bundleWebinar->id . '/update' : 'store' }}">

                <input type="hidden"
                       name="ajax[{{ !empty($bundleWebinar) ? $bundleWebinar->id : 'new' }}][bundle_id]"
                       value="{{ !empty($bundle) ? $bundle->id :'' }}">

                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label class="kemetic-label">
                                {{ trans('panel.select_course') }}
                            </label>

                            <select
                                name="ajax[{{ !empty($bundleWebinar) ? $bundleWebinar->id : 'new' }}][webinar_id]"
                                class="js-ajax-webinar_id kemetic-select form-control {{ !empty($bundleWebinar) ? 'select2' : 'bundleWebinars-select2' }}"
                                data-bundle-id="{{ !empty($bundle) ? $bundle->id : '' }}">

                                <option value="">{{ trans('panel.select_course') }}</option>

                                @foreach($webinars as $webinar)
                                    <option value="{{ $webinar->id }}"
                                        {{ (!empty($bundleWebinar) && $bundleWebinar->webinar_id == $webinar->id) ? 'selected' : '' }}>
                                        {{ $webinar->title }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="invalid-feedback"></div>
                        </div>

                        <p class="font-12 text-gray mt-5">
                            - {{ trans('update.bundle_webinars_required_hint') }}
                        </p>
                    </div>
                </div>

                <div class="mt-25 d-flex align-items-center">
                    <button type="button"
                            class="js-save-bundleWebinar btn kemetic-btn-primary btn-sm">
                        {{ trans('public.save') }}
                    </button>

                    @if(empty($bundleWebinar))
                        <button type="button"
                                class="btn kemetic-btn-danger btn-sm ml-10 cancel-accordion">
                            {{ trans('public.close') }}
                        </button>
                    @endif
                </div>

            </div>
        </div>
    </div>
</li>
