<style>
/* ===== Kemetic Modal Design ===== */

.k-modal-title{
    font-size:20px;
    font-weight:700;
    color:#F2C94C;
}

.k-card{
    background:#161a22;
    border:1px solid #262c3a;
    border-radius:18px;
}

.k-sub-title{
    color:#e5e7eb;
    font-weight:600;
}

.k-text{
    color:#9ca3af;
    font-size:14px;
}

.text-gold{
    color:#F2C94C;
    font-weight:600;
}

.k-btn{
    background:linear-gradient(135deg,#F2C94C,#E0B93D);
    color:#000;
    border:none;
    border-radius:12px;
    font-weight:600;
    padding:6px 18px;
}

.k-btn:hover{
    opacity:.95;
}
</style>
<div class="d-none" id="meetingCreateSessionModal">

    <h3 class="k-modal-title mb-25">
        {{ trans('update.create_a_live_session') }}
    </h3>

    <div class="k-card p-25 text-center">

        <img src="/assets/default/img/meeting/live_session.svg"
             alt="Live Session"
             class="mb-15"
             width="140"
             height="140">

        {{-- CREATE SESSION CONTENT --}}
        <h4 class="js-for-create-session-text d-none k-sub-title mt-10">
            {{ trans('update.new_in-app_call_session') }}
        </h4>

        <p class="js-for-create-session-text d-none k-text mt-5">
            {{ trans('update.are_you_sure_to_create_an_in-app_live_session_for_this_meeting') }}
        </p>

        <p class="js-for-create-session-text d-none k-text mt-5">
            {{ trans('update.your_meeting_date_is') }}
            <span class="js-meeting-date text-gold"></span>
        </p>

        {{-- JOIN SESSION CONTENT --}}
        <h4 class="js-for-join-session-text d-none k-sub-title mt-10">
            {{ trans('update.join_the_live_session_now') }}
        </h4>

        <p class="js-for-join-session-text d-none k-text mt-5">
            {{ trans('update.live_session_created_successfully_and_you_can_join_it_right_now') }}
        </p>

    </div>

    <div class="mt-30 d-flex align-items-center justify-content-end">
        <button type="button"
                data-item-id=""
                class="js-create-meeting-session btn k-btn">
            {{ trans('public.create') }}
        </button>

        <a href=""
           target="_blank"
           class="js-join-to-session d-none btn k-btn ml-10">
            {{ trans('footer.join') }}
        </a>

        <button type="button"
                class="btn btn-outline-danger ml-10 close-swl">
            {{ trans('public.close') }}
        </button>
    </div>
</div>
