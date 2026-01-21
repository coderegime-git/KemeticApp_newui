<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\FileResource;
use App\Http\Resources\SessionResource;
use App\Http\Resources\TextLessonResource;
use App\Http\Resources\WebinarChapterResource;
use App\Mixins\Cashback\CashbackRules;
use App\Models\Favorite;
use App\Models\CourseLearning;
use App\Models\Ticket;
use App\Models\Region;
use App\Models\Country;
use App\Models\Category;
use App\Models\Api\Book;
use App\Models\Livestream;
use App\Models\Api\FeatureWebinar;
use App\Models\Api\Webinar;
use App\Models\WebinarChapter;
use App\Models\WebinarFilterOption;
use App\Models\WebinarReport;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Cache;
use App\Models\{
    HomeSection, Bundle, UpcomingCourse, Reel,
    Sale, SpecialOffer, Product, TrendCategory, Blog, Testimonial, Subscribe, AdvertisingBanner, HomePageStatistic
};
use App\Mixins\Installment\InstallmentPlans;
use App\Models\Role;
use App\User;


class WebinarController extends Controller
{


    public function index()
    {
        // $webinars = Webinar::where('webinars.status', 'active')
        //     ->with([
        //         "badges" => function ($query) {
        //             $query->where('targetable_type', 'App\Models\Webinar');
        //             $query->with([
        //                 'badge' => function ($query) {
        //                     $time = time();
        //                     $query->where('enable', true);

        //                     $query->where(function ($query) use ($time) {
        //                         $query->whereNull('start_at');
        //                         $query->orWhere('start_at', '<', $time);
        //                     });

        //                     $query->where(function ($query) use ($time) {
        //                         $query->whereNull('end_at');
        //                         $query->orWhere('end_at', '>', $time);
        //                     });
        //                 }
        //             ]);
        //         },
        //     ])
        //     ->whereHas('teacher', function ($query) {
        //         $query->where('status', 'active')
        //             ->where(function ($query) {
        //                 $query->where('ban', false)
        //                     ->orWhere(function ($query) {
        //                         $query->whereNotNull('ban_end_at')
        //                             ->where('ban_end_at', '<', time());
        //                     });
        //             });
        //     })
        //     ->where('private', false)
        //     ->handleFilters()
        //     ->get()->map(function ($webinar) {
        //         return $webinar->brief;
        //     });
        $user = apiAuth();
        //print_r($user);die;

        $webinars = Webinar::where('webinars.status', 'active')
            ->with([
                "badges" => function ($query) {
                    $query->where('targetable_type', 'App\Models\Webinar');
                    $query->with([
                        'badge' => function ($query) {
                            $time = time();
                            $query->where('enable', true);

                            $query->where(function ($query) use ($time) {
                                $query->whereNull('start_at');
                                $query->orWhere('start_at', '<', $time);
                            });

                            $query->where(function ($query) use ($time) {
                                $query->whereNull('end_at');
                                $query->orWhere('end_at', '>', $time);
                            });
                        },

                    ]);
                }
                
            ])
            ->leftJoin('apple_product_table', 'webinars.reference_id', '=', 'apple_product_table.id')
            ->select(
                'webinars.*',
                'apple_product_table.reference_name',
                'apple_product_table.product_id',
                'apple_product_table.display_name',
                'apple_product_table.description as product_description',
                'apple_product_table.type',
            )
            ->orderBy('id', 'desc')
            ->handleFilters()
            ->get()
            ->groupBy('id')
            ->map(function ($group) {
                $webinar = $group->first(); // Webinar data
    
                // Create the products array
                $products = $group->map(function ($item) {
                    return [
                        'reference_name' => $item->reference_name,
                        'product_id' => $item->product_id,
                        'display_name' => $item->display_name,
                        'description' => $item->product_description, // Clean description
                        'type' => $item->type,
                    ];
                })->filter(function ($product) {
                    return $product['product_id'] !== null; // Filter out null product rows
                })->values()->toArray();

                // Add the products array as a top-level field in the webinar
                $webinar->product = $products ?: []; // Add products to the webinar object
                $thumbnailImage =  url($webinar->thumbnail);
                $coverImage = url($webinar->image_cover);
                $webinar->thumbnail = $thumbnailImage;
                $webinar->image = $coverImage;
                // Unset the extra fields from the webinar object to avoid duplicates
                unset($webinar->reference_name, $webinar->product_id, $webinar->display_name, $webinar->product_description, $webinar->type);

                return $webinar;
            })
            
            ->flatten(1); // Flatten the result into a single-level collection
 

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $webinars);

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

    // public function home(Request $request)
    // {
    //     $userId = $this->getUserIdFromToken($request);

    //     $homeSections = HomeSection::orderBy('order', 'asc')->get();
    //     $selectedSectionsName = $homeSections->pluck('name')->toArray();

    //     $data = [];

    //     // Featured Webinars
    //     // if (in_array(HomeSection::$featured_classes, $selectedSectionsName)) {
    //     //     $data['featureWebinars'] = FeatureWebinar::whereIn('page', ['home', 'home_categories'])
    //     //         ->where('status', 'publish')
    //     //         ->whereHas('webinar', fn($q) => $q->where('status', Webinar::$active))
    //     //         ->with(['webinar.teacher:id,full_name,avatar', 'webinar.reviews' => fn($q) => $q->where('status', 'active'), 'webinar.tickets', 'webinar.feature'])
    //     //         ->orderBy('updated_at', 'desc')
    //     //         ->get();
    //     // }

    //     // Latest Webinars
    //     if (in_array(HomeSection::$latest_classes, $selectedSectionsName)) {
    //         $data['course'] = Webinar::where('status', Webinar::$active)
    //             ->where('private', false)
    //             ->orderBy('updated_at', 'desc')
    //             ->with(['teacher:id,full_name,avatar', 'reviews' => fn($q) => $q->where('status', 'active'), 'tickets', 'feature'])
    //             ->limit(6)
    //             ->get()
    //             ->map(function ($course) {
    //                 $course->thumbnail = url($course->thumbnail);
    //                 $course->image_cover = url($course->image_cover);
    //                 $course->teacher->avatar = url($course->teacher->avatar);
    //                 return $course;
    //             });

