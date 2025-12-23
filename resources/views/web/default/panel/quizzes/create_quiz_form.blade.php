<style>
  .kemetic-container { max-width: 1200px; margin: auto; }

.kemetic-card {
    background: linear-gradient(180deg,#0b0b0b,#111);
    border-radius: 18px;
    padding: 30px;
    border: 1px solid rgba(212,175,55,.2);
    box-shadow: 0 25px 60px rgba(0,0,0,.6);
}

.kemetic-section-title {
    color: #d4af37;
    font-weight: 600;
    font-size: 20px;
}

.kemetic-section-title.with-line::after {
    content:'';
    display:block;
    width:50px;
    height:2px;
    background:#d4af37;
    margin-top:8px;
}

.kemetic-form-group { margin-bottom: 18px; }

.kemetic-label {
    font-size: 13px;
    color: #caa84a;
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
}

.kemetic-input,
.kemetic-select {
    width: 100%;
    height: 46px;
    background: #0f0f0f;
    border: 1px solid rgba(212,175,55,.25);
    border-radius: 12px;
    color: #fff;
    padding: 10px 14px;
}

.kemetic-btn-gold {
    background: linear-gradient(135deg,#d4af37,#b8962e);
    color:#000;
    border-radius:14px;
    padding:10px 20px;
    font-weight:600;
}

.kemetic-btn-outline {
    border:1px solid #d4af37;
    color:#d4af37;
    border-radius:14px;
    padding:10px 18px;
    background:transparent;
}

.kemetic-question-card {
    background:#111;
    border:1px solid rgba(212,175,55,.2);
    border-radius:14px;
    padding:16px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:12px;
}
.custom-control {
  position: relative;
  z-index: 1;
  display: block;
  min-height: 1.3rem;
  padding-left: 2rem;
  -webkit-print-color-adjust: exact;
          print-color-adjust: exact;
}

.custom-control-inline {
  display: inline-flex;
  margin-right: 1rem;
}

.custom-control-input {
  position: absolute;
  left: 0;
  z-index: -1;
  width: 1.5rem;
  height: 1.4rem;
  opacity: 0;
}
.custom-control-input:checked ~ .custom-control-label::before {
  color: #ffffff;
  border-color: #f2c94c;
  background-color: #f2c94c;
    box-shadow: 0 0 10px rgba(242, 201, 76, 0.45);
}
.custom-control-input:focus ~ .custom-control-label::before {
  box-shadow: none, 1.5rem;
}
.custom-control-input:focus:not(:checked) ~ .custom-control-label::before {
  border-color: #43d477;
}
.custom-control-input:not(:disabled):active ~ .custom-control-label::before {
  color: #ffffff;
  background-color: #43d477;
  border-color: #43d477;
}
.custom-control-input[disabled] ~ .custom-control-label, .custom-control-input:disabled ~ .custom-control-label {
  color: #6c757d;
}
.custom-control-input[disabled] ~ .custom-control-label::before, .custom-control-input:disabled ~ .custom-control-label::before {
  background-color: #f1f1f1;
}

.custom-control-label {
  position: relative;
  margin-bottom: 0;
  vertical-align: top;
}
.custom-control-label::before {
  position: absolute;
  top: -0.1rem;
  left: -2rem;
  display: block;
  width: 1.5rem;
  height: 1.5rem;
  pointer-events: none;
  content: "";
  background-color: #ffffff;
  border: 2px solid #adb5bd;
  box-shadow: none;
}
.custom-control-label::after {
  position: absolute;
  top: -0.1rem;
  left: -2rem;
  display: block;
  width: 1.5rem;
  height: 1.5rem;
  content: "";
  background: 50%/50% 50% no-repeat;
}

.custom-checkbox .custom-control-label::before {
  border-radius: 0.25rem;
}
.custom-checkbox .custom-control-input:checked ~ .custom-control-label::after {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%23ffffff' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26l2.974 2.99L8 2.193z'/%3e%3c/svg%3e");
}
.custom-checkbox .custom-control-input:indeterminate ~ .custom-control-label::before {
  border-color: #F2C94C;
  background-color: #F2C94C;
}#
.custom-checkbox .custom-control-input:indeterminate ~ .custom-control-label::after {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='4' height='4' viewBox='0 0 4 4'%3e%3cpath stroke='%23ffffff' d='M0 2h4'/%3e%3c/svg%3e");
}
.custom-checkbox .custom-control-input:disabled:checked ~ .custom-control-label::before {
  background-color: #F2C94C;
}
.custom-checkbox .custom-control-input:disabled:indeterminate ~ .custom-control-label::before {
  background-color: #F2C94C;
}

.custom-radio .custom-control-label::before {
  border-radius: 50%;
}
.custom-radio .custom-control-input:checked ~ .custom-control-label::after {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23ffffff'/%3e%3c/svg%3e");
}
.custom-radio .custom-control-input:disabled:checked ~ .custom-control-label::before {
  background-color: rgba(67, 212, 119, 0.5);
}

.custom-switch {
  padding-left: 3.125rem;
}
.custom-switch .custom-control-label::before {
  left: -3.125rem;
  width: 2.625rem;
  pointer-events: all;
  border-radius: 0.75rem;
}
.custom-switch .custom-control-label::after {
  top: calc(-0.1rem + 4px);
  left: calc(-3.125rem + 4px);
  width: calc(1.5rem - 8px);
  height: calc(1.5rem - 8px);
  background-color: #adb5bd;
  border-radius: 0.75rem;
  transition: transform 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
@media (prefers-reduced-motion: reduce) {
  .custom-switch .custom-control-label::after {
    transition: none;
  }
}
.custom-switch .custom-control-input:checked ~ .custom-control-label::after {
  background-color: #ffffff;
  transform: translateX(1.125rem);
}
.custom-switch .custom-control-input:disabled:checked ~ .custom-control-label::before {
  background-color: rgba(67, 212, 119, 0.5);
}
</style>
<div class="kemetic-container">

    <div data-action="{{ !empty($quiz) ? ('/panel/quizzes/'. $quiz->id .'/update') : ('/panel/quizzes/store') }}"
         class="js-content-form quiz-form webinar-form kemetic-card">

        {{-- ================= HEADER ================= --}}
        <section>
            <h2 class="kemetic-section-title with-line">
                {{ !empty($quiz) ? (trans('public.edit').' ('. $quiz->title .')') : trans('quiz.new_quiz') }}
            </h2>

            <div class="row mt-30">
                <div class="col-12 col-lg-4">

                    {{-- Language --}}
                    @if(!empty(getGeneralSettings('content_translate')))
                        <div class="kemetic-form-group">
                            <label class="kemetic-label">{{ trans('auth.language') }}</label>
                            <select name="ajax[{{ !empty($quiz) ? $quiz->id : 'new' }}][locale]"
                                    class="kemetic-select {{ !empty($quiz) ? 'js-webinar-content-locale' : '' }}"
                                    data-webinar-id="{{ !empty($quiz) ? $quiz->webinar_id : '' }}"
                                    data-id="{{ !empty($quiz) ? $quiz->id : '' }}"
                                    data-relation="quizzes"
                                    data-fields="title">
                                @foreach($userLanguages as $lang => $language)
                                    <option value="{{ $lang }}"
                                        {{ (!empty($quiz) && mb_strtolower($quiz->locale) == mb_strtolower($lang)) || $locale == $lang ? 'selected' : '' }}>
                                        {{ $language }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="ajax[new][locale]" value="{{ $defaultLocale }}">
                    @endif

                    {{-- Webinar --}}
                    @if(empty($selectedWebinar))
                        <div class="kemetic-form-group">
                            <label class="kemetic-label">{{ trans('panel.webinar') }}</label>
                            <select name="ajax[{{ !empty($quiz) ? $quiz->id : 'new' }}][webinar_id]"
                                    class="kemetic-select js-ajax-webinar_id">
                                <option disabled selected>{{ trans('panel.choose_webinar') }}</option>
                                @foreach($webinars as $webinar)
                                    <option value="{{ $webinar->id }}"
                                        {{ !empty($quiz) && $quiz->webinar_id == $webinar->id ? 'selected' : '' }}>
                                        {{ $webinar->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="ajax[new][webinar_id]" value="{{ $selectedWebinar->id }}">
                    @endif

                    {{-- Chapter --}}
                    @if(!empty($quiz))
                        <div class="kemetic-form-group">
                            <label class="kemetic-label">{{ trans('public.chapter') }}</label>
                            <select name="ajax[{{ $quiz->id }}][chapter_id]"
                                    class="kemetic-select js-ajax-chapter_id">
                                @foreach($chapters as $ch)
                                    <option value="{{ $ch->id }}"
                                        {{ $quiz->chapter_id == $ch->id ? 'selected' : '' }}>
                                        {{ $ch->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- Title --}}
                    <div class="kemetic-form-group">
                        <label class="kemetic-label">{{ trans('quiz.quiz_title') }}</label>
                        <input type="text"
                               name="ajax[{{ !empty($quiz) ? $quiz->id : 'new' }}][title]"
                               value="{{ $quiz->title ?? old('title') }}"
                               class="kemetic-input js-ajax-title">
                    </div>

                    {{-- Time --}}
                    <div class="kemetic-form-group">
                        <label class="kemetic-label">
                            {{ trans('public.time') }} ({{ trans('public.minutes') }})
                        </label>
                        <input type="number"
                               name="ajax[{{ !empty($quiz) ? $quiz->id : 'new' }}][time]"
                               value="{{ $quiz->time ?? old('time') }}"
                               class="kemetic-input js-ajax-time"
                               min="0"
                               placeholder="{{ trans('forms.empty_means_unlimited') }}">
                    </div>

                    {{-- Attempts --}}
                    <div class="kemetic-form-group">
                        <label class="kemetic-label">{{ trans('quiz.number_of_attemps') }}</label>
                        <input type="number"
                               name="ajax[{{ !empty($quiz) ? $quiz->id : 'new' }}][attempt]"
                               value="{{ $quiz->attempt ?? old('attempt') }}"
                               class="kemetic-input js-ajax-attempt"
                               min="0">
                    </div>

                    {{-- Pass Mark --}}
                    <div class="kemetic-form-group">
                        <label class="kemetic-label">{{ trans('quiz.pass_mark') }}</label>
                        <input type="number"
                               name="ajax[{{ !empty($quiz) ? $quiz->id : 'new' }}][pass_mark]"
                               value="{{ $quiz->pass_mark ?? old('pass_mark') }}"
                               class="kemetic-input js-ajax-pass_mark">
                    </div>

                    {{-- Expiry --}}
                    <div class="kemetic-form-group">
                        <label class="kemetic-label">{{ trans('update.expiry_days') }}</label>
                        <input type="number"
                               name="ajax[{{ !empty($quiz) ? $quiz->id : 'new' }}][expiry_days]"
                               value="{{ $quiz->expiry_days ?? old('expiry_days') }}"
                               class="kemetic-input js-ajax-expiry_days">
                        <small class="kemetic-hint">{{ trans('update.quiz_expiry_days_hint') }}</small>
                    </div>

                    <div class="form-group mt-20 d-flex align-items-center justify-content-between">
                        <label class="cursor-pointer input-label" for="displayQuestionsRandomlySwitch{{ !empty($quiz) ? $quiz->id : 'record' }}">{{ trans('update.display_questions_randomly') }}</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="ajax[{{ !empty($quiz) ? $quiz->id : 'new' }}][display_questions_randomly]" class="js-ajax-display_questions_randomly custom-control-input" id="displayQuestionsRandomlySwitch{{ !empty($quiz) ? $quiz->id : 'record' }}" {{ (!empty($quiz) && $quiz->display_questions_randomly) ? 'checked' : ''}}>
                            <label class="custom-control-label" for="displayQuestionsRandomlySwitch{{ !empty($quiz) ? $quiz->id : 'record' }}"></label>
                        </div>
                    </div>

                    <div class="form-group mt-20 d-flex align-items-center justify-content-between">
                        <label class="cursor-pointer input-label" for="certificateSwitch{{ !empty($quiz) ? $quiz->id : 'record' }}">{{ trans('quiz.certificate_included') }}</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="ajax[{{ !empty($quiz) ? $quiz->id : 'new' }}][certificate]" class="js-ajax-certificate custom-control-input" id="certificateSwitch{{ !empty($quiz) ? $quiz->id : 'record' }}" {{ (!empty($quiz) && $quiz->certificate) ? 'checked' : ''}}>
                            <label class="custom-control-label" for="certificateSwitch{{ !empty($quiz) ? $quiz->id : 'record' }}"></label>
                        </div>
                    </div>

                    <div class="form-group mt-20 d-flex align-items-center justify-content-between">
                        <label class="cursor-pointer input-label" for="statusSwitch{{ !empty($quiz) ? $quiz->id : 'record' }}">{{ trans('quiz.active_quiz') }}</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="ajax[{{ !empty($quiz) ? $quiz->id : 'new' }}][status]" class="js-ajax-status custom-control-input" id="statusSwitch{{ !empty($quiz) ? $quiz->id : 'record' }}" {{ (!empty($quiz) && $quiz->status == 'active') ? 'checked' : ''}}>
                            <label class="custom-control-label" for="statusSwitch{{ !empty($quiz) ? $quiz->id : 'record' }}"></label>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- ================= QUESTIONS ================= --}}
        @if(!empty($quiz))
            <section class="mt-50">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="kemetic-section-title with-line">
                        {{ trans('public.questions') }}
                    </h2>

                    <div class="d-flex gap-10">
                        <button type="button" id="add_multiple_question"
                                data-quiz-id="{{ $quiz->id }}" style="margin-right:10px;"
                                class="kemetic-btn-outline">
                            {{ trans('quiz.add_multiple_choice') }}
                        </button>

                        <button type="button" id="add_descriptive_question"
                                data-quiz-id="{{ $quiz->id }}"
                                class="kemetic-btn-gold">
                            {{ trans('quiz.add_descriptive') }}
                        </button>
                    </div>
                </div>

                <ul class="kemetic-question-list mt-30 draggable-questions-lists-{{ $quiz->id }}">
                    @foreach($quizQuestions as $question)
                        <li class="kemetic-question-card" data-id="{{ $question->id }}">
                            <div>
                                <h4>{{ $question->title }}</h4>
                                <span class="kemetic-muted">
                                    {{ trans('quiz.grade') }}: {{ $question->grade }}
                                </span>
                            </div>

                            <div class="d-flex gap-10">
                                <i data-feather="move"></i>
                                <a href="/panel/quizzes-questions/{{ $question->id }}/delete"
                                   class="kemetic-text-danger">
                                    {{ trans('public.delete') }}
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        {{-- ================= FOOTER ================= --}}
        <div class="d-flex justify-content-end mt-40 gap-10">
            <button type="button" class="js-submit-quiz-form kemetic-btn-gold">
                {{ !empty($quiz) ? trans('public.save_change') : trans('public.create') }}
            </button>

            @if(empty($quiz) && !empty($inWebinarPage))
                <button type="button" class="kemetic-btn-outline cancel-accordion">
                    {{ trans('public.close') }}
                </button>
            @endif
        </div>

    </div>
</div>


    <!-- Modal -->
@if(!empty($quiz))
    @include(getTemplate() .'.panel.quizzes.modals.multiple_question',['quiz' => $quiz])
    @include(getTemplate() .'.panel.quizzes.modals.descriptive_question',['quiz' => $quiz])
@endif
