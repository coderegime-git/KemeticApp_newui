<style>
/* ========== KEMETIC PROGRESS STEPS ========== */
.k-card {
    background: #151a23;
    padding: 15px;
    border-radius: 12px;
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.k-progress-item {
    display: flex;
    align-items: center;
}

.progress-icon {
    width: 50px;
    height: 50px;
    background: #0e1117;
    border: 2px solid #262c3a;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.progress-icon img {
    width: 24px;
    height: 24px;
}

.progress-icon.active {
    border-color: #F2C94C;
    background: linear-gradient(135deg,#F2C94C,#e0b93d);
}

.progress-icon:hover {
    border-color: #F2C94C;
}

.k-text-gold {
    color: #F2C94C;
    font-weight: 500;
}

.k-text-light {
    color: #e5e7eb;
}

.k-progress-item h4 {
    margin: 0;
}
</style>
@php
    $progressSteps = [
        1 => [
            'name' => 'basic_information',
            'icon' => 'paper'
        ],

        2 => [
            'name' => 'extra_information',
            'icon' => 'paper_plus'
        ],

        3 => [
            'name' => 'faq',
            'icon' => 'tick_square'
        ],

        4 => [
            'name' => 'message_to_reviewer',
            'icon' => 'shield_done'
        ],
    ];

    $currentStep = empty($currentStep) ? 1 : $currentStep;
@endphp

<div class="webinar-progress d-block d-lg-flex align-items-center p-15 k-card rounded-md">

    @foreach($progressSteps as $key => $step)
        <div class="progress-item d-flex align-items-center k-progress-item">
            <button type="button"
                    data-step="{{ $key }}"
                    class="js-get-next-step progress-icon d-flex align-items-center justify-content-center rounded-circle {{ $key == $currentStep ? 'active' : '' }}"
                    data-toggle="tooltip"
                    data-placement="top"
                    title="{{ trans('public.' . $step['name']) }}">
                <img src="/assets/default/img/icons/{{ $step['icon'] }}.svg" class="img-cover" alt="">
            </button>

            <div class="ml-10 {{ $key == $currentStep ? '' : 'd-lg-none' }}">
                <span class="font-14 k-text-gold">{{ trans('webinars.progress_step', ['step' => $key,'count' => 4]) }}</span>
                <h4 class="font-16 k-text-light font-weight-bold">{{ trans('public.' . $step['name']) }}</h4>
            </div>
        </div>
    @endforeach
</div>

