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

/* CARD ITEM */
.kemetic-item {
    background: var(--kemetic-card);
    padding: 14px;
    border-radius: var(--kemetic-radius);
    border: 1px solid var(--kemetic-gold-soft);
    box-shadow: var(--kemetic-shadow);
    display: flex;
    gap: 14px;
    margin-bottom: 10px;
    text-decoration: none;
    transition: 0.25s ease;
}

.kemetic-item:hover {
    border-color: var(--kemetic-gold);
    background: var(--kemetic-card-light);
}

/* Icon Circle */
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

/* Title + Status */
.kemetic-title {
    font-size: 15px;
    font-weight: 600;
    color: var(--kemetic-gold);
}

.kemetic-status {
    font-size: 12px;
    margin-top: 3px;
}

/* Description */
.kemetic-desc {
    margin-top: 12px;
    font-size: 12px;
    color: var(--kemetic-text-dim);
}

/* Deadline */
.kemetic-deadline {
    margin-top: 10px;
    font-size: 12px;
    color: var(--kemetic-text-dim);
}

</style>

@php
    $itemHistory = $item->getAssignmentHistoryByStudentId(request()->get('student', $user->id));

    $checkSequenceContent = $item->checkSequenceContent();
    $sequenceContentHasError = (!empty($checkSequenceContent) 
        and (!empty($checkSequenceContent['all_passed_items_error']) 
        or !empty($checkSequenceContent['access_after_day_error'])));

    $assignmentUrl = "{$course->getLearningPageUrl()}?type=assignment&item={$item->id}";
    $assignmentUrlTarget = "_self";

    if ($course->isOwner($user->id)) {
        $assignmentUrl = "/panel/assignments/{$item->id}/students";
        $assignmentUrlTarget = "_blank";
    } elseif ($user->isAdmin() or $course->isPartnerTeacher($user->id)) {
        $assignmentUrl = "#!";
    }
@endphp


<a href="{{ (!empty($checkSequenceContent) and $sequenceContentHasError) ? '#!' : $assignmentUrl }}"
   target="{{ $assignmentUrlTarget }}"
   class="
        kemetic-item
        cursor-pointer
        {{ (!empty($checkSequenceContent) and $sequenceContentHasError) ? 'js-sequence-content-error-modal' : 'tab-item' }}
        {{ ($user->isAdmin() or $course->isPartnerTeacher($user->id)) ? 'js-not-access-toast' : '' }}
    "
   data-type="assignment"
   data-id="{{ $item->id }}"
   data-passed-error="{{ !empty($checkSequenceContent['all_passed_items_error']) ? $checkSequenceContent['all_passed_items_error'] : '' }}"
   data-access-days-error="{{ !empty($checkSequenceContent['access_after_day_error']) ? $checkSequenceContent['access_after_day_error'] : '' }}"
>

    {{-- ICON --}}
    <span class="kemetic-icon-circle">
        <i data-feather="feather" width="18" height="18"></i>
    </span>

    {{-- INFO --}}
    <div class="flex-grow-1">

        {{-- TITLE + STATUS --}}
        <div>
            <span class="kemetic-title">{{ $item->title }}</span>

            @if(empty($itemHistory) or ($itemHistory->status == \App\Models\WebinarAssignmentHistory::$notSubmitted))
                <span class="kemetic-status text-danger">{{ trans('update.assignment_history_status_not_submitted') }}</span>
            @else
                @switch($itemHistory->status)
                    @case(\App\Models\WebinarAssignmentHistory::$passed)
                        <span class="kemetic-status" style="color: var(--kemetic-gold);">
                            {{ trans('quiz.passed') }}
                        </span>
                        @break

                    @case(\App\Models\WebinarAssignmentHistory::$pending)
                        <span class="kemetic-status" style="color: #FFB400;">
                            {{ trans('public.pending') }}
                        </span>
                        @break

                    @case(\App\Models\WebinarAssignmentHistory::$notPassed)
                        <span class="kemetic-status text-danger">{{ trans('quiz.failed') }}</span>
                        @break
                @endswitch
            @endif
        </div>

        {{-- DESCRIPTION --}}
        <p class="kemetic-desc">{!! truncate($item->description, 150) !!}</p>

        {{-- DEADLINE --}}
        @php $itemDeadline = $item->getDeadlineTimestamp(); @endphp

        <div class="kemetic-deadline">
            <span>{{ trans('update.deadline') }}: </span>

            @if(is_bool($itemDeadline))
                @if(!$itemDeadline)
                    <span class="text-danger">{{ trans('panel.expired') }}</span>
                @else
                    <span>{{ trans('update.unlimited') }}</span>
                @endif
            @elseif(!empty($itemDeadline))
                {{ dateTimeFormat($itemDeadline, 'j M Y') }}
            @else
                <span>{{ trans('update.unlimited') }}</span>
            @endif
        </div>

    </div>
</a>
