<style>
/* ===== Kemetic Modal Theme ===== */
.k-modal-title{
    font-size:20px;
    font-weight:700;
    color:#F2C94C;
}

.k-card{
    background:#161a22;
    border:1px solid #262c3a;
    border-radius:16px;
}

.k-label{
    color:#9ca3af;
    font-size:13px;
    margin-bottom:6px;
}

.k-input{
    background:#0b0e14;
    border:1px solid #262c3a;
    color:#e5e7eb;
    border-radius:12px;
}

.k-input:focus{
    border-color:#F2C94C;
    box-shadow:none;
}

.k-btn{
    background:linear-gradient(135deg,#F2C94C,#E0B93D);
    color:#000;
    border:none;
    border-radius:12px;
    font-weight:600;
    padding:6px 16px;
}

.k-hint{
    color:#9ca3af;
    font-size:13px;
}
</style>
<div class="d-none" id="joinMeetingLinkModal">

    <h3 class="k-modal-title mb-25">
        {{ trans('panel.join_live_meeting') }}
    </h3>

    <div class="k-card p-20">
        <div class="row">
            {{-- MEETING LINK --}}
            <div class="col-12 col-md-8">
                <div class="form-group">
                    <label class="k-label">{{ trans('panel.url') }}</label>
                    <input type="text" readonly name="link" class="form-control k-input"/>
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            {{-- PASSWORD --}}
            <div class="col-12 col-md-4">
                <div class="form-group">
                    <label class="k-label">
                        {{ trans('auth.password') }}
                        <span class="text-muted font-12">({{ trans('public.optional') }})</span>
                    </label>
                    <input type="text" readonly name="password" class="form-control k-input"/>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>

        <p class="k-hint mt-10">
            {{ trans('panel.add_live_meeting_link_hint') }}
        </p>

        <div class="mt-30 d-flex align-items-center justify-content-end">
            <a href=""
               target="_blank"
               class="js-join-meeting-link btn k-btn">
                {{ trans('footer.join') }}
            </a>

            <button type="button"
                    class="btn btn-outline-danger ml-10 close-swl">
                {{ trans('public.close') }}
            </button>
        </div>
    </div>
</div>

@push('scripts_bottom')
<script src="/assets/default/js/panel/meeting/join_modal.min.js"></script>
@endpush
