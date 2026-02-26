<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Api\Objects\UserObj;
use App\Http\Controllers\Controller;
use App\Models\Api\Book;
use App\Models\Api\User;
use App\Models\Livestream;
use App\Models\BookTranslation;
use App\Models\Region;
use App\Models\Country;
use App\Services\IvsService;
use App\Models\IvsChatToken;
use App\Services\IvsChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Support\Str;

class LivestreamController extends Controller
{
    protected $ivsService;

    public function __construct(IvsService $ivsService, IvsChatService $ivsChatService)
    {
        $this->ivsService = $ivsService;
        $this->ivsChatService = $ivsChatService;
    }
    
    private function getUserIdFromToken(Request $request)
    {
        $authorizationHeader = $request->header('Authorization');
        
        if (!$authorizationHeader || !str_starts_with($authorizationHeader, 'Bearer ')) {
            return null;
        }
        
        $token = substr($authorizationHeader, 7);
        
        if (empty($token)) {
            return null;
        }
        
        try {
            $user = auth('api')->setToken($token)->user();
            return $user ? $user->id : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function index(Request $request)
    {
        $userId = $this->getUserIdFromToken($request);

        $camera = $request->query('camera');
        $platform = $request->query('platform');
        $country = $request->query('country');
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Invalid or missing token.'
            ], 401);
        }

        $count = Livestream::where('creator_id', $userId)
            ->where('livestream_end', 'No')
            ->orderBy('created_at', 'desc')
            ->get();

        if($count->count() == 0)
        {
            $originalName = $userId;
            
            $cleanName = preg_replace('/[^a-zA-Z0-9-_]/', '', $originalName);
            
            if (empty($cleanName)) {
                $cleanName = 'channel';
            }
            
            $channelName = $cleanName . '-' . Str::random(8);
            $channelName = substr($channelName, 0, 128);
            
            $options = [
                'type' => "BASIC",
                'latencyMode' => "LOW",
                'tags' => [
                    'environment' => config('app.env'),
                    'created_by' => 'laravel-system'
                ]
            ];
            
            $result = $this->ivsService->createChannel($channelName, $options);

            if (!$result['success']) {
                throw new \Exception('Failed to create IVS channel: ' . ($result['error'] ?? 'Unknown error'));
            }
            
            $channelData = $result['channel'];
            $streamKeyData = $result['streamKey'];

            $ingestEndpoint = '';
            if (isset($channelData['ingestEndpoint'])) {
                $urlParts = parse_url($channelData['ingestEndpoint']);
                $ingestEndpoint = $urlParts['host'] ?? '';
            }
            
            $playbackUrl = '';
            if (isset($channelData['playbackUrl'])) {
                $urlParts = parse_url($channelData['playbackUrl']);
                $playbackUrl = $urlParts['host'] ?? '';
            }
            
            $ivsChannel = Livestream::create([
                'channel_name' => $userId,
                'channel_arn' => $channelData['arn'],
                'ingest_endpoint' => $channelData['ingestEndpoint'],
                'stream_key' => $streamKeyData['value'],
                'stream_key_arn' => $streamKeyData['arn'],
                'playback_url' => $channelData['playbackUrl'],
                'channel_id' => $channelData['id'] ?? Str::random(16),
                'region' => config('ivs.region'),
                'type' => "BASIC",
                'latency_mode' => "LOW",
                'recording_configuration_arn' => null,
                'creator_id' => $userId,
                'tags' => $options['tags'],
                'camera' => $camera ?? "Back",
                'platform' => $platform ?? "Android",
                'country' => $country ?? null,
                'is_active' => true,
                'livestream_end' => 'No',
                'created_at' => time(),
                'updated_at' => time(),
            ]);

            DB::commit();
        }
        else
        {
            LiveStream::where('creator_id', $userId)
            ->update([
                'camera' => $camera ?? "Back",
                'platform' => $platform ?? "Android",
                'updated_at' => time(),
            ]);
        }
        
