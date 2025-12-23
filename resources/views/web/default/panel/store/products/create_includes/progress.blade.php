<style>
/* Kemetic Progress */
.kemetic-progress-wrapper {
    background: #0F0F0F;
    border: 1px solid rgba(242, 201, 76, 0.25);
    padding: 18px 20px;
    border-radius: 16px;
}

/* FLEX ITEMS */
.kemetic-progress-steps {
    gap: 22px;
}

/* ITEM */
.kemetic-progress-item {
    display: flex;
    align-items: center;
    position: relative;
}

/* STEP BUTTON */
.progress-btn {
    width: 54px;
    height: 54px;
    border-radius: 50%;
    background: #1a1a1a;
    border: 1px solid rgba(242, 201, 76, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: 0.25s ease;
    padding: 0;
}

.progress-btn img {
    width: 26px;
    filter: brightness(0) invert(1);
    opacity: 0.7;
}

/* HOVER */
.progress-btn:hover {
    border-color: #F2C94C;
    box-shadow: 0 0 16px rgba(242, 201, 76, 0.35);
}

.progress-btn:hover img {
    opacity: 1;
}

/* ACTIVE */
.progress-btn.active {
    background: linear-gradient(145deg, #F2C94C, #b78a26);
    border-color: #F2C94C;
    box-shadow: 0 0 20px rgba(242, 201, 76, 0.55);
}

.progress-btn.active img {
    filter: brightness(0);
    opacity: 1;
}

/* LABEL SECTION */
.progress-label {
    margin-left: 12px;
    display: none;
}

.progress-label.show {
    display: block;
}

/* TEXT */
.progress-label span {
    font-size: 12px;
    color: #b8b8b8;
}

.progress-label h4 {
    color: #F2C94C;
    font-size: 15px;
    font-weight: 600;
    margin: 2px 0 0;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .kemetic-progress-steps {
        gap: 16px;
    }

    .progress-btn {
        width: 46px;
        height: 46px;
    }

    .progress-label h4 {
        font-size: 14px;
    }
}

</style>

@php
    $progressSteps = [
        1 => ['name'=>'basic_information','icon'=>'paper'],
        2 => ['name'=>'extra_information','icon'=>'paper_plus'],
        3 => ['name'=>'images','icon'=>'images'],
        4 => ['name'=>'specifications','icon'=>'tick_square'],
        5 => ['name'=>'message_to_reviewer','icon'=>'shield_done'],
    ];

    $currentStep = empty($currentStep) ? 1 : $currentStep;
@endphp

@php
    $progressSteps = [
        1 => ['name'=>'basic_information','icon'=>'paper'],
        2 => ['name'=>'extra_information','icon'=>'paper_plus'],
        3 => ['name'=>'images','icon'=>'images'],
        4 => ['name'=>'specifications','icon'=>'tick_square'],
        5 => ['name'=>'message_to_reviewer','icon'=>'shield_done'],
    ];

    $currentStep = empty($currentStep) ? 1 : $currentStep;
@endphp

<div class="kemetic-progress-wrapper">
    <div class="kemetic-progress-steps d-flex flex-wrap align-items-center">

@foreach($progressSteps as $key => $step)
    <div class="kemetic-progress-item">

        <button type="button"
                data-step="{{ $key }}"
                class="progress-btn {{ $key == $currentStep ? 'active' : '' }}"
                data-toggle="tooltip"
                data-placement="top"
                title="{{ trans('public.' . $step['name']) }}">

            <img src="/assets/default/img/icons/{{ $step['icon'] }}.svg" alt="">
        </button>

        <div class="progress-label {{ $key == $currentStep ? 'show' : '' }}">
            <span>{{ trans('webinars.progress_step',['step' => $key,'count' => 5]) }}</span>
            <h4>{{ trans('public.' . $step['name']) }}</h4>
        </div>

    </div>
@endforeach
</div>
</div>

