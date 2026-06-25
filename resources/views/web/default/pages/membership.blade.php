@extends('web.default.layouts.app')
<style>
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
  .membership-hero .membership-pill {
    width: fit-content !important;
    margin-left: auto;
  }
  @media (max-width: 820px) {
    .membership-hero .membership-pill {
        width: fit-content !important;
        margin-right: 0 !important;
    }
  }
</style>
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


<section class="membership-hero">
  <div class="membership-video-wrapper">
    <div class="membership-video-container">
      @php
      $videoDemo = "https://youtu.be/9xBfox5lvLo?is=9ciOarG_yRkC3IVO";
       $isYoutube = true;
        $isIframe = true;
        // Extract YouTube video ID
        $youtubeId = '';
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $videoDemo, $matches)) {
            $youtubeId = $matches[1];
        }
        $iframeSrc = "https://www.youtube.com/embed/{$youtubeId}?autoplay=1&controls=1&showinfo=0&rel=0&modestbranding=1";
      @endphp
      <iframe 
          id="videoIframe"
          class="img-cover course-cover-img"
          src="{{ $iframeSrc }}"
          frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowfullscreen
          loading="lazy"
          data-original-src="{{ $iframeSrc }}"
          data-current-time="0"
          data-is-playing="false">
      </iframe>
    </div>
  </div>
  <div style="display:flex; justify-content:flex-end; width:100%; margin-bottom: 15px; padding-right: 20px;">
    <div class="membership-pill" role="tablist" aria-label="Currency" id="currencyToggle">
      <button class="active" data-currency="EUR" aria-selected="true">EUR</button>
      <button data-currency="USD" aria-selected="false">USD</button>
    </div>
  </div>
  <div class="membership-wrap">
    <div class="membership-hero-card">
      <div>
        <h1>Membership</h1>
        <p>Unlimited access to Courses, E-books & PDFs, Portals, Livestreams, Kemetic Television, Articles, and more.</p>
        <div class="membership-badges">
          <span class="membership-badge">Cancel anytime</span>
          <span class="membership-badge">Watch on any device</span>
          <span class="membership-badge">Creators get paid fairly</span>
        </div>
      </div>
      <div style="display:flex; gap:12px; align-items:center; justify-content:flex-end; flex-wrap:wrap">
        <button type="button" class="membership-cta" onclick="chooseplan()">Join €33/Lifetime</button>
        <button type="button" class="membership-cta secondary" onclick="chooseplan()">or €10/year</button>
      </div>
    </div>
  </div>
</section>

<!-- Pricing -->
<section class="membership-section" id="choose-plan">
  <div class="membership-wrap">
    <h2>Choose your plan</h2>
    <div class="membership-pricing-grid">
      @php
        $hasLifetime = $activeSubscribe && $activeSubscribe->days == 100000;
      @endphp
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

          $isActive = $activeSubscribe && $activeSubscribe->id == $subscribe->id;
          $hasAnySubscription = !empty($activeSubscribe);

        @endphp
        <form action="/panel/financial/recurringPay-subscribes?auto_redirect=1" method="post" class="membership-w-100" id="upgradeform">
          {{ csrf_field() }}
          <!-- <input name="amount" value="{{ $subscribe->price }}" type="hidden"> -->
           <input name="currency" class="js-currency-input" value="EUR" type="hidden">
            <input name="amount" 
           class="js-amount-input"
           value="{{ $subscribe->price }}" 
           data-eur-amount="{{ $subscribe->price }}"
           data-usd-amount="{{ $subscribe->days == 31 ? '2' : ($subscribe->days == 365 ? '11' : '33') }}"
           type="hidden">
          <input name="id" id="upgradeid" value="{{ $subscribe->id }}" type="hidden">

          <article class="membership-card" id="plan-{{ $subscribe->id }}" data-base-price="{{ $subscribe->price }}" data-eur="€{{ $subscribe->price }}" data-usd="{{ $subscribe->days == 31 ? '$2' : ($subscribe->days == 365 ? '$11' : '$33') }}">
            <div class="membership-small">{{ $subscribe->title }}</div>
            <div class="membership-price js-price">€{{ $subscribe->price }}</div>
            <div class="membership-small">{{ $membershipType }}</div>
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
              onclick="storeRedirectThenLogin(this.closest('form'))">Join Now</button>
            @endif
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
      <div class="membership-feature"><div class="membership-f-ico">▶</div><div><strong>Portals</strong> Global Top Ranked, Trending, For You, and Live — with chakra actions.</div></div>
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
      <div class="membership-quote">"Best €10 I spend Yearls. The Portals and live classes keep me consistent."<div class="by">— Ama</div></div>
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
      <summary>What's included at €10/year or €33/Lifetime?</summary>
      <div class="membership-small">Full access to courses, Portals, livestreams, Television, articles, and eligible e-books/PDFs.</div>
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
        <button type="button" class="membership-cta secondary" onclick="chooseplan()">€10/year</button>
        <button type="button" class="membership-cta" onclick="chooseplan()">Join Membership</button>
      </div>
  </div>
