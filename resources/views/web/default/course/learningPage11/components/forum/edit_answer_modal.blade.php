<style>
    :root {
    --kemetic-dark: #0D0D0D;
    --kemetic-dark-light: #1A1A1A;
    --kemetic-gold: #D4AF37;
    --kemetic-gold-soft: #E6C766;
    --kemetic-red: #D9534F;

    --kemetic-radius: 18px;
    --kemetic-shadow: 0 8px 24px rgba(0,0,0,0.35);
}

/* Modal Wrapper */
.kemetic-modal {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.75);
    backdrop-filter: blur(6px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 25px;
    z-index: 9999;
}

/* Modal Body */
.kemetic-modal-body {
    width: 100%;
    max-width: 550px;
    background: var(--kemetic-dark-light);
    border: 1px solid var(--kemetic-gold);
    border-radius: var(--kemetic-radius);
    padding: 30px;
    box-shadow: var(--kemetic-shadow);
    animation: fadeIn .28s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
}

.kemetic-title {
    font-size: 22px;
    font-weight: 700;
    color: var(--kemetic-gold);
    margin-bottom: 20px;
}

/* Inputs */
.kemetic-form-group {
    margin-bottom: 20px;
}

.kemetic-label {
    font-size: 14px;
    margin-bottom: 6px;
    color: var(--kemetic-gold-soft);
    display: block;
}

.kemetic-input {
    width: 100%;
    background: var(--kemetic-dark);
    border: 1px solid var(--kemetic-gold-soft);
    padding: 12px 14px;
    border-radius: var(--kemetic-radius);
    color: #fff;
    font-size: 14px;
    resize: vertical;
}

.kemetic-input:focus {
    border-color: var(--kemetic-gold);
    box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.25);
    outline: none;
}

/* Buttons */
.kemetic-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 25px;
}

.kemetic-btn {
    padding: 10px 18px;
    border-radius: var(--kemetic-radius);
    border: none;
    font-size: 14px;
    cursor: pointer;
    transition: 0.25s ease;
}

.kemetic-btn-gold {
    background: var(--kemetic-gold);
    color: #000;
    font-weight: bold;
}

.kemetic-btn-gold:hover {
    background: var(--kemetic-gold-soft);
}

.kemetic-btn-red {
    background: var(--kemetic-red);
    color: #fff;
}

.kemetic-btn-red:hover {
    opacity: 0.8;
}

</style>

<div id="editQuestionAnswerModal" class="kemetic-modal d-none">
    <div class="kemetic-modal-body">

        <h2 class="kemetic-title">{{ trans('update.edit_answer') }}</h2>

        <form action="" class="mt-20">

            <div class="kemetic-form-group">
                <label class="kemetic-label">{{ trans('public.description') }}</label>
                <textarea name="description" rows="5" class="kemetic-input"></textarea>
                <span class="invalid-feedback"></span>
            </div>

            <div class="kemetic-actions">
                <button type="button" class="kemetic-btn kemetic-btn-gold js-save-question-answer">
                    {{ trans('admin/main.post') }}
                </button>

                <button type="button" class="kemetic-btn kemetic-btn-red close-swl ml-2">
                    {{ trans('public.close') }}
                </button>
            </div>

        </form>
    </div>
</div>

