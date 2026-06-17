@extends('web.default.layouts.app')

@section('content')
  <style>
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
    * { box-sizing: border-box; }

    .ck-wrap {
      width: 100%; max-width: 100%; overflow-x: hidden;
      background: radial-gradient(ellipse 130% 50% at 50% -10%, #2A0C52 0%, #0D0B14 70%);
      min-height: 100vh; color: var(--ck-text);
      font-family: Arial, Helvetica, sans-serif; padding: 32px 20px 80px;
    }
    .ck-inner { max-width: 1100px; width: 100%; margin: 0 auto; overflow: hidden; }

    /* Header */
    .ck-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
    .ck-back-btn {
      width: 40px; height: 40px; border-radius: 12px;
      border: 1px solid rgba(212,175,55,.45); background: rgba(18,14,31,.8);
      color: var(--ck-gold); font-size: 24px; line-height: 1;
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; text-decoration: none;
    }
    .ck-logo { color: var(--ck-gold); font-size: 26px; font-weight: 900; letter-spacing: 4px; }
    .ck-secure-badge {
      border: 1px solid rgba(212,175,55,.45); color: var(--ck-gold);
      background: rgba(18,14,31,.8); border-radius: 12px; padding: 8px 16px; font-size: 13px;
    }

    /* Step indicator */
    .ck-step-label { font-size: 14px; color: var(--ck-muted); margin-bottom: 12px; }
    .ck-steps {
      display: flex; flex-direction: row; align-items: flex-start;
      justify-content: center; gap: 0; margin-bottom: 32px;
      position: relative; max-width: 600px;
    }
    .ck-steps::before {
      content: ''; position: absolute; top: 16px; left: 24px; right: 24px;
      height: 1px; background: var(--ck-border); z-index: 0;
    }
    .ck-step { display: flex; flex-direction: column; align-items: center; gap: 6px; z-index: 1; flex: 1; }
    .ck-step-circle {
      width: 34px; height: 34px; border-radius: 50%;
      border: 2px solid rgba(255,255,255,.18); background: #120E1F;
      display: flex; align-items: center; justify-content: center;
      font-size: 13px; font-weight: 700; color: rgba(255,255,255,.35); transition: all .25s;
    }
    .ck-step.done .ck-step-circle { background: rgba(155,53,255,.2); border-color: var(--ck-purple); color: var(--ck-purple); }
    .ck-step.active .ck-step-circle { background: var(--ck-purple); border-color: var(--ck-purple); color: #fff; box-shadow: 0 0 16px rgba(155,53,255,.6); }
    .ck-step-name { font-size: 11px; color: rgba(255,255,255,.28); text-align: center; white-space: nowrap; }
    .ck-step.active .ck-step-name, .ck-step.done .ck-step-name { color: var(--ck-text); }

    /* Layout */
    .ck-layout { display: grid; grid-template-columns: 1fr 400px; gap: 24px; align-items: start; }
    @media(max-width:900px) { .ck-layout { grid-template-columns: 1fr; } }
    @media(max-width:600px) {
      .ck-wrap { padding: 16px 12px 80px; }
      .ck-section { padding: 16px 14px; }
      .ck-layout > div { min-width: 0; width: 100%; overflow: hidden; }
    }

    /* Section card */
    .ck-section {
      background: var(--ck-surface); border: 1px solid var(--ck-border);
      border-radius: 16px; padding: 24px; margin-bottom: 20px; backdrop-filter: blur(10px);
    }
    .ck-section-title { font-size: 18px; font-weight: 700; margin: 0 0 8px; color: #fff; }
    .ck-section-sub { font-size: 13px; color: var(--ck-muted); margin: 0 0 20px; }

    /* Cart items */
    .ck-item { display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px solid var(--ck-border); }
    .ck-item:last-child { border-bottom: none; }
    .ck-item-thumb { width: 60px; height: 60px; border-radius: 10px; overflow: hidden; flex-shrink: 0; background: rgba(155,53,255,.15); }
    .ck-item-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .ck-item-meta { flex: 1; min-width: 0; }
    .ck-item-title { font-size: 14px; font-weight: 600; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .ck-item-sub { font-size: 12px; color: var(--ck-muted); margin-top: 4px; }
    .ck-item-price { font-size: 15px; font-weight: 700; color: var(--ck-gold); white-space: nowrap; }

    /* Summary sidebar */
    .ck-sidebar .ck-section { margin-bottom: 0; }
    .ck-summary-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid var(--ck-border); font-size: 15px; }
    .ck-summary-row:last-child { border-bottom: none; }
    .ck-summary-row.total { font-size: 17px; font-weight: 800; color: var(--ck-gold); padding-top: 14px; }

    /* Trust bar */
    .ck-trust { display: flex; align-items: center; justify-content: space-between; background: rgba(255,255,255,.04); border: 1px solid var(--ck-border); border-radius: 10px; padding: 12px 16px; font-size: 12px; color: var(--ck-muted); margin-top: 16px; }

    /* CTA button */
    .ck-cta {
      width: 100%; border: none; border-radius: 14px; padding: 17px;
      background: linear-gradient(135deg, var(--ck-gold), var(--ck-gold2));
      color: #0D0B14; font-size: 16px; font-weight: 900; letter-spacing: 1px;
      cursor: pointer; text-transform: uppercase; transition: filter .2s;
      margin-top: 16px; display: block; text-align: center;
    }
    .ck-cta:hover { filter: brightness(1.08); }
    .ck-cta:disabled { opacity: .5; cursor: not-allowed; }

    /* Inputs */
    .ck-field { margin-bottom: 14px; }
    .ck-field label { display: block; font-size: 12px; color: var(--ck-muted); text-transform: uppercase; letter-spacing: .7px; margin-bottom: 6px; }
    .ck-input {
      width: 100%; background: var(--ck-input); border: 1px solid rgba(255,255,255,.12);
      border-radius: 12px; padding: 13px 16px; color: var(--ck-text); font-size: 14px;
      outline: none; transition: border-color .2s; -webkit-appearance: none; appearance: none;
    }
    .ck-input:focus { border-color: var(--ck-purple); }
    .ck-input option { background: #1A1430; }
    textarea.ck-input { resize: vertical; }
    .ck-row { display: flex; gap: 12px; }
    .ck-row .ck-field { flex: 1; }

    /* Currency Pills */
    .ck-currency-list { display: flex; flex-wrap: wrap; gap: 10px; }
    .ck-currency-pill {
      border: 1px solid rgba(255,255,255,.12); background: var(--ck-input); color: var(--ck-muted);
      padding: 12px 18px; border-radius: 24px; font-size: 12px; font-weight: 700;
      cursor: pointer; transition: all .2s; display: inline-flex; align-items: center; gap: 6px;
    }
    .ck-currency-pill:hover { border-color: var(--ck-purple); color: #fff; }
    .ck-currency-pill.active { border-color: var(--ck-gold); color: var(--ck-gold); }

    /* Panels */
    .ck-panel { display: none; }
    .ck-panel.active { display: block; }

    /* Toast */
    .ck-toast {
      position: fixed; bottom: 30px; right: 30px; background: #FF6B6B; color: #fff;
      padding: 16px 24px; border-radius: 12px; font-size: 14px; font-weight: 600;
      box-shadow: 0 10px 30px rgba(255,107,107,0.4); transform: translateY(100px);
      opacity: 0; transition: all 0.3s cubic-bezier(0.68,-0.55,0.265,1.55);
      z-index: 999999; display: flex; align-items: center; gap: 12px; pointer-events: none;
    }
    .ck-toast.show { transform: translateY(0); opacity: 1; }
    .ck-toast.success { background: #22c55e; box-shadow: 0 10px 30px rgba(34,197,94,0.4); }

    /* Loading overlay */
    .ck-page-loading {
      position: fixed; inset: 0; z-index: 9999998; background: rgba(10,6,20,0.92);
      display: none; flex-direction: column; align-items: center; justify-content: center; gap: 20px;
    }
    .ck-page-loading-spinner {
      width: 52px; height: 52px; border: 4px solid rgba(155,53,255,0.2);
      border-top-color: #9B35FF; border-radius: 50%; animation: ck-spin 0.8s linear infinite;
    }
    .ck-page-loading-text { color: #A89EC4; font-size: 15px; letter-spacing: 1px; }
    @keyframes ck-spin { to { transform: rotate(360deg); } }

    /* Delete modal */
    .ck-modal-overlay {
      position: fixed; inset: 0; z-index: 99999; background: rgba(0,0,0,.65);
      backdrop-filter: blur(6px); display: flex; align-items: center; justify-content: center;
      opacity: 0; pointer-events: none; transition: opacity .25s;
    }
    .ck-modal-overlay.visible { opacity: 1; pointer-events: auto; }
    .ck-modal {
      background: linear-gradient(160deg, #1E0D35, #120E1F); border: 1px solid rgba(155,53,255,.35);
      border-radius: 24px; padding: 36px 30px 28px; max-width: 380px; width: calc(100% - 40px);
      text-align: center; transform: scale(.92); transition: transform .25s; box-shadow: 0 20px 60px rgba(0,0,0,.6);
    }
    .ck-modal-overlay.visible .ck-modal { transform: scale(1); }
    .ck-modal-icon { font-size: 44px; margin-bottom: 14px; display: block; }
    .ck-modal h3 { color: #fff; font-size: 20px; font-weight: 800; margin: 0 0 8px; }
    .ck-modal p { color: var(--ck-muted); font-size: 14px; line-height: 1.6; margin: 0 0 24px; }
    .ck-modal-btns { display: flex; gap: 10px; }
    .ck-modal-btns button { flex: 1; border: none; border-radius: 14px; padding: 14px; font-size: 14px; font-weight: 700; cursor: pointer; transition: filter .2s; }
    .ck-modal-btn-cancel { background: rgba(255,255,255,.08); color: var(--ck-text); border: 1px solid rgba(255,255,255,.12) !important; }
    .ck-modal-btn-delete { background: linear-gradient(135deg, #FF6B6B, #ff3b3b); color: #fff; }

    /* Empty cart */
    .ck-empty-cart { display: none; text-align: center; padding: 60px 20px; }
    .ck-empty-cart .ck-empty-icon { font-size: 64px; margin-bottom: 20px; display: block; }
    .ck-empty-cart h3 { color: #fff; font-size: 22px; font-weight: 800; margin: 0 0 10px; }
    .ck-empty-cart p { color: var(--ck-muted); font-size: 14px; line-height: 1.6; margin: 0 0 28px; }
    .ck-go-app {
      display: inline-block; padding: 15px 36px;
      background: linear-gradient(135deg, var(--ck-gold), var(--ck-gold2));
      color: #0D0B14; font-size: 15px; font-weight: 900; border-radius: 14px;
      text-decoration: none; text-transform: uppercase; letter-spacing: 1px; transition: filter .2s;
    }
    .ck-go-app:hover { filter: brightness(1.08); color: #0D0B14; }

    /* Status Card */
    .ck-status-container { max-width: 500px; margin: 0 auto; }
    .ck-status-card {
      background: var(--ck-surface); border: 1px solid var(--ck-border);
      border-radius: 18px; padding: 40px 30px 30px; text-align: center;
      backdrop-filter: blur(12px); margin-bottom: 20px;
    }
    .ck-sparkle { font-size: 40px; display: block; margin-bottom: 20px; }
    .ck-status-card h2 { font-size: 24px; font-weight: 800; color: var(--ck-gold); margin: 0 0 12px; }
    .ck-status-card p { font-size: 15px; color: var(--ck-muted); line-height: 1.65; margin: 0 0 30px; }
    .ck-status-card.failed h2 { color: var(--ck-danger); }
  </style>

  <div class="ck-wrap">
    <div class="ck-inner">

      {{-- HEADER --}}
      <header class="ck-header">
        <a href="javascript:void(0)" id="ckBackBtn" class="ck-back-btn">&#8249;</a>
        <!-- <div class="ck-logo">KEMETIC</div> -->
        <div class="ck-secure-badge">&#128274; Secure</div>
      </header>

      {{-- STEP INDICATOR --}}
      <div class="ck-step-label" id="ckStepLabel">Step 1 of 4</div>
      <div class="ck-steps">
        <div class="ck-step active" id="step-dot-1">
          <div class="ck-step-circle">1</div><span class="ck-step-name">Review</span>
        </div>
        <div class="ck-step" id="step-dot-2">
          <div class="ck-step-circle">2</div><span class="ck-step-name">Info</span>
        </div>
        <div class="ck-step" id="step-dot-3">
          <div class="ck-step-circle">3</div><span class="ck-step-name">Details</span>
        </div>
        <div class="ck-step" id="step-dot-4">
          <div class="ck-step-circle">4</div><span class="ck-step-name">Done</span>
        </div>
      </div>

      <form action="/cart/checkout" method="post" id="cartForm">
        {{ csrf_field() }}
        <input type="hidden" name="discount_id" value="">
        <input type="hidden" id="shipping_cost" name="shipping_cost" value="{{ $productDeliveryFee ?? 0 }}">
        <input type="hidden" id="checkoutAmount" name="amount" value="">
        <input type="hidden" id="checkoutCurrency" name="currency" value="">

        @php
          $isMultiCurrency = !empty(getFinancialCurrencySettings('multi_currency'));
          $userCurrency = currency();
          $invalidChannels = [];
        @endphp

        @if(empty($step) || $step != 4)

          {{-- ===================== STEP 1: REVIEW ===================== --}}
          <div class="ck-panel active" id="panel1">
            <div class="ck-layout">

              {{-- LEFT: Cart Items --}}
              <div>
                <section class="ck-section">
                  <h2 class="ck-section-title">Your Items</h2>

                  @foreach($carts as $cart)
                    @php
                      $cartItemInfo = $cart->getItemInfo();
                      if (empty($cartItemInfo)) continue;
                      $cartTaxType = !empty($cartItemInfo['isProduct']) ? 'store' : 'general';
                      $qty = !empty($cartItemInfo['quantity']) ? $cartItemInfo['quantity'] : 1;
                      $unitPrice = !empty($cartItemInfo['price']) ? $cartItemInfo['price'] : 0;
                      $lineTotal = $qty * $unitPrice;
                    @endphp
                    <div style="display:flex;gap:12px;padding:20px 0;border-bottom:1px solid var(--ck-border);align-items:flex-start;width:100%;overflow:hidden;">
                      <div style="width:64px;height:64px;min-width:64px;border-radius:10px;overflow:hidden;background:rgba(155,53,255,.15);">
                        @if(!empty($cartItemInfo['imgPath']))
                          <img src="{{ $cartItemInfo['imgPath'] }}" alt="{{ $cartItemInfo['title'] ?? '' }}" style="width:100%;height:100%;object-fit:cover;">
                        @endif
                      </div>
                      <div style="flex:1;min-width:0;overflow:hidden;">
                        <div style="font-size:14px;font-weight:700;color:#fff;margin-bottom:3px;word-break:break-word;line-height:1.3;">{{ $cartItemInfo['title'] ?? '' }}</div>
                        <div style="font-size:12px;color:var(--ck-purple);margin-bottom:3px;">
                          @if(!empty($cartItemInfo['quantity'])) Product @else Course @endif
                        </div>
                        <div style="font-size:12px;color:var(--ck-muted);margin-bottom:14px;">
                          Vendor:&nbsp;{{ $cartItemInfo['teacherName'] ?? 'Abundance Shop' }}
                        </div>
                        <div
                          style="font-size:11px;color:var(--ck-muted);margin-bottom:14px;display:flex;align-items:center;gap:4px;">
                          <span>🔴 🟠 🟡 🟢 🔵 🟣</span>
                          <!-- <span>·</span> -->
                          <!-- <span style="color:var(--ck-gold);font-size:12px;">★</span> -->
                          <!-- <span>2 Global -->
                            <!-- {{ $cartItemInfo['rate'] ?? '0' }} -->
                          <!-- </span> -->
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;width:100%;">
                          <div style="display:flex;align-items:center;gap:8px;font-size:14px;color:var(--ck-text);">
                            <span>Qty</span>
                            <strong style="color:#fff;">{{ $qty }}</strong>
                          </div>
                          <button type="button" onclick="removeCartItem(this, {{ $cart->id }})"
                            style="background:rgba(255,107,107,0.12);border:1.5px solid #FF6B6B;border-radius:50%;width:34px;height:34px;min-width:34px;display:flex;align-items:center;justify-content:center;color:#FF6B6B;cursor:pointer;margin-left:auto;flex-shrink:0;"
                            title="Remove">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#FF6B6B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                              <polyline points="3 6 5 6 21 6"></polyline>
                              <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                              <line x1="10" y1="11" x2="10" y2="17"></line>
                              <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>
                          </button>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;width:100%;font-size:14px;color:var(--ck-text);">
                          <span>Unit Price</span>
                          <strong class="cart-item-unit-price" data-base="{{ $unitPrice }}" style="color:var(--ck-gold);white-space:nowrap;margin-right:8px;">
                            {{ handlePrice($unitPrice, true, true, false, null, true, $cartTaxType) }}
                          </strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;width:100%;font-size:14px;color:var(--ck-text);">
                          <span>Line Total</span>
                          <strong class="cart-item-line-total" data-base="{{ $lineTotal }}" style="color:var(--ck-gold);white-space:nowrap;margin-right:8px;">
                            {{ handlePrice($lineTotal, true, true, false, null, true, $cartTaxType) }}
                          </strong>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </section>



                {{-- Need Help? --}}
                <section class="ck-section">
                  <h2 class="ck-section-title">Need Help?</h2>
                  <p style="font-size:13px;color:var(--ck-muted);line-height:1.6;margin-bottom:16px;">
                    If your payment fails, try another currency or contact a human agent.
                  </p>
                  <a href="https://chat.whatsapp.com/FfWt50zLTax1DUZCqJK59Q?mode=gi_t" target="_blank"
                    style="display:flex;justify-content:center;align-items:center;gap:8px;padding:14px 20px;border-radius:14px;border:1px solid #25D366;color:#25D366;background:rgba(37,211,102,.08);font-size:14px;font-weight:700;text-decoration:none;transition:filter .2s;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Talk to a Human Agent
                  </a>
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
                    <div class="ck-summary-row" style="color:#00E676;">
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
                  <button type="button" id="ckNextBtn1" class="ck-cta">Continue to Info</button>
                  <div class="ck-trust">
                    <span>&#128274; 100% Secure</span>
                    <span>Stripe Protected</span>
                    <span>SSL</span>
                  </div>
                </section>
              </div>

            </div>
          </div>

          {{-- ===================== STEP 2: INFO ===================== --}}
          <div class="ck-panel" id="panel2">
            <div class="ck-layout">

              {{-- LEFT: Contact + Country + Currency --}}
              <div>
                {{-- Country / Region --}}
                <section class="ck-section">
                  <h2 class="ck-section-title" style="font-size:14px;color:var(--ck-gold);letter-spacing:1px;text-transform:uppercase;">&#127758; Country / Region</h2>
                  <div class="ck-field" style="margin-bottom:10px;">
                    <select id="dummy_country_id" name="dummy_country_id" class="ck-input">
                      <option value="">{{ trans('update.select_country') }}</option>
                      @if(!empty($countries))
                        @foreach($countries as $country)
                          @php $isSelected = stripos($country->title, 'Netherlands') !== false; @endphp
                          <option value="{{ $country->id }}" {{ $isSelected ? 'selected' : '' }}>{{ $country->title }}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                  <p style="font-size:11px;color:var(--ck-muted);margin:0;">Your country determines taxes, delivery options and local payment methods.</p>
                </section>

                {{-- Currency --}}
                @php
                  $multiCurrencyMixin = new \App\Mixins\Financial\MultiCurrency();
                  $currencies = $multiCurrencyMixin->getCurrencies()->unique('currency');
                  $userCurrency = currency();
                @endphp
                @if(!empty($currencies) && count($currencies))
                  <section class="ck-section">
                    <h2 class="ck-section-title" style="font-size:14px;color:var(--ck-gold);letter-spacing:1px;text-transform:uppercase;">&#128338; Currency</h2>
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

                {{-- Contact Info --}}
                <section class="ck-section">
                  <h2 class="ck-section-title" style="font-size:14px;color:var(--ck-gold);letter-spacing:1px;text-transform:uppercase;">&#128100; Contact Information</h2>
                  <div class="ck-row">
                    <div class="ck-field">
                      <label for="firstName">{{ trans('update.first_name') }} <span style="color:var(--ck-danger);">*</span></label>
                      <input id="firstName" name="first_name" type="text" class="ck-input @error('first_name') is-invalid @enderror" required value="{{ !empty($user) ? $user->first_name : '' }}">
                      <div class="ck-invalid ck-client-error" id="error-firstName" style="display:none;color:var(--ck-danger);font-size:12px;margin-top:4px;">Please fill required fields</div>
                      @error('first_name')<div class="ck-invalid">{{ $message }}</div>@enderror
                    </div>
                    <div class="ck-field">
                      <label for="lastName">{{ trans('update.last_name') }} <span style="color:var(--ck-danger);">*</span></label>
                      <input id="lastName" name="last_name" type="text" class="ck-input @error('last_name') is-invalid @enderror" required value="{{ !empty($user) ? $user->last_name : '' }}">
                      <div class="ck-invalid ck-client-error" id="error-lastName" style="display:none;color:var(--ck-danger);font-size:12px;margin-top:4px;">Please fill required fields</div>
                      @error('last_name')<div class="ck-invalid">{{ $message }}</div>@enderror
                    </div>
                  </div>
                  <div class="ck-field">
                    <label for="email">{{ trans('update.email') }} <span style="color:var(--ck-danger);">*</span></label>
                    <input id="email" name="email" type="email" class="ck-input @error('email') is-invalid @enderror" required value="{{ !empty($user) ? $user->email : '' }}">
                    <div class="ck-invalid ck-client-error" id="error-email" style="display:none;color:var(--ck-danger);font-size:12px;margin-top:4px;">Please fill required fields</div>
                    @error('email')<div class="ck-invalid">{{ $message }}</div>@enderror
                  </div>
                  <div class="ck-field">
                    <label for="phone">Phone Number <span style="color:var(--ck-danger);">*</span></label>
                    <input id="phone" name="phone" type="text" class="ck-input @error('phone') is-invalid @enderror" required maxlength="15"
                      oninput="this.value=this.value.replace(/[^0-9]/g,'');if(this.value.length>15)this.value=this.value.slice(0,15);"
                      value="{{ !empty($user) ? $user->mobile : '' }}">
                    <div class="ck-invalid ck-client-error" id="error-phone" style="display:none;color:var(--ck-danger);font-size:12px;margin-top:4px;">Please fill required fields</div>
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
                    <div class="ck-summary-row" style="color:#00E676;">
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
                  <button type="button" id="ckNextBtn2" class="ck-cta">Continue to Details</button>
                  <div class="ck-trust">
                    <span>&#128274; 100% Secure</span>
                    <span>Stripe Protected</span>
                    <span>SSL</span>
                  </div>
                </section>
              </div>

            </div>
          </div>

          {{-- ===================== STEP 3: DETAILS (Shipping) ===================== --}}
          <div class="ck-panel" id="panel3">
            <div class="ck-layout">

              {{-- LEFT: Shipping Address --}}
              <div>
                <section class="ck-section">
                  <h2 class="ck-section-title">Shipping Address</h2>

                  <div class="ck-field">
                    <label for="shipping_country">Country <span style="color:var(--ck-danger);">*</span></label>
                    <select id="shipping_country" name="country_id" class="ck-input @error('country_id') is-invalid @enderror">
                      <option value="">{{ trans('update.select_country') }}</option>
                      @if(!empty($countries))
                        @foreach($countries as $country)
                          @php $isSelected = !empty($user) && $user->country_id == $country->id; @endphp
                          <option value="{{ $country->id }}" {{ $isSelected ? 'selected' : '' }}>{{ $country->title }}</option>
                        @endforeach
                      @endif
                    </select>
                    <div class="ck-invalid ck-client-error" id="error-shipping_country" style="display:none;color:var(--ck-danger);font-size:12px;margin-top:4px;">Please fill required fields</div>
                    @error('country_id')<div class="ck-invalid" style="color:var(--ck-danger);">{{ $message }}</div>@enderror
                  </div>

                  <div class="ck-row">
                    <div class="ck-field">
                      <label for="province">Province / State <span style="color:var(--ck-danger);">*</span></label>
                      <input id="province" name="province_name" type="text" class="ck-input @error('province_name') is-invalid @enderror" value="{{ !empty($user) ? $user->province_name : '' }}">
                      <div class="ck-invalid ck-client-error" id="error-province" style="display:none;color:var(--ck-danger);font-size:12px;margin-top:4px;">Please fill required fields</div>
                      @error('province_name')<div class="ck-invalid" style="color:var(--ck-danger);">{{ $message }}</div>@enderror
                    </div>
                    <div class="ck-field">
                      <label for="city">{{ trans('update.city') }} <span style="color:var(--ck-danger);">*</span></label>
                      <input id="city" name="city_name" type="text" class="ck-input @error('city_name') is-invalid @enderror" value="{{ !empty($user) ? $user->city_name : '' }}">
                      <div class="ck-invalid ck-client-error" id="error-city" style="display:none;color:var(--ck-danger);font-size:12px;margin-top:4px;">Please fill required fields</div>
                      @error('city_name')<div class="ck-invalid" style="color:var(--ck-danger);">{{ $message }}</div>@enderror
                    </div>
                  </div>

                  <div class="ck-field">
                    <label for="house_no">House No. <span style="color:var(--ck-danger);">*</span></label>
                    <input id="house_no" name="house_no" type="text" class="ck-input @error('house_no') is-invalid @enderror" value="{{ !empty($user) ? $user->house_no : '' }}">
                    <div class="ck-invalid ck-client-error" id="error-house_no" style="display:none;color:var(--ck-danger);font-size:12px;margin-top:4px;">Please fill required fields</div>
                    @error('house_no')<div class="ck-invalid" style="color:var(--ck-danger);">{{ $message }}</div>@enderror
                  </div>

                  <div class="ck-field">
                    <label for="address">{{ trans('update.address') }} <span style="color:var(--ck-danger);">*</span></label>
                    <textarea id="address" name="address" rows="3" class="ck-input @error('address') is-invalid @enderror">{{ !empty($user) ? $user->address : '' }}</textarea>
                    <div class="ck-invalid ck-client-error" id="error-address" style="display:none;color:var(--ck-danger);font-size:12px;margin-top:4px;">Please fill required fields</div>
                    @error('address')<div class="ck-invalid" style="color:var(--ck-danger);">{{ $message }}</div>@enderror
                  </div>

                  <div class="ck-field">
                    <label for="zip">ZIP / Postal Code <span style="color:var(--ck-danger);">*</span></label>
                    <input id="zip" name="zip_code" type="text" class="ck-input @error('zip_code') is-invalid @enderror" maxlength="10"
                      oninput="if(this.value.length>10)this.value=this.value.slice(0,10);"
                      value="{{ !empty($user) ? $user->zip_code : '' }}">
                    <div class="ck-invalid ck-client-error" id="error-zip" style="display:none;color:var(--ck-danger);font-size:12px;margin-top:4px;">Please fill required fields</div>
                    @error('zip_code')<div class="ck-invalid" style="color:var(--ck-danger);">{{ $message }}</div>@enderror
                  </div>

                  <div style="background:transparent;border:1px solid rgba(212,175,55,0.45);border-radius:24px;padding:10px 16px;display:inline-flex;align-items:center;gap:8px;margin-top:16px;">
                    <span style="color:var(--ck-gold);font-size:13px;">&#9889;</span>
                    <span style="font-size:13px;font-weight:600;color:var(--ck-gold);">Express Shipping Always Selected</span>
                  </div>
                </section>

                {{-- Billing Address --}}
                <section class="ck-section" id="billingSection">
                  <h2 class="ck-section-title">Billing Address</h2>
                  <label style="display:flex;align-items:center;gap:10px;cursor:pointer;margin-bottom:16px;">
                    <input type="checkbox" id="billingSameAsShipping" checked style="width:18px;height:18px;accent-color:var(--ck-purple);" onchange="toggleBillingFields(this.checked)">
                    <span style="font-size:14px;color:var(--ck-text);">Billing address is the same as shipping address</span>
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
                      <input id="billing_zip" name="billing_zip" type="text" class="ck-input" maxlength="10" oninput="if(this.value.length>10)this.value=this.value.slice(0,10);">
                    </div>
                  </div>
                </section>

                {{-- Legal Confirmation (moved from Step 1) --}}
                <section class="ck-section" id="legalSection">
                  <h2 class="ck-section-title">Legal Confirmation</h2>
                  <p style="font-size:13px;color:var(--ck-muted);margin:0 0 16px;">Please confirm the following before completing your payment.</p>
                  <label style="display:flex;align-items:center;gap:10px;margin-bottom:12px;cursor:pointer;">
                    <input type="checkbox" class="legal-checkbox" id="legal1" style="width:18px;height:18px;accent-color:var(--ck-purple);">
                    <span style="font-size:14px;color:var(--ck-text);">I agree to the <a href="/pages/Terms-and-Conditions" target="_blank" style="color:var(--ck-gold);">Terms &amp; Conditions</a>.</span>
                  </label>
                  <label style="display:flex;align-items:center;gap:10px;margin-bottom:12px;cursor:pointer;">
                    <input type="checkbox" class="legal-checkbox" id="legal2" style="width:18px;height:18px;accent-color:var(--ck-purple);">
                    <span style="font-size:14px;color:var(--ck-text);">I understand the <a href="/pages/refund-policy" target="_blank" style="color:var(--ck-gold);">Refund &amp; Shipping Policy</a>.</span>
                  </label>
                  <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                    <input type="checkbox" class="legal-checkbox" id="legal3" style="width:18px;height:18px;accent-color:var(--ck-purple);">
                    <span style="font-size:14px;color:var(--ck-text);">I want to receive order updates and Kemetic App notifications.</span>
                  </label>
                </section>

              </div>

              {{-- RIGHT: Order Summary --}}
              <div class="ck-sidebar">

                {{-- Auto-select Stripe gateway --}}
                @php
                  $stripeChannel = null; $autoGatewayId = null;
                  if (!empty($paymentChannels)) {
                    foreach ($paymentChannels as $pc) {
                      if (!$isMultiCurrency || (!empty($pc->currencies) && in_array($userCurrency, $pc->currencies))) {
                        if (stripos($pc->class_name, 'stripe') !== false || stripos($pc->title, 'stripe') !== false) {
                          $stripeChannel = $pc; $autoGatewayId = $pc->id; break;
                        }
                      }
                    }
                    if (!$stripeChannel) {
                      foreach ($paymentChannels as $pc) {
                        if (!$isMultiCurrency || (!empty($pc->currencies) && in_array($userCurrency, $pc->currencies))) {
                          $stripeChannel = $pc; $autoGatewayId = $pc->id; break;
                        }
                      }
                    }
                  }
                @endphp
                @if($autoGatewayId)
                  <input type="hidden" name="gateway" id="autoGatewayInput" value="{{ $autoGatewayId }}">
                @endif

                <section class="ck-section">
                  <h2 class="ck-section-title">Order Summary</h2>
                  <div class="ck-summary-row">
                    <span>Subtotal</span>
                    <span class="cart-subtotal-value">{{ handlePrice($subTotal) }}</span>
                  </div>
                  <div class="ck-summary-row">
                    <span>Shipping</span>
                    <span class="cart-shipping-value">{{ handlePrice($productDeliveryFee ?? 0) }}</span>
                  </div>
                  @if(isset($totalDiscount) && $totalDiscount > 0)
                    <div class="ck-summary-row" style="color:#00E676;">
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
                    <span class="cart-total-value">{{ handlePrice($total) }}</span>
                  </div>
                  <button type="button" id="ckNextBtn3" class="ck-cta">Continue Payment</button>
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

        {{-- ===================== STEP 4: DONE ===================== --}}
        @if(!empty($step) && $step == 4)
          <div class="ck-panel active" id="panel4">
            <div class="ck-status-container">

              @if(!empty($order) && $order->status === \App\Models\Order::$paid)
                <div class="ck-status-card">
                  <span class="ck-sparkle">&#10024;</span>
                  <h2>{{ trans('cart.success_pay_title') }}</h2>
                  <p>{!! trans('cart.success_pay_msg') !!}</p>
                  <a href="academyapp://payment-success" class="ck-cta d-flex d-sm-none" style="justify-content:center;">{{ trans('public.redirect_to_app') }}</a>
                  <a href="/" class="ck-cta d-none d-sm-block">{{ trans('public.redirect_to_app') }}</a>
                </div>
              @endif

              @if(!empty($order) && $order->status === \App\Models\Order::$fail)
                <div class="ck-status-card failed">
                  <span class="ck-sparkle">&#9888;&#65039;</span>
                  <h2>{{ trans('cart.failed_pay_title') }}</h2>
                  <p>{!! nl2br(trans('cart.failed_pay_msg')) !!}</p>
                  <a href="academyapp://payment-failed" class="ck-cta d-flex d-sm-none" style="justify-content:center;">{{ trans('public.redirect_to_app') }}</a>
                  <a href="/" class="ck-cta d-none d-sm-block">{{ trans('public.redirect_to_app') }}</a>
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

      {{-- ── Delete confirmation modal ──────────────────────────── --}}
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

      {{-- ── Full-page Loading Overlay ──────────────────────────── --}}
      <div class="ck-page-loading" id="ckPageLoading">
        <div class="ck-page-loading-spinner"></div>
        <span class="ck-page-loading-text">Processing…</span>
      </div>

      {{-- ── Toast Notification ──────────────────────────────────── --}}
      <div class="ck-toast" id="ckToast">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
      $multiCurrencyMixin2 = new \App\Mixins\Financial\MultiCurrency();
      $currencies2 = $multiCurrencyMixin2->getCurrencies()->unique('currency');
    @endphp

    var currencyRates = {
      @foreach($currencies2 as $c)
        '{{ $c->currency }}': {{ floatval($c->exchange_rate ?? 1) }},
      @endforeach
    };

    var apiKey = '571fa201a47780cdeaa90825';
    if (apiKey) {
      fetch('https://v6.exchangerate-api.com/v6/' + apiKey + '/latest/EUR')
        .then(function(res) { return res.json(); })
        .then(function(data) {
          if (data && data.result === 'success' && data.conversion_rates) {
            for (var c in currencyRates) {
              if (data.conversion_rates[c]) currencyRates[c] = data.conversion_rates[c];
            }
            currencyRates['EUR'] = 1;
            updateDomTotals();
          }
        })
        .catch(function(err) { console.warn('Exchange API unavailable, using table rates.', err); });
    }

    var currencySymbols = {
      @foreach($currencies2 as $c)
        '{{ $c->currency }}': '{{ addslashes(currencySign($c->currency)) }}',
      @endforeach
    };
    var currentCurrency = '{{ currency() }}';
    var baseSubTotal = {{ floatval($subTotal ?? 0) }};
    var baseShipping = {{ floatval($productDeliveryFee ?? 0) }};

    var userProfileCountryId = '{{ !empty($user) && $user->country_id ? $user->country_id : "" }}';

    document.addEventListener('DOMContentLoaded', function() {
      if (userProfileCountryId) {
        var sel = document.getElementById('shipping_country');
        if (sel) sel.value = userProfileCountryId;
      }
      try {
            var savedCountry = sessionStorage.getItem('ck_dummy_country_id');
            if (savedCountry) {
                var dummySel = document.getElementById('dummy_country_id');
                if (dummySel) dummySel.value = savedCountry;
            }
        } catch(e) {}
    });

    function formatMoney(amount) {
      var rate = parseFloat(currencyRates[currentCurrency]);
      if (!rate || rate <= 0) rate = 1;
      var sym = currencySymbols[currentCurrency] || currentCurrency + ' ';
      return sym + (amount * rate).toFixed(2);
    }

    function updateDomTotals() {
      document.querySelectorAll('.cart-subtotal-value').forEach(function(el) { el.textContent = formatMoney(baseSubTotal); });
      document.querySelectorAll('.cart-shipping-value').forEach(function(el) { el.textContent = formatMoney(baseShipping); });
      document.querySelectorAll('.cart-total-value').forEach(function(el) { el.textContent = formatMoney(baseSubTotal + baseShipping); });
      document.querySelectorAll('.cart-item-unit-price').forEach(function(el) { el.textContent = formatMoney(parseFloat(el.getAttribute('data-base')) || 0); });
      document.querySelectorAll('.cart-item-line-total').forEach(function(el) { el.textContent = formatMoney(parseFloat(el.getAttribute('data-base')) || 0); });
    }

    function changeCurrency(currency, calculateShippingAfter) {
      currentCurrency = currency;
      document.querySelectorAll('.ck-currency-pill').forEach(function(el) { el.classList.remove('active'); });
      var pill = document.querySelector('.ck-currency-pill[data-currency="' + currency + '"]');
      if (pill) pill.classList.add('active');
      updateDomTotals();
      fetch('/set-currency', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: 'currency=' + encodeURIComponent(currency)
      }).then(function() {
        if (calculateShippingAfter && window.calculateShipping) window.calculateShipping();
      });
    }

    /* =====================================================================
       STEP LOGIC  (4 steps: Review → Info → Details → Done via Stripe)
       ===================================================================== */
    (function() {
      var nextBtn1 = document.getElementById('ckNextBtn1'); // Review → Info
      var nextBtn2 = document.getElementById('ckNextBtn2'); // Info → Details
      var nextBtn3 = document.getElementById('ckNextBtn3'); // Details → Stripe → Done

      function showPanel(panel) {
        document.querySelectorAll('.ck-panel').forEach(function(el) { el.style.display = 'none'; });
        var target = document.getElementById('panel' + panel);
        if (target) target.style.display = 'block';

        for (var i = 1; i <= 4; i++) {
          var dot = document.getElementById('step-dot-' + i);
          if (!dot) continue;
          dot.classList.remove('active', 'done');
          if (i < panel) dot.classList.add('done');
          if (i === panel) dot.classList.add('active');
        }

        var lbl = document.getElementById('ckStepLabel');
        if (lbl) lbl.textContent = 'Step ' + panel + ' of 4';

        var backBtn = document.getElementById('ckBackBtn');
        if (backBtn) {
          if (panel === 1 || panel === 4) {
            backBtn.style.visibility = 'hidden';
            backBtn.onclick = function(e) { e.preventDefault(); };
          } else {
            backBtn.style.visibility = 'visible';
            backBtn.onclick = function() { showPanel(panel - 1); };
          }
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
      window.showPanel = showPanel;

      window.showToast = function(msg, type) {
        var toast = document.getElementById('ckToast');
        var toastMsg = document.getElementById('ckToastMsg');
        if (!toast || !toastMsg) return;
        toastMsg.textContent = msg;
        toast.classList.remove('success', 'show');
        if (type === 'success') toast.classList.add('success');
        void toast.offsetWidth;
        toast.classList.add('show');
        clearTimeout(window._ckToastTimer);
        window._ckToastTimer = setTimeout(function() { toast.classList.remove('show'); }, 4000);
      };

      /* Step 1 → 2 (Review → Info): check legal checkboxes */
      if (nextBtn1) {
        nextBtn1.addEventListener('click', function() {
          var checkboxes = document.querySelectorAll('#panel1 .legal-checkbox');
          var allChecked = true;
          checkboxes.forEach(function(cb) { if (!cb.checked) allChecked = false; });
          if (!allChecked) {
            window.showToast('Please agree to all checkboxes before continuing.');
            return;
          }
          showPanel(2);
        });
      }

      /* Step 2 → 3 (Info → Details): validate contact fields */
      if (nextBtn2) {
        nextBtn2.addEventListener('click', function() {
          var ok = true;
          ['firstName', 'lastName', 'email', 'phone'].forEach(function(id) {
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
                if (!emailRegex.test(val)) { isValid = false; if (errEl) errEl.textContent = 'Please enter a valid email address'; }
              }
              if (!isValid) { el.style.borderColor = 'var(--ck-danger)'; if (errEl) errEl.style.display = 'block'; ok = false; }
              else { el.style.borderColor = ''; if (errEl) errEl.style.display = 'none'; }
            }
          });
          if (ok) showPanel(3);
        });
      }

      /* Step 3 → Stripe → Done: validate legal checkboxes, shipping fields, create order, submit to Stripe */
      if (nextBtn3) {
        nextBtn3.addEventListener('click', function() {

          /* ── 1. Check Legal checkboxes first ── */
          var legalCheckboxes = document.querySelectorAll('#panel3 .legal-checkbox');
          var allLegalChecked = true;
          legalCheckboxes.forEach(function(cb) { if (!cb.checked) allLegalChecked = false; });
          if (!allLegalChecked) {
            window.showToast('⚠️ Please agree to all legal confirmations before proceeding to payment.');
            var legalSec = document.getElementById('legalSection');
            if (legalSec) legalSec.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
          }

          /* ── 2. Validate shipping address fields ── */
          var ok = true;
          ['shipping_country', 'province', 'city', 'house_no', 'address', 'zip'].forEach(function(id) {
            var el = document.getElementById(id);
            var errEl = document.getElementById('error-' + id);
            if (el) {
              var val = el.value.trim();
              if (!val) {
                el.style.borderColor = 'var(--ck-danger)';
                if (errEl) { errEl.textContent = 'Please fill required fields'; errEl.style.display = 'block'; }
                ok = false;
              } else {
                el.style.borderColor = '';
                if (errEl) errEl.style.display = 'none';
              }
            }
          });
          if (!ok) return;

          var form = document.getElementById('cartForm');
          var btn = this;
          var originalText = btn.innerHTML;
          btn.innerHTML = 'Processing...';
          btn.style.opacity = '0.75';
          btn.style.pointerEvents = 'none';

          // Step 1: Create order via AJAX
          var formData = new FormData(form);
          fetch('/cart/checkout', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
          })
          .then(function(response) {
            var ct = response.headers.get('content-type');
            if (ct && ct.includes('application/json')) return response.json();
            return null;
          })
          .then(function(data) {
            if (data && data.order_id) {
              // Attach order_id
              var orderInput = form.querySelector('input[name="order_id"]');
              if (orderInput) { orderInput.value = data.order_id; }
              else {
                var hid = document.createElement('input');
                hid.type = 'hidden'; hid.name = 'order_id'; hid.value = data.order_id;
                form.appendChild(hid);
              }

              try { sessionStorage.removeItem('ck_order_id'); } catch(e) {}
              try { sessionStorage.removeItem('ck_dummy_country_id'); } catch (e) { }
              try { sessionStorage.setItem('ck_went_to_stripe', data.order_id); } catch(e) {}

              // Set live amount + currency
              var liveRate = parseFloat(currencyRates[currentCurrency]) || 1;
              var liveTotal = Math.round((parseFloat(baseSubTotal) + parseFloat(baseShipping)) * liveRate * 100) / 100;

              var amountInput = document.getElementById('checkoutAmount') || form.querySelector('input[name="amount"]');
              if (!amountInput) { amountInput = document.createElement('input'); amountInput.type = 'hidden'; amountInput.name = 'amount'; amountInput.id = 'checkoutAmount'; form.appendChild(amountInput); }
              amountInput.value = liveTotal;

              var currencyInput = document.getElementById('checkoutCurrency') || form.querySelector('input[name="currency"]');
              if (!currencyInput) { currencyInput = document.createElement('input'); currencyInput.type = 'hidden'; currencyInput.name = 'currency'; currencyInput.id = 'checkoutCurrency'; form.appendChild(currencyInput); }
              currencyInput.value = currentCurrency;

              // Step 2: Submit to Stripe (payment-request redirect)
              form.action = '/payments/payment-request';
              form.submit();
            } else {
              btn.innerHTML = originalText;
              btn.style.opacity = '1';
              btn.style.pointerEvents = 'auto';
              window.showToast('Checkout failed. Please check your details.');
            }
          })
          .catch(function(err) {
            console.error(err);
            btn.innerHTML = originalText;
            btn.style.opacity = '1';
            btn.style.pointerEvents = 'auto';
            window.showToast('Network error, please try again.');
          });
        });
      }

      /* Country → Currency auto-select */
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
        dummyCountryEl.addEventListener('change', function() {
          try { sessionStorage.setItem('ck_dummy_country_id', this.value); } catch(e) {}
          var map = mapCountryToCurrency(this.options[this.selectedIndex].text);
          if (map) changeCurrency(map, false);
        });
      }
      

      /* Back from Stripe detection */
      window.addEventListener('pageshow', function(event) {
        try {
          var wentToStripe = sessionStorage.getItem('ck_went_to_stripe');
          if (wentToStripe) {
            sessionStorage.removeItem('ck_went_to_stripe');
            var isBackNav = event.persisted ||
              (window.performance && window.performance.navigation && window.performance.navigation.type === 2) ||
              (window.performance && window.performance.getEntriesByType && window.performance.getEntriesByType('navigation').length > 0 && window.performance.getEntriesByType('navigation')[0].type === 'back_forward');
            if (isBackNav) {
              window.location.href = '/payments/status?canceled=1&order_id=' + wentToStripe;
              return;
            }
          }
        } catch(e) {}
      });

      /* Init */
      var serverStep = {{ isset($step) ? $step : 'null' }};
      var urlParams = new URLSearchParams(window.location.search);
      var initStep = serverStep ? serverStep : (urlParams.get('step') ? parseInt(urlParams.get('step')) : 1);
      showPanel(initStep <= 3 ? initStep : 1);

    }());

    /* ── Delete confirmation modal ───────────────────────────── */
    var _deleteCartId = null;
    var _deleteRow = null;

    function removeCartItem(btn, cartId) {
      _deleteCartId = cartId;
      _deleteRow = btn.closest('[style*="border-bottom"]') || btn.parentNode.parentNode.parentNode;
      document.getElementById('deleteModal').classList.add('visible');
      var confirmBtn = document.getElementById('confirmDeleteBtn');
      confirmBtn.onclick = function() { closeDeleteModal(); doDelete(cartId); };
    }

    function closeDeleteModal() { document.getElementById('deleteModal').classList.remove('visible'); }

    function doDelete(cartId) {
      var overlay = document.getElementById('ckPageLoading');
      if (overlay) overlay.style.display = 'flex';
      fetch('/cart/' + cartId + '/delete', {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
      })
      .then(function(response) {
        var ct = response.headers.get('content-type') || '';
        if (ct.indexOf('application/json') !== -1) return response.json();
        return { success: true };
      })
      .then(function(data) {
        if (data.success || data.success === undefined) {
          if (_deleteRow) _deleteRow.remove();
          checkEmpty();
          var url = new URL(window.location.href);
          url.searchParams.set('step', '1');
          window.location.href = url.toString();
        } else {
          if (overlay) overlay.style.display = 'none';
          window.showToast('Failed to remove item. Please try again.');
        }
      })
      .catch(function(error) {
        console.error('Delete error:', error);
        if (overlay) overlay.style.display = 'none';
        window.showToast('Error removing item.');
      });
    }

    function checkEmpty() {
      var remaining = document.querySelectorAll('#panel1 [style*="border-bottom"]').length;
      if (remaining === 0) {
        var emptyScreen = document.getElementById('emptyCartScreen');
        if (emptyScreen) { emptyScreen.style.display = 'block'; }
        var cartForm = document.getElementById('cartForm');
        if (cartForm) cartForm.style.display = 'none';
        var steps = document.querySelector('.ck-steps');
        if (steps) steps.style.display = 'none';
        var stepLabel = document.getElementById('ckStepLabel');
        if (stepLabel) stepLabel.style.display = 'none';
      }
    }

    window.toggleBillingFields = function(sameAsShipping) {
      var fields = document.getElementById('billingFields');
      if (fields) fields.style.display = sameAsShipping ? 'none' : 'block';
    };

    window.calculateShipping = function() {
      var country = document.getElementById('shipping_country') ? document.getElementById('shipping_country').value : '';
      var city = document.getElementById('city') ? document.getElementById('city').value : '';
      var zip = document.getElementById('zip') ? document.getElementById('zip').value : '';
      var phone = document.getElementById('phone') ? document.getElementById('phone').value : '';
      var province = document.getElementById('province') ? document.getElementById('province').value : '';
      if (!country) return;
      fetch('/cart/calculate-shipping', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ country_id: country, city_name: city, phone: phone, province_name: province, zip_code: zip })
      })
      .then(function(r) { return r.json(); })
      .then(function(data) {
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