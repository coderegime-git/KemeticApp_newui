@push('styles_top')
<style>
    /* KEMETIC THEME */
    :root {
        --k-bg: #0f0f0f;
        --k-card: #181818;
        --k-border: #2a2a2a;
        --k-gold: #f2c94c;
        --k-gold-soft: rgba(242, 201, 76, 0.15);
        --k-text: #e6e6e6;
        --k-radius: 14px;
        --k-shadow: 0 4px 18px rgba(0,0,0,0.35);
    }

    .kemetic-section-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--k-gold);
        margin-bottom: 15px;
        border-left: 4px solid var(--k-gold);
        padding-left: 12px;
    }

    /* BUTTON */
    .kemetic-btn {
        background: var(--k-gold);
        color: #000;
        border-radius: var(--k-radius);
        padding: 8px 18px;
        font-weight: 600;
        transition: 0.3s;
    }

    .kemetic-btn:hover {
        background: #ffdd73;
        color: #000;
    }

    /* ACCORDION WRAPPER */
    .kemetic-accordion-item {
        background: var(--k-card);
        border: 1px solid var(--k-border);
        border-radius: var(--k-radius);
        padding: 15px;
        margin-bottom: 12px;
        box-shadow: var(--k-shadow);
    }

    .kemetic-accordion-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: var(--k-text);
        cursor: pointer;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--k-border);
    }

    .kemetic-accordion-title {
        font-size: 16px;
        font-weight: 600;
    }

    .kemetic-accordion-body {
        margin-top: 12px;
    }

    /* NO RESULT BOX */
    .kemetic-no-result {
        background: var(--k-card);
        border: 1px solid var(--k-border);
        padding: 25px;
        border-radius: var(--k-radius);
        text-align: center;
        color: #888;
    }

    /* CHECKBOX */
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

</style>
@endpush


<section class="mt-40">
    <h2 class="kemetic-section-title">{{ trans('public.quiz_certificate') }} ({{ trans('public.optional') }})</h2>

    <button id="webinarAddQuiz"
            data-webinar-id="{{ $webinar->id }}"
            class="kemetic-btn mt-15">
        {{ trans('public.add_quiz') }}
    </button>

    <div class="row mt-20">
        <div class="col-12">

            <div id="quizzesAccordion">
                @if(!empty($webinar->quizzes) and count($webinar->quizzes))

                    @foreach($webinar->quizzes as $quizInfo)
                        <div class="kemetic-accordion-item">
                            @include('web.default.panel.webinar.create_includes.accordions.quiz',
                                ['webinar' => $webinar, 'quizInfo' => $quizInfo])
                        </div>
                    @endforeach

                @else
                    <div class="kemetic-no-result">
                        <img src="/assets/default/img/no-result/cert.png" width="60" class="mb-10">
                        <h5 class="mt-10">{{ trans('public.quizzes_no_result') }}</h5>
                        <p>{{ trans('public.quizzes_no_result_hint') }}</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</section>


<div id="newQuizForm" class="d-none">
    <div class="kemetic-accordion-item">
        @include('web.default.panel.webinar.create_includes.accordions.quiz',
            ['webinar' => $webinar, 'quizInfo' => null])
    </div>
</div>


@push('scripts_bottom')
<script>
    var saveSuccessLang = '{{ trans('webinars.success_store') }}';
    var quizzesSectionLang = '{{ trans('quiz.quizzes_section') }}';
</script>
@endpush
