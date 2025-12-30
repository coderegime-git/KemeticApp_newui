@extends('web.default.layouts.app')

@section('content')
<!-- Header -->
<!-- <header class="membership-topbar">
  <div class="membership-wrap row">
    <div class="membership-brand">
      <div class="membership-logo"></div>
      <div>Kemetic App</div>
    </div>
    <div class="membership-pill" role="tablist" aria-label="Currency">
      <button class="active" data-currency="EUR" aria-selected="true">EUR</button>
      <button data-currency="USD" aria-selected="false">USD</button>
    </div>
  </div>
</header> -->


<!-- Hero -->
<section class="membership-hero">
  <div class="membership-wrap">
    <div class="membership-hero-card">
      <div>
        <h1>Membership</h1>
        <p>Unlimited access to Courses, E-books & PDFs, Reels, Livestreams, Kemetic Television, Articles, and more.</p>
        <div class="membership-badges">
          <span class="membership-badge">Cancel anytime</span>
          <span class="membership-badge">Watch on any device</span>
          <span class="membership-badge">Creators get paid fairly</span>
        </div>
      </div>
      <div style="display:flex; gap:12px; align-items:center; justify-content:flex-end; flex-wrap:wrap">
        <button class="membership-cta" data-join="monthly">Join €1/mo</button>
        <button class="membership-cta secondary" data-join="yearly">or €10/year</button>
      </div>
    </div>
  </div>
</section>

<!-- Pricing -->
<section class="membership-section">
  <div class="membership-wrap">
    <h2>Choose your plan</h2>
    <div class="membership-pricing-grid">
      @foreach($subscribes as $subscribe)
        @php
          $membershipType = '';
          if ($subscribe->days == 31) {
              $membershipType = 'Monthly Membership';
          } elseif ($subscribe->days == 365) {
              $membershipType = 'Yearly Membership';
          } elseif ($subscribe->days == 100000) {
              $membershipType = 'Lifetime access to the full platform';
          } else {
              $membershipType = $subscribe->days . ' days';
          }
        @endphp
        <form action="/panel/financial/recurringPay-subscribes?auto_redirect=1" method="post" class="membership-w-100">
          {{ csrf_field() }}
          <input name="amount" value="{{ $subscribe->price }}" type="hidden">
          <input name="id" value="{{ $subscribe->id }}" type="hidden">

          <article class="membership-card" id="plan-monthly" data-eur="€{{ $subscribe->price }}" data-usd="${{ $subscribe->price }}">
            <div class="membership-small">{{ $subscribe->title }}</div>
            <div class="membership-price js-price">€{{ $subscribe->price }}</div>
            <div class="membership-small">{{ $membershipType }}</div>
            <button type='submit' class="membership-cta" data-join="monthly">Join Now</button>
          </article>
        </form>
      @endforeach
      <!-- Additional plans can be added here -->
    </div>
  </div>
</section>

<!-- Features -->
<section class="membership-section">
  <div class="membership-wrap">
    <h2>Included in membership</h2>
    <div class="membership-features">
      <div class="membership-feature"><div class="membership-f-ico">★</div><div><strong>Courses</strong> Learn from Wisdom Keepers with reels-style lessons & certificates.</div></div>
      <div class="membership-feature"><div class="membership-f-ico">▶</div><div><strong>Reels</strong> Global Top Ranked, Trending, For You, and Live — with chakra actions.</div></div>
      <div class="membership-feature"><div class="membership-f-ico">📺</div><div><strong>Kemetic Television</strong> Old-school live channel + linked courses.</div></div>
      <div class="membership-feature"><div class="membership-f-ico">🔴</div><div><strong>Livestreams</strong> Join live, gift, co-watch with friends.</div></div>
      <div class="membership-feature"><div class="membership-f-ico">📚</div><div><strong>E-books & PDFs</strong> Instant downloads; audiobook options when available.</div></div>
      <div class="membership-feature"><div class="membership-f-ico">📝</div><div><strong>Articles</strong> Full-screen article-reels with inline reading.</div></div>
    </div>
  </div>
</section>

<!-- Testimonials -->
<section class="membership-section">
  <div class="membership-wrap">
    <h2>What members say</h2>
    <div class="membership-testimonials">
      <div class="membership-quote">"Best €1 I spend monthly. The reels and live classes keep me consistent."<div class="by">— Ama</div></div>
      <div class="membership-quote">"I connected my videos to products with affiliate mode and started earning."<div class="by">— Tima</div></div>
      <div class="membership-quote">"The Television + course link is genius."<div class="by">— Malik</div></div>
    </div>
  </div>
</section>

<!-- FAQ -->
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

<!-- Sticky CTA -->
<div class="membership-sticky">
  <div class="membership-bar">
    <div class="membership-left">Ready to unlock everything?</div>
    <div style="display:flex; gap:10px;">
      <button class="membership-cta secondary" data-join="monthly">€1/mo</button>
      <button class="membership-cta" data-join="yearly">Join Membership</button>
    </div>
  </div>
</div>
@endsection
<script>
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
    alert(`Join ${plan} (${currency}) — hook this to your checkout`);
  }
  document.querySelectorAll('[data-join]').forEach(el=>{
    el.addEventListener('click', ()=>handleJoin(el.dataset.join));
  });

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


