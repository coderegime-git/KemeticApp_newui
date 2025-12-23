<style>
    /* =========================================================
   KEMETIC MODAL – NEXT SESSION FORM
   Black • Gold • Premium
========================================================= */

/* ROOT COLORS */
:root {
    --k-bg: #0f0f0f;
    --k-card: #161616;
    --k-border: rgba(242, 201, 76, 0.28);
    --k-gold: #F2C94C;
    --k-gold-soft: rgba(242, 201, 76, 0.18);
    --k-text: #e6e6e6;
    --k-muted: #9a9a9a;
    --k-radius: 16px;
}

/* MODAL WRAPPER */
#webinarNextSessionModal {
    background: var(--k-bg);
    border-radius: var(--k-radius);
    padding: 28px;
    border: 1px solid var(--k-border);
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.85);
    max-width: 900px;
    margin: auto;
}

/* SECTION TITLES */
#webinarNextSessionModal .section-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--k-gold);
    margin-bottom: 20px;
    letter-spacing: 0.4px;
    position: relative;
}

#webinarNextSessionModal .section-title:after {
    content: "";
    display: block;
    height: 1px;
    width: 60px;
    margin-top: 6px;
    background: linear-gradient(to right, var(--k-gold), transparent);
}

/* FORM GROUP */
#webinarNextSessionModal .form-group {
    margin-bottom: 20px;
}

/* LABEL */
#webinarNextSessionModal .input-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--k-gold);
    margin-bottom: 6px;
}

/* INPUTS */
#webinarNextSessionModal .form-control {
    background: #101010;
    border: 1px solid #2a2a2a;
    border-radius: 12px;
    color: var(--k-text);
    padding: 12px 14px;
    font-size: 14px;
    transition: all 0.25s ease;
}

#webinarNextSessionModal .form-control::placeholder {
    color: var(--k-muted);
}

#webinarNextSessionModal .form-control:focus {
    border-color: var(--k-gold);
    box-shadow: 0 0 0 2px var(--k-gold-soft);
    background: #141414;
    outline: none;
}

/* TEXTAREA */
#webinarNextSessionModal textarea.form-control {
    resize: none;
}

/* INPUT GROUP */
#webinarNextSessionModal .input-group-text {
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    border-radius: 12px 0 0 12px;
    color: var(--k-gold);
}

#webinarNextSessionModal .input-group .form-control {
    border-left: none;
}

/* COPY BUTTON */
#webinarNextSessionModal .js-copy {
    cursor: pointer;
    transition: 0.25s ease;
}

#webinarNextSessionModal .js-copy:hover {
    background: var(--k-gold);
    color: #000;
}

/* INVALID FEEDBACK */
#webinarNextSessionModal .invalid-feedback {
    color: #ff6b6b;
    font-size: 12px;
}

/* BUTTONS */
#webinarNextSessionModal .btn {
    border-radius: 12px;
    padding: 10px 22px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.25s ease;
}

/* SAVE BUTTON */
#webinarNextSessionModal .btn-primary {
    background: linear-gradient(135deg, #F2C94C, #E5A100);
    border: none;
    color: #000;
}

#webinarNextSessionModal .btn-primary:hover {
    box-shadow: 0 10px 25px rgba(242, 201, 76, 0.4);
    transform: translateY(-2px);
}

/* CLOSE BUTTON */
#webinarNextSessionModal .btn-danger {
    background: transparent;
    border: 1px solid #444;
    color: #ccc;
}

#webinarNextSessionModal .btn-danger:hover {
    background: #1f1f1f;
    color: #fff;
}

/* ICONS */
#webinarNextSessionModal svg {
    stroke: var(--k-gold);
}

/* RESPONSIVE */
@media (max-width: 768px) {
    #webinarNextSessionModal {
        padding: 20px;
    }

    #webinarNextSessionModal .section-title {
        font-size: 15px;
    }
}

