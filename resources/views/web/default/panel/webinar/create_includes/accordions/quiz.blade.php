<style>
    /* MAIN ACCORDION BOX */
.kemetic-accordion-item{
    background:#111;
    border:1px solid rgba(242,201,76,0.25);
    border-radius:14px;
    padding:15px 18px;
    margin-top:20px;
    transition:.3s;
}
.kemetic-accordion-item:hover{
    border-color:#F2C94C;
    box-shadow:0 0 12px rgba(242,201,76,0.25);
}

/* HEADER */
.kemetic-accordion-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    cursor:pointer;
}

.kemetic-title{
    color:#fff;
    font-weight:600;
    font-size:15px;
}

.kemetic-icon i{
    color:#F2C94C;
    width:22px;
    height:22px;
}

/* ACTION BUTTONS */
.kemetic-header-actions{
    display:flex;
    align-items:center;
    gap:10px;
}

.kemetic-icon-btn{
    background:transparent;
    border:none;
    cursor:pointer;
    color:#F2C94C;
    padding:5px;
}
.kemetic-icon-btn i{
    width:20px;
    height:20px;
}

/* DISABLED BADGE */
.kemetic-badge-disabled{
    background:#422;
    color:#F2C94C;
    padding:4px 10px;
    border-radius:6px;
    font-size:12px;
}

/* MOVE ICON */
.kemetic-move-icon{
    color:#F2C94C;
    width:20px;
    height:20px;
    cursor:grab;
}

/* CHEVRON */
.kemetic-chevron{
    color:#F2C94C;
    width:20px;
    height:20px;
    cursor:pointer;
    transition:.3s;
}

/* BODY WRAPPER */
.kemetic-collapse-body{
    margin-top:12px;
}

.kemetic-body-inner{
    background:#0B0B0B;
    border:1px solid rgba(242,201,76,0.20);
    border-radius:12px;
    padding:20px;
}

/* DELETE ICON RED */
.text-red i{
    color:#FF5C5C;
}
.text-red:hover i{
    color:#ff7f7f;
}

</style>

<li data-id="{{ !empty($chapterItem) ? $chapterItem->id :'' }}" 
    class="accordion-row kemetic-accordion-item">

    <!-- HEADER -->
    <div class="kemetic-accordion-header"
         id="quiz_{{ !empty($quizInfo) ? $quizInfo->id :'record' }}"
         data-toggle="collapse"
         href="#collapseQuiz{{ !empty($quizInfo) ? $quizInfo->id :'record' }}"
         aria-expanded="true">

        <div class="d-flex align-items-center">
            <span class="kemetic-icon mr-10">
                <i data-feather="award"></i>
            </span>

            <span class="kemetic-title">
                {{ !empty($quizInfo) ? $quizInfo->title : trans('public.add_new_quizzes') }}
            </span>
        </div>

        <div class="kemetic-header-actions">

            <!-- Disabled Badge -->
            @if(!empty($quizInfo) and $quizInfo->status != \App\Models\WebinarChapter::$chapterActive)
                <span class="kemetic-badge-disabled"> {{ trans('public.disabled') }} </span>
            @endif

            <!-- Move Quiz to Another Chapter -->
            @if(!empty($quizInfo) and !empty($chapterItem))
                <button type="button"
                        data-item-id="{{ $quizInfo->id }}"
                        data-item-type="{{ \App\Models\WebinarChapterItem::$chapterQuiz }}"
                        data-chapter-id="{{ !empty($chapter) ? $chapter->id : '' }}"
                        class="kemetic-icon-btn js-change-content-chapter mr-10">
                    <i data-feather="grid"></i>
                </button>
            @endif

            @if(!empty($chapter))
                <i data-feather="move" class="kemetic-move-icon mr-10"></i>
            @endif

            <!-- Delete -->
            @if(!empty($quizInfo))
                <a href="/panel/quizzes/{{ $quizInfo->id }}/delete"
                   class="kemetic-icon-btn text-red">
                    <i data-feather="trash-2"></i>
                </a>
            @endif

            <!-- Chevron -->
            <i class="kemetic-chevron"
               data-feather="chevron-down"
               data-toggle="collapse"
               href="#collapseQuiz{{ !empty($quizInfo) ? $quizInfo->id :'record' }}">
            </i>

        </div>
    </div>

    <!-- COLLAPSE BODY -->
    <div id="collapseQuiz{{ !empty($quizInfo) ? $quizInfo->id :'record' }}"
         class="collapse kemetic-collapse-body @if(empty($quizInfo)) show @endif">

        <div class="kemetic-body-inner">

            @include('web.default.panel.quizzes.create_quiz_form', [
                'inWebinarPage' => true,
                'selectedWebinar' => $webinar,
                'quiz' => $quizInfo ?? null,
                'quizQuestions' => !empty($quizInfo) ? $quizInfo->quizQuestions : [],
                'chapters' => $webinar->chapters,
                'webinarChapterPages' => !empty($webinarChapterPages)
            ])
        </div>
    </div>
</li>
