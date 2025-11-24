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

    public function index(Request $request)
    {

        $data = $request->all();
        $user = apiAuth();
        $user_id = $user->id;

        // Default limit and offset values
        $limit = (int) $request->input('limit', 10); // Default limit is 10
        $offset = (int) $request->input('offset', 0); // Default offset is 0

        // Base query
        $query = Product::where('products.status', Product::$active)
            ->where('ordering', true)
            ->where('price', '!=', 0)
            ->orderBy('id', 'desc');

        // Apply any additional filters
        $query = $this->handleFilters($request, $query);

        // Get total count for pagination metadata
        $totalCount = $query->count();

        // Apply limit and offset
        $products = $query->skip($offset)->take($limit)->get();
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

        return apiResponse2(
            1,
            'retrieved',
            trans('api.public.retrieved'),
            [
                'products' => ProductResource::collection($products),
                'limit' => $limit,
                'offset' => $offset,
                'count' => count($products),
            ]
        );
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

    public function show($id)
    {
        $user = apiAuth();

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
}
