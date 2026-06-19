<style>
    .kemetic-quiz-card {
    background: var(--kemetic-dark-light);
    border: 1px solid var(--kemetic-gold-soft);
    border-radius: var(--kemetic-radius);
    padding: 15px;
    box-shadow: var(--kemetic-shadow);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    cursor: pointer;
}

.kemetic-quiz-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(0,0,0,0.45);
}

.kemetic-quiz-card .quiz-title {
    font-weight: 600;
    font-size: 16px;
    color: var(--kemetic-gold);
}

.kemetic-quiz-card .quiz-meta {
    font-size: 12px;
    color: #ccc;
    margin-top: 5px;
}

.kemetic-quiz-card .quiz-status {
    font-size: 13px;
    font-weight: 500;
    color: var(--kemetic-gold-soft);
    margin-top: 8px;
}

    </style>
<div class="content-tab p-15 pb-50">

    @if(!empty($course->quizzes) and $course->quizzes->count())
        @foreach($course->quizzes as $quiz)
            @include('web.default.course.learningPage.components.quiz_tab.quiz', [
                'item' => $quiz, 
                'type' => 'quiz',
                'class' => 'kemetic-quiz-card px-10 mb-15'
            ])
        @endforeach

    @else
        <div class="learning-page-forum-empty d-flex align-items-center justify-content-center flex-column">
            <div class="learning-page-forum-empty-icon d-flex align-items-center justify-content-center">
                <img src="/assets/default/img/learning/quiz-empty.svg" class="img-fluid" alt="no quizzes">
            </div>

            <div class="d-flex align-items-center flex-column mt-10 text-center">
                <h3 class="font-20 font-weight-bold text-dark-blue text-center">{{ trans('update.learning_page_empty_quiz_title') }}</h3>
                <p class="font-14 font-weight-500 text-gray mt-5 text-center">{{ trans('update.learning_page_empty_quiz_hint') }}</p>
            </div>
        </div>
    @endif
</div>
