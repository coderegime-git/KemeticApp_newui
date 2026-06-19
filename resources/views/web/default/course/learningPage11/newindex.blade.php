@extends('web.default.layouts.app',['appFooter' => false, 'appHeader' => false])

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/learning_page/styles.css"/>
    <link rel="stylesheet" href="/assets/default/vendors/video/video-js.min.css">
@endpush

@section('content')

    <div class="learning-page">

        <!-- @include('web.default.course.learningPage.components.navbar') -->
        @php
            $percent = $course->getProgress(true);
        @endphp

        <div class="learning-page-navbar d-flex align-items-lg-center justify-content-between flex-column flex-lg-row px-15 px-lg-35 py-10">
            <div class="d-flex align-items-lg-center flex-column flex-lg-row flex-grow-1">

                <div class="learning-page-logo-card d-flex align-items-center justify-content-between justify-content-lg-start">
                    <a class="navbar-brand d-flex align-items-center justify-content-center mr-0" href="/">
                        @if(!empty($generalSettings['logo']))
                            <img src="{{ $generalSettings['logo'] }}" class="img-cover" alt="site logo">
                        @endif
                    </a>

                    <div class="d-flex align-items-center d-lg-none ml-20">
                        <a href="{{ $course->getUrl() }}" class="btn learning-page-navbar-btn btn-sm border-gray200 d-none d-md-block">{{ trans('update.course_page') }}</a>

                        <a href="/panel/webinars/purchases" class="btn learning-page-navbar-btn btn-sm border-gray200 ml-0 ml-md-10">{{ trans('update.my_courses') }}</a>
                    </div>
                </div>

                <div class="learning-page-progress-card d-flex flex-column">
                    <a href="{{ $course->getUrl() }}" class="learning-page-navbar-title">
                        <span class="font-weight-bold">{{ $course->title }}</span>
                    </a>

                    <div class="d-flex align-items-center">
                        <div class="progress course-progress d-flex align-items-center flex-grow-1 bg-white border border-gray200 rounded-sm shadow-none mt-5">
                            <span class="progress-bar rounded-sm bg-warning" style="width: {{ $percent }}%"></span>
                        </div>

                        <span class="ml-10 font-weight-500 font-14 text-gray">{{ $percent }}% {{ trans('update.learnt') }}</span>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center mt-5 mt-md-0">

                @if(!empty($course->noticeboards_count) and $course->noticeboards_count > 0)
                    <a href="{{ $course->getNoticeboardsPageUrl() }}" target="_blank" class="btn learning-page-navbar-btn noticeboard-btn btn-sm border-gray200 mr-10">
                        <i data-feather="bell" class="" width="16" height="16"></i>

                        <span class="noticeboard-btn-badge d-flex align-items-center justify-content-center text-white bg-danger rounded-circle font-12">{{ $course->noticeboards_count }}</span>
                    </a>
                @endif

                @if($course->forum)
                    <a href="{{ $course->getForumPageUrl() }}" class="btn learning-page-navbar-btn btn-sm border-gray200 mr-10">{{ trans('update.course_forum') }}</a>
                @endif

                <div class="d-none align-items-center d-lg-flex">
                    <a href="{{ $course->getUrl() }}" class="btn learning-page-navbar-btn btn-sm border-gray200">{{ trans('update.course_page') }}</a>

                    <a href="/panel/webinars/purchases" class="btn learning-page-navbar-btn btn-sm border-gray200 ml-10">{{ trans('update.my_courses') }}</a>
                </div>

                <button id="collapseBtn" type="button" class="btn-transparent ml-auto ml-lg-20">
                    <i data-feather="menu" width="20" height="20" class=""></i>
                </button>
            </div>
        </div>


        <div class="d-flex position-relative">
            <div class="learning-page-content flex-grow-1 bg-info-light p-15">
                <!-- @include('web.default.course.learningPage.components.content') -->
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
                        <!-- @include('web.default.course.learningPage.components.forum.forum_answers') -->
                         <section class="p-15 m-15 border rounded-lg">
                            <h3 class="font-20 font-weight-bold text-secondary">{{ $courseForum->title }}</h3>

                            <span class="d-block font-14 font-weight-500 text-gray mt-5">{{ trans('public.by') }} <span class="font-weight-bold">{{ $courseForum->user->full_name }}</span> {{ trans('public.in') }} {{ dateTimeFormat($courseForum->created_at, 'j M Y | H:i') }}</span>

                            <div class="mt-15 ">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb p-0 m-0">
                                        <li class="breadcrumb-item font-12 text-gray"><a href="{{ $course->getLearningPageUrl() }}">{{ $course->title }}</a></li>
                                        <li class="breadcrumb-item font-12 text-gray"><a href="{{ $course->getForumPageUrl() }}">{{ trans('update.forum') }}</a></li>
                                        <li class="breadcrumb-item font-12 text-gray font-weight-bold" aria-current="page">{{ $courseForum->title }}</li>
                                    </ol>
                                </nav>
                            </div>
                        </section>

                        {{-- Load Question Card  --}}
                        @include('web.default.course.learningPage.components.forum.forum_answer_card')

                        {{-- Load Answers Card  --}}
                        @if(!empty($courseForum) and count($courseForum->answers))
                            @foreach($courseForum->answers as $courseForumAnswer)
                                @include('web.default.course.learningPage.components.forum.forum_answer_card',['answer' => $courseForumAnswer])
                            @endforeach
                        @endif

                        {{-- Post Answer Card  --}}
                        <div class="mt-25">
                            <h3 class="font-20 font-weight-bold text-secondary px-15">{{ trans('update.write_a_reply') }}</h3>

                            <form action="{{ $course->getForumPageUrl() }}/{{ $courseForum->id }}/answers" method="post">
                                <div class="course-forum-answer-card py-15 m-15 rounded-lg">
                                    <div class="d-flex flex-wrap">
                                        <div class="col-12 col-md-3">
                                            <div class="position-relative bg-info-light d-flex flex-column align-items-center justify-content-center rounded-lg w-100 h-100 p-20">
                                                <div class="user-avatar rounded-circle">
                                                    <img src="{{ $user->getAvatar(72) }}" class="img-cover rounded-circle" alt="{{ $user->full_name }}">
                                                </div>
                                                <h4 class="font-14 text-secondary mt-15 font-weight-bold">{{ $user->full_name }}</h4>

                                                <span class="px-10 py-5 mt-5 rounded-lg border bg-info-light text-center font-12 text-gray">
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
                                                <textarea name="description" class="form-control h-100"></textarea>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-10 text-right px-15">
                                        <button type="button" class="js-reply-course-question btn btn-primary btn-sm">{{ trans('update.post_reply') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @elseif(!empty($isForumPage) and $isForumPage)
                        <!-- @include('web.default.course.learningPage.components.forum.forum') -->

                        <section class="p-15 m-15 border rounded-lg">
                            <div class="course-forum-top-stats d-flex flex-wrap flex-md-nowrap align-items-center justify-content-around">
                                <div class="d-flex align-items-center justify-content-center pb-5 pb-md-0">
                                    <div class="d-flex flex-column align-items-center text-center">
                                        <img src="/assets/default/img/activity/47.svg" class="course-forum-top-stats__icon" alt="">
                                        <strong class="font-20 text-dark-blue font-weight-bold mt-5">{{ $questionsCount }}</strong>
                                        <span class="font-14 text-gray font-weight-500">{{ trans('public.questions') }}</span>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-center pb-5 pb-md-0">
                                    <div class="d-flex flex-column align-items-center text-center">
                                        <img src="/assets/default/img/activity/120.svg" class="course-forum-top-stats__icon" alt="">
                                        <strong class="font-20 text-dark-blue font-weight-bold mt-5">{{ $resolvedCount }}</strong>
                                        <span class="font-14 text-gray font-weight-500">{{ trans('update.resolved') }}</span>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-center pb-5 pb-md-0">
                                    <div class="d-flex flex-column align-items-center text-center">
                                        <img src="/assets/default/img/activity/119.svg" class="course-forum-top-stats__icon" alt="">
                                        <strong class="font-20 text-dark-blue font-weight-bold mt-5">{{ $openQuestionsCount }}</strong>
                                        <span class="font-14 text-gray font-weight-500">{{ trans('update.open_questions') }}</span>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-center pb-5 pb-md-0">
                                    <div class="d-flex flex-column align-items-center text-center">
                                        <img src="/assets/default/img/activity/39.svg" class="course-forum-top-stats__icon" alt="">
                                        <strong class="font-20 text-dark-blue font-weight-bold mt-5">{{ $commentsCount }}</strong>
                                        <span class="font-14 text-gray font-weight-500">{{ trans('update.answers') }}</span>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-center pb-5 pb-md-0">
                                    <div class="d-flex flex-column align-items-center text-center">
                                        <img src="/assets/default/img/activity/49.svg" class="course-forum-top-stats__icon" alt="">
                                        <strong class="font-20 text-dark-blue font-weight-bold mt-5">{{ $activeUsersCount }}</strong>
                                        <span class="font-14 text-gray font-weight-500">{{ trans('update.active_users') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="container-fluid p-15 rounded-lg bg-info-light font-14 text-gray mt-20">
                                <div class="row align-items-center">
                                    <div class="col-12 col-lg-4">
                                        <div class="">
                                            <h3 class="font-16 font-weight-bold text-dark-blue">{{ trans('update.course_forum') }}</h3>
                                            <span class="d-block font-14 font-weight-500 text-gray mt-1">{{ trans('update.communicate_others_and_ask_your_questions') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-5 mt-15 mt-lg-0">
                                        <form action="{{ request()->url() }}" method="get">
                                            <div class="d-flex align-items-center">
                                                <input type="text" name="search" class="form-control flex-grow-1" value="{{ request()->get('search') }}" placeholder="{{ trans('update.search_in_this_forum') }}">
                                                <button type="submit" class="btn btn-primary btn-sm ml-10 course-forum-search-btn">
                                                    <i data-feather="search" class="text-white" width="16" height="16"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-12 col-lg-3 mt-15 mt-lg-0 text-right">
                                        <button type="button" id="askNewQuestion" class="btn btn-primary btn-sm course-forum-search-btn">
                                            <i data-feather="file" class="text-white" width="16" height="16"></i>
                                            <span class="ml-1">{{ trans('update.ask_new_question') }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </section>

                        @if($forums and count($forums))
                            @foreach($forums as $forum)
                                <div class="course-forum-question-card p-15 m-15 border rounded-lg">
                                    <div class="row">
                                        <div class="col-12 col-lg-6">
                                            <div class="d-flex align-items-start">
                                                <div class="question-user-avatar">
                                                    <img src="{{ $forum->user->getAvatar(64) }}" class="img-cover rounded-circle" alt="{{ $forum->user->full_name }}">
                                                </div>
                                                <div class="ml-10">
                                                    <a href="{{ $course->getForumPageUrl() }}/{{ $forum->id }}/answers" class="">
                                                        <h4 class="font-16 font-weight-bold text-dark-blue">{{ $forum->title }}</h4>
                                                    </a>

                                                    <span class="d-block font-12 text-gray mt-5">{{ trans('public.by') }} {{ $forum->user->full_name }} {{ trans('public.in') }} {{ dateTimeFormat($forum->created_at, 'j M Y | H:i') }}</span>

                                                    <p class="d-block font-14 text-gray mt-10 white-space-pre-wrap">{{ $forum->description }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6 mt-15 mt-lg-0 border-left">
                                            @if($course->isOwner($user->id))
                                                <button type="button" data-action="{{ $course->getForumPageUrl() }}/{{ $forum->id }}/pinToggle" class="question-forum-pin-btn d-flex align-items-center justify-content-center">
                                                    <img src="/assets/default/img/learning/{{ $forum->pin ? 'un_pin' : 'pin' }}.svg" alt="pin icon" class="">
                                                </button>
                                            @endif


                                            @if(!empty($forum->answers) and count($forum->answers))
                                                <div class="py-15 row">
                                                    <div class="col-3">
                                                        <span class="d-block font-12 text-gray">{{ trans('public.answers') }}</span>
                                                        <span class="d-block font-14 text-dark mt-10">{{ $forum->answer_count }}</span>
                                                    </div>

                                                    <div class="col-3">
                                                        <span class="d-block font-12 text-gray">{{ trans('panel.users') }}</span>
                                                        <div class="answers-user-icons d-flex align-items-center">
                                                            @if(!empty($forum->usersAvatars))
                                                                @foreach($forum->usersAvatars as $userAvatar)
                                                                    <div class="user-avatar-card rounded-circle">
                                                                        <img src="{{ $userAvatar->getAvatar(32) }}" class="img-cover rounded-circle" alt="{{ $userAvatar->full_name }}">
                                                                    </div>
                                                                @endforeach
                                                            @endif

                                                            @if(($forum->answers->groupBy('user_id')->count() - count($forum->usersAvatars)) > 0)
                                                                <span class="answer-count d-flex align-items-center justify-content-center font-12 text-gray rounded-circle">+{{ $forum->answer_count - count($forum->usersAvatars) }}</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-6 position-relative">
                                                        <span class="d-block font-12 text-gray">{{ trans('update.last_activity') }}</span>
                                                        <span class="d-block font-14 text-dark mt-10">{{ dateTimeFormat($forum->lastAnswer->created_at,'j M Y | H:i') }}</span>
                                                    </div>
                                                </div>

                                                <div class="py-15 border-top position-relative">
                                                    <span class="d-block font-12 text-gray text-left">{{ trans('update.last_answer') }}</span>

                                                    <div class="d-flex align-items-start mt-20">
                                                        <div class="last-answer-user-avatar">
                                                            <img src="{{ $forum->lastAnswer->user->getAvatar(30) }}" class="img-cover rounded-circle" alt="{{ $forum->lastAnswer->user->full_name }}">
                                                        </div>
                                                        <div class="ml-10">
                                                            <h4 class="font-14 text-dark font-weight-bold">{{ $forum->lastAnswer->user->full_name }}</h4>
                                                            <p class="font-12 font-weight-500 text-gray mt-5">{!! truncate($forum->lastAnswer->description, 160) !!}</p>
                                                        </div>
                                                    </div>

                                                    @if(!empty($forum->resolved))
                                                        <div class="resolved-answer-badge d-flex align-items-center font-12 text-primary">
                                                            <span class="badge-icon d-flex align-items-center justify-content-center">
                                                                <i data-feather="check" width="20" height="20"></i>
                                                            </span>
                                                            {{ trans('update.resolved') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="d-flex flex-column justify-content-center text-center py-15 h-100">
                                                    <p class="text-gray font-14 font-weight-bold">{{ trans('update.be_the_first_to_answer_this_question') }}</p>

                                                    <div class="">
                                                        <a href="{{ $course->getForumPageUrl() }}/{{ $forum->id }}/answers" class="btn btn-primary btn-sm mt-15">{{ trans('public.answer') }}</a>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="learning-page-forum-empty d-flex align-items-center justify-content-center flex-column">
                                <div class="learning-page-forum-empty-icon d-flex align-items-center justify-content-center">
                                    <img src="/assets/default/img/learning/forum-empty.svg" class="img-fluid" alt="">
                                </div>

                                <div class="d-flex align-items-center flex-column mt-10 text-center">
                                    <h3 class="font-20 font-weight-bold text-dark-blue text-center"></h3>
                                    <p class="font-14 font-weight-500 text-gray mt-5 text-center">{{ trans('update.learning_page_empty_content_title_hint') }}</p>
                                </div>
                            </div>
                        @endif

                        @include('web.default.course.learningPage.components.forum.ask_question_modal')

                    @elseif(!empty($noticeboards) and $noticeboards)
                        <!-- @include('web.default.course.learningPage.components.noticeboards') -->
                         <section class="px-15 pb-15 my-15 mx-lg-15 bg-white rounded-lg">

                            @if(!empty($course->noticeboards) and count($course->noticeboards))
                                @foreach($course->noticeboards as $noticeboard)
                                    <div class="course-noticeboards noticeboard-{{ $noticeboard->color }} p-15 mt-15 rounded-sm w-100">
                                        <div class="d-flex align-items-center">
                                            <div class="course-noticeboard-icon d-flex align-items-center justify-content-center rounded-circle">
                                                <i data-feather="{{ $noticeboard->getIcon() }}" class="" width="24" height="24"></i>
                                            </div>

                                            <div class="ml-10">
                                                <h3 class="font-14 font-weight-bold">{{ $noticeboard->title }}</h3>
                                                <span class="d-block font-12">{{ $noticeboard->creator->full_name }} {{ trans('public.in') }} {{ dateTimeFormat($noticeboard->created_at,'j M Y') }}</span>
                                            </div>
                                        </div>

                                        <div class="mt-10 font-14">{!! $noticeboard->message !!}</div>
                                    </div>
                                @endforeach
                            @endif

                        </section>

                    @elseif(!empty($assignment))
                        <!-- @include('web.default.course.learningPage.components.assignment') -->
                         <section class="p-15 m-15 border rounded-lg">
                            <div class="assignment-top-stats d-flex flex-wrap flex-md-nowrap align-items-center justify-content-around">
                                <div class="assignment-top-stats__item d-flex align-items-center justify-content-center pb-5 pb-md-0">
                                    <div class="d-flex flex-column align-items-center text-center">
                                        <img src="/assets/default/img/activity/calendar.svg" class="assignment-top-stats__icon" alt="">
                                        <strong class="font-20 text-dark-blue font-weight-bold mt-5">
                                            @if($assignmentDeadline)
                                                {{ is_bool($assignmentDeadline) ? trans('update.unlimited') : trans('update.n_day', ['day' => ceil($assignmentDeadline)]) }}
                                            @else
                                                <span class="text-danger">{{ trans('panel.expired') }}</span>
                                            @endif
                                        </strong>
                                        <span class="font-14 text-gray font-weight-500">{{ trans('update.deadline') }}</span>
                                    </div>
                                </div>

                                <div class="assignment-top-stats__item d-flex align-items-center justify-content-center pb-5 pb-md-0">
                                    <div class="d-flex flex-column align-items-center text-center">
                                        <img src="/assets/default/img/activity/homework.svg" class="assignment-top-stats__icon" alt="">
                                        <strong class="font-20 text-dark-blue font-weight-bold mt-5">
                                            @if(!empty($assignment->attempts))
                                                {{ $submissionTimes }}/{{ $assignment->attempts  }}
                                            @else
                                                {{ trans('update.unlimited') }}
                                            @endif
                                        </strong>
                                        <span class="font-14 text-gray font-weight-500">{{ trans('update.submission_times') }}</span>
                                    </div>
                                </div>

                                <div class="assignment-top-stats__item d-flex align-items-center justify-content-center pb-5 pb-md-0">
                                    <div class="d-flex flex-column align-items-center text-center">
                                        <img src="/assets/default/img/activity/45.svg" class="assignment-top-stats__icon" alt="">
                                        <strong class="font-20 text-dark-blue font-weight-bold mt-5">{{ $assignmentHistory->grade ?? 0 }}/{{ $assignment->grade }}</strong>
                                        <span class="font-14 text-gray font-weight-500">{{ trans('quiz.your_grade') }}</span>
                                    </div>
                                </div>

                                <div class="assignment-top-stats__item d-flex align-items-center justify-content-center pb-5 pb-md-0">
                                    <div class="d-flex flex-column align-items-center text-center">
                                        <img src="/assets/default/img/activity/58.svg" class="assignment-top-stats__icon" alt="">
                                        <strong class="font-20 text-dark-blue font-weight-bold mt-5">{{ $assignment->pass_grade }}</strong>
                                        <span class="font-14 text-gray font-weight-500">{{ trans('update.min_grade') }}</span>
                                    </div>
                                </div>

                                <div class="assignment-top-stats__item d-flex align-items-center justify-content-center pb-5 pb-md-0">
                                    <div class="d-flex flex-column align-items-center text-center">
                                        <img src="/assets/default/img/activity/88.svg" class="assignment-top-stats__icon" alt="">
                                        <strong class="font-20 text-dark-blue font-weight-bold mt-5">{{ trans('update.assignment_history_status_'.$assignmentHistory->status) }}</strong>
                                        <span class="font-14 text-gray font-weight-500">{{ trans('public.status') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-15 rounded-lg bg-info-light font-14 text-gray mt-20">{!! $assignment->description !!}</div>
                        </section>

                        @if(!empty($assignment->attachments) and count($assignment->attachments))
                            <section class="mt-25 container-fluid">
                                <h2 class="section-title">{{ trans('public.attachments') }}</h2>

                                <div class="row">
                                    @foreach($assignment->attachments as $attachment)
                                        <div class="col-6 col-lg-3 mt-10">
                                            <a href="{{ $attachment->getDownloadUrl() }}" target="_blank" class="d-flex align-items-center p-10 border rounded-sm">
                                                <span class="chapter-icon bg-gray300 mr-10">
                                                    <i data-feather="file" class="text-gray" width="16" height="16"></i>
                                                </span>

                                                <div class="">
                                                    <h4 class="font-12 text-gray font-weight-bold">{{ $attachment->title }}</h4>
                                                    <span class="font-12 text-gray">{{ $attachment->getFileSize() }}</span>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        @endif

                        <section class="mt-25 container-fluid">
                            <h2 class="section-title">{{ trans('update.assignment_history') }}</h2>

                            <section class=" p-10 my-10 border rounded-lg">
                                <div class="row">
                                    <div class="col-12 col-lg-4">
                                        @if(
                                            $user->id != $assignment->creator_id and
                                            (
                                                $assignmentHistory->status == \App\Models\WebinarAssignmentHistory::$passed or
                                                $assignmentHistory->status == \App\Models\WebinarAssignmentHistory::$notPassed or
                                                !$assignmentDeadline or
                                                (
                                                    !$checkHasAttempts and !empty($assignment->attempts) and $submissionTimes >= $assignment->attempts
                                                )
                                            )
                                        )
                                            <div class="d-flex align-items-center justify-content-center flex-column bg-info-light p-10 rounded-sm border h-100">
                                                <div class="learning-page-assignment-history-status-icon d-flex align-items-center justify-content-center">
                                                    @if($assignmentHistory->status == \App\Models\WebinarAssignmentHistory::$passed)
                                                        <img src="/assets/default/img/learning/assignment_passed.svg" class="img-fluid" alt="">
                                                    @elseif($assignmentHistory->status == \App\Models\WebinarAssignmentHistory::$notPassed)
                                                        <img src="/assets/default/img/learning/no_assignment.svg" class="img-fluid" alt="">
                                                    @else
                                                        <img src="/assets/default/img/learning/assignment_pending.svg" class="img-fluid" alt="">
                                                    @endif
                                                </div>

                                                <div class="d-flex align-items-center flex-column mt-10 text-center">
                                                    @if($assignmentHistory->status == \App\Models\WebinarAssignmentHistory::$passed)
                                                        <h3 class="font-20 font-weight-bold text-dark-blue text-center">{{ trans('update.assignment_passed_title') }}</h3>
                                                        <p class="mt-5 text-gray font-14 text-center">{{ trans('update.assignment_passed_desc') }}</p>
                                                    @elseif($assignmentHistory->status == \App\Models\WebinarAssignmentHistory::$notPassed)
                                                        <h3 class="font-20 font-weight-bold text-dark-blue text-center">{{ trans('update.assignment_not_passed_title') }}</h3>
                                                        <p class="mt-5 text-gray font-14 text-center">{{ trans('update.assignment_not_passed_desc') }}</p>
                                                    @elseif(!$assignmentDeadline)
                                                        <h3 class="font-20 font-weight-bold text-dark-blue text-center">{{ trans('update.assignment_deadline_error_title') }}</h3>
                                                        <p class="mt-5 text-gray font-14 text-center">{{ trans('update.assignment_deadline_error_desc') }}</p>
                                                    @else
                                                        <h3 class="font-20 font-weight-bold text-dark-blue text-center">{{ trans('update.assignment_submission_error_title') }}</h3>
                                                        <p class="mt-5 text-gray font-14 text-center">{{ trans('update.assignment_submission_error_desc') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <div class="bg-info-light p-10 rounded-sm border">
                                                <h4 class="font-16 font-weight-bold text-dark-blue">
                                                    @if($user->id == $assignment->creator_id)
                                                        {{ trans('update.reply_to_the_conversation') }}
                                                    @else
                                                        {{ trans('update.send_assignment') }}
                                                    @endif
                                                </h4>

                                                <form method="post" action="/course/assignment/{{ $assignment->id }}/history/{{ $assignmentHistory->id }}/message">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
                                                    <input type="hidden" name="assignment_history_id" value="{{ $assignmentHistory->id }}">
                                                    @if($user->id == $assignment->creator_id)
                                                        <input type="hidden" name="student_id" value="{{ $assignmentHistory->student_id }}">
                                                    @endif

                                                    <div class="form-group">
                                                        <label class="input-label">{{ trans('public.description') }}</label>
                                                        <textarea rows="6" name="description" class="form-control"></textarea>
                                                        <div class="invalid-feedback"></div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="input-label">{{ trans('update.file_title') }} ({{ trans('public.optional') }})</label>
                                                        <input name="file_title" class="form-control"/>
                                                        <div class="invalid-feedback"></div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="input-label">{{ trans('update.attach_a_file') }} ({{ trans('public.optional') }})</label>

                                                        <div class="d-flex align-items-center">
                                                            <div class="input-group mr-10">
                                                                <div class="input-group-prepend">
                                                                    <button type="button" class="input-group-text panel-file-manager" data-input="assignmentAttachmentInput" data-preview="holder">
                                                                        <i data-feather="upload" width="18" height="18" class="text-white"></i>
                                                                    </button>
                                                                </div>
                                                                <input type="text" name="file_path" id="assignmentAttachmentInput" value="" class="form-control" placeholder="{{ trans('update.assignment_attachments_placeholder') }}"/>
                                                            </div>

                                                            <button type="button" class="js-save-history-message btn btn-primary btn-sm">{{ trans('update.send') }}</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                            @if($user->id == $assignment->creator_id)
                                                <div class="bg-info-light p-10 rounded-sm border mt-15">
                                                    <h4 class="font-16 font-weight-bold text-dark-blue">{{ trans('update.rate_the_assignment') }}</h4>

                                                    <form method="post" action="/course/assignment/{{ $assignment->id }}/history/{{ $assignmentHistory->id }}/setGrade">
                                                        <input type="hidden" name="student_id" value="{{ $assignmentHistory->student_id }}">

                                                        <div class="form-group">
                                                            <label class="input-label">{{ trans('update.assignments_grade') }}</label>
                                                            <div class="d-flex align-items-start">
                                                                <div class="mr-10 w-100">
                                                                    <input name="grade" class="form-control" placeholder="{{ trans('update.pass_grade') }}: {{ $assignment->pass_grade }}"/>
                                                                    <div class="invalid-feedback"></div>
                                                                </div>

                                                                <button type="button" class="js-save-history-rate btn btn-primary btn-sm">{{ trans('public.submit') }}</button>
                                                            </div>
                                                        </div>
                                                        <p class="font-12 text-gray">{{ trans('update.by_submitting_the_grade_you_the_assignment_will_be_closed') }}</p>
                                                    </form>
                                                </div>
                                            @endif
                                        @endif
                                    </div>

                                    <div class="col-12 col-lg-8 border-left">

                                        <div class="h-100">

                                            @if(!empty($assignmentHistory->messages) and count($assignmentHistory->messages))
                                                @foreach($assignmentHistory->messages as $message)
                                                    <div class="assignment-attachments-post p-15 border rounded-sm mb-15">
                                                        <div class="d-flex align-items-center">
                                                            <div class="user-avatar rounded-circle">
                                                                <img src="{{ $message->sender->getAvatar(50) }}" class="img-cover rounded-circle" alt="{{ $message->sender->full_name }}">
                                                            </div>
                                                            <div class="ml-10">
                                                                <h4 class="font-14 font-weight-500 text-dark-blue">{{ $message->sender->full_name }}</h4>
                                                                <span class="d-block font-12 text-gray">{{ dateTimeFormat($message->created_at, 'j M Y | H:i') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="mt-15 font-14 text-gray">
                                                            {!! $message->message !!}
                                                        </div>

                                                        @if(!empty($message->file_path))
                                                            <div class="d-flex flex-wrap align-items-center mt-10">
                                                                <a href="{{ $message->getDownloadUrl($assignment->id) }}" target="_blank" class="d-flex align-items-center text-gray bg-info-light border px-10 py-5 rounded-pill mr-10 mt-5">
                                                                    <i data-feather="paperclip" class="text-gray" width="16" height="16"></i>
                                                                    <span class="ml-5 font-12 text-gray">{{ !empty($message->file_title) ? $message->file_title : trans('update.attachment') }}</span>
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="d-flex align-items-center justify-content-center flex-column h-100">
                                                    <div class="learning-page-assignment-history-status-icon d-flex align-items-center justify-content-center">
                                                        <img src="/assets/default/img/learning/no_assignment.svg" class="img-fluid" alt="">
                                                    </div>

                                                    <div class="d-flex align-items-center flex-column mt-10 text-center">
                                                        <h3 class="font-20 font-weight-bold text-dark-blue text-center">{{ trans('update.no_assignment') }}</h3>
                                                        <p class="mt-5 text-gray font-14 text-center">{{ trans('update.submit_your_assignment_and_evaluate_your_learning') }}</p>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </section>
                        </section>

                    @endif

                    <div class="learning-content-loading align-items-center justify-content-center flex-column w-100 h-100 {{ $showLoading ? 'd-flex' : 'd-none' }}">
                        <img src="/assets/default/img/loading.gif" alt="">
                        <p class="mt-10">{{ trans('update.please_wait_for_the_content_to_load') }}</p>
                    </div>
                </div>

            </div>

            <div class="learning-page-tabs show">
                <ul class="nav nav-tabs py-15 d-flex align-items-center justify-content-around" id="tabs-tab" role="tablist">
                    <li class="nav-item">
                        <a class="position-relative font-14 d-flex align-items-center active" id="content-tab"
                           data-toggle="tab" href="#content" role="tab" aria-controls="content"
                           aria-selected="true">
                            <i class="learning-page-tabs-icons mr-5">
                                @include('web.default.panel.includes.sidebar_icons.webinars')
                            </i>
                            <span class="learning-page-tabs-link-text">{{ trans('product.content') }}</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="position-relative font-14 d-flex align-items-center" id="quizzes-tab" data-toggle="tab"
                           href="#quizzes" role="tab" aria-controls="quizzes"
                           aria-selected="false">
                            <i class="learning-page-tabs-icons mr-5">
                                @include('web.default.panel.includes.sidebar_icons.quizzes')
                            </i>
                            <span class="learning-page-tabs-link-text">{{ trans('quiz.quizzes') }}</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="position-relative font-14 d-flex align-items-center" id="certificates-tab" data-toggle="tab"
                           href="#certificates" role="tab" aria-controls="certificates"
                           aria-selected="false">
                            <i class="learning-page-tabs-icons mr-5">
                                @include('web.default.panel.includes.sidebar_icons.certificate')
                            </i>
                            <span class="learning-page-tabs-link-text">{{ trans('panel.certificates') }}</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content h-100" id="nav-tabContent">
                    <div class="pb-20 tab-pane fade show active h-100" id="content" role="tabpanel"
                         aria-labelledby="content-tab">
                        <div class="content-tab p-15 pb-50">

                            @if(
                                (empty($sessionsWithoutChapter) or !count($sessionsWithoutChapter)) and
                                (empty($textLessonsWithoutChapter) or !count($textLessonsWithoutChapter)) and
                                (empty($filesWithoutChapter) or !count($filesWithoutChapter)) and
                                (empty($course->chapters) or !count($course->chapters))
                            )
                                <div class="learning-page-forum-empty d-flex align-items-center justify-content-center flex-column">
                                    <div class="learning-page-forum-empty-icon d-flex align-items-center justify-content-center">
                                        <img src="/assets/default/img/learning/content-empty.svg" class="img-fluid" alt="">
                                    </div>

                                    <div class="d-flex align-items-center flex-column mt-10 text-center">
                                        <h3 class="font-20 font-weight-bold text-dark-blue text-center">{{ trans('update.learning_page_empty_content_title') }}</h3>
                                        <p class="font-14 font-weight-500 text-gray mt-5 text-center">{{ trans('update.learning_page_empty_content_hint') }}</p>
                                    </div>
                                </div>
                            @else
                                @if(!empty($sessionsWithoutChapter) and count($sessionsWithoutChapter))
                                    @foreach($sessionsWithoutChapter as $session)
                                        <!-- @include('web.default.course.learningPage.components.content_tab.content',['item' => $session, 'type' => \App\Models\WebinarChapter::$chapterSession]) -->
                                        @php
                                            $icon = '';
                                            $hintText= '';

                                            if ($type == \App\Models\WebinarChapter::$chapterSession) {
                                                $icon = 'video';
                                                $hintText = dateTimeFormat($item->date, 'j M Y  H:i') . ' | ' . $item->duration . ' ' . trans('public.min');
                                            } elseif ($type == \App\Models\WebinarChapter::$chapterFile) {
                                                $hintText = trans('update.file_type_'.$item->file_type) . ($item->volume > 0 ? ' | '.$item->getVolume() : '');

                                                $icon = $item->getIconByType();
                                            } elseif ($type == \App\Models\WebinarChapter::$chapterTextLesson) {
                                                $icon = 'file-text';
                                                $hintText= $item->study_time . ' ' . trans('public.min');
                                            }

                                            $checkSequenceContent = $item->checkSequenceContent();
                                            $sequenceContentHasError = (!empty($checkSequenceContent) and (!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error'])));

                                            $itemPersonalNote = $item->personalNote()->where('user_id', $authUser->id)->first();
                                            $hasPersonalNote = (!empty($itemPersonalNote) and !empty($itemPersonalNote->note));
                                        @endphp

                                        <div class=" d-flex align-items-start p-10 cursor-pointer {{ (!empty($checkSequenceContent) and $sequenceContentHasError) ? 'js-sequence-content-error-modal' : 'tab-item' }}"
                                            data-type="{{ $type }}"
                                            data-id="{{ $item->id }}"
                                            data-passed-error="{{ !empty($checkSequenceContent['all_passed_items_error']) ? $checkSequenceContent['all_passed_items_error'] : '' }}"
                                            data-access-days-error="{{ !empty($checkSequenceContent['access_after_day_error']) ? $checkSequenceContent['access_after_day_error'] : '' }}"
                                        >

                                                <span class="chapter-icon bg-gray300 mr-10">
                                                    <i data-feather="{{ $icon }}" class="text-gray" width="16" height="16"></i>
                                                </span>

                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="">
                                                        <span class="font-weight-500 font-14 text-dark-blue d-block">{{ $item->title }}</span>
                                                        <span class="font-12 text-gray d-block">{{ $hintText }}</span>
                                                    </div>

                                                    @if($hasPersonalNote)
                                                        <span class="item-personal-note-icon d-flex-center bg-gray200">
                                                            <i data-feather="edit-2" class="text-gray" width="14" height="14"></i>
                                                        </span>
                                                    @endif
                                                </div>


                                                <div class="tab-item-info mt-15">
                                                    <p class="font-12 text-gray d-block">
                                                        @php
                                                            $description = !empty($item->description) ? $item->description : (!empty($item->summary) ? $item->summary : '');
                                                        @endphp

                                                        {!! truncate($description, 150) !!}
                                                    </p>

                                                    <div class="d-flex align-items-center justify-content-between mt-15">
                                                        <label class="mb-0 mr-10 cursor-pointer font-weight-normal font-14 text-dark-blue" for="readToggle{{ $type }}{{ $item->id }}">{{ trans('public.i_passed_this_lesson') }}</label>
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" @if($sequenceContentHasError) disabled @endif id="readToggle{{ $type }}{{ $item->id }}" data-item-id="{{ $item->id }}" data-item="{{ $type }}_id" value="{{ $item->webinar_id }}" class="js-passed-lesson-toggle custom-control-input" @if(!empty($item->checkPassedItem())) checked @endif>
                                                            <label class="custom-control-label" for="readToggle{{ $type }}{{ $item->id }}"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    @endforeach
                                @endif

                                @if(!empty($textLessonsWithoutChapter) and count($textLessonsWithoutChapter))
                                    @foreach($textLessonsWithoutChapter as $textLesson)
                                        <!-- @include('web.default.course.learningPage.components.content_tab.content',['item' => $textLesson, 'type' => \App\Models\WebinarChapter::$chapterTextLesson]) -->
                                         @php
                                            $icon = '';
                                            $hintText= '';

                                            if ($type == \App\Models\WebinarChapter::$chapterSession) {
                                                $icon = 'video';
                                                $hintText = dateTimeFormat($item->date, 'j M Y  H:i') . ' | ' . $item->duration . ' ' . trans('public.min');
                                            } elseif ($type == \App\Models\WebinarChapter::$chapterFile) {
                                                $hintText = trans('update.file_type_'.$item->file_type) . ($item->volume > 0 ? ' | '.$item->getVolume() : '');

                                                $icon = $item->getIconByType();
                                            } elseif ($type == \App\Models\WebinarChapter::$chapterTextLesson) {
                                                $icon = 'file-text';
                                                $hintText= $item->study_time . ' ' . trans('public.min');
                                            }

                                            $checkSequenceContent = $item->checkSequenceContent();
                                            $sequenceContentHasError = (!empty($checkSequenceContent) and (!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error'])));

                                            $itemPersonalNote = $item->personalNote()->where('user_id', $authUser->id)->first();
                                            $hasPersonalNote = (!empty($itemPersonalNote) and !empty($itemPersonalNote->note));
                                        @endphp

                                        <div class=" d-flex align-items-start p-10 cursor-pointer {{ (!empty($checkSequenceContent) and $sequenceContentHasError) ? 'js-sequence-content-error-modal' : 'tab-item' }}"
                                            data-type="{{ $type }}"
                                            data-id="{{ $item->id }}"
                                            data-passed-error="{{ !empty($checkSequenceContent['all_passed_items_error']) ? $checkSequenceContent['all_passed_items_error'] : '' }}"
                                            data-access-days-error="{{ !empty($checkSequenceContent['access_after_day_error']) ? $checkSequenceContent['access_after_day_error'] : '' }}"
                                        >

                                                <span class="chapter-icon bg-gray300 mr-10">
                                                    <i data-feather="{{ $icon }}" class="text-gray" width="16" height="16"></i>
                                                </span>

                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="">
                                                        <span class="font-weight-500 font-14 text-dark-blue d-block">{{ $item->title }}</span>
                                                        <span class="font-12 text-gray d-block">{{ $hintText }}</span>
                                                    </div>

                                                    @if($hasPersonalNote)
                                                        <span class="item-personal-note-icon d-flex-center bg-gray200">
                                                            <i data-feather="edit-2" class="text-gray" width="14" height="14"></i>
                                                        </span>
                                                    @endif
                                                </div>


                                                <div class="tab-item-info mt-15">
                                                    <p class="font-12 text-gray d-block">
                                                        @php
                                                            $description = !empty($item->description) ? $item->description : (!empty($item->summary) ? $item->summary : '');
                                                        @endphp

                                                        {!! truncate($description, 150) !!}
                                                    </p>

                                                    <div class="d-flex align-items-center justify-content-between mt-15">
                                                        <label class="mb-0 mr-10 cursor-pointer font-weight-normal font-14 text-dark-blue" for="readToggle{{ $type }}{{ $item->id }}">{{ trans('public.i_passed_this_lesson') }}</label>
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" @if($sequenceContentHasError) disabled @endif id="readToggle{{ $type }}{{ $item->id }}" data-item-id="{{ $item->id }}" data-item="{{ $type }}_id" value="{{ $item->webinar_id }}" class="js-passed-lesson-toggle custom-control-input" @if(!empty($item->checkPassedItem())) checked @endif>
                                                            <label class="custom-control-label" for="readToggle{{ $type }}{{ $item->id }}"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                @if(!empty($filesWithoutChapter) and count($filesWithoutChapter))
                                    @foreach($filesWithoutChapter as $file)
                                        <!-- @include('web.default.course.learningPage.components.content_tab.content',['item' => $file, 'type' => \App\Models\WebinarChapter::$chapterFile]) -->
                                         @php
                                            $icon = '';
                                            $hintText= '';

                                            if ($type == \App\Models\WebinarChapter::$chapterSession) {
                                                $icon = 'video';
                                                $hintText = dateTimeFormat($item->date, 'j M Y  H:i') . ' | ' . $item->duration . ' ' . trans('public.min');
                                            } elseif ($type == \App\Models\WebinarChapter::$chapterFile) {
                                                $hintText = trans('update.file_type_'.$item->file_type) . ($item->volume > 0 ? ' | '.$item->getVolume() : '');

                                                $icon = $item->getIconByType();
                                            } elseif ($type == \App\Models\WebinarChapter::$chapterTextLesson) {
                                                $icon = 'file-text';
                                                $hintText= $item->study_time . ' ' . trans('public.min');
                                            }

                                            $checkSequenceContent = $item->checkSequenceContent();
                                            $sequenceContentHasError = (!empty($checkSequenceContent) and (!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error'])));

                                            $itemPersonalNote = $item->personalNote()->where('user_id', $authUser->id)->first();
                                            $hasPersonalNote = (!empty($itemPersonalNote) and !empty($itemPersonalNote->note));
                                        @endphp

                                        <div class=" d-flex align-items-start p-10 cursor-pointer {{ (!empty($checkSequenceContent) and $sequenceContentHasError) ? 'js-sequence-content-error-modal' : 'tab-item' }}"
                                            data-type="{{ $type }}"
                                            data-id="{{ $item->id }}"
                                            data-passed-error="{{ !empty($checkSequenceContent['all_passed_items_error']) ? $checkSequenceContent['all_passed_items_error'] : '' }}"
                                            data-access-days-error="{{ !empty($checkSequenceContent['access_after_day_error']) ? $checkSequenceContent['access_after_day_error'] : '' }}"
                                        >

                                                <span class="chapter-icon bg-gray300 mr-10">
                                                    <i data-feather="{{ $icon }}" class="text-gray" width="16" height="16"></i>
                                                </span>

                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="">
                                                        <span class="font-weight-500 font-14 text-dark-blue d-block">{{ $item->title }}</span>
                                                        <span class="font-12 text-gray d-block">{{ $hintText }}</span>
                                                    </div>

                                                    @if($hasPersonalNote)
                                                        <span class="item-personal-note-icon d-flex-center bg-gray200">
                                                            <i data-feather="edit-2" class="text-gray" width="14" height="14"></i>
                                                        </span>
                                                    @endif
                                                </div>


                                                <div class="tab-item-info mt-15">
                                                    <p class="font-12 text-gray d-block">
                                                        @php
                                                            $description = !empty($item->description) ? $item->description : (!empty($item->summary) ? $item->summary : '');
                                                        @endphp

                                                        {!! truncate($description, 150) !!}
                                                    </p>

                                                    <div class="d-flex align-items-center justify-content-between mt-15">
                                                        <label class="mb-0 mr-10 cursor-pointer font-weight-normal font-14 text-dark-blue" for="readToggle{{ $type }}{{ $item->id }}">{{ trans('public.i_passed_this_lesson') }}</label>
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" @if($sequenceContentHasError) disabled @endif id="readToggle{{ $type }}{{ $item->id }}" data-item-id="{{ $item->id }}" data-item="{{ $type }}_id" value="{{ $item->webinar_id }}" class="js-passed-lesson-toggle custom-control-input" @if(!empty($item->checkPassedItem())) checked @endif>
                                                            <label class="custom-control-label" for="readToggle{{ $type }}{{ $item->id }}"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                @if(!empty($course->chapters) and count($course->chapters))
                                    <!-- @include('web.default.course.learningPage.components.content_tab.chapter') -->
                                     @if(!empty($course->chapters) and count($course->chapters))
                                        <div class="accordion-content-wrapper mt-15" id="chapterAccordion" role="tablist" aria-multiselectable="true">
                                            @foreach($course->chapters as $chapter)
                                                <div class="accordion-row bg-white rounded-sm border border-gray200 mb-2">
                                                    <div class="d-flex align-items-center justify-content-between p-10" role="tab" id="chapter_{{ $chapter->id  }}">
                                                        <div class="d-flex align-items-center" href="#collapseChapter{{ $chapter->id  }}" aria-controls="collapseChapter{{ $chapter->id  }}" data-parent="#chapterAccordion" role="button" data-toggle="collapse" aria-expanded="true">
                                                            <span class="chapter-icon mr-10">
                                                                <i data-feather="grid" class="" width="20" height="20"></i>
                                                            </span>

                                                            <div class="">
                                                                <span class="font-weight-bold font-14 text-dark-blue d-block">{{ $chapter->title }}</span>

                                                                <span class="font-12 text-gray d-block">
                                                                    {{ $chapter->getTopicsCount(true) }} {{ trans('public.topic') }}
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <div class="d-flex align-items-center">
                                                            <i class="collapse-chevron-icon feather-chevron-down text-gray" data-feather="chevron-down" height="20" href="#collapseChapter{{ $chapter->id  }}" aria-controls="collapseChapter{{ $chapter->id  }}" data-parent="#chapterAccordion" role="button" data-toggle="collapse" aria-expanded="true"></i>
                                                        </div>
                                                    </div>

                                                    <div id="collapseChapter{{ $chapter->id  }}" aria-labelledby="chapter_{{ $chapter->id  }}" class="collapse" role="tabpanel">
                                                        <div class="panel-collapse text-gray">

                                                            @if(!empty($chapter->chapterItems) and count($chapter->chapterItems))
                                                                @foreach($chapter->chapterItems as $chapterItem)
                                                                    @if($chapterItem->type == \App\Models\WebinarChapterItem::$chapterSession and !empty($chapterItem->session) and $chapterItem->session->status == 'active')
                                                                        @include('web.default.course.learningPage.components.content_tab.content' , ['item' => $chapterItem->session, 'type' => 'session'])
                                                                    @elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterFile and !empty($chapterItem->file) and $chapterItem->file->status == 'active')
                                                                        @include('web.default.course.learningPage.components.content_tab.content' , ['item' => $chapterItem->file, 'type' => 'file'])
                                                                    @elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterTextLesson and !empty($chapterItem->textLesson) and $chapterItem->textLesson->status == 'active')
                                                                        @include('web.default.course.learningPage.components.content_tab.content' , ['item' => $chapterItem->textLesson, 'type' => 'text_lesson'])
                                                                    @elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterAssignment and !empty($chapterItem->assignment) and $chapterItem->assignment->status == 'active')
                                                                        @include('web.default.course.learningPage.components.content_tab.assignment-content-tab' ,['item' => $chapterItem->assignment])
                                                                    @elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterQuiz and !empty($chapterItem->quiz) and $chapterItem->quiz->status == 'active')
                                                                        @include('web.default.course.learningPage.components.quiz_tab.quiz' ,['item' => $chapterItem->quiz, 'type' => 'quiz'])
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                @endif

                            @endif
                        </div>

                    </div>

                    <div class="pb-20 tab-pane fade  h-100" id="quizzes" role="tabpanel"
                         aria-labelledby="quizzes-tab">
                        <div class="content-tab p-15 pb-50">
                            @if(!empty($course->quizzes) and $course->quizzes->count())
                                @foreach($course->quizzes as $quiz)
                                    @include('web.default.course.learningPage.components.quiz_tab.quiz',['item' => $quiz, 'type' => 'quiz','class' => 'px-10 border border-gray200 rounded-sm mb-15'])
                                @endforeach

                            @else
                                <div class="learning-page-forum-empty d-flex align-items-center justify-content-center flex-column">
                                    <div class="learning-page-forum-empty-icon d-flex align-items-center justify-content-center">
                                        <img src="/assets/default/img/learning/quiz-empty.svg" class="img-fluid" alt="">
                                    </div>

                                    <div class="d-flex align-items-center flex-column mt-10 text-center">
                                        <h3 class="font-20 font-weight-bold text-dark-blue text-center">{{ trans('update.learning_page_empty_quiz_title') }}</h3>
                                        <p class="font-14 font-weight-500 text-gray mt-5 text-center">{{ trans('update.learning_page_empty_quiz_hint') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>

                    <div class="pb-20 tab-pane fade  h-100" id="certificates" role="tabpanel"
                         aria-labelledby="certificates-tab">
                        @php
                            $hasCertificateItem=false;
                        @endphp

                        <div class="content-tab p-15 pb-50">
                            @if($course->certificate)
                                @php
                                    $hasCertificateItem = true;
                                @endphp

                                <div class="course-certificate-item cursor-pointer p-10 border border-gray200 rounded-sm mb-15" data-course-certificate="{{ !empty($courseCertificate) ? $courseCertificate->id : '' }}">
                                    <div class="d-flex align-items-center">
                                        <span class="chapter-icon bg-gray300 mr-10">
                                            <i data-feather="award" class="text-gray" width="16" height="16"></i>
                                        </span>

                                        <div class="flex-grow-1">
                                            <span class="font-weight-500 font-14 text-dark-blue d-block">{{ trans('update.course_certificate') }}</span>

                                            <div class="d-flex align-items-center">
                                                @if(!empty($courseCertificate))
                                                    <span class="font-12 text-gray">{{ trans("public.date") }}: {{ dateTimeFormat($courseCertificate->created_at, 'j F Y') }}</span>
                                                @else
                                                    <span class="font-12 text-gray">{{ trans("update.not_achieve") }}</span>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endif

                            @if(!empty($course->quizzes) and count($course->quizzes))
                                @foreach($course->quizzes as $courseQuiz)
                                    @if($courseQuiz->certificate)
                                        @php
                                            $hasCertificateItem = true;
                                        @endphp

                                        <div class="certificate-item cursor-pointer p-10 border border-gray200 rounded-sm mb-15" data-result="{{ $courseQuiz->result ? $courseQuiz->result->id : '' }}">
                                            <div class="d-flex align-items-center">
                                                <span class="chapter-icon bg-gray300 mr-10">
                                                    <i data-feather="award" class="text-gray" width="16" height="16"></i>
                                                </span>

                                                <div class="flex-grow-1">
                                                    <span class="font-weight-500 font-14 text-dark-blue d-block">{{ $courseQuiz->title }}</span>

                                                    <div class="d-flex align-items-center">
                                                        <span class="font-12 text-gray">{{ $courseQuiz->pass_mark }}/{{ $courseQuiz->quizQuestions->sum('grade') }}</span>

                                                        @if(!empty($courseQuiz->result))
                                                            <span class="font-12 text-gray ml-10">{{ dateTimeFormat($courseQuiz->result->created_at, 'j M Y H:i') }}</span>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif

                            @if(!$hasCertificateItem)
                                <div class="learning-page-forum-empty d-flex align-items-center justify-content-center flex-column">
                                    <div class="learning-page-forum-empty-icon d-flex align-items-center justify-content-center">
                                        <img src="/assets/default/img/learning/certificate-empty.svg" class="img-fluid" alt="">
                                    </div>

                                    <div class="d-flex align-items-center flex-column mt-10 text-center">
                                        <h3 class="font-20 font-weight-bold text-dark-blue text-center">{{ trans('update.learning_page_empty_certificate_title') }}</h3>
                                        <p class="font-14 font-weight-500 text-gray mt-5 text-center">{{ trans('update.learning_page_empty_certificate_hint') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/video/video.min.js"></script>
    <script src="/assets/default/vendors/video/youtube.min.js"></script>
    <script src="/assets/default/vendors/video/vimeo.js"></script>

    <script>
        var defaultItemType = '{{ !empty(request()->get('type')) ? request()->get('type') : (!empty($userLearningLastView) ? $userLearningLastView->item_type : '') }}'
        var defaultItemId = '{{ !empty(request()->get('item')) ? request()->get('item') : (!empty($userLearningLastView) ? $userLearningLastView->item_id : '') }}'
        var loadFirstContent = {{ (!empty($dontAllowLoadFirstContent) and $dontAllowLoadFirstContent) ? 'false' : 'true' }}; // allow to load first content when request item is empty

        var appUrl = '{{ url('') }}';
        var courseUrl = '{{ $course->getUrl() }}';
        var courseNotesStatus = '{{ !empty(getFeaturesSettings('course_notes_status')) }}';
        var courseNotesShowAttachment = '{{ !empty(getFeaturesSettings('course_notes_attachment')) }}';

        // lang
        var pleaseWaitForTheContentLang = '{{ trans('update.please_wait_for_the_content_to_load') }}';
        var downloadTheFileLang = '{{ trans('update.download_the_file') }}';
        var downloadLang = '{{ trans('home.download') }}';
        var showHtmlFileLang = '{{ trans('update.show_html_file') }}';
        var showLang = '{{ trans('update.show') }}';
        var sessionIsLiveLang = '{{ trans('update.session_is_live') }}';
        var youCanJoinTheLiveNowLang = '{{ trans('update.you_can_join_the_live_now') }}';
        var passwordLang = '{{ trans('auth.password') }}';
        var joinTheClassLang = '{{ trans('update.join_the_class') }}';
        var coursePageLang = '{{ trans('update.course_page') }}';
        var quizPageLang = '{{ trans('update.quiz_page') }}';
        var sessionIsNotStartedYetLang = '{{ trans('update.session_is_not_started_yet') }}';
        var thisSessionWillBeStartedOnLang = '{{ trans('update.this_session_will_be_started_on') }}';
        var sessionIsFinishedLang = '{{ trans('update.session_is_finished') }}';
        var sessionIsFinishedHintLang = '{{ trans('update.this_session_is_finished_You_cant_join_it') }}';
        var goToTheQuizPageForMoreInformationLang = '{{ trans('update.go_to_the_quiz_page_for_more_information') }}';
        var downloadCertificateLang = '{{ trans('update.download_certificate') }}';
        var enjoySharingYourCertificateWithOthersLang = '{{ trans('update.enjoy_sharing_your_certificate_with_others') }}';
        var attachmentsLang = '{{ trans('public.attachments') }}';
        var checkAgainLang = '{{ trans('update.check_again') }}';
        var learningToggleLangSuccess = '{{ trans('public.course_learning_change_status_success') }}';
        var learningToggleLangError = '{{ trans('public.course_learning_change_status_error') }}';
        var sequenceContentErrorModalTitle = '{{ trans('update.sequence_content_error_modal_title') }}';
        var sendAssignmentSuccessLang = '{{ trans('update.send_assignment_success') }}';
        var saveAssignmentRateSuccessLang = '{{ trans('update.save_assignment_grade_success') }}';
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
        var changesSavedSuccessfullyLang = '{{ trans('update.changes_saved_successfully') }}';
        var oopsLang = '{{ trans('update.oops') }}';
        var somethingWentWrongLang = '{{ trans('update.something_went_wrong') }}';
        var notAccessToastTitleLang = '{{ trans('public.not_access_toast_lang') }}';
        var notAccessToastMsgLang = '{{ trans('public.not_access_toast_msg_lang') }}';
        var cantStartQuizToastTitleLang = '{{ trans('public.request_failed') }}';
        var cantStartQuizToastMsgLang = '{{ trans('quiz.cant_start_quiz') }}';
        var learningPageEmptyContentTitleLang = '{{ trans('update.learning_page_empty_content_title') }}';
        var learningPageEmptyContentHintLang = '{{ trans('update.learning_page_empty_content_hint') }}';
        var expiredQuizLang = '{{ trans('update.expired_quiz') }}';
        var personalNoteLang = '{{ trans('update.personal_note') }}';
        var personalNoteHintLang = '{{ trans('update.this_note_will_be_displayed_for_you_privately') }}';
        var attachmentLang = '{{ trans('update.attachment') }}';
        var saveNoteLang = '{{ trans('update.save_note') }}';
        var clearNoteLang = '{{ trans('update.clear_note') }}';
        var personalNoteStoredSuccessfullyLang = '{{ trans('update.personal_note_stored_successfully') }}';
    </script>
    <script type="text/javascript" src="/assets/default/vendors/dropins/dropins.js"></script>
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>

    <script src="/assets/default/js/parts/video_player_helpers.min.js"></script>
    <script src="/assets/learning_page/scripts.min.js"></script>

    @if((!empty($isForumPage) and $isForumPage) or (!empty($isForumAnswersPage) and $isForumAnswersPage))
        <script src="/assets/learning_page/forum.min.js"></script>
    @endif
@endpush
