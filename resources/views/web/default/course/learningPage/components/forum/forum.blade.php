<style>
    /* --- KEMETIC APP THEME COLORS --- */
    :root {
        --kemetic-black: #0D0D0D;
        --kemetic-dark: #1A1A1A;
        --kemetic-gold: #D4AF37;
        --kemetic-gold-soft: rgba(212, 175, 55, 0.15);
        --kemetic-text-light: #EAEAEA;
        --kemetic-gray: #888;
        --kemetic-card-radius: 18px;
        --kemetic-shadow: 0 6px 20px rgba(0,0,0,0.45);
    }

    .kemetic-card {
        background: var(--kemetic-dark);
        border-radius: var(--kemetic-card-radius);
        border: 1px solid var(--kemetic-gold-soft);
        padding: 18px;
        margin: 15px;
        box-shadow: var(--kemetic-shadow);
    }

    .kemetic-stat-icon img {
        width: 55px;
        height: 55px;
        opacity: 0.85;
    }

    .kemetic-label {
        color: var(--kemetic-gray);
        font-size: 14px;
    }

    .kemetic-value {
        color: var(--kemetic-gold);
        font-size: 22px;
        font-weight: 700;
    }

    .kemetic-heading {
        color: var(--kemetic-gold);
        font-weight: 700;
    }

    .kemetic-text {
        color: var(--kemetic-text-light);
        font-weight: 500;
    }

    .kemetic-btn {
        background: var(--kemetic-gold);
        border: none;
        color: #000;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 12px;
    }

    .kemetic-btn i {
        color: #000 !important;
    }

    .kemetic-search-box input {
        background: #111;
        border: 1px solid var(--kemetic-gold-soft);
        color: var(--kemetic-text-light);
        border-radius: 12px;
    }

    .kemetic-border-left {
        border-left: 1px solid var(--kemetic-gold-soft);
    }

    .kemetic-pin-btn img {
        width: 30px;
        opacity: .8;
        cursor: pointer;
    }

    .kemetic-avatar img {
        border: 2px solid var(--kemetic-gold-soft);
    }
</style>


<section class="kemetic-card">
    <div class="d-flex flex-wrap align-items-center justify-content-around">

        {{-- Stats Loop --}}
        <div class="text-center p-10">
            <div class="kemetic-stat-icon">
                <img src="/assets/default/img/activity/47.svg" alt="">
            </div>
            <div class="kemetic-value mt-2">{{ $questionsCount }}</div>
            <div class="kemetic-label">{{ trans('public.questions') }}</div>
        </div>

        <div class="text-center p-10">
            <div class="kemetic-stat-icon">
                <img src="/assets/default/img/activity/120.svg" alt="">
            </div>
            <div class="kemetic-value mt-2">{{ $resolvedCount }}</div>
            <div class="kemetic-label">{{ trans('update.resolved') }}</div>
        </div>

        <div class="text-center p-10">
            <div class="kemetic-stat-icon">
                <img src="/assets/default/img/activity/119.svg" alt="">
            </div>
            <div class="kemetic-value mt-2">{{ $openQuestionsCount }}</div>
            <div class="kemetic-label">{{ trans('update.open_questions') }}</div>
        </div>

        <div class="text-center p-10">
            <div class="kemetic-stat-icon">
                <img src="/assets/default/img/activity/39.svg" alt="">
            </div>
            <div class="kemetic-value mt-2">{{ $commentsCount }}</div>
            <div class="kemetic-label">{{ trans('update.answers') }}</div>
        </div>

        <div class="text-center p-10">
            <div class="kemetic-stat-icon">
                <img src="/assets/default/img/activity/49.svg" alt="">
            </div>
            <div class="kemetic-value mt-2">{{ $activeUsersCount }}</div>
            <div class="kemetic-label">{{ trans('update.active_users') }}</div>
        </div>

    </div>

    <div class="kemetic-card mt-20 bg-black">
        <div class="row align-items-center">

            <div class="col-12 col-lg-4">
                <h3 class="kemetic-heading">{{ trans('update.course_forum') }}</h3>
                <span class="kemetic-text">{{ trans('update.communicate_others_and_ask_your_questions') }}</span>
            </div>

            <div class="col-12 col-lg-5 mt-3 mt-lg-0">
                <form action="{{ request()->url() }}" method="get">
                    <div class="d-flex kemetic-search-box">
                        <input type="text" name="search"
                            value="{{ request()->get('search') }}"
                            class="form-control"
                            placeholder="{{ trans('update.search_in_this_forum') }}">

                        <button class="kemetic-btn ml-10">
                            <i data-feather="search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-12 col-lg-3 mt-3 mt-lg-0 text-right">
                <button id="askNewQuestion" class="kemetic-btn">
                    <i data-feather="file"></i>
                    <span class="ml-1">{{ trans('update.ask_new_question') }}</span>
                </button>
            </div>
        </div>
    </div>
