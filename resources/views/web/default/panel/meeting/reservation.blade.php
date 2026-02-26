@extends('web.default.layouts.newapp')

<style>
  /* KEMETIC STATS */
.kemetic-stat-section {
    margin-top: 25px;
}

.kemetic-title {
    font-size: 22px;
    font-weight: 700;
    color: #F2C94C;
    margin-bottom: 18px;
}

/* CARD */
.kemetic-stat-card {
    background: linear-gradient(180deg, #121212, #0b0b0b);
    border: 1px solid #262626;
    border-radius: 18px;
    padding: 26px 18px;
}

/* ITEM */
.kemetic-stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
}

/* ICON */
.kemetic-stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: rgba(242, 201, 76, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
}
.kemetic-stat-icon img {
    width: 28px;
    filter: invert(0.9);
}

/* VALUE */
.kemetic-stat-value {
    font-size: 30px;
    font-weight: 700;
    color: #F2C94C;
}

/* LABEL */
.kemetic-stat-label {
    font-size: 14px;
    color: #9a9a9a;
}

/* MOBILE */
@media (max-width: 768px) {
    .kemetic-stat-card {
        padding: 20px 12px;
    }
    .kemetic-stat-value {
        font-size: 24px;
    }
}

/* KEMETIC FILTER */
.kemetic-filter-section {
    color: #eaeaea;
}

.kemetic-title {
    font-size: 22px;
    font-weight: 700;
    color: #F2C94C;
}

