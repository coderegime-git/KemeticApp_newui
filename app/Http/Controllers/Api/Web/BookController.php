<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Api\Objects\UserObj;
use App\Http\Controllers\Controller;
use App\Models\Api\Book;
use App\Models\Api\User;
use App\Models\BookTranslation;
use App\Models\Api\BookCategory;
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
        $paginatedBooks = $query->with(['translations', 'creator'])
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

        $data = [
            'books' => $paginatedBooks,
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

        $book = Book::with(['translations', 'creator'])->find($id);
        abort_unless($book, 404);

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), [
            'book' => $this->formatBookDetails($book, $userid)
        ]);
    }

    public function list(Request $request, $id = null)
    {
        $userid = $this->getUserIdFromToken($request);

        $query = Book::query()->with(['translations', 'creator'])
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
            'comment_count' => $book->comments_count ?? 0,
            'saved_count' => $book->saved_count ?? 0,
            'review_count' => $book->review_count ?? 0,
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
                    'user' => [
                        'full_name' => $item->user->full_name ?? '',
                        'avatar' => $item->user ? url($item->user->getAvatar()) : '',
                        'userid' => $item->user_id,
                    ],
                    'create_at' => $item->created_at,
                    'comment' => $item->content,
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
            $image_cover = "/store/scrolls/" . $filename1;
        
            $pdfurl = url("/store/scrolls/" . $filename);

            if($data['type'] == 'Print')
            {
                $interior = $pdfService->resizeForLulu(
                    $pdfurl, // interior PDF
                    false                // no full bleed
                );

                $interiorPdfPath = str_replace(public_path(), '', $interior['local_path']);
                $pageCount = $interior['page_count'];

                $cover = $pdfService->generateCoverFromPdf(
                    $pdfurl, // cover PDF
                    $pageCount
                );

                $coverPdfPath = str_replace(public_path(), '', $cover['local_path']);
            }
            else
            {
                $interiorPdfPath = "/store/scrolls/" . $filename;
                $coverPdfPath = "/store/scrolls/" . $filename; // Use same path for cover if no separate cover is generated
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
            
            return [
                'success' => false,
                'message' => 'Unexpected response structure from Lulu API',
                'print_price' => 0,
                'raw_response' => $responseData
            ];
        }
       
        //return $responseData;
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
        
        return response()->json([
            'status' => 'success',
            'message' => 'Book Shared successfully',
            'data' => $share
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
            "user" => [
                "full_name" => $user->full_name,
                "avatar" => url($user->getAvatar())
            ],
            "create_at" => $now,
            "comment" => $request->get('content')
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Comment added successfully',
            'data' => $responseData
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