<?php

namespace App\Http\Controllers\Api\Panel;

use App\Bitwise\UserLevelOfTraining;
use App\Http\Controllers\Api\Controller;
use App\Http\Controllers\Api\Objects\UserObj;
use App\Models\Api\UserFirebaseSessions;
use App\Models\Category;
use App\Models\Newsletter;
use Carbon\Carbon;
use App\Models\Reward;
use App\Models\RewardAccounting;
use App\Models\UserMeta;
use App\Models\Follow;

use App\Models\UserZoomApi;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Api\Cart;
use App\Http\Controllers\Api\UploadFileManager;
use App\Models\ProductOrder;


class UsersController extends Controller
{

    public function setting()
    {
        $user = apiAuth();
        return apiResponse2(
            1,
            'retrieved',
            trans('api.public.retrieved'),
            [
                'user' => $user->details
            ]
        );


    }

    public function updateImages(Request $request)
    {
        $user = apiAuth();
        if ($request->file('profile_image')) {

            $profileImage = $this->createImage($user, $request->file('profile_image'));
            $user->update([
                'avatar' => $profileImage
            ]);
        }

        if ($request->file('identity_scan')) {

            $storage = new UploadFileManager($request->file('identity_scan'));

            $user->update([
                'identity_scan' => $storage->storage_path,
            ]);

        }

        if ($request->file('certificate')) {

            $storage = new UploadFileManager($request->file('certificate'));

            $user->update([
                'certificate' => $storage->storage_path,
            ]);


        }

        return apiResponse2(1, 'updated', trans('api.public.updated'));


    }


    public function update(Request $request)
    {
        $available_inputs = [
            'full_name',
            'language',
            'email',
            'mobile',
            'newsletter',
            'public_message',
            'timezone',
            'password',
            'about',
            'bio',
            'account_type',
            'iban',
            'account_id',
            'level_of_training',
            'meeting_type',
            'country_id',
            'province_id',
            'city_id',
            'district_id',
            'location'
        ];
        $meta = ['address', 'gender', 'age'];

        $user = apiAuth();

        validateParam($request->all(), [
            'full_name' => 'string',
            'language' => 'string',
            'email' => 'email|unique:users,email,' . $user->id,
            'mobile' => 'numeric|unique:users,mobile,' . $user->id,
            'timezone' => ['string', Rule::in(getListOfTimezones())],
            'public_message' => 'boolean',
            'newsletter' => 'boolean',
            // 'password' => 'required|string|min:6',

            'account_type' => Rule::in(getOfflineBanksTitle()),
            'iban' => 'required_with:account_type',
            'account_id' => 'required_with:account_type',
            // 'identity_scan' => 'required_with:account_type',

            'bio' => 'nullable|string|min:3|max:48',
            'level_of_training' => 'array|in:beginner,middle,expert',
            'meeting_type' => 'in:in_person,all,online',

            'gender' => 'nullable|in:man,woman',
            'location' => 'array|size:2',
            'location.latitude' => 'required_with:location',
            'location.longitude' => 'required_with:location',
            'address' => 'string',
            'country_id' => 'exists:regions,id',
            'province_id' => 'exists:regions,id',
            'city_id' => 'exists:regions,id',
            'district_id' => 'exists:regions,id',
        ]);

        $user = User::find($user->id);

        foreach ($available_inputs as $input) {
            if ($request->has($input)) {
                $value = $request->input($input);
                if ($input == 'level_of_training') {
                    $value = (new UserLevelOfTraining())->getValue($value);
                }
                if ($input == 'location') {
                    $value = DB::raw("POINT(" . $value['latitude'] . "," . $value['longitude'] . ")");
                }
                if ($input == 'password') {
                    $value = User::generatePassword($value);
                }

                $user->update([
                    $input => $value
                ]);
            }
        }


        if (!$user->isUser()) {
            if ($request->has('zoom_jwt_token') and !empty($request->input('zoom_jwt_token'))) {

                UserZoomApi::updateOrCreate(
                    [
                        'user_id' => $user->id,
                    ],
                    [
                        'jwt_token' => $request->input('zoom_jwt_token'),
                        'created_at' => time()
                    ]
                );

            } else {
                UserZoomApi::where('user_id', $user->id)->delete();
            }
        }

        if ($request->has('newsletter')) {
            $this->handleNewsletter($user->email, $user->id, $user->newsletter);
        }

        $this->updateMeta($meta);


        return apiResponse2(1, 'updated', trans('api.public.updated'));
    }

