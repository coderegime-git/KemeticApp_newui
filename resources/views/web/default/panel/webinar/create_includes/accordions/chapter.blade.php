<style>
    /* ROOT COLORS */
:root {
    --kemetic-bg: #141414;
    --kemetic-panel: #1b1b1b;
    --kemetic-gold: #d4af37;
    --kemetic-gold-soft: rgba(212,175,55,0.25);
    --kemetic-border: #2e2e2e;
    --kemetic-text: #f5f5f5;
}

/* WRAPPER */
.kemetic-chapter-wrapper {
    margin-top: 20px;
}

/* LIST */
.kemetic-chapter-item {
    background: var(--kemetic-panel);
    border: 1px solid var(--kemetic-border);
    border-radius: 14px;
    padding: 0;
    margin-bottom: 15px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.35);
    transition: 0.25s ease;
}

.kemetic-chapter-item:hover {
    border-color: var(--kemetic-gold);
}

/* HEADER */
.kemetic-chapter-header {
    padding: 18px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
}

.kemetic-icon-box {
    width: 38px;
    height: 38px;
    background: var(--kemetic-bg);
    border: 1px solid var(--kemetic-border);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.chapter-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--kemetic-text);
}

.chapter-meta {
    font-size: 12px;
    color: #aaa;
}

/* RIGHT BUTTONS */
.kemetic-btn-icon {
    background: transparent;
    border: none;
    color: #bcbcbc;
    padding: 6px;
    margin-left: 8px;
    cursor: pointer;
    transition: 0.2s ease;
}

.kemetic-btn-icon:hover {
    color: var(--kemetic-gold);
}

/* DISABLED TAG */
.disabled-badge {
    background: #5a0000;
    padding: 3px 10px;
    font-size: 11px;
    border-radius: 6px;
    color: white;
    margin-right: 10px;
}

/* DROPDOWN */
.kemetic-dropdown-menu {
    background: #111;
    border: 1px solid #333;
    border-radius: 10px;
    min-width: 180px;
    padding: 10px;
}

.kemetic-dropdown-menu .dropdown-item {
    color: #ddd !important;
    padding: 8px 12px;
    border-radius: 6px;
}

.kemetic-dropdown-menu .dropdown-item:hover {
    background: var(--kemetic-gold-soft);
    color: var(--kemetic-gold) !important;
}

/* COLLAPSE CONTENT */
.kemetic-chapter-content {
    padding: 12px 20px 20px;
}

/* DRAG ICON */
.kemetic-drag-icon {
    color: #777;
    margin-left: 12px;
    cursor: move;
}

/* CHEVRON */
.kemetic-chevron {
    margin-left: 12px;
    color: #888;
}

/* ITEMS LIST */
.kemetic-items-list {
    margin-top: 15px;
}

/* ===============================
   KEMETIC CHAPTER CONTENT
================================ */

