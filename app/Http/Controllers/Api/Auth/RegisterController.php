<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Auth\VerificationController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\traits\UserFormFieldsTrait;
use App\Mixins\RegistrationBonus\RegistrationBonusAccounting;
use App\Models\Affiliate;
use App\Models\Reward;
use App\Models\RewardAccounting;
use App\Models\Role;
use App\Models\UserFormField;
use App\Models\UserMeta;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Api\UserFirebaseSessions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    use UserFormFieldsTrait;
    
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


    public function stepRegister(Request $request, $step)
    {
        if ($step == 1) {
            return $this->stepOne($request);
        } elseif ($step == 2) {
            return $this->stepTwo($request);
        } elseif ($step == 3) {
            return $this->stepThree($request);
        }
    
        abort(404);
    }
    
    private function stepOne(Request $request)
    {
        $registerMethod = getGeneralSettings('register_method') ?? 'mobile';
        $data = $request->all();
        $username = $this->username();
    
        if ($registerMethod !== $username && $username) {
            return apiResponse2(0, 'invalid_register_method', trans('api.auth.invalid_register_method'));
        }
    
        $rules = [
            // 'country_code' => ($username == 'mobile') ? 'required' : 'nullable',
            $username => ($username == 'mobile') ? 'required|numeric' : 'required|string|email|max:255',
            'full_name' => 'required|string|min:3',
            'first_name' => 'required|string|min:3',
            'last_name' => 'required|string|min:3',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|same:password',
        ];
    
        validateParam($data, $rules);

        if ($username === 'email' && !empty($data['email'])) {
            if ($this->isBlockedEmailDomain($data['email'])) {
                return apiResponse2(0, 'invalid_email_domain', trans('This email provider is not allowed. Please use a valid email address.'));
            }
        }
    
        if ($username == 'mobile') {
            $data[$username] = ltrim($data['country_code'], '+') . ltrim($data[$username], '0');
        }
    
        $userCase = User::where($username, $data[$username])->first();
        if ($userCase) {
            if ($userCase->full_name) {
                return apiResponse2(0, 'already_registered', trans('api.auth.already_registered'));
            } else {
                $userCase->update(['password' => Hash::make($data['password'])]);
                return apiResponse2(1, 'go_step_3', trans('api.auth.go_step_3'), [
                    'user_id' => $userCase->id
                ]);
            }
        }
    
        $referralSettings = getReferralSettings();
        $usersAffiliateStatus = (!empty($referralSettings) && !empty($referralSettings['users_affiliate_status']));

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
            $username => $data[$username],
            'full_name' => $data['full_name'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'status' => User::$pending,
            'password' => Hash::make($data['password']),
            // 'country_id' => $data['country_id'],
            'affiliate' => $usersAffiliateStatus,
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
    
        $form = $this->getFormFieldsByType($request->get('account_type'));
        $errors = [];
    
        if (!empty($form)) {
            $fieldErrors = $this->checkFormRequiredFields($request, $form);
            if (!empty($fieldErrors)) {
                foreach ($fieldErrors as $id => $error) {
                    $errors[$id] = $error;
                }
            }
        }
    
        if (count($errors)) {
            return apiResponse2(0, 'login', trans('api.auth.login'), $errors);
        }
    
        $this->storeFormFields($data, $user);

        $otp = rand(100000, 999999);

        $user->update([
            'verify_otp'        => $otp,
            'otp_expires_at'    => time() + (10 * 60),   // 10 minutes
            'email_verified_at' => null,
        ]);

        $generalSettings = getGeneralSettings();

        Mail::send('web.default.auth.otp_verify', [
            'otp'             => $otp,
            'generalSettings' => $generalSettings,
            'email'           => $user->email,
        ], function ($message) use ($user, $generalSettings) {
            $message->from(
                !empty($generalSettings['site_email'])
                    ? $generalSettings['site_email']
                    : env('MAIL_FROM_ADDRESS'),
                env('MAIL_FROM_NAME')
            );
            $message->to($user->email);
            $message->subject('Verify Your Email - OTP Code');
        });

        return apiResponse2(1, 'otp_sent', 'OTP has been sent to your email. Please verify to complete registration.', [
            'user_id' => $user->id,
        ]);

        // $enableRegistrationBonus = false;
        // $registrationBonusAmount = null;
        // $registrationBonusSettings = getRegistrationBonusSettings();
        // if (!empty($registrationBonusSettings['status']) && !empty($registrationBonusSettings['registration_bonus_amount'])) {
        //     $enableRegistrationBonus = true;
        //     $registrationBonusAmount = $registrationBonusSettings['registration_bonus_amount'];
        // }
    
        // $user->update([
        //     'enable_registration_bonus' => $enableRegistrationBonus,
        //     'registration_bonus_amount' => $registrationBonusAmount,
        // ]);
    
        // $registerReward = RewardAccounting::calculateScore(Reward::REGISTER);
        // RewardAccounting::makeRewardAccounting($user->id, $registerReward, Reward::REGISTER, $user->id, true);
    
        // (new RegistrationBonusAccounting())->storeRegistrationBonusInstantly($user);
    
        // if (!empty($data['referral_code'])) {
        //     Affiliate::storeReferral($user, $data['referral_code']);
        // }
    
        // event(new Registered($user));
        // $data['token'] = auth('api')->tokenById($user->id);
        // $data['user_id'] = $user->id;

        // return apiResponse2(1, 'login', trans('api.auth.login'), $data);
    
        // return apiResponse2(1, 'Registration Successfully', trans('api.auth.go_step_3'), [
        //     'user_id' => $user->id
        // ]);
    }

    public function stepTwo(Request $request)
    {
        $data = $request->all();

        validateParam($data, [
            'user_id' => 'required|exists:users,id',
            'otp'     => 'required|numeric|digits:6',
        ]);

        $user = User::find($data['user_id']);

        if (!$user) {
            return apiResponse2(0, 'user_not_found', 'User not found.');
        }

        // ── OTP expired → delete pending user ────────────────────────────────
        if (time() > $user->otp_expires_at) {
            // $user->delete();
            return apiResponse2(0, 'otp_expired',
                'Your OTP has expired. Please register again.');
        }

        // ── Wrong OTP ─────────────────────────────────────────────────────────
        if ((string) $user->verify_otp !== (string) $data['otp']) {
            return apiResponse2(0, 'invalid_otp',
                'The OTP you entered is incorrect. Please try again.');
        }

        // ── OTP correct — activate account ───────────────────────────────────
        $user->update([
            'verify_otp'        => null,
            'otp_expires_at'    => null,
            'email_verified_at' => time(),
            'status'            => User::$active,
        ]);

        // ── Registration bonus ────────────────────────────────────────────────
        $enableRegistrationBonus = false;
        $registrationBonusAmount = null;
        $registrationBonusSettings = getRegistrationBonusSettings();
        if (!empty($registrationBonusSettings['status']) && !empty($registrationBonusSettings['registration_bonus_amount'])) {
            $enableRegistrationBonus = true;
            $registrationBonusAmount = $registrationBonusSettings['registration_bonus_amount'];
        }

        $user->update([
            'enable_registration_bonus' => $enableRegistrationBonus,
            'registration_bonus_amount' => $registrationBonusAmount,
        ]);

        // ── Rewards ───────────────────────────────────────────────────────────
        $registerReward = RewardAccounting::calculateScore(Reward::REGISTER);
        RewardAccounting::makeRewardAccounting($user->id, $registerReward, Reward::REGISTER, $user->id, true);
        (new RegistrationBonusAccounting())->storeRegistrationBonusInstantly($user);

        // ── Referral ──────────────────────────────────────────────────────────
        if (!empty($data['referral_code'])) {
            Affiliate::storeReferral($user, $data['referral_code']);
        }

        // ── Fire event & issue token ──────────────────────────────────────────
        event(new Registered($user));

        $token = auth('api')->tokenById($user->id);

        return apiResponse2(1, 'login', trans('api.auth.login'), [
            'user_id' => $user->id,
            'token'   => $token,
        ]);
    }

    public function stepThree(Request $request)
    {
        $data = $request->all();

        validateParam($data, [
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::find($data['user_id']);

        if (!$user) {
            return apiResponse2(0, 'user_not_found', 'User not found.');
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
            'email'           => $user->email,
        ], function ($message) use ($user, $generalSettings) {
            $message->from(
                !empty($generalSettings['site_email'])
                    ? $generalSettings['site_email']
                    : env('MAIL_FROM_ADDRESS'),
                env('MAIL_FROM_NAME')
            );
            $message->to($user->email);
            $message->subject('Verify Your Email - OTP Code');
        });

        return apiResponse2(1, 'otp_resent', 'A new OTP has been sent to your email address.');
    }
    
    private function stepfour(Request $request)
    {
        $data = $request->all();
        validateParam($data, [
            'user_id' => 'required|exists:users,id',
            'full_name' => 'required|string|min:3',
            'first_name' => 'required|string|min:3',
            'last_name' => 'required|string|min:3',
            'referral_code' => 'nullable|exists:affiliates_codes,code'
        ]);
    
        $user = User::find($data['user_id']);
        $user->update([
            'full_name' => $data['full_name'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
        ]);
    
        $enableRegistrationBonus = false;
        $registrationBonusAmount = null;
        $registrationBonusSettings = getRegistrationBonusSettings();
        if (!empty($registrationBonusSettings['status']) && !empty($registrationBonusSettings['registration_bonus_amount'])) {
            $enableRegistrationBonus = true;
            $registrationBonusAmount = $registrationBonusSettings['registration_bonus_amount'];
        }
    
        $user->update([
            'enable_registration_bonus' => $enableRegistrationBonus,
            'registration_bonus_amount' => $registrationBonusAmount,
        ]);
    
        $registerReward = RewardAccounting::calculateScore(Reward::REGISTER);
        RewardAccounting::makeRewardAccounting($user->id, $registerReward, Reward::REGISTER, $user->id, true);
    
        (new RegistrationBonusAccounting())->storeRegistrationBonusInstantly($user);
    
        if (!empty($data['referral_code'])) {
            Affiliate::storeReferral($user, $data['referral_code']);
        }
    
        event(new Registered($user));
        $data['token'] = auth('api')->tokenById($user->id);
        $data['user_id'] = $user->id;
    
        return apiResponse2(1, 'login', trans('api.auth.login'), $data);
    }

    public function username()
    {
        $email_regex = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";

        $data = request()->all();

        if (empty($this->username)) {
            if (in_array('mobile', array_keys($data))) {
                $this->username = 'mobile';
            } else if (in_array('email', array_keys($data))) {
                $this->username = 'email';
            }
        }

        return $this->username ?? '';
    }


}