</section>


{{-- Forum Items --}}
@if($forums && count($forums))
    @foreach($forums as $forum)

        <div class="kemetic-card">
            <div class="row">

                {{-- LEFT COLUMN --}}
                <div class="col-12 col-lg-6">
                    <div class="d-flex">

                        <div class="kemetic-avatar">
                            <img src="{{ $forum->user->getAvatar(64) }}" class="rounded-circle" />
                        </div>

                        <div class="ml-10">
                            <a href="{{ $course->getForumPageUrl() }}/{{ $forum->id }}/answers">
                                <h4 class="kemetic-heading">{{ $forum->title }}</h4>
                            </a>

                            <span class="kemetic-label d-block mt-5">
                                {{ trans('public.by') }} {{ $forum->user->full_name }}
                                {{ trans('public.in') }} {{ dateTimeFormat($forum->created_at,'j M Y | H:i') }}
                            </span>

                            <p class="kemetic-text mt-10">{{ $forum->description }}</p>
                        </div>

                    </div>
                </div>

                {{-- RIGHT COLUMN --}}
                <div class="col-12 col-lg-6 mt-15 mt-lg-0 kemetic-border-left">

                    {{-- Pin Button --}}
                    @if($course->isOwner($user->id))
                        <button class="kemetic-pin-btn"
                            data-action="{{ $course->getForumPageUrl() }}/{{ $forum->id }}/pinToggle">
                            <img src="/assets/default/img/learning/{{ $forum->pin ? 'un_pin' : 'pin' }}.svg" />
                        </button>
                    @endif


                    {{-- Replies --}}
                    @if(!empty($forum->answers) && count($forum->answers))

                        <div class="py-15 row">

                            <div class="col-3">
                                <span class="kemetic-label">{{ trans('public.answers') }}</span>
                                <span class="kemetic-text mt-10 d-block">{{ $forum->answer_count }}</span>
                            </div>

                            <div class="col-3">
                                <span class="kemetic-label">{{ trans('panel.users') }}</span>

                                <div class="d-flex mt-2">
                                    @foreach($forum->usersAvatars as $avatar)
                                        <img src="{{ $avatar->getAvatar(32) }}"
                                            class="rounded-circle mr-1"
                                            style="border:1px solid var(--kemetic-gold-soft);" />
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-6">
                                <span class="kemetic-label">{{ trans('update.last_activity') }}</span>
                                <span class="kemetic-text mt-10 d-block">
                                    {{ dateTimeFormat($forum->lastAnswer->created_at,'j M Y | H:i') }}
                                </span>
                            </div>

                        </div>

                        <div class="py-15 border-top">
                            <span class="kemetic-label">{{ trans('update.last_answer') }}</span>

                            <div class="d-flex mt-20">
                                <img src="{{ $forum->lastAnswer->user->getAvatar(30) }}"
                                    class="rounded-circle" />

                                <div class="ml-10">
                                    <h4 class="kemetic-heading">{{ $forum->lastAnswer->user->full_name }}</h4>
                                    <p class="kemetic-text mt-5">
                                        {!! truncate($forum->lastAnswer->description, 160) !!}
                                    </p>
                                </div>
                            </div>

                            @if(!empty($forum->resolved))
                                <div class="kemetic-text mt-10">
                                    ✔ {{ trans('update.resolved') }}
                                </div>
                            @endif
                        </div>

                    @else
                        <div class="text-center py-20">
                            <p class="kemetic-text font-weight-bold">
                                {{ trans('update.be_the_first_to_answer_this_question') }}
                            </p>

                            <a href="{{ $course->getForumPageUrl() }}/{{ $forum->id }}/answers"
                                class="kemetic-btn mt-15">
                                {{ trans('public.answer') }}
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    @endforeach
@else

    <div class="kemetic-card text-center py-40">
        <img src="/assets/default/img/learning/forum-empty.svg" class="img-fluid" width="140" />
        <h3 class="kemetic-heading mt-15">{{ trans('update.learning_page_empty_content_title_hint') }}</h3>
    </div>

@endif


@include('web.default.course.learningPage.components.forum.ask_question_modal')