.kemetic-chapter-panel {
    background: linear-gradient(145deg,#0b0b0b,#141414);
    border: 1px solid rgba(242,201,76,.18);
    border-radius: 18px;
    padding: 20px;
}

/* Inner content wrapper */
.kemetic-chapter-content {
    background: rgba(0,0,0,.35);
    border: 1px dashed rgba(242,201,76,.2);
    border-radius: 14px;
    padding: 16px;
}

/* Draggable list */
.kemetic-draggable-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

/* Each accordion item (generic fallback) */
.kemetic-draggable-list > li {
    background: linear-gradient(145deg,#0e0e0e,#1a1a1a);
    border: 1px solid rgba(242,201,76,.22);
    border-radius: 14px;
    margin-bottom: 14px;
    transition: all .25s ease;
}

/* Hover effect */
.kemetic-draggable-list > li:hover {
    transform: translateY(-1px);
    box-shadow: 0 10px 28px rgba(0,0,0,.6);
}

/* Dragging */
.kemetic-draggable-list > li.ui-sortable-helper {
    box-shadow: 0 18px 40px rgba(242,201,76,.25);
    opacity: .95;
}

/* Empty state wrapper */
.kemetic-empty-state {
    background: linear-gradient(145deg,#0b0b0b,#121212);
    border: 1px dashed rgba(242,201,76,.25);
    border-radius: 14px;
    padding: 30px 20px;
    text-align: center;
}

/* Reduce default gray */
.panel-collapse.text-gray {
    color: #bdbdbd;
}

</style>

<div class="kemetic-chapter-wrapper mt-10">
    <ul class="kemetic-chapter-list" data-drag-class="draggable-lists-chapter" data-order-table="webinar_chapters">
        @if(!empty($webinar->chapters) and count($webinar->chapters))
        @foreach($webinar->chapters as $chapter)
        <li class="kemetic-chapter-item" data-id="{{ $chapter->id }}" data-chapter-order="{{ $chapter->order }}">

            <!-- HEADER -->
            <div class="kemetic-chapter-header" data-toggle="collapse" role="button"
                 data-target="#chapterCollapse{{ $chapter->id }}" aria-expanded="true">

                <div class="header-left d-flex align-items-center">
                    <div class="kemetic-icon-box">
                        <i data-feather="grid"></i>
                    </div>

                    <div class="kemetic-chapter-info">
                        <span class="chapter-title">{{ $chapter->title }}</span>
                        <span class="chapter-meta">
                            {{ count($chapter->chapterItems) }} Topics •
                            {{ convertMinutesToHourAndMinute($chapter->getDuration()) }} hr
                        </span>
                    </div>
                </div>

                <div class="header-right d-flex align-items-center">

                    @if($chapter->status != \App\Models\WebinarChapter::$chapterActive)
                        <span class="disabled-badge">Disabled</span>
                    @endif

                    <!-- + ADD CONTENT DROPDOWN -->
                    <div class="kemetic-dropdown">
                        <button class="kemetic-btn-icon" data-toggle="dropdown">
                            <i data-feather="plus"></i>
                        </button>

                        <div class="dropdown-menu kemetic-dropdown-menu">

                            @if($webinar->isWebinar())
                                <button class="dropdown-item js-add-course-content-btn"
                                    data-type="session" data-webinar-id="{{ $webinar->id }}"
                                    data-chapter="{{ $chapter->id }}">
                                    Add Session
                                </button>
                            @endif

                            <button class="dropdown-item js-add-course-content-btn"
                                data-type="file" data-webinar-id="{{ $webinar->id }}"
                                data-chapter="{{ $chapter->id }}">
                                Add File
                            </button>

                            <button class="dropdown-item js-add-course-content-btn"
                                data-type="text_lesson" data-webinar-id="{{ $webinar->id }}"
                                data-chapter="{{ $chapter->id }}">
                                Add Text Lesson
                            </button>

                            <button class="dropdown-item js-add-course-content-btn"
                                data-type="quiz" data-webinar-id="{{ $webinar->id }}"
                                data-chapter="{{ $chapter->id }}">
                                Add Quiz
                            </button>

                            @if(getFeaturesSettings('webinar_assignment_status'))
                                <button class="dropdown-item js-add-course-content-btn"
                                    data-type="assignment" data-webinar-id="{{ $webinar->id }}"
                                    data-chapter="{{ $chapter->id }}">
                                    Add Assignment
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Edit -->
                     <button type="button" class="js-add-chapter btn-transparent kemetic-btn-icon" data-webinar-id="{{ $webinar->id }}" data-chapter="{{ $chapter->id }}" data-locale="{{ mb_strtoupper($chapter->locale) }}">
                        <i data-feather="edit-3" height="20"></i>
                    </button>
                    <!-- <button class="kemetic-btn-icon"
                        data-webinar-id="{{ $webinar->id }}"
                        data-chapter="{{ $chapter->id }}"
                        data-locale="{{ mb_strtoupper($chapter->locale) }}">
                        <i data-feather="edit-3"></i>
                    </button> -->

                    <!-- Delete -->
                    <a href="/panel/chapters/{{ $chapter->id }}/delete" class="kemetic-btn-icon delete-action">
                        <i data-feather="trash-2"></i>
                    </a>

                    <!-- Drag -->
                    <i data-feather="move" class="kemetic-drag-icon"></i>

                    <!-- Chevron -->
                     <!-- <i class="collapse-chevron-icon feather-chevron-up kemetic-chevron" data-feather="chevron-down" height="20" href="#collapseChapter{{ !empty($chapter) ? $chapter->id :'record' }}" aria-controls="collapseChapter{{ !empty($chapter) ? $chapter->id :'record' }}" data-parent="#chapterAccordion" role="button" data-toggle="collapse" aria-expanded="true"></i> -->
                    <i data-feather="chevron-down" class="kemetic-chevron"></i>
                </div>
            </div>

            <!-- COLLAPSE AREA -->
            <div id="collapseChapter{{ !empty($chapter) ? $chapter->id :'record' }}"
                aria-labelledby="chapter_{{ !empty($chapter) ? $chapter->id :'record' }}"
                class="collapse show"
                role="tabpanel">

                <div class="panel-collapse kemetic-chapter-panel">

                    <div class="accordion-content-wrapper kemetic-chapter-content mt-20"
                        id="chapterContentAccordion{{ !empty($chapter) ? $chapter->id :'' }}"
                        role="tablist"
                        aria-multiselectable="true">

                        @if(!empty($chapter->chapterItems) and count($chapter->chapterItems))
                            <ul class="draggable-content-lists
                                    kemetic-draggable-list
                                    draggable-lists-chapter-{{ $chapter->id }}"
                                data-drag-class="draggable-lists-chapter-{{ $chapter->id }}"
                                data-order-table="webinar_chapter_items">

                                @foreach($chapter->chapterItems as $chapterItem)
                                    @if($chapterItem->type == \App\Models\WebinarChapterItem::$chapterSession and !empty($chapterItem->session))
                                        @include('web.default.panel.webinar.create_includes.accordions.session',['session'=>$chapterItem->session,'chapter'=>$chapter,'chapterItem'=>$chapterItem])
                                    @elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterFile and !empty($chapterItem->file))
                                        @include('web.default.panel.webinar.create_includes.accordions.file',['file'=>$chapterItem->file,'chapter'=>$chapter,'chapterItem'=>$chapterItem])
                                    @elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterTextLesson and !empty($chapterItem->textLesson))
                                        @include('web.default.panel.webinar.create_includes.accordions.text-lesson',['textLesson'=>$chapterItem->textLesson,'chapter'=>$chapter,'chapterItem'=>$chapterItem])
                                    @elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterAssignment and !empty($chapterItem->assignment))
                                        @include('web.default.panel.webinar.create_includes.accordions.assignment',['assignment'=>$chapterItem->assignment,'chapter'=>$chapter,'chapterItem'=>$chapterItem])
                                    @elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterQuiz and !empty($chapterItem->quiz))
                                        @include('web.default.panel.webinar.create_includes.accordions.quiz',['quizInfo'=>$chapterItem->quiz,'chapter'=>$chapter,'chapterItem'=>$chapterItem])
                                    @endif
                                @endforeach

                            </ul>
                        @else
                            <div class="kemetic-empty-state">
                                @include(getTemplate().'.includes.no-result',[
                                    'file_name'=>'meet.png',
                                    'title'=>trans('update.chapter_content_no_result'),
                                    'hint'=>trans('update.chapter_content_no_result_hint'),
                                ])
                            </div>
                        @endif

                    </div>
                </div>
            </div>


        </li>
        @endforeach

    </ul>
    @else
                @include(getTemplate() . '.includes.no-result',[
                    'file_name' => 'meet.png',
                    'title' => trans('update.chapter_no_result'),
                    'hint' => trans('update.chapter_no_result_hint'),
                ])
            @endif
</div>
