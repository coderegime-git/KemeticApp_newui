<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\traits\UserFormFieldsTrait;
use App\Mixins\RegistrationBonus\RegistrationBonusAccounting;
use App\Models\Affiliate;
use App\Models\Reward;
use App\Models\RewardAccounting;
use App\Models\Role;
use App\Models\UserMeta;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;
use App\Models\Subscribe;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Web\CartManagerController;
use App\Models\Cart;

class RegisterController extends Controller
{

    use UserFormFieldsTrait;

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    protected array $blockedEmailDomains = [
        // Disposable / Temporary
        'mailinator.com', '10minutemail.com', 'guerrillamail.com', 'temp-mail.org',
        'throwawaymail.com', 'throwam.com', 'yopmail.com', 'getnada.com',
        'fakemail.net', 'moakt.com', 'sharklasers.com', 'trashmail.com',
        'mintemail.com', 'dispostable.com', 'dollicons.com', 'xkxkud.com',
        'tempmail.com', 'fakeinbox.com', 'maildrop.cc', 'spamgourmet.com',
        'tempail.com', 'emailondeck.com',
        // Spam / Abuse-Friendly
        'mail.ru', 'inbox.ru', 'bk.ru', 'list.ru', 'rambler.ru',
        'qq.com', '163.com', '126.com',
        // Anonymous / High-Risk
        'cock.li', 'ctemplar.com', 'elude.in',
    ];

    private function isBlockedEmailDomain(string $email): bool
    {
        $domain = strtolower(substr(strrchr($email, '@'), 1));
        return in_array($domain, $this->blockedEmailDomains);
    }

    protected $redirectTo = '/panel';
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm(Request $request)
    {
        //new add 05-02
        if (isset($request->plan_id) && !empty($request->plan_id)) {
            $subscribe = Subscribe::where('id', $request->plan_id)->first();
            if (!empty($subscribe)) {
                session()->put('redirect_to_checkout', [
                    'amount' => $subscribe->price,
                    'id' => $subscribe->id,
                ]);
            } else {
                session()->forget('redirect_to_checkout');
            }
        }
        //end

        $seoSettings = getSeoMetas('register');
        $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('site.register_page_title');
        $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('site.register_page_title');
        $pageRobot = getPageRobot('register');

        $referralSettings = getReferralSettings();

        $referralCode = Cookie::get('referral_code');

        $accountType = !empty($request->old('account_type')) ? $request->old('account_type') : "user";
        $formFields = $this->getFormFieldsByUserType($request, $accountType, true);

        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'referralCode' => $referralCode,
            'referralSettings' => $referralSettings,
            'formFields' => $formFields
        ];

