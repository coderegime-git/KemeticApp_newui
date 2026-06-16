@extends('web.default.layouts.app')

@section('content')
  <style>
    /* =========================================================
     KEMETIC CHECKOUT  –  cart.blade.php  (Steps 1 & 2)
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
    .ck-wrap {
      width: 100%;
      background: radial-gradient(ellipse 130% 50% at 50% -10%, #2A0C52 0%, #0D0B14 70%);
      min-height: 100vh;
      color: var(--ck-text);
      font-family: Arial, Helvetica, sans-serif;
      padding: 32px 20px 80px;
    }

    .ck-inner {
      max-width: 1100px;
      margin: 0 auto;
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
      margin: 0 0 16px;
      color: #fff;
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
    .ck-cta,
    .ck-btn {
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

    .ck-cta:hover,
    .ck-btn:hover {
      filter: brightness(1.08);
    }

    .ck-invalid {
      font-size: 11px;
      color: var(--ck-danger);
      margin-top: 4px;
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

    /* Step panels */
    .ck-panel {
      display: none;
    }

    .ck-panel.active {
      display: block;
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

        {{-- ================================
        PANEL 1: STEP 1 — CONTACT INFO
        ================================ --}}
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
                    <div class="ck-invalid ck-client-error" id="error-firstName" style="display:none;">Please fill
                      required fields</div>
                    @error('first_name')<div class="ck-invalid">{{ $message }}</div>@enderror
                  </div>
                  <div class="ck-field">
                    <label for="lastName">{{ trans('update.last_name') }} <span
                        style="color:var(--ck-danger);">*</span></label>
                    <input id="lastName" name="last_name" type="text"
                      class="ck-input @error('last_name') is-invalid @enderror" required
                      value="{{ !empty($user) ? $user->last_name : '' }}">
                    <div class="ck-invalid ck-client-error" id="error-lastName" style="display:none;">Please fill required
                      fields</div>
                    @error('last_name')<div class="ck-invalid">{{ $message }}</div>@enderror
                  </div>
                </div>
                <div class="ck-field">
                  <label for="email">{{ trans('update.email') }} <span style="color:var(--ck-danger);">*</span></label>
                  <input id="email" name="email" type="email" class="ck-input @error('email') is-invalid @enderror"
                    required value="{{ !empty($user) ? $user->email : '' }}">
                  <div class="ck-invalid ck-client-error" id="error-email" style="display:none;">Please fill required
                    fields</div>
                  @error('email')<div class="ck-invalid">{{ $message }}</div>@enderror
                </div>
                <div class="ck-field">
                  <label for="phone">Phone Number <span style="color:var(--ck-danger);">*</span></label>
                  <input id="phone" name="phone" type="text" class="ck-input @error('phone') is-invalid @enderror"
                    required min-length="6" value="{{ !empty($user) ? $user->mobile : '' }}" maxlength="15" oninput="this.value=this.value.replace(/[^0-9]/g,''); if(this.value.length > 15) this.value = this.value.slice(0, 15);">
                  <div class="ck-invalid ck-client-error" id="error-phone" style="display:none;">Please fill required
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

        {{-- ================================
        PANEL 2: STEP 2 — SHIPPING
        ================================ --}}
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
                  <div class="ck-invalid ck-client-error" id="error-shipping_country" style="display:none;">Please fill
                    required fields</div>
                  @error('country_id')<div class="ck-invalid">{{ $message }}</div>@enderror
                </div>

                <div class="ck-row">
                  <div class="ck-field">
                    <label for="province">Province / State <span style="color:var(--ck-danger);">*</span></label>
                    <input id="province" name="province_name" type="text"
                      class="ck-input @error('province_name') is-invalid @enderror"
                      value="{{ !empty($user) ? $user->province_name : '' }}">
                    <div class="ck-invalid ck-client-error" id="error-province" style="display:none;">Please fill required
                      fields</div>
                    @error('province_name')<div class="ck-invalid">{{ $message }}</div>@enderror
                  </div>
                  <div class="ck-field">
                    <label for="city">{{ trans('update.city') }} <span style="color:var(--ck-danger);">*</span></label>
                    <input id="city" name="city_name" type="text"
                      class="ck-input @error('city_name') is-invalid @enderror"
                      value="{{ !empty($user) ? $user->city_name : '' }}">
                    <div class="ck-invalid ck-client-error" id="error-city" style="display:none;">Please fill required
                      fields</div>
                    @error('city_name')<div class="ck-invalid">{{ $message }}</div>@enderror
                  </div>
                </div>

                <div class="ck-field">
                  <label for="house_no">House No. <span style="color:var(--ck-danger);">*</span></label>
                  <input id="house_no" name="house_no" type="text"
                    class="ck-input @error('house_no') is-invalid @enderror"
                    value="{{ !empty($user) ? $user->house_no : '' }}">
                  <div class="ck-invalid ck-client-error" id="error-house_no" style="display:none;">Please fill required
                    fields</div>
                  @error('house_no')<div class="ck-invalid">{{ $message }}</div>@enderror
                </div>

                <div class="ck-field">
                  <label for="address">{{ trans('update.address') }} <span
                      style="color:var(--ck-danger);">*</span></label>
                  <textarea id="address" name="address" rows="3"
                    class="ck-input @error('address') is-invalid @enderror">{{ !empty($user) ? $user->address : '' }}</textarea>
                  <div class="ck-invalid ck-client-error" id="error-address" style="display:none;">Please fill required
                    fields</div>
                  @error('address')<div class="ck-invalid">{{ $message }}</div>@enderror
                </div>

                <div class="ck-field">
                  <label for="zip">ZIP / Postal Code <span style="color:var(--ck-danger);">*</span></label>
                  <input id="zip" name="zip_code" type="text" class="ck-input @error('zip_code') is-invalid @enderror"
                    value="{{ !empty($user) ? $user->zip_code : '' }}" maxlength="10" oninput="if(this.value.length > 10) this.value = this.value.slice(0, 10);">
                  <div class="ck-invalid ck-client-error" id="error-zip" style="display:none;">Please fill required fields
                  </div>
                  @error('zip_code')<div class="ck-invalid">{{ $message }}</div>@enderror
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
                <button type="button" class="ck-btn" onclick="submitCart(this)">Continue to Payment</button>
                <div class="ck-trust">
                  <span>🔒 SSL Secure</span>
                  <span>Stripe Protected</span>
                  <span>Express Shipping</span>
                </div>
              </section>
            </div>
          </div>
        </div>

      </form>
    </div>
  </div>
@endsection

@push('scripts_bottom')
  <script>
    /* =====================================================================
       CART CHECKOUT  –  Globals (currency, totals)
       Must be defined BEFORE the IIFE so both scopes share them
       ===================================================================== */

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

    /* Force Step 2 shipping country to user's profile country on load */
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
      /* highlight pill */
      document.querySelectorAll('.ck-currency-pill').forEach(function (el) {
        el.classList.remove('active');
      });
      var pill = document.querySelector('.ck-currency-pill[data-currency="' + currency + '"]');
      if (pill) pill.classList.add('active');
      /* instant price update */
      updateDomTotals();
      /* persist in background */
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
       CART CHECKOUT  –  Step / Panel logic (IIFE keeps vars private)
       ===================================================================== */
    (function () {

      /* ── Show a panel (1 or 2) ─────────────────────────────────────── */
      function showPanel(panel) {
        /* hide both panels */
        ['panel1', 'panel2'].forEach(function (id) {
          var el = document.getElementById(id);
          if (el) el.style.display = 'none';
        });
        /* show target */
        var target = document.getElementById('panel' + panel);
        if (target) target.style.display = 'block';

        /* update step-dot circles (IDs: step-dot-1 … step-dot-5) */
        for (var i = 1; i <= 5; i++) {
          var dot = document.getElementById('step-dot-' + i);
          if (!dot) continue;
          dot.classList.remove('active', 'done');
          if (i < panel) dot.classList.add('done');
          if (i === panel) dot.classList.add('active');
        }

        /* label */
        var lbl = document.getElementById('ckStepLabel');
        if (lbl) lbl.textContent = 'Step ' + panel + ' of 5';

        /* back button */
        var backBtn = document.getElementById('ckBackBtn');
        if (backBtn) {
          if (panel === 1) {
            backBtn.style.visibility = 'hidden';
            backBtn.onclick = function (e) { e.preventDefault(); };
          } else {
            backBtn.style.visibility = 'visible';
            (function (p) {
              backBtn.onclick = function () { showPanel(p - 1); };
            }(panel));
          }
        }

        /* update URL so refresh restores correct step */
        history.replaceState(null, '',
          panel === 1 ? window.location.pathname : '?step=' + panel
        );
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
      window.showPanel = showPanel;

      /* ── Submit Step-2 with loader ─────────────────────────────────── */
      window.submitCart = function (btn) {
        var ok = true;
        ['shipping_country', 'province', 'city', 'house_no', 'address', 'zip'].forEach(function (id) {
          var el = document.getElementById(id);
          var errEl = document.getElementById('error-' + id);
          if (el && !el.value.trim()) {
            el.style.borderColor = 'var(--ck-danger)';
            if (errEl) errEl.style.display = 'block';
            ok = false;
          } else if (el) {
            el.style.borderColor = '';
            if (errEl) errEl.style.display = 'none';
          }
        });
        if (!ok) return;

        btn.innerHTML = 'Processing ...';
        btn.style.opacity = '0.75';
        btn.style.pointerEvents = 'none';

        // Force enable all fields before submit so they are included in POST
        var selects = document.querySelectorAll('#cartForm select');
        for (var i = 0; i < selects.length; i++) {
          selects[i].disabled = false;
        }

        document.getElementById('cartForm').submit();
      };

      /* ── Step 1 → Step 2 validation ────────────────────────────────── */
      var btn1 = document.getElementById('ckNextBtn1');
      if (btn1) {
        btn1.addEventListener('click', function () {
          var ok = true;
          ['firstName', 'lastName', 'email', 'phone'].forEach(function (id) {
            var el = document.getElementById(id);
            var errEl = document.getElementById('error-' + id);
            if (el && !el.value.trim()) {
              el.style.borderColor = 'var(--ck-danger)';
              if (errEl) errEl.style.display = 'block';
              ok = false;
            } else if (el) {
              el.style.borderColor = '';
              if (errEl) errEl.style.display = 'none';
            }
          });
          var phone = document.getElementById('phone');
          if (phone && phone.value.trim() && phone.value.replace(/\D/g, '').length < 6) {
            phone.style.borderColor = 'var(--ck-danger)';
            var errElPhone = document.getElementById('error-phone');
            if (errElPhone) {
              errElPhone.textContent = 'Phone number must be at least 6 digits.';
              errElPhone.style.display = 'block';
            }
            ok = false;
          }
          if (ok) showPanel(2);
        });
      }

      /* ── Step 1 Dummy Country → changes CURRENCY only (never shipping) ── */
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
        /* Auto-apply currency on page load based on default Netherlands */
        var initMap = mapCountryToCurrency(
          dummyCountryEl.options[dummyCountryEl.selectedIndex]
            ? dummyCountryEl.options[dummyCountryEl.selectedIndex].text
            : ''
        );
        if (initMap) changeCurrency(initMap, false);
      }

      /* ── Shipping cost recalc — reads Step 2 (user's real country) ── */
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

      /* ── Step 2 country change → recalc shipping ─────────────────────── */
      var shippingCountryEl = document.getElementById('shipping_country');
      if (shippingCountryEl) {
        shippingCountryEl.addEventListener('change', function () {
          window.calculateShipping && window.calculateShipping();
        });
      }

      /* ── Init: restore step from URL ────────────────────────────────── */
      var step = parseInt(new URLSearchParams(window.location.search).get('step')) || 1;
      showPanel(step);


    }());

    /* ── Billing address toggle ───────────────────────────────────── */
    window.toggleBillingFields = function (sameAsShipping) {
      var fields = document.getElementById('billingFields');
      if (fields) fields.style.display = sameAsShipping ? 'none' : 'block';
    };
  </script>
  <script>
    var couponInvalidLng = '{{ trans('cart.coupon_invalid') }}';
    var selectProvinceLang = '{{ trans('update.select_province') }}';
    var selectCityLang = '{{ trans('update.select_city') }}';
    var selectDistrictLang = '{{ trans('update.select_district') }}';
  </script>
  <script src="/assets/default/js/parts/get-regions.min.js"></script>
  <script src="/assets/default/js/parts/cart.min.js"></script>
@endpush