</style>
<div class="d-none" id="webinarNextSessionModal">
    <form action="/panel/sessions/store" method="post">
        {{ csrf_field() }}

        <input type="hidden" name="ajax[new][webinar_id]">
        <input type="hidden" name="ajax[new][chapter_id]">
        <input type="hidden" name="ajax[new][locale]">
        <input type="hidden" name="ajax[new][status]" value="on">
        <input type="hidden" name="ajax[new][agora_chat]">
        <input type="hidden" name="ajax[new][agora_rec]">

        <h3 class="section-title after-line font-16 text-dark-blue mb-25">{{ trans('webinars.next_session_info') }}</h3>

        <div class="mt-25">

            <div class="row">
                <div class="col-12 col-md-7">
                    @if(!empty(getGeneralSettings('content_translate')))
                        <div class="form-group">
                            <label class="input-label">{{ trans('auth.language') }}</label>
                            <select name="ajax[new][locale]"
                                    class="form-control"
                                    data-bundle-id=""
                                    data-id=""
                                    data-relation=""
                                    data-fields=""
                            >
                                @foreach(getUserLanguagesLists() as $lang => $language)
                                    <option value="{{ $lang }}" {{ app()->getLocale() == $lang ? 'selected' : '' }}>{{ $language }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="ajax[new][locale]" value="{{ mb_strtolower(getDefaultLocale()) }}">
                    @endif
                </div>
                <div class="col-12 col-md-5">
                    <div class="form-group">
                        <label class="input-label">{{ trans('public.chapter') }}</label>

                        <select name="ajax[new][chapter_id]" class="js-ajax-chapter_id form-control">

                        </select>

                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-7">
                    <div class="form-group">
                        <label class="input-label">{{ trans('webinars.session_title') }}</label>
                        <input type="text" name="ajax[new][title]" class="js-ajax-title form-control" value=""/>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="col-12 col-md-5">
                    <div class="form-group">
                        <label class="input-label">{{ trans('public.date') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i data-feather="calendar" width="18" height="18" class="text-white"></i>
                            </span>
                            </div>
                            <input type="text" name="ajax[new][date]" value="" class="js-ajax-date form-control datetimepicker"/>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label class="input-label">{{ trans('public.description') }}</label>
                        <textarea name="ajax[new][description]" class="js-ajax-description form-control" rows="5"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="section-title after-line font-16 text-dark-blue mb-25">{{ trans('webinars.join_information') }}</h3>

        <div class="row">
            <div class="col-6 js-local-link">
                <div class="form-group">
                    <label class="input-label">{{ trans('public.link') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="button" class="input-group-text js-copy" data-input="ajax[new][link]" data-toggle="tooltip" data-placement="top" title="{{ trans('public.copy') }}" data-copy-text="{{ trans('public.copy') }}" data-done-text="{{ trans('public.copied') }}">
                                <i data-feather="copy" width="18" height="18" class="text-white"></i>
                            </button>
                        </div>
                        <input type="text" name="ajax[new][link]" value="" class="js-ajax-link form-control"/>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label class="input-label">{{ trans('public.duration') }}</label>
                    <input type="text" name="ajax[new][duration]" value="" class="js-ajax-duration form-control"/>
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label class="input-label">{{ trans('webinars.system') }}</label>

                    <select name="ajax[new][session_api]" class="js-ajax-session_api form-control">
                        @foreach(getFeaturesSettings("available_session_apis") as $sessionApi)
                            <option value="{{ $sessionApi }}">{{ trans('update.session_api_'.$sessionApi) }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="col-12 col-md-6 js-api-secret">
                <div class="form-group">
                    <label class="input-label">{{ trans('auth.password') }}</label>
                    <input type="text" name="ajax[new][api_secret]" class="js-ajax-api_secret form-control" value=""/>
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="col-12 col-md-6 js-moderator-secret d-none">
                <div class="form-group">
                    <label class="input-label">{{ trans('public.moderator_password') }}</label>
                    <input type="text" name="ajax[new][moderator_secret]" class="js-ajax-moderator_secret form-control" value=""/>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>

        <div class="mt-30 d-flex align-items-center justify-content-end">
            <button type="button" class="js-save-next-session btn btn-sm btn-primary">{{ trans('public.save') }}</button>
            <button type="button" class="btn btn-sm btn-danger ml-10 close-swl">{{ trans('public.close') }}</button>
        </div>
    </form>
</div>
