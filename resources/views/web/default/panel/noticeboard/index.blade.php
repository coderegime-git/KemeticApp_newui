@extends('web.default.layouts.newapp')
<style>
 /* Section Title */
.kemetic-title {
    color: #f5c77a;
    font-weight: 700;
    letter-spacing: 0.5px;
}

/* Container */
.kemetic-stats-container {
    background: linear-gradient(145deg, #0b0b0b, #141414);
    border-radius: 16px;
    border: 1px solid rgba(245, 199, 122, 0.15);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
}

/* Card */
.kemetic-stat-card {
    background: radial-gradient(
        circle at top,
        rgba(245, 199, 122, 0.08),
        transparent 70%
    );
    border-radius: 14px;
    padding: 30px 20px;
    height: 100%;
    transition: all 0.3s ease;
}

.kemetic-stat-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 28px rgba(245, 199, 122, 0.15);
}

/* Icon */
.kemetic-icon-wrapper {
    /* width: 72px;
    height: 72px;
    border-radius: 50%; */
    /* background: linear-gradient(135deg, #f5c77a, #c89b3c); */
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px; 
}

.kemetic-icon-wrapper img {
    /* width: 36px; */
    /* filter: brightness(0) invert(1); */
}

/* Numbers */
.kemetic-stat-number {
    font-size: 32px;
    font-weight: 800;
    color: #f5c77a;
    margin-top: 10px;
}

/* Labels */
.kemetic-stat-label {
    font-size: 14px;
    color: #b7b7b7;
    margin-top: 6px;
    letter-spacing: 0.4px;
}

/* Card */
.kemetic-filter-card {
    background: linear-gradient(145deg, #0c0c0c, #151515);
    border-radius: 16px;
    border: 1px solid rgba(245, 199, 122, 0.15);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.65);
}

/* Labels */
.kemetic-input-label {
    font-size: 13px;
    font-weight: 600;
    color: #f5c77a;
    margin-bottom: 6px;
}

/* Inputs */
.kemetic-input {
    background: #0f0f0f;
    border: 1px solid rgba(245, 199, 122, 0.2);
    color: #ffffff;
    border-radius: 10px;
    height: 44px;
}

.kemetic-input::placeholder {
    color: #8d8d8d;
}

.kemetic-input:focus {
    background: #0f0f0f;
    border-color: #f5c77a;
    box-shadow: 0 0 0 0.15rem rgba(245, 199, 122, 0.25);
    color: #ffffff;
}

/* Input group */
.kemetic-input-group {
    display: flex;
    align-items: center;
}

