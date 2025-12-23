@push('styles_top')
    <link href="/assets/default/vendors/sortable/jquery-ui.min.css"/>

    <style>
        /* ─────────────────────
            KEMETIC THEME
        ─────────────────────*/
        :root {
            --k-bg: #0F0F0F;
            --k-card: #141414;
            --k-border: rgba(242,201,76,0.22);
            --k-gold: #F2C94C;
            --k-gold-dark: #C79D2C;
            --k-text: #E8E8E8;
            --k-muted: #9b9b9b;
            --k-shadow: 0 8px 28px rgba(0,0,0,0.65);
            --k-radius: 18px;
        }

        .kemetic-section {
            margin-top: 48px;
            padding: 20px;
            background: var(--k-card);
            border: 1px solid var(--k-border);
            border-radius: var(--k-radius);
            box-shadow: var(--k-shadow);
        }

        .kemetic-section .section-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--k-gold);
        }

        .kemetic-add-btn {
            background: linear-gradient(180deg, var(--k-gold), var(--k-gold-dark));
            border: none;
            padding: 8px 18px;
            border-radius: 14px;
            color: #000;
            font重量: 700;
            box-shadow: 0 6px 14px rgba(242,201,76,0.25);
        }
        .kemetic-add-btn:hover {
            filter: brightness(.92);
        }

        .kemetic-card-item {
            background: #101010;
            border: 1px solid var(--k-border);
            padding: 16px;
            border-radius: 16px;
            margin-bottom: 14px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.45);
            color: var(--k-text);
        }

        .kemetic-card-item .item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--k-gold);
            font-weight: 600;
            margin-bottom: 8px;
        }

        .kemetic-no-result {
            padding: 30px;
            background: #121212;
            border: 1px dashed var(--k-border);
            text-align: center;
            border-radius: 16px;
            margin-top: 15px;
        }
        .kemetic-no-result .title {
            font-weight: 700;
            color: var(--k-gold);
        }
        .kemetic-no-result .hint {
            color: var(--k-muted);
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


{{-- ─────────────────────────────────
        FAQ SECTION (KEMETIC)
────────────────────────────────── --}}
<section class="kemetic-section">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h2 class="section-title after-line">
            {{ trans('public.faq') }} <span class="text-muted">({{ trans('public.optional') }})</span>
        </h2>

        <button id="webinarAddFAQ" data-webinar-id="{{ $webinar->id }}" type="button" class="kemetic-add-btn">
            + {{ trans('public.add_faq') }}
        </button>
    </div>

    <div id="faqsAccordion" class="mt-3">
        @if(!empty($webinar->faqs) and count($webinar->faqs))
            <ul class="draggable-lists" data-order-table="faqs">
                @foreach($webinar->faqs as $faqInfo)
                    <li class="kemetic-card-item">
                        @include('web.default.panel.webinar.create_includes.accordions.faq', [
                            'webinar' => $webinar,
                            'faq' => $faqInfo
                        ])
                    </li>
                @endforeach
            </ul>
        @else
            <div class="kemetic-no-result">
                <div class="title">{{ trans('public.faq_no_result') }}</div>
                <div class="hint">{{ trans('public.faq_no_result_hint') }}</div>
            </div>
        @endif
    </div>
</section>

<div id="newFaqForm" class="d-none">
    @include('web.default.panel.webinar.create_includes.accordions.faq', ['webinar' => $webinar])
</div>


{{-- ─────────────────────────────────────────────
    EXTRA DESCRIPTIONS (LEARNING MATERIAL, REQS…)
──────────────────────────────────────────────── --}}
@foreach(\App\Models\WebinarExtraDescription::$types as $type)
<section class="kemetic-section">
    <div class="d-flex justify-content-between align-items-center mb-2">

        <h2 class="section-title after-line">
            {{ trans('update.'.$type) }} <span class="text-muted">({{ trans('public.optional') }})</span>
        </h2>

        <button id="add_new_{{ $type }}"
                data-webinar-id="{{ $webinar->id }}"
                type="button"
                class="kemetic-add-btn">
            + {{ trans('update.add_'.$type) }}
        </button>
    </div>

    @php
        $values = $webinar->webinarExtraDescription->where('type',$type);
    @endphp

    <div id="{{ $type }}_accordion" class="mt-3">
        @if(!empty($values) and count($values))
            <ul class="draggable-content-lists draggable-lists-{{ $type }}"
                data-drag-class="draggable-lists-{{ $type }}"
                data-order-table="webinar_extra_descriptions_{{ $type }}">
                @foreach($values as $extra)
                    <li class="kemetic-card-item">
                        @include('web.default.panel.webinar.create_includes.accordions.extra_description', [
                            'webinar'                   => $webinar,
                            'extraDescription'          => $extra,
                            'extraDescriptionType'      => $type,
                            'extraDescriptionParentAccordion' => $type.'_accordion'
                        ])
                    </li>
                @endforeach
            </ul>
        @else
            <div class="kemetic-no-result">
                <div class="title">{{ trans("update.{$type}_no_result") }}</div>
                <div class="hint">{{ trans("update.{$type}_no_result_hint") }}</div>
            </div>
        @endif
    </div>
</section>

<div id="new_{{ $type }}_html" class="d-none">
    @include('web.default.panel.webinar.create_includes.accordions.extra_description', [
        'webinar' => $webinar,
        'extraDescriptionType' => $type,
        'extraDescriptionParentAccordion' => $type.'_accordion',
    ])
</div>
@endforeach


@push('scripts_bottom')
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>
@endpush