</div>

<div id="membershipModal" class="membership-modal hidden">
  <div class="membership-modal-box">

      <h3 id="modalTitle"></h3>
      <p id="modalDesc"></p>

      <div class="modal-actions">
        <button class="membership-cta secondary" onclick="closeModal()">No</button>
        <button class="membership-cta danger" id="modalConfirmBtn">Yes</button>
      </div>
  </div>
</div>
@endsection
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
<script>

  function storeRedirectThenLogin(formEl) {
      fetch('/membership/store-redirect', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
      }).finally(function () {
          formEl.submit();
      });
  }

  const usdMap = { 1: '$2', 10: '$11', 33: '$33' };
  let currentCurrency = 'EUR';

  function applyGlobalCurrency(cur) {
    currentCurrency = cur;

    document.querySelectorAll('#currencyToggle button').forEach(b => {
        const isActive = b.dataset.currency === cur;
        b.classList.toggle('active', isActive);
        b.setAttribute('aria-selected', String(isActive));
    });

    document.querySelectorAll('.membership-card').forEach(card => {
        const el = card.querySelector('.js-price');
        if (!el) return;
        if (cur === 'USD') {
            el.textContent = card.dataset.usd;
        } else {
            el.textContent = card.dataset.eur;
        }
    });

    // ← ADD THIS: update hidden amount inputs so Stripe gets correct value
    document.querySelectorAll('.js-amount-input').forEach(input => {
        if (cur === 'USD') {
            input.value = input.dataset.usdAmount;
        } else {
            input.value = input.dataset.eurAmount;
        }
    });

    document.querySelectorAll('.js-currency-input').forEach(input => {
        input.value = cur;
    });

    // Update sticky CTA button label
    const stickyBtn = document.querySelector('.membership-sticky .membership-cta.secondary');
    if (stickyBtn) {
        stickyBtn.textContent = cur === 'USD' ? '$11/yr' : '€10/yr';
    }

    // Update hero CTA button labels
    const heroBtns = document.querySelectorAll('.membership-hero-card .membership-cta');
    // if (heroBtns[0]) heroBtns[0].textContent = cur === 'USD' ? 'Join $2/mo' : 'Join €1/mo';
    if (heroBtns[1]) heroBtns[1].textContent = cur === 'USD' ? 'or $11/year' : 'or €10/year';

    // Update Join Now button text in cards (if needed)
    document.querySelectorAll('.membership-card').forEach(card => {
        const btn = card.querySelector('button[data-join]');
        if (btn) {
           const isMonthly = card.querySelector('.membership-small').textContent.toLowerCase().includes('monthly');
           const isYearly = card.querySelector('.membership-small').textContent.toLowerCase().includes('yearly');
           // Logic to update button text if desired, but user didn't explicitly ask for it
        }
    });
}

  document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('#currencyToggle button').forEach(btn => {
          btn.addEventListener('click', () => applyGlobalCurrency(btn.dataset.currency));
      });

      // Init default
      applyGlobalCurrency('EUR');

      // Deep-link support: ?currency=USD
      const qCur = new URLSearchParams(location.search).get('currency');
      if (qCur) applyGlobalCurrency(qCur.toUpperCase());
  });

  

  function chooseplan() {
      const section = document.getElementById('choose-plan');
      if (section) {
          const offset = 80;
          const top = section.getBoundingClientRect().top + window.pageYOffset - offset;
          window.scrollTo({ top: top, behavior: 'smooth' });
      }
  }
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