.kemetic-input-icon {
    background: linear-gradient(135deg, #f5c77a, #c89b3c);
    color: #000;
    border-radius: 10px 0 0 10px;
    padding: 0 12px;
    display: flex;
    align-items: center;
}

/* Button */
.kemetic-btn-gold {
    background: linear-gradient(135deg, #f5c77a, #c89b3c);
    border: none;
    color: #000;
    font-weight: 700;
    height: 46px;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.kemetic-btn-gold:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(245, 199, 122, 0.4);
}

/* Table Card */
.kemetic-table-card {
    background: linear-gradient(145deg, #0b0b0b, #151515);
    border-radius: 18px;
    border: 1px solid rgba(245, 199, 122, 0.15);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.75);
}

/* Table */
.kemetic-table {
    color: #ffffff;
    margin-bottom: 0;
}

.kemetic-table thead th {
    border: none;
    padding: 14px 12px;
    font-size: 13px;
    font-weight: 600;
    color: #f5c77a;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Rows */
.kemetic-row {
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    transition: all 0.25s ease;
}

.kemetic-row:hover {
    background: rgba(245, 199, 122, 0.05);
}

/* Title */
.kemetic-table-title {
    font-size: 15px;
    font-weight: 600;
    color: #ffffff;
}

.kemetic-table-subtitle {
    font-size: 12px;
    color: #9a9a9a;
}

/* Meta */
.kemetic-table-meta {
    font-size: 14px;
    font-weight: 500;
    color: #d6d6d6;
}

/* Buttons */
.kemetic-btn-outline {
    border: 1px solid #f5c77a;
    background: transparent;
    color: #f5c77a;
    font-size: 13px;
    padding: 5px 14px;
    border-radius: 20px;
    transition: all 0.3s ease;
}

.kemetic-btn-outline:hover {
    background: #f5c77a;
    color: #000;
}

/* Actions */
.kemetic-action-btn {
    background: transparent;
    border: none;
    color: #f5c77a;
}

.kemetic-actions .dropdown-menu {
    background: #111;
    border: 1px solid rgba(245, 199, 122, 0.2);
    border-radius: 12px;
}

.kemetic-dropdown-item {
    color: #f5c77a;
    font-size: 14px;
}

.kemetic-dropdown-item:hover {
    background: rgba(245, 199, 122, 0.1);
    color: #ffffff;
}

</style>
@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')

    {{-- Statistics --}}
    @if(empty($isCourseNotice))
        <section class="mt-35">
    <h2 class="section-title kemetic-title">
        {{ trans('update.noticeboard_statistics') }}
    </h2>

    <div class="kemetic-stats-container mt-25 p-25 p-lg-35">
        <div class="row text-center">

            <!-- Total Noticeboards -->
            <div class="col-12 col-md-4 mb-25 mb-md-0">
                <div class="kemetic-stat-card">
                    <div class="kemetic-icon-wrapper">
                        <img src="/assets/default/img/activity/homework.svg" alt="">
                    </div>
                    <strong class="kemetic-stat-number">
                        {{ $totalNoticeboards }}
                    </strong>
                    <span class="kemetic-stat-label">
                        {{ trans('update.total_noticeboards') }}
                    </span>
                </div>
            </div>

            <!-- Course Notices -->
            <div class="col-12 col-md-4 mb-25 mb-md-0">
                <div class="kemetic-stat-card">
                    <div class="kemetic-icon-wrapper">
                        <img src="/assets/default/img/activity/58.svg" alt="">
                    </div>
                    <strong class="kemetic-stat-number">
                        {{ $totalCourseNotices }}
                    </strong>
                    <span class="kemetic-stat-label">
                        {{ trans('update.course_notices') }}
                    </span>
                </div>
            </div>

            <!-- General Notices -->
            <div class="col-12 col-md-4">
                <div class="kemetic-stat-card">
                    <div class="kemetic-icon-wrapper">
                        <img src="/assets/default/img/activity/45.svg" alt="">
                    </div>
                    <strong class="kemetic-stat-number">
                        {{ $totalGeneralNotices }}
                    </strong>
                    <span class="kemetic-stat-label">
                        {{ trans('update.general_notices') }}
                    </span>
                </div>
            </div>

        </div>
    </div>
</section>

    @endif

    {{-- Filters --}}
 <section class="mt-35">
    <h2 class="section-title kemetic-title">
        {{ trans('update.filter_noticeboards') }}
    </h2>

    <div class="kemetic-filter-card mt-25 p-25 p-lg-35" style="padding:10px;">
        <form action="{{ request()->url() }}" method="get" class="row g-3 align-items-end">

            {{-- Date Range --}}
            <div class="col-12 col-lg-4">
                <div class="row g-3">
                    <div class="col-6">
                        <label class="kemetic-input-label">{{ trans('public.from') }}</label>
                        <div class="kemetic-input-group">
                            <span class="kemetic-input-icon">
                                <i data-feather="calendar" width="18" height="18"></i>
                            </span>
                            <input type="text"
                                   name="from"
                                   autocomplete="off"
                                   class="form-control kemetic-input @if(request()->get('from')) datepicker @else datefilter @endif"
                                   value="{{ request()->get('from','') }}">
                        </div>
                    </div>

                    <div class="col-6">
                        <label class="kemetic-input-label">{{ trans('public.to') }}</label>
                        <div class="kemetic-input-group">
                            <span class="kemetic-input-icon">
                                <i data-feather="calendar" width="18" height="18"></i>
                            </span>
                            <input type="text"
                                   name="to"
                                   autocomplete="off"
                                   class="form-control kemetic-input @if(request()->get('to')) datepicker @else datefilter @endif"
                                   value="{{ request()->get('to','') }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Course & Title --}}
            <div class="col-12 col-lg-6">
                <div class="row g-3">

                    <div class="col-12 col-lg-4">
                        <label class="kemetic-input-label">{{ trans('product.course') }}</label>
                        <select name="webinar_id" class="form-control kemetic-input select2">
                            <option value="">{{ trans('webinars.all_courses') }}</option>
                            @foreach($webinars as $webinar)
                                <option value="{{ $webinar->id }}" @if(request()->get('webinar_id') == $webinar->id) selected @endif>
                                    {{ $webinar->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 {{ !empty($isCourseNotice) ? 'col-lg-5' : 'col-lg-8' }}">
                        <label class="kemetic-input-label">{{ trans('public.title') }}</label>
                        <input type="text"
                               name="title"
                               class="form-control kemetic-input"
                               value="{{ request()->get('title') }}"
                               placeholder="{{ trans('public.search') }}">
                    </div>

                    @if(!empty($isCourseNotice))
                        <div class="col-12 col-lg-3">
                            <label class="kemetic-input-label">{{ trans('update.color') }}</label>
                            <select name="color" class="form-control kemetic-input select2">
                                <option value="">{{ trans('update.all_colors') }}</option>
                                @foreach(\App\Models\CourseNoticeboard::$colors as $noticeColor)
                                    <option value="{{ $noticeColor }}" @if(request()->get('color') == $noticeColor) selected @endif>
                                        {{ trans('update.course_noticeboard_color_'.$noticeColor) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                </div>
            </div>

            {{-- Submit --}}
            <div class="col-12 col-lg-2">
                <button type="submit" class="btn kemetic-btn-gold w-100">
                    {{ trans('public.show_results') }}
                </button>
            </div>

        </form>
    </div>
</section>



    {{-- Noticeboard Table --}}
   <section class="mt-35">
    <h2 class="section-title kemetic-title">
        {{ trans('panel.noticeboards') }}
    </h2>

    @if(!empty($noticeboards) and !$noticeboards->isEmpty())
        <div class="kemetic-table-card mt-25 p-25 p-lg-35">
            <div class="table-responsive">

                <table class="table kemetic-table align-middle">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('webinars.title') }}</th>
                        <th class="text-center">{{ trans('site.message') }}</th>
                        <th class="text-center">
                            {{ !empty($isCourseNotice) ? trans('update.color') : trans('public.type') }}
                        </th>
                        <th class="text-center">{{ trans('public.date') }}</th>
                        <th class="text-right"></th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($noticeboards as $noticeboard)
                        <tr class="kemetic-row">
                            <td class="text-left">
                                <span class="d-block kemetic-table-title">
                                    {{ $noticeboard->title }}
                                </span>

                                @if(!empty($noticeboard->webinar))
                                    <span class="d-block kemetic-table-subtitle">
                                        {{ $noticeboard->webinar->title }}
                                    </span>
                                @endif
                            </td>

                            <td class="text-center">
                                <button type="button"
                                        class="btn kemetic-btn-outline js-view-message">
                                    {{ trans('public.view') }}
                                </button>
                                <input type="hidden"
                                       class="js-noticeboard-message"
                                       value="{{ nl2br($noticeboard->message) }}">
                            </td>

                            <td class="text-center kemetic-table-meta">
                                @if(!empty($isCourseNotice))
                                    {{ trans('update.course_noticeboard_color_'.$noticeboard->color) }}
                                @else
                                    @if(!empty($noticeboard->instructor_id) and !empty($noticeboard->webinar_id))
                                        {{ trans('product.course') }}
                                    @else
                                        {{ trans('public.'.$noticeboard->type) }}
                                    @endif
                                @endif
                            </td>

                            <td class="text-center kemetic-table-meta">
                                {{ dateTimeFormat($noticeboard->created_at,'j M Y | H:i') }}
                            </td>

                            <td class="text-right">
                                <div class="dropdown kemetic-actions">
                                    <button class="btn kemetic-action-btn"
                                            data-toggle="dropdown">
                                        <i data-feather="more-vertical" height="18"></i>
                                    </button>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        @can('panel_noticeboard_create')
                                            <a href="/panel/{{ (!empty($isCourseNotice) and $isCourseNotice) ? 'course-noticeboard' : 'noticeboard' }}/{{ $noticeboard->id }}/edit"
                                               class="dropdown-item kemetic-dropdown-item">
                                                {{ trans('public.edit') }}
                                            </a>
                                        @endcan

                                        @can('panel_noticeboard_delete')
                                            <a href="/panel/{{ (!empty($isCourseNotice) and $isCourseNotice) ? 'course-noticeboard' : 'noticeboard' }}/{{ $noticeboard->id }}/delete"
                                               class="dropdown-item kemetic-dropdown-item text-danger">
                                                {{ trans('public.delete') }}
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    @else
        @include(getTemplate() . '.includes.no-result',[
            'file_name' => 'comment.png',
            'title' => trans('panel.comments_no_result'),
            'hint' => nl2br(trans('panel.comments_no_result_hint')),
        ])
    @endif
</section>


    {{-- Pagination --}}
    <div class="my-30">
        {{ $noticeboards->appends(request()->input())->links('vendor.pagination.panel') }}
    </div>

    {{-- Message Modal --}}
    <div class="d-none" id="noticeboardMessageModal">
        <div class="text-center">
            <h3 class="modal-title font-16 font-weight-bold text-dark-blue"></h3>
            <span class="modal-time d-block font-12 text-gray mt-2"></span>
            <p class="modal-message font-weight-500 text-gray mt-2"></p>
        </div>
    </div>

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/js/panel/noticeboard.min.js"></script>
@endpush
