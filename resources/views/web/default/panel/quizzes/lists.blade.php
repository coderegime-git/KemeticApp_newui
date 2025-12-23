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
    background: #0f0f0f;
    border: 1px solid #2a2a2a;
    border-radius: 12px;
    height: 44px;
    display: flex;
    align-items: center;
}
.select2-selection__rendered {
    color: #fff !important;
}

/* BUTTON */
.kemetic-btn {
    background: linear-gradient(135deg, #F2C94C, #d4af37);
    border: none;
    border-radius: 14px;
    padding: 12px;
    font-weight: 700;
    color: #000;
}

/* MOBILE */
@media (max-width: 768px) {
    .kemetic-filter-card {
        padding: 20px 16px;
    }
}

/* KEMETIC BASE */
.kemetic-section { color:#eaeaea; }
.kemetic-title { color:#F2C94C; font-size:22px; font-weight:700; }

/* HEADER */
.kemetic-header { gap:15px; }

/* SWITCH */
.kemetic-switch-wrapper {
    display:flex; align-items:center; gap:12px;
}
.kemetic-switch-label { color:#b5b5b5; font-size:14px; }

.kemetic-switch {
    position:relative; width:46px; height:24px;
}
.kemetic-switch input { display:none; }
.kemetic-slider {
    position:absolute; inset:0;
    background:#2a2a2a; border-radius:30px;
    cursor:pointer;
}
.kemetic-slider:before {
    content:""; position:absolute;
    width:18px; height:18px;
    background:#F2C94C;
    border-radius:50%;
    top:3px; left:4px;
    transition:.3s;
}
.kemetic-switch input:checked + .kemetic-slider:before {
    transform:translateX(20px);
}

/* TABLE CARD */
.kemetic-table-card {
    background:linear-gradient(180deg,#121212,#0a0a0a);
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
    color:#aaa; font-size:13px;
    font-weight:600; text-align:center;
}
.kemetic-table tbody tr {
    background:#0f0f0f;
    border:1px solid #262626;
}
.kemetic-table td {
    padding:14px;
    text-align:center;
    vertical-align:middle;
}
.kemetic-table td.text-left { text-align:left; }

/* TITLE CELL */
.kemetic-title-cell .title {
    color:#fff; font-weight:600;
}
.kemetic-title-cell small {
    color:#888; display:block;
}

/* STATUS */
.status-badge {
    padding:4px 10px;
    border-radius:12px;
    font-size:12px;
}
.status-badge.active {
    background:#1f3d2b; color:#2ecc71;
}
.status-badge.inactive {
    background:#3d1f1f; color:#e74c3c;
}

/* SUCCESS */
.success { color:#2ecc71; display:block; font-size:12px; }

/* ACTIONS */
.kemetic-actions button {
    background:none; border:none; color:#F2C94C;
}
.kemetic-actions .dropdown-menu {
    background:#121212;
    border:1px solid #2a2a2a;
}
.kemetic-actions a {
    color:#fff; display:block;
    padding:8px 14px;
}
.kemetic-actions a:hover {
    background:#1a1a1a;
}

</style>
@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
@endpush

@section('content')

    {{-- Statistics --}}
    <section class="kemetic-stat-section">
        <h2 class="kemetic-title">{{ trans('panel.comments_statistics') }}</h2>

        <div class="kemetic-stat-card">
            <div class="row text-center">

                <div class="col-4">
                    <div class="kemetic-stat-item">
                        <div class="kemetic-stat-icon">
                            <img src="/assets/default/img/activity/46.svg" alt="">
                        </div>
                        <div class="kemetic-stat-value">{{ $quizzesCount }}</div>
                        <div class="kemetic-stat-label">{{ trans('quiz.quizzes') }}</div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="kemetic-stat-item">
                        <div class="kemetic-stat-icon">
                            <img src="/assets/default/img/activity/47.svg" alt="">
                        </div>
                        <div class="kemetic-stat-value">{{ $questionsCount }}</div>
                        <div class="kemetic-stat-label">{{ trans('public.questions') }}</div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="kemetic-stat-item">
                        <div class="kemetic-stat-icon">
                            <img src="/assets/default/img/activity/48.svg" alt="">
                        </div>
                        <div class="kemetic-stat-value">{{ $userCount }}</div>
                        <div class="kemetic-stat-label">{{ trans('quiz.students') }}</div>
                    </div>
                </div>

            </div>
        </div>
    </section>



    {{-- Filters --}}
    <section class="kemetic-filter-section mt-30">
        <h2 class="kemetic-title">{{ trans('quiz.filter_quizzes') }}</h2>

        <div class="kemetic-filter-card mt-20">
            <form action="/panel/quizzes" method="get" class="row g-3">

                {{-- Date range --}}
                <div class="col-12 col-lg-4">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="kemetic-label">{{ trans('public.from') }}</label>
                            <div class="kemetic-input-group">
                                <i data-feather="calendar"></i>
                                <input type="text"
                                    name="from"
                                    autocomplete="off"
                                    class="kemetic-input @if(request()->get('from')) datepicker @else datefilter @endif"
                                    value="{{ request()->get('from','') }}">
                            </div>
                        </div>

                        <div class="col-6">
                            <label class="kemetic-label">{{ trans('public.to') }}</label>
                            <div class="kemetic-input-group">
                                <i data-feather="calendar"></i>
                                <input type="text"
                                    name="to"
                                    autocomplete="off"
                                    class="kemetic-input @if(request()->get('to')) datepicker @else datefilter @endif"
                                    value="{{ request()->get('to','') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quiz filters --}}
                <div class="col-12 col-lg-6">
                    <div class="row g-2">

                        <div class="col-12 col-lg-6">
                            <label class="kemetic-label">{{ trans('quiz.quiz_or_webinar') }}</label>
                             <div class="kemetic-input-group">
                            <select name="quiz_id" class="kemetic-input select2" data-placeholder="{{ trans('public.all') }}">
                                <option value="all">{{ trans('public.all') }}</option>
                                @foreach($allQuizzesLists as $allQuiz)
                                    <option value="{{ $allQuiz->id }}"
                                        @if(request()->get('quiz_id') == $allQuiz->id) selected @endif>
                                        {{ $allQuiz->title .' - '. ($allQuiz->webinar ? $allQuiz->webinar->title : '-') }}
                                    </option>
                                @endforeach
                            </select>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="kemetic-label">{{ trans('public.total_mark') }}</label>
                                     <div class="kemetic-input-group">
                                    <input type="text"
                                        name="total_mark"
                                        class="kemetic-input"
                                        value="{{ request()->get('total_mark','') }}">
                                    </div>
                                </div>

                                <div class="col-6">
                                    <label class="kemetic-label">{{ trans('public.status') }}</label>
                                     <div class="kemetic-input-group">
                                    <select name="status" class="kemetic-input">
                                        <option value="all">{{ trans('public.all') }}</option>
                                        <option value="active" @if(request()->get('status') == 'active') selected @endif>
                                            {{ trans('public.active') }}
                                        </option>
                                        <option value="inactive" @if(request()->get('status') == 'inactive') selected @endif>
                                            {{ trans('public.inactive') }}
                                        </option>
                                    </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-12 col-lg-2 d-flex align-items-end">
                    <button type="submit" class="kemetic-btn w-100">
                        {{ trans('public.show_results') }}
                    </button>
                </div>

            </form>
        </div>
    </section>


    {{-- Quizzes Table --}}
    <section class="kemetic-section mt-30">

        <div class="kemetic-header d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
            <h2 class="kemetic-title">{{ trans('quiz.quizzes') }}</h2>

            <form action="/panel/quizzes" method="get">
                <div class="kemetic-switch-wrapper mt-15 mt-md-0">
                    <span class="kemetic-switch-label">{{ trans('quiz.show_only_active_quizzes') }}</span>
                    <label class="kemetic-switch">
                        <input type="checkbox" name="active_quizzes"
                            @if(request()->get('active_quizzes') == 'on') checked @endif>
                        <span class="kemetic-slider"></span>
                    </label>
                </div>
            </form>
        </div>

        @if($quizzes->count() > 0)

            <div class="kemetic-table-card mt-25">
                <div class="table-responsive">
                    <table class="kemetic-table">
                        <thead>
                        <tr>
                            <th class="text-left">{{ trans('public.title') }}</th>
                            <th>{{ trans('public.questions') }}</th>
                            <th>{{ trans('public.time') }} ({{ trans('public.min') }})</th>
                            <th>{{ trans('public.total_mark') }}</th>
                            <th>{{ trans('public.pass_mark') }}</th>
                            <th>{{ trans('quiz.students') }}</th>
                            <th>{{ trans('public.status') }}</th>
                            <th>{{ trans('public.date_created') }}</th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($quizzes as $quiz)
                            <tr>
                                <td class="text-left">
                                    <div class="kemetic-title-cell">
                                        <span class="title">{{ $quiz->title }}</span>
                                        <small>
                                            {{ !empty($quiz->webinar) ? $quiz->webinar->title : trans('panel.not_assign_any_webinar') }}
                                        </small>
                                    </div>
                                </td>

                                <td>
                                    {{ $quiz->quizQuestions->count() }}
                                    @if($quiz->display_limited_questions && $quiz->display_number_of_questions)
                                        <small>({{ trans('public.active') }}: {{ $quiz->display_number_of_questions }})</small>
                                    @endif
                                </td>

                                <td>{{ $quiz->time }}</td>
                                <td>{{ $quiz->quizQuestions->sum('grade') }}</td>
                                <td>{{ $quiz->pass_mark }}</td>

                                <td>
                                    <span>{{ $quiz->quizResults->pluck('user_id')->count() }}</span>
                                    @if(!empty($quiz->userSuccessRate))
                                        <small class="success">{{ $quiz->userSuccessRate }}% {{ trans('quiz.passed') }}</small>
                                    @endif
                                </td>

                                <td>
                                    <span class="status-badge {{ $quiz->status }}">
                                        {{ trans('public.'.$quiz->status) }}
                                    </span>
                                </td>

                                <td>{{ dateTimeFormat($quiz->created_at, 'j M Y') }}</td>

                                <td>
                                    <div class="dropdown kemetic-actions">
                                        <button data-toggle="dropdown">
                                            <i data-feather="more-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            @can('panel_quizzes_create')
                                                <a href="/panel/quizzes/{{ $quiz->id }}/edit">{{ trans('public.edit') }}</a>
                                            @endcan
                                            @can('panel_quizzes_delete')
                                                <a href="/panel/quizzes/{{ $quiz->id }}/delete"
                                                class="delete-action">{{ trans('public.delete') }}</a>
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
            @include(getTemplate().'.includes.no-result',[
                'file_name'=>'quiz.png',
                'title'=>trans('quiz.quiz_no_result'),
                'hint'=>nl2br(trans('quiz.quiz_no_result_hint')),
                'btn'=>['url'=>'/panel/quizzes/new','text'=>trans('quiz.create_a_quiz')]
            ])
        @endif

    </section>


    {{-- Pagination --}}
    <div class="my-30">
        {{ $quizzes->appends(request()->input())->links('vendor.pagination.panel') }}
    </div>

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/default/js/panel/quiz_list.min.js"></script>
@endpush
