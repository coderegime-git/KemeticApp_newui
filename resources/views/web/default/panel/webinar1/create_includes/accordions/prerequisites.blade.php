<style>
    /* MAIN ACCORDION BOX */
.kemetic-accordion-item{
    background:#111;
    border:1px solid rgba(242,201,76,0.25);
    border-radius:14px;
    padding:15px 18px;
    margin-top:18px;
    transition:.3s ease;
}
.kemetic-accordion-item:hover{
    border-color:#F2C94C;
    box-shadow:0 0 12px rgba(242,201,76,0.25);
}

/* HEADER */
.kemetic-accordion-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    cursor:pointer;
    color:#F2C94C;
}

.kemetic-accordion-title{
    font-size:15px;
    font-weight:600;
    color:#fff;
}

/* ICONS */
.kemetic-move-icon,
.kemetic-chevron,
.kemetic-icon-btn i{
    color:#F2C94C;
    width:20px;
    height:20px;
    cursor:pointer;
}

/* DROPDOWN */
.kemetic-dropdown-menu{
    background:#222;
    border:1px solid rgba(242,201,76,0.35);
    border-radius:10px;
    padding:5px 0;
}

.kemetic-dropdown-item{
    display:block;
    padding:8px 15px;
    color:#fff;
}
.kemetic-dropdown-item:hover{
    background:rgba(242,201,76,0.15);
    color:#F2C94C;
}

/* CONTENT BOX */
.kemetic-collapse-body{
    margin-top:12px;
}
.kemetic-body-inner{
    background:#0b0b0b;
    padding:20px;
    border-radius:12px;
    border:1px solid rgba(242,201,76,0.18);
}

/* FORM ELEMENTS */
.kemetic-form-group label{
    color:#F2C94C;
    margin-bottom:6px;
    font-size:14px;
}

.kemetic-input{
    width:100%;
    background:#111;
    border:1px solid rgba(242,201,76,0.25);
    color:#fff;
    padding:8px 12px;
    border-radius:10px;
}
.kemetic-input:focus{
    border-color:#F2C94C;
    box-shadow:0 0 6px rgba(242,201,76,0.35);
}

