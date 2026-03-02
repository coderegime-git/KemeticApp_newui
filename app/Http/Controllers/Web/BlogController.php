<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\User;
use App\Models\BlogCategory;
use App\Models\Translation\BlogTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $user = null;

        $activeSubscribe = "";

        if (auth()->check()) {
            $user = auth()->user();
        }

        $data = $request->all();
        
        $author = $request->get('author', null);
        $search = $request->get('search', null);
        $categoryId = $request->get('category_id', null);
        
        $seoSettings = getSeoMetas('blog');
        $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('home.blog');
        $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('home.blog');
        $pageRobot = getPageRobot('blog');

        $blogCategories = BlogCategory::all();

        $query = Blog::where('status', 'publish')
            ->orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc');

        $selectedCategory = null;

        if (!empty($category)) {
            $selectedCategory = $category;
        }

        if (!empty($author) and is_numeric($author)) {
            $query->where('author_id', $author);
        }

        if (!empty($search)) {
            $query->whereTranslationLike('title', "%$search%");
        }

        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }


        $blogCount = $query->count();

        $blog = $query->with([
            'category',
            'author' => function ($query) {
                $query->select('id', 'full_name', 'avatar', 'role_id', 'role_name');
            }
        ])
            ->withCount('comments')
            ->paginate(8);

        $popularPosts = $this->getPopularPosts($user);

        $selectedCategory = null;

        if (!empty($data['category_id'])) {
            $selectedCategory = BlogCategory::where('id', $data['category_id'])->first();
        }


        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'blog' => $blog,
            'blogCount' => $blogCount,
            'blogCategories' => $blogCategories,
            'popularPosts' => $popularPosts,
            'selectedCategory' =>$selectedCategory
        ];

        return view(getTemplate() . '.blog.index', $data);
    }

    public function show($slug)
    {
        $user = null;

        $activeSubscribe = "";

        if (auth()->check()) {
            $user = auth()->user();
        }

        if (!empty($slug)) {
            $post = Blog::where('slug', $slug)
                ->where('status', 'publish')
                ->with([
                    'category',
                    'author' => function ($query) {
                        $query->select('id', 'full_name', 'role_id', 'avatar', 'role_name');
                        $query->with('role');
                    },
                     'reviews' => function ($query) {
                        $query->where('status', 'active');
                        $query->with([
                            // 'comments' => function ($query) {
                            //     $query->where('status', 'active');
                            // },
                            'creator' => function ($qu) {
                                $qu->select('id', 'full_name', 'avatar');
                            }
                        ]);
                    },
                    'comments' => function ($query) {
                        $query->where('status', 'active');
                        $query->whereNull('reply_id');
                        $query->with([
                            'user' => function ($query) {
                                $query->select('id', 'full_name', 'avatar', 'avatar_settings', 'role_id', 'role_name');
                            },
                            'replies' => function ($query) {
                                $query->where('status', 'active');
                                $query->with([
                                    'user' => function ($query) {
                                        $query->select('id', 'full_name', 'avatar', 'avatar_settings', 'role_id', 'role_name');
                                    }
                                ]);
                            }
                        ]);
                    }])
                ->first();

            if (!empty($post)) {
                $post->update(['visit_count' => $post->visit_count + 1]);

                $blogCategories = BlogCategory::all();
                $popularPosts = $this->getPopularPosts($user);

                $pageRobot = getPageRobot('blog');

                $data = [
                    'pageTitle' => $post->title,
                    'pageDescription' => $post->meta_description,
                    'pageMetaImage' => $post->image,
                    'blogCategories' => $blogCategories,
                    'popularPosts' => $popularPosts,
                    'pageRobot' => $pageRobot,
                    'post' => $post
                ];

                return view(getTemplate() . '.blog.show', $data);
            }
            if (!empty($translate)) {
                app()->setLocale($translate->locale);


            }
        }

        abort(404);
    }

    private function getPopularPosts($user = null)
    {
        $query = Blog::where('blog.status', 'publish')
            ->with(['category', 'author:id,full_name,avatar'])
            ->withCount('comments');

        // $user = User::find($userid);

        // dd($user->role->caption);

        // Check if user is Wisdom Keeper
        $isWisdomKeeper = $user && $user->role->caption === 'Wisdom Keeper';

        if ($isWisdomKeeper) {
            // Get all Wisdom Keeper user IDs for filtering
            $wisdomKeeperIds = User::whereHas('role', function($q) {
                $q->where('caption', 'Wisdom Keeper');
            })->pluck('id')->toArray();

            // Wisdom Keeper logic: show only Wisdom Keeper created articles, sorted by highest reviews first
            return $query->whereIn('blog.author_id', $wisdomKeeperIds)
                ->leftJoin('article_reviews', 'blog.id', '=', 'article_reviews.article_id')
                ->leftJoin('article_like', 'blog.id', '=', 'article_like.article_id')
                ->select('blog.*',
                    DB::raw('COUNT(DISTINCT article_like.id) as likes_count'),
                    DB::raw('AVG(article_reviews.rates) as avg_rating'),
                    DB::raw('COUNT(DISTINCT article_reviews.id) as review_count'),
                    DB::raw('COUNT(DISTINCT comments.id) as comments_count')
                )
                ->leftJoin('comments', function($join) {
                    $join->on('blog.id', '=', 'comments.blog_id')
                        ->where('comments.status', 'active');
                })
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
                    -- Then by comments
                    COUNT(DISTINCT comments.id) DESC,
                    -- Finally by created date
                    blog.created_at DESC
                ')
                ->limit(5)
                ->get()
                ->map(function ($article) {
                    $article->image = !empty($article->image) ? url($article->image) : null;
                    if ($article->author && !empty($article->author->avatar)) {
                        $article->author->avatar = url($article->author->avatar);
                    }
                    return $article;
                });
        } else {
            // Guest/normal user logic: prioritize by engagement metrics
            return $query->select('blog.*')
                ->selectRaw('
                    (SELECT COUNT(*) FROM article_like 
                    WHERE article_like.article_id = blog.id
                    ) as likes_count
                ')
                ->selectRaw('
                    (SELECT COUNT(*) FROM article_reviews 
                    WHERE article_reviews.article_id = blog.id
                    ) as reviews_count
                ')
                ->selectRaw('
                    (SELECT COALESCE(AVG(rates), 0) FROM article_reviews 
                    WHERE article_reviews.article_id = blog.id
                    ) as avg_rating
                ')
                ->selectRaw('
                    (SELECT COUNT(*) FROM comments 
                    WHERE comments.blog_id = blog.id 
                    AND comments.status = "active"
                    ) as comments_count
                ')
                ->orderByRaw('
                    -- First by average rating
                    (SELECT COALESCE(AVG(rates), 0) FROM article_reviews 
                    WHERE article_reviews.article_id = blog.id) DESC,
                    -- Then by engagement score (likes, reviews, comments, visits)
                    (
                        (SELECT COUNT(*) FROM article_like 
                        WHERE article_like.article_id = blog.id) * 0.3 +
                        (SELECT COUNT(*) FROM article_reviews 
                        WHERE article_reviews.article_id = blog.id) * 0.25 +
                        (SELECT COUNT(*) FROM comments 
                        WHERE comments.blog_id = blog.id 
                        AND comments.status = "active") * 0.25 +
                        blog.visit_count * 0.2
                    ) DESC,
                    -- Finally by created date
                    blog.created_at DESC
                ')
                ->limit(5)
                ->get()
                ->map(function ($article) {
                    $article->image = !empty($article->image) ? url($article->image) : null;
                    if ($article->author && !empty($article->author->avatar)) {
                        $article->author->avatar = url($article->author->avatar);
                    }
                    return $article;
                });
        }
    }

    // private function getPopularPosts()
    // {
    //     return Blog::where('status', 'publish')
    //         ->orderBy('visit_count', 'desc')
    //         ->limit(5)
    //         ->get();
    // }
}
