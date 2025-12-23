@extends('web.default.layouts.newapp')
<style>
    /* Kemetic Card Styling */
.kemetic-card {
    background-color: #ffffff;
    border-radius: 1rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    padding: 1.5rem;
}

/* Section Title */
.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1e1e2d;
}

/* Stats */
.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #3b82f6;
    display: block;
    margin-top: 0.5rem;
}

.stat-label {
    font-size: 0.9rem;
    color: #6b7280;
}

/* Quiz Card */
.quiz-card {
    margin-top: 1rem;
}

.question-number {
    font-size: 0.875rem;
    background-color: #f1f3f5;
}

/* Multi answers */
.answer-item {
    margin-bottom: 1rem;
    position: relative;
}

.answer-label {
    display: flex;
    align-items: center;
    background-color: #f8f9fa;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    cursor: pointer;
}

.answer-label:hover {
    background-color: #e5e7eb;
}

.answer-label input[type="radio"] {
    display: none;
}

.answer-label .selected {
    position: absolute;
    top: -10px;
    right: 10px;
    font-size: 0.75rem;
    color: #3b82f6;
}

/* Image container */
.image-container {
    position: relative;
    display: inline-block;
}

.image-container img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 0.5rem;
}

/* Buttons */
.btn-primary {
    background-color: #3b82f6;
    border-color: #3b82f6;
    color: #fff;
}

.btn-primary:hover {
    background-color: #2563eb;
}

.btn-danger {
    background-color: #ef4444;
    border-color: #ef4444;
    color: #fff;
}

.btn-danger:hover {
    background-color: #dc2626;
}

</style>
@push('styles_top')
<link rel="stylesheet" href="/assets/default/css/kemetic-quiz.css">
@endpush

