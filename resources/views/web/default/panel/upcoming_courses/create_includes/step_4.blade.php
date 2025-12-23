@push('styles_top')
<style>
    /* ================= KEMETIC THEME: Message to Reviewer ================= */
    .k-message-card {
        background: #151a23;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
        color: #e5e7eb;
        margin-top: 20px;
    }

    .k-message-card .section-title {
        color: #F2C94C;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .k-message-card textarea {
        background: #0e1117;
        border: 1px solid #262c3a;
        border-radius: 10px;
        color: #e5e7eb;
        padding: 12px;
        width: 100%;
        resize: vertical;
    }

    .k-message-card textarea:focus {
        outline: none;
        border-color: #F2C94C;
        box-shadow: 0 0 5px rgba(242, 201, 76, 0.6);
        background: #0e1117;
        color: #e5e7eb;
    }

    .k-message-card .custom-switch .custom-control-label::before {
        background-color: #262c3a;
        border-radius: 50px;
    }

    .k-message-card .custom-switch .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #F2C94C;
        border-color: #F2C94C;
    }

    .k-message-card label.input-label {
        font-weight: 600;
        color: #F2C94C;
    }

    .k-message-card .text-danger {
        color: #ff6b6b !important;
        font-weight: 500;
    }
</style>
@endpush

<section class="k-message-card">
    <h2 class="section-title after-line">{{ trans('public.message_to_reviewer') }}</h2>

    <div class="row">
        <div class="col-12">
            <div class="form-group mt-15">
                <textarea name="message_for_reviewer" rows="10" placeholder="{{ trans('update.enter_your_message') }}">{{ (!empty($upcomingCourse) and $upcomingCourse->message_for_reviewer) ? $upcomingCourse->message_for_reviewer : old('message_for_reviewer') }}</textarea>
            </div>
        </div>
    </div>

    <div class="row mt-10">
        <div class="col-12 col-md-4">
            <div class="form-group mt-10">
                <div class="d-flex align-items-center justify-content-between">
                    <label class="cursor-pointer input-label" for="rulesSwitch">{{ trans('public.agree_rules') }}</label>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="rules" class="custom-control-input" id="rulesSwitch">
                        <label class="custom-control-label" for="rulesSwitch"></label>
                    </div>
                </div>

                @error('rules')
                <div class="text-danger mt-10">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
    </div>
</section>

@push('scripts_bottom')
{{-- Add JS if needed for switch behavior --}}
@endpush
