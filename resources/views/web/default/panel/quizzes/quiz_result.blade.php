@extends('web.default.layouts.newapp')
@push('styles_top')
<style>
    /* ==========================================
       Quiz Result Page – Dark / Gold Theme
       ========================================== */

    /* Page container */
    .qr-wrap {
        max-width: 900px;
        margin: 0 auto;
    }

    /* Quiz title */
    .qr-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #F2C94C;
    }

    .qr-info a {
        color: #aaa;
        text-decoration: underline;
    }

    /* Stat cards row */
    .qr-stat-card {
        background: #1C1C1C;
        border: 1px solid #333;
        border-radius: 1rem;
        padding: 1.25rem 1rem;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.35);
    }

    .qr-stat-number {
        font-size: 1.75rem;
        font-weight: 700;
        color: #F2C94C;
        display: block;
        margin-top: 0.5rem;
    }

    .qr-stat-number.status-passed  { color: #4ade80; }
    .qr-stat-number.status-failed  { color: #f87171; }
    .qr-stat-number.status-waiting { color: #facc15; }

    .qr-stat-label {
        font-size: 0.8rem;
        color: #888;
        margin-top: 0.25rem;
    }

    /* Question steps – hidden by default, first shown via inline style on element */
    .qr-question-step {
        display: none;
    }

    /* Question card */
    .qr-question-card {
        background: #1C1C1C;
        border: 1px solid #333;
        border-radius: 1rem;
        padding: 1.5rem 1.25rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.25);
    }

    .qr-question-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #fff;
        margin-top: 0.5rem;
    }

    .qr-question-meta {
        font-size: 0.85rem;
        color: #999;
        margin-top: 0.35rem;
    }

    .qr-question-badge {
        background: #F2C94C;
        color: #1C1C1C;
        font-weight: 700;
        font-size: 0.8rem;
        padding: 0.3rem 0.65rem;
        border-radius: 0.5rem;
        white-space: nowrap;
    }

    /* Answer items */
    .qr-answer-item {
        position: relative;
        margin-top: 0.75rem;
    }

    .qr-answer-item input[type="radio"] {
        display: none;
    }

    .qr-answer-label {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #2A2A2A;
        color: #ccc;
        border: 1px solid #444;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        cursor: default;
        transition: border-color 0.2s;
    }

    /* Custom radio circle */
    .qr-answer-label::before {
        content: '';
        display: inline-block;
        width: 18px;
        height: 18px;
        border: 2px solid #555;
        border-radius: 50%;
        background: transparent;
        flex-shrink: 0;
        transition: all 0.2s;
    }

    /* Correct answer highlight */
    .qr-answer-item.answer-correct .qr-answer-label {
        background: rgba(74, 222, 128, 0.12);
        border-color: #4ade80;
        color: #d1fae5;
    }

    .qr-answer-item.answer-correct .qr-answer-label::before {
        border-color: #4ade80;
        background: #4ade80;
        box-shadow: inset 0 0 0 4px #1C1C1C;
    }

    /* Student selected answer */
    .qr-answer-item.student-selected .qr-answer-label {
        background: rgba(242, 201, 76, 0.15);
        border-color: #F2C94C;
        color: #fff;
        font-weight: 600;
    }

    .qr-answer-item.student-selected .qr-answer-label::before {
        border-color: #F2C94C;
        background: #1C1C1C;
        box-shadow: inset 0 0 0 4px #F2C94C;
    }

    /* Wrong selected answer */
    .qr-answer-item.student-wrong .qr-answer-label {
        background: rgba(248, 113, 113, 0.12);
        border-color: #f87171;
        color: #fca5a5;
    }

    .qr-answer-item.student-wrong .qr-answer-label::before {
        border-color: #f87171;
        background: #f87171;
        box-shadow: inset 0 0 0 4px #1C1C1C;
    }

    /* Answer image */
    .qr-answer-img {
        width: 175px;
        height: 175px;
        max-width: 175px;
        max-height: 175px;
        object-fit: cover;
        border-radius: 0.5rem;
        flex-shrink: 0;
    }

    /* Correct badge */
    .qr-correct-badge {
        display: inline-block;
        background: #4ade80;
        color: #064e3b;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.15rem 0.5rem;
        border-radius: 0.35rem;
        margin-left: 8px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .qr-student-badge {
        display: inline-block;
        background: #F2C94C;
        color: #1C1C1C;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.15rem 0.5rem;
        border-radius: 0.35rem;
        margin-left: 8px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Textareas */
    .qr-textarea {
        background: #2A2A2A !important;
        color: #ddd !important;
        border: 1px solid #444 !important;
        border-radius: 0.75rem !important;
        resize: vertical;
    }

    .qr-textarea:focus {
        border-color: #F2C94C !important;
        outline: none;
        box-shadow: 0 0 0 3px rgba(242,201,76,0.15) !important;
    }

    .qr-input-label {
        font-size: 0.875rem;
        color: #aaa;
        font-weight: 600;
        margin-bottom: 0.4rem;
    }

    /* Grade input */
    .qr-grade-input {
        background: #2A2A2A !important;
        color: #fff !important;
        border: 1px solid #444 !important;
        border-radius: 0.5rem !important;
        max-width: 120px;
    }

    /* Navigation buttons */
    .qr-btn-nav {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.6rem 1.4rem;
        border-radius: 0.75rem;
        font-weight: 600;
        font-size: 0.9rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .qr-btn-prev, .qr-btn-next {
        background: #F2C94C;
        color: #1C1C1C;
    }

    .qr-btn-prev:hover, .qr-btn-next:hover {
        background: #d4a017;
        color: #1C1C1C;
    }

    .qr-btn-prev:disabled, .qr-btn-next:disabled {
        background: #3a3a3a !important;
        color: #666 !important;
        cursor: not-allowed !important;
        opacity: 1 !important;
    }

    .qr-btn-finish {
        background: #ef4444;
        color: #fff;
    }

    .qr-btn-finish:hover {
        background: #dc2626;
        color: #fff;
    }

    .qr-nav-row {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 16px;
        margin-top: 2rem;
        margin-bottom: 2rem;
    }
</style>
@endpush

@section('content')
<div class="qr-wrap py-40">

    {{-- Quiz Header --}}
    <section>
        <h2 class="qr-title">{{ $quiz->title }}</h2>
        <p class="qr-info mt-5">
            @if($quiz->webinar)
                <a href="{{ $quiz->webinar->getUrl() }}" target="_blank">{{ $quiz->webinar->title }}</a>
                <span class="text-secondary mx-1">|</span>
            @endif
            {{ trans('public.by') }}
            @if($quiz->creator)
                <a href="{{ $quiz->creator->getProfileUrl() }}" target="_blank" style="color:#F2C94C; font-weight:600;">{{ $quiz->creator->full_name }}</a>
            @else
                <span style="color:#F2C94C; font-weight:600;">-</span>
            @endif
        </p>
        
    </section>

    {{-- Stats Row --}}
    <div class="row mt-25">
        <div class="col-6 col-md-3 mb-15">
            <div class="qr-stat-card">
                <img src="/assets/default/img/activity/58.svg" width="52" alt="">
                <span class="qr-stat-number">{{ $quiz->pass_mark }}/{{ $questionsSumGrade }}</span>
                <div class="qr-stat-label">{{ trans('public.min') }} {{ trans('quiz.grade') }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-15">
            <div class="qr-stat-card">
                <img src="/assets/default/img/activity/88.svg" width="52" alt="">
                <span class="qr-stat-number">{{ $numberOfAttempt }}/{{ $quiz->attempt }}</span>
                <div class="qr-stat-label">{{ trans('quiz.attempts') }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-15">
            <div class="qr-stat-card">
                <img src="/assets/default/img/activity/45.svg" width="52" alt="">
                <span class="qr-stat-number">{{ $quizResult->user_grade }}/{{ $questionsSumGrade }}</span>
                <div class="qr-stat-label">{{ trans('quiz.your_grade') }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-15">
            <div class="qr-stat-card">
                <img src="/assets/default/img/activity/44.svg" width="52" alt="">
                <span class="qr-stat-number status-{{ $quizResult->status }}">
                    {{ trans('quiz.' . $quizResult->status) }}
                </span>
                <div class="qr-stat-label">{{ trans('public.status') }}</div>
            </div>
        </div>
    </div>

    {{-- Questions --}}
    <section class="mt-30">
        <form action="{{ !empty($newQuizStart) ? '/panel/quizzes/'. $newQuizStart->quiz->id .'/update-result' : '' }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="quiz_result_id" value="{{ !empty($newQuizStart) ? $newQuizStart->id : '' }}">
            <input type="hidden" name="attempt_number" value="{{ $numberOfAttempt }}">
            <input type="hidden" class="js-quiz-question-count" value="{{ $quizQuestions->count() }}">

            @foreach($quizQuestions as $key => $question)
            @php
                $isFirst    = ($key === 0);
                $totalCount = $quizQuestions->count();
            @endphp
            <fieldset class="qr-question-step question-step-{{ $key + 1 }}" style="{{ $isFirst ? 'display:block;' : 'display:none;' }}">
                <div class="qr-question-card">

                    {{-- Header row --}}
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h3 class="qr-question-title">{{ $question->title }}</h3>
                            <p class="qr-question-meta">
                                {{ trans('quiz.question_grade') }}: <strong style="color:#F2C94C;">{{ $question->grade }}</strong>
                                &nbsp;|&nbsp;
                                {{ trans('quiz.your_grade') }}: <strong style="color:#4ade80;">{{ (!empty($userAnswers[$question->id]['grade'])) ? $userAnswers[$question->id]['grade'] : 0 }}</strong>
                            </p>
                        </div>
                        <div class="qr-question-badge">{{ $key + 1 }}/{{ $totalCount }}</div>
                    </div>

                    {{-- Question image/video --}}
                    @if(!empty($question->image) || !empty($question->video))
                    <div class="mt-15 mb-15 text-center">
                        @if(!empty($question->image))
                            <img src="{{ $question->image }}" alt="" style="max-width:200px; max-height:200px; border-radius:0.75rem; object-fit:contain;">
                        @else
                            <video controls style="max-width:100%; max-height:200px; border-radius:0.75rem;">
                                <source src="{{ $question->video }}" type="video/mp4">
                            </video>
                        @endif
                    </div>
                    @endif

                    {{-- Descriptive question --}}
                    @if($question->type === \App\Models\QuizzesQuestion::$descriptive)
                        <div class="mt-20">
                            <p class="qr-input-label">{{ trans('quiz.student_answer') }}</p>
                            <textarea class="form-control qr-textarea" rows="6" disabled>{{ (!empty($userAnswers[$question->id]['answer'])) ? $userAnswers[$question->id]['answer'] : '' }}</textarea>
                        </div>

                        <div class="mt-20">
                            <p class="qr-input-label">{{ trans('quiz.correct_answer') }}</p>
                            <textarea class="form-control qr-textarea" rows="6"
                                @if(empty($newQuizStart) or $newQuizStart->quiz->creator_id != $authUser->id) disabled @endif>{{ $question->correct }}</textarea>
                        </div>

                        @if(!empty($newQuizStart) && $newQuizStart->quiz->creator_id == $authUser->id)
                        <div class="mt-20">
                            <p class="qr-input-label">{{ trans('quiz.grade') }}</p>
                            <input type="text" class="form-control qr-grade-input"
                                   name="question[{{ $question->id }}][grade]"
                                   value="{{ (!empty($userAnswers[$question->id]['grade'])) ? $userAnswers[$question->id]['grade'] : 0 }}">
                        </div>
                        @endif

                    {{-- Multiple choice --}}
                    @else
                        <div class="mt-20">
                            @foreach($question->quizzesQuestionsAnswers as $answer)
                            @php
                                $isCorrect  = (bool) $answer->correct;
                                $isSelected = (!empty($userAnswers[$question->id]) && $userAnswers[$question->id]['answer'] == $answer->id);
                                $itemClass  = '';
                                if ($isCorrect)              $itemClass = 'answer-correct';
                                elseif ($isSelected)         $itemClass = 'student-wrong';
                            @endphp
                            <div class="qr-answer-item {{ $itemClass }}">
                                <input type="radio" id="asw-{{ $answer->id }}"
                                       name="question[{{ $question->id }}][answer]"
                                       value="{{ $answer->id }}" disabled
                                       {{ $isSelected ? 'checked' : '' }}>
                                <label for="asw-{{ $answer->id }}" class="qr-answer-label">
                                    @if($answer->image)
                                        <img src="{{ config('app_url') . $answer->image }}" class="qr-answer-img" alt="">
                                    @else
                                        <span>{{ $answer->title }}</span>
                                    @endif
                                    @if($isCorrect)
                                        <span class="qr-correct-badge">✓ {{ trans('quiz.correct') }}</span>
                                    @endif
                                    @if($isSelected)
                                        <span class="qr-student-badge">{{ trans('quiz.student_answer') }}</span>
                                    @endif
                                </label>
                            </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </fieldset>
            @endforeach

            {{-- Navigation Buttons --}}
            <div class="qr-nav-row">
                <button type="button"
                        class="qr-btn-nav qr-btn-prev previous"
                        disabled>
                    {{ trans('quiz.previous_question') }}
                </button>

                <button type="button"
                        class="qr-btn-nav qr-btn-next next"
                        {{ $quizQuestions->count() <= 1 ? 'disabled' : '' }}>
                    {{ trans('quiz.next_question') }}
                </button>

                @if(!empty($newQuizStart))
                <button type="submit" class="qr-btn-nav qr-btn-finish finish">
                    {{ trans('public.finish') }}
                </button>
                @endif
            </div>

        </form>
    </section>
</div>
@endsection

@push('scripts_bottom')
<script src="/assets/default/js/parts/quiz-start.min.js"></script>
@endpush
