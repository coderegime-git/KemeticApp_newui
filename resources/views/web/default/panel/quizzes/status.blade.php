@extends('web.default.layouts.newapp')

<style>
    /* Header */
.quiz-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e1e2d;
}
.quiz-info {
    font-size: 0.9rem;
    color: #6b7280;
}

/* Stats Cards */
.kemetic-card {
    background: #fff;
    border-radius: 1rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    padding: 1.5rem;
}
.stat-number {
    font-size: 1.75rem;
    font-weight: 700;
    color: #3b82f6;
    margin-top: 0.5rem;
}
.stat-label {
    font-size: 0.875rem;
    color: #6b7280;
}

/* Status Cards */
.kemetic-status-card {
    background: #fff;
    border-radius: 1rem;
    box-shadow: 0 6px 20px rgba(0,0,0,0.07);
    padding: 2rem;
}
.status-logo img {
    max-width: 120px;
}
.status-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1e1e2d;
}
.status-message {
    font-size: 1rem;
    color: #6b7280;
}
.status-actions .btn {
    margin: 0 0.5rem;
}
.text-primary { color: #3b82f6 !important; }
.text-warning { color: #f59e0b !important; }
.text-danger { color: #ef4444 !important; }

</style>

@section('content')
<div class="container">

    <!-- Quiz Header & Stats -->
    <section class="mt-40">
        <h2 class="quiz-title">{{ trans('quiz.level_identification_quiz') }}</h2>
        <p class="quiz-info mt-5">{{ $quiz->title }} | {{ trans('public.by') }} <span class="font-weight-bold">{{ $quiz->creator->full_name }}</span></p>

        <div class="quiz-stats mt-25">
            <div class="row">

                <div class="col-6 col-md-3 mb-20">
                    <div class="kemetic-card text-center">
                        <img src="/assets/default/img/activity/58.svg" width="64" alt="">
                        <div class="stat-number">{{ $quiz->pass_mark }}/{{ $quizQuestions->sum('grade') }}</div>
                        <div class="stat-label">{{ trans('public.min') }} {{ trans('quiz.grade') }}</div>
                    </div>
                </div>

                <div class="col-6 col-md-3 mb-20">
                    <div class="kemetic-card text-center">
                        <img src="/assets/default/img/activity/88.svg" width="64" alt="">
                        <div class="stat-number">{{ $attempt_count }}/{{ $quiz->attempt }}</div>
                        <div class="stat-label">{{ trans('quiz.attempts') }}</div>
                    </div>
                </div>

                <div class="col-6 col-md-3 mb-20">
                    <div class="kemetic-card text-center">
                        <img src="/assets/default/img/activity/45.svg" width="64" alt="">
                        <div class="stat-number">{{ $quizResult->user_grade }}/{{ $quizQuestions->sum('grade') }}</div>
                        <div class="stat-label">{{ trans('quiz.your_grade') }}</div>
                    </div>
                </div>

                <div class="col-6 col-md-3 mb-20">
                    <div class="kemetic-card text-center">
                        <img src="/assets/default/img/activity/44.svg" width="64" alt="">
                        <div class="stat-number text-{{ ($quizResult->status == 'passed') ? 'primary' : ($quizResult->status == 'waiting' ? 'warning' : 'danger') }}">
                            {{ trans('quiz.'.$quizResult->status) }}
                        </div>
                        <div class="stat-label">{{ trans('public.status') }}</div>
                    </div>
                </div>

            </div>
        </div>
    </section>

        <section class="mt-30 rounded-lg shadow-sm py-25 px-20">

                @switch($quizResult->status)

                @case(\App\Models\QuizzesResult::$passed)
                    <div class="no-result default-no-result mt-50 d-flex align-items-center justify-content-center flex-column">
                        <div class="no-result-logo">
                            <img src="/assets/default/img/no-results/497.png" alt="">
                        </div>
                        <div class="d-flex align-items-center flex-column mt-30 text-center">
                            <h2 class="section-title">{{ trans('quiz.status_passed_title') }}</h2>
                            <p class="mt-5 text-center">{!! trans('quiz.status_passed_hint',['grade' => $quizResult->user_grade.'/'.$quizQuestions->sum('grade')]) !!}</p>

                            @if($quiz->certificate)
                                <p>{{ trans('quiz.you_can_download_certificate') }}</p>
                            @endif

                            <div class=" mt-25">
                                <a href="/panel/quizzes/my-results" class="btn btn-sm btn-primary">{{ trans('public.show_results') }}</a>

                                @if($quiz->certificate)
                                    <a href="/panel/quizzes/results/{{ $quizResult->id }}/showCertificate" class="btn btn-sm btn-primary">{{ trans('quiz.download_certificate') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @break

                @case(\App\Models\QuizzesResult::$failed)
                    <div class="no-result status-failed mt-50 d-flex align-items-center justify-content-center flex-column">
                        <div class="no-result-logo">
                            <img src="/assets/default/img/no-results/339.png" alt="">
                        </div>
                        <div class="d-flex align-items-center flex-column mt-30 text-center">
                            <h2 class="section-title">{{ trans('quiz.status_failed_title') }}</h2>
                            <p class="mt-5 text-center">{!! trans('quiz.status_failed_hint',['min_grade' =>  $quiz->pass_mark .'/'. $quizQuestions->sum('grade'),'user_grade' => $quizResult->user_grade]) !!}</p>
                            @if($canTryAgain)
                                <p>{{ trans('public.you_can_try_again') }}</p>
                            @endif
                            <div class=" mt-25">
                                @if($canTryAgain)
                                    <a href="/panel/quizzes/{{ $quiz->id }}/start" class="btn btn-sm btn-primary">{{ trans('public.try_again') }}</a>
                                @endif
                                <a href="/panel/quizzes/my-results" class="btn btn-sm btn-primary">{{ trans('public.show_results') }}</a>
                            </div>
                        </div>
                    </div>
                @break

                @case(\App\Models\QuizzesResult::$waiting)
                    <div class="no-result status-waiting mt-50 d-flex align-items-center justify-content-center flex-column">
                        <div class="no-result-logo">
                            <img src="/assets/default/img/no-results/242.png" alt="">
                        </div>
                        <div class="d-flex align-items-center flex-column mt-30 text-center">
                            <h2 class="section-title">{{ trans('quiz.status_waiting_title') }}</h2>
                            <p class="mt-5 text-center">{!! nl2br(trans('quiz.status_waiting_hint')) !!}</p>
                            <div class=" mt-25">
                                <a href="/panel/quizzes/my-results" class="btn btn-sm btn-primary">{{ trans('public.show_results') }}</a>
                            </div>
                        </div>
                    </div>
                @break
            @endswitch

        </section>

    </div>
@endsection