    //     }

    //     // Latest Bundles
    //     // if (in_array(HomeSection::$latest_bundles, $selectedSectionsName)) {
    //     //     $data['latestBundles'] = Bundle::where('status', Webinar::$active)
    //     //         ->orderBy('updated_at', 'desc')
    //     //         ->with(['teacher:id,full_name,avatar', 'reviews' => fn($q) => $q->where('status', 'active'), 'tickets'])
    //     //         ->limit(6)
    //     //         ->get();
    //     // }

    //     // // Upcoming Courses
    //     // if (in_array(HomeSection::$upcoming_courses, $selectedSectionsName)) {
    //     //     $data['upcomingCourses'] = UpcomingCourse::where('status', Webinar::$active)
    //     //         ->orderBy('created_at', 'desc')
    //     //         ->with(['teacher:id,full_name,avatar'])
    //     //         ->limit(6)
    //     //         ->get();
    //     // }

    //     // Best Sellers
    //     if (in_array(HomeSection::$best_sellers, $selectedSectionsName)) {
    //         $bestSaleWebinarsIds = Sale::whereNotNull('webinar_id')
    //             ->select(DB::raw('COUNT(id) as cnt, webinar_id'))
    //             ->groupBy('webinar_id')
    //             ->orderBy('cnt', 'DESC')
    //             ->limit(1)
    //             ->pluck('webinar_id')
    //             ->toArray();

    //         $data['topsale'] = Webinar::whereIn('id', $bestSaleWebinarsIds)
    //             ->where('status', Webinar::$active)
    //             ->where('private', false)
    //             ->with(['teacher:id,full_name,avatar', 'reviews' => fn($q) => $q->where('status', 'active'), 'sales', 'tickets', 'feature'])
    //             ->get()
    //             ->map(function ($topsale) {
    //                 $topsale->thumbnail = url($topsale->thumbnail);
    //                 $topsale->image_cover = url($topsale->image_cover);
    //                 $topsale->teacher->avatar = url($topsale->teacher->avatar);
    //                 return $topsale;
    //             });
    //     }

    //     // Best Rated
    //     if (in_array(HomeSection::$best_rates, $selectedSectionsName)) {
    //         $data['toprate'] = Webinar::join('webinar_reviews', 'webinars.id', '=', 'webinar_reviews.webinar_id')
    //             ->select('webinars.*', DB::raw('avg(webinar_reviews.rates) as avg_rates'))
    //             ->where('webinars.status', 'active')
    //             ->where('webinars.private', false)
    //             ->where('webinar_reviews.status', 'active')
    //             ->groupBy('webinars.id')
    //             ->orderBy('avg_rates', 'desc')
    //             ->with(['teacher:id,full_name,avatar'])
    //             ->limit(1)
    //             ->get()
    //             ->map(function ($toprate) {
    //                 $toprate->thumbnail = url($toprate->thumbnail);
    //                 $toprate->image_cover = url($toprate->image_cover);
    //                 $toprate->teacher->avatar = url($toprate->teacher->avatar);
    //                 return $toprate;
    //             });
    //     }

    //     // Discounted Webinars
    //     // if (in_array(HomeSection::$discount_classes, $selectedSectionsName)) {
    //     //     $now = time();
    //     //     $webinarIdsHasDiscount = [];

    //     //     $tickets = Ticket::where('start_date', '<', $now)->where('end_date', '>', $now)->get();
    //     //     foreach ($tickets as $ticket) {
    //     //         if ($ticket->isValid()) $webinarIdsHasDiscount[] = $ticket->webinar_id;
    //     //     }

    //     //     $specialOffersWebinarIds = SpecialOffer::where('status', 'active')
    //     //         ->where('from_date', '<', $now)
    //     //         ->where('to_date', '>', $now)
    //     //         ->pluck('webinar_id')
    //     //         ->toArray();

    //     //     $webinarIdsHasDiscount = array_merge($specialOffersWebinarIds, $webinarIdsHasDiscount);

    //     //     $data['hasDiscountWebinars'] = Webinar::whereIn('id', array_unique($webinarIdsHasDiscount))
    //     //         ->where('status', Webinar::$active)
    //     //         ->where('private', false)
    //     //         ->with(['teacher:id,full_name,avatar', 'reviews' => fn($q) => $q->where('status', 'active'), 'sales', 'tickets', 'feature'])
    //     //         ->limit(6)
    //     //         ->get();
    //     // }

    //     // Free Webinars
    //     // if (in_array(HomeSection::$free_classes, $selectedSectionsName)) {
    //     //     $data['freeWebinars'] = Webinar::where('status', Webinar::$active)
    //     //         ->where('private', false)
    //     //         ->where(fn($q) => $q->whereNull('price')->orWhere('price', '0'))
    //     //         ->orderBy('updated_at', 'desc')
    //     //         ->with(['teacher:id,full_name,avatar', 'reviews' => fn($q) => $q->where('status', 'active'), 'tickets', 'feature'])
    //     //         ->limit(6)
    //     //         ->get();
    //     // }

    //     // Store Products
    //     if (in_array(HomeSection::$store_products, $selectedSectionsName)) {
    //         $data['shop'] = Product::where('status', Product::$active)
    //             ->orderBy('updated_at', 'desc')
    //             ->with(['creator:id,full_name,avatar'])
    //             ->limit(6)
    //             ->get()
    //             ->map(function ($shop) {
    //                 $shop->creator->avatar = url($shop->creator->avatar);
    //                 return $shop;
    //             });
    //     }

