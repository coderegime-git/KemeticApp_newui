<style>
/* KEMETIC THEME ROOTS */
:root {
    --kemetic-bg: #0F0F0F;
    --kemetic-card: #141414;
    --kemetic-border: rgba(242, 201, 76, 0.25);
    --kemetic-gold: #F2C94C;
    --kemetic-gold-dark: #b8922b;
    --kemetic-text: #e1e1e1;
    --kemetic-gray: #9f9f9f;
    --kemetic-radius: 14px;
    --kemetic-shadow: 0 0 14px rgba(242, 201, 76, 0.18);
}

/* MAIN ROW WRAPPER */
.kemetic-form-section {
    background: var(--kemetic-card);
    border: 1px solid var(--kemetic-border);
    padding: 20px;
    border-radius: var(--kemetic-radius);
    box-shadow: var(--kemetic-shadow);
    margin-bottom: 25px;
}

/* LABEL */
.input-label {
    color: var(--kemetic-gold);
    font-weight: 600;
    margin-bottom: 6px;
}

/* FORM CONTROL */
.form-control,
.custom-select {
    background: #5a5a5a !important;
    border: 1px solid var(--kemetic-border) !important;
    color: var(--kemetic-text) !important;
    border-radius: var(--kemetic-radius) !important;
    padding: 10px 14px !important;
    transition: 0.25s ease;
}

.form-control:focus,
.custom-select:focus {
    border-color: var(--kemetic-gold) !important;
    box-shadow: 0 0 12px rgba(242, 201, 76, 0.35);
}

/* ICON GROUP */
.input-group-text {
    background: #1a1a1a !important;
    border: 1px solid var(--kemetic-border) !important;
}

.input-group-text i {
    color: var(--kemetic-gold) !important;
}

/* TEXT GRAY */
.text-gray {
    color: var(--kemetic-gray) !important;
}

/* SELECT2 */
.select2-container .select2-selection--single {
    background: #1a1a1a !important;
    border: 1px solid var(--kemetic-border) !important;
    height: 42px !important;
    border-radius: var(--kemetic-radius) !important;
}

.select2-selection__rendered {
    line-height: 40px !important;
    color: var(--kemetic-text) !important;
}

.select2-selection__arrow {
    margin-top: 6px !important;
}

/* TAG INPUT */
.bootstrap-tagsinput {
    background: #5a5a5a !important;
    border: 1px solid var(--kemetic-border) !important;
    border-radius: var(--kemetic-radius) !important;
    padding: 8px !important;
}

.bootstrap-tagsinput .tag {
    background: var(--kemetic-gold) !important;
    color: #000 !important;
    border-radius: 8px;
    padding: 3px 8px;
}

/* SWITCH (CUSTOM BOOTSTRAP) */
.custom-control-input:checked ~ .custom-control-label::before {
    background-color: var(--kemetic-gold) !important;
    border-color: var(--kemetic-gold) !important;
    box-shadow: 0 0 10px rgba(242, 201, 76, 0.45);
}

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

/* ===============================
   KEMETIC PARTNER INSTRUCTOR
================================ */

