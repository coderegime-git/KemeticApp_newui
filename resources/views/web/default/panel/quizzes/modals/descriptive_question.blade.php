@push('styles_top')
<style>
    .kemetic-modal {
    background: #212020;
    border-radius: 14px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
    overflow: hidden;
}

.kemetic-modal-header {
    padding: 20px 25px;
    border-bottom: 1px solid #eee;
}

.kemetic-modal-body {
    padding: 25px;
}

.kemetic-modal-footer {
    padding: 15px 25px;
    border-top: 1px solid #eee;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

.kemetic-input {
    border-radius: 10px;
    padding: 10px 12px;
}

.kemetic-file-input {
    display: flex;
    gap: 10px;
    align-items: center;
}

</style>
@endpush

<div class="@if(!empty($quiz)) descriptiveQuestionModal{{ $quiz->id }} @endif {{ empty($question_edit) ? 'd-none' : '' }}">
    <div class="kemetic-modal">

        {{-- Header --}}
        <div class="kemetic-modal-header">
            <h2 class="section-title after-line">
                {{ trans('quiz.new_descriptive_question') }}
            </h2>
        </div>

        {{-- Body --}}
        <div class="kemetic-modal-body">
            <form class="quiz-questions-form"
                  data-action="/panel/quizzes-questions/{{ empty($question_edit) ? 'store' : $question_edit->id.'/update' }}">

                @csrf
                <input type="hidden" name="ajax[quiz_id]" value="{{ !empty($quiz) ? $quiz->id : '' }}">
                <input type="hidden" name="ajax[type]" value="{{ \App\Models\QuizzesQuestion::$descriptive }}">

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

                {{-- Question & Grade --}}
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="kemetic-label">{{ trans('quiz.question_title') }}</label>
                            <textarea name="ajax[title]"
                                      class="form-control kemetic-input js-ajax-title"
                                      rows="2"
                                      placeholder="Enter your question here">{{ $question_edit->title ?? '' }}</textarea>
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

                {{-- Correct Answer --}}
                <div class="form-group mt-20">
                    <label class="kemetic-label">{{ trans('quiz.correct_answer') }}</label>
                    <textarea name="ajax[correct]"
                              class="form-control kemetic-input js-ajax-correct"
                              rows="8"
                              placeholder="Enter the correct answer here">{{ $question_edit->correct ?? '' }}</textarea>
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

