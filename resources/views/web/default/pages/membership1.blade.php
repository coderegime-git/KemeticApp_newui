@extends('web.default.layouts.app')
<style>
  /* ── Keep all original membership styles ── */
  .membership-modal {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.6);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
  }
  .membership-modal.hidden { display: none; }
  .membership-modal-box {
    background: #111;
    padding: 24px;
    border-radius: 14px;
    width: 100%;
    max-width: 420px;
  }
  .danger { background:#d9534f; }

  /* ── Founders extra styles (match app palette) ── */
  .founders-urgency-strip {
    background: linear-gradient(90deg, rgba(255,193,7,0.08), rgba(255,193,7,0.15), rgba(255,193,7,0.08));
    border-bottom: 1px solid rgba(255,193,7,0.2);
    padding: 10px 0;
    text-align: center;
  }
  .founders-urgency-inner {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    flex-wrap: wrap;
    font-size: 12px;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    color: #ffc107;
    font-weight: 600;
  }
  .founders-urgency-dot {
    width: 6px; height: 6px;
    background: #ffc107;
    border-radius: 50%;
    animation: founders-pulse 1.8s ease-in-out infinite;
  }
  @keyframes founders-pulse {
    0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.4;transform:scale(0.7)}
  }

  /* Founders lock banner — shown inside hero section */
  .founders-lock-banner {
    display: flex;
    align-items: center;
    gap: 14px;
    background: rgba(255,193,7,0.1);
    border: 1px solid rgba(255,193,7,0.25);
    border-radius: 12px;
    padding: 16px 20px;
    margin-top: 20px;
    max-width: 480px;
  }
  .founders-lock-banner .flock-icon { font-size: 24px; flex-shrink: 0; }
  .founders-lock-banner .flock-title {
    font-size: 13px;
    font-weight: 700;
    color: #ffc107;
    letter-spacing: 0.05em;
    margin-bottom: 3px;
  }
  .founders-lock-banner .flock-desc {
    font-size: 12px;
    color: rgba(255,255,255,0.6);
    line-height: 1.5;
  }

  /* ── Founders Manifesto Section ── */
  .founders-manifesto-section {
    padding: 56px 0 48px;
    position: relative;
    overflow: hidden;
  }
  .founders-manifesto-section::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 70% 80% at 50% 50%, rgba(255,193,7,0.07) 0%, transparent 70%);
    pointer-events: none;
  }
  .founders-manifesto-inner {
    max-width: 680px;
    margin: 0 auto;
    text-align: center;
    position: relative;
    z-index: 1;
  }
  .founders-manifesto-tag {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: rgba(255,193,7,0.1);
    border: 1px solid rgba(255,193,7,0.25);
    border-radius: 40px;
    padding: 7px 18px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: #ffc107;
    margin-bottom: 28px;
  }
  .founders-manifesto-dot {
    width: 7px; height: 7px;
    background: #ffc107;
    border-radius: 50%;
    animation: founders-pulse 1.8s ease-in-out infinite;
    flex-shrink: 0;
  }
  .founders-manifesto-heading {
    font-size: clamp(28px, 5vw, 48px);
    font-weight: 800;
    line-height: 1.15;
    color: #fff;
    margin-bottom: 28px;
    letter-spacing: -0.01em;
  }
  .founders-manifesto-body {
    display: flex;
    flex-direction: column;
    gap: 14px;
    margin-bottom: 36px;
  }
  .founders-manifesto-body p {
    font-size: clamp(15px, 2.2vw, 18px);
    color: rgba(255,255,255,0.65);
    line-height: 1.8;
    margin: 0;
  }
  .founders-manifesto-body strong {
    color: rgba(255,255,255,0.9);
  }
  .founders-manifesto-divider {
    width: 60px; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,193,7,0.5), transparent);
    margin: 0 auto 36px;
  }
  .founders-manifesto-lock {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    background: rgba(255,193,7,0.07);
    border: 1px solid rgba(255,193,7,0.22);
    border-radius: 16px;
    padding: 28px 28px;
    text-align: left;
  }
  .founders-manifesto-lock-icon {
    font-size: 36px;
    flex-shrink: 0;
    line-height: 1;
    margin-top: 2px;
  }
  .founders-manifesto-lock-content {
    flex: 1;
  }
  .founders-manifesto-lock-title {
    font-size: 18px;
    font-weight: 800;
    color: #ffc107;
    margin-bottom: 12px;
    letter-spacing: 0.02em;
  }
  .founders-manifesto-lock-content p {
    font-size: 14px;
    color: rgba(255,255,255,0.6);
    line-height: 1.7;
    margin: 0 0 8px;
  }
  .founders-manifesto-lock-content p:last-child { margin-bottom: 0; }
  .founders-manifesto-lock-content strong {
    color: rgba(255,255,255,0.9);
  }
  .founders-manifesto-lock-highlight {
    font-size: 14px !important;
    color: rgba(255,193,7,0.85) !important;
    font-weight: 600;
    border-top: 1px solid rgba(255,193,7,0.15);
    padding-top: 12px;
    margin-top: 4px;
  }

  /* ── Founders promise cards ── */
  .founders-promise-section {
    padding: 60px 0 20px;
  }
  .founders-section-eyebrow {
    font-size: 11px;
    letter-spacing: 0.28em;
    text-transform: uppercase;
    color: #ffc107;
    text-align: center;
    margin-bottom: 10px;
    font-weight: 600;
    display: block;
  }
  .founders-section-title {
    font-size: clamp(20px,3vw,28px);
    font-weight: 700;
    text-align: center;
    color: #fff;
    margin-bottom: 8px;
  }
  .founders-divider {
    width: 60px; height: 2px;
    background: linear-gradient(90deg,transparent,#ffc107,transparent);
    margin: 0 auto 36px;
  }
  .founders-promise-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px,1fr));
    gap: 16px;
  }
  .founders-promise-card {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 14px;
    padding: 28px 20px;
    text-align: center;
    transition: border-color 0.3s, transform 0.3s;
  }
  .founders-promise-card:hover {
    border-color: rgba(255,193,7,0.3);
    transform: translateY(-4px);
  }
  .founders-promise-icon {
    font-size: 30px;
    margin-bottom: 14px;
    display: block;
  }
  .founders-promise-title {
    font-size: 13px;
    font-weight: 700;
    color: #ffc107;
    letter-spacing: 0.06em;
    margin-bottom: 8px;
  }
  .founders-promise-text {
    font-size: 13px;
    color: rgba(255,255,255,0.55);
    line-height: 1.65;
  }

  /* ── FAQ enhanced ── */
  .membership-section details {
    margin-bottom: 6px;
  }
  .membership-section details summary {
    cursor: pointer;
    padding: 16px 0;
    font-weight: 600;
    list-style: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(255,255,255,0.07);
    color: rgba(255,255,255,0.85);
    font-size: 14px;
  }
  .membership-section details summary::-webkit-details-marker { display:none; }
  .membership-section details summary::after {
    content: '+';
    font-size: 20px;
    color: #ffc107;
    transition: transform 0.3s;
    flex-shrink: 0;
    margin-left: 12px;
  }
  .membership-section details[open] summary::after { transform: rotate(45deg); }
  .membership-section details .membership-small {
    padding: 12px 0 16px;
    color: rgba(255,255,255,0.5);
    line-height: 1.7;
    font-size: 13px;
  }

  /* ── Testimonial stars ── */
  .membership-stars { color: #ffc107; font-size: 11px; letter-spacing: 2px; margin-bottom: 8px; }

  /* ── Founders manifesto text ── */
  .founders-manifesto-text {
    font-size: clamp(15px,2vw,18px);
    color: rgba(255,255,255,0.65);
    line-height: 1.85;
    text-align: center;
    max-width: 620px;
    margin: 0 auto 24px;
  }

  /* ── Plan perks list ── */
  .membership-plan-perks {
    list-style: none;
    margin: 12px 0 20px;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 8px;
  }
  .membership-plan-perks li {
    font-size: 12px;
    color: rgba(255,255,255,0.6);
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .membership-plan-perks li::before {
    content: '◆';
    font-size: 5px;
    color: #ffc107;
    flex-shrink: 0;
  }

  /* ── Founders badge on plan card ── */
  .founders-plan-badge {
    display: inline-block;
    background: rgba(255,193,7,0.15);
    border: 1px solid rgba(255,193,7,0.3);
    color: #ffc107;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    padding: 3px 10px;
    border-radius: 20px;
    margin-bottom: 10px;
  }
</style>

@section('content')

<!-- ══ URGENCY STRIP ══ -->
<div class="founders-urgency-strip">
  <div class="founders-urgency-inner">
    <span class="founders-urgency-dot"></span>
    Founders Pricing &nbsp;·&nbsp; Limited Time &nbsp;·&nbsp; Your Price Is Locked Forever
    <span class="founders-urgency-dot"></span>
  </div>
</div>

<!-- ══ FOUNDERS MANIFESTO SECTION ══ -->
<div style="display:block !important; visibility:visible !important; width:100% !important; padding:25px 0px 10px !important; box-sizing:border-box !important; background:linear-gradient(180deg,rgba(255,193,7,0.06) 0%,transparent 100%) !important; border-top:1px solid rgba(255,193,7,0.15) !important;">
  <div style="max-width:700px !important; margin:0 auto !important; text-align:center !important; display:block !important;">

    <!-- Tag pill -->
    <div style="display:inline-block !important; background:rgba(255,193,7,0.13) !important; border:1px solid rgba(255,193,7,0.35) !important; border-radius:40px !important; padding:8px 22px !important; font-size:11px !important; font-weight:700 !important; letter-spacing:0.22em !important; text-transform:uppercase !important; color:#ffc107 !important; margin-bottom:30px !important;">
      🔥 Founders Access — Limited Time
    </div>

    <!-- Main heading -->
    <div style="font-size:clamp(28px,5vw,48px) !important; font-weight:800 !important; line-height:1.15 !important; color:#ffffff !important; margin:0 0 28px !important; letter-spacing:-0.01em !important; display:block !important;">
      This Is Not Just a Membership.
    </div>

    <!-- Paragraph 1 -->
    <div style="font-size:17px !important; color:rgba(255,255,255,0.68) !important; line-height:1.85 !important; margin:0 0 16px !important; display:block !important;">
      For a short time, the Kemetic App is opening its doors with <span style="color:#ffffff !important; font-weight:700 !important;">Founders Pricing.</span>
    </div>

    <!-- Paragraph 2 -->
    <div style="font-size:17px !important; color:rgba(255,255,255,0.68) !important; line-height:1.85 !important; margin:0 0 16px !important; display:block !important;">
      This is access to a growing global community of <span style="color:#ffffff !important; font-weight:700 !important;">seekers, wisdom keepers,</span> and hidden knowledge that was <span style="color:#ffffff !important; font-weight:700 !important;">never taught in school.</span>
    </div>

    <!-- Paragraph 3 -->
    <div style="font-size:17px !important; color:rgba(255,255,255,0.68) !important; line-height:1.85 !important; margin:0 0 16px !important; display:block !important;">
      To allow the first wave of people to enter the platform, we are offering <span style="color:#ffffff !important; font-weight:700 !important;">extremely discounted membership prices.</span>
    </div>

    <!-- Paragraph 4 - hook -->
    <div style="font-size:18px !important; color:rgba(255,255,255,0.85) !important; line-height:1.85 !important; margin:0 0 36px !important; font-style:italic !important; display:block !important;">
      But there is something even more powerful.
    </div>

    <!-- Gold divider -->
    <div style="width:70px !important; height:2px !important; background:linear-gradient(90deg,transparent,#ffc107,transparent) !important; margin:0 auto 36px !important; display:block !important;"></div>

    <!-- Lock price box -->
    <div style="display:flex !important; align-items:flex-start !important; gap:22px !important; background:rgba(255,193,7,0.09) !important; border:1px solid rgba(255,193,7,0.32) !important; border-radius:18px !important; padding:30px !important; text-align:left !important; box-sizing:border-box !important;">

      <div style="font-size:40px !important; flex-shrink:0 !important; line-height:1 !important; margin-top:4px !important;">🔒</div>

      <div style="flex:1 !important; min-width:0 !important;">
        <div style="font-size:22px !important; font-weight:800 !important; color:#ffc107 !important; margin-bottom:16px !important; letter-spacing:0.01em !important; display:block !important;">
          Your Price Is Locked Forever
        </div>
        <div style="font-size:15px !important; color:rgba(255,255,255,0.68) !important; line-height:1.8 !important; margin-bottom:12px !important; display:block !important;">
          If you join now, your price will <span style="color:#ffffff !important; font-weight:700 !important;">never increase.</span>
        </div>
        <div style="font-size:15px !important; color:rgba(255,255,255,0.68) !important; line-height:1.8 !important; margin-bottom:18px !important; display:block !important;">
          Even if the membership price rises in the future, you will always keep the price you joined with.
        </div>
        <div style="font-size:15px !important; font-weight:700 !important; color:#ffc107 !important; border-top:1px solid rgba(255,193,7,0.2) !important; padding-top:16px !important; display:block !important; letter-spacing:0.02em !important;">
          ✦ &nbsp;Early members will always have the best price on the platform.
        </div>
      </div>

    </div>

  </div>
</div>

<!-- ══ ORIGINAL HERO (preserved exactly) ══ -->
<section class="membership-hero" id="choose-plan">
  <div class="membership-wrap">
    <div class="membership-hero-card">
      <div>
        <h1>Membership</h1>
        <p>Unlimited access to Courses, E-books &amp; PDFs, Reels, Livestreams, Kemetic Television, Articles, and more.</p>
        <div class="membership-badges">
          <span class="membership-badge">Cancel anytime</span>
          <span class="membership-badge">Watch on any device</span>
          <span class="membership-badge">Creators get paid fairly</span>
        </div>

        
      </div>
      <div style="display:flex; gap:12px; align-items:center; justify-content:flex-end; flex-wrap:wrap">
        <button type="button" class="membership-cta" onclick="chooseplan()">Join €1/mo</button>
        <button type="button" class="membership-cta secondary" onclick="chooseplan()">or €10/year</button>
      </div>
    </div>
  </div>
</section>

<!-- ══ ORIGINAL PRICING SECTION (preserved + enhanced with perks) ══ -->
<section class="membership-section">
  <div class="membership-wrap">
    <h2>Choose your plan</h2>
    <div class="membership-pricing-grid">
      @php
        $hasLifetime = $activeSubscribe && $activeSubscribe->days == 100000;
      @endphp
      @foreach($subscribes as $subscribe)
        @php
          $membershipType = '';
          $planPerks = [];
          $planBadge = '';
          $originalPrice = '';
          $originalLabel = '';
          $founderLabel  = '';
          if ($subscribe->days == 31) {
              $membershipType = 'Monthly Membership';
              $originalPrice   = '€9';
              $originalLabel   = '/ month';
              $founderLabel    = '🔥 Now only €' . $subscribe->price . ' / month';
              $planPerks = ['Full platform access','All courses & reels','Kemetic Television','Livestreams & e-books'];
          } elseif ($subscribe->days == 365) {
              $membershipType = 'Yearly Membership';
              $originalPrice   = '€99';
              $originalLabel   = '/ year';
              $founderLabel    = '🔥 Now only €' . $subscribe->price . ' / year';
              $planPerks = ['Everything in Monthly','Save over 16%','Priority support','Founders price locked'];
              $planBadge = 'Most Popular';
          } elseif ($subscribe->days == 100000) {
              $membershipType = 'Lifetime access to the full platform';
              $planPerks = ['Everything, forever','All future features','Founding Member badge','Never pay again'];
              $originalPrice   = '€299';
              $originalLabel   = 'one time';
              $founderLabel    = '🔥 Now only €' . $subscribe->price . ' forever';
              $planBadge = 'Lifetime';
          } else {
              $membershipType = $subscribe->days . ' days';
              $planPerks = ['Full platform access'];
          }

          $isActive = $activeSubscribe && $activeSubscribe->id == $subscribe->id;
          $hasAnySubscription = !empty($activeSubscribe);
        @endphp
        <form action="/panel/financial/recurringPay-subscribes?auto_redirect=1" method="post" class="membership-w-100" id="upgradeform-{{ $subscribe->id }}">
          {{ csrf_field() }}
          <input name="amount" value="{{ $subscribe->price }}" type="hidden">
          <input name="id" id="upgradeid-{{ $subscribe->id }}" value="{{ $subscribe->id }}" type="hidden">

          <article class="membership-card" id="plan-{{ $subscribe->days == 31 ? 'monthly' : ($subscribe->days == 365 ? 'yearly' : 'lifetime') }}"
                   data-eur="€{{ $subscribe->price }}" data-usd="${{ $subscribe->price }}">

            <!-- @if($planBadge)
              <div class="founders-plan-badge">{{ $planBadge }}</div>
            @endif -->

            <div class="membership-small">{{ $subscribe->title }}</div>

            @if($originalPrice)
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:2px;">
              <span style="font-size:16px;color:rgba(255,255,255,0.35);text-decoration:line-through;font-weight:500;">{{ $originalPrice }}</span>
              <span style="font-size:11px;color:rgba(255,255,255,0.28);">{{ $originalLabel }}</span>
            </div>
            @endif

            <div class="membership-price js-price">€{{ $subscribe->price }}</div>
            <div class="membership-small">{{ $membershipType }}</div>

             @if($founderLabel)
            <div style="display:inline-block;background:rgba(255,193,7,0.12);border:1px solid rgba(255,193,7,0.3);border-radius:8px;padding:6px 12px;font-size:12px;font-weight:700;color:#ffc107;margin:10px 0 6px;">
              {{ $founderLabel }}
            </div>
            @endif
            <!-- <ul class="membership-plan-perks">
              @foreach($planPerks as $perk)
                <li>{{ $perk }}</li>
              @endforeach
            </ul> -->

            @if($isActive)
              @if($subscribe->days == 100000)
                <button type="button" class="membership-cta" disabled style="opacity:.5;cursor:not-allowed;">
                  Lifetime Activated
                </button>
              @else
                <button type="button" class="membership-cta danger" onclick="openCancelPopup({{ $subscribe->id }})">
                  Cancel
                </button>
              @endif
            @elseif($hasLifetime)
              <button type="button" class="membership-cta" disabled style="opacity:.5;cursor:not-allowed;">
                Already Lifetime
              </button>
            @elseif($hasAnySubscription)
              <button type='button' class="membership-cta" onclick="openUpgradePopup({{ $subscribe->id }}, {{ $activeSubscribe->id }})">Upgrade</button>
            @else
              <button type='submit' class="membership-cta" data-join="monthly" 
                onclick="storeRedirectThenLogin1(this.closest('form'))">Join Now</button>
            @endif
          </article>
        </form>
      @endforeach
    </div>
  </div>
</section>

<!-- ══ ORIGINAL FEATURES SECTION (preserved) ══ -->
<section class="membership-section">
  <div class="membership-wrap">
    <h2>Included in membership</h2>
    <div class="membership-features">
      <div class="membership-feature"><div class="membership-f-ico">★</div><div><strong>Courses</strong> Learn from Wisdom Keepers with reels-style lessons &amp; certificates.</div></div>
      <div class="membership-feature"><div class="membership-f-ico">▶</div><div><strong>Reels</strong> Global Top Ranked, Trending, For You, and Live — with chakra actions.</div></div>
      <div class="membership-feature"><div class="membership-f-ico">📺</div><div><strong>Kemetic Television</strong> Old-school live channel + linked courses.</div></div>
      <div class="membership-feature"><div class="membership-f-ico">🔴</div><div><strong>Livestreams</strong> Join live, gift, co-watch with friends.</div></div>
      <div class="membership-feature"><div class="membership-f-ico">📚</div><div><strong>E-books &amp; PDFs</strong> Instant downloads; audiobook options when available.</div></div>
      <div class="membership-feature"><div class="membership-f-ico">📝</div><div><strong>Articles</strong> Full-screen article-reels with inline reading.</div></div>
    </div>
  </div>
</section>

<!-- ══ ORIGINAL TESTIMONIALS (preserved + enhanced with stars) ══ -->
<section class="membership-section">
  <div class="membership-wrap">
    <h2>What members say</h2>
    <div class="membership-testimonials">
      <div class="membership-quote">
        <div class="membership-stars">★★★★★</div>
        "Best €1 I spend monthly. The reels and live classes keep me consistent."
        <div class="by">— Ama</div>
      </div>
      <div class="membership-quote">
        <div class="membership-stars">★★★★★</div>
        "I connected my videos to products with affiliate mode and started earning."
        <div class="by">— Tima</div>
      </div>
      <div class="membership-quote">
        <div class="membership-stars">★★★★★</div>
        "The Television + course link is genius. Nothing else like this exists."
        <div class="by">— Malik</div>
      </div>
    </div>
  </div>
</section>

<!-- ══ FAQ (original + new founders questions) ══ -->
<section class="membership-section">
  <div class="membership-wrap">
    <h2>FAQs</h2>
    <details>
      <summary>Can I switch between EUR and USD?</summary>
      <div class="membership-small">Yes — the prices displayed reflect the selected currency. You'll be charged in that currency at checkout.</div>
    </details>
    <details>
      <summary>What's included at €1/mo or €10/year?</summary>
      <div class="membership-small">Full access to courses, reels, livestreams, Television, articles, and eligible e-books/PDFs.</div>
    </details>
    <details>
      <summary>How does Lifetime work?</summary>
      <div class="membership-small">Single payment; permanent access to member features on this account.</div>
    </details>
    <details>
      <summary>How do creators get paid?</summary>
      <div class="membership-small">We share revenue across views, gifts, affiliate attributions, and sales. Your dashboard shows all metrics.</div>
    </details>
  </div>
</section>

<!-- ══ ORIGINAL STICKY CTA (preserved) ══ -->
<div class="membership-sticky">
  <div class="membership-bar">
    <div class="membership-left">
      <strong style="display:block;color:#ffc107;font-size:13px;letter-spacing:0.05em;">Founders Pricing — Limited Time</strong>
      Lock your rate before it's gone.
    </div>
    <div style="display:flex; gap:10px;">
      <button class="membership-cta secondary" onclick="chooseplan()">€1/mo</button>
      <button class="membership-cta" onclick="chooseplan()">Join Membership</button>
    </div>
  </div>
</div>

<!-- ══ ORIGINAL MODAL (preserved) ══ -->
<div id="membershipModal" class="membership-modal hidden">
  <div class="membership-modal-box">
    <h3 id="modalTitle"></h3>
    <p id="modalDesc"></p>
    <div class="modal-actions">
      <button type="button" class="membership-cta secondary" onclick="closeModal()">No</button>
      <button type="button" class="membership-cta danger" id="modalConfirmBtn">Yes</button>
    </div>
  </div>
</div>

@endsection

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
<script>

  // function storeRedirectThenLogin1(formEl) {
  //     fetch('/membership1/store-redirect1', {
  //         method: 'POST',
  //         headers: {
  //             'Content-Type': 'application/json',
  //             'X-CSRF-TOKEN': '{{ csrf_token() }}'
  //         }
  //     }).finally(function () {
  //         formEl.submit();
  //     });
  // }

  function storeRedirectThenLogin1(formEl) {
    fetch('/membership1/store-redirect1', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.guest) {
            // Don't submit form — go straight to login
            // This avoids panel middleware overwriting the session
            window.location.href = '/login';
        } else {
            formEl.submit();
        }
    });
}
  
  // Currency Toggle
  const currencyButtons = document.querySelectorAll('.pill button');
  const plans = document.querySelectorAll('.card[id^="plan-"]');
  const stickyMonthly = document.querySelector('.sticky [data-join="monthly"]');
  const stickyYearly = document.querySelector('.sticky [data-join="yearly"]');
  const heroJoin = document.querySelector('.hero-card .cta');
  const heroAlt  = document.querySelector('.hero-card .cta.secondary');

  let currency = 'EUR';
  function applyCurrency(cur){
    currency = cur;
    currencyButtons.forEach(b=>{
      const isActive = b.dataset.currency === cur;
      b.classList.toggle('active', isActive);
      b.setAttribute('aria-selected', isActive);
    });
    plans.forEach(card=>{
      const price = card.dataset[cur.toLowerCase()];
      card.querySelector('.js-price').textContent = price;
    });
    // Update sticky/hero labels (simple mapping from the plan cards)
    const pMonthly = document.querySelector('#plan-monthly .js-price').textContent;
    const pYearly  = document.querySelector('#plan-yearly .js-price').textContent;
    stickyMonthly.textContent = `${pMonthly}/mo`;
    stickyYearly.textContent  = `Join ${pYearly}/yr`;
    if (heroJoin) heroJoin.textContent = `Join ${pMonthly}/mo`;
    if (heroAlt)  heroAlt.textContent  = `or ${pYearly}/year`;
  }
  currencyButtons.forEach(btn => btn.addEventListener('click',()=>applyCurrency(btn.dataset.currency)));
  applyCurrency('EUR');

  // Join actions (wire these to your checkout / app bridge)
  function handleJoin(plan){
    // Example: window.location.href = `/checkout?plan=${plan}&currency=${currency}`;
    console.log('JOIN:', plan, 'currency:', currency);
    (function() {

        $.toast({
            heading: 'Success',
            text: 'Join ${plan} (${currency}) — hook this to your checkout',
            bgColor: '#43d477',
            textColor: 'white',
            hideAfter: 10000,
            position: 'bottom-right',
            icon: 'success'
        });
    })();
    
    // alert(`Join ${plan} (${currency}) — hook this to your checkout`);
  }
  document.querySelectorAll('[data-join]').forEach(el=>{
    el.addEventListener('click', ()=>handleJoin(el.dataset.join));
  });

  function chooseplan() {
      const section = document.getElementById('choose-plan');
      if (section) {
          const offset = 30; // adjust for sticky header height
          const top = section.getBoundingClientRect().top + window.pageYOffset - offset;
          window.scrollTo({ top: top, behavior: 'smooth' });
      }

      // Optionally highlight the matching plan card after scroll
      setTimeout(function() {
          const planMap = { monthly: 'plan-monthly', yearly: 'plan-yearly', lifetime: 'plan-lifetime' };
          const cardId = planMap[plan];
          if (cardId) {
              const card = document.getElementById(cardId);
              if (card) {
                  card.style.transition = 'box-shadow 0.3s ease';
                  card.style.boxShadow = '0 0 0 2px #ffc107';
                  setTimeout(() => card.style.boxShadow = '', 2000);
              }
          }
      }, 600);
  }

  // Optional: deep-link when arriving with ?currency=USD or ?plan=yearly
  const params = new URLSearchParams(location.search);
  if (params.get('currency')) applyCurrency(params.get('currency').toUpperCase());
  const qp = params.get('plan');
  if (qp) handleJoin(qp);


  document.addEventListener('DOMContentLoaded', function() {
        // Currency Toggle for membership pages
        const currencyButtons = document.querySelectorAll('.pill button');
        const plans = document.querySelectorAll('.membership-card');
        const stickyMonthly = document.querySelector('.sticky [data-join="monthly"]');
        const stickyYearly = document.querySelector('.sticky [data-join="yearly"]');
        const heroJoin = document.querySelector('.hero [data-join="monthly"]');
        const heroAlt = document.querySelector('.hero [data-join="yearly"]');

        if (currencyButtons.length > 0) {
            let currency = 'EUR';
            
            function applyCurrency(cur){
                currency = cur;
                currencyButtons.forEach(b=>{
                    const isActive = b.dataset.currency === cur;
                    b.classList.toggle('active', isActive);
                    b.setAttribute('aria-selected', isActive);
                });
                
                // Update pricing display based on currency
                plans.forEach(card=>{
                    const priceElement = card.querySelector('.js-price');
                    if (priceElement) {
                        const basePrice = card.dataset.basePrice;
                        if (basePrice) {
                            const convertedPrice = cur === 'USD' ? (parseFloat(basePrice) * 1.1).toFixed(2) : basePrice;
                            priceElement.textContent = cur === 'USD' ? `$${convertedPrice}` : `€${convertedPrice}`;
                        }
                    }
                });
                
                // Update sticky/hero labels
                const monthlyPlan = document.querySelector('#plan-monthly');
                const yearlyPlan = document.querySelector('#plan-yearly');
                
                if (monthlyPlan && stickyMonthly) {
                    const monthlyPrice = monthlyPlan.querySelector('.js-price')?.textContent || '€1';
                    stickyMonthly.textContent = `${monthlyPrice}/mo`;
                }
                
                if (yearlyPlan && stickyYearly) {
                    const yearlyPrice = yearlyPlan.querySelector('.js-price')?.textContent || '€10';
                    stickyYearly.textContent = `Join ${yearlyPrice}/yr`;
                }
                
                if (heroJoin) heroJoin.textContent = `Join ${monthlyPlan?.querySelector('.js-price')?.textContent || '€1'}/mo`;
                if (heroAlt) heroAlt.textContent = `or ${yearlyPlan?.querySelector('.js-price')?.textContent || '€10'}/year`;
            }
            
            currencyButtons.forEach(btn => {
                btn.addEventListener('click', () => applyCurrency(btn.dataset.currency));
            });
            
            // Initialize with EUR
            applyCurrency('EUR');

            // Join actions
            function handleJoin(plan){
                // Redirect to appropriate plan
                const planElement = document.querySelector(`#plan-${plan}`);
                if (planElement) {
                    planElement.closest('form').submit();
                }
            }
            
            document.querySelectorAll('[data-join]').forEach(el=>{
                el.addEventListener('click', ()=>handleJoin(el.dataset.join));
            });

            // Optional: deep-link when arriving with ?currency=USD or ?plan=yearly
            const params = new URLSearchParams(location.search);
            if (params.get('currency')) applyCurrency(params.get('currency').toUpperCase());
        }
    });
