<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Cart;
use App\Models\BookOrder;
use App\Models\BookCategory;
use App\Models\Subscribe;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http; // Add this line
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    //protected $laragonCertPath;
    
    // public function __construct()
    // {
    //     // Laragon certificate path - adjust if different
    //     $this->laragonCertPath = "C:/laragon/etc/ssl/cert.pem";
        
    //     // Alternative paths to check
    //     $alternativePaths = [
    //         "C:/laragon/etc/ssl/cert.pem",
    //         "C:/laragon/etc/ssl/cacert.pem",
    //         "C:/laragon/etc/ssl/certs/ca-bundle.crt",
    //         base_path("cacert.pem"), // If you want to store in your project
    //         storage_path("app/certs/cacert.pem"), // Custom storage path
    //     ];
        
    //     // Find the first existing certificate file
    //     foreach ($alternativePaths as $path) {
    //         if (file_exists($path)) {
    //             $this->laragonCertPath = $path;
    //             break;
    //         }
    //     }
    // }

    public function index(Request $request)
    {
        $user = null;

        $activeSubscribe = "";

        if (auth()->check()) {
            $user = auth()->user();
        }

        $data = $request->all();

        $creator = $request->get('creator', null);
        $search = $request->get('search', null);
        $categoryId = $request->get('category_id', null);
        
        $seoSettings = getSeoMetas('books');
        $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('Books');
        $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('home.books');
        $pageRobot = getPageRobot('books');

        $bookCategories = BookCategory::all();

        $query = Book::orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc');

        if (!empty($creator) and is_numeric($creator)) {
            $query->where('creator_id', $creator);
        }

        if (!empty($search)) {
            $query->whereTranslationLike('title', "%$search%");
        }

        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        $bookCount = $query->count();

        $books = $query->with([
            'categories',
            'creator' => function ($query) {
                $query->select('id', 'full_name', 'avatar', 'role_id', 'role_name');
            }
        ])
            ->withCount(['likes', 'comments', 'share', 'gift', 'savedItems'])
            ->paginate(6);

        $popularBooks = $this->getPopularBooks($user);
        $popularBook = $popularBooks->first(); 

        $selectedCategory = null;

        if (!empty($data['category_id'])) {
            $selectedCategory = BookCategory::where('id', $data['category_id'])->first();
        }

        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'books' => $books,
            'bookCount' => $bookCount,
            'bookCategories' => $bookCategories,
            'popularBooks' => $popularBooks,
            'popularBook' => $popularBook,
            'selectedCategory' => $selectedCategory
        ];

        return view(getTemplate() . '.book.index', $data);
    }    

    private function downloadCertificate($path)
    {
        $certUrl = 'https://curl.se/ca/cacert.pem';
        $certContent = @file_get_contents($certUrl);
        
        if ($certContent) {
            file_put_contents($path, $certContent);
            return true;
        }
        
        return false;
    }

    public function show($slug)
    {
        $user = null;

        $activeSubscribe = "";

        if (auth()->check()) {
            $user = auth()->user();
            $activeSubscribe = Subscribe::getActiveSubscribe($user->id);
        }

        if (!empty($slug)) {
            $book = Book::where('slug', $slug)
                ->with([
                    'categories',
                    'creator' => function ($query) {
                        $query->select('id', 'full_name', 'role_id', 'avatar', 'role_name');
                        $query->with('role');
                    },
                    'comments' => function ($query) {
                        //$query->where('status', 'active');
                        //$query->whereNull('reply_id');
                        $query->with([
                            'user' => function ($query) {
                                $query->select('id', 'full_name', 'avatar', 'avatar_settings', 'role_id', 'role_name');
                            },
                            // 'replies' => function ($query) {
                            //     $query->where('status', 'active');
                            //     $query->with([
                            //         'user' => function ($query) {
                            //             $query->select('id', 'full_name', 'avatar', 'avatar_settings', 'role_id', 'role_name');
                            //         }
                            //     ]);
                            // }
                        ]);
                    },
                    'likes',
                    'share',
                    'gift',
                    'savedItems'
                ])
                ->withCount(['likes', 'comments', 'share', 'gift', 'savedItems'])
                ->first();

            if (!empty($book)) {
                $bookCategories = BookCategory::all();
                $popularBooks = $this->getPopularBooks($user);
                $popularBook = $popularBooks->first(); 

                $pageRobot = getPageRobot('books');

                // Calculate engagement metrics
                $totalEngagement = $book->likes_count + $book->comments_count + $book->saved_items_count;
                $ratingDisplay = $book->likes_count > 0 ? number_format(($book->likes_count / max($book->likes_count + 1, 1)) * 5, 1) : '4.98';
                $hasBought = $book->checkUserHasBought($user);
                

                $data = [
                    'pageTitle' => $book->title,
                    'pageDescription' => $book->meta_description ?? $book->title,
                    'pageMetaImage' => $book->getImage(),
                    'bookCategories' => $bookCategories,
                    'popularBooks' => $popularBooks,
                    'popularBook' => $popularBook,
                    'pageRobot' => $pageRobot,
                    'book' => $book,
                    'hasBought' => $hasBought,
                    'activeSubscribe' => $activeSubscribe,
                    'likeCount' => $book->likes_count,
                    'shareCount' => $book->share_count,
                    'giftCount' => $book->gift_count,
                    'commentCount' => $book->comments_count,
                    'savedCount' => $book->saved_items_count,
                    'totalEngagement' => $totalEngagement,
                    'ratingDisplay' => $ratingDisplay,
                    'formattedPrice' => $book->formatted_price,
                    'isFree' => $book->is_free,
                    'bookUrl' => $book->getUrl()
                ];

                return view(getTemplate() . '.book.show', $data);
            }
        }

        abort(404);
    }

    // private function getPopularBooks()
    // {
    //     return Book::withCount([
    //         'likes',
    //         'comments', 
    //         'share',
    //         'gift',
    //         'savedItems'
    //     ])
    //     ->orderByRaw('(
    //         (likes_count * 2) + 
    //         (saved_items_count * 3) + 
    //         (comments_count * 2) + 
    //         (share_count * 1.5) + 
    //         (gift_count * 2)
    //     ) DESC')
    //     ->limit(1)
    //     ->get();
    // }

    private function getPopularBooks($user = null)
    {
        $query = Book::with([
            'creator:id,full_name,avatar'
        ]);

        // Check if user is Wisdom Keeper
        $isWisdomKeeper = $user && $user->role->caption === 'Wisdom Keeper';

        if ($isWisdomKeeper) {
            // Get all Wisdom Keeper user IDs for filtering
            $wisdomKeeperIds = User::whereHas('role', function($q) {
                $q->where('caption', 'Wisdom Keeper');
            })->pluck('id')->toArray();

            // Wisdom Keeper logic: show only Wisdom Keeper created books, sorted by highest reviews first
            return $query->whereIn('book.creator_id', $wisdomKeeperIds)
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
                ->map(function ($book) {
                    $book->image_cover = !empty($book->image_cover) ? url($book->image_cover) : null;
                    $book->url = !empty($book->url) ? url($book->url) : null;
                    if ($book->creator && !empty($book->creator->avatar)) {
                        $book->creator->avatar = url($book->creator->avatar);
                    }
                    return $book;
                });
        } else {
            // Guest/normal user logic: prioritize by engagement metrics
            return $query->select('book.*')
                ->selectRaw('
                    (SELECT COUNT(*) FROM book_like 
                    WHERE book_like.book_id = book.id
                    ) as likes_count
                ')
                ->selectRaw('
                    (SELECT COUNT(*) FROM book_review 
                    WHERE book_review.book_id = book.id
                    ) as review_count
                ')
                ->selectRaw('
                    (SELECT COALESCE(AVG(rating), 0) FROM book_review 
                    WHERE book_review.book_id = book.id
                    ) as avg_rating
                ')
                ->selectRaw('
                    (SELECT COUNT(*) FROM book_comment 
                    WHERE book_comment.book_id = book.id 
                    ) as comments_count
                ')
                ->selectRaw('
                    (SELECT COUNT(*) FROM book_share 
                    WHERE book_share.book_id = book.id
                    ) as share_count
                ')
                ->selectRaw('
                    (SELECT COUNT(*) FROM book_gift 
                    WHERE book_gift.book_id = book.id
                    ) as gift_count
                ')
                ->selectRaw('
                    (SELECT COUNT(*) FROM book_saved 
                    WHERE book_saved.book_id = book.id
                    ) as saved_items_count
                ')
                ->orderByRaw('
                    -- First by average rating
                    (SELECT COALESCE(AVG(rating), 0) FROM book_review 
                    WHERE book_review.book_id = book.id) DESC,
                    -- Then by engagement score
                    (
                        (SELECT COUNT(*) FROM book_like 
                        WHERE book_like.book_id = book.id) * 2 +
                        (SELECT COUNT(*) FROM book_saved 
                        WHERE book_saved.book_id = book.id) * 3 +
                        (SELECT COUNT(*) FROM book_comment 
                        WHERE book_comment.book_id = book.id) * 2 +
                        (SELECT COUNT(*) FROM book_share 
                        WHERE book_share.book_id = book.id) * 1.5 +
                        (SELECT COUNT(*) FROM book_gift 
                        WHERE book_gift.book_id = book.id) * 2
                    ) DESC,
                    -- Finally by id
                    book.id DESC
                ')
                ->limit(6)
                ->get()
                ->map(function ($book) {
                    $book->image_cover = !empty($book->image_cover) ? url($book->image_cover) : null;
                    $book->url = !empty($book->url) ? url($book->url) : null;
                    if ($book->creator && !empty($book->creator->avatar)) {
                        $book->creator->avatar = url($book->creator->avatar);
                    }
                    return $book;
                });
        }
    }

    public function directPayment(Request $request)
    {
        $user = auth()->user();

        if (!empty($user)) {
            $this->validate($request, [
                'item_id' => 'required',
            ]);

            $data = $request->except('_token');

            $bookid = $data['item_id'];
            $specifications = $data['specifications'] ?? null;
            $quantity = $data['quantity'] ?? 1;

            $book = Book::where('id', $bookid)
                ->first();

            if (!empty($book)) {
                $checkCourseForSale = checkBookForSale($book, $user);
                if ($checkCourseForSale != 'ok') {
                    return $checkCourseForSale;
                }

                $bookOrder = BookOrder::updateOrCreate([
                    'book_id' => $book->id,
                    'seller_id' => $book->creator_id,
                    'buyer_id' => $user->id,
                ], [
                    'quantity' => $quantity,
                    'status' => 'pending',
                    'created_at' => time()
                ]);


                Cart::updateOrCreate([
                    'creator_id' => $user->id,
                    'book_order_id' => $bookOrder->id,
                ], [
                    'created_at' => time()
                ]);

                return redirect('/cart');
            }
        }

        abort(404);
    }
}