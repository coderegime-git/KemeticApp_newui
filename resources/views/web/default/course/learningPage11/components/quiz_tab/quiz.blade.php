<style>

    .kemetic-quiz-card {
    background-color: #1C1C1C; /* Dark background */
    border: 1px solid #F2C94C33; /* Soft gold border */
    border-radius: 18px;
    padding: 15px;
    margin-bottom: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    cursor: pointer;
}

.kemetic-quiz-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(0,0,0,0.45);
}

.kemetic-quiz-card .quiz-title {
    color: #F2C94C; /* Gold text */
    font-size: 16px;
}

.kemetic-quiz-card .quiz-meta {
    color: #ccc;
}

.kemetic-quiz-card .quiz-status {
    font-weight: 600;
}

</style>
    
@php
    $checkSequenceContent = $item->checkSequenceContent();
    $sequenceContentHasError = (!empty($checkSequenceContent) and (!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error'])));
@endphp

<div class="{{ $sequenceContentHasError ? 'js-sequence-content-error-modal' : 'kemetic-quiz-card tab-item' }} p-10 cursor-pointer {{ $class ?? '' }}"
     data-type="{{ $type }}"
     data-id="{{ $item->id }}"
     data-passed-error="{{ $checkSequenceContent['all_passed_items_error'] ?? '' }}"
     data-access-days-error="{{ $checkSequenceContent['access_after_day_error'] ?? '' }}"
>
    <div class="d-flex align-items-center">
        <span class="chapter-icon bg-gray800 mr-10">
            <i data-feather="award" class="text-gold" width="16" height="16"></i>
        </span>

        <div class="flex-grow-1">
            <span class="quiz-title font-weight-bold d-block">{{ $item->title }}</span>

            <div class="d-flex align-items-center justify-content-between mt-5">
                <span class="quiz-meta font-12 text-gray">
                    @if(!empty($item->time))
                        {{ $item->time .' '. trans('public.min') }}
                    @else
                        {{ trans('update.unlimited_time') }}
                    @endif

                    @if($item->quizQuestions)
                        | {{ ($item->display_limited_questions && !empty($item->display_number_of_questions)) ? $item->display_number_of_questions : $item->quizQuestions->count() }} {{ trans('public.questions') }}
                    @endif
                </span>

                @if(!empty($quiz->result_status))
                    @if($quiz->result_status == 'passed')
                        <span class="quiz-status font-12 text-primary">{{ trans('quiz.passed') }}</span>
                    @elseif($quiz->result_status == 'failed')
                        <span class="quiz-status font-12 text-danger">{{ trans('quiz.failed') }}</span>
                    @elseif($quiz->result_status == 'waiting')
                        <span class="quiz-status font-12 text-warning">{{ trans('quiz.waiting') }}</span>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

