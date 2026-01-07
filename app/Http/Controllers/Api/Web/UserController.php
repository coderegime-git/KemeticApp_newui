<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Mixins\Cashback\CashbackRules;
use App\Mixins\RegistrationPackage\UserPackage;
use App\Mixins\RegistrationBonus\RegistrationBonusAccounting;
use App\Models\Api\Meeting;
use App\Models\Newsletter;
use App\Models\Api\ReserveMeeting;
use App\Models\Role;
use App\Models\Sale;
use App\Models\Reel;
use App\Models\UserOccupation;
use App\Models\Api\Webinar;
// use App\Models\Api\User;
use App\Models\ForumTopic;
use App\Models\UserStory;
use App\Models\UserStoryView;
use App\Models\Comment;
use App\Models\Gift;
use App\Models\Support;
use App\Models\Reward;
use App\Models\RewardAccounting;
use App\Models\ReelSaved;
use App\Models\Order;
use App\Models\Affiliate;
use App\Models\Product;
use App\User;
use Carbon\Carbon;
use App\Models\Api\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Exception;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    public function profile(Request $request, $id)
    {
        $userStories = collect();

        // $user = User::where('id', $id)
        //     ->whereIn('role_name', [Role::$organization, Role::$teacher, Role::$user])
        //     ->first();

        $user = User::where('id', $id)
        ->whereIn('role_name', [Role::$organization, Role::$teacher, Role::$user, Role::$admin])
            ->with([
                'blog' => function ($query) {
                    $query->where('status', 'publish');
                    $query->withCount([
                        'comments' => function ($query) {
                            $query->where('status', 'active');
                        }
                    ]);
                },
                'products' => function ($query) {
                    $query->where('status', Product::$active)
                    ->with('media');
                },
                'reels' => function ($query) {
                    $query->where('is_hidden', '0');
                },
                'stories' => function ($query) {
                    $query->active()
                            ->withCount('views')
                            ->orderBy('created_at', 'desc');
                },
                'userMetas'
        ])
        ->first();

        if (!$user) {
            abort(404);
        }

        if ($user) {
            // Set full URL for avatar
            $user->avatar = !empty($user->avatar) ? url($user->avatar) : "";
            
            // Set full URLs for each story's media_url
            if ($user->stories) {
                foreach ($user->stories as $story) {
                    $story->media_url = url($story->media_url);
                }
            }
            
            // Also consider other relationships that might need URL conversion
            if ($user->blog) {
                // Handle blog media URLs if needed
                foreach ($user->blog as $blog) {
                    $blog->image = url($blog->image);
                }
            }

            if ($user->products) {
                foreach ($user->products as $product) {
                    if ($product->thumbnail) {
                        $product->thumbnail = url($product->thumbnail);
                    }
                    
                    // Update media collection paths
                    if ($product->media) {
                        foreach ($product->media as $media) {
                            $media->path = url($media->path);
                        }
                    }
                }
            }

            if ($user->webinars) {
                foreach ($user->webinars as $webinar) {
                    $webinar->thumbnail = url($webinar->thumbnail); // Add backslash
                    $webinar->image_cover = url($webinar->image_cover); 
                }
            }
            
            
            // Handle reels media URLs if needed
            if ($user->reels) {
                foreach ($user->reels as $reel) {
                    // $reel->media_url = url($reel->media_url);
                }
            }
        }
        
        $totalCounts = [
            'likes' => 0,
            'comments' => 0,
            'reviews' => 0
        ];

        foreach ($user->blog as $article) {
            $totalCounts['likes'] += $article->like()->count();
            $totalCounts['comments'] += $article->comments()->where('status', 'active')->count();
            $totalCounts['reviews'] += $article->reviews()->where('status', 'active')->count();
        }

        // 2. Products counts
        foreach ($user->products as $product) {
            $totalCounts['likes'] += $product->likes()->count();
            $totalCounts['comments'] += $product->comments()->count();
            $totalCounts['reviews'] += $product->reviews()->where('status', 'active')->count();
        }

        // 3. Reels counts (reels don't have reviews)
        foreach ($user->reels as $reel) {
            $totalCounts['likes'] += $reel->likes()->count();
            $totalCounts['comments'] += $reel->comments()->count();
            // Reels don't have reviews
        }

        // 4. Webinars counts
        foreach ($user->webinars as $webinar) {
            // $totalCounts['likes'] += $webinar->likes()->count(); // If webinar has likes relation
            $totalCounts['comments'] += $webinar->comments()->count();
            $totalCounts['reviews'] += $webinar->reviews()->where('status', 'active')->count();
        }

        $userMetas = $user->userMetas;

        if (!empty($userMetas)) {
            foreach ($userMetas as $meta) {
                $user->{$meta->name} = $meta->value;
            }
        }
        $userBadges = $user->getBadges();

        $meeting = \App\Models\Meeting::where('creator_id', $user->id)
            ->with([
                'meetingTimes'
            ])
            ->first();
        $cashbackRules = null;

        if (!empty($meeting) and !empty($meeting->meetingTimes)) {

            $authUser = apiAuth();
            /* Cashback Rules */
            if (getFeaturesSettings('cashback_active') and (empty($authUser) or !$authUser->disable_cashback)) {
                $cashbackRulesMixin = new CashbackRules($authUser);
                $cashbackRules = $cashbackRulesMixin->getRules('meetings', null, null, null, $user->id);
            }
        }
        
        // Transform user details to handle commission fields
        $userDetails = $user->details;
        if (is_array($userDetails) || is_object($userDetails)) {
            $userDetails = $this->transformNullStrings($userDetails);
            $userDetails = $this->fixCommissionField($userDetails);
        }

        $followings = $user->following();
        $followers = $user->followers();

        $authUser = apiAuth();
        
        $authUserIsFollower = false;
        
        if ($authUser) {
            // $authUserIsFollower = $followers->where('follower', $authUser->id)
            //     ->where('status', Follow::$accepted)
            //     ->first();
            $authUserIsFollower = Follow::where('user_id', $user->id)  // users who follow the current user
                ->where('follower', $authUser->id)  // where follower is the auth user
                ->where('status', Follow::$accepted)
                ->exists();
            $authUserIsFollower = !is_null($authUserIsFollower);
        } else {
            $authUserIsFollower = false;
        }
        
        $user->auth_user_is_follower = $authUserIsFollower;

        $userMetas = $user->userMetas;
        $occupations = $user->occupations()
            ->with([
                'category'
            ])->get();


        $webinars = Webinar::where('status', Webinar::$active)
            ->where('private', false)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })
            ->orderBy('updated_at', 'desc')
            ->with(['teacher' => function ($qu) {
                $qu->select('id', 'full_name', 'avatar');
            }, 'reviews', 'tickets', 'feature'])
            ->get();

        $webinars = $webinars->map(function ($webinar) {
          
            $webinar->thumbnail = \url($webinar->thumbnail); // Add backslash
            $webinar->image_cover = \url($webinar->image_cover); // Add backslash
            return $webinar;
        });

        // dd($webinars);

        $meetingIds = Meeting::where('creator_id', $user->id)->pluck('id');
        $appointments = ReserveMeeting::whereIn('meeting_id', $meetingIds)
            ->whereNotNull('reserved_at')
            ->where('status', '!=', ReserveMeeting::$canceled)
            ->count();

        $studentsIds = Sale::whereNull('refund_at')
            ->where('seller_id', $user->id)
            ->whereNotNull('webinar_id')
            ->pluck('buyer_id')
            ->toArray();
        $user->students_count = count(array_unique($studentsIds));

        $instructors = null;
        if ($user->isOrganization()) {
            $instructors = User::where('organ_id', $user->id)
                ->where('role_name', Role::$teacher)
                ->where('status', 'active')
                ->get();
        }

        $instructorDiscounts = null;

        if (!empty(getFeaturesSettings('frontend_coupons_status'))) {
            $instructorDiscounts = Discount::query()
                ->where('creator_id', $user->id)
                ->where(function (Builder $query) {
                    $query->where('source', 'all');
                    $query->orWhere('source', Discount::$discountSourceMeeting);
                })
                ->where('status', 'active')
                ->where('expired_at', '>', time())
                ->get();
        }

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
        
        // dd($user->rates());
        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), [
            'user' => $user,
            'userBadges' => $userBadges,
            'meeting' => $meeting,
            // 'times' => $times,
            'userRates' => $user->rates(),
            'userFollowers' => $followers,
            'userFollowing' => $followings,
            'auth_user_is_follower' => $authUserIsFollower,
            'educations' => $userMetas->where('name', 'education'),
            'experiences' => $userMetas->where('name', 'experience'),
            'occupations' => $occupations,
            'webinars' => $webinars,
            'appointments' => $appointments,
            'meetingTimezone' => $meeting ? $meeting->getTimezone() : null,
            'instructors' => $instructors,
            'forumTopics' => $this->getUserForumTopics($user->id),
            'instructorDiscounts' => $instructorDiscounts,
            'cashbackRules' => $cashbackRules,
            'totalLikes' => $totalCounts['likes'],
            'totalComments' => $totalCounts['comments'],
            'totalReviews' => $totalCounts['reviews'],
            // 'stories' => $userStories
        ]);

    }
    
    private function getUserForumTopics($userId)
    {
        $forumTopics = null;

        if (!empty(getFeaturesSettings('forums_status')) and getFeaturesSettings('forums_status')) {
            $forumTopics = ForumTopic::where('creator_id', $userId)
                ->orderBy('pin', 'desc')
                ->orderBy('created_at', 'desc')
                ->withCount([
                    'posts'
                ])
                ->get();

            foreach ($forumTopics as $topic) {
                $topic->lastPost = $topic->posts()->orderBy('created_at', 'desc')->first();
            }
        }

        return $forumTopics;
    }

    public function instructors(Request $request)
    {
        $providers = $this->handleProviders($request, [Role::$teacher]);

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $providers);

    }

    public function consultations(Request $request)
    {
        $providers = $this->handleProviders($request, [Role::$teacher, Role::$organization], true);
        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $providers);


    }

    public function organizations(Request $request)
    {
        $providers = $this->handleProviders($request, [Role::$organization]);

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $providers);


    }

    public function providers(Request $request)
    {
        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), [
            'instructors' => $this->instructors($request),
            'organizations' => $this->organizations($request),
            'consultations' => $this->consultations($request),
        ]);

    }

    public function handleProviders(Request $request, $role, $has_meeting = false)
    {
        $offset = $request->input('offset', 0); // Default offset is 0
        $limit = $request->input('limit', 10); // Default limit is 10
        
        $query = User::whereIn('role_name', $role)
            ->where('users.status', 'active')
            ->where(function ($query) {
                $query->where('users.ban', false)
                    ->orWhere(function ($query) {
                        $query->whereNotNull('users.ban_end_at')
                            ->orWhere('users.ban_end_at', '<', time());
                    });
            });
        
        if ($has_meeting) {
            $query->whereHas('meeting');
        }
        
        // Apply the filter providers function
        $query = $this->filterProviders($request, deepClone($query), $role);
        
        // Get total count before pagination
        $totalCount = deepClone($query)->count();

        // Fetch paginated results using skip and take
        $users = $query->skip($offset)->take($limit)->get()
            ->map(function ($user) {
                // Get the brief data
                $brief = $user->brief;
                
                // Transform the data to handle null string fields and commission field
                if (is_array($brief) || is_object($brief)) {
                    $brief = $this->transformNullStrings($brief);
                    $brief = $this->fixCommissionField($brief);
                }
                
                return $brief;
            });
        
        return [
            'count' => $totalCount, // Total count of all users without limit/offset
            'users' => $users, // Limited user data with null strings transformed
            'offset' => $offset === null ? "" : $offset,
            'limit' => $limit === null ? "" : $limit,
        ];
        

    }

    private function filterProviders($request, $query, $role)
    {
        $categories = $request->get('categories', null);
        $sort = $request->get('sort', null);
        $availableForMeetings = $request->get('available_for_meetings', null);
        $hasFreeMeetings = $request->get('free_meetings', null);
        $withDiscount = $request->get('discount', null);
        $search = $request->get('search', null);
        $organization_id = $request->get('organization', null);
        $downloadable = $request->get('downloadable', null);

        if ($downloadable) {
            $query->whereHas('webinars', function ($qu) {
                return $qu->where('downloadable', 1);
            });
        }
        if (!empty($categories) and is_array($categories)) {
            $userIds = UserOccupation::whereIn('category_id', $categories)->pluck('user_id')->toArray();

            $query->whereIn('users.id', $userIds);
        }
        if ($organization_id) {
            $query->where('organ_id', $organization_id);
        }

        if (!empty($sort) and $sort == 'top_rate') {
            $query = $this->getBestRateUsers($query, $role);
        }

        if (!empty($sort) and $sort == 'top_sale') {
            $query = $this->getTopSalesUsers($query, $role);
        }

        if (!empty($availableForMeetings) and $availableForMeetings == 1) {
            $hasMeetings = DB::table('meetings')
                ->where('meetings.disabled', 0)
                ->join('meeting_times', 'meetings.id', '=', 'meeting_times.meeting_id')
                ->select('meetings.creator_id', DB::raw('count(meeting_id) as counts'))
                ->groupBy('creator_id')
                ->orderBy('counts', 'desc')
                ->get();

            $hasMeetingsInstructorsIds = [];
            if (!empty($hasMeetings)) {
                $hasMeetingsInstructorsIds = $hasMeetings->pluck('creator_id')->toArray();
            }

            $query->whereIn('users.id', $hasMeetingsInstructorsIds);
        }

        if (!empty($hasFreeMeetings) and $hasFreeMeetings == 1) {
            $freeMeetingsIds = Meeting::where('disabled', 0)
                ->where(function ($query) {
                    $query->whereNull('amount')->orWhere('amount', '0');
                })->groupBy('creator_id')
                ->pluck('creator_id')
                ->toArray();

            $query->whereIn('users.id', $freeMeetingsIds);
        }

        if (!empty($withDiscount) and $withDiscount == 1) {
            $withDiscountMeetingsIds = Meeting::where('disabled', 0)
                ->whereNotNull('discount')
                ->groupBy('creator_id')
                ->pluck('creator_id')
                ->toArray();

            $userIds = array_unique(array_merge($withDiscountMeetingsIds, $query->pluck('id')->toArray()));
            $query->whereIn('users.id', $userIds);
        }

        if (!empty($search)) {
            $query->where(function ($qu) use ($search) {
                $qu->where('users.full_name', 'like', "%$search%")
                    ->orWhere('users.email', 'like', "%$search%")
                    ->orWhere('users.mobile', 'like', "%$search%");
            });
        }

        return $query;
    }

    private function getBestRateUsers($query, $role)
    {
        $query->leftJoin('webinars', function ($join) use ($role) {
            if ($role == Role::$organization) {
                $join->on('users.id', '=', 'webinars.creator_id');
            } else {
                $join->on('users.id', '=', 'webinars.teacher_id');
            }

            $join->where('webinars.status', 'active');
        })->leftJoin('webinar_reviews', function ($join) {
            $join->on('webinars.id', '=', 'webinar_reviews.webinar_id');
            $join->where('webinar_reviews.status', 'active');
        })
            ->whereNotNull('rates')
            ->select('users.*', DB::raw('avg(rates) as rates'))
            ->orderBy('rates', 'desc');

        if ($role == Role::$organization) {
            $query->groupBy('webinars.creator_id');
        } else {
            $query->groupBy('webinars.teacher_id');
        }

        return $query;
    }

    private function getTopSalesUsers($query, $role)
    {
        $query->leftJoin('sales', function ($join) {
            $join->on('users.id', '=', 'sales.seller_id')
                ->whereNull('refund_at');
        })
            ->whereNotNull('sales.seller_id')
            ->select('users.*', 'sales.seller_id', DB::raw('count(sales.seller_id) as counts'))
            ->groupBy('sales.seller_id')
            ->orderBy('counts', 'desc');

        return $query;
    }


    public function sendMessage(Request $request, $id)
    {

        $user = User::find($id);
        if (!$user) {
            abort(404);
        }
        if (!$user->public_message) {
            return apiResponse2(0, 'disabled_public_message', trans('api.user.disabled_public_message'));
        }

        validateParam($request->all(), [
            'title' => 'required|string',
            'email' => 'required|email',
            'description' => 'required|string',
            //    'captcha' => 'required|captcha',
        ]);
        $data = $request->all();

        $mail = [
            'title' => $data['title'],
            'message' => trans('site.you_have_message_from', ['email' => $data['email']]) . "\n" . $data['description'],
        ];

        try {
            Mail::to($user->email)->send(new \App\Mail\SendNotifications($mail));
            return apiResponse2(1, 'email_sent', trans('api.user.email_sent'));

        } catch (Exception $e) {

            return apiResponse2(0, 'email_error', $e->getMessage());

        }


    }


    public function makeNewsletter(Request $request)
    {
        validateParam($request->all(), [
            'email' => 'required|string|email|max:255|unique:newsletters,email'
        ]);

        $data = $request->all();
        $user_id = null;
        $email = $data['email'];
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->email == $email) {
                $user_id = $user->id;

                $user->update([
                    'newsletter' => true,
                ]);
            }
        }

        Newsletter::create([
            'user_id' => $user_id,
            'email' => $email,
            'created_at' => time()
        ]);

        return apiResponse2('1', 'subscribed_newsletter', 'email subscribed in newsletter successfully.');


    }


    public function availableTimes(Request $request, $id)
    {
        $date = $request->input('date');

        $day_label = $request->input('day_label');

        $timestamp = strtotime($date);

        //  dd($timestamp);
        $user = User::where('id', $id)
            ->whereIn('role_name', [Role::$teacher, Role::$organization])
            ->where('status', 'active')
            ->first();

        if (!$user) {
            abort(404);
        }

        $meeting = Meeting::where('creator_id', $user->id)->first();

        $meetingTimes = [];

        if (!empty($meeting->meetingTimes)) {
            foreach ($meeting->meetingTimes->groupBy('day_label') as $day => $meetingTime) {

                foreach ($meetingTime as $time) {
                    $can_reserve = true;

                 $explodetime = explode('-', $time->time);

                     $secondTime = dateTimeFormat(strtotime($explodetime['0']), 'H') * 3600 + dateTimeFormat(strtotime($explodetime['0']), 'i') * 60;

                    $reserveMeeting = ReserveMeeting::where('meeting_time_id', $time->id)
                        ->where('day', dateTimeFormat($timestamp, 'Y-m-d'))
                        ->where('meeting_time_id', $time->id)
                        ->first();

                    if ($reserveMeeting && ($reserveMeeting->locked_at || $reserveMeeting->reserved_at)) {
                        $can_reserve = false;
                    }

                        if ($timestamp + $secondTime < time()) {
                           $can_reserve = false;
                       }
                    // $time_explode = explode('-', $time->time);
                    // Carbon::parse($time_explode[0]);

                    $user = apiAuth();
                    $userReservedMeeting = null;
                    if ($user) {
                        $userReservedMeeting = ReserveMeeting::where('user_id', $user->id)
                            ->where('meeting_id', $meeting->id)->where('meeting_time_id',
                                $time->id
                            )
                            ->first();
                    }


                    $meetingTimes[$day]["times"][] =
                        [
                            "id" => $time->id,
                            "time" => $time->time,
                            "can_reserve" => $can_reserve,
                            "description" => $time->description,
                            'meeting_type'=>$time->meeting_type ,
                            'meeting' => $time->meeting->details,
                            'auth_reservation' => $userReservedMeeting

                        ];
                }
            }
        }

        //  return $meetingTimes ;
        $array = [];;
        foreach ($meetingTimes as $day => $time) {
            if ($day == strtolower(date('l', $timestamp))) // if ($day == $day_label) {
            {
                $array = $time['times'];

            }
        }

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), [
            'count' => count($array),
            'times' => $array
        ]);

    }

    /**
     * Recursively transform null strings to empty strings in an array or object
     *
     * @param mixed $data
     * @return mixed
     */
    private function transformNullStrings($data)
    {
        if (is_array($data)) {
            return array_map(function ($item) {
                return $this->transformNullStrings($item);
            }, $data);
        }

        if (is_object($data)) {
            $vars = get_object_vars($data);
            foreach ($vars as $key => $value) {
                $data->$key = $this->transformNullStrings($value);
            }
            return $data;
        }

        // Convert null strings to empty strings
        if ($data === null) {
            return "";
        }

        // Also handle empty strings that might be considered null in some contexts
        if (is_string($data) && trim($data) === '') {
            return "";
        }

        return $data;
    }

    /**
     * Fix commission field - if empty or null, set to 0
     * Handles nested structures like user_group.commission
     *
     * @param mixed $data
     * @return mixed
     */
    private function fixCommissionField($data)
    {
        if (is_array($data)) {
            // Check for commission field at current level
            if (isset($data['commission']) && ($data['commission'] === null || $data['commission'] === '' || $data['commission'] === '0')) {
                $data['commission'] = 0;
            }
            
            // Check for nested user_group.commission
            if (isset($data['user_group']) && is_array($data['user_group'])) {
                if (isset($data['user_group']['commission']) && ($data['user_group']['commission'] === null || $data['user_group']['commission'] === '' || $data['user_group']['commission'] === '0')) {
                    $data['user_group']['commission'] = 0;
                }
                
                // Also check for any other nested commission fields within user_group
                $data['user_group'] = $this->fixCommissionField($data['user_group']);
            }
            
            // Recursively process all array elements
            foreach ($data as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    $data[$key] = $this->fixCommissionField($value);
                }
            }
            
            return $data;
        }

        if (is_object($data)) {
            // Check for commission field at current level
            if (isset($data->commission) && ($data->commission === null || $data->commission === '' || $data->commission === '0')) {
                $data->commission = 0;
            }
            
            // Check for nested user_group->commission
            if (isset($data->user_group) && is_object($data->user_group)) {
                if (isset($data->user_group->commission) && ($data->user_group->commission === null || $data->user_group->commission === '' || $data->user_group->commission === '0')) {
                    $data->user_group->commission = 0;
                }
                
                // Also check for any other nested commission fields within user_group
                $data->user_group = $this->fixCommissionField($data->user_group);
            }
            
            // Recursively process all object properties
            $vars = get_object_vars($data);
            foreach ($vars as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    $data->$key = $this->fixCommissionField($value);
                }
            }
            
            return $data;
        }

        return $data;
    }

    public function dashboard(Request $request, $id)
    {

        $user = User::where('id', $id)
            ->whereIn('role_name', [Role::$organization, Role::$teacher, Role::$user])
            ->first();
        if (!$user) {
            abort(404);
        }

        if (session()->has('user_just_registered') || $user->user_just_registered == '1') {
            
            // Ensure the tasks only run once
            $registeredUserId = session('user_just_registered');
            session()->forget('user_just_registered'); // Clear the flag immediately
            // update user_just_registered to 0
            $user->update(['user_just_registered' => 0]);

            // Verify the current logged-in user matches the registered ID (safety check)
            if ($user->id == $registeredUserId) {
                event(new Registered($user));

                $notifyOptions = [
                    '[u.name]' => $user->full_name,
                    '[u.role]' => trans("update.role_{$user->role_name}"),
                    '[time.date]' => dateTimeFormat($user->created_at, 'j M Y H:i'),
                ];
                sendNotification("new_registration", $notifyOptions, 1);

                // 2. Reward Accounting
                $registerReward = RewardAccounting::calculateScore(Reward::REGISTER);
                RewardAccounting::makeRewardAccounting($user->id, $registerReward, Reward::REGISTER, $user->id, true);

                // 3. Affiliate/Referral Storage
                $referralCode = session('referralCode', null); // Retrieve referral code saved during registration
                if (!empty($referralCode)) {
                    Affiliate::storeReferral($user, $referralCode);
                    session()->forget('referralCode'); // Clear referral code after use
                }

                // 4. Registration Bonus
                $registrationBonusAccounting = new RegistrationBonusAccounting();
                $registrationBonusAccounting->storeRegistrationBonusInstantly($user);
            }
        }

        $nextBadge = $user->getBadges(true, true);

        // $data = [
        //     'pageTitle' => trans('panel.dashboard'),
        //     'nextBadge' => $nextBadge
        // ];

        $userRole = $user->role->caption;
    
        // For all users
        $data['user_role'] = $userRole;
        $data['username'] = $user->full_name ?: $user->username;
       

        if ($user->isUser()) {
            
            $data['seeker_data'] = [
                'continue_learning' => $this->getContinueLearningCount($user),
                'my_courses' => $this->getMyCoursesCount($user),
                'saved_reels' => $this->getSavedReelsCount($user),
                'orders' => $this->getOrdersCount($user),
                'membership' => [
                    'status' => $this->getMembershipStatus($user),
                    'price' => $this->getMembershipPrice($user)
                ],
                'messages' => $this->getMessagesCount($user),
                // Store detailed data for drawer
                'detailed_data' => [
                    'continue' => $this->getContinueLearningData($user),
                    'myCourses' => $this->getMyCoursesData($user),
                    'savedReels' => $this->getSavedReelsData($user),
                    'orders' => $this->getOrdersData($user),
                    'membership' => $this->getMembershipDetailedData($user),
                    'messages' => $this->getMessagesData($user),
                ]
            ];
        } elseif ($user->isTeacher() || $user->isOrganization()) {
            $data['creator_data'] = [
                'reel_studio' => $this->getReelStudioCount($user),
                'live_studio' => $this->getLiveStudioStatus($user),
                'creator_analytics' => $this->getCreatorAnalytics($user),
                'payouts' => $this->getPayoutsTotal($user),
                'detailed_data' => [
                    'reelStudio' => $this->getReelStudioData($user),
                    'liveStudio' => $this->getLiveStudioData($user),
                    'creatorAnalytics' => $this->getCreatorAnalyticsData($user),
                    'payouts' => $this->getPayoutsData($user),
                ]
            ];
            
            $data['keeper_data'] = [
                'courses' => $this->getInstructorCoursesCount($user),
                'students' => $this->getInstructorStudentsCount($user),
                'reel_studio' => $this->getReelStudioCount($user),
                'live_studio' => $this->getLiveStudioStatus($user),
                'products' => $this->getProductsCount($user),
                'vendor_orders' => $this->getVendorOrdersCount($user),
                'books' => $this->getBooksCount($user),
                'royalties' => $this->getRoyaltiesTotal($user),
                'analytics' => $this->getAnalyticsGrowth($user),
                'payouts' => $this->getTotalPayouts($user),
                'detailed_data' => [
                    'courses' => $this->getInstructorCoursesData($user),
                    'students' => $this->getInstructorStudentsData($user),
                    'reelStudio' => $this->getReelStudioData($user),
                    'liveStudio' => $this->getLiveStudioData($user),
                    'products' => $this->getProductsData($user),
                    'ordersVendor' => $this->getVendorOrdersData($user),
                    'books' => $this->getBooksData($user),
                    'royalties' => $this->getRoyaltiesData($user),
                    'keeperAnalytics' => $this->getAnalyticsDetailedData($user),
                    'payouts' => $this->getTotalPayoutsData($user),
                ]
            ];
        }

        $data['giftModal'] = $this->showGiftModal($user);
        
        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), [
            'data' => $data
        ]);
    }

    private function getContinueLearningCount($user)
    {
        $webinarsIds = $user->getPurchasedCoursesIds();
        $count = 0;
        
        if (!empty($webinarsIds)) {
            foreach ($webinarsIds as $webinarId) {
                $webinar = Webinar::find($webinarId);
                if ($webinar && $webinar->getProgress() < 100) {
                    $count++;
                }
            }
        }
        
        return $count;
    }

    private function getMyCoursesCount($user)
    {
        $webinarsIds = $user->getPurchasedCoursesIds();
        return count($webinarsIds ?? []);
    }

    private function getSavedReelsCount($user)
    {
        return ReelSaved::where('user_id', $user->id)->count();
    }

    private function getOrdersCount($user)
    {
        return Order::where('user_id', $user->id)
            ->where('status', Order::$paid)
            ->count();
    }

    private function getMembershipStatus($user)
    {
        // Check if user has active subscription
        // This is a placeholder - implement based on your membership system
        return 'Active';
    }

    private function getMembershipPrice($user)
    {
        // Get user's membership price
        // This is a placeholder - implement based on your membership system
        return '€1/mo';
    }

    private function getMessagesCount($user)
    {
        // Get unread messages count
        // This is a placeholder - implement based on your messaging system
        return 0;
    }

    private function getInstructorCoursesCount($user)
    {
        return Webinar::where(function ($query) use ($user) {
                if ($user->isTeacher()) {
                    $query->where('teacher_id', $user->id);
                } elseif ($user->isOrganization()) {
                    $query->where('creator_id', $user->id);
                }
            })
            ->where('status', 'active')
            ->count();
    }

    private function getInstructorStudentsCount($user)
    {
        $courses = Webinar::where(function ($query) use ($user) {
                if ($user->isTeacher()) {
                    $query->where('teacher_id', $user->id);
                } elseif ($user->isOrganization()) {
                    $query->where('creator_id', $user->id);
                }
            })
            ->where('status', 'active')
            ->get();
        
        $uniqueStudents = collect();
        
        foreach ($courses as $course) {
            $studentIds = $course->getStudentsIds();
            $uniqueStudents = $uniqueStudents->merge($studentIds);
        }
        
        return $uniqueStudents->unique()->count();
    }

    // Placeholder methods for other counts (implement based on your system)
    private function getReelStudioCount($user) { return '+ New'; }
    private function getLiveStudioStatus($user) { return 'Ready'; }
    private function getCreatorAnalytics($user) { return '0 views'; }
    private function getPayoutsTotal($user) { return '€0'; }
    private function getProductsCount($user) { return 0; }
    private function getVendorOrdersCount($user) { return 0; }
    private function getBooksCount($user) { return 0; }
    private function getRoyaltiesTotal($user) { return '€0'; }
    private function getAnalyticsGrowth($user) { return '↑ 0%'; }
    private function getTotalPayouts($user) { return '€0'; }

    // Detailed data methods (for drawer)
    private function getContinueLearningData($user)
    {
        $webinarsIds = $user->getPurchasedCoursesIds();
        $courses = [];
        
        if (!empty($webinarsIds)) {
            $courses = Webinar::whereIn('id', $webinarsIds)
                ->where('status', 'active')
                ->with(['creator'])
                ->get()
                ->map(function ($webinar) use ($user) {
                    $progress = $webinar->getProgress();
                    if ($progress < 100) {
                        return [
                            'id' => $webinar->id,
                            'title' => $webinar->title,
                            'instructor' => $webinar->creator->full_name ?? $webinar->creator->username,
                            'progress' => $progress,
                            'updated_at' => date('Y-m-d H:i', $webinar->updated_at),
                            'url' => $webinar->getLearningPageUrl(),
                        ];
                    }
                    return null;
                })
                ->filter()
                ->values()
                ->toArray();
        }
        
        return $courses;
    }

    private function getMyCoursesData($user)
    {
        $webinarsIds = $user->getPurchasedCoursesIds();
        $courses = [];
        
        if (!empty($webinarsIds)) {
            // Get purchase dates
            $purchases = Sale::where('buyer_id', $user->id)
                ->whereIn('webinar_id', $webinarsIds)
                ->where('type', 'webinar')
                ->whereNull('refund_at')
                ->get()
                ->keyBy('webinar_id');
            
            $courses = Webinar::whereIn('id', $webinarsIds)
                ->where('status', 'active')
                ->with(['creator'])
                ->get()
                ->map(function ($webinar) use ($purchases, $user) {
                    $purchase = $purchases->get($webinar->id);
                    return [
                        'id' => $webinar->id,
                        'title' => $webinar->title,
                        'instructor' => $webinar->creator->full_name ?? $webinar->creator->username,
                        'type' => $webinar->type == 'course' ? 'Course' : ($webinar->type == 'webinar' ? 'Webinar' : 'Text Lesson'),
                        'enrolled_at' => $purchase ? date('Y-m-d H:i', $purchase->created_at) : date('Y-m-d H:i', time()),
                        'progress' => $webinar->getProgress(),
                        'url' => $webinar->getLearningPageUrl(),
                    ];
                })
                ->toArray();
        }
        
        return $courses;
    }

    private function getSavedReelsData($user)
    {
        $reels = ReelSaved::where('user_id', $user->id)
            ->with(['reel.user'])
            ->orderBy('created_at', 'desc')
            ->limit(50) // Limit to 50 items for performance
            ->get()
            ->map(function ($savedReel) {
                $reel = $savedReel->reel;
                if (!$reel) return null;
                
                return [
                    'id' => $reel->id,
                    'title' => $reel->title ?? 'Untitled Reel',
                    'creator' => $reel->user->full_name ?? $reel->user->username ?? 'Unknown',
                    // 'saved_at' => date('Y-m-d H:i', $savedReel->created_at),
                    'views' => $reel->views ?? 0,
                    'duration' => $reel->duration ?? '0:00',
                ];
            })
            ->filter()
            ->values()
            ->toArray();
        
        return $reels;
    }

    private function getOrdersData($user)
    {
        $orders = Order::where('user_id', $user->id)
            ->where('status', Order::$paid)
            ->with(['orderItems'])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($order) {
                $items = $order->orderItems->map(function ($item) {
                    return $item->title ?? 'Product';
                })->implode(', ');
                
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number ?? 'N/A',
                    'items' => $items,
                    'total' => $order->total_amount,
                    'date' => date('Y-m-d H:i', $order->created_at),
                    'status' => $order->status,
                ];
            })
            ->toArray();
        
        return $orders;
    }

    private function getMembershipDetailedData($user)
    {
        return [[
            'plan' => 'Basic',
            'cycle' => 'Monthly',
            'status' => 'Active',
            'renewal' => date('Y-m-d', strtotime('+1 month')),
            'price' => '€1/mo',
        ]];
    }

    private function getMessagesData($user)
    {
        // This is placeholder - implement with your messaging system
        return [];
    }

    // Additional detailed data methods for Creator/Keeper
    private function getInstructorCoursesData($user)
    {
        $courses = Webinar::where(function ($query) use ($user) {
                if ($user->isTeacher()) {
                    $query->where('teacher_id', $user->id);
                } elseif ($user->isOrganization()) {
                    $query->where('creator_id', $user->id);
                }
            })
            ->where('status', 'active')
            ->withCount(['sales as enrollments_count' => function($query) {
                $query->whereNull('refund_at');
            }])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'type' => $course->type == 'course' ? 'Course' : ($course->type == 'webinar' ? 'Webinar' : 'Text Lesson'),
                    'price' => $course->price,
                    'enrollments' => $course->enrollments_count,
                    'rating' => $course->getRate(),
                    'updated_at' => date('Y-m-d H:i', $course->updated_at),
                    'url' => $course->getUrl(),
                ];
            })
            ->toArray();
        
        return $courses;
    }

    private function getInstructorStudentsData($user)
    {
        $courses = Webinar::where(function ($query) use ($user) {
                if ($user->isTeacher()) {
                    $query->where('teacher_id', $user->id);
                } elseif ($user->isOrganization()) {
                    $query->where('creator_id', $user->id);
                }
            })
            ->where('status', 'active')
            ->get();
        
        $students = collect();
        
        foreach ($courses as $course) {
            $studentIds = $course->getStudentsIds();
            
            foreach ($studentIds as $studentId) {
                $student = User::find($studentId);
                if ($student) {
                    $progress = $course->getProgress($studentId);
                    
                    $students->push([
                        'id' => $student->id,
                        'name' => $student->full_name ?? $student->username,
                        'email' => $student->email,
                        'course_id' => $course->id,
                        'course_title' => $course->title,
                        'progress' => $progress,
                        'enrolled_at' => date('Y-m-d H:i', $course->created_at),
                        'last_access' => date('Y-m-d H:i', $student->last_access_at ?? time()),
                    ]);
                }
            }
        }
        
        return $students->unique('id')->values()->toArray();
    }

    // Placeholder detailed data methods for other sections
    private function getReelStudioData($user) { return []; }
    private function getLiveStudioData($user) { return []; }
    private function getCreatorAnalyticsData($user) { return []; }
    private function getPayoutsData($user) { return []; }
    private function getProductsData($user) { return []; }
    private function getVendorOrdersData($user) { return []; }
    private function getBooksData($user) { return []; }
    private function getRoyaltiesData($user) { return []; }
    private function getAnalyticsDetailedData($user) { return []; }
    private function getTotalPayoutsData($user) { return []; }

    private function getSeekerData($user)
    {
        // 1. Continue Learning - Get courses with progress < 100%
        $webinarsIds = $user->getPurchasedCoursesIds();
        $continueLearningCount = 0;
        
        if (!empty($webinarsIds)) {
            foreach ($webinarsIds as $webinarId) {
                $webinar = Webinar::find($webinarId);
                if ($webinar && $webinar->getProgress() < 100) {
                    $continueLearningCount++;
                }
            }
        }

        // 2. My Courses - All purchased courses
        $myCoursesCount = count($webinarsIds ?? []);

        // 3. Saved Reels
        $savedReelsCount = ReelSaved::where('user_id', $user->id)->count();

        // 4. Orders
        $ordersCount = Order::where('user_id', $user->id)
            ->where('status', Order::$paid)
            ->count();

        // 5. Membership status
        $membershipStatus = 'Active'; // This would come from your membership system
        $membershipPrice = '€1/mo'; // Default or actual price

        // 6. Messages (assuming you have a messaging system)
        $messagesCount = 0; // Replace with actual count from your messaging system

        return [
            'continue_learning' => $continueLearningCount,
            'my_courses' => $myCoursesCount,
            'saved_reels' => $savedReelsCount,
            'orders' => $ordersCount,
            'membership' => [
                'status' => $membershipStatus,
                'price' => $membershipPrice
            ],
            'messages' => $messagesCount
        ];
    }

    private function getCreatorData($user)
    {
        // 1. Reel Studio - Count of user's reels
        $reelCount = 0; // Assuming you have a Reel model
        // $reelCount = Reel::where('user_id', $user->id)->count();
        
        // 2. Live Studio - Check if user has active live streams
        $liveStatus = 'Go Live'; // This would check if user has live streaming capability
        
        // 3. Creator Analytics - Get views count
        $totalViews = 0; // This would come from your analytics system
        
        // 4. Payouts - Total earnings
        $payoutsTotal = '€0'; // This would come from your payout system
        
        return [
            'reel_studio' => $reelCount > 0 ? $reelCount : '+ New',
            'live_studio' => $liveStatus,
            'creator_analytics' => number_format($totalViews) . ' views',
            'payouts' => $payoutsTotal
        ];
    }

    private function getKeeperData($user)
    {
        // Instructor data
        $coursesCount = Webinar::where(function ($query) use ($user) {
                if ($user->isTeacher()) {
                    $query->where('teacher_id', $user->id);
                } elseif ($user->isOrganization()) {
                    $query->where('creator_id', $user->id);
                }
            })
            ->where('status', 'active')
            ->count();

        // Get total students across all courses
        $totalStudents = 0;
        $userCourses = Webinar::where(function ($query) use ($user) {
                if ($user->isTeacher()) {
                    $query->where('teacher_id', $user->id);
                } elseif ($user->isOrganization()) {
                    $query->where('creator_id', $user->id);
                }
            })
            ->where('status', 'active')
            ->get();

        foreach ($userCourses as $course) {
            $totalStudents += count($course->getStudentsIds());
        }

        // Vendor data (if applicable)
        $productsCount = 0; // From Product model if exists
        $vendorOrdersCount = 0; // From Order model with vendor filter
        
        // Books data (if applicable)
        $booksCount = 0; // From Books model if exists
        $royaltiesTotal = '€0'; // From royalties system
        
        // Shared analytics
        $analyticsGrowth = '↑ 0%'; // Calculate growth percentage
        
        // Total payouts
        $totalPayouts = '€0'; // Sum of all payout sources

        return [
            // Creator section
            'reel_studio' => '+ New',
            'live_studio' => 'Ready',
            
            // Instructor section
            'courses' => $coursesCount,
            'students' => number_format($totalStudents),
            
            // Vendor section
            'products' => $productsCount,
            'vendor_orders' => number_format($vendorOrdersCount),
            
            // Books section
            'books' => $booksCount,
            'royalties' => $royaltiesTotal,
            
            // Shared
            'analytics' => $analyticsGrowth,
            'payouts' => $totalPayouts
        ];
    }

    private function showGiftModal($user)
    {
        $gift = Gift::query()->where('email', $user->email)
            ->where('status', 'active')
            ->where('viewed', false)
            ->where(function ($query) {
                $query->whereNull('date');
                $query->orWhere('date', '<', time());
            })
            ->whereHas('sale')
            ->first();

        if (!empty($gift)) {
            $gift->update([
                'viewed' => true
            ]);

            $data = [
                'gift' => $gift
            ];

            $result = (string)view()->make('web.default.panel.dashboard.gift_modal', $data);
            $result = str_replace(array("\r\n", "\n", "  "), '', $result);

            return $result;
        }

        return null;
    }

    private function getMonthlySalesOrPurchase($user)
    {
        $months = [];
        $data = [];

        // all 12 months
        for ($month = 1; $month <= 12; $month++) {
            $date = Carbon::create(date('Y'), $month);

            $start_date = $date->timestamp;
            $end_date = $date->copy()->endOfMonth()->timestamp;

            $months[] = trans('panel.month_' . $month);

            if (!$user->isUser()) {
                $monthlySales = Sale::where('seller_id', $user->id)
                    ->whereNull('refund_at')
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->sum('total_amount');

                $data[] = round($monthlySales, 2);
            } else {
                $monthlyPurchase = Sale::where('buyer_id', $user->id)
                    ->whereNull('refund_at')
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->count();

                $data[] = $monthlyPurchase;
            }
        }

        return [
            'months' => $months,
            'data' => $data
        ];
    }

}