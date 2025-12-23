@extends('web.default.layouts.app')

@section('content')
<div class="auth-page">
  <div class="login-shell">
    <!-- LEFT: AUTH CARD -->
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
      <div id="t-signin" class="login-tab active" onclick="switchTab('signin')">Sign in</div>
      <div id="t-signup" class="login-tab" onclick="switchTab('signup')">Create account</div>
    </div>

    <!-- SIGN IN -->
    <form id="form-signin" class="login-form" method="Post" action="/login">
      <div>
        <label class="login-label">Email</label>
        <div class="login-inp"><input type="email" name="email" placeholder="you@kemetic.app" required></div>
          @error('email')
          <div class="invalid-feedback">
              {{ $message }}
          </div>
          @enderror
      </div>
      <div>
        <label class="login-label">Password</label>
        <div class="login-inp">
          <input id="si-pass" name="password" type="password" placeholder="•••••••" minlength="6" required>
          <button type="button" class="login-btn login-btn-ghost" style="padding:6px 10px" onclick="togglePw('si-pass', this)">Show</button>
        </div>
      </div>

      <div class="login-action">
        <label class="login-remember"><input type="checkbox" checked> Remember me</label>
        <a href="/forget-password" style="color:#ffd769;text-decoration:none;">Forgot password?</a>
      </div>

      <div class="login-row">
        <button class="login-btn login-btn-gold" type="submit">Sign in</button>
        <button class="login-btn login-btn-ghost" type="button" onclick="switchTab('signup')">Create account</button>
      </div>

      <div class="login-row">
        @if(!empty(getFeaturesSettings('show_google_login_button')))
          <button class="login-btn login-btn-ghost" type="button"><a href="/google" target="_blank">Continue with Google</a></button>
        @endif
        <!-- <button class="login-btn login-btn-ghost" type="button">Continue with Apple</button> -->
      </div>

      <div class="login-fine">
        By continuing, you agree to our <a href="pages/terms" style="color:#ffd769;">Terms</a> and <a href="pages/terms" style="color:#ffd769;">Privacy</a>.
      </div>
    </form>

    <!-- SIGN UP -->
    <form id="form-signup" class="login-form" style="display:none"  method="post" action="/register">
       <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <div class="login-row">

      @php
        $registerMethod = getGeneralSettings('register_method') ?? 'mobile';
        $showOtherRegisterMethod = getFeaturesSettings('show_other_register_method') ?? false;
        $showCertificateAdditionalInRegister = getFeaturesSettings('show_certificate_additional_in_register') ?? false;
        $selectRolesDuringRegistration = getFeaturesSettings('select_the_role_during_registration') ?? null;
    @endphp

      @if(!empty($selectRolesDuringRegistration) and count($selectRolesDuringRegistration))
        <div>
          <label class="login-label">Account type</label>
          <div class="login-inp">
            <div class="wizard-custom-radio-item flex-grow-1">
             <input type="radio" name="account_type" value="user" id="role_user" class="" checked>
             <label class="font-12 cursor-pointer px-15 py-10" for="role_user">{{ trans('update.role_user') }}</label>
             </div>
             @foreach($selectRolesDuringRegistration as $selectRole)
              @if($selectRole !== 'organization' )      {{-- Hide Temple of Learning --}}									
                <div class="wizard-custom-radio-item flex-grow-1">
                    <input type="radio" name="account_type" value="{{ $selectRole }}" id="role_{{ $selectRole }}" class="">
                    <label class="font-12 cursor-pointer px-15 py-10" for="role_{{ $selectRole }}">{{ trans('update.role_'.$selectRole) }}</label>
                </div>
              @endif
              @endforeach
          </div>
          @error('account_type')
          <div class="invalid-feedback">
              {{ $message }}
          </div>
          @enderror
        </div>
      @endif
