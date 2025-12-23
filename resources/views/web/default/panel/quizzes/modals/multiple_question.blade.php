@push('styles_top')
<style>
    .kemetic-modal {
    background: #212020;
    border-radius: 14px;
    box-shadow: 0 15px 40px rgba(0,0,0,.15);
    padding:10px;
}

.kemetic-modal-header,
.kemetic-modal-footer {
    padding: 20px 25px;
    border-bottom: 1px solid #eee;
}

.kemetic-modal-footer {
    border-top: 1px solid #eee;
    border-bottom: none;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.kemetic-input {
    border-radius: 10px;
    padding: 10px 12px;
}

.kemetic-file-input {
    display: flex;
    gap: 10px;
}

</style>
@endpush

<div class="@if(!empty($quiz)) multipleQuestionModal{{ $quiz->id }} @endif {{ empty($question_edit) ? 'd-none' : '' }}">
    <div class="kemetic-modal">

        {{-- Header --}}
        <div class="kemetic-modal-header">
            <h2 class="section-title after-line">
                {{ trans('quiz.multiple_choice_question') }}
            </h2>
        </div>

        {{-- Body --}}
        <div class="kemetic-modal-body">
            <form class="quiz-questions-form"
                  data-action="/panel/quizzes-questions/{{ empty($question_edit) ? 'store' : $question_edit->id.'/update' }}">

                <input type="hidden" name="ajax[quiz_id]" value="{{ !empty($quiz) ? $quiz->id : '' }}">
                <input type="hidden" name="ajax[type]" value="{{ \App\Models\QuizzesQuestion::$multiple }}">

                {{-- Language --}}
                @if(!empty(getGeneralSettings('content_translate')))
                    <div class="form-group">
                        <label class="kemetic-label">{{ trans('auth.language') }}</label>
                        <select name="ajax[locale]"
                                class="form-control kemetic-select {{ !empty($question_edit) ? 'js-quiz-question-locale' : '' }}"
                                data-id="{{ !empty($question_edit) ? $question_edit->id : '' }}">
                            @foreach($userLanguages as $lang => $language)
                                <option value="{{ $lang }}"
                                    {{ (!empty($question_edit) && $question_edit->locale == $lang) ? 'selected' : '' }}>
                                    {{ $language }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <input type="hidden" name="ajax[locale]" value="{{ $defaultLocale }}">
                @endif

                {{-- Question --}}
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="kemetic-label">{{ trans('quiz.question_title') }}</label>
                            <textarea name="ajax[title]"
                                      class="form-control kemetic-input js-ajax-title"
                                      rows="2"
                                      placeholder="Enter your question">{{ $question_edit->title ?? '' }}</textarea>
                            <span class="invalid-feedback"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="kemetic-label">{{ trans('quiz.grade') }}</label>
                            <input type="number"
                                   name="ajax[grade]"
                                   class="form-control kemetic-input js-ajax-grade"
                                   value="{{ $question_edit->grade ?? '' }}">
                            <span class="invalid-feedback"></span>
                        </div>
                    </div>
                </div>

                {{-- Media --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="kemetic-label">{{ trans('public.image') }} (Optional)</label>
                            <div class="kemetic-file-input">
                                <button type="button"
                                        class="kemetic-btn-outline panel-file-manager"
                                        data-input="questionImageInput_{{ $question_edit->id ?? 'record' }}">
                                    <i data-feather="upload" width="16"></i>
                                </button>
                                <input type="text"
                                       id="questionImageInput_{{ $question_edit->id ?? 'record' }}"
                                       name="ajax[image]"
                                       value="{{ $question_edit->image ?? '' }}"
                                       class="form-control kemetic-input js-ajax-image">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="kemetic-label">{{ trans('update.video') }} (Optional)</label>
                            <div class="kemetic-file-input">
                                <button type="button"
                                        class="kemetic-btn-outline panel-file-manager"
                                        data-input="questionVideoInput_{{ $question_edit->id ?? 'record' }}">
                                    <i data-feather="upload" width="16"></i>
                                </button>
                                <input type="text"
                                       id="questionVideoInput_{{ $question_edit->id ?? 'record' }}"
                                       name="ajax[video]"
                                       value="{{ $question_edit->video ?? '' }}"
                                       class="form-control kemetic-input js-ajax-video">
                            </div>
                        </div>
                    </div>
                </div>

                <p class="font-12 text-gray mt-10">
                    {{ trans('update.quiz_question_image_validation_by_video') }}
                </p>

                {{-- Answers --}}
                <div class="mt-30">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="section-title after-line">{{ trans('public.answers') }}</h3>
                        <button type="button" class="kemetic-btn-outline add-answer-btn">
                            <i data-feather="plus" width="16"></i> {{ trans('quiz.add_an_answer') }}
                        </button>
                    </div>

                    <input type="hidden" name="ajax[current_answer]" class="js-ajax-current_answer">
                    <span class="invalid-feedback"></span>

                    <div class="add-answer-container mt-20">
                        @if (!empty($question_edit->quizzesQuestionsAnswers))
                            @foreach ($question_edit->quizzesQuestionsAnswers as $answer)
                                @include(getTemplate().'.panel.quizzes.modals.multiple_answer_form',['answer'=>$answer])
                            @endforeach
                        @else
                            @include(getTemplate().'.panel.quizzes.modals.multiple_answer_form')
                        @endif
                    </div>
                </div>

                {{-- Footer --}}
                <div class="kemetic-modal-footer">
                    <button type="button" class="kemetic-btn-gold save-question">
                        <i data-feather="save" width="16"></i>
                        <span class="ml-5">{{ trans('public.save') }}</span>
                    </button>

                    <button type="button" class="kemetic-btn-outline close-swl">
                        {{ trans('public.close') }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

