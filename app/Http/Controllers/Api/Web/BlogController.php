<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Api\Objects\UserObj;
use App\Http\Controllers\Controller;
use App\Models\Api\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    //blog
    public function index(Request $request)
    {
        $user = auth('api')->user();
        $userid = $user->id;
        
        $searchQuery = $request->query('title');

        $popularPosts = $this->getPopularPosts();

        $popularPosts = $popularPosts->map(function ($blog) {
            return $blog->details;
        });

        $blog = Blog::when(!empty($searchQuery), function ($query) use ($searchQuery) {
            $query->whereHas('translations', function ($subQuery) use ($searchQuery) {
                $subQuery->where('title', 'like', '%' . $searchQuery . '%')
                    ->where('locale', 'en'); // Replace 'en' with your desired locale
            });
        }, function ($query) {
            // Apply handleFilters when searchQuery is empty
            $query->handleFilters();
        })
            ->with([
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
            ->orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($blog) {
                return $blog->details;
            });
        $data = [];
        $data['blog'] = $blog;
        $data['popular_posts'] = $popularPosts;


        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $data);
    }

    private function getPopularPosts()
    {
        return Blog::where('status', 'publish')
            ->orderBy('visit_count', 'desc')
            ->limit(5)
            ->get();
    }
    
    public function show($id)
    {
        $blog = Blog::find($id);
        abort_unless($blog, 404);


        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), [
            'blog' => $blog->details
        ]);
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
