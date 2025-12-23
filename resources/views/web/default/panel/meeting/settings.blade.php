@extends('web.default.layouts.newapp')

@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/bootstrap-clockpicker/bootstrap-clockpicker.min.css">

<style>
.kemetic-form {
    color: #f2c94c;
    font-family: 'Poppins', sans-serif;
}

.kemetic-section-title {
    font-size: 22px;
    font-weight: 600;
    color: #f2c94c;
    border-left: 4px solid #f2c94c;
    padding-left: 12px;
    margin-bottom: 15px;
}

.kemetic-card {
    background-color: #0f0f0f;
    border: 1px solid rgba(242,201,76,0.3);
    border-radius: 14px;
    box-shadow: 0 0 20px rgba(242,201,76,0.1);
}

.kemetic-table thead {
    background-color: #1a1a1a;
    color: #f2c94c;
}

.kemetic-table tbody tr td {
    background-color: #0f0f0f;
    color: #f2c94c;
    border-bottom: 1px solid rgba(242,201,76,0.2);
}

.kemetic-time-block {
    background-color: #1a1a1a;
    border: 1px solid rgba(242,201,76,0.3);
    border-radius: 6px;
    padding: 4px 8px;
}

.kemetic-label {
    font-weight: 500;
    color: #f2c94c;
}

.kemetic-input-group .input-group-text {
    background-color: #f2c94c;
    color: #0f0f0f;
}

.btn-gold {
    background-color: #f2c94c;
    color: #0f0f0f;
    border-radius: 8px;
    border: none;
    transition: 0.3s;
}

.btn-gold:hover {
    background-color: #e0b539;
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
  position: absolute;
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
.kemetic-modal {
    background-color: #0f0f0f;
    border: 1px solid rgba(242, 201, 76, 0.3);
    border-radius: 14px;
    box-shadow: 0 0 20px rgba(242, 201, 76, 0.2);
    color: #f2c94c;
    font-family: 'Poppins', sans-serif;
    padding: 10px;
}

.kemetic-modal .input-label {
    color: #f2c94c;
    font-weight: 500;
}

.kemetic-modal .form-control {
    background-color: #1a1a1a;
    color: #f2c94c;
    border: 1px solid rgba(242, 201, 76, 0.3);
    border-radius: 6px;
}

.kemetic-modal select.form-control {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}

.kemetic-modal .btn-gold {
    background-color: #f2c94c;
    color: #0f0f0f;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.kemetic-modal .btn-gold:hover {
    background-color: #e0b539;
}

.kemetic-modal .from-time,
.kemetic-modal .to-time {
    font-weight: 700;
}

.kemetic-modal .pulsate {
    animation: pulsate 1.2s infinite;
}

@keyframes pulsate {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.05); opacity: 0.9; }
    100% { transform: scale(1); opacity: 1; }
}

</style>
@endpush

@section('content')

