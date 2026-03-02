<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mixins\Installment\InstallmentPlans;
use App\Models\AdvertisingBanner;
use App\Models\Blog;
use App\Models\Bundle;
use App\Models\FeatureWebinar;
use App\Models\HomePageStatistic;
use App\Models\HomeSection;
use App\Models\Product;
use App\Models\Role;
use App\Models\Sale;
use App\Models\SpecialOffer;
use App\Models\Subscribe;
use App\Models\Ticket;
use App\Models\TrendCategory;
use App\Models\UpcomingCourse;
use App\Models\Webinar;
use App\Models\MediaKit;
use App\Models\MediaTool;
use App\Models\Reel;
use App\Models\Book;
use App\Models\SubscribeUse;
use App\Models\Accounting;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Translation\CategoryTranslation;
use App\Models\Testimonial;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function brand_ambassador()
    {
        return view('web.default.pages.brand-ambassador');
    }

    public function landing_page()
    {
        $subscribes = Subscribe::orderBy('price', 'desc')->get()->skip(1)->take(2);

        $user = auth()->user();
        $installmentPlans = new InstallmentPlans($user);
        
        foreach ($subscribes as $subscribe) {
            if (getInstallmentsSettings('status') and (empty($user) or $user->enable_installments) and $subscribe->price > 0) {
                $installments = $installmentPlans->getPlans('subscription_packages', $subscribe->id);
        
                $subscribe->has_installment = (!empty($installments) and count($installments));
            }
        }
        

        return view('web.default.pages.landing-page',compact('subscribes'));
    }
    
    public function media_kit()
    {
        $mediaTools = MediaTool::where('status','active')->get();
        $mediaKit = MediaKit::where('status','active')->get();
        $categories = CategoryTranslation::where('locale','en')->get();
        return view('web.default.pages.media-kit', compact('mediaKit','categories', 'mediaTools'));
    }
    
    public function upload_media()
    {
        $categories = CategoryTranslation::where('locale','en')->get();
        return view('web.default.pages.upload_media', compact('categories'));
    }
    
    public function create_media(Request $request)
    {
        $request->validate([
            'category'    => 'required|exists:category_translations,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'courseLink'  => 'nullable|string',
            'video'       => 'required|mimes:mp4,mov,avi,wmv|max:20480', // Max 20MB
        ]);

        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $filename = time() . '_' . $file->getClientOriginalName(); // Unique filename
            $file->move(public_path('assets/media-kit'), $filename); // Store in public/uploads/videos
        
            $videoPath = 'assets/media-kit/' . $filename;
        } else {
            return back()->with('error', 'Video file is required.');
        }

        // Save data to database
        MediaKit::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category,
            'title'       => $request->title,
            'description' => $request->description,
            'course_link' => $request->courseLink,
            'video_path'  => $videoPath,
        ]);

        return back()->with('success', 'Media successfully uploaded.');
    }
    
    public static function getActiveSubscribe($userId)
    {
        $activePlan = null;
        $subscribe = null;
        $saleCreatedAt = null;

        $lastSubscribeSale = Sale::where('buyer_id', $userId)
            ->where('type', Sale::$subscribe)
            ->whereNull('refund_at')
            ->latest('created_at')
            ->first();

        if ($lastSubscribeSale) {
            $subscribe = $lastSubscribeSale->subscribe;
            $saleCreatedAt = $lastSubscribeSale->created_at;
        }


        /* check installment */
        if (empty($subscribe)) {
            $installmentOrder = InstallmentOrder::query()->where('user_id', $userId)
                ->whereNotNull('subscribe_id')
                ->where('status', 'open')
                ->whereNull('refund_at')
                ->latest('created_at')
                ->first();

            if (!empty($installmentOrder)) {
                $subscribe = $installmentOrder->subscribe;
                $subscribe->installment_order_id = $installmentOrder->id;
                $saleCreatedAt = $installmentOrder->created_at;

                if ($installmentOrder->checkOrderHasOverdue()) {
                    $overdueIntervalDays = getInstallmentsSettings('overdue_interval_days');

                    if (empty($overdueIntervalDays) or $installmentOrder->overdueDaysPast() > $overdueIntervalDays) {
                        $subscribe = null;
                    }
                }
            }
        }


        if (!empty($subscribe) and !empty($saleCreatedAt)) {
            $useCount = SubscribeUse::where('user_id', $userId)
                ->where('subscribe_id', $subscribe->id)
                ->whereHas('sale', function ($query) use ($saleCreatedAt) {
                    $query->where('created_at', '>', $saleCreatedAt);
                    $query->whereNull('refund_at');
                })
                ->count();

            $subscribe->used_count = $useCount;

            $countDayOfSale = (int)diffTimestampDay(time(), $saleCreatedAt);
            // echo "<pre>";
            // print_r($countDayOfSale);
            // die;

            // 100000 > 5 or 1 and 31 >= 88;

            if (($subscribe->usable_count > $useCount || $subscribe->infinite_use) && $subscribe->days >= $countDayOfSale) {

                $activePlan = $subscribe;
            }
        }

        // echo "<pre>";
        // print_r($activePlan);
        // die;
        return $activePlan;
    }

    public function test_subscription()
    {
        $this->authorize("panel_financial_subscribes");

        $user = auth()->user();

        if (!$user) {
            $user = apiAuth();
        }

        $subscribes = Subscribe::all();

        $installmentPlans = new InstallmentPlans($user);
        foreach ($subscribes as $subscribe) {
            if (getInstallmentsSettings('status') and $user->enable_installments and $subscribe->price > 0) {
                $installments = $installmentPlans->getPlans('subscription_packages', $subscribe->id);

                $subscribe->has_installment = (!empty($installments) and count($installments));
            }
        }
        // $subscribe = Subscribe::getActiveSubscribe($user->id);
        // echo "<pre>";
        // print_r($subscribe->title);
        // die;
        $data = [
            'pageTitle' => trans('financial.subscribes'),
            'subscribes' => $subscribes,
            'activeSubscribe' => Subscribe::getActiveSubscribe($user->id),
            'dayOfUse' => Subscribe::getDayOfUse($user->id),
        ];

        return view('web.default.pages.test-subscription', $data);
    }
    
    public function test_new(Request $request)
    {
        // Log the entire request data
        Log::info('Incoming request:', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'headers' => $request->headers->all(),
            'body' => $request->all(),
            'ip' => $request->ip()
        ]);

        // Your existing code logic here

        return response()->json(['message' => 'Request logged successfully']);
    }
    public function index()
    { 
        $user = null;

        $activeSubscribe = "";

        if (auth()->check()) {
            $user = auth()->user();
        }

        $homeSections = HomeSection::orderBy('order', 'asc')->get();
        $selectedSectionsName = $homeSections->pluck('name')->toArray();

        $isWisdomKeeper = $user && $user->role->caption === 'Wisdom Keeper';

        $wisdomKeeperIds = [];
        if ($isWisdomKeeper) {
            $wisdomKeeperIds = User::whereHas('role', function($q) {
                $q->where('caption', 'Wisdom Keeper');
            })->pluck('id')->toArray();
        }

        $featureWebinars = null;
        if (in_array(HomeSection::$featured_classes, $selectedSectionsName)) {
            $featureWebinars = FeatureWebinar::whereIn('page', ['home', 'home_categories'])
                ->where('status', 'publish')
                ->whereHas('webinar', function ($query) {
                    $query->where('status', Webinar::$active);
                })
                ->with([
                    'webinar' => function ($query) {
                        $query->with([
                            'teacher' => function ($qu) {
                                $qu->select('id', 'full_name', 'avatar');
                            },
                            'reviews' => function ($query) {
                                $query->where('status', 'active');
                            },
                            'tickets',
                            'feature'
                        ]);
                    }
                ])
                ->orderBy('updated_at', 'desc')
                ->get();
            //$selectedWebinarIds = $featureWebinars->pluck('id')->toArray();
        }

        if (in_array(HomeSection::$latest_classes, $selectedSectionsName)) {
            
            $courseQuery = Webinar::where('webinars.status', Webinar::$active)
                ->where('private', false)
                ->with(['teacher:id,full_name,avatar', 'reviews' => fn($q) => $q->where('webinar_reviews.status', 'active'), 'tickets', 'feature']);

            if ($isWisdomKeeper) {
                // Wisdom Keeper logic: show only Wisdom Keeper created content, sorted by highest reviews first
                // Items with NULL ratings (no reviews) will appear after items with ratings
                $latestWebinars = $courseQuery
                    ->whereIn('webinars.teacher_id', $wisdomKeeperIds)
                    ->leftJoin('webinar_reviews', 'webinars.id', '=', 'webinar_reviews.webinar_id')
                    ->leftJoin('webinar_like', 'webinars.id', '=', 'webinar_like.webinar_id')
                    ->select('webinars.*', 
                        DB::raw('COUNT(DISTINCT webinar_like.id) as likes_count'),
                        DB::raw('AVG(webinar_reviews.rates) as avg_rating'),
                        DB::raw('COUNT(DISTINCT webinar_reviews.id) as review_count')
                    )
                    ->groupBy('webinars.id')
                    ->orderByRaw('
                        -- First priority: items with reviews (non-NULL ratings)
                        CASE 
                            WHEN AVG(webinar_reviews.rates) IS NOT NULL THEN 0
                            ELSE 1 
                        END,
                        -- Then order by highest rating (NULLs will be last automatically in MySQL)
                        AVG(webinar_reviews.rates) DESC,
                        -- Then by number of reviews
                        COUNT(DISTINCT webinar_reviews.id) DESC,
                        -- Then by likes
                        COUNT(DISTINCT webinar_like.id) DESC,
                        -- Finally by updated date
                        webinars.updated_at DESC
                    ')
                    ->limit(6)
                    ->get()
                    ->map(function ($course) {
                        $course->thumbnail = !empty($course->thumbnail) ? url($course->thumbnail) : null;
                        $course->image_cover = !empty($course->image_cover) ? url($course->image_cover) : null;
                        
                        if ($course->teacher && !empty($course->teacher->avatar)) {
                            $course->teacher->avatar = url($course->teacher->avatar);
                        }
                        
                        return $course;
                    });
            } else {
                // Guest/normal user logic: prioritize by engagement metrics
                $latestWebinars = $courseQuery
                    ->leftJoin('webinar_reviews', 'webinars.id', '=', 'webinar_reviews.webinar_id')
                    ->leftJoin('sales', 'webinars.id', '=', 'sales.webinar_id')
                    ->select('webinars.*',
                        DB::raw('COUNT(DISTINCT sales.id) as sales_count'),
                        DB::raw('COALESCE(AVG(webinar_reviews.rates), 0) as avg_rating'),
                        DB::raw('COUNT(DISTINCT webinar_reviews.id) as review_count')
                    )
                    ->groupBy('webinars.id')
                    ->orderByRaw('
                        COALESCE(AVG(webinar_reviews.rates), 0) DESC,
                        (COUNT(DISTINCT sales.id) * 0.4 + 
                        COUNT(DISTINCT webinar_reviews.id) * 0.3) DESC,
                        webinars.updated_at DESC
                    ')
                    ->limit(6)
                    ->get()
                    ->map(function ($course) {
                        $course->thumbnail = !empty($course->thumbnail) ? url($course->thumbnail) : null;
                        $course->image_cover = !empty($course->image_cover) ? url($course->image_cover) : null;
                        
                        if ($course->teacher && !empty($course->teacher->avatar)) {
                            $course->teacher->avatar = url($course->teacher->avatar);
                        }
                        
                        return $course;
                    });
            }
            
            // $latestWebinars = Webinar::where('status', Webinar::$active)
            //     ->where('private', false)
            //     ->orderBy('updated_at', 'desc')
            //     ->with([
            //         'teacher' => function ($qu) {
            //             $qu->select('id', 'full_name', 'avatar');
            //         },
            //         'reviews' => function ($query) {
            //             $query->where('status', 'active');
            //         },
            //         'tickets',
            //         'feature'
            //     ])
            //     ->limit(6)
            //     ->get();

            //$selectedWebinarIds = array_merge($selectedWebinarIds, $latestWebinars->pluck('id')->toArray());
        }

        if (in_array(HomeSection::$latest_bundles, $selectedSectionsName)) {
            $latestBundles = Bundle::where('status', Webinar::$active)
                ->orderBy('updated_at', 'desc')
                ->with([
                    'teacher' => function ($qu) {
                        $qu->select('id', 'full_name', 'avatar');
                    },
                    'reviews' => function ($query) {
                        $query->where('status', 'active');
                    },
                    'tickets',
                ])
                ->limit(6)
                ->get();
        }

        if (in_array(HomeSection::$upcoming_courses, $selectedSectionsName)) {
            $upcomingCourses = UpcomingCourse::where('status', Webinar::$active)
                ->orderBy('created_at', 'desc')
                ->with([
                    'teacher' => function ($qu) {
                        $qu->select('id', 'full_name', 'avatar');
                    }
                ])
                ->limit(6)
                ->get();
        }

        if (in_array(HomeSection::$best_sellers, $selectedSectionsName)) {
            $bestSaleWebinarsIds = Sale::whereNotNull('webinar_id')
                ->select(DB::raw('COUNT(id) as cnt,webinar_id'))
                ->groupBy('webinar_id')
                ->orderBy('cnt', 'DESC')
                ->limit(6)
                ->pluck('webinar_id')
                ->toArray();

            $bestSaleWebinars = Webinar::whereIn('id', $bestSaleWebinarsIds)
                ->where('status', Webinar::$active)
                ->where('private', false)
                ->with([
                    'teacher' => function ($qu) {
                        $qu->select('id', 'full_name', 'avatar');
                    },
                    'reviews' => function ($query) {
                        $query->where('status', 'active');
                    },
                    'sales',
                    'tickets',
                    'feature'
                ])
                ->get();

            //$selectedWebinarIds = array_merge($selectedWebinarIds, $bestSaleWebinars->pluck('id')->toArray());
        }

        if (in_array(HomeSection::$best_rates, $selectedSectionsName)) {
            $bestRateWebinars = Webinar::join('webinar_reviews', 'webinars.id', '=', 'webinar_reviews.webinar_id')
                ->select('webinars.*', 'webinar_reviews.rates', 'webinar_reviews.status', DB::raw('avg(rates) as avg_rates'))
                ->where('webinars.status', 'active')
                ->where('webinars.private', false)
                ->where('webinar_reviews.status', 'active')
                ->groupBy('teacher_id')
                ->orderBy('avg_rates', 'desc')
                ->with([
                    'teacher' => function ($qu) {
                        $qu->select('id', 'full_name', 'avatar');
                    }
                ])
                ->limit(1)
                //->limit(6)
                ->get();
            // echo "<pre>";print_r(count($bestRateWebinars));die;
        }

        // hasDiscountWebinars
        if (in_array(HomeSection::$discount_classes, $selectedSectionsName)) {
            $now = time();
            $webinarIdsHasDiscount = [];

            $tickets = Ticket::where('start_date', '<', $now)
                ->where('end_date', '>', $now)
                ->get();

            foreach ($tickets as $ticket) {
                if ($ticket->isValid()) {
                    $webinarIdsHasDiscount[] = $ticket->webinar_id;
                }
            }

            $specialOffersWebinarIds = SpecialOffer::where('status', 'active')
                ->where('from_date', '<', $now)
                ->where('to_date', '>', $now)
                ->pluck('webinar_id')
                ->toArray();

            $webinarIdsHasDiscount = array_merge($specialOffersWebinarIds, $webinarIdsHasDiscount);

            $hasDiscountWebinars = Webinar::whereIn('id', array_unique($webinarIdsHasDiscount))
                ->where('status', Webinar::$active)
                ->where('private', false)
                ->with([
                    'teacher' => function ($qu) {
                        $qu->select('id', 'full_name', 'avatar');
                    },
                    'reviews' => function ($query) {
                        $query->where('status', 'active');
                    },
                    'sales',
                    'tickets',
                    'feature'
                ])
                ->limit(6)
                ->get();
        }
        // .\ hasDiscountWebinars

        if (in_array(HomeSection::$free_classes, $selectedSectionsName)) {
            $freeWebinars = Webinar::where('status', Webinar::$active)
                ->where('private', false)
                ->where(function ($query) {
                    $query->whereNull('price')
                        ->orWhere('price', '0');
                })
                ->orderBy('updated_at', 'desc')
                ->with([
                    'teacher' => function ($qu) {
                        $qu->select('id', 'full_name', 'avatar');
                    },
                    'reviews' => function ($query) {
                        $query->where('status', 'active');
                    },
                    'tickets',
                    'feature'
                ])
                ->limit(6)
                ->get();
        }

        if (in_array(HomeSection::$store_products, $selectedSectionsName)) {

            $productQuery = Product::where('products.status', Product::$active)
                ->with(['creator:id,full_name,avatar']);
            if ($isWisdomKeeper) {
                // Wisdom Keeper logic for products - only Wisdom Keeper products, sorted by highest reviews
                $newProducts = $productQuery
                    ->whereIn('products.creator_id', $wisdomKeeperIds)
                    ->leftJoin('product_reviews', 'products.id', '=', 'product_reviews.product_id')
                    ->leftJoin('product_like', 'products.id', '=', 'product_like.product_id')
                    ->select('products.*',
                        DB::raw('COUNT(DISTINCT product_like.id) as likes_count'),
                        DB::raw('AVG(product_reviews.rates) as avg_rating'),
                        DB::raw('COUNT(DISTINCT product_reviews.id) as review_count')
                    )
                    ->groupBy('products.id')
                    ->orderByRaw('
                        -- First priority: items with reviews (non-NULL ratings)
                        CASE 
                            WHEN AVG(product_reviews.rates) IS NOT NULL THEN 0
                            ELSE 1 
                        END,
                        -- Then order by highest rating
                        AVG(product_reviews.rates) DESC,
                        -- Then by number of reviews
                        COUNT(DISTINCT product_reviews.id) DESC,
                        -- Then by likes
                        COUNT(DISTINCT product_like.id) DESC,
                        -- Finally by updated date
                        products.updated_at DESC
                    ')
                    ->limit(6)
                    ->get()
                    ->map(function ($shop) {
                        if ($shop->creator && !empty($shop->creator->avatar)) {
                            $shop->creator->avatar = url($shop->creator->avatar);
                        }
                        $shop->image = url($shop->getImage());
                        return $shop;
                    });
            } else {
                // Guest/normal user logic for products
                $newProducts = $productQuery
                    ->leftJoin('product_reviews', 'products.id', '=', 'product_reviews.product_id')
                    ->leftJoin('product_like', 'products.id', '=', 'product_like.product_id')
                    ->select('products.*',
                        DB::raw('COUNT(DISTINCT product_like.id) as likes_count'),
                        DB::raw('COALESCE(AVG(product_reviews.rates), 0) as avg_rating'),
                        DB::raw('COUNT(DISTINCT product_reviews.id) as review_count')
                    )
                    ->groupBy('products.id')
                    ->orderByRaw('
                        COALESCE(AVG(product_reviews.rates), 0) DESC,
                        (COUNT(DISTINCT product_like.id) * 0.5 + 
                        COUNT(DISTINCT product_reviews.id) * 0.2) DESC,
                        products.updated_at DESC
                    ')
                    ->limit(6)
                    ->get()
                    ->map(function ($shop) {
                        if ($shop->creator && !empty($shop->creator->avatar)) {
                            $shop->creator->avatar = url($shop->creator->avatar);
                        }
                        $shop->image = url($shop->getImage());
                        return $shop;
                    });
            }
            // $newProducts = Product::where('status', Product::$active)
            //     ->orderBy('updated_at', 'desc')
            //     ->with([
            //         'creator' => function ($qu) {
            //             $qu->select('id', 'full_name', 'avatar');
            //         },
            //     ])
            //     ->limit(6)
            //     ->get();
        }

        if (in_array(HomeSection::$trend_categories, $selectedSectionsName)) {
            $trendCategories = TrendCategory::with([
                'category' => function ($query) {
                    $query->withCount([
                        'webinars' => function ($query) {
                            $query->where('status', 'active');
                        }
                    ]);
                }
            ])->orderBy('created_at', 'desc')
                ->get();
        }

        if (in_array(HomeSection::$blog, $selectedSectionsName)) {

            $articleQuery = Blog::where('blog.status', 'publish')
                ->with(['category', 'author:id,full_name'])
                ->withCount('comments');
            
            if ($isWisdomKeeper) {
                // Wisdom Keeper logic for articles - only Wisdom Keeper articles, sorted by highest reviews
                $blog = $articleQuery
                    ->whereIn('blog.author_id', $wisdomKeeperIds)
                    ->leftJoin('article_reviews', 'blog.id', '=', 'article_reviews.article_id')
                    ->leftJoin('article_like', 'blog.id', '=', 'article_like.article_id')
                    ->select('blog.*',
                        DB::raw('COUNT(DISTINCT article_like.id) as likes_count'),
                        DB::raw('AVG(article_reviews.rates) as avg_rating'),
                        DB::raw('COUNT(DISTINCT article_reviews.id) as review_count')
                    )
                    ->groupBy('blog.id')
                    ->orderByRaw('
                        -- First priority: items with reviews (non-NULL ratings)
                        CASE 
                            WHEN AVG(article_reviews.rates) IS NOT NULL THEN 0
                            ELSE 1 
                        END,
                        -- Then order by highest rating
                        AVG(article_reviews.rates) DESC,
                        -- Then by number of reviews
                        COUNT(DISTINCT article_reviews.id) DESC,
                        -- Then by likes
                        COUNT(DISTINCT article_like.id) DESC,
                        -- Finally by created date
                        blog.created_at DESC
                    ')
                    ->limit(6)
                    ->get()
                    ->map(function ($articles) {
                        $articles->image = !empty($articles->image) ? url($articles->image) : null;
                        return $articles;
                    });
            } else {
                // Guest/normal user logic for articles
                $blog = $articleQuery
                    ->leftJoin('article_reviews', 'blog.id', '=', 'article_reviews.article_id')
                    ->leftJoin('article_like', 'blog.id', '=', 'article_like.article_id')
                    ->select('blog.*',
                        DB::raw('COUNT(DISTINCT article_like.id) as likes_count'),
                        DB::raw('COALESCE(AVG(article_reviews.rates), 0) as avg_rating'),
                        DB::raw('COUNT(DISTINCT article_reviews.id) as review_count')
                    )
                    ->groupBy('blog.id')
                    ->orderByRaw('
                        COALESCE(AVG(article_reviews.rates), 0) DESC,
                        (COUNT(DISTINCT article_like.id) * 0.4 + 
                        COUNT(DISTINCT article_reviews.id) * 0.3) DESC,
                        blog.created_at DESC
                    ')
                    ->limit(6)
                    ->get()
                    ->map(function ($articles) {
                        $articles->image = !empty($articles->image) ? url($articles->image) : null;
                        return $articles;
                    });
            }
            // $blog = Blog::where('status', 'publish')
            //     ->with(['category', 'author' => function ($query) {
            //         $query->select('id', 'full_name');
            //     }])->orderBy('updated_at', 'desc')
            //     ->withCount('comments')
            //     ->orderBy('created_at', 'desc')
            //     ->limit(3)
            //     ->get();
        }

        if (in_array(HomeSection::$instructors, $selectedSectionsName)) {
            $instructors = User::where('role_name', Role::$teacher)
                ->select('id', 'full_name', 'avatar', 'bio')
                ->where('status', 'active')
                ->where(function ($query) {
                    $query->where('ban', false)
                        ->orWhere(function ($query) {
                            $query->whereNotNull('ban_end_at')
                                ->where('ban_end_at', '<', time());
                        });
                })
                ->limit(8)
                ->get();
        }

        if (in_array(HomeSection::$organizations, $selectedSectionsName)) {
            $organizations = User::where('role_name', Role::$organization)
                ->where('status', 'active')
                ->where(function ($query) {
                    $query->where('ban', false)
                        ->orWhere(function ($query) {
                            $query->whereNotNull('ban_end_at')
                                ->where('ban_end_at', '<', time());
                        });
                })
                ->withCount('webinars')
                ->orderBy('webinars_count', 'desc')
                ->limit(6)
                ->get();
        }

        if (in_array(HomeSection::$testimonials, $selectedSectionsName)) {
            $testimonials = Testimonial::where('status', 'active')->get();
        }

        if (in_array(HomeSection::$subscribes, $selectedSectionsName)) {
            $subscribes = Subscribe::all();

            $user = auth()->user();
            $installmentPlans = new InstallmentPlans($user);

            foreach ($subscribes as $subscribe) {
                if (getInstallmentsSettings('status') and (empty($user) or $user->enable_installments) and $subscribe->price > 0) {
                    $installments = $installmentPlans->getPlans('subscription_packages', $subscribe->id);

                    $subscribe->has_installment = (!empty($installments) and count($installments));
                }
            }
        }

        if (in_array(HomeSection::$find_instructors, $selectedSectionsName)) {
            $findInstructorSection = getFindInstructorsSettings();
        }

        if (in_array(HomeSection::$reward_program, $selectedSectionsName)) {
            $rewardProgramSection = getRewardProgramSettings();
        }


        if (in_array(HomeSection::$become_instructor, $selectedSectionsName)) {
            $becomeInstructorSection = getBecomeInstructorSectionSettings();
        }


        if (in_array(HomeSection::$forum_section, $selectedSectionsName)) {
            $forumSection = getForumSectionSettings();
        }

        $advertisingBanners = AdvertisingBanner::where('published', true)
            ->whereIn('position', ['home1', 'home2'])
            ->get();


        $siteGeneralSettings = getGeneralSettings();
        $heroSection = (!empty($siteGeneralSettings['hero_section2']) and $siteGeneralSettings['hero_section2'] == "1") ? "2" : "1";
        $heroSectionData = getHomeHeroSettings($heroSection);

        if (in_array(HomeSection::$video_or_image_section, $selectedSectionsName)) {
            $boxVideoOrImage = getHomeVideoOrImageBoxSettings();
        }

        $seoSettings = getSeoMetas('home');
        $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('home.home_title');
        $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('home.home_title');
        $pageRobot = getPageRobot('home');

        $statisticsSettings = getStatisticsSettings();

        $homeDefaultStatistics = null;
        $homeCustomStatistics = null;

        if (!empty($statisticsSettings['enable_statistics'])) {
            if (!empty($statisticsSettings['display_default_statistics'])) {
                $homeDefaultStatistics = $this->getHomeDefaultStatistics();
            } else {
                $homeCustomStatistics = HomePageStatistic::query()->orderBy('order', 'asc')->limit(4)->get();
            }
        }

        // $reels= Reel::where('is_hidden','0')->orderby('id','desc')->limit(6)->get();
        // $books= Book::orderby('id','desc')->limit(6)->get();

        $reelQuery = Reel::where('is_hidden', '0');

        if ($isWisdomKeeper) {
            // Wisdom Keeper logic for reels - only Wisdom Keeper reels, sorted by highest reviews
            $reels = $reelQuery
                ->whereIn('reels.user_id', $wisdomKeeperIds)
                ->leftJoin('reel_review', 'reels.id', '=', 'reel_review.reel_id')
                ->leftJoin('reel_likes', 'reels.id', '=', 'reel_likes.reel_id')
                ->select('reels.*',
                    DB::raw('COUNT(DISTINCT reel_likes.id) as likes_count'),
                    DB::raw('AVG(reel_review.rating) as avg_rating'),
                    DB::raw('COUNT(DISTINCT reel_review.id) as review_count')
                )
                ->groupBy('reels.id')
                ->orderByRaw('
                    -- First priority: items with reviews (non-NULL ratings)
                    CASE 
                        WHEN AVG(reel_review.rating) IS NOT NULL THEN 0
                        ELSE 1 
                    END,
                    -- Then order by highest rating
                    AVG(reel_review.rating) DESC,
                    -- Then by number of reviews
                    COUNT(DISTINCT reel_review.id) DESC,
                    -- Then by likes
                    COUNT(DISTINCT reel_likes.id) DESC,
                    -- Finally by id
                    reels.id DESC
                ')
                ->limit(6)
                ->get();
        } else {
            // Guest/normal user logic for reels
            $reels = $reelQuery
                ->leftJoin('reel_review', 'reels.id', '=', 'reel_review.reel_id')
                ->leftJoin('reel_likes', 'reels.id', '=', 'reel_likes.reel_id')
                ->select('reels.*',
                    DB::raw('COUNT(DISTINCT reel_likes.id) as likes_count'),
                    DB::raw('COALESCE(AVG(reel_review.rating), 0) as avg_rating'),
                    DB::raw('COUNT(DISTINCT reel_review.id) as review_count')
                )
                ->groupBy('reels.id')
                ->orderByRaw('
                    COALESCE(AVG(reel_review.rating), 0) DESC,
                    (COUNT(DISTINCT reel_likes.id) * 0.4 + 
                    COUNT(DISTINCT reel_review.id) * 0.3) DESC,
                    reels.id DESC
                ')
                ->limit(6)
                ->get();
        }

        // Books
        $bookQuery = Book::query();

        if ($isWisdomKeeper) {
            // Wisdom Keeper logic for books - only Wisdom Keeper books, sorted by highest reviews
            $books = $bookQuery
                ->whereIn('book.creator_id', $wisdomKeeperIds)
                ->leftJoin('book_review', 'book.id', '=', 'book_review.book_id')
                ->leftJoin('book_like', 'book.id', '=', 'book_like.book_id')
                ->select('book.*',
                    DB::raw('COUNT(DISTINCT book_like.id) as likes_count'),
                    DB::raw('AVG(book_review.rating) as avg_rating'),
                    DB::raw('COUNT(DISTINCT book_review.id) as review_count')
                )
                ->groupBy('book.id')
                ->orderByRaw('
                    -- First priority: items with reviews (non-NULL ratings)
                    CASE 
                        WHEN AVG(book_review.rating) IS NOT NULL THEN 0
                        ELSE 1 
                    END,
                    -- Then order by highest rating
                    AVG(book_review.rating) DESC,
                    -- Then by number of reviews
                    COUNT(DISTINCT book_review.id) DESC,
                    -- Then by likes
                    COUNT(DISTINCT book_like.id) DESC,
                    -- Finally by id
                    book.id DESC
                ')
                ->limit(6)
                ->get()
                ->map(function ($books) {
                    $books->image_cover = !empty($books->image_cover) ? url($books->image_cover) : null;
                    $books->url = !empty($books->url) ? url($books->url) : null;
                    return $books;
                });
        } else {
            // Guest/normal user logic for books
            $books = $bookQuery
                ->leftJoin('book_review', 'book.id', '=', 'book_review.book_id')
                ->leftJoin('book_like', 'book.id', '=', 'book_like.book_id')
                ->select('book.*',
                    DB::raw('COUNT(DISTINCT book_like.id) as likes_count'),
                    DB::raw('COALESCE(AVG(book_review.rating), 0) as avg_rating'),
                    DB::raw('COUNT(DISTINCT book_review.id) as review_count')
                )
                ->groupBy('book.id')
                ->orderByRaw('
                    COALESCE(AVG(book_review.rating), 0) DESC,
                    (COUNT(DISTINCT book_like.id) * 0.4 + 
                    COUNT(DISTINCT book_review.id) * 0.3) DESC,
                    book.id DESC
                ')
                ->limit(6)
                ->get()
                ->map(function ($books) {
                    $books->image_cover = !empty($books->image_cover) ? url($books->image_cover) : null;
                    $books->url = !empty($books->url) ? url($books->url) : null;
                    return $books;
                });
        }

        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'heroSection' => $heroSection,
            'heroSectionData' => $heroSectionData,
            'homeSections' => $homeSections,
            'featureWebinars' => $featureWebinars,
            'latestWebinars' => $latestWebinars ?? [],
            'latestBundles' => $latestBundles ?? [],
            'upcomingCourses' => $upcomingCourses ?? [],
            'bestSaleWebinars' => $bestSaleWebinars ?? [],
            'hasDiscountWebinars' => $hasDiscountWebinars ?? [],
            'bestRateWebinars' => $bestRateWebinars ?? [],
            'freeWebinars' => $freeWebinars ?? [],
            'newProducts' => $newProducts ?? [],
            'trendCategories' => $trendCategories ?? [],
            'instructors' => $instructors ?? [],
            'testimonials' => $testimonials ?? [],
            'subscribes' => $subscribes ?? [],
            'blog' => $blog ?? [],
            'reels' => $reels ?? [],
            'books' => $books ?? [],
            'organizations' => $organizations ?? [],
            'advertisingBanners1' => $advertisingBanners->where('position', 'home1'),
            'advertisingBanners2' => $advertisingBanners->where('position', 'home2'),
            'homeDefaultStatistics' => $homeDefaultStatistics,
            'homeCustomStatistics' => $homeCustomStatistics,
            'boxVideoOrImage' => $boxVideoOrImage ?? null,
            'findInstructorSection' => $findInstructorSection ?? null,
            'rewardProgramSection' => $rewardProgramSection ?? null,
            'becomeInstructorSection' => $becomeInstructorSection ?? null,
            'forumSection' => $forumSection ?? null,
        ];
        
        return view(getTemplate() . '.pages.home', $data);
    }

    public function membership()
    {
        $subscribes = Subscribe::all();

        $user = auth()->user();
        $installmentPlans = new InstallmentPlans($user);

        $activeSubscribe = null;

        if ($user) {
            $activeSubscribe = Subscribe::getActiveSubscribe($user->id);
        }

        // dd($activeSubscribe);

        foreach ($subscribes as $subscribe) {
            if (getInstallmentsSettings('status') and (empty($user) or $user->enable_installments) and $subscribe->price > 0) {
                $installments = $installmentPlans->getPlans('subscription_packages', $subscribe->id);

                $subscribe->has_installment = (!empty($installments) and count($installments));
            }
        }
        $data = [
            'subscribes' => $subscribes ?? [],
            'activeSubscribe' => $activeSubscribe
        ];

        return view(getTemplate() . '.pages.membership', $data);
    }

    public function cancelSubscription(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No active subscription found'
            ], 404);
        }

        $subscriptionId = $request->input('subscription_id');
        // dd($subscriptionId);
        DB::beginTransaction();

        try {   

            $activeSubscribe = Subscribe::getActiveSubscribe($user->id);

            if (!$activeSubscribe) {
                return apiResponse2(0, 'no_active_subscription', trans('site.no_active_subscription'));
            }

            try {

                \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        
                $customer = \Stripe\Customer::retrieve($user->stripe_customer_id);
                
                if ($customer) {
                    // Cancel any active subscriptions
                    $subscriptions = \Stripe\Subscription::all([
                        'customer' => $user->stripe_customer_id,
                        'status' => 'active'
                    ]);
                    
                    foreach ($subscriptions->data as $subscription) {
                        $subscription->cancel();
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Stripe cancellation error: ' . $e->getMessage());
            }
            // dd('hi1');
            /** 3️⃣ Disable subscription usage */
           SubscribeUse::where('user_id', $user->id)
            ->where('subscribe_id', $subscriptionId)
            ->delete();

            /** 4️⃣ Update sales table */
            Sale::where('buyer_id',  (string) $user->id)
                ->where('type','subscribe')
                ->whereNull('refund_at')
                // ->delete();
                ->update([
                    'refund_at' => time(),
                    // 'status' => 'canceled'
                ]);

            /** 5️⃣ Accounting cleanup */
            // Accounting::where('user_id', $user->id)
            //     ->where('type', 'subscribe')
            //     ->delete();

            /** 6️⃣ Update related orders */
            $orderIds = OrderItem::where('user_id', $user->id)
                ->whereNotNull('subscribe_id')
                ->pluck('order_id');

            Order::whereIn('id', $orderIds)
                ->update([
                    'status' => 'fail'
                ]);

            /** 7️⃣ Update user table */
            $user->update([
                'subscription_status' => 'canceled',
                'subscription_id' => null,
                'stripe_customer_id' => null
            ]);

            DB::commit();

            $notifyOptions = [
                '[u.name]' => $user->full_name,
                '[s.p.name]' => $activeSubscribe->title,
            ];
            
            sendNotification('subscripe_plan_cancel', $notifyOptions, $user->id);

            return response()->json([
                'success' => true,
                'message' => 'Subscription cancelled successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Subscription Cancel Error', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel subscription',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getHomeDefaultStatistics()
    {
        $skillfulTeachersCount = User::where('role_name', Role::$teacher)
            ->where(function ($query) {
                $query->where('ban', false)
                    ->orWhere(function ($query) {
                        $query->whereNotNull('ban_end_at')
                            ->where('ban_end_at', '<', time());
                    });
            })
            ->where('status', 'active')
            ->count();

        $studentsCount = User::where('role_name', Role::$user)
            ->where(function ($query) {
                $query->where('ban', false)
                    ->orWhere(function ($query) {
                        $query->whereNotNull('ban_end_at')
                            ->where('ban_end_at', '<', time());
                    });
            })
            ->where('status', 'active')
            ->count();

        $liveClassCount = Webinar::where('type', 'webinar')
            ->where('status', 'active')
            ->count();

        $offlineCourseCount = Webinar::where('status', 'active')
            ->whereIn('type', ['course', 'text_lesson'])
            ->count();

        return [
            'skillfulTeachersCount' => $skillfulTeachersCount,
            'studentsCount' => $studentsCount,
            'liveClassCount' => $liveClassCount,
            'offlineCourseCount' => $offlineCourseCount,
        ];
    }
}
