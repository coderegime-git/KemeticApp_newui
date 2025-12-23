<style>

    /* WRAPPER */
    .kemetic-content-tab {
        background: var(--k-card);
    }

    /* EMPTY STATE */
    .kemetic-empty-state {
        padding: 40px 20px;
    }

    .kemetic-empty-icon img {
        width: 120px;
        opacity: 0.8;
    }

    .kemetic-empty-title {
        color: var(--k-gold);
        font-size: 20px;
        font-weight: 700;
    }

    .kemetic-empty-subtitle {
        color: #aaa;
        font-size: 14px;
    }

    /* SECTION HEADINGS */
    .kemetic-section-heading {
        color: var(--k-gold);
        font-weight: 600;
        font-size: 16px;
        margin: 10px 0 10px 5px;
        letter-spacing: 0.5px;
    }

    /* CONTENT CARDS */
    .kemetic-list-card {
        background: #121212;
        border: 1px solid var(--k-border);
        border-radius: var(--k-radius);
        padding: 15px 18px;
        margin-bottom: 10px;
        transition: 0.25s;
    }

    .kemetic-list-card:hover {
        background: rgba(242, 201, 76, 0.08);
        border-color: var(--k-gold);
    }

    /* CHAPTER WRAPPER */
    .kemetic-chapter-wrapper {
        background: #101010;
        border: 1px solid var(--k-border);
        border-radius: var(--k-radius);
        padding: 10px 15px;
        margin-top: 10px;
    }

    /* GOLD ICON COLORING FOR SVGs */
    .kemetic-list-card svg,
    .kemetic-chapter-wrapper svg {
        stroke: var(--k-gold) !important;
    }

</style>

<div class="kemetic-content-tab p-20 pb-50">

    @if(
        (empty($sessionsWithoutChapter) or !count($sessionsWithoutChapter)) and
        (empty($textLessonsWithoutChapter) or !count($textLessonsWithoutChapter)) and
        (empty($filesWithoutChapter) or !count($filesWithoutChapter)) and
        (empty($course->chapters) or !count($course->chapters))
    )

        <div class="kemetic-empty-state d-flex align-items-center justify-content-center flex-column">

            <div class="kemetic-empty-icon d-flex align-items-center justify-content-center">
                <img src="/assets/default/img/learning/content-empty.svg" class="img-fluid" alt="empty">
            </div>

            <div class="kemetic-empty-text mt-15 text-center">
                <h3 class="kemetic-empty-title">
                    {{ trans('update.learning_page_empty_content_title') }}
                </h3>

                <p class="kemetic-empty-subtitle mt-5">
                    {{ trans('update.learning_page_empty_content_hint') }}
                </p>
            </div>
        </div>

    @else
    
        @if(!empty($sessionsWithoutChapter) and count($sessionsWithoutChapter))
            <div class="kemetic-section-heading">Sessions</div>
            @foreach($sessionsWithoutChapter as $session)
                <div class="kemetic-list-card">
                    @include('web.default.course.learningPage.components.content_tab.content',
                        ['item' => $session, 'type' => \App\Models\WebinarChapter::$chapterSession])
                </div>
            @endforeach
        @endif

        @if(!empty($textLessonsWithoutChapter) and count($textLessonsWithoutChapter))
            <div class="kemetic-section-heading mt-25">Lessons</div>
            @foreach($textLessonsWithoutChapter as $textLesson)
                <div class="kemetic-list-card">
                    @include('web.default.course.learningPage.components.content_tab.content',
                        ['item' => $textLesson, 'type' => \App\Models\WebinarChapter::$chapterTextLesson])
                </div>
            @endforeach
        @endif

        @if(!empty($filesWithoutChapter) and count($filesWithoutChapter))
            <div class="kemetic-section-heading mt-25">Files</div>
            @foreach($filesWithoutChapter as $file)
                <div class="kemetic-list-card">
                    @include('web.default.course.learningPage.components.content_tab.content',
                        ['item' => $file, 'type' => \App\Models\WebinarChapter::$chapterFile])
                </div>
            @endforeach
        @endif
        
        @if(!empty($course->chapters) and count($course->chapters))
            <div class="kemetic-section-heading mt-25">Chapters</div>

            <div class="kemetic-chapter-wrapper">
                @include('web.default.course.learningPage.components.content_tab.chapter')
            </div>
        @endif

    @endif

</div>

