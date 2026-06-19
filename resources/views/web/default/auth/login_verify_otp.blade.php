@extends('web.default.layouts.app')

@section('content')
<div class="auth-page">
  <div class="login-shell">

    <section class="login-card">
      <header class="login-card-head">
        <div class="login-logo">KA</div>
        <div style="font-weight:900; letter-spacing:.2px">Kemetic app</div>
        <div class="login-k-dots">
          <div class="login-dot" style="background:var(--red)"></div>
          <div class="login-dot" style="background:var(--orange)"></div>
          <div class="login-dot" style="background:var(--yellow)"></div>
          <div class="login-dot" style="background:var(--green)"></div>
          <div class="login-dot" style="background:var(--blue)"></div>
        </div>
      </header>

      <div class="login-tabs">
        <div class="login-tab active">Verify Email</div>
      </div>

      <form class="login-form" method="POST" action="/login/verify-otp">
        @csrf

        <p style="color:#aaa; font-size:13px; margin:0 0 16px">
          We sent a 6-digit OTP to <strong style="color:#ffd769">{{ session('login_otp_email') }}</strong>.
          <br>Please enter it below to verify your account.
        </p>

        <div>
          <label class="login-label">Enter OTP</label>
          <div class="login-inp">
            <input type="number" name="otp" placeholder="000000"
                   maxlength="6" autofocus required
                   style="text-align:center; font-size:22px; letter-spacing:8px">
          </div>
          @error('otp')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="login-row">
          <button class="login-btn login-btn-gold" type="submit">Verify & Continue</button>
        </div>

        <div style="text-align:center; margin-top:12px">
          <span style="color:#aaa; font-size:13px">Didn't receive the OTP?</span>
          <a href="/login/resend-otp" style="color:#ffd769; font-size:13px; margin-left:6px">Resend OTP</a>
        </div>

        <div style="text-align:center; margin-top:8px">
          <a href="/login" style="color:#aaa; font-size:12px">Back to login</a>
        </div>
      </form>
    </section>

    <aside class="login-side">
      <div class="login-art"></div>
      <div class="login-overlay">
        <h3>Welcome to the Kemetic App</h3>
        <p>Reels • Livestreams • Courses • Books • Articles • Shop</p>
        <div class="login-chips">
          <div class="login-chip">💎 €1/mo membership</div>
          <div class="login-chip">⭐ 3,000+ reviews</div>
          <div class="login-chip">🔒 Secure</div>
        </div>
      </div>
    </aside>

  </div>
</div>
@endsection

@push('scripts_bottom')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    @if(session('toast'))
      showToast("{{ session('toast')['msg'] }}", "{{ session('toast')['status'] }}");
    @endif
  });

  function showToast(message, type) {
    const colors = {
      error:   { bg: '#2a1a1a', border: '#e53e3e', icon: '✕', iconColor: '#e53e3e' },
      success: { bg: '#1a2a1a', border: '#38a169', icon: '✓', iconColor: '#38a169' },
    };
    const c = colors[type] || colors.error;
    const toast = document.createElement('div');
    toast.innerHTML = `
      <span style="font-size:16px;color:${c.iconColor};font-weight:bold">${c.icon}</span>
      <span style="flex:1;font-size:13px;line-height:1.5">${message}</span>
      <button onclick="this.parentElement.remove()" style="background:none;border:none;color:#aaa;font-size:16px;cursor:pointer">✕</button>
    `;
    toast.style.cssText = `
      position:fixed;top:20px;right:20px;z-index:9999;
      display:flex;align-items:flex-start;gap:10px;
      padding:14px 16px;background:${c.bg};
      border:1px solid ${c.border};border-radius:10px;
      box-shadow:0 4px 24px rgba(0,0,0,0.4);
      max-width:360px;color:#f0f0f0;
    `;
    document.body.appendChild(toast);
    setTimeout(() => { if (toast.parentElement) toast.remove(); }, 5000);
  }
</script>
@endpush