/* SWITCH */
.kemetic-form-switch{
    margin-top:20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.kemetic-switch input{ display:none; }
.kemetic-switch span{
    width:46px;
    height:24px;
    background:#333;
    border-radius:20px;
    position:relative;
    display:inline-block;
}
.kemetic-switch span::after{
    content:'';
    width:20px;
    height:20px;
    background:#F2C94C;
    border-radius:50%;
    position:absolute;
    top:2px;
    left:2px;
    transition:.3s;
}
.kemetic-switch input:checked + span{
    background:#F2C94C;
}
.kemetic-switch input:checked + span::after{
    left:24px;
    background:#000;
}

.kemetic-hint{
    font-size:12px;
    color:#aaa;
    margin-top:8px;
}

/* BUTTONS */
.kemetic-save-row{
    margin-top:25px;
    display:flex;
    gap:15px;
}
.kemetic-btn-save{
    background:#F2C94C;
    color:#000;
    padding:8px 18px;
    font-weight:600;
    border-radius:8px;
}
.kemetic-btn-cancel{
    background:#822;
    color:#fff;
    padding:8px 18px;
    border-radius:8px;
}
/* ===============================
   KEMETIC PREREQUISITE SELECT
================================ */

.kemetic-prerequisite-box {
    background: linear-gradient(145deg,#0b0b0b,#141414);
    border: 1px solid rgba(242,201,76,.22);
    border-radius: 14px;
    padding: 14px 16px;
}

/* Label */
.kemetic-label {
    color: #f2c94c;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
}

/* Select input */
.kemetic-input {
    background: #000 !important;
    border: 1px solid rgba(242,201,76,.35) !important;
    border-radius: 10px;
    color: #fff !important;
    min-height: 46px;
}

.kemetic-input:focus {
    border-color: #f2c94c !important;
    box-shadow: 0 0 0 2px rgba(242,201,76,.25);
}

/* Validation */
.kemetic-input.is-invalid {
    border-color: #c62828 !important;
}

/* ===============================
   SELECT2 DROPDOWN THEME
================================ */

.select2-container--default .select2-selection--single {
    background: #000;
    border: none;
}

.select2-dropdown {
    background: #0b0b0b;
    border: 1px solid rgba(242,201,76,.25);
    border-radius: 12px;
}

.select2-results__option {
    color: #e0e0e0;
    padding: 10px 14px;
}

.select2-results__option--highlighted {
    background: rgba(242,201,76,.15);
    color: #f2c94c;
}


</style>
<li data-id="{{ !empty($prerequisite) ? $prerequisite->id :'' }}" 
    class="accordion-row kemetic-accordion-item">

    <!-- HEADER -->
    <div class="kemetic-accordion-header" 
         role="tab"
         id="prerequisite_{{ !empty($prerequisite) ? $prerequisite->id :'record' }}"
         data-toggle="collapse"
         href="#collapsePrerequisite{{ !empty($prerequisite) ? $prerequisite->id :'record' }}"
         aria-expanded="true">

        <div class="kemetic-accordion-title">
            <span class="kemetic-title-text">
                {{ (!empty($prerequisite) and !empty($prerequisite->prerequisiteWebinar)) 
                    ? $prerequisite->prerequisiteWebinar->title .' - '. $prerequisite->prerequisiteWebinar->teacher->full_name
                    : trans('public.add_new_prerequisites') }}
            </span>
        </div>

        <div class="kemetic-header-actions">

            <i data-feather="move" class="kemetic-move-icon"></i>

            @if(!empty($prerequisite))
                <div class="kemetic-dropdown">
                    <button class="kemetic-icon-btn" data-toggle="dropdown">
                        <i data-feather="more-vertical"></i>
                    </button>
                    <div class="kemetic-dropdown-menu">
                        <a href="/panel/prerequisites/{{ $prerequisite->id }}/delete"
                           class="kemetic-dropdown-item delete-action">
                            {{ trans('public.delete') }}
                        </a>
                    </div>
                </div>
            @endif

            <i class="kemetic-chevron" 
               data-feather="chevron-down"
               data-toggle="collapse"
               href="#collapsePrerequisite{{ !empty($prerequisite) ? $prerequisite->id :'record' }}">
            </i>
        </div>
    </div>

    <!-- CONTENT -->
    <div id="collapsePrerequisite{{ !empty($prerequisite) ? $prerequisite->id :'record' }}"
         class="collapse kemetic-collapse-body @if(empty($prerequisite)) show @endif">

        <div class="kemetic-body-inner">

            <div class="prerequisite-form"
                 data-action="/panel/prerequisites/{{ !empty($prerequisite) ? $prerequisite->id . '/update' : 'store' }}">

                <input type="hidden"
                       name="ajax[{{ !empty($prerequisite) ? $prerequisite->id : 'new' }}][webinar_id]"
                       value="{{ !empty($webinar) ? $webinar->id :'' }}">

                <div class="row">
                    <div class="col-12 col-lg-6">

                        <!-- SELECT -->
                        <div class="form-group kemetic-form-group kemetic-prerequisite-box">

                            <label class="kemetic-label">
                                {{ trans('public.select_prerequisites') }}
                            </label>

                            <select name="ajax[{{ !empty($prerequisite) ? $prerequisite->id : 'new' }}][prerequisite_id]"
                                    class="kemetic-input kemetic-select2 prerequisites-select2 js-ajax-prerequisite_id"
                                    data-webinar-id="{{ !empty($webinar) ? $webinar->id : '' }}"
                                    data-placeholder="{{ trans('public.search_prerequisites') }}">

                                @if(!empty($prerequisite) && !empty($prerequisite->prerequisiteWebinar))
                                    <option value="{{ $prerequisite->prerequisiteWebinar->id }}" selected>
                                        {{ $prerequisite->prerequisiteWebinar->title }}
                                        — {{ $prerequisite->prerequisiteWebinar->teacher->full_name }}
                                    </option>
                                @endif
                            </select>

                            <div class="invalid-feedback"></div>
                        </div>


                        <!-- SWITCH -->
                        <div class="kemetic-form-switch">
                            <label>{{ trans('public.required') }}</label>

                            <label class="kemetic-switch">
                                <input type="checkbox"
                                       name="ajax[{{ !empty($prerequisite) ? $prerequisite->id : 'new' }}][required]"
                                       @if(!empty($prerequisite) && $prerequisite->required) checked @endif>
                                <span></span>
                            </label>
                        </div>

                        <p class="kemetic-hint">
                            - {{ trans('webinars.required_hint') }}
                        </p>

                    </div>
                </div>

                <!-- BUTTONS -->
                <div class="kemetic-save-row">
                    <button type="button" class="kemetic-btn-save js-save-prerequisite">
                        {{ trans('public.save') }}
                    </button>

                    @if(empty($prerequisite))
                        <button type="button" class="kemetic-btn-cancel cancel-accordion">
                            {{ trans('public.close') }}
                        </button>
                    @endif
                </div>

            </div>
        </div>
    </div>
</li>