        $livestreams = Livestream::where('creator_id', $userId)
            ->orderBy('created_at', 'desc')
            ->with(['creator' => function($query) {  // Changed from 'user' to 'creator'
                $query->select('id', 'full_name', 'avatar', 'country_id');
            }])
            ->get()
            ->map(function ($livestream) {
            if ($livestream->creator) {  // Changed from 'user' to 'creator'
                // Initialize country name variable
                $countryName = null;
                
                // Get country name from Region table
                if ($livestream->creator->country_id) {
                    $country = Region::select('title')
                                    ->where('id', $livestream->creator->country_id)
                                    ->where('type', Region::$country)
                                    ->first();
                    
                    if ($country) {
                        $countryName = $country->title;
                    }
                }
                
                // Get country code from Country table
                $countryCode = null;
                if ($countryName) {
                    $countryCode = Country::where('country_name', $countryName)->value('country_code');
                }
                
                // Add the data to livestream object
                $livestream->user_name = $livestream->creator->full_name ?? null;
                $livestream->avatar = !empty($livestream->creator->avatar) ? url($livestream->creator->avatar) : "";
                $livestream->user_country_code = $countryCode;
                
                // Remove the creator object if you don't need it anymore
                unset($livestream->creator);
            }
            return $livestream;
        });

