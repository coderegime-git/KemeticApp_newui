<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCategory;
use Illuminate\Http\Request;
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

    public function index(Request $request, $category = null)
    {
        $creator = $request->get('creator', null);
        $search = $request->get('search', null);
        
        $seoSettings = getSeoMetas('books');
        $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('Books');
        $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('home.books');
        $pageRobot = getPageRobot('books');

        $bookCategories = BookCategory::all();

        $query = Book::orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc');

        $selectedCategory = null;

        if (!empty($category)) {
            $selectedCategory = $bookCategories->where('slug', $category)->first();
            if (!empty($selectedCategory)) {
                $query->where('category_id', $selectedCategory->id);
                $pageTitle .= ' ' . $selectedCategory->title;
                $pageDescription .= ' ' . $selectedCategory->title;
            }
        }

        if (!empty($creator) and is_numeric($creator)) {
            $query->where('creator_id', $creator);
        }

        if (!empty($search)) {
            $query->whereTranslationLike('title', "%$search%");
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

        $popularBooks = $this->getPopularBooks();
        $popularBook = $popularBooks->first(); // Get the single popular book

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
                $popularBooks = $this->getPopularBooks();

                $pageRobot = getPageRobot('books');

                // Calculate engagement metrics
                $totalEngagement = $book->likes_count + $book->comments_count + $book->saved_items_count;
                $ratingDisplay = $book->likes_count > 0 ? number_format(($book->likes_count / max($book->likes_count + 1, 1)) * 5, 1) : '4.98';

                $data = [
                    'pageTitle' => $book->title,
                    'pageDescription' => $book->meta_description ?? $book->title,
                    'pageMetaImage' => $book->getImage(),
                    'bookCategories' => $bookCategories,
                    'popularBooks' => $popularBooks,
                    'pageRobot' => $pageRobot,
                    'book' => $book,
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

    private function getPopularBooks()
    {
        return Book::withCount([
            'likes',
            'comments', 
            'share',
            'gift',
            'savedItems'
        ])
        ->orderByRaw('(
            (likes_count * 2) + 
            (saved_items_count * 3) + 
            (comments_count * 2) + 
            (share_count * 1.5) + 
            (gift_count * 2)
        ) DESC')
        ->limit(1)
        ->get();
    }
}