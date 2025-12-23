<style>
    /* ITEM WRAPPER */
.kemetic-content-item {
    background: #141414;
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius);
    margin-bottom: 12px;
    transition: 0.25s ease;
}

.kemetic-content-item:hover {
    background: rgba(242, 201, 76, 0.06);
    border-color: var(--k-gold);
}

/* ICON */
.kemetic-item-icon {
    background: rgba(242, 201, 76, 0.08);
    border-radius: 50%;
    padding: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.kemetic-item-icon i,
.kemetic-item-icon svg {
    stroke: var(--k-gold) !important;
    width: 20px;
    height: 20px;
}

/* TITLES */
.kemetic-item-title {
    color: var(--k-gold);
    font-size: 15px;
    font-weight: 600;
    display: block;
}

.kemetic-item-hint {
    color: #999;
    font-size: 12px;
    margin-top: 2px;
    display: block;
}

/* PERSONAL NOTE ICON */
.kemetic-note-icon {
    width: 26px;
    height: 26px;
    background: #222;
    border: 1px solid var(--k-border);
    border-radius: 8px;
}

.kemetic-note-icon svg {
    stroke: var(--k-gold) !important;
    width: 14px;
    height: 14px;
}

/* DESCRIPTION */
.kemetic-item-description {
    color: #bbb;
    font-size: 13px;
    line-height: 1.5;
}

/* PASSED SWITCH LABEL */
.kemetic-pass-label {
    color: var(--k-gold);
    font-size: 14px;
}

.custom-control {
  position: relative;
  z-index: 1;
  display: block;
  min-height: 1.3rem;
  padding-left: 2rem;
  -webkit-print-color-adjust: exact;
          print-color-adjust: exact;
}

.custom-control-inline {
  display: inline-flex;
  margin-right: 1rem;
}

.custom-control-input {
  position: absolute;
  left: 0;
  z-index: -1;
  width: 1.5rem;
  height: 1.4rem;
  opacity: 0;
}
.custom-control-input:checked ~ .custom-control-label::before {
  color: #ffffff;
  border-color: #43d477;
  background-color: #43d477;
}
.custom-control-input:focus ~ .custom-control-label::before {
  box-shadow: none, 1.5rem;
}
.custom-control-input:focus:not(:checked) ~ .custom-control-label::before {
  border-color: #43d477;
}
.custom-control-input:not(:disabled):active ~ .custom-control-label::before {
  color: #ffffff;
  background-color: #43d477;
  border-color: #43d477;
}
.custom-control-input[disabled] ~ .custom-control-label, .custom-control-input:disabled ~ .custom-control-label {
  color: #6c757d;
}
.custom-control-input[disabled] ~ .custom-control-label::before, .custom-control-input:disabled ~ .custom-control-label::before {
  background-color: #f1f1f1;
}

.custom-control-label {
  position: relative;
  margin-bottom: 0;
  vertical-align: top;
}
.custom-control-label::before {
  position: absolute;
  top: -0.1rem;
  left: -2rem;
  display: block;
  width: 1.5rem;
  height: 1.5rem;
  pointer-events: none;
  content: "";
  background-color: #ffffff;
  border: 2px solid #adb5bd;
  box-shadow: none;
}
.custom-control-label::after {
  position: absolute;
  top: -0.1rem;
  left: -2rem;
  display: block;
  width: 1.5rem;
  height: 1.5rem;
  content: "";
  background: 50%/50% 50% no-repeat;
}

.custom-checkbox .custom-control-label::before {
  border-radius: 0.25rem;
}
.custom-checkbox .custom-control-input:checked ~ .custom-control-label::after {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%23ffffff' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26l2.974 2.99L8 2.193z'/%3e%3c/svg%3e");
}
.custom-checkbox .custom-control-input:indeterminate ~ .custom-control-label::before {
  border-color: #F2C94C;
  background-color: #F2C94C;
}#
.custom-checkbox .custom-control-input:indeterminate ~ .custom-control-label::after {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='4' height='4' viewBox='0 0 4 4'%3e%3cpath stroke='%23ffffff' d='M0 2h4'/%3e%3c/svg%3e");
}
.custom-checkbox .custom-control-input:disabled:checked ~ .custom-control-label::before {
  background-color: #F2C94C;
}
.custom-checkbox .custom-control-input:disabled:indeterminate ~ .custom-control-label::before {
  background-color: #F2C94C;
}

.custom-radio .custom-control-label::before {
  border-radius: 50%;
}
.custom-radio .custom-control-input:checked ~ .custom-control-label::after {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23ffffff'/%3e%3c/svg%3e");
}
.custom-radio .custom-control-input:disabled:checked ~ .custom-control-label::before {
  background-color: rgba(67, 212, 119, 0.5);
}

.custom-switch {
  padding-left: 3.125rem;
}
.custom-switch .custom-control-label::before {
  left: -3.125rem;
  width: 2.625rem;
  pointer-events: all;
  border-radius: 0.75rem;
}
.custom-switch .custom-control-label::after {
  top: calc(-0.1rem + 4px);
  left: calc(-3.125rem + 4px);
  width: calc(1.5rem - 8px);
  height: calc(1.5rem - 8px);
  background-color: #adb5bd;
  border-radius: 0.75rem;
  transition: transform 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
@media (prefers-reduced-motion: reduce) {
  .custom-switch .custom-control-label::after {
    transition: none;
  }
}
.custom-switch .custom-control-input:checked ~ .custom-control-label::after {
  background-color: #ffffff;
  transform: translateX(1.125rem);
}
.custom-switch .custom-control-input:disabled:checked ~ .custom-control-label::before {
  background-color: rgba(67, 212, 119, 0.5);
}


/* DISABLED SEQUENCE STATE */
.js-sequence-content-error-modal {
    opacity: 0.6;
    pointer-events: all;
}

</style>

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



<div class="kemetic-content-item d-flex align-items-start p-15 cursor-pointer 
    {{ (!empty($checkSequenceContent) and $sequenceContentHasError) ? 'js-sequence-content-error-modal' : 'tab-item' }}"
     data-type="{{ $type }}"
     data-id="{{ $item->id }}"
     data-passed-error="{{ $checkSequenceContent['all_passed_items_error'] ?? '' }}"
     data-access-days-error="{{ $checkSequenceContent['access_after_day_error'] ?? '' }}"
>
    <span class="kemetic-item-icon mr-15">
        <i data-feather="{{ $icon }}"></i>
    </span>

    <div class="flex-grow-1">

        <div class="d-flex align-items-center justify-content-between">
            <div>
                <span class="kemetic-item-title">{{ $item->title }}</span>
                <span class="kemetic-item-hint">{{ $hintText }}</span>
            </div>

            @if($hasPersonalNote)
                <span class="kemetic-note-icon d-flex-center">
                    <i data-feather="edit-2"></i>
                </span>
            @endif
        </div>

        {{-- DESCRIPTION --}}
        <div class="kemetic-item-info mt-15">
            <p class="kemetic-item-description">
                @php
                    $description = $item->description ?? $item->summary ?? '';
                @endphp

                {!! truncate($description, 150) !!}
            </p>

            {{-- CHECKBOX --}}
            <div class="d-flex align-items-center justify-content-between mt-20">
                <label class="kemetic-pass-label" for="readToggle{{ $type }}{{ $item->id }}">
                    {{ trans('public.i_passed_this_lesson') }}
                </label>

                <div class="custom-control custom-switch">
                    <input type="checkbox"
                           id="readToggle{{ $type }}{{ $item->id }}"
                           data-item-id="{{ $item->id }}"
                           data-item="{{ $type }}_id"
                           value="{{ $item->webinar_id }}"
                           class="js-passed-lesson-toggle custom-control-input"
                           @if($sequenceContentHasError) disabled @endif
                           @if(!empty($item->checkPassedItem())) checked @endif>

                           <!-- <span class="kemetic-switch-slider"></span> -->

                    <label class="custom-control-label" for="readToggle{{ $type }}{{ $item->id }}"></label>
                </div>
            </div>
        </div>

    </div>

</div>
