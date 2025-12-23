<style>
/* KEMETIC APP — BLACK GOLD THEME */
:root {
    --kemetic-bg: #0A0A0A;
    --kemetic-card: #131313;
    --kemetic-card-light: #1A1A1A;
    --kemetic-gold: #D4AF37;
    --kemetic-gold-soft: rgba(212,175,55,0.18);
    --kemetic-text: #FFFFFF;
    --kemetic-text-dim: #A0A0A0;
    --kemetic-radius: 14px;
    --kemetic-shadow: 0 0 20px rgba(212,175,55,0.06);
}

/* CERTIFICATE ITEM */
.kemetic-cert-item {
    background: var(--kemetic-card);
    padding: 14px;
    border-radius: var(--kemetic-radius);
    border: 1px solid var(--kemetic-gold-soft);
    box-shadow: var(--kemetic-shadow);
    margin-bottom: 12px;
    cursor: pointer;
    transition: 0.25s ease;
}

.kemetic-cert-item:hover {
    border-color: var(--kemetic-gold);
    background: var(--kemetic-card-light);
}

.kemetic-icon-circle {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: var(--kemetic-gold-soft);
    display: flex;
    justify-content: center;
    align-items: center;
}

.kemetic-icon-circle i {
    color: var(--kemetic-gold);
}

.kemetic-title {
    font-size: 15px;
    font-weight: 600;
    color: var(--kemetic-gold);
}

.kemetic-sub {
    font-size: 12px;
    color: var(--kemetic-text-dim);
    margin-top: 3px;
}

/* EMPTY STATE */
.kemetic-empty-title {
    color: var(--kemetic-gold);
}

.kemetic-empty-text {
    color: var(--kemetic-text-dim);
}


</style>

@php
    $hasCertificateItem = false;
@endphp

<div class="content-tab p-15 pb-50">

    {{-- COURSE CERTIFICATE --}}
    @if($course->certificate)
        @php $hasCertificateItem = true; @endphp

        <div class="kemetic-cert-item"
             data-course-certificate="{{ !empty($courseCertificate) ? $courseCertificate->id : '' }}">

            <div class="d-flex align-items-center">

                <span class="kemetic-icon-circle mr-10">
                    <i data-feather="award" width="18" height="18"></i>
                </span>

                <div class="flex-grow-1">
                    <span class="kemetic-title">{{ trans('update.course_certificate') }}</span>

                    <div class="kemetic-sub mt-1">
                        @if(!empty($courseCertificate))
                            {{ trans("public.date") }}: {{ dateTimeFormat($courseCertificate->created_at, 'j F Y') }}
                        @else
                            {{ trans("update.not_achieve") }}
                        @endif
                    </div>
                </div>

            </div>
        </div>
    @endif


    {{-- QUIZ CERTIFICATES --}}
    @if(!empty($course->quizzes) and count($course->quizzes))
        @foreach($course->quizzes as $courseQuiz)
            @if($courseQuiz->certificate)

                @php $hasCertificateItem = true; @endphp

                <div class="kemetic-cert-item"
                     data-result="{{ $courseQuiz->result ? $courseQuiz->result->id : '' }}">

                    <div class="d-flex align-items-center">

                        <span class="kemetic-icon-circle mr-10">
                            <i data-feather="award" width="18" height="18"></i>
                        </span>

                        <div class="flex-grow-1">
                            <span class="kemetic-title">{{ $courseQuiz->title }}</span>

                            <div class="d-flex align-items-center kemetic-sub mt-1">
                                <span>{{ $courseQuiz->pass_mark }}/{{ $courseQuiz->quizQuestions->sum('grade') }}</span>

                                @if(!empty($courseQuiz->result))
                                    <span class="ml-10">
                                        {{ dateTimeFormat($courseQuiz->result->created_at, 'j M Y H:i') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

            @endif
        @endforeach
    @endif


    {{-- EMPTY STATE --}}
    @if(!$hasCertificateItem)
        <div class="learning-page-forum-empty d-flex align-items-center justify-content-center flex-column mt-40">

            <div class="learning-page-forum-empty-icon">
                <img src="/assets/default/img/learning/certificate-empty.svg" class="img-fluid" alt="">
            </div>

            <div class="d-flex align-items-center flex-column mt-10 text-center">
                <h3 class="kemetic-empty-title font-20 font-weight-bold">
                    {{ trans('update.learning_page_empty_certificate_title') }}
                </h3>

                <p class="kemetic-empty-text font-14 mt-5">
                    {{ trans('update.learning_page_empty_certificate_hint') }}
                </p>
            </div>

        </div>
    @endif

</div>

