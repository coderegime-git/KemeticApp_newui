<style>
    .bg-dark-blue {
    background-color: #1C1C1C;
}

.text-gold {
    color: #F2C94C;
}

.bg-gray900 {
    background-color: #222;
}

.bg-gray800 {
    background-color: #2A2A2A;
}

.btn-gold {
    background-color: #F2C94C;
    color: #1C1C1C;
    border: none;
}

.btn-gold:hover {
    background-color: #d4b03c;
    color: #1C1C1C;
}

.course-forum-answer-card {
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    transition: transform 0.2s ease;
}

.course-forum-answer-card:hover {
    transform: translateY(-2px);
}

</style>
<section class="p-15 m-15 border border-gray700 rounded-lg bg-dark-blue">
    <h3 class="font-20 font-weight-bold text-gold">{{ $courseForum->title }}</h3>

    <span class="d-block font-14 font-weight-500 text-gray mt-5">
        {{ trans('public.by') }} <span class="font-weight-bold text-gold">{{ $courseForum->user->full_name }}</span> 
        {{ trans('public.in') }} {{ dateTimeFormat($courseForum->created_at, 'j M Y | H:i') }}
    </span>

    <div class="mt-15">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb p-0 m-0 bg-transparent">
                <li class="breadcrumb-item font-12 text-gray"><a class="text-gold" href="{{ $course->getLearningPageUrl() }}">{{ $course->title }}</a></li>
                <li class="breadcrumb-item font-12 text-gray"><a class="text-gold" href="{{ $course->getForumPageUrl() }}">{{ trans('update.forum') }}</a></li>
                <li class="breadcrumb-item font-12 text-gold font-weight-bold" aria-current="page">{{ $courseForum->title }}</li>
            </ol>
        </nav>
    </div>
</section>

{{-- Load Question Card --}}
@include('web.default.course.learningPage.components.forum.forum_answer_card')

{{-- Load Answers Card --}}
@if(!empty($courseForum) and count($courseForum->answers))
    @foreach($courseForum->answers as $courseForumAnswer)
        @include('web.default.course.learningPage.components.forum.forum_answer_card',['answer' => $courseForumAnswer])
    @endforeach
@endif

{{-- Post Answer Card --}}
<div class="mt-25">
    <h3 class="font-20 font-weight-bold text-gold px-15">{{ trans('update.write_a_reply') }}</h3>

    <form action="{{ $course->getForumPageUrl() }}/{{ $courseForum->id }}/answers" method="post">
        <div class="course-forum-answer-card py-15 m-15 rounded-lg bg-black border border-gray700">
            <div class="d-flex flex-wrap">
                <div class="col-12 col-md-3">
                    <div class="position-relative bg-gray900 d-flex flex-column align-items-center justify-content-center rounded-lg w-100 h-100 p-20">
                        <div class="user-avatar rounded-circle border border-gold">
                            <img src="{{ $user->getAvatar(72) }}" class="img-cover rounded-circle" alt="{{ $user->full_name }}">
                        </div>
                        <h4 class="font-14 text-gold mt-15 font-weight-bold">{{ $user->full_name }}</h4>

                        <span class="px-10 py-5 mt-5 rounded-lg border bg-gray800 text-center font-12 text-gray">
                            @if($user->isUser())
                                {{ trans('quiz.student') }}
                            @elseif($user->isTeacher())
                                {{ trans('panel.teacher') }}
                            @elseif($user->isOrganization())
                                {{ trans('home.organization') }}
                            @elseif($user->isAdmin())
                                {{ trans('panel.staff') }}
                            @endif
                        </span>
                    </div>
                </div>

                <div class="col-12 col-md-9 mt-15 mt-md-0">
                    <div class="form-group mb-0 h-100 w-100">
                        <textarea name="description" class="form-control h-100 bg-gray800 text-gray border-0 rounded-lg p-3"></textarea>
                        <div class="invalid-feedback text-warning"></div>
                    </div>
                </div>
            </div>

            <div class="mt-10 text-right px-15">
                <button type="button" class="js-reply-course-question btn btn-gold btn-sm">{{ trans('update.post_reply') }}</button>
            </div>
        </div>
    </form>
</div>

{{-- Ask Modal For Edit Forum --}}
@include('web.default.course.learningPage.components.forum.ask_question_modal')

{{-- Edit Forum Answer Modal --}}
@include('web.default.course.learningPage.components.forum.edit_answer_modal')

