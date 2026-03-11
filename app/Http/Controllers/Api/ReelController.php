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
use App\Models\ReelComment;
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

    private function getRandomSeed(Request $request, $userId = null, $forceNew = false)
    {
         $today = date('Y-m-d');
    
        if ($userId) {
            // User-specific seed based on user ID and date
            $seedString = $userId . '_' . $today;
        } else {
            // IP-based seed
            $ip = $request->ip() ?? '0.0.0.0';
            $seedString = $ip . '_' . $today;
        }
        
        // If force new, modify the seed string
        if ($forceNew) {
            $seedString .= '_new_' . time();
        }
        
        // Convert string to a consistent number between 1 and 10000
        $seed = crc32($seedString);
        $seed = abs($seed) % 10000 + 1;
        
        return $seed;
    }
    
    public function index(Request $request)
    {
        // $userId = Auth::id();
        $userId = $this->getUserIdFromToken($request);
    
         $randomSeed = $this->getRandomSeed($request, $userId);
        
        // Get ALL viewed reel IDs for this user (permanent tracking)
        $viewedReelIds = [];
        if ($userId) {
            $viewedReelIds = DB::table('reel_views')
                ->where('user_id', $userId)
                ->pluck('reel_id')
                ->toArray();
        }

        // Base query - exclude hidden and reported reels
        $reelQuery = Reel::with(['likes', 'comments.user', 'comments.replies.user', 'review.user', 'savedreel', 'user'])
            ->where('is_hidden', false)
            ->where(function($query) {
                $query->where('reports_count', '<', 15)
                    ->orWhereNull('reports_count');
            });

        // Check if user has viewed all reels
        $totalReelsCount = (clone $reelQuery)->count();
        $viewedCount = count($viewedReelIds);
        
        // If all reels viewed, reset viewed tracking for fresh start
        if ($viewedCount >= $totalReelsCount && $totalReelsCount > 0) {
            if ($userId) {
                // Delete all view records for this user to start fresh
                DB::table('reel_views')
                    ->where('user_id', $userId)
                    ->delete();
                $viewedReelIds = [];
                
                // Generate new random seed for fresh start
                $randomSeed = $this->getRandomSeed($request, $userId, true);
            }
        }

        // Apply viewed filter
        if (!empty($viewedReelIds)) {
            $reelQuery->whereNotIn('id', $viewedReelIds);
        }

        if ($userId) {
            // ========== LOGGED-IN USER LOGIC ==========
            // Get user's liked categories with counts
            $userLikedCategories = DB::table('reel_likes')
                ->join('reels', 'reel_likes.reel_id', '=', 'reels.id')
                ->where('reel_likes.user_id', $userId)
                ->whereNotNull('reels.category_id')
                ->select('reels.category_id', DB::raw('COUNT(*) as like_count'))
                ->groupBy('reels.category_id')
                ->orderByDesc('like_count')
                ->get();

            if ($userLikedCategories->isNotEmpty()) {
                // Build CASE statement for category ordering
                $caseStatements = [];
                foreach ($userLikedCategories as $index => $category) {
                    $caseStatements[] = "WHEN category_id = {$category->category_id} THEN {$index}";
                }
                $caseStatements[] = "ELSE " . count($userLikedCategories);
                
                // Apply ordering: category preference → unviewed first → most liked → consistent random
                $reels = $reelQuery
                    ->select('reels.*')
                    ->orderByRaw("CASE " . implode(' ', $caseStatements) . " END")
                    ->orderBy('views_count', 'asc')  // Unviewed (0) first
                    ->orderBy('likes_count', 'desc') // Most liked first
                    // ->inRandomOrder() 
                    ->orderByRaw("RAND($randomSeed)") // Consistent random shuffle
                    ->paginate(10);
            } else {
                // No liked categories - show unviewed first, then most liked
                $reels = $reelQuery
                    ->select('reels.*')
                    ->orderBy('views_count', 'asc')  // Unviewed first
                    ->orderBy('likes_count', 'desc') // Most liked first
                    ->inRandomOrder() 
                    ->orderByRaw("RAND($randomSeed)") // Consistent random shuffle
                    ->paginate(10);
            }
        } else {
            // ========== NON-LOGGED-IN USER LOGIC ==========
            // Show unviewed first (by views_count=0), then most liked, with shuffle
            $reels = $reelQuery
                ->select('reels.*')
                ->orderBy('views_count', 'asc')  // Unviewed (0) first
                ->orderBy('likes_count', 'desc') // Most liked first
                ->inRandomOrder() 
                ->orderByRaw("RAND($randomSeed)") // Consistent random shuffle
                ->paginate(10);
        }

        // Format the response
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

                $formattedReplies = [];
                foreach ($comment->replies as $reply) {
                    $formattedReplies[] = [
                        'id'         => $reply->id,
                        'content'    => $reply->content,
                        'created_at' => $reply->created_at,
                        'user'       => [
                            'full_name' => $reply->user?->full_name ?? '',
                            'avatar'    => $reply->user ? url($reply->user->getAvatar()) : '',
                        ],
                    ];
                }
                $commentsArr[] = [
                    'id' => $comment->id,
                    'user_id' => $comment->user_id,
                    'reel_id' => $comment->reel_id,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at,
                    'username' => $comment->user ? $comment->user->full_name : '',
                    'avatar' => $comment->user ? url($comment->user->getAvatar()) : '',
                    'replies'    => $formattedReplies,
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
    
        // Get random seed for consistent pagination
        $randomSeed = $this->getRandomSeed($request, $userId);
        
        // Get ALL viewed reel IDs for this user (excluding current reel)
        $viewedReelIds = [];
        if ($userId) {
            $viewedReelIds = DB::table('reel_views')
                ->where('user_id', $userId)
                ->where('reel_id', '!=', $id)
                ->pluck('reel_id')
                ->toArray();
        }

        // Get the selected reel
        $selectedReel = Reel::with(['likes', 'comments.user', 'comments.replies.user', 'review.user', 'savedreel', 'user'])
            ->where('id', $id)
            ->where('is_hidden', false)
            ->where(function($query) {
                $query->where('reports_count', '<', 15)
                    ->orWhereNull('reports_count');
            })
            ->firstOrFail();

        // Track selected reel as viewed
        if ($userId) {
            $alreadyViewed = DB::table('reel_views')
                ->where('user_id', $userId)
                ->where('reel_id', $selectedReel->id)
                ->exists();
                
            if (!$alreadyViewed) {
                DB::table('reel_views')->insert([
                    'user_id' => $userId,
                    'reel_id' => $selectedReel->id,
                    'created_at' => now()->timestamp
                ]);
            }
        }

        // Build query for other reels
        $reelQuery = Reel::with(['likes', 'comments.user','comments.replies.user', 'review.user', 'savedreel', 'user'])
            ->where('id', '!=', $id)
            ->where('is_hidden', false)
            ->where(function($query) {
                $query->where('reports_count', '<', 15)
                    ->orWhereNull('reports_count');
            });

        // Check if user has viewed all other reels
        $totalOtherReelsCount = (clone $reelQuery)->count();
        $viewedOtherCount = count($viewedReelIds);
        
        // If all other reels viewed, reset viewed tracking for fresh start
        if ($viewedOtherCount >= $totalOtherReelsCount && $totalOtherReelsCount > 0) {
            if ($userId) {
                // Delete all view records for this user except current reel
                DB::table('reel_views')
                    ->where('user_id', $userId)
                    ->where('reel_id', '!=', $id)
                    ->delete();
                $viewedReelIds = [];
                
                // Generate new random seed for fresh start
                $randomSeed = $this->getRandomSeed($request, $userId, true);
            }
        }

        // Apply viewed filter
        if (!empty($viewedReelIds)) {
            $reelQuery->whereNotIn('id', $viewedReelIds);
        }

        if ($userId) {
            // Get user's liked categories
            $userLikedCategories = DB::table('reel_likes')
                ->join('reels', 'reel_likes.reel_id', '=', 'reels.id')
                ->where('reel_likes.user_id', $userId)
                ->whereNotNull('reels.category_id')
                ->select('reels.category_id', DB::raw('COUNT(*) as like_count'))
                ->groupBy('reels.category_id')
                ->orderByDesc('like_count')
                ->get();

            if ($userLikedCategories->isNotEmpty()) {
                // Prioritize selected reel's category
                $selectedCategoryId = $selectedReel->category_id;
                
                $caseStatements = [];
                $index = 0;
                
                // Put selected reel's category first
                if ($selectedCategoryId) {
                    $caseStatements[] = "WHEN category_id = {$selectedCategoryId} THEN {$index}";
                    $index++;
                }
                
                // Add other liked categories
                foreach ($userLikedCategories as $category) {
                    if ($category->category_id != $selectedCategoryId) {
                        $caseStatements[] = "WHEN category_id = {$category->category_id} THEN {$index}";
                        $index++;
                    }
                }
                
                $caseStatements[] = "ELSE {$index}";
                
                $reelQuery->orderByRaw("CASE " . implode(' ', $caseStatements) . " END")
                        ->orderBy('views_count', 'asc')
                        ->orderBy('likes_count', 'desc')
                        ->orderByRaw("RAND($randomSeed)");
            } else {
                $reelQuery->orderBy('views_count', 'asc')
                        ->orderBy('likes_count', 'desc')
                        ->orderByRaw("RAND($randomSeed)");
            }
        } else {
            //   $reelQuery->inRandomOrder();
            $reelQuery->orderBy('views_count', 'asc')
                    ->orderBy('likes_count', 'desc')
                    ->orderByRaw("RAND($randomSeed)");
        }

        $reels = $reelQuery->paginate(10);

        // Build response data
        $pagination = $reels->toArray();
        $reelsArr = [];

        $buildReelData = function ($reel, $isSelected = false) use ($userId) {
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
                    'replies'    => $comment->replies->map(fn ($reply) => [  // ← added
                        'id'         => $reply->id,
                        'content'    => $reply->content,
                        'created_at' => $reply->created_at,
                        'user'       => [
                            'full_name' => $reply->user?->full_name ?? '',
                            'avatar'    => $reply->user ? url($reply->user->getAvatar()) : '',
                        ],
                    ]),
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

    public function trending(Request $request)
    {
        $userId = $this->getUserIdFromToken($request);

        // Base query - exclude hidden and reported reels
        $reelQuery = Reel::with(['likes', 'comments.user', 'comments.replies.user', 'review.user', 'savedreel', 'user'])
            ->where('is_hidden', false)
            ->where(function ($query) {
                $query->where('reports_count', '<', 15)
                    ->orWhereNull('reports_count');
            });

        // All users same logic - most liked reels first
        $reels = $reelQuery
            ->leftJoin('reel_review', 'reels.id', '=', 'reel_review.reel_id')
            ->leftJoin('reel_likes',  'reels.id', '=', 'reel_likes.reel_id')
            ->select(
                'reels.*',
                DB::raw('COUNT(DISTINCT reel_likes.id) as computed_likes_count'),
                DB::raw('COALESCE(AVG(reel_review.rating), 0) as avg_rating'),
                DB::raw('COUNT(DISTINCT reel_review.id) as computed_review_count')
            )
            ->groupBy('reels.id')
            ->orderByRaw('
                COUNT(DISTINCT reel_likes.id) DESC,
                (COUNT(DISTINCT reel_likes.id)  * 0.5 +
                 COUNT(DISTINCT reel_review.id) * 0.3 +
                 reels.views_count              * 0.2) DESC,
                reels.id DESC
            ')
            ->paginate(10);

        // Format the response (same as index)
        $pagination = $reels->toArray();
        $reelModels = $reels->items();
        $reelsArr   = [];

        foreach ($reelModels as $reel) {
            $reelData = $reel->toArray();

            $isLiked  = $reel->likes->contains('user_id', $userId);
            $isSaved  = $reel->savedreel->contains('user_id', $userId);
            $username = $reel->user ? $reel->user->full_name : '';

            $likesArr = [];
            foreach ($reel->likes as $like) {
                $likesArr[] = [
                    'id'         => $like->id,
                    'user_id'    => $like->user_id,
                    'reel_id'    => $like->reel_id,
                    'created_at' => $like->created_at,
                ];
            }

            $commentsArr = [];
            foreach ($reel->comments as $comment) {
                $formattedReplies = [];
                foreach ($comment->replies as $reply) {
                    $formattedReplies[] = [
                        'id'         => $reply->id,
                        'content'    => $reply->content,
                        'created_at' => $reply->created_at,
                        'user'       => [
                            'full_name' => $reply->user?->full_name ?? '',
                            'avatar'    => $reply->user ? url($reply->user->getAvatar()) : '',
                        ],
                    ];
                }

                $commentsArr[] = [
                    'id'         => $comment->id,
                    'user_id'    => $comment->user_id,
                    'reel_id'    => $comment->reel_id,
                    'content'    => $comment->content,
                    'created_at' => $comment->created_at,
                    'username'   => $comment->user ? $comment->user->full_name : '',
                    'avatar'     => $comment->user ? url($comment->user->getAvatar()) : '',
                ];
            }

            $reviewsArr = [];
            foreach ($reel->review as $reviews) {
                $reviewsArr[] = [
                    'id'         => $reviews->id,
                    'user_id'    => $reviews->user_id,
                    'reel_id'    => $reviews->reel_id,
                    'review'     => $reviews->review,
                    'rating'     => $reviews->rating,
                    'created_at' => $reviews->created_at,
                    'username'   => $reviews->user ? $reviews->user->full_name : '',
                    'avatar'     => $reviews->user ? url($reviews->user->getAvatar()) : '',
                    'replies'    => $formattedReplies,
                ];
            }

            $reelData['username'] = $username;
            $reelData['is_liked'] = $isLiked;
            $reelData['is_saved'] = $isSaved;
            $reelData['likes']    = $likesArr;
            $reelData['comments'] = $commentsArr;
            $reelData['reviews']  = $reviewsArr;
            $reelsArr[] = $reelData;
        }

        $pagination['reels'] = $reelsArr;
        unset($pagination['data']);

        return response()->json([
            'status' => 'success',
            'data'   => $pagination,
        ]);
    }

    public function global(Request $request)
    {
        $userId = $this->getUserIdFromToken($request);

        // Base query - exclude hidden and reported reels
        $reelQuery = Reel::with(['likes', 'comments.user', 'comments.replies.user', 'review.user', 'savedreel', 'user'])
            ->where('is_hidden', false)
            ->where(function ($query) {
                $query->where('reports_count', '<', 15)
                    ->orWhereNull('reports_count');
            });

        // All users same logic - highest rated + most engaged reels first
        $reels = $reelQuery
            ->leftJoin('reel_review', 'reels.id', '=', 'reel_review.reel_id')
            ->leftJoin('reel_likes',  'reels.id', '=', 'reel_likes.reel_id')
            ->select(
                'reels.*',
                DB::raw('COUNT(DISTINCT reel_likes.id) as computed_likes_count'),
                DB::raw('COALESCE(AVG(reel_review.rating), 0) as avg_rating'),
                DB::raw('COUNT(DISTINCT reel_review.id) as computed_review_count')
            )
            ->groupBy('reels.id')
            ->orderByRaw('
                COALESCE(AVG(reel_review.rating), 0) DESC,
                (COUNT(DISTINCT reel_likes.id)  * 0.4 +
                 COUNT(DISTINCT reel_review.id) * 0.3) DESC,
                reels.id DESC
            ')
            ->paginate(10);

        // Format the response (same as index)
        $pagination = $reels->toArray();
        $reelModels = $reels->items();
        $reelsArr   = [];

        foreach ($reelModels as $reel) {
            $reelData = $reel->toArray();

            $isLiked  = $reel->likes->contains('user_id', $userId);
            $isSaved  = $reel->savedreel->contains('user_id', $userId);
            $username = $reel->user ? $reel->user->full_name : '';

            $likesArr = [];
            foreach ($reel->likes as $like) {
                $likesArr[] = [
                    'id'         => $like->id,
                    'user_id'    => $like->user_id,
                    'reel_id'    => $like->reel_id,
                    'created_at' => $like->created_at,
                ];
            }

            $commentsArr = [];
            foreach ($reel->comments as $comment) {
                $formattedReplies = [];
                foreach ($comment->replies as $reply) {
                    $formattedReplies[] = [
                        'id'         => $reply->id,
                        'content'    => $reply->content,
                        'created_at' => $reply->created_at,
                        'user'       => [
                            'full_name' => $reply->user?->full_name ?? '',
                            'avatar'    => $reply->user ? url($reply->user->getAvatar()) : '',
                        ],
                    ];
                }

                $commentsArr[] = [
                    'id'         => $comment->id,
                    'user_id'    => $comment->user_id,
                    'reel_id'    => $comment->reel_id,
                    'content'    => $comment->content,
                    'created_at' => $comment->created_at,
                    'username'   => $comment->user ? $comment->user->full_name : '',
                    'avatar'     => $comment->user ? url($comment->user->getAvatar()) : '',
                    'replies'    => $formattedReplies,
                ];
            }

            $reviewsArr = [];
            foreach ($reel->review as $reviews) {
                $reviewsArr[] = [
                    'id'         => $reviews->id,
                    'user_id'    => $reviews->user_id,
                    'reel_id'    => $reviews->reel_id,
                    'review'     => $reviews->review,
                    'rating'     => $reviews->rating,
                    'created_at' => $reviews->created_at,
                    'username'   => $reviews->user ? $reviews->user->full_name : '',
                    'avatar'     => $reviews->user ? url($reviews->user->getAvatar()) : '',
                ];
            }

            $reelData['username'] = $username;
            $reelData['is_liked'] = $isLiked;
            $reelData['is_saved'] = $isSaved;
            $reelData['likes']    = $likesArr;
            $reelData['comments'] = $commentsArr;
            $reelData['reviews']  = $reviewsArr;
            $reelsArr[] = $reelData;
        }

        $pagination['reels'] = $reelsArr;
        unset($pagination['data']);

        return response()->json([
            'status' => 'success',
            'data'   => $pagination,
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

        $share = $reel->shares()->create([
            'user_id' => Auth::id(),
            'reel_id' => $reel->id,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $reel->increment('share_count');

        $shareLink = url('/app/launch') . '?' . http_build_query([
            'page'         => 'portals',
            'value'        => $reel->id   // or any unique share code
        ]);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Reel Shared successfully',
            'data' => [
                'share' => $share,
                'share_link' => $shareLink,
            ]
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
                'replies' => [] 
            ]
            // 'data' => $comment->load('user')
        ], 201);
    }

    public function reply(ReelCommentRequest $request, $id)
    {
        $comment = ReelComment::where('id', $id)->first();

        if (!$comment) {
            abort(404);
        }

        $now = time();
        $reply = ReelComment::create([
            'user_id' => Auth::id(),
            'reel_id' => $comment->reel_id,
            'content' => $request->get('content'),
            'reply_id' => $comment->id,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $replies = ReelComment::where('reply_id', $comment->id)
        ->with('user')
        ->orderBy('created_at', 'asc')
        ->get();

        // Format all replies
        $formattedReplies = [];
        foreach ($replies as $existingReply) {
            $formattedReplies[] = [
                'id' => $existingReply->id,
                'content' => $existingReply->content,
                'created_at' => $existingReply->created_at,
                // 'comment_user_type' => 'student',
                'user' => [
                    // 'id' => $existingReply->user->id,
                    'full_name' => $existingReply->user->full_name,
                    'avatar' => url($existingReply->user->getAvatar()),
                ]
            ];
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Reply added successfully',
            'data' => [
                'id' => $comment->id,
                'user_id' => $comment->user_id,
                'reel_id' => $comment->reel_id,
                'content' => $comment->content,
                'created_at' => $comment->created_at, // Convert to timestamp
                'username' => $comment->user->full_name,
                'avatar' => $comment->user ? url($comment->user->getAvatar()) : '',
                'replies' => $formattedReplies
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

        // if (!$reel->isViewedBy(Auth::user())) {
        //     $reel->views()->create(['user_id' => Auth::id()]);
        //     $reel->increment('views_count');
        // }
        $now = time();
        $reel->views()->create(['user_id' => Auth::id(),'created_at' => $now]);
        $reel->increment('views_count');

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
