<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Translation\BlogTranslation;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
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

        $popularPosts = $this->getPopularPosts();

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
                $popularPosts = $this->getPopularPosts();

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

    private function getPopularPosts()
    {
        return Blog::where('status', 'publish')
            ->orderBy('visit_count', 'desc')
            ->limit(5)
            ->get();
    }
}