    private function handleNewsletter($email, $user_id, $joinNewsletter)
    {
        $check = Newsletter::where('email', $email)->first();
        if ($joinNewsletter) {
            if (empty($check)) {
                Newsletter::create([
                    'user_id' => $user_id,
                    'email' => $email,
                    'created_at' => time()
                ]);
            } else {
                $check->update([
                    'user_id' => $user_id,
                ]);
            }

            $newsletterReward = RewardAccounting::calculateScore(Reward::NEWSLETTERS);
            RewardAccounting::makeRewardAccounting($user_id, $newsletterReward, Reward::NEWSLETTERS, $user_id, true);
        } elseif (!empty($check)) {
            $reward = RewardAccounting::where('user_id', $user_id)
                ->where('item_id', $user_id)
                ->where('type', Reward::NEWSLETTERS)
                ->where('status', RewardAccounting::ADDICTION)
                ->first();

            if (!empty($reward)) {
                $reward->delete();
            }

            $check->delete();
        }
    }

    public function updatePassword(Request $request)
    {
        validateParam($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:6',
        ]);

        $user = apiAuth();
        if (Hash::check($request->input('current_password'), $user->password)) {
            $user->update([
                'password' => User::generatePassword($request->input('new_password'))
            ]);
            $token = auth('api')->refresh();

            return apiResponse2(1, 'updated', trans('api.public.updated'), [
                'token' => $token
            ]);

        }
        return apiResponse2(0, 'incorrect', trans('api.public.profile_setting.incorrect'));


    }
    public function updateCurrency(Request $request)
    {
        validateParam($request->all(), [
            'currency' => 'required',
        ]);

        $user = apiAuth();
        $currency = $request->input("currency");

        $user->update([
            'currency' => $currency
        ]);
        return apiResponse2(1, 'updated', trans('api.public.updated'), [
            'currency' => $currency
        ]);
    }

    private function updateMeta($updateUserMeta)
    {
        $user = apiAuth();
        foreach ($updateUserMeta as $name) {
            $value = request()->input($name);
            $checkMeta = UserMeta::where('user_id', $user->id)
                ->where('name', $name)
                ->first();

            if (!empty($checkMeta)) {
                if (!empty($value)) {
                    $checkMeta->update([
                        'value' => $value
                    ]);
                } else {
                    $checkMeta->delete();
                }
            } else if (!empty($value)) {
                UserMeta::create([
                    'user_id' => $user->id,
                    'name' => $name,
                    'value' => $value
                ]);
            }
        }
    }

    public function followToggle(Request $request, $id)
    {
        // dd('ff') ;
        $authUser = apiAuth();
        validateParam($request->all(), [
            'status' => 'required|boolean'
        ]);

        $status = $request->input('status');

        $user = User::where('id', $id)->first();
        if (!$user) {
            abort(404);
        }
        $followStatus = false;
        $follow = Follow::where('follower', $authUser->id)
            ->where('user_id', $user->id)
            ->first();

        if ($status) {

            if (empty($follow)) {
                Follow::create([
                    'follower' => $authUser->id,
                    'user_id' => $user->id,
                    'status' => Follow::$accepted,
                ]);

                $followStatus = true;

            }
            return apiResponse2(1, 'followed', trans('api.user.followed'));


        }

        if (!empty($follow)) {

            $follow->delete();
            return apiResponse2(1, 'unfollowed', trans('api.user.unfollowed'));

        }

        return apiResponse2(0, 'not_followed', trans('api.user.not_followed'));


    }

    public function createImage($user, $img)
    {
        $folderPath = "/" . $user->id . '/avatar';

        //     $image_parts = explode(";base64,", $img);
        //   $image_type_aux = explode("image/", $image_parts[0]);
        //   $image_type = $image_type_aux[1];
        //  $image_base64 = base64_decode($image_parts[1]);
        // $file = uniqid() . '.' . $image_type;

        $file = uniqid() . '.' . $img->getClientOriginalExtension();
        $storage_path = $img->storeAs($folderPath, $file);
        return 'store/' . $storage_path;

        //    Storage::disk('public')->put($folderPath . $file, $img);

        //  return Storage::disk('public')->url($folderPath . $file);
    }
    public function fcm()
    {
        $session = UserFirebaseSessions::where("token", request()->bearerToken())->get()->first();
        abort_unless($session, 404);
        $session->fcm_token = \request("token");
        $session->save();
        return apiResponse2(1, 'retrieved', "");
    }
    public function loginHistory()
    {
        $user = apiAuth();
        return apiResponse2(1, 'retrieved', "", $user->loginHistories->all());
    }

    public function storeAddress(Request $request)
    {
        
        $user = apiAuth();

        // if (!$user) {
        //     abort(403);
        // }
        
        validateParam($request->all(), [
            'country_id' => 'required',
            'address' => 'required|max:255',
            'house_no' => 'required',
            'zip_code' => 'required',
            /*'province_id' => 'required',*/
            // 'city_id' => 'required',
            'create_account' => 'required'
        ]);
        $data = $request->all();
            
        try {
        
            $user_as_a_guest=false;
            if(!$user){
                $guestuser = new \stdClass(); // Create an empty object for guest users
                $guestuser->id = $data['device_id'] ?? null;
                $user_as_a_guest=true;
                if (!$guestuser->id) {
                    return apiResponse2(0, 'invalid_device_id', 'Device ID is required for guest users.');
                }
                $carts = Cart::where('creator_guest_id', $guestuser->id)->get();
                $userid = $guestuser->id;
            }
            else{
                $carts = Cart::where('creator_id', $user->id)->get();
                $userid = $user->id;
            }
           
            
            foreach ($carts as $cart) {
                if (!empty($cart->product_order_id)) {
                    ProductOrder::where('id', $cart->product_order_id)
                        ->where('buyer_id', $userid)
                        ->update([
                            'message_to_seller' => $data['description'],
                        ]);
                }
            }

           

            if (!$user) {
                
                $name = $data['first_name']." ".$data['last_name'];
                $createuser = User::create([
                    'device_id_or_ip_address' => $data['device_id'],
                    'country_id'    => $data['country_id'] ?? null,
                    'province_name' => $data['province_name'] ?? null,
                    'city_name'     => $data['city_name'] ?? null,
                    'zip_code'      => $data['zip_code'] ?? null,
                    'house_no'      => $data['house_no'] ?? null,
                    'address'       => $data['address'] ?? null,
                    'district_name' => $data['district_name'] ?? null,
                    'full_name'     => $name ?? null,
                    'email'         => $data['email'] ?? null,
                    'mobile'        => $data['mobile'] ?? null,
                    'role_id'       => 1,
                    'role_name'     => 'user',
                    'created_at'    => Carbon::now()->timestamp,
                    'updated_at'    => Carbon::now()->timestamp
                ]);
                    
                if($data['create_account']){    
                    if($createuser->id){
                        if($data['create_account']){
                            Cart::where('creator_guest_id', $userid)
                            ->update([
                                'creator_id' => $createuser->id,
                            ]);
                        }
                    }
                }
            }
            else{

                $name = $data['first_name']." ".$data['last_name'];
                $user->update([
                    'country_id' => $data['country_id'] ?? $user->country_id,
                    'province_name' => $data['province_name'] ?? $user->province_name,
                    'city_name' => $data['city_name'] ?? $user->city_name,
                    'district_id' => $user->district_id ?? null,
                    'zip_code' => $data['zip_code'] ?? $user->zip_code,
                    'house_no' => $data['house_no'] ?? $user->house_no,
                    'address' => $data['address'] ?? $user->address,
                    'district_name' => $data['district_name'] ?? $user->district_name,
                    'full_name'     => $name ?? null,
                    'email'         => $data['email'] ?? null,
                    'mobile'        => $data['mobile'] ?? null,
                    'updated_at'    => Carbon::now()->timestamp
                ]);
            }
            
            // panel.user_setting_success

            return apiResponse2(1, 'updated', trans('api.public.updated'));

        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                // Duplicate entry error
                return apiResponse2(0,'error','The email address or mobile no is already in use.');
            } 
            return apiResponse2(0, 'error', 'Something went wrong');
        }
        

    }

}
