@extends('web.default.layouts.newapp')

@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
<link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">

<style>
/* ================= KEMETIC DESIGN SYSTEM ================= */
:root{
    --k-bg:#0f131a;
    --k-card:#161b26;
    --k-border:#262c3a;
    --k-gold:#F2C94C;
    --k-text:#e5e7eb;
    --k-muted:#9ca3af;
    --k-radius:16px;
}

.k-section-title{
    color:var(--k-gold);
    font-weight:700;
}

.k-card{
    background:var(--k-card);
    border:1px solid var(--k-border);
    border-radius:var(--k-radius);
}

.k-stat strong{
    color:var(--k-gold);
}

.k-label{
    color:var(--k-muted);
    font-weight:500;
}

.k-input,
.k-select{
    background:#0f131a;
    border:1px solid var(--k-border);
    color:var(--k-text);
}

.k-input:focus,
.k-select:focus{
    border-color:var(--k-gold);
}

.k-btn{
    background:linear-gradient(135deg,#F2C94C,#e0b93d);
    color:#000;
    font-weight:600;
    border-radius:12px;
}

.custom-table thead th{
    color:var(--k-muted);
}

.custom-table tbody td{
    color:var(--k-text);
}

.avatar{
    border-radius:50%;
    overflow:hidden;
}

.kemetic-section {
    padding: 15px 0;
}

.kemetic-title {
    font-size: 22px;
    font-weight: 600;
    color: #f2c94c; /* Gold */
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

.kemetic-input-group .input-group-text {
    background-color: #f2c94c;
    color: #0f0f0f;
    border-radius: 6px 0 0 6px;
}

.kemetic-select {
    background-color: #1a1a1a;
    color: #f2c94c;
    border: 1px solid rgba(242,201,76,0.3);
    border-radius: 6px;
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
  padding-left: 4.125rem;
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

@section('content')

    {{-- ================= STATISTICS ================= --}}
    <section>
        <h2 class="section-title k-section-title">{{ trans('panel.meeting_statistics') }}</h2>

        <div class="k-card mt-25 p-25" style="padding: 10px;">
            <div class="row text-center">

                <div class="col-6 col-md-3 k-stat">
                    <img src="/assets/default/img/activity/49.svg" width="60">
                    <strong class="d-block font-28 mt-15">{{ $pendingReserveCount }}</strong>
                    <span class="k-label">{{ trans('panel.pending_appointments') }}</span>
                </div>

                <div class="col-6 col-md-3 k-stat">
                    <img src="/assets/default/img/activity/50.svg" width="60">
                    <strong class="d-block font-28 mt-15">{{ $totalReserveCount }}</strong>
                    <span class="k-label">{{ trans('panel.total_meetings') }}</span>
                </div>

                <div class="col-6 col-md-3 k-stat mt-20 mt-md-0">
                    <img src="/assets/default/img/activity/38.svg" width="60">
                    <strong class="d-block font-28 mt-15">{{ handlePrice($sumReservePaid) }}</strong>
                    <span class="k-label">{{ trans('panel.sales_amount') }}</span>
                </div>

                <div class="col-6 col-md-3 k-stat mt-20 mt-md-0">
                    <img src="/assets/default/img/activity/hours.svg" width="60">
                    <strong class="d-block font-28 mt-15">{{ convertMinutesToHourAndMinute($activeHoursCount / 60) }}</strong>
                    <span class="k-label">{{ trans('panel.active_hours') }}</span>
                </div>

            </div>
        </div>
    </section>

    <section class="mt-35 kemetic-section">
        <h2 class="kemetic-title">{{ trans('panel.filter_meetings') }}</h2>

        <div class="kemetic-card mt-20 p-25" style="padding: 10px;">
             <form action="/panel/meetings/requests" method="get" class="row">
                <!-- Date Range -->
                <div class="col-12 col-lg-4">
                    <div class="row gx-2 gy-2">
                        <div class="col-6">
                            <label class="form-label">{{ trans('public.from') }}</label>
                            <div class="input-group kemetic-input-group">
                                <span class="input-group-text bg-gold text-dark">
                                    <i data-feather="calendar" width="18" height="18"></i>
                                </span>
                                <input type="text" name="from" autocomplete="off" 
                                    class="form-control @if(request()->get('from')) datepicker @else datefilter @endif" 
                                    value="{{ request()->get('from','') }}">
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label">{{ trans('public.to') }}</label>
                            <div class="input-group kemetic-input-group">
                                <span class="input-group-text bg-gold text-dark">
                                    <i data-feather="calendar" width="18" height="18"></i>
                                </span>
                                <input type="text" name="to" autocomplete="off" 
                                    class="form-control @if(request()->get('to')) datepicker @else datefilter @endif" 
                                    value="{{ request()->get('to','') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Day & Instructor -->
                <div class="col-12 col-lg-6">
                    <div class="row gx-2 gy-2">
                        <div class="col-4">
                            <label class="form-label">{{ trans('public.day') }}</label>
                            <select class="form-control kemetic-select" name="day">
                                <option value="all">{{ trans('public.all_days') }}</option>
                                <option value="saturday" {{ (request()->get('day') === "saturday") ? 'selected' : '' }}>{{ trans('public.saturday') }}</option>
                                <option value="sunday" {{ (request()->get('day') === "sunday") ? 'selected' : '' }}>{{ trans('public.sunday') }}</option>
                                <option value="monday" {{ (request()->get('day') === "monday") ? 'selected' : '' }}>{{ trans('public.monday') }}</option>
                                <option value="tuesday" {{ (request()->get('day') === "tuesday") ? 'selected' : '' }}>{{ trans('public.tuesday') }}</option>
                                <option value="wednesday" {{ (request()->get('day') === "wednesday") ? 'selected' : '' }}>{{ trans('public.wednesday') }}</option>
                                <option value="thursday" {{ (request()->get('day') === "thursday") ? 'selected' : '' }}>{{ trans('public.thursday') }}</option>
                                <option value="friday" {{ (request()->get('day') === "friday") ? 'selected' : '' }}>{{ trans('public.friday') }}</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <label class="form-label">{{ trans('quiz.student') }}</label>
                              <select name="student_id" class="form-control kemetic-select">
                            <option value="all">{{ trans('webinars.all_students') }}</option>
                            @foreach($usersReservedTimes as $student)
                                <option value="{{ $student->id }}" {{ request('student_id')==$student->id?'selected':'' }}>
                                    {{ $student->full_name }}
                                </option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-4 mt-2">
                            <label class="form-label">{{ trans('public.status') }}</label>
                            <select class="form-control kemetic-select" name="status">
                                <option value="all">{{ trans('public.all') }}</option>
                                <option value="open" {{ (request()->get('status') === "open") ? 'selected' : '' }}>{{ trans('public.open') }}</option>
                                <option value="finished" {{ (request()->get('status') === "finished") ? 'selected' : '' }}>{{ trans('public.finished') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="col-12 col-lg-2 d-flex align-items-end justify-content-end">
                    <button type="submit" class="btn btn-gold w-100">{{ trans('public.show_results') }}</button>
                </div>
            </form>
        </div>
    </section>

    


    <section class="mt-35 pb-50 mb-50">
        <form action="/panel/meetings/requests?{{ http_build_query(request()->all()) }}" method="get" class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
            <h2 class="section-title k-section-title">{{ trans('panel.meeting_requests_list') }}</h2>

            <div class="d-flex align-items-center flex-row-reverse flex-md-row justify-content-start justify-content-md-center mt-20 mt-md-0">
                <label class="cursor-pointer mb-0 mr-10 text-gray font-14 font-weight-500" for="openMeetingResult">{{ trans('panel.show_only_open_meetings') }}</label>
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="open_meetings" {{ (request()->get('open_meetings', '') == 'on') ? 'checked' : '' }} class="js-panel-list-switch-filter custom-control-input" id="openMeetingResult">
                    <label class="custom-control-label" for="openMeetingResult"></label>
                </div>
            </div>
        </form>

        @if($reserveMeetings->count() > 0)

            <div class="panel-section-card py-20 px-25 mt-20">
                <div class="row">
                    <div class="col-12 ">
                        <div class="table-responsive">
                            <table class="table text-center custom-table">
                                <thead>
                                <tr>
                                    <th>{{ trans('quiz.student') }}</th>
                                    <th class="text-center">{{ trans('update.meeting_type') }}</th>
                                    <th class="text-center">{{ trans('public.day') }}</th>
                                    <th class="text-center">{{ trans('public.date') }}</th>
                                    <th class="text-center">{{ trans('public.time') }}</th>
                                    <th class="text-center">{{ trans('public.paid_amount') }}</th>
                                    <th class="text-center">{{ trans('update.students_count') }}</th>
                                    <th class="text-center">{{ trans('public.status') }}</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($reserveMeetings as $ReserveMeeting)
                                    <tr>
                                        <td class="text-left">
                                            <div class="user-inline-avatar d-flex align-items-center">
                                                <div class="avatar bg-gray200">
                                                    <img src="{{ $ReserveMeeting->user->getAvatar() }}" class="img-cover" alt="">
                                                </div>
                                                <div class=" ml-5">
                                                    <span class="d-block font-weight-500">{{ $ReserveMeeting->user->full_name }}</span>
                                                    <span class="mt-5 font-12 text-gray d-block">{{ $ReserveMeeting->user->email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <span class="font-weight-500">{{ trans('update.'.$ReserveMeeting->meeting_type) }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="font-weight-500">{{ dateTimeFormat($ReserveMeeting->start_at, 'D') }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <span>{{ dateTimeFormat($ReserveMeeting->start_at, 'j M Y') }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-inline-flex align-items-center rounded bg-gray200 py-5 px-15 font-14 font-weight-500">
                                                <span class="">{{ dateTimeFormat($ReserveMeeting->start_at, 'H:i') }}</span>
                                                <span class="mx-1">-</span>
                                                <span class="">{{ dateTimeFormat($ReserveMeeting->end_at, 'H:i') }}</span>
                                            </div>
                                        </td>

                                        <td class="font-weight-500 align-middle">{{ handlePrice($ReserveMeeting->paid_amount) }}</td>

                                        <td class="align-middle font-weight-500">
                                            {{ $ReserveMeeting->student_count ?? 1 }}
                                        </td>

                                        <td class="align-middle">
                                            @switch($ReserveMeeting->status)
                                                @case(\App\Models\ReserveMeeting::$pending)
                                                    <span class="font-weight-500">{{ trans('public.pending') }}</span>
                                                    @break
                                                @case(\App\Models\ReserveMeeting::$open)
                                                    <span class="text-primary font-weight-500">{{ trans('public.open') }}</span>
                                                    @break
                                                @case(\App\Models\ReserveMeeting::$finished)
                                                    <span class="font-weight-500">{{ trans('public.finished') }}</span>
                                                    @break
                                                @case(\App\Models\ReserveMeeting::$canceled)
                                                    <span class="font-weight-500">{{ trans('public.canceled') }}</span>
                                                    @break
                                            @endswitch
                                        </td>

                                        <td class="align-middle text-right">
                                            @if($ReserveMeeting->status != \App\Models\ReserveMeeting::$finished)
                                                <input type="hidden" class="js-meeting-password-{{ $ReserveMeeting->id }}" value="{{ $ReserveMeeting->password }}">
                                                <input type="hidden" class="js-meeting-link-{{ $ReserveMeeting->id }}" value="{{ $ReserveMeeting->link }}">


                                                <div class="btn-group dropdown table-actions">
                                                    <button type="button" class="btn-transparent dropdown-toggle"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                        <i data-feather="more-vertical" height="20"></i>
                                                    </button>
                                                    <div class="dropdown-menu menu-lg">

                                                        @if(getFeaturesSettings('agora_for_meeting') and $ReserveMeeting->meeting_type != 'in_person')
                                                            @if(empty($ReserveMeeting->session))
                                                                <button type="button" data-item-id="{{ $ReserveMeeting->id }}" data-date="{{ dateTimeFormat($ReserveMeeting->start_at, 'j M Y H:i') }}"
                                                                        class="js-add-meeting-session btn-transparent webinar-actions d-block mt-10 text-primary">{{ trans('update.create_a_session') }}</button>
                                                            @elseif($ReserveMeeting->status == \App\Models\ReserveMeeting::$open)
                                                                <button type="button" data-item-id="{{ $ReserveMeeting->id }}" data-date="{{ dateTimeFormat($ReserveMeeting->start_at, 'j M Y H:i') }}" data-link="{{ $ReserveMeeting->session->getJoinLink() }}"
                                                                        class="js-join-meeting-session btn-transparent webinar-actions d-block mt-10 text-primary">{{ trans('update.join_to_session') }}</button>
                                                            @endif
                                                        @endif


                                                        @if($ReserveMeeting->meeting_type != 'in_person' and !empty($ReserveMeeting->link) and $ReserveMeeting->status == \App\Models\ReserveMeeting::$open)
                                                            <button type="button" data-reserve-id="{{ $ReserveMeeting->id }}"
                                                                    class="js-join-reserve btn-transparent webinar-actions d-block mt-10">{{ trans('footer.join') }}</button>
                                                        @endif

                                                        @if($ReserveMeeting->meeting_type != 'in_person')
                                                            <button type="button" data-item-id="{{ $ReserveMeeting->id }}"
                                                                    class="add-meeting-url btn-transparent webinar-actions d-block mt-10">{{ trans('panel.create_link') }}</button>
                                                        @endif

                                                        <a href="{{ $ReserveMeeting->addToCalendarLink() }}" target="_blank" class="webinar-actions d-block mt-10 font-weight-normal">{{ trans('public.add_to_calendar') }}</a>

                                                        <button type="button"
                                                                data-user-id="{{ $ReserveMeeting->user_id }}"
                                                                data-item-id="{{ $ReserveMeeting->id }}"
                                                                data-user-type="student"
                                                                class="contact-info btn-transparent webinar-actions d-block mt-10">{{ trans('panel.contact_student') }}</button>

                                                        <button type="button" data-id="{{ $ReserveMeeting->id }}" class="webinar-actions js-finish-meeting-reserve d-block btn-transparent mt-10 font-weight-normal">{{ trans('panel.finish_meeting') }}</button>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-30">
                {{ $reserveMeetings->appends(request()->input())->links('vendor.pagination.panel') }}
            </div>

        @else
            @include(getTemplate() . '.includes.no-result',[
                'file_name' => 'meeting.png',
                'title' => trans('panel.meeting_no_result'),
                'hint' => nl2br(trans('panel.meeting_no_result_hint')),
            ])
        @endif
    </section>


    <div class="d-none" id="liveMeetingLinkModal">
        <h3 class="section-title after-line font-20 text-dark-blue mb-25">{{ trans('panel.add_live_meeting_link') }}</h3>

        <form action="/panel/meetings/create-link" method="post">
            <input type="hidden" name="item_id" value="">

            <div class="row">
                <div class="col-12 col-md-8">
                    <div class="form-group">
                        <label class="input-label">{{ trans('panel.url') }}</label>
                        <input type="text" name="link" class="form-control"/>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label class="input-label">{{ trans('auth.password') }} ({{ trans('public.optional') }})</label>
                        <input type="text" name="password" class="form-control"/>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
            <p class="font-weight-500 font-12 text-gray">{{ trans('panel.add_live_meeting_link_hint') }}</p>

            <div class="mt-30 d-flex align-items-center justify-content-end">
                <button type="button" class="js-save-meeting-link btn btn-sm btn-primary">{{ trans('public.save') }}</button>
                <button type="button" class="btn btn-sm btn-danger ml-10 close-swl">{{ trans('public.close') }}</button>
            </div>
        </form>
    </div>

    @include('web.default.panel.meeting.join_modal')
    @include('web.default.panel.meeting.meeting_create_session_modal')
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/select2/select2.min.js"></script>

    <script>
        var instructor_contact_information_lang = '{{ trans('panel.instructor_contact_information') }}';
        var student_contact_information_lang = '{{ trans('panel.student_contact_information') }}';
        var email_lang = '{{ trans('public.email') }}';
        var phone_lang = '{{ trans('public.phone') }}';
        var location_lang = '{{ trans('update.location') }}';
        var close_lang = '{{ trans('public.close') }}';
        var linkSuccessAdd = '{{ trans('panel.add_live_meeting_link_success') }}';
        var linkFailAdd = '{{ trans('panel.add_live_meeting_link_fail') }}';
        var finishReserveHint = '{{ trans('meeting.finish_reserve_modal_hint') }}';
        var finishReserveConfirm = '{{ trans('meeting.finish_reserve_modal_confirm') }}';
        var finishReserveCancel = '{{ trans('meeting.finish_reserve_modal_cancel') }}';
        var finishReserveTitle = '{{ trans('meeting.finish_reserve_modal_title') }}';
        var finishReserveSuccess = '{{ trans('meeting.finish_reserve_modal_success') }}';
        var finishReserveSuccessHint = '{{ trans('meeting.finish_reserve_modal_success_hint') }}';
        var finishReserveFail = '{{ trans('meeting.finish_reserve_modal_fail') }}';
        var finishReserveFailHint = '{{ trans('meeting.finish_reserve_modal_fail_hint') }}';
        var sessionSuccessAdd = '{{ trans('update.add_live_meeting_session_success') }}';
        var youCanJoinTheSessionNowLang = '{{ trans('update.you_can_join_the_session_now') }}';
    </script>

    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/js/panel/meeting/contact-info.min.js"></script>
    <script src="/assets/default/js/panel/meeting/reserve_meeting.min.js"></script>
    <script src="/assets/default/js/panel/meeting/requests.min.js"></script>
@endpush
