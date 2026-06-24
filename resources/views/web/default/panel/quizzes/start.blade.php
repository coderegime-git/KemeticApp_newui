@extends('web.default.layouts.newapp')
@push('styles_top')
    <style>
        /* Quiz Header */
        .quiz-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #F2C94C;
        }

        .quiz-info a {
            color: #ccc;
            text-decoration: underline;
        }

        /* Card Stats */
        .kemetic-card {
            background: #1C1C1C;
            border: 1px solid #333;
            border-radius: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            padding: 1.5rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #F2C94C;
            margin-top: 0.5rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #aaa;
        }

        /* Timer Fix */
        .timer {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .timer div,
        .timer span {
            display: inline-block;
            margin: 0 1px;
        }

        /* Hide duplicated timer elements if script runs twice */
        .timer .jst-hours~.jst-hours,
        .timer .jst-minutes~.jst-minutes,
        .timer .jst-seconds~.jst-seconds,
        .timer .jst-clearDiv {
            display: none !important;
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
            color: #fff;
            margin-top: 0.75rem;
        }

        .question-grade {
            font-size: 0.875rem;
            color: #aaa;
        }

        .question-number {
            font-size: 0.875rem;
            color: #1C1C1C;
            background: #F2C94C;
            padding: 0.25rem 0.5rem;
            border-radius: 0.5rem;
            font-weight: bold;
        }

        /* Multiple Choice */
        .question-multi-answers .answer-item {
            margin-top: 0.5rem;
        }

        .answer-item input[type="radio"] {
            display: none;
        }

        .answer-label {
            padding: 0.75rem 1rem;
            background: #2A2A2A;
            color: #ccc;
            border: 1px solid #444;
            border-radius: 0.75rem;
            cursor: pointer;
            flex: 1;
            text-align: left;
            transition: all 0.2s ease-in-out;
            display: flex;
            align-items: center;
        }

        /* Custom radio circle */
        .answer-label::before {
            content: '';
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 12px;
            border: 2px solid #666;
            border-radius: 50%;
            background: transparent;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .answer-label:hover {
            background: #333;
            border-color: #F2C94C;
            color: #fff;
        }

        .answer-label:hover::before {
            border-color: #F2C94C;
        }

        .answer-item input[type="radio"]:checked+.answer-label {
            background: #F2C94C;
            color: #1C1C1C;
            border-color: #F2C94C;
            font-weight: bold;
        }

        .answer-item input[type="radio"]:checked+.answer-label::before {
            border-color: #1C1C1C;
            background: #1C1C1C;
            box-shadow: inset 0 0 0 4px #F2C94C;
        }

        /* Buttons */
        .btn-gold {
            background-color: #F2C94C !important;
            color: #1C1C1C !important;
            border-color: #F2C94C !important;
            opacity: 1 !important;
        }

        .btn-gold:hover {
            background-color: #d4b03c !important;
            color: #1C1C1C !important;
        }

        .btn-gold:disabled {
            background-color: #555 !important;
            color: #999 !important;
            border-color: #555 !important;
            opacity: 1 !important;
            cursor: not-allowed;
        }

        .btn-danger {
            background-color: #ef4444 !important;
            border-color: #ef4444 !important;
            color: #fff !important;
        }

        .btn-danger:hover {
            background-color: #dc2626 !important;
        }

        .quiz-media img {
            max-width: 200px !important;
            max-height: 200px !important;
            border-radius: 0.75rem;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }

        .quiz-media iframe {
            width: 100% !important;
            max-width: 100% !important;
            height: auto;
            border-radius: 0.75rem;
            display: block;
            margin: 0 auto;
            aspect-ratio: 16/9;
        }

        .quiz-media video {
            width: 100% !important;
            max-height: 450px !important;
            border-radius: 0.75rem;
            display: block;
            margin: 0 auto;
            object-fit: contain;
            background: #000;
        }
    </style>
    <link rel="stylesheet" href="/assets/default/vendors/video/video-js.min.css">
    <link rel="stylesheet" href="/assets/default/css/kemetic-quiz-start.css">
@endpush

@section('content')
    <div class="container" style="max-width: 900px; margin: 0 auto;">

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

        <section class="mt-30 quiz-form">
            <form action="/panel/quizzes/{{ $quiz->id }}/store-result" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="quiz_result_id" value="{{ $newQuizStart->id }}">
                <input type="hidden" name="attempt_number" value="{{ $attempt_count }}">
                <input type="hidden" class="js-quiz-question-count" value="{{ $totalQuestionsCount }}">

                @foreach($quizQuestions as $key => $question)
                    <fieldset class="question-step question-step-{{ $key + 1 }} {{ $key == 0 ? 'active' : '' }}">
                        <div class="kemetic-card py-25 px-20 mb-30">

                            <div class="d-flex justify-content-between align-items-center">
                                <p class="question-grade">{{ trans('quiz.question_grade') }}: {{ $question->grade }}</p>
                                <div class="question-number">{{ $key + 1 }}/{{ $totalQuestionsCount }}</div>
                            </div>

                            @if(!empty($question->image) || !empty($question->video))
                                <div class="quiz-media mt-15 mb-15">
                                    @if(!empty($question->image))
                                        <img src="{{ $question->image }}" class="img-cover rounded-lg" alt=""
                                            style="width:200px;hegight:100px;">
                                    @else
                                        @php
                                            $isYouTube = false;
                                            $youtubeId = '';
                                            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $question->video, $matches)) {
                                                $isYouTube = true;
                                                $youtubeId = $matches[1];
                                            }
                                        @endphp

                                        @if($isYouTube)
                                            <iframe
                                                src="https://www.youtube.com/embed/{{ $youtubeId }}?autoplay=0&controls=1&rel=0&modestbranding=1"
                                                frameborder="0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen style="width: 100%; aspect-ratio: 16/9; border-radius: 0.75rem;"></iframe>
                                        @else
                                            <video id="questionVideo{{ $question->id }}" controls preload="auto" style="width: 100%; max-height: 450px; background: #000; border-radius: 0.75rem;">
                                                <source src="{{ $question->video }}" type="video/mp4" />
                                            </video>
                                        @endif
                                    @endif
                                </div>
                            @endif

                            <h3 class="question-title">{{ $question->title }}</h3>

                            @if($question->type === \App\Models\QuizzesQuestion::$descriptive)
                                <textarea name="question[{{ $question->id }}][answer]" rows="15" class="form-control mt-20"
                                    style="background: #2A2A2A; color: #fff; border: 1px solid #444;"></textarea>
                            @else
                                <div class="question-multi-answers mt-20">
                                    @foreach($question->quizzesQuestionsAnswers as $answer)
                                        <div class="answer-item">
                                            <input id="asw-{{ $answer->id }}" type="radio" name="question[{{ $question->id }}][answer]"
                                                value="{{ $answer->id }}">
                                            <label for="asw-{{ $answer->id }}"
                                                class="answer-label d-flex align-items-center justify-content-center">
                                                @if(!empty($answer->image))
                                                    <img src="{{ config('app_url') . $answer->image }}" class="img-cover rounded" alt=""
                                                        style="width:200px;hegight:100px;">
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

                <div class="d-flex mt-30 justify-content-center align-items-center" style="gap: 15px;margin-top:10px;">
                    <button type="button" class="previous btn btn-gold"
                        disabled>{{ trans('quiz.previous_question') }}</button>
                    <button type="button" class="next btn btn-gold" {{ $totalQuestionsCount <= 1 ? 'disabled' : '' }}>{{ trans('quiz.next_question') }}</button>
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