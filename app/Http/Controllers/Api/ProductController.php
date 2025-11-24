<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\User;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductOrder;
use App\Models\ProductSelectedFilterOption;
use App\Models\ProductSelectedSpecification;
use App\Http\Controllers\Web\traits\CheckContentLimitationTrait;
use App\Http\Controllers\Web\traits\InstallmentsTrait;
use App\Models\AdvertisingBanner;
use App\Mixins\Installment\InstallmentPlans;

class ProductController extends Controller
{
    use InstallmentsTrait;
    use CheckContentLimitationTrait;

    public function getStore(Request $request){

        $data = $request->all();

        $query = Product::where('products.status', Product::$active)
            ->where('ordering', true);

        $query = $this->handleFilters($request, $query);

        $products = $query->paginate(9);

        if(empty($products)){
            return response()->json(['status'=> 201, 'message' => 'Data Not Found!']);
        }

        $categories = ProductCategory::whereNull('parent_id')
            ->with([
                'subCategories' => function ($query) {
                    $query->orderBy('order', 'asc');
                },
            ])
            ->get();

        $selectedCategory = null;

        if (!empty($data['category_id'])) {
            $selectedCategory = ProductCategory::where('id', $data['category_id'])->first();
        }

        $seoSettings = getSeoMetas('products_lists');
        $pageTitle = $seoSettings['title'] ?? '';
        $pageDescription = $seoSettings['description'] ?? '';
        $pageRobot = getPageRobot('products_lists');

        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'productsCount' => $products->total(),
            'productCategories' => $categories,
            'selectedCategory' => $selectedCategory,
            'products' => $products,
        ];

        return response()->json(['status'=> 200, 'data' => $data]);
    }

    public function show($slug)
    {
        $user = null;

        if (auth()->check()) {
            $user = auth()->user();
        }

        $contentLimitation = $this->checkContentLimitation($user, true);
        if ($contentLimitation != "ok") {
            return $contentLimitation;
        }

        $product = Product::where('status', Product::$active)
            ->where('slug', $slug)
            ->with([
                'selectedSpecifications' => function ($query) {
                    $query->where('status', ProductSelectedSpecification::$Active);
                    $query->with(['specification']);
                },
                'comments' => function ($query) {
                    $query->where('status', 'active');
                    $query->whereNull('reply_id');
                    $query->with([
                        'user' => function ($query) {
                            $query->select('id', 'full_name', 'role_name', 'role_id', 'avatar', 'avatar_settings');
                        },
                        'replies' => function ($query) {
                            $query->where('status', 'active');
                            $query->with([
                                'user' => function ($query) {
                                    $query->select('id', 'full_name', 'role_name', 'role_id', 'avatar', 'avatar_settings');
                                }
                            ]);
                        }
                    ]);
                    $query->orderBy('created_at', 'desc');
                },
                'files' => function ($query) {
                    $query->where('status', 'active');
                    $query->orderBy('order', 'asc');
                },
                'reviews' => function ($query) {
                    $query->where('status', 'active');
                    $query->with([
                        'comments' => function ($query) {
                            $query->where('status', 'active');
                        },
                        'creator' => function ($qu) {
                            $qu->select('id', 'full_name', 'avatar');
                        }
                    ]);
                },
            ])
            ->first();

        if (empty($product)) {
            return response()->json(['status'=> 201, 'message' => 'Data Not Found!']);
        }

        $selectableSpecifications = $product->selectedSpecifications->where('allow_selection', true)
            ->where('type', 'multi_value');
        $selectedSpecifications = $product->selectedSpecifications->where('allow_selection', false);

        $seller = $product->creator;
        $following = $seller->following();
        $followers = $seller->followers();

        $authUserIsFollower = false;
        if (auth()->check()) {
            $authUserIsFollower = $followers->where('follower', auth()->id())
                ->where('status', Follow::$accepted)
                ->first();
        }

        $advertisingBanners = AdvertisingBanner::where('published', true)
            ->whereIn('position', ['product_show'])
            ->get();

        /* Installments */
        $installments = null;
        if (!empty($product->price) and $product->price > 0 and getInstallmentsSettings('status') and (empty($user) or $user->enable_installments)) {
            $installmentPlans = new InstallmentPlans($user);
            $installments = $installmentPlans->getPlans('store_products', $product->id, $product->type, $product->category_id, $product->creator_id);
        }

        /* Cashback Rules */
        $cashbackRules = null;
        if (!empty($product->price) and getFeaturesSettings('cashback_active') and (empty($user) or !$user->disable_cashback)) {
            $cashbackRulesMixin = new CashbackRules($user);
            $cashbackRules = $cashbackRulesMixin->getRules('store_products', $product->id, $product->type, $product->category_id, $product->creator_id);
        }

        $instructorDiscounts = null;

        if (!empty(getFeaturesSettings('frontend_coupons_status'))) {
            $instructorDiscounts = Discount::query()
                ->where('creator_id', $product->creator_id)
                ->where(function (Builder $query) use ($product) {
                    $query->where('source', 'all');
                    $query->orWhere(function (Builder $query) use ($product) {
                        $query->where('source', Discount::$discountSourceProduct);
                        $query->where('product_type', $product->type);
                    });
                })
                ->where('status', 'active')
                ->where('expired_at', '>', time())
                ->get();
        }


        $pageRobot = getPageRobot('product_show'); // return => index

        $data = [
            'pageTitle' => $product->title,
            'pageDescription' => $product->seo_description,
            'pageRobot' => $pageRobot,
            'pageMetaImage' => $product->thumbnail,
            'product' => $product,
            'user' => $user,
            'selectableSpecifications' => $selectableSpecifications,
            'selectedSpecifications' => $selectedSpecifications,
            'seller' => $seller,
            'sellerBadges' => $seller->getBadges(),
            'sellerRates' => $seller->rates(),
            'sellerFollowers' => $following,
            'sellerFollowing' => $followers,
            'authUserIsFollower' => $authUserIsFollower,
            'advertisingBanners' => $advertisingBanners,
            'activeSpecialOffer' => $product->getActiveDiscount(),
            'hasInstallments' => (!empty($installments) and count($installments)),
            'cashbackRules' => $cashbackRules,
            'instructorDiscounts' => $instructorDiscounts,
        ];

        return response()->json(['status'=> 200, 'data' => $data]);
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
        $categoryId = $request->get('category_id', null);
        $filterOption = $request->get('filter_option', null);

        if (!empty($search)) {
            $query->whereTranslationLike('title', '%' . $search . '%');
        }

        if (!empty($isFree) and $isFree == 'on') {
            $query->where(function ($qu) {
                $qu->whereNull('price')
                    ->orWhere('price', '0');
            });
        }

        if (!empty($isFreeShipping) and $isFreeShipping == 'on') {
            $query->where(function ($qu) {
                $qu->whereNull('delivery_fee')
                    ->orWhere('delivery_fee', '0');
            });
        }

        if (!empty($withDiscount) and $withDiscount == 'on') {
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

}
