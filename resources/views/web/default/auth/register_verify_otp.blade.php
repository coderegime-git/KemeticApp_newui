@extends(getTemplate().'.layouts.app')

@section('content')
<div class="container">
    <div class="row login-container">
        <div class="col-12 col-md-6 pl-0">
            <img src="{{ getPageBackgroundSettings('register') }}"  style="width: -webkit-fill-available;" class="img-cover" alt="" loading="lazy">
        </div>
        <div class="col-12 col-md-6">
            <div class="login-card">
                <h1 class="font-20 font-weight-bold">Verify Email</h1>
                <p class="text-secondary mt-10">
                    We sent a 6-digit OTP to <strong>{{ session('register_otp_email') }}</strong>
                </p>

                <form method="POST" action="/register/verify-otp" class="mt-35">
                    @csrf
                    <div class="form-group">
                        <label class="input-label">Enter OTP</label>
                        <input type="text" name="otp" class="form-control text-center font-24 letter-spacing-10"
                               maxlength="6" placeholder="------" autofocus required>
                        @error('otp')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-center mt-20">
                        <button type="submit" class="btn btn-primary btn-block mt-20">
                            Verify & Activate Account
                        </button>
                    </div>
                </form>

                <div class="text-center mt-20">
                    <span class="text-secondary">Didn't receive the OTP?</span>
                    <a href="/register/resend-otp" class="text-secondary font-weight-bold">Resend OTP</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
