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
use App\Models\UserStory;
use App\Models\UserStoryView;
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
        $userStories = collect();
        
        if ($user) {
            // Get stories from users that the current user follows
            // $followingIds = Follow::where('follower', $user->id)
            //     ->where('status', Follow::$accepted)
            //     ->pluck('user_id')
            //     ->toArray();
            
            // // Add current user's own ID to see their own stories
            // $followingIds[] = $user->id;
            
            // // Get stories from last 24 hours
            // $stories = UserStory::whereIn('user_id', $followingIds)
            //     ->where('is_active', true)
            //     ->where('expires_at', '>', Carbon::now())
            //     ->with(['user' => function($query) {
            //         $query->select('id', 'full_name', 'avatar');
            //     }])
            //     ->orderBy('created_at', 'desc')
            //     ->get()
            //     ->groupBy('user_id');
            
            // // Mark if story is viewed by current user
            // $stories = $stories->map(function ($userStories) use ($user) {
            //     return $userStories->map(function ($story) use ($user) {
            //         $story->viewed_by_current_user = UserStoryView::where('story_id', $story->id)
            //             ->where('user_id', $user->id)
            //             ->exists();
            //         return $story;
            //     });
            // });

            $userStories = UserStory::where('user_id', $user->id)
                ->where('is_active', true)
                ->where('expires_at', '>', Carbon::now())
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Mark if story is viewed by current user
            $userStories = $userStories->map(function ($story) use ($user) {
                $story->viewed_by_current_user = UserStoryView::where('story_id', $story->id)
                    ->where('user_id', $user->id)
                    ->exists();
                $story->media_url = url($story->media_url);  
                return $story;
            });
        }

        return apiResponse2(
            1,
            'retrieved',
            trans('api.public.retrieved'),
            [
                'user' => $user->details,
                'stories' => $userStories
            ]
        );
    }

     public function getUserStories($id)
    {
        $authUser = apiAuth();
        $user = User::findOrFail($id);
        
        $stories = UserStory::where('user_id', $id)
            ->where('is_active', true)
            ->where('expires_at', '>', Carbon::now())
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Mark if viewed by current user
        if ($authUser) {
            $stories = $stories->map(function ($story) use ($authUser) {
                $story->viewed_by_current_user = UserStoryView::where('story_id', $story->id)
                    ->where('user_id', $authUser->id)
                    ->exists();
                $story->media_url = url($story->media_url);  
                return $story;
            });
        }
        
        return apiResponse2(
            1,
            'retrieved',
            trans('api.public.retrieved'),
            [
                'stories' => $stories,
                'user' => [
                    'id' => $user->id,
                    'full_name' => $user->full_name,
                    'avatar' => url($user->avatar)
                ]
            ]
        );
    }

    public function uploadStory(Request $request)
    {
        $user = apiAuth();
        
        if (!$user) {
            return apiResponse2(0, 'unauthorized', trans('api.auth.unauthorized'));
        }

        validateParam($request->all(), [
            'story' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi,wmv|max:51200', // 50MB max
            'title' => 'nullable|string|max:100',
            'link' => 'nullable|url|max:255'
        ]);

        try {
            $file = $request->file('story');
            $isVideo = in_array($file->getMimeType(), ['video/mp4', 'video/quicktime', 'video/avi', 'video/wmv']);
            $mediaType = $isVideo ? 'video' : 'image';
            
            // Create directory if it doesn't exist
            $directory = 'stories/' . $user->id . '/' . date('Y/m');
            Storage::disk('public')->makeDirectory($directory);
            
            // Generate unique filename
            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $directory . '/' . $filename;
            
            // Store the file
            Storage::disk('public')->put($path, file_get_contents($file));
            
            $mediaUrl = Storage::disk('public')->url($path);
            $thumbnailUrl = null;
            
            // Generate thumbnail
            if ($isVideo) {
                $thumbnailUrl = $this->generateVideoThumbnail($file, $directory);
            } else {
                $thumbnailUrl = $this->createImageThumbnail($file, $directory);
            }

            if ($request->file('story')) {
                $storage = new UploadFileManager($request->file('story'));
            }
            
            // Create story record
            $story = UserStory::create([
                'user_id' => $user->id,
                'title' => $request->input('title'),
                'media_url' => $mediaUrl,
                'media_type' => $mediaType,
                'thumbnail_url' => $thumbnailUrl,
                'link' => $request->input('link'),
                'is_active' => true,
                'expires_at' => Carbon::now()->addHours(24),
                'created_at' => time(),
                'updated_at' => time(),
            ]);
            
            return apiResponse2(
                1,
                'uploaded',
                trans('Story Uploaded Successfully'),
                [
                    'story' => $story
                ]
            );
            
        } catch (\Exception $e) {
            Log::error('Story upload error: ' . $e->getMessage());
            
            return apiResponse2(
                0,
                'error',
                trans('Upload error')
            );
        }
    }

    public function markStoryViewed(Request $request, $storyId)
    {
        $user = apiAuth();
        
        if (!$user) {
            return apiResponse2(0, 'unauthorized', trans('api.auth.unauthorized'));
        }
        
        $story = UserStory::where('id', $storyId)
            ->where('is_active', true)
            ->where('expires_at', '>', Carbon::now())
            ->first();
        
        if (!$story) {
            return apiResponse2(0, 'not_found', trans('api.story.not_found'));
        }
        
        // Check if already viewed
        $existingView = UserStoryView::where('story_id', $storyId)
            ->where('user_id', $user->id)
            ->first();
        
        if (!$existingView) {
            // Create view record
            UserStoryView::create([
                'story_id' => $storyId,
                'user_id' => $user->id,
                'created_at' => time(),
                'updated_at' => time()
            ]);
            
            // Increment views count
            $story->increment('views');
        }
        
        return apiResponse2(
            1,
            'viewed',
            trans('Story Viewed Successfully')
        );
    }

    public function deleteStory($storyId)
    {
        $user = apiAuth();
        
        if (!$user) {
            return apiResponse2(0, 'unauthorized', trans('api.auth.unauthorized'));
        }
        
        $story = UserStory::where('id', $storyId)
            ->where('user_id', $user->id)
            ->first();
        
        if (!$story) {
            return apiResponse2(0, 'not_found', trans('api.story.not_found'));
        }
        
        try {
            // Delete file from storage
            $this->deleteStoryFile($story->media_url);
            
            if ($story->thumbnail_url) {
                $this->deleteStoryFile($story->thumbnail_url);
            }
            
            // Delete from database
            $story->delete();
            
            return apiResponse2(
                1,
                'deleted',
                trans('api.story.deleted')
            );
            
        } catch (\Exception $e) {
            Log::error('Story delete error: ' . $e->getMessage());
            
            return apiResponse2(
                0,
                'error',
                trans('api.story.delete_error')
            );
        }
    }

    public function getFollowedUsersStories()
    {
        $user = apiAuth();
        
        if (!$user) {
            return apiResponse2(0, 'unauthorized', trans('api.auth.unauthorized'));
        }
        
        // Get users that the current user follows
        $followingIds = Follow::where('follower', $user->id)
            ->where('status', Follow::$accepted)
            ->pluck('user_id')
            ->toArray();
        
        // Add current user's own ID
        $followingIds[] = $user->id;
        
        // Get stories from last 24 hours
        $storiesByUser = UserStory::whereIn('user_id', $followingIds)
            ->where('is_active', true)
            ->where('expires_at', '>', Carbon::now())
            ->with(['user' => function($query) {
                $query->select('id', 'full_name', 'avatar');
            }])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('user_id');
        
        // Format response
        $formattedStories = [];
        foreach ($storiesByUser as $userId => $stories) {
            $userStories = $stories->map(function ($story) use ($user) {
                $story->viewed_by_current_user = UserStoryView::where('story_id', $story->id)
                    ->where('user_id', $user->id)
                    ->exists();
                return $story;
            });
            
            if ($userStories->count() > 0) {
                $formattedStories[] = [
                    'user' => $userStories->first()->user,
                    'stories' => $userStories,
                    'has_unviewed' => $userStories->where('viewed_by_current_user', false)->count() > 0
                ];
            }
        }
        
        return apiResponse2(
            1,
            'retrieved',
            trans('api.public.retrieved'),
            [
                'stories' => $formattedStories
            ]
        );
    }

    // Helper method to generate video thumbnail
    private function generateVideoThumbnail($videoFile, $directory)
    {
        try {
            // Create a temporary file for the video
            $tempVideoPath = tempnam(sys_get_temp_dir(), 'video_') . '.mp4';
            file_put_contents($tempVideoPath, file_get_contents($videoFile->getRealPath()));
            
            // Use FFmpeg to generate thumbnail
            $thumbnailFilename = 'thumbnail_' . uniqid() . '.jpg';
            $thumbnailPath = $directory . '/' . $thumbnailFilename;
            $fullThumbnailPath = storage_path('app/public/' . $thumbnailPath);
            
            // Ensure directory exists
            Storage::disk('public')->makeDirectory($directory);
            
            // Generate thumbnail (using first frame)
            $ffmpegCommand = "ffmpeg -i {$tempVideoPath} -ss 00:00:01 -vframes 1 -vf 'scale=320:-1' {$fullThumbnailPath} 2>&1";
            exec($ffmpegCommand);
            
            // Clean up temp file
            if (file_exists($tempVideoPath)) {
                unlink($tempVideoPath);
            }
            
            if (file_exists($fullThumbnailPath)) {
                return Storage::disk('public')->url($thumbnailPath);
            }
            
            return null;
            
        } catch (\Exception $e) {
            Log::error('Video thumbnail generation error: ' . $e->getMessage());
            return null;
        }
    }

    // Helper method to create image thumbnail
    private function createImageThumbnail($imageFile, $directory)
    {
        try {
            $thumbnailFilename = 'thumbnail_' . uniqid() . '.jpg';
            $thumbnailPath = $directory . '/' . $thumbnailFilename;
            $fullThumbnailPath = storage_path('app/public/' . $thumbnailPath);
            
            // Create thumbnail using Intervention Image
            $image = Image::make($imageFile->getRealPath());
            $image->fit(320, 320, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            
            // Ensure directory exists
            Storage::disk('public')->makeDirectory($directory);
            
            // Save thumbnail
            $image->save($fullThumbnailPath, 80);
            
            return Storage::disk('public')->url($thumbnailPath);
            
        } catch (\Exception $e) {
            Log::error('Image thumbnail creation error: ' . $e->getMessage());
            return null;
        }
    }

    // Helper method to delete story file
    private function deleteStoryFile($url)
    {
        try {
            $path = parse_url($url, PHP_URL_PATH);
            $relativePath = str_replace('/storage/', '', $path);
            
            if (Storage::disk('public')->exists($relativePath)) {
                Storage::disk('public')->delete($relativePath);
            }
        } catch (\Exception $e) {
            Log::error('Story file deletion error: ' . $e->getMessage());
        }
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
