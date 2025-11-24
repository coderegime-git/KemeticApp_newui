<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{
    HomeSection, FeatureWebinar, Webinar, Bundle, UpcomingCourse, Reel,
    Sale, Ticket, SpecialOffer, Product, TrendCategory, Blog, Testimonial, Subscribe, AdvertisingBanner, HomePageStatistic
};
use App\Mixins\Installment\InstallmentPlans;
use App\Models\Role;
use App\User;

class ApiHomeController extends Controller
{
    public function index(Request $request)
    {
        $homeSections = HomeSection::orderBy('order', 'asc')->get();
        $selectedSectionsName = $homeSections->pluck('name')->toArray();

        $data = [];

        // Featured Webinars
        if (in_array(HomeSection::$featured_classes, $selectedSectionsName)) {
            $data['featureWebinars'] = FeatureWebinar::whereIn('page', ['home', 'home_categories'])
                ->where('status', 'publish')
                ->whereHas('webinar', fn($q) => $q->where('status', Webinar::$active))
                ->with(['webinar.teacher:id,full_name,avatar', 'webinar.reviews' => fn($q) => $q->where('status', 'active'), 'webinar.tickets', 'webinar.feature'])
                ->orderBy('updated_at', 'desc')
                ->get();
        }

        // Latest Webinars
        if (in_array(HomeSection::$latest_classes, $selectedSectionsName)) {
            $data['latestWebinars'] = Webinar::where('status', Webinar::$active)
                ->where('private', false)
                ->orderBy('updated_at', 'desc')
                ->with(['teacher:id,full_name,avatar', 'reviews' => fn($q) => $q->where('status', 'active'), 'tickets', 'feature'])
                ->limit(6)
                ->get();
        }

        // Latest Bundles
        if (in_array(HomeSection::$latest_bundles, $selectedSectionsName)) {
            $data['latestBundles'] = Bundle::where('status', Webinar::$active)
                ->orderBy('updated_at', 'desc')
                ->with(['teacher:id,full_name,avatar', 'reviews' => fn($q) => $q->where('status', 'active'), 'tickets'])
                ->limit(6)
                ->get();
        }

        // Upcoming Courses
        if (in_array(HomeSection::$upcoming_courses, $selectedSectionsName)) {
            $data['upcomingCourses'] = UpcomingCourse::where('status', Webinar::$active)
                ->orderBy('created_at', 'desc')
                ->with(['teacher:id,full_name,avatar'])
                ->limit(6)
                ->get();
        }

        // Best Sellers
        if (in_array(HomeSection::$best_sellers, $selectedSectionsName)) {
            $bestSaleWebinarsIds = Sale::whereNotNull('webinar_id')
                ->select(DB::raw('COUNT(id) as cnt, webinar_id'))
                ->groupBy('webinar_id')
                ->orderBy('cnt', 'DESC')
                ->limit(6)
                ->pluck('webinar_id')
                ->toArray();

            $data['bestSaleWebinars'] = Webinar::whereIn('id', $bestSaleWebinarsIds)
                ->where('status', Webinar::$active)
                ->where('private', false)
                ->with(['teacher:id,full_name,avatar', 'reviews' => fn($q) => $q->where('status', 'active'), 'sales', 'tickets', 'feature'])
                ->get();
        }

        // Best Rated
        if (in_array(HomeSection::$best_rates, $selectedSectionsName)) {
            $data['bestRateWebinars'] = Webinar::join('webinar_reviews', 'webinars.id', '=', 'webinar_reviews.webinar_id')
                ->select('webinars.*', DB::raw('avg(webinar_reviews.rates) as avg_rates'))
                ->where('webinars.status', 'active')
                ->where('webinars.private', false)
                ->where('webinar_reviews.status', 'active')
                ->groupBy('webinars.id')
                ->orderBy('avg_rates', 'desc')
                ->with(['teacher:id,full_name,avatar'])
                ->limit(6)
                ->get();
        }

        // Discounted Webinars
        if (in_array(HomeSection::$discount_classes, $selectedSectionsName)) {
            $now = time();
            $webinarIdsHasDiscount = [];

            $tickets = Ticket::where('start_date', '<', $now)->where('end_date', '>', $now)->get();
            foreach ($tickets as $ticket) {
                if ($ticket->isValid()) $webinarIdsHasDiscount[] = $ticket->webinar_id;
            }

            $specialOffersWebinarIds = SpecialOffer::where('status', 'active')
                ->where('from_date', '<', $now)
                ->where('to_date', '>', $now)
                ->pluck('webinar_id')
                ->toArray();

            $webinarIdsHasDiscount = array_merge($specialOffersWebinarIds, $webinarIdsHasDiscount);

            $data['hasDiscountWebinars'] = Webinar::whereIn('id', array_unique($webinarIdsHasDiscount))
                ->where('status', Webinar::$active)
                ->where('private', false)
                ->with(['teacher:id,full_name,avatar', 'reviews' => fn($q) => $q->where('status', 'active'), 'sales', 'tickets', 'feature'])
                ->limit(6)
                ->get();
        }

        // Free Webinars
        if (in_array(HomeSection::$free_classes, $selectedSectionsName)) {
            $data['freeWebinars'] = Webinar::where('status', Webinar::$active)
                ->where('private', false)
                ->where(fn($q) => $q->whereNull('price')->orWhere('price', '0'))
                ->orderBy('updated_at', 'desc')
                ->with(['teacher:id,full_name,avatar', 'reviews' => fn($q) => $q->where('status', 'active'), 'tickets', 'feature'])
                ->limit(6)
                ->get();
        }

        // Store Products
        if (in_array(HomeSection::$store_products, $selectedSectionsName)) {
            $data['newProducts'] = Product::where('status', Product::$active)
                ->orderBy('updated_at', 'desc')
                ->with(['creator:id,full_name,avatar'])
                ->limit(6)
                ->get();
        }

        // Trend Categories
        if (in_array(HomeSection::$trend_categories, $selectedSectionsName)) {
            $data['trendCategories'] = TrendCategory::with(['category' => fn($q) => $q->withCount(['webinars' => fn($w) => $w->where('status', 'active')])])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Blogs
        if (in_array(HomeSection::$blog, $selectedSectionsName)) {
            $data['blog'] = Blog::where('status', 'publish')
                ->with(['category', 'author:id,full_name'])
                ->withCount('comments')
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();
        }

        // Instructors
        if (in_array(HomeSection::$instructors, $selectedSectionsName)) {
            $data['instructors'] = User::where('role_name', Role::$teacher)
                ->select('id', 'full_name', 'avatar', 'bio')
                ->where('status', 'active')
                ->where(fn($q) => $q->where('ban', false)->orWhere(fn($sub) => $sub->whereNotNull('ban_end_at')->where('ban_end_at', '<', time())))
                ->limit(8)
                ->get();
        }

        // Organizations
        if (in_array(HomeSection::$organizations, $selectedSectionsName)) {
            $data['organizations'] = User::where('role_name', Role::$organization)
                ->where('status', 'active')
                ->where(fn($q) => $q->where('ban', false)->orWhere(fn($sub) => $sub->whereNotNull('ban_end_at')->where('ban_end_at', '<', time())))
                ->withCount('webinars')
                ->orderBy('webinars_count', 'desc')
                ->limit(6)
                ->get();
        }

        // Testimonials
        if (in_array(HomeSection::$testimonials, $selectedSectionsName)) {
            $data['testimonials'] = Testimonial::where('status', 'active')->get();
        }

        $data['reels'] = Reel::where('is_hidden','0')->orderby('id','desc')->limit(10)->get();

        // Advertising Banners
        $banners = AdvertisingBanner::where('published', true)->whereIn('position', ['home1', 'home2'])->get();
        $data['advertisingBanners1'] = $banners->where('position', 'home1')->values();
        $data['advertisingBanners2'] = $banners->where('position', 'home2')->values();

        // Response
        return response()->json([
            'status' => true,
            'message' => 'Home data loaded successfully',
            'data' => $data,
        ], 200, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);

    }
}
