<style>

/* Backgrounds */
.bg-gray800 { background-color: #1C1C1C !important; }
.bg-gray700 { background-color: #2A2A2A !important; }

/* Text colors */
.text-gold { color: #F2C94C !important; }
.text-gray { color: #B0B0B0 !important; }

/* Typography */
.font-12 { font-size: 12px; }
.font-14 { font-size: 14px; }
.font-16 { font-size: 16px; }
.font-weight-bold { font-weight: 700; }
.font-weight-500 { font-weight: 500; }

/* Cards and sections */
.rounded-lg { border-radius: 18px !important; }
.shadow-sm { box-shadow: 0 4px 12px rgba(0,0,0,0.15); }

/* Loading */
.learning-content-loading img { width: 60px; height: 60px; }
.learning-content-loading p { color: #F2C94C; text-align: center; }

/* Buttons & interactive */
.btn-gold { background-color: #F2C94C; color: #222; border: none; }
.btn-gold:hover { background-color: #d4b943; color: #1C1C1C; }

/* Override included components to match theme */
.course-forum-answer-card,
.assignment-page,
.noticeboard-card {
    background-color: #2A2A2A;
    border-color: #444;
    color: #F2C94C;
}
.course-forum-answer-card p,
.assignment-page p,
.noticeboard-card p {
    color: #B0B0B0;
}

</style>
@php
    $showLoading = true;

    if(
        (!empty($noticeboards) and $noticeboards) or
        !empty($assignment) or
        (!empty($isForumPage) and $isForumPage) or
        (!empty($isForumAnswersPage) and $isForumAnswersPage)
    ) {
        $showLoading = false;
    }
@endphp

<div class="learning-content mb-5" id="learningPageContent">

    @if(!empty($isForumAnswersPage) and $isForumAnswersPage)
        <div class="bg-gray800 p-15 rounded-lg shadow-sm">
            @include('web.default.course.learningPage.components.forum.forum_answers')
        </div>
    @elseif(!empty($isForumPage) and $isForumPage)
        <div class="bg-gray800 p-15 rounded-lg shadow-sm">
            @include('web.default.course.learningPage.components.forum.forum')
        </div>
    @elseif(!empty($noticeboards) and $noticeboards)
        <div class="bg-gray800 p-15 rounded-lg shadow-sm">
            @include('web.default.course.learningPage.components.noticeboards')
        </div>
    @elseif(!empty($assignment))
        <div class="bg-gray800 p-15 rounded-lg shadow-sm">
            @include('web.default.course.learningPage.components.assignment')
        </div>
    @endif

    <div class="learning-content-loading align-items-center justify-content-center flex-column w-100 h-100 {{ $showLoading ? 'd-flex' : 'd-none' }}">
        <img src="/assets/default/img/loading.gif" alt="loading" class="mb-3">
        <p class="text-gold font-14">{{ trans('update.please_wait_for_the_content_to_load') }}</p>
    </div>
</div>