    //     // Trend Categories
    //     // if (in_array(HomeSection::$trend_categories, $selectedSectionsName)) {
    //     //     $data['trendCategories'] = TrendCategory::with(['category' => fn($q) => $q->withCount(['webinars' => fn($w) => $w->where('status', 'active')])])
    //     //         ->orderBy('created_at', 'desc')
    //     //         ->get();
    //     // }

    //     // Blogs
    //     if (in_array(HomeSection::$blog, $selectedSectionsName)) {
    //         $data['articles'] = Blog::where('status', 'publish')
    //             ->with(['category', 'author:id,full_name'])
    //             ->withCount('comments')
    //             ->orderBy('created_at', 'desc')
    //             ->limit(3)
    //             ->get()
    //             ->map(function ($articles) {
    //                 $articles->image = url($articles->image);
    //                 return $articles;
    //             });
    //     }

    //     // Instructors
    //     // if (in_array(HomeSection::$instructors, $selectedSectionsName)) {
    //     //     $data['instructors'] = User::where('role_name', Role::$teacher)
    //     //         ->select('id', 'full_name', 'avatar', 'bio')
    //     //         ->where('status', 'active')
    //     //         ->where(fn($q) => $q->where('ban', false)->orWhere(fn($sub) => $sub->whereNotNull('ban_end_at')->where('ban_end_at', '<', time())))
    //     //         ->limit(8)
    //     //         ->get();
    //     // }

    //     // Organizations
    //     // if (in_array(HomeSection::$organizations, $selectedSectionsName)) {
    //     //     $data['organizations'] = User::where('role_name', Role::$organization)
    //     //         ->where('status', 'active')
    //     //         ->where(fn($q) => $q->where('ban', false)->orWhere(fn($sub) => $sub->whereNotNull('ban_end_at')->where('ban_end_at', '<', time())))
    //     //         ->withCount('webinars')
    //     //         ->orderBy('webinars_count', 'desc')
    //     //         ->limit(6)
    //     //         ->get();
    //     // }

    //     // // Testimonials
    //     // if (in_array(HomeSection::$testimonials, $selectedSectionsName)) {
    //     //     $data['testimonials'] = Testimonial::where('status', 'active')->get();
    //     // }

    //     $data['reels'] = Reel::where('is_hidden','0')->orderby('id','desc')->limit(6)->get();
    //     $data['books'] = Book::orderby('id','desc')->limit(6)->get()
    //     ->map(function ($books) {
    //         $books->image_cover = url($books->image_cover);
    //         $books->url = url($books->url);
    //         return $books;
    //     });

    //     if($userId){
    //         $data['livestream'] = Livestream::where('creator_id', $userId)
    //         ->orderBy('created_at', 'desc')
    //         ->get();
    //     }else{
    //         $data['livestream'] = [];
    //     }

    //     // Advertising Banners
    //     //$banners = AdvertisingBanner::where('published', true)->whereIn('position', ['home1', 'home2'])->get();
    //     //$data['advertisingBanners1'] = $banners->where('position', 'home1')->values();
    //     //$data['advertisingBanners2'] = $banners->where('position', 'home2')->values();

    //     // Response
    //     return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $data);

    // }

