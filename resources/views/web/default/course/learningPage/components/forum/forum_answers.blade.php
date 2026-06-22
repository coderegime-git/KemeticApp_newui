<style>
    .kemetic-forum-header {
        background-color: #1A1A1A;
        border: 1px solid rgba(212,175,55,0.2);
        border-radius: 18px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.45);
        padding: 20px;
        margin: 15px;
    }

    .text-gold { color: #F2C94C !important; }

    .course-forum-answer-card {
        background-color: #1A1A1A;
        border: 1px solid rgba(212,175,55,0.2);
        border-radius: 18px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.45);
        margin: 15px;
        padding-bottom: 15px;
        transition: transform 0.2s ease;
    }

    .course-forum-answer-card:hover {
        transform: translateY(-2px);
    }

    .forum-textarea {
        background-color: #0f0f0f !important;
        border: 1px solid rgba(212,175,55,0.2) !important;
        border-radius: 12px;
        color: #fff !important;
        padding: 15px;
        resize: vertical;
        min-height: 120px;
    }

    .forum-textarea:focus {
        border-color: #F2C94C !important;
        outline: none;
        box-shadow: 0 0 0 2px rgba(212,175,55,0.2);
    }

    .btn-gold {
        background-color: #F2C94C;
        color: #000;
        font-weight: bold;
        border-radius: 12px;
        padding: 8px 20px;
        border: none;
    }

    .btn-gold:hover {
        background-color: #d4b03c;
    }
</style>
<section class="kemetic-forum-header">
    <h3 class="font-20 font-weight-bold text-gold">{{ $courseForum->title }}</h3>

    <span class="d-block font-14 font-weight-500 text-gray mt-2">
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
        <div class="course-forum-answer-card pt-15">
            <div class="row m-2">
                <div class="col-12 col-md-3 col-lg-2 text-center pt-15 pb-15" style="border-right: 1px solid rgba(212,175,55,0.1);">
                    <div class="user-avatar mx-auto rounded-circle d-inline-block border border-gold" style="width: 72px; height: 72px; overflow: hidden; flex-shrink: 0;">
                        <img src="{{ $user->getAvatar(72) }}" class="img-cover rounded-circle w-100 h-100" alt="{{ $user->full_name }}" style="object-fit:cover; max-width: 100%;">
                    </div>
                    <h4 class="font-14 text-gold mt-10 font-weight-bold">{{ $user->full_name }}</h4>

                    <div class="mt-10">
                        <span class="d-inline-block px-10 py-5 rounded-lg border font-12 text-gray" style="line-height:1; background:#0f0f0f; border-color:rgba(212,175,55,0.2) !important;">
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

                <div class="col-12 col-md-9 col-lg-10 pt-15 pb-15">
                    <div class="form-group mb-0 w-100 px-10">
                        <textarea name="description" class="form-control forum-textarea w-100"></textarea>
                        <div class="invalid-feedback text-warning"></div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-15 px-20" style="margin-right: 10px;">
                <button type="button" class="js-reply-course-question btn-gold">{{ trans('update.post_reply') }}</button>
            </div>
        </div>
    </form>
</div>

{{-- Ask Modal For Edit Forum --}}
@include('web.default.course.learningPage.components.forum.ask_question_modal')

{{-- Edit Forum Answer Modal --}}
@include('web.default.course.learningPage.components.forum.edit_answer_modal')

