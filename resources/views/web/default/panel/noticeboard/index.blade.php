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

/* TITLE CELL */
.kemetic-title-cell .title {
    color:#fff; 
    font-weight:600;
    font-size: 15px;
}
.kemetic-title-cell small {
    color:#888; 
    display:block;
    font-size: 11px;
    margin-top: 2px;
}

/* META TEXT */
.kemetic-meta {
    color: #d6d6d6;
    font-size: 14px;
    font-weight: 500;
}

/* VIEW BUTTON */
.view-btn {
    background: transparent;
    border: 1px solid rgba(242,201,76,0.4);
    color: #F2C94C;
    border-radius: 20px;
    padding: 5px 16px;
    font-size: 13px;
    transition: 0.3s ease;
}
.view-btn:hover {
    background: rgba(242,201,76,0.1);
    border-color: #F2C94C;
}

/* COLOR BADGES */
.color-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
}
.color-badge.brown {
    background: #8B4513;
    color: #fff;
}
.color-badge.gray {
    background: #808080;
    color: #fff;
}
.color-badge.orange {
    background: #FFA500;
    color: #000;
}
.color-badge.yellow {
    background: #FFD700;
    color: #000;
}
.color-badge.green {
    background: #2ecc71;
    color: #000;
}
.color-badge.blue {
    background: #3498db;
    color: #fff;
}
.color-badge.red {
    background: #e74c3c;
    color: #fff;
}
.color-badge.purple {
    background: #9b59b6;
    color: #fff;
}
.color-badge.course {
    background: rgba(242,201,76,0.15);
    color: #F2C94C;
    border: 1px solid rgba(242,201,76,0.3);
}
.color-badge.general {
    background: rgba(255,255,255,0.1);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.2);
}

