<section class="kemetic-footer-bar">
  <div class="kemetic-footer-bar-top">
    <span class="home-chip">© Kemetic.app</span>

    @if(auth()->check())
      <a href="/membership" class="home-chip home-chip-gold">
        <span class="topheader-material-symbols-outlined" style="font-size:13px;">workspace_premium</span>
        {{ trans('membership') ?? 'Membership' }} — Upgrade
      </a>
    @else
      <a href="javascript:void(0)" onclick="storeRedirectThenLogin()" class="home-chip home-chip-gold">
        <span class="topheader-material-symbols-outlined" style="font-size:13px;">workspace_premium</span>
        {{ trans('membership') ?? 'Membership' }} €1/mo or €10/yr
      </a>
    @endif
  </div>
  <div class="kemetic-footer-bar-bottom">
    <a href="/pages/newsletter" class="kemetic-newsletter-btn">
      <span class="topheader-material-symbols-outlined" style="font-size:15px;">mail</span>
      Join Newsletter
    </a>
  </div>
</section>

<style>
.kemetic-footer-bar {
  display: flex;
  flex-direction: column;
  gap: 10px;
  padding: 14px 16px;
  border-top: 1px solid rgba(255, 255, 255, 0.06);
  background: rgba(5, 2, 15, 0.6);
  border-radius: 0 0 18px 18px;
}

.kemetic-footer-bar-top {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
}

.kemetic-footer-bar-bottom {
  display: flex;
}

.home-chip {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  font-size: 11px;
  padding: 4px 10px;
  border-radius: 999px;
  border: 1px solid rgba(255, 255, 255, 0.18);
  color: rgba(255, 255, 255, 0.65);
  background: rgba(255, 255, 255, 0.05);
  text-decoration: none;
  white-space: nowrap;
}

.home-chip-gold {
  border-color: rgba(255, 202, 40, 0.4);
  color: var(--chakra-gold, #FFCA28);
  background: rgba(255, 202, 40, 0.08);
  transition: background 0.15s ease, box-shadow 0.15s ease;
}

.home-chip-gold:hover {
  background: rgba(255, 202, 40, 0.15);
  box-shadow: 0 0 12px rgba(255, 202, 40, 0.3);
  color: var(--chakra-gold, #FFCA28);
  text-decoration: none;
}

.kemetic-newsletter-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 20px;
  border-radius: 999px;
  background: linear-gradient(135deg, var(--chakra-gold, #FFCA28), #FFD54F);
  color: #05020B;
  font-size: 12px;
  font-weight: 700;
  text-decoration: none;
  box-shadow: 0 0 12px rgba(255, 202, 40, 0.35);
  transition: box-shadow 0.2s ease, transform 0.2s ease;
  white-space: nowrap;
}

.kemetic-newsletter-btn:hover {
  box-shadow: 0 0 22px rgba(255, 202, 40, 0.65);
  transform: translateY(-1px);
  color: #05020B;
  text-decoration: none;
}

/* Desktop: single row side by side */
@media (min-width: 600px) {
  .kemetic-footer-bar {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
  }
}
</style>
<script>

  function storeRedirectThenLogin() {
    fetch('/membership/store-redirect', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }).finally(function () {
        window.location.href = '/login'; // ← redirect directly
    });
  }
</script>