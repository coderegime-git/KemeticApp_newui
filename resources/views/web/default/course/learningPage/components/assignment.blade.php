<style>
    /* Base Backgrounds */
.bg-gray900 { background-color: #1C1C1C !important; } /* Dark main background */
.bg-gray800 { background-color: #2A2A2A !important; } /* Cards, panels */
.bg-gray700 { background-color: #333 !important; } /* Slightly lighter background */
.bg-info-light { background-color: #2a2a2a !important; } /* Reuse for info sections */

/* Text Colors */
.text-gold { color: #F2C94C !important; } /* Gold highlight text */
.text-gray { color: #B0B0B0 !important; } /* Gray text */
.text-dark-blue { color: #E5E5E5 !important; } /* Light text for dark backgrounds */
.text-primary { color: #F2C94C !important; } /* Gold for buttons, status */
.text-danger { color: #FF4C4C !important; } /* Red alert text */
.text-warning { color: #FFC107 !important; } /* Yellow warning */

/* Borders */
.border-gray700 { border-color: #444 !important; }
.border-gray600 { border-color: #555 !important; }

/* Buttons */
.btn-gold {
    background-color: #F2C94C;
    color: #222;
    border: none;
    transition: all 0.3s ease;
}
.btn-gold:hover {
    background-color: #d4b943;
    color: #1C1C1C;
}

/* Hover Effects */
.hover-bg-gray700:hover { background-color: #333 !important; }

/* Card styling */
.rounded-lg { border-radius: 18px !important; }
.rounded-sm { border-radius: 10px !important; }
.p-10 { padding: 10px !important; }
.p-15 { padding: 15px !important; }
.mt-10 { margin-top: 10px !important; }
.mt-15 { margin-top: 15px !important; }
.mt-20 { margin-top: 20px !important; }
.mt-25 { margin-top: 25px !important; }

/* Assignment Stats */
.assignment-top-stats__item img.assignment-top-stats__icon {
    width: 40px;
    height: 40px;
}

/* Text and Font */
.font-12 { font-size: 12px !important; }
.font-14 { font-size: 14px !important; }
.font-16 { font-size: 16px !important; }
.font-20 { font-size: 20px !important; }
.font-weight-bold { font-weight: 700 !important; }
.font-weight-500 { font-weight: 500 !important; }

/* Form Inputs */
.form-control {
    background-color: #2A2A2A;
    color: #E5E5E5;
    border: 1px solid #444;
    border-radius: 8px;
}
.form-control::placeholder { color: #888; }

/* Attachment Cards */
.chapter-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
}
.chapter-icon.bg-gray300 { background-color: #555; }
.d-flex.align-items-center.justify-content-center.flex-column img { max-width: 100%; }

/* Messages & User Avatars */
.user-avatar img {
    border: 2px solid #F2C94C;
}
.assignment-attachments-post {
    background-color: #2A2A2A;
    border-color: #444;
}

/* Resolved/Status badges */
.resolved-answer-badge {
    color: #F2C94C;
}

</style>
<section class="p-15 m-15 rounded-lg bg-gray900 border border-gray700">
    <div class="assignment-top-stats d-flex flex-wrap flex-md-nowrap align-items-center justify-content-around">

        <div class="assignment-top-stats__item d-flex align-items-center justify-content-center pb-5 pb-md-0">
            <div class="d-flex flex-column align-items-center text-center">
                <img src="/assets/default/img/activity/calendar.svg" class="assignment-top-stats__icon" alt="">
                <strong class="font-20 text-gold font-weight-bold mt-5">
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
                <strong class="font-20 text-gold font-weight-bold mt-5">
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
                <strong class="font-20 text-gold font-weight-bold mt-5">{{ $assignmentHistory->grade ?? 0 }}/{{ $assignment->grade }}</strong>
                <span class="font-14 text-gray font-weight-500">{{ trans('quiz.your_grade') }}</span>
            </div>
        </div>

        <div class="assignment-top-stats__item d-flex align-items-center justify-content-center pb-5 pb-md-0">
            <div class="d-flex flex-column align-items-center text-center">
                <img src="/assets/default/img/activity/58.svg" class="assignment-top-stats__icon" alt="">
                <strong class="font-20 text-gold font-weight-bold mt-5">{{ $assignment->pass_grade }}</strong>
                <span class="font-14 text-gray font-weight-500">{{ trans('update.min_grade') }}</span>
            </div>
        </div>

        <div class="assignment-top-stats__item d-flex align-items-center justify-content-center pb-5 pb-md-0">
            <div class="d-flex flex-column align-items-center text-center">
                <img src="/assets/default/img/activity/88.svg" class="assignment-top-stats__icon" alt="">
                <strong class="font-20 text-gold font-weight-bold mt-5">{{ trans('update.assignment_history_status_'.$assignmentHistory->status) }}</strong>
                <span class="font-14 text-gray font-weight-500">{{ trans('public.status') }}</span>
            </div>
        </div>
    </div>

    <div class="p-15 rounded-lg bg-gray800 font-14 text-gray mt-20">{!! $assignment->description !!}</div>
</section>

@if(!empty($assignment->attachments) and count($assignment->attachments))
<section class="mt-25 container-fluid">
    <h2 class="section-title text-gold">{{ trans('public.attachments') }}</h2>

    <div class="row">
        @foreach($assignment->attachments as $attachment)
            <div class="col-6 col-lg-3 mt-10">
                <a href="{{ $attachment->getDownloadUrl() }}" target="_blank" class="d-flex align-items-center p-10 border rounded-sm bg-gray800 hover-bg-gray700">
                    <span class="chapter-icon bg-gray700 mr-10">
                        <i data-feather="file" class="text-gold" width="16" height="16"></i>
                    </span>

                    <div>
                        <h4 class="font-12 text-gold font-weight-bold">{{ $attachment->title }}</h4>
                        <span class="font-12 text-gray">{{ $attachment->getFileSize() }}</span>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</section>
@endif

<section class="mt-25 container-fluid">
    <h2 class="section-title text-gold">{{ trans('update.assignment_history') }}</h2>

    <section class="p-10 my-10 rounded-lg border border-gray700 bg-gray900">
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
                <div class="bg-gray800 p-10 rounded-sm border border-gray700">
                    <h4 class="font-16 font-weight-bold text-gold">
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
                            <label class="input-label text-gold">{{ trans('public.description') }}</label>
                            <textarea rows="6" name="description" class="form-control bg-gray700 text-gray border-gray600"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group">
                            <label class="input-label text-gold">{{ trans('update.file_title') }} ({{ trans('public.optional') }})</label>
                            <input name="file_title" class="form-control bg-gray700 text-gray border-gray600"/>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group d-flex align-items-center">
                            <div class="input-group mr-10">
                                <div class="input-group-prepend">
                                    <button type="button" class="input-group-text panel-file-manager bg-gold text-dark" data-input="assignmentAttachmentInput" data-preview="holder">
                                        <i data-feather="upload" width="18" height="18"></i>
                                    </button>
                                </div>
                                <input type="text" name="file_path" id="assignmentAttachmentInput" value="" class="form-control bg-gray700 text-gray border-gray600" placeholder="{{ trans('update.assignment_attachments_placeholder') }}"/>
                            </div>

                            <button type="button" class="js-save-history-message btn btn-gold btn-sm">{{ trans('update.send') }}</button>
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

            <div class="col-12 col-lg-8 border-left border-gray700">
                <div class="h-100">
                    @if(!empty($assignmentHistory->messages) and count($assignmentHistory->messages))
                        @foreach($assignmentHistory->messages as $message)
                            <div class="assignment-attachments-post p-15 border rounded-sm mb-15 bg-gray800 border-gray700">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar rounded-circle">
                                        <img src="{{ $message->sender->getAvatar(50) }}" class="img-cover rounded-circle" alt="{{ $message->sender->full_name }}">
                                    </div>
                                    <div class="ml-10">
                                        <h4 class="font-14 font-weight-500 text-gold">{{ $message->sender->full_name }}</h4>
                                        <span class="d-block font-12 text-gray">{{ dateTimeFormat($message->created_at, 'j M Y | H:i') }}</span>
                                    </div>
                                </div>
                                <div class="mt-15 font-14 text-gray">
                                    {!! $message->message !!}
                                </div>

                                @if(!empty($message->file_path))
                                    <div class="d-flex flex-wrap align-items-center mt-10">
                                        <a href="{{ $message->getDownloadUrl($assignment->id) }}" target="_blank" class="d-flex align-items-center text-gray bg-gray700 border border-gray600 px-10 py-5 rounded-pill mr-10 mt-5">
                                            <i data-feather="paperclip" class="text-gold" width="16" height="16"></i>
                                            <span class="ml-5 font-12 text-gold">{{ !empty($message->file_title) ? $message->file_title : trans('update.attachment') }}</span>
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
                                <h3 class="font-20 font-weight-bold text-gold text-center">{{ trans('update.no_assignment') }}</h3>
                                <p class="mt-5 text-gray font-14 text-center">{{ trans('update.submit_your_assignment_and_evaluate_your_learning') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</section>