/* ACTIONS */
.kemetic-actions button {
    background: none;
    border: none;
    color: #F2C94C;
}
.kemetic-actions .dropdown-menu {
    background: #121212;
    border: 1px solid #2a2a2a;
    border-radius: 12px;
    padding: 8px 0;
}
.kemetic-actions .dropdown-item {
    color: #F2C94C;
    padding: 8px 16px;
    font-size: 14px;
}
.kemetic-actions .dropdown-item:hover {
    background: rgba(242,201,76,0.1);
    color: #fff;
}
.kemetic-actions .dropdown-item.text-danger {
    color: #e74c3c !important;
}
.kemetic-actions .dropdown-item.text-danger:hover {
    background: rgba(231,76,60,0.1);
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
.modal-time {
    color: #888;
    font-size: 12px;
    display: block;
    margin-top: 5px;
}
.modal-message {
    color: #eaeaea;
    font-size: 14px;
    line-height: 1.6;
    margin-top: 15px;
    white-space: pre-wrap;
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
.modal-footer .btn:hover {
    background: #2a2a2a;
}

/* NO RESULT */
.no-result-card {
    background: linear-gradient(180deg,#121212,#0a0a0a);
    border:1px solid #262626;
    border-radius:18px;
    padding:40px;
    text-align: center;
}
.no-result-card img {
    opacity: 0.7;
    margin-bottom: 20px;
}
.no-result-card h3 {
    color: #F2C94C;
    font-size: 20px;
    margin-bottom: 10px;
}
.no-result-card p {
    color: #888;
    font-size: 14px;
}
</style>

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
@endpush

@section('content')

    {{-- Statistics --}}
    @if(empty($isCourseNotice))
        <section class="kemetic-stat-section">
            <h2 class="kemetic-title">{{ trans('update.noticeboard_statistics') }}</h2>

            <div class="kemetic-stat-card">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="kemetic-stat-item">
                            <div class="kemetic-stat-icon">
                                <img src="/assets/default/img/activity/homework.svg" alt="">
                            </div>
                            <div class="kemetic-stat-value">{{ $totalNoticeboards }}</div>
                            <div class="kemetic-stat-label">{{ trans('update.total_noticeboards') }}</div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="kemetic-stat-item">
                            <div class="kemetic-stat-icon">
                                <img src="/assets/default/img/activity/58.svg" alt="">
                            </div>
                            <div class="kemetic-stat-value">{{ $totalCourseNotices }}</div>
                            <div class="kemetic-stat-label">{{ trans('update.course_notices') }}</div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="kemetic-stat-item">
                            <div class="kemetic-stat-icon">
                                <img src="/assets/default/img/activity/45.svg" alt="">
                            </div>
                            <div class="kemetic-stat-value">{{ $totalGeneralNotices }}</div>
                            <div class="kemetic-stat-label">{{ trans('update.general_notices') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Filters --}}
    <section class="kemetic-filter-section mt-30">
        <h2 class="kemetic-title">{{ trans('update.filter_noticeboards') }}</h2>

        <div class="kemetic-filter-card mt-20">
            <form action="{{ request()->url() }}" method="get">
                <div class="row g-3">

                    {{-- Date range --}}
                    <div class="col-12 col-lg-4">
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="kemetic-label">{{ trans('public.from') }}</label>
                                <div class="kemetic-input-group">
                                    <!-- <i data-feather="calendar" width="18" height="18"></i> -->
                                    <input type="date" name="from" autocomplete="off"
                                        class="kemetic-input"
                                        value="{{ request()->get('from','') }}">
                                </div>
                            </div>

                            <div class="col-6">
                                <label class="kemetic-label">{{ trans('public.to') }}</label>
                                <div class="kemetic-input-group">
                                    <!-- <i data-feather="calendar" width="18" height="18"></i> -->
                                    <input type="date" name="to" autocomplete="off"
                                        class="kemetic-input"
                                        value="{{ request()->get('to','') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Course & Title --}}
                    <div class="col-12 col-lg-6">
                        <div class="row g-2">
                            <div class="col-12 col-lg-4">
                                <label class="kemetic-label">{{ trans('product.course') }}</label>
                                <select name="webinar_id" class="kemetic-select select2" style="width: 100%;">
                                    <option value="">{{ trans('webinars.all_courses') }}</option>
                                    @foreach($webinars as $webinar)
                                        <option value="{{ $webinar->id }}" @if(request()->get('webinar_id') == $webinar->id) selected @endif>
                                            {{ $webinar->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 {{ !empty($isCourseNotice) ? 'col-lg-5' : 'col-lg-8' }}">
                                <label class="kemetic-label">{{ trans('public.title') }}</label>
                                <div class="kemetic-input-group">
                                    <i data-feather="search" width="18" height="18"></i>
                                    <input type="text" name="title"
                                        class="kemetic-input"
                                        value="{{ request()->get('title') }}"
                                        placeholder="{{ trans('public.search') }}">
                                </div>
                            </div>

                            @if(!empty($isCourseNotice))
                                <div class="col-12 col-lg-3">
                                    <label class="kemetic-label">{{ trans('update.color') }}</label>
                                    <select name="color" class="kemetic-select select2" style="width: 100%;">
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
                    <div class="col-12 col-lg-2 d-flex align-items-end">
                        <button type="submit" class="kemetic-btn w-100">
                            {{ trans('public.show_results') }}
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </section>

    {{-- Noticeboard Table --}}
    <section class="kemetic-section mt-40">
        <h2 class="kemetic-title">{{ trans('panel.noticeboards') }}</h2>

        @if(!empty($noticeboards) and !$noticeboards->isEmpty())

            <div class="kemetic-table-card mt-25">
                <div class="table-responsive">
                    <table class="kemetic-table">
                        <thead>
                            <tr>
                                <th class="text-left">{{ trans('webinars.title') }}</th>
                                <th>{{ trans('site.message') }}</th>
                                <th>{{ !empty($isCourseNotice) ? trans('update.color') : trans('public.type') }}</th>
                                <th>{{ trans('public.date') }}</th>
                                <th class="text-right"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($noticeboards as $noticeboard)
                                <tr>
                                    <td class="text-left">
                                        <div class="kemetic-title-cell">
                                            <span class="title">{{ $noticeboard->title }}</span>
                                            @if(!empty($noticeboard->webinar))
                                                <small>{{ $noticeboard->webinar->title }}</small>
                                            @endif
                                        </div>
                                    </td>

                                    <td>
                                        <input type="hidden" class="js-noticeboard-message" value="{{ nl2br($noticeboard->message) }}">
                                        <input type="hidden" class="js-noticeboard-title" value="{{ $noticeboard->title }}">
                                        <input type="hidden" class="js-noticeboard-date" value="{{ dateTimeFormat($noticeboard->created_at,'j M Y | H:i') }}">
                                        <button type="button" class="view-btn js-view-message" data-notice-id="{{ $noticeboard->id }}">
                                            {{ trans('public.view') }}
                                        </button>
                                    </td>

                                    <td>
                                        @if(!empty($isCourseNotice))
                                            <span class="color-badge {{ $noticeboard->color }}">
                                                {{ trans('update.course_noticeboard_color_'.$noticeboard->color) }}
                                            </span>
                                        @else
                                            @if(!empty($noticeboard->instructor_id) and !empty($noticeboard->webinar_id))
                                                <span class="color-badge course">{{ trans('product.course') }}</span>
                                            @else
                                                <span class="color-badge general">{{ trans('public.'.$noticeboard->type) }}</span>
                                            @endif
                                        @endif
                                    </td>

                                    <td>
                                        <span class="kemetic-meta">{{ dateTimeFormat($noticeboard->created_at,'j M Y | H:i') }}</span>
                                    </td>

                                    <td class="text-right">
                                        <div class="dropdown kemetic-actions">
                                            <button data-toggle="dropdown">
                                                <i data-feather="more-vertical" height="18"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @can('panel_noticeboard_create')
                                                    <a href="/panel/{{ (!empty($isCourseNotice) and $isCourseNotice) ? 'course-noticeboard' : 'noticeboard' }}/{{ $noticeboard->id }}/edit" 
                                                       class="dropdown-item">
                                                        {{ trans('public.edit') }}
                                                    </a>
                                                @endcan
                                                @can('panel_noticeboard_delete')
                                                    <a href="/panel/{{ (!empty($isCourseNotice) and $isCourseNotice) ? 'course-noticeboard' : 'noticeboard' }}/{{ $noticeboard->id }}/delete" 
                                                       class="dropdown-item text-danger">
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
            <div class="no-result-card mt-25">
                @include(getTemplate() . '.includes.no-result',[
                    'file_name' => 'comment.png',
                    'title' => trans('panel.comments_no_result'),
                    'hint' => nl2br(trans('panel.comments_no_result_hint')),
                ])
            </div>
        @endif
    </section>

    {{-- Pagination --}}
    @if(!empty($noticeboards) and !$noticeboards->isEmpty())
        <div class="my-30">
            {{ $noticeboards->appends(request()->input())->links('vendor.pagination.panel') }}
        </div>
    @endif

    {{-- Message Modal --}}
    <div class="modal fade" id="noticeboardMessageModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: #111010;">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span class="modal-time" id="modalTime" style="color: #111010;"></span>
                    <p class="modal-message" id="modalMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">{{ trans('public.close') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                width: '100%',
                theme: 'default'
            });

            // View message
            $('.js-view-message').on('click', function() {
                var message = $(this).closest('tr').find('.js-noticeboard-message').val();
                var title = $(this).closest('tr').find('.js-noticeboard-title').val();
                var date = $(this).closest('tr').find('.js-noticeboard-date').val();
                
                $('#modalTitle').text(title);
                $('#modalTime').text(date);
                $('#modalMessage').html(message);
                $('#noticeboardMessageModal').modal('show');
            });
        });
    </script>

    <script src="/assets/default/js/panel/noticeboard.min.js"></script>
@endpush