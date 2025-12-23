<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Category;
use App\Models\FeatureWebinar;
use App\Models\SpecialOffer;
use App\Models\Ticket;
use App\Models\Webinar;
use App\Models\WebinarFilterOption;
use App\Models\WebinarReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Stat;
use App\Models\Sale;

class ClassesController extends Controller
{
    public $tableName = 'webinars';
    public $columnId = 'webinar_id';


    public function index(Request $request)
    {
        $webinarsQuery = Webinar::where('webinars.status', 'active');
            // ->where('private', false);

        $data = $request->all();

        $type = $request->get('type');
        if (!empty($type) and is_array($type) and in_array('bundle', $type)) {
            $webinarsQuery = Bundle::where('bundles.status', 'active');
            $this->tableName = 'bundles';
            $this->columnId = 'bundle_id';
        }

        
        $webinarsQuery = $this->handleFilters($request, $webinarsQuery);
        // echo "<pre>";
        // print_r($webinarsQuery);die;
        


        $sort = $request->get('sort', null);

        if (empty($sort) or $sort == 'newest') {
            $webinarsQuery = $webinarsQuery->orderBy("{$this->tableName}.created_at", 'desc');
        }

        $webinars = $webinarsQuery->with([
            'tickets'
        ])->paginate(8);

        $seoSettings = getSeoMetas('classes');
        $pageTitle = $seoSettings['title'] ?? '';
        $pageDescription = $seoSettings['description'] ?? '';
        $pageRobot = getPageRobot('classes');

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
        ->limit(1)
        ->get();

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

        $locale = 'en'; //app()->getLocale();
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
        ->makeHidden('translations');

        $selectedCategory = null;

        if (!empty($data['category_id'])) {
            $selectedCategory = Category::where('id', $data['category_id'])->first();
        }

        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'webinars' => $webinars,
            'bestRateWebinars' => $bestRateWebinars,
            'bestSaleWebinars' => $bestSaleWebinars,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'coursesCount' => $webinars->total()
        ];

