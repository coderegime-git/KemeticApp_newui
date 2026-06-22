<div id="editQuestionAnswerModal" class="d-none">
    <div class="kemetic-modal">

        <h2 class="kemetic-modal-title">{{ trans('update.edit_answer') }}</h2>

        <form action="" class="mt-2">

            <div class="form-group mb-3">
                <label class="kemetic-label">{{ trans('public.description') }} *</label>
                <textarea name="description" rows="5" class="kemetic-textarea w-100"></textarea>
                <span class="invalid-feedback"></span>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="button" class="js-save-question-answer kemetic-btn" style="margin-right: 10px;">
                    {{ trans('admin/main.post') }}
                </button>

                <button type="button" class="close-swl kemetic-btn-danger ml-2">
                    {{ trans('public.close') }}
                </button>
            </div>

        </form>
    </div>
</div>

