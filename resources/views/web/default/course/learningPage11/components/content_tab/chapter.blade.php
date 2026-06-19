<style>
    /* KEMETIC APP — BLACK GOLD THEME */
:root {
    --kemetic-bg: #0A0A0A;
    --kemetic-card: #131313;
    --kemetic-gold: #D4AF37;
    --kemetic-gold-soft: rgba(212, 175, 55, 0.15);
    --kemetic-text: #FFFFFF;
    --kemetic-text-dim: #9F9F9F;
    --kemetic-radius: 14px;
    --kemetic-shadow: 0 0 20px rgba(212, 175, 55, 0.08);
}

/* Accordion Wrapper */
.kemetic-accordion-row {
    background: var(--kemetic-card);
    border: 1px solid var(--kemetic-gold-soft);
    border-radius: var(--kemetic-radius);
    margin-bottom: 12px;
    box-shadow: var(--kemetic-shadow);
    transition: 0.3s ease;
}

.kemetic-accordion-row:hover {
    border-color: var(--kemetic-gold);
}

/* Header */
.kemetic-accordion-header {
    padding: 14px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.kemetic-accordion-header .chapter-title {
    color: var(--kemetic-gold);
    font-weight: 600;
    font-size: 15px;
}

.kemetic-accordion-header .chapter-subtitle {
    color: var(--kemetic-text-dim);
    font-size: 12px;
}

/* Icon circle */
.kemetic-icon-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--kemetic-gold-soft);
    display: flex;
    justify-content: center;
    align-items: center;
}

.kemetic-icon-circle i {
    color: var(--kemetic-gold);
}

/* Chevron */
.kemetic-chevron {
    color: var(--kemetic-text-dim);
    transition: 0.3s;
}

.kemetic-chevron.rotate {
    transform: rotate(180deg);
}

/* Content */
.kemetic-panel {
    background: #0F0F0F;
    padding: 10px 16px 16px;
    color: var(--kemetic-text-dim);
    border-top: 1px solid var(--kemetic-gold-soft);
}

</style>
@if(!empty($course->chapters) && count($course->chapters))
    <div class="accordion-content-wrapper mt-15" id="chapterAccordion" role="tablist" aria-multiselectable="true">

        @foreach($course->chapters as $chapter)
            <div class="kemetic-accordion-row" data-chapter="{{ $chapter->id }}">

                <div class="kemetic-accordion-header" role="tab"
                    id="chapter_{{ $chapter->id }}"
                    data-toggle="collapse"
                    href="#collapseChapter{{ $chapter->id }}"
                    aria-expanded="false"
                    aria-controls="collapseChapter{{ $chapter->id }}">

                    <div class="d-flex align-items-center">

                        <div class="kemetic-icon-circle mr-10">
                            <i data-feather="grid" width="20" height="20"></i>
                        </div>

                        <div>
                            <span class="chapter-title">{{ $chapter->title }}</span>
                            <span class="chapter-subtitle d-block">
                                {{ $chapter->getTopicsCount(true) }} {{ trans('public.topic') }}
                            </span>
                        </div>

                    </div>

                    <i class="kemetic-chevron" data-feather="chevron-down" width="20"></i>
                </div>
                
                <div id="collapseChapter{{ $chapter->id }}"
                     class="collapse"
                     role="tabpanel"
                     aria-labelledby="chapter_{{ $chapter->id }}"
                     data-parent="#chapterAccordion">

                    <div class="kemetic-panel">

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

