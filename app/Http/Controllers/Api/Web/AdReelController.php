<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Models\AdReel;
use App\Models\Plan;
use App\Models\FmdPurchase;
use App\Models\FmdLike;
use App\Models\FmdShare;
use App\Models\FmdSaved;
use App\Models\FmdGift;
use App\Models\FmdComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class AdReelController extends Controller
{
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

    public function adreels(Request $request)
    {
        $userid = $this->getUserIdFromToken($request);

        $tab = $request->get('tab'); // trending, newest, foryou, sponsored
        $plan = $request->plan_code;
        $query = AdReel::with(['user', 'likes', 'comments.user']);

        if ($tab === 'sponsored') {
            $query->where('plan_code', $plan)
                  ->orderBy('created_at', 'desc');
        } else {
            
            if ($tab === 'trending') {
                $query->orderBy('trending_score', 'desc');
            } elseif ($tab === 'newest') {
                $query->orderBy('created_at', 'desc');
            }elseif ($tab === 'foryou') {
                $query->orderBy('stars', 'desc')
                ->orderBy('trending_score', 'desc');
            }
            else {
                $query->orderBy('created_at', 'desc');
            }
        }

        $reels = $query->paginate(10);
        
        // Convert reels to array and add likes/comments arrays
        $pagination = $reels->toArray();
        $reelModels = $reels->items();
        $reelsArr = [];
        
        foreach ($reelModels as $reel) {
            $reelData = $reel->toArray();

            $isLiked = $reel->isLikedBy($userid);
            $isSaved = $reel->isSavedBy($userid);
            
            // Likes array
            $likesArr = [];
            foreach ($reel->likes as $like) {
                $likesArr[] = [
                    'id' => $like->id,
                    'user_id' => $like->user_id,
                    'fmd_id' => $like->fmd_id,
                    'created_at' => $like->created_at,
                ];
            }
            
            // Comments array
            $commentsArr = [];
            foreach ($reel->comments as $comment) {
                $commentsArr[] = [
                    'id' => $comment->id,
                    'user_id' => $comment->user_id,
                    'fmd_id' => $comment->fmd_id,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at,
                    'username' => $comment->user ? $comment->user->full_name : '',
                ];
            }
            
            $reelData['likes'] = $likesArr;
            $reelData['comments'] = $commentsArr;
            $reelData['is_liked'] = $isLiked;
            $reelData['is_saved'] = $isSaved;
            $reelData['media_image'] = url('store/reels/videos/' . $reel->media_image);
            
            $reelsArr[] = $reelData;
        }
        
        // Replace 'data' with 'reels' in pagination array
        $pagination['reels'] = $reelsArr;
        unset($pagination['data']);
        
        return response()->json([
            'status' => 'success',
            'data' => $pagination
        ]);
    }

    public function store(Request $request)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        ini_set('upload_max_filesize', '250M');
        ini_set('post_max_size', '250M');
        

        $video = $request->file('video');
        $filename = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();

        $videoPath = public_path('store/reels/videos');
        if (!file_exists($videoPath)) {
            mkdir($videoPath, 0777, true);
        }
        $video->move($videoPath, $filename);
        
        $now = time();
        $startsAt = $request->starts_at ?? $now;
        $expiresAt = $request->expires_at ?? ($now + 86400); // Default: 1 day
        
        $reel = AdReel::create([
            'user_id' => $userid,
            'title' => $request->title,
            'media_image' => $filename,
            'stars' => 0,
            'reviews' => 0,
            'product_id' => $request->product_id,
            'starts_at' => $startsAt,
            'expires_at' => $expiresAt,
            'likes_count' => 0,
            'comments_count' => 0,
            'shares_count' => 0,
            'saved_count' => 0,
            'gifts_count' => 0,
            'views_count' => 0,
            'trending_score' => 0,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'uploaded successfully',
            'data' => $reel
        ], 201);
    }

    public function toggleLike(Request $request, $id)
    {
        $user = auth('api')->user();
        $userId = $user->id;
        
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $reel = AdReel::findOrFail($id);
        
        $like = FmdLike::where('fmd_id', $id)
            ->where('user_id', $userId)
            ->first();

        if ($like) {
            $like->delete();
            $reel->decrement('likes_count');
            $action = 'unliked';
        } else {
            $now = time();
            FmdLike::create([
                'user_id' => $userId,
                'fmd_id' => $id,
                'created_at' => $now,
                'updated_at' => $now
            ]);
            $reel->increment('likes_count');
            $action = 'liked';
        }

        

        return response()->json([
            'status' => 'success',
            'message' => "FMD {$action} successfully",
            'data' => [
                'liked' => !$like,
                'likes_count' => $reel->likes_count
            ]
        ]);
    }

    public function share(Request $request, $id)
    {
        $user = auth('api')->user();
        $userId = $user->id;
        
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $reel = AdReel::findOrFail($id);
        $now = time();

        $share = FmdShare::create([
            'user_id' => $userId,
            'fmd_id' => $id,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $reel->increment('shares_count');

        return response()->json([
            'status' => 'success',
            'message' => 'FMD Shared successfully',
            'data' => $share
        ], 201);
    }

    public function save(Request $request, $id)
    {
        $user = auth('api')->user();
        $userId = $user->id;
        
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $reel = AdReel::findOrFail($id);
        $now = time();

        $save = FmdSaved::where('fmd_id', $id)
            ->where('user_id', $userId)
            ->first();

        if ($save) {
            $save->delete();
            $reel->decrement('saved_count');
            $action = 'unsaved';
        } else {
            FmdSaved::create([
                'user_id' => $userId,
                'fmd_id' => $id,
                'created_at' => $now,
                'updated_at' => $now
            ]);
            $reel->increment('saved_count');
            $action = 'saved';
        }

        return response()->json([
            'status' => 'success',
            'message' => "FMD {$action} successfully",
            'data' => [
                'saved' => !$save,
                'saved_count' => $reel->saved_count
            ]
        ], 201);
    }

    public function gift(Request $request, $id)
    {
        $user = auth('api')->user();
        $userId = $user->id;
        
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $reel = AdReel::findOrFail($id);
        $now = time();

        $request->validate([
            'gift_id' => 'required|integer'
        ]);

        $gift = FmdGift::create([
            'user_id' => $userId,
            'fmd_id' => $id,
            'gift_id' => $request->gift_id,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $reel->increment('gifts_count');

        return response()->json([
            'status' => 'success',
            'message' => 'Gift sent successfully',
            'data' => $gift
        ], 201);
    }

    public function comment(Request $request, $id)
    {
        $user = auth('api')->user();
        $userId = $user->id;
        
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $reel = AdReel::findOrFail($id);
        $now = time();

        $request->validate([
            'content' => 'required|string|max:500'
        ]);

        $comment = FmdComment::create([
            'user_id' => $userId,
            'fmd_id' => $id,
            'content' => $request->content,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $reel->increment('comments_count');

        return response()->json([
            'status' => 'success',
            'message' => 'Comment added successfully',
            'data' => [
                'id' => $comment->id,
                'user_id' => $comment->user_id,
                'fmd_id' => $comment->fmd_id,
                'content' => $comment->content,
                'created_at' => $comment->created_at,
                'username' => $comment->user ? $comment->user->full_name : ''
            ]
        ], 201);
    }

    public function view(Request $request, $id)
    {
        $user = auth('api')->user();
        $userId = $user->id;
        
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $reel = AdReel::findOrFail($id);
        $reel->increment('views_count');

        return response()->json([
            'status' => 'success',
            'data' => [
                'views_count' => $reel->views_count
            ]
        ]);
    }

    public function deleteComment(Request $request, $commentId)
    {
        $user = auth('api')->user();
        $userId = $user->id;

        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $comment = FmdComment::findOrFail($commentId);
        
        // Check if user owns the comment or is admin
        if ($comment->user_id != $userId) {
            return response()->json(['error' => 'Unauthorized to delete this comment'], 403);
        }

        $now = time();
        $comment->update([
            'deleted_at' => $now,
            'updated_at' => $now
        ]);

        // Decrement comment count on the reel
        $reel = AdReel::find($comment->fmd_id);
        if ($reel) {
            $reel->decrement('comments_count');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Comment deleted successfully'
        ]);
    }

    public function plans()
    {
        return response()->json([
            'status' => true,
            'plans' => Plan::all()
        ]);
    }

    public function purchase(Request $request)
    {
        $user = auth('api')->user();
        $userid = $user->id;
        
        $request->validate([
            'plan_code' => 'required'
        ]);

        $plan = Plan::where('code', $request->plan_code)->firstOrFail();

        $currentTimestamp = time();

        $purchase = FmdPurchase::create([
            'user_id' => $userid,
            'reel_id' => $request->reel_id,
            'plan_code' => $plan->code,
            'amount' => $plan->price,
            'starts_at' => $currentTimestamp,
            'expires_at' => $plan->duration_days
                ? $currentTimestamp + ($plan->duration_days * 86400) // days to seconds
                : null,
            'created_at' => $currentTimestamp,
            'updated_at' => $currentTimestamp
        ]);

        // Update reel
        AdReel::where('id', $request->reel_id)
            ->update([
                'plan_code' => $plan->code,
                'created_at' => $currentTimestamp,
                'expires_at' =>  $plan->duration_days
                ? $currentTimestamp + ($plan->duration_days * 86400) // days to seconds
                : null,
            ]);

        return response()->json([
            'status' => true,
            'plan' => $plan->code,
            'visibleInSponsoredOnly' => $plan->code === 'sponsored_only',
            'visibleInDiscoveryTabs' => $plan->is_membership
        ]);
    }
}

