@extends('web.default.layouts.app',['appFooter' => false, 'appHeader' => false])
<style>
    :root {
    --k-gold: #F2C94C;
    --k-dark: #0D0D0D;
    --k-card: #1A1A1A;
    --k-border: #333;
    --k-soft: rgba(242, 201, 76, 0.1);
    --k-radius: 16px;
}

/* MAIN PAGE */
.kemetic-learning-page {
    background: var(--k-dark);
    min-height: 100vh;
    max-width: 1160px;
}

/* MAIN CONTENT */
/* .kemetic-learning-content {
    background: var(--k-card);
    padding: 20px;
    flex-grow: 1;
    border-right: 1px solid var(--k-border);
} */

.kemetic-learning-content {
    background: var(--k-card);
    padding: 20px;

    /* STOP flex auto-expanding */
    flex: 0 0 720px; /* static width */

    /* Optional: max width control */
    /* max-width: 720px; */

    border-right: 1px solid var(--k-border);
    overflow-y: auto; /* keep scroll inside if content is long */
}


/* RIGHT TAB AREA */
.kemetic-learning-tabs {
    width: 320px;
    background: var(--k-card);
    border-left: 1px solid var(--k-border);
    display: flex;
    flex-direction: column;
}

/* TABS HEADER STYLING */
.kemetic-tabs-nav {
    background: var(--k-dark);
    border-bottom: 1px solid var(--k-border);
    padding: 0;
    margin: 0;
    display: flex;
    justify-content: space-around;
}

.kemetic-tabs-nav .nav-item {
    flex: 1;
}

/* TAB BUTTON */
.kemetic-tab-link {
    color: #aaa;
    font-size: 14px;
    padding: 14px 5px;
    text-align: center;
    border-bottom: 2px solid transparent;
    transition: 0.25s ease;
    display: flex;
    justify-content: center;
    align-items: center;
}

.kemetic-tab-link:hover {
    color: var(--k-gold);
}

.kemetic-tab-link.active {
    color: var(--k-gold);
    font-weight: 600;
    border-bottom: 2px solid var(--k-gold);
    background: var(--k-soft);
}

/* TAB ICONS */
.kemetic-tab-icon svg {
    width: 18px;
    height: 18px;
    stroke: var(--k-gold);
}

/* TAB CONTENT AREA */
.kemetic-tab-container {
    flex-grow: 1;
    overflow-y: auto;
    padding: 15px;
}

.kemetic-tab-pane {
    color: #ddd;
}

/* MOBILE RESPONSIVE */
@media (max-width: 992px) {
    .kemetic-learning-tabs {
        width: 100%;
        border-left: none;
        border-top: 1px solid var(--k-border);
    }

    .kemetic-tabs-nav {
        justify-content: space-between;
    }

    .kemetic-learning-content {
        border-right: none;
    }
}

/* Main note container */
.kemetic-note-box {
    background: #1A1A1A;
    border: 1px solid #2D2D2D;
    border-radius: 12px;
    padding: 18px 20px;
    margin-top: 20px;
    color: #F2C94C;
}

/* Title */
.kemetic-note-box .note-title {
    font-size: 16px;
    font-weight: 600;
    margin: 0;
    padding: 0;
    color: #F2C94C;
}

/* Subtitle */
.kemetic-note-box .note-subtitle {
    font-size: 12px;
    margin-top: 4px !important;
    margin-bottom: 10px !important;
    color: #bbbbbb;
}

/* Textarea */
.kemetic-note-box .note-textarea {
    width: 100%;
    background: #111;
    color: #F2C94C;
    border: 1px solid #333;
    border-radius: 8px;
    padding: 10px;
    font-size: 14px;
    resize: vertical;
}

/* Attachment box */
.kemetic-note-box .note-attachment {
    margin-top: 15px;
}

.kemetic-note-box .note-label {
    font-size: 13px;
    color: #F2C94C;
    margin-bottom: 5px;
    display: block;
}

.kemetic-note-box .note-attach-box {
    display: flex;
    align-items: center;
    background: #111;
    border: 1px solid #333;
    padding: 6px 10px;
    border-radius: 8px;
}

