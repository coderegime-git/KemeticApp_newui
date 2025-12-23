@extends('web.default.layouts.newapp')
<style>
    /* Quiz Header */
.quiz-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e1e2d;
}

.quiz-info a {
    color: #6b7280;
    text-decoration: underline;
}

/* Card Stats */
.kemetic-card {
    background: #fff;
    border-radius: 1rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    padding: 1.5rem;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #3b82f6;
    margin-top: 0.5rem;
}

.stat-label {
    font-size: 0.875rem;
    color: #6b7280;
}

/* Quiz Form */
.question-step {
    display: none;
}

.question-step.active {
    display: block;
}

.question-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1e1e2d;
    margin-top: 0.75rem;
}

.question-grade {
    font-size: 0.875rem;
    color: #6b7280;
}

.question-number {
    font-size: 0.875rem;
    color: #6b7280;
    background: #f1f3f5;
    padding: 0.25rem 0.5rem;
    border-radius: 0.5rem;
}

/* Multiple Choice */
.question-multi-answers .answer-item {
    margin-top: 0.5rem;
}

.answer-label {
    padding: 0.75rem 1rem;
    background: #f8f9fa;
    border-radius: 0.75rem;
    cursor: pointer;
    flex: 1;
    text-align: center;
    transition: all 0.2s ease-in-out;
}

.answer-label:hover {
    background: #e0e7ff;
}

/* Buttons */
.btn-primary {
    background-color: #3b82f6;
    border-color: #3b82f6;
}

.btn-danger {
    background-color: #ef4444;
    border-color: #ef4444;
}

.btn-primary:hover {
    background-color: #2563eb;
}

.btn-danger:hover {
    background-color: #dc2626;
}

/* Media */
.quiz-media img,
.quiz-media video {
    width: 100%;
    border-radius: 0.75rem;
    object-fit: cover;
}

</style>
@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/video/video-js.min.css">
<link rel="stylesheet" href="/assets/default/css/kemetic-quiz-start.css">
@endpush

@section('content')
<div class="container">

    <!-- Quiz Header & Stats -->
    <section class="mt-40">
        <h2 class="quiz-title">{{ $quiz->title }}</h2>
        <p class="quiz-info text-gray mt-5">
            <a href="{{ $quiz->webinar->getUrl() }}" target="_blank">{{ $quiz->webinar->title }}</a>
            | {{ trans('public.by') }}
            <span class="font-weight-bold">
                <a href="{{ $quiz->creator->getProfileUrl() }}" target="_blank">{{ $quiz->creator->full_name }}</a>
            </span>
        </p>

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
                        <img src="/assets/default/img/activity/47.svg" width="64" alt="">
                        <div class="stat-number">{{ $totalQuestionsCount }}</div>
                        <div class="stat-label">{{ trans('public.questions') }}</div>
                    </div>
                </div>

                <div class="col-6 col-md-3 mb-20">
                    <div class="kemetic-card text-center">
                        <img src="/assets/default/img/activity/clock.svg" width="64" alt="">
                        @if(!empty($quiz->time))
                            <div class="stat-number">
                                <div class="timer ltr" data-minutes-left="{{ $quiz->time }}"></div>
                            </div>
                        @else
                            <div class="stat-number">{{ trans('quiz.unlimited') }}</div>
                        @endif
                        <div class="stat-label">{{ trans('quiz.remaining_time') }}</div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Quiz Form -->
    <section class="mt-30 quiz-form">
        <form action="/panel/quizzes/{{ $quiz->id }}/store-result" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="quiz_result_id" value="{{ $newQuizStart->id }}">
            <input type="hidden" name="attempt_number" value="{{ $attempt_count }}">

            @foreach($quizQuestions as $key => $question)
            <fieldset class="question-step question-step-{{ $key + 1 }}">
                <div class="kemetic-card py-25 px-20 mb-30">

                    <div class="d-flex justify-content-between align-items-center">
                        <p class="question-grade">{{ trans('quiz.question_grade') }}: {{ $question->grade }}</p>
                        <div class="question-number">{{ $key + 1 }}/{{ $totalQuestionsCount }}</div>
                    </div>

                    @if(!empty($question->image) || !empty($question->video))
                    <div class="quiz-media mt-15 mb-15">
                        @if(!empty($question->image))
                            <img src="{{ $question->image }}" class="img-cover rounded-lg" alt="">
                        @else
                            <video id="questionVideo{{ $question->id }}" class="video-js" controls preload="auto" width="100%" data-setup='{"fluid": true}'>
                                <source src="{{ $question->video }}" type="video/mp4"/>
                            </video>
                        @endif
                    </div>
                    @endif

                    <h3 class="question-title">{{ $question->title }}</h3>

                    @if($question->type === \App\Models\QuizzesQuestion::$descriptive)
                        <textarea name="question[{{ $question->id }}][answer]" rows="15" class="form-control mt-20"></textarea>
                    @else
                        <div class="question-multi-answers mt-20">
                            @foreach($question->quizzesQuestionsAnswers as $answer)
                                <div class="answer-item">
                                    <input id="asw-{{ $answer->id }}" type="radio" name="question[{{ $question->id }}][answer]" value="{{ $answer->id }}">
                                    <label for="asw-{{ $answer->id }}" class="answer-label d-flex align-items-center justify-content-center">
                                        @if(!empty($answer->image))
                                            <img src="{{ config('app_url') . $answer->image }}" class="img-cover rounded" alt="">
                                        @else
                                            {{ $answer->title }}
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </fieldset>
            @endforeach

            <div class="d-flex mt-30">
                <button type="button" class="previous btn btn-primary mr-15">{{ trans('quiz.previous_question') }}</button>
                <button type="button" class="next btn btn-primary mr-auto">{{ trans('quiz.next_question') }}</button>
                <button type="submit" class="finish btn btn-danger">{{ trans('public.finish') }}</button>
            </div>
        </form>
    </section>

</div>
@endsection

@push('scripts_bottom')
<script src="/assets/default/vendors/video/video.min.js"></script>
<script src="/assets/default/vendors/jquery.simple.timer/jquery.simple.timer.js"></script>
<script src="/assets/default/js/parts/quiz-start.min.js"></script>
@endpush
