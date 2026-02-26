<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Mixins\Cashback\CashbackRules;
use App\Models\AdvertisingBanner;
use App\Models\Api\Product;
use App\Models\Follow;
use App\Models\ProductCategory;
use App\Models\ProductSelectedSpecification;
use App\Models\ProductOrder;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
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
            // dd('hi'.$user);
            return $user ?? null;
        } catch (\Exception $e) {
            // Log the error if needed
            // \Log::error('Token validation failed: ' . $e->getMessage());
            return null;
        }
    }

    public function index(Request $request)
    {
        $user = $this->getUserIdFromToken($request);
        
        // $user = apiAuth();
        // $user_id = $user->id;
        if($user)
        {
            $user_id = $user->id;
        }
        else
        {
            $user_id = null;
        }

        $data = $request->all();

        // Default limit and offset values
        $limit = (int) $request->input('limit', 10); // Default limit is 10
        $offset = (int) $request->input('offset', 0); // Default offset is 0

        // Base query
        $query = Product::where('products.status', Product::$active)
            ->where('ordering', true)
            ->where('price', '!=', 0);
            // ->orderBy('id', 'desc');

        // Apply any additional filters
        $query = $this->handleFilters($request, $query);

        if ($user_id) {
            // Get user's like statistics by category
            $userCategoryStats = DB::table('product_like')
                ->join('products', 'product_like.product_id', '=', 'products.id')
                ->where('product_like.user_id', $user_id)
                ->whereNotNull('products.category_id')
                ->select('products.category_id', DB::raw('COUNT(*) as like_count'))
                ->groupBy('products.category_id')
                ->orderByDesc('like_count')
                ->get();

            // Get user's saved products count by category
            $userSavedStats = DB::table('product_saved')
                ->join('products', 'product_saved.product_id', '=', 'products.id')
                ->where('product_saved.user_id', $user_id)
                ->whereNotNull('products.category_id')
                ->select('products.category_id', DB::raw('COUNT(*) as saved_count'))
                ->groupBy('products.category_id')
                ->get()
                ->keyBy('category_id');

            // Calculate engagement score for each category
            $categoryEngagement = [];
            foreach ($userCategoryStats as $stat) {
                $engagementScore = $stat->like_count;
                
                // Add saved count to engagement score (weighted less than likes)
                if (isset($userSavedStats[$stat->category_id])) {
                    $engagementScore += ($userSavedStats[$stat->category_id]->saved_count * 0.5);
                }
                
                $categoryEngagement[$stat->category_id] = [
                    'likes' => $stat->like_count,
                    'engagement_score' => $engagementScore,
                    'level' => $this->getEngagementLevel($stat->like_count)
                ];
            }

            // Sort categories by engagement score
            uasort($categoryEngagement, function($a, $b) {
                return $b['engagement_score'] <=> $a['engagement_score'];
            });

            // Apply different logic based on engagement levels
            // $highlyEngagedCategories = [];
            // $interestedCategories = [];
            // $moderatelyEngagedCategories = [];

            // foreach ($categoryEngagement as $categoryId => $stats) {
            //     switch ($stats['level']) {
            //         case 'highly_engaged': // 6+ likes
            //             $highlyEngagedCategories[] = $categoryId;
            //             break;
            //         case 'interested': // 3-5 likes
            //             $interestedCategories[] = $categoryId;
            //             break;
            //         case 'moderate': // 1-2 likes
            //             $moderatelyEngagedCategories[] = $categoryId;
            //             break;
            //     }
            // }

            // Create prioritized ordering based on engagement levels
            $caseOrder = [];
            $priority = 0;
            
            foreach ($categoryEngagement as $categoryId => $stats) {
                $caseOrder[] = "WHEN category_id = {$categoryId} THEN {$priority}";
                $priority++;
            }
            
            // Add other categories (no engagement yet)
            $caseOrder[] = "WHEN category_id IS NOT NULL THEN {$priority}";
            $priority++;
            
            // Add products without category
            $caseOrder[] = "WHEN category_id IS NULL THEN {$priority}";

            // Apply the CASE ordering
            if (!empty($caseOrder)) {
                $caseStatement = "CASE " . implode(' ', $caseOrder) . " END";
                $query->orderByRaw($caseStatement);
            }

            // Apply different logic based on highest engagement
            if (!empty($categoryEngagement)) {
                $highestEngagement = reset($categoryEngagement); // Get first item (highest likes)
                $highestLikes = $highestEngagement['likes'];
                
                if ($highestLikes >= 9) {
                    // Deep Interest: Show only from top categories
                    $topCategories = array_slice(array_keys($categoryEngagement), 0, 3); // Top 3 categories
                    $query->whereIn('category_id', $topCategories);
                    
                } elseif ($highestLikes >= 6) {
                    // High Engagement: Show from all engaged categories
                    $engagedCategoryIds = array_keys($categoryEngagement);
                    $query->whereIn('category_id', $engagedCategoryIds);
                    
                }
                // For 3-5 likes and 1-2 likes, just use the CASE ordering
            }

            // For highly engaged users (9+ likes in any category), mix in learning resources
            $hasDeepLearningInterest = false;
            foreach ($categoryEngagement as $categoryId => $stats) {
                if ($stats['likes'] >= 9) {
                    $hasDeepLearningInterest = true;
                    break;
                }
            }

            if ($hasDeepLearningInterest) {
                // Every 6th item should be a learning resource
                // We'll handle this after fetching results
            }
            
            // Secondary ordering: by id for consistent results
            $query->orderBy('id', 'desc');

        } else {
            // Non-logged in user - default ordering
            $query->orderBy('id', 'desc');
        }

        $totalCount = (clone $query)->count();

        $dbOffset = $offset * $limit;

        // Apply limit and offset
        $products = $query->skip($dbOffset)->take($limit)->get();
        // print_r(count($products));die;

        $productIds = $products->pluck('id')->toArray();

        // Get user's liked products in a single query
        $userLikedProductIds = [];
        if ($user_id && !empty($productIds)) {
            $userLikedProductIds = DB::table('product_like')
                ->where('user_id', $user_id)
                ->whereIn('product_id', $productIds)
                ->pluck('product_id')
                ->toArray();
        }

        $userSavedProductIds = [];
        if ($user_id && !empty($productIds)) {
            $userSavedProductIds = DB::table('product_saved')
                ->where('user_id', $user_id)
                ->whereIn('product_id', $productIds)
                ->pluck('product_id')
                ->toArray();
        }

        $finalProducts = collect([]);
        if ($user_id && isset($hasDeepLearningInterest) && $hasDeepLearningInterest) {
            $learningResources = $this->getLearningResources($user_id, $categoryEngagement);
            
            // Mix products with learning resources (every 6th item)
            $counter = 1;
            foreach ($products as $product) {
                $finalProducts->push($product);
                
                if ($counter % 6 == 0 && !$learningResources->isEmpty()) {
                    // Add a learning resource
                    $learningResource = $learningResources->shift();
                    if ($learningResource) {
                        $finalProducts->push($learningResource);
                    }
                }
                $counter++;
            }
            
            // If we didn't reach the limit, add more products
            if ($finalProducts->count() < $limit) {
                $remaining = $limit - $finalProducts->count();
                $extraProducts = Product::where('products.status', Product::$active)
                    ->where('ordering', true)
                    ->where('price', '!=', 0)
                    ->whereNotIn('id', $finalProducts->pluck('id')->toArray())
                    ->orderBy('id', 'desc')
                    ->take($remaining)
                    ->get();
                    
                $finalProducts = $finalProducts->merge($extraProducts);
            }
            
            $products = $finalProducts->take($limit);
            $productIds = $products->pluck('id')->toArray();
        }

        foreach ($products as $key => $product) {
            if ($user && $product->checkUserHasBought($user)) {
                $product->purchaseStatus = true;
            }

            $product->is_liked = in_array($product->id, $userLikedProductIds);
            $product->is_saved = in_array($product->id, $userSavedProductIds);

            $seller = $product->creator;
            $followers = $seller->followers();

            $authUserIsFollower = false;
            if ($user) {
                // $authUserIsFollower = $followers->where('follower', auth()->id())
                $authUserIsFollower = $followers->where('follower', $user->id)
                    ->where('status', Follow::$accepted)
                    ->isNotEmpty();
            }

            $product->creator->userFollowerStatus = $authUserIsFollower;
        }

        if (!empty($data['category_id'])) {
            $selectedCategory = ProductCategory::where('id', $data['category_id'])->first();
        }

        $trendingProducts = Product::getTrendingProducts(3);

        if ($trendingProducts->isNotEmpty()) {
            $trendingProductIds = $trendingProducts->pluck('id')->toArray();
            
            // Get user's liked products for trending products
            $userLikedTrendingIds = [];
            if ($user_id && !empty($trendingProductIds)) {
                $userLikedTrendingIds = DB::table('product_like')
                    ->where('user_id', $user_id)
                    ->whereIn('product_id', $trendingProductIds)
                    ->pluck('product_id')
                    ->toArray();
            }
            
            // Get user's saved products for trending products
            $userSavedTrendingIds = [];
            if ($user_id && !empty($trendingProductIds)) {
                $userSavedTrendingIds = DB::table('product_saved')
                    ->where('user_id', $user_id)
                    ->whereIn('product_id', $trendingProductIds)
                    ->pluck('product_id')
                    ->toArray();
            }
            
            // Enrich each trending product with user data
            foreach ($trendingProducts as $product) {
                // Check if user has bought the product
                if ($user && $product->checkUserHasBought($user)) {
                    $product->purchaseStatus = true;
                }
                
                // Set like/save status
                $product->is_liked = in_array($product->id, $userLikedTrendingIds);
                $product->is_saved = in_array($product->id, $userSavedTrendingIds);
                
                // Set follower status for creator
                $seller = $product->creator;
                $followers = $seller->followers();
                
                $authUserIsFollower = false;
                if ($user) {
                    $authUserIsFollower = $followers->where('follower', $user->id)
                        ->where('status', Follow::$accepted)
                        ->isNotEmpty();
                }
                
                $product->creator->userFollowerStatus = $authUserIsFollower;
            }
        }

        return apiResponse2(
            1,
            'retrieved',
            trans('api.public.retrieved'),
            [
                'products' => ProductResource::collection($products),
                'trendingProducts' => ProductResource::collection($trendingProducts),
                'limit' => $limit,
                'offset' => $offset,
                'count' => count($products),
                'total_count' => $totalCount,
                'has_more'    => (($dbOffset + $products->count()) < $totalCount)
            ]
        );
    }

    private function getEngagementLevel($likeCount)
    {
        if ($likeCount >= 9) {
            return 'deep_learning'; // User wants deep learning
        } elseif ($likeCount >= 6) {
            return 'highly_engaged'; // Prioritize this topic
        } elseif ($likeCount >= 3) {
            return 'interested'; // Show 3-5 more similar items
        } else {
            return 'moderate'; // Minimal engagement
        }
    }

    /**
     * Get learning resources for deeply engaged users
     */
    private function getLearningResources($userId, $categoryEngagement)
    {
        // Get top 3 most engaged categories
        $topCategories = array_slice($categoryEngagement, 0, 3, true);
        $categoryIds = array_keys($topCategories);
        
        $resources = collect([]);
        
        // 1. Books (products marked as books or with specific tag)
        $books = Product::where('products.status', Product::$active)
            ->where('ordering', true)
            ->whereIn('category_id', $categoryIds)
            ->where(function($query) {
                $query->where('type', 'book')
                    ->orWhere('product_type', 'book')
                    ->orWhereHas('tags', function($q) {
                        $q->where('title', 'like', '%book%');
                    });
            })
            ->orderBy('id', 'desc')
            ->take(3)
            ->get();
        
        $resources = $resources->merge($books);
        
        // 2. Courses (if you have a course model or product type)
        $courses = Product::where('products.status', Product::$active)
            ->where('ordering', true)
            ->whereIn('category_id', $categoryIds)
            ->where(function($query) {
                $query->where('type', 'course')
                    ->orWhere('product_type', 'course')
                    ->orWhere('is_course', true);
            })
            ->orderBy('id', 'desc')
            ->take(3)
            ->get();
        
        $resources = $resources->merge($courses);
        
        // 3. Webinars (if you have webinar model or product type)
        $webinars = Product::where('products.status', Product::$active)
            ->where('ordering', true)
            ->whereIn('category_id', $categoryIds)
            ->where(function($query) {
                $query->where('type', 'webinar')
                    ->orWhere('product_type', 'webinar')
                    ->orWhere('is_webinar', true);
            })
            ->orderBy('id', 'desc')
            ->take(3)
            ->get();
        
        $resources = $resources->merge($webinars);
        
        return $resources->shuffle();
    }

    public function handleFilters(Request $request, $query, $isRewardProducts = false)
    {
        $search = $request->get('search', null);
        $isFree = $request->get('free', null);
        $isFreeShipping = $request->get('free_shipping', null);
        $withDiscount = $request->get('discount', null);
        $sort = $request->get('sort', null);
        $type = $request->get('type', null);
        $options = $request->get('options', null);
        $categoryId = (int) $request->get('category_id', null);
        $filterOption = $request->get('filter_option', null);

        if (!empty($search)) {
            $query->whereTranslationLike('title', '%' . $search . '%');
        }

        if (!empty($isFree) and $isFree == true) {
            $query->where(function ($qu) {
                $qu->whereNull('price')
                    ->orWhere('price', '0');
            });
        }

        if (!empty($isFreeShipping) and $isFreeShipping == true) {
            $query->where(function ($qu) {
                $qu->whereNull('delivery_fee')
                    ->orWhere('delivery_fee', '0');
            });
        }

        if (!empty($withDiscount) and $withDiscount == true) {
            $query->whereHas('discounts', function ($query) {
                $query->where('status', 'active')
                    ->where('start_date', '<', time())
                    ->where('end_date', '>', time());
            });
        }

        if (!empty($type) and count($type)) {
            $query->whereIn('type', $type);
        }

        if (!empty($options) and count($options)) {
            if (in_array('only_available', $options)) {
                $query->where(function ($query) {
                    $query->where('unlimited_inventory', true)
                        ->orWhereHas('productOrders', function ($query) {
                            $query->havingRaw('products.inventory > sum(quantity)')
                                ->whereNotNull('sale_id')
                                ->whereNotIn('status', [ProductOrder::$canceled, ProductOrder::$pending])
                                ->groupBy('product_id');
                        });
                });
            }

            if (in_array('with_point', $options)) {
                $query->whereNotNull('point');
            }
        }

        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        if (!empty($filterOption) and is_array($filterOption)) {
            $productIdsFilterOptions = ProductSelectedFilterOption::whereIn('filter_option_id', $filterOption)
                ->pluck('product_id')
                ->toArray();

            $productIdsFilterOptions = array_unique($productIdsFilterOptions);

            $query->whereIn('products.id', $productIdsFilterOptions);
        }

        if (!empty($sort)) {
            if ($sort == 'expensive') {
                if ($isRewardProducts) {
                    $query->orderBy('point', 'desc');
                } else {
                    $query->orderBy('price', 'desc');
                }
            }

            if ($sort == 'inexpensive') {
                if ($isRewardProducts) {
                    $query->orderBy('point', 'asc');
                } else {
                    $query->orderBy('price', 'asc');
                }
            }

            if ($sort == 'bestsellers') {
                $query->leftJoin('product_orders', function ($join) {
                    $join->on('products.id', '=', 'product_orders.product_id')
                        ->whereNotNull('product_orders.sale_id')
                        ->whereNotIn('product_orders.status', [ProductOrder::$canceled, ProductOrder::$pending]);
                })
                    ->select('products.*', DB::raw('sum(product_orders.quantity) as salesCounts'))
                    ->groupBy('product_orders.product_id')
                    ->orderBy('salesCounts', 'desc');
            }

            if ($sort == 'best_rates') {
                $query->leftJoin('product_reviews', function ($join) {
                    $join->on('products.id', '=', 'product_reviews.product_id');
                    $join->where('product_reviews.status', 'active');
                })
                    ->whereNotNull('rates')
                    ->select('products.*', DB::raw('avg(rates) as rates'))
                    ->groupBy('product_reviews.product_id')
                    ->orderBy('rates', 'desc');
            }
        }

        return $query;
    }

    public function show(Request $request, $id)
    {
        $user = $this->getUserIdFromToken($request);
        $user_id = $user ? $user->id : null;
        
        $product = Product::where('status', Product::$active)
            ->where('id', $id)
            ->with([
                'selectedSpecifications' => function ($query) {
                    $query->where('status', ProductSelectedSpecification::$Active);
                    $query->with(['specification']);
                },
                'comments' => function ($query) {
                    $query->where('status', 'active');
                    $query->whereNull('reply_id');
                    $query->with([
                        'replies' => function ($query) {
                            $query->where('status', 'active');
                        }
                    ]);
                    $query->orderBy('created_at', 'desc');
                },
                'files' => function ($query) {
                    $query->where('status', 'active');
                    $query->orderBy('order', 'asc');
                },
                'reviews' => function ($query) {
                    // $query->where('status', 'active');
                    $query->with([
                        'comments' => function ($query) {
                            $query->where('status', 'active');
                        },
                    ]);
                },
            ])
            ->first();

        if (empty($product)) {
            abort(404);
        }

        // Add user-specific data to the product
        if ($user_id) {
            // Check if user has liked this product
            $product->is_liked = DB::table('product_like')
                ->where('user_id', $user_id)
                ->where('product_id', $product->id)
                ->exists();
                
            // Check if user has saved this product
            $product->is_saved = DB::table('product_saved')
                ->where('user_id', $user_id)
                ->where('product_id', $product->id)
                ->exists();
                
            // Check if user has bought this product
            $product->purchaseStatus = $product->checkUserHasBought($user);
        } else {
            $product->is_liked = false;
            $product->is_saved = false;
            $product->purchaseStatus = false;
        }

        // Add follower status for the creator
        $seller = $product->creator;
        $followers = $seller->followers();
        
        $authUserIsFollower = false;
        if ($user) {
            $authUserIsFollower = $followers->where('follower', $user->id)
                ->where('status', Follow::$accepted)
                ->isNotEmpty();
        }
        
        $product->creator->userFollowerStatus = $authUserIsFollower;

        $selectableSpecifications = $product->selectedSpecifications->where('allow_selection', true)
            ->where('type', 'multi_value');
        $selectedSpecifications = $product->selectedSpecifications->where('allow_selection', false);
        $seller = $product->creator;

        $cashbackRules = null;
        if (!empty($product->price) and getFeaturesSettings('cashback_active') and (empty($user) or !$user->disable_cashback)) {
            $cashbackRulesMixin = new CashbackRules($user);
            $cashbackRules = $cashbackRulesMixin->getRules('store_products', $product->id, $product->type, $product->category_id, $product->creator_id);
        }
        $product->cashbackRules = $cashbackRules;

        $resource = new ProductResource($product);
        $resource->show = true;
        // dd($resource);
        return apiResponse2(
            1,
            'retrieved',
            trans('api.public.retrieved'),
            [
                'product' => $resource,
            ]
        );
    }


    public function getSortData()
    {
        $data = ['newest', 'expensive', 'inexpensive', 'best_rates', 'bestsellers'];
        return apiResponse2(
            1,
            'retrieved',
            trans('api.public.retrieved'),
            $data
        );
    }

    public function productlike(Request $request,$id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $product = Product::where('id', $id)->first();

        $like = DB::table('product_like')
            ->where('product_id', $product->id)
            ->where('user_id', $userid)
            ->exists();

        if ($like) {
            DB::table('product_like')
            ->where('product_id', $product->id)
            ->where('user_id', $userid)
            ->delete();
            
            Product::where('id', $id)->decrement('like_count');
            //$product->decrement('like_count');
            $action = 'unliked';
        } else {
            DB::table('product_like')->insert([
                'user_id' => $userid,
                'product_id' => $product->id
            ]);

            Product::where('id', $id)->increment('like_count');
            //$product->increment('like_count');
            $action = 'liked';
        }

        return response()->json([
            'status' => 'success',
            'message' => "Product {$action} successfully",
            'data' => [
                'liked' => !$like,
                'like_count' => $product->like_count
            ]
        ]);
    }

    public function productshare(Request $request,$id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $product = Product::where('id', $id)->first();

        $now = time();

        $share = $product->share()->create([
            'user_id' => $userid,
            'product_id' => $product->id,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        Product::where('id', $id)->increment('share_count');
        //$product->increment('share_count');
        
        return response()->json([
            'status' => 'success',
            'message' => 'Product Shared successfully',
            'data' => $share
        ], 201);
    }

    public function productgift(Request $request,$id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $product = Product::where('id', $id)->first();

        $now = time();

        $gift = $product->gifts()->create([
            'user_id' => $userid,
            'product_id' => $product->id,
            'gift_id' => $request->gift_id, 
            'created_at' => $now,
            'updated_at' => $now
        ]);

        Product::where('id', $id)->increment('gift_count');
        //$product->increment('gift_count');
        
        return response()->json([
            'status' => 'success',
            'message' => 'Gift Send successfully',
            'data' => $gift
        ], 201);
    }
    
    public function productcomment(Request $request,$id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $product = Product::where('id', $id)->first();

        $now = time();
        $comment = $product->comments()->create([
            'user_id' => $userid,
            'product_id' => $product->id,
            'comment' => $request->get('content'),
            'status' => 'active',
            'created_at' => $now,
            'updated_at' => $now
        ]);

        //dd($comment);

        Product::where('id', $id)->increment('comments_count');
        //$product->increment('comments_count');

        $formattedComment = [
            'id' => $comment->id,
            'status' => $comment->status,
            'comment_user_type' => $comment->comment_user_type,
            'create_at' => $comment->created_at,
            'content' => $comment->comment,
            'can' => [
                'delete' => true, // You'll need to implement your logic here
                'report' => true,
                'reply' => true
            ],
            'product' => [
                'id' => $comment->product->id,
                'title' => $comment->product->title
            ],
            'user' => [
                'id' => $comment->user->id,
                'full_name' => $comment->user->full_name,
                'role_name' => $comment->user->role_name ?? 'user',
                'bio' => $comment->user->bio,
                'email' => $comment->user->email,
                'mobile' => $comment->user->mobile,
                'offline' => $comment->user->offline ?? 0,
                'offline_message' => $comment->user->offline_message,
                'verified' => $comment->user->verified ?? 0,
                'rate' => $comment->user->rate ?? 0,
                'avatar' => $comment->user->avatar ?? 'http://127.0.0.1:8000/getDefaultAvatar?item=' . $comment->user->id . '&name=' . urlencode($comment->user->full_name) . '&size=40',
                'meeting_status' => $comment->user->meeting_status ?? 'no',
                'user_group' => $comment->user->user_group,
                'address' => $comment->user->address,
                'status' => $comment->user->status ?? 'active'
            ],
            'replies' => [] // Empty array for replies
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Comment added successfully',
            'data' => $formattedComment
        ], 201);
    }

    public function productreport(Request $request, $id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $product = Product::where('id', $id)->first();

        $now = time();

        $report = $product->reports()->create([
            'user_id' => $userid,
            'product_id' => $product->id,
            'reason' => $request->reason,
            'description' => $request->description,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        Product::where('id', $id)->increment('report_count');

        return response()->json([
            'status' => 'success',
            'message' => 'Product reported successfully',
            'data' => $report
        ], 201);
    }


    public function productsave(Request $request,$id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $product = Product::where('id', $id)->first();

        $now = time();

        $save = DB::table('product_saved')
            ->where('product_id', $product->id)
            ->where('user_id', $userid)
            ->exists();

        if ($save) {
            DB::table('product_saved')
            ->where('product_id', $product->id)
            ->where('user_id', $userid)
            ->delete();
            
            Product::where('id', $id)->decrement('saved_count');
            //$product->decrement('like_count');
            $action = 'unsaved';
        } else {
            DB::table('product_saved')->insert([
                'user_id' => $userid,
                'product_id' => $product->id,
                'created_at' => $now,
                'updated_at' => $now
            ]);

            Product::where('id', $id)->increment('saved_count');
            //$product->increment('like_count');
            $action = 'saved';
        }
        //$product->increment('saved_count');
        
        return response()->json([
            'status' => 'success',
            'message' => "Product {$action} successfully",
            'data' => [
                'saved' => !$save,
                'saved_count' => $product->saved_count
            ]
        ], 201);
    }

    public function productreview(Request $request,$id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $now = time();

        $product = Product::where('id', $id)->first();

        $review = $product->reviews()->create([
            'product_id' => $id,
            'creator_id' => $user->id,
            'product_quality' => 0,
            'purchase_worth' => 0,
            'delivery_quality' => 0,
            'seller_quality' => 0,
            'rates' => $request->rating,
            'description' => $request->review,
            'created_at' => time(),
            'status' => 'active',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Review added successfully',
            'data' => [
                'id' => $review->id,
                'description' => $review->description,
                'user' => [
                    'user_id' => $user->id,
                    'full_name' => $user->full_name,
                    'avatar' => $user->getAvatar() ? url($user->getAvatar()) : ''
                ],
                'can' => [
                    'delete' => true, // or your logic to determine if user can delete
                    'reply' => true    // or your logic to determine if user can reply
                ],
                'rate' => (string)$review->rates, // Convert to string if needed
                'rate_type' => [
                    'content_quality' => (string)($review->content_quality ?? ''),
                    'instructor_skills' => (string)($review->instructor_skills ?? ''),
                    'purchase_worth' => (string)($review->purchase_worth ?? '0'),
                    'support_quality' => (string)($review->support_quality ?? '')
                ],
                'created_at' =>$review->created_at, // Convert to timestamp
                'comments' => [], // Empty array as in your example
                'replies' => [], // Empty array as in your example
                'review_status' => true,

                // 'id' => $review->id,
                // 'user_id' => $review->creator_id,
                // 'webinar_id' => $review->rates,
                // 'review' => $review->description,
                // 'rating' => $review->rates,
                // 'created_at' => $review->created_at, // Convert to timestamp
                // 'username' => $user->full_name, // Use the authenticated user directly
                // 'avatar' => $user->getAvatar() ? url($user->getAvatar()) : '',
            ]
            // 'data' => $comment->load('user')
        ], 201);
    }
}
