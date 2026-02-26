<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Api\Objects\UserObj;
use App\Http\Controllers\Controller;
use App\Models\Api\Blog;
use App\Models\BlogCategory;
use App\Models\Comment;
use App\Models\Reward;
use App\Models\ArticleSave;
use App\Models\RewardAccounting;
use App\Models\Translation\BlogTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
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
            // Log the error if needed
            // \Log::error('Token validation failed: ' . $e->getMessage());
            return null;
        }
    }

    public function index(Request $request)
    {
        // $user = auth('api')->user();
        // if($user)
        // {
        //     $userid = $user->id;
        // }
        // else
        // {
        //     $userid = null;
        // }

        $userid = $this->getUserIdFromToken($request);
       
        $searchQuery = $request->query('title');
        $page = (int)$request->query('offset', 0); // offset is actually page number
        $perPage = (int)$request->query('limit', 10);

        $actualOffset = $page * $perPage;

        $category = $request->query('category');

        $popularPosts = $this->getPopularPosts();

        $popularPosts = $popularPosts->map(function ($blog) {
            return $blog->details;
        });

        // $blogQuery = Blog::when(!empty($searchQuery), function ($query) use ($searchQuery) {
        //     $query->whereHas('translations', function ($subQuery) use ($searchQuery) {
        //         $subQuery->where('title', 'like', '%' . $searchQuery . '%')
        //             ->where('locale', 'en'); // Replace 'en' with your desired locale
        //     });
        // }, function ($query) {
        //     // Apply handleFilters when searchQuery is empty
        //     $query->handleFilters();
        // })
        // ->with([
        //     "badges" => function ($query) {
        //         $query->where('targetable_type', 'App\Models\Blog')
        //             ->with([
        //                 'badge' => function ($query) {
        //                     $time = time();
        //                     $query->where('enable', true)
        //                         ->where(function ($query) use ($time) {
        //                             $query->whereNull('start_at')
        //                                 ->orWhere('start_at', '<', $time);
        //                         })
        //                         ->where(function ($query) use ($time) {
        //                             $query->whereNull('end_at')
        //                                 ->orWhere('end_at', '>', $time);
        //                         });
        //                 }
        //             ]);
        //     },
        // ])
        // ->where('status', 'publish')
        // ->orderBy('updated_at', 'desc')
        // ->orderBy('created_at', 'desc');
        // dd($category);
        $blogQuery = Blog::with([
            "badges" => function ($query) {
                $query->where('targetable_type', 'App\Models\Blog')
                    ->with([
                        'badge' => function ($query) {
                            $time = time();
                            $query->where('enable', true)
                                ->where(function ($query) use ($time) {
                                    $query->whereNull('start_at')
                                        ->orWhere('start_at', '<', $time);
                                })
                                ->where(function ($query) use ($time) {
                                    $query->whereNull('end_at')
                                        ->orWhere('end_at', '>', $time);
                                });
                        }
                    ]);
            },
        ])
        ->where('status', 'publish')
        ->when(!empty($category), function ($q) use ($category) {
            $q->where('category_id', $category);
        });

        // Apply search filter
        if (!empty($searchQuery)) {
            $blogQuery->whereHas('translations', function ($subQuery) use ($searchQuery, $category) {
                $subQuery->where('title', 'like', '%' . $searchQuery . '%')
                    ->where('locale', 'en');
            });
        } else {
            // Apply handleFilters when searchQuery is empty
            $blogQuery = $this->handleFilters($request, $blogQuery);
        }

        $userEngagement  = [];
        if ($userid) {
            // Get user's like statistics by blog category
            $userEngagement = $this->getUserBlogEngagementByCategory($userid);

                \Log::info('User engagement data:', [
                'user_id' => $userid,
                'engagement' => $userEngagement,
                'category_ids' => array_keys($userEngagement)
            ]);
            
            if (!empty($userEngagement)) {
                // Sort by likes (highest first)
                uasort($userEngagement, function($a, $b) {
                    return $b['likes'] <=> $a['likes'];
                });
                
                $sortedCategories = array_keys($userEngagement);
                
                \Log::info('Sorted categories:', [
                    'categories' => $sortedCategories,
                    'with_counts' => array_map(function($catId) use ($userEngagement) {
                        return [
                            'category_id' => $catId,
                            'likes' => $userEngagement[$catId]['likes']
                        ];
                    }, $sortedCategories)
                ]);
                
                // Build CASE statement
                $caseStatements = [];
                foreach ($sortedCategories as $index => $categoryId) {
                    $caseStatements[] = "WHEN category_id = {$categoryId} THEN {$index}";
                }
                
                $caseStatements[] = "WHEN category_id IS NOT NULL THEN " . count($sortedCategories);
                $caseStatements[] = "WHEN category_id IS NULL THEN " . (count($sortedCategories) + 1);
                
                $caseSql = "CASE " . implode(' ', $caseStatements) . " END";
                
                \Log::info('CASE SQL:', ['sql' => $caseSql]);
                
                $blogQuery->orderByRaw($caseSql);
            }
            
            // Secondary ordering
            $blogQuery->orderBy('updated_at', 'desc')
                    ->orderBy('created_at', 'desc');
        } else {
            // Default ordering for non-logged in users
            $blogQuery->orderBy('updated_at', 'desc')
                     ->orderBy('created_at', 'desc');
        }

        $sql = $blogQuery->toSql();
        $bindings = $blogQuery->getBindings();
        
        \Log::info('Final Query:', [
            'sql' => $sql,
            'bindings' => $bindings
        ]);

        $total = $blogQuery->count();

        $paginatedBlogs = $blogQuery->skip($actualOffset)->take($perPage)->get();

        $transformedBlogs = $paginatedBlogs->map(function ($blog) {
            return $blog->details;
        });

        $nextPage = null;
        if (($actualOffset + $paginatedBlogs->count()) < $total) {
            $nextPage = $page + 1;
        }

        $nextPage = null;

        if (($actualOffset + $paginatedBlogs->count()) < $total) {
            $nextPage = $page + 1;
        }

        // ->get()
        // ->map(function ($blog) {
        //     return $blog->details;
        // });
        
        $data = [];
        $data['blog'] = $transformedBlogs;
        $data['popular_posts'] = $popularPosts;
        $data['pagination'] = [
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'has_more' => ($actualOffset + $paginatedBlogs->count()) < $total,
            'next_page' => $nextPage
        ];


        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $data);
    }
    
    public function show(Request $request, $id)
    {
        $userid = $this->getUserIdFromToken($request);

        $page = (int) $request->query('offset', 0); // page number
        $perPage = (int) $request->query('limit', 10);
        $actualOffset = $page * $perPage;

        $selectedBlog = Blog::where('id', $id)->first();
        abort_unless($selectedBlog, 404);

        $userLikesByCategory = [];
        if ($userid) {
            $userLikesByCategory = $this->getUserBlogEngagementByCategory($userid);
        }

        $blogQuery = Blog::with([
            "badges" => function ($query) {
                $query->where('targetable_type', 'App\Models\Blog')
                    ->with([
                        'badge' => function ($query) {
                            $time = time();
                            $query->where('enable', true)
                                ->where(function ($query) use ($time) {
                                    $query->whereNull('start_at')
                                        ->orWhere('start_at', '<', $time);
                                })
                                ->where(function ($query) use ($time) {
                                    $query->whereNull('end_at')
                                        ->orWhere('end_at', '>', $time);
                                });
                        }
                    ]);
            },
            "reviews" => function ($query) {
                $query->with('user'); 
            },
        ])
        ->where('status', 'publish')
        ->where('id', '!=', $id);
        // ->orderBy('updated_at', 'desc')
        // ->orderBy('created_at', 'desc');

        if ($userid && !empty($userLikesByCategory)) {
            // Sort categories by like count (highest first)
            uasort($userLikesByCategory, function($a, $b) {
                return $b['likes'] <=> $a['likes'];
            });

            // Get selected blog's category
            $selectedCategoryId = $selectedBlog->category_id;
            
            // Get sorted category IDs
            $sortedCategoryIds = array_column($userLikesByCategory, 'category_id');
            
            $selectedCategoryIndex = array_search($selectedCategoryId, $sortedCategoryIds);
        
            if ($selectedCategoryIndex !== false) {
                // Selected category is already in user's liked categories
                // Keep it in its position based on like count
                // No need to move it to front
            } else {
                // Selected category is not in user's liked categories
                // Add it based on its position relative to other categories
                // Find where to insert it based on available data
                
                // Get selected category's like count if it exists in database
                $selectedCategoryLikes = DB::table('article_like')
                    ->join('blog', 'article_like.article_id', '=', 'blog.id')
                    ->where('article_like.user_id', $userid)
                    ->where('blog.category_id', $selectedCategoryId)
                    ->count();
                
                // Insert selected category at appropriate position
                $inserted = false;
                foreach ($userLikesByCategory as $category) {
                    if ($selectedCategoryLikes >= $category['likes']) {
                        // Insert before this category
                        $index = array_search($category['category_id'], $sortedCategoryIds);
                        array_splice($sortedCategoryIds, $index, 0, $selectedCategoryId);
                        $inserted = true;
                        break;
                    }
                }
                
                if (!$inserted) {
                    // Add at the end
                    $sortedCategoryIds[] = $selectedCategoryId;
                }
            }
            
            // Build CASE statement for ordering
            $caseStatements = [];
            foreach ($sortedCategoryIds as $index => $categoryId) {
                $caseStatements[] = "WHEN category_id = {$categoryId} THEN {$index}";
            }
            
            $caseStatements[] = "WHEN category_id IS NOT NULL THEN " . count($sortedCategoryIds);
            $caseStatements[] = "WHEN category_id IS NULL THEN " . (count($sortedCategoryIds) + 1);
            
            $caseSql = "CASE " . implode(' ', $caseStatements) . " END";
            
            $blogQuery->orderByRaw($caseSql);
        }
        else {
            $blogQuery->orderBy('updated_at', 'desc')
                    ->orderBy('created_at', 'desc');
        }
        
        // Secondary ordering
        $blogQuery->orderBy('updated_at', 'desc')
                  ->orderBy('created_at', 'desc');

        $total = $blogQuery->count() + 1;

        $blogs = collect();
        
        // if ($page === 0) {
        //     $blogs->push(
        //         array_merge(
        //             $selectedBlog->details,
        //             ['is_selected' => true]
        //         )
        //     );

        //     $perPage--; // reduce slot since selected blog is added
        // }

        if ($page === 0) {
            $blogDetails = array_merge(
                $selectedBlog->details,
                ['is_selected' => true]
            );
            
            // Add engagement analysis if available
            if ($userid && isset($userLikesByCategory[$selectedBlog->category_id])) {
                $blogDetails['engagement_analysis'] = [
                    'category_id' => $selectedBlog->category_id,
                    'user_likes_in_category' => $userLikesByCategory[$selectedBlog->category_id]['likes'],
                    'engagement_level' => $userLikesByCategory[$selectedBlog->category_id]['level'] ?? 'low_engagement',
                ];
            }
            
            $blogs->push($blogDetails);
            $perPage--; // reduce slot since selected blog is added
        }

        $paginatedBlogs = $blogQuery
            ->skip($actualOffset)
            ->take($perPage)
            ->get()
        ->map(function ($blog) use ($id, $userid, $userLikesByCategory) {
            $details = $blog->details;
            $details['is_selected'] = false;
            
            // Add engagement analysis if available
            if ($userid && isset($userLikesByCategory[$blog->category_id])) {
                $details['engagement_analysis'] = [
                    'category_id' => $blog->category_id,
                    'user_likes_in_category' => $userLikesByCategory[$blog->category_id]['likes'],
                    'engagement_level' => $userLikesByCategory[$blog->category_id]['level'] ?? 'low_engagement',
                ];
            }
            
            return $details;
        });

        $blogs = $blogs->merge($paginatedBlogs);
        
        $hasMore = ($actualOffset + $paginatedBlogs->count()) < ($total - 1);
        $nextPage = $hasMore ? $page + 1 : null;
        
        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), [
            'blog' => $blogs,
            'pagination' => [
                'current_page' => $page,
                'per_page' => (int) $request->query('limit', 10),
                'total' => $total,
                'has_more' => $hasMore,
                'next_page' => $nextPage
            ]
        ]);

        // $blog = Blog::find($id);
        // abort_unless($blog, 404);

        // return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), [
        //     'blog' => $blog->details
        // ]);
    }

    private function getUserBlogEngagementByCategory($userId)
    {
        $result = DB::table('article_like')
            ->join('blog', 'article_like.article_id', '=', 'blog.id')
            ->where('article_like.user_id', $userId)
            ->whereNotNull('blog.category_id')
            ->select(
                'blog.category_id',
                DB::raw('COUNT(*) as likes')
            )
            ->groupBy('blog.category_id')
            ->orderByDesc('likes')
            ->get();
        
        \Log::info('Final aggregated result:', [
            'result' => $result->toArray()
        ]);
        
        return $result->mapWithKeys(function($item) {
            return [$item->category_id => [
                'category_id' => $item->category_id,
                'likes' => (int)$item->likes,
                'level' => $this->getBlogEngagementLevel($item->likes)
            ]];
        })
        ->toArray();
    }

    /**
     * Determine blog engagement level based on like count
     */
    private function getBlogEngagementLevel($likeCount)
    {
        if ($likeCount >= 9) {
            return 'deep_interest';
        } elseif ($likeCount >= 6) {
            return 'high_engagement';
        } elseif ($likeCount >= 3) {
            return 'medium_engagement';
        } else {
            return 'low_engagement';
        }
    }

    private function getPopularPosts()
    {
        return Blog::where('status', 'publish')
            ->orderBy('visit_count', 'desc')
            ->limit(5)
            ->get();
    }

    public function list(Request $request, $id = null)
    {

        $query = Blog::where('status', 'publish')
            ->orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc');

        if (isset($id)) {
            $query = $query->where('id', $id)->get();
            if (!$query->count()) {
                abort(404);
            }
            $blogs = self::details($query, true);
        } else {
            $query = $this->handleFilters($request, $query);
            $blogs = self::details($query->get());
        }

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $blogs);
    }

    public function handleFilters($request, $query)
    {
        $offset = $request->get('offset', null);
        $limit = $request->get('limit', null);

        if (!empty($offset) && !empty($limit)) {
            $query->skip($offset);
        }
        if (!empty($limit)) {
            $query->take($limit);
        }
        return $query;
    }

    public static function details($blogs, $single = false)
    {
        $blogs = $blogs->map(function ($blog) {

            return [
                'id' => $blog->id,
                'title' => $blog->title,
                'image' => url($blog->image),
                'description' => truncate($blog->description, 160),
                'content' => $blog->content,
                'created_at' => $blog->created_at,
                'author' => UserObj::brief($blog->author, true),
                'like_count' => $blog->like->count(),
                'share_count' => $blog->share->count(),
                'gift_count' => $blog->gift->count(),
                'comment_count' => $blog->comments->count(),
                'comments' => $blog->comments->map(function ($item) {
                    return [
                        'user' => [
                            'full_name' => $item->user->full_name,
                            'avatar' => url($item->user->getAvatar()),
                        ],
                        'create_at' => $item->created_at,
                        'comment' => $item->comment,
                        'replies' => $item->replies->map(function ($reply) {
                            return [
                                'user' => [
                                    'full_name' => $reply->user->full_name,
                                    'avatar' => url($reply->user->getAvatar()),
                                ],
                                'create_at' => $reply->created_at,
                                'comment' => $reply->comment,
                            ];
                        })
                    ];
                }),
                'category' => $blog->category->title,
            ];
        });

        if ($single) {
            return [
                'blog' => $blogs->first()
            ];
        }
        return [
            'count' => count($blogs),
            'blogs' => $blogs
        ];
    }

    public function blogcategory(){

        $categories = BlogCategory::all()->map(function($category){
            return $category->details;
        });

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'),$categories);
    }

    public function store(Request $request)
    {
        try {
            // Get user ID from token
            $userid = $this->getUserIdFromToken($request);

            $user = auth('api')->user();
            
            if (!$userid) {
                return apiResponse2(0, 'unauthorized', trans('api.auth.unauthorized'), null, 401);
            }
            
            // Validate required fields
            $validator = \Validator::make($request->all(), [
                'locale' => 'required|string',
                'title' => 'required|string|max:255',
                'category_id' => 'required|integer|exists:blog_categories,id', // Adjust table name as needed
                // 'image' => 'required|string', // Assuming image is base64 or URL
                'description' => 'required|string',
                'content' => 'required|string',
            ]);
            
            if ($validator->fails()) {
                return apiResponse2(0, 'validation_error', trans('api.public.validation_error'), [
                    'errors' => $validator->errors()
                ], 422);
            }

            $filename = '';
            $video = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();
            $videoPath = public_path('store/articles');
            if (!file_exists($videoPath)) {
                mkdir($videoPath, 0777, true);
            }
            $video->move($videoPath, $filename);
            
            $data = $request->all();
            
            // Check if direct publication is enabled
            $directPublicationOfBlog = !empty(getGeneralOptionsSettings('direct_publication_of_blog'));
            
            // Create blog
            $blog = Blog::create([
                'slug' => Blog::makeSlug($data['title']),
                'category_id' => $data['category_id'],
                'author_id' => $userid,
                'image' => '/store/articles/' . $filename,
                'enable_comment' => true,
                'status' => $directPublicationOfBlog ? 'publish' : 'pending',
                'created_at' => time(),
                'updated_at' => time(),
            ]);
            
            if (!$blog) {
                return apiResponse2(0, 'creation_failed', trans('api.public.creation_failed'), null, 500);
            }
            
            BlogTranslation::updateOrCreate([
                'blog_id' => $blog->id,
                'locale' => mb_strtolower($data['locale']),
            ], [
                'title' => $data['title'],
                'description' => $data['description'],
                'meta_description' => strip_tags($data['description']),
                'content' => $data['content'],
            ]);
            
            // Handle rewards if direct publication is enabled
            if ($directPublicationOfBlog) {
                $createPostReward = RewardAccounting::calculateScore(Reward::CREATE_BLOG_BY_INSTRUCTOR);
                RewardAccounting::makeRewardAccounting($user->id, $createPostReward, Reward::CREATE_BLOG_BY_INSTRUCTOR, $blog->id, true);
            }
            
            // Send notification
            $notifyOptions = [
                '[u.name]' => $user->full_name,
                '[blog_title]' => $blog->title,
            ];
            sendNotification("new_user_blog_post", $notifyOptions, 1);

            return response()->json([
                'status' => 'success',
                'message' => 'Blog created successfully',
                'data' => $blog
            ], 201);
            
        } catch (\Exception $e) {
            \Log::error('Blog creation error: ' . $e->getMessage());
            return apiResponse2(0, 'server_error', trans('api.public.server_error'), [
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function bloglike(Request $request,$id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $blog = Blog::where('id', $id)->first();
        
        $like = DB::table('article_like')
            ->where('article_id', $blog->id)
            ->where('user_id', $userid)
            ->exists();

        if ($like) {
            DB::table('article_like')
                ->where('article_id', $blog->id)
                ->where('user_id', $userid)
                ->delete();
            
            Blog::where('id', $id)->decrement('like_count');
            //$blog->decrement('like_count');
            $action = 'unliked';
        } else {
            DB::table('article_like')->insert([
                'user_id' => $userid,
                'article_id' => $blog->id
            ]);
            
            Blog::where('id', $id)->increment('like_count');
            //$blog->increment('like_count');
            $action = 'liked';
        }

        return response()->json([
            'status' => 'success',
            'message' => "Blog {$action} successfully",
            'data' => [
                'liked' => !$blog,
                'like_count' => $blog->like_count
            ]
        ]);
    }

    public function blogshare(Request $request,$id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $blog = Blog::where('id', $id)->first();

        $now = time();

        $share = $blog->share()->create([
            'user_id' => $userid,
            'article_id' => $blog->id,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        Blog::where('id', $id)->increment('share_count');
        //$blog->increment('share_count');
        return response()->json([
            'status' => 'success',
            'message' => 'Blog Shared successfully',
            'data' => $share
        ], 201);
    }

    public function bloggift(Request $request,$id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $blog = Blog::where('id', $id)->first();
        // print_r($blog);exit;

        $now = time();

        $gift = $blog->gift()->create([
            'user_id' => $userid,
            'article_id' => $blog->id,
            'gift_id' => $request->gift_id, 
            'created_at' => $now,
            'updated_at' => $now
        ]);

        Blog::where('id', $id)->increment('gift_count');
        //$blog->increment('gift_count');
        
        return response()->json([
            'status' => 'success',
            'message' => 'Gift Send successfully',
            'data' => $gift
        ], 201);
    }

    public function blogreport(Request $request, $id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $blog = Blog::where('id', $id)->first();

        $now = time();

        $report = $blog->reports()->create([
            'user_id' => $userid,
            'article_id' => $blog->id,
            'reason' => $request->reason,
            'description' => $request->description,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        Blog::where('id', $id)->increment('report_count');

        return response()->json([
            'status' => 'success',
            'message' => 'Article reported successfully',
            'data' => $report
        ], 201);
    }

    public function blogsave(Request $request,$id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $blog = Blog::where('id', $id)->first();

        if (!$blog) {
            return response()->json([
                'status' => 'error',
                'message' => 'Blog not found'
            ], 404);
        }

        $now = time();

        $save = DB::table('article_saved')
            ->where('article_id', $blog->id)
            ->where('user_id', $userid)
            ->exists();

        if ($save) {
            DB::table('article_saved')
            ->where('article_id', $blog->id)
            ->where('user_id', $userid)
            ->delete(); 

            Blog::where('id', $id)->decrement('saved_count');
            $action = 'unsaved';
        } else {
            DB::table('article_saved')->insert([
                'user_id' => $userid,
                'article_id' => $blog->id,
                'created_at' => $now,
                'updated_at' => $now
            ]);
            
            Blog::where('id', $id)->increment('saved_count');
            $action = 'saved';
        }
        
        return response()->json([
            'status' => 'success',
            'message' => "Blog {$action} successfully",
            'data' => [
                'saved' => !$save,
                'saved_count' => $blog->saved_count
            ]
        ], 201);
    }

    public function blogreview(Request $request, $id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $now = time();

        $blog = Blog::where('id', $id)->first();

        $review = $blog->reviews()->create([
            'creator_id' => $userid,
            'article_id' => $blog->id,
            'description' => $request->review,
            'rates' => $request->rating,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $blog->increment('review_count');

        return response()->json([
            'status' => 'success',
            'message' => 'Review added successfully',
            'data' => [
                'id' => $review->id,
                'user_id' => $review->creator_id,
                'article_id' => $review->article_id,
                'review' => $review->description,
                'rating' => $review->rates,
                'created_at' => $review->created_at, // Convert to timestamp
                'username' => $user->full_name,
                'avatar' => $user ? url($user->getAvatar()) : '',
            ]
            // 'data' => $comment->load('user')
        ], 201);
    }

    public function blogcomment(Request $request,$id)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        $blog = Blog::where('id', $id)->first();

        $now = time();
        $comment = $blog->comments()->create([
            'user_id' => $userid,
            'comment' => $request->get('content'),
            'status' => 'active',
            'created_at' => $now,
            'updated_at' => $now
        ]);

        Blog::where('id', $id)->increment('comments_count');
        //$blog->increment('comments_count');

        $comment->load(['user', 'blog.author', 'blog.category']);

        // Format the response to match your example structure
        $formattedComment = [
            'id' => $comment->id,
            'status' => $comment->status,
            'comment_user_type' => 'student', // You might want to get this from user role
            'create_at' => $comment->created_at,
            'comment' => $comment->comment,
            'blog' => [
                'id' => $blog->id,
                'title' => $blog->title,
                'image' => $blog->image,
                'description' => $blog->description,
                'created_at' => $blog->created_at,
                'author' => [
                    'id' => $blog->author->id,
                    'full_name' => $blog->author->full_name,
                    'role_name' => $blog->author->role_name,
                    'bio' => $blog->author->bio,
                    'email' => $blog->author->email,
                    'mobile' => $blog->author->mobile,
                    'offline' => $blog->author->offline,
                    'offline_message' => $blog->author->offline_message,
                    'verified' => $blog->author->verified,
                    'rate' => $blog->author->rate,
                    'avatar' => $blog->author->avatar,
                    'meeting_status' => $blog->author->meeting_status,
                    'user_group' => $blog->author->user_group,
                    'address' => $blog->author->address,
                    'status' => $blog->author->status,
                ],
                'comment_count' => $blog->comments_count,
                'category' => $blog->category->name ?? 'Articles' // Adjust based on your category relationship
            ],
            'user' => [
                'id' => $comment->user->id,
                'full_name' => $comment->user->full_name,
                'role_name' => $comment->user->role_name,
                'bio' => $comment->user->bio,
                'email' => $comment->user->email,
                'mobile' => $comment->user->mobile,
                'offline' => $comment->user->offline,
                'offline_message' => $comment->user->offline_message,
                'verified' => $comment->user->verified,
                'rate' => $comment->user->rate,
                'avatar' => $comment->user->avatar,
                'meeting_status' => $comment->user->meeting_status,
                'user_group' => $comment->user->user_group,
                'address' => $comment->user->address,
                'status' => $comment->user->status,
            ],
            'webinar' => null,
            'product' => null,
            'replies' => []
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Comment added successfully',
            'data' => $formattedComment
        ], 201);
    }
}
