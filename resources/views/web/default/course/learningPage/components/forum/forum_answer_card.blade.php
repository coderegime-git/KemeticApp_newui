<style>
.course-forum-answer-card {
    background-color: #1A1A1A; /* dark card */
    border: 1px solid rgba(212,175,55,0.2) !important;
    border-radius: 18px !important;
    box-shadow: 0 6px 20px rgba(0,0,0,0.45);
    margin: 15px;
    padding-bottom: 15px;
    transition: transform 0.2s ease;
}

.course-forum-answer-card:hover {
    transform: translateY(-2px);
}

.user-avatar.is-instructor {
    border: 2px solid #F2C94C; /* gold border for instructors */
}

.user-avatar {
    overflow: hidden;
    flex-shrink: 0;
}
.user-avatar img {
    object-fit: cover;
}

.text-gold {
    color: #F2C94C !important;
}

.bg-gray900 {
    background-color: #1C1C1C;
}

.bg-gray800 {
    background-color: #2A2A2A;
}

.btn-transparent {
    background: transparent;
    border: none;
    cursor: pointer;
}

.resolved-answer-badge {
    color: #F2C94C;
}

</style>
@php
    $cardUser = !empty($answer) ? $answer->user : $courseForum->user;
@endphp

<div class="course-forum-answer-card pt-15 {{ (!empty($answer) and $answer->resolved) ? 'resolved' : '' }}">
    <div class="row m-2">
        <div class="col-12 col-md-3 col-lg-2 text-center pt-15 pb-15" style="border-right: 1px solid rgba(212,175,55,0.1);">
            <div class="user-avatar rounded-circle d-inline-block mx-auto {{ (!empty($answer) and $cardUser->isTeacher()) ? 'is-instructor' : '' }} border border-gold" style="width: 72px; height: 72px;">
                <img src="{{ $cardUser->getAvatar(72) }}" class="img-cover rounded-circle w-100 h-100" alt="{{ $cardUser->full_name }}" style="max-width: 100%;">
            </div>
            <h4 class="font-14 text-gold mt-10 font-weight-bold">{{ $cardUser->full_name }}</h4>

            <div class="mt-10">
                <span class="d-inline-block px-10 py-5 rounded-lg border font-12 text-gray" style="line-height:1; background:#0f0f0f; border-color:rgba(212,175,55,0.2) !important;">
                @if($cardUser->isUser())
                    {{ trans('quiz.student') }}
                @elseif($cardUser->isTeacher())
                    {{ trans('public.instructor') }}
                @elseif($cardUser->isOrganization())
                    {{ trans('home.organization') }}
                @elseif($cardUser->isAdmin())
                    {{ trans('panel.staff') }}
                @endif
                </span>
            </div>

            @if(!empty($answer) and $answer->pin)
                <span class="pinned-icon mt-10 mx-auto d-flex align-items-center justify-content-center">
                    <img src="/assets/default/img/learning/un_pin.svg" alt="pin icon">
                </span>
            @endif
        </div>

        <div class="col-12 col-md-9 col-lg-10 pt-15 pb-15">
            <div class="d-flex flex-column justify-content-between h-100">
                <div class="px-10">
                    <p class="font-14 text-white d-block white-space-pre-wrap">{{ !empty($answer) ? $answer->description : $courseForum->description }}</p>

                    @if(empty($answer) and !empty($courseForum->attach))
                        <div class="mt-25 d-inline-block">
                            <a href="{{ $course->getForumPageUrl() }}/{{ $courseForum->id }}/downloadAttach" target="_blank" class="d-flex align-items-center text-gray bg-gray900 border border-gray700 px-10 py-5 rounded-pill">
                                <i data-feather="paperclip" class="text-gold" width="16" height="16"></i>
                                <span class="ml-5 font-12 text-gold">{{ trans('update.attachment') }}</span>
                            </a>
                        </div>
                    @endif
                </div>

                <div class="d-flex align-items-center justify-content-between mt-20 pt-15 px-10" style="border-top: 1px solid rgba(212,175,55,0.1);">
                    <span class="font-12 font-weight-500 text-gray">{{ dateTimeFormat(!empty($answer) ? $answer->created_at : $courseForum->created_at,'j M Y | H:i') }}</span>

                    <div class="d-flex align-items-center">
                        @if(empty($answer) and $user->id == $courseForum->user_id)
                            <button type="button" data-action="{{ $course->getForumPageUrl() }}/{{ $courseForum->id }}/edit" class="js-edit-forum btn-transparent font-12 font-weight-500 text-gold">{{ trans('public.edit') }}</button>
                        @elseif(!empty($answer))
                            @if($course->isOwner($user->id))
                                @if($answer->pin)
                                    <button type="button" data-action="{{ $course->getForumPageUrl() }}/{{ $courseForum->id }}/answers/{{ $answer->id }}/un_pin" class="js-btn-answer-un_pin btn-transparent font-12 font-weight-500 text-warning">{{ trans('update.un_pin') }}</button>
                                @else
                                    <button type="button" data-action="{{ $course->getForumPageUrl() }}/{{ $courseForum->id }}/answers/{{ $answer->id }}/pin" class="js-btn-answer-pin btn-transparent font-12 font-weight-500 text-gold">{{ trans('update.pin') }}</button>
                                @endif
                            @endif

                            @if($course->isOwner($user->id) or $user->id == $courseForum->user_id)
                                @if($answer->resolved)
                                    <button type="button" data-action="{{ $course->getForumPageUrl() }}/{{ $courseForum->id }}/answers/{{ $answer->id }}/mark_as_not_resolved" class="js-btn-answer-mark_as_not_resolved btn-transparent font-12 font-weight-500 text-gray ml-20">{{ trans('update.mark_as_not_resolved') }}</button>
                                @else
                                    <button type="button" data-action="{{ $course->getForumPageUrl() }}/{{ $courseForum->id }}/answers/{{ $answer->id }}/mark_as_resolved" class="js-btn-answer-mark_as_resolved btn-transparent font-12 font-weight-500 text-gold ml-20">{{ trans('update.mark_as_resolved') }}</button>
                                @endif
                            @endif

                            @if($user->id == $answer->user_id)
                                <button type="button" data-action="{{ $course->getForumPageUrl() }}/{{ $courseForum->id }}/answers/{{ $answer->id }}/edit" class="js-edit-forum-answer btn-transparent font-12 font-weight-500 text-gray ml-20">{{ trans('public.edit') }}</button>
                            @endif

                            @if($answer->resolved)
                                <div class="resolved-answer-badge d-flex align-items-center ml-25 text-primary font-12">
                                    <span class="badge-icon d-flex align-items-center justify-content-center">
                                        <i data-feather="check" width="20" height="20"></i>
                                    </span>
                                    {{ trans('update.resolved') }}
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

