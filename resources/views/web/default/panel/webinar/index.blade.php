
@extends('web.default.layouts.newapp')

<style>
   /* ======================================================
   KEMETIC APP – PREMIUM PANEL DESIGN
   Black • Gold • Luxury SaaS
====================================================== */

:root {
    --k-bg: #070707;
    --k-surface: #0e0e0e;
    --k-card: linear-gradient(180deg,#141414,#0c0c0c);
    --k-border: rgba(242,201,76,.18);

    --k-gold: #F2C94C;
    --k-gold-soft: rgba(242,201,76,.12);
    --k-gold-glow: rgba(242,201,76,.35);

    --k-text: #f1f1f1;
    --k-muted: #9b9b9b;

    --k-radius-lg: 20px;
    --k-radius-md: 14px;
}

/* ===================== PAGE ===================== */

section {
    padding: 34px 18px 18px !important;
}

.section-title {
    color: var(--k-gold);
    font-size: 22px;
    font-weight: 700;
    letter-spacing: .4px;
}

/* ===================== ACTIVITY STATS ===================== */

.activities-container {
    background: radial-gradient(circle at top,#161616,#090909);
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius-lg);
    box-shadow: 0 20px 60px rgba(0,0,0,.75);
}

.activities-container img {
    filter: drop-shadow(0 0 14px var(--k-gold-glow));
}

.activities-container strong {
    font-size: 22px;
    color: var(--k-gold);
}

.activities-container span {
    color: var(--k-muted);
}

/* ===================== WEBINAR CARD ===================== */

.webinar-card {
    background: var(--k-card);
    border-radius: var(--k-radius-lg);
    border: 1px solid var(--k-border);
    overflow: hidden;
    transition: all .35s ease;
    box-shadow: 0 18px 55px rgba(0,0,0,.7);
}

.webinar-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 28px 75px rgba(242,201,76,.18);
    border-color: var(--k-gold);
}

/* ===================== IMAGE ===================== */

.webinar-card .image-box {
    width: 260px;
    position: relative;
}

.webinar-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* ===================== PROGRESS ===================== */

.webinar-card .progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 6px;
    width: 100%;
    background: rgba(255,255,255,.08);
}