<form action="/panel/meetings/{{ $meeting->id }}/update" method="post" class="kemetic-form">
    {{ csrf_field() }}

    <!-- Timesheet Header -->
    <section class="mt-35">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <h2 class="kemetic-section-title">{{ trans('panel.my_timesheet') }}</h2>

            <div class="d-flex align-items-center flex-row-reverse flex-md-row justify-content-start justify-content-md-center mt-20 mt-md-0">
                <label class="mb-0 me-2 cursor-pointer kemetic-label" for="temporaryDisableMeetingsSwitch">{{ trans('panel.temporary_disable_meetings') }}</label>
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="disabled" class="custom-control-input" id="temporaryDisableMeetingsSwitch" {{ $meeting->disabled ? 'checked' : '' }}>
                    <label class="custom-control-label" for="temporaryDisableMeetingsSwitch"></label>
                </div>
            </div>
        </div>

        <!-- Timesheet Table -->
        <div class="kemetic-card mt-20 p-20">
            <div class="table-responsive">
                <table class="table kemetic-table text-center">
                    <thead class="table-dark text-gold">
                        <tr>
                            <th class="text-start">{{ trans('public.day') }}</th>
                            <th class="text-start">{{ trans('public.availability_times') }}</th>
                            <th class="text-end">{{ trans('public.controls') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\MeetingTime::$days as $day)
                        <tr id="{{ $day }}TimeSheet" data-day="{{ $day }}">
                            <td class="text-start">
                                <span class="fw-bold text-gold">{{ trans('panel.'.$day) }}</span>
                                <span class="d-block text-gray small">{{ isset($meetingTimes[$day]) ? $meetingTimes[$day]["hours_available"] : 0 }} {{ trans('home.hours') .' '. trans('public.available') }}</span>
                            </td>
                            <td class="time-sheet-items text-start align-middle">
                                @if(isset($meetingTimes[$day]))
                                    @foreach($meetingTimes[$day]["times"] as $time)
                                        <div class="kemetic-time-block position-relative d-inline-block px-3 py-1 me-2 mb-2 rounded bg-dark-gray">
                                            <span class="text-gold small">
                                                {{ $time->time }} - {{ trans('update.'.($time->meeting_type == 'all' ? 'both' : $time->meeting_type)) }}
                                            </span>
                                            <span data-time-id="{{ $time->id }}" class="remove-time rounded-circle bg-danger">
                                                <i data-feather="x" width="12" height="12"></i>
                                            </span>
                                        </div>
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-end align-middle">
                                <div class="btn-group dropdown">
                                    <button type="button" class="btn btn-sm btn-dark text-gold dropdown-toggle" data-bs-toggle="dropdown">
                                        <i data-feather="more-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><button type="button" class="add-time dropdown-item">{{ trans('public.add_time') }}</button></li>
                                        @if(isset($meetingTimes[$day]) and !empty($meetingTimes[$day]["hours_available"]) and $meetingTimes[$day]["hours_available"] > 0)
                                            <li><button type="button" class="clear-all dropdown-item">{{ trans('public.clear_all') }}</button></li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Hourly Charge -->
    <section class="mt-30">
        <h2 class="kemetic-section-title after-line">{{ trans('panel.my_hourly_charge') }}</h2>
        <div class="row mt-20 g-3">
            <div class="col-12 col-md-3">
                <label class="kemetic-label">{{ trans('panel.amount') }}</label>
                <div class="input-group kemetic-input-group">
                    <span class="input-group-text bg-gold text-dark">{{ $currency }}</span>
                    <input type="number" name="amount" value="{{ !empty($meeting) ? convertPriceToUserCurrency($meeting->amount) : old('amount') }}" class="form-control" placeholder="{{ trans('panel.number_only') }}">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <label class="kemetic-label">{{ trans('panel.discount') }} (%)</label>
                <div class="input-group kemetic-input-group">
                    <span class="input-group-text bg-gold text-dark">%</span>
                    <input type="number" name="discount" value="{{ !empty($meeting) ? $meeting->discount : old('discount') }}" class="form-control" placeholder="{{ trans('panel.number_only') }}">
                </div>
            </div>
        </div>
    </section>

    <!-- In-Person Meetings -->
    <section class="mt-30">
        <h2 class="kemetic-section-title after-line">{{ trans('update.in_person_meetings') }}</h2>
        <div class="row g-3">
            <div class="col-12 col-lg-3">
                <div class="d-flex justify-content-between align-items-center">
                    <label class="kemetic-label cursor-pointer" for="inPersonMeetingSwitch">{{ trans('update.available_for_in_person_meetings') }}</label>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="in_person" class="custom-control-input" id="inPersonMeetingSwitch" {{ ((!empty($meeting) and $meeting->in_person) or old('in_person') == 'on') ? 'checked' :  '' }}>
                        <label class="custom-control-label" for="inPersonMeetingSwitch"></label>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-3 {{ ((!empty($meeting) and $meeting->in_person) or old('in_person') == 'on') ? '' : 'd-none' }}" id="inPersonMeetingAmount">
                <label class="kemetic-label">{{ trans('update.hourly_amount') }}</label>
                <div class="input-group kemetic-input-group">
                    <span class="input-group-text bg-gold text-dark">{{ $currency }}</span>
                    <input type="number" name="in_person_amount" value="{{ !empty($meeting) ? convertPriceToUserCurrency($meeting->in_person_amount) : old('in_person_amount') }}" class="form-control" placeholder="{{ trans('panel.number_only') }}">
                </div>
            </div>
        </div>
    </section>

    <!-- Group Meeting -->
    <section class="mt-30">
        <h2 class="kemetic-section-title after-line">{{ trans('update.group_meeting') }}</h2>
        <div class="row g-3">
            <div class="col-12 col-lg-3">
                <div class="d-flex justify-content-between align-items-center">
                    <label class="kemetic-label cursor-pointer" for="groupMeetingSwitch">{{ trans('update.available_for_group_meeting') }}</label>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="group_meeting" class="custom-control-input" id="groupMeetingSwitch" {{ ((!empty($meeting) and $meeting->group_meeting) or old('group_meeting') == 'on') ? 'checked' :  '' }}>
                        <label class="custom-control-label" for="groupMeetingSwitch"></label>
                    </div>
                </div>
            </div>

            <!-- Online Group Options -->
            <div class="col-12 mt-3 {{ ((!empty($meeting) and $meeting->group_meeting) or old('group_meeting') == 'on') ? '' : 'd-none' }}" id="onlineGroupMeetingOptions">
                <h4 class="text-gold fw-bold small">{{ trans('update.online_group_meeting_options') }}</h4>
                <div class="row g-3 mt-2">
                    <div class="col-12 col-lg-3">
                        <label class="kemetic-label">{{ trans('update.minimum_students') }}</label>
                        <input type="number" min="2" name="online_group_min_student" value="{{ !empty($meeting) ? $meeting->online_group_min_student : old('online_group_min_student') }}" class="form-control" placeholder="{{ trans('panel.number_only') }}">
                    </div>
                    <div class="col-12 col-lg-3">
                        <label class="kemetic-label">{{ trans('update.maximum_students') }}</label>
                        <input type="number" name="online_group_max_student" value="{{ !empty($meeting) ? $meeting->online_group_max_student : old('online_group_max_student') }}" class="form-control" placeholder="{{ trans('panel.number_only') }}">
                    </div>
                    <div class="col-12 col-lg-3">
                        <label class="kemetic-label">{{ trans('update.hourly_amount') }}</label>
                        <div class="input-group kemetic-input-group">
                            <span class="input-group-text bg-gold text-dark">{{ $currency }}</span>
                            <input type="text" name="online_group_amount" value="{{ !empty($meeting) ? convertPriceToUserCurrency($meeting->online_group_amount) : old('online_group_amount') }}" class="form-control" placeholder="{{ trans('panel.number_only') }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- In-Person Group Options -->
            <div class="col-12 mt-3 {{ ((!empty($meeting) and $meeting->group_meeting and $meeting->in_person) or (old('group_meeting') == 'on' and old('in_person') == 'on')) ? '' : 'd-none' }}" id="inPersonGroupMeetingOptions">
                <h4 class="text-gold fw-bold small">{{ trans('update.in_person_group_meeting_options') }}</h4>
                <div class="row g-3 mt-2">
                    <div class="col-12 col-lg-3">
                        <label class="kemetic-label">{{ trans('update.minimum_students') }}</label>
                        <input type="number" min="2" name="in_person_group_min_student" value="{{ !empty($meeting) ? $meeting->in_person_group_min_student : old('in_person_group_min_student') }}" class="form-control" placeholder="{{ trans('panel.number_only') }}">
                    </div>
                    <div class="col-12 col-lg-3">
                        <label class="kemetic-label">{{ trans('update.maximum_students') }}</label>
                        <input type="number" name="in_person_group_max_student" value="{{ !empty($meeting) ? $meeting->in_person_group_max_student : old('in_person_group_max_student') }}" class="form-control" placeholder="{{ trans('panel.number_only') }}">
                    </div>
                    <div class="col-12 col-lg-3">
                        <label class="kemetic-label">{{ trans('update.hourly_amount') }}</label>
                        <div class="input-group kemetic-input-group">
                            <span class="input-group-text bg-gold text-dark">{{ $currency }}</span>
                            <input type="text" name="in_person_group_amount" value="{{ !empty($meeting) ? convertPriceToUserCurrency($meeting->in_person_group_amount) : old('in_person_group_amount') }}" class="form-control" placeholder="{{ trans('panel.number_only') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-20">
            <button type="submit" class="btn btn-gold">{{ trans('public.submit') }}</button>
        </div>
</form>

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/bootstrap-clockpicker/bootstrap-clockpicker.min.js"></script>
    <script type="text/javascript">
        var saveLang = '{{ trans('public.save') }}';
        var closeLang = '{{ trans('public.close') }}';
        var successDeleteTime = '{{ trans('meeting.success_delete_time') }}';
        var errorDeleteTime = '{{ trans('meeting.error_delete_time') }}';
        var successSavedTime = '{{ trans('meeting.success_save_time') }}';
        var errorSavingTime = '{{ trans('meeting.error_saving_time') }}';
        var noteToTimeMustGreater = '{{ trans('meeting.note_to_time_must_greater_from_time') }}';
        var requestSuccess = '{{ trans('public.request_success') }}';
        var requestFailed = '{{ trans('public.request_failed') }}';
        var saveMeetingSuccessLang = '{{ trans('meeting.save_meeting_setting_success') }}';
        var meetingTypeLang = '{{ trans('update.meeting_type') }}';
        var inPersonLang = '{{ trans('update.in_person') }}';
        var onlineLang = '{{ trans('update.online') }}';
        var bothLang = '{{ trans('update.both') }}';
        var descriptionLng = '{{ trans('public.description') }}';
        var saveTimeDescriptionPlaceholder = '{{ trans('update.save_time_description_placeholder') }}';

        var toTimepicker, fromTimepicker;
    </script>
    <script src="/assets/default/js/panel/meeting/meeting.min.js"></script>
@endpush
