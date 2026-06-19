@extends('web.default.layouts.app')
<style>
@keyframes slideInToast {
  from { opacity: 0; transform: translateX(40px); }
  to   { opacity: 1; transform: translateX(0); }
}
</style>
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
       <div class="login-row">
        <div style="flex:1">
          <label class="login-label">First name</label>
          <div class="login-inp">
            <input type="text" name="first_name" placeholder="First name" value="{{ old('first_name') }}" required>
          </div>
          @error('first_name')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div style="flex:1">
          <label class="login-label">Last name</label>
          <div class="login-inp">
            <input type="text" name="last_name" placeholder="Last name" value="{{ old('last_name') }}" required>
          </div>
          @error('last_name')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div>
        <label class="login-label">Display Name</label>
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
        <div id="password-match-error" class="invalid-feedback" style="display: none;">
          Passwords do not match
        </div> 
        @error('password_confirmation')
          <div class="invalid-feedback">
              {{ $message }}
          </div>
          @enderror
      </div>

      <div>
        <label class="login-label">Country</label>
        <div class="login-inp">
          <select name="country_id" required>
            <option value="">{{ trans('update.select_country') }}</option>
            @foreach($countries as $country)
             <option value="{{ $country->id }}" >{{ $country->title }}</option>
            @endforeach
          </select>
        </div>
        @error('timezone')
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
      </div>                     
      @endif
      <div class="login-action" >
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
      <p>Portals • Livestreams • Courses • Books • Articles • Shop</p>
      <div class="login-chips">
        <div class="login-chip">💎 €10/yr membership</div>
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

    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
  
  function togglePw(id, btn){
    const el = document.getElementById(id);
    el.type = el.type === 'password' ? 'text' : 'password';
    btn.textContent = el.type === 'password' ? 'Show' : 'Hide';
  }

  function checkMatch(){
    const a = document.getElementById('su-pass').value;
    const b = document.getElementById('su-pass2').value;
    const errorDiv = document.getElementById('password-match-error');
    if(a !== b){ 
      errorDiv.style.display = 'block';
      return false;
    }
    else
    {
      errorDiv.style.display = 'none';
      return true;
    }

    return true;
  }
  

  // Auto-switch to signup if there are signup errors or hash
  document.addEventListener('DOMContentLoaded', function() {

    if (window.location.hash === '#register' || window.location.pathname === '/register') {
        switchTab('signup');
    } else {
        switchTab('signin');
    }

    window.addEventListener('hashchange', function() {
        if (window.location.hash === '#register') {
            switchTab('signup');
        } else {
            switchTab('signin');
        }
    });

    // Auto-switch to signup tab if any signup field has an error
    @if(
      $errors->has('first_name') || $errors->has('last_name') ||
      $errors->has('full_name')  || $errors->has('email')     ||
      $errors->has('password')   || $errors->has('password_confirmation') ||
      $errors->has('country_id') || $errors->has('timezone')  ||
      $errors->has('term')       || $errors->has('account_type')
    )
      switchTab('signup');

      // Collect all error messages and show as toast
      const allErrors = [
        @foreach($errors->all() as $error)
          "{{ $error }}",
        @endforeach
      ];
      if (allErrors.length > 0) {
        showToast(allErrors.join('<br>'), 'Error', 'error');
      }
    @endif

    // Show success toast if session has a success message
    @if(session('success'))
      showToast("{{ session('success') }}", 'Success', 'success');
    @endif

    // Show toast from session data
    @if(session('toast'))
      const toastData = @json(session('toast'));
      showToast(toastData.msg, toastData.title, toastData.status || 'error');
    @endif

    // Check for login_failed_active_session toast
    @if(session('login_failed_active_session'))
      const loginFailedToast = @json(session('login_failed_active_session'));
      showToast(loginFailedToast.msg, loginFailedToast.title, loginFailedToast.status || 'error');
    @endif
});

// Fixed showToast function
function showToast(message, title = '', status = 'error') {
    (function () {
        "use strict";
        $.toast({
            heading: title,
            text: message,
            bgColor: status === 'success' ? '#43d477' : '#f63c3c',
            textColor: 'white',
            hideAfter: 10000,
            position: 'bottom-right',
            icon: status
        });
    })(jQuery);
}
</script>
@endpush