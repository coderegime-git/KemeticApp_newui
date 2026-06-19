@extends('web.default.layouts.app')

@section('content')
  <style>
    /* =========================================================
         KEMETIC CHECKOUT  –  payment.blade.php  (Steps 3 & 4)
         Desktop-first, uses the normal app layout/sidebar
         ========================================================= */
    :root {
      --ck-purple: #9B35FF;
      --ck-gold: #D4AF37;
      --ck-gold2: #FFE28A;
      --ck-muted: #A89EC4;
      --ck-text: #F0ECF8;
      --ck-input: #1A1430;
      --ck-border: rgba(255, 255, 255, .10);
      --ck-danger: #FF6B6B;
      --ck-surface: rgba(18, 14, 31, .8);
    }

    /* Checkout wrapper — normal flow, dark background */
    * {
      box-sizing: border-box;
    }

    .ck-wrap {
      width: 100%;
      max-width: 100%;
      overflow-x: hidden;
      background: radial-gradient(ellipse 130% 50% at 50% -10%, #2A0C52 0%, #0D0B14 70%);
      min-height: 100vh;
      color: var(--ck-text);
      font-family: Arial, Helvetica, sans-serif;
      padding: 32px 20px 80px;
    }

    .ck-inner {
      max-width: 1100px;
      width: 100%;
      margin: 0 auto;
      overflow: hidden;
    }

    /* Header */
    .ck-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 24px;
    }

    .ck-back-btn {
      width: 40px;
      height: 40px;
      border-radius: 12px;
      border: 1px solid rgba(212, 175, 55, .45);
      background: rgba(18, 14, 31, .8);
      color: var(--ck-gold);
      font-size: 24px;
      line-height: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      text-decoration: none;
    }

    .ck-logo {
      color: var(--ck-gold);
      font-size: 26px;
      font-weight: 900;
      letter-spacing: 4px;
    }

    .ck-secure-badge {
      border: 1px solid rgba(212, 175, 55, .45);
      color: var(--ck-gold);
      background: rgba(18, 14, 31, .8);
      border-radius: 12px;
      padding: 8px 16px;
      font-size: 13px;
    }

    /* Step indicator */
    .ck-step-label {
      font-size: 14px;
      color: var(--ck-muted);
      margin-bottom: 12px;
    }

    .ck-steps {
      display: flex;
      flex-direction: row;
      align-items: flex-start;
      justify-content: center;
      gap: 0;
      margin-bottom: 32px;
      position: relative;
      max-width: 600px;
    }

    .ck-steps::before {
      content: '';
      position: absolute;
      top: 16px;
      left: 24px;
      right: 24px;
      height: 1px;
      background: var(--ck-border);
      z-index: 0;
    }

    .ck-step {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 6px;
      z-index: 1;
      flex: 1;
    }

    .ck-step-circle {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      border: 2px solid rgba(255, 255, 255, .18);
      background: #120E1F;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 13px;
      font-weight: 700;
      color: rgba(255, 255, 255, .35);
      transition: all .25s;
    }

    .ck-step.done .ck-step-circle {
      background: rgba(155, 53, 255, .2);
      border-color: var(--ck-purple);
      color: var(--ck-purple);
    }

    .ck-step.active .ck-step-circle {
      background: var(--ck-purple);
      border-color: var(--ck-purple);
      color: #fff;
      box-shadow: 0 0 16px rgba(155, 53, 255, .6);
    }

    .ck-step-name {
      font-size: 11px;
      color: rgba(255, 255, 255, .28);
      text-align: center;
      white-space: nowrap;
    }

    .ck-step.active .ck-step-name,
    .ck-step.done .ck-step-name {
      color: var(--ck-text);
    }

    /* Two-column desktop layout */
    .ck-layout {
      display: grid;
      grid-template-columns: 1fr 400px;
      gap: 24px;
      align-items: start;
    }

    @media(max-width:900px) {
      .ck-layout {
        grid-template-columns: 1fr;
      }
    }

    /* Mobile fixes */
    @media(max-width:600px) {
      .ck-wrap {
        padding: 16px 12px 80px;
      }

      .ck-section {
        padding: 16px 14px;
      }

      .ck-item {
        flex-wrap: nowrap;
        overflow: hidden;
      }

      .ck-item-thumb {
        width: 56px !important;
        height: 56px !important;
        min-width: 56px !important;
      }

      .ck-item-meta {
        min-width: 0;
        overflow: hidden;
        flex: 1;
      }

      .ck-item-title {
        white-space: normal !important;
        word-break: break-word;
        overflow: hidden;
        text-overflow: initial !important;
      }

      .ck-layout>div {
        min-width: 0;
        width: 100%;
        overflow: hidden;
      }
    }

    /* Section card */
    .ck-section {
      background: var(--ck-surface);
      border: 1px solid var(--ck-border);
      border-radius: 16px;
      padding: 24px;
      margin-bottom: 20px;
      backdrop-filter: blur(10px);
    }

    .ck-section-title {
      font-size: 18px;
      font-weight: 700;
      margin: 0 0 8px;
      color: #fff;
    }

    .ck-section-sub {
      font-size: 13px;
      color: var(--ck-muted);
      margin: 0 0 20px;
    }

    /* Cart items */
    .ck-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 0;
      border-bottom: 1px solid var(--ck-border);
    }

    .ck-item:last-child {
      border-bottom: none;
    }

    .ck-item-thumb {
      width: 60px;
      height: 60px;
      border-radius: 10px;
      overflow: hidden;
      flex-shrink: 0;
      background: rgba(155, 53, 255, .15);
    }

    .ck-item-thumb img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .ck-item-meta {
      flex: 1;
      min-width: 0;
    }

    .ck-item-title {
      font-size: 14px;
      font-weight: 600;
      color: #fff;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .ck-item-sub {
      font-size: 12px;
      color: var(--ck-muted);
      margin-top: 4px;
    }

    .ck-item-price {
      font-size: 15px;
      font-weight: 700;
      color: var(--ck-gold);
      white-space: nowrap;
    }

    /* Gateway cards */
    .ck-gateway {
      display: flex;
      align-items: center;
      gap: 16px;
      background: rgba(255, 255, 255, .04);
      border: 1px solid var(--ck-border);
      border-radius: 12px;
      padding: 16px;
      margin-bottom: 12px;
      cursor: pointer;
      transition: border-color .2s, background .2s;
    }

    .ck-gateway:last-child {
      margin-bottom: 0;
    }

    .ck-gateway.selected,
    .ck-gateway:has(input:checked) {
      border-color: var(--ck-purple);
      background: rgba(155, 53, 255, .1);
    }

    .ck-gateway input[type="radio"] {
      accent-color: var(--ck-purple);
      width: 20px;
      height: 20px;
      flex-shrink: 0;
    }

    .ck-gateway-logo {
      height: 40px;
      max-width: 100px;
      object-fit: contain;
      background: #fff;
      border-radius: 6px;
      padding: 4px 8px;
    }

    .ck-gateway-name {
      font-size: 14px;
      font-weight: 600;
      color: #fff;
      flex: 1;
    }

    .ck-gateway-note {
      font-size: 12px;
      color: var(--ck-muted);
      margin-top: 4px;
    }

    .ck-gateway-disabled {
      opacity: .4;
      pointer-events: none;
    }

    /* Order summary sidebar */
    .ck-sidebar .ck-section {
      margin-bottom: 0;
    }

    .ck-summary-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 0;
      border-bottom: 1px solid var(--ck-border);
      font-size: 15px;
    }

    .ck-summary-row:last-child {
      border-bottom: none;
    }

    .ck-summary-row.total {
      font-size: 17px;
      font-weight: 800;
      color: var(--ck-gold);
      padding-top: 14px;
    }

    /* Trust bar */
    .ck-trust {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: rgba(255, 255, 255, .04);
      border: 1px solid var(--ck-border);
      border-radius: 10px;
      padding: 12px 16px;
      font-size: 12px;
      color: var(--ck-muted);
      margin-top: 16px;
    }

    /* CTA button */
    .ck-cta {
      width: 100%;
      border: none;
      border-radius: 14px;
      padding: 17px;
      background: linear-gradient(135deg, var(--ck-gold), var(--ck-gold2));
      color: #0D0B14;
      font-size: 16px;
      font-weight: 900;
      letter-spacing: 1px;
      cursor: pointer;
      text-transform: uppercase;
      transition: filter .2s;
      margin-top: 16px;
      display: block;
      text-align: center;
    }

    .ck-cta:hover {
      filter: brightness(1.08);
    }

    .ck-cta:disabled {
      opacity: .5;
      cursor: not-allowed;
    }

    /* ── Toast Notification ─────────────────────────────────── */
    .ck-toast {
      position: fixed;
      bottom: 30px;
      right: 30px;
      background: #FF6B6B;
      color: #fff;
      padding: 16px 24px;
      border-radius: 12px;
      font-size: 14px;
      font-weight: 600;
      box-shadow: 0 10px 30px rgba(255, 107, 107, 0.4);
      transform: translateY(100px);
      opacity: 0;
      transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
      z-index: 999999;
      display: flex;
      align-items: center;
      gap: 12px;
      pointer-events: none;
    }

    .ck-toast.show {
      transform: translateY(0);
      opacity: 1;
    }

    .ck-toast.success {
      background: #22c55e;
      box-shadow: 0 10px 30px rgba(34, 197, 94, 0.4);
    }

    .ck-toast.warning {
      background: #f59e0b;
      box-shadow: 0 10px 30px rgba(245, 158, 11, 0.4);
    }

    /* ── Loading bar ─────────────────────────────────────────── */
    .ck-loading-bar {
      position: fixed;
      top: 0;
      left: 0;
      width: 0%;
      height: 3px;
      background: linear-gradient(90deg, #9B35FF, #D4AF37);
      z-index: 9999999;
      transition: width 0.3s ease;
      box-shadow: 0 0 10px rgba(155, 53, 255, 0.6);
    }

    .ck-loading-bar.running {
      animation: ck-load 1.2s ease-in-out infinite;
    }

    @keyframes ck-load {
      0% {
        width: 0%;
        left: 0;
      }

      50% {
        width: 70%;
        left: 0;
      }

      100% {
        width: 100%;
        left: 0;
      }
    }

    /* ── Full-page loading overlay ─────────────────────────── */
    .ck-page-loading {
      position: fixed;
      inset: 0;
      z-index: 9999998;
      background: rgba(10, 6, 20, 0.92);
      display: none;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 20px;
    }

    .ck-page-loading-spinner {
      width: 52px;
      height: 52px;
      border: 4px solid rgba(155, 53, 255, 0.2);
      border-top-color: #9B35FF;
      border-radius: 50%;
      animation: ck-spin 0.8s linear infinite;
    }

    .ck-page-loading-text {
      color: #A89EC4;
      font-size: 15px;
      letter-spacing: 1px;
    }

    @keyframes ck-spin {
      to {
        transform: rotate(360deg);
      }
    }

    /* Step panels */
    .ck-panel {
      display: none;
    }

    .ck-panel.active {
      display: block;
    }

    /* ── Delete confirmation modal ────────────────────────── */
    .ck-modal-overlay {
      position: fixed;
      inset: 0;
      z-index: 99999;
      background: rgba(0, 0, 0, .65);
      backdrop-filter: blur(6px);
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      pointer-events: none;
      transition: opacity .25s;
    }

    .ck-modal-overlay.visible {
      opacity: 1;
      pointer-events: auto;
    }

    .ck-modal {
      background: linear-gradient(160deg, #1E0D35, #120E1F);
      border: 1px solid rgba(155, 53, 255, .35);
      border-radius: 24px;
      padding: 36px 30px 28px;
      max-width: 380px;
      width: calc(100% - 40px);
      text-align: center;
      transform: scale(.92);
      transition: transform .25s;
      box-shadow: 0 20px 60px rgba(0, 0, 0, .6);
    }

    .ck-modal-overlay.visible .ck-modal {
      transform: scale(1);
    }

    .ck-modal-icon {
      font-size: 44px;
      margin-bottom: 14px;
      display: block;
    }

    .ck-modal h3 {
      color: #fff;
      font-size: 20px;
      font-weight: 800;
      margin: 0 0 8px;
    }

    .ck-modal p {
      color: var(--ck-muted);
      font-size: 14px;
      line-height: 1.6;
      margin: 0 0 24px;
    }

    .ck-modal-btns {
      display: flex;
      gap: 10px;
    }

    .ck-modal-btns button {
      flex: 1;
      border: none;
      border-radius: 14px;
      padding: 14px;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      transition: filter .2s;
    }

    .ck-modal-btn-cancel {
      background: rgba(255, 255, 255, .08);
      color: var(--ck-text);
      border: 1px solid rgba(255, 255, 255, .12) !important;
    }

    .ck-modal-btn-delete {
      background: linear-gradient(135deg, #FF6B6B, #ff3b3b);
      color: #fff;
    }

    .ck-modal-btn-cancel:hover {
      filter: brightness(1.2);
    }

    .ck-modal-btn-delete:hover {
      filter: brightness(1.1);
    }

    /* ── Empty cart screen ──────────────────────────────────── */
    .ck-empty-cart {
      display: none;
      text-align: center;
      padding: 60px 20px;
    }

    .ck-empty-cart .ck-empty-icon {
      font-size: 64px;
      margin-bottom: 20px;
      display: block;
    }

    .ck-empty-cart h3 {
      color: #fff;
      font-size: 22px;
      font-weight: 800;
      margin: 0 0 10px;
    }

    .ck-empty-cart p {
      color: var(--ck-muted);
      font-size: 14px;
      line-height: 1.6;
      margin: 0 0 28px;
    }

    .ck-go-app {
      display: inline-block;
      padding: 15px 36px;
      background: linear-gradient(135deg, var(--ck-gold), var(--ck-gold2));
      color: #0D0B14;
      font-size: 15px;
      font-weight: 900;
      border-radius: 14px;
      text-decoration: none;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: filter .2s;
    }

    .ck-go-app:hover {
      filter: brightness(1.08);
      color: #0D0B14;
    }

    /* Status Card Specific CSS */
    .ck-status-container {
      max-width: 500px;
      margin: 0 auto;
    }

    .ck-status-card {
      background: var(--ck-surface);
      border: 1px solid var(--ck-border);
      border-radius: 18px;
      padding: 40px 30px 30px;
      text-align: center;
      backdrop-filter: blur(12px);
      margin-bottom: 20px;
    }

    .ck-sparkle {
      font-size: 40px;
      display: block;
      margin-bottom: 20px;
    }

    .ck-status-card h2 {
      font-size: 24px;
      font-weight: 800;
      color: var(--ck-gold);
      margin: 0 0 12px;
    }

    .ck-status-card p {
      font-size: 15px;
      color: var(--ck-muted);
      line-height: 1.65;
      margin: 0 0 30px;
    }

    .ck-status-card.failed h2 {
      color: var(--ck-danger);
    }

    /* Inputs */
    .ck-field {
      margin-bottom: 14px;
    }

    .ck-field label {
      display: block;
      font-size: 12px;
      color: var(--ck-muted);
      text-transform: uppercase;
      letter-spacing: .7px;
      margin-bottom: 6px;
    }

    .ck-input {
      width: 100%;
      background: var(--ck-input);
      border: 1px solid rgba(255, 255, 255, .12);
      border-radius: 12px;
      padding: 13px 16px;
      color: var(--ck-text);
      font-size: 14px;
      outline: none;
      transition: border-color .2s;
      -webkit-appearance: none;
      appearance: none;
    }

    .ck-input:focus {
      border-color: var(--ck-purple);
    }

    .ck-input option {
      background: #1A1430;
    }

    textarea.ck-input {
      resize: vertical;
    }

    .ck-row {
      display: flex;
      gap: 12px;
    }

    .ck-row .ck-field {
      flex: 1;
    }

    /* Currency Pills */
    .ck-currency-list {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }

    .ck-currency-pill {
      border: 1px solid rgba(255, 255, 255, .12);
      background: var(--ck-input);
      color: var(--ck-muted);
      padding: 12px 18px;
      border-radius: 24px;
      font-size: 12px;
      font-weight: 700;
      cursor: pointer;
      transition: all .2s;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .ck-currency-pill:hover {
      border-color: var(--ck-purple);
      color: #fff;
    }

    .ck-currency-pill.active {
      border-color: var(--ck-gold);
      color: var(--ck-gold);
    }

    /* Shipping opt */
    .ck-shipping-opt {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: rgba(155, 53, 255, .08);
      border: 1px solid rgba(155, 53, 255, .3);
      border-radius: 12px;
      padding: 14px;
      margin-top: 12px;
    }

    .ck-shipping-opt-left {
      display: flex;
      align-items: center;
      gap: 10px;
    }
  </style>
  <div class="ck-wrap">
    <div class="ck-inner">

      {{-- HEADER --}}
      <header class="ck-header">
        <a href="javascript:void(0)" id="ckBackBtn" class="ck-back-btn">&#8249;</a>
        <div class="ck-logo">KEMETIC</div>
        <div class="ck-secure-badge">&#128274; Secure</div>
      </header>

      {{-- STEP INDICATOR --}}
      <div class="ck-step-label" id="ckStepLabel">Step 1 of 5</div>
      <div class="ck-steps">
        <div class="ck-step active" id="step-dot-1">
          <div class="ck-step-circle">1</div><span class="ck-step-name">Info</span>
        </div>
        <div class="ck-step" id="step-dot-2">
          <div class="ck-step-circle">2</div><span class="ck-step-name">Shipping</span>
        </div>
        <div class="ck-step" id="step-dot-3">
          <div class="ck-step-circle">3</div><span class="ck-step-name">Payment</span>
        </div>
        <div class="ck-step" id="step-dot-4">
          <div class="ck-step-circle">4</div><span class="ck-step-name">Review</span>
        </div>
        <div class="ck-step" id="step-dot-5">
          <div class="ck-step-circle">5</div><span class="ck-step-name">Done</span>
        </div>
      </div>

      <form action="/cart/checkout" method="post" id="cartForm">
        {{ csrf_field() }}
        <input type="hidden" name="discount_id" value="">
        <input type="hidden" id="shipping_cost" name="shipping_cost" value="{{ $productDeliveryFee ?? 0 }}">

        <!-- We merge variables needed for payment steps -->
        @php
          $isMultiCurrency = !empty(getFinancialCurrencySettings('multi_currency'));
          $userCurrency = currency();
          $invalidChannels = [];
        @endphp

        @if(empty($step) || $step != 5)
        <div class="ck-panel active" id="panel1">
          <div class="ck-layout">

            {{-- LEFT: Forms --}}
            <div>
              {{-- Country / Region --}}
              <section class="ck-section">
                <h2 class="ck-section-title"
                  style="font-size:14px;color:var(--ck-gold);letter-spacing:1px;text-transform:uppercase;">
                  &#127758; Country / Region
                </h2>
                <div class="ck-field" style="margin-bottom:10px;">
                  <select id="dummy_country_id" name="dummy_country_id" class="ck-input">
                    <option value="">{{ trans('update.select_country') }}</option>
                    @if(!empty($countries))
                      @foreach($countries as $country)
                        @php
                          $isSelected = false;
                          if (stripos($country->title, 'Netherlands') !== false) {
                            $isSelected = true;
                          }
                        @endphp
                        <option value="{{ $country->id }}" {{ $isSelected ? 'selected' : '' }}>
                          {{ $country->title }}
                        </option>
                      @endforeach
                    @endif
                  </select>
                </div>
                <p style="font-size:11px;color:var(--ck-muted);margin:0;">Your country determines taxes, delivery options
                  and local payment methods.</p>
              </section>

              {{-- Currency --}}
              @php
                $multiCurrency = new \App\Mixins\Financial\MultiCurrency();
                $currencies = $multiCurrency->getCurrencies()->unique('currency');
                $userCurrency = currency();
              @endphp
              @if(!empty($currencies) and count($currencies))
                <section class="ck-section">
                  <h2 class="ck-section-title"
                    style="font-size:14px;color:var(--ck-gold);letter-spacing:1px;text-transform:uppercase;">
                    &#128338; Currency
                  </h2>
                  <div class="ck-currency-list">
                    @foreach($currencies as $currencyItem)
                      <div class="ck-currency-pill {{ ($userCurrency == $currencyItem->currency) ? 'active' : '' }}"
                        data-currency="{{ $currencyItem->currency }}"
                        onclick="changeCurrency('{{ $currencyItem->currency }}')">
                        {{ $currencyItem->currency }} {{ currencySign($currencyItem->currency) }}
                      </div>
                    @endforeach
                  </div>
                </section>
              @endif

              <section class="ck-section">
                <h2 class="ck-section-title"
                  style="font-size:14px;color:var(--ck-gold);letter-spacing:1px;text-transform:uppercase;">&#128100;
                  Contact Information</h2>
                <div class="ck-row">
                  <div class="ck-field">
                    <label for="firstName">{{ trans('update.first_name') }} <span
                        style="color:var(--ck-danger);">*</span></label>
                    <input id="firstName" name="first_name" type="text"
                      class="ck-input @error('first_name') is-invalid @enderror" required
                      value="{{ !empty($user) ? $user->first_name : '' }}">
                    <div class="ck-invalid ck-client-error" id="error-firstName" style="display:none; color:var(--ck-danger);">Please fill
                      required fields</div>
                    @error('first_name')<div class="ck-invalid">{{ $message }}</div>@enderror
                  </div>
                  <div class="ck-field">
                    <label for="lastName">{{ trans('update.last_name') }} <span
                        style="color:var(--ck-danger);">*</span></label>
                    <input id="lastName" name="last_name" type="text"
                      class="ck-input @error('last_name') is-invalid @enderror" required
                      value="{{ !empty($user) ? $user->last_name : '' }}">
                    <div class="ck-invalid ck-client-error" id="error-lastName" style="display:none; color:var(--ck-danger);">Please fill required
                      fields</div>
                    @error('last_name')<div class="ck-invalid">{{ $message }}</div>@enderror
                  </div>
                </div>
                <div class="ck-field">
                  <label for="email">{{ trans('update.email') }} <span style="color:var(--ck-danger);">*</span></label>
                  <input id="email" name="email" type="email" class="ck-input @error('email') is-invalid @enderror"
                    required value="{{ !empty($user) ? $user->email : '' }}">
                  <div class="ck-invalid ck-client-error" id="error-email" style="display:none; color:var(--ck-danger);">Please fill required
                    fields</div>
                  @error('email')<div class="ck-invalid">{{ $message }}</div>@enderror
                </div>
                <div class="ck-field">
                  <label for="phone">Phone Number <span style="color:var(--ck-danger);">*</span></label>
                  <input id="phone" name="phone" type="text" class="ck-input @error('phone') is-invalid @enderror"
                    required min-length="6" maxlength="15" oninput="this.value=this.value.replace(/[^0-9]/g,''); if(this.value.length > 15) this.value = this.value.slice(0, 15);" value="{{ !empty($user) ? $user->mobile : '' }}">
                  <div class="ck-invalid ck-client-error" id="error-phone" style="display:none; color:var(--ck-danger);">Please fill required
                    fields</div>
                  @error('phone')<div class="ck-invalid">{{ $message }}</div>@enderror
                </div>
              </section>
            </div>

            {{-- RIGHT: Order Summary --}}
            <div class="ck-sidebar">
              <section class="ck-section">
                <h2 class="ck-section-title">Order Summary</h2>
                <div class="ck-summary-row">
                  <span>{{ trans('cart.sub_total') }}</span>
                  <span class="cart-subtotal-value">{{ handlePrice($subTotal) }}</span>
                </div>
                <div class="ck-summary-row">
                  <span>Estimated Shipping</span>
                  <span class="cart-shipping-value">{{ handlePrice($productDeliveryFee ?? 0) }}</span>
                </div>
                @if(isset($totalDiscount) && $totalDiscount > 0)
                  <div class="ck-summary-row" style="color: #00E676;">
                    <span>Discount</span>
                    <span>-{{ handlePrice($totalDiscount) }}</span>
                  </div>
                @endif
                @if(isset($taxPrice) && $taxPrice > 0)
                  <div class="ck-summary-row">
                    <span>Tax / VAT</span>
                    <span>{{ handlePrice($taxPrice) }}</span>
                  </div>
                @endif
                <div class="ck-summary-row total">
                  <span>{{ trans('cart.total') }}</span>
                  <span class="cart-total-value">{{ handlePrice($total) }}</span>
                </div>
                <button type="button" id="ckNextBtn1" class="ck-cta">Continue to Shipping</button>
                <div class="ck-trust">
                  <span>&#128274; 100% Secure</span>
                  <span>Stripe Protected</span>
                  <span>SSL</span>
                </div>
              </section>
            </div>
          </div>
        </div>

        <div class="ck-panel" id="panel2">
          <div class="ck-layout">

            {{-- LEFT: Shipping Address --}}
            <div>
              <section class="ck-section">
                <h2 class="ck-section-title">Shipping Address</h2>

                {{-- Country (shipping) --}}
                <div class="ck-field">
                  <label for="shipping_country">Country <span style="color:var(--ck-danger);">*</span></label>
                  <select id="shipping_country" name="country_id"
                    class="ck-input @error('country_id') is-invalid @enderror">
                    <option value="">{{ trans('update.select_country') }}</option>
                    @if(!empty($countries))
                      @foreach($countries as $country)
                        @php
                          $isSelected = false;
                          if (!empty($user) && $user->country_id == $country->id) {
                            $isSelected = true;
                          }
                        @endphp
                        <option value="{{ $country->id }}" {{ $isSelected ? 'selected' : '' }}>
                          {{ $country->title }}
                        </option>
                      @endforeach
                    @endif
                  </select>
                  <div class="ck-invalid ck-client-error" id="error-shipping_country" style="display:none; color:var(--ck-danger);">Please fill
                    required fields</div>
                  @error('country_id')<div class="ck-invalid" style="color:var(--ck-danger);">{{ $message }}</div>@enderror
                </div>

                <div class="ck-row">
                  <div class="ck-field">
                    <label for="province">Province / State <span style="color:var(--ck-danger);">*</span></label>
                    <input id="province" name="province_name" type="text"
                      class="ck-input @error('province_name') is-invalid @enderror"
                      value="{{ !empty($user) ? $user->province_name : '' }}">
                    <div class="ck-invalid ck-client-error" id="error-province" style="display:none; color:var(--ck-danger);">Please fill required
                      fields</div>
                    @error('province_name')<div class="ck-invalid" style="color:var(--ck-danger);">{{ $message }}</div>@enderror
                  </div>
                  <div class="ck-field">
                    <label for="city">{{ trans('update.city') }} <span style="color:var(--ck-danger);">*</span></label>
                    <input id="city" name="city_name" type="text"
                      class="ck-input @error('city_name') is-invalid @enderror"
                      value="{{ !empty($user) ? $user->city_name : '' }}">
                    <div class="ck-invalid ck-client-error" id="error-city" style="display:none; color:var(--ck-danger);">Please fill required
                      fields</div>
                    @error('city_name')<div class="ck-invalid" style="color:var(--ck-danger);">{{ $message }}</div>@enderror
                  </div>
                </div>

                <div class="ck-field">
                  <label for="house_no">House No. <span style="color:var(--ck-danger);">*</span></label>
                  <input id="house_no" name="house_no" type="text"
                    class="ck-input @error('house_no') is-invalid @enderror"
                    value="{{ !empty($user) ? $user->house_no : '' }}">
                  <div class="ck-invalid ck-client-error" id="error-house_no" style="display:none; color:var(--ck-danger);">Please fill required
                    fields</div>
                  @error('house_no')<div class="ck-invalid" style="color:var(--ck-danger);">{{ $message }}</div>@enderror
                </div>

                <div class="ck-field">
                  <label for="address">{{ trans('update.address') }} <span
                      style="color:var(--ck-danger);">*</span></label>
                  <textarea id="address" name="address" rows="3"
                    class="ck-input @error('address') is-invalid @enderror">{{ !empty($user) ? $user->address : '' }}</textarea>
                  <div class="ck-invalid ck-client-error" id="error-address" style="display:none; color:var(--ck-danger);">Please fill required
                    fields</div>
                  @error('address')<div class="ck-invalid" style="color:var(--ck-danger);">{{ $message }}</div>@enderror
                </div>

                <div class="ck-field">
                  <label for="zip">ZIP / Postal Code <span style="color:var(--ck-danger);">*</span></label>
                  <input id="zip" name="zip_code" type="text" class="ck-input @error('zip_code') is-invalid @enderror"
                    maxlength="10" oninput="if(this.value.length > 10) this.value = this.value.slice(0, 10);" value="{{ !empty($user) ? $user->zip_code : '' }}">
                  <div class="ck-invalid ck-client-error" id="error-zip" style="display:none; color:var(--ck-danger);">Please fill required fields
                  </div>
                  @error('zip_code')<div class="ck-invalid" style="color:var(--ck-danger);">{{ $message }}</div>@enderror
                </div>

                <div
                  style="background:transparent; border:1px solid rgba(212,175,55,0.45); border-radius:24px; padding:10px 16px; display:inline-flex; align-items:center; justify-content:center; gap:8px; margin-top:20px;">
                  <span style="color:var(--ck-gold); font-size:13px;">&#9889;</span>
                  <span style="font-size:13px; font-weight:600; color:var(--ck-gold);">Express Shipping Always
                    Selected</span>
                </div>
              </section>

              {{-- Billing Address --}}
              <section class="ck-section" id="billingSection">
                <h2 class="ck-section-title">Billing Address</h2>
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;margin-bottom:16px;">
                  <input type="checkbox" id="billingSameAsShipping" checked
                    style="width:18px;height:18px;accent-color:var(--ck-purple);"
                    onchange="toggleBillingFields(this.checked)">
                  <span style="font-size:14px;color:var(--ck-text);">Billing address is the same as shipping
                    address</span>
                </label>

                <div id="billingFields" style="display:none;">
                  <div class="ck-field">
                    <label for="billing_country">Country</label>
                    <select id="billing_country" name="billing_country_id" class="ck-input">
                      <option value="">{{ trans('update.select_country') }}</option>
                      @if(!empty($countries))
                        @foreach($countries as $country)
                          <option value="{{ $country->id }}">{{ $country->title }}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                  <div class="ck-row">
                    <div class="ck-field">
                      <label for="billing_province">Province / State</label>
                      <input id="billing_province" name="billing_province" type="text" class="ck-input">
                    </div>
                    <div class="ck-field">
                      <label for="billing_city">City</label>
                      <input id="billing_city" name="billing_city" type="text" class="ck-input">
                    </div>
                  </div>
                  <div class="ck-field">
                    <label for="billing_address">Address</label>
                    <textarea id="billing_address" name="billing_address" rows="3" class="ck-input"></textarea>
                  </div>
                  <div class="ck-field">
                    <label for="billing_zip">ZIP / Postal Code</label>
                    <input id="billing_zip" name="billing_zip" type="text" class="ck-input" maxlength="10" oninput="if(this.value.length > 10) this.value = this.value.slice(0, 10);">
                  </div>
                </div>
              </section>
            </div>

            {{-- RIGHT: Summary --}}
            <div class="ck-sidebar">
              <section class="ck-section">
                <h2 class="ck-section-title">Order Summary</h2>
                <div class="ck-summary-row">
                  <span>{{ trans('cart.sub_total') }}</span>
                  <span>{{ handlePrice($subTotal) }}</span>
                </div>
                <div class="ck-summary-row">
                  <span>Shipping</span>
                  <span class="cart-shipping-value">{{ handlePrice($productDeliveryFee) }}</span>
                </div>
                <div class="ck-summary-row total">
                  <span>{{ trans('cart.total') }}</span>
                  <span class="cart-total-value">{{ handlePrice($total) }}</span>
                </div>
                <button type="button" id="ckNextBtn2" class="ck-cta">Continue to Payment</button>
                <div class="ck-trust">
                  <span>🔒 SSL Secure</span>
                  <span>Stripe Protected</span>
                  <span>Express Shipping</span>
                </div>
              </section>
            </div>
          </div>
        </div>

        <div class="ck-panel active" id="panel3">
          <div class="ck-layout">

            {{-- LEFT: Payment Options --}}
            <div>
              <section class="ck-section">
                <h2 class="ck-section-title">Payment</h2>

                {{-- Auto-select the first available Stripe gateway --}}
                @php
                  $stripeChannel = null;
                  $autoGatewayId = null;
                  if (!empty($paymentChannels)) {
                    foreach ($paymentChannels as $pc) {
                      if (!$isMultiCurrency || (!empty($pc->currencies) && in_array($userCurrency, $pc->currencies))) {
                        if (stripos($pc->class_name, 'stripe') !== false || stripos($pc->title, 'stripe') !== false) {
                          $stripeChannel = $pc;
                          $autoGatewayId = $pc->id;
                          break;
                        }
                      }
                    }
                    // fallback: first valid channel
                    if (!$stripeChannel) {
                      foreach ($paymentChannels as $pc) {
                        if (!$isMultiCurrency || (!empty($pc->currencies) && in_array($userCurrency, $pc->currencies))) {
                          $stripeChannel = $pc;
                          $autoGatewayId = $pc->id;
                          break;
                        }
                      }
                    }
                  }
                @endphp

                {{-- Hidden input so the form always submits the gateway --}}
                @if($autoGatewayId)
                  <input type="hidden" name="gateway" id="autoGatewayInput" value="{{ $autoGatewayId }}">
                @endif

                <div
                  style="border:1px dashed var(--ck-border);border-radius:12px;padding:20px;margin-bottom:20px;font-size:14px;color:var(--ck-text);line-height:1.6;background:rgba(255,255,255,.02);">
                  Stripe Payment Element / PaymentSheet goes here.<br><br>
                  Cards, Apple Pay, Google Pay, Wero Europe and local methods appear automatically depending on country,
                  currency and Stripe settings.
                </div>

                <label
                  style="display:flex;align-items:center;gap:10px;cursor:pointer;font-size:14px;color:var(--ck-text);">
                  <input type="checkbox" checked style="width:18px;height:18px;accent-color:var(--ck-purple);">
                  Save payment method for faster checkout next time
                </label>
              </section>

              <section class="ck-section">
                <h2 class="ck-section-title">Promo Code</h2>
                <div class="ck-row" style="display:flex; gap:10px;">
                  <input id="promo" class="ck-input" placeholder="Enter promo code" style="flex:1;">
                  <button class="ck-cta ghost"
                    style="width:auto; margin:0; padding:17px 24px; font-size:14px;">Apply</button>
                </div>
              </section>
            </div>

            {{-- RIGHT: Summary --}}
            <div class="ck-sidebar">
              <section class="ck-section">
                <h2 class="ck-section-title">Order Summary</h2>
                <div class="ck-summary-row">
                  <span>Subtotal</span>
                  <span>{{ handlePrice($subTotal) }}</span>
                </div>
                <div class="ck-summary-row">
                  <span>Shipping</span>
                  <span>{{ handlePrice($order->product_delivery_fee ?? 0) }}</span>
                </div>
                @if(isset($totalDiscount) && $totalDiscount > 0)
                  <div class="ck-summary-row" style="color: #00E676;">
                    <span>Discount</span>
                    <span>-{{ handlePrice($totalDiscount) }}</span>
                  </div>
                @endif
                @if(isset($taxPrice) && $taxPrice > 0)
                  <div class="ck-summary-row">
                    <span>Tax / VAT</span>
                    <span>{{ handlePrice($taxPrice) }}</span>
                  </div>
                @endif
                <div class="ck-summary-row total">
                  <span>Total</span>
                  <span>{{ handlePrice($total) }}</span>
                </div>
                <button type="button" id="ckNextBtn3" class="ck-cta">Continue to Review</button>
                <div class="ck-trust">
                  <span>&#128274; 100% Secure</span>
                  <span>Stripe Protected</span>
                  <span>SSL</span>
                </div>
              </section>
            </div>
          </div>
        </div>

        <div class="ck-panel" id="panel4">
          <div class="ck-layout">

            {{-- LEFT: Items --}}
            <div>
              <section class="ck-section">
                <h2 class="ck-section-title">Your Items</h2>
                <!-- <p class="ck-section-sub" style="font-size:13px;color:var(--ck-muted);margin:0 0 16px;">Please confirm the items below before placing your order.</p> -->

                @foreach($carts as $cart)
                  @php
                    $cartItemInfo = $cart->getItemInfo();
                    if (empty($cartItemInfo))
                      continue;
                    $cartTaxType = !empty($cartItemInfo['isProduct']) ? 'store' : 'general';
                  @endphp
                  <div
                    style="display:flex;gap:12px;padding:20px 0;border-bottom:1px solid var(--ck-border);align-items:flex-start;width:100%;overflow:hidden;">
                    {{-- Thumbnail --}}
                    <div
                      style="width:64px;height:64px;min-width:64px;border-radius:10px;overflow:hidden;background:rgba(155,53,255,.15);">
                      @if(!empty($cartItemInfo['imgPath']))
                        <img src="{{ $cartItemInfo['imgPath'] }}" alt="{{ $cartItemInfo['title'] ?? '' }}"
                          style="width:100%;height:100%;object-fit:cover;">
                      @endif
                    </div>
                    {{-- Details --}}
                    <div style="flex:1;min-width:0;overflow:hidden;">
                      {{-- Title --}}
                      <div
                        style="font-size:14px;font-weight:700;color:#fff;margin-bottom:3px;word-break:break-word;line-height:1.3;">
                        {{ $cartItemInfo['title'] ?? '' }}</div>
                      {{-- Type --}}
                      <div style="font-size:12px;color:var(--ck-purple);margin-bottom:3px;">
                        @if(!empty($cartItemInfo['quantity'])) Product @else Course @endif
                      </div>
                      {{-- Vendor --}}
                      <div style="font-size:12px;color:var(--ck-muted);margin-bottom:4px;word-break:break-word;">
                        Vendor:&nbsp;{{ $cartItemInfo['teacherName'] ?? 'Abundance Shop' }}
                      </div>
                      {{-- Meta Colors --}}
                      <div
                        style="font-size:11px;color:var(--ck-muted);margin-bottom:14px;display:flex;align-items:center;gap:4px;">
                        <span>🔴 🟠 🟡 🟢 🔵 🟣</span>
                        <span>·</span>
                        <!-- <span style="color:var(--ck-gold);font-size:12px;">★</span> -->
                        <span>2 Global
                          <!-- {{ $cartItemInfo['rate'] ?? '0' }} -->
                        </span>
                      </div>

                      {{-- Qty row --}}
                      <div
                        style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;width:100%;">
                        <div style="display:flex;align-items:center;gap:8px;font-size:14px;color:var(--ck-text);">
                          <span>Qty</span>
                          <strong style="color:#fff;">{{ $cartItemInfo['quantity'] ?? 1 }}</strong>
                        </div>
                        <button type="button" onclick="removeCartItem(this, {{ $cart->id }})"
                          style="background:rgba(255,107,107,0.12);border:1.5px solid #FF6B6B;border-radius:50%;width:34px;height:34px;min-width:34px;max-width:34px;display:flex;align-items:center;justify-content:center;color:#FF6B6B;cursor:pointer;margin-left:auto;flex-shrink:0;"
                          title="Remove">
                          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#FF6B6B" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                          </svg>
                        </button>
                      </div>

                      {{-- Unit Price --}}
                      <div
                        style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;width:100%;font-size:14px;color:var(--ck-text);">
                        <span>Unit Price</span>
                        <strong style="color:var(--ck-gold);white-space:nowrap;margin-right:8px;">
                          @if(!empty($cartItemInfo['price']))
                            {{ handlePrice($cartItemInfo['price'], true, true, false, null, true, $cartTaxType) }}
                          @else
                            {{ handlePrice(0, true, true, false, null, true, $cartTaxType) }}
                          @endif
                        </strong>
                      </div>

                      {{-- Line Total --}}
                      <div
                        style="display:flex;justify-content:space-between;align-items:center;width:100%;font-size:14px;color:var(--ck-text);">
                        <span>Line Total</span>
                        <strong style="color:var(--ck-gold);white-space:nowrap;margin-right:8px;">
                          @php
                            $qty = !empty($cartItemInfo['quantity']) ? $cartItemInfo['quantity'] : 1;
                            $unitPrice = !empty($cartItemInfo['price']) ? $cartItemInfo['price'] : 0;
                            $lineTotal = $qty * $unitPrice;
                          @endphp
                          {{ handlePrice($lineTotal, true, true, false, null, true, $cartTaxType) }}
                        </strong>
                      </div>
                    </div>
                  </div>
                @endforeach
              </section>

              <section class="ck-section">
                <h2 class="ck-section-title">Legal Confirmation</h2>
                <label style="display:flex; align-items:center; gap:10px; margin-bottom:12px; cursor:pointer;">
                  <input type="checkbox" class="legal-checkbox" required style="width:18px;height:18px;">
                  <span style="font-size:14px; color:var(--ck-text);">I agree to the <a href="/terms" target="_blank"
                      style="color:var(--ck-gold);">Terms & Conditions</a>.</span>
                </label>
                <label style="display:flex; align-items:center; gap:10px; margin-bottom:12px; cursor:pointer;">
                  <input type="checkbox" class="legal-checkbox" required style="width:18px;height:18px;">
                  <span style="font-size:14px; color:var(--ck-text);">I understand the <a href="/refund-policy"
                      target="_blank" style="color:var(--ck-gold);">Refund &
                      Shipping Policy</a>.</span>
                </label>
                <label style="display:flex; align-items:center; gap:10px; margin-bottom:12px; cursor:pointer;">
                  <input type="checkbox" class="legal-checkbox" required style="width:18px;height:18px;">
                  <span style="font-size:14px; color:var(--ck-text);">I want to receive order updates and
                    Kemetic App notifications.</span>
                </label>
              </section>

              <section class="ck-section">
                <h2 class="ck-section-title">Need Help?</h2>
                <p style="font-size:13px; color:var(--ck-muted); line-height:1.6; margin-bottom:16px;">
                  If your payment fails because of currency or payment method restrictions, try another
                  currency or use a local payment method.
                  You can also place your order manually with one of our human agents.
                </p>
                <a href="https://chat.whatsapp.com/FfWt50zLTax1DUZCqJK59Q?mode=gi_t" target="_blank" class="ck-cta ghost"
                  style="display:flex; justify-content:center; align-items:center; gap:8px; border-color:#25D366; color:#25D366; background:rgba(37,211,102,.08);">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path
                      d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                  </svg>
                  Talk to a Human Agent
                </a>
              </section>
            </div>

            {{-- RIGHT: Final Summary --}}
            <div class="ck-sidebar">
              <section class="ck-section">
                <h2 class="ck-section-title">Order Summary</h2>
                <div class="ck-summary-row">
                  <span>Subtotal</span>
                  <span>{{ handlePrice($subTotal) }}</span>
                </div>
                <div class="ck-summary-row">
                  <span>Shipping</span>
                  <span>{{ handlePrice($order->product_delivery_fee ?? 0) }}</span>
                </div>
                @if(isset($totalDiscount) && $totalDiscount > 0)
                  <div class="ck-summary-row" style="color: #00E676;">
                    <span>Discount</span>
                    <span>-{{ handlePrice($totalDiscount) }}</span>
                  </div>
                @endif
                @if(isset($taxPrice) && $taxPrice > 0)
                  <div class="ck-summary-row">
                    <span>Tax / VAT</span>
                    <span>{{ handlePrice($taxPrice) }}</span>
                  </div>
                @endif
                <div class="ck-summary-row total">
                  <span>Total</span>
                  <span>{{ handlePrice($total) }}</span>
                </div>
                <button type="button" id="ckNextBtn4" class="ck-cta">Place Order</button>
                <div class="ck-trust">
                  <span>&#128274; 100% Secure</span>
                  <span>Stripe Protected</span>
                  <span>SSL</span>
                </div>
              </section>
            </div>

          </div>
        </div>

        @endif

        @if(!empty($step) && $step == 5)
        <div class="ck-panel active" id="panel5">
          <div class="ck-status-container">

            {{-- SUCCESS --}}
            @if(!empty($order) && $order->status === \App\Models\Order::$paid)
              <div class="ck-status-card">
                <span class="ck-sparkle">&#10024;</span>
                <h2>{{ trans('cart.success_pay_title') }}</h2>
                <p>{!! trans('cart.success_pay_msg') !!}</p>

                <a href="academyapp://payment-success" class="ck-cta d-flex d-sm-none" style="justify-content:center;">
                  {{ trans('public.redirect_to_app') }}
                </a>
                <a href="/" class="ck-cta d-none d-sm-block">
                  {{ trans('public.redirect_to_app') }}
                </a>
              </div>
            @endif

            {{-- FAILED --}}
            @if(!empty($order) && $order->status === \App\Models\Order::$fail)
              <div class="ck-status-card failed">
                <span class="ck-sparkle">&#9888;&#65039;</span>
                <h2>{{ trans('cart.failed_pay_title') }}</h2>
                <p>{!! nl2br(trans('cart.failed_pay_msg')) !!}</p>

                <a href="academyapp://payment-failed" class="ck-cta d-flex d-sm-none" style="justify-content:center;">
                  {{ trans('public.redirect_to_app') }}
                </a>
                <a href="/" class="ck-cta ghost d-none d-sm-block">
                  {{ trans('public.redirect_to_app') }}
                </a>
              </div>
            @endif


            <div class="ck-trust">
              <span>&#128274; 100% Secure</span>
              <span>Stripe Protected</span>
              <span>SSL</span>
            </div>
          </div>
        </div>
        @endif

      </form>

      {{-- RAZORPAY --}}
      @if(!empty($razorpay) && $razorpay)
        <form action="/payments/verify/Razorpay" method="get">
          <input type="hidden" name="order_id" value="{{ $order->id ?? '' }}">
          <script src="https://checkout.razorpay.com/v1/checkout.js" data-key="{{ getRazorpayApiKey()['api_key'] ?? '' }}"
            data-amount="{{ (int) (($order->total_amount ?? 0) * 100) }}" data-buttontext="product_price"
            data-description="Razorpay" data-currency="{{ currency() }}" data-image="{{ $generalSettings['logo'] ?? '' }}"
            data-prefill.name="{{ $user->full_name ?? '' }}" data-prefill.email="{{ $user->email ?? '' }}"
            data-theme.color="#9B35FF">
            </script>
        </form>
      @endif

      {{-- ── Delete confirmation modal ────────────────────── --}}
      <div class="ck-modal-overlay" id="deleteModal">
        <div class="ck-modal">
          <span class="ck-modal-icon">🗑️</span>
          <h3>Remove Item?</h3>
          <p>Are you sure you want to remove this item from your order?</p>
          <div class="ck-modal-btns">
            <button class="ck-modal-btn-cancel" onclick="closeDeleteModal()">Keep It</button>
            <button class="ck-modal-btn-delete" id="confirmDeleteBtn">Yes, Remove</button>
          </div>
        </div>
      </div>

      {{-- ── Empty cart screen ──────────────────────────────── --}}
      <div class="ck-empty-cart" id="emptyCartScreen">
        <span class="ck-empty-icon">🛒</span>
        <h3>Your Cart is Empty</h3>
        <p>You've removed all items from your order.<br>Head back to explore our products and courses.</p>
        <a href="academyapp://home" class="ck-go-app d-flex d-sm-none" style="justify-content:center;">🏠 Go to App</a>
        <a href="/" class="ck-go-app d-none d-sm-inline-block">🏠 Go to App</a>
      </div>

      {{-- ── Full-page Loading Overlay ──────────────────────── --}}
      <div class="ck-page-loading" id="ckPageLoading">
        <div class="ck-page-loading-spinner"></div>
        <span class="ck-page-loading-text">Processing…</span>
      </div>

      {{-- ── Loading Bar ──────────────────────────────────────── --}}
      <div class="ck-loading-bar" id="ckLoadingBar"></div>

      {{-- ── Toast Notification ────────────────────────────────── --}}
      <div class="ck-toast" id="ckToast">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
          stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="12" y1="8" x2="12" y2="12"></line>
          <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
        <span id="ckToastMsg">Notification</span>
      </div>

    </div>
  </div>
@endsection

@push('scripts_bottom')
  <script>
    var couponInvalidLng = '{{ trans('cart.coupon_invalid') }}';
    var selectProvinceLang = '{{ trans('update.select_province') }}';
    var selectCityLang = '{{ trans('update.select_city') }}';
    var selectDistrictLang = '{{ trans('update.select_district') }}';

    @php
      $multiCurrency = new \App\Mixins\Financial\MultiCurrency();
      $currencies = $multiCurrency->getCurrencies()->unique('currency');
    @endphp

    var currencyRates = {
      @foreach($currencies as $c)
        '{{ $c->currency }}': {{ floatval($c->exchange_rate ?? 1) }},
      @endforeach
  };
    var currencySymbols = {
      @foreach($currencies as $c)
        '{{ $c->currency }}': '{{ addslashes(currencySign($c->currency)) }}',
      @endforeach
  };
    var currentCurrency = '{{ currency() }}';
    var baseSubTotal = {{ floatval($subTotal ?? 0) }};
    var baseShipping = {{ floatval($productDeliveryFee ?? 0) }};

    /* ── User profile country (for Step 2 default) ── */
    var userProfileCountryId = '{{ !empty($user) && $user->country_id ? $user->country_id : "" }}';

    document.addEventListener('DOMContentLoaded', function () {
      if (userProfileCountryId) {
        var sel = document.getElementById('shipping_country');
        if (sel) sel.value = userProfileCountryId;
      }
    });

    function formatMoney(amount) {
      var rate = parseFloat(currencyRates[currentCurrency]);
      if (!rate || rate <= 0) rate = 1;
      var sym = currencySymbols[currentCurrency] || currentCurrency + ' ';
      return sym + (amount * rate).toFixed(2);
    }

    function updateDomTotals() {
      document.querySelectorAll('.cart-subtotal-value').forEach(function (el) {
        el.textContent = formatMoney(baseSubTotal);
      });
      document.querySelectorAll('.cart-shipping-value').forEach(function (el) {
        el.textContent = formatMoney(baseShipping);
      });
      document.querySelectorAll('.cart-total-value').forEach(function (el) {
        el.textContent = formatMoney(baseSubTotal + baseShipping);
      });
    }

    function changeCurrency(currency, calculateShippingAfter) {
      currentCurrency = currency;
      document.querySelectorAll('.ck-currency-pill').forEach(function (el) {
        el.classList.remove('active');
      });
      var pill = document.querySelector('.ck-currency-pill[data-currency="' + currency + '"]');
      if (pill) pill.classList.add('active');
      updateDomTotals();
      fetch('/set-currency', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: 'currency=' + encodeURIComponent(currency)
      }).then(function () {
        if (calculateShippingAfter && window.calculateShipping) {
          window.calculateShipping();
        }
      });
    }

    /* =====================================================================
       UNIFIED STEP LOGIC
       ===================================================================== */
    (function () {
      var nextBtn1 = document.getElementById('ckNextBtn1');
      var btnCartSubmit = document.getElementById('ckNextBtn2');
      var nextBtn3 = document.getElementById('ckNextBtn3');
      var nextBtn4 = document.getElementById('ckNextBtn4');

      function showPanel(panel) {
        document.querySelectorAll('.ck-panel').forEach(function (el) {
          el.style.display = 'none';
        });

        var target = document.getElementById('panel' + panel);
        if (target) target.style.display = 'block';

        for (var i = 1; i <= 5; i++) {
          var dot = document.getElementById('step-dot-' + i);
          if (!dot) continue;
          dot.classList.remove('active', 'done');
          if (i < panel) dot.classList.add('done');
          if (i === panel) dot.classList.add('active');
        }

        var lbl = document.getElementById('ckStepLabel');
        if (lbl) lbl.textContent = 'Step ' + panel + ' of 5';

        var backBtn = document.getElementById('ckBackBtn');
        if (backBtn) {
          if (panel === 1 || panel === 5) {
            backBtn.style.visibility = 'hidden';
            backBtn.onclick = function (e) { e.preventDefault(); };
          } else {
            backBtn.style.visibility = 'visible';
            backBtn.onclick = function () { showPanel(panel - 1); };
          }
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
      window.showPanel = showPanel;

      /* ── Global toast helper ─────────────────────────── */
      window.showToast = function(msg, type) {
        var toast = document.getElementById('ckToast');
        var toastMsg = document.getElementById('ckToastMsg');
        if (!toast || !toastMsg) return;
        toastMsg.textContent = msg;
        toast.classList.remove('success', 'warning', 'show');
        if (type === 'success') toast.classList.add('success');
        else if (type === 'warning') toast.classList.add('warning');
        /* default is red (error) */
        void toast.offsetWidth; /* force reflow for re-animation */
        toast.classList.add('show');
        clearTimeout(window._ckToastTimer);
        window._ckToastTimer = setTimeout(function() {
          toast.classList.remove('show');
        }, 4000);
      };

      /* Step 1 -> 2 */
      if (nextBtn1) {
        nextBtn1.addEventListener('click', function () {
          var ok = true;
          ['firstName', 'lastName', 'email', 'phone'].forEach(function (id) {
            var el = document.getElementById(id);
            var errEl = document.getElementById('error-' + id);
            if (el) {
              var val = el.value.trim();
              var isValid = true;
              
              if (!val) {
                isValid = false;
                if (errEl) errEl.textContent = 'Please fill required fields';
              } else if (id === 'email') {
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(val)) {
                  isValid = false;
                  if (errEl) errEl.textContent = 'Please enter a valid email address';
                }
              }
              
              if (!isValid) {
                el.style.borderColor = 'var(--ck-danger)';
                if (errEl) errEl.style.display = 'block';
                ok = false;
              } else {
                el.style.borderColor = '';
                if (errEl) errEl.style.display = 'none';
              }
            }
          });
          if (ok) showPanel(2);
        });
      }

      /* Step 2 -> 3 (AJAX Submit Shipping Data, then go to Step 3) */
      if (btnCartSubmit) {
        btnCartSubmit.onclick = function () {
          var ok = true;
          var fieldsToCheck = ['shipping_country', 'province', 'city', 'house_no', 'address', 'zip'];
          // If guest user inputs exist, validate them too
          if (document.getElementById('firstName')) fieldsToCheck.push('firstName', 'lastName', 'email', 'phone');

          fieldsToCheck.forEach(function (id) {
            var el = document.getElementById(id);
            var errEl = document.getElementById('error-' + id);
            if (el) {
              var val = el.value.trim();
              var isValid = true;
              
              if (!val) {
                isValid = false;
                if (errEl) errEl.textContent = 'Please fill required fields';
              } else if (id === 'email') {
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(val)) {
                  isValid = false;
                  if (errEl) errEl.textContent = 'Please enter a valid email address';
                }
              }
              
              if (!isValid) {
                el.style.borderColor = 'var(--ck-danger)';
                if (errEl) errEl.style.display = 'block';
                ok = false;
              } else {
                el.style.borderColor = '';
                if (errEl) errEl.style.display = 'none';
              }
            }
          });
          if (!ok) return;

          var btn = this;
          btn.innerHTML = 'Processing ...';
          btn.style.opacity = '0.75';
          btn.style.pointerEvents = 'none';

          var form = document.getElementById('cartForm');
          var formData = new FormData(form);


          fetch('/cart/checkout', {
            method: 'POST',
            body: formData,
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }
          }).then(response => {
            // If response is JSON, parse it. Otherwise, return null
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
              return response.json();
            }
            return null;
          }).then(data => {
            if (data && data.order_id) {
              var existing = form.querySelector('input[name="order_id"]');
              if (existing) {
                existing.value = data.order_id;
              } else {
                var h = document.createElement('input');
                h.type = 'hidden';
                h.name = 'order_id';
                h.value = data.order_id;
                form.appendChild(h);
              }

              // Persist order_id across page reloads (e.g. after cart item removal on step 4)
              try { sessionStorage.setItem('ck_order_id', data.order_id); } catch(e) {}

              // Success: show payment options
              showPanel(3);
            } else {
              // Validation failed or other error
              var errText = (data && data.msg) ? data.msg : 'Please fill all required fields correctly (including Contact Info).';
              if (data && data.errors) {
                errText = Object.values(data.errors)[0][0]; // Show first validation error
              }
              showToast(errText);
            }
            btn.innerHTML = 'Continue to Payment';
            btn.style.opacity = '1';
            btn.style.pointerEvents = 'auto';
          }).catch(err => {
            console.error(err);
            showToast('An error occurred while saving your details. Please try again.');
            btn.innerHTML = 'Continue to Payment';
            btn.style.opacity = '1';
            btn.style.pointerEvents = 'auto';
          });
        };
      }

      /* Step 3 -> 4 */
      if (nextBtn3) {
        nextBtn3.addEventListener('click', function () {
          showPanel(4);
        });
      }

      /* Step 4 -> Submit final payment -> show Panel 5 */
      if (nextBtn4) {
        nextBtn4.addEventListener('click', function () {

        /* 1. Legal checkbox gate */
        var checkboxes = document.querySelectorAll('.legal-checkbox');
        var allChecked = true;
        checkboxes.forEach(function (cb) { if (!cb.checked) allChecked = false; });
        if (!allChecked) {
          showToast('Please check all three boxes in the Legal Confirmation section.');
          return;
        }

        /* 2. Ensure order_id exists (restored from sessionStorage if needed) */
        var form = document.getElementById('cartForm');
        var orderInput = form.querySelector('input[name="order_id"]');
        if (!orderInput || !orderInput.value) {
          try {
            var savedId = sessionStorage.getItem('ck_order_id');
            if (savedId) {
              if (orderInput) {
                orderInput.value = savedId;
              } else {
                var hid = document.createElement('input');
                hid.type = 'hidden';
                hid.name = 'order_id';
                hid.value = savedId;
                form.appendChild(hid);
              }
            } else {
              showToast('Order session expired. Please go back to Step 1 and try again.', 'warning');
              return;
            }
          } catch(e) {}
        }

        /* 3. Disable button + show loading */
        var btn = nextBtn4;
        btn.textContent = 'Processing…';
        btn.disabled = true;

        var overlay = document.getElementById('ckPageLoading');
        if (overlay) overlay.style.display = 'flex';

        /* 4. FIRST: call /cart/checkout to re-confirm the order */
        var formData = new FormData(form);

        fetch('/cart/checkout', {
          method: 'POST',
          body: formData,
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (response) {
          var ct = response.headers.get('content-type') || '';
          if (ct.includes('application/json')) return response.json();
          return null;
        })
        .then(function (data) {

          /* Update / persist order_id from fresh checkout response */
          if (data && data.order_id) {
            var oi = form.querySelector('input[name="order_id"]');
            if (oi) {
              oi.value = data.order_id;
            } else {
              var h = document.createElement('input');
              h.type = 'hidden';
              h.name = 'order_id';
              h.value = data.order_id;
              form.appendChild(h);
            }
            try { sessionStorage.setItem('ck_order_id', data.order_id); } catch(e) {}
          }

          /* 5. SECOND: submit to /payments/payment-request */
          try { sessionStorage.removeItem('ck_order_id'); } catch(e) {}
          form.action = '/payments/payment-request';
          form.submit();

        })
        .catch(function (err) {
          console.error('Checkout re-confirm error:', err);
          if (overlay) overlay.style.display = 'none';
          btn.textContent = 'Place Order';
          btn.disabled = false;
          showToast('An error occurred. Please try again.');
        });

      });
      }

      /* Setup dummy country change */
      var dummyCountryEl = document.getElementById('dummy_country_id');
      function mapCountryToCurrency(txt) {
        if (/United States|USA/i.test(txt)) return 'USD';
        else if (/United Kingdom|England|Wales|Scotland/i.test(txt)) return 'GBP';
        else if (/Canada/i.test(txt)) return 'CAD';
        else if (/Australia/i.test(txt)) return 'AUD';
        else if (/India/i.test(txt)) return 'INR';
        else if (/France|Germany|Italy|Spain|Netherlands|Belgium|Austria|Portugal/i.test(txt)) return 'EUR';
        return null;
      }
      if (dummyCountryEl) {
        dummyCountryEl.addEventListener('change', function () {
          var map = mapCountryToCurrency(this.options[this.selectedIndex].text);
          if (map) changeCurrency(map, false);
        });
      }

      /* Init */
      var urlParams = new URLSearchParams(window.location.search);
      var serverStep = {{ isset($step) ? $step : 'null' }};
      var initStep = serverStep ? serverStep : (urlParams.get('step') ? parseInt(urlParams.get('step')) : 1);

      // Restore order_id from sessionStorage if page was reloaded (e.g. after cart item removal on step 4)
      (function restoreOrderId() {
        try {
          var savedOrderId = sessionStorage.getItem('ck_order_id');
          if (savedOrderId) {
            var form = document.getElementById('cartForm');
            if (form) {
              var existing = form.querySelector('input[name="order_id"]');
              if (existing) {
                existing.value = savedOrderId;
              } else {
                var h = document.createElement('input');
                h.type = 'hidden';
                h.name = 'order_id';
                h.value = savedOrderId;
                form.appendChild(h);
              }
            }
          }
        } catch(e) {}
      })();

      showPanel(initStep);

      // Set gateway logic
      if (document.getElementById('autoGatewayInput')) {
        if (nextBtn3) nextBtn3.disabled = false;
      }

    }());

    /* ── Delete confirmation modal ────────────────────── */
    var _deleteCartId = null;
    var _deleteRow = null;

    function removeCartItem(btn, cartId) {
      _deleteCartId = cartId;
      _deleteRow = btn.closest('.ck-item') || btn.closest('.ck-row') || btn.parentNode.parentNode;
      document.getElementById('deleteModal').classList.add('visible');

      var confirmBtn = document.getElementById('confirmDeleteBtn');
      confirmBtn.onclick = function () {
        closeDeleteModal();
        doDelete(cartId);
      };
    }

    function closeDeleteModal() {
      document.getElementById('deleteModal').classList.remove('visible');
    }

    function doDelete(cartId) {
      var overlay = document.getElementById('ckPageLoading');
      if (overlay) overlay.style.display = 'flex';

      fetch('/cart/' + cartId + '/delete', {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        }
      })
      .then(function(response) {
        var contentType = response.headers.get('content-type') || '';
        if (contentType.indexOf('application/json') !== -1) {
          return response.json();
        }
        return { success: true };
      })
      .then(function(data) {
        if (data.success || data.success === undefined) {
          if (_deleteRow) _deleteRow.remove();
          checkEmpty();
          /* Reload staying on Step 4 */
          var url = new URL(window.location.href);
          url.searchParams.set('step', '4');
          window.location.href = url.toString();
        } else {
          if (overlay) overlay.style.display = 'none';
          showToast('Failed to remove item. Please try again.');
        }
      })
      .catch(function(error) {
        console.error('Delete error:', error);
        var url = new URL(window.location.href);
        url.searchParams.set('step', '4');
        window.location.href = url.toString();
      });
    }

    function checkEmpty() {
      var remaining = document.querySelectorAll('#panel4 .ck-item, #panel4 [style*="border-bottom:1px solid var(--ck-border)"]').length;
      if (remaining === 0) {
        var emptyScreen = document.getElementById('emptyCartScreen');
        if (emptyScreen) {
          emptyScreen.style.display = 'block';
          document.getElementById('cartForm').style.display = 'none';
          document.querySelector('.ck-steps').style.display = 'none';
          document.getElementById('ckStepLabel').style.display = 'none';
        }
      }
    }

    window.toggleBillingFields = function (sameAsShipping) {
      var fields = document.getElementById('billingFields');
      if (fields) fields.style.display = sameAsShipping ? 'none' : 'block';
    };

    window.calculateShipping = function () {
      var country = document.getElementById('shipping_country') ? document.getElementById('shipping_country').value : '';
      var city = document.getElementById('city') ? document.getElementById('city').value : '';
      var zip = document.getElementById('zip') ? document.getElementById('zip').value : '';
      var phone = document.getElementById('phone') ? document.getElementById('phone').value : '';
      var province = document.getElementById('province') ? document.getElementById('province').value : '';
      if (!country) return;
      fetch('/cart/calculate-shipping', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
          country_id: country, city_name: city,
          phone: phone, province_name: province, zip_code: zip
        })
      })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          if (data.shipping_cost !== undefined) {
            baseShipping = parseFloat(data.shipping_cost);
            var sc = document.getElementById('shipping_cost');
            if (sc) sc.value = data.shipping_cost;
            updateDomTotals();
          }
        });
    };
  </script>
  <script src="/assets/default/js/parts/get-regions.min.js"></script>
  <script src="/assets/default/js/parts/cart.min.js"></script>
  <script src="/assets/default/js/parts/payment.min.js"></script>
@endpush