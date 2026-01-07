<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReelStoreRequest;
use App\Http\Requests\ReelCommentRequest;
use App\Http\Requests\ReelReportRequest;
use App\Jobs\ProcessReelVideo;
use App\Models\Reel;
use App\Models\GiftReel;
use App\Http\Resources\ReelResourceCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReelController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    //     $this->middleware(function ($request, $next) {
    //         if (!$request->expectsJson()) {
    //             return response()->json(['error' => 'Unauthorized'], 401);
    //         }
    //         return $next($request);
    //     });
    // }

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


    /**
     * Get paginated reels feed
     */
    public function index(Request $request)
    {
        // $userId = Auth::id();
        $userId = $this->getUserIdFromToken($request);

        $reels = Reel::with(['likes', 'comments.user'])
            ->where('is_hidden', false)
            ->where(function($query) {
                $query->where('reports_count', '<', 15)
                      ->orWhereNull('reports_count');
            })
            ->latest()
            ->paginate(10);

        // Convert reels to array and add likes/comments arrays
        $pagination = $reels->toArray();
        $reelModels = $reels->items();
        $reelsArr = [];
        foreach ($reelModels as $reel) {
            $reelData = $reel->toArray();

            $isLiked = $reel->likes->contains('user_id', $userId);
             
            $isSaved = $reel->savedreel->contains('user_id', $userId);
            // Likes array
            $likesArr = [];
            foreach ($reel->likes as $like) {
                $likesArr[] = [
                    'id' => $like->id,
                    'user_id' => $like->user_id,
                    'reel_id' => $like->reel_id,
                    'created_at' => $like->created_at,
                ];
            }
            // Comments array
            $commentsArr = [];
            foreach ($reel->comments as $comment) {
                $commentsArr[] = [
                    'id' => $comment->id,
                    'user_id' => $comment->user_id,
                    'reel_id' => $comment->reel_id,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at,
                    'username' => $comment->user ? $comment->user->full_name : '',
                ];
            }
            $reelData['likes'] = $likesArr;
            $reelData['comments'] = $commentsArr;
            $reelData['is_liked'] = $isLiked;
            $reelData['is_saved'] = $isSaved;
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

    /**
     * Upload a new reel
     */
    public function store(ReelStoreRequest $request)
    {
        // Debug line to check authentication
        if (!Auth::check()) {
            return response()->json(['error' => 'Not authenticated', 'user' => Auth::user()], 401);
        }
        
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
        $reel = Reel::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'caption' => $request->caption,
            'video_path' => $filename,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Queue video processing job
        ProcessReelVideo::dispatch($reel);

        return response()->json([
            'status' => 'success',
            'message' => 'Reel uploaded successfully and is being processed',
            'data' => $reel
        ], 201);
    }

    /**
     * Get a specific reel
     */
    public function show(Reel $reel)
    {
        if ($reel->is_hidden && !Auth::user()->isAdmin()) {
            abort(404);
        }

        return response()->json(data: [
            'status' => 'success',
            'data' => $reel->load(['user', 'comments.user'])
        ]);
    }

    /**
     * Toggle like on a reel
     */
    public function toggleLike(Reel $reel)
    {
        if ($reel->is_hidden) {
            abort(404);
        }

        $like = DB::table('reel_likes')
            ->where('reel_id', $reel->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($like) {
            DB::table('reel_likes')
                ->where('reel_id', $reel->id)
                ->where('user_id', Auth::id())
                ->delete();
            $reel->decrement('likes_count');
            $action = 'unliked';
        } else {
            DB::table('reel_likes')->insert([
                'user_id' => Auth::id(),
                'reel_id' => $reel->id
            ]);
            $reel->increment('likes_count');
            $action = 'liked';
        }

        return response()->json([
            'status' => 'success',
            'message' => "Reel {$action} successfully",
            'data' => [
                'liked' => !$like,
                'likes_count' => $reel->likes_count
            ]
        ]);
    }

    public function reelgift()
    {
        //print_r("hello");exit;
        $reelgift = GiftReel::get()
            ->map(function ($reelgift) {
            $reelgift->thumbnail = url($reelgift->thumbnail);
            return $reelgift;
        });

        // Response
        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $reelgift);
    }

    public function sharereel(Reel $reel)
    {
        if ($reel->is_hidden) {
            abort(404);
        }

        $now = time();

        $share = $reel->share()->create([
            'user_id' => Auth::id(),
            'reel_id' => $reel->id,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $reel->increment('share_count');
         return response()->json([
            'status' => 'success',
            'message' => 'Reel Shared successfully',
            'data' => $share
        ], 201);
    }

    public function savereel(Reel $reel)
    {
        if ($reel->is_hidden) {
            abort(404);
        }

        $now = time();

         
        $save = DB::table('reel_saved')
            ->where('reel_id', $reel->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($save) {
             DB::table('reel_saved')
            ->where('reel_id', $reel->id)
            ->where('user_id', Auth::id())
            ->delete(); 

            Reel::where('id', $reel->id)->decrement('saved_count');
            $action = 'unsaved';
        } else {
            DB::table('reel_saved')->insert([
                'user_id' => Auth::id(),
                'reel_id' => $reel->id,
                'created_at' => $now,
                'updated_at' => $now
            ]);
            
            Reel::where('id', $reel->id)->increment('saved_count');
            $action = 'saved';
        }
        
        return response()->json([
            'status' => 'success',
            'message' => "Reel {$action} successfully",
            'data' => [
                'saved' => !$save,
                'saved_count' => $reel->saved_count
            ]
        ], 201);
    }

    public function giftreel(ReelCommentRequest $request, Reel $reel)
    {
        if ($reel->is_hidden) {
            abort(404);
        }

        $now = time();

        $gift = $reel->gifts()->create([
            'user_id' => Auth::id(),
            'reel_id' => $reel->id,
            'gift_id' => $request->gift_id, 
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $reel->increment('gift_count');
         return response()->json([
            'status' => 'success',
            'message' => 'Gift Send successfully',
            'data' => $gift
        ], 201);
    }

    /**
     * Add comment to a reel
     */
    public function comment(ReelCommentRequest $request, Reel $reel)
    {
        if ($reel->is_hidden) {
            abort(404);
        }

        $now = time();
        $comment = $reel->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->get('content'),
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
                'reel_id' => $comment->reel_id,
                'content' => $comment->content,
                'created_at' => $comment->created_at, // Convert to timestamp
                'username' => $comment->user->full_name
            ]
            // 'data' => $comment->load('user')
        ], 201);
    }

    /**
     * Report a reel
     */
    public function report(ReelReportRequest $request, Reel $reel)
    {
        if ($reel->isReportedBy(Auth::user())) {
            return response()->json([
                'status' => 'error',
                'message' => 'You have already reported this reel'
            ], 400);
        }

        $now = time();
        $report = $reel->reports()->create([
            'user_id' => Auth::id(),
            'reason' => $request->reason,
            'description' => $request->description,
            'created_at' => $now
        ]);

        $reel->increment('reports_count');
        $reel->checkAndUpdateHiddenStatus();

        return response()->json([
            'status' => 'success',
            'message' => 'Reel reported successfully',
            'data' => $report
        ], 201);
    }

    /**
     * Record a view for the reel
     */
    public function view(Reel $reel)
    {
        if ($reel->is_hidden) {
            abort(404);
        }

        if (!$reel->isViewedBy(Auth::user())) {
            $reel->views()->create(['user_id' => Auth::id()]);
            $reel->increment('views_count');
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'views_count' => $reel->views_count
            ]
        ]);
    }

    /**
     * Delete a reel
     */
    public function destroy(Reel $reel)
    {
        if (Auth::id() !== $reel->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $reel->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Reel deleted successfully'
        ]);
    }
}