.kemetic-note-box .attach-btn {
    background: none;
    border: none;
    padding: 0;
    margin-right: 8px;
    cursor: pointer;
}

.kemetic-note-box .attach-btn i {
    color: #F2C94C !important;
}

.kemetic-note-box .attach-input {
    flex-grow: 1;
    background: transparent;
    border: none;
    color: #F2C94C;
    font-size: 13px;
    outline: none;
}

/* Buttons row */
.kemetic-note-box .note-actions {
    margin-top: 18px;
    display: flex;
    gap: 10px;
}

/* Save Button */
.kemetic-note-box .note-btn-save {
    background: #F2C94C;
    color: #000;
    padding: 8px 16px;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
}

/* Clear Button */
.kemetic-note-box .note-btn-clear {
    background: #222;
    color: #F2C94C;
    padding: 8px 16px;
    border-radius: 8px;
    border: 1px solid #444;
    font-weight: 600;
    cursor: pointer;
}

.kemetic-note-box .note-btn-save:hover {
    opacity: 0.9;
}

.kemetic-note-box .note-btn-clear:hover {
    border-color: #F2C94C;
}

.kemetic-download-box {
    background: #111;
    border: 1px dashed rgba(255, 215, 0, 0.35);
    padding: 15px;
    border-radius: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.kemetic-download-box .file-title {
    font-size: 16px;
    font-weight: 600;
    color: #F2C94C;
}

.kemetic-download-btn {
    background: linear-gradient(135deg, #F2C94C, #b9912c);
    color: #111;
    padding: 6px 16px;
    font-size: 14px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: 0.25s ease;
    border: none;
}

.kemetic-download-btn:hover {
    background: linear-gradient(135deg, #ffe382, #e1b24c);
    transform: translateY(-2px);
    box-shadow: 0 3px 8px rgba(242, 201, 76, 0.35);
}

/* Container */
.kemetic-text-lesson {
    background: #323030;
    border: 1px solid rgba(242, 201, 76, 0.22);
    border-radius: 18px;
    color: #e8e8e8;
    box-shadow: 0 4px 12px rgba(0,0,0,0.35);
    padding: 20px 22px;
}
/* .kemetic-text-lesson * {
    color: #ffffff !important;
} */

/* .kemetic-text-lesson {
    color: #fff;
}

.kemetic-text-lesson [style*="color"] {
    color: inherit !important;
} */

/* Title */
.kemetic-title {
    font-size: 18px;
    font-weight: 700;
    color: #F2C94C;
    margin-bottom: 10px;
}

/* Image */
.kemetic-main-image {
    border-radius: 14px;
    overflow: hidden;
    background: #222;
    border: 1px solid rgba(242, 201, 76, 0.18);
}

.kemetic-main-image img {
    width: 100%;
    display: block;
}

/* Content */
.kemetic-content {
    margin-top: 18px;
    line-height: 1.7;
    font-size: 15px;
    color: #dcdcdc;
}

.kemetic-content p {
    color: #dcdcdc;
}
/* Attachment container */
.kemetic-attachment-box {
    background: #0E0E0E;
    border: 1px solid rgba(242, 201, 76, 0.25);
    border-radius: 18px;
    padding: 20px 18px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.35);
}

/* Section title */
.kemetic-section-title {
    font-size: 16px;
    font-weight: 700;
    color: #F2C94C; /* Kemetic Gold */
    margin-bottom: 10px;
}
/* Attachment item */
.kemetic-attachment-item {
    display: flex;
    align-items: center;
    padding: 12px;
    background: #111; /* pure dark */
    border: 1px solid rgba(242, 201, 76, 0.25); /* soft gold border */
    border-radius: 14px;
    text-decoration: none;
    transition: 0.25s ease;
    box-shadow: 0 4px 10px rgba(0,0,0,0.35);
}

.kemetic-attachment-item:hover {
    border-color: #F2C94C;
    box-shadow: 0 0 10px rgba(242,201,76,0.35);
}

/* Icon container */
.kemetic-attachment-icon {
    width: 40px;
    height: 40px;
    background: rgba(242, 201, 76, 0.15);
    border: 1px solid rgba(242,201,76,0.35);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
}

.kemetic-attachment-icon i {
    color: #F2C94C;
}

/* Title */
.kemetic-attachment-title {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #F2C94C;
}

/* Meta text */
.kemetic-attachment-meta {
    display: block;
    font-size: 12px;
    color: #888;
}

/* Kemetic Certificate Download Button */
.kemetic-certificate-btn {
    display: inline-block;
    padding: 10px 18px;
    margin-top: 15px;
    background: #F2C94C;        /* gold */
    color: #1A1A1A;             /* dark text */
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: 0.25s ease;
    box-shadow: 0 4px 10px rgba(242, 201, 76, 0.3);
}

.kemetic-certificate-btn:hover {
    background: #FFD84F;
    box-shadow: 0 0 12px rgba(242, 201, 76, 0.45);
    transform: translateY(-2px);
}
/* Kemetic Disabled Button */
.kemetic-disabled-btn {
    display: inline-block;
    padding: 10px 18px;
    margin-top: 15px;
    background: #3A3A3A;         /* Dark gray disabled background */
    color: #8D8D8D;              /* Light gray text */
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    border: 1px solid #555;
    opacity: 0.6;
    cursor: not-allowed;
    pointer-events: none;
}

/* Active Show Button – Kemetic Style */
.kemetic-show-btn {
    display: inline-block;
    padding: 10px 20px;
    margin-top: 15px;
    background: #F2C94C;       /* Kemetic Gold */
    color: #1C1C1C;            /* Dark text */
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    transition: 0.3s ease;
}

.kemetic-show-btn:hover {
    background: #FFD84D;
    transform: translateY(-1px);
}


/* Disabled Show Button */
.kemetic-show-btn-disabled {
    display: inline-block;
    padding: 10px 20px;
    margin-top: 15px;
    background: #333;          /* Dark disabled background */
    color: #888;               /* Soft gray text */
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    border: 1px solid #555;
    opacity: 0.6;
    cursor: not-allowed;
}
/* Expire Time Message */
.kemetic-expire-msg {
    margin-top: 10px;
    padding: 12px 15px;
    font-size: 14px;
    color: #F2C94C;            /* Kemetic Gold */
    background: #1A1A1A;       /* Dark background */
    border: 1px dashed #F2C94C;
    border-radius: 10px;
    line-height: 1.5;
}
/* Kemetic Outline Button */
.kemetic-btn-outline {
    display: inline-block;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 600;
    color: #F2C94C;                  /* Gold Text */
    background: transparent;
    border: 1px solid #F2C94C;       /* Gold Border */
    border-radius: 8px;
    transition: 0.3s ease;
    text-decoration: none;
}

.kemetic-btn-outline:hover {
    background: #F2C94C;             /* Filled Gold */
    color: #1A1A1A;                  /* Dark Text */
}
/* Flex wrapper */
.kemetic-flex {
    display: flex;
    align-items: center;
}

/* Gold Filled Button */
.kemetic-btn-gold {
    background: #F2C94C;
    color: #1A1A1A;
    border: none;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s ease;
}

.kemetic-btn-gold:hover {
    background: #e0b746;
}

/* Gold Outline Button */
.kemetic-btn-outline {
    display: inline-block;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 600;
    color: #F2C94C;
    background: transparent;
    border: 1px solid #F2C94C;
    border-radius: 8px;
    text-decoration: none;
    transition: 0.3s ease;
}

.kemetic-btn-outline:hover {
    background: #F2C94C;
    color: #1A1A1A;
}

/* Flex wrapper */
.kemetic-flex {
    display: flex;
    align-items: center;
}

/* Gold Filled Button */
.kemetic-btn-gold {
    background: #F2C94C;
    color: #1A1A1A;
    border: none;
    padding: 8px 18px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 8px;
    cursor: pointer;
    text-decoration: none;
    transition: 0.3s ease;
}
.kemetic-btn-gold:hover {
    background: #e0b746;
}

/* Gold Outline Button */
.kemetic-btn-outline {
    display: inline-block;
    padding: 8px 18px;
    font-size: 14px;
    font-weight: 600;
    color: #F2C94C;
    background: transparent;
    border: 1px solid #F2C94C;
    border-radius: 8px;
    text-decoration: none;
    transition: 0.3s ease;
}
.kemetic-btn-outline:hover {
    background: #F2C94C;
    color: #1A1A1A;
}

/* Password Text */
.kemetic-info-text {
    font-size: 14px;
    font-weight: 500;
    color: #C5C5C5;
}
/* Viewer Wrapper */
.kemetic-file-viewer-wrapper {
    padding: 12px;
    background: #111;              /* Kemetic dark background */
    border: 1px solid #2b2b2b;
    border-radius: 12px;
    height: 100%;
    display: flex;
    flex-direction: column;
}

/* Viewer iframe */
/* .kemetic-file-iframe {
    width: 100%;
    height: 75vh;
    background: #000;
    border-radius: 12px;
    border: 1px solid #333;
} */

    .kemetic-file-iframe {
    width: 100%;
    height: 70vh;
    border: none;
    z-index: 1;
    pointer-events: auto;
}
/* If file has download card */
.kemetic-file-iframe.has-download-card {
    margin-bottom: 15px;
}
/* Video wrapper */
.kemetic-video-wrapper {
    padding: 12px;
    height: 100%;
    background: #111;
    border: 1px solid #222;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
}

/* Download box */
/* .kemetic-download-box {
    margin-top: 18px;
    padding: 15px 18px;
    border: 1px dashed #444;
    background: #1a1a1a;
    border-radius: 12px;

    display: flex;
    align-items: center;
    justify-content: space-between;
} */

    .kemetic-download-box {
    position: relative;
    z-index: 2;
}

/* Title */
.kemetic-download-box .download-title {
    font-size: 14px;
    font-weight: 600;
    color: #F2C94C; /* Gold */
}

/* Download button */
.kemetic-download-btn {
    background: #F2C94C;
    color: #1C1C1C;
    font-size: 12px;
    padding: 7px 14px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
}

.kemetic-download-btn:hover {
    background: #e5b73e;
    color: #000;
}
.kemetic-download-btn {
    display: inline-block;
    background: linear-gradient(90deg, #F2C94C, #d5a738);
    color: #000 !important;
    font-weight: 600;
    padding: 10px 18px;
    margin-top: 15px;
    border-radius: 10px;
    text-align: center;
    transition: 0.3s ease;
}

.kemetic-download-btn:hover {
    background: #000;
    color: #F2C94C !important;
    box-shadow: 0 0 12px rgba(242, 201, 76, 0.4);
}


.kemetic-video-wrapper {
    background: #0f0f0f; /* Deep black */
    border: 1px solid rgba(242, 201, 76, 0.25); /* Soft gold edge */
    border-radius: 16px;
    padding: 12px;
    box-shadow: 0 0 18px rgba(242, 201, 76, 0.15);
    position: relative;
}

.kemetic-video-wrapper .learning-content-video-player {
    width: 70%;
    height: 320px; /* Adjust as needed */
    border-radius: 14px;
    overflow: hidden;
    background: #000;
}

.kemetic-video-wrapper {
    background: #0f0f0f; /* Deep black */
    border: 1px solid rgba(242, 201, 76, 0.25); /* Soft gold edge */
    border-radius: 16px;
    padding: 12px;
    box-shadow: 0 0 18px rgba(242, 201, 76, 0.15);
    position: relative;
}

.kemetic-video-wrapper .learning-content-video-player {
    width: 70%;
    height: 320px; /* Adjust if needed */
    border-radius: 14px;
    overflow: hidden;
    background: #000;
}
.kemetic-btn {
    display: inline-block;
    padding: 8px 18px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 12px;
    text-decoration: none;
    transition: 0.25s ease;
}

.kemetic-btn-gold {
    background: linear-gradient(90deg, #C89B3C, #F2C94C);
    color: #000;
    border: none;
    box-shadow: 0 0 12px rgba(242, 201, 76, 0.35);
}

.kemetic-btn-gold:hover {
    box-shadow: 0 0 20px rgba(242, 201, 76, 0.55);
    transform: translateY(-2px);
}
/* Empty State Box */
.kemetic-empty-box {
    background: #1A1A1A;
    border: 1px solid rgba(242, 201, 76, 0.25);
    padding: 30px;
    border-radius: 18px;
    box-shadow: 0 0 18px rgba(0,0,0,0.35);
}

/* Icon Circle */
.kemetic-empty-icon {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    background: rgba(242, 201, 76, 0.1);
    backdrop-filter: blur(6px);
    box-shadow: 0 0 12px rgba(242, 201, 76, 0.2);
}

/* Title */
.kemetic-empty-title {
    font-size: 20px;
    font-weight: 700;
    color: #F2C94C;
}

/* Hint Text */
.kemetic-empty-hint {
    font-size: 14px;
    font-weight: 500;
    color: #C9C9C9;
    max-width: 320px;
}
/* Kemetic iframe wrapper */
.kemetic-iframe-box {
    background: #111;
    border: 1px solid rgba(242, 201, 76, 0.25);
    border-radius: 18px;
    padding: 10px;
    box-shadow: 0 0 18px rgba(0,0,0,0.35);
    overflow: hidden;
}

/* Make iframe beautifully fit */
.kemetic-iframe-box iframe {
    width: 100%;
    height: 100%;
    min-height: 480px;
    border-radius: 14px;
    border: none;
    background: #000;
}


</style>
@push('styles_top')
    <link rel="stylesheet" href="/assets/default/learning_page/styles.css"/>
    <link rel="stylesheet" href="/assets/default/vendors/video/video-js.min.css">
@endpush

@section('content')

    <div class="kemetic-learning-page">

        {{-- Navbar --}}
        @include('web.default.course.learningPage.components.navbar')

        <div class="d-flex position-relative">

            <div class="kemetic-learning-content flex-grow-1">
                @include('web.default.course.learningPage.components.content')
            </div>

            {{-- RIGHT SIDE TABS --}}
            <div class="kemetic-learning-tabs show">

                <ul class="nav kemetic-tabs-nav" id="tabs-tab" role="tablist">

                    {{-- CONTENT TAB --}}
                    <li class="nav-item">
                        <a class="kemetic-tab-link active" id="content-tab" data-toggle="tab"
                        href="#content" role="tab" aria-controls="content" aria-selected="true">

                            <i class="kemetic-tab-icon mr-5">
                                @include('web.default.panel.includes.sidebar_icons.webinars')
                            </i>

                            <span> {{ trans('product.content') }} </span>
                        </a>
                    </li>

                    {{-- QUIZZES TAB --}}
                    <li class="nav-item">
                        <a class="kemetic-tab-link" id="quizzes-tab" data-toggle="tab"
                        href="#quizzes" role="tab" aria-controls="quizzes" aria-selected="false">

                            <i class="kemetic-tab-icon mr-5">
                                @include('web.default.panel.includes.sidebar_icons.quizzes')
                            </i>

                            <span>{{ trans('quiz.quizzes') }}</span>
                        </a>
                    </li>

                    {{-- CERTIFICATES TAB --}}
                    <li class="nav-item">
                        <a class="kemetic-tab-link" id="certificates-tab" data-toggle="tab"
                        href="#certificates" role="tab" aria-controls="certificates" aria-selected="false">

                            <i class="kemetic-tab-icon mr-5">
                                @include('web.default.panel.includes.sidebar_icons.certificate')
                            </i>

                            <span>{{ trans('panel.certificates') }}</span>
                        </a>
                    </li>

                </ul>

                <div class="tab-content h-100 kemetic-tab-container" id="nav-tabContent">

                    {{-- CONTENT PANEL --}}
                    <div class="kemetic-tab-pane tab-pane fade show active h-100" id="content"
                        role="tabpanel" aria-labelledby="content-tab">
                        @include('web.default.course.learningPage.components.content_tab.index')
                    </div>

                    {{-- QUIZ PANEL --}}
                    <div class="kemetic-tab-pane tab-pane fade h-100" id="quizzes"
                        role="tabpanel" aria-labelledby="quizzes-tab">
                        @include('web.default.course.learningPage.components.quiz_tab.index')
                    </div>

                    {{-- CERTIFICATE PANEL --}}
                    <div class="kemetic-tab-pane tab-pane fade h-100" id="certificates"
                        role="tabpanel" aria-labelledby="certificates-tab">
                        @include('web.default.course.learningPage.components.certificate_tab.index')
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
