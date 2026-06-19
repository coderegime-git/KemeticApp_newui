<style>
    /* =====================================================
   KEMETIC MODAL – JOIN WEBINAR
===================================================== */

.kemetic-modal {
    background: linear-gradient(180deg, #141414, #0c0c0c);
    border-radius: 18px;
    border: 1px solid rgba(242,201,76,.25);
    box-shadow: 0 30px 80px rgba(0,0,0,.8);
    color: #eaeaea;
}

/* HEADER */
.kemetic-modal-header {
    padding: 20px 25px;
    border-bottom: 1px solid rgba(242,201,76,.2);
}

.kemetic-title {
    color: #F2C94C;
    font-weight: 800;
    font-size: 20px;
}

/* BODY */
.kemetic-modal-body {
    padding: 25px;
}

/* SUBTITLE */
.kemetic-subtitle {
    margin: 25px 0 15px;
    color: #F2C94C;
    font-size: 16px;
    font-weight: 700;
    border-left: 3px solid #F2C94C;
    padding-left: 10px;
}

/* FORM */
.kemetic-form label {
    color: #cfcfcf;
    font-weight: 500;
    margin-bottom: 6px;
}

.kemetic-input,
.kemetic-textarea {
    background: #101010;
    border: 1px solid rgba(255,255,255,.08);
    color: #fff;
    border-radius: 10px;
}

.kemetic-input:focus,
.kemetic-textarea:focus {
    border-color: #F2C94C;
    box-shadow: 0 0 0 2px rgba(242,201,76,.15);
}

/* INPUT GROUP */
.kemetic-input-group .input-group-text {
    background: #1a1a1a;
    border: 1px solid rgba(255,255,255,.08);
    color: #F2C94C;
}

/* COPY BUTTON */
.kemetic-copy {
    cursor: pointer;
    transition: .2s;
}

.kemetic-copy:hover {
    background: rgba(242,201,76,.15);
}

/* FOOTER */
.kemetic-modal-footer {
    padding: 20px 25px;
    border-top: 1px solid rgba(242,201,76,.2);
    display: flex;
    justify-content: flex-end;
}

/* BUTTONS */
.kemetic-btn-primary {
    background: linear-gradient(135deg, #F2C94C, #E5A100);
    color: #000;
    font-weight: 700;
    border-radius: 30px;
    padding: 8px 22px;
}

.kemetic-btn-primary:hover {
    background: linear-gradient(135deg, #E5A100, #F2C94C);
}

.kemetic-btn-danger {
    background: transparent;
    border: 1px solid rgba(255,80,80,.6);
    color: #ff6b6b;
    border-radius: 30px;
    padding: 8px 18px;
}

</style>
<div class="d-none kemetic-modal" id="joinWebinarModal">

    {{-- HEADER --}}
    <div class="kemetic-modal-header">
        <h3 class="kemetic-title">
            {{ trans('webinars.next_session_info') }}
        </h3>
    </div>

    {{-- SESSION INFO --}}
    <div class="kemetic-modal-body">

        <div class="row">
            <div class="col-12 col-md-7">
                <div class="form-group kemetic-form">
                    <label>{{ trans('webinars.session_title') }}</label>
                    <input type="text" readonly
                           class="js-join-session-title form-control kemetic-input"/>
                </div>
            </div>

            <div class="col-12 col-md-5">
                <div class="form-group kemetic-form">
                    <label>{{ trans('public.date') }}</label>
                    <div class="input-group kemetic-input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i data-feather="calendar"></i>
                            </span>
                        </div>
                        <input type="text" readonly
                               class="js-join-session-date form-control kemetic-input"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group kemetic-form">
            <label>{{ trans('public.description') }}</label>
            <textarea readonly rows="5"
                      class="js-join-session-description form-control kemetic-textarea"></textarea>
        </div>

        {{-- JOIN INFO --}}
        <h4 class="kemetic-subtitle">
            {{ trans('webinars.join_information') }}
        </h4>

        <div class="form-group kemetic-form js-join-session-link-row">
            <label>{{ trans('public.link') }}</label>
            <div class="input-group kemetic-input-group">
                <div class="input-group-prepend">
                    <button type="button"
                            class="input-group-text kemetic-copy js-copy"
                            data-input="link"
                            data-toggle="tooltip"
                            title="{{ trans('public.copy') }}"
                            data-copy-text="{{ trans('public.copy') }}"
                            data-done-text="{{ trans('public.done') }}">
                        <i data-feather="copy"></i>
                    </button>
                </div>
                <input type="text" readonly
                       name="link"
                       class="js-join-session-link form-control kemetic-input"/>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group kemetic-form">
                    <label>{{ trans('webinars.system') }}</label>
                    <input type="text" readonly
                           class="js-join-session-session_api form-control kemetic-input"/>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="form-group kemetic-form">
                    <label>{{ trans('auth.password') }}</label>
                    <input type="text" readonly
                           class="js-join-session-api_secret form-control kemetic-input"/>
                </div>
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="kemetic-modal-footer">
        <a href="" target="_blank"
           class="js-join-session-link-action btn kemetic-btn-primary">
            {{ trans('footer.join') }}
        </a>

        <button type="button"
                class="btn kemetic-btn-danger close-swl ml-10">
            {{ trans('public.close') }}
        </button>
    </div>
</div>