        return view(getTemplate() . '.auth.register', $data);
    }

    protected function validator(array $data)
    {
        $registerMethod = getGeneralSettings('register_method') ?? 'mobile';

        if (!empty($data['mobile']) and !empty($data['country_code'])) {
            $data['mobile'] = ltrim($data['country_code'], '+') . ltrim($data['mobile'], '0');
        }

        $rules = [
            'country_code' => ($registerMethod == 'mobile') ? 'required' : 'nullable',
            'mobile' => (($registerMethod == 'mobile') ? 'required' : 'nullable') . '|numeric|unique:users',
            'email' => (($registerMethod == 'email') ? 'required' : 'nullable') . '|email|max:255|unique:users',
            'term' => 'required',
            'full_name' => 'required|string|min:3',
            'first_name'    => "required|string|min:2",
            'last_name'     => "required|string|min:2",
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|same:password',
            'referral_code' => 'nullable|exists:affiliates_codes,code'
        ];

        if (!empty(getGeneralSecuritySettings('captcha_for_register'))) {
            $rules['captcha'] = 'required|captcha';
        }

        return Validator::make($data, $rules, [], [
            'mobile' => trans('auth.mobile'),
            'email' => trans('auth.email'),
            'term' => trans('update.terms'),
            'full_name' => trans('auth.full_name'),
            'password' => trans('auth.password'),
            'password_confirmation' => trans('auth.password_repeat'),
            'referral_code' => trans('financial.referral_code'),
        ]);
    }

    protected function create(array $data)
    {
        if (!empty($data['mobile']) and !empty($data['country_code'])) {
            $data['mobile'] = ltrim($data['country_code'], '+') . ltrim($data['mobile'], '0');
        }

        $referralSettings = getReferralSettings();
        $usersAffiliateStatus = (!empty($referralSettings) and !empty($referralSettings['users_affiliate_status']));

        if (empty($data['timezone'])) {
            $data['timezone'] = getGeneralSettings('default_time_zone') ?? null;
        }

        $disableViewContentAfterUserRegister = getFeaturesSettings('disable_view_content_after_user_register');
        $accessContent = !((!empty($disableViewContentAfterUserRegister) and $disableViewContentAfterUserRegister));

        $roleName = Role::$user;
        $roleId = Role::getUserRoleId();

        if (!empty($data['account_type'])) {
            if ($data['account_type'] == Role::$teacher) {
                $roleName = Role::$teacher;
                $roleId = Role::getTeacherRoleId();
            } else if ($data['account_type'] == Role::$organization) {
                $roleName = Role::$organization;
                $roleId = Role::getOrganizationRoleId();
            }
        }
        
        $user = User::create([
            'role_name' => $roleName,
            'role_id' => $roleId,
            'mobile' => $data['mobile'] ?? null,
            'email' => $data['email'] ?? null,
            'full_name' => $data['full_name'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'status' => User::$pending,
            'access_content' => $accessContent,
            'password' => Hash::make($data['password']),
            'affiliate' => $usersAffiliateStatus,
            'country_id' => $data['country_id'],
            'timezone' => $data['timezone'] ?? null,
            'created_at' => time()
        ]);

        if (!empty($data['certificate_additional'])) {
            UserMeta::updateOrCreate([
                'user_id' => $user->id,
                'name' => 'certificate_additional'
            ], [
                'value' => $data['certificate_additional']
            ]);
        }

        $this->storeFormFields($data, $user);

        return $user;
    }


    public function register(Request $request)
    {
        $registerMethod = getGeneralSettings('register_method') ?? 'mobile';
        if ($registerMethod === 'email' && !empty($request->email)) {
            if ($this->isBlockedEmailDomain($request->email)) {
                $toastData = [
                    'title' => trans('Email Not Allowed'),
                    'msg'   => trans('This email provider is not allowed. Please use a valid email address.'),
                    'status' => 'error'
                ];
                return back()->with(['toast' => $toastData])->withInput();
            }
        }

        if ($request->wantsJson()) {
            $validate = $this->validator($request->all());

            if ($validate->fails()) {
                $errors = $validate->errors();

                $form = $this->getFormFieldsByType($request->get('account_type'));

                if (!empty($form)) {
                    $fieldErrors = $this->checkFormRequiredFields($request, $form);

                    if (!empty($fieldErrors) and count($fieldErrors)) {
                        foreach ($fieldErrors as $id => $error) {
                            $errors->add($id, $error);
                        }
                    }
                }

                throw new ValidationException($validate);
            } else {
                $form = $this->getFormFieldsByType($request->get('account_type'));
                $errors = [];

                if (!empty($form)) {
                    $fieldErrors = $this->checkFormRequiredFields($request, $form);

                    if (!empty($fieldErrors) and count($fieldErrors)) {
                        foreach ($fieldErrors as $id => $error) {
                            $errors[$id] = $error;
                        }
                    }
                }

                if (count($errors)) {
                    return back()->withErrors($errors)->withInput($request->all());
                }
            }


            $data = $request->all();

            if (!empty($data['mobile']) and !empty($data['country_code'])) {
                $data['mobile'] = $data['country_code'] . ltrim($data['mobile'], '0');
            }


            if (!empty($data['mobile'])) {
                $checkIsValid = checkMobileNumber($data['mobile']);

                if (!$checkIsValid) {
                    $errors['mobile'] = [trans('update.mobile_number_is_not_valid')];
                    return back()->withErrors($errors)->withInput($request->all());
                }
            }

            $user = $this->create($request->all());
        } else {
            $data = $request->validate([
                'account_type' => "required",
                'timezone' => "required",
                "country_id" => "required",
                'full_name' => "required|string|max:255",
                'first_name'    => "required|string|max:255",
                'last_name'     => "required|string|max:255",
                'email' => "required|email|unique:users,email",
                'mobile' => "nullable|unique:users,mobile",
                'password' => "required|string|min:6|confirmed",
                'term' => 'accepted',
                'referral_code' => 'nullable|exists:affiliates_codes,code'
            ]);

            $data['access_content'] = 1;
            $data['affiliate'] = 1;
            $data['status'] = 'Active';
            $data['role_name'] = $data['account_type'];
            $data['country_id'] = $data['country_id'];
            $data['role_id'] = Role::whereName($data['account_type'])->first()->id;
            $data['created_at'] = time();
            $data['user_just_registered'] = 1;

            if (!empty($data['mobile']) and !empty($data['country_code'])) {
                $data['mobile'] = ltrim($data['country_code'], '+') . ltrim($data['mobile'], '0');
            }

            // Remove non-database fields
            unset($data['password_confirmation'], $data['term'], $data['account_type']);

            // Hash password
            $data['password'] = Hash::make($data['password']);

            // dd($data);

            // Now safe to insert
            $user = User::create($data);
        }

        $otp = rand(100000, 999999);

        $user->update([
            'verify_otp'        => $otp,
            'otp_expires_at'    => time() + (10 * 60), // 10 minutes as unix timestamp
            'email_verified_at' => null,
        ]);

        $generalSettings = getGeneralSettings();

        Mail::send('web.default.auth.otp_verify', [
            'otp'             => $otp,
            'generalSettings' => $generalSettings,
            'email'           => $user->email,
        ], function ($message) use ($user, $generalSettings) {
            $message->from(
                !empty($generalSettings['site_email']) ? $generalSettings['site_email'] : env('MAIL_FROM_ADDRESS'),
                env('MAIL_FROM_NAME')
            );
            $message->to($user->email);
            $message->subject('Verify Your Email - OTP Code');
        });

        session()->put('register_otp_email', $user->email);
        session()->put('register_otp_user_id', $user->id);

        $toastData = [
            'title'  => 'Verify Your Email',
            'msg'    => 'An OTP has been sent to your email address. Please verify to complete registration.',
            'status' => 'success'
        ];

        return redirect('/register/verify-otp')->with(['toast' => $toastData]);

        // $registerMethod = getGeneralSettings('register_method') ?? 'mobile';

        // $value = $request->get($registerMethod);
        // if ($registerMethod == 'mobile') {
        //     $value = $request->get('country_code') . ltrim($request->get('mobile'), '0');
        // }

        // $referralCode = $request->get('referral_code', null);
        // if (!empty($referralCode)) {
        //     session()->put('referralCode', $referralCode);
        // }

        // $verificationController = new VerificationController();
        // $checkConfirmed = $verificationController->checkConfirmed($user, $registerMethod, $value);

        // $referralCode = $request->get('referral_code', null);

        // if ($checkConfirmed['status'] == 'send') {
        //     if (!empty($referralCode)) {
        //         session()->put('referralCode', $referralCode);
        //     }
        //     return redirect('/verification');

        // } elseif ($checkConfirmed['status'] == 'verified') {
        //     $this->guard()->login($user);

        //     $enableRegistrationBonus = false;
        //     $registrationBonusAmount = null;
        //     $registrationBonusSettings = getRegistrationBonusSettings();
        //     if (!empty($registrationBonusSettings['status']) and !empty($registrationBonusSettings['registration_bonus_amount'])) {
        //         $enableRegistrationBonus = true;
        //         $registrationBonusAmount = $registrationBonusSettings['registration_bonus_amount'];
        //     }


        //     $user->update([
        //         'status' => User::$active,
        //         'enable_registration_bonus' => $enableRegistrationBonus,
        //         'registration_bonus_amount' => $registrationBonusAmount,
        //     ]);

        //     // if (!empty($referralCode)) {
        //     //     Affiliate::storeReferral($user, $referralCode);
        //     // }

        //     // $registrationBonusAccounting = new RegistrationBonusAccounting();
        //     // $registrationBonusAccounting->storeRegistrationBonusInstantly($user);

        //     // Flag to differ the codes to dashboard controller
        //     session()->put('user_just_registered', $user->id);

        //     if (session()->has('membership1_after_login')) {
        //         $redirectUrl = session()->pull('membership1_after_login');
        //         return redirect($redirectUrl);
        //     }

        //     if (session()->has('membership_after_login')) {
        //         $redirectUrl = session()->pull('membership_after_login');
        //         return redirect($redirectUrl);
        //     }
            
        //     // dd($data);
        //     if ($response = $this->registered($request, $user)) {
        //         // return $response;
        //         if ($request->wantsJson())
        //             return $response;
        //         else
        //             if (session()->has('membership1_after_login')) {
        //                 $redirectUrl = session()->pull('membership1_after_login');
        //                 return redirect($redirectUrl);
        //             }

        //             if (session()->has('membership_after_login')) {
        //                 $redirectUrl = session()->pull('membership_after_login');
        //                 return redirect($redirectUrl);
        //             }

        //             redirect()->route('homepage');
        //     }

        //     if (session()->has('membership1_after_login')) {
        //         $redirectUrl = session()->pull('membership1_after_login');
        //         return redirect($redirectUrl);
        //     }

        //     if (session()->has('membership_after_login')) {
        //         $redirectUrl = session()->pull('membership_after_login');
        //         return redirect($redirectUrl);
        //     }

        //     return $request->wantsJson()
        //         ? new JsonResponse([], 201)
        //         : redirect()->route('homepage');
        // }
    }

    public function showRegisterOtpForm()
    {
        if (!session()->has('register_otp_email')) {
            return redirect('/register');
        }
        return view(getTemplate() . '.auth.register_verify_otp');
    }

    public function verifyRegisterOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        $email  = session()->get('register_otp_email');
        $userId = session()->get('register_otp_user_id');

        if (!$email || !$userId) {
            return redirect('/login');
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect('/login');
        }

        // OTP expired
        if (time() > $user->otp_expires_at) {
            $user->delete();
            session()->forget(['register_otp_email', 'register_otp_user_id']);

            $toastData = [
                'title'  => 'OTP Expired',
                'msg'    => 'Your OTP has expired. Please register again.',
                'status' => 'error'
            ];
            //return redirect('/register')->with(['toast' => $toastData]);
        }

        if ((string) $user->verify_otp !== (string) $request->otp) {
            $toastData = [
                'title'  => 'Invalid OTP',
                'msg'    => 'The OTP you entered is incorrect. Please try again.',
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }

        // ── OTP correct — clear OTP columns ───────────────────────────
        $user->update([
            'verify_otp'        => null,
            'otp_expires_at'    => null,
            'email_verified_at' => time(), // unix timestamp
            'status'            => User::$active,
        ]);

        session()->forget(['register_otp_email', 'register_otp_user_id']);

        // ── Registration bonus ─────────────────────────────────────────
        $enableRegistrationBonus   = false;
        $registrationBonusAmount   = null;
        $registrationBonusSettings = getRegistrationBonusSettings();
        if (!empty($registrationBonusSettings['status']) && !empty($registrationBonusSettings['registration_bonus_amount'])) {
            $enableRegistrationBonus = true;
            $registrationBonusAmount = $registrationBonusSettings['registration_bonus_amount'];
        }

        $user->update([
            'enable_registration_bonus' => $enableRegistrationBonus,
            'registration_bonus_amount' => $registrationBonusAmount,
        ]);

        // ── Rewards & bonus accounting ─────────────────────────────────
        $registerReward = RewardAccounting::calculateScore(Reward::REGISTER);
        RewardAccounting::makeRewardAccounting($user->id, $registerReward, Reward::REGISTER, $user->id, true);
        (new RegistrationBonusAccounting())->storeRegistrationBonusInstantly($user);

        // ── Referral ───────────────────────────────────────────────────
        $referralCode = session()->pull('referralCode', null);
        if (!empty($referralCode)) {
            Affiliate::storeReferral($user, $referralCode);
        }

        // ── Fire registered event & login ─────────────────────────────
        event(new Registered($user));
        $this->guard()->login($user);

        session()->put('user_just_registered', $user->id);

        // ── Membership redirects layer 1 ──────────────────────────────
        if (session()->has('membership1_after_login')) {
            $redirectUrl = session()->pull('membership1_after_login');
            return redirect($redirectUrl);
        }

        if (session()->has('membership_after_login')) {
            $redirectUrl = session()->pull('membership_after_login');
            return redirect($redirectUrl);
        }

        // ── registered() hook ─────────────────────────────────────────
        if ($response = $this->registered($request, $user)) {
            if ($request->wantsJson()) {
                return $response;
            }

            if (session()->has('membership1_after_login')) {
                $redirectUrl = session()->pull('membership1_after_login');
                return redirect($redirectUrl);
            }

            if (session()->has('membership_after_login')) {
                $redirectUrl = session()->pull('membership_after_login');
                return redirect($redirectUrl);
            }

            return $response;
        }

        // ── Final fallback ─────────────────────────────────────────────
        if (session()->has('membership1_after_login')) {
            $redirectUrl = session()->pull('membership1_after_login');
            return redirect($redirectUrl);
        }

        if (session()->has('membership_after_login')) {
            $redirectUrl = session()->pull('membership_after_login');
            return redirect($redirectUrl);
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect()->route('homepage');
    }

    public function resendRegisterOtp()
    {
        $email  = session()->get('register_otp_email');
        $userId = session()->get('register_otp_user_id');

        if (!$email || !$userId) {
            return redirect('/login');
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect('/login');
        }

        $otp = rand(100000, 999999);

        $user->update([
            'verify_otp'     => $otp,
            'otp_expires_at' => time() + (10 * 60),
        ]);

        $generalSettings = getGeneralSettings();

        Mail::send('web.default.auth.otp_verify', [
            'otp'             => $otp,
            'generalSettings' => $generalSettings,
            'email'           => $email,
        ], function ($message) use ($email, $generalSettings) {
            $message->from(
                !empty($generalSettings['site_email']) ? $generalSettings['site_email'] : env('MAIL_FROM_ADDRESS'),
                env('MAIL_FROM_NAME')
            );
            $message->to($email);
            $message->subject('Verify Your Email - OTP Code');
        });

        $toastData = [
            'title'  => 'OTP Resent',
            'msg'    => 'A new OTP has been sent to your email address.',
            'status' => 'success'
        ];

        return back()->with(['toast' => $toastData]);
    }

    protected function registered(Request $request, $user)
    {

        $cartManagerController = new CartManagerController();
        $cartManagerController->storeCookieCartsToDB();

        if (session()->has('membership1_after_login')) {
            $redirectUrl = session()->pull('membership1_after_login');
            return redirect($redirectUrl);
        }

        if (session()->has('membership_after_login')) {
            $redirectUrl = session()->pull('membership_after_login');
            return redirect($redirectUrl);
        }

        //new add 05-02
        if (session()->has('redirect_to_checkout')) {
            $checkoutData = session('redirect_to_checkout');
            session()->forget('redirect_to_checkout');
            return view('checkout-redirect')->with('checkoutData', $checkoutData);
        }
        //end

        if (auth()->check()) {
            $user = auth()->user();
            $cartItems = Cart::where('creator_id', $user->id)->count();

            if ($cartItems > 0) {
                return redirect('/cart');
            }
        }
        // dd($user);

        return redirect('/panel');

    }



}
