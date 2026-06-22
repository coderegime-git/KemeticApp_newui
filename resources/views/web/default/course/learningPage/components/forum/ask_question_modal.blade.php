<style>
    /* KEMETIC APP THEME */
    :root {
        --kemetic-black: #0D0D0D;
        --kemetic-dark: #1A1A1A;
        --kemetic-gold: #D4AF37;
        --kemetic-gold-soft: rgba(212,175,55,0.18);
        --kemetic-text: #EAEAEA;
        --kemetic-gray: #999;
        --kemetic-radius: 18px;
        --kemetic-shadow: 0 10px 40px rgba(0,0,0,0.55);
    }

    /* Kemetic Swal Override */
    .kemetic-swal-popup {
        background: #0f0f0f !important;
        border: 1px solid rgba(242,201,76,0.25) !important;
        border-radius: 18px !important;
    }
    .kemetic-swal-popup .swal2-content {
        color: #e6e6e6 !important;
    }

    /* Modal Container */
    #askNewQuestionModal {
        background: rgba(0,0,0,0.65);
        position: fixed;
        inset: 0;
        z-index: 2000;
        display: none !important; /* Will be shown with JS */
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    /* Modal Body */
    .kemetic-modal {
        text-align: left;
        padding: 10px 20px;
    }

    @keyframes kemeticFadeUp {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    /* Title */
    .kemetic-modal-title {
        color: var(--kemetic-gold);
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 20px;
    }

    /* Labels */
    .kemetic-label {
        color: var(--kemetic-text);
        font-weight: 600;
        margin-bottom: 6px;
    }

    .kemetic-input,
    .kemetic-textarea {
        background: var(--kemetic-black) !important;
        border: 1px solid var(--kemetic-gold-soft) !important;
        color: var(--kemetic-text-light) !important;
        border-radius: 12px !important;
        padding: 10px 12px !important;
        width: 100% !important;
        display: block;
    }
    .kemetic-input:focus,
    .kemetic-textarea:focus {
        border-color: var(--kemetic-gold) !important;
        box-shadow: 0 0 0 3px rgba(212,175,55,0.25) !important;
    }

    /* File Input Group */
    .kemetic-input-group {
        display: flex;
        align-items: center;
        background: var(--kemetic-black);
        border-radius: 12px;
        border: 1px solid var(--kemetic-gold-soft);
    }

    .kemetic-input-group button {
        background: var(--kemetic-gold);
        border-radius: 12px 0 0 12px;
        padding: 6px 12px;
        border: none;
    }

    .kemetic-input-group input {
        background: transparent;
        border: none;
        color: var(--kemetic-text);
    }

    /* Buttons */
    .kemetic-btn {
        background: var(--kemetic-gold);
        color: #000;
        border-radius: 12px;
        padding: 8px 18px;
        font-weight: 700;
        border: none;
    }

    .kemetic-btn-danger {
        background: #cc2b2b;
        color: #fff;
    }
</style>

<div id="askNewQuestionModal" class="d-none">
    <div class="kemetic-modal">

        <h2 class="kemetic-modal-title">{{ trans('update.new_question') }}</h2>

        <form action="{{ $course->getForumPageUrl() }}/store" class="mt-2">

            {{-- Title --}}
            <div class="form-group mb-3">
                <label class="kemetic-label">{{ trans('public.title') }} <span class="text-danger">*</span></label>
                <input type="text" name="title" class="kemetic-input w-100"/>
                <span class="invalid-feedback"></span>
            </div>

            {{-- Description --}}
            <div class="form-group mb-3">
                <label class="kemetic-label">{{ trans('public.description') }} <span class="text-danger">*</span></label>
                <textarea name="description" rows="5" class="kemetic-textarea w-100"></textarea>
                <span class="invalid-feedback"></span>
            </div>

            {{-- Attachment --}}
            <div class="form-group mb-3">
                <label class="kemetic-label">
                    {{ trans('update.attach_a_file') }} ({{ trans('public.optional') }})
                </label>

                <div class="kemetic-input-group">
                    <button type="button"
                        class="panel-file-manager"
                        data-input="questionAttachmentInput_record"
                        data-preview="holder">
                        <i data-feather="upload" width="18" height="18"></i>
                    </button>

                    <input type="text"
                           name="attach"
                           id="questionAttachmentInput_record"
                           placeholder="{{ trans('update.attach_a_file') }}"
                           class="w-100 px-2">
                </div>
            </div>

            {{-- Buttons --}}
            <div class="d-flex justify-content-end mt-4">
                <button type="button" class="js-save-question kemetic-btn" style="margin-right: 10px;">
                    {{ trans('admin/main.post') }}
                </button>

                <button type="button" class="close-swl kemetic-btn-danger ml-2">
                    {{ trans('public.close') }}
                </button>
            </div>

        </form>
    </div>
</div>

