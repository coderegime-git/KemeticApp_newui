<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReelStoreRequest;
use App\Http\Requests\ReelCommentRequest;
use App\Http\Requests\ReelReportRequest;
use App\Jobs\ProcessReelVideo;
use App\Models\Reel;
use App\Models\GiftReel;
use App\Models\ReelCategory;
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
    
    public function index(Request $request)
    {
        // $userId = Auth::id();
        $userId = $this->getUserIdFromToken($request);

        $userEngagement = [];
         
        if ($userId) {
            // Get user's like statistics by category
            $userEngagement = $this->getUserEngagementByCategory($userId);
            
            // Sort by like count DESC (most liked categories first)
            uasort($userEngagement, function($a, $b) {
                return $b['likes'] <=> $a['likes'];
            });
            
            // DEBUG: Check engagement data
            \Log::info('Sorted User Engagement:', $userEngagement);
        }

        $reelQuery = Reel::with(['likes', 'comments.user','review.user'])
            ->where('is_hidden', false)
            ->where(function($query) {
                $query->where('reports_count', '<', 15)
                      ->orWhereNull('reports_count');
            });
            // ->latest()
            // ->paginate(10);

        if ($userId && !empty($userEngagement)) {
            // Get category IDs sorted by like count (highest first)
            $sortedCategoryIds = array_keys($userEngagement);
            
            // Build CASE statement for ordering
            $caseStatements = [];
            foreach ($sortedCategoryIds as $index => $categoryId) {
                $caseStatements[] = "WHEN category_id = {$categoryId} THEN {$index}";
            }
            
            // Add other categories and null at the end
            $caseStatements[] = "WHEN category_id IS NOT NULL THEN " . count($sortedCategoryIds);
            $caseStatements[] = "WHEN category_id IS NULL THEN " . (count($sortedCategoryIds) + 1);
            
            $caseSql = "CASE " . implode(' ', $caseStatements) . " END";
            
            // Apply ordering
            $reelQuery->orderByRaw($caseSql)
                    ->orderBy('created_at', 'desc');
            
            // Apply different logic based on highest engagement
            $highestEngagement = reset($userEngagement); // Get first item (highest likes)
            $highestLikes = $highestEngagement['likes'];
            
            if ($highestLikes >= 9) {
                // Deep Interest: Show only from top categories
                $topCategories = array_slice($sortedCategoryIds, 0, 3); // Top 3 categories
                $reelQuery->whereIn('category_id', $topCategories);
                
            } elseif ($highestLikes >= 6) {
                // High Engagement: Show from all engaged categories
                $reelQuery->whereIn('category_id', $sortedCategoryIds);
                
            } elseif ($highestLikes >= 3) {
                // Medium Engagement: Inject more from engaged categories
                // Already handled by CASE ordering
            }
            // Low engagement (1-2 likes): Already handled by CASE ordering
        } else {
            // Default ordering for non-logged in users
            $reelQuery->latest();
        }
        
        // Paginate results
        $reels = $reelQuery->paginate(10);

        // Convert reels to array and add likes/comments arrays
        $pagination = $reels->toArray();
        $reelModels = $reels->items();
        $reelsArr = [];
        foreach ($reelModels as $reel) {
            $reelData = $reel->toArray();

            $isLiked = $reel->likes->contains('user_id', $userId);
             
            $isSaved = $reel->savedreel->contains('user_id', $userId);
            $username = $reel->user ? $reel->user->full_name : '';
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
                    'avatar' => $comment->user ? url($comment->user->getAvatar()) : '',
                ];
            }

            $reviewsArr = [];
            foreach ($reel->review as $reviews) {
                $reviewsArr[] = [
                    'id' => $reviews->id,
                    'user_id' => $reviews->user_id,
                    'reel_id' => $reviews->reel_id,
                    'review' => $reviews->review,
                    'rating' => $reviews->rating,
                    'created_at' => $reviews->created_at,
                    'username' => $reviews->user ? $reviews->user->full_name : '',
                    'avatar' => $reviews->user ? url($reviews->user->getAvatar()) : '',
                ];
            }

            $reelData['username'] = $username;
            $reelData['is_liked'] = $isLiked;
            $reelData['is_saved'] = $isSaved;
            $reelData['likes'] = $likesArr;
            $reelData['comments'] = $commentsArr;
            $reelData['reviews'] = $reviewsArr;
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

    public function details(Request $request, $id)
    {
        $userId = $this->getUserIdFromToken($request);

        $userEngagement = [];

        if ($userId) {
            $userEngagement = $this->getUserEngagementByCategory($userId);
            
            // Sort by like count DESC (most liked categories first)
            uasort($userEngagement, function($a, $b) {
                return $b['likes'] <=> $a['likes'];
            });
        }

        $selectedReel = Reel::with(['likes', 'comments.user', 'review.user', 'savedreel', 'user'])
            ->where('id', $id)
            ->where('is_hidden', false)
        ->where(function ($query) {
            $query->where('reports_count', '<', 15)
                ->orWhereNull('reports_count');
        })
        ->firstOrFail();

        // Other reels paginate
        $reelQuery = Reel::with(['likes', 'comments.user', 'review.user', 'savedreel', 'user'])
            ->where('id', '!=', $id)
            ->where('is_hidden', false)
            ->where(function ($query) {
                $query->where('reports_count', '<', 15)
                    ->orWhereNull('reports_count');
        });
            // ->latest()
            // ->paginate(10);
        if ($userId && !empty($userEngagement)) {
            // Get sorted category IDs (highest likes first)
            $sortedCategoryIds = array_keys($userEngagement);
            
            // Get selected reel's category
            $selectedCategoryId = $selectedReel->category_id;
            
            // Check if selected category is in user's engaged categories
            if (in_array($selectedCategoryId, $sortedCategoryIds)) {
                // Move selected category to the front
                $selectedIndex = array_search($selectedCategoryId, $sortedCategoryIds);
                unset($sortedCategoryIds[$selectedIndex]);
                array_unshift($sortedCategoryIds, $selectedCategoryId);
                $sortedCategoryIds = array_values($sortedCategoryIds);
            } else {
                // Add selected category at the beginning
                array_unshift($sortedCategoryIds, $selectedCategoryId);
            }
            
            // Build CASE statement for ordering
            $caseStatements = [];
            foreach ($sortedCategoryIds as $index => $categoryId) {
                $caseStatements[] = "WHEN category_id = {$categoryId} THEN {$index}";
            }
            
            $caseStatements[] = "WHEN category_id IS NOT NULL THEN " . count($sortedCategoryIds);
            $caseStatements[] = "WHEN category_id IS NULL THEN " . (count($sortedCategoryIds) + 1);
            
            $caseSql = "CASE " . implode(' ', $caseStatements) . " END";
            
            $reelQuery->orderByRaw($caseSql)
                    ->orderBy('created_at', 'desc');
        } else {
            $reelQuery->latest();
        }

        $reels = $reelQuery->paginate(10);    

        $pagination = $reels->toArray();
        $reelsArr = [];

        $buildReelData = function ($reel, $isSelected = false) use ($userId, $userEngagement) {
            return [
                ...$reel->toArray(),
                'username'    => $reel->user->full_name ?? '',
                'is_liked'    => $reel->likes->contains('user_id', $userId),
                'is_saved'    => $reel->savedreel->contains('user_id', $userId),
                'is_selected' => $isSelected,

                'likes' => $reel->likes->map(fn ($like) => [
                    'id' => $like->id,
                    'user_id' => $like->user_id,
                    'reel_id' => $like->reel_id,
                    'created_at' => $like->created_at,
                ]),

                'comments' => $reel->comments->map(fn ($comment) => [
                    'id' => $comment->id,
                    'user_id' => $comment->user_id,
                    'reel_id' => $comment->reel_id,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at,
                    'username' => $comment->user?->full_name ?? '',
                    'avatar' => $comment->user ? url($comment->user->getAvatar()) : '',
                ]),

                'reviews' => $reel->review->map(fn ($review) => [
                    'id' => $review->id,
                    'user_id' => $review->user_id,
                    'reel_id' => $review->reel_id,
                    'review' => $review->review,
                    'rating' => $review->rating,
                    'created_at' => $review->created_at,
                    'username' => $review->user?->full_name ?? '',
                    'avatar' => $review->user ? url($review->user->getAvatar()) : '',
                ]),
            ];
            
        };

        // Page 1 → add selected reel on top
        if ($reels->currentPage() === 1) {
            $reelsArr[] = $buildReelData($selectedReel, true);
        }

        foreach ($reels->items() as $reel) {
            $reelsArr[] = $buildReelData($reel, false);
        }

        // Replace data with reels
        $pagination['reels'] = $reelsArr;
        unset($pagination['data']);

        return response()->json([
            'status' => 'success',
            'data' => $pagination
        ]);
    }

    private function getUserEngagementByCategory($userId)
    {
        return DB::table('reel_likes')
            ->join('reels', 'reel_likes.reel_id', '=', 'reels.id')
            ->leftJoin('reel_categories', 'reels.category_id', '=', 'reel_categories.id')
            ->where('reel_likes.user_id', $userId)
            ->whereNotNull('reels.category_id')
            ->select(
                'reels.category_id',
                DB::raw('COUNT(DISTINCT reel_likes.id) as likes'),
                DB::raw('MAX(reel_likes.created_at) as last_interaction')
            )
            ->groupBy('reels.category_id')
            ->orderByDesc('likes') // Already order by likes DESC
            ->get()
            ->keyBy('category_id')
            ->map(function($item) {
                // Get category name from ReelCategory model
                $category = ReelCategory::find($item->category_id);
                
                return [
                    'category_id' => $item->category_id,
                    'category_name' => $category ? $category->title : 'Unknown',
                    'likes' => (int)$item->likes,
                    'last_interaction' => $item->last_interaction,
                    'engagement_level' => $this->determineEngagementLevel($item->likes)
                ];
            })
            ->toArray();
    }

    /**
     * Determine engagement level based on like count
     */
    private function determineEngagementLevel($likeCount)
    {
        if ($likeCount >= 9) {
            return 'deep_interest'; // 9+ likes
        } elseif ($likeCount >= 6) {
            return 'high_engagement'; // 6+ likes
        } elseif ($likeCount >= 3) {
            return 'medium_engagement'; // 3-5 likes
        } else {
            return 'low_engagement'; // 1-2 likes
        }
    }

    /**
     * Get engagement strategy based on highest like count
     */
    private function getEngagementStrategy($userEngagement)
    {
        if (empty($userEngagement)) {
            return 'default_popular';
        }
        
        // Get the category with highest likes
        $highestEngagement = reset($userEngagement);
        $highestLikes = $highestEngagement['likes'];
        
        if ($highestLikes >= 9) {
            return 'deep_interest';
        } elseif ($highestLikes >= 6) {
            return 'high_engagement';
        } elseif ($highestLikes >= 3) {
            return 'medium_engagement';
        } else {
            return 'low_engagement';
        }
    }
    
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
            'category_id' => $request->category_id ?? 1,
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
    
    public function categories(){

        $categories=ReelCategory::all()->map(function($category){
            return $category->details ;
        });

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'),$categories);
    }
    
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

    public function giftreel(Request $request, Reel $reel)
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
                'username' => $comment->user->full_name,
                'avatar' => $comment->user ? url($comment->user->getAvatar()) : '',
            ]
            // 'data' => $comment->load('user')
        ], 201);
    }

    public function review(Request $request, Reel $reel)
    {
        // dd('here');
        if ($reel->is_hidden) {
            abort(404);
        }

        $now = time();
        $review = $reel->review()->create([
            'user_id' => Auth::id(),
            'reel_id' => $reel->id,
            'review' => $request->review,
            'rating' => $request->rating,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $reel->increment('review_count');

        return response()->json([
            'status' => 'success',
            'message' => 'Review added successfully',
            'data' => [
                'id' => $review->id,
                'user_id' => $review->user_id,
                'reel_id' => $review->reel_id,
                'review' => $review->review,
                'rating' => $review->rating,
                'created_at' => $review->created_at, // Convert to timestamp
                'username' => $review->user->full_name,
                'avatar' => $review->user ? url($review->user->getAvatar()) : '',
            ]
            // 'data' => $comment->load('user')
        ], 201);
    }

    /**
     * Report a reel
     */
    public function report(ReelReportRequest $request, Reel $reel)
    {
        // if ($reel->isReportedBy(Auth::user())) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'You have already reported this reel'
        //     ], 400);
        // }

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
