@extends('web.default.layouts.newapp')
<style>
    /* Kemetic Card Styling */
.kemetic-section {
    padding: 15px 0;
}

.kemetic-title {
    font-size: 20px;
    color: #f2c94c;
    font-weight: 600;
    border-left: 4px solid #f2c94c;
    padding-left: 12px;
    margin-bottom: 15px;
}

/* CARD */
.kemetic-card {
    background-color: #0f0f0f;
    border: 1px solid rgba(242,201,76,0.35);
    border-radius: 14px;
    box-shadow: 0 0 20px rgba(242,201,76,0.15);
}

/* INPUTS */
.kemetic-label {
    color: #f2c94c;
    font-weight: 500;
    font-size: 14px;
    margin-bottom: 5px;
}

.kemetic-input-group .kemetic-icon {
    background-color: #f2c94c;
    color: #0f0f0f;
}

.kemetic-input {
    background-color: #1a1a1a;
    border: 1px solid rgba(242,201,76,0.4);
    color: #f2c94c;
    border-radius: 6px;
}

.kemetic-input:focus {
    background-color: #1a1a1a;
    border-color: #f2c94c;
    box-shadow: 0 0 6px rgba(242,201,76,0.5);
    color: #f2c94c;
}

/* BUTTON */
.kemetic-btn {
    background-color: #f2c94c;
    color: #0f0f0f;
    font-weight: 600;
    border-radius: 6px;
    transition: all 0.3s;
}

.kemetic-btn:hover {
    background-color: #e0b93f;
    color: #0f0f0f;
    box-shadow: 0 0 10px rgba(242,201,76,0.5);
}
.kemetic-section {
    padding: 15px 0;
}

.kemetic-title {
    font-size: 22px;
    color: #f2c94c;
    font-weight: 600;
    border-left: 4px solid #f2c94c;
    padding-left: 12px;
}

.kemetic-card {
    background-color: #0f0f0f;
    border: 1px solid rgba(242,201,76,0.3);
    border-radius: 14px;
    box-shadow: 0 0 20px rgba(242,201,76,0.1);
}

.kemetic-table {
    width: 100%;
    color: #f2c94c;
}

.kemetic-table thead tr {
    background-color: #1a1a1a;
    color: #f2c94c;
}

.kemetic-table tbody tr {
    background-color: #0f0f0f;
    border-bottom: 1px solid rgba(242,201,76,0.2);
}

.kemetic-table tbody tr:hover {
    background-color: rgba(242,201,76,0.05);
}

.kemetic-btn-sm {
    background-color: transparent;
    border: 1px solid #f2c94c;
    color: #f2c94c;
    border-radius: 6px;
    padding: 3px 10px;
    transition: all 0.3s;
}

.kemetic-btn-sm:hover {
    background-color: #f2c94c;
    color: #0f0f0f;
}

.text-gold {
    color: #f2c94c !important;
}


</style>
@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')
<section class="mt-35 kemetic-section">
    <h2 class="kemetic-title">{{ trans('quiz.filter_results') }}</h2>

    <div class="kemetic-card mt-20 p-25" style="padding:10px;">
        <form action="/panel/quizzes/opens" method="get" class="row gx-3 gy-3">
            
            {{-- DATE FILTER --}}
            <div class="col-12 col-lg-4">
                <div class="row gx-2 gy-2">
                    <div class="col-6">
                        <label class="kemetic-label">{{ trans('public.from') }}</label>
                        <div class="input-group kemetic-input-group">
                            <span class="input-group-text kemetic-icon bg-gold text-black">
                                <i data-feather="calendar" width="18" height="18"></i>
                            </span>
                            <input type="text" name="from" autocomplete="off"
                                   class="form-control kemetic-input @if(request()->get('from')) datepicker @else datefilter @endif"
                                   value="{{ request()->get('from','') }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="kemetic-label">{{ trans('public.to') }}</label>
                        <div class="input-group kemetic-input-group">
                            <span class="input-group-text kemetic-icon bg-gold text-black">
                                <i data-feather="calendar" width="18" height="18"></i>
                            </span>
                            <input type="text" name="to" autocomplete="off"
                                   class="form-control kemetic-input @if(request()->get('to')) datepicker @else datefilter @endif"
                                   value="{{ request()->get('to','') }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- QUIZ & INSTRUCTOR FILTER --}}
            <div class="col-12 col-lg-6">
                <div class="row gx-2 gy-2">
                    <div class="col-6">
                        <label class="kemetic-label">{{ trans('quiz.quiz_or_webinar') }}</label>
                        <input type="text" name="quiz_or_webinar" class="form-control kemetic-input"
                               value="{{ request()->get('quiz_or_webinar','') }}">
                    </div>
                    <div class="col-6">
                        <label class="kemetic-label">{{ trans('public.instructor') }}</label>
                        <input type="text" name="instructor" class="form-control kemetic-input"
                               value="{{ request()->get('instructor','') }}">
                    </div>
                </div>
            </div>

            {{-- SUBMIT BUTTON --}}
           <div class="col-12 col-lg-2 d-flex align-items-end">
                <button type="submit" class="kemetic-btn w-100">
                    {{ trans('public.show_results') }}
                </button>
            </div>
        </form>
    </div>
</section>


<section class="mt-35 kemetic-section">
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="kemetic-title">{{ trans('quiz.open_quizzes') }}</h2>
    </div>

    @if($quizzes->count())
        <div class="kemetic-card mt-20 p-20">
            <div class="table-responsive">
                <table class="table kemetic-table">
                    <thead>
                        <tr>
                            <th>{{ trans('public.instructor') }}</th>
                            <th>{{ trans('quiz.quiz') }}</th>
                            <th class="text-center">{{ trans('quiz.quiz_grade') }}</th>
                            <th class="text-center">{{ trans('public.date') }}</th>
                            <th class="text-end"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quizzes as $quiz)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar rounded-circle bg-gray200">
                                        <img src="{{ $quiz->creator->getAvatar() }}" class="img-cover" alt="">
                                    </div>
                                    <div class="ms-2">
                                        <div class="fw-500 text-gold">{{ $quiz->creator->full_name }}</div>
                                        <div class="text-gray small">{{ $quiz->creator->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-500 text-gold">{{ $quiz->title }}</div>
                                <div class="text-gray small">{{ $quiz->webinar->title }}</div>
                            </td>
                            <td class="text-center fw-500 text-gold">{{ $quiz->quizQuestions->sum('grade') }}</td>
                            <td class="text-center fw-500 text-gray">{{ dateTimeFormat($quiz->created_at,'j M Y H:i') }}</td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn kemetic-btn-sm" type="button" data-bs-toggle="dropdown">
                                        <i data-feather="more-vertical" class="text-gold"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item text-gold" href="/panel/quizzes/{{ $quiz->id }}/start">{{ trans('public.start') }}</a></li>
                                        <li><a class="dropdown-item text-gold" target="_blank" href="{{ $quiz->webinar->getUrl() }}">{{ trans('webinars.webinar_page') }}</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        @include(getTemplate() . '.includes.no-result', [
            'file_name' => 'result.png',
            'title' => trans('quiz.quiz_result_no_result'),
            'hint' => trans('quiz.quiz_result_no_result_hint')
        ])
    @endif
</section>


<div class="my-30">
    {{ $quizzes->appends(request()->input())->links('vendor.pagination.panel') }}
</div>
@endsection

@push('scripts_bottom')
<script src="/assets/default/vendors/moment.min.js"></script>
<script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
@endpush