/* CARD */
.kemetic-filter-card {
    background: linear-gradient(180deg, #121212, #0a0a0a);
    border: 1px solid #262626;
    border-radius: 18px;
    padding: 26px 22px;
}

/* LABEL */
.kemetic-label {
    font-size: 13px;
    color: #b5b5b5;
    margin-bottom: 6px;
    display: block;
}

/* INPUT GROUP */
.kemetic-input-group {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #0f0f0f;
    border: 1px solid #2a2a2a;
    border-radius: 12px;
    padding: 10px 12px;
}

.kemetic-input-group i {
    color: #F2C94C;
}

/* INPUT */
.kemetic-input {
    width: 100%;
    background: transparent;
    border: none;
    color: #fff;
    outline: none;
    font-size: 14px;
}

/* SELECT2 */
.select2-container--default .select2-selection--single {
    background: #0f0f0f !important;
    border: 1px solid #2a2a2a !important;
    border-radius: 12px !important;
    height: 44px !important;
    display: flex;
    align-items: center;
}
.select2-selection__rendered {
    color: #fff !important;
    line-height: 44px !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 42px !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: #F2C94C transparent transparent transparent !important;
}
.select2-dropdown {
    background: #0f0f0f !important;
    border: 1px solid rgba(242,201,76,.35) !important;
    border-radius: 12px !important;
}
.select2-results__option {
    color: #e0e0e0 !important;
    padding: 10px 14px !important;
}
.select2-results__option--highlighted {
    background: rgba(242,201,76,.15) !important;
    color: #fff !important;
}
.select2-results__option[aria-selected=true] {
    background: rgba(242,201,76,.25) !important;
}
.select2-search--dropdown .select2-search__field {
    background: #0d0d0d !important;
    border: 1px solid rgba(242,201,76,.35) !important;
    color: #fff !important;
    border-radius: 8px !important;
}

/* SELECT */
.kemetic-select {
    background: #0f0f0f;
    border: 1px solid #2a2a2a;
    border-radius: 12px;
    color: #fff;
    padding: 10px 12px;
    width: 100%;
    outline: none;
}
.kemetic-select option {
    background: #0f0f0f;
    color: #fff;
}
.kemetic-select:focus {
    border-color: #F2C94C;
}

/* BUTTON */
.kemetic-btn {
    background: linear-gradient(135deg, #F2C94C, #d4af37);
    border: none;
    border-radius: 14px;
    padding: 12px;
    font-weight: 700;
    color: #000;
    transition: 0.3s ease;
}
.kemetic-btn:hover {
    background: linear-gradient(135deg, #d4af37, #F2C94C);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(242,201,76,0.4);
}
.kemetic-btn-sm {
    padding: 8px 16px;
    font-size: 13px;
}

/* TABLE CARD */
.kemetic-table-card {
    background: linear-gradient(180deg,#121212,#0a0a0a);
    border:1px solid #262626;
    border-radius:18px;
    padding:20px;
}

/* TABLE */
.kemetic-table {
    width:100%;
    border-collapse:separate;
    border-spacing:0 10px;
}
.kemetic-table thead th {
    color:#aaa; 
    font-size:13px;
    font-weight:600; 
    text-align:center;
    padding-bottom: 10px;
}
.kemetic-table thead th.text-left {
    text-align: left;
}
.kemetic-table tbody tr {
    background:#0f0f0f;
    border:1px solid #262626;
    transition: 0.3s ease;
}
.kemetic-table tbody tr:hover {
    background: #1a1a1a;
    border-color: rgba(242,201,76,0.3);
}
.kemetic-table td {
    padding:14px;
    text-align:center;
    vertical-align:middle;
}
.kemetic-table td.text-left { 
    text-align:left; 
}
.kemetic-table td.text-right {
    text-align: right;
}

/* USER AVATAR */
.user-avatar-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}
.user-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid rgba(242,201,76,0.3);
    background: #1a1a1a;
}
.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.user-info {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}
.user-name {
    color: #fff;
    font-weight: 600;
    font-size: 14px;
}
.user-email {
    color: #888;
    font-size: 11px;
}

/* MEETING TYPE */
.meeting-type {
    color: #fff;
    font-weight: 500;
    font-size: 14px;
}

/* DAY BADGE */
.day-badge {
    background: rgba(242,201,76,0.15);
    color: #F2C94C;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
}

/* TIME SLOT */
.time-slot {
    display: inline-flex;
    align-items: center;
    background: #1a1a1a;
    border: 1px solid #262626;
    border-radius: 20px;
    padding: 5px 15px;
    font-size: 13px;
    font-weight: 500;
    color: #fff;
}
.time-slot span {
    color: #F2C94C;
    margin: 0 5px;
}

/* PRICE */
.price-value {
    color: #F2C94C;
    font-weight: 600;
    font-size: 14px;
}
.free-value {
    color: #2ecc71;
    font-weight: 600;
    font-size: 14px;
}

/* STUDENT COUNT */
.student-count {
    color: #fff;
    font-weight: 600;
    font-size: 14px;
}

/* STATUS BADGES */
.status-badge {
    padding:4px 10px;
    border-radius:12px;
    font-size:12px;
    font-weight: 500;
    display: inline-block;
}
.status-badge.pending {
    background: #3d2e1f;
    color: #f39c12;
}
.status-badge.open {
    background: #1f3d2b;
    color: #2ecc71;
}
.status-badge.finished {
    background: #2c3e50;
    color: #3498db;
}
.status-badge.canceled {
    background: #3d1f1f;
    color: #e74c3c;
}

/* ACTIONS DROPDOWN */
.kemetic-actions {
    position: relative;
}
.kemetic-actions button {
    background: transparent;
    border: none;
    color: #F2C94C;
    padding: 5px 10px;
}
.kemetic-actions .dropdown-menu {
    background: #121212;
    border: 1px solid #262626;
    border-radius: 12px;
    padding: 8px 0;
    min-width: 200px;
}
.kemetic-actions .dropdown-item {
    color: #F2C94C;
    padding: 8px 16px;
    font-size: 13px;
    transition: 0.2s ease;
    background: transparent;
    border: none;
    width: 100%;
    text-align: left;
}
.kemetic-actions .dropdown-item:hover {
    background: rgba(242,201,76,0.1);
    color: #fff;
}
.kemetic-actions .dropdown-item.text-primary {
    color: #2ecc71 !important;
}
.kemetic-actions .dropdown-item.text-danger {
    color: #e74c3c !important;
}

/* SWITCH */
.kemetic-switch-wrapper {
    display: flex;
    align-items: center;
    gap: 12px;
}
.kemetic-switch-label {
    color: #b5b5b5;
    font-size: 14px;
}
.kemetic-switch {
    position: relative;
    width: 46px;
    height: 24px;
}
.kemetic-switch input {
    display: none;
}
.kemetic-slider {
    position: absolute;
    inset: 0;
    background: #2a2a2a;
    border-radius: 30px;
    cursor: pointer;
}
.kemetic-slider:before {
    content: "";
    position: absolute;
    width: 18px;
    height: 18px;
    background: #F2C94C;
    border-radius: 50%;
    top: 3px;
    left: 4px;
    transition: .3s;
}
.kemetic-switch input:checked + .kemetic-slider:before {
    transform: translateX(20px);
}

/* MODAL */
.modal-content {
    background: #121212;
    border: 1px solid #262626;
    border-radius: 18px;
}
.modal-header {
    border-bottom: 1px solid #262626;
}
.modal-title {
    color: #F2C94C;
    font-weight: 700;
}
.modal-body {
    color: #eaeaea;
}
.modal-footer {
    border-top: 1px solid #262626;
}
.modal-footer .btn {
    background: #1a1a1a;
    border: 1px solid #262626;
    color: #fff;
    border-radius: 12px;
    padding: 8px 20px;
}
.modal-footer .btn-primary {
    background: linear-gradient(135deg, #F2C94C, #d4af37);
    color: #000;
    border: none;
}
.modal-footer .btn-primary:hover {
    background: linear-gradient(135deg, #d4af37, #F2C94C);
}
.modal-footer .btn-danger {
    background: #3d1f1f;
    color: #e74c3c;
    border: 1px solid #e74c3c;
}
.modal-footer .btn-danger:hover {
    background: #4a2a2a;
}

/* NO RESULT */
.no-result-card {
    background: linear-gradient(180deg,#121212,#0a0a0a);
    border:1px solid #262626;
    border-radius:18px;
    padding:60px 40px;
    text-align: center;
}
.no-result-card img {
    opacity: 0.7;
    margin-bottom: 20px;
    width: 120px;
}
.no-result-card h3 {
    color: #F2C94C;
    font-size: 20px;
    margin-bottom: 10px;
}
.no-result-card p {
    color: #888;
    font-size: 14px;
    max-width: 400px;
    margin: 0 auto;
}
</style>

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
@endpush

@section('content')

    {{-- Statistics --}}
    <section class="kemetic-stat-section">
        <h2 class="kemetic-title">{{ trans('panel.meeting_statistics') }}</h2>

        <div class="kemetic-stat-card">
            <div class="row text-center">
                <div class="col-4">
                    <div class="kemetic-stat-item">
                        <div class="kemetic-stat-icon">
                            <img src="/assets/default/img/activity/49.svg" alt="">
                        </div>
                        <div class="kemetic-stat-value">{{ $openReserveCount }}</div>
                        <div class="kemetic-stat-label">{{ trans('panel.open_meetings') }}</div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="kemetic-stat-item">
                        <div class="kemetic-stat-icon">
                            <img src="/assets/default/img/activity/50.svg" alt="">
                        </div>
                        <div class="kemetic-stat-value">{{ $totalReserveCount }}</div>
                        <div class="kemetic-stat-label">{{ trans('panel.total_meetings') }}</div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="kemetic-stat-item">
                        <div class="kemetic-stat-icon">
                            <img src="/assets/default/img/activity/hours.svg" alt="">
                        </div>
                        <div class="kemetic-stat-value">{{ $activeHoursCount }}</div>
                        <div class="kemetic-stat-label">{{ trans('panel.active_hours') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Filters --}}
    <section class="kemetic-filter-section mt-30">
        <h2 class="kemetic-title">{{ trans('panel.filter_meetings') }}</h2>

        <div class="kemetic-filter-card mt-20">
            <form action="/panel/meetings/reservation" method="get">
                <div class="row g-3">

                    {{-- Date range --}}
                    <div class="col-12 col-lg-4">
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="kemetic-label">{{ trans('public.from') }}</label>
                                <div class="kemetic-input-group">
                                    <!-- <i data-feather="calendar" width="18" height="18"></i> -->
                                    <input type="date" name="from"
                                        class="kemetic-input"
                                        value="{{ request()->get('from') }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="kemetic-label">{{ trans('public.to') }}</label>
                                <div class="kemetic-input-group">
                                    <!-- <i data-feather="calendar" width="18" height="18"></i> -->
                                    <input type="date" name="to"
                                        class="kemetic-input"
                                        value="{{ request()->get('to') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Day & Instructor & Status --}}
                    <div class="col-12 col-lg-6">
                        <div class="row g-2">
                            <div class="col-4">
                                <label class="kemetic-label">{{ trans('public.day') }}</label>
                                <select class="kemetic-select" name="day">
                                    <option value="all">{{ trans('public.all_days') }}</option>
                                    <option value="saturday" {{ request()->get('day') === "saturday" ? 'selected' : '' }}>{{ trans('public.saturday') }}</option>
                                    <option value="sunday" {{ request()->get('day') === "sunday" ? 'selected' : '' }}>{{ trans('public.sunday') }}</option>
                                    <option value="monday" {{ request()->get('day') === "monday" ? 'selected' : '' }}>{{ trans('public.monday') }}</option>
                                    <option value="tuesday" {{ request()->get('day') === "tuesday" ? 'selected' : '' }}>{{ trans('public.tuesday') }}</option>
                                    <option value="wednesday" {{ request()->get('day') === "wednesday" ? 'selected' : '' }}>{{ trans('public.wednesday') }}</option>
                                    <option value="thursday" {{ request()->get('day') === "thursday" ? 'selected' : '' }}>{{ trans('public.thursday') }}</option>
                                    <option value="friday" {{ request()->get('day') === "friday" ? 'selected' : '' }}>{{ trans('public.friday') }}</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="kemetic-label">{{ trans('public.instructor') }}</label>
                                <select name="instructor_id" class="kemetic-select select2" style="width: 100%;">
                                    <option value="all">{{ trans('webinars.all_instructors') }}</option>
                                    @foreach($instructors as $instructor)
                                        <option value="{{ $instructor->id }}" @if(request()->get('instructor_id') == $instructor->id) selected @endif>{{ $instructor->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="kemetic-label">{{ trans('public.status') }}</label>
                                <select class="kemetic-select" name="status">
                                    <option value="all">{{ trans('public.all') }}</option>
                                    <option value="open" {{ request()->get('status') === "open" ? 'selected' : '' }}>{{ trans('public.open') }}</option>
                                    <option value="finished" {{ request()->get('status') === "finished" ? 'selected' : '' }}>{{ trans('public.finished') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="col-12 col-lg-2 d-flex align-items-end">
                        <button type="submit" class="kemetic-btn w-100">
                            {{ trans('public.show_results') }}
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </section>

    {{-- Meeting List --}}
    <section class="kemetic-section mt-40">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-20">
            <h2 class="kemetic-title">{{ trans('panel.meeting_list') }}</h2>

            <form action="/panel/meetings/reservation?{{ http_build_query(request()->all()) }}" method="get" class="mt-15 mt-md-0">
                <div class="kemetic-switch-wrapper">
                    <span class="kemetic-switch-label">{{ trans('panel.show_only_open_meetings') }}</span>
                    <label class="kemetic-switch">
                        <input type="checkbox" name="open_meetings" onchange="this.form.submit()"
                            {{ request('open_meetings') == 'on' ? 'checked' : '' }}>
                        <span class="kemetic-slider"></span>
                    </label>
                </div>
            </form>
        </div>

        @if($reserveMeetings->count() > 0)

            <div class="kemetic-table-card mt-20">
                <div class="table-responsive">
                    <table class="kemetic-table">
                        <thead>
                            <tr>
                                <th class="text-left">{{ trans('public.instructor') }}</th>
                                <th>{{ trans('update.meeting_type') }}</th>
                                <th>{{ trans('public.day') }}</th>
                                <th>{{ trans('public.date') }}</th>
                                <th>{{ trans('public.time') }}</th>
                                <th>{{ trans('public.paid_amount') }}</th>
                                <th>{{ trans('update.students_count') }}</th>
                                <th>{{ trans('public.status') }}</th>
                                <th class="text-right"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reserveMeetings as $ReserveMeeting)
                                <tr>
                                    <td class="text-left">
                                        <div class="user-avatar-cell">
                                            <div class="user-avatar">
                                                <img src="{{ $ReserveMeeting->meeting->creator->getAvatar() }}" alt="">
                                            </div>
                                            <div class="user-info">
                                                <span class="user-name">{{ $ReserveMeeting->meeting->creator->full_name }}</span>
                                                <span class="user-email">{{ $ReserveMeeting->meeting->creator->email }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="meeting-type">{{ trans('update.'.$ReserveMeeting->meeting_type) }}</span>
                                    </td>

                                    <td>
                                        <span class="day-badge">{{ dateTimeFormat($ReserveMeeting->start_at, 'D') }}</span>
                                    </td>

                                    <td>
                                        <span class="meeting-type">{{ dateTimeFormat($ReserveMeeting->start_at, 'j M Y') }}</span>
                                    </td>

                                    <td>
                                        <div class="time-slot">
                                            <span>{{ dateTimeFormat($ReserveMeeting->start_at, 'H:i') }}</span>
                                            <span>-</span>
                                            <span>{{ dateTimeFormat($ReserveMeeting->end_at, 'H:i') }}</span>
                                        </div>
                                    </td>

                                    <td>
                                        @if(!empty($ReserveMeeting->sale) and !empty($ReserveMeeting->sale->total_amount) and $ReserveMeeting->sale->total_amount > 0)
                                            <span class="price-value">{{ handlePrice($ReserveMeeting->sale->total_amount) }}</span>
                                        @else
                                            <span class="free-value">{{ trans('public.free') }}</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="student-count">{{ $ReserveMeeting->student_count ?? 1 }}</span>
                                    </td>

                                    <td>
                                        @switch($ReserveMeeting->status)
                                            @case(\App\Models\ReserveMeeting::$pending)
                                                <span class="status-badge pending">{{ trans('public.pending') }}</span>
                                                @break
                                            @case(\App\Models\ReserveMeeting::$open)
                                                <span class="status-badge open">{{ trans('public.open') }}</span>
                                                @break
                                            @case(\App\Models\ReserveMeeting::$finished)
                                                <span class="status-badge finished">{{ trans('public.finished') }}</span>
                                                @break
                                            @case(\App\Models\ReserveMeeting::$canceled)
                                                <span class="status-badge canceled">{{ trans('public.canceled') }}</span>
                                                @break
                                        @endswitch
                                    </td>

                                    <td class="text-right">
                                        @if(!in_array($ReserveMeeting->status, [\App\Models\ReserveMeeting::$finished, \App\Models\ReserveMeeting::$canceled]))
                                            <input type="hidden" class="js-meeting-password-{{ $ReserveMeeting->id }}" value="{{ $ReserveMeeting->password }}">
                                            <input type="hidden" class="js-meeting-link-{{ $ReserveMeeting->id }}" value="{{ $ReserveMeeting->link }}">

                                            <div class="dropdown kemetic-actions">
                                                <button type="button" data-toggle="dropdown">
                                                    <i data-feather="more-vertical" height="18"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    @if(getFeaturesSettings('agora_for_meeting') and $ReserveMeeting->meeting_type != 'in_person' and $ReserveMeeting->status == \App\Models\ReserveMeeting::$open)
                                                        @if(!empty($ReserveMeeting->session))
                                                            <button type="button" 
                                                                    data-item-id="{{ $ReserveMeeting->id }}" 
                                                                    data-date="{{ dateTimeFormat($ReserveMeeting->start_at, 'j M Y H:i') }}" 
                                                                    data-link="{{ $ReserveMeeting->session->getJoinLink() }}"
                                                                    class="dropdown-item text-primary js-join-meeting-session">
                                                                {{ trans('update.join_to_session') }}
                                                            </button>
                                                        @endif
                                                    @endif

                                                    @if($ReserveMeeting->link and $ReserveMeeting->status == \App\Models\ReserveMeeting::$open)
                                                        <button type="button" 
                                                                data-reserve-id="{{ $ReserveMeeting->id }}"
                                                                class="dropdown-item js-join-reserve">
                                                            {{ trans('footer.join') }}
                                                        </button>
                                                    @endif

                                                    <a href="{{ $ReserveMeeting->addToCalendarLink() }}" target="_blank" class="dropdown-item">
                                                        {{ trans('public.add_to_calendar') }}
                                                    </a>

                                                    <button type="button"
                                                            data-user-id="{{ $ReserveMeeting->meeting->creator_id }}"
                                                            data-item-id="{{ $ReserveMeeting->id }}"
                                                            data-user-type="instructor"
                                                            class="dropdown-item contact-info">
                                                        {{ trans('panel.contact_instructor') }}
                                                    </button>

                                                    <button type="button" 
                                                            data-id="{{ $ReserveMeeting->id }}" 
                                                            class="dropdown-item js-finish-meeting-reserve">
                                                        {{ trans('panel.finish_meeting') }}
                                                    </button>
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

            {{-- Pagination --}}
            <div class="my-30">
                {{ $reserveMeetings->appends(request()->input())->links('vendor.pagination.panel') }}
            </div>

        @else
            <div class="no-result-card">
                @include(getTemplate() . '.includes.no-result',[
                    'file_name' => 'meeting.png',
                    'title' => trans('panel.meeting_no_result'),
                    'hint' => nl2br(trans('panel.meeting_no_result_hint')),
                ])
            </div>
        @endif
    </section>

    @include('web.default.panel.meeting.join_modal')
    @include('web.default.panel.meeting.meeting_create_session_modal')

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                width: '100%',
                theme: 'default'
            });

            // Initialize datepickers
            if ($('.datefilter').length) {
                $('.datefilter').daterangepicker();
            }
        });

        var instructor_contact_information_lang = '{{ trans('panel.instructor_contact_information') }}';
        var student_contact_information_lang = '{{ trans('panel.student_contact_information') }}';
        var email_lang = '{{ trans('public.email') }}';
        var phone_lang = '{{ trans('public.phone') }}';
        var location_lang = '{{ trans('update.location') }}';
        var close_lang = '{{ trans('public.close') }}';
        var finishReserveHint = '{{ trans('meeting.finish_reserve_modal_hint') }}';
        var finishReserveConfirm = '{{ trans('meeting.finish_reserve_modal_confirm') }}';
        var finishReserveCancel = '{{ trans('meeting.finish_reserve_modal_cancel') }}';
        var finishReserveTitle = '{{ trans('meeting.finish_reserve_modal_title') }}';
        var finishReserveSuccess = '{{ trans('meeting.finish_reserve_modal_success') }}';
        var finishReserveSuccessHint = '{{ trans('meeting.finish_reserve_modal_success_hint') }}';
        var finishReserveFail = '{{ trans('meeting.finish_reserve_modal_fail') }}';
        var finishReserveFailHint = '{{ trans('meeting.finish_reserve_modal_fail_hint') }}';
    </script>

    <script src="/assets/default/js/panel/meeting/contact-info.min.js"></script>
    <script src="/assets/default/js/panel/meeting/reserve_meeting.min.js"></script>
@endpush