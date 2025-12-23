@push('styles_top')
<style>
    /* ================= KEMETIC THEME: Quiz Answer Card ================= */
    .add-answer-card {
        background: #1a1f2b;
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.4);
        color: #e5e7eb;
        position: relative;
    }

    .add-answer-card .input-label {
        font-weight: 600;
        color: #F2C94C;
    }

    .add-answer-card .form-control {
        background: #0e1117;
        border: 1px solid #262c3a;
        border-radius: 10px;
        color: #e5e7eb;
        padding: 10px 12px;
    }

    .add-answer-card .form-control:focus {
        outline: none;
        border-color: #F2C94C;
        box-shadow: 0 0 5px rgba(242,201,76,0.6);
        background: #0e1117;
        color: #e5e7eb;
    }

    .add-answer-card .btn-danger {
        background-color: #ff6b6b;
        border-color: #ff6b6b;
        color: #ffffff;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        top: -10px;
        right: -10px;
        z-index: 10;
    }

    .add-answer-card .btn-danger:hover {
        background-color: #ff4c4c;
        border-color: #ff4c4c;
        color: #ffffff;
    }

    .add-answer-card .input-group-text {
        background-color: #1c2230;
        border-radius: 10px 0 0 10px;
        border: 1px solid #262c3a;
        color: #e5e7eb;
    }

    .add-answer-card .js-switch-parent {
        justify-content: space-between;
    }

    .add-answer-card .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #F2C94C;
        border-color: #F2C94C;
    }

    .add-answer-card .custom-control-label::before {
        border-radius: 20px;
        background-color: #262c3a;
    }

    .add-answer-card .custom-control-label::after {
        background-color: #F2C94C;
    }
</style>
@endpush

<div class="add-answer-card mt-25 {{ (empty($answer) or (!empty($loop) and $loop->iteration == 1)) ? 'main-answer-row' : '' }}">
    <button type="button" class="btn btn-danger answer-remove {{ (!empty($answer) and !empty($loop) and $loop->iteration > 1) ? '' : 'd-none' }}">
        <i data-feather="x" width="20" height="20"></i>
    </button>

    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label class="input-label">{{ trans('quiz.answer_title') }}</label>
                <textarea type="text" name="ajax[answers][{{ !empty($answer) ? $answer->id : 'ans_tmp' }}][title]" class="form-control {{ !empty($answer) ? 'js-ajax-answer-title-'.$answer->id : '' }}" rows="1">{{ !empty($answer) ? $answer->title : '' }}</textarea>
            </div>
        </div>
    </div>

    <div class="row mt-15 align-items-end">
        <div class="col-12 col-md-8">
            <div class="form-group">
                <label class="input-label">{{ trans('quiz.answer_image') }}</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button type="button" class="input-group-text panel-file-manager" data-input="file{{ !empty($answer) ? $answer->id : '_ans_tmp' }}" data-preview="holder">
                            <i data-feather="arrow-up" width="18" height="18"></i>
                        </button>
                    </div>
                    <input id="file{{ !empty($answer) ? $answer->id : '_ans_tmp' }}" type="text" name="ajax[answers][{{ !empty($answer) ? $answer->id : 'ans_tmp' }}][file]" value="{{ !empty($answer) ? $answer->image : '' }}" class="form-control lfm-input"/>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="form-group mt-20 d-flex align-items-center js-switch-parent">
                <label class="js-switch input-label" for="correctAnswerSwitch{{ !empty($answer) ? $answer->id : '' }}">{{ trans('quiz.correct_answer') }}</label>
                <div class="custom-control custom-switch">
                    <input id="correctAnswerSwitch{{ !empty($answer) ? $answer->id : '' }}" type="checkbox" name="ajax[answers][{{ !empty($answer) ? $answer->id : 'ans_tmp' }}][correct]" @if(!empty($answer) and $answer->correct) checked @endif class="custom-control-input js-switch">
                    <label class="custom-control-label js-switch" for="correctAnswerSwitch{{ !empty($answer) ? $answer->id : '' }}"></label>
                </div>
            </div>
        </div>
    </div>
</div>
