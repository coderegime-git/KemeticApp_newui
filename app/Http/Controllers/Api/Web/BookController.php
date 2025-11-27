<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Api\Objects\UserObj;
use App\Http\Controllers\Controller;
use App\Models\Api\Book;
use App\Models\Api\User;
use App\Models\BookTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
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
            return $user ? $user->id : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function index(Request $request)
    {
        $userid = $this->getUserIdFromToken($request);
            
        $searchQuery = $request->query('title');

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

        $books = $query->with(['translations', 'creator'])
            ->orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($book) use ($userid) {
                return $this->formatBookDetails($book, $userid);
            });

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $books);
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
            'description' => truncate($translation->description ?? '', 160),
            'content' => $translation->content ?? '',
            'created_at' => $book->created_at,
            'updated_at' => $book->updated_at,
            'author' =>  $authorName ?? '',
            'slug' => $book->slug,
            'like_count' => $book->like_count ?? 0,
            'share_count' => $book->share_count ?? 0,
            'gift_count' => $book->gift_count ?? 0,
            'comment_count' => $book->comments_count ?? 0,
            'saved_count' => $book->saved_count ?? 0,
            'is_liked' => $isLiked,
            'is_saved' => $isSaved,
            'comments' => $book->comments ? $book->comments->map(function ($item) {
                return [
                    'user' => [
                        'full_name' => $item->user->full_name ?? '',
                        'avatar' => $item->user ? url($item->user->getAvatar()) : '',
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
}