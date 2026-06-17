@extends(getTemplate() . '.layouts.app')

@section('content')
  <style>
    /* =========================================================
         KEMETIC CHECKOUT  –  status_pay.blade.php  (Step 4)
         Desktop-first, uses the normal app layout/sidebar
         ========================================================= */
    :root {
      --ck-purple: #9B35FF;
      --ck-gold: #D4AF37;
      --ck-gold2: #FFE28A;
      --ck-muted: #A89EC4;
      --ck-text: #F0ECF8;
      --ck-border: rgba(255, 255, 255, .10);
      --ck-danger: #FF6B6B;
      --ck-green: #19D45B;
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
      box-shadow: 0 0 18px rgba(155, 53, 255, .65);
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

    /* Status card container */
    .ck-status-container {
      max-width: 500px;
      margin: 0 auto;
    }

    /* Status card */
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

    /* Buttons */
    .ck-cta {
      display: block;
      width: 100%;
      border: none;
      border-radius: 14px;
      padding: 17px;
      background: linear-gradient(135deg, var(--ck-gold), var(--ck-gold2));
      color: #0D0B14;
      font-size: 16px;
      font-weight: 900;
      letter-spacing: 1px;
      text-align: center;
      text-decoration: none;
      cursor: pointer;
      text-transform: uppercase;
      transition: filter .2s;
      margin-bottom: 12px;
    }

    .ck-cta:hover {
      filter: brightness(1.08);
      color: #0D0B14;
    }

    .ck-cta.ghost {
      background: rgba(255, 255, 255, .06);
      color: var(--ck-text);
      border: 1px solid var(--ck-border);
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
    }
  </style>

  <div class="ck-wrap">
    <div class="ck-inner">

      {{-- HEADER --}}
      <header class="ck-header">
        <a href="javascript:void(0)" class="ck-back-btn" style="visibility:hidden; cursor:default;">&#8249;</a>
        <!-- <div class="ck-logo">KEMETIC</div> -->
        <div class="ck-secure-badge">&#128274; Secure</div>
      </header>

      {{-- STEP 4: ALL DONE --}}
      <div class="ck-step-label">Step 4 of 4</div>
      <div class="ck-steps">
        <div class="ck-step done">
          <div class="ck-step-circle">1</div><span class="ck-step-name">Review</span>
        </div>
        <div class="ck-step done">
          <div class="ck-step-circle">2</div><span class="ck-step-name">Info</span>
        </div>
        <div class="ck-step done">
          <div class="ck-step-circle">3</div><span class="ck-step-name">Details</span>
        </div>
        <div class="ck-step active">
          <div class="ck-step-circle">4</div><span class="ck-step-name">Done</span>
        </div>
      </div>

      <div class="ck-status-container">
        {{-- SUCCESS --}}
        @if(!empty($order) && $order->status === \App\Models\Order::$paid)
          <div class="ck-status-card">
            <span class="ck-sparkle">&#10024;</span>
            <h2>{{ trans('cart.success_pay_title') }}</h2>
            <p>{!! trans('cart.success_pay_msg') !!}</p>

            <a href="/" class="ck-cta d-flex d-sm-none" style="justify-content:center;">
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

            <!-- <a href="/cart?step=3" class="ck-cta" style="margin-bottom: 15px;">
              Try Again
            </a> -->

            <a href="/cart" class="ck-cta ghost d-flex d-sm-none" style="justify-content:center;">
             Proceed to checkout
            </a>
            <a href="/cart" class="ck-cta ghost d-none d-sm-block">
             Proceed to checkout
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
  </div>
  
  <script>
    // Ensure the Stripe back-button trap is fully cleared as soon as we reach the status page
    try {
      sessionStorage.removeItem('ck_went_to_stripe');
    } catch (e) {}
  </script>
@endsection