.kemetic-partner-box {
    background: linear-gradient(145deg, #0c0c0c, #141414);
    border: 1px solid rgba(212, 175, 55, 0.25);
    border-radius: 14px;
    padding: 18px 20px;
}

/* Label */
.kemetic-label {
    color: #d4af37;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 8px;
    display: block;
}

/* Select field */
.kemetic-select2 {
    background: #5a5a5a !important;
    border: 1px solid rgba(212, 175, 55, 0.35) !important;
    color: #fff !important;
    border-radius: 10px;
    min-height: 48px;
}

/* Hint text */
.kemetic-hint {
    font-size: 12px;
    color: #9a9a9a;
    margin-top: 6px;
}

/* Error state */
.kemetic-select2.is-invalid {
    border-color: #b00020 !important;
}

/* ===============================
   SELECT2 – KEMETIC THEME
================================ */

.select2-container--default .select2-selection--multiple {
    background: #5a5a5a !important;
    border: 1px solid rgba(212, 175, 55, 0.35) !important;
    border-radius: 10px;
    padding: 6px;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background: linear-gradient(135deg, #d4af37, #b8962e);
    border: none;
    color: #000;
    font-size: 12px;
    padding: 4px 10px;
    border-radius: 20px;
}

.select2-container--default .select2-selection__choice__remove {
    color: #5a5a5a;
    margin-right: 6px;
}

/* Dropdown */
.select2-dropdown {
    background: #5a5a5a;
    border: 1px solid rgba(212, 175, 55, 0.3);
}

.select2-results__option {
    color: #ddd;
}

.select2-results__option--highlighted {
    background: rgba(212, 175, 55, 0.15) !important;
    color: #d4af37;
}



/* FORM GROUP SPACING */
.form-group {
    margin-bottom: 18px;
}
</style>

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.css">
@endpush
<div class="kemetic-form-section">
<div class="row">
    <div class="col-12 col-md-6 mt-15">


        <div class="form-group mt-15">
            <label class="input-label">{{ trans('public.capacity') }}</label>
            <input type="number" name="capacity" value="{{ (!empty($webinar) and !empty($webinar->capacity)) ? $webinar->capacity : old('capacity') }}" class="form-control @error('capacity')  is-invalid @enderror" placeholder="{{ trans('forms.capacity_placeholder') }}"/>
            @error('capacity')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
            <p class="text-gray mt-5 font-12">{{  trans('forms.empty_means_unlimited')  }}</p>
        </div>

        <div class="row mt-15">

            @if($webinar->isWebinar())
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label class="input-label">{{ trans('public.start_date') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text" id="dateInputGroupPrepend">
                                <i data-feather="calendar" width="18" height="18" class="text-white"></i>
                            </span>
                            </div>

                            <input type="date" name="start_date" value="{{ (!empty($webinar) and $webinar->start_date) ? dateTimeFormat($webinar->start_date, 'Y-m-d H:i', false, false, $webinar->timezone) : old('start_date') }}"
                                   class="form-control " aria-describedby="dateInputGroupPrepend"/>
                            @error('start_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-12 @if($webinar->isWebinar()) col-md-6 @endif">
                <div class="form-group">
                    <label class="input-label">{{ trans('public.duration') }} ({{ trans('public.minutes') }})</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="timeInputGroupPrepend">
                                <i data-feather="clock" width="18" height="18" class="text-white"></i>
                            </span>
                        </div>


                        <input type="text" name="duration" value="{{ (!empty($webinar) and !empty($webinar->duration)) ? $webinar->duration : old('duration') }}" class="form-control @error('duration')  is-invalid @enderror"/>
                        @error('duration')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        @if($webinar->isWebinar() and getFeaturesSettings('timezone_in_create_webinar'))
            @php
                $selectedTimezone = getGeneralSettings('default_time_zone');

                if (!empty($webinar->timezone)) {
                    $selectedTimezone = $webinar->timezone;
                } elseif (!empty($authUser) and !empty($authUser->timezone)) {
                    $selectedTimezone = $authUser->timezone;
                }
            @endphp

            <div class="form-group">
                <label class="input-label">{{ trans('update.timezone') }}</label>
                <select name="timezone" class="custom-select" data-allow-clear="false">
                    @foreach(getListOfTimezones() as $timezone)
                        <option value="{{ $timezone }}" @if($selectedTimezone == $timezone) selected @endif>{{ $timezone }}</option>
                    @endforeach
                </select>
                @error('timezone')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        @endif

        @if(!empty(getFeaturesSettings("course_forum_status")))
            <div class="form-group mt-30 d-flex align-items-center justify-content-between mb-5">
                <label class="cursor-pointer input-label" for="forumSwitch">{{ trans('update.course_forum') }}</label>
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="forum" class="custom-control-input" id="forumSwitch" {{ !empty($webinar) && $webinar->forum ? 'checked' : (old('forum') ? 'checked' : '')  }}>
                    <label class="custom-control-label" for="forumSwitch"></label>
                </div>
            </div>

            <div>
                <p class="font-12 text-gray">- {{ trans('update.panel_course_forum_hint') }}</p>
            </div>
        @endif

        <div class="form-group mt-30 d-flex align-items-center justify-content-between">
            <label class="cursor-pointer input-label" for="supportSwitch">{{ trans('webinars.support') }}</label>
            <div class="custom-control custom-switch">
                <input type="checkbox" name="support" class="custom-control-input" id="supportSwitch" {{ ((!empty($webinar) && $webinar->support) or old('support') == 'on') ? 'checked' :  '' }}>
                <label class="custom-control-label" for="supportSwitch"></label>
            </div>
        </div>

        @if(!empty(getCertificateMainSettings("status")))
            <div class="form-group mt-30 d-flex align-items-center justify-content-between">
                <label class="cursor-pointer input-label" for="certificateSwitch">{{ trans('update.include_certificate') }}</label>
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="certificate" class="custom-control-input" id="certificateSwitch" {{ ((!empty($webinar) && $webinar->certificate) or old('certificate') == 'on') ? 'checked' :  '' }}>
                    <label class="custom-control-label" for="certificateSwitch"></label>
                </div>
            </div>

            <div>
                <p class="font-12 text-gray">- {{ trans('update.certificate_completion_hint') }}</p>
            </div>
        @endif

        <div class="form-group mt-30 d-flex align-items-center justify-content-between">
            <label class="cursor-pointer input-label" for="downloadableSwitch">{{ trans('home.downloadable') }}</label>
            <div class="custom-control custom-switch">
                <input type="checkbox" name="downloadable" class="custom-control-input" id="downloadableSwitch" {{ ((!empty($webinar) && $webinar->downloadable) or old('downloadable') == 'on') ? 'checked' : '' }}>
                <label class="custom-control-label" for="downloadableSwitch"></label>
            </div>
        </div>

        <div class="form-group mt-30 d-flex align-items-center justify-content-between">
            <label class="cursor-pointer input-label" for="partnerInstructorSwitch">{{ trans('public.partner_instructor') }}</label>
            <div class="custom-control custom-switch">
                <input type="checkbox" name="partner_instructor" class="custom-control-input" id="partnerInstructorSwitch" {{ ((!empty($webinar) && $webinar->partner_instructor) or old('partner_instructor') == 'on') ? 'checked' : ''  }}>
                <label class="custom-control-label" for="partnerInstructorSwitch"></label>
            </div>
        </div>


        <div id="partnerInstructorInput"
            class="form-group mt-25 kemetic-partner-box
            {{ ((!empty($webinar) && $webinar->partner_instructor) or old('partner_instructor') == 'on') ? '' : 'd-none' }}">

            <label class="input-label kemetic-label">
                {{ trans('public.select_a_partner_teacher') }}
            </label>

            <select name="partners[]"
                    class="form-control kemetic-select2 panel-search-user-select2
                    @error('partners') is-invalid @enderror"
                    multiple
                    data-search-option="just_teachers"
                    data-placeholder="{{ trans('public.search_instructor') }}">

                @if(!empty($webinar->webinarPartnerTeacher))
                    @foreach($webinar->webinarPartnerTeacher as $partnerTeacher)
                        <option selected value="{{ $partnerTeacher->teacher->id }}">
                            {{ $partnerTeacher->teacher->full_name }}
                        </option>
                    @endforeach
                @endif
            </select>

            <div class="kemetic-hint">
                {{ trans('admin/main.invited_instructor_hint') }}
            </div>

            @error('partners')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
            @enderror
        </div>


        <div class="form-group">
            <label class="input-label d-block">{{ trans('public.tags') }}</label>
            <input type="text" name="tags" data-max-tag="5" value="{{ !empty($webinar) ? implode(',',$webinarTags) : '' }}" class="form-control inputtags" placeholder="{{ trans('public.type_tag_name_and_press_enter') }} ({{ trans('forms.max') }} : 5)"/>
        </div>


        <div class="form-group mt-15">
            <label class="input-label">{{ trans('public.category') }}</label>

            <select id="categories" class="custom-select @error('category_id')  is-invalid @enderror" name="category_id" required>
                <option {{ (!empty($webinar) and !empty($webinar->category_id)) ? '' : 'selected' }} disabled>{{ trans('public.choose_category') }}</option>
                @foreach($categories as $category)
                    @if(!empty($category->subCategories) and $category->subCategories->count() > 0)
                        <optgroup label="{{  $category->title }}">
                            @foreach($category->subCategories as $subCategory)
                                <option value="{{ $subCategory->id }}" {{ ((!empty($webinar) and $webinar->category_id == $subCategory->id) or old('category_id') == $subCategory->id) ? 'selected' : '' }}>{{ $subCategory->title }}</option>
                            @endforeach
                        </optgroup>
                    @else
                        <option value="{{ $category->id }}" {{ ((!empty($webinar) and $webinar->category_id == $category->id) or old('category_id') == $category->id) ? 'selected' : '' }}>{{ $category->title }}</option>
                    @endif
                @endforeach
            </select>
            @error('category_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

    </div>
</div>

<div class="form-group mt-15 {{ (!empty($webinarCategoryFilters) and count($webinarCategoryFilters)) ? '' : 'd-none' }}" id="categoriesFiltersContainer">
    <span class="input-label d-block">{{ trans('public.category_filters') }}</span>
    <div id="categoriesFiltersCard" class="row mt-20">

        @if(!empty($webinarCategoryFilters) and count($webinarCategoryFilters))
            @foreach($webinarCategoryFilters as $filter)
                <div class="col-12 col-md-3">
                    <div class="webinar-category-filters">
                        <strong class="category-filter-title d-block">{{ $filter->title }}</strong>
                        <div class="py-10"></div>

                        @php
                            $webinarFilterOptions = $webinar->filterOptions->pluck('filter_option_id')->toArray();

                            if (!empty(old('filters'))) {
                                $webinarFilterOptions = array_merge($webinarFilterOptions, old('filters'));
                            }
                        @endphp

                        @foreach($filter->options as $option)
                            <div class="form-group mt-10 d-flex align-items-center justify-content-between">
                                <label class="cursor-pointer font-14 text-gray" for="filterOptions{{ $option->id }}">{{ $option->title }}</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="filters[]" value="{{ $option->id }}" {{ ((!empty($webinarFilterOptions) && in_array($option->id, $webinarFilterOptions)) ? 'checked' : '') }} class="custom-control-input" id="filterOptions{{ $option->id }}">
                                    <label class="custom-control-label" for="filterOptions{{ $option->id }}"></label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif

    </div>
</div>
</div>

@push('scripts_bottom')
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
@endpush