    public function country(Request $request)
    {
        $countries = Region::select(DB::raw('*, ST_AsText(geo_center) as geo_center'))
            ->where('type', Region::$country)
            ->get();

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $countries);
    }

    public function home(Request $request)
    {
        $userId = $this->getUserIdFromToken($request);

        $homeSections = HomeSection::orderBy('order', 'asc')->get();
        $selectedSectionsName = $homeSections->pluck('name')->toArray();

        $data = [];

        // Latest Webinars
        if (in_array(HomeSection::$latest_classes, $selectedSectionsName)) {
            $data['course'] = Webinar::where('status', Webinar::$active)
                ->where('private', false)
                ->orderBy('updated_at', 'desc')
                ->with(['teacher:id,full_name,avatar', 'reviews' => fn($q) => $q->where('status', 'active'), 'tickets', 'feature'])
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

        // Best Sellers
        if (in_array(HomeSection::$best_sellers, $selectedSectionsName)) {
            $bestSaleWebinarsIds = Sale::whereNotNull('webinar_id')
                ->select(DB::raw('COUNT(id) as cnt, webinar_id'))
                ->groupBy('webinar_id')
                ->orderBy('cnt', 'DESC')
                ->limit(1)
                ->pluck('webinar_id')
                ->toArray();

            $data['topsale'] = Webinar::whereIn('id', $bestSaleWebinarsIds)
                ->where('status', Webinar::$active)
                ->where('private', false)
                ->with(['teacher:id,full_name,avatar', 'reviews' => fn($q) => $q->where('status', 'active'), 'sales', 'tickets', 'feature'])
                ->get()
                ->map(function ($topsale) {
                    $topsale->thumbnail = !empty($topsale->thumbnail) ? url($topsale->thumbnail) : null;
                    $topsale->image_cover = !empty($topsale->image_cover) ? url($topsale->image_cover) : null;
                    
                    if ($topsale->teacher && !empty($topsale->teacher->avatar)) {
                        $topsale->teacher->avatar = url($topsale->teacher->avatar);
                    }
                    
                    return $topsale;
                });
        }

        // Best Rated
        if (in_array(HomeSection::$best_rates, $selectedSectionsName)) {
            $data['toprate'] = Webinar::join('webinar_reviews', 'webinars.id', '=', 'webinar_reviews.webinar_id')
                ->select('webinars.*', DB::raw('avg(webinar_reviews.rates) as avg_rates'))
                ->where('webinars.status', 'active')
                ->where('webinars.private', false)
                ->where('webinar_reviews.status', 'active')
                ->groupBy('webinars.id')
                ->orderBy('avg_rates', 'desc')
                ->with(['teacher:id,full_name,avatar'])
                ->limit(1)
                ->get()
                ->map(function ($toprate) {
                    $toprate->thumbnail = !empty($toprate->thumbnail) ? url($toprate->thumbnail) : null;
                    $toprate->image_cover = !empty($toprate->image_cover) ? url($toprate->image_cover) : null;
                    
                    if ($toprate->teacher && !empty($toprate->teacher->avatar)) {
                        $toprate->teacher->avatar = url($toprate->teacher->avatar);
                    }
                    
                    return $toprate;
                });
        }

        // Store Products
        if (in_array(HomeSection::$store_products, $selectedSectionsName)) {
            $data['shop'] = Product::where('status', Product::$active)
                ->orderBy('updated_at', 'desc')
                ->with(['creator:id,full_name,avatar'])
                ->limit(6)
                ->get()
                ->map(function ($shop) {
                    if ($shop->creator && !empty($shop->creator->avatar)) {
                        $shop->creator->avatar = url($shop->creator->avatar);
                    }
                    return $shop;
                });
        }

        // Blogs
        if (in_array(HomeSection::$blog, $selectedSectionsName)) {
            $data['articles'] = Blog::where('status', 'publish')
                ->with(['category', 'author:id,full_name'])
                ->withCount('comments')
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get()
                ->map(function ($articles) {
                    $articles->image = !empty($articles->image) ? url($articles->image) : null;
                    return $articles;
                });
        }

        $data['reels'] = Reel::where('is_hidden','0')->orderby('id','desc')->limit(6)->get();
        $data['books'] = Book::orderby('id','desc')->limit(6)->get()
        ->map(function ($books) {
            $books->image_cover = !empty($books->image_cover) ? url($books->image_cover) : null;
            $books->url = !empty($books->url) ? url($books->url) : null;
            return $books;
        });

        if($userId){
            $data['livestream'] = Livestream::where('creator_id', $userId)
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
        }else{
            $data['livestream'] = [];
        }

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $data);
    }

    public function getWebinarsCombined(Request $request)
    {
        $data = [];
        $sort = $request->get('sort', 'newest');
        $categorySlug = $request->get('category', null);
        $isFree = filter_var($request->get('free', false), FILTER_VALIDATE_BOOLEAN);
        $categoryId = null;

        // Get category ID if slug is provided
        if ($categorySlug) {
            $category = Category::where('id', $categorySlug)->first();
            if ($category) {
                $categoryId = $category->id;
            }
        }

        // Base query for regular webinars with filters
        $webinarsQuery = Webinar::where('status', Webinar::$active)
            ->where('private', false);

        // Apply category filter
        if ($categoryId) {
            $webinarsQuery->where('category_id', $categoryId);
        }

        // Apply free filter
        if ($isFree) {
            $webinarsQuery->where(function($query) {
                $query->where('price', 0)
                    ->orWhere('price', null);
            });
        }

        // Apply sorting
        switch ($sort) {
            case 'newest':
                $webinarsQuery->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $webinarsQuery->orderBy('created_at', 'asc');
                break;
            default:
                $webinarsQuery->orderBy('created_at', 'desc');
        }

        // Get regular webinars
        $webinars = $webinarsQuery->with([
            'teacher:id,full_name,avatar', 
            'reviews' => fn($q) => $q->where('status', 'active'), 
            'tickets', 
            'feature'
        ])
        // ->limit(6)
        ->get()
        ->map(function ($course) {
            // Add proper null checking for thumbnail
            if (!empty($course->thumbnail)) {
                $course->thumbnail = url($course->thumbnail);
            }
            
            // Add proper null checking for image_cover
            if (!empty($course->image_cover)) {
                $course->image_cover = url($course->image_cover);
            }
            
            // Add proper null checking for teacher avatar
            if ($course->teacher && !empty($course->teacher->avatar)) {
                $course->teacher->avatar = url($course->teacher->avatar);
            }
            
            return $course;
        });
        
        $bestSaleWebinarsIds = Sale::whereNotNull('webinar_id')
        ->select(DB::raw('COUNT(id) as cnt,webinar_id'))
        ->groupBy('webinar_id')
        ->orderBy('cnt', 'DESC')
        ->limit(1)
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
        ->limit(1)
        ->get()
        ->map(function ($bestSaleWebinars) {
            // Add proper null checking
            if (!empty($bestSaleWebinars->thumbnail)) {
                $bestSaleWebinars->thumbnail = url($bestSaleWebinars->thumbnail);
            }
            
            if (!empty($bestSaleWebinars->image_cover)) {
                $bestSaleWebinars->image_cover = url($bestSaleWebinars->image_cover);
            }
            
            if ($bestSaleWebinars->teacher && !empty($bestSaleWebinars->teacher->avatar)) {
                $bestSaleWebinars->teacher->avatar = url($bestSaleWebinars->teacher->avatar);
            }
            
            return $bestSaleWebinars;
        });

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
        ->get()
        ->map(function ($bestRateWebinars) {
            // Add proper null checking
            if (!empty($bestRateWebinars->thumbnail)) {
                $bestRateWebinars->thumbnail = url($bestRateWebinars->thumbnail);
            }
            
            if (!empty($bestRateWebinars->image_cover)) {
                $bestRateWebinars->image_cover = url($bestRateWebinars->image_cover);
            }
            
            if ($bestRateWebinars->teacher && !empty($bestRateWebinars->teacher->avatar)) {
                $bestRateWebinars->teacher->avatar = url($bestRateWebinars->teacher->avatar);
            }
            
            return $bestRateWebinars;
        });

        $liveClassesQuery = Webinar::where('status', 'active')
            ->where('type', 'webinar')
            ->where('private', false);

        // Apply category filter to live classes if needed
        if ($categoryId) {
            $liveClassesQuery->where('category_id', $categoryId);
        }

        // Apply free filter to live classes if needed
        if ($isFree) {
            $liveClassesQuery->where(function($query) {
                $query->where('price', 0)
                    ->orWhere('price', null);
            });
        }

        // Newest Live Classes
        $newestLiveClasses = clone $liveClassesQuery;
        $newestLiveClasses = $newestLiveClasses->orderBy('created_at', 'desc')
            ->with([
                'teacher:id,full_name,avatar', 
                'reviews' => fn($q) => $q->where('status', 'active'), 
                'tickets', 
                'feature'
            ])
            ->get()
            ->map(function ($liveClass) {
                // Add proper null checking
                if (!empty($liveClass->thumbnail)) {
                    $liveClass->thumbnail = url($liveClass->thumbnail);
                }
                
                if (!empty($liveClass->image_cover)) {
                    $liveClass->image_cover = url($liveClass->image_cover);
                }
                
                if ($liveClass->teacher && !empty($liveClass->teacher->avatar)) {
                    $liveClass->teacher->avatar = url($liveClass->teacher->avatar);
                }
                
                return $liveClass;
            });

        // Top Rated Live Classes
        $topRatedLiveClasses = Webinar::join('webinar_reviews', 'webinars.id', '=', 'webinar_reviews.webinar_id')
            ->select('webinars.*', 'webinar_reviews.rates', 'webinar_reviews.status', DB::raw('avg(rates) as avg_rates'))
            ->where('webinars.status', 'active')
            ->where('webinars.type', 'webinar')
            ->where('webinars.private', false)
            ->where('webinar_reviews.status', 'active')
            ->groupBy('webinars.id')
            ->orderBy('avg_rates', 'desc')
            ->with([
                'teacher:id,full_name,avatar', 
                'reviews' => fn($q) => $q->where('status', 'active'), 
                'tickets', 
                'feature'
            ])
            ->get()
            ->map(function ($liveClass) {
                // Add proper null checking
                if (!empty($liveClass->thumbnail)) {
                    $liveClass->thumbnail = url($liveClass->thumbnail);
                }
                
                if (!empty($liveClass->image_cover)) {
                    $liveClass->image_cover = url($liveClass->image_cover);
                }
                
                if ($liveClass->teacher && !empty($liveClass->teacher->avatar)) {
                    $liveClass->teacher->avatar = url($liveClass->teacher->avatar);
                }
                
                return $liveClass;
            });

        $data['course'] = $webinars;
        $data['bestrate'] = $bestRateWebinars;
        $data['bestsale'] = $bestSaleWebinars;
        $data['newest_live_classes'] = $newestLiveClasses;
        $data['top_rated_live_classes'] = $topRatedLiveClasses;

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $data);
    }

    public function categoriesnew(Request $request)
    {
        $locale = $request->get('locale', 'en');

        $categories = Category::query()
            ->join('category_translations', function ($join) use ($locale) {
                $join->on('categories.id', '=', 'category_translations.category_id')
                    ->where('category_translations.locale', '=', $locale);
            })
            ->select(
                'categories.id',
                'categories.slug',
                'categories.parent_id',
                'categories.icon',
                'categories.order',
                'category_translations.title as title'
            )
            ->orderBy('categories.order', 'asc')
            ->get()
            ->makeHidden('translations'); // This will hide the translations array

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $categories);
    }

    public function show($id)
    {
        $user = apiAuth();
        $cacheKey = "course_details_{$id}_" . ($user ? $user->id : 'guest');
    
        // Cache for 5 minutes (300s). Adjust as needed.
        return Cache::remember($cacheKey, 300, function () use ($id, $user) {
    
            $webinar = Webinar::where('webinars.status', 'active')
                ->where('webinars.private', false)
                ->where('webinars.id', $id)
                ->leftJoin('apple_product_table', 'webinars.reference_id', '=', 'apple_product_table.id')
                ->select(
                    'webinars.*',
                    'apple_product_table.reference_name',
                    'apple_product_table.product_id',
                    'apple_product_table.display_name',
                    'apple_product_table.description as product_description',
                    'apple_product_table.type'
                )
                ->first();
    
            if (empty($webinar)) {
                return apiResponse2(0, 'invalid', trans('api.public.invalid'));
            }
    
            // Build product data
            $product = [
                'reference_name' => $webinar->reference_name,
                'product_id' => $webinar->product_id,
                'display_name' => $webinar->display_name,
                'description' => $webinar->product_description,
                'type' => $webinar->type,
            ];
    
            unset($webinar->reference_name, $webinar->product_id, $webinar->display_name, $webinar->product_description, $webinar->type);
    
            $details = $webinar->details;
    
            // Check purchase status
            if ($user) {
                $checkOrderItem = OrderItem::where('user_id', $user->id)
                    ->where('webinar_id', $webinar->id)
                    ->whereHas('order', function ($q) {
                        $q->where('status', 'paid');
                    })
                    ->latest('id')
                    ->first();
    
                if (!empty($checkOrderItem)) {
                    $details['auth_has_bought'] = true;
                    $details['purchased_at'] = $checkOrderItem->created_at;
                }
            }
    
            $details['product'] = $product;
            $data = $details;
    
            // Cashback rules
            $cashbackRules = null;
            if (!empty($data["price"]) && getFeaturesSettings('cashback_active') && (empty($user) || !$user->disable_cashback)) {
                $cashbackRulesMixin = new CashbackRules($user);
                $cashbackRules = $cashbackRulesMixin->getRules('courses', $data["id"], $data["type"], null, null);
            }
    
            $data["cashbackRules"] = $cashbackRules;
    
            return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $data);
        });
    }

    public function content($id)
    {
        $user = apiAuth();
        $webinar = Webinar::where('id', $id)
            ->with([
                'chapters' => function ($query) use ($user) {
                    $query->where('status', WebinarChapter::$chapterActive);
                    $query->orderBy('order', 'asc');

                    $query->with([
                        'chapterItems' => function ($query) {
                            $query->orderBy('order', 'asc');
                        }
                    ]);
                },
                'quizzes' => function ($query) {
                    $query->where('status', 'active')
                        ->with(['quizResults', 'quizQuestions']);
                },
                'files' => function ($query) use ($user) {
                    $query->join('webinar_chapters', 'webinar_chapters.id', '=', 'files.chapter_id')
                        ->select('files.*', DB::raw('webinar_chapters.order as chapterOrder'))
                        ->where('files.status', WebinarChapter::$chapterActive)
                        ->orderBy('chapterOrder', 'asc')
                        ->orderBy('files.order', 'asc')
                        ->with([
                            'learningStatus' => function ($query) use ($user) {
                                $query->where('user_id', !empty($user) ? $user->id : null);
                            }
                        ]);
                },
                'textLessons' => function ($query) use ($user) {
                    $query->where('status', WebinarChapter::$chapterActive)
                        ->withCount(['attachments'])
                        ->orderBy('order', 'asc')
                        ->with([
                            'learningStatus' => function ($query) use ($user) {
                                $query->where('user_id', !empty($user) ? $user->id : null);
                            }
                        ]);
                },
                'sessions' => function ($query) use ($user) {
                    $query->where('status', WebinarChapter::$chapterActive)
                        ->orderBy('order', 'asc')
                        ->with([
                            'learningStatus' => function ($query) use ($user) {
                                $query->where('user_id', !empty($user) ? $user->id : null);
                            }
                        ]);
                },
                'assignments' => function ($query) {
                    $query->where('status', WebinarChapter::$chapterActive);
                },
            ])
            ->first();

        if (!empty($webinar)) {
            $chapters = collect(WebinarChapterResource::collection($webinar->chapters))->map(function (WebinarChapterResource $item) {
                return array_merge(['type' => 'chapter'], $item->toArray(null));
            });

            $files = collect(FileResource::collection($webinar->files->whereNull('chapter_id')))->map(function (FileResource $item) {
                return array_merge(['type' => 'file'], $item->toArray(null));
            });
            $sessions = collect(SessionResource::collection($webinar->sessions->whereNull('chapter_id')))->map(function (SessionResource $item) {
                return array_merge(['type' => 'session'], $item->toArray(null));
            });
            $textLessons = collect(TextLessonResource::collection($webinar->textLessons->whereNull('chapter_id')))->map(function (TextLessonResource $item) {
                return array_merge(['type' => 'text_lesson'], $item->toArray(null));
            });

            $content = $chapters->merge($files)->merge($sessions)->merge($textLessons);
            return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $content);
        }

        return apiResponse2(0, 'invalid', trans('api.public.invalid'));
    }

    public function learningStatus(Request $request, $webinar_id)
    {
        switch ($request->input('item')) {
            case 'file_id':
                $table = 'files';
                break;

            case 'session_id':
                $table = 'sessions';
                break;

            case 'text_lesson_id':
                $table = 'text_lessons';
                break;
            default:
                $table = null;

        }

        validateParam($request->all(), [
            'item' => 'required|in:file_id,session_id,text_lesson_id',
            'item_id' => ['required', Rule::exists($table, 'id')],
            'status' => 'required|boolean',
        ]);

        $user = apiAuth();
        $data = $request->all();

        $item = $data['item'];
        $item_id = $data['item_id'];
        $status = $data['status'];

        $course = Webinar::where('id', $webinar_id)->first();

        if (empty($course)) {
            abort(404);
        }


        if (!$course->checkUserHasBought($user)) {

            return apiResponse2(0, 'not_purchased', trans('api.webinar.not_purchased'));
        }


        $courseLearning = CourseLearning::where('user_id', $user->id)
            ->where($item, $item_id)->delete();


        if ($status) {

            CourseLearning::create([
                'user_id' => $user->id,
                $item => $item_id,
                'created_at' => time()
            ]);

            return apiResponse2(1, 'read', trans('api.learning_status.read'));

        }
        return apiResponse2(1, 'unread', trans('api.learning_status.unread'));


    }

    public function report(Request $request, $id)
    {
        $user = apiAuth();
        validateParam($request->all(), [
            'reason' => 'required|string',
            'message' => 'required|string',
        ]);

        $webinar = Webinar::select('id', 'status')
            ->where('id', $id)
            ->where('status', 'active')
            ->first();
        if (!$webinar) {
            abort(404);
        }

        WebinarReport::create([
            'user_id' => $user->id,
            'webinar_id' => $webinar->id,
            'reason' => $request->post('reason'),
            'message' => $request->post('message'),
            'created_at' => time()
        ]);
        return apiResponse2(1, 'reported', trans('courses.reported'));
    }

    public static function brief($webinars, $single = false)
    {
        if ($single) {
            $webinars = collect([$webinars]);
        }
        //
        $user = apiAuth();
        $webinars = $webinars->map(function ($webinar) use ($user) {

            $hasBought = $webinar->checkUserHasBought($user);

            /* progressbar status */
            $progress = self::progress($webinar);

            /* is user favorite */
            $is_favorite = self::isFavorite($webinar);

            /* live webinar status */
            $live_webinar_status = self::liveWebinarStatus($webinar);

            return [
                'auth' => ($user) ? true : false,
                'id' => $webinar->id,
                'status' => $webinar->status,
                'title' => $webinar->title,
                'type' => $webinar->type,
                'live_webinar_status' => $live_webinar_status,
                'auth_has_bought' => $hasBought,

                'price' => $webinar->price,
                'price_with_discount' => ($webinar->activeSpecialOffer()) ? (
                    number_format($webinar->price - ($webinar->price * $webinar->activeSpecialOffer()->percent / 100), 2)) : false,
                'active_special_offer' => $webinar->activeSpecialOffer(),

                'duration' => $webinar->duration,
                'teacher' => [
                    'full_name' => $webinar->teacher->full_name,
                    'avatar' => $webinar->teacher->getAvatar(),
                    'rate' => $webinar->teacher->rates(),
                ],
                'rate' => $webinar->getRate(),
                'discount' => $webinar->getDiscount(),
                'created_at' => $webinar->created_at,
                'start_date' => $webinar->start_date,
                'progress' => $webinar->getProgress(),
                'category' => $webinar->category->title,

            ];
        });

        if ($single) {
            return $webinars->first();
        }

        return [
            'count' => count($webinars),
            'webinars' => $webinars,
        ];
    }

    public function details($webinars)
    {
        $user = apiAuth();

        $webinars = $webinars->map(function ($webinar) use ($user) {
            $hasBought = $webinar->checkUserHasBought($user);

            /* progressbar status */
            $progress = $this->progress($webinar);

            /* is user favorite */
            $is_favorite = $this->isFavorite($webinar);

            /* live webinar status */
            $live_webinar_status = $this->liveWebinarStatus($webinar);

            return [
                'auth' => ($user) ? true : false,
                'id' => $webinar->id,
                'title' => $webinar->title,
                'type' => $webinar->type,
                'live_webinar_status' => $live_webinar_status,
                'auth_has_bought' => $hasBought,
                'price' => $webinar->price,
                'price_with_discount' => ($webinar->activeSpecialOffer()) ? (
                    number_format($webinar->price - ($webinar->price * $webinar->activeSpecialOffer()->percent / 100), 2)) : false,
                'active_special_offer' => $webinar->activeSpecialOffer(),

                'duration' => $webinar->duration,
                'teacher' => [
                    'full_name' => $webinar->teacher->full_name,
                    'avatar' => $webinar->teacher->getAvatar(),
                    'rate' => $webinar->teacher->rates(),
                ],

                'sessions_count' => $webinar->sessions->count(),
                'text_lessons_count' => $webinar->textLessons->count(),
                'files_count' => $webinar->files->count(),
                /*    $sessionChapters = $course->chapters->where('type', WebinarChapter::$chapterSession);
                $sessionsWithoutChapter = $course->sessions->whereNull('chapter_id');*/

                'sessions_without_chapter' => $webinar->sessions->whereNull('chapter_id')->map(function ($session) {
                    return [
                        'id' => $session->id,
                        'title' => $session->title,
                        'description' => $session->description,
                        'date' => dateTimeFormat($session->date, 'j M Y | H:i')
                    ];

                }),
                'sessions_with_chapter' => $webinar->chapters->where('type', WebinarChapter::$chapterSession)->map(function ($chapter) {
                    $chapter->sessions->map(function ($session) {
                        return [
                            'id' => $session->id,
                            'title' => $session->title,
                            'description' => $session->description,
                            'date' => dateTimeFormat($session->date, 'j M Y | H:i')
                        ];
                    });


                }),

                'rate' => $webinar->getRate(),
                'rate_type' => [
                    'content_quality' => $webinar->reviews->count() > 0 ? round($webinar->reviews->avg('content_quality'), 1) : 0,
                    'instructor_skills' => $webinar->reviews->count() > 0 ? round($webinar->reviews->avg('instructor_skills'), 1) : 0,
                    'purchase_worth' => $webinar->reviews->count() > 0 ? round($webinar->reviews->avg('purchase_worth'), 1) : 0,
                    'support_quality' => $webinar->reviews->count() > 0 ? round($webinar->reviews->avg('support_quality'), 1) : 0,

                ],
                'reviews_count' => $webinar->reviews->count(),
                'reviews' => $webinar->reviews->map(function ($review) {
                    return [
                        'user' => [
                            'full_name' => $review->creator->full_name,
                            'avatar' => $review->creator->getAvatar(),
                        ],
                        'create_at' => $review->created_at,
                        'description' => $review->description,
                        'replies' => $review->comments->map(function ($reply) {
                            return [
                                'user' => [
                                    'full_name' => $reply->user->full_name,
                                    'avatar' => $reply->user->getAvatar(),
                                ],
                                'create_at' => $reply->created_at,
                                'comment' => $reply->comment,
                            ];

                        })


                    ];
                }),
                'comments' => $webinar->comments->map(function ($item) {
                    return [
                        'user' => [
                            'full_name' => $item->user->full_name,
                            'avatar' => $item->user->getAvatar(),
                        ],
                        'create_at' => $item->created_at,
                        'comment' => $item->comment,
                        'replies' => $item->replies->map(function ($reply) {
                            return [
                                'user' => [
                                    'full_name' => $reply->user->full_name,
                                    'avatar' => $reply->user->getAvatar(),
                                ],
                                'create_at' => $reply->created_at,
                                'comment' => $reply->comment,
                            ];

                        })
                    ];
                }),
                'discount' => $webinar->getDiscount(),
                'created_at' => $webinar->created_at,
                'start_date' => $webinar->start_date,

                'progress' => $progress,
                //'progressa' => $webinar->$progress,
                'category' => $webinar->category->title,
                'video_demo' => $webinar->video_demo,
                'image' => $webinar->getImage(),
                'description' => $webinar->description,
                'isDownloadable' => $webinar->isDownloadable(),
                'support' => $webinar->support ? true : false,
                'certificate' => ($webinar->quizzes->where('certificate', 1)->count() > 0) ? true : false,
                'quizzes_count' => $webinar->quizzes->where('status', \App\models\Quiz::ACTIVE)->count(),
                'is_favorite' => $is_favorite,
                'students_count' => $webinar->sales->count(),
                'tags' => $webinar->tags,
                'tickets' => $webinar->tickets->map(function ($ticket) {
                    return [
                        'id' => $ticket->id,
                        'title' => $ticket->title,
                        'sub_title' => $ticket->getSubTitle(),
                        'discount' => $ticket->discount,
                        //  'order' => $ticket->order,
                        'is_valid' => $ticket->isValid(),

                    ];
                }),
                'prerequisites' => $webinar->prerequisites->map(function ($prerequisite) {
                    return [
                        'required' => $prerequisite->required,
                        'webinar' => self::brief($prerequisite->prerequisiteWebinar, true)
                    ];
                }),
                'faqs' => $webinar->faqs

            ];
        });
        return [
            'count' => count($webinars),
            'webinars' => $webinars,
        ];
    }

    public static function getSingle($id)
    {
        $webinar = Webinar::where('status', 'active')
            ->where('private', false)->where('id', $id)->first();
        //  dd($webinar->id);
        if (!$webinar) {
            return null;
        }
        return self::brief($webinar, true);

    }

    public function handleFilters($request, $query)
    {
        $offset = $request->get('offset', null);
        $limit = $request->get('limit', null);
        $upcoming = $request->get('upcoming', null);
        $isFree = $request->get('free', null);
        $withDiscount = $request->get('discount', null);
        $isDownloadable = $request->get('downloadable', null);
        $sort = $request->get('sort', null);
        $filterOptions = $request->get('filter_option', null);
        $typeOptions = $request->get('type', []);
        $moreOptions = $request->get('moreOptions', []);
        $category = $request->get('cat', null);

        if (!empty($category) and is_numeric($category)) {
            $query->where('category_id', $category);
        }
        if (!empty($upcoming) and $upcoming == 1) {
            $query->whereNotNull('start_date')
                ->where('start_date', '>=', time());
        }

        if (!empty($isFree) and $isFree == 1) {
            $query->where(function ($qu) {
                $qu->whereNull('price')
                    ->orWhere('price', '0');
            });
        }

        if (!empty($isDownloadable) and $isDownloadable == 1) {
            $query->where('downloadable', 1);
        }

        if (!empty($withDiscount) and $withDiscount == 1) {
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

            $webinarIdsHasDiscount = array_unique($webinarIdsHasDiscount);

            $query->whereIn('webinars.id', $webinarIdsHasDiscount);
        }

        if (!empty($sort)) {
            if ($sort == 'expensive') {
                $query->orderBy('price', 'desc');
            }

            if ($sort == 'newest') {
                $query->orderBy('created_at', 'desc');
            }

            if ($sort == 'inexpensive') {
                $query->orderBy('price', 'asc');
            }

            if ($sort == 'bestsellers') {
                $query->whereHas('sales')
                    ->with('sales')
                    ->get()
                    ->sortBy(function ($qu) {
                        return $qu->sales->count();
                    });
            }

            if ($sort == 'best_rates') {
                $query->whereHas('reviews', function ($query) {
                    $query->where('status', 'active');
                })->with('reviews')
                    ->get()
                    ->sortBy(function ($qu) {
                        return $qu->reviews->avg('rates');
                    });
            }
        }

        if (!empty($filterOptions)) {
            $webinarIdsFilterOptions = WebinarFilterOption::where('filter_option_id', $filterOptions)
                ->pluck('webinar_id')
                ->toArray();

            $query->whereIn('webinars.id', $webinarIdsFilterOptions);
        }

        if (!empty($typeOptions) and is_array($typeOptions)) {
            $query->whereIn('type', $typeOptions);
        }

        if (!empty($moreOptions) and is_array($moreOptions)) {
            if (in_array('subscribe', $moreOptions)) {
                $query->where('subscribe', 1);
            }

            if (in_array('certificate_included', $moreOptions)) {
                $query->whereHas('quizzes', function ($query) {
                    $query->where('certificate', 1)
                        ->where('status', 'active');
                });
            }

            if (in_array('with_quiz', $moreOptions)) {
                $query->whereHas('quizzes', function ($query) {
                    $query->where('status', 'active');
                });
            }

            if (in_array('featured', $moreOptions)) {
                $query->whereHas('feature', function ($query) {
                    $query->whereIn('page', ['home_categories', 'categories'])
                        ->where('status', 'publish');
                });
            }
        }

        if (!empty($offset) && !empty($limit)) {
            $query->skip($offset);
        }
        if (!empty($limit)) {
            $query->take($limit);
        }
        return $query;
    }

    private static function liveWebinarStatus($webinar)
    {
        $live_webinar_status = false;
        if ($webinar->type == 'webinar') {
            if ($webinar->start_date > time()) {
                $live_webinar_status = 'not_conducted';
            } elseif ($webinar->isProgressing()) {
                $live_webinar_status = 'in_progress';
            } else {
                $live_webinar_status = 'finished';
            }
        }
        return $live_webinar_status;


    }

    private static function progress($webinar)
    {
        $user = apiAuth();
        /* progressbar status */
        $hasBought = $webinar->checkUserHasBought($user);
        $progress = null;
        if ($hasBought or $webinar->isWebinar()) {
            if ($webinar->isWebinar()) {
                if ($hasBought and $webinar->isProgressing()) {
                    $progress = $webinar->getProgress();
                } else {
                    $progress = $webinar->sales()->count() . '/' . $webinar->capacity;
                }
            } else {
                $progress = $webinar->getProgress();
            }
        }

        return $progress;
    }

    private static function isFavorite($webinar)
    {
        $user = apiAuth();
        $isFavorite = false;
        if (!empty($user)) {
            $isFavorite = Favorite::where('webinar_id', $webinar->id)
                ->where('user_id', $user->id)
                ->first();
        }
        return ($isFavorite) ? true : false;
    }
    
    public function communitychat(Request $request)
    {
        $webinar = Webinar::where('id', $request->webinar_id)->first();
        if (empty($webinar)) {
            return apiResponse2(0, 'invalid', trans('api.public.invalid'));
        }

        if(!empty($webinar)) {
            $webinarCommunityChat = Webinar::UpdateOrCreate([
                'id' => $webinar->id,
            ], [
                'cummnity_chat' => $request->community_chat,
            ]);
            
        }
        
        return apiResponse2(1, 'retrieved', 'Success', $webinarCommunityChat);
        
    }


}