</div>
        <div>
          <label class="login-label">Full name</label>
          <div class="login-inp"><input type="text" name="full_name" placeholder="Full name" value="{{ old('full_name') }}" required></div>
          @error('full_name')
          <div class="invalid-feedback">
              {{ $message }}
          </div>
          @enderror
        </div>
      
      <div>
        <label class="login-label">Email</label>
        <div class="login-inp"><input type="email" name="email" placeholder="you@kemetic.app" required value="{{ old('email') }}" ></div>
          @error('email')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
      </div>
      <div>
        <label class="login-label">Password</label>
        <div class="login-inp">
          <input id="su-pass" name="password" type="password" placeholder="At least 6 characters" minlength="6" required>
          <button type="button" class="login-btn login-btn-ghost" style="padding:6px 10px" onclick="togglePw('su-pass', this)">Show</button>
        </div>
        @error('password')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
      </div>
      <div>
        <label class="login-label">Confirm password</label>
        <div class="login-inp"><input id="su-pass2"  name="password_confirmation"  type="password" placeholder="Repeat password" minlength="6" required></div>
         @error('password_confirmation')
          <div class="invalid-feedback">
              {{ $message }}
          </div>
          @enderror
      </div>

      @if(getFeaturesSettings('timezone_in_register'))
        @php
            $selectedTimezone = getGeneralSettings('default_time_zone');
        @endphp
      <div>
        <label class="login-label">Timezone</label>
        <div class="login-inp">
          <select name="timezone" required>
            <option value="" disabled selected>Select your timezone</option>
            @foreach(\DateTimeZone::listIdentifiers() as $timezone)
              <option value="{{ $timezone }}" {{ (old('timezone', $selectedTimezone) == $timezone) ? 'selected' : '' }}>{{ $timezone }}</option>
            @endforeach
          </select>
        </div>
        @error('timezone')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
                            
      @endif
      <div class="login-action">
        <label class="login-remember"><input type="checkbox" name="term" required> I agree to the Terms & Privacy</label>
      </div>

      <div class="login-row">
        <button class="login-btn login-btn-gold" type="submit" onclick="checkMatch()">Create account</button>
        <button class="login-btn login-btn-ghost" type="button" onclick="switchTab('signin')">Back to sign in</button>
      </div>

      <div class="login-row">
        @if(!empty(getFeaturesSettings('show_google_login_button')))
          <button class="login-btn login-btn-ghost" type="button"><a href="/google" target="_blank">Continue with Google</a></button>
        @endif
        <!-- <button class="login-btn login-btn-ghost" type="button">Continue with Apple</button> -->
      </div>
    </form>
  </section>

  <!-- RIGHT: PROMO / ART -->
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


@endsection

@push('scripts_bottom')
   <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/default/js/parts/forgot_password.min.js"></script>
<script>
  function switchTab(which){
    const si = document.getElementById('form-signin');
    const su = document.getElementById('form-signup');
    const ts = document.getElementById('t-signin');
    const tu = document.getElementById('t-signup');
    const signIn = which === 'signin';
    si.style.display = signIn ? 'grid' : 'none';
    su.style.display = signIn ? 'none' : 'grid';
    ts.classList.toggle('active', signIn);
    tu.classList.toggle('active', !signIn);
  }
  
  function togglePw(id, btn){
    const el = document.getElementById(id);
    el.type = el.type === 'password' ? 'text' : 'password';
    btn.textContent = el.type === 'password' ? 'Show' : 'Hide';
  }
  
  function checkMatch(){
    const a = document.getElementById('su-pass').value;
    const b = document.getElementById('su-pass2').value;
    if(a !== b){ 
      alert('Passwords do not match'); 
      return false;
    }
    return true;
  }

  // Auto-switch to signup if there are signup errors
  document.addEventListener('DOMContentLoaded', function() {
    @if($errors->has('first_name') || $errors->has('last_name') || $errors->has('terms'))
      switchTab('signup');
    @endif
  });
</script>
@endpush