</script>
<script>
  let selectedSubscribeId = null;
  let actionType = null;
  let actionsubscritionID = null;

  function openCancelPopup(subscribeId) {
      selectedSubscribeId = subscribeId;
      actionType = 'cancel';

      document.getElementById('modalTitle').innerText = 'Cancel Membership';
      document.getElementById('modalDesc').innerText =
          'Are you sure you want to cancel your active membership?';

      document.getElementById('modalConfirmBtn').onclick = confirmAction;
      document.getElementById('membershipModal').classList.remove('hidden');
  }

  function openUpgradePopup(subscribeId,activeSubscribe) {
      selectedSubscribeId = activeSubscribe;
      actionsubscritionID = subscribeId;
      actionType = 'upgrade';

      document.getElementById('modalTitle').innerText = 'Upgrade Membership';
      document.getElementById('modalDesc').innerText =
          'Your current membership will be replaced with this plan. Continue?';

      document.getElementById('modalConfirmBtn').onclick = confirmAction;
      document.getElementById('membershipModal').classList.remove('hidden');
  }

  function closeModal() {
      document.getElementById('membershipModal').classList.add('hidden');
  }

  function confirmAction() {
      closeModal();

      if (actionType === 'cancel') {
          cancelSubscription();
      }

      if (actionType === 'upgrade') {

      const upgradeForm = document.getElementById('upgradeform');
      const upgradeInput = document.getElementById('upgradeid');

      upgradeInput.value = actionsubscritionID;
      
       fetch("/membership/cancel", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                subscription_id: selectedSubscribeId
            })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
              //   (function() {
          
              //     $.toast({
              //         heading: 'Success',
              //         text: data.message,
              //         bgColor: '#43d477',
              //         textColor: 'white',
              //         hideAfter: 10000,
              //         position: 'bottom-right',
              //         icon: 'success'
              //     });
              // })();
                //alert(data.message);
                return;
            }

           upgradeForm.submit();
        })
        .catch(err => {
            console.error(err);
            (function() {
        
                $.toast({
                    heading: 'Failed',
                    text: 'Something went wrong',
                    bgColor: '#f63c3c',
                    textColor: 'white',
                    hideAfter: 10000,
                    position: 'bottom-right',
                    icon: 'Failed'
                });
            })();
            // alert("Something went wrong");
        });
      }
  }

  function cancelSubscription() {
      fetch("/membership/cancel", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                subscription_id: selectedSubscribeId
            })
        })
        .then(res => res.json())
        .then(data => {
           (function() {
        
                $.toast({
                    heading: 'Success',
                    text: data.message,
                    bgColor: '#43d477',
                    textColor: 'white',
                    hideAfter: 10000,
                    position: 'bottom-right',
                    icon: 'Success'
                });
            })();
            // alert("Something went wrong");
            
            if (data.success) location.reload();
        })
        .catch(err => {
            console.error(err);
            (function() {
        
                $.toast({
                    heading: 'Failed',
                    text: 'Something went wrong',
                    bgColor: '#f63c3c',
                    textColor: 'white',
                    hideAfter: 10000,
                    position: 'bottom-right',
                    icon: 'Failed'
                });
            })();
            // alert("Something went wrong");
        });
  }
</script>