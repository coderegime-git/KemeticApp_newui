@php
    $registerMethod = getGeneralSettings('register_method') ?? 'mobile';
    $showOtherRegisterMethod = getFeaturesSettings('show_other_register_method') ?? false;
@endphp

@if($showOtherRegisterMethod)
    <div class="d-flex align-items-center wizard-custom-radio mb-20">
        <div class="wizard-custom-radio-item flex-grow-1">
            <input type="radio" name="type" value="email" id="emailType" class="" {{ (($registerMethod == 'email' and empty(old('type'))) or old('type') == "email") ? 'checked' : '' }}>
            <label class="font-12 cursor-pointer px-15 py-10" for="emailType">{{ trans('public.email') }}</label>
        </div>

        <div class="wizard-custom-radio-item flex-grow-1">
            <input type="radio" name="type" value="mobile" id="mobileType" class="" {{ (($registerMethod == 'mobile' and empty(old('type'))) or old('type') == "mobile") ? 'checked' : '' }}>
            <label class="font-12 cursor-pointer px-15 py-10" for="mobileType">{{ trans('public.mobile') }}</label>
        </div>
    </div>

    <div class="js-email-fields form-group {{ (($registerMethod == 'email' and empty(old('type'))) or old('type') == "email") ? '' : 'd-none' }}">
        <label class="input-label" for="email">{{ trans('public.email') }}:</label>
        <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" id="email"
               value="{{ old('email') }}" aria-describedby="emailHelp"
               pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$"
               title="{{ 'Please enter a valid email address (e.g. user@.com)' }}"
               oninput="validateEmail(this)">
        @error('email')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>


    <div class="js-mobile-fields {{ (($registerMethod == 'mobile' and empty(old('type'))) or old('type') == "mobile") ? '' : 'd-none' }}">
        @include('web.default.auth.register_includes.mobile_field')
    </div>

@else
    @if($registerMethod == 'mobile')
        <input type="hidden" name="type" value="mobile">
        <div class="">
            @include('web.default.auth.register_includes.mobile_field')
        </div>

    @else
        <input type="hidden" name="type" value="email">

        <div class=" form-group">
            <label class="input-label" for="email">{{ trans('public.email') }}:</label>
            <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                   value="{{ old('email') }}" aria-describedby="emailHelp"
                    pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$"
                    title="{{ 'Please enter a valid email address (e.g. user@gmail.com)' }}"
                    oninput="validateEmail(this)">
            @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    @endif
@endif
<script>
    function validateEmail(input) {
        // Full RFC-compliant-style regex
        const emailRegex = /^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/;

        const value = input.value.trim();

        // Additional strict checks beyond regex
        const isValid =
            emailRegex.test(value) &&
            !value.includes('..') &&           // no consecutive dots
            !value.startsWith('.') &&           // no leading dot in local part
            value.indexOf('@') > 0 &&           // @ not at start
            value.split('@').length === 2 &&    // only one @ symbol
            value.split('@')[1].includes('.') && // domain has a dot
            !value.split('@')[1].startsWith('.') && // domain doesn't start with dot
            !value.split('@')[1].endsWith('.');  // domain doesn't end with dot

        if (!isValid && value.length > 0) {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
        } else if (isValid) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        } else {
            input.classList.remove('is-invalid');
            input.classList.remove('is-valid');
        }
    }

    // Also validate on form submit
    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('form');
        forms.forEach(function (form) {
            form.addEventListener('submit', function (e) {
                const emailInputs = form.querySelectorAll('input[type="email"]');
                emailInputs.forEach(function (input) {
                    if (!input.closest('.d-none')) { // only validate visible fields
                        validateEmail(input);
                        if (input.classList.contains('is-invalid')) {
                            e.preventDefault();
                        }
                    }
                });
            });
        });
    });
</script>