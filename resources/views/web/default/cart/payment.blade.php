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
            <div class="ck-step-label" id="ckStepLabel">Step 3 of 5</div>
            <div class="ck-steps">
                <div class="ck-step done" id="step-dot-1">
                    <div class="ck-step-circle">1</div><span class="ck-step-name">Info</span>
                </div>
                <div class="ck-step done" id="step-dot-2">
                    <div class="ck-step-circle">2</div><span class="ck-step-name">Shipping</span>
                </div>
                <div class="ck-step active" id="step-dot-3">
                    <div class="ck-step-circle">3</div><span class="ck-step-name">Payment</span>
                </div>
                <div class="ck-step" id="step-dot-4">
                    <div class="ck-step-circle">4</div><span class="ck-step-name">Review</span>
                </div>
                <div class="ck-step" id="step-dot-5">
                    <div class="ck-step-circle">5</div><span class="ck-step-name">Done</span>
                </div>
            </div>

            @php
                $isMultiCurrency = !empty(getFinancialCurrencySettings('multi_currency'));
                $userCurrency = currency();
                $invalidChannels = [];
              @endphp

            <form action="/payments/payment-request" method="post" id="paymentForm">
                {{ csrf_field() }}
                <input type="hidden" name="order_id" value="{{ $order->id }}">

                {{-- ===============================
                PANEL 3: PAYMENT METHOD
                =============================== --}}
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
                                    Cards, Apple Pay, Google Pay, Wero Europe and local methods appear automatically
                                    depending on country, currency and Stripe settings.
                                </div>

                                <label
                                    style="display:flex;align-items:center;gap:10px;cursor:pointer;font-size:14px;color:var(--ck-text);">
                                    <input type="checkbox" checked
                                        style="width:18px;height:18px;accent-color:var(--ck-purple);">
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
                                <button type="button" id="ckNextBtn3" class="ck-cta" disabled>Continue to Review</button>
                                <div class="ck-trust">
                                    <span>&#128274; 100% Secure</span>
                                    <span>Stripe Protected</span>
                                    <span>SSL</span>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>

                {{-- ===============================
                PANEL 4: REVIEW YOUR ORDER
                =============================== --}}
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
                                                                <div
                                                                    style="font-size:12px;color:var(--ck-muted);margin-bottom:4px;word-break:break-word;">
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
                                                                    <div
                                                                        style="display:flex;align-items:center;gap:8px;font-size:14px;color:var(--ck-text);">
                                                                        <span>Qty</span>
                                                                        <strong style="color:#fff;">{{ $cartItemInfo['quantity'] ?? 1 }}</strong>
                                                                    </div>
                                                                    <button type="button" onclick="removeCartItem(this, {{ $cart->id }})"
                                                                        style="background:rgba(255,107,107,0.12);border:1.5px solid #FF6B6B;border-radius:50%;width:34px;height:34px;min-width:34px;max-width:34px;display:flex;align-items:center;justify-content:center;color:#FF6B6B;cursor:pointer;margin-left:auto;flex-shrink:0;"
                                                                        title="Remove">
                                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#FF6B6B"
                                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                                            <path
                                                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                                            </path>
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
                                <label
                                    style="display:flex; align-items:center; gap:10px; margin-bottom:12px; cursor:pointer;">
                                    <input type="checkbox" class="legal-checkbox" required style="width:18px;height:18px;">
                                    <span style="font-size:14px; color:var(--ck-text);">I agree to the <a href="/terms"
                                            target="_blank" style="color:var(--ck-gold);">Terms & Conditions</a>.</span>
                                </label>
                                <label
                                    style="display:flex; align-items:center; gap:10px; margin-bottom:12px; cursor:pointer;">
                                    <input type="checkbox" class="legal-checkbox" required style="width:18px;height:18px;">
                                    <span style="font-size:14px; color:var(--ck-text);">I understand the <a
                                            href="/refund-policy" target="_blank" style="color:var(--ck-gold);">Refund &
                                            Shipping Policy</a>.</span>
                                </label>
                                <label
                                    style="display:flex; align-items:center; gap:10px; margin-bottom:12px; cursor:pointer;">
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
                                <a href="https://chat.whatsapp.com/FfWt50zLTax1DUZCqJK59Q?mode=gi_t" target="_blank"
                                    class="ck-cta ghost"
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

            </form>

            {{-- RAZORPAY --}}
            @if(!empty($razorpay) && $razorpay)
                <form action="/payments/verify/Razorpay" method="get">
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <script src="https://checkout.razorpay.com/v1/checkout.js" data-key="{{ getRazorpayApiKey()['api_key'] }}"
                        data-amount="{{ (int) ($order->total_amount * 100) }}" data-buttontext="product_price"
                        data-description="Razorpay" data-currency="{{ currency() }}" data-image="{{ $generalSettings['logo'] }}"
                        data-prefill.name="{{ $order->user->full_name }}" data-prefill.email="{{ $order->user->email }}"
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
                <a href="academyapp://home" class="ck-go-app d-flex d-sm-none" style="justify-content:center;">🏠 Go to
                    App</a>
                <a href="/" class="ck-go-app d-none d-sm-inline-block">🏠 Go to App</a>
            </div>

            {{-- ── Full-page Loading Overlay ──────────────────────── --}}
            <div class="ck-page-loading" id="ckPageLoading">
                <div class="ck-page-loading-spinner"></div>
                <span class="ck-page-loading-text">Removing item…</span>
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
                <span id="ckToastMsg">Please check all three boxes in the Legal Confirmation section.</span>
            </div>

        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/js/parts/payment.min.js"></script>
    <script>
        (function () {
            var urlParams = new URLSearchParams(window.location.search);
            var initStep = urlParams.get('step') ? parseInt(urlParams.get('step')) : 3;
            var currentStep = initStep;
            var nextBtn3 = document.getElementById('ckNextBtn3');
            var nextBtn4 = document.getElementById('ckNextBtn4');
            var backBtn = document.getElementById('ckBackBtn');
            var stepLabel = document.getElementById('ckStepLabel');
            var radios = document.querySelectorAll('input[name="gateway"]');
            var form = document.getElementById('paymentForm');

            function setDots(active) {
                for (var i = 1; i <= 5; i++) {
                    var d = document.getElementById('step-dot-' + i);
                    if (!d) continue;
                    d.classList.remove('active', 'done');
                    if (i < active) d.classList.add('done');
                    if (i === active) d.classList.add('active');
                }
            }

            function showStep(n) {
                document.querySelectorAll('.ck-panel').forEach(function (p) { p.classList.remove('active'); });
                var panel = document.getElementById('panel' + n);
                if (panel) panel.classList.add('active');
                setDots(n);
                stepLabel.textContent = 'Step ' + n + ' of 5';
                window.scrollTo({ top: 0, behavior: 'smooth' });
                currentStep = n;

                if (n === 3) {
                    var autoGw = document.getElementById('autoGatewayInput');
                    nextBtn3.disabled = !autoGw;
                    backBtn.onclick = function () { window.location.href = '/cart?step=2'; };
                } else if (n === 4) {
                    backBtn.onclick = function () { showStep(3); };
                }
            }

            nextBtn3.addEventListener('click', function () {
                var autoGw = document.getElementById('autoGatewayInput');
                if (!autoGw) { alert('No payment method available.'); return; }
                showStep(4);
            });

            nextBtn4.addEventListener('click', function () {
                var checkboxes = document.querySelectorAll('.legal-checkbox');
                var allChecked = true;
                checkboxes.forEach(function (cb) {
                    if (!cb.checked) allChecked = false;
                });

                if (!allChecked) {
                    var toast = document.getElementById('ckToast');
                    if (toast) {
                        toast.classList.add('show');
                        setTimeout(function () { toast.classList.remove('show'); }, 3500);
                    }
                    return;
                }

                form.submit();
            });

            showStep(currentStep);
        })();

        var _deleteCartId = null;
        var _deleteRow = null;

        function removeCartItem(btn, cartId) {
            _deleteCartId = cartId;
            _deleteRow = btn.closest('.ck-item');
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

        /* close modal on overlay click */
        document.getElementById('deleteModal').addEventListener('click', function (e) {
            if (e.target === this) closeDeleteModal();
        });

        function doDelete(cartId) {
            /* Show full-page loading overlay */
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
                    /* Reload and stay on Step 4 */
                    var url = new URL(window.location.href);
                    url.searchParams.set('step', '4');
                    window.location.href = url.toString();
                } else {
                    if (overlay) overlay.style.display = 'none';
                    alert('Failed to remove item. Please try again.');
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
            var remaining = document.querySelectorAll('.ck-item').length;
            if (remaining === 0) {
                /* Hide step indicators and cart form */
                var cartForm = document.getElementById('cartForm');
                if (cartForm) cartForm.style.display = 'none';
                var stepsEl = document.querySelector('.ck-steps');
                if (stepsEl) stepsEl.style.display = 'none';
                var stepLabel = document.getElementById('ckStepLabel');
                if (stepLabel) stepLabel.style.display = 'none';
                /* Show empty cart screen */
                var emptyScreen = document.getElementById('emptyCartScreen');
                if (emptyScreen) emptyScreen.style.display = 'block';
            }
        }
    </script>
@endpush