        return view(getTemplate() . '.pages.classes', $data);
    }

    public function handleFilters($request, $query)
    {
        $upcoming = $request->get('upcoming', null);
        $isFree = $request->get('free', null);
        $withDiscount = $request->get('discount', null);
        $isDownloadable = $request->get('downloadable', null);
        $sort = $request->get('sort', null);
        $filterOptions = $request->get('filter_option', []);
        $typeOptions = $request->get('type', []);
        $moreOptions = $request->get('moreOptions', []);
        $search = $request->get('search', null);
        $categoryId = $request->get('category_id', null);

        $query->whereHas('teacher', function ($query) {
            $query->where('status', 'active')
                ->where(function ($query) {
                    $query->where('ban', false)
                        ->orWhere(function ($query) {
                            $query->whereNotNull('ban_end_at')
                                ->where('ban_end_at', '<', time());
                        });
                });
        });

        if ($this->tableName == 'webinars') {

            if (!empty($upcoming) and $upcoming == 'on') {
                $query->whereNotNull('start_date')
                    ->where('start_date', '>=', time());
            }

            if (!empty($isDownloadable) and $isDownloadable == 'on') {
                $query->where('downloadable', 1);
            }

            if (!empty($typeOptions) and is_array($typeOptions)) {
                $query->whereIn("{$this->tableName}.type", $typeOptions);
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
        }

        if (!empty($search)) {
            $query->whereTranslationLike('title', '%' . $search . '%');
        }

        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        if (!empty($isFree) and $isFree == 'on') {
            $query->where(function ($qu) {
                $qu->whereNull('price')
                    ->orWhere('price', '0');
            });
        }

        if (!empty($withDiscount) and $withDiscount == 'on') {
            $now = time();
            $webinarIdsHasDiscount = [];

            $tickets = Ticket::where('start_date', '<', $now)
                ->where('end_date', '>', $now)
                ->whereNotNull("{$this->columnId}")
                ->get();

            foreach ($tickets as $ticket) {
                if ($ticket->isValid()) {
                    $webinarIdsHasDiscount[] = $ticket->{$this->columnId};
                }
            }

            $specialOffersItemIds = SpecialOffer::where('status', 'active')
                ->where('from_date', '<', $now)
                ->where('to_date', '>', $now)
                ->pluck("{$this->columnId}")
                ->toArray();

            $webinarIdsHasDiscount = array_merge($specialOffersItemIds, $webinarIdsHasDiscount);

            $webinarIdsHasDiscount = array_unique($webinarIdsHasDiscount);

            $query->whereIn("{$this->tableName}.id", $webinarIdsHasDiscount);
        }

        if (!empty($sort)) {
            if ($sort == 'expensive') {
                $query->whereNotNull('price');
                $query->where('price', '>', 0);
                $query->orderBy('price', 'desc');
            }

            if ($sort == 'inexpensive') {
                $query->whereNotNull('price');
                $query->where('price', '>', 0);
                $query->orderBy('price', 'asc');
            }

            if ($sort == 'bestsellers') {
                $query->leftJoin('sales', function ($join) {
                    $join->on("{$this->tableName}.id", '=', "sales.{$this->columnId}")
                        ->whereNull('refund_at');
                })
                    ->whereNotNull("sales.{$this->columnId}")
                    ->select("{$this->tableName}.*", "sales.{$this->columnId}", DB::raw("count(sales.{$this->columnId}) as salesCounts"))
                    ->groupBy("sales.{$this->columnId}")
                    ->orderBy('salesCounts', 'desc');
            }

            if ($sort == 'best_rates') {
                $query->leftJoin('webinar_reviews', function ($join) {
                    $join->on("{$this->tableName}.id", '=', "webinar_reviews.{$this->columnId}");
                    $join->where('webinar_reviews.status', 'active');
                })
                    ->whereNotNull('rates')
                    ->select("{$this->tableName}.*", DB::raw('avg(rates) as rates'))
                    ->groupBy("{$this->tableName}.id")
                    ->orderBy('rates', 'desc');
            }
        }

        if (!empty($filterOptions) and is_array($filterOptions)) {
            $webinarIdsFilterOptions = WebinarFilterOption::whereIn('filter_option_id', $filterOptions)
                ->pluck($this->columnId)
                ->toArray();

            $query->whereIn("{$this->tableName}.id", $webinarIdsFilterOptions);
        }

        return $query;
    }
    
    // public function updateStats(Request $request)
    // {
    //     $webinarId = $request->input('webinar_id');
    //     $postId = $request->input('post_id');
    //     $type = $request->input('type'); // like, view, share
    //     $action = $request->input('action'); // add or remove
    //     $userId = auth()->id();
    //     $systemIp = getSystemIP();

    //     $column = match ($type) {
    //         'like' => 'likes',
    //         'view' => 'views',
    //         'share' => 'shares',
    //         default => null,
    //     };

    //     if (!$column) {
    //         return response()->json(['success' => false, 'message' => 'Invalid type']);
    //     }

    //     // Determine the primary identifier: webinar_id or blog_id (post_id)
    //     $query = DB::table('stats');
    //     if ($webinarId) {
    //         $query->where('webinar_id', $webinarId);
    //     } elseif ($postId) {
    //         $query->where('blog_id', $postId);
    //     } else {
    //         return response()->json(['success' => false, 'message' => 'Missing webinar_id or post_id']);
    //     }

    //     // Check for user ID or IP address
    //     if ($userId) {
    //         $query->where('user_id', $userId);
    //     } else {
    //         $query->where('ip_address', $systemIp);
    //     }

    //     $existingStat = $query->first();

    //     if ($action === 'add') {
    //         if ($existingStat) {
    //             $query->increment($column);
    //         } else {
    //             DB::table('stats')->insert([
    //                 'webinar_id' => $webinarId ?? 0,
    //                 'blog_id' => $postId ?? 0,
    //                 'user_id' => $userId ?? 0,
    //                 'ip_address' => $systemIp ?? null,
    //                 $column => 1
    //             ]);
    //         }
    //     } elseif ($action === 'remove') {
    //         if ($existingStat) {
    //             $query->decrement($column);
    //         }
    //     }

    //     return response()->json(['success' => true]);
    // }
    
    public function getStats(Request $request)
    {
        $webinarId = $request->input('webinar_id');
        $postId = $request->input('post_id');
    
        $query = Stat::query();
    
        // Apply filters only if the parameters are provided
        if ($webinarId) {
            $query->where('webinar_id', $webinarId);
        }
        if ($postId) {
            $query->where('blog_id', $postId);
        }
    
        // Get sum of views and likes
        $views = $query->sum('views');
        $likes = $query->sum('likes');
    
        return response()->json([
            'success' => true,
            'updated_views' => $views,
            'updated_likes' => $likes
        ]);
    }
    
    public function updateStats(Request $request)
    {
        $webinarId = $request->input('webinar_id');
        $postId = $request->input('post_id');
        $type = $request->input('type'); // like, view, share
        $action = $request->input('action'); // add or remove
        $userId = auth()->id();
        $systemIp = getSystemIP();

        $column = match ($type) {
            'like' => 'likes',
            'view' => 'views',
            'share' => 'shares',
            default => null,
        };

        if (!$column) {
            return response()->json(['success' => false, 'message' => 'Invalid type']);
        }

        // Determine the primary identifier: webinar_id or blog_id (post_id)
        $query = DB::table('stats');
        if ($webinarId) {
            $query->where('webinar_id', $webinarId);
        } elseif ($postId) {
            $query->where('blog_id', $postId);
        } else {
            return response()->json(['success' => false, 'message' => 'Missing webinar_id or post_id']);
        }

        // Check for user ID or IP address
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('ip_address', $systemIp);
        }

        $existingStat = $query->first();

        if ($type === 'view') {
            // If the user has already viewed, do not increment again
            if ($existingStat) {
                // Fetch the latest view count from the database
                $updatedViews = DB::table('stats')
                    ->when($webinarId, fn($q) => $q->where('webinar_id', $webinarId))
                    ->when($postId, fn($q) => $q->where('blog_id', $postId))
                    ->sum('views');

                return response()->json(['success' => true, 'updated_views' => $updatedViews]);
            }
        }

        if ($action === 'add') {
            if ($existingStat) {
                $query->increment($column);
            } else {
                DB::table('stats')->insert([
                    'webinar_id' => $webinarId ?? 0,
                    'blog_id' => $postId ?? 0,
                    'user_id' => $userId ?? 0,
                    'ip_address' => $systemIp ?? null,
                    'likes' => 0,
                    'views' => 0,
                    'shares' => 0,
                    $column => 1
                ]);
            }
        } elseif ($action === 'remove') {
            if ($existingStat) {
                $query->decrement($column);
            }
        }

        // Fetch the latest view count from the database
        $updatedViews = DB::table('stats')
            ->when($webinarId, fn($q) => $q->where('webinar_id', $webinarId))
            ->when($postId, fn($q) => $q->where('blog_id', $postId))
            ->sum('views');

        return response()->json(['success' => true, 'updated_views' => $updatedViews]);
    }
}