.webinar-card .progress-bar {
    background: linear-gradient(90deg,#F2C94C,#E5A100);
}

/* ===================== BADGES ===================== */

.badges-lists {
    position: absolute;
    top: 12px;
    left: 12px;
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.badge {
    border-radius: 999px;
    padding: 6px 14px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid transparent;
}

.badge-primary {
    background: var(--k-gold-soft);
    color: var(--k-gold);
    border-color: var(--k-gold);
}

.badge-secondary {
    background: rgba(255,255,255,.08);
    color: #ddd;
}

.badge-danger {
    background: rgba(220,53,69,.18);
    color: #ff6b6b;
}

.badge-warning {
    background: rgba(255,193,7,.22);
    color: #ffc107;
}

.status-badge-dark {
    background: rgba(0,0,0,.7);
    color: var(--k-gold);
    border: 1px solid var(--k-border);
}

/* ===================== CARD BODY ===================== */

.webinar-card-body {
    padding: 20px 24px;
}

.webinar-card-body h3 {
    color: var(--k-text);
    font-size: 16px;
    line-height: 1.4;
}

.webinar-card-body a:hover h3 {
    color: var(--k-gold);
}

/* ===================== PRICE ===================== */

.webinar-price-box .real {
    color: var(--k-gold);
    font-size: 20px;
    font-weight: 700;
}

.webinar-price-box .off {
    color: #888;
    text-decoration: line-through;
}

/* ===================== STATS ===================== */

.stat-title {
    color: var(--k-muted);
    font-size: 12px;
    letter-spacing: .08em;
    text-transform: uppercase;
}

.stat-value {
    color: var(--k-text);
    font-size: 15px;
    font-weight: 600;
}

/* ===================== DROPDOWN ===================== */

.table-actions .dropdown-menu {
    background: #0b0b0b;
    border-radius: var(--k-radius-md);
    border: 1px solid var(--k-border);
    box-shadow: 0 18px 60px rgba(0,0,0,.85);
}

.webinar-actions {
    color: #ddd;
    padding: 12px 16px;
    font-size: 14px;
    transition: .25s;
}

.webinar-actions:hover {
    background: var(--k-gold-soft);
    color: var(--k-gold);
}

/* ===================== SWITCH ===================== */

.custom-control-input:checked ~ .custom-control-label::before {
    background-color: var(--k-gold);
    border-color: var(--k-gold);
}

/* ===================== PAGINATION ===================== */

.pagination .page-link {
    background: #0d0d0d;
    border: 1px solid var(--k-border);
    color: var(--k-gold);
    border-radius: 10px;
}

.pagination .active .page-link {
    background: var(--k-gold);
    color: #000;
}

/* ===================== MOBILE ===================== */

@media (max-width: 991px) {
    .webinar-card {
        flex-direction: column;
    }

    .webinar-card .image-box {
        width: 100%;
        height: 220px;
    }
}

/* ===================== FIX DROPDOWN POSITION ===================== */

.webinar-dropdown {
    position: relative;
}

.webinar-dropdown .dropdown-menu {
    position: absolute !important;
    top: 42px !important;
    right: 0 !important;
    left: auto !important;

    transform: none !important;
    margin-top: 6px;

    min-width: 220px;
    z-index: 9999;
}

/* Prevent parent clipping */
.webinar-card,
.webinar-card-body {
    overflow: visible !important;
}

/* Mobile fix */
@media (max-width: 991px) {
    .webinar-dropdown .dropdown-menu {
        right: 10px !important;
        left: auto !important;
    }
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

/* ===================== KEMETIC 3 DOT BUTTON ===================== */

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
@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')
    <section>
        <h2 class="section-title">{{ trans('panel.my_activity') }}</h2>

        <div class="activities-container mt-25 p-20 p-lg-35">
            <div class="row" style="padding:10px;">
                <div class="col-6 col-md-3 mt-30 mt-md-0 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/default/img/activity/webinars.svg" width="64" height="64" alt="">
                        <strong class="">{{ !empty($webinars) ? $webinarsCount : 0}}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('panel.classes') }}</span>
                    </div>
                </div>

                <div class="col-6 col-md-3 mt-30 mt-md-0 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/default/img/activity/hours.svg" width="64" height="64" alt="">
                        <strong class="">{{ convertMinutesToHourAndMinute($webinarHours) }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('home.hours') }}</span>
                    </div>
                </div>

                <div class="col-6 col-md-3 mt-30 mt-md-0 d-flex align-items-center justify-content-center mt-5 mt-md-0">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/default/img/activity/sales.svg" width="64" height="64" alt="">
                        <strong class="">{{ handlePrice($webinarSalesAmount) }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('cart.total') .' '. trans('panel.webinar_sales') }}</span>
                    </div>
                </div>

                <div class="col-6 col-md-3 mt-30 mt-md-0 d-flex align-items-center justify-content-center mt-5 mt-md-0">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/default/img/activity/download-sales.svg" width="64" height="64" alt="">
                        <strong class="">{{ handlePrice($courseSalesAmount) }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('cart.total') .' '.trans('panel.course_sales') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-25">
        <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
            <h2 class="section-title">{{ trans('panel.my_webinars') }}</h2>

            <form action="" method="get">
                <div class="d-flex align-items-center flex-row-reverse flex-md-row justify-content-start justify-content-md-center mt-20 mt-md-0">
                    <label class="cursor-pointer mb-0 mr-10 font-weight-500 font-14 text-gray" for="conductedSwitch">{{ trans('panel.only_not_conducted_webinars') }}</label>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="not_conducted" @if(request()->get('not_conducted','') == 'on') checked @endif class="custom-control-input" id="conductedSwitch">
                        <label class="custom-control-label" for="conductedSwitch"></label>
                    </div>
                </div>
            </form>
        </div>

        @if(!empty($webinars) and !$webinars->isEmpty())
            @foreach($webinars as $webinar)
                @php
                    $lastSession = $webinar->lastSession();
                    $nextSession = $webinar->nextSession();
                    $isProgressing = false;

                    if($webinar->start_date <= time() and !empty($lastSession) and $lastSession->date > time()) {
                        $isProgressing=true;
                    }
                @endphp

                <div class="row mt-30">
                    <div class="col-12">
                        <div class="webinar-card webinar-list d-flex" style="padding:10px;">
                            <div class="image-box">
                                @if($webinar->getImage())
                                    <img src="{{ $webinar->getImage() }}" class="img-cover" alt="">
                                @else
                                    <img src="/noimage.png" class="img-cover" alt="">
                                @endif
                                <!-- <img src="{{ $webinar->getImage() }}" class="img-cover" alt=""> -->

                                <div class="badges-lists">
                                    @if(!empty($webinar->deleteRequest) and $webinar->deleteRequest->status == "pending")
                                        <span class="badge badge-danger">{{ trans('update.removal_request_sent') }}</span>
                                    @else
                                        @switch($webinar->status)
                                            @case(\App\Models\Webinar::$active)
                                                @if($webinar->isWebinar())
                                                    @if($webinar->start_date > time())
                                                        <span class="badge badge-primary">{{  trans('panel.not_conducted') }}</span>
                                                    @elseif($webinar->isProgressing())
                                                        <span class="badge badge-secondary">{{ trans('webinars.in_progress') }}</span>
                                                    @else
                                                        <span class="badge badge-secondary">{{ trans('public.finished') }}</span>
                                                    @endif
                                                @else
                                                    <span class="badge badge-secondary">{{ trans('webinars.'.$webinar->type) }}</span>
                                                @endif
                                                @break
                                            @case(\App\Models\Webinar::$isDraft)
                                                <span class="badge badge-danger">{{ trans('public.draft') }}</span>
                                                @break
                                            @case(\App\Models\Webinar::$pending)
                                                <span class="badge badge-warning">{{ trans('public.waiting') }}</span>
                                                @break
                                            @case(\App\Models\Webinar::$inactive)
                                                <span class="badge badge-danger">{{ trans('public.rejected') }}</span>
                                                @break
                                        @endswitch
                                    @endif
                                </div>

                                @if($webinar->isWebinar())
                                    <div class="progress">
                                        <span class="progress-bar" style="width: {{ $webinar->getProgress() }}%"></span>
                                    </div>
                                @endif
                            </div>

                            <div class="webinar-card-body w-100 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="{{ $webinar->getUrl() }}" target="_blank">
                                        <h3 class="font-16 text-dark-blue font-weight-bold">{{ $webinar->title }}
                                            <span class="badge badge-dark ml-10 status-badge-dark">{{ trans('webinars.'.$webinar->type) }}</span>
                                        </h3>
                                    </a>

                                    @if($webinar->isOwner($authUser->id) or $webinar->isPartnerTeacher($authUser->id))
                                        <div class="btn-group dropdown table-actions webinar-dropdown">
                                            <button type="button" class="btn-transparent dropdown-toggle kemetic-more-btn" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
                                                <i data-feather="more-vertical" height="20"></i>
                                            </button>
                                            <div class="dropdown-menu ">
                                                @if(!empty($webinar->start_date))
                                                    <button type="button" data-webinar-id="{{ $webinar->id }}" class="js-webinar-next-session webinar-actions btn-transparent d-block">{{ trans('public.create_join_link') }}</button>
                                                @endif


                                                @can('panel_webinars_learning_page')
                                                    <a href="{{ $webinar->getLearningPageUrl() }}" target="_blank" class="webinar-actions d-block mt-10">{{ trans('update.learning_page') }}</a>
                                                @endcan

                                                @can('panel_webinars_create')
                                                    <a href="/panel/webinars/{{ $webinar->id }}/edit" class="webinar-actions d-block mt-10">{{ trans('public.edit') }}</a>
                                                @endcan

                                                @if($webinar->isWebinar())
                                                    @can('panel_webinars_create')
                                                        <a href="/panel/webinars/{{ $webinar->id }}/step/4" class="webinar-actions d-block mt-10">{{ trans('public.sessions') }}</a>
                                                    @endcan
                                                @endif

                                                @can('panel_webinars_create')
                                                    <a href="/panel/webinars/{{ $webinar->id }}/step/4" class="webinar-actions d-block mt-10">{{ trans('public.files') }}</a>
                                                @endcan

                                                @can('panel_webinars_export_students_list')
                                                    <a href="/panel/webinars/{{ $webinar->id }}/export-students-list" class="webinar-actions d-block mt-10">{{ trans('public.export_list') }}</a>
                                                @endcan

                                                @if($authUser->id == $webinar->creator_id)
                                                    @can('panel_webinars_duplicate')
                                                        <a href="/panel/webinars/{{ $webinar->id }}/duplicate" class="webinar-actions d-block mt-10">{{ trans('public.duplicate') }}</a>
                                                    @endcan
                                                @endif

                                                @can('panel_webinars_statistics')
                                                    <a href="/panel/webinars/{{ $webinar->id }}/statistics" class="webinar-actions d-block mt-10">{{ trans('update.statistics') }}</a>
                                                @endcan

                                                @if($webinar->creator_id == $authUser->id)
                                                    @can('panel_webinars_delete')
                                                        @include('web.default.panel.includes.content_delete_btn', [
                                                            'deleteContentUrl' => "/panel/webinars/{$webinar->id}/delete",
                                                            'deleteContentClassName' => 'webinar-actions d-block mt-10 text-danger',
                                                            'deleteContentItem' => $webinar,
                                                        ])
                                                    @endcan
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                @include(getTemplate() . '.includes.webinar.rate',['rate' => $webinar->getRate()])

                                <div class="webinar-price-box mt-15">
                                    @if($webinar->price > 0)
                                        @if($webinar->bestTicket() < $webinar->price)
                                            <span class="real">{{ handlePrice($webinar->bestTicket(), true, true, false, null, true) }}</span>
                                            <span class="off ml-10">{{ handlePrice($webinar->price, true, true, false, null, true) }}</span>
                                        @else
                                            <span class="real">{{ handlePrice($webinar->price, true, true, false, null, true) }}</span>
                                        @endif
                                    @else
                                        <span class="real">{{ trans('public.free') }}</span>
                                    @endif
                                </div>

                                <div class="d-flex align-items-center justify-content-between flex-wrap mt-auto">
                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                        <span class="stat-title">{{ trans('public.item_id') }}:</span>
                                        <span class="stat-value">{{ $webinar->id }}</span>
                                    </div>

                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                        <span class="stat-title">{{ trans('public.category') }}:</span>
                                        <span class="stat-value">{{ !empty($webinar->category_id) ? $webinar->category->title : '' }}</span>
                                    </div>

                                    @if($webinar->isProgressing() and !empty($nextSession))
                                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                            <span class="stat-title">{{ trans('webinars.next_session_duration') }}:</span>
                                            <span class="stat-value">{{ convertMinutesToHourAndMinute($nextSession->duration) }} Hrs</span>
                                        </div>

                                        @if($webinar->isWebinar())
                                            <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                <span class="stat-title">{{ trans('webinars.next_session_start_date') }}:</span>
                                                <span class="stat-value">{{ dateTimeFormat($nextSession->date,'j M Y') }}</span>
                                            </div>
                                        @endif
                                    @else
                                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                            <span class="stat-title">{{ trans('public.duration') }}:</span>
                                            <span class="stat-value">{{ convertMinutesToHourAndMinute($webinar->duration) }} Hrs</span>
                                        </div>

                                        @if($webinar->isWebinar())
                                            <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                <span class="stat-title">{{ trans('public.start_date') }}:</span>
                                                <span class="stat-value">{{ dateTimeFormat($webinar->start_date,'j M Y') }}</span>
                                            </div>
                                        @endif
                                    @endif

                                    @if($webinar->isTextCourse() or $webinar->isCourse())
                                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                            <span class="stat-title">{{ trans('public.files') }}:</span>
                                            <span class="stat-value">{{ $webinar->files->count() }}</span>
                                        </div>
                                    @endif

                                    @if($webinar->isTextCourse())
                                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                            <span class="stat-title">{{ trans('webinars.text_lessons') }}:</span>
                                            <span class="stat-value">{{ $webinar->textLessons->count() }}</span>
                                        </div>
                                    @endif

                                    @if($webinar->isCourse())
                                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                            <span class="stat-title">{{ trans('home.downloadable') }}:</span>
                                            <span class="stat-value">{{ ($webinar->downloadable) ? trans('public.yes') : trans('public.no') }}</span>
                                        </div>
                                    @endif

                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                        <span class="stat-title">{{ trans('panel.sales') }}:</span>
                                        <span class="stat-value">{{ count($webinar->sales) }} ({{ (!empty($webinar->sales) and count($webinar->sales)) ? handlePrice($webinar->sales->sum('amount')) : 0 }})</span>
                                    </div>

                                    @if(!empty($webinar->partner_instructor) and $webinar->partner_instructor and $authUser->id != $webinar->teacher_id and $authUser->id != $webinar->creator_id)
                                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                            <span class="stat-title">{{ trans('panel.invited_by') }}:</span>
                                            <span class="stat-value">{{ $webinar->teacher->full_name }}</span>
                                        </div>
                                    @elseif($authUser->id != $webinar->teacher_id and $authUser->id != $webinar->creator_id)
                                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                            <span class="stat-title">{{ trans('webinars.teacher_name') }}:</span>
                                            <span class="stat-value">{{ $webinar->teacher->full_name }}</span>
                                        </div>
                                    @elseif($authUser->id == $webinar->teacher_id and $authUser->id != $webinar->creator_id and $webinar->creator->isOrganization())
                                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                            <span class="stat-title">{{ trans('webinars.organization_name') }}:</span>
                                            <span class="stat-value">{{ $webinar->creator->full_name }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="my-30" style="padding: 10px;">
                {{ $webinars->appends(request()->input())->links('vendor.pagination.panel') }}
            </div>

        @else
            @include(getTemplate() . '.includes.no-result',[
                'file_name' => 'webinar.png',
                'title' => trans('panel.you_not_have_any_webinar'),
                'hint' =>  trans('panel.no_result_hint') ,
                'btn' => ['url' => '/panel/webinars/new','text' => trans('panel.create_a_webinar') ]
            ])
        @endif

    </section>

    @include('web.default.panel.webinar.make_next_session_modal')
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>

    <script>
        var undefinedActiveSessionLang = '{{ trans('webinars.undefined_active_session') }}';
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
        var selectChapterLang = '{{ trans('update.select_chapter') }}';
    </script>

    <script src="/assets/default/js/panel/make_next_session.min.js"></script>
@endpush
