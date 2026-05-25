<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Api\Objects\UserObj;
use App\Http\Controllers\Controller;
use App\Models\Api\Book;
use App\Models\Api\User;
use App\Models\BookTranslation;
use App\Models\Api\BookCategory;
use App\Models\BookComment;
use App\Services\PdfResizerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    protected $laragonCertPath;
    protected $pdfResizer;

    public function __construct()
    {
        $pdfResizer = new PdfResizerService();
        $this->pdfResizer = $pdfResizer;
        // Laragon certificate path - adjust if different
        $this->laragonCertPath = "C:/laragon/etc/ssl/cert.pem";
        
        // Alternative paths to check
        $alternativePaths = [
            "C:/laragon/etc/ssl/cert.pem",
            "C:/laragon/etc/ssl/cacert.pem",
            "C:/laragon/etc/ssl/certs/ca-bundle.crt",
            base_path("cacert.pem"), // If you want to store in your project
            storage_path("app/certs/cacert.pem"), // Custom storage path
        ];
        
        // Find the first existing certificate file
        foreach ($alternativePaths as $path) {
            if (file_exists($path)) {
                $this->laragonCertPath = $path;
                break;
            }
        }
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

    public function index(Request $request)
    {
        $userid = $this->getUserIdFromToken($request);
            
        $searchQuery = $request->query('title');

        $page = $request->has('offset') ? (int) $request->get('offset') : null;
        $perPage  = $request->has('limit') ? (int) $request->get('limit') : null;

        $usePagination = !is_null($page) && !is_null($perPage);

        $actualOffset = $usePagination ? ($page * $perPage) : null;

        $query = Book::query();

        // Apply search filter if search query exists
        if (!empty($searchQuery)) {
            $query->whereHas('translations', function ($subQuery) use ($searchQuery) {
                $subQuery->where('title', 'like', '%' . $searchQuery . '%')
                    ->where('locale', 'en'); // Replace 'en' with your desired locale
            });
        }

        // Apply other filters
        $query = $this->handleFilters($request, $query);

        if ($userid) {
            // Get user's like statistics by book category
            $userLikesByCategory = DB::table('book_like')
                ->join('book', 'book_like.book_id', '=', 'book.id')
                ->where('book_like.user_id', $userid)
                ->whereNotNull('book.category_id')
                ->select(
                    'book.category_id',
                    DB::raw('COUNT(*) as likes')
                )
                ->groupBy('book.category_id')
                ->orderByDesc('likes')
                ->get();

            if ($userLikesByCategory->isNotEmpty()) {
                // Build CASE statement for ordering
                $caseStatements = [];
                foreach ($userLikesByCategory as $index => $category) {
                    $caseStatements[] = "WHEN category_id = {$category->category_id} THEN {$index}";
                }
                
                // Add other categories
                $caseStatements[] = "WHEN category_id IS NOT NULL THEN " . count($userLikesByCategory);
                $caseStatements[] = "WHEN category_id IS NULL THEN " . (count($userLikesByCategory) + 1);
                
                $caseSql = "CASE " . implode(' ', $caseStatements) . " END";
                
                $query->orderByRaw($caseSql);
            }
            
            // Secondary ordering
            $query->orderBy('updated_at', 'desc')
                  ->orderBy('created_at', 'desc');
        } else {
            // Default ordering for non-logged in users
            $query->orderBy('updated_at', 'desc')
                  ->orderBy('created_at', 'desc');
        }

        $total = $query->count();
        $paginatedBooks = $query->with(['translations', 'creator','comments.user', 'comments.replies.user', 'reviews.user'])
            ->skip($actualOffset)
            ->take($perPage)
            ->get()
            ->map(function ($book) use ($userid) {
                return $this->formatBookDetails($book, $userid);
            });

        $nextPage = null;
        if (($actualOffset + $paginatedBooks->count()) < $total) {
            $nextPage = $page + 1;
        }

        $user = $userid ? User::find($userid) : null;

        // $popularBooks = $this->getPopularBooks($user);
        $popularBooks = $this->getPopularBooks($user)
            ->map(function ($book) use ($userid) {
                return $this->formatBookDetails($book, $userid);
            });

        $popularBook = $popularBooks->first();

        $data = [
            'books' => $paginatedBooks,
            'popularBook' => $popularBook,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'has_more' => ($actualOffset + $paginatedBooks->count()) < $total,
                'next_page' => $nextPage
            ]
        ];

        // $books = $query->with(['translations', 'creator'])
        //     ->get()
        //     ->map(function ($book) use ($userid) {
        //         return $this->formatBookDetails($book, $userid);
        //     });

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $data);
    }

    public function show($id, Request $request)
    {
        $userid = $this->getUserIdFromToken($request);

        // Get the selected book
        $selectedBook = Book::with(['translations', 'creator','comments.user', 'comments.replies.user', 'reviews.user'])->find($id);
        abort_unless($selectedBook, 404);

        // Build the query for other books (excluding the selected one)
        $query = Book::query();
        
        // Apply search filter if exists (optional - you might want to remove this)
        $searchQuery = $request->query('title');
        if (!empty($searchQuery)) {
            $query->whereHas('translations', function ($subQuery) use ($searchQuery) {
                $subQuery->where('title', 'like', '%' . $searchQuery . '%')
                    ->where('locale', 'en');
            });
        }

        // Apply other filters
        $query = $this->handleFilters($request, $query);

        // Exclude the selected book
        $query->where('id', '!=', $id);

        if ($userid) {
            // Get user's like statistics by book category
            $userLikesByCategory = DB::table('book_like')
                ->join('book', 'book_like.book_id', '=', 'book.id')
                ->where('book_like.user_id', $userid)
                ->whereNotNull('book.category_id')
                ->select(
                    'book.category_id',
                    DB::raw('COUNT(*) as likes')
                )
                ->groupBy('book.category_id')
                ->orderByDesc('likes')
                ->get();

            if ($userLikesByCategory->isNotEmpty()) {
                // Build CASE statement for ordering
                $caseStatements = [];
                foreach ($userLikesByCategory as $index => $category) {
                    $caseStatements[] = "WHEN category_id = {$category->category_id} THEN {$index}";
                }
                
                // Add other categories
                $caseStatements[] = "WHEN category_id IS NOT NULL THEN " . count($userLikesByCategory);
                $caseStatements[] = "WHEN category_id IS NULL THEN " . (count($userLikesByCategory) + 1);
                
                $caseSql = "CASE " . implode(' ', $caseStatements) . " END";
                
                $query->orderByRaw($caseSql);
            }
            
            // Secondary ordering
            $query->orderBy('updated_at', 'desc')
                ->orderBy('created_at', 'desc');
        } else {
            // Default ordering for non-logged in users
            $query->orderBy('updated_at', 'desc')
                ->orderBy('created_at', 'desc');
        }

        // Get paginated other books
        $page = $request->has('offset') ? (int) $request->get('offset') : null;
        $perPage = $request->has('limit') ? (int) $request->get('limit') : null;
        
        $usePagination = !is_null($page) && !is_null($perPage);
        $actualOffset = $usePagination ? ($page * $perPage) : null;

        $total = $query->count() + 1; // +1 for the selected book

        // Get other books with pagination
        $otherBooks = $query->with(['translations', 'creator','comments.user', 'comments.replies.user', 'reviews.user'])
            ->when($usePagination, function ($q) use ($actualOffset, $perPage) {
                return $q->skip($actualOffset)->take($perPage);
            })
            ->get()
            ->map(function ($book) use ($userid) {
                return $this->formatBookDetails($book, $userid);
            });

        // Combine selected book with other books (selected book first)
        $allBooks = collect([$this->formatBookDetails($selectedBook, $userid)])
            ->concat($otherBooks);

        $nextPage = null;
        if ($usePagination && ($actualOffset + $otherBooks->count()) < ($total - 1)) {
            $nextPage = $page + 1;
        }

        $user = $userid ? User::find($userid) : null;

        $popularBooks = $this->getPopularBooks($user)
            ->map(function ($book) use ($userid) {
                return $this->formatBookDetails($book, $userid);
            });

        $popularBook = $popularBooks->first();

        $data = [
            'books' => $allBooks,
            'popularBook' => $popularBook,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'has_more' => $usePagination ? (($actualOffset + $otherBooks->count()) < ($total - 1)) : false,
                'next_page' => $nextPage
            ]
        ];

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $data);
    }
    
    // public function show($id, Request $request)
    // {
    //     $userid = $this->getUserIdFromToken($request);

    //     $book = Book::with(['translations', 'creator'])->find($id);
    //     abort_unless($book, 404);

    //     return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), [
    //         'book' => $this->formatBookDetails($book, $userid)
    //     ]);
    // }

    public function list(Request $request, $id = null)
    {
        $userid = $this->getUserIdFromToken($request);

        $query = Book::query()->with(['translations', 'creator','comments.user', 'comments.replies.user', 'reviews.user'])
            ->orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc');

        if (isset($id)) {
            $query = $query->where('id', $id)->get();
            if (!$query->count()) {
                abort(404);
            }
            $books = $this->formatBooksResponse($query, true, $userid);
        } else {
            $query = $this->handleFilters($request, $query);
            $books = $this->formatBooksResponse($query->get(), false, $userid);
        }

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $books);
    }

    public function handleFilters(Request $request, $query)
    {
        $userid = $this->getUserIdFromToken($request);

        $offset = $request->get('offset', null);
        $limit = $request->get('limit', null);

        if (!empty($offset) && !empty($limit)) {
            $query->skip($offset);
        }
        if (!empty($limit)) {
            $query->take($limit);
        }
        
        // Additional filters can be added here
        $search = $request->get('search', null);
        if (!empty($search)) {
            $query->whereHas('translations', function ($subQuery) use ($search) {
                $subQuery->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $category_id = $request->get('category_id', null);
        if (!empty($category_id)) {
            $query->where('category_id', $category_id);
        }

        // Filter by author if provided
        $authorId = $request->get('author_id', null);
        if (!empty($authorId)) {
            $query->where('creator_id', $authorId);
        }

        // Filter by creation date if provided
        $createdAfter = $request->get('created_after', null);
        if (!empty($createdAfter)) {
            $query->where('created_at', '>=', $createdAfter);
        }

        $createdBefore = $request->get('created_before', null);
        if (!empty($createdBefore)) {
            $query->where('created_at', '<=', $createdBefore);
        }
        
        return $query;
    }

    private function formatBookDetails($book, $userid = null)
    {
        $translation = $book->translation ?? $book->translations->first();
        $isLiked = $userid ? $book->likes()->where('user_id', $userid)->exists() : false;
        $isSaved = $userid ? $book->savedItems()->where('user_id', $userid)->exists() : false;

        $authorName = '';
        if ($book->creator) {
            $authorName = $book->creator->full_name ?? $book->creator->fullname ?? '';
        } elseif ($book->user) {
            $authorName = $book->user->full_name ?? $book->user->fullname ?? '';
        }

        // Handle categories - check if it's a single model or collection
        $categories = [];
        
        if ($book->categories) {
            // If categories is a collection (many-to-many)
            if (method_exists($book->categories, 'map')) {
                $categories = $book->categories->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->title,
                    ];
                })->toArray();
            } 
            // If categories is a single model (belongsTo relationship)
            else {
                $categories = [[
                    'id' => $book->categories->id,
                    'name' => $book->categories->title,
                ]];
            }
        }

        return [
            'id' => $book->id,
            'title' => $translation->title ?? '',
            'image_cover' => url($book->image_cover),
            'image_path' => url($book->url),
            'price' => $book->price,
            'formatted_price' => $book->formatted_price,
            'is_free' => $book->is_free,
            'category' => $categories,
            'type' => $book->type,
            'auth_has_bought' => $book->checkUserHasBought($userid),
            'description' => truncate($translation->description ?? '', 160),
            'content' => $translation->content ?? '',
            'created_at' => $book->created_at,
            'updated_at' => $book->updated_at,
            'userid' => $book->creator_id,
            'author' =>  $authorName ?? '',
            'slug' => $book->slug,
            'like_count' => $book->like_count ?? 0,
            'share_count' => $book->share_count ?? 0,
            'gift_count' => $book->gift_count ?? 0,
            'comment_count' => $book->comments->count() ?? 0,
            'saved_count' => $book->saved_count ?? 0,
            'review_count' => $book->reviews->count() ?? 0,
            'is_liked' => $isLiked,
            'is_saved' => $isSaved, 
            'rate' => $book->getRate(),
            'reviews' => $book->reviews ? $book->reviews->map(function ($item) {
                return [
                    'id' => $item->id,
                    'user_id' => $item->user_id,
                    'book_id' => $item->book_id,
                    'review' => $item->review,
                    'rating' => $item->rating,
                    'created_at' => $item->created_at, // Convert to timestamp
                    'username' => $item->user->full_name,
                    'avatar' => $item->user ? url($item->user->getAvatar()) : '',
                ];
            }) : [],
            'comments' => $book->comments ? $book->comments->map(function ($item) {
                return [
                    'id'         => $item->id,
                    'user_id'    => $item->user_id,
                    'create_at'  => $item->created_at,
                    'comment'    => $item->content,
                    'user' => [
                        'full_name' => $item->user->full_name ?? '',
                        'avatar' => $item->user ? url($item->user->getAvatar()) : '',
                        'userid' => $item->user_id,
                    ],
                    'replies' => $item->replies ? $item->replies->map(function ($reply) {
                        return [
                            'id'         => $reply->id,
                            'content'    => $reply->content,
                            'created_at' => $reply->created_at,
                            'user' => [
                                'full_name' => $reply->user->full_name ?? '',
                                'avatar'    => $reply->user ? url($reply->user->getAvatar()) : '',
                            ],
                        ];
                    }) : [],
                ];
            }) : [],
            'translations' => $book->translations->map(function ($translation) {
                return [
                    'locale' => $translation->locale,
                    'title' => $translation->title,
                    'description' => $translation->description,
                    'content' => $translation->content,
                ];
            }) ?? [],
        ];
    }

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

    private function formatBooksResponse($books, $single = false, $userid = null)
    {
        $formattedBooks = $books->map(function ($book) use ($userid) {
            return $this->formatBookDetails($book, $userid);
        });

        if ($single) {
            return [
                'book' => $formattedBooks->first()
            ];
        }
        return [
            'count' => count($formattedBooks),
            'books' => $formattedBooks
        ];
    }

    public function bookcategory(){

        $categories=BookCategory::all()->map(function($category){
            return $category->details ;
        }) ;

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'),$categories);
    }

    public function store(Request $request)
    {
        try {
            // Get user ID from token
            $userid = $this->getUserIdFromToken($request);
            
            if (!$userid) {
                return apiResponse2(0, 'unauthorized', trans('api.auth.unauthorized'), null, 401);
            }

            ini_set('upload_max_filesize', '250M');
            ini_set('post_max_size', '250M');
            ini_set('max_execution_time', '300');
            ini_set('max_input_time', '300');
            
            // Validate request
            $validator = \Validator::make($request->all(), [
                'locale' => 'required|string',
                'title' => 'required|string|max:255',
                'category_id' => 'required|numeric|exists:book_categories,id',
                // 'image_cover' => 'required|string',
                // 'image_path' => 'required|string',
                'type' => 'required|string|in:Print,E-book,Audio Book',
                'price' => 'nullable|numeric|min:0',
                'description' => 'required|string',
                'content' => 'required|string',
            ]);
            
            if ($validator->fails()) {
                return apiResponse2(0, 'validation_error', trans('api.public.validation_error'), [
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $data = $request->all();
            
            $pdfService = new PdfResizerService();

            $filename = '';
            $video = $request->file('image_path');
            $filename = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();
            $videoPath = public_path('store/scrolls');
            if (!file_exists($videoPath)) {
                mkdir($videoPath, 0777, true);
            }
            $video->move($videoPath, $filename);

            $filename1 = '';
            $video1 = $request->file('image_cover');
            $filename1 = time() . '_' . uniqid() . '.' . $video1->getClientOriginalExtension();
            $videoPath1 = public_path('store/scrolls');
            if (!file_exists($videoPath1)) {
                mkdir($videoPath1, 0777, true);
            }
            $video1->move($videoPath1, $filename1);

            $filename2 = '';
            $video2 = $request->file('cover_pdf');
            $filename2 = time() . '_' . uniqid() . '.' . $video2->getClientOriginalExtension();
            $videoPath2 = public_path('store/scrolls');
            if (!file_exists($videoPath2)) {
                mkdir($videoPath2, 0777, true);
            }
            $video2->move($videoPath2, $filename2);

            $image_cover = "/store/scrolls/" . $filename1;
        
            $pdfurl = url("/store/scrolls/" . $filename);
            
            $coverpdfurl = url("/store/scrolls/" . $filename2);

            if($data['type'] == 'Print')
            {
                $covercheck = $pdfService->resizeForLulu(
                    $coverpdfurl, // interior PDF
                    false                // no full bleed
                );
                
                $coverpageCount = $covercheck['page_count'];

                if ($coverpageCount > 1) {
                    return apiResponse2(0, 'Cover PDF Upload Error',
                        'Cover PDF must be a single page only. Your file contains ' . $coverpageCount . ' pages.',
                        ['page_count' => $coverpageCount],
                        422
                    );
                }

                $interior        = $pdfService->resizeForLulu($pdfurl, false);
                $interiorPdfPath = str_replace(public_path(), '', $interior['local_path']);
                $pageCount       = $interior['page_count'];

                // ── 3. Get Lulu token ─────────────────────────────────────
                $token        = $this->getLuluAccessTokenUsingCurl();
                $podPackageId = "0600X0900BWSTDPB060UW444MXX";

                $interiorPublicUrl = url(str_replace('\\', '/', ltrim($interiorPdfPath, '/\\')));

                // ── 4. Validate interior (POST + GET poll) ────────────────
                $interiorValidation = $this->validateLuluFile('interior', $interiorPublicUrl, $token);

                if (!empty($interiorValidation['errors'])) {
                    return apiResponse2(0, 'Interior PDF Validation Error',
                        $this->formatLuluValidationErrors($interiorValidation['errors']),
                        ['errors' => $interiorValidation['errors']],
                        422
                    );
                }

                // ── 5. Get cover dimensions ───────────────────────────────
                $dimensions = $this->getLuluCoverDimensionsFromApi($podPackageId, $pageCount, $token);

                if (!isset($dimensions['width']) || !isset($dimensions['height'])) {
                    return apiResponse2(0, 'Dimension Error',
                        'Could not calculate cover dimensions from Lulu. Please check your interior PDF and try again.',
                        null,
                        422
                    );
                }

                // ── 6. Generate resized cover ─────────────────────────────
                $cover          = $pdfService->generateCoverFromDimensions(
                    $coverpdfurl,
                    (float) $dimensions['width'],
                    (float) $dimensions['height']
                );
                $coverPdfPath   = str_replace(public_path(), '', $cover['local_path']);
                $coverPublicUrl = url(str_replace('\\', '/', ltrim($coverPdfPath, '/\\')));

                // ── 7. Validate cover (POST + GET poll) ───────────────────
                $coverValidation = $this->validateLuluFile('cover', $coverPublicUrl, $token, $podPackageId, $pageCount);

                if (!empty($coverValidation['errors'])) {
                    return apiResponse2(0, 'Cover PDF Validation Error',
                        $this->formatLuluValidationErrors($coverValidation['errors']),
                        ['errors' => $coverValidation['errors']],
                        422
                    );
                }
            }
            else
            {
                $interiorPdfPath = "/store/scrolls/" . $filename;
                $coverPdfPath = "/store/scrolls/" . $filename2; // Use same path for cover if no separate cover is generated
                $pageCount = 0;
            }
            
            // Create the book
            $book = Book::create([
                'creator_id' => $userid,
                'category_id' => $data['category_id'],
                'slug' => Book::makeSlug($data['title']),
                'image_cover' => $image_cover,     // ✅ Lulu cover PDF
                'url'         => $interiorPdfPath,  // ✅ Lulu interior PDF
                'cover_pdf'   => $coverPdfPath,  
                'page_count' => $pageCount,
                'price' => $data['price'] ?? 0,
                'print_price' => $data['print_price'] ?? 0,
                'shipping_price' => $data['shipping_price'] ?? 0,
                'platform_price' => $data['platform_price'] ?? 0,
                'book_price' => $data['book_price'] ?? 0,
                'type' => $data['type'],
                'status' => 'active', // or 'pending' depending on your workflow
                'created_at' => time(),
                'updated_at' => time(),
            ]);
            
            if (!$book) {
                return apiResponse2(0, 'creation_failed', trans('api.public.creation_failed'), null, 500);
            }
            
            // Create translation
            BookTranslation::updateOrCreate([
                'book_id' => $book->id,
                'locale' => mb_strtolower($data['locale']),
            ], [
                'title' => $data['title'],
                'description' => $data['description'],
                'content' => $data['content'],
            ]);

            // Format response
            $formattedBook = $this->formatBookDetails($book->fresh(), $userid);
            
            return apiResponse2(1, 'created', trans('api.public.created'), [
                'book' => $formattedBook,
                'message' => trans('api.book.created_successfully')
            ], 201);
            
        } catch (\Exception $e) {
            \Log::error('Book creation error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            
            return apiResponse2(0, 'server_error', trans('api.public.server_error'), [
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    private function formatLuluValidationErrors(array $errors): string
    {
        if (empty($errors)) {
            return 'An unknown validation error occurred.';
        }

        $messages = [];

        foreach ($errors as $error) {
            // Lulu errors can come as strings or as objects with a 'message' key
            if (is_string($error)) {
                $messages[] = $error;
            } elseif (is_array($error)) {
                if (!empty($error['message'])) {
                    $messages[] = $error['message'];
                } elseif (!empty($error['detail'])) {
                    $messages[] = $error['detail'];
                } elseif (!empty($error['field'])) {
                    $messages[] = $error['field'] . ': ' . ($error['description'] ?? 'Invalid value');
                } else {
                    $messages[] = json_encode($error);
                }
            }
        }

        return implode(' | ', array_filter($messages)) ?: 'Validation failed with unknown errors.';
    }

    public function getLuluAccessTokenUsingCurl()
    {
        
        $url = "https://api.lulu.com/auth/realms/glasstree/protocol/openid-connect/token";
        //$url = "https://api.sandbox.lulu.com/auth/realms/glasstree/protocol/openid-connect/token";
        $authorization = "OWY2MDViMTUtNmMzYy00OWU1LTkxOWItODRmNzM0MWEyMjgzOk50cVpOa2N2aE1nNlJpb25FaEVSbWpyZW5EQTJYU3dW";
        // $authorization = "9f605b15-6c3c-49e5-919b-84f7341a2283:NtqZNkcvhMg6RionEhERmjrenDA2XSwV"; // Basic xxxx

        $laragonCertPath = "C:/laragon/etc/ssl/cert.pem";
        $verifyOption = file_exists($laragonCertPath) ? $laragonCertPath : false;

        
        $curl = curl_init();
         $authorization = "OWY2MDViMTUtNmMzYy00OWU1LTkxOWItODRmNzM0MWEyMjgzOk50cVpOa2N2aE1nNlJpb25FaEVSbWpyZW5EQTJYU3dW";
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.lulu.com/auth/realms/glasstree/protocol/openid-connect/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic ' . $authorization
            ],
        ]);
        
        
        if ($this->laragonCertPath && file_exists($this->laragonCertPath)) {
            // Use Laragon certificate
            $options[CURLOPT_CAINFO] = $this->laragonCertPath;
            $options[CURLOPT_CAPATH] = dirname($this->laragonCertPath);
            $options[CURLOPT_SSL_VERIFYPEER] = true;
            $options[CURLOPT_SSL_VERIFYHOST] = 2;
        } else {
            // Disable SSL verification if no certificate found (NOT RECOMMENDED for production)
            $options[CURLOPT_SSL_VERIFYPEER] = false;
            $options[CURLOPT_SSL_VERIFYHOST] = false;
            
            \Log::warning('SSL certificate verification disabled. Certificate file not found.');
        }
        
        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
          
        if ($error) {
            $errorNo = curl_errno($curl);
            \Log::error("cURL Error #{$errorNo}: {$error}");
            \Log::error("Certificate path used: " . ($this->laragonCertPath ?? 'none'));
            \Log::error("File exists: " . (file_exists($this->laragonCertPath) ? 'Yes' : 'No'));
        }
        
        curl_close($curl);

        $data = json_decode($response, true);

        return $data['access_token'] ?? null;
    }

    public function getLuluPriceUsingCurl(Request $request, $method = 'POST', $token = null)
    {
        if (!$token) {
            $token = $this->getLuluAccessTokenUsingCurl();
        }

        $lineItems[] = [
            'page_count' => $request->pages,
            'pod_package_id' => '0600X0900BWSTDPB060UW444MXX', // Default package
            'quantity' => '1'
        ];

        $data = [
            'line_items' => $lineItems,
            'shipping_address' => [
                'city' => 'washington',
                'country_code' => 'US',
                'postcode' => '20540',
                'state_code' => 'DC',
                // 'street1' => ($addressData['house_no'] ?? '') . ' ' . ($addressData['address'] ?? ''),
                'street1' => '1600 Pennsylvania Avenue NW',
                'phone_number' => '+1234567890',
            ],
            'shipping_option' => 'MAIL' // Standard shipping
        ];


        $curl = curl_init();
        
        $url = "https://api.lulu.com/print-job-cost-calculations/";
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer '. $token,
                'Cache-Control: no-cache',
                'Content-Type: application/json'
            ],
        ]);

        // Certificate verification handling
        if ($this->laragonCertPath && file_exists($this->laragonCertPath)) {
            $options[CURLOPT_CAINFO] = $this->laragonCertPath;
            $options[CURLOPT_CAPATH] = dirname($this->laragonCertPath);
            $options[CURLOPT_SSL_VERIFYPEER] = true;
            $options[CURLOPT_SSL_VERIFYHOST] = 2;
        } else {
            $options[CURLOPT_SSL_VERIFYPEER] = false;
            $options[CURLOPT_SSL_VERIFYHOST] = false;
        }

        if (!empty($data)) {
            $options[CURLOPT_POSTFIELDS] = json_encode($data);
        }

        curl_setopt_array($curl, $options);
        
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);

        if ($error) {
            $errorNo = curl_errno($curl);
            \Log::error("cURL Error #{$errorNo}: {$error}");
            \Log::error("Certificate path used: " . ($this->laragonCertPath ?? 'none'));
            \Log::error("File exists: " . (file_exists($this->laragonCertPath) ? 'Yes' : 'No'));
        }

        //dd($response);
        
        // Enhanced error logging
        if ($error) {
            $errorNo = curl_errno($curl);
            $errorInfo = [
                'error_no' => $errorNo,
                'error_msg' => $error,
                'endpoint' => $endpoint,
                'cert_path' => $this->laragonCertPath,
                'cert_exists' => file_exists($this->laragonCertPath) ? 'Yes' : 'No',
                'url' => $url,
            ];
            \Log::error('Lulu API cURL Error', $errorInfo);
        }
        
        curl_close($curl);

        $responseData = json_decode($response, true);

         if (json_last_error() !== JSON_ERROR_NONE) {
            \Log::error('Lulu API Invalid JSON Response', [
                'response' => $response,
                'json_error' => json_last_error_msg()
            ]);
            
            return [
                'success' => false,
                'message' => 'Invalid response from Lulu API',
                'print_price' => 0
            ];
        }
        
        // Check if we got the expected data structure
        if (isset($responseData['line_item_costs'][0]['total_cost_incl_tax'])) {
            $printPrice = (float) $responseData['line_item_costs'][0]['total_cost_incl_tax'];
            
            return [
                'success' => true,
                'print_price' => $printPrice,
                // 'raw_response' => $responseData // Optional: include raw response for debugging
            ];
        } else {
            \Log::error('Lulu API Unexpected Response Structure', [
                'response_data' => $responseData
            ]);

            if (isset($responseData['line_items'])) {
                $errors = [];
                foreach ($responseData['line_items'] as $lineItemErrors) {
                    foreach ($lineItemErrors as $field => $messages) {
                        $errors = array_merge($errors, (array) $messages);
                    }
                }

                return [
                    'success' => false,
                    'message' => str_replace('Line item 0: ', '', implode(', ', $errors)),
                    'print_price' => 0,
                    'raw_response' => $responseData
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Unexpected response structure from Lulu API',
                'print_price' => 0,
                'raw_response' => $responseData
            ];
        }
       
        //return $responseData;
    }

    private function validateLuluFile($type, $sourceUrl, $token, $podPackageId = null, $pageCount = null)
    {
        // ── STEP 1: POST to start validation, get the ID ──────────────────────
        $postUrl = $type == 'interior'
            ? 'https://api.lulu.com/validate-interior/'
            : 'https://api.lulu.com/validate-cover/';

        $postData = ['source_url' => $sourceUrl];
        if ($type == 'cover') {
            $postData['pod_package_id']       = $podPackageId;
            $postData['interior_page_count']  = $pageCount;
        }

        $postOptions = [
            CURLOPT_URL            => $postUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 60,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => json_encode($postData),
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $token,
                'Cache-Control: no-cache',
                'Content-Type: application/json'
            ],
        ];

        $this->applySslOptions($postOptions);

        $curl = curl_init();
        curl_setopt_array($curl, $postOptions);
        $postResponse = curl_exec($curl);

        if (curl_error($curl)) {
            $error = curl_error($curl);
            \Log::error("cURL POST Error Lulu Validation ($type): " . $error);
            curl_close($curl);
            return ['errors' => [['message' => $error]]];
        }

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $postData = json_decode($postResponse, true);

        \Log::info("Lulu Validation POST ($type) - HTTP $httpCode", ['response' => $postData]);

        // ── STEP 2: Extract validation ID from POST response ──────────────────
        $validationId = $postData['id'] ?? null;

        if (!$validationId) {
            \Log::error("Lulu Validation: No ID returned for $type", ['response' => $postData]);
            return ['errors' => [['message' => 'Validation ID not returned from Lulu API.']]];
        }

        // ── STEP 3: GET poll until status is complete ─────────────────────────
        $getUrl = $type == 'interior'
            ? "https://api.lulu.com/validate-interior/{$validationId}/"
            : "https://api.lulu.com/validate-cover/{$validationId}/";

        $maxAttempts  = 20;  // 20 x 3s = 60 seconds max wait
        $sleepSeconds = 3;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {

            sleep($sleepSeconds);

            $getOptions = [
                CURLOPT_URL            => $getUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => '',
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => 'GET',
                CURLOPT_HTTPHEADER     => [
                    'Authorization: Bearer ' . $token,
                    'Cache-Control: no-cache',
                ],
            ];

            $this->applySslOptions($getOptions);

            $curl = curl_init();
            curl_setopt_array($curl, $getOptions);
            $getResponse = curl_exec($curl);

            if (curl_error($curl)) {
                \Log::error("cURL GET Error Lulu Validation ($type, attempt $attempt): " . curl_error($curl));
                curl_close($curl);
                continue; // retry on network error
            }
            curl_close($curl);

            $pollData = json_decode($getResponse, true);
            $status   = $pollData['status'] ?? '';

            \Log::info("Lulu Validation GET ($type, attempt $attempt)", [
                'validation_id' => $validationId,
                'status'        => $status,
                'data'          => $pollData,
            ]);

            // Still processing — keep polling
            if (in_array($status, ['VALIDATING', 'NORMALIZING', 'PENDING'])) {
                continue;
            }

            // ── Validation finished ───────────────────────────────────────────
            // If errors array is non-empty, caller will show toast
            return $pollData;
        }

        // ── Timed out ─────────────────────────────────────────────────────────
        \Log::error("Lulu Validation timed out after {$maxAttempts} attempts ($type)", [
            'validation_id' => $validationId,
        ]);

        return ['errors' => [['message' => 'Lulu ' . $type . ' validation timed out. Please try again.']]];
    }

    /**
     * Apply SSL options to a cURL options array.
     */
    private function applySslOptions(array &$options): void
    {
        if ($this->laragonCertPath && file_exists($this->laragonCertPath)) {
            $options[CURLOPT_CAINFO]         = $this->laragonCertPath;
            $options[CURLOPT_CAPATH]         = dirname($this->laragonCertPath);
            $options[CURLOPT_SSL_VERIFYPEER] = true;
            $options[CURLOPT_SSL_VERIFYHOST] = 2;
        } else {
            $options[CURLOPT_SSL_VERIFYPEER] = false;
            $options[CURLOPT_SSL_VERIFYHOST] = false;
            \Log::warning('SSL certificate verification disabled. Certificate file not found.');
        }
    }

    private function getLuluCoverDimensionsFromApi($podPackageId, $pageCount, $token)
    {
        $curl = curl_init();
        $url = 'https://api.lulu.com/cover-dimensions/';

        $data = [
            'pod_package_id' => $podPackageId,
            'interior_page_count' => $pageCount
        ];

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Cache-Control: no-cache',
                'Content-Type: application/json'
            ],
        ];

        if ($this->laragonCertPath && file_exists($this->laragonCertPath)) {
            $options[CURLOPT_CAINFO] = $this->laragonCertPath;
            $options[CURLOPT_CAPATH] = dirname($this->laragonCertPath);
            $options[CURLOPT_SSL_VERIFYPEER] = true;
            $options[CURLOPT_SSL_VERIFYHOST] = 2;
        } else {
            $options[CURLOPT_SSL_VERIFYPEER] = false;
            $options[CURLOPT_SSL_VERIFYHOST] = false;
        }

        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);

        if (curl_error($curl)) {
            \Log::error("cURL Error Lulu Cover Dimensions: " . curl_error($curl));
            return null;
        }
        curl_close($curl);

        return json_decode($response, true);
    }

    public function booklike(Request $request, $id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $book = Book::where('id', $id)->first();
        
        if (!$book) {
            return response()->json([
                'status' => 'error',
                'message' => 'Book not found'
            ], 404);
        }

        $like = DB::table('book_like')
            ->where('book_id', $book->id)
            ->where('user_id', $userid)
            ->exists();

        if ($like) {
            DB::table('book_like')
                ->where('book_id', $book->id)
                ->where('user_id', $userid)
                ->delete();
            
            Book::where('id', $id)->decrement('like_count');
            $action = 'unliked';
        } else {
            DB::table('book_like')->insert([
                'user_id' => $userid,
                'book_id' => $book->id
            ]);
            
            Book::where('id', $id)->increment('like_count');
            $action = 'liked';
        }

        // Refresh the book to get updated like_count
        $book = $book->fresh();

        return response()->json([
            'status' => 'success',
            'message' => "Book {$action} successfully",
            'data' => [
                'liked' => !$like,
                'like_count' => $book->like_count ?? 0
            ]
        ]);
    }

    public function bookshare(Request $request, $id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $book = Book::where('id', $id)->first();

        if (!$book) {
            return response()->json([
                'status' => 'error',
                'message' => 'Book not found'
            ], 404);
        }

        $now = time();

        $share = $book->share()->create([
            'user_id' => $userid,
            'book_id' => $book->id,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        Book::where('id', $id)->increment('share_count');

        $shareLink = url('/launch') . '?' . http_build_query([
            'page'         => 'scrolls',
            'value'        => $book->id   // or any unique share code
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Book Shared successfully',
            'data' => [
                'share' => $share,
                'share_link' => $shareLink,
            ]
        ], 201);
    }

    public function bookgift(Request $request, $id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $book = Book::where('id', $id)->first();

        if (!$book) {
            return response()->json([
                'status' => 'error',
                'message' => 'Book not found'
            ], 404);
        }

        $now = time();

        $gift = $book->gift()->create([
            'user_id' => $userid,
            'book_id' => $book->id,
            'gift_id' => $request->gift_id, 
            'created_at' => $now,
            'updated_at' => $now
        ]);

        Book::where('id', $id)->increment('gift_count');
        
        return response()->json([
            'status' => 'success',
            'message' => 'Gift Send successfully',
            'data' => $gift
        ], 201);
    }

    public function bookcomment(Request $request, $id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $book = Book::where('id', $id)->first();

        if (!$book) {
            return response()->json([
                'status' => 'error',
                'message' => 'Book not found'
            ], 404);
        }

        $now = time();
        $comment = $book->comments()->create([
            'user_id' => $userid,
            'content' => $request->get('content'),
            'created_at' => $now,
            'updated_at' => $now
        ]);

        Book::where('id', $id)->increment('comments_count');

        $responseData = [
            'id' => $comment->id,
            'user_id' => $userid,
            'book_id' => $id,
            "create_at" => $now,
            "comment" => $request->get('content'),
            "user" => [
                "full_name" => $user->full_name,
                "avatar" => url($user->getAvatar())
            ],
            'replies' => [] 
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Comment added successfully',
            'data' => $responseData
        ], 201);
    }

    public function bookreply(Request $request, $id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $comment = BookComment::where('id', $id)->first();

        if (!$comment) {
            abort(404);
        }

        $now = time();
        $reply = BookComment::create([
            'user_id' => $userid,
            'book_id' => $comment->book_id,
            'content' => $request->get('content'),
            'reply_id' => $comment->id,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $replies = BookComment::where('reply_id', $comment->id)
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
                'book_id' => $comment->book_id,
                'content' => $comment->content,
                'created_at' => $comment->created_at, // Convert to timestamp
                "user" => [
                    "full_name" => $comment->user->full_name,
                    "avatar" => url($comment->user->getAvatar())
                ],
                'replies' => $formattedReplies
            ]
            // 'data' => $comment->load('user')
        ], 201);
    }

    public function bookreport(Request $request, $id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $book = Book::where('id', $id)->first();

        $now = time();

        $report = $book->reports()->create([
            'user_id' => $userid,
            'book_id' => $book->id,
            'reason' => $request->reason,
            'description' => $request->description,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        Book::where('id', $id)->increment('report_count');

        return response()->json([
            'status' => 'success',
            'message' => 'Book reported successfully',
            'data' => $report
        ], 201);
    }

    public function booksave(Request $request,$id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $book = Book::where('id', $id)->first();

        if (!$book) {
            return response()->json([
                'status' => 'error',
                'message' => 'Book not found'
            ], 404);
        }

        $now = time();

        
        $save = DB::table('book_saved')
            ->where('book_id', $book->id)
            ->where('user_id', $userid)
            ->exists();

        if ($save) {
            DB::table('book_saved')
            ->where('book_id', $book->id)
            ->where('user_id', $userid)
            ->delete(); 

            Book::where('id', $id)->decrement('saved_count');
            $action = 'unsaved';
        } else {
            DB::table('book_saved')->insert([
                'user_id' => $userid,
                'book_id' => $book->id,
                'created_at' => $now,
                'updated_at' => $now
            ]);
            
            Book::where('id', $id)->increment('saved_count');
            $action = 'saved';
        }
        
        return response()->json([
            'status' => 'success',
            'message' => "Book {$action} successfully",
            'data' => [
                'saved' => !$save,
                'saved_count' => $book->saved_count
            ]
        ], 201);
    }

    public function bookreview(Request $request, $id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $now = time();

        $book = Book::where('id', $id)->first();

        $review = $book->reviews()->create([
            'user_id' => $userid,
            'book_id' => $book->id,
            'review' => $request->review,
            'rating' => $request->rating,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $book->increment('review_count');

        return response()->json([
            'status' => 'success',
            'message' => 'Review added successfully',
            'data' => [
                'id' => $review->id,
                'user_id' => $review->user_id,
                'book_id' => $review->book_id,
                'review' => $review->review,
                'rating' => $review->rating,
                'created_at' => $review->created_at, // Convert to timestamp
                'username' => $review->user->full_name,
                'avatar' => $review->user ? url($review->user->getAvatar()) : '',
            ]
            // 'data' => $comment->load('user')
        ], 201);
    }

}