        return response()->json([
            'success' => true,
            'data' => $livestreams
        ]);
    }

    public function delete(Request $request, $id)
    {
        $userId = $this->getUserIdFromToken($request);
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Invalid or missing token.'
            ], 401);
        }

        $livestream = Livestream::where('id', $id)
            ->where('creator_id', $userId)
            ->first();

        $chattoken = IvsChatToken::where('livestream_id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$livestream) {
            return response()->json([
                'success' => false,
                'message' => 'Livestream channel not found or you do not have permission to delete it.'
            ], 404);
        }

        try {
            // In production, you would delete from AWS IVS here:
           try {
                $this->ivsService->deleteChannel($livestream->channel_arn);
            } catch (\Exception $awsError) {
                // Just log the error and continue
                Log::warning('AWS deletion failed (channel might be already deleted): ' . $awsError->getMessage());
            }

            if($chattoken)
            {
                $this->ivsChatService->deleteRoom($chattoken->chat_room_arn);
            }

            IvsChatToken::where('livestream_id', $livestream->id)->delete();
            
            // Delete from database
            $livestream->update([
                'livestream_end' => 'Yes'
            ]);
            //$livestream->delete();

            return response()->json([
                'success' => true,
                'message' => 'Livestream Ended successfully.',
                'data' => [
                    'id' => $id,
                    'deleted' => true,
                    'livestream_end' => 'Yes',
                    'livestreamview_count' => $livestream->livestreamview_count ?? 0   
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete livestream channel.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function livechat(Request $request, $id)
    {
        $userId = $this->getUserIdFromToken($request);

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Invalid or missing token.'
            ], 401);
        }

        $livestream = Livestream::find($id);
        if (!$livestream) {
            return response()->json([
                'success' => false,
                'message' => 'Livestream not found'
            ], 404);
        }
        
        // $roomArn   = 'arn:aws:ivschat:us-east-1:585130011235:room/ygddYITgmm7N';
        // $roomTitle = 'portalschat';

        $existing = IvsChatToken::where('livestream_id', $livestream->id)
            ->latest()
            ->first();

        if ($existing) {
            $arn = $existing->chat_room_arn;
            $title = $existing->chat_room_title;
        }
        else
        {
            $room = $this->ivsChatService->createRoom($livestream->id);
            $arn = $room['arn'];
            $title = $room['title'];
        }

        // Create token (180 mins, full permissions)
        $tokenData = $this->ivsChatService->createChatToken(
            $arn,
            $userId
        );

        $expiresAtTimestamp = strtotime($tokenData['expires_at']);

        $chatToken = IvsChatToken::create([
            'user_id' => $userId,
            'livestream_id' => $id,
            'chat_room_arn' => $arn,
            'chat_room_title' => $title,
            'chat_token' => $tokenData['token'],
            'capabilities' => $tokenData['capabilities'],
            'expires_at' => $expiresAtTimestamp,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $updatedChatTokenCount = IvsChatToken::where('livestream_id', $livestream->id)->count();

        Livestream::where('id', $livestream->id)->update([
            'livestreamview_count' => $updatedChatTokenCount
        ]);
        
        // $livestream->update([
        //     'livestreamview_count' => $updatedChatTokenCount
        // ]);

        return response()->json([
            'success' => true,
            'data' => [
                'livestream_id' => $id,
                'chat_token' => $chatToken->chat_token,
                'room_arn' => $arn,
                'room_title' => $title,
                'capabilities' => $chatToken->capabilities,
                'expires_at' => $chatToken->expires_at,
                'view_count' => $updatedChatTokenCount,
            ]
        ]);
    }

    public function details(Request $request, $id)
    {
        $userId = $this->getUserIdFromToken($request);

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Invalid or missing token.'
            ], 401);
        }

        // Get selected livestream
        $selectedLivestream = Livestream::where('id', $id)
            ->where('livestream_end', 'No')
            ->with(['creator' => function($query) {
                $query->select('id', 'full_name', 'avatar', 'country_id');
            }])
            ->first();

        if (!$selectedLivestream) {
            return response()->json([
                'success' => false,
                'message' => 'Selected livestream not found'
            ], 404);
        }

        $page = $request->has('offset') ? (int) $request->get('offset') : null;
        $perPage = $request->has('limit') ? (int) $request->get('limit') : null;

        $usePagination = !is_null($page) && !is_null($perPage);
        $dbOffset = $usePagination ? ($page * $perPage) : null;

        $comments = DB::table('livestream_comment')
            ->where('livestream_id', $id)
            ->join('users', 'livestream_comment.user_id', '=', 'users.id')
            ->select(
                'livestream_comment.id',
                'livestream_comment.content',
                'livestream_comment.created_at',
                'users.full_name',
                'users.avatar'
            )
            ->orderBy('livestream_comment.created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($comment) {
            return [
                'id' => $comment->id,
                'content' => $comment->content,
                'created_at' => $comment->created_at,
                'user' => [
                    'full_name' => $comment->full_name,
                    'avatar' => !empty($comment->avatar) ? url($comment->avatar) : null
                ]
            ];
        });

        $reviews = DB::table('livestream_review')
            ->where('livestream_id', $id)
            ->join('users', 'livestream_review.user_id', '=', 'users.id')
            ->select(
                'livestream_review.id',
                'livestream_review.user_id',
                'livestream_review.livestream_id',
                'livestream_review.review',
                'livestream_review.rating',
                'livestream_review.created_at',
                'users.full_name',
                'users.avatar'
            )
            ->orderBy('livestream_review.created_at', 'desc')
            ->limit(20) // Adjust as needed
            ->get()
            ->map(function ($review) {
            return [
                'id' => $review->id,
                'user_id' => $review->user_id,
                'livestream_id' => $review->livestream_id,
                'review' => $review->review,
                'rating' => $review->rating,
                'created_at' => $review->created_at,
                'username' => $review->full_name,
                'avatar' => !empty($review->avatar) ? url($review->avatar) : null
            ];
        });

        // Calculate average rating
        $avgRating = DB::table('livestream_review')
            ->where('livestream_id', $id)
            ->avg('rating');

        $userReviewed = DB::table('livestream_review')
            ->where('livestream_id', $id)
            ->where('user_id', $userId)
            ->exists();

        // Check if user liked selected livestream
        $selectedIsLiked = DB::table('livestream_like')
            ->where('livestream_id', $id)
            ->where('user_id', $userId)
            ->exists();

        $selectedIssaved = DB::table('livestream_saved')
            ->where('livestream_id', $id)
            ->where('user_id', $userId)
            ->exists();

        // Get other livestreams with or without pagination
        $otherLivestreamsQuery = Livestream::where('id', '!=', $id)
            ->where('livestream_end', 'No')
            ->orderBy('created_at', 'desc')
            ->with(['creator' => function($query) {
                $query->select('id', 'full_name', 'avatar', 'country_id');
            }]);

        $totalOtherLivestreams = $otherLivestreamsQuery->count();

        if ($usePagination) {
            // Apply pagination
            $otherLivestreams = $otherLivestreamsQuery
                ->offset($dbOffset)
                ->limit($perPage)
                ->get();
        } else {
            // Get all without pagination
            $otherLivestreams = $otherLivestreamsQuery->get();
        }

        // Process selected livestream
        $selectedData = $this->processLivestreamData($selectedLivestream);
        $selectedData['is_liked'] = $selectedIsLiked;
        $selectedData['is_saved'] = $selectedIssaved;
        $selectedData['comments'] = $comments;
        $selectedData['reviews'] = $reviews;
        $selectedData['average_rating'] = round($avgRating, 1);
        $selectedData['user_reviewed'] = $userReviewed;
        $selectedData['is_selected'] = true;
        $selectedData['liked_by_current_user'] = $selectedIsLiked;

        // Process other livestreams
        $otherLivestreamsData = [];
        foreach ($otherLivestreams as $livestream) {
            $livestreamData = $this->processLivestreamData($livestream);
            
            $isLiked = DB::table('livestream_like')
                ->where('livestream_id', $livestream->id)
                ->where('user_id', $userId)
            ->exists();

            $isSaved = DB::table('livestream_saved')
            ->where('livestream_id', $livestream->id)
            ->where('user_id', $userId)
            ->exists(); 
            
            $livestreamData['is_liked'] = $isLiked;
            $livestreamData['is_Saved'] = $isSaved;
            $livestreamData['is_selected'] = false;
            $livestreamData['liked_by_current_user'] = $isLiked;
            
            $otherLivestreamsData[] = $livestreamData;
        }

        // Create single array with selected first
        $resultArray = [$selectedData];
        foreach ($otherLivestreamsData as $livestream) {
            $resultArray[] = $livestream;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'livestreams' => $resultArray,
                'pagination' => [
                    'current_offset' => $page,
                    'per_page' => $perPage,
                    'total_other_streams' => $totalOtherLivestreams,
                    'has_more' => ($dbOffset + $perPage) < $totalOtherLivestreams,
                    'next_offset' => ($dbOffset + $perPage) < $totalOtherLivestreams ? $page + 1 : null
                ]
            ]
        ]);
    }

    private function processLivestreamData($livestream)
    {
        $result = [
            'id' => $livestream->id,
            'channel_name' => $livestream->channel_name,
            'channel_arn' => $livestream->channel_arn,
            'ingest_endpoint' => $livestream->ingest_endpoint,
            'stream_key' => $livestream->stream_key,
            'stream_key_arn' => $livestream->stream_key_arn,
            'playback_url' => $livestream->playback_url,
            'channel_id' => $livestream->channel_id,
            'region' => $livestream->region,
            'type' => $livestream->type,
            'tags' => $livestream->tags,
            'latency_mode' => $livestream->latency_mode,
            'camera' => $livestream->camera,
            'platform' => $livestream->platform,
            'country' => $livestream->country,
            'is_active' => $livestream->is_active,
            'livestream_end' => $livestream->livestream_end,
            'like_count' => $livestream->like_count ?? 0,
            'comments_count' => $livestream->comments_count ?? 0,
            'saved_count' => $livestream->saved_count ?? 0,
            'share_count' => $livestream->share_count ?? 0,
            'gift_count' => $livestream->gift_count ?? 0,
            'report_count' => $livestream->report_count ?? 0,
            'review_count' => $livestream->review_count ?? 0,
            'livestreamview_count' => $livestream->livestreamview_count ?? 0,
            'created_at' => $livestream->created_at,
            'updated_at' => $livestream->updated_at,
            'user_name' => null,
            'avatar' => null,
            'user_country_code' => null,
            'creator_id' => $livestream->creator_id
        ];

        if ($livestream->creator) {
            // Initialize country name variable
            $countryName = null;
            
            // Get country name from Region table
            if ($livestream->creator->country_id) {
                $country = Region::select('title')
                    ->where('id', $livestream->creator->country_id)
                    ->where('type', Region::$country)
                    ->first();
                
                if ($country) {
                    $countryName = $country->title;
                }
            }
            
            // Get country code from Country table
            $countryCode = null;
            if ($countryName) {
                $countryCode = Country::where('country_name', $countryName)->value('country_code');
            }
            
            // Add the data to result
            $result['user_name'] = $livestream->creator->full_name ?? null;
            $result['avatar'] = !empty($livestream->creator->avatar) ? url($livestream->creator->avatar) : "";
            $result['user_country_code'] = $countryCode;
        }

        return $result;
    }

    public function livestreamendcheck(Request $request, $id)
    {
        $livestream = Livestream::where('id', $id)->first();

        if (!$livestream) {
            return response()->json([
                'status' => 'error',
                'message' => 'Livestream not found'
            ], 404);
        }

        $livestreamend = $livestream->livestream_end;

        if($livestreamend == 'Yes')
        {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'livestream_end' => "Yes",
                    'liestreamview_count' => $livestream->livestreamview_count
                ]
            ]);
        }
        else
        {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'livestream_end' => "No"
                ]
            ]);
        }  
    }

    public function livestreamlike(Request $request, $id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $livestream = Livestream::where('id', $id)->first();
        
        if (!$livestream) {
            return response()->json([
                'status' => 'error',
                'message' => 'Livestream not found'
            ], 404);
        }

        $like = DB::table('livestream_like')
            ->where('livestream_id', $livestream->id)
            ->where('user_id', $userid)
            ->exists();

        if ($like) {
            DB::table('livestream_like')
                ->where('livestream_id', $livestream->id)
                ->where('user_id', $userid)
                ->delete();
            
            Livestream::where('id', $id)->decrement('like_count');
            $action = 'unliked';
        } else {
            DB::table('livestream_like')->insert([
                'user_id' => $userid,
                'livestream_id' => $livestream->id
            ]);
            
            Livestream::where('id', $id)->increment('like_count');
            $action = 'liked';
        }

        // Refresh the livestream to get updated like_count
        $livestream = $livestream->fresh();

        return response()->json([
            'status' => 'success',
            'message' => "Livestream {$action} successfully",
            'data' => [
                'liked' => !$like,
                'like_count' => $livestream->like_count ?? 0
            ]
        ]);
    }

    public function livestreamcomment(Request $request, $id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $livestream = Livestream::where('id', $id)->first();

        if (!$livestream) {
            return response()->json([
                'status' => 'error',
                'message' => 'Livestream not found'
            ], 404);
        }

        $now = time();
        $comment = $livestream->comments()->create([
            'user_id' => $userid,
            'livestream_id' => $id,
            'content' => $request->get('content'),
            'created_at' => $now,
            'updated_at' => $now
        ]);

        Livestream::where('id', $id)->increment('comments_count');

        $responseData = [
            "user" => [
                "full_name" => $user->full_name,
                "avatar" => url($user->getAvatar())
            ],
            "created_at" => $now,
            "content" => $request->get('content')
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Comment added successfully',
            'data' => $responseData
        ], 201);
    }

    public function livestreamshare(Request $request,$id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $livestream = Livestream::where('id', $id)->first();

        $now = time();

        $share = $livestream->share()->create([
            'user_id' => $userid,
            // 'livestream_id' => $livestream->id,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        Livestream::where('id', $id)->increment('share_count');
        //$livestream->increment('share_count');
        return response()->json([
            'status' => 'success',
            'message' => 'Livestream Shared successfully',
            'data' => $share
        ], 201);
    }

    public function livestreamgift(Request $request,$id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $livestream = Livestream::where('id', $id)->first();

        $now = time();

        $gift = $livestream->gift()->create([
            'user_id' => $userid,
            // 'livestream_id' => $livestream->id,
            'gift_id' => $request->gift_id, 
            'created_at' => $now,
            'updated_at' => $now
        ]);

        Livestream::where('id', $id)->increment('gift_count');
        //$livestream->increment('gift_count');
        
        return response()->json([
            'status' => 'success',
            'message' => 'Gift Send successfully',
            'data' => $gift
        ], 201);
    }

    public function livestreamsave(Request $request,$id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $livestream = Livestream::where('id', $id)->first();

        $now = time();

        $save = DB::table('livestream_saved')
            ->where('livestream_id', $livestream->id)
            ->where('user_id', $userid)
            ->exists();

        if ($save) {
            DB::table('livestream_saved')
            ->where('livestream_id', $livestream->id)
            ->where('user_id', $userid)
            ->delete();
            
            Livestream::where('id', $id)->decrement('saved_count');
            //$livestream->decrement('like_count');
            $action = 'unsaved';
        } else {
            DB::table('livestream_saved')->insert([
                'user_id' => $userid,
                'livestream_id' => $livestream->id,
                'created_at' => $now,
                'updated_at' => $now
            ]);

            Livestream::where('id', $id)->increment('saved_count');
            //$livestream->increment('like_count');
            $action = 'saved';
        }
        //$livestream->increment('saved_count');
        
        return response()->json([
            'status' => 'success',
            'message' => "Livestream {$action} successfully",
            'data' => [
                'saved' => !$save,
                'saved_count' => $livestream->saved_count
            ]
        ], 201);
    }

    public function livestreamreport(Request $request, $id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $livestream = Livestream::where('id', $id)->first();

        $now = time();

        $report = $livestream->reports()->create([
            'user_id' => $userid,
            // 'livestream_id' => $livestream->id,
            'reason' => $request->reason,
            'description' => $request->description,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        Livestream::where('id', $id)->increment('report_count');

        return response()->json([
            'status' => 'success',
            'message' => 'Livestream reported successfully',
            'data' => $report
        ], 201);
    }

    public function livestreamreview(Request $request, $id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $now = time();

        $livestream = Livestream::where('id', $id)->first();

        $review = $livestream->review()->create([
            'user_id' => $userid,
            // 'livestream_id' => $livestream->id,
            'review' => $request->review,
            'rating' => $request->rating,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $livestream->increment('review_count');

        return response()->json([
            'status' => 'success',
            'message' => 'Review added successfully',
            'data' => [
                'id' => $review->id,
                'user_id' => $review->user_id,
                'livestream_id' => $review->livestream_id,
                'review' => $review->review,
                'rating' => $review->rating,
                'created_at' => $review->created_at, // Convert to timestamp
                'username' => $review->user->full_name,
                'avatar' => $review->user ? url($review->user->getAvatar()) : '',
            ]
            // 'data' => $comment->load('user')
        ], 201);
    }
}