@section('content')
<div class="container">
    <!-- Quiz Header -->
    <section class="mt-40">
        <h2 class="section-title">{{ $quiz->title }}</h2>
        <p class="text-gray font-14 mt-5">
            <a href="{{ $quiz->webinar->getUrl() }}" target="_blank" class="text-gray">{{ $quiz->webinar->title }}</a>
            | {{ trans('public.by') }}
            <span class="font-weight-bold">
                <a href="{{ $quiz->creator->getProfileUrl() }}" target="_blank">{{ $quiz->creator->full_name }}</a>
            </span>
        </p>

        <!-- Quiz Stats -->
        <div class="kemetic-card shadow-sm rounded-xl mt-25 p-25">
            <div class="row text-center">
                <div class="col-6 col-md-3 mb-20">
                    <img src="/assets/default/img/activity/58.svg" width="64" height="64" alt="">
                    <strong class="stat-number">{{ $quiz->pass_mark }}/{{ $questionsSumGrade }}</strong>
                    <div class="stat-label">{{ trans('public.min') }} {{ trans('quiz.grade') }}</div>
                </div>
                <div class="col-6 col-md-3 mb-20">
                    <img src="/assets/default/img/activity/88.svg" width="64" height="64" alt="">
                    <strong class="stat-number">{{ $numberOfAttempt }}/{{ $quiz->attempt }}</strong>
                    <div class="stat-label">{{ trans('quiz.attempts') }}</div>
                </div>
                <div class="col-6 col-md-3 mb-20">
                    <img src="/assets/default/img/activity/45.svg" width="64" height="64" alt="">
                    <strong class="stat-number">{{ $quizResult->user_grade }}/{{ $questionsSumGrade }}</strong>
                    <div class="stat-label">{{ trans('quiz.your_grade') }}</div>
                </div>
                <div class="col-6 col-md-3 mb-20">
                    <img src="/assets/default/img/activity/44.svg" width="64" height="64" alt="">
                    <strong class="stat-number text-{{ $quizResult->status == 'passed' ? 'primary' : ($quizResult->status == 'waiting' ? 'warning' : 'danger') }}">
                        {{ trans('quiz.' . $quizResult->status) }}
                    </strong>
                    <div class="stat-label">{{ trans('public.status') }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quiz Questions -->
    <section class="mt-30 quiz-form">
        <form action="{{ !empty($newQuizStart) ? '/panel/quizzes/'. $newQuizStart->quiz->id .'/update-result' : '' }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="quiz_result_id" value="{{ !empty($newQuizStart) ? $newQuizStart->id : '' }}">
            <input type="hidden" name="attempt_number" value="{{ $numberOfAttempt }}">
            <input type="hidden" class="js-quiz-question-count" value="{{ $quizQuestions->count() }}">

            @foreach($quizQuestions as $key => $question)
                <fieldset class="question-step question-step-{{ $key + 1 }}">
                    <div class="kemetic-card shadow-sm rounded-xl p-25 mb-25">
                        <div class="quiz-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="font-weight-bold text-secondary">{{ $question->title }}?</h3>
                                    <p class="text-gray mt-5">
                                        <span>{{ trans('quiz.question_grade') }} : {{ $question->grade }}</span> | 
                                        <span>{{ trans('quiz.your_grade') }} : {{ (!empty($userAnswers[$question->id]['grade'])) ? $userAnswers[$question->id]['grade'] : 0 }}</span>
                                    </p>
                                </div>
                                <div class="question-number rounded-sm border border-gray200 p-10">{{ $key + 1 }}/{{ $quizQuestions->count() }}</div>
                            </div>

                            @if($question->type === \App\Models\QuizzesQuestion::$descriptive)
                                <div class="form-group mt-25">
                                    <label class="input-label text-secondary">{{ trans('quiz.student_answer') }}</label>
                                    <textarea class="form-control" rows="8" disabled>{{ (!empty($userAnswers[$question->id]['answer'])) ? $userAnswers[$question->id]['answer'] : '' }}</textarea>
                                </div>

                                <div class="form-group mt-25">
                                    <label class="input-label text-secondary">{{ trans('quiz.correct_answer') }}</label>
                                    <textarea class="form-control" rows="8" 
                                              @if(empty($newQuizStart) or $newQuizStart->quiz->creator_id != $authUser->id) disabled @endif>{{ $question->correct }}</textarea>
                                </div>

                                @if(!empty($newQuizStart) && $newQuizStart->quiz->creator_id == $authUser->id)
                                    <div class="form-group mt-25">
                                        <label class="input-label">{{ trans('quiz.grade') }}</label>
                                        <input type="text" class="form-control" name="question[{{ $question->id }}][grade]" 
                                               value="{{ (!empty($userAnswers[$question->id]['grade'])) ? $userAnswers[$question->id]['grade'] : 0 }}">
                                    </div>
                                @endif

                            @else
                                <div class="question-multi-answers mt-25">
                                    @foreach($question->quizzesQuestionsAnswers as $answer)
                                        <div class="answer-item">
                                            @if($answer->correct)
                                                <span class="badge badge-primary correct">{{ trans('quiz.correct') }}</span>
                                            @endif
                                            <input type="radio" id="asw-{{ $answer->id }}" name="question[{{ $question->id }}][answer]" 
                                                   value="{{ $answer->id }}" disabled
                                                   {{ (!empty($userAnswers[$question->id]) && $userAnswers[$question->id]['answer'] == $answer->id) ? 'checked' : '' }}>
                                            <label for="asw-{{ $answer->id }}" class="answer-label">
                                                @if($answer->image)
                                                    <div class="image-container">
                                                        <img src="{{ config('app_url') . $answer->image }}" class="img-cover" alt="">
                                                        @if(!empty($userAnswers[$question->id]) && $userAnswers[$question->id]['answer'] == $answer->id)
                                                            <span class="selected">{{ trans('quiz.student_answer') }}</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="answer-title">{{ $answer->title }}</span>
                                                    @if(!empty($userAnswers[$question->id]) && $userAnswers[$question->id]['answer'] == $answer->id)
                                                        <span class="d-block">({{ trans('quiz.student_answer') }})</span>
                                                    @endif
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </fieldset>
            @endforeach

            <div class="d-flex align-items-center mt-30">
                <button type="button" class="previous btn btn-primary btn-sm mr-20" disabled>{{ trans('quiz.previous_question') }}</button>
                <button type="button" class="next btn btn-primary btn-sm mr-auto">{{ trans('quiz.next_question') }}</button>
                @if(!empty($newQuizStart))
                    <button type="submit" class="finish btn btn-danger btn-sm">{{ trans('public.finish') }}</button>
                @endif
            </div>
        </form>
    </section>
</div>
@endsection

@push('scripts_bottom')
<script src="/assets/default/js/parts/quiz-start.min.js"></script>